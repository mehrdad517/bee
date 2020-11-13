<?php

namespace Modules\Finanical\Entities;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $table = 'transaction';

    protected $primaryKey = 'id';

    protected $guarded = [];
}
