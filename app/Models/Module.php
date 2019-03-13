<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /**
     * @var string table name
     */
    protected $table = 'modules';

    /**
     * @var array mass assignable cols
     */
    protected $fillable = [
        'user_id',
        'title',
        'subtitle',
        'image_path',
        'image_name',
        'background_color'
    ];
}
