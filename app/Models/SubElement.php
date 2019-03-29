<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubElement extends Model
{
    /**
     * @var string
     */
    protected $table = 'sub_elements';

    /**
     * @var array
     */
    protected $fillable = [
        'element_id',
        'icon_id',
        'type',
        'title',
        'description',
        'binary',
        'url'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function element()
    {
        return $this->belongsTo(Element::class);
    }
}
