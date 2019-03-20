<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'title',
        'subtitle',
        'user_id',
        'background_color',
        'background_path',
        'background_name',
    ];
}
