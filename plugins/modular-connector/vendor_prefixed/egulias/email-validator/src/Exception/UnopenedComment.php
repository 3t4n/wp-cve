<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class UnopenedComment extends InvalidEmail
{
    const CODE = 152;
    const REASON = "No opening comment token found";
}
