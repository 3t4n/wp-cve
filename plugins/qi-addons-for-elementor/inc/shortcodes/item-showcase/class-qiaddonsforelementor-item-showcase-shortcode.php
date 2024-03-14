<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_item_showcase_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_item_showcase_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Item_Showcase_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_item_showcase_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Item_Showcase_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_item_showcase_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_item_showcase_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/item-showcase' );
			$this->set_base( 'qi_addons_for_elementor_item_showcase' );
			$this->set_name( esc_html__( 'Item Showcase', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds item showcase holder', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Showcase', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/item-showcase/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#item_showcase' );
			$this->set_video( 'https://www.youtube.com/watch?v=Ntb24Ard5u8' );
			$this->set_necessary_styles( qi_addons_for_elementor_icon_necessary_styles() );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);

			$options_map = qi_addons_for_elementor_get_variations_options_map( $this->get_layouts() );

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'layout',
					'title'         => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
					'options'       => $this->get_layouts(),
					'default_value' => 'standard',
					'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'image',
					'name'       => 'main_image',
					'title'      => esc_html__( 'Image', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'appear_animation',
					'title'      => esc_html__( 'Enable Appear Animation', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
					'group'      => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Child elements', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_title' => esc_html__( 'Item Title 1', 'qi-addons-for-elementor' ),
							'item_text'  => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
							'additional' => 'icon',
							'item_icon'  => array(
								'value'   => 'far fa-paper-plane',
								'library' => 'fa-regular',
							),
						),
						array(
							'item_title' => esc_html__( 'Item Title 2', 'qi-addons-for-elementor' ),
							'item_text'  => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
							'additional' => 'icon',
							'item_icon'  => array(
								'value'   => 'far fa-paper-plane',
								'library' => 'fa-regular',
							),
						),
						array(
							'item_title' => esc_html__( 'Item Title 3', 'qi-addons-for-elementor' ),
							'item_text'  => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
							'additional' => 'icon',
							'item_icon'  => array(
								'value'   => 'far fa-paper-plane',
								'library' => 'fa-regular',
							),
						),
						array(
							'item_title' => esc_html__( 'Item Title 4', 'qi-addons-for-elementor' ),
							'item_text'  => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
							'additional' => 'icon',
							'item_icon'  => array(
								'value'   => 'far fa-paper-plane',
								'library' => 'fa-regular',
							),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'select',
							'name'          => 'additional',
							'title'         => esc_html__( 'Additional', 'qi-addons-for-elementor' ),
							'options'       => array(
								''       => esc_html__( 'No', 'qi-addons-for-elementor' ),
								'icon'   => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
								'number' => esc_html__( 'Number', 'qi-addons-for-elementor' ),
							),
							'default_value' => '',
						),
						array(
							'field_type' => 'icons',
							'name'       => 'item_icon',
							'title'      => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
							'dependency' => array(
								'show' => array(
									'additional' => array(
										'values'        => 'icon',
										'default_value' => '',
									),
								),
							),
						),
						array(
							'field_type' => 'number',
							'name'       => 'item_number',
							'title'      => esc_html__( 'Number', 'qi-addons-for-elementor' ),
							'dependency' => array(
								'show' => array(
									'additional' => array(
										'values'        => 'number',
										'default_value' => '',
									),
								),
							),
						),
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
							'field_type' => 'link',
							'name'       => 'item_link',
							'title'      => esc_html__( 'Link', 'qi-addons-for-elementor' ),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_color',
					'title'      => esc_html__( 'Icon Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-icon-holder' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_hover_color',
					'title'      => esc_html__( 'Icon Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} a:hover .qodef-e-icon-holder' => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-m-item > .qodef-e-icon-holder:hover' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icon_size',
					'title'      => esc_html__( 'Icon Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-icon-holder' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag', false ),
					'default_value' => 'h4',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_hover_color',
					'title'      => esc_html__( 'Title Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title:hover' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-title',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'number_color',
					'title'      => esc_html__( 'Number Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-number' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'number_typography',
					'title'      => esc_html__( 'Number Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-number',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-text',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'main_image_offset',
					'title'      => esc_html__( 'Image Top Offset', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => -300,
							'max' => 300,
						),
						'%'  => array(
							'min' => -300,
							'max' => 300,
						),
						'em' => array(
							'min' => -30,
							'max' => 30,
						),
						'vh' => array(
							'min' => -10,
							'max' => 10,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-image' => 'top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'image_side_margin',
					'title'      => esc_html__( 'Image Side Margin', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em', 'vw' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-image' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'item_space',
					'title'      => esc_html__( 'Item Space', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_bottom_margin',
					'title'      => esc_html__( 'Title Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->map_extra_options();
		}

		public function load_assets() {
			parent::load_assets();

			qi_addons_for_elementor_icon_load_assets();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['this_shortcode'] = $this;

			$items = $this->parse_repeater_items( $atts['children'] );
			if ( ! empty( $items ) ) {
				$atts['items'] = array_chunk( $items, ceil( count( $items ) / 2 ) );
			} else {
				$atts['items'] = array();
			}
			return qi_addons_for_elementor_get_template_part( 'shortcodes/item-showcase', 'templates/holder', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-item-showcase';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = 'yes' === $atts['appear_animation'] ? 'qodef-qi--has-appear ' : '';

			return implode( ' ', $holder_classes );
		}
	}
}
