<?php

declare (strict_types=1);
namespace Modular\ConnectorDependencies\Brick\Math\Exception;

/**
 * Base class for all math exceptions.
 *
 * This class is abstract to ensure that only fine-grained exceptions are thrown throughout the code.
 * @internal
 */
class MathException extends \RuntimeException
{
}
