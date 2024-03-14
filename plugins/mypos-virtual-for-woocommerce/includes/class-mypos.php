<?php

defined('ABSPATH') || exit;

/**
 * MyPOS class.
 *
 * @class MyPOS
 */
final class MyPOS
{
    /**
     * The single instance of the class.
     *
     * @var MyPOS
     */
    protected static $_instance = null;

    /**
     * Main MyPOS Instance.
     *
     * Ensures only one instance of WooCommerce is loaded or can be loaded.
     *
     * @static
     * @see MyPOS()
     * @return MyPOS - Main instance.
     */
    public static function instance()
    {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * MyPOS Constructor.
     */
    public function __construct()
    {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Define MyPOS Constants.
     */
    private function define_constants()
    {
        $this->define('MYPOS_ABSPATH', dirname(MYPOS_PLUGIN_FILE) . '/');
        $this->define('MYPOS_PLUGIN_BASENAME', plugin_basename(MYPOS_PLUGIN_FILE));
    }

    /**
     * Define constant if not already set.
     *
     * @param string $name Constant name.
     * @param string|bool $value Constant value.
     */
    private function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Returns true if the request is a non-legacy REST API request.
     *
     * @todo: replace this function once core WP function is available: https://core.trac.wordpress.org/ticket/42061.
     *
     * @return bool
     */
    public function is_rest_api_request()
    {
        if (empty($_SERVER['REQUEST_URI'])) {
            return false;
        }

        $rest_prefix         = trailingslashit( rest_get_url_prefix());
        $is_rest_api_request = (false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix)); // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

        return apply_filters('is_rest_api_request', $is_rest_api_request);
    }

    /**
     * What type of request is this?
     *
     * @param string $type admin, ajax, cron or frontend.
     * @return bool
     */
    private function is_request($type)
    {
        switch ($type) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined('DOING_AJAX');
            case 'cron':
                return defined('DOING_CRON');
            case 'frontend':
                return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON') && !$this->is_rest_api_request();
        }
    }

    public function includes() {
        /**
         * Core classes.
         */
        include_once MYPOS_ABSPATH . 'includes/mypos-core-functions.php';
        include_once MYPOS_ABSPATH . 'includes/class-mypos-install.php';

        /**
         * REST API.
         */
        include_once MYPOS_ABSPATH . 'includes/class-mypos-auth.php';
        include_once MYPOS_ABSPATH . 'rest-api/class-mypos-rest-upsell-controller.php';
        include_once MYPOS_ABSPATH . 'rest-api/class-mypos-rest-version-controller.php';

        if ($this->is_request('frontend')) {
            $this->frontend_includes();
        }
    }

    /**
     * Include required frontend files.
     */
    public function frontend_includes()
    {
        include_once MYPOS_ABSPATH . 'includes/mypos-template-hooks.php';
    }

    /**
     * Function used to Init WooCommerce Template Functions - This makes them pluggable by plugins and themes.
     */
    public function include_template_functions() {
        include_once MYPOS_ABSPATH . 'includes/mypos-template-functions.php';
    }

    /**
     * Hook into actions and filters.
     *
     */
    private function init_hooks()
    {
        register_activation_hook(MYPOS_PLUGIN_FILE, ['MyPOS_Install', 'init']);

        add_filter('woocommerce_rest_api_get_rest_namespaces', [$this, 'register_custom_api']);

        add_action('after_setup_theme', [$this, 'include_template_functions'], 11);
    }

    function register_custom_api($controllers)
    {
        $controllers['wc/v3']['upsell'] = 'WC_REST_Upsell_Controller';
        $controllers['mp']['version'] = 'MyPOS_REST_Version_Controller';

        return $controllers;
    }

    /**
     * Get the plugin url.
     *
     * @return string
     */
    public function plugin_url()
    {
        return untrailingslashit(plugins_url('/', MYPOS_PLUGIN_FILE));
    }

    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path()
    {
        return untrailingslashit(plugin_dir_path(MYPOS_PLUGIN_FILE));
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function template_path()
    {
        return apply_filters('mypos_template_path', 'mypos/');
    }

    /**
     * Set table names inside WPDB object.
     */
    public function wpdb_table_fix() {
        $this->define_tables();
    }

    /**
     * Register custom tables within $wpdb object.
     */
    private function define_tables()
    {
        global $wpdb;

        // List of tables without prefixes.
        $tables = array(
            'mp_upsells'      => 'mp_upsells',
        );

        foreach ($tables as $name => $table) {
            $wpdb->$name    = $wpdb->prefix . $table;
            $wpdb->tables[] = $table;
        }
    }

	private function get_settings()
	{
		return json_decode(get_option('woocommerce_mypos_virtual_settings', array()), false);
	}
}
