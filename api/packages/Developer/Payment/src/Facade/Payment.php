<?php


namespace Developer\Payment\Facade;

use Illuminate\Support\Facades\Facade;

class Payment extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'payment';
    }
}

