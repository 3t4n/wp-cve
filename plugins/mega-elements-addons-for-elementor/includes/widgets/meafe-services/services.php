<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Repeater;

class MEAFE_Services extends Widget_Base
{

    public function get_name() {
        return 'meafe-services';
    }

    public function get_title() {
        return esc_html__( 'Info Box Grid', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-service';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-services'];
    }

    public function get_grid_classes( $settings, $columns_field = 'bscgs_service_per_line' ) {
        
        $grid_classes = ' meafe-grid-desktop-';
        $grid_classes .= $settings[$columns_field];
        $grid_classes .= ' meafe-grid-tablet-';
        $grid_classes .= $settings[$columns_field . '_tablet'];
        $grid_classes .= ' meafe-grid-mobile-';
        $grid_classes .= $settings[$columns_field . '_mobile'];

        return apply_filters( 'meafe_grid_classes', esc_attr($grid_classes), $settings, $columns_field );
    }

    protected function register_controls()
    {   
        /**
         * Services General Settings
        */
        $this->start_controls_section( 
            'meafe_services_content_general_settings', 
            [
                'label' => esc_html__( 'General Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_responsive_control( 
            'bscgs_service_per_line', 
            [
                'label'              => esc_html__( 'Columns per row', 'mega-elements-addons-for-elementor' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => '3',
                'tablet_default'     => '2',
                'mobile_default'     => '1',
                'options'            => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'frontend_available' => true,
            ] 
        );

        $this->add_control( 
            'bscgs_service_icon_type',
            [

                'label'   => esc_html__( 'Icon Type', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'none'       => esc_html__( 'None', 'mega-elements-addons-for-elementor' ),
                    'icon'       => esc_html__( 'Icon', 'mega-elements-addons-for-elementor' ),
                    'icon_image' => esc_html__( 'Icon Image', 'mega-elements-addons-for-elementor' ),
                    'image'      => esc_html__( 'Image', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $service_main_repeater = new Repeater();

        $service_main_repeater->add_control(
            'bscgs_service_title',
            [
                'label'       => esc_html__( 'Service Title', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'My service title', 'mega-elements-addons-for-elementor' ),
                'dynamic'     => [
                    'active'  => true,
                ],
            ]
        );

        $service_main_repeater->add_control(
            'bscgs_service_selected_icon',
            [
                'label'            => esc_html__( 'Service Icon', 'mega-elements-addons-for-elementor' ),
                'type'             => Controls_Manager::ICONS,
                'label_block'      => true,
                // 'default'          => '',
                'fa4compatibility' => 'bscgs_service_icon',
            ]
        );

        $service_main_repeater->add_control(
            'bscgs_service_link',
            [
                'label'       => esc_html__( 'Service URL', 'mega-elements-addons-for-elementor' ),
                'description' => esc_html__( 'The link for the page describing the service.', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::URL,
                'label_block' => true,
                'default'     => [
                'url'         => '',
                    'is_external' => 'true',
                ],
                'placeholder' => esc_html__( 'https://', 'mega-elements-addons-for-elementor' ),
                    'dynamic'     => [
                    'active' => true,
                ],
            ]
        );
        
        $service_main_repeater->add_control(
            'bscgs_service_excerpt',
            [
                'label'       => esc_html__( 'Service description', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Service description goes here', 'mega-elements-addons-for-elementor' ),
                'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $service_main_repeater->add_control(
            'bscgs_service_read_more',
            [
                'label'       => esc_html__( 'Read More Button', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Read More', 'mega-elements-addons-for-elementor' ),
                'label_block' => true,
            ]
        );
        
        $service_main_repeater->add_control(
            'bscgs_service_selected_read_more_icon',
            [
                'label'            => esc_html__( 'Read More Button Icon', 'mega-elements-addons-for-elementor' ),
                'type'             => Controls_Manager::ICONS,
                'label_block'      => true,
                // 'default'          => '',
                'condition'        => [
                    'bscgs_service_read_more!' => '',
                ],
                'fa4compatibility' => 'bscgs_service_read_more_icon',
            ]
        );

        $this->add_control( 
            'bscgs_services', 
            [
                'type'        => Controls_Manager::REPEATER,
                'default'     => [ 
                    [
                        'bscgs_service_title'     => esc_html__( 'Business Coaching', 'mega-elements-addons-for-elementor' ),
                        'bscgs_service_selected_icon'     => [
                            'value'     => 'far fa-bell',
                            'library'   => 'fa-regular',
                        ],
                        'bscgs_service_excerpt'       => 'Curabitur ligula sapien, tincidunt non, euismod vitae, posuere imperdiet, leo. Donec venenatis vulputate lorem. In hac habitasse aliquam.',
                        'bscgs_service_read_more'       => 'Read More',
                    ], 
                    [
                        'bscgs_service_title'   => esc_html__( '1:1 Consultation', 'mega-elements-addons-for-elementor' ),
                        'bscgs_service_selected_icon'   => [
                            'value'     => 'fas fa-laptop',
                            'library'   => 'fa-solid',
                        ],
                        'bscgs_service_excerpt' => 'Suspendisse nisl elit, rhoncus eget, elementum ac, condimentum eget, diam. Phasellus nec sem in justo pellentesque facilisis platea dictumst.',
                        'bscgs_service_read_more'       => 'Read More',
                    ], 
                    [
                        'bscgs_service_title'   => esc_html__( 'Career Analysis', 'mega-elements-addons-for-elementor' ),
                        'bscgs_service_selected_icon'   => [
                            'value'     => 'fas fa-toggle-off',
                            'library'   => 'fa-solid',
                        ],
                        'bscgs_service_excerpt' => 'Nunc egestas, augue at pellentesque laoreet, felis eros vehicula leo, at malesuada velit leo quis pede. Etiam ut purus mattis mauris sodales.',
                        'bscgs_service_read_more'       => 'Read More',
                    ] 
                ],
                'fields'      => $service_main_repeater->get_controls(),
                'title_field' => '{{{ bscgs_service_title }}}',
                'condition'        => [
                    'bscgs_service_icon_type' => 'icon',
                ],
            ] 
        );

        $service_icon_image_repeater = new Repeater();

        $service_icon_image_repeater->add_control(
            'bscgs_service_title',
            [
                'label'       => esc_html__( 'Service Title', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'My service title', 'mega-elements-addons-for-elementor' ),
                'dynamic'     => [
                    'active'  => true,
                ],
            ]
        );

        $service_icon_image_repeater->add_control(
            'bscgs_service_icon_image',
            [
                'label'       => esc_html__( 'Service Image', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );
        
        $service_icon_image_repeater->add_control(
            'bscgs_service_link',
            [
                'label'       => esc_html__( 'Service URL', 'mega-elements-addons-for-elementor' ),
                'description' => esc_html__( 'The link for the page describing the service.', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::URL,
                'label_block' => true,
                'default'     => [
                'url'         => '',
                    'is_external' => 'true',
                ],
                'placeholder' => esc_html__( 'https://', 'mega-elements-addons-for-elementor' ),
                    'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $service_icon_image_repeater->add_control(
            'bscgs_service_excerpt',
            [
                'label'       => esc_html__( 'Service description', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Service description goes here', 'mega-elements-addons-for-elementor' ),
                'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );
        
        $service_icon_image_repeater->add_control(
            'bscgs_service_read_more',
            [
                'label'       => esc_html__( 'Read More Button', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Read More', 'mega-elements-addons-for-elementor' ),
                'label_block' => true,
            ]
        );

        $service_icon_image_repeater->add_control(
            'bscgs_service_selected_read_more_icon',
            [
                'label'            => esc_html__( 'Read More Button Icon', 'mega-elements-addons-for-elementor' ),
                'type'             => Controls_Manager::ICONS,
                'label_block'      => true,
                // 'default'          => '',
                'condition'        => [
                    'bscgs_service_read_more!' => '',
                ],
                'fa4compatibility' => 'bscgs_service_read_more_icon',
            ]
        );

        $this->add_control( 
            'bscgs_icon_image_services', 
            [
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $service_icon_image_repeater->get_controls(),
                'title_field' => '{{{ bscgs_service_title }}}',
                'condition'        => [
                    'bscgs_service_icon_type' => 'icon_image',
                ],
            ] 
        );

        $service_image_repeater = new Repeater();

        $service_image_repeater->add_control(
            'bscgs_service_title',
            [
                'label'       => esc_html__( 'Service Title', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'My service title', 'mega-elements-addons-for-elementor' ),
                'dynamic'     => [
                    'active'  => true,
                ],
            ]
        );

        $service_image_repeater->add_control(
            'bscgs_service_icon_image',
            [
                'label'       => esc_html__( 'Service Image', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $service_image_repeater->add_control(
            'bscgs_service_link',
            [
                'label'       => esc_html__( 'Service URL', 'mega-elements-addons-for-elementor' ),
                'description' => esc_html__( 'The link for the page describing the service.', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::URL,
                'label_block' => true,
                'default'     => [
                'url'         => '',
                    'is_external' => 'true',
                ],
                'placeholder' => esc_html__( 'https://', 'mega-elements-addons-for-elementor' ),
                    'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $service_image_repeater->add_control(
            'bscgs_service_excerpt',
            [
                'label'       => esc_html__( 'Service description', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Service description goes here', 'mega-elements-addons-for-elementor' ),
                'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $service_image_repeater->add_control(
            'bscgs_service_read_more',
            [
                'label'       => esc_html__( 'Read More Button', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Read More', 'mega-elements-addons-for-elementor' ),
                'label_block' => true,
            ]
        );
        
        $service_image_repeater->add_control(
            'bscgs_service_selected_read_more_icon',
            [
                'label'            => esc_html__( 'Read More Button Icon', 'mega-elements-addons-for-elementor' ),
                'type'             => Controls_Manager::ICONS,
                'label_block'      => true,
                // 'default'          => '',
                'condition'        => [
                    'bscgs_service_read_more!' => '',
                ],
                'fa4compatibility' => 'bscgs_service_read_more_icon',
            ]
        );

        $this->add_control( 
            'bscgs_image_services', 
            [
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $service_image_repeater->get_controls(),
                'title_field' => '{{{ bscgs_service_title }}}',
                'condition'        => [
                    'bscgs_service_icon_type' => 'image',
                ],
            ] 
        );
        
        $this->add_control(
            'bscgs_service_target_blank_all',
            [
                'label'     => esc_html__( 'Open in new Tab', 'mega-elements-addons-for-elementor' ),
                'description'     => esc_html__( 'Enable to show link all readmore to new widow.', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'no',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bscgs_service_alignment',
            [
                'label'         => esc_html__( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title'     => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-left'
                    ],
                    'center'    => [
                        'title'     => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-center'
                    ],
                    'right'     => [
                        'title'     => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-right'
                    ],
                ],
                'prefix_class'  => 'meafe-service-align-',
            ]
        );

        $this->add_control(
            'bscgs_service_alignment_title',
            [
                'label'         => esc_html__( 'Title Alignment', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title'     => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-left'
                    ],
                    'center'    => [
                        'title'     => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-center'
                    ],
                    'right'     => [
                        'title'     => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-right'
                    ],
                ],
                'prefix_class'  => 'meafe-service-title-align-',
            ]
        );

        $this->add_control(
            'bscgs_service_alignment_content',
            [
                'label'         => esc_html__( 'Content Alignment', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title'     => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-left'
                    ],
                    'center'    => [
                        'title'     => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-center'
                    ],
                    'right'     => [
                        'title'     => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-right'
                    ],
                ],
                'prefix_class'  => 'meafe-service-content-align-',
            ]
        );
        
        $this->add_control(
            'bscgs_service_alignment_button',
            [
                'label'         => esc_html__( 'Button Alignment', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title'     => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-left'
                    ],
                    'center'    => [
                        'title'     => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-center'
                    ],
                    'right'     => [
                        'title'     => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-right'
                    ],
                ],
                'prefix_class'  => 'meafe-service-button-align-',
            ]
        );

        $this->end_controls_section();

        /**
         * Services Title Style
        */
        $this->start_controls_section( 
            'meafe_services_style_title_style', 
            [
                'label' => esc_html__( 'Title Style', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ] 
        );

        $this->add_control( 
            'bssts_service_title_tag', 
            [
                'label'   => esc_html__( 'Title HTML Tag', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                'h1'  => esc_html__( 'H1', 'mega-elements-addons-for-elementor' ),
                'h2'  => esc_html__( 'H2', 'mega-elements-addons-for-elementor' ),
                'h3'  => esc_html__( 'H3', 'mega-elements-addons-for-elementor' ),
                'h4'  => esc_html__( 'H4', 'mega-elements-addons-for-elementor' ),
                'h5'  => esc_html__( 'H5', 'mega-elements-addons-for-elementor' ),
                'h6'  => esc_html__( 'H6', 'mega-elements-addons-for-elementor' ),
                'div' => esc_html__( 'div', 'mega-elements-addons-for-elementor' ),
            ],
                'default' => 'h3',
            ] 
        );

        $this->add_control( 
            'bssts_service_title_color', 
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-services .meafe-service-text .meafe-title, {{WRAPPER}} .meafe-services .meafe-service-text .meafe-title a' => 'color: {{VALUE}}',
                ],
            ] 
        );

        $this->add_control( 
            'bssts_service_title_hover_color', 
            [
                'label'     => esc_html__( 'Hover Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-services .meafe-service-text .meafe-title a:hover' => 'color: {{VALUE}}',
                ],
            ] 
        );

        $this->add_group_control( 
            Group_Control_Typography::get_type(), 
            [
                'name'     => 'bssts_service_title_typography',
                'selector' => '{{WRAPPER}} .meafe-services .meafe-service-text .meafe-title',
            ] 
        );

        $this->end_controls_section();

        /**
         * Services Text Style
        */
        $this->start_controls_section( 
            'meafe_services_style_text_style', 
            [
                'label' => esc_html__( 'Content Style', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ] 
        );

        $this->add_control( 
            'bssts_service_text_color', 
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-services .meafe-service-text .meafe-service-details' => 'color: {{VALUE}}',
                ],
            ] 
        );

        $this->add_control( 
            'bssts_service_text_hover_color', 
            [
                'label'     => esc_html__( 'Hover Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-services .meafe-service-text .meafe-service-details:hover' => 'color: {{VALUE}}',
                ],
            ] 
        );

        $this->add_group_control( 
            Group_Control_Typography::get_type(), 
            [
                'name'     => 'bssts_service_text_typography',
                'selector' => '{{WRAPPER}} .meafe-services .meafe-service-text .meafe-service-details',
            ] 
        );

        $this->end_controls_section();

        /**
         * Services Icons Style
        */
        $this->start_controls_section( 
            'meafe_services_style_icon_style', 
            [
                'label' => esc_html__( 'Icons Style', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ] 
        );

        $this->add_control( 
            'bssis_service_icon_size', 
            [
                'label'      => esc_html__( 'Icon or Image size', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .meafe-services .meafe-image-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .meafe-services .meafe-icon-wrapper svg'    => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control( 
            'bssis_service_icon_height', 
            [
                'label'      => esc_html__( 'Icon Height', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .meafe-services .meafe-image-wrapper' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control( 
            'bssis_service_icon_color', 
            [
                'label'     => esc_html__( 'Custom Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-services .meafe-icon-wrapper' => 'color: {{VALUE}}',
                ],
            ] 
        );

        $this->add_control( 
            'bssis_service_hover_color', 
            [
                'label'     => esc_html__( 'Hover Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-services .meafe-icon-wrapper:hover' => 'color: {{VALUE}}',
                ],
            ] 
        );

        $this->end_controls_section();

        /**
         * Button General Style
        */
        $this->start_controls_section(
            'meafe_service_style_button_style',
            [
                'label'     => esc_html__( 'Button Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );    
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'bssbs_service_button_typo',
                'selector'          => '{{WRAPPER}} .meafe-services .meafe-readmore-link',
            ]
        );
        
        $this->start_controls_tabs( 'bssbs_service_button_style_tabs' );
        
        $this->start_controls_tab( 
            'bssbs_service_button_style_normal',
            [
                'label'             => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );
        
        $this->add_control(
            'bssbs_service_button_text_color_normal',
            [
                'label'             => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::COLOR,
                'selectors'         => [
                    '{{WRAPPER}} .meafe-services .meafe-readmore-link'   => 'color: {{VALUE}}',
                ]
            ]
        );        

        $this->add_control(
            'bssbs_service_button_background_color',
            [
                'label'             => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::COLOR,
                'selectors'      => [
                    '{{WRAPPER}} .meafe-services .meafe-readmore-link'  => 'background: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'bssbs_service_button_border_normal',
                'selector'      => '{{WRAPPER}} .meafe-services .meafe-readmore-link',
            ]
        );
        
        $this->add_control(
            'bssbs_service_button_border_radius_normal',
            [
                'label'         => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-services .meafe-readmore-link' => 'border-radius: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'bssbs_service_button_box_shadow_normal',
                    'label'         => esc_html__( 'Button Shadow', 'mega-elements-addons-for-elementor' ),
                    'selector'      => '{{WRAPPER}} .meafe-services .meafe-readmore-link',
                ]
                );
        
        $this->add_responsive_control(
            'bssbs_service_button_margin_normal',
            [
                'label'         => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-services .meafe-readmore-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'bssbs_service_button_padding_normal',
            [
                'label'         => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-services .meafe-readmore-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'bssbs_service_button_style_hover',
            [
                'label'         => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );
        
        $this->add_control(
            'bssbs_service_button_text_color_hover',
            [
                'label'             => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::COLOR,
                'selectors'         => [
                    '{{WRAPPER}} .meafe-services .meafe-readmore-link:hover'=> 'color: {{VALUE}}',
                ],
            ]);
        
        $this->add_control(
            'bssbs_service_button_background_hover',
            [
                'label'             => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::COLOR,
                'selectors'          => [
                    '' . '{{WRAPPER}} .meafe-services .meafe-readmore-link:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'bssbs_service_button_border_hover',
                'selector'      => '{{WRAPPER}} .meafe-services .meafe-readmore-link:hover',
            ]
        );
        
        $this->add_control(
            'bssbs_service_button_border_radius_hover',
            [
                'label'         => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-services .meafe-readmore-link:hover' => 'border-radius: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
            
        $this->end_controls_tab();

        
        $this->end_controls_tabs();

        $this->add_control(
            'bssbs_service_button_icon_color',
            [
                'label'             => esc_html__( 'Icon Color', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::COLOR,
                'selectors'         => [
                    '{{WRAPPER}} .meafe-services .meafe-readmore-link svg'   => 'color: {{VALUE}}',
                ]
            ]
        );   

        $this->add_control( 
            'bssbs_service_button_icon_size', 
            [
                'label'      => esc_html__( 'Icon size', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .meafe-services .meafe-readmore-link svg'    => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );   

        $this->add_control( 
            'bssbs_service_button_icon_indent', 
            [
                'label'      => esc_html__( 'Icon Indentation', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .meafe-services .meafe-readmore-link svg'    => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );     

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $migration_allowed = Icons_Manager::is_migration_allowed();

        if( $settings['bscgs_service_icon_type'] == 'image' ) {
            $settings['bscgs_services'] = $settings['bscgs_image_services'];
        }elseif( $settings['bscgs_service_icon_type'] == 'icon_image' ) {
            $settings['bscgs_services'] = $settings['bscgs_icon_image_services'];
        }else{
            $settings['bscgs_services'] = $settings['bscgs_services'];
        }

        ?>
        <div class="meafe-services meafe-grid-container<?php echo $this->get_grid_classes( $settings ); ?>">
            <?php 
            foreach ( $settings['bscgs_services'] as $index => $service ) {
                $has_link = false;
                
                if ( !empty( $service['bscgs_service_link']['url'] ) ) {
                    $has_link = true;
                    $link_key = 'bscgs_service_link_' . $index;
                    $url = $service['bscgs_service_link'];
                    $this->add_render_attribute( $link_key, 'title', esc_html($service['bscgs_service_title']) );
                    $this->add_render_attribute( $link_key, 'href', esc_url($url['url']) );
                    if ( $settings['bscgs_service_target_blank_all'] || !empty($url['is_external']) ) {
                        $this->add_render_attribute( $link_key, 'target', '_blank' );
                    }
                    if ( !empty($url['nofollow']) ) {
                        $this->add_render_attribute( $link_key, 'rel', 'nofollow' );
                    }
                }
                ?>

                <div class="meafe-grid-item meafe-service-wrapper">
                <?php 
                    if ( $settings['bscgs_service_icon_type'] == 'icon_image' ) {
                        
                        if ( !empty( $service['bscgs_service_icon_image']['id'] ) ) {
                            echo '<div class="meafe-image-wrapper">';
                            if ( $has_link ) {
                                echo '<a class="meafe-image-link" ' . $this->get_render_attribute_string( $link_key ) . '>';
                            } 
                            echo wp_get_attachment_image( $service['bscgs_service_icon_image']['id'], 'thumbnail', false );
                            if ( $has_link ) {
                                '</a>';
                            }
                            echo '</div>';
                        }
                    
                    } elseif ( $settings['bscgs_service_icon_type'] == 'image' ) {
                        
                        if ( !empty( $service['bscgs_service_icon_image']['id'] ) ) {                                                           
                            echo '<div class="meafe-full-image-wrapper">';
                            if ( $has_link ) {
                                echo '<a class="meafe-full-image-link" ' . $this->get_render_attribute_string( $link_key ) . '>';
                            } 
                            echo wp_get_attachment_image( $service['bscgs_service_icon_image']['id'], 'full', false );
                            if ( $has_link ) {
                                echo '</a>';
                            }
                            echo '</div>';
                        }
                    
                    } elseif ( $settings['bscgs_service_icon_type'] == 'icon' && ( !empty( $service['bscgs_service_icon'] ) || !empty($service['bscgs_service_selected_icon']['value'])) ) {
                        $migrated = isset( $service['__fa4_migrated']['bscgs_service_selected_icon'] );
                        $is_new = empty($service['bscgs_service_icon']) && $migration_allowed;
                        echo '<div class="meafe-icon-wrapper">';
                        
                        if ( $is_new || $migrated ) {
                            ob_start();
                            Icons_Manager::render_icon( $service['bscgs_service_selected_icon'], [
                                'aria-hidden' => 'true',
                            ] );
                            $icon_html = ob_get_contents();
                            ob_end_clean();
                        } else {
                            $icon_html = '<i class="' . esc_attr( $service['bscgs_service_icon'] ) . '" aria-hidden="true"></i>';
                        }
                        
                        if ( $has_link ) {
                            $icon_html = '<a class="meafe-icon-link" ' . $this->get_render_attribute_string( $link_key ) . '>' . $icon_html . '</a>';
                        }
                        echo $icon_html;
                        echo '</div>';
                    }
                    ?>
                    <div class="meafe-service-text">
                        <?php
                        echo '<' . Utils::validate_html_tag( $settings['bssts_service_title_tag'] ) . ' class="meafe-title">'; 

                        if ( $has_link ) {
                            echo '<a class="meafe-title-link" ' . $this->get_render_attribute_string( $link_key ) . '>';
                        }
                        echo esc_html( $service['bscgs_service_title'] );
                        if ( $has_link ) {
                            echo '</a>';
                        }
                        echo '</' . Utils::validate_html_tag( $settings['bssts_service_title_tag'] ) . '>';
                        echo '<div class="meafe-service-details">' . wp_kses_post( $service['bscgs_service_excerpt'] ) . '</div>';
                        ?>
                    </div><!-- .meafe-service-text -->
                <?php
                if ( $has_link && $service['bscgs_service_read_more'] ) {
                    echo '<div class="meafe-button-wrap">';
                    echo '<a class="meafe-readmore-link" ' . $this->get_render_attribute_string( $link_key ) . '>' . esc_html($service['bscgs_service_read_more']);
                    if ( !empty( $service['bscgs_service_read_more_icon'] ) || !empty($service['bscgs_service_selected_read_more_icon']['value']) ) {
                        $migrated = isset( $service['__fa4_migrated']['bscgs_service_selected_read_more_icon'] );
                        $is_new = empty($service['icon']) && $migration_allowed;
                        
                        if ( $is_new || $migrated ) {
                            ob_start();
                            Icons_Manager::render_icon( $service['bscgs_service_selected_read_more_icon'], [
                                'aria-hidden' => 'true',
                            ] );
                            $icon_html = ob_get_contents();
                            ob_end_clean();
                        } else {
                            $icon_html = '<i class="' . esc_attr( $service['bscgs_service_read_more_icon'] ) . '" aria-hidden="true"></i>';
                        }
                    
                        echo $icon_html;
                    }
                    echo '</a>';
                    echo '</div>';
                } ?>
                </div><!-- .meafe-service-wrapper -->
            <?php } ?>
        </div>
        <div class="meafe-clear"></div>
        <?php
    }
}
