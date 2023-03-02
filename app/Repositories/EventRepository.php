<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Helpers\UploadHelper;
use App\Interfaces\CrudInterface;
use App\Models\Event;
use App\Models\Brand;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use JWTAuth;

class EventRepository implements CrudInterface
{
    /**
     * Authenticated User Instance.
     *
     * @var User
     */
    public User | null $user;

    /**
     * Constructor.
     */
    public function __construct(WalletRepository $walletRepository)
    {
        try {
            $token = JWTAuth::getToken();
            $this->user =   JWTAuth::toUser($token ) ;
        } catch (\Throwable $th) {
            return "valid token required";
        }

        $this->walletRepository = $walletRepository;

    }

    /**
     * Get All Events.
     *
     * @return collections Array of Event Collection
     */
    public function getAll(): Paginator
    {
        return Event::orderBy('id', 'desc')
            ->with('brand')
            ->with('tags')
            ->paginate(10);
    }


    public function myEvent($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;

        $brand= Brand::where('user_id', $this->user->id)->pluck('id');

        return Event::orderBy('id', 'desc')
            ->whereIn('brand_id',$brand)
            ->with('tags')
            ->with('brand')
            ->paginate($perPage);
    }

    
    public function myTicket($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        $ticket= Ticket::where('user_id', $this->user->id)->with('event')->with('user');
        //dd($this->user->id);
        return $ticket->paginate($perPage);
    }

    public function eventTicket($perPage,$id): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        $ticket= Ticket::where('event_id', $id)->with('event')->with('user');
        //dd($this->user->id);
        return $ticket->paginate($perPage);
    }

    public function eventFeatured()
    {
        return Event::where('featured',1)
            ->with('tags')
            ->with('brand')
            ->accepted()
            ->get();
            
    }

    public function eventToday()
    {
        return Event::whereDate('start',Carbon::today()->toDateString())
            ->with('brand')
            ->with('tags')
            ->accepted()
            ->get();

            
    }
    public function eventTomorrow()
    {
        return Event::whereDate('start',Carbon::Tomorrow()->toDateString())
        ->with('brand')
        ->with('tags')
        ->accepted()
        ->get();
    }
    public function eventWeekend()
    {
        $dt = Carbon::now();
        return Event::whereDate('start',$dt->next('Friday')->format('Y-m-d'))
        ->with('brand')
        ->with('tags')
        ->accepted()
        ->get();
    }
    /**
     * Get Paginated Event Data.
     *
     * @param int $pageNo
     * @return collections Array of Event Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        return Event::orderBy('id', 'desc')
            ->with('brand')
            ->with('tags')
            ->with('user')
            ->accepted()
            ->paginate($perPage);
    }

    /**
     * Get Searchable Event Data with Pagination.
     *
     * @param int $pageNo
     * @return collections Array of Event Collection
     */
    public function searchEvent($keyword,$tagId, $entry_feeLessThan,$startDate,$sort, $perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;

        $query = Event::query()->with('tags')->accepted();;

        // Filter by name
        if ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        // Filter by tag IDs

        if ($tagId && $tagId!=[0=>null]) {
            $query->whereHas('tags', function ($query) use ($tagId) {
                $query->whereIn('id', $tagId);
            });
        }
        
    

        // Filter by entry_fee less than
    
        if ($entry_feeLessThan) {
            $query->where('entry_fee', '<', $entry_feeLessThan);
        }

        if ($startDate) {
            $query->whereDate('start', Carbon::parse($startDate)->format('Y-m-d'));
        }

        if($sort == 'new'){
            $query->orderBy('id', 'ASC');
        }
        if($sort == 'fee'){
            $query->orderBy('entry_fee', 'ASC');
        }
        if($sort == 'like'){
            $query->orderBy('likes', 'ASC');
        }

        // Execute the query and paginate the results
        $events = $query->paginate($perPage);


        return $events;
    }

    /**
     * Create New Event.
     *
     * @param array $data
     * @return object Event Object
     */
    public function create(array $data): Event
    {
        $titleShort      = Str::slug(substr($data['name'], 0, 20));
        $data['user_id'] = $this->user->id;

        if (!empty($data['image'])) {
            $data['image'] = UploadHelper::upload('image', $data['image'], $titleShort . '-' . time(), 'storage');
        }


        $event = Event::create($data);
        try {
            $event->tags()->attach(json_decode($data['tags'],true));

        } catch (\Throwable $th) {
            //throw $th;
        }
        return  $event;
    }
    public function addEvent(array $data): Event
    {
        $titleShort      = Str::slug(substr($data['name'], 0, 20));
        $data['user_id'] = $this->user->id;

        if (!empty($data['image'])) {
            $data['image'] = UploadHelper::upload('image', $data['image'], $titleShort . '-' . time(), 'storage');
        }
        $event = Event::create($data);
        $event->tags()->attach(json_decode($data['tags'],true));
        return  $event;
    }


    
    /**
     * Delete Event.
     *
     * @param int $id
     * @return boolean true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $event = Event::find($id);
        if (empty($event)) {
            return false;
        }

        UploadHelper::deleteFile('images/events/' . $event->image);
        $event->delete($event);
        return true;
    }

    /**
     * Get Event Detail By ID.
     *
     * @param int $id
     * @return void
     */
    public function getByID(int $id): Event|null
    {
      
        $event = Event::with('tags')->with('brand')->find($id);

        $inWishlist = false;
        $user =$this->user ;
        if ($user) {
            $inWishlist = $user->wishlists()->where('event_id', $event->id)->exists();
        }
         

        $event->inWishlist = $inWishlist;

        return $event;
    }

    /**
     * Update Event By ID.
     *
     * @param int $id
     * @param array $data
     * @return object Updated Event Object
     */
    public function update(int $id, array $data): Event|null
    {
        $event = Event::find($id);
        if (!empty($data['image'])) {
            $titleShort = Str::slug(substr($data['title'], 0, 20));
            $data['image'] = UploadHelper::update('image', $data['image'], $titleShort . '-' . time(), 'images/events', $event->image);
        } else {
            $data['image'] = $event->image;
        }

        if (is_null($event)) {
            return null;
        }

        try {
            if(isset($data['tags'])){
                $event->tags()->sync(json_decode($data['tags'],true),true);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        // If everything is OK, then update.
        $event->update($data);

        // Finally return the updated event.
        return $event;
    }


    public function bookingEvent(array $data): Event|null|string
    {
        $event = Event::find($data['event_id']);
        if (is_null($event)) {
            return null;
        }
        if($event->ticket - $data['ticket_number'] < 0 ){
            return  null;
        }
        if ($event->is_pay) {
           $totalPrice =  (int)$event->entry_fee *(int)$data['ticket_number']  ; 
           $this->walletRepository->decreaseWallet($totalPrice,'Book a party ticket for '.$event->name);
           $event->decrement('ticket',$data['ticket_number']); 
        }else{
            
            $event->decrement('ticket',$data['ticket_number']);
        } 
        

        $tickets = $this->generateTickets( $event->id,$this->user->id,  (int)$event->entry_fee, (int)$data['ticket_number'] );
        $this->createTickets($tickets);
    
        // Finally return the updated event.
        return $event;
    }

    public function generateTickets($event_id, $user_id, $price, $quantity)
    {
        $tickets = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $ticket_number = 'T' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $ticket = [
                'event_id' => $event_id,
                'user_id' => $user_id,
                'ticket_number' => $ticket_number,
                'price' => $price
            ];
            array_push($tickets, $ticket);
        }

        return $tickets;
    }

    public function createTickets($tickets)
    {
        foreach ($tickets as $ticket) {
            $event_id = $ticket['event_id'];
            $user_id = $ticket['user_id'];
            $ticket_number = $ticket['ticket_number'];
            $price = $ticket['price'];
            Ticket::create([
                'event_id' => $event_id,
                'user_id' => $user_id,
                'ticket_number' => $ticket_number,
                'price' => $price
            ]);
        }
    }
}
