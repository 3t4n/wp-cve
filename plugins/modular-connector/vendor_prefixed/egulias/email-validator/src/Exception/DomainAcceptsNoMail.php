<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class DomainAcceptsNoMail extends InvalidEmail
{
    const CODE = 154;
    const REASON = 'Domain accepts no mail (Null MX, RFC7505)';
}
