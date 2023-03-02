<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Helpers\UploadHelper;
use App\Interfaces\CrudInterface;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class WalletRepository implements CrudInterface
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
    public function __construct()
    {
        try {
            $token = JWTAuth::getToken();
            $this->user =   JWTAuth::toUser($token ) ;
        } catch (\Throwable $th) {
            return "valid token required";
        }
    }

    /**
     * Get All Wallets.
     *
     * @return collections Array of Wallet Collection
     */
    public function getAll(): Paginator
    {
        return Wallet::
            orderBy('id', 'desc')
            ->with('transactions')
            ->paginate(10);
    }

    /**
     * Get Paginated Wallet Data.
     *
     * @param int $pageNo
     * @return collections Array of Wallet Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        return Wallet::orderBy('id', 'desc')
            ->with('transactions')
            ->paginate($perPage);
    }

    /**
     * Get Searchable Wallet Data with Pagination.
     *
     * @param int $pageNo
     * @return collections Array of Wallet Collection
     */
    public function searchWallet($keyword, $perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;

        return Wallet::where('title', 'like', '%' . $keyword . '%')
            ->orWhere('description', 'like', '%' . $keyword . '%')
            ->orWhere('price', 'like', '%' . $keyword . '%')
            ->orderBy('id', 'desc')
            ->with('user')
            ->paginate($perPage);
    }

    /**
     * Create New Wallet.
     *
     * @param array $data
     * @return object Wallet Object
     */
    public function create(array $data): Wallet
    {
        $data['user_id'] = $this->user->id;
        return Wallet::create($data);
    }

    /**
     * Delete Wallet.
     *
     * @param int $id
     * @return boolean true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $wallet = Wallet::find($id);
        if (empty($wallet)) {
            return false;
        }

        UploadHelper::deleteFile('images/wallets/' . $wallet->image);
        $wallet->delete($wallet);
        return true;
    }

    /**
     * Get Wallet Detail By ID.
     *
     * @param int $id
     * @return void
     */
    public function getByID(int $id): Wallet|null
    {
        return Wallet::with('transactions')->find($id);
    }

    public function infoWallet() 
    {
        $user=  User::with('wallet')->find($this->user->id) ;
        if($id = $user->wallet->id){
        return Wallet::with('transactions')->find($id);
         }else

         return "user dont have wallet yet";
        
    }

    /**
     * Update Wallet By ID.
     *
     * @param int $id
     * @param array $data
     * @return object Updated Wallet Object
     */
    public function update(int $id, array $data): Wallet|null
    {
        $wallet = Wallet::find($id);

        if (is_null($wallet)) {
            return null;
        }

        // If everything is OK, then update.
        $wallet->update($data);

        // Finally return the updated wallet.
        return $this->getByID($wallet->id);
    }


    public function increaseWallet(int $amount,$desc) 
    {
        $user=  User::with('wallet')->find($this->user->id) ;
      
        if($id = $user->wallet->id){
        $transactionDetils = ['type' => 'in','wallet_id'=>$id,'description'=>$desc,'amount'=>$amount];
        Transaction::create($transactionDetils);
        $wallet = Wallet::find($id);
        $wallet->increment('balance', $amount);
        }
        if (is_null($wallet)) {
            return null;
        }
        // Finally return the updated wallet.
        return $this->getByID($wallet->id);
    }

    public function decreaseWallet(int $amount,$desc) 
    {
        $user=  User::with('wallet')->find($this->user->id) ;
      
        if($id = $user->wallet->id){
        $transactionDetils = ['type' => 'out','wallet_id'=>$id,'description'=>$desc,'amount'=>$amount];
        Transaction::create($transactionDetils);
        $wallet = Wallet::find($id);
        $wallet->decrement('balance', $amount);
        }
        if (is_null($wallet)) {
            return null;
        }
        // Finally return the updated wallet.
        return $this->getByID($wallet->id);
    }
}
