<?php

namespace Axiostudio\Comuni\Models;

use Illuminate\Database\Eloquent\Model;

class Zip extends Model
{
    protected $fillable = [
        'code',
        'city_id',
    ];

    protected $hidden = [
        'city_id',
    ];

    public $timestamps = false;

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
