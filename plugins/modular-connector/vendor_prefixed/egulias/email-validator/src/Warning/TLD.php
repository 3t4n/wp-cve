<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Warning;

/** @internal */
class TLD extends Warning
{
    const CODE = 9;
    public function __construct()
    {
        $this->message = "RFC5321, TLD";
    }
}
