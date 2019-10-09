<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'answers';

    protected $fillable = [
        'user_id',
        'file_id',
        'module_id',
        'element_id',
        'sub_element_id',
        'answer'
    ];
}
