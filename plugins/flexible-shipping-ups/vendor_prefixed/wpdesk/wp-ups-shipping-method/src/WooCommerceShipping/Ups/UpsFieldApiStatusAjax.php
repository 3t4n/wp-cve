<?php

/**
 * Ajax status handler.
 *
 * @package WPDesk\WooCommerceShipping\Ups
 */
namespace UpsFreeVendor\WPDesk\WooCommerceShipping\Ups;

use UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\ConnectionChecker;
use UpsFreeVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax;
/**
 * Can handle api status ajax request.
 */
class UpsFieldApiStatusAjax extends \UpsFreeVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax
{
    /**
     * Check connection error.
     *
     * @return string|false
     */
    protected function check_connection_error()
    {
        try {
            $this->ping();
            return \false;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * Ping api.
     *
     * @throws \Exception
     */
    private function ping()
    {
        $connection_checker = new \UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\ConnectionChecker($this->get_shipping_service(), $this->get_settings(), $this->get_logger());
        $connection_checker->check_connection();
    }
}
