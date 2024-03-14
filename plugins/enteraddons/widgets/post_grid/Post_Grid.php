<?php
namespace Enteraddons\Widgets\Post_Grid;

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
 * Enteraddons elementor Post Grid widget.
 *
 * @since 1.0
 */

class Post_Grid extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-post-grid';
	}

	public function get_title() {
		return esc_html__( 'Post Grid', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-post-grid';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Post content ------------------------------
        $this->start_controls_section(
            'enteraddons_post_grid_content_settings',
            [
                'label' => esc_html__( 'Post Grid Content', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'limit',
            [
                'label' => esc_html__( 'Post Limit', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '10'
            ]
        );
        $this->add_control(
            'order',
            [
                'label' => esc_html__( 'Order', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,
                'default' => 'ASC',
                'options' => [
                    'ASC' => esc_html__( 'ASC', 'enteraddons' ),
                    'DESC' => esc_html__( 'DESC', 'enteraddons' )
                ]
            ]
        );
        
        $this->add_control(
            'excerpt_count',
            [
                'label' => esc_html__( 'Excerpt Words Count', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30
                ]
            ]
        );
        $this->add_control(
            'post_offset',
            [
                'label' => esc_html__( 'Post Offset', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => ''
            ]
        );
        $this->add_control(
            'column',
            [
                'label' => esc_html__( 'Column', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,
                'default' => '3',
                'options' => [
                    '1' => esc_html__( 'Column 1', 'enteraddons' ),
                    '2' => esc_html__( 'Column 2', 'enteraddons' ),
                    '3' => esc_html__( 'Column 3', 'enteraddons' ),
                    '4' => esc_html__( 'Column 4', 'enteraddons' )
                ]
            ]
        );
        $this->add_responsive_control(
            'item_layout',
            [
                'label' => esc_html__( 'Thumbnail Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'label_block'=> true,
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Left Thumbnail', 'enteraddons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Top Thumbnail', 'enteraddons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__( 'Right Thumbnail', 'enteraddons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__( 'Bottom Thumbnail', 'enteraddons' ),
                        'icon' => ' eicon-v-align-bottom',
                    ],
                ],
                'default' => 'column',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-blog.blog-style--six' => 'flex-direction: {{VALUE}} !important',
                ],
            ]
        );
        
        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__( 'Title Tag', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,
                'default' => 'h6',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6'
                ]
            ]
        );
        $this->end_controls_section(); // End content

        // ---------------------------------------- Visibility Settings ------------------------------
        $this->start_controls_section(
            'enteraddons_post_grid_visibility_settings',
            [
                'label' => esc_html__( 'Visibility Settings', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'show_thumbnail',
            [
                'label' => esc_html__( 'Show Thumbnail', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'show_meta',
            [
                'label' => esc_html__( 'Show Meta', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'show_author',
            [
                'label' => esc_html__( 'Show Author', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [ 'show_meta' => 'yes' ]
            ]
        );
        $this->add_control(
            'meta_author_icon',
            [
                'label' => esc_html__( 'Author Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [ 'show_meta' => 'yes', 'show_author' => 'yes' ],
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_control(
            'show_post_date',
            [
                'label' => esc_html__( 'Show Post Date', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [ 'show_meta' => 'yes' ]
            ]
        );
        $this->add_control(
            'meta_date_icon',
            [
                'label' => esc_html__( 'Date Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [ 'show_meta' => 'yes', 'show_post_date' => 'yes' ],
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_control(
            'show_comments',
            [
                'label' => esc_html__( 'Show Comments', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [ 'show_meta' => 'yes' ]
            ]
        );
        $this->add_control(
            'meta_comments_icon',
            [
                'label' => esc_html__( 'Comments Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [ 'show_meta' => 'yes', 'show_comments' => 'yes' ],
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_control(
            'show_desc',
            [
                'label' => esc_html__( 'Show Description', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'show_more_btn',
            [
                'label' => esc_html__( 'Show Read More Button', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->end_controls_section(); // End content
        // ---------------------------------------- Meta Position Settings ------------------------------
        $this->start_controls_section(
            'enteraddons_post_grid_meta_position_settings',
            [
                'label' => esc_html__( 'Meta Position', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'meta_position',
            [
                'label' => esc_html__( 'Select Meta Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,
                'default' => 'before_title',
                'options' => [
                    'before_title' => esc_html__( 'Before Title', 'enteraddons' ),
                    'after_title' => esc_html__( 'After Title', 'enteraddons' ),
                    'on_thumbnail' => esc_html__( 'On Thumbnail', 'enteraddons' )
                ]
            ]
        );
        $this->end_controls_section(); // End content

        //------------------------------ Read More Button ------------------------------
        $this->start_controls_section(
            'enteraddons_post_grid_read_more_btn_settings',
            [
                'label' => esc_html__( 'Button', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__( 'Button Text', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Read More'
            ]
        );
        $this->add_control(
            'icon_position',
            [
                'label' => esc_html__( 'Buton Icon Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'right' => esc_html__( 'Right', 'enteraddons' ),
                    'left'  => esc_html__( 'Left', 'enteraddons' ),
                ]
            ]
        );
        $this->add_control(
            'button_icon',
            [
                'label' => esc_html__( 'Button Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'solid',
                ]
            ]
        );
        $this->end_controls_section();


        /**
         * Style Tab
         * ------------------------------ Content Wrapper Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_item_wrapper_style_settings', [
                'label' => esc_html__( 'Item Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tab_item' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );

            $this->add_responsive_control(
                'item_alignment',
                [
                    'label' => esc_html__( 'Content Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
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
                        '{{WRAPPER}} .enteraddons-single-blog' => 'text-align: {{VALUE}} !important',
                    ],
                ]
            );
            $this->add_responsive_control(
                'items_space',
                [
                    'label' => esc_html__( 'Item Gap', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ]
                    ],
                    'default' => [
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-post-grid-wrapper' => 'grid-gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'item_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-blog' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'item_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-blog' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'item_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .enteraddons-single-blog',
                ]
            );
            $this->add_responsive_control(
                'item_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-blog' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-blog',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'item_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-single-blog',
                ]
            );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'item_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'item_hover_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .enteraddons-single-blog:hover',
                ]
            );
            $this->add_responsive_control(
                'item_hover_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-blog:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_hover_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-blog:hover',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'item_hover_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-single-blog:hover',
                ]
            );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        //------------------------------ Content area Style ------------------------------
        $this->start_controls_section(
            'enteraddons_post_grid_content_area_style', [
                'label' => esc_html__( 'Content Area', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-entry-body',
            ]
        );
        $this->add_responsive_control(
            'content_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-entry-body',
            ]
        );
        $this->end_controls_section();
        //------------------------------ Thumbnail Style ------------------------------
        $this->start_controls_section(
            'enteraddons_thumbnail_wrapper_style', [
                'label' => esc_html__( 'Thumbnail Wrapper', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'thumbnail_wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'thumbnail_wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'thumbnail_wrapper_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-entry-header',
            ]
        );
        $this->add_responsive_control(
            'thumbnail_wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'thumbnail_wrapper_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-entry-header',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'thumbnail_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-entry-header',
            ]
        );
        $this->add_control(
            'img_z_index',
            [
                'label' => esc_html__( 'Z Index', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-header' => 'z-index: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        //------------------------------ Thumbnail Style ------------------------------
        $this->start_controls_section(
            'enteraddons_thumbnail_style', [
                'label' => esc_html__( 'Thumbnail', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'tab_post_thumbnail' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_thumbnail_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_responsive_control(
            'img_width',
            [
                'label' => esc_html__( 'Thumbnail Width', 'enteraddons' ),
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-header img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_height',
            [
                'label' => esc_html__( 'Thumbnail Height', 'enteraddons' ),
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-header img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );   
        $this->add_responsive_control(
            'img_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-header img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-header img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'img_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-entry-header img',
            ]
        );
        $this->add_responsive_control(
            'img_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-header img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'img_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-entry-header img',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'item_image_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'img_hover_animation',
            [
                'label' => esc_html__( 'Image Hover Animation', 'enteraddons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none-animation',
                'options' => [
                    'none-animation' => esc_html__( 'None', 'enteraddons' ),
                    'scaleup-animation' => esc_html__( 'Image Scaleup', 'enteraddons' )
                ]
            ]
        );
        $this->add_responsive_control(
            'img_hover_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-blog:hover .enteraddons-entry-header img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'img_hover_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-blog:hover .enteraddons-entry-header img',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();
        //------------------------------ Post Meta Style ------------------------------
        $this->start_controls_section(
            'enteraddons_post_meta_style', [
                'label' => esc_html__( 'Post Meta', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'meta_alignment',
            [
                'label' => esc_html__( 'Meta Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .enteraddons-single-blog .post-meta-list' => 'text-align: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-blog .post-meta-list',
            ]
        );
        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-blog .post-meta-list' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'meta_link_color',
            [
                'label' => esc_html__( 'Meta Link Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-blog .post-meta-list span *' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'meta_link_hover_color',
            [
                'label' => esc_html__( 'Meta Link Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-blog .post-meta-list span:hover *' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'meta_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-blog .post-meta-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'meta_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-blog .post-meta-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'meta_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-blog .post-meta-list',
            ]
        );
        $this->add_responsive_control(
            'meta_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-blog .post-meta-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'meta_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-blog .post-meta-list',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'meta_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-single-blog .post-meta-list',
            ]
        );
        $this->end_controls_section();

        //------------------------------ Title Style ------------------------------
        $this->start_controls_section(
            'enteraddons_post_title_style', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tab_post_title' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_title_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body .entry-title a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-entry-body .entry-title',
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
                    '{{WRAPPER}} .enteraddons-entry-body .entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .enteraddons-entry-body .entry-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'item_title_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-blog:hover .enteraddons-entry-body .entry-title a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section


        $this->end_controls_section();

        //------------------------------ Descriptions Style ------------------------------
        $this->start_controls_section(
            'enteraddons_post_grid_descriptions_style', [
                'label' => esc_html__( 'Descriptions', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        // start controls tabs
        $this->start_controls_tabs( 'tab_descriptions_style' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'tab_descriptions_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'descriptions_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body .entry-summery' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'descriptions_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-entry-body .entry-summery',
            ]
        );
        $this->add_responsive_control(
            'descriptions_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body .entry-summery' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'descriptions_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body .entry-summery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        //  Controls tab For Hover
        $this->start_controls_tab(
            'item_descriptions_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'descriptions_hover_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-blog:hover .enteraddons-entry-body .entry-summery' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();

        //------------------------------ Button Style ------------------------------
        $this->start_controls_section(
            'enteraddons_more_btn_style', [
                'label' => esc_html__( 'Button', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'link_display',
            [
                'label' => esc_html__( 'Display', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'inline-block'  => esc_html__( 'Inline Block', 'enteraddons' ),
                    'block' => esc_html__( 'block', 'enteraddons' ),
                    'none' => esc_html__( 'None', 'enteraddons' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn' => 'display: {{VALUE}};',
                ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'link_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn',
            ]
        );
        $this->add_responsive_control(
            'link_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'link_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        // start controls tabs
        
        $this->start_controls_tabs( 'tab_link_style' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'tab_link_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__( 'Link Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'link_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'link_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn',
            ]
        );
        $this->add_responsive_control(
            'link_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'link_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn',
            ]
        ); 

        $this->end_controls_tab();


        //  Controls tab For Hover
        $this->start_controls_tab(
            'tab_link_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => esc_html__( 'Link Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'link_hover_background',
                'label' => esc_html__( 'Hover Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'link_hover_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn:hover',
            ]
        );
        $this->add_responsive_control(
            'link_border_hover_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'link_box_hover_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-entry-body .post-grid-btn:hover',
            ]
        );
        $this->end_controls_tabs(); //  end controls section

        $this->add_control(
            'btn_icon_style',
            [
                'label' => esc_html__( 'Button Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'left_space',
            [
                'label' => esc_html__( 'Left Space', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .post-grid-btn i' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'right_space',
            [
                'label' => esc_html__( 'Right Space', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .post-grid-btn i' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();


	}

	protected function render() {
        
        // get settings
        $settings = $this->get_settings_for_display();

        // Tema template render
        $obj = new Post_Grid_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style'];
    }


}
