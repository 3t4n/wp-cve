<?php

class CashBillTwistoPaymentType extends CashBillPaymentTypeAbstract
{

    public function __construct()
    {
        parent::__construct('twisto', 'logo_twisto_blocks_70x24.png');
    }
}
