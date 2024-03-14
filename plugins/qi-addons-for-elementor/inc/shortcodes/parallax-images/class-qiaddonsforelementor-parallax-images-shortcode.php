<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_parallax_images_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_parallax_images_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Parallax_Images_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_parallax_images_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Parallax_Images_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_parallax_images_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_parallax_images_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/parallax-images' );
			$this->set_base( 'qi_addons_for_elementor_parallax_images' );
			$this->set_name( esc_html__( 'Parallax Image Showcase', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds parallax image showcase element', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Creative', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/parallax-image-showcase/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#parallax_image_showcase' );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_scripts(
				array(
					'parallax-scroll' => array(
						'registered' => false,
						'url'        => QI_ADDONS_FOR_ELEMENTOR_INC_URL_PATH . '/shortcodes/parallax-images/assets/js/plugins/jquery.parallax-scroll.js',
						'dependency' => array( 'jquery' ),
					),
				)
			);

			$options_map = qi_addons_for_elementor_get_variations_options_map( $this->get_layouts() );

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'layout',
					'title'         => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
					'options'       => $this->get_layouts(),
					'default_value' => $options_map['default_value'],
					'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'image',
					'name'       => 'main_image',
					'title'      => esc_html__( 'Main Image', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'main_image_proportion',
					'default_value' => 'full',
					'title'         => esc_html__( 'Main Image Proportion', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'list_image_dimension', false ),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'main_image_custom_width',
					'title'       => esc_html__( 'Main Image Custom Width', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image width in px', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'main_image_proportion' => array(
								'values'        => 'custom',
								'default_value' => 'full',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'main_image_custom_height',
					'title'       => esc_html__( 'Main Image Custom Height', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image height in px', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'main_image_proportion' => array(
								'values'        => 'custom',
								'default_value' => 'full',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'main_image_parallax_level',
					'title'       => esc_html__( 'Main Image Parallax Level', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter custom image parallax level', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'main_padding',
					'title'      => esc_html__( 'Main Image Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-parallax-images' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'      => 'box_shadow',
					'name'            => 'main_box_shadow',
					'title'           => esc_html__( 'Main Image Shadow', 'qi-addons-for-elementor' ),
					'selector'        => '{{WRAPPER}} .qodef-qi-parallax-images .qodef-e-main-image img',
					'exclude_options' => array(
						'box_shadow_position',
					),
					'group'           => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'      => 'box_shadow',
					'name'            => 'parallax_box_shadow',
					'title'           => esc_html__( 'Parallax Images Shadow', 'qi-addons-for-elementor' ),
					'selector'        => '{{WRAPPER}} .qodef-qi-parallax-images  .qodef-e-parallax-image img',
					'exclude_options' => array(
						'box_shadow_position',
					),
					'group'           => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$placeholder = get_option( 'qi_addons_for_elementor_placeholder_image' );

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Parallax Images', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'parallax_image'            => $placeholder,
							'parallax_image_position'   => 'bottom-left',
							'parallax_image_proportion' => 'thumbnail',
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'image',
							'name'          => 'parallax_image',
							'title'         => esc_html__( 'Parallax Image', 'qi-addons-for-elementor' ),
							'default_value' => $placeholder,
						),
						array(
							'field_type'    => 'select',
							'name'          => 'parallax_image_proportion',
							'default_value' => 'thumbnail',
							'title'         => esc_html__( 'Image Proportion', 'qi-addons-for-elementor' ),
							'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'list_image_dimension', false ),
						),
						array(
							'field_type'  => 'number',
							'name'        => 'parallax_image_custom_width',
							'title'       => esc_html__( 'Image Custom Width', 'qi-addons-for-elementor' ),
							'description' => esc_html__( 'Enter image width in px', 'qi-addons-for-elementor' ),
							'dependency'  => array(
								'show' => array(
									'parallax_image_proportion' => array(
										'values'        => 'custom',
										'default_value' => 'full',
									),
								),
							),
						),
						array(
							'field_type'  => 'number',
							'name'        => 'parallax_image_custom_height',
							'title'       => esc_html__( 'Image Custom Height', 'qi-addons-for-elementor' ),
							'description' => esc_html__( 'Enter image height in px', 'qi-addons-for-elementor' ),
							'dependency'  => array(
								'show' => array(
									'parallax_image_proportion' => array(
										'values'        => 'custom',
										'default_value' => 'full',
									),
								),
							),
						),
						array(
							'field_type' => 'slider',
							'name'       => 'parallax_image_max_width',
							'title'      => esc_html__( 'Image Max Width', 'qi-addons-for-elementor' ),
							'size_units' => array( 'px', '%', 'em' ),
							'range'      => array(
								'px' => array(
									'min' => 0,
									'max' => 500,
								),
							),
							'responsive' => true,
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
							),
						),
						array(
							'field_type'    => 'select',
							'name'          => 'parallax_image_position',
							'title'         => esc_html__( 'Image Position', 'qi-addons-for-elementor' ),
							'options'       => array(
								'top-left'     => esc_html__( 'Top Left', 'qi-addons-for-elementor' ),
								'top-right'    => esc_html__( 'Top Right', 'qi-addons-for-elementor' ),
								'bottom-left'  => esc_html__( 'Bottom Left', 'qi-addons-for-elementor' ),
								'bottom-right' => esc_html__( 'Bottom Right', 'qi-addons-for-elementor' ),
							),
							'default_value' => 'bottom-left',
						),
						array(
							'field_type' => 'slider',
							'name'       => 'parallax_image_vertical_offset',
							'title'      => esc_html__( 'Vertical Offset', 'qi-addons-for-elementor' ),
							'size_units' => array( 'px', 'vh', '%' ),
							'range'      => array(
								'px' => array(
									'min' => - 200,
									'max' => 200,
								),
								'vh' => array(
									'min' => - 50,
									'max' => 50,
								),
								'%'  => array(
									'min' => - 100,
									'max' => 100,
								),
							),
							'responsive' => true,
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}}.qodef-position--top-left'     => 'top: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} {{CURRENT_ITEM}}.qodef-position--top-right'    => 'top: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} {{CURRENT_ITEM}}.qodef-position--bottom-left'  => 'bottom: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} {{CURRENT_ITEM}}.qodef-position--bottom-right' => 'bottom: {{SIZE}}{{UNIT}};',
							),
						),
						array(
							'field_type' => 'slider',
							'name'       => 'parallax_image_horizontal_offset',
							'title'      => esc_html__( 'Horizontal Offset', 'qi-addons-for-elementor' ),
							'size_units' => array( 'px', 'vw', '%' ),
							'range'      => array(
								'px' => array(
									'min' => - 200,
									'max' => 200,
								),
								'vw' => array(
									'min' => - 50,
									'max' => 50,
								),
								'%'  => array(
									'min' => - 10,
									'max' => 10,
								),
							),
							'responsive' => true,
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}}.qodef-position--top-left'     => 'left: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} {{CURRENT_ITEM}}.qodef-position--top-right'    => 'right: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} {{CURRENT_ITEM}}.qodef-position--bottom-left'  => 'left: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} {{CURRENT_ITEM}}.qodef-position--bottom-right' => 'right: {{SIZE}}{{UNIT}};',
							),
						),
						array(
							'field_type' => 'number',
							'name'       => 'parallax_image_index',
							'title'      => esc_html__( 'Image z-index', 'qi-addons-for-elementor' ),
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}}' => 'z-index: {{VALUE}};',
							),
						),
						array(
							'field_type'  => 'number',
							'name'        => 'image_parallax_level',
							'title'       => esc_html__( 'Image Parallax Level', 'qi-addons-for-elementor' ),
							'description' => esc_html__( 'Enter custom image parallax level', 'qi-addons-for-elementor' ),
						),
					),
				)
			);
			$this->map_extra_options();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes']        = $this->get_holder_classes( $atts );
			$atts['items']                 = $this->parse_repeater_items( $atts['children'] );
			$atts['this_shortcode']        = $this;
			$atts['main_image_data_attrs'] = $this->get_main_image_data_attrs( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/parallax-images', 'variations/' . $atts['layout'] . '/templates/' . $atts['layout'], '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-parallax-images';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';

			return implode( ' ', $holder_classes );
		}

		public function get_item_classes( $item ) {

			$item_classes[] = 'qodef-e-parallax-image';
			$item_classes[] = ! empty( $item['parallax_image_position'] ) ? 'qodef-position--' . $item['parallax_image_position'] : '';
			$item_classes[] = ! empty( $item['_id'] ) ? 'elementor-repeater-item-' . $item['_id'] : '';

			return implode( ' ', $item_classes );
		}

		public function get_item_data_atts( $item ) {

			$data = array();

			$item_parallax_level = $item['image_parallax_level'];

			$data['data-parallax'] = $item_parallax_level;

			if ( ( 'integer' === gettype( $item['image_parallax_level'] ) ) && ( 0 === $item['image_parallax_level'] ) ) {
				$data['data-parallax'] = 'parallax-disabled';
			}

			return $data;
		}

		private function get_main_image_data_attrs( $atts ) {
			$data = array();

			$main_image_parallax_level = $atts['main_image_parallax_level'];

			$data['data-parallax-main'] = $main_image_parallax_level;

			if ( ( 'integer' === gettype( $atts['main_image_parallax_level'] ) ) && ( 0 === $atts['main_image_parallax_level'] ) ) {
				$data['data-parallax-main'] = 'parallax-disabled';
			}

			return $data;
		}
	}
}
