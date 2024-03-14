<?php

namespace FRFreeVendor\WPDesk\Persistence;

use FRFreeVendor\Psr\Container\NotFoundExceptionInterface;
trait FallbackFromGetTrait
{
    public function get_fallback(string $id, $fallback = null)
    {
        try {
            return $this->get($id);
        } catch (\FRFreeVendor\Psr\Container\NotFoundExceptionInterface $e) {
            return $fallback;
        }
    }
}
