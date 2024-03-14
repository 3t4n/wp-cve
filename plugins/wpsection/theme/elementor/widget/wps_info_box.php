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




class wpsection_wps_info_box_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'wpsection_wps_info_box';
    }

    public function get_title()
    {
        return __('Info Box', 'wpsection');
    }

    public function get_icon()
    {
        return 'eicon-info-box';
    }

    public function get_keywords()
    {
        return ['wpsection', 'info_box'];
    }

    public function get_categories()
    {
        return ['wpsection_category'];
    }


    protected function register_controls()
    {
        $this->start_controls_section(
            'icon_section',
            [
                'label' => esc_html__('Icon', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'sec_class',
            [
                'label'       => __('Section Class', 'rashid'),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter Section Class', 'rashid'),
            ]
        );
        $this->add_control(
            'choose_media',
            [
                'label'   => esc_html__('Select Type', 'wpsection'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'style1',
                'options' => [
                    'style2'  => esc_html__('Icon', 'wpsection'),
                    'style1' => esc_html__('Image', 'wpsection'),
                ],
            ]
        );

        $this->add_control(
            'image',
            [
                'label'     => esc_html__('Choose Image', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::MEDIA,
                'default'   => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'choose_media' => 'style1',
                ],
            ]
        );

        $this->add_control(
            'icons',
            [
                'label'            => esc_html__('Choose Icon', 'wpsection'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
                'condition'        => [
                    'choose_media' => 'style2',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'title_section',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'info_count',
            [
                'label' => esc_html__('Info Count', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'style' => '3',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => __('Title', 'rashid'),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your title', 'rashid'),
                'default' => 'Jack Nicholson',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'description_section',
            [
                'label' => esc_html__('Description', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
            
        );
        $this->add_control(
            'text',
            [
                'label'       => __('Description', 'rashid'),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your Description', 'rashid'),
                'default' => 'Lorem ipsum dolor sit elit consectur sed eius mod tempor labore set aliquat enim minim veniam quis nostrud.',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'buttons_section',
            [
                'label' => esc_html__('Buttons', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label'       => esc_html__('Button Text', 'wpsection'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Get Started', 'wpsection'),
                'default' => 'View More',
            ]
        );

        $this->add_control(
            'btn_url',
            [
                'label'         => esc_html__('Button URL', 'wpsection'),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => esc_html__('https://your-link.com', 'wpsection'),
                'show_external' => true,
                'default'       => [
                    'url'         => '',
                    'is_external' => true,
                    'nofollow'    => true,
                ],
                'separator'     => 'after',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_infobox_style',
            [
                'label' => esc_html__('Global Style', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label'     => esc_html__('Primary Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
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
                'condition' => [
                    'style' => ['4', '5'],
                ],
            ]
        );

        $this->add_control(
            'is_featured',
            [
                'label' => esc_html__('Featured?', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wpsection'),
                'label_off' => esc_html__('No', 'wpsection'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'style' => ['4', '2'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__('Icon', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'img_width',
            [
                'label'      => esc_html__('Image Width', 'wpsection'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 300,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 42,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-info-box .icon-wrap img' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'choose_media' => 'choose_image',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => esc_html__('Icon Size', 'wpsection'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 170,
                        'step' => 1,
                    ]
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 42,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-info-box .icon-wrap > i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'choose_media' => 'choose_icon',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_bg_size',
            [
                'label'      => esc_html__('Icon Background Size', 'wpsection'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 300,
                        'step' => 1,
                    ]
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-info-box .icon-wrap > i' => 'width: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'choose_media' => 'choose_icon',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-info-box .icon-wrap > i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wpsection-info-box-5 .icon-wrap:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg',
            [
                'label'     => esc_html__('Background Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-info-box .icon-wrap > i' => 'background-color: {{VALUE}};box-shadow: 0 0 0 1px {{VALUE}};',
                ],
                'condition' => [
                    'style' => '2',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_common',
            [
                'label'     => esc_html__('Background Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-info-box .icon-wrap > i' => 'background-color: {{VALUE}};box-shadow: 0 0 0 1px {{VALUE}};',
                ],
                'condition' => [
                    'style' => ['1', '3'],
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
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-desc .wpsection-info-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_title',
                'selector' => '{{WRAPPER}} .wpsection-info-box .wpsection-info-desc .wpsection-info-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__('Margin', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-desc .wpsection-info-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-desc .wpsection-info-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-desc > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_desc',
                'selector' => '{{WRAPPER}} .wpsection-info-box .wpsection-info-desc > *',
            ]
        );

        $this->add_responsive_control(
            'desc_margin',
            [
                'label'      => esc_html__('Margin', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_buttons_style',
            [
                'label' => esc_html__('Buttons', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label'     => esc_html__('Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-btn'          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-btn svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label'     => esc_html__('Background', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-btn:hover'          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-btn:hover svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btn_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_btn',
                'selector' => '{{WRAPPER}} .wpsection-info-box .wpsection-info-btn',
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label'      => esc_html__('Padding', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_radius',
            [
                'label'      => esc_html__('Radius', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-info-box .wpsection-info-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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






        <div class="wpsection-info-box wpsection-info-box-<?php echo esc_attr($settings['sec_class']);?>">
                <div class="info-count"><?php echo $settings['info_count']; ?></div>
            <div class="icon-wrap">
                <?php if ('style1' === $settings['choose_media']) : ?>
                    <?php if (esc_url($settings['image']['id'])) : ?>
                        <img src="<?php echo wp_get_attachment_url($settings['image']['id']); ?>" />
                    <?php else : ?>
                        <div class="noimage"></div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ('style2' === $settings['choose_media']) : ?>
                    <i class="<?php echo esc_attr($settings['icons']); ?>"></i>
                <?php endif; ?>
            </div>

            <div class="wpsection-info-desc">
                <h3 class="wpsection-info-title"><?php echo $settings['title']; ?></h3>
                <div class="wpsection-info-desc"><?php echo $settings['text']; ?></div>

                <a href="<?php echo esc_url($settings['btn_url']['url']); ?>" class="wpsection-info-btn">
                    <?php echo $settings['btn_text']; ?>
                </a>
            </div>
            <div class="curve-shape">

            </div>
        </div>
<?php
    }
}


Plugin::instance()->widgets_manager->register(new \wpsection_wps_info_box_Widget());
