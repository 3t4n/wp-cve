<?php

if ( ! bwfan_is_autonami_pro_active() && ! class_exists( 'BWFAN_Funnel_Optin_Form_Submit' ) ) {
	#[AllowDynamicProperties]
	final class BWFAN_Funnel_Optin_Form_Submit extends BWFAN_Event {
		private static $instance = null;
		public $form_id = 0;
		public $form_title = '';
		public $fields = [];
		public $email = '';
		public $first_name = '';
		public $last_name = '';
		public $contact_phone = '';
		public $mark_subscribe = false;
		/**
		 * @var int
		 */
		public $cid = 0;

		private function __construct() {
			$this->event_merge_tag_groups = array( 'optinforms', 'bwf_contact' );
			$this->event_name             = esc_html__( 'Form Submits', 'wp-marketing-automations' );
			$this->event_desc             = esc_html__( 'This event runs after a form is submitted', 'wp-marketing-automations' );
			$this->event_rule_groups      = array(
				'optinforms',
				'bwf_contact_segments',
				'bwf_contact',
				'bwf_contact_fields',
				'bwf_contact_user',
				'bwf_contact_wc',
				'bwf_contact_geo',
				'bwf_engagement',
				'bwf_broadcast'
			);
			$this->optgroup_label         = esc_html__( 'Optin Form', 'wp-marketing-automations' );
			$this->priority               = 10;
			$this->customer_email_tag     = '';
			$this->v2                     = true;
			$this->support_v1             = false;
			$this->optgroup_priority      = 20;
		}

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function load_hooks() {
			add_action( 'wp_ajax_bwfan_get_optin_form_fields', array( $this, 'bwfan_get_optin_form_fields' ) );
			add_action( 'wffn_optin_form_submit', array( $this, 'process' ), 999, 2 );
		}

		public function get_view_data() {
			$options = [];

			$args = array(
				'post_type'      => 'wffn_optin',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			);

			$optin_forms = new WP_Query( $args );
			if ( ! empty( $optin_forms->posts ) ) {
				foreach ( $optin_forms->posts as $form ) {
					$funnel_id            = get_post_meta( $form->ID, '_bwf_in_funnel', true );
					$options[ $form->ID ] = $form->post_title;
					if ( absint( $funnel_id ) > 0 ) {
						$funnel = new WFFN_Funnel( $funnel_id );
						if ( ! ( $funnel instanceof WFFN_Funnel ) && $funnel_id !== $funnel->get_id() && empty( $funnel->get_title() ) ) {
							continue;
						}
						$options[ $form->ID ] .= ! empty( $funnel->get_title() ) ? ' (' . $funnel->get_title() . ')' : '( no title  )';
						$options[ $form->ID ] = str_replace( '((', '(', $options[ $form->ID ] );
						$options[ $form->ID ] = str_replace( '))', ')', $options[ $form->ID ] );
					}
				}
			}

			return $options;
		}

		public function bwfan_get_optin_form_fields() {
			$form_id = filter_input( INPUT_POST, 'id' );
			$fields  = [];
			if ( ! empty( $form_id ) ) {
				$fields = $this->get_form_fields( $form_id );
			}

			/** fields for v2 */
			$fromApp = filter_input( INPUT_POST, 'fromApp' );
			if ( ! empty( $fromApp ) ) {
				$final_arr = [];
				foreach ( $fields as $key => $value ) {
					$final_arr[] = [
						'key'   => $key,
						'value' => $value
					];
				}

				wp_send_json( array(
					'results' => $final_arr
				) );
			}

			wp_send_json( array(
				'fields' => $fields,
			) );
		}

		public function get_form_fields( $form_id ) {
			if ( empty( $form_id ) ) {
				return array();
			}
			if ( ! function_exists( 'WFOPP_Core' ) ) {
				return array();
			}

			$form_fields = WFOPP_Core()->optin_pages->form_builder->get_optin_layout( $form_id );
			if ( empty( $form_fields ) ) {
				return array();
			}

			$fields = array();
			foreach ( $form_fields as $step_field ) {
				/** Empty step fields than continue */
				if ( empty( $step_field ) ) {
					continue;
				}
				foreach ( $step_field as $field ) {
					$fields[ $field['InputName'] ] = $field['label'];
				}
			}

			return $fields;
		}

		public function process( $optin_page_id, $posted_data ) {
			$data               = $this->get_default_data();
			$data['form_id']    = $optin_page_id;
			$data['fields']     = $this->get_submitted_form_values( $optin_page_id, $posted_data );
			$data['form_title'] = get_the_title( $optin_page_id );

			if ( isset( $posted_data['cid'] ) && ! empty( $posted_data['cid'] ) ) {
				$data['cid'] = absint( $posted_data['cid'] );
			}
			$this->send_async_call( $data );
		}

		/**
		 * @param $form_id
		 *
		 * @return array
		 */
		public function get_submitted_form_values( $form_id, $posted_data ) {
			$fields = $this->get_form_fields( $form_id );
			$fields = array_keys( $fields );
			$data   = [];
			foreach ( $fields as $field ) {
				if ( false !== strpos( $field, 'wfop_' ) ) {
					$fieldname      = str_replace( 'wfop_', '', $field );
					$data[ $field ] = isset( $posted_data[ $fieldname ] ) ? $posted_data[ $fieldname ] : '';
				} else {
					$data[ $field ] = isset( $_REQUEST[ $field ] ) ? $_REQUEST[ $field ] : '';
				}
			}

			return $data;
		}

		public function get_user_id_event() {
			if ( is_email( $this->email ) ) {
				$user = get_user_by( 'email', $this->email );

				return ( $user instanceof WP_User ) ? $user->ID : false;
			}

			return false;
		}

		public function get_event_data() {
			$data_to_send                         = [];
			$data_to_send['global']['form_id']    = $this->form_id;
			$data_to_send['global']['form_title'] = $this->form_title;
			$data_to_send['global']['fields']     = $this->fields;
			$data_to_send['global']['email']      = $this->email;

			return $data_to_send;
		}

		public function get_email_event() {
			return is_email( $this->email ) ? $this->email : false;
		}

		/**
		 * v2 Method: Validate event settings
		 *
		 * @param $automation_data
		 *
		 * @return bool
		 */
		public function validate_v2_event_settings( $automation_data ) {
			if ( absint( $automation_data['form_id'] ) !== absint( $automation_data['event_meta']['bwfan-optin_form_submit_form_id'] ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Capture the async data for the current event.
		 * @return array|bool
		 */
		public function capture_v2_data( $automation_data ) {
			$map_fields     = isset( $automation_data['event_meta']['bwfan-form-field-map'] ) ? $automation_data['event_meta']['bwfan-form-field-map'] : [];
			$email_map      = isset( $map_fields['bwfan_email_field_map'] ) ? $map_fields['bwfan_email_field_map'] : '';
			$first_name_map = isset( $map_fields['bwfan_first_name_field_map'] ) ? $map_fields['bwfan_first_name_field_map'] : '';
			$last_name_map  = isset( $map_fields['bwfan_last_name_field_map'] ) ? $map_fields['bwfan_last_name_field_map'] : '';
			$phone_map      = isset( $map_fields['bwfan_phone_field_map'] ) ? $map_fields['bwfan_phone_field_map'] : '';

			$this->form_id        = BWFAN_Common::$events_async_data['form_id'];
			$this->form_title     = BWFAN_Common::$events_async_data['form_title'];
			$this->fields         = BWFAN_Common::$events_async_data['fields'];
			$this->email          = ( ! empty( $email_map ) && isset( $this->fields[ $email_map ] ) && is_email( $this->fields[ $email_map ] ) ) ? $this->fields[ $email_map ] : '';
			$this->first_name     = ( ! empty( $first_name_map ) && isset( $this->fields[ $first_name_map ] ) ) ? $this->fields[ $first_name_map ] : '';
			$this->last_name      = ( ! empty( $last_name_map ) && isset( $this->fields[ $last_name_map ] ) ) ? $this->fields[ $last_name_map ] : '';
			$this->contact_phone  = ( ! empty( $phone_map ) && isset( $this->fields[ $phone_map ] ) ) ? $this->fields[ $phone_map ] : '';
			$this->mark_subscribe = isset( $automation_data['event_meta']['bwfan-mark-contact-subscribed'] ) ? $automation_data['event_meta']['bwfan-mark-contact-subscribed'] : 0;

			$automation_data['form_id']                 = $this->form_id;
			$automation_data['form_title']              = $this->form_title;
			$automation_data['fields']                  = $this->fields;
			$automation_data['email']                   = $this->email;
			$automation_data['first_name']              = $this->first_name;
			$automation_data['contact_phone']           = $this->contact_phone;
			$automation_data['last_name']               = $this->last_name;
			$automation_data['mark_contact_subscribed'] = $this->mark_subscribe;
			$this->maybe_create_update_contact( $automation_data );

			return $automation_data;
		}

		/**
		 * v2 Method: Get fields schema
		 * @return array[][]
		 */
		public function get_fields_schema() {
			$forms = $this->get_view_data();
			$forms = array_map( function ( $label, $value ) {
				return array(
					'label' => $label,
					'value' => $value,
				);
			}, $forms, array_keys( $forms ) );

			return [
				[
					'id'          => 'bwfan-optin_form_submit_form_id',
					'type'        => 'select',
					'options'     => $forms,
					'label'       => __( 'Select Form', 'wp-marketing-automations' ),
					"class"       => 'bwfan-input-wrapper',
					"placeholder" => 'Select',
					"required"    => true,
					"errorMsg"    => "Form is required.",
					"description" => ""
				],
				[
					'id'          => 'bwfan-form-field-map',
					'type'        => 'bwf_form_submit',
					"class"       => 'bwfan-input-wrapper',
					"required"    => true,
					'placeholder' => 'Select',
					"description" => "",
					"ajax_cb"     => 'bwfan_get_optin_form_fields',
					"ajax_field"  => [
						'id' => 'bwfan-optin_form_submit_form_id'
					],
					"fieldChange" => 'bwfan-optin_form_submit_form_id',
					"toggler"     => [
						'fields'   => array(
							array(
								'id'    => 'bwfan-optin_form_submit_form_id',
								'value' => '',
							)
						),
						'relation' => 'AND',
					]
				],
				[
					'id'            => 'bwfan-mark-contact-subscribed',
					'type'          => 'checkbox',
					'checkboxlabel' => 'Mark Contact as Subscribed',
					'description'   => '',
					"toggler"       => [
						'fields'   => array(
							array(
								'id'    => 'bwfan-optin_form_submit_form_id',
								'value' => '',
							),
						),
						'relation' => 'AND',
					]
				]
			];
		}

		public static function maybe_create_update_contact( $automation_data ) {
			$email = $automation_data['email'];
			if ( ! is_email( trim( $email ) ) ) {
				return;
			}

			$contact = new WooFunnels_Contact( '', $email );
			if ( ! $contact instanceof WooFunnels_Contact ) {
				return;
			}
			if ( isset( $automation_data['first_name'] ) && ! empty( $automation_data['first_name'] ) ) {
				$contact->set_f_name( $automation_data['first_name'] );
			}

			if ( isset( $automation_data['last_name'] ) && ! empty( $automation_data['last_name'] ) ) {
				$contact->set_l_name( $automation_data['last_name'] );
			}

			if ( isset( $automation_data['contact_phone'] ) && ! empty( $automation_data['contact_phone'] ) ) {
				$contact->set_contact_no( $automation_data['contact_phone'] );
			}

			if ( isset( $automation_data['mark_contact_subscribed'] ) && 1 === intval( $automation_data['mark_contact_subscribed'] ) ) {
				$contact->set_status( 1 );
			}

			$contact->save();
		}
	}

	/**
	 * Register this event to a source.
	 * This will show the current event in dropdown in single automation screen.
	 */
	if ( function_exists( 'bwfan_is_funnel_optin_forms_active' ) && bwfan_is_funnel_optin_forms_active() ) {
		return 'BWFAN_Funnel_Optin_Form_Submit';
	}

}