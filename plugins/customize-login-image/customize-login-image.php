<?php
/*
 Plugin Name: Customize Login Image
 Plugin URI: http://apasionados.es/#utm_source=wpadmin&utm_medium=plugin&utm_campaign=wpcustomizeloginimageplugin 
 Description: This plugin allows you to customize the image and the appearance of the WordPress Login Screen.
 Version: 3.5.3
 Author: Apasionados, Apasionados del Marketing
 Author URI: http://apasionados.es

 Release notes: 2.0 release.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function apa_cli_logo_title() {
	if ( get_option( 'apa_cli_logo_url' ) != '' ) {
		return sprintf ( __( 'Go to %1$s' ), esc_html( get_option ( 'apa_cli_logo_url' ) ) );
	}
}
function apa_cli_logo_url($url) {
	if ( get_option( 'apa_cli_logo_url' ) != '' ) {
		return esc_html( get_option( 'apa_cli_logo_url' ) );
	} else {
		return esc_html( get_bloginfo( 'url' ) );
	}
}
function apa_cli_logo_file() {
	$bgimage= get_option( 'apa_cli_logo_file' );
	if (! $bgimage ) {
		$upload_dir = wp_upload_dir();
		$customize_login_image = $upload_dir['basedir'] . '/customize-login-image.png';
		if (@file_exists($customize_login_image) && is_readable($customize_login_image)) {
			$bgimage= $upload_dir['baseurl'] . '/customize-login-image.png';
		}
	}
	if ( $bgimage ) {
		echo '<style>#login h1 a { background-image: url("' . esc_html($bgimage) . '"); background-size: auto; width: auto; margin: 0; }</style>' . "\n";
	}
}
function apa_cli_login_background_color() {
	if ( get_option( 'apa_cli_login_background_color' ) != '' ) {
		echo '<style>body { background-color: ' . esc_html( get_option( 'apa_cli_login_background_color' ) ) . '!important; } </style>';
	}
}

function apa_cli_plugin_action_links( $links, $file ) {
	if ( $file == plugin_basename( dirname(__FILE__).'/customize-login-image.php' ) ) {
		$links[] = '<a href="' . admin_url( 'options-general.php?page=customize-login-image/customize-login-image-options.php' ) . '">'.__( 'Settings' ).'</a>';
	}
	return $links;
}

function apa_cli_ad_login_footer() {
	$server_ip_address = (!empty($_SERVER[ 'SERVER_ADDR' ]) ? $_SERVER[ 'SERVER_ADDR' ] : "");
	if ($server_ip_address == "") { // Added for IP Address in IIS
		$server_ip_address = (!empty($_SERVER[ 'LOCAL_ADDR' ]) ? $_SERVER[ 'LOCAL_ADDR' ] : "");
	}
	if ( ( get_option( 'apa_cli_show_server_ip' ) === 'show') && ( get_option( 'apa_cli_show_server_hostname' ) === 'show') )  {
		echo ('<p style="width: 320px; margin:auto; padding: 20px 0 20px 0; -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.13); box-shadow: 0 1px 3px rgba(0,0,0,.13); text-align: center; color:#008EC2">' . esc_html__( 'SERVER IP:', 'customize-login-image' ) . ' <strong>' . esc_html( $server_ip_address ) . '</strong><br />' . esc_html__( 'HOST NAME:', 'customize-login-image' ) . ' <strong>' . esc_html( gethostname() ) . '</strong></p>');
	} elseif ( get_option( 'apa_cli_show_server_ip' ) === 'show' ) {
		echo ('<p style="width: 320px; margin:auto; padding: 20px 0 20px 0; -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.13); box-shadow: 0 1px 3px rgba(0,0,0,.13); text-align: center; color:#008EC2">' . esc_html__( 'SERVER IP:', 'customize-login-image' ) . ' <strong>' . esc_html( $server_ip_address ) . '</strong></p>');	
	} elseif ( get_option( 'apa_cli_show_server_hostname' ) === 'show' ) {
		echo ('<p style="width: 320px; margin:auto; padding: 20px 0 20px 0; -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.13); box-shadow: 0 1px 3px rgba(0,0,0,.13); text-align: center; color:#008EC2">' . esc_html__( 'HOST NAME:', 'customize-login-image' ) . ' <strong>' . esc_html( gethostname() ) . '</strong></p>');	
	}
}

add_action( 'login_head', 'apa_cli_load_language' );
add_filter( 'login_headertext', 'apa_cli_logo_title' );
add_filter( 'login_headerurl', 'apa_cli_logo_url' );
add_action( 'login_head', 'apa_cli_logo_file' );
add_action( 'login_head', 'apa_cli_login_background_color' );
add_filter( 'plugin_action_links', 'apa_cli_plugin_action_links', 10, 2);

if ( ( get_option( 'apa_cli_show_server_ip' ) === 'show') || ( get_option( 'apa_cli_show_server_hostname' ) === 'show') )  {
	add_action( 'login_footer', 'apa_cli_ad_login_footer');
}

require_once( 'customize-login-image-options.php' );
?>