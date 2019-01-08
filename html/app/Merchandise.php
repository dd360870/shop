<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{

    protected $table = 'merchandises';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'intro',
        'category',
        'price',
        'amount',
        'status',
        'barcode_EAN',
        'photo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
