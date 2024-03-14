<?php
/**
 * Hotspots widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Hotspots extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Hotspots', 'skt-addons-elementor' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'skti skti-hot-spot';
    }

    public function get_keywords() {
        return [ 'hot', 'spots', 'point', 'product' ];
    }

	/**
     * Register widget content controls
     */
    protected function register_content_controls() {
		$this->__image_content_controls();
		$this->__spots_content_controls();
		$this->__options_content_controls();
	}

    protected function __image_content_controls() {

        $this->start_controls_section(
            '_section_image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'image',
            [
                'show_label' => false,
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'label' => __( 'Image Size', 'skt-addons-elementor' ),
                'default' => 'large',
            ]
        );

        $this->end_controls_section();
	}

    protected function __spots_content_controls() {

        $this->start_controls_section(
            '_section_spots',
            [
                'label' => __( 'Spots', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs( '_tabs_spots' );

        $repeater->start_controls_tab(
            '_tab_spot',
            [
                'label' => __( 'Spot', 'skt-addons-elementor' )
            ]
        );

        $repeater->add_control(
            'type',
            [
                'label' => __( 'Type', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'text' => [
                        'title' => __( 'Text', 'skt-addons-elementor' ),
                        'icon' => 'eicon-text-area',
                    ],
                    'icon' => [
                        'title' => __( 'Icon', 'skt-addons-elementor' ),
                        'icon' => 'eicon-star',
                    ],
                    'image' => [
                        'title' => __( 'Image', 'skt-addons-elementor' ),
                        'icon' => 'eicon-image',
                    ],
                ],
                'default' => 'icon',
                'toggle' => false,
            ]
        );

        $repeater->add_control(
            'text',
            [
                'default' => '+',
                'type' => Controls_Manager::TEXT,
                'label' => __( 'Text', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'type'	=> 'text'
                ]
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'skt-addons-elementor' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'library' => 'solid',
                    'value' => 'fas fa-plus',
                ],
                'condition' => [
                    'type' => 'icon'
                ],
            ]
        );

        $repeater->add_control(
            'image',
            [
                'show_label' => false,
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'type' => 'image'
                ],
            ]
        );

        $repeater->add_responsive_control(
            'x_pos',
            [
                'label' => __( 'X Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'separator' => 'before',
                'size_units' => ['%'],
                'desktop_default' => [
                    'size' => 50,
                    'unit' => '%'
                ],
                'tablet_default' => [
                    'unit' => '%'
                ],
                'mobile_default' => [
                    'unit' => '%'
                ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => .1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $repeater->add_responsive_control(
            'y_pos',
            [
                'label' => __( 'Y Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'desktop_default' => [
                    'size' => 45,
                    'unit' => '%'
                ],
                'tablet_default' => [
                    'unit' => '%'
                ],
                'mobile_default' => [
                    'unit' => '%'
                ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => .1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $repeater->add_control(
            'css_id',
            [
                'label' => __( 'CSS ID', 'skt-addons-elementor' ),
                'title' => __( 'Add your custom id. e.g: my-custom-id', 'skt-addons-elementor' ),
                'separator' => 'before',
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true
                ],
            ]
        );

        $repeater->add_control(
            'css_classes',
            [
                'label' => __( 'CSS Classes', 'skt-addons-elementor' ),
                'title' => __( 'Add your custom class WITHOUT the dot. e.g: my-custom-class', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'prefix_class' => '',
                'dynamic' => [
                    'active' => true
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            '_tab_tooltip',
            [
                'label' => __( 'Tooltip', 'skt-addons-elementor' )
            ]
        );

        $repeater->add_control(
            'content',
            [
                'label' => __( 'Content', 'skt-addons-elementor' ),
                'separator' => 'before',
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'Hotspot tooltip content goes here', 'skt-addons-elementor' ),
            ]
        );

        $repeater->add_control(
            'position',
            [
                'label' => __( 'Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'separator' => 'before',
                'default' => '',
                'options' => [
                    '' => __( 'Default', 'skt-addons-elementor' ),
                    'left' => __( 'Left', 'skt-addons-elementor' ),
                    'top' => __( 'Top', 'skt-addons-elementor' ),
                    'right' => __( 'Right', 'skt-addons-elementor' ),
                    'bottom' => __( 'Bottom', 'skt-addons-elementor' ),
                    'top-left' => __( 'Top Left', 'skt-addons-elementor' ),
                    'top-right' => __( 'Top Right', 'skt-addons-elementor' ),
                    'bottom-left' => __( 'Bottom Left', 'skt-addons-elementor' ),
                    'bottom-right' => __( 'Bottom Right', 'skt-addons-elementor' ),
                ]
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'spots',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'type' => 'icon',
                        'icon' => [
                            'library' => 'solid',
                            'value' => 'fas fa-plus',
                        ],
                        'x_pos' => [
                            'size' => 47,
                            'unit' => '%'
                        ],
                        'y_pos' => [
                            'size' => 43,
                            'unit' => '%'
                        ],
                        'content' => 'Tooltip content goes here'
                    ]
                ]
            ]
        );

        $this->end_controls_section();
	}

    protected function __options_content_controls() {

        $this->start_controls_section(
            '_section_options',
            [
                'label' => __( 'Options', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'tooltip_position',
            [
                'label' => __( 'Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'frontend_available' => true,
                'default' => 'top',
                'options' => [
                    'left' => __( 'Left', 'skt-addons-elementor' ),
                    'top' => __( 'Top', 'skt-addons-elementor' ),
                    'right' => __( 'Right', 'skt-addons-elementor' ),
                    'bottom' => __( 'Bottom', 'skt-addons-elementor' ),
                    'top-left' => __( 'Top Left', 'skt-addons-elementor' ),
                    'top-right' => __( 'Top Right', 'skt-addons-elementor' ),
                    'bottom-left' => __( 'Bottom Left', 'skt-addons-elementor' ),
                    'bottom-right' => __( 'Bottom Right', 'skt-addons-elementor' ),
                ],
                'render_type' => 'ui'
            ]
        );

        $this->add_control(
            'tooltip_speed',
            [
                'label' => __( 'Speed', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 10,
                'max' => 10000,
                'title' => __( 'Speed in milliseconds (default 400)', 'skt-addons-elementor' ),
                'frontend_available' => true,
                'placeholder' => 400,
                'render_type' => 'ui'
            ]
        );

        $this->add_control(
            'tooltip_delay',
            [
                'label' => __( 'Delay', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 10,
                'max' => 10000,
                'title' => __( 'Delay in milliseconds (default 200)', 'skt-addons-elementor' ),
                'frontend_available' => true,
                'placeholder' => 200,
                'render_type' => 'ui'
            ]
        );

        $this->add_control(
            'tooltip_hide_delay',
            [
                'label' => __( 'Hide Delay', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'max' => 100000,
                'title' => __( 'Hide delay in milliseconds (default 0)', 'skt-addons-elementor' ),
                'frontend_available' => true,
                'placeholder' => 0,
                'render_type' => 'ui'
            ]
        );

        $this->add_control(
            'tooltip_hide_arrow',
            [
                'label' => __( 'Hide Arrow', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'frontend_available' => true,
                'render_type' => 'ui',
            ]
        );

        $this->add_control(
            'tooltip_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
                'description' => __( 'Make sure to enable this option when you have a link in tooltip content.', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'frontend_available' => true,
                'render_type' => 'ui',
            ]
        );

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__image_style_controls();
		$this->__spots_style_controls();
		$this->__tooltip_style_controls();
	}

    protected function __image_style_controls() {

        $this->start_controls_section(
            '_section_style_image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __( 'Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'desktop_default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__figure' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__figure' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .skt-hotspots__figure img',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .skt-hotspots__figure img',
            ]
        );

        $this->end_controls_section();
	}

    protected function __spots_style_controls() {

        $this->start_controls_section(
            '_section_style_spots',
            [
                'label' => __( 'Spots', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'spot_width',
            [
                'label' => __( 'Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 500,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'spot_height',
            [
                'label' => __( 'Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 500,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__item' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'spot_font_size',
            [
                'label' => __( 'Font / Icon Size', 'skt-addons-elementor' ),
                'description' => __( 'Applicable for icon and text spot type', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__item' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'spot_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'spot_border',
                'selector' => '{{WRAPPER}} .skt-hotspots__item-inner'
            ]
        );

        $this->add_responsive_control(
            'spot_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( '_tabs_spot' );

        $this->start_controls_tab(
            '_tab_spot_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'spot_text_color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__item' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-hotspots__item svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'spot_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__item' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'spot_box_shadow',
                'selector' => '{{WRAPPER}} .skt-hotspots__item'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_spot_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'spot_hover_text_color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__item:hover, {{WRAPPER}} .skt-hotspots__item:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-hotspots__item:hover svg, {{WRAPPER}} .skt-hotspots__item:focus svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'spot_hover_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__item:hover, {{WRAPPER}} .skt-hotspots__item:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'spot_hover_border_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-hotspots__item:hover, {{WRAPPER}} .skt-hotspots__item:focus' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'spot_border_border!' => ''
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'spot_hover_box_shadow',
                'selector' => '{{WRAPPER}} .skt-hotspots__item:hover, {{WRAPPER}} .skt-hotspots__item:focus'
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __tooltip_style_controls() {

        $this->start_controls_section(
            '_section_style_tooltip',
            [
                'label' => __( 'Tooltip', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tooltip_width',
            [
                'label' => __( 'Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 500,
                    ],
                ],
                'frontend_available' => true,
                'render_type' => 'ui'
            ]
        );

        $this->add_responsive_control(
            'tooltip_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '.skt-hotspots--{{ID}}.tipso_bubble' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '.skt-hotspots--{{ID}}.tipso_bubble' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'frontend_available' => true,
                'default' => '#fff',
                'render_type' => 'ui'
            ]
        );

        $this->add_control(
            'tooltip_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'frontend_available' => true,
                'default' => '#562dd4',
                'render_type' => 'ui'
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tooltip_typograhpy',
                'selector' => '.tipso_bubble.skt-hotspots--{{ID}} .tipso_content'
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tooltip_box_shadow',
                'selector' => '.tipso_bubble.skt-hotspots--{{ID}}'
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="skt-hotspots__inner">
            <figure class="skt-hotspots__figure">
                <?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' )); ?>
            </figure>

            <?php
            if ( ! empty( $settings['spots'] ) ) :
                foreach ( $settings['spots'] as $index => $spot ) :
                    $tooltip_id = $this->get_id() . $spot['_id'];

                    $this->add_render_attribute( 'spot-' . $index, [
                        'href' => '#',
                        'data-index' => $index,
                        'data-target' => $tooltip_id,
                        'class' => 'skt-hotspots__item elementor-repeater-item-' . $spot['_id'],
                        'data-settings' => json_encode( [
                            'position' => $spot['position'],
                        ] )
                    ] );

                    if ( ! empty( $spot['css_classes'] ) ) {
                        $this->add_render_attribute( 'spot-' . $index, 'class', esc_attr( $spot['css_classes'] ) );
                    }

                    if ( ! empty( $spot['css_id'] ) ) {
                        $this->add_render_attribute( 'spot-' . $index, 'id', esc_attr( $spot['css_id'] ) );
                    }
                    ?>
                    <div role="tooltip" id="skt-<?php echo esc_attr($tooltip_id); ?>" class="screen-reader-text"><?php echo wp_kses_post($this->parse_text_editor( $spot['content'] )); ?></div>
                    <a <?php echo wp_kses_post($this->print_render_attribute_string( 'spot-' . $index )); ?>>
                        <span class="skt-hotspots__item-inner">
                            <?php
                            if ( $spot['type'] === 'icon' ) {
                                Icons_Manager::render_icon( $spot['icon'], ['aria-hidden' => true ] );
                            } elseif ( $spot['type'] === 'image') {
                                echo wp_kses_post(wp_get_attachment_image( $spot['image']['id'] ));
                            } else {
                                echo esc_html( $spot['text'] );
                            }
                            ?>
                        </span>
                    </a>
                <?php endforeach;
            endif;
            ?>
        </div>
        <?php
    }
}