<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Helpers\UploadHelper;
use App\Interfaces\CrudInterface;
use App\Models\Card;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use JWTAuth;

class CardRepository implements CrudInterface
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
     * Get All Cards.
     *
     * @return collections Array of Card Collection
     */
    public function getAll(): Paginator
    {
        return Card::orderBy('id', 'desc')
            ->paginate(10);
    }

    /**
     * Get Paginated Card Data.
     *
     * @param int $pageNo
     * @return collections Array of Card Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        return Card::orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get Searchable Card Data with Pagination.
     *
     * @param int $pageNo
     * @return collections Array of Card Collection
     */
    public function searchCard($keyword, $perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;

        return Card::where('title', 'like', '%' . $keyword . '%')
            ->orWhere('description', 'like', '%' . $keyword . '%')
            ->orWhere('price', 'like', '%' . $keyword . '%')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Create New Card.
     *
     * @param array $data
     * @return object Card Object
     */
    public function create(array $data): Card
    {
     
        $data['user_id'] = $this->user->id;



        return Card::create($data);
    }

    /**
     * Delete Card.
     *
     * @param int $id
     * @return boolean true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $card = Card::find($id);
        if (empty($card)) {
            return false;
        }

        UploadHelper::deleteFile('images/cards/' . $card->image);
        $card->delete($card);
        return true;
    }

    /**
     * Get Card Detail By ID.
     *
     * @param int $id
     * @return void
     */
    public function getByID(int $id): Card|null
    {
        return Card::find($id);
    }

    /**
     * Update Card By ID.
     *
     * @param int $id
     * @param array $data
     * @return object Updated Card Object
     */
    public function update(int $id, array $data): Card|null
    {
        $card = Card::find($id);
        if (!empty($data['image'])) {
            $titleShort = Str::slug(substr($data['title'], 0, 20));
            $data['image'] = UploadHelper::update('image', $data['image'], $titleShort . '-' . time(), 'images/cards', $card->image);
        } else {
            $data['image'] = $card->image;
        }

        if (is_null($card)) {
            return null;
        }

        // If everything is OK, then update.
        $card->update($data);

        // Finally return the updated card.
        return $this->getByID($card->id);
    }

    public function chargeCard(array $data) : Card|null
    {
        $card = Card::where('number', '=',$data['number'])->first();
        if (is_null($card)||$card->status !=true) {
            return null;
        }
        $this->walletRepository->increaseWallet($card->balance,'Charge card number '.$data['number']);
        $card->update(['used_at'=>Carbon::now(),'status'=>false]);
        return $this->getByID($card->id);
    }
}
