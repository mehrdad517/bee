<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @property string type
 * @property int id
 * @property mixed to
 */
class Notification extends Model
{
    protected $table = 'notification';

    protected $guarded = [];
}
