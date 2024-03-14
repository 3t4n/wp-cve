<?php

namespace WunderAuto\Format;

/**
 * Class CountryCodes
 */
class CountryCodes
{
    /**
     * @var \stdClass
     */
    private $allCountries;

    /**
     * Phone
     */
    public function __construct()
    {
        $content            = file_get_contents(__DIR__ . '/allcountries.json');
        $this->allCountries = $content !== false ? json_decode($content) : (object)[];
    }

    /**
     * Guess a country code from a string representing a country
     * i.e Sverige would return SE
     *
     * @param string $string Country name in (almost) any language.
     *
     * @return string
     */
    public function fromString($string)
    {
        if (strlen($string) === 2 && isset($this->allCountries->$string)) {
            return $string;
        }

        $lcString = strtolower($string);
        foreach ((array)$this->allCountries as $code => $country) {
            foreach ($country as $name) {
                if (strtolower($name) === $lcString) {
                    return $code;
                }
            }
        }

        return $string;
    }
}
