<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class ExpectingCTEXT extends InvalidEmail
{
    const CODE = 139;
    const REASON = "Expecting CTEXT";
}
