<?php
/**
 * Disable Gutenberg on LearnDash post types
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Disable_Gutenberg_Editor', false ) ) {
	/**
	 * LearnDash_PowerPack_Disable_Gutenberg_Editor Class.
	 */
	class LearnDash_PowerPack_Disable_Gutenberg_Editor {
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
				add_filter( 'use_block_editor_for_post_type', [ $this, 'use_block_editor_for_post_type_func' ], 10, 2 );
			}
		}

		/**
		 * Sets the use of Gutemberg blocks.
		 *
		 * @param bool   $use_gutenberg The option to use Gutenber block.
		 * @param String $post_type The post type.
		 *
		 * @return bool wether use gutenberg blocks or not.
		 */
		public function use_block_editor_for_post_type_func( $use_gutenberg, $post_type ) {
			$ld_course_types = [ 'sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz', 'sfwd-question', 'group' ];
			if ( in_array( $post_type, $ld_course_types, true ) ) {
				$use_gutenberg = false;
			}

			// Always return $use_gutenberg.
			return $use_gutenberg;
		}

		/**
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'editor', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Disable Gutenberg Editor', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to Disable Gutenberg Editor on any of the LearnDash custom post types.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Disable_Gutenberg_Editor();
}

