<?php
/*
 * Elementor Primary Addon for Pricing Table
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'pafe_bw_settings' )['napafe_pricing_table'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Primary_Addon_Pricing_Table extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'prim_basic_pricing_table';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Pricing Table', 'primary-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-price-table';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['prim-basic-category'];
	}

	/**
	 * Register Events Addon for Elementor Unique Upcoming widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		// Style
		$this->start_controls_section(
			'_section_style_settings',
			[
				'label' => esc_html__( 'Options', 'primary-addon-for-elementor' ),
			]
		);

		$this->add_control(
			'pricing_style',
			[
				'label' => esc_html__( 'Style', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'primary-addon-for-elementor' ),
					'two' => esc_html__( 'Style Two', 'primary-addon-for-elementor' ),
					'three' => esc_html__( 'Style Three', 'primary-addon-for-elementor' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select pricing layout style.', 'primary-addon-for-elementor' ),
			]
		);

        $this->end_controls_section();

		// Header
		$this->start_controls_section(
			'_section_header',
			[
				'label' => esc_html__( 'Header', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__( 'Basic', 'primary-addon-for-elementor' ),
                'dynamic' => [
                    'active' => true
                ]
            ]
        );

        $this->end_controls_section();
        
        // Pricing
        $this->start_controls_section(
            '_section_pricing',
            [
                'label' => esc_html__( 'Pricing', 'primary-addon-for-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'currency',
            [
                'label' => esc_html__( 'Custom Symbol', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );
        
        $this->add_control(
            'price',
            [
                'label' => esc_html__( 'Price', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => '9.99',
                'dynamic' => [
                    'active' => true
                ]
            ]
        );

        $this->add_control(
            'period',
            [
                'label' => esc_html__( 'Period', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Per Month', 'primary-addon-for-elementor' ),
                'dynamic' => [
                    'active' => true
                ]
            ]
        );

        $this->end_controls_section();

        // Features
        $this->start_controls_section(
            '_section_features',
            [
                'label' => esc_html__( 'Features', 'primary-addon-for-elementor' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'text',
            [
                'label' => esc_html__( 'Text', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Exciting Feature', 'primary-addon-for-elementor' ),
                'dynamic' => [
                    'active' => true
                ]
            ]
        );
        
        $repeater->add_control(
            'selected_icon',
            [
                'label' => esc_html__( 'Icon', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-regular' => [
                        'check-square',
                        'window-close',
                    ],
                    'fa-solid' => [
                        'check',
                    ]
                ]
            ]
        );

        $this->add_control(
            'features_list',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'text' => esc_html__( 'Standard Feature', 'primary-addon-for-elementor' ),
                        'selected_icon' => 'fa fa-check',
                    ],
                    [
                        'text' => esc_html__( 'Another Great Feature', 'primary-addon-for-elementor' ),
                        'selected_icon' => 'fa fa-check',
                    ],
                    [
                        'text' => esc_html__( 'Obsolete Feature', 'primary-addon-for-elementor' ),
                        'selected_icon' => 'fa fa-close',
                    ],
                    [
                        'text' => esc_html__( 'Exciting Feature', 'primary-addon-for-elementor' ),
                        'selected_icon' => 'fa fa-check',
                    ],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->end_controls_section(); 

        // Footer
        $this->start_controls_section(
            '_section_footer',
            [
                'label' => esc_html__( 'Footer', 'primary-addon-for-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__( 'Button Text', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Subscribe', 'primary-addon-for-elementor' ),
                'placeholder' => esc_html__( 'Type button text here', 'primary-addon-for-elementor' ),
                'label_block' => true,
                'dynamic' => [
                    'active' => true
                ]
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => esc_html__( 'Link', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'placeholder' => 'https://example.com',
                'dynamic' => [
                    'active' => true,
				],
				'default' => [
					'url' => '#'
				]
            ]
        );

        $this->end_controls_section();

        // Badge
        $this->start_controls_section(
            '_section_badge',
            [
                'label' => esc_html__( 'Badge', 'primary-addon-for-elementor' ),
            ]
        );

        $this->add_control(
            'show_badge',
            [
                'label' => esc_html__( 'Show', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'primary-addon-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'primary-addon-for-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => esc_html__( 'Badge Text', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Recommended', 'primary-addon-for-elementor' ),
                'placeholder' => esc_html__( 'Type badge text', 'primary-addon-for-elementor' ),
                'condition' => [
                    'show_badge' => 'yes'
                ],
                'dynamic' => [
                    'active' => true
                ]
            ]
        );

        $this->end_controls_section();    

        // Header Style
        $this->start_controls_section(
            'section_header_style',
            [
                'label' => esc_html__( 'Header', 'primary-addon-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'header_padding',
            [
                'label' => esc_html__( 'Padding', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );      
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
                'name' => 'header_typography',
                'selector' => '{{WRAPPER}} .napae-pricing-table-header',
            ]
        );
        $this->add_control(
            'header_color',
            [
                'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-header' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'header_bg_color_1',
                'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
                'types' => [ 'gradient' ],
                'selector' => '{{WRAPPER}} .napae-pricing-table-style-one .napae-pricing-table-price-wrapper, {{WRAPPER}} .napae-pricing-table-style-one .napae-pricing-table-header',
                'condition' => [
                    'pricing_style' => array('one'),
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'header_bg_color_3',
                'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
                'types' => [ 'gradient' ],
                'selector' => '{{WRAPPER}} .napae-pricing-table-style-three .napae-pricing-table-header-wrapper',
                'condition' => [
                    'pricing_style' => array('three'),
                ],
            ]
        );
        $this->end_controls_section();// end: Section    

        // Badge Style
        $this->start_controls_section(
            'section_badge_style',
            [
                'label' => esc_html__( 'Badge', 'primary-addon-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        ); 
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
                'name' => 'badge_typography',
                'selector' => '{{WRAPPER}} .napae-pricing-table-badge',
            ]
        );
        $this->add_control(
            'badge_color',
            [
                'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-badge' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'badge_bg_color',
                'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
                'types' => [ 'gradient' ],
                'selector' => '{{WRAPPER}} .napae-pricing-table-badge',
            ]
        );
        $this->end_controls_section();// end: Section 

        // Price Style
        $this->start_controls_section(
            'section_price_style',
            [
                'label' => esc_html__( 'Price', 'primary-addon-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'price_padding',
            [
                'label' => esc_html__( 'Padding', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );      
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .napae-pricing-table-price',
            ]
        );
        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-price' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'price_bg_color',
                'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
                'types' => [ 'gradient' ],
                'selector' => '{{WRAPPER}} .napae-pricing-table-style-two .napae-pricing-table-price-wrapper',
                'condition' => [
                    'pricing_style' => array('two'),
                ],
            ]
        );
        $this->end_controls_section();// end: Section 

        // Currency Style
        $this->start_controls_section(
            'section_currency_style',
            [
                'label' => esc_html__( 'Currency', 'primary-addon-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
                'name' => 'currency_typography',
                'selector' => '{{WRAPPER}} .napae-pricing-table-currency',
            ]
        );
        $this->add_control(
            'currency_color',
            [
                'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-currency' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();// end: Section  

        // Period Style
        $this->start_controls_section(
            'section_period_style',
            [
                'label' => esc_html__( 'Period', 'primary-addon-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'period_padding',
            [
                'label' => esc_html__( 'Padding', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-period' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );      
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
                'name' => 'period_typography',
                'selector' => '{{WRAPPER}} .napae-pricing-table-period',
            ]
        );
        $this->add_control(
            'period_color',
            [
                'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-period' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();// end: Section    

        // Overall Features Style
        $this->start_controls_section(
            'section_features_style',
            [
                'label' => esc_html__( 'Features', 'primary-addon-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'features_padding',
            [
                'label' => esc_html__( 'Padding', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );      
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
                'name' => 'features_typography',
                'selector' => '{{WRAPPER}} .napae-pricing-table-features-list li',
            ]
        );
        $this->add_control(
            'features_color',
            [
                'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-features-list li' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'features_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-features-list li i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();// end: Section        

        // Button
        $this->start_controls_section(
            'section_btn_style',
            [
                'label' => esc_html__( 'Button', 'primary-addon-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_section_bg_color',
                'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
                'types' => [ 'gradient' ],
                'selector' => '{{WRAPPER}} .napae-pricing-table-style-two .napae-pricing-table-footer',
                'condition' => [
                    'pricing_style' => array('two'),
                ],
            ]
        );
        $this->add_control(
            'btn_out_padding',
            [
                'label' => esc_html__( 'Button Section Padding', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'btn_out_margin',
            [
                'label' => esc_html__( 'Button Section Margin', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-table-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-btn, {{WRAPPER}} .napae-pricing-btn:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'btn_padding',
            [
                'label' => esc_html__( 'Button Padding', 'primary-addon-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .napae-pricing-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .napae-pricing-btn',
            ]
        );
        $this->start_controls_tabs( 'btn_style' );
            $this->start_controls_tab(
                'btn_normal',
                [
                    'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
                ]
            );
            $this->add_control(
                'btn_color',
                [
                    'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .napae-pricing-btn' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'btn_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .napae-pricing-btn' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'btn_border',
                    'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
                    'selector' => '{{WRAPPER}} .napae-pricing-btn',
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'btn_shadow',
                    'label' => esc_html__( 'Button Shadow', 'primary-addon-for-elementor' ),
                    'selector' => '{{WRAPPER}} .napae-pricing-btn:after',
                ]
            );
            $this->end_controls_tab();  // end:Normal tab
            $this->start_controls_tab(
                'btn_hover',
                [
                    'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
                ]
            );
            $this->add_control(
                'btn_hover_color',
                [
                    'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .napae-pricing-btn:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'btn_bg_hover_color',
                [
                    'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .napae-pricing-btn:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'btn_hover_border',
                    'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
                    'selector' => '{{WRAPPER}} .napae-pricing-btn:hover',
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'btn_hover_shadow',
                    'label' => esc_html__( 'Button Shadow', 'primary-addon-for-elementor' ),
                    'selector' => '{{WRAPPER}} .napae-pricing-btn:hover:after',
                ]
            );
            $this->end_controls_tab();  // end:Hover tab
        $this->end_controls_tabs(); // end tabs
        $this->end_controls_section();// end: Section



	}

	/**
	 * Render Upcoming widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Style
		$pricing_style = !empty( $settings['pricing_style'] ) ? $settings['pricing_style'] : 'one';
		
		// Title
		$title = !empty( $settings['title'] ) ? '<div class="napae-pricing-table-header">'.$settings['title'].'</div>' : '';
		$title = $title ? '<div class="napae-pricing-table-header-wrapper">'.$title.'</div>' : '';
		
		// Price
		$currency = !empty( $settings['currency'] ) ? '<span class="napae-pricing-table-currency">'.$settings['currency'].'</span>' : '';
		$price = !empty( $settings['price'] ) ? '<span class="napae-pricing-table-price">'.$currency.$settings['price'].'</span>' : '';
		$period = !empty( $settings['period'] ) ? '<span class="napae-pricing-table-period">'.$settings['period'].'</span>' : '';
		$price = $price ? '<div class="napae-pricing-table-price-wrapper"><div class="napae-pricing-table-price-inner">'.$price.$period.'</div></div>' : '';

		// btn
		$button_text = !empty( $settings['button_text'] ) ? $settings['button_text'] : '';
		$button_link = !empty( $settings['button_link']['url'] ) ? $settings['button_link']['url'] : '';
		$button_link_external = !empty( $settings['button_link']['is_external'] ) ? 'target="_blank"' : '';
		$button_link_nofollow = !empty( $settings['button_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$button_link_attr = !empty( $button_link ) ?  $button_link_external.' '.$button_link_nofollow : '';

		$button = $button_link ? '<div class="napae-pricing-table-footer"><a href="'.esc_url($button_link).'" class="napae-pricing-btn" '.$button_link_attr.'>'.esc_html($button_text).'</a></div>' : '';

		ob_start();

		?>
		<div class="napae-pricing-table napae-pricing-table-style-<?php echo esc_attr($pricing_style); ?>">
			<?php if($settings['show_badge'] && $settings['badge_text']) { ?>
			<div class="napae-pricing-table-badge"><?php echo $settings['badge_text']; ?></div>
			<?php } ?>
			<div class="napae-pricing-table-wrapper">
				<?php echo $title . $price; ?>
	            <?php if ( is_array( $settings['features_list'] ) ) : ?>
				<div class="napae-pricing-table-features">
	                <ul class="napae-pricing-table-features-list">
	                    <?php foreach ( $settings['features_list'] as $index => $feature ) : ?>
	                        <li class="">
	                            <?php 
	                            if ( ! empty( $feature['selected_icon']['value'] ) ) :
	                                echo '<i class="'.$feature["selected_icon"]["value"].'"></i>';
	                            endif; 
	                            echo $feature['text']
	                            ?>
	                        </li>
	                    <?php endforeach; ?>
	                </ul>				
				</div>
	            <?php endif; ?>	
				<?php echo $button; ?>
			</div>
		</div>
		<?php
		echo ob_get_clean();

	}

	/**
	 * Render Upcoming widget output in the editor.
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	*/

	//protected function _content_template(){}

}
Plugin::instance()->widgets_manager->register_widget_type( new Primary_Addon_Pricing_Table() );

} // enable & disable
