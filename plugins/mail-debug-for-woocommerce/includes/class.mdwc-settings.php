<?php

if ( ! class_exists( 'MDWC_Settings' ) ) {
	class MDWC_Settings {
		private static $_instance;

		public static function get_instance() {
			return ! is_null( self::$_instance ) ? self::$_instance : self::$_instance = new self();
		}

		private function __construct() {
			add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			add_action( 'admin_menu', array( $this, 'add_enabled_bubble' ), 20 );
			add_action( 'init', array( $this, 'move_menu' ), 20 );


			add_action( 'admin_enqueue_scripts', array( $this, 'init_wp_with_tabs' ), 11 );
		}

		public function move_menu() {
			if ( isset( $_REQUEST['wcmd_show_in_wp_menu'] ) ) {
				$show_in_wp_menu = 'yes' === $_REQUEST['wcmd_show_in_wp_menu'];

				update_option( 'mdwc_show_in_wp_menu', ! ! $show_in_wp_menu ? 'yes' : 'no' );


				wp_safe_redirect( $this->get_settings_menu_url() );
				exit;

			}
		}

		public function get_settings_menu_url() {
			$base = 'edit.php?post_type=mail-debug';
			if ( ! $this->show_in_wp_menu() ) {
				$base = 'options-general.php';
			}

			return add_query_arg( array( 'page' => 'mdwc_settings_panel' ), admin_url( $base ) );
		}

		public function add_settings_menu() {
			if ( mdwc_settings()->show_in_wp_menu() ) {
				add_submenu_page( 'edit.php?post_type=mail-debug',
								  __( 'Settings', 'mail-debug-for-woocommerce' ),
								  __( 'Settings', 'mail-debug-for-woocommerce' ),
								  'manage_options',
								  'mdwc_settings_panel',
								  array( $this, 'print_settings' )
				);
			} else {
				add_submenu_page( 'options-general.php',
								  'Mail Debug',
								  'Mail Debug',
								  'manage_options',
								  'mdwc_settings_panel',
								  array( $this, 'print_settings' )
				);
			}
		}

		public function print_settings() {
			require MDWC_VIEWS_PATH . 'settings.php';
		}

		public function register_settings() {
			register_setting( 'mdwc-settings', 'mdwc_debug_enabled' );
			register_setting( 'mdwc-settings', 'mdwc_all_emails_to' );
		}

		public function show_in_wp_menu() {
			return 'yes' === get_option( 'mdwc_show_in_wp_menu', 'yes' );
		}

		public function add_enabled_bubble() {
			if ( ! mdwc_is_debug_enabled() ) {
				global $menu;
				$mail_debug_menu = 'edit.php?post_type=mail-debug';

				$not_enabled = __( 'Debug not enabled', 'mail-debug-for-woocommerce' );
				$bubble      = "<span style='background: #ea5f0f;color:#fff;border-radius: 50%;display: inline-block;font-size: 11px;line-height: 17px;font-weight: 800;padding: 0 7px;margin-left:  5px;' title='{$not_enabled}'>!</span>";

				foreach ( $menu as $i => $item ) {
					if ( $mail_debug_menu === $item[2] ) {
						$menu[ $i ][0] .= $bubble;
						break;
					}
				}
			}
		}

		public function init_wp_with_tabs() {
			global $pagenow, $post_type;

			if ( in_array( $pagenow, array( 'post.php', 'post-new.php', 'edit.php' ), true )
				 && 'mail-debug' === $post_type ) {

				add_action( 'all_admin_notices', array( $this, 'print_panel_tabs_in_wp_pages' ) );
				add_action( 'admin_footer', array( $this, 'print_panel_tabs_in_wp_pages_end' ) );
			}
		}

		public function print_panel_tabs_in_wp_pages() {
			require MDWC_VIEWS_PATH . 'settings-start.php';
		}


		public function print_panel_tabs_in_wp_pages_end() {
			require MDWC_VIEWS_PATH . 'settings-end.php';
		}
	}
}

if ( ! function_exists( 'mdwc_settings' ) ) {
	function mdwc_settings() {
		return MDWC_Settings::get_instance();
	}
}