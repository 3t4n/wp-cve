<?php

namespace Baqend\SDK\Exception;

use Baqend\SDK\Value\MediaType;
use Psr\Http\Message\ResponseInterface;

/**
 * Class BadRequestException created on 13.11.18.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Exception
 */
class BadRequestException extends \Exception
{

    /** @var ResponseInterface */
    private $response;

    /**
     * InvalidAuthorizationException constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response) {
        $this->response = $response;
        $type = MediaType::parse($response->getHeaderLine('content-type'))->withoutParameter();

        if ($type !== MediaType::application('json')) {
            parent::__construct('Unsuspected "400 Bad Request" returned from server.', 400);
            return;
        }

        $body = json_decode($response->getBody()->getContents(), true);
        parent::__construct('Server answered with 400 Bad Request: "'.$body['message'].'"', 400);
    }
}
