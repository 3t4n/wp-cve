<?php

namespace Baqend\SDK\Exception;

use Psr\Http\Message\RequestInterface;

/**
 * Class RequestException created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Exception
 */
class RequestException extends \Exception
{

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var string
     */
    private $reason;

    /**
     * RequestException constructor.
     * @param RequestInterface $request
     * @param string $reason
     * @param \Exception|null $previous
     */
    public function __construct(RequestInterface $request, $reason, \Exception $previous = null) {
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();
        $message = "Error sending $method $path: $reason";

        parent::__construct($message, 0, $previous);
        $this->request = $request;
        $this->reason = $reason;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getReason() {
        return $this->reason;
    }
}
