<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Validation\Error;

use Modular\ConnectorDependencies\Egulias\EmailValidator\Exception\InvalidEmail;
/** @internal */
class RFCWarnings extends InvalidEmail
{
    const CODE = 997;
    const REASON = 'Warnings were found.';
}
