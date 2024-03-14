<?php

namespace Baqend\SDK\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseException created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Exception
 */
class ResponseException extends \Exception
{

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * ResponseException constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response) {
        $status = $response->getStatusCode();
        $phrase = $response->getReasonPhrase();
        $message = "Error in response: $status $phrase";

        parent::__construct($message);
        $this->response = $response;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse() {
        return $this->response;
    }
}
