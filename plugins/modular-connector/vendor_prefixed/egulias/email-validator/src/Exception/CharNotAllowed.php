<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class CharNotAllowed extends InvalidEmail
{
    const CODE = 201;
    const REASON = "Non allowed character in domain";
}
