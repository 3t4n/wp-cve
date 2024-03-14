<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will be extended by all all single optin form controller(like Contact form 7, Gravity etc. etc) to register different form builders
 * Class WFFN_Optin_Action
 */
if ( ! class_exists( 'WFFN_Optin_Form_Controller' ) ) {
	#[AllowDynamicProperties]

 abstract class WFFN_Optin_Form_Controller {
		public $slug = '';

		/**
		 * WFFN_Optin_Form_Controller constructor.
		 */
		public function __construct() {


		}

		/**
		 * @return bool
		 */
		public function should_register() {
			return true;
		}

		/**
		 * Return title of form controller
		 */
		public function get_title() {
			return '';
		}

		/**
		 * Return all form list
		 */
		public function get_all_forms() {
			return '';
		}

		/**
		 * Get form type to open the form selection and mapping fields
		 * Group 1: form_builders: Forms created by form builder plugins (e.g. Gravity forms, WP Forms, Contact form 7, etc.)
		 * Group 2: page_builders: Forms created by page builder plugins (e.g. Elementor, Thrive etc.)
		 * Group 3: auto-responders: created by Auto responders (e.g. Active Campaign, MailChimp, WP Fusion, drip etc.)
		 * Group 4: custom_form : Created by our drag & drop fields
		 * @return string
		 */
		public function get_form_group() {
			return 'form_builders';
		}

		/**
		 * @param $posted_data
		 * @param $fields_settings
		 *
		 * @return bool|array
		 */
		public function handle_actions( $posted_data, $fields_settings, $optin_action_settings ) {
			$form_actions = WFOPP_Core()->optin_actions->get_supported_actions();

			foreach ( $form_actions as $form_action ) {
				if ( $form_action instanceof WFFN_Optin_Action ) {
					$posted_data = $form_action->handle_action( $posted_data, $fields_settings, $optin_action_settings );
				}
			}

			return $posted_data;
		}



		/**
		 * @return string|void
		 */
		public function get_form_shortcode() {
			return __( 'Optin is built by dragging the widget.', 'funnel-builder' );
		}

		/**
		 * Converting multidimensional posted data array to single dimensional data
		 *
		 * @param $posted_data
		 * @param null $output_data
		 *
		 * @return array|null
		 */
		public function get_parsed_posted_data( $posted_data, $output_data = null ) {
			$output_data = ( null === $output_data ) ? [] : $output_data;

			foreach ( $posted_data as $posted_key => $posted_datum ) {
				if ( is_array( $posted_datum ) ) {
					$output_data = $this->get_parsed_posted_data( $posted_datum, $output_data );
				} else {
					$output_data[ $posted_key ] = $posted_datum;
				}
			}

			return $output_data;
		}


		/**
		 * @return bool
		 */
		public function wffn_optin_form_output() {
			global $post;

			$optin_page_id = ( $post instanceof WP_Post ) ? $post->ID : 0;
			if ( $optin_page_id < 1 ) {
				return false;
			}

			$optin_settings = WFOPP_Core()->optin_pages->get_optin_form_integration_option( $optin_page_id );
			$optinPageId    = isset( $optin_settings['optinPageId'] ) ? $optin_settings['optinPageId'] : 0;
			if ( $optinPageId > 0 && intval( $optinPageId ) === intval( $optin_page_id ) ) {
				$form_builder = isset( $optin_settings['formBuilder'] ) ? $optin_settings['formBuilder'] : '';
				if ( empty( $form_builder ) ) {
					return false;
				}
				$form_controller = WFOPP_Core()->form_controllers->get_integration_object( $form_builder );
				if ( ! $form_controller instanceof WFFN_Optin_Form_Controller ) {
					return false;
				}

				if ( 'page_builders' === $form_controller->get_form_group() ) {
					return false;
				}
				$optinFormId = isset( $optin_settings['optinFormId'] ) ? $optin_settings['optinFormId'] : 0;
				if ( $optinFormId < 1 && 'auto_responders' !== $form_controller->get_form_group() && 'custom_form' !== $form_controller->get_form_group() ) {
					return false;
				}
				$shortcode = $form_controller->get_form_shortcode();

				if ( empty( $shortcode ) ) {
					return false;
				}
				ob_start();
				echo do_shortcode( $shortcode );

				return ob_get_clean();
			}

			return false;
		}

		public function wffn_recaptcha_response( $data ) {

			$db_options = WFOPP_Core()->optin_pages->get_option();
			$result     = array( 'success' => true );



			if ( $db_options['op_recaptcha'] !== 'true' ) {
				return $result;
			}

			if ( $db_options['op_recaptcha_secret'] === '' ) {
				return $result;
			}

			$msg = $db_options['op_recaptcha_msg'];

			$secretKey = $db_options['op_recaptcha_secret'];

			if ( isset( $data['wffn-captcha-response'] ) && ! empty( $data['wffn-captcha-response'] ) ) {
				// Get verify response data
				$verifyResponse = file_get_contents( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . wffn_clean( $data['wffn-captcha-response'] ) ); //phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData
				$responseData   = json_decode( $verifyResponse );

				if ( $responseData->success ) {
					WFFN_Core()->logger->log( "Optin form Recaptcha successfully applied" );  //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
				} else {
					WFFN_Core()->logger->log( "Optin form Recaptcha failed" );  //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
					$result['success'] = false;
				}
				$result['message'] = $msg;
			}

			return $result;
		}

	}
}
