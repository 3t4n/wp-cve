<?php

namespace WpLHLAdminUi\LicenseKeys;

class LicenseKeyHandlerError{

    public $code;
    public $message;

    public function __construct( string $err_code = '', string $message = '')
    {
        $this->code = ($err_code) ?? 'missing_code';
        $this->message = ($message) ?? 'Empty Error Message.';
    }
}