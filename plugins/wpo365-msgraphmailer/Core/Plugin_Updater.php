<?php

namespace Wpo\Core;

use \Wpo\Core\Extensions_Helpers;
use \Wpo\Core\WordPress_Helpers;
use \Wpo\Core\Wpmu_Helpers;
use \Wpo\Services\Log_Service;
use \Wpo\Services\Options_Service;

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Allows plugins to use their own update API.
 *
 * @author Easy Digital Downloads
 * @version 1.6.18
 */
class Plugin_Updater
{
    private $api_url              = '';
    private $api_data             = array();
    private $plugin_file          = '';
    private $name                 = '';
    private $slug                 = '';
    private $version              = '';
    private $wp_override          = false;
    private $beta                 = false;
    private $failed_request_cache_key;

    /**
     * Class constructor.
     *
     * @uses plugin_basename()
     * @uses hook()
     *
     * @param string  $_api_url     The URL pointing to the custom API endpoint.
     * @param string  $_plugin_file Path to the plugin file.
     * @param array   $_api_data    Optional data to send with API calls.
     */
    public function __construct($_api_url, $_plugin_file, $name, $slug, $_api_data = null)
    {
        global $edd_plugin_data;

        $this->api_url                  = trailingslashit($_api_url);
        $this->api_data                 = $_api_data;
        $this->plugin_file              = $_plugin_file;
        $this->name                     = wp_normalize_path($name);
        $this->slug                     = $slug;
        $this->version                  = $_api_data['version'];
        $this->wp_override              = isset($_api_data['wp_override']) ? (bool) $_api_data['wp_override'] : false;
        $this->beta                     = !empty($this->api_data['beta']) ? true : false;
        $this->failed_request_cache_key = 'edd_sl_failed_http_' . md5($this->api_url);

        $edd_plugin_data[$this->slug] = $this->api_data;

        /**
         * Fires after the $edd_plugin_data is setup.
         *
         * @since x.x.x
         *
         * @param array $edd_plugin_data Array of EDD SL plugin data.
         */
        do_action('post_edd_sl_plugin_updater_setup', $edd_plugin_data);

        // Set up hooks.
        $this->init();
    }

    /**
     * Set up WordPress filters to hook into WP's update process.
     *
     * @uses add_filter()
     *
     * @return void
     */
    public function init()
    {
        add_filter('plugins_api', array($this, 'plugins_api_filter'), 10, 3);
        add_action('after_plugin_row', array($this, 'show_update_notification'), 10, 2);
        add_action('admin_init', array($this, 'show_changelog'));
    }

    public static function add_hooks()
    {
        add_filter('pre_set_site_transient_update_plugins', function ($transient_data) {

            if (empty($transient_data)) {
                return $transient_data;
            }

            $wpo365_plugins_updated = get_site_transient('wpo365_plugins_updated');
            $force_check = isset($_GET['action']) && 'force_plugin_updates_check' == $_GET['action']; // https://wordpress.org/plugins/force-plugin-updates-check/
            $recently_checked = !empty($wpo365_plugins_updated) && isset($wpo365_plugins_updated['last_checked']) && time() - $wpo365_plugins_updated['last_checked'] < 12 * HOUR_IN_SECONDS;

            if (!$force_check && function_exists('get_current_screen')) {

                $current_screen = get_current_screen();

                if (!empty($current_screen) && ($current_screen->id == 'plugins' || $current_screen->id == 'update-core')) {
                    $force_check = true;
                }
            }

            if (!$force_check && $recently_checked) {
                return $transient_data;
            }

            \Wpo\Core\Plugin_Updater::check_licenses();
            $transient_data = \Wpo\Core\Plugin_Updater::check_for_updates($transient_data);

            set_site_transient('wpo365_plugins_updated', array('last_checked' => time()));
            return $transient_data;
        });

        add_action('upgrader_process_complete', '\Wpo\Core\Plugin_Updater::check_for_updates');
        add_action('load-plugins.php', '\Wpo\Core\Plugin_Updater::check_for_updates');
        add_action('load-update-core.php', '\Wpo\Core\Plugin_Updater::check_for_updates');
        add_action('load-update.php', '\Wpo\Core\Plugin_Updater::check_for_updates');
    }

    /**
     * Check for newer versions of any of the extensions.
     * 
     * @since 11.6
     * 
     * Changed to EDD plugin updater since 7.15
     * 
     * @return object $transient_data
     */
    public static function check_for_updates($transient_data = null)
    {
        $extensions = Extensions_Helpers::get_extensions();

        foreach ($extensions as $slug => $extension) {
            $store_item_id = $extension['store_item_id'];
            $name = $extension['name'];
            $extension_file = $extension['extension_file'];
            $version = $extension['version'];
            $license_key_name = \sprintf("license_%d", $store_item_id);
            $license_key = '';
            $network_options = get_site_option('wpo365_options');

            if (!empty($network_options[$license_key_name])) {
                $license_option = $network_options[$license_key_name];

                if (WordPress_Helpers::stripos($license_option, '|') > -1) {
                    $exploded = explode('|', $license_option);
                    $license_key = $exploded[0];
                } else {
                    $license_key = $license_option;
                }
            }

            $updater = new Plugin_Updater(
                $GLOBALS['WPO_CONFIG']['store'],
                $extension_file,
                $extension['slug'],
                $extension['name'],
                array(
                    'version'       => $version,                                                    // current version number
                    'license'       => $license_key,                                                // license key (used get_option above to retrieve from DB)
                    'item_id'       => $store_item_id,                                              // ID of the product
                    'author'        => 'support@wpo365.com',                                        // author of this plugin
                    'beta'          => false,
                )
            );

            $transient_data = $updater->check_update($transient_data);
        }

        return $transient_data;
    }

    public static function check_licenses()
    {
        Wpmu_Helpers::mu_delete_transient('wpo365_lic_notices');

        $extensions = Extensions_Helpers::get_extensions();

        foreach ($extensions as $slug => $extension) {
            $store_item_id = $extension['store_item_id'];
            $license_key_name = \sprintf("license_%d", $store_item_id);
            $license_key = '';
            $url = '';
            $network_options = get_site_option('wpo365_options');

            if (!empty($network_options[$license_key_name])) {
                $license_option = $network_options[$license_key_name];

                if (WordPress_Helpers::stripos($license_option, '|') > -1) {
                    $exploded = explode('|', $license_option);
                    $license_key = $exploded[0];
                    $url = $exploded[1];
                } else {
                    $license_key = $license_option;
                }
            }

            if (true === $extension['activated']) {
                self::check_license($extension, $license_key, $url);
            }
        }
    }

    public static function check_license($extension, $license_key, $url = '')
    {
        Log_Service::write_log('DEBUG', sprintf(
            '##### -> %s [%s]',
            __METHOD__,
            $extension['store_item']
        ));

        $lic_notices = Wpmu_Helpers::mu_get_transient('wpo365_lic_notices');

        if (empty($lic_notices)) {
            $lic_notices = array();
        }

        $lic_url = is_multisite()
            ? network_admin_url('admin.php?page=wpo365-manage-licenses')
            : admin_url('admin.php?page=wpo365-manage-licenses');

        $empty_url_arg = empty($url);

        if ($empty_url_arg) {
            $url = is_multisite() ? network_home_url() : home_url();
        } else {
            $_url = is_multisite() ? network_home_url() : home_url();

            // Check if the URL that was previously valid has changed
            if (strcasecmp(trailingslashit($url), trailingslashit($_url)) !== 0) {
                $host = parse_url($_url, PHP_URL_HOST);

                // Ignore the case where the hostname is an IP address
                if (false !== filter_var($host, FILTER_VALIDATE_IP)) {
                    return;
                }
            }
        }

        $skip_license_check = false;

        if (false !== WordPress_Helpers::stripos($url, 'localhost')) {
            $skip_license_check = true;
        } else {
            $url_host = parse_url($url, PHP_URL_HOST);
            $url_path = parse_url($url, PHP_URL_PATH);

            $url_host_segments = explode('.', $url_host);

            for ($i = 0; $i < count($url_host_segments) && $i < 2; $i++) {
                array_pop($url_host_segments);
            }

            $url_subdomain = implode('.', $url_host_segments);
            $skip_list = array('dev', 'test', 'staging');

            foreach ($skip_list as $skip) {

                if (false !== WordPress_Helpers::stripos($url_path, $skip)) {
                    $skip_license_check = true;
                    break;
                }

                if (false !== WordPress_Helpers::stripos($url_subdomain, $skip)) {
                    $skip_license_check = true;
                    break;
                }
            }
        }

        if ($skip_license_check) {
            Log_Service::write_log('DEBUG', sprintf(
                '%s -> Skipping license check for %s using URL %s',
                __METHOD__,
                $extension['store_item'],
                $url
            ));
            return;
        }

        Log_Service::write_log('DEBUG', sprintf(
            '%s -> Checking license for %s using %s',
            __METHOD__,
            $extension['store_item'],
            $url
        ));

        // Generate warning if license was not found
        if (empty($license_key)) {
            $lic_notices[] = \sprintf(
                'Could not find a license for <strong>%s</strong>. Please go to <a href="%s">WP Admin > WPO365 > Licenses</a> and activate your license or purchase a <a href="%s" target="_blank">new license online</a>. See the <a href="%s" target="_blank">End User License Agreement</a> for details',
                $extension['store_item'],
                $lic_url,
                $extension['store_url'],
                'https://www.wpo365.com/end-user-license-agreement/'
            );
            Wpmu_Helpers::mu_set_transient('wpo365_lic_notices', $lic_notices);
            return;
        }

        // Call the custom API.
        $response = wp_remote_get(\sprintf("https://www.wpo365.com/?edd_action=check_license&license=%s&item_id=%s&url=%s", $license_key, $extension['store_item_id'], $url), array('timeout' => 15, 'sslverify' => false));

        // make sure the response came back okay
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = __('An error occurred, please try again.');
            }

            Log_Service::write_log('ERROR', __METHOD__ . ' -> ' . $message);
        }

        $license_data = json_decode(wp_remote_retrieve_body($response));
        $message = '';

        switch ($license_data->license) {

            case 'valid':

                Log_Service::write_log('DEBUG', sprintf(
                    '%s -> License key for %s is valid',
                    __METHOD__,
                    $extension['store_item']
                ));

                /**
                 * @since   23.0    Lets cache the URL for which the license check was successful
                 */

                if ($empty_url_arg) {
                    $license_key_name = \sprintf("license_%d", $extension['store_item_id']);
                    $network_options = get_site_option('wpo365_options');

                    if (!empty($network_options[$license_key_name])) {
                        $network_options[$license_key_name] = sprintf('%s|%s', $license_key, $url);
                        update_site_option('wpo365_options', $network_options);
                        $GLOBALS['WPO_CONFIG']['options'] = array();
                    }
                }

                return;

            case 'expired':
                $message = sprintf(
                    __('Your license key for <strong>%s</strong> expired on %s. Please go to <a href="%s" target="_blank">WP Admin > WPO365 > Licenses</a> and update your license or purchase a <a href="%s" target="_blank">new license online</a>. See the <a href="%s" target="_blank">End User License Agreement</a> for details'),
                    $extension['store_item'],
                    date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp'))),
                    $lic_url,
                    $extension['store_url'],
                    'https://www.wpo365.com/end-user-license-agreement/'
                );
                break;

            case 'disabled':
                $message =
                    sprintf(
                        __('Your license key for <strong>%s</strong> has been disabled. Please go to <a href="%s" target="_blank">WP Admin > WPO365 > Licenses</a> and update your license or purchase a <a href="%s" target="_blank">new license online</a>. See the <a href="%s" target="_blank">End User License Agreement</a> for details'),
                        $extension['store_item'],
                        $lic_url,
                        $extension['store_url'],
                        'https://www.wpo365.com/end-user-license-agreement/'
                    );
                break;

            case 'key_mismatch':
            case 'item_name_mismatch':
                $message =
                    sprintf(
                        __('Your license key for <strong>%s</strong> is not valid for this product. Please go to <a href="%s" target="_blank">WP Admin > WPO365 > Licenses</a> and update your license key or purchase additional <a href="%s" target="_blank">licenses online</a>. See the <a href="%s" target="_blank">End User License Agreement</a> for details'),
                        $extension['store_item'],
                        $lic_url,
                        $extension['store_url'],
                        'https://www.wpo365.com/end-user-license-agreement/'
                    );
                break;

            case 'site_inactive':
                $message =
                    sprintf(
                        __('Your license key for <strong>%s</strong> is not active for this site. Please go to <a href="%s" target="_blank">WP Admin > WPO365 > Licenses</a> and activate your license or purchase additional <a href="%s" target="_blank">licenses online</a>. See the <a href="%s" target="_blank">End User License Agreement</a> for details'),
                        $extension['store_item'],
                        $lic_url,
                        $extension['store_url'],
                        'https://www.wpo365.com/end-user-license-agreement/'
                    );
                break;

            case 'invalid_item_id':
                $message =
                    sprintf(
                        __('The item ID <strong>%s</strong> for <strong>%s</strong> is not valid. Please go to <a href="%s" target="_blank">WP Admin > WPO365 > Licenses</a> and update your license key or purchase additional <a href="%s" target="_blank">licenses online</a>. See the <a href="%s" target="_blank">End User License Agreement</a> for details'),
                        $extension['store_item_id'],
                        $extension['store_item'],
                        $lic_url,
                        $extension['store_url'],
                        'https://www.wpo365.com/end-user-license-agreement/'
                    );
                break;

            default:
                $message =
                    sprintf(
                        __('An unknown error occurred whilst checking your license key for %s. Please check WP Admin > WPO365 > ... > Debug to view the raw request (and optionally send it to support@wpo365.com).'),
                        $extension['store_item']
                    );
                Log_Service::write_log('WARN', sprintf(
                    '%s -> License key %s for %s is not valid for site with URL %s [raw request: %s]',
                    __METHOD__,
                    $license_key,
                    $extension['store_item_id'],
                    $url,
                    htmlentities(serialize($response))
                ));
                break;
        }

        if (!empty($message)) {
            $lic_notices[] = $message;
            Wpmu_Helpers::mu_set_transient('wpo365_lic_notices', $lic_notices);

            Log_Service::write_log('ERROR', sprintf(
                '%s -> License key %s for %s is not valid for site with URL %s [error: %s]',
                __METHOD__,
                $license_key,
                $extension['store_item_id'],
                $url,
                $message
            ));
        }
    }

    /**
     * Check for Updates at the defined API endpoint and modify the update array.
     *
     * This function dives into the update API just when WordPress creates its update array,
     * then adds a custom API call and injects the custom plugin data retrieved from the API.
     * It is reassembled from parts of the native WordPress plugin update code.
     * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
     *
     * @uses api_request()
     *
     * @param array   $_transient_data Update array build by WordPress.
     * @return array Modified update array with custom plugin data.
     */
    public function check_update($_transient_data)
    {
        Log_Service::write_log('DEBUG', sprintf(
            '%s -> Checking version update for %s',
            __METHOD__,
            $this->name
        ));

        global $pagenow;

        if (!is_object($_transient_data)) {
            $_transient_data = new \stdClass();
        }

        if (!empty($_transient_data->response) && !empty($_transient_data->response[$this->name]) && false === $this->wp_override) {
            return $_transient_data;
        }

        $current = $this->get_repo_api_data();
        if (false !== $current && is_object($current) && isset($current->new_version)) {
            if (version_compare($this->version, $current->new_version, '<')) {
                $_transient_data->response[$this->name] = $current;
            } else {
                // Populating the no_update information is required to support auto-updates in WordPress 5.5.
                $_transient_data->no_update[$this->name] = $current;
            }
        }
        $_transient_data->last_checked           = time();
        $_transient_data->checked[$this->name] = $this->version;

        return $_transient_data;
    }

    /**
     * Get repo API data from store.
     * Save to cache.
     *
     * @return \stdClass
     */
    public function get_repo_api_data()
    {
        $version_info = $this->get_cached_version_info();

        if (false === $version_info) {
            $version_info = $this->api_request(
                'plugin_latest_version',
                array(
                    'slug' => $this->slug,
                    'beta' => $this->beta,
                )
            );
            if (!$version_info) {
                return false;
            }

            // This is required for your plugin to support auto-updates in WordPress 5.5.
            $version_info->plugin = $this->name;
            $version_info->id     = $this->name;
            $version_info->tested = $this->get_tested_version($version_info);

            $this->set_version_info_cache($version_info);
        }

        return $version_info;
    }

    /**
     * Gets the plugin's tested version.
     *
     * @since 1.9.2
     * @param object $version_info
     * @return null|string
     */
    private function get_tested_version($version_info)
    {
        // There is no tested version.
        if (empty($version_info->tested)) {
            return null;
        }

        // Strip off extra version data so the result is x.y or x.y.z.
        list($current_wp_version) = explode('-', get_bloginfo('version'));

        // The tested version is greater than or equal to the current WP version, no need to do anything.
        if (version_compare($version_info->tested, $current_wp_version, '>=')) {
            return $version_info->tested;
        }
        $current_version_parts = explode('.', $current_wp_version);
        $tested_parts          = explode('.', $version_info->tested);

        // The current WordPress version is x.y.z, so update the tested version to match it.
        if (isset($current_version_parts[2]) && $current_version_parts[0] === $tested_parts[0] && $current_version_parts[1] === $tested_parts[1]) {
            $tested_parts[2] = $current_version_parts[2];
        }

        return implode('.', $tested_parts);
    }

    /**
     * Show the update notification on multisite subsites.
     *
     * @param string  $file
     * @param array   $plugin
     */
    public function show_update_notification($file, $plugin)
    {
        // Return early if in the network admin, or if this is not a multisite install.
        if (is_network_admin() || !is_multisite()) {
            return;
        }

        // Allow single site admins to see that an update is available.
        if (!current_user_can('activate_plugins')) {
            return;
        }

        if ($this->name !== $file) {
            return;
        }

        // Do not print any message if update does not exist.
        $update_cache = get_site_transient('update_plugins');

        if (!isset($update_cache->response[$this->name])) {
            if (!is_object($update_cache)) {
                $update_cache = new \stdClass();
            }
            $update_cache->response[$this->name] = $this->get_repo_api_data();
        }

        // Return early if this plugin isn't in the transient->response or if the site is running the current or newer version of the plugin.
        if (empty($update_cache->response[$this->name]) || version_compare($this->version, $update_cache->response[$this->name]->new_version, '>=')) {
            return;
        }

        printf(
            '<tr class="plugin-update-tr %3$s" id="%1$s-update" data-slug="%1$s" data-plugin="%2$s">',
            $this->slug,
            $file,
            in_array($this->name, $this->get_active_plugins(), true) ? 'active' : 'inactive'
        );

        echo '<td colspan="3" class="plugin-update colspanchange">';
        echo '<div class="update-message notice inline notice-warning notice-alt"><p>';

        $changelog_link = '';
        if (!empty($update_cache->response[$this->name]->sections->changelog)) {
            $changelog_link = add_query_arg(
                array(
                    'edd_sl_action' => 'view_plugin_changelog',
                    'plugin'        => urlencode($this->name),
                    'slug'          => urlencode($this->slug),
                    'TB_iframe'     => 'true',
                    'width'         => 77,
                    'height'        => 911,
                ),
                self_admin_url('index.php')
            );
        }
        $update_link = add_query_arg(
            array(
                'action' => 'upgrade-plugin',
                'plugin' => urlencode($this->name),
            ),
            self_admin_url('update.php')
        );

        printf(
            /* translators: the plugin name. */
            esc_html__('There is a new version of %1$s available.', 'easy-digital-downloads'),
            esc_html($plugin['Name'])
        );

        if (!current_user_can('update_plugins')) {
            echo ' ';
            esc_html_e('Contact your network administrator to install the update.', 'easy-digital-downloads');
        } elseif (empty($update_cache->response[$this->name]->package) && !empty($changelog_link)) {
            echo ' ';
            printf(
                /* translators: 1. opening anchor tag, do not translate 2. the new plugin version 3. closing anchor tag, do not translate. */
                __('%1$sView version %2$s details%3$s.', 'easy-digital-downloads'),
                '<a target="_blank" class="thickbox open-plugin-details-modal" href="' . esc_url($changelog_link) . '">',
                esc_html($update_cache->response[$this->name]->new_version),
                '</a>'
            );
        } elseif (!empty($changelog_link)) {
            echo ' ';
            printf(
                __('%1$sView version %2$s details%3$s or %4$supdate now%5$s.', 'easy-digital-downloads'),
                '<a target="_blank" class="thickbox open-plugin-details-modal" href="' . esc_url($changelog_link) . '">',
                esc_html($update_cache->response[$this->name]->new_version),
                '</a>',
                '<a target="_blank" class="update-link" href="' . esc_url(wp_nonce_url($update_link, 'upgrade-plugin_' . $file)) . '">',
                '</a>'
            );
        } else {
            printf(
                ' %1$s%2$s%3$s',
                '<a target="_blank" class="update-link" href="' . esc_url(wp_nonce_url($update_link, 'upgrade-plugin_' . $file)) . '">',
                esc_html__('Update now.', 'easy-digital-downloads'),
                '</a>'
            );
        }

        do_action("in_plugin_update_message-{$file}", $plugin, $plugin);

        echo '</p></div></td></tr>';
    }

    /**
     * Gets the plugins active in a multisite network.
     *
     * @return array
     */
    private function get_active_plugins()
    {
        $active_plugins         = (array) get_option('active_plugins');
        $active_network_plugins = (array) get_site_option('active_sitewide_plugins');

        return array_merge($active_plugins, array_keys($active_network_plugins));
    }

    /**
     * Updates information on the "View version x.x details" page with custom data.
     *
     * @uses api_request()
     *
     * @param mixed   $_data
     * @param string  $_action
     * @param object  $_args
     * @return object $_data
     */
    public function plugins_api_filter($_data, $_action = '', $_args = null)
    {
        if ('plugin_information' !== $_action) {

            return $_data;
        }

        if (!isset($_args->slug) || ($_args->slug !== $this->slug)) {

            return $_data;
        }

        $to_send = array(
            'slug'   => $this->slug,
            'is_ssl' => is_ssl(),
            'fields' => array(
                'banners' => array(),
                'reviews' => false,
                'icons'   => array(),
            ),
        );

        // Get the transient where we store the api request for this plugin for 24 hours
        $edd_api_request_transient = $this->get_cached_version_info();

        //If we have no transient-saved value, run the API, set a fresh transient with the API value, and return that value too right now.
        if (empty($edd_api_request_transient)) {

            $api_response = $this->api_request('plugin_information', $to_send);

            // Expires in 3 hours
            $this->set_version_info_cache($api_response);

            if (false !== $api_response) {
                $_data = $api_response;
            }
        } else {
            $_data = $edd_api_request_transient;
        }

        // Convert sections into an associative array, since we're getting an object, but Core expects an array.
        if (isset($_data->sections) && !is_array($_data->sections)) {
            $_data->sections = $this->convert_object_to_array($_data->sections);
        }

        // Convert banners into an associative array, since we're getting an object, but Core expects an array.
        if (isset($_data->banners) && !is_array($_data->banners)) {
            $_data->banners = $this->convert_object_to_array($_data->banners);
        }

        // Convert icons into an associative array, since we're getting an object, but Core expects an array.
        if (isset($_data->icons) && !is_array($_data->icons)) {
            $_data->icons = $this->convert_object_to_array($_data->icons);
        }

        // Convert contributors into an associative array, since we're getting an object, but Core expects an array.
        if (isset($_data->contributors) && !is_array($_data->contributors)) {
            $_data->contributors = $this->convert_object_to_array($_data->contributors);
        }

        if (!isset($_data->plugin)) {
            $_data->plugin = $this->name;
        }

        return $_data;
    }

    /**
     * Convert some objects to arrays when injecting data into the update API
     *
     * Some data like sections, banners, and icons are expected to be an associative array, however due to the JSON
     * decoding, they are objects. This method allows us to pass in the object and return an associative array.
     *
     * @since 3.6.5
     *
     * @param stdClass $data
     *
     * @return array
     */
    private function convert_object_to_array($data)
    {
        if (!is_array($data) && !is_object($data)) {
            return array();
        }
        $new_data = array();
        foreach ($data as $key => $value) {
            $new_data[$key] = is_object($value) ? $this->convert_object_to_array($value) : $value;
        }

        return $new_data;
    }

    /**
     * Disable SSL verification in order to prevent download update failures
     *
     * @param array   $args
     * @param string  $url
     * @return object $array
     */
    public function http_request_args($args, $url)
    {

        if (strpos($url, 'https://') !== false && strpos($url, 'edd_action=package_download')) {
            $args['sslverify'] = $this->verify_ssl();
        }
        return $args;
    }

    /**
     * Calls the API and, if successfull, returns the object delivered by the API.
     *
     * @uses get_bloginfo()
     * @uses wp_remote_post()
     * @uses is_wp_error()
     *
     * @param string  $_action The requested action.
     * @param array   $_data   Parameters for the API action.
     * @return false|object|void
     */
    private function api_request($_action, $_data)
    {
        Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

        $data = array_merge($this->api_data, $_data);

        if ($data['slug'] !== $this->slug) {
            return;
        }

        // Don't allow a plugin to ping itself
        if (trailingslashit(home_url()) === $this->api_url) {
            return false;
        }

        if ($this->request_recently_failed()) {
            return false;
        }

        return $this->get_version_from_remote();
    }

    /**
     * Determines if a request has recently failed.
     *
     * @since 1.9.1
     *
     * @return bool
     */
    private function request_recently_failed()
    {
        $failed_request_details = get_option($this->failed_request_cache_key);

        // Request has never failed.
        if (empty($failed_request_details) || !is_numeric($failed_request_details)) {
            return false;
        }

        /*
		 * Request previously failed, but the timeout has expired.
		 * This means we're allowed to try again.
		 */
        if (time() > $failed_request_details) {
            delete_option($this->failed_request_cache_key);

            return false;
        }

        return true;
    }

    /**
     * Logs a failed HTTP request for this API URL.
     * We set a timestamp for 1 hour from now. This prevents future API requests from being
     * made to this domain for 1 hour. Once the timestamp is in the past, API requests
     * will be allowed again. This way if the site is down for some reason we don't bombard
     * it with failed API requests.
     *
     * @see EDD_SL_Plugin_Updater::request_recently_failed
     *
     * @since 1.9.1
     */
    private function log_failed_request()
    {
        update_option($this->failed_request_cache_key, strtotime('+1 hour'));
    }

    /**
     * If available, show the changelog for sites in a multisite install.
     */
    public function show_changelog()
    {
        if (empty($_REQUEST['edd_sl_action']) || 'view_plugin_changelog' !== $_REQUEST['edd_sl_action']) {
            return;
        }

        if (empty($_REQUEST['plugin'])) {
            return;
        }

        if (empty($_REQUEST['slug']) || $this->slug !== $_REQUEST['slug']) {
            return;
        }

        if (!current_user_can('update_plugins')) {
            wp_die(esc_html__('You do not have permission to install plugin updates', 'easy-digital-downloads'), esc_html__('Error', 'easy-digital-downloads'), array('response' => 403));
        }

        $version_info = $this->get_repo_api_data();
        if (isset($version_info->sections)) {
            $sections = $this->convert_object_to_array($version_info->sections);
            if (!empty($sections['changelog'])) {
                echo '<div style="background:#fff;padding:10px;">' . wp_kses_post($sections['changelog']) . '</div>';
            }
        }

        exit;
    }

    /**
     * Gets the current version information from the remote site.
     *
     * @return array|false
     */
    private function get_version_from_remote()
    {
        $api_params = array(
            'edd_action'  => 'get_version',
            'license'     => !empty($this->api_data['license']) ? $this->api_data['license'] : '',
            'item_name'   => isset($this->api_data['item_name']) ? $this->api_data['item_name'] : false,
            'item_id'     => isset($this->api_data['item_id']) ? $this->api_data['item_id'] : false,
            'version'     => isset($this->api_data['version']) ? $this->api_data['version'] : false,
            'slug'        => $this->slug,
            'author'      => $this->api_data['author'],
            'url'         => home_url(),
            'beta'        => $this->beta,
            'php_version' => phpversion(),
            'wp_version'  => get_bloginfo('version'),
        );

        /**
         * Filters the parameters sent in the API request.
         *
         * @param array  $api_params        The array of data sent in the request.
         * @param array  $this->api_data    The array of data set up in the class constructor.
         * @param string $this->plugin_file The full path and filename of the file.
         */
        $api_params = apply_filters('edd_sl_plugin_updater_api_params', $api_params, $this->api_data, $this->plugin_file);

        $request = wp_remote_post(
            $this->api_url,
            array(
                'timeout'   => 15,
                'sslverify' => $this->verify_ssl(),
                'body'      => $api_params,
                'headers'   => array('Expect' => ''),
            )
        );

        if (is_wp_error($request) || (200 !== wp_remote_retrieve_response_code($request))) {
            $this->log_failed_request();

            return false;
        }

        $request = json_decode(wp_remote_retrieve_body($request));

        if ($request && isset($request->sections)) {
            $request->sections = maybe_unserialize($request->sections);
        } else {
            $request = false;
        }

        if ($request && isset($request->banners)) {
            $request->banners = maybe_unserialize($request->banners);
        }

        if ($request && isset($request->icons)) {
            $request->icons = maybe_unserialize($request->icons);
        }

        if (!empty($request->sections)) {
            foreach ($request->sections as $key => $section) {
                $request->$key = (array) $section;
            }
        }

        return $request;
    }

    /**
     * Get the version info from the cache, if it exists.
     *
     * @param string $cache_key
     * @return object
     */
    public function get_cached_version_info($cache_key = '')
    {
        if (empty($cache_key)) {
            $cache_key = $this->get_cache_key();
        }

        $cache = get_option($cache_key);

        // Cache is expired
        if (empty($cache['timeout']) || time() > $cache['timeout']) {
            return false;
        }

        // We need to turn the icons into an array, thanks to WP Core forcing these into an object at some point.
        $cache['value'] = json_decode($cache['value']);
        if (!empty($cache['value']->icons)) {
            $cache['value']->icons = (array) $cache['value']->icons;
        }

        return $cache['value'];
    }

    /**
     * Adds the plugin version information to the database.
     *
     * @param string $value
     * @param string $cache_key
     */
    public function set_version_info_cache($value = '', $cache_key = '')
    {
        if (empty($cache_key)) {
            $cache_key = $this->get_cache_key();
        }

        $data = array(
            'timeout' => strtotime('+3 hours', time()),
            'value'   => wp_json_encode($value),
        );

        update_option($cache_key, $data, 'no');

        // Delete the duplicate option
        delete_option('edd_api_request_' . md5(serialize($this->slug . $this->api_data['license'] . $this->beta)));
    }

    /**
     * Returns if the SSL of the store should be verified.
     *
     * @since  1.6.13
     * @return bool
     */
    private function verify_ssl()
    {
        return (bool) apply_filters('edd_sl_api_request_verify_ssl', true, $this);
    }

    /**
     * Gets the unique key (option name) for a plugin.
     *
     * @since 1.9.0
     * @return string
     */
    private function get_cache_key()
    {
        $string = $this->slug . $this->api_data['license'] . $this->beta;

        return 'edd_sl_' . md5(serialize($string));
    }

    public static function show_license_notices()
    {
        // Get all license related admin notices
        $lic_notices = Wpmu_Helpers::mu_get_transient('wpo365_lic_notices');

        if (\is_array($lic_notices)) {

            foreach ($lic_notices as $lic_notice) {
                add_action('admin_notices', function () use ($lic_notice) {
                    printf('<div class="notice notice-error" style="margin-left: 2px;"><p>%s</p></div>', __($lic_notice));
                }, 10, 0);
                add_action('network_admin_notices', function () use ($lic_notice) {
                    printf('<div class="notice notice-error" style="margin-left: 2px;"><p>%s</p></div>', __($lic_notice));
                }, 10, 0);
            }
        }
    }
}
