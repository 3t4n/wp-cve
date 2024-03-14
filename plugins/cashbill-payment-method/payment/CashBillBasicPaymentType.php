<?php

class CashBillBasicPaymentType extends CashBillPaymentTypeAbstract
{

    public function __construct()
    {
        parent::__construct('basic', 'logo_black_blocks_94x24.png');
    }

    public function getPaymentChannels()
    {
        $paymetChannels = [];

        $gateways = WC()->payment_gateways->get_available_payment_gateways();
        if (isset($gateways[$this->name])) {
            $shop = $gateways[$this->name]->getCashBillShop();
            $paymetChannels = $shop->getPaymentChannels();
        }

        return $paymetChannels;
    }

    public function get_payment_method_data()
    {
        $parentData = parent::get_payment_method_data();

        if (!$parentData['extended'] || !is_checkout()) {
            return $parentData;
        }

        return array_merge(
            $parentData,
            array(
                'paymentChannels' => $this->getPaymentChannels()
            )
        );
    }


}
