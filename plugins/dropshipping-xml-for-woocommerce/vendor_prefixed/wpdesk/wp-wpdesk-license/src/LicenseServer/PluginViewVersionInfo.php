<?php

namespace DropshippingXmlFreeVendor\WPDesk\License\LicenseServer;

use DropshippingXmlFreeVendor\WPDesk_Plugin_Info;
/**
 * Attaches to WordPress hooks to display plugin version info.
 *
 * @package WPDesk\License\LicenseServer
 */
class PluginViewVersionInfo
{
    private $server;
    /** @var \WPDesk_Plugin_Info */
    private $plugin_info;
    public function __construct($plugin_info, string $server)
    {
        $this->plugin_info = $plugin_info;
        $this->server = $server;
    }
    public function hooks()
    {
        \add_filter('plugins_api', function ($false, $action, $args) {
            if ($action === 'plugin_information' && $args->slug === $this->plugin_info->get_plugin_slug()) {
                $remote_response = \wp_remote_get("{$this->server}/api/v1/plugin/{$this->plugin_info->get_product_id()}");
                if ($remote_response instanceof \WP_Error) {
                    \error_log("Error while trying to get plugin info: " . \json_encode($remote_response));
                    return $false;
                }
                $parsed_response = \json_decode($remote_response['body'], \true);
                if ($parsed_response) {
                    $response = new \stdClass();
                    $response->name = $this->plugin_info->get_plugin_name();
                    $response->slug = $parsed_response['slug'];
                    $response->version = $parsed_response['version'];
                    $response->homepage = $parsed_response['homepage'];
                    $response->requires = $parsed_response['requires'];
                    $response->tested = $parsed_response['tested'];
                    $response->testedWC = $parsed_response['testedWC'];
                    $response->requiresWC = $parsed_response['requiresWC'];
                    $response->last_updated = \date_i18n('Y-n-d H:i:s', $parsed_response['last_updated']);
                    $response->requires_php = $parsed_response['requires_php'];
                    $response->sections = [];
                    $response->sections['description'] = $parsed_response['sections']['description'] ?? '';
                    $response->sections['changelog'] = \nl2br($parsed_response['sections']['changelog'] ?? '');
                    return $response;
                }
                \error_log("Response from license server cannot be parsed: " . \json_encode($remote_response));
            }
            return $false;
        }, 10, 3);
    }
}
