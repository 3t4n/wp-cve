<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WFFN_REST_OPTIN_API_EndPoint' ) ) {
	class WFFN_REST_OPTIN_API_EndPoint extends WFFN_REST_Controller {

		private static $ins = null;
		protected $namespace = 'funnelkit-app';

		/**
		 * WFFN_REST_API_EndPoint constructor.
		 */
		public function __construct() {
			add_action( 'rest_api_init', [ $this, 'register_endpoint' ], 12 );
		}

		/**
		 * @return WFFN_REST_OPTIN_API_EndPoint|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function register_endpoint() {

			// Register routes for form fields.
			register_rest_route( $this->namespace, '/' . 'funnel-optin' . '/(?P<step_id>[\d]+)' . '/form_fields', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'save_form_fields' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_form_fields' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Register route for Add Optin Form field.
			register_rest_route( $this->namespace, '/' . 'funnel-optin' . '/(?P<step_id>[\d]+)' . '/form_fields' . '/add_field', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
						'required'    => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'add_op_field' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Register route for Remove Optin Form field.
			register_rest_route( $this->namespace, '/' . 'funnel-optin' . '/(?P<step_id>[\d]+)' . '/form_fields' . '/remove_field', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
						'required'    => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'remove_op_field' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Register routes for Optin Form Actions.
			register_rest_route( $this->namespace, '/' . 'funnel-optin' . '/(?P<step_id>[\d]+)' . '/actions', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_op_actions' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_op_actions' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Route for Testing Email Option.
			// Route to Search Pages.
			register_rest_route( $this->namespace, '/funnel-optin/testing_email', array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'testing_email' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'options' => array(
							'description'       => __( 'Notify Options', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );

		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		// Save form fields
		public function save_form_fields( WP_REST_Request $request ) {
			$resp                   = array();
			$resp['success']        = false;
			$resp['msg']            = __( 'Failed', 'funnel-builder' );
			$resp['data']['fields'] = array();

			$step_id = $request->get_param( 'step_id' );
			$fields  = $request->get_body();

			if ( absint( $step_id ) > 0 && ! empty( $fields ) ) {

				$data = $this->sanitize_custom( $fields, 1 );

				if ( empty( $data['steps'] ) || empty( $data['current_step'] ) || empty( $data['fieldsets'] ) ) {

					$step_layout = WFFN_Optin_Pages::get_instance()->get_page_layout( $step_id );
					unset( $step_layout['fieldsets']['single_step'][0]['fields'] );

					if ( empty( $step_layout['fieldsets']['two_step'] ) ) {
						unset( $step_layout['fieldsets']['two_step'] );
					}

					if ( empty( $step_layout['fieldsets']['third_step'] ) ) {
						unset( $step_layout['fieldsets']['third_step'] );
					}

					$fieldset = array();
					foreach ( $data as $field ) {

						if ( ! empty( $field['options'] ) ) {
							$option           = sanitize_title( $field['options'] );
							$field['options'] = [ $option => $field['options'] ];
						}

						if ( isset( $field['hint'] ) ) {
							unset( $field['hint'] );
						}
						// Unset Values.
						if ( isset( $field['values'] ) ) {
							unset( $field['values'] );
						}

						$fieldset[] = $field;
					}

					$step_layout['fieldsets']['single_step'][0]['fields'] = $fieldset;

					$data = $step_layout;

				}

				update_post_meta( $step_id, '_wfop_page_layout', $data );

				$resp['success']        = true;
				$resp['msg']            = __( 'Fields Updated', 'funnel-builder' );
				$resp['data']['fields'] = $this->sanitize_custom( $fields );

			}

			return rest_ensure_response( $resp );
		}

		// Get form fields
		public function get_form_fields( WP_REST_Request $request ) {
			$resp                   = array();
			$resp['success']        = false;
			$resp['msg']            = __( 'Failed', 'funnel-builder' );
			$resp['data']['fields'] = array();

			$step_id   = $request->get_param( 'step_id' );
			$funnel_id = $request->get_param( 'funnel_id' );

			wffn_rest_api_helpers()->maybe_step_not_exits( $step_id );

			if ( absint( $step_id ) > 0 ) {

				$resp['data']['step'] = wffn_rest_funnel_modules()->get_step_design( $step_id );

				$step_post = wffn_rest_api_helpers()->get_step_post( $step_id );

				if ( 0 === absint( $funnel_id ) ) {
					$funnel_id = get_post_meta( $step_id, '_bwf_in_funnel', true );

				}
				$resp['data']['funnel_data'] =  WFFN_REST_Funnels::get_instance()->get_funnel_data( $funnel_id );
				$resp['data']['step_data'] = $step_post;

				$fields    = WFFN_Optin_Pages::get_instance()->get_page_layout( $step_id );
				$op_fields = $this->format_op_form_fields( $fields, $step_id, false );

				$resp['success']             = true;
				$resp['msg']                 = __( 'Fields Loaded', 'funnel-builder' );
				$resp['data']['fields']      = $op_fields;
				$resp['data']['fields_data'] = $this->optin_fetch_fields( $step_id );

			}

			return rest_ensure_response( $resp );
		}

		// Add Optin Form field.
		public function add_op_field( WP_REST_Request $request ) {

			$resp = array(
				'msg'     => __( 'Failed', 'funnel-builder' ),
				'success' => false,
				'data'    => array(),
			);

			$step_id = $request->get_param( 'step_id' );
			$fields  = $request->get_body();

			if ( absint( $step_id ) > 0 && ! empty( $fields ) ) {

				$posted_data = $this->sanitize_custom( $fields );

				if ( is_array( $posted_data ) && ! empty( $posted_data['fields'] ) ) {

					$label        = ( isset( $posted_data['fields']['label'] ) ) ? stripslashes( wffn_clean( $posted_data['fields']['label'] ) ) : '';
					$placeholder  = ( isset( $posted_data['fields']['placeholder'] ) ) ? stripslashes( wffn_clean( $posted_data['fields']['placeholder'] ) ) : '';
					$width        = ! empty( $posted_data['fields']['width'] ) ? wffn_clean( $posted_data['fields']['width'] ) : 'wffn-sm-100';
					$field_type   = ! empty( $posted_data['fields']['field_type'] ) ? wffn_clean( $posted_data['fields']['field_type'] ) : 'text';
					$section_type = ( isset( $posted_data['fields']['section_type'] ) ) ? wffn_clean( $posted_data['fields']['section_type'] ) : '';
					$default      = ( isset( $posted_data['fields']['default'] ) ) ? stripslashes( wffn_clean( $posted_data['fields']['default'] ) ) : '';
					$options      = ( isset( $posted_data['fields']['options'] ) && ! empty( $posted_data['fields']['options'] ) ) ? ( explode( '|', trim( wffn_clean( $posted_data['fields']['options'] ) ) ) ) : array();
					$name         = apply_filters( 'wffn_optin_advanced_field_name', $section_type . '_' . WFFN_Common::generate_hash_key(), wffn_clean( $posted_data['fields'] ) );

					$new_sanitize_option = array();
					if ( is_array( $options ) && count( $options ) > 0 ) {
						foreach ( $options as $option ) {
							$key                         = sanitize_title( trim( $option ) );
							$new_sanitize_option[ $key ] = trim( $option );
						}
					}

					$required = ( isset( $posted_data['fields']['required'] ) ) ? wffn_clean( $posted_data['fields']['required'] ) : false;
					$data     = array(
						'label'       => $label,
						'placeholder' => $placeholder,
						'type'        => $field_type,
						'required'    => $required,
						'options'     => $new_sanitize_option,
						'default'     => $default,
						'width'       => $width,
					);

					if ( 'email' === $field_type ) {
						$data['validate'][] = 'email';
					}

					if ( 'radio' === $field_type ) {
						if ( ! empty( $posted_data['fields']['radio_alignment'] ) ) {
							$data['radio_alignment'] = ! empty( $posted_data['fields']['radio_alignment'] ) ? wffn_clean( $posted_data['fields']['radio_alignment'] ) : "";
						}
					}

					$custom_fields                           = WFFN_Optin_Pages::get_instance()->get_page_custom_fields( $step_id );
					$custom_fields[ $section_type ][ $name ] = $data;

					update_post_meta( $step_id, '_wfop_page_custom_field', $custom_fields );

					$data['id']         = $name;
					$data['unique_id']  = $name;
					$data['options']    = ! empty( $new_sanitize_option ) ? array_values( $new_sanitize_option )[0] : [];
					$data['field_type'] = $section_type;
					$resp['success']    = true;
					$resp['data']       = $data;
					$resp['msg']        = __( 'Field Added Saved', 'funnel-builder' );
				}
			}

			return rest_ensure_response( $resp );
		}

		// Remove Optin Form Field.
		public function remove_op_field( WP_REST_Request $request ) {

			$resp = array(
				'msg'     => __( 'Failed', 'funnel-builder' ),
				'success' => false,
			);

			$step_id = $request->get_param( 'step_id' );
			$fields  = $request->get_body();

			if ( absint( $step_id ) > 0 && ! empty( $fields ) ) {

				$posted_data = $this->sanitize_custom( $fields );
				if ( is_array( $posted_data ) && ! empty( $posted_data ) ) {

					$section_type = '';
					$index        = 0;

					if ( isset( $posted_data['section'] ) && ! empty( $posted_data['section'] ) ) {
						$section_type = wffn_clean( $posted_data['section'] );
					}

					if ( isset( $posted_data['index'] ) && ! empty( $posted_data['index'] ) ) {
						$index = wffn_clean( $posted_data['index'] );
					}

					if ( empty( $index ) ) {
						return rest_ensure_response( $resp );
					}

					$custom_fields = WFFN_Optin_Pages::get_instance()->get_page_custom_fields( $step_id );

					if ( isset( $custom_fields[ $section_type ] ) && isset( $custom_fields[ $section_type ][ $index ] ) ) {
						unset( $custom_fields[ $section_type ][ $index ] );
						update_post_meta( $step_id, '_wfop_page_custom_field', $custom_fields );
						$resp['success'] = true;
						$resp['msg']     = __( 'Field Deleted', 'funnel-builder' );
					}
				}
			}

			return rest_ensure_response( $resp );
		}


		// Save Optin Actions.
		public function update_op_actions( WP_REST_Request $request ) {

			$resp                    = array();
			$resp['success']         = false;
			$resp['msg']             = __( 'Failed', 'funnel-builder' );
			$resp['data']['actions'] = array();

			$step_id = $request->get_param( 'step_id' );
			$data    = $request->get_body();

			if ( absint( $step_id ) > 0 && ! empty( $data ) ) {

				$posted_data = $this->sanitize_custom( $data, 1 );

				if ( ! is_array( $posted_data ) && empty( $posted_data['actions'] ) ) {
					return rest_ensure_response( $resp );
				}

				$options    = ! empty( $posted_data['actions'] ) ? $this->sanitize_custom( $posted_data['actions'], 1 ) : 0;
				$test_email = ( isset( $posted_data['email_testing'] ) ) ? wffn_clean( $posted_data['email_testing'] ) : false;

				if ( 'true' === $test_email ) {
					if ( empty( $options['test_email'] ) ) {
						$resp['msg'] = __( 'Kindly provide the test email id.', 'funnel-builder' );

						return rest_ensure_response( $resp );
					}
					$resp = WFFN_Optin_Pages::get_instance()->testing_email( $options, $options['test_email'] );
				} else {
					if ( ! empty( $options['test_email'] ) ) {
						unset( $options['test_email'] );
					}
					$service_form = isset( $options['optin_service_form'] ) ? $options['optin_service_form'] : [];  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					$optin_data   = array();
					if ( ! empty( $service_form['fields'] ) ) {
						$optin_data['optin_form_enable'] = isset( $service_form['optin_form_enable'] ) ? $service_form['optin_form_enable'] : 'false';
						$optin_data['formBuilder']       = isset( $service_form['formBuilder'] ) ? $service_form['formBuilder'] : '';
						$optin_data['fields']            = $service_form['fields'];
						$optin_data['formFields']        = $service_form['formFields'];
						WFFN_Core()->logger->log( 'Form connection settings: ' . print_r( $service_form, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
					}

					$options['optin_service_form'] = $optin_data;

					if ( ! empty( $posted_data['lead_notification_body'] ) ) {
						$options['lead_notification_body'] = preg_replace( '/(\v|\s)+/', ' ', $posted_data['lead_notification_body'] );
					}

					if ( ! empty( $options['assign_ld_course'] ) ) {
						$options['assign_ld_course'] = wffn_clean( $options['assign_ld_course'] );
					}

					if ( ! empty( $options['assign_lifter_course'] ) ) {
						$options['assign_lifter_course'] = wffn_clean( $options['assign_lifter_course'] );
					}

					$options['affiliatewp_id'] = ! empty( $options['affiliatewp_id'] ) ? $options['affiliatewp_id'] : false;

					if ( is_array( $options ) ) {
						$options = apply_filters( 'wffn_update_optin_actions_settings', $options, $step_id );
						update_post_meta( $step_id, 'wffn_actions_custom_settings', $options );
					}

					$resp['success'] = true;
					$resp['msg']     = __( 'Settings Updated', 'funnel-builder' );
					$resp['data']    = '';
				}
			}

			return rest_ensure_response( $resp );
		}

		// Get Optin Actions.
		public function get_op_actions( WP_REST_Request $request ) {
			$resp                    = array();
			$resp['success']         = false;
			$resp['msg']             = __( 'Failed', 'funnel-builder' );
			$resp['data']['actions'] = array();

			$step_id   = $request->get_param( 'step_id' );
			$funnel_id = $request->get_param( 'funnel_id' );

			wffn_rest_api_helpers()->maybe_step_not_exits( $step_id );

			if ( absint( $step_id ) > 0 ) {

				$resp['data']['step'] = wffn_rest_funnel_modules()->get_step_design( $step_id );
				$step_post = wffn_rest_api_helpers()->get_step_post( $step_id);

				if ( 0 === absint( $funnel_id ) ) {
					$funnel_id = get_post_meta( $step_id, '_bwf_in_funnel', true );

				}
				$resp['data']['funnel_data'] =  WFFN_REST_Funnels::get_instance()->get_funnel_data( $funnel_id );
				$resp['data']['step_data'] = $step_post;

				$db_actions = get_post_meta( $step_id, 'wffn_actions_custom_settings', true );

				$db_actions      = ( ! empty( $db_actions ) && is_array( $db_actions ) ) ? $db_actions : array();
				$action_options  = wp_parse_args( $db_actions, WFFN_Optin_Pages::get_instance()->default_actions_settings() );
				$resp['success'] = true;
				$resp['msg']     = __( 'Actions Loaded', 'funnel-builder' );

				$op_fields = WFFN_Optin_Pages::get_instance()->get_page_layout( $step_id );

				if ( ! empty( $op_fields['fieldsets']['single_step'][0]['fields'] ) ) {
					$action_options['form_fields'] = $op_fields['fieldsets']['single_step'][0]['fields'];
				} else {
					$action_options['form_fields'] = WFFN_Optin_Pages::get_instance()->get_default_steps_fields();
				}

				$data_actions = $this->get_action_tabs( $action_options, $step_id );

				$resp['data']['actions'] = $data_actions;
			}

			return rest_ensure_response( $resp );
		}

		public function testing_email( WP_REST_Request $request ) {
			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'Mail could not be sent', 'funnel-builder' );

			$options = $request->get_param( 'options' );

			if ( ! empty( $options ) ) {

				$posted_data = json_decode( $options, 1 );

				if ( empty( $posted_data['notify_subject'] ) || empty( $posted_data['notify_body'] ) || empty( $posted_data['notify_email'] ) ) {
					$resp['msg'] = __( 'Invalid mail fields', 'funnel-builder' );

					return rest_ensure_response( $resp );
				}

				$response = $this->test_email( $posted_data );

				if ( true === $response['success'] ) {
					$resp['success'] = true;
					$resp['msg']     = __( 'Mail sent successfully', 'funnel-builder' );
				}
			}

			return rest_ensure_response( $resp );
		}

		public function test_email( $option ) {
			$db_options  = WFOPP_Core()->optin_pages->get_option();
			$optin_email = $option['notify_email'];
			$content     = apply_filters( 'the_content', $option['notify_body'] );

			$modified_content = WFFN_Common::modify_content_emogrifier( $content );
			if ( ! empty( $modified_content ) ) {
				$content = $modified_content;
			}

			$subject  = do_shortcode( $option['notify_subject'] );
			$result   = WFFN_Optin_Action_User_Email::get_instance()->trigger_email( $optin_email, $subject, $db_options['op_user_name'], $db_options['op_user_email'], $db_options['op_user_email_reply'], $content );
			$response = [ 'success' => true ];

			if ( ! $result ) {
				$response['success'] = false;
			}

			return $response;
		}

		public function format_op_form_fields( $fields, $step_id, $skip_field_switch = false, $set_options = false ) {

			$field_sets = $fields['fieldsets'];
			if ( is_array( $field_sets ) ) {
				$options = [];
				foreach ( $field_sets as $_key => $field_set ) {
					foreach ( $field_set as $_fields => $fields ) {
						$key_array = array();
						$op_fields = $fields['fields'];
						foreach ( $op_fields as $_field => $field ) {
							$values     = '';
							$key_fields = array();

							if ( ! empty( $field['options'] ) && is_array( $field['options'] ) ) {
								$options = $field['options'];
								if ( is_array( $options ) ) {
									if ( true === $set_options ) {
										$options = wffn_rest_api_helpers()->format_fields_options( $options, ',', true );
									} else {
										$options = wffn_rest_api_helpers()->format_fields_options( $options, ',' );
									}
								}
							}

							$required = ! empty( $field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['required'] ) ? wffn_string_to_bool( $field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['required'] ) : false;

							$field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['options']  = $options;
							$field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['required'] = $required;
							$field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['key']      = $field['id'];
							$field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['values']   = $values;
							$key_array[ $field['id'] ]                                        = ! empty( $field['default'] ) ? wffn_clean( $field['default'] ) : '';

							if ( 'radio' === $field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['type'] ) {
								$field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['radio_alignment'] = ! empty( $field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['radio_alignment'] ) ? wffn_clean( $field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['radio_alignment'] ) : 'horizontal';
							}

							if ( 'tel' === $field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['type'] ) {
								$field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['phone_validation'] = ! empty( $field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['phone_validation'] ) ? bwf_string_to_bool( $field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['phone_validation'] ) : false;
							}

							if ( empty( $field['field_type'] ) ) {
								$field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['field_type'] = $this->get_field_type( $field['id'], $step_id );
							}


							$required_field = $field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['required'];

							if ( true === $skip_field_switch ) {
								if ( true === $required_field || true === wffn_string_to_bool( $required_field ) ) {
									$field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['hint']  = $field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['label'];
									$field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['label'] = '';
								} else {
									$field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['hint'] = '';
								}
							} else {
								$field_sets[ $_key ][ $_fields ]['fields'][ $_field ]['hint'] = '';
							}

							$key_fields[] = $key_array;
						}
						$field_sets[ $_key ][ $_fields ]['values'] = $key_array;
					}
				}
			}

			return $field_sets;
		}

		public function format_op_basic_fields( $fields ) {
			$advanced_fields = array();
			if ( is_array( $fields ) && ! empty( $fields ) ) {
				$basic = $fields['basic'];
				foreach ( $basic as $op_key => $field ) {
					$field['id']         = $op_key;
					$field['unique_id']  = $op_key;
					$field['field_type'] = 'basic';
					$advanced_fields[]   = $field;
				}
			}

			return $advanced_fields;
		}

		public function format_op_advanced_fields( $fields ) {
			$advanced_fields = array();
			if ( is_array( $fields ) && ! empty( $fields ) ) {
				$advanced = $fields['advanced'];
				foreach ( $advanced as $op_key => $field ) {
					$field['id']         = $op_key;
					$field['unique_id']  = $op_key;
					$field['field_type'] = 'advanced';
					if ( ! empty( $field['options'] ) ) {
						$field['options'] = WFFN_REST_API_Helpers::get_instance()->format_fields_options( $field['options'] );
					}
					$advanced_fields[] = $field;
				}
			}

			return $advanced_fields;
		}

		public function get_field_type( $field_name, $step_id ) {

			$fields = $this->optin_fetch_fields( $step_id );

			$basic_json    = wp_json_encode( $fields['basic'] );
			$advanced_json = wp_json_encode( $fields['advanced'] );

			if ( strpos( $basic_json, $field_name, ) ) {
				return 'basic';
			}

			if ( strpos( $advanced_json, $field_name ) ) {
				return 'advanced';
			}

			return '';

		}

		public function optin_fetch_fields( $step_id ) {
			$fields = array();

			if ( absint( $step_id ) ) {

				$advanced_fields    = WFFN_Optin_Pages::get_instance()->get_page_custom_fields( $step_id );
				$fields             = WFFN_Optin_Pages::get_instance()->get_optin_fields();
				$fields['basic']    = $this->format_op_basic_fields( $fields );
				$fields['advanced'] = $this->format_op_advanced_fields( $advanced_fields );

			}

			return $fields;
		}

		public function sanitize_custom( $data, $skip_clean = 0 ) {
			$data = json_decode( $data, true );

			if ( 0 === $skip_clean ) {
				return wffn_clean( $data );
			}

			return $data;
		}

		public function get_action_tabs( $values, $step_id ) {

			$lms_active       = $lifterlms_active = false;
			$learndash_course = $lifterlms_course = "";

			$lifterlms_hint   = __( 'Note: LifterLMS plugin needs to be activated to enable integration.', 'funnel-builder' );
			$lms_hint         = __( 'Note: Learndash plugin needs to be activated to enable integration.', 'funnel-builder' );
			$affiliatewp_hint = __( 'Note: AffiliateWP plugin needs to be activated to enable integration.', 'funnel-builder' );

			$is_pro_active = WFFN_Common::wffn_is_funnel_pro_active();

			$affiliatewp_active = wffn_is_plugin_active( 'affiliate-wp/affiliate-wp.php' );

			if ( class_exists( 'WFFN_Optin_Action_Assign_LIFTER_Course' ) ) {
				$lifterlms_obj = WFOPP_Core()->optin_actions->get_integration_object( WFFN_Optin_Action_Assign_LIFTER_Course::get_slug() );
				if ( $lifterlms_obj instanceof WFFN_Optin_Action ) {
					if ( $lifterlms_obj->should_register() ) {
						$lifterlms_active = true;
					}
				}
			}

			if ( class_exists( 'WFFN_Optin_Action_Assign_LD_Course' ) ) {
				$lms_obj = WFOPP_Core()->optin_actions->get_integration_object( WFFN_Optin_Action_Assign_LD_Course::get_slug() );
				if ( $lms_obj instanceof WFFN_Optin_Action ) {
					if ( $lms_obj->should_register() ) {
						$lms_active = true;
					}
				}
			}

			$notify_fields = array(
				'lead_enable_notify'        => ! empty( $values['lead_enable_notify'] ) ? wffn_clean( $values['lead_enable_notify'] ) : 'false',
				'lead_notification_subject' => ! empty( $values['lead_notification_subject'] ) ? wffn_clean( $values['lead_notification_subject'] ) : '',
				'lead_notification_body'    => ! empty( $values['lead_notification_body'] ) ? preg_replace( '/(\v|\s)+/', ' ', $values['lead_notification_body'] ) : '',
				'test_email'                => ! empty( $values['test_email'] ) ? wffn_clean( $values['test_email'] ) : '',
				'admin_email_notify'        => ! empty( $values['admin_email_notify'] ) ? wffn_clean( $values['admin_email_notify'] ) : 'false',
				'op_admin_email'            => ! empty( $values['op_admin_email'] ) ? wffn_clean( $values['op_admin_email'] ) : '',
			);

			$crm_fields = array(
				'optin_form_enable'  => ! empty( $values['optin_service_form']['optin_form_enable'] ) ? $values['optin_service_form']['optin_form_enable'] : 'false',
				'optin_form_builder' => ! empty( $values['optin_service_form']['formBuilder'] ) ? $values['optin_service_form']['formBuilder'] : 'select-services',
				'optin_service_form' => ! empty( $values['optin_service_form'] ) ? wffn_clean( $values['optin_service_form'] ) : '',
				'is_pro_active'      => $is_pro_active,
				'pro_inactive_label' => __( 'Get pro to enable CRM integration.', 'funnel-builder' ),
				'is_pro'			 => true,
			);

			$webhook_fields = array(
				'op_webhook_enable' => ! empty( $values['op_webhook_enable'] ) ? $values['op_webhook_enable'] : 'false',
				'op_webhook_url'    => ! empty( $values['op_webhook_url'] ) ? ( $values['op_webhook_url'] ) : '',
			);

			if ( isset( $values['lms_course'] ) && true === wffn_string_to_bool( $values['lms_course'] ) ) {
				$learndash_course = ! empty( $values['assign_ld_course']['id'] ) ? array(
					'id'   => $values['assign_ld_course']['id'],
					'name' => html_entity_decode( get_the_title( $values['assign_ld_course']['id'] ) )
				) : '';
			}

			if ( isset( $values['lifterlms_course'] ) && true === wffn_string_to_bool( $values['lifterlms_course'] ) ) {
				$lifterlms_course = ! empty( $values['assign_lifter_course']['id'] ) ? array(
					'id'   => $values['assign_lifter_course']['id'],
					'name' => html_entity_decode( get_the_title( $values['assign_lifter_course']['id'] ) )
				) : '';
			}

			$affiliatewp_fields = array(
				'affiliatewp_id'       => ! empty( $values['affiliatewp_id'] ) ? $values['affiliatewp_id'] : 'false',
				'is_pro_active'        => $is_pro_active,
				'pro_inactive_label'   => __( 'Get pro to enable Affiliate WP integration.', 'funnel-builder' ),
				'is_plugin_active'     => $affiliatewp_active,
				'plugin_inactive_hint' => $affiliatewp_hint,
				'is_pro'			   => true,
			);

			$learndash_fields = array(
				'lms_course'           => ! empty( $values['lms_course'] ) ? $values['lms_course'] : 'false',
				'assign_ld_course'     => ! empty( $values['assign_ld_course'] ) ? wffn_clean( $learndash_course ) : '',
				'is_pro_active'        => $is_pro_active,
				'pro_inactive_label'   => __( 'Get pro to enable Learndash integration.', 'funnel-builder' ),
				'is_plugin_active'     => $lms_active,
				'plugin_inactive_hint' => $lms_hint,
				'is_pro'			   => true,
			);

			$lifterlms_fields = array(
				'lifterlms_course'     => ! empty( $values['lifterlms_course'] ) ? $values['lifterlms_course'] : 'false',
				'assign_lifter_course' => ! empty( $values['assign_lifter_course'] ) ? wffn_clean( $lifterlms_course ) : '',
				'is_pro_active'        => $is_pro_active,
				'pro_inactive_label'   => __( 'Get pro to enable Learndash integration.', 'funnel-builder' ),
				'is_plugin_active'     => $lifterlms_active,
				'plugin_inactive_hint' => $lifterlms_hint,
				'is_pro'			   => true,
			);

			$action_builder_services = array(
				'select-services' => __( 'Select Services', 'funnel-builder' ),
				'active-campaign' => __( 'ActiveCampaign', 'funnel-builder' ),
				'drip'            => __( 'Drip', 'funnel-builder' ),
				'convert-git'     => __( 'ConvertKit', 'funnel-builder' ),
				'infusion-soft'   => __( 'InfusionSoft', 'funnel-builder' ),
				'mailchimp'       => __( 'Mailchimp', 'funnel-builder' ),
				'madmimi'         => __( 'Mad Mimi', 'funnel-builder' ),
				'raw_html'        => __( 'Raw HTML', 'funnel-builder' ),
			);

			$localization_data = WFFN_Optin_Pages::get_instance()->localize_action_data();
			$lms_hint          = $localization_data['action_fileld']['email_notify']['fields']['lead_notification_body']['hint'];

			$form_builders_optin = array();

			foreach ( $action_builder_services as $key => $_services ) {
				$form_builders_optin[] = array( 'value' => $key, 'label' => $_services );
			}

			// Hide Select Services from List.
			unset( $action_builder_services['select-services'] );

			$tabs = [
				'notifications' => [
					'title'    => __( 'Notifications', 'funnel-builder' ),
					'heading'  => __( 'Email Notification', 'funnel-builder' ),
					'slug'     => 'notifications',
					'fields'   => [
						0 => [
							'type'   => 'radios',
							'key'    => 'lead_enable_notify',
							'label'  => __( 'Optin Notification', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
						],
						1 => [
							'type'    => 'input',
							'key'     => 'lead_notification_subject',
							'label'   => __( 'Subject', 'funnel-builder' ),
							'hint'    => '',
							'toggler' => [
								'key'   => 'lead_enable_notify',
								'value' => 'true',
							],
						],
						2 => [
							'type'    => 'text-editor',
							'key'     => 'lead_notification_body',
							'label'   => __( 'Body', 'funnel-builder' ),
							'hint'    => $lms_hint,
							'toggler' => [
								'key'   => 'lead_enable_notify',
								'value' => 'true',
							],
						],
						3 => [
							'type'        => 'input',
							'key'         => 'test_email',
							'label'       => __( 'Test Email', 'funnel-builder' ),
							'placeholder' => __( 'Enter your email to test', 'funnel-builder' ),
							'hint'        => '',
							'toggler'     => [
								'key'   => 'lead_enable_notify',
								'value' => 'true',
							],
						],
						4 => [
							'type'        => 'input_button',
							'key'         => 'btn_test_email',
							'label'       => __( 'Test Email', 'funnel-builder' ),
							'hint'        => '',
							'apiEndPoint' => '/funnel-optin/testing_email',
							'data_fields' => [
								'notify_subject' => 'lead_notification_subject',
								'notify_body'    => 'lead_notification_body',
								'notify_email'   => 'test_email',
							],
							'toggler'     => [
								'key'   => 'lead_enable_notify',
								'value' => 'true',
							],
						],
						5 => [
							'type'   => 'radios',
							'key'    => 'admin_email_notify',
							'label'  => __( 'Admin Notification', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
						],
						6 => [
							'type'    => 'input',
							'key'     => 'op_admin_email',
							'label'   => __( 'Email', 'funnel-builder' ),
							'hint'    => __( 'Enter comma separated email IDs for multiple emails', 'funnel-builder' ),
							'toggler' => [
								'key'   => 'admin_email_notify',
								'value' => 'true',
							],
						],
					],
					'priority' => 5,
					'values'   => $notify_fields,
				],
				'crm'           => [
					'title'    => __( 'CRM', 'funnel-builder' ),
					'heading'  => __( 'CRM', 'funnel-builder' ),
					'slug'     => 'crm',
					'fields'   => [
						0 => [
							'type'   => 'radios',
							'key'    => 'optin_form_enable',
							'label'  => __( 'Enable Integration', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
						],
						1 => [
							'type'    => 'select',
							'key'     => 'optin_form_builder',
							'label'   => __( 'Send contacts to', 'funnel-builder' ),
							'hint'    => '',
							'toggler' => [
								'key'   => 'optin_form_enable',
								'value' => 'true',
							],
							'options' => $form_builders_optin,
						],
						2 => [
							'type'    => 'input_button_reset',
							'key'     => 'wffn_crm_reset',
							'label'   => __( 'Reset', 'funnel-builder' ),
							'hint'    => '',
							'toggler' => [
								'key'   => 'optin_service_form',
								'value' => 'true',
							]
						],
						3 => [
							'type'        => 'check_service',
							'key'         => 'optin_service_form',
							'label'       => '',
							'placeholder' => __( 'Paste form embed code here.', 'funnel-builder' ),
							'hint'        => '',
							'toggler'     => [
								'key'   => 'optin_form_builder',
								'value' => array_keys( $action_builder_services ),
							],
							'options'     => $values['form_fields'],
						],
						4 => [
							'type'    => 'input_button_continue',
							'key'     => 'wffn_crm_continue',
							'class'   => 'wffn-disabled',
							'label'   => __( 'Continue', 'funnel-builder' ),
							'hint'    => '',
							'toggler' => [
								'key'   => 'optin_service_form',
								'value' => 'true',
							]
						]
					],
					'priority' => 10,
					'values'   => $crm_fields,
				],
				'webhook'       => [
					'title'    => __( 'Webhook', 'funnel-builder' ),
					'heading'  => __( 'Webhook', 'funnel-builder' ),
					'slug'     => 'webhook',
					'fields'   => [
						0 => [
							'type'   => 'radios',
							'key'    => 'op_webhook_enable',
							'label'  => __( 'Enable', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
						],
						1 => [
							'type'        => 'url',
							'key'         => 'op_webhook_url',
							'label'       => __( 'Webhook URL', 'funnel-builder' ),
							'placeholder' => __( 'Enter Webhook URL', 'funnel-builder' ),
							'hint'        => '',
							'toggler'     => [
								'key'   => 'op_webhook_enable',
								'value' => 'true',
							],
						],
					],
					'priority' => 20,
					'values'   => $webhook_fields,
				],
				'learndash'     => [
					'title'    => __( 'Learndash', 'funnel-builder' ),
					'heading'  => __( 'Assign Course', 'funnel-builder' ),
					'slug'     => 'learndash',
					'fields'   => [
						0 => [
							'type'   => 'radios',
							'key'    => 'lms_course',
							'label'  => __( 'LMS Course', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
						],
						1 => [
							'type'        => 'custom-select',
							'key'         => 'assign_ld_course',
							'placeholder' => __( 'Select Course', 'funnel-builder' ),
							'apiEndPoint' => '/funnels/pages/search?pages=learndash',
							'label'       => __( 'Select Course', 'funnel-builder' ),
							'hint'        => '',
							'hintLabel'   => __( 'Enter minimum 3 letters.', 'funnel-builder' ),
							'toggler'     => [
								'key'   => 'lms_course',
								'value' => 'true',
							],
							'values'      => $learndash_course,
						],
					],
					'priority' => 15,
					'values'   => $learndash_fields,
				],
				'lifterlms'     => [
					'title'    => __( 'Lifter LMS', 'funnel-builder' ),
					'heading'  => __( 'Assign Course', 'funnel-builder' ),
					'slug'     => 'lifterlms',
					'fields'   => [
						0 => [
							'type'   => 'radios',
							'key'    => 'lifterlms_course',
							'label'  => __( 'LMS Course', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
						],
						1 => [
							'type'        => 'custom-select',
							'key'         => 'assign_lifter_course',
							'placeholder' => __( 'Select Course', 'funnel-builder' ),
							'apiEndPoint' => '/funnels/pages/search?pages=lifter_lms',
							'label'       => __( 'Select Course', 'funnel-builder' ),
							'hint'        => '',
							'hintLabel'   => __( 'Enter minimum 3 letters.', 'funnel-builder' ),
							'toggler'     => [
								'key'   => 'lifterlms_course',
								'value' => 'true',
							],
							'values'      => $lifterlms_course,
						],
					],
					'priority' => 25,
					'values'   => $lifterlms_fields,
				],
				'affiliatewp'   => [
					'title'    => __( 'AffiliateWP', 'funnel-builder' ),
					'heading'  => __( 'AffiliateWP', 'funnel-builder' ),
					'slug'     => 'affiliatewp_id',
					'fields'   => [
						0 => [
							'type'   => 'radios',
							'key'    => 'affiliatewp_id',
							'label'  => __( 'Enable', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
						],
					],
					'priority' => 35,
					'values'   => $affiliatewp_fields,
				],
			];

			return apply_filters( 'wfopp_default_actions_args', $tabs, $values, $step_id, $localization_data );
		}
	}

	WFFN_REST_OPTIN_API_EndPoint::get_instance();
}