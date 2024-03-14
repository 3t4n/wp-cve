<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_device_slider_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_device_slider_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Device_Slider_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_device_slider_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Slider_Shortcode' ) ) {
	class QiAddonsForElementor_Device_Slider_Shortcode extends QiAddonsForElementor_Slider_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_device_slider_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_device_slider_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/device-slider' );
			$this->set_base( 'qi_addons_for_elementor_device_slider' );
			$this->set_name( esc_html__( 'Device Frame Slider', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds device slider element', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Creative', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/device-frame-slider/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#device_frame_slider' );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'effect',
					'title'      => esc_html__( 'Slide Effect', 'qi-addons-for-elementor' ),
					'group'      => 'Slider Settings',
					'options'    => array(
						'slide' => esc_html__( 'Slide', 'qi-addons-for-elementor' ),
						'fade'  => esc_html__( 'Fade', 'qi-addons-for-elementor' ),
					),
				)
			);
			$this->map_slider_options(
				array(
					'group'          => 'Slider Settings',
					'exclude_option' => array(
						'columns',
						'images_proportion',
						'space',
						'centered',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'image',
					'name'       => 'images',
					'multiple'   => 'yes',
					'title'      => esc_html__( 'Images', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'device',
					'title'      => esc_html__( 'Device Frame', 'qi-addons-for-elementor' ),
					'options'    => array(
						'mobile' => esc_html__( 'Mobile', 'qi-addons-for-elementor' ),
						'tablet' => esc_html__( 'Tablet', 'qi-addons-for-elementor' ),
						'laptop' => esc_html__( 'Laptop', 'qi-addons-for-elementor' ),
						'custom' => esc_html__( 'Custom', 'qi-addons-for-elementor' ),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'image',
					'name'       => 'custom_device',
					'multiple'   => 'no',
					'title'      => esc_html__( 'Custom Device Image', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'device' => array(
								'values'        => 'custom',
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_frame_shadow',
					'title'         => esc_html__( 'Enable Device Frame Shadow', 'qi-addons-for-elementor' ),
					'options'       => array(
						'yes' => esc_html__( 'Yes', 'qi-addons-for-elementor' ),
						'no'  => esc_html__( 'No', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'yes',
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_custom_links',
					'title'         => esc_html__( 'Enable Custom Links', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'default_value' => 'no',
					'group'         => esc_html__( 'Slider Settings', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'  => 'textarea',
					'name'        => 'custom_links',
					'title'       => esc_html__( 'Custom Links', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter links for slider images, separated by comma', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'enable_custom_links' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'dynamic'     => false,
					'group'       => esc_html__( 'Slider Settings', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'device_width',
					'title'      => esc_html__( 'Device Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 700,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-device-slider' => 'width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'image_border_radius',
					'title'      => esc_html__( 'Image Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-device-slider .qodef-qi-swiper-container' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'image_offsets',
					'title'      => esc_html__( 'Image Offsets', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-device-slider .qodef-m-items' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->map_extra_options();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['unique']         = wp_unique_id();
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['slider_classes'] = $this->get_slider_classes( $atts );
			$atts['images']         = $this->generate_images_params( $atts );
			$atts['slider_attr']    = $this->get_slider_data( $atts );
			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'shortcodes/device-slider', 'templates/device-slider', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-device-slider';
			$holder_classes[] = ( 'no' === $atts['enable_frame_shadow'] ) ? 'qodef-frame-shadow--disabled' : 'qodef-frame-shadow--enabled';

			return implode( ' ', $holder_classes );
		}

		private function generate_images_params( $atts ) {
			$image_ids = array();
			$images    = array();
			$i         = 0;

			if ( ! empty( $atts['images'] ) ) {
				$image_ids = explode( ',', $atts['images'] );
			}

			if ( 'yes' === $atts['enable_custom_links'] && ! empty( $atts['custom_links'] ) ) {
				$links = explode( ',', $atts['custom_links'] );
			}

			foreach ( $image_ids as $id ) {
				$image['image_id'] = intval( $id );
				$image['alt']      = get_post_meta( $id, '_wp_attachment_image_alt', true );

				if ( isset( $links[ $i ] ) && ! empty( $links[ $i ] ) ) {
					$image['image_link'] = $links[ $i ];
				} else {
					$image['image_link'] = '';
				}

				$images[ $i ] = $image;
				$i ++;
			}

			return $images;
		}
	}
}
