<?php

declare (strict_types=1);
namespace Prokerala\Astrology\Vendor\Buzz\Exception;

use Prokerala\Astrology\Vendor\Http\Client\Exception as HTTPlugException;
/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ClientException extends \RuntimeException implements ExceptionInterface, HTTPlugException
{
}
