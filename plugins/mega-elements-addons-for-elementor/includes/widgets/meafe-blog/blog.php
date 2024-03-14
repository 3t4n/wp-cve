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

class MEAFE_Blog extends Widget_Base
{

    public function get_name() {
        return 'meafe-blog';
    }

    public function get_title() {
        return esc_html__( 'Blog', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-blog';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-blog'];
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
            'meafe_blog_content_query_settings',
            [
                'label'     => esc_html__( 'Query Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bbcqs_blog_authors', [
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
                    'bbcqs_blog_' . $taxonomy . '_ids',
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
            'bbcqs_blog_post__not_in',
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
            'bbcqs_blog_posts_per_page',
            [
                'label'     => esc_html__( 'Posts Per Page', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '3',
            ]
        );

        $this->add_control(
            'bbcqs_blog_orderby',
            [
                'label'     => esc_html__( 'Order By', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $this->get_post_orderby_options(),
                'default'   => 'date',

            ]
        );

        $this->add_control(
            'bbcqs_blog_order',
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
            'bbcqs_blog_offset',
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
         * Blog Layout Settings
        */
        $this->start_controls_section(
            'meafe_blog_content_layout_settings',
            [
                'label'     => esc_html__( 'Layout Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bbcls_blog_layout_mode',
            [
                'label'     => esc_html__( 'Layout', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => [
                    '1'   => esc_html__( 'Layout One', 'mega-elements-addons-for-elementor' ),
                    '2'   => esc_html__( 'Layout Two', 'mega-elements-addons-for-elementor' ),
                    '3'   => esc_html__( 'Layout Three', 'mega-elements-addons-for-elementor' ),
                    '4'   => esc_html__( 'Layout Four', 'mega-elements-addons-for-elementor' ),
                    '5'   => esc_html__( 'Layout Five', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $this->add_group_control(
		    Group_Control_Image_Size::get_type(),
		    [
			    'name'      => 'meafe-featured-page',
			    'default'   => 'meafe-blog-one',
		    ]
	    );
            
        $this->add_control(
            'bbcls_blog_show_load_more',
            [
                'label'     => esc_html__( 'Show Load More', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
            ]
        );

        $this->add_control(
            'bbcls_blog_show_load_more_text',
            [
                'label'     => esc_html__( 'Load More Text', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'label_block' => false,
                'default'   => esc_html__( 'Load More', 'mega-elements-addons-for-elementor' ),
                'condition' => [
                    'bbcls_blog_show_load_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'bbcls_blog_show_load_more_url',
            [
                'label'     => esc_html__( 'Load More URL', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'label_block' => false,
                'default'   => esc_html__( '#', 'mega-elements-addons-for-elementor' ),
                'condition' => [
                    'bbcls_blog_show_load_more' => 'yes',
                    'bbcls_blog_show_load_more_text!' => '',
                ],
            ]
        );

    
        $this->add_control(
            'bbcls_blog_show_image',
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
            'bbcls_blog_show_title',
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
            'bbcls_blog_show_excerpt',
            [
                'label'        => esc_html__( 'Show excerpt', 'mega-elements-addons-for-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition' => [
                    'bbcls_blog_layout_mode!' => '5',
                ],
            ]
        );

        $this->add_control(
            'bbcls_blog_excerpt_length',
            [
                'label'     => esc_html__( 'Excerpt Words', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '10',
                'condition' => [
                    'bbcls_blog_show_excerpt' => 'yes',
                    'bbcls_blog_layout_mode!' => '5',
                ],
            ]
        );

        $this->add_control(
            'bbcls_blog_excerpt_expanison_indicator',
            [
                'label'     => esc_html__( 'Expanison Indicator', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'label_block' => false,
                'default'   => esc_html__( '...', 'mega-elements-addons-for-elementor' ),
                'condition' => [
                    'bbcls_blog_show_excerpt' => 'yes',
                    'bbcls_blog_layout_mode!' => '5',
                ],
            ]
        );

        $this->add_control(
            'bbcls_blog_show_read_more_button',
            [
                'label'     => esc_html__( 'Show Read More Button', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
                'condition' => [
                    'bbcls_blog_layout_mode!' => '5',
                ]
            ]
        );

        $this->add_control(
            'bbcls_blog_read_more_button_text',
            [
                'label'     => esc_html__( 'Button Text', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Read More', 'mega-elements-addons-for-elementor' ),
                'condition' => [
                    'bbcls_blog_show_read_more_button' => 'yes',
                    'bbcls_blog_layout_mode!' => '5',
                ],
            ]
        );
        
        $this->add_control(
            'bbcls_blog_show_author',
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
            'bbcls_blog_show_author_avatar',
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
            'bbcls_blog_show_date',
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
            'bbcls_blog_show_category',
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
            'meafe_blog_style_read_more_style',
            [
                'label'     => esc_html__( 'Read More Button Style', 'mega-elements-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'bbcls_blog_show_read_more_button' => 'yes',
                    'bbcls_blog_layout_mode!' => '5',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bbsrms_blog_read_more_btn_typography',
                'selector'  => '{{WRAPPER}} .meafe-post-elements-readmore-btn',
            ]
        );

        $this->start_controls_tabs( 'bbsrms_blog_read_more_button_tabs' );

        $this->start_controls_tab(
            'bbsrms_blog_read_more_button_style_normal',
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bbsrms_blog_read_more_btn_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-blog-wrapper .meafe-blog-button.read-more a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'bbsrms_blog_read_more_btn_background',
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
                'name'      => 'bbsrms_blog_read_more_btn_border',
                'label'     => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-post-elements-readmore-btn',
            ]
        );

        $this->add_responsive_control(
            'bbsrms_blog_read_more_btn_border_radius',
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
            'bbsrms_blog_read_more_button_style_hover',
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bbsrms_blog_read_more_btn_hover_color',
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
                'name'      => 'bbsrms_blog_read_more_btn_hover_background',
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
                'name'      => 'bbsrms_blog_read_more_btn_hover_border',
                'label'     => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-post-elements-readmore-btn:hover',
            ]
        );

        $this->add_responsive_control(
            'bbsrms_blog_read_more_btn_border_hover_radius',
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
            'bbsrms_blog_read_more_btn_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-blog-button.read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbsrms_blog_read_more_btn_margin',
            [
                'label'     => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-blog-button.read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
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
            'meafe_blog_style_load_more_style',
            [
                'label'     => esc_html__( 'Load More Button Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'bbcls_blog_show_load_more' => ['yes', '1', 'true'],
                ],
            ]
        );

        $this->add_responsive_control(
            'bbslms_blog_load_more_btn_padding',
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
            'bbslms_blog_load_more_btn_margin',
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
                'name'      => 'bbslms_blog_load_more_btn_typography',
                'selector'  => '{{WRAPPER}} .meafe-load-more-button',
            ]
        );

        $this->start_controls_tabs( 'bbslms_blog_load_more_btn_tabs' );

        // Normal State Tab
        $this->start_controls_tab(
            'bbslms_blog_load_more_btn_normal', 
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' )
            ]
        );

        $this->add_control(
            'bbslms_blog_load_more_btn_normal_text_color',
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
            'bbslms_blog_load_more_btn_normal_bg_color',
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
                'name'      => 'bbslms_blog_load_more_btn_normal_border',
                'label'     => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-load-more-button',
            ]
        );

        $this->add_control(
            'bbslms_blog_load_more_btn_border_radius',
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
                'name'      => 'bbslms_blog_load_more_btn_shadow',
                'selector'  => '{{WRAPPER}} .meafe-load-more-button',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab(
            'bbslms_blog_load_more_btn_hover', 
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ) 
            ] 
        );

        $this->add_control(
            'bbslms_blog_load_more_btn_hover_text_color',
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
            'bbslms_blog_load_more_btn_hover_bg_color',
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
            'bbslms_blog_load_more_btn_hover_border_color',
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
                'name'      => 'bbslms_blog_load_more_btn_hover_shadow',
                'selector'  => '{{WRAPPER}} .meafe-load-more-button:hover',
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'bbslms_blog_loadmore_button_alignment',
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
            'meafe_blog_style_general_style',
            [
                'label'     => esc_html__( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bbsgs_blog_bg_color',
            [
                'label'     => esc_html__( 'Post Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-blog-card-inner' => 'background-color: {{VALUE}}',
                ],

            ]
        );

        $this->add_responsive_control(
            'bbsgs_blog_spacing',
            [
                'label'     => esc_html__( 'Spacing Between Items', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-blog-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-blog-wrapper' => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right: -{{RIGHT}}{{UNIT}}'
                ],
            ]
        );

        $this->add_responsive_control(
            'bbsgs_blog_image_spacing',
            [
                'label'     => esc_html__( 'Spacing Between Image', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-media.image-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbsgs_blog_spacing_content',
            [
                'label'     => esc_html__( 'Content Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-blog-innerwrapper .meafe-entry-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'bbsgs_blog_border',
                'label'     => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-blog-card-inner',
            ]
        );

        $this->add_control(
            'bbsgs_blog_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .meafe-blog-card-inner' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'bbsgs_blog_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe-blog-card-inner',
            ]
        );

        $this->add_responsive_control(
            'bbsgs_blog_alignment',
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
                'prefix_class' => 'meafe-blog-alignment-',
            ]
        );

        $this->end_controls_section();

        /**
         * Color & Typography Style
         */
        $this->start_controls_section(
            'meafe_blog_style_color_typography_style',
            [
                'label'     => esc_html__( 'Color & Typography', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bbscts_blog_title_style',
            [
                'label'     => esc_html__( 'Title Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bbscts_blog_title_color',
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
            'bbscts_blog_title_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-title:hover, {{WRAPPER}} .meafe-entry-title a:hover, {{WRAPPER}} .meafe-blog-wrapper .details a:hover' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bbscts_blog_title_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-entry-title',
            ]
        );

        $this->add_responsive_control(
            'bbscts_blog_title_margin',
            [
                'label'         => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control(
            'bbscts_blog_excerpt_style',
            [
                'label'     => esc_html__( 'Excerpt Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'bbcls_blog_layout_mode!' => '5',
                ],
            ]
        );

        $this->add_control(
            'bbscts_blog_excerpt_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-content p' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'bbcls_blog_layout_mode!' => '5',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bbscts_blog_excerpt_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-content p',
                'condition' => [
                    'bbcls_blog_layout_mode!' => '5',
                ],
            ]
        );

        $this->add_control(
            'bbscts_blog_content_height',
            [
                'label'     => esc_html__( 'Content Height', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'     => [
                    'px' => ['max' => 300],
                    '%' => ['max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-blog-card-inner .meafe-entry-wrapper .meafe-content' => 'height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'bbcls_blog_layout_mode!' => '5',
                ],
            ]
        );

        $this->add_control(
            'bbscts_blog_meta_style',
            [
                'label'     => esc_html__( 'Meta Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bbscts_blog_meta_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-wrapper .category--wrapper a, .meafe-blog-wrapper .details .meafe-posted-by a, .meafe-entry-meta .meafe-date' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bbscts_blog_meta_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-entry-meta > div, {{WRAPPER}} .meafe-entry-wrapper .category--wrapper > a',
            ]
        );

        $this->add_control(
            'bbscts_blog_date_color',
            [
                'label'     => esc_html__( 'Date Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-posted-on-wrapper' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'bbcls_blog_layout_mode' => '5',
                ],
            ]
        );

        $this->add_control(
            'bbscts_blog_date_bg_color',
            [
                'label'     => esc_html__( 'Date Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5081F5',
                'selectors' => [
                    '{{WRAPPER}}  .meafe-posted-on-wrapper' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'bbcls_blog_layout_mode' => '5',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bbscts_date_meta_typography',
                'label'     => esc_html__( 'Date Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-posted-on-wrapper',
                'condition' => [
                    'bbcls_blog_layout_mode' => '5',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * Author Image Style
         */
        $this->start_controls_section(
            'meafe_blog_style_author_image_style',
            [
                'label'     => esc_html__( 'Author Image', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'bbcls_blog_show_author_avatar' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbscts_blog_author_image_width',
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
                'selectors' => [
                    '{{WRAPPER}} .meafe-author-avatar' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbscts_blog_author_image_height',
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
                'selectors' => [
                    '{{WRAPPER}} .meafe-author-avatar' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'bbscts_blog_image_border',
                'selector'  => '{{WRAPPER}} .meafe-author-avatar',
            ]
        );

        $this->add_responsive_control(
            'bbscts_blog_author_image_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'  => [ 'top' => 100, 'right' => 100, 'bottom' => 100, 'left' => 100, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-author-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
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
            'orderby'             => $settings['bbcqs_blog_orderby'],
            'order'               => $settings['bbcqs_blog_order'],
            'ignore_sticky_posts' => 1,
            'post_status'         => 'publish',
            'posts_per_page'      => $settings['bbcqs_blog_posts_per_page'],
            'offset'              => $settings['bbcqs_blog_offset'],
        ];

        
        $args['tax_query'] = [];
        $taxonomies = get_object_taxonomies( 'post', 'objects' );

        foreach ( $taxonomies as $object ) {
            $setting_key = 'bbcqs_blog_' . $object->name . '_ids';

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

        if ( !empty( $settings['bbcqs_blog_authors'] ) ) {
            $args['author__in'] = $settings['bbcqs_blog_authors'];
        }

        if ( !empty( $settings['bbcqs_blog_post__not_in'] ) ) {
            $args['post__not_in'] = $settings['bbcqs_blog_post__not_in'];
        }

        return $args;
    }

    public function render_blog_template( $args, $settings )
    {

        $blog_query = new \WP_Query($args);

        $settings = $this->get_settings_for_display();
         
        ob_start();

        if( $blog_query->have_posts() ) {
            while( $blog_query->have_posts() ) {
                $blog_query->the_post();
                echo '<div class="meafe-blog-card" data-id="' . esc_attr( get_the_ID() ) . '">
                    <div class="meafe-blog-card-inner">';
                        if ( has_post_thumbnail() && $settings['bbcls_blog_show_image'] == 'yes') {
                            echo '<figure class="meafe-entry-media image-wrapper">';
                                echo '<a class="meafe-grid-post-link" href="' . esc_url( get_the_permalink() ) . '" title="' . esc_html( get_the_title() ) . '">';
                                echo wp_get_attachment_image( get_post_thumbnail_id(), $settings[ 'meafe-featured-page_size' ] );
                            echo '</a>';
                            if ( $settings['bbcls_blog_layout_mode'] === '5' ){
                                if ( $settings['bbcls_blog_show_date'] ) echo '<div class="meafe-posted-on-wrapper">
                                <div class="meafe-posted-on meafe-day"><time datetime="' . esc_attr( get_the_date() ). '">' . date_i18n( esc_html__( 'd ', 'mega-elements-addons-for-elementor' ), strtotime( get_the_date() ) ). '</time></div>
                            <div class="meafe-posted-on meafe-time"><time datetime="' . esc_attr( get_the_date() ). '">' . date_i18n( esc_html__( 'M', 'mega-elements-addons-for-elementor' ), strtotime( get_the_date() ) ). '</time></div></div>';
                            }
                            echo '</figure>';
                        }
                        echo '<div class="meafe-entry-wrapper category--main">';

                            if ( 'post' === get_post_type() && $settings['bbcls_blog_show_category'] ) {
                                $categories_list = get_the_category_list( ' ' );
                                if ( $categories_list ) {
                                    echo '<span class="category--wrapper" itemprop="about">' . $categories_list . '</span>';
                                }
                            }

                            if ( $settings['bbcls_blog_show_title'] ) {
                                echo '<h2 class="meafe-entry-title"><a class="meafe-grid-post-link" href="' . esc_url( get_the_permalink() ). '" title="' . esc_html( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</a></h2>';
                            }
                            
                            echo '<div class="meafe-entry-meta details">';
                                if ( $settings['bbcls_blog_show_author'] || $settings['bbcls_blog_show_author_avatar'] ) {
                                    echo '<div class="meafe-posted-by author">';
                                        if ( $settings['bbcls_blog_show_author_avatar'] ) {
                                            echo '<div class="meafe-author-avatar">
                                                <a href="' . esc_url( get_author_posts_url( get_the_author_meta('ID') ) ). '">' . get_avatar( get_the_author_meta( 'ID' ), 96 ) . '</a>
                                            </div>';
                                        }
                                        if ( $settings['bbcls_blog_show_author'] ) echo get_the_author_posts_link();
                                    echo '</div>';
                                }
                                if ( $settings['bbcls_blog_layout_mode'] !== '5' ){
                                        if ( $settings['bbcls_blog_show_date'] ) echo '<div class="meafe-posted-on meafe-date"><time datetime="' . esc_attr( get_the_date() ) . '">' . esc_html( get_the_date() ) . '</time></div>';
                                }
                                
                            echo '</div>';

                            if ( $settings['bbcls_blog_show_excerpt'] ) {
                                echo '<div class="meafe-entry-content meafe-content">
                                    <p>' . wp_kses_post( wp_trim_words( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ), $settings['bbcls_blog_excerpt_length'], $settings['bbcls_blog_excerpt_expanison_indicator']) ). '</p>';
                                echo '</div>';
                            }

                            if ( $settings['bbcls_blog_show_read_more_button'] && $settings['bbcls_blog_read_more_button_text'] ) {
                                echo '<div class="meafe-blog-button read-more">';
                                echo '<a href="' . esc_url( get_the_permalink() ) . '" class="meafe-post-elements-readmore-btn">' . esc_attr( $settings['bbcls_blog_read_more_button_text'] ) . '<svg xmlns="http://www.w3.org/2000/svg" width="11.249"
                                    height="7.741" viewBox="0 0 11.249 7.741">
                                    <path id="Path_2" data-name="Path 2"
                                        d="M11.1,45.417,7.748,42.069a.523.523,0,0,0-.74.74l2.455,2.455H.523a.523.523,0,0,0,0,1.046h8.94L7.008,48.764a.523.523,0,0,0,.74.74L11.1,46.156A.523.523,0,0,0,11.1,45.417Z"
                                        transform="translate(0 -41.916)" fill="currentcolor"/>
                                </svg></a>';
                                echo '</div>';
                            }
                        echo '</div>';
                    echo '</div>
                </div>';
            }
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
            'bbcls_blog_show_image'                  => $settings['bbcls_blog_show_image'],
            'bbcls_blog_show_title'                  => $settings['bbcls_blog_show_title'],
            'bbcls_blog_show_excerpt'                => $settings['bbcls_blog_show_excerpt'],
            'bbcls_blog_show_author'                 => $settings['bbcls_blog_show_author'],
            'bbcls_blog_show_author_avatar'          => $settings['bbcls_blog_show_author_avatar'],
            'bbcls_blog_show_date'                   => $settings['bbcls_blog_show_date'],
            'bbcls_blog_show_category'               => $settings['bbcls_blog_show_category'],
            'bbcls_blog_excerpt_length'              => intval( $settings['bbcls_blog_excerpt_length'], 10 ),
            'bbcls_blog_show_read_more_button'       => $settings['bbcls_blog_show_read_more_button'],
            'bbsrms_blog_read_more_btn_color'        => $settings['bbsrms_blog_read_more_btn_color'],
            'bbcls_blog_read_more_button_text'       => $settings['bbcls_blog_read_more_button_text'],
            'bbcls_blog_show_load_more'              => $settings['bbcls_blog_show_load_more'],
            'bbcls_blog_show_load_more_text'         => $settings['bbcls_blog_show_load_more_text'],
            'bbcls_blog_show_load_more_url'         => $settings['bbcls_blog_show_load_more_url'],
            'bbcls_blog_excerpt_expanison_indicator' => $settings['bbcls_blog_excerpt_expanison_indicator'],
            'bbcls_blog_layout_mode'                 => $settings['bbcls_blog_layout_mode'],
            'bbcqs_blog_orderby'                     => $settings['bbcqs_blog_orderby'],
        ];

        $this->add_render_attribute(
            'blog_wrapper',
            [
                'id' => 'meafe-post-grid-' . esc_attr( $this->get_id() ),
                'class' => [
                    'meafe-blog-wrapper layout-' . $settings['bbcls_blog_layout_mode'],
                ],
            ]
        );

        if( $settings['bbcqs_blog_posts_per_page'] <= 2 ) {
            $this->add_render_attribute( 'blog_wrapper', 'class', 'wrapper-alignment-center' );
        }

        echo '<div ' . $this->get_render_attribute_string( 'blog_wrapper' ) . '>
            <div class="meafe-blog-innerwrapper">
                ' . self::render_blog_template( $args, $settings_arry ) . '
            </div>
        </div>';

        if ( 'yes' == $settings['bbcls_blog_show_load_more'] && $settings['bbcls_blog_show_load_more_text'] && $settings['bbcls_blog_show_load_more_url']  ) {
            echo '<div class="meafe-load-more-button-wrap">
                <a href="' . esc_url($settings['bbcls_blog_show_load_more_url']) . '" class = "meafe-load-more-button">' . esc_html( $settings['bbcls_blog_show_load_more_text'] ) . '</a>
            </div>';
        }
    }

    protected function content_template() {
    }
}
