<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class El_WFACP_Form_Widget extends WFACP_Elementor_HTML_BLOCK {

	private $html_fields = [];
	public $typo_default_value = [];
	public $progress_bar = [];
	public $section_fields = [];
	private $current_step = 1;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );


		add_action( 'elementor/document/after_save', [ $this, 'migrate_label' ], 10, 2 );
	}


	public function get_name() {

		return 'wfacp_form';
	}

	public function get_title() {
		return __( 'Checkout Form', 'funnel-builder' );
	}

	public function get_icon() {
		return 'wfacp-icon-icon_checkout';
	}

	public function get_categories() {
		return [ 'woofunnels-aero-checkout' ];
	}

	/**
	 * _register_controls function DEPRECATED in 3.1.0 version of elementor 24-01-2021
	 * @return void
	 */
	protected function register_controls() {
		$template = wfacp_template();
		if ( is_null( $template ) ) {
			return;
		}
		$template->get_fieldsets();

		$this->register_sections();
		$this->register_styles();
	}

	protected function register_sections() {


		$this->breadcrumb_bar();
		$this->mobile_mini_cart();
		$this->register_section_fields();
		$this->coupon_fields();
		$this->order_summary_fields();
		$this->payment_method();


	}

	/* ----------------Coupon field Under Content Section----------------------- */

	private function coupon_fields() {

		$this->add_tab( __( 'Coupon', 'funnel-builder' ), 1 );
		$this->add_text( 'form_coupon_button_text', __( 'Coupon Button Text', 'funnel-builder' ), __( 'Apply', 'funnel-builder' ) );
		$this->end_tab();


	}

	/* -------------------------------End--------------------------------------- */


	/* ----------------Order Summary field Under Content Section----------------------- */

	private function order_summary_fields() {
		$this->add_tab( __( 'Order Summary', 'funnel-builder' ), 5 );
		$this->add_switcher( 'order_summary_enable_product_image', __( 'Enable Image', 'funnel-builder' ), '', '', "yes", 'yes', [], '', '', 'wfacp_elementor_device_hide' );
		$this->end_tab();
	}

	/* -------------------------------End--------------------------------------- */

	private function register_section_fields() {
		$template = wfacp_template();
		$steps    = $template->get_fieldsets();

		$do_not_show_fields = WFACP_Common::get_html_excluded_field();
		$exclude_fields     = [];
		foreach ( $steps as $step_key => $fieldsets ) {
			foreach ( $fieldsets as $section_key => $section_data ) {
				if ( empty( $section_data['fields'] ) ) {
					continue;
				}
				$count            = count( $section_data['fields'] );
				$html_field_count = 0;


				if ( ! empty( $section_data['html_fields'] ) ) {
					foreach ( $do_not_show_fields as $h_key ) {
						if ( isset( $section_data['html_fields'][ $h_key ] ) ) {
							$html_field_count ++;
							$this->html_fields[ $h_key ] = true;

						}
					}
				}

				if ( $html_field_count == $count ) {
					continue;
				}

				if ( is_array( $section_data['fields'] ) && count( $section_data['fields'] ) > 0 ) {
					foreach ( $section_data['fields'] as $fkey => $fval ) {
						if ( isset( $fval['id'] ) && in_array( $fval['id'], $do_not_show_fields ) ) {
							$exclude_fields[]                 = $fval['id'];
							$this->html_fields[ $fval['id'] ] = true;
							continue;
						}
					}
				}

				if ( count( $exclude_fields ) == count( $section_data['fields'] ) ) {
					continue;
				}


				$this->add_tab( $section_data['name'], 5 );
				$this->register_fields( $section_data['fields'] );
				$this->end_tab();


			}
		}

	}

	private function register_fields( $temp_fields ) {

		$template      = wfacp_template();
		$template_slug = $template->get_template_slug();
		$template_cls  = $template->get_template_fields_class();

		$default_cls        = $template->default_css_class();
		$do_not_show_fields = WFACP_Common::get_html_excluded_field();


		//$this->add_heading( __( 'Field Width', 'woofunnels-aero-checkout' ) );


		$this->section_fields[] = $temp_fields;
		foreach ( $temp_fields as $loop_key => $field ) {

			if ( in_array( $loop_key, [ 'wfacp_start_divider_billing', 'wfacp_start_divider_shipping' ], true ) ) {
				$address_key_group = ( $loop_key == 'wfacp_start_divider_billing' ) ? __( 'Billing Address', 'funnel-builder' ) : __( 'Shipping Address', 'funnel-builder' );
				$this->add_heading( $address_key_group, 'none' );
			}

			if ( ! isset( $field['id'] ) || ! isset( $field['label'] ) ) {
				continue;
			}

			$field_key = $field['id'];

			if ( isset( $template_cls[ $field_key ] ) ) {
				$field_default_cls = $template_cls[ $field_key ]['class'];
			} else {
				$field_default_cls = $default_cls['class'];
			}

			if ( in_array( $field_key, $do_not_show_fields ) ) {
				$this->html_fields[ $field_key ] = true;
				continue;
			}


			$skipKey = [ 'billing_same_as_shipping', 'shipping_same_as_billing' ];
			if ( in_array( $field_key, $skipKey ) ) {
				continue;
			}
            $options = $this->get_class_options();
			if ( isset( $field['type'] ) && 'wfacp_html' === $field['type'] ) {
				$options           = [ 'wfacp-col-full' => __( 'Full', 'funnel-builder' ), ];
				$field_default_cls = 'wfacp-col-full';
			}

            $options = apply_filters('wfacp_widget_fields_classes', $options, $field, $this->get_class_options());


            $this->add_select( 'wfacp_' . $template_slug . '_' . $field_key . '_field', $field['label'], $options, $field_default_cls );


		}

	}

	private function form_buttons() {

		$template = wfacp_template();
		$count    = $template->get_step_count();

		$backLinkArr = [];


		for ( $i = 1; $i <= $count; $i ++ ) {

			$button_default_text = __( 'NEXT STEP →', 'funnel-builder' );
			$button_key          = 'wfacp_payment_button_' . $i . '_text';
			$button_label        = "Step {$i}";
			$text_key            = $i;
			if ( $i == $count ) {
				$button_key          = 'wfacp_payment_place_order_text';
				$text_key            = 'place_order';
				$button_default_text = __( 'PLACE ORDER NOW', 'funnel-builder' );
				$button_label        = __( 'Place Order', 'funnel-builder' );

			}
			$this->add_heading( __( $button_label, 'funnel-builder' ), 'none' );
			$this->add_text( $button_key, __( "Button Text", 'funnel-builder' ), esc_js( $button_default_text ), [], "wfacp_field_text_wrap" );

			$this->icon_text( $text_key );

			if ( $i == $count ) {
				$this->add_switcher_without_responsive( 'enable_price_in_place_order_button', __( 'Enable Price', 'funnel-builder' ), '', '', 'no', 'yes', [] );
			}

			if ( $i > 1 ) {

				$backCount = $i - 1;

				$backLinkArr[ 'payment_button_back_' . $i . '_text' ] = [
					'label'   => __( "Return to Step {$backCount}", 'funnel-builder' ),
					'default' => sprintf( '« Return to Step %s ', $i - 1 )
				];

			}


		}


		$this->add_divider();
		if ( is_array( $backLinkArr ) && count( $backLinkArr ) > 0 ) {
			$this->add_heading( __( 'Return Link Text', 'funnel-builder' ), 'none' );
			$cart_name = __( '« Return to Cart', 'funnel-builder' );
			$this->add_text( "return_to_cart_text", 'Return to Cart', $cart_name, [ 'step_cart_link_enable' => 'yes' ], 'wfacp_field_text_wrap' );
			foreach ( $backLinkArr as $i => $val ) {


				$this->add_text( $i, $val['label'], $val['default'], [], 'wfacp_field_text_wrap' );
			}
		}

		$this->add_text( 'text_below_placeorder_btn', __( "Text Below Place Order Button", 'funnel-builder' ), sprintf( 'We Respect Your privacy & Information ', 'woofunnels-aero-checkout' ), [], 'wfacp_field_text_wrap wfacp_bold' );

	}

	private function icon_text( $counter_step ) {


		$this->add_text( 'step_' . $counter_step . '_text_after_place_order', __( " Sub Text", 'funnel-builder' ), '', [], 'wfacp_field_text_wrap' );
		$icon_list = [
			'aero-e902' => __( 'Arrow 1', 'funnel-builder' ),
			'aero-e906' => __( 'Arrow 2', 'funnel-builder' ),
			'aero-e907' => __( 'Arrow 3', 'funnel-builder' ),
			'aero-e908' => __( 'Checkmark', 'funnel-builder' ),
			'aero-e905' => __( 'Cart 1', 'funnel-builder' ),
			'aero-e901' => __( 'Lock 1', 'funnel-builder' ),
			'aero-e900' => __( 'Lock 2', 'funnel-builder' ),
		];

		$bwf_icon_list = apply_filters( 'bwf_icon_list', $icon_list );

		$this->add_switcher_without_responsive( 'enable_icon_with_place_order_' . $counter_step, __( 'Enable Icon', 'funnel-builder' ), '', '', 'no', 'yes', [] );

		$condition = [
			'enable_icon_with_place_order_' . $counter_step => "yes"
		];
		$this->add_select( 'icons_with_place_order_list_' . $counter_step, "Select Icons Style", $bwf_icon_list, 'aero-e901', $condition, '', 'wfacp_field_text_wrap ' );

	}

	private function mobile_mini_cart() {


		$this->add_tab( __( 'Collapsible Order Summary', 'funnel-builder' ), 5 );

		$this->add_switcher( 'enable_callapse_order_summary', __( 'Enable', 'funnel-builder' ), '', '', 'no', 'yes', [], '', '' );
		$this->add_switcher_without_responsive( 'order_summary_enable_product_image_collapsed', __( 'Enable Image', 'funnel-builder' ), '', '', "yes", 'yes', [], '', '', 'wfacp_elementor_device_hide' );
		$enable_callapse_order_summary_condition = [];


		$this->add_text( 'cart_collapse_title', __( 'Collapsed View Text', 'funnel-builder' ), __( 'Show Order Summary', 'funnel-builder' ), $enable_callapse_order_summary_condition );
		$this->add_text( 'cart_expanded_title', __( 'Expanded View Text', 'funnel-builder' ), __( 'Hide Order Summary', 'funnel-builder' ), $enable_callapse_order_summary_condition );

		$this->add_text( 'collapse_coupon_button_text', __( 'Coupon Button Text', 'funnel-builder' ), __( 'Apply', 'funnel-builder' ), $enable_callapse_order_summary_condition );

		$this->add_switcher_without_responsive( 'collapse_enable_coupon', __( 'Enable Coupon', 'funnel-builder' ), '', '', 'false', 'true', $enable_callapse_order_summary_condition, 'true', 'true', '' );
		$this->add_switcher_without_responsive( 'collapse_enable_coupon_collapsible', __( 'Collapsible Coupon Field', 'funnel-builder' ), '', '', 'false', 'true', $enable_callapse_order_summary_condition, 'true', 'true', '' );


		$this->add_switcher_without_responsive( 'collapse_order_quantity_switcher', __( 'Quantity Switcher', 'funnel-builder' ), '', '', 'true', 'true', $enable_callapse_order_summary_condition, 'true', 'true', '' );
		$this->add_switcher_without_responsive( 'collapse_order_delete_item', __( 'Allow Deletion', 'funnel-builder' ), '', '', 'true', 'true', $enable_callapse_order_summary_condition, 'true', 'true', '' );


		$this->end_tab();

	}

	private function collapsible_summary_coupon() {

		$field_key = 'wfacp_collapsible_summary';
		$condition = [ 'collapse_enable_coupon' => 'true' ];
		$this->add_heading( __( 'Coupon', 'funnel-builder' ), '', $condition );
		$this->add_heading( __( 'Link', 'funnel-builder' ), '', $condition );
		$coupon_typography_opt = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_woocommerce_form_coupon .wfacp-coupon-section .woocommerce-info span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_woocommerce_form_coupon .wfacp-coupon-section .woocommerce-info .wfacp_showcoupon',
		];
		$this->add_typography( $field_key . '_coupon_typography', implode( ',', $coupon_typography_opt ), [], $condition );
		$this->add_color( $field_key . '_coupon_text_color', $coupon_typography_opt, '', '', $condition );

		$this->add_heading( __( 'Field', 'funnel-builder' ), '', $condition );
		$form_fields_label_typo = [
			'{{WRAPPER}} #wfacp-e-form form.checkout_coupon.woocommerce-form-coupon .wfacp-col-left-half label.wfacp-form-control-label',
		];
		$fields_options         = [
			'font_weight' => [
				'default' => '400',
			],
		];

		$this->add_typography( $field_key . '_label_typo', implode( ',', $form_fields_label_typo ), $fields_options, $condition, __( 'Label Typography', 'funnel-builder' ) );

		$form_fields_label_color_opt = [
			'{{WRAPPER}} #wfacp-e-form form.checkout_coupon.woocommerce-form-coupon .wfacp-col-left-half label.wfacp-form-control-label',
		];
		$this->add_color( $field_key . '_label_color', $form_fields_label_color_opt, '', __( 'Label Color', 'funnel-builder' ), $condition );


		$fields_options = [
			'{{WRAPPER}} #wfacp-e-form form.checkout_coupon.woocommerce-form-coupon .wfacp-col-left-half .wfacp-form-control',
		];

		$optionString = implode( ',', $fields_options );
		$this->add_typography( $field_key . '_input_typo', $optionString, [], $condition, __( 'Coupon Typography', 'funnel-builder' ) );


		$inputColorOption = [
			'{{WRAPPER}} #wfacp-e-form form.checkout_coupon.woocommerce-form-coupon .wfacp-col-left-half .wfacp-form-control',
		];
		$this->add_color( $field_key . '_input_color', $inputColorOption, '', __( 'Coupon Color', 'funnel-builder' ), $condition );

		$this->add_border_color( $field_key . '_focus_color', [ '{{WRAPPER}} #wfacp-e-form form.checkout_coupon.woocommerce-form-coupon .wfacp-col-left-half .wfacp-form-control:focus' ], '#61bdf7', __( 'Focus Color', 'woofunnels-aero-checkout' ), true, $condition );

		$fields_options = [
			'{{WRAPPER}} #wfacp-e-form form.checkout_coupon.woocommerce-form-coupon .wfacp-col-left-half .wfacp-form-control',
		];
		$default        = [ 'top' => 4, 'right' => 4, 'bottom' => 4, 'left' => 4, 'unit' => 'px' ];
		$this->add_border( $field_key . '_coupon_border', implode( ',', $fields_options ), $condition, $default );


		$this->add_heading( __( 'Button', 'funnel-builder' ), '', $condition );

		/* Button color setting */
		$btnkey = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_mb_mini_cart_sec_accordion_content form.checkout_coupon.woocommerce-form-coupon .form-row-last.wfacp-col-left-half button.button.wfacp-coupon-btn'
		];

		$btnkey_hover = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_mb_mini_cart_sec_accordion_content form.checkout_coupon.woocommerce-form-coupon .form-row-last.wfacp-col-left-half button.button.wfacp-coupon-btn:hover'
		];
		$this->add_controls_tabs( $field_key . "_tabs", $condition );
		$this->add_controls_tab( $field_key . "_normal_tab", 'Normal' );
		$this->add_background_color( $field_key . '_btn_bg_color', $btnkey, '', __( 'Background', 'funnel-builder' ) );
		$this->add_color( $field_key . '_btn_text_color', $btnkey, '', __( 'Label', 'funnel-builder' ) );
		$this->close_controls_tab();

		$this->add_controls_tab( $field_key . "_hover_tab", 'Hover' );
		$this->add_background_color( $field_key . '_btn_bg_hover_color', $btnkey_hover, '', __( 'Hover', 'funnel-builder' ) );
		$this->add_color( $field_key . '_btn_bg_hover_text_color', $btnkey_hover, '', __( 'Hover Label', 'funnel-builder' ) );
		$this->close_controls_tab();
		$this->close_controls_tabs();
		/* Button color setting End*/

	}

	private function breadcrumb_bar() {
		$template     = wfacp_template();
		$num_of_steps = $template->get_step_count();


		if ( $num_of_steps >= 1 ) {
			$stepsCounter = 1;

			$tab_name              = __( 'Steps', 'funnel-builder' );
			$enable_condition_name = __( 'Enable Steps', 'funnel-builder' );

			$options                    = [
				'tab'       => __( 'Tabs', 'funnel-builder' ),
				'bredcrumb' => __( 'Breadcrumb', 'funnel-builder' ),
			];
			$wfacp_elementor_hide_field = '';

			if ( $num_of_steps == 1 ) {
				$tab_name              = __( 'Form Header', 'funnel-builder' );
				$enable_condition_name = __( 'Enable', 'funnel-builder' );
				unset( $options['bredcrumb'] );

			}

			$this->add_tab( $tab_name, 5 );
			$this->add_switcher( 'enable_progress_bar', $enable_condition_name, '', '', '', 'yes', [], '', '' );


			$enableOptions = [
				'enable_progress_bar' => 'yes',
			];


			$this->add_select( 'select_type', "Select Type", $options, 'tab', $enableOptions, '', $wfacp_elementor_hide_field );


			$bredcrumb_controls = [
				'select_type' => [
					'bredcrumb',
				],

				'enable_progress_bar' => "yes"
			];

			$progress_controls = [
				'select_type'         => [
					'progress_bar',
				],
				'enable_progress_bar' => "yes"
			];

			$labels = [
				[
					'heading'     => __( 'SHIPPING', 'funnel-builder' ),
					'sub-heading' => __( 'Where to ship it?', 'funnel-builder' ),
				],
				[
					'heading'     => __( 'PRODUCTS', 'funnel-builder' ),
					'sub-heading' => __( 'Select your product', 'funnel-builder' ),
				],
				[
					'heading'     => __( 'PAYMENT', 'funnel-builder' ),
					'sub-heading' => __( 'Confirm your order', 'funnel-builder' ),
				],

			];

			for ( $bi = 0; $bi < $num_of_steps; $bi ++ ) {
				$heading    = $labels[ $bi ]['heading'];
				$subheading = $labels[ $bi ]['sub-heading'];

				$label = __( 'Step', 'funnel-builder' );


				if ( $num_of_steps > 1 ) {
					$this->add_heading( $label . " " . $stepsCounter, 'none', [ 'enable_progress_bar' => "yes" ] );
				}


				$this->add_text( 'step_' . $bi . '_bredcrumb', "Title", "Step $stepsCounter", $bredcrumb_controls );

				$this->add_text( 'step_' . $bi . '_progress_bar', "Heading", "Step $stepsCounter", $progress_controls );


				$this->add_text( 'step_' . $bi . '_heading', "Heading", $heading, [ 'select_type' => 'tab', 'enable_progress_bar' => "yes" ] );
				$this->add_text( 'step_' . $bi . '_subheading', "Sub Heading", $subheading, [ 'select_type' => 'tab', 'enable_progress_bar' => "yes" ] );
				$stepsCounter ++;
				$heading    = '';
				$subheading = '';
			}

			if ( $num_of_steps > 1 ) {

				$condtion_control = [
					'select_type'         => [
						'bredcrumb',
						'progress_bar',
					],
					'enable_progress_bar' => "yes"
				];

				$cartTitle          = __( 'Title', 'funnel-builder' );
				$progresscartTitle  = __( 'Cart title', 'funnel-builder' );
				$settingDescription = __( 'Note: Cart settings will work for Global Checkout when user navigates from Product > Cart > Checkout', 'funnel-builder' );
				$cartText           = __( 'Cart', 'funnel-builder' );

				$options = [
					'yes' => __( 'Yes', 'funnel-builder' ),
					'no'  => __( 'No', 'funnel-builder' ),

				];
				$this->add_heading( 'Cart', 'none', $bredcrumb_controls );

				$this->add_select( 'step_cart_link_enable', "Add to Breadcrumb", $options, 'yes', $condtion_control );
				$this->add_text( 'step_cart_progress_bar_link', $progresscartTitle, $cartText, $progress_controls );
				$this->add_text( 'step_cart_bredcrumb_link', $cartTitle, $cartText, $bredcrumb_controls, '', $settingDescription );

			}

			$this->end_tab();
		}


	}

	protected function register_styles() {

		$this->global_typography();
		$this->get_progress_settings();
		$this->collapsible_order_summary();
		$this->get_heading_settings();
		$this->fields_typo_settings();
		$this->section_typo_settings();


		if ( is_array( $this->html_fields ) && ! isset( $this->html_fields['order_summary'] ) ) {
			$this->html_fields['order_summary'] = 1;
		}
		if ( is_array( $this->html_fields ) && ! isset( $this->html_fields['order_coupon'] ) ) {
			$this->html_fields['order_coupon'] = 1;
		}

		foreach ( $this->html_fields as $key => $v ) {

			$this->generate_html_block( $key );
		}
		$this->payment_method_styling();
		$this->privacy_policy_styling();
		$this->terms_policy_styling();
		$this->payment_buttons_styling();


		$this->class_section();
	}

	public function get_progress_settings() {

		$template = wfacp_template();

		$number_of_steps = $template->get_step_count();

		if ( $number_of_steps < 1 ) {
			return;
		}

		$class     = '';
		$step_text = __( 'Steps', 'funnel-builder' );
		if ( $number_of_steps <= 1 ) {
			$class     = 'wfacp_elementor_hide_field';
			$step_text = __( 'Header', 'funnel-builder' );
		}

		$controlsCondition = [
			'select_type' => [
				'bredcrumb',
				'progress_bar',
				'tab',
			],
		];


		$tab_condition          = [ 'select_type' => 'tab' ];
		$breadcrumb_condition   = [ 'select_type' => 'bredcrumb' ];
		$progress_bar_condition = [ 'select_type' => 'progress_bar' ];


		$this->add_tab( __( $step_text, 'funnel-builder' ), 2 );


		$this->add_heading( 'Typography', '', $controlsCondition );
		$this->add_typography( 'tab_heading_typography', '{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-order2StepTitle.wfacp-order2StepTitleS1', [], $tab_condition, 'Heading' );
		$this->add_typography( 'tab_subheading_typography', '{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-order2StepSubTitle.wfacp-order2StepSubTitleS1', [], $tab_condition, 'Sub Heading' );

		$alignmentOption = [ '{{WRAPPER}} #wfacp-e-form .wfacp-payment-tab-list .wfacp-order2StepHeaderText' ];
		$this->add_text_alignments( 'tab_text_alignment', $alignmentOption, '', [], 'center', [ 'select_type' => 'tab' ] );
		$this->add_typography( 'progress_bar_heading_typography', '{{WRAPPER}} #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li a', [], $progress_bar_condition, 'Heading' );

		/* Breadcrumb */


		$this->add_typography( 'breadcrumb_heading_typography', '{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_steps_sec ul li a', [], $breadcrumb_condition, 'Heading' );
		$this->add_heading( 'Colors', '', $controlsCondition );


		/* color setting */
		$this->add_controls_tabs( "wfacp_breadcrumb_style", $breadcrumb_condition );

		$this->add_controls_tab( "wfacp_breadcrumb_normal_tab", 'Normal' );
		$this->add_color( 'breadcrumb_text_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp-form .wfacp_main_form.woocommerce .wfacp_steps_sec ul li a' ], '', 'Color ', $breadcrumb_condition );
		$this->close_controls_tab();

		$this->add_controls_tab( "wfacp_breadcrumb_hover_tab", 'Hover' );
		$this->add_color( 'breadcrumb_text_hover_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp-form .wfacp_main_form.woocommerce .wfacp_breadcrumb_wrap_here #wfacp_steps_sec.wfacp_steps_sec ul li a.wfacp_breadcrumb_link:hover' ], '', 'Color', $breadcrumb_condition );
		$this->close_controls_tab();


		$this->close_controls_tabs();

		/* Back link color setting End*/


		/* Progress Bar */
		$activeColor = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li.wfacp_bred_active:before',
			'{{WRAPPER}} #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li.wfacp_active_prev:before',
			'{{WRAPPER}} #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li.df_cart_link.wfacp_bred_visited:before'
		];


		$this->add_background_color( 'progress_bar_line_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul:before' ], '', 'Line', $progress_bar_condition );
		$this->add_border_color( 'progress_bar_circle_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li:before' ], '', __( 'Circle Border', 'funnel-builder' ), false, $progress_bar_condition );

		$this->add_background_color( 'progress_bar_active_color', $activeColor, '', 'Active Step', $progress_bar_condition );
		$this->add_color( 'progressbar_text_color', [ '{{WRAPPER}}  #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li a' ], '', 'Text ', $progress_bar_condition );
		$this->add_color( 'progressbar_text_hover_color', [ '{{WRAPPER}}  #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li a:hover' ], '', 'Text Hover', $progress_bar_condition );

		$this->add_controls_tabs( "wfacp_progress_bar_tabs", $tab_condition, $class );

		$this->add_controls_tab( "wfacp_progress_bar_active_tab", __( 'Active Step', 'funnel-builder' ) );

		$this->add_background_color( 'active_step_bg_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list.wfacp-active' ], '', 'Background Color', $tab_condition );
		$this->add_color( 'active_step_text_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list.wfacp-active .wfacp_tcolor' ], '', 'Text Color', $tab_condition );
		$this->add_border_color( 'active_tab_border_bottom_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp-payment-tab-list.wfacp-active' ], '', __( 'Tab Border Color', 'funnel-builder' ), false, $tab_condition );

		if ( $number_of_steps > 1 ) {
			$this->add_background_color( 'active_step_count_bg_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list.wfacp-active .wfacp-order2StepNumber' ], '', 'Count Background Color', $tab_condition );
			$this->add_border_color( 'active_step_count_border_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list.wfacp-active .wfacp-order2StepNumber' ], '', __( 'Count Border Color', 'funnel-builder' ), false, $tab_condition );
			$this->add_color( 'active_step_count_text_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list.wfacp-active .wfacp-order2StepNumber' ], '', 'Count Text Color', $tab_condition );
		}

		$this->close_controls_tab();

		$this->add_controls_tab( "wfacp_progress_bar_inactive_tab", __( 'Inactive Step', 'funnel-builder' ) );

		$inactiveBgcolor = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list',

		];
		$this->add_background_color( 'inactive_step_bg_color', $inactiveBgcolor, '', __( 'Background Color', 'funnel-builder' ), $tab_condition );
		$this->add_color( 'inactive_step_text_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list .wfacp_tcolor' ], '', __( 'Text Color', 'funnel-builder' ), $tab_condition );
		$this->add_border_color( 'inactive_tab_border_bottom_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp-payment-tab-list' ], '', __( 'Tab Border Color', 'funnel-builder' ), false, $tab_condition );
		$this->add_background_color( 'inactive_step_count_bg_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list .wfacp-order2StepNumber' ], '', 'Count Background Color', $tab_condition );
		$this->add_border_color( 'inactive_step_count_border_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list .wfacp-order2StepNumber' ], '', __( 'Count Border Color', 'funnel-builder' ), false, $tab_condition );
		$this->add_color( 'inactive_step_count_text_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list .wfacp-order2StepNumber' ], '', 'Count Text Color', $tab_condition );


		$this->close_controls_tab();
		$this->close_controls_tabs();

		$this->add_heading( 'Border Radius', '', $tab_condition );

		$label = __( 'Step Bar Border Radius', 'funnel-builder' );
		$this->add_border_radius( 'border_radius_steps', '{{WRAPPER}} #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list', $tab_condition, '', '', $label );

		$selector = [
			'{{WRAPPER}} #wfacp-e-form .tab'
		];

		$default = [ 'top' => 0, 'right' => 0, 'bottom' => 15, 'left' => 0, 'unit' => 'px', 'isLinked' => true ];
		$this->add_margin( 'wfacp_tab_margin', implode( ',', $selector ), $default, $default, $tab_condition, $default );

		$this->end_tab();
	}

	private function get_heading_settings() {
		/**
		 * @var $template WFACP_Elementor_Template
		 */


		$this->add_tab( __( 'Heading', 'funnel-builder' ), 2 );
		$this->add_heading( __( 'Heading', 'funnel-builder' ) );


		$sectionTitleOption = [ '{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_section_title' ];

		$this->add_typography( 'section_heading_typo', implode( ',', $sectionTitleOption ) );
		$this->add_color( 'form_heading_color', $sectionTitleOption );


		$extra_options = [

			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #payment button#place_order'                       => 'font-weight: 700;font-size: 25px;',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce button#place_order'                                => 'font-weight: 700;font-size: 25px;',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout .wfacp-next-btn-wrap button' => 'font-weight: 700;font-size: 25px;',

		];

		$alignment = 'Left';
		if ( is_rtl() ) {
			$alignment = 'Right';
		}
		$this->add_text_alignments( 'form_heading_align', $sectionTitleOption, '', [], $alignment, [], $extra_options );


		$subheadingOption = [ '{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-comm-title h4' ];

		//Sub heading start here
		$this->add_heading( __( 'Sub Heading', 'funnel-builder' ) );
		$this->add_typography( 'section_sub_heading_typo', implode( ',', $subheadingOption ) );
		$this->add_color( 'form_sub_heading_color', $subheadingOption );
		$this->add_text_alignments( 'form_sub_heading_align', $subheadingOption );


		//Sub heading end here

		$advanceOption = [ '{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-section .wfacp-comm-title' ];
		$this->add_heading( __( 'Advanced', 'woofunnels-aero-checkout' ) );
		$this->add_background_color( 'form_heading_bg_color', $advanceOption, 'transparent' );

		$this->add_padding( 'form_heading_padding', implode( ',', $advanceOption ) );

		$default = [ 'top' => 0, 'right' => 0, 'bottom' => 10, 'left' => 0, 'unit' => 'px' ];
		$this->add_margin( 'form_heading_margin', implode( ',', $advanceOption ), $default, $default, [], $default );
		$this->add_border( 'form_heading_border', implode( ',', $advanceOption ) );

		$this->end_tab();

	}

	private function fields_typo_settings() {
		$this->add_tab( __( 'Fields', 'funnel-builder' ), 2 );

		$this->add_heading( __( 'Label', 'elementor' ) );


		$options = [
			'wfacp-modern-label' => __( 'Floating', 'funnel-builder' ),
			'wfacp-top'          => __( 'Outside', 'funnel-builder' ),
			'wfacp-inside'       => __( 'Inside', 'funnel-builder' ),

		];
		$this->add_select( 'wfacp_label_position', __( 'Label Position', 'funnel-builder' ), $options, 'wfacp-inside' );


		$form_fields_label_typo = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce p.wfacp-form-control-wrapper label.wfacp-form-control-label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .create-account label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .create-account label span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce p.wfacp-form-control-wrapper:not(.wfacp-anim-wrap) label.wfacp-form-control-label abbr',
			'{{WRAPPER}} #wfacp-e-form .wfacp-form.wfacp-top .form-row:not(.wfacp_checkbox_field) label.wfacp-form-control-label',
			'{{WRAPPER}} #wfacp-e-form .wfacp-form.wfacp-top .form-row:not(.wfacp_checkbox_field) label.wfacp-form-control-label abbr.required',
			'{{WRAPPER}} #wfacp-e-form .wfacp-form.wfacp-top .form-row:not(.wfacp_checkbox_field) label.wfacp-form-control-label .optional',
		];


		$this->add_typography( 'wfacp_form_fields_label_typo', implode( ',', $form_fields_label_typo ) );

		$form_fields_label_color_opt = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-form-control-label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_allowed_countries strong',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-form-control-label abbr',
		];
		$this->add_color( 'wfacp_form_fields_label_color', $form_fields_label_color_opt );


		$fields_options = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="text"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="email"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="tel"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="password"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="number"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce select',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce textarea',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce number',
			'{{WRAPPER}} #wfacp-e-form .woocommerce-input-wrapper .wfacp-form-control',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .select2-container .select2-selection--single .select2-selection__rendered',
			'body:not(.wfacp_pre_built) .select2-results__option',
			'body:not(.wfacp_pre_built) .select2-container--default .select2-search--dropdown .select2-search__field',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .form-row label.checkbox',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .form-row label.checkbox *',


		];

		$optionString = implode( ',', $fields_options );

		$fields_options = [
			'font_size' => [
				'label'          => _x( 'Size', 'Typography Control', 'elementor' ),
				'default'        => [
					'unit' => 'px',
					'size' => 14,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'range'          => [
					'px' => [
						'min' => 14,
						'max' => 55,
					],
				],
			],
		];

		$this->add_heading( __( 'Input', 'funnel-builder' ) );
		$this->add_typography( 'wfacp_form_fields_input_typo', $optionString, $fields_options );


		$inputColorOption = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-input-wrapper .wfacp-form-control',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .select2-container .select2-selection--single .select2-selection__rendered',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce select',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .form-row label.checkbox',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .form-row label.checkbox *',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options *',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul li',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul li p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul li label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul li .wfacp_shipping_price span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul li .wfacp_shipping_price',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_subscription_count_wrap p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_table ul#shipping_method label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_table ul#shipping_method span',

		];
		$this->add_color( 'wfacp_form_fields_input_color', $inputColorOption );

		$inputbgColorOption = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-input-wrapper .wfacp-form-control:not(.input-checkbox):not(.hidden)',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-form-control:not(.input-checkbox):not(.hidden)',
			'{{WRAPPER}} #wfacp-e-form .wfacp_allowed_countries strong',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .select2-container .select2-selection--single .select2-selection__rendered',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce select',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-login-wrapper input[type=email]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-login-wrapper input[type=number]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-login-wrapper input[type=password]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-login-wrapper input[type=tel]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-login-wrapper select',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-login-wrapper input[type=text]',
			'{{WRAPPER}} #wfacp-e-form .wfacp-form.wfacp-inside .form-row .wfacp-form-control-label:not(.checkbox)',

		];

		$this->add_background_color( 'wfacp_form_fields_input_bg_color', $inputbgColorOption, '#ffffff' );

		$this->add_heading( __( 'Border', 'funnel-builder' ) );


		$fields_options = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="text"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="email"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="tel"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="password"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce select',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce textarea',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-input-wrapper input[type="number"].wfacp-form-control',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-input-wrapper input[type="text"].wfacp-form-control',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-input-wrapper input[type="emal"].wfacp-form-control',
			'{{WRAPPER}} #wfacp-e-form .wfacp_allowed_countries strong',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .select2-container .select2-selection--single .select2-selection__rendered',
			'{{WRAPPER}} #wfacp-e-form .iti__selected-flag',
		];

		$Validation_options = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-error',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-error ul',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-error li',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-error li strong',
			'{{WRAPPER}} #wfacp-e-form .iti__selected-flag',
		];


		$default = [ 'top' => 4, 'right' => 4, 'bottom' => 4, 'left' => 4, 'unit' => 'px' ];
		$this->add_border( 'wfacp_form_fields_border', implode( ',', $fields_options ), [], $default );

		$fields_options_hover = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="text"]:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="email"]:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="tel"]:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type="password"]:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce select:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce textarea:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-input-wrapper input[type="number"].wfacp-form-control:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-input-wrapper input[type="text"].wfacp-form-control:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-input-wrapper input[type="emal"].wfacp-form-control:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .select2-container .select2-selection--single .select2-selection__rendered:hover',

		];




		$validation_error = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce p.woocommerce-invalid-required-field .wfacp-form-control',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce p.woocommerce-invalid-email .wfacp-form-control',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce p.wfacp_coupon_failed .wfacp_coupon_code',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce p.woocommerce-invalid-required-field:not(.wfacp_select2_country_state):not(.wfacp_state_wrap) .woocommerce-input-wrapper .select2-container .select2-selection--single .select2-selection__rendered',
		];

		$focus_fields_color = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce p.form-row:not(.woocommerce-invalid-email) .wfacp-form-control:not(.wfacp_coupon_code):focus',
			'{{WRAPPER}} #wfacp-e-form p.form-row:not(.woocommerce-invalid-email) .wfacp-form-control:not(.input-checkbox):focus',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce p.wfacp_coupon_failed .wfacp_coupon_code',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .form-row:not(.woocommerce-invalid-required-field) .woocommerce-input-wrapper .select2-container .select2-selection--single .select2-selection__rendered:focus',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .form-row:not(.woocommerce-invalid-required-field) .woocommerce-input-wrapper .select2-container .select2-selection--single:focus>span.select2-selection__rendered',
		];
		$this->add_border_color( 'wfacp_form_fields_focus_color', $focus_fields_color, '#61bdf7', __( 'Focus Color', 'funnel-builder' ), true );
		$this->add_border_color( 'wfacp_form_fields_validation_color', $validation_error, '#d50000', __( 'Error Validation Color', 'funnel-builder' ), true );
		$this->end_tab();
	}

	private function section_typo_settings() {

		$this->add_tab( __( 'Section', 'funnel-builder' ), 2 );


		$form_section_bg_color = [
			'{{WRAPPER}} #wfacp-e-form .wfacp-section',

		];
		$this->add_background_color( 'form_section_bg_color', $form_section_bg_color, '', __( 'Background Color', 'funnel-builder' ) );
		$this->add_border_shadow( 'form_section_box_shadow', '{{WRAPPER}} #wfacp-e-form .wfacp-section' );
		$this->add_divider( "none" );
		$this->add_border( 'form_section_border', implode( ',', $form_section_bg_color ) );
		$this->add_divider( "none" );

		$this->add_padding( 'form_section_padding', '{{WRAPPER}} #wfacp-e-form  .wfacp-section' );
		$default = [ 'top' => 0, 'right' => 0, 'bottom' => 10, 'left' => 0, 'unit' => 'px' ];
		$this->add_margin( 'form_section_margin', '{{WRAPPER}} #wfacp-e-form .wfacp-section', $default, $default, [], $default );
		$this->end_tab();

	}

	private function payment_method() {

		$this->add_tab( __( 'Payment Gateways', 'funnel-builder' ), 5 );
		$this->add_heading( __( 'Section', 'funnel-builder' ) );
		$this->add_text( 'wfacp_payment_method_heading_text', __( 'Heading', 'funnel-builder' ), esc_attr__( 'Payment Information', 'funnel-builder' ), [], 'wfacp_field_text_wrap' );
		$this->add_textArea( 'wfacp_payment_method_subheading', __( 'Sub heading', 'funnel-builder' ), esc_attr__( 'All transactions are secure and encrypted. Credit card information is never stored on our servers.', 'funnel-builder' ) );

		$this->end_tab();

		$this->add_tab( __( 'Checkout Button(s)', 'funnel-builder' ), 5 );
		$this->form_buttons();
		$this->end_tab();


	}

	private function payment_buttons_styling() {
		$this->add_tab( __( 'Checkout Button(s)', 'funnel-builder' ), 2 );

		$selector  = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-next-btn-wrap button',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #payment button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce  button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce  #ppcp-hosted-fields .button',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .button.button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .button.wfacp_next_page_button',

		];
		$selector1 = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-next-btn-wrap button',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #payment button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce  button#place_order',
			'#wfacp_qr_model_wrap .wfacp_qr_wrap .wfacp_qv-summary .button',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce  #ppcp-hosted-fields .button',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .button.button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .button.wfacp_next_page_button',
		];

		$tablet_default = [
			'unit' => '%',
			'size' => 100,
		];
		$mobile_default = [
			'unit' => '%',
			'size' => 100,
		];

		$this->add_width( 'wfacp_button_width', implode( ',', $selector ), 'Button Width (in %)', [ 'unit' => '%', 'width' => 100 ], [], [ '%' ], $tablet_default, $mobile_default );


		$alignment = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout .wfacp-order-place-btn-wrap',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout .wfacp-next-btn-wrap',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce  #ppcp-hosted-fields',
		];


		$this->add_text_alignments( 'wfacp_form_button_alignment', $alignment, '', [], 'center', [] );

		$btntypo = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #payment button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce  button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce  #ppcp-hosted-fields .button',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout button.button.button-primary.wfacp_next_page_button'
		];

		$fields_options = [
			'font_weight' => [
				'default' => '700',
			],
			'font_size'   => [
				'default' => [
					'unit' => 'px',
					'size' => 25
				]
			],
		];

		$this->add_typography( 'wfacp_form_payment_button_typo', implode( ',', $btntypo ), $fields_options );

		/* Button Icon Style*/
		$this->button_icon_style();


		$button_bg_hover_color = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-next-btn-wrap button:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #payment button#place_order:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce  button#place_order:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce  #ppcp-hosted-fields .button:hover',
			'#wfacp_qr_model_wrap .wfacp_qr_wrap .wfacp_qv-summary .button:hover'
		];


		/* Button Background hover tab */
		$this->add_heading( __( 'Color', 'elementor' ) );
		$this->add_controls_tabs( "wfacp_button_style_tab" );
		$this->add_controls_tab( "wfacp_button_style_normal_tab", 'Normal' );
		$this->add_background_color( 'wfacp_button_bg_color', $selector1, "", 'Background' );
		$this->add_color( 'wfacp_button_label_color', $selector1, '', 'Label' );
		$this->close_controls_tab();
		$this->add_controls_tab( "wfacp_button_style_hover_tab", 'Hover' );
		$this->add_background_color( 'wfacp_button_bg_hover_color', $button_bg_hover_color, "", 'Background' );
		$this->add_color( 'wfacp_button_label_hover_color', $button_bg_hover_color, '', 'Label' );
		$this->close_controls_tab();
		$this->close_controls_tabs();


		$this->add_divider( "none" );

		$default   = [ 'top' => "15", 'right' => "25", 'bottom' => "15", 'left' => "25", 'unit' => 'px', 'isLinked' => false ];
		$Mbdefault = [ 'top' => "10", 'right' => "20", 'bottom' => "10", 'left' => "20", 'unit' => 'px', 'isLinked' => false ];
		$this->add_padding( "wfacp_button_padding", implode( ',', $selector ), $default, $Mbdefault );
		$this->add_margin( "wfacp_button_margin", implode( ',', $selector ) );
		$this->add_divider( "none" );


		$this->add_border( "wfacp_button_border", implode( ',', $selector ) );


		$stepBackLink = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form .wfacp-back-btn-wrap a',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form .btm_btn_sec.wfacp_back_cart_link .wfacp-back-btn-wrap a',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form .wfacp-back-btn-wrap a.wfacp_back_page_button',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form  .place_order_back_btn a'
		];

		$stepBackLinkHover = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form .wfacp-back-btn-wrap a:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form .btm_btn_sec.wfacp_back_cart_link .wfacp-back-btn-wrap a:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form .wfacp-back-btn-wrap a.wfacp_back_page_button:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form .place_order_back_btn a:hover'
		];


		$this->add_heading( __( 'Return Link', 'funnel-builder' ), 'none' );


		/* Back Link color setting */
		$this->add_controls_tabs( "wfacp_back_link_style" );

		$this->add_controls_tab( "wfacp_back_link_normal_tab", 'Normal' );
		$this->add_color( 'step_back_link_color', $stepBackLink, '', "Color" );
		$this->close_controls_tab();

		$this->add_controls_tab( "wfacp_back_link_hover_normal_tab", 'Hover' );
		$this->add_color( 'step_back_link_hover_color', $stepBackLinkHover, '', "Color" );
		$this->close_controls_tab();
		$this->close_controls_tabs();

		/* Back link color setting End*/


		$this->add_heading( __( 'Additional Text', 'funnel-builder' ) );
		$this->add_color( 'additional_text_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-payment-dec' ] );
		$this->add_background_color( 'additional_bg_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-payment-dec' ], "", 'Background' );


		$this->add_switcher( 'wfacp_make_button_sticky_on_mobile', __( 'Sticky on Mobile', 'funnel-builder' ), '', '', "no", 'yes', [], '', '', 'wfacp_elementor_device_hide' );

		$this->end_tab();

	}

	private function button_icon_style() {
		$template_obj = wfacp_template();

		$template_slug = $template_obj->get_template_slug();

		$this->add_heading( __( 'Button Icon', 'funnel-builder' ) );

		$btn_icon_selector = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-order-place-btn-wrap button:before',
			'{{WRAPPER}} #wfacp-e-form .wfacp-next-btn-wrap button:before'
		];


		$this->add_color( $template_slug . '_btn_icon_color', $btn_icon_selector, '#ffffff', 'Icon Color' );
		$this->add_heading( __( 'Sub Text', 'funnel-builder' ) );
		$button_sub_text_selector = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-order-place-btn-wrap button:after',
			'{{WRAPPER}} #wfacp-e-form .wfacp-next-btn-wrap button:after'
		];


		$fields_options = [
			'font_size' => [
				'default' => [
					'unit' => 'px',
					'size' => 15
				]
			],
		];
		$default        = [
			'unit' => 'px',
			'size' => 12,
		];


		$this->add_font_size( $template_slug . '_button_sub_text_font_size', implode( ',', $button_sub_text_selector ), 'Font Size (in px)', $default, [], [ 'px' ], $default, $default );
		$this->add_color( $template_slug . '_button_sub_text_color', $button_sub_text_selector, '#ffffff', 'Text Color' );

	}

	private function payment_method_styling() {
		$this->add_tab( __( 'Payment Methods', 'funnel-builder' ), 2 );

		$payment_method_typo = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods p a',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods strong',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods input',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #add_payment_method #payment .payment_box p',
		];

		$this->add_typography( 'wfacp_form_payment_method_typo', implode( ',', $payment_method_typo ) );

		/* Color Setting  */

		$this->add_heading( __( 'Colors', 'funnel-builder' ) );

		$payment_method_label_color = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout #payment ul.payment_methods li label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout #payment ul.payment_methods li label span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout #payment ul.payment_methods li label a',
		];
		$this->add_color( 'wfacp_form_payment_method_label_color', $payment_method_label_color, '', __( 'Text Color', 'funnel-builder' ) );


		$payment_method_description_color = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods li .payment_box p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods li .payment_box p span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods li .payment_box  p strong',
		];
		$this->add_color( 'wfacp_form_payment_method_description_color', $payment_method_description_color, '', __( 'Description Color', 'funnel-builder' ) );

		$payment_method_description_bg_color = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods li .payment_box',
		];
		$this->add_background_color( 'wfacp_form_payment_method_description_bg_color', $payment_method_description_bg_color, '', __( 'Information Background Color', 'funnel-builder' ) );

		$this->end_tab();
	}

	private function set_typo_default_value( $fontFamily = '' ) {

		$fields_options = [
			'font_size'       => [
				'default' => [
					'unit' => 'px',
					'size' => 14
				]
			],
			'font_weight'     => [
				'default' => '500',
			],
			'font_style'      => [
				'default' => 'normal',
			],
			'text_decoration' => [
				'default' => 'none',
			],
			'text_transform'  => [
				'default' => 'none',
			],

		];
		if ( ! empty( $fontFamily ) ) {
			$fields_options['font_family'] = [ 'default' => $fontFamily ];
		}

		$this->typo_default_value = $fields_options;

		return $this->typo_default_value;
	}

	private function global_typography() {
		$this->add_tab( __( 'Checkout Form', 'funnel-builder' ), 2 );

		$selector = [
			'body:not(.wfacpef_page) {{WRAPPER}} #wfacp-e-form .wfacp-form',
		];


		$globalSettingOptions = [
			'body.wfacp_main_wrapper',
			'body #wfacp-e-form *:not(i)',
			'body .wfacp_qv-main *',
			'{{WRAPPER}} #wfacp-e-form .wfacp_section_heading.wfacp_section_title',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_whats_included h3',
			'{{WRAPPER}}  #wfacp-e-form .wfacp_main_form .wfacp_whats_included .wfacp_product_switcher_description .wfacp_description a',
			'{{WRAPPER}}  #wfacp-e-form .wfacp_main_form .wfacp_whats_included .wfacp_product_switcher_description .wfacp_description',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-section h4',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form p.wfacp-form-control-wrapper label.wfacp-form-control-label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form input[type="text"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form input[type="email"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form input[type="tel"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form input[type="number"]',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form select',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form textarea',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form a',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form label span a',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form a',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form button',
			'{{WRAPPER}} #wfacp-e-form #payment button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce  button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form ul li',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form ul li span',
			'{{WRAPPER}} #wfacp-e-form .woocommerce-form-login-toggle .woocommerce-info ',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form ul li span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-payment-dec',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form label.checkbox',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-title > div',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_shipping_table ul li label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .select2-container .select2-selection--single .select2-selection__rendered',
			'{{WRAPPER}} #et-boc .et-l span.select2-selection.select2-selection--multiple',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_sec *',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_quantity_selector input',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_price_sec span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_product_sec *',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_quantity_selector input',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_product_price_sec span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form #product_switching_field fieldset .wfacp_best_value',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel .wfacp_product_switcher_col_2 .wfacp_you_save_text',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_whats_included .wfacp_product_switcher_description h4',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_product_sec .wfacp_product_select_options .wfacp_qv-button',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form #product_switching_field .wfacp_product_switcher_col_2 .wfacp_product_subs_details > span:not(.subscription-details):not(.woocommerce-Price-amount):not(.woocommerce-Price-currencySymbol)',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-coupon-section .wfacp-coupon-page .woocommerce-info > span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_woocommerce_form_coupon .wfacp-coupon-section .woocommerce-info .wfacp_showcoupon',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form label.woocommerce-form__label span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr th',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot .shipping_total_fee td',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr td',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr td span.woocommerce-Price-amount.amount',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr td span.woocommerce-Price-amount.amount bdi',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr td p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_best_value',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tbody .wfacp_order_summary_item_name',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) td small',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) th small',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce table tfoot tr.order-total td small',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody .wfacp_order_summary_item_name',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody .product-name .product-quantity',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody td.product-total',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody .wfacp_order_summary_container dl',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody .wfacp_order_summary_container dd',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody .wfacp_order_summary_container dt',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody .wfacp_order_summary_container p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody tr span.amount',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody tr span.amount bdi',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody .wfacp_order_summary_item_name',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody .cart_item .product-total span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody .cart_item .product-total small',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody .cart_item .product-total span.amount',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody .cart_item .product-total span.amount bdi',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody .product-name .product-quantity',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody td.product-total',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tbody dl',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tbody dd',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tbody dt',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tbody p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody tr span.amount',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table.shop_table tbody tr span.amount bdi',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_product_sec .wfacp_product_select_options .wfacp_qv-button',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form #product_switching_field .wfacp_product_switcher_col_2 .wfacp_product_subs_details > span:not(.subscription-details):not(.woocommerce-Price-amount):not(.woocommerce-Price-currencySymbol)',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_you_save_text',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_row_wrap .wfacp_you_save_text span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_whats_included .wfacp_product_switcher_description .wfacp_description p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_coupon_field_msg',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-coupon-page .wfacp_coupon_remove_msg',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-coupon-page .wfacp_coupon_error_msg',
			'body:not(.wfacp_pre_built) .select2-results__option',
			'body:not(.wfacp_pre_built) .select2-container--default .select2-search--dropdown .select2-search__field',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_order_total_field table.wfacp_order_total_wrap tr td',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_order_total_field table.wfacp_order_total_wrap tr td span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_order_total .wfacp_order_total_wrap',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form #payment button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form  button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce  button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-checkout button.button.button-primary.wfacp_next_page_button',
			'{{WRAPPER}} #wfacp-e-form .wfacp-order2StepTitle.wfacp-order2StepTitleS1',
			'{{WRAPPER}} #wfacp-e-form .wfacp-order2StepSubTitle.wfacp-order2StepSubTitleS1',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_steps_sec ul li a',
			'{{WRAPPER}} #wfacp-e-form .wfacp_custom_breadcrumb ul li a',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr td span ',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form p.wfacp-form-control-wrapper:not(.wfacp-anim-wrap) label.wfacp-form-control-label abbr',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_you_save_text',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_row_wrap .wfacp_you_save_text span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_row_wrap .wfacp_product_subs_details span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form p.wfacp-form-control-wrapper.wfacp_checkbox_field label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .create-account label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .create-account label span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form p.wfacp-form-control-wrapper.wfacp_checkbox_field label span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form p.wfacp-form-control-wrapper.wfacp_custom_field_radio_wrap > label ',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) ul',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) ul li',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) ul li label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) td span.woocommerce-Price-amount.amount',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) td span.woocommerce-Price-amount.amount bdi',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_sec .wfacp_product_name_inner *',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_sec .wfacp_product_attributes .wfacp_selected_attributes  *',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_quantity_selector input',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_price_sec span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_switcher_col_2 .wfacp_product_subs_details > span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_subs_details span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_subs_details *',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_sec .wfacp_product_select_options .wfacp_qv-button',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_sec .wfacp_product_name_inner *',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_sec .wfacp_product_attributes .wfacp_selected_attributes  *',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_quantity_selector input',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_price_sec span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_switcher_col_2 .wfacp_product_subs_details > span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_subs_details span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_subs_details *',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_sec .wfacp_product_select_options .wfacp_qv-button',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_woocommerce_form_coupon .wfacp-coupon-section .wfacp-coupon-field-btn',
			'{{WRAPPER}} #wfacp-e-form .wfacp_mb_mini_cart_sec_accordion_content form.checkout_coupon button.button.wfacp-coupon-btn',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_shipping_options',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_shipping_options ul li',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_shipping_options ul li p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_shipping_options ul li .wfacp_shipping_price span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_shipping_options ul li .wfacp_shipping_price',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_payment',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_payment p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_payment p span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_payment p a',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_payment label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_payment ul',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_payment ul li',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_payment ul li input',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_payment #add_payment_method #payment div.payment_box',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_payment #add_payment_method #payment .payment_box p',
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .woocommerce-info > a',
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .woocommerce-info > a:not(.wfacp_close_icon):not(.button-social-login):not(.wfob_btn_add):not(.ywcmas_shipping_address_button_new):not(.wfob_qv-button):not(.wfob_read_more_link):not(.wfacp_step_text_have ):not(.wfacp_cart_link)',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount)',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) th',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) th span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td small',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td bdi',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td a',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods p a',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods strong',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods input',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #add_payment_method #payment .payment_box p',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .wfacp_order_summary_item_name',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .product-name .product-quantity',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody td.product-total',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .cart_item .product-total span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .cart_item .product-total span.amount',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .cart_item .product-total span.amount bdi',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .cart_item .product-total small',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .wfacp_order_summary_container dl',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .wfacp_order_summary_container dd',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .wfacp_order_summary_container dt',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .wfacp_order_summary_container p',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody tr span.amount',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody tr span.amount bdi',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody dl',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody dd',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody dt',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody p',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody tr td span:not(.wfacp-pro-count)',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount td',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount td span',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount td a',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount td span',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount td span bdi',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount th .wfacp_coupon_code',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td span.woocommerce-Price-amount.amount',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td span.woocommerce-Price-amount.amount bdi',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td p',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td small',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td a',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td p',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th small',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th a'
		];

		$this->add_font_family( 'wfacp_font_family', $globalSettingOptions, 'Family', 'Open Sans' );


		$primary_color = [
			'{{WRAPPER}} #wfacp-e-form  #payment li.wc_payment_method input.input-radio:checked::before',
			'{{WRAPPER}} #wfacp-e-form  #payment.wc_payment_method input[type=radio]:checked:before',
			'{{WRAPPER}} #wfacp-e-form  button[type=submit]:not(.white):not(.black)',
			'{{WRAPPER}} #wfacp-e-form  button[type=button]:not(.white):not(.black)',
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page .wfacp-coupon-field-btn',
			'{{WRAPPER}} #wfacp-e-form input[type=checkbox]:checked',
			'{{WRAPPER}} #wfacp-e-form #payment input[type=checkbox]:checked',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-input-wrapper .wfacp-form-control:checked',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type=checkbox]:checked',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .button.button#place_order',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .button.wfacp_next_page_button',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form  #ppcp-hosted-fields .button',
		];


		$this->add_background_color( 'default_primary_color', [], '', "Primary Color" );


		$fields_contentColor = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-form-login-toggle .woocommerce-info',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-form-login.login p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-privacy-policy-text p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-info .message-container',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form #wc_checkout_add_ons .description',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-checkout-review-order h3',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .aw_addon_wrap label',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form p:not(.woocommerce-shipping-contents):not(.wfacp_dummy_preview_heading )',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form p label:not(.wfacp-form-control-label):not(.wfob_title):not(.wfob_span):not(.checkbox)',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-message',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-error',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_payment h4',
			'{{WRAPPER}} #wfacp-e-form #payment .woocommerce-privacy-policy-text p',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .wfacp_whats_included .wfacp_product_switcher_description .wfacp_description p',
			'{{WRAPPER}} #wfacp-e-form .wfacp-form label.woocommerce-form__label .woocommerce-terms-and-conditions-checkbox-text',
			'{{WRAPPER}} #wfacp-e-form fieldset',
			'{{WRAPPER}} #wfacp-e-form fieldset legend',
		];


		$this->add_color( 'default_text_color', $fields_contentColor, '', "Content Color" );

		$default_link_color_option = [
			'{{WRAPPER}} #wfacp-e-form .woocommerce-form-login-toggle .woocommerce-info a',
			'{{WRAPPER}} #wfacp-e-form a:not(.wfacp_close_icon):not(.button-social-login):not(.wfob_btn_add):not(.ywcmas_shipping_address_button_new):not(.wfob_qv-button):not(.wfob_read_more_link):not(.wfacp_step_text_have ):not(.wfacp_cart_link):not(.wfacp_summary_link)',
			'{{WRAPPER}} #wfacp-e-form a:not(.wfacp_summary_link) span:not(.wfob_btn_text_added):not(.wfob_btn_text_remove)',
			'{{WRAPPER}} #wfacp-e-form label a',
			'{{WRAPPER}} #wfacp-e-form ul li a:not(.wfacp_breadcrumb_link)',
			'{{WRAPPER}} #wfacp-e-form table tr td a',
			'{{WRAPPER}} #wfacp-e-form .wfacp_steps_sec ul li a',
			'{{WRAPPER}} #wfacp-e-form a.wfacp_remove_coupon',
			'{{WRAPPER}} #wfacp-e-form a:not(.button-social-login):not(.wfob_read_more_link)',
			'{{WRAPPER}} #wfacp-e-form .wfacp-login-wrapper input#rememberme + span',
			'{{WRAPPER}} #wfacp-e-form #product_switching_field .wfacp_product_switcher_col_2 .wfacp_product_switcher_description a.wfacp_qv-button'
		];


		$default_link_hover_color_option = [
			'{{WRAPPER}} #wfacp-e-form .woocommerce-form-login-toggle .woocommerce-info a:hover',
			'{{WRAPPER}} #wfacp-e-form a:not(.wfacp_close_icon):not(.button-social-login):hover:not(.wfob_btn_add):hover:not(.ywcmas_shipping_address_button_new):hover:not(.wfacp_cart_link):hover:not(.wfacp_back_page_button):hover:not(.wfacp_summary_link)',
			'{{WRAPPER}} #wfacp-e-form a:not(.wfacp_summary_link) span:not(.wfob_btn_text_added):not(.wfob_btn_text_remove):hover',
			'{{WRAPPER}} #wfacp-e-form label a:hover',
			'{{WRAPPER}} #wfacp-e-form ul li a:not(.wfacp_breadcrumb_link):hover',
			'{{WRAPPER}} #wfacp-e-form table tr td a:hover',
			'{{WRAPPER}} #wfacp-e-form a.wfacp_remove_coupon:hover',
			'{{WRAPPER}} #wfacp-e-form a:not(.button-social-login):not(.wfob_read_more_link):hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp-login-wrapper input#rememberme + span:hover',
			'{{WRAPPER}} #wfacp-e-form #product_switching_field .wfacp_product_switcher_col_2 .wfacp_product_switcher_description a.wfacp_qv-button:hover'
		];


		/* Button Background hover tab */

		$this->add_controls_tabs( "wfacp_form_link_color_tab" );
		$this->add_controls_tab( "wfacp_form_link_color_normal_tab", 'Normal' );
		$this->add_color( 'default_link_color', $default_link_color_option, '', 'Link Normal Color' );
		$this->close_controls_tab();
		$this->add_controls_tab( "wfacp_form_link_color_hover_tab", 'Hover' );
		$this->add_color( 'default_link_hover_color', $default_link_hover_color_option, '', 'Link Hover Color' );
		$this->close_controls_tab();
		$this->close_controls_tabs();


		$default    = [ 'top' => 0, 'right' => 10, 'bottom' => 10, 'left' => 10, 'unit' => 'px', 'isLinked' => true ];
		$mb_default = [ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px', 'isLinked' => false ];
		$this->add_padding( 'wfacp_form_border_padding', implode( ',', $selector ), $default, $mb_default, [], $default );


		$this->end_tab();

	}

	private function collapsible_order_summary() {
		$this->add_tab( __( 'Collapsible Order Summary', 'funnel-builder' ), 2 );
		$advanceOption = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_mb_mini_cart_sec_accordion_content',
		];


		$this->add_background_color( 'collapsible_order_summary_bg_color', [ '{{WRAPPER}} #wfacp-e-form .wfacp_mb_mini_cart_wrap .wfacp_mb_cart_accordian' ], '#f7f7f7', 'Collapsed Background' );
		$this->add_background_color( 'expanded_order_summary_bg_color', $advanceOption, '#f7f7f7', 'Expanded Background' );
		$this->add_color( 'expanded_order_summary_link_color', [
			'{{WRAPPER}} #wfacp-e-form .wfacp_show_icon_wrap a span',
			'{{WRAPPER}} #wfacp-e-form .wfacp_show_price_wrap span'
		], '#323232', __( 'Text Color', 'funnel-builder' ) );

		$selector = [
			'{{WRAPPER}} #wfacp-e-form .wfacp_collapsible_order_summary_wrap'
		];

		$default = [ 'top' => 0, 'right' => 0, 'bottom' => 15, 'left' => 0, 'unit' => 'px', 'isLinked' => true ];
		$this->add_margin( 'wfacp_collapsible_margin', implode( ',', $selector ), $default, $default, [], $default );

		$label = __( 'Border Radius', 'funnel-builder' );
		$this->add_border_radius( 'wfacp_collapsible_border_radius', '{{WRAPPER}} #wfacp-e-form .wfacp_mb_mini_cart_wrap .wfacp_mb_cart_accordian, {{WRAPPER}} #wfacp-e-form .wfacp_mb_mini_cart_wrap .wfacp_mb_mini_cart_sec_accordion_content', [], '', '', $label );

		//$this->collapsible_summary_coupon();
		do_action( 'wfacp_elementor_collapsible_fields_settings', $this );
		$this->end_tab();

	}

	private function class_section() {
		$template           = wfacp_template();
		$template_slug      = $template->get_template_slug();
		$do_not_show_fields = WFACP_Common::get_html_excluded_field();
		$this->add_tab( __( 'Field Classes', 'funnel-builder' ), 3 );


		$sections = $this->section_fields;
		foreach ( $sections as $keys => $val ) {
			foreach ( $val as $loop_key => $field ) {
				if ( in_array( $loop_key, [ 'wfacp_start_divider_billing', 'wfacp_start_divider_shipping' ], true ) ) {
					$address_key_group = ( $loop_key == 'wfacp_start_divider_billing' ) ? __( 'Billing Address', 'funnel-builder' ) : __( 'Shipping Address', 'funnel-builder' );
					$this->add_heading( $address_key_group, 'none' );
				}

				if ( ! isset( $field['id'] ) || ! isset( $field['label'] ) ) {
					continue;
				}

				$field_key = $field['id'];

				if ( in_array( $field_key, $do_not_show_fields ) ) {
					$this->html_fields[ $field_key ] = true;
					continue;
				}


				$skipKey = [ 'billing_same_as_shipping', 'shipping_same_as_billing' ];
				if ( in_array( $field_key, $skipKey ) ) {
					continue;
				}
				$this->add_text( 'wfacp_' . $template_slug . '_' . $field_key . '_field_class', __( $field['label'], 'funnel-builder' ), '', [], '', '', 'Custom Class' );

			}
		}


		$this->end_tab();
	}

	protected function get_class_options() {
		return [
			'wfacp-col-full'       => __( 'Full', 'funnel-builder' ),
			'wfacp-col-left-half'  => __( 'One Half', 'funnel-builder' ),
			'wfacp-col-left-third' => __( 'One Third', 'funnel-builder' ),
			'wfacp-col-two-third'  => __( 'Two Third', 'funnel-builder' ),
		];
	}

	protected function html() {
		$template = wfacp_template();
		if ( null === $template ) {
			return;
		}


		$id = $this->get_id();
		if ( WFACP_Common::is_theme_builder() ) {
			do_action( 'wfacp_form_widgets_elementor_editor', $this );
			add_filter( 'wfacp_forms_field', [ $this, 'modern_label' ], 20, 2 );
		}

		$setting = WFACP_Common::get_session( $id );


		$template->set_form_data( $setting );

		/**
		 * @var $template WFACP_Elementor_template;
		 */
		if ( isset( $_COOKIE['wfacp_elementor_open_page'] ) && wp_doing_ajax() ) {
			$cookie             = $_COOKIE['wfacp_elementor_open_page'];
			$parts              = explode( '@', $cookie );
			$this->current_step = $parts[1];
			if ( ! empty( $this->current_step ) && 'single_step' !== $this->current_step ) {
				$template->set_current_open_step( $this->current_step );
				add_filter( 'wfacp_el_bread_crumb_active_class_key', [ $this, 'set_breadcrumb' ], 10, 2 );
			}


		}


		include $template->wfacp_get_form();


	}

	public function set_breadcrumb( $active, $instance ) {
		if ( ! empty( $this->current_step ) && 'single_step' !== $this->current_step ) {
			if ( 'two_step' == $this->current_step ) {
				$active = 1;
			} else if ( 'third_step' == $this->current_step ) {
				$active = 2;
			} else {
				$active = 0;
			}
		}

		return $active;
	}

	/* ----------------Privacy Policy & Term Conditions----------------------- */
	private function privacy_policy_styling() {
		$this->add_tab( __( 'Privacy Policy', 'funnel-builder' ), 2 );

//		$privacy_policy_selector = $this->get_privacy_policy_selector();
//		extract( $privacy_policy_selector );


		$typo = [
			'{{WRAPPER}} #wfacp-e-form #payment .woocommerce-privacy-policy-text p',
			'{{WRAPPER}} #wfacp-e-form #payment .woocommerce-privacy-policy-text a',
		];

		$color = [
			'{{WRAPPER}} #wfacp-e-form #payment .woocommerce-privacy-policy-text p',
		];

		$default = [
			'unit' => 'px',
			'size' => 12,
		];


		$this->add_font_size( 'wfacp_privacy_policy_font_size', implode( ',', $typo ), 'Font Size (in px) 1', $default, [], [ 'px' ], $default, $default );
		$this->add_color( 'wfacp_privacy_policy_color', $color, '#777777', 'Color' );

		$this->end_tab();
	}

	private function terms_policy_styling() {
		$this->add_tab( __( 'Terms & Conditions', 'funnel-builder' ), 2 );
		$typo = [
			'{{WRAPPER}} #wfacp-e-form .wfacp-form .wfacp_main_form.woocommerce .woocommerce-terms-and-conditions-wrapper .form-row label',
			'{{WRAPPER}} #wfacp-e-form .wfacp-form .wfacp_main_form.woocommerce .woocommerce-terms-and-conditions-wrapper .form-row label span',
			'{{WRAPPER}} #wfacp-e-form .wfacp-form .wfacp_main_form.woocommerce.woocommerce-terms-and-conditions-wrapper .form-row label a',
			'{{WRAPPER}} #wfacp-e-form .wfacp-form .wfacp_main_form.woocommerce label.woocommerce-form__label .woocommerce-terms-and-conditions-checkbox-text',
			'{{WRAPPER}} #wfacp-e-form .wfacp-form .wfacp_main_form.woocommerce label.woocommerce-form__label .woocommerce-terms-and-conditions-checkbox-text a',

		];

		$color = [
			'{{WRAPPER}} #wfacp-e-form .wfacp-form .wfacp_main_form.woocommerce .woocommerce-terms-and-conditions-wrapper .form-row',
			'{{WRAPPER}} #wfacp-e-form  .wfacp-form .wfacp_main_form.woocommerce .woocommerce-terms-and-conditions-wrapper .woocommerce-terms-and-conditions-checkbox-text',
			'{{WRAPPER}} #wfacp-e-form .wfacp-form .wfacp_main_form.woocommerce label.woocommerce-form__label .woocommerce-terms-and-conditions-checkbox-text',

		];


		$default = [
			'unit' => 'px',
			'size' => 14,
		];

		$range = [
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
			'px' => [
				'min'  => 0,
				'max'  => 22,
				'step' => 1,
			],
		];

		$this->add_font_size( 'wfacp_terms_condition_font_size', implode( ',', $typo ), 'Font Size (in px)', $default, [], [ 'px' ], $default, $default, $range );
		$this->add_color( 'wfacp_terms_condition_color', $color, '', 'Color' );

		$this->end_tab();
	}

	/* -------------------------------End--------------------------------------- */


	/* ----------------Modern Label----------------------- */

	public function modern_label( $field ) {
		if ( empty( $field ) ) {
			return $field;
		}
		$data = $this->get_settings();
		if ( 'wfacp-modern-label' != $data['wfacp_label_position'] || ! isset( $field['placeholder'] ) ) {
			return $field;
		}

		return WFACP_Common::live_change_modern_label( $field );
	}

	public function migrate_label( $el, $data ) {

		$json_data = json_encode( $data );
		// Do not run migration if label matched with top ,inside class

		if ( false !== strpos( $json_data, 'wfacp-modern-label' ) ) {
			$field_label = 'wfacp-modern-label';
			WFACP_Common_Helper::modern_label_migrate( $el->get_post()->ID );
		} else if ( false !== strpos( $json_data, 'wfacp-top' ) ) {
			$field_label = 'wfacp-top';
		} else {
			$field_label = 'wfacp-inside';
		}

		update_post_meta( $el->get_post()->ID, '_wfacp_field_label_position', $field_label );


	}

	/* -------------------------------End--------------------------------------- */

}

if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
	\Elementor\Plugin::instance()->widgets_manager->register( new \El_WFACP_Form_Widget() );
} else {
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \El_WFACP_Form_Widget() );
}