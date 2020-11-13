<?php

namespace Developer\Payment;


interface GatewayInterface
{

    /**
     * Set amount
     *
     * @param $amount integer
     *
     * @return string
     */
    public function setAmount($amount);

    /**
     * Sets callback url
     *
     * @param $url string
     * @return string
     */
    public function setCallback($url);

    /**
     * Set description
     *
     * @param $description integer
     *
     * @return string
     */
    public function setDescription($description);


    /**
     * Set description
     *
     * @param $refId string
     *
     * @return string
     */
    public function setRefId($refId);



    /**
     * This method use for redirect to port
     *
     * @return mixed
     */
    public function redirect();

    /**
     * Return result of payment
     * If result is done, return true, otherwise throws an related exception
     *
     * @param object $transaction row of transaction in database
     *
     * @return $this
     */
    public function verify($transaction);
}
