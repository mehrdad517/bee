<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'order';
    protected $guarded = [];
}
