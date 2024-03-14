<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable;

/**
 * Interface Registrable, return array with class names that should be registered to service container.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Registrable
 */
interface Registrable
{
    /**
     * @return string[]
     */
    public function register() : array;
}
