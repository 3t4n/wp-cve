<?php
namespace Enteraddons\Widgets\Events_Card;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Enteraddons elementor Events_Card widget.
 *
 * @since 1.0
 */
class Events_Card extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-events-card';
	}

	public function get_title() {
		return esc_html__( 'Events Card', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-events-card';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

        //------------------------------ Events Card Style ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_layout', [
                'label' => esc_html__( 'Layout', 'enteraddons' )
            ]
        );
        $this->add_control(
            'event_style',
            [
                'label' => esc_html__( 'Event Style', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'style-1' => esc_html__( 'Style 1', 'enteraddons' ),
                    'style-2' => esc_html__( 'Style 2', 'enteraddons' )
                ],
                'default' => 'style-1'
            ]
        );
        $this->add_responsive_control(
            'content_alignment',
            [
                'label' => esc_html__( 'Content Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'condition' => [ 'event_style' => 'style-2' ],
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
                    '{{WRAPPER}} .enteraddons-single-event' => 'text-align: {{VALUE}} !important',
                    '{{WRAPPER}} .enteraddons-single-event .el-event-location' => 'text-align: {{VALUE}} !important',
                ],
            ]
        );
        $this->end_controls_section();

        // ----------------------------------------  Event Card Title content ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_content_title',
            [
                'label' => esc_html__( 'Event Title', 'enteraddons' ),
            ]
        );
       
        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'DROP THE EVENTS NAME HERE'
            ]
        );
        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__( 'Set HTML Tag', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'span' => 'span',
                    'p' => 'p',
                    'div' => 'div'
                ],
                'default' => 'h6'
            ]
        );
        
        $this->end_controls_section(); // End Team content
        // ----------------------------------------  Event Card Type content ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_event_type',
            [
                'label' => esc_html__( 'Event Type', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'event_type',
            [
                'label' => esc_html__( 'Type', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'Rock Band'
            ]
        );
        $this->add_control(
            'event_type_icon',
            [
                'label' => esc_html__( 'Icons', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-music',
                    'library' => 'solid',
                ],
            ]
        );


        $this->end_controls_section(); // End Event Card content
        // ----------------------------------------  Event Card place content ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_event_place',
            [
                'label' => esc_html__( 'Event Place', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'event_place',
            [
                'label' => esc_html__( 'Place', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Californoia, USA', 'enteraddons' )
            ]
        );
        $this->add_control(
            'event_place_icon',
            [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-map-marker-alt',
                    'library' => 'solid',
                ],
            ]
        );
        $this->end_controls_section(); // End Event Card content
        // ----------------------------------------  Event Card Time content ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_event_time',
            [
                'label' => esc_html__( 'Event Date&Time', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'event_date',
            [
                'label' => esc_html__( 'Date', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'description' => esc_html__( 'This field support span, small, br, b, strong, i html tag.', 'enteraddons' ),
                'label_block' => true,
                'default' => '26 AUG<b>2019</b>'
            ]
        );
        $this->add_control(
            'event_date_icon',
            [
                'label' => esc_html__( 'Date Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-calendar-check',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_control(
            'event_time',
            [
                'label' => esc_html__( 'Time', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => '11:00 am - 05:30 PM'
            ]
        );
        $this->add_control(
            'event_time_icon',
            [
                'label' => esc_html__( 'Time Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-clock',
                    'library' => 'solid',
                ],
            ]
        );
        $this->end_controls_section(); // End Event Card content
        // ----------------------------------------  Event Card Ticket Price content ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_event_ticket_price',
            [
                'label' => esc_html__( 'Event Ticket Price', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'event_ticket_price',
            [
                'label' => esc_html__( 'Ticket Price', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'description' => esc_html__( 'This field support span, br, b, strong, i html tag.', 'enteraddons' ),
                'label_block' => true,
                'default' => '$15.99/Person'
            ]
        );
        
        $this->end_controls_section(); // End Event Card content

        // ----------------------------------------  Event Card description content ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_description',
            [
                'label' => esc_html__( 'Event Short Description', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'event_description',
            [
                'label' => esc_html__( 'Description', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore esse.', 'enteraddons' )
            ]
        );
        
        $this->end_controls_section(); // End Event Card content
        // ----------------------------------------  Event Card button content ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_event_btn',
            [
                'label' => esc_html__( 'Button', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'btn_label',
            [
                'label' => esc_html__( 'Button Label', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true
            ]
        );
        $this->add_control(
            'btn_link',
            [
                'label' => esc_html__( 'Link', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'enteraddons' ),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $this->end_controls_section(); // End Event Card content
        // ----------------------------------------  Event Card image content ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_image',
            [
                'label' => esc_html__( 'Image', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'img', [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
                
        $this->end_controls_section(); // End Event Card content
        
        /**
         * Style Tab
         * ------------------------------ Item Content Wrapper Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_event_card_wrapper_style_settings', [
                'label' => esc_html__( 'Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tab_event_card_wrapper' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_wrapper_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_responsive_control(
            'item_content_width',
            [
                'label' => esc_html__( 'Content Width', 'enteraddons' ),
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
                    'size' => '75',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-event-card-style-1 .el-event-info' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['event_style' => 'style-1']
            ]
        );

        $this->add_responsive_control(
            'item_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event.enteraddons-single-event-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event.enteraddons-single-event-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'wrapper_border_heading',
            [
                'label' => esc_html__( 'Wrapper Border', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'item_border',
                'label'     => esc_html__( 'Wrapper Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .enteraddons-single-event.enteraddons-single-event-card',
            ]
        );
        
        $this->add_responsive_control(
            'item_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event.enteraddons-single-event-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_control(
            'info_border_heading',
            [
                'label' => esc_html__( 'Info Border', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'event_info_border',
                'label'     => esc_html__( 'Event Info Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .enteraddons-single-event.enteraddons-single-event-card .el-event-info',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event.enteraddons-single-event-card',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-single-event.enteraddons-single-event-card',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'item_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'item_hover_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .enteraddons-single-event.enteraddons-single-event-card:hover',
                ]
            );
            $this->add_responsive_control(
                'item_hover_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-event.enteraddons-single-event-card:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_hover_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-event.enteraddons-single-event-card:hover',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'item_hover_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-single-event.enteraddons-single-event-card:hover',
                ]
            );


        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        //------------------------------ Title Style ------------------------------
        $this->start_controls_section( 
            'enteraddons_event_card_title_style', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .el-event-title',
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        //------------------------------ Event Type Style ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_type_style', [
                'label' => esc_html__( 'Type', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'type_color',
            [
                'label' => esc_html__( 'Type Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-cat' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'type_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-cat i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'type_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .el-event-cat',
            ]
        );
        $this->add_responsive_control(
            'type_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-cat' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'type_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-cat' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
 
        //------------------------------ Event Place Style ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_place_style', [
                'label' => esc_html__( 'Place', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'place_color',
            [
                'label' => esc_html__( 'Place Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-place' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'place_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-place i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'place_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .el-event-place',
            ]
        );
        $this->add_responsive_control(
            'place_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-place' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'place_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-place' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        //------------------------------ Event Date Style ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_date_style', [
                'label' => esc_html__( 'Date', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'date_position',
            [
                'label' => esc_html__( 'Date Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top-left',
                'options' => [
                    'top-left' => esc_html__( 'Top Left', 'enteraddons' ),
                    'top-right' => esc_html__( 'Top Right', 'enteraddons' ),
                    'bottom-left' => esc_html__( 'Bottom Left', 'enteraddons' ),
                    'bottom-right' => esc_html__( 'Bottom Right', 'enteraddons' )
                ]
            ]
        );
        $this->add_control(
            'date_color',
            [
                'label' => esc_html__( 'Date Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-date' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'date_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-date i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .el-event-date',
            ]
        );
        $this->add_responsive_control(
            'date_wrapper_width',
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
                    'unit' => 'px',
                    'size' => '50',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-date' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'date_wrapper_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
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
                    'size' => '52',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-date' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'date_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .el-event-date',
            ]
        );
        $this->add_responsive_control(
            'date_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'date_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        //------------------------------ Event Time Style ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_time_style', [
                'label' => esc_html__( 'Time', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'time_color',
            [
                'label' => esc_html__( 'Time Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-time' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'time_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-time i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'time_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .el-event-time',
            ]
        );
        $this->add_responsive_control(
            'time_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-time' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'time_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-time' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        //------------------------------ Event Price Style ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_price_style', [
                'label' => esc_html__( 'Price', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Price Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-price' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .el-event-price',
            ]
        );
        $this->add_responsive_control(
            'price_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'price_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-event-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        //------------------------------ Event Descriptions Style ------------------------------
        $this->start_controls_section(
            'enteraddons_event_card_descriptions_style', [
                'label' => esc_html__( 'Descriptions', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'descriptions_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-short-des' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'descriptions_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .el-short-des',
            ]
        );
        $this->add_responsive_control(
            'descriptions_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-short-des' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'descriptions_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .el-short-des' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Image Style Settings ------------------------------
         *
         */
        
        $this->start_controls_section(
            'enteraddons_event_cart_img_settings', [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'tab_img_icon' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_img_general',
            [
                'label' => esc_html__( 'General', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'image_style',
            [
                'label' => esc_html__( 'Border Style', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'img-1',
                'options' => [
                    'img-1'  => esc_html__( 'Image Style 1', 'enteraddons' ),
                    'img-2'  => esc_html__( 'Image Style 2', 'enteraddons' ),
                ],
            ]
        );
        $this->add_control(
            'image_hover_overlay',
            [
                'label' => esc_html__( 'Image Hover Overlay', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_hover_overlay_bg',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-overlay:after',
                'condition' => ['image_hover_overlay' => 'yes']
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_img_wrapper',
            [
                'label' => esc_html__( 'Wrapper', 'enteraddons' ),
            ]
        );
        $this->add_responsive_control(
            'img_container_width',
            [
                'label' => esc_html__( 'Image Container Width', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-event-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_container_height',
            [
                'label' => esc_html__( 'Image Container Height', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-event-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'img_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-event-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-event-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'img_wrapper_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-event-image',
            ]
        );
        $this->add_responsive_control(
            'img_wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-event-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'img_box_bg',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-event-image',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'img_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-event-image',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For image style settings
        $this->start_controls_tab(
            'img_style',
            [
                'label' => esc_html__( 'Image', 'enteraddons' ),
            ]
        );

        $this->add_responsive_control(
            'img_width',
            [
                'label' => esc_html__( 'Image Width', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-event-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_height',
            [
                'label' => esc_html__( 'Image Height', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-event-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-event-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'img_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-event-image img',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Event Card Button Style ------------------------------
         *
         */
        $this->start_controls_section(
            'event_card_btn_settings', [
                'label' => esc_html__( 'Button Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE
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
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn',
            ]
        );
        //  Controls tab start
        $this->start_controls_tabs( 'btn_tabs_start' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'btn_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        
        $this->add_control(
            'btn_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn',
            ]
        );
        $this->add_responsive_control(
            'btn_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_bg_color',
                'label' => esc_html__( 'Button Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'btn_hover_normal',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => esc_html__( 'Hover Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_hover_border',
                'label' => esc_html__( 'Hover Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn:hover',
            ]
        );
        $this->add_responsive_control(
            'btn_hover_radius',
            [
                'label' => esc_html__( 'Hover Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_hover_box_shadow',
                'label' => esc_html__( 'Hover Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_hover_bg_color',
                'label' => esc_html__( 'Hover Button Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-single-event-card .enteraddons-btn:hover:after',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();
        
	}

	protected function render() {
        
        // get settings
        $settings = $this->get_settings_for_display();
        // Tema template render
        $obj = new Events_Card_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }

    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }


}
