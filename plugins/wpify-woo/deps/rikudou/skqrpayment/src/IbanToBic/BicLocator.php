<?php

namespace WpifyWooDeps\rikudou\SkQrPayment\IbanToBic;

use WpifyWooDeps\Rikudou\Iban\Iban\IbanInterface;
use WpifyWooDeps\rikudou\SkQrPayment\Exception\DictionaryNotFoundException;
use WpifyWooDeps\rikudou\SkQrPayment\IbanToBic\Dictionary\BicDictionaryInterface;
use WpifyWooDeps\rikudou\SkQrPayment\IbanToBic\Dictionary\CzechBicDictionary;
use WpifyWooDeps\rikudou\SkQrPayment\IbanToBic\Dictionary\SlovakBicDictionary;
final class BicLocator
{
    /**
     * @var BicDictionaryInterface[]
     */
    private $dictionaries;
    public function __construct()
    {
        $this->dictionaries = [new SlovakBicDictionary(), new CzechBicDictionary()];
    }
    public function getBic(IbanInterface $iban) : string
    {
        $countryCode = $this->getCountryCode($iban);
        foreach ($this->dictionaries as $dictionary) {
            if ($dictionary->getCountryCode() === $countryCode) {
                return $dictionary->getBic($iban);
            }
        }
        throw new DictionaryNotFoundException("Could not find any dictionary for country code '{$countryCode}'");
    }
    private function getCountryCode(IbanInterface $iban) : string
    {
        return \strtoupper(\substr($iban, 0, 2));
    }
}
