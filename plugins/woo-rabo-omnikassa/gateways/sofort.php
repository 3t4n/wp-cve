<?php

    class icwoorok2_sofort extends icwoorok2_abstract
    {
        public function getPaymentCode()
        {
            return 'icwoorok2_sofort';
        }

        public function getPaymentName()
        {
            return 'Sofort';
        }
    }
