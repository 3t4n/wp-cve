<?php

/**
 * Plugin Name: WP-HR Manager
 * Description: Manage your HR processes and information within WordPress
 * Plugin URI: http://www.wphrmanager.com
 * Author: Black and White Digital Ltd
 * Author URI: http://www.wphrmanager.com
 * Version: 3.0.9
 * Requires at least: 5
 * License: GPLv2
 * Text Domain: wphr
 * Forked from a plugin by weDevs
 * Domain Path: /i18n/languages/
 *
 * Copyright (c) 2017-2019 wphrmanager (email: info@wphrmanager.com). All rights reserved.
 *
 * Copyright (c) 2016 weDevs (email: info@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */
// don't call the file directly
if ( !defined( 'ABSPATH' ) ) {
    die;
}
// Freemius Code starts here

if ( !function_exists( 'wphr_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wphr_fs()
    {
        global  $wphr_fs ;
        
        if ( !isset( $wphr_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $wphr_fs = fs_dynamic_init( array(
                'id'              => '1296',
                'slug'            => 'wp-hr-manager',
                'type'            => 'plugin',
                'public_key'      => 'pk_3dcdd297d8b052f4cc1fa5e68338b',
                'is_premium'      => false,
                'premium_suffix'  => 'Premium',
                'has_addons'      => true,
                'has_paid_plans'  => true,
                'trial'           => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'has_affiliation' => 'all',
                'menu'            => array(
                'slug'           => 'wphr-settings',
                'override_exact' => true,
                'affiliation'    => false,
                'parent'         => array(
                'slug' => 'wphr-support',
            ),
            ),
                'is_live'         => true,
            ) );
        }
        
        return $wphr_fs;
    }
    
    // Init Freemius.
    wphr_fs();
    // Signal that SDK was initiated.
    do_action( 'wphr_fs_loaded' );
    function wphr_fs_settings_url()
    {
        return admin_url( 'admin.php?page=wphr-settings' );
    }
    
    wphr_fs()->add_filter( 'connect_url', 'wphr_fs_settings_url' );
    wphr_fs()->add_filter( 'after_skip_url', 'wphr_fs_settings_url' );
    wphr_fs()->add_filter( 'after_connect_url', 'wphr_fs_settings_url' );
    wphr_fs()->add_filter( 'after_pending_connect_url', 'wphr_fs_settings_url' );
}

//Fremius ends here
/**
 * clsWP_HR class
 *
 * @class clsWP_HR The class that holds the entire clsWP_HR plugin
 */
final class clsWP_HR
{
    /**
     * Plugin version
     *
     * @var string
     */
    public  $version = '0.1' ;
    /**
     * Minimum PHP version required
     *
     * @var string
     */
    private  $min_php = '5.4.0' ;
    /**
     * Holds various class instances
     *
     * @var array
     */
    private  $container = array() ;
    /**
     * @var object
     *
     * @since 1.2.1
     */
    private static  $instance ;
    /**
     * Initializes the clsWP_HR() class
     *
     * @since 0.1
     * @since 1.2.1 Rename `__construct` function to `setup` and call it only once
     *
     * Checks for an existing clsWP_HR() instance
     * and if it doesn't find one, creates it.
     *
     * @return object
     */
    public static function wphr_init()
    {
        
        if ( !isset( self::$instance ) && !self::$instance instanceof clsWP_HR ) {
            self::$instance = new clsWP_HR();
            self::$instance->wphr_setup();
        }
        
        return self::$instance;
    }
    
    /**
     * Setup the plugin
     *
     * Sets up all the appropriate hooks and actions within our plugin.
     *
     * @since 1.2.1
     *
     * @return void
     *
     */
    private function wphr_setup()
    {
        // dry check on older PHP versions, if found deactivate itself with an error
        register_activation_hook( __FILE__, array( $this, 'wphr_auto_deactivate' ) );
        if ( !$this->wphr_is_supported_php() ) {
            return;
        }
        // Define constants
        $this->wphr_define_constants();
        // Include required files
        $this->wphr_includes();
        // instantiate classes
        $this->wphr_instantiate();
        // Initialize the action hooks
        $this->wphr_init_actions();
        // load the modules
        $this->wphr_load_module();
        // Loaded action
        do_action( 'wphr_loaded' );
    }
    
    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop )
    {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[$prop];
        }
        return $this->{$prop};
    }
    
    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop )
    {
        return isset( $this->{$prop} ) || isset( $this->container[$prop] );
    }
    
    /**
     * Check if the PHP version is supported
     *
     * @return bool
     */
    public function wphr_is_supported_php()
    {
        if ( version_compare( PHP_VERSION, $this->min_php, '<=' ) ) {
            return false;
        }
        return true;
    }
    
    /**
     * Bail out if the php version is lower than
     *
     * @return void
     */
    public function wphr_auto_deactivate()
    {
        if ( $this->wphr_is_supported_php() ) {
            return;
        }
        deactivate_plugins( basename( __FILE__ ) );
        $error = __( '<h1>An Error Occured</h1>', 'wphr' );
        $error .= __( '<h2>Your installed PHP Version is: ', 'wphr' ) . PHP_VERSION . '</h2>';
        $error .= __( '<p>The <strong>WPHR Manager</strong> plugin requires PHP version <strong>', 'wphr' ) . $this->min_php . __( '</strong> or greater', 'wphr' );
        $error .= __( '<p>The version of your PHP is ', 'wphr' ) . '<a href="http://php.net/supported-versions.php" target="_blank"><strong>' . __( 'unsupported and old', 'wphr' ) . '</strong></a>.';
        $error .= __( 'You should update your PHP software or contact your host regarding this matter.</p>', 'wphr' );
        wp_die( $error, __( 'Plugin Activation Error', 'wphr' ), array(
            'response'  => 200,
            'back_link' => true,
        ) );
    }
    
    /**
     * Define the plugin constants
     *
     * @return void
     */
    private function wphr_define_constants()
    {
        define( 'WPHR_VESRSION', $this->version );
        define( 'WPHR_FILE', __FILE__ );
        define( 'WPHR_PATH', dirname( WPHR_FILE ) );
        define( 'WPHR_INCLUDES', WPHR_PATH . '/includes' );
        define( 'WPHR_MODULES', WPHR_PATH . '/modules' );
        define( 'WPHR_URL', plugins_url( '', WPHR_FILE ) );
        define( 'WPHR_ASSETS', WPHR_URL . '/assets' );
        define( 'WPHR_VIEWS', WPHR_INCLUDES . '/admin/views' );
    }
    
    /**
     * Include the required files
     *
     * @return void
     */
    private function wphr_includes()
    {
        include dirname( __FILE__ ) . '/vendor/autoload.php';
        require_once WPHR_INCLUDES . '/functions.php';
        require_once WPHR_INCLUDES . '/class-install.php';
        require_once WPHR_INCLUDES . '/actions-filters.php';
        require_once WPHR_INCLUDES . '/functions-html.php';
        require_once WPHR_INCLUDES . '/functions-company.php';
        require_once WPHR_INCLUDES . '/functions-people.php';
        //require_once WPHR_INCLUDES . '/lib/class-wphr-insights.php';
        //require_once WPHR_INCLUDES . '/api/class-api-registrar.php';
        
        if ( is_admin() ) {
            require_once WPHR_INCLUDES . '/admin/functions.php';
            require_once WPHR_INCLUDES . '/admin/class-menu.php';
            require_once WPHR_INCLUDES . '/admin/class-admin.php';
        }
        
        // cli command
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            include WPHR_INCLUDES . '/cli/commands.php';
        }
    }
    
    /**
     * Instantiate classes
     *
     * @since 1.0.0
     * @since 1.2.0 Call `wphr_setup_database` to setup db immediately
     *
     * @return void
     */
    private function wphr_instantiate()
    {
        $this->wphr_setup_database();
        new \WPHR\HR_MANAGER\Admin\User_Profile();
        new \WPHR\HR_MANAGER\Scripts();
        new \WPHR\HR_MANAGER\Updates();
        //new \WPHR\HR_MANAGER\Tracker();
        //new \WPHR\HR_MANAGER\API\API_Registrar();
        $this->container['modules'] = new \WPHR\HR_MANAGER\Framework\Modules();
        $this->container['emailer'] = \WPHR\HR_MANAGER\Emailer::wphr_init();
        $this->container['integration'] = \WPHR\HR_MANAGER\Integration::wphr_init();
    }
    
    /**
     * Initialize WordPress action hooks
     *
     * @since 1.0.0
     * @since 1.2.0 Remove `wphr_setup_database` hook from `init` action
     *
     * @return void
     */
    private function wphr_init_actions()
    {
        // Localize our plugin
        add_action( 'init', array( $this, 'wphr_localization_setup' ) );
        $plugin = plugin_basename( __FILE__ );
        add_filter( "plugin_action_links_{$plugin}", array( $this, 'plugin_add_settings_link' ) );
        // initialize emailer class
        add_action( 'wphr_loaded', array( $this->container['emailer'], 'init_emails' ) );
        // initialize integration class
        add_action( 'wphr_loaded', array( $this->container['integration'], 'init_integrations' ) );
    }
    
    public function plugin_add_settings_link( $links )
    {
        $settings_link = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GLKGN964GRZJW" target="_blank">' . __( 'Donate' ) . '</a>';
        array_push( $links, $settings_link );
        return $links;
    }
    
    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function wphr_localization_setup()
    {
        load_plugin_textdomain( 'wphr', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages/' );
        global  $current_user ;
        $oldCapArray = array(
            'erp_list_employee',
            'erp_view_employee',
            'erp_edit_employee',
            'erp_view_jobinfo',
            'erp_leave_create_request',
            'erp_list_employee',
            'erp_create_employee',
            'erp_view_employee',
            'erp_edit_employee',
            'erp_delete_employee',
            'erp_create_review',
            'erp_delete_review',
            'erp_manage_review',
            'erp_manage_announcement',
            'erp_manage_jobinfo',
            'erp_view_jobinfo',
            'erp_manage_department',
            'erp_manage_designation',
            'erp_leave_create_request',
            'erp_leave_manage',
            'erp_manage_hr_settings'
        );
        foreach ( $current_user->allcaps as $key => $cap ) {
            
            if ( in_array( $key, $oldCapArray ) ) {
                $newKey = substr_replace(
                    $key,
                    'wphr_',
                    0,
                    4
                );
                $current_user->allcaps[$newKey] = $current_user->allcaps[$key];
                unset( $current_user->allcaps[$key] );
            }
        
        }
    }
    
    /**
     * Setup database related tasks
     *
     * @return void
     */
    public function wphr_setup_database()
    {
        global  $wpdb ;
        $wpdb->wphr_peoplemeta = $wpdb->prefix . 'wphr_peoplemeta';
    }
    
    /**
     * Load the current wphr module
     *
     * We don't load every module at once, just load
     * what is necessary
     *
     * @return void
     */
    public function wphr_load_module()
    {
        $modules = $this->modules->get_modules();
        if ( !$modules ) {
            return;
        }
        foreach ( $modules as $key => $module ) {
            if ( !$this->modules->is_module_active( $key ) ) {
                continue;
            }
            if ( isset( $module['callback'] ) && class_exists( $module['callback'] ) ) {
                new $module['callback']( $this );
            }
        }
    }

}
// clsWP_HR
/**
 * Init the wphr plugin
 *
 * @return clsWP_HR the plugin object
 */
function wphr()
{
    return clsWP_HR::wphr_init();
}

// kick it off
wphr();
