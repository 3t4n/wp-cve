<?php
/**
 * Utilities Trait
 * Includes helper functions for the plugin
 *
 * @since 1.3.8
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews\Traits;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! trait_exists( __NAMESPACE__ . '\Utilities' ) ) {

	/**
	 * Utilities Trait
	 * Includes helper functions for the plugin
	 *
	 * @since 1.3.8
	 * @package EasyVideoReviews
	 */
	trait Utilities {


		/**
		 * Checks if WooCommerce is installed
		 *
		 * @return bool
		 */
		final public function is_wc_installed() {
			$plugin_slug = 'woocommerce/woocommerce.php';

			$installed_plugins = get_plugins();

			return isset( $installed_plugins[ $plugin_slug ] );
		}

		/**
		 * Checks if WooCommerce is active
		 *
		 * @return bool
		 */
		final public function is_wc_active() {
			return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
		}

		/**
		 * Checks if Easy Digital Downloads is installed
		 *
		 * @return bool
		 */
		final public function is_edd_installed() {
			$plugin_slug = 'easy-digital-downloads/easy-digital-downloads.php';

			$installed_plugins = get_plugins();

			return isset( $installed_plugins[ $plugin_slug ] );
		}

		/**
		 * Checks if Easy Digital Downloads is active
		 *
		 * @return bool
		 */
		final public function is_edd_active() {
			return in_array( 'easy-digital-downloads/easy-digital-downloads.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
		}

		/**
		 * Checks if Elementor is installed
		 *
		 * @return bool
		 */
		final public function is_elementor_installed() {
			$plugin_slug = 'elementor/elementor.php';

			$installed_plugins = get_plugins();

			return isset( $installed_plugins[ $plugin_slug ] );
		}

		/**
		 * Checks if Elementor is active
		 *
		 * @return bool
		 */
		final public function is_elementor_active() {
			return in_array( 'elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
		}

		/**
		 * Loads a template
		 *
		 * @param string $template_name Template name.
		 * @param array  $args Arguments.
		 * @return string
		 */
		final public function load_template( $template_name, $args = [] ) {
			$template_path = EASY_VIDEO_REVIEWS_TEMPLATES . $template_name . '.php';

			if ( ! file_exists( $template_path ) ) {
				return '';
			}

			ob_start();

			include $template_path;

			return ob_get_clean();
		}
		/**
		 * Renders a template
		 *
		 * @param string $template_name Template name.
		 * @param array  $args Arguments.
		 * @return string
		 */
		public function render_template( $template_name, $args = [] ) { // phpcs:ignore
			$template_path = EASY_VIDEO_REVIEWS_TEMPLATES . $template_name . '.php';

			if ( ! file_exists( $template_path ) ) {
				return '';
			}

			include $template_path;
		}

		/**
		 * Renders navigation
		 *
		 * @param array $args Arguments.
		 * @return mixed
		 */
		final public function render_nav( $args = [] ) {

			$items = isset($args['items']) ? $args['items'] : false;

			if ( empty($items) ) {
				return false;
			}

			$args['items'] = $items;

			$this->render_template('admin/other/top-navigation', $args);
		}

		/**
		 * Returns I/O instance
		 *
		 * @return \EasyVideoReviews\Helper\IO
		 */
		final public function io() {
			return \EasyVideoReviews\Helper\IO::get_instance();
		}

		/**
		 * Returns option instance
		 *
		 * @return \EasyVideoReviews\Helper\Option
		 */
		final public function option() {
			return \EasyVideoReviews\Helper\Option::get_instance();
		}

		/**
		 * Returns client instance
		 *
		 * @return \EasyVideoReviews\Helper\Client
		 */
		final public function client() {
			return \EasyVideoReviews\Helper\Client::get_instance();
		}

		/**
		 * Returns globals instance
		 *
		 * @return \EasyVideoReviews\Helper\Globals
		 */
		final public function globals() {
			return \EasyVideoReviews\Globals::get_instance();
		}
	}
}
