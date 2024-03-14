<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class CommaInDomain extends InvalidEmail
{
    const CODE = 200;
    const REASON = "Comma ',' is not allowed in domain part";
}
