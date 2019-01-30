<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MerchandiseInventory extends Model
{
    protected $table = "merchandise_inventory";

    protected $fillable = [
        'merchandise_id',
        'color_id',
        'size_id',
        'amount',
    ];

    public function merchandise() {
        return $this->hasOne('App\Merchandise', 'id', 'merchandise_id');
    }

    public function getPhotoPathAttribute() {
        return '/i/'.sprintf("%06d", $this->merchandise_id).'/'.sprintf("%06d", $this->merchandise_id).sprintf("%02d", $this->color_id).'.jpeg';
    }
    
    public function getPhotoUrlAttribute() {
        return Storage::disk('s3')->exists($this->photoPath) ? Storage::disk('s3')->url($this->photoPath) : secure_asset('default-merchandise.jpg') ;
    }
}
