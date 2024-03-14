<?php

namespace WPDeskFIVendor\WPDesk\Persistence;

use WPDeskFIVendor\Psr\Container\NotFoundExceptionInterface;
trait FallbackFromGetTrait
{
    public function get_fallback(string $id, $fallback = null)
    {
        try {
            return $this->get($id);
        } catch (\WPDeskFIVendor\Psr\Container\NotFoundExceptionInterface $e) {
            return $fallback;
        }
    }
}
