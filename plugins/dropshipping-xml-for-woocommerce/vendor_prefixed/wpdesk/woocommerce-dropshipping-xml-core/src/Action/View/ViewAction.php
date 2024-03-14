<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ViewActionFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable;
/**
 * Class ViewAction, show view based on request.
 * @package WPDesk\Library\DropshippingXmlCore\Action\View
 */
class ViewAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ViewActionFactory
     */
    private $factory;
    /**
     * @var ServiceContainerInterface
     */
    private $service_container;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ViewActionFactory $factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface $service_container)
    {
        $this->request = $request;
        $this->factory = $factory;
        $this->service_container = $service_container;
    }
    public function show()
    {
        $view = $this->factory->create_from_request($this->request);
        $this->service_container->register($view);
        $view->show();
    }
}
