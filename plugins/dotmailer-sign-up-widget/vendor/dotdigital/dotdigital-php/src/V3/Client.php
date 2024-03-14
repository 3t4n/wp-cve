<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\V3;

use Dotdigital_WordPress_Vendor\Dotdigital\AbstractClient;
use Dotdigital_WordPress_Vendor\Dotdigital\Exception\ResponseValidationException;
use Dotdigital_WordPress_Vendor\Dotdigital\HttpClient\Message\V3\ResponseMediator;
use Dotdigital_WordPress_Vendor\Dotdigital\V3\Resources\Contacts;
use Dotdigital_WordPress_Vendor\Dotdigital\V3\Resources\InsightData;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ResponseInterface;
/**
 * @property Contacts $contacts
 * @property InsightData $insightData
 */
class Client extends AbstractClient
{
    /**
     * @param ResponseInterface $response
     * @return string
     * @throws ResponseValidationException
     */
    public function mediateResponse($response) : string
    {
        return ResponseMediator::getContent($response);
    }
}
