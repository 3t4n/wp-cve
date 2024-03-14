<?php

/**
 * Product Carousel widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Skt_Addons_Elementor\Elementor\Traits\Lazy_Query_Builder;
use WP_Query;

defined('ABSPATH') || die();

class Product_Carousel_New extends Base {

    use Lazy_Query_Builder;

    protected static $_query = null;

    public function get_title() {
        return __('Product Carousel', 'skt-addons-elementor');
    }

    public function get_icon() {
        return 'skti skti-Product-Carousel';
    }

    public function get_keywords() {
        return ['ecommerce', 'woocommerce', 'product', 'carousel', 'sale', 'skt-skin'];
    }

    /**
     * Overriding default function to add custom html class.
     *
     * @return string
     */
    public function get_html_wrapper_class() {
        $html_class = parent::get_html_wrapper_class();
        $html_class .= ' ' . str_replace('-new', '', $this->get_name());
        return $html_class;
    }

    public function get_query() {
        $args = $this->get_query_args();
        $args['posts_per_page'] = $this->get_settings_for_display('posts_per_page');

        if (is_null(self::$_query)) {
            self::$_query = new WP_Query();
        }

        self::$_query->query($args);

        return self::$_query;
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_post_layout',
            [
                'label' => __('Layout', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'skin',
            [
                'label' => __('Skin', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'classic' => __('Classic', 'skt-addons-elementor'),
                    'modern' => __('Modern', 'skt-addons-elementor'),
                ],
                'default' => 'classic',
                'render_type' => 'template',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'post_image',
                'default' => 'large',
                'exclude' => [
                    'custom'
                ]
            ]
        );

        $this->add_control(
            'product_on_sale_show',
            [
                'label' => __('Show On Sale Badge', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'skt-addons-elementor'),
                'label_off' => __('Hide', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'product_ratings_show',
            [
                'label' => __('Show Ratings', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'skt-addons-elementor'),
                'label_off' => __('Hide', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'product_add_to_cart_show',
            [
                'label' => __('Show Add To cart', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'skt-addons-elementor'),
                'label_off' => __('Hide', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'content_alignment',
            [
                'label' => __('Content Alignment', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
                'toggle' => true,
                'selectors_dictionary' => [
                    'left' => 'align-items: flex-start',
                    'center' => 'align-items: center',
                    'right' => 'align-items: flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-item-inner' => '{{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __('Title HTML Tag', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                // 'separator' => 'before',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h2',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_query',
            [
                'label' => __('Query', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->register_query_controls();
        $this->update_control(
            'posts_post_type',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => 'product'
            ]
        );
        $this->remove_control('posts_selected_ids');
        $this->update_control(
            'posts_include_by',
            [
                'options' => [
                    'terms' => __('Terms', 'skt-addons-elementor'),
                    'featured' => __('Featured Products', 'skt-addons-elementor'),
                ]
            ]
        );
        $this->remove_control('posts_include_author_ids');
        $this->update_control(
            'posts_exclude_by',
            [
                'options' => [
                    'current_post'      => __('Current Product', 'skt-addons-elementor'),
                    'manual_selection'  => __('Manual Selection', 'skt-addons-elementor'),
                    'terms'             => __('Terms', 'skt-addons-elementor'),
                ]
            ]
        );
        $this->remove_control('posts_exclude_author_ids');
        $this->update_control(
            'posts_include_term_ids',
            [
                'description' => __('Select product categories and tags', 'skt-addons-elementor'),
            ]
        );
        $this->update_control(
            'posts_exclude_term_ids',
            [
                'description' => __('Select product categories and tags', 'skt-addons-elementor'),
            ]
        );
        $this->update_control(
            'posts_select_date',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => 'anytime'
            ]
        );
        $this->remove_control('posts_date_before');
        $this->remove_control('posts_date_after');
        $this->update_control(
            'posts_orderby',
            [
                'options' => [
                    'comment_count' => __('Review Count', 'skt-addons-elementor'),
                    'date'          => __('Date', 'skt-addons-elementor'),
                    'ID'            => __('ID', 'skt-addons-elementor'),
                    'menu_order'    => __('Menu Order', 'skt-addons-elementor'),
                    'rand'          => __('Random', 'skt-addons-elementor'),
                    'title'         => __('Title', 'skt-addons-elementor'),
                ],
                'default' => 'title',
            ]
        );
        $this->update_control(
            'posts_order',
            [
                'default' => 'asc',
            ]
        );
        $this->remove_control('posts_ignore_sticky_posts');
        $this->update_control(
            'posts_only_with_featured_image',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => false
            ]
        );
        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Number Of Products', 'skt-addons-elementor'),
                'description' => __('Only visible products will be shown in the products grid. Hence number of products in the grid may differ from number of products setting.', 'skt-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 9,
            ]
        );

        $this->add_control(
            'add_to_cart_text',
            [
                'label' => __('Add To Cart Text', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => __('Add To Cart', 'skt-addons-elementor'),
                'dynamic' => [
                    'active' => true
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __('Carousel Settings', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'animation_speed',
            [
                'label' => __('Animation Speed', 'skt-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 10,
                'max' => 10000,
                'default' => 800,
                'description' => __('Slide speed in milliseconds', 'skt-addons-elementor'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed', 'skt-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 100,
                'max' => 10000,
                'default' => 2000,
                'description' => __('Autoplay speed in milliseconds', 'skt-addons-elementor'),
                'condition' => [
                    'autoplay' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __('Infinite Loop?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => __('Navigation', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __('None', 'skt-addons-elementor'),
                    'arrow' => __('Arrow', 'skt-addons-elementor'),
                    'dots' => __('Dots', 'skt-addons-elementor'),
                    'both' => __('Arrow & Dots', 'skt-addons-elementor'),
                ],
                'default' => 'arrow',
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => __('Slides To Show', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    1 => __('1 Slide', 'skt-addons-elementor'),
                    2 => __('2 Slides', 'skt-addons-elementor'),
                    3 => __('3 Slides', 'skt-addons-elementor'),
                    4 => __('4 Slides', 'skt-addons-elementor'),
                    5 => __('5 Slides', 'skt-addons-elementor'),
                    6 => __('6 Slides', 'skt-addons-elementor'),
                ],
                'desktop_default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_common_style',
            [
                'label' => __('Carousel Item', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'carousel_item_heght',
            [
                'label' => __('Height', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-item-inner' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'carousel_item_spacing',
            [
                'label' => __('Space between Items', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'carousel_item_border',
                'selector' => '{{WRAPPER}} .skt-product-carousel-item-inner',
            ]
        );

        $this->add_responsive_control(
            'carousel_item_border_radius',
            [
                'label' => __('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .skt-product-carousel-item-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'carousel_item_background',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .skt-product-carousel-item-inner'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_feature_image',
            [
                'label' => __('Image & Badge', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'feature_image_width',
            [
                'label' => __('Width', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 2000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-image img' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_image_height',
            [
                'label' => __('Height', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 2000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-image img' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_image_border_radius',
            [
                'label' => __('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .skt-product-carousel-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .skt-product-carousel-image',
            ]
        );

        $this->add_control(
            '_heading_badge',
            [
                'label' => __('Badge', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'badge_note',
            [
                'label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<strong>Badge</strong> is Switched off on "Layout"', 'skt-addons-elementor'),
                'condition' => [
                    'product_on_sale_show!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_position_toggle',
            [
                'label' => __('Position', 'skt-addons-elementor'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __('None', 'skt-addons-elementor'),
                'label_on' => __('Custom', 'skt-addons-elementor'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'badge_position_y',
            [
                'label' => __('Vertical', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'badge_position_toggle' => 'yes'
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-on-sale' => 'top: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_position_x',
            [
                'label' => __('Horizontal', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'badge_position_toggle' => 'yes'
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-on-sale' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => __('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-on-sale span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label' => __('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-on-sale span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'badge_box_shadow',
                'selector' => '{{WRAPPER}} .skt-product-carousel-on-sale span',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typography',
                'label' => __('Typography', 'skt-addons-elementor'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
                'selector' => '{{WRAPPER}} .skt-product-carousel-on-sale span',
            ]
        );

        $this->add_control(
            'badge_background_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-on-sale span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-on-sale span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_content_style',
            [
                'label' => __('Content', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '_heading_name',
            [
                'label' => __('Name', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'name_spacing',
            [
                'label' => __('Bottom Spacing', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-title' => 'margin-bottom: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => __('Typography', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-product-carousel-title',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-title a' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'name_hover_color',
            [
                'label' => __('Hover Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-title a:hover' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            '_heading_price',
            [
                'label' => __('Price', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'label' => __('Typography', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-product-carousel-price',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-price' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            '_heading_rating',
            [
                'label' => __('Ratings', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'ratings_note',
            [
                'label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<strong>Ratings</strong> is not selected on "Layout"', 'skt-addons-elementor'),
                'condition' => [
                    'product_ratings_show!' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'ratings_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-ratings .star-rating span:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_add_to_cart',
            [
                'label' => __('Add to Cart Button', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'add_to_cart_note',
            [
                'label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<strong>Add To Cart</strong> is not selected on "Layout"', 'skt-addons-elementor'),
                'condition' => [
                    'product_add_to_cart_show!' => 'yes'
                ],
            ]
        );

        $this->add_responsive_control(
            'add_to_cart_spacing',
            [
                'label' => __('Spacing', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-add-to-cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'add_to_cart_padding',
            [
                'label' => __('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-add-to-cart a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'add_to_cart_border_radius',
            [
                'label' => __('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-add-to-cart a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'add_to_cart_border',
                'selector' => '{{WRAPPER}} .skt-product-carousel-add-to-cart a',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'add_to_cart_typography',
                'label' => __('Typography', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-product-carousel-add-to-cart a',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->start_controls_tabs('_tab_add_to_cart_colors');
        $this->start_controls_tab(
            '_tab_links_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'add_to_cart_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-add-to-cart a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'add_to_cart_background_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-add-to-cart a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            '_tab_add_to_cart_hover',
            [
                'label' => __('Hover', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'add_to_cart_hover_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-add-to-cart a:hover, {{WRAPPER}} .skt-product-carousel-add-to-cart a:focus' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'add_to_cart_hover_background_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-add-to-cart a:hover, {{WRAPPER}} .skt-product-carousel-add-to-cart a:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'add_to_cart_hover_border_color',
            [
                'label' => __('Border Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-product-carousel-add-to-cart a:hover, {{WRAPPER}} .skt-product-carousel-add-to-cart a:focus' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'add_to_cart_border_border!' => '',
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_arrow',
            [
                'label' => __('Navigation - Arrow', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'arrow_position_toggle',
            [
                'label' => __('Position', 'skt-addons-elementor'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __('None', 'skt-addons-elementor'),
                'label_on' => __('Custom', 'skt-addons-elementor'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'arrow_sync_position',
            [
                'label' => __('Sync Position', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'yes' => [
                        'title' => __('Yes', 'skt-addons-elementor'),
                        'icon' => 'eicon-sync',
                    ],
                    'no' => [
                        'title' => __('No', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-stretch',
                    ]
                ],
                'condition' => [
                    'arrow_position_toggle' => 'yes'
                ],
                'default' => 'no',
                'toggle' => false,
                'prefix_class' => 'skt-arrow-sync-'
            ]
        );

        $this->add_control(
            'sync_position_alignment',
            [
                'label' => __('Alignment', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
                'condition' => [
                    'arrow_position_toggle' => 'yes',
                    'arrow_sync_position' => 'yes'
                ],
                'default' => 'center',
                'toggle' => false,
                'selectors_dictionary' => [
                    'left' => 'left: calc(0px + 80px)',
                    'center' => 'left: 50%',
                    'right' => 'left: calc(100% - 50px)',
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => '{{VALUE}}'
                ]
            ]
        );

        $this->add_responsive_control(
            'arrow_position_y',
            [
                'label' => __('Vertical', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'arrow_position_toggle' => 'yes'
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'top: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_position_x',
            [
                'label' => __('Horizontal', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'arrow_position_toggle' => 'yes'
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 1200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.skt-arrow-sync-no .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.skt-arrow-sync-no .slick-next' => 'right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.skt-arrow-sync-yes .slick-next, {{WRAPPER}}.skt-arrow-sync-yes .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_spacing',
            [
                'label' => __('Space between Arrows', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'arrow_position_toggle' => 'yes',
                    'arrow_sync_position' => 'yes'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}}.skt-arrow-sync-yes .slick-next' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->add_responsive_control(
            'arrow_size',
            [
                'label' => __('Box Size', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 70,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .slick-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_font_size',
            [
                'label' => __('Icon Size', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 2,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-prev' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .slick-next' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'arrow_border',
                'selector' => '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next',
            ]
        );

        $this->add_responsive_control(
            'arrow_border_radius',
            [
                'label' => __('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs('_tabs_arrow');

        $this->start_controls_tab(
            '_tab_arrow_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_arrow_hover',
            [
                'label' => __('Hover', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_bg_color',
            [
                'label' => __('Background Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_border_color',
            [
                'label' => __('Border Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'arrow_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_dots',
            [
                'label' => __('Navigation - Dots', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'dots_nav_position_y',
            [
                'label' => __('Vertical Position', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-dots' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_nav_spacing',
            [
                'label' => __('Space Between', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_nav_align',
            [
                'label' => __('Alignment', 'skt-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'skt-addons-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->start_controls_tabs('_tabs_dots');
        $this->start_controls_tab(
            '_tab_dots_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'dots_nav_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li button:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_dots_hover',
            [
                'label' => __('Hover', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'dots_nav_hover_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li button:hover:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_dots_active',
            [
                'label' => __('Active', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'dots_nav_active_color',
            [
                'label' => __('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots .slick-active button:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    public function custom_add_to_cart_text($text, $product) {
        $add_to_cart_text = $this->get_settings_for_display('add_to_cart_text');

        if ($product->get_type() === 'simple' && $product->is_purchasable() && $product->is_in_stock() && !empty($add_to_cart_text)) {
            $text = $add_to_cart_text;
        }

        return $text;
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        if (!class_exists('WooCommerce')) {
            printf('<div class="skt-product-carousel-error">%s</div>', __('Please Install/Activate Woocommerce Plugin.', 'skt-addons-elementor'));

            return;
        }

        $loop = $this->get_query();

        $this->add_render_attribute(
            'wrapper',
            'class',
            [
                'skt-product-carousel-wrapper',
                'skt-layout-' . $settings['skin'],
                'skt-product-carousel-' . $settings['skin'],
            ]
        );
        ?>

        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <?php
            if ($loop->have_posts()) :
                if($settings['skin'] == 'classic') :
                    add_filter('woocommerce_product_add_to_cart_text', [$this, 'custom_add_to_cart_text'], 10, 2);
                endif;

                while ($loop->have_posts()) : $loop->the_post();
                    global $product;
            ?>
                    <article class="skt-product-carousel-item" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <div class="skt-product-carousel-item-inner">
                            <div class="skt-product-carousel-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php echo wp_kses_post(woocommerce_get_product_thumbnail($settings['post_image_size'])); ?>
                                </a>

                                <?php if ($settings['product_on_sale_show'] == 'yes') : ?>
                                    <div class="skt-product-carousel-on-sale"><?php woocommerce_show_product_loop_sale_flash(); ?></div>
                                <?php endif; ?>

                                <?php if ($settings['product_add_to_cart_show'] == 'yes') : ?>
                                    <div class="skt-product-carousel-quick-view-wrap">
                                        <?php if ($settings['skin'] == 'modern' && $settings['product_add_to_cart_show'] == 'yes') : ?>
                                            <div class="skt-product-carousel-add-to-cart">
                                                <?php woocommerce_template_loop_add_to_cart(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                            </div>

                            <?php if ($settings['product_ratings_show'] == 'yes' && $product->get_average_rating()) : ?>
                                <div class="skt-product-carousel-ratings"><?php woocommerce_template_loop_rating();  ?></div>
                            <?php endif; ?>

                            <<?php echo wp_kses_post(skt_addons_elementor_escape_tags($settings['title_tag'], 'h2') . ' class="skt-product-carousel-title"'); ?>>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </<?php echo wp_kses_post(skt_addons_elementor_escape_tags($settings['title_tag'], 'h2')); ?>>

                            <div class="skt-product-carousel-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>

                            <?php if ($settings['skin'] == 'classic' && $settings['product_add_to_cart_show'] == 'yes') : ?>
                                <div class="skt-product-carousel-add-to-cart">
                                    <?php woocommerce_template_loop_add_to_cart(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>

            <?php
                endwhile;

                wp_reset_postdata();

                if($settings['skin'] == 'classic') :
                    remove_filter('woocommerce_product_add_to_cart_text', [$this, 'custom_add_to_cart_text'], 10, 2);
                endif;

            else :
                if (is_admin()) {
                    return printf('<div class="skt-product-carousel-error">%s</div>', __('Nothing Found. Please Add Products.', 'skt-addons-elementor'));
                }
            endif;
            ?>
        </div>

        <?php
    }
}