<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class DotAtEnd extends InvalidEmail
{
    const CODE = 142;
    const REASON = "Dot at the end";
}
