<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_slider_shortcode' ) ) {
	/**
	 * Function that isadding shortcode into shortcodes slider for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blog_slider_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Blog_Slider_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_blog_slider_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Slider_Shortcode' ) ) {
	class QiAddonsForElementor_Blog_Slider_Shortcode extends QiAddonsForElementor_Slider_Shortcode {

		public function __construct() {
			$this->set_post_type( 'post' );
			$this->set_post_type_taxonomy( 'category' );
			$this->set_post_type_additional_taxonomies( array( 'post_tag' ) );
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_blog_slider_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_blog_slider_extra_options', array(), $this ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_INC_URL_PATH . '/blog/shortcodes/blog-slider' );
			$this->set_base( 'qi_addons_for_elementor_blog_slider' );
			$this->set_name( esc_html__( 'Blog Carousel', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays carousel of blog posts', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Business', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/blog-carousel/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#blog_carousel' );
			$this->set_scripts(
				apply_filters(
					'qi_addons_for_elementor_filter_blog_slider_register_scripts',
					array(
						'fslightbox' => array(
							'registered' => true,
						),
					)
				)
			);
			$this->set_necessary_styles(
				apply_filters( 'qi_addons_for_elementor_filter_blog_slider_register_styles', array() )
			);

			$show_media_layouts   = array_keys( array_diff_key( $this->get_layouts(), apply_filters( 'qi_addons_for_elementor_filter_blog_slider_layout_hide_media', array() ) ) );
			$show_button_layouts  = array_keys( array_diff_key( $this->get_layouts(), apply_filters( 'qi_addons_for_elementor_filter_blog_slider_layout_hide_button', array() ) ) );
			$show_excerpt_layouts = array_keys( array_diff_key( $this->get_layouts(), apply_filters( 'qi_addons_for_elementor_filter_blog_slider_layout_hide_excerpt', array() ) ) );

			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->map_slider_options();
			$this->map_query_options();
			$this->map_layout_options( array( 'layouts' => $this->get_layouts() ) );
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'title_hover_underline',
					'title'      => esc_html__( 'Title Hover Underline', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'show_excerpt',
					'title'      => esc_html__( 'Show Excerpt', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'dependency' => array(
						'show' => array(
							'layout' => array(
								'values'        => $show_excerpt_layouts,
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'excerpt_length',
					'title'      => esc_html__( 'Excerpt Length', 'qi-addons-for-elementor' ),
					'dynamic'    => false,
					'group'      => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'relation' => 'and',
						'show'     => array(
							'layout'       => array(
								'values'        => $show_excerpt_layouts,
								'default_value' => '',
							),
							'show_excerpt' => array(
								'values'        => array( '', 'yes' ),
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'excerpt_color',
					'title'      => esc_html__( 'Excerpt Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-blog-item .qodef-e-excerpt' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'relation' => 'and',
						'show'     => array(
							'layout'       => array(
								'values'        => $show_excerpt_layouts,
								'default_value' => '',
							),
							'show_excerpt' => array(
								'values'        => array( '', 'yes' ),
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Excerpt Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'excerpt_typography',
					'title'      => esc_html__( 'Excerpt Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-blog-item .qodef-e-excerpt',
					'dependency' => array(
						'relation' => 'and',
						'show'     => array(
							'layout'       => array(
								'values'        => $show_excerpt_layouts,
								'default_value' => '',
							),
							'show_excerpt' => array(
								'values'        => array( '', 'yes' ),
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Excerpt Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'center_content',
					'title'      => esc_html__( 'Center Content', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'group'      => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'show_media',
					'title'      => esc_html__( 'Show Media', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'dependency' => array(
						'show' => array(
							'layout' => array(
								'values'        => $show_media_layouts,
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'show_info_icons',
					'title'      => esc_html__( 'Show Info Icons', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'group'      => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'show_date',
					'title'      => esc_html__( 'Show Date', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'group'      => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'show_category',
					'title'      => esc_html__( 'Show Category', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'group'      => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'show_author',
					'title'      => esc_html__( 'Show Author', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'group'      => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'show_button',
					'title'      => esc_html__( 'Show Button', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'dependency' => array(
						'show' => array(
							'layout' => array(
								'values'        => $show_button_layouts,
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_color',
					'title'      => esc_html__( 'Info Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Info Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-blog-item .qodef-e-info.qodef-info--top .qodef-e-info-item' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_hover_color',
					'title'      => esc_html__( 'Info Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Info Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-info.qodef-info--top .qodef-e-info-item a:hover' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'info_typography',
					'title'      => esc_html__( 'Info Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Info Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-info.qodef-info--top .qodef-e-info-item',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'post_info_margin_bottom',
					'title'      => esc_html__( 'Post Info Margin Bottom', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Layout Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-blog-item .qodef-e-info.qodef-info--top' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'info_icons_color',
					'title'      => esc_html__( 'Info Icons Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Info Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-info.qodef-info--top .qodef-e-info-item-icon' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'show_info_icons' => array(
								'values'        => array( 'yes' ),
								'default_value' => '',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'image_border_radius',
					'title'      => esc_html__( 'Image Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-media-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'image_hover',
					'title'      => esc_html__( 'Image Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
					'options'    => array(
						'zoom'     => esc_html__( 'Zoom In', 'qi-addons-for-elementor' ),
						'zoom-out' => esc_html__( 'Zoom Out', 'qi-addons-for-elementor' ),
						'move'     => esc_html__( 'Move', 'qi-addons-for-elementor' ),
						''         => esc_html__( 'None', 'qi-addons-for-elementor' ),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'image_zoom_origin',
					'title'      => esc_html__( 'Image Hover Zoom Origin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
					'options'    => array(
						''       => esc_html__( 'Center', 'qi-addons-for-elementor' ),
						'top'    => esc_html__( 'Top', 'qi-addons-for-elementor' ),
						'bottom' => esc_html__( 'Bottom', 'qi-addons-for-elementor' ),
						'left'   => esc_html__( 'Left', 'qi-addons-for-elementor' ),
						'right'  => esc_html__( 'Right', 'qi-addons-for-elementor' ),
					),
					'dependency' => array(
						'show' => array(
							'image_hover' => array(
								'values'        => array( 'zoom', 'zoom-out' ),
								'default_value' => 'zoom',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'overlay_color',
					'title'      => esc_html__( 'Overlay Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-media-image a:after' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'overlay_hover_color',
					'title'      => esc_html__( 'Overlay Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e:hover .qodef-e-media-image a:after' => 'background-color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_margin_bottom',
					'title'      => esc_html__( 'Title Margin Bottom', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Layout Spacing Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-shortcode .qodef-e-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'text',
					'name'        => 'read_more_text',
					'title'       => esc_html__( 'Read More Text', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'If nothing is entered, \'Read More\' text will be used', 'qi-addons-for-elementor' ),
					'dynamic'     => false,
					'group'       => esc_html__( 'Read More Button', 'qi-addons-for-elementor' ),
				)
			);
			$this->import_shortcode_options(
				array(
					'shortcode_base'    => 'qi_addons_for_elementor_button',
					'exclude'           => array( 'custom_class', 'button_link', 'button_text' ),
					'additional_params' => array(
						'nested_group' => esc_html__( 'Read More Button', 'qi-addons-for-elementor' ),
					),
				)
			);
			$this->map_extra_options();
		}

		public static function call_shortcode( $params ) {
			$html = qi_addons_for_elementor_framework_call_shortcode( 'qi_addons_for_elementor_blog_slider', $params );
			$html = str_replace( "\n", '', $html );

			return $html;
		}

		public function load_assets() {
			wp_enqueue_style( 'fslightbox' );
			parent::load_assets();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );

			$atts = $this->get_atts();

			$atts['post_type'] = $this->get_post_type();

			// Additional query args
			$atts['additional_query_args'] = $this->get_additional_query_args( $atts );

			$atts['unique']         = wp_unique_id();
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['query_result']   = new \WP_Query( qi_addons_for_elementor_get_query_params( $atts ) );
			$atts['button_params']  = $this->generate_button_params( $atts );
			$atts['slider_attr']    = $this->get_slider_data( $atts );
			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'blog/shortcodes/blog-slider', 'templates/content', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-addons-blog-slider';
			if ( ! empty( $atts['layout'] ) && 'standard' === $atts['layout'] ) {
				$holder_classes[] = 'qodef--list';
			}
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-item-layout--' . $atts['layout'] : '';
			if ( ! empty( $atts['center_content'] ) && 'yes' === $atts['center_content'] ) {
				$holder_classes[] = 'qodef-alignment--centered';
			}
			$holder_classes[] = ( 'yes' !== $atts['show_info_icons'] ) ? 'qodef-info-no-icons' : '';
			$holder_classes[] = 'yes' === $atts['title_hover_underline'] ? 'qodef-title--hover-underline' : '';
			$holder_classes[] = ! empty( $atts['image_hover'] ) ? 'qodef-image--hover-' . $atts['image_hover'] : '';
			$holder_classes[] = ! empty( $atts['image_zoom_origin'] ) ? 'qodef-image--hover-from-' . $atts['image_zoom_origin'] : '';

			$list_classes   = $this->get_slider_classes( $atts );
			$holder_classes = array_merge( $holder_classes, $list_classes );

			return implode( ' ', $holder_classes );
		}

		public function get_item_classes( $atts ) {
			$item_classes      = $this->init_item_classes();
			$item_classes[]    = 'qodef-blog-item';
			$list_item_classes = $this->get_slider_item_classes( $atts );

			$item_classes = array_merge( $item_classes, $list_item_classes );

			return implode( ' ', $item_classes );
		}
		private function generate_button_params( $atts ) {
			$atts['button_link'] = array(
				'url'               => '',
				'is_external'       => '',
				'nofollow'          => '',
				'custom_attributes' => '',
			);
			$atts['button_text'] = ! empty( $atts['read_more_text'] ) ? esc_html( $atts['read_more_text'] ) : esc_html__( 'Read More', 'qi-addons-for-elementor' );

			$button_params = $this->populate_imported_shortcode_atts(
				array(
					'shortcode_base' => 'qi_addons_for_elementor_button',
					'exclude'        => array( 'custom_class' ),
					'atts'           => $atts,
				)
			);

			return $button_params;
		}
	}
}
