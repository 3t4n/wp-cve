<?php

namespace FedExVendor\WPDesk\WooCommerceShipping\AddMethodReminder;

trait TrackerOption
{
    /**
     * @param string $method_id
     *
     * @return string
     */
    private function get_tracker_option_name($method_id)
    {
        return 'octolize_add_method_reminder_' . $method_id;
    }
    /**
     * @param string $method_id
     *
     * @return int
     */
    private function get_tracker_option_value($method_id)
    {
        return (int) \get_option($this->get_tracker_option_name($method_id), 0);
    }
    /**
     * @param string $method_id
     *
     * @return void
     */
    private function increase_tracker_option_value($method_id)
    {
        \update_option($this->get_tracker_option_name($method_id), $this->get_tracker_option_value($method_id) + 1);
    }
}
