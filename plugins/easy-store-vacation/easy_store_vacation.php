<?php
/**
 * Plugin Name:       Easy Store Vacation
 * @link              https://samuilmarinov.co.uk
 * @since             1.1.6
 * @package           Easy_store_vacation
 * Plugin URI:        samuilmarinov.co.uk
 * Description:       This plugin puts your store into Vacation Mode and shows a Notice to the user.
 * Version:           1.1.6
 * Author:            Samuil Marinov
 * Author URI:        https://samuilmarinov.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy_store_vacation
 * Domain Path:       /languages
 */
if (!defined('WPINC')){ die; }
/**
 * Currently plugin version.
 * Start at version 1.1.6 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('EASY_STORE_VACATION_VERSION', '1.1.6');
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-easy_store_vacation-activator.php
 */
function activate_easy_store_vacation()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-easy_store_vacation-activator.php';
    Easy_store_vacation_Activator::activate();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-easy_store_vacation-deactivator.php
 */
function deactivate_easy_store_vacation()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-easy_store_vacation-deactivator.php';
    Easy_store_vacation_Deactivator::deactivate();
}
register_activation_hook(__FILE__, 'activate_easy_store_vacation');
register_deactivation_hook(__FILE__, 'deactivate_easy_store_vacation');
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-easy_store_vacation.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.6
 */
function run_easy_store_vacation()
{
    $plugin = new Easy_store_vacation();
    //PLUGIN LINK
    function easy_store_vacation_action_links( $links ) {
        $links = array_merge( array(
            '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=products&section=vacation_text_section' ) ) . '">' . __( 'Settings', 'easy-store-vacation' ) . '</a>',
            '<img style="position: absolute; left: 15.4rem; margin-top: -1.8rem;" width=128 height=128 src="/wp-content/plugins/easy-store-vacation/admin/icon-128x128.jpg">'
        ), $links );

        return $links;
    }

    add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'easy_store_vacation_action_links' );
    //PLUGIN LINK
    // Trigger Holiday Mode
    add_action('init', 'samsvacation_woocommerce_holiday_mode');
    // Disable Cart, Checkout, Add Cart
    function samsvacation_woocommerce_holiday_mode()
    {
        $date_vacation_on = get_option('vacation_text_section_on');
        $date_vacation_on_notice = get_option('vacation_text_section_on_notice');
        $paymentDate = date('Y-m-d');
        $paymentDate = date('Y-m-d', strtotime($paymentDate));
        //echo $paymentDate; // echos today!
        $date_vacation_from = get_option('vacation_text_section');
        $date_vacation_to = get_option('vacation_text_section_to');
        $contractDateBegin = date('Y-m-d', strtotime($date_vacation_from));
        $contractDateEnd = date('Y-m-d', strtotime($date_vacation_to));
        
        if ($date_vacation_on_notice === 'yes' && $date_vacation_on != 'yes' ){ 
            
            if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
            add_action('woocommerce_before_main_content', 'samsvacation_wc_shop_disabled', 5);
            add_action('woocommerce_before_cart', 'samsvacation_wc_shop_disabled', 5);
            add_action('woocommerce_before_checkout_form', 'samsvacation_wc_shop_disabled', 5);
            }
            
        }
        
        if ($date_vacation_on === 'yes')
        {

            if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd))
            {
                $theme = wp_get_theme(); // gets the current theme
                if ( 'Storefront' == $theme->name || 'Storefront' == $theme->parent_theme ) {
                    add_filter( 'woocommerce_is_purchasable', '__return_false'); // DISABLING PURCHASE FUNCTIONALITY AND REMOVING ADD TO CART BUTTON FROM NORMAL 
                }    
                remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
                remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
                remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
                if ($date_vacation_on_notice === 'yes'){
                add_action('woocommerce_before_main_content', 'samsvacation_wc_shop_disabled', 5);
                add_action('woocommerce_before_cart', 'samsvacation_wc_shop_disabled', 5);
                add_action('woocommerce_before_checkout_form', 'samsvacation_wc_shop_disabled', 5);
                }
            }
            else
            {
                // NADA     
            }
        }
    }
	// Show Holiday Notice
		function samsvacation_wc_shop_disabled() {
			    $notice_text = get_option( 'vacation_text_section_notice' ); 
                wc_print_notice( $notice_text, 'error');
                if ( 'Storefront' == $theme->name || 'Storefront' == $theme->parent_theme ) {
                    wc_add_notice( $notice_text, 'notice' );
                }
		} 
    add_filter('woocommerce_get_sections_products', 'vacation_add_settings_tab');
    function vacation_add_settings_tab($settings_tab)
    {
        $settings_tab['vacation_text_section'] = __('Store Vacation');
        return $settings_tab;
	}
    add_filter('woocommerce_get_settings_products', 'vacation_get_settings', 10, 2);
    function vacation_get_settings($settings, $current_section)
    {
        $custom_settings = array();
        if ('vacation_text_section' == $current_section)
        {
            $custom_settings = array(
             array(
			'name' => 'Set date range and notice below',
			'desc' => 'use the addtional options below to control the plugin behaviour',
			'type' => 'title',
			'id' => 'title_vacation'
		),
                array(
                    'name' => __('Vacation date from') ,
					'type' => 'date',
					'default' => '-',
                    'id' => 'vacation_text_section'
                ) ,
                array(
                    'name' => __('Vacation date to') ,
					'type' => 'date',
					'default' => '-',
                    'id' => 'vacation_text_section_to'
                ) ,
                array(
                    'name' => __('Notice to display') ,
                    'type' => 'textarea',
                    'id' => 'vacation_text_section_notice'
                ),
                array(
                    'name'    => 'ORDERS',
                    'label' => __('Switch Vacation Mode ON') ,
                    'desc' => '',
                    'type' => 'radio',
                    'id' => 'vacation_text_section_on',
                    'default' => 'no',
                    'options' => array(
                        'no' => __('ACCEPT ORDERS'),
                        'yes' => __('DO NOT ACCEPT ORDERS')
                     )
                            
                ),
                array(
                    'name'    => 'NOTICE',
                    'label' => __('Switch Only Notice ON') ,
                    'desc' => '',
                    'type' => 'radio',
                    'id' => 'vacation_text_section_on_notice',
                    'default' => 'no',
                    'options' => array(
                        'no' => __('DON NOT DISPLAY NOTICE'),
                        'yes' => __('DISPLAY NOTICE')
                     )
                            
                )
            );
            return $custom_settings;
        }
        else
        {
            return $settings;
        }
    }
    $plugin->run();
}
run_easy_store_vacation();