<?php

namespace FedExVendor\WPDesk\WooCommerceShipping\AddMethodReminder;

use FedExVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Can append reminder data to deactivation tracker.
 */
class DeactivationTrackerData implements \FedExVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    use TrackerOption;
    const ADDITIONAL_DATA = 'additional_data';
    /**
     * @var string
     */
    private $method_id;
    /**
     * @param string $method_id
     */
    public function __construct(string $method_id)
    {
        $this->method_id = $method_id;
    }
    public function hooks()
    {
        \add_filter('wpdesk_tracker_deactivation_data', [$this, 'append_deactivation_tracker_data']);
    }
    /**
     * @param array $data
     *
     * @return array
     */
    public function append_deactivation_tracker_data($data)
    {
        if (\is_array($data)) {
            if (!isset($data[self::ADDITIONAL_DATA]) || !\is_array($data[self::ADDITIONAL_DATA])) {
                $data[self::ADDITIONAL_DATA] = [];
            }
            $data[self::ADDITIONAL_DATA][$this->get_tracker_option_name($this->method_id)] = $this->get_tracker_option_value($this->method_id);
        }
        return $data;
    }
}
