<?php

namespace DropshippingXmlFreeVendor\WPDesk\License\LicenseServer;

use DropshippingXmlFreeVendor\WPDesk_Plugin_Info;
/**
 * Idea is to have a class that will be responsible for checking if external requests are blocked.
 * Can show a notice if external requests are blocked.
 *
 * @package WPDesk\License\LicenseServer
 */
class PluginExternalBlocking
{
    /** @var \WPDesk_Plugin_Info */
    private $plugin_info;
    public function __construct($plugin_info, string $server, string $token)
    {
        $this->plugin_info = $plugin_info;
    }
    /**
     * Check for external blocking constants
     */
    public function display_info_when_external_blocking()
    {
        // show notice if external requests are blocked through the WP_HTTP_BLOCK_EXTERNAL constant
        if (\defined('DropshippingXmlFreeVendor\\WP_HTTP_BLOCK_EXTERNAL') && WP_HTTP_BLOCK_EXTERNAL === \true) {
            // check if our API endpoint is in the allowed hosts
            $host = \parse_url($this->server, \PHP_URL_HOST);
            if (!\defined('DropshippingXmlFreeVendor\\WP_ACCESSIBLE_HOSTS') || \stristr(WP_ACCESSIBLE_HOSTS, $host) === \false) {
                ?>
				<div class="error">
					<p><?php 
                \printf(\__('<b>Warning!</b> You\'re blocking external requests which means you won\'t be able to get %s updates. Please add %s to %s.', 'dropshipping-xml-for-woocommerce'), $this->plugin_info->get_plugin_name(), '<strong>' . $host . '</strong>', '<code>WP_ACCESSIBLE_HOSTS</code>');
                ?></p>
				</div>
				<?php 
            }
        }
    }
    public function hooks()
    {
        \add_action('admin_notices', [$this, 'display_info_when_external_blocking']);
    }
}
