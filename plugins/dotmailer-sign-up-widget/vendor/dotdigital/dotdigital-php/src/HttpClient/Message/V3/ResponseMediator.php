<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\HttpClient\Message\V3;

use Dotdigital_WordPress_Vendor\Dotdigital\Exception\ResponseValidationException;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ResponseInterface;
class ResponseMediator
{
    /**
     * @var int[] $passableStatusCodes
     */
    private static $passableStatusCodes = [200, 201, 202, 204];
    /**
     * @param ResponseInterface $response
     *
     * @return string
     * @throws ResponseValidationException
     */
    public static function getContent(ResponseInterface $response)
    {
        if (!\in_array($response->getStatusCode(), self::$passableStatusCodes)) {
            throw ResponseValidationException::fromErrorResponse($response);
        }
        return $response->getBody()->getContents();
    }
}
