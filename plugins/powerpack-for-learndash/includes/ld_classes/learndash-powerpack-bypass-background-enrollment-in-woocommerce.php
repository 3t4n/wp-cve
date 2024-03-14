<?php
/**
 * Bypass background enrollment in WooCommerce
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Bypass_Background_Enrollment_In_Woocommerce', false ) ) {
	/**
	 * LearnDash_PowerPack_Bypass_Background_Enrollment_In_Woocommerce Class.
	 */
	class LearnDash_PowerPack_Bypass_Background_Enrollment_In_Woocommerce {
		/**
		 * Current class name
		 *
		 * @var string
		 */
		public $current_class = '';

		/**
		 * Constructor and hooks.
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter(
					'learndash_woocommerce_products_count_for_silent_course_enrollment',
					[ $this, 'learndash_woocommerce_products_count_for_silent_course_enrollment_func' ]
				);
			}
		}

		/**
		 * Returns a big number so it won't use background course enrollment.
		 *
		 * @param int $count NOT USED.
		 *
		 * @return int arbitrary big number.
		 */
		public function learndash_woocommerce_products_count_for_silent_course_enrollment_func( $count ) {
			return 999; // Big number so it won't use background course enrollment.
		}

		/**
		 * Add class details.
		 *
		 * @return array Class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = '';
			$class_title       = esc_html__( 'Bypass Background Enrollment in WooCommerce', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to Bypass Background Enrollment in WooCommerce.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Bypass_Background_Enrollment_In_Woocommerce();
}

