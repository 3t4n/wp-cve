<?php

namespace UpsFreeVendor\WPDesk\UpsShippingService;

use UpsFreeVendor\WPDesk\AbstractShipping\Exception\InvalidSettingsException;
use UpsFreeVendor\WPDesk\AbstractShipping\Exception\RateException;
use UpsFreeVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use UpsFreeVendor\WPDesk\AbstractShipping\Rate\SingleRate;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsSenderSingleRate;
/**
 * Ups main shipping class injected into WooCommerce shipping method.
 */
class UpsSurepostShippingService extends \UpsFreeVendor\WPDesk\UpsShippingService\AbstractUpsShippingService
{
    const UNIQUE_ID = 'flexible_shipping_ups_surepost';
    /**
     * Rate shipment.
     *
     * @param SettingsValues $settings Settings.
     * @param Shipment       $shipment Shipment.
     *
     * @return ShipmentRating
     * @throws InvalidSettingsException InvalidSettingsException.
     * @throws RateException RateException.
     */
    public function rate_shipment(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment)
    {
        $ups_shipment_rating = new \UpsFreeVendor\WPDesk\UpsShippingService\UpsShipmentRatingImplementation([], \false);
        $ups_services = new \UpsFreeVendor\WPDesk\UpsShippingService\UpsServices();
        if ($this->is_us_domestic_shipment($shipment)) {
            $surepost_services = $settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::SUREPOST_SERVICES, []);
            foreach ($surepost_services as $surepost_service) {
                if (isset($surepost_service['enabled']) && $this->should_add_surepost_service($ups_shipment_rating->get_ratings(), $surepost_service['enabled'], $ups_services->get_surepost_same_services())) {
                    $surepost_service_code = $surepost_service['enabled'];
                    try {
                        $sender = $this->create_single_rate_sender($settings, $surepost_service_code);
                        $ups_shipment_rating->merge_ratings($this->rate_shipment_for_ups($settings, $shipment, $surepost_services, $sender));
                    } catch (\Exception $e) {
                        $this->get_logger()->info(\sprintf('UPS surepost rate not added! %1$s', $e->getMessage()));
                    }
                }
            }
        }
        return $ups_shipment_rating;
    }
    protected function create_single_rate_sender(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, string $surepost_service_code)
    {
        if ($settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::API_TYPE, \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::API_TYPE_XML) === \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::API_TYPE_REST) {
            return new \UpsFreeVendor\Octolize\Ups\RestApi\UpsRestApiSenderSingleRate($this->rest_api_client, $surepost_service_code, $this->get_logger(), $this->is_testing($settings), $this->get_shop_settings()->is_tax_enabled());
        } else {
            return new \UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsSenderSingleRate($settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ACCESS_KEY), $settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::USER_ID), $settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::PASSWORD), $surepost_service_code, $this->get_logger(), $this->is_testing($settings), $this->get_shop_settings()->is_tax_enabled());
        }
    }
    /**
     * @param SingleRate[] $ratings .
     * @param string       $service .
     * @param array<array> $same_services .
     */
    private function should_add_surepost_service(array $ratings, $service, $same_services)
    {
        foreach ($ratings as $single_rate) {
            foreach ($same_services as $same_service) {
                if (\in_array($single_rate->service_type, $same_service, \true) && \in_array($service, $same_service, \true)) {
                    return \false;
                }
            }
        }
        return \true;
    }
    /**
     * @param Shipment $shipment .
     *
     * @return bool
     */
    private function is_us_domestic_shipment(\UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment)
    {
        return 'US' === $shipment->ship_from->address->country_code && 'US' === $shipment->ship_to->address->country_code;
    }
    /**
     * Get unique ID.
     *
     * @return string
     */
    public function get_unique_id()
    {
        return self::UNIQUE_ID;
    }
    /**
     * Get name.
     *
     * @return string
     */
    public function get_name()
    {
        return \__('UPS SurePost Live Rates', 'flexible-shipping-ups');
    }
    /**
     * Get description.
     *
     * @return string
     */
    public function get_description()
    {
        return \__('UPS integration', 'flexible-shipping-ups');
    }
    /**
     * Get settings
     *
     * @return UpsSurepostSettingsDefinition
     */
    public function get_settings_definition()
    {
        return new \UpsFreeVendor\WPDesk\UpsShippingService\UpsSurepostSettingsDefinition($this->get_shop_settings());
    }
}
