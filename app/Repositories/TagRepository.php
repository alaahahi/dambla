<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Helpers\UploadHelper;
use App\Interfaces\CrudInterface;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class TagRepository implements CrudInterface
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
     * Get All Tags.
     *
     * @return collections Array of Tag Collection
     */
    public function getAll(): Paginator
    {
        return Tag::
            orderBy('id', 'desc')
            ->paginate(10);
    }

    /**
     * Get Paginated Tag Data.
     *
     * @param int $pageNo
     * @return collections Array of Tag Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        return Tag::orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get Searchable Tag Data with Pagination.
     *
     * @param int $pageNo
     * @return collections Array of Tag Collection
     */
    public function searchTag($keyword, $perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;
        return Tag::where('name', 'like', '%' . $keyword . '%')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Create New Tag.
     *
     * @param array $data
     * @return object Tag Object
     */
    public function create(array $data): Tag
    {
        return Tag::create($data);
    }

    /**
     * Delete Tag.
     *
     * @param int $id
     * @return boolean true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $tag = Tag::find($id);
        if (empty($tag)) {
            return false;
        }
        $tag->delete($tag);
        return true;
    }

    /**
     * Get Tag Detail By ID.
     *
     * @param int $id
     * @return void
     */
    public function getByID(int $id): Tag|null
    {
        return Tag::find($id);
    }

    /**
     * Update Tag By ID.
     *
     * @param int $id
     * @param array $data
     * @return object Updated Tag Object
     */
    public function update(int $id, array $data): Tag|null
    {
        $tag = Tag::find($id);
        
        // If everything is OK, then update.
        $tag->update($data);

        // Finally return the updated product.
        return $this->getByID($tag->id);
    }
}
