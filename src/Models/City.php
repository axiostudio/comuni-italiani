<?php

namespace Axiostudio\Comuni\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'id',
        'name',
        'code',
        'province_id',
    ];

    protected $hidden = [
        'province_id',
    ];

    public $timestamps = false;

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function zips()
    {
        return $this->hasMany(Zip::class)->orderBy('code');
    }
}
