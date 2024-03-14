<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class DomainHyphened extends InvalidEmail
{
    const CODE = 144;
    const REASON = "Hyphen found in domain";
}
