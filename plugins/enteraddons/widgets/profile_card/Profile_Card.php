<?php
namespace Enteraddons\Widgets\Profile_Card;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Enteraddons elementor Profile Card widget.
 *
 * @since 1.0
 */
class Profile_Card extends Widget_Base {

	public function get_name() {
		return 'enteraddons-profile-card';
	}

	public function get_title() {
		return esc_html__( 'Profile Card', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-profile-card';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {
        
        $repeater = new \Elementor\Repeater();

        // ----------------------------------------  Profile Card content ------------------------------
        $this->start_controls_section(
            'profile_card_content',
            [
                'label' => esc_html__( 'Profile Card Content', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'card_style',
            [
                'label' => esc_html__( 'Card Style', 'enteraddons' ),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1'  => esc_html__( 'One', 'enteraddons' ),
                    '2' => esc_html__( 'Two', 'enteraddons' ),
                ],
            ]
        );
        $this->add_control(
            'profile_name',
            [
                'label' => esc_html__( 'Profile Name', 'enteraddons' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'John Doe', 'enteraddons' ),
                'placeholder' => esc_html__( 'Type your name here', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'profile_username',
            [
                'label' => esc_html__( 'Profile Username', 'enteraddons' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( '@anonymous_wp', 'enteraddons' ),
                'placeholder' => esc_html__( 'Type your name here', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'show_profile_ratings',
            [
                'label' => esc_html__( 'Show Ratings', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'ratings',
            [
                'label' => esc_html__( 'Ratings', 'enteraddons' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'card_style' => '1',
                    'show_profile_ratings' => 'yes'
                ],
                'label_block' => true,
                'default' => '4.5',
                'options' => [
                    '1'     => esc_html__( '1', 'enteraddons' ),
                    '1.5'   => esc_html__( '1.5', 'enteraddons' ),
                    '2'     => esc_html__( '2', 'enteraddons' ),
                    '2.5'   => esc_html__( '2.5', 'enteraddons' ),
                    '3'     => esc_html__( '3', 'enteraddons' ),
                    '3.5'   => esc_html__( '3.5', 'enteraddons' ),
                    '4'     => esc_html__( '4', 'enteraddons' ),
                    '4.5'   => esc_html__( '4.5', 'enteraddons' ),
                    '5'     => esc_html__( '5', 'enteraddons' )
                ]
            ]
        );
        $this->add_control(
            'followers_amount',
            [
                'label' => esc_html__( 'Total Followers', 'enteraddons' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( '5K Followers', 'enteraddons' ),
                'placeholder' => esc_html__( 'Type the amount here', 'enteraddons' ),
                'condition' => [
                    'card_style' => '1',
                ],
            ]
        );
        $this->add_control(
            'profile_link_text',
            [
                'label' => esc_html__( 'Profile Link Text', 'enteraddons' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'View Profile', 'enteraddons' ),
                'placeholder' => esc_html__( 'Type text here', 'enteraddons' ),
                'condition' => [
                    'card_style' => '1',
                ],
            ]
        );
        $this->add_control(
            'profile_link',
            [
                'label' => esc_html__( 'Profile Link', 'enteraddons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'enteraddons' ),
                'dynamic' => [
                    'active' => true,
                ],
                'options' => [ 'url', 'is_external', 'nofollow' ],
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'label_block' => true,
            ]
        );
        $this->add_control(
            'profile_cover',
            [
                'label' => esc_html__( 'Profile Cover', 'enteraddons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'card_style' => '1',
                ],
            ]
        );
        $this->add_control(
            'profile_avatar',
            [
                'label' => esc_html__( 'Profile Avatar', 'enteraddons' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        //
        $this->add_control(
            'profile_description',
            [
                'label' => esc_html__( 'Description', 'enteraddons' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'rows' => 10,
                'default' => esc_html__( 'Default description', 'enteraddons' ),
                'placeholder' => esc_html__( 'Type your description here', 'enteraddons' ),
                'condition' => [
                    'card_style' => '2',
                ],
            ]
        );
        $this->add_control(
            'signature_image',
            [
                'label' => esc_html__( 'Signature Image', 'enteraddons' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'card_style' => '2',
                ],
            ]
        );
        
        $this->end_controls_section();

        // ---------------------------------- Profile Card content ---------------------------
        $this->start_controls_section(
            'profile_card_status',
            [
                'label' => esc_html__( 'Profile Status', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'card_style' => '1',
                ]
            ]
        );
        $this->add_control(
            'profile_status_enable',
            [
                'label' => esc_html__( 'Show Profile Status', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'profile_status_icon',
            [
                'label' => esc_html__( 'Status Icon', 'enteraddons' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid',
                ]                
            ]
        );
        $this->end_controls_section(); 

        // ---------------------------------- Profile Social Media content ---------------------------
        $this->start_controls_section(
            'profile_card_social_media',
            [
                'label' => esc_html__( 'Social Media', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'card_style' => '2',
                ]
            ]
        );
        $this->add_control(
            'profile_social_media_enable',
            [
                'label' => esc_html__( 'Show Social Media', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'subscription_text', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Subscribe and Follow:' , 'enteraddons' ),
                'label_block' => true,
                'condition' => [
                    'card_style' => '2',
                ],
            ]
        );
        $repeater->add_control(
            'social_icon',
            [
                'label' => esc_html__( 'Add Icon', 'enteraddons' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fab fa-facebook-f',
                    'library' => 'fa-solid',
                ],
            ]
        );
        $repeater->add_control(
            'social_icon_link',
            [
                'label' => esc_html__( 'Link', 'enteraddons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'enteraddons' ),
                'dynamic' => [
                    'active' => true,
                ],
                'options' => [ 'url', 'is_external', 'nofollow' ],
                'label_block' => true,
            ]
        );
        $this->add_control(
            'icon_list',
            [
                'label' => esc_html__( 'Social Media', 'enteraddons' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'social_icon' => [
                            'value' => 'fab fa-facebook-f',
                        ],
                        'social_icon_link' => [
                            'url' => esc_html__( '#', 'enteraddons' ),
                        ],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-instagram',
                        ],
                        'social_icon_link' => [
                            'url' => esc_html__( '#', 'enteraddons' ),
                        ],
                    ],
                ],
                'condition' => [
                    'card_style' => '2',
                ],
            ]
        );
        $this->end_controls_section(); 

        // ----------------------------------------  Profile Card 1 style ------------------------------

        // wrapper style section
        $this->start_controls_section(
            'wrapper_style',
            [
                'label' => esc_html__( 'Wrapper Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-profile-card, .ea-sb-about-me .widget-main-content',
            ]
        );
        $this->add_responsive_control(
            'wrapper_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card, .ea-sb-about-me .widget-main-content' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card, .ea-sb-about-me .widget-main-content' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wrapper_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-profile-card, .ea-sb-about-me .widget-main-content',
            ]
        );
        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card, .ea-sb-about-me .widget-main-content' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-profile-card, .ea-sb-about-me .widget-main-content',
            ]
        );
        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card, .ea-sb-about-me .widget-main-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card, .ea-sb-about-me .widget-main-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // cover photo style section
        $this->start_controls_section(
            'cover_photo_style',
            [
                'label' => esc_html__( 'Cover Photo Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'card_style' => '1',
                ],
            ]
        );

        $this->add_responsive_control(
            'photo_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-cover img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'photo_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-cover img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'cover_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-cover img',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'cover_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-cover img',
            ]
        );
        $this->add_responsive_control(
            'cover_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-cover img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // avatar photo style section
        $this->start_controls_section(
            'avatar_photo_style',
            [
                'label' => esc_html__( 'Avatar Photo Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'table_avatar_photo_area' );

        $this->start_controls_tab(
            'avatar_photo_area_tab',
            [
                'label' => esc_html__( 'Photo Area', 'enteraddons' ),
            ]
        );
        $this->add_responsive_control(
            'avatar_area_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .profile-card-wrap .profile-avatar-img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'avatar_area_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .profile-card-wrap .profile-avatar-img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'avatar_area_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .profile-card-wrap .profile-avatar-img',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'avatar_area_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .profile-card-wrap .profile-avatar-img',
            ]
        );
        $this->add_responsive_control(
            'avatar_area_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .profile-card-wrap .profile-avatar-img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'avatar_area_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .profile-card-wrap .profile-avatar-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'avatar_area_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .profile-card-wrap .profile-avatar-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'avatar_photo_img_tab',
            [
                'label' => esc_html__( 'Image', 'enteraddons' ),
            ]
        );

        $this->add_responsive_control(
            'avatar_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .profile-card-wrap .profile-avatar-img img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'avatar_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .profile-card-wrap .profile-avatar-img img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'avatar_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .profile-card-wrap .profile-avatar-img img',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'avatar_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .profile-card-wrap .profile-avatar-img img',
            ]
        );
        $this->add_responsive_control(
            'avatar_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .profile-card-wrap .profile-avatar-img img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'avatar_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .profile-card-wrap .profile-avatar-img img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'avatar_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .profile-card-wrap .profile-avatar-img img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        // status style section
        $this->start_controls_section(
            'status_style_section',
            [
                'label' => esc_html__( 'Profile Status Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'card_style' => '1',
                    'profile_status_enable' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'status_icon_font_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .sb-status' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'status_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .sb-status' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'status_icon_bg_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-avatar .sb-status' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'status_icon_bg_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-avatar .sb-status' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'status_icon_top_position',
            [
                'label' => esc_html__( 'Postion Top', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-avatar .sb-status' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'status_icon_left_position',
            [
                'label' => esc_html__( 'Position Left', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-avatar .sb-status' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'status_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-profile-card .sb-status',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'status_icon_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-profile-card .sb-status',
            ]
        );
        $this->end_controls_section();

        // name style section
        $this->start_controls_section(
            'profile_name_style',
            [
                'label' => esc_html__( 'Name Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-title h3, .ea-sb-about-me .sb-profile-avatar-wrap h3',
            ]
        );
        $this->add_control(
            'name_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-title h3' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ea-sb-about-me .sb-profile-avatar-wrap h3 a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'name_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-title h3' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .ea-sb-about-me .sb-profile-avatar-wrap h3' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'name_stroke',
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-title h3, .ea-sb-about-me .sb-profile-avatar-wrap h3',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'name_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-title h3, .ea-sb-about-me .sb-profile-avatar-wrap h3',
            ]
        );
        $this->add_responsive_control(
            'name_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-title h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-sb-about-me .sb-profile-avatar-wrap h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'name_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-title h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-sb-about-me .sb-profile-avatar-wrap h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // username style section
        $this->start_controls_section(
            'profile_username_style',
            [
                'label' => esc_html__( 'Username Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'username_typography',
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-meta .sb-name, .ea-sb-about-me .sb-profile-avatar-wrap h6',
            ]
        );
        $this->add_control(
            'username_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-meta .sb-name' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ea-sb-about-me .sb-profile-avatar-wrap h6' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'username_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-meta' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .ea-sb-about-me .sb-profile-avatar-wrap h6' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'username_text_stroke',
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-meta .sb-name, .ea-sb-about-me .sb-profile-avatar-wrap h6',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'username_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-meta .sb-name, .ea-sb-about-me .sb-profile-avatar-wrap h6',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'username_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-meta',
            ]
        );
        $this->add_responsive_control(
            'username_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-sb-about-me .sb-profile-avatar-wrap h6' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'username_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-sb-about-me .sb-profile-avatar-wrap h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();

        // followers text style section
        $this->start_controls_section(
            'follower_text_style',
            [
                'label' => esc_html__( 'Follower Text Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'card_style' => '1',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'follower_text_typography',
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-content .profile-info .follower',
            ]
        );
        $this->add_control(
            'follower_text_color',
            [
                'label' => esc_html__( 'Color', 'enteraddonst' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-content .profile-info .follower' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'follower_text_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddonst' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-content .profile-info .follower' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        
        // followers link text style section
        $this->start_controls_section(
            'profile_link_text_style',
            [
                'label' => esc_html__( 'Profile Link Text Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'card_style' => '1',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pro_link_text_typography',
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-info .btn-link',
            ]
        );
        $this->add_control(
            'pro_link_text_color',
            [
                'label' => esc_html__( 'Color', 'enteraddonst' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-info .btn-link' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pro_link_text_text_stroke',
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-info .btn-link',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pro_link_text_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'enteraddonst' ),
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-info .btn-link',
            ]
        );
        $this->add_responsive_control(
            'pro_link_text_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddonst' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-info .btn-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'pro_link_text_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddonst' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-info .btn-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ratings star style section
        $this->start_controls_section(
            'ratings_star_style',
            [
                'label' => esc_html__( 'Ratings Star Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'card_style' => '1',
                ],
            ]
        );
        $this->add_responsive_control(
            'ratings_star_font_size',
            [
                'label' => esc_html__( 'Font Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-content .sb-star-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'ratings_star_icon_color',
            [
                'label' => esc_html__( 'Color', 'enteraddonst' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-content .sb-star-rating i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ratings_star_border',
                'label' => esc_html__( 'Border', 'enteraddonst' ),
                'selector' => '{{WRAPPER}} .ea-profile-card .profile-content .sb-star-rating i',
            ]
        );
        $this->add_responsive_control(
            'ratings_star_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddonst' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px','%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-content .sb-star-rating i' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ratings_star_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddonst' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-content .sb-star-rating i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ratings_star_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddonst' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-profile-card .profile-content .sb-star-rating i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // description style section
        $this->start_controls_section(
            'profile_descrtiption_style',
            [
                'label' => esc_html__( 'Description Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'card_style' => '2',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .ea-sb-about-me p',
            ]
        );
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me p' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'description_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me p' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'description_text_stroke',
                'selector' => '{{WRAPPER}} .ea-sb-about-me p',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'description_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-sb-about-me p',
            ]
        );
        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'description_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();

        // signature photo style section
        $this->start_controls_section(
            'signature_photo_style',
            [
                'label' => esc_html__( 'Signature Photo Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'card_style' => '2',
                ]
            ]
        );
        $this->add_responsive_control(
            'signature_size',
            [
                'label' => esc_html__( 'Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .signature img' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ea-sb-about-me .signature img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'signature_text_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .signature' => 'justify-content: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'signature_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-sb-about-me .signature img',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'signature_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-sb-about-me .signature img',
            ]
        );
        $this->add_responsive_control(
            'signature_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .signature img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'signature_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .signature img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'signature_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .signature img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'divider_heading',
            [
                'label' => esc_html__( 'Divider', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'signature_divider',
                'label' => esc_html__( 'Divider', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-sb-about-me .signature',
            ]
        );

        $this->end_controls_section();

        // subscription text style section
        $this->start_controls_section(
            'subscription_text_style',
            [
                'label' => esc_html__( 'Social Title Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'card_style' => '2',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subscription_text_typography',
                'selector' => '{{WRAPPER}} .ea-sb-about-me .about-socials h6',
            ]
        );
        $this->add_control(
            'subscription_text_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .about-socials h6' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'subscription_text_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .about-socials h6' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'subscription_text_stroke',
                'selector' => '{{WRAPPER}} .ea-sb-about-me .about-socials h6',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'subscription_text_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-sb-about-me .about-socials h6',
            ]
        );
        $this->add_responsive_control(
            'subscription_text_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .about-socials h6' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'subscription_text_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .about-socials h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // social icon style section
        $this->start_controls_section(
            'social_icon_style_section',
            [
                'label' => esc_html__( 'Social Icon Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'card_style' => '2',
                ],
            ]
        );

        $this->start_controls_tabs(
            'social_icons_style_tabs'
        );

        $this->start_controls_tab(
            'social_icons_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );

        $this->add_responsive_control(
            'icon_text_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .about-social-icons' => 'justify-content: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'social_icon_font_size',
            [
                'label' => esc_html__( 'Font Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .about-social-icons a' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'social_icon_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .about-social-icons a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icon_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .about-social-icons a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'social_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .about-social-icons a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'social_icon_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-sb-about-me .about-social-icons a',
            ]
        );
        $this->add_responsive_control(
            'social_icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .about-social-icons a' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'social_icon_bg',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-sb-about-me .about-social-icons a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'social_icons_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'social_icon_hover_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-sb-about-me .about-social-icons a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'social_icon_hover_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-sb-about-me .about-social-icons a:hover',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'social_icon_hover_bg',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-sb-about-me .about-social-icons a:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

	}

	protected function render() {

        $settings = $this->get_settings_for_display();

        // Profile Card template render
        $obj = new \Enteraddons\Widgets\Profile_Card\Profile_Card_Template();
        //
        $obj::setDisplaySettings( $settings );
        //
        $obj::renderTemplate();
    }

    public function get_style_depends() {
        return [ 'enteraddons-global-style', 'fontawesome' ];
    }
    
}

