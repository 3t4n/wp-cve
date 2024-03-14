<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Http\Client\Common\Exception;

use Dotdigital_WordPress_Vendor\Http\Client\Exception\TransferException;
/**
 * Thrown when a http client cannot be chosen in a pool.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class HttpClientNotFoundException extends TransferException
{
}
