<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;

class Event extends Model
{
    use HasFactory;
    protected $table = 'event';
    protected $fillable = [
        'name',
        'brand_id',
        'lat',
        'lng',
        'start',
        'end',
        'entry_fee',
        'titcket_link',
        'about',
        'weekly_or_daily',
        'image',
        'status',
        'featured',
        'is_accepted',
        'ticket'

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

    public function scopeAccepted($query)
    {
        return $query->where('is_accepted', true);
    }

    public function brand(): object
    {
        return $this->belongsTo(Brand::class)->select('id', 'name', 'about');
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
        return $this->belongsToMany(Tag::class,'tag_event');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }
    public function event(){
        return $this->belongsTo(User::class);
    }
}
