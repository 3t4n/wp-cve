<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional;

/**
 * Interface Conditional, check if service should run next listener for exmaple Initable, Registrable etc.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Conditional
 */
interface Conditional
{
    public function isActive() : bool;
}
