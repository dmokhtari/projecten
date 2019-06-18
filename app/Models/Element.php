<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    /**
     * @var string
     */
    protected $table = 'elements';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'module_id',
        'title',
        'subtitle',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subelements()
    {
        return $this->hasMany(SubElement::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ranking()
    {
        return $this->hasOne(Ranking::class, 'table_id', 'id')->where('type', '=', 'element');
    }
}
