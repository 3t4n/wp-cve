<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class CRLFX2 extends InvalidEmail
{
    const CODE = 148;
    const REASON = "Folding whitespace CR LF found twice";
}
