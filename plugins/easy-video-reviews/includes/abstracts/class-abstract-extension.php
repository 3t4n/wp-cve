<?php

/**
 * Base Extension Class
 * Initiate the extensions for Easy Video Reviews
 *
 * @since 1.3.8
 * @package EasyVideoReviews
 */
// Namespace.
namespace EasyVideoReviews\Base;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Extension' ) ) {

	/**
	 * Base Extension Class
	 *
	 * @since 1.3.8
	 */
	abstract class Extension extends \EasyVideoReviews\Base\Controller {


		/**
		 * Adds the extension to the list of extensions
		 *
		 * @param mixed $extensions Extensions.
		 * @return mixed
		 */
		public function add_extension( $extensions ) {
			$extensions[] = $this->get_settings();

			return $extensions;
		}


		/**
		 * Adds the options to the list of options
		 *
		 * @param mixed $options Options.
		 * @return mixed
		 */
		public function add_options( $options ) {
			$options[ $this->get_id() ] = $this->options();
			return $options;
		}

		/**
		 * Get the settings for the extension
		 *
		 * @return array
		 */
		public function get_settings() {
			return apply_filters( 'evr_extension_settings', [
				'id'       => $this->get_id(),
				'name'     => $this->name(),
				'icon'     => $this->icon(),
				'icon_url' => $this->icon_url(),
				'is_pro'   => $this->is_pro(),
			], 99 );
		}

		/**
		 * Gets the options for the extension
		 *
		 * @return array
		 */
		public function options() {
			return [];
		}

		/**
		 * Renders the extension form
		 *
		 * @return void
		 */
		public function render_extension_form() {
			?>
					<section v-if="isScreen('<?php echo esc_attr( $this->id() ); ?>')">
						<?php $this->form(); ?>
					</section>
			<?php
		}

		/**
		 * Registers hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			add_filter( 'evr_extensions', [ $this, 'add_extension' ], 99 );
			add_filter( 'evr_options', [ $this, 'add_options' ], 99 );
			add_filter( 'evr_admin_localize_script', [ $this, 'add_script' ], 99 );
			add_action( 'evr_extension_form', [ $this, 'render_extension_form' ], 99 );
		}

		/**
		 * Gets the id of the extension
		 *
		 * @return string
		 */
		protected function get_id() {
			return sanitize_title( $this->id() );
		}

		/**
		 * Gets the id of the extension
		 *
		 * @return string
		 */
		public function id() {
			return sanitize_title( $this->name() );
		}

		/**
		 * Gets the name of the extension
		 *
		 * @return string
		 */
		public function name() {
			return esc_html__( 'Example Extension', 'easy-video-reviews' );
		}

		/**
		 * Renders the extension form
		 *
		 * @return void
		 */
		public function form() {}

		/**
		 * Gets the icon of the extension
		 *
		 * @return string
		 */
		public function icon() {
			return '';
		}

		/**
		 * Gets the icon url of the extension
		 *
		 * @return string
		 */
		public function icon_url() {
			return '';
		}

		/**
		 * Checks if the extension is pro
		 *
		 * @return mixed
		 */
		public function is_pro() {
			return true;
		}

		/**
		 * Gets the script for the extension
		 *
		 * @return mixed
		 */
		public function script() {
			return false;
		}

		/**
		 * Adds the script to the list of scripts
		 *
		 * @param mixed $script Script.
		 * @return mixed
		 */
		public function add_script( $script ) {
			if ( $this->script() ) {
				$script[ $this->get_id() ] = $this->script();
			}

			return $script;
		}
	}
}
