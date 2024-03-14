<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_cards_slider_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_cards_slider_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Cards_Slider_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_cards_slider_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Cards_Slider_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/cards-slider' );
			$this->set_base( 'qi_addons_for_elementor_cards_slider' );
			$this->set_name( esc_html__( 'Cards Slider', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds cards gallery holder', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Showcase', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/cards-slider/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#cards_slider' );
			$this->set_video( 'https://www.youtube.com/watch?v=6C9KOZEvd10' );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'orientation',
					'title'         => esc_html__( 'Image Shuffle Style', 'qi-addons-for-elementor' ),
					'options'       => array(
						'1'  => esc_html__( 'Shuffle Right', 'qi-addons-for-elementor' ),
						'-1' => esc_html__( 'Shuffle Left', 'qi-addons-for-elementor' ),
					),
					'default_value' => '1',
				)
			);

			$placeholder = get_option( 'qi_addons_for_elementor_placeholder_image' );

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Image Items', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_image' => $placeholder,
						),
						array(
							'item_image' => $placeholder,
						),
						array(
							'item_image' => $placeholder,
						),
					),
					'items'         => array(
						array(
							'field_type' => 'link',
							'name'       => 'item_link',
							'title'      => esc_html__( 'Link', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'image',
							'name'          => 'item_image',
							'title'         => esc_html__( 'Item Image', 'qi-addons-for-elementor' ),
							'default_value' => $placeholder,
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'images_proportion',
					'default_value' => 'medium',
					'title'         => esc_html__( 'Image Proportions', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'list_image_dimension', false ),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'custom_image_width',
					'title'       => esc_html__( 'Custom Image Width', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image width in px', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'images_proportion' => array(
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
					'name'        => 'custom_image_height',
					'title'       => esc_html__( 'Custom Image Height', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image height in px', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'images_proportion' => array(
								'values'        => 'custom',
								'default_value' => 'full',
							),
						),
					),
				)
			);
			$offset_selectors = array();

			for ( $i = 10; $i > 0; $i -- ) {
				$offset_selectors[ '{{WRAPPER}} .qodef-orientation--one-side .qodef-m-card:nth-last-child(' . $i . ')' ] = 'transform: translateX(calc( {{orientation.VALUE}} * ' . ( $i - 1 ) . ' * {{SIZE}}px)) translateY(calc( -1 * ' . ( $i - 1 ) . ' * {{SIZE}}px));';
			}
			$this->set_option(
				array(
					'field_type'    => 'slider',
					'name'          => 'offset_step',
					'title'         => esc_html__( 'Offset Step', 'qi-addons-for-elementor' ),
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'size_units'    => array( 'px' ),
					'range'         => array(
						'px' => array(
							'min'  => 1,
							'max'  => 100,
							'step' => 1,
						),
					),
					'selectors'     => $offset_selectors,
					'default_value' => array(
						'unit' => '%',
						'size' => '25',
					),
					'responsive'    => true,
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'slider_navigation_arrow_prev',
					'title'         => esc_html__( 'Navigation Arrow Previous', 'qi-addons-for-elementor' ),
					'default_value' => array(),
					'group'         => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'slider_navigation_arrow_next',
					'title'         => esc_html__( 'Navigation Arrow Next', 'qi-addons-for-elementor' ),
					'default_value' => array(),
					'group'         => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_navigation_style',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'arrows_style_tabs',
					'title'      => esc_html__( 'Arrows Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'arrows_style_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'slider_navigation_arrows_color',
					'title'      => esc_html__( 'Navigation Arrow Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-navigation .qodef-nav' => 'color: {{VALUE}}',
					),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'slider_navigation_arrows_background_color',
					'title'      => esc_html__( 'Navigation Arrow Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-navigation .qodef-nav' => 'background-color: {{VALUE}}',
					),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'arrows_style_tab_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'arrows_style_tab_hover',
					'title'      => esc_html__( 'Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'slider_navigation_arrows_hover_color',
					'title'      => esc_html__( 'Navigation Arrow Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-navigation .qodef-nav:hover' => 'color: {{VALUE}}',
					),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'slider_navigation_arrows_hover_background_color',
					'title'      => esc_html__( 'Navigation Arrow Background Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-navigation .qodef-nav:hover' => 'background-color: {{VALUE}}',
					),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'slider_navigation_arrows_hover_move',
					'title'      => esc_html__( 'Enable Hover Arrow Move', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'arrows_style_tab_hover_end',
					'title'      => esc_html__( 'Hover End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'arrows_style_tabs_end',
					'title'      => esc_html__( 'Arrows End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_navigation_style_end',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'slider_navigation_arrows_size',
					'title'      => esc_html__( 'Navigation Arrow Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-navigation .qodef-nav' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'slider_navigation_arrows_holder_width',
					'title'      => esc_html__( 'Navigation Arrow Holder Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-navigation .qodef-nav' => 'width: {{SIZE}}{{UNIT}} !important;',
					),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'slider_navigation_arrows_holder_height',
					'title'      => esc_html__( 'Navigation Arrow Holder Height', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-navigation .qodef-nav' => 'height: {{SIZE}}{{UNIT}} !important;',
					),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_spacing_navigation_style',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'slider_navigation_together_margin_top',
					'title'      => esc_html__( 'Navigation Margin Top', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-navigation' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'slider_navigation_together_left_offset',
					'title'      => esc_html__( 'Navigation Left Offset', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em', '%' ),
					'range'      => array(
						'px' => array(
							'min' => - 100,
							'max' => 100,
						),
						'em' => array(
							'min' => - 5,
							'max' => 5,
						),
						'%'  => array(
							'min' => - 50,
							'max' => 50,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-navigation' => 'margin-left: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'slider_navigation_together_space_between',
					'title'      => esc_html__( 'Space Between Arrows', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-navigation > .qodef--prev' => 'margin-right: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->map_extra_options();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );
			$atts['data_attrs']     = $this->get_data_attrs( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/cards-slider', 'templates/cards-slider', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-cards-slider';
			$holder_classes[] = 'qodef-orientation--one-side';
			$holder_classes[] = 'yes' === $atts['slider_navigation_arrows_hover_move'] ? 'qodef-navigation--hover-move' : '';

			$orientation      = '-1' == $atts['orientation'] ? 'left' : 'right';
			$holder_classes[] = ! empty( $atts['orientation'] ) ? 'qodef-orientation--' . $orientation : 'qodef-orientation--right';

			return implode( ' ', $holder_classes );
		}

		private function get_data_attrs( $atts ) {
			$data = array();

			$orientation = '-1' == $atts['orientation'] ? 'left' : 'right';

			$data['data-orientation'] = ! empty( $atts['orientation'] ) ? $orientation : 'right';
			$data['data-offset']      = ! empty( $atts['offset_step']['size'] ) ? $atts['offset_step']['size'] : 30;

			return $data;
		}
	}
}
