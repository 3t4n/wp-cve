<?php

namespace FluentSupport\App\Hooks\DataValidation;

use FluentSupport\Framework\Support\Arr;

class AccountingRestrictions
{
    public function register()
    {
        add_action('ninja_erp/before_add_payment', array($this, 'validateNewPayment'), 10, 1);
    }

    public function validateNewPayment($payment)
    {

    }

    private function handleError($message)
    {

    }
}
