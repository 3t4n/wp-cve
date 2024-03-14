<?php

class CashBillPayPalPaymentType extends CashBillPaymentTypeAbstract
{

    public function __construct()
    {
        parent::__construct('paypal', 'logo_paypal_blocks_94x24.png');
    }
}
