<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagBrand extends Model
{
    use HasFactory;
    protected $table = 'tag_brand';

    /**
     * Override fillable property data.
     *
     * @var array
     */
    protected $fillable = [
        'tag_id',
        'brand_id'
    ];

}
