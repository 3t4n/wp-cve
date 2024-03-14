<?php

    class icwoorok2_bancontact extends icwoorok2_abstract
    {
        public function getPaymentCode()
        {
            return 'icwoorok2_bancontact';
        }

        public function getPaymentName()
        {
            return 'Bancontact';
        }
    }
