<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Dotdigital\HttpClient\Message\V2;

use Dotdigital_WordPress_Vendor\Dotdigital\Exception\ResponseValidationException;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ResponseInterface;
class ResponseMediator
{
    /**
     * @param ResponseInterface $response
     *
     * @return string
     * @throws ResponseValidationException
     */
    public static function getContent(ResponseInterface $response)
    {
        $content = $response->getBody()->getContents();
        if (null === \json_decode($content) || \false === \json_decode($content)) {
            throw new ResponseValidationException('Cannot decode response.');
        }
        $decoded = \json_decode($content, \true);
        if (isset($decoded['message'])) {
            throw new ResponseValidationException($decoded['message']);
        }
        return $content;
    }
}
