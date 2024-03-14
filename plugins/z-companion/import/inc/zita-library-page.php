<?php // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Z_Companion_Sites_Page' ) ) {

	/**
	 * Zita Admin Settings
	 */
	class Z_Companion_Sites_Page {
		static public $plugin_slug = Z_COMPANION_SLUG;

		/**
		 * Constructor
		 */
		function __construct() {

			if ( ! is_admin() ) {
				return;
			}

		add_action( 'init', __CLASS__ . '::init_admin_settings', 99 );
		}


		/**
		 * Admin settings init
		 */
		static public function init_admin_settings() {

			if ( isset( $_REQUEST['page'] ) && strpos( $_REQUEST['page'], self::$plugin_slug ) !== false ) {

				// Let extensions hook into saving.
				self::save_settings();
			}

		add_action( 'admin_menu', __CLASS__ . '::add_admin_menu', 100 );
		add_action( 'z_companion_menu_action', __CLASS__ . '::general_page' );


		}

static public function add_admin_menu() {

			$parent_page    = 'themes.php';
			$page_title     = __(Z_COMPANION_SITES_NAME,'z-companion-sites');
			$capability     = 'manage_options';
			$page_menu_slug = self::$plugin_slug;
			$page_menu_func = __CLASS__ . '::menu_callback';

			add_theme_page( $page_title, $page_title, $capability, $page_menu_slug, $page_menu_func );
		}

		/**
		 * Save All admin settings here
		 */
		static public function save_settings() {

			// Only admins can save settings.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
		}

		/**
		 * Menu callback
		 *
		 * @since 1.0.6
		 */
		static public function menu_callback() {
			?>
			<div class="z-companion-sites-menu-page-wrapper">
				<?php do_action( 'z_companion_menu_action'); ?>
			</div>
			<?php
		}

		static public function general_page() {

			if(isset($_GET['site-key'])){

			require_once Z_COMPANION_SITES_DIR . 'inc/key-api/form.php';
			
			}else {

			require_once Z_COMPANION_SITES_DIR . 'inc/admin-tmpl.php';

			}

		}
}
	new Z_Companion_Sites_Page;
}