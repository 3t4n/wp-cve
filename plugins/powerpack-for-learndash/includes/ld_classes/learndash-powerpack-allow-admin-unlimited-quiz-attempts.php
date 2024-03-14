<?php
/**
 * Allow admin unlimited quiz attempts
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Allow_Admin_Unlimited_Quiz_Attempts', false ) ) {
	/**
	 * LearnDash_PowerPack_Allow_Admin_Unlimited_Quiz_Attempts Class.
	 */
	class LearnDash_PowerPack_Allow_Admin_Unlimited_Quiz_Attempts {
		/**
		 * Current class name
		 *
		 * @var string
		 */
		public $current_class = '';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter( 'learndash_quiz_attempts', [ $this, 'learndash_quiz_attempts_func' ], 10, 4 );
			}
		}

		/**
		 * Allows the Admin user to unlimited quiz attempts.
		 *
		 * @param int $attempts_left The number of attemps left.
		 * @param int $attempts_count The qty of attemps.
		 * @param int $user_id The ID of the user.
		 * @param int $quiz_id The ID of the quiz.
		 *
		 * @return int $attempts_left The number of attemps left.
		 */
		public function learndash_quiz_attempts_func( $attempts_left, $attempts_count, $user_id, $quiz_id ) {
			if ( current_user_can( 'manage_options' ) ) {
				$attempts_left = 1;
			}

			return $attempts_left;
		}

		/**
		 * Add class details.
		 *
		 * @return array Class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'quiz', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Unlimited Quiz Attempts', 'learndash-powerpack' );
			$class_description = esc_html__( 'Allow "admin" for unlimited quiz attempts', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Allow_Admin_Unlimited_Quiz_Attempts();
}

