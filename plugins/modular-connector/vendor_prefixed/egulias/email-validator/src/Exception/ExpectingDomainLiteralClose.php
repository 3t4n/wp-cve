<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class ExpectingDomainLiteralClose extends InvalidEmail
{
    const CODE = 137;
    const REASON = "Closing bracket ']' for domain literal not found";
}
