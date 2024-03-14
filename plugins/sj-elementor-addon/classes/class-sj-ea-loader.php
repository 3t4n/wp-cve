<?php

if ( ! class_exists( 'SJEaLoader' ) ) {
	
	/**
	* Responsible for setting up constants, classes and includes.
	*
	* @since 0.1
	*/
	final class SJEaLoader {
		
		/**
		 * Load the builder if it's not already loaded, otherwise
		 * show an admin notice.
		 *
		 * @since 0.1
		 * @return void
		 */ 
		static public function init() {
			if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
				add_action('admin_notices',         __CLASS__ . '::php_required_admin_notice');
				add_action('network_admin_notices', __CLASS__ . '::php_required_admin_notice');
				return;
			} 
			
						
			self::define_constants();
			self::load_files();
		}

		/**
		 * Define addon constants.
		 *
		 * @since 0.1
		 * @return void
		 */ 
		static private function define_constants() {	
			define('SJ_EA_VERSION', '0.2.0');
			define('SJ_EA_FILE', trailingslashit(dirname(dirname(__FILE__))) . 'sj-elementor-addon.php');
			define('SJ_EA_PLUGIN_BASE', plugin_basename( SJ_EA_FILE ) );
			define('SJ_EA_DIR', plugin_dir_path( SJ_EA_FILE ) );
			define('SJ_EA_URL', plugins_url( '/', SJ_EA_FILE ) );
			define('SJ_EA_FILE_ASSETS_URL', SJ_EA_URL . 'assets/' );
		}

		/**
		 * Loads classes and includes.
		 *
		 * @since 0.1
		 * @return void
		 */ 
		static private function load_files()
		{
			require_once SJ_EA_DIR . 'classes/class-sj-ea-model-helper.php';
			require_once SJ_EA_DIR . 'classes/class-sj-ea-ajax.php';
			require_once SJ_EA_DIR . 'classes/class-sj-ea-services.php';
			require_once SJ_EA_DIR . 'classes/class-sj-ea-admin-settings.php';

			/* Required Main File */
			require_once SJ_EA_DIR . 'classes/class-sj-ea-helper.php';
			require_once SJ_EA_DIR . 'classes/class-sj-ea-module-scripts.php';
			require_once SJ_EA_DIR . 'classes/class-sj-ea-model.php';

			/* Includes */
		}
		/**
		 * Shows an admin notice if php version is not correct.
		 *
		 * @since 0.1
		 * @return void
		 */
		static public function php_required_admin_notice() {
			$message = esc_html__( 'SJ Elementor requires Elementor Page Builder.', 'sjea' );
			self::render_admin_notice( $message, 'error' );
		}

		/**
		 * Renders an admin notice.
		 *
		 * @since 0.1
		 * @access private
		 * @param string $message
		 * @param string $type
		 * @return void
		 */ 
		static private function render_admin_notice( $message, $type = 'update' ) {
			if ( ! is_admin() ) {
				return;
			}
			else if ( ! is_user_logged_in() ) {
				return;
			}
			else if ( ! current_user_can( 'update_core' ) ) {
				return;
			}
			
			echo '<div class="' . $type . '">';
			echo '<p>' . $message . '</p>';
			echo '</div>';
		}
	}

	SJEaLoader::init();
}

