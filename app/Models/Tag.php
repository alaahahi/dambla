<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
use App\Models\Event;
class Tag extends Model
{
    use HasFactory;
    protected $table = 'tag';

    /**
     * Override fillable property data.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status'
    ];
    protected $hidden = ['pivot'];

    public function Brands()
    {
        return $this->belongsToMany(Brand::class,'tag_brand');
    }

    public function Events()
    {
        return $this->belongsToMany(Event::class,'tag_event');
    }

}
