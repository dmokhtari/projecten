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
        'text'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class)->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function elements()
    {
        return $this->hasMany(Element::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ranking()
    {
        return $this->hasOne(Ranking::class, 'table_id', 'id')->where('type', '=', 'module');
    }
}
