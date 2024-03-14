<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class ExpectingATEXT extends InvalidEmail
{
    const CODE = 137;
    const REASON = "Expecting ATEXT";
}
