<?php

namespace Baqend\SDK\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class InvalidAuthorizationException created on 16.10.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Exception
 */
class InvalidAuthorizationException extends \Exception
{

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * InvalidAuthorizationException constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response) {
        $error = json_decode($response->getBody()->getContents());
        if (is_object($error)) {
            $message = 'Sending the request failed because of authorization: '.$error->message.'.';
        } else {
            $message = 'Sending the request failed because of an unknown error.';
        }
        parent::__construct($message, 0);
        $this->response = $response;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse() {
        return $this->response;
    }
}
