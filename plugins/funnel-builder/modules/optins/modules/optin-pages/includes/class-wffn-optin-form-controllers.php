<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Handles the operations and usage of form controllers in optin pages
 * Class WFFN_Optin_Form_Controllers
 */
if ( ! class_exists( 'WFFN_Optin_Form_Controllers' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Optin_Form_Controllers {

		/**
		 * @var null
		 */
		public static $ins = null;

		/**
		 * @var WFFN_Optin_Form_Controllers[]
		 */
		public $form_controllers = array();

		/**
		 * Step classes prefix
		 * @var string
		 */
		public $class_prefix = 'WFFN_Optin_Form_Controller_';

		/**
		 * WFFN_Optin_Form_Controllers constructor.
		 */
		public function __construct() {
			add_action( 'wfopp_loaded', array( $this, 'load_optin_form_controllers' ) );
		}

		/**
		 * @return WFFN_Optin_Form_Controllers|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		/**
		 * @return WFFN_Optin_Form_Controllers[]
		 */
		public function get_supported_form_controllers() {
			return $this->form_controllers;
		}

		/**
		 * @param $optin_form_controller_slug
		 *
		 * @return bool|WFFN_Optin_Form_Controller
		 */
		public function get_integration_object( $optin_form_controller_slug ) {

			if ( isset( $this->form_controllers[ $optin_form_controller_slug ] ) ) {
				return $this->form_controllers[ $optin_form_controller_slug ];
			}

			return false;
		}

		/**
		 * @param $form_controller WFFN_Optin_Form_Controller
		 *
		 * @throws Exception
		 */
		public function register( $form_controller ) {

			if ( empty( $form_controller->slug ) ) {
				throw new Exception( 'The optin action type must be set' );
			}
			if ( isset( $this->form_controllers[ $form_controller->slug ] ) ) {
				throw new Exception( 'Optin action type already registered: ' . $form_controller->slug );
			}
			if ( false === $form_controller->should_register() ) {
				return;
			}
			$this->form_controllers[ $form_controller->slug ] = $form_controller;

		}

		/**
		 * Includes optin actions files
		 */
		public function load_optin_form_controllers() {
			// load all the trigger files automatically
			foreach ( glob( plugin_dir_path( WFOPP_PLUGIN_FILE ) . 'modules/optin-pages/form-controllers/class-*.php' ) as $form_controller_file_name ) {
				require_once( $form_controller_file_name ); //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			}
		}
	}

	WFOPP_Core::register( 'form_controllers', 'WFFN_Optin_Form_Controllers' );
}
