<?php

namespace WpifyWooDeps\Rikudou\Iban\Iban;

use WpifyWooDeps\Rikudou\Iban\Helper\ToStringIbanTrait;
use WpifyWooDeps\Rikudou\Iban\Validator\GenericIbanValidator;
use WpifyWooDeps\Rikudou\Iban\Validator\ValidatorInterface;
class IBAN implements IbanInterface
{
    use ToStringIbanTrait;
    /**
     * @var string
     */
    private $iban;
    public function __construct(string $iban)
    {
        $this->iban = $iban;
    }
    /**
     * Returns the resulting IBAN.
     *
     * @return string
     */
    public function asString() : string
    {
        return $this->iban;
    }
    /**
     * Returns the validator that checks whether the IBAN is valid.
     *
     * @return ValidatorInterface|null
     */
    public function getValidator() : ?ValidatorInterface
    {
        return new GenericIbanValidator($this);
    }
}
