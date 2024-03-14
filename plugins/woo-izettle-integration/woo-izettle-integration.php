<?php
/**
 * The main plugin file for WooCommerce Zettle Integration.
 *
 * This file is included during the WordPress bootstrap process if the plugin is active.
 *
 * @package   WooCommerce_Zettle_Integration
 * @author    BjornTech AB
 * @license   GPL-3.0
 * @link      https://bjorntech.com
 * @copyright 2017-2020 BjornTech AB
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Zettle Integration
 * Plugin URI:        https://www.bjorntech.com/woocommerce-zettle/?utm_source=wp-izettle&utm_medium=plugin&utm_campaign=product
 * Description:       Synchronizes products, purchases and stock levels.
 * Version:           7.9.0
 * Author:            BjornTech AB
 * Author URI:        https://www.bjorntech.com/?utm_source=wp-izettle&utm_medium=plugin&utm_campaign=product
 * Text Domain:       woo-izettle-integration
 *
 * WC requires at least: 4.0
 * WC tested up to: 8.6
 *
 * Copyright:         2017-2020 BjornTech AB
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('ABSPATH') || exit;

define('WC_ZETTLE_MIN_WC_VER', '4.0');

/**
 * WooCommerce fallback notice.
 *
 * @since 4.0.0
 * @return string
 */

if (!function_exists('woocommerce_izettle_integration_missing_wc_notice')) {

    function woocommerce_izettle_integration_missing_wc_notice()
    {
        /* translators: 1. URL link. */
        echo '<div class="error"><p><strong>' . sprintf(esc_html__('WooCommerce Zettle integration requires WooCommerce to be installed and active. You can download %s here.', 'woo-izettle-integration'), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>') . '</strong></p></div>';
    }

}

/**
 * WooCommerce not supported fallback notice.
 *
 * @since 7.0.0
 * @return string
 */

if (!function_exists('woocommerce_zettle_wc_not_supported')) {

    function woocommerce_zettle_wc_not_supported()
    {
        /* translators: $1. Minimum WooCommerce version. $2. Current WooCommerce version. */
        echo '<div class="error"><p><strong>' . sprintf(esc_html__('WooCommerce Zettle integration requires WooCommerce %1$s or greater to be installed and active. WooCommerce %2$s is no longer supported.', 'woo-izettle-integration'), WC_ZETTLE_MIN_WC_VER, WC_VERSION) . '</strong></p></div>';
    }

}

if (!function_exists('woocommerce_zettle_pos_installed')) {

    function woocommerce_zettle_pos_installed()
    {
        /* translators: $1. Minimum WooCommerce version. $2. Current WooCommerce version. */
        echo '<div class="error"><p><strong>' . sprintf(esc_html__('Uninstalling the PayPal Zettle POS plugin is required to use the WooCommerce Zettle integration.', 'woo-izettle-integration')) . '</strong></p></div>';
    }

}


class WC_iZettle_Integration
{

    /**
     * Plugin data
     */
    const NAME = 'WooCommerce Zettle Integration';
    const VERSION = '7.9.0';
    const SCRIPT_HANDLE = 'wc-izettle-integration';
    const PLUGIN_FILE = __FILE__;
    const CLIENT_ID = '6605a8aa-bdc2-4e38-b03b-4abb238aebc9';
    const UUID_NODE_ID = '556899';

    public $plugin_basename;
    public $includes_dir;
    public $external_dir;
    public $assets_url;
    public $access_token;
    public $version = self::VERSION;
    public $logger;
    public $settings_handler;
    public $purchase_handler;
    public $izettle;
    public $client_id;
    public $product_identifier;
    /**
     * @var bool
     */
    private static $shutDownCalled = false;

    /**
     *    $instance
     *
     * @var    mixed
     * @access public
     * @static
     */
    public static $instance = null;

    public function __construct()
    {

        $this->plugin_basename = plugin_basename(self::PLUGIN_FILE);
        $this->includes_dir = plugin_dir_path(self::PLUGIN_FILE) . 'includes/';
        $this->external_dir = plugin_dir_path(self::PLUGIN_FILE) . 'external/';
        $this->assets_url = trailingslashit(plugins_url('assets', self::PLUGIN_FILE));
        $this->vendor_dir = plugin_dir_path(self::PLUGIN_FILE) . 'vendor/';

        $this->includes();

        add_action('plugins_loaded', array($this, 'maybe_load_plugin'));

        add_action('before_woocommerce_init', array($this, 'declare_hpos_compatible'));

        $this->client_id = ($client_id = get_option('izettle_alternate_client_id', '')) != '' ? $client_id : self::CLIENT_ID;

        if (get_site_transient('izettle_activated_or_upgraded')) {
            delete_site_transient('izettle_activated_or_upgraded');
            delete_site_transient('izettle_last_purchase_sync');
            delete_site_transient('izettle_last_product_sync');
            delete_site_transient('zettle_tax_rates');
            delete_site_transient('zettle_tax_settings');
            try {
                IZ_Notice::clear();
                delete_site_transient('izettle_did_show_unauthorized_info');
                delete_site_transient('izettle_did_show_expire_warning');
                delete_site_transient('izettle_did_show_trial_info');
                delete_site_transient('izettle_did_show_avaliable_info');
                do_action('izettle_force_connection');
            } catch (IZ_Integration_API_Exception $e) {
                $e->write_to_logs();
            }
        }

    }

    public function include_settings($settings)
    {
        $settings[] = include $this->includes_dir . 'admin/settings/wc-settings-page-izettle.php';
        return $settings;
    }

    private function includes()
    {
        require_once $this->includes_dir . 'izettle-integration-constants.php';
        require_once $this->includes_dir . 'api/izettle-integration-api.php';
        require_once $this->includes_dir . 'api/izettle-integration-api-transaction.php';
        require_once $this->includes_dir . 'api/izettle-integration-api-transaction_v2.php';
        require_once $this->includes_dir . 'api/izettle-service-object.php';
        require_once $this->includes_dir . 'admin/izettle-integration-exceptions.php';
        require_once $this->includes_dir . 'admin/izettle-integration-notices.php';
        require_once $this->includes_dir . 'admin/izettle-integration-helper.php';
        require_once $this->includes_dir . 'libraries/izettle-class.uuid.php';
        require_once $this->includes_dir . 'libraries/izettle-class.uuidv2.php';
        require_once $this->includes_dir . 'izettle-purchase-transaction-post.php';
    }

    public function maybe_load_plugin()
    {

        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', 'woocommerce_izettle_integration_missing_wc_notice');
            return;
        }

        if (!WC_Zettle_Helper::wc_version_check(WC_ZETTLE_MIN_WC_VER)) {
            add_action('admin_notices', 'woocommerce_zettle_wc_not_supported');
            return;
        }

        if (WC_Zettle_Helper::is_plugin_installed('/zettle-pos-integration/zettle-pos-integration.php')){
            add_action('admin_notices', 'woocommerce_zettle_pos_installed');
        }

        require_once $this->includes_dir . 'admin/izettle-integration-log.php';
        $this->logger = new WC_iZettle_Integration_Log(get_option('izettle_logging') != 'yes');

        add_filter('action_scheduler_queue_runner_batch_size', array($this, 'action_scheduler_batch_size'));
        add_filter('action_scheduler_queue_runner_time_limit', array($this, 'izettle_action_scheduler_time_limit'));
        add_action('rest_api_init', array($this, 'activate_webhook_function'));

        require_once $this->includes_dir . 'izettle-integration-authorization.php';
        require_once $this->includes_dir . 'izettle-integration-purchase-handler.php';
        require_once $this->includes_dir . 'izettle-stock-level-handler.php';
        require_once $this->includes_dir . 'izettle-integration-barcode.php';
        require_once $this->includes_dir . 'izettle-integration-image.php';
        require_once $this->includes_dir . 'izettle-integration-iz-products.php';
        require_once $this->includes_dir . 'izettle-integration-product-handler.php';

        if (is_admin()) {
            require_once $this->includes_dir . 'izettle_product_quick_bulk_edit.php';
            require_once $this->includes_dir . 'izettle-integration-product-handler-admin.php';
            require_once $this->includes_dir . 'izettle-integration-product-handler-metabox.php';

            add_action('wp_ajax_wciz_processing_button', array($this, 'ajax_wciz_processing_button'));
            add_action('wp_ajax_izettle_sync_purchases', array($this, 'ajax_sync_purchases_function'));
            add_action('wp_ajax_izettle_force_new_token', array($this, 'ajax_force_new_token_function'));
            add_action('wp_ajax_izettle_clear_product_meta_data', array($this, 'ajax_clear_product_meta_data_function'));
            add_action('wp_ajax_izettle_sync_iz_products', array($this, 'ajax_sync_iz_products'));
            add_action('wp_ajax_izettle_clear_notice', array($this, 'ajax_clear_notice'));
            add_action('wp_ajax_izettle_get_state', array($this, 'ajax_get_state'));

            add_action('admin_notices', array($this, 'generate_messages'), 50);
            add_action('admin_enqueue_scripts', array($this, 'admin_add_styles_and_scripts'));
            add_action('admin_menu', array($this, 'remove_submenus'));
            add_filter('wp_image_editors', array($this, 'image_editor_default_to_gd'));
            add_filter('woocommerce_get_settings_pages', array($this, 'include_settings'));
        }

        add_action('shutdown', array($this, 'shutdown'));

    }

    public function declare_hpos_compatible()
    {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    }

    public function image_editor_default_to_gd($editors)
    {
        $gd_editor = 'WP_Image_Editor_GD';
        $editors = array_diff($editors, array($gd_editor));
        $result = array_unshift($editors, $gd_editor);
        return $editors;
    }

    public function action_scheduler_batch_size($batch_size)
    {
        if ($batch_size_option = intval(get_option('izettle_action_scheduler_batch_size', 10))) {
            $batch_size = $batch_size_option;
        }
        return $batch_size;
    }

    public function izettle_action_scheduler_time_limit($time_limit)
    {
        if ($time_limit_option = intval(get_option('izettle_action_scheduler_time_limit', 30))) {
            $time_limit = $time_limit_option;
        }
        return $time_limit;
    }

    public function shutdown()
    {
        if ('yes' == get_option('izettle_manual_cron')) {
            do_action('action_scheduler_run_queue');
        }
    }

    public function generate_messages()
    {

        if (version_compare(($installed_version = get_option('izettle_installed_version', '5.0.4')), $this->version, "<")) {

            require_once $this->includes_dir . 'upgrades/upgrade-to-4-2-0.php';
            require_once $this->includes_dir . 'upgrades/upgrade-to-4-8-0.php';
            require_once $this->includes_dir . 'upgrades/upgrade-to-5-1-1.php';
            require_once $this->includes_dir . 'upgrades/upgrade-to-5-1-2.php';
            require_once $this->includes_dir . 'upgrades/upgrade-to-5-1-3.php';
            require_once $this->includes_dir . 'upgrades/upgrade-to-6-5-0.php';
            require_once $this->includes_dir . 'upgrades/upgrade-to-7-0-0.php';

            update_option('izettle_installed_version', $this->version);

            WC_IZ()->logger->add(sprintf('Plugin upgraded from %s to %s', $installed_version, $this->version), true);

        }

        require_once $this->includes_dir . 'upgrades/upgrade-to-7-3-0.php';

        $connection_status = apply_filters('izettle_connection_status', '');

        if ($connection_status == 'unauthorized' && !get_site_transient('izettle_did_show_unauthorized_info')) {
            $message = sprintf(__('To start using WooCommerce Zettle Integration, <a href="%s">go to the plugin connection page.</a>', 'woo-izettle-integration'), get_admin_url(null, 'admin.php?page=wc-settings&tab=izettle'));
            $id = IZ_Notice::add($message, 'info');
            set_site_transient('izettle_did_show_unauthorized_info', $id);
        } elseif ($connection_status == 'expired' && !get_site_transient('izettle_did_show_expire_warning')) {
            $message = sprintf(__('Your Zettle subscription has expired! Extend your subscription to enable full syncing capabilities again in our <a href="%s">webshop.</a>', 'woo-izettle-integration'), 'https://www.bjorntech.com/product/woocommerce-izettle-integration-automatic-sync/?token=' . izettle_api()->get_organization_uuid() . '&utm_source=wp-izettle&utm_medium=plugin&utm_campaign=product');
            $id = IZ_Notice::add($message, 'warning');
            set_site_transient('izettle_did_show_expire_warning', $id);
        } elseif ($connection_status == 'trial' && !get_site_transient('izettle_did_show_trial_info')) {
            $message = sprintf(__('Congratulations your have been connected to the Zettle service and can use all functionality during the trial period. Do not forget to upgrade in our <a href="%s">webshop.</a> before the trial ends', 'woo-izettle-integration'), 'https://www.bjorntech.com/product/woocommerce-izettle-integration-automatic-sync/?token=' . izettle_api()->get_organization_uuid() . '&utm_source=wp-izettle&utm_medium=plugin&utm_campaign=product');
            $id = IZ_Notice::add($message, 'info');
            set_site_transient('izettle_did_show_trial_info', $id);
        } elseif ($connection_status == 'error') {
            $message = sprintf(__('WooCommerce Zettle Integration cannot connect to service. Try to <a href="%s">refresh the connection.</a> If you still experience problems contact hello@bjorntech.com', 'woo-izettle-integration'), get_admin_url(null, 'admin.php?page=wc-settings&tab=izettle&section=advanced'));
            IZ_Notice::add($message, 'error', 'connection_error', false);
        }

        if ('ERROR' === izettle_api()->get_webhook_status()) {
            $message = sprintf(__('The Zettle service was not able to reach your site (needed for updates from Zettle). Remove any "coming soon" plugins, enable https and try to <a href="%s">refresh the connection.</a> If you still experience problems contact us at hello@bjorntech.com', 'woo-izettle-integration'), get_admin_url(null, 'admin.php?page=wc-settings&tab=izettle&section=advanced'));
            IZ_Notice::add($message, 'error', 'webhook_error', false);
        } else {
            IZ_Notice::clear('webhook_error');
        }

    }

    public function remove_new_content_items($wp_admin_bar)
    {
        $wp_admin_bar->remove_node('new-content', 'new-izettle_purchase');
    }

    public function remove_submenus()
    {
        global $submenu;
        unset($submenu['edit.php?post_type=izettle_purchase'][10]);
    }

    public function hide_top_bar_item()
    {
        if ('izettle_purchase' == get_post_type()) {
            echo '<style type="text/css">
          .page-title-action{display:none;}
          .subsubsub{display:none;}
          </style>';
        }

    }

    public function admin_add_styles_and_scripts($pagehook)
    {
        // do nothing if we are not on the target pages
        if ('edit.php' == $pagehook) {
            wp_enqueue_script('izettle_quick_bulk_edit', plugin_dir_url(__FILE__) . 'includes/javascript/izettle_quick_bulk_edit.js', array('jquery'), $this->version);
        }

        $post_type = get_post_type();

        if ('edit.php' == $pagehook && 'izettle_purchase' == $post_type) {
            wp_register_style('jquery-tiptip', plugin_dir_url(__FILE__) . 'includes/stylesheets/tipTip.css', array(), $this->version);
            wp_enqueue_style('jquery-tiptip');

            wp_register_script('jquery-tiptip', plugin_dir_url(__FILE__) . 'includes/javascript/jquery.tipTip.minified.js', array('jquery'), $this->version, true);
            wp_enqueue_script('jquery-tiptip');
        }

        wp_register_style('izettle-integration', plugin_dir_url(__FILE__) . 'includes/stylesheets/izettle.css', array(), $this->version);
        wp_enqueue_style('izettle-integration');

        wp_enqueue_script('izettle-integration', plugin_dir_url(__FILE__) . 'includes/javascript/izettle-integration.js', array('jquery', 'jquery-tiptip'), $this->version, true);

        wp_localize_script('izettle-integration', 'izettledata', array(
            'nonce' => wp_create_nonce('ajax-izettle'),
            'redirect_warning' => __('I agree to the BjornTech Privacy Policy', 'woo-izettle-integration'),
            'email_warning' => __('Enter user-email and save before connecting to the service', 'woo-izettle-integration'),
            'clear_meta_data_warning' => __('You are about to clear the Zettle data on all WooCommerce products. Are you sure?', 'woo-izettle-integration'),
        ));

    }

    public function ajax_clear_notice()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-izettle')) {
            wp_die();
        }

        if (isset($_POST['parents'])) {
            $id = substr($_POST['parents'], strpos($_POST['parents'], 'id-'));
            IZ_Notice::clear($id);
        }
        $response = array(
            'status' => 'success',
        );

        wp_send_json($response);
        exit;
    }

    /**
     * Check if the e-mail has been filled in and saved before requesting authorization (call from js)
     */
    public function ajax_get_state()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-izettle')) {
            wp_die();
        }

        $state = array();
        $user_email = get_option('izettle_username');

        $adm_url = izettle_api()->get_adm_url();
        if ($user_email == $_POST['email']) {
            $site_params = array(
                'email' => $user_email,
                'website' => ($alternate_url = get_option('bjorntech_alternate_webhook_url')) ? $alternate_url : get_site_url(),
                'client_id' => $this->client_id,
                'version' => $this->version,
            );
            $encoded_params = base64_encode(json_encode($site_params, JSON_INVALID_UTF8_IGNORE));
            $client_id = $this->client_id;
            $state = array(
                'state' => "https://oauth.izettle.com/authorize?response_type=code&client_id=$client_id&scope=READ:PRODUCT%20WRITE:PRODUCT%20READ:PURCHASE%20READ:FINANCE&state=$encoded_params&redirect_uri=https://zettle.$adm_url",
            );
        } else {
            $state = array(
                'state' => 'mismatch',
            );
        }
        wp_send_json($state);
        wp_die();
    }

    public function ajax_wciz_processing_button()
    {

        if (!wp_verify_nonce($_POST['nonce'], 'ajax-izettle')) {
            wp_die();
        }

        $id = $_POST['id'];

        $processing_queue = WC_Zettle_Helper::get_processing_queue($id);
        $queue_lenght = count($processing_queue);

        $display_name = WC_Zettle_Helper::display_name($id);

        if ('start' == $_POST['task'] && 0 == $queue_lenght) {

            if (apply_filters('izettle_is_client_allowed_to_sync', false, true)) {
                if (0 == ($number_synced = apply_filters($id . '_filter', 0, true))) {
                    $response = array(
                        'status' => 'success',
                        'ready' => true,
                        'message' => sprintf(__('No %s to syncronize found.', 'woo-izettle-integration'), $display_name),
                    );
                } else {
                    $response = array(
                        'status' => 'success',
                        'button_text' => __('Cancel', 'woo-izettle-integration'),
                        'message' => sprintf(__('Added %s %s to the syncronisation queue.', 'woo-izettle-integration'), $number_synced, $display_name),
                    );
                }
            } else {
                $response = array(
                    'status' => 'success',
                    'message' => sprintf(__('You do not have an active subscription. Please head to our <a href="%s">webshop</a> to purchase a subscription to continue using the plugin.', 'woo-izettle-integration'), 'https://www.bjorntech.com/product/woocommerce-izettle-integration-automatic-sync/?token=' . izettle_api()->get_organization_uuid() . '&utm_source=wp-izettle&utm_medium=plugin&utm_campaign=product'),
                );
            }

        } elseif ('start' == $_POST['task']) {
            try {
                as_unschedule_all_actions($id . '_process');
            } catch (Throwable $throwable) {
                WC_IZ()->logger->add(sprintf('ajax_wciz_processing_button - No process to unschedule'));
            }
            $response = array(
                'status' => 'success',
                'button_text' => __('Start', 'woo-izettle-integration'),
                'ready' => true,
                'message' => sprintf(__('Successfully removed %s %s from the syncronisation queue.', 'woo-izettle-integration'), $queue_lenght, $display_name),
            );

        } elseif (0 != $queue_lenght) {

            $response = array(
                'status' => 'success',
                'button_text' => __('Cancel', 'woo-izettle-integration'),
                'status_message' => sprintf(__('%s %s in syncronisation queue - click "Cancel" to clear queue.', 'woo-izettle-integration'), $queue_lenght, $display_name),
                'message' => sprintf(__('%ss have been added to the syncronisation queue.', 'woo-izettle-integration'), $display_name),
            );

        } else {

            $response = array(
                'status' => 'success',
                'ready' => true,
                'button_text' => __('Start', 'woo-izettle-integration'),
                'message' => sprintf(__('%s syncronisation finished', 'woo-izettle-integration'), $display_name),
            );

        }

        wp_send_json($response);

    }

    public function clear_meta_data()
    {
        $products = wc_get_products(array(
            'limit' => -1,
            'return' => 'ids',
        ));

        foreach ($products as $product_id) {
            as_schedule_single_action(as_get_datetime_object(), 'wciz_remove_product_data', array($product_id));
        }

        as_schedule_single_action(as_get_datetime_object(), 'wciz_remove_product_data_final');

        return array(
            'number' => count($products),
        );
    }

    public function ajax_clear_product_meta_data_function()
    {

        if (!wp_verify_nonce($_POST['nonce'], 'ajax-izettle')) {
            wp_die();
        }

        $process = $this->clear_meta_data();

        $response = array(
            'message' => sprintf('Deleting metadata for %s products', $process['number']),
        );

        IZ_Notice::clear();

        IZ_Notice::add('Clearing Zettle data on all WooCommerce products. This might take a few minutes...', 'success');

        wp_send_json($response);
    }

    public function ajax_force_new_token_function()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-izettle')) {
            wp_die();
        }

        IZ_Notice::clear();

        try {

            do_action('izettle_force_connection');
            delete_site_transient('izettle_last_purchase_sync');
            delete_site_transient('izettle_last_product_sync');
            delete_site_transient('zettle_tax_rates');
            delete_site_transient('zettle_tax_settings');
            delete_site_transient('izettle_categories');
            IZ_Notice::add('New token requested from service', 'success');

        } catch (IZ_Integration_API_Exception $e) {

            $e->write_to_logs();
            IZ_Notice::add('Error when connecting to service, check logs', 'success');

        }

        wp_send_json_success();

    }

    public static function add_action_links($links)
    {
        $links = array_merge(array(
            '<a href="' . admin_url('admin.php?page=wc-settings&tab=izettle') . '">' . __('Settings', 'woo-izettle-integration') . '</a>',
        ), $links);

        return $links;
    }

    public function activate_webhook_function()
    {
        register_rest_route(
            'izettle',
            '/webhook',
            [
                'methods' => 'POST',
                'callback' => array($this, 'received_webhook_call_rest'),
                'permission_callback' => array($this, 'verify_signature'),
            ]
        );

    }

    public function verify_signature(WP_REST_Request $request): bool
    {

        if (!get_site_transient('zettle_signature_connecting_to_service')){
            set_site_transient('zettle_signature_connecting_to_service', 'yes', 600);
            izettle_api()->connect_to_service();
            delete_site_transient('zettle_signature_connecting_to_service');
        }

        if (wc_string_to_bool(get_option('zettle_skip_webhook_signature_check'))){
            return true;
        }

        if ($this->isTestMessage($request)) {
            return true;
        }

        if ($this->isSubscriptionStart($request)) {
            return true;
        }

        if ($this->isBTAPITurnOffMessage($request)) {
            return true;
        }

        $signature = $request->get_header('X-Izettle-Signature');

        if (!$signature) {
            return false;
        }

        $toVerify = $this->sign(
            (string) $request['timestamp'],
            (string) $request['payload']
        );

        WC_IZ()->logger->add(sprintf('Calculated HMAC %s', $toVerify));

        return $signature === $toVerify;
    }

    /**
     * @param WP_REST_Request $request
     *
     * @return bool
     */
    private function isTestMessage(WP_REST_Request $request): bool
    {
        if ($request['eventName'] !== 'TestMessage') {
            return false;
        }

        $payload = json_decode((string) $request['payload'], true);

        if (empty($payload)) {
            return false;
        }

        if (!isset($payload['data']) || $payload['data'] !== 'payload') {
            return false;
        }

        WC_IZ()->logger->add('isTestMessage: Got TestMessage from Zettle');

        return true;
    }

    private function isBTAPITurnOffMessage(WP_REST_Request $request): bool
    {
        if ($request['eventName'] !== 'TurnOffAPIMessage') {
            return false;
        }

        $payload = json_decode((string) $request['payload'], true);

        if (empty($payload)) {
            return false;
        }

        if (!isset($payload['data']) || $payload['data'] !== 'payload') {
            return false;
        }

        update_option('izettle_send_through_service','');

        WC_IZ()->logger->add('isBTAPITurnOffMessage: Got APITurnOffMessage from Zettle');

        return true;
    }

    /**
     * @param WP_REST_Request $request
     *
     * @return bool
     */
    private function isSubscriptionStart(WP_REST_Request $request): bool
    {
        if ($request['eventName'] !== 'SubscriptionStart') {
            return false;
        }

        WC_IZ()->logger->add('received_subscription_start: Clearing notices and forcing connection');
        IZ_Notice::clear();
        delete_site_transient('izettle_did_show_unauthorized_info');
        delete_site_transient('izettle_did_show_expire_warning');
        delete_site_transient('izettle_did_show_trial_info');
        delete_site_transient('izettle_did_show_avaliable_info');
        delete_site_transient('zettle_tax_rates');
        delete_site_transient('zettle_tax_settings');
        do_action('izettle_force_connection');

        return true;
    }

    /**
     * @param WP_REST_Request $request
     *
     * @return array
     */
    public function received_webhook_call_rest(WP_REST_Request $request): array
    {
        $this->registerShutdownHook($request);
        return ['status' => 200];
    }

    /**
     * @param string $payload
     */
    public function registerShutdownHook(WP_REST_Request $request): void
    {
        $priority = intval(get_option('izettle_webhook_priority', '10')) !== 0 ? intval(get_option('izettle_webhook_priority', '10')) : 10;

        add_action(
            'shutdown',
            function () use ($request): void {
                if (self::$shutDownCalled) {
                    return;
                }
                $this->handle_webhook_call_rest($request);
                self::$shutDownCalled = true;
            }
        , $priority );
    }

    /**
     * @param string $timestamp
     * @param string $payload
     *
     * @return string
     */
    private function sign(string $timestamp, string $payload): string
    {
        $webhook_signing_key = izettle_api()->get_webhook_signing_key();
        WC_IZ()->logger->add(sprintf('Webhook signing key %s', $webhook_signing_key));
        return hash_hmac(
            'sha256',
            "{$timestamp}.{$payload}",
            $webhook_signing_key
        );
    }

    public function handle_webhook_call_rest(WP_REST_Request $request)
    {

        try {

            WC_IZ()->logger->add(sprintf('received_webhook_call: Received %s with messageId %s', $request['eventName'], $request['messageId']));

            switch ($request['eventName']) {

                case 'PurchaseCreated':
                    if (wc_string_to_bool(get_option('zettle_enable_purchase_processing')) && 1 == get_option('izettle_purchase_sync_model')) {
                        $payload = json_decode($request['payload']);
                        WC_IZ()->logger->add(sprintf('received_webhook_call: Entering PurchaseCreated'));
                        do_action('izettle_add_purchase_to_queue', $payload->purchaseUuid, true);
                    } else {
                        WC_IZ()->logger->add(sprintf('received_webhook_call: Not processing PurchaseCreated %s', $request['messageId']));
                    }
                    break;

                case 'InventoryBalanceChanged':
                    if (WC_Zettle_Helper::zettle_changes_stock()) {
                        $payload = json_decode($request['payload']);
                        if (!get_site_transient('izettle_stocklevel_update' . $payload->externalUuid)) {
                            WC_IZ()->logger->add(sprintf('received_webhook_call: Entering InventoryBalanceChanged'));
                            do_action('izettle_received_inventory_balance_changed_add', $payload, $request['messageId'], true);
                        } else {
                            WC_IZ()->logger->add(sprintf('received_webhook_call: Not updating own InventoryBalanceChanged %s', $request['messageId']));
                        }
                    }
                    break;

                case 'ProductCreated':
                    if (false !== strpos(get_option('izettle_when_changed_in_izettle'), 'create')) {
                        if ($payload = json_decode($request['payload'])) {
                            if (!isset($payload->externalReference)) {
                                WC_IZ()->logger->add(sprintf('received_webhook_call: Entering ProductCreated'));
                                do_action('izettle_sync_products_from_izettle_add', $payload, $request['messageId'], true);
                            } else {
                                WC_IZ()->logger->add(sprintf('received_webhook_call: Not updating own created product with UUID %s', $payload->uuid));
                            }
                        }
                    }
                    break;

                case 'ProductUpdated':
                    if (false !== strpos(get_option('izettle_when_changed_in_izettle'), 'update')) {
                        if ($payload = json_decode($request['payload'])) {
                            if (!isset($payload->newEntity->externalReference) || $payload->newEntity->etag != get_post_meta(WC_Zettle_Helper::get_id($payload->newEntity->externalReference), '_izettle_product_etag', true)) {
                                WC_IZ()->logger->add(sprintf('received_webhook_call: Entering ProductUpdated'));
                                do_action('izettle_sync_products_from_izettle_add', $payload, $request['messageId'], true, false);
                            } else {
                                WC_IZ()->logger->add(sprintf('received_webhook_call: Not updating unchanged ProductUpdated %s with etag %s', $request['messageId'], $payload->newEntity->etag));
                            }
                        }
                    }
                    break;

                case 'ProductDeleted':
                    if (false !== strpos(get_option('izettle_when_changed_in_izettle'), 'delete')) {
                        if ($payload = json_decode($request['payload'])) {
                            WC_IZ()->logger->add(sprintf('received_webhook_call: Entering ProductDeleted'));
                            do_action('izettle_delete_product_from_izettle', $payload->uuid, get_option('izettle_force_delete_in_woocommerce') == 'yes');
                        }
                    }
                    break;

                case 'InventoryTrackingStarted':
                    if (WC_Zettle_Helper::zettle_changes_stock()) {
                        if ($payload = json_decode($request['payload'])) {
                            WC_IZ()->logger->add(sprintf('received_webhook_call: Entering InventoryTrackingStarted'));
                            do_action('izettle_received_inventory_tracking_started_add', $payload, $request['messageId'], true);
                        }
                    }
                    break;

                case 'InventoryTrackingStopped':
                    if (WC_Zettle_Helper::zettle_changes_stock()) {
                        if ($payload = json_decode($request['payload'])) {
                            WC_IZ()->logger->add(sprintf('received_webhook_call: Entering InventoryTrackingStopped'));
                            do_action('izettle_received_inventory_tracking_stopped_add', $payload, $request['messageId'], true);
                        }
                    }
                    break;

            }

        } catch (Throwable $throwable) {

            WC_IZ()->logger->add(print_r($throwable, true));

        }

    }

    /**
     * Returns a new instance of self, if it does not already exist.
     *
     * @access public
     * @static
     * @return WC_iZettle_Integration
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}

/**
 * Make the object available for later use
 *
 * @return WC_iZettle_Integration
 */
function WC_IZ()
{
    return WC_iZettle_Integration::instance();
}

/**
 * Instantiate
 */
$izettle = WC_IZ();

/**
 * Add link to settings in the plugin page
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'WC_iZettle_Integration::add_action_links');

/**
 * Aactivities to be performed then the plugin is deactivated
 */
function woocommerce_izettle_integration_uninstall()
{
    delete_option('izettle_last_product_sync_done');
}

register_uninstall_hook(__FILE__, 'woocommerce_izettle_integration_uninstall');

/**
 * Activities to be performed then the plugin is activated
 */
function woocommerce_izettle_integration_activate()
{

    /**
     * Log the activation time in a transient
     */
    set_site_transient('izettle_activation_time', current_time('timestamp'));

    /**
     * Set transient to always force the plugin to ask for credentials when activated
     */
    set_site_transient('izettle_activated_or_upgraded', 1);

}

register_activation_hook(__FILE__, 'woocommerce_izettle_integration_activate');

/**
 * Aactivities to be performed then the plugin is deactivated
 */
function woocommerce_izettle_integration_deactivate()
{

}

register_deactivation_hook(__FILE__, 'woocommerce_izettle_integration_deactivate');

function izettle_upgrade_completed($upgrader_object, $options)
{

    $our_plugin = plugin_basename(__FILE__);
    if ($options['action'] == 'update' && $options['type'] == 'plugin' && isset($options['plugins'])) {
        foreach ($options['plugins'] as $plugin) {
            if ($plugin == $our_plugin) {
                set_site_transient('izettle_activated_or_upgraded', 1);
            }
        }
    }
}
add_action('upgrader_process_complete', 'izettle_upgrade_completed', 10, 2);
