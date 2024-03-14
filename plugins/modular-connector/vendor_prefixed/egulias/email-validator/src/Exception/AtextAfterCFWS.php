<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class AtextAfterCFWS extends InvalidEmail
{
    const CODE = 133;
    const REASON = "ATEXT found after CFWS";
}
