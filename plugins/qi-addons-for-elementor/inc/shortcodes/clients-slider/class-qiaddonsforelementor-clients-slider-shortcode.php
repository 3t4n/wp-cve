<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_clients_slider_shortcode' ) ) {
	/**
	 * Function that is adding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_clients_slider_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Clients_Slider_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_clients_slider_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Slider_Shortcode' ) ) {
	class QiAddonsForElementor_Clients_Slider_Shortcode extends QiAddonsForElementor_Slider_Shortcode {

		public function __construct() {
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_clients_slider_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/clients-slider' );
			$this->set_base( 'qi_addons_for_elementor_clients_slider' );
			$this->set_name( esc_html__( 'Clients Carousel', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays carousel of clients', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Business', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/clients-carousel/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#clients_carousel' );
			$this->set_video( 'https://www.youtube.com/watch?v=WT8ngVYlJ_0' );
			$this->set_necessary_styles( qi_addons_for_elementor_icon_necessary_styles() );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->map_slider_options(
				array(
					'group'          => esc_html__( 'Slider Settings', 'qi-addons-for-elementor' ),
					'exclude_option' => array( 'enable_pagination' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_boxed',
					'title'         => esc_html__( 'Enable Boxed Items', 'qi-addons-for-elementor' ),
					'options'       => array(
						'no'  => esc_html__( 'No', 'qi-addons-for-elementor' ),
						'yes' => esc_html__( 'Yes', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'no',
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'client_hover_type',
					'title'         => esc_html__( 'Hover Type', 'qi-addons-for-elementor' ),
					'options'       => array(
						''                => esc_html__( 'None', 'qi-addons-for-elementor' ),
						'change-image'    => esc_html__( 'Change Image', 'qi-addons-for-elementor' ),
						'opacity'         => esc_html__( 'Opacity', 'qi-addons-for-elementor' ),
						'scale'           => esc_html__( 'Scale', 'qi-addons-for-elementor' ),
						'roll-horizontal' => esc_html__( 'Horizontal Rollover', 'qi-addons-for-elementor' ),
						'roll-vertical'   => esc_html__( 'Vertical Rollover', 'qi-addons-for-elementor' ),
					),
					'default-value' => '',
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'choose',
					'name'          => 'alignment',
					'title'         => esc_html__( 'Alignment', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
					'selectors'     => array(
						'{{WRAPPER}} .qodef-e-inner' => 'text-align: {{VALUE}};',
					),
					'default_value' => 'center',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'background_color',
					'title'      => esc_html__( 'Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef--boxed .qodef-e-inner' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'hide' => array(
							'enable_boxed' => array(
								'values'        => 'no',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Boxed Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'item_padding',
					'title'      => esc_html__( 'Item Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef--boxed .qodef-e-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'dependency' => array(
						'hide' => array(
							'enable_boxed' => array(
								'values'        => 'no',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Boxed Style', 'qi-addons-for-elementor' ),
				)
			);

			$placeholder = get_option( 'qi_addons_for_elementor_placeholder_image' );

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Client', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'client_main_image' => $placeholder,
							'client_title'      => esc_html__( 'Example Title 1', 'qi-addons-for-elementor' ),
						),
						array(
							'client_main_image' => $placeholder,
							'client_icon'       => array(
								'value'   => 'fas fa-star',
								'library' => 'fa-solid',
							),
							'client_title'      => esc_html__( 'Example Title 2', 'qi-addons-for-elementor' ),
						),
						array(
							'client_main_image' => $placeholder,
							'client_title'      => esc_html__( 'Example Title 3', 'qi-addons-for-elementor' ),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'image',
							'name'          => 'client_main_image',
							'title'         => esc_html__( 'Client Image', 'qi-addons-for-elementor' ),
							'default_value' => $placeholder,
						),
						array(
							'field_type' => 'image',
							'name'       => 'client_hover_image',
							'title'      => esc_html__( 'Client Hover Image', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'text',
							'name'          => 'client_title',
							'title'         => esc_html__( 'Client Title', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Example Title', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'link',
							'name'       => 'client_link',
							'title'      => esc_html__( 'Client Link', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'icons',
							'name'       => 'client_icon',
							'title'      => esc_html__( 'Client Icon', 'qi-addons-for-elementor' ),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'title_tag',
					'title'      => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
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
					'field_type'    => 'select',
					'name'          => 'title_hover_underline',
					'title'         => esc_html__( 'Title Hover Underline', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes' ),
					'default_value' => 'no',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_hover_underline_duration',
					'title'         => esc_html__( 'Title Underline Animation Duration', 'qi-addons-for-elementor' ),
					'options'       => array(
						'short' => esc_html__( 'Short', 'qi-addons-for-elementor' ),
						'long'  => esc_html__( 'Long', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'short',
					'dependency'    => array(
						'hide' => array(
							'title_hover_underline' => array(
								'values'        => 'no',
								'default_value' => 'no',
							),
						),
					),
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'images_margin_bottom',
					'title'      => esc_html__( 'Images Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-images-holder' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_color',
					'title'      => esc_html__( 'Icon Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-icon' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-e-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'         => 'dimensions',
					'name'               => 'icon_position',
					'title'              => esc_html__( 'Icon Position', 'qi-addons-for-elementor' ),
					'allowed_dimensions' => array( 'top', 'right' ),
					'size_units'         => array( 'px', '%', 'em' ),
					'responsive'         => true,
					'selectors'          => array(
						'{{WRAPPER}} .qodef-e-icon' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}};',
					),
					'group'              => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
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
			$atts['slider_attr']    = $this->get_slider_data( $atts );

			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'shortcodes/clients-slider', 'templates/content', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-clients-slider';
			$holder_classes[] = ( 'yes' === $atts['enable_boxed'] ) ? 'qodef--boxed' : '';
			$holder_classes[] = ! empty( $atts['client_hover_type'] ) ? 'qodef--hover-' . $atts['client_hover_type'] : '';
			$holder_classes[] = 'yes' === $atts['title_hover_underline'] ? 'qodef-title--hover-underline' : '';
			$holder_classes[] = ! empty( $atts['title_hover_underline_duration'] ) ? 'qodef-title--hover-' . $atts['title_hover_underline_duration'] : '';

			$list_classes   = $this->get_slider_classes( $atts );
			$holder_classes = array_merge( $holder_classes, $list_classes );

			return implode( ' ', $holder_classes );
		}

		public function get_item_classes( $atts ) {
			$item_classes = $this->init_item_classes();

			$list_item_classes = $this->get_slider_item_classes( $atts );

			$item_classes = array_merge( $item_classes, $list_item_classes );

			return implode( ' ', $item_classes );
		}
	}
}
