<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

class MEAFE_Team extends Widget_Base
{

    public function get_name() {
        return 'meafe-team';
    }

    public function get_title() {
        return esc_html__( 'Team', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-team';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-team'];
    }

    protected function register_controls() {

        /**
         * Team Content Settings
        */
        $this->start_controls_section(
            'meafe_team_content_content_settings',
            [
                'label'     => esc_html__( 'Content Settings', 'mega-elements-addons-for-elementor')
            ]
        );

        $this->add_control(
            'btccs_team_member_layouts',
            [
                'label'         => esc_html__( 'Select Layout', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => '1',
                'label_block'   => false,
                'options'       => [
                    '1'       => esc_html__( 'Layout One', 'mega-elements-addons-for-elementor' ),
                    '2'       => esc_html__( 'Layout Two', 'mega-elements-addons-for-elementor' ),
                    '3'       => esc_html__( 'Layout Three', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'btccs_team_member_image',
            [
                'label'     => esc_html__( 'Team Member Avatar', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'btccs_team_member_thumbnail',
                'default'   => 'full',
                'condition' => [
                    'btccs_team_member_image[url]!' => '',
                ],
            ]
        );

        $this->add_control(
            'btccs_team_member_title',
            [
                'label'         => esc_html__( 'Name', 'mega-elements-addons-for-elementor' ),
                'label_block'   => true,
                'type'          => Controls_Manager::TEXT,
                'default'       => esc_html__( 'John Doe', 'mega-elements-addons-for-elementor'),
                'separator'     => 'before',
                'dynamic'       => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'btccs_team_member_job_title',
            [
                'label'         => esc_html__( 'Designation', 'mega-elements-addons-for-elementor' ),
                'label_block'   => true,
                'type'          => Controls_Manager::TEXT,
                'default'       => esc_html__( 'Managing Director', 'mega-elements-addons-for-elementor' ),
                'dynamic'       => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'btccs_team_member_bio',
            [
                'label'         => esc_html__( 'Bio', 'mega-elements-addons-for-elementor' ),
                'description'   => esc_html__( 'Add team member description here. Remove the text if not necessary.', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::TEXTAREA,
                'default'       => esc_html__( 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut', 'mega-elements-addons-for-elementor' ),
                'rows'          => 5,
                'dynamic'       => [
                    'active' => true,
                ]
            ]
        );        
        
        $this->add_control(
            'btccs_team_member_title_tag',
            [
                'label'         => esc_html__( 'Title HTML Tag', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'h1'  => [
                        'title' => esc_html__( 'H1', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => esc_html__( 'H2', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => esc_html__( 'H3', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => esc_html__( 'H4', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => esc_html__( 'H5', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => esc_html__( 'H6', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-editor-h6'
                    ]
                ],
                'default'       => 'h3',
                'toggle'        => false,
                'separator'     => 'before',
            ]
        );

        $this->add_responsive_control(
            'btccs_team_member_align',
            [
                'label'     => esc_html__( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'      => [
                        'title' => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title' => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'     => [
                        'title' => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'toggle'    => true,
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_section();

        /**
         * Team Social Settings
        */
        $this->start_controls_section(
            'meafe_team_content_social_settings',
            [
                'label'     => esc_html__( 'Social Settings', 'mega-elements-addons-for-elementor')
            ]
        );

        $this->add_control(
            'btcss_team_member_enable_social_profiles',
            [
                'label'     => esc_html__( 'Display Social Profiles?', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
            ]
        );

        $social_profile_repeater = new Repeater();

        $social_profile_repeater->add_control(
            'btcss_team_member_social_new',
            [
                'label'     => esc_html__( 'Icon', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::ICONS,
                'fa4compatibility' => 'btcss_team_member_social',
                'default'   => [
                    'value'   => 'fab fa-wordpress',
                    'library' => 'fa-brands',
                ],
            ]
        ); 

        $social_profile_repeater->add_control(
            'btcss_team_member_link',
            [
                'label'     => esc_html__( 'Link', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '',
                    'is_external' => 'true',
                ],
                'placeholder' => esc_html__( 'Place URL here', 'mega-elements-addons-for-elementor'),
            ]
        );        
        
        $this->add_control(
            'btcss_team_member_social_profile_links',
            array(
                'type'      => Controls_Manager::REPEATER,
                'fields'    => $social_profile_repeater->get_controls(),
                'condition' => [
                    'btcss_team_member_enable_social_profiles!' => '',
                ],
                'default'   => array(
                    array(
                        'btcss_team_member_social_new'  => array(
                            'value'   => 'fab fa-facebook',
                            'library' => 'fa-brands'
                        )
                    ),
                    array(
                        'btcss_team_member_social_new'  => array(
                            'value'   => 'fab fa-twitter',
                            'library' => 'fa-brands'
                        )
                    ),
                    array(
                        'btcss_team_member_social_new'  => array(
                            'value'   => 'fab fa-instagram',
                            'library' => 'fa-brands'
                        )
                    ),
                    array(
                        'btcss_team_member_social_new'  => array(
                            'value'   => 'fab fa-linkedin',
                            'library' => 'fa-brands'
                        )
                    ),
                ),
                'title_field' => '<i class="{{ btcss_team_member_social_new.value }}"></i>',
            )
        );

        $this->end_controls_section();

        /**
         * Team Image Style
        */
        $this->start_controls_section(
            'meafe_team_style_image_style',
            [
                'label' => esc_html__( 'Image Style', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btsis_team_member_image_width',
            [
                'label'     => esc_html__( 'Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%'],
                'range'     => [
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 100,
                        'max' => 700,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-figure img' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'btsis_team_member_image_height',
            [
                'label'     => esc_html__( 'Height', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'     => [
                    'px' => [
                        'min' => 100,
                        'max' => 700,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-figure img' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'btsis_team_member_image_spacing',
            [
                'label'     => esc_html__( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-figure' => 'margin-bottom: {{SIZE}}{{UNIT}} !important',
                ],
            ]
        );

        $this->add_responsive_control(
            'btsis_team_member_image_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'btsis_team_member_image_border',
                'selector'  => '{{WRAPPER}} .meafe-member-figure img'
            ]
        );

        $this->add_responsive_control(
            'btsis_team_member_image_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'btsis_team_member_image_box_shadow',
                'exclude'   => [
                    'box_shadow_position',
                ],
                'selector'  => '{{WRAPPER}} .meafe-member-figure img'
            ]
        );

        $this->add_control(
            'btsis_team_member_image_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-figure img' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-team-wrapper-main.layout-3 .meafe-team-flip-card-outer' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Team Content Style
        */
        $this->start_controls_section(
            'meafe_team_style_content_style',
            [
                'label'     => esc_html__( 'Content Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btscs_team_member_content_padding',
            [
                'label'     => esc_html__( 'Content Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btscs_team_member_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Name', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btscs_team_member_title_spacing',
            [
                'label'     => esc_html__( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-name' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btscs_team_member_title_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'btscs_team_member_title_typography',
                'selector'  => '{{WRAPPER}} .meafe-member-name',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'btscs_team_member_title_text_shadow',
                'selector'  => '{{WRAPPER}} .meafe-member-name',
            ]
        );

        $this->add_control(
            'btscs_team_member_heading_job_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Job Title', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'btscs_team_member_job_title_spacing',
            [
                'label'     => esc_html__( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-position' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btscs_team_member_job_title_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-position' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'btscs_team_member_job_title_typography',
                'selector'  => '{{WRAPPER}} .meafe-member-position',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'btscs_team_member_job_title_text_shadow',
                'selector'  => '{{WRAPPER}} .meafe-member-position',
            ]
        );

        $this->add_control(
            'btscs_team_member_heading_bio',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Short Bio', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'btscs_team_member_bio_spacing',
            [
                'label'     => esc_html__( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-bio' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btscs_team_member_bio_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-bio' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'btscs_team_member_bio_typography',
                'selector'  => '{{WRAPPER}} .meafe-member-bio',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'btscs_team_member_bio_text_shadow',
                'selector'  => '{{WRAPPER}} .meafe-member-bio',
            ]
        );

        $this->end_controls_section();

        /**
         * Team Social Style
        */
        $this->start_controls_section(
            'meafe_team_style_social_profiles_styles',
            [
                'label'     => esc_html__( 'Social Profiles Style', 'mega-elements-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE
            ]
        );      

        $this->start_controls_tabs( 'btssps_team_members_social_icons_tabs' );

        $this->start_controls_tab( 
            'btssps_team_members_normal_first', 
            [ 
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor') 
            ] 
        );

        $this->add_control(
            'btssps_team_members_social_icon_size',
            [
                'label'     => esc_html__( 'Icon Size', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default'   => [
                    'size'  => 20,
                    'unit'  => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btssps_team_members_social_width',
            [
                'label'     => esc_html__( 'Width', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default'   => [
                    'size'  => '',
                    'unit'  => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link' => 'width: {{SIZE}}px',
                ],
            ]
        );

        $this->add_control(
            'btssps_team_members_social_height',
            [
                'label'     => esc_html__( 'Height', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default'   => [
                    'size'  => '',
                    'unit'  => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link' => 'height: {{SIZE}}px',
                ],
            ]
        );

        $this->add_control(
            'btssps_team_members_social_line_height',
            [
                'label'     => esc_html__( 'Line Height', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default'   => [
                    'size'  => '',
                    'unit'  => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link' => 'line-height: {{SIZE}}px',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'btssps_team_members_hover_first', 
            [ 
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor') 
            ] 
        );

         $this->add_control(
            'btssps_team_members_social_icon_size_hover',
            [
                'label'     => esc_html__( 'Icon Size', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default'   => [
                    'size'  => '',
                    'unit'  => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link:hover' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btssps_team_members_social_width_hover',
            [
                'label'     => esc_html__( 'Width', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default'   => [
                    'size'  => '',
                    'unit'  => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link:hover' => 'width: {{SIZE}}px',
                ],
            ]
        );

        $this->add_control(
            'btssps_team_members_social_height_hover',
            [
                'label'     => esc_html__( 'Height', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default'   => [
                    'size'  => '',
                    'unit'  => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link:hover' => 'height: {{SIZE}}px',
                ],
            ]
        );

        $this->add_control(
            'btssps_team_members_social_line_height_hover',
            [
                'label'     => esc_html__( 'Line Height', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default'   => [
                    'size'  => '',
                    'unit'  => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link:hover' => 'line-height: {{SIZE}}px',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'btssps_team_members_social_profiles_padding',
            [
                'label'     => esc_html__( 'Social Profiles Spacing', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-body > .meafe-team-member-social-profiles' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'btssps_team_members_social_icons_spacing',
            [
                'label'      => esc_html__( 'Social Icon Spacing', 'mega-elements-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .meafe-member-body > .meafe-team-member-social-profiles li.meafe-team-member-social-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );


        $this->start_controls_tabs( 'btssps_team_members_social_icons_style_tabs' );

        $this->start_controls_tab( 
            'btssps_team_members_normal', 
            [ 
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor') 
            ] 
        );

        $this->add_control(
            'btssps_team_members_social_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-link' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->add_control(
            'btssps_team_members_social_icon_background',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-link' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'btssps_team_members_social_icon_border',
                'selector'  => '{{WRAPPER}} .meafe-team-member-social-link',
            ]
        );
        
        $this->add_control(
            'btssps_team_members_social_icon_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-link' => 'border-radius: {{SIZE}}px',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab( 
            'btssps_team_members_social_icon_hover', 
            [ 
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor') 
            ] 
        );

        $this->add_control(
            'btssps_team_members_social_icon_hover_color',
            [
                'label'     => esc_html__( 'Icon Hover Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-link:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btssps_team_members_social_icon_hover_background',
            [
                'label'     => esc_html__( 'Hover Background Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-link:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btssps_team_members_social_icon_hover_border_color',
            [
                'label'     => esc_html__( 'Hover Border Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-link:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'btccs_team_member_title', 'basic' );
        $this->add_render_attribute( 'btccs_team_member_title', 'class', 'meafe-member-name' );

        $this->add_inline_editing_attributes( 'btccs_team_member_job_title', 'basic' );
        $this->add_render_attribute( 'btccs_team_member_job_title', 'class', 'meafe-member-position' );

        $this->add_inline_editing_attributes( 'btccs_team_member_bio', 'intermediate' );
        $this->add_render_attribute( 'btccs_team_member_bio', 'class', 'meafe-member-bio' );

        $allowedOptions = ['1', '2', '3'];
        $layouts_safe = in_array($settings['btccs_team_member_layouts'], $allowedOptions) ? $settings['btccs_team_member_layouts'] : '1';
        ?>
        <div class="meafe-team-wrapper-main layout-<?php echo esc_attr($layouts_safe); ?>">
            <div class="meafe-team-inner-wrap">
                <div class="meafe-team-wrap">

                    <?php if( $settings['btccs_team_member_layouts'] == '3' ) {
                        echo '<div class="meafe-team-wrap-inner">';
                        echo '<div class="meafe-team-flip-card-inner">';
                    } ?>

                    <?php if ( $settings['btccs_team_member_image']['url'] || $settings['btccs_team_member_image']['id'] ) : ?>
                        <div class="meafe-team-fig">
                            <figure class="meafe-member-figure">
                                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'btccs_team_member_thumbnail', 'btccs_team_member_image' ); ?>
                            </figure>
                        </div>
                    <?php endif; ?>

                    <div class="meafe-member-body">
                        <?php
                        if ( $settings['btccs_team_member_title'] ) :
                            printf( '<%1$s %2$s>%3$s</%1$s>',
                                Utils::validate_html_tag( $settings['btccs_team_member_title_tag'] ),
                                $this->get_render_attribute_string( 'btccs_team_member_title' ),
                                esc_html($settings['btccs_team_member_title'])
                            );
                        endif; ?>

                        <?php if ( $settings['btccs_team_member_job_title' ] ) : ?>
                            <div <?php $this->print_render_attribute_string( 'btccs_team_member_job_title' ); ?>><?php echo esc_html($settings['btccs_team_member_job_title' ]); ?></div>
                        <?php endif; ?>
                        
                        <?php if ( $settings['btccs_team_member_bio'] && $settings['btccs_team_member_layouts'] == '2' ) : ?>
                            <div <?php $this->print_render_attribute_string( 'btccs_team_member_bio' ); ?>>
                                <p><?php echo wp_kses_post($settings['btccs_team_member_bio']); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $settings['btcss_team_member_enable_social_profiles'] ) ): ?>
                            <ul class="meafe-team-member-social-profiles">
                                <?php foreach ( $settings['btcss_team_member_social_profile_links'] as $item ) : ?>
                                    <?php $icon_migrated = isset( $item['__fa4_migrated']['btcss_team_member_social_new'] );
                                    $icon_is_new = empty( $item['btcss_team_member_social']); ?>
                                    <?php if ( ! empty( $item['btcss_team_member_social'] ) || !empty( $item['btcss_team_member_social_new'] ) ) : ?>
                                        <?php $target = $item['btcss_team_member_link']['is_external'] ? ' target=_blank' : ''; ?>
                                        <li class="meafe-team-member-social-link">
                                            <?php if ( ! empty( $item['btcss_team_member_link'] ) && $item['btcss_team_member_link']['url'] ) : ?>
                                                <a href="<?php echo esc_url( $item['btcss_team_member_link']['url'] ); ?>" <?php echo esc_attr($target); ?>>
                                            <?php endif; ?>
                                                <?php if ( $icon_is_new || $icon_migrated ) { ?>
                                                    <?php if( isset( $item['btcss_team_member_social_new']['value']['url'] ) ) : ?>
                                                        <img src="<?php echo esc_url( $item['btcss_team_member_social_new']['value']['url'] ); ?>" alt="<?php echo esc_attr( $item['btccs_team_member_title'] ); ?>" />
                                                    <?php else : ?>
                                                        <i class="<?php echo esc_attr( $item['btcss_team_member_social_new']['value'] ); ?>"></i>
                                                    <?php endif; ?>
                                                <?php } else { ?>
                                                    <i class="<?php echo esc_attr( $item['btcss_team_member_social'] ); ?>"></i>
                                                <?php } ?>
                                            <?php if ( ! empty( $item['btcss_team_member_link'] ) && $item['btcss_team_member_link']['url'] ) : ?>
                                                </a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?> 

                        <?php if ( $settings['btccs_team_member_bio'] && $settings['btccs_team_member_layouts'] == '1' ) : ?>
                            <div <?php $this->print_render_attribute_string( 'btccs_team_member_bio' ); ?>>
                                <p><?php echo wp_kses_post($settings['btccs_team_member_bio']); ?></p>
                            </div>
                        <?php endif; ?>                       
                    </div>

                    <?php if( $settings['btccs_team_member_layouts'] == '3' ) {
                        echo '</div>';
                    } ?>

                    <?php if( $settings['btccs_team_member_layouts'] == '3' && $settings['btccs_team_member_bio'] ) { ?>
                        <div class="meafe-team-flip-card-outer">
                            <div <?php $this->print_render_attribute_string( 'btccs_team_member_bio' ); ?>>
                                <p><?php echo wp_kses_post($settings['btccs_team_member_bio']); ?></p>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if( $settings['btccs_team_member_layouts'] == '3' ) {
                        echo '</div>';
                    } ?>
                </div>
            </div>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        view.addInlineEditingAttributes( 'btccs_team_member_title', 'basic' );
        view.addRenderAttribute( 'btccs_team_member_title', 'class', 'meafe-member-name' );

        view.addInlineEditingAttributes( 'btccs_team_member_job_title', 'basic' );
        view.addRenderAttribute( 'btccs_team_member_job_title', 'class', 'meafe-member-position' );

        view.addInlineEditingAttributes( 'btccs_team_member_bio', 'intermediate' );
        view.addRenderAttribute( 'btccs_team_member_bio', 'class', 'meafe-member-bio' );

        var iconsHTML = {},
            migrated = {},
            allowedLayouts = ['1', '2', '3'];

        function validateSelectOptions(option) {
            return allowedLayouts.some(element => element === option) ? option : '1';
        }
        #>

        <div class="meafe-team-wrapper-main layout-{{{validateSelectOptions(settings.btccs_team_member_layouts)}}}">
            <div class="meafe-team-inner-wrap">
                <div class="meafe-team-wrap">

                    <# if( settings.btccs_team_member_layouts == '3' ) { #>
                        <div class="meafe-team-wrap-inner">
                        <div class="meafe-team-flip-card-inner">
                    <# }

                    if ( settings.btccs_team_member_image.url || settings.btccs_team_member_image.id ) {
                        var image = {
                            id: settings.btccs_team_member_image.id,
                            url: settings.btccs_team_member_image.url,
                            size: settings.btccs_team_member_thumbnail_size,
                            dimension: settings.btccs_team_member_thumbnail_custom_dimension,
                            model: view.getEditModel()
                        };

                        var image_url = elementor.imagesManager.getImageUrl( image );
                        #>
                        <div class="meafe-team-fig">
                            <figure class="meafe-member-figure">
                                <img src="{{ image_url }}">
                            </figure>
                        </div>
                    <# } #>
                    <div class="meafe-member-body">
                        <# var titleSizeTag = elementor.helpers.validateHTMLTag( settings.btccs_team_member_title_tag ); #>
                        <# if (settings.btccs_team_member_title) { #>
                            <{{ titleSizeTag }} {{{ view.getRenderAttributeString( 'btccs_team_member_title' ) }}}>{{ settings.btccs_team_member_title }}</{{ titleSizeTag }}>
                        <# } #>
                        <# if (settings.btccs_team_member_job_title) { #>
                            <div {{{ view.getRenderAttributeString( 'btccs_team_member_job_title' ) }}}>{{ settings.btccs_team_member_job_title }}</div>
                        <# } #>
                        <# if (settings.btccs_team_member_bio && settings.btccs_team_member_layouts == '2' ) { #>
                            <div {{{ view.getRenderAttributeString( 'btccs_team_member_bio' ) }}}>
                                <p>{{{ settings.btccs_team_member_bio }}}</p>
                            </div>
                        <# } #>
                        <# if (settings.btcss_team_member_enable_social_profiles) { #>
                            <ul class="meafe-team-member-social-profiles">
                                <# _.each( settings.btcss_team_member_social_profile_links, function( item, index ) { #>
                                    <li class="meafe-team-member-social-link">
                                        <# if ( item.btcss_team_member_link && item.btcss_team_member_link.url ) { 
                                            var target = item.btcss_team_member_link.is_external ? ' target="_blank"' : '';
                                            #>
                                            <a href="{{ item.btcss_team_member_link.url }}" {{{ target }}}>
                                        <# } #>
                                            <# if ( item.btcss_team_member_social || item.btcss_team_member_social_new.value ) { #>
                                                <#
                                                    iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.btcss_team_member_social_new, { 'aria-hidden': true }, 'i', 'object' );
                                                    migrated[ index ] = elementor.helpers.isIconMigrated( item, 'btcss_team_member_social_new' );
                                                    if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.btcss_team_member_social || migrated[ index ] ) ) { #>
                                                        <# if ( item.btcss_team_member_social_new.value.url ) { #>
                                                            <img src="{{ item.btcss_team_member_social_new.value.url }}" alt="{{{ item.btccs_team_member_title }}}" />
                                                        <# }else{ #>
                                                            {{{ iconsHTML[ index ].value }}}
                                                        <# } #>  
                                                    <# } else { #>
                                                        <i class="{{ item.btcss_team_member_social }}" aria-hidden="true"></i>
                                                    <# }
                                                #>
                                            <# } #>
                                        <# if ( item.btcss_team_member_link && item.btcss_team_member_link.url ) { #>
                                            </a>
                                        <# } #>
                                    </li>
                                <# } ); #>
                            </ul>
                        <# } #>
                        <# if (settings.btccs_team_member_bio && settings.btccs_team_member_layouts == '1' ) { #>
                            <div {{{ view.getRenderAttributeString( 'btccs_team_member_bio' ) }}}>
                                <p>{{{ settings.btccs_team_member_bio }}}</p>
                            </div>
                        <# } #>
                    </div>

                    <# if( settings.btccs_team_member_layouts == '3' ) { #>
                        </div>
                    <# }

                    if( settings.btccs_team_member_layouts == '3' && settings.btccs_team_member_bio ) { #>
                        <div class="meafe-team-flip-card-outer">
                            <div {{{ view.getRenderAttributeString( 'btccs_team_member_bio' ) }}}>
                                <p>{{{ settings.btccs_team_member_bio }}}</p>
                            </div>
                        </div>
                    <# } 

                    if( settings.btccs_team_member_layouts == '3' ) { #>
                        </div>
                    <# } #>
                </div>
            </div>
        </div>
        <?php
    }
}
