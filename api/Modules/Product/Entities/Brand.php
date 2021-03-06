<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brand';

    protected $primaryKey='id';

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

    public function files()
    {
        return $this->morphMany(\Modules\FileManager\Entities\File::class, 'fileable');
    }



    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category_assignable', 'category_id', 'assignable_id')->where('type', '=', 'brand');
    }



}
