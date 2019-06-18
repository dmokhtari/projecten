<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    protected $table = 'rankings';

    protected $fillable = [
        'ranking',
        'table_id',
        'type'
    ];
}
