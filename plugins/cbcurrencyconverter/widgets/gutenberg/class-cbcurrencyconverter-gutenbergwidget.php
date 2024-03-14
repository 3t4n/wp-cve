<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Gutenberg Widget Class for CBX Currency Converted
 */
class CBCurrencyConverterGutenbergWidget {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;


	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * for setting
	 * @since    1.0.0
	 * @access   private
	 * @var      string $settings_api The current version of this plugin.
	 * */
	private $settings_api;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->settings_api = new CBCurrencyconverterSetting();
	}

	/**
	 * Init all gutenberg blocks
	 */
	public function gutenberg_blocks() {
		// if Gutenberg is not active
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		$css_url_part     = CBCURRENCYCONVERTER_ROOT_URL . 'assets/css/';
		$js_url_part      = CBCURRENCYCONVERTER_ROOT_URL . 'assets/js/';
		$vendors_url_part = CBCURRENCYCONVERTER_ROOT_URL . 'assets/vendors/';

		$css_path_part     = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/css/';
		$js_path_part      = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/js/';
		$vendors_path_part = CBCURRENCYCONVERTER_ROOT_PATH . 'assets/vendors/';


		$default_values = CBCurrencyConverterHelper::global_default_values();
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

		//write_log($default_values);

		extract( $default_values, EXTR_SKIP );


		//convert option valeus compatible for gutenberg
		$layouts         = CBCurrencyConverterHelper::get_layouts();
		$layouts_options = [];
		foreach ( $layouts as $key => $value ) {
			$layouts_options[] = [
				'label' => esc_attr( $value ),
				'value' => esc_attr( $key ),
			];
		}

		$all_currencies          = CBCurrencyConverterHelper::getCurrencyList();
		$currencies_options      = [];
		$currencies_options_from = [];
		$currencies_options_to   = [];
		$currencies_options_list = [];


		foreach ( $all_currencies as $key => $value ) {
			$currencies_options[] = [
				'label' => esc_attr( $key . ' - ' . $value ),
				'value' => esc_attr( $key ),
			];

			if ( in_array( $key, $calc_from_currencies ) ) {
				$currencies_options_from[] = [
					'label' => esc_attr( $key . ' - ' . $value ),
					'value' => esc_attr( $key ),
				];
			}

			if ( in_array( $key, $calc_to_currencies ) ) {
				$currencies_options_to[] = [
					'label' => esc_attr( $key . ' - ' . $value ),
					'value' => esc_attr( $key ),
				];
			}

			if ( in_array( $key, $list_to_currencies ) ) {
				$currencies_options_list[] = [
					'label' => esc_attr( $key . ' - ' . $value ),
					'value' => esc_attr( $key ),
				];
			}
		}


		wp_register_script( 'cbcurrencyconverter-block',
			$js_url_part . 'cbcurrencyconverter-block.js',
			[
				'wp-blocks',
				'wp-element',
				'wp-editor',
				'wp-components'
			],
			filemtime( $js_path_part . 'cbcurrencyconverter-block.js' ) );


		wp_register_style( 'cbcurrencyconverter-public', $css_url_part . 'cbcurrencyconverter-public.css', [], $this->version, 'all' );

		wp_register_style( 'cbcurrencyconverter-block', $css_url_part . 'cbcurrencyconverter-block.css', [ 'cbcurrencyconverter-public' ], filemtime( $css_path_part . 'cbcurrencyconverter-block.css' ) );

		$js_vars = apply_filters( 'cbcurrencyconverter_block_js_vars',
			[
				'block_title'         => esc_html__( 'CBX Currency Converter', 'cbcurrencyconverter' ),
				'block_category'      => 'codeboxr',
				'block_icon'          => 'universal-access-alt',
				'block_icon_url'      => plugin_dir_url( __FILE__ ) . '../assets/images/dollar.svg',
				'all_currencies_options' => $currencies_options,
				'general_settings'    => [
					'title'          => esc_html__( 'CBX Currency Converter', 'cbcurrencyconverter' ),
					'layout'         => esc_html__( 'Select Layout', 'cbcurrencyconverter' ),
					'layout_options' => $layouts_options,
					'decimal_point'  => esc_html__( 'Decimal Point', 'cbcurrencyconverter' )
				],
				'calculator_settings' => [
					'title'                        => esc_html__( 'Calculator settings', 'cbcurrencyconverter' ),
					'calc_title'                   => esc_html__( 'Calculator Header', 'cbcurrencyconverter' ),
					'calc_from_currencies'              => esc_html__( 'Calculator From Enabled Currencies', 'cbcurrencyconverter' ),
					//'calc_from_currencies_options' => $currencies_options,
					'calc_from_currency'           => esc_html__( 'Calculator From Currency', 'cbcurrencyconverter' ),
					'calc_to_currencies'           => esc_html__( 'Calculator To Enabled Currencies', 'cbcurrencyconverter' ),
					//'calc_to_currencies_options'   => $currencies_options,
					'calc_to_currency'             => esc_html__( 'Calculator To Currency', 'cbcurrencyconverter' ),
					'calc_default_amount'          => esc_html__( 'Default Amount for Calculator', 'cbcurrencyconverter' )
				],
				'list_settings'       => [
					'title'                  => esc_html__( 'List settings', 'cbcurrencyconverter' ),
					'list_title'             => esc_html__( 'List Header', 'cbcurrencyconverter' ),
					'list_default_amount'    => esc_html__( 'Default Amount for Calculator', 'cbcurrencyconverter' ),
					'list_from_currency'     => esc_html__( 'List From Currency', 'cbcurrencyconverter' ),
					'list_to_currencies'       => esc_html__( 'List To Currencies', 'cbcurrencyconverter' ),

				],
			] );

		wp_localize_script( 'cbcurrencyconverter-block', 'cbcurrencyconverter_block', $js_vars );

		//write_log( $calc_from_currencies );

		register_block_type( 'codeboxr/cbcurrencyconverter',
			[
				'editor_script'   => 'cbcurrencyconverter-block',
				'editor_style'    => 'cbcurrencyconverter-block',
				'attributes'      => apply_filters( 'cbcurrencyconverter_block_attributes',
					[
						//general
						'layout'               => [
							'type'    => 'string',
							'default' => $layout,
						],
						'decimal_point'        => [
							'type'    => 'integer',
							'default' => $decimal_point,
						],
						//calculator
						'calc_title'           => [
							'type'    => 'string',
							'default' => $calc_title,
						],
						'calc_default_amount'  => [
							'type'    => 'integer',
							'default' => $calc_default_amount,
						],
						'calc_from_currencies' => [
							'type'    => 'array',
							'default' => $calc_from_currencies,
							'items'   => [
								'type' => 'string'
							]
						],
						'calc_from_currency'   => [
							'type'    => 'string',
							'default' => $calc_from_currency,
						],
						'calc_to_currencies'   => [
							'type'    => 'array',
							'default' => $calc_to_currencies,
							'items'   => [
								'type' => 'string'
							]
						],
						'calc_to_currency'     => [
							'type'    => 'string',
							'default' => $calc_to_currency,
						],
						//list
						'list_title'          => [
							'type'    => 'string',
							'default' => $list_title,
						],
						'list_default_amount' => [
							'type'    => 'integer',
							'default' => $list_default_amount,
						],
						'list_from_currency'  => [
							'type'    => 'string',
							'default' => $list_from_currency,
						],
						'list_to_currencies'  => [
							'type'    => 'array',
							'default' => $list_to_currencies,
							'items'   => [
								'type' => 'string'
							]
						],

					] ),
				'render_callback' => [ $this, 'cbcurrencyconverter_block_render' ],
			] );

	}//end gutenberg_blocks

	/**
	 * Getenberg server side render
	 *
	 * @param $settings
	 *
	 * @return string
	 */
	public function cbcurrencyconverter_block_render( $instance ) {
		$default_values = CBCurrencyConverterHelper::global_default_values();
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

		$atts = [];


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
		$atts['list_from_currency']  = isset( $instance['list_from_currency'] ) ? sanitize_text_field( $instance['list_from_currency'] ) : $list_from_currency;
		$atts['list_to_currencies']  = isset( $instance['list_to_currencies'] ) ? wp_unslash( $instance['list_to_currencies'] ) : $list_to_currencies;


		$atts['calc_from_currencies'] = array_values( array_filter( $atts['calc_from_currencies'] ) );
		$atts['calc_to_currencies']   = array_values( array_filter( $atts['calc_to_currencies'] ) );
		$atts['list_to_currencies']   = array_values( array_filter( $atts['list_to_currencies'] ) );

		extract( $atts );

		//write_log($decimal_point);

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
			return CBCurrencyConverterHelper::cbxcclistview( 'widget', $atts );
		} elseif ( $layout == 'cal' ) {
			return CBCurrencyConverterHelper::cbxcccalcview( 'widget', $atts );
		} elseif ( $layout == 'calwithlistbottom' ) {
			return CBCurrencyConverterHelper::cbxcccalcview( 'widget', $atts ) . CBCurrencyConverterHelper::cbxcclistview( 'widget', $instance );
		} elseif ( $layout == 'calwithlisttop' ) {
			return CBCurrencyConverterHelper::cbxcclistview( 'widget', $atts ) . CBCurrencyConverterHelper::cbxcccalcview( 'widget', $instance );
		}
	}//end cbcurrencyconverter_block_render

	/**
	 * Register New Gutenberg block Category if need
	 *
	 * @param $categories
	 * @param $post
	 *
	 * @return mixed
	 */
	public function gutenberg_block_categories( $categories, $post ) {
		$found = false;

		foreach ( $categories as $category ) {
			if ( $category['slug'] == 'codeboxr' ) {
				$found = true;
				break;
			}
		}

		if ( ! $found ) {
			return array_merge(
				$categories,
				[
					[
						'slug'  => 'codeboxr',
						'title' => esc_html__( 'CBX Blocks', 'cbcurrencyconverter' ),
						//'icon'  => 'wordpress',
					],
				]
			);
		}

		return $categories;
	}//end gutenberg_block_categories


	/**
	 * Enqueue style for block editor
	 */
	public function enqueue_block_editor_assets() {

	}//end enqueue_block_editor_assets
}//end class CBCurrencyConverterGutenbergWidget