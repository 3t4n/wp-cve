<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_device_carousel_shortcode' ) ) {
	/**
	 * Function that is adding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_device_carousel_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Device_Carousel_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_device_carousel_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Slider_Shortcode' ) ) {
	class QiAddonsForElementor_Device_Carousel_Shortcode extends QiAddonsForElementor_Slider_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_device_carousel_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_device_carousel_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/device-carousel' );
			$this->set_base( 'qi_addons_for_elementor_device_carousel' );
			$this->set_name( esc_html__( 'Device Frame Carousel', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays device carousel of images', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Creative', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/device-frame-carousel/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#device_frame_carousel' );
			$this->set_video( 'https://www.youtube.com/watch?v=7qYRfZ-sD7Q' );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->map_slider_options(
				array(
					'group'          => 'Slider Settings',
					'exclude_option' => array( 'columns', 'images_proportion' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Device Items', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'device'      => 'mobile',
							'slide_width' => array(
								'unit' => '%',
								'size' => '20',
							),
						),
						array(
							'device'      => 'tablet',
							'slide_width' => array(
								'unit' => '%',
								'size' => '30',
							),
						),
						array(
							'device'      => 'laptop',
							'slide_width' => array(
								'unit' => '%',
								'size' => '50',
							),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'select',
							'name'          => 'device',
							'title'         => esc_html__( 'Device Frame', 'qi-addons-for-elementor' ),
							'options'       => array(
								'mobile'           => esc_html__( 'Mobile', 'qi-addons-for-elementor' ),
								'mobile-landscape' => esc_html__( 'Mobile Landscape', 'qi-addons-for-elementor' ),
								'tablet'           => esc_html__( 'Tablet', 'qi-addons-for-elementor' ),
								'laptop'           => esc_html__( 'Laptop', 'qi-addons-for-elementor' ),
								'custom'           => esc_html__( 'Custom', 'qi-addons-for-elementor' ),
							),
							'default_value' => 'mobile',
						),
						array(
							'field_type' => 'image',
							'name'       => 'custom_device',
							'multiple'   => 'no',
							'title'      => esc_html__( 'Custom Device Image', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'image',
							'name'       => 'images',
							'multiple'   => 'yes',
							'title'      => esc_html__( 'Device Image', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'slider',
							'name'          => 'slide_width',
							'title'         => esc_html__( 'Slide Width', 'qi-addons-for-elementor' ),
							'size_units'    => array( 'px', '%', 'em' ),
							'responsive'    => true,
							'selectors'     => array(
								'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
							),
							'default_value' => array(
								'unit' => '%',
								'size' => '30',
							),
							'group'         => esc_html__( 'Device Slider Style', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'slider',
							'name'       => 'device_width',
							'title'      => esc_html__( 'Device Width', 'qi-addons-for-elementor' ),
							'size_units' => array( 'px', '%', 'em' ),
							'responsive' => true,
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-device-carousel-device' => 'width: {{SIZE}}{{UNIT}};',
							),
							'group'      => esc_html__( 'Device Slider Style', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'slider',
							'name'       => 'image_border_radius',
							'title'      => esc_html__( 'Image Border Radius', 'qi-addons-for-elementor' ),
							'size_units' => array( 'px', '%', 'em' ),
							'responsive' => true,
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-device-carousel-device .qodef-qi-swiper-container' => 'border-radius: {{SIZE}}{{UNIT}};',
							),
							'group'      => esc_html__( 'Device Slider Style', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'dimensions',
							'name'       => 'image_offsets',
							'title'      => esc_html__( 'Image Offsets', 'qi-addons-for-elementor' ),
							'size_units' => array( 'px', '%', 'em' ),
							'responsive' => true,
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-device-carousel-device .qodef-m-items' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
							),
							'group'      => esc_html__( 'Device Slider Style', 'qi-addons-for-elementor' ),
						),
					),
				)
			);
		}

		public function render( $options, $content = null ) {
			parent::render( $options );

			$atts = $this->get_atts();

			$atts['unique'] = wp_unique_id();
			$atts['items']  = $this->parse_repeater_items( $atts['children'] );

			$atts['main_holder_classes'] = $this->get_main_holder_classes( $atts );

			$atts['slider_classes'] = $this->get_slider_classes( $atts );
			$atts['item_classes']   = $this->get_item_classes( $atts );

			$atts['main_slider_attr']   = $this->generate_main_slider_data( $atts );
			$atts['device_slider_attr'] = $this->generate_device_slider_data( $atts );

			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'shortcodes/device-carousel', 'templates/device-carousel', '', $atts );
		}

		private function get_main_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-device-carousel';

			return implode( ' ', $holder_classes );
		}

		public function get_item_classes( $atts ) {
			$item_classes = $this->init_item_classes();

			$slider_item_classes = $this->get_slider_item_classes( $atts );

			$item_classes = array_merge( $item_classes, $slider_item_classes );

			return implode( ' ', $item_classes );
		}

		private function generate_main_slider_data( $atts ) {

			$atts['columns'] = 'auto';

			return $this->get_slider_data( $atts );
		}

		private function generate_device_slider_data( $atts ) {
			$atts['unique']               = '';
			$atts['columns']              = 1;
			$atts['slider_autoplay']      = 'yes';
			$atts['slider_loop']          = 'yes';
			$atts['columns_1440']         = 1;
			$atts['columns_1366']         = 1;
			$atts['columns_1024']         = 1;
			$atts['columns_768']          = 1;
			$atts['columns_680']          = 1;
			$atts['columns_480']          = 1;
			$atts['space']['size']        = 0;
			$atts['space_tablet']['size'] = 0;
			$atts['space_mobile']['size'] = 0;
			$atts['centered_slides']      = 'no';
			$atts['slider_navigation']    = 'no';
			$atts['slider_pagination']    = 'no';
			$atts['partial_columns']      = 'no';

			return $this->get_slider_data( $atts );
		}
	}
}
