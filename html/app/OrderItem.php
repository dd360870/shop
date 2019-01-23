<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'merchandise_id',
        'amount',
        'price',
    ];

    public function merchandise() {
        return $this->belongsTo('App\Merchandise');
    }
}
