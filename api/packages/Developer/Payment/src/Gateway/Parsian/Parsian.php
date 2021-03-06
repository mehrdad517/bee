<?php

namespace Developer\Payment\Gateway\Parsian;

use Developer\Payment\GatewayAbstract;
use Developer\Payment\GatewayInterface;
use GuzzleHttp\Client;
use SoapClient;

class Parsian extends GatewayAbstract implements GatewayInterface
{

    private $MerchantCode;
    private $parameter;

    /**
     * Parsian constructor.
     */
    public function __construct()
    {
        $this->MerchantCode = env('Parsian_Merchant_Id');
        $this->gateway = 'parsian';
    }

    /**
     * @param mixed $parameter
     */
    public function setParameter($parameter): void
    {
        $this->parameter = $parameter;
    }



    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function setCallback($url)
    {
        $this->callback = $url;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }


    public function setRefId($refId)
    {
        $this->refId = $refId;
    }

    public function ready()
    {
        return parent::ready(); // TODO: Change the autogenerated stub
    }


    /**
     *
     *
     */
    public function redirect()
    {
        $url = "https://pec.shaparak.ir/NewIPGServices/Sale/SaleService.asmx?WSDL";

        $data = [
            'LoginAccount'   => $this->MerchantCode,
            'Amount'         => $this->amount,
            'OrderId'        => $this->transaction->id,
            'CallBackUrl'    => $this->callback,
            'AdditionalData' => ""
        ];

        $client = new SoapClient($url);

        $result = $client->SalePaymentRequest([
            "requestData" => $data
        ]);



        if ($result->SalePaymentRequestResult->Token && $result->SalePaymentRequestResult->Status === 0) {

            return response([
                'status' => true,
                'msg' => 'در حال اتصال به درگاه بانک',
                'payload' => [
                    'action' => "https://pec.shaparak.ir/NewIPG/?Token=" . $result->SalePaymentRequestResult->Token,
                    'method' => 'POST',
                    'fields' => []
                ]
            ]);

        } elseif ($result->SalePaymentRequestResult->Status  != '0') {
            return response(['status' => false, 'msg' => $result->SalePaymentRequestResult->Message]);
        }


    }



    public function verify($transaction)
    {

        $PIN = $this->MerchantCode;
        $wsdl_url = "https://pec.shaparak.ir/NewIPGServices/Confirm/ConfirmService.asmx?WSDL";

        $Token = isset($this->parameter["Token"]) ? $this->parameter["Token"] : -1;
        $status = isset($this->parameter["status"]) ? $this->parameter["status"] : -1;
        $RRN = isset($this->parameter["RRN"]) ? $this->parameter["RRN"] : 0;

        if ($RRN > 0 && $status == 0) {

            $params = [
                "LoginAccount" => $PIN,
                "Token" => $Token
            ];

            $client = new SoapClient($wsdl_url);

            try {
                $result = $client->ConfirmPayment ([
                    "requestData" => $params
                ]);


                if ($result->ConfirmPaymentResult->Status != '0') {
                    $err_msg = $result->ConfirmPaymentResult->Message;
                } else {
                    $this->refId = $result->ConfirmPaymentResult->RRN;
                    $this->transactionSucceed($transaction);
                    return ['status' => true, 'payload' => $result];
                }
            } catch (\Exception $ex ) {
                $err_msg = $ex->getMessage();
            }
        } elseif($status) {
            $err =  [
                -32768 => "خطاي ناشناخته رخ داده است",
                -32768 => "خطاي ناشناخته رخ داده است",
                -1552  => "برگشت تراکنش مجاز نمی باشد",
                -1551  => "برگشت تراکنش قب ًلا انجام شده است",
                -138   => "عملیات پرداخت توسط کاربر لغو شد",
                0      => 'تراکنش با موفقیت انجام شد.',
                1      => 'خطا در انجام تراکنش',
                2      => 'بین عملیات وقفه افتاده است.',
                10     => 'شماره کارت نامعتبر است.',
                11     => 'کارت شما منقضی شده است',
                12     => 'رمز کارت وارد شده اشتباه است',
                13     => 'موجودی کارت شما کافی نیست',
                14     => 'مبلغ تراکنش بیش از سقف مجاز پذیرنده است.',
                15     => 'سقف مجاز روزانه شما کامل شده است.',
                18     => 'این تراکنش قبلا تایید شده است',
                20     => 'اطلاعات پذیرنده صحیح نیست.',
                21     => 'invalid authority',
                22     => 'اطلاعات پذیرنده صحیح نیست.',
                30     => 'عملیات قبلا با موفقیت انجام شده است.',
                34     => 'شماره تراکنش فروشنده درست نمی باشد.',
            ];
            $err_msg = $err[$status];
        } else {
            $err_msg = "پاسخی از سمت بانک ارسال نشد " ;
        }


        $this->transactionFailed($transaction);

        return ['status' => false, 'msg' => $err_msg];

    }


}
?>
