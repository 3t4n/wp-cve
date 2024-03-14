<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Common\Dto;

use WPPayVendor\BlueMedia\HttpClient\ValueObject\Request;
use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\JMS\Serializer\Annotation\Type;
abstract class AbstractDto
{
    /**
     * @var string
     * @Type("string");
     */
    protected $gatewayUrl;
    /**
     * @var Request
     * @Type("WPPayVendor\BlueMedia\HttpClient\ValueObject\Request");
     */
    protected $request;
    /**
     * @return string
     */
    public function getGatewayUrl() : string
    {
        return $this->gatewayUrl;
    }
    public function setRequest(\WPPayVendor\BlueMedia\HttpClient\ValueObject\Request $request) : self
    {
        $this->request = $request;
        return $this;
    }
    public function getRequest() : ?\WPPayVendor\BlueMedia\HttpClient\ValueObject\Request
    {
        return $this->request;
    }
    public abstract function getRequestData() : \WPPayVendor\BlueMedia\Serializer\SerializableInterface;
}
