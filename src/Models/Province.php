<?php

namespace Axiostudio\Comuni\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'id',
        'name',
        'code',
        'region_id',
    ];

    protected $hidden = [
        'region_id',
    ];

    public $timestamps = false;

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class)->orderBy('name');
    }
}
