<?php

class CashBillPaysafecardPaymentType extends CashBillPaymentTypeAbstract
{

    public function __construct()
    {
        parent::__construct('paysafecard', 'logo_paysafecard_blocks_134x24.png');
    }
}
