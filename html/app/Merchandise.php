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
        'category_id',
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

    public function category() {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    //取回正在販賣的商品
    public function scopeSelling($query) {
        return $query->where('status', '=', 'S');
    }

    public function scopeOfCategory($query, $category) {
        return $query->where('category_id', $category);
    }
    
    public function scopeInCategory($query, $category) {
        return $query->whereIn('category_id', $category);
    }
}
