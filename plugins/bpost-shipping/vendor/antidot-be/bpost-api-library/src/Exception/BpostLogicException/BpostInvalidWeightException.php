<?php

namespace Bpost\BpostApiClient\Exception\BpostLogicException;

use Bpost\BpostApiClient\Exception\BpostLogicException;

/**
 * Class BpostInvalidWeightException
 * @package Bpost\BpostApiClient\Exception\LogicException
 */
class BpostInvalidWeightException extends BpostLogicException
{
    /**
     * @param string     $invalidWeight
     * @param int        $maximumWeight
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($invalidWeight, $maximumWeight, $code = 0, \Exception $previous = null)
    {
        $message = sprintf(
            'Invalid weight (%1$s kg), maximum is %2$s.',
            $invalidWeight,
            $maximumWeight
        );
        parent::__construct($message, $code, $previous);
    }
}
