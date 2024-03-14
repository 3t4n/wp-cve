<?php

namespace CODNetwork\Services\Exception;

use Exception;

class CODN_Application_Token_Null_Exception extends Exception
{
    protected $extraMessage;

    protected $responseCode;

    public function __construct(string $extraMessage, int $responseCode)
    {
        parent::__construct('order was not pushed to cod network due to empty token');

        $this->extraMessage = $extraMessage;
        $this->responseCode = $responseCode;
    }


    public function getExtraMessage(): string
    {
        return $this->extraMessage;
    }

    public function getResposneCode(): string
    {
        return $this->responseCode;
    }
}
