<?php
/**
 * Plugin Name:       Plugin Optimizer MU
 * Plugin URI:        https://pluginoptimizer.com
 * Description:       This MU plugin is required by the Plugin Optimizer plugin. It will be removed upon deactivation.
 * Version:           1.0.8
 * Author:            Plugin Optimizer
 * Author URI:        https://pluginoptimizer.com/about/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 */

class SOSPO_MU {

    public $version                 = "1.0.8-9";

    protected static $instance      = null;

    public $current_url             = false;
    public $wp_relative_url         = false;

    public $po_plugins              = [];
    protected $po_pages             = [];
    protected $po_post_types        = [];
    protected $po_ajax_actions      = [];

    public $is_po_default_page      = false;
    public $is_being_filtered       = false;
    public $is_skipped              = false;

    public $all_plugins             = [];
    public $original_active_plugins = [];
    public $filtered_active_plugins = [];
    public $plugins_to_block        = [];
    public $blocked_plugins         = [];
    public $filters_in_use          = [];

    public $has_premium             = false;
    public $has_agent               = false;
    public $current_query_params    = [];

    public $current_url_host  = '';
    public $current_url_path  = '';
    public $current_url_params = [];

    private function __construct() {

        $this->po_ajax_actions = [
        
            // WP Core
            'update-plugin',
            
            // PO Free
            'po_save_filter',
            'po_save_group',
            'po_save_category',
            'po_create_category',
            'po_delete_elements',
            'po_publish_elements',
            'po_turn_filter_on',
            'po_turn_filter_off',
            'po_mark_tab_complete',
            'po_save_option_alphabetize_menu',
            'po_turn_off_filter',
            'po_save_original_menu',
                // 'po_get_post_types',// excluded because it wouldn't work!
            'po_scan_prospector',
            'po_save_columns_state',
            
            // PO Agent
            'PO_retrieve_filters',
            'PO_compile_filters',
            'PO_submit_filters',
            'PO_send_approval',
            'PO_delete_filter',
            'po_get_premium_filters_status',
            
            // PO Premium
            'PO_retrieve_filters',
            'PO_compile_filters',
        ];
        $this->po_plugins = [
            "plugin-optimizer/plugin-optimizer.php",
            "plugin-optimizer-agent/plugin-optimizer-agent.php",
            "plugin-optimizer-premium/plugin-optimizer-premium.php",
        ];
        $this->po_pages = [
            "/wp-admin/admin.php?page=plugin_optimizer",
            "/wp-admin/admin.php?page=plugin_optimizer_filters",
            "/wp-admin/admin.php?page=plugin_optimizer_add_filters",
            "/wp-admin/admin.php?page=plugin_optimizer_filters_categories",
            "/wp-admin/admin.php?page=plugin_optimizer_add_categories",
            "/wp-admin/admin.php?page=plugin_optimizer_groups",
            "/wp-admin/admin.php?page=plugin_optimizer_add_groups",
            "/wp-admin/admin.php?page=plugin_optimizer_worklist",
            "/wp-admin/admin.php?page=plugin_optimizer_settings",
            "/wp-admin/admin.php?page=plugin_optimizer_support",
            "/wp-admin/admin.php?page=plugin_optimizer_agent",
            "/wp-admin/admin.php?page=plugin_optimizer_pending",
            "/wp-admin/admin.php?page=plugin_optimizer_approved",
            "/wp-admin/admin.php?page=plugin_optimizer_premium"
        ];
        $this->po_post_types = [
            "plgnoptmzr_filter",
            "plgnoptmzr_group",
            "plgnoptmzr_work",
        ];

        if( $this->should_abort() ){
            return;
        }

        $this->current_full_url         = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $this->current_wp_relative_url  = str_replace( site_url(), "", $this->current_full_url );

        $pathinfo = parse_url($this->current_full_url);

        $this->current_url_host  = $pathinfo['host'];
        $this->current_url_path  = $pathinfo['path'];    
        if( !empty($pathinfo['query']) ) {
            parse_str($pathinfo['query'], $this->current_url_params);
        }

        $this->set_hooks();

    }

    private function should_abort(){

        if( wp_doing_cron() ){
            // changing to false. This should never be blocked.
            return false;
        }

        if( defined( 'WP_CLI' ) && WP_CLI ){

            return true;
        }

        if( wp_doing_ajax() ){
            
            
            // $this->write_log( $_POST, "mu_plugin-should_abort-doing_ajax-post" );
            // $this->write_log( $_GET,  "mu_plugin-should_abort-doing_ajax-get" );

            if( empty( $_POST["action"] ) ){

                return true;
            }


            if( ! in_array( $_POST["action"], $this->po_ajax_actions ) ){

                // $this->write_log( $this->po_ajax_actions, "mu_plugin-should_abort-doing_ajax-po_ajax_actions" );
                // $this->write_log( $_POST["action"], "mu_plugin-should_abort-doing_ajax-not_po_action" );
                return true;
            }

            // $this->write_log( $_POST["action"], "mu_plugin-should_abort-doing_ajax-_POST" );

        }

        return false;
    }

    static function get_instance() {

        if( self::$instance == null ){
            self::$instance = new self();
        }

        return self::$instance;
    }


    function set_hooks() {


        add_filter( 'option_active_plugins', [ $this, 'filter_active_plugins_option_value' ], 5 );

        // This sets a boolean whether agent and/or premium is installed
        add_action( 'plugins_loaded',        [ $this, 'complete_action_once_plugins_are_loaded' ], 5 );

        // This isn't doing anything at the moment
        add_action( 'shutdown',              [ $this, 'update_worklist_if_needed' ] );
    }

    function complete_action_once_plugins_are_loaded(){

        remove_filter('option_active_plugins', [ $this, 'filter_active_plugins_option_value' ], 5 );

        if( in_array( "plugin-optimizer-premium/plugin-optimizer-premium.php", $this->original_active_plugins ) ){

            $this->has_premium = true;
        }

        //if( in_array( "plugin-optimizer-agent/plugin-optimizer-agent.php", $this->original_active_plugins ) ){
        if( is_plugin_active("plugin-optimizer-agent/plugin-optimizer-agent.php") ){

            $this->has_agent = true;
        }
    }

    function filter_active_plugins_option_value( $active_plugins ) {

        /*
        */
        if( ! empty( $this->all_plugins ) ){
            return $active_plugins;
        }

        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        remove_filter('option_active_plugins', [ $this, 'filter_active_plugins_option_value' ], 5 );
        $this->all_plugins              = get_plugins();
        add_filter( 'option_active_plugins', [ $this, 'filter_active_plugins_option_value' ], 5 );

        $this->original_active_plugins  = $active_plugins;

        if( in_array( "plugin-optimizer-premium/plugin-optimizer-premium.php", $this->original_active_plugins ) ){

            $this->has_premium = true;
        }

        //if( in_array( "plugin-optimizer-agent/plugin-optimizer-agent.php", $this->original_active_plugins ) ){
        if( is_plugin_active("plugin-optimizer-agent/plugin-optimizer-agent.php") ){

            $this->has_agent = true;
        }

        $active_plugins_on_menu_save = get_option( "active_plugins_on_menu_save" );

        if( $active_plugins_on_menu_save != $active_plugins ){

            // this will trigger the script that recreates the menu:
            update_option( "active_plugins_on_menu_save", $active_plugins );
            $_GET["po_original_menu"] = "get";

        }

        $this->plugins_to_block         = $this->get_plugins_to_block_for_current_url();

        $this->filtered_active_plugins  = array_diff( $this->original_active_plugins, $this->plugins_to_block );

        $this->blocked_plugins          = array_intersect( $this->original_active_plugins, $this->plugins_to_block );

        foreach ( $this->blocked_plugins as $blockedplugin ) {
            $key = array_search( $blockedplugin, $active_plugins );
            if ( false !== $key ) {
                unset( $active_plugins[ $key ] );
            }
        }

        //return $this->filtered_active_plugins;
        return $active_plugins;
    }

    function update_worklist_if_needed(){

        if( $this->is_skipped === false && $this->is_being_filtered === false && ! $this->is_po_default_page ){ /* not doing anything */

            if( ! is_admin() ){


                // TODO we need to add endpoints to the Worklist here

            }

            // $this->write_log( ( is_admin() ? "Back end" : "Front end" ) . ": " . var_export( trim( $this->current_wp_relative_url ), true ), "update_worklist_if_needed-REQUEST_URI" );
        }
    }

    function should_block_all( $url ) {

        if( strpos( $url, 'plugin-install.php?tab=plugin-information&plugin=' ) !== false ){
            return true;
        }
    }

    function should_skip_url( $url ) {

        $skip_for = [
            '/favicon.ico',
        ];

        if( in_array( $url, $skip_for ) ){
            return true;
        } elseif( strpos( $url, 'wp-content/plugins' ) !== false ){
            return true;
        } elseif( strpos( $url, 'wp-content/themes' ) !== false ){
            return true;
        } elseif( strpos( $url, '/wp-cron.php' ) !== false ){
            return true;
        } elseif( strpos( $url, '/wp-json/' ) !== false ){
            return true;
        }

        return false;
    }

    function po_get_filters_exclude_premium(){
        global $wpdb;
        $main_query = "
        SELECT 
          `p`.`ID`,
          `p`.`post_title`,
          (SELECT `meta_value` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = 'endpoints' AND `post_id` = `p`.`ID`) as endpoints,
          (SELECT `meta_value` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = 'filter_type' AND `post_id` = `p`.`ID`) as filter_type,
          (SELECT `meta_value` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = 'dict_id' AND `post_id` = `p`.`ID`) as filter_id,
          (SELECT `meta_value` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = 'plugins_to_block' AND `post_id` = `p`.`ID`) as plugins_to_block,
          (SELECT `meta_value` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = 'belongs_to' AND `post_id` = `p`.`ID`) as belongsTo,
          (SELECT `user_email` FROM {$wpdb->prefix}users as u WHERE `u`.`ID` = `p`.`post_author`) as author
          FROM {$wpdb->prefix}posts as p 
          JOIN {$wpdb->prefix}postmeta as pm
           ON pm.post_id = p.ID
          WHERE `p`.`post_type`='plgnoptmzr_filter' 
          AND `p`.`post_status` = 'publish' 
          AND `pm`.`meta_key` = 'premium_filter'
          AND `pm`.`meta_value` != 'true'
        ";
        $results = $wpdb->get_results($main_query);
        return $results;
    }

    function po_get_filters(){
        global $wpdb;
        $main_query = "
        SELECT 
          `p`.`ID`,
          `p`.`post_title`,
          (SELECT `meta_value` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = 'endpoints' AND `post_id` = `p`.`ID`) as endpoints,
          (SELECT `meta_value` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = 'filter_type' AND `post_id` = `p`.`ID`) as filter_type,
          (SELECT `meta_value` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = 'dict_id' AND `post_id` = `p`.`ID`) as filter_id,
          (SELECT `meta_value` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = 'plugins_to_block' AND `post_id` = `p`.`ID`) as plugins_to_block,
          (SELECT `meta_value` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = 'belongs_to' AND `post_id` = `p`.`ID`) as belongsTo,
          (SELECT `meta_value` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = 'frontend' AND `post_id` = `p`.`ID`) as frontend,
          (SELECT `user_email` FROM {$wpdb->prefix}users as u WHERE `u`.`ID` = `p`.`post_author`) as author
          FROM {$wpdb->prefix}posts as p WHERE `post_type`='plgnoptmzr_filter' AND `post_status` = 'publish'";
        $results = $wpdb->get_results($main_query);
        return $results;
    }

    function get_plugins_to_block_for_current_url() {


        // On PO Ajax requests we are blocking all plugins, except PO
        if( wp_doing_ajax() && ! empty( $_POST["action"] ) && in_array( $_POST["action"], $this->po_ajax_actions ) ){
            
            $block_plugins = array_diff( $this->original_active_plugins, $this->po_plugins );
            
            // $this->write_log( $_POST, "get_plugins_to_block_for_current_url-post" );
            if( ! empty( $_POST["action"] ) && $_POST["action"] == "update-plugin" && ! empty( $_POST["plugin"] ) ){
                $this->write_log( $_POST["plugin"], "get_plugins_to_block_for_current_url-update_plugin-post-plugin" );
                
                // disabled this because some plugins need their dependency plugins
                // $block_plugins = array_diff( $block_plugins, [ $_POST["plugin"] ] );
            }
            
            $this->is_skipped = true; /* not doing anything */
            return $block_plugins;
        }

        // On PO Ajax requests we are blocking all plugins, except PO
        if( wp_doing_ajax() && ! empty( $_POST["action"] ) ){
                        
            $block_plugins = array_diff( $this->original_active_plugins, $this->po_plugins );

            
            $filters = $this->po_get_filters();

            foreach( $filters as $filter ){

                if( $filter->turned_off ){

                    continue;
                }

                // Filter by URL

                $endpoints = unserialize($filter->endpoints);
                $endpoints = is_array( $filter->endpoints ) ? $filter->endpoints : [ $filter->endpoints ];

                $protocol = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

                $index = strlen($protocol .'://'.$_SERVER['HTTP_HOST']);

                $referer = substr($_SERVER['HTTP_REFERER'], $index );

                if( in_array( $referer, $endpoints ) ){

                    $this->use_filter( $filter );

                } else {

                    foreach( $endpoints as $endpoint ){

                        if( fnmatch( $endpoint, $this->current_wp_relative_url, FNM_PATHNAME | FNM_CASEFOLD ) ){

                            $this->use_filter( $filter );

                            break;
                        }

                    }

                }

            }

            
            $this->is_skipped = true; /* not doing anything */
            return $block_plugins;
        }

        // some URLs just need all plugins to get blocked
        if( $this->should_block_all( $this->current_wp_relative_url ) ){
            $this->is_skipped = true; /* not doing anything */
            return $this->original_active_plugins;
        }

        // some URLs just need to be skipped
        if( $this->should_skip_url( $this->current_wp_relative_url ) ){
            $this->is_skipped = true; /* not doing anything */
            return [];
        }

        // when we want to disable blocking on the current page, we use ?disable_po=yes on any page
        if( ! empty( $_GET["disable_po"] ) && $_GET["disable_po"] == "yes" ){
            $this->is_skipped = true; /* not doing anything */
            return [];
        }

        // when we are recreating the menu
        if( ! empty( $_GET["po_original_menu"] ) && $_GET["po_original_menu"] == "get" ){
            $this->is_skipped = true; /* not doing anything */
            return [];
        }


        $editing_post_type = $this->is_editing_post_type( $this->current_wp_relative_url );


        // --- are we on any of the PO pages? yes, second boolean in the condition
        if(
            strpos( $this->current_wp_relative_url, "wp-admin/admin.php?page=plugin_optimizer") !== false ||
            in_array( $this->current_wp_relative_url, $this->po_pages ) ||
            in_array( $editing_post_type, $this->po_post_types )
        ){

            $this->is_po_default_page   = true;
            $this->is_being_filtered    = true;
            $this->plugins_to_block     = array_diff( $this->original_active_plugins, $this->po_plugins );

            return $this->plugins_to_block;
        }

        // --- Get plugins to block from all the filters
        
        $filters = $this->po_get_filters();

        foreach( $filters as $filter ){

            if( !empty($filter->turned_off) && $filter->turned_off ){

                continue;
            }

            // If we're on the edit post screen, filter by post type

            if( $filter->filter_type !== '_endpoint' && $editing_post_type && $editing_post_type == $filter->filter_type ){

                $this->use_filter( $filter );

                continue;
            }
            
            if( $filter->filter_type !== '_endpoint' && $filter->frontend == 'true' ){

                $slug = str_replace('/', '', $this->current_wp_relative_url);
                global $wpdb;
                $post_type = $wpdb->get_var("SELECT `post_type` FROM `{$wpdb->prefix}posts` WHERE `post_name` = '{$slug}'");

                if( $filter->filter_type == $post_type ){
                    
                    $this->use_filter($filter);
                    
                    continue;
                }
            }

            // Filter by URL
            $endpoints = unserialize($filter->endpoints);
            $endpoints = is_array( $endpoints ) ? $endpoints : [ $endpoints ];


            if( in_array( urldecode($this->current_wp_relative_url), $endpoints ) ){

                $plugins_to_block = $this->use_filter( $filter );

            } else {

                foreach( $endpoints as $endpoint ){

                    if( strpos($endpoint, '*') !== FALSE ){

                        if( fnmatch( $endpoint, $this->current_wp_relative_url, FNM_PATHNAME | FNM_CASEFOLD ) ){

                            $this->use_filter( $filter );

                            break;
                        }
                    }

                    $parsed_endpoint = parse_url($endpoint);

                    // Check if there's a path ex /blog or /about-us
                    if( !empty($parsed_endpoint['path']) && !empty($this->current_url_params)){
                        
                        // Compare the paths of current url and in the filter endpoint
                        if( $parsed_endpoint['path'] == $this->current_url_path ){


                            // Are there query params?
                            if( isset($parsed_endpoint['query']) && !empty($parsed_endpoint['query']) ){
                                
                                // convert endpoint params to array
                                parse_str($parsed_endpoint['query'],$endpoint_params);

                                // check if the current url is missing any of the required params in filter endpoint
                                $params_diff = array_diff_assoc($endpoint_params,$this->current_url_params );

                                // if no missing parameters - fire the filter
                                if( empty($params_diff) ){

                                    $this->use_filter( $filter );
                                    break;
                                }

                            // There's no query parameters in the endpoint and paths match so - fire filter
                            } else {

                                $this->use_filter( $filter );
                                break;
                            }

                        }
                    }

                }

            }

        }

        return array_unique( $this->plugins_to_block );
    }

    function use_filter( $filter ){

        $this->is_being_filtered = true;

        $filter->plugins_to_block = unserialize($filter->plugins_to_block);

        $plugins_to_block = ! empty( $filter->plugins_to_block ) ? array_keys( $filter->plugins_to_block ) : [];

        $this->plugins_to_block = array_merge( $this->plugins_to_block, $plugins_to_block );

        $this->filters_in_use[ $filter->ID ] = $filter->post_title;

        return $this->plugins_to_block;
    }

    function is_editing_post_type( $url ){

        $post_id   = $this->url_to_postid( $url );
        $post_type = false;

        if( $post_id !== 0 && strpos( $url, "post.php" ) !== false && strpos( $url, "action=edit" ) !== false ){

            $post_type = get_post_type( $post_id );
        }

        return $post_type;
    }

    function url_to_postid( $url ){

        parse_str( parse_url( $url, PHP_URL_QUERY ), $query_vars);

        $post_id =                   ! empty( $query_vars["post"] )    ? $query_vars["post"]    : 0;
        $post_id = $post_id === 0 && ! empty( $query_vars["post_id"] ) ? $query_vars["post_id"] : $post_id;

        return $post_id;
    }

    function write_log( $log, $text = "write_log: ", $file_name = "debug.log" )  {

        $file = WP_CONTENT_DIR . '/' . $file_name;

        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( $text . PHP_EOL . print_r( $log, true ) . PHP_EOL, 3, $file );
        } else {
            error_log( $text . PHP_EOL . $log . PHP_EOL . PHP_EOL, 3, $file );
        }
    }

    function get_names_list( $array_name, $key = "Name" ){

        $list = [];

        foreach( $this->$array_name as $plugin_id ){

            if( ! empty( $this->all_plugins[ $plugin_id ] ) ){

                $list[ $plugin_id ] = $this->all_plugins[ $plugin_id ][ $key ];
            }

        }

        // $list = array_map( function( $plugin_id ) use ( $key ){

            // return $this->all_plugins[ $plugin_id ][ $key ];

        // }, $this->$array_name );

        natcasesort( $list );

        return $list;
    }

}

function sospo_mu_plugin(){
    return SOSPO_MU::get_instance();
}
sospo_mu_plugin();
