<?php

namespace DhlVendor\Octolize\Tracker;

use DhlVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Can create and register sender in filter.
 */
class SenderRegistrator implements \DhlVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var string
     */
    private $plugin_slug;
    /**
     * @param string $plugin_slug
     */
    public function __construct(string $plugin_slug)
    {
        $this->plugin_slug = $plugin_slug;
    }
    /**
     * @return void
     */
    public function hooks()
    {
        \add_filter('wpdesk/tracker/sender/' . $this->plugin_slug, [$this, 'create_sender']);
    }
    /**
     * @return SenderToOctolize
     */
    public function create_sender()
    {
        return new \DhlVendor\Octolize\Tracker\SenderToOctolize();
    }
}
