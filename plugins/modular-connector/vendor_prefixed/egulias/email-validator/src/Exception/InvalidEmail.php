<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Exception;

/** @internal */
abstract class InvalidEmail extends \InvalidArgumentException
{
    const REASON = "Invalid email";
    const CODE = 0;
    public function __construct()
    {
        parent::__construct(static::REASON, static::CODE);
    }
}
