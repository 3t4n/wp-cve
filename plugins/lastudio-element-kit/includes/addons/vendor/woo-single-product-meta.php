<?php

/**
 * Class: LaStudioKit_Woo_Single_Product_Meta
 * Name: Product Meta
 * Slug: lakit-wooproduct-meta
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class LaStudioKit_Woo_Single_Product_Meta extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-woocommerce' );
		    $this->add_script_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_name() {
        return 'lakit-wooproduct-meta';
    }

    public function get_categories() {
        return [ 'lastudiokit-woo-product' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'meta', 'data', 'product' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Meta', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-product-meta';
    }

    protected function register_controls() {
      $this->start_controls_section(
        'section_product',
        [
          'label' => esc_html__( 'Product', 'lastudio-kit' ),
        ]
      );
      $this->add_control(
        'wc_product_warning',
        [
          'type' => Controls_Manager::RAW_HTML,
          'raw' => esc_html__( 'Leave a blank to get the data for current product.', 'lastudio-kit' ),
          'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
        ]
      );
      $this->add_control(
        'product_id',
        [
          'label' =>  esc_html__( 'Product', 'lastudio-kit' ),
          'type' => 'lastudiokit-query',
          'options' => [],
          'label_block' => true,
          'autocomplete' => [
            'object' => 'post',
            'query' => [
              'post_type' => [ 'product' ],
            ],
          ],
        ]
      );
      $this->end_controls_section();
        $this->start_controls_section(
            'section_product_meta_style',
            [
                'label' => esc_html__( 'Style', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wc_style_warning',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'lastudio-kit' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => esc_html__( 'View', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'table' => esc_html__( 'Table', 'lastudio-kit' ),
                    'stacked' => esc_html__( 'Stacked', 'lastudio-kit' ),
                    'inline' => esc_html__( 'Inline', 'lastudio-kit' ),
                ],
                'prefix_class' => 'elementor-woo-meta--view-',
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label' => esc_html__( 'Space Between', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .product_meta .detail-container:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .product_meta .detail-container:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}}.elementor-woo-meta--view-inline .product_meta .detail-container' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}}.elementor-woo-meta--view-inline .product_meta' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
                    'body:not(.rtl) {{WRAPPER}}.elementor-woo-meta--view-inline .detail-container:after' => 'right: calc( (-{{SIZE}}{{UNIT}}/2) + (-{{divider_weight.SIZE}}px/2) )',
                    'body:not.rtl {{WRAPPER}}.elementor-woo-meta--view-inline .detail-container:after' => 'left: calc( (-{{SIZE}}{{UNIT}}/2) - ({{divider_weight.SIZE}}px/2) )',
                ],
            ]
        );

        $this->add_control(
            'divider',
            [
                'label' => esc_html__( 'Divider', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__( 'Off', 'lastudio-kit' ),
                'label_on' => esc_html__( 'On', 'lastudio-kit' ),
                'selectors' => [
                    '{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'content: ""',
                ],
                'return_value' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'divider_style',
            [
                'label' => esc_html__( 'Style', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'solid' => esc_html__( 'Solid', 'lastudio-kit' ),
                    'double' => esc_html__( 'Double', 'lastudio-kit' ),
                    'dotted' => esc_html__( 'Dotted', 'lastudio-kit' ),
                    'dashed' => esc_html__( 'Dashed', 'lastudio-kit' ),
                ],
                'default' => 'solid',
                'condition' => [
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .product_meta .detail-container:not(:last-child):after' => 'border-top-style: {{VALUE}}',
                    '{{WRAPPER}}.elementor-woo-meta--view-inline .product_meta .detail-container:not(:last-child):after' => 'border-left-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'divider_weight',
            [
                'label' => esc_html__( 'Weight', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'condition' => [
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .product_meta .detail-container:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}; margin-bottom: calc(-{{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}}.elementor-woo-meta--view-inline .product_meta .detail-container:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_width',
            [
                'label' => esc_html__( 'Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => '%',
                ],
                'condition' => [
                    'divider' => 'yes',
                    'view!' => 'inline',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'divider_height',
            [
                'label' => esc_html__( 'Height', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'divider' => 'yes',
                    'view' => 'inline',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'divider_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ddd',
                'condition' => [
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'heading_text_style',
            [
                'label' => esc_html__( 'Text', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}}',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'heading_link_style',
            [
                'label' => esc_html__( 'Link', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'link_typography',
                'selector' => '{{WRAPPER}} a',
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .detail-content span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => esc_html__( 'Hover Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_product_meta_captions',
            [
                'label' => esc_html__( 'Captions', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_category_caption',
            [
                'label' => esc_html__( 'Category', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'category_caption_single',
            [
                'label' => esc_html__( 'Singular', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Category', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'category_caption_plural',
            [
                'label' => esc_html__( 'Plural', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Categories', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'heading_tag_caption',
            [
                'label' => esc_html__( 'Tag', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tag_caption_single',
            [
                'label' => esc_html__( 'Singular', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Tag', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'tag_caption_plural',
            [
                'label' => esc_html__( 'Plural', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Tags', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'heading_sku_caption',
            [
                'label' => esc_html__( 'SKU', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'sku_caption',
            [
                'label' => esc_html__( 'SKU', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'SKU', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'sku_missing_caption',
            [
                'label' => esc_html__( 'Missing', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'N/A', 'lastudio-kit' ),
            ]
        );

        $this->end_controls_section();
    }

    private function get_plural_or_single( $single, $plural, $count ) {
        return 1 === $count ? $single : $plural;
    }

    protected function render() {
      $_product_id = $this->get_settings_for_display('product_id');
      $product_id = !empty($_product_id) ? $_product_id : false;

      global $product;
      $product = wc_get_product( $product_id );

        if ( empty( $product ) ) {
            return;
        }

        $this->add_render_attribute('_wrapper', 'data-product_id', $product->get_id());

        $sku = $product->get_sku();

        $settings = $this->get_settings_for_display();
        $sku_caption = ! empty( $settings['sku_caption'] ) ? $settings['sku_caption'] : esc_html__( 'SKU:', 'lastudio-kit' );
        $sku_missing = ! empty( $settings['sku_missing_caption'] ) ? $settings['sku_missing_caption'] : esc_html__( 'N/A', 'lastudio-kit' );
        $category_caption_single = ! empty( $settings['category_caption_single'] ) ? $settings['category_caption_single'] : esc_html__( 'Category:', 'lastudio-kit' );
        $category_caption_plural = ! empty( $settings['category_caption_plural'] ) ? $settings['category_caption_plural'] : esc_html__( 'Categories:', 'lastudio-kit' );
        $tag_caption_single = ! empty( $settings['tag_caption_single'] ) ? $settings['tag_caption_single'] : esc_html__( 'Tag:', 'lastudio-kit' );
        $tag_caption_plural = ! empty( $settings['tag_caption_plural'] ) ? $settings['tag_caption_plural'] : esc_html__( 'Tags:', 'lastudio-kit' );
        ?>
        <div class="product_meta">

            <?php do_action( 'woocommerce_product_meta_start' ); ?>

            <?php if ( wc_product_sku_enabled() && ( $sku || $product->is_type( 'variable' ) ) ) : ?>
                <span class="sku_wrapper detail-container"><span class="detail-label"><?php echo esc_html( $sku_caption ); ?></span> <span class="sku"><?php echo $sku ? $sku : esc_html( $sku_missing ); ?></span></span>
            <?php endif; ?>

            <?php if ( count( $product->get_category_ids() ) ) : ?>
                <span class="posted_in detail-container"><span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $category_caption_single, $category_caption_plural, count( $product->get_category_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_cat', '', '<span>, </span>' ); ?></span></span>
            <?php endif; ?>

            <?php if ( count( $product->get_tag_ids() ) ) : ?>
                <span class="tagged_as detail-container"><span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $tag_caption_single, $tag_caption_plural, count( $product->get_tag_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_tag', '', '<span>, </span>' ); ?></span></span>
            <?php endif; ?>

            <?php do_action( 'woocommerce_product_meta_end' ); ?>

        </div>
        <?php
    }

    public function render_plain_content() {}

}