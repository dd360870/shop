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
    ];

    /*
    取回樹狀結構表
    name 為區分男女裝分類
     */
    public function scopeTree($query, $name = null) {
        $categories = $query->select('t1.name as lev1', 't2.name as lev2', 't2.id as lev2_id', 't3.name as lev3', 't3.id as lev3_id', 't4.name as lev4', 't4.id as lev4_id')
            ->from('category as t1');
        if(empty($name)) {
            $categories = $categories->leftJoin('category as t2', 't2.parent', '=', 't1.id');
        } else {
            $categories = $categories->leftJoin('category as t2', function($join) use ($name) {
                $join->on('t2.parent', '=', 't1.id')
                    ->where('t2.name', '=', $name);
            });
        }
        $categories = $categories->leftJoin('category as t3', 't3.parent', '=', 't2.id')
            ->leftJoin('category as t4', 't4.parent', '=', 't3.id')
            ->where('t1.name', '=', 'HEAD');
        return $categories;
    }
}
