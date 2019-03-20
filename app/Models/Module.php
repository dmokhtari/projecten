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
        'background_path',
        'background_name',
        'background_color',
        'text'
    ];
}
