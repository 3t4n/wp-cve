<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;

class MEAFE_Cta extends Widget_Base
{

    public function get_name() {
        return 'meafe-cta';
    }

    public function get_title() {
        return esc_html__( 'Call To Action', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-cta';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-cta'];
    }

    protected function register_controls()
    {
        /**
         * CTA Image Settings
         */
        $this->start_controls_section(
            'meafe_cta_content_image_settings',
            [
                'label' => esc_html__( 'Image Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );
        $this->add_control(
            'bccis_cta_type',
            [
                'label'         => esc_html__( 'CTA Type', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'classic',
                'options'       => [
                    'classic'   => esc_html__( 'Classic', 'mega-elements-addons-for-elementor' ),
                    'cover'     => esc_html__( 'Cover', 'mega-elements-addons-for-elementor' ),
                ],
                'prefix_class'  => 'meafe-cta-type-',
                'render_type'   => 'template'
            ]
        );

        $this->add_control(
            'bccis_cta_image_alignment',
            [
                'label'         => esc_html__( 'Layout', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'label_block'   => false,
                'options'       => [
                    'left'      => [
                        'title'     => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'eicon-h-align-left'
                    ],
                    'center'    => [
                        'title'     => esc_html__( 'Above', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'eicon-v-align-top'
                    ],
                    'right'     => [
                        'title'     => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'eicon-h-align-right'
                    ],
                ],
                'default'       => 'left',
                'prefix_class'  => 'meafe-cta-layout-image-',
                'condition'     => [
                    'bccis_cta_type!' => 'cover',
                ],
            ]
        );

        $this->add_control(
            'bccis_cta_bg_image',
            [
                'label'         => esc_html__( 'Choose Image', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::MEDIA,
                'default'       => [
                    'url'       => Utils::get_placeholder_image_src(),
                ],
                'show_label'    => false,
            ]                    
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'         => 'bccis_cta_bg_image',
                'label'         => esc_html__( 'Image Resolution', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => 'large',
                'condition'     => [
                    'bccis_cta_bg_image[id]!' => '',
                ],
                'separator'     => 'none',
            ]                    
        );

        $this->end_controls_section();

        /**
         * Cta Content Settings
         */
        $this->start_controls_section(
            'meafe_cta_content_content_settings',
            [
                'label' => esc_html__( 'Content Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bcccs_cta_title',
            [
                'label'     => esc_html__( 'Title', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'This is the heading.', 'mega-elements-addons-for-elementor' ),
                'placeholder'   => esc_html__( 'Enter your title.', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bcccs_cta_description',
            [
                'label'     => esc_html__( 'Description', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::WYSIWYG,
                'default'   => esc_html__( 'Click Edit Button to change this text. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit.', 'mega-elements-addons-for-elementor' ),
                'placeholder'   => esc_html__( 'Enter your Description.', 'mega-elements-addons-for-elementor' ),
                'separator' => 'none',
                'rows'      => 5
            ]
        );

        $this->add_control(
            'bcccs_cta_title_tag',
            [
                'label'     => esc_html__( 'Select Tag', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'h2',
                'options'   => [
                    'h1'    => esc_html__( 'H1', 'mega-elements-addons-for-elementor' ),
                    'h2'    => esc_html__( 'H2', 'mega-elements-addons-for-elementor' ),
                    'h3'    => esc_html__( 'H3', 'mega-elements-addons-for-elementor' ),
                    'h4'    => esc_html__( 'H4', 'mega-elements-addons-for-elementor' ),
                    'h5'    => esc_html__( 'H5', 'mega-elements-addons-for-elementor' ),
                    'h6'    => esc_html__( 'H6', 'mega-elements-addons-for-elementor' ),
                    'span'  => esc_html__( 'Span', 'mega-elements-addons-for-elementor' ),
                    'p'     => esc_html__( 'P', 'mega-elements-addons-for-elementor' ),
                    'div'   => esc_html__( 'Div', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'bcccs_cta_button',
            [
                'label'     => esc_html__( 'Button Text', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Click Here', 'mega-elements-addons-for-elementor'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bcccs_cta_btn_link',
            [
                'label'     => esc_html__( 'Button Link', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => 'https://',
                    'is_external' => '',
                ],
                'show_external' => true,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'bcccs_cta_button_two_show',
            [
                'label'     => esc_html__( 'Show Secondary Button', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'no',
            ]
        );

        $this->add_control(
            'bcccs_cta_button_two',
            [
                'label'     => esc_html__( 'Secondary Button Text', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Click Here', 'mega-elements-addons-for-elementor'),
                'condition' => [
                    'bcccs_cta_button_two_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'bcccs_cta_btn_two_link',
            [
                'label'     => esc_html__( 'Secondary Button Link', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => 'https://',
                    'is_external' => '',
                ],
                'show_external' => true,
                'separator' => 'after',
                'condition' => [
                    'bcccs_cta_button_two_show' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * CTA Box Style 
         * -------------------------------------------
         */

        $this->start_controls_section(
            'meafe_cta_style_box_style',
            [
                'label'     => esc_html__( 'Box Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'bcsbs_cta_box_min_height',
            [
                'label'     => esc_html__( 'Min. Height', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px'    => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh'    => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units'=> [ 'px', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-content' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'bccis_cta_type!'  => 'classic',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'bcsbs_cta_box_max_width',
            [
                'label'     => esc_html__( 'Max. Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px'    => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh'    => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units'=> [ 'px', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-content' => 'max-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'bccis_cta_type!'  => 'classic',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bcsbs_cta_box_alignment',
            [
                'label'     => esc_html__( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'   => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'prefix_class' => 'meafe-cta-text-align-',
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-content' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'bcsbs_cta_box_vertical_position',
            [
                'label'     => esc_html__( 'Vertical Position', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'   => [
                    'top'   => [
                        'title' => esc_html__( 'Top', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__( 'Middle', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Bottom', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'prefix_class' => 'meafe-cta-valign-',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'bcsbs_cta_box_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units'=> [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'bcsbs_cta_box_heading_bg_image_style',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Image', 'mega-elements-addons-for-elementor' ),
                'condition' => [
                    'bccis_cta_bg_image[url]!' => '',
                    'bccis_cta_type'  => 'classic',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'bcsbs_cta_box_image_max_width',
            [
                'label'     => esc_html__( 'Image Max Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 1170,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-bg-wrapper' => 'max-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'bccis_cta_type' => 'classic',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'bcsbs_cta_box_image_max_height',
            [
                'label'     => esc_html__( 'Image Max Height', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 700,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-bg-wrapper' => 'max-height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'bccis_cta_type' => 'classic',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'bcsbs_cta_box_content_max_width',
            [
                'label'     => esc_html__( 'Content Max Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 1170,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', '%' ],

                'selectors'  => [
                    '{{WRAPPER}} .meafe-cta-content' => 'max-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'bccis_cta_type' => 'classic',
                ],
            ]
        );

        $this->end_controls_section();


        /**
         * -------------------------------------------
         * CTA Content Style 
         * -------------------------------------------
         */
        $this->start_controls_section(
            'meafe_cta_style_content_style',
            [
                'label'     => esc_html__( 'Content Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'conditions'=> [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'bcccs_cta_title',
                            'operator' => '!==',
                            'value' => '',
                        ],
                        [
                            'name' => 'bcccs_cta_description',
                            'operator' => '!==',
                            'value' => '',
                        ],
                        [
                            'name' => 'bcccs_cta_button',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'bcscs_cta_content_heading_style_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Title', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
                'condition' => [
                    'bcccs_cta_title!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bcscs_cta_content_title_typography',
                'selector'  => '{{WRAPPER}} .meafe-cta-title',
                'condition' => [
                    'bcccs_cta_title!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'bcscs_cta_content_title_spacing',
            [
                'label'     => esc_html__( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-title:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'bcccs_cta_title!' => '',
                ],
            ]
        );

        $this->add_control(
            'bcscs_cta_content_heading_style_description',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Description', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
                'condition' => [
                    'bcccs_cta_description!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bcscs_cta_content_description_typography',
                'selector'  => '{{WRAPPER}} .meafe-cta-description',
                'condition' => [
                    'bcccs_cta_description!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'bcscs_cta_content_description_spacing',
            [
                'label'     => esc_html__( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-description:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'bcccs_cta_description!' => '',
                ],
            ]
        );

        $this->add_control(
            'bcscs_cta_content_heading_colors',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Colors', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( 'bcscs_cta_content_color_tabs' );

        $this->start_controls_tab( 'bcscs_cta_content_color_normal',
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bcscs_cta_content_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-content' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'bccis_cta_type'  => 'classic',
                ],
            ]
        );

        $this->add_control(
            'bcscs_cta_content_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-title' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'bcccs_cta_title!' => '',
                ],
            ]
        );

        $this->add_control(
            'bcscs_cta_content_description_color',
            [
                'label'     => esc_html__( 'Description Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-description' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'bcccs_cta_description!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'bcscs_cta_content_color_hover',
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bcscs_cta_content_bg_color_hover',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta:hover .meafe-cta-content' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'bccis_cta_type' => 'classic',
                ],
            ]
        );

        $this->add_control(
            'bcscs_cta_content_title_color_hover',
            [
                'label'     => esc_html__( 'Title Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta:hover .meafe-cta-title' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'bcccs_cta_title!' => '',
                ],
            ]
        );

        $this->add_control(
            'bcscs_cta_content_description_color_hover',
            [
                'label'     => esc_html__( 'Description Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta:hover .meafe-cta-description' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'bcccs_cta_description!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * CTA Button Style 
         * -------------------------------------------
         */
        $this->start_controls_section(
            'meafe_cta_style_button_style',
            [
                'label'     => esc_html__( 'Button Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'bcccs_cta_button!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'bcsbs_cta_buttons_space',
            [
                'label' => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'bcccs_cta_button_two_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_one_style',
            [
                'label'     => esc_html__( 'Button One Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_size',
            [
                'label'     => esc_html__( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'sm',
                'options'   => [
                    'xs' => esc_html__( 'Extra Small', 'mega-elements-addons-for-elementor' ),
                    'sm' => esc_html__( 'Small', 'mega-elements-addons-for-elementor' ),
                    'md' => esc_html__( 'Medium', 'mega-elements-addons-for-elementor' ),
                    'lg' => esc_html__( 'Large', 'mega-elements-addons-for-elementor' ),
                    'xl' => esc_html__( 'Extra Large', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bcsbs_cta_button_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-cta-button',
            ]
        );

        $this->start_controls_tabs( 'bcsbs_cta_button_tabs' );

        $this->start_controls_tab( 'bcsbs_cta_button_normal',
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'bcsbs_cta_button_hover',
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_hover_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'bcsbs_cta_button_border_width',
            [
                'label'     => esc_html__( 'Border Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bcsbs_cta_button_spacing',
            [
                'label'         => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-cta-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_two_style',
            [
                'label'     => esc_html__( 'Secondary Button Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_two_size',
            [
                'label'     => esc_html__( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'sm',
                'options'   => [
                    'xs' => esc_html__( 'Extra Small', 'mega-elements-addons-for-elementor' ),
                    'sm' => esc_html__( 'Small', 'mega-elements-addons-for-elementor' ),
                    'md' => esc_html__( 'Medium', 'mega-elements-addons-for-elementor' ),
                    'lg' => esc_html__( 'Large', 'mega-elements-addons-for-elementor' ),
                    'xl' => esc_html__( 'Extra Large', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bcsbs_cta_button_two_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-cta-button-two',
            ]
        );

        $this->start_controls_tabs( 'bcsbs_cta_button_two_tabs' );

        $this->start_controls_tab( 'bcsbs_cta_button_two_normal',
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_two_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button-two' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_two_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button-two' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_two_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button-two' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'bcsbs_cta_button_two_hover',
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_two_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button-two:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_two_hover_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button-two:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_two_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button-two:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'bcsbs_cta_button_two_border_width',
            [
                'label'     => esc_html__( 'Border Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button-two' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bcsbs_cta_button_two_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-cta-button-two' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bcsbs_cta_button_two_spacing',
            [
                'label'         => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-cta-button-two' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings();

        $wrapper_tag = 'div';
        $button_tag = 'a';
        $link_url = empty( $settings['bcccs_cta_btn_link']['url'] ) ? false : $settings['bcccs_cta_btn_link']['url'];
        $link_url_two = empty( $settings['bcccs_cta_btn_two_link']['url'] ) ? false : $settings['bcccs_cta_btn_two_link']['url'];
        $bg_image = '';
        $print_bg = true;
        $print_content = true;

        if ( ! empty( $settings['bccis_cta_bg_image']['id'] ) ) {
            $bg_image = Group_Control_Image_Size::get_attachment_image_src( $settings['bccis_cta_bg_image']['id'], 'bccis_cta_bg_image', $settings );
        } elseif ( ! empty( $settings['bccis_cta_bg_image']['url'] ) ) {
            $bg_image = $settings['bccis_cta_bg_image']['url'];
        }

        if ( empty( $bg_image ) && 'classic' == $settings['bccis_cta_type'] ) {
            $print_bg = false;
        }

        if ( empty( $settings['bcccs_cta_title'] ) && empty( $settings['bcccs_cta_description'] ) && empty( $settings['bcccs_cta_button'] ) ) {
            $print_content = false;
        }

        $this->add_render_attribute( 'bcccs_cta_title', 'class', [
            'meafe-cta-title',
            'meafe-cta-content-item',
            'meafe-content-item',
        ] );

        $this->add_render_attribute( 'bcccs_cta_description', 'class', [
            'meafe-cta-description',
            'meafe-cta-content-item',
            'meafe-content-item',
        ] );

        $this->add_render_attribute( 'bcccs_cta_button', 'class', [
            'meafe-cta-button',
            'meafe-button',
            'meafe-size-' . esc_attr($settings['bcsbs_cta_button_size']),
        ] );

        $this->add_render_attribute( 'bcccs_cta_button_two', 'class', [
            'meafe-cta-button-two',
            'meafe-button',
            'meafe-size-' . esc_attr($settings['bcsbs_cta_button_two_size']),
        ] );

        if ( ! empty( $link_url ) ) {
            $this->add_render_attribute( 'bcccs_cta_button', 'href', esc_url($link_url) );
            if ( $settings['bcccs_cta_btn_link']['is_external'] ) {
                $this->add_render_attribute( 'bcccs_cta_button', 'target', '_blank' );
            }
        }

        if ( ! empty( $link_url_two ) ) {
            $this->add_render_attribute( 'bcccs_cta_button_two', 'href', esc_url($link_url_two));
            if ( $settings['bcccs_cta_btn_two_link']['is_external'] ) {
                $this->add_render_attribute( 'bcccs_cta_button_two', 'target', '_blank' );
            }
        }

        $this->add_inline_editing_attributes( 'bcccs_cta_title' );
        $this->add_inline_editing_attributes( 'bcccs_cta_description' );
        $this->add_inline_editing_attributes( 'bcccs_cta_button' );
        $this->add_inline_editing_attributes( 'bcccs_cta_button_two' );

        ?>
        <<?php echo esc_attr($wrapper_tag); ?> class="meafe-cta">
        <?php if ( $print_bg ) : ?>
            <div class="meafe-cta-bg-wrapper">
                <img src="<?php echo esc_url( $bg_image ); ?>" class="meafe-cta-bg meafe-bg">
                <div class="meafe-cta-bg-overlay"></div>
            </div>
        <?php endif; ?>
        <?php if ( $print_content ) : ?>
            <div class="meafe-cta-content">
                <div class="meafe-cta-content-inner">
                    <?php if ( ! empty( $settings['bcccs_cta_title'] ) ) : ?>
                        <<?php Utils::print_validated_html_tag( $settings['bcccs_cta_title_tag'] ) . ' ' . $this->get_render_attribute_string( 'bcccs_cta_title' ); ?>>
                            <?php echo esc_html( $settings['bcccs_cta_title'] ); ?>
                        </<?php Utils::print_validated_html_tag( $settings['bcccs_cta_title_tag'] ); ?>>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['bcccs_cta_description'] ) ) : ?>
                        <div <?php echo $this->get_render_attribute_string( 'bcccs_cta_description' ); ?>>
                            <?php echo wp_kses_post( $settings['bcccs_cta_description'] ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['bcccs_cta_button'] ) && ! empty( $link_url ) ) : ?>
                        <div class="meafe-cta-button-wrapper meafe-cta-content-item meafe-content-item">
                        <<?php echo esc_attr( $button_tag ) . ' ' . $this->get_render_attribute_string( 'bcccs_cta_button' ); ?>>
                            <?php echo esc_html( $settings['bcccs_cta_button'] ); ?>
                        </<?php echo esc_attr( $button_tag ); ?>>
                        </div>
                    <?php endif; ?>

                    <?php if ( $settings['bcccs_cta_button_two_show'] == 'yes' && !empty( $settings['bcccs_cta_button_two'] && ! empty( $link_url ) ) ) : ?>
                        <div class="meafe-cta-button-two-wrapper meafe-cta-content-item meafe-content-item">
                        <<?php echo esc_attr( $button_tag ). ' ' . $this->get_render_attribute_string( 'bcccs_cta_button_two' ); ?>>
                            <?php echo esc_html( $settings['bcccs_cta_button_two'] ); ?>
                        </<?php echo esc_attr( $button_tag ); ?>>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        </<?php echo esc_attr($wrapper_tag); ?>>
        <?php
    }

    protected function content_template() {
        ?>
        <#
            var wrapperTag = 'div',
                buttonTag = 'a',
                btnSizeClass = 'meafe-size-' + settings.bcsbs_cta_button_size,
                btntwoSizeClass = 'meafe-size-' + settings.bcsbs_cta_button_two_size,
                printBg = true,
                printContent = true;

            if ( '' !== settings.bccis_cta_bg_image.url ) {
                var bg_image = {
                    id: settings.bccis_cta_bg_image.id,
                    url: settings.bccis_cta_bg_image.url,
                    size: settings.bccis_cta_bg_image_size,
                    dimension: settings.bccis_cta_bg_image_custom_dimension,
                    model: view.getEditModel()
                };

                var bgImageUrl = elementor.imagesManager.getImageUrl( bg_image );
            }

            if ( ! bg_image ) {
                printBg = false;
            }

            view.addRenderAttribute( 'cta_background_image', 'src', bgImageUrl );
            view.addRenderAttribute( 'bcccs_cta_title', 'class', [ 'meafe-cta-title', 'meafe-cta-content-item', 'meafe-content-item' ] );
            view.addRenderAttribute( 'bcccs_cta_description', 'class', [ 'meafe-cta-description', 'meafe-cta-content-item', 'meafe-content-item' ] );
            view.addRenderAttribute( 'bcccs_cta_button', 'class', [ 'meafe-cta-button', 'meafe-button', btnSizeClass ] );

            if( settings.bcccs_cta_btn_link.url != '' ) {

                view.addRenderAttribute( 'bcccs_cta_button', 'href', [ settings.bcccs_cta_btn_link.url ] );
                
                if( settings.bcccs_cta_btn_link.is_external != '' ) {
                    view.addRenderAttribute( 'bcccs_cta_button', 'target', '_blank' );
                }

                if( settings.bcccs_cta_btn_link.nofollow != '' ){
                    view.addRenderAttribute( 'bcccs_cta_button', 'rel', 'nofollow' );
                }
            }

            view.addRenderAttribute( 'bcccs_cta_button_two', 'class', [ 'meafe-cta-button-two', 'meafe-button', btntwoSizeClass ] );

            if( settings.bcccs_cta_btn_two_link.url != '' ) {

                view.addRenderAttribute( 'bcccs_cta_button_two', 'href', [ settings.bcccs_cta_btn_two_link.url ] );
                
                if( settings.bcccs_cta_btn_two_link.is_external != '' ) {
                    view.addRenderAttribute( 'bcccs_cta_button_two', 'target', '_blank' );
                }

                if( settings.bcccs_cta_btn_two_link.nofollow != '' ){
                    view.addRenderAttribute( 'bcccs_cta_button_two', 'rel', 'nofollow' );
                }
            }


            view.addInlineEditingAttributes( 'bcccs_cta_title' );
            view.addInlineEditingAttributes( 'bcccs_cta_description' );
            view.addInlineEditingAttributes( 'bcccs_cta_button' );

            if ( settings.bcccs_cta_title == '' && settings.bcccs_cta_description == '' && settings.bcccs_cta_button == '' ) {
                $print_content = false;
            }
        #>

        <{{ wrapperTag }} class="meafe-cta">

        <# if ( printBg ) { #>
            <div class="meafe-cta-bg-wrapper">
                <img {{{ view.getRenderAttributeString( 'cta_background_image' ) }}} class="meafe-cta-bg meafe-bg">
                <div class="meafe-cta-bg-overlay"></div>
            </div>
        <# } #>

        <# if ( printContent ) { #>
            <div class="meafe-cta-content">
                <div class="meafe-cta-content-inner">
                    <# var titleSizeTag = elementor.helpers.validateHTMLTag( settings.bcccs_cta_title_tag ); #>
                    <# if ( settings.bcccs_cta_title ) { #>
                        <{{ titleSizeTag }} {{{ view.getRenderAttributeString( 'bcccs_cta_title' ) }}}>{{{ settings.bcccs_cta_title }}}</{{ titleSizeTag }}>
                    <# } #>

                    <# if ( settings.bcccs_cta_description ) { #>
                        <div {{{ view.getRenderAttributeString( 'bcccs_cta_description' ) }}}>{{{ settings.bcccs_cta_description }}}</div>
                    <# } #>

                    <# if ( settings.bcccs_cta_button && settings.bcccs_cta_btn_link != '' ) { #>
                        <div class="meafe-cta-button-wrapper meafe-cta-content-item meafe-content-item">
                            <{{ buttonTag }} {{{ view.getRenderAttributeString( 'bcccs_cta_button' ) }}}>{{{ settings.bcccs_cta_button }}}</{{ buttonTag }}>
                        </div>
                    <# } #>

                    <# if ( settings.bcccs_cta_button_two_show == 'yes' && settings.bcccs_cta_button_two != '' && settings.bcccs_cta_btn_two_link != '' ) { #>
                        <div class="meafe-cta-button-two-wrapper meafe-cta-content-item meafe-content-item">
                            <{{ buttonTag }} {{{ view.getRenderAttributeString( 'bcccs_cta_button_two' ) }}}>{{{ settings.bcccs_cta_button_two }}}</{{ buttonTag }}>
                        </div>
                    <# } #>
                </div>
            </div>
        <# } #>
        </{{ wrapperTag }}>
    <?php
    }
}
