<?php
/**
 * The Zahls Exception for any exception occurred during the API process 
 * @since     v1.0
 */
namespace Zahls;

/**
 * Class ZahlsException
 * @package Zahls
 */
class ZahlsException extends \Exception
{
    private $reason = '';

    public function getReason()
    {
        return $this->reason;
    }

    public function setReason($reason = '')
    {
        $this->reason = $reason;
    }
}
