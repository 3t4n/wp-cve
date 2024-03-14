<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Warning;

/** @internal */
class CFWSWithFWS extends Warning
{
    const CODE = 18;
    public function __construct()
    {
        $this->message = 'Folding whites space followed by folding white space';
    }
}
