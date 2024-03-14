<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Image_Size;
use Elementor\Widget_Base;

class MEAFE_Post_Carousel extends Widget_Base
{
    public function get_name() {
        return 'meafe-post-carousel';
    }

    public function get_title() {
        return esc_html__( 'Post Carousel', 'mega-elements-addons-for-elementor' );
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_icon() {
        return 'meafe-post-carousel';
    }

    public function get_style_depends() {
        return ['meafe-post-carousel'];
    }

    public function get_script_depends() {
        return ['meafe-post-carousel'];
    }

    public function get_authors() {
        
        $users = get_users([
            'has_published_posts' => true,
            'fields' => [
                'ID',
                'display_name',
            ],
        ]);

        if ( !empty( $users ) ) {
            return wp_list_pluck( $users, 'display_name', 'ID' );
        }

        return [];
    }

    public function get_all_types_post() {
        
        $posts = get_posts([
            'post_type'     => 'post',
            'post_style'    => 'all_types',
            'post_status'   => 'publish',
            'posts_per_page' => '-1',
        ]);

        if ( !empty( $posts ) ) {
            return wp_list_pluck( $posts, 'post_title', 'ID' );
        }

        return [];
    }

    public function get_post_orderby_options() {
        
        $orderby = array(
            'ID'            => 'Post ID',
            'author'        => 'Post Author',
            'title'         => 'Title',
            'date'          => 'Date',
            'modified'      => 'Last Modified Date',
            'parent'        => 'Parent Id',
            'rand'          => 'Random',
            'comment_count' => 'Comment Count',
            'menu_order'    => 'Menu Order',
        );

        return $orderby;
    }

    protected function query_controls()
    {
        
        /**
         * Blog Query Settings
        */
        $taxonomies = get_taxonomies( [], 'objects' );

        $this->start_controls_section(
            'meafe_post_carousel_content_query_settings',
            [
                'label'     => esc_html__( 'Query Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'PC_authors', [
                'label'         => esc_html__( 'Author', 'mega-elements-addons-for-elementor' ),
                'label_block'   => true,
                'type'          => Controls_Manager::SELECT2,
                'multiple'      => true,
                'default'       => [],
                'options'       => $this->get_authors(),
            ]
        );

        foreach ($taxonomies as $taxonomy => $object) {
            if( in_array( $taxonomy, array( 'category', 'post_tag' ) ) ) :
                $this->add_control(
                    'PC_' . $taxonomy . '_ids',
                    [
                        'label'         => $object->label,
                        'type'          => Controls_Manager::SELECT2,
                        'label_block'   => true,
                        'multiple'      => true,
                        'object_type'   => $taxonomy,
                        'options'       => wp_list_pluck( get_terms( $taxonomy ), 'name', 'term_id' ),
                    ]
                );
            endif;
        }

        $this->add_control(
            'PC_post__not_in',
            [
                'label'         => esc_html__( 'Exclude', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->get_all_types_post(),
                'label_block'   => true,
                'post_type'     => '',
                'multiple'      => true,
            ]
        );

        $this->add_control(
            'PC_posts_per_page',
            [
                'label'     => esc_html__( 'Posts Per Page', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '3',
            ]
        );

        $this->add_control(
            'PC_orderby',
            [
                'label'     => esc_html__( 'Order By', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $this->get_post_orderby_options(),
                'default'   => 'date',

            ]
        );

        $this->add_control(
            'PC_order',
            [
                'label'     => esc_html__( 'Order', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'asc'       => 'Ascending',
                    'desc'      => 'Descending',
                ],
                'default'   => 'desc',

            ]
        );

        $this->add_control(
            'PC_offset',
            array(
                'label'       => __( 'Offset', 'mega-elements-addons-for-elementor' ),
                'description' => __( 'This option is used to exclude number of initial posts from being display.', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '0',
                'min'         => '0',
            )
        );

        $this->end_controls_section();
    }

    protected function layout_controls()
    {
        /**
         * Post Carousel Layout Settings
        */
        $this->start_controls_section(
            'meafe_post_carousel_content_layout_settings',
            [
                'label'     => esc_html__( 'Layout Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'PC_layout_mode',
            [
                'label'     => esc_html__( 'Layout', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => [
                    '1'   => esc_html__( 'Layout One', 'mega-elements-addons-for-elementor' ),
                    '2'   => esc_html__( 'Layout Two', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'PC_per_line',
            [
                'label'     => esc_html__( 'No. of items per slide', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'options' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PC_swiper_nav',
            [
                'label'     => esc_html__( 'Enable Navigation', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PC_prev_icon',
            [
                'label' => __('Previous Icon', 'mega-elements-addons-for-elementor'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-arrow-left',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'PC_swiper_nav' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PC_next_icon',
            [
                'label' => __('Next Icon', 'mega-elements-addons-for-elementor'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'PC_swiper_nav' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PC_swiper_dots',
            [
                'label' => esc_html__('Show Navigation Dots', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PC_enable_carousel_loop',
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

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'meafe_post_carousel',
                'default'   => 'meafe-blog-one',
                'exclude'   => ['custom'],
            ]
        );
    
        $this->add_control(
            'PC_show_image',
            [
                'label'        => esc_html__( 'Show Image', 'mega-elements-addons-for-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'PC_show_title',
            [
                'label'        => esc_html__( 'Show Title', 'mega-elements-addons-for-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'PC_show_excerpt',
            [
                'label'        => esc_html__( 'Show excerpt', 'mega-elements-addons-for-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition' => [
                    'PC_layout_mode' => '1',
                ]
            ]
        );

        $this->add_control(
            'PC_excerpt_length',
            [
                'label'     => esc_html__( 'Excerpt Words', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '10',
                'condition' => [
                    'PC_show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'PC_excerpt_expanison_indicator',
            [
                'label'     => esc_html__( 'Expanison Indicator', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'label_block' => false,
                'default'   => esc_html__( '...', 'mega-elements-addons-for-elementor' ),
                'condition' => [
                    'PC_show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'PC_show_read_more_button',
            [
                'label'     => esc_html__( 'Show Read More Button', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
                'condition' => [
                    'PC_layout_mode' => '1',
                ]
            ]
        );

        $this->add_control(
            'PC_read_more_button_text',
            [
                'label'     => esc_html__( 'Button Text', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Read More', 'mega-elements-addons-for-elementor' ),
                'condition' => [
                    'PC_show_read_more_button' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'PC_show_author',
            [
                'label'     => esc_html__( 'Show Author', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
            ]
        );

        $this->add_control(
            'PC_show_author_avatar',
            [
                'label'     => esc_html__( 'Show Author Avatar', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
            ]
        );
        
        $this->add_control(
            'PC_show_date',
            [
                'label'     => esc_html__( 'Show Date', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
            ]
        );
        
        $this->add_control(
            'PC_show_category',
            [
                'label'     => esc_html__( 'Show Category', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function read_more_button_style()
    {
        /**
         * Blog Read More Button Style
        */ 
        $this->start_controls_section(
            'meafe_post_carousel_style_read_more_style',
            [
                'label'     => esc_html__( 'Read More Button Style', 'mega-elements-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'PC_show_read_more_button' => 'yes',
                    'PC_layout_mode' => '1',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'PC_read_more_btn_typography',
                'selector'  => '{{WRAPPER}} .meafe-post-elements-readmore-btn',
            ]
        );

        $this->start_controls_tabs( 'PC_read_more_button_tabs' );

        $this->start_controls_tab(
            'PC_read_more_button_style_normal',
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'PC_read_more_btn_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-carousel-wrapper .meafe-post-carousel-button.read-more a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'PC_read_more_btn_background',
                'label'     => esc_html__( 'Background', 'mega-elements-addons-for-elementor' ),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .meafe-post-elements-readmore-btn',
                'exclude'   => [
                    'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'PC_read_more_btn_border',
                'label'     => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-post-elements-readmore-btn',
            ]
        );

        $this->add_responsive_control(
            'PC_read_more_btn_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-elements-readmore-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'PC_read_more_button_style_hover',
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'PC_read_more_btn_hover_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-elements-readmore-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'PC_read_more_btn_hover_background',
                'label'     => esc_html__( 'Background', 'mega-elements-addons-for-elementor' ),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .meafe-post-elements-readmore-btn:hover',
                'exclude'   => [
                    'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'PC_read_more_btn_hover_border',
                'label'     => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-post-elements-readmore-btn:hover',
            ]
        );

        $this->add_responsive_control(
            'PC_read_more_btn_border_hover_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-elements-readmore-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'PC_read_more_btn_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-carousel-button.read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'PC_read_more_btn_margin',
            [
                'label'     => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-carousel-button.read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function load_more_button_style()
    {
        /**
         * Blog Load More Button Style
        */ 
        $this->start_controls_section(
            'meafe_post_carousel_style_load_more_style',
            [
                'label'     => esc_html__( 'Load More Button Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'PC_show_load_more' => ['yes', '1', 'true'],
                ],
            ]
        );

        $this->add_responsive_control(
            'PC_load_more_btn_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'PC_load_more_btn_margin',
            [
                'label'     => esc_html__('Margin', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-load-more-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'PC_load_more_btn_typography',
                'selector'  => '{{WRAPPER}} .meafe-load-more-button',
            ]
        );

        $this->start_controls_tabs( 'PC_load_more_btn_tabs' );

        // Normal State Tab
        $this->start_controls_tab(
            'PC_load_more_btn_normal', 
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' )
            ]
        );

        $this->add_control(
            'PC_load_more_btn_normal_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-load-more-button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PC_load_more_btn_normal_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-load-more-button' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'PC_load_more_btn_normal_border',
                'label'     => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-load-more-button',
            ]
        );

        $this->add_control(
            'PC_load_more_btn_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-load-more-button' => 'border-radius: {{SIZE}}px',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'PC_load_more_btn_shadow',
                'selector'  => '{{WRAPPER}} .meafe-load-more-button',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab(
            'PC_load_more_btn_hover', 
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ) 
            ] 
        );

        $this->add_control(
            'PC_load_more_btn_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-load-more-button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PC_load_more_btn_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-load-more-button:hover' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PC_load_more_btn_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-load-more-button:hover' => 'border-color: {{VALUE}}',
                ],
            ]

        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'PC_load_more_btn_hover_shadow',
                'selector'  => '{{WRAPPER}} .meafe-load-more-button:hover',
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'PC_loadmore_button_alignment',
            [
                'label'     => esc_html__( 'Button Alignment', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'    => [
                        'title'     => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-left',
                    ],
                    'center'        => [
                        'title'     => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-center',
                    ],
                    'right'      => [
                        'title'     => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .meafe-load-more-button-wrap' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_controls()
    {
        /**
         * Query Controls
         */
        $this->query_controls();

        /**
         * Layout Controls
         */
        $this->layout_controls();

        /**
         * General Style Controls
         */
        $this->start_controls_section(
            'meafe_post_carousel_style_general_style',
            [
                'label'     => esc_html__( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'PC_bg_color',
            [
                'label'     => esc_html__( 'Post Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-carousel-card-inner' => 'background-color: {{VALUE}}',
                ],

            ]
        );

        $this->add_responsive_control(
            'PC_spacing',
            [
                'label'     => esc_html__( 'Spacing Between Items', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => ['size' => 20],
                'tablet_default' => ['size' => 15],
                'mobile_default' => ['size' => 10],
                'range'     => [
                    'px' => [
                        'min' => 5,
                        'max' => 50,
                    ],
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'PC_image_spacing',
            [
                'label'     => esc_html__( 'Spacing Between Image', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-wrapper-media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'PC_spacing_content',
            [
                'label'     => esc_html__( 'Content Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'  => [ 'top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-carousel-card-inner .meafe-entry-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'PC_border',
                'label'     => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-post-carousel-card-inner',
            ]
        );

        $this->add_control(
            'PC_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-carousel-card-inner' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'PC_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe-post-carousel-card-inner',
            ]
        );

        $this->add_responsive_control(
            'PC_alignment',
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
                'prefix_class' => 'meafe-post-carousel-alignment-',
            ]
        );

        $this->end_controls_section();

        /**
         * Color & Typography Style
         */
        $this->start_controls_section(
            'meafe_post_carousel_style_color_typography_style',
            [
                'label'     => esc_html__( 'Color & Typography', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'PC_title_style',
            [
                'label'     => esc_html__( 'Title Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'PC_title_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-title a' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->add_control(
            'PC_title_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-title:hover, {{WRAPPER}} .meafe-entry-title a:hover, {{WRAPPER}} .meafe-post-carousel-wrapper .details a:hover' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'PC_title_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-entry-title',
            ]
        );

        $this->add_responsive_control(
            'PC_title_margin',
            [
                'label'         => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ],
            ]
        );

        $this->add_control(
            'PC_excerpt_style',
            [
                'label'     => esc_html__( 'Excerpt Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'PC_excerpt_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-content p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'PC_excerpt_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-content p',
            ]
        );

        $this->add_control(
            'PC_content_height',
            [
                'label'     => esc_html__( 'Content Height', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'     => [
                    'px' => ['max' => 300],
                    '%' => ['max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-carousel-card-inner .meafe-entry-wrapper .meafe-content' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'PC_meta_style',
            [
                'label'     => esc_html__( 'Meta Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'PC_meta_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-wrapper .category--wrapper a, .meafe-post-carousel-wrapper .details .meafe-posted-by a, .meafe-entry-meta .meafe-date' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'PC_meta_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-entry-meta > div, {{WRAPPER}} .meafe-entry-wrapper .category--wrapper > a',
            ]
        );

        $this->end_controls_section();

        /**
         * Author Image Style
         */
        $this->start_controls_section(
            'meafe_post_carousel_style_author_image_style',
            [
                'label'     => esc_html__( 'Author Image', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'PC_show_author_avatar' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'PC_author_image_width',
            [
                'label'     => __( 'Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => ['size' => 40, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-author-avatar' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'PC_author_image_height',
            [
                'label'     => __( 'Height', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => ['size' => 40, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-author-avatar' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'PC_image_border',
                'selector'  => '{{WRAPPER}} .meafe-author-avatar .avatar',
            ]
        );

        $this->add_responsive_control(
            'PC_author_image_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'  => [ 'top' => 100, 'right' => 100, 'bottom' => 100, 'left' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-author-avatar .avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Read More Button Style
         */
        $this->read_more_button_style();

        /**
         * Load More Button Style
         */
        $this->load_more_button_style();

        $this->start_controls_section(
            'PC_nav_arrow',
            [
                'label' => __( 'Navigation :: Arrow', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'PC_swiper_nav' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'PC_arrow_size',
            [
                'label' => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .post-carousel.meafe-navigation-prev, {{WRAPPER}} .post-carousel.meafe-navigation-next' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'PC_arrow_width',
            [
                'label' => __( 'Width', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default' => [
                    'size' => 44,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-navigation-wrap .nav' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'PC_arrow_border_radius',
            [
                'label' => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'  => [ 'top' => 100, 'right' => 100, 'bottom' => 100, 'left' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-navigation-prev, {{WRAPPER}} .meafe-navigation-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs( 'PC_tabs_arrow' );

        $this->start_controls_tab(
            'PC_arrow_normal',
            [
                'label' => __( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'PC_arrow_normal_border',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'default' => '#5081F5',
                    ],
                ],
                'selector' => '{{WRAPPER}} .meafe-navigation-prev, {{WRAPPER}} .meafe-navigation-next',
            ]
        );

        $this->add_control(
            'PC_arrow_color',
            [
                'label' => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'default' => '#5081F5',
                'selectors' => [
                    '{{WRAPPER}} .meafe-navigation-prev, {{WRAPPER}} .meafe-navigation-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'PC_arrow_bg_color',
            [
                'label' => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .meafe-navigation-prev, {{WRAPPER}} .meafe-navigation-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'PC_arrow_hover',
            [
                'label' => __( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'PC_arrow_hover_border',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'default' => '#5081F5',
                    ],
                ],
                'selector' => '{{WRAPPER}} .meafe-navigation-prev, {{WRAPPER}} .meafe-navigation-next',
            ]
        );

        $this->add_control(
            'PC_arrow_hover_color',
            [
                'label' => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-navigation-prev:not(.swiper-button-disabled):hover, {{WRAPPER}} .meafe-navigation-next:not(.swiper-button-disabled):hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'PC_arrow_hover_bg_color',
            [
                'label' => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#5081F5',
                'selectors' => [
                    '{{WRAPPER}} .meafe-navigation-prev:not(.swiper-button-disabled):hover, {{WRAPPER}} .meafe-navigation-next:not(.swiper-button-disabled):hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'PC_nav_dots',
            [
                'label' => __( 'Navigation :: Dots', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'PC_swiper_dots' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'PC_dots_nav_spacing',
            [
                'label' => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
					'unit' => 'px',
					'size' => 8,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafe-swiper-pagination .swiper-pagination-bullet' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'PC_dots_nav_align',
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
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .meafe-swiper-pagination' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->start_controls_tabs( 'PC_tabs_dots' );
        $this->start_controls_tab(
            'PC_tab_dots_normal',
            [
                'label' => __( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'PC_dots_nav_size',
            [
                'label' => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
					'unit' => 'px',
					'size' => 8,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafe-swiper-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'PC_dots_nav_color',
            [
                'label' => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .meafe-swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'PC_tab_dots_active',
            [
                'label' => __( 'Active', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'PC_dots_nav_active_size',
            [
                'label' => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
					'unit' => 'px',
					'size' => 12,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafe-swiper-pagination .swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'PC_dots_nav_active_color',
            [
                'label' => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#5081F5',
                'selectors' => [
                    '{{WRAPPER}} .meafe-swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    /**
     * Function for sanitizing Hex color 
     */
    public function mega_elements_sanitize_hex_color( $color ){
        if ( '' === $color )
            return '';

        // 3 or 6 hex digits, or the empty string.
        if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
            return $color;
    }

    /**
     * Convert '#' to '%23'
    */
    public function mega_elements_hash_to_percent23( $color_code ){
        $color_code = str_replace( "#", "%23", $color_code );
        return $color_code;
    }

    public function get_query_args( $settings = [] )
    {
        $settings = wp_parse_args( $settings, [
            'post_type'         => 'post',
            'posts_ids'         => [],
            'orderby'           => 'date',
            'order'             => 'desc',
            'posts_per_page'    => 3,
            'offset'            => 0,
            'post__not_in'      => [],
        ]);

        $args = [
            'orderby'             => $settings['PC_orderby'],
            'order'               => $settings['PC_order'],
            'ignore_sticky_posts' => 1,
            'post_status'         => 'publish',
            'posts_per_page'      => $settings['PC_posts_per_page'],
            'offset'              => $settings['PC_offset'],
        ];

        $args['tax_query'] = [];
        $taxonomies = get_object_taxonomies( 'post', 'objects' );

        foreach ( $taxonomies as $object ) {
            $setting_key = 'PC_' . $object->name . '_ids';

            if ( !empty( $settings[$setting_key] ) ) {
                $args['tax_query'][] = [
                    'taxonomy' => $object->name,
                    'field' => 'term_id',
                    'terms' => $settings[$setting_key],
                ];
            }
        }

        if ( !empty( $args['tax_query'] ) ) {
            $args['tax_query']['relation'] = 'AND';
        }

        if ( !empty( $settings['PC_authors'] ) ) {
            $args['author__in'] = $settings['PC_authors'];
        }

        if ( !empty( $settings['PC_post__not_in'] ) ) {
            $args['post__not_in'] = $settings['PC_post__not_in'];
        }

        return $args;
    }

    public function get_nav_details(){
        $settings   = $this->get_settings_for_display();
        $nav        = $settings['PC_swiper_nav'];
        $nav_prev   = $settings['PC_prev_icon'];
        $nav_next   = $settings['PC_next_icon'];

        if( $nav === 'yes' ) {
            $return_all = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_alls = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_all_start = [ '', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_all_end = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '' ];
            
            if( $nav_prev['library'] != 'svg' && $nav_next['library'] != 'svg' ) {
                return ( [ '<i class="' . esc_attr($nav_prev['value'] ). '" aria-hidden="true"></i>', '<i class="' . esc_attr($nav_next['value']) . '" aria-hidden="true"></i>' ] );                    
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

    public function render_blog_template( $args, $settings )
    {
        $nav_icons = $this->get_nav_details();
        $blog_query = new \WP_Query($args);
        $settings = $this->get_settings_for_display();

        ob_start();

        if( $blog_query->have_posts() ) {
            echo '<div class="swiper-container"><div class="swiper-wrapper">';
            while( $blog_query->have_posts() ) {
                $blog_query->the_post();
                echo '<div class="meafe-post-carousel-card swiper-slide" data-id="' . esc_attr( get_the_ID() ) . '">
                    <div class="meafe-post-carousel-card-inner">';
                    if ( $settings['PC_layout_mode'] == '1'){
                        echo '<div class="meafe-entry-wrapper-media">';
                            if ( has_post_thumbnail() && $settings['PC_show_image'] == 'yes') {
                                echo '<figure class="meafe-entry-media image-wrapper">';
                                    echo '<a class="meafe-grid-post-link" href="' . esc_url( get_the_permalink() ) . '" title="' . esc_html( get_the_title() ) . '">';
                                    echo wp_get_attachment_image( get_post_thumbnail_id(), $settings[ 'meafe_post_carousel_size' ] );
                                echo '</a>';
                                echo '</figure>';
                            }
                            if ( 'post' === get_post_type() && $settings['PC_show_category'] ) {
                                $categories_list = get_the_category_list( ' ' );
                                if ( $categories_list ) {
                                    echo '<span class="category--wrapper" itemprop="about">' . wp_kses_post($categories_list) . '</span>';
                                }
                            }
                        echo '</div>';
                        echo '<div class="meafe-entry-wrapper">';
                            if ( $settings['PC_show_title'] ) {
                                echo '<h2 class="meafe-entry-title"><a class="meafe-grid-post-link" href="' . esc_url( get_the_permalink() ). '" title="' . esc_html( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</a></h2>';
                            }
                            
                            echo '<div class="meafe-entry-meta details">';
                                if ( $settings['PC_show_author'] || $settings['PC_show_author_avatar'] ) {
                                    echo '<div class="meafe-posted-by author">';
                                        if ( $settings['PC_show_author_avatar'] ) {
                                            echo '<div class="meafe-author-avatar">
                                                <a href="' . esc_url(get_author_posts_url( get_the_author_meta('ID') )) . '">' . get_avatar( get_the_author_meta( 'ID' ), 96 ) . '</a>
                                            </div>';
                                        }
                                        if ( $settings['PC_show_author'] ) echo get_the_author_posts_link();
                                    echo '</div>';
                                }
                                if ( $settings['PC_show_date'] ) echo '<div class="meafe-posted-on meafe-date"><time datetime="' . esc_attr(get_the_date()) . '">' . esc_html(get_the_date()) . '</time></div>';
                                
                            echo '</div>';

                            if ( $settings['PC_show_excerpt'] ) {
                                echo '<div class="meafe-entry-content meafe-content">
                                    <p>' . wp_trim_words( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ), $settings['PC_excerpt_length'], $settings['PC_excerpt_expanison_indicator']) . '</p>';
                                echo '</div>';
                            }

                            if ( $settings['PC_show_read_more_button'] && $settings['PC_read_more_button_text'] ) {
                                $fill_color = ( isset( $settings['PC_read_more_btn_color'] ) && $settings['PC_read_more_btn_color'] ) ? $settings['PC_read_more_btn_color'] : "#5081f5";
                                echo '<div class="meafe-post-carousel-button read-more">';
                                echo '<a href="' . esc_url( get_the_permalink() ) . '" class="meafe-post-elements-readmore-btn">' . esc_html( $settings['PC_read_more_button_text'] ) . '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                </a>';
                                echo '</div>';
                            }
                        echo '</div>';
                    } else if ( $settings['PC_layout_mode'] == '2'){
                        echo '<div class="meafe-entry-wrapper">';
                            if ( 'post' === get_post_type() && $settings['PC_show_category'] ) {
                                $categories_list = get_the_category_list( ' ' );
                                if ( $categories_list ) {
                                    echo '<span class="category--wrapper" itemprop="about">' . wp_kses_post($categories_list) . '</span>';
                                }
                            }

                            if ( $settings['PC_show_title'] ) {
                                echo '<h2 class="meafe-entry-title"><a class="meafe-grid-post-link" href="' . esc_url( get_the_permalink() ). '" title="' . esc_html( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</a></h2>';
                            }

                            echo '<div class="meafe-entry-meta details">';
                                if ( $settings['PC_show_author'] || $settings['PC_show_author_avatar'] ) {
                                    echo '<div class="meafe-posted-by author">';
                                        if ( $settings['PC_show_author_avatar'] ) {
                                            echo '<div class="meafe-author-avatar">
                                                <a href="' . esc_url(get_author_posts_url( get_the_author_meta('ID') )) . '">' . get_avatar( get_the_author_meta( 'ID' ), 96 ) . '</a>
                                            </div>';
                                        }
                                        if ( $settings['PC_show_author'] ) echo get_the_author_posts_link();
                                    echo '</div>';
                                }
                                if ( $settings['PC_show_date'] ) echo '<div class="meafe-posted-on meafe-date"><time datetime="' . esc_attr(get_the_date()) . '">' . esc_html(get_the_date()) . '</time></div>';
                            echo '</div>';
                        echo '</div>';
                        
                        echo '<div class="meafe-entry-wrapper-media">';
                            if ( has_post_thumbnail() && $settings['PC_show_image'] == 'yes') {
                                echo '<figure class="meafe-entry-media image-wrapper">';
                                    echo '<a class="meafe-grid-post-link" href="' . esc_url( get_the_permalink() ) . '" title="' . esc_html( get_the_title() ) . '">';
                                    echo wp_get_attachment_image( get_post_thumbnail_id() );
                                echo '</a>';
                                echo '</figure>';
                            }
                        echo '</div>';
                    }
                    echo '</div>
                </div>';
            }
            echo '</div></div>';

            if( $settings['PC_swiper_nav'] == 'yes') { ?>
                <!-- If we need navigation buttons -->
                <div class="meafe-navigation-wrap">
                    <div class="post-carousel meafe-navigation-prev nav">
                        <?php echo $nav_icons[0]; ?>
                    </div>
                    <div class="post-carousel meafe-navigation-next nav">
                        <?php echo $nav_icons[1]; ?>
                    </div>
                </div>
            <?php } 
            
            if( $settings['PC_swiper_dots'] == 'yes') { ?>
                <!-- If we need pagination -->
                <div class="post-carousel meafe-swiper-pagination"></div>
            <?php } 

            
        }  else { ?>
            <p class="no-posts-found">
                <?php esc_html_e( 'No posts found!', 'mega-elements-addons-for-elementor' ); ?>
            </p>
        <?php 
        }

        wp_reset_postdata();

        return ob_get_clean();
    }

    protected function render()
    {
        $settings = $this->get_settings();
        $args = $this->get_query_args($settings);

        $settings_arry = [
            'PC_show_image'                  => $settings['PC_show_image'],
            'PC_show_title'                  => $settings['PC_show_title'],
            'PC_show_excerpt'                => $settings['PC_show_excerpt'],
            'PC_show_author'                 => $settings['PC_show_author'],
            'PC_show_author_avatar'          => $settings['PC_show_author_avatar'],
            'PC_show_date'                   => $settings['PC_show_date'],
            'PC_show_category'               => $settings['PC_show_category'],
            'PC_excerpt_length'              => intval( $settings['PC_excerpt_length'], 10 ),
            'PC_show_read_more_button'       => $settings['PC_show_read_more_button'],
            'PC_read_more_btn_color'         => $settings['PC_read_more_btn_color'],
            'PC_read_more_button_text'       => $settings['PC_read_more_button_text'],
            'PC_excerpt_expanison_indicator' => $settings['PC_excerpt_expanison_indicator'],
            'PC_layout_mode'                 => $settings['PC_layout_mode'],
            'PC_orderby'                     => $settings['PC_orderby'],
        ];

        $this->add_render_attribute(
            'post_carousel_wrapper',
            [
                'id' => 'meafe-post-grid-' . esc_attr( $this->get_id() ),
                'class' => [
                    'meafe-post-carousel-wrapper layout-' . esc_attr($settings['PC_layout_mode']),
                ],
            ]
        );

        if( $settings['PC_posts_per_page'] <= 2 ) {
            $this->add_render_attribute( 'post_carousel_wrapper', 'class', 'wrapper-alignment-center' );
        }

        echo '<div ' . $this->get_render_attribute_string( 'post_carousel_wrapper' ) . '>
                ' . self::render_blog_template( $args, $settings_arry ) . '
        </div>';
    }
}