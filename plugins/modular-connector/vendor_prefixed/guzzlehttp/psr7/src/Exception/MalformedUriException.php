<?php

declare (strict_types=1);
namespace Modular\ConnectorDependencies\GuzzleHttp\Psr7\Exception;

use InvalidArgumentException;
/**
 * Exception thrown if a URI cannot be parsed because it's malformed.
 * @internal
 */
class MalformedUriException extends InvalidArgumentException
{
}
