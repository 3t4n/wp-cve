<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
class CRNoLF extends InvalidEmail
{
    const CODE = 150;
    const REASON = "Missing LF after CR";
}
