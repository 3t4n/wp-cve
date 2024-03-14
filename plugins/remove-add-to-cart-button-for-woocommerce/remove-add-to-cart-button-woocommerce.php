<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpartisan.net/
 * @since             1.0.0
 * @package           Remove_Add_To_Cart_Button_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name: 	  Remove Add to Cart Button for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/remove-add-to-cart-button-for-woocommerce
 * Description:       Remove Add to Cart Button for WooCommerce Plugin gives you a really easy interface to hide/remove Product Add to Cart button for all users or only for the visitors. It has a option to hide product price also.
 * Version:           1.0.6
 * Author:            wpArtisan
 * Author URI:        https://wpartisan.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       remove-add-to-cart-button-woocommerce
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( function_exists( 'ratcbw_fs' ) ) {
    ratcbw_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'ratcbw_fs' ) ) {
        // Create a helper function for easy SDK access.
        function ratcbw_fs()
        {
            global  $ratcbw_fs ;
            
            if ( !isset( $ratcbw_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/includes/freemius/start.php';
                $ratcbw_fs = fs_dynamic_init( array(
                    'id'              => '6639',
                    'slug'            => 'remove-add-to-cart-button-for-woocommerce',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_c0490aef508bc341e97b0c1e0448f',
                    'is_premium'      => false,
                    'premium_suffix'  => 'Premium',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'has_affiliation' => 'selected',
                    'menu'            => array(
                    'first-path' => 'plugins.php',
                    'support'    => false,
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $ratcbw_fs;
        }
        
        // Init Freemius.
        ratcbw_fs();
        // Signal that SDK was initiated.
        do_action( 'ratcbw_fs_loaded' );
    }
    
    /**
     * Currently plugin version.
     * Start at version 1.0.0 and use SemVer - https://semver.org
     * Rename this for your plugin and update it as you release new versions.
     */
    define( 'REMOVE_ADD_TO_CART_BUTTON_WOOCOMMERCE_VERSION', '1.0.2' );
    if ( !function_exists( 'ratcw_remove_add_to_cart_button_admin_notice' ) ) {
        /**
         *  Show an admin notice if WooCommerce is not activated
         *
         */
        function ratcw_remove_add_to_cart_button_admin_notice()
        {
            ?>
			<div class="error">
				<p><?php 
            esc_html_e( 'Remove Add to Cart Button for WooCommerce Plugin is enabled but not effective. In order to work it requires WooCommerce.', 'remove-add-to-cart-button-woocommerce' );
            ?></p>
			</div>
			<?php 
        }
    
    }
    add_action( 'plugins_loaded', 'ratcw_remove_add_to_cart_button_install', 12 );
    /**
     * Add plugin page settings link.
     * @since    1.0.0
     */
    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ratcw_add_plugin_page_settings_link' );
    function ratcw_add_plugin_page_settings_link( $links )
    {
        $links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=remove-add-to-cart-button-settings' ) . '">' . esc_html__( 'Settings', 'remove-add-to-cart-button-woocommerce' ) . '</a>';
        $links[] = '<a class="ratcw-plugins-gopro" href="' . ratcbw_fs()->get_upgrade_url() . '">' . esc_html__( 'Go Pro', 'remove-add-to-cart-button-woocommerce' ) . '</a>';
        return $links;
    }
    
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-remove-add-to-cart-button-woocommerce-activator.php
     */
    function ratcw_activate_plugin()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-remove-add-to-cart-button-woocommerce-activator.php';
        Remove_Add_To_Cart_Button_Woocommerce_Activator::ratcw_activate();
    }
    
    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-remove-add-to-cart-button-woocommerce-deactivator.php
     */
    function ratcw_deactivate_plugin()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-remove-add-to-cart-button-woocommerce-deactivator.php';
        Remove_Add_To_Cart_Button_Woocommerce_Deactivator::ratcw_deactivate();
    }
    
    register_activation_hook( __FILE__, 'ratcw_activate_plugin' );
    register_deactivation_hook( __FILE__, 'ratcw_deactivate_plugin' );
    /**
     * Add admin notice.
     * @since    1.0.0
     */
    add_action( 'admin_notices', 'ratcw_admin_notice' );
    function ratcw_admin_notice()
    {
        global  $current_user ;
        
        if ( isset( $_SERVER['HTTPS'] ) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }
        
        $current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parse_url = parse_url( $current_url );
        
        if ( isset( $parse_url['query'] ) && !empty($parse_url['query']) ) {
            $current_url = $current_url . '&ratcw_igne_noti=1';
        } else {
            $current_url = $current_url . '?ratcw_igne_noti=1';
        }
        
        $user_id = $current_user->ID;
        if ( !get_user_meta( $user_id, 'ratcw_igne_noti' ) ) {
            
            if ( ratcbw_fs()->is_not_paying() ) {
                echo  '<div class="updated"><p>' ;
                echo  '<h3>' . esc_html__( 'Awesome Premium Features in Remove Add to Cart Button for WooCommerce Plugin', 'remove-add-to-cart-button-woocommerce' ) . '</h3>' ;
                echo  '<ul>' ;
                echo  '<li><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Remove Add to Cart Button Based on User Roles', 'remove-add-to-cart-button-woocommerce' ) . '</li>' ;
                echo  '<li><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Remove Add to Cart Button Based on Countries', 'remove-add-to-cart-button-woocommerce' ) . '</li>' ;
                echo  '<li><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Set Category Wise Remove Add to Cart Button Conditions', 'remove-add-to-cart-button-woocommerce' ) . '</li>' ;
                echo  '<li><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Priority email support', 'remove-add-to-cart-button-woocommerce' ) . '</li>' ;
                echo  '</ul>' ;
                echo  '<a href="' . ratcbw_fs()->get_upgrade_url() . '" class="upgradebtn">' . esc_html__( 'Upgrade Now!', 'remove-add-to-cart-button-woocommerce' ) . '</a>&nbsp;&nbsp;' ;
                echo  '<a href="' . $current_url . '" class="hidebtn">' . esc_html__( 'Hide Notice', 'remove-add-to-cart-button-woocommerce' ) . '</a>' ;
                echo  "</p></div>" ;
            }
        
        }
    }
    
    /**
     * Ignore admin notice.
     * @since    1.0.0
     */
    add_action( 'admin_init', 'ratcw_ignore_notice' );
    function ratcw_ignore_notice()
    {
        global  $current_user ;
        $user_id = $current_user->ID;
        if ( isset( $_GET['ratcw_igne_noti'] ) && 1 == $_GET['ratcw_igne_noti'] ) {
            add_user_meta(
                $user_id,
                'ratcw_igne_noti',
                'true',
                true
            );
        }
    }
    
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-remove-add-to-cart-button-woocommerce.php';
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function ratcw_run_remove_add_to_cart_button_woocommerce()
    {
        $plugin = new Remove_Add_To_Cart_Button_Woocommerce();
        $plugin->ratcw_run();
    }
    
    function ratcw_remove_add_to_cart_button_install()
    {
        
        if ( !function_exists( 'WC' ) ) {
            add_action( 'admin_notices', 'ratcw_remove_add_to_cart_button_admin_notice' );
        } else {
            ratcw_run_remove_add_to_cart_button_woocommerce();
        }
    
    }

}
