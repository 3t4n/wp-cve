<?php

/**
 * Smart Variations Images & Swatches for WooCommerce
 *
 * By default WooCommerce will only swap the main variation image when you select a product variation, not the gallery images below it.
 *
 * This extension allows visitors to your online store to be able to swap different gallery images when they select a product variation.
 * Adding this feature will let visitors see different images of a product variation all in the same color and style.
 *
 * This extension will allow the use of multiple images per variation, and simplifies it! How?
 * Instead of upload one image per variation, upload all the variation images to the product gallery and for each image choose the corresponding slug of the variation on the dropdown.
 * As quick and simple as that!
 *
 * @link              https://www.rosendo.pt
 * @since             5.2.14
 * @package           Smart_Variations_Images
 *
 * @wordpress-plugin
 * Plugin Name:       Smart Variations Images & Swatches for WooCommerce
 * Plugin URI:        https://www.smart-variations.com/
 * Description:       This is a WooCommerce extension plugin, that allows the user to add any number of images to the product images gallery and be used as variable product variations images in a very simple and quick way, without having to insert images p/variation.
 * Version:           5.2.14
 * WC requires at least: 5.0
 * WC tested up to:	  8.6.0
 * Author:            David Rosendo
 * Author URI:        https://www.rosendo.pt
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc_svi
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * Currently plugin version.
 */
define( 'SMART_VARIATIONS_IMAGES_VERSION', '5.2.14' );
define( 'WCSVFS_VERSION', '1.0' );
define( 'SMART_SVI_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'SMART_SCRIPT_DEBUG', ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) );
define( 'SMART_SVI_OPTIONS_CONTROL', '1' );
define( 'SMART_SVI_PROVS', '<span class="wpsfsvi-label label-warning">PRO VERSION</span>' );

if ( function_exists( 'svi_fs' ) ) {
    svi_fs()->set_basename( false, __FILE__ );
    return;
} else {
    
    if ( !function_exists( 'svi_fs' ) ) {
        // Create a helper function for easy SDK access.
        function svi_fs()
        {
            global  $svi_fs ;
            
            if ( !isset( $svi_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/includes/library/freemius/start.php';
                $svi_fs = fs_dynamic_init( array(
                    'id'             => '2228',
                    'slug'           => 'smart-variations-images',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_6a5f1fc0c8ab537a0b07683099ada',
                    'is_premium'     => false,
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug'       => 'woosvi-options-settings',
                    'first-path' => 'admin.php?page=woosvi-options-settings',
                    'support'    => false,
                    'network'    => true,
                    'parent'     => array(
                    'slug' => 'woocommerce',
                ),
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $svi_fs;
        }
        
        // Init Freemius.
        svi_fs();
        // Signal that SDK was initiated.
        do_action( 'svi_fs_loaded' );
    }
    
    /**
     * Some custom hooks for freemius display
     */
    require plugin_dir_path( __FILE__ ) . 'includes/freemius_conditions.php';
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-smart-variations-images-activator.php
     */
    function activate_smart_variations_images()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-smart-variations-images-activator.php';
        Smart_Variations_Images_Activator::activate();
    }
    
    register_activation_hook( __FILE__, 'activate_smart_variations_images' );
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-smart-variations-images.php';
    require plugin_dir_path( __FILE__ ) . 'includes/class-wcsvfs.php';
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function run_smart_variations_images()
    {
        $plugin = new Smart_Variations_Images();
        $plugin->run();
        $wcsvfs = new Wcsvfs( $plugin->options );
        $wcsvfs->run();
    }
    
    if ( !function_exists( 'WC_SVINST' ) ) {
        /**
         * Main instance of plugin
         *
         * @return Smart_Variations_Images
         */
        function WC_SVINST()
        {
            return Smart_Variations_Images::instance();
        }
    
    }
    if ( !function_exists( 'WC_SVFS' ) ) {
        /**
         * Main instance of plugin
         *
         * @return Wcsvfs
         */
        function WC_SVFS()
        {
            return Wcsvfs::instance();
        }
    
    }
    if ( !function_exists( 'fs_dd' ) ) {
        function fs_dd( $args )
        {
            
            if ( current_user_can( 'administrator' ) ) {
                echo  "<pre>" . print_r( $args, true ) . "</pre>" ;
                die;
            }
        
        }
    
    }
    if ( !function_exists( 'fs_ddd' ) ) {
        function fs_ddd( $args )
        {
            if ( current_user_can( 'administrator' ) ) {
                echo  "<pre>" . print_r( $args, true ) . "</pre>" ;
            }
        }
    
    }
    add_action( 'plugins_loaded', 'run_smart_variations_images', 99 );
}

add_action( 'before_woocommerce_init', function () {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );