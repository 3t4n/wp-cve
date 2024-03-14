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




class wpsection_wps_social_profile_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'wpsection_wps_social_profile';
    }

    public function get_title()
    {
        return __('Social Profile', 'wpsection');
    }

    public function get_icon()
    {
        return 'eicon-social-icons';
    }

    public function get_keywords()
    {
        return ['wpsection', 'social_profile'];
    }

    public function get_categories()
    {
        return ['wpsection_category'];
    }


    protected function register_controls()
    {

        $this->start_controls_section(
            'social_content_section',
            [
                'label' => esc_html__('Social Items', 'wpsection'),
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
                'default' => '5',
                'options' => array(
                    '1' => esc_html__('Style 1', 'wpsection'),
                    '2' => esc_html__('Style 2', 'wpsection'),
                    '3' => esc_html__('Style 3', 'wpsection'),
                    '4' => esc_html__('Style 4', 'wpsection'),
                    '5' => esc_html__('Style 5', 'wpsection'),


                ),
            ]
        );

        $this->add_control(
            'repeat',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' =>
                [
                    ['block_title' => esc_html__('Add Feature', 'wpsection')],
                ],
                'fields' =>

                [
                    'choose_media' =>
                    [
                        'name' => 'choose_media',
                        'label'   => esc_html__('Select Icon', 'wpsection'),
                        'type'    => Controls_Manager::SELECT,
                        'default' => 'choose_icon',
                        'options' => [
                            'choose_icon'  => esc_html__('Icon', 'wpsection'),
                            'choose_image' => esc_html__('Image', 'wpsection'),
                        ],
                    ],
                    'image' =>
                    [
                        'name' => 'image',
                        'label' => __('Image', 'rashid'),
                        'type' => Controls_Manager::MEDIA,
                        'default' => ['url' => Utils::get_placeholder_image_src(),],
                        'condition' => [
                            'choose_media' => 'choose_image',
                        ],
                    ],

                    '_icons' =>
                    [
                        'name' => '_icons',
                        'label' => esc_html__('Icon', 'rashid'),
                        'type' => Controls_Manager::ICONS,
                        'default' => [
                            'value' => 'fas fa-facebook-f',
                            'library' => 'solid',
                        ],
                        'condition' => [
                            'choose_media' => 'choose_icon',
                        ],
                    ],

                    '_label' =>
                    [
                        'name' => '_label',
                        'label'       => __('Lable', 'wpsection'),
                        'type'        => Controls_Manager::TEXT,
                        'default' => 'Facebook',
                        'dynamic'     => [
                            'active' => true,
                        ],


                    ],
                    'social_url' =>
                    [
                        'name' => 'social_url',
                        'label' => __('Button Url', 'rashid'),
                        'type' => Controls_Manager::URL,
                        'placeholder' => __('https://your-link.com', 'rashid'),
                        'show_external' => true,
                        'default' => [
                            'url' => '',
                            'is_external' => true,
                            'nofollow' => true,
                        ],
                    ],
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_social_style',
            [
                'label' => esc_html__('Global Style', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            '_color',
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
                    ),
                ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-social-profile .wpsection-sp-label'  => 'color: {{VALUE}}',

                ],
            ],

        );
        $this->add_control(
            'primary',
            [
                'label'     => esc_html__('Primary Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
            ]
        );

        $this->add_control(
            'secondary',
            [
                'label'     => esc_html__('Secondary Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'condition'        => [
                    // 'style' => array('1', '3', '4', '5'),
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


        <?php


        ?>


        <?php if ('style1' === $settings['style']) : ?>

            <div id="wpsection-social-profile-" class="wpsection-social-profile wpsection-social-profile-<?php echo esc_attr($settings['sec_class']); ?>">
                <?php foreach ($settings['repeat'] as $item) : ?>
                    <a href="<?php echo esc_url($item['social_url']['url']); ?>" id="social-link">
                        <?php if ('choose_image' === $item['choose_media']) : ?>
                            <img src="<?php echo wp_get_attachment_url($item['image']['id']); ?>" alt="">
                        <?php endif; ?>
                        <?php if ('choose_icon' === $item['choose_media']) : ?>
                            <i class="<?php echo esc_attr($item['_icons']); ?>"></i>
                        <?php endif; ?>
                        <span class="wpsection-sp-label"><?php echo wp_kses($item['_label'], $allowed_tags); ?></span>
                    </a>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>
        <?php if ('style2' === $settings['style']) : ?>


            <div id="wpsection-social-profile-" class="wpsection-social-profile wpsection-social-profile-<?php echo esc_attr($settings['sec_class']); ?>">

                <div class="wpsection-social-profile-inner">

                    <?php foreach ($settings['repeat'] as $item) : ?>
                        <a href="<?php echo esc_url($item['social_url']['url']); ?>" id="social-link">
                            <?php if ('choose_image' === $item['choose_media']) : ?>
                                <img src="<?php echo wp_get_attachment_url($item['image']['id']); ?>" alt="">
                            <?php endif; ?>
                            <?php if ('choose_icon' === $item['choose_media']) : ?>
                                <i class="<?php echo esc_attr($item['_icons']); ?>"></i>
                            <?php endif; ?>
                            <span class="wpsection-sp-label"><?php echo wp_kses($item['_label'], $allowed_tags); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php endif; ?>

        <?php if ('style3' === $settings['style']) : ?>
            <div class="wpsection-social-profile-<?php echo esc_attr($settings['sec_class']); ?>">
                <?php foreach ($settings['repeat'] as $item) : ?>
                    <a href="<?php echo esc_url($item['social_url']['url']); ?>">
                        <?php if ('choose_image' === $item['choose_media']) : ?>
                            <img src="<?php echo wp_get_attachment_url($item['image']['id']); ?>" alt="">
                        <?php endif; ?>
                        <?php if ('choose_icon' === $item['choose_media']) : ?>
                            <i class="<?php echo esc_attr($item['_icons']); ?>"></i>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>


<?php
    }
}


Plugin::instance()->widgets_manager->register(new \wpsection_wps_social_profile_Widget());
