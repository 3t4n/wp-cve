<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction\AbstractServiceListener;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface;
/**
 * Class ConditionalServiceListener, checks is service implements Conditional interface and controll listeners propagation in the service.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Conditional
 */
final class ConditionalServiceListener extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction\AbstractServiceListener
{
    /**
     * @var bool
     */
    private $active = \false;
    /**
     * @see AbstractServiceListener::update()
     */
    public function update($service, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface $service_container)
    {
        $this->active = \false;
        if ($service instanceof \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional) {
            $this->active = !$service->isActive();
        }
    }
    /**
     * @see AbstractServiceListener::stop_propagation()
     */
    public function stop_propagation() : bool
    {
        return $this->active;
    }
}
