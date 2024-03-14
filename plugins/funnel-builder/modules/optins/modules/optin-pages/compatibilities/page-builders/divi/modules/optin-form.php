<?php

#[AllowDynamicProperties]

  class WFOP_Optin_Form extends WFOP_Divi_HTML_BLOCK {
	public $slug = 'wfop_optin_form';

	public function __construct() {
		$this->ajax = true;
		parent::__construct();
	}

	public function setup_data() {
		$form_id = $this->add_tab( __( 'Form', 'funnel-builder' ), 5 );

		$optinPageId = $this->get_divi_page_id();

		$get_fields = [];
		if ( $optinPageId > 0 ) {
			$get_fields = WFOPP_Core()->optin_pages->form_builder->get_form_fields( $optinPageId );
		}

		$options = [
			'wffn-sm-100' => __( 'Full', 'funnel-builder' ),
			'wffn-sm-50'  => __( 'One Half', 'funnel-builder' ),
			'wffn-sm-33'  => __( 'One Third', 'funnel-builder' ),
			'wffn-sm-67'  => __( 'Two Third', 'funnel-builder' ),
		];

		if ( is_array( $get_fields ) && count( $get_fields ) > 0 ) {
			foreach ( $get_fields as $field ) {
				$default    = isset( $field['width'] ) ? $field['width'] : 'wffn-sm-100';
				$input_name = isset( $field['InputName'] ) ? $field['InputName'] : '';
				$label      = isset( $field['label'] ) ? $field['label'] : '';
				if ( ! empty( $input_name ) && ! empty( $label ) ) {
					$this->add_select( $form_id, $input_name, __( $label, 'funnel-builder' ), $options, $default );
				}
			}
		}

		$this->add_switcher( $form_id, 'show_labels', __( 'Label', 'funnel-builder' ), 'on' );

		$btn_id = $this->add_tab( __( 'Submit Button', 'funnel-builder' ), 5 );

		$this->add_text( $btn_id, 'button_text', __( 'Title', 'funnel-builder' ), __( 'Send Me My Free Guide', 'funnel-builder' ) );
		$this->add_text( $btn_id, 'subtitle', __( 'Sub Title', 'funnel-builder' ), '', [], '', __( 'Enter subtitle', 'funnel-builder' ) );
		$this->add_text( $btn_id, 'button_submitting_text', __( 'Submitting Text', 'funnel-builder' ), __( 'Submitting...', 'funnel-builder' ) );


		$this->style_field();

	}

	private function style_field() {

		$key       = "wfop_optin_form";
		$condition = [ 'show_labels' => 'on', ];

		$form_id = $this->add_tab( __( 'Form', 'funnel-builder' ), 2 );

		$this->add_subheading( $form_id, __( 'Label Typography', 'funnel-builder' ), '', $condition );

		$font_side_default = [ 'default' => '14px', 'unit' => 'px' ];
		$default           = '|400|||||||';

		$label_selector = '%%order_class%% .bwfac_form_sec > label';
		$this->add_typography( $form_id, $key . '_label_typography', $label_selector, '', $default, $condition, $font_side_default );

		$this->add_color( $form_id, $key . '_label_color', $label_selector, __( 'Text', 'funnel-builder' ), '', $condition );
		$this->add_color( $form_id, $key . '_mark_required_color', '%%order_class%% .bwfac_form_sec > label > span', __( 'Asterisk', 'funnel-builder' ), '', $condition );

		$this->add_subheading( $form_id, __( 'Input Typography', 'funnel-builder' ) );

		$font_side_default = [ 'default' => '16px', 'unit' => 'px' ];
		$default           = '|400|||||||';

		$this->add_typography( $form_id, $key . '_field_typography', '%%order_class%% .bwfac_form_sec .wffn-optin-input', '', $default, [], $font_side_default );

		$this->add_subheading( $form_id, __( 'Input Color', 'funnel-builder' ) );
		$this->add_color( $form_id, $key . '_field_text_color', '%%order_class%% .bwfac_form_sec .wffn-optin-input, %%order_class%% .bwfac_form_sec .wffn-optin-input::placeholder', __( 'Text', 'funnel-builder' ), '#3F3F3F' );
		$this->add_background_color( $form_id, $key . '_field_background_color', '%%order_class%% .bwfac_form_sec .wffn-optin-input', '#ffffff', __( 'Background', 'funnel-builder' ) );

		$this->add_select( $form_id, 'input_size', __( 'Field Size', 'funnel-builder' ), self::get_input_fields_sizes(), '12px' );

		$this->add_subheading( $form_id, __( 'Advanced', 'funnel-builder' ) );

		$border_args = [
			'border_type'          => 'solid',
			'border_width_top'     => '2',
			'border_width_bottom'  => '2',
			'border_width_left'    => '2',
			'border_width_right'   => '2',
			'border_radius_top'    => '0',
			'border_radius_bottom' => '0',
			'border_radius_left'   => '0',
			'border_radius_right'  => '0',
			'border_color'         => '#d8d8d8',
		];
		$this->add_border( $form_id, $key . '_field_border', '%%order_class%% .bwfac_form_sec .wffn-optin-input', [], $border_args );

		$this->add_subheading( $form_id, __( 'Spacing', 'funnel-builder' ) );

		$default = [
			'default'        => '10',
			'unit'           => 'px',
			'range_settings' => [
				'min'  => '0',
				'max'  => '60',
				'step' => '1',
			]
		];

		$defaults_padding = '0px|5px|0px|5px|false|false';

		$this->add_padding( $form_id, $key . '_column_gap_padding', '%%order_class%% .wffn-custom-optin-from .bwfac_form_sec', $defaults_padding, __( 'Columns', 'funnel-builder' ) );

		$defaults_margin = ' ||10px|||false|';
		$this->add_margin( $form_id, $key . '_row_gap_margin', '%%order_class%% .wffn-custom-optin-from .bwfac_form_sec', $defaults_margin, __( 'Rows', 'funnel-builder' ) );

		$btn_id = $this->add_tab( __( 'Submit Button', 'funnel-builder' ), 2 );

		$max_width_args = [ 'default' => '100%', 'unit' => '%' ];
		$this->add_max_width( $btn_id, $key . '_button_width', '%%order_class%% .bwfac_form_sec #wffn_custom_optin_submit', __( 'Button width (in %)', 'funnel-builder' ), $max_width_args );
		$this->add_text_alignments( $btn_id, $key . '_button_alignment', '%%order_class%% .wffn-custom-optin-from #bwf-custom-button-wrap', __( 'Alignment', 'funnel-builder' ), 'center' );
		$this->add_text_alignments( $btn_id, $key . '_button_text_alignment', '%%order_class%% .wffn-custom-optin-from #bwf-custom-button-wrap span', __( 'Text Alignment', 'funnel-builder' ), 'center' );

		$controls_tabs_id = $this->add_controls_tabs( $btn_id, "Button Color" );
		$colors_field     = [];
		$colors_field[]   = $this->add_color( $btn_id, $key . '_button_color', '%%order_class%% .bwfac_form_sec #wffn_custom_optin_submit .bwf_heading, %%order_class%% .bwfac_form_sec #wffn_custom_optin_submit .bwf_subheading', __( 'Label', 'funnel-builder' ), '#ffffff' );
		$colors_field[]   = $this->add_background_color( $btn_id, $key . '_button_bg_color', '%%order_class%% .bwfac_form_sec #wffn_custom_optin_submit', '#E69500', __( 'Background', 'funnel-builder' ) );

		$this->add_controls_tab( $controls_tabs_id, __( 'Normal', 'funnel-builder' ), $colors_field );

		$colors_field   = [];
		$colors_field[] = $this->add_color( $btn_id, $key . '_button_hover_color', '%%order_class%% .bwfac_form_sec #wffn_custom_optin_submit:hover .bwf_heading, %%order_class%% .bwfac_form_sec #wffn_custom_optin_submit:hover .bwf_subheading', __( 'Label', 'funnel-builder' ), '#ffffff' );
		$colors_field[] = $this->add_background_color( $btn_id, $key . '_button_hover_bg_color', '%%order_class%% .bwfac_form_sec #wffn_custom_optin_submit:hover', '#E69500', __( 'Background', 'funnel-builder' ) );

		$this->add_controls_tab( $controls_tabs_id, __( 'Hover', 'funnel-builder' ), $colors_field );


		$default = '|700|||||||';

		$this->add_subheading( $btn_id, __( 'Heading Typography', 'funnel-builder' ) );
		$this->add_typography( $btn_id, $key . '_button_text_typo', '%%order_class%% .bwfac_form_sec #wffn_custom_optin_submit .bwf_heading', '', $default );


		$sub_heading_font_size_default = [ 'default' => '15px', 'unit' => 'px' ];
		$default                       = '|400|||||||';

		$this->add_subheading( $btn_id, __( 'Sub Heading Typography', 'funnel-builder' ) );

		$this->add_typography( $btn_id, $key . '_button_subheading_text_typo', '%%order_class%% .bwfac_form_sec #wffn_custom_optin_submit .bwf_subheading', '', $default, [], $sub_heading_font_size_default );

		$this->add_subheading( $btn_id, __( 'Advanced', 'funnel-builder' ) );

		$defaults_padding = '15px|15px|15px|15px';
		$this->add_padding( $btn_id, $key . '_button_text_padding', '%%order_class%% .bwfac_form_sec #wffn_custom_optin_submit', $defaults_padding );

		$defaults_margin = '15px | 0px| 25px | 0px';
		$this->add_margin( $btn_id, $key . '_button_text_margin', '%%order_class%% .bwfac_form_sec #wffn_custom_optin_submit', $defaults_margin );

		$btn_border_args = [
			'border_type'          => 'solid',
			'border_width_top'     => '2',
			'border_width_bottom'  => '2',
			'border_width_left'    => '2',
			'border_width_right'   => '2',
			'border_radius_top'    => '0',
			'border_radius_bottom' => '0',
			'border_radius_left'   => '0',
			'border_radius_right'  => '0',
			'border_color'         => '#E69500',
		];
		$this->add_border( $btn_id, 'bwf_button_border', '%%order_class%% .bwfac_form_sec #wffn_custom_optin_submit', [], $btn_border_args );

		$this->add_box_shadow( $btn_id, 'button_text_alignment_box_shadow', '%%order_class%% .bwfac_form_sec #wffn_custom_optin_submit' );

		$spacing_id = $this->add_tab( __( 'Spacing', 'funnel-builder' ), 2 );
		$this->add_margin( $spacing_id, $key . '_text_margin', '%%order_class%%' );
		$this->add_padding( $spacing_id, $key . '_text_padding', '%%order_class%%' );


	}

	public static function get_input_fields_sizes() {
		return [
			'6px'  => __( 'Small', 'funnel-builder' ),
			'9px'  => __( 'Medium', 'funnel-builder' ),
			'12px' => __( 'Large', 'funnel-builder' ),
			'15px' => __( 'Extra Large', 'funnel-builder' ),
		];
	}

	public function get_divi_page_id() {
		$post_id = 0;
		if ( wp_doing_ajax() ) {

			if ( isset( $_REQUEST['action'] ) && "heartbeat" === $_REQUEST['action'] && isset( $_REQUEST['data'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( isset( $_REQUEST['data']['et'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$post_id = $_REQUEST['data']['et']['post_id']; //phpcs:ignore
				}
			}

			if ( isset( $_REQUEST['post_id'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['post_id'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( isset( $_REQUEST['et_post_id'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['et_post_id'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}

			if ( $post_id > 0 ) {
				$post      = get_post( $post_id );
				$post_type = WFOPP_Core()->optin_pages->get_post_type_slug();
				if ( ! is_null( $post ) && $post->post_type === $post_type ) {
					return $post->ID;
				}
			}

		}

		return $this->get_the_ID();
	}

	protected function html( $attrs, $content = null, $render_slug = '' ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

		ob_start();
		$settings                       = $this->props;
		$settings['button_border_size'] = 0;


		$wrapper_class = 'divi-form-fields-wrapper';
		$show_labels   = ( isset( $settings['show_labels'] ) && 'off' === $settings['show_labels'] ) ? false : true;
		$input_size    = isset( $settings['input_size'] ) ? $settings['input_size'] : '12px';

		$wrapper_class .= $show_labels ? '' : ' wfop_hide_label';

		$optinPageId    = WFOPP_Core()->optin_pages->get_optin_id();
		$optin_fields   = WFOPP_Core()->optin_pages->form_builder->get_optin_layout( $optinPageId );
		$optin_settings = WFOPP_Core()->optin_pages->get_optin_form_integration_option( $optinPageId );

		foreach ( $optin_fields as $step_slug => $optinFields ) {
			foreach ( $optinFields as $key => $optin_field ) {
				$optin_fields[ $step_slug ][ $key ]['width'] = $settings[ $optin_field['InputName'] ];
			}
		}

		$custom_form = WFOPP_Core()->form_controllers->get_integration_object( 'form' );
		if ( $custom_form instanceof WFFN_Optin_Form_Controller_Custom_Form ) {
			$settings = wp_parse_args( $settings, WFOPP_Core()->optin_pages->form_builder->form_customization_settings_default() );
			$custom_form->_output_form( $wrapper_class, $optin_fields, $optinPageId, $optin_settings, 'inline', $settings );
		}

		if ( did_action( 'wp_ajax_et_wfop_optin_form' ) ) { ?>
			<script>
				jQuery(document).trigger('wffn_reload_phone_field');
			</script>
		<?php }	?>
		<style>
            .et-db #et-boc .et-l .et_pb_module .wffn-custom-optin-from .wffn-optin-input {
                padding: <?php echo $input_size;//phpcs:ignore ?> 15px;
            }


		</style>
		<?php
		return ob_get_clean();
	}
}

return new WFOP_Optin_Form;