<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Brand;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;
use Twilio;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\UploadHelper;
use JWTAuth;

class AuthRepository
{

    public function __construct()
    {
        try {
          $token = JWTAuth::getToken();
            $this->user =   JWTAuth::toUser($token ) ;
        } catch (\Throwable $th) {
            //throw $th;
        }
       
    }

    public function register(array $data): User
    {
        $data = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' =>  $data['phone'],
            'password' => Hash::make($data['password'])
        ];

        return User::create($data);
    }
    public function sendcode($data): User
    {
        $new = [
            'phone' =>  $data['phone'],
            'code' => rand(1000,9999),
        ];
        $user= User::where('phone',$data['phone'])->first();
        $user->update($new);
        try {
            Twilio::message('+'.$user->phone, $user->code);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $user;
     
    }

    public function updateLocation($data): User
    {
        $new = [
            'phone' =>  $data['phone'],
            'lat'   =>  $data['lat'],
            'lng'   =>  $data['lng'],
        ];
        $user= User::where('phone',$data['phone'])->first();
        $user->update($new);
        return $user;
    }

    public function updateUserInfo($data): User
    {
        $new = [
            'phone' =>  $data['phone'],
            'lat'   =>  $data['lat'],
            'lng'   =>  $data['lng'],
        ];
        $user= User::where('phone',$data['phone'])->first();
        $user->update($new);
        return $user;
    }
    
    public function sendcodeFirst($data): User
    {
        $data = [
            'phone' =>  $data['phone'],
            'code' => rand(1000,9999),
            'email' =>  $data['email'],
            'name' =>  $data['name'],

        ];
        $user= User::create($data);
        try {
            Twilio::message('+'.$user->phone, $user->code);
        } catch (\Throwable $th) {
            //throw $th;
        }
        //Twilio::message('+'.$user->phone, $user->code);
        return $user;
      
     
    }
    public function following($id)
    {
        try {
            $this->user->increment('following');
            $userOther = User::find($id);
            $userOther->increment('follower');
            $this->user->following()->attach($id);
            return 'Ok';
        } catch (\Throwable $th) {
            return 'following befor';
        }

    }

    public function unfollowing($id)
    {
        try {
            $this->user->decrement('following');
            $userOther = User::find($id);
            $userOther->decrement('follower');
            $this->user->following()->detach($id);
            return 'Ok';
        } catch (\Throwable $th) {
            return 'following befor';
        }
    }
    
    public function addWishlistsEvent($id)
    {
        try {
            $event = Event::find($id);
            $event->increment('likes');
            $this->user->wishlists()->attach($id);
            return 'Ok';
        } catch (\Throwable $th) {
            return 'addWishlistsEvent befor';
        }

    }
    public function removeWishlistsEvent($id)
    {
        try {
            $event = Event::find($id);
            $event->decrement('likes');
         
            $this->user->wishlists()->detach($id);
            return 'Ok';
        } catch (\Throwable $th) {
            return 'removeWishlistsEvent befor';
        }

    }
    public function addWishlistsBrand($id)
    {
        try {
            $event = Brand::find($id);
            $event->increment('likes');
            $this->user->wishlists_brands()->attach($id);
            return 'Ok';
        } catch (\Throwable $th) {
            return 'addWishlitssBrand befor';
        }

    }
    public function removeWishlistBrand($id)
    {
        try {
            $event = Brand::find($id);
            $event->decrement('likes');
            $this->user->wishlists_brands()->detach($id);
            return 'Ok';
        } catch (\Throwable $th) {
            return 'removeWishlistsBrand befor';
        }

    }
    public function loginCheck(array $data)
    {
        $user= User::where('phone', $data['phone'])->where('code', $data['code']);
        if($user->first()){
            $data = [
                'phone_verified_at' => Carbon::today(),
                'is_valid' => 1
            ];
            $user->update($data);
            return $user->first();
        }
        else return null;

    }

    public function topHunters()
    {

        $users = User::with('followers')->paginate(10);
        $following =  User::find($this->user->id)->following()->pluck('id');
        $users->getCollection()->transform(function($user) use ($following) {
            $user->is_following = $following->contains($user->id);
            return $user;
        });

        return $users;
    }

    public function popularHunters(): Paginator
    {
        return User::orderBy('id', 'desc')
            ->paginate(10);
    }
    public function hunters(): Paginator
    {
        return User::orderBy('id', 'desc')
            ->paginate(10);
    }
    public function infoHunters($id): User
    {
        $user= User::with('wishlists_brands')->with('wallet')->with('following')->with('followers')->find($id);

        $user->wishlists()->with('event.tags')->get();
        return  $user;
    }

    public function update(int $id, array $data): User|null
    {
        $user = User::find($id);
        if (!empty($data['image'])) {
            $titleShort ='pic';
            $data['image'] = UploadHelper::update('image', $data['image'], $titleShort . '-' . time(), 'images/users', $user->image);
        } else {
            $data['image'] = $user->image;
        }

        if (is_null($user)) {
            return null;
        }

        // If everything is OK, then update.
        $user->update($data);

        // Finally return the updated user.
        return $this->getByID($user->id);
    }
    public function getByID(int $id): User|null
    {
        return User::find($id);
    }
}
