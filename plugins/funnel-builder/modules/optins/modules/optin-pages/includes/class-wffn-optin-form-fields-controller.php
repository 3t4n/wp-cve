<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Handles the operations and usage of form optin form fields in optin pages
 * Class WFFN_Optin_Form_Fields_Controller
 */
if ( ! class_exists( 'WFFN_Optin_Form_Fields_Controller' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Optin_Form_Fields_Controller {

		/**
		 * @var null
		 */
		public static $ins = null;

		/**
		 * @var WFFN_Optin_Form_Field[]
		 */
		public $form_fields = array();

		/**
		 * Step classes prefix
		 * @var string
		 */
		public $class_prefix = 'WFFN_Optin_Form_Field_';

		/**
		 * WFFN_Optin_Form_Controllers constructor.
		 */
		public function __construct() {
			add_action( 'wfopp_loaded', array( $this, 'load_optin_form_fields' ) );
		}

		/**
		 * @return WFFN_Optin_Form_Fields_Controller|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		/**
		 * @return WFFN_Optin_Form_Field[]
		 */
		public function get_supported_form_fields_controller() {
			usort( $this->form_fields, function ( $a, $b ) {
				if ( $a->index > $b->index ) {
					return 1;
				} elseif ( $a->index < $b->index ) {
					return - 1;
				} else {
					return 0;
				}
			} );

			return $this->form_fields;
		}

		/**
		 * @param $optin_form_field_slug
		 *
		 * @return false|WFFN_Optin_Form_Field
		 */
		public function get_integration_object( $optin_form_field_slug ) {

			if ( isset( $this->form_fields[ $optin_form_field_slug ] ) ) {
				return $this->form_fields[ $optin_form_field_slug ];
			}

			return false;
		}

		/**
		 * @param $form_field WFFN_Optin_Form_Field
		 *
		 * @throws Exception
		 */
		public function register( $form_field ) {

			if ( empty( $form_field->get_slug() ) ) {
				throw new Exception( 'The optin field type must be set' );
			}
			if ( isset( $this->form_fields[ $form_field->get_slug() ] ) ) {
				throw new Exception( 'Optin action type already registered: ' . $form_field->get_slug() );
			}
			if ( false === $form_field->should_register() ) {
				return;
			}
			$this->form_fields[ $form_field->get_slug() ] = $form_field;

		}

		/**
		 * Includes optin form fields files
		 */
		public function load_optin_form_fields() {
			// load all the trigger files automatically
			foreach ( glob( plugin_dir_path( WFOPP_PLUGIN_FILE ) . 'modules/optin-pages/form-fields/class-*.php' ) as $form_field_file_name ) {
				require_once( $form_field_file_name ); //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			}
		}
	}

	WFOPP_Core::register( 'form_fields', 'WFFN_Optin_Form_Fields_Controller' );
}
