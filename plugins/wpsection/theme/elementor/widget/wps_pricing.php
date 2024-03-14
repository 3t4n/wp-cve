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




class wpsection_wps_pricing_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'wpsection_wps_pricing';
    }

    public function get_title()
    {
        return __('Pricing', 'wpsection');
    }

    public function get_icon()
    {
        return 'eicon-price-table';
    }

    public function get_keywords()
    {
        return ['wpsection', 'pricing'];
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
                'label' => esc_html__('Pricing', 'wpsection'),
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
                    'style4' => esc_html__('Choose Style 4', 'wpsection'),

                ),
            ]
        );
        $this->add_control(
            'sec_class',
            [
                'label'   => esc_html__('Choose Different Style', 'wpsection'),
                'label_block' => true,
                'type'    => Controls_Manager::SELECT,
                'default' => '4',
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
            'choose_media',
            [
                'label'     => esc_html__('Select Icon', 'wpsection'),
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
                'type'      => Controls_Manager::SELECT,
                'default'   => 'choose_icon',
                'options'   => [
                    'choose_icon'  => esc_html__('Icon', 'wpsection'),
                    'choose_image' => esc_html__('Image', 'wpsection'),
                ],
            ]
        );

        $this->add_control(
            'image',
            [
                'label'     => esc_html__('Choose Image', 'wpsection'),
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
                'type'      => \Elementor\Controls_Manager::MEDIA,
                'default'   => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'choose_media' => 'choose_image',
                    // 'style'        => array('1', '2', '3')
                ],
            ]
        );

        $this->add_control(
            'icons',
            [
                'label'            => esc_html__('Choose Icon', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'choose_media' => 'choose_icon',
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
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'fa4_icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
                'condition'        => [
                    'choose_media' => 'choose_icon',
                ],
            ]
        );

        $this->add_control(
            'highlighted_text',
            [
                'label'       => esc_html__('Highlighted Text', 'wpsection'),
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
                'placeholder' => esc_html__('Basic Plan', 'wpsection'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'High Quality Adjustable Executive Chair',
            ]
        );


        $this->add_control(
            'sale_price',
            [
                'label' => esc_html__('Sale Price', 'wpsection'),
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
                'type'  => Controls_Manager::NUMBER,
                'default' => '120',
            ]
        );

        $this->add_control(
            'regular_price',
            [
                'label' => esc_html__('Regular Price', 'wpsection'),
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
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style4',
                        ),
                    ),
                ),
                'type'  => Controls_Manager::NUMBER,
                'default' => '90',
            ]
        );

        $this->add_control(
            'currency',
            [
                'label' => esc_html__('Currency', 'wpsection'),
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
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style4',
                        ),
                    ),
                ),
                'type'  => Controls_Manager::TEXT,
                'default' => ' $',
            ]
        );

        $this->add_control(
            'short_desc',
            [
                'label' => esc_html__('Short Description', 'wpsection'),
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
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style4',
                        ),
                    ),
                ),
                'type'  => Controls_Manager::TEXTAREA,
                'default' => 'Lorem ipsum dolor sit elit consectur sed eius mod tempor labore set aliquat enim minim veniam quis nostrud.Lorem ipsum dolor sit elit consectur sed eius mod tempor labore set aliquat enim minim veniam quis nostrud.',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Feature', 'wpsection'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
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
                    'feature_text' =>
                    [
                        'name' => 'feature_text',
                        'label' => esc_html__('Title', 'wpsection'),
                        'type' => Controls_Manager::TEXTAREA,
                        'default' => esc_html__('Lorem ipsum dolor sit elit consectur sed eius mod tempor labore set aliquat enim minim veniam quis nostrud.', 'wpsection')
                    ],

                    'feature_in' =>
                    [
                        'name'         => 'feature_in',
                        'label'        => esc_html__('Feature in', 'wpsection'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__('Yes', 'wpsection'),
                        'label_off'    => esc_html__('No', 'wpsection'),
                        'return_value' => 'yes',
                        'default'      => 'yes',
                    ],
                ]
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Button', 'wpsection'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label'       => esc_html__('Button Text', 'wpsection'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Buy Now',
            ]
        );

        $this->add_control(
            'btn_url',
            [
                'label'         => esc_html__('Button URL', 'wpsection'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => esc_html__('https://your-link.com', 'wpsection'),
                'show_external' => true,
                'default'       => [
                    'url'         => '',
                    'is_external' => true,
                    'nofollow'    => true,
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_testimonial_style',
            [
                'label' => esc_html__('Global Style', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );


        // $this->add_control(
        //     '_featured',
        //     [
        //         'label'        => esc_html__('Is Featured?', 'wpsection'),
        //         'type'         => Controls_Manager::SWITCHER,
        //         'label_on'     => esc_html__('Yes', 'wpsection'),
        //         'label_off'    => esc_html__('No', 'wpsection'),
        //         'return_value' => 'yes',
        //         'default'      => 'no',
        //     ]
        // );

        $this->add_control(
            'primary_color',
            [
                'label'     => esc_html__('Primary Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-pricing-1.is-featured' => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}} .wpsection-pricing-4 .pricing-footer > a' => 'border-color: {{VALUE}};color: {{VALUE}};',
                    '{{WRAPPER}} .wpsection-pricing-1 .pricing-footer > a, 
					{{WRAPPER}} .wpsection-pricing-2 .pricing-footer > a, 
					{{WRAPPER}} .wpsection-pricing-3.is-featured:before, 
					{{WRAPPER}} .wpsection-pricing-4 .pricing-head, 
					{{WRAPPER}} .wpsection-pricing-5:hover .pricing-head, 
					{{WRAPPER}} .wpsection-pricing-5.is-featured .pricing-head, 
					{{WRAPPER}} .wpsection-pricing-5 .pricing-footer > a, 
					{{WRAPPER}} .wpsection-pricing-4 .pricing-footer > a:hover, 
					{{WRAPPER}} .wpsection-pricing-4 .pricing-footer > a:focus, 
					{{WRAPPER}} .wpsection-pricing-3 .pricing-footer > a:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'style' => ['style1', 'style2', 'style3', 'style4',],
                ],
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label'     => esc_html__('Secondary Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .wpsection-pricing-1 .pricing-footer > a:hover, 
					{{WRAPPER}} .wpsection-pricing-2 .pricing-footer > a:hover, 
					{{WRAPPER}} .wpsection-pricing-3 .pricing-footer > a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wpsection-pricing-4 .pricing-footer > a:hover, 
					{{WRAPPER}} .wpsection-pricing-4 .pricing-footer > a:focus' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'style' => ['style1', 'style2', 'style3', 'style4',],
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'      => 'background',
                'label'     => esc_html__('Gradient Color', 'wpsection'),
                'types'     => ['gradient'],
                'selector'  => '{{WRAPPER}} .wpsection-pricing-3:after, {{WRAPPER}} .wpsection-pricing-3 .pricing-duration,
				                {{WRAPPER}} .wpsection-pricing-3 .pricing-duration:before,
                                {{WRAPPER}} .wpsection-pricing-6,
                                {{WRAPPER}} .wpsection-pricing-5,
                                {{WRAPPER}} .wpsection-pricing-4,
                                {{WRAPPER}} .wpsection-pricing-2,
                                {{WRAPPER}} .wpsection-pricing-1',

                'condition' => [
                    'style' => ['style3'],
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
            <div class="wpsection-pricing wpsection-pricing-<?php echo esc_attr($settings['sec_class']); ?>">
                <div class="pricing-head">
                    <div class="pricing-icon">
                        <?php if ('choose_image' === $settings['choose_media']) : ?>
                            <?php if (esc_url($settings['image']['id'])) : ?>
                                <img src="<?php echo wp_get_attachment_url($settings['image']['id']); ?>" />
                            <?php else : ?>
                                <div class="noimage"></div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ('choose_icon' === $settings['choose_media']) : ?>
                            <i class="<?php echo esc_attr($settings['icons']); ?>"></i>
                        <?php endif; ?>
                    </div>

                    <h4 class="pricing-duration"><?php echo $settings['highlighted_text']; ?></h4>

                    <div class="price">
                        <span class="sale-price">
                            <span class="currency"><?php echo $settings['currency']; ?></span><?php echo esc_attr($settings['sale_price'], true); ?>
                        </span>

                        <span class="regular-price">
                            <span class="currency"><?php echo $settings['currency']; ?></span><?php echo esc_attr($settings['regular_price'], true); ?>
                        </span>
                    </div>
                    <h3 class="pricing-title"><?php echo $settings['short_desc']; ?></h3>
                </div>

                <div class="pricing-content">
                    <ul>
                        <?php foreach ($settings['repeat'] as $item) : ?>
                            <li class="<?php echo esc_attr('feature_in'); ?>"><span class="check-close-icon"></span><?php echo $item['feature_text']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="pricing-footer">
                    <a href="<?php echo esc_url($settings['btn_url']['url']); ?>"><?php echo $settings['btn_text']; ?></a>
                </div>
            </div>
        <?php endif; ?>

        <?php if ('style2' === $settings['style']) : ?>
            <div class="wpsection-pricing wpsection-pricing-<?php echo esc_attr($settings['sec_class']); ?>">
                <div class="pricing-head">
                    <div class="pricing-head">
                        <div class="pricing-icon">
                            <?php if ('choose_image' === $settings['choose_media']) : ?>
                                <?php if (esc_url($settings['image']['id'])) : ?>
                                    <img src="<?php echo wp_get_attachment_url($settings['image']['id']); ?>" />
                                <?php else : ?>
                                    <div class="noimage"></div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if ('choose_icon' === $settings['choose_media']) : ?>
                                <i class="<?php echo esc_attr($settings['icons']); ?>"></i>
                            <?php endif; ?>
                        </div>


                        <h4 class="pricing-duration"><?php echo $settings['highlighted_text']; ?></h4>

                        <div class="price">
                            <span class="sale-price">
                                <span class="currency"><?php echo $settings['currency']; ?></span><?php echo esc_attr($settings['sale_price'], true); ?>
                            </span>

                            <span class="regular-price">
                                <span class="currency"><?php echo $settings['currency']; ?></span><?php echo esc_attr($settings['regular_price'], true); ?>
                            </span>
                        </div>
                        <h3 class="pricing-title"><?php echo $settings['short_desc']; ?></h3>
                    </div>
                </div>

                <div class="pricing-content">
                    <ul>
                        <?php foreach ($settings['repeat'] as $item) : ?>
                            <li class="<?php echo esc_attr('feature_in'); ?>"><span class="check-close-icon"></span><?php echo $item['feature_text']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="pricing-footer">
                    <a href="<?php echo esc_url($settings['btn_url']['url']); ?>"><?php echo $settings['btn_text']; ?></a>
                </div>
                <div class="wpsection-bubbles">
                </div>
            </div>

        <?php endif; ?>
        <?php if ('style3' === $settings['style']) : ?>
            <div class="wpsection-pricing wpsection-pricing-<?php echo esc_attr($settings['sec_class']); ?>">
                <div class="pricing-head">
                    <div class="price">
                        <span class="sale-price">
                            <span class="currency"><?php echo $settings['currency']; ?></span><?php echo esc_attr($settings['sale_price'], true); ?>
                        </span>
                        <span class="regular-price">
                            <span class="currency"><?php echo $settings['currency']; ?></span><?php echo esc_attr($settings['regular_price'], true); ?>
                        </span>
                    </div>
                    <h3 class="pricing-title"><?php echo $settings['short_desc']; ?></h3>

                    <h4 class="pricing-duration"><?php echo $settings['highlighted_text']; ?></h4>
                </div>

                <div class="pricing-content">
                    <div class="pricing-icon">
                        <?php if ('choose_image' === $settings['choose_media']) : ?>
                            <?php if (esc_url($settings['image']['id'])) : ?>
                                <img src="<?php echo wp_get_attachment_url($settings['image']['id']); ?>" />
                            <?php else : ?>
                                <div class="noimage"></div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ('choose_icon' === $settings['choose_media']) : ?>
                            <i class="<?php echo esc_attr($settings['icons']); ?>"></i>
                        <?php endif; ?>
                    </div>

                    <ul>
                        <?php foreach ($settings['repeat'] as $item) : ?>
                            <li class="<?php echo esc_attr('feature_in'); ?>"><span class="check-close-icon"></span><?php echo $item['feature_text']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="pricing-footer">
                    <a href="<?php echo esc_url($settings['btn_url']['url']); ?>"><?php echo $settings['btn_text']; ?></a>
                </div>
            </div>

        <?php endif; ?>
        <?php if ('style4' === $settings['style']) : ?>
            <div class="wpsection-pricing wpsection-pricing-<?php echo esc_attr($settings['sec_class']); ?>">
                <div class="pricing-head">

                    <h4 class="pricing-duration"><?php echo $settings['highlighted_text']; ?></h4>

                    <div class="price">
                        <span class="sale-price">
                            <span class="currency"><?php echo $settings['currency']; ?></span><?php echo esc_attr($settings['sale_price'], true); ?>
                        </span>
                        <span class="regular-price">
                            <span class="currency"><?php echo $settings['currency']; ?></span><?php echo esc_attr($settings['regular_price'], true); ?>
                        </span>
                    </div>
                    <h3 class="pricing-title"><?php echo $settings['short_desc']; ?></h3>
                </div>

                <div class="pricing-content">
                    <ul>
                        <?php foreach ($settings['repeat'] as $item) : ?>
                            <li class="<?php echo esc_attr('feature_in'); ?>"><span class="check-close-icon"></span><?php echo $item['feature_text']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="pricing-footer">
                    <a href="<?php echo esc_url($settings['btn_url']['url']); ?>"><?php echo $settings['btn_text']; ?></a>
                </div>
            </div>

        <?php endif; ?>
<?php
    }
}


Plugin::instance()->widgets_manager->register(new \wpsection_wps_pricing_Widget());
