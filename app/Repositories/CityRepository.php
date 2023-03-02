<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Helpers\UploadHelper;
use App\Interfaces\CrudInterface;
use App\Models\City;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class CityRepository implements CrudInterface
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
     * Get All Citys.
     *
     * @return collections Array of City Collection
     */
    public function getAll(): Paginator
    {
        return City::
            orderBy('id', 'desc')
            ->paginate(10);
    }

    /**
     * Get Paginated City Data.
     *
     * @param int $pageNo
     * @return collections Array of City Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        return City::orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get Searchable City Data with Pagination.
     *
     * @param int $pageNo
     * @return collections Array of City Collection
     */
    public function searchCity($keyword, $perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;
        return City::where('name', 'like', '%' . $keyword . '%')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Create New City.
     *
     * @param array $data
     * @return object City Object
     */
    public function create(array $data): City
    {
        return City::create($data);
    }

    /**
     * Delete City.
     *
     * @param int $id
     * @return boolean true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $tag = City::find($id);
        if (empty($tag)) {
            return false;
        }
        $tag->delete($tag);
        return true;
    }

    /**
     * Get City Detail By ID.
     *
     * @param int $id
     * @return void
     */
    public function getByID(int $id): City|null
    {
        return City::find($id);
    }

    /**
     * Update City By ID.
     *
     * @param int $id
     * @param array $data
     * @return object Updated City Object
     */
    public function update(int $id, array $data): City|null
    {
        $tag = City::find($id);
        
        // If everything is OK, then update.
        $tag->update($data);

        // Finally return the updated product.
        return $this->getByID($tag->id);
    }
}
