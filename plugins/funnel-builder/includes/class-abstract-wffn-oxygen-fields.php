<?php

#[AllowDynamicProperties]

 abstract class WFFN_OXY_Field extends OxyEl {
	protected $child_class_slug = 'wfacp_';
	protected $run_controls = false;
	protected $name = '';
	protected $get_parameter = 'oxy_wffn_optin_id';
	public $slug = '';
	protected $id = '';
	protected $settings = [];
	protected $post_id = 0;
	protected $tabs = [];
	protected $sub_tabs = [];
	protected $html_fields = [];
	private $add_tab_number = 1;


	protected $style_box = null;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_footer', [ $this, 'scripts' ] );
		add_action( 'wp_head', [ $this, 'style' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'remove_other_style' ], 11 );
	}

	public function options() {
		return [ 'rebuild_on_dom_change' => true ];
	}

	final public function render( $setting, $defaults, $content ) {


		if ( apply_filters( 'wffn_optin_print_oxy_widget', true, $this->get_id(), $this ) ) {

			if ( WFFN_OXYGEN::is_template_editor() ) {
				$this->preview_shortcode();

				return;
			}

			$this->settings = $setting;
			$this->html( $setting, $defaults, $content );
			if ( isset( $_REQUEST['action'] ) && false !== strpos( $_REQUEST['action'], 'oxy_render' ) ) {//phpcs:ignore
				exit;
			}
		}

	}

	protected function preview_shortcode() {
		echo "[{$this->name}]";//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
			$title = '';
		}
		$field_key = $this->child_class_slug . $this->add_tab_number . "_tab";
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
			$default = '15';
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


	/* Typography Fields End*/

	protected function add_switcher( $control, $key, $label = '', $default = 'off', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = __( 'Enable', 'woofunnels-aero-checkout' );
		}
		$input = [
			"type"    => "radio",
			"name"    => $label,
			"slug"    => $key,
			"value"   => [ 'on' => __( "Yes" ), "off" => __( 'No' ) ],
			"default" => $default
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

	public function add_text( $control, $key, $label, $default = '', $conditions = [], $description = '', $placeholder = '' ) {

		$input = array(
			'name'        => $label,
			'slug'        => $key,
			'type'        => 'textarea',
			'default'     => $default,
			'placeholder' => $placeholder,
			"base64"      => true,
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
			'default' => $default,
			"base64"  => true,
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

	protected function add_text_alignments( $tab_id, $key, $selectors = '', $label = '', $default = 'left', $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_html__( 'Alignment', 'et_builder' );
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
			$label = esc_attr__( 'Background', 'funnel-builder' );
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
			$label = esc_attr__( 'Border Color', 'funnel-builder' );
		}

		$input     = array(
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

	protected function add_border_radius( $tab_id, $key, $selector, $conditions = [], $default = [] ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Border Radius', 'funnel-builder' );
		}

		$input     = array(
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
		$tab_id->addStyleControls( [ $input ] );

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


	protected function add_box_shadow( $tab_id, $key, $selector, $label = '' ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Border Shadow', 'funnel-builder' );
		}

		$tab_id->boxShadowSection( $label, $selector, $this );

		return $key;
	}

	protected function add_width( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Width', 'funnel-builder' );
		}


		$input = array(
			"name"     => $label,
			"selector" => $selectors,
			"slug"     => $key,
			"property" => 'width',
		);
		if ( isset( $default['default'] ) && ! empty( $default['default'] ) ) {
			$input['default'] = $default['default'];
		}
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

	protected function add_max_width( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Width', 'funnel-builder' );
		}


		$input = array(
			"name"     => $label,
			"selector" => $selectors,
			"slug"     => $key,
			"property" => 'max-width',
		);
		if ( isset( $default['default'] ) && ! empty( $default['default'] ) ) {
			$input['default'] = $default['default'];
		}
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

	protected function add_height( $tab_id, $key, $selectors = '', $label = '', $default = [], $conditions = [] ) {
		if ( empty( $label ) ) {
			$label = esc_attr__( 'Height', 'funnel-builder' );
		}
		$input = array(
			"name"     => $label,
			"selector" => $selectors,
			"slug"     => $key,
			"property" => 'height',
		);

		if ( isset( $default['default'] ) && ! empty( $default['default'] ) ) {
			$input['default'] = $default['default'];
		}

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
			$label = esc_attr__( 'Min Width', 'funnel-builder' );
		}
		$input     = array(
			"name"     => $label,
			"selector" => $selectors,
			"slug"     => $key,
			'default'  => $default,
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
		$c->setUnits( $default['unit'], "px,em,%" );


		return $key;
	}


	protected function get_class_options() {
		return [
			'wffn-sm-100' => __( 'Full', 'funnel-builder' ),
			'wffn-sm-50'  => __( 'One Half', 'funnel-builder' ),
			'wffn-sm-33'  => __( 'One Third', 'funnel-builder' ),
			'wffn-sm-67'  => __( 'Two Third', 'funnel-builder' ),
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
		$key = md5( $this->child_class_slug . $count );

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


	public function controls() {
		$this->process_data();
	}


	private function process_data() {

		global $post;

		// checking for when builder is open
		if ( $this->is_oxy_page() ) {
			$wffn_post_id = 0;
			if ( isset( $_REQUEST[ $this->get_parameter ] ) ) {//phpcs:ignore
				$wffn_post_id = $_REQUEST[ $this->get_parameter ];//phpcs:ignore
			} else if ( isset( $_REQUEST['post_id'] ) && ! WFFN_OXYGEN::is_template_editor() ) {//phpcs:ignore
				$wffn_post_id = $_REQUEST['post_id'];//phpcs:ignore
			}
			if ( $wffn_post_id > 0 ) {
				$post = get_post( $wffn_post_id );//phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}

		}

		if ( is_null( $post ) || ( ! is_null( $post ) && $post->post_type !== $this->get_post_type() ) ) {
			return [];
		}

		$this->run_controls = $this->setup_data( $post );
		if ( true === $this->run_controls ) {
			$this->setup_controls();
		}
	}


	public function is_oxy_page() {

		$status = true;
		// At load
		if ( isset( $_REQUEST['ct_builder'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$this->is_oxy = true;
			$status       = true;

		}
		// when ajax running for form html
		if ( isset( $_REQUEST['action'] ) && ( 'set_oxygen_edit_post_lock_transient' === $_REQUEST['action'] || 'oxy_load_elements_presets' === $_REQUEST['action'] || false !== strpos( $_REQUEST['action'], 'oxy_render_' ) || false !== strpos( $_REQUEST['action'], 'oxy_load_controls_oxy' ) ) ) { //phpcs:ignore

			$this->is_oxy = true;
			$status       = true;
		}


		return $status;
	}

	protected function get_post_type() {

	}

	protected function setup_data( $post = null ) {//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
		return false;
	}

	protected function setup_controls() {

	}


	public function scripts() {

	}

	public function style() {

	}

	public function remove_other_style() {

		wp_dequeue_style( 'woofunnels-op-divi-popup-wfop-divi' );

	}


}