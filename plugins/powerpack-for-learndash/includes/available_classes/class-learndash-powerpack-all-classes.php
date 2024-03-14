<?php
/**
 * Classes
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_All_Classes', false ) ) {
	/**
	 * LearnDash_PowerPack_All_Classes Class.
	 */
	class LearnDash_PowerPack_All_Classes {
		/**
		 * Get all LearnDash Powerpack classes.
		 */
		public static function get_all_powerpack_classes() {
			$get_declared  = get_declared_classes();
			$classes_array = [];

			foreach ( $get_declared as $class_name ) {
				if ( strpos( $class_name, 'LearnDash_PowerPack_' ) === false ) {
					continue;
				}

				$classes_array[] = $class_name;
			}

			if ( has_filter( 'learndash_filter_classes' ) ) {
				/**
				 * Filters the LearnDash Powerpack classes.
				 *
				 * @deprecated 1.3.0 Use {@see 'learndash_powerpack_filter_classes'} instead.
				 *
				 * @param array $classes_array An array of LearnDash Powerpack class names.
				 */
				$classes_array = apply_filters_deprecated(
					'learndash_filter_classes',
					array( $classes_array ),
					'1.3.0',
					'learndash_powerpack_filter_classes'
				);
			}

			/**
			 * Filters LearnDash Powerpack classes.
			 *
			 * @since 1.3.0
			 *
			 * @param array $select_option_array Array of options.
			 */
			return apply_filters( 'learndash_powerpack_filter_classes', $classes_array );
		}
	}
}

