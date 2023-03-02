<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Helpers\UploadHelper;
use App\Interfaces\CrudInterface;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class SlideRepository implements CrudInterface
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
     * Get All Slides.
     *
     * @return collections Array of Slide Collection
     */
    public function getAll(): Paginator
    {
        return Slide::orderBy('id', 'desc')
            ->paginate(10);
    }

    /**
     * Get Paginated Slide Data.
     *
     * @param int $pageNo
     * @return collections Array of Slide Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        return Slide::orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get Searchable Slide Data with Pagination.
     *
     * @param int $pageNo
     * @return collections Array of Slide Collection
     */
    public function searchSlide($keyword, $perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;

        return Slide::where('title', 'like', '%' . $keyword . '%')
            ->orWhere('description', 'like', '%' . $keyword . '%')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Create New Slide.
     *
     * @param array $data
     * @return object Slide Object
     */
    public function create(array $data): Slide
    {

        $titleShort      = Str::slug(substr($data['title'], 0, 20));
       // $data['user_id'] = $this->user->id;

        if (!empty($data['image'])) {
            $data['image'] = UploadHelper::upload('image', $data['image'], $titleShort . '-' . time(), 'storage');
        }

        return Slide::create($data);
    }

    /**
     * Delete Slide.
     *
     * @param int $id
     * @return boolean true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $product = Slide::find($id);
        if (empty($product)) {
            return false;
        }

        UploadHelper::deleteFile('images/Slide/' . $product->image);
        $product->delete($product);
        return true;
    }

    /**
     * Get Slide Detail By ID.
     *
     * @param int $id
     * @return void
     */
    public function getByID(int $id): Slide|null
    {
        return Slide::find($id);
    }

    /**
     * Update Slide By ID.
     *
     * @param int $id
     * @param array $data
     * @return object Updated Slide Object
     */
    public function update(int $id, array $data): Slide|null
    {
        $product = Slide::find($id);
        if (!empty($data['image'])) {
            $titleShort = Str::slug(substr($data['title'], 0, 20));
            $data['image'] = UploadHelper::update('image', $data['image'], $titleShort . '-' . time(), 'images/Slide', $product->image);
        } else {
            $data['image'] = $product->image;
        }

        if (is_null($product)) {
            return null;
        }

        // If everything is OK, then update.
        $product->update($data);

        // Finally return the updated product.
        return $this->getByID($product->id);
    }
}
