<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Board extends Model
{
    use HasFactory;
    protected $table = 'boards';
    protected $fillable = ['user_id', 'game_id', 'board_data','card_number','is_pay'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}