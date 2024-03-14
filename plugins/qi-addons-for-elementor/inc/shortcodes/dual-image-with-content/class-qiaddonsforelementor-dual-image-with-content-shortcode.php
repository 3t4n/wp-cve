<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_dual_image_with_content_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_dual_image_with_content_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Dual_Image_With_Content_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_dual_image_with_content_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Dual_Image_With_Content_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/dual-image-with-content' );
			$this->set_base( 'qi_addons_for_elementor_dual_image_with_content' );
			$this->set_name( esc_html__( 'Dual Image with Content', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds dual image with content element', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Showcase', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/dual-image-with-content/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#dual_image_with_content' );

			$placeholder = get_option( 'qi_addons_for_elementor_placeholder_image' );

			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'title',
					'title'         => esc_html__( 'Title', 'qi-addons-for-elementor' ),
					'default_value' => qi_addons_for_elementor_get_example_text( 'title' ),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'text',
					'name'        => 'decoration_positions',
					'title'       => esc_html__( 'Positions of Decorated Words', 'qi-addons-for-elementor' ),
					'dynamic'     => false,
					'description' => esc_html__( 'Enter the positions of the words which you would like to be decorated in title. Separate the positions with commas (e.g. if you would like the first, third, and fourth word to have a decoration, you would enter "1,3,4")', 'qi-addons-for-elementor' ),
					'group'       => esc_html__( 'Additional', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'text',
					'name'        => 'color_positions',
					'title'       => esc_html__( 'Positions of Different Colored Words', 'qi-addons-for-elementor' ),
					'dynamic'     => false,
					'description' => esc_html__( 'Enter the positions of the words which you would like to be in different color in title. Separate the positions with commas (e.g. if you would like the first, third, and fourth word to have a different color, you would enter "1,3,4")', 'qi-addons-for-elementor' ),
					'group'       => esc_html__( 'Additional', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'link',
					'name'       => 'link',
					'title'      => esc_html__( 'Link', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Additional', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'text',
					'name'        => 'link_positions',
					'title'       => esc_html__( 'Positions of Links', 'qi-addons-for-elementor' ),
					'dynamic'     => false,
					'description' => esc_html__( 'Enter the position, or start and end position of the words which you would like to include in link. Separate the positions with commas (e.g. if you would like the first and second word to be a link, you would enter "1,2")', 'qi-addons-for-elementor' ),
					'group'       => esc_html__( 'Additional', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'textarea',
					'name'          => 'text',
					'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
					'default_value' => qi_addons_for_elementor_get_example_text(),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'bottom_section',
					'title'         => esc_html__( 'Select Bottom Section Template', 'qi-addons-for-elementor' ),
					'default_value' => '0',
					'options'       => qi_addons_for_elementor_generate_elementor_templates_control(),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'image',
					'name'          => 'main_image',
					'title'         => esc_html__( 'Main Image', 'qi-addons-for-elementor' ),
					'default_value' => $placeholder,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'main_images_proportion',
					'default_value' => 'full',
					'title'         => esc_html__( 'Main Image Proportions', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'list_image_dimension', false ),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'main_custom_image_width',
					'title'       => esc_html__( 'Main Custom Image Width', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image width in px', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'main_images_proportion' => array(
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
					'name'        => 'main_custom_image_height',
					'title'       => esc_html__( 'Main Custom Image Height', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image height in px', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'main_images_proportion' => array(
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
					'name'        => 'main_image_holder_height',
					'title'       => esc_html__( 'Main Image Holder Height', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image height in px.', 'qi-addons-for-elementor' ),
					'selectors'   => array(
						'{{WRAPPER}} .qodef-image-holder' => '--holder_height: {{SIZE}}px',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'main_image_holder_height_responsive',
					'title'      => esc_html__( 'Main Image Holder Height Responsive', 'qi-addons-for-elementor' ),
					'options'    => array(
						''       => esc_html__( 'Default', 'qi-addons-for-elementor' ),
						'custom' => esc_html__( 'Custom ', 'qi-addons-for-elementor' ),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'main_image_holder_height_1440',
					'title'       => esc_html__( 'Main Image Holder Height 1440', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image height in px.', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'main_image_holder_height_responsive' => array(
								'values'        => 'custom',
								'default_value' => '',
							),
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .qodef-image-holder' => '--holder_height_1440: {{SIZE}}px',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'main_image_holder_height_1366',
					'title'       => esc_html__( 'Main Image Holder Height 1366', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image height in px.', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'main_image_holder_height_responsive' => array(
								'values'        => 'custom',
								'default_value' => '',
							),
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .qodef-image-holder' => '--holder_height_1366: {{SIZE}}px',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'main_image_holder_height_1280',
					'title'       => esc_html__( 'Main Image Holder Height 1280', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image height in px.', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'main_image_holder_height_responsive' => array(
								'values'        => 'custom',
								'default_value' => '',
							),
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .qodef-image-holder' => '--holder_height_1280: {{SIZE}}px',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'main_image_holder_height_1024',
					'title'       => esc_html__( 'Main Image Holder Height 1024', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image height in px.', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'main_image_holder_height_responsive' => array(
								'values'        => 'custom',
								'default_value' => '',
							),
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .qodef-image-holder' => '--holder_height_1024: {{SIZE}}px',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'main_image_holder_height_768',
					'title'       => esc_html__( 'Main Image Holder Height 768', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image height in px.', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'main_image_holder_height_responsive' => array(
								'values'        => 'custom',
								'default_value' => '',
							),
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .qodef-image-holder' => '--holder_height_768: {{SIZE}}px',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'main_image_holder_height_680',
					'title'       => esc_html__( 'Main Image Holder Height 680', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image height in px.', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'main_image_holder_height_responsive' => array(
								'values'        => 'custom',
								'default_value' => '',
							),
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .qodef-image-holder' => '--holder_height_680: {{SIZE}}px',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'image',
					'name'          => 'second_image',
					'title'         => esc_html__( 'Second Image', 'qi-addons-for-elementor' ),
					'default_value' => $placeholder,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'second_images_proportion',
					'default_value' => 'medium',
					'title'         => esc_html__( 'Second Image Proportions', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'list_image_dimension', false ),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'second_custom_image_width',
					'title'       => esc_html__( 'Second Custom Image Width', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image width in px', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'second_images_proportion' => array(
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
					'name'        => 'second_custom_image_height',
					'title'       => esc_html__( 'Second Custom Image Height', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image height in px', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'second_images_proportion' => array(
								'values'        => 'custom',
								'default_value' => 'full',
							),
						),
					),
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
					'field_type' => 'slider',
					'name'       => 'second_image_max_width',
					'title'      => esc_html__( 'Second Image Max Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'vw' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-second-image img' => 'max-width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'choose',
					'name'       => 'alignment',
					'title'      => esc_html__( 'Alignment', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-dual-content' => 'text-align: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'content_position',
					'title'      => esc_html__( 'Content Position', 'qi-addons-for-elementor' ),
					'options'    => array(
						'content-left'  => esc_html__( 'Content Left from Image', 'qi-addons-for-elementor' ),
						'content-right' => esc_html__( 'Content Right from Image', 'qi-addons-for-elementor' ),
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'content_background_color',
					'title'      => esc_html__( 'Content Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-dual-content' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'top_content_width',
					'title'      => esc_html__( 'Top Content Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%' ),
					'responsive' => true,
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 700,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-inner-content' => 'max-width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_alignment',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag', false ),
					'default_value' => 'h2',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-inner-content > .qodef-m-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-inner-content > .qodef-m-title',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'color',
					'name'          => 'title_different_color',
					'title'         => esc_html__( 'Title Different Color', 'qi-addons-for-elementor' ),
					'selectors'     => array(
						'{{WRAPPER}} .qodef-m-inner-content .qodef-e-colored' => 'color: {{VALUE}};',
					),
					'default_value' => '#bababa',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_decoration',
					'title'         => esc_html__( 'Decoration', 'qi-addons-for-elementor' ),
					'options'       => array(
						'underline' => esc_html__( 'Underline', 'qi-addons-for-elementor' ),
						'italic'    => esc_html__( 'Italic', 'qi-addons-for-elementor' ),
						'bold'      => esc_html__( 'Bold', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'italic',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'link_underline_draw',
					'title'         => esc_html__( 'Link Underline Draw', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes' ),
					'default_value' => 'yes',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_title',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-inner-content > .qodef-m-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-inner-content > .qodef-m-text',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_subtitle',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-m-inner-content > .qodef-m-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'text_margin_bottom',
					'title'      => esc_html__( 'Text Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-inner-content > .qodef-m-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'content_padding',
					'title'      => esc_html__( 'Content Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-dual-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['title']          = $this->get_modified_title( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/dual-image-with-content', 'templates/dual-image-with-content', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-dual-image-with-content';
			$holder_classes[] = ! empty( $atts['content_position'] ) ? 'qodef--' . esc_attr( $atts['content_position'] ) : 'qodef--content-left';
			$holder_classes[] = ! empty( $atts['title_decoration'] ) ? 'qodef-decoration--' . esc_attr( $atts['title_decoration'] ) : '';
			$holder_classes[] = 'yes' === $atts['link_underline_draw'] ? 'qodef-link--underline-draw' : '';
			$holder_classes[] = 'yes' === $atts['appear_animation'] ? 'qodef-qi--has-appear ' : '';

			return implode( ' ', $holder_classes );
		}

		private function get_modified_title( $atts ) {
			$title = $atts['title'];

			if ( ! empty( $title ) ) {
				$split_title = explode( ' ', $title );

				if ( ! empty( $atts['decoration_positions'] ) ) {
					$decoration_positions = explode( ',', str_replace( ' ', '', $atts['decoration_positions'] ) );

					foreach ( $decoration_positions as $position ) {
						$position = intval( $position );
						if ( isset( $split_title[ $position - 1 ] ) && ! empty( $split_title[ $position - 1 ] ) ) {
							$split_title[ $position - 1 ] = '<span class="qodef-e-decorated">' . $split_title[ $position - 1 ] . '</span>';
						}
					}
				}

				if ( ! empty( $atts['color_positions'] ) ) {
					$color_positions = explode( ',', str_replace( ' ', '', $atts['color_positions'] ) );

					foreach ( $color_positions as $position ) {
						$position = intval( $position );
						if ( isset( $split_title[ $position - 1 ] ) && ! empty( $split_title[ $position - 1 ] ) ) {
							$split_title[ $position - 1 ] = '<span class="qodef-e-colored">' . $split_title[ $position - 1 ] . '</span>';
						}
					}
				}

				if ( ! empty( $atts['link_positions'] ) && ! empty( $atts['link']['url'] ) ) {
					$link_positions = explode( ',', str_replace( ' ', '', $atts['link_positions'] ) );

					if ( count( $link_positions ) === 2 ) {
						$begin = $link_positions[0];
						$end   = $link_positions[1];
						if ( ! empty( $split_title[ $begin - 1 ] ) && ! empty( $split_title [ $end - 1 ] ) ) {
							$split_title[ $begin - 1 ] = '<a class="qodef-e-link" href="' . esc_url( $atts['link']['url'] ) . '" ' . qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $atts['link'] ) ) . '>' . $split_title[ $begin - 1 ];
							$split_title[ $end - 1 ]   = $split_title[ $end - 1 ] . '</a>';
						}
					} else {
						foreach ( $link_positions as $position ) {
							$position = intval( $position );

							if ( isset( $split_title[ $position - 1 ] ) && ! empty( $split_title[ $position - 1 ] ) ) {
								$split_title[ $position - 1 ] = '<a class="qodef-e-link" href="' . esc_url( $atts['link']['url'] ) . '" ' . qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $atts['link'] ) ) . '>' . $split_title[ $position - 1 ] . '</a>';
							}
						}
					}
				}

				$title = implode( ' ', $split_title );
			}

			return $title;
		}
	}
}
