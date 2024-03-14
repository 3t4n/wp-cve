<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_how_to_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_how_to_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_How_To_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_how_to_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_How_To_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/how-to' );
			$this->set_base( 'qi_addons_for_elementor_how_to' );
			$this->set_name( esc_html__( 'How-to Schema', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds How-to schema', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'SEO', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/how-to-schema/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#how_to_schema' );

			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'checkbox',
					'name'       => 'enable_schema',
					'title'      => esc_html__( 'Enable JSON Schema', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
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
					'field_type' => 'choose',
					'name'       => 'alignment',
					'title'      => esc_html__( 'Alignment', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-how-to' => 'text-align: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
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
						'{{WRAPPER}} .qodef-m-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-title',
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
					'field_type' => 'color',
					'name'       => 'subtitle_color',
					'title'      => esc_html__( 'Subtitle Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-subtitle' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'subtitle_typography',
					'title'      => esc_html__( 'Subtitle Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-subtitle',
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
						'{{WRAPPER}} .qodef-m-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-text',
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
						'{{WRAPPER}} .qodef-m-title' => 'margin-top: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .qodef-m-text' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_holder_margin_bottom',
					'title'      => esc_html__( 'Title Holder Margin Bottom', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-title-holder' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'items_space',
					'title'      => esc_html__( 'Items Space', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-step:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$placeholder = get_option( 'qi_addons_for_elementor_placeholder_image' );

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Steps', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_title'   => esc_html__( ' Item Title 1', 'qi-addons-for-elementor' ),
							'item_content' => qi_addons_for_elementor_get_example_text(),
							'item_image'   => $placeholder,
						),
						array(
							'item_title'   => esc_html__( ' Item Title 2', 'qi-addons-for-elementor' ),
							'item_content' => qi_addons_for_elementor_get_example_text(),
							'item_image'   => $placeholder,
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'text',
							'name'          => 'item_title',
							'title'         => esc_html__( 'Step Title', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( ' Item Title', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'textarea',
							'name'          => 'item_content',
							'title'         => esc_html__( 'Step Content', 'qi-addons-for-elementor' ),
							'default_value' => qi_addons_for_elementor_get_example_text(),
						),
						array(
							'field_type'    => 'image',
							'name'          => 'item_image',
							'title'         => esc_html__( 'Step Image', 'qi-addons-for-elementor' ),
							'default_value' => $placeholder,
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'item_title_tag',
					'title'         => esc_html__( 'Step Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'h4',
					'group'         => esc_html__( 'Steps Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_title_color',
					'title'      => esc_html__( 'Step Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-step-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Steps Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'item_title_typography',
					'title'      => esc_html__( 'Step Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-step-title',
					'group'      => esc_html__( 'Steps Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_step_title',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Steps Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_text_color',
					'title'      => esc_html__( 'Step Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-step-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Steps Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'item_text_typography',
					'title'      => esc_html__( 'Step Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-step-text',
					'group'      => esc_html__( 'Steps Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'step_title_margin_bottom',
					'title'      => esc_html__( 'Step Title Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-step-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Steps Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'step_text_margin_bottom',
					'title'      => esc_html__( 'Step Text Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-step-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Steps Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_step_text',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Steps Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'      => 'box_shadow',
					'name'            => 'image_shadow',
					'title'           => esc_html__( 'Step Image Shadow', 'qi-addons-for-elementor' ),
					'group'           => esc_html__( 'Steps Style', 'qi-addons-for-elementor' ),
					'exclude_options' => array(
						'box_shadow_position',
					),
					'selector'        => '{{WRAPPER}} .qodef-e-step-image',
				)
			);
		}

		public function render( $options, $content = null ) {
			parent::render( $options );

			$atts                   = $this->get_atts();
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );

			$atts['schema'] = $this->get_how_to_schema( $atts );

			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'shortcodes/how-to', 'templates/how-to', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-how-to';

			return implode( ' ', $holder_classes );
		}

		public function build_title_id( $item, $title ) {
			$id = '';

			if ( ! empty( $item['item_title'] ) ) {
				if ( ! empty( $title ) ) {
					$id .= $title . ' ';
				}
				$id .= $item['item_title'];

				$id = str_replace( ' ', '_', trim( $id ) );

			}

			return $id;
		}

		private function get_how_to_schema( $atts ) {
			if ( 'yes' === $atts['enable_schema'] && ! empty( $atts['title'] ) && ! empty( $atts['items'] ) ) {
				$schema = array(
					'@context' => 'https://schema.org',
					'@type'    => 'HowTo',
					'name'     => esc_html( $atts['title'] ),
				);

				if ( ! empty( $atts['text'] ) ) {
					$schema['description'] = esc_html( $atts['text'] );
				}

				$schema['step'] = array();

				if ( ! empty( $atts['items'] ) ) {
					foreach ( $atts['items'] as $item ) {
						if ( ! empty( $item['item_title'] ) ) {
							$link = get_permalink() . '#' . $this->build_title_id( $item, $atts['title'] );

							$schema['step'][] = array(
								'@type' => 'HowToStep',
								'name'  => esc_html( $item['item_title'] ),
								'text'  => esc_html( $item['item_content'] ),
								'image' => qi_addons_for_elementor_get_attachment_image_url( $item['item_image'], 'full' ),
								'url'   => esc_url( $link ),
							);
						}
					}
				}

				return $schema;
			} else {
				return false;
			}
		}
	}
}
