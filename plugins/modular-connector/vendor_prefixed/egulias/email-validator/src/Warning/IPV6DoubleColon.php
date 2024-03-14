<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Warning;

/** @internal */
class IPV6DoubleColon extends Warning
{
    const CODE = 73;
    public function __construct()
    {
        $this->message = 'Double colon found after IPV6 tag';
        $this->rfcNumber = 5322;
    }
}
