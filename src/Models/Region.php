<?php

namespace Axiostudio\Comuni\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'id',
        'name',
        'zone_id',
    ];

    protected $hidden = [
        'zone_id',
    ];

    public $timestamps = false;

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function provinces()
    {
        return $this->hasMany(Province::class)->orderBy('name');
    }
}
