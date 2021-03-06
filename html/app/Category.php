<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";
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
            ->from('categories as t1')
            ->leftJoin('categories as t2', 't2.parent', '=', 't1.id')
            ->leftJoin('categories as t3', 't3.parent', '=', 't2.id');
        if(empty($type)) {
            $categories = $categories->where('t1.type', 0);
        } else {
            $categories = $categories->where('t1.id', $type);
        }

        return $categories;
    }

    public function getFullNameAttribute() {
        $Category = $this->select('t1.name as lev1', 't2.name as lev2', 't3.name as lev3')
            ->from('categories as t1')
            ->leftJoin('categories as t2', 't2.parent', '=', 't1.id')
            ->leftJoin('categories as t3', 't3.parent', '=', 't2.id')
            ->where('t3.id', $this->id)->first();
        return $Category->lev1.'->'.$Category->lev2.'->'.$Category->lev3;
    }
}
