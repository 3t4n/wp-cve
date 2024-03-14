<?php

namespace FedExVendor\WPDesk\FedexShippingService\FedexApi;

use FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use FedExVendor\WPDesk\AbstractShipping\Rate\SingleRate;
use FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition;
/**
 * Can filter rates using custom services settings.
 *
 * @package WPDesk\FedexShippingService\FedexApi
 */
class FedexRateCustomServicesFilter implements \FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating
{
    /** @var ShipmentRating */
    private $rating;
    /** @var SettingsValues */
    private $settings;
    public function __construct(\FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating $rating, \FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        $this->rating = $rating;
        $this->settings = $settings;
    }
    /**
     * Get filtered ratings.
     *
     * @return SingleRate[]
     */
    public function get_ratings()
    {
        $rates = [];
        $ratings = $this->rating->get_ratings();
        if (!empty($ratings)) {
            $services = $this->settings->get_value(\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_SERVICES_TABLE);
            if ($this->is_custom_services_enable($this->settings)) {
                foreach ($ratings as $service_id => $service) {
                    if (isset($service->service_type) && isset($services[$service->service_type]) && !empty($services[$service->service_type]['enabled'])) {
                        $service->service_name = $services[$service->service_type]['name'];
                        $rates[$service->service_type] = $service;
                    }
                }
                $rates = $this->sort_services($rates, $services);
            } else {
                $possible_services = \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::SERVICES;
                foreach ($ratings as $service_id => $service) {
                    if (isset($service->service_type) && isset($possible_services[$service->service_type])) {
                        $service->service_name = \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::SERVICES[$service->service_type];
                        $rates[$service->service_type] = $service;
                    }
                }
            }
        }
        return $rates;
    }
    /**
     * Sort rates according to order set in admin settings.
     *
     * @param SingleRate[] $rates Rates.
     * @param array $option_services Saved services to settings.
     *
     * @return SingleRate[]
     */
    private function sort_services($rates, $option_services)
    {
        if (!empty($option_services)) {
            $services = [];
            foreach ($option_services as $service_code => $service_name) {
                if (isset($rates[$service_code])) {
                    $services[] = $rates[$service_code];
                }
            }
            return $services;
        }
        return $rates;
    }
    /**
     * Are customs service settings enabled.
     *
     * @param SettingsValues $settings Values.
     *
     * @return bool
     */
    private function is_custom_services_enable(\FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        return $settings->has_value(\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_ENABLE_CUSTOM_SERVICES) && 'yes' === $settings->get_value(\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_ENABLE_CUSTOM_SERVICES);
    }
}
