<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_image_slider_shortcode' ) ) {
	/**
	 * Function that is adding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_image_slider_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Image_Slider_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_image_slider_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Slider_Shortcode' ) ) {
	class QiAddonsForElementor_Image_Slider_Shortcode extends QiAddonsForElementor_Slider_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_image_slider_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_image_slider_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/image-slider' );
			$this->set_base( 'qi_addons_for_elementor_image_slider' );
			$this->set_name( esc_html__( 'Image Slider', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays slider of images', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Showcase', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/image-slider/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#image_slider' );
			$this->set_scripts(
				apply_filters(
					'qi_addons_for_elementor_filter_image_gallery_slider_register_scripts',
					array(
						'fslightbox' => array(
							'registered' => true,
						),
					)
				)
			);
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
					'include_option' => array( 'slider-height' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'image',
					'name'       => 'images',
					'multiple'   => 'yes',
					'title'      => esc_html__( 'Images', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'zoom_centered',
					'title'         => esc_html__( 'Zoom Centered Slide', 'qi-addons-for-elementor' ),
					'description'   => esc_html__( 'Will affect spacing between items', 'qi-addons-for-elementor' ),
					'group'         => esc_html__( 'Slider Settings', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'default_value' => 'no',
					'dependency'    => array(
						'show' => array(
							'centered_slides' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'effect',
					'title'      => esc_html__( 'Slide Effect', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Settings', 'qi-addons-for-elementor' ),
					'options'    => array(
						'slide' => esc_html__( 'Slide', 'qi-addons-for-elementor' ),
						'fade'  => esc_html__( 'Fade', 'qi-addons-for-elementor' ),
					),
					'dependency' => array(
						'show' => array(
							'columns' => array(
								'values'        => '1',
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_custom_links',
					'title'         => esc_html__( 'Enable Custom Links', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'default_value' => 'no',
					'group'         => esc_html__( 'Slider Settings', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'  => 'textarea',
					'name'        => 'custom_links',
					'title'       => esc_html__( 'Custom Links', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter links for gallery images, separated by comma', 'qi-addons-for-elementor' ),
					'dependency'  => array(
						'show' => array(
							'enable_custom_links' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'dynamic'     => false,
					'group'       => esc_html__( 'Slider Settings', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'custom_links_target',
					'title'         => esc_html__( 'Custom Links Target', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'link_target', false ),
					'dependency'    => array(
						'show' => array(
							'enable_custom_links' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'default_value' => '_blank',
					'group'         => esc_html__( 'Slider Settings', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'enable_popup',
					'title'      => esc_html__( 'Enable Lightbox Popup', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
					'dependency' => array(
						'show' => array(
							'enable_custom_links' => array(
								'values'        => 'no',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'Slider Settings', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'image_border_radius',
					'title'      => esc_html__( 'Image Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Slider Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'image_hover',
					'title'      => esc_html__( 'Image Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Style', 'qi-addons-for-elementor' ),
					'options'    => apply_filters(
						'qi_addons_for_elementor_image_gallery_filter_image_hover',
						array(
							''         => esc_html__( 'None', 'qi-addons-for-elementor' ),
							'zoom'     => esc_html__( 'Zoom In', 'qi-addons-for-elementor' ),
							'zoom-out' => esc_html__( 'Zoom Out', 'qi-addons-for-elementor' ),
							'move'     => esc_html__( 'Move', 'qi-addons-for-elementor' ),
						)
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'image_zoom_origin',
					'title'      => esc_html__( 'Image Hover Zoom Origin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Style', 'qi-addons-for-elementor' ),
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
								'default_value' => '',
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
					'group'      => esc_html__( 'Slider Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-inner:after' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'overlay_hover_color',
					'title'      => esc_html__( 'Overlay Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Slider Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e:hover .qodef-e-inner:after' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'checkbox',
					'name'          => 'enable_alt_text',
					'title'         => esc_html__( 'Show Image Alt Text', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
					'default_value' => 'no',
					'dependency'    => array(
						'show' => array(
							'centered_slides' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'alt_text_color',
					'title'      => esc_html__( 'Alt Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-alt-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Alt Text Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'enable_alt_text' => array(
								'values'        => 'yes',
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'alt_text_typography',
					'title'      => esc_html__( 'Alt Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-alt-text',
					'group'      => esc_html__( 'Alt Text Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'enable_alt_text' => array(
								'values'        => 'yes',
								'default_value' => '',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'alt_text_margin_top',
					'title'      => esc_html__( 'Alt Text Margin Top', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-alt-text-holder' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Alt Text Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'enable_alt_text' => array(
								'values'        => 'yes',
								'default_value' => '',
							),
						),
					),
				)
			);

			$this->map_extra_options();
		}

		public function load_assets() {
			wp_enqueue_style( 'fslightbox' );
			parent::load_assets();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );

			$atts = $this->get_atts();

			$atts['unique']         = rand( 0, 999 );
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['item_classes']   = $this->get_item_classes( $atts );
			$atts['images']         = $this->generate_images_params( $atts );
			$atts['slider_attr']    = $this->get_slider_data( $atts );

			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'shortcodes/image-slider', 'templates/image-slider', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-image-slider';
			$holder_classes[] = ! empty( $atts['enable_popup'] ) && 'yes' === $atts['enable_popup'] ? 'qodef-qi-fslightbox-popup qodef-popup-gallery' : '';
			$holder_classes[] = ! empty( $atts['image_hover'] ) ? 'qodef-image--hover-' . $atts['image_hover'] : '';
			$holder_classes[] = ! empty( $atts['image_zoom_origin'] ) ? 'qodef-image--hover-from-' . $atts['image_zoom_origin'] : '';
			$holder_classes[] = 'yes' === $atts['zoom_centered'] ? 'qodef--centered-zoom' : '';
			$holder_classes[] = ! empty( $atts['columns'] ) ? 'qodef-col-num--' . $atts['columns'] : '';

			$slider_classes = $this->get_slider_classes( $atts );
			$holder_classes = array_merge( $holder_classes, $slider_classes );

			return implode( ' ', $holder_classes );
		}

		public function get_item_classes( $atts ) {
			$item_classes = $this->init_item_classes();

			$slider_item_classes = $this->get_slider_item_classes( $atts );

			$item_classes = array_merge( $item_classes, $slider_item_classes );

			return implode( ' ', $item_classes );
		}

		private function generate_images_params( $atts ) {
			$image_ids = array();
			$images    = array();
			$i         = 0;

			if ( '' !== $atts['images'] && is_string( $atts['images'] ) ) {
				$image_ids = explode( ',', $atts['images'] );
			}

			if ( 'yes' === $atts['enable_custom_links'] && ! empty( $atts['custom_links'] ) ) {
				$links = explode( ',', $atts['custom_links'] );
			}

			if ( count( $image_ids ) ) {

				foreach ( $image_ids as $id ) {
					$image['image_id'] = intval( $id );
					$image['alt']      = get_post_meta( $id, '_wp_attachment_image_alt', true );

					if ( isset( $links[ $i ] ) && ! empty( $links[ $i ] ) ) {
						$image['image_link'] = $links[ $i ];
					} else {
						$image['image_link'] = '';
					}

					$images[ $i ] = $image;
					$i ++;
				}
			}

			return $images;
		}
	}
}
