<?php
/**
 * Plugin Name: 9MAIL - WordPress Email Templates Designer
 * Plugin URI: https://villatheme.com/extensions/9mail-wordpress-email-templates-designer/
 * Description: Make your WordPress emails become professional.
 * Version: 1.0.12
 * Author: VillaTheme
 * Author URI: https://villatheme.com
 * Text Domain: 9mail-wp-email-templates-designer
 * Domain Path: /languages
 * Copyright 2022 - 2023 VillaTheme.com. All rights reserved.
 * Requires at least: 5.0
 * Tested up to: 6.2
 * Requires PHP: 7.0
 **/

namespace EmTmplF;

use EmTmplF\Inc\Email_Builder;
use EmTmplF\Inc\Email_Samples;
use EmTmplF\Inc\Email_Trigger;
use EmTmplF\Inc\Enqueue;

defined( 'ABSPATH' ) || exit;

define( 'EMTMPL_CONST', [
	'version'     => '1.0.12',
	'plugin_name' => '9MAIL - WP Email Templates Designer',
	'slug'        => 'em-tmpl',
	'assets_slug' => 'em-tmpl-',
	'file'        => __FILE__,
	'basename'    => plugin_basename( __FILE__ ),
	'plugin_dir'  => plugin_dir_path( __FILE__ ),
	'dist_url'    => plugins_url( 'assets/dist/', __FILE__ ),
	'libs_url'    => plugins_url( 'assets/libs/', __FILE__ ),
	'img_url'     => plugins_url( 'assets/img/', __FILE__ ),
] );

require_once EMTMPL_CONST['plugin_dir'] . 'autoload.php';

if ( ! class_exists( 'WP_Email_Templates_Designer' ) ) {
	class WP_Email_Templates_Designer {
		protected $checker;

		public function __construct() {
			add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ], 20 );
			add_action( 'admin_notices', [ $this, 'plugin_require_notice' ] );
			register_activation_hook( __FILE__, array( $this, 'emtmpl_activate' ) );
		}

		public function plugins_loaded() {
			if ( class_exists( '\EmTmpl\EMTMPL_Email_Templates_Designer' ) ) {
				return;
			}

			$this->checker = new \WP_Error();
			global $wp_version;
			$php_require = '7.0';
			$wp_require  = '5.0';

			if ( version_compare( phpversion(), $php_require, '<' ) ) {
				$this->checker->add( '', sprintf( '%s %s', esc_html__( 'require PHP version at least', '9mail-wp-email-templates-designer' ), $php_require ) );
			}

			if ( version_compare( $wp_version, $wp_require, '<' ) ) {
				$this->checker->add( '', sprintf( '%s %s', esc_html__( 'require WordPress version at least', '9mail-wp-email-templates-designer' ), $wp_require ) );
			}

			if ( $this->checker->has_errors() ) {
				return;
			}

			$this->init();
		}

		public function init() {
			add_filter( 'plugin_action_links_' . EMTMPL_CONST['basename'], [ $this, 'setting_link' ] );
			$this->load_text_domain();
			$this->load_classes();
		}

		public function load_text_domain() {
			load_plugin_textdomain( '9mail-wp-email-templates-designer', false, EMTMPL_CONST['basename'] . '/languages' );
		}

		public function load_classes() {
			require_once EMTMPL_CONST['plugin_dir'] . 'inc/functions.php';
			require_once EMTMPL_CONST['plugin_dir'] . 'support/support.php';

			Enqueue::instance();
			Email_Builder::instance();
			Email_Trigger::instance();

			if ( is_admin() && ! wp_doing_ajax() ) {
				$this->support();
			}
		}

		public function plugin_require_notice() {
			if ( ! $this->checker instanceof \WP_Error || ! $this->checker->has_errors() ) {
				return;
			}

			$messages = $this->checker->get_error_messages();
			foreach ( $messages as $message ) {
				echo sprintf( "<div id='message' class='error'><p>%s %s</p></div>", esc_html( EMTMPL_CONST['plugin_name'] ), wp_kses_post( $message ) );
			}
		}

		public function support() {
			if ( class_exists( 'VillaTheme_Support' ) ) {
				new \VillaTheme_Support(
					array(
						'support'    => 'https://wordpress.org/support/plugin/',
						'docs'       => 'http://docs.villatheme.com/?item=9mail-wp-email-templates-designer',
						'review'     => 'https://wordpress.org/support/plugin/9mail-wp-email-templates-designer/reviews/?rate=5#rate-response',
						'pro_url'    => 'https://1.envato.market/kj3VaN',
						'css'        => EMTMPL_CONST['dist_url'],
						'image'      => EMTMPL_CONST['img_url'],
						'slug'       => '9mail-wp-email-templates-designer',
						'menu_slug'  => 'edit.php?post_type=wp_email_tmpl',
						'version'    => EMTMPL_CONST['version'],
						'survey_url' => 'https://script.google.com/macros/s/AKfycbxCadAI0khct5tqhGMvp1kGqVOtHH05iwOqrbPyJcjGWiQiKv-64FL7-VpWbO0bPUU7/exec'
					)
				);
			}
		}

		public function emtmpl_activate() {
			$check_exist = get_posts( [ 'post_type' => 'wp_email_tmpl', 'numberposts' => 1 ] );

			if ( empty( $check_exist ) ) {
				$default_subject = Email_Samples::default_subject();
				$templates       = Email_Samples::sample_templates();
				$site_title      = get_option( 'blogname' );

				foreach ( $templates as $key => $template ) {
					$args = [
						'post_title'  => $default_subject[ $key ] ? str_replace( '{site_title}', $site_title, $default_subject[ $key ] ) : '',
						'post_status' => 'publish',
						'post_type'   => 'wp_email_tmpl',
					];

					$post_id  = wp_insert_post( $args );
					$template = $template['basic']['data'];
					$template = str_replace( '\\', '\\\\', $template );

					update_post_meta( $post_id, 'emtmpl_settings_type', $key );
					update_post_meta( $post_id, 'emtmpl_email_structure', $template );
				}
			}
		}

		public function setting_link( $links ) {
			return array_merge(
				[
					sprintf( "<a href='%1s' >%2s</a>", esc_url( admin_url( 'edit.php?post_type=wp_email_tmpl' ) ),
						esc_html__( 'Settings', '9mail-wp-email-templates-designer' ) )
				],
				$links );
		}

	}

	new WP_Email_Templates_Designer();
}