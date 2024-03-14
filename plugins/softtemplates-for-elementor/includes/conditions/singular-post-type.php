<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Conditions_Singular_Post_Type' ) ) {

	/**
	 * Define Soft_template_Core_Conditions_Singular_Post_Type class
	 */
	class Soft_template_Core_Conditions_Singular_Post_Type extends Soft_template_Core_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'singular-post-type';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Post Type', 'soft-template-core' );
		}

		/**
		 * Condition group
		 *
		 * @return string
		 */
		public function get_group() {
			return 'singular';
		}

		public function get_controls() {
			return array(
				'types' => array(
					'label'    => esc_html__( 'Post Type', 'soft-template-core' ),
					'type'     => Elementor\Controls_Manager::SELECT2,
					'default'  => 'post',
					'options'  => Soft_template_Core_Utils::get_post_types(),
					'multiple' => true,
				),
			);
		}

		public function verbose_args( $args ) {

			if ( empty( $args['types'] ) ) {
				return __( 'All', 'soft-template-core' );
			}

			$result = '';
			$sep    = '';

			foreach ( $args['types'] as $post_type ) {
				$obj     = get_post_type_object( $post_type );
				$result .= $sep . $obj->labels->singular_name;
				$sep     = ', ';
			}

			return $result;
		}

		/**
		 * Condition check callback
		 *
		 * @return bool
		 */
		public function check( $args ) {

			if ( empty( $args['types'] ) ) {
				return is_singular();
			}
			
			return is_singular( $args['types'] );
		}

	}

}