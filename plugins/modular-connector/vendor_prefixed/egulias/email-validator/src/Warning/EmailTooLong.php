<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Warning;

use Modular\ConnectorDependencies\Egulias\EmailValidator\EmailParser;
/** @internal */
class EmailTooLong extends Warning
{
    const CODE = 66;
    public function __construct()
    {
        $this->message = 'Email is too long, exceeds ' . EmailParser::EMAIL_MAX_LENGTH;
    }
}
