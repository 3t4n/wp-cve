<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface;
/**
 * Class AbstractServiceListener, abstraction layer for service listener.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener
 */
abstract class AbstractServiceListener
{
    /**
     * Stop propagation, if true then listener stops checking next listeners, if false go to the next listener.
     *
     * @return bool
     */
    public function stop_propagation() : bool
    {
        return \false;
    }
    /**
     * @param Object                    $service
     * @param ServiceContainerInterface $service_container
     *
     * @return void
     */
    public abstract function update($service, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface $service_container);
}
