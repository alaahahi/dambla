<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;
    protected $table = 'slide';

    /**
     * Override fillable property data.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'image',
    ];

    /**
     * User
     *
     * Get User Uploaded By Product
     *
     * @return object
     */


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
}
