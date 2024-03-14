<?php
/**
 * Change welcome message in focus mode to first name
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Change_Welcome_Message_In_Focus_Mode_To_Use_First_Name', false ) ) {
	/**
	 * LearnDash_PowerPack_Change_Welcome_Message_In_Focus_Mode_To_Use_First_Name Class.
	 */
	class LearnDash_PowerPack_Change_Welcome_Message_In_Focus_Mode_To_Use_First_Name {
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
				add_filter( 'ld_focus_mode_welcome_name', [ $this, 'ld_focus_mode_welcome_name_func' ], 10, 2 );
			}
		}

		/**
		 * Get the First Name from the user_info parameter.
		 *
		 * @param String $display_name The display name of the user.
		 * @param array  $user_info The array with all the user information.
		 *
		 * @return String The First name of the user.
		 */
		public function ld_focus_mode_welcome_name_func( $display_name, $user_info ) {
			return $user_info->first_name;
		}

		/**
		 * Add the class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'focus_mode', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Welcome message in Focus Mode', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to change welcome message in Focus Mode to use first name instead of the username', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Change_Welcome_Message_In_Focus_Mode_To_Use_First_Name();
}

