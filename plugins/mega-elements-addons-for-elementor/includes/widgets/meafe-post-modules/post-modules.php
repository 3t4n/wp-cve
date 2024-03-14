<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class MEAFE_Post_Modules extends Widget_Base
{

    public function get_name() {
        return 'meafe-post-modules';
    }

    public function get_title() {
        return esc_html__( 'Post Modules', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-post-modules';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-post-modules'];
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
         * Post Modules Query Settings
        */
        $taxonomies = get_taxonomies( [], 'objects' );

        $this->start_controls_section(
            'meafe_post_modules_content_query_settings',
            [
                'label'     => esc_html__( 'Query Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'PM_authors', [
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
                    'PM_' . $taxonomy . '_ids',
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
            'PM_post__not_in',
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
            'PM_orderby',
            [
                'label'     => esc_html__( 'Order By', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $this->get_post_orderby_options(),
                'default'   => 'date',

            ]
        );

        $this->add_control(
            'PM_order',
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
            'PM_offset',
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
            'meafe_post_modules_content_layout_settings',
            [
                'label'     => esc_html__( 'Layout Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'PM_layout_mode',
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

        $this->add_control(
            'PM_item_spacing',
            [
                'label'     => esc_html__( 'Item Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px','em'],
                'default'   => [
					'size' => 20,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-modules-innerwrapper' => 'gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'PM_layout_alignment',
            [
                'label'     => esc_html__( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'      => [
                        'title' => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'right'     => [
                        'title' => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'prefix_class' => 'meafe-post-modules-alignment-',
                'condition' => [
                    'PM_layout_mode' => '4',
                ]
            ]
        );

        $this->add_control(
            'PM_show_title',
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
            'PM_show_author',
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
            'PM_show_author_avatar',
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
            'PM_show_date',
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
            'PM_show_category',
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
            'meafe_post_modules_style_general_style',
            [
                'label'     => esc_html__( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'PM_row_spacing',
            [
                'label'     => esc_html__( 'Post Row Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px','em'],
                'default'   => [
					'size' => 20,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-modules.items-right' => 'gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'PM_column_spacing',
            [
                'label'     => esc_html__( 'Post Column Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px','em'],
                'default'   => [
					'size' => 30,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-modules.items-right .meafe-post-modules-card .meafe-post-modules-card-inner' => 'gap: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'PM_layout_mode!' => '1',
                ],
            ]
        );

        $this->add_responsive_control(
            'PM_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px','%','em'],
                'default'   => [
					'size' => 0,
				],
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-modules.items-right .meafe-post-modules-card:not(:last-child)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ],
                'condition' => [
                    'PM_layout_mode!' => '3',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'PM_border',
                'label'     => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-post-modules.items-right .meafe-post-modules-card:not(:last-child)',
                'condition' => [
                    'PM_layout_mode!' => '3',
                ],
            ]
        );

        $this->end_controls_section();

        // Featured Post Stying

        $this->start_controls_section(
            'meafe_post_modules_style_featured_post_style',
            [
                'label'     => esc_html__( 'Feature Post Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'PM_featured_title_style',
            [
                'label'     => esc_html__( 'Featured Post Title Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'PM_featured_title_color',
            [
                'label'     => esc_html__( 'Title', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .items-left .meafe-entry-title a' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->add_control(
            'PM_featured_title_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .items-left .meafe-entry-title:hover, {{WRAPPER}} .items-left .meafe-entry-title a:hover, {{WRAPPER}} .items-left .meafe-post-modules-wrapper .details a:hover' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'PM_featured_title_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .items-left .meafe-entry-title',
            ]
        );

        $this->add_responsive_control(
            'PM_featured_title_margin',
            [
                'label'         => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .items-left .meafe-entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control(
            'PM_featured_meta_style',
            [
                'label'     => esc_html__( 'Feature Post Meta Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'PM_featured_meta_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-modules-wrapper .meafe-post-modules-innerwrapper .items-left .meafe-entry-wrapper .category--wrapper a:last-child::before, .meafe-post-modules-wrapper .meafe-post-modules-innerwrapper .items-left .meafe-entry-wrapper :is(.meafe-posted-by, .meafe-posted-on)+div::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .items-left .meafe-entry-wrapper .category--wrapper a, .meafe-post-modules-wrapper .items-left  .meafe-posted-by a, .meafe-post-modules-wrapper .items-left .details .meafe-date time' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'PM_featured_meta_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .items-left .meafe-entry-meta > div, {{WRAPPER}} .items-left .meafe-entry-wrapper .category--wrapper > a',
            ]
        );

      
        $this->end_controls_section();

        /**
         * Color & Typography Style
         */
        $this->start_controls_section(
            'meafe_post_modules_style_color_typography_style',
            [
                'label'     => esc_html__( 'Color & Typography', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'PM_title_style',
            [
                'label'     => esc_html__( 'Title Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );


        $this->add_control(
            'PM_title_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .items-right .meafe-entry-title a' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->add_control(
            'PM_title_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .items-right .meafe-entry-title:hover, {{WRAPPER}} .items-right .meafe-entry-title a:hover, {{WRAPPER}} .meafe-post-modules .items-right .meafe-post-modules-wrapper .details a:hover' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'PM_title_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .items-right .meafe-entry-title',
            ]
        );

        $this->add_responsive_control(
            'PM_title_margin',
            [
                'label'         => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .items-right .meafe-entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control(
            'PM_meta_style',
            [
                'label'     => esc_html__( 'Meta Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'PM_meta_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-post-modules-wrapper .meafe-post-modules-innerwrapper .items-right .meafe-entry-wrapper .category--wrapper a:last-child::before, .meafe-post-modules-wrapper .meafe-post-modules-innerwrapper .items-right .meafe-entry-wrapper :is(.meafe-posted-by, .meafe-posted-on)+div::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .items-right .meafe-entry-wrapper .category--wrapper a, .meafe-post-modules-wrapper .items-right .meafe-posted-by a, .meafe-post-modules-wrapper  .items-right .meafe-entry-meta .meafe-date time' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'PM_meta_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .items-right .meafe-entry-meta > div, {{WRAPPER}} .items-right .meafe-entry-wrapper .category--wrapper > a',
            ]
        );

        $this->end_controls_section();

        /**
         * Author Image Style
         */
        $this->start_controls_section(
            'meafe_post_modules_style_author_image_style',
            [
                'label'     => esc_html__( 'Author Image', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'PM_show_author_avatar' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'PM_author_image_width',
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
            'PM_author_image_height',
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
                'name'      => 'PM_image_border',
                'selector'  => '{{WRAPPER}} .meafe-author-avatar',
            ]
        );

        $this->add_responsive_control(
            'PM_author_image_border_radius',
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
         * Feature Image Style
         */
        $this->start_controls_section(
            'meafe_post_modules_style_featured_image_style',
            [
                'label'     => esc_html__( 'Feature Image', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'PM_feature_image_border',
                'selector'  => '{{WRAPPER}} .meafe-post-modules-card .meafe-entry-media img',
            ]
        );

        $this->add_responsive_control(
            'PM_feature_image_border_radius',
            [
                'label'     => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'  => [ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-media.image-wrapper img, {{WRAPPER}} .meafe-entry-media::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        // layout 5 box styling  
        $this ->start_controls_section(
            'PM_box_styling',
            [
                'label'     => esc_html__( 'Box Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'PM_layout_mode' => '5',
                ]
            ]
        );
            
        $this->add_control(
            'PM_box_background_color',
            [
                'label' => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .layout-5 .meafe-post-modules.items-left .meafe-entry-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'PM_box_spacing',
            [
                'label' => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .layout-5 .meafe-post-modules.items-left .meafe-entry-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'PM_box_border_radius',
            [
                'label' => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .layout-5 .meafe-post-modules.items-left .meafe-entry-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );


        $this ->end_controls_section();
    }

    public function get_query_args( $settings = [] )
    {
        $no_of_post = 3;
        if( $settings['PM_layout_mode'] == '1' ){
            $no_of_post = 3;
        }elseif( $settings['PM_layout_mode'] == '2' ){
            $no_of_post = 4;
        }elseif( $settings['PM_layout_mode'] == '3' || $settings['PM_layout_mode'] == '4' || $settings['PM_layout_mode'] == '5' ){
            $no_of_post = 5;
        }

        $settings = wp_parse_args( $settings, [
            'post_type'         => 'post',
            'posts_ids'         => [],
            'orderby'           => 'date',
            'order'             => 'desc',
            'posts_per_page'    => $no_of_post,
            'offset'            => 0,
            'post__not_in'      => [],
        ]);

        $args = [
            'orderby'             => $settings['PM_orderby'],
            'order'               => $settings['PM_order'],
            'ignore_sticky_posts' => 1,
            'post_status'         => 'publish',
            'posts_per_page'      => $no_of_post,
            'offset'              => $settings['PM_offset'],
        ];
        
        $args['tax_query'] = [];
        $taxonomies = get_object_taxonomies( 'post', 'objects' );

        foreach ( $taxonomies as $object ) {
            $setting_key = 'PM_' . $object->name . '_ids';

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

        if ( !empty( $settings['PM_authors'] ) ) {
            $args['author__in'] = $settings['PM_authors'];
        }

        if ( !empty( $settings['PM_post__not_in'] ) ) {
            $args['post__not_in'] = $settings['PM_post__not_in'];
        }

        return $args;
    }

    public function render_template( $args, $settings )
    {

        $modules_query = new \WP_Query($args);

        $settings = $this->get_settings_for_display();

        ob_start();

        $image_num      =  $args['posts_per_page'];
        $index          = 1;

        if( $modules_query->have_posts() ) {
        while( $modules_query->have_posts() ) {
            $modules_query->the_post();
            if( $modules_query->current_post % $image_num == 0 ){
                echo '<div class="meafe-post-modules items-left">';
                    $image_size ='full';
            } else if( $modules_query->current_post % $image_num == 1  ){
                echo'<div class="meafe-post-modules items-right">';
                    $image_size ='meafe-post-modules-small-img';
            } ?>
            <div class="meafe-post-modules-card" data-id="<?php echo esc_attr( get_the_ID() ); ?>">
                <div class="meafe-post-modules-card-inner">
                    <?php if ( has_post_thumbnail() ) { ?>
                        <figure class="meafe-entry-media image-wrapper">
                            <a class="meafe-grid-post-link" href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_html( get_the_title() ); ?> ">
                                <?php echo wp_get_attachment_image( get_post_thumbnail_id(), $image_size ); ?>
                            </a>
                        </figure>
                        <?php 
                    } ?>
                    <div class="meafe-entry-wrapper category--main">
                        <?php
                        if ( 'post' === get_post_type() && $settings['PM_show_category'] ) {
                            $categories_list = get_the_category_list( ' ' );
                            if ( $categories_list ) {
                                echo '<span class="category--wrapper" itemprop="about">' . wp_kses_post($categories_list) . '</span>';
                            }
                        }

                        if ( $settings['PM_show_title'] ) { ?>
                            <h2 class="meafe-entry-title">
                                <a class="meafe-grid-post-link" href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_html( get_the_title() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
                            </h2>
                        <?php
                        } ?>
                        
                        <div class="meafe-entry-meta details">
                            <?php if ( $settings['PM_show_author'] || $settings['PM_show_author_avatar'] ) { ?>
                                <div class="meafe-posted-by author">
                                    <?php if ( $settings['PM_show_author_avatar'] ) { ?>
                                        <div class="meafe-author-avatar">
                                            <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta('ID') )) ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 96 ); ?> </a>
                                        </div>
                                    <?php
                                    }
                                    if ( $settings['PM_show_author'] ) echo get_the_author_posts_link();
                                echo '</div>';
                            }
                            if ( $settings['PM_show_date'] ) echo '<div class="meafe-posted-on meafe-date"><time datetime="' . esc_attr(get_the_date()) . '">' . esc_html(get_the_date()) . '</time></div>'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if( $index % $image_num == 1  ) echo '</div>';
            if( $index % $image_num == 0  ) echo '</div>';
            $index++;
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
            'PM_show_title'         => $settings['PM_show_title'],
            'PM_show_author'        => $settings['PM_show_author'],
            'PM_show_author_avatar' => $settings['PM_show_author_avatar'],
            'PM_show_date'          => $settings['PM_show_date'],
            'PM_show_category'      => $settings['PM_show_category'],
            'PM_layout_mode'        => $settings['PM_layout_mode'],
            ' PM_item_spacing  '    => $settings['PM_item_spacing'],
            'PM_orderby'            => $settings['PM_orderby'],
        ];

        $this->add_render_attribute(
            'post_modules_wrapper',
            [
                'id' => 'meafe-post-grid-' . esc_attr( $this->get_id() ),
                'class' => [
                    'meafe-post-modules-wrapper layout-' . esc_attr($settings['PM_layout_mode']),
                ],
            ]
        );

        echo '<div ' . $this->get_render_attribute_string( 'post_modules_wrapper' ) . '>
            <div class="meafe-post-modules-innerwrapper">
                ' . self::render_template( $args, $settings_arry ) . '
            </div>
        </div>';

    }

    protected function content_template() {
    }
}
