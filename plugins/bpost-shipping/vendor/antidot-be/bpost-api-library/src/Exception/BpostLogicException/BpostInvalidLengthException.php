<?php

namespace Bpost\BpostApiClient\Exception\BpostLogicException;

use Bpost\BpostApiClient\Exception\BpostLogicException;

/**
 * Class BpostInvalidLengthException
 * @package Bpost\BpostApiClient\Exception\LogicException
 */
class BpostInvalidLengthException extends BpostLogicException
{
    /**
     * BpostInvalidLengthException constructor.
     * @param string     $nameEntry
     * @param int        $invalidLength
     * @param int        $maximumLength
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($nameEntry, $invalidLength, $maximumLength, $code = 0, \Exception $previous = null)
    {
        $message = sprintf(
            'Invalid length for entry "%1$s" (%2$s characters), maximum is %3$s.',
            $nameEntry,
            $invalidLength,
            $maximumLength
        );
        parent::__construct($message, $code, $previous);
    }
}
