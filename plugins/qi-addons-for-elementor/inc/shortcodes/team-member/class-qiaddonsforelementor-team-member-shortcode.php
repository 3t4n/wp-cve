<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_team_member_shortcode' ) ) {
	/**
	 * Function that isadding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_team_member_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Team_Member_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_team_member_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Team_Member_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_team_member_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_team_member_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/team-member' );
			$this->set_base( 'qi_addons_for_elementor_team_member' );
			$this->set_name( esc_html__( 'Team', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays team member', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Business', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/team/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#team' );
			$this->set_necessary_styles( qi_addons_for_elementor_icon_necessary_styles() );
			$this->set_video( 'https://www.youtube.com/watch?v=RCLVNvw03Rg' );

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
					'default_value' => 'info-below',
					'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'name',
					'title'         => esc_html__( 'Name', 'qi-addons-for-elementor' ),
					'default_value' => esc_html__( 'John Doe', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'role',
					'title'         => esc_html__( 'Role', 'qi-addons-for-elementor' ),
					'default_value' => esc_html__( 'Team Manager', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'image',
					'name'       => 'image',
					'title'      => esc_html__( 'Image', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'image_proportion',
					'default_value' => 'full',
					'title'         => esc_html__( 'Image Proportions', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'list_image_dimension', false, array( 'custom' ) ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Social', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'icon' => array(
								'value'   => 'far fa-address-book',
								'library' => 'fa-regular',
							),
						),
						array(
							'icon' => array(
								'value'   => 'far fa-envelope',
								'library' => 'fa-regular',
							),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'icons',
							'name'          => 'icon',
							'title'         => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
							'default_value' => array(
								'value'   => 'far fa-envelope',
								'library' => 'fa-regular',
							),
						),
						array(
							'field_type'    => 'link',
							'name'          => 'link',
							'title'         => esc_html__( 'Link', 'qi-addons-for-elementor' ),
							'default_value' => array(
								'url'         => '',
								'is_external' => 'on',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'name_tag',
					'title'         => esc_html__( 'Name Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag', false ),
					'default_value' => 'h4',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'name_color',
					'title'      => esc_html__( 'Name Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'name_typography',
					'title'      => esc_html__( 'Name Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-title',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'role_color',
					'title'      => esc_html__( 'Role Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-role' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'role_typography',
					'title'      => esc_html__( 'Role Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-role',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icons_color',
					'title'      => esc_html__( 'Icons Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-social-icon' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icons_hover_color',
					'title'      => esc_html__( 'Icons Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-social-icon:hover' => 'color: {{VALUE}};',
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
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-social-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'image_hover',
					'title'      => esc_html__( 'Image Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'options'    => array(
						''      => esc_html__( 'None', 'qi-addons-for-elementor' ),
						'zoom'  => esc_html__( 'Zoom', 'qi-addons-for-elementor' ),
						'scale' => esc_html__( 'Scale', 'qi-addons-for-elementor' ),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'role_margin_bottom',
					'title'      => esc_html__( 'Role Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-role' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'name_margin_bottom',
					'title'      => esc_html__( 'Name Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icons_space_between',
					'title'      => esc_html__( 'Space Between Icons', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-social-icons > *:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
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
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );

			return qi_addons_for_elementor_get_template_part( 'shortcodes/team-member', 'variations/' . $atts['layout'] . '/layouts/' . $atts['layout'], '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-team-member';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-item-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['image_hover'] ) ? 'qodef-image--hover-' . $atts['image_hover'] : '';

			return implode( ' ', $holder_classes );
		}
	}
}
