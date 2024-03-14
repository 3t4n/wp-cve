<?php

namespace CODNetwork\Services\Exception;

use Exception;

class CODN_WP_Error_Exception extends Exception
{
    protected $extraMessage;

    protected $responseCode;

    public function __construct(string $extraMessage, string $responseCode)
    {
        parent::__construct('cod network respond by invalid status while pushing new order');

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
