<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will be extended by all all single optin action(like create-woo-order, create-wp-user etc) to register different actions
 * Class WFFN_Optin_Action
 */
if ( ! class_exists( 'WFFN_Optin_Action' ) ) {
	#[AllowDynamicProperties]

 abstract class WFFN_Optin_Action {

		private static $slug = '';
		public $optin_data = [];
		public $priority = 100;

		/**
		 * WFFN_Optin_Action constructor.
		 *
		 */
		public function __construct() {
		}

		public function should_register() {
			return true;
		}

		public static function get_slug() {
			return self::$slug;
		}

		/**
		 * @param $posted_data
		 * @param $fields_settings
		 *
		 * @return array|bool|mixed
		 */
		public function handle_action( $posted_data, $fields_settings, $optin_action_settings ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			$optin_data = $this->setup_optin_data( $posted_data );

			return $optin_data;
		}

		/**
		 * @param string $key
		 *
		 * @return array|bool|mixed
		 */
		public function get_optin_data( $key = 'all' ) {

			if ( 'all' === $key ) {
				return $this->optin_data;
			}
			if ( isset( $this->optin_data[ $key ] ) ) {
				return $this->optin_data[ $key ];
			}

			return false;
		}

		/**
		 * @param $posted_data
		 * @param $fields_settings
		 */
		public function setup_optin_data( $posted_data ) {
			$this->optin_data = $posted_data;

			return $this->optin_data;
		}
	}
}
