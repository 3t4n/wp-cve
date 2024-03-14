<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will Create bwf contact on submitting optin form
 * Class WFFN_Optin_Action_Create_BWF_Contact
 */
if ( ! class_exists( 'WFFN_Optin_Action_Create_BWF_Contact' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Optin_Action_Create_BWF_Contact extends WFFN_Optin_Action {

		private static $slug = 'create_bwf_contact';
		private static $ins = null;
		public $priority = 10;

		/**
		 * WFFN_Optin_Action_Create_BWF_Contact constructor.
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * @return WFFN_Optin_Action_Create_BWF_Contact|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public static function get_slug() {
			return self::$slug;
		}

		/**
		 * @param $posted_data
		 * @param $fields_settings
		 * @param $optin_action_settings
		 *
		 * @return array|bool|mixed
		 */
		public function handle_action( $posted_data, $fields_settings, $optin_action_settings ) {

			$posted_data = parent::handle_action( $posted_data, $fields_settings, $optin_action_settings );

			$optin_email = $this->get_optin_data( WFFN_Optin_Pages::WFOP_EMAIL_FIELD_SLUG );
			$first_name  = $this->get_optin_data( WFFN_Optin_Pages::WFOP_FIRST_NAME_FIELD_SLUG );
			$last_name   = $this->get_optin_data( WFFN_Optin_Pages::WFOP_LAST_NAME_FIELD_SLUG );

			/** Creating bwf_contact **/ global $current_user;
			$user_id = ( ! empty( $current_user->ID ) && ( $current_user->ID > 0 ) ) ? $current_user->ID : 0;

			$bwf_contact = ( function_exists( 'bwf_get_contact' ) ) ? bwf_get_contact( $user_id, $optin_email ) : new stdClass();
			if ( $bwf_contact instanceof WooFunnels_Contact && 0 === $bwf_contact->get_id() ) {
				$bwf_contact->set_status( 0 );
				if ( $user_id > 0 ) {
					$bwf_contact->set_email( $current_user->user_email );
				} else {
					$bwf_contact->set_email( $optin_email );
				}
			}

			if ( class_exists( 'WFFN_Optin_Form_Field_Phone' ) ) {
				$phone_no = $this->get_optin_data( WFFN_Optin_Pages::WFOP_PHONE_FIELD_SLUG );
				if ( ! empty( $phone_no ) ) {
					$bwf_contact->set_contact_no( $phone_no );
					if ( isset( $posted_data['wfop_optin_country'] ) && ! empty( $posted_data['wfop_optin_country'] ) ) {
						$bwf_contact->set_country( strtoupper( $posted_data['wfop_optin_country'] ) );
					}
				}
			}

			$bwf_contact->set_f_name( $first_name );
			$bwf_contact->set_l_name( $last_name );
			$bwf_contact->save( true );

			$form_data            = [];
			$current_step         = WFFN_Core()->data->get_current_step();
			$form_data['step_id'] = $current_step['id'];
			$funnel               = WFFN_Core()->data->get_session_funnel();
			if ( isset( $funnel->id ) && absint( $funnel->id ) ) {
				$form_data['funnel_id'] = $funnel->id;
			}
			$form_data['cid'] = $bwf_contact->get_id();

			if ( $posted_data[ WFFN_Optin_Pages::WFOP_EMAIL_FIELD_SLUG ] && $posted_data[ WFFN_Optin_Pages::WFOP_EMAIL_FIELD_SLUG ] !== '' ) {
				$form_data['email'] = $posted_data[ WFFN_Optin_Pages::WFOP_EMAIL_FIELD_SLUG ];

				$form_data['data'] = $this->before_submit_form( $form_data['step_id'], $posted_data );
				unset( $form_data['data'][ WFFN_Optin_Pages::WFOP_EMAIL_FIELD_SLUG ] );
				unset( $form_data['data']['optin_page_id'] );

				$get_hash          = WFFN_Core()->data->generate_transient_key();
				$form_data['opid'] = $get_hash;

				$last_id = WFFN_DB_Optin::get_instance()->insert_optin( $form_data );

				if ( is_numeric( $last_id ) && $last_id > 0 ) {
					WFFN_Core()->logger->log( 'Optin form save successfully: ' . print_r( $form_data['email'], true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

					$posted_data['opid'] = $form_data['opid'];
					$posted_data['optin_entry_id'] = $last_id;
					$bwf_obj = BWF_Optin_Tags::get_instance();
					$bwf_obj->maybe_set_optin( $posted_data['opid'] );

					WFFN_Core()->data->set( 'opid', $get_hash );
					WFFN_Core()->data->save();
				}
			}

			BWF_Contact_Tags::get_instance()->set_contact( $bwf_contact );
			$posted_data['cid'] = $bwf_contact->get_id();

			return $posted_data;
		}

		/**
		 * @param $optin_id
		 * @param $posted_data
		 *
		 * save custom field with label instead with advanced name
		 *
		 * @return mixed
		 */
		public function before_submit_form( $optin_id, $posted_data ) {

			$get_fields = WFOPP_Core()->optin_pages->form_builder->get_form_fields( $optin_id );
			foreach ( $get_fields as $field ) {
				if ( isset( $posted_data[ $field['InputName'] ] ) ) {
					$posted_data[ $field['label'] ] = $posted_data[ $field['InputName'] ];
					unset( $posted_data[ $field['InputName'] ] );

				}
			}

			return $posted_data;

		}

	}

	if ( class_exists( 'WFOPP_Core' ) ) {
		WFOPP_Core()->optin_actions->register( WFFN_Optin_Action_Create_BWF_Contact::get_instance() );
	}
}
