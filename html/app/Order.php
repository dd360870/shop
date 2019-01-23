<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";

    protected $fillable = [
        'user_id',
        'pay_method',
        'paid',
        'delivery_method',
        'delivery_name',
        'delivery_address',
        'delivery_phone',
        'status',
        'total',
        'delivery_traceID',
    ];

    public function items() {
        return $this->hasMany('\App\OrderItem');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
    
}
