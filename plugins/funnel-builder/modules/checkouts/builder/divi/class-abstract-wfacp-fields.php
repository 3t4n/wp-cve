<?php

#[AllowDynamicProperties]

 abstract class WFACP_Divi_Field extends ET_Builder_Module {
	protected $get_local_slug = '';
	public $vb_support = 'on';
	protected $post_id = 0;
	protected $tabs = [];
	protected $sub_tabs = [];
	protected $html_fields = [];
	protected $section_fields = [];
	protected $modules_fields = [];
	protected $typography = [];
	protected $tab_array = [];
	protected $style_selector = [];
	private $heading_tag_keys = [];
	public $global_typography = [ 'wfacp_font_family_typography', 'wfacp_mini_cart_font_family' ];
	protected $module_credits = array(
		'module_uri' => '',
		'author'     => 'FunnelKit',
		'author_uri' => 'https://funnelkit.com/',
	);

	public function __construct() {
		parent::__construct();
		$this->advanced_fields['margin_padding']['use_margin']  = false;
		$this->advanced_fields['margin_padding']['use_padding'] = false;
	}

	public function add_heading( $tab_id, $heading, $separator = '', $conditions = [], $not_condition = [] ) {

		$key = $this->get_unique_id();

		$this->modules_fields[ $key ] = array(
			'label'     => $heading,
			'type'      => 'text',
			'className' => 'wfacp_heading_divi_builder',
			'default'   => ''
		);


		if ( is_array( $conditions ) && ! empty( $conditions ) ) {


			$this->show_if( $key, $conditions );
		}
		if ( is_array( $not_condition ) && ! empty( $not_condition ) ) {


			$this->show_if_not( $key, $not_condition );
		}
		$this->assign_tab( $key, $tab_id );

		return $key;
	}

	protected function add_switcher( $tab_id, $key, $label = '', $default = 'off', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = __( 'Enable', 'woofunnels-aero-checkout' );
		}
		$this->modules_fields[ $key ] = array(
			'label'           => $label,
			'type'            => 'yes_no_button',
			'default'         => $this->get_default_data( $key, $default ),
			'option_category' => 'configuration',
			'options'         => array(
				'on'  => esc_html__( 'Yes', 'et_builder' ),
				'off' => esc_html__( 'No', 'et_builder' ),
			),
		);


		$this->assign_tab( $key, $tab_id );
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}

		return $key;

	}

	protected function add_select( $tab_id, $key, $label, $options, $default, $conditions = [] ) {
		$this->modules_fields[ $key ] = array(
			'label'   => $label,
			'type'    => 'select',
			'options' => $options,
			'default' => $this->get_default_data( $key, $default )
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}

		return $key;
	}

	public function add_text( $tab_id, $key, $label, $default = '', $conditions = [], $not_condition = [] ) {

		$this->modules_fields[ $key ] = array(
			'label'     => $label,
			'type'      => 'text',
			'default'   => $default,
			'className' => 'wfacp_divi_textarea',

		);


		if ( ! empty( $description ) ) {
			$this->modules_fields[ $key ]['description'] = $description;
		}

		if ( ! empty( $placeholder ) ) {
			$this->modules_fields[ $key ]['placeholder'] = $placeholder;
		}
		if ( ! empty( $classes ) ) {
			$this->modules_fields[ $key ]['classes'] = $classes;
		}
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		if ( is_array( $not_condition ) && ! empty( $not_condition ) ) {
			$this->show_if_not( $key, $not_condition );
		}

		$this->assign_tab( $key, $tab_id );


		return $key;
	}

	public function add_class( $key, $class ) {
		if ( isset( $this->modules_fields[ $key ] ) ) {
			$this->modules_fields[ $key ]['className'] = $class;
		}
	}

	protected function add_textArea( $tab_id, $key, $label, $default = '', $conditions = [] ) {

		$this->modules_fields[ $key ] = [
			'label'   => $label,
			'type'    => 'textarea',
			'default' => $this->get_default_data( $key, $default ),
		];

		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );

		return $key;
	}

	protected function add_typography( $tab_id, $key, $selectors = '', $label = '', $default = '#333333', $conditions = [], $font_side_default = [], $letter_spacing_default = [] ) {
		$keys = [];
		if ( in_array( $key, $this->global_typography ) ) {
			$keys[] = $this->add_global_font_size( $tab_id, $key, $selectors, '', '', [ 'aa' => "on" ], [] );
			$keys[] = $this->add_font( $tab_id, $key, $selectors, '', 'Open Sans', $conditions );
		} else {
			$keys[] = $this->add_font_size( $tab_id, $key, $selectors, '', '', $conditions, $font_side_default );
			$keys[] = $this->add_font( $tab_id, $key, $selectors, '', '', $conditions );
			$keys[] = $this->add_line_height( $tab_id, $key, $selectors, '', '1px', $conditions );
			$keys[] = $this->add_letter_spacing( $tab_id, $key . '_letter_spacing', $selectors, '', $letter_spacing_default, $conditions );
		}


		return $keys;
	}

	protected function add_text_alignments( $tab_id, $key, $selectors = '', $label = '', $default = 'left', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_html__( 'Alignment', 'et_builder' );
		}
		$this->modules_fields[ $key ] = array(
			'label'          => $label,
			'type'           => 'text_align',
			'mobile_options' => true,
			'options'        => et_builder_get_text_orientation_options(),
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}

	protected function add_font( $tab_id, $key, $selectors = '', $label = '', $default = 'default', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_html__( 'Select Font', 'et_builder' );
		}

		$defaultVal = $this->get_default_data( $key, $default );


		if ( in_array( $key, $this->global_typography ) ) {
			$defaultVal = $default;

		}


		$key = $key . '_typograhy';


		$this->modules_fields[ $key ] = array(
			'label'   => $label,
			'type'    => 'font',
			'default' => $defaultVal,
		);


		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}


	protected function add_font_size( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [], $font_side_default = [] ) {

		if ( empty( $label ) ) {
			$label = esc_html__( 'Font Size', 'et_builder' );
		}
		if ( empty( $default ) ) {
			$default = [ 'default' => '14px', 'unit' => 'px' ];
		}


		if ( ! empty( $font_side_default ) ) {
			$default = $font_side_default;
		}


		if ( ! isset( $default['range_settings'] ) ) {

			$default['range_settings'] = array(
				'min'  => '1',
				'max'  => '120',
				'step' => '1',
			);
		}


		if ( ! isset( $default['allowed_units'] ) ) {
			$default['allowed_units'] = array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' );
		}
		$this->typography[ $key . '_font_size' ] = $key . "_typograhy";


		$key = $key . '_font_size';


		$this->modules_fields[ $key ] = array(
			'label'           => $label,
			'type'            => 'range',
			'option_category' => 'font_option',
			'c_type'          => 'font_size',
			'default'         => $this->get_default_data( $key, $default['default'] ),
			'default_unit'    => $default['unit'],
			'allowed_units'   => $default['allowed_units'],
			'range_settings'  => $default['range_settings'],
			'responsive'      => true,
			'mobile_options'  => true,
		);


		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );


		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}

	protected function add_global_font_size( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [], $font_side_default = [] ) {

		if ( empty( $label ) ) {
			$label = esc_html__( 'Font Size', 'et_builder' );
		}
		if ( empty( $default ) ) {
			$default = [ 'default' => '14px', 'unit' => 'px' ];
		}


		if ( ! empty( $font_side_default ) ) {
			$default = $font_side_default;
		}


		if ( ! isset( $default['range_settings'] ) ) {

			$default['range_settings'] = array(
				'min'  => '1',
				'max'  => '120',
				'step' => '1',
			);
		}


		if ( ! isset( $default['allowed_units'] ) ) {
			$default['allowed_units'] = array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' );
		}
		$this->typography[ $key . '_font_size' ] = $key . "_typograhy";


		$key = $key . '_font_size';


		$this->modules_fields[ $key ] = array(
			'label'           => $label,
			'type'            => 'range',
			'option_category' => 'font_option',
			'c_type'          => 'font_size',
			'responsive'      => true,
			'mobile_options'  => true,
		);


		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );


		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}


	protected function add_letter_spacing( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = esc_html__( 'Letter Spacing', 'et_builder' );
		}
		if ( empty( $default ) ) {
			$default = [ 'default' => '0px', 'unit' => 'px' ];
		}
		if ( ! isset( $default['range_settings'] ) ) {
			$default['range_settings'] = array(
				'min'  => '1',
				'max'  => '10',
				'step' => '.2',
			);
		}
		if ( ! isset( $default['allowed_units'] ) ) {
			$default['allowed_units'] = array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' );
		}

		$this->modules_fields[ $key ] = [
			'label'           => $label,
			'type'            => 'range',
			'option_category' => 'font_option',
			'c_type'          => 'letter_spacing',
			'default'         => $this->get_default_data( $key, $default['default'] ),
			'default_unit'    => $default['unit'],
			'allowed_units'   => $default['allowed_units'],
			'range_settings'  => $default['range_settings'],
			'responsive'      => true,
			'mobile_options'  => true,
		];
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}


	protected function color_type() {
		return WFACP_Divi_Extension::$field_color_type;
	}

	protected function add_color( $tab_id, $key, $selectors = '', $label = 'Color', $default = '#000000', $conditions = [], $not_condition = [] ) {
		if ( empty( $label ) ) {
			$label = 'Color';
		}
		$this->modules_fields[ $key ] = array(
			'label'        => $label,
			'type'         => $this->color_type(),
			'custom_color' => true,
			'default'      => $this->get_default_data( $key, $default ),
		);

		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		if ( is_array( $not_condition ) && ! empty( $not_condition ) ) {
			$this->show_if_not( $key, $not_condition );
		}


		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}

	public function add_background_color( $tab_id, $key, $selectors = [], $default = '#000000', $label = '', $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = esc_attr__( 'Background', 'elementor' );
		}


		$this->modules_fields[ $key ] = array(
			'label'        => $label,
			'type'         => $this->color_type(),
			'c_type'       => 'background_color',
			'custom_color' => true,
			'default'      => $this->get_default_data( $key, $default ),
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}


		$this->assign_tab( $key, $tab_id );

		$this->set_selector( $tab_id, $key, $selectors );


		return $key;
	}

	public function add_border_color( $tab_id, $key, $selectors = [], $default = '#000000', $label = '', $box_shadow = false, $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = esc_attr__( 'Color', 'elementor' );
		}
		$keys_for_imp = [
			'wfacp_form_fields_validation_color',
			'wfacp_form_fields_hover_color',
			'wfacp_form_fields_focus_color',
			'wfacp_form_mini_cart_coupon_focus_color',
			'order_coupon_focus_color',
		];

		$color_selectors = [];
		if ( is_array( $selectors ) && count( $selectors ) > 0 ) {
			foreach ( $selectors as $selector ) {
				if ( in_array( $key, $keys_for_imp ) ) {
					$border_color = 'border-color:{{VALUE}} !important;';
				} else {
					$border_color = 'border-color:{{VALUE}};';
				}
				if ( true == $box_shadow ) {
					$border_color .= 'box-shadow:0 0 0 1px {{VALUE}} !important';
				}

				$color_selectors[ $selector ] = $border_color;

			}
		}


		$this->modules_fields[ $key ] = array(
			'label'        => $label,
			'type'         => $this->color_type(),
			'c_type'       => 'border_color',
			'custom_color' => true,
			'default'      => $this->get_default_data( $key, $default ),
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}

		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;

	}

	protected function add_border_radius( $tab_id, $key, $selector, $conditions = [], $default = [], $custom_label = '' ) {

		$label = __( 'Border Radius', 'elementor' );

		if ( ! empty( $custom_label ) ) {
			$label = $custom_label;
		}


		if ( empty( $default ) ) {
			$default = [ 'default' => '0', 'unit' => 'px' ];
		}

		$this->modules_fields[ $key ] = array(
			'label'            => $label,
			'type'             => 'range',
			'option_category'  => 'button',
			'c_type'           => 'border_radius',
			'default'          => $this->get_default_data( $key, $default['default'] ),
			'default_unit'     => $default['unit'],
			'default_on_front' => '',
			'allowed_units'    => [ 'px', 'em', '%' ],
			'hover'            => 'tabs',
			'mobile_options'   => true,
		);


		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selector );

		return $key;

	}

	protected function add_padding( $tab_id, $key, $selector, $default = '', $label = '', $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = esc_html__( 'Padding', 'et_builder' );
		}


		$this->modules_fields[ $key ] = array(
			'label'           => $label,
			'type'            => 'custom_padding',
			'c_type'          => 'padding',
			'mobile_options'  => true,
			'default'         => $this->get_default_data( $key, $default ),
			'option_category' => 'layout',
			'hover'           => 'tabs',
			'allowed_units'   => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selector );

		return $key;
	}

	protected function add_margin( $tab_id, $key, $selector, $default = '', $label = '', $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = esc_html__( 'Margin', 'et_builder' );
		}

		$this->modules_fields[ $key ] = array(
			'label'           => $label,
			'type'            => 'custom_margin',
			'c_type'          => 'margin',
			'mobile_options'  => true,
			'default'         => $this->get_default_data( $key, $default ),
			'default_unit'    => "px",
			'option_category' => 'layout',
			'hover'           => 'tabs',
			'allowed_units'   => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selector );

		return $key;
	}

	protected function add_border( $tab_id, $key, $selectors, $conditions = [], $default = [], $fields_options = [], $default_args = [] ) {

		$exclude_border_keys = [];

		$border_option = [
			'none'   => __( 'None', 'woofunnels-aero-checkout' ),
			'solid'  => __( 'Solid', 'woofunnels-aero-checkout' ),
			'double' => __( 'Double', 'woofunnels-aero-checkout' ),
			'dotted' => __( 'Dotted', 'woofunnels-aero-checkout' ),
			'dashed' => __( 'Dashed', 'woofunnels-aero-checkout' ),
			'groove' => __( 'Groove', 'woofunnels-aero-checkout' ),
		];


		//if ( empty( $default_args ) ) {
		$args = [
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
		//}
		if ( ! empty( $default_args ) ) {
			$default = $default_args;
		}

		$fields_keys = [];
		$default     = wp_parse_args( $default, $args );


		$wfacp_start_border                          = $this->get_unique_id();
		$this->modules_fields[ $wfacp_start_border ] = array(
			'label'     => 'start border',
			'type'      => 'hidden',
			'c_type'    => 'wfacp_start_border',
			'field_key' => $key,
			'selector'  => $selectors
		);

		$type_condition = [];
		if ( ! in_array( $key, $exclude_border_keys ) ) {
			$fields_keys[]  = $this->add_heading( $tab_id, __( 'Border', 'woofunnels-aero-checkout', '', $conditions ) );
			$border_type    = $this->add_select( $tab_id, $key . '_border_type', __( 'Type', 'woofunnels-aero-checkout' ), $border_option, $default['border_type'] );
			$fields_keys[]  = $border_type;
			$type_condition = [ $border_type => 'none' ];
			if ( ! empty( $conditions ) ) {
				$type_condition = array_merge( $type_condition, $conditions );
			}

			$fields_keys[] = $this->add_heading( $tab_id, __( 'Width', 'woofunnels-aero-checkout' ), '', $conditions, $type_condition );
			$fields_keys[] = $this->add_text( $tab_id, $key . '_border_width_top', __( 'Top', 'woofunnels-aero-checkout' ), $default['border_width_top'], $conditions, $type_condition );
			$fields_keys[] = $this->add_text( $tab_id, $key . '_border_width_bottom', __( 'Bottom', 'woofunnels-aero-checkout' ), $default['border_width_bottom'], $conditions, $type_condition );
			$fields_keys[] = $this->add_text( $tab_id, $key . '_border_width_left', __( 'Left', 'woofunnels-aero-checkout' ), $default['border_width_left'], $conditions, $type_condition );
			$fields_keys[] = $this->add_text( $tab_id, $key . '_border_width_right', __( 'Right', 'woofunnels-aero-checkout' ), $default['border_width_right'], $conditions, $type_condition );
			$fields_keys[] = $this->add_color( $tab_id, $key . '_border_color', $selectors, '', $default['border_color'], $conditions, $type_condition );
		}


		$fields_keys[] = $this->add_heading( $tab_id, __( 'Border Radius', 'woofunnels-aero-checkout' ), '', $conditions, $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_radius_top', __( 'Top', 'woofunnels-aero-checkout' ), $default['border_radius_top'], $conditions, $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_radius_bottom', __( 'Bottom', 'woofunnels-aero-checkout' ), $default['border_radius_bottom'], $conditions, $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_radius_left', __( 'Left', 'woofunnels-aero-checkout' ), $default['border_radius_left'], $conditions, $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_radius_right', __( 'Right', 'woofunnels-aero-checkout' ), $default['border_radius_right'], $conditions, $type_condition );

		$wfacp_end_border                          = $this->get_unique_id();
		$this->modules_fields[ $wfacp_end_border ] = array(
			'label'     => 'end border',
			'type'      => 'hidden',
			'c_type'    => 'wfacp_end_border',
			'field_key' => $key
		);

		if ( ! in_array( $key, $exclude_border_keys ) ) {
			$this->add_class( $key . '_border_width_top', 'wfacp_divi_border_width_start wfacp_border_width_top' );
			$this->add_class( $key . '_border_width_bottom', 'wfacp_border_width_bottom' );
			$this->add_class( $key . '_border_width_left', 'wfacp_border_width_left' );
			$this->add_class( $key . '_border_width_right', 'wfacp_divi_border_width_start wfacp_border_width_right' );
		}

		$this->add_class( $key . '_border_radius_top', 'wfacp_divi_border_width_start wfacp_border_width_top' );
		$this->add_class( $key . '_border_radius_bottom', 'wfacp_border_width_bottom' );
		$this->add_class( $key . '_border_radius_left', 'wfacp_border_width_left' );
		$this->add_class( $key . '_border_radius_right', 'wfacp_divi_border_width_end wfacp_border_width_right' );


		return $fields_keys;
	}

	protected function add_border_radius_new( $tab_id, $key, $selectors, $conditions = [], $default = [], $fields_options = [], $default_args = [] ) {


		$fields_keys = [];

		if ( empty( $default ) ) {
			$default = [
				'border_radius_top'    => '0',
				'border_radius_bottom' => '0',
				'border_radius_left'   => '0',
				'border_radius_right'  => '0',

			];
		}


		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_radius_top', __( 'Top', 'woofunnels-aero-checkout' ), $default['border_radius_top'], $conditions );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_radius_bottom', __( 'Bottom', 'woofunnels-aero-checkout' ), $default['border_radius_bottom'], $conditions );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_radius_left', __( 'Left', 'woofunnels-aero-checkout' ), $default['border_radius_left'], $conditions );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_radius_right', __( 'Right', 'woofunnels-aero-checkout' ), $default['border_radius_right'], $conditions );


		$this->add_class( $key . '_border_radius_top', 'wfacp_divi_border_width_start wfacp_border_width_top' );
		$this->add_class( $key . '_border_radius_bottom', 'wfacp_border_width_bottom' );
		$this->add_class( $key . '_border_radius_left', 'wfacp_border_width_left' );
		$this->add_class( $key . '_border_radius_right', 'wfacp_divi_border_width_end wfacp_border_width_right' );


		return $fields_keys;
	}

	protected function add_box_shadow( $tab_id, $key, $selectors, $default = [], $conditions = [] ) {


		$border_option = [
			''      => __( 'Ouline', 'woofunnels-upstroke-one-click-upsell' ),
			'inset' => __( 'Inset', 'woofunnels-upstroke-one-click-upsell' ),
		];


		$default_args = [
			'enable'     => 'off',
			'type'       => '',
			'horizontal' => '0',
			'vertical'   => '0',
			'blur'       => '0',
			'spread'     => '0',
			'color'      => '#dddddd',
		];


		$fields_keys                                 = [];
		$default                                     = wp_parse_args( $default, $default_args );
		$wfacp_start_border                          = $this->get_unique_id();
		$this->modules_fields[ $wfacp_start_border ] = array(
			'label'     => 'start box shadow',
			'type'      => 'hidden',
			'c_type'    => 'wfacp_start_box_shadow',
			'field_key' => $key,
			'selector'  => $selectors
		);


		$fields_keys[] = $this->add_heading( $tab_id, __( 'Box Shadow', 'woofunnels-upstroke-one-click-upsell', '', $conditions ) );
		$enabled       = $this->add_switcher( $tab_id, $key . '_shadow_enable', __( 'Enable', 'woofunnels-upstroke-one-click-upsell' ), $default['enable'], $conditions );

		$type_condition = [ $enabled => 'on' ];
		if ( ! empty( $conditions ) ) {
			$type_condition = array_merge( $type_condition, $conditions );
		}
		$fields_keys[] = $this->add_select( $tab_id, $key . '_shadow_type', __( 'Position', 'woofunnels-upstroke-one-click-upsell' ), $border_option, $default['type'], $type_condition );
		$fields_keys[] = $this->add_color( $tab_id, $key . '_shadow_color', $selectors, '', $default['color'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_shadow_horizontal', __( 'Horizontal', 'woofunnels-upstroke-one-click-upsell' ), $default['horizontal'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_shadow_vertical', __( 'Vertical', 'woofunnels-upstroke-one-click-upsell' ), $default['vertical'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_shadow_blur', __( 'Blur', 'woofunnels-upstroke-one-click-upsell' ), $default['blur'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_shadow_spread', __( 'Spread', 'woofunnels-upstroke-one-click-upsell' ), $default['spread'], $type_condition );


		$wfacp_end_border                          = $this->get_unique_id();
		$this->modules_fields[ $wfacp_end_border ] = array(
			'label'     => 'end border',
			'type'      => 'hidden',
			'c_type'    => 'wfacp_end_box_shadow',
			'field_key' => $key
		);

		$this->add_class( $key . '_shadow_horizontal', 'wfacp_divi_border_width_start wfacp_border_width_top' );
		$this->add_class( $key . '_shadow_vertical', 'wfacp_border_width_bottom' );
		$this->add_class( $key . '_shadow_blur', 'wfacp_border_width_left' );
		$this->add_class( $key . '_shadow_spread', 'wfacp_divi_border_width_start wfacp_border_width_right' );

		return $fields_keys;
	}


	protected function add_line_height( $tab_id, $key, $selectors = '', $label = '', $default = '1px', $conditions = [] ) {
		$key                          .= '_line_height';
		$this->modules_fields[ $key ] = array(
			'label'           => esc_html__( 'Line Height', 'et_builder' ),
			'type'            => 'range',
			'c_type'          => 'line_height',
			'mobile_options'  => true,
			'option_category' => 'font_option',
			'default_unit'    => 'em',
			'default'         => "1.5",
			'allowed_units'   => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
			'range_settings'  => array(
				'min'  => '1',
				'max'  => '45',
				'step' => '0.1',
			),
			'hover'           => 'tabs',
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;

	}

	protected function add_divider( $type ) {

	}

	protected function add_width( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = esc_html__( 'Width', 'et_builder' );
		}
		if ( empty( $default ) ) {
			$default = [ 'default' => '100', 'unit' => '%' ];
		}
		if ( ! isset( $default['range_settings'] ) ) {
			$default['range_settings'] = array(
				'min'  => '1',
				'max'  => '100',
				'step' => '1',
			);
		}
		if ( ! isset( $default['allowed_units'] ) ) {
			$default['allowed_units'] = array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' );
		}
		$key                          = $key . '_width';
		$this->modules_fields[ $key ] = array(
			'label'           => $label,
			'type'            => 'range',
			'option_category' => 'configuration',
			'c_type'          => 'width',
			'default'         => $this->get_default_data( $key, $default['default'] ),
			'default_unit'    => $default['unit'],
			'allowed_units'   => $default['allowed_units'],
			'range_settings'  => $default['range_settings'],
			'responsive'      => true,
			'mobile_options'  => true,
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}

	protected function add_min_width( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = esc_html__( 'Min Width', 'et_builder' );
		}
		if ( empty( $default ) ) {
			$default = [ 'default' => '100', 'unit' => '%' ];
		}
		if ( ! isset( $default['range_settings'] ) ) {
			$default['range_settings'] = array(
				'min'  => '1',
				'max'  => '100',
				'step' => '1',
			);
		}
		if ( ! isset( $default['allowed_units'] ) ) {
			$default['allowed_units'] = array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' );
		}
		$key                          = $key . '_width';
		$this->modules_fields[ $key ] = array(
			'label'            => $label,
			'type'             => 'range',
			'option_category'  => 'configuration',
			'c_type'           => 'min_width',
			'default'          => $this->get_default_data( $key, $default['default'] ),
			'default_unit'     => $default['unit'],
			'default_on_front' => '',
			'allowed_units'    => $default['allowed_units'],
			'range_settings'   => $default['range_settings'],
			'responsive'       => false,
			'mobile_options'   => false,
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}

	protected function add_controls_tabs( $tab_id, $label, $conditions = [] ) {

		$key                          = $this->get_unique_id();
		$this->modules_fields[ $key ] = array(
			'label'               => $label,
			'attr_suffix'         => '',
			'type'                => 'composite',
			'composite_type'      => 'default',
			'composite_structure' => array(),
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );

		return $key;
	}

	protected function add_controls_tab( $control_tabs_id, $label, $field_keys, $id = '' ) {
		$id = $this->get_unique_id();
		if ( isset( $this->modules_fields[ $control_tabs_id ] ) ) {
			$this->modules_fields[ $control_tabs_id ]['composite_structure'][ $id ]['label'] = $label;
			$controls                                                                        = [];
			foreach ( $field_keys as $field_key ) {
				if ( ! isset( $this->modules_fields[ $field_key ] ) ) {
					continue;
				}
				$controls[ $field_key ] = $this->modules_fields[ $field_key ];
				if ( count( $controls ) > 0 ) {
					$this->tab_array[ $field_key ] = $this->modules_fields[ $field_key ];
				}

				unset( $controls[ $field_key ]['toggle_slug'] );
				unset( $controls[ $field_key ]['tab_slug'] );
				unset( $this->modules_fields[ $field_key ] );

			}
			if ( count( $controls ) > 0 ) {

				$this->modules_fields[ $control_tabs_id ]['composite_structure'][ $id ]['controls'] = $controls;


			}
		}

		return [ $id, $control_tabs_id ];
	}

	protected function add_icon( $tab_id, $key, $label = '', $default = '', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_html__( 'Icon', 'et_builder' );
		}
		$this->modules_fields[ $key ] = array(
			'label'           => $label,
			'type'            => 'select_icon',
			'option_category' => 'configuration',
			'class'           => array( 'et-pb-font-icon' ),
			'mobile_options'  => false,
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
	}

	protected function set_selector( $tab_id, $key, $selector, $value = '' ) {
		if ( empty( $selector ) ) {
			return;
		}

		$selector                                 = is_array( $selector ) ? implode( ',', $selector ) : $selector;
		$type                                     = isset( $this->modules_fields[ $key ]['c_type'] ) ? $this->modules_fields[ $key ]['c_type'] : ( isset( $this->modules_fields[ $key ]['type'] ) ? $this->modules_fields[ $key ]['type'] : '' );
		$this->modules_fields[ $key ]['selector'] = $selector;

		$css_data                                 = $this->create_css_property( $key, $type );





		if ( empty( $css_data ) ) {
			return;
		}

		$property       = $css_data['property'];
		$property_value = $css_data['value'];

		if ( ! empty( $property_value ) ) {

			$this->style_selector[ $selector ][ $property ] = $property_value;
		}
	}


	protected function create_css_property( $key, $type ) {
		$property = [];
		$default  = isset( $this->modules_fields[ $key ]['default'] ) ? $this->modules_fields[ $key ]['default'] : '';


		switch ( $type ) {
			case  'text_align':
				$default  = isset( $this->modules_fields[ $key ]['default'] ) ? $this->modules_fields[ $key ]['default'] : ( is_rtl() ? 'right' : 'left' );
				$property = [ 'property' => 'text-align', 'value' => $default ];
				break;
			case  'letter_spacing':
				$property = [ 'property' => 'letter-spacing', 'value' => $default ];
				break;
			case  'line_height':
				$property = [ 'property' => 'line-height', 'value' => $default ];
				break;
			case  'width':
				$property = [ 'property' => 'width', 'value' => $default ];
				break;
			case  'margin':
				$property = [ 'property' => 'margin', 'value' => $default ];
				break;
			case  'padding':
				$property = [ 'property' => 'margin', 'value' => $default ];
				break;
			case  'border_radius':
				$property = [ 'property' => 'border-radius', 'value' => $default ];
				break;
			case  'border_color':
				$property = [ 'property' => 'border-color', 'value' => $default ];
				break;
			case  'background_color':
				$property = [ 'property' => 'background-color', 'value' => $default ];
				break;
			case  'color':
				$property = [ 'property' => 'color', 'value' => $default ];
				break;
			case  'color-alpha':
				$property = [ 'property' => 'color', 'value' => $default ];
				break;
			case  'font_size':
				$property = [ 'property' => 'font-size', 'value' => $default ];
				break;
			case  'box_shadow':
				$property = [ 'property' => 'box-shadow', 'value' => '' ];
				break;
			default:
				break;


		}


		return $property;
	}


	protected function get_class_options() {
		return [
			'wfacp-col-full'       => __( 'Full', 'woofunnels-aero-checkout' ),
			'wfacp-col-left-half'  => __( 'One Half', 'woofunnels-aero-checkout' ),
			'wfacp-col-left-third' => __( 'One Third', 'woofunnels-aero-checkout' ),
			'wfacp-col-two-third'  => __( 'Two Third', 'woofunnels-aero-checkout' ),
		];
	}

	protected function show_if( $key, $condition ) {
		$this->modules_fields[ $key ]['show_if'] = $condition;
	}

	protected function show_if_not( $key, $condition ) {
		$this->modules_fields[ $key ]['show_if_not'] = $condition;
	}

	protected function add_responsive_control( $key ) {
		if ( isset( $this->modules_fields[ $key ] ) ) {
			$this->modules_fields[ $key ]['mobile_options'] = true;

		}

	}


	protected function assign_tab( $key, $tab_id ) {


		if ( isset( $this->modules_fields[ $key ] ) ) {
			$this->modules_fields[ $key ]['toggle_slug'] = $tab_id;
			$this->modules_fields[ $key ]['tab_slug']    = $this->tabs[ $tab_id ]['type'];
		}
	}

	protected function assign_sub_tab( $key, $sub_tab_id ) {
		if ( isset( $this->modules_fields[ $key ] ) ) {
			$this->modules_fields[ $key ]['sub_toggle'] = $sub_tab_id;
		}
	}

	protected function add_tab( $name, $type, $id = '' ) {

		if ( $type == '5' ) {
			$type = 'general';
		} else if ( $type == '3' ) {
			$type = 'custom_css';
		} else if ( $type == '2' ) {
			$type = 'advanced';
		} else {
			$type = 'general';
		}
		//advanced

		if ( empty( $id ) ) {
			$id = $this->get_unique_id();
		}
		if ( isset( $this->settings_modal_toggles[ $type ] ) ) {
			$this->settings_modal_toggles[ $type ]['toggles'][ $id ] = [ 'title' => $name ];
		} else {
			$this->settings_modal_toggles[ $type ] = [ 'toggles' => [ $id => [ 'title' => $name ] ] ];
		}
		$this->tabs[ $id ] = [ 'type' => $type, 'name' => $name ];

		return $id;
	}

	protected function add_sub_tab( $name, $tab_id, $id = '' ) {
		if ( empty( $id ) ) {
			$id = $this->get_unique_id();
		}
		$tab  = $this->tabs[ $tab_id ];
		$type = $tab['type'];

		if ( isset( $this->settings_modal_toggles[ $type ]['toggles'][ $tab_id ] ) ) {
			$this->settings_modal_toggles[ $type ]['toggles'][ $tab_id ]['tabbed_subtoggles']   = true;
			$this->settings_modal_toggles[ $type ]['toggles'][ $tab_id ]['sub_toggles'] [ $id ] = [ 'name' => $name ];
		} else {
			$this->settings_modal_toggles[ $type ]['toggles'][ $tab_id ]['tabbed_subtoggles']   = true;
			$this->settings_modal_toggles[ $type ]['toggles'][ $tab_id ]['sub_toggles'] [ $id ] = [ 'name' => $name ];
		}

		$this->sub_tabs[ $id ] = [ 'type' => $type, 'id' => $id, 'name' => $name, 'tab_id' => $tab_id ];

		return $this->sub_tabs[ $id ];
	}

	protected function add_font_family( $tab_id, $key, $selectors = '', $label = '', $default = 'default', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_html__( 'Font Family', 'et_builder' );
		}

		$defaultVal = $this->get_default_data( $key, $default );


		if ( in_array( $key, $this->global_typography ) ) {
			$defaultVal = $default;

		}
		$this->typography[ $key . '_font_family' ] = $key;

		$this->modules_fields[ $key ] = array(
			'label'   => $label,
			'type'    => 'font',
			'default' => $defaultVal,
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}

	protected function _add_link_options_fields() {
		$this->_additional_fields_options = [];
	}

	public function get_fields() {


		return $this->modules_fields;
	}

	protected function get_unique_id() {
		static $count = 0;
		$count ++;
		$key = md5( 'wfacp_' . $count );

		return $key;
	}

	protected function get_name() {
		return $this->name;
	}

	protected function get_slug() {
		return $this->slug;
	}

	protected function get_id() {
		return $this->id;
	}

	protected function get_local_slug() {
		return $this->get_local_slug;
	}


	public function init() {
		if ( isset( $_REQUEST['et_post_id'] ) ) {
			$this->post_id = absint( $_REQUEST['et_post_id'] );
			if ( WFACP_Common::get_post_type_slug() == trim( $_REQUEST['et_post_type'] ) ) {
				$_REQUEST['wfacp_id'] = $this->post_id;
				WFACP_Common::set_id( $this->post_id );
				WFACP_Core()->template_loader->load_template( $this->post_id );
			}
		}

		$template = wfacp_template();
		if ( is_null( $template ) ) {
			return [];
		}

		$this->setup_data( $template );
	}


	protected function setup_data( $template ) {

	}

	private function get_default_data( $key, $default = '' ) {
		return $default;
	}
}