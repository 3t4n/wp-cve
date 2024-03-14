<?php

namespace WpifyWooDeps\Rikudou\Iban\Iban;

class SlovakIbanAdapter extends CzechAndSlovakIbanAdapter
{
    protected function getCountryCode() : string
    {
        return 'SK';
    }
}
