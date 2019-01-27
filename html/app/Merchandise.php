<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Merchandise extends Model
{
    private $inventoryByColors;
    private $colors;

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
        'photo_path',
        'is_selling',
        'size_min',
        'size_max',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $cast = [
        'is_selling' => 'boolean',
    ];

    public function category() {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    public function inventory() {
        return $this->hasMany('App\MerchandiseInventory');
    }

    public function getPhotoPathAttribute() {
        return 'i/'.sprintf("%06d", $this->id).'/'.sprintf("%06d", $this->id).'.jpeg';
    }

    public function getPhotoUrlAttribute() {
        if(Storage::disk('s3')->exists($this->getPhotoPathAttribute())) {
            return Storage::disk('s3')->url($this->getPhotoPathAttribute());
        }
        return secure_asset('default-merchandise.jpg');
    }

    public function getPhotoDirectoryAttribute() {
        return 'i/'.sprintf("%06d", $this->id).'/';
    }

    public function getInventoryByColorsAttribute() {
        if(!isset($this->inventoryByColors)) {
            $this->inventoryByColors = $this->inventory()->select('color_id', 'merchandise_id')->distinct()->get();
        }
        return $this->inventoryByColors;
    }

    public function getColorsAttribute() {
        if(! isset($this->colors)) {
            $this->colors = $this->getInventoryByColorsAttribute()->pluck('color_id')->all();
        }
        return $this->colors;
    }

    //取回正在販賣的商品
    public function scopeSelling($query) {
        return $query->where('is_selling', '=', true);
    }

    public function scopeOfCategory($query, $category) {
        return $query->where('category_id', $category);
    }
    
    public function scopeInCategories($query, $categories) {
        return $query->whereIn('category_id', $categories);
    }
}
