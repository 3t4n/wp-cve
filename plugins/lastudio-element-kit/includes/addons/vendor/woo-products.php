<?php
/**
 * Class: LaStudioKit_Woo_Products
 * Name: Products
 * Slug: lakit-wooproducts
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use LaStudioKitExtensions\Elementor\Controls\Group_Control_Query;
use LaStudioKitThemeBuilder\Modules\Woocommerce\Classes\Products_Renderer;
use LaStudioKitThemeBuilder\Modules\Woocommerce\Classes\Current_Query_Renderer;

class LaStudioKit_Woo_Products extends LaStudioKit_Base {

    public static $__called_index = 0;
    public static $__called_item = false;

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-woocommerce' );
		    $this->add_script_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_name() {
        return 'lakit-wooproducts';
    }

    public function get_widget_title() {
        return esc_html__( 'Products', 'lastudio-kit' );
    }

    public function get_categories() {
        return [ 'lastudiokit-woocommerce' ];
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'product', 'archive' ];
    }

    protected function register_advance_control_layout(){

    }

    protected function register_query_controls() {
        $this->_start_controls_section(
            'section_query',
            [
                'label' => esc_html__( 'Query', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->_add_group_control(
            Group_Control_Query::get_type(),
            [
                'name' => 'query',
                'post_type' => 'product',
                'presets' => [ 'full' ],
                'fields_options' => [
                    'post_type' => [
                        'default' => 'product',
                        'options' => [
                            'current_query' => esc_html__( 'Current Query', 'lastudio-kit' ),
                            'product' => esc_html__( 'Latest Products', 'lastudio-kit' ),
                            'sale' => esc_html__( 'Sale', 'lastudio-kit' ),
                            'featured' => esc_html__( 'Featured', 'lastudio-kit' ),
                            'related' => esc_html__( 'Related', 'lastudio-kit' ),
                            'upsells' => esc_html__( 'Up-Sells', 'lastudio-kit' ),
                            'by_id' => esc_html_x( 'Manual Selection', 'Posts Query Control', 'lastudio-kit' ),
                        ],
                    ],
                    'orderby' => [
                        'default' => 'date',
                        'options' => [
                            'date' => esc_html__( 'Date', 'lastudio-kit' ),
                            'title' => esc_html__( 'Title', 'lastudio-kit' ),
                            'price' => esc_html__( 'Price', 'lastudio-kit' ),
                            'popularity' => esc_html__( 'Popularity', 'lastudio-kit' ),
                            'rating' => esc_html__( 'Rating', 'lastudio-kit' ),
                            'rand' => esc_html__( 'Random', 'lastudio-kit' ),
                            'menu_order' => esc_html__( 'Menu Order', 'lastudio-kit' ),
                        ],
                    ],
                    'exclude' => [
                        'options' => [
                            'current_post' => esc_html__( 'Current Post', 'lastudio-kit' ),
                            'manual_selection' => esc_html__( 'Manual Selection', 'lastudio-kit' ),
                            'terms' => esc_html__( 'Term', 'lastudio-kit' ),
                        ],
                    ],
                    'include' => [
                        'options' => [
                            'terms' => esc_html__( 'Term', 'lastudio-kit' ),
                        ],
                    ],
                    'exclude_ids' => [
                        'object_type' => 'product',
                    ],
                    'include_ids' => [
                        'object_type' => 'product',
                    ],
                ],
                'exclude' => [
                    'posts_per_page',
                    'exclude_authors',
                    'authors',
                    'offset',
                    'related_fallback',
                    'related_ids',
                    'query_id',
                    'ignore_sticky_posts',
                ],
            ]
        );

        $this->_add_control(
            'nothing_found_message',
            [
                'label' => esc_html__( 'Nothing Found Message', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',
            ]
        );

        $this->_add_control(
            'heading',
            [
                'label' => esc_html__( 'Custom Heading', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'html_tag',
            [
                'label' => esc_html__( 'Heading HTML Tag', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'p',
                    'div' => 'div',
                    'span' => 'span',
                ],
                'default' => 'h2',
                'condition' => [
                    'heading!' => '',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_section_style_pagination( ){
        /**
         * Pagination section
         */
        $this->_start_controls_section(
            'section_pagination_style',
            [
                'label'     => __( 'Pagination', 'lastudio-kit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );

        $this->_add_responsive_control(
            'pagination_align',
            [
                'label'     => __( 'Alignment', 'lastudio-kit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-pagination' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->_add_responsive_control(
            'pagination_spacing',
            [
                'label'     => __( 'Spacing', 'lastudio-kit' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .lakit-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_control(
            'show_pagination_border',
            [
                'label'        => __( 'Border', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => __( 'Hide', 'lastudio-kit' ),
                'label_on'     => __( 'Show', 'lastudio-kit' ),
                'default'      => 'yes',
                'return_value' => 'yes',
                'prefix_class' => 'lakit-pagination-has-border-',
            ]
        );

        $this->_add_control(
            'pagination_border_color',
            [
                'label'     => __( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-border-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_pagination_border' => 'yes',
                ],
            ]
        );

        $this->_add_responsive_control(
            'pagination_item_width',
            [
                'label'     => __( 'Item Width', 'lastudio-kit' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-item-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'pagination_item_spacing',
            [
                'label'     => __( 'Item Spacing', 'lastudio-kit' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-item-spacing: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'pagination_item_radius',
            [
                'label'      => __( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => [
                    '{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-radius: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .lakit-pagination .page-numbers' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'pagination_padding',
            [
                'label'      => __( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => [
                    '{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pagination_typography',
                'selector' => '{{WRAPPER}} .lakit-pagination',
            ]
        );

        $this->_start_controls_tabs( 'pagination_style_tabs' );

        $this->_start_controls_tab( 'pagination_style_normal',
            [
                'label' => __( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'pagination_link_color',
            [
                'label'     => __( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-link-color: {{VALUE}}',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'pagination_link_bkg',
                'selector' => '{{WRAPPER}} .lakit-pagination_ajax_loadmore a',
                'condition' => [
                    'paginate_as_loadmore' => 'yes',
                ],
            ),
            25
        );

        $this->_add_control(
            'pagination_link_bg_color',
            [
                'label'     => __( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-link-bg-color: {{VALUE}}',
                ],
                'condition' => [
                    'paginate_as_loadmore!' => 'yes',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab( 'pagination_style_hover',
            [
                'label' => __( 'Active', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'pagination_link_color_hover',
            [
                'label'     => __( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-link-hover-color: {{VALUE}}',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'pagination_link_bkg_hover',
                'selector' => '{{WRAPPER}} .lakit-pagination_ajax_loadmore a:hover',
                'condition' => [
                    'paginate_as_loadmore' => 'yes',
                ],
            ),
            25
        );

        $this->_add_control(
            'pagination_link_bg_color_hover',
            [
                'label'     => __( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-link-hover-bg-color: {{VALUE}}',
                ],
                'condition' => [
                    'paginate_as_loadmore!' => 'yes',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();
    }

    protected function register_controls_v1(){
        $grid_style = apply_filters(
            'lastudio-kit/products/control/grid_style',
            array(
                '1' => esc_html__( 'Type-1', 'lastudio-kit' ),
                '2' => esc_html__( 'Type-2', 'lastudio-kit' )
            )
        );

        $list_style = apply_filters(
            'lastudio-kit/products/control/list_style',
            array(
                '1' => esc_html__( 'Type-1', 'lastudio-kit' ),
                '2' => esc_html__( 'Type-2', 'lastudio-kit' )
            )
        );

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Layout', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'layout',
            array(
                'label'     => esc_html__( 'Layout', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'grid',
                'render_type' => 'template',
                'options'   => [
                    'grid'      => esc_html__( 'Grid', 'lastudio-kit' ),
                    'list'      => esc_html__( 'List', 'lastudio-kit' ),
                ]
            )
        );

        $this->add_control(
            'grid_style',
            array(
                'label'     => esc_html__( 'Style', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => $grid_style,
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'grid'
                ]
            )
        );

        $this->add_control(
            'list_style',
            array(
                'label'     => esc_html__( 'Style', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => $list_style,
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'list'
                ]
            )
        );

        $this->_add_control(
            'enable_masonry',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Enable Masonry?', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'lastudio-kit' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => esc_html__( 'Rows', 'lastudio-kit' ),
                'type' => Controls_Manager::NUMBER,
                'default' => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
                'render_type' => 'template',
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
            ]
        );

        $this->add_control(
            'item_html_tag',
            [
                'label' => esc_html__( 'Product Title HTML Tag', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'p',
                    'div' => 'div',
                    'span' => 'span',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_control(
            'enable_ajax_load',
            [
                'label' => esc_html__( 'Enable Ajax Load', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'enable_custom_image_size',
            [
                'label' => esc_html__( 'Enable Custom Image Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'image_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Images Size', 'lastudio-kit' ),
                'default'    => 'shop_catalog',
                'options'    => lastudio_kit_helper()->get_image_sizes(),
                'condition' => [
                    'enable_custom_image_size' => 'yes'
                ]
            )
        );

        $this->add_control(
            'enable_alt_image',
            [
                'label' => esc_html__( 'Enable Crossfade Image Effect', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => ''
            ]
        );

        $this->register_advance_control_layout();

        $this->add_control(
            'allow_order',
            [
                'label' => esc_html__( 'Allow Order', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => ''
            ]
        );

        $this->add_control(
            'show_result_count',
            [
                'label' => esc_html__( 'Show Result Count', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => ''
            ]
        );

        $this->add_control(
            'paginate',
            [
                'label' => esc_html__( 'Pagination', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => ''
            ]
        );

        $this->add_control(
            'paginate_as_loadmore',
            [
                'label' => esc_html__( 'Use Load More', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'paginate_infinite',
            [
                'label' => esc_html__( 'Infinite loading', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'paginate' => 'yes',
                    'paginate_as_loadmore' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loadmore_text',
            [
                'label' => esc_html__( 'Load More Text', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Load More',
                'condition' => [
                    'paginate' => 'yes',
                    'paginate_as_loadmore' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

        $this->register_query_controls();

        $this->register_masonry_setting_section( [ 'enable_masonry' => 'yes' ] );

        $this->register_carousel_section( [ 'enable_masonry!' => 'yes' ], 'columns');

        $this->start_controls_section(
            'section_products_style',
            [
                'label' => esc_html__( 'Products', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => esc_html__( 'Columns Gap', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products:not(.swiper-wrapper)' => 'margin-right: -{{SIZE}}{{UNIT}}; margin-left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ul.products li.product' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}' => '--lakit-carousel-item-right-space: {{SIZE}}{{UNIT}}; --lakit-carousel-item-left-space: {{SIZE}}{{UNIT}};--lakit-gcol-left-space: {{SIZE}}{{UNIT}}; --lakit-gcol-right-space: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => esc_html__( 'Rows Gap', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_image_style',
            [
                'label' => esc_html__( 'Image', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_bg',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'image2_bg',
            [
                'label' => esc_html__( 'Crossfade Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit.p_img-second' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}}' => '--lakit-p_img_color: {{VALUE}}',
                ],
                'condition' => [
                    'enable_alt_image' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'custom_image_width',
            array(
                'label' => esc_html__( 'Image Width', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => 100,
                    'unit' => '%'
                ],
                'selectors' => \apply_filters('lastudio-kit/products/thumbnail_width_selector', array(
                    '{{WRAPPER}} .woocommerce-loop-product__link' => 'width: {{SIZE}}{{UNIT}};'
                ))
            )
        );

        $this->add_control(
            'enable_custom_image_height',
            array(
                'label'        => esc_html__( 'Enable Custom Image Height', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => '',
                'prefix_class' => 'active-object-fit active-object-fit-',
            )
        );

        $this->add_responsive_control(
            'custom_image_height',
            array(
                'label' => esc_html__( 'Image Height', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 50,
                        'max' => 2000,
                    )
                ),
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => 100,
                    'unit' => '%'
                ],
                'selectors' => apply_filters('lastudio-kit/products/thumbnail_height_selector', array(
                    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit' => 'padding-bottom: {{SIZE}}{{UNIT}};'
                )),
                'condition' => [
                    'enable_custom_image_height!' => ''
                ]
            )
        );

        $this->add_control(
            'image_pos',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Images Position', 'lastudio-kit' ),
                'default'    => 'center',
                'options'    => [
                    'center'    => esc_html__( 'Center', 'lastudio-kit' ),
                    'top'       => esc_html__( 'Top', 'lastudio-kit' ),
                    'bottom'    => esc_html__( 'Bottom', 'lastudio-kit' ),
                ],
                'condition' => [
                    'enable_custom_image_height!' => ''
                ],
                'selectors' => apply_filters('lastudio-kit/products/thumbnail_height_selector', array(
                    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit > *' => 'object-position: {{VALUE}}; background-position: {{VALUE}}'
                )),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} ul.products li.product .product_item--thumbnail',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'heading_title_style',
            [
                'label' => esc_html__( 'Title', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .product_item--title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'heading_rating_style',
            [
                'label' => esc_html__( 'Rating', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => esc_html__( 'Star Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .star-rating span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'empty_star_color',
            [
                'label' => esc_html__( 'Empty Star Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .star-rating' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'star_size',
            [
                'label' => esc_html__( 'Star Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'em',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 4,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_spacing',
            [
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}  ul.products li.product .star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'heading_price_style',
            [
                'label' => esc_html__( 'Price', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .price' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ul.products li.product .price ins' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ul.products li.product .price ins .amount' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .price',
            ]
        );

        $this->add_control(
            'heading_old_price_style',
            [
                'label' => esc_html__( 'Regular Price', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'old_price_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .price del' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ul.products li.product .price del .amount' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'old_price_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .price del  ',
            ]
        );

        $this->add_responsive_control(
            'price_spacing',
            [
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}  ul.products li.product .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'heading_button_style',
            [
                'label' => esc_html__( 'Button', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_color',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_fz',
            [
                'label' => esc_html__( 'Font Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}. ul.products li.product .button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'button_border',
                'exclude' => [ 'color' ],
                'selector' => '{{WRAPPER}} ul.products li.product .button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_excerpt_style',
            [
                'label' => esc_html__( 'Excerpt', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .item--excerpt' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .item--excerpt',
            ]
        );


        $this->add_responsive_control(
            'excerpt_spacing',
            [
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}  ul.products li.product .item--excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'heading_cat_style',
            [
                'label' => esc_html__( 'Category', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cat_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--category-link' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cat_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .product_item--category-link',
            ]
        );

        $this->add_responsive_control(
            'cat_spacing',
            [
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}  ul.products li.product .product_item--category-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_design_box',
            [
                'label' => esc_html__( 'Box', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_border_width',
            [
                'label' => esc_html__( 'Border Width', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    apply_filters('lastudio-kit/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    apply_filters('lastudio-kit/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    apply_filters('lastudio-kit/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_responsive_control(
            'box_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    apply_filters('lastudio-kit/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->start_controls_tabs( 'box_style_tabs' );

        $this->start_controls_tab( 'classic_style_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => apply_filters('lastudio-kit/products/box_selector', '{{WRAPPER}} ul.products li.product'),
            ]
        );

        $this->add_control(
            'box_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    apply_filters('lastudio-kit/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_color',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    apply_filters('lastudio-kit/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'classic_style_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_hover',
                'selector' => apply_filters('lastudio-kit/products/box_selector', '{{WRAPPER}} ul.products li.product') . ':hover',
            ]
        );

        $this->add_control(
            'box_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    apply_filters('lastudio-kit/products/box_selector', '{{WRAPPER}} ul.products li.product') . ':hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    apply_filters('lastudio-kit/products/box_selector', '{{WRAPPER}} ul.products li.product') . ':hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_design_content',
            [
                'label' => esc_html__( 'Content Box', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit'),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit'),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit'),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ul.products .product_item--info' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'content_border_width',
            [
                'label' => esc_html__( 'Border Width', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products .product_item--info' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'content_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products .product_item--info' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products .product_item--info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-p_item--c-mt:{{TOP}}{{UNIT}}; --lakit-p_item--c-mr:{{RIGHT}}{{UNIT}}; --lakit-p_item--c-mb:{{BOTTOM}}{{UNIT}}; --lakit-p_item--c-ml:{{LEFT}}{{UNIT}} ',
                    '{{WRAPPER}} ul.products .product_item--info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->start_controls_tabs( 'content_style_tabs' );

        $this->start_controls_tab( 'content_style_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_shadow',
                'selector' => '{{WRAPPER}} ul.products .product_item--info',
            ]
        );

        $this->add_control(
            'content_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products .product_item--info' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'content_border_color',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products .product_item--info' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'content_style_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_shadow_hover',
                'selector' => '{{WRAPPER}} ul.products li.product:hover .product_item--info',
            ]
        );

        $this->add_control(
            'content_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product:hover .product_item--info' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'content_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product:hover .product_item--info' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->register_section_style_pagination();

        $this->start_controls_section(
            'section_heading_style',
            [
                'label' => esc_html__( 'Heading', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lakit-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .lakit-heading',
            ]
        );

        $this->add_responsive_control(
            'heading_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'heading_border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-heading',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'heading_text_shadow',
                'selector' => '{{WRAPPER}} .lakit-heading',
            ]
        );

        $this->end_controls_section();

        $this->register_carousel_arrows_dots_style_section( [ 'enable_masonry!' => 'yes', 'enable_carousel' => 'yes' ] );
    }

    protected function v2_layout_section(){
        $grid_style = apply_filters(
            'lastudio-kit/products/control/grid_style',
            array(
                'default' => esc_html__( 'Default', 'lastudio-kit' ),
            )
        );
        $list_style = apply_filters(
            'lastudio-kit/products/control/list_style',
            array(
                'default' => esc_html__( 'Default', 'lastudio-kit' ),
                'reverse' => esc_html__( 'Reverse', 'lastudio-kit' ),
            )
        );
        $this->_start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout', 'lastudio-kit' ),
            ]
        );
        $this->_add_control(
            'layout',
            array(
                'label'     => esc_html__( 'Type', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'grid',
                'render_type' => 'template',
                'options'   => [
                    'grid'      => esc_html__( 'Grid', 'lastudio-kit' ),
                    'list'      => esc_html__( 'List', 'lastudio-kit' ),
                ]
            )
        );
        $this->_add_control(
            'grid_style',
            array(
                'label'     => esc_html__( 'Preset', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'default',
                'options'   => $grid_style,
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'grid'
                ]
            )
        );
        $this->_add_control(
            'list_style',
            array(
                'label'     => esc_html__( 'Preset', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'default',
                'options'   => $list_style,
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'list'
                ]
            )
        );
        $this->_add_control(
            'enable_masonry',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Enable Masonry?', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );
        $this->_add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'lastudio-kit' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
                'render_type' => 'template'
            ]
        );
        $this->_add_control(
            'rows',
            [
                'label' => esc_html__( 'Rows', 'lastudio-kit' ),
                'type' => Controls_Manager::NUMBER,
                'default' => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
                'render_type' => 'template',
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
            ]
        );

        $this->_add_control(
            'enable_ajax_load',
            [
                'label' => esc_html__( 'Enable Ajax Load', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'query_post_type!' => 'current_query',
                ],
            ]
        );

        $this->_add_control(
            'is_filter_container',
            [
                'label' => esc_html__( 'Is Filter Container ?', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => ''
            ]
        );

        $this->_add_control(
            'paginate',
            [
                'label' => esc_html__( 'Pagination', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => ''
            ]
        );

        $this->_add_control(
            'paginate_as_loadmore',
            [
                'label' => esc_html__( 'Use Load More', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );

        $this->_add_control(
            'paginate_infinite',
            [
                'label' => esc_html__( 'Infinite loading', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'paginate' => 'yes',
                    'paginate_as_loadmore' => 'yes',
                ],
            ]
        );

        $this->_add_control(
            'loadmore_text',
            [
                'label' => esc_html__( 'Load More Text', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Load More',
                'condition' => [
                    'paginate' => 'yes',
                    'paginate_as_loadmore' => 'yes',
                ]
            ]
        );

        $this->_add_control(
            'item_html_tag',
            [
                'label' => esc_html__( 'Product Title HTML Tag', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'p',
                    'div' => 'div',
                    'span' => 'span',
                ],
                'default' => 'h3',
            ]
        );

        $this->_add_control(
            'enable_custom_image_size',
            [
                'label' => esc_html__( 'Custom Product Image Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->_add_control(
            'image_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Images Size', 'lastudio-kit' ),
                'default'    => 'shop_catalog',
                'options'    => lastudio_kit_helper()->get_image_sizes(),
                'condition' => [
                    'enable_custom_image_size' => 'yes'
                ]
            )
        );

        $this->_add_control(
            'enable_alt_image',
            [
                'label' => esc_html__( 'Enable Crossfade Image Effect', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => ''
            ]
        );

        $this->_add_control(
            'alt_image_as_slide',
            [
                'label' => esc_html__( 'Show Product Gallery', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'enable_alt_image' => 'yes'
                ]
            ]
        );

        $this->_end_controls_section();
    }

    protected function v2_content_setting_section(){
        $this->_start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content Settings', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            '_content_setting_heading_1',
            [
                'label' => esc_html__( 'Product Image Zone', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $product_image_zone_pos = apply_filters( 'lastudio-kit/products/actions/position', [
            'center-center' => esc_html__( 'Center Center', 'elementor' ),
            'center-left' => esc_html__( 'Center Left', 'lastudio-kit' ),
            'center-right' => esc_html__( 'Center Right', 'lastudio-kit' ),
            'top-center' => esc_html__( 'Top Center', 'lastudio-kit' ),
            'top-left' => esc_html__( 'Top Left', 'lastudio-kit' ),
            'top-right' => esc_html__( 'Top Right', 'lastudio-kit' ),
            'bottom-center' => esc_html__( 'Bottom Center', 'lastudio-kit' ),
            'bottom-left' => esc_html__( 'Bottom Left', 'lastudio-kit' ),
            'bottom-right' => esc_html__( 'Bottom Right', 'lastudio-kit' ),
        ] );

        $product_image_zone_action = apply_filters( 'lastudio-kit/products/actions/list', [
            'addcart'   => esc_html__( 'Add Cart', 'lastudio-kit' ),
            'quickview' => esc_html__( 'Quickview', 'lastudio-kit' ),
            'wishlist' => esc_html__( 'Add Wishlist', 'lastudio-kit' ),
            'compare' => esc_html__( 'Add Compare', 'lastudio-kit' ),
            'button-toggle' => esc_html__( 'Button Toggle', 'lastudio-kit' ),
        ] );

        $product_content_zone_item = apply_filters( 'lastudio-kit/products/content_zone/list', [
            'row' => esc_html__( 'New Row', 'lastudio-kit' ),
            'product_title' => esc_html__( 'Product Title', 'lastudio-kit' ),
            'product_price' => esc_html__( 'Product Price', 'lastudio-kit' ),
            'product_rating' => esc_html__( 'Product Rating', 'lastudio-kit' ),
            'product_stock' => esc_html__( 'Product Stock', 'lastudio-kit' ),
            'product_short_description' => esc_html__( 'Product Short Description', 'lastudio-kit' ),
            'product_tag' => esc_html__( 'Product Tags', 'lastudio-kit' ),
            'product_category' => esc_html__( 'Product Category', 'lastudio-kit' ),
            'product_attribute' => esc_html__( 'Product Attribute', 'lastudio-kit' ),
            'product_countdown' => esc_html__( 'Product Countdown', 'lastudio-kit' ),
            'custom_field' => esc_html__( 'Product Custom Field', 'lastudio-kit' ),
            'product_action' => esc_html__( 'Product Actions', 'lastudio-kit' ),
            'product_author' => esc_html__( 'Product Author', 'lastudio-kit' ),
            'shipping_class' => esc_html__( 'Shipping Class', 'lastudio-kit' ),
        ] );

        $product_image_zone_action_json = json_encode($product_image_zone_action);
        $product_content_json = json_encode($product_content_zone_item);

        $product_image_zone_repeater = new Repeater();
        $product_image_zone_repeater->add_control(
            'item_type',
            [
                'label' => esc_html__( 'Action', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT2,
                'options' => $product_image_zone_action
            ]
        );
        $product_image_zone_repeater->add_control(
            'only_icon',
            [
                'label'        => esc_html__( 'Only Icon', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );
        $product_image_zone_repeater->add_control(
            'item_label',
            array(
                'label' => esc_html__( 'Normal Text', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
                'condition' => [
                    'item_type' => ['quickview', 'wishlist', 'compare', 'button-toggle'],
                ],
            )
        );
        $product_image_zone_repeater->add_control(
            'item_label2',
            array(
                'label' => esc_html__( 'Added Text', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
                'condition' => [
                    'item_type' => ['wishlist', 'compare'],
                ],
            )
        );
        $product_image_zone_repeater->add_control(
            'item_icon',
            [
                'label'            => esc_html__( 'Icon', 'lastudio-kit' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin'             => 'inline',
                'label_block'      => false,
                'default' => array(
                    'value'     => 'lastudioicon-shopping-cart-3',
                    'library'   => 'lastudioicon'
                )
            ]
        );
        $product_image_zone_repeater->add_control(
            'item_full',
            [
                'label'        => esc_html__( 'Full size', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--lakit-zone-btn-grow: 1;'
                ]
            ]
        );

        $product_content_zone_repeater = new Repeater();
        $product_content_zone_repeater->add_control(
            'item_type',
            [
                'label' => esc_html__( 'Item Type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT2,
                'options' => $product_content_zone_item
            ]
        );
        $product_content_zone_repeater->add_responsive_control(
            'item_width',
            [
                'label' => esc_html__( 'Item width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--lakit-zone-item-width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $product_content_zone_repeater->add_responsive_control(
            'item_direction',
            [
                'label' => esc_html_x( 'Row direction', 'Flex Container Control', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html_x( 'Row', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-arrow-right',
                    ],
                    'column' => [
                        'title' => esc_html_x( 'Column', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-arrow-down',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--lakit-zone-item-direction: {{VALUE}};',
                ],
                'condition' => [
                    'item_type' => ['row', 'product_action'],
                ],
            ]
        );
        $product_content_zone_repeater->add_responsive_control(
            'item_justify_content',
            [
                'label' => esc_html_x( 'Justify Content', 'Flex Container Control', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html_x( 'Flex Start', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html_x( 'Center', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html_x( 'Flex End', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html_x( 'Space Between', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html_x( 'Space Around', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html_x( 'Space Evenly', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--lakit-zone-item-justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'item_type' => ['row', 'product_action'],
                ],
            ]
        );
        $product_content_zone_repeater->add_responsive_control(
            'item_gap',
            [
                'label' => esc_html__( 'Items gap', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--lakit-zone-item-gap: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'item_type' => ['row', 'product_action'],
                ],
            ]
        );
        $product_content_zone_repeater->add_responsive_control(
            'item_wrap',
            [
                'label' => esc_html__( 'Wrap', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'nowrap' => [
                        'title' => esc_html_x( 'No Wrap', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-nowrap',
                    ],
                    'wrap' => [
                        'title' => esc_html_x( 'Wrap', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-wrap',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--lakit-zone-item-wrap: {{VALUE}};'
                ],
                'condition' => [
                    'item_type' => ['row', 'product_action'],
                ],
            ]
        );

        $product_content_zone_repeater->add_control(
            'item_fname',
            array(
                'label' => esc_html__( 'Field Name', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
                'condition' => [
                    'item_type' => 'custom_field',
                ],
            )
        );
        $product_content_zone_repeater->add_control(
            'item_icon',
            [
                'label'            => esc_html__( 'Icon', 'lastudio-kit' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin'             => 'inline',
                'label_block'      => false,
                'condition' => [
                    'item_type' => ['product_stock', 'product_tag', 'product_category', 'product_author', 'custom_field', 'shipping_class'],
                ],
            ]
        );

        $product_content_zone_repeater->add_control(
            'is_stock_progress',
            [
                'label'        => esc_html__( 'Use progress bar', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'default'      => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'item_type' => 'product_stock',
                ],
            ]
        );
        $product_content_zone_repeater->add_control(
            'stock_progress_label',
            array(
                'label' => esc_html__( 'Label', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
                'placeholder' => '[sold] sold/ [total] total',
                'condition' => [
                    'item_type' => 'product_stock',
                ],
            )
        );


        $this->_add_control(
            'product_image_zone_1',
            array(
                'label'         => esc_html__( 'Action 1', 'lastudio-kit' ),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $product_image_zone_repeater->get_controls(),
                'title_field'   => "<# let zone_json=$product_image_zone_action_json; zone_json_label=zone_json[item_type] #>{{{ zone_json_label }}}",
                'prevent_empty' => false,
                'default'       => [
                    [
                        'item_type' => 'addcart'
                    ],
                ]
            )
        );
        $this->_add_control(
            'product_image_zone_1_pos',
            array(
                'label'     => esc_html__( 'Action 1 Position', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $product_image_zone_pos,
                'default'   => 'center-center',
                'prefix_class' => 'actionzone-a-pos-',
            )
        );
        $this->_add_control(
            'product_image_zone_2',
            array(
                'label'         => esc_html__( 'Action 2 Content', 'lastudio-kit' ),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $product_image_zone_repeater->get_controls(),
                'title_field'   => "<# let zone_json=$product_image_zone_action_json; zone_json_label=zone_json[item_type] #>{{{ zone_json_label }}}",
                'prevent_empty' => false,
                'default'       => [
                    [
                        'item_type' => 'wishlist'
                    ],
                ]
            )
        );
        $this->_add_control(
            'product_image_zone_2_pos',
            array(
                'label'     => esc_html__( 'Action 2 Position', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $product_image_zone_pos,
                'default'   => 'top-right',
                'prefix_class' => 'actionzone-b-pos-',
            )
        );

        $this->_add_control(
            '_content_setting_heading_2',
            [
                'label' => esc_html__( 'Buttons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_add_control(
            'product_content_buttons',
            array(
                'label'         => esc_html__( 'Product Actions', 'lastudio-kit' ),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $product_image_zone_repeater->get_controls(),
                'title_field'   => "<# let zone_json=$product_image_zone_action_json; zone_json_label=zone_json[item_type] #>{{{ zone_json_label }}}",
                'prevent_empty' => false,
                'default'       => [
                    [
                        'item_type' => 'addcart'
                    ],
                ]
            )
        );

        $this->_add_control(
            'product_image_zone_3',
            array(
                'label'         => esc_html__( 'Action 3 Content', 'lastudio-kit' ),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $product_content_zone_repeater->get_controls(),
                'prevent_empty' => false,
                'title_field'   => "<# let zone_json=$product_content_json; zone_json_label=zone_json[item_type] #>{{{ zone_json_label }}}",
            )
        );
        $this->_add_control(
            'product_image_zone_3_pos',
            array(
                'label'     => esc_html__( 'Action 3 Position', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $product_image_zone_pos,
                'default'   => 'bottom-center',
                'prefix_class' => 'actionzone-c-pos-',
            )
        );

        $this->_add_control(
            '_content_setting_heading_3',
            [
                'label' => esc_html__( 'Product Content Zone', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_add_control(
            'product_content_zone',
            array(
                'label'         => esc_html__( 'Zone Content', 'lastudio-kit' ),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $product_content_zone_repeater->get_controls(),
                'prevent_empty' => false,
                'title_field'   => "<# let zone_json=$product_content_json; zone_json_label=zone_json[item_type] #>{{{ zone_json_label }}}",
                'default'       => [
                    [
                        'item_type' => 'product_title'
                    ],
                    [
                        'item_type' => 'product_price'
                    ]
                ]
            )
        );

        $this->_end_controls_section();
    }

    protected function v2_style_item_section(){
        $this->_start_controls_section(
            'section_style__item',
            [
                'label' => esc_html__( 'Item', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_responsive_control(
            'column_padding',
            array(
                'label'       => esc_html__( 'Column Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}}' => '--lakit-item-padding-top: {{TOP}}{{UNIT}};--lakit-item-padding-right: {{RIGHT}}{{UNIT}};--lakit-item-padding-bottom: {{BOTTOM}}{{UNIT}};--lakit-item-padding-left: {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_start_controls_tabs( 'tab_items' );
        $this->_start_controls_tab( 'tab_item_normal', [
            'label' => esc_html__( 'Normal', 'lastudio-kit' ),
        ] );
        $this->_add_control(
            'item_bg_normal',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .product_item--inner' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'item_padding_normal',
            array(
                'label'       => esc_html__( 'Item Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .product_item--inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'item_margin_normal',
            array(
                'label'       => esc_html__( 'Item Margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .product_item--inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_normal',
                'selector' => '{{WRAPPER}} .product_item--inner',
            ]
        );
        $this->_add_responsive_control(
            'item_radius_normal',
            array(
                'label'       => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .product_item--inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_normal',
                'selector' => '{{WRAPPER}} .product_item--inner',
            ]
        );
        $this->_end_controls_tab();

        $this->_start_controls_tab( 'tab_item_hover', [
            'label' => esc_html__( 'Hover', 'lastudio-kit' ),
        ] );
        $this->_add_control(
            'item_bg_hover',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} li.product:hover .product_item--inner' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'item_padding_hover',
            array(
                'label'       => esc_html__( 'Item Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} li.product:hover .product_item--inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'item_margin_hover',
            array(
                'label'       => esc_html__( 'Item Margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} li.product:hover .product_item--inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_hover',
                'selector' => '{{WRAPPER}} li.product:hover .product_item--inner',
            ]
        );
        $this->_add_responsive_control(
            'item_radius_hover',
            array(
                'label'       => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} li.product:hover .product_item--inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_hover',
                'selector' => '{{WRAPPER}} li.product:hover .product_item--inner',
            ]
        );
        $this->_end_controls_tab();
        $this->_end_controls_tabs();

        $this->_end_controls_section();
    }

    protected function v2_style_heading_section(){
        $this->_start_controls_section(
            'section_style__heading',
            [
                'label' => esc_html__( 'Heading', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'heading!' => '',
                ],
            ]
        );

        $this->_add_responsive_control(
            'heading_align',
            [
                'label' => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lakit-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );


        $this->_add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .lakit-heading',
            ]
        );

        $this->_add_responsive_control(
            'heading_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'heading_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'heading_border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-heading',
            ]
        );

        $this->_add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'heading_text_shadow',
                'selector' => '{{WRAPPER}} .lakit-heading',
            ]
        );

        $this->_end_controls_section();
    }

    protected function v2_style_image_section(){
        $this->_start_controls_section(
            'section_style__image',
            [
                'label' => esc_html__( 'Product Image', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

	    $this->_add_control(
		    'section_style___heading1',
		    [
			    'label'       => esc_html__( 'Wrapper', 'lastudio-kit' ),
			    'type'        => Controls_Manager::HEADING,
			    'label_block' => true,
		    ]
	    );
	    $this->_add_control(
		    'image_wrap_bg',
		    [
			    'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .product_item--thumbnail' => 'background-color: {{VALUE}}',
			    ]
		    ]
	    );

	    $this->_add_responsive_control(
		    'image_padding',
		    [
			    'label' => esc_html__( 'Padding', 'lastudio-kit' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em', '%', 'custom' ],
			    'selectors' => [
				    '{{WRAPPER}} .product_item--thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->_add_responsive_control(
		    'image_spacing',
		    [
			    'label' => esc_html__( 'Margin', 'lastudio-kit' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em', '%', 'custom' ],
			    'selectors' => [
				    '{{WRAPPER}} .product_item--thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
			    ]
		    ]
	    );
	    $this->_add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'image_border',
			    'selector' => '{{WRAPPER}} .product_item--thumbnail',
		    ]
	    );

	    $this->_add_responsive_control(
		    'image_border_radius',
		    [
			    'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', '%' ],
			    'selectors' => [
				    '{{WRAPPER}} .product_item--thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
			    ],
		    ]
	    );
	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'image_shadow',
			    'selector' => '{{WRAPPER}} .product_item--thumbnail',
		    ]
	    );

		$this->_add_control(
		    'section_style___heading2',
		    [
			    'label'       => esc_html__( 'Image', 'lastudio-kit' ),
			    'type'        => Controls_Manager::HEADING,
			    'label_block' => true,
			    'separator'   => 'before',
		    ]
	    );
	    $this->_add_control(
		    'image_bg',
		    [
			    'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit' => 'background-color: {{VALUE}}',
			    ]
		    ]
	    );

        $this->_add_control(
            'image2_bg',
            [
                'label' => esc_html__( 'Crossfade Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit.p_img-second' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}}' => '--lakit-p_img_color: {{VALUE}}',
                ],
                'condition' => [
                    'enable_alt_image' => 'yes'
                ]
            ]
        );

        $this->_add_responsive_control(
            'custom_image_width',
            array(
                'label' => esc_html__( 'Image Width', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => 100,
                    'unit' => '%'
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__link' => 'width: {{SIZE}}{{UNIT}};'
                ]
            )
        );

        $this->_add_control(
            'enable_custom_image_height',
            array(
                'label'        => esc_html__( 'Enable Custom Image Height', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => '',
                'prefix_class' => 'active-object-fit active-object-fit-',
            )
        );

        $this->_add_responsive_control(
            'custom_image_height',
            array(
                'label' => esc_html__( 'Image Height', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 50,
                        'max' => 2000,
                    )
                ),
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => 100,
                    'unit' => '%'
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit' => 'padding-bottom: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'enable_custom_image_height!' => ''
                ]
            )
        );

        $this->_add_control(
            'image_pos',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Image Position', 'lastudio-kit' ),
                'default'    => 'center',
                'options'    => [
                    'center'    => esc_html__( 'Center', 'lastudio-kit' ),
                    'top'       => esc_html__( 'Top', 'lastudio-kit' ),
                    'bottom'    => esc_html__( 'Bottom', 'lastudio-kit' ),
                ],
                'condition' => [
                    'enable_custom_image_height!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit > *' => 'object-position: {{VALUE}}; background-position: {{VALUE}}'
                ]
            )
        );

        $this->_add_control(
            '_image_overlay',
            [
                'label' => esc_html__( 'Overlay', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( 'tabs_image_overlay' );

        $this->start_controls_tab(
            'tabs_image_overlay_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit'),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'overlay_background',
                'selector' => '{{WRAPPER}} .product_item--thumbnail .item--overlay',
            )
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label' => esc_html__( 'Opacity', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--thumbnail .item--overlay' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'overlay_pos',
            [
                'label' => esc_html__( 'Position', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--thumbnail .item--overlay' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_image_overlay_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit'),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'overlay_background_hover',
                'selector' => '{{WRAPPER}} .product_item--inner:hover .product_item--thumbnail .item--overlay',
            )
        );

        $this->add_control(
            'overlay_opacity_hover',
            [
                'label' => esc_html__( 'Opacity', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--inner:hover .product_item--thumbnail .item--overlay' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'overlay_pos_hover',
            [
                'label' => esc_html__( 'Position', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--inner:hover .product_item--thumbnail .item--overlay' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->_end_controls_section();
    }

    protected function v2_style_image_zone_1(){
        $this->_start_controls_section(
            'section_style__image_zone_1',
            [
                'label' => esc_html__( 'Product Image Zone Action 1', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_control(
            'zone_1_visible_on_hover',
            [
                'label'        => esc_html__( 'Show on hover', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'return_value' => 'yes',
                'prefix_class' => 'actionzone-a-hover-',
            ]
        );

        $this->_add_control(
            'zone_1_hide_on',
            [
                'label'        => esc_html__( 'Hide on', 'lastudio-kit' ),
                'type'         => Controls_Manager::SELECT2,
                'options'      => [
                    'desktop' => esc_html__( 'Desktop', 'lastudio-kit' ),
                    'laptop' => esc_html__( 'Laptop', 'lastudio-kit' ),
                    'tablet' => esc_html__( 'Tablet', 'lastudio-kit' ),
                    'mobile_extra' => esc_html__( 'Mobile Extra', 'lastudio-kit' ),
                    'mobile' => esc_html__( 'Mobile', 'lastudio-kit' ),
                ],
                'multiple' => true,
            ]
        );

        $this->_add_control(
            'zone_1_show_toggle',
            [
                'label'        => esc_html__( 'Show first item', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'return_value' => 'yes',
                'prefix_class' => 'actionzone-a-toggle-',
                'condition'    => [
                    'zone_1_visible_on_hover!' => 'yes'
                ]
            ]
        );
        $this->_add_control(
            'zone_1_bgcolor',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-a' => '--lakit-zone-bg: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_1_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-a' => '--lakit-zone-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_1_direction',
            [
                'label' => esc_html_x( 'Zone direction', 'Flex Container Control', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html_x( 'Row', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-arrow-right',
                    ],
                    'column' => [
                        'title' => esc_html_x( 'Column', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-arrow-down',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-a' => '--lakit-zone-direction: {{VALUE}};',
                ],
                'prefix_class' => 'actionzone-a%s-direction-',
            ]
        );
        $this->_add_responsive_control(
            'zone_1_width',
            [
                'label' => esc_html__( 'Zone width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-a' => '--lakit-zone-width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_1_gap',
            [
                'label' => esc_html__( 'Zone Items gap', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
                    'size' => '5',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-a' => '--lakit-zone-gap: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_1_padding',
            array(
                'label'       => esc_html__( 'Zone padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-a' => '--lakit-zone-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_1_margin',
            array(
                'label'       => esc_html__( 'Zone margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-a' => '--lakit-zone-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_1_radius',
            array(
                'label'       => esc_html__( 'Zone border radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-a' => '--lakit-zone-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'zone_1_shadow',
                'selector' => '{{WRAPPER}} .lakitp-zone-a',
            ]
        );

        $this->_add_control(
            '_zone_1_heading_button',
            [
                'label' => esc_html__( 'Buttons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_start_controls_tabs( 'zone_1_action_tabs' );
        $this->_start_controls_tab( 'zone_1_action_tab_normal', [
            'label' => esc_html__( 'Normal', 'lastudio-kit' ),
        ] );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'zone_1_btn_font',
                'selector' => '{{WRAPPER}} .lakitp-zone-a .lakit-btn',
            ]
        );
        $this->_add_control(
            'zone_1_btn_color',
            array(
                'label'     => esc_html__( 'Text color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_1_btn_bgcolor',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_1_btn_padding',
            array(
                'label'       => esc_html__( 'Button padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'zone_1_btn_border',
                'label' => esc_html__( 'Button border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakitp-zone-a .lakit-btn',
            ]
        );

        $this->_add_responsive_control(
            'zone_1_btn_radius',
            array(
                'label'       => esc_html__( 'Button radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'zone_1_btn_shadow',
                'selector' => '{{WRAPPER}} .lakitp-zone-a .lakit-btn',
            ]
        );

        $this->_add_control(
            '_zone_1_heading_button_1',
            [
                'label' => esc_html__( 'Icons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->_add_responsive_control(
            'zone_1_btn_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-a' => '--lakit-zone-icon-font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn .lakit-btn--icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_1_btn_icon_padding',
            array(
                'label'       => esc_html__( 'Button icon padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn .lakit-btn--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_1_btn_icon_margin',
            array(
                'label'       => esc_html__( 'Button icon margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn .lakit-btn--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_tab();
        $this->_start_controls_tab( 'zone_1_action_tab_hover', [
            'label' => esc_html__( 'Hover', 'lastudio-kit' ),
        ] );
        $this->_add_control(
            'zone_1_btn_color_hover',
            array(
                'label'     => esc_html__( 'Text color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn.added' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_1_btn_bgcolor_hover',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn.added' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_1_btn_padding_hover',
            array(
                'label'       => esc_html__( 'Button padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn.added' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'zone_1_btn_border_hover',
                'label' => esc_html__( 'Button border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakitp-zone-a .lakit-btn:hover, {{WRAPPER}} .lakitp-zone-a .lakit-btn.added',
            ]
        );

        $this->_add_responsive_control(
            'zone_1_btn_radius_hover',
            array(
                'label'       => esc_html__( 'Button radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn.added' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'zone_1_btn_shadow_hover',
                'selector' => '{{WRAPPER}} .lakitp-zone-a .lakit-btn:hover, {{WRAPPER}} .lakitp-zone-a .lakit-btn.added',
            ]
        );

        $this->_add_control(
            '_zone_1_heading_button_2',
            [
                'label' => esc_html__( 'Icons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->_add_responsive_control(
            'zone_1_btn_icon_size_hover',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn:hover .lakit-btn--icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn.added .lakit-btn--icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_1_btn_icon_padding_hover',
            array(
                'label'       => esc_html__( 'Button icon padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn:hover .lakit-btn--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn.added .lakit-btn--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_1_btn_icon_margin_hover',
            array(
                'label'       => esc_html__( 'Button icon margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn:hover .lakit-btn--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-a .lakit-btn.added .lakit-btn--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_end_controls_tab();
        $this->_end_controls_tabs();

        $this->_add_control(
            '_zone_1_heading_tooltip',
            [
                'label' => esc_html__( 'Tooltip', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->_add_control(
            'zone_1_tooltip_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.lakit-tooltip-zone-a--id-{{ID}}' => '--hint-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_1_tooltip_bg',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.lakit-tooltip-zone-a--id-{{ID}}' => '--hint-bgcolor: {{VALUE}}',
                ),
            )
        );
        /*
        $this->_add_responsive_control(
            'zone_1_tooltip_position',
            [
                'label' => esc_html_x( 'Position', 'Tooltip Control', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html_x( 'Top', 'Tooltip Control', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => esc_html_x( 'Right', 'Tooltip Control', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'bottom' => [
                        'title' => esc_html_x( 'Bottom', 'Tooltip Control', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'left' => [
                        'title' => esc_html_x( 'Left', 'Tooltip Control', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                ],
                'default' => 'top',
                'selectors_dictionary' => [
                    'top'       => '--hint-left:50%;--hint-top:initial;--hint-right:initial;--hint-bottom:100%;--hint-transform:translateX(-50%);--hint-before-margin-left:initial;--hint-before-margin-top:initial;--hint-before-margin-right:initial;--hint-before-margin-bottom:-13px;--hint-after-margin-left:initial;--hint-after-margin-top:14px;--hint-after-margin-right:initial;--hint-after-margin-bottom:initial;--hint-hover-transform:translateX(-50%) translateY(-8px);--hint-border-color:var(--hint-bgcolor) transparent transparent transparent',
                    'right'     => '--hint-left:100%;--hint-top:initial;--hint-right:initial;--hint-bottom:50%;--hint-transform:translateZ(0);--hint-before-margin-left:-13px;--hint-before-margin-top:initial;--hint-before-margin-right:initial;--hint-before-margin-bottom:-7px;--hint-after-margin-left:initial;--hint-after-margin-top:initial;--hint-after-margin-right:initial;--hint-after-margin-bottom:-14px;--hint-hover-transform:translateX(8px);--hint-border-color:transparent var(--hint-bgcolor) transparent transparent',
                    'bottom'    => '--hint-left:50%;--hint-top:100%;--hint-right:initial;--hint-bottom:initial;--hint-transform:translateX(-50%);--hint-before-margin-left:initial;--hint-before-margin-top:-13px;--hint-before-margin-right:initial;--hint-before-margin-bottom:initial;--hint-after-margin-left:initial;--hint-after-margin-top:initial;--hint-after-margin-right:initial;--hint-after-margin-bottom:-14px;--hint-hover-transform:translateX(-50%) translateY(8px);--hint-border-color:transparent transparent var(--hint-bgcolor) transparent',
                    'left'      => '--hint-left:initial;--hint-top:initial;--hint-right:100%;--hint-bottom:50%;--hint-transform:translateZ(0);--hint-before-margin-left:initial;--hint-before-margin-top:initial;--hint-before-margin-right:-13px;--hint-before-margin-bottom:-7px;--hint-after-margin-left:initial;--hint-after-margin-top:initial;--hint-after-margin-right:initial;--hint-after-margin-bottom:-14px;--hint-hover-transform:translateX(-8px);--hint-border-color:transparent transparent transparent var(--hint-bgcolor)',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-a' => '{{VALUE}}',
                ],
            ]
        );
        */

        $this->_end_controls_section();
    }

    protected function v2_style_image_zone_2(){
        $this->_start_controls_section(
            'section_style__image_zone_2',
            [
                'label' => esc_html__( 'Product Image Zone Action 2', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_control(
            'zone_2_visible_on_hover',
            [
                'label'        => esc_html__( 'Show on hover', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'return_value' => 'yes',
                'prefix_class' => 'actionzone-b-hover-',
            ]
        );

        $this->_add_control(
            'zone_2_hide_on',
            [
                'label'        => esc_html__( 'Hide on', 'lastudio-kit' ),
                'type'         => Controls_Manager::SELECT2,
                'options'      => [
                    'desktop' => esc_html__( 'Desktop', 'lastudio-kit' ),
                    'laptop' => esc_html__( 'Laptop', 'lastudio-kit' ),
                    'tablet' => esc_html__( 'Tablet', 'lastudio-kit' ),
                    'mobile_extra' => esc_html__( 'Mobile Extra', 'lastudio-kit' ),
                    'mobile' => esc_html__( 'Mobile', 'lastudio-kit' ),
                ],
                'multiple' => true,
            ]
        );

        $this->_add_control(
            'zone_2_show_toggle',
            [
                'label'        => esc_html__( 'Show first item', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'return_value' => 'yes',
                'prefix_class' => 'actionzone-b-toggle-',
                'condition'    => [
                    'zone_2_visible_on_hover!' => 'yes'
                ]
            ]
        );
        $this->_add_control(
            'zone_2_bgcolor',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-b' => '--lakit-zone-bg: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_2_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-b' => '--lakit-zone-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_2_direction',
            [
                'label' => esc_html_x( 'Zone direction', 'Flex Container Control', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html_x( 'Row', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-arrow-right',
                    ],
                    'column' => [
                        'title' => esc_html_x( 'Column', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-arrow-down',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-b' => '--lakit-zone-direction: {{VALUE}};',
                ],
                'prefix_class' => 'actionzone-b-direction-',
            ]
        );
        $this->_add_responsive_control(
            'zone_2_width',
            [
                'label' => esc_html__( 'Zone width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-b' => '--lakit-zone-width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_2_gap',
            [
                'label' => esc_html__( 'Zone Items gap', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
                    'size' => '5',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-b' => '--lakit-zone-gap: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_2_padding',
            array(
                'label'       => esc_html__( 'Zone padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-b' => '--lakit-zone-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_2_margin',
            array(
                'label'       => esc_html__( 'Zone margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-b' => '--lakit-zone-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_2_radius',
            array(
                'label'       => esc_html__( 'Zone border radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-b' => '--lakit-zone-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'zone_2_shadow',
                'selector' => '{{WRAPPER}} .lakitp-zone-b',
            ]
        );

        $this->_add_control(
            '_zone_2_heading_button',
            [
                'label' => esc_html__( 'Buttons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_start_controls_tabs( 'zone_2_action_tabs' );
        $this->_start_controls_tab( 'zone_2_action_tab_normal', [
            'label' => esc_html__( 'Normal', 'lastudio-kit' ),
        ] );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'zone_2_btn_font',
                'selector' => '{{WRAPPER}} .lakitp-zone-b .lakit-btn',
            ]
        );
        $this->_add_control(
            'zone_2_btn_color',
            array(
                'label'     => esc_html__( 'Text color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_2_btn_bgcolor',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_2_btn_padding',
            array(
                'label'       => esc_html__( 'Button padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'zone_2_btn_border',
                'label' => esc_html__( 'Button border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakitp-zone-b .lakit-btn',
            ]
        );

        $this->_add_responsive_control(
            'zone_2_btn_radius',
            array(
                'label'       => esc_html__( 'Button radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'zone_2_btn_shadow',
                'selector' => '{{WRAPPER}} .lakitp-zone-b .lakit-btn',
            ]
        );

        $this->_add_control(
            '_zone_2_heading_button_1',
            [
                'label' => esc_html__( 'Icons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->_add_responsive_control(
            'zone_2_btn_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-b' => '--lakit-zone-icon-font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn .lakit-btn--icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_2_btn_icon_padding',
            array(
                'label'       => esc_html__( 'Button icon padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn .lakit-btn--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_2_btn_icon_margin',
            array(
                'label'       => esc_html__( 'Button icon margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn .lakit-btn--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_tab();
        $this->_start_controls_tab( 'zone_2_action_tab_hover', [
            'label' => esc_html__( 'Hover', 'lastudio-kit' ),
        ] );
        $this->_add_control(
            'zone_2_btn_color_hover',
            array(
                'label'     => esc_html__( 'Text color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn.added' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_2_btn_bgcolor_hover',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn.added' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_2_btn_padding_hover',
            array(
                'label'       => esc_html__( 'Button padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn.added' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'zone_2_btn_border_hover',
                'label' => esc_html__( 'Button border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakitp-zone-b .lakit-btn:hover, {{WRAPPER}} .lakitp-zone-b .lakit-btn.added',
            ]
        );

        $this->_add_responsive_control(
            'zone_2_btn_radius_hover',
            array(
                'label'       => esc_html__( 'Button radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn.added' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'zone_2_btn_shadow_hover',
                'selector' => '{{WRAPPER}} .lakitp-zone-b .lakit-btn:hover, {{WRAPPER}} .lakitp-zone-b .lakit-btn.added',
            ]
        );

        $this->_add_control(
            '_zone_2_heading_button_2',
            [
                'label' => esc_html__( 'Icons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->_add_responsive_control(
            'zone_2_btn_icon_size_hover',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn:hover .lakit-btn--icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn.added .lakit-btn--icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_2_btn_icon_padding_hover',
            array(
                'label'       => esc_html__( 'Button icon padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn:hover .lakit-btn--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn.added .lakit-btn--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_2_btn_icon_margin_hover',
            array(
                'label'       => esc_html__( 'Button icon margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn:hover .lakit-btn--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-b .lakit-btn.added .lakit-btn--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_end_controls_tab();
        $this->_end_controls_tabs();

        $this->_add_control(
            '_zone_2_heading_tooltip',
            [
                'label' => esc_html__( 'Tooltip', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->_add_control(
            'zone_2_tooltip_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.lakit-tooltip-zone-b--id-{{ID}}' => '--hint-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_2_tooltip_bg',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.lakit-tooltip-zone-b--id-{{ID}}' => '--hint-bgcolor: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_section();
    }

    protected function v2_style_image_zone_3(){
        $this->_start_controls_section(
            'section_style__image_zone_3',
            [
                'label' => esc_html__( 'Product Image Zone Action 3', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_control(
            'zone_3_visible_on_hover',
            [
                'label'        => esc_html__( 'Show on hover', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'return_value' => 'yes',
                'prefix_class' => 'actionzone-c-hover-',
            ]
        );

        $this->_add_control(
            'zone_3_hide_on',
            [
                'label'        => esc_html__( 'Hide on', 'lastudio-kit' ),
                'type'         => Controls_Manager::SELECT2,
                'options'      => [
                    'desktop' => esc_html__( 'Desktop', 'lastudio-kit' ),
                    'laptop' => esc_html__( 'Laptop', 'lastudio-kit' ),
                    'tablet' => esc_html__( 'Tablet', 'lastudio-kit' ),
                    'mobile_extra' => esc_html__( 'Mobile Extra', 'lastudio-kit' ),
                    'mobile' => esc_html__( 'Mobile', 'lastudio-kit' ),
                ],
                'multiple' => true,
            ]
        );

        $this->_add_control(
            'zone_3_bgcolor',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-c' => '--lakit-zone-bg: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'zone_3_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-c' => '--lakit-zone-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'zone_3_content_align',
            [
                'label' => esc_html_x( 'Content Align', 'Flex Container Control', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center',  'lastudio-kit' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => '',
                'selectors_dictionary' => [
                    'left'    => 'text-align:left; align-items: flex-start;',
                    'center' => 'text-align:center; align-items: center;',
                    'right' => 'text-align:right; align-items: flex-end;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-c' => '{{VALUE}}',
                ],
            ]
        );
        $this->_add_responsive_control(
            'zone_3_width',
            [
                'label' => esc_html__( 'Zone width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom' ],
                'default' => [
                    'unit' => '%',
                    'size' => '100',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-c' => '--lakit-zone-width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_3_gap',
            [
                'label' => esc_html__( 'Zone Items gap', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
                    'size' => '5',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-c' => '--lakit-zone-gap: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_3_padding',
            array(
                'label'       => esc_html__( 'Zone padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-c' => '--lakit-zone-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_3_margin',
            array(
                'label'       => esc_html__( 'Zone margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-c' => '--lakit-zone-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_3_radius',
            array(
                'label'       => esc_html__( 'Zone border radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-c' => '--lakit-zone-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'zone_3_shadow',
                'selector' => '{{WRAPPER}} .lakitp-zone-c',
            ]
        );

        $this->_add_control(
            '_zone_3_heading_button',
            [
                'label' => esc_html__( 'Buttons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_start_controls_tabs( 'zone_3_action_tabs' );
        $this->_start_controls_tab( 'zone_3_action_tab_normal', [
            'label' => esc_html__( 'Normal', 'lastudio-kit' ),
        ] );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'zone_3_btn_font',
                'selector' => '{{WRAPPER}} .lakitp-zone-c .lakit-btn',
            ]
        );
        $this->_add_control(
            'zone_3_btn_color',
            array(
                'label'     => esc_html__( 'Text color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_3_btn_bgcolor',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_3_btn_padding',
            array(
                'label'       => esc_html__( 'Button padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'zone_3_btn_border',
                'label' => esc_html__( 'Button border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakitp-zone-c .lakit-btn',
            ]
        );

        $this->_add_responsive_control(
            'zone_3_btn_radius',
            array(
                'label'       => esc_html__( 'Button radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'zone_3_btn_shadow',
                'selector' => '{{WRAPPER}} .lakitp-zone-c .lakit-btn',
            ]
        );

        $this->_add_control(
            '_zone_3_heading_button_1',
            [
                'label' => esc_html__( 'Icons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->_add_responsive_control(
            'zone_3_btn_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn .lakit-btn--icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_3_btn_icon_padding',
            array(
                'label'       => esc_html__( 'Button icon padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn .lakit-btn--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_3_btn_icon_margin',
            array(
                'label'       => esc_html__( 'Button icon margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn .lakit-btn--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_tab();
        $this->_start_controls_tab( 'zone_3_action_tab_hover', [
            'label' => esc_html__( 'Hover', 'lastudio-kit' ),
        ] );
        $this->_add_control(
            'zone_3_btn_color_hover',
            array(
                'label'     => esc_html__( 'Text color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn.added' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_3_btn_bgcolor_hover',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn.added' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_3_btn_padding_hover',
            array(
                'label'       => esc_html__( 'Button padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn.added' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'zone_3_btn_border_hover',
                'label' => esc_html__( 'Button border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakitp-zone-c .lakit-btn:hover, {{WRAPPER}} .lakitp-zone-c .lakit-btn.added',
            ]
        );

        $this->_add_responsive_control(
            'zone_3_btn_radius_hover',
            array(
                'label'       => esc_html__( 'Button radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn.added' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'zone_3_btn_shadow_hover',
                'selector' => '{{WRAPPER}} .lakitp-zone-c .lakit-btn:hover, {{WRAPPER}} .lakitp-zone-c .lakit-btn.added',
            ]
        );

        $this->_add_control(
            '_zone_3_heading_button_2',
            [
                'label' => esc_html__( 'Icons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->_add_responsive_control(
            'zone_3_btn_icon_size_hover',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn:hover .lakit-btn--icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn.added .lakit-btn--icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_3_btn_icon_padding_hover',
            array(
                'label'       => esc_html__( 'Button icon padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn:hover .lakit-btn--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn.added .lakit-btn--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_3_btn_icon_margin_hover',
            array(
                'label'       => esc_html__( 'Button icon margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn:hover .lakit-btn--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-c .lakit-btn.added .lakit-btn--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_end_controls_tab();
        $this->_end_controls_tabs();

        $this->_add_control(
            '_zone_3_heading_tooltip',
            [
                'label' => esc_html__( 'Tooltip', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->_add_control(
            'zone_3_tooltip_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.lakit-tooltip-zone-c--id-{{ID}}' => '--hint-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_3_tooltip_bg',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.lakit-tooltip-zone-c--id-{{ID}}' => '--hint-bgcolor: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_section();
    }

    protected function v2_style_image_zone_4(){
        $this->_start_controls_section(
            'section_style__image_zone_4',
            [
                'label' => esc_html__( 'Product Zone Content', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_control(
            'zone_4_hide_on',
            [
                'label'        => esc_html__( 'Hide on', 'lastudio-kit' ),
                'type'         => Controls_Manager::SELECT2,
                'options'      => [
                    'desktop' => esc_html__( 'Desktop', 'lastudio-kit' ),
                    'laptop' => esc_html__( 'Laptop', 'lastudio-kit' ),
                    'tablet' => esc_html__( 'Tablet', 'lastudio-kit' ),
                    'mobile_extra' => esc_html__( 'Mobile Extra', 'lastudio-kit' ),
                    'mobile' => esc_html__( 'Mobile', 'lastudio-kit' ),
                ],
                'multiple' => true,
            ]
        );

        $this->_add_control(
            'zone_4_bgcolor',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-d' => '--lakit-zone-bg: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'zone_4_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-d' => '--lakit-zone-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_4_direction',
            [
                'label' => esc_html_x( 'Zone direction', 'Flex Container Control', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html_x( 'Row', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-arrow-right',
                    ],
                    'column' => [
                        'title' => esc_html_x( 'Column', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-arrow-down',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-d' => '--lakit-zone-direction: {{VALUE}};',
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_4_content_align',
            [
                'label' => esc_html_x( 'Content Align', 'Flex Container Control', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center',  'lastudio-kit' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => '',
                'selectors_dictionary' => [
                    'left'    => 'text-align:left; align-items: flex-start;',
                    'center' => 'text-align:center; align-items: center;',
                    'right' => 'text-align:right; align-items: flex-end;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-d' => '{{VALUE}}',
                ],
            ]
        );
        $this->_add_responsive_control(
            'zone_4_width',
            [
                'label' => esc_html__( 'Zone width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom' ],
                'default' => [
                    'unit' => '%',
                    'size' => '100',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-d' => '--lakit-zone-width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_4_gap',
            [
                'label' => esc_html__( 'Zone Items gap', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
                    'size' => '5',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-d' => '--lakit-zone-gap: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_4_padding',
            array(
                'label'       => esc_html__( 'Zone padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-d' => '--lakit-zone-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_4_margin',
            array(
                'label'       => esc_html__( 'Zone margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-d' => '--lakit-zone-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_4_radius',
            array(
                'label'       => esc_html__( 'Zone border radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-d' => '--lakit-zone-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'zone_4_shadow',
                'selector' => '{{WRAPPER}} .lakitp-zone-d',
            ]
        );

        $this->_add_control(
            '_zone_4_heading_button',
            [
                'label' => esc_html__( 'Buttons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_start_controls_tabs( 'zone_4_action_tabs' );
        $this->_start_controls_tab( 'zone_4_action_tab_normal', [
            'label' => esc_html__( 'Normal', 'lastudio-kit' ),
        ] );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'zone_4_btn_font',
                'selector' => '{{WRAPPER}} .lakitp-zone-d .lakit-btn',
            ]
        );
        $this->_add_control(
            'zone_4_btn_color',
            array(
                'label'     => esc_html__( 'Text color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_4_btn_bgcolor',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_4_btn_padding',
            array(
                'label'       => esc_html__( 'Button padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'zone_4_btn_border',
                'label' => esc_html__( 'Button border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakitp-zone-d .lakit-btn',
            ]
        );

        $this->_add_responsive_control(
            'zone_4_btn_radius',
            array(
                'label'       => esc_html__( 'Button radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'zone_4_btn_shadow',
                'selector' => '{{WRAPPER}} .lakitp-zone-d .lakit-btn',
            ]
        );

        $this->_add_control(
            '_zone_4_heading_button_1',
            [
                'label' => esc_html__( 'Icons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->_add_responsive_control(
            'zone_4_btn_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn .lakit-btn--icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_4_btn_icon_padding',
            array(
                'label'       => esc_html__( 'Button icon padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn .lakit-btn--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_4_btn_icon_margin',
            array(
                'label'       => esc_html__( 'Button icon margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn .lakit-btn--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_tab();
        $this->_start_controls_tab( 'zone_4_action_tab_hover', [
            'label' => esc_html__( 'Hover', 'lastudio-kit' ),
        ] );
        $this->_add_control(
            'zone_4_btn_color_hover',
            array(
                'label'     => esc_html__( 'Text color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn.added' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_4_btn_bgcolor_hover',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn.added' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_4_btn_padding_hover',
            array(
                'label'       => esc_html__( 'Button padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn.added' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'zone_4_btn_border_hover',
                'label' => esc_html__( 'Button border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakitp-zone-d .lakit-btn:hover, {{WRAPPER}} .lakitp-zone-d .lakit-btn.added',
            ]
        );

        $this->_add_responsive_control(
            'zone_4_btn_radius_hover',
            array(
                'label'       => esc_html__( 'Button radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn.added' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'zone_4_btn_shadow_hover',
                'selector' => '{{WRAPPER}} .lakitp-zone-d .lakit-btn:hover, {{WRAPPER}} .lakitp-zone-d .lakit-btn.added',
            ]
        );

        $this->_add_control(
            '_zone_4_heading_button_2',
            [
                'label' => esc_html__( 'Icons', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->_add_responsive_control(
            'zone_4_btn_icon_size_hover',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn:hover .lakit-btn--icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn.added .lakit-btn--icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->_add_responsive_control(
            'zone_4_btn_icon_padding_hover',
            array(
                'label'       => esc_html__( 'Button icon padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn:hover .lakit-btn--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn.added .lakit-btn--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'zone_4_btn_icon_margin_hover',
            array(
                'label'       => esc_html__( 'Button icon margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn:hover .lakit-btn--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakitp-zone-d .lakit-btn.added .lakit-btn--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_end_controls_tab();
        $this->_end_controls_tabs();


        $this->_add_control(
            '_zone_4_heading_tooltip',
            [
                'label' => esc_html__( 'Tooltip', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->_add_control(
            'zone_4_tooltip_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.lakit-tooltip-zone-d--id-{{ID}}' => '--hint-bgcolor: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'zone_4_tooltip_bg',
            array(
                'label'     => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.lakit-tooltip-zone-d--id-{{ID}}' => '--hint-bgcolor: {{VALUE}}',
                ),
            )
        );
        $this->_end_controls_section();
    }

    protected function v2_style_title(){
        $this->_start_controls_section(
            'section_style__title',
            [
                'label' => esc_html__( 'Product Title', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .product_item--title',
            ]
        );
        $this->_add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_control(
            'title_color_hover',
            [
                'label' => esc_html__( 'Hover Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--title:hover a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_end_controls_section();
    }

    protected function v2_style_price(){
        $this->_start_controls_section(
            'section_style__price',
            [
                'label' => esc_html__( 'Product Price', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_control(
            'price_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--price' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .product_item--price ins' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .product_item--price ins .amount' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .product_item--price',
            ]
        );

        $this->_add_control(
            'heading_old_price_style',
            [
                'label' => esc_html__( 'Regular Price', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_add_control(
            'old_price_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--price del' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .product_item--price del .amount' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'old_price_typography',
                'selector' => '{{WRAPPER}} .product_item--price del  ',
            ]
        );

        $this->_add_responsive_control(
            'price_spacing',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}  .product_item--price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_end_controls_section();
    }

    protected function v2_style_rating(){
        $this->_start_controls_section(
            'section_style__rating',
            [
                'label' => esc_html__( 'Product Rating', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_responsive_control(
            'star_size',
            [
                'label' => esc_html__( 'Star Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--rating' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->_add_control(
            'star_color',
            [
                'label' => esc_html__( 'Star Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--rating span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->_add_control(
            'empty_star_color',
            [
                'label' => esc_html__( 'Empty Star Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--rating' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'rating_spacing',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}  .product_item--rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_end_controls_section();
    }

    protected function v2_style_stock(){
        $this->_start_controls_section(
            'section_style__stock',
            [
                'label' => esc_html__( 'Product Stock', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_responsive_control(
            'stock_text_align',
            [
                'label' => esc_html__( 'Text Align', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--stock' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'stock_typography',
                'selector' => '{{WRAPPER}} .product_item--stock',
            ]
        );

        $this->add_control(
            'stock_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--stock' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'stock_bar_color',
            [
                'label' => esc_html__( 'Progress bar background', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .stock_bar--progress' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'stock_bar_active_color',
            [
                'label' => esc_html__( 'Progress bar active', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .stock_bar--progress-val' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'stock_bar_height',
            [
                'label'      => esc_html__( 'Progress bar height', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => [
                    '{{WRAPPER}} .stock_bar--progress' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'stock_bar_radius',
            [
                'label'      => esc_html__( 'Progress bar radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => [
                    '{{WRAPPER}} .stock_bar--progress' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'stock_spacing',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}  .product_item--stock' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_add_control(
            'stock_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--stock .lakitp-zone-item--icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_responsive_control(
            'stock_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--stock .lakitp-zone-item--icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'stock_icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--stock .lakitp-zone-item--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_end_controls_section();
    }

    protected function v2_style_description(){
        $this->_start_controls_section(
            'section_style__desc',
            [
                'label' => esc_html__( 'Product Short Description', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .product_item--short_description',
            ]
        );
        $this->_add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--short_description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'desc_spacing',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--short_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_end_controls_section();
    }

    protected function v2_style_tag(){

        $this->_start_controls_section(
            'section_style__tag',
            [
                'label' => esc_html__( 'Product Tags', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_control(
            'tag_first_item',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Display only first item', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
                'prefix_class' => 'zone-tag-only-item-',
                'condition' => [
                    'tag_last_item' => ''
                ]
            )
        );
        $this->_add_control(
            'tag_last_item',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Display only last item', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
                'prefix_class' => 'zone-tag-only-litem-',
                'condition' => [
                    'tag_first_item' => ''
                ]
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tag_typography',
                'selector' => '{{WRAPPER}} .product_item--tags',
            ]
        );
        $this->_add_control(
            'tag_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--tags' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_control(
            'tag_color_hover',
            [
                'label' => esc_html__( 'Hover Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--tags a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'tag_spacing',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--tags' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_add_control(
            'tag_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--tags .lakitp-zone-item--icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_responsive_control(
            'tag_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--tags .lakitp-zone-item--icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'tag_icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--tags .lakitp-zone-item--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_end_controls_section();
    }

    protected function v2_style_category(){

        $this->_start_controls_section(
            'section_style__category',
            [
                'label' => esc_html__( 'Product Category', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_control(
            'cat_first_item',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Display only first item', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
                'prefix_class' => 'zone-cat-only-item-',
                'condition' => [
                    'cat_last_item' => ''
                ]
            )
        );
        $this->_add_control(
            'cat_last_item',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Display only last item', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
                'prefix_class' => 'zone-cat-only-litem-',
                'condition' => [
                    'cat_first_item' => ''
                ]
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cat_typography',
                'selector' => '{{WRAPPER}} .product_item--category',
            ]
        );
        $this->_add_control(
            'cat_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--category' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_control(
            'cat_color_hover',
            [
                'label' => esc_html__( 'Hover Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--category a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'cat_spacing',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_add_control(
            'cat_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--category .lakitp-zone-item--icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_responsive_control(
            'cat_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--category .lakitp-zone-item--icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'cat_icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--category .lakitp-zone-item--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        $this->_end_controls_section();
    }

    protected function v2_style_cfield(){

        $this->_start_controls_section(
            'section_style__cfield',
            [
                'label' => esc_html__( 'Product Custom Field', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cfield_typography',
                'selector' => '{{WRAPPER}} .product_item--cfield',
            ]
        );
        $this->_add_control(
            'cfield_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--cfield' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->_add_control(
            'cfield_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--cfield .lakitp-zone-item--icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_responsive_control(
            'cfield_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--cfield .lakitp-zone-item--icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'cfield_icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--cfield .lakitp-zone-item--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_responsive_control(
            'cfield_spacing',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--cfield' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_end_controls_section();
    }

    protected function v2_style_slfield(){

        $this->_start_controls_section(
            'section_style__slfield',
            [
                'label' => esc_html__( 'Product Shipping Class', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'slfield_typography',
                'selector' => '{{WRAPPER}} .product_item--shipping_class',
            ]
        );
        $this->_add_control(
            'slfield_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--shipping_class' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->_add_control(
            'slfield_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--shipping_class .lakitp-zone-item--icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_responsive_control(
            'slfield_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--shipping_class .lakitp-zone-item--icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'slfield_icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--shipping_class .lakitp-zone-item--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_responsive_control(
            'slfield_spacing',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--shipping_class' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_end_controls_section();
    }

    protected function v2_style_author(){

        $this->_start_controls_section(
            'section_style__author',
            [
                'label' => esc_html__( 'Product Author', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'author_typography',
                'selector' => '{{WRAPPER}} .product_item--author',
            ]
        );
        $this->_add_control(
            'author_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--author' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'author_spacing',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--author' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_add_control(
            'author_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--author .lakitp-zone-item--icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_responsive_control(
            'author_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--author .lakitp-zone-item--icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'author_icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--author .lakitp-zone-item--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_end_controls_section();
    }

    protected function v2_style_attribute(){

        $this->_start_controls_section(
            'section_style__attribute',
            [
                'label' => esc_html__( 'Product Attribute', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_responsive_control(
            'attribute_content_align',
            [
                'label' => esc_html_x( 'Content Align', 'Flex Container Control', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center',  'lastudio-kit' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => '',
                'selectors_dictionary' => [
                    'left'    => 'text-align:left; align-items: flex-start;',
                    'center' => 'text-align:center; align-items: center;',
                    'right' => 'text-align:right; align-items: flex-end;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--attributes' => '{{VALUE}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'attribute_item_fontsize',
            [
                'label'     => esc_html__( 'Item Fontsize', 'lastudio-kit' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-item .swatch-wrapper' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'attribute_item_width',
            [
                'label'     => esc_html__( 'Item Width', 'lastudio-kit' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-item .swatch-wrapper' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'attribute_item_height',
            [
                'label'     => esc_html__( 'Item Height', 'lastudio-kit' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-item .swatch-wrapper' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'attribute_item_gap',
            [
                'label'     => esc_html__( 'Item gap', 'lastudio-kit' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--attributes' => '--lakit-zone-swatches-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'attribute_item_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakitp-zone-item .swatch-wrapper' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'attribute_spacing',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--attributes' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_end_controls_section();
    }

    protected function v2_style_countdown(){

        $this->_start_controls_section(
            'section_style__countdown',
            [
                'label' => esc_html__( 'Product Countdown', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'countdown_spacing',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--countdown' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'countdown__heading_01',
            [
                'label' => esc_html__( 'Item', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'countdown_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-zone--countdown-color: {{VALUE}}',
                ]
            ]
        );
        $this->add_control(
            'countdown_bg',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-zone--countdown-bg: {{VALUE}}',
                ]
            ]
        );
        $this->_add_responsive_control(
            'countdown_item_gap',
            [
                'label'       => esc_html__( 'Gap', 'lastudio-kit' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 'px', '%', 'em', 'custom' ],
                'selectors'   => [
                    '{{WRAPPER}}' => '--lakit-zone--countdown-gap: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'countdown_item_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-zone--countdown-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'countdown__heading_02',
            [
                'label' => esc_html__( 'Number', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'countdown_number_typography',
                'selector' => '{{WRAPPER}} .lakit-countdown-timer__item-value',
            ]
        );
        $this->add_control(
            'countdown_number_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-zone--countdown-number-color: {{VALUE}}',
                ]
            ]
        );
        $this->_add_responsive_control(
            'countdown_number_gap',
            [
                'label'       => esc_html__( 'Gap', 'lastudio-kit' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 'px', '%', 'em', 'custom' ],
                'selectors'   => [
                    '{{WRAPPER}}' => '--lakit-zone--countdown-number-gap: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'countdown__heading_03',
            [
                'label' => esc_html__( 'Label', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'countdown_label_typography',
                'selector' => '{{WRAPPER}} .lakit-countdown-timer__item-label',
            ]
        );
        $this->add_control(
            'countdown_label_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-zone--countdown-label-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'countdown__heading_04',
            [
                'label' => esc_html__( 'Text', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'countdown_label_day',
            array(
                'label' => esc_html__( 'Day label', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
            )
        );
        $this->add_control(
            'countdown_label_hour',
            array(
                'label' => esc_html__( 'Hour label', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
            )
        );
        $this->add_control(
            'countdown_label_minute',
            array(
                'label' => esc_html__( ' Minute label', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT
            )
        );
        $this->add_control(
            'countdown_label_second',
            array(
                'label' => esc_html__( ' Second label', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
            )
        );

        $this->_end_controls_section();
    }

    protected function v2_style_product_list(){

        $this->_start_controls_section(
            'section_style__product_list',
            [
                'label' => esc_html__( 'Product List', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout' => 'list'
                ]
            ]
        );
        $this->_add_responsive_control(
            'boxcontent_alignment',
            array(
                'label'     => esc_html__( 'Horizontal Alignment', 'lastudio-kit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .product_item--inner' => 'text-align: {{VALUE}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'boxcontent_v_alignment',
            array(
                'label'     => esc_html__( 'Vertical Alignment', 'lastudio-kit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Middle', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .product_item--inner' => 'align-items: {{VALUE}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'zone_product_image_width',
            array(
                'label'       => esc_html__( 'Product Image Width', 'lastudio-kit' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    'vh' => array(
                        'min' => 0,
                        'max' => 100,
                    )
                ),
                'size_units'  => [ 'px', '%', 'em', 'custom' ],
                'selectors'   => [
                    '{{WRAPPER}}' => '--lakit-zone-product-image-width: {{SIZE}}{{UNIT}};'
                ],
            )
        );
        $this->_add_responsive_control(
            'zone_product_info_width',
            array(
                'label'       => esc_html__( 'Product Information Width', 'lastudio-kit' ),
                'type'        => Controls_Manager::SLIDER,
                'range'       => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    'vh' => array(
                        'min' => 0,
                        'max' => 100,
                    )
                ),
                'size_units'  => [ 'px', '%', 'em', 'custom' ],
                'selectors'   => [
                    '{{WRAPPER}}' => '--lakit-zone-product-information-width: {{SIZE}}{{UNIT}};'
                ],
            )
        );

        $this->_end_controls_section();
    }

    protected function register_controls_v2(){
        $this->register_query_controls();
        $this->v2_layout_section();
        $this->v2_content_setting_section();
        $this->register_masonry_setting_section( [ 'enable_masonry' => 'yes' ] );
        $this->register_carousel_section( [ 'enable_masonry!' => 'yes' ], 'columns');
        $this->v2_style_item_section();
        $this->v2_style_product_list();
        $this->v2_style_image_section();
        $this->v2_style_image_zone_1();
        $this->v2_style_image_zone_2();
        $this->v2_style_image_zone_3();
        $this->v2_style_image_zone_4();
        $this->v2_style_title();
        $this->v2_style_price();
        $this->v2_style_rating();
        $this->v2_style_stock();
        $this->v2_style_description();
        $this->v2_style_tag();
        $this->v2_style_category();
        $this->v2_style_author();
        $this->v2_style_cfield();
        $this->v2_style_attribute();
        $this->v2_style_countdown();
        $this->v2_style_slfield();
        $this->v2_style_heading_section();
        $this->register_section_style_pagination();
        $this->register_carousel_arrows_dots_style_section( [ 'enable_masonry!' => 'yes', 'enable_carousel' => 'yes' ] );
    }

    protected function register_controls() {
        if( lastudio_kit()->get_theme_support('elementor::product-grid-v2') ){
            $this->register_controls_v2();
        }
        else{
            $this->register_controls_v1();
        }
    }

    protected function get_shortcode_object( $settings ) {
        if ( 'current_query' === $settings[ Products_Renderer::QUERY_CONTROL_NAME . '_post_type' ] ) {
            return new Current_Query_Renderer( $settings, 'current_query' );
        }
        return new Products_Renderer( $settings, 'products' );
    }

    protected function render() {
        if(self::$__called_item == $this->get_id()){
            self::$__called_index++;
        }
        else{
            self::$__called_item = $this->get_id();
        }

        $unique_id = self::$__called_item . '_' . self::$__called_index;

        $paged_key = 'product-page-' . $unique_id;
        $query_post_type = $this->get_settings_for_display( 'query_post_type' );

        $enable_ajax_load = filter_var($this->get_settings_for_display('enable_ajax_load'), FILTER_VALIDATE_BOOLEAN);
        if( $query_post_type !== 'current_query' && $enable_ajax_load && !lastudio_kit()->elementor()->editor->is_edit_mode() && !isset($_REQUEST[$paged_key])){
            echo sprintf(
                '<div data-lakit_ajax_loadwidget="true" data-widget-id="%1$s" data-pagedkey="%2$s"><span class="lakit-css-loader"></span></div>',
                $this->get_id(),
                $paged_key
            );
            return;
        }

        $settings = $this->get_settings();

        if ('current_query' === $this->get_settings(Products_Renderer::QUERY_CONTROL_NAME . '_post_type') && WC()->session && function_exists('wc_print_notices')) {
            wc_print_notices();
	        $paged_key = 'paged';
        }

        // For Products_Renderer.
        if ( ! isset( $GLOBALS['post'] ) ) {
            $GLOBALS['post'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        }

	    $this->_paged_key = $paged_key;

        $settings['unique_id'] = $unique_id;
        $settings['widget_id'] = self::$__called_item;

        $carousel_dot_html = '';
        $carousel_arrow_html = '';
        $carousel_scrollbar_html = '';
        $masonry_filter = '';
        $masonry_settings = '';

        if( filter_var($this->get_settings_for_display('enable_masonry'), FILTER_VALIDATE_BOOLEAN) ) {
            $masonry_settings = $this->get_masonry_options('li.product', '.lakit-products__list');
            $masonry_filter = $this->render_masonry_filters('.lakit_wc_widget_'.$unique_id.' .lakit-products__list', false);
        }
        elseif (filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN)) {
            if (filter_var($this->get_settings_for_display('carousel_dots'), FILTER_VALIDATE_BOOLEAN)) {
                $carousel_dot_html = '<div class="lakit-carousel__dots lakit-carousel__dots_'.$unique_id.' swiper-pagination"></div>';
            }
            if (filter_var($this->get_settings_for_display('carousel_arrows'), FILTER_VALIDATE_BOOLEAN)) {
                $carousel_arrow_html = sprintf('<div class="lakit-carousel__prev-arrow-%s lakit-arrow prev-arrow">%s</div>', $unique_id, $this->_render_icon('carousel_prev_arrow', '%s', '', false));
                $carousel_arrow_html .= sprintf('<div class="lakit-carousel__next-arrow-%s lakit-arrow next-arrow">%s</div>', $unique_id, $this->_render_icon('carousel_next_arrow', '%s', '', false));
            }
            if (filter_var($this->get_settings_for_display('carousel_scrollbar'), FILTER_VALIDATE_BOOLEAN)) {
	            $carousel_scrollbar_html = sprintf('<div class="lakit-carousel__scrollbar swiper-scrollbar lakit-carousel__scrollbar_%1$s"></div>', $unique_id);
            }
        }

        $carousel_settings = $this->get_advanced_carousel_options('columns', $unique_id, $settings);

        $settings['lakit_extra_settings'] = [
            'carousel_settings' => $carousel_settings,
            'masonry_settings'  => $masonry_settings,
            'masonry_filter'  => $masonry_filter,
            'carousel_dot_html' => $carousel_dot_html,
            'carousel_arrow_html' => $carousel_arrow_html,
            'carousel_scrollbar_html' => $carousel_scrollbar_html,
        ];
        if( lastudio_kit()->get_theme_support('elementor::product-grid-v2') ) {
            $settings['lakit_v2_settings'] = [
                'product_image_zone_1' => $this->get_settings_for_display('product_image_zone_1'),
                'product_image_zone_2' => $this->get_settings_for_display('product_image_zone_2'),
                'product_image_zone_3' => $this->get_settings_for_display('product_image_zone_3'),
                'product_content_zone' => $this->get_settings_for_display('product_content_zone'),
                'product_content_buttons' => $this->get_settings_for_display('product_content_buttons'),
                'zone_1_hide_on' => $this->get_settings_for_display('zone_1_hide_on'),
                'zone_2_hide_on' => $this->get_settings_for_display('zone_2_hide_on'),
                'zone_3_hide_on' => $this->get_settings_for_display('zone_3_hide_on'),
                'zone_4_hide_on' => $this->get_settings_for_display('zone_4_hide_on'),
            ];
        }

        $shortcode = $this->get_shortcode_object( $settings );

        do_action('lastudio-kit/products/before_render', $settings);

        $content = $shortcode->get_content();
		if(!empty($content) && strlen($content) < 100){
			$content = '';
		}

        $nothing_found_message = $this->get_settings_for_display( 'nothing_found_message' );

        if ( $content ) {
            echo $content;
        }
        elseif ( !empty($nothing_found_message) ) {
            echo '<div class="elementor-nothing-found elementor-products-nothing-found woocommerce-no-products-found">' . esc_html( $nothing_found_message ) . '</div>';
        }

        do_action('lastudio-kit/products/after_render', $settings);
    }

    public function render_plain_content() {}
}