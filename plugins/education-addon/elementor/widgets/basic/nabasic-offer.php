<?php
/*
 * Elementor Education Addon Offer Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_offer'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Offer extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_offer';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Offer', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-product-stock';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Offer widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_offer',
			[
				'label' => __( 'Offer Item', 'education-addon' ),
			]
		);
		$this->add_control(
			'offer_style',
			[
				'label' => esc_html__( 'Offer Style', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'education-addon' ),
					'two' => esc_html__( 'Style Two', 'education-addon' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your style.', 'education-addon' ),
			]
		);
		$this->add_control(
			'offer_bg',
			[
				'label' => esc_html__( 'Background Image', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Set your background image.', 'education-addon'),
				'selectors' => [
					'{{WRAPPER}} .offer-wrap:after, {{WRAPPER}} .offer-style-two .naedu-images' => 'background-image: url({{url}});',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'offer_image',
			[
				'label' => esc_html__( 'Content Image', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'education-addon'),
				'condition' => [
					'offer_style' => 'one',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'offer_title',
			[
				'label' => esc_html__( 'Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Special Offer!', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'discount_title',
			[
				'label' => esc_html__( 'Discount Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Combo 15% Discount', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'sale_title',
			[
				'label' => esc_html__( 'Sale Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Sale', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'sale_link',
			[
				'label' => esc_html__( 'Sale Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
				'condition' => [
					'offer_style' => 'two',
				],
			]
		);
		$this->add_control(
			'buy_title',
			[
				'label' => esc_html__( 'Buy Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Buy 1 Course Get 1 Free', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Buy Now', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_link',
			[
				'label' => esc_html__( 'Button Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .offer-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .offer-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_bdr_rad',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .offer-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .offer-wrap, {{WRAPPER}} .naedu-offer .naedu-images:after, {{WRAPPER}} .naedu-offer h3' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .offers-shape .st0' => 'fill: {{VALUE}};',
					],
				]
			);			
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .offer-wrap',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .offer-wrap',
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'title_padding',
				[
					'label' => __( 'Title Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-offer h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .naedu-offer h3',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-offer h3' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'title_text_shadow',
					'label' => esc_html__( 'Text Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-offer h3',
				]
			);
			$this->end_controls_section();// end: Section

		// Discount Title
			$this->start_controls_section(
				'section_discount_title_style',
				[
					'label' => esc_html__( 'Discount Title', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'discount_title_padding',
				[
					'label' => __( 'Title Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-offer h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'discount_title_typography',
					'selector' => '{{WRAPPER}} .naedu-offer h4',
				]
			);
			$this->add_control(
				'discount_title_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-offer h4' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'discount_title_text_shadow',
					'label' => esc_html__( 'Text Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-offer h4',
				]
			);
			$this->end_controls_section();// end: Section

		// Sale Title
			$this->start_controls_section(
				'section_sale_title_style',
				[
					'label' => esc_html__( 'Sale Title', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'sale_title_padding',
				[
					'label' => __( 'Title Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-offer h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'sale_title_typography',
					'selector' => '{{WRAPPER}} .naedu-offer h5',
				]
			);
			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'sale_title_text_shadow',
					'label' => esc_html__( 'Text Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-offer h5',
				]
			);
			$this->start_controls_tabs( 'sale_ttl_style' );
				$this->start_controls_tab(
					'sale_ttl_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'sale_ttl_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-offer h5, {{WRAPPER}} .naedu-offer h5 a' => 'color: {{VALUE}};-webkit-text-fill-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'sale_stroke_color',
					[
						'label' => esc_html__( 'Stroke Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-offer h5' => '-webkit-text-stroke-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'sale_ttl_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'sale_ttl_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-offer h5 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Buy Title
			$this->start_controls_section(
				'section_buy_title_style',
				[
					'label' => esc_html__( 'Buy Title', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'buy_title_padding',
				[
					'label' => __( 'Title Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-offer h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'buy_title_typography',
					'selector' => '{{WRAPPER}} .naedu-offer h6',
				]
			);
			$this->add_control(
				'buy_title_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-offer h6' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'buy_title_text_shadow',
					'label' => esc_html__( 'Text Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-offer h6',
				]
			);
			$this->end_controls_section();// end: Section

		// Button
			$this->start_controls_section(
				'section_btn_style',
				[
					'label' => esc_html__( 'Button', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'btn_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'btn_padding',
				[
					'label' => __( 'Button Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'btn_typography',
					'selector' => '{{WRAPPER}} .naedu-btn',
				]
			);
			$this->start_controls_tabs( 'btn_style' );
				$this->start_controls_tab(
					'btn_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'btn_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'btn_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'btn_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_hover_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn:hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_hover_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Offer widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Offer query
		$settings = $this->get_settings_for_display();
		$offer_style = !empty( $settings['offer_style'] ) ? $settings['offer_style'] : '';
		$offer_image = !empty( $settings['offer_image']['id'] ) ? $settings['offer_image']['id'] : '';
		$offer_title = !empty( $settings['offer_title'] ) ? $settings['offer_title'] : '';
		$discount_title = !empty( $settings['discount_title'] ) ? $settings['discount_title'] : '';

		$sale_title = !empty( $settings['sale_title'] ) ? $settings['sale_title'] : '';
		$sale_link = !empty( $settings['sale_link']['url'] ) ? esc_url($settings['sale_link']['url']) : '';
		$sale_link_external = !empty( $sale_link['is_external'] ) ? 'target="_blank"' : '';
		$sale_link_nofollow = !empty( $sale_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$sale_link_attr = !empty( $sale_link['url'] ) ?  $sale_link_external.' '.$sale_link_nofollow : '';

		$buy_title = !empty( $settings['buy_title'] ) ? $settings['buy_title'] : '';

		$btn_text = !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
		$btn_link = !empty( $settings['btn_link']['url'] ) ? esc_url($settings['btn_link']['url']) : '';
		$btn_link_external = !empty( $btn_link['is_external'] ) ? 'target="_blank"' : '';
		$btn_link_nofollow = !empty( $btn_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$btn_link_attr = !empty( $btn_link['url'] ) ?  $btn_link_external.' '.$btn_link_nofollow : '';

		$image_url = wp_get_attachment_url( $offer_image );
		$image = $image_url ? '<img src="'.esc_url($image_url).'" alt="Image">' : '';

		$offer_title = $offer_title ? '<h3>'.$offer_title.'</h3>' : '';
		$discount_title = $discount_title ? '<h4>'.$discount_title.'</h4>' : '';

		$sale_link = $sale_link ? '<a href="'.esc_url($sale_link).'" '.$sale_link_attr.'>'.$sale_title.'</a>' : $sale_title;
		$sale_title_link = $sale_title ? '<h5>'.$sale_link.'</h5>' : '';
		$sale_title = $sale_title ? '<h5>'.$sale_title.'</h5>' : '';

		$buy_title = $buy_title ? '<h6>'.$buy_title.'</h6>' : '';

		$button = $btn_link ? '<a href="'.esc_url($btn_link).'" class="naedu-btn naedu-btn-dark naedu-btn-sm" '.$btn_link_attr.'>'.esc_html($btn_text).'</a>' : '';

		if ($offer_style === 'two') {
			$style_cls = ' offer-style-two';
		} else {
			$style_cls = '';
		}

		$output = '<div class="naedu-offer'.$style_cls.'">';
		if ($offer_style === 'two') {
	    $output .= '<div class="offer-wrap">
						        <div class="nich-row">
						          <div class="nich-col-lg-5 nich-col-xl-4 nich-my-auto nich-order-lg-2">
						            <div class="offer-info">
						              '.$offer_title.$discount_title.$sale_title_link.$buy_title.'
						            </div>
						          </div>
						          <div class="nich-col-lg-7 nich-col-xl-8">
						            <div class="naedu-images">
						              <svg version="1.1" class="offers-shape" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						                viewBox="0 0 156.98 497.78">
						                <path class="st0" d="M0,0h156.98v497.78h-14.9C142.08,497.78,181.8,242.34,0,0z"/>
						              </svg>
						            </div>
						          </div>
						        </div>
						      </div>';
	  } else {
	  	$output .= '<div class="offer-wrap">
						        <div class="nich-row">
						          <div class="nich-col-lg-5 nich-my-auto nich-order-lg-2">
						            <div class="offer-info">'.$offer_title.$discount_title.$sale_title.$buy_title.$button.'</div>
						          </div>
						          <div class="nich-col-lg-7">'.$image.'</div>
						        </div>
						      </div>';
	  }
	  $output .= '</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Offer() );

} // enable & disable
