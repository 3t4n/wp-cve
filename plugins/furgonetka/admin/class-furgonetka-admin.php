<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://furgonetka.pl
 * @since      1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two hooks
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/admin
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Admin
{
    const API_REST_URL      = 'https://api.furgonetka.pl';
    const TEST_API_REST_URL = 'https://api.sandbox.furgonetka.pl';

    const PATH_ACCOUNT = '/account';
    const PATH_CREATE_OAUTH_APPLICATION = '/ecommerce/integrations/create-oauth-application';
    const PATH_CONFIGURATIONS = '/ecommerce/integrations/configurations';
    const PATH_CREATE_FORM_URL = '/ecommerce/packages/create-form-url';
    const PATH_QUICK_ACTION_INIT = '/ecommerce/packages/quick-action/init';
    const PATH_APP_LINK_INIT = '/ecommerce/app/link/init';

    const API_OAUTH_URL      = 'https://api.furgonetka.pl/oauth';
	const TEST_API_OAUTH_URL = 'https://api.sandbox.furgonetka.pl/oauth';

    const SHOP_URL      = 'https://shop.furgonetka.pl';
    const TEST_SHOP_URL = 'https://shop.sandbox.furgonetka.pl';

    const METADATA_FURGONETKA_ORDER_NUMBER = '_furgonetkaOrderNumber';

    const OPTION_CHECKOUT_UUID      = 'checkout_uuid';
    const OPTION_CHECKOUT_ACTIVE    = 'checkout_active';
    const OPTION_CHECKOUT_TEST_MODE = 'checkout_test_mode';

    const OPTION_PRODUCT_PAGE_BUTTON_VISIBLE = 'product_page_button_visible';

    const OPTION_DETAILS = [
        self::OPTION_PRODUCT_PAGE_BUTTON_VISIBLE,
    ];

    /**
     * Query params supported for admin pages
     */
    const PARAM_PAGE = 'page';
    const PARAM_ACTION = 'action';
    const PARAM_ERROR_CODE = 'error_code';
    const PARAM_ORDER_ID = 'order_id';
    const PARAM_SUCCESS = 'success';

    /**
     * Pages available within admin panel
     */
    const PAGE_FURGONETKA = 'furgonetka';
    const PAGE_FURGONETKA_PANEL_SETTINGS = 'furgonetka_panel_settings';
    const PAGE_FURGONETKA_WAITING_PACKAGES = 'furgonetka_waiting_packages';
    const PAGE_FURGONETKA_ORDERED_PACKAGES = 'furgonetka_ordered_packages';
    const PAGE_FURGONETKA_RETURNS = 'furgonetka_returns';
    const PAGE_FURGONETKA_ATTACH_MAP = 'furgonetka_attach_map';
    const PAGE_FURGONETKA_ADVANCED = 'furgonetka_advanced';

    /**
     * Actions available for the pages above
     */
    const ACTION_CONNECT_INTEGRATION = 'connect_integration';
    const ACTION_OAUTH_COMPLETE = 'oauth_complete';
    const ACTION_GET_PACKAGE_FORM = 'get_package_form';
    const ACTION_ERROR_PAGE = 'error_page';

    const ACTION_SAVE_DELIVERY = 'save_delivery';
    const ACTION_SAVE_ADVANCED = 'save_advanced';
    const ACTION_RESET_CREDENTIALS = 'reset_credentials';

    /**
     * Error codes
     */
    const ERROR_CODE_UNKNOWN = 'unknown';
    const ERROR_CODE_INACTIVE_ACCOUNT = 'inactive_account';
    const ERROR_CODE_INVALID_CREDENTIALS = 'invalid_credentials';
    const ERROR_CODE_MISSING_REQUIRED_PARAMETERS = 'missing_required_parameters';
    const ERROR_CODE_INTEGRATION_FAILED = 'integration_failed';
    const ERROR_CODE_UNSUPPORTED_LINK = 'unsupported_link';

    const SUPPORTED_ERROR_CODES = array(
        self::ERROR_CODE_UNKNOWN,
        self::ERROR_CODE_INACTIVE_ACCOUNT,
        self::ERROR_CODE_INVALID_CREDENTIALS,
        self::ERROR_CODE_MISSING_REQUIRED_PARAMETERS,
        self::ERROR_CODE_INTEGRATION_FAILED,
        self::ERROR_CODE_UNSUPPORTED_LINK
    );

    /**
     * OAuth-related constants
     */
    const PARAM_OAUTH_ERROR = 'error';
    const ERROR_OAUTH_ACCESS_DENIED = 'access_denied';

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
     * Error array.
     *
     * @since    1.0.0
     * @access   public
     * @var      array $errors Array of error messages.
     */
    public $errors;

    /**
     * Messages array.
     *
     * @since    1.0.0
     * @access   public
     * @var      array $messages Array of messages.
     */
    public $messages;

    /**
     * Furgonetka_admin_metaboxes class.
     *
     *  @var furgonetka_admin_metaboxes
     *
     */
    private $furgonetka_admin_metaboxes;

    /**
     * View
     *
     * @var \Furgonetka_Admin_View
     */
    private $view;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;

        require_once FURGONETKA_PLUGIN_DIR . 'includes/class-furgonetka-admin-metaboxes.php';
        $this->furgonetka_admin_metaboxes = new furgonetka_admin_metaboxes( $this );

        require_once FURGONETKA_PLUGIN_DIR . 'includes/view/class-furgonetka-admin-view.php';
        $this->view = new Furgonetka_Admin_View();

        if ( empty( self::get_rest_customer_key() ) || empty( self::get_rest_customer_secret() ) ) {
            $this->delete_credentials_data( false );
        }
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since 1.0.0
     */
    public function enqueue_scripts_and_styles()
    {
        /**
         * Common CSS styles
         */
        $admin_css_file_path = 'css/furgonetka-admin.css';

        wp_enqueue_style(
            "{$this->plugin_name}-admin-css",
            plugin_dir_url( __FILE__ ) . $admin_css_file_path,
            array(),
            filemtime( plugin_dir_path( __FILE__ ) . $admin_css_file_path ),
            'all'
        );

        /**
         * Quick action-related assets
         */
        if ( $this->is_current_screen_supported( $this->get_quick_action_supported_screens() ) ) {
            /**
             * CSS styles
             */
            $admin_quick_action_css_file_path = 'css/furgonetka-admin-quick-action.css';

            wp_enqueue_style(
                "{$this->plugin_name}-admin-quick-action-css",
                plugin_dir_url( __FILE__ ) . $admin_quick_action_css_file_path,
                array(),
                filemtime( plugin_dir_path( __FILE__ ) . $admin_quick_action_css_file_path ),
                'all'
            );

            /**
             * JS files
             */
            $admin_quick_action_js_file_path = 'js/furgonetka-admin-quick-action.js';

            wp_enqueue_script(
                "{$this->plugin_name}-admin-quick-action-js",
                plugin_dir_url( __FILE__ ) . $admin_quick_action_js_file_path,
                array( 'jquery' ),
                filemtime( plugin_dir_path( __FILE__ ) . $admin_quick_action_js_file_path )
            );

            wp_localize_script(
                "{$this->plugin_name}-admin-quick-action-js",
                'furgonetka_quick_action',
                array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
            );
        }

        /**
         * Settings screens assets
         */
        if ( $this->is_current_screen_supported( $this->get_plugin_settings_screens() ) ) {
            /**
             * Connect integration JS files
             */
            $admin_connect_integration_js_handle = "{$this->plugin_name}-admin-connect-integration-js";
            $admin_connect_integration_js_file_path = 'js/furgonetka-admin-connect-integration.js';

            wp_enqueue_script(
                $admin_connect_integration_js_handle,
                plugin_dir_url( __FILE__ ) .  $admin_connect_integration_js_file_path,
                array( 'jquery' ),
                filemtime( plugin_dir_path( __FILE__ ) . $admin_connect_integration_js_file_path )
            );

            wp_localize_script(
                $admin_connect_integration_js_handle,
                'furgonetka_connect_integration',
                array(
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'furgonetka_shop_base_url' => self::get_furgonetka_shop_base_url()
                )
            );

            /**
             * Error page JS files
             */
            $admin_error_page_js_handle = "{$this->plugin_name}-admin-error-page-js";
            $admin_error_page_js_file_path = 'js/furgonetka-admin-error-page.js';

            wp_enqueue_script(
                $admin_error_page_js_handle,
                plugin_dir_url( __FILE__ ) .  $admin_error_page_js_file_path,
                array( 'jquery' ),
                filemtime( plugin_dir_path( __FILE__ ) . $admin_error_page_js_file_path )
            );

            wp_localize_script(
                $admin_error_page_js_handle,
                'furgonetka_error_page',
                array(
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'furgonetka_shop_base_url' => self::get_furgonetka_shop_base_url(),
                    'furgonetka_module_settings_page_url' => self::get_plugin_admin_url()
                )
            );
        }
    }

    /**
     * Add relevant links to plugins page.
     *
     * @since 1.0.0
     *
     * @param array $links Plugin action links.
     *
     * @return array Plugin action links
     */
    public function plugin_action_links( $links )
    {
        $plugin_links = array();

        $plugin_links[] = '<a href="' . esc_url( static::get_plugin_admin_url() ) . '">'
            . esc_html__( 'Settings', 'furgonetka' ) . '</a>';

        $plugin_links[] = '<a href="mailto:ecommerce@furgonetka.pl">' .
            esc_html__( 'Contact', 'furgonetka' ) . '</a>';

        return array_merge( $plugin_links, $links );
    }

    /**
     * Add furgonetka Page to woocommerce menu
     *
     * @since 1.0.0
     */
    public function furgonetka_menu()
    {
        global $menu;
        $menu_pos = 57;
        while ( isset( $menu[ $menu_pos ] ) ) {
            $menu_pos ++;
        }
            $icon_svg = 'data:image/svg+xml;base64, PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48c3ZnIGlkPSJXYXJzdHdhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiNhN2FhYWQ7fTwvc3R5bGU+PC9kZWZzPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0ibTk3Ni41MSw0OTMuOTZWMjI5LjZMNjI5Ljg0LDMxLjMxYy03Mi44NS00MS43NC0xNjIuNzItNDEuNzQtMjM1LjU3LDBMNDcuNDksMjI5LjZ2NTI4LjYxbDkyLjMxLDUyLjgxdi0yMzcuNDFsMTU4LjQxLDkwLjA1di0xMDYuNDlsLTE1OC40MS04OS41MXYtMTMxLjhsMjY2Ljc0LDE1MS40MXYtMTA1LjcybC0yMjEuMDktMTI1LjIyLDI1NS45MS0xNDYuNDhjNDMuNzctMjQuOTgsOTcuNjEtMjQuOTgsMTQxLjM4LDBsMjU1LjE0LDE0Ni4wNC0yNTYuNDcsMTQ1Ljgydi4yMmwtNjkuMzEsMzkuNDR2MTA1LjYxbDUzLjI4LTMwLjc4LDE1LjkyLTkuMDksMTg4LjI2LTEwNy4wNCwxMTQuNDEtNjUuMDh2MzcwLjQxbC0zNzIuMjEsMjEyLjk4LTIxMy43OS0xMjIuMzctNTEuNTEtMjkuNDd2MTA1LjYxbDI2NS4zMSwxNTEuODQsNDY0LjUxLTI2NS43OHYtMjY0LjM2bC4yMi4xMVoiLz48L3N2Zz4=';

        add_menu_page(
            __('Furgonetka', 'furgonetka'),
            __('Furgonetka', 'furgonetka'),
            'manage_woocommerce',
            self::PAGE_FURGONETKA,
            array( $this, 'furgonetka_default_page' ),
            $icon_svg,
            $menu_pos
        );

        if ( self::isIntegrationActive() ) {
            add_submenu_page(
                'furgonetka',
                __('Plugin panel', 'furgonetka'),
                __('Plugin panel', 'furgonetka'),
                'manage_woocommerce',
                self::PAGE_FURGONETKA_PANEL_SETTINGS,
                array( $this, 'get_furgonetka_iframe' )
            );

            add_submenu_page(
                'furgonetka',
                __('To send', 'furgonetka'),
                __('To send', 'furgonetka'),
                'manage_woocommerce',
                self::PAGE_FURGONETKA_WAITING_PACKAGES,
                array( $this, 'get_furgonetka_iframe' )
            );

            add_submenu_page(
                'furgonetka',
                __('Ordered', 'furgonetka'),
                __('Ordered', 'furgonetka'),
                'manage_woocommerce',
                self::PAGE_FURGONETKA_ORDERED_PACKAGES,
                array( $this, 'get_furgonetka_iframe' )
            );

            add_submenu_page(
                'furgonetka',
                __('Shopping returns', 'furgonetka'),
                __('Shopping returns', 'furgonetka'),
                'manage_woocommerce',
                self::PAGE_FURGONETKA_RETURNS,
                array( $this, 'get_furgonetka_iframe' )
            );

            add_submenu_page(
                'furgonetka',
                __('Attach map', 'furgonetka'),
                __('Attach map', 'furgonetka'),
                'manage_woocommerce',
                self::PAGE_FURGONETKA_ATTACH_MAP,
                array( $this, 'get_furgonetka_map' )
            );

            add_submenu_page(
                'furgonetka',
                __('Advanced', 'furgonetka'),
                __('Advanced', 'furgonetka'),
                'manage_woocommerce',
                self::PAGE_FURGONETKA_ADVANCED,
                array( $this, 'get_furgonetka_advanced' )
            );
        }

        remove_submenu_page( 'furgonetka', 'furgonetka' );
    }

    /**
     * Quick action AJAX handler
     *
     * @return void
     */
    public function furgonetka_quick_action_init()
    {
        /**
         * Validate permissions
         */
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_send_json_error(
                array(
                    'error_message' => __( 'You do not have sufficient permissions to access this page.', 'furgonetka' ),
                )
            );
        }

        /**
         * Connection with Furgonetka.pl is not configured or token has expired
         */
        if ( get_option( $this->plugin_name . '_expires_date' ) <= strtotime( 'now' ) ) {
            wp_send_json_error(
                array(
                    'redirect_url'  => static::get_plugin_admin_url(),
                    'error_message' => __( 'Error occurred while executing quick action. Please connect module with Furgonetka.pl account.', 'furgonetka' ),
                )
            );
        }

        /**
         * Validate whether integration UUID is present
         */
        if ( empty( self::get_integration_uuid() ) ) {
            wp_send_json_error(
                array(
                    'redirect_url'  => static::get_plugin_admin_url( self::PAGE_FURGONETKA_ADVANCED ),
                    'error_message' => __( 'Error occurred while executing quick action. Please reconnect module with Furgonetka.pl account.', 'furgonetka' ),
                )
            );
        }

        /**
         * Handle quick action request
         */
        if ( isset ( $_POST['order_id'] ) ) {
            $order_id = sanitize_text_field( wp_unslash( $_POST['order_id'] ) );

            try {
                $result = self::get_quick_action_url( $order_id );

                wp_send_json_success( array ( 'url' => $result ) );
            } catch ( Exception $e ) {
                /** Silence is golden */
                $this->log( $e );
            }
        }

        /**
         * Fail when something went wrong
         */
        wp_send_json_error(
            array(
                'error_message' => __( 'Error occurred while executing quick action.', 'furgonetka' ),
            )
        );
    }

    /**
     * Connect integration handler
     *
     * @return void
     */
    public function furgonetka_connect_integration()
    {
        /**
         * Validate permissions
         */
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_send_json_error(
                array(
                    'error_message' => __( 'You do not have sufficient permissions to access this page.', 'furgonetka' ),
                )
            );
        }

        /**
         * Gather input params
         */
        $test_mode = false;

        if ( isset ( $_POST[ 'test_mode' ] ) && sanitize_text_field( wp_unslash( $_POST[ 'test_mode' ] ) ) ) {
            $test_mode = true;
        }

        update_option( $this->plugin_name . '_test_mode', $test_mode );

        /**
         * Edge-case when shop doesn't have SSL - flow excludes prompt and generates keys directly
         */
        if ( ! $this->is_shop_ssl_enabled() ) {
            $credentials = $this->create_api_credentials();
            $this->store_temporary_api_credentials( $credentials['consumer_key'], $credentials['consumer_secret'] );

            $redirect_url = static::get_plugin_admin_url(
                self::PAGE_FURGONETKA,
                self::ACTION_CONNECT_INTEGRATION,
                array( self::PARAM_SUCCESS => 1 )
            );

            wp_send_json_success( array ( 'redirect_url' => $redirect_url ) );
        }

        /**
         * Regular authorization flow with prompt
         */
        $return_url = static::get_plugin_admin_url( self::PAGE_FURGONETKA, self::ACTION_CONNECT_INTEGRATION );

        $query_string = http_build_query(
            array(
                'app_name' => __( 'Furgonetka', 'furgonetka' ),
                'scope' => 'read_write',
                'user_id' => $this->generate_auth_api_nonce(),
                'return_url' => $return_url,
                'callback_url' => get_home_url() . '/wp-json/furgonetka/v1/authorize/callback'
            )
        );

        $result = get_home_url() . '/wc-auth/v1/authorize?' . $query_string;

        wp_send_json_success( array ( 'redirect_url' => $result ) );
    }

    /**
     * Detect whether shop has SSL enabled
     */
    protected function is_shop_ssl_enabled()
    {
        $url = urldecode( get_home_url() );

        return ( strpos( $url, '://' ) === false ) || ( stripos( $url, 'https://' ) === 0 );
    }

    /**
     * Return plugin name
     *
     * @return string
     */
    public function get_plugin_name(): string
    {
        return $this->plugin_name;
    }

    public static function get_checkout_uuid()
    {
        return self::get_plugin_option( self::OPTION_CHECKOUT_UUID );
    }

    public static function is_checkout_active()
    {
        return (bool) self::get_plugin_option( self::OPTION_CHECKOUT_ACTIVE );
    }

    public static function is_checkout_test_mode()
    {
        return (bool) self::get_plugin_option( self::OPTION_CHECKOUT_TEST_MODE );
    }

    public static function is_product_page_button_visible()
    {
        return (bool) self::get_plugin_option( self::OPTION_PRODUCT_PAGE_BUTTON_VISIBLE, true );
    }

    private static function get_plugin_option( $option_name, $default_value = false )
    {
        return get_option( FURGONETKA_PLUGIN_NAME . '_' . $option_name, $default_value );
    }

    public static function get_checkout_details()
    {
        return array(
            'product_page_button_visible' => self::is_product_page_button_visible(),
        );
    }

    public static function get_checkout_rest_urls()
    {
        $checkout_paths = [
            'all_in_one',
            'cart',
            'cart/add_coupon',
            'cart/remove_coupons',
            'shippings',
            'payments',
            'coupons',
            'totals'
        ];

        $checkout_rest_urls = [];

        foreach ( $checkout_paths as $path ) {
            $checkout_rest_urls[$path] = get_rest_url( null, FURGONETKA_REST_NAMESPACE . '/checkout/' . $path );
        }

        return $checkout_rest_urls;
    }

    public function update_checkout_options( $uuid, $active, $test_mode, $details )
    {
            update_option( $this->plugin_name . '_' . self::OPTION_CHECKOUT_UUID, sanitize_text_field( strval( $uuid ) ) );
            update_option( $this->plugin_name . '_' . self::OPTION_CHECKOUT_ACTIVE, (int) $active );
            update_option( $this->plugin_name . '_' . self::OPTION_CHECKOUT_TEST_MODE, (int) $test_mode );

            foreach ( $details as $key => $detail ) {
                if ( in_array( $key, self::OPTION_DETAILS, true ) ) {
                    update_option( $this->plugin_name . '_' . $key, $detail );
                }
            }
    }

    public static function is_hpos_enabled()
    {
        return class_exists( \Automattic\WooCommerce\Utilities\OrderUtil::class ) &&
            method_exists( \Automattic\WooCommerce\Utilities\OrderUtil::class, 'custom_orders_table_usage_is_enabled' ) &&
            \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
    }

    /**
     * Furgonetka default settings page
     *
     * @since 1.0.0
     */
    public function furgonetka_default_page()
    {
        /**
         * Validate permissions
         */
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
        }

        /**
         * Handle actions
         */
        $action = $this->get_sanitized_query_param( self::PARAM_ACTION );

        switch ($action) {
            case self::ACTION_CONNECT_INTEGRATION:
                $this->connect_integration();
            case self::ACTION_OAUTH_COMPLETE:
                $this->oauth_complete();
            case self::ACTION_GET_PACKAGE_FORM:
                $this->get_package_form();
            case self::ACTION_ERROR_PAGE:
                $this->error_page();
        }

        /**
         * No action is given & account is not active = render welcome screen
         */
        if ( ! self::is_account_active() ) {
            $this->welcome_screen();
        }

        /**
         * No action is given & account is active = redirect to settings panel
         */
        $this->redirect_to_plugin_admin_page( self::PAGE_FURGONETKA_PANEL_SETTINGS );
    }

    /**
     * Get package form action
     *
     * @return never
     */
    private function get_package_form()
    {
        /**
         * Get & validate order_id
         */
        $order_id = $this->get_sanitized_query_param( self::PARAM_ORDER_ID );

        if ( $order_id === null ) {
            $this->redirect_to_error_page( self::ERROR_CODE_MISSING_REQUIRED_PARAMETERS );
        }

        if ( ! static::is_account_active() ) {
            $this->redirect_to_error_page( self::ERROR_CODE_INACTIVE_ACCOUNT );
        }

        /**
         * Render iframe
         */
        try {
            $this->render_view(
                'partials/furgonetka-admin-getpackageform.php',
                array( 'furgonetka_package_form_url' => self::get_package_form_url( (int) $order_id ) )
            );
        } catch ( Exception $e ) {
            $this->log( $e );
        }

        $this->redirect_to_error_page( self::ERROR_CODE_UNKNOWN );
    }

    /**
     * Render view with the given params
     *
     * @param $view_path
     * @param $variables
     * @return never
     */
    private function render_view( $view_path, $variables = array() )
    {
        /** Default view variables */
        $furgonetka_form_url = static::get_plugin_admin_url();
        $furgonetka_errors   = $this->errors;
        $furgonetka_messages = $this->messages;

        /** Additional view variables */
        foreach ( $variables as $key => $value ) {
            ${$key} = $value;
        }

        /** Require view */
        require $view_path;

        /**
         * End execution
         */
        exit;
    }

    /**
     * Render error page
     *
     * @return never
     */
    private function error_page()
    {
        /**
         * Get error
         */
        $error = $this->get_sanitized_query_param( self::PARAM_ERROR_CODE );

        if ( ! in_array($error, self::SUPPORTED_ERROR_CODES, true ) ) {
            $error = self::ERROR_CODE_UNKNOWN;
        }

        /**
         * Build URL
         */
        $error_screen_url = Furgonetka_Admin::get_furgonetka_shop_base_url();
        $error_screen_url .= '/ecommerce/app/error_screen?';
        $error_screen_url .= http_build_query(
            array(
                'origin' => get_home_url(),
                'type' => 'woocommerce',
                'error' => $error,
            )
        );

        /**
         * Render URL inside iframe
         */
        $this->render_iframe( $error_screen_url );
    }

    /**
     * Redirect to error page with given error
     *
     * @param string $error_code
     * @return never
     */
    private function redirect_to_error_page( $error_code )
    {
        $this->redirect_to_plugin_admin_page(
            self::PAGE_FURGONETKA,
            self::ACTION_ERROR_PAGE,
            array( self::PARAM_ERROR_CODE => $error_code )
        );
    }

    /**
     * Render welcome screen
     *
     * @return never
     */
    private function welcome_screen()
    {
        $welcome_screen_url = Furgonetka_Admin::get_furgonetka_shop_base_url();
        $welcome_screen_url .= '/ecommerce/app/welcome_screen?';
        $welcome_screen_url .= http_build_query(
            array(
                'origin' => get_home_url(),
                'type' => 'woocommerce',
            )
        );

        $this->render_iframe( $welcome_screen_url );
    }

    /**
     * Render iframe view for the given URL
     *
     * @param $url
     * @return never
     */
    private function render_iframe( $url )
    {
        $this->render_view(
            plugin_dir_path( __DIR__ ) . 'includes/view/furgonetka-iframe.php',
            array( 'url' => $url )
        );
    }

    /**
     * Get sanitized query param from $_GET superglobal
     *
     * @param $name
     * @return string|null
     */
    private function get_sanitized_query_param( $name )
    {
        if ( isset( $_GET[ $name ] ) ) {
            return sanitize_text_field( wp_unslash( $_GET[ $name ] ) );
        }

        return null;
    }

    public function get_furgonetka_map()
    {
        $this->render_simple_form( 'includes/view/furgonetka-attach-map.php' );
    }

    public function get_furgonetka_advanced()
    {
        $additional_data = [
            'position_options' => [
                'To left' => 'left',
                'Center' => 'center',
                'To right' => 'right'
            ],
            'width_options' => [
                'Automatic' => 'auto',
                'Half width' => 'half',
                'Full width' => 'full'
            ]
        ];

        $this->render_simple_form( 'includes/view/furgonetka-advanced.php', $additional_data );
    }

    /**
     * @param $viewPath
     * @param $additional_data
     * @return never
     */
    public function render_simple_form( $viewPath, $additional_data = null )
    {
        if ( !current_user_can( 'manage_woocommerce') && !self::isIntegrationActive() ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
        }

        /**
         * Get action
         */
        $action = null;

        if (
            isset( $_POST['_wpnonce'], $_POST['furgonetkaAction'] )
            && wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ) )
        ) {
            $action = sanitize_text_field( wp_unslash( $_POST['furgonetkaAction'] ) );
        }

        /**
         * Handle action
         */
        switch ($action) {
            case self::ACTION_SAVE_DELIVERY:
                $this->save_delivery();
                break;
            case self::ACTION_SAVE_ADVANCED:
                $this->save_advanced_settings();
                break;
            case self::ACTION_RESET_CREDENTIALS:
                $this->reset_credentials();
                $this->redirect_to_plugin_admin_page();
        }

        /**
         * Render view
         */
        $this->render_view(
            plugin_dir_path( __DIR__ ) . $viewPath,
            array( 'additional_data' => $additional_data )
        );
    }

    public function get_furgonetka_iframe()
    {
        $full_page_name = $this->get_sanitized_query_param( self::PARAM_PAGE );
        $this->render_furgonetka_app_link( $full_page_name );
    }

    /**
     * @param $full_page_name
     * @return never
     */
    private function render_furgonetka_app_link( $full_page_name )
    {
        if ( !current_user_can( 'manage_woocommerce' ) && !self::isIntegrationActive() ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
        }

        /**
         * Remove prefix from full page name to get proper, common name
         */
        $page = str_replace( 'furgonetka_', '', $full_page_name );

        try {
            $url = self::get_app_link_url( $page );

            $this->render_iframe( $url );
        } catch (Exception $e) {
            $this->log( 'Error occurred while getting app link.' );

            $this->redirect_to_error_page( self::ERROR_CODE_UNSUPPORTED_LINK );
        }
    }

    /**
     * Validate user by code and save
     *
     * @since 1.0.0
     * @throws Exception
     */
    private function save_credentials_code()
    {
        $code  = isset( $_GET['code'] ) ? urldecode( sanitize_text_field( wp_unslash( $_GET['code'] ) ) ) : null;
        $state = isset( $_GET['state'] ) ? urldecode( sanitize_text_field( wp_unslash( $_GET['state'] ) ) ) : null;

        if ( ! wp_verify_nonce( $state, 'furgonetka_csrf' ) ) {
            throw new Exception( 'Incorrect CSRF' );
        }

        $test                = self::get_test_mode();
        $key_consumer_key    = get_option( $this->plugin_name . '_key_consumer_key' );
        $key_consumer_secret = get_option( $this->plugin_name . '_key_consumer_secret' );

        try {
            $this->grant_code_access($code, self::get_client_id(), self::get_client_secret(), $test );

            $integration_identifiers = $this->add_integration_source(
                $key_consumer_key,
                $key_consumer_secret
            );
            $source_id               = $integration_identifiers->sourceId ?? null;
            $integration_uuid        = $integration_identifiers->integrationUuid ?? null;

            if ( is_numeric( $source_id ) && is_string( $integration_uuid ) ) {
                update_option( $this->plugin_name . '_source_id', $source_id );
                update_option( $this->plugin_name . '_integration_uuid', $integration_uuid );
            } else {
                throw new Exception( 'Invalid source_id or integration_uuid' );
            }
        } catch ( Exception $e ) {
            $this->delete_credentials_data();
            $this->log( $e );

            throw $e;
        }
    }

    /**
     * Delete credentials data.
     *
     * @return void
     */
    private function delete_credentials_data( $delete_temporary_data = true )
    {
        delete_option( $this->plugin_name . '_source_id' );
        delete_option( $this->plugin_name . '_integration_uuid' );
        delete_option( $this->plugin_name . '_access_token' );
        delete_option( $this->plugin_name . '_refresh_token' );
        delete_option( $this->plugin_name . '_expires_date' );

        if ( $delete_temporary_data ) {
            delete_option( $this->plugin_name . '_key_consumer_key' );
            delete_option( $this->plugin_name . '_key_consumer_secret' );
        }
    }

    /**
     * Save delivery option
     *
     * @since 1.0.0
     */
    private function save_delivery()
    {
        if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ) ) ) {
            $map_to_delivery = map_deep( wp_unslash( isset( $_POST['mapToDelivery'] ) ?
                $_POST['mapToDelivery'] : [] ), 'sanitize_text_field' );
            $result_array    = array();
            $fail            = false;
            if ( ! empty( $map_to_delivery ) ) {
                foreach ( $map_to_delivery as $type => $options ) {
                    foreach ( $options as $option ) {
                        if ( isset( $result_array[ sanitize_text_field( $option ) ] ) ) {
                            $fail = true;
                            break 2;
                        }
                        $result_array[ $option ] = sanitize_text_field( $type );
                    }
                }
            }
            if ( $fail ) {
                $this->errors[] = esc_html__( 'Every delivery option can have just one map attached.', 'furgonetka' );
                return;
            }
            update_option( $this->plugin_name . '_deliveryToType', $result_array );
            $this->messages[] = esc_html__( 'Configuration saved successfully.', 'furgonetka' );
        }
    }

    /**
     * Reset credentials
     *
     * @since 1.0.0
     */
    private function reset_credentials()
    {
        delete_option( $this->plugin_name . '_client_ID' );
        delete_option( $this->plugin_name . '_client_secret' );
        delete_option( $this->plugin_name . '_access_token' );
        delete_option( $this->plugin_name . '_refresh_token' );
        delete_option( $this->plugin_name . '_expires_date' );
        delete_option( $this->plugin_name . '_test_mode' );
        delete_option( $this->plugin_name . '_email' );
        delete_option( $this->plugin_name . '_source_id' );
        delete_option( $this->plugin_name . '_integration_uuid' );

        delete_option( $this->plugin_name . '_key_consumer_key' );
        delete_option( $this->plugin_name . '_key_consumer_secret' );
    }

    /**
     * Get product html selector to put portmonetka button in
     *
     * @since 1.2.2
     */
    public static function get_portmonetka_product_selector()
    {
        return self::get_plugin_option( 'portmonetka_product_selector' );
    }

    /**
     * Get cart html selector to put portmonetka button in
     *
     * @since 1.2.2
     */
    public static function get_portmonetka_cart_selector()
    {
        return self::get_plugin_option( 'portmonetka_cart_selector' );
    }

    /**
     * Get cart html selector to put portmonetka button in
     *
     * @since 1.2.2
     */
    public static function get_portmonetka_minicart_selector()
    {
        return self::get_plugin_option( 'portmonetka_minicart_selector' );
    }

    /**
     * Get cart button position selector
     *
     * @since 1.2.4
     */
    public static function get_portmonetka_cart_button_position()
    {
        return self::get_plugin_option( 'portmonetka_cart_button_position' );
    }

    /**
     * Get cart button width
     *
     * @since 1.2.4
     */
    public static function get_portmonetka_cart_button_width()
    {
        return self::get_plugin_option( 'portmonetka_cart_button_width' );
    }

    /**
     * Get cart button css
     *
     * @since 1.2.4
     */
    public static function get_portmonetka_cart_button_css()
    {
        return self::get_plugin_option( 'portmonetka_cart_button_css' );
    }

    /**
     * Save advanced settings
     *
     * @since 1.2.2
     */
    private function save_advanced_settings()
    {
        $product_selector  = '';
        $cart_selector     = '';
        $minicart_selector = '';

        $button_position   = '';
        $button_width      = '';
        $button_css        = '';

        if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ) ) ) {
            $product_selector  = isset( $_POST['portmonetka_product_selector'] ) ?
                sanitize_text_field( wp_unslash( $_POST['portmonetka_product_selector'] ) ) : '';
            $cart_selector     = isset( $_POST['portmonetka_cart_selector'] ) ?
                sanitize_text_field( wp_unslash( $_POST['portmonetka_cart_selector'] ) ) : '';
            $minicart_selector = isset( $_POST['portmonetka_minicart_selector'] ) ?
                sanitize_text_field( wp_unslash( $_POST['portmonetka_minicart_selector'] ) ) : '';

            $button_position = isset( $_POST['portmonetka_cart_button_position'] ) ?
                sanitize_text_field( wp_unslash( $_POST['portmonetka_cart_button_position'] ) ) : '';
            $button_width = isset( $_POST['portmonetka_cart_button_width'] ) ?
                sanitize_text_field( wp_unslash( $_POST['portmonetka_cart_button_width'] ) ) : '';
            $button_css = isset( $_POST['portmonetka_cart_button_css'] ) ?
                sanitize_text_field( wp_unslash( $_POST['portmonetka_cart_button_css'] ) ) : '';
        }

        update_option( $this->plugin_name . '_portmonetka_product_selector', $product_selector );
        update_option( $this->plugin_name . '_portmonetka_cart_selector', $cart_selector );
        update_option( $this->plugin_name . '_portmonetka_minicart_selector', $minicart_selector );

        update_option( $this->plugin_name . '_portmonetka_cart_button_position', $button_position );
        update_option( $this->plugin_name . '_portmonetka_cart_button_width', $button_width );
        update_option( $this->plugin_name . '_portmonetka_cart_button_css', $button_css );

        $this->messages[] = esc_html__( 'Configuration saved successfully.', 'furgonetka' );
    }

    /**
     * Create integration based on given consumer key and consumer secret
     *
     * @param $ck
     * @param $cs
     * @return never
     * @throws Exception
     */
    private function create_integration_internal( $ck, $cs )
    {
        if ( empty( $ck ) || empty( $cs ) ) {
            $this->log( 'Empty consumer key or consumer secret' );

            throw new Exception(  __( 'Add integration source problem', 'furgonetka' ) );
        }

        $api_data = array(
            'type' => 'woocommerce',
            'url'              => self::get_redirect_uri(),
            'data' => array(
                'shopUrl'          => get_home_url(),
                'consumerKey'      => $ck,
                'consumerSecret'   => $cs
            )
        );

        $result = self::send_rest_api_request('POST', self::PATH_CREATE_OAUTH_APPLICATION, array(), $api_data);

        if ( empty( $result->client_id ) ) {
            $this->log( $result );

            throw new Exception(  __( 'Add integration source problem', 'furgonetka' ) );
        }

        $test_mode = self::get_test_mode() ? true : false;
        update_option( $this->plugin_name . '_client_ID', $result->client_id );
        update_option( $this->plugin_name . '_client_secret', $result->client_secret );

        /**
         * Save wp access api data in db
         */
        update_option( $this->plugin_name . '_key_consumer_key', $ck );
        update_option( $this->plugin_name . '_key_consumer_secret', $cs );

        /**
         * Woocommerce api keys
         */
        update_option( $this->plugin_name . '_woo_ck', $ck );
        update_option( $this->plugin_name . '_woo_cs', password_hash( $cs, PASSWORD_DEFAULT ) );

        update_option( $this->plugin_name . '_test_mode', $test_mode );

        $url   = self::get_test_mode() ? self::TEST_API_OAUTH_URL : self::API_OAUTH_URL;
        $query = http_build_query(
            array(
                'client_id'    => $result->client_id,
                'redirect_uri' => self::get_redirect_uri(),
                'state'        => self::get_oauth_state(),
            )
        );
        $url  .= '/authorize?response_type=code&' . $query;
        header( 'Location: ' . $url );
        die();
    }

    /**
     * Handle oAuth complete
     *
     * @return never
     */
    private function oauth_complete()
    {
        /**
         * Redirect to welcome screen when user denied access
         */
        if ( $this->get_sanitized_query_param( self::PARAM_OAUTH_ERROR ) === self::ERROR_OAUTH_ACCESS_DENIED ) {
            $this->delete_credentials_data();

            $this->redirect_to_plugin_admin_page();
        }

        /**
         * Create integration
         */
        try {
            $this->save_credentials_code();
        } catch (Exception $e) {
            $this->log( $e );

            $this->redirect_to_error_page( self::ERROR_CODE_INTEGRATION_FAILED );
        }

        /**
         * Redirect to default page for connected account
         */
        $this->redirect_to_plugin_admin_page();
    }

    /**
     * Create integration based on stored options
     *
     * @return never
     */
    private function connect_integration()
    {
        $success = $this->get_sanitized_query_param( self::PARAM_SUCCESS );

        if ( ! $success ) {
            $this->redirect_to_plugin_admin_page();
        }

        $ck = get_option($this->plugin_name . '_key_consumer_key');
        $cs = get_option($this->plugin_name . '_key_consumer_secret');

        try {
            $this->create_integration_internal($ck, $cs);
        } catch (Exception $e) {
            $this->redirect_to_error_page( self::ERROR_CODE_INTEGRATION_FAILED );
        }
    }

    /**
     * Store generated API credentials
     *
     * @param $consumer_key
     * @param $consumer_secret
     * @return void
     */
    public function store_temporary_api_credentials( $consumer_key, $consumer_secret )
    {
        update_option( $this->plugin_name . '_key_consumer_key', $consumer_key );
        update_option( $this->plugin_name . '_key_consumer_secret', $consumer_secret );
    }

    /**
     * Generate auth API nonce to verify further request
     *
     * @return string
     */
    public function generate_auth_api_nonce()
    {
        $nonce = wc_rand_hash();

        update_option( $this->plugin_name . '_auth_api_nonce', $nonce );

        return $nonce;
    }

    /**
     * Create API credentials
     *
     * This method is used as fallback when website is not protected with SSL/TLS.
     *
     * @return array
     */
    protected function create_api_credentials()
    {
        global $wpdb;

        $app_name = __( 'Furgonetka', 'furgonetka' );

        $description = sprintf(
            '%s - API (%s)',
            wc_trim_string( wc_clean( $app_name ), 170 ),
            gmdate( 'Y-m-d H:i:s' )
        );
        $user        = wp_get_current_user();

        // Created API keys.
        $permissions     = 'read_write';
        $consumer_key    = 'ck_' . wc_rand_hash();
        $consumer_secret = 'cs_' . wc_rand_hash();

        $wpdb->insert(
            $wpdb->prefix . 'woocommerce_api_keys',
            array(
                'user_id'         => $user->ID,
                'description'     => $description,
                'permissions'     => $permissions,
                'consumer_key'    => wc_api_hash( $consumer_key ),
                'consumer_secret' => $consumer_secret,
                'truncated_key'   => substr( $consumer_key, -7 ),
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );

        return array(
            'key_id'          => $wpdb->insert_id,
            'consumer_key'    => $consumer_key,
            'consumer_secret' => $consumer_secret,
            'key_permissions' => $permissions,
        );
    }

    public static function perform_migrations()
    {
        if ( function_exists( 'WC' ) ) {
            WC()->queue()->add('furgonetka_perform_migrations');
        }
    }

    public static function update_plugin_version( $version )
    {
        if ( ! self::get_rest_customer_key() || ! self::get_rest_customer_secret() ) {
            return;
        }

        $token = self::get_access_token();

        if ( ! $token ) {
            return;
        }

        $integration_uuid = self::get_integration_uuid();
        $source_id        = self::get_source_id();

        if ( ! $integration_uuid && ! $source_id ) {
            return;
        }

        $body_params = array( 'version' => $version );

        if ( $integration_uuid ) {
            $path = '/e-commerce/integrations/' . $integration_uuid . '/plugin';
        } elseif ( $source_id ) {
            $path = '/e-commerce/integrations/plugin';

            $body_params['sourceId'] = $source_id;
        }

        if ( ! empty ( $path ) ) {
            try {
                $result = self::send_rest_api_request('PATCH', $path, self::authorization_headers(), $body_params);

                if ( ! $integration_uuid && ! empty( $result->integrationUuid ) ) {
                    update_option( FURGONETKA_PLUGIN_NAME . '_integration_uuid', $result->integrationUuid );
                }
            } catch (\Exception $e) {
                /** Do nothing */
            }
        }
    }

    /**
     * Get access token by code
     *
     * @since 1.0.0.
     * @param  mixed $code          - code.
     * @param  mixed $client_id     - client id.  - id of the client.
     * @param  mixed $client_secret - client secret.  - client secret.
     * @param  mixed $test_mode     - set test mode. - set if in test mode.
     * @throws Exception - curl errors.
     */
    private function grant_code_access( $code, $client_id, $client_secret, $test_mode = null )
    {
        //phpcs:ignore
        $auth = base64_encode( $client_id . ':' . $client_secret );
        $url  = self::get_test_mode() ? self::TEST_API_OAUTH_URL : self::API_OAUTH_URL;
        $url .= '/token';

        $args     = array(
            'headers'    => array(
                'Authorization' => 'Basic ' . $auth,
                'content-type'  => 'application/x-www-form-urlencoded',
            ),
            'method'     => 'POST',
            'body'       => http_build_query(
                array(
                    'grant_type'   => 'authorization_code',
                    'code'         => $code,
                    'redirect_uri' => self::get_redirect_uri(),
                )
            ),
            'user-agent' => self::get_request_user_agent(),
        );
        $response = wp_remote_request( $url, $args );

        if ( is_wp_error( $response ) ) {
            throw new Exception( 'Curl error: ' . $response->get_error_message() );
        } else {
            $http_status   = wp_remote_retrieve_response_code( $response );
            $server_output = trim( wp_remote_retrieve_body( $response ) );
            if ( $http_status >= 400 ) {
                $error = json_decode( $server_output );
                throw new Exception( 'HTTP STATUS: ' . $http_status . ' - Message: ' . $error->message );
            } else {
                if ( 200 === $http_status ) {
                    $response = json_decode( $server_output );
                    if ( isset( $response->access_token ) ) {
                        update_option( $this->plugin_name . '_access_token', $response->access_token );
                    }
                    if ( isset( $response->refresh_token ) ) {
                        update_option( $this->plugin_name . '_refresh_token', $response->refresh_token );
                    }
                    if ( isset( $response->expires_in ) ) {
                        $expires_date = strtotime( 'now' ) + $response->expires_in;
                        update_option( $this->plugin_name . '_expires_date', $expires_date );
                    }
                    $test_mode = $test_mode ? true : false;
                    update_option( $this->plugin_name . '_test_mode', $test_mode );
                } else {
                    throw new Exception( 'BAD HTTP STATUS: ' . $http_status );
                }
            }
        }
    }

    /**
     * Add integration source
     *
     * @since 1.0.0
     *
     * @param  mixed $key_consumer_key    - consumer key.
     * @param  mixed $key_consumer_secret - secret key.
     * @throws \Exception - integration source error.
     */
    private function add_integration_source( $key_consumer_key, $key_consumer_secret )
    {
        $api_data = array(
            'type' => 'woocommerce',
            'version' => $this->version,
            'data' => array(
                'shopUrl'          => get_home_url(),
                'consumerKey'      => $key_consumer_key,
                'consumerSecret'   => $key_consumer_secret
            )
        );

        delete_option( $this->plugin_name . '_key_consumer_key' );
        delete_option( $this->plugin_name . '_key_consumer_secret' );

        $result = self::send_rest_api_request('POST', self::PATH_CONFIGURATIONS, self::authorization_headers(), $api_data );

        if ( empty ( $result->sourceId ) ) {
            if ( ! empty( $result->errors ) ) {
                $first_error = reset( $result->errors );

                throw new \Exception( $first_error->path . ': ' . $first_error->message );
            }

            throw new \Exception( __( 'Add integration source problem', 'furgonetka' ) );
        }

        return $result;
    }

    /**
     * Refresh furgonetka token
     *
     * @since 1.0.0
     */
    public function furgonetka_refresh_token()
    {
        /** Break if expires date > 7 days */
        if ( get_option( $this->plugin_name . '_expires_date' ) > strtotime( '+7 day' ) ) {
            return;
        }

        $test_mode     = get_option( $this->plugin_name . '_test_mode' );
        $client_id     = get_option( $this->plugin_name . '_client_ID' );
        $client_secret = get_option( $this->plugin_name . '_client_secret' );
        $refresh_token = get_option( $this->plugin_name . '_refresh_token' );

        try {
            $this->refresh_token( $client_id, $client_secret, $test_mode, $refresh_token );
        } catch ( Exception $e ) {
            /** Silence is golden */
            $this->log( $e );
        }
    }

    /**
     * Refresh user token
     *
     * @since 1.0.0
     *
     * @param mixed $client_id     - client id.
     * @param mixed $client_secret - client secret.
     * @param mixed $test_mode     - set test mode.
     * @param mixed $refresh_token - refresh token.
     *
     * @throws Exception - http status.
     */
    private function refresh_token( $client_id, $client_secret, $test_mode, $refresh_token )
    {
        //phpcs:ignore
        $auth = base64_encode( $client_id . ':' . $client_secret );
        $url  = $test_mode ? self::TEST_API_OAUTH_URL : self::API_OAUTH_URL;
        $url .= '/token';

        $args = array(
            'headers'    => array(
                'Authorization' => 'Basic ' . $auth,
                'content-type'  => 'application/x-www-form-urlencoded',
            ),
            'method'     => 'POST',
            'body'       => http_build_query(
                array(
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => $refresh_token,
                    'redirect_uri'  => self::get_redirect_uri(),
                )
            ),
            'user-agent' => self::get_request_user_agent(),
        );

        $response = wp_remote_request( $url, $args );

        if ( is_wp_error( $response ) ) {
            throw new Exception( 'Curl error: ' . $response->get_error_message() );
        } else {
            $http_status   = wp_remote_retrieve_response_code( $response );
            $server_output = trim( wp_remote_retrieve_body( $response ) );

            if ( $http_status >= 400 ) {
                $error = json_decode( $server_output );
                throw new Exception( 'HTTP STATUS: ' . $http_status . ' - Message: ' . $error->message );
            } else {
                if ( 200 === $http_status ) {
                    $response = json_decode( $server_output );
                    if ( isset( $response->access_token ) ) {
                        update_option( $this->plugin_name . '_access_token', $response->access_token );
                    }
                    if ( isset( $response->refresh_token ) ) {
                        update_option( $this->plugin_name . '_refresh_token', $response->refresh_token );
                    }
                    if ( isset( $response->expires_in ) ) {
                        $expires_date = strtotime( 'now' ) + $response->expires_in;
                        update_option( $this->plugin_name . '_expires_date', $expires_date );
                    }
                } else {
                    throw new Exception( 'BAD HTTP STATUS: ' . $http_status );
                }
            }
        }
    }

    /**
     * Get user balance from API
     *
     * @throws \Exception - Get balance problem.
     *
     * @return string
     */
    public static function get_balance()
    {
        $response = self::send_rest_api_request('GET', self::PATH_ACCOUNT, array_merge( self::authorization_headers(), self::furgonetka_api_v2_headers() ) );

        if ( ! isset( $response->user->balance ) ) {
            if ( ! empty( $response->message ) ) {
                throw new \Exception( $response->message );
            }

            throw new \Exception( __( 'Balance GET problem', 'furgonetka' ) );
        }

        return $response->user->balance;
    }

    /**
     * Get package form url from API
     *
     * @param mixed $order_id     - order ID.
     *
     * @throws \Exception - url package error.
     *
     * @since 1.0.0
     */
    public static function get_package_form_url( $order_id )
    {
        $order_data = wc_get_order( $order_id );
        $additional_services = array();

        if ( ! $order_data ) {
            throw new \Exception( __( 'Get package Form URL problem.', 'furgonetka' ) );
        }

        if ( $order_data->get_payment_method() === 'cod' ) {
            $additional_services['cod'] = array(
                'amount' => $order_data->get_total(),
            );
        }

        $products_names = array();
        $total_weight = 0;
        foreach ( $order_data->get_items() as $item ) {
            $products_names[] = $item['name'];
            if ($item instanceof WC_Order_Item_Product && $item->get_product() instanceof WC_Product) {
                $total_weight += (float) $item->get_product()->get_weight();
            }
        }

        $parcels = array(
            array(
                'description' => implode(', ', $products_names),
                'value'       => $order_data->get_total(),
                'weight'      => $total_weight
            )
        );

        $receiver = self::get_receiver( $order_data );

        $reference = $order_data->get_order_number();

        if ( empty( $reference ) ) {
            $reference = $order_id;
        }

        $data = array(
            'receiver'                 => $receiver,
            'additional_services'      => $additional_services,
            'service'                  => self::get_service( $order_data ),
            'service_description'      => $order_data->get_shipping_method(),
            'type'                     => 'package',
            'partner_reference_number' => $reference,
            'user_reference_number'    => $reference,
            'parcels'                  => $parcels,
            'sale_source_id'           => self::get_source_id(),
            'integration_uuid'         => self::get_integration_uuid()
        );

        $result = self::send_rest_api_request('POST', self::PATH_CREATE_FORM_URL, self::authorization_headers(), $data );

        if ( empty ( $result->url )) {
            if ( ! empty( $result->errors ) ) {
                $first_error = reset( $result->errors );

                throw new \Exception( $first_error->path . ': ' . $first_error->message );
            }

            throw new \Exception( __( 'Get package Form URL problem.', 'furgonetka' ) );
        }

        /**
         * Store order number in metadata
         */
        if ( $order_data->get_order_number() !== ( (string) $order_data->get_id() ) ) {
            $order_data->update_meta_data( self::METADATA_FURGONETKA_ORDER_NUMBER, $order_data->get_order_number() );
            $order_data->save();
        }

        return $result->url;
    }

    /**
     * Quick Action API
     *
     * @param mixed $order_id - order ID.
     * @throws \Exception     - error
     */
    public static function get_quick_action_url( $order_id )
    {
        /**
         * Get order number
         */
        $order_data = wc_get_order( $order_id );

        if ( ! $order_data ) {
            throw new \Exception( __( 'Get quick action URL problem.', 'furgonetka' ) );
        }

        $reference = $order_data->get_order_number();

        if ( empty( $reference ) ) {
            $reference = $order_id;
        }

        /**
         * Initialize quick action
         */
        $data = array(
            'integrationUuid' => self::get_integration_uuid(),
            'sourceOrderId'   => $reference,
            'shopOrderId'     => $order_id
        );

        $path = self::PATH_QUICK_ACTION_INIT . '?' . http_build_query( $data );

        $result = self::send_rest_api_request('POST', $path, self::authorization_headers() );

        if ( empty ( $result->url )) {
            if ( ! empty( $result->errors ) ) {
                $first_error = reset( $result->errors );

                throw new \Exception( $first_error->path . ': ' . $first_error->message );
            }

            throw new \Exception( __( 'Get quick action URL problem.', 'furgonetka' ) );
        }

        /**
         * Store order number in metadata
         */
        if ( $order_data->get_order_number() !== ( (string) $order_data->get_id() ) ) {
            $order_data->update_meta_data( self::METADATA_FURGONETKA_ORDER_NUMBER, $order_data->get_order_number() );
            $order_data->save();
        }

        return $result->url;
    }

    public static function get_app_link_url(string $page)
    {
        $data = array(
            'integrationUuid' => self::get_integration_uuid(),
            'page'            => $page,
        );

        $path = self::PATH_APP_LINK_INIT . '?' . http_build_query( $data );

        $response = self::send_rest_api_request('POST', $path, self::authorization_headers() );

        if ( empty( $response->url ) ) {
            throw new Exception();
        }

        return $response->url;
    }

    /**
     * Get receiver from order
     *
     * @param bool|WC_Order|WC_Order_Refund $order_data - order data.
     *
     * @since 1.0.0
     */
    public static function get_receiver( $order_data )
    {
        if ( $order_data ) {
            $point = $order_data->get_meta( '_furgonetkaPoint' );
            $name = ! empty( $order_data->get_shipping_first_name() ) ?
                $order_data->get_shipping_first_name() . ' ' . $order_data->get_shipping_last_name()
                : $order_data->get_billing_first_name() . ' ' . $order_data->get_billing_last_name();
            $email = $order_data->get_billing_email();
            $company = ! empty( $order_data->get_shipping_company() ) ?
                $order_data->get_shipping_company() : $order_data->get_billing_company();
            $street = ! empty( $order_data->get_shipping_address_1() ) ?
                $order_data->get_shipping_address_1() . ' ' . $order_data->get_shipping_address_2()
                : $order_data->get_billing_address_1() . ' ' . $order_data->get_billing_address_2();
            $post_code = ! empty( $order_data->get_shipping_postcode() ) ?
                $order_data->get_shipping_postcode() : $order_data->get_billing_postcode();
            $city = ! empty( $order_data->get_shipping_city() ) ?
                $order_data->get_shipping_city() : $order_data->get_billing_city();
            $country_code = ! empty( $order_data->get_shipping_country() ) ?
                $order_data->get_shipping_country() : $order_data->get_billing_country();
            $phone = ! empty( $order_data->get_shipping_phone() ) ?
                $order_data->get_shipping_phone() : $order_data->get_billing_phone();

            $data = array(
                'name'              => $name,
                'email'             => $email,
                'company'           => $company,
                'street'            => $street,
                'postcode'          => $post_code,
                'city'              => $city,
                'country_code'      => $country_code,
                'county'            => '',
                'phone'             => $phone,
                'point'             => $point,
            );
        } else {
            $data = array(
                'name'              => '',
                'email'             => '',
                'company'           => '',
                'street'            => '',
                'postcode'          => '',
                'city'              => '',
                'country_code'      => '',
                'county'            => '',
                'phone'             => '',
                'point'             => '',
            );
        }
        return $data;
    }

    /**
     * Get furgonetka service name from order
     *
     * @param mixed $order_data - order data.
     *
     * @since 1.0.0.
     */
    public static function get_service( $order_data )
    {
        // Dhl, dpd, fedex, ups, inpost, inpostkurier, poczta, kex, ruch, xpress, gls.
        $shipping_methods = $order_data->get_shipping_methods();
        $shipping_name    = '';
        foreach ( $shipping_methods as $shipping_method ) {
            $data = $shipping_method->get_data();

            $shipping_name = $data['method_id'] . ':' . $data['instance_id'];
            if ( 'flexible_shipping' === $data['method_id'] ) {
                if (
                    isset( $shipping_method['item_meta'] )
                    && isset( $shipping_method['item_meta']['_fs_method'] )
                ) {
                    $fs_method     = $shipping_method['item_meta']['_fs_method'];
                    $shipping_name = $fs_method['id_for_shipping'];
                }
            }
        }

        $delivery_to_type = get_option( FURGONETKA_PLUGIN_NAME . '_deliveryToType' );
        $service          = null;

        if ( isset( $delivery_to_type[ $shipping_name ] ) ) {
            $service = $delivery_to_type[ $shipping_name ];
        } elseif ( $order_data ) {
            $service = $order_data->get_meta( '_furgonetkaService' );
        }

        if ( ! empty( $service ) ) {
            $type = '';
            switch ( $service ) {
                case 'inpost':
                    $type = 'inpost';
                    break;
                case 'poczta':
                    $type = 'poczta';
                    break;
                case 'kiosk':
                    $type = 'ruch';
                    break;
                case 'uap':
                    $type = 'ups';
                    break;
                case 'dpd':
                    $type = 'dpd';
                    break;
                case 'dhl':
                    $type = 'dhl';
                    break;
                case 'fedex':
                    $type = 'fedex';
                    break;
                case 'gls':
                    $type = 'gls';
                    break;
                case 'orlen':
                    $type = 'orlen';
                    break;
                default:
                    break;
            }
            return $type;
        }
        if ( ! $order_data ) {
            return;
        }

        return '';
    }

    /**
     * Get email
     *
     * @since 1.0.0.
     */
    public static function get_email()
    {
        return self::check_nonce_and_get_data( 'email', '_email' );
    }

    /**
     * Get client ID
     *
     * @since 1.0.0.
     */
    public static function get_client_id()
    {
        return self::check_nonce_and_get_data( 'clientID', '_client_ID' );
    }

    /**
     * Get client balance
     *
     * @since 1.0.0.
     */
    public static function get_client_balance()
    {
        try {
            return self::get_balance();
        } catch ( Exception $e ) {
            return $e->getMessage();
        }
    }

    /**
     * Get client service
     *
     * @since 1.0.0.
     */
    public static function get_client_secret()
    {
        return self::check_nonce_and_get_data( 'clientSecret', '_client_secret' );
    }

    /**
     * Get test mode
     *
     * @since 1.0.0.
     */
    public static function get_test_mode()
    {
        return get_option( FURGONETKA_PLUGIN_NAME . '_test_mode' );
    }

    /**
     * Check nonce and get data from options table
     *
     * @param  string $post_field_name - field name from POST table.
     * @param  string $option_name     - option name in DB.
     * @return mixed
     */
    public static function check_nonce_and_get_data( $post_field_name, $option_name )
    {
        return (
            isset( $_POST['_wpnonce'], $_POST[ $post_field_name ] )
            && wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ) )
        ) ? sanitize_text_field( wp_unslash( $_POST[ $post_field_name ] ) )
            : get_option( FURGONETKA_PLUGIN_NAME . $option_name );
    }

    /**
     * Get source id
     *
     * @since 1.0.0.
     */
    public static function get_source_id()
    {
        return get_option( FURGONETKA_PLUGIN_NAME . '_source_id' );
    }

    /**
     * Get access token
     *
     * @since    1.2.0.
     */
    public static function get_access_token()
    {
        return get_option( FURGONETKA_PLUGIN_NAME . '_access_token' );
    }

    /**
     * Get integration uuid
     *
     * @since    1.2.0.
     */
    public static function get_integration_uuid()
    {
        return get_option( FURGONETKA_PLUGIN_NAME . '_integration_uuid' );
    }

    /**
     * Get redirect Uri
     *
     * @since 1.0.0.
     */
    public static function get_redirect_uri()
    {
        return static::get_plugin_admin_url( self::PAGE_FURGONETKA, self::ACTION_OAUTH_COMPLETE );
    }

    /**
     * Get Oauth state
     *
     * @since 1.0.0.
     */
    public static function get_oauth_state()
    {
        return wp_create_nonce( 'furgonetka_csrf' );
    }

    /**
     * Generate multiselect with delivery options
     *
     * @param mixed $type             - field type.
     * @param mixed $delivery_to_type - delivery type.
     *
     * @since 1.0.0.
     */
    public static function map_attach_to( $type, $delivery_to_type )
    {
        if ( ! $type ) {
            return;
        }
        $options = '';

        $zones = WC_Shipping_Zones::get_zones();

        /**
         * Add "0" zone (that contains shipping methods without assigned real zone)
         */
        $fallback_zone = WC_Shipping_Zones::get_zone( 0 );

        if ( $fallback_zone ) {
            /**
             * Get zone data & assigned shipping methods
             */
            $data                     = $fallback_zone->get_data();
            $data['shipping_methods'] = $fallback_zone->get_shipping_methods( false, 'admin' );

            /**
             * Push zone to the array
             */
            $zones[ $fallback_zone->get_id() ] = $data;
        }
        ?>
        <select multiple size="6" name="mapToDelivery[<?php echo esc_html( $type ); ?>][]" class="furgonetka__select furgonetka__select-multiselect">
        <?php
        $supported_shipping_methods_ids = array( 'flat_rate', 'flexible_shipping_single', 'free_shipping' );
        foreach ( $zones as $zone_item ) {
            $shipping_methods = $zone_item['shipping_methods'];
            foreach ( $shipping_methods as $shipping_method ) {
                if ( ! in_array( $shipping_method->id, $supported_shipping_methods_ids, true ) ) {
                    continue;
                }
                $instance = $shipping_method->id . ':' . $shipping_method->instance_id;
                ?>
                    <option
                            value="<?php echo esc_html( $instance ); ?>"
                            <?php echo self::check_selected( $type, $instance, $delivery_to_type ) ?
                                'selected' : ''; ?>
                            class="furgonetka__option"
                    >
                        <?php echo esc_html( $zone_item['zone_name'] ) . ':' . esc_html( $shipping_method->title ); ?>
                    </option>
                <?php
            }
        }
        ?>
        </select>
        <?php
    }

    /**
     * Admin print messages
     *
     * @param mixed $messages - message.
     * @param mixed $type     - message type.
     *
     * @since 1.0.0.
     */
    public static function print_messages( $messages, $type )
    {
        if ( ! $messages ) {
            return;
        }

        if ( ! $type ) {
            $type = 'message';
        }

        if ( ! is_array( $messages ) ) {
            return;
        }

        foreach ( $messages as $message ) {
            echo sprintf(
                '<div id="message" class="updated woocommerce-%1$s inline">
                        <p>%2$s</p>
                    </div>',
                esc_html( $type ),
                esc_html( $message )
            );
        }
    }

    /**
     * Render modal
     *
     * @return void
     */
    public function render_modal()
    {
        if ( $this->is_current_screen_supported( $this->get_quick_action_supported_screens() ) ) {
            $this->view->render_modal();
        }
    }

    /**
     * Get quick action-related screens
     *
     * @return array
     */
    private function get_quick_action_supported_screens()
    {
        $supported_screens   = array();
        $supported_screens[] = self::is_hpos_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order';
        $supported_screens[] = self::is_hpos_enabled() ? wc_get_page_screen_id( 'edit-shop-order' ) : 'edit-shop_order';

        return $supported_screens;
    }

    /**
     * Get plugin settings screens
     *
     * @return array
     */
    private function get_plugin_settings_screens()
    {
        return array(
            $this->plugin_name . '_page_furgonetka',
            'toplevel_page_furgonetka'
        );
    }

    /**
     * Check whether current screen is supported
     *
     * @return bool
     */
    private function is_current_screen_supported( array $supported_screens )
    {
        $current_screen    = get_current_screen();
        $current_screen_id = $current_screen ? $current_screen->id : '';

        return in_array( $current_screen_id, $supported_screens, true );
    }

    /**
     * Check if map is attach to delivery option
     *
     * @param mixed $type             - type.
     * @param mixed $instance         - instance.
     * @param mixed $delivery_to_type - delivery type.
     *
     * @since 1.0.0
     */
    public static function check_selected( $type, $instance, $delivery_to_type )
    {

        if (
            isset( $_POST['_wpnonce'], $_POST['mapToDelivery'] )
            && wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ) )
        ) {
            if (
                isset( $_POST['mapToDelivery'][ $type ] )
                && in_array( $instance, $_POST['mapToDelivery'][ $type ], true )
            ) {
                return true;
            }
        } else {
            if ( isset( $delivery_to_type[ $instance ] ) && $delivery_to_type[ $instance ] === $type ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get woocommerce version
     *
     * @since 1.0.0
     */
    private static function get_wc_version()
    {
        if ( function_exists( 'WC' ) ) {
            return WC()->version;
        }
    }

    /**
     * Check if account is active and enabled
     *
     * @since 1.0.0
     */
    public static function is_account_active()
    {
        if ( ! get_option( FURGONETKA_PLUGIN_NAME . '_expires_date' ) ) {
            return false;
        }
        if ( get_option( FURGONETKA_PLUGIN_NAME . '_expires_date' ) < strtotime( 'now' ) ) {
            return false;
        }

        return true;
    }

    /**
     * Get user agent
     *
     * @return string
     */
    private static function get_request_user_agent()
    {
        return 'woocommerce_' . self::get_wc_version() . '_plugin_' . FURGONETKA_VERSION;
    }

    /**
     * Get rest customer key
     *
     * @return string
     */
    public static function get_rest_customer_key()
    {
        return get_option( FURGONETKA_PLUGIN_NAME . '_woo_ck' );
    }

    /**
     * Get rest customer secret
     *
     * @return string
     */
    public static function get_rest_customer_secret()
    {
        return get_option( FURGONETKA_PLUGIN_NAME . '_woo_cs' );
    }

    public static function get_rest_api_url()
    {
        return self::get_test_mode() ? self::TEST_API_REST_URL : self::API_REST_URL;
    }

    /**
     * Send request to REST API
     *
     * @param string $method
     * @param string $path
     * @param array $headers
     * @param mixed $body
     * @return mixed
     * @throws Exception
     */
    private static function send_rest_api_request( $method, $path, $headers = array(), $body = null ) {
        $args = array(
            'headers'    => array(
                'Accept'        => 'application/vnd.furgonetka.v1+json',
                'Cache-Control' => 'no-cache',
            ),
            'method'     => $method,
            'user-agent' => self::get_request_user_agent(),
            'timeout'    => 10
        );

        if ( $body !== null ) {
            $args['headers']['Content-Type'] = 'application/json';
            $args['body'] = json_encode( $body );
        }

        if ( !empty( $headers ) ) {
            $args['headers'] = array_merge( $args['headers'], $headers );
        }

        $wp_response = wp_remote_request( self::get_rest_api_url() . $path , $args );

        if ( is_wp_error( $wp_response ) ) {
            throw new Exception( $wp_response->get_error_message() );
        }

        $server_output = trim( wp_remote_retrieve_body( $wp_response ) );

        return json_decode( $server_output, false );
    }

    private static function authorization_headers()
    {
        return array(
            'Authorization' => 'Bearer ' . self::get_access_token()
        );
    }

    private static function furgonetka_api_v2_headers()
    {
        return array(
            'Accept' => 'application/vnd.furgonetka.v2+json',
        );
    }

    public static function isIntegrationActive()
    {
       return self::is_account_active() && self::get_rest_customer_key() && self::get_rest_customer_secret();
    }

    /**
     * Get Furgonetka base URL form shop subdomain
     *
     * @return string
     */
    public static function get_furgonetka_shop_base_url()
    {
        return FURGONETKA_DEBUG ? self::TEST_SHOP_URL : self::SHOP_URL;
    }

    /**
     * Get plugin admin page URL
     *
     * @param $page
     * @param $action
     * @param $params
     * @return string
     */
    public static function get_plugin_admin_url( $page = self::PAGE_FURGONETKA, $action = null, $params = array() )
    {
        /**
         * Build query params
         */
        $query_params = array(
            self::PARAM_PAGE => $page
        );

        if ( $action ) {
            $query_params[self::PARAM_ACTION] = $action;
        }

        $query_params_string = http_build_query(
            array_merge( $query_params, $params )
        );

        /**
         * Build target URL
         */
        return get_admin_url( null, '/admin.php?' . $query_params_string );
    }

    /**
     * Redirect to plugin admin page
     *
     * @param $page
     * @param $action
     * @param $params
     * @return never
     */
    private function redirect_to_plugin_admin_page( $page = self::PAGE_FURGONETKA, $action = null, $params = array() )
    {
        wp_redirect( static::get_plugin_admin_url( $page, $action, $params ) );

        exit;
    }

    /**
     * Error log
     *
     * @param $value
     * @return void
     */
    private function log( $value )
    {
        $logger = wc_get_logger();

        if ( ! $logger ) {
            return;
        }

        if ( ! is_string( $value ) ) {
            $value = serialize( $value );
        }

        $logger->error( $value, array( 'source' => $this->plugin_name ) );
    }
}
