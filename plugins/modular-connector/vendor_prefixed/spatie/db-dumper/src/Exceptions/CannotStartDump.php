<?php

namespace Modular\ConnectorDependencies\Spatie\DbDumper\Exceptions;

use Exception;
/** @internal */
class CannotStartDump extends Exception
{
    /**
     * @param string $name
     *
     * @return \Spatie\DbDumper\Exceptions\CannotStartDump
     */
    public static function emptyParameter($name)
    {
        return new static("Parameter `{$name}` cannot be empty.");
    }
}
