<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;


class MEAFE_Price_Menu extends Widget_Base {
    
    public function get_name() {
        return 'meafe-price-menu';
    }

    public function get_title() {
        return __( 'Price Menu', 'mega-elements-addons-for-elementor' );
    }

    public function get_categories() {
        return [ 'meafe-elements' ];
    }

    public function get_icon() {
        return 'meafe-price-menu';
    }

    public function get_style_depends() {
        return ['meafe-price-menu'];
    }

    protected function register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  Content Tab
        /*-----------------------------------------------------------------------------------*/
        
        $this->start_controls_section(
            'meafe_price_menu_content_general_settings',
            [
                'label'                 => __( 'Price Menu', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $pm_repeater = new Repeater();

        $pm_repeater->add_control(
            'bpmcgs_price_menu_title',
            [
                'name'          => 'bpmcgs_price_menu_title',
                'label'         => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [
                    'active'    => true,
                ],
                'label_block'   => true,
                'placeholder'   => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'default'       => __( 'Title', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $pm_repeater->add_control(
            'bpmcgs_price_after_title_type',
            [
                'label'         => esc_html__('Title Suffix', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::CHOOSE,
                'label_block'   => false,
                'options'       => [
                    'none'      => [
                        'title'     => esc_html__('None', 'mega-elements-addons-for-elementor'),
                        'icon'      => 'fa fa-ban',
                    ],
                    'icon'      => [
                        'title'     => esc_html__('Icon', 'mega-elements-addons-for-elementor'),
                        'icon'      => 'fa fa-gear',
                    ],
                    'text'      => [
                        'title'     => esc_html__('Text', 'mega-elements-addons-for-elementor'),
                        'icon'      => 'fa fa-text-width',
                    ],
                ],
                'default' => 'none',
            ]
        );

        $pm_repeater->add_control(
            'bpmcgs_price_title_icon_new',
            [
                'label'         => esc_html__('Icon', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::ICONS,
                'fa4compatibility' => 'bpmcgs_price_title_icon',
                'default'       => [
                    'value'         => 'fas fa-home',
                    'library'       => 'fa-solid',
                ],
                'condition'     => [
                    'bpmcgs_price_after_title_type' => 'icon',
                ],
            ]
        );

        $pm_repeater->add_control(
            'bpmcgs_price_title_text',
            [
                'name'          => 'bpmcgs_price_title_text',
                'label'         => esc_html__('Suffix Text', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'default'       => esc_html__('Popular', 'mega-elements-addons-for-elementor'),
                'condition'     => [
                    'bpmcgs_price_after_title_type' => 'text',
                ],
            ]
        );

        $pm_repeater->add_control(
            'bpmcgs_price_link',
            [
                'name'          => 'bpmcgs_price_link',
                'label'         => __( 'Link', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::URL,
                'dynamic'       => [
                    'active'    => true,
                ],
                'placeholder'   => 'https://www.your-link.com',
                'condition'     => [
                    'bpmcgs_price_after_title_type' => 'text',
                ],
            ]
        );

        $pm_repeater->add_control(
            'bpmcgs_price_menu_description',
            [
                'name'          => 'bpmcgs_price_menu_description',
                'label'         => __( 'Description', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXTAREA,
                'dynamic'       => [
                    'active'    => true,
                ],
                'label_block'   => true,
                'placeholder'   => __( 'Description', 'mega-elements-addons-for-elementor' ),
                'default'       => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus est in nisl elementum, non tempor eros volutpat.', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $pm_repeater->add_control(
            'bpmcgs_price_menu_price',
            [
                'name'          => 'bpmcgs_price_menu_price',
                'label'         => __( 'Price', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [
                    'active'    => true,
                ],
                'default'       => '$49',
            ]
        );

        $pm_repeater->add_control(
            'bpmcgs_price_discount',
            [
                'name'          => 'bpmcgs_price_discount',
                'label'         => __( 'Discount', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'no',
                'label_on'      => __( 'On', 'mega-elements-addons-for-elementor' ),
                'label_off'     => __( 'Off', 'mega-elements-addons-for-elementor' ),
                'return_value'  => 'yes',
            ]
        );

        $pm_repeater->add_control(
            'bpmcgs_price_original_price',
            [
                'name'          => 'bpmcgs_price_original_price',
                'label'         => __( 'Original Price', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [
                    'active'    => true,
                ],
                'default'       => '$69',
                'condition'     => [
                    'bpmcgs_price_discount' => 'yes',
                ],
            ]
        );

        $pm_repeater->add_control(
            'bpmcgs_price_image_switch',
            [
                'name'          => 'bpmcgs_price_image_switch',
                'label'         => __( 'Show Image', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => '',
                'label_on'      => __( 'On', 'mega-elements-addons-for-elementor' ),
                'label_off'     => __( 'Off', 'mega-elements-addons-for-elementor' ),
                'return_value'  => 'yes',
            ]
        );

        $pm_repeater->add_control(
            'bpmcgs_price_image',
            [
                'name'          => 'bpmcgs_price_image',
                'label'         => __( 'Image', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => [
                    'active'    => true,
                ],
                'condition'     => [
                    'bpmcgs_price_image_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'bpmcgs_price_menu_items',
            [
                'label'                 => '',
                'type'                  => Controls_Manager::REPEATER,
                'default'               => [
                    [
                        'bpmcgs_price_menu_title' => __( 'Menu Item #1', 'mega-elements-addons-for-elementor' ),
                        'bpmcgs_price_menu_price' => '$49',
                    ],
                    [
                        'bpmcgs_price_menu_title' => __( 'Menu Item #2', 'mega-elements-addons-for-elementor' ),
                        'bpmcgs_price_menu_price' => '$49',
                    ],
                    [
                        'bpmcgs_price_menu_title' => __( 'Menu Item #3', 'mega-elements-addons-for-elementor' ),
                        'bpmcgs_price_menu_price' => '$49',
                    ],
                ],
                'fields'                => $pm_repeater->get_controls(),
                'title_field'       => '{{{ bpmcgs_price_menu_title }}}',
            ]
        );
        
        $this->add_control(
          'bpmcgs_price_menu_style',
          [
             'label'                => __( 'Price Menu Layout', 'mega-elements-addons-for-elementor' ),
             'type'                 => Controls_Manager::SELECT,
             'default'              => '1',
             'options'              => [
                '1'           => __( 'Position 1', 'mega-elements-addons-for-elementor' ),
                '2'           => __( 'Position 2', 'mega-elements-addons-for-elementor' ),
                '3'           => __( 'Position 3', 'mega-elements-addons-for-elementor' ),
             ],
          ]
        );
        
        $this->add_control(
            'bpmcgs_price_title_price_connector',
            [
                'label'                 => __( 'Title-Price Connector', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Yes', 'mega-elements-addons-for-elementor' ),
                'label_off'             => __( 'No', 'mega-elements-addons-for-elementor' ),
                'return_value'          => 'yes',
                'condition'             => [
                    'bpmcgs_price_menu_style!' => '3',
                ],

            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Tab
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Menu Items Section
         */
        $this->start_controls_section(
            'meafe_price_menu_style_menu_items_style',
            [
                'label'                 => __( 'Menu Items', 'mega-elements-addons-for-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bpmsmis_items_bg_color',
            [
                'label'                 => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-wrapper .meafe-pricing-menu-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'bpmsmis_items_spacing',
            [
                'label'                 => __( 'Items Spacing', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    '%' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-wrap' => 'margin-bottom: calc(({{SIZE}}{{UNIT}})/2); padding-bottom: calc(({{SIZE}}{{UNIT}})/2)',
                ],
            ]
        );

        $this->add_responsive_control(
            'bpmsmis_items_padding',
            [
                'label'                 => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-wrapper .meafe-pricing-menu-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'                  => 'bpmsmis_items_border',
                'label'                 => __( 'Border', 'mega-elements-addons-for-elementor' ),
                'placeholder'           => '1px',
                'default'               => '1px',
                'selector'              => '{{WRAPPER}} .meafe-pricing-menu-wrapper .meafe-pricing-menu-wrap',
            ]
        );

        $this->add_control(
            'bpmsmis_items_border_radius',
            [
                'label'                 => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-wrapper .meafe-pricing-menu-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'                  => 'bpmsmis_pricing_table_shadow',
                'selector'              => '{{WRAPPER}} .meafe-pricing-menu-wrap',
                'separator'             => 'before',
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Title Section
         */
        $this->start_controls_section(
            'meafe_price_menu_style_title_style',
            [
                'label'                 => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bpmsts_title_color',
            [
                'label'                 => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-wrapper .pricing-menu-title .title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'bpmsts_title_typography',
                'label'                 => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'              => '{{WRAPPER}} .meafe-pricing-menu-wrapper .pricing-menu-title .title',
            ]
        );
        
        $this->add_responsive_control(
            'bpmsts_title_margin',
            [
                'label'                 => __( 'Margin Bottom', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    '%' => [
                        'min'   => 0,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-wrapper .meafe-pricing-menu-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_price_menu_style_price_style',
            [
                'label'                 => __( 'Price', 'mega-elements-addons-for-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'bpmsps_price_color',
            [
                'label'                 => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-wrapper .menu-price .price' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'bpmsps_price_typography',
                'label'                 => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'              => '{{WRAPPER}} .meafe-pricing-menu-wrapper .menu-price .price',
            ]
        );
        
        $this->add_control(
            'bpmsps_original_price_heading',
            [
                'label'                 => __( 'Original Price', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );

        $this->add_control(
            'bpmsps_original_price_color',
            [
                'label'                 => __( 'Original Price Color', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#a3a3a3',
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-wrapper .menu-price .original-price' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'bpmsps_original_price_typography',
                'label'                 => __( 'Original Price Typography', 'mega-elements-addons-for-elementor' ),
                'selector'              => '{{WRAPPER}} .meafe-pricing-menu-wrapper .menu-price .original-price',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_price_menu_style_description_style',
            [
                'label'                 => __( 'Description', 'mega-elements-addons-for-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bpmsds_description_color',
            [
                'label'                 => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-content' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'bpmsds_description_typography',
                'label'                 => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'              => '{{WRAPPER}} .meafe-pricing-menu-content',
            ]
        );
        
        $this->add_responsive_control(
            'bpmsds_description_spacing',
            [
                'label'                 => __( 'Margin Bottom', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    '%' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Image Section
         */
        $this->start_controls_section(
            'meafe_price_menu_style_image_style',
            [
                'label'                 => __( 'Image', 'mega-elements-addons-for-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'                  => 'bpmsis_image_size',
                'label'                 => __( 'Image Size', 'mega-elements-addons-for-elementor' ),
                'default'               => 'thumbnail',
            ]
        );

        $this->add_control(
            'bpmsis_image_bg_color',
            [
                'label'                 => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-head .menu-image img' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'bpmsis_image_width',
            [
                'label'                 => __( 'Width', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 20,
                        'max'   => 300,
                        'step'  => 1,
                    ],
                    '%' => [
                        'min'   => 5,
                        'max'   => 50,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-head .menu-image img' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bpmsis_image_margin',
            [
                'label'                 => __( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-head .menu-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bpmsis_image_padding',
            [
                'label'                 => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-head .menu-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'                  => 'bpmsis_image_border',
                'label'                 => __( 'Border', 'mega-elements-addons-for-elementor' ),
                'placeholder'           => '1px',
                'default'               => '1px',
                'selector'              => '{{WRAPPER}} .meafe-pricing-menu-head .menu-image img',
            ]
        );

        $this->add_control(
            'bpmsis_image_border_radius',
            [
                'label'                 => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-head .menu-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Items Divider Section
         */
        $this->start_controls_section(
            'meafe_price_menu_style_title_price_connector_style',
            [
                'label'                 => __( 'Title-Price Connector', 'mega-elements-addons-for-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'bpmcgs_price_title_price_connector' => 'yes',
                    'bpmcgs_price_menu_style!' => '3',
                ],
            ]
        );
        
        $this->add_control(
            'bpmstpcs_items_divider_style',
            [
                'label'                 => __( 'Style', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'dashed',
                'options'              => [
                    'solid'     => __( 'Solid', 'mega-elements-addons-for-elementor' ),
                    'dashed'    => __( 'Dashed', 'mega-elements-addons-for-elementor' ),
                    'dotted'    => __( 'Dotted', 'mega-elements-addons-for-elementor' ),
                    'double'    => __( 'Double', 'mega-elements-addons-for-elementor' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-wrapper .meafe-pricing-menu-title .divider' => 'border-bottom-style: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'bpmstpcs_items_divider_borders',
            [
                'label'                 => __( 'Divider Border', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    '%' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-wrapper .meafe-pricing-menu-title .divider' => 'border-width:{{SIZE}}{{UNIT}} ;',
                ],
            ]
        );


        $this->add_control(
            'bpmstpcs_items_divider_color',
            [
                'label'                 => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#000',
                'selectors'             => [
                    '{{WRAPPER}} .meafe-pricing-menu-wrapper .meafe-pricing-menu-title .divider' => 'border-bottom-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Suffix Section
         */
        $this->start_controls_section(
            'meafe_price_menu_style_title_suffix_style',
            [
                'label'                 => __( 'Title Suffix Style', 'mega-elements-addons-for-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,                
            ]
        );

        $this->add_control(
            'bpmstss_suffix_text_color',
            [
                'label'                 => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .pricing-menu-title .tag' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'bpmstss_suffix_bg_color',
            [
                'label'                 => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .pricing-menu-title .tag' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'                  => 'bpmstss_suffix_border',
                'label'                 => __( 'Border', 'mega-elements-addons-for-elementor' ),
                'placeholder'           => '1px',
                'default'               => '1px',
                'selector'              => '{{WRAPPER}} .pricing-menu-title .tag',
            ]
        );

        $this->add_control(
            'bpmstss_suffix_border_radius',
            [
                'label'                 => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pricing-menu-title .tag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $pm_icon_migrated = isset($settings['__fa4_migrated']['bpmcgs_price_title_icon_new']);
        $pm_icon_is_new = empty($settings['bpmcgs_price_title_icon']);
        $i = 1;
        $this->add_render_attribute( 'price-menu', 'class', 'meafe-pricing-menu-wrapper' );
        

        if ( $settings['bpmcgs_price_menu_style'] ) {
            $this->add_render_attribute( 'price-menu', 'class', 'layout-' . esc_attr($settings['bpmcgs_price_menu_style']) );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'price-menu' ); ?>>
            <?php foreach ( $settings['bpmcgs_price_menu_items'] as $index => $item ) : ?>
                <div class="meafe-pricing-menu-wrap">
                    <?php
                        $title_key = $this->get_repeater_setting_key( 'bpmcgs_price_menu_title', 'bpmcgs_price_menu_items', $index );
                        $this->add_render_attribute( $title_key, 'class', 'title' );
                        $this->add_inline_editing_attributes( $title_key, 'none' );

                        $description_key = $this->get_repeater_setting_key( 'bpmcgs_price_menu_description', 'bpmcgs_price_menu_items', $index );
                        $this->add_render_attribute( $description_key, 'class', 'meafe-pricing-menu-content' );
                        $this->add_inline_editing_attributes( $description_key, 'basic' );

                        $discount_price_key = $this->get_repeater_setting_key( 'bpmcgs_price_menu_price', 'bpmcgs_price_menu_items', $index );
                        $this->add_render_attribute( $discount_price_key, 'class', 'price' );
                        $this->add_inline_editing_attributes( $discount_price_key, 'none' );

                        $original_price_key = $this->get_repeater_setting_key( 'bpmcgs_price_original_price', 'bpmcgs_price_menu_items', $index );
                        $this->add_render_attribute( $original_price_key, 'class', 'original-price' );
                        $this->add_inline_editing_attributes( $original_price_key, 'none' );
                    ?>
                    
                    <?php if ( $item['bpmcgs_price_image_switch'] == 'yes' ) { ?>
                        <div class="meafe-pricing-menu-head">
                            <figure class="menu-image">
                            <?php
                                if ( ! empty( $item['bpmcgs_price_image']['url'] ) ) :
                                    $image = $item['bpmcgs_price_image'];
                                    $image_url = Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'bpmsis_image_size', $settings );
                                ?>
                                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_post_meta($image['id'], '_wp_attachment_image_alt', true ) ); ?>">   
                            <?php endif; ?>
                            </figure>
                        </div>
                    <?php } ?>
                    <div class="meafe-pricing-menu-info">
                        <div class="meafe-pricing-menu-title">
                            <?php if ( ! empty( $item['bpmcgs_price_menu_title'] ) ) { ?>
                                <div class="pricing-menu-title">
                                        <h2 <?php echo $this->get_render_attribute_string( $title_key ); ?>>
                                            <?php echo esc_html($item['bpmcgs_price_menu_title']); ?>
                                        </h2>
                                        <?php
                                        if ( $item['bpmcgs_price_after_title_type'] === 'icon' ) :
                                            echo '<span class="menu-icon">';
                                            if ( $pm_icon_is_new || $pm_icon_migrated ) {
                                                if ( isset( $item['bpmcgs_price_title_icon_new']['value']['url'] ) ) {
                                                    echo '<img src="' . esc_url($item['bpmcgs_price_title_icon_new']['value']['url']) . '"/>';
                                                } else {
                                                    echo '<i class="' . esc_attr($item['bpmcgs_price_title_icon_new']['value']) . '"></i>';
                                                }
                                            } else {
                                                echo '<i class="' . esc_attr($item['bpmcgs_price_title_icon']) . '"></i>';
                                            } 
                                            echo '</span>';
                                        elseif ( $item['bpmcgs_price_after_title_type'] === 'text' ) :
                                            if ( ! empty( $item['bpmcgs_price_link']['url'] ) ) {
                                                $this->add_render_attribute( 'price-menu-link' . $i, 'href', esc_url($item['bpmcgs_price_link']['url']) );
                                                $this->add_render_attribute( 'price-menu-link' . $i, 'class', 'tag' );

                                                if ( ! empty( $item['bpmcgs_price_link']['is_external'] ) ) {
                                                    $this->add_render_attribute( 'price-menu-link' . $i, 'target', '_blank' );
                                                } ?>
                                                <a <?php echo $this->get_render_attribute_string( 'price-menu-link' . $i ); ?>>
                                                    <?php echo esc_html($item['bpmcgs_price_title_text']); ?>
                                                </a>
                                            <?php
                                            } else {
                                                echo '<span class="tag">' . esc_html( $item['bpmcgs_price_title_text'] ) . '</span>';
                                            }
                                        endif; 
                                    ?>
                                </div>
                            <?php } ?>
                            
                            <?php if ( $settings['bpmcgs_price_title_price_connector'] == 'yes' && $settings['bpmcgs_price_menu_style'] != '3' ) { ?>
                                <span class="divider"></span>
                            <?php } ?>
                            
                            <?php if ( $settings['bpmcgs_price_menu_style'] != '3' ) { ?>
                                <?php if ( ! empty( $item['bpmcgs_price_menu_price'] ) ) { ?>
                                    <div class="menu-price">
                                        <?php if ( $item['bpmcgs_price_discount'] == 'yes' ) { ?>
                                            <span <?php echo $this->get_render_attribute_string( $original_price_key ); ?>>
                                                <?php echo esc_html( $item['bpmcgs_price_original_price'] ); ?>
                                            </span>
                                        <?php } ?>
                                        <span <?php echo $this->get_render_attribute_string( $discount_price_key ); ?>>
                                            <?php echo esc_html( $item['bpmcgs_price_menu_price'] ); ?>
                                        </span>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>

                        <?php
                            if ( ! empty( $item['bpmcgs_price_menu_description'] ) ) {
                                $description_html = sprintf( '<div %1$s>%2$s</div>', $this->get_render_attribute_string( $description_key ), wp_kses_post($item['bpmcgs_price_menu_description']) );
                                
                                echo $description_html;
                            }
                        ?>

                        <?php if ( $settings['bpmcgs_price_menu_style'] == '3' ) { ?>
                            <?php if ( ! empty( $item['bpmcgs_price_menu_price'] ) ) { ?>
                                <div class="menu-price">
                                    <?php if ( $item['bpmcgs_price_discount'] == 'yes' ) { ?>
                                        <span <?php echo $this->get_render_attribute_string( $original_price_key ); ?>>
                                            <?php echo esc_html( $item['bpmcgs_price_original_price'] ); ?>
                                        </span>
                                    <?php } ?>
                                    <span <?php echo $this->get_render_attribute_string( $discount_price_key ); ?>>
                                        <?php echo esc_html( $item['bpmcgs_price_menu_price'] ); ?>
                                    </span>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>                    
                </div>
            <?php $i++; endforeach; ?>
        </div>
        <?php
    }

    protected function content_template() {
    }
}