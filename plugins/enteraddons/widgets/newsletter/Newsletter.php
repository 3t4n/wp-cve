<?php
namespace Enteraddons\Widgets\Newsletter;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *
 * Enteraddons elementor newsletter widget.
 *
 * @since 1.0
 */
class Newsletter extends Widget_Base {

	public function get_name() {
		return 'enteraddons-newsletter';
	}

	public function get_title() {
		return esc_html__( 'Newsletter', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-news-letter';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

    public function get_mailchimp_list() {
        $options = [ '' => esc_html__( 'Select List ID', 'enteraddons' ) ];

        // Call API to get mailchimp list id

        $getAPIKey = get_option(ENTERADDONS_OPTION_KEY);

        if( isset( $getAPIKey['integration']['mailchimp_token'] ) && !empty( $getAPIKey['integration']['mailchimp_token'] ) ) {
            $apiKey = $getAPIKey['integration']['mailchimp_token'];
            $serverType = explode('-', $apiKey);

            if( !empty( $serverType[1] ) ) {

                $apiUrl = 'https://'.esc_attr($serverType[1]).'.api.mailchimp.com/3.0/lists?apikey='.$apiKey;

                $getResponse = wp_remote_get($apiUrl, []);

                if ( is_array( $getResponse ) && ! is_wp_error( $getResponse ) ) {
                    $headers = $getResponse['headers']; 
                    $body    = (array) json_decode( $getResponse['body'] ); 

                    $lists  = isset( $body['lists'] ) ? $body['lists'] : [];
                    
                    if( is_array( $lists ) && !empty( $lists ) ){
                        foreach( $lists as $l ){
                            $options[$l->id] = $l->name;
                        }
                        
                    }

                }

            }

        }

        return $options;

    }

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Newsletter content ------------------------------
        $this->start_controls_section(
            'enteraddons_newsletter_content',
            [
                'label' => esc_html__( 'Newsletter Content', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'newsletter_list_id',
            [
                'label' => esc_html__( 'Mailchimp List ID', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_mailchimp_list()
            ]
        );
        $this->add_control(
            'newsletter_type',
            [
                'label' => esc_html__( 'Layout Type', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'vertical',
                'options' => [
                    'vertical'   => esc_html__( 'Vertical', 'enteraddons' ),
                    'horizontal' => esc_html__( 'Horizontal', 'enteraddons' ),
                ],
            ]
        );
        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1'   => esc_html__( 'Style 1', 'enteraddons' ),
                    '2' => esc_html__( 'Style 2', 'enteraddons' )
                ],
            ]
        );
        $this->add_control(
            'input_place_holder',
            [
                'label' => esc_html__( 'Input Place Holder', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'Enter Your E-mail Address'
            ]
        );
        $this->add_control(
            'search_btn_text',
            [
                'label' => esc_html__( 'Search Button Text', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'Subscribe Now'
            ]
        );
        $this->add_control(
            'search_btn_icon',
            [
                'label' => esc_html__( 'Search Button Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-paper-plane',
                    'library' => 'solid',
                ],
            ]
        );
        
        $this->end_controls_section(); // End content

        /**
         * Style Tab
         * ------------------------------ Style area Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_newsletter_content_wrapper_settings', [
                'label' => esc_html__( 'Content Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_max_width',
            [
                'label' => esc_html__( 'Max Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-wrapper form' => 'max-width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_alignment',
            [
                'label' => esc_html__( 'Content Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => ' eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => ' eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-wrapper' => 'display:flex;justify-content: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-wrapper',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-wrapper',
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Divider Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_newsletter_divider_settings', [
                'label' => esc_html__( 'Divider Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'newsletter_type' => 'horizontal', 'style' => '2' ]
            ]
        );
        $this->add_responsive_control(
            'divider_width',
            [
                'label' => esc_html__( 'Divider Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '2',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner:before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'divider_height',
            [
                'label' => esc_html__( 'Divider Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '30',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner:before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'divider_top_position',
            [
                'label' => esc_html__( 'Divider Top Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner:before' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'divider_bottom_position',
            [
                'label' => esc_html__( 'Divider Bottom Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner:before' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'divider_left_position',
            [
                'label' => esc_html__( 'Divider Left Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner:before' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'divider_right_position',
            [
                'label' => esc_html__( 'Divider Right Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '180',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner:before' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_input_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-inner:before',
            ]
        );
        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_newsletter_input_settings', [
                'label' => esc_html__( 'Input Field', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'input_field_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'input_field_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'input_field_wrapper_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '100',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-input' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'input_field_wrapper_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '40',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-input' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'input_field_color',
            [
                'label' => esc_html__( 'Input Field Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-input' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'input_field_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-input',
            ]
        );
        $this->add_control(
            'input_field_placeholder_color',
            [
                'label' => esc_html__( 'Input Field Placeholder Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-input::placeholder' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_responsive_control(
            'input_field_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-input' => 'text-align: {{VALUE}};',
                ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'input_field_wrapper_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-input',
            ]
        );
        $this->add_responsive_control(
            'input_field_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'input_field_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-input',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'input_field_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-input',
            ]
        );
        $this->end_controls_section();
        
        /**
         * Style Tab
         * ------------------------------ Button Wrapper Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_newsletter_btn_settings', [
                'label' => esc_html__( 'Button', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'newsletter_btn_wrap_tab' );
        $this->start_controls_tab(
            'btn_wrap_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'btn_text_color',
            [
                'label' => esc_html__( 'Button Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'btn_text_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn',
            ]
        );
        $this->add_responsive_control(
            'btn_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_position',
            [
                'label' => esc_html__( 'Button Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'condition' => [ 'style' => '2' ],
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}} .style-2 .layout-horizontal .enteraddons-newsletter-btn' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn' => 'text-align: {{VALUE}};',
                ]
            ]
        );
        $this->add_responsive_control(
            'btn_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn',
            ]
        );
        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_bg',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn',
            ]
        );
        

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'btn_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'btn_hover_text_color',
            [
                'label' => esc_html__( 'Button Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_hover_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_hover_box_bg',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-newsletter-inner .enteraddons-newsletter-btn:hover',
            ]
        );
        
        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();
        
        /**
         * Style Tab
         * ------------------------------ Icon Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_newsletter_icon_settings', [
                'label' => esc_html__( 'Button Icon', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'tab_newsletter_icon' );
        //  Controls tab For Normal
        $this->start_controls_tab(
            'icon_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-btn i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_icon_margin',
            [
                'label' => esc_html__( 'Icon Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-btn i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .enteraddons-newsletter-btn img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'icon_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__( 'Icon Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-newsletter-btn:hover i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();

	}

	protected function render() {
        // get settings
        $settings = $this->get_settings_for_display();

        // Testimonial template render
        $obj = new Newsletter_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
    }

    public function get_script_depends() {
        return [ 'enteraddons-main'];
    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }


}
