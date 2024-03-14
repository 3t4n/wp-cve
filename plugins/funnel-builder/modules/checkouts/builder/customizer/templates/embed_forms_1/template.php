<?php

#[AllowDynamicProperties]

 final class WFACP_Template_Custom_Page extends WFACP_Pre_Built {

	private static $ins = null;
	protected $template_type = 'embed_form';
	protected $form_steps_data = [];
	public $steps_inline_styles = [];

	/**
	 * Using protected method no one create new instance this class
	 * WFACP_template_layout1 constructor.
	 */
	protected function __construct() {
		parent::__construct();
		$this->template_dir  = __DIR__;
		$this->template_slug = 'embed_forms_2';
		$this->css_classes   = [];

		define( 'WFACP_TEMPLATE_MODULE_DIR', __DIR__ . '/views/template-parts/sections' );
		$this->url             = WFACP_PLUGIN_URL . '/builder/customizer/templates/embed_forms_1/views/';
		$is_customizer_preview = WFACP_Common::is_customizer();
		if ( false == $is_customizer_preview ) {
			remove_action( 'wp_print_styles', [ $this, 'remove_theme_css_and_scripts' ], 100 );
		}
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_style' ], 9999 );

		add_action( 'wfacp_header_print_in_head', [ $this, 'add_step_form_style' ] );

		add_action( 'wfacpef_before_form', [ $this, 'get_step_forms_data' ] );
		add_filter( 'wfacp_style_default_setting', [ $this, 'wfacp_multi_tab_default_setting' ], 11, 2 );
		add_filter( 'wfacp_load_template', [ $this, 'disable_template_loading' ] );
		add_filter( 'wfacp_order_summary_cols_span', '__return_empty_string' );

		/* Activate DIvi Customizer csss for embed form */
		add_action( 'wp', [ $this, 'run_divi_customizer_css' ] );
		add_filter( 'body_class', [ $this, 'add_body_class' ] );
		add_action( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );

	}

	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self();
		}

		return self::$ins;
	}


	public function enqueue_style() {

		if ( apply_filters( 'wfacp_not_allowed_cart_fragments_js_for_embed_form', true, $this ) ) {
			wp_dequeue_script( 'wc-cart-fragments' );
		}

		wp_enqueue_style( 'layout1-style', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/css/wfacp-form.min.css', '', WFACP_VERSION, false );
		if ( is_rtl() ) {
			wp_enqueue_style( 'layout1-style-rtl', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/css/wfacp-form-style-rtl.css', '', WFACP_VERSION, false );

		}
	}


	public function get_step_forms_data() {
		$selected_template_slug = $this->get_template_slug();
		$layout_key             = '';
		if ( isset( $selected_template_slug ) && $selected_template_slug != '' ) {
			$layout_key = $selected_template_slug . '_';
		}

		$section_key = 'wfacp_form';
		$data        = array();

		/* Layout Section */
		$data[ $section_key ]['layout']['step_form_max_width'] = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'step_form_max_width' );
		$data[ $section_key ]['layout']['disable_steps_bar']   = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'disable_steps_bar' );


		/* Stepbar styling */
		$data[ $section_key ]['steps_styling']['step_heading_font_size']     = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'step_heading_font_size' );
		$data[ $section_key ]['steps_styling']['step_sub_heading_font_size'] = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'step_sub_heading_font_size' );
		$data[ $section_key ]['steps_styling']['step_alignment']             = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'step_alignment' );


		/* Step Data Section */

		$no_of_fields = $this->get_step_count();
		$count_is     = 1;
		for ( $i = 0; $i < $no_of_fields; $i ++ ) {
			$stepData           = array();
			$field_key_name     = 'name_' . $i;
			$field_key_headling = 'headline_' . $i;

			$name_text    = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . $field_key_name );
			$heading_text = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . $field_key_headling );

			if ( ( isset( $heading_text ) && $heading_text != '' ) || ( isset( $name_text ) && $name_text != '' ) ) {
				$stepData[ $field_key_name ]         = $name_text;
				$stepData[ $field_key_headling ]     = $heading_text;
				$data[ $section_key ]['step_form'][] = $stepData;
			} else {
				$data[ $section_key ]['step_form'][] = [
					'name_' . $i     => 'Step ' . $count_is . ' Heading',
					'headline_' . $i => 'Step ' . $count_is . ' Sub heading',
				];
			}
			$count_is ++;

		}

		/* Layout Section */
		$data[ $section_key ]['colors']['active_step_bg_color']         = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'active_step_bg_color' );
		$data[ $section_key ]['colors']['active_step_text_color']       = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'active_step_text_color' );
		$data[ $section_key ]['colors']['active_step_count_bg_color']   = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'active_step_count_bg_color' );
		$data[ $section_key ]['colors']['active_step_count_text_color'] = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'active_step_count_text_color' );

		$data[ $section_key ]['border-color']['active_step_count_border_color'] = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'active_step_count_border_color' );
		$data[ $section_key ]['border-color']['active_step_tab_border_color']   = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'active_step_tab_border_color' );

		$data[ $section_key ]['colors']['inactive_step_bg_color']         = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'inactive_step_bg_color' );
		$data[ $section_key ]['colors']['inactive_step_text_color']       = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'inactive_step_text_color' );
		$data[ $section_key ]['colors']['inactive_step_count_bg_color']   = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'inactive_step_count_bg_color' );
		$data[ $section_key ]['colors']['inactive_step_count_text_color'] = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'inactive_step_count_text_color' );

		$data[ $section_key ]['border-color']['inactive_step_count_border_color'] = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'inactive_step_count_border_color' );
		$data[ $section_key ]['border-color']['inactive_step_tab_border_color']   = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'inactive_step_tab_border_color' );


		$data[ $section_key ]['colors']['form_content_color'] = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'form_content_color' );


		$data[ $section_key ]['other']['field_border_width'] = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'field_border_width' );

		/* FOrm Border */

		$data[ $section_key ]['other']['form_border']['border-style'] = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'form_border_type' );
		$data[ $section_key ]['other']['form_border']['border-width'] = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'form_border_width' );
		$data[ $section_key ]['other']['form_border']['border-color'] = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'form_border_color' );
		$data[ $section_key ]['other']['padding']                     = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'form_inner_padding' );


		if ( isset( $data[ $section_key ]['other']['form_border'] ) ) {
			$form_border = $data[ $section_key ]['other']['form_border'];
			if ( is_array( $form_border ) && count( $form_border ) > 0 ) {
				foreach ( $form_border as $fkey => $fborder ) {
					$px = '';
					if ( $fkey == 'border-width' ) {

						$px = 'px';

						if ( isset( $data[ $section_key ]['layout']['disable_steps_bar'] ) && $data[ $section_key ]['layout']['disable_steps_bar'] == false ) {
							$this->steps_inline_styles['desktop']['body.wfacpef_page #wfacp-e-form .wfacp_form .wfacp-inner-form-detail-wrap']['border-top-width']    = 0 . $px;
							$this->steps_inline_styles['desktop']['body.wfacpef_page #wfacp-e-form .wfacp_form .wfacp-inner-form-detail-wrap']['border-left-width']   = $fborder . $px;
							$this->steps_inline_styles['desktop']['body.wfacpef_page #wfacp-e-form .wfacp_form .wfacp-inner-form-detail-wrap']['border-right-width']  = $fborder . $px;
							$this->steps_inline_styles['desktop']['body.wfacpef_page #wfacp-e-form .wfacp_form .wfacp-inner-form-detail-wrap']['border-bottom-width'] = $fborder . $px;
							continue;
						}


					}
					$this->steps_inline_styles['desktop']['body.wfacpef_page #wfacp-e-form .wfacp_form .wfacp-inner-form-detail-wrap'][ $fkey ] = $fborder . $px;


				}

			}
		}


		if ( isset( $data[ $section_key ]['other']['padding'] ) ) {
			$this->steps_inline_styles['desktop']['body #wfacp-e-form .wfacp_form .wfacp-inner-form-detail-wrap']['padding'] = $data[ $section_key ]['other']['padding'] . "px";
		}


		$this->form_steps_data = $data;


		if ( isset( $data[ $section_key ]['colors']['form_content_color'] ) ) {
			$form_content_color                                                           = $data[ $section_key ]['colors']['form_content_color'];
			$this->steps_inline_styles['desktop']['body .wfacp_main_form label']['color'] = $form_content_color;

			$this->steps_inline_styles['desktop']['body .wfacp_main_form p']['color']                                    = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form .woocommerce-terms-and-conditions']['color']    = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form .woocommerce-terms-and-conditions h1']['color'] = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form .woocommerce-terms-and-conditions h2']['color'] = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form .woocommerce-terms-and-conditions h3']['color'] = $form_content_color;
			$this->steps_inline_styles['desktop']['body #et_builder_outer_content #wfacp-e-form p']['color']             = $form_content_color . ' !important';

			$this->steps_inline_styles['desktop']['body .wfacp_main_form span.woocommerce-input-wrapper label.checkbox']['color']                         = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form table.shop_table.woocommerce-checkout-review-order-table thead tr th']['color']  = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form table.shop_table.woocommerce-checkout-review-order-table tr td']['color']        = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form table.shop_table.woocommerce-checkout-review-order-table tr th']['color']        = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form .woocommerce-form-coupon-toggle.wfacp-woocom-coupon .woocommerce-info']['color'] = $form_content_color;

			$this->steps_inline_styles['desktop']['body .wfacp_main_form .woocommerce-info']['color']                 = $form_content_color . ' !important';
			$this->steps_inline_styles['desktop']['body #wfacp-e-form .woocommerce-info .message-container']['color'] = $form_content_color;

			$this->steps_inline_styles['desktop']['body .wfacp_main_form form.woocommerce-form.woocommerce-form-login.login p']['color']       = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form label.woocommerce-form__label span']['color']                         = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form form.checkout_coupon.woocommerce-form-coupon p']['color']             = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form .shop_table.wfacp-product-switch-panel .product-name label']['color'] = $form_content_color;

			$this->steps_inline_styles['desktop']['body .wfacp_main_form .shop_table.wfacp-product-switch-panel .product-price']['color']                         = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form .wfacp_row_wrap .product-name .wfacp_product_sec .wfacp_product_choosen_label']['color'] = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form .shop_table.wfacp-product-switch-panel .wfacp-product-switch-title']['color']            = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form .wfacp-product-switch-title .product-remove']['color']                                   = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form .wfacp-product-switch-title .product-quantity']['color']                                 = $form_content_color;
			$this->steps_inline_styles['desktop']['body .wfacp_main_form .wfacp_shipping_table tr.shipping td']['color']                                          = $form_content_color;

		}

		if ( isset( $data[ $section_key ]['layout']['step_form_max_width'] ) ) {
			$step_form_max_width = $data[ $section_key ]['layout']['step_form_max_width'];

			$popup_width = $step_form_max_width + 10;

			$this->steps_inline_styles['desktop'][ 'body .' . $section_key ]['max-width']                                                 = $step_form_max_width . 'px';
			$this->steps_inline_styles['desktop']['body .wfacp_modal_outerwrap .wfacp_modal_innerwrap #wfacp_modal_content']['max-width'] = $popup_width . 'px';


			$this->steps_inline_styles['desktop']['body .wfacp_form']['padding'] = '0';

			$this->steps_inline_styles['desktop']['body .wfacp_paypal_express']['padding'] = '0';

		}

		$data[ $section_key ]['colors']['section_bg_color'] = WFACP_Common::get_option( $section_key . '_section_' . $layout_key . 'section_bg_color' );
		if ( isset( $data[ $section_key ]['colors']['section_bg_color'] ) ) {
			$section_bg_color                                                                                                        = $data[ $section_key ]['colors']['section_bg_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . ' .wfacp-inner-form-detail-wrap' ]['background-color']  = $section_bg_color;
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . ' .woocommerce-checkout #payment' ]['background-color'] = $section_bg_color;
		}

		if ( isset( $data[ $section_key ]['colors']['active_step_bg_color'] ) ) {
			$active_step_bg_color                                                                                                          = $data[ $section_key ]['colors']['active_step_bg_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . ' .wfacp-payment-tab-list.wfacp-active' ]['background-color'] = $active_step_bg_color;
		}

		if ( isset( $data[ $section_key ]['colors']['active_step_text_color'] ) ) {
			$active_step_text_color                                                                                                           = $data[ $section_key ]['colors']['active_step_text_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . ' .wfacp-payment-tab-list.wfacp-active .wfacp_tcolor' ]['color'] = $active_step_text_color;
		}

		if ( isset( $data[ $section_key ]['colors']['inactive_step_bg_color'] ) ) {
			$inactive_step_bg_color                                                                                           = $data[ $section_key ]['colors']['inactive_step_bg_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . ' .wfacp-payment-tab-list' ]['background-color'] = $inactive_step_bg_color;
			//	$this->steps_inline_styles['desktop'][ 'body .' . $section_key . ' .wfacp-payment-tab-wrapper' ]['background-color'] = $inactive_step_bg_color;
		}
		if ( isset( $data[ $section_key ]['colors']['inactive_step_text_color'] ) ) {
			$inactive_step_text_color                                                                                            = $data[ $section_key ]['colors']['inactive_step_text_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . ' .wfacp-payment-tab-list .wfacp_tcolor' ]['color'] = $inactive_step_text_color;
		}
		if ( isset( $data[ $section_key ]['colors']['active_step_count_bg_color'] ) ) {
			$active_step_count_bg_color                                                                                                                            = $data[ $section_key ]['colors']['active_step_count_bg_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . ' .wfacp-payment-tab-list.wfacp-active .wfacp-order2StepNumber' ]['background-color'] = $active_step_count_bg_color;
		}

		if ( isset( $data[ $section_key ]['colors']['active_step_count_text_color'] ) ) {
			$active_step_count_text_color                                                                                                               = $data[ $section_key ]['colors']['active_step_count_text_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . ' .wfacp-payment-tab-list.wfacp-active .wfacp-order2StepNumber' ]['color'] = $active_step_count_text_color;
		}

		if ( isset( $data[ $section_key ]['colors']['inactive_step_count_bg_color'] ) ) {
			$inactive_step_count_bg_color                                                                                                             = $data[ $section_key ]['colors']['inactive_step_count_bg_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . ' .wfacp-payment-tab-list .wfacp-order2StepNumber' ]['background-color'] = $inactive_step_count_bg_color;
		}
		if ( isset( $data[ $section_key ]['colors']['inactive_step_count_text_color'] ) ) {
			$inactive_step_count_text_color                                                                                                = $data[ $section_key ]['colors']['inactive_step_count_text_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . ' .wfacp-payment-tab-list .wfacp-order2StepNumber' ]['color'] = $inactive_step_count_text_color;
		}
		if ( isset( $data[ $section_key ]['steps_styling']['step_alignment'] ) ) {
			$step_alignment = $data[ $section_key ]['steps_styling']['step_alignment'];

			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . '  .wfacp-order2StepHeaderText' ]['text-align'] = $step_alignment;

		}


		/* Active border color */
		if ( isset( $data[ $section_key ]['border-color']['active_step_count_border_color'] ) ) {
			$active_step_count_border_color                                                                                                                     = $data[ $section_key ]['border-color']['active_step_count_border_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . '  .wfacp-payment-tab-list.wfacp-active .wfacp-order2StepNumber' ]['border-color'] = $active_step_count_border_color;
		}

		if ( isset( $data[ $section_key ]['border-color']['active_step_tab_border_color'] ) ) {
			$active_step_tab_border_color                                                                                               = $data[ $section_key ]['border-color']['active_step_tab_border_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . '  .wfacp-payment-tab-list.wfacp-active' ]['border-color'] = $active_step_tab_border_color;

		}

		/* inActive border color */
		if ( isset( $data[ $section_key ]['border-color']['inactive_step_count_border_color'] ) ) {
			$inactive_step_count_border_color                                                                                                                        = $data[ $section_key ]['border-color']['inactive_step_count_border_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . ' .wfacp-payment-tab-list:not(.wfacp-active) .wfacp-order2StepNumber' ]['border-color'] = $inactive_step_count_border_color;
		}
		if ( isset( $data[ $section_key ]['border-color']['inactive_step_tab_border_color'] ) ) {
			$inactive_step_tab_border_color                                                                                                   = $data[ $section_key ]['border-color']['inactive_step_tab_border_color'];
			$this->steps_inline_styles['desktop'][ 'body .' . $section_key . '  .wfacp-payment-tab-list:not(.wfacp-active)' ]['border-color'] = $inactive_step_tab_border_color;
		}

		$step_heading_fonts = [
			'section_key' => 'wfacp_form',
			'target_to'   => 'body .wfacp-order2StepTitle',
			'source_from' => 'step_heading_font_size',
		];


		$this->wfacp_font_size( $data[ $section_key ]['steps_styling'], $step_heading_fonts );

		$step_subheading_fonts = [
			'section_key' => 'wfacp_form',
			'target_to'   => 'body .wfacp-order2StepSubTitle',
			'source_from' => 'step_sub_heading_font_size',
		];
		$this->wfacp_font_size( $data[ $section_key ]['steps_styling'], $step_subheading_fonts );


	}


	public function add_step_form_style() {

		$finalStyle[] = $this->customizer_css;
		$finalStyle[] = $this->steps_inline_styles;

		$deskotp_css_style = '';
		$tablet_css_style  = '';
		$mobile_css_style  = '';

		$this->customizer_css['desktop']['p .select2 span.selection']['display'] = 'block';

		if ( true == WFACP_Embed_Form_loader::$pop_up_trigger ) {
			$this->customizer_css['desktop']['p .select2 span.selection']['display']                                                 = 'none';
			$this->customizer_css['desktop']['body.wfacpef_page.et_divi_builder #et_builder_outer_content .et_pb_column']['z-index'] = '999';
		}


		foreach ( $this->steps_inline_styles as $key1 => $value ) {
			$this->customizer_css[ $key1 ] = array_merge( $this->customizer_css[ $key1 ], $value );
		}


		$form_id          = [
			'#wfacp_qr_model_wrap .wfacp_qr_wrap .button:hover',
			'#wfacp_qr_model_wrap .wfacp_qr_wrap .button',
			'body #wfacp_qr_model_wrap .wfacp_qr_wrap .button',
			'body  #wfacp_qr_model_wrap .wfacp_qr_wrap .button:hover',
			'.select2-search--dropdown .select2-search__field',
			'#wfacp_qr_model_wrap *',
			'.select2-results__options',
			'body #et_builder_outer_content #wfacp-e-form p',
			'body .wfacp_modal_outerwrap .wfacp_modal_innerwrap #wfacp_modal_content',
			'body.wfacpef_page #wfacp-e-form .wfacp_form .wfacp-inner-form-detail-wrap',
			'#et-boc .et-l span.select2-selection.select2-selection--multiple',
			'body.wfacpef_page .wfacp-payment-title.wfacp_embed_step_3',
			'body.wfacpef_page.et_divi_builder #et_builder_outer_content .et_pb_column',
			'body #wfacp-e-form .wfacp_form .wfacp-inner-form-detail-wrap',
			'body #et-boc #wfacp-e-form .wfacp-form a',
			'body #et-boc #wfacp-e-form .wfacp-form a:hover',
			'#et-boc .et-l span.select2-selection.select2-selection--multiple',
		];
		$body_not_removed = [
			'body.wfacpef_page #wfacp-e-form .wfacp_form',
			'body.wfacpef_page .wfacp-payment-title.wfacp_embed_step_3',
			'body #wfacp-e-form .wfacp_form .wfacp-inner-form-detail-wrap'
		];


		if ( isset( $this->customizer_css['desktop'] ) && is_array( $this->customizer_css['desktop'] ) && count( $this->customizer_css['desktop'] ) > 0 ) {
			foreach ( $this->customizer_css['desktop'] as $key => $value ) {
				$elment = $key;

				if ( preg_match( "~\bbody\b~", $key ) && ! in_array( $key, $body_not_removed ) ) {
					$elment = str_replace( 'body', '', $key );
				}


				if ( ! in_array( $key, $form_id ) ) {
					$elment = 'body #wfacp-e-form ' . $elment;
				}


				$pixel_used_property = [ 'font-size', 'border-width' ];


				foreach ( $value as $css_property => $css_value ) {
					if ( '' == $css_value ) {
						continue;
					}
					$suffix = '';


					if ( in_array( $css_property, $pixel_used_property ) && false == strpos( $css_value, 'px' ) ) {
						$suffix = 'px';
					}
					if ( 'px' == $css_value ) {
						$css_value = '0px';
					}
					if ( $css_property == 'content' ) {
						if ( strpos( $css_value, "&#039;" ) !== false ) {
							$selector = $css_property . ':"' . str_replace( "&#039;", "'", $css_value ) . '"';
						} else {
							$selector = $css_property . ':"' . $css_value . '"';
						}

					} else {
						$selector = $css_property . ':' . $css_value . $suffix;
					}
				
					$style_inline      = $elment . '{' . $selector . ';}';
					$deskotp_css_style .= $style_inline;
				}
			}
		}


		if ( isset( $this->customizer_css['tablet'] ) && is_array( $this->customizer_css['tablet'] ) && count( $this->customizer_css['tablet'] ) > 0 ) {
			$tablet_css_style .= '@media (max-width: 991px) {';
			foreach ( $this->customizer_css['tablet'] as $key => $value ) {
				$elment = $key;
				if ( preg_match( "~\bbody\b~", $key ) ) {
					$elment = str_replace( 'body', '', $key );

				}
				$elment = 'body #wfacp-e-form ' . $elment;
				foreach ( $value as $css_property => $css_value ) {
					$selector         = $css_property . ':' . $css_value;
					$style_inline     = $elment . '{' . $selector . ';}';
					$tablet_css_style .= $style_inline;
				}
			}
			$tablet_css_style .= '}';
		}

		if ( isset( $this->customizer_css['mobile'] ) && is_array( $this->customizer_css['mobile'] ) && count( $this->customizer_css['mobile'] ) > 0 ) {
			$mobile_css_style .= '@media (max-width: 767px) {';
			foreach ( $this->customizer_css['mobile'] as $key => $value ) {
				$elment = $key;
				if ( preg_match( "~\bbody\b~", $key ) ) {
					$elment = str_replace( 'body', '', $key );

				}
				$elment = 'body #wfacp-e-form ' . $elment;
				foreach ( $value as $css_property => $css_value ) {
					$selector         = $css_property . ':' . $css_value;
					$style_inline     = $elment . '{' . $selector . ';}';
					$mobile_css_style .= $style_inline;
				}
			}
			$mobile_css_style .= '}';
		}

		echo '<style>';
		echo $deskotp_css_style;
		echo $tablet_css_style;
		echo $mobile_css_style;
		echo '</style>';
	}

	public function get_form_step_data() {
		return $this->form_steps_data;
	}


	public function wfacp_get_header() {
		return $this->template_dir . '/views/template-parts/customizer-header.php';
	}

	public function wfacp_get_footer() {
		return $this->template_dir . '/views/template-parts/customizer-footer.php';
	}

	public function change_cancel_url( $url ) {
		if ( ! WFACP_Core()->public->is_checkout_override() ) {
			if ( isset( $_REQUEST['wfacp_embed_form_page_id'] ) ) {
				$embed_id = filter_input( INPUT_POST, 'wfacp_embed_form_page_id', FILTER_UNSAFE_RAW );
				$url      = get_the_permalink( $embed_id );
			}

		}

		return $url;
	}


	public function wfacp_multi_tab_default_setting( $panel_details, $panel_key ) {

		$selected_template_slug = $this->get_template_slug();
		if ( array_key_exists( 'wfacp_style', $panel_key ) ) {
			$panel_details['panel'] = 'no';
			unset( $panel_details['sections']['colors'] );
			unset( $panel_details['sections']['typography']['fields']['ct_font_size'] );
			unset( $panel_details['sections']['typography']['fields'][ $selected_template_slug . '_content_fs' ] );
			$panel_details['sections']['typography']['data']['priority'] = 35;

		}

		return $panel_details;

	}

	public function disable_template_loading( $status ) {
		if ( ! WFACP_Common::is_customizer() ) {
			$status = false;
		}

		return $status;
	}


	public function run_divi_customizer_css() {
		if ( function_exists( 'et_divi_add_customizer_css' ) ) {
			et_divi_add_customizer_css();
		}
	}

	public function no_follow_no_index() {
		global $post;
		if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {
			parent::no_follow_no_index();

		}
	}

	public function add_body_class( $classes ) {
		$classes[] = 'wfacpef_page';

		return $classes;
	}

	public function add_internal_css() {

		echo "<style>";
		echo ".customize-partial-edit-shortcuts-shown #wfacp-e-form .wfacp_form {margin: auto;}";
		echo ".customize-partial-edit-shortcuts-shown .wfacpef_page #wfacp-e-form .wfacp_form .wfacp-inner-form-detail-wrap {border-top-width: 0px;}";
		echo ".customize-partial-edit-shortcuts-shown a.wfacp_qv-button.var_product {pointer-events: none !important;}";
		echo "body #wfacp-e-form .wfacp_form .wfacp-inner-form-detail-wrap {padding: 15px;}";
		echo "body.wfacpef_page #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-section.wfacp_order_coupon_box,body.wfacpef_page #wfacp-e-form .wfacp_main_form.woocommerce .wfacp-section.wfacp_order_summary_box {margin-bottom: 0;}";
		echo "body.wfacpef_page #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-info {padding-bottom: 0 !important;}";
		echo 'body.wfacpef_page .wfacp-coupon-section.wfacp_custom_row_wrap.clearfix {margin-bottom: 20px !important;margin-top: 10px;}';
		echo 'body.wfacpef_page #wfacp-e-form .woocommerce-notices-wrapper + .wfacp-coupon-section.clearfix {margin-bottom: 15px;}';
		echo 'body.wfacpef_page #wfacp-e-form .wfacp-order2StepTitle {font-weight: 700;}';
		echo 'body:not(.wfacpef_page) #wfacp-e-form .wfacp-order2StepTitle {font-size: 17px;}';
		echo "<style>";

	}

}

return WFACP_Template_Custom_Page::get_instance();
