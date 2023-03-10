<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use TCG\Voyager\Traits\Translatable;

class Session extends Model
{
    use HasFactory;
    use Translatable;
    protected $table = 'sessions'; 
    
}
