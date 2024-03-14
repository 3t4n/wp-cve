<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Warning;

/** @internal */
class IPV6ColonStart extends Warning
{
    const CODE = 76;
    public function __construct()
    {
        $this->message = ':: found at the start of the domain literal';
        $this->rfcNumber = 5322;
    }
}
