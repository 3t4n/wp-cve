<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Persistence;

use OctolizeShippingNoticesVendor\Psr\Container\NotFoundExceptionInterface;
trait FallbackFromGetTrait
{
    public function get_fallback(string $id, $fallback = null)
    {
        try {
            return $this->get($id);
        } catch (\OctolizeShippingNoticesVendor\Psr\Container\NotFoundExceptionInterface $e) {
            return $fallback;
        }
    }
}
