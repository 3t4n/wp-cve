<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class ExpectingDTEXT extends InvalidEmail
{
    const CODE = 129;
    const REASON = "Expected DTEXT";
}
