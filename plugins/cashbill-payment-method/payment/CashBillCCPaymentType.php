<?php

class CashBillCCPaymentType extends CashBillPaymentTypeAbstract
{

    public function __construct()
    {
        parent::__construct('paymentocc', 'logo_paymentocc_blocks_96x24.png');
    }
}
