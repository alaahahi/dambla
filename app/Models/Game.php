<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use TCG\Voyager\Traits\Translatable;

class Game extends Model
{
    use HasFactory;
    use Translatable;
    protected $table = 'game'; 
    protected $translatable = ['group_price', 'accumulated'];
    public function type(): object
    {
        return $this->belongsTo(GameType::class)->select('id', 'name');
    }
}
