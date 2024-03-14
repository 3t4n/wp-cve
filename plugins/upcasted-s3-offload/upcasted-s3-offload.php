<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Upcasted S3 Offload - AWS S3, Digital Ocean Spaces, Backblaze, Minio and more
 * Plugin URI:        https://upcasted.com/upcasted-s3-offload
 * Description:       Seamless sync between your WordPress Media Library and AWS S3 now in a top notch WordPress plugin with easy licensing and no limitations.
 * Version:           3.0.2
 * Author:            Upcasted
 * Author URI:        https://upcasted.com
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       upcasted-s3-offload
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( function_exists( 'uso_fs' ) ) {
    uso_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'uso_fs' ) ) {
        // Create a helper function for easy SDK access.
        function uso_fs()
        {
            global  $uso_fs ;
            
            if ( !isset( $uso_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $uso_fs = fs_dynamic_init( array(
                    'id'              => '6128',
                    'slug'            => 'upcasted-s3-offload',
                    'premium_slug'    => 'upcasted-s3-offload-pro',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_1fb61de9679c1069993d1ba9f2166',
                    'is_premium'      => false,
                    'premium_suffix'  => 'Pro',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'has_affiliation' => 'selected',
                    'menu'            => array(
                    'slug'    => 'upcasted-s3-offload-panel',
                    'support' => false,
                    'parent'  => array(
                    'slug' => 'upload.php',
                ),
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $uso_fs;
        }
        
        // Init Freemius.
        uso_fs();
        // Signal that SDK was initiated.
        do_action( 'uso_fs_loaded' );
    }
    
    if ( !class_exists( '\\Aws\\S3\\S3Client' ) ) {
        // Require AWS Autoloader file.
        require_once dirname( __FILE__ ) . '/vendor/autoload.php';
    }
    define( 'UPCASTED_S3_OFFLOAD_VERSION', '3.0.2' );
    if ( !defined( 'UPCASTED_S3_OFFLOAD_SETTINGS' ) ) {
        define( 'UPCASTED_S3_OFFLOAD_SETTINGS', 'upcasted_s3_offload_settings' );
    }
    if ( !defined( 'UPCASTED_S3_OFFLOAD_ACCESS_KEY_ID' ) ) {
        define( 'UPCASTED_S3_OFFLOAD_ACCESS_KEY_ID', 'upcasted_s3_offload_access_key_id' );
    }
    if ( !defined( 'UPCASTED_S3_OFFLOAD_SECRET_ACCESS_KEY' ) ) {
        define( 'UPCASTED_S3_OFFLOAD_SECRET_ACCESS_KEY', 'upcasted_s3_offload_secret_access_key' );
    }
    if ( !defined( 'UPCASTED_OFFLOAD_REGION' ) ) {
        define( 'UPCASTED_OFFLOAD_REGION', 'upcasted_offload_region' );
    }
    if ( !defined( 'UPCASTED_CUSTOM_ENDPOINT' ) ) {
        define( 'UPCASTED_CUSTOM_ENDPOINT', 'upcasted_custom_endpoint' );
    }
    if ( !defined( 'UPCASTED_CUSTOM_BATCH_SIZE' ) ) {
        define( 'UPCASTED_CUSTOM_BATCH_SIZE', 'upcasted_custom_batch_size' );
    }
    if ( !defined( 'UPCASTED_REMOVE_LOCAL_FILE' ) ) {
        define( 'UPCASTED_REMOVE_LOCAL_FILE', 'upcasted_remove_local_file' );
    }
    if ( !defined( 'UPCASTED_S3_OFFLOAD_INCLUDED_FILETYPES' ) ) {
        define( 'UPCASTED_S3_OFFLOAD_INCLUDED_FILETYPES', 'upcasted_s3_offload_included_filetypes' );
    }
    if ( !defined( 'UPCASTED_REMOVE_CLOUD_FILE' ) ) {
        define( 'UPCASTED_REMOVE_CLOUD_FILE', 'upcasted_remove_cloud_file' );
    }
    if ( !defined( 'UPCASTED_OFFLOAD_REGION' ) ) {
        define( 'UPCASTED_OFFLOAD_REGION', '' );
    }
    if ( !defined( 'UPCASTED_S3_OFFLOAD_BUCKET' ) ) {
        define( 'UPCASTED_S3_OFFLOAD_BUCKET', 'upcasted_s3_offload_bucket' );
    }
    if ( !defined( 'UPCASTED_S3_OFFLOAD_CUSTOM_DOMAIN' ) ) {
        define( 'UPCASTED_S3_OFFLOAD_CUSTOM_DOMAIN', '' );
    }
    if ( !defined( 'UPCASTED_S3_OFFLOAD_PROTOCOL' ) ) {
        define( 'UPCASTED_S3_OFFLOAD_PROTOCOL', 'upcasted_s3_offload_protocol' );
    }
    if ( !defined( 'UPCASTED_CRON_LOCAL_TO_S3' ) ) {
        define( 'UPCASTED_CRON_LOCAL_TO_S3', 'upcasted_cron_local_to_s3' );
    }
    if ( !defined( 'UPCASTED_CRON_S3_TO_LOCAL' ) ) {
        define( 'UPCASTED_CRON_S3_TO_LOCAL', 'upcasted_cron_s3_to_local' );
    }
    /**
     * This code shows a settings button on the plugins page to find the settings easier.
     */
    function upcasted_s3_offload_settings_link( $links )
    {
        $settings_link = '<a href="upload.php?page=upcasted-s3-offload-panel">Settings</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }
    
    $plugin = plugin_basename( __FILE__ );
    add_filter( "plugin_action_links_{$plugin}", 'upcasted_s3_offload_settings_link' );
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-upcasted-offload.php';
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function run_upcasted_s3_offload()
    {
        $plugin = new Upcasted_Offload();
        $plugin->run();
    }
    
    run_upcasted_s3_offload();
}
