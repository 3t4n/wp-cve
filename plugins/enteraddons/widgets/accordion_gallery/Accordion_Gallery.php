<?php
namespace Enteraddons\Widgets\Accordion_Gallery;

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
 * Enteraddons elementor Accordion Gallery widget.
 *
 * @since 1.0
 */

class Accordion_Gallery extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-accordion-gallery';
	}

	public function get_title() {
		return esc_html__( 'Accordion Gallery', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-accordion-gallery';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ---------------------------------------- Accordion Gallery content ------------------------------
        $this->start_controls_section(
            'enteraddons_accordion_gallery_content_settings',
            [
                'label' => esc_html__( 'Accordion Gallery Content', 'enteraddons' ),
            ]
        );
        $repeater->add_control(
            'gallery_title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Jason E. Austin', 'enteraddons' ),
            ]
        );
        $repeater->add_control(
            'gallery_subtitle',
            [
                'label' => esc_html__( 'Sub Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Welcome to my amazing world of photos','enteraddons'),
            ]
        );
        $repeater->add_control(
            'description',
            [
                'label' => esc_html__( 'Description', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' =>  esc_html__('Pork belly Carles Wes Anderson semiotics, Thundercats Truffaut iPhone chambray blog normcore direct
                 trade Pinterest gentrify. Pinterest gentrify fanny pack Truffaut,
                 bitters brunch hella kogi craft beer readymade. Synth whatever trust fund Marfa before they sold out, chillwave lomo','enteraddons'),
            ]
         );
        $repeater->add_control(
            'button_label',
            [
                'label' => esc_html__( 'Trigger Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'SEE DESCRIPTION','enteraddons'),
            ]
        );
        $repeater->add_control(
            'image',
            [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'more_link',
            [
                'label' => esc_html__( 'Link', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'enteraddons' ),
                'show_external' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );
        $repeater->add_control(
            'link_label',
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
            'gallery_item_list',
            [
                'label' => esc_html__( 'Gallery Item List', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default' => [
                    [
                        'gallery_title' =>esc_html__( 'Jason E. Austin', 'enteraddons' ),
                    
                    ],
                ],
               'title_field' => '{{{ gallery_title }}}',
            ]
        );
    
        $this->end_controls_section(); // End Accordion Gallery Content

         /**
         * Style Tab
         * ------------------------------ Content Wrapper Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_team_wrapper_style_settings', [
                'label' => esc_html__( 'Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'Wrapper_width',
            [
                'label' => esc_html__( 'Wrapper Width', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-accordion-gallery' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_alignment',
            [
                'label' => esc_html__( 'Content Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info' => 'text-align: {{VALUE}} !important',
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-description' => 'text-align: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_control(
            'wrapper_overlay',
            [
                'label' => esc_html__( 'Overlay Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_overlay',
                'label' => esc_html__( 'Wrapper Overlay', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-gallery-overlay',
            ]
        );

        $this->add_responsive_control(
			'wrapper_opacity',
			[
				'label' => esc_html__( 'Opacity', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => .1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => .6,
				],
				'selectors' => [
					'{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-gallery-overlay' => 'opacity: {{SIZE}}',
				],
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
                    '{{WRAPPER}} .ea-accordion-gallery' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-accordion-gallery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'item_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-accordion-gallery',
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
                    '{{WRAPPER}} .ea-accordion-gallery' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-accordion-gallery',
            ]
        ); 

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-accordion-gallery',
            ]
        );

        $this->end_controls_section();

        /**
        * Style Tab
        * ------------------------------ Title Style ------------------------------
        *
        */
        $this->start_controls_section(
            'enteraddons_gallery_title_style', [
                'label' => esc_html__( 'Title Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'title__hover_color',
            [
                'label' => esc_html__( 'Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info:hover .ea-vg-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-title',
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'title_stroke',
				'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-title',
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
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'title_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-title',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-title',
            ]
        );
        $this->add_responsive_control(
            'title_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
        * Style Tab
        * ------------------------------ Sub Title Style ------------------------------
        *
        */
        $this->start_controls_section(
            'enteraddons_gallery_sub_title_style', [
                'label' => esc_html__( 'Sub Title Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'sub_title_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'sub_title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-subtitle',
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'sub_title_stroke',
				'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-subtitle',
			]
		);
        $this->add_responsive_control(
            'sub_title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'sub_title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'sub_title_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-subtitle',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'sub_title_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-subtitle',
            ]
        );
        $this->add_responsive_control(
            'sub_title_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Gallery Trigger Button Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_trigger_button_settings', [
                'label' => esc_html__( 'Trigger Button Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'tab_trigger_button' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'trigger_button_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'trigger_button_typography',
				'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn',
			]
		);
		$this->add_responsive_control(
			'trigger_button_margin',
			[
				'label' => esc_html__( 'Margin', 'enteraddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'trigger_button_padding',
			[
				'label' => esc_html__( 'Padding', 'enteraddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'trigger_button_border',
				'label'     => esc_html__( 'Border', 'enteraddons' ),
				'selector'  => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn',
			]
		);
		$this->add_responsive_control(
			'trigger_button_radius',
			[
				'label' => esc_html__( 'Border Radius', 'enteraddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'trigger_button_shadow',
				'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
				'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn',
			]
		); 
		$this->add_control(
			'trigger_button_text_color',
			[
				'label' => esc_html__( 'Color', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'trigger_button_background',
				'label' => esc_html__( 'Background', 'enteraddons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn',
			]
		);
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'trigger_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'trigger_button_hover_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn:hover',
            ]
        );
        $this->add_responsive_control(
            'trigger_button_hover_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'trigger_button_hover_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn:hover',
            ]
        ); 
        $this->add_control(
            'trigger_button_hover_text_color',
            [
                'label' => esc_html__( 'Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'trigger_button_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-btn:hover',
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();


         /**
        * Style Tab
        * ------------------------------ Description Style ------------------------------
        *
        */
        $this->start_controls_section(
            'enteraddons_gallery_description_style', [
                'label' => esc_html__( 'Description Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-description' => 'color: {{VALUE}}',
                ],
            ]
        );
       
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-description',
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'description_stroke',
				'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-description',
			]
		);
        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'description_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'description_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-description',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'description_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-description',
            ]
        );
        $this->add_responsive_control(
            'description_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-accordion-gallery .ea-gallery-item .ea-vg-info .ea-vg-description' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Gallery Button Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_gallery_button_settings', [
                'label' => esc_html__( 'Button Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => esc_html__( 'Button Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'solid',
                ]
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-vg-info .ea-vg-btn i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
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
                    '{{WRAPPER}} .ea-vg-info .ea-vg-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-vg-info .ea-vg-btn i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'tab_button' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'button_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .ea-gallery-button',
			]
		);
		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'enteraddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ea-gallery-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'enteraddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ea-gallery-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'button_border',
				'label'     => esc_html__( 'Border', 'enteraddons' ),
				'selector'  => '{{WRAPPER}} .ea-gallery-button',
			]
		);
		$this->add_responsive_control(
			'button_radius',
			[
				'label' => esc_html__( 'Border Radius', 'enteraddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ea-gallery-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_shadow',
				'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
				'selector' => '{{WRAPPER}} .ea-gallery-button',
			]
		); 

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Color', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ea-gallery-button' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'label' => esc_html__( 'Background', 'enteraddons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ea-gallery-button',
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
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'button_hover_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-gallery-button:hover',
            ]
        );
        $this->add_responsive_control(
            'button_hover_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-gallery-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-gallery-button:hover',
            ]
        ); 
        $this->add_control(
            'button_hover_text_color',
            [
                'label' => esc_html__( 'Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-gallery-button:hover' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-gallery-button:hover',
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();
   
	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // template render
        $obj = new Accordion_Gallery_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }

    public function get_script_depends() {
        return [ 'enteraddons-main'];
    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style'];
    }
}
