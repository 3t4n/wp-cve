<?php

namespace WpifyWooDeps\Rikudou\Iban\Iban;

use WpifyWooDeps\Rikudou\Iban\Validator\CompoundValidator;
use WpifyWooDeps\Rikudou\Iban\Validator\CzechIbanValidator;
use WpifyWooDeps\Rikudou\Iban\Validator\GenericIbanValidator;
use WpifyWooDeps\Rikudou\Iban\Validator\ValidatorInterface;
class CzechIbanAdapter extends CzechAndSlovakIbanAdapter
{
    public function getValidator() : ?ValidatorInterface
    {
        return new CompoundValidator(new CzechIbanValidator($this->accountNumber, $this->bankCode), new GenericIbanValidator($this));
    }
    protected function getCountryCode() : string
    {
        return 'CZ';
    }
}
