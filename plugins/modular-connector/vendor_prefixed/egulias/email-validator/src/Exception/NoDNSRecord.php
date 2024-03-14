<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class NoDNSRecord extends InvalidEmail
{
    const CODE = 5;
    const REASON = 'No MX or A DSN record was found for this email';
}
