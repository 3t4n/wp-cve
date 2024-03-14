<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Conditions_Archive_Tax' ) ) {

	/**
	 * Define Soft_template_Core_Conditions_Archive_Tax class
	 */
	class Soft_template_Core_Conditions_Archive_Tax extends Soft_template_Core_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'archive-tax';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Custom Taxonomy Archives', 'soft-template-core' );
		}

		/**
		 * Condition group
		 *
		 * @return string
		 */
		public function get_group() {
			return 'archive';
		}

		public function get_controls() {
			return array(
				'tax' => array(
					'label'    => esc_html__( 'Taxonomy', 'soft-template-core' ),
					'type'     => Elementor\Controls_Manager::SELECT2,
					'default'  => '',
					'options'  => Soft_template_Core_Utils::get_taxonomies(),
					'multiple' => true,
				),
				'terms' => array(
					'label'        => __( 'Select Terms', 'soft-template-core' ),
					'type'         => 'softtemplate_search',
					'action'       => 'soft_template_search_terms',
					'query_params' => array( 'conditions_archive-tax_tax' ),
					'label_block'  => true,
					'multiple'     => true,
					'description'  => __( 'Leave empty to apply for all terms', 'soft-template-core' ),
					'saved'        => $this->get_saved_tags(),
				),
			);
		}

		public function get_saved_tags() {

			$template_id = get_the_ID();
			$saved       = get_post_meta( $template_id, '_elementor_page_settings', true );


			if ( empty( $saved['conditions_archive-tax_tax'] ) ) {
				return array();
			}

			$tax = $saved['conditions_archive-tax_tax'];

			if ( ! empty( $saved['conditions_archive-tax_terms'] ) ) {

				$terms = get_terms( array(
					'include'    => $saved['conditions_archive-tax_terms'],
					'taxonomy'   => $tax,
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

			if ( empty( $args['tax'] ) ) {
				return __( 'All', 'soft-template-core' );
			}

			$result = '';
			$sep    = '';

			$terms = get_terms( array(
				'include'    => $args['tax'],
				'taxonomy'   => 'post_tag',
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

			if ( empty( $args['tax'] ) ) {
				return is_tax();
			}

			if ( ! empty( $args['tax'] ) && empty( $args['terms'] ) ) {
				return is_tax( $args['tax'] );
			}

			if ( ! empty( $args['terms'] ) ) {
				return is_tax( $args['tax'], $args['terms'] );
			}
		}

	}

}