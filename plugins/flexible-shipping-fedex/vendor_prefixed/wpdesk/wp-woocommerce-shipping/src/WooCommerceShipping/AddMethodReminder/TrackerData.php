<?php

namespace FedExVendor\WPDesk\WooCommerceShipping\AddMethodReminder;

use FedExVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Can append reminder data to tracker.
 */
class TrackerData implements \FedExVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    use TrackerOption;
    /**
     * @var string
     */
    private $method_id;
    /**
     * @param string $method_id
     * @param string $tracker_name
     */
    public function __construct(string $method_id, string $tracker_name)
    {
        $this->method_id = $method_id;
        $this->tracker_name = $tracker_name;
    }
    public function hooks()
    {
        \add_filter('wpdesk_tracker_data', [$this, 'append_tracker_data']);
    }
    /**
     * @param array $data
     *
     * @return array
     */
    public function append_tracker_data($data)
    {
        if (\is_array($data)) {
            if (!isset($data[$this->tracker_name]) || !\is_array($data[$this->tracker_name])) {
                $data[$this->tracker_name] = [];
            }
            $data[$this->tracker_name][$this->get_tracker_option_name($this->method_id)] = $this->get_tracker_option_value($this->method_id);
        }
        return $data;
    }
}
