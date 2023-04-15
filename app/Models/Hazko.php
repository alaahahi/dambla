<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hazko extends Model
{
    use HasFactory;
    protected $table = 'hazko';
    protected $fillable = ['card_number',
        'row_1_col_1', 'row_1_col_2', 'row_1_col_3', 'row_1_col_4', 'row_1_col_5',
        'row_2_col_1', 'row_2_col_2', 'row_2_col_3', 'row_2_col_4', 'row_2_col_5',
        'row_3_col_1', 'row_3_col_2', 'row_3_col_3', 'row_3_col_4', 'row_3_col_5',
        'row_4_col_1', 'row_4_col_2', 'row_4_col_3', 'row_4_col_4', 'row_4_col_5'];
}
