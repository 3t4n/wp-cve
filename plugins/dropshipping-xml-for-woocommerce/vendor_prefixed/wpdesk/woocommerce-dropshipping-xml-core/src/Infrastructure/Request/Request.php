<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\Abstraction\DataTypeInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Container\ParameterContainer;
/**
 * Class Request, shares data access methods to request.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Request
 */
class Request
{
    /**
     * @var ParameterContainer
     */
    private $parameter_container;
    public function __construct()
    {
        $this->parameter_container = new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Container\ParameterContainer(array('METHOD' => \strtoupper(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET'), 'GET' => $_GET, 'POST' => $_POST, 'FILES' => $_FILES, 'COOKIE' => $_COOKIE, 'SERVER' => $_SERVER, 'SESSION' => isset($_SESSION) ? $_SESSION : array(), 'INPUT' => \file_get_contents("php://input")));
    }
    public function get_param(string $param) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\Abstraction\DataTypeInterface
    {
        return $this->parameter_container->get_param($param);
    }
}
