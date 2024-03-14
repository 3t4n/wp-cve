<?php

/*
 * Plugin: Brazilian Market on WooCommerce by Claudio Sanches v.4.0.0
 */
#[AllowDynamicProperties]
class WFACP_Brazil_Field_2 {
	private static $instance = null;
	private $settings = [];

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->settings = get_option( 'wcbcf_settings' );

		if ( WFACP_Common::is_funnel_builder_3() ) {
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_fields_billing' ] );
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_fields_shipping' ] );
		} else {
			$this->setup_fields_billing();
			$this->setup_fields_shipping();
		}


		add_filter( 'wfacp_update_posted_data_vice_versa_keys', [ $this, 'update_address_data' ] );
		add_filter( 'wfacp_unset_vice_versa_keys_shipping_keys', [ $this, 'unset_shipping_address_data' ] );


		/*-----------Dependency Message Will Be WooCommerce Extra Checkout Fields for Brazil---------------*/
		add_filter( 'wfacp_global_dependency_messages', [ $this, 'add_dependency_messages' ] );

		/*--------------------------Validation For billing company------------------------------------*/
		add_filter( 'wfacp_forms_field', [ $this, 'check_wc_validations_billing' ], 25, 2 );

		/*---------------------Update User Meta for Billing Sex Field When User Logged In----------------*/
		add_filter( 'wfacp_default_values', [ $this, 'change_default_value' ], 11, 3 );

		/*-----------------------------------Add Internal Css----------------------------------------*/
		add_action( 'wfacp_internal_css', [ $this, 'internal_css_js' ] );


	}

	private function is_enabled() {
		return class_exists( 'Extra_Checkout_Fields_For_Brazil_Front_End' );
	}

	public function setup_fields_billing() {
		if ( false == $this->is_enabled() ) {
			return;
		}

		$person_type = intval( $this->settings['person_type'] );
		$settings    = $this->settings;
		if ( 0 !== $person_type ) {
			if ( 1 === $person_type ) {
				new WFACP_Add_Address_Field( 'persontype', [
					'type'        => 'select',
					'label'       => __( 'Person type', 'woocommerce-extra-checkout-fields-for-brazil' ),
					'class'       => [ 'form-row-wide', 'person-type-field' ],
					'cssready'    => [ 'wfacp-col-full' ],
					'input_class' => [ 'wc-ecfb-select' ],
					'required'    => false,
					'options'     => [
						'1' => __( 'Individuals', 'woocommerce-extra-checkout-fields-for-brazil' ),
						'2' => __( 'Legal Person', 'woocommerce-extra-checkout-fields-for-brazil' ),
					],
					'priority'    => 22,
				], 'billing', false );
			}

			if ( 1 === $person_type || 2 === $person_type ) {
				if ( isset( $settings['rg'] ) ) {

					new WFACP_Add_Address_Field( 'cpf', [
						'label'    => __( 'CPF', 'woocommerce-extra-checkout-fields-for-brazil' ),
						'class'    => [ 'form-row-first', 'person-type-field' ],
						'cssready' => [ 'wfacp-col-left-half' ],
						'required' => false,
						'type'     => 'tel',
						'priority' => 23,
					], 'billing', false );


					new WFACP_Add_Address_Field( 'rg', [
						'label'    => __( 'RG', 'woocommerce-extra-checkout-fields-for-brazil' ),
						'class'    => [ 'form-row-last', 'person-type-field' ],
						'cssready' => [ 'wfacp-col-left-half' ],
						'required' => false,
						'priority' => 24,
					], 'billing', false );


				} else {
					new WFACP_Add_Address_Field( 'cpf', [
						'label'    => __( 'CPF', 'woocommerce-extra-checkout-fields-for-brazil' ),
						'class'    => [ 'form-row-first', 'person-type-field' ],
						'cssready' => [ 'wfacp-col-left-half' ],
						'required' => false,
						'type'     => 'tel',
						'priority' => 23,
					], 'billing', false );

				}
			}

			if ( 1 === $person_type || 3 === $person_type ) {

				if ( isset( $settings['ie'] ) ) {

					new WFACP_Add_Address_Field( 'cnpj', [
						'label'    => __( 'CNPJ', 'woocommerce-extra-checkout-fields-for-brazil' ),
						'class'    => [ 'form-row-first', 'person-type-field' ],
						'cssready' => [ 'wfacp-col-left-half' ],
						'required' => false,
						'type'     => 'tel',
						'priority' => 26,
					], 'billing', false );

					new WFACP_Add_Address_Field( 'ie', [
						'label'    => __( 'State Registration', 'woocommerce-extra-checkout-fields-for-brazil' ),
						'class'    => [ 'form-row-last', 'person-type-field' ],
						'cssready' => [ 'wfacp-col-left-half' ],
						'required' => false,
						'priority' => 27,
					], 'billing', false );


				} else {


					new WFACP_Add_Address_Field( 'cnpj', [
						'label'    => __( 'CNPJ', 'woocommerce-extra-checkout-fields-for-brazil' ),
						'class'    => [ 'form-row-wide', 'person-type-field' ],
						'cssready' => [ 'wfacp-col-full' ],
						'required' => false,
						'type'     => 'tel',
						'priority' => 26,
					], 'billing', false );
				}
			}
		}

		if ( isset( $settings['birthdate'] ) ) {
			new WFACP_Add_Address_Field( 'birthdate', [
				'label'       => __( 'Birthdate', 'woocommerce-extra-checkout-fields-for-brazil' ),
				'placeholder' => __( '', 'woocommerce-extra-checkout-fields-for-brazil' ),
				'class'       => [ 'form-row-first' ],
				'cssready'    => [ 'wfacp-col-left-half' ],
				'clear'       => false,
				'required'    => true,
				'priority'    => 31,
			], 'billing', false );

		}
		if ( isset( $settings['gender'] ) ) {
			new WFACP_Add_Address_Field( 'gender', [
				'type'        => 'select',
				'label'       => __( 'Gender', 'woocommerce-extra-checkout-fields-for-brazil' ),
				'placeholder' => __( '', 'woocommerce-extra-checkout-fields-for-brazil' ),
				'class'       => [ 'form-row-last' ],
				'cssready'    => [ 'wfacp-col-left-half' ],
				'input_class' => [ 'wc-ecfb-select' ],
				'clear'       => true,
				'required'    => true,
				'options'     => [
					''                                                             => __( 'Select', 'woocommerce-extra-checkout-fields-for-brazil' ),
					__( 'Female', 'woocommerce-extra-checkout-fields-for-brazil' ) => __( 'Female', 'woocommerce-extra-checkout-fields-for-brazil' ),
					__( 'Male', 'woocommerce-extra-checkout-fields-for-brazil' )   => __( 'Male', 'woocommerce-extra-checkout-fields-for-brazil' ),
				],
				'priority'    => 32,
			], 'billing', false );
		}


		new WFACP_Add_Address_Field( 'number', array(
			'label'    => __( 'Number', 'woocommerce-extra-checkout-fields-for-brazil' ),
			'class'    => [ 'form-row-first', 'address-field' ],
			'cssready' => [ 'wfacp-col-left-half' ],
			'clear'    => true,
			'required' => true,
			'priority' => 55,
		), 'billing', false );


		new WFACP_Add_Address_Field( 'neighborhood', array(
			'label'    => __( 'Neighborhood', 'woocommerce-extra-checkout-fields-for-brazil' ),
			'class'    => [ 'form-row-first', 'address-field' ],
			'cssready' => [ 'wfacp-col-left-half' ],
			'clear'    => true,
			'priority' => 65,
		), 'billing', false );


		if ( isset( $settings['cell_phone'] ) ) {
			new WFACP_Add_Address_Field( 'cellphone', array(
				'label'    => __( 'Cell Phone', 'woocommerce-extra-checkout-fields-for-brazil' ),
				'class'    => [ 'form-row-last' ],
				'cssready' => [ 'wfacp-col-full' ],
				'clear'    => true,
				'priority' => 105,
			), 'billing', false );
		}
	}

	public function setup_fields_shipping() {
		if ( false == $this->is_enabled() ) {
			return;
		}
		new WFACP_Add_Address_Field( 'number', array(
			'label'    => __( 'Number', 'woocommerce-extra-checkout-fields-for-brazil' ),
			'class'    => [ 'form-row-first', 'address-field' ],
			'cssready' => [ 'wfacp-col-left-half' ],
			'clear'    => true,
			'required' => true,
			'priority' => 55,
		), 'shipping', false );

		new WFACP_Add_Address_Field( 'neighborhood', array(
			'label'    => __( 'Neighborhood', 'woocommerce-extra-checkout-fields-for-brazil' ),
			'class'    => [ 'form-row-first', 'address-field' ],
			'cssready' => [ 'wfacp-col-left-half' ],
			'clear'    => true,
			'priority' => 65,
		), 'shipping', false );
	}

	public function update_address_data( $keys ) {
		$keys['shipping_number']       = 'billing_number';
		$keys['shipping_neighborhood'] = 'billing_neighborhood';
		$keys['billing_number']        = 'shipping_number';
		$keys['billing_neighborhood']  = 'shipping_neighborhood';

		return $keys;
	}

	public function unset_shipping_address_data( $keys ) {
		$keys[] = 'shipping_number';
		$keys[] = 'shipping_neighborhood';

		return $keys;
	}

	/*
	 * Dependency Message Will Be WooCommerce Extra Checkout Fields for Brazil
	 */
	public function add_dependency_messages( $messages ) {

		$messages[] = [
			'message'     => __( '"WooCommerce Extra Checkout Fields for Brazil" is activated. Learn about the right away to configure it with ' . "Funnelkit" . ' Checkout.<a target="_blank" href="//funnelkit.com/docs/aerocheckout/compatibility/woocommerce-extra-checkout-fields-for-Brazil"> Know more</a>', 'woofunnels-aero-checkout' ),
			'id'          => '',
			'show'        => 'yes',
			'dismissible' => true,
			'is_global'   => true,
			'type'        => 'wfacp_error',
		];

		return $messages;
	}

	/*
	 * Validation For billing company
	 */

	public function check_wc_validations_billing( $fields ) {

		if ( false == $this->is_enabled() ) {
			return $fields;
		}
		if ( isset( $fields['id'] ) && $fields['id'] == 'billing_company' ) {
			$fields['required'] = true;
		}

		if ( isset( $fields['placeholder'] ) && false !== strpos( $fields['placeholder'], 'false' ) ) {
			$fields['placeholder'] = "";
		}
		$none_person_type_fields = [ 'billing_birthdate', 'billing_gender' ];
		if ( ! is_array( $fields['class'] ) ) {
			$fields['class'] = [];
		}
		if ( in_array( $key, $none_person_type_fields ) ) {
			foreach ( $fields['class'] as $c_i => $class ) {
				if ( false !== strpos( $class, 'person-type-field' ) ) {
					$fields['class'][ $c_i ] = str_replace( 'person-type-field', '', $class );
				}
			}
		}

		return $fields;
	}

	/*
	 * Update User Meta for Billing Sex Field When User Logged In
	 */

	function change_default_value( $field_value, $key, $field ) {

		if ( is_user_logged_in() && $key == 'billing_sex' ) {
			$userID = get_current_user_id();

			if ( $userID != '' && get_user_meta( $userID, $key, true ) != '' ) {
				$field_value = get_user_meta( $userID, $key, true );
			}
		}

		return $field_value;
	}

	public function internal_css_js() {
		?>
        <style>
            body #wfacp-e-form .wfacp_main_form.woocommerce #billing_persontype_field .optional,
            body #wfacp-e-form .wfacp_main_form.woocommerce #billing_cnpj_field .optional,
            body #wfacp-e-form .wfacp_main_form.woocommerce #billing_ie_field .optional,
            body #wfacp-e-form .wfacp_main_form.woocommerce #billing_cpf_field .optional,
            body #wfacp-e-form .wfacp_main_form.woocommerce #billing_rg_field .optional {
                display: none !important;
            }

        </style>

        <script>
            window.addEventListener('load', function () {
                (function ($) {

                    function select2_reinitiate() {
                        let wc_ecfb_select = $('.wc-ecfb-select');
                        if ($().select2 && wc_ecfb_select.length > 0) {
                            wc_ecfb_select.select2('destroy');
                            setTimeout(function () {
                                $('.wc-ecfb-select').select2();
                            }, 800);
                        }

                    }

                    $(".wfacp_steps_wrap a").on('click', function (e) {
                        select2_reinitiate();
                        validate_required();
                    });

                    $(document).ready(function () {
                        validate_required();
                    });

                    $(document.body).on('wfacp_step_switching', function () {
                        select2_reinitiate();
                        validate_required();

                    });

                    function validate_required() {
                        var $ele = $("#billing_persontype");
                        var $parent = $("#billing_company_field");

                        if ($ele.val() == "2" || $ele.val() == 2) {

                            var billing_company = $parent.find("input[name=billing_company]").val();
                            if (billing_company.trim() === '' || billing_company == null) {

                                $parent.removeClass('woocommerce-validated').addClass('validate-required');
                            } else {
                                $parent.removeClass('woocommerce-invalid').addClass('woocommerce-validated');
                            }

                        } else {
                            $parent.removeClass('woocommerce-invalid').addClass('woocommerce-validated');
                        }

                    }

                    $(document.body).on("change", "#billing_persontype", function (e) {
                        validate_required()
                    });
                    $(document).on('focus', '#billing_company', function () {
                        validate_required()
                    });
                    $(document).on('change', '#billing_same_as_shipping,#shipping_same_as_billing', function () {
                        if ($(this).is(":checked")) {
                            select2_reinitiate();
                        }
                    });
                })(jQuery);
            });
        </script>
		<?php
	}
}


WFACP_Brazil_Field_2::get_instance();