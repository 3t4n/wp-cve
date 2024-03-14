<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Conditions_Singular_Page' ) ) {

	/**
	 * Define Soft_template_Core_Conditions_Singular_Page class
	 */
	class Soft_template_Core_Conditions_Singular_Page extends Soft_template_Core_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'singular-page';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Page', 'soft-template-core' );
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
				'pages' => array(
					'label'       => __( 'Select Pages', 'soft-template-core' ),
					'type'        => 'softtemplate_search',
					'action'      => 'soft_template_search_pages',
					'label_block' => true,
					'multiple'    => true,
					'description' => __( 'Leave empty to apply for all pages', 'soft-template-core' ),
					'saved'       => $this->get_saved_pages(),
				),
			);
		}

		public function get_saved_pages() {

			$template_id = get_the_ID();
			$saved       = get_post_meta( $template_id, '_elementor_page_settings', true );

			if ( ! empty( $saved['conditions_singular-page_pages'] ) ) {

				$posts = get_posts( array(
					'post_type'           => 'page',
					'post__in'            => $saved['conditions_singular-page_pages'],
					'ignore_sticky_posts' => true,
				) );

				if ( empty( $posts ) ) {
					return array();
				} else {
					return wp_list_pluck( $posts, 'post_title', 'ID' );
				}

			} else {
				return array();
			}
		}

		public function verbose_args( $args ) {

			if ( empty( $args['pages'] ) ) {
				return __( 'All', 'soft-template-core' );
			}

			$result = '';
			$sep    = '';

			foreach ( $args['pages'] as $page ) {
				$result .= $sep . get_the_title( $page );
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

			if ( empty( $args['pages'] ) ) {
				return is_page();
			}

			return is_page( $args['pages'] );
		}

	}

}