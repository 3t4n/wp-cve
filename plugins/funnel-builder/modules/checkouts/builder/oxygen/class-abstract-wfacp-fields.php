<?php

#[AllowDynamicProperties]

 abstract class WFACP_OXY_Field extends OxyEl {
	protected $get_local_slug = '';
	protected $name = '';
	protected $media_settings = [];
	public $slug = 'wfacp_checkout_form_summary';
	protected $id = 'wfacp_order_summary_widget';
	protected $ajax_session_settings = [];
	protected $settings = [];
	protected $post_id = 0;
	protected $tabs = [];
	protected $sub_tabs = [];
	protected $html_fields = [];
	private $add_tab_number = 1;

	static $css_build = false;
	protected $style_box = null;

	public function __construct() {
		parent::__construct();
		add_filter( "oxy_component_css_styles", array( $this, "generate_id_css" ), 10, 5 );
	}

	public function generate_id_css( $styles, $states, $selector, $class_obj, $defaults ) {//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
		return $styles;
	}

	public function init() {
		$this->El->useAJAXControls();
	}    /*
	  * used by OxyEl class to show the element button in a specific section/subsection
	  * @returns {string}
	  */
	public function button_place() {
		return 'woofunnels::woofunnels';
	}

	protected function add_tab( $title = '' ) {
		if ( empty( $title ) ) {
			$title = $this->get_title();
		}
		$field_key = 'wfacp_' . $this->add_tab_number . "_tab";
		$control   = $this->addControlSection( $field_key, $title, "assets/icon.png", $this );
		$this->add_tab_number ++;

		return $control;
	}

	public function add_heading( $control, $heading, $separator = '', $conditions = [] ) {
		$key            = $this->get_unique_id();
		$custom_control = $control->addCustomControl( __( '<div class="oxygen-option-default"  style="color: #fff; line-height: 1.3; font-size: 15px;font-weight: 900;    text-transform: uppercase;    text-decoration: underline;">' . $heading . '</div>' ), 'description' );
		$custom_control->setParam( $key, '' );
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition_string = $this->get_condition_string( $key, $conditions );
			if ( '' !== $condition_string ) {
				$custom_control->setCondition( $condition_string );
			}
		}

		return $custom_control;
	}

	public function add_sub_heading( $control, $heading, $separator = '', $conditions = [] ) {
		$key            = $this->get_unique_id();
		$custom_control = $control->addCustomControl( __( '<div class="oxygen-option-default"  style="color: #fff; line-height: 1.3; font-size: 13px;font-weight: 600;    text-transform: uppercase;    text-decoration: underline;">' . $heading . '</div>' ), 'description' );
		$custom_control->setParam( $key, '' );
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition_string = $this->get_condition_string( $key, $conditions );
			if ( '' !== $condition_string ) {
				$custom_control->setCondition( $condition_string );
			}
		}

		return $custom_control;
	}

	protected function add_switcher( $control, $key, $label = '', $default = 'on', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = __( 'Enable', 'woofunnels-aero-checkout' );
		}
		$input = [
			"type"    => "radio",
			"name"    => $label,
			"slug"    => $key,
			"value"   => [ 'on' => __( "Yes" ), "off" => __( 'No' ) ],
			"default" => $default,
			"css"     => false,
		];


		$condition_string = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition_string = $this->get_condition_string( $key, $conditions );
		}


		if ( '' !== $condition_string ) {
			$input['condition'] = $condition_string;
		}

		$ctrl = $control->addOptionControl( $input );
		$ctrl->rebuildElementOnChange();
		$ctrl->whiteList();

		return $key;
	}

	protected function add_icon( $control, $key, $label = 'Icon', $default = '', $conditions = [], $selector = '' ) {

		$input = [
			'type'    => 'icon_finder',
			'name'    => $label,
			'slug'    => $key,
			'default' => $default
		];
		if ( ! empty( $selector ) ) {
			$input['selector'] = $selector;

		}
		$condition_string = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition_string = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition_string ) {
			$input['condition'] = $condition_string;
		}
		$control->addOptionControl( $input )->rebuildElementOnChange();


		return $key;
	}

	protected function add_select( $control, $key, $label, $options, $default, $conditions = [] ) {

		$input            = [
			'type'    => 'dropdown',
			'name'    => $label,
			'slug'    => $key,
			'value'   => $options,
			'default' => $default
		];
		$condition_string = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition_string = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition_string ) {
			$input['condition'] = $condition_string;
		}
		$control->addOptionControl( $input )->rebuildElementOnChange();


		return $key;
	}

	public function add_text( $control, $key, $label, $default = '', $conditions = [], $description = '', $placeholder = '' ) {

		$input = array(
			'name'        => $label,
			'slug'        => $key,
			'type'        => 'textfield',
			'default'     => $default,
			'placeholder' => $placeholder,
		);


		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$control->addOptionControl( $input )->rebuildElementOnChange();

		return $key;
	}

	protected function add_textArea( $control, $key, $label, $default = '', $conditions = [] ) {
		$input = array(
			'name'    => $label,
			'slug'    => $key,
			'type'    => 'textarea',
			'default' => $default
		);


		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$control->addOptionControl( $input )->rebuildElementOnChange();

		return $key;
	}

	protected function add_typography( $control, $key, $selectors = '', $label = '' ) {

		if ( empty( $label ) ) {
			$label = __( 'Typography', 'woofunnels-aero-checkout' );
		}
		$typo = $control->typographySection( $label, $selectors, $this );


		return $typo;
	}

	protected function add_font( $tab_id, $key, $selectors = '', $label = 'Color', $default = '', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = 'Font Family';
		}

		$input     = array(
			"name"     => $label,
			"slug"     => $key,
			"selector" => $selectors,
			"property" => 'font-family',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	protected function add_color( $tab_id, $key, $selectors = '', $label = 'Color', $default = '#000000', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = 'Color';
		}

		$input     = array(
			"name"     => $label,
			"slug"     => $key,
			"selector" => $selectors,
			"property" => 'color',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	protected function add_background_color( $tab_id, $key, $selectors = [], $default = '#000000', $label = '', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Background', 'woofunnles-aero-checkout' );
		}


		$input     = array(
			"name"     => $label,
			"selector" => $selectors,
			"slug"     => $key,
			'default'  => $default,
			"property" => 'background-color',
		);


		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	protected function add_border_color( $tab_id, $key, $selectors = [], $default = '#000000', $label = '', $box_shadow = false, $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Border Color', 'woofunnles-aero-checkout' );
		}

		$input = array(
			"name"     => $label,
			"selector" => $selectors,
			"slug"     => $key,
			'default'  => $default,
			"property" => 'border-color',

		);

		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}

		$tab_id->addStyleControls( [ $input ] );

		return $key;
	}

	public function custom_typography( $tab_id, $key, $selector, $label = '', $default = [], $tab_condition = [] ) {


		$font_family    = '';
		$font_size      = '';
		$font_weight    = '';
		$line_height    = '';
		$letter_spacing = '';
		$transform      = '';
		$decoration     = '';

		if ( is_array( $default ) && count( $default ) > 0 ) {

			if ( isset( $default['font_family'] ) && ! empty( $default['font_family'] ) ) {
				$font_family = $default['font_family'];
			}

			if ( isset( $default['font_size'] ) && ! empty( $default['font_size'] ) ) {
				$font_size = $default['font_size'];
			}

			if ( isset( $default['font_weight'] ) && ! empty( $default['font_weight'] ) ) {
				$font_weight = $default['font_weight'];
			}

			if ( isset( $default['line_height'] ) && ! empty( $default['line_height'] ) ) {
				$line_height = $default['line_height'];
			}


			if ( isset( $default['letter_spacing'] ) && ! empty( $default['letter_spacing'] ) ) {
				$letter_spacing = $default['letter_spacing'];
			}

			if ( isset( $default['transform'] ) && ! empty( $default['transform'] ) ) {
				$transform = $default['transform'];
			}

			if ( isset( $default['decoration'] ) && ! empty( $default['decoration'] ) ) {
				$decoration = $default['decoration'];
			}

		}
		$this->add_font_family( $tab_id, $key . '_font_family', $selector, "", $font_family, $tab_condition );
		$this->add_font_size( $tab_id, $key . '_font_size', $selector, "", $font_size, $tab_condition );
		$this->add_font_weight( $tab_id, $key . '_font_weight', $selector, "", $font_weight, $tab_condition );
		$this->add_line_height( $tab_id, $key . '_line_height', $selector, "", $line_height, $tab_condition );
		$this->add_letter_spacing( $tab_id, $key . '_letter_spacing', $selector, "", $letter_spacing, $tab_condition );
		$this->add_text_transform( $tab_id, $key . '_transform', $selector, "", $transform, $tab_condition );
		$this->add_text_decoration( $tab_id, $key . '_decoration', $selector, "", $decoration, $tab_condition );
	}

	/* Typography Fields  Start*/

	protected function add_font_family( $tab_id, $key, $selectors = '', $label = 'Font Family', $default = 'Inherit', $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = __( 'Font Family', 'woofunnels-upstroke-one-click-upsell' );
		}

		if ( empty( $default ) ) {
			$default = 'inherit';
		}


		$input     = array(
			"name"        => $label,
			"slug"        => $key,
			"selector"    => $selectors,
			"param_name"  => "font-family",
			"param_value" => $default,
			"default"     => $default,
			"property"    => 'font-family',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	protected function add_font_size( $tab_id, $key, $selectors = '', $label = 'Color', $default = '', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = __( 'Font Size', 'woofunnels-upstroke-one-click-upsell' );
		}

		if ( empty( $default ) ) {
			$default = '16';
		}
		$input     = array(
			"name"     => $label,
			"slug"     => $key,
			"selector" => $selectors,
			"default"  => $default,
			"property" => 'font-size',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	protected function add_font_weight( $tab_id, $key, $selectors = '', $label = 'Font Weight', $default = 'noormal', $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = __( 'Font Weight', 'woofunnels-upstroke-one-click-upsell' );
		}
		if ( empty( $default ) ) {
			$default = '400';
		}
		$input     = array(
			"name"     => $label,
			"slug"     => $key,
			"selector" => $selectors,
			"default"  => $default,
			"property" => 'font-weight',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	protected function add_line_height( $tab_id, $key, $selectors = '', $label = 'Line Height', $default = '1.5', $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = __( 'Line Height', 'woofunnels-upstroke-one-click-upsell' );
		}
		if ( empty( $default ) ) {
			$default = '1.5';
		}
		$input     = array(
			"name"     => $label,
			"slug"     => $key,
			"selector" => $selectors,
			"default"  => $default,
			"property" => 'line-height',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	protected function add_letter_spacing( $tab_id, $key, $selectors = '', $label = 'Letter Spacing', $default = '1', $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = __( 'Letter Spacing', 'woofunnels-upstroke-one-click-upsell' );
		}

		if ( empty( $default ) ) {
			$default = '0';
		}

		$input     = array(
			"name"     => $label,
			"slug"     => $key,
			"selector" => $selectors,
			"default"  => $default,
			"property" => 'letter-spacing',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	protected function add_text_transform( $tab_id, $key, $selectors = '', $label = 'Text Transform', $default = 'none', $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = __( 'Text Transform', 'woofunnels-upstroke-one-click-upsell' );
		}
		if ( empty( $default ) ) {
			$default = 'none';
		}
		$input     = array(
			"name"     => $label,
			"slug"     => $key,
			"selector" => $selectors,
			"default"  => $default,
			"property" => 'text-transform',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	protected function add_text_decoration( $tab_id, $key, $selectors = '', $label = 'Text Decoration', $default = 'none', $conditions = [] ) {

		if ( empty( $label ) ) {
			$label = __( 'Text Decoration', 'woofunnels-upstroke-one-click-upsell' );
		}
		if ( empty( $default ) ) {
			$default = 'none';
		}
		$input     = array(
			"name"     => $label,
			"slug"     => $key,
			"selector" => $selectors,
			"default"  => $default,
			"property" => 'text-decoration',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	protected function add_text_alignments( $tab_id, $key, $selectors = '', $label = '', $default = 'left', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = __( 'Alignment', 'woofunnels-upstroke-one-click-upsell' );
		}


		if ( empty( $default ) ) {
			$default = 'left';
		}

		$items_align = $tab_id->addControl( "buttons-list", $key, $label );

		$items_align->setValue( array(
			"left"   => "Left",
			"center" => "Center",
			"right"  => "Right"
		) );
		$items_align->setDefaultValue( $default );
		$items_align->setValueCSS( array(
			"left"   => "
                $selectors{
                    text-align: left;
                }
            ",
			"center" => "
				$selectors{
                    text-align: center;
                }
            ",
			"right"  => "
               $selectors{
                    text-align: right;
                }
            ",
		) );
		$items_align->whiteList();
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition_string = $this->get_condition_string( $key, $conditions );
			if ( '' !== $condition_string ) {
				$items_align->setCondition( $condition_string );
			}
		}

		return $key;
	}

	/* Typography Fields End*/


	protected function add_border_radius( $tab_id, $key, $selector, $conditions = [], $default = [] ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Border Radius', 'woofunnles-aero-checkout' );
		}

		$input = array(
			"name"     => $label,
			"selector" => $selector,
			"slug"     => $key,
			'default'  => $default,
			"property" => 'border-radius',
		);

		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}

		$tab_id->addStyleControls( [ $input ], 'border-radius' );

		return $key;
	}

	protected function add_border_radius_preset( $tab_id, $key, $selector, $label = '' ) {
		if ( empty( $label ) ) {
			$label = esc_html__( 'Border Radius', 'woofunnels-aero-checkout' );
		}

		$tab_id->addPreset( "border-radius", $key, $label, $selector )->whiteList();


		return $key;
	}

	protected function add_padding( $tab_id, $key, $selector, $label = '' ) {
		if ( empty( $label ) ) {
			$label = esc_html__( 'Padding', 'woofunnels-aero-checkout' );
		}
		$tab_id->addPreset( "padding", $key, $label, $selector )->whiteList();


		return $key;
	}

	protected function add_margin( $tab_id, $key, $selector, $label = '' ) {
		if ( empty( $label ) ) {
			$label = esc_html__( 'Margin', 'woofunnels-aero-checkout' );
		}

		$tab_id->addPreset( "margin", $key, $label, $selector )->whiteList();


		return $key;
	}


	protected function add_border( $tab_id, $key, $selectors, $label = '' ) {
		if ( empty( $label ) ) {
			$label = __( "Border" );
		}
		$tab_id->borderSection( $label, $selectors, $this );


		return $key;
	}

	protected function add_only_border_radius( $tab_id, $key, $selector, $name = '' ) {
		if ( empty( $name ) ) {
			$name = __( "Border Radius" );
		}

		$borderRadiusPreset = $tab_id->addPreset( "border-radius", $key, __( $name . " Border Radius" ) );
		$borderRadiusPreset->whiteList();
		$borderSelector = $this->El->registerCSSSelector( $selector );

		$borderSelector->mapPreset( 'border-radius', $key );


		return $key;

	}

	protected function add_box_shadow( $tab_id, $key, $selector, $label = '' ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Border Shadow', 'woofunnles-aero-checkout' );
		}

		$tab_id->boxShadowSection( $label, $selector, $this );

		return $key;
	}

	protected function add_divider( $control, $type ) {//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
		$key = $this->get_unique_id();
		$control->addCustomControl( __( '<hr class="oxygen-option-default" style="color: #fff" />' ), 'description' )->setParam( $key, '' );

		return $key;
	}

	protected function range( $tab_id, $key, $label = '', $selectors = '', $property = 'transition-duration', $default = [], $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = __( 'Transition Duration' );
		}

		$input     = array(
			"name"         => __( 'Transition Duration' ),
			"selector"     => $selectors,
			"slug"         => $key,
			"property"     => $property,
			"control_type" => 'slider-measurebox',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}

		$transition = $tab_id->addStyleControl( [ $input ] );
		$transition->setUnits( 's', 's' );
		$transition->setRange( 0, 1, 0.1 );

	}

	protected function add_width( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Width', 'woofunnles-aero-checkout' );
		}
		$input     = array(
			"name"     => $label,
			"selector" => $selectors,
			"slug"     => $key,
			'default'  => isset( $default['default'] ) ? $default['default'] : '',
			"property" => 'width',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	protected function slider_measure_box( $tab_id, $key, $selectors, $label, $default, $conditions = [], $property = "margin-bottom" ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Icon Font Size', 'funnel-builder' );
		}


		$input     = array(
			"name"         => $label,
			"selector"     => $selectors,
			"slug"         => $key,
			'default'      => $default,
			"property"     => $property,
			"control_type" => 'slider-measurebox',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	protected function add_min_width( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Min Width', 'woofunnles-aero-checkout' );
		}
		$input     = array(
			"name"     => $label,
			"selector" => $selectors,
			"slug"     => $key,
			'default'  => isset( $default['default'] ) ? $default['default'] : '',
			"property" => 'min-width',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$c = $tab_id->addStyleControls( [ $input ] );
		$c->setUnits( isset( $default['unit'] ) ? $default['unit'] : 'px', "px,em,%" );


		return $key;
	}

	protected function add_height( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Height', 'funnel-builder' );
		}
		$input     = array(
			"name"     => $label,
			"selector" => $selectors,
			"slug"     => $key,
			'default'  => isset( $default['default'] ) ? $default['default'] : '',
			"property" => 'height',
		);
		$condition = '';
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition = $this->get_condition_string( $key, $conditions );
		}
		if ( '' !== $condition ) {
			$input['condition'] = $condition;
		}
		$tab_id->addStyleControls( [ $input ] );


		return $key;
	}

	public function add_wrapper( $control, $conditions = [] ) {
		$key            = $this->get_unique_id();
		$custom_control = $control->addCustomControl( __( '<div class="oxygen-option-default"  style="color: #fff; line-height: 1.3; font-size: 15px;font-weight: 900;    text-transform: uppercase;    text-decoration: underline;">' ), 'description' );
		$custom_control->setParam( $key, '' );
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition_string = $this->get_condition_string( $key, $conditions );
			if ( '' !== $condition_string ) {
				$custom_control->setCondition( $condition_string );
			}
		}

		return $custom_control;
	}

	public function close_wrapper( $control, $conditions = [] ) {
		$key            = $this->get_unique_id();
		$custom_control = $control->addCustomControl( __( '</div>' ), 'description' );
		$custom_control->setParam( $key, '' );
		if ( is_array( $conditions ) && ! empty( $conditions ) ) {
			$condition_string = $this->get_condition_string( $key, $conditions );
			if ( '' !== $condition_string ) {
				$custom_control->setCondition( $condition_string );
			}
		}

		return $custom_control;
	}

	protected function get_class_options() {
		return [
			'wfacp-col-full'       => __( 'Full', 'woofunnels-aero-checkout' ),
			'wfacp-col-left-half'  => __( 'One Half', 'woofunnels-aero-checkout' ),
			'wfacp-col-left-third' => __( 'One Third', 'woofunnels-aero-checkout' ),
			'wfacp-col-two-third'  => __( 'Two Third', 'woofunnels-aero-checkout' ),
		];
	}

	protected function get_condition_string( $key, $condition ) {

		if ( empty( $condition ) ) {
			return '';
		}

		$output = [];
		foreach ( $condition as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = implode( ',', $value );
			}
			$output[] = $key . '=' . $value;
		}

		return implode( '&&', $output );
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

	public function slug() {
		return $this->slug;

	}

	protected function get_id() {
		return $this->id;
	}

	protected function get_local_slug() {
		return $this->get_local_slug;
	}

	public function controls() {
		$this->process_checkout();
	}

	private function process_checkout() {
		$template = wfacp_template();

		if ( is_null( $template ) ) {
			return [];
		}
		global $post;

		if ( ! is_null( $post ) && $post->post_type !== WFACP_Common::get_post_type_slug() ) {
			return [];
		}

		$run_setup = true;

		if ( isset( $_GET['ct_builder'] ) && isset( $_GET['oxy_wfacp_id'] ) && ! isset( $_GET['oxygen_iframe'] ) ) {
			$run_setup = false;
		}

		if ( true == $run_setup ) {
			$this->setup_data( $template );
		}

	}

	protected function setup_data( $template ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
	}

	function defaultCSS() {

		if ( self::$css_build === true ) {
			return;
		}


		self::$css_build = true;


		return file_get_contents( __DIR__ . '/css/wfacp-oxygen.css' );

	}


}