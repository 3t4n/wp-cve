<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Validation\Error;

use Modular\ConnectorDependencies\Egulias\EmailValidator\Exception\InvalidEmail;
/** @internal */
class SpoofEmail extends InvalidEmail
{
    const CODE = 998;
    const REASON = "The email contains mixed UTF8 chars that makes it suspicious";
}
