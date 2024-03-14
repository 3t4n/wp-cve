<?php
/*
Plugin Name: Flip Cards Module For Divi
Plugin URI:  http://www.learnhowwp.com/divi-flipbox-plugin
Description: This plugin adds a Flipbox Modules in the Divi Builder which allows you to create flip cards on your website easily.
Version:     0.9.4.1
Author:      learnhowwp.com
Author URI:  http://www.learnhowwp.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: lwp-divi-flipbox
Domain Path: /languages

Divi Flipbox is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Divi Flipbox is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Divi Flipbox. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/


if ( ! function_exists( 'lwp_flipbox_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function lwp_flipbox_initialize_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/DiviFlipbox.php';
}
add_action( 'divi_extensions_init', 'lwp_flipbox_initialize_extension' );
endif;

if ( ! function_exists( 'lwp_flip_cards_activation_time' ) ):
function lwp_flip_cards_activation_time(){

    $get_activation_time = strtotime("now");
    add_option('lwp_flip_cards_activation_time', $get_activation_time );

}
register_activation_hook( __FILE__, 'lwp_flip_cards_activation_time' );
endif;

if ( ! function_exists( 'lwp_flip_cards_check_installation_time' ) ):
function lwp_flip_cards_check_installation_time() {   
    
	$install_date = get_option( 'lwp_flip_cards_activation_time' );
	$spare_me = get_option( 'lwp_flip_cards_spare_me' );
	$past_date = strtotime( '-7 days' );
 
	if ( $past_date >= $install_date && $spare_me==false) {
 
		add_action( 'admin_notices', 'lwp_flip_cards_rating_admin_notice' );
	}

}
add_action( 'admin_init', 'lwp_flip_cards_check_installation_time' );
endif;

if ( ! function_exists( 'lwp_flip_cards_rating_admin_notice' ) ):
/*
Display Admin Notice, asking for a review
*/
function lwp_flip_cards_rating_admin_notice() {
 
        $dont_disturb = esc_url( get_admin_url() . '?lwp_flip_cards_spare_me=1' );
        $dont_show = esc_url( get_admin_url() . '?lwp_flip_cards_spare_me=1' );
        $plugin_info = get_plugin_data( __FILE__ , true, true );       
        $reviewurl = esc_url( 'https://wordpress.org/support/plugin/flip-cards-module-divi/reviews/?filter=5' );
     
        printf(__('<div class="wrap notice notice-info">
						<div style="margin:10px 0px;">
							Hello! Seems like you are using <strong> %s </strong> plugin to build your Divi website - Thanks a lot! Could you please do us a BIG favor and give it a 5-star rating on WordPress? This would boost our motivation and help other users make a comfortable decision while choosing the plugin.
						</div>	
						<div class="button-group" style="margin:10px 0px;">
							<a href="%s" class="button button-primary" target="_blank" style="margin-right:10px;">Ok,you deserve it</a>
							<span class="dashicons dashicons-smiley"></span><a href="%s" class="button button-link" style="margin-right:10px; margin-left:3px;">I already did</a>
							<a href="%s" class="button button-link"> Don\'t show this again.</a>							
						</div>
					</div>', 'lwp-divi-flipbox'), $plugin_info['Name'], $reviewurl, $dont_disturb,$dont_show );

}
endif;

if ( ! function_exists( 'lwp_flip_cards_spare_me' ) ):
function lwp_flip_cards_spare_me(){ 

    if( isset( $_GET['lwp_flip_cards_spare_me'] ) && !empty( $_GET['lwp_flip_cards_spare_me'] ) ){

        $lwp_flip_cards_spare_me = $_GET['lwp_flip_cards_spare_me'];

        if( $lwp_flip_cards_spare_me == 1 ){
            add_option( 'lwp_flip_cards_spare_me' , TRUE );
        }

    }

}
add_action( 'admin_init', 'lwp_flip_cards_spare_me', 5 );
endif;

if ( ! function_exists( 'lwp_flip_cards_add_action_links' ) ):

	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'lwp_flip_cards_add_action_links' );
	 
	function lwp_flip_cards_add_action_links ( $actions ) {
		$mylinks = array(
			'<a href="https://wordpress.org/support/plugin/flip-cards-module-divi/reviews/?filter=5" target="_blank">'.esc_html__( 'Rate Plugin', 'lwp-divi-flipbox' ).'</a>',
			'<a href="https://www.learnhowwp.com/divi-plugins/" target="_blank">'.esc_html__( 'More Divi Plugins', 'lwp-divi-flipbox' ).'</a>',
		);
		$actions = array_merge( $actions, $mylinks );
		return $actions;
	}
	
endif;

if ( ! function_exists( 'lwp_flip_cards_plugin_row_meta' ) ):

	add_filter( 'plugin_row_meta', 'lwp_flip_cards_plugin_row_meta', 10, 2 );
	
	function lwp_flip_cards_plugin_row_meta( $links, $file ) {
	
		if ( plugin_basename( __FILE__ ) == $file ) {
			$new_links = array(
				'<a href="https://www.learnhowwp.com/add-flip-cards-divi/" target="_blank">'.esc_html__( 'Getting Started Guide', 'lwp-divi-flipbox' ).'</a>'
				);
			
			$links = array_merge( $links, $new_links );
		}
		
		return $links;
	}
	
endif;


if ( ! function_exists( 'lwp_flip_cards_add_icons' ) ):

add_filter( 'et_global_assets_list', 'lwp_flip_cards_add_icons', 10 );

function lwp_flip_cards_add_icons( $assets ) {
    if ( isset( $assets['et_icons_all'] ) && isset( $assets['et_icons_fa'] ) ) {
        return $assets;
    }

    $assets_prefix = et_get_dynamic_assets_path();

    $assets['et_icons_all'] = array(
        'css' => "{$assets_prefix}/css/icons_all.css",
    );

    $assets['et_icons_fa'] = array(
        'css' => "{$assets_prefix}/css/icons_fa_all.css",
    );

    return $assets;
}

endif;

