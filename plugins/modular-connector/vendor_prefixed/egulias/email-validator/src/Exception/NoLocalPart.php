<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class NoLocalPart extends InvalidEmail
{
    const CODE = 130;
    const REASON = "No local part";
}
