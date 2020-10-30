<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderQueueJob extends Model
{

    protected $table = 'order_queue_job';

    protected $guarded = [];
}
