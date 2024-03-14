<?php



use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;




class wpsection_wps_accordion_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'wpsection_wps_accordion';
    }

    public function get_title()
    {
        return __('Accordion', 'wpsection');
    }

    public function get_icon()
    {
        return 'eicon-accordion';
    }

    public function get_keywords()
    {
        return ['wpsection', 'accordion'];
    }

    public function get_categories()
    {
        return ['wpsection_category'];
    }


    /**
     * Register content related controls
     */
    protected function _register_controls()
    {

        $this->start_controls_section(
            'section_accordion_content',
            [
                'label' => esc_html__('Accordion Items', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'style',
            [
                'label'   => esc_html__('Choose Different Style', 'wpsection'),
                'label_block' => true,
                'type'    => Controls_Manager::SELECT,
                'default' => 'style1',
                'options' => array(
                    'style1' => esc_html__('Choose Style 1', 'wpsection'),
                    'style2' => esc_html__('Choose Style 2', 'wpsection'),
                    'style3' => esc_html__('Choose Style 3', 'wpsection'),
                ),
            ]
        );
        $this->add_control(
            'sec_class',
            [
                'label'   => esc_html__('Choose Different Style', 'wpsection'),
                'label_block' => true,
                'type'    => Controls_Manager::SELECT,
                'default' => '2',
                'options' => array(
                    '1' => esc_html__('Style 1', 'wpsection'),
                    '2' => esc_html__('Style 2', 'wpsection'),
                    '3' => esc_html__('Style 3', 'wpsection'),
                    '4' => esc_html__('Style 4', 'wpsection'),
                    '5' => esc_html__('Style 5', 'wpsection'),


                ),
            ]
        );


        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__('Title', 'wpsection'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'content',
            [
                'label'       => esc_html__('Description', 'wpsection'),
                'type'        => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'accordions',
            [
                'label'       => esc_html__('Content', 'wpsection'),
                'show_label'  => false,
                'type'        => Controls_Manager::REPEATER,
                'separator'   => 'before',
                'title_field' => '{{ title }}',
                'dynamic'     => [
                    'active' => true,
                ],
                'default'     => [
                    [
                        'title'   => esc_html__('How to Change my Photo from Admin Dashboard? ', 'wpsection'),
                        'content' => esc_html__('Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast', 'wpsection'),
                    ],
                    [
                        'title'   => esc_html__('How to Change my Password easily?', 'wpsection'),
                        'content' => esc_html__('Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast', 'wpsection'),
                    ],
                    [
                        'title'   => esc_html__('How to Change my Subscription Plan using PayPal', 'wpsection'),
                        'content' => esc_html__('Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast', 'wpsection'),
                    ],
                ],
                'fields'      => $repeater->get_controls(),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_accordion_style',
            [
                'label' => esc_html__('Style', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'acdn_item_margin',
            [
                'label'      => esc_html__('Item Margin', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-accordion .wpsection-accordion-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'      => 'background',
                'label'     => esc_html__('Background', 'wpsection'),
                'types'     => ['gradient'],
                'selector'  => '{{WRAPPER}} .wpsection-accordion-3 .wpsection-accordion-item.active .wpsection-accordion-title',
                'condition' => [
                    'style' => ['3'],
                ],
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label'     => esc_html__('Primary Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-accordion .wpsection-accordion-inner' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wpsection-accordion .toggle-arrow-bg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'style' => ['4', '5'],
                ],
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label'     => esc_html__('Secondary Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-accordion-5 .wpsection-accordion-item' => 'background-color: {{VALUE}};'
                ],
                'condition' => [
                    'style' => ['4', '5'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Title Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-accordion .wpsection-accordion-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_active_color',
            [
                'label'     => esc_html__('Title Active Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-accordion .wpsection-accordion-item.active .wpsection-accordion-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_title',
                'selector' => '{{WRAPPER}} .wpsection-accordion .wpsection-accordion-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__('Margin', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-accordion .wpsection-accordion-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label'      => esc_html__('Padding', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-accordion .wpsection-accordion-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_icon_style',
            [
                'label' => esc_html__('Toggle Icon', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'toggle_color',
            [
                'label'     => esc_html__('Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-accordion .toggle-icon:after, {{WRAPPER}} .wpsection-accordion .toggle-icon:before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wpsection-accordion .toggle-icon svg .toggle-arrow'                                       => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_active_color',
            [
                'label'     => esc_html__('Active Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-accordion  .wpsection-accordion-item.active .toggle-icon:after, {{WRAPPER}} .wpsection-accordion .wpsection-accordion-item.active .toggle-icon:before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wpsection-accordion  .wpsection-accordion-item.active .toggle-icon svg .toggle-arrow'                                                                    => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_open_bg',
            [
                'label'     => esc_html__('Open Background', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-accordion-6 .onoffswitch-label' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'style' => ['6'],
                ]
            ]
        );

        $this->add_control(
            'toggle_close_bg',
            [
                'label'     => esc_html__('Close Background', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-accordion-6 .wpsection-accordion-item.active .onoffswitch-label' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'style' => ['6'],
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_desc_style',
            [
                'label' => esc_html__('Description', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__('Description Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-accordion .wpsection-accordion-content, {{WRAPPER}} .wpsection-accordion .wpsection-accordion-content > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_desc',
                'selector' => '{{WRAPPER}} .wpsection-accordion .wpsection-accordion-content, {{WRAPPER}} .wpsection-accordion .wpsection-accordion-content > *',
            ]
        );

        $this->add_responsive_control(
            'desc_margin',
            [
                'label'      => esc_html__('Margin', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-accordion .wpsection-accordion-content, {{WRAPPER}} .wpsection-accordion .wpsection-accordion-content > *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }






    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $allowed_tags = wp_kses_allowed_html('post');

        /**
         * Widget Render: Accordion
         *
         * @package widgets/accordion/views/template-1.php
         * @copyright Pluginbazar 2020
         */

        $style           = wpsection()->get_settings_atts('style');
?>

        <?php

        echo '
<script>

"use strict";
    var widgetAccordion = function ($scope, $) {
        $(document).on("click", ".wpsection-accordion-item > .wpsection-accordion-title", function () {

            let thisTitle = $(this),
                thisItem = $(this).parent(),
                allItems = thisItem.parent(),
                thisContent = thisItem.find(".wpsection-accordion-content"),
                thisIcon = thisTitle.find(".toggle-icon");

            if (!thisItem.hasClass("active")) {
                allItems.find(".wpsection-accordion-item").removeClass("active").find(".wpsection-accordion-content").slideUp();
                allItems.find(".toggle-icon").removeClass("icon-minus");
                thisContent.slideToggle();
                thisItem.toggleClass("active");
                thisIcon.toggleClass("icon-minus icon-plus");
            }
        });

    };
    jQuery(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/wpsection-accordion.default", widgetAccordion);
    });


//put the code above the line 


</script>';

        ?>

        <?php if ('style1' === $settings['style']) : ?>
            <div class="wpsection-accordion wpsection-accordion-<?php echo esc_attr($settings['sec_class']); ?>">
                <?php foreach ($settings['accordions'] as $item) : ?>
                    <div class="wpsection-accordion-item">
                        <h3 class="wpsection-accordion-title">
                            <?php echo wp_kses($item['title'], $allowed_tags); ?>
                            <span class="toggle-icon icon-plus"></span>
                        </h3>
                        <div class="wpsection-accordion-content"><?php echo wp_kses_post($item['content']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ('style2' === $settings['style']) : ?>
            <div id="wpsection-accordion" class="wpsection-accordion wpsection-accordion-<?php echo esc_attr($style); ?>">
                <div class="wpsection-accordion-inner">
                    <div class="wpsection-accordion-wrap">
                        <?php foreach ($settings['accordions'] as $item) : ?>

                            <div class="wpsection-accordion-item">
                                <h3 class="wpsection-accordion-title">
                                    <?php echo wp_kses($item['title'], $allowed_tags); ?>
                                    <div class="toggle-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <g transform="translate(-1495 -355)">
                                                <path d="M10.858,24C16.574,24,25.148,8.842,22.29,3.789S2.284-1.263-.574,3.789,5.142,24,10.858,24Z" transform="translate(1496.142 355)" fill="#5f27cd" class="toggle-arrow-bg"></path>
                                                <g transform="translate(1512 364) rotate(90)">
                                                    <path d="M10.281,10a1.216,1.216,0,0,1-.845-.341,1.249,1.249,0,0,1-.044-1.752l2.743-2.916L9.4,2.1A1.249,1.249,0,0,1,9.434.344a1.217,1.217,0,0,1,1.733.039l3.548,3.749a1.25,1.25,0,0,1,0,1.711L11.17,9.615A1.219,1.219,0,0,1,10.281,10Z" transform="translate(-9.055 0)" fill="#fff" class="toggle-arrow"></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                </h3>
                                <div class="wpsection-accordion-content"><?php echo wp_kses_post($item['content']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ('style3' === $settings['style']) : ?>

            <div class="wpsection-accordion wpsection-accordion-<?php echo esc_attr($settings['sec_class']); ?>">
                <div class="wpsection-accordion-inner">
                    <div class="wpsection-accordion-wrap">
                        <?php foreach ($settings['accordions'] as $item) : ?>
                            <div class="wpsection-accordion-item">
                                <h3 class="wpsection-accordion-title">
                                    <?php echo wp_kses($item['title'], $allowed_tags); ?>

                                    <div class="onoffswitch">
                                        <label class="onoffswitch-label" for="myonoffswitch">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </h3>
                                <div class="wpsection-accordion-content"><?php echo wp_kses_post($item['content']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
<?php
    }
}


Plugin::instance()->widgets_manager->register(new \wpsection_wps_accordion_Widget());
