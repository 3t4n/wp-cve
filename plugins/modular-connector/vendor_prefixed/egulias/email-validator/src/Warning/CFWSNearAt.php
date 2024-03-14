<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Warning;

/** @internal */
class CFWSNearAt extends Warning
{
    const CODE = 49;
    public function __construct()
    {
        $this->message = "Deprecated folding white space near @";
    }
}
