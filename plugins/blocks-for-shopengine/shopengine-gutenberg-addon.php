<?php
/**
 * Plugin Name: Blocks for ShopEngine - Woocommerce Builder
 * Plugin URI:  https://wpmet.com/plugin/shopengine
 * Description: ShopEngine Gutenberg Addon is the most-complete WooCommerce template builder for Gutenburg. It helps you build and customize the single product page, cart page, archive page, checkout page, order page, my account page, and thank-you page from scratch. It also packed with product comparison, wishlist, quick view, and variation swatches etc.
 * Version: 2.3.6
 * Author: Wpmet
 * Author URI:  https://wpmet.com
 * Text Domain: shopengine-gutenberg-addon
 * Domain Path: /languages
 * License:  GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 */

defined('ABSPATH') || exit;

	require_once __DIR__ . '/autoload.php';

	final class Shopengine_Gutenberg_Addon {

		public static function version() {
			return '2.3.6';
		}


		/**
		 * Plugin file plugins's root file.
		 *
		 * @return string
		 * @since 1.0.0
		 *
		 */
		public static function plugin_file() {
			return __FILE__;
		}


		/**
		 * Plugin url
		 *
		 * @return mixed
		 * @since 1.0.0
		 */
		public static function plugin_url() {
			return trailingslashit(plugin_dir_url(__FILE__));
		}


		/**
		 * Plugin dir
		 *
		 * @return mixed
		 * @since 1.0.0
		 */
		public static function plugin_dir() {
			return trailingslashit(plugin_dir_path(__FILE__));
		}

		public function __construct() {
			add_action('init', [$this, 'i18n']);
			add_action('plugins_loaded', [$this, 'init']);
		}

		public function i18n() {

			load_plugin_textdomain('shopengine-gutenberg-addon', false, self::plugin_dir() . 'languages/');
		}

		public function init() {

			do_action('shopengine-gutenberg-addon/before_loaded');

			if(!class_exists('ShopEngine')) {

				$this->missing_shopengine();

				return;
			}

			//Check for required shopengine version.
			if(!version_compare(\ShopEngine::version(), '2.1.1', '>=')) {

				$this->version_mismatch();

				return;
			}

			add_action('shopengine/before_loaded', function () {

				\Shopengine_Gutenberg_Addon\Plugin::instance()->init();

				do_action('shopengine-gutenberg-addon/after_loaded');
			});
		}

		public function version_mismatch() {

			\Oxaim\Libs\Notice::init();

			\Oxaim\Libs\Notice::instance('shopengine-gutenberg-addon', 'sga-version-mismatch-shopengine')
							->set_type('error')
							->set_message(sprintf(esc_html__('Shopengine Gutenberg Addon requires ShopEngine version 2.1.1 or higher. ', 'shopengine-gutenberg-addon')))
							->call();
		}

		public function missing_shopengine() {


			$btn = [
				'default_class' => 'button',
				'class'         => 'button-primary ', // button-primary button-secondary button-small button-large button-link
			];
			if(file_exists(WP_PLUGIN_DIR . '/shopengine/shopengine.php')) {
				$btn['text'] = esc_html__('Activate ShopEngine', 'shopengine-gutenberg-addon');
				$btn['url']  = wp_nonce_url(self_admin_url('plugins.php?action=activate&plugin=shopengine/shopengine.php&plugin_status=all&paged=1'), 'activate-plugin_shopengine/shopengine.php');
			} else {
				$btn['text'] = esc_html__('Install ShopEngine', 'shopengine-gutenberg-addon');
				$btn['url']  = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=shopengine'), 'install-plugin_shopengine');
			}

			\Oxaim\Libs\Notice::init();

			\Oxaim\Libs\Notice::instance('shopengine-gutenberg-addon', 'sga-missing-shopengine')
							->set_type('error')
							->set_message(sprintf(esc_html__('Shopengine Gutenberg Addon requires ShopEngine, which is currently NOT RUNNING. ', 'shopengine-gutenberg-addon')))
							->set_button($btn)
							->call();
		}
	}

	new \Shopengine_Gutenberg_Addon();

	register_activation_hook(__FILE__, 'activate_gutenova');

	register_deactivation_hook(__FILE__, 'deactivate_gutenova');

	function activate_gutenova() { }

	function deactivate_gutenova() { }

