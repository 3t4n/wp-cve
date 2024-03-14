<?php
/**
 * Enable course step in WP Menu
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Enable_Course_Step_In_Wp_Menu', false ) ) {
	/**
	 * LearnDash_PowerPack_Enable_Course_Step_In_Wp_Menu Class.
	 */
	class LearnDash_PowerPack_Enable_Course_Step_In_Wp_Menu {
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
				add_filter( 'learndash_post_args', [ $this, 'learndash_post_args_func' ] );
			}
		}

		/**
		 * Add the options tho show the lessons, topic and quiz in the menu.
		 *
		 * @param array $post_args The array with the post arguments.
		 *
		 * @return array The modified post arguments passed as reference.
		 */
		public function learndash_post_args_func( $post_args = [] ) {
			// LearnDash v2.5.3 LEARNDASH-1388.
			if ( 'yes' !== LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Courses_Builder', 'shared_steps' ) ) {
				$post_args['sfwd-lessons']['cpt_options']['show_in_nav_menus'] = true;
				$post_args['sfwd-topic']['cpt_options']['show_in_nav_menus']   = true;
				$post_args['sfwd-quiz']['cpt_options']['show_in_nav_menus']    = true;
			}

			// always return the $post_args array.
			return $post_args;
		}

		/**
		 * Add the class details.
		 *
		 * @returns array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'course', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Course step in WP menu', 'learndash-powerpack' );
			$class_description = esc_html__( 'LearnDash enable course step in WP menu.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Enable_Course_Step_In_Wp_Menu();
}

