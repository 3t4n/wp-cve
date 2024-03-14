<?php

namespace Dotdigital_WordPress_Vendor\Http\Client\Exception;

use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestInterface;
trait RequestAwareTrait
{
    /**
     * @var RequestInterface
     */
    private $request;
    private function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }
    /**
     * {@inheritdoc}
     */
    public function getRequest() : RequestInterface
    {
        return $this->request;
    }
}
