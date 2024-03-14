<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
class MEAFE_Product_Cat_Tab extends Widget_Base
{
    public function get_name()
    {
        return 'meafe-product-cat-tab';
    }

    public function get_title()
    {
        return esc_html__('Product Category Tab', 'mega-elements-addons-for-elementor');
    }

    public function get_icon()
    {
        return 'meafe-product-cat-tab';
    }

    public function get_categories()
    {
        return ['meafe-elements'];
    }

    public function get_style_depends()
    {
        return ['meafe-product-cat-tab'];
    }

    public function get_script_depends()
    {
        return ['meafe-product-cat-tab'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'meafe_PCT_content_general_settings',
            array(
                'label' => __('General Settings', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'PCT_layouts',
            [
                'label' => esc_html__('Select Layout', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'label_block' => false,
                'options' => [
                    '1' => esc_html__('Layout One', 'mega-elements-addons-for-elementor'),
                    '2' => esc_html__('Layout Two', 'mega-elements-addons-for-elementor'),
                    '3' => esc_html__('Layout Three', 'mega-elements-addons-for-elementor'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_cat_select',
            [
                'label' => esc_html__('Select product categories', 'mega-elements-addons-for-elementor'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => [],
                'options' => wp_list_pluck(get_terms('product_cat'), 'name', 'term_id'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_type',
            [
                'label' => esc_html__('Show products based on', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'latest',
                'label_block' => false,
                'options' => [
                    'latest' => esc_html__('Latest Products', 'mega-elements-addons-for-elementor'),
                    'popular' => esc_html__('Popular Products', 'mega-elements-addons-for-elementor'),
                    'sales' => esc_html__('Products on Sales', 'mega-elements-addons-for-elementor'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_number',
            [
                'label' => esc_html__('Number of Products', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
                'max' => '32',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_ed_filter',
            [
                'label' => esc_html__('Show Filters', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_ed_cat',
            [
                'label' => esc_html__('Show Category', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_ed_title',
            [
                'label' => esc_html__('Show Title', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_ed_price',
            [
                'label' => esc_html__('Show Price', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_ed_cart',
            [
                'label' => esc_html__('Show Cart', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_ed_quick_view',
            [
                'label' => esc_html__('Show Quick View', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_ed_wishlist',
            [
                'label' => esc_html__('Show Wishlist', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_ed_badge',
            [
                'label' => esc_html__('Show badge', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_ed_excerpt',
            [
                'label' => esc_html__('Show Excerpt', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'PCT_layouts' => '3',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_excerpt_number',
            [
                'label' => esc_html__('Excerpt Length', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '20',
                'condition' => [
                    'PCT_layouts' => '3',
                    'PCT_ed_excerpt' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_ed_carousel',
            [
                'label' => esc_html__('Show Carousel', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_prev_icon',
            [
                'label' => __('Previous Icon', 'mega-elements-addons-for-elementor'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-chevron-left',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'PCT_ed_carousel' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_next_icon',
            [
                'label' => __('Next Icon', 'mega-elements-addons-for-elementor'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'PCT_ed_carousel' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PCT_carousel_dots',
            [
                'label' => esc_html__('Show Carousel Dots', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        /**
         *  Product Category Tab Section General Style
         */
        $this->start_controls_section(
            'meafe_PCT_general_style',
            [
                'label' => esc_html__('General Style', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'PCT_padding',
            [
                'label' => __('Padding', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => ['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-products-wrapper .meafe-products .meafe-products-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'PCT_box_shadow',
                'selector' => '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-products-wrapper .meafe-products .meafe-products-inner',
            ]
        );

        $this->add_responsive_control(
            'PCT_border_radius',
            [
                'label' => __('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%','em'],
                'selectors' => [
                    '
                     {{WRAPPER}} .meafe-product-tab-wrapper .meafe-products-wrapper .meafe-products .meafe-products-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                     '{{WRAPPER}} .meafe-product-tab-wrapper.layout-1 .meafe-products-wrapper .meafe-products .meafe-products-inner img,{{WRAPPER}} .meafe-product-tab-wrapper.layout-2 .meafe-products-wrapper .meafe-products .meafe-products-inner img,{{WRAPPER}} .meafe-product-tab-wrapper.layout-3 .meafe-products-wrapper .meafe-products .meafe-products-inner img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0',
                     '{{WRAPPER}}.meafe-product-tab-wrapper.layout-1 .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper,{{WRAPPER}}.meafe-product-tab-wrapper.layout-2 .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'PCT_align',
            [
                'label' => esc_html__('Alignment', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'mega-elements-addons-for-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'mega-elements-addons-for-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'mega-elements-addons-for-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'left',
                'prefix_class' => 'meafe-product-tab-content-align-',
            ]
        );

        $this->end_controls_section();

        /**
         * Product Category Tab Section Content Style
         */
        $this->start_controls_section(
            'meafe_PCT_content_style',
            [
                'label' => __('Content Style', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'PCT_content_padding',
            [
                'label' => __('Padding', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => ['top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'PCT_content_bg_color',
            [
                'label' => __('Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'PCT_title_color',
            [
                'label' => __('Title Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .meafe-entry-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PCT_title_typography',
                'label' => __('Title Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .meafe-entry-title',
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 20]],
                    'line_height' => ['default' => ['unit' => 'em', 'size' => 1]]
                ]
            ]
        );

        $this->add_control(
            'PCT_cat_color',
            [
                'label' => __('Category Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#878787',
                'selectors' => [
                    '{{WRAPPER}}  .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .category--wrapper' => 'color: {{VALUE}};',
                ],
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 14]],
                    'line_height' => ['default' => ['unit' => 'em', 'size' => 1.6]]
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PCT_cat_typography',
                'label' => __('Category Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}}  .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .category--wrapper',
            ]
        );

        $this->add_control(
            'PCT_regular_price_color',
            [
                'label' => __('Regular Price Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#878787',
                'selectors' => [
                    '{{WRAPPER}}  .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer .price del' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PCT_regular_price_typo',
                'label' => __('Regular Price Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}}  .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer .price del',
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 16]],
                    'line_height' => ['default' => ['unit' => 'px', 'size' => 28.8]],
                    'font_weight' => ['default' => 'normal']
                ]
            ]
        );

        $this->add_control(
            'PCT_sale_price_color',
            [
                'label' => __('Sale Price Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#547c0e',
                'selectors' => [
                    '{{WRAPPER}}  .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer .price ins, {{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer .price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PCT_sale_price_typo',
                'label' => __('Sale Price Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}}  .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer .price ins, {{WRAPPER}} .elementor-widget-meafe-product-cat-tab .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer .price',
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 20]],
                    'line_height' => ['default' => ['unit' => 'px', 'size' => 28.8]],
                    'font_weight' => ['default' => 700]
                ]
            ]
        );

        $this->add_control(
            'PCT_excerpt_price_color',
            [
                'label' => __('Excerpt Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}  .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .meafe-entry-content p' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'PCT_layouts' => '3'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PCT_excerpt_price_typo',
                'label' => __('Excerpt Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .meafe-entry-content p',
                'condition' => [
                    'PCT_layouts' => '3'
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Product Category Tab Section Content Style
         */
        $this->start_controls_section(
            'meafe_PCT_icon_style',
            [
                'label' => __('Product Icons', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'PCT_icon_size',
            [
                'label' => esc_html__('Icon Size', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .product-meta .add-to-cart .button::before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner  .product-meta .quickview-icon .yith-wcqv-button::before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .product-meta .yith-wcwl-add-to-wishlist .yith-wcwl-add-button .add_to_wishlist::before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs('PCT_icon_colors');

        $this->start_controls_tab(
            'PCT_icon_initial',
            [
                'label' => __('Initial', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PCT_cart_icon_color_initial',
            [
                'label' => esc_html__('Cart Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .product-meta .add-to-cart .button::before' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'PCT_layouts' => '1',
                ],
            ]
        );

        $this->add_control(
            'PCT_cart_icon_bg_color_initial',
            [
                'label' => esc_html__('Cart Icon Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .product-meta .add-to-cart .button ' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'PCT_layouts' => '1',
                ],
            ]
        );

        $this->add_control(
            'PCT_quickview_icon_color_initial',
            [
                'label' => esc_html__('Quick View Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner  .product-meta .quickview-icon .yith-wcqv-button::before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PCT_quickview_icon_bg_color_initial',
            [
                'label' => esc_html__('Quick View Icon Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner  .product-meta .quickview-icon .button, {{WRAPPER}} .meafe-product-tab-wrapper.layout-2 .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-media .product-meta .quickview-icon .yith-wcqv-button .button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PCT_wishlist_icon_color_initial',
            [
                'label' => esc_html__('Wishlist Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .product-meta .yith-wcwl-add-to-wishlist .yith-wcwl-add-button .add_to_wishlist::before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PCT_wishlist_icon_bg_color_initial',
            [
                'label' => esc_html__('Wishlist Icon Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f52c2c',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .product-meta .yith-wcwl-add-to-wishlist .yith-wcwl-add-button .add_to_wishlist' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'PCT_cart_icon_hover',
            [
                'label' => __('Hover', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PCT_cart_icon_hover_color',
            [
                'label' => esc_html__('Cart Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .product-meta .add-to-cart .add_to_cart_button:hover::before' => 'background-color:{{VALUE}}',
                ],
                'condition' => [
                    'PCT_layouts' => '1',
                ],
            ]
        );

        $this->add_control(
            'PCT_cart_icon_hover_bg_color',
            [
                'label' => esc_html__('Cart Icon Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .product-meta .add-to-cart .button:hover' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'PCT_layouts' => '1',
                ],
            ]
        );

        $this->add_control(
            'PCT_quickview_icon_hover_color',
            [
                'label' => esc_html__('Quick View Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}  .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner  .product-meta .quickview-icon .yith-wcqv-button:hover::before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PCT_quickview_icon_hover_bg_color',
            [
                'label' => esc_html__('Quick View Icon Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}  .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner  .product-meta .quickview-icon .button:hover ' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PCT_wishlist_icon_hover_color',
            [
                'label' => esc_html__('Wishlist Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .product-meta .yith-wcwl-add-to-wishlist .yith-wcwl-add-button .add_to_wishlist:hover::before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PCT_wishlist_icon_hover_bg_color',
            [
                'label' => esc_html__('Wishlist Icon Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .product-meta .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Product Category Tab Section Content Style
         */
        $this->start_controls_section(
            'meafe_PCT_cat_filter_style',
            [
                'label' => __('Category Filter', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'PCT_cat_filter_align',
            [
                'label' => __('Alignment', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'mega-elements-addons-for-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'mega-elements-addons-for-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'mega-elements-addons-for-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => true,
                'prefix_class' => 'meafe-product-tab-filter-align-',
            ]
        );

        $this->start_controls_tabs('PCT_cat_filter_color');

        $this->start_controls_tab(
            'PCT_cat_filter_initial',
            [
                'label' => __('Initial', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PCT_cat_filter_color_initial',
            [
                'label' => esc_html__('Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .post-filter-tab-wrapper ul.meafe-nav-tabs li a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PCT_cat_filter_bg_color_initial',
            [
                'label' => esc_html__('Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#547C0E',
                'selectors' => [
                    '{{WRAPPER}} .post-filter-tab-wrapper ul.meafe-nav-tabs li a::before, {{WRAPPER}} .meafe-product-tab-wrapper.layout-2 .meafe-nav-tabs li:hover a, {{WRAPPER}} .meafe-product-tab-wrapper.layout-2 ul.meafe-nav-tabs li.active a, {{WRAPPER}} .meafe-product-tab-wrapper.layout-3 .meafe-nav-tabs li:hover a' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'PCT_layouts' => '2',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'PCT_cat_filter_hover',
            [
                'label' => __('Hover', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PCT_cat_filter_hover_color',
            [
                'label' => esc_html__('Hover Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .post-filter-tab-wrapper ul.meafe-nav-tabs li a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PCT_cat_filter_bg_hover_color',
            [
                'label' => esc_html__('Background Hover Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#547C0E',
                'selectors' => [
                    '{{WRAPPER}} .post-filter-tab-wrapper ul.meafe-nav-tabs li a:hover::before,{{WRAPPER}} .meafe-product-tab-wrapper.layout-2 .meafe-nav-tabs li:hover a, {{WRAPPER}} .meafe-product-tab-wrapper.layout-2 ul.meafe-nav-tabs li.active a:hover
                    , {{WRAPPER}} .meafe-product-tab-wrapper.layout-3 .meafe-nav-tabs li:hover a' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'PCT_layouts' => '2',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PCT_cat_filter_typography',
                'label' => __('Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .post-filter-tab-wrapper ul.meafe-nav-tabs li a',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'PCT_cat_filter_border',
                'selector' => '{{WRAPPER}} .category--main .button, {{WRAPPER}} .meafe-product-tab-wrapper.layout-2 .meafe-nav-tabs ',
                'condition' => [
                    'PCT_layouts' => '2'
                ],
            ]
        );

        $this->add_control(
            'PCT_cat_filter_border_radius_normal',
            [
                'label' => esc_html__('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'condition' => [
                    'PCT_layouts' => '2'
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper.layout-2 .meafe-nav-tabs' => 'border-radius: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->end_controls_section();

        /**
         * Product Category Tab Section Content Style
         */
        $this->start_controls_section(
            'meafe_PCT_cart_style',
            [
                'label' => __('Cart Button', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'PCT_layouts!' => '1',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PCT_cart_typography',
                'label' => __('Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe-product-tab-wrapper.layout-2 .meafe-products-wrapper .meafe-products .meafe-products-inner:hover .meafe-entry-media .product-meta .add-to-cart ,{{WRAPPER}} .meafe-product-tab-wrapper.layout-3 .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .add-to-cart ',
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 20]],
                    'line_height' => ['default' => ['unit' => 'em', 'size' => 1]]
                ]
            ]
        );

        $this->start_controls_tabs('PCT_cart_color');

        $this->start_controls_tab(
            'PCT_cart_btn_color',
            [
                'label' => __('Initial', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PCT_cart_btn_color_initial',
            [
                'label' => esc_html__('Button Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-media .product-meta .add-to-cart .button, {{WRAPPER}} .meafe-product-tab-wrapper.layout-3 .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .add-to-cart .button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PCT_cart_bg_color_initial',
            [
                'label' => esc_html__('Button Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-media .product-meta .add-to-cart .button, {{WRAPPER}} .meafe-product-tab-wrapper.layout-3 .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .add-to-cart .button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'PCT_cart_btn_color_hover',
            [
                'label' => __('Hover', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PCT_cart_hover_color',
            [
                'label' => esc_html__('Button Hover Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-media .product-meta .add-to-cart:hover .button, {{WRAPPER}} .meafe-product-tab-wrapper.layout-3 .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .add-to-cart:hover .button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PCT_cart_bg_hover_color',
            [
                'label' => esc_html__('Button Background Hover Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-media .product-meta .add-to-cart:hover .button, {{WRAPPER}} .meafe-product-tab-wrapper.layout-3 .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .add-to-cart:hover .button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'PCT_button_border_normal',
                'selector' => '{{WRAPPER}} .category--main .button, {{WRAPPER}} .meafe-product-tab-wrapper.layout-2 .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-media .product-meta .add-to-cart .button',
            ]
        );

        $this->add_control(
            'PCT_button_border_radius_normal',
            [
                'label' => esc_html__('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-tab-wrapper.layout-3 .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .add-to-cart .button, {{WRAPPER}} .meafe-product-tab-wrapper.layout-2 .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-media .product-meta .add-to-cart .button' => 'border-radius: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'PCT_button_box_shadow_normal',
                'label' => esc_html__('Button Shadow', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .category--main .button',
            ]
        );

        $this->end_controls_section();

        /**
         * Product Category Tab Section Content Style
         */
        $this->start_controls_section(
            'meafe_PCT_carousel_style',
            [
                'label' => __('Carousel', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('PCT_carousel_color');

        $this->start_controls_tab(
            'PCT_carousel_color_initial',
            [
                'label' => __('Initial', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PCT_carousel_icon_color_initial',
            [
                'label' => __('Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-products-wrapper .meafa-navigation-wrap .nav i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'PCT_carousel_bg_color_initial',
            [
                'label' => __('Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4e7302',
                'selectors' => [
                    '{{WRAPPER}} .meafe-products-wrapper .meafa-navigation-wrap .nav' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'PCT_carousel_color_hover',
            [
                'label' => __('Hover', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PCT_carousel_icon_hover_color',
            [
                'label' => __('Icon Hover Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-products-wrapper .meafa-navigation-wrap .nav:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'PCT_carousel_bg_hover_color',
            [
                'label' => __('Background Hover Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4e7302',
                'selectors' => [
                    '{{WRAPPER}} .meafe-products-wrapper .meafa-navigation-wrap .nav:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->start_controls_tabs('PCT_carousel_dots_color');

        $this->start_controls_tab(
            'PCT_carousel_dots_normal',
            [
                'label' => __('Normal', 'mega-elements-addons-for-elementor')
            ]
        );

        $this->add_control(
            'PCT_carousel_dots_color_normal',
            [
                'label' => __('Carousel Dots Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4e7302',
                'selectors' => [
                    '{{WRAPPER}} .meafe-products-wrapper .meafa-swiper-pagination .swiper-pagination-bullet' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'PCT_carousel_dots_active',
            [
                'label' => __('Active', 'mega-elements-addons-for-elementor')
            ]
        );

        $this->add_control(
            'PCT_carousel_dots_color_active',
            [
                'label' => __('Carousel Dots Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4e7302',
                'selectors' => [
                    '{{WRAPPER}} .meafe-products-wrapper .meafa-swiper-pagination .swiper-pagination-bullet-active' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    public function get_query_args($settings = [])
    {
        $settings = wp_parse_args($settings, [
            'post_type' => 'product',
            'posts_ids' => [],
            'orderby' => 'date',
            'order' => 'desc',
            'posts_per_page' => 4,
            'offset' => 0,
            'post__not_in' => [],
        ]);

        $args = [
            'post_type' => 'product',
            'orderby' => 'date',
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish',
            'posts_per_page' => $settings['PCT_number'],
        ];

        if ($settings['PCT_type'] == 'sales') {
            $args['meta_query'] = WC()->query->get_meta_query();
            $args['post__in'] = wc_get_product_ids_on_sale();
        } elseif ($settings['PCT_type'] == 'popular') {
            $args['meta_key'] = 'total_sales';
            $args['order_by'] = 'meta_value_num';
        };

        $args['tax_query'] = [];

        if (!empty($settings['PCT_cat_select'])) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $settings['PCT_cat_select'],
                'operator' => 'IN'
            ];
        }

        if (!empty($args['tax_query'])) {
            $args['tax_query']['relation'] = 'AND';
        }

        return $args;
    }

    public function get_nav_details(){
        $settings    = $this->get_settings_for_display();
        $ed_carousel = $settings['PCT_ed_carousel'];
        $nav_prev    = $settings['PCT_prev_icon'];
        $nav_next    = $settings['PCT_next_icon'];

        if( $ed_carousel === 'yes' ) {
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

    public static function render_product_template($args, $settings, $nav_icons)
    {

        $cat_query = new \WP_Query($args);
        $swiper_enabled = '';
        if($settings['PCT_ed_carousel'] == 'yes'){
            $swiper_enabled = ' swiper-enabled';
        }

        ob_start();

        $cats = $settings['PCT_cat_select'];
        echo '</pre>';

        if ($cats && $settings['PCT_ed_filter'] && meafe_is_woocommerce_activated() ) {
            echo '<div class="post-filter-tab-wrapper"><ul class="meafe-nav-tabs"><li class="active" data-tab=""><a href="javascript:void(0)">' . esc_html__('All', 'mega-elements-addons-for-elementor') . '</a></li>';
            foreach ($cats as $key => $cat) {
                $cat_data = get_term_by('id', $cat, 'product_cat');
                if( isset( $cat_data->name ) && isset( $cat ) ) echo '<li data-tab="cat-id-' . esc_attr($cat) . '"><a href="javascript:void(0)">' . esc_html ($cat_data->name) . '</a></li>';
            }
            echo '</ul></div>';
        }

        $swiper_class = ($settings['PCT_ed_carousel'] == 'yes') ? ' swiper-slide' : '';

        if ($cat_query->have_posts() && meafe_is_woocommerce_activated() ) {
            echo '<div class="meafe-products-wrapper"><div class="meafe-products' . esc_attr($swiper_enabled) . ' ">';
                if($settings['PCT_ed_carousel'] === 'yes') echo '<div class="swiper-container"><div class="swiper-wrapper">';     
                    while ($cat_query->have_posts()) {
                        $cat_query->the_post();
                        echo '<div class="meafe-products-inner' . esc_attr($swiper_class) .'">';
                        if (has_post_thumbnail()) {
                            echo '<figure class="meafe-entry-media image-wrapper">';
                            if ($settings['PCT_ed_badge']) woocommerce_show_product_sale_flash();
                            if ($settings['PCT_ed_cart'] || (is_yith_quickview_activated() && $settings['PCT_ed_quick_view']) || (is_yith_whislist_activated() && $settings['PCT_ed_wishlist'])) {
                                echo '<div class="product-meta">';
                                if ($settings['PCT_ed_cart'] && $settings['PCT_layouts'] != 3) {
                                    echo '<span class="add-to-cart">';
                                    woocommerce_template_loop_add_to_cart();
                                    echo '</span>';
                                }
                                if ($settings['PCT_layouts'] == 2 && ((is_yith_quickview_activated() && $settings['PCT_ed_quick_view']) || (is_yith_whislist_activated() && $settings['PCT_ed_wishlist']))) echo '<div class="product-icon-wrapper">';
                                if (is_yith_quickview_activated() && $settings['PCT_ed_quick_view']) {
                                    echo '<span class="quickview-icon">';
                                    echo do_shortcode('[yith_quick_view]');
                                    echo '</span>';
                                }
                                if (is_yith_whislist_activated() && $settings['PCT_ed_wishlist']) {
                                    echo do_shortcode('[yith_wcwl_add_to_wishlist]');
                                }
                                if ($settings['PCT_layouts'] == 2 && ((is_yith_quickview_activated() && $settings['PCT_ed_quick_view']) || (is_yith_whislist_activated() && $settings['PCT_ed_wishlist']))) echo '</div>';
                                echo '</div>';
                            }
                            echo '<a class="meafe-grid-post-link" href="' . esc_url(get_the_permalink()) . '" title="' . esc_html(get_the_title()) . '">
                                    <img src="' . esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), 'meafe-category-tab')) . '" alt="' . esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)) . '">
                                </a>';
                            echo '</figure>';
                        }
                        echo '<div class="meafe-entry-wrapper category--main ' . esc_attr($settings['PCT_align']) . '">';
                        if ('product' === get_post_type() && $settings['PCT_ed_cat']) {
                            $categories_list = get_the_terms(get_the_ID(), 'product_cat');
                            if ($categories_list) {
                                foreach ($categories_list as $product_cat) {
                                    echo '<span class="category--wrapper" itemprop="about">' . esc_html($product_cat->name) . '</span>';
                                }
                            }
                        }

                        if ($settings['PCT_ed_title']) {
                            echo '<h2 class="meafe-entry-title"><a class="meafe-grid-post-link" href="' . esc_url(get_the_permalink()) . '" title="' . esc_html(get_the_title()) . '">' . esc_html(get_the_title()) . '</a></h2>';
                        }
                        if ($settings['PCT_layouts'] == 3 && $settings['PCT_ed_excerpt']) {
                            echo '<div class="meafe-entry-content meafe-content">
                                    <p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()), $settings['PCT_excerpt_number']) . '</p>';
                            echo '</div>';
                        }
                        $stock = get_post_meta(get_the_ID(), '_stock_status', true);
                        if ($settings['PCT_ed_price'] || $stock == 'outofstock') {
                            echo '<div class="product-footer">';
                            if ($settings['PCT_ed_price']) woocommerce_template_single_price(); //price
                            if ($stock == 'outofstock') {
                                echo '<span class="outofstock">' . esc_html__('Sold Out', 'mega-elements-addons-for-elementor') . '</span>';
                            }
                            echo '</div>';
                        }
                        if ($settings['PCT_ed_cart'] && $settings['PCT_layouts'] == 3) {
                            echo '<span class="add-to-cart">';
                            woocommerce_template_loop_add_to_cart();
                            echo '</span>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                if($settings['PCT_ed_carousel'] === 'yes') echo '</div></div>';
                
                if($settings['PCT_ed_carousel'] === 'yes' && $settings['PCT_carousel_dots'] == 'yes') { ?>
                    <!-- If we need pagination -->
                    <?php if( ($settings['PCT_number'] > 3 && wp_is_mobile()) || ($settings['PCT_number'] > 4 && !wp_is_mobile())) { ?>
                        <div class="prod-tab meafa-swiper-pagination"></div>
                    <?php }
                }

                if($settings['PCT_ed_carousel'] === 'yes') { ?>
                    <!-- If we need navigation buttons -->
                    <?php if( ($settings['PCT_number'] > 3 && wp_is_mobile()) || ($settings['PCT_number'] > 4 && !wp_is_mobile())) { ?>
                        <div class="meafa-navigation-wrap">
                            <div class="prod-tab meafa-navigation-prev nav">
                                <?php echo $nav_icons[0]; ?>
                            </div>
                            <div class="prod-tab meafa-navigation-next nav">
                                <?php echo $nav_icons[1]; ?>
                            </div>
                        </div>
                    <?php } 
                }
            echo '</div></div>';
        } else {
            echo '<p class="no-posts-found">' . esc_html__('No Products found!', 'mega-elements-addons-for-elementor') . '</p>';
        }

        wp_reset_postdata();

        return ob_get_clean();
    }

    protected function render()
    {   
        $settings = $this->get_settings();
        $args = $this->get_query_args($settings);
        $nav_icons   = $this->get_nav_details();

        $settings_arry = [
            'PCT_ed_title'       => $settings['PCT_ed_title'],
            'PCT_ed_excerpt'     => $settings['PCT_ed_excerpt'],
            'PCT_ed_cat'         => $settings['PCT_ed_cat'],
            'PCT_excerpt_number' => intval($settings['PCT_excerpt_number'], 20),
            'PCT_layouts'        => $settings['PCT_layouts'],
            'PCT_cat_select'     => $settings['PCT_cat_select'],
            'PCT_ed_badge'       => $settings['PCT_ed_badge'],
            'PCT_ed_cart'        => $settings['PCT_ed_cart'],
            'PCT_ed_quick_view'  => $settings['PCT_ed_quick_view'],
            'PCT_ed_wishlist'    => $settings['PCT_ed_wishlist'],
            'PCT_ed_price'       => $settings['PCT_ed_price'],
            'PCT_ed_filter'      => $settings['PCT_ed_filter'],
            'PCT_type'           => $settings['PCT_type'],
            'PCT_ed_carousel'    => $settings['PCT_ed_carousel'],
            'PCT_align'          => $settings['PCT_align'],
            'PCT_carousel_dots'  => $settings['PCT_carousel_dots'],
            'PCT_number'         => $settings['PCT_number'],
        ];

        $this->add_render_attribute(
            'product_wrapper',
            [
                'id' => 'meafe-post-grid-' . esc_attr($this->get_id()),
                'class' => [
                    'meafe-product-tab-wrapper layout-' . esc_attr($settings['PCT_layouts']),
                ],
                'data-post-no' => esc_attr($settings['PCT_number'])
            ]
        );

        if ($settings['PCT_number'] <= 2) {
            $this->add_render_attribute('product_wrapper', 'class', 'wrapper-alignment-center');
        }

        echo '<div ' . $this->get_render_attribute_string('product_wrapper') . '>
            <div class="meafe-product-innerwrapper">
                ' . self::render_product_template($args, $settings_arry, $nav_icons) . '
            </div>
        </div>';
    }

    protected function content_template()
    {
    }
}