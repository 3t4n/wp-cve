<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Warning;

/** @internal */
class DeprecatedComment extends Warning
{
    const CODE = 37;
    public function __construct()
    {
        $this->message = 'Deprecated comments';
    }
}
