<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Warning;

/** @internal */
class DomainLiteral extends Warning
{
    const CODE = 70;
    public function __construct()
    {
        $this->message = 'Domain Literal';
        $this->rfcNumber = 5322;
    }
}
