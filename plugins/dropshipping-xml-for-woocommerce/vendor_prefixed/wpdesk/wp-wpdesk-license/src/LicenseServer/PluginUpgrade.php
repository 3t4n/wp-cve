<?php

namespace DropshippingXmlFreeVendor\WPDesk\License\LicenseServer;

/**
 * Retrieves updates from license server.
 *
 * @package WPDesk\License\LicenseServer
 */
class PluginUpgrade
{
    private $server;
    private $token;
    /** @var \WPDesk_Plugin_Info */
    private $plugin_info;
    public function __construct($plugin_info, string $server, string $token)
    {
        $this->server = $server;
        $this->token = $token;
        $this->plugin_info = $plugin_info;
    }
    /**
     * @param string|array|\WP_Error|\stdClass $value Any response from remote server or request routine.
     *
     * @return mixed|\stdClass
     * @internal
     */
    public function inject_info_about_plugin_update_from_remote($value)
    {
        // on info about upgrade
        if ($value instanceof \stdClass) {
            // every each time only timestamp mutex is saved and when it's not the stdClass is here
            $plugin_filename = $this->plugin_info->get_plugin_file_name();
            if (isset($value->response[$plugin_filename])) {
                // pre_set_site_transient is used two times and we need only once
                return $value;
            }
            $remote_response = $this->request_remote_plugin_info_plugin_update_check();
            if ($remote_response instanceof \WP_Error) {
                // WP Error while connecting to remote server
                $value->response[$plugin_filename] = $this->react_to_wp_error_response($remote_response);
            } else {
                // there is a response
                $parsed_response = \json_decode($remote_response['body'], \true);
                if (!empty($parsed_response) && $parsed_response['code'] >= 200) {
                    // response makes sense and had been parsed
                    if ($parsed_response['code'] !== 503) {
                        $value->response[$plugin_filename] = $this->react_to_valid_remote_response($parsed_response);
                    }
                    if (empty($value->response[$plugin_filename]->message) && empty($value->response[$plugin_filename]->need_update)) {
                        unset($value->response[$plugin_filename]);
                    }
                } else {
                    // there is a response, but it makes no sense and cannot be parsed
                    $value->response[$plugin_filename] = $this->react_to_nonsense_response($remote_response);
                }
            }
        }
        return $value;
    }
    /**
     * @return array|\WP_Error The same typ of response as wp_remote_request
     */
    private function request_remote_plugin_info_plugin_update_check()
    {
        global $wp_version;
        $params = ["site_url" => \get_site_url(), "plugin_name" => $this->plugin_info->get_plugin_name(), "plugin_slug" => $this->plugin_info->get_plugin_slug(), "plugin_version" => $this->plugin_info->get_version(), "php_version" => \PHP_VERSION, "wc_version" => \class_exists('WooCommerce') ? \WooCommerce::instance()->version : '', "wp_version" => $wp_version, 'locale' => \str_replace('-', '_', \get_bloginfo('language'))];
        $product_id = \urlencode($this->plugin_info->get_product_id());
        return \wp_remote_request("{$this->server}/api/v1/cid/{$this->token}/plugin/{$product_id}", ['body' => \json_encode($params), 'method' => 'POST']);
    }
    /**
     * React when there an error while retrieving data from remote server.
     *
     * @param \WP_Error $remote_response
     *
     * @return \stdClass
     */
    private function react_to_wp_error_response(\WP_Error $remote_response) : \stdClass
    {
        $response = $this->prepare_transient_base();
        $response->message = \wp_kses_post('<span style="color: red" class="error">' . \sprintf(\__('Error while connecting to remote server %s. Please contact your hosting provider or try again later. Errors:  ', 'dropshipping-xml-for-woocommerce'), $this->server) . \implode(', ', $remote_response->get_error_messages()) . '</span>');
        return $response;
    }
    /**
     * Prepares response object to store in WP transient to avoid code duplication.
     *
     * @return \stdClass
     */
    private function prepare_transient_base() : \stdClass
    {
        $response = new \stdClass();
        $response->id = $this->plugin_info->get_plugin_slug();
        $response->slug = $this->plugin_info->get_plugin_slug();
        $response->plugin = $this->plugin_info->get_plugin_file_name();
        return $response;
    }
    /**
     * We have a valid response from server so we can react to it: show upgrade possibility or not.
     *
     * @param array $parsed_response
     *
     * @return \stdClass
     */
    private function react_to_valid_remote_response(array $parsed_response) : \stdClass
    {
        $response = $this->prepare_transient_base();
        // Just setting these two fields below is enough to show upgrade possibility
        $response->new_version = $parsed_response['version'] ?? null;
        $response->package = $parsed_response['package'] ?? null;
        $response->requires_php = $parsed_response['requires_php'] ?? null;
        $response->need_update = $parsed_response['need_update'] ?? null;
        $response->changelog = $parsed_response['changelog'] ?? null;
        $response->message = $parsed_response['message'] ?? null;
        // Set license status
        if (empty($parsed_response['package'])) {
            (new \DropshippingXmlFreeVendor\WPDesk\License\LicenseServer\PluginLicense($this->plugin_info))->set_inactive();
        } else {
            (new \DropshippingXmlFreeVendor\WPDesk\License\LicenseServer\PluginLicense($this->plugin_info))->set_active();
        }
        return $response;
    }
    private function render_changelog(string $plugin_version, string $changelog) : string
    {
        $parser = new \DropshippingXmlFreeVendor\WPDesk\License\Changelog\Parser($changelog);
        $parser->parse();
        $parsed_changelog = $parser->get_parsed_changelog()->getIterator();
        $changes = new \DropshippingXmlFreeVendor\WPDesk\License\Changelog\Filter\ByVersion($parsed_changelog, $plugin_version);
        if (\iterator_count($changes) > 0) {
            $changelogFormatter = new \DropshippingXmlFreeVendor\WPDesk\License\Changelog\Formatter($changes);
            $changelogFormatter->set_changelog_types($parser->get_types());
            $formatted_changelog = $changelogFormatter->prepare_formatted_html();
            if ($formatted_changelog) {
                return \wp_kses_post('<br /><br />' . $formatted_changelog);
            }
        }
        return '';
    }
    /**
     * It was not possible to parse a response. Show some error message.
     *
     * @param $remote_response
     *
     * @return \stdClass
     */
    private function react_to_nonsense_response($remote_response) : \stdClass
    {
        $response = $this->prepare_transient_base();
        \error_log("Update for {$this->plugin_info->get_plugin_name()} cannot be retrieved. Remote response invalid: " . \json_encode($remote_response));
        if (isset($remote_response['response']['code'])) {
            $message = \sprintf(\__('Error while connecting to remote server %s. Please contact with your hosting provider. Cannot parse response. Response code: %s Message: %s', 'dropshipping-xml-for-woocommerce'), $this->server, $remote_response['response']['code'], $remote_response['body']);
        } else {
            $message = \sprintf(\__('Error while connecting to remote server %s. Please contact with your hosting provider. Cannot parse response: %s', 'dropshipping-xml-for-woocommerce'), $this->server, \json_encode($remote_response));
        }
        $response->message = \wp_kses_post("<span style='color: red' class='upgrade-error'>{$message}</span>");
        return $response;
    }
    public function show_messages_from_transients(array $plugin_data, \stdClass $response)
    {
        $transient = \get_site_transient('update_plugins');
        $message = $transient->response[$this->plugin_info->get_plugin_file_name()]->message ?? '';
        $changelog = $transient->response[$this->plugin_info->get_plugin_file_name()]->changelog ?? '';
        // Server could have sent as some message to show. Show it.
        if (!empty($message)) {
            echo \wp_kses_post('<br><br>' . $message);
        }
        if (!empty($changelog)) {
            echo $this->render_changelog($plugin_data['Version'], $changelog);
        }
    }
    public function hooks()
    {
        \add_filter('pre_set_site_transient_update_plugins', [$this, 'inject_info_about_plugin_update_from_remote'], 10);
        \add_filter('in_plugin_update_message-' . $this->plugin_info->get_plugin_file_name(), [$this, 'show_messages_from_transients'], 10, 2);
    }
}
