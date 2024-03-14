<?php
/**
 * Plugin Name: Carousel Upsells and Related Product for Woocommerce
 * Plugin URI: https://wordpress.org/plugins/carousel-upsells-and-related-product-for-woocommerce/
 * Text Domain: carousel-upsells-and-related-product-for-woocommerce
 * Domain Path: /languages
 * Description: The plugin replaces the standard related and upsells products on carousel using a script glide.js that does not depend on the jquery, which much faster than its analogues. Just activate the plugin!
 * Version: 0.4.6
 * Author: Dan Zakirov
 * Author URI: https://profiles.wordpress.org/alexodiy/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * WC requires at least: 3.3.0
 * WC tested up to: 4.4
 *
 *     Copyright Dan Zakirov and Igor Ovs
 *
 *     This file is part of Carousel Upsells and Related Product for Woocommerce,
 *     a plugin for WordPress.
 *
 *     Carousel Upsells and Related Product for Woocommerce is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 3 of the License, or (at your option)
 *     any later version.
 *
 *     Carousel Upsells and Related Product for Woocommerce is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Carousel Upsells and Related Product for Woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'init', 'ffxf_woo_glide_load_textdomain' );


/**
 * Load plugin textdomain.
 */
function ffxf_woo_glide_load_textdomain() {
 	load_plugin_textdomain( 'carousel-upsells-and-related-product-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Activate plugin and save option
 */
register_activation_hook( __FILE__, 'ffxf_my_plugin_activate' );

function ffxf_my_plugin_activate() {

	add_option( 'glideffxf_data_install_related_and_upsells', date("Y-m-d", strtotime("+2 days")),'','no' );

	add_option( 'glideffxf_related_no_varusel','no','','no' );
	add_option( 'glideffxf_related_no_upsells','no','','no'  );

    add_option( 'glideffxf_related_title', __( 'Related products', 'carousel-upsells-and-related-product-for-woocommerce' ),'','no'  );
    add_option( 'glideffxf_related_autoplay', 'yes','','no'  );
    add_option( 'glideffxf_related_hover_stop','yes','','no'  );
    add_option( 'glideffxf_related_interval', '3000','','no'  );
    add_option( 'glideffxf_related_quantity', '12','','no'  );
    add_option( 'glideffxf_related_visible', '4','','no'  );
    add_option( 'glideffxf_related_lm', '4','','no'  );
    add_option( 'glideffxf_related_td', '3','','no'  );
    add_option( 'glideffxf_related_md', '2','','no'  );


    add_option( 'glideffxf_upsells_title', __( 'You may also like…', 'carousel-upsells-and-related-product-for-woocommerce' ),'','no'  );
    add_option( 'glideffxf_upsells_autoplay', 'yes','','no'  );
    add_option( 'glideffxf_upsells_hover_stop','yes','','no'  );
    add_option( 'glideffxf_upsells_interval', '3000','','no'  );
    add_option( 'glideffxf_upsells_quantity', '12','','no'  );
    add_option( 'glideffxf_upsells_visible', '4','','no'  );
    add_option( 'glideffxf_upsells_lm', '4','','no'  );
    add_option( 'glideffxf_upsells_td', '3','','no'  );
    add_option( 'glideffxf_upsells_md', '2','','no'  );

}


add_action( 'upgrader_process_complete', 'ffxf_completed_related_and_upsells', 10, 2 );

add_action( 'admin_notices', 'ffxf_update_related_and_upsells' );

/**
 * This function runs when WordPress completes its upgrade process
 * It iterates through each plugin updated to see if ours is included
 *
 * @param array $upgrader_object
 * @param array $options
 */
function ffxf_completed_related_and_upsells( $upgrader_object, $options ) {

	// The path to our plugin's main file
	$our_plugin = plugin_basename( __FILE__ );

	// If an update has taken place and the updated type is plugins and the plugins element exists
	if( $options['action'] === 'update' && $options['type'] === 'plugin' && isset( $options['plugins'] ) ) {

		// Iterate through the plugins being updated and check if ours is there
		foreach( $options['plugins'] as $plugin ) {

			if( $plugin == $our_plugin ) {
				// Set a transient to record that our plugin has just been updated
				set_transient( 'wp_upe_updated', 1 );
			}
		}
	}
}

/**
 * Show a notice to anyone who has just updated this plugin
 * This notice shouldn't display to anyone who has just installed the plugin for the first time
 */
function ffxf_update_related_and_upsells() {

	// Check the transient to see if we've just updated the plugin
	if( get_transient( 'wp_upe_updated' ) ) {

		echo '<div class="notice notice-success"><p>' . __( 'Thanks for the update! Do not forget to update the plugin in time in order to get a better plugin <b>"Carousel Upsells and Related Product for Woocommerce"</b> without errors and with new functionality.', 'carousel-upsells-and-related-product-for-woocommerce' ) . '</p></div>';

			$glideffxf_data_install_related_and_upsells = get_option( 'glideffxf_data_install_related_and_upsells' );

			if ( $glideffxf_data_install_related_and_upsells === null || $glideffxf_data_install_related_and_upsells === false ){
				update_option( 'glideffxf_data_install_related_and_upsells', date("Y-m-d", strtotime("+2 days")) );
			}

		delete_transient( 'wp_upe_updated' );
	}
}




// Регистрация стилей
function ffxf_registering_style_glide(){
	wp_register_style('ffxf_glide-core', plugin_dir_url( __FILE__ ) . 'assets/css/glide.core.min.css', array(), '0.4.6');
    wp_register_style('ffxf_glide-theme', plugin_dir_url( __FILE__ ) . 'assets/css/glide.theme.min.css', array(), '0.4.6');
    
}
add_action( 'wp_enqueue_scripts', 'ffxf_registering_style_glide' );

// Регистрация скриптов
function ffxf_registering_script_glide(){
    wp_register_script('ffxf_glide', plugin_dir_url( __FILE__ ) . 'assets/js/glide.min.js', array(), '0.4.6', true);
}

add_action( 'wp_enqueue_scripts', 'ffxf_registering_script_glide' );



function ffxf_related_wc_template($template, $template_name, $template_path) {
    if ($template_name == 'single-product/related.php' ) {
        $template = plugin_dir_path( __FILE__ ) . 'includes/related.php';
    }

    return $template;
}

if ( get_option( 'glideffxf_related_no_varusel') == 'no'  ){
	add_filter('woocommerce_locate_template', 'ffxf_related_wc_template', 20, 3);
}



function ffxf_up_sells_wc_template($template, $template_name, $template_path) {
    if ($template_name == 'single-product/up-sells.php') {
        $template = plugin_dir_path( __FILE__ ) . 'includes/up-sells.php';
    }

    return $template;
}

if ( get_option( 'glideffxf_related_no_upsells') == 'no'  ){
	add_filter('woocommerce_locate_template', 'ffxf_up_sells_wc_template', 20, 3);
}

add_filter( 'woocommerce_output_related_products_args', 'ffxf_related_products_args', 20 );
function ffxf_related_products_args( $args ) {

    $args['posts_per_page'] = get_option( 'glideffxf_related_quantity' );
    $args['columns'] = get_option( 'glideffxf_related_visible' );
    return $args;
}

/**
 * Implement the Custom Header feature.
 */
require plugin_dir_path( __FILE__ ) . 'includes/option_plugin_related.php';
require plugin_dir_path( __FILE__ ) . 'includes/option_plugin_upsells.php';




// Register a script on a specific page
function ffxf_registering_script_glide_admin(){

    if ( isset( $_GET['section'] ) && $_GET['section'] === 'glideffxf_related' || isset( $_GET['section'] ) && $_GET['section'] === 'glideffxf_upsells' ) {

	    wp_enqueue_style('ffxf-glide-core', plugin_dir_url( __FILE__ ) . 'assets/css/glide.core.min.css', array(), '0.4.6');
	    wp_enqueue_style('ffxf-glide-theme', plugin_dir_url( __FILE__ ) . 'assets/css/glide.theme.min.css', array(), '0.4.6');

	    wp_enqueue_script('ffxf-glide', plugin_dir_url( __FILE__ ) . 'assets/js/glide.min.js', array(), '0.4.6', true);

	    wp_enqueue_style( 'ffxf-settings-related', plugins_url( '/assets/css/ffxf_settings_related.css', __FILE__ ), array(), '0.4.6' );

	    wp_enqueue_script( 'ffxf-plugin-upsells-admin', plugin_dir_url( __FILE__ ) . 'assets/js/ffxf_plugin_upsells_admin.js', array(), '0.4.6', true);
	    wp_localize_script(
		    'ffxf-plugin-upsells-admin',
		    'ffxf_settings_locale',
		    array(
			    'ffxf_img' => plugins_url( '/assets/img/', __FILE__ ), // ffxf_settings_locale.ffxf_img
                'ffxf_Central_mode' => 'Central mode', // ffxf_settings_locale.ffxf_Central_mode
			    'ffxf_Mobile_notification' => 'Mobile notification', // ffxf_settings_locale.ffxf_Mobile_notification
			    'ffxf_Center_mode_in_mobile' => 'Center mode in mobile', // ffxf_settings_locale.ffxf_Center_mode_in_mobile
                'ffxf_Center_Mode_DEMO' => 'Center Mode DEMO', // ffxf_settings_locale.ffxf_Center_Mode_DEMO
			    'ffxf_Demo_anim' => 'Demo animation', // ffxf_settings_locale.ffxf_Demo_anim
			    'ffxf_Demo_gap' => 'Size of the gap DEMO' // ffxf_settings_locale.ffxf_Demo_gap
		    )
	    );


    }

}

add_action( 'admin_enqueue_scripts', 'ffxf_registering_script_glide_admin' );


/**
 * Add Settings link to plugins.
 *
 * @param mixed $links return.
 * @param mixed $file plugin basename.
 *
 * @return $links
 */
function ffxf_woo_glide_add_settings_link( $links, $file ) {

	$this_plugin = plugin_basename( __FILE__ );
	if ( $file === $this_plugin ) {
        $site_url = home_url();
		$settings_link = '<a href="'.$site_url.'/wp-admin/admin.php?page=wc-settings&tab=products&section=glideffxf_related">' . __( 'Setting Related Carousel', 'carousel-upsells-and-related-product-for-woocommerce' ) . '</a> | <a href="'.$site_url.'/wp-admin/admin.php?page=wc-settings&tab=products&section=glideffxf_upsells">' . __( 'Setting Upsells Carousel', 'carousel-upsells-and-related-product-for-woocommerce' ) . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}

add_filter( 'plugin_action_links', 'ffxf_woo_glide_add_settings_link', 10, 2 );


function ffxf_registering_script_relat() {
    $site_url = home_url();
	if ( get_current_screen()->parent_base !== 'edit' && get_current_screen()->id !== 'product' ) {return;}

	wp_enqueue_style( 'ffxf_related_and_upsale_sing_admin', plugins_url( '/assets/css/related_and_upsale_sing_admin.css', __FILE__ ), array(), '0.4.6' );
	wp_enqueue_style( 'ffxf_tooltip', plugins_url( '/assets/css/ffxf_tooltip.css', __FILE__ ), array(), '0.4.6' );

	wp_enqueue_script( 'ffxf_settings_relat', plugins_url( '/assets/js/related_and_upsale_sing_admin.js', __FILE__ ), array('jquery'), '0.4.6', true );
	wp_localize_script(
		'ffxf_settings_relat',
		'ffxf_settings_locale',
		array(
			'ffxf_related' => __( 'Setting Related Carousel', 'carousel-upsells-and-related-product-for-woocommerce' ), // ffxf_settings_locale.ffxf_related
			'ffxf_upsale' => __( 'Setting Upsells Carousel', 'carousel-upsells-and-related-product-for-woocommerce' ), // ffxf_settings_locale.ffxf_upsale
			'ffxf_related_tooltip' => __( 'Set up your related product carousel here', 'carousel-upsells-and-related-product-for-woocommerce' ), // ffxf_settings_locale.ffxf_related_tooltip
            'ffxf_upsale_tooltip' => __( 'Set up your related upsells carousel here', 'carousel-upsells-and-related-product-for-woocommerce' ), // ffxf_settings_locale.ffxf_upsale_tooltip
            'ffxf_link_site' => $site_url,
		)
	);

}

add_action( 'admin_enqueue_scripts', 'ffxf_registering_script_relat' );


function ffxf_glideffxf_related() {

if ( isset( $_GET['section'] ) && $_GET['section'] === 'glideffxf_related' || isset( $_GET['section'] ) && $_GET['section'] === 'glideffxf_upsells'  ){

    add_thickbox();

	if ( isset( $_GET['section'] ) && $_GET['section'] === 'glideffxf_related' ){
        if ( get_option( 'glideffxf_releted_navigation' ) ){
	        $glideffxf_ar_left = plugins_url( 'assets/img/' . get_option( 'glideffxf_releted_navigation' ) . 'left.svg' , __FILE__ );
	        $glideffxf_ar_right = plugins_url( 'assets/img/' . get_option( 'glideffxf_releted_navigation') . 'right.svg' , __FILE__ );
        }else{
	        $glideffxf_ar_left = plugins_url( 'assets/img/one_left.svg' , __FILE__ );
	        $glideffxf_ar_right = plugins_url( 'assets/img/one_right.svg' , __FILE__ );
        }

		$glideffxf_releted_picker = get_option( 'glideffxf_releted_picker' );
    }

	if ( isset( $_GET['section'] ) && $_GET['section'] === 'glideffxf_upsells' ){
        if ( get_option( 'glideffxf_upsells_navigation' ) ){
	        $glideffxf_ar_left = plugins_url( 'assets/img/' . get_option( 'glideffxf_upsells_navigation' ) . 'left.svg' , __FILE__ );
	        $glideffxf_ar_right = plugins_url( 'assets/img/' . get_option( 'glideffxf_upsells_navigation') . 'right.svg' , __FILE__ );
        }else{
	        $glideffxf_ar_left = plugins_url( 'assets/img/one_left.svg' , __FILE__ );
	        $glideffxf_ar_right = plugins_url( 'assets/img/one_right.svg' , __FILE__ );
        }

		$glideffxf_releted_picker = get_option( 'glideffxf_upsells_picker' );
    }

    ?>

    <div id="ffxf_center_mode" style="display:none;">
        <div class="block_distance">
            <p><?php echo __( 'When you enable this option, you can set the left and right indentation value in pixels.', 'carousel-upsells-and-related-product-for-woocommerce' ) ?></p>
            <p><img src="<?php echo plugins_url( 'assets/img/center_mod.png', __FILE__ ); ?>" alt=""></p>
            <p><?php echo __( 'The central mode includes carousel trimming left and right.', 'carousel-upsells-and-related-product-for-woocommerce' ) ?></p>
            <p><img class="shadow_img" src="<?php echo plugins_url( 'assets/img/sc_1.png', __FILE__ ); ?>" alt=""></p>
            <p><?php echo __( 'Do not forget to configure the carousel on the mobile version of the site in the general settings.', 'carousel-upsells-and-related-product-for-woocommerce' ) ?></p>
            <p><img class="shadow_img" src="<?php echo plugins_url( 'assets/img/sc_2.png', __FILE__ ); ?>" alt=""></p>
            <p><?php echo __( 'Thanks for attention!', 'carousel-upsells-and-related-product-for-woocommerce' ) ?></p>
        </div>
    </div>

    <!-- DEMO Mobile mobile notification -->

    <div id="ffxf_mobile_notification" style="display:none;">
            <p><img src="<?php echo plugins_url( 'assets/img/iPhone_6.png', __FILE__ ); ?>" alt=""></p>
    </div>

    <div id="ffxf_mobile_center_mode" style="display:none;">

        <p><img src="<?php echo plugins_url( 'assets/img/CM_in_mobile.jpg', __FILE__ ); ?>" alt=""></p>

    </div>



    <!-- DEMO setting Center Mode -->

    <div id="ffxf_center_mode_DEMO_modal" style="display:none;">
        <div class="block_distance">
            <p><?php echo __( 'You can crop the carousel left or right.<br>Do not forget that this is a demo mode and the data entered in these fields is not saved anywhere.', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></p>


            <div class="container_demo_slide">
                <div id="options_center_mode" class="glide">
                    <div class="slider__track glide__track" data-glide-el="track">

                        <ul class="slider__slides glide__slides">
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                        </ul>

                    </div>

                    <div class="glide__arrows" data-glide-el="controls">
                        <div style="background-color:<?php echo $glideffxf_releted_picker; ?>;" class="glide__arrow glide__arrow--left" data-glide-dir="<"><img src="<?php echo $glideffxf_ar_left; ?>" alt=""></div>
                        <div style="background-color:<?php echo $glideffxf_releted_picker; ?>;" class="glide__arrow glide__arrow--right" data-glide-dir=">"><img src="<?php echo $glideffxf_ar_right; ?>" alt=""></div>
                    </div>

                </div>

            </div>

            <p class="paragraph">

	            <?php echo __( 'Peek', 'carousel-upsells-and-related-product-for-woocommerce' ); ?> <input class="input input--inline" id="options-peek-before" type="number" value="0" min="0" max="150" step="10"> <?php echo __( 'pixels on the left and', 'carousel-upsells-and-related-product-for-woocommerce' ); ?> <input class="input input--inline" id="options-peek-after" type="number" value="0" min="0" max="150" step="10"> <?php echo __( 'on right side', 'carousel-upsells-and-related-product-for-woocommerce' ); ?>

            </p>


        </div>
    </div>

    <!-- DEMO animation -->

    <div id="ffxf_demo_anim" style="display:none;">
        <div class="block_distance">
            <p><?php echo __( 'See in action how this animation works, and leave the one you like.', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></p>


            <div class="container_demo_slide">
                <div id="options-animation-timing-func" class="glide">
                    <div class="slider__track glide__track" data-glide-el="track">

                        <ul class="slider__slides glide__slides">
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                        </ul>

                    </div>

                    <div class="glide__arrows" data-glide-el="controls">
                        <div style="background-color:<?php echo $glideffxf_releted_picker; ?>;" class="glide__arrow glide__arrow--left" data-glide-dir="<"><img src="<?php echo $glideffxf_ar_left; ?>" alt=""></div>
                        <div style="background-color:<?php echo $glideffxf_releted_picker; ?>;" class="glide__arrow glide__arrow--right" data-glide-dir=">"><img src="<?php echo $glideffxf_ar_right; ?>" alt=""></div>
                    </div>

                </div>

            </div>

                <p class="paragraph">
	                <?php echo __( 'Ease moving animation with', 'carousel-upsells-and-related-product-for-woocommerce' ); ?> <select id="options-animation-timing-func-select" class="input input--inline">
                        <option value="cubic-bezier(0.165, 0.840, 0.440, 1.000)" selected="">default</option>
                        <option value="linear" >linear</option>
                        <option value="ease">ease</option>
                        <option value="ease-in">ease-in</option>
                        <option value="ease-out">ease-out</option>
                        <option value="ease-in-out">ease-in-out</option>
                        <option value="cubic-bezier(0.680, -0.550, 0.265, 1.550)">bounce</option>
                    </select> <?php echo __( 'timing function', 'carousel-upsells-and-related-product-for-woocommerce' ); ?>

                </p>


        </div>
    </div>

    <!-- DEMO animation Duration -->

    <div id="ffxf_demo_Duration" style="display:none;">
        <div class="block_distance">
            <p><?php echo __( 'See in action how this animation works, and leave the one you like.', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></p>


            <div class="container_demo_slide">
                <div id="options_animationDuration" class="glide">
                    <div class="slider__track glide__track" data-glide-el="track">

                        <ul class="slider__slides glide__slides">
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                        </ul>

                    </div>

                    <div class="glide__arrows" data-glide-el="controls">
                        <div style="background-color:<?php echo $glideffxf_releted_picker; ?>;" class="glide__arrow glide__arrow--left" data-glide-dir="<"><img src="<?php echo $glideffxf_ar_left; ?>" alt=""></div>
                        <div style="background-color:<?php echo $glideffxf_releted_picker; ?>;" class="glide__arrow glide__arrow--right" data-glide-dir=">"><img src="<?php echo $glideffxf_ar_right; ?>" alt=""></div>
                    </div>

                </div>

            </div>

            <p class="paragraph">

	            <?php echo __( 'Duration of the animation have be', 'carousel-upsells-and-related-product-for-woocommerce' ); ?> <input class="input input--inline" id="options-animation-duration-input" type="number" value="1000" min="100" max="4000" step="100"> <?php echo __( 'milliseconds', 'carousel-upsells-and-related-product-for-woocommerce' ); ?>

            </p>


        </div>
    </div>

    <!-- DEMO Gap -->

    <div id="ffxf_demo_Gap" style="display:none;">
        <div class="block_distance">
            <p><?php echo __( 'A gap size is added between the slides. This window works as an example.', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></p>


            <div class="container_demo_slide">
                <div id="options_Gap" class="glide">
                    <div class="slider__track glide__track" data-glide-el="track">

                        <ul class="slider__slides glide__slides">
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                            <li class="slider__frame glide__slide"><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt=""><br><span></span><span></span><span></span><span></span><span></span><span></span></li>
                        </ul>

                    </div>

                    <div class="glide__arrows" data-glide-el="controls">
                        <div style="background-color:<?php echo $glideffxf_releted_picker; ?>;" class="glide__arrow glide__arrow--left" data-glide-dir="<"><img src="<?php echo $glideffxf_ar_left; ?>" alt=""></div>
                        <div style="background-color:<?php echo $glideffxf_releted_picker; ?>;" class="glide__arrow glide__arrow--right" data-glide-dir=">"><img src="<?php echo $glideffxf_ar_right; ?>" alt=""></div>
                    </div>

                </div>

            </div>

            <p class="paragraph">

	            <?php echo __( 'Gaps between slides should be', 'carousel-upsells-and-related-product-for-woocommerce' ); ?> <input class="input input--inline" id="options-gap-input" type="number" value="10" min="0" max="100" step="5"> <?php echo __( 'pixels wide', 'carousel-upsells-and-related-product-for-woocommerce' ); ?>

            </p>


        </div>
    </div>


	<?php if (isset( $_GET['section'] ) && $_GET['section'] === 'glideffxf_related' ){?>
		<h1><?php echo __( 'Settings Related Products Carousel', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></h1>
	<?php }else{ ?>
		<h1><?php echo __( 'Settings Upsells Products Carousel', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></h1>
	<?php } ?>

	<div class="wrapper_setting_ffxf">
	<div class="block_left">

		<div id="postbox-container-1" class="postbox-container postbox">
			<div class="meta-box-sortables">
				<div id="woocommerce_awooc_call_to_rate" class="postbox__ffxf">

					<div class="inside inside_icons">
						<span class="dashicons dashicons-businessperson"></span>
						<h2><span><?php echo __( 'Message from the author', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></span></h2>
						<p><?php echo __( 'As we have already said, this plugin and, in particular, similar products may not be compatible with your theme. This is especially true of premium topics where custom output of similar products is implemented. If something does not work, we will be very grateful if you report this to the user <a href="https://wordpress.org/support/plugin/carousel-upsells-and-related-product-for-woocommerce/#new-post" target="_blank">this topic forum</a>', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></p>
						<p><?php echo __( 'We are actively working on the plugin and soon new parameters will appear that will allow you to comfortably customize the carousel for your site design.', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></p>
						<p><?php echo __( 'Some functions will be available only in the professional version of the plugin. The most active installers and testers are waiting gifts – pro version the plugin "Carousel Upsells and Related Product for Woocommerce".', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></p>
					</div>
				</div>
			</div>
		</div>


		<div id="postbox-container-4" class="postbox-container postbox">
			<div class="meta-box-sortables">
				<div id="woocommerce_awooc_call_to_rate" class="postbox__ffxf">
					<div class="inside">
						<span class="dashicons dashicons-chart-pie"></span>
						<h2><span><?php echo __( 'Recommended Plugins', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></span></h2>
						<p><span><?php echo __( 'Let me recommend some plugins that can provide the greatest conversion for your store.', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></p>
						<hr>
						<div class="recomendated_plugin_block">
							<div><img src="https://ps.w.org/easy-woocommerce-auto-sku-generator/assets/icon-256x256.png" alt=""></div>
							<div>
								<a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=easy-woocommerce-auto-sku-generator&TB_iframe=true&width=772&height=550" class="thickbox open-plugin-details-modal" aria-label="Easy Auto SKU Generator for WooCommerce" data-title="Easy Auto SKU Generator for WooCommerce"><h4><?php echo __( 'Easy Auto SKU Generator for WooCommerce', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></h4></a>
							</div>
						</div>
						<hr>
						<div class="recomendated_plugin_block">
							<div><img src="https://ps.w.org/art-woocommerce-order-one-click/assets/icon.svg" alt=""></div>
							<div>
								<a href="/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=art-woocommerce-order-one-click&amp;TB_iframe=true&amp;width=772&amp;height=550" class="thickbox open-plugin-details-modal" aria-label="Art WooCommerce Order One Click" data-title="Art WooCommerce Order One Click"><h4><?php echo __( 'Art WooCommerce Order One Click', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></h4></a>
							</div>
						</div>
						<hr>
						<div class="recomendated_plugin_block">
							<div><img src="https://ps.w.org/luckywp-scripts-control/assets/icon-128x128.png" alt=""></div>
							<div>
								<a href="/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=luckywp-scripts-control&amp;TB_iframe=true&amp;width=772&amp;height=550" class="thickbox open-plugin-details-modal" aria-label="LuckyWP Scripts Control" data-title="LuckyWP Scripts Control"><h4><?php echo __( 'LuckyWP Scripts Control', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></h4></a>
							</div>
						</div>
						<hr>
						<div class="recomendated_plugin_block">
							<div><img src="https://ps.w.org/art-woocommerce-custom-sale/assets/icon.svg" alt=""></div>
							<div>
								<a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=art-woocommerce-custom-sale&TB_iframe=true&width=772&height=550" class="thickbox open-plugin-details-modal" aria-label="Art Woocommerce Custom Sale" data-title="Art Woocommerce Custom Sale"><h4><?php echo __( 'Art Woocommerce Custom Sale', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></h4></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>




		<div id="postbox-container-3" class="postbox-container postbox">
			<div class="meta-box-sortables">
				<div id="woocommerce_awooc_call_to_rate" class="postbox__ffxf">
					<div class="inside">
						<span class="dashicons dashicons-buddicons-topics"></span>
						<h2><span><?php echo __( 'Donate to development', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></span></h2>

						<p><?php echo __( 'You can make a donation to make the plugin Carousel Upsells and Related Product for Woocommerce even better!', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></p>

						<p><?php echo __( 'Choose how much you want to donate?', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></p>

						<div class="slidecontainer">
							<input type="range" min="4" max="100" value="50" class="slider" id="myRange">
						</div>

						<p class="center"><a id="donate" target="_blank" href="https://www.paypal.com/cgi-bin/webscr?&cmd=_xclick&business=studia55x5@yandex.ru&currency_code=USD&amount=4&item_name=On%20coffee%20for%20the%20developer"><?php echo __( 'To donate', 'carousel-upsells-and-related-product-for-woocommerce' ); ?> $<span id="demo">51</span></a> </p>
						<p class="center"><a href="https://wordpress.org/support/plugin/carousel-upsells-and-related-product-for-woocommerce/reviews/#new-post" target="_blank"><?php echo __( 'Leave feedback on the official repository', 'carousel-upsells-and-related-product-for-woocommerce' ); ?></a></p>
					</div>
				</div>
			</div>
		</div>




	</div>

	<div><span class="dashicons dashicons-admin-generic my_generic_two my_generic"></span>
<?php }
}
add_action( 'woocommerce_settings_products', 'ffxf_glideffxf_related' );

/**
 * Register script notice
 *
 * @return void
 */
function ffxf_registering_notice_related_and_upsells_script() {
	wp_register_script( 'glideffxf-rate-related-and-upsells', plugins_url( '/assets/js/ffxf_rate_related_and_upsells.js', __FILE__ ), array('jquery'), '0.4.6', true );
	wp_localize_script(
		'glideffxf-rate-related-and-upsells',
		'ffxf_sp',
		array(
			'ffxf_sp' => __( 'Thank you very much!<br>Remember to make updates when it is available.', 'carousel-upsells-and-related-product-for-woocommerce' ) // ffxf_sp.ffxf_sp
		)
	);
}

add_action( 'admin_enqueue_scripts', 'ffxf_registering_notice_related_and_upsells_script' );

/**
 * Notifications to the user about the plugin revocation
 */
require_once ( plugin_dir_path(__FILE__) . 'includes/ffxf_related_and_upsells_rate.php' );

/**
 * Filter upsell fix theme
 */
if ( get_option( 'glideffxf_upsells_filter_fix' ) == 'yes' ){

	add_filter( 'woocommerce_upsell_display_args', 'ffxf_upsell_fix_theme_filter', 99 );

	function ffxf_upsell_fix_theme_filter( $args ) {

		$args['posts_per_page'] = get_option( 'glideffxf_upsells_quantity' );
		$args['columns'] = 3;
		return $args;
	}

}

/**
 * Filter related fix theme
 */
if ( get_option( 'glideffxf_releted_filter_fix' ) == 'yes' ){

	add_filter( 'woocommerce_output_related_products_args', 'ffxf_related_fix_theme_filter', 99 );

	function ffxf_related_fix_theme_filter( $args ) {
		$args['posts_per_page'] = get_option( 'glideffxf_related_quantity' );
		$args['columns'] = 3;
		return $args;
	}

}
if ( get_option( 'glideffxf_releted_function_fix' ) == 'yes' ){

	function remove_woo_relate_products(){
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
	}
	add_action('init', 'remove_woo_relate_products', 10);

	function woocommerce_output_related_products_main() {

		$args = array(
			'posts_per_page' => get_option( 'glideffxf_related_quantity' ),
			'columns'        => 3,
			'orderby'        => 'rand', // @codingStandardsIgnoreLine.
		);

		woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args, 99 ) );
	}

	add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products_main', 20);

}






