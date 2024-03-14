<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Warning;

/** @internal */
class IPV6Deprecated extends Warning
{
    const CODE = 13;
    public function __construct()
    {
        $this->message = 'Deprecated form of IPV6';
        $this->rfcNumber = 5321;
    }
}
