<?php

namespace Elementor;

use Thim_EL_Kit\Elementor\Controls\Controls_Manager as Thim_Control_Manager;

abstract class Thim_Ekit_Products_Base extends Widget_Base {

	protected function register_controls() {
// 		$this->register_style_product_controls();
//		$this->register_style_pagination_controls();
//		$this->register_style_sale_controls();
	}

	protected function register_layout_content() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
				'condition' => array(
					'build_loop_item!' => 'yes',
				)
			)
		);
		$this->register_content_image_thumbnail();

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'key',
			array(
				'label'   => esc_html__( 'Type', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => array(
					'title'     => esc_html__( 'Title', 'thim-elementor-kit' ),
					'meta_data' => esc_html__( 'Meta Data', 'thim-elementor-kit' ),
					'content'   => esc_html__( 'Content', 'thim-elementor-kit' ),
					'price'     => esc_html__( 'Price', 'thim-elementor-kit' ),
					'cart'      => esc_html__( 'Add To Cart', 'thim-elementor-kit' ),
				),
			)
		);
		$repeater->add_control(
			'title_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default'   => 'h3',
				'condition' => array(
					'key' => 'title',
				),
			)
		);

		$repeater->add_control(
			'excerpt_lenght',
			array(
				'label'     => esc_html__( 'Excerpt Lenght', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 25,
				'condition' => array(
					'key' => 'content',
				),
			)
		);

		$repeater->add_control(
			'excerpt_more',
			array(
				'label'     => esc_html__( 'Excerpt More', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '...',
				'condition' => array(
					'key' => 'content',
				),
			)
		);
		$repeater->add_control(
			'meta_data_pr',
			array(
				'label'       => esc_html__( 'Meta Data', 'thim-elementor-kit' ),
				'label_block' => true,
				'type'        => Thim_Control_Manager::SELECT2,
				'default'     => array( 'attributes' ),
				'multiple'    => true,
				'sortable'    => true,
				'options'     => apply_filters( 'thim-kits/meta-data-item/product', array(
						'rating'     => esc_html__( 'Rating', 'thim-elementor-kit' ),
 						'cart'       => esc_html__( 'Icon Cart', 'thim-elementor-kit' ),
 						'price'      => esc_html__( 'Price', 'thim-elementor-kit' ),
					)
				),
				'condition'   => array(
					'key' => 'meta_data',
				),
			)
		);

		$this->add_control(
			'repeater',
			array(
				'label'       => esc_html__( 'Post Data', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'key' => 'title',
					),
					array(
						'key' => 'price',
					),
					array(
						'key' => 'meta_data',
					),
				),
				'title_field' => '<span style="text-transform: capitalize;">{{{ key.replace("_", " ") }}}</span>',
			)
		);
		$this->add_control(
			'open_new_tab',
			array(
				'label'     => esc_html__( 'Open in new window', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'thim-elementor-kit' ),
				'label_off' => esc_html__( 'No', 'thim-elementor-kit' ),
				'default'   => 'no',
			)
		);
		$this->end_controls_section();

	}

	protected function register_content_image_thumbnail() {
		$this->add_control(
			'show_image',
			array(
				'label'     => esc_html__( 'Show Image', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'show_onsale_flash',
			array(
				'label'        => esc_html__( 'Sale Flash', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Hide', 'thim-elementor-kit' ),
				'label_on'     => esc_html__( 'Show', 'thim-elementor-kit' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array(
					'show_image' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'thumbnail_position',
			array(
				'label'       => esc_html__( 'Image', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'top',
				'options'     => array(
					'top'   => array(
						'title' => esc_html__( 'Top', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-top',
					),
					'left'  => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'render_type' => 'ui',
				//				'prefix_class' => 'list-product-img-layout-',
				'condition'   => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail_size',
				'default'   => 'full',
				'condition' => array(
					'show_image' => 'yes',
				),
			)
		);
	}

	protected function register_style_image() {
		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => esc_html__( 'Image', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'build_loop_item!' => 'yes',
				)
			)
		);

		$this->add_control(
			'img_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-product .product-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'image_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thumbnail-position-left .product-image'  => 'margin-left: {{SIZE}}{{UNIT}};margin-bottom: 0px;',
					'{{WRAPPER}} .thumbnail-position-right .product-image' => 'margin-right: {{SIZE}}{{UNIT}};margin-bottom: 0px;',
					'{{WRAPPER}} .thumbnail-position-top .product-image'   => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'default'   => array(
					'size' => 20,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .thim-ekits-product .product-image',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .thim-ekits-product .product-image',
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_content() {
		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'build_loop_item!' => 'yes',
				)
			)
		);
		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .wrapper-content-item'       => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .thim-ekits-product .wrapper-content-item .star-rating' => 'justify-content: {{VALUE}};float:unset;display: inline-block;',
				),
			)
		);
		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-product .product .wrapper-content-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'heading_title_style',
			array(
				'label' => esc_html__( 'Title', 'thim-elementor-kit' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .woocommerce-loop-product_title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-product .woocommerce-loop-product_title a',
			)
		);

		$this->add_control(
			'title_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .woocommerce-loop-product_title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_meta_style',
			array(
				'label'     => esc_html__( 'Meta', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-item-actions > div > svg > path' => 'fill: {{VALUE}};stroke:{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'meta_separator_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-item-actions > div ' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'meta_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .product-item-actions > div' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_excerpt_style',
			array(
				'label'     => esc_html__( 'Excerpt', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-excerpt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .product-excerpt',
			)
		);

		$this->add_control(
			'excerpt_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .product-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_price_style',
			array(
				'label'     => esc_html__( 'Price', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product .price'             => 'color: {{VALUE}}',
					'{{WRAPPER}} .thim-ekits-product .product .price ins'         => 'color: {{VALUE}}',
					'{{WRAPPER}} .thim-ekits-product .product .price .amount' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-product .woocommerce-Price-amount',
			)
		);

		$this->add_control(
			'price_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product .price' => 'margin-bottom: {{SIZE}}{{UNIT}};margin-bottom: 44px !important;
					display: inline-block;
					width: 100%;',
				),
			)
		);
		$this->add_control(
			'heading_regular_price_style',
			array(
				'label'     => esc_html__( 'Regular Price', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'regular_price_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .price del'         => 'color: {{VALUE}}',
					'{{WRAPPER}} .thim-ekits-product .price del .woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'regular_price_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-product .price del .amount.woocommerce-Price-amount ,{{WRAPPER}} .thim-ekits-product .price del',
			)
		);

		$this->add_control(
			'heading_rating_style',
			array(
				'label'     => esc_html__( 'Rating', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'star_color',
			array(
				'label'     => esc_html__( 'Star Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} .thim-ekits-product .star-rating span::before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'empty_star_color',
			array(
				'label'     => esc_html__( 'Empty Star Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} .thim-ekits-product .star-rating::before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'star_size',
			array(
				'label'      => esc_html__( 'Star Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'unit' => 'em',
				),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'body {{WRAPPER}} .thim-ekits-product .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rating_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'body {{WRAPPER}} .thim-ekits-product .star-rating' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'heading_button_style',
			array(
				'label'     => esc_html__( 'Button', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),

				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product .button,
					{{WRAPPER}} .thim-ekits-product .product .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'exclude'  => array( 'color' ),
				'selector' => '{{WRAPPER}} .thim-ekits-product .product .button,
				{{WRAPPER}} .thim-ekits-product .product .added_to_cart',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-product .product .button,
					{{WRAPPER}} .thim-ekits-product .product .added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'button_ưidth',
			array(
				'label'      => esc_html__( 'Width', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => [
						'min' => 0,
						'max' => 250,
						'step' => 5,
					],
				),
				'selectors'  => array(
					'body {{WRAPPER}} .thim-ekits-product .product .button,
					{{WRAPPER}} .thim-ekits-product .product .added_to_cart' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_responsive_control(
			'button_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'body {{WRAPPER}} .thim-ekits-product .product .button,
					{{WRAPPER}} .thim-ekits-product .product .added_to_cart' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product .button,
					 {{WRAPPER}} .thim-ekits-product .product .added_to_cart' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product .button,
					{{WRAPPER}} .thim-ekits-product .product .added_to_cart' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'button_border_border!' => [ 'none', '' ],
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product .button,
					{{WRAPPER}} .thim-ekits-product .product .added_to_cart' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-product .product .button,
							   {{WRAPPER}} .thim-ekits-product .product .added_to_cart',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product .button:hover,
					{{WRAPPER}} .thim-ekits-product .product .added_to_cart:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product .button:hover,
					{{WRAPPER}} .thim-ekits-product .product .added_to_cart:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'button_border_border!' => [ 'none', '' ],
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product .button:hover,
					{{WRAPPER}} .thim-ekits-product .product .added_to_cart:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_product_controls() {
		$this->start_controls_section(
			'section_style_product',
			array(
				'label' => esc_html__( 'Product', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'build_loop_item!' => 'yes',
				)
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'product_border',
				'exclude'  => array( 'color' ),
				'selector' => '{{WRAPPER}} .thim-ekits-product .product',
			)
		);

		$this->add_control(
			'product_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-product .product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'product_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-product .product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'product_style_tabs' );

		$this->start_controls_tab(
			'product_style_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'product_shadow',
				'selector' => '{{WRAPPER}} .thim-ekits-product .product',
			)
		);

		$this->add_control(
			'product_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'product_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'product_border_border!' => [ 'none', '' ],
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'product_style_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'product_shadow_hover',
				'selector' => '{{WRAPPER}} .thim-ekits-product .product:hover',
			)
		);

		$this->add_control(
			'product_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'product_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'product_border_border!' => [ 'none', '' ],
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_pagination_controls() {
		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'     => esc_html__( 'Pagination', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'paginate' => 'yes',
				),
			)
		);

		$this->add_control(
			'logo_align',
			array(
				'label'        => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'prefix_class' => 'thim-ekit-archive-product--pagination--align--',
				'selectors'    => array(
					'{{WRAPPER}} nav.woocommerce-pagination' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_gap',
			array(
				'label'          => esc_html__( 'Columns Gap', 'thim-elementor-kit' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'em' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
					'em' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}}.thim-ekit-archive-product--pagination--align--left nav.woocommerce-pagination ul li'   => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.thim-ekit-archive-product--pagination--align--right nav.woocommerce-pagination ul li'  => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.thim-ekit-archive-product--pagination--align--center nav.woocommerce-pagination ul li' => 'margin-left: calc( {{SIZE}}{{UNIT}} / 2 ); margin-right: calc( {{SIZE}}{{UNIT}} / 2 );',
				),
			)
		);

		$this->add_control(
			'pagination_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pagination_border',
				'exclude'  => array( 'color' ),
				'selector' => '{{WRAPPER}} nav.woocommerce-pagination ul li a, {{WRAPPER}} nav.woocommerce-pagination ul li span',
			)
		);

		$this->add_responsive_control(
			'pagination_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a, {{WRAPPER}} nav.woocommerce-pagination ul li span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} nav.woocommerce-pagination',
			)
		);

		$this->start_controls_tabs( 'pagination_style_tabs' );

		$this->start_controls_tab(
			'pagination_style_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'pagination_link_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_style_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'pagination_link_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_style_active',
			array(
				'label' => esc_html__( 'Active', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'pagination_link_color_active',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_sale_controls() {
		$this->start_controls_section(
			'section_sale_style',
			array(
				'label'     => esc_html__( 'Sale Badge', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_onsale_flash' => 'yes',
					'show_image'        => 'yes',
					'build_loop_item!' => 'yes',
				),
 			)
		);

		$this->add_control(
			'onsale_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product span.onsale' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'onsale_text_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-product .product span.onsale' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'onsale_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-product .product span.onsale',
			)
		);

		$this->add_control(
			'onsale_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-product .product span.onsale' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'onsale_width',
			array(
				'label'      => esc_html__( 'Width', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-product .product span.onsale' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'onsale_height',
			array(
				'label'      => esc_html__( 'Height', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-product .product span.onsale' => 'min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'onsale_horizontal_position',
			array(
				'label'                => esc_html__( 'Position', 'thim-elementor-kit' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} .thim-ekits-product .product span.onsale' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'left'  => 'right: auto; left: 0',
					'right' => 'left: auto; right: 0',
				),
			)
		);

		$this->add_control(
			'onsale_distance',
			array(
				'label'      => esc_html__( 'Distance', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => - 20,
						'max' => 20,
					),
					'em' => array(
						'min' => - 2,
						'max' => 2,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-product .product span.onsale' => 'margin: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render_item_product( $settings ) {
		?>
		<?php if ( ! empty( $settings['build_loop_item'] ) && $settings['build_loop_item'] == 'yes' ) {
			\Thim_EL_Kit\Utilities\Elementor::instance()->render_loop_item_content( $settings['template_id'] );
		} else { ?>

			<?php $class_item = $settings['thumbnail_position'] ? ' thumbnail-position-' . $settings['thumbnail_position'] : ''; ?>

			<div class="inner-item-product<?php echo $class_item; ?>">
				<?php $this->render_image_product( $settings ); ?>

				<?php
				if ( $settings['repeater'] ) {
					echo '<div class="wrapper-content-item">';
					foreach ( $settings['repeater'] as $item ) {
						switch ( $item['key'] ) {
							case 'title':
								$this->render_title( $settings );
								break;
							case 'content':
								$this->render_excerpt( $item );
								break;
							case 'price':
								$this->render_price();
								break;
							case 'cart' :
								$this->render_cart();
								break;
							case 'meta_data':
								$this->render_meta_data( $item );
								break;
						}
					}
					echo '</div>';
				}
				?>
			</div>

		<?php } ?>

		<?php
	}

	protected function render_image_product( $settings ) {
		if ( ! $settings['show_image'] ) {
			return;
		}
		$attributes_html = ( isset( $settings['open_new_tab'] ) && $settings['open_new_tab'] == 'yes' ) ? ' target="_blank" rel="noopener noreferrer"' : '';
		?>
		<div class="product-image">
			<?php do_action( 'thim-ekit/before-product-image' ); ?>

			<a href="<?php echo esc_url( the_permalink() ) ?>" title="<?php the_title(); ?>"<?php echo
			$attributes_html; ?> ><?php echo woocommerce_get_product_thumbnail( $settings['thumbnail_size_size'] ); ?></a>

			<?php
			if ( $settings['show_onsale_flash'] == 'yes' ) {
				woocommerce_show_product_loop_sale_flash();
			}
 			do_action( 'thim-ekit/after-product-image' );
			?>
		</div>
		<?php
	}

	protected function render_meta_data( $item ) {
		$meta_data = $item['meta_data_pr'];
		?>
		<div class="product-item-meta elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
			<?php
			foreach ( $meta_data as $key => $data ) {
				switch ( $data ) {
					case 'price':
						$this->render_price();
						break;
 					case'cart':
						$this->render_cart();
						break;
					case'rating':
						$this->render_rating();
						break;
				}
			}
			echo wp_kses_post( apply_filters( 'thim-kits/render-meta-data/product', '', $meta_data ) );
			?>
		</div>
		<?php
	}

	protected function render_title( $settings ) {
		$attributes_html = ( isset( $settings['open_new_tab'] ) && $settings['open_new_tab'] == 'yes' ) ? 'target="_blank" rel="noopener noreferrer"' : '';
		?>
		<h2 class="woocommerce-loop-product_title">
			<a href="<?php echo esc_url( the_permalink() ) ?>"
			   title="<?php the_title(); ?>"<?php echo $attributes_html; ?> ><?php the_title(); ?></a>
		</h2>
	<?php }

	protected function render_excerpt( $item ) { ?>
		<div class="product-excerpt">
			<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), absint( $item['excerpt_lenght'] ), esc_html( $item['excerpt_more'] ) ) ); ?>
		</div>
		<?php
	}

	protected function render_price() {
		woocommerce_template_loop_price();
	}

	protected function render_cart() {
		woocommerce_template_loop_add_to_cart();
	}

	protected function render_rating() {
		woocommerce_template_loop_rating();
	}
}
