<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class CRLFAtTheEnd extends InvalidEmail
{
    const CODE = 149;
    const REASON = "CRLF at the end";
}
