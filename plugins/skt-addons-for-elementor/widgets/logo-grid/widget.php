<?php
/**
 * Logo grid widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Group_Control_Css_Filter;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Logo_Grid extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Logo Grid', 'skt-addons-elementor');
    }

	public function get_custom_help_url() {
		return '#';
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
        return 'skti skti-logo-grid';
    }

    public function get_keywords() {
        return ['logo', 'grid', 'brand', 'client'];
    }

	/**
     * Register widget content controls
     */
    protected function register_content_controls() {
		$this->__logo_content_controls();
		$this->__settings_content_controls();
	}

    protected function __logo_content_controls() {

        $this->start_controls_section(
            '_section_logo',
            [
                'label' => __( 'Logo Grid', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => __( 'Logo', 'skt-addons-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __( 'Website Url', 'skt-addons-elementor' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
				],
				'default' => [
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				]
            ]
        );

        $repeater->add_control(
            'name',
            [
                'label' => __( 'Brand Name', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Brand Name', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'logo_list',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default' => [
                    [
						'image' => [
							'url' => Utils::get_placeholder_image_src()
						],
						'link' => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
					],
                    [
						'image' => [
							'url' => Utils::get_placeholder_image_src()
						],
						'link' => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
					],
					[
						'image' => [
							'url' => Utils::get_placeholder_image_src()
						],
						'link' => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
					],
					[
						'image' => [
							'url' => Utils::get_placeholder_image_src()
						],
						'link' => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
					],
					[
						'image' => [
							'url' => Utils::get_placeholder_image_src()
						],
						'link' => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
					],
					[
						'image' => [
							'url' => Utils::get_placeholder_image_src()
						],
						'link' => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
					],
					[
						'image' => [
							'url' => Utils::get_placeholder_image_src()
						],
						'link' => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
					],
					[
						'image' => [
							'url' => Utils::get_placeholder_image_src()
						],
						'link' => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
					],
                ]
            ]
        );

        $this->end_controls_section();
	}

    protected function __settings_content_controls() {

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Settings', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'large',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __( 'Grid Layout', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'box' => __( 'Box', 'skt-addons-elementor' ),
                    'border' => __( 'Border', 'skt-addons-elementor' ),
                    'tictactoe' => __( 'Tic Tac Toe', 'skt-addons-elementor' ),
                ],
                'default' => 'box',
                'prefix_class' => 'skt-logo-grid--',
                'style_transfer' => true,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __( 'Columns', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    2 => __( '2 Columns', 'skt-addons-elementor' ),
                    3 => __( '3 Columns', 'skt-addons-elementor' ),
                    4 => __( '4 Columns', 'skt-addons-elementor' ),
                    5 => __( '5 Columns', 'skt-addons-elementor' ),
                    6 => __( '6 Columns', 'skt-addons-elementor' ),
                ],
                'desktop_default' => 4,
                'tablet_default' => 2,
                'mobile_default' => 2,
                'prefix_class' => 'skt-logo-grid--col-%s',
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {

        $this->start_controls_section(
            '_section_style_grid',
            [
                'label' => __( 'Grid', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-logo-grid-figure' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => __( 'Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'max' => 500,
                        'min' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-logo-grid-item' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'grid_border_type',
            [
                'label' => __( 'Border Type', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __( 'None', 'skt-addons-elementor' ),
                    'solid' => __( 'Solid', 'skt-addons-elementor' ),
                    'double' => __( 'Double', 'skt-addons-elementor' ),
                    'dotted' => __( 'Dotted', 'skt-addons-elementor' ),
                    'dashed' => __( 'Dashed', 'skt-addons-elementor' ),
                    'groove' => __( 'Groove', 'skt-addons-elementor' ),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .skt-logo-grid-item' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_border_width',
            [
                'label' => __( 'Border Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border .skt-logo-grid-item' => 'border-right-width: {{grid_border_width.SIZE}}{{UNIT}}; border-bottom-width: {{grid_border_width.SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border .skt-logo-grid-item' => 'border-right-width: {{grid_border_width_tablet.SIZE}}{{UNIT}}; border-bottom-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border .skt-logo-grid-item' => 'border-right-width: {{grid_border_width_mobile.SIZE}}{{UNIT}}; border-bottom-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',

                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-2 .skt-logo-grid-item:nth-child(2n+1)' => 'border-left-width: {{grid_border_width.SIZE}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-3 .skt-logo-grid-item:nth-child(3n+1)' => 'border-left-width: {{grid_border_width.SIZE}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-4 .skt-logo-grid-item:nth-child(4n+1)' => 'border-left-width: {{grid_border_width.SIZE}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-5 .skt-logo-grid-item:nth-child(5n+1)' => 'border-left-width: {{grid_border_width.SIZE}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-6 .skt-logo-grid-item:nth-child(6n+1)' => 'border-left-width: {{grid_border_width.SIZE}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-2 .skt-logo-grid-item:nth-child(-n+2)' => 'border-top-width: {{grid_border_width.SIZE}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-3 .skt-logo-grid-item:nth-child(-n+3)' => 'border-top-width: {{grid_border_width.SIZE}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-4 .skt-logo-grid-item:nth-child(-n+4)' => 'border-top-width: {{grid_border_width.SIZE}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-5 .skt-logo-grid-item:nth-child(-n+5)' => 'border-top-width: {{grid_border_width.SIZE}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-6 .skt-logo-grid-item:nth-child(-n+6)' => 'border-top-width: {{grid_border_width.SIZE}}{{UNIT}};',

                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet2 .skt-logo-grid-item:nth-child(2n+1)' => 'border-left-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet3 .skt-logo-grid-item:nth-child(3n+1)' => 'border-left-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet4 .skt-logo-grid-item:nth-child(4n+1)' => 'border-left-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet5 .skt-logo-grid-item:nth-child(5n+1)' => 'border-left-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet6 .skt-logo-grid-item:nth-child(6n+1)' => 'border-left-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet2 .skt-logo-grid-item:nth-child(-n+2)' => 'border-top-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet3 .skt-logo-grid-item:nth-child(-n+3)' => 'border-top-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet4 .skt-logo-grid-item:nth-child(-n+4)' => 'border-top-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet5 .skt-logo-grid-item:nth-child(-n+5)' => 'border-top-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet6 .skt-logo-grid-item:nth-child(-n+6)' => 'border-top-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',

                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile2 .skt-logo-grid-item:nth-child(2n+1)' => 'border-left-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile3 .skt-logo-grid-item:nth-child(3n+1)' => 'border-left-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile4 .skt-logo-grid-item:nth-child(4n+1)' => 'border-left-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile5 .skt-logo-grid-item:nth-child(5n+1)' => 'border-left-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile6 .skt-logo-grid-item:nth-child(6n+1)' => 'border-left-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile2 .skt-logo-grid-item:nth-child(-n+2)' => 'border-top-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile3 .skt-logo-grid-item:nth-child(-n+3)' => 'border-top-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile4 .skt-logo-grid-item:nth-child(-n+4)' => 'border-top-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile5 .skt-logo-grid-item:nth-child(-n+5)' => 'border-top-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile6 .skt-logo-grid-item:nth-child(-n+6)' => 'border-top-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',

                    '{{WRAPPER}}.skt-logo-grid--tictactoe .skt-logo-grid-item' => 'border-top-width: {{SIZE}}{{UNIT}}; border-right-width: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}}.skt-logo-grid--box .skt-logo-grid-item' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'grid_border_type!' => 'none',
                ]
            ]
        );

        $this->add_control(
            'grid_border_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-logo-grid-item' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'grid_border_type!' => 'none',
                ]
            ]
        );

        $this->add_control(
            'grid_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-logo-grid-figure' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}.skt-logo-grid--border .skt-logo-grid-wrapper, {{WRAPPER}}.skt-logo-grid--box .skt-logo-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.skt-logo-grid--border .skt-logo-grid-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}};',
                    '{{WRAPPER}}.skt-logo-grid--border .skt-logo-grid-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',

                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-2 .skt-logo-grid-item:nth-child(2)' => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-2 .skt-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-3 .skt-logo-grid-item:nth-child(3)' => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-3 .skt-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-4 .skt-logo-grid-item:nth-child(4)' => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-4 .skt-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-5 .skt-logo-grid-item:nth-child(5)' => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-5 .skt-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-6 .skt-logo-grid-item:nth-child(6)' => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col-6 .skt-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',

                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet2 .skt-logo-grid-item:nth-child(2)' => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet2 .skt-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet3 .skt-logo-grid-item:nth-child(3)' => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet3 .skt-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet4 .skt-logo-grid-item:nth-child(4)' => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet4 .skt-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet5 .skt-logo-grid-item:nth-child(5)' => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet5 .skt-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet6 .skt-logo-grid-item:nth-child(6)' => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--tablet6 .skt-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',

                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile2 .skt-logo-grid-item:nth-child(2)' => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile2 .skt-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile3 .skt-logo-grid-item:nth-child(3)' => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile3 .skt-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile4 .skt-logo-grid-item:nth-child(4)' => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile4 .skt-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile5 .skt-logo-grid-item:nth-child(5)' => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile5 .skt-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile6 .skt-logo-grid-item:nth-child(6)' => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--border.skt-logo-grid--col--mobile6 .skt-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',

                    // Tictactoe
                    '{{WRAPPER}}.skt-logo-grid--tictactoe .skt-logo-grid-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.skt-logo-grid--tictactoe .skt-logo-grid-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}};',
                    '{{WRAPPER}}.skt-logo-grid--tictactoe .skt-logo-grid-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',

                    '(desktop+){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col-2 .skt-logo-grid-item:nth-child(2)' => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col-2 .skt-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col-3 .skt-logo-grid-item:nth-child(3)' => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col-3 .skt-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col-4 .skt-logo-grid-item:nth-child(4)' => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col-4 .skt-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col-5 .skt-logo-grid-item:nth-child(5)' => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col-5 .skt-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col-6 .skt-logo-grid-item:nth-child(6)' => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col-6 .skt-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',

                    '(tablet){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--tablet2 .skt-logo-grid-item:nth-child(2)' => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--tablet2 .skt-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--tablet3 .skt-logo-grid-item:nth-child(3)' => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--tablet3 .skt-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--tablet4 .skt-logo-grid-item:nth-child(4)' => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--tablet4 .skt-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--tablet5 .skt-logo-grid-item:nth-child(5)' => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--tablet5 .skt-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--tablet6 .skt-logo-grid-item:nth-child(6)' => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--tablet6 .skt-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',

                    '(mobile){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--mobile2 .skt-logo-grid-item:nth-child(2)' => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--mobile2 .skt-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--mobile3 .skt-logo-grid-item:nth-child(3)' => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--mobile3 .skt-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--mobile4 .skt-logo-grid-item:nth-child(4)' => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--mobile4 .skt-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--mobile5 .skt-logo-grid-item:nth-child(5)' => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--mobile5 .skt-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--mobile6 .skt-logo-grid-item:nth-child(6)' => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.skt-logo-grid--tictactoe.skt-logo-grid--col--mobile6 .skt-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'grid_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}}.skt-logo-grid--tictactoe .skt-logo-grid-wrapper, {{WRAPPER}}.skt-logo-grid--border .skt-logo-grid-wrapper, {{WRAPPER}}.skt-logo-grid--box .skt-logo-grid-item'
            ]
        );


        $this->start_controls_tabs(
            '_tabs_image_effects',
            [
                'separator' => 'before'
            ]
        );

        $this->start_controls_tab(
            '_tab_image_effects_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'image_opacity',
            [
                'label' => __( 'Opacity', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-logo-grid-figure img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters',
                'selector' => '{{WRAPPER}} .skt-logo-grid-figure img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'image_opacity_hover',
            [
                'label' => __( 'Opacity', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-logo-grid-figure:hover img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters_hover',
                'selector' => '{{WRAPPER}} .skt-logo-grid-figure:hover img',
            ]
        );

        $this->add_control(
            'image_bg_hover_transition',
            [
                'label' => __( 'Transition Duration', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-logo-grid-figure:hover img' => 'transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __( 'Hover Animation', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
                'label_block' => true,
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty($settings['logo_list'] ) ) {
            return;
        }
        ?>

        <div class="skt-logo-grid-wrapper">
            <?php
            foreach ( $settings['logo_list'] as $index => $item ) :
                $image = wp_get_attachment_image_url( $item['image']['id'], $settings['thumbnail_size'] );
                $repeater_key = 'grid_item' . $index;
                $tag = 'div';
                $this->add_render_attribute( $repeater_key, 'class', 'skt-logo-grid-item' );

                if ( $item['link']['url'] ) {
                    $tag = 'a';
					$this->add_render_attribute( $repeater_key, 'class', 'skt-logo-grid-link' );
					$this->add_link_attributes( $repeater_key, $item['link'] );
                }
                ?>
                <<?php echo wp_kses_post($tag); ?> <?php $this->print_render_attribute_string( $repeater_key ); ?>>
                    <figure class="skt-logo-grid-figure">
                    <?php if ( $image ) :
                            echo wp_kses_post(wp_get_attachment_image(
                                $item['image']['id'],
                                $settings['thumbnail_size'],
                                false,
                                [
                                    'class' => 'skt-logo-grid-img elementor-animation-' . esc_attr( $settings['hover_animation'] )
                                ]
                            ));
                        else :
                            printf( '<img class="skt-logo-grid-img elementor-animation-%s" src="%s" alt="%s">',
                                esc_attr( $settings['hover_animation'] ),
                                Utils::get_placeholder_image_src(),
                                esc_attr( $item['name'] )
                                );
                        endif; ?>
                    </figure>
                </<?php echo wp_kses_post($tag); ?>>
            <?php endforeach; ?>
        </div>

        <?php
    }
}