<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'amount',
        'price',
    ];

    public function merchandiseInventory() {
        return $this->hasOne('App\MerchandiseInventory',
            'product_id',
            'product_id'
        );
    }
}
