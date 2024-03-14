<?php

/*

	Plugin Name: WIP Custom Login
	Plugin URI: https://www.themeinprogress.com
	Description: WIP Custom Login allows you to customize the login and register section of Wordpress. Thanks to this plugin, you can replace the WordPress logo, set a background image and much more.
	Version: 1.3.2
	Text Domain: wip-custom-login
	Author: ThemeinProgress
	Author URI: https://www.themeinprogress.com
	License: GPL3
	Domain Path: /languages/

	Copyright 2024  ThemeinProgress  (email : info@wpinprogress.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 3, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

require_once dirname(__FILE__) . '/core/functions.wip-custom-login.php';

if( !class_exists( 'wip_custom_login_init' ) ) {

	class wip_custom_login_init {

		/**
		* Constructor
		*/

		public function __construct(){

			add_action('plugins_loaded', array(&$this,'plugin_setup'));
			add_action('login_enqueue_scripts', array(&$this,'load_scripts') );
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_links' ), 10, 2 );

		}

		/**
		* Plugin settings link
		*/

		public function plugin_action_links( $links ) {

			$links[] = '<a href="'.esc_url( get_admin_url(null, 'admin.php?page=wip_custom_login_panel') ) .'">' . __('Settings','wip-custom-login') . '</a>';
			$links[] = '<a target="_blank" href="https://www.themeinprogress.com/c-login-free-custom-login-wordpress-plugin/?ref=2&campaign=wip-custom-login-action-link">' . esc_html__('Upgrade to PRO','wip-custom-login') . '</a>';

			return $links;

		}

		/**
		* Plugin setup
		*/

		public function plugin_setup() {

			load_plugin_textdomain( 'wip-custom-login', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
			require_once dirname(__FILE__) . '/core/includes/class-panel.php';
			require_once dirname(__FILE__) . '/core/includes/class-notice.php';
			require_once dirname(__FILE__) . '/core/includes/class-custom-login.php';

			if ( is_admin() == 1 )
				require_once dirname(__FILE__) . '/core/admin/panel.php';

		}

		/**
		* Load scripts
		*/

		public function load_scripts() {

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'wip_custom_login_custom.login', plugins_url('/assets/js/custom.login.js', __FILE__ ), array('jquery'), FALSE, TRUE );

			if ( wip_custom_login_setting('wip_custom_login_charset') ):

				$charset = implode(",", wip_custom_login_setting('wip_custom_login_charset') );

			else:

				$charset = 'latin,latin-ext';

			endif;

			$fonts_args = array(
				'family' => get_option( 'wip_custom_login_fontlist', 'Montserrat' ),
				'subset' => $charset
			);

			wp_enqueue_style( 'wip_custom_login_google_fonts', add_query_arg ( $fonts_args, "//fonts.googleapis.com/css" ), array(), null );

		}

	}

	new wip_custom_login_init();

}

?>
