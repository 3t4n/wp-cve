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




class wpsection_wps_testimonial_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'wpsection_wps_testimonial';
    }

    public function get_title()
    {
        return __('Testimonial', 'wpsection');
    }

    public function get_icon()
    {
        return 'eicon-testimonial';
    }

    public function get_keywords()
    {
        return ['wpsection', 'testimonial'];
    }

    public function get_categories()
    {
        return ['wpsection_category'];
    }


    protected function register_controls()
    {


        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Testimonial', 'wpsection'),
                'tab'   => Controls_Manager::TAB_CONTENT,
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
                'default' => '1',
                'options' => array(
                    '1' => esc_html__('Style 1', 'wpsection'),
                    '2' => esc_html__('Style 2', 'wpsection'),
                    '3' => esc_html__('Style 3', 'wpsection'),
                    '4' => esc_html__('Style 4', 'wpsection'),
                    '5' => esc_html__('Style 5', 'wpsection'),
                    '6' => esc_html__('Style 6', 'wpsection'),


                ),
            ]
        );
        $this->add_control(
            'image',
            [
                'label' => __('Image', 'rashid'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style3',
                        ),
                    ),
                ),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => Utils::get_placeholder_image_src(),],
            ]
        );




        $this->add_control(
            'subtitle',
            [
                'label'       => __('Name', 'rashid'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style3',
                        ),
                    ),
                ),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your Name', 'rashid'),
                'default' => 'Esther Howard',
            ]
        );

        $this->add_control(
            'text',
            [
                'label'       => __('Designation', 'rashid'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style3',
                        ),
                    ),
                ),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your Designation', 'rashid'),
                'default' => 'Manager',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Review', 'wpsection'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'review_text',
            [
                'label'       => __('Review Text', 'rashid'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style3',
                        ),
                    ),
                ),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('90% Recommended', 'wpsection'),
                'default' => 'Lorem ipsum dolor sit elit consectur sed eius mod tempor labore set aliquat enim minim veniam quis nostrud.',
            ]
        );
        $this->add_control(
            'rating',
            [
                'label'   => esc_html__('Rating', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style3',
                        ),
                    ),
                ),
                'type'    => Controls_Manager::SELECT,
                'default' => '0',
                'options' => [
                    '0'   => esc_html__('No Rating', 'wpsection'),
                    '1'   => esc_html__('1', 'wpsection'),
                    '1.5' => esc_html__('1.5', 'wpsection'),
                    '2'   => esc_html__('2', 'wpsection'),
                    '2.5' => esc_html__('2.5', 'wpsection'),
                    '3'   => esc_html__('3', 'wpsection'),
                    '3.5' => esc_html__('3.5', 'wpsection'),
                    '4'   => esc_html__('4', 'wpsection'),
                    '4.5' => esc_html__('4.5', 'wpsection'),
                    '5'   => esc_html__('5', 'wpsection'),
                ],
                'selectors' => [
                    '{{WRAPPER}}  .wpsection-testi-rating i' => ' <i class="far fa-star"></i>',
                ],
            ]
        );
        $this->add_control(
            'rating_color',
            [
                'label'     => esc_html__('Rating Color', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style3',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .wpsection-testi-rating i' => 'color: {{VALUE}};',
                ],
            ]
        );
        // $this->add_control(
        //     'repeat',
        //     [
        //         'type' => Controls_Manager::REPEATER,
        //         'seperator' => 'before',
        //         'default' =>
        //         [
        //             ['block_title' => esc_html__('Projects Completed', 'modrox')],
        //         ],
        //         'fields' =>

        //         [
        //             'block_icons' =>
        //             [
        //                 'name' => 'block_icons',
        //                 'label' => esc_html__('Enter The icons', 'rashid'),
        //                 'type' => Controls_Manager::ICONS,
        //             ],

        //         ],
        //     ]
        // );


        $this->end_controls_section();



        $this->start_controls_section(
            'section_testimonial_style',
            [
                'label' => esc_html__('Global Style', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .wpsection-testimonial-1 .wpsection-testi-desc',
            ]
        );
        $this->add_control(
            'global_primary',
            [
                'label'     => esc_html__('Primary Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-testimonial-4:after' => 'border-color: {{VALUE}};',
                    'body:not(.rtl) {{WRAPPER}} .wpsection-testimonial-5 .wpsection-testi-desc' => 'border-left-color: {{VALUE}};',
                    '.rtl {{WRAPPER}} .wpsection-testimonial-5 .wpsection-testi-desc' => 'border-right-color: {{VALUE}};',
                ],
                'condition' => [
                    'style' => ['4', '5'],
                ],
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label'     => esc_html__('Primary Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'style' => ['3'],
                ],
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label'     => esc_html__('Secondary Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'style' => ['3'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_name_style',
            [
                'label' => esc_html__('Name', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label'     => esc_html__('Color', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style3',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-testimonial .wpsection-testi-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_name',
                'selector' => '{{WRAPPER}} .wpsection-testimonial .wpsection-testi-name',
            ]
        );

        $this->add_responsive_control(
            'name_margin',
            [
                'label'      => esc_html__('Margin', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style3',
                        ),
                    ),
                ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-testimonial .wpsection-testi-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_designation_style',
            [
                'label' => esc_html__('Designation', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'designation_color',
            [
                'label'     => esc_html__('Color', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style3',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-testimonial .wpsection-testi-designation' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_designation',
                'selector' => '{{WRAPPER}} .wpsection-testimonial .wpsection-testi-designation',
            ]
        );

        $this->add_responsive_control(
            'designation_margin',
            [
                'label'      => esc_html__('Margin', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style3',
                        ),
                    ),
                ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-testimonial .wpsection-testi-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_review_text_style',
            [
                'label' => esc_html__('Review Text', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__('Color', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style3',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-testimonial .wpsection-review-text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wpsection-testimonial .wpsection-testi-desc p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_desc',
                'selector' =>
                '{{WRAPPER}} .wpsection-testimonial .wpsection-review-text',
                '{{WRAPPER}} .wpsection-testimonial .wpsection-testi-desc p'
            ]
        );

        $this->add_responsive_control(
            'desc_margin',
            [
                'label'      => esc_html__('Margin', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style3',
                        ),
                    ),
                ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-testimonial .wpsection-testi-desc p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $allowed_tags = wp_kses_allowed_html('post');
?>


        <?php if ('style1' === $settings['style']) : ?>
            <div class="wpsection-testimonial wpsection-testimonial-<?php echo esc_attr($settings['sec_class']); ?>">
                <div class="wpsection-testi-desc">
                    <div class="wpsection-review-text"><?php echo $settings['review_text']; ?></div>
                    <div class="wpsection-testi-rating">
                        <?php if ('rat1' === $settings['rating']) : ?>
                            <i class="far fa-star"></i>
                        <?php endif; ?>
                        <?php if ('rat2' === $settings['rating']) : ?>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        <?php endif; ?>
                        <?php if ('rat3' === $settings['rating']) : ?>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        <?php endif; ?>
                        <?php if ('rat4' === $settings['rating']) : ?>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        <?php endif; ?>
                        <?php if ('rat5' === $settings['rating']) : ?>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="wpsection-testi-footer">
                    <div class="wpsection-testi-img">
                        <?php if (esc_url($settings['image']['id'])) : ?>
                            <img src="<?php echo wp_get_attachment_url($settings['image']['id']); ?>" />
                        <?php else : ?>
                            <div class="noimage"></div>
                        <?php endif; ?>
                    </div>
                    <div class="wpsection-testi-info">
                        <h3 class="wpsection-testi-name"><?php echo $settings['subtitle']; ?></h3>
                        <span class="wpsection-testi-designation"><?php echo $settings['text']; ?></span>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <?php if ('style2' === $settings['style']) : ?>

            <div class="wpsection-testimonial wpsection-testimonial-<?php echo esc_attr($settings['sec_class']); ?>">
                <div class="wpsection-testi-img">
                    <?php if (esc_url($settings['image']['id'])) : ?>
                        <img src="<?php echo wp_get_attachment_url($settings['image']['id']); ?>" />
                    <?php else : ?>
                        <div class="noimage"></div>
                    <?php endif; ?>
                </div>
                <div class="wpsection-testi-footer">
                    <div class="wpsection-testi-info">
                        <h3 class="wpsection-testi-name"><?php echo $settings['subtitle']; ?></h3>
                        <span class="wpsection-testi-designation"><?php echo $settings['text']; ?></span>
                    </div>

                    <div class="wpsection-testi-desc">
                        <div class="wpsection-review-text"><?php echo $settings['review_text']; ?></div>

                        <div class="wpsection-testi-rating">
                            <?php if ('rat1' === $settings['rating']) : ?>
                                <i class="fa fa-star"></i>
                            <?php endif; ?>
                            <?php if ('rat2' === $settings['rating']) : ?>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            <?php endif; ?>
                            <?php if ('rat3' === $settings['rating']) : ?>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            <?php endif; ?>
                            <?php if ('rat4' === $settings['rating']) : ?>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            <?php endif; ?>
                            <?php if ('rat5' === $settings['rating']) : ?>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>


        <?php endif; ?>

        <?php if ('style3' === $settings['style']) : ?>
            <div class="wpsection-testimonial wpsection-testimonial-<?php echo esc_attr($settings['sec_class']); ?>">

                <div class="wpsection-testi-img">
                    <?php if (esc_url($settings['image']['id'])) : ?>
                        <img src="<?php echo wp_get_attachment_url($settings['image']['id']); ?>" />
                    <?php else : ?>
                        <div class="noimage"></div>
                    <?php endif; ?>
                </div>
                <div class="wpsection-testi-desc">
                    <div class="wpsection-review-text">
                        <?php echo $settings['review_text']; ?>
                    </div>
                </div>
                <i class="fa fa-quote-right wpsection-quote"></i>


                <div class="wpsection-testi-info">

                    <h3 class="wpsection-testi-name"><?php echo $settings['subtitle']; ?></h3>
                    <span class="wpsection-testi-designation"><?php echo $settings['text']; ?></span>

                    <div class="wpsection-testi-rating">
                        <?php if ('rat1' === $settings['rating']) : ?>
                            <i class="far fa-star"></i>
                        <?php endif; ?>
                        <?php if ('rat2' === $settings['rating']) : ?>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        <?php endif; ?>
                        <?php if ('rat3' === $settings['rating']) : ?>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        <?php endif; ?>
                        <?php if ('rat4' === $settings['rating']) : ?>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        <?php endif; ?>
                        <?php if ('rat5' === $settings['rating']) : ?>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        <?php endif; ?>

                    </div>
                </div>

            <?php endif; ?>


    <?php
    }
}


Plugin::instance()->widgets_manager->register(new \wpsection_wps_testimonial_Widget());
