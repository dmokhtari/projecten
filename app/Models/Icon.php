<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    /**
     * @var string
     */
    protected $table = 'icons';

    /**
     * @var array
     */
    protected $fillable = [
        'src',
        'name',
        'title',
        'type'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sub_elements()
    {
        return $this->belongsToMany(SubElement::class)->withTimestamps();
    }
}
