<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class UnclosedQuotedString extends InvalidEmail
{
    const CODE = 145;
    const REASON = "Unclosed quoted string";
}
