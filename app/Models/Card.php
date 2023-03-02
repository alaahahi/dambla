<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $fillable = [
        'number',
        'status',
        'used_at',
    ];

    protected $dates = [
        'used_at',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->number = rand(10000000, 99999999);
            $model->status = true;
        });
    }
}
