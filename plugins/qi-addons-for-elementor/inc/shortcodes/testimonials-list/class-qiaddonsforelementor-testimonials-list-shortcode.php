<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_testimonials_list_shortcode' ) ) {
	/**
	 * Function that is adding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_testimonials_list_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Testimonials_List_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_testimonials_list_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_List_Shortcode' ) ) {
	class QiAddonsForElementor_Testimonials_List_Shortcode extends QiAddonsForElementor_List_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_testimonials_list_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_testimonials_list_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/testimonials-list' );
			$this->set_base( 'qi_addons_for_elementor_testimonials_list' );
			$this->set_name( esc_html__( 'Testimonials', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays list of testimonials', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Business', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/testimonials/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#testimonials' );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->map_list_options(
				array(
					'exclude_behavior' => array( 'masonry' ),
					'exclude_option'   => array( 'images_proportion', 'enable_pagination' ),
				)
			);

			$placeholder = get_option( 'qi_addons_for_elementor_placeholder_image' );

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Items', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_title'             => esc_html__( 'Example Title 1', 'qi-addons-for-elementor' ),
							'item_text'              => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
							'item_author'            => esc_html__( 'Author', 'qi-addons-for-elementor' ),
							'item_author_image'      => $placeholder,
							'item_author_occupation' => esc_html__( 'occupation', 'qi-addons-for-elementor' ),
						),
						array(
							'item_title'             => esc_html__( 'Example Title 2', 'qi-addons-for-elementor' ),
							'item_text'              => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
							'item_author'            => esc_html__( 'Author', 'qi-addons-for-elementor' ),
							'item_author_image'      => $placeholder,
							'item_author_occupation' => esc_html__( 'occupation', 'qi-addons-for-elementor' ),
						),
						array(
							'item_title'             => esc_html__( 'Example Title 3', 'qi-addons-for-elementor' ),
							'item_text'              => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
							'item_author'            => esc_html__( 'Author', 'qi-addons-for-elementor' ),
							'item_author_image'      => $placeholder,
							'item_author_occupation' => esc_html__( 'occupation', 'qi-addons-for-elementor' ),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'text',
							'name'          => 'item_title',
							'title'         => esc_html__( 'Title', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Example Title', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'textarea',
							'name'          => 'item_text',
							'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
							'default_value' => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
						),
						array(
							'field_type'    => 'text',
							'name'          => 'item_author',
							'title'         => esc_html__( 'Author', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Author', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'text',
							'name'          => 'item_author_occupation',
							'title'         => esc_html__( 'Author Occupation', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'occupation', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'image',
							'name'          => 'item_author_image',
							'title'         => esc_html__( 'Author Image', 'qi-addons-for-elementor' ),
							'default_value' => $placeholder,
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_quote_color',
							'title'      => esc_html__( 'Quote Color', 'qi-addons-for-elementor' ),
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-quote' => 'color: {{VALUE}};',
							),
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_title_color',
							'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-title' => 'color: {{VALUE}};',
							),
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_text_color',
							'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-text' => 'color: {{VALUE}};',
							),
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_author_color',
							'title'      => esc_html__( 'Author Color', 'qi-addons-for-elementor' ),
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-author-name' => 'color: {{VALUE}};',
							),
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_author_occupation_color',
							'title'      => esc_html__( 'Author Occupation Color', 'qi-addons-for-elementor' ),
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-author-job' => 'color: {{VALUE}};',
							),
						),
						array(
							'field_type'  => 'background',
							'name'        => 'item_background',
							'title'       => esc_html__( 'Boxed Background', 'qi-addons-for-elementor' ),
							'types'       => array( 'classic', 'gradient' ),
							'selector'    => '{{WRAPPER}} .qodef-item-layout--boxed {{CURRENT_ITEM}} .qodef-e-inner',
							'description' => esc_html__( 'This background will be used on "boxed" layout only', 'qi-addons-for-elementor' ),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'item_quote_icon',
					'title'         => esc_html__( 'Quote Icon', 'qi-addons-for-elementor' ),
					'default_value' => array(),
					'group'         => esc_html__( 'Quote Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_quote_color',
					'title'      => esc_html__( 'Quote Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-quote' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Quote Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'item_quote_font_size',
					'title'      => esc_html__( 'Quote Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-quote' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Quote Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-e-title' => 'color: {{VALUE}};',
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
					'field_type' => 'slider',
					'name'       => 'title_margin_bottom',
					'title'      => esc_html__( 'Title Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'item_text_tag',
					'title'         => esc_html__( 'Text Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag', false ),
					'default_value' => 'h3',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_text_color',
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
					'name'       => 'item_text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-text',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'item_author_tag',
					'title'         => esc_html__( 'Author Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag', false ),
					'default_value' => 'h5',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_author_color',
					'title'      => esc_html__( 'Author Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-author-name' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'item_author_typography',
					'title'      => esc_html__( 'Author Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-author-name',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_author_occupation_color',
					'title'      => esc_html__( 'Author Occupation Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-author-job' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'item_author_occupation_typography',
					'title'      => esc_html__( 'Author Occupation Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-author-job',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'text_margin_bottom',
					'title'      => esc_html__( 'Item Text Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'text_padding',
					'title'      => esc_html__( 'Item Text padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->map_layout_options(
				array(
					'layouts'        => $this->get_layouts(),
					'exclude_option' => array( 'title_tag', 'title_color', 'title_hover_color', 'title_typography' ),
				)
			);
			$this->map_extra_options();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );

			$atts = $this->get_atts();

			$atts['unique']         = wp_unique_id();
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );

			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'shortcodes/testimonials-list', 'templates/content', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-testimonials-list';

			$list_classes   = $this->get_list_classes( $atts );
			$holder_classes = array_merge( $holder_classes, $list_classes );

			return implode( ' ', $holder_classes );
		}

		public function get_item_classes( $atts ) {
			$item_classes = $this->init_item_classes();

			$list_item_classes = $this->get_list_item_classes( $atts );

			$item_classes = array_merge( $item_classes, $list_item_classes );

			return implode( ' ', $item_classes );
		}
	}
}
