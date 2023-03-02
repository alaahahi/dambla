<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
class Brand extends Model
{
    use HasFactory;
    protected $table = 'brand';
    /**
     * Override fillable property data.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'about',
        'facebook',
        'instagram',
        'user_id',
        'category_id',
        'youtube',
        'email',
        'phone_number',
        'website',
        'image',
        'photo',
        'is_band',
        'is_accepted',
        'is_sponsors',
        'likes',
        'location'
    ];

    /**
     * User
     *
     * Get User Uploaded By Brand
     *
     * @return object
     */
    public function user(): object
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'email');
    }
    public function category(): object
    {
        return $this->belongsTo(Category::class)->select('id', 'name');
    }
    // Add New Attribute to get image address
    protected $appends = ['image_url'];

    /**
     * Get Added Image Attribute URL.
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): string | null
    {
        if (is_null($this->image) || $this->image === "") {
            return null;
        }

        return url('') . "/storage/" . $this->image;
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'tag_brand');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    public function usersBrand()
    {
        return $this->belongsToMany(User::class, 'wishlists_brands');
    }

    public function wishlists()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    public function wishlistsBrand()
    {
        return $this->belongsToMany(User::class, 'wishlists_brands');
    }
    public function scopeAccepted($query)
    {
        return $query->where('is_accepted', true)->where('is_band', false);
    }
}
