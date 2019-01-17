<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "category";
    //
    protected $fillable = [
        'name',
        'parent',
        'type'
    ];

    /*
    取回樹狀結構表
    name 為區分男女裝分類
     */
    public function scopeTree($query, $type = null) {
        $categories = $query->select('t1.name as lev1', 't1.id as lev1_id', 't2.name as lev2', 't2.id as lev2_id', 't3.name as lev3', 't3.id as lev3_id')
            ->from('category as t1')
            ->leftJoin('category as t2', 't2.parent', '=', 't1.id')
            ->leftJoin('category as t3', 't3.parent', '=', 't2.id');
        if(empty($type)) {
            $categories = $categories->where('t1.type', 0);
        } else {
            $categories = $categories->where('t1.id', $type);
        }

        return $categories;
    }
}
