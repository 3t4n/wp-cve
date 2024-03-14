<?php
namespace Enteraddons\Widgets\Image_Gallery;

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
 * Enteraddons elementor Image Gallery widget.
 *
 * @since 1.0
 */

class Image_Gallery extends Widget_Base {

	public function get_name() {
		return 'enteraddons-image-gallery';
	}

	public function get_title() {
		return esc_html__( 'Image Gallery', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-image-gallery';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ---------------------------------------- Gallery Settings Content ------------------------------
        $this->start_controls_section(
            'enteraddons_gallery_settings_content',
            [
                'label' => esc_html__( 'Gallery Settings', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'column',
            [
                'label' => esc_html__( 'Grid Column', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1'  => esc_html__( '1 Column', 'enteraddons' ),
                    '2' => esc_html__( '2 Column', 'enteraddons' ),
                    '3' => esc_html__( '3 Column', 'enteraddons' ),
                    '4' => esc_html__( '4 Column', 'enteraddons' )
                ],
            ]
        );
        $this->add_responsive_control(
            'item_grid_gap',
            [
                'label' => esc_html__( 'Grid Gap', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .grid--wrap' => 'grid-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'overly_active',
            [
                'label'         => esc_html__( 'Overly Active', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'enteraddons' ),
                'label_off'     => esc_html__( 'Hide', 'enteraddons' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );
        $this->add_control(
            'image_settings_block',
            [
                'label' => esc_html__( 'Image Settings', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'g_image_width',
            [
                'label' => esc_html__( 'Image Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '100',
                ],
                'selectors' => [
                    '{{WRAPPER}} .grid-item-inner img' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'g_image_max_width',
            [
                'label' => esc_html__( 'Image Max Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '100',
                ],
                'selectors' => [
                    '{{WRAPPER}} .grid-item-inner img' => 'max-width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'g_image_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '100',
                ],
                'selectors' => [
                    '{{WRAPPER}} .grid-item-inner img' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_control(
            'icon_options_block',
            [
                'label' => esc_html__( 'Popup/Link', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'link_type',
            [
                'label' => esc_html__( 'Link Type', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'prettyphoto',
                'options' => [
                    'wrap_link' => esc_html__( 'Wrapper Link', 'enteraddons' ),
                    'prettyphoto' => esc_html__( 'Popup', 'enteraddons' ),
                    'none' => esc_html__( 'None', 'enteraddons' )
                ]
            ]
        );
        $this->add_control(
            'icon_show_on',
            [
                'label' => esc_html__( 'Icon Show On', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition' => [ 'link_type' => 'prettyphoto' ],
                'default' => 'icon-show-on-hover',
                'options' => [
                    'icon-show-on-hover' => esc_html__( 'Hover', 'enteraddons' ),
                    'icon-show-on-always' => esc_html__( 'Always', 'enteraddons' ),
                ]
            ]
        );
        $this->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [ 'link_type' => 'prettyphoto' ],
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'solid',
                ],
            ]
        );
        
        $this->add_control(
            'title_options_block',
            [
                'label' => esc_html__( 'Title/Tags', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'title_show',
            [
                'label'         => esc_html__( 'Title Show', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'enteraddons' ),
                'label_off'     => esc_html__( 'Hide', 'enteraddons' ),
                'return_value'  => 'yes',
                'default'       => 'no',
            ]
        );
        $this->add_control(
            'title_show_on',
            [
                'label' => esc_html__( 'Title Show On', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition' => [ 'title_show' => 'yes' ],
                'default' => 'title-show-on-hover',
                'options' => [
                    'title-show-on-hover' => esc_html__( 'Hover', 'enteraddons' ),
                    'title-show-on-always' => esc_html__( 'Always', 'enteraddons' ),
                ]
            ]
        );
        $this->add_responsive_control(
            'title_horizontal_align',
            [
                'label' => esc_html__( 'Title Horizontal Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'condition' => [ 'title_show' => 'yes' ],
                'options' => [
                    'flex-star' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .grid-item-inner .overlay' => 'align-items: {{VALUE}};',
                ]
            ]
        );
        $this->add_responsive_control(
            'title_vertical_align',
            [
                'label' => esc_html__( 'Title Vertical  Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'condition' => [ 'title_show' => 'yes' ],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'enteraddons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'enteraddons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .grid-item-inner .overlay' => 'justify-content: {{VALUE}};',
                ]
            ]
        );
        
        $this->end_controls_section(); // End content
        // ---------------------------------------- Image Gallery Content ------------------------------
        $this->start_controls_section(
            'enteraddons_image_gallery_content',
            [
                'label' => esc_html__( 'Image Gallery Content', 'enteraddons' ),
            ]
        );
        $repeater->add_control(
            'column_space',
            [
                'label' => esc_html__( 'Column Space', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1'  => esc_html__( '1', 'enteraddons' ),
                    '2' => esc_html__( '2', 'enteraddons' ),
                    '3' => esc_html__( '3', 'enteraddons' ),
                    '4' => esc_html__( '4', 'enteraddons' )
                ],
            ]
        );
        $repeater->add_control(
            'title', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'MARY P. JOHNSON' , 'enteraddons' ),
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'tags', [
                'label' => esc_html__( 'Tags', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Branding, Product Design' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'enteraddons' ),
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );
        $repeater->add_control(
            'img', [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        
        $this->add_control(
            'image_gallery',
            [
                'label' => esc_html__( 'Add Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title'   => esc_html__( 'MARY P. JOHNSON', 'enteraddons' ),
                        'link'   => '#',
                        'img'    => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    [
                        'name'      => esc_html__( 'MARY P. JOHNSON', 'enteraddons' ),
                        'link'   => "#",
                        'img'    => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    [
                        'title'   => esc_html__( 'MARY P. JOHNSON', 'enteraddons' ),
                        'link'   => '#',
                        'img'    => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    [
                        'name'      => esc_html__( 'MARY P. JOHNSON', 'enteraddons' ),
                        'link'   => "#",
                        'img'    => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    [
                        'name'      => esc_html__( 'MARY P. JOHNSON', 'enteraddons' ),
                        'link'   => "#",
                        'img'    => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    [
                        'name'      => esc_html__( 'MARY P. JOHNSON', 'enteraddons' ),
                        'link'   => "#",
                        'img'    => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    
                ]
            ]
        );

        $this->end_controls_section(); // End content

        /**
         * Style Tab
         * ------------------------------ Image Gallery Content area Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_image_gallery_content_wrapper_settings', [
                'label' => esc_html__( 'Content Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-image-gallery' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-image-gallery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-image-gallery' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-image-gallery',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-image-gallery',
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Gallery Item Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_gallery_item_settings', [
                'label' => esc_html__( 'Item Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'gallery_item_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .grid--wrap .grid-item-inner-top' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'gallery_item_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .grid--wrap .grid-item-inner-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'gallery_item_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .grid--wrap .grid-item-inner-top',
                ]
            );
            $this->add_responsive_control(
                'gallery_item_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .grid--wrap .grid-item-inner-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'gallery_item_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .grid--wrap .grid-item-inner-top',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'gallery_item_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .grid--wrap .grid-item-inner-top',
                ]
            );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Gallery Item Overly Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_gallery_item_overly_settings', [
                'label' => esc_html__( 'Item Overly Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'gallery_item_overlay_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .overlay-active .grid-item-inner .overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'gallery_item_overly_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .overlay-active .grid-item-inner .overlay',
            ]
        );
        $this->end_controls_section();

        /**
         * 
         * Style Tab
         * ------------------------------ Text Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_text_settings', [
                'label' => esc_html__( 'Text Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_options_heading',
            [
                'label' => esc_html__( 'Title Style', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gallery-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .gallery-title',
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * 
         * Style Tab
         * ------------------------------ Tags Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_tags_settings', [
                'label' => esc_html__( 'Tags Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'tags_color',
            [
                'label' => esc_html__( 'Tags Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .grid-item-inner .tags' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tags_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .grid-item-inner .tags',
            ]
        );
        $this->add_responsive_control(
            'tags_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .grid-item-inner .tags' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tags_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .grid-item-inner .tags' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        /**
         * 
         * Style Tab
         * ------------------------------ Popup Icon Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_popup_icon_settings', [
                'label' => esc_html__( 'Popup Icon Settings', 'enteraddons' ),
                'condition' => [ 'link_type' => 'prettyphoto' ],
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .grid-item-inner i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__( 'Icon Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .grid-item-inner i:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .grid-item-inner i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_top_position',
            [
                'label' => esc_html__( 'Icon Top Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-image-gallery .grid-item-inner > a' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_bottom_position',
            [
                'label' => esc_html__( 'Icon Bottom Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-image-gallery .grid-item-inner > a' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_left_position',
            [
                'label' => esc_html__( 'Icon Left Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-image-gallery .grid-item-inner > a' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_right_position',
            [
                'label' => esc_html__( 'Icon Right Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-image-gallery .grid-item-inner > a' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
	}

	protected function render() {
        // get settings
        $settings = $this->get_settings_for_display();
        // Testimonial template render
        $obj = new Image_Gallery_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
        
    }
	
    public function get_script_depends() {
        return [ 'enteraddons-main', 'isotope-pkgd', 'packery-mode-pkgd' ];
    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }

}
