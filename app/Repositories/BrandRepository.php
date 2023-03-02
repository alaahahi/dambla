<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Helpers\UploadHelper;
use App\Interfaces\CrudInterface;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
class BrandRepository implements CrudInterface
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
     * Get All Brands.
     *
     * @return collections Array of Brand Collection
     */
    public function getAll(): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        return Brand::orderBy('id', 'desc')
            ->with('user')
            ->with('tags')
            ->with('category')
            ->paginate($perPage);
    }

    public function topBrand($perPage): Paginator
    {

        $brands = Brand::orderBy('likes', 'desc')
        ->with('category')->accepted()
        ->with('tags')->with('wishlistsBrand')->paginate(10);

        $wish =  User::find($this->user->id)->wishlists_brands()->pluck('brand.id');
   
        $brands->getCollection()->transform(function($brand) use ($wish) {
            $brand->is_liked = $wish->contains($brand->id);
            return $brand;
        });

        return $brands;
    }

    /**
     * Get Paginated Brand Data.
     *
     * @param int $pageNo
     * @return collections Array of Brand Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        return Brand::orderBy('id', 'desc')
            ->with('user')
            ->with('tags')
            ->with('category')
            ->accepted()
            ->paginate($perPage);
    }


    public function myBrand($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 12;
        
        return Brand::orderBy('id', 'desc')
            ->where('user_id', $this->user->id)
            ->with('tags')
            ->with('category')
            ->paginate($perPage);
    }

    /**
     * Get Searchable Brand Data with Pagination.
     *
     * @param int $pageNo
     * @return collections Array of Brand Collection
     */
 

    public function searchBrand($keyword,$tagId,$catId, $entry_feeLessThan, $perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;

        $query = Brand::query()->with('tags')->with('category')->accepted();

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
        
        if ($catId && $catId!=[0=>null]) {
            $query->whereHas('category', function ($query) use ($catId) {
                $query->whereIn('id', $catId);
            });
        }


        // Filter by entry_fee less than
    
        if ($entry_feeLessThan) {
            $query->where('entry_fee', '<', $entry_feeLessThan);
        }


        $wish =  User::find($this->user->id)->wishlists_brands()->pluck('brand.id');
        $brands = $query->paginate($perPage);

        $brands->getCollection()->transform(function($brand) use ($wish) {
            $brand->is_liked = $wish->contains($brand->id);
            return $brand;
        });
        // Execute the query and paginate the results


        return $brands;
    }


    /**
     * Create New Brand.
     *
     * @param array $data
     * @return object Brand Object
     */
    public function create(array $data): Brand
    {
        $titleShort      = Str::slug(substr($data['name'], 0, 20));
        $data['user_id'] = $this->user->id;
        if (!empty($data['image'])) {
            $data['image'] = UploadHelper::upload('image', $data['image'], $titleShort . '-' . time(), 'storage');
        }
        $brand = Brand::create($data);
        try {
            $brand->tags()->attach(json_decode($data['tags'],true));
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $brand;
    }

    public function addBrand(array $data): Brand
    {
        $titleShort      = Str::slug(substr($data['name'], 0, 20));
         $data['user_id'] = $this->user->id;
        if (!empty($data['image'])) {
            $data['image'] = UploadHelper::upload('image', $data['image'], $titleShort . '-' . time(), 'storage');
        }
        $brand = Brand::create($data);
        try {
            $brand->tags()->attach(json_decode($data['tags'],true));
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $brand;
    }

    /**
     * Delete Brand.
     *
     * @param int $id
     * @return boolean true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            return false;
        }

        UploadHelper::deleteFile('images/brands/' . $brand->image);
        $brand->delete($brand);
        return true;
    }

    /**
     * Get Brand Detail By ID.
     *
     * @param int $id
     * @return void
     */
    public function getByID(int $id): Brand|null
    {
       // return Brand::with('user')->with('category')->with('tags')->find($id);

        $brand = Brand::with('category')->with('tags')->find($id);

        $inWishlist = false;
        $user =$this->user ;
        if ($user) {
            $inWishlist = $user->wishlists()->where('brand_id', $brand->id)->exists();
        }
         

        $brand->inWishlist = $inWishlist;

        return $brand;

    }

    /**
     * Update Brand By ID.
     *
     * @param int $id
     * @param array $data
     * @return object Updated Brand Object
     */
    public function update(int $id, array $data): Brand|null
    {
        $brand = Brand::find($id);
        if (!empty($data['image'])) {
            $titleShort = Str::slug(substr($data['name'], 0, 20));
            $data['image'] = UploadHelper::update('image', $data['image'], $titleShort . '-' . time(), 'images/brands', $brand->image);
        } else {
            $data['image'] = $brand->image;
        }

        if (is_null($brand)) {
            return null;
        }
        try {
            if(isset($data['tags'])){
                $brand->tags()->sync(json_decode($data['tags'],true),true);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
  
        // If everything is OK, then update.
        $brand->update($data);

        // Finally return the updated brand.
        return $this->getByID($brand->id);
    }
    public function brandSponsors()
    {
        return Brand::where('is_sponsors',1)
            ->with('tags')
            ->accepted()
            ->get();
    }
}
