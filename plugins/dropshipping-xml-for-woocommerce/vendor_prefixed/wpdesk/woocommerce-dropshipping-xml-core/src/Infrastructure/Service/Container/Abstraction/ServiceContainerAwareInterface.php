<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction;

/**
 * Interface ServiceContainerAwareInterface allows to set service container.
 */
interface ServiceContainerAwareInterface
{
    /**
     * Service container setter.
     *
     * @param ServiceContainerInterface $service_container
     */
    public function set_service_container(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface $service_container);
}
