<?php

namespace Developer\Payment\Gateway\Pasargad;

use Developer\Payment\GatewayAbstract;
use Developer\Payment\GatewayInterface;
use GuzzleHttp\Client;

class Pasargad extends GatewayAbstract implements GatewayInterface
{


    public $Timestamp;
    public $Action;
    private $MerchantCode;
    private $TerminalCode;


    /**
     * Pasargad constructor.
     */
    public function __construct()
    {
        $this->Action = 1003;
        $this->TerminalCode = env('Pasargad_terminal_Id');
        $this->MerchantCode = env('Pasargad_Merchant_Id');
        $this->Timestamp = date('Y/m/d H:i:s');
        $this->gateway = 'pasargad';
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
     * Sign given data.
     *
     * @param string $data
     *
     * @return string
     */
    public function sign($data)
    {

        $processor = new RSAProcessor(env('Pasargad_key'),RSAKeyType::XMLString);

        return $processor->sign($data);
    }

    /**
     * Prepare signature based on Pasargad document
     *
     * @param string $data
     * @return string
     */
    public function prepareSignature(string $data): string
    {
        return base64_encode($this->sign(sha1($data, true)));
    }


    public function redirect()
    {
        $url = "https://pep.shaparak.ir/Api/v1/Payment/GetToken";


        $data = [
            'InvoiceNumber' => $this->transaction->id,
            'InvoiceDate' => $this->transaction->created_at,
            'Amount' => $this->amount,
            'TerminalCode' => $this->TerminalCode,
            'MerchantCode' => $this->MerchantCode,
            'RedirectAddress' => $this->callback,
            'Timestamp' => $this->Timestamp,
            'Action' => $this->Action
        ];

        $sign = $this->prepareSignature(json_encode($data));


        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 'Sign' => $sign]
        ]);
        $response = $client->post($url, ['body' => json_encode($data)]);


        if ($response->getStatusCode() == 200) {

            $result = json_decode($response->getBody()->getContents());


            if ($result->IsSuccess) {
                return response([
                    'status' => true,
                    'msg' => 'در حال اتصال به درگاه بانک',
                    'payload' => [
                        'action' => env('Pasargad_Api'),
                        'method' => 'POST',
                        'fields' => [
                            ['name' => 'Token', 'value' => $result->Token]
                        ]
                    ]
                ]);
            } else {
                return response(['status' => false, 'msg' => $result->Message]);
            }
        }

        return response(['status' => false, 'msg' => 'خطا در اتصال به درگاه']);
    }


    /**
     * check transaction result
     *
     * @return bool
     */
    public function _checkTransactionResult()
    {

        $url = "https://pep.shaparak.ir/Api/v1/Payment/CheckTransactionResult";


        $data = [
            'InvoiceNumber' => $this->transaction->id,
            'InvoiceDate' => $this->transaction->created_at,
            'TerminalCode' => $this->TerminalCode,
            'MerchantCode' => $this->MerchantCode,
            'TransactionReferenceID' => $this->refId
        ];



        $client = new Client([
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $response = $client->post($url, ['body' => json_encode($data)]);


        if ($response->getStatusCode() == 200) {

            $result = json_decode($response->getBody()->getContents());


            if ($result->IsSuccess) {
                return true;
            } else {
                return false;
            }
        }

        return false;

    }


    public function verify($transaction)
    {
        if (! $this->_checkTransactionResult() ) return ['status' => false, 'msg' => 'check Transaction Result is false'];

        $url = "https://pep.shaparak.ir/Api/v1/Payment/VerifyPayment";

        $data = [
            'InvoiceNumber' => $this->transaction->id,
            'InvoiceDate' => $this->transaction->created_at,
            'Amount' => $this->amount,
            'TerminalCode' => $this->TerminalCode,
            'MerchantCode' => $this->MerchantCode,
            'Timestamp' => date('Y/m/d H:i:s'),
        ];

        $sign = $this->prepareSignature(json_encode($data));


        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 'Sign' => $sign]
        ]);
        $response = $client->post($url, ['body' => json_encode($data)]);


        if ($response->getStatusCode() == 200) {

            $result = json_decode($response->getBody()->getContents());


            if ($result->IsSuccess) {
                $this->refId = $result->refId;
                $this->transactionSucceed($transaction);
                return ['status' => true, 'payload' => $result];
            }

            $this->transactionFailed($transaction);

            return ['status' => true, 'msg' => $result->Message];
        }
    }


}
?>
