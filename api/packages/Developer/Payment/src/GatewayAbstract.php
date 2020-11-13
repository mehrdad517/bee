<?php

namespace Developer\Payment;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Credit\Entities\Transaction;

abstract class GatewayAbstract
{

    protected $amount;

    protected $callback;

    protected $description;

    protected $gateway;

    protected $transaction;

    protected $refId;


    function getTimeId()
    {
        $genuid = function() {
            return substr(str_pad(str_replace('.','', microtime(true)),12,0),0,12);
        };

        $uid = $genuid();

        while (DB::table('transaction')->find($uid)) {
            $uid = $genuid();
        }

        return $uid;
    }


    protected function ready()
    {

        $id = $this->getTimeId();

        DB::table('transaction')->insert([
            'id' => $id,
            'user_id' => Auth::id(),
            'amount' => $this->amount,
            'gateway' => $this->gateway,
            'description' => $this->description,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->transaction = DB::table('transaction')->find($id);

        return $this->transaction;

    }

    protected function transactionSucceed($transaction)
    {
        $transaction->payment_date = Carbon::now();
        $transaction->ref_id = $this->refId;
        $transaction->status = 'success';
        $transaction->save();

        return $transaction;
    }

    protected function transactionFailed($transaction)
    {
        $transaction->status = 'failed';
        $transaction->save();

        return $transaction;
    }

}
?>
