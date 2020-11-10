<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class StickyConfig extends Model
{
    protected $table = 'domain_config';

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
}