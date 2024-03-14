<?php

class WFACP_Divi_Form extends WFACP_Divi_HTML_BLOCK {
	public $slug = 'wfacp_checkout_form';
	public $form_sub_headings = [];
	protected $get_local_slug = 'wfacp_form';
	protected $id = 'wfacp_divi_checkout_form';
	private $custom_class_tab_id = '';

	public function __construct() {
		$this->name = __( 'Checkout Form', 'funnel-builder' );
		parent::__construct();
	}

	/**
	 * @param $template WFACP_Template_Common;
	 */
	public function setup_data( $template ) {
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

		$this->form_buttons();

	}

	private function register_section_fields() {
		$template                  = wfacp_template();
		$steps                     = $template->get_fieldsets();
		$do_not_show_fields        = WFACP_Common::get_html_excluded_field();
		$exclude_fields            = [];
		$this->custom_class_tab_id = $this->add_tab( __( 'Field Classes', 'funnel-builder' ), 3 );
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
				$title = $section_data['name'];
				if ( empty( $title ) ) {
					$title = $this->get_title();
				}
				if ( isset( $section_data['sub_heading'] ) && ! empty( $section_data['sub_heading'] ) ) {
					$this->form_sub_headings[] = $section_data['sub_heading'];
				}
				$tab_id = $this->add_tab( $title, 5 );
				$this->register_fields( $section_data['fields'], $tab_id );
			}
		}
	}

	private function register_fields( $temp_fields, $tab_id ) {
		$template               = wfacp_template();
		$template_slug          = $template->get_template_slug();
		$template_cls           = $template->get_template_fields_class();
		$default_cls            = $template->default_css_class();
		$do_not_show_fields     = WFACP_Common::get_html_excluded_field();
		$this->section_fields[] = $temp_fields;
		foreach ( $temp_fields as $loop_key => $field ) {
			if ( in_array( $loop_key, [ 'wfacp_start_divider_billing', 'wfacp_start_divider_shipping' ], true ) ) {
				$address_key_group = ( $loop_key == 'wfacp_start_divider_billing' ) ? __( 'Billing Address', 'woocommerce' ) : __( 'Shipping Address', 'woocommerce' );
				$this->add_heading( $tab_id, $address_key_group, 'none' );
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

			$this->add_select( $tab_id, 'wfacp_' . $template_slug . '_' . $field_key . '_field', $field['label'], $options, $field_default_cls );
			if ( ! empty( $this->custom_class_tab_id ) ) {
				$this->add_text( $this->custom_class_tab_id, 'wfacp_' . $template_slug . '_' . $field_key . '_field_class', __( $field['label'], 'woofunnels-aero-checkout' ), '', [], '', __( 'Custom Class', 'woofunnels-aero-checkout' ) );
			}
		}
	}

	private function breadcrumb_bar() {
		$template     = wfacp_template();
		$num_of_steps = $template->get_step_count();
		if ( $num_of_steps >= 1 ) {
			$stepsCounter          = 1;
			$tab_name              = __( 'Steps', 'funnel-builder' );
			$enable_condition_name = __( 'Enable Steps', 'funnel-builder' );
			$options               = [
				'tab'       => __( 'Tabs', 'funnel-builder' ),
				'bredcrumb' => __( 'Breadcrumb', 'funnel-builder' ),
			];
			$default               = "off";
			if ( $num_of_steps == 1 ) {
				$tab_name              = __( 'Header', 'funnel-builder' );
				$enable_condition_name = __( 'Enable', 'funnel-builder' );
				unset( $options['bredcrumb'] );
			}

		}
		$tab_id = $this->add_tab( $tab_name, 5 );
		$this->add_switcher( $tab_id, 'enable_progress_bar', $enable_condition_name, $default );
		$this->add_responsive_control( 'enable_progress_bar' );
		$enableOptions = [
			'enable_progress_bar' => 'on',
		];
		$this->add_select( $tab_id, 'select_type', __( "Select Type", 'funnel-builder' ), $options, 'tab', $enableOptions );
		$bredcrumb_controls = [
			'select_type'         => 'bredcrumb',
			'enable_progress_bar' => "on"
		];
		$progress_controls  = [
			'select_type'         => [
				'progress_bar'
			],
			'enable_progress_bar' => "on"
		];
		$labels             = [
			[
				'heading'     => __( 'SHIPPING', 'funnel-builder' ),
				'sub-heading' => '',
			],
			[
				'heading'     => __( 'PRODUCTS', 'funnel-builder' ),
				'sub-heading' => '',
			],
			[
				'heading'     => __( 'PAYMENT', 'funnel-builder' ),
				'sub-heading' => '',
			],
		];
		for ( $bi = 0; $bi < $num_of_steps; $bi ++ ) {
			$heading    = $labels[ $bi ]['heading'];
			$subheading = $labels[ $bi ]['sub-heading'];
			$label      = __( 'Step', 'funnel-builder' );
			if ( $num_of_steps > 1 ) {
				$this->add_heading( $tab_id, $label . " " . $stepsCounter, 'none', [ 'enable_progress_bar' => "on" ] );
			}
			$default_val = "Step " . $stepsCounter;
			$this->add_text( $tab_id, 'step_' . $bi . '_bredcrumb', __( "Title", 'funnel-builder' ), $default_val, $bredcrumb_controls );
			$this->add_text( $tab_id, 'step_' . $bi . '_progress_bar', __( "Heading", 'funnel-builder' ), "Step $stepsCounter", $progress_controls );
			$this->add_text( $tab_id, 'step_' . $bi . '_heading', __( "Heading", 'funnel-builder' ), $heading, [
				'select_type'         => 'tab',
				'enable_progress_bar' => "on"
			] );
			$this->add_text( $tab_id, 'step_' . $bi . '_subheading', __( "Sub Heading", 'funnel-builder' ), $subheading, [
				'select_type'         => 'tab',
				'enable_progress_bar' => "on"
			] );
			$stepsCounter ++;
		}
		if ( $num_of_steps > 1 ) {
			$condtion_control   = [
				'select_type'         => [
					'bredcrumb',
					'progress_bar',
				],
				'enable_progress_bar' => "on"
			];
			$cartTitle          = __( 'Title', 'funnel-builder' );
			$progresscartTitle  = __( 'Cart title', 'funnel-builder' );
			$settingDescription = __( 'Note: Cart settings will work for Global Checkout when user navigates from Product > Cart > Checkout', 'funnel-builder' );
			$cartText           = __( 'Cart', 'funnel-builder' );
			$options            = [
				'yes' => __( 'Yes', 'funnel-builder' ),
				'no'  => __( 'No', 'funnel-builder' ),
			];
			$this->add_heading( $tab_id, 'Cart', 'none', $bredcrumb_controls );
			$this->add_select( $tab_id, 'step_cart_link_enable', __( "Add to Breadcrumb", 'funnel-builder' ), $options, 'yes', $condtion_control );
			$this->add_text( $tab_id, 'step_cart_progress_bar_link', $progresscartTitle, $cartText, $progress_controls, $settingDescription );
			$this->add_text( $tab_id, 'step_cart_bredcrumb_link', $cartTitle, $cartText, $bredcrumb_controls, $settingDescription );
		}
	}


	private function payment_method() {
		$tab_id = $this->add_tab( __( 'Payment Gateways', 'funnel-builder' ), 5 );
		$this->add_heading( $tab_id, __( 'Section', 'funnel-builder' ) );
		$this->add_text( $tab_id, 'wfacp_payment_method_heading_text', __( 'Heading', 'funnel-builder' ), esc_attr__( 'Payment Information', 'funnel-builder' ), [], '' );
		$this->add_textArea( $tab_id, 'wfacp_payment_method_subheading', __( __( 'Sub Heading', 'funnel-builder' ), 'woofunnel-aero-checkout' ), '' );

	}

	private function form_buttons() {
		$tab_id      = $this->add_tab( __( 'Checkout Button(s)', 'funnel-builder' ), 5 );
		$template    = wfacp_template();
		$count       = $template->get_step_count();
		$backLinkArr = [];
		$this->add_heading( $tab_id, __( 'Button Text', 'funnel-builder' ), 'none' );
		for ( $i = 1; $i <= $count; $i ++ ) {
			$button_default_text = __( 'NEXT STEP →', 'funnel-builder' );
			$button_key          = 'wfacp_payment_button_' . $i . '_text';
			$button_label        = "Step {$i}";
			$text_key            = $i;
			if ( $i == $count ) {
				$text_key            = 'place_order';
				$button_key          = 'wfacp_payment_place_order_text';
				$button_default_text = __( 'PLACE ORDER NOW', 'funnel-builder' );
				$button_label        = __( 'Place Order', 'funnel-builder' );
			}
			$this->add_text( $tab_id, $button_key, __( $button_label, 'woofunnel-aero-checkout' ), esc_js( $button_default_text ), [] );

			$this->icon_text( $tab_id, $text_key );
			if ( $i == $count ) {
				$this->add_switcher( $tab_id, 'enable_price_in_place_order_button', __( 'Enable Price', 'funnel-builder' ), 'yes' );

			}
			if ( $i > 1 ) {
				$backCount                                            = $i - 1;
				$backLinkArr[ 'payment_button_back_' . $i . '_text' ] = [
					'label' => __( "Return to Step {$backCount}", 'funnel-builder' ),
				];
			}
		}
		if ( is_array( $backLinkArr ) && count( $backLinkArr ) > 0 ) {
			$this->add_heading( $tab_id, __( 'Return Link Text', 'funnel-builder' ), 'none' );
			$cart_name = __( '« Return to Cart', 'funnel-builder' );
			$this->add_text( $tab_id, "return_to_cart_text", 'Return to Cart', $cart_name, [ 'step_cart_link_enable' => 'yes' ] );
			foreach ( $backLinkArr as $i => $val ) {
				$this->add_text( $tab_id, $i, $val['label'], '', [] );
			}
		}
		$this->add_text( $tab_id, 'text_below_placeorder_btn', __( "Text Below Place Order Button", 'funnel-builder' ) );
	}

	private function mobile_mini_cart() {
		$tab_id = $this->add_tab( __( 'Collapsible Order Summary', 'funnel-builder' ), 5 );
		$this->add_switcher( $tab_id, 'enable_callapse_order_summary', __( 'Enable', 'funnel-builder' ), 'off' );
		$this->add_responsive_control( 'enable_callapse_order_summary' );
		$this->add_switcher( $tab_id, 'order_summary_enable_product_image_collapsed', __( 'Enable Image', 'funnel-builder' ), 'yes' );

		$this->add_text( $tab_id, 'cart_collapse_title', __( 'Collapsed View Text ', 'funnel-builder' ), __( 'Show Order Summary', 'funnel-builder' ) );
		$this->add_text( $tab_id, 'cart_expanded_title', __( 'Expanded View Text', 'funnel-builder' ), __( 'Hide Order Summary', 'funnel-builder' ) );

		$collapse_enable_coupon = [
			'collapse_enable_coupon' => 'on',
		];
		$this->add_switcher( $tab_id, 'collapse_enable_coupon', __( 'Enable Coupon', 'funnel-builder' ), 'on' );
		$this->add_switcher( $tab_id, 'collapse_enable_coupon_collapsible', __( 'Collapsible Coupon Field', 'funnel-builder' ), 'off', $collapse_enable_coupon );
		$this->add_text( $tab_id, 'collapse_coupon_button_text', __( 'Coupon Button Text', 'funnel-builder' ), __( 'Apply', 'woocommerce' ), $collapse_enable_coupon );
		$this->add_switcher( $tab_id, 'collapse_order_quantity_switcher', __( 'Quantity Switcher', 'funnel-builder' ), 'on', $collapse_enable_coupon );
		$this->add_switcher( $tab_id, 'collapse_order_delete_item', __( 'Allow Deletion', 'funnel-builder' ), 'on', $collapse_enable_coupon );
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
	}

	public function get_progress_settings() {
		$template        = wfacp_template();
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
		$controlsCondition      = [
			'select_type' => [
				'bredcrumb',
				'progress_bar',
				'tab',
			],
		];
		$tab_condition          = [ 'select_type' => 'tab', 'enable_progress_bar' => 'on' ];
		$breadcrumb_condition   = [ 'select_type' => 'bredcrumb', 'enable_progress_bar' => 'on' ];
		$progress_bar_condition = [ 'select_type' => 'progress_bar', 'enable_progress_bar' => 'on' ];
		$tab_id                 = $this->add_tab( __( $step_text, 'woofunnels-aero-checkout' ), 2 );
		$this->add_heading( $tab_id, 'Heading Typography', '', $tab_condition );
		$font_side_default = [ 'default' => '17px', 'unit' => 'px' ];
		$this->add_typography( $tab_id, 'tab_heading_typography', '%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-order2StepTitle.wfacp-order2StepTitleS1', 'Heading', [], $tab_condition, $font_side_default );
		$this->add_heading( $tab_id, 'Subheading Typography', '', $tab_condition );
		$this->add_typography( $tab_id, 'tab_subheading_typography', '%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-order2StepSubTitle.wfacp-order2StepSubTitleS1', __( 'Sub Heading', 'woofunnels-aero-checkout' ), [], $tab_condition );
		$alignmentOption = [ '%%order_class%% #wfacp-e-form .wfacp-payment-tab-list .wfacp-order2StepHeaderText' ];
		$this->add_text_alignments( $tab_id, 'tab_text_alignment', $alignmentOption, '', 'center', $tab_condition );
		$this->add_typography( $tab_id, 'progress_bar_heading_typography', '%%order_class%% #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li a', 'Heading', [], $progress_bar_condition );
		/* Breadcrumb */
		$this->add_heading( $tab_id, 'Heading Typography', '', $breadcrumb_condition );
		$this->add_typography( $tab_id, 'breadcrumb_heading_typography', '%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_steps_sec ul li a', 'Heading', [], $breadcrumb_condition );
		/* color setting */
		$controls_tabs_id      = $this->add_controls_tabs( $tab_id, "Colors", $breadcrumb_condition );
		$breadcrumb_text_color = $this->add_color( $tab_id, 'breadcrumb_text_color', [ '%%order_class%% #wfacp-e-form .wfacp-form .wfacp_main_form.woocommerce .wfacp_steps_sec ul li a' ], 'Color', '#000000 ' );
		$this->add_controls_tab( $controls_tabs_id, 'Normal', [ $breadcrumb_text_color ] );
		$breadcrumb_text_hover_color = $this->add_color( $tab_id, 'breadcrumb_text_hover_color', [ '%%order_class%% #wfacp-e-form .wfacp-form .wfacp_main_form.woocommerce .wfacp_steps_sec ul li a:hover' ], 'Color', '#000000' );
		$this->add_controls_tab( $controls_tabs_id, 'Hover', [ $breadcrumb_text_hover_color ] );
		/* Back link color setting End*/
		/*Progress Bar*/
		$activeColor = [
			'%%order_class%% #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li.wfacp_bred_active:before',
			'%%order_class%% #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li.wfacp_active_prev:before',
			'%%order_class%% #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li.df_cart_link.wfacp_bred_visited:before'
		];
		$this->add_background_color( $tab_id, 'progress_bar_line_color', [ '%%order_class%% #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul:before' ], '', 'Line', $progress_bar_condition );
		$this->add_border_color( $tab_id, 'progress_bar_circle_color', [ '%%order_class%% #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li:before' ], '', __( 'Circle Border', 'woofunnels-aero-checkout' ), false, $progress_bar_condition );
		$this->add_background_color( $tab_id, 'progress_bar_active_color', $activeColor, '', 'Active Step', $progress_bar_condition );
		$this->add_color( $tab_id, 'progressbar_text_color', [ '%%order_class%%  #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li a' ], '', 'Text ', $progress_bar_condition );
		$this->add_color( $tab_id, 'progressbar_text_hover_color', [ '%%order_class%%  #wfacp-e-form .wfacp_custom_breadcrumb .wfacp_steps_sec ul li a:hover' ], '', 'Text Hover', $progress_bar_condition );
		/** Tab settings start completed */
		$wfacp_progress_bar_tabs = $this->add_controls_tabs( $tab_id, "Colors", $tab_condition );
		$field_keys              = [];
		$field_keys[]            = $this->add_background_color( $tab_id, 'active_step_bg_color', [ '%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list.wfacp-active' ], '', 'Background Color', $tab_condition );
		$field_keys[]            = $this->add_color( $tab_id, 'active_step_text_color', [ '%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list.wfacp-active .wfacp_tcolor' ], 'Text Color', '', $tab_condition );
		$field_keys[]            = $this->add_border_color( $tab_id, 'active_tab_border_bottom_color', [ '%%order_class%% #wfacp-e-form .wfacp-payment-tab-list.wfacp-active' ], '#000000', __( 'Tab Border Color', 'woofunnels-aero-checkout' ), false, $tab_condition );
		if ( $number_of_steps > 1 ) {
			$field_keys[] = $this->add_background_color( $tab_id, 'active_step_count_bg_color', [ '%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list.wfacp-active .wfacp-order2StepNumber' ], '#000000', 'Count Background Color', $tab_condition );
			$field_keys[] = $this->add_border_color( $tab_id, 'active_step_count_border_color', [ '%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list.wfacp-active .wfacp-order2StepNumber' ], '#000000', __( 'Count Border Color', 'woofunnels-aero-checkout' ), false, $tab_condition );
			$field_keys[] = $this->add_color( $tab_id, 'active_step_count_text_color', [ '%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list.wfacp-active .wfacp-order2StepNumber' ], 'Count Text Color', '', $tab_condition );
		}
		//Put All active step Field to control Tab
		$this->add_controls_tab( $wfacp_progress_bar_tabs, __( 'Active Step', 'woofunnels-aero-checkout' ), $field_keys );
		$inactiveBgcolor = [
			'%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list',
		];
		$field_keys      = [];
		$field_keys[]    = $this->add_background_color( $tab_id, 'inactive_step_bg_color', $inactiveBgcolor, '', __( 'Background Color', 'woofunnels-aero-checkout' ), $tab_condition );
		$field_keys[]    = $this->add_color( $tab_id, 'inactive_step_text_color', [ '%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list .wfacp_tcolor' ], __( 'Text Color', 'woofunnels-aero-checkout' ), '', $tab_condition );
		$field_keys[]    = $this->add_border_color( $tab_id, 'inactive_tab_border_bottom_color', [ '%%order_class%% #wfacp-e-form .wfacp-payment-tab-list' ], '#000000', __( 'Tab Border Color', 'woofunnels-aero-checkout' ), false, $tab_condition );
		$field_keys[]    = $this->add_background_color( $tab_id, 'inactive_step_count_bg_color', [ '%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list .wfacp-order2StepNumber' ], '#000000', 'Count Background Color', $tab_condition );
		$field_keys[]    = $this->add_border_color( $tab_id, 'inactive_step_count_border_color', [ '%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list .wfacp-order2StepNumber' ], '#000000', __( 'Count Border Color', 'woofunnels-aero-checkout' ), false, $tab_condition );
		$field_keys[]    = $this->add_color( $tab_id, 'inactive_step_count_text_color', [ '%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list .wfacp-order2StepNumber' ], 'Count Text Color', '', $tab_condition );
		//Put In Active step Field to control Tab
		$this->add_controls_tab( $wfacp_progress_bar_tabs, __( 'Inactive Step', 'woofunnels-aero-checkout' ), $field_keys );
		/** Tab settings completed */
		$this->add_heading( $tab_id, __( 'Border Radius', 'woofunnels-aero-checkout' ), '', $tab_condition );
		$this->add_border_radius_new( $tab_id, 'border_radius_steps', '%%order_class%% #wfacp-e-form .wfacp_form_steps .wfacp-payment-tab-list', $tab_condition );
		$this->add_heading( $tab_id, 'Margin', '', $tab_condition );
		$default = '0px || 15px || 0px || 0px';
		$this->add_margin( $tab_id, 'wfacp_tab_margin', '%%order_class%% #wfacp-e-form .tab', $default, '', $tab_condition );
	}

	private function get_heading_settings() {
		/**
		 * @var $template WFACP_Elementor_Template
		 */
		$sectionTitleOption = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_section_heading.wfacp_section_title'
		];
		$extra_options      = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #payment button#place_order'                       => 'font-weight: 700;font-size: 25px;',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout .wfacp-next-btn-wrap button' => 'font-weight: 700;font-size: 25px;',
		];
		$alignment          = 'Left';
		if ( is_rtl() ) {
			$alignment = 'Right';
		}
		$tab_id            = $this->add_tab( __( 'Heading', 'woofunnels-aero-checkout' ), 2 );
		$font_side_default = [ 'default' => '18px', 'unit' => 'px' ];
		$this->add_heading( $tab_id, __( 'Heading', 'woofunnels-aero-checkout' ) );
		$this->add_typography( $tab_id, 'section_heading_typo', implode( ',', $sectionTitleOption ), '', '', [], $font_side_default );
		$this->add_color( $tab_id, 'form_heading_color', $sectionTitleOption, '', '#333333' );
		$this->add_text_alignments( $tab_id, 'form_heading_align', $sectionTitleOption, '', $alignment, [] );
		//Sub heading start here
		$this->add_heading( $tab_id, __( __( 'Sub Heading', 'woofunnels-aero-checkout' ), 'woofunnels-aero-checkout' ), 2 );
		$subheadingOption  = [ '%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-comm-title h4' ];
		$font_side_default = [ 'default' => '14px', 'unit' => 'px' ];
		$this->add_typography( $tab_id, 'section_sub_heading_typo', implode( ',', $subheadingOption ), '', '', [], $font_side_default );
		$this->add_color( $tab_id, 'form_sub_heading_color', $subheadingOption, '', '' );
		$this->add_text_alignments( $tab_id, 'form_sub_heading_align', $subheadingOption );
		//Sub heading end here
		$advanceOption = [ '%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-section .wfacp-comm-title' ];
		$this->add_heading( $tab_id, __( 'Advanced', 'woofunnels-aero-checkout' ) );
		$this->add_background_color( $tab_id, 'form_heading_bg_color', $advanceOption, 'transparent' );
		$this->add_padding( $tab_id, 'form_heading_padding', implode( ',', $advanceOption ) );
		$this->add_margin( $tab_id, 'form_heading_margin', implode( ',', $advanceOption ), '', '', [] );
		$default_args = [
			'border_type'          => 'none',
			'border_width_top'     => '1',
			'border_width_bottom'  => '1',
			'border_width_left'    => '1',
			'border_width_right'   => '1',
			'border_radius_top'    => '0',
			'border_radius_bottom' => '0',
			'border_radius_left'   => '0',
			'border_radius_right'  => '0',
			'border_color'         => '#dddddd',
		];
		$this->add_border( $tab_id, 'form_heading_border', implode( ',', $advanceOption ), [], $default_args );
	}

	private function fields_typo_settings() {
		$tabs_id = $this->add_tab( __( 'Fields', 'woofunnels-aero-checkout' ), 2 );
		$this->add_heading( $tabs_id, __( 'Label', 'woofunnels-aero-checkout' ) );


		/* Label Position */

		$options = [
			'wfacp-modern-label' => __( 'Floating', 'woofunnels-aero-checkout' ),
			'wfacp-top'          => __( 'Outside', 'woofunnels-aero-checkout' ),
			'wfacp-inside'       => __( 'Inside', 'woofunnels-aero-checkout' ),

		];
		$this->add_select( $tabs_id, 'wfacp_label_position', __( 'Label Position', 'woofunnels-aero-checkout' ), $options, 'wfacp-inside' );


		$form_fields_label_typo = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce p.wfacp-form-control-wrapper:not(.wfacp-anim-wrap) label.wfacp-form-control-label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce p.wfacp-form-control-wrapper.wfacp_checkbox_field label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .create-account label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .create-account label span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce p.wfacp-form-control-wrapper.wfacp_checkbox_field label span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce p.wfacp-form-control-wrapper.wfacp_custom_field_radio_wrap > label ',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce p.wfacp-form-control-wrapper:not(.wfacp-anim-wrap) label.wfacp-form-control-label abbr',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul li p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul li',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul li p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul li label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul li .wfacp_shipping_price span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul li .wfacp_shipping_price span bdi',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_shipping_options ul li .wfacp_shipping_price',
			'%%order_class%% #wfacp-e-form .wfacp-form.wfacp-top .form-row > label.wfacp-form-control-label',
		];
		$font_side_default      = [ 'default' => '13px', 'unit' => 'px' ];
		$this->add_typography( $tabs_id, 'wfacp_form_fields_label_typo', implode( ',', $form_fields_label_typo ), '', '', [], $font_side_default );
		$form_fields_label_color_opt = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-form-control-label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-form-control-label abbr',
			'%%order_class%% #wfacp-e-form .wfacp_allowed_countries strong',
		];
		$this->add_color( $tabs_id, 'wfacp_form_fields_label_color', $form_fields_label_color_opt, '', '#777' );
		$fields_options = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type="text"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type="email"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type="tel"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type="password"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type="number"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce select',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce textarea',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce number',
			'%%order_class%% #wfacp-e-form .woocommerce-input-wrapper .wfacp-form-control',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .select2-container .select2-selection--single .select2-selection__rendered',
			'body:not(.wfacp_pre_built) .select2-results__option',
			'body:not(.wfacp_pre_built) .select2-container--default .select2-search--dropdown .select2-search__field',
		];
		$optionString   = implode( ',', $fields_options );
		/* Input field typography */
		$this->add_heading( $tabs_id, __( 'Input', 'woofunnels-aero-checkout' ) );
		$font_side_default = [ 'default' => '14px', 'unit' => 'px' ];
		$this->add_typography( $tabs_id, 'wfacp_form_fields_input_typo', $optionString, '', '', [], $font_side_default );
		$inputColorOption = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-input-wrapper .wfacp-form-control',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .select2-container .select2-selection--single .select2-selection__rendered',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce select',
		];
		$this->add_color( $tabs_id, 'wfacp_form_fields_input_color', $inputColorOption, '', '#404040' );
		$fields_options = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type="text"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type="email"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type="tel"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type="password"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce select',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce textarea',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .woocommerce-input-wrapper input[type="number"].wfacp-form-control',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .woocommerce-input-wrapper input[type="text"].wfacp-form-control',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .woocommerce-input-wrapper input[type="emal"].wfacp-form-control',
			'%%order_class%% #wfacp-e-form .wfacp_allowed_countries strong',
			'%%order_class%% #wfacp-e-form .iti__selected-flag',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .select2-container .select2-selection--single .select2-selection__rendered',
		];

		$inputbgColorOption = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-input-wrapper .wfacp-form-control:not(.input-checkbox)',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .select2-container .select2-selection--single .select2-selection__rendered',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce select',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-form-control:not(.input-checkbox):not(.hidden)',
			'%%order_class%% #wfacp-e-form .wfacp_allowed_countries strong',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-login-wrapper input[type=email]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-login-wrapper input[type=number]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-login-wrapper input[type=password]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-login-wrapper input[type=tel]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-login-wrapper select',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-login-wrapper input[type=text]',
			'%%order_class%% #wfacp-e-form .wfacp-form.wfacp-inside .form-row .wfacp-form-control-label:not(.checkbox)',
		];

		$this->add_background_color( $tabs_id, 'wfacp_form_fields_input_bg_color', $inputbgColorOption, '' );

		$default_args = [
			'border_type'          => 'solid',
			'border_width_top'     => '1',
			'border_width_bottom'  => '1',
			'border_width_left'    => '1',
			'border_width_right'   => '1',
			'border_radius_top'    => '4',
			'border_radius_bottom' => '4',
			'border_radius_left'   => '4',
			'border_radius_right'  => '4',
			'border_color'         => '#bfbfbf',
		];
		$this->add_border( $tabs_id, 'wfacp_form_fields_border', implode( ',', $fields_options ), [], $default_args );
		$validation_error = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce p.woocommerce-invalid-required-field .wfacp-form-control',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce p.woocommerce-invalid-email .wfacp-form-control',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_coupon_failed .wfacp_coupon_code',
		];
		$this->add_border_color( $tabs_id, 'wfacp_form_fields_focus_color', [ '%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .form-row:not(.woocommerce-invalid-email) .wfacp-form-control:not(.wfacp_coupon_code):focus' ], '', __( 'Focus Color', 'woofunnel-aero-checkout' ), true );

		$this->add_border_color( $tabs_id, 'wfacp_form_fields_validation_color', $validation_error, '#d50000', __( 'Error Validation Color', 'woofunnel-aero-checkout' ), true );
	}

	private function section_typo_settings() {
		$tab_id                = $this->add_tab( __( 'Section', 'woofunnels-aero-checkout' ), 2 );
		$form_section_bg_color = [
			'%%order_class%% #wfacp-e-form .wfacp-section',
		];

		$this->add_background_color( $tab_id, 'form_section_bg_color', $form_section_bg_color, '', __( 'Background Color', 'woofunnels-aero-checkout' ) );
		$this->add_box_shadow( $tab_id, 'form_section_box_shadow', implode( ',', $form_section_bg_color ) );

		$this->add_padding( $tab_id, 'form_section_padding', '%%order_class%% #wfacp-e-form .wfacp-section', '', 'Padding' );

		$default = '0px || 15px || 0px || 0px';
		$this->add_margin( $tab_id, 'form_section_margin', '%%order_class%% #wfacp-e-form .wfacp-section', $default, 'Margin' );
		$default_args = [
			'border_type'          => 'none',
			'border_width_top'     => '1',
			'border_width_bottom'  => '1',
			'border_width_left'    => '1',
			'border_width_right'   => '1',
			'border_radius_top'    => '0',
			'border_radius_bottom' => '0',
			'border_radius_left'   => '0',
			'border_radius_right'  => '0',
			'border_color'         => '#dddddd',
		];
		$this->add_border( $tab_id, 'form_section_border', implode( ',', $form_section_bg_color ), [], $default_args );

	}



	private function payment_buttons_styling() {
		$tab_id    = $this->add_tab( __( 'Checkout Button(s)', 'woofunnel-aero-checkout' ), 2 );
		$selector  = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-next-btn-wrap button',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #payment button#place_order',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce  button#place_order',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce  #ppcp-hosted-fields .button',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .button.button#place_order',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .button.wfacp_next_page_button',
		];
		$selector1 = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-next-btn-wrap button',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #payment button#place_order',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce  button#place_order',
			'#wfacp_qr_model_wrap .wfacp_qr_wrap .wfacp_qv-summary .button',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce  #ppcp-hosted-fields .button',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .button.button#place_order',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .button.wfacp_next_page_button',
		];
		$this->add_switcher( $tab_id, 'wfacp_make_button_sticky_on_mobile', __( 'Sticky on Mobile', 'woofunnels-aero-checkout' ), 'off', [] );
		$default = [ 'default' => '100%', 'unit' => '%' ];
		$this->add_width( $tab_id, 'wfacp_button_width', implode( ',', $selector ), 'Button Width (in %)', $default );
		$alignment = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout .wfacp-order-place-btn-wrap',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout .wfacp-next-btn-wrap',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce  #ppcp-hosted-fields',
		];
		$this->add_text_alignments( $tab_id, 'wfacp_form_button_alignment', $alignment );
		$button_selector   = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #payment button#place_order',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce button#place_order',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout button.button.button-primary.wfacp_next_page_button',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce  #ppcp-hosted-fields .button',
		];
		$font_side_default = [ 'default' => '25px', 'unit' => 'px' ];
		$this->add_typography( $tab_id, 'wfacp_form_payment_button_typo', implode( ',', $button_selector ), '', '', [], $font_side_default );

		/* Button Icon Style*/
		$this->button_icon_style( $tab_id );

		$button_bg_hover_color = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-next-btn-wrap button:hover',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #payment button#place_order:hover',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce  button#place_order:hover',
			'#wfacp_qr_model_wrap .wfacp_qr_wrap .wfacp_qv-summary .button:hover',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce  #ppcp-hosted-fields .button:hover',
		];
		/* Button Background hover tab */
		$control_tab_id = $this->add_controls_tabs( $tab_id, "Color" );
		$field_keys     = [];
		$field_keys[]   = $this->add_background_color( $tab_id, 'wfacp_button_bg_color', $selector1, "", 'Background' );
		$field_keys[]   = $this->add_color( $tab_id, 'wfacp_button_label_color', $selector1, '', 'Label' );
		$this->add_controls_tab( $control_tab_id, __( 'Normal', 'woofunnels-aero-checkout' ), $field_keys );
		$field_keys   = [];
		$field_keys[] = $this->add_background_color( $tab_id, 'wfacp_button_bg_hover_color', $button_bg_hover_color, "", 'Background' );
		$field_keys[] = $this->add_color( $tab_id, 'wfacp_button_label_hover_color', $button_bg_hover_color, '', 'Label' );
		$this->add_controls_tab( $control_tab_id, __( 'Hover', 'woofunnels-aero-checkout' ), $field_keys );
		$this->add_divider( "none" );
		$default = '15px || 15px || 25px || 25px';
		$this->add_padding( $tab_id, 'wfacp_button_padding', implode( ',', $selector ), $default, 'Padding' );
		$this->add_margin( $tab_id, "wfacp_button_margin", implode( ',', $selector ) );
		$this->add_divider( "none" );
		$this->add_border( $tab_id, "wfacp_button_border", implode( ',', $selector ) );
		$this->add_divider( "none" );
		$stepBackLink      = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form .wfacp-back-btn-wrap a',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form .place_order_back_btn a'
		];
		$stepBackLinkHover = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form .wfacp-back-btn-wrap a:hover',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form .place_order_back_btn a:hover'
		];

		$this->add_heading( $tab_id, __( 'Return Link', 'woofunnels-aero-checkout' ), 'none' );
		/* Back Link color setting */
		$back_control_tab_id = $this->add_controls_tabs( $tab_id, '' );
		$field_keys          = [];
		$field_keys[]        = $this->add_color( $tab_id, 'step_back_link_color', $stepBackLink );
		$this->add_controls_tab( $back_control_tab_id, 'Normal', $field_keys );
		$field_keys   = [];
		$field_keys[] = $this->add_color( $tab_id, 'step_back_link_hover_color', $stepBackLinkHover );
		$field_keys[] = $this->add_controls_tab( $back_control_tab_id, 'Hover', $field_keys );

		/* Back link color setting End*/
		$this->add_heading( $tab_id, __( 'Additional Text', 'woofunnels-aero-checkout' ) );
		$this->add_color( $tab_id, 'additional_text_color', [ '%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-payment-dec' ], '', '' );
		$this->add_background_color( $tab_id, 'additional_bg_color', [ '%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-payment-dec' ], "", 'Background' );
	}

	private function button_icon_style( $tab_id ) {


		$template      = wfacp_template();
		$template_slug = $template->get_template_slug();
		$this->add_heading( $tab_id, __( 'Button Icon', 'elementor' ) );

		$btn_icon_selector = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-order-place-btn-wrap button:before',
			'%%order_class%% #wfacp-e-form .wfacp-next-btn-wrap button:before'
		];


		$this->add_color( $tab_id, $template_slug . '_btn_icon_color', $btn_icon_selector, 'Icon Color', '#ffffff' );
		$this->add_heading( $tab_id, __( 'Sub Text', 'elementor' ) );
		$button_sub_text_selector = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-order-place-btn-wrap button:after',
			'%%order_class%% #wfacp-e-form .wfacp-next-btn-wrap button:after'
		];


		$default = [
			'range_settings' => [
				'min'  => '1',
				'max'  => '100',
				'step' => '1',
			],
			'default'        => [ "12px", 'px' ],
			'unit'           => 'px',
			'allowed_units'  => [ 'px' ],

		];

		$this->add_font_size( $tab_id, $template_slug . '_button_sub_text_font_size', implode( ',', $button_sub_text_selector ), 'Font Size (in px)', $default );
		$this->add_color( $tab_id, $template_slug . '_button_sub_text_color', $button_sub_text_selector, 'Text Color', '#ffffff' );

	}

	private function payment_method_styling() {
		$tab_id = $this->add_tab( __( 'Payment Methods', 'woofunnel-aero-checkout' ), 2 );


		$payment_method_typo = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods p a',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods strong',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods input',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #add_payment_method #payment .payment_box p',

		];
		$font_side_default   = [ 'default' => '14px', 'unit' => 'px' ];

		$this->add_typography( $tab_id, 'wfacp_form_payment_method_typo', implode( ',', $payment_method_typo ), '', '', [], $font_side_default );

		/* Color Setting  */
		$this->add_heading( $tab_id, __( 'Colors', 'woofunnel-aero-checkout' ) );

		$payment_method_label_color = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout #payment ul.payment_methods li label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout #payment ul.payment_methods li label span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout #payment ul.payment_methods li label a',
		];

		$this->add_color( $tab_id, 'wfacp_form_payment_method_label_color', implode( ',', $payment_method_label_color ), __( 'Text Color', 'woofunnel-aero-checkout' ), '' );

		$payment_method_description_color = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods li .payment_box p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods li .payment_box p span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods li .payment_box  p strong',

		];
		$this->add_color( $tab_id, 'wfacp_form_payment_method_description_color', implode( ',', $payment_method_description_color ), __( 'Description Color', 'woofunnel-aero-checkout' ), '' );

		$payment_method_description_bg_color = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .wfacp_payment #payment .payment_methods li .payment_box',
		];
		$this->add_background_color( $tab_id, 'wfacp_form_payment_method_description_bg_color', implode( ',', $payment_method_description_bg_color ), '#ffffff', __( 'Information Background Color', 'woofunnel-aero-checkout' ) );

	}


	private function global_typography() {

		$tab_id = $this->add_tab( __( 'Checkout Form', 'woofunnel-aero-checkout' ), 2 );


		/* Typography */


		$globalSettingOptions = [
			'body.wfacp_main_wrapper',
			'body #wfacp-e-form *',
			'body #wfacp-e-form *:not(i)',
			'body .wfacp_qv-main *',
			'body:not(.wfacp_pre_built) .select2-results__option',
			'body:not(.wfacp_pre_built) .select2-container--default .select2-search--dropdown .select2-search__field',
			'%%order_class%% #wfacp-e-form .wfacp_main_form input[type="text"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form input[type="email"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form input[type="tel"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form input[type="number"]',
			'%%order_class%% #wfacp-e-form .wfacp_main_form select',
			'%%order_class%% #wfacp-e-form .wfacp_main_form textarea',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .select2-container .select2-selection--single .select2-selection__rendered',
			'%%order_class%% #et-boc .et-l span.select2-selection.select2-selection--multiple',
			'%%order_class%% #wfacp-e-form .wfacp_main_form p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form a',
			'%%order_class%% #wfacp-e-form .wfacp_main_form span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form label span a',
			'%%order_class%% #wfacp-e-form .wfacp_main_form ul li',
			'%%order_class%% #wfacp-e-form .wfacp_main_form ul li span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form ul li p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_section_title',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-section h4',
			'%%order_class%% #wfacp-e-form .wfacp_main_form p label.wfacp-form-control-label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form label.checkbox',
			'%%order_class%% #wfacp-e-form .wfacp_main_form button',
			'%%order_class%% #wfacp-e-form #payment #place_order',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-payment-dec',
			'%%order_class%% #wfacp-e-form .wfacp_collapsible_order_summary_wrap *',
			'%%order_class%% #wfacp-e-form .woocommerce-form-login-toggle .woocommerce-info ',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-coupon-section .wfacp-coupon-page .woocommerce-info > span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_woocommerce_form_coupon .wfacp-coupon-section .woocommerce-info .wfacp_showcoupon',
			'%%order_class%% #wfacp-e-form .wfacp-coupon-section .woocommerce-info > a',
			'%%order_class%% #wfacp-e-form .wfacp-coupon-section .woocommerce-info > a:not(.wfacp_close_icon):not(.button-social-login):not(.wfob_btn_add):not(.ywcmas_shipping_address_button_new):not(.wfob_qv-button):not(.wfob_read_more_link):not(.wfacp_step_text_have ):not(.wfacp_cart_link)',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-title > div',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_shipping_table ul li label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_sec *',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_quantity_selector input',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_price_sec span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_product_sec *',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_quantity_selector input',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_product_price_sec span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_best_value',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel .wfacp_product_switcher_col_2 .wfacp_you_save_text',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel .wfacp_whats_included .wfacp_product_switcher_description h4',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_product_sec .wfacp_product_select_options .wfacp_qv-button',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_best_value',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_product_sec .wfacp_product_select_options .wfacp_qv-button',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel .wfacp_product_switcher_col_2 .wfacp_product_subs_details > span:not(.subscription-details):not(.woocommerce-Price-amount):not(.woocommerce-Price-currencySymbol)',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_you_save_text',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel .wfacp_product_switcher_col_2 .wfacp_product_subs_details > span:not(.subscription-details):not(.woocommerce-Price-amount):not(.woocommerce-Price-currencySymbol)',
			'%%order_class%% #wfacp-e-form .wfacp_main_form label.woocommerce-form__label span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr th',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr td',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr td span.amount',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr td span bdi',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr td p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tbody .wfacp_order_summary_item_name',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) td small',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) th small',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr.order-total td small',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody .wfacp_order_summary_item_name',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody .product-name .product-quantity',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody td.product-total',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody .wfacp_order_summary_container dl',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody .wfacp_order_summary_container dd',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody .wfacp_order_summary_container dt',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody .wfacp_order_summary_container p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody tr span.amount',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody tr span.amount bdi',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody .wfacp_order_summary_item_name',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody .cart_item .product-total span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody .cart_item .product-total small',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody .cart_item .product-total span.amount',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody .cart_item .product-total span.amount bdi',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody .product-name .product-quantity',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody td.product-total',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tbody dl',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tbody dd',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tbody dt',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tbody p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody tr span.amount',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table.shop_table tbody tr span.amount bdi',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_row_wrap .wfacp_you_save_text span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_whats_included .wfacp_product_switcher_description .wfacp_description p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_coupon_field_msg',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-coupon-page .wfacp_coupon_remove_msg',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-coupon-page .wfacp_coupon_error_msg',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_order_total_field table.wfacp_order_total_wrap tr td',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_order_total_field table.wfacp_order_total_wrap tr td span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_order_total .wfacp_order_total_wrap',
			'%%order_class%% #wfacp-e-form .wfacp_main_form #payment button#place_order',
			'%%order_class%% #wfacp-e-form .wfacp_main_form  button#place_order',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .woocommerce-checkout button.button.button-primary.wfacp_next_page_button',
			'%%order_class%% #wfacp-e-form .wfacp-order2StepTitle.wfacp-order2StepTitleS1',
			'%%order_class%% #wfacp-e-form .wfacp-order2StepSubTitle.wfacp-order2StepSubTitleS1',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_steps_sec ul li a',
			'%%order_class%% #wfacp-e-form .wfacp_custom_breadcrumb ul li a',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr td span ',
			'%%order_class%% #wfacp-e-form .wfacp_main_form p.wfacp-form-control-wrapper:not(.wfacp-anim-wrap) label.wfacp-form-control-label abbr',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset .wfacp_you_save_text',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_row_wrap .wfacp_you_save_text span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_row_wrap .wfacp_product_subs_details span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form p.wfacp-form-control-wrapper.wfacp_checkbox_field label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .create-account label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .create-account label span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form p.wfacp-form-control-wrapper.wfacp_checkbox_field label span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form p.wfacp-form-control-wrapper.wfacp_custom_field_radio_wrap > label ',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) ul',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) ul li',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) ul li label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) td span.woocommerce-Price-amount.amount',
			'%%order_class%% #wfacp-e-form .wfacp_main_form table tfoot tr:not(.order-total) td span.woocommerce-Price-amount.amount bdi',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_sec .wfacp_product_name_inner *',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_sec .wfacp_product_attributes .wfacp_selected_attributes  *',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_quantity_selector input',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_price_sec span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_switcher_col_2 .wfacp_product_subs_details > span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_subs_details span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_subs_details *',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset.wfacp-selected-product .wfacp_product_sec .wfacp_product_select_options .wfacp_qv-button',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_sec .wfacp_product_name_inner *',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_sec .wfacp_product_attributes .wfacp_selected_attributes  *',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_quantity_selector input',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_price_sec span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_switcher_col_2 .wfacp_product_subs_details > span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_subs_details span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_subs_details *',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp-product-switch-panel fieldset:not(.wfacp-selected-product) .wfacp_product_sec .wfacp_product_select_options .wfacp_qv-button',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_woocommerce_form_coupon .wfacp-coupon-section .wfacp-coupon-field-btn',
			'%%order_class%% #wfacp-e-form .wfacp_mb_mini_cart_sec_accordion_content form.checkout_coupon button.button.wfacp-coupon-btn',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_shipping_options',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_shipping_options ul li',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_shipping_options ul li p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_shipping_options ul li .wfacp_shipping_price span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_shipping_options ul li .wfacp_shipping_price',
			'%%order_class%% #wfacp-e-form .shop_table tbody .wfacp_order_summary_item_name',
			'%%order_class%% #wfacp-e-form .shop_table tbody .product-name .product-quantity',
			'%%order_class%% #wfacp-e-form .shop_table tbody .product-total',
			'%%order_class%% #wfacp-e-form .shop_table tbody .cart_item .product-total span',
			'%%order_class%% #wfacp-e-form .shop_table tbody .cart_item .product-total span.amount',
			'%%order_class%% #wfacp-e-form .shop_table tbody .cart_item .product-total span.amount bdi',
			'%%order_class%% #wfacp-e-form .shop_table tbody .cart_item .product-total small',
			'%%order_class%% #wfacp-e-form .shop_table tbody .wfacp_order_summary_container dl',
			'%%order_class%% #wfacp-e-form .shop_table tbody .wfacp_order_summary_container dd',
			'%%order_class%% #wfacp-e-form .shop_table tbody .wfacp_order_summary_container dt',
			'%%order_class%% #wfacp-e-form .shop_table tbody .wfacp_order_summary_container p',
			'%%order_class%% #wfacp-e-form .shop_table tbody tr span.amount',
			'%%order_class%% #wfacp-e-form .shop_table tbody tr span.amount bdi',
			'%%order_class%% #wfacp-e-form .shop_table tbody tr td span:not(.wfacp-pro-count)',
			'%%order_class%% #wfacp-e-form .shop_table tbody dl',
			'%%order_class%% #wfacp-e-form .shop_table tbody dd',
			'%%order_class%% #wfacp-e-form .shop_table tbody dt',
			'%%order_class%% #wfacp-e-form .shop_table tbody p',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr:not(.order-total):not(.cart-discount)',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr:not(.order-total):not(.cart-discount) td',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr:not(.order-total):not(.cart-discount) th',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr:not(.order-total):not(.cart-discount) th span',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr:not(.order-total):not(.cart-discount) td span',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr:not(.order-total):not(.cart-discount) td small',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr:not(.order-total):not(.cart-discount) td bdi',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr:not(.order-total):not(.cart-discount) td a',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.cart-discount th .wfacp_coupon_code',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.cart-discount th',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.cart-discount td',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.cart-discount td span',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.cart-discount td a',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.cart-discount td span',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.cart-discount td span bdi',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total th',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total th span',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total th small',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total th a',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total td',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total td span.amount',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total td span.amount bdi',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total td p',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total td span',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total td small',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total td a',
			'%%order_class%% #wfacp-e-form .shop_table tfoot tr.order-total td p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment p span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment p a',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment ul',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment ul li',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment ul li input',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment #add_payment_method #payment div.payment_box',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment #add_payment_method #payment .payment_box p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment #payment .payment_methods p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment #payment .payment_methods label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment #payment .payment_methods span',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment #payment .payment_methods p a',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment #payment .payment_methods strong',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment #payment .payment_methods input',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment #payment .payment_box p',
		];


		$font_side_default = [ 'default' => '14px', 'unit' => 'px' ];
		$this->add_typography( $tab_id, 'wfacp_font_family_typography', implode( ',', $globalSettingOptions ), '', '', [] );
		$this->add_background_color( $tab_id, 'form_background_color', '%%order_class%% .wfacp_form_divi_container', '#ffffff', __( 'Form Background Color', 'woofunnels-aero-checkout' ) );

		/* Colors */


		$primary_color = [
			'%%order_class%% #wfacp-e-form  #payment li.wc_payment_method input.input-radio:checked::before',
			'%%order_class%% #wfacp-e-form  #payment.wc_payment_method input[type=radio]:checked:before',
			'%%order_class%% #wfacp-e-form  button[type=submit]:not(.white):not(.black)',
			'%%order_class%% #wfacp-e-form  button[type=button]:not(.white):not(.black)',
			'%%order_class%% #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page .wfacp-coupon-field-btn',
			'%%order_class%% #wfacp-e-form input[type=checkbox]:checked',
			'%%order_class%% #wfacp-e-form #payment input[type=checkbox]:checked',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-input-wrapper .wfacp-form-control:checked',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type=checkbox]:checked',
			'%%order_class%% #wfacp-e-form .wfacp_main_form  #ppcp-hosted-fields .button',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .button.button#place_order',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .button.wfacp_next_page_button',
		];



		$this->add_background_color( $tab_id, 'default_primary_color', $primary_color, '', __( 'Primary Color', 'woofunnels-aero-checkout' ) );


		$fields_contentColor = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form .woocommerce-form-login.login p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .woocommerce-privacy-policy-text p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .woocommerce-info .message-container',
			'%%order_class%% #wfacp-e-form .wfacp_main_form #wc_checkout_add_ons .description',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .woocommerce-checkout-review-order h3',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .aw_addon_wrap label',
			'%%order_class%% #wfacp-e-form .wfacp_main_form p:not(.woocommerce-shipping-contents):not(.wfacp_dummy_preview_heading )',
			'%%order_class%% #wfacp-e-form .wfacp_main_form p label:not(.wfacp-form-control-label):not(.wfob_title):not(.wfob_span):not(.checkbox)',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .woocommerce-error',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_payment h4',
			'%%order_class%% #wfacp-e-form #payment .woocommerce-privacy-policy-text p',
			'%%order_class%% #wfacp-e-form .wfacp_main_form .wfacp_whats_included .wfacp_product_switcher_description .wfacp_description p',
			'%%order_class%% #wfacp-e-form .wfacp-form label.woocommerce-form__label .woocommerce-terms-and-conditions-checkbox-text',
			'%%order_class%% #wfacp-e-form fieldset',
			'%%order_class%% #wfacp-e-form fieldset legend',
			'%%order_class%% #wfacp-e-form .wfacp_main_form #payment .woocommerce-terms-and-conditions-wrapper .form-row'
		];

		$this->add_color( $tab_id, 'default_text_color', $fields_contentColor, __( "Content Color", 'woofunnels-aero-checkout' ), '' );


		$default_link_color_option = [
			'%%order_class%% #wfacp-e-form .woocommerce-form-login-toggle .woocommerce-info a',
			'%%order_class%% #wfacp-e-form a:not(.wfacp_close_icon):not(.button-social-login):not(.wfob_btn_add):not(.ywcmas_shipping_address_button_new):not(.wfob_qv-button):not(.wfob_read_more_link):not(.wfacp_step_text_have ):not(.wfacp_cart_link):not(.wfacp_summary_link)',
			'%%order_class%% #wfacp-e-form a:not(.wfacp_summary_link) span:not(.wfob_btn_text_added):not(.wfob_btn_text_remove)',
			'%%order_class%% #wfacp-e-form label a',
			'%%order_class%% #wfacp-e-form ul li a:not(.wfacp_breadcrumb_link)',
			'%%order_class%% #wfacp-e-form table tr td a',
			'%%order_class%% #wfacp-e-form .wfacp_steps_sec ul li a',
			'%%order_class%% #wfacp-e-form a.wfacp_remove_coupon',
			'%%order_class%% #wfacp-e-form a:not(.button-social-login):not(.wfob_read_more_link)',
			'%%order_class%% #wfacp-e-form .wfacp-login-wrapper input#rememberme + span',
			'%%order_class%% #wfacp-e-form #product_switching_field .wfacp_product_switcher_col_2 .wfacp_product_switcher_description a.wfacp_qv-button'
		];


		$default_link_hover_color_option = [
			'%%order_class%% #wfacp-e-form .woocommerce-form-login-toggle .woocommerce-info a:hover',
			'%%order_class%% #wfacp-e-form a:not(.wfacp_close_icon):not(.button-social-login):hover:not(.wfob_btn_add):hover:not(.ywcmas_shipping_address_button_new):hover:not(.wfacp_cart_link):hover:not(.wfacp_back_page_button):hover:not(.wfacp_summary_link)',
			'%%order_class%% #wfacp-e-form a:not(.wfacp_summary_link) span:not(.wfob_btn_text_added):not(.wfob_btn_text_remove):hover',
			'%%order_class%% #wfacp-e-form label a:hover',
			'%%order_class%% #wfacp-e-form ul li a:not(.wfacp_breadcrumb_link):hover',
			'%%order_class%% #wfacp-e-form table tr td a:hover',
			'%%order_class%% #wfacp-e-form a.wfacp_remove_coupon:hover',
			'%%order_class%% #wfacp-e-form a:not(.button-social-login):not(.wfob_read_more_link):hover',
			'%%order_class%% #wfacp-e-form .wfacp-login-wrapper input#rememberme + span:hover',
			'%%order_class%% #wfacp-e-form #product_switching_field .wfacp_product_switcher_col_2 .wfacp_product_switcher_description a.wfacp_qv-button:hover'
		];

		/* Button Background hover tab */

		$control_id = $this->add_controls_tabs( $tab_id, "Form Link Color" );
		$fields     = [];
		$fields[]   = $this->add_color( $tab_id, 'default_link_color', $default_link_color_option, __( 'Link Color', 'woofunnels-aero-checkout' ) );
		$this->add_controls_tab( $control_id, "Normal", $fields );
		$fields   = [];
		$fields[] = $this->add_color( $tab_id, 'default_link_hover_color', $default_link_hover_color_option, __( 'Link Hover Color', 'woofunnels-aero-checkout' ) );
		$this->add_controls_tab( $control_id, 'Hover', $fields );



		$this->end_tab();

		$spacing_tab_id = $this->add_tab( __( 'Spacing', 'woofunnel-aero-checkout' ), 2 );
		$this->add_margin( $tab_id, 'form_margin', '%%order_class%% #wfacp-e-form .wfacp-form' );
		$this->add_padding( $tab_id, 'form_padding', '%%order_class%% #wfacp-e-form .wfacp-form' );
		$border_tab_id = $this->add_tab( __( 'Border', 'woofunnel-aero-checkout' ), 2 );
		$default       = [ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px' ];
		$default_args  = [
			'border_type'          => 'none',
			'border_width_top'     => '1',
			'border_width_bottom'  => '1',
			'border_width_left'    => '1',
			'border_width_right'   => '1',
			'border_radius_top'    => '0',
			'border_radius_bottom' => '0',
			'border_radius_left'   => '0',
			'border_radius_right'  => '0',
			'border_color'         => '#dddddd',
		];
		$this->add_border( $tab_id, 'form_border', '%%order_class%% .wfacp_form_divi_container', [], $default, [], $default_args );


		$this->end_tab();


	}


	private function collapsible_order_summary() {
		$tab_id = $this->add_tab( __( 'Collapsible Order Summary', 'woofunnels-aero-checkout' ), 2 );

		$this->add_background_color( $tab_id, 'collapsible_order_summary_bg_color', '%%order_class%% #wfacp-e-form .wfacp_mb_mini_cart_wrap .wfacp_mb_cart_accordian', '#f7f7f7', __( 'Collapsed Background', 'woofunnels-aero-checkout' ) );
		$this->add_background_color( $tab_id, 'expanded_order_summary_bg_color', '%%order_class%% #wfacp-e-form .wfacp_mb_mini_cart_sec_accordion_content', '#f7f7f7', __( 'Expanded Background', 'woofunnels-aero-checkout' ) );
		$this->add_color( $tab_id, 'expanded_order_summary_link_color', [
			'%%order_class%% #wfacp-e-form .wfacp_show_icon_wrap a span',
			'%%order_class%% #wfacp-e-form .wfacp_show_price_wrap span'
		], __( 'Text Color', 'woofunnels-aero-checkout' ), '#323232' );
		$default = '0px || 10px || 0px || 0px';
		$this->add_margin( $tab_id, 'wfacp_collapsible_margin', '%%order_class%% #wfacp-e-form .wfacp_collapsible_order_summary_wrap', $default );

		$default_args = [
			'border_type'          => 'solid',
			'border_width_top'     => '1',
			'border_width_bottom'  => '1',
			'border_width_left'    => '1',
			'border_width_right'   => '1',
			'border_radius_top'    => '0',
			'border_radius_bottom' => '0',
			'border_radius_left'   => '0',
			'border_radius_right'  => '0',
			'border_color'         => '#dddddd',
		];


		$this->add_border( $tab_id, 'wfacp_collapsible_border', '%%order_class%% #wfacp-e-form .wfacp_mb_mini_cart_wrap .wfacp_mb_cart_accordian', [], $default_args );


		/* ----------------------- ------------------------ ------------------*/


		do_action( 'wfacp_elementor_collapsible_fields_settings', $this );
	}

	public function html( $attrs, $content = null, $render_slug = '' ) {
		$template = wfacp_template();
		if ( is_null( $template ) ) {
			return '';
		}


		if ( isset( $this->props['default_primary_color'] ) && ! empty( $this->props['default_primary_color'] ) ) {

			$focus_list                         = [
				'%%order_class%% #wfacp-e-form .form-row:not(.woocommerce-invalid-required-field) .woocommerce-input-wrapper .select2-container .select2-selection--single:focus',
				'%%order_class%% #wfacp-e-form .form-row:not(.woocommerce-invalid-required-field) .woocommerce-input-wrapper .select2-container .select2-selection--single .select2-selection__rendered:focus',
				'%%order_class%% #wfacp-e-form .form-row:not(.woocommerce-invalid-email) .wfacp-form-control:focus',

				'%%order_class%% #wfacp-e-form .wfacp_main_form .form-row:not(.woocommerce-invalid-required-field) .woocommerce-input-wrapper .select2-container .select2-selection--single .select2-selection__rendered:focus',
				'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .form-row:not(.woocommerce-invalid-required-field) .woocommerce-input-wrapper .select2-container .select2-selection--single .select2-selection__rendered:focus',
				'%%order_class%% #wfacp-e-form .wfacp_main_form .form-row:not(.woocommerce-invalid-required-field) .woocommerce-input-wrapper .select2-container .select2-selection--single:focus>span.select2-selection__rendered',
				'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .form-row:not(.woocommerce-invalid-required-field) .woocommerce-input-wrapper .select2-container .select2-selection--single:focus>span.select2-selection__rendered',
			];
			$primary_default_focus_border_color = array(
				'selector'    => implode( ',', $focus_list ),
				'declaration' => sprintf( 'border-color:%s !important;', esc_attr( $this->props['default_primary_color'] ) ),
			);
			$primary_default_focus_color        = array(
				'selector'    => implode( ',', $focus_list ),
				'declaration' => sprintf( 'box-shadow:0 0 0 1px %s !important;', esc_attr( $this->props['default_primary_color'] ) ),
			);


			ET_Builder_Element::set_style( $render_slug, $primary_default_focus_border_color );
			ET_Builder_Element::set_style( $render_slug, $primary_default_focus_color );


			/* Hide radio Button*/

			$hide_radio_array = [
				'%%order_class%% #wfacp-e-form #payment li.wc_payment_method input.input-radio:checked::before',
				'%%order_class%% #wfacp-e-form #payment.wc_payment_method input[type=radio]:checked:before',
				'%%order_class%% #wfacp-e-form input[type=radio]:checked:before',
				'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type=radio]:checked:before',
			];
			$hide_radio       = array(
				'selector'    => implode( ',', $hide_radio_array ),
				'declaration' => sprintf( 'display:%s;', 'none' ),
			);
			ET_Builder_Element::set_style( $render_slug, $hide_radio );

			/* Radio Border Width */

			$radio_border_width_array = [
				'%%order_class%% #wfacp-e-form .wfacp_main_form #payment li.wc_payment_method input.input-radio:checked',
				'%%order_class%% #wfacp-e-form .wfacp_main_form #payment.wc_payment_method input[type=radio]:checked',
				'%%order_class%% #wfacp-e-form .wfacp_main_form input[type=radio]:checked',
				'%%order_class%% #wfacp-e-form .wfacp_main_form #add_payment_method #payment ul.payment_methods li input[type=radio]:checked',
			];

			$radio_border_width = array(
				'selector'    => implode( ',', $radio_border_width_array ),
				'declaration' => sprintf( 'border-width:%s;', '5px' ),
			);
			ET_Builder_Element::set_style( $render_slug, $radio_border_width );

			/* Radio Border Width Color*/
			$radio_border_color_array = [
				'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #payment li.wc_payment_method input.input-radio:checked',
				'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #payment.wc_payment_method input[type=radio]:checked',
				'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type=radio]:checked',
				'%%order_class%% #wfacp-e-form input[type=radio]:checked',
				'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #add_payment_method #payment ul.payment_methods li input[type=radio]:checked',
				'%%order_class%% #wfacp-e-form #payment ul.payment_methods li input[type=radio]:checked',
				'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce input[type=radio]:checked',
				'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-cart #payment ul.payment_methods li input[type=radio]:checked',
			];

			$radio_border_color = array(
				'selector'    => implode( ',', $radio_border_color_array ),
				'declaration' => sprintf( 'border-color:%s;', esc_attr( $this->props['default_primary_color'] ) ),
			);
			ET_Builder_Element::set_style( $render_slug, $radio_border_color );

			/* Checkbox */
			$checkbox_border_color_array = [
				'%%order_class%% #wfacp-e-form .wfacp-form input[type=checkbox]:checked',
				'%%order_class%% #wfacp-e-form .wfacp_main_form #payment input[type=checkbox]:checked',
				'%%order_class%% #wfacp-e-form .wfacp_main_form .woocommerce-input-wrapper .wfacp-form-control:checked',
				'%%order_class%% #wfacp-e-form .wfacp_main_form input[type=checkbox]:checked',
			];
			$checkbox_border_color       = array(
				'selector'    => implode( ',', $checkbox_border_color_array ),
				'declaration' => sprintf( 'border-color:%s;', esc_attr( $this->props['default_primary_color'] ) ),
			);
			ET_Builder_Element::set_style( $render_slug, $checkbox_border_color );


			ET_Builder_Element::set_style( $render_slug, [
				'selector'    => '%%order_class%% #wfacp-e-form .wfacp_main_form input[type=checkbox]:after',
				'declaration' => sprintf( 'display:%s;', 'block' )
			] );
			ET_Builder_Element::set_style( $render_slug, [
				'selector'    => '%%order_class%% #wfacp-e-form .wfacp_main_form input[type=checkbox]:before',
				'declaration' => sprintf( 'display:%s;', 'none' )
			] );
			ET_Builder_Element::set_style( $render_slug, [
				'selector'    => '%%order_class%% #wfacp-e-form .wfacp_main_form input[type=checkbox]:checked',
				'declaration' => sprintf( 'border-width:%s;', '8px' )
			] );

		}


		$template->set_form_data( $this->props );
		ob_start();
		?>
        <div class='wfacp_form_divi_container'>
            <div class='wfacp_divi_forms' id='wfacp-e-form'><?php include $template->wfacp_get_form() ?></div>
        </div>
		<?php
		return ob_get_clean();
	}

	public function get_complete_fields() {
		add_filter( 'et_builder_module_general_fields', '__return_empty_array' );
		$fields = parent::get_complete_fields();
		remove_filter( 'et_builder_module_general_fields', '__return_empty_array' );

		return $fields;
	}

	private function privacy_policy_styling() {
		$tab_id = $this->add_tab( __( 'Privacy Policy', 'woofunnel-aero-checkout' ), 2 );


		$default = [
			'range_settings' => [
				'min'  => '1',
				'max'  => '100',
				'step' => '1',
			],
			'default'        => '12px',
			'unit'           => 'px',
			'allowed_units'  => [ 'px' ],

		];
		$typo    = [
			'%%order_class%% #wfacp-e-form #payment .woocommerce-privacy-policy-text p',
			'%%order_class%% #wfacp-e-form #payment .woocommerce-privacy-policy-text a',
		];

		$color = [
			'%%order_class%% #wfacp-e-form #payment .woocommerce-privacy-policy-text p',
		];

		$default = [
			'range_settings' => [
				'min'  => '1',
				'max'  => '100',
				'step' => '1',
			],
			'default'        => [ 12, 'px' ],
			'unit'           => 'px',
			'allowed_units'  => [ 'px' ],

		];

		$this->add_font_size( $tab_id, 'wfacp_privacy_policy_font', $typo, '', $default );
		$this->add_color( $tab_id, 'wfacp_privacy_policy_color', $color, '', '#777777' );


	}

	private function terms_policy_styling() {
		$tab_id = $this->add_tab( __( 'Terms & Conditions', 'woofunnel-aero-checkout' ), 2 );

		$default = [
			'range_settings' => [
				'min'  => '1',
				'max'  => '100',
				'step' => '1',
			],
			'default'        => '12px',
			'unit'           => 'px',
			'allowed_units'  => [ 'px' ],

		];


		$typo = [
			'%%order_class%% #wfacp-e-form #payment  .woocommerce-terms-and-conditions-wrapper .form-row label',
			'%%order_class%% #wfacp-e-form #payment .woocommerce-terms-and-conditions-wrapper .form-row label span',
			'%%order_class%% #wfacp-e-form #payment .woocommerce-terms-and-conditions-wrapper .form-row label a',
		];

		$color = [
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce  #payment .woocommerce-terms-and-conditions-wrapper .form-row',
			'%%order_class%% #wfacp-e-form .wfacp_main_form.woocommerce #payment .woocommerce-terms-and-conditions-wrapper .woocommerce-terms-and-conditions-checkbox-text',
		];


		$this->add_font_size( $tab_id, 'wfacp_terms_conditions_font', $typo, '', $default );
		$this->add_color( $tab_id, 'wfacp_terms_color', $color, '', '#777' );


	}

	/* Button Icon and Text  */
	private function icon_text( $tab_id, $counter_step ) {


		$this->add_text( $tab_id, 'step_' . $counter_step . '_text_after_place_order', __( " Sub Text", 'woofunnel-aero-checkout' ), '', [], 'wfacp_field_text_wrap' );
		$icon_list = [
			'aero-e902' => __( 'Arrow 1', 'woofunnels-aero-checkout' ),
			'aero-e906' => __( 'Arrow 2', 'woofunnels-aero-checkout' ),
			'aero-e907' => __( 'Arrow 3', 'woofunnels-aero-checkout' ),
			'aero-e908' => __( 'Checkmark', 'woofunnels-aero-checkout' ),
			'aero-e905' => __( 'Cart 1', 'woofunnels-aero-checkout' ),
			'aero-e901' => __( 'Lock 1', 'woofunnels-aero-checkout' ),
			'aero-e900' => __( 'Lock 2', 'woofunnels-aero-checkout' ),
		];

		$bwf_icon_list = apply_filters( 'bwf_icon_list', $icon_list );

		$this->add_switcher( $tab_id, 'enable_icon_with_place_order_' . $counter_step, __( 'Enable Icon', 'woofunnels-aero-checkout' ), '' );


		$condition = [
			'enable_icon_with_place_order_' . $counter_step => "on"
		];
		$this->add_select( $tab_id, 'icons_with_place_order_list_' . $counter_step, "Select Icon", $bwf_icon_list, 'aero-e901', $condition );

	}


	/* ----------------Coupon field Under Content Section----------------------- */

	private function coupon_fields() {
		$coupon_id = $this->add_tab( __( 'Coupon', 'woocommerce' ), 5 );
		$this->add_text( $coupon_id, 'form_coupon_button_text', __( 'Coupon Button Text', 'woofunnels-aero-checkout' ), __( 'Apply', 'woocommerce' ) );
	}

	/* -------------------------------End--------------------------------------- */


	/* ----------------Order Summary field Under Content Section----------------------- */

	private function order_summary_fields() {
		$tab_id = $this->add_tab( __( 'Order Summary', 'woofunnel-aero-checkout' ), 5 );
		$this->add_switcher( $tab_id, 'order_summary_enable_product_image', __( 'Enable Image', 'woofunnels-aero-checkout' ), 'on' );

	}

	/* -------------------------------End--------------------------------------- */
}

new WFACP_Divi_Form;