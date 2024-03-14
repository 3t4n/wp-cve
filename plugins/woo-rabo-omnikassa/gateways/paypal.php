<?php

    class icwoorok2_paypal extends icwoorok2_abstract
    {
        public function getPaymentCode()
        {
            return 'icwoorok2_paypal';
        }

        public function getPaymentName()
        {
            return 'Paypal';
        }
    }
