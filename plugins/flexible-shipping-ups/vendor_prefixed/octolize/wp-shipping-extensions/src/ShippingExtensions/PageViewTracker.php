<?php

namespace UpsFreeVendor\Octolize\ShippingExtensions;

use UpsFreeVendor\Octolize\ShippingExtensions\Tracker\ViewPageTracker;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * .
 */
class PageViewTracker implements \UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    use AdminPage;
    /**
     * @var ViewPageTracker
     */
    private $tracker;
    /**
     * @param ViewPageTracker $tracker .
     */
    public function __construct(\UpsFreeVendor\Octolize\ShippingExtensions\Tracker\ViewPageTracker $tracker)
    {
        $this->tracker = $tracker;
    }
    /**
     * @return void
     */
    public function hooks() : void
    {
        \add_action('in_admin_header', [$this, 'view_tracking']);
    }
    /**
     * @return void
     */
    public function view_tracking() : void
    {
        if (!$this->is_shipping_extensions_page()) {
            return;
        }
        if (isset($_GET[\UpsFreeVendor\Octolize\ShippingExtensions\PluginLinks::PLUGIN_LINKS_PAGE])) {
            $this->tracker->add_view_plugins_list();
        } else {
            $this->tracker->add_view_direct();
        }
    }
}
