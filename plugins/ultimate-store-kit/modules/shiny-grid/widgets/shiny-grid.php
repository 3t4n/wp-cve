<?php

namespace UltimateStoreKit\Modules\ShinyGrid\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use UltimateStoreKit\Base\Module_Base;
use UltimateStoreKit\Traits\Global_Widget_Template;
use UltimateStoreKit\Includes\Controls\GroupQuery\Group_Control_Query;
use UltimateStoreKit\Traits\Global_Widget_Controls;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

// Exit if accessed directly

class Shiny_Grid extends Module_Base {
    use Global_Widget_Controls;
    use Global_Widget_Template;
    use Group_Control_Query;
    // use Global_Widget_Template;

    /**
     * @var \WP_Query
     */
    private $_query = null;

    public function get_name() {
        return 'usk-shiny-grid';
    }

    public function get_title() {
        return esc_html__('Shiny Grid', 'ultimate-store-kit');
    }

    public function get_icon() {
        return 'usk-widget-icon usk-icon-shiny-grid';
    }

    public function get_categories() {
        return ['ultimate-store-kit'];
    }

    public function get_keywords() {
        return ['product', 'product-grid', 'table', 'wc'];
    }

    // public function get_script_depends() {
    //     return ['usk-shiny-grid'];
    // }

    public function get_style_depends() {
        if ($this->usk_is_edit_mode()) {
            return ['usk-all-styles'];
        } else {
            return ['ultimate-store-kit-font', 'usk-shiny-grid', 'slick-modal'];
        }
    }

    // public function get_custom_help_url() {
    //     return 'https://youtu.be/3VkvEpVaNAM';
    // }

    public function get_query() {
        return $this->_query;
    }
    protected function register_controls() {
        $this->start_controls_section(
            'section_woocommerce_layout',
            [
                'label' => esc_html__('Layout', 'ultimate-store-kit'),
            ]
        );

        $this->add_control(
            'layout_style',
            [
                'label'   => esc_html__('Style', 'ultimate-store-kit'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => esc_html__('Grid', 'ultimate-store-kit'),
                    'list' => esc_html__('List', 'ultimate-store-kit'),
                ],
            ]
        );
        $this->add_responsive_control(
            'columns',
            [
                'label'          => esc_html__('Columns', 'ultimate-store-kit'),
                'type'           => Controls_Manager::SELECT,
                'default'        => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options'        => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'condition' => [
                    'layout_style' => 'grid'
                ],
                'selectors' => [
                    ' .usk-florence-grid .usk-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',

                ],
                'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'items_columns_gap',
            [
                'label'     => esc_html__('Columns Gap', 'ultimate-wook'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid' => 'grid-column-gap: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_row_gap',
            [
                'label'     => esc_html__('Row Gap', 'ultimate-wook'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid' => 'grid-row-gap: {{SIZE}}px;',
                ],
            ]
        );

        $this->register_global_controls_grid_layout();

        $this->add_control(
            'show_tab',
            [
                'label' => esc_html__('Columns Filter', 'ultimate-store-kit'),
                'type'  => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'filter_column_lists',
            [
                'label'           => __('Select Column Type', 'ultimate-store-kit'),
                'type'            => Controls_Manager::SELECT2,
                'label_block'     => true,
                'multiple'        => true,
                'options'         => [
                    'list-2'        => __('List', 'ultimate-store-kit'),
                    'grid-2'        => __('Column 2', 'ultimate-store-kit'),
                    'grid-3'        => __('Column 3', 'ultimate-store-kit'),
                    'grid-4'        => __('Column 4', 'ultimate-store-kit'),
                    'grid-5'        => __('Column 5', 'ultimate-store-kit'),
                    'grid-6'        => __('Column 6', 'ultimate-store-kit'),

                ],
                'condition' => [
                    'show_tab' => 'yes'
                ],
                'default'         => ['list-2', 'grid-2', 'grid-3', 'grid-4'],
            ]
        );
        $this->add_control(
            'show_result_count',
            [
                'label'         => esc_html__('Result Count', 'ultimate-store-kit'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__('Show', 'ultimate-store-kit'),
                'label_off'     => esc_html__('Hide', 'ultimate-store-kit'),
                'return_value'  => 'yes',
                'default'       => 'yes',
                'separator'    => 'before',
                'condition' => [
                    'show_tab' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'show_pagination',
            [
                'label'     => esc_html__('Pagination', 'ultimate-store-kit'),
                'type'      => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_post_query_builder',
            [
                'label' => __('Query', 'ultimate-store-kit'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->register_query_builder_controls();
        $this->register_controls_wc_additional();
        $this->end_controls_section();
        $this->register_global_controls_additional();
        $this->register_global_controls_grid_columns();
        $this->register_global_controls_result_count();
        $this->register_global_controls_grid_items();
        $this->register_global_controls_grid_image();
        $this->register_global_controls_content();
        $this->register_global_controls_title();
        $this->register_global_controls_category();
        $this->register_global_controls_excerpt();
        $this->register_global_controls_price();
        $this->register_global_controls_rating();
        $this->start_controls_section(
            'section_style_button',
            [
                'label'     => esc_html__('Button', 'ultimate-store-kit'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_cart' => 'yes',
                ],
            ]
        );


        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__('Normal', 'ultimate-store-kit'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__('Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .usk-button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .added_to_cart' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-button.loading::after' => 'border-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'btn_background_color',
                'label'     => esc_html__('Background', 'ultimate-store-kit'),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .usk-shiny-grid .usk-item-box .usk-image .usk-button, {{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .added_to_cart',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__('Hover', 'ultimate-store-kit'),
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label'     => esc_html__('Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .usk-button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .added_to_cart:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'btn_hover_bg',
                'label'     => esc_html__('Background', 'ultimate-store-kit'),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .usk-shiny-grid .usk-item-box .usk-image .usk-button:hover, {{WRAPPER}} .usk-shiny-grid .usk-item-box .usk-image .added_to_cart:hover',
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label'     => esc_html__('Border Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'btn_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .usk-button:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .added_to_cart:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
            'button_width',
            [
                'label'         => esc_html__('Width', 'ultimate-store-kit'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .usk-shiny-grid' => '--btn-width: {{SIZE}}%;',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'button_margin_spacing',
            [
                'label'         => esc_html__('Bottom Spacing', 'ultimate-store-kit'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 200,
                        'step'  => 1,
                    ]
                ],
                'default'       => [
                    'unit'      => 'px',
                    'size'      => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item:hover .usk-item-box .usk-image .usk-button' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item:hover .usk-item-box .usk-image .added_to_cart' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'btn_border',
                'label'     => esc_html__('Border', 'ultimate-store-kit'),
                'selector'  =>
                '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .usk-button, {{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .added_to_cart',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label'      => esc_html__('Border Radius', 'ultimate-store-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .usk-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_shadow',
                'selector' =>
                '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .usk-button, {{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .added_to_cart',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'button_typography',
                'label'     => esc_html__('Typography', 'ultimate-store-kit'),
                'selector'  => '{{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .usk-button, {{WRAPPER}} .usk-shiny-grid .usk-grid .usk-item .usk-item-box .usk-image .added_to_cart',
                'exclude' => ['line_height', 'letter_spacing'],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
        $this->register_global_controls_badge();
        $this->register_global_controls_action_btn();
        $this->register_global_controls_grid_pagination();
    }
    public function render_header() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('usk-shiny-grid', 'class', ['usk-shiny-grid', 'usk-content-position-' . $settings['alignment'] . ''], true);
        $this->add_render_attribute('usk-shiny-grid', 'data-filter', [$settings['show_tab']]);

?>
        <div class="ultimate-store-kit">
            <div <?php $this->print_render_attribute_string('usk-shiny-grid'); ?>>
                <?php $this->template_grid_columns(); ?>
            <?php
        }
        public function render_footer() { ?>
            </div>
        </div>
        <?php
        }
        public function render_add_to_cart() {
            global $product;
            $settings = $this->get_settings_for_display();
            if ('yes' == $settings['show_cart']) : ?>
            <?php if ($product) {
                    $defaults = [
                        'quantity'   => 1,
                        'class'      => implode(
                            ' ',
                            array_filter(
                                [
                                    'usk-button',
                                    'product_type_' . $product->get_type(),
                                    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                    $product->supports('ajax_add_to_cart') && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
                                ]
                            )
                        ),
                        'attributes' => [
                            'data-product_id'  => $product->get_id(),
                            'data-product_sku' => $product->get_sku(),
                            'aria-label'       => $product->add_to_cart_description(),
                            'rel'              => 'nofollow',
                        ],
                    ];
                    $args = apply_filters('woocommerce_loop_add_to_cart_args', wp_parse_args($defaults), $product);
                    if (isset($args['attributes']['aria-label'])) {
                        $args['attributes']['aria-label'] = wp_strip_all_tags($args['attributes']['aria-label']);
                    }
                    echo apply_filters(
                        'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
                        sprintf(
                            '<a href="%s" data-quantity="%s" class="%s" %s>%s <i class="button-icon eicon-arrow-right"></i></a>',
                            esc_url($product->add_to_cart_url()),
                            esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
                            esc_attr(isset($args['class']) ? $args['class'] : 'button'),
                            isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
                            esc_html($product->add_to_cart_text())
                        ),
                        $product,
                        $args
                    );
                }; ?>
        <?php endif;
        }
        public function render_image() {
            global $product;
            $tooltip_position = 'left';
            $settings = $this->get_settings_for_display();
            $gallery_thumbs = $product->get_gallery_image_ids();
            $product_image = wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size']);
            if ($gallery_thumbs) {
                foreach ($gallery_thumbs as $key => $gallery_thumb) {
                    if ($key == 0) :
                        $gallery_image_link = wp_get_attachment_image_url($gallery_thumb, $settings['image_size']);
                    endif;
                }
            } else {
                $gallery_image_link = wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size']);
            }
        ?>
        <div class="usk-image">
            <a href="<?php echo get_permalink(); ?>">
                <img class="img image-default" src="<?php echo esc_url($product_image); ?>" alt="<?php echo get_the_title(); ?>">
                <img class="img image-hover" src="<?php echo esc_url($gallery_image_link); ?>" alt="<?php echo get_the_title(); ?>">
            </a>
            <?php $this->render_add_to_cart(); ?>
            <div class="usk-shoping">
                <?php $this->register_global_template_add_to_wishlist($tooltip_position); ?>
                <?php $this->register_global_template_add_to_compare($tooltip_position); ?>
                <?php $this->register_global_template_quick_view($product->get_id(), $tooltip_position); ?>
            </div>
            <div class="usk-badge-label-wrapper">
                <div class="usk-badge-label-content">
                    <?php $this->register_global_template_badge_label(); ?>
                </div>
            </div>
        </div>
        <?php
        }
        public function print_price_output($output) {
            $tags = [
                'del' => ['aria-hidden' => []],
                'span'  => ['class' => []],
                'bdi' => [],
                'ins' => [],
            ];

            if (isset($output)) {
                echo wp_kses($output, $tags);
            }
        }
        public function render_loop_item() {
            $settings = $this->get_settings_for_display();
            $this->query_product();
            $wp_query = $this->get_query();


            if ($settings['layout_style'] === 'grid') {
                $this->add_render_attribute('usk-grid', 'class', ['usk-grid', 'usk-grid-layout', 'usk-grid-' . $settings['columns'] . '']);
            } else {
                $this->add_render_attribute('usk-grid', 'class', ['usk-grid', 'usk-list-layout', 'usk-grid-2']);
            }

            if ($wp_query->have_posts()) { ?>
            <div <?php $this->print_render_attribute_string('usk-grid'); ?>">
                <?php while ($wp_query->have_posts()) : $wp_query->the_post();
                    global $product;
                    $rating_count = $product->get_rating_count();
                    $average      = $product->get_average_rating();
                    if ($settings['show_rating'] == 'yes') {
                        $have_rating = 'usk-have-rating';
                    } else {
                        $have_rating = '';
                    }
                ?>
                    <div class="usk-item <?php esc_attr_e($have_rating, 'utlimate-woo-kit'); ?>">
                        <div class="usk-item-box">
                            <?php $this->render_image(); ?>
                            <div class="usk-content">
                                <div class="usk-inner-content">
                                    <?php if ('yes' == $settings['show_category']) : ?>
                                        <?php printf('<%1$s class="usk-category">%2$s</%1$s>', $settings['category_tags'], wc_get_product_category_list($product->get_id())); ?>
                                    <?php endif; ?>
                                    <?php if ('yes' == $settings['show_title']) :
                                        printf('<a href="%2$s" class="usk-title"><%1$s  class="title">%3$s</%1$s></a>', $settings['title_tags'], $product->get_permalink(), $product->get_title());
                                    endif; ?>
                                    <div class="usk-desc">
                                        <span class="desc"><?php echo wp_trim_words($product->get_short_description(), $settings['excerpt_limit'], '…'); ?></span>
                                    </div>
                                    <?php if (('yes' == $settings['show_price'])) : ?>
                                        <div class="usk-price">
                                            <?php $this->print_price_output($product->get_price_html()); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ('yes' == $settings['show_rating']) : ?>
                                        <div class="usk-rating">
                                            <span><?php echo $this->register_global_template_wc_rating($average, $rating_count); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php if ($settings['show_pagination']) :
                    ultimate_store_kit_post_pagination($wp_query);
                endif;
                wp_reset_postdata();
            } else { ?>
            <div class="usk-warning">
                <span><?php echo esc_html__('Ops! There no product to display', 'ultimate-store-kit'); ?></span>
            </div>
        <?php
            }
        }
        public function render() {
            $this->render_header();
            $this->render_loop_item();
            $this->render_footer();
        }

        public function query_product() {
            $default = $this->getGroupControlQueryArgs();
            $this->_query = new WP_Query($default);
        }
        protected function template_grid_columns() {
            $settings = $this->get_settings_for_display();
            $this->query_product();
            $wp_query = $this->get_query();
            if (get_query_var('paged')) {
                $paged = get_query_var('paged');
            } elseif (get_query_var('page')) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
            $args = array(
                'total'    => $wp_query->found_posts,
                'per_page' => $settings['product_limit'],
                'current'  => $paged,
            );
            if ($settings['show_tab'] == 'yes') : ?>
            <div class="usk-grid-header usk-visible@l">
                <?php if (($settings['show_result_count'] == 'yes')) :
                    wc_get_template('loop/result-count.php', $args);
                endif;
                ?>
                <ul class="usk-grid-header-tabs">
                    <?php if (in_array("list-2", $settings['filter_column_lists'])) : ?>
                        <li class="usk-grid-tabs-list">
                            <a class="tab-option" href="javascript:void(0)" data-grid-column="usk-list-2">
                                <span class="usk-icon-grid-list"></span>
                            </a>
                        </li>
                    <?php
                    endif;
                    if (in_array('grid-2', $settings['filter_column_lists'])) :
                    ?>
                        <li class="usk-grid-tabs-list">
                            <a class="tab-option" href="javascript:void(0)" data-grid-column="usk-grid-2">
                                <span class="usk-icon-grid-2"></span>
                            </a>
                        </li>
                    <?php
                    endif;
                    if (in_array('grid-3', $settings['filter_column_lists'])) :
                    ?>
                        <li class="usk-grid-tabs-list">
                            <a class="tab-option" href="javascript:void(0)" data-grid-column="usk-grid-3">
                                <span class="usk-icon-grid-3"></span>
                            </a>
                        </li>
                    <?php
                    endif;
                    if (in_array('grid-4', $settings['filter_column_lists'])) :
                    ?>
                        <li class="usk-grid-tabs-list">
                            <a class="tab-option" href="javascript:void(0)" data-grid-column="usk-grid-4">
                                <span class="usk-icon-grid-4"></span>
                            </a>
                        </li>
                    <?php
                    endif;
                    if (in_array('grid-5', $settings['filter_column_lists'])) :
                    ?>
                        <li class="usk-grid-tabs-list">
                            <a class="tab-option" href="javascript:void(0)" data-grid-column="usk-grid-5">
                                <span class="usk-icon-grid-5"></span>
                            </a>
                        </li>
                    <?php
                    endif;
                    if (in_array('grid-6', $settings['filter_column_lists'])) :
                    ?>
                        <li class="usk-grid-tabs-list">
                            <a class="tab-option" href="javascript:void(0)" data-grid-column="usk-grid-6">
                                <span class="usk-icon-grid-6"></span>
                            </a>
                        </li>
                    <?php
                    endif;
                    ?>
                </ul>
            </div>
<?php endif;
        }
    }
