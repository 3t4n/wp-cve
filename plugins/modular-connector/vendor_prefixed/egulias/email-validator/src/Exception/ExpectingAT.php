<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class ExpectingAT extends InvalidEmail
{
    const CODE = 202;
    const REASON = "Expecting AT '@' ";
}
