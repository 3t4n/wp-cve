<?php
/**
 * Elmo plugin main class
 *
 * @package Elmo
 */

if ( ! class_exists( 'Elmo_Plugin' ) ) {
	/**
	 * Elmo plugin main class
	 */
	final class Elmo_Plugin {
		/**
		 * The existing instance of this class.
		 *
		 * @var Elmo_Plugin
		 */
		private static $instance;

		/**
		 * The Elmo site's URL
		 *
		 * @var string
		 */
		public $elmo_site = 'https://www.privacylab.it';

		/**
		 * V2 URL for Elmo's banner.
		 *
		 * @var string
		 */
		public $elmo_site_v2 = 'https://bnr.elmobot.eu';

		/**
		 * Array of allowed languages inside Elmo's banner
		 *
		 * @var string[]
		 */
		public $elmo_allowed_languages = array( 'it', 'en', 'fr', 'de', 'ro', 'es' );

		/**
		 * The init function. Call it to start the plugin.
		 *
		 * @return void
		 */
		public function init() {
			add_action( 'wp_head', array( $this, 'install_elmo' ), 0 );

			if ( is_admin() ) {
				require_once __DIR__ . '/class-elmo-plugin-admin.php';

				Elmo_Plugin_Admin::get_instance( $this )->init();
			}
		}

		/**
		 * Get the existing instance of this class. If not exists instantiate a new one.
		 *
		 * @return Elmo_Plugin
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Install Elmo's script on the frontend site
		 *
		 * @return void
		 */
		public function install_elmo() {
			$elmo_code       = get_option( 'elmo_code' );
			$elmo_code_v2    = get_option( 'elmo_code_v2' );
			$elmo_language   = get_option( 'elmo_language' );
			$language_params = '';
			$language_file   = 'it.js';
			// Cannot use wp_enqueue_script because must be the first script executed.
			if ( ! empty( $elmo_code_v2 ) ) {
				if ( ! empty( $elmo_language ) ) {
					if ( 'default' !== $elmo_language ) {
						$language_file = $elmo_language . '.js';
					} else {
						// Se sono default con una lingua valida uso il locale di WordPress, altrimenti redirect IT.
						if ( in_array( substr( get_locale(), 0, 2 ), $this->elmo_allowed_languages, true ) ) {
							$language_file = substr( get_locale(), 0, 2 ) . '.js';
						}
						// Altrimenti tengo il default a it.js.
					}
				}
				echo '<script src="' . esc_attr( $this->elmo_site_v2 ) . '/' . esc_attr( $elmo_code_v2 ) . '/' . esc_attr( $language_file ) . '"></script>';// phpcs:ignore WordPress.WP.EnqueuedResources
			} elseif ( ! empty( $elmo_code ) ) {
				if ( ! empty( $elmo_language ) ) {
					if ( 'default' !== $elmo_language ) {
						$language_params = '&lang=' . $elmo_language;
					} else {
						// Se sono default uso il locale di WordPress.
						$language_params = '&lang=' . substr( get_locale(), 0, 2 );
					}
				}
				echo '<script src="' . esc_attr( $this->elmo_site ) . '/elmo.php?code=' . esc_attr( $elmo_code ) . esc_attr( $language_params ) . '"></script>';// phpcs:ignore WordPress.WP.EnqueuedResources
			}
		}
	}

	Elmo_Plugin::get_instance()->init();
}

