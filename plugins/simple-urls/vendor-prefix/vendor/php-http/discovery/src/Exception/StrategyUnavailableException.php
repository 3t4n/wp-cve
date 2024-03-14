<?php

namespace LassoLiteVendor\Http\Discovery\Exception;

use LassoLiteVendor\Http\Discovery\Exception;
/**
 * This exception is thrown when we cannot use a discovery strategy. This is *not* thrown when
 * the discovery fails to find a class.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class StrategyUnavailableException extends \RuntimeException implements Exception
{
}
