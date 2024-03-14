<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Conditions_Singular_Post_From_Category' ) ) {

	/**
	 * Define Soft_template_Core_Conditions_Singular_Post_From_Category class
	 */
	class Soft_template_Core_Conditions_Singular_Post_From_Category extends Soft_template_Core_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'singular-post-from-cat';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Posts from Category', 'soft-template-core' );
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
				'cats' => array(
					'label'       => __( 'Select Categories', 'soft-template-core' ),
					'type'        => 'softtemplate_search',
					'action'      => 'soft_template_search_cats',
					'label_block' => true,
					'multiple'    => true,
					'saved'       => $this->get_saved_cats(),
				),
			);
		}

		public function get_saved_cats() {

			$template_id = get_the_ID();
			$saved       = get_post_meta( $template_id, '_elementor_page_settings', true );

			if ( ! empty( $saved['conditions_singular-post-from-cat_cats'] ) ) {

				$terms = get_terms( array(
					'include'    => $saved['conditions_singular-post-from-cat_cats'],
					'taxonomy'   => 'category',
					'hide_empty' => false,
				) );

				if ( empty( $terms ) ) {
					return array();
				} else {
					return wp_list_pluck( $terms, 'name', 'term_id' );
				}

			} else {
				return array();
			}
		}

		public function verbose_args( $args ) {

			if ( empty( $args['cats'] ) ) {
				return __( 'All', 'soft-template-core' );
			}

			$result = '';
			$sep    = '';

			$terms = get_terms( array(
				'include'    => $args['cats'],
				'taxonomy'   => 'category',
				'hide_empty' => false,
			) );

			foreach ( $terms as $term ) {
				$result .= $sep . $term->name;
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

			if ( empty( $args['cats'] ) ) {
				return false;
			}

			if ( ! is_single() ) {
				return false;
			}

			global $post;

			return in_category( $args['cats'], $post );
		}

	}

}