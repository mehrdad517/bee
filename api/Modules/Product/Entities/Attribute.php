<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Tag\Entities\Tag;

class Attribute extends Model
{

    protected $table = 'attribute';

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

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'attribute_has_tags', 'attribute_id', 'tag_id');
    }
}
