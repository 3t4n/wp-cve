<?php
/**
 * Force page reload when restarting quiz
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Force_Page_Reload_When_Restart_Quiz_Button', false ) ) {
	/**
	 * LearnDash_PowerPack_Force_Page_Reload_When_Restart_Quiz_Button Class.
	 */
	class LearnDash_PowerPack_Force_Page_Reload_When_Restart_Quiz_Button {
		/**
		 * Current class name
		 *
		 * @var string
		 */
		public $current_class = '';

		/**
		 * Cosntructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_action( 'wp_footer', [ $this, 'learndash_wp_footer_page_reload' ], 999 );
			}
		}

		/**
		 * Returns the JS script to force page reload.
		 */
		public function learndash_wp_footer_page_reload() {
			?>
			<script>
				jQuery(document).ready(function () {
					if (jQuery('.wpProQuiz_content input[name="restartQuiz"]').length) {
						jQuery('.wpProQuiz_content input[name="restartQuiz"]').click(function (event) {
							window.location.reload(true);
						});
					}
				});
			</script>
			<?php
		}

		/**
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'quiz', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Page reload', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to force page reload when Restart Quiz button is clicked.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Force_Page_Reload_When_Restart_Quiz_Button();
}

