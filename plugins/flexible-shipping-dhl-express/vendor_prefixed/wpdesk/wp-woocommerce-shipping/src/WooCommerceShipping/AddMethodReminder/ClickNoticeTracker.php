<?php

namespace DhlVendor\WPDesk\WooCommerceShipping\AddMethodReminder;

use DhlVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use DhlVendor\WPDesk\ShowDecision\GetStrategy;
/**
 * Can track click notice link.
 */
class ClickNoticeTracker implements \DhlVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    use TrackerOption;
    private $method_id;
    /**
     * @param $method_id
     */
    public function __construct($method_id)
    {
        $this->method_id = $method_id;
    }
    public function hooks()
    {
        \add_action('admin_init', [$this, 'track_click']);
    }
    public function track_click()
    {
        $display_strategy = new \DhlVendor\WPDesk\ShowDecision\GetStrategy([['page' => 'wc-settings', 'tab' => 'shipping']]);
        if ($display_strategy->shouldDisplay() && isset($_GET['track_click_notice_method']) && \wp_verify_nonce($_GET['track_click_notice_method'], $this->method_id)) {
            $this->increase_tracker_option_value($this->method_id);
        }
    }
}
