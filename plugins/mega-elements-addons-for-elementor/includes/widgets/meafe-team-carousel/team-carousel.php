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

class MEAFE_Team_Carousel extends Widget_Base
{

    public function get_name() {
        return 'meafe-team-carousel';
    }

    public function get_title() {
        return esc_html__( 'Team Carousel', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-team-carousel';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-team-carousel'];
    }

    public function get_script_depends() {
        return ['meafe-team-carousel'];
    }

    protected function register_controls() {

        /**
         * Team Content Settings
        */
        $this->start_controls_section(
            'meafe_team_carousel_content_content_settings',
            [
                'label'     => esc_html__( 'Content Settings', 'mega-elements-addons-for-elementor')
            ]
        );

        $this->add_control(
            'btcccs_team_member_layouts',
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
                'frontend_available' => true
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'btcccs_team_member_thumbnail',
                'default'   => 'full',
            ]
        );

        $team_repeater = new Repeater();

        $team_repeater->add_control(
            'btcccs_team_member_image',
            [
                'label'     => esc_html__( 'Team Member Avatar', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $team_repeater->add_control(
            'btcccs_team_member_title',
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

        $team_repeater->add_control(
            'btcccs_team_member_job_title',
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

        $team_repeater->add_control(
            'btcccs_team_member_bio',
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

        $team_repeater->add_control(
            'btcccs_team_member_facebook_url',
            [
                'label'       => __('Facebook', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::URL,
                'default'     => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'description' => __('Enter Facebook page or profile URL of team member', 'mega-elements-addons-for-elementor'),
            ]
        );

        $team_repeater->add_control(
            'btcccs_team_member_twitter_url',
            [
                'label'       => __('Twitter', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::URL,
                'default'     => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'description' => __('Enter Twitter profile URL of team member', 'mega-elements-addons-for-elementor'),
            ]
        );
        
        $team_repeater->add_control(
            'btcccs_team_member_linkedin_url',
            [
                'label'       => __('Linkedin', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::URL,
                'default'     => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'description' => __('Enter Linkedin profile URL of team member', 'mega-elements-addons-for-elementor'),
            ]
        );

        $team_repeater->add_control(
            'btcccs_team_member_instagram_url',
            [
                'label'       => __('Instagram', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::URL,
                'default'     => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'description' => __('Enter Instagram profile URL of team member', 'mega-elements-addons-for-elementor'),
            ]
        );

        $team_repeater->add_control(
            'btcccs_team_member_youtube_url',
            [
                'label'       => __('YouTube', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::URL,
                'description' => __('Enter YouTube profile URL of team member', 'mega-elements-addons-for-elementor'),
            ]
        );

        $team_repeater->add_control(
            'btcccs_team_member_pinterest_url',
            [
                'label'       => __('Pinterest', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::URL,
                'description' => __('Enter Pinterest profile URL of team member', 'mega-elements-addons-for-elementor'),
            ]
        );

        $team_repeater->add_control(
            'btcccs_team_member_dribbble_url',
            [
                'label'       => __('Dribbble', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::URL,
                'description' => __('Enter Dribbble profile URL of team member', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control( 
            'btcccs_team_member_carousel', 
            array(
                'label'       => esc_html__( 'Team Carousel', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $team_repeater->get_controls(),
                'default'     => array( 
                    array(
                        'btcccs_team_member_title'       => esc_html__( 'John Doe', 'mega-elements-addons-for-elementor' ), 
                        'btcccs_team_member_job_title' => esc_html__( 'Managing Director', 'mega-elements-addons-for-elementor' ), 
                        'btcccs_team_member_bio'       => esc_html__( 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut.', 'mega-elements-addons-for-elementor' ), 
                    ),
                    array(
                        'btcccs_team_member_title'       => esc_html__( 'John Doe', 'mega-elements-addons-for-elementor' ), 
                        'btcccs_team_member_job_title' => esc_html__( 'Managing Director', 'mega-elements-addons-for-elementor' ), 
                        'btcccs_team_member_bio'       => esc_html__( 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut.', 'mega-elements-addons-for-elementor' ), 
                    ),
                    array(
                        'btcccs_team_member_title'       => esc_html__( 'John Doe', 'mega-elements-addons-for-elementor' ), 
                        'btcccs_team_member_job_title' => esc_html__( 'Managing Director', 'mega-elements-addons-for-elementor' ), 
                        'btcccs_team_member_bio'       => esc_html__( 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut.', 'mega-elements-addons-for-elementor' ), 
                    ),
                    array(
                        'btcccs_team_member_title'       => esc_html__( 'John Doe', 'mega-elements-addons-for-elementor' ), 
                        'btcccs_team_member_job_title' => esc_html__( 'Managing Director', 'mega-elements-addons-for-elementor' ), 
                        'btcccs_team_member_bio'       => esc_html__( 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut.', 'mega-elements-addons-for-elementor' ), 
                    ),                     
                ),                
                'title_field' => '{{{ btcccs_team_member_title }}}',
            ) 
        );
        
        $this->add_control(
            'btcccs_team_member_show_carousel_nav',
            [
                'label'     => esc_html__( 'Enable Carousel Navigation', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btcccs_team_member_arrow_prev_icon',
            [
                'label' => __( 'Previous Icon', 'mega-elements-addons-for-elementor' ),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-chevron-left',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'btcccs_team_member_show_carousel_nav' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btcccs_team_member_arrow_next_icon',
            [
                'label' => __( 'Next Icon', 'mega-elements-addons-for-elementor' ),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'btcccs_team_member_show_carousel_nav' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btcccs_team_member_show_carousel_dots',
            [
                'label'     => esc_html__( 'Enable Carousel Dots', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btcccs_team_member_show_carousel_auto',
            [
                'label'     => esc_html__( 'Enable Carousel AutoPlay', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btcccs_team_member_carousel_autoplay_speed',
            [
                'label'     => __( 'Autoplay Speed', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 100,
                'step'      => 100,
                'max'       => 10000,
                'default'   => 3000,
                'description' => __( 'Autoplay speed in milliseconds', 'mega-elements-addons-for-elementor' ),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btcccs_team_member_show_carousel_loop',
            [
                'label'     => esc_html__( 'Enable Carousel Infinite Loop', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btcccs_team_member_title_tag',
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
            'btcccs_team_member_align',
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
                'default'    => 'left',
                'toggle'    => true,
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_section();

        /**
         * Team Image Style
        */
        $this->start_controls_section(
            'meafe_team_carousel_style_image_style',
            [
                'label' => esc_html__( 'Image Style', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btcsis_team_member_image_width',
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
            'btcsis_team_member_image_height',
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
            'btcsis_team_member_image_spacing',
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
            'btcsis_team_member_image_padding',
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
                'name'      => 'btcsis_team_member_image_border',
                'selector'  => '{{WRAPPER}} .meafe-member-figure img'
            ]
        );

        $this->add_responsive_control(
            'btcsis_team_member_image_border_radius',
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
                'name'      => 'btcsis_team_member_image_box_shadow',
                'exclude'   => [
                    'box_shadow_position',
                ],
                'selector'  => '{{WRAPPER}} .meafe-member-figure img'
            ]
        );

        $this->add_control(
            'btcsis_team_member_image_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-figure img' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Team Content Style
        */
        $this->start_controls_section(
            'meafe_team_carousel_style_content_style',
            [
                'label'     => esc_html__( 'Content Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btcscs_team_member_content_padding',
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
            'btcscs_team_member_content_bg_color',
            [
                'label'     => esc_html__( 'Content Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-member-body' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btcscs_team_member_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Name', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btcscs_team_member_title_spacing',
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
            'btcscs_team_member_title_color',
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
                'name'      => 'btcscs_team_member_title_typography',
                'selector'  => '{{WRAPPER}} .meafe-member-name',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'btcscs_team_member_title_text_shadow',
                'selector'  => '{{WRAPPER}} .meafe-member-name',
            ]
        );

        $this->add_control(
            'btcscs_team_member_heading_job_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Job Title', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'btcscs_team_member_job_title_spacing',
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
            'btcscs_team_member_job_title_color',
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
                'name'      => 'btcscs_team_member_job_title_typography',
                'selector'  => '{{WRAPPER}} .meafe-member-position',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'btcscs_team_member_job_title_text_shadow',
                'selector'  => '{{WRAPPER}} .meafe-member-position',
            ]
        );

        $this->add_control(
            'btcscs_team_member_heading_bio',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Short Bio', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'btcscs_team_member_bio_spacing',
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
            'btcscs_team_member_bio_color',
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
                'name'      => 'btcscs_team_member_bio_typography',
                'selector'  => '{{WRAPPER}} .meafe-member-bio',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'btcscs_team_member_bio_text_shadow',
                'selector'  => '{{WRAPPER}} .meafe-member-bio',
            ]
        );

        $this->end_controls_section();

        /**
         * Team Social Style
        */
        $this->start_controls_section(
            'meafe_team_carousel_style_social_profiles_styles',
            [
                'label'     => esc_html__( 'Social Profiles Style', 'mega-elements-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE
            ]
        );      

        $this->start_controls_tabs( 'btcssps_team_members_social_icons_tabs' );

        $this->start_controls_tab( 
            'btcssps_team_members_normal_first', 
            [ 
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor') 
            ] 
        );

        $this->add_control(
            'btcssps_team_members_social_icon_size',
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
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link a' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btcssps_team_members_social_width',
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
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link a' => 'width: {{SIZE}}px',
                ],
            ]
        );

        $this->add_control(
            'btcssps_team_members_social_height',
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
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link a' => 'height: {{SIZE}}px',
                ],
            ]
        );

        $this->add_control(
            'btcssps_team_members_social_line_height',
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
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link a' => 'line-height: {{SIZE}}px',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'btcssps_team_members_hover_first', 
            [ 
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor') 
            ] 
        );

         $this->add_control(
            'btcssps_team_members_social_icon_size_hover',
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
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link a:hover' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btcssps_team_members_social_width_hover',
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
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link a:hover' => 'width: {{SIZE}}px',
                ],
            ]
        );

        $this->add_control(
            'btcssps_team_members_social_height_hover',
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
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link a:hover' => 'height: {{SIZE}}px',
                ],
            ]
        );

        $this->add_control(
            'btcssps_team_members_social_line_height_hover',
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
                    '{{WRAPPER}} .meafe-team-member-social-profiles .meafe-team-member-social-link a:hover' => 'line-height: {{SIZE}}px',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'btcssps_team_members_social_profiles_padding',
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
            'btcssps_team_members_social_icons_spacing',
            [
                'label'      => esc_html__( 'Social Icon Spacing', 'mega-elements-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .meafe-member-body > .meafe-team-member-social-profiles li.meafe-team-member-social-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );


        $this->start_controls_tabs( 'btcssps_team_members_social_icons_style_tabs' );

        $this->start_controls_tab( 
            'btcssps_team_members_normal', 
            [ 
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor') 
            ] 
        );

        $this->add_control(
            'btcssps_team_members_social_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-link a' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->add_control(
            'btcssps_team_members_social_icon_background',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-link a' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'btcssps_team_members_social_icon_border',
                'selector'  => '{{WRAPPER}} .meafe-team-member-social-link',
            ]
        );
        
        $this->add_control(
            'btcssps_team_members_social_icon_border_radius',
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
            'btcssps_team_members_social_icon_hover', 
            [ 
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor') 
            ] 
        );

        $this->add_control(
            'btcssps_team_members_social_icon_hover_color',
            [
                'label'     => esc_html__( 'Icon Hover Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-link a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btcssps_team_members_social_icon_hover_background',
            [
                'label'     => esc_html__( 'Hover Background Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-link a:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btcssps_team_members_social_icon_hover_border_color',
            [
                'label'     => esc_html__( 'Hover Border Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-team-member-social-link a:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Arrows
        */
        $this->start_controls_section(
            'meafe_team_carousel_style_nav_arrow',
            [
                'label' => __( 'Navigation :: Arrow', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btcsna_team_member_arrow_size',
            [
                'label' => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsna_team_member_arrow_width',
            [
                'label' => __( 'Width', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-wrap .nav' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btcsna_team_member_arrow_border',
                'selector' => '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next',
            ]
        );

        $this->add_responsive_control(
            'btcsna_team_member_arrow_border_radius',
            [
                'label' => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs( 'btcsna_team_member_tabs_arrow' );

        $this->start_controls_tab(
            'btcsna_team_member_tab_arrow_normal',
            [
                'label' => __( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'btcsna_team_member_arrow_color',
            [
                'label' => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btcsna_team_member_arrow_bg_color',
            [
                'label' => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'btcsna_team_member_tab_arrow_hover',
            [
                'label' => __( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'btcsna_team_member_arrow_hover_color',
            [
                'label' => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev:hover, {{WRAPPER}} .meafa-navigation-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btcsna_team_member_arrow_hover_bg_color',
            [
                'label' => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev:hover, {{WRAPPER}} .meafa-navigation-next:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_team_carousel_style_nav_dots',
            [
                'label' => __( 'Navigation :: Dots', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btcsnd_team_member_dots_nav_spacing',
            [
                'label' => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsnd_team_member_dots_nav_align',
            [
                'label' => __( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->start_controls_tabs( 'btcsnd_team_member_tabs_dots' );
        $this->start_controls_tab(
            'btcsnd_team_member_tab_dots_normal',
            [
                'label' => __( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'btcsnd_team_member_dots_nav_size',
            [
                'label' => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'btcsnd_team_member_dots_nav_color',
            [
                'label' => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'btcsnd_team_member_tab_dots_active',
            [
                'label' => __( 'Active', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'btcsnd_team_member_dots_nav_active_color',
            [
                'label' => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    public function get_nav_details(){
        $settings  = $this->get_settings_for_display();
        $nav      = $settings['btcccs_team_member_show_carousel_nav'];
        $nav_prev = $settings['btcccs_team_member_arrow_prev_icon'];
        $nav_next = $settings['btcccs_team_member_arrow_next_icon'];

        if( $nav ) {
            $return_all = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_alls = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_all_start = [ '', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_all_end = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '' ];
            
            if( $nav_prev['library'] != 'svg' && $nav_next['library'] != 'svg' ) {
                return ( [ '<i class="' . esc_attr($nav_prev['value']) . '" aria-hidden="true"></i>', '<i class="' . esc_attr($nav_next['value']) . '" aria-hidden="true"></i>' ] );                    
            }
            
            if ( $nav_prev['library'] == 'svg' && $nav_next['library'] == 'svg' ){
                return ( [ '<img src="' . esc_url($nav_prev['value']['url']) . '">', '<img src="' . esc_url($nav_next['value']['url']) . '">' ] );
            }
            
            if ( $nav_prev['library'] == '' && $nav_next['library'] == 'svg' ){
                array_pop($return_all_start);
                array_push($return_all_start, esc_url($nav_next['value']['url']));
                return ( [ '', '<img src="' . $return_all_start[1] . '">' ] );
                // return return_all_start;
            }

            if ( $nav_prev['library'] != 'svg' && $nav_next['library'] == 'svg' ){
                array_pop($return_all);
                array_push($return_all, '<img src="' . esc_url($nav_next['value']['url']) . '">');
                return $return_all;
            }
            
            if ( $nav_prev['library'] == 'svg' && $nav_next['library'] == '' ){
                array_reverse($return_all_end);
                array_pop($return_all_end);
                array_push($return_all_end, esc_url($nav_prev['value']['url']));
                array_reverse($return_all_end);
                return ( [ '<img src="' . $return_all_end[0] . '">', '' ] );
            }

            if ( $nav_prev['library'] == 'svg' && $nav_next['library'] != 'svg' ){
                array_reverse($return_alls);
                array_pop($return_alls);
                array_push($return_alls, '<img src="' . esc_url($nav_prev['value']['url']) . '">');
                array_reverse($return_alls);
                return $return_alls;
            }   
        }
        
        return ( [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ] );

    }

    protected function render() {
        $settings  = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        $nav_icons = $this->get_nav_details();
        $nav       = $settings['btcccs_team_member_show_carousel_nav'];
        $dots      = $settings['btcccs_team_member_show_carousel_dots'];

        $this->add_inline_editing_attributes( 'btcccs_team_member_title', 'basic' );
        $this->add_render_attribute( 'btcccs_team_member_title', 'class', 'meafe-member-name' );

        $this->add_inline_editing_attributes( 'btcccs_team_member_job_title', 'basic' );
        $this->add_render_attribute( 'btcccs_team_member_job_title', 'class', 'meafe-member-position' );

        $this->add_inline_editing_attributes( 'btcccs_team_member_bio', 'intermediate' );
        $this->add_render_attribute( 'btcccs_team_member_bio', 'class', 'meafe-member-bio' );

        $allowedOptions = ['1', '2', '3'];
        $layouts_safe = in_array($settings['btcccs_team_member_layouts'], $allowedOptions) ? $settings['btcccs_team_member_layouts'] : '1';
        ?>
        <div id=<?php echo esc_attr( $widget_id ); ?> class="meafe-team-carousel-wrapper-main layout-<?php echo esc_attr($layouts_safe); ?>">
            <div class="meafe-team-inner-wrap">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ( $settings['btcccs_team_member_carousel'] as $index => $team_carousel ) { ?>
                            <div class="meafe-team-wrap swiper-slide">
                                <?php if( $settings['btcccs_team_member_layouts'] == '3' ) {
                                    echo '<div class="meafe-team-wrap-inner">';
                                    echo '<div class="meafe-team-flip-card-inner">';
                                } ?>
                                <?php if ( $team_carousel['btcccs_team_member_image']['url'] || $team_carousel['btcccs_team_member_image']['id'] ) : ?>
                                    <div class="meafe-team-fig">
                                        <figure class="meafe-member-figure">
                                            <?php echo Group_Control_Image_Size::get_attachment_image_html( $team_carousel, 'btcccs_team_member_thumbnail', 'btcccs_team_member_image' ); ?>
                                        </figure>
                                    </div>
                                <?php endif; ?>
                                <div class="meafe-member-body">
                                    <?php
                                    if ( $team_carousel['btcccs_team_member_title'] ) :
                                        printf( '<%1$s %2$s>%3$s</%1$s>',
                                            Utils::validate_html_tag( $settings['btcccs_team_member_title_tag'] ),
                                            $this->get_render_attribute_string( 'btcccs_team_member_title' ),
                                            esc_html($team_carousel['btcccs_team_member_title'])
                                        );
                                    endif; ?>
                                    <?php if ( $team_carousel['btcccs_team_member_job_title' ] ) : ?>
                                        <div <?php $this->print_render_attribute_string( 'btcccs_team_member_job_title' ); ?>><?php echo esc_html($team_carousel['btcccs_team_member_job_title' ]); ?></div>
                                    <?php endif; ?>
                                    <?php if ( $team_carousel['btcccs_team_member_bio'] && $settings['btcccs_team_member_layouts'] == '2' ) : ?>
                                        <div <?php $this->print_render_attribute_string( 'btcccs_team_member_bio' ); ?>>
                                            <p><?php echo wp_kses_post($team_carousel['btcccs_team_member_bio']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <ul class="meafe-team-member-social-profiles">
                                        <?php
                                        if ( $team_carousel['btcccs_team_member_facebook_url']['url'] ) : ?>
                                            <li class="meafe-team-member-social-link">
                                                <a href="<?php echo esc_url( $team_carousel['btcccs_team_member_facebook_url']['url'] ); ?>"<?php echo ( $team_carousel['btcccs_team_member_facebook_url']['is_external'] ) ? ' target="_blank"' : ''; ?>>
                                                    <i class="fab fa-facebook-square"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ( $team_carousel['btcccs_team_member_twitter_url']['url'] ) : ?>
                                            <li class="meafe-team-member-social-link">
                                                <a href="<?php echo esc_url( $team_carousel['btcccs_team_member_twitter_url']['url'] ); ?>"<?php echo ( $team_carousel['btcccs_team_member_twitter_url']['is_external'] ) ? ' target="_blank"' : ''; ?>>
                                                    <i class="fab fa-twitter-square"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ( $team_carousel['btcccs_team_member_linkedin_url']['url'] ) : ?>
                                            <li class="meafe-team-member-social-link">
                                                <a href="<?php echo esc_url( $team_carousel['btcccs_team_member_linkedin_url']['url'] ); ?>"<?php echo ( $team_carousel['btcccs_team_member_linkedin_url']['is_external'] ) ? ' target="_blank"' : ''; ?>>
                                                    <i class="fab fa-linkedin"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ( $team_carousel['btcccs_team_member_instagram_url']['url'] ) : ?>
                                            <li class="meafe-team-member-social-link">
                                                <a href="<?php echo esc_url( $team_carousel['btcccs_team_member_instagram_url']['url'] ); ?>"<?php echo ( $team_carousel['btcccs_team_member_instagram_url']['is_external'] ) ? ' target="_blank"' : ''; ?>>
                                                    <i class="fab fa-instagram"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ( $team_carousel['btcccs_team_member_pinterest_url']['url'] ) : ?>
                                            <li class="meafe-team-member-social-link">
                                                <a href="<?php echo esc_url( $team_carousel['btcccs_team_member_pinterest_url']['url'] ); ?>"<?php echo ( $team_carousel['btcccs_team_member_pinterest_url']['is_external'] ) ? ' target="_blank"' : ''; ?>>
                                                    <i class="fab fa-pinterest"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ( $team_carousel['btcccs_team_member_youtube_url']['url'] ) : ?>
                                            <li class="meafe-team-member-social-link">
                                                <a href="<?php echo esc_url( $team_carousel['btcccs_team_member_youtube_url']['url'] ); ?>"<?php echo ( $team_carousel['btcccs_team_member_youtube_url']['is_external'] ) ? ' target="_blank"' : ''; ?>>
                                                    <i class="fab fa-youtube"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ( $team_carousel['btcccs_team_member_dribbble_url']['url'] ) : ?>
                                            <li class="meafe-team-member-social-link">
                                                <a href="<?php echo esc_url( $team_carousel['btcccs_team_member_dribbble_url']['url'] ); ?>"<?php echo ( $team_carousel['btcccs_team_member_dribbble_url']['is_external'] ) ? ' target="_blank"' : ''; ?>>
                                                    <i class="fab fa-dribbble"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                    <?php if ( $team_carousel['btcccs_team_member_bio'] && $settings['btcccs_team_member_layouts'] == '1' ) : ?>
                                        <div <?php $this->print_render_attribute_string( 'btcccs_team_member_bio' ); ?>>
                                            <p><?php echo wp_kses_post($team_carousel['btcccs_team_member_bio']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if( $settings['btcccs_team_member_layouts'] == '3' ) {
                                    echo '</div>';
                                } ?>
                                <?php if( $settings['btcccs_team_member_layouts'] == '3' && $team_carousel['btcccs_team_member_bio'] ) { ?>
                                    <div class="meafe-team-flip-card-outer">
                                        <div <?php $this->print_render_attribute_string( 'btcccs_team_member_bio' ); ?>>
                                            <p><?php echo wp_kses_post($team_carousel['btcccs_team_member_bio']); ?></p>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if( $settings['btcccs_team_member_layouts'] == '3' ) {
                                    echo '</div>';
                                } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if($dots === 'yes') { ?>
                    <!-- If we need pagination -->
                    <div class="team meafa-swiper-pagination"></div>
                <?php }
                
                if($nav === 'yes') { ?>
                    <!-- If we need navigation buttons -->
                    <div class="meafa-navigation-wrap">
                        <div class="team meafa-navigation-prev nav">
                            <?php echo $nav_icons[0]; ?>
                        </div>
                        <div class="team meafa-navigation-next nav">
                            <?php echo $nav_icons[1]; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
    }
}
