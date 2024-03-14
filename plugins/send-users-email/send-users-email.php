<?php

/**
 * Plugin Name:       Send Users Email
 * Plugin URI:        https://sendusersemail.com/
 * Description:       Easily send emails to your users. Select individual users or role to send email.
 * Version:           1.5.1
 * Author:            Suman Bhattarai
 * Author URI:        https://sumanbhattarai.com.np
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       send-users-email
 * Domain Path:       /languages
 *
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( function_exists( 'sue_fs' ) ) {
    sue_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'sue_fs' ) ) {
        // Create a helper function for easy SDK access.
        function sue_fs()
        {
            global  $sue_fs ;
            
            if ( !isset( $sue_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $sue_fs = fs_dynamic_init( array(
                    'id'             => '11436',
                    'slug'           => 'send-users-email',
                    'premium_slug'   => 'send-users-email-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_29ad05bfbd6008f9c20e155704ec0',
                    'is_premium'     => false,
                    'premium_suffix' => 'PRO',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 14,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'first-path' => 'plugins.php',
                    'support'    => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $sue_fs;
        }
        
        // Init Freemius.
        sue_fs();
        // Signal that SDK was initiated.
        do_action( 'sue_fs_loaded' );
    }
    
    /**
     * Currently plugin version.
     */
    define( 'SEND_USERS_EMAIL_VERSION', '1.5.1' );
    /**
     * Currently plugin base path.
     */
    define( 'SEND_USERS_EMAIL_PLUGIN_BASE_PATH', dirname( __FILE__ ) );
    /**
     * Currently plugin base path.
     */
    define( 'SEND_USERS_EMAIL_PLUGIN_BASE_URL', plugins_url( plugin_basename( SEND_USERS_EMAIL_PLUGIN_BASE_PATH ) ) );
    /**
     * Email send capability name
     */
    define( 'SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY', 'sue_send_email_capability' );
    /**
     * The code that runs during plugin activation.
     */
    function activate_send_users_email()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-send-users-email-activator.php';
        Send_Users_Email_Activator::activate();
    }
    
    /**
     * The code that runs during plugin deactivation.
     */
    function deactivate_send_users_email()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-send-users-email-deactivator.php';
        Send_Users_Email_Deactivator::deactivate();
    }
    
    register_activation_hook( __FILE__, 'activate_send_users_email' );
    register_deactivation_hook( __FILE__, 'deactivate_send_users_email' );
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-send-users-email.php';
    /**
     * Begins execution of the plugin.
     */
    function run_send_users_email()
    {
        $plugin = new Send_Users_Email();
        $plugin->run();
    }
    
    run_send_users_email();
    /**
     * Perform Uninstall cleanup and actions
     */
    sue_fs()->add_action( 'after_uninstall', 'sue_fs_uninstall_cleanup_action' );
    function sue_fs_uninstall_cleanup_action()
    {
        /**
         * Perform cleanup
         */
        // Delete option created by send users email
        delete_option( 'sue_send_users_email' );
        delete_option( 'sue_db_version' );
        // Remove capability to send email for all roles
        $all_roles = sue_get_roles();
        // Remove capability from all roles
        foreach ( $all_roles as $role_slug ) {
            $role = get_role( $role_slug );
            if ( $role ) {
                $role->remove_cap( SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY );
            }
        }
        // Delete log folder and its files
        $dirDetails = wp_upload_dir();
        $uploads_dir = trailingslashit( $dirDetails['basedir'] );
        if ( file_exists( $uploads_dir . DIRECTORY_SEPARATOR . 'send-users-email' ) ) {
            unlink( $uploads_dir . DIRECTORY_SEPARATOR . 'send-users-email' );
        }
    }

}
