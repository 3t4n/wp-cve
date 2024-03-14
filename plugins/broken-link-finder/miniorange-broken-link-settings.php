<?php  //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName -- The main file name of published plugin can't be changed.
/**
 * Plugin Name: Broken Link Checker/Finder
 * Description: Simple & user friendly Plugin. This plugin provides features like broken link checker, loading time of the pages, report of broken link in csv/xml format, etc.
 * Version: 2.5.0
 * Author: Cyberlord92
 * Author URI: https://miniorange.com
 * License: GPL2
 *
 * @package broken-link-finder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'MOBLC_VERSION', '2.5.0' );
define( 'MOBLC_PLUGIN_URL', ( plugin_dir_url( __FILE__ ) ) );
global $moblc_dirname;
$moblc_dirname = dirname( __FILE__ );
require_once $moblc_dirname . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'moblc-db-options.php';
if ( ! class_exists( 'MOBLC' ) ) {
	/**
	 * This is main class.
	 */
	class MOBLC {
		/**
		 * Contructor function of main class.
		 */
		public function __construct() {
			register_deactivation_hook( __FILE__, array( $this, 'moblc_deactivate' ) );
			register_activation_hook( __FILE__, array( $this, 'moblc_activate' ) );
			add_action( 'admin_menu', array( $this, 'moblc_widget_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'moblc_settings_style' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'moblc_settings_script' ) );
			add_action( 'moblc_show_message', array( $this, 'moblc_show_message' ), 1, 2 );
			add_action( 'plugins_loaded', array( $this, 'moblc_update_db_check' ) );
			add_action( 'admin_footer', array( $this, 'moblc_recheck_links' ) );

			$this->moblc_includes();
		}
		/**
		 * Function for including files.
		 *
		 * @return void
		 */
		public function moblc_includes() {
			require_once 'handler' . DIRECTORY_SEPARATOR . 'class-moblc-cron.php';
			require_once 'handler' . DIRECTORY_SEPARATOR . 'class-moblc-plugin.php';
			require_once 'controllers' . DIRECTORY_SEPARATOR . 'class-moblc-ajax.php';
			require_once 'database' . DIRECTORY_SEPARATOR . 'class-moblc-database.php';
			require_once 'helper' . DIRECTORY_SEPARATOR . 'class-moblc-constants.php';
			require_once 'helper' . DIRECTORY_SEPARATOR . 'class-moblc-post.php';
			require_once 'helper' . DIRECTORY_SEPARATOR . 'utility.php';
			require_once 'handler' . DIRECTORY_SEPARATOR . 'class-moblc-log-download.php';
		}
		/**
		 * Function for adding menu and submenu in the plugin.
		 *
		 * @return void
		 */
		public function moblc_widget_menu() {
			$menu_slug = 'moblc_manual';

			add_menu_page(
				'Broken Link Checker',
				'Broken Link Checker',
				'activate_plugins',
				$menu_slug,
				array(
					$this,
					'moblc',
				)
			);

			add_submenu_page(
				$menu_slug,
				'Broken Link Checker',
				'Broken Link Scan',
				'administrator',
				'moblc_manual',
				array(
					$this,
					'moblc',
				),
				1
			);
			add_submenu_page(
				$menu_slug,
				'Broken Link Checker',
				'Settings',
				'administrator',
				'moblc_settings',
				array(
					$this,
					'moblc',
				),
				2
			);
			add_submenu_page(
				$menu_slug,
				'Broken Link Checker',
				'Report',
				'administrator',
				'moblc_report',
				array(
					$this,
					'moblc',
				),
				3
			);

			add_submenu_page(
				$menu_slug,
				'Broken Link Checker',
				'Troubleshoot',
				'administrator',
				'moblc_troubleshooting',
				array(
					$this,
					'moblc',
				),
				4
			);
		}
		/**
		 * Function to include main-controller.php
		 *
		 * @return void
		 */
		public function moblc() {
			include 'controllers' . DIRECTORY_SEPARATOR . 'main-controller.php';
		}
		/**
		 * Function for site admin to activate the plugin.
		 *
		 * @return void
		 */
		public function moblc_activate() {
			if ( is_network_admin() ) {
				wp_die( esc_html__( 'Site Admin can activate the plugin.' ) );
			}
			update_site_option( 'moblc_update_on_activate', 1 );
			update_site_option( 'moblc_show_4xx', true );
		}
		/**
		 * Function to deactivate plugin and remove tables from database.
		 *
		 * @return void
		 */
		public function moblc_deactivate() {
			global $wpdb;
			$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}moblc_link_details_table" );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange -- Caching is not required here and no database schema change;
			$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}moblc_scan_status_table" );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange -- Caching is not required here and no database schema change;
			delete_site_option( 'moblc_activated_time' );
		}
		/**
		 * Function to enqueue styles.
		 *
		 * @param mixed $hook hook.
		 * @return void
		 */
		public function moblc_settings_style( $hook ) {
			if ( strpos( $hook, 'page_moblc' ) ) {
				wp_enqueue_style( 'moblc_admin_settings_style', plugins_url( 'includes' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'style_settings.min.css', __FILE__ ), array(), MOBLC_VERSION );
				wp_enqueue_style( 'moblc_admin_settings_datatable_style', plugins_url( 'includes' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'jquery.dataTables.min.css', __FILE__ ), array(), MOBLC_VERSION );
			}

		}
		/**
		 * Function for enqueing scripts.
		 *
		 * @param mixed $hook hook.
		 * @return void
		 */
		public function moblc_settings_script( $hook ) {
			wp_enqueue_script( 'moblc_admin_settings_script', plugins_url( 'includes' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'settings_page.min.js', __FILE__ ), array( 'jquery' ), MOBLC_VERSION, true );
			if ( strpos( $hook, 'page_moblc' ) ) {
				wp_enqueue_script( 'moblc_admin_datatable_script', plugins_url( 'includes' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'jquery.dataTables.min.js', __FILE__ ), array( 'jquery' ), MOBLC_VERSION, true );
			}
		}
		/**
		 * Function for showing success pop up.
		 *
		 * @param string $content pop up message.
		 * @return void
		 */
		public function moblc_success( $content ) {
			echo "<div class='moblc_overlay_not_JQ_success' id='pop_up_success'><p class='moblc_popup_text_not_JQ'>" . esc_html( $content ) . '</p> </div>';
			?>
			<script type="text/javascript">
				setTimeout(function () {
					var element = document.getElementById("pop_up_success");
					element.classList.toggle("moblc_overlay_not_JQ_success");
					element.innerHTML = "";
				}, 4000);

			</script>
				<?php

		}
		/**
		 * Function for showing error pop up.
		 *
		 * @param string $content pop up message.
		 * @return void
		 */
		public function moblc_error( $content ) {
			echo "<div class='moblc_overlay_not_JQ_error' id='pop_up_error'><p class='moblc_popup_text_not_JQ'>" . esc_html( $content ) . '</p> </div>';
			?>
		<script type="text/javascript">
			setTimeout(function () {
				var element = document.getElementById("pop_up_error");
				element.classList.toggle("moblc_overlay_not_JQ_error");
				element.innerHTML = "";
			}, 4000);

		</script>
			<?php

		}
		/**
		 * This function is for showing pop up messages.
		 *
		 * @param mixed $content content.
		 * @param mixed $type type.
		 * @return void
		 */
		public function moblc_show_message( $content, $type ) {
			if ( 'CUSTOM_MESSAGE' === $type ) {
				$this->moblc_success( $content );
			}
			if ( 'NOTICE' === $type ) {
				$this->moblc_error( $content );
			}
			if ( 'ERROR' === $type ) {
				$this->moblc_error( $content );
			}
			if ( 'SUCCESS' === $type ) {
				$this->moblc_success( $content );
			}
		}

			/**
			 * Function to update db version.
			 *
			 * @return void
			 */
		public function moblc_update_db_check() {

			if ( get_site_option( 'moblc_db_version' ) === null || get_site_option( 'moblc_db_version' ) < MOBLC_Constants::MOBLC_DB_VERSION ) {
				global $moblc_db_queries;
				$moblc_db_queries->moblc_migration();
				update_site_option( 'moblc_db_version', MOBLC_Constants::MOBLC_DB_VERSION );
			}
		}
			/**
			 * Function to recheck links.
			 *
			 * @return void
			 */
		public function moblc_recheck_links() {
			$nonce = wp_create_nonce( 'moblc-recheck-links-nonce' );
			?>
		<script>
			setInterval(
				(function moblc_recheck_links() {
					var data = {
						action: "moblc_broken_link_checker",
						option: "moblc_recheck_links",
						nonce: '<?php echo esc_js( $nonce ); ?>'
					};

					jQuery.post(ajaxurl, data, function (response) {
						if (response == "SUCCESS") {
						}
					});

					return moblc_recheck_links;
				})(),
				6000
			);

		</script>
				<?php
		}

	}
	new MOBLC();
}


