<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * @var string table name
     */
    protected $table = 'files';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'subtitle',
        'user_id',
        'background_color',
        'background_path',
        'background_name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class)->withTimestamps();
    }
}
