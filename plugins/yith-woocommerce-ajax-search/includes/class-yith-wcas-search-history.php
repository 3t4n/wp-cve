<?php
/**
 * Search History class
 *
 * @author  YITH
 * @package YITH/Search
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'YITH_WCAS_Search_History' ) ) {
	/**
	 * WooCommerce Ajax Search
	 *
	 * @since 2.0.0
	 */
	class YITH_WCAS_Search_History {

		use YITH_WCAS_Trait_Singleton;


		/**
		 * Register query string
		 *
		 * @param string $query Query to register.
		 * @param int    $total_results Number of results.
		 * @param string $lang Current language.
		 * @param int    $item_id Product id clicked.
		 *
		 * @return int
		 */
		public function register_query( $query, $total_results, $lang, $item_id ) {

			return YITH_WCAS_Data_Search_Engine::get_instance()->get_logger_reference( $query, $total_results, $item_id, $lang );
		}
	}
}
