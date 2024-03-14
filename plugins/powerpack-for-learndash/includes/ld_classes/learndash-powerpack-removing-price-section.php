<?php
/**
 * Remove price section
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Removing_Price_Section', false ) ) {
	/**
	 * LearnDash_PowerPack_Removing_Price_Section Class.
	 */
	class LearnDash_PowerPack_Removing_Price_Section {
		/**
		 * Current class name
		 *
		 * @var string
		 */
		public $current_class = '';

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_action( 'wp_footer', [ $this, 'wp_footer_func' ] );
				add_action( 'admin_footer', [ $this, 'wp_footer_func' ] );
			}
		}

		/**
		 * Return style for the wp_footer.
		 */
		public function wp_footer_func() {
			?>
			<style>
				.ld-course-status-seg-price {
					display: none;
				}
			</style>
			<?php
		}

		/**
		 * Add the class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'price', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Remove price section', 'learndash-powerpack' );
			$class_description = esc_html__( 'Remove price section', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Removing_Price_Section();
}

