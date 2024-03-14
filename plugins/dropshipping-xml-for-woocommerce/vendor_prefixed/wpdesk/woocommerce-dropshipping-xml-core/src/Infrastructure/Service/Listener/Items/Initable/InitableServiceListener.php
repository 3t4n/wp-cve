<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction\AbstractServiceListener;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface;
/**
 * Class InitableServiceListener, chceck if service implements initable interface and run it.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Initable
 */
final class InitableServiceListener extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction\AbstractServiceListener
{
    /**
     * @see AbstractServiceListener::update()
     */
    public function update($service, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface $service_container)
    {
        if ($service instanceof \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable) {
            $service->init();
        }
    }
}
