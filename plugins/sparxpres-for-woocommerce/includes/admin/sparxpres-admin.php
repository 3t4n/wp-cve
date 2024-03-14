<?php
defined('ABSPATH') || exit;

class SparxpresWebSaleAdmin
{
    private $plugin;

    /**
     * Constructor
     * @param $version
     * @param $root_file
     */
    public function __construct($version, $root_file)
    {
        // Plugin Details
        $this->plugin = new stdClass();
        $this->plugin->name = 'sparxpres-for-woocommerce';
        $this->plugin->settingsName = $this->plugin->name . '-settings';
        $this->plugin->version = $version;
        $this->plugin->plugin_root_file = $root_file;

        // Hooks
        add_action('admin_menu', array($this, 'addOptionPage'));
        add_action('admin_init', array($this, 'pageInit'));

        // Filters
        add_filter('plugin_action_links_' . plugin_basename($root_file), array($this, 'addSettingsLink'));
    }

    /**
     * Add options page
     */
    public function addOptionPage()
    {
        add_options_page(
            esc_html__('Sparxpres for WooCommerce', 'sparxpres'),
            esc_html__('Sparxpres', 'sparxpres'),
            'manage_options',
            $this->plugin->name,
            array($this, 'createAdminPage')
        );
    }

    /**
     * Options page callback
     */
    public function createAdminPage()
    {
        echo '<div class="wrap">';
        echo '    <h1>' . esc_html__('Sparxpres Settings', 'sparxpres') . '</h1>';
        echo '    <form method="post" action="options.php">';
        settings_fields($this->plugin->name);
        do_settings_sections($this->plugin->settingsName);
        submit_button();
        echo '</form>';
        echo '</div>';
    }

    /**
     * Register and add settings
     */
    public function pageInit()
    {
        register_setting($this->plugin->name, SparxpresUtils::$DK_SPARXPRES_LINK_ID,
            array($this, 'validateLinkId'));
        register_setting($this->plugin->name, SparxpresUtils::$DK_SPARXPRES_VIEW_TYPE, 'trim');
        register_setting($this->plugin->name, SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_PRODUCT_PAGE, 'trim');
        register_setting($this->plugin->name, SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_CART_PAGE, 'trim');
        register_setting($this->plugin->name, SparxpresUtils::$DK_SPARXPRES_CONTENT_DISPLAY_TYPE, 'trim');
        register_setting($this->plugin->name, SparxpresUtils::$DK_SPARXPRES_INFO_PAGE_ID, 'intval');
        register_setting($this->plugin->name, SparxpresUtils::$DK_SPARXPRES_MAIN_COLOR,
            array($this, 'validateHexColor'));
        register_setting($this->plugin->name, SparxpresUtils::$DK_SPARXPRES_SLIDER_BG_COLOR,
            array($this, 'validateHexColor'));
        register_setting($this->plugin->name, SparxpresUtils::$DK_SPARXPRES_CALLBACK_IDENTIFIER, 'trim');

        require_once plugin_dir_path($this->plugin->plugin_root_file) . 'includes/admin/sparxpres-settings-utils.php';
        $settingsUtil = new Sparxpres_Settings_Utils($this->plugin->settingsName);
        $settingsUtil->add_basic_settings();
        $settingsUtil->add_advanced_settings();
    }

    /**
     * Validate link id
     */
    public function validateLinkId($value = ''): string
    {
        $value = trim($value);
        if (empty($value) || !preg_match('/^[a-z0-9\-]{36}$/i', $value)) {
            add_settings_error(
                SparxpresUtils::$DK_SPARXPRES_LINK_ID,
                SparxpresUtils::$DK_SPARXPRES_LINK_ID . '_error',
                esc_html__('Sparxpres link id', 'sparxpres') . ' ' . esc_html__('is required.', 'sparxpres')
            );
            return "";
        }

        $preValue = wp_unslash(get_option(SparxpresUtils::$DK_SPARXPRES_LINK_ID));
        if (empty($preValue) || strcmp($value, $preValue) !== 0) {
            // send callback identifier and link id to Sparxpres
            if (SparxpresUtils::post_callback_key($value)) {
                add_settings_error(
                    SparxpresUtils::$DK_SPARXPRES_CALLBACK_IDENTIFIER,
                    SparxpresUtils::$DK_SPARXPRES_CALLBACK_IDENTIFIER . '_updated',
                    esc_html__('Callback key is sent to Sparxpres.', 'sparxpres'),
                    'info'
                );
            }

            //TODO - when should we remove this?..
            $this->updateOldOrderStatuses();
        }

        return $value;
    }

    /**
     * Validate hex color
     */
    public function validateHexColor($value)
    {
        if (isset($value) && strlen($value) > 1) {
            return sanitize_hex_color(substr($value, 0, 1) === "#" ? $value : "#" . $value);
        }
        return "";
    }

    /**
     * Add settings link to plugin
     */
    public function addSettingsLink($links): array
    {
        $url = esc_url(add_query_arg('page', $this->plugin->name, get_admin_url() . 'admin.php'));
        $settingsLink = sprintf('<a href="%s">%s</a>', $url, __('Settings'));
        array_unshift($links, $settingsLink);
        return $links;
    }

    /**
     * Cleanup old order statuses
     */
    private function updateOldOrderStatuses()
    {
        if (!get_option('dk_spx_old_order_status_converted')) {
            global $wpdb;
            $wpdb->get_results("UPDATE " . $wpdb->prefix . "posts SET post_status = 'wc-completed' " .
                "WHERE post_type = 'shop_order' " .
                "AND post_status = 'wc-spx-captured'");

            $wpdb->get_results("UPDATE " . $wpdb->prefix . "posts SET post_status = 'wc-pending' " .
                "WHERE post_type = 'shop_order' " .
                "AND post_status = 'wc-spx-processing'");

            add_option('dk_spx_old_order_status_converted', true);
            delete_option('dk_spx_convert_old_order_status');

            add_settings_error(
                SparxpresUtils::$DK_SPARXPRES_CALLBACK_IDENTIFIER,
                SparxpresUtils::$DK_SPARXPRES_CALLBACK_IDENTIFIER . '_updated',
                esc_html__('Old order statuses updated', 'sparxpres'),
                'info'
            );
        }
    }

}
