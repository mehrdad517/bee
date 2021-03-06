<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class ItemProduct extends Model
{

    protected $table = 'item_product';

    protected $primaryKey = 'id';

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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'item_product_list', 'item_id', 'product_id');
    }


}
