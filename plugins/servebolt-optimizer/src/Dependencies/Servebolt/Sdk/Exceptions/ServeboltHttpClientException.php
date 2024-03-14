<?php

namespace Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Exceptions;

use Servebolt\Optimizer\Dependencies\GuzzleHttp\Exception\ClientException;
use Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Response;

class ServeboltHttpClientException extends ClientException
{

    public function getDecodeMessage() : object
    {
        return json_decode($this->getResponse()->getBody()->getContents());
    }

    public function getResponseObject() : Response
    {
        return new Response($this->getDecodeMessage(), $this->getResponse()->getStatusCode());
    }
}
