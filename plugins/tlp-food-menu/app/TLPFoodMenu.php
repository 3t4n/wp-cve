<?php
/**
 * Main initialization class.
 *
 * @package RT_FoodMenu
 */

use RT\FoodMenu\Helpers as Helpers;
use RT\FoodMenu\Controllers as Controllers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

//require_once __DIR__ . './../vendor/autoload.php';

if ( ! class_exists( TLPFoodMenu::class ) ) {
	/**
	 * Main initialization class.
	 */
	final class TLPFoodMenu {

		use RT\FoodMenu\Traits\SingletonTrait;

		/**
		 * Post Type.
		 *
		 * @var string
		 */
		public $post_type;

		/**
		 * Shortcode Post Type.
		 *
		 * @var string
		 */
		public $shortCodePT;

		/**
		 * Taxonomies.
		 *
		 * @var array
		 */
		public $taxonomies;

		/**
		 * Options
		 *
		 * @var array
		 */
		public $options;

		/**
		 * Plugin path.
		 *
		 * @var string
		 */
		public $plugin_path;

		/**
		 * Pro path.
		 *
		 * @var string
		 */
		public $pro_path;

		/**
		 * Class init.
		 *
		 * @return void
		 */
		protected function init() {
			// Checks for PRO and checks version.
			$this->check_pro();

			// Defaults.
			$this->defaults();

			// Hooks.
			$this->init_hooks();
		}

		/**
		 * Checks for PRO and checks version
		 *
		 * @return void
		 */
		private function check_pro() {
			if ( in_array(
				'food-menu-pro/food-menu-pro.php',
				apply_filters( 'active_plugins', get_option( 'active_plugins' ) ),
				true
			) && false === \RT\FoodMenu\Helpers\Upgrade::check_plugin_version() ) {
				\add_action( 'admin_init', [ \RT\FoodMenu\Helpers\Upgrade::class, 'deactivate' ] );

				return;
			}

			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$fm_pro_path = WP_PLUGIN_DIR . '/food-menu-pro/food-menu-pro.php';

			if ( file_exists( $fm_pro_path ) ) {
				$plugin_path = get_plugin_data( $fm_pro_path );

				if ( isset( $plugin_path['Version'] ) ) {

					if ( version_compare( $plugin_path['Version'], '3', '<' ) ) {
						\add_action( 'admin_init', [ \RT\FoodMenu\Helpers\Upgrade::class, 'notice' ] );
					}
				}
			}
		}

		/**
		 * Defaults
		 *
		 * @return void
		 */
		private function defaults() {
			$this->post_type   = 'food-menu';
			$this->shortCodePT = 'fmsc';
			$this->options     = [
				'settings'          => 'tpl_food_menu_settings',
				'version'           => TLP_FOOD_MENU_VERSION,
				'title'             => esc_html__( 'Food Menu', 'tlp-food-menu' ),
				'installed_version' => 'tlp-food-menu-installed-version',
				'slug'              => 'tlp-food-menu',
				'flash'             => 'tlp-fm-flash',
			];
			$this->taxonomies  = [
				'category' => $this->post_type . '-cat',
			];
		}

		/**
		 * Init Hooks.
		 *
		 * @return void
		 */
		private function init_hooks() {
			\add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ], -1 );
			\add_action( 'init', [ $this, 'initialize' ], 0 );
		}

		/**
		 * Init Hooks.
		 *
		 * @return void
		 */
		public function initialize() {
			\do_action( 'rtfm_init' );

			$this->load_text_domain();
			Helpers\Fns::instances( $this->controllers() );
		}

		/**
		 * Load plugin text domain.
		 *
		 * @return void
		 */
		public function load_text_domain() {
			$locale = determine_locale();
			$locale = apply_filters( 'rtfm_plugin_locale', $locale );

			unload_textdomain( 'tlp-food-menu' );
			load_textdomain( 'tlp-food-menu', WP_LANG_DIR . '/tlp-food-menu/tlp-food-menu-' . $locale . '.mo' );
			load_plugin_textdomain( 'tlp-food-menu', false, TLP_FOOD_MENU_LANGUAGE_PATH );
		}

		/**
		 * Controllers.
		 *
		 * @return array
		 */
		public function controllers() {
			$controllers = [];

			if ( is_admin() ) {
				$controllers[] = Controllers\AdminController::class;
			}

			$controllers[] = Controllers\PostTypesController::class;
			$controllers[] = Controllers\ScriptsController::class;
			$controllers[] = Controllers\AjaxController::class;
			$controllers[] = Controllers\WidgetsController::class;
			$controllers[] = Controllers\FrontendController::class;
			$controllers[] = Controllers\GutenbergController::class;

			return $controllers;
		}

		/**
		 * Actions on Plugins Loaded.
		 *
		 * @return void
		 */
		public function on_plugins_loaded() {
			\do_action( 'rtfm_loaded' );
		}

		/**
		 * Plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( TLP_FOOD_MENU_PLUGIN_PATH ) );
		}

		/**
		 * PRO plugin path
		 *
		 * @return string
		 */
		public function pro_plugin_path() {
			return untrailingslashit( plugin_dir_path( str_replace( 'tlp-', '', TLP_FOOD_MENU_PLUGIN_PATH ) ) ) . '-pro';
		}

		/**
		 * Template path
		 *
		 * @return string
		 */
		public function templates_path() {
			return apply_filters( 'fmp_template_path', $this->plugin_path() . '/templates/' );
		}

		/**
		 * Plugin Template path
		 *
		 * @return string
		 */
		public function plugin_template_path() {
			return apply_filters( 'tlp_fm_template_path', $this->plugin_path() . '/templates/' );
		}

		/**
		 * PRO Template path
		 *
		 * @return string
		 */
		public function pro_templates_path() {
			return apply_filters( 'rttlp_team_pro_template_path', $this->pro_plugin_path() . '/templates/' );
		}

		/**
		 * Checks if Pro version installed
		 *
		 * @return boolean
		 */
		public function has_pro() {
			return class_exists( 'FMP' );
		}

		/**
		 * Checks if WooCommerce installed
		 *
		 * @return boolean
		 */
		public function isWcActive() {
			return class_exists( 'WooCommerce' );
		}

		/**
		 * Get ShortCode Post Type.
		 *
		 * @return string
		 */
		public function getShortCodePT() {
			return $this->shortCodePT;
		}

		/**
		 * PRO Version URL.
		 *
		 * @return string
		 */
		public function pro_version_link() {
			return esc_url( 'https://www.radiustheme.com/downloads/food-menu-pro-wordpress/' );
		}

		/**
		 * Documentation URL.
		 *
		 * @return string
		 */
		public function documentation_link() {
			return esc_url( 'https://www.radiustheme.com/docs/food-menu/' );
		}

		/**
		 * Assets URL.
		 *
		 * @return string
		 */
		public function assets_url() {
			return esc_url( TLP_FOOD_MENU_PLUGIN_URL . '/assets/' );
		}
	}

	/**
	 * Returns TLPFoodMenu.
	 *
	 * @return TLPFoodMenu
	 */
	function TLPFoodMenu() {
		return TLPFoodMenu::get_instance();
	}

	/**
	 * App Init.
	 */
	TLPFoodMenu();
}
