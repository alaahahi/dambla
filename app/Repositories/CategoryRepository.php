<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Helpers\UploadHelper;
use App\Interfaces\CrudInterface;
use App\Models\Category;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class CategoryRepository implements CrudInterface
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
     * Get All Categorys.
     *
     * @return collections Array of Category Collection
     */
    public function getAll(): Paginator
    {
        return Category::
            orderBy('id', 'desc')
            ->paginate(10);
    }

    /**
     * Get Paginated Category Data.
     *
     * @param int $pageNo
     * @return collections Array of Category Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        return Category::orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get Searchable Category Data with Pagination.
     *
     * @param int $pageNo
     * @return collections Array of Category Collection
     */
    public function searchCategory($keyword, $perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;
        return Category::where('name', 'like', '%' . $keyword . '%')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Create New Category.
     *
     * @param array $data
     * @return object Category Object
     */
    public function create(array $data): Category
    {

        return Category::create($data);
    }

    /**
     * Delete Category.
     *
     * @param int $id
     * @return boolean true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $category = Category::find($id);
        if (empty($category)) {
            return false;
        }
        $category->delete($category);
        return true;
    }

    /**
     * Get Category Detail By ID.
     *
     * @param int $id
     * @return void
     */
    public function getByID(int $id): Category|null
    {
        return Category::find($id);
    }

    /**
     * Update Category By ID.
     *
     * @param int $id
     * @param array $data
     * @return object Updated Category Object
     */
    public function update(int $id, array $data): Category|null
    {
        $category = Category::find($id);
        
        // If everything is OK, then update.
        $category->update($data);

        // Finally return the updated product.
        return $this->getByID($category->id);
    }
}
