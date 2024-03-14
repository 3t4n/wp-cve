<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Warning;

/** @internal */
class LabelTooLong extends Warning
{
    const CODE = 63;
    public function __construct()
    {
        $this->message = 'Label too long';
        $this->rfcNumber = 5322;
    }
}
