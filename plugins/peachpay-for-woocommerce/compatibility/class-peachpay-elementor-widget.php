<?php
/**
 * Support for the Elementor Plugin
 * Plugin: https://elementor.com/
 *
 * @package PeachPay
 */

namespace Elementor;

use ElementorPro\Modules\QueryControl\Module;

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * Elementor widget that inserts the PeachPay button onto any page.
 */
class PeachPay_Elementor_Widget extends Widget_Base {
	//phpcs:ignore
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}
//phpcs:ignore
	public function get_style_depends() {
		return array( 'enqueue_peachpay_css' );
	}
//phpcs:ignore
	public function get_script_depends() {
		return array( 'enqueue_peachpay_js' );
	}
//phpcs:ignore
	public function get_name() {
		return 'peachpay';
	}
//phpcs:ignore
	public function get_title() {
		return __( 'PeachPay', 'peachpay-for-woocommerce' );
	}
//phpcs:ignore
	public function get_icon() {
		return 'eicon-cart-solid';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the PeachPay widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	/**
	 * Register PeachPay widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function register_controls() {
		$button_options   = get_option( 'peachpay_express_checkout_button' );
		$branding_options = get_option( 'peachpay_express_checkout_branding' );

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'peachpay-for-woocommerce' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => __( 'Button Text', 'peachpay-for-woocommerce' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Express Checkout', 'peachpay-for-woocommerce' ),
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'   => __( 'Button Color', 'peachpay-for-woocommerce' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => $branding_options['button_color'] ?? PEACHPAY_DEFAULT_BACKGROUND_COLOR,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'   => __( 'Text Color', 'peachpay-for-woocommerce' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => $branding_options['button_text_color'] ?? PEACHPAY_DEFAULT_TEXT_COLOR,
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'   => __( 'Rounded Corners', 'peachpay-for-woocommerce' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 50,
				'default' => $button_options['button_border_radius'] ?? 5,
			)
		);

		$this->add_control(
			'icon_class',
			array(
				'label'   => __( 'Icon', 'peachpay-for-woocommerce' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'pp-icon-lock'     => esc_html__( 'Lock', 'peachpay-for-woocommerce' ),
					'pp-icon-baseball' => esc_html__( 'Baseball', 'peachpay-for-woocommerce' ),
					'pp-icon-arrow'    => esc_html__( 'Arrow', 'peachpay-for-woocommerce' ),
					'pp-icon-mountain' => esc_html__( 'Mountain', 'peachpay-for-woocommerce' ),
					'pp-icon-bag'      => esc_html__( 'Bag', 'peachpay-for-woocommerce' ),
					'pp-icon-cart'     => esc_html__( 'Cart', 'peachpay-for-woocommerce' ),
					'pp-icon-disabled' => esc_html__( 'None', 'peachpay-for-woocommerce' ),
				),
				'default' => $button_options['button_icon'] ?? 'pp-icon-disabled',
			)
		);

		$this->add_control(
			'effect_class',
			array(
				'label'   => __( 'Button hover effect', 'peachpay-for-woocommerce' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'none' => esc_html__( 'None', 'peachpay-for-woocommerce' ),
					'fade' => esc_html__( 'Fade', 'peachpay-for-woocommerce' ),
				),
				'default' => $button_options['button_effect'] ?? 'fade',
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'   => __( 'Alignment', 'peachpay-for-woocommerce' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'left'   => __( 'Left', 'peachpay-for-woocommerce' ),
					'right'  => __( 'Right', 'peachpay-for-woocommerce' ),
					'full'   => __( 'Full', 'peachpay-for-woocommerce' ),
					'center' => __( 'Center', 'peachpay-for-woocommerce' ),
				),
				'default' => 'center',
			)
		);

		$this->add_control(
			'width',
			array(
				'label'   => __( 'Width', 'peachpay-for-woocommerce' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 400,
				'step'    => 5,
				'default' => 220,
			)
		);

		$this->add_control(
			'display_available_payment_icons',
			array(
				'label'     => __( 'Display payment method icons below', 'peachpay-for-woocommerce' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_on'  => 'yes',
				'label_off' => 'no',
				'default'   => 'no',
			)
		);

		$this->add_control(
			'add_product_enable',
			array(
				'label'       => __( 'Enable Specific Product', 'peachpay-for-woocommerce' ),
				'description' => __( "Choose a product that will be added to the shopper's cart before the checkout window opens.", 'peachpay-for-woocommerce' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label_on'    => 'yes',
				'label_off'   => 'no',
				'default'     => 'no',
			)
		);

		if ( class_exists( 'ElementorPro\Modules\QueryControl\Module' ) ) {
			$this->add_control(
				'product_id',
				array(
					'label'        => __( 'Specific Product', 'peachpay-for-woocommerce' ),
					'type'         => Module::QUERY_CONTROL_ID,
					'options'      => array(),
					'label_block'  => true,
					'autocomplete' => array(
						'object' => Module::QUERY_OBJECT_POST,
						'query'  => array(
							'post_type' => array( 'product' ),
						),
					),
					'condition'    => array(
						'add_product_enable' => 'yes',
					),
				)
			);
		} else {
			$this->add_control(
				'product_id',
				array(
					'label'       => __( 'Specific Product', 'peachpay-for-woocommerce' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'placeholder' => 'Product id',
					'condition'   => array(
						'add_product_enable' => 'yes',
					),
				)
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Render PeachPay widget output on the frontend.
	 */
	protected function render() {
		if ( ! pp_should_display_public() ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		if ( ! isset( $settings['custom_styles'] ) ) {
			$settings['custom_styles'] = '';
		}

		if ( ! isset( $settings['width'] ) ) {
			$settings['width'] = 220;
		}

		if ( ! isset( $settings['custom_attributes'] ) ) {
			$settings['custom_attributes'] = array();
		}

		if ( 'left' === $settings['alignment'] ) {
			$settings['custom_styles'] .= 'margin-right: auto;';
		} elseif ( 'right' === $settings['alignment'] ) {
			$settings['custom_styles'] .= 'margin-left: auto;';
		} elseif ( 'center' === $settings['alignment'] ) {
			$settings['custom_styles'] .= 'margin-left: auto; margin-right: auto;';
		} elseif ( 'full' === $settings['alignment'] ) {
			$settings['width'] = '100%';
		}

		if ( isset( $settings['add_product_enable'] ) && isset( $settings['product_id'] ) && 'yes' === $settings['add_product_enable'] ) {
			$product = wc_get_product( $settings['product_id'] );
			if ( is_null( $product ) || ! $product ) {
				if ( pp_should_display_admin() ) {
					echo esc_html( 'Error: Product id ' . $settings['product_id'] . ' not found. Shortcode usage: [peachpay product_id=123] where 123 is the id of a valid product.' );
					return;
				} else {
					return;
				}
			}

			$url   = $product->add_to_cart_url();
			$query = wp_parse_url( $url, PHP_URL_QUERY );

			$settings['custom_attributes']['href']                    = pp_checkout_permalink() . '?' . $query;
			$settings['custom_attributes']['data-activation-trigger'] = 'shortcode';
		}

		echo pp_checkout_button_template( $settings );//PHPCS:ignore
	}
}
