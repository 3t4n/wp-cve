<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Http\Client\Common\Exception;

use Dotdigital_WordPress_Vendor\Http\Client\Exception\HttpException;
/**
 * Thrown when circular redirection is detected.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class CircularRedirectionException extends HttpException
{
}
