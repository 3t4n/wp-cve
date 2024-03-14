<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    WC_Swiss_Qr_Bill
 * @subpackage WC_Swiss_Qr_Bill/admin
 */
class WC_Swiss_Qr_Bill_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    public function may_be_disable_plugin()
    {
        if (get_option('wc_swiss_qr_bill_may_deactivate', '') == 'yes') {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            deactivate_plugins(plugin_basename(WC_SWISS_QR_BILL_FILE));
        }
    }

    /**
     * @param $loader
     */
    public function admin_hooks($loader)
    {
        $loader->add_action('admin_init', $this, 'may_be_disable_plugin');
        $loader->add_action('admin_init', $this, 'handle_dismiss_notice');
        $loader->add_action('pre_update_option_woocommerce_default_country', $this, 'check_store_location', 99, 2);
        $loader->add_filter('admin_notices', $this, 'sqb_admin_notices', 99);

        // Load Payment Gateway
        $loader->add_filter('woocommerce_payment_gateways', $this, 'add_gateway', 99, 1);

        // Plugin action link
        $loader->add_filter('plugin_action_links_' . plugin_basename(WC_SWISS_QR_BILL_FILE), $this, 'plugin_action_links', 99, 1);

        // Initialize the product category setting option
        new WSQB_Settings_Product_Cat();

        // Metabox to order edit page
        $loader->add_action('add_meta_boxes_shop_order', $this, 'add_meta_boxes', 99);
        // Generate the invoice from order edit page
        $loader->add_action('wp_ajax_view_swiss_qr_bill', $this, 'generate_invoice');

    }


    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wc-swiss-qr-bill-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        if (get_current_screen()->id == 'woocommerce_page_wc-settings') {
            wp_enqueue_media();

            wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wc-swiss-qr-bill-admin.js', array('jquery'), time(), false);

            $gateways = WC()->payment_gateways->payment_gateways();
            $enabled_gateways = [];

            if ($gateways) {
                foreach ($gateways as $gateway) {
                    if ($gateway->enabled == 'yes') {
                        $enabled_gateways[] = $gateway->id;
                    }
                }
            }

            // Localize the script with new data
            $translation_array = array(
                'settings_heading' => array(
                    'woocommerce_wc_swiss_qr_bill_qr_iban' => __('QR Billing Settings', 'swiss-qr-bill'),
                    'woocommerce_wc_swiss_qr_bill_shop_logo' => __('Invoice Data', 'swiss-qr-bill'),
                    'woocommerce_wc_swiss_qr_bill_login_restriction' => __('QR Billing Restrictions ', 'swiss-qr-bill'),
                ),
                'set_image_text' => __('Set Image', 'swiss-qr-bill'),
                'remove_image_text' => __('Remove Image', 'swiss-qr-bill'),

            );
            wp_localize_script($this->plugin_name, 'wsqb_translation', $translation_array);
            wp_localize_script($this->plugin_name, 'wsqb_data', array('enabled_gateways' => $enabled_gateways));

            // Enqueued script with localized data.
            wp_enqueue_script($this->plugin_name);
        }

    }

    /**
     * Deactivate the plugin based on the shop country location
     * @param $old_value
     * @param $new_value
     */
    public function check_store_location($new_value, $old_value)
    {

        $country = explode(':', $new_value)[0];

        if (!in_array(strtoupper($country), array('CH', 'LI'))) {

            add_action('admin_notices', array($this, 'render_country_dependency_error_notice'));
            update_option('wc_swiss_qr_bill_may_deactivate', 'yes');
        }
        return $new_value;
    }

    /**
     * Display the error notice
     */
    public function render_country_dependency_error_notice()
    {
        echo '<div class="error notice is-dismissible"><p>' .
            self::country_dependency_error_notice() .
            '</p></div>';

    }

    /**
     * Return the notice message
     * @return string|void
     */
    public static function country_dependency_error_notice()
    {
        return __('Swiss QR bill for WooCommerce only works for shops in Switzerland and Liechtenstein. You have chosen another country in your shop address, therefore the plugin cannot be activated', 'swiss-qr-bill');
    }

    /**
     * Display action links in the Plugins list table.
     *
     * @param array $actions Plugin Action links.
     * @return array
     */
    public function plugin_action_links($actions)
    {
        $new_actions = array(
            'settings' => '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=wc_swiss_qr_bill') . '" aria-label="' . esc_attr(__('View Swiss QR Bill for WooCommerce settings', 'swiss-qr-bill')) . '">' . __('Settings', 'swiss-qr-bill') . '</a>',
        );

        return array_merge($new_actions, $actions);
    }

    /**
     * WooCommerce fallback notice.
     */
    public function woocommerce_missing_notice()
    {
        /* translators: %s: woocommerce version */
        echo '<div class="error notice is-dismissible"><p>' . sprintf(esc_html__('Swiss QR Bill for WooCommerce depends on %s to work!', 'swiss-qr-bill'), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">' . esc_html__('WooCommerce', 'swiss-qr-bill') . '</a>') . '</p></div>';
    }

    /**
     * Admin notices
     */
    public function sqb_admin_notices()
    {
        global $wp;
        if (!WC_Gateway_Swiss_Qr::check_currency_validation()) {
            echo '<div class="error notice is-dismissible"><p>' . sprintf(esc_html__('Swiss QR Bill for WooCommerce works only with CHF and EUR.', 'swiss-qr-bill')) . '</p></div>';

            return;
        }

        // Show this message only to the woo commerce setting page
        if ('woocommerce_page_wc-settings' !== get_current_screen()->id) {
            return;
        }
        if (!isset($_GET['tab']) || $_GET['tab'] !== 'checkout') {
            return;
        }
        // check if this notice is already dismissed
        $user_id = get_current_user_id();

        $is_notice_dismissed = get_user_meta($user_id, '_sqb_notice_dismissed', true) === 'yes';
        if (class_exists('WC_Gateway_Swiss_Qr_Classic') && !$is_notice_dismissed) { ?>
            <div class="notice notice-warning" style="position:relative;">
                <a href="<?php print wp_nonce_url(self::get_current_url(), 'sqb_notice_dismissing', 'sqb_notice_dismiss'); ?>"
                   class="woocommerce-message-close notice-dismiss"></a>
                <p><?php _e('Only one version of Swiss QR bill payment can be activated.', 'swiss-qr-bill'); ?></p>
            </div>
            <?php
        }
    }

    /**
     * @return string|void
     */
    public static function get_current_url()
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function handle_dismiss_notice()
    {
        $user_id = get_current_user_id();
        if (!$user_id) {
            return;
        }
        if (
            !isset($_GET['sqb_notice_dismiss'])
            || !wp_verify_nonce($_GET['sqb_notice_dismiss'], 'sqb_notice_dismissing')
        ) {

            return;
        }
        update_user_meta($user_id, '_sqb_notice_dismissed', 'yes');
    }

    /**
     * Filter function to add the new payment gateway
     *
     * @param [type] $methods
     * @return array list of payment gateways
     */
    public function add_gateway($methods)
    {

        $methods[] = 'WC_Gateway_Swiss_Qr';
        $methods[] = 'WC_Gateway_Swiss_Qr_Classic';
        return $methods;

    }

    /**
     * Add metabox to view the invoice of order
     * @param $post
     */

    public function add_meta_boxes($post)
    {
        $order = wc_get_order($post->ID);
        // create Invoice PDF button only with the order paid via Swiss Qr Invoicing
        if (in_array($order->get_payment_method(), array('wc_swiss_qr_bill', 'wc_swiss_qr_bill_classic'))) {
            add_meta_box(
                'wc_swiss_qr_bill-box',
                __('Swiss QR Bill for WooCommerce', 'swiss-qr-bill'),
                array($this, 'actions_meta_box'),
                'shop_order',
                'side',
                'default'
            );
        }
    }

    /**
     * @param $post
     */
    public function actions_meta_box($post)
    {
        $url = wp_nonce_url(admin_url('admin-ajax.php?action=view_swiss_qr_bill&order_id=' . $post->ID), 'generate_invoice');
        echo sprintf(__('<a href="%1$s" class="button" target="_blank">View Invoice</a>', 'swiss-qr-bill'), $url);
    }

    /**
     * Function to generate the Swiss Qr Bill
     */
    public function generate_invoice()
    {
        if (!wp_verify_nonce(sanitize_text_field($_GET['_wpnonce']), 'generate_invoice')) {
            die(__('Sorry you are not allowed to access this.', 'swiss-qr-bill'));
        }

        if (!isset($_GET['order_id'])) {
            return false;
        }

        $order = wc_get_order($_GET['order_id']);
        if (!$order) {
            return false;
        }

        $payment_method = $order->get_payment_method();
        if (!in_array($payment_method, array('wc_swiss_qr_bill', 'wc_swiss_qr_bill_classic'))) {
            return false;
        }

        //Init the invoice generate class
        new WC_Swiss_Qr_Bill_Generate(WC()->payment_gateways()->payment_gateways()[$payment_method]);

        // Get payment gateway setting option for this particular order
        $gateway_options = get_post_meta($order->get_id(), '_wsqb_gateway_data', true);

        do_action('invoice_generate', sanitize_text_field($order->get_id()), unserialize($gateway_options));
    }

}
