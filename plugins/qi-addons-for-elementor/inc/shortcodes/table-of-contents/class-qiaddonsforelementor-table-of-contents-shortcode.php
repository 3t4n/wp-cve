<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_table_of_contents_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_table_of_contents_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Table_Of_Contents_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_table_of_contents_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Table_Of_Contents_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/table-of-contents' );
			$this->set_base( 'qi_addons_for_elementor_table_of_contents' );
			$this->set_name( esc_html__( 'Table of Contents', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds section title element', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'SEO', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/table-of-contents/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#table_of_contents' );
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
					'field_type'    => 'text',
					'name'          => 'subtitle',
					'title'         => esc_html__( 'Subtitle', 'qi-addons-for-elementor' ),
					'default_value' => qi_addons_for_elementor_get_example_text( 'subtitle' ),
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
					'name'          => 'only_content',
					'title'         => esc_html__( 'Limit ToC to Main Page Content', 'qi-addons-for-elementor' ),
					'options'       => array(
						'no'  => esc_html__( 'No', 'qi-addons-for-elementor' ),
						'yes' => esc_html__( 'Yes', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'no',
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'textarea',
					'name'        => 'excluded_tags',
					'title'       => esc_html__( 'Excluded Tags/Classes/Ids', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter h tags, classes (in .example form) or ids (in #example form) that you want to exclude, separated by comma', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-qi-table-of-contents' => 'text-align: {{VALUE}};',
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
						'{{WRAPPER}} .qodef-qi-table-of-contents .qodef-m-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-table-of-contents .qodef-m-title',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
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
					'field_type'    => 'select',
					'name'          => 'subtitle_tag',
					'title'         => esc_html__( 'Subtitle Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag', false ),
					'default_value' => 'h5',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'subtitle_color',
					'title'      => esc_html__( 'Subtitle Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-table-of-contents .qodef-m-subtitle' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'subtitle_typography',
					'title'      => esc_html__( 'Subtitle Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-table-of-contents .qodef-m-subtitle',
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
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-table-of-contents > .qodef-m-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-table-of-contents > .qodef-m-text',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_margin_top',
					'title'      => esc_html__( 'Title Margin Top', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-table-of-contents .qodef-m-title' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'text_margin_top',
					'title'      => esc_html__( 'Text Margin Top', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder > .qodef-m-text' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'text_padding',
					'title'      => esc_html__( 'Text Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title-holder > .qodef-m-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'list_type',
					'title'         => esc_html__( 'List Type', 'qi-addons-for-elementor' ),
					'options'       => array(
						'ul' => esc_html__( 'Unordered', 'qi-addons-for-elementor' ),
						'ol' => esc_html__( 'Ordered', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'ul',
					'group'         => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'list_style_position',
					'title'         => esc_html__( 'List Style Position', 'qi-addons-for-elementor' ),
					'options'       => array(
						'inside'  => esc_html__( 'Inside', 'qi-addons-for-elementor' ),
						'outside' => esc_html__( 'Outside', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'inside',
					'group'         => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'list_style_type',
					'title'         => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
					'options'       => array(
						'disc'   => esc_html__( 'Disc', 'qi-addons-for-elementor' ),
						'circle' => esc_html__( 'Circle', 'qi-addons-for-elementor' ),
						'square' => esc_html__( 'Square', 'qi-addons-for-elementor' ),
						'none'   => esc_html__( 'None', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'disc',
					'dependency'    => array(
						'show' => array(
							'list_type' => array(
								'values'       => 'ul',
								'defaul_value' => 'ul',
							),
						),
					),
					'selectors'     => array(
						'{{WRAPPER}} .qodef-m-table-content ul' => 'list-style-type: {{VALUE}};',
					),
					'group'         => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'list_style_hover_underline',
					'title'      => esc_html__( 'List Hover Underline', 'qi-addons-for-elementor' ),
					'options'    => array(
						'yes' => esc_html__( 'Yes', 'qi-addons-for-elementor' ),
						'no'  => esc_html__( 'No', 'qi-addons-for-elementor' ),
					),
					'group'      => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_color',
					'title'      => esc_html__( 'Item Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-table-content li' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Items Typography Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_hover_color',
					'title'      => esc_html__( 'Item Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-table-content a:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-m-table-content a:after' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Items Typography Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'item_typography',
					'title'      => esc_html__( 'Item Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-table-content',
					'group'      => esc_html__( 'Items Typography Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'list_margin_top',
					'title'      => esc_html__( 'List Margin Top', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-table-content' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'list_margin_left',
					'title'      => esc_html__( 'List Margin Left', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-table-content ul' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-m-table-content ol' => 'margin-left: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'space_between_items',
					'title'      => esc_html__( 'Space Between Items', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-table-content li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-m-table-content li > ul' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$excluded             = $this->get_excluded( $atts['excluded_tags'] );
			$atts['exclude_tags'] = implode( ',', $excluded['tags'] );
			$atts['exclude_cid']  = implode( ',', $excluded['cid'] );

			$atts['holder_classes'] = $this->get_holder_classes( $atts );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/table-of-contents', 'templates/table-of-contents', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-table-of-contents';
			$holder_classes[] = ! empty( $atts['list_style_position'] ) ? 'qodef-list-position--' . $atts['list_style_position'] : 'qodef-list-position--inside';
			$holder_classes[] = ( 'yes' === $atts['list_style_hover_underline'] ) ? 'qodef-list-underline' : '';
			$holder_classes[] = ( 'yes' === $atts['only_content'] ) ? 'qodef--only-content' : '';

			return implode( ' ', $holder_classes );
		}

		private function get_excluded( $excluded_tags ) {
			$excluded = array(
				'tags' => array(),
				'cid'  => array(
					'.qodef-exclude',
					'.qodef-page-title',
					'.qodef-e-author',
					'.qodef-testimonials-list',
					'.qodef-testimonials-slider',
				),
			);

			if ( ! empty( $excluded_tags ) ) {
				$excluded_array = explode( ',', $excluded_tags );

				foreach ( $excluded_array as $exc ) {
					$exc = trim( $exc );

					//if class or id used add it to cid part, else add it to tags
					if ( 0 === strpos( $exc, '.' ) || 0 === strpos( $exc, '#' ) ) {
						$excluded['cid'][] = $exc;
					} else {
						$excluded['tags'][] = $exc;
					}
				}
			}

			return $excluded;
		}
	}
}
