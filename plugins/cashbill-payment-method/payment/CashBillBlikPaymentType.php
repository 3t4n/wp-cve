<?php

class CashBillBlikPaymentType extends CashBillPaymentTypeAbstract
{

    public function __construct()
    {
        parent::__construct('blik', 'logo_blik_blocks_48x24.png');
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
                'blikCodeForm' => true
            )
        );
    }
}
