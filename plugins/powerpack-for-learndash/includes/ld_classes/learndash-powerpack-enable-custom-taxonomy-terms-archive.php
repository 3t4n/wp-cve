<?php
/**
 * Enable custom taxonomy terms archive
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Enable_Custom_Taxonomy_Terms_Archive', false ) ) {
	/**
	 * LearnDash_PowerPack_Enable_Custom_Taxonomy_Terms_Archive Class.
	 */
	class LearnDash_PowerPack_Enable_Custom_Taxonomy_Terms_Archive {
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
				add_filter( 'learndash_taxonomy_args', [ $this, 'learndash_taxonomy_args_func' ], 10, 2 );
			}
		}

		/**
		 * Set the custom taxonomy 'ld_course_taxonomy' to public.
		 *
		 * @param array  $tax_options The options for the taxonomie.
		 * @param String $tax_slug The taxonomie's slug.
		 *
		 * @return array The modified $tax_options array.
		 */
		public function learndash_taxonomy_args_func( $tax_options, $tax_slug ) {
			// Example 1 Set the custom taxonomy 'ld_course_taxonomy' to public.
			if ( 'ld_course_category' === $tax_slug ) {
				$tax_options['tax_args']['public'] = true;
			}

			// Always return $tax_options.
			return $tax_options;
		}

		/**
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'taxonomy', 'learndash-powerpack' );
			$class_title       = esc_html__( 'LearnDash Enable Custom Taxonomy Terms Archive', 'learndash-powerpack' );
			$class_description = '';

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Enable_Custom_Taxonomy_Terms_Archive();
}

