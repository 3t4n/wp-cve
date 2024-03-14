<?php

class CashBillGooglePayPaymentType extends CashBillPaymentTypeAbstract
{

    public function __construct()
    {
        parent::__construct('googlepay', 'logo_googlepay_blocks_44x24.png');
    }
}
