<?php

declare(strict_types=1);

namespace ADEV_EmailValidation;

defined('ABSPATH') or die('Nope nope nope...');

class ValidationResults
{
    /** @var array */
    private $results = array();

    /**
     * @param string $resultName
     * @param mixed $resultValue
     */
    public function addResult($resultName, $resultValue)
    {
        $this->results[$resultName] = $resultValue;
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return $this->results;
    }

    /**
     * @return string
     */
    public function asJson()
    {
        return json_encode($this->results);
    }

    /**
     * @return bool
     */
    public function hasResults()
    {
        return !empty($this->results);
    }
}
