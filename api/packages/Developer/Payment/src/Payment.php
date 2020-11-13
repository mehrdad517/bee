<?php


namespace Developer\Payment;

use Developer\Payment\Gateway\Parsian\Parsian;
use Developer\Payment\Gateway\Pasargad\Pasargad;
use Developer\Payment\Gateway\Zarinpal\Zarinpal;

class Payment
{


    /**
     * @param $name
     * @param $arguments
     * @return Pasargad|Zarinpal
     */
    public function __call($name, $arguments)
    {
        return $this->init($name);
    }

    /**
     * @param $gateway
     * @return Pasargad|Zarinpal
     */
    public function init($gateway)
    {
      if ($gateway instanceof Pasargad) {
            return new Pasargad();
      } elseif ($gateway instanceof Zarinpal) {
            return new Zarinpal();
      } elseif ($gateway instanceof Parsian) {
          return new Parsian();
      } else {
          $class = __NAMESPACE__ . '\\' . 'Gateway' . '\\' . ucfirst(strtolower($gateway)) . '\\' . ucfirst(strtolower($gateway));
          return new $class;
      }

    }

}
?>
