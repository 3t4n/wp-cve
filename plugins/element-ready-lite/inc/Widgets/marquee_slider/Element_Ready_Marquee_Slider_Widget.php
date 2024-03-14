<?php

namespace Element_Ready\Widgets\marquee_slider;

use Elementor\Widget_Base;
use Elementor\Repeater;


if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Element_Ready_Marquee_Slider_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'er_marquee_sldier_widget';
    }

    public function get_title()
    {
        return __('ER Marquee Slider', 'element-ready-lite');
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid';
    }

    public function get_categories()
    {
        return ['element-ready-addons'];
    }

    public function get_keywords()
    {
        return ['marquee slider', 'slick'];
    }


    public function get_style_depends()
    {


        return [
            'er-marquee',
        ];
    }


    protected function register_controls()
    {

        /*---------------------------
            CONTENT SECTION
        -----------------------------*/
        $this->start_controls_section(
            'my_section',
            [
                'label' => esc_html__('Marquee Content', 'element-ready-lite'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'marquee_style',
            [
                'label' => esc_html__('Marquee Style', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => [
                    'style1' => esc_html__('Style One', 'element-ready-lite'),
                    'style2'  => esc_html__('Style Two', 'element-ready-lite'),
                ],
            ]
        );

        $this->add_control(
            'marquee_icon',
            [
                'label' => esc_html__('Icon', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'circle',
                        'dot-circle',
                        'square-full',
                    ],
                    'fa-regular' => [
                        'circle',
                        'dot-circle',
                        'square-full',
                    ],
                ],
            ]
        );



        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'marquee_item_title',
            [
                'label' => esc_html__('Title', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('List Title', 'element-ready-lite'),
                'label_block' => true,
            ]
        );


        $this->add_control(
            'marquee_lists',
            [
                'label' => esc_html__('Repeater List', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'marquee_item_title' => esc_html__('Title #1', 'element-ready-lite'),
                    ],
                    [
                        'marquee_item_title' => esc_html__('Title #2', 'element-ready-lite'),
                    ],
                ],
                'title_field' => '{{{ marquee_item_title }}}',
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Marquee Style', 'element-ready-lite'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'marquee_typho',
                'selector' => '{{WRAPPER}} .marquee-scroll-text',
            ]
        );


        $this->add_control(
            'marquee_bg_color',
            [
                'label' => esc_html__('Background Color', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .marquee-scroll-main' => 'background: {{VALUE}}',
                ],
            ]
        );


        $this->add_control(
            'marquee_text_color',
            [
                'label' => esc_html__('Text Color', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .marquee-scroll-text' => 'color: {{VALUE}};-webkit-text-fill-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'marquee_stroke',
				'selector' => '{{WRAPPER}} .marquee-scroll-text',
			]
		);

		$this->add_control(
            'marquee_icon_color',
            [
                'label' => esc_html__('Icon Color', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .marquee-section span i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .marquee-section span svg' => 'fill: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'marquee_icon_font',
                'label' => esc_html__('Icon Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .marquee-section span i',
            ]
        );
		
		// Width
		$this->add_control(
			'marquee_icon_width',
			[
				'label'      => esc_html__('SVG Icon Width', 'element-ready-lite'),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .marquee-section span, {{WRAPPER}} .marquee-section span svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height
		$this->add_control(
			'marquee_icon_height',
			[
				'label'      => esc_html__('SVG Icon Height', 'element-ready-lite'),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .marquee-section span, {{WRAPPER}} .marquee-section span svg' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
        $this->add_control(
            'marquee_angle_color',
            [
                'label' => esc_html__('Marquee Angle Color', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .marquee-scroll-two .marquee-wrapper' => 'background: {{VALUE}}',
                ],
            ]
        );
		

        $this->add_responsive_control(
            'title_padding',
            [
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .marquee-scroll-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'label' => esc_html__('Container Padding', 'element-ready-lite'),
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .marquee-scroll' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render($instance = [])
    {
        $settings = $this->get_settings_for_display();
        $marquee_style = $settings['marquee_style'];

        if ($marquee_style === 'style1') { ?>
            <div class="marquee-scroll marquee-scroll-two">
                <div class="marquee-wrapper">
                    <div class="marquee-scroll-main">
                        <div class="marquee-scroll-item">
                            <?php
                            foreach ($settings['marquee_lists'] as $marquee_item) { ?>
                                <div class="marquee-section">
                                <p class="marquee-scroll-text"><?php echo esc_html($marquee_item['marquee_item_title']); ?></p>
                                <span>
                                    <?php \Elementor\Icons_Manager::render_icon($settings['marquee_icon'], ['aria-hidden' => 'true']); ?>
                                </span>
                            </div>
                            <?php }

                            ?>
                        </div>
                        <div class="marquee-scroll-item">
                            <?php
                            foreach ($settings['marquee_lists'] as $marquee_item) { ?>
                                <div class="marquee-section">
                                <p class="marquee-scroll-text"><?php echo esc_html($marquee_item['marquee_item_title']); ?></p>
                                <span>
                                    <?php \Elementor\Icons_Manager::render_icon($settings['marquee_icon'], ['aria-hidden' => 'true']); ?>
                                </span>
                            </div>
                            <?php }

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="marquee-scroll">
                <div class="marquee-scroll-main">
                    <div class="marquee-scroll-item">

                        <?php
                        foreach ($settings['marquee_lists'] as $marquee_item) { ?>
                            <div class="marquee-section">
                            <p class="marquee-scroll-text"><?php echo esc_html($marquee_item['marquee_item_title']); ?></p>
                                <span>
                                    <?php \Elementor\Icons_Manager::render_icon($settings['marquee_icon'], ['aria-hidden' => 'true']); ?>
                                </span>
                            </div>
                        <?php }

                        ?>


                    </div>
                    <div class="marquee-scroll-item">

                        <?php
                        foreach ($settings['marquee_lists'] as $marquee_item) { ?>
                            <div class="marquee-section">
                            <p class="marquee-scroll-text"><?php echo esc_html($marquee_item['marquee_item_title']); ?></p>
                                <span>
                                    <?php \Elementor\Icons_Manager::render_icon($settings['marquee_icon'], ['aria-hidden' => 'true']); ?>
                                </span>
                            </div>
                        <?php }

                        ?>


                    </div>
                </div>
            </div>
<?php }
    }
}
