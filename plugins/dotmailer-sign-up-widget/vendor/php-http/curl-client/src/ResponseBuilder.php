<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Http\Client\Curl;

use Dotdigital_WordPress_Vendor\Http\Message\Builder\ResponseBuilder as OriginalResponseBuilder;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ResponseInterface;
/**
 * Extended response builder.
 */
class ResponseBuilder extends OriginalResponseBuilder
{
    /**
     * Replace response with a new instance.
     *
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response) : void
    {
        $this->response = $response;
    }
}
