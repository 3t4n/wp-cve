<?php

/**
 * Plugin Name: Master FAQ Accordion ( Former WP Awesome FAQ Plugin )
 * Plugin URI: https://jeweltheme.com/shop/wordpress-faq-plugin/
 * Description: Best Accordion Plugin. Create your FAQ (Frequently Asked Question) items on a Colorful way. A nice creation by <a href="http://www.jeweltheme.com/">Jewel Theme</a>.
 * Version: 4.1.9
 * Author: Jewel Theme
 * Author URI: https://wpadminify.com
 * Text Domain: maf
 */
$plugin_data = get_file_data( __FILE__, array(
    'Version'     => 'Version',
    'Plugin Name' => 'Plugin Name',
), false );
$plugin_name = $plugin_data['Plugin Name'];
$plugin_version = $plugin_data['Version'];
define( 'MAF', $plugin_name );
define( 'MAF_VERSION', $plugin_version );
define( 'MAF_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'MAF_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'MAF_TD', load_plugin_textdomain( 'maf' ) );
define( 'MAF_ADDON', plugin_dir_path( __FILE__ ) . 'inc/elementor/addon/' );
define( 'MAF_PRO_URL', 'https://jeweltheme.com/product/wordpress-faq-plugin/' );

if ( !function_exists( 'jltmaf_accordion' ) ) {
    // Create a helper function for easy SDK access.
    function jltmaf_accordion()
    {
        global  $jltmaf_accordion ;
        
        if ( !isset( $jltmaf_accordion ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_6343_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_6343_MULTISITE', true );
            }
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $jltmaf_accordion = fs_dynamic_init( array(
                'id'              => '6343',
                'slug'            => 'wp-awesome-faq',
                'type'            => 'plugin',
                'public_key'      => 'pk_a54451ba3b9416a4e0215b62f962a',
                'is_premium'      => false,
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'trial'           => array(
                'days'               => 14,
                'is_require_payment' => false,
            ),
                'has_affiliation' => 'selected',
                'menu'            => array(
                'slug'       => 'edit.php?post_type=faq',
                'first-path' => 'edit.php?post_type=faq&page=jltmaf_faq_settings',
            ),
                'is_live'         => true,
            ) );
        }
        
        return $jltmaf_accordion;
    }
    
    // Init Freemius.
    jltmaf_accordion();
    // Signal that SDK was initiated.
    do_action( 'jltmaf_accordion_loaded' );
}

// Include Files
include MAF_DIR . '/inc/faq-cpt.php';
include MAF_DIR . '/inc/fa-icons.php';
include MAF_DIR . '/inc/faq-assets.php';
include MAF_DIR . '/inc/faq-metabox.php';
include MAF_DIR . '/inc/faq-dependecies.php';
include MAF_DIR . '/inc/helper-functions.php';
// include( MAF_DIR . '/src/init.php');
// require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
// Admin Settings
include MAF_DIR . '/admin/class.settings-api.php';
include MAF_DIR . '/admin/colorful-faq-settings.php';
//Shortcoes
include MAF_DIR . '/inc/faq-shortcodes.php';
//Sorting
include MAF_DIR . '/lib/sorting.php';
// Load shortcode generator files
include MAF_DIR . '/lib/tinymce.button.php';