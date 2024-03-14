<?php

namespace Dotdigital_WordPress_Vendor\Http\Discovery\Exception;

use Dotdigital_WordPress_Vendor\Http\Discovery\Exception;
/**
 * Thrown when a discovery does not find any matches.
 *
 * @final do NOT extend this class, not final for BC reasons
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
/* final */
class NotFoundException extends \RuntimeException implements Exception
{
}
