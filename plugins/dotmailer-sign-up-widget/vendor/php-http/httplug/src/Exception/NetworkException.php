<?php

namespace Dotdigital_WordPress_Vendor\Http\Client\Exception;

use Dotdigital_WordPress_Vendor\Psr\Http\Client\NetworkExceptionInterface as PsrNetworkException;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestInterface;
/**
 * Thrown when the request cannot be completed because of network issues.
 *
 * There is no response object as this exception is thrown when no response has been received.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class NetworkException extends TransferException implements PsrNetworkException
{
    use RequestAwareTrait;
    /**
     * @param string $message
     */
    public function __construct($message, RequestInterface $request, \Exception $previous = null)
    {
        $this->setRequest($request);
        parent::__construct($message, 0, $previous);
    }
}
