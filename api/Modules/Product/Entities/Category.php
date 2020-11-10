<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;

    protected $table = 'product_category';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::created(function ($model) {
            \Cache::flush();
        });

        static::updated(function ($model) {
            \Cache::flush();
        });

        static::deleted(function ($model) {
            \Cache::flush();
        });

    }


    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'product_category_assignable', 'category_id', 'assignable_id')->where('type', '=', 'brand');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_category_assignable', 'category_id', 'assignable_id')->where('type', '=', 'attribute');
    }


    public function filters()
    {
        return $this->belongsToMany(Filter::class, 'product_category_assignable', 'category_id', 'assignable_id')->where('type', '=', 'filter');
    }



    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_has_categories', 'category_id', 'product_id');
    }


}