<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Entity\Traits;

trait EntityUpdater
{
    /**
     * Update parameters on entity.
     *
     * @param array $parameters
     */
    public function update(array $parameters)
    {
        foreach ($parameters as $k => $p) {
            $method_name = 'set_' . \strtolower($k);
            if (\method_exists($this, $method_name)) {
                $this->{$method_name}($p);
            }
        }
    }
}
