<?php

namespace DhlVendor\WPDesk\Persistence;

use DhlVendor\Psr\Container\NotFoundExceptionInterface;
trait FallbackFromGetTrait
{
    public function get_fallback(string $id, $fallback = null)
    {
        try {
            return $this->get($id);
        } catch (\DhlVendor\Psr\Container\NotFoundExceptionInterface $e) {
            return $fallback;
        }
    }
}
