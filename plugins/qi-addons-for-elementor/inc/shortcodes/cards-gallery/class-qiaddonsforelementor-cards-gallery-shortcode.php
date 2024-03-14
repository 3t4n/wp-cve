<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_cards_gallery_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_cards_gallery_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Cards_Gallery_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_cards_gallery_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Cards_Gallery_Shortcode extends QiAddonsForElementor_Shortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/cards-gallery' );
			$this->set_base( 'qi_addons_for_elementor_cards_gallery' );
			$this->set_name( esc_html__( 'Cards Gallery', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds cards gallery holder', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Creative', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/cards-gallery/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#cards_gallery' );
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
						'right' => esc_html__( 'Shuffle Right', 'qi-addons-for-elementor' ),
						'left'  => esc_html__( 'Shuffle Left', 'qi-addons-for-elementor' ),
						'both'  => esc_html__( 'Shuffle Left and Right', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'right',
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
			$scale_selectors = array();
			for ( $i = 9; $i > 0; $i -- ) {
				$scale_selectors[ '{{WRAPPER}} .qodef-orientation--one-side .qodef-m-card:nth-last-child(' . $i . ')' ] = 'transform: scale(calc(1 - ' . ( $i - 1 ) . ' * {{SIZE}}));';
				$scale_selectors[ '{{WRAPPER}} .qodef-orientation--both .qodef-m-card:nth-last-child(' . $i . ')' ]     = 'transform: scale(calc(1 - ' . floor( $i / 2 ) . ' * {{SIZE}}));';
			}
			$this->set_option(
				array(
					'field_type'    => 'slider',
					'name'          => 'scale_step',
					'title'         => esc_html__( 'Scale Step', 'qi-addons-for-elementor' ),
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'size_units'    => array( 'px' ),
					'range'         => array(
						'px' => array(
							'min'  => 0.01,
							'max'  => 0.5,
							'step' => 0.01,
						),
					),
					'selectors'     => $scale_selectors,
					'default_value' => array(
						'unit' => 'px',
						'size' => '0.2',
					),
					'responsive'    => false,
				)
			);
			$offset_selectors = array();
			for ( $i = 10; $i > 0; $i -- ) {
				$offset_selectors[ '{{WRAPPER}} .qodef-orientation--one-side .qodef-m-card:nth-last-child(' . $i . ')' ] = '{{orientation.VALUE}}: calc( -1 * ' . ( $i - 1 ) . ' * {{SIZE}}%);';

				if ( $i % 2 ) {
					$offset_selectors[ '{{WRAPPER}} .qodef-orientation--both .qodef-m-card:nth-last-child(' . $i . ')' ] = 'left: calc( -1 * ' . floor( $i / 2 ) . ' * {{SIZE}}%);';
				} else {
					$offset_selectors[ '{{WRAPPER}} .qodef-orientation--both .qodef-m-card:nth-last-child(' . $i . ')' ] = 'right: calc( -1 * ' . floor( $i / 2 ) . ' * {{SIZE}}%);';
				}
			}
			$this->set_option(
				array(
					'field_type'    => 'slider',
					'name'          => 'offset_step',
					'title'         => esc_html__( 'Offset Step', 'qi-addons-for-elementor' ),
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'size_units'    => array( '%' ),
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
					'responsive'    => false,
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

			return qi_addons_for_elementor_get_template_part( 'shortcodes/cards-gallery', 'templates/cards-gallery', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-cards-gallery';
			$holder_classes[] = ! empty( $atts['orientation'] ) ? 'qodef-orientation--' . $atts['orientation'] : 'qodef-orientation--right';
			$holder_classes[] = ( 'both' !== $atts['orientation'] ) ? 'qodef-orientation--one-side' : '';

			return implode( ' ', $holder_classes );
		}

		private function get_data_attrs( $atts ) {
			$data = array();

			$data['data-orientation'] = ! empty( $atts['orientation'] ) ? $atts['orientation'] : 'right';
			$data['data-scale']       = ! empty( $atts['scale_step']['size'] ) ? $atts['scale_step']['size'] : 0.1;
			$data['data-offset']      = ! empty( $atts['offset_step']['size'] ) ? $atts['offset_step']['size'] : 30;

			return $data;
		}
	}
}
