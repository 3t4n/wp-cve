<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class MEAFE_Product_Cat_Grid extends Widget_Base
{
    public function get_name()
    {
        return 'meafe-product-cat-grid';
    }

    public function get_title()
    {
        return esc_html__('Product Category Grid', 'mega-elements-addons-for-elementor');
    }

    public function get_icon()
    {
        return 'meafe-product-cat-grid';
    }

    public function get_categories()
    {
        return ['meafe-elements'];
    }

    public function get_style_depends()
    {
        return ['meafe-product-cat-grid'];
    }

    public function get_script_depends()
    {
        return ['meafe-product-cat-grid'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'meafe_PCG_content_general_settings',
            array(
                'label' => __('General Settings', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'PCG_layouts',
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
            ]
        );

        $this->add_control(
            'PCG_cat_select',
            [
                'label' => esc_html__('Select product categories', 'mega-elements-addons-for-elementor'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => [],
                'options' => wp_list_pluck(get_terms('product_cat'), 'name', 'term_id'),
            ]
        );

        $this->add_control(
            'PCG_ed_cat',
            [
                'label' => esc_html__('Show Category', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'PCG_ed_prod_count',
            [
                'label' => esc_html__('Show Product Counts', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'PCG_ed_button',
            [
                'label' => esc_html__('Show Button', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'PCG_layouts' => '1'
                ]
            ]
        );

        $this->add_control(
            'PCG_button_text',
            [
                'label' => esc_html__('Text', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Shop Now', 'mega-elements-addons-for-elementor'),
                'label_block' => true,
                'condition' => [
                    'PCG_ed_button' => 'yes',
                    'PCG_layouts' => '1'
                ]
            ]
        );

        $this->add_control(
            'PCG_button_icon_switcher',
            [
                'label' => esc_html__('Icon', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Enable or disable button icon', 'mega-elements-addons-for-elementor'),
                'separator' => 'before',
                'default' => 'yes',
                'condition' => [
                    'PCG_layouts' => '1'
                ]
            ]
        );

        $this->add_control(
            'PCG_button_icon_selection_updated',
            [
                'label' => esc_html__('Icon', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'PCG_button_icon_selection',
                'default' => [
                    'value' => 'fas fa-arrow-circle-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'PCG_button_icon_switcher' => 'yes',
                    'PCG_layouts' => '1'
                ],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        /**
         *  Product Category Tab Section General Style
         */
        $this->start_controls_section(
            'meafe_PCG_general_style',
            [
                'label' => esc_html__('General Style', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'PCG_align',
            [
                'label' => __('Alignment', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
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
                'prefix_class' => 'meafe-product-cat-grid-content-align-'
            ]
        );

        $this->add_control(
            'PCG_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper .meafe-prod-cat-grid-innerwrapper  .meafe-cat-grid-wrapper .meafe-cat-grid-item .meafe-entry-media img,{{WRAPPER}} .meafe-product-cat-grid-wrapper .meafe-prod-cat-grid-innerwrapper  .meafe-cat-grid-wrapper .meafe-cat-grid-item .meafe-entry-media svg,{{WRAPPER}} .meafe-product-cat-grid-wrapper .meafe-prod-cat-grid-innerwrapper .meafe-cat-grid-wrapper .meafe-cat-grid-item .product-cat-meta, {{WRAPPER}} .meafe-product-cat-grid-wrapper.layout-2 .meafe-cat-grid-wrapper .meafe-cat-grid-item .meafe-entry-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper.layout-2 .meafe-cat-grid-wrapper .meafe-cat-grid-item .product-cat-meta' => 'border-radius: 0 0 0 {{LEFT}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper.layout-1 .meafe-cat-grid-wrapper .meafe-cat-grid-item .product-cat-meta' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',

                ]
            ]
        );

        $this->add_responsive_control(
            'PCG_spacing',
            [
                'label' => esc_html__('Spacing Between Items', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper .meafe-prod-cat-grid-innerwrapper .meafe-cat-grid-wrapper .meafe-cat-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'PCG_bg_color',
            [
                'label' => __('Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper .meafe-prod-cat-grid-innerwrapper .meafe-cat-grid-wrapper' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'PCG_layouts' => '3'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'PCG_box_shadow',
                'selector' => '{{WRAPPER}}',
                'condition' => [
                    'PCG_layouts' => '3'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_PCG_content_style',
            [
                'label' => __('Content', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'PCG_padding',
            [
                'label' => esc_html__('Padding', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'size' => 7,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper.layout-2 .meafe-cat-grid-wrapper .meafe-cat-grid-item .product-cat-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'condition' => [
                    'PCG_layouts' => '2'
                ]
            ]
        );

        $this->add_control(
            'PCG_cat_color',
            [
                'label'     => __('Category Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper .meafe-prod-cat-grid-innerwrapper .meafe-cat-grid-wrapper .meafe-cat-grid-item .product-cat-meta .category-title .cat-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PCG_cat_typography',
                'label' => __('Category Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe-product-cat-grid-wrapper .meafe-prod-cat-grid-innerwrapper .meafe-cat-grid-wrapper .meafe-cat-grid-item .product-cat-meta .category-title .cat-title',
            ]
        );

        $this->add_control(
            'PCG_prod_count_color',
            [
                'label'     => __('Product Count Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper .meafe-prod-cat-grid-innerwrapper .meafe-cat-grid-wrapper .meafe-cat-grid-item .product-cat-meta .category-title .cat-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'PCG_prod_count_typography',
                'label'    => __('Product Count Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe-product-cat-grid-wrapper .meafe-prod-cat-grid-innerwrapper .meafe-cat-grid-wrapper .meafe-cat-grid-item .product-cat-meta .category-title .cat-count',
            ]
        );

        $this->add_control(
            'PCG_bg_overlay_color',
            [
                'label' => __('Background Overlay Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper .meafe-prod-cat-grid-innerwrapper .meafe-cat-grid-wrapper .meafe-cat-grid-item .product-cat-meta' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'PCG_layouts' => '1'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_PCG_button_style',
            [
                'label'     => __('Button', 'mega-elements-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'PCG_layouts' => '1'
                ]
            ]
        );

        $this->start_controls_tabs('PCG_button_colors');

        $this->start_controls_tab(
            'PCG_button_color_initial',
            [
                'label' => __('Initial', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PCG_btn_color_initial',
            [
                'label'     => esc_html__('Button Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper .meafe-prod-cat-grid-innerwrapper .meafe-cat-grid-wrapper .meafe-cat-grid-item .product-cat-meta .product-btn-wrapper' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PCG_icon_color_initial',
            [
                'label'     => esc_html__('Icon Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper .product-cat-meta .product-btn-wrapper i::before, {{WRAPPER}} .meafe-product-cat-grid-wrapper .product-cat-meta .product-btn-wrapper svg' => 'color: {{VALUE}}; fill: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'PCG_button_color_hover',
            [
                'label' => __('Hover', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PCG_btn_color_hover',
            [
                'label' => esc_html__('Button Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper .meafe-prod-cat-grid-innerwrapper .meafe-cat-grid-wrapper .meafe-cat-grid-item .product-cat-meta .product-btn-wrapper .product-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PCG_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-cat-grid-wrapper .product-cat-meta .product-btn-wrapper i::before:hover, {{WRAPPER}} .meafe-product-cat-grid-wrapper .product-cat-meta .product-btn-wrapper svg:hover' => 'color: {{VALUE}}; fill: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PCG_button_typography',
                'label' => __('Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .product-btn-wrapper .product-btn',
            ]
        );

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
            'taxonomy' => 'product_cat',
            'include' => $settings['PCG_cat_select'],
            'orderby' => 'include'
        ];

        return $args;
    }

    public function render_category_grid_template($args, $settings, $migrated, $is_new)
    {
        $cat_query = get_terms($args);
        ob_start();
        if ($cat_query && meafe_is_woocommerce_activated() ) {
            echo '<div class="meafe-cat-grid-wrapper">';
            foreach ($cat_query as $key => $cat) {
                if ($settings['PCG_layouts'] == 1) {
                    $image_size = 'meafe-category-grid';
                } elseif ($settings['PCG_layouts'] == 2) {
                    if ($key == 0) {
                        $image_size = 'meafe-category-grid-one';
                    } elseif ($key == 1 || $key == 2) {
                        $image_size = 'meafe-category-grid-two';
                    } elseif ($key == 3) {
                        $image_size = 'meafe-category-grid-three';
                    }
                } else {
                    $image_size = 'meafe-category-grid-lay-three';
                }
                $image = wp_get_attachment_image(get_term_meta($cat->term_id, 'thumbnail_id', true), $image_size);
                if ($settings['PCG_layouts'] == 2) {
                    if ($key == 0) echo '<div class="meafe-cat-grid-big-wrapper">';
                    if ($key == 1) echo '<div class="meafe-cat-grid-big-wrapper"><div class="meafe-cat-grid-small-wrapper">';
                    if ($key == 3) echo '<div class="meafe-cat-grid-wrap">';
                    if ($key == 4) echo '<div class="meafe-cat-grid-end-wrapper">';
                }
                echo '<div class="meafe-cat-grid-item">';
                    echo '<a href="' . esc_url(get_term_link($cat->term_id, 'product_cat')) . '">';
                        echo '<figure class="meafe-entry-media image-wrapper">';
                            if ( $image ) {
                                echo $image;
                            }else{
                                meafe_get_fallback_svg( $image_size );
                            }
                        echo '</figure>';
                        if( $settings['PCG_layouts'] == 1 || $settings['PCG_ed_cat'] || $settings['PCG_ed_prod_count'] ) {
                            echo '<div class="product-cat-meta">';
                                if ( $cat->name && ( $settings['PCG_ed_cat'] || $settings['PCG_ed_prod_count'] ) ) {
                                    echo '<h3 class="category-title">';
                                    if( $settings['PCG_ed_cat'] ) echo '<span class="cat-title">' . esc_html($cat->name) . '</span>';
                                    if( $settings['PCG_ed_prod_count'] )echo '<span class="cat-count">(' . esc_html($cat->count) . esc_html__(' Products)', 'mega-elements-addons-for-elementor') . '</span>';
                                    echo '</h3>';
                                }
                                if( $settings['PCG_layouts'] == 1 ){
                                    echo '<div class="product-btn-wrapper">';
                                    if( $settings['PCG_ed_button'] )echo '<span class="product-btn">' . esc_html($settings['PCG_button_text']) . '</span>';
                                        if( 'yes' == $settings['PCG_button_icon_switcher'] ) :
                                            echo '<span class="product-btn-icon">';
                                            if ( $is_new || $migrated ) :
                                                Icons_Manager::render_icon( $settings['PCG_button_icon_selection_updated'], [ 'aria-hidden' => 'true' ] );
                                            else: ?>
                                                <i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
                                            </span>
                                            <?php endif;
                                        endif;
                                    echo '</div>';
                                }
                            echo '</div>';
                        }
                    echo '</a>';
                echo '</div>';
                $last_value = array_key_last( $cat_query ) + 1;
                if ($settings['PCG_layouts'] == 2 && $key == 0) echo '</div>';
                if ($settings['PCG_layouts'] == 2 && $key == 2) echo '</div>';
                if ($settings['PCG_layouts'] == 2 && $key == 3) echo '</div></div>';
                if ($settings['PCG_layouts'] == 2 && $key == $last_value ) echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p class="no-posts-found">' . esc_html__('No Categories found!', 'mega-elements-addons-for-elementor') . '</p>';
        }

        wp_reset_postdata();

        return ob_get_clean();
    }

    protected function render()
    {
        {
            $settings = $this->get_settings();
            $args = $this->get_query_args($settings);

            if ( ! empty ( $settings['PCG_button_icon_selection'] ) ) {
                $this->add_render_attribute( 'icon', 'class', $settings['PCG_button_icon_selection'] );
                $this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
            }

            $migrated = isset( $settings['__fa4_migrated']['PCG_button_icon_selection_updated'] );
            $is_new = empty( $settings['PCG_button_icon_selection'] ) && Icons_Manager::is_migration_allowed();

            $settings_arry = [
                'PCG_cat_select'                    => $settings['PCG_cat_select'],
                'PCG_layouts'                       => $settings['PCG_layouts'],
                'PCG_button_text'                   => $settings['PCG_button_text'],
                'PCG_button_icon_switcher'          => $settings['PCG_button_icon_switcher'],
                'PCG_button_icon_selection_updated' => $settings['PCG_button_icon_selection_updated'],
                'PCG_ed_cat'                        => $settings['PCG_ed_cat'],
                'PCG_ed_prod_count'                 => $settings['PCG_ed_prod_count'],
                'PCG_ed_button'                      => $settings['PCG_ed_button']
            ];

            $this->add_render_attribute(
                'product_wrapper',
                [
                    'id' => 'meafe-post-grid-' . esc_attr($this->get_id()),
                    'class' => [
                        'meafe-product-cat-grid-wrapper layout-' . esc_attr($settings['PCG_layouts']),
                    ],
                ]
            );

            echo '<div ' . $this->get_render_attribute_string('product_wrapper') . '>
                <div class="meafe-prod-cat-grid-innerwrapper">
                    ' . self::render_category_grid_template($args, $settings_arry, $migrated, $is_new) . '
                </div>
            </div>';
        }
    }
}