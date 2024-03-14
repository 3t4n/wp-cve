<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class ConsecutiveAt extends InvalidEmail
{
    const CODE = 128;
    const REASON = "Consecutive AT";
}
