<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Handles the operations and usage of actions in optin pages
 * Class WFFN_Optin_Actions
 */
if ( ! class_exists( 'WFFN_Optin_Actions' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Optin_Actions {

		/**
		 * @var null
		 */
		public static $ins = null;

		/**
		 * @var WFFN_Optin_Actions[]
		 */
		public $optin_actions = array();

		/**
		 * Step classes prefix
		 * @var string
		 */
		public $class_prefix = 'WFFN_Optin_Action_';

		/**
		 * WFFN_Optin_Actions constructor.
		 */
		public function __construct() {
			add_action( 'wfopp_loaded', array( $this, 'load_optin_actions' ) );
		}

		/**
		 * @return WFFN_Optin_Actions|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		/**
		 * @return WFFN_Optin_Actions[]
		 */
		public function get_supported_actions() {
			uasort( $this->optin_actions, function ( $a, $b ) {

				if ( $a->priority === $b->priority ) {
					return 0;
				}

				return ( $a->priority < $b->priority ) ? - 1 : 1;
			} );

			return $this->optin_actions;
		}


		/**
		 * @param $optin_action_class
		 *
		 * @return bool|WFFN_Optin_Actions
		 */
		public function get_integration_object( $optin_action_class ) {

			if ( isset( $this->optin_actions[ $optin_action_class ] ) ) {
				return $this->optin_actions[ $optin_action_class ];
			}

			return false;
		}

		/**
		 * @param $optin_action WFFN_Optin_Action
		 *
		 * @throws Exception
		 */
		public function register( $optin_action ) {

			if ( empty( $optin_action::get_slug() ) ) {
				throw new Exception( 'The optin action type must be set' );
			}
			if ( isset( $this->optin_actions[ $optin_action::get_slug() ] ) ) {
				throw new Exception( 'Optin action type already registered: ' . $optin_action::get_slug() );
			}
			if ( false === $optin_action->should_register() ) {
				return;
			}
			$this->optin_actions[ $optin_action::get_slug() ] = $optin_action;

		}

		/**
		 * Includes optin actions files
		 */
		public function load_optin_actions() {
			// load all the trigger files automatically
			foreach ( glob( plugin_dir_path( WFOPP_PLUGIN_FILE ) . 'modules/optin-pages/actions/class-*.php' ) as $optin_action_file_name ) {
				require_once( $optin_action_file_name ); //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			}
		}

		/**
		 * @param $posted_data
		 *
		 * @return mixed
		 */
		public function update_bwf_contact( $posted_data ) {
			$create_bwf_contact = WFOPP_Core()->optin_actions->get_integration_object( WFFN_Optin_Action_Create_BWF_Contact::get_slug() );
			if ( $create_bwf_contact instanceof WFFN_Optin_Action ) {
				return $create_bwf_contact->update_bwf_contact_force( $posted_data );
			}

			return $posted_data;
		}

		/**
		 * @return array
		 */
		public function get_optin_action_settings( $optin_page_id ) {
			return ( $optin_page_id > 0 ) ? get_post_meta( $optin_page_id, 'wffn_actions_custom_settings', true ) : [];

		}
	}

	WFOPP_Core::register( 'optin_actions', 'WFFN_Optin_Actions' );
}