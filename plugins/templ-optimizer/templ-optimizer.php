<?php
/**
 * Plugin Name: Templ Optimizer
 * Description: An easy-to-use optimization plugin that lets you clean your database and tweak various performance related settings on your WordPress site.
 * Version: 2.0.1
 * Author: Templ
 * Author URI: https://templ.io/
 * License: GNU GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: templ-optimizer
 */

defined('ABSPATH') || die();

define('TEMPL_OPTIMIZER_PATH', plugin_dir_path(__FILE__));
define('TEMPL_OPTIMIZER_URL', plugin_dir_url(__FILE__));
define('TEMPL_OPTIMIZER_BASENAME', plugin_basename(__FILE__));

class templOptimizer {
    
    protected $db = null;
    protected $tweaks = null;
    protected $cli = null;

    private $capability = 'manage_options';
    private $admin_page = 'tools.php?page=templ-optimizer';
    private $docs_link = 'https://help.templ.io/en/articles/5749500-templ-optimizer';
    private $plugin_data;

    private $default_settings = array(
        'heartbeat_interval'            => 'default',
        'wp_rocket_preload_interval'    => 'default',
    );

    function __construct() {

        if( ! function_exists('get_plugin_data') ){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $this->plugin_data = get_plugin_data(__FILE__);

        // Include sub-modules
        require_once( TEMPL_OPTIMIZER_PATH . 'includes/vendor/WPConfigTransformer.php' );
        require_once( TEMPL_OPTIMIZER_PATH . 'includes/db-optimizations.php' );
        $this->db = new templOptimizerDb();
        require_once( TEMPL_OPTIMIZER_PATH . 'includes/tweaks.php' );
        $this->tweaks = new templOptimizerTweaks();

        if( file_exists( ABSPATH . 'wp-config.php' ) ) {
            $this->config = new templOptimizerConfigTransformer( ABSPATH . 'wp-config.php' );
        }

        // Define WP CLI commands
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            require_once( TEMPL_OPTIMIZER_PATH . 'includes/cli.php' );
            $this->cli = new templOptimizerCli();
        }

        // Default settings
        register_activation_hook( __FILE__, array( $this, 'set_default_settings' ) );
        add_action( 'templ_optimizer_before_settings_page', array( $this, 'set_default_settings' ) ); // Recreate default settings if they would go missing

        // Admin page stuff
        add_filter( 'plugin_action_links_' . TEMPL_OPTIMIZER_BASENAME, array( $this, 'add_plugin_actions_links' ) );
        add_filter( 'plugin_row_meta', array( $this, 'add_plugin_info_links' ), 10, 4 );

        add_action( 'admin_menu', array( $this, 'add_admin_menu_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_filter( 'script_loader_tag', array($this, 'script_tag_filter'), 10, 3) ;

        add_action( 'rest_api_init', array($this, 'register_rest_routes') );

    }

    function set_default_settings() {
        if( get_option( 'templ_optimizer_settings' ) ) {
            return;
        }
        update_option( 'templ_optimizer_settings', $this->default_settings, true );
    }

    // Add settings link to plugin actions
    public function add_plugin_actions_links( $links ) {
        $links = array_merge(
            array( '<a href="' . esc_url( admin_url( $this->admin_page ) ) . '">' . __('Settings', 'templ-optimizer') . '</a>' ),
            $links
        );
        return $links;
    }
    
    // Add link to Docs
    public function add_plugin_info_links( $links, $plugin_file_name, $plugin_data, $status ) {
        if( strpos( $plugin_file_name, basename(__FILE__) ) ) {
            $links = array_merge(
                $links,
                array( '<a href="' . esc_url( $this->docs_link ) . '" target="_blank">' . __('Docs', 'templ-optimizer') . '</a>' )
            );
        }
        return $links;
    }

    function add_admin_menu_page() {
        if ( ! current_user_can( $this->capability ) ) {
            return;
        }
        // Add "Templ Optimizer" sub-page
        add_management_page(
            __( 'Templ Optimizer', 'templ-optimizer' ),
            __( 'Templ Optimizer', 'templ-optimizer' ),
            $this->capability,
            'templ-optimizer',
            array( $this, 'show_settings_page' )
        );
    }

    function show_settings_page() {
        wp_enqueue_script('templ-optimizer-vue');
        wp_enqueue_style('templ-optimizer-vue');
        $localization = array(
            'baseUrl' => get_rest_url( null, 'templ-optimizer/v1' ),
            'nonce' => wp_create_nonce('wp_rest'),
            'permission_check' => $this->permission_check(),
        );
        wp_localize_script('templ-optimizer-vue', 'templOptimizer', $localization);
        echo "<div id=\"templ-optimizer\" class=\"templ-optimizer\"></div>";
    }

    function enqueue_scripts( $hook_suffix ) {
        $build_time = date( "U", filemtime( TEMPL_OPTIMIZER_PATH.'assets/index.js' ) );
        wp_register_script('templ-optimizer-vue', TEMPL_OPTIMIZER_URL.'assets/index.js', array(), $build_time, true);
        wp_register_style('templ-optimizer-vue', TEMPL_OPTIMIZER_URL.'assets/index.css', array(), $build_time);
    }

    function script_tag_filter($tag, $handle, $src) {
        if ( 'templ-optimizer-vue' !== $handle ) {
            return $tag;
        }
        $tag = '<script src="' . esc_url( $src ) . '" type="module"></script>';
        return $tag;
    }

    function get_settings() {
        global $wpdb;
        $settings = array(
            'plugin_data' => $this->plugin_data,
            'current_settings' => array(
                'DISABLE_WP_CRON' => (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON) ? 'disabled' : 'enabled',
                'WP_POST_REVISIONS' => (defined('WP_POST_REVISIONS') && WP_POST_REVISIONS !== true ) ? WP_POST_REVISIONS : null, // Default is true
                'WP_MEMORY_LIMIT' => defined('WP_MEMORY_LIMIT') ? WP_MEMORY_LIMIT : null,
                'heartbeat_interval' => $this->get_option('heartbeat_interval'),
                'wp_rocket_preload_interval' => $this->get_option('wp_rocket_preload_interval'),
            ),
            'current_stats' => array(
                'database_size' => $this->db->get_database_size(),
                'delete_trashed_posts' => $this->db->count_trashed_posts(),
                'delete_revisions' => $this->db->count_revisions(),
                'delete_auto_drafts' => $this->db->count_auto_drafts(),
                'delete_orphaned_postmeta' => $this->db->count_orphaned_postmeta(),
                'delete_expired_transients' => $this->db->count_expired_transients(),
                'drop_tables_with_different_prefix' => array(
                    'count' => $this->db->count_tables_with_different_prefix(),
                    'table_list' => $this->db->list_tables_with_different_prefix(),
                    'prefix' => $wpdb->prefix,
                ),
                'convert_to_innodb' => array(
                    'count' => $this->db->count_myisam_tables(),
                    'table_list' => $this->db->list_myisam_tables(),
                ),
                'optimize_tables' => $this->db->count_tables(),
            ),
            'disabled_features' => array(),
            'hosted_by_templ' => $this->hosted_by_templ(),
        );
        if( ! $this->is_wp_rocket_enabled() ) {
            $settings['disabled_features'] []= 'wp_rocket_preload_interval';
        }
        return $settings;
    }

    function permission_check() {
        return apply_filters('templ_optimizer_permission_check', current_user_can('manage_options'));
    }

    function register_rest_routes() {
        register_rest_route( 'templ-optimizer/v1', '/get/', array(
            'methods' => 'GET',
            'permission_callback' => array($this, 'permission_check'),
            'callback' => array($this, 'get_settings'),
        ) );
        register_rest_route( 'templ-optimizer/v1', '/optimize-db/(?P<tool>.*)', array(
            'methods' => 'GET',
            'permission_callback' => array($this, 'permission_check'),
            'callback' => array($this, 'optimize_db'),
        ) );
        register_rest_route( 'templ-optimizer/v1', '/set/', array(
            'methods' => 'POST',
            'permission_callback' => array($this, 'permission_check'),
            'callback' => array($this, 'set_setting'),
        ) );
    }

    function optimize_db( $req ) {
        $tool = isset($req['tool']) ? $req['tool'] : null;
        if( ! $tool ) {
            return new WP_Error('no_tool_specified');
        }
        if( ! method_exists($this->db, $tool) ) {
            return new WP_Error('no_matching_tool_found');
        }
        call_user_func(array($this->db, $tool));
        return $this->get_settings();
    }

    function set_setting( $req ) {
        $data = $req->get_json_params();

        $setting = $data['setting'];
        $value = $data['value'];

        $method = 'set_'.strtolower($setting);
        return $this->tweaks->{$method}($value);
    }

    function get_option( string $option_name ) {
        $settings = get_option( 'templ_optimizer_settings', array() );
        if( ! array_key_exists( $option_name, $settings ) ) {
            return false;
        }
        return $settings[$option_name];
    }

    function update_option( string $option_name, $value ) {
        $settings = get_option( 'templ_optimizer_settings' );
        $settings[$option_name] = $value;
        update_option( 'templ_optimizer_settings', $settings, true );
    }

    function hosted_by_templ() {
        $hosted_by_templ = isset($_SERVER['TEMPL_APP_ID']) || get_option('templio_app_id');
        return apply_filters('hosted_by_templ', $hosted_by_templ);
    }

    function is_wp_rocket_enabled() {
        return defined('WP_ROCKET_VERSION');
    }

}

$templ_optimizer = new templOptimizer();
