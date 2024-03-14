<?php

class CashBillApplePayPaymentType extends CashBillPaymentTypeAbstract
{
    public function __construct()
    {
        parent::__construct('applepay', 'logo_applepay_blocks_36x24.png');
    }
}
