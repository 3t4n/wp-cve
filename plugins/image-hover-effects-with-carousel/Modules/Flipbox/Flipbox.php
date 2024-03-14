<?php

namespace OXIIMAEADDONS\Modules\Flipbox;

if (!defined('ABSPATH')) {

    exit;
}

use Elementor\Controls_Manager as Controls_Manager;
use Elementor\Group_Control_Background as Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size as Group_Control_Image_Size;
use Elementor\Group_Control_Typography as Group_Control_Typography;
use Elementor\Icons_Manager as Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils as Utils;
use Elementor\Widget_Base as Widget_Base;

/**
 * Description of Flipbox
 *
 * @author biplo
 */
class Flipbox extends Widget_Base {

    public function get_name() {

        return 'oxi_i_addons_flipbox';
    }

    public function get_title() {

        return esc_html__('Flipbox Effects', 'oxi-hover-effects-addons');
    }

    public function get_icon() {
        return ' eicon-image';
    }

    public function get_categories() {

        return ['oxi-h-effects-addons'];
    }

    public function get_keywords() {
        return [
                'image',
                'image hover effects',
                'hover effect',
                'effects',
                'image hover',
                'flip',
                'box',
                'hover box'
        ];
    }

    public function get_style_depends() {
        wp_register_style('oxi-i-addons-f', OXIIMAEADDONS_URL . 'Modules/Flipbox/css/index.css',);
        return [
                'oxi-i-addons-f',
        ];
    }

    public function get_custom_help_url() {
        return 'https://wordpress.org/support/plugin/image-hover-effects-with-carousel/';
    }

    protected function register_controls() {
        $this->init_settings_content_controls();
        $this->init_frontend_content_controls();
        $this->init_backend_content_controls();
        $this->init_content_url_controls();
        $this->init_content_promotion_controls();
        $this->init_style_general_controls();
        $this->init_style_title_controls();
        $this->init_style_desc_controls();
        $this->init_style_icon_controls();
        $this->init_style_image_controls();
        $this->init_style_divider_controls();
        $this->init_style_button_controls();
    }

    public function init_settings_content_controls() {
        $this->start_controls_section(
                'oxi_addons_f_settings',
                [
                        'label' => esc_html__('Settings', 'oxi-hover-effects-addons'),
                ],
        );
        $this->add_control(
                'oxi_addons_flip_type',
                [
                        'label'   => __('Flipbox Type', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::SELECT,
                        'options' => [
                                'oxi-f-top-to-bottom' => __('Top to Bottom', 'oxi-hover-effects-addons'),
                                'oxi-f-bottom-to-top' => __('Bottom to Top', 'oxi-hover-effects-addons'),
                                'oxi-f-left-to-right' => __('Left to Right', 'oxi-hover-effects-addons'),
                                'oxi-f-right-to-left' => __('Right to Left', 'oxi-hover-effects-addons'),
                                'oxi-f-zoom-in'       => __('Zoom In', 'oxi-hover-effects-addons'),
                                'oxi-f-zoom-out'      => __('Zoom Out', 'oxi-hover-effects-addons'),
                                'oxi-f-fade-in'       => __('Fade In', 'oxi-hover-effects-addons'),
                        ],
                        'default' => 'oxi-f-left-to-right',
                ],
        );
        $this->add_control(
                'oxi_addons_flip_timing_type',
                [
                        'label'     => __('Timing Type', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''                     => esc_html__('Normal Animation', 'oxi-hover-effects-addons'),
                                'easing_easeInOutExpo' => esc_html__('EaseOutBack', 'oxi-hover-effects-addons'),
                                'easing_easeInOutCirc' => esc_html__('EaseInOutExpo', 'oxi-hover-effects-addons'),
                                'easing_easeOutBack'   => esc_html__('EaseInOutCirc', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_flip_type' => [
                                        'oxi-f-top-to-bottom',
                                        'oxi-f-bottom-to-top',
                                        'oxi-f-left-to-right',
                                        'oxi-f-right-to-left',
                                ]
                        ],
                        'default'   => '',
                ],
        );
        $this->add_control(
                'oxi_addons_flip_type_3d',
                [
                        'label'        => __('3D Effects', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __('On', 'oxi-hover-effects-addons'),
                        'label_off'    => __('Off', 'oxi-hover-effects-addons'),
                        'return_value' => 'oxi-addons-flip-3d',
                        'default'      => '',
                        'prefix_class' => '',
                        'condition'    => [
                                'oxi_addons_flip_type' => [
                                        'oxi-f-top-to-bottom',
                                        'oxi-f-bottom-to-top',
                                        'oxi-f-left-to-right',
                                        'oxi-f-right-to-left',
                                ]
                        ],
                ],
        );
        $this->add_responsive_control(
                'oxi_addons_flip_width',
                [
                        'label'      => __('Width', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px', 'em', '%'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 1000,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-style' => 'width: {{SIZE}}{{UNIT}}; margin: 0 auto;',
                        ],
                ],
        );
        $this->add_responsive_control(
                'oxi_addons_flip_height',
                [
                        'label'      => esc_html__('Height', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px', '%'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'step' => 1,
                                        'max'  => 1000,
                                ],
                                '%'  => [
                                        'min'  => 0,
                                        'step' => 3,
                                        'max'  => 100,
                                ],
                        ],
                        'default'    => [
                                'unit' => 'px',
                                'size' => '',
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-style' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->end_controls_section();
    }

    protected function init_elementor_template_list() {

        $post_info = get_posts([
                'post_type' => 'elementor_library',
        ]);
        return wp_list_pluck($post_info, 'post_title', 'ID');
    }

    public function init_frontend_content_controls() {
        $this->start_controls_section(
                'oxi_addons_f_content',
                [
                        'label' => esc_html__('Frontend Settings', 'oxi-hover-effects-addons'),
                ],
        );
        $this->add_control(
                'oxi_addons_f_type',
                [
                        'label'   => __('Content Type', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::SELECT,
                        'options' => [
                                'content'  => __('Content', 'oxi-hover-effects-addons'),
                                'template' => __('Saved Templates', 'oxi-hover-effects-addons'),
                        ],
                        'default' => 'content',
                ],
        );

        $this->add_control(
                'oxi_addons_f_templates',
                [
                        'label'       => __('Choose Template', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::SELECT2,
                        'label_block' => true,
                        'default'     => '',
                        'options'     => $this->init_elementor_template_list(),
                        'condition'   => [
                                'oxi_addons_f_type' => 'template',
                        ],
                ],
        );

        $repeater = new Repeater();
        $repeater->add_control(
                'oxi_addons_f_content_type',
                [
                        'label'   => esc_html__('Content Type', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::SELECT,
                        'options' => [
                                'title'       => esc_html__('Title', 'oxi-hover-effects-addons'),
                                'description' => esc_html__('Description', 'oxi-hover-effects-addons'),
                                'divider'     => esc_html__('Divider', 'oxi-hover-effects-addons'),
                                'icon'        => esc_html__('Icon', 'oxi-hover-effects-addons'),
                                'image'       => esc_html__('Image', 'oxi-hover-effects-addons'),
                        ],
                        'default' => 'title',
                ],
        );
        $repeater->add_control(
                'oxi_addons_f_width',
                [
                        'label'        => esc_html__('Width', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __('Block', 'oxi-hover-effects-addons'),
                        'label_off'    => __('Inline', 'oxi-hover-effects-addons'),
                        'default'      => 'full-width',
                        'return_value' => 'full-width',
                        'condition'    => [
                                'oxi_addons_f_content_type' => [
                                        'title',
                                        'icon',
                                        'image',
                                        'divider'
                                ],
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_f_position',
                [
                        'label'        => esc_html__('Position', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __('Bottom', 'oxi-hover-effects-addons'),
                        'label_off'    => __('Auto', 'oxi-hover-effects-addons'),
                        'default'      => '',
                        'return_value' => 'bottom',
                        'condition'    => [
                                'oxi_addons_f_width' => '',
                        ],
                ],
        );

        $repeater->add_control(
                'oxi_addons_f_title',
                [
                        'label'       => esc_html__('Title', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => esc_html__('Flip Title', 'oxi-hover-effects-addons'),
                        'placeholder' => esc_html__('Type your title here', 'oxi-hover-effects-addons'),
                        'separator'   => 'before',
                        'label_block' => true,
                        'dynamic'     => ['active' => true],
                        'condition'   => [
                                'oxi_addons_f_content_type' => 'title',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_f_title_tag',
                [
                        'label'     => esc_html__('Title Tag', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'h1'   => esc_html__('H1', 'oxi-hover-effects-addons'),
                                'h2'   => esc_html__('H2', 'oxi-hover-effects-addons'),
                                'h3'   => esc_html__('H3', 'oxi-hover-effects-addons'),
                                'h4'   => esc_html__('H4', 'oxi-hover-effects-addons'),
                                'h5'   => esc_html__('H5', 'oxi-hover-effects-addons'),
                                'h6'   => esc_html__('H6', 'oxi-hover-effects-addons'),
                                'p'    => esc_html__('Paragraph', 'oxi-hover-effects-addons'),
                                'span' => esc_html__('Span', 'oxi-hover-effects-addons'),
                                'div'  => esc_html__('Div', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => 'h3',
                        'condition' => [
                                'oxi_addons_f_content_type' => 'title',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_f_description',
                [
                        'label'       => esc_html__('Description', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::TEXTAREA,
                        'rows'        => 5,
                        'default'     => esc_html__('Description', 'oxi-hover-effects-addons'),
                        'placeholder' => esc_html__('Type your description here', 'oxi-hover-effects-addons'),
                        'show_label'  => true,
                        'dynamic'     => ['active' => true],
                        'condition'   => [
                                'oxi_addons_f_content_type' => 'description',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_f_description_tag',
                [
                        'label'     => esc_html__('Description Tag', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'p'    => esc_html__('Paragraph', 'oxi-hover-effects-addons'),
                                'span' => esc_html__('Span', 'oxi-hover-effects-addons'),
                                'div'  => esc_html__('Div', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => 'p',
                        'condition' => [
                                'oxi_addons_f_content_type' => 'description',
                        ],
                ],
        );

        $repeater->add_control(
                'oxi_addons_f_icon',
                [
                        'label'       => esc_html__('Icon', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::ICONS,
                        'label_block' => true,
                        'separator'   => 'before',
                        'condition'   => [
                                'oxi_addons_f_content_type' => 'icon',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_f_image',
                [
                        'label'     => esc_html__('Choose Image', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::MEDIA,
                        'default'   => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'dynamic'   => ['active' => true],
                        'condition' => [
                                'oxi_addons_f_content_type' => 'image',
                        ],
                ],
        );
        $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                        'name'      => 'oxi_addons_f_image_thumbnail',
                        'exclude'   => ['custom'],
                        'include'   => [],
                        'default'   => 'full',
                        'condition' => [
                                'oxi_addons_f_content_type' => 'image',
                        ],
                ],
        );

        $repeater->add_responsive_control(
                'oxi_addons_f_image_opacity',
                [
                        'label'      => __('Opacity', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 1,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 1,
                                        'step' => 0.01,
                                ],
                        ],
                        'condition'  => [
                                'oxi_addons_f_content_type' => 'image',
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image' => 'opacity: {{SIZE}};',
                        ],
                ],
        );
        $this->add_control(
                'oxi_addons_frontend_content',
                [
                        'type'        => Controls_Manager::REPEATER,
                        'seperator'   => 'before',
                        'default'     => [
                                ['oxi_addons_f_content_type' => esc_html__('title', 'oxi-hover-effects-addons')],
                                ['oxi_addons_f_content_type' => esc_html__('description', 'oxi-hover-effects-addons')],
                        ],
                        'fields'      => $repeater->get_controls(),
                        'title_field' => '{{oxi_addons_f_content_type}}',
                        'condition'   => [
                                'oxi_addons_f_type' => 'content',
                        ],
                ],
        );
        $this->end_controls_section();
    }

    public function init_backend_content_controls() {
        $this->start_controls_section(
                'oxi_addons_b_content',
                [
                        'label' => esc_html__('Backend Content', 'oxi-hover-effects-addons'),
                ],
        );
        $this->add_control(
                'oxi_addons_b_type',
                [
                        'label'   => __('Content Type', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::SELECT,
                        'options' => [
                                'content'  => __('Content', 'oxi-hover-effects-addons'),
                                'template' => __('Saved Templates', 'oxi-hover-effects-addons'),
                        ],
                        'default' => 'content',
                ],
        );

        $this->add_control(
                'oxi_addons_b_templates',
                [
                        'label'       => __('Choose Template', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::SELECT2,
                        'label_block' => true,
                        'default'     => '',
                        'options'     => $this->init_elementor_template_list(),
                        'condition'   => [
                                'oxi_addons_b_type' => 'template',
                        ],
                ],
        );

        $repeater = new Repeater();
        $repeater->add_control(
                'oxi_addons_b_content_type',
                [
                        'label'   => esc_html__('Content Type', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::SELECT,
                        'options' => [
                                'title'       => esc_html__('Title', 'oxi-hover-effects-addons'),
                                'description' => esc_html__('Description', 'oxi-hover-effects-addons'),
                                'divider'     => esc_html__('Divider', 'oxi-hover-effects-addons'),
                                'icon'        => esc_html__('Icon', 'oxi-hover-effects-addons'),
                                'image'       => esc_html__('Image', 'oxi-hover-effects-addons'),
                                'button'      => esc_html__('Button', 'oxi-hover-effects-addons'),
                        ],
                        'default' => 'title',
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_width',
                [
                        'label'        => esc_html__('Width', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __('Block', 'oxi-hover-effects-addons'),
                        'label_off'    => __('Inline', 'oxi-hover-effects-addons'),
                        'default'      => 'full-width',
                        'return_value' => 'full-width',
                        'condition'    => [
                                'oxi_addons_b_content_type' => [
                                        'title',
                                        'icon',
                                        'image',
                                        'button',
                                        'divider'
                                ],
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_position',
                [
                        'label'        => esc_html__('Position', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __('Bottom', 'oxi-hover-effects-addons'),
                        'label_off'    => __('Auto', 'oxi-hover-effects-addons'),
                        'default'      => '',
                        'return_value' => 'bottom',
                        'condition'    => [
                                'oxi_addons_b_width' => '',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_title',
                [
                        'label'       => esc_html__('Title', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => esc_html__('Hover Title', 'oxi-hover-effects-addons'),
                        'placeholder' => esc_html__('Type your title here', 'oxi-hover-effects-addons'),
                        'separator'   => 'before',
                        'label_block' => true,
                        'dynamic'     => ['active' => true],
                        'condition'   => [
                                'oxi_addons_b_content_type' => 'title',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_title_tag',
                [
                        'label'     => esc_html__('Title Tag', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'h1'   => esc_html__('H1', 'oxi-hover-effects-addons'),
                                'h2'   => esc_html__('H2', 'oxi-hover-effects-addons'),
                                'h3'   => esc_html__('H3', 'oxi-hover-effects-addons'),
                                'h4'   => esc_html__('H4', 'oxi-hover-effects-addons'),
                                'h5'   => esc_html__('H5', 'oxi-hover-effects-addons'),
                                'h6'   => esc_html__('H6', 'oxi-hover-effects-addons'),
                                'p'    => esc_html__('Paragraph', 'oxi-hover-effects-addons'),
                                'span' => esc_html__('Span', 'oxi-hover-effects-addons'),
                                'div'  => esc_html__('Div', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => 'h3',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'title',
                        ],
                ],
        );

        $repeater->add_control(
                'oxi_addons_b_title_animation',
                [
                        'label'     => esc_html__('Animation', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => $this->init_content_animation_name(),
                        'default'   => '',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'title',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_title_animation_delay',
                [
                        'label'     => esc_html__('Animation Delay', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''                    => esc_html__('None', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xs'  => esc_html__('Delay XS', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-sm'  => esc_html__('Delay SM', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-md'  => esc_html__('Delay MD', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-lg'  => esc_html__('Delay LG', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xl'  => esc_html__('Delay XL', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xxl' => esc_html__('Delay XXL', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'title',
                        ],
                ],
        );

        $repeater->add_control(
                'oxi_addons_b_description',
                [
                        'label'       => esc_html__('Description', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::TEXTAREA,
                        'rows'        => 5,
                        'default'     => esc_html__('Description', 'oxi-hover-effects-addons'),
                        'placeholder' => esc_html__('Type your description here', 'oxi-hover-effects-addons'),
                        'show_label'  => true,
                        'dynamic'     => ['active' => true],
                        'condition'   => [
                                'oxi_addons_b_content_type' => 'description',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_description_tag',
                [
                        'label'     => esc_html__('Description Tag', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'p'    => esc_html__('Paragraph', 'oxi-hover-effects-addons'),
                                'span' => esc_html__('Span', 'oxi-hover-effects-addons'),
                                'div'  => esc_html__('Div', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => 'p',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'description',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_description_animation',
                [
                        'label'     => esc_html__('Animation', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => $this->init_content_animation_name(),
                        'default'   => '',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'description',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_description_animation_delay',
                [
                        'label'     => esc_html__('Animation Delay', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''                    => esc_html__('None', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xs'  => esc_html__('Delay XS', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-sm'  => esc_html__('Delay SM', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-md'  => esc_html__('Delay MD', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-lg'  => esc_html__('Delay LG', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xl'  => esc_html__('Delay XL', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xxl' => esc_html__('Delay XXL', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'description',
                        ],
                ],
        );

        $repeater->add_control(
                'oxi_addons_b_icon',
                [
                        'label'       => esc_html__('Icon', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::ICONS,
                        'label_block' => true,
                        'separator'   => 'before',
                        'condition'   => [
                                'oxi_addons_b_content_type' => 'icon',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_icon_animation',
                [
                        'label'     => esc_html__('Animation', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => $this->init_content_animation_name(),
                        'default'   => '',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'icon',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_icon_animation_delay',
                [
                        'label'     => esc_html__('Animation Delay', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''                    => esc_html__('None', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xs'  => esc_html__('Delay XS', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-sm'  => esc_html__('Delay SM', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-md'  => esc_html__('Delay MD', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-lg'  => esc_html__('Delay LG', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xl'  => esc_html__('Delay XL', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xxl' => esc_html__('Delay XXL', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'icon',
                        ],
                ],
        );

        $repeater->add_control(
                'oxi_addons_b_icon_link_type',
                [
                        'label'     => esc_html__('Link Type', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''         => esc_html__('None', 'oxi-hover-effects-addons'),
                                'link'     => esc_html__('Link', 'oxi-hover-effects-addons'),
                                'lightbox' => esc_html__('lightbox', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'separator' => 'before',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'icon',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_icon_lightbox',
                [
                        'label'     => esc_html__('Lightbox Image', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::MEDIA,
                        'default'   => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'dynamic'   => ['active' => true],
                        'condition' => [
                                'oxi_addons_b_content_type'   => 'icon',
                                'oxi_addons_b_icon_link_type' => 'lightbox',
                        ],
                ],
        );

        $repeater->add_control(
                'oxi_addons_b_icon_link',
                [
                        'label'         => __('Link To', 'oxi-hover-effects-addons'),
                        'type'          => Controls_Manager::URL,
                        'placeholder'   => __('https://your-link.com', 'oxi-hover-effects-addons'),
                        'show_external' => true,
                        'default'       => [
                                'url'         => '',
                                'is_external' => false,
                                'nofollow'    => false,
                        ],
                        'dynamic'       => ['active' => true],
                        'condition'     => [
                                'oxi_addons_b_content_type'   => 'icon',
                                'oxi_addons_b_icon_link_type' => 'link',
                        ],
                ],
        );

        $repeater->add_control(
                'oxi_addons_b_image',
                [
                        'label'     => esc_html__('Choose Image', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::MEDIA,
                        'default'   => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'dynamic'   => ['active' => true],
                        'condition' => [
                                'oxi_addons_b_content_type' => 'image',
                        ],
                ],
        );
        $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                        'name'      => 'oxi_addons_b_image_thumbnail',
                        'exclude'   => ['custom'],
                        'include'   => [],
                        'default'   => 'full',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'image',
                        ],
                ],
        );
        $repeater->add_responsive_control(
                'oxi_addons_b_image_opacity',
                [
                        'label'      => __('Opacity', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 1,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 1,
                                        'step' => 0.01,
                                ],
                        ],
                        'condition'  => [
                                'oxi_addons_b_content_type' => 'image',
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-hover-image' => 'opacity: {{SIZE}};',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_image_animation',
                [
                        'label'     => esc_html__('Animation', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => $this->init_content_animation_name(),
                        'default'   => '',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'image',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_image_animation_delay',
                [
                        'label'     => esc_html__('Animation Delay', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''                    => esc_html__('None', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xs'  => esc_html__('Delay XS', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-sm'  => esc_html__('Delay SM', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-md'  => esc_html__('Delay MD', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-lg'  => esc_html__('Delay LG', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xl'  => esc_html__('Delay XL', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xxl' => esc_html__('Delay XXL', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'image',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_button',
                [
                        'label'       => esc_html__('Button Text', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => esc_html__('Click Me', 'oxi-hover-effects-addons'),
                        'placeholder' => esc_html__('Type your title here', 'oxi-hover-effects-addons'),
                        'separator'   => 'before',
                        'label_block' => true,
                        'dynamic'     => ['active' => true],
                        'condition'   => [
                                'oxi_addons_b_content_type' => 'button',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_button_icon',
                [
                        'label'       => esc_html__('Button Icon', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::ICONS,
                        'label_block' => true,
                        'condition'   => [
                                'oxi_addons_b_content_type' => 'button',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_button_icon_alignment',
                [
                        'label'     => esc_html__('Icon Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'flex-start'  => [
                                        'title' => esc_html__('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'flex-top'    => [
                                        'title' => esc_html__('Top', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-top',
                                ],
                                'flex-bottom' => [
                                        'title' => esc_html__('Bottom', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-bottom',
                                ],
                                'flex-end'    => [
                                        'title' => esc_html__('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ]
                        ],
                        'condition' => [
                                'oxi_addons_b_content_type' => 'button',
                        ],
                        'default'   => 'flex-start',
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_button_animation',
                [
                        'label'     => esc_html__('Animation', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => $this->init_content_animation_name(),
                        'default'   => 'oxi-a-ani-regular',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'button',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_button_animation_delay',
                [
                        'label'     => esc_html__('Animation Delay', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''                    => esc_html__('None', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xs'  => esc_html__('Delay XS', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-sm'  => esc_html__('Delay SM', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-md'  => esc_html__('Delay MD', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-lg'  => esc_html__('Delay LG', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xl'  => esc_html__('Delay XL', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xxl' => esc_html__('Delay XXL', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'button',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_button_link_type',
                [
                        'label'     => esc_html__('Link Type', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''         => esc_html__('None', 'oxi-hover-effects-addons'),
                                'link'     => esc_html__('Link', 'oxi-hover-effects-addons'),
                                'lightbox' => esc_html__('lightbox', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'separator' => 'before',
                        'condition' => [
                                'oxi_addons_b_content_type' => 'button',
                        ],
                ],
        );
        $repeater->add_control(
                'oxi_addons_b_button_lightbox',
                [
                        'label'     => esc_html__('Lightbox Image', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::MEDIA,
                        'default'   => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'dynamic'   => ['active' => true],
                        'condition' => [
                                'oxi_addons_b_content_type'     => 'button',
                                'oxi_addons_b_button_link_type' => 'lightbox',
                        ],
                ],
        );

        $repeater->add_control(
                'oxi_addons_b_button_link',
                [
                        'label'         => __('Link To', 'oxi-hover-effects-addons'),
                        'type'          => Controls_Manager::URL,
                        'placeholder'   => __('https://your-link.com', 'oxi-hover-effects-addons'),
                        'show_external' => true,
                        'default'       => [
                                'url'         => '',
                                'is_external' => false,
                                'nofollow'    => false,
                        ],
                        'dynamic'       => ['active' => true],
                        'condition'     => [
                                'oxi_addons_b_content_type'     => 'button',
                                'oxi_addons_b_button_link_type' => 'link',
                        ],
                ],
        );

        $this->add_control(
                'oxi_addons_backend_content',
                [
                        'type'        => Controls_Manager::REPEATER,
                        'seperator'   => 'before',
                        'default'     => [
                                ['oxi_addons_b_content_type' => esc_html__('title', 'oxi-hover-effects-addons')],
                                ['oxi_addons_b_content_type' => esc_html__('description', 'oxi-hover-effects-addons')],
                        ],
                        'fields'      => $repeater->get_controls(),
                        'title_field' => '{{oxi_addons_b_content_type}}',
                        'condition'   => [
                                'oxi_addons_b_type' => 'content',
                        ],
                ],
        );
        $this->end_controls_section();
    }

    public function init_content_animation_name() {
        return [
                ''                           => esc_html__('None', 'oxi-hover-effects-addons'),
                'oxi-a-ani-regular'          => esc_html__('Regular', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-up'          => esc_html__('Fade Up', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-down'        => esc_html__('Fade Down', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-left'        => esc_html__('Fade Left', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-right'       => esc_html__('Fade Right', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-up-big'      => esc_html__('Fade up Big', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-down-big'    => esc_html__('Fade down Big', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-left-big'    => esc_html__('Fade left Big', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-right-big'   => esc_html__('Fade Right Big', 'oxi-hover-effects-addons'),
                'oxi-a-ani-zoom-in'          => esc_html__('Zoom In', 'oxi-hover-effects-addons'),
                'oxi-a-ani-zoom-out'         => esc_html__('Zoom Out', 'oxi-hover-effects-addons'),
                'oxi-a-ani-flip-x'           => esc_html__('Flip X', 'oxi-hover-effects-addons'),
                'oxi-a-ani-flip-y'           => esc_html__('Flip Y', 'oxi-hover-effects-addons'),
                'oxi-a-ani-scale-x'          => esc_html__('Scale X', 'oxi-hover-effects-addons'),
                'oxi-a-ani-scale-y'          => esc_html__('Scale Y', 'oxi-hover-effects-addons'),
                'oxi-a-ani-transform-left'   => esc_html__('Transform Left', 'oxi-hover-effects-addons'),
                'oxi-a-ani-transform-right'  => esc_html__('Transform Right', 'oxi-hover-effects-addons'),
                'oxi-a-ani-transform-top'    => esc_html__('Transform Top', 'oxi-hover-effects-addons'),
                'oxi-a-ani-transform-bottom' => esc_html__('Transform Bottom', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-left'      => esc_html__('Rotate Left', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-right'     => esc_html__('Rotate Right', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-left-c'    => esc_html__('Rotate Left C', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-right-c'   => esc_html__('Rotate Right C', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-top-c'     => esc_html__('Rotate Top C', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-bottom-c'  => esc_html__('Rotate Bottom C', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-left-cc'   => esc_html__('Rotate Left CC', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-right-cc'  => esc_html__('Rotate Right CC', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-top-cc'    => esc_html__('Rotate Top CC', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-bottom-cc' => esc_html__('Rotate Bottom CC', 'oxi-hover-effects-addons'),
        ];
    }

    public function init_content_url_controls() {
        $this->start_controls_section(
                'oxi_addons_b_Link',
                [
                        'label' => esc_html__('Link or Lightbox', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_CONTENT,
                ],
        );
        $this->add_control(
                'link_or_lightbox',
                [
                        'label'       => __('Link Or Lightbox', 'plugin-domain'),
                        'label_block' => true,
                        'type'        => Controls_Manager::SELECT,
                        'default'     => '',
                        'options'     => [
                                ''         => __('None', 'plugin-domain'),
                                'link'     => __('URL', 'plugin-domain'),
                                'lightbox' => __('Light Box', 'plugin-domain'),
                        ],
                ],
        );
        $this->add_control(
                'content_lightbox',
                [
                        'label'     => esc_html__('Lightbox Image', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::MEDIA,
                        'default'   => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'dynamic'   => ['active' => true],
                        'condition' => [
                                'link_or_lightbox' => 'lightbox',
                        ],
                ],
        );

        $this->add_control(
                'content_link',
                [
                        'label'         => __('Link To', 'oxi-hover-effects-addons'),
                        'type'          => Controls_Manager::URL,
                        'placeholder'   => __('https://your-link.com', 'oxi-hover-effects-addons'),
                        'show_external' => true,
                        'separator'     => 'before',
                        'default'       => [
                                'url'         => '',
                                'is_external' => false,
                                'nofollow'    => false,
                        ],
                        'dynamic'       => ['active' => true],
                        'condition'     => [
                                'link_or_lightbox' => 'link',
                        ],
                ],
        );
        $this->end_controls_section();
    }

    public function init_content_promotion_controls() {

    }

    public function init_style_general_controls() {
        $this->start_controls_section(
                'general_style_settings',
                [
                        'label' => esc_html__('General Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );
        $this->start_controls_tabs('general_section_style_settings');
        $this->start_controls_tab('general_section_front_style_settings', [
                'label' => esc_html__('Front', 'oxi-hover-effects-addons'),
        ]);
        $this->init_style_frontend_controls();
        $this->end_controls_tab();
        $this->start_controls_tab('general_section_back_style_settings', [
                'label' => esc_html__('Backend', 'oxi-hover-effects-addons'),
        ]);
        $this->init_style_backend_controls();
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function init_style_frontend_controls() {

        $this->add_group_control(Group_Control_Background::get_type(), [
                'name'     => "frontend_style_background",
                'label'    => __('Background', 'oxi-hover-effects-addons'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .oxi-flip-box-frontend-container'
        ]);
        $this->add_control(
                'frontend_align',
                [
                        'label'   => esc_html__('Horizontal Alignment', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::CHOOSE,
                        'options' => [
                                'flex-start' => [
                                        'title' => esc_html__('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-h-align-left',
                                ],
                                'center'     => [
                                        'title' => esc_html__('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-h-align-center',
                                ],
                                'flex-end'   => [
                                        'title' => esc_html__('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-h-align-right',
                                ]
                        ],
                        'default' => '',
                ],
        );
        $this->add_control(
                'frontend_vertical_align',
                [
                        'label'   => esc_html__('Vertical Alignment', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::CHOOSE,
                        'options' => [
                                'flex-start' => [
                                        'title' => esc_html__('Top', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-top',
                                ],
                                'center'     => [
                                        'title' => esc_html__('Middle', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-middle',
                                ],
                                'flex-end'   => [
                                        'title' => esc_html__('Bottom', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-bottom',
                                ]
                        ],
                        'default' => '',
                ],
        );
        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name'     => 'frontend_style_border',
                        'label'    => esc_html__('Border', 'oxi-hover-effects-addons'),
                        'selector' => '{{WRAPPER}} .oxi-flip-box-frontend-container',
                ],
        );
        $this->add_responsive_control(
                'frontend_style_border_radius',
                [
                        'label'      => esc_html__('Border Radius', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden ;',
                        ],
                ],
        );
        $this->add_responsive_control(
                'frontend_style_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name'     => 'frontend_style_box_shadow',
                        'selector' => '{{WRAPPER}} .oxi-flip-box-frontend-container',
                ],
        );
    }

    public function init_style_backend_controls() {

        $this->add_group_control(Group_Control_Background::get_type(), [
                'name'     => "backend_style_background",
                'label'    => __('Background', 'oxi-hover-effects-addons'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .oxi-flip-box-backend-container'
        ]);
        $this->add_control(
                'backend_align',
                [
                        'label'   => esc_html__('Horizontal Alignment', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::CHOOSE,
                        'options' => [
                                'flex-start' => [
                                        'title' => esc_html__('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-h-align-left',
                                ],
                                'center'     => [
                                        'title' => esc_html__('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-h-align-center',
                                ],
                                'flex-end'   => [
                                        'title' => esc_html__('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-h-align-right',
                                ]
                        ],
                        'default' => '',
                ],
        );
        $this->add_control(
                'backend_vertical_align',
                [
                        'label'   => esc_html__('Vertical Alignment', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::CHOOSE,
                        'options' => [
                                'flex-start' => [
                                        'title' => esc_html__('Top', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-top',
                                ],
                                'center'     => [
                                        'title' => esc_html__('Middle', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-middle',
                                ],
                                'flex-end'   => [
                                        'title' => esc_html__('Bottom', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-bottom',
                                ]
                        ],
                        'default' => '',
                ],
        );
        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name'     => 'backend_style_border',
                        'label'    => esc_html__('Border', 'oxi-hover-effects-addons'),
                        'selector' => '{{WRAPPER}} .oxi-flip-box-backend-container',
                ],
        );
        $this->add_responsive_control(
                'backend_style_border_radius',
                [
                        'label'      => esc_html__('Border Radius', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden ;',
                        ],
                ],
        );
        $this->add_responsive_control(
                'backend_style_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name'     => 'backend_style_box_shadow',
                        'selector' => '{{WRAPPER}} .oxi-flip-box-backend-container',
                ],
        );
    }

    public function init_style_title_controls() {
        $this->start_controls_section(
                'title_style_settings',
                [
                        'label' => esc_html__('Title Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );
        $this->add_control(
                'title_style_interface',
                [
                        'label'        => esc_html__('Interface', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __('Advanced', 'oxi-hover-effects-addons'),
                        'label_off'    => __('simple', 'oxi-hover-effects-addons'),
                        'default'      => '',
                        'return_value' => 'advance',
                ],
        );
        $this->start_controls_tabs('title_section_style_settings');
        $this->start_controls_tab('title_section_front_style_settings', [
                'label' => esc_html__('Front', 'oxi-hover-effects-addons'),
        ]);
        $this->init_style_frontend_title_controls();
        $this->end_controls_tab();
        $this->start_controls_tab('title_section_back_style_settings', [
                'label' => esc_html__('Backend', 'oxi-hover-effects-addons'),
        ]);
        $this->init_style_backend_title_controls();
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function init_style_frontend_title_controls() {
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name'     => 'frontend_title_style_settings_typography',
                        'selector' => '{{WRAPPER}} .oxi-image-caption-hover .oxi-image-hover-addons-heading',
                ],
        );
        $this->add_responsive_control(
                'frontend_title_style_alignment',
                [
                        'label'     => __('Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'left'    => [
                                        'title' => __('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'center'  => [
                                        'title' => __('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-center',
                                ],
                                'right'   => [
                                        'title' => __('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ],
                                'justify' => [
                                        'title' => __('Justified', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-justify',
                                ],
                        ],
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-heading' => 'text-align: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'frontend_title_style_settings_color',
                [
                        'label'     => esc_html__('Text Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '#FFF',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-heading' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'frontend_title_style_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => 'rgba(126, 0, 184, 1)',
                        'selectors' => [
                                "{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-heading" => "background: {{VALUE}};",
                        ],
                        'condition' => [
                                'title_style_interface' => 'advance',
                        ],
                ],
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name'      => 'frontend_title_style_border',
                        'label'     => esc_html__('Border', 'oxi-hover-effects-addons'),
                        'condition' => [
                                'title_style_interface' => 'advance',
                        ],
                        'selector'  => '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-heading',
                ],
        );
        $this->add_responsive_control(
                'frontend_title_style_border_radius',
                [
                        'label'      => esc_html__('Border Radius', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'condition'  => [
                                'title_style_interface' => 'advance',
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden ;',
                        ],
                ],
        );
        $this->add_responsive_control(
                'frontend_title_style_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'frontend_title_style_margin',
                [
                        'label'      => esc_html__('Margin', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'condition'  => [
                                'title_style_interface' => 'advance',
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );
    }

    public function init_style_backend_title_controls() {
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name'     => 'backend_title_style_settings_typography',
                        'selector' => '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-heading',
                ],
        );
        $this->add_responsive_control(
                'backend_title_style_alignment',
                [
                        'label'     => __('Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'left'    => [
                                        'title' => __('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'center'  => [
                                        'title' => __('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-center',
                                ],
                                'right'   => [
                                        'title' => __('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ],
                                'justify' => [
                                        'title' => __('Justified', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-justify',
                                ],
                        ],
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-heading' => 'text-align: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'backend_title_style_settings_color',
                [
                        'label'     => esc_html__('Text Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '#FFF',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-heading' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'backend_title_style_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => 'rgba(126, 0, 184, 1)',
                        'selectors' => [
                                "{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-heading" => "background: {{VALUE}};",
                        ],
                        'condition' => [
                                'title_style_interface' => 'advance',
                        ],
                ],
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name'      => 'backend_title_style_border',
                        'label'     => esc_html__('Border', 'oxi-hover-effects-addons'),
                        'condition' => [
                                'title_style_interface' => 'advance',
                        ],
                        'selector'  => '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-heading',
                ],
        );
        $this->add_responsive_control(
                'backend_title_style_border_radius',
                [
                        'label'      => esc_html__('Border Radius', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'condition'  => [
                                'title_style_interface' => 'advance',
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden ;',
                        ],
                ],
        );
        $this->add_responsive_control(
                'backend_title_style_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'backend_title_style_margin',
                [
                        'label'      => esc_html__('Margin', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'condition'  => [
                                'title_style_interface' => 'advance',
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );
    }

    public function init_style_desc_controls() {
        $this->start_controls_section(
                'desc_style_settings',
                [
                        'label' => esc_html__('Desc Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );

        $this->start_controls_tabs('desc_section_style_settings');
        $this->start_controls_tab('desc_section_front_style_settings', [
                'label' => esc_html__('Front', 'oxi-hover-effects-addons'),
        ]);
        $this->init_style_frontend_desc_controls();
        $this->end_controls_tab();
        $this->start_controls_tab('desc_section_back_style_settings', [
                'label' => esc_html__('Backend', 'oxi-hover-effects-addons'),
        ]);
        $this->init_style_backend_desc_controls();
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function init_style_frontend_desc_controls() {
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name'     => 'frontend_desc_style_settings_typography',
                        'selector' => '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-cont',
                ],
        );
        $this->add_responsive_control(
                'frontend_desc_style_alignment',
                [
                        'label'     => __('Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'left'    => [
                                        'title' => __('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'center'  => [
                                        'title' => __('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-center',
                                ],
                                'right'   => [
                                        'title' => __('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ],
                                'justify' => [
                                        'title' => __('Justified', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-justify',
                                ],
                        ],
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-cont' => 'text-align: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'frontend_desc_style_settings_color',
                [
                        'label'     => esc_html__('Text Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '#FFF',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-cont' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'frontend_desc_style_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-cont' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator'  => 'before',
                ],
        );
    }

    public function init_style_backend_desc_controls() {
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name'     => 'backend_desc_style_settings_typography',
                        'selector' => '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-cont',
                ],
        );
        $this->add_responsive_control(
                'backend_desc_style_alignment',
                [
                        'label'     => __('Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'left'    => [
                                        'title' => __('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'center'  => [
                                        'title' => __('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-center',
                                ],
                                'right'   => [
                                        'title' => __('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ],
                                'justify' => [
                                        'title' => __('Justified', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-justify',
                                ],
                        ],
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-cont' => 'text-align: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'backend_desc_style_settings_color',
                [
                        'label'     => esc_html__('Text Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '#FFF',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-cont' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'backend_desc_style_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-cont' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator'  => 'before',
                ],
        );
    }

    public function init_style_icon_controls() {
        $this->start_controls_section(
                'icon_style_settings',
                [
                        'label' => esc_html__('Icon Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );

        $this->start_controls_tabs('icon_section_style_settings');
        $this->start_controls_tab('icon_section_front_style_settings', [
                'label' => esc_html__('Front', 'oxi-hover-effects-addons'),
        ]);
        $this->init_style_front_icon_controls();
        $this->end_controls_tab();
        $this->start_controls_tab('icon_section_back_style_settings', [
                'label' => esc_html__('Backend', 'oxi-hover-effects-addons'),
        ]);
        $this->init_style_back_icon_controls();
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function init_style_front_icon_controls() {
        $this->add_responsive_control(
                'front_icon_style_size',
                [
                        'label'      => __('Icon Size', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 100,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-icon svg' => 'max-height: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'front_icon_style_alignment',
                [
                        'label'     => __('Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'left'    => [
                                        'title' => __('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'center'  => [
                                        'title' => __('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-center',
                                ],
                                'right'   => [
                                        'title' => __('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ],
                                'justify' => [
                                        'title' => __('Justified', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-justify',
                                ],
                        ],
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-icon' => 'text-align: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'front_icon_style_interface',
                [
                        'label'        => esc_html__('Style Interface', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __('Advanced', 'oxi-hover-effects-addons'),
                        'label_off'    => __('simple', 'oxi-hover-effects-addons'),
                        'default'      => '',
                        'return_value' => 'advance',
                ],
        );
        $this->add_responsive_control(
                'front_icon_style_width',
                [
                        'label'      => __('Width', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 75,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 200,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-image-addons-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; display: inline-flex; align-items: center; justify-content: center;',
                        ],
                        'condition'  => [
                                'front_icon_style_interface' => 'advance',
                        ],
                ],
        );
        $this->add_responsive_control(
                'front_icon_style_height',
                [
                        'label'      => __('Height', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => '',
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 200,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-image-addons-icon' => ' height: {{SIZE}}{{UNIT}} !important;',
                        ],
                        'condition'  => [
                                'front_icon_style_interface' => 'advance',
                        ],
                ],
        );
        $this->add_control(
                'front_icon_style_settings_color',
                [
                        'label'     => esc_html__('Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '#FFF',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-image-addons-icon,'
                                . ' {{WRAPPER}} .oxi-flip-box-frontend-container .oxi-image-addons-icon i' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'front_icon_style_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => 'rgba(126, 0, 184, 1)',
                        'selectors' => [
                                "{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-image-addons-icon" => "background: {{VALUE}};",
                        ],
                        'condition' => [
                                'front_icon_style_interface' => 'advance',
                        ],
                ],
        );
        $this->add_responsive_control(
                'front_icon_style_border_radius',
                [
                        'label'      => esc_html__('Border Radius', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-image-addons-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden ;',
                        ],
                        'condition'  => [
                                'front_icon_style_interface' => 'advance',
                        ],
                ],
        );
        $this->add_responsive_control(
                'front_icon_style_settings_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-image-addons-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'front_icon_style_settings_margin',
                [
                        'label'      => esc_html__('Margin', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-image-addons-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'condition'  => [
                                'front_icon_style_interface' => 'advance',
                        ],
                ],
        );
    }

    public function init_style_back_icon_controls() {
        $this->add_responsive_control(
                'back_icon_style_size',
                [
                        'label'      => __('Icon Size', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 100,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon svg' => 'max-height: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'back_icon_style_alignment',
                [
                        'label'     => __('Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'left'    => [
                                        'title' => __('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'center'  => [
                                        'title' => __('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-center',
                                ],
                                'right'   => [
                                        'title' => __('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ],
                                'justify' => [
                                        'title' => __('Justified', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-justify',
                                ],
                        ],
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-icon' => 'text-align: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'back_icon_style_interface',
                [
                        'label'        => esc_html__('Style Interface', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __('Advanced', 'oxi-hover-effects-addons'),
                        'label_off'    => __('simple', 'oxi-hover-effects-addons'),
                        'default'      => '',
                        'return_value' => 'advance',
                ],
        );
        $this->add_responsive_control(
                'back_icon_style_width',
                [
                        'label'      => __('Width', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 75,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 200,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; display: inline-flex; align-items: center; justify-content: center;',
                        ],
                        'condition'  => [
                                'back_icon_style_interface' => 'advance',
                        ],
                ],
        );
        $this->add_responsive_control(
                'back_icon_style_height',
                [
                        'label'      => __('Height', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => '',
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 200,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon' => 'height: {{SIZE}}{{UNIT}}; !important',
                        ],
                        'condition'  => [
                                'back_icon_style_interface' => 'advance',
                        ],
                ],
        );
        $this->add_control(
                'back_icon_style_settings_color',
                [
                        'label'     => esc_html__('Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '#FFF',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon,'
                                . ' {{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon i' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'back_icon_style_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => 'rgba(126, 0, 184, 1)',
                        'selectors' => [
                                "{{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon" => "background: {{VALUE}};",
                        ],
                        'condition' => [
                                'back_icon_style_interface' => 'advance',
                        ],
                ],
        );
        $this->add_control(
                'back_icon_style_settings_color_hover',
                [
                        'label'     => esc_html__('Hover Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '#FFF',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon:hover,'
                                . ' {{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon:hover i' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'back_icon_style_background_hover',
                [
                        'label'     => esc_html__('Hover Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => 'rgba(126, 0, 184, 1)',
                        'selectors' => [
                                "{{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon:hover" => "background: {{VALUE}};",
                        ],
                        'condition' => [
                                'back_icon_style_interface' => 'advance',
                        ],
                ],
        );
        $this->add_responsive_control(
                'back_icon_style_border_radius',
                [
                        'label'      => esc_html__('Border Radius', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden ;',
                        ],
                        'condition'  => [
                                'back_icon_style_interface' => 'advance',
                        ],
                ],
        );
        $this->add_responsive_control(
                'back_icon_style_settings_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-image-addons-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'back_icon_style_settings_margin',
                [
                        'label'      => esc_html__('Margin', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'condition'  => [
                                'back_icon_style_interface' => 'advance',
                        ],
                ],
        );
    }

    public function init_style_image_controls() {
        $this->start_controls_section(
                'image_style_settings',
                [
                        'label' => esc_html__('Image Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );

        $this->start_controls_tabs('image_section_style_settings');
        $this->start_controls_tab('image_section_front_style_settings', [
                'label' => esc_html__('Front', 'oxi-hover-effects-addons'),
        ]);
        $this->init_style_front_image_controls();
        $this->end_controls_tab();
        $this->start_controls_tab('image_section_back_style_settings', [
                'label' => esc_html__('Backend', 'oxi-hover-effects-addons'),
        ]);
        $this->init_style_back_image_controls();
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function init_style_front_image_controls() {
        $this->add_responsive_control(
                'front_image_style_width',
                [
                        'label'      => __('Width', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px', 'em', '%'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 1000,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'front_image_style_height',
                [
                        'label'      => __('Height', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px', 'em', '%'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 1000,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_control(
                'front_image_style_transform',
                [
                        'label'              => __('Animation', 'oxi-hover-effects-addons'),
                        'type'               => Controls_Manager::POPOVER_TOGGLE,
                        'return_value'       => 'yes',
                        'frontend_available' => true,
                ],
        );
        $this->start_popover();

        $this->add_responsive_control(
                'front_image_style_translate_x',
                [
                        'label'      => __('Translate X', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => -500,
                                        'max'  => 500,
                                        'step' => 1,
                                ],
                        ],
                        'condition'  => [
                                'front_image_style_transform' => 'yes',
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img' =>
                                '-ms-transform:'
                                . 'translate({{front_image_style_translate_x.SIZE || 0}}px, {{front_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{front_image_style_translate_x.SIZE || 0}}px, {{front_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{front_image_style_translate_x.SIZE || 0}}px, {{front_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{front_image_style_translate_x_tablet.SIZE || 0}}px, {{front_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_tablet.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{front_image_style_translate_x_tablet.SIZE || 0}}px, {{front_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_tablet.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{front_image_style_translate_x_tablet.SIZE || 0}}px, {{front_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_tablet.SIZE || 1}});',
                                '(mobile){{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{front_image_style_translate_x_mobile.SIZE || 0}}px, {{front_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_mobile.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{front_image_style_translate_x_mobile.SIZE || 0}}px, {{front_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_mobile.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{front_image_style_translate_x_mobile.SIZE || 0}}px, {{front_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_mobile.SIZE || 1}})'
                        ]
                ],
        );
        $this->add_responsive_control(
                'front_image_style_translate_y',
                [
                        'label'      => __('Translate Y', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => -500,
                                        'max'  => 500,
                                        'step' => 1,
                                ],
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'condition'  => [
                                'front_image_style_transform' => 'yes',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img' =>
                                '-ms-transform:'
                                . 'translate({{front_image_style_translate_x.SIZE || 0}}px, {{front_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{front_image_style_translate_x.SIZE || 0}}px, {{front_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{front_image_style_translate_x.SIZE || 0}}px, {{front_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{front_image_style_translate_x_tablet.SIZE || 0}}px, {{front_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_tablet.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{front_image_style_translate_x_tablet.SIZE || 0}}px, {{front_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_tablet.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{front_image_style_translate_x_tablet.SIZE || 0}}px, {{front_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_tablet.SIZE || 1}});',
                                '(mobile){{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{front_image_style_translate_x_mobile.SIZE || 0}}px, {{front_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_mobile.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{front_image_style_translate_x_mobile.SIZE || 0}}px, {{front_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_mobile.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{front_image_style_translate_x_mobile.SIZE || 0}}px, {{front_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_mobile.SIZE || 1}})'
                        ]
                ],
        );
        $this->add_responsive_control(
                'front_image_style_rotate',
                [
                        'label'      => __('Rotate', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => -180,
                                        'max'  => 180,
                                        'step' => 1,
                                ],
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'condition'  => [
                                'front_image_style_transform' => 'yes',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img' =>
                                '-ms-transform:'
                                . 'translate({{front_image_style_translate_x.SIZE || 0}}px, {{front_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{front_image_style_translate_x.SIZE || 0}}px, {{front_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{front_image_style_translate_x.SIZE || 0}}px, {{front_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{front_image_style_translate_x_tablet.SIZE || 0}}px, {{front_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_tablet.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{front_image_style_translate_x_tablet.SIZE || 0}}px, {{front_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_tablet.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{front_image_style_translate_x_tablet.SIZE || 0}}px, {{front_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_tablet.SIZE || 1}});',
                                '(mobile){{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{front_image_style_translate_x_mobile.SIZE || 0}}px, {{front_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_mobile.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{front_image_style_translate_x_mobile.SIZE || 0}}px, {{front_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_mobile.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{front_image_style_translate_x_mobile.SIZE || 0}}px, {{front_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_mobile.SIZE || 1}})'
                        ]
                ],
        );
        $this->add_responsive_control(
                'front_image_style_scale',
                [
                        'label'      => __('Scale', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'default'    => [
                                'size' => 1
                        ],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 5,
                                        'step' => 0.01,
                                ],
                        ],
                        'condition'  => [
                                'front_image_style_transform' => 'yes',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img' =>
                                '-ms-transform:'
                                . 'translate({{front_image_style_translate_x.SIZE || 0}}px, {{front_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{front_image_style_translate_x.SIZE || 0}}px, {{front_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{front_image_style_translate_x.SIZE || 0}}px, {{front_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{front_image_style_translate_x_tablet.SIZE || 0}}px, {{front_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_tablet.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{front_image_style_translate_x_tablet.SIZE || 0}}px, {{front_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_tablet.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{front_image_style_translate_x_tablet.SIZE || 0}}px, {{front_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_tablet.SIZE || 1}});',
                                '(mobile){{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{front_image_style_translate_x_mobile.SIZE || 0}}px, {{front_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_mobile.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{front_image_style_translate_x_mobile.SIZE || 0}}px, {{front_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_mobile.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{front_image_style_translate_x_mobile.SIZE || 0}}px, {{front_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{front_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{front_image_style_scale_mobile.SIZE || 1}})'
                        ]
                ],
        );
        $this->end_popover();
        $this->add_responsive_control(
                'front_image_style_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator'  => 'before',
                ],
        );
    }

    public function init_style_back_image_controls() {
        $this->add_responsive_control(
                'back_image_style_width',
                [
                        'label'      => __('Width', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px', 'em', '%'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 1000,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'back_image_style_height',
                [
                        'label'      => __('Height', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px', 'em', '%'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 1000,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_control(
                'back_image_style_transform',
                [
                        'label'              => __('Animation', 'oxi-hover-effects-addons'),
                        'type'               => Controls_Manager::POPOVER_TOGGLE,
                        'return_value'       => 'yes',
                        'frontend_available' => true,
                ],
        );
        $this->start_popover();

        $this->add_responsive_control(
                'back_image_style_translate_x',
                [
                        'label'      => __('Translate X', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => -500,
                                        'max'  => 500,
                                        'step' => 1,
                                ],
                        ],
                        'condition'  => [
                                'back_image_style_transform' => 'yes',
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img' =>
                                '-ms-transform:'
                                . 'translate({{back_image_style_translate_x.SIZE || 0}}px, {{back_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{back_image_style_translate_x.SIZE || 0}}px, {{back_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{back_image_style_translate_x.SIZE || 0}}px, {{back_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{back_image_style_translate_x_tablet.SIZE || 0}}px, {{back_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_tablet.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{back_image_style_translate_x_tablet.SIZE || 0}}px, {{back_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_tablet.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{back_image_style_translate_x_tablet.SIZE || 0}}px, {{back_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_tablet.SIZE || 1}});',
                                '(mobile){{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{back_image_style_translate_x_mobile.SIZE || 0}}px, {{back_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_mobile.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{back_image_style_translate_x_mobile.SIZE || 0}}px, {{back_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_mobile.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{back_image_style_translate_x_mobile.SIZE || 0}}px, {{back_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_mobile.SIZE || 1}})'
                        ]
                ],
        );
        $this->add_responsive_control(
                'back_image_style_translate_y',
                [
                        'label'      => __('Translate Y', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => -500,
                                        'max'  => 500,
                                        'step' => 1,
                                ],
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'condition'  => [
                                'back_image_style_transform' => 'yes',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img' =>
                                '-ms-transform:'
                                . 'translate({{back_image_style_translate_x.SIZE || 0}}px, {{back_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{back_image_style_translate_x.SIZE || 0}}px, {{back_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{back_image_style_translate_x.SIZE || 0}}px, {{back_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{back_image_style_translate_x_tablet.SIZE || 0}}px, {{back_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_tablet.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{back_image_style_translate_x_tablet.SIZE || 0}}px, {{back_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_tablet.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{back_image_style_translate_x_tablet.SIZE || 0}}px, {{back_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_tablet.SIZE || 1}});',
                                '(mobile){{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{back_image_style_translate_x_mobile.SIZE || 0}}px, {{back_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_mobile.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{back_image_style_translate_x_mobile.SIZE || 0}}px, {{back_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_mobile.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{back_image_style_translate_x_mobile.SIZE || 0}}px, {{back_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_mobile.SIZE || 1}})'
                        ]
                ],
        );
        $this->add_responsive_control(
                'back_image_style_rotate',
                [
                        'label'      => __('Rotate', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => -180,
                                        'max'  => 180,
                                        'step' => 1,
                                ],
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'condition'  => [
                                'back_image_style_transform' => 'yes',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img' =>
                                '-ms-transform:'
                                . 'translate({{back_image_style_translate_x.SIZE || 0}}px, {{back_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{back_image_style_translate_x.SIZE || 0}}px, {{back_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{back_image_style_translate_x.SIZE || 0}}px, {{back_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{back_image_style_translate_x_tablet.SIZE || 0}}px, {{back_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_tablet.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{back_image_style_translate_x_tablet.SIZE || 0}}px, {{back_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_tablet.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{back_image_style_translate_x_tablet.SIZE || 0}}px, {{back_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_tablet.SIZE || 1}});',
                                '(mobile){{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{back_image_style_translate_x_mobile.SIZE || 0}}px, {{back_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_mobile.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{back_image_style_translate_x_mobile.SIZE || 0}}px, {{back_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_mobile.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{back_image_style_translate_x_mobile.SIZE || 0}}px, {{back_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_mobile.SIZE || 1}})'
                        ]
                ],
        );
        $this->add_responsive_control(
                'back_image_style_scale',
                [
                        'label'      => __('Scale', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'default'    => [
                                'size' => 1
                        ],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 5,
                                        'step' => 0.01,
                                ],
                        ],
                        'condition'  => [
                                'back_image_style_transform' => 'yes',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img' =>
                                '-ms-transform:'
                                . 'translate({{back_image_style_translate_x.SIZE || 0}}px, {{back_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{back_image_style_translate_x.SIZE || 0}}px, {{back_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{back_image_style_translate_x.SIZE || 0}}px, {{back_image_style_translate_y.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{back_image_style_translate_x_tablet.SIZE || 0}}px, {{back_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_tablet.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{back_image_style_translate_x_tablet.SIZE || 0}}px, {{back_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_tablet.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{back_image_style_translate_x_tablet.SIZE || 0}}px, {{back_image_style_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_tablet.SIZE || 1}});',
                                '(mobile){{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image img'  =>
                                '-ms-transform:'
                                . 'translate({{back_image_style_translate_x_mobile.SIZE || 0}}px, {{back_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_mobile.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{back_image_style_translate_x_mobile.SIZE || 0}}px, {{back_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_mobile.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{back_image_style_translate_x_mobile.SIZE || 0}}px, {{back_image_style_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{back_image_style_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{back_image_style_scale_mobile.SIZE || 1}})'
                        ]
                ],
        );
        $this->end_popover();
        $this->add_responsive_control(
                'back_image_style_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator'  => 'before',
                ],
        );
    }

    public function init_style_divider_controls() {
        $this->start_controls_section(
                'divider_style_settings',
                [
                        'label' => esc_html__('Divider Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );

        $this->start_controls_tabs('divider_section_style_settings');
        $this->start_controls_tab('divider_section_front_style_settings', [
                'label' => esc_html__('Front', 'oxi-hover-effects-addons'),
        ]);
        $this->init_style_front_divider_controls();
        $this->end_controls_tab();
        $this->start_controls_tab('divider_section_back_style_settings', [
                'label' => esc_html__('Backend', 'oxi-hover-effects-addons'),
        ]);
        $this->init_style_back_divider_controls();
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function init_style_front_divider_controls() {
        $this->add_responsive_control(
                'front_divider_style_width',
                [
                        'label'      => __('Width', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 75,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 2000,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-frontend-container .oxi-flipbox-content-divider' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'front_divider_style_height',
                [
                        'label'      => __('Height', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 2,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 200,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}}  .oxi-flip-box-frontend-container .oxi-flipbox-content-divider-span' => 'border-top: {{SIZE}}px; important',
                        ],
                ],
        );
        $this->add_control(
                'front_divider_style_type',
                [
                        'label'     => esc_html__('Type', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'solid'  => esc_html__('Solid', 'oxi-hover-effects-addons'),
                                'double' => esc_html__('Double', 'oxi-hover-effects-addons'),
                                'dotted' => esc_html__('Dotted', 'oxi-hover-effects-addons'),
                                'dashed' => esc_html__('Dashed', 'oxi-hover-effects-addons'),
                                'groove' => esc_html__('Groove', 'oxi-hover-effects-addons'),
                        ],
                        'selectors' => [
                                '{{WRAPPER}}  .oxi-flip-box-frontend-container .oxi-flipbox-content-divider-span' => 'border-bottom-style: {{VALUE}};',
                        ],
                        'default'   => 'solid',
                ],
        );

        $this->add_control(
                'front_divider_style_settings',
                [
                        'label'     => esc_html__('Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}}  .oxi-flip-box-frontend-container .oxi-flipbox-content-divider-span' => 'border-color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'front_divider_style_settings_margin',
                [
                        'label'      => esc_html__('Margin', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-container .oxi-flipbox-content-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );
    }

    public function init_style_back_divider_controls() {
        $this->add_responsive_control(
                'back_divider_style_width',
                [
                        'label'      => __('Width', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 75,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 2000,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-divider' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'back_divider_style_height',
                [
                        'label'      => __('Width', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 2,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 200,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-divider' => 'border-top: {{SIZE}}px; important',
                        ],
                ],
        );
        $this->add_control(
                'back_divider_style_type',
                [
                        'label'     => esc_html__('Type', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'solid'  => esc_html__('Solid', 'oxi-hover-effects-addons'),
                                'double' => esc_html__('Double', 'oxi-hover-effects-addons'),
                                'dotted' => esc_html__('Dotted', 'oxi-hover-effects-addons'),
                                'dashed' => esc_html__('Dashed', 'oxi-hover-effects-addons'),
                                'groove' => esc_html__('Groove', 'oxi-hover-effects-addons'),
                        ],
                        'selectors' => [
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-divider-span' => 'border-bottom-style: {{VALUE}};',
                        ],
                        'default'   => 'solid',
                ],
        );

        $this->add_control(
                'back_divider_style_settings',
                [
                        'label'     => esc_html__('Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-divider-span' => 'border-color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'back_divider_style_settings_margin',
                [
                        'label'      => esc_html__('Margin', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-divider-span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );
    }

    public function init_style_button_controls() {


        $this->start_controls_section(
                'button_style_settings',
                [
                        'label' => esc_html__('Button Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );
        $this->add_responsive_control(
                'button_style_alignment',
                [
                        'label'     => __('Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'left'    => [
                                        'title' => __('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'center'  => [
                                        'title' => __('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-center',
                                ],
                                'right'   => [
                                        'title' => __('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ],
                                'justify' => [
                                        'title' => __('Justified', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-justify',
                                ],
                        ],
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-button' => 'text-align: {{VALUE}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'button_style_size',
                [
                        'label'      => __('Font Size', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 100,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-button-span'     => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-button-span i'   => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-button-span svg' => 'max-width: {{SIZE}}{{UNIT}};max-height: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'button_style_size_icon_gap',
                [
                        'label'      => __('Icon Gap', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 10,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 100,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-button-span i'   => 'margin: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-button-span svg' => 'margin: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name'     => 'button_style_settings_typography',
                        'exclude'  => [
                                'font_size',
                        ],
                        'selector' => '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-button-span',
                ],
        );
        $this->start_controls_tabs('button_section_style_settings');
        $this->start_controls_tab('button_section_front_style_settings', [
                'label' => esc_html__('Normal', 'oxi-hover-effects-addons'),
        ]);
        $this->add_control(
                'button_style_settings_color',
                [
                        'label'     => esc_html__('Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-button-span, {{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-button-span i' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'button_style_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                "{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-button-span" => "background: {{VALUE}};",
                        ],
                ],
        );
        $this->end_controls_tab();
        $this->start_controls_tab('button_section_back_style_settings', [
                'label' => esc_html__('Hover', 'oxi-hover-effects-addons'),
        ]);
        $this->add_control(
                'button_style_settings_hover_color',
                [
                        'label'     => esc_html__('Text Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-button-span:hover, {{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-button-span:hover i' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'button_style_hover_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                "{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-button-span:hover" => "background: {{VALUE}};",
                        ]
                ],
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'button_style_settings_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-button-span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator'  => 'before',
                ],
        );
        $this->add_responsive_control(
                'button_style_settings_margin',
                [
                        'label'      => esc_html__('Margin', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-flip-box-backend-container .oxi-flipbox-content-button-span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator'  => 'before',
                ],
        );
        $this->add_responsive_control(
                'button_style_border_radius',
                [
                        'label'      => esc_html__('Border Radius', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}}  .oxi-flip-box-backend-container .oxi-flipbox-content-button-span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden;',
                        ],
                ],
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('flipbox-main-wrap', ['class' => ['oxi-flip-box-container oxi-flip-box-style-animate-push', 'oxi-flip-box-style', esc_attr($settings['oxi_addons_flip_type']), esc_attr($settings['oxi_addons_flip_timing_type']), esc_attr($settings['oxi_addons_flip_timing_type']), esc_attr($settings['oxi_addons_flip_type_3d']),],],);
        $this->add_render_attribute('flipbox-container', 'class', 'oxi-flip-box-figure');
        $parent_tag = 'div';
        if ($settings['link_or_lightbox'] != '') {
            if ($settings['link_or_lightbox'] === 'link' && !empty($settings['content_link']['url'])) {
                $parent_tag = 'a';
                $this->add_link_attributes('flipbox-container', $settings['content_link']);
            } elseif ($settings['link_or_lightbox'] === 'lightbox' && !empty($settings['content_lightbox']['url'])) {
                $parent_tag = 'a';
                $this->add_render_attribute('flipbox-container', [
                        'href'                         => esc_url($settings['content_lightbox']['url']),
                        'data-elementor-open-lightbox' => 'yes',
                ]);
            }
        }
        ?>
        <div <?php echo $this->get_render_attribute_string('flipbox-main-wrap'); ?>>

                <<?php echo $parent_tag, ' ', $this->get_render_attribute_string('flipbox-container'); ?>>

                <div class="oxi-flip-box-frontend-container">

                        <?php
                        if ($settings['oxi_addons_f_type'] == 'template') {
                            if (!empty($settings['oxi_addons_f_templates'])) {
                                echo Plugin::$instance->frontend->get_builder_content($settings['oxi_addons_f_templates'], true);
                            }
                        } else {
                            $this->add_render_attribute('oxi-flipbox-frontend-wrapper', ['class' => [
                                            'oxi-flipbox-figure-wrapper', 'oxi-flipbox-horizontal-align-' . esc_attr($settings['frontend_align']), 'oxi-flipbox-vertical-align-' . esc_attr($settings['frontend_vertical_align']),],],);
                            ?>
                            <div class="oxi-flip-box-frontend-row">
                                    <div <?php echo $this->get_render_attribute_string('oxi-flipbox-frontend-wrapper'); ?>>
                                        <?php
                                        $content_align = 'full-width';
                                        foreach ($settings['oxi_addons_frontend_content'] as $index => $value) {
                                            $type = !empty($value['oxi_addons_f_content_type']) ? esc_attr($value['oxi_addons_f_content_type']) : '';
                                            if ($type === 'title' || $type === 'image' || $type === 'icon' || $type === 'divider') {
                                                $position = $value['oxi_addons_f_width'] == 'full-width' ? 'oxi-flipbox-content-full-width' : '';
                                                if (empty($position) && $content_align == 'full-width') {
                                                    $content_align = 'inline';
                                                    $bottom        = $value['oxi_addons_f_position'] == 'bottom' ? 'oxi-flipbox-content-inline-bottom' : '';
                                                    echo '<div class="oxi-flipbox-content-inline ' . $bottom . '">';
                                                } elseif (!empty($position) && $content_align == 'inline') {
                                                    $content_align = 'full-width';
                                                    echo '</div>';
                                                }
                                            }
                                            if ($type === 'title') {
                                                $title = wp_kses_post($value['oxi_addons_f_title']);
                                                $tag   = esc_attr($value['oxi_addons_f_title_tag']);
                                                echo '<' . $tag . ' class="oxi-flipbox-content-heading oxi-flipbox-content-auto ' . $position . '">' . $title . '</' . $tag . '>';
                                            } elseif ($type === 'description') {
                                                $desc = wp_kses_post($value['oxi_addons_f_description']);
                                                $tag  = esc_attr($value['oxi_addons_f_description_tag']);
                                                if ($content_align == 'inline') {
                                                    $content_align = 'full-width';
                                                    echo '</div>';
                                                }
                                                echo '<' . $tag . ' class="oxi-flipbox-content-cont oxi-flipbox-content-auto">' . $desc . '</' . $tag . '>';
                                            } elseif ($type === 'icon') {
                                                echo '<div class="oxi-flipbox-content-icon oxi-flipbox-content-auto ' . $position . '">
                                                        <div class="oxi-image-addons-icon">';
                                                Icons_Manager::render_icon($value['oxi_addons_f_icon'], ['aria-hidden' => 'true']);
                                                echo '        </div>
                                                </div>';
                                            } elseif ($type === 'image') {
                                                echo '<div class="oxi-flipbox-content-image oxi-flipbox-content-auto ' . $position . '">' . Group_Control_Image_Size::get_attachment_image_html($value, 'oxi_addons_f_image_thumbnail', 'oxi_addons_f_image') . '</div>';
                                            } elseif ($type === 'divider') {
                                                echo '<div class="oxi-flipbox-content-divider oxi-flipbox-content-auto ' . $position . '"><div class="oxi-flipbox-content-divider-span"></div> </div>';
                                            }
                                        }
                                        if ($content_align == 'inline') {
                                            $content_align = 'full-width';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                            </div>
                            <?php
                        }
                        ?>


                </div>
                <div class="oxi-flip-box-backend-container">

                        <?php
                        if ($settings['oxi_addons_b_type'] == 'template') {
                            if (!empty($settings['oxi_addons_b_templates'])) {
                                echo Plugin::$instance->frontend->get_builder_content($settings['oxi_addons_b_templates'], true);
                            }
                        } else {
                            $this->add_render_attribute('oxi-flipbox-backend-wrapper', ['class' => [
                                            'oxi-flipbox-figure-wrapper', 'oxi-flipbox-horizontal-align-' . esc_attr($settings['backend_align']), 'oxi-flipbox-vertical-align-' . esc_attr($settings['backend_vertical_align']),],],);
                            ?>
                            <div class="oxi-flip-box-backend-row">
                                    <div <?php echo $this->get_render_attribute_string('oxi-flipbox-backend-wrapper'); ?>>
                                        <?php
                                        $content_align = 'full-width';
                                        foreach ($settings['oxi_addons_backend_content'] as $index => $value) {
                                            $type = !empty($value['oxi_addons_b_content_type']) ? esc_attr($value['oxi_addons_b_content_type']) : '';

                                            if ($type === 'title' || $type === 'image' || $type === 'icon' || $type === 'button' || $type === 'divider') {
                                                $position = $value['oxi_addons_b_width'] == 'full-width' ? 'oxi-flipbox-content-full-width' : '';
                                                if (empty($position) && $content_align == 'full-width') {
                                                    $content_align = 'inline';
                                                    $bottom        = $value['oxi_addons_b_position'] == 'bottom' ? 'oxi-flipbox-content-inline-bottom' : '';
                                                    echo '<div class="oxi-flipbox-content-inline ' . $bottom . '">';
                                                } elseif (!empty($position) && $content_align == 'inline') {
                                                    $content_align = 'full-width';
                                                    echo '</div>';
                                                }
                                            }
                                            if ($type === 'title') {

                                                $title     = wp_kses_post($value['oxi_addons_b_title']);
                                                $tag       = esc_attr($value['oxi_addons_b_title_tag']);
                                                $animation = esc_attr($value['oxi_addons_b_title_animation']) . ' ' . esc_attr($value['oxi_addons_b_title_animation_delay']);
                                                echo '<' . $tag . ' class="oxi-flipbox-content-heading oxi-flipbox-content-auto ' . $position . ' ' . $animation . '">' . $title . '</' . $tag . '>';
                                            } elseif ($type === 'description') {
                                                $desc = wp_kses_post($value['oxi_addons_b_description']);
                                                $tag  = esc_attr($value['oxi_addons_b_description_tag']);
                                                if ($content_align == 'inline') {
                                                    $content_align = 'full-width';
                                                    echo '</div>';
                                                }
                                                $animation = esc_attr($value['oxi_addons_b_description_animation']) . ' ' . esc_attr($value['oxi_addons_b_description_animation_delay']);
                                                echo '<' . $tag . ' class="oxi-flipbox-content-cont oxi-flipbox-content-auto ' . $animation . '">' . $desc . '</' . $tag . '>';
                                            } elseif ($type === 'icon') {
                                                $icon_tag = 'div';
                                                $this->add_render_attribute('oxi-image-addons-icon', 'class', 'oxi-image-addons-icon');
                                                if ($value['oxi_addons_b_icon_link_type'] != '' && $parent_tag !== 'a') {
                                                    if ($value['oxi_addons_b_icon_link_type'] === 'link' && !empty($value['oxi_addons_b_icon_link']['url'])) {
                                                        $icon_tag = 'a';
                                                        $this->add_link_attributes('oxi-image-addons-icon', $value['oxi_addons_b_icon_link']);
                                                    } elseif ($value['oxi_addons_b_icon_link_type'] === 'lightbox' && !empty($value['oxi_addons_b_icon_lightbox']['url'])) {
                                                        $icon_tag = 'a';
                                                        $this->add_render_attribute('oxi-image-addons-icon', [
                                                                'href'                         => esc_url($value['oxi_addons_b_icon_lightbox']['url']),
                                                                'data-elementor-open-lightbox' => 'yes',
                                                        ]);
                                                    }
                                                }

                                                $animation = esc_attr($value['oxi_addons_b_icon_animation']) . ' ' . esc_attr($value['oxi_addons_b_icon_animation_delay']);
                                                echo '<div class="oxi-flipbox-content-icon oxi-flipbox-content-auto ' . $position . ' ' . $animation . '">
                                                            <' . $icon_tag . ' ' . $this->get_render_attribute_string('oxi-image-addons-icon') . '>';
                                                Icons_Manager::render_icon($value['oxi_addons_b_icon'], ['aria-hidden' => 'true']);
                                                echo '      </' . $icon_tag . '>
                                                      </div>';
                                            } elseif ($type === 'image') {

                                                $animation = esc_attr($value['oxi_addons_b_image_animation']) . ' ' . esc_attr($value['oxi_addons_b_image_animation_delay']);
                                                echo '<div class="oxi-flipbox-content-image oxi-flipbox-content-auto ' . $position . ' ' . $animation . '">' . Group_Control_Image_Size::get_attachment_image_html($value, 'oxi_addons_b_image_thumbnail', 'oxi_addons_b_image') . '</div>';
                                            } elseif ($type === 'button') {
                                                $title      = wp_kses_post($value['oxi_addons_b_button']);
                                                $animation  = esc_attr($value['oxi_addons_b_button_animation']) . ' ' . esc_attr($value['oxi_addons_b_button_animation_delay']);
                                                $button_tag = 'div';
                                                $this->add_render_attribute('oxi-flipbox-content-button-span',
                                                        ['class' => [
                                                                        'oxi-flipbox-content-button-span',
                                                                        'oxi-flipbox-button-alignment-' . esc_attr($value['oxi_addons_b_button_icon_alignment'])
                                                ]]);
                                                if ($value['oxi_addons_b_button_link_type'] != '' && $parent_tag !== 'a') {
                                                    if ($value['oxi_addons_b_button_link_type'] === 'link' && !empty($value['oxi_addons_b_button_link']['url'])) {
                                                        $button_tag = 'a';
                                                        $this->add_link_attributes('oxi-flipbox-content-button-span', $value['oxi_addons_b_button_link']);
                                                    } elseif ($value['oxi_addons_b_button_link_type'] === 'lightbox' && !empty($value['oxi_addons_b_button_lightbox']['url'])) {
                                                        $button_tag = 'a';
                                                        $this->add_render_attribute('oxi-flipbox-content-button-span', [
                                                                'href'                         => esc_url($value['oxi_addons_b_button_lightbox']['url']),
                                                                'data-elementor-open-lightbox' => 'yes',
                                                        ]);
                                                    }
                                                }

                                                echo '<div class="oxi-flipbox-content-button oxi-flipbox-content-auto ' . $position . ' ' . $animation . '">
                                                    <' . $button_tag . '  ' . $this->get_render_attribute_string('oxi-flipbox-content-button-span') . '>';
                                                Icons_Manager::render_icon($value['oxi_addons_b_button_icon'], ['aria-hidden' => 'true']);

                                                echo $title . '    </' . $button_tag . '>
                                                </div>';
                                            } elseif ($type === 'divider') {
                                                echo '<div class="oxi-flipbox-content-divider oxi-flipbox-content-auto ' . $position . '"><div class="oxi-flipbox-content-divider-span"></div> </div>';
                                            }
                                        }
                                        if ($content_align == 'inline') {
                                            $content_align = 'full-width';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                            </div>
                            <?php
                        }
                        ?>
                </div>

                </<?php echo $parent_tag; ?>>
        </div>
        <?php
    }

}
