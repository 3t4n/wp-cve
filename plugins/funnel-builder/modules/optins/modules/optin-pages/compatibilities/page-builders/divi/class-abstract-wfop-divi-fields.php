<?php

#[AllowDynamicProperties]

 abstract class WFOP_Divi_Field extends ET_Builder_Module {
	protected $get_local_slug = '';
	protected $ajax = false;
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
	protected static $product_options = [];
	protected $module_credits = array(
		'module_uri' => '',
		'author'     => 'FunnelKit',
		'author_uri' => 'https://funnelkit.com/',
	);

	public function __construct() {
		$name       = get_class( $this );
		$this->slug = 'et_' . strtolower( $name );
		parent::__construct();
		if ( true === $this->ajax ) {
			add_action( 'wp_ajax_' . $this->slug, [ $this, 'render_ajax' ] );
		}
		$this->advanced_fields['margin_padding']['use_margin'] = false;
		$this->advanced_fields['margin_padding']['use_padding'] = false;

	}

	/**
	 * Widget Name
	 *
	 * @param $name
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}

	public function set_slug( $slug ) {
		$this->slug = 'wfop_' . $slug;
	}

	protected function color_type() {
		return WFOP_Divi_Extension::$field_color_type;
	}


	public function add_heading( $tab_id, $heading, $separator = '', $conditions = [] ) {
		$key                          = $this->get_unique_id();
		$this->modules_fields[ $key ] = array(
			'label'     => $heading,
			'type'      => 'text',
			'className' => 'wfop_heading_divi_builder',
			'default'   => ''
		);


		if ( is_array( $conditions ) && ! empty( $conditions ) ) {


			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );

		return $key;
	}

	public function add_subheading( $tab_id, $heading, $separator = '', $conditions = [] ) {
		$key                          = $this->get_unique_id();
		$this->modules_fields[ $key ] = array(
			'label'     => $heading,
			'type'      => 'text',
			'className' => 'wfop_subheading_divi_builder',
			'default'   => ''
		);


		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );

		return $key;
	}

	protected function add_switcher( $tab_id, $key, $label = '', $default = 'off', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = __( 'Enable', 'funnel-builder' );
		}
		$this->modules_fields[ $key ] = array(
			'label'           => $label,
			'type'            => 'yes_no_button',
			'default'         => $default,
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

	protected function add_select( $tab_id, $key, $label, $options, $field_default_cls, $conditions = [] ) {
		$this->modules_fields[ $key ] = array(
			'label'   => $label,
			'type'    => 'select',
			'options' => $options,
			'default' => $field_default_cls
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );

		return $key;
	}

	public function add_text( $tab_id, $key, $label, $default = '', $conditions = [], $description = '', $placeholder = '', $classes = '' ) {

		$this->modules_fields[ $key ] = array(
			'label'     => $label,
			'type'      => 'text',
			'default'   => $default,
			'className' => 'wfop_divi_text',
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
			'default' => $default,
		];

		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );

		return $key;
	}

	protected function add_typography( $tab_id, $key, $selectors = '', $label = '', $default = '#333333', $conditions = [], $font_side_default = [] ) {

		$keys         = [];
		$default_typo = '';
		if ( ! empty( $default ) ) {

			$default_typo = $default;

		}

		$keys[] = $this->add_font_size( $tab_id, $key, $selectors, '', '', $conditions, $font_side_default );
		$keys[] = $this->add_font( $tab_id, $key, $selectors, '', $default_typo, $conditions );
		$keys[] = $this->add_line_height( $tab_id, $key, $selectors, '', '1px', $conditions );

		return $keys;
	}

	protected function add_text_alignments( $tab_id, $key, $selectors = '', $label = '', $default = 'left', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_html__( 'Alignment', 'et_builder' );
		}
		$options=et_builder_get_text_orientation_options();

		if((is_array($options) && count($options) > 0) && isset($options['justified'])){
			unset($options['justified']);

			$options['justify']='justify';
		}

		$this->modules_fields[ $key ] = array(
			'label'          => $label,
			'type'           => 'text_align',
			'mobile_options' => true,
			'default'        => $default,
			'options'        => $options,
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
			$default = [ 'default' => '16px', 'unit' => 'px' ];
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
		$key                                     = $key . '_font_size';
		$this->modules_fields[ $key ]            = array(
			'label'           => $label,
			'type'            => 'range',
			'option_category' => 'font_option',
			'c_type'          => 'font_size',
			'default'         => $default['default'],
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


	protected function add_letter_spacing( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = esc_html__( 'Letter Spacing', 'et_builder' );
		}
		if ( empty( $default ) ) {
			$default = [ 'default' => '0.9px', 'unit' => 'px' ];
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
			'default'         => $default['default'],
			'default_unit'    => $default['unit'],

			'allowed_units'  => $default['allowed_units'],
			'range_settings' => $default['range_settings'],
			'responsive'     => true,
			'mobile_options' => true,
		];
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}

	protected function add_font( $tab_id, $key, $selectors = '', $label = '', $default = '#000000', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_html__( 'Select Font', 'et_builder' );
		}
		$key = $key . '_typograhy';

		$this->modules_fields[ $key ] = array(
			'label'   => $label,
			'type'    => 'font',
			'default' => $default
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}

	protected function add_color( $tab_id, $key, $selectors = '', $label = 'Color', $default = '#000000', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = 'Color';
		}
		$this->modules_fields[ $key ] = array(
			'label'        => $label,
			'type'         => $this->color_type(),
			'custom_color' => true,
			'default'      => $default,
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
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
			'default'      => $default,
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
		$color_selectors = [];
		if ( is_array( $selectors ) && count( $selectors ) > 0 ) {
			foreach ( $selectors as $selector ) {
				$color_selectors[ $selector ] = $selectors;
			}
		}

		$this->modules_fields[ $key ] = array(
			'label'        => $label,
			'type'         => $this->color_type(),
			'c_type'       => 'border_color',
			'custom_color' => true,
			'default'      => $default,
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
			'default'          => $default['default'],
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
			'default'         => $default,
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
			'default'         => $default,
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


	protected function add_border( $tab_id, $key, $selectors, $conditions = [], $default = [], $fields_options = [] ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable


		$border_option = [
			'none'   => __( 'None', 'funnel-builder' ),
			'solid'  => __( 'Solid', 'funnel-builder' ),
			'double' => __( 'Double', 'funnel-builder' ),
			'dotted' => __( 'Dotted', 'funnel-builder' ),
			'dashed' => __( 'Dashed', 'funnel-builder' ),
			'groove' => __( 'Groove', 'funnel-builder' ),
		];

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

		$fields_keys = [];
		$default     = wp_parse_args( $default, $default_args );


		$wfop_start_border                          = $this->get_unique_id();
		$this->modules_fields[ $wfop_start_border ] = array(
			'label'     => 'start border',
			'type'      => 'hidden',
			'c_type'    => 'wfop_start_border',
			'field_key' => $key,
			'selector'  => $selectors
		);

		$fields_keys[]  = $this->add_subheading( $tab_id, __( 'Border', 'funnel-builder', '', $conditions ) );
		$border_type    = $this->add_select( $tab_id, $key . '_border_type', __( 'Type', 'funnel-builder' ), $border_option, $default['border_type'] );
		$fields_keys[]  = $border_type;
		$type_condition = [ $border_type => [ 'solid', 'double', 'dotted', 'dashed', 'groove' ] ];
		if ( ! empty( $conditions ) ) {
			$type_condition = array_merge( $type_condition, $conditions );
		}


		$fields_keys[] = $this->add_subheading( $tab_id, __( 'Width', 'funnel-builder' ), '', $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_width_top', __( 'Top', 'funnel-builder' ), $default['border_width_top'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_width_bottom', __( 'Bottom', 'funnel-builder' ), $default['border_width_bottom'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_width_left', __( 'Left', 'funnel-builder' ), $default['border_width_left'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_width_right', __( 'Right', 'funnel-builder' ), $default['border_width_right'], $type_condition );
		$fields_keys[] = $this->add_color( $tab_id, $key . '_border_color', $selectors, '', $default['border_color'], $type_condition );

		$fields_keys[] = $this->add_subheading( $tab_id, __( 'Border Radius', 'funnel-builder' ), '', $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_radius_top', __( 'Top', 'funnel-builder' ), $default['border_radius_top'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_radius_bottom', __( 'Bottom', 'funnel-builder' ), $default['border_radius_bottom'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_radius_left', __( 'Left', 'funnel-builder' ), $default['border_radius_left'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_border_radius_right', __( 'Right', 'funnel-builder' ), $default['border_radius_right'], $type_condition );


		$wfop_end_border                          = $this->get_unique_id();
		$this->modules_fields[ $wfop_end_border ] = array(
			'label'     => 'end border',
			'type'      => 'hidden',
			'c_type'    => 'wfop_end_border',
			'field_key' => $key
		);

		$this->add_class( $key . '_border_width_top', 'wfop_divi_border_width_start wfop_border_width_top' );
		$this->add_class( $key . '_border_width_bottom', 'wfop_border_width_bottom' );
		$this->add_class( $key . '_border_width_left', 'wfop_border_width_left' );
		$this->add_class( $key . '_border_width_right', 'wfop_divi_border_width_start wfop_border_width_right' );

		$this->add_class( $key . '_border_radius_top', 'wfop_divi_border_width_start wfop_border_width_top' );
		$this->add_class( $key . '_border_radius_bottom', 'wfop_border_width_bottom' );
		$this->add_class( $key . '_border_radius_left', 'wfop_border_width_left' );
		$this->add_class( $key . '_border_radius_right', 'wfop_divi_border_width_end wfop_border_width_right' );


		return $fields_keys;
	}

	protected function add_box_shadow( $tab_id, $key, $selectors, $label = '', $default = [], $conditions = [] ) {


		$border_option = [
			''      => __( 'Outline', 'funnel-builder' ),
			'inset' => __( 'Inset', 'funnel-builder' ),
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


		$fields_keys                                = [];
		$default                                    = wp_parse_args( $default, $default_args );
		$wfop_start_border                          = $this->get_unique_id();
		$this->modules_fields[ $wfop_start_border ] = array(
			'label'     => 'start box shadow',
			'type'      => 'hidden',
			'c_type'    => 'wfop_start_box_shadow',
			'field_key' => $key,
			'selector'  => $selectors
		);

		if ( empty( $label ) ) {
			$label = esc_html__( 'Box Shadow', 'et_builder' );
		}

		$fields_keys[] = $this->add_heading( $tab_id, $label, '', $conditions );
		$enabled       = $this->add_switcher( $tab_id, $key . '_shadow_enable', __( 'Enable', 'funnel-builder' ), $default['enable'], $conditions );

		$type_condition = [ $enabled => 'on' ];
		if ( ! empty( $conditions ) ) {
			$type_condition = array_merge( $type_condition, $conditions );
		}
		$fields_keys[] = $this->add_select( $tab_id, $key . '_shadow_type', __( 'Position', 'funnel-builder' ), $border_option, $default['type'], $type_condition );
		$fields_keys[] = $this->add_color( $tab_id, $key . '_shadow_color', $selectors, '', $default['color'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_shadow_horizontal', __( 'Horizontal', 'funnel-builder' ), $default['horizontal'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_shadow_vertical', __( 'Vertical', 'funnel-builder' ), $default['vertical'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_shadow_blur', __( 'Blur', 'funnel-builder' ), $default['blur'], $type_condition );
		$fields_keys[] = $this->add_text( $tab_id, $key . '_shadow_spread', __( 'Spread', 'funnel-builder' ), $default['spread'], $type_condition );


		$wfop_end_border                          = $this->get_unique_id();
		$this->modules_fields[ $wfop_end_border ] = array(
			'label'     => 'end border',
			'type'      => 'hidden',
			'c_type'    => 'wfop_end_box_shadow',
			'field_key' => $key
		);

		$this->add_class( $key . '_shadow_horizontal', 'wfop_divi_border_width_start wfop_border_width_top' );
		$this->add_class( $key . '_shadow_vertical', 'wfop_border_width_bottom' );
		$this->add_class( $key . '_shadow_blur', 'wfop_border_width_left' );
		$this->add_class( $key . '_shadow_spread', 'wfop_divi_border_width_start wfop_border_width_right' );

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

	protected function add_divider( $type ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable

	}

	protected function add_width( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [], $responsive = false ) {

		if ( empty( $label ) ) {
			$label = esc_html__( 'Width', 'funnel-builder' );
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
			'default'         => $default['default'],
			'default_unit'    => $default['unit'],
			'allowed_units'   => $default['allowed_units'],
			'range_settings'  => $default['range_settings'],
			'responsive'      => $responsive,
			'mobile_options'  => $responsive,
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}

	protected function add_max_width( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [], $responsive = false ) {

		if ( empty( $label ) ) {
			$label = esc_html__( 'Width', 'funnel-builder' );
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
			'c_type'          => 'max-width',
			'default'         => $default['default'],
			'default_unit'    => $default['unit'],
			'allowed_units'   => $default['allowed_units'],
			'range_settings'  => $default['range_settings'],
			'responsive'      => $responsive,
			'mobile_options'  => $responsive,
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}


	protected function add_height( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = esc_html__( 'Height', 'funnel-builder' );
		}
		if ( empty( $default ) ) {
			$default = [ 'default' => '100', 'unit' => 'px' ];
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
		$key                          = $key . '_height';
		$this->modules_fields[ $key ] = array(
			'label'            => $label,
			'type'             => 'range',
			'option_category'  => 'configuration',
			'c_type'           => 'height',
			'default'          => $default['default'],
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

	protected function add_min_width( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = esc_html__( 'Min Width', 'funnel-builder' );
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
			'default'          => $default['default'],
			'default_unit'     => $default['unit'],
			'default_on_front' => '',
			'allowed_units'    => $default['allowed_units'],
			'range_settings'   => $default['range_settings'],
			'responsive'       => false,
			'mobile_options'   => true,
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

	public function add_animation_fields( $tab_id, $key, $selectors = '', $label = 'Animation Style', $default = 'none', $conditions = [] ) {


		$this->modules_fields[ $key ] = array(
			'label'           => $label,
			'type'            => 'select_animation',
			'option_category' => 'configuration',
			'default'         => $default,
			'description'     => esc_html__( 'Pick an animation style to enable animations for this element. Once enabled, you will be able to customize your animation style further. To disable animations, choose the None option.', 'funnel-builder' ),
			'options'         => array(
				'none'   => esc_html__( 'None', 'funnel-builder' ),
				'fade'   => esc_html__( 'Fade', 'funnel-builder' ),
				'slide'  => esc_html__( 'Slide', 'funnel-builder' ),
				'bounce' => esc_html__( 'Bounce', 'funnel-builder' ),
				'zoom'   => esc_html__( 'Zoom', 'funnel-builder' ),
				'flip'   => esc_html__( 'Flip', 'funnel-builder' ),
				'fold'   => esc_html__( 'Fold', 'funnel-builder' ),
				'roll'   => esc_html__( 'Roll', 'funnel-builder' ),
			),
		);
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$this->show_if( $key, $conditions );
		}
		$this->assign_tab( $key, $tab_id );
		$this->set_selector( $tab_id, $key, $selectors );

		return $key;
	}

	protected function add_icon( $tab_id, $key, $label = '', $default = '', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_html__( 'Icon', 'funnel-builder' );
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

	protected function set_selector( $tab_id, $key, $selector, $value = '' ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		if ( empty( $selector ) ) {
			return;
		}
		$selector = is_array( $selector ) ? implode( ',', $selector ) : $selector;

		$selector = str_replace( '%%order_class%%', '%%order_class%% #' . $this->slug, $selector );

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
			case  'max-width':
				$property = [ 'property' => 'max-width', 'value' => $default ];
				break;
			case  'height':
				$property = [ 'property' => 'height', 'value' => $default ];
				break;
			case  'min_width':
				$property = [ 'property' => 'min-width', 'value' => $default ];
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
			'wfop-col-full'       => __( 'Full', 'funnel-builder' ),
			'wfop-col-left-half'  => __( 'One Half', 'funnel-builder' ),
			'wfop-col-left-third' => __( 'One Third', 'funnel-builder' ),
			'wfop-col-two-third'  => __( 'Two Third', 'funnel-builder' ),
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


	public function get_complete_fields() {

		add_filter( 'et_builder_module_general_fields', '__return_empty_array' );
		$fields = parent::get_complete_fields();
		remove_filter( 'et_builder_module_general_fields', '__return_empty_array' );

		return $fields;
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

		if ( $type === 5 ) {
			$type = 'general';
		} else if ( $type === 3 ) {
			$type = 'custom_css';
		} else if ( $type === 2 ) {
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

	protected function _add_link_options_fields() {
		$this->_additional_fields_options = [];
	}

	public function get_fields() {


		return $this->modules_fields;
	}

	protected function get_unique_id() {
		static $count = 0;
		$count ++;
		$key = md5( 'wfop_' . $count );

		return $key;
	}

	protected function get_name() {
		return $this->name;
	}

	protected function get_slug() {
		return $this->slug;
	}

	protected function get_local_slug() {
		return $this->get_local_slug;
	}


	public function init() {

		global $post;
		$post_type = WFOPP_Core()->optin_pages->get_post_type_slug();

		if ( wp_doing_ajax() ) {
			if ( isset( $_REQUEST['action'] ) && "et_fb_get_saved_templates" === $_REQUEST['action'] && isset( $_REQUEST['et_post_type'] ) && $post_type !== $_REQUEST['et_post_type'] ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}
			if ( isset( $_REQUEST['action'] ) && "et_fb_update_builder_assets" === $_REQUEST['action'] && isset( $_REQUEST['et_post_type'] ) && $post_type !== $_REQUEST['et_post_type'] ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}
			$post_id = 0;
			if ( isset( $_REQUEST['action'] ) && "heartbeat" === $_REQUEST['action'] && isset( $_REQUEST['data'] ) ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( isset( $_REQUEST['data']['et'] ) ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$post_id = $_REQUEST['data']['et']['post_id']; //phpcs:ignore
				}
			}
			if ( isset( $_REQUEST['post_id'] ) ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['post_id'] );  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( isset( $_REQUEST['et_post_id'] ) ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['et_post_id'] );  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( $post_id > 0 ) {
				$post = get_post( $post_id );//phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}
		}

		if ( ! is_null( $post ) && $post->post_type !== $post_type ) {
			return;
		}

		$this->setup_offer();
		$this->setup_data();

	}

	public function render_ajax() {

		$this->props = $_REQUEST; //phpcs:ignore
		echo "<div id='{$this->slug}'>" . $this->html( [] ) . '</div>';//phpcs:ignore
		exit;
	}

	private function setup_offer() {
		if ( empty( self::$product_options ) ) {
			self::$product_options = array( '0' => __( '--No Product--', 'funnel-builder' ) );;

		}
	}

	protected function setup_data() {
	}
}