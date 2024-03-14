<?php

use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class WOO_LOOKBOOK_Elementor_Widget extends Widget_Base {

	public static $slug = 'wlb-lookbook-elementor-widget';

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		do_action( 'wlb_elementor_register_scripts' );
	}

	public function get_script_depends() {
		return [ 'jquery-slides', 'jquery-vi-flexslider', 'woo-lookbook' ];
	}

	public function get_style_depends() {
		do_action( 'wlb_elementor_get_inline_style' );

		return [ 'woocommerce-vi-flexslider', 'woo-lookbook' ];
	}

	public function get_name() {
		return 'woo-lookbook';
	}

	public function get_title() {
		return __( 'WC Lookbooks', 'woocommerce-lookbook' );
	}

	public function get_icon() {
		return "dashicons dashicons-location";
	}

	public function get_categories() {
		return [ 'woocommerce-elements' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'general',
			[
				'label' => __( 'General', 'woocommerce-lookbook' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'shortcode_type',
			[
				'label'       => __( 'Select shortcode type', 'woocommerce-lookbook' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'single',
				'options'     => [
					'single'    => esc_html__( 'Single', 'woocommerce-lookbook' ),
					'multiple'  => esc_html__( 'Carousel/Gallery', 'woocommerce-lookbook' ),
					'instagram' => esc_html__( 'Instagram', 'woocommerce-lookbook' )
				]
			]
		);

		$lookbook_id = $this->get_lookbook_id();
		$this->add_control(
			'shortcode_id',
			[
				'label'       => __( 'Select id', 'woocommerce-lookbook' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => current( $lookbook_id ),
				'options'     => $lookbook_id,
				'condition'   => [ 'shortcode_type' => 'single' ]
			]
		);

		$this->add_control(
			'shortcode_ids',
			[
				'label'       => __( 'Select ids', 'woocommerce-lookbook' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'options'     => $lookbook_id,
				'multiple'    => true,
				'condition'   => [ 'shortcode_type' => 'multiple' ]
			]
		);

		$this->add_control(
			'layout',
			[
				'label'       => __( 'Layout', 'woocommerce-lookbook' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'carousel',
				'options'     => [
					'carousel' => esc_html__( 'Carousel', 'woocommerce-lookbook' ),
					'gallery'  => esc_html__( 'Gallery', 'woocommerce-lookbook' )
				],
				'condition'   => [ 'shortcode_type' => 'multiple' ]
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls() {
		$this->_register_controls();
	}

	public function raw_render() {
		$shortcode = '';
		$settings  = $this->get_settings_for_display();
		switch ( $settings['shortcode_type'] ) {
			case 'single':
				$id        = $settings['shortcode_id'] ? $settings['shortcode_id'] : '';
				$shortcode = "[woocommerce_lookbook  id='{$id}']";
				break;
			case 'multiple':
				$id        = $settings['shortcode_ids'] ? implode( ',', array_filter( $settings['shortcode_ids'] ) ) : '';
				$layout    = $settings['layout'] ? $settings['layout'] : '';
				$shortcode = "[woocommerce_lookbook_slide id='{$id}' layout='{$layout}']";
				break;
			case 'instagram':
				$shortcode = "[woocommerce_lookbook_instagram]";
				break;
		}

		return shortcode_unautop( $shortcode );
	}

	protected function render() {
		echo do_shortcode( $this->raw_render() );
	}

	public function render_plain_content() {
		echo $this->raw_render();
	}

	public function get_lookbook_id() {
		$list = [];
		$args = [
			'numberposts' => - 1,
			'orderby'     => 'date',
			'post_type'   => 'woocommerce-lookbook',
			'post_status' => [ 'publish' ],
			'meta_query'  => array(
				array(
					'key'     => 'wlb_params',
					'value'   => 's:9:"instagram";s:1',
					'compare' => 'NOT LIKE',
				),
			)
		];

		$posts = get_posts( $args );
		if ( is_array( $posts ) && ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$list[ $post->ID ] = $post->ID;
			}
		}

		return $list;
	}


}

