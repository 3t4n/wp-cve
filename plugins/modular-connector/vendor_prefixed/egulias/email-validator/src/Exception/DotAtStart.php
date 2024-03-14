<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class DotAtStart extends InvalidEmail
{
    const CODE = 141;
    const REASON = "Found DOT at start";
}
