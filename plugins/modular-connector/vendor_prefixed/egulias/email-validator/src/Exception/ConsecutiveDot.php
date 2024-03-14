<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class ConsecutiveDot extends InvalidEmail
{
    const CODE = 132;
    const REASON = "Consecutive DOT";
}
