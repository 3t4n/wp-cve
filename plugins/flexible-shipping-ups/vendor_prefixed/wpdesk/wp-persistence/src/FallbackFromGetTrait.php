<?php

namespace UpsFreeVendor\WPDesk\Persistence;

use UpsFreeVendor\Psr\Container\NotFoundExceptionInterface;
trait FallbackFromGetTrait
{
    public function get_fallback(string $id, $fallback = null)
    {
        try {
            return $this->get($id);
        } catch (\UpsFreeVendor\Psr\Container\NotFoundExceptionInterface $e) {
            return $fallback;
        }
    }
}
