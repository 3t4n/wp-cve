<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class UnclosedComment extends InvalidEmail
{
    const CODE = 146;
    const REASON = "No closing comment token found";
}
