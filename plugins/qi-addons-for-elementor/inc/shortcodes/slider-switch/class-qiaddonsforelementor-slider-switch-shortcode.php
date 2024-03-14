<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_slider_switch_shortcode' ) ) {
	/**
	 * Function that is adding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_slider_switch_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Slider_Switch_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_slider_switch_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Slider_Shortcode' ) ) {
	class QiAddonsForElementor_Slider_Switch_Shortcode extends QiAddonsForElementor_Slider_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_slider_switch_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_slider_switch_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/slider-switch' );
			$this->set_base( 'qi_addons_for_elementor_slider_switch' );
			$this->set_name( esc_html__( 'Slider Switch', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays slider of images', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Creative', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/slider-switch/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#slider_switch' );
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
					'exclude_option' => array( 'columns', 'space' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'number',
					'name'          => 'autoplay_delay',
					'title'         => esc_html__( 'Set Slider Autoplay Delay', 'qi-addons-for-elementor' ) . ' (ms)',
					'dependency'    => array(
						'hide' => array(
							'slider_autoplay' => array(
								'values'        => 'no',
								'default_value' => 'yes',
							),
						),
					),
					'default_value' => 0,
					'group'         => esc_html__( 'Slider Settings', 'qi-addons-for-elementor' ),
				)
			);

			$placeholder = get_option( 'qi_addons_for_elementor_placeholder_image' );

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Images', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'main_image'   => $placeholder,
							'device_image' => $placeholder,
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'text',
							'name'          => 'item_title',
							'title'         => esc_html__( 'Title', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Item Title', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'textarea',
							'name'          => 'item_text',
							'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
							'default_value' => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
						),
						array(
							'field_type'    => 'image',
							'name'          => 'main_image',
							'multiple'      => 'no',
							'title'         => esc_html__( 'Main Image', 'qi-addons-for-elementor' ),
							'default_value' => $placeholder,
						),
						array(
							'field_type'    => 'image',
							'name'          => 'device_image',
							'multiple'      => 'no',
							'title'         => esc_html__( 'Device Image', 'qi-addons-for-elementor' ),
							'default_value' => $placeholder,
						),
						array(
							'field_type' => 'link',
							'name'       => 'item_link',
							'title'      => esc_html__( 'Link', 'qi-addons-for-elementor' ),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'main_device',
					'title'         => esc_html__( 'Main Device Frame', 'qi-addons-for-elementor' ),
					'options'       => array(
						'laptop' => esc_html__( 'Laptop', 'qi-addons-for-elementor' ),
						'tablet' => esc_html__( 'Tablet', 'qi-addons-for-elementor' ),
						'custom' => esc_html__( 'Custom', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'tablet',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'image',
					'name'       => 'main_custom_device',
					'multiple'   => 'no',
					'title'      => esc_html__( 'Main Custom Device Image', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'main_device' => array(
								'values'        => 'custom',
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'main_image_border_radius',
					'title'      => esc_html__( 'Main Image Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-main-slider' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Device Slider Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'main_image_offsets',
					'title'      => esc_html__( 'Main Image Offsets', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-main-slider' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Device Slider Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'main_device_left_offset',
					'title'      => esc_html__( 'Main Slider Left Offset', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min' => - 300,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-main' => 'left: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Device Slider Style', 'qi-addons-for-elementor' ),
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
					'field_type' => 'slider',
					'name'       => 'device_bottom_offset',
					'title'      => esc_html__( 'Device Bottom Offset', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-slider-switch-device' => 'bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Device Slider Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'device_right_offset',
					'title'      => esc_html__( 'Device Right Offset', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-slider-switch-device' => 'right: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Device Slider Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'device_width',
					'title'      => esc_html__( 'Device Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-slider-switch-device' => 'width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Device Slider Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-slider-switch-device .qodef-qi-swiper-container' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Device Slider Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-slider-switch-device .qodef-m-items' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Device Slider Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'item_title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'h3',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-main-text .qodef-e-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'item_title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-main-text .qodef-e-title',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-main-text .qodef-e-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'item_text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-main-text .qodef-e-text',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_margin_bottom',
					'title'      => esc_html__( 'Title Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-main-text .qodef-e-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'main_text_margin_bottom',
					'title'      => esc_html__( 'Main Text Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-main-text' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function render( $options, $content = null ) {
			parent::render( $options );

			$atts = $this->get_atts();

			$atts['unique'] = wp_unique_id();
			$atts['items']  = $this->parse_repeater_items( $atts['children'] );

			$atts['main_images']   = $this->generate_main_images_params( $atts );
			$atts['device_images'] = $this->generate_device_images_params( $atts );

			$atts['main_holder_classes'] = $this->get_main_holder_classes( $atts );

			$atts['slider_classes'] = $this->get_slider_classes( $atts );
			$atts['item_classes']   = $this->get_item_classes( $atts );

			$atts['main_slider_attr']   = $this->get_slider_data( $atts );
			$atts['device_slider_attr'] = $this->generate_device_slider_data( $atts );

			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'shortcodes/slider-switch', 'templates/slider-switch', '', $atts );
		}

		private function get_main_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-slider-switch';

			return implode( ' ', $holder_classes );
		}

		public function get_item_classes( $atts ) {
			$item_classes = $this->init_item_classes();

			$slider_item_classes = $this->get_slider_item_classes( $atts );

			$item_classes = array_merge( $item_classes, $slider_item_classes );

			return implode( ' ', $item_classes );
		}

		private function generate_main_images_params( $atts ) {
			$images = array();
			$i      = 0;

			foreach ( $atts['items'] as $item_key => $item ) {
				$image['image_id']  = intval( $item['main_image'] );
				$image['alt']       = get_post_meta( intval( $item['main_image'] ), '_wp_attachment_image_alt', true );
				$image['item_link'] = $item['item_link'];

				$images[ $i ] = $image;
				$i ++;
			}

			return $images;
		}

		private function generate_device_images_params( $atts ) {
			$images = array();
			$i      = 0;

			foreach ( $atts['items'] as $item_key => $item ) {
				$image['image_id']  = intval( $item['device_image'] );
				$image['alt']       = get_post_meta( intval( $item['device_image'] ), '_wp_attachment_image_alt', true );
				$image['item_link'] = $item['item_link'];

				$images[ $i ] = $image;
				$i ++;
			}

			return $images;
		}

		private function generate_device_slider_data( $atts ) {

			$atts['unique']               = '';
			$atts['columns']              = 1;
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

			$autoplay_delay = $atts['autoplay_delay'] ? $atts['autoplay_delay'] : '';


			return $this->get_slider_data( $atts, array( 'autoplayDelay' => $autoplay_delay ) );
		}
	}
}
