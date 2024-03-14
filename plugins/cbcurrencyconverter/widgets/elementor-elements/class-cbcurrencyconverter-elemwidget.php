<?php

namespace CBCurrencyConverterElemWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Currency converted elementor widget class
 */
class CBCurrencyConverter_ElemWidget extends \Elementor\Widget_Base {
	//private $current_settings;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );


		//$this->current_settings = isset($data['settings'])? $data['settings'] : [];

		//wp_register_script( 'script-handle', 'path/to/file.js', [ 'elementor-frontend' ], '1.0.0', true );

		/*$all_currencies = \CBCurrencyConverterHelper::getCurrencyList();

		wp_register_script( 'cbcurrencyconverter-elementor', CBCURRENCYCONVERTER_ROOT_URL . 'assets/js/cbcurrencyconverter-elementor.js', ['jquery', 'elementor-frontend'], CBCURRENCYCONVERTER_VERSION, true );
		// Localize the script with new data
		$translation_array = array(
			'please_select'   => esc_html__( 'Please Select', 'cbcurrencyconverter' ),
			'upload_btn'      => esc_html__( 'Upload', 'cbcurrencyconverter' ),
			'upload_title'    => esc_html__( 'Select Media', 'cbcurrencyconverter' ),
			'all_currencies'  => $all_currencies,
		);

		wp_localize_script( 'cbcurrencyconverter-elementor', 'cbcurrencyconverter_elementor', $translation_array );*/

		//wp_enqueue_script( 'cbcurrencyconverter-elementor' );
	}

	/*public function get_script_depends() {
		return [ 'cbcurrencyconverter-elementor' ];
	}*/


	/**
	 * Retrieve google maps widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'cbcurrencyconverter';
	}

	/**
	 * Retrieve google maps widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'CBX Currency Converter', 'cbcurrencyconverter' );
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the widget categories.
	 *
	 * @return array Widget categories.
	 * @since  1.0.10
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'codeboxr' ];
	}

	/**
	 * Retrieve google maps widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'cbcurrencyconverter-icon';
	}

	/**
	 * Register google maps widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$default_values = \CBCurrencyConverterHelper::global_default_values();

		//take care(comma sep string to array) array related properties
		if ( isset( $default_values['calc_from_currencies'] ) && is_string( $default_values['calc_from_currencies'] ) ) {
			$default_values['calc_from_currencies'] = explode( ',', $default_values['calc_from_currencies'] );
		}

		if ( isset( $default_values['calc_to_currencies'] ) && is_string( $default_values['calc_to_currencies'] ) ) {
			$default_values['calc_to_currencies'] = explode( ',', $default_values['calc_to_currencies'] );
		}

		if ( isset( $default_values['list_to_currencies'] ) && is_string( $default_values['list_to_currencies'] ) ) {
			$default_values['list_to_currencies'] = explode( ',', $default_values['list_to_currencies'] );
		}

		extract( $default_values, EXTR_SKIP );


		$all_currencies = \CBCurrencyConverterHelper::getCurrencyList();


		$this->start_controls_section(
			'section_cbcurrencyconverter_single',
			[
				'label' => esc_html__( 'CBX Currency Converter Setting', 'cbcurrencyconverter' ),
			]
		);


		$this->add_control(
			'layout',
			[
				'label'       => esc_html__( 'Layout', 'cbcurrencyconverter' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'placeholder' => esc_html__( 'Select layout', 'cbcurrencyconverter' ),
				'default'     => $layout,
				'options'     => \CBCurrencyConverterHelper::get_layouts(),
				'label_block' => true,
			]
		);

		$this->add_control(
			'decimal_point',
			[
				'label'   => esc_html__( 'Decimal Point', 'cbcurrencyconverter' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => $decimal_point,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_cbcurrencyconverter_cal_settings',
			[
				'label' => esc_html__( 'Calculator settings', 'cbcurrencyconverter' ),
			]
		);

		$this->add_control(
			'calc_title',
			[
				'label'   => esc_html__( 'Calculator Header', 'cbcurrencyconverter' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => $calc_title,
			]
		);

		$this->add_control(
			'calc_default_amount',
			[
				'label'   => esc_html__( 'Default Amount for Calculator', 'cbcurrencyconverter' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => $calc_default_amount,
			]
		);


		$this->add_control(
			'calc_from_currencies',
			[
				'label'       => esc_html__( 'From Enable Currencies', 'cbcurrencyconverter' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'placeholder' => esc_html__( 'Select currencies from', 'cbcurrencyconverter' ),
				'default'     => $calc_from_currencies,
				'options'     => $all_currencies,
				'multiple'    => true,
				'label_block' => true,
				'required'    => true
				//'event'       => 'change',
			]
		);

		/*$all_currencies_options = [];
		foreach ( $all_currencies as $key => $title ) {
			if ( ! in_array( $key, $calc_from_currencies ) ) {
				continue;
			}

			$all_currencies_options[$key] = $title;
		}*/

		$this->add_control(
			'calc_from_currency',
			[
				'label'       => esc_html__( 'Calculator From Currency', 'cbcurrencyconverter' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'placeholder' => esc_html__( 'Select currency', 'cbcurrencyconverter' ),
				'default'     => $calc_from_currency,
				'options'     => $all_currencies,
				'label_block' => true,
				'required'    => true
			]
		);

		$this->add_control(
			'calc_to_currencies',
			[
				'label'       => esc_html__( 'To Enable Currencies', 'cbcurrencyconverter' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'placeholder' => esc_html__( 'Select to currencies', 'cbcurrencyconverter' ),
				'default'     => $calc_to_currencies,
				'options'     => $all_currencies,
				'multiple'    => true,
				'label_block' => true,
				'required'    => true
			]
		);

		/*$all_currencies_options = [];
		foreach ( $all_currencies as $key => $title ) {
			if ( ! in_array( $key, $calc_to_currencies ) ) {
				continue;
			}

			$all_currencies_options[$key] = $title;
		}*/

		$this->add_control(
			'calc_to_currency',
			[
				'label'       => esc_html__( 'Calculator To Currency', 'cbcurrencyconverter' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'placeholder' => esc_html__( 'Select currency', 'cbcurrencyconverter' ),
				'default'     => $calc_to_currency,
				'options'     => $all_currencies,
				'label_block' => true,
				'required'    => true
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_cbcurrencyconverter_list',
			[
				'label' => esc_html__( 'List settings', 'cbcurrencyconverter' ),
			]
		);
		$this->add_control(
			'list_title',
			[
				'label'   => esc_html__( 'List Header', 'cbcurrencyconverter' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => $list_title,
			]
		);

		$this->add_control(
			'list_default_amount',
			[
				'label'   => esc_html__( 'Default Amount for List', 'cbcurrencyconverter' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => $list_default_amount,
			]
		);

		//$all_currencies = \CBCurrencyConverterHelper::getCurrencyList();


		$this->add_control(
			'list_to_currencies',
			[
				'label'       => esc_html__( 'List To Currencies', 'cbcurrencyconverter' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'placeholder' => esc_html__( 'Select currencies', 'cbcurrencyconverter' ),
				'default'     => $list_to_currencies,
				'options'     => $all_currencies,
				'label_block' => true,
				'multiple'    => true,
			]
		);

		$this->add_control(
			'list_from_currency',
			[
				'label'       => esc_html__( 'List From Currency', 'cbcurrencyconverter' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'placeholder' => esc_html__( 'Select currency', 'cbcurrencyconverter' ),
				'default'     => $list_from_currency,
				'options'     => $all_currencies,
				'label_block' => true,
			]
		);


		$this->end_controls_section();
	}//end register_controls

	/**
	 * Render google maps widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$default_values = \CBCurrencyConverterHelper::global_default_values();

		//take care(comma sep string to array) array related properties
		if ( isset( $default_values['calc_from_currencies'] ) && is_string( $default_values['calc_from_currencies'] ) ) {
			$default_values['calc_from_currencies'] = explode( ',', $default_values['calc_from_currencies'] );
		}

		if ( isset( $default_values['calc_to_currencies'] ) && is_string( $default_values['calc_to_currencies'] ) ) {
			$default_values['calc_to_currencies'] = explode( ',', $default_values['calc_to_currencies'] );
		}

		if ( isset( $default_values['list_to_currencies'] ) && is_string( $default_values['list_to_currencies'] ) ) {
			$default_values['list_to_currencies'] = explode( ',', $default_values['list_to_currencies'] );
		}

		extract( $default_values, EXTR_SKIP );

		$calc_from_currencies = $default_values['calc_from_currencies'] = array_values( array_filter( $calc_from_currencies ) );
		$calc_to_currencies   = $default_values['calc_to_currencies'] = array_values( array_filter( $calc_to_currencies ) );
		$list_to_currencies   = $default_values['list_to_currencies'] = array_values( array_filter( $list_to_currencies ) );


		$atts = [];

		//$instance = $this->get_settings();
		$instance = $this->get_settings_for_display();


		$atts['layout']        = isset( $instance['layout'] ) ? sanitize_text_field( $instance['layout'] ) : $layout;
		$atts['decimal_point'] = isset( $instance['decimal_point'] ) ? intval( $instance['decimal_point'] ) : $decimal_point;


		//calculator setting
		$atts['calc_title']           = isset( $instance['calc_title'] ) ? sanitize_text_field( $instance['calc_title'] ) : $calc_title;
		$atts['calc_default_amount']  = isset( $instance['calc_default_amount'] ) ? floatval( $instance['calc_default_amount'] ) : $calc_default_amount;
		$atts['calc_from_currencies'] = isset( $instance['calc_from_currencies'] ) ? wp_unslash( $instance['calc_from_currencies'] ) : $calc_from_currencies;
		$atts['calc_from_currency']   = isset( $instance['calc_from_currency'] ) ? sanitize_text_field( $instance['calc_from_currency'] ) : $calc_from_currency;
		$atts['calc_to_currencies']   = isset( $instance['calc_to_currencies'] ) ? wp_unslash( $instance['calc_to_currencies'] ) : $calc_to_currencies;
		$atts['calc_to_currency']     = isset( $instance['calc_to_currency'] ) ? sanitize_text_field( $instance['calc_to_currency'] ) : $calc_to_currency;


		//list setting
		$atts['list_title']          = isset( $instance['list_title'] ) ? sanitize_text_field( $instance['list_title'] ) : $list_title;
		$atts['list_default_amount'] = isset( $instance['list_default_amount'] ) ? floatval( $instance['list_default_amount'] ) : $list_default_amount;
		$atts['list_to_currencies']  = isset( $instance['list_to_currencies'] ) ? wp_unslash( $instance['list_to_currencies'] ) : $list_to_currencies;
		$atts['list_from_currency']  = isset( $instance['list_from_currency'] ) ? sanitize_text_field( $instance['list_from_currency'] ) : $list_from_currency;


		$atts['calc_from_currencies'] = array_values( array_filter( $atts['calc_from_currencies'] ) );
		$atts['calc_to_currencies']   = array_values( array_filter( $atts['calc_to_currencies'] ) );
		$atts['list_to_currencies']   = array_values( array_filter( $atts['list_to_currencies'] ) );

		extract( $atts );

		if ( sizeof( $calc_from_currencies ) == 0 ) {
			$calc_from_currencies = $atts['calc_from_currencies'] = $default_values['calc_from_currencies'];
		}
		if ( sizeof( $calc_to_currencies ) == 0 ) {
			$calc_to_currencies = $atts['calc_to_currencies'] = $default_values['calc_to_currencies'];
		}
		if ( sizeof( $list_to_currencies ) == 0 ) {
			$list_to_currencies = $atts['list_to_currencies'] = $default_values['list_to_currencies'];
		}


		if ( ! in_array( $calc_from_currency, $calc_from_currencies ) || $calc_from_currency == '' ) {
			$calc_from_currency = $atts['calc_from_currency'] = cbcurrencyconverter_first_value( $calc_from_currencies );
		}

		if ( ! in_array( $calc_to_currency, $calc_to_currencies ) || $calc_to_currency == '' ) {
			$calc_to_currency = $atts['calc_to_currency'] = cbcurrencyconverter_first_value( $calc_to_currencies );
		}

		if ( $list_from_currency == '' ) {
			$list_from_currency = $atts['list_from_currency'] = $default_values['list_from_currency'];
		}

		if ( $layout == 'list' ) {
			echo \CBCurrencyConverterHelper::cbxcclistview( 'widget', $atts );
		} elseif ( $layout == 'cal' ) {
			echo \CBCurrencyConverterHelper::cbxcccalcview( 'widget', $atts );
		} elseif ( $layout == 'calwithlistbottom' ) {
			echo \CBCurrencyConverterHelper::cbxcccalcview( 'widget', $atts ) . \CBCurrencyConverterHelper::cbxcclistview( 'widget', $atts );
		} elseif ( $layout == 'calwithlisttop' ) {
			echo \CBCurrencyConverterHelper::cbxcclistview( 'widget', $atts ) . \CBCurrencyConverterHelper::cbxcccalcview( 'widget', $atts );
		}
	}//end render

	/**
	 * Render google maps widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
		//
	}//end content_template
}//end CBCurrencyConverter_ElemWidget
