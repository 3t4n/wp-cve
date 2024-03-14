<?php
/**
 * @class XproWooUpsellsModule
 *
 */

if ( class_exists( 'WooCommerce' ) ) {

    class XproWooUpsellsModule extends FLBuilderModule {

        /**
         * @method __construct
         *
         */
        public function __construct()
        {
            parent::__construct(array(
                'name'          => __('Woo Up Sells', 'xpro-bb-addons'),
                'description'   => __('An awesome addition by Xpro team!', 'xpro-bb-addons'),
                'group'         => XPRO_Plugins_Helper::$branding_modules,
                'category'      => XPRO_Plugins_Helper::$themer_modules,
                'dir'           => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-woo-upsells/',
                'url'           => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-woo-upsells/',
                'partial_refresh' 	=> true,
            ));
        }

        /**
         * @method enqueue_scripts
         *
         */
        public function enqueue_scripts()
        {
            // Register and enqueue your own.
            $this->add_css( 'cubeportfolio-css', XPRO_ADDONS_FOR_BB_URL . 'assets/css/cubeportfolio.min.css' );
            $this->add_js( 'cubeportfolio-js', XPRO_ADDONS_FOR_BB_URL . 'assets/js/jquery.cubeportfolio.min.js', array( 'jquery' ), '4.4.0', true );
        }

    }

    /**
     * Register the module and its form settings.
     */
    FLBuilder::register_module('XproWooUpsellsModule', array(
        'general'       => array(
            'title'         => __('General', 'xpro-bb-addons'),
            'sections'      => array(
                'content'       => array(
                    'title'         => __('General', 'xpro-bb-addons'),
                    'fields'        => array(
                        'layout' => array(
                            'type'    => 'select',
                            'label'   => __('Select Layout', 'xpro-bb-addons'),
                            'default' => '1',
                            'options' => array(
                                '1' => __('1', 'xpro-bb-addons'),
                                '2' => __('2', 'xpro-bb-addons'),
                                '3' => __('3', 'xpro-bb-addons'),
                                '4' => __('4', 'xpro-bb-addons'),
                                '5' => __('5', 'xpro-bb-addons'),
                                '6' => __('6', 'xpro-bb-addons'),
                                '7' => __('7', 'xpro-bb-addons'),
                                '8' => __('8', 'xpro-bb-addons'),
                                '9' => __('9', 'xpro-bb-addons'),
                                '10' => __('10', 'xpro-bb-addons'),
                            ),
                        ),
                        'column_grid' => array(
                            'type'    => 'select',
                            'label'   => __('Select Columns', 'xpro-bb-addons'),
                            'responsive'  => array(
                                'default' => array(
                                    'default'    =>  '3',
                                    'medium'     =>  '2',
                                    'responsive' =>  '1',
                                ),
                            ),
                            'options' => array(
                                '1' => __('1', 'xpro-bb-addons'),
                                '2' => __('2', 'xpro-bb-addons'),
                                '3' => __('3', 'xpro-bb-addons'),
                                '4' => __('4', 'xpro-bb-addons'),
                                '5' => __('5', 'xpro-bb-addons'),
                                '6' => __('6', 'xpro-bb-addons'),
                            ),
                        ),
                        'thumbnail_size'   => array(
                            'type'    => 'photo-sizes',
                            'label'   => __( 'Image Size', 'xpro-bb-addons' ),
                            'default' => 'medium',
                        ),
                        'show_category' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Category', 'xpro-bb-addons'),
                            'default' => 'yes',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                        ),
                        'show_title' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Title', 'xpro-bb-addons'),
                            'default' => 'yes',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                        ),
                        'show_content' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Description', 'xpro-bb-addons'),
                            'default' => 'no',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                            'toggle'        => array(
                                'yes'      => array(
                                    'fields'        => array( 'content_length' ),
                                ),
                            )
                        ),
                        'content_length' => array(
                            'type'         => 'unit',
                            'label'        => 'Description Length',
                            'default'   => 10,
                            'slider' => array(
                                'min'   => 0,
                                'max'   => 500,
                                'step'  => 5,
                            ),
                        ),
                        'show_rating' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Rating', 'xpro-bb-addons'),
                            'default' => 'yes',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                        ),
                        'show_price' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Price', 'xpro-bb-addons'),
                            'default' => 'yes',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                        ),
                        'show_qv_action' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Actions', 'xpro-bb-addons'),
                            'default' => 'yes',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                            'toggle'        => array(
                                'yes'      => array(
                                    'fields'      => array( 'show_qv_icon', 'show_cart_icon' ),
                                    'tabs'      => array( 'quick-view' ),
                                ),
                            )
                        ),
                        'show_qv_icon' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Quick View', 'xpro-bb-addons'),
                            'default' => 'yes',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                            'toggle'        => array(
                                'yes'      => array(
                                    'tabs'      => array( 'quick-view' ),
                                ),
                            )
                        ),
                        'show_cart_icon' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Cart Icon', 'xpro-bb-addons'),
                            'default' => 'yes',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                        ),
                        'show_cta' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show CTA Button', 'xpro-bb-addons'),
                            'default' => 'no',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                        ),
                    )
                ),
                'badge'       => array(
                    'title'         => __('Badge', 'xpro-bb-addons'),
                    'collapsed' => true,
                    'fields'        => array(
                        'show_badges' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Badges', 'xpro-bb-addons'),
                            'default' => 'yes',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                            'toggle'        => array(
                                'yes'      => array(
                                    'fields'    => array( 'woo_badges_style', 'badges_direction', 'sale_badge_type', 'show_sale_badge', 'show_featured_badge' ),
                                ),
                            )
                        ),
                        'woo_badges_style' => array(
                            'type'    => 'button-group',
                            'label'   => __('Style', 'xpro-bb-addons'),
                            'default' => 'square',
                            'responsive' => true,
                            'options' => array(
                                'square' => __('Square', 'xpro-bb-addons'),
                                'circle' => __('Circle', 'xpro-bb-addons'),
                            ),
                        ),
                        'badges_direction' => array(
                            'type'    => 'button-group',
                            'label'   => __('Direction', 'xpro-bb-addons'),
                            'default' => 'column',
                            'responsive' => true,
                            'options' => array(
                                'column' => __('Column', 'xpro-bb-addons'),
                                'row' => __('Row', 'xpro-bb-addons'),
                            ),
                        ),
                        'sale_badge_type' => array(
                            'type'    => 'button-group',
                            'label'   => __('Sale Badge Type', 'xpro-bb-addons'),
                            'default'   => 'text',
                            'responsive' => true,
                            'options'   => array(
                                'text'       => __( 'Text', 'xpro-elementor-addons' ),
                                'percentage' => __( 'Percentage', 'xpro-elementor-addons' ),
                            ),
                        ),
                        'show_sale_badge' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Sale Type', 'xpro-bb-addons'),
                            'default'   => 'yes',
                            'options'   => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                        ),
                        'show_featured_badge' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Featured Type', 'xpro-bb-addons'),
                            'default'   => 'yes',
                            'options'   => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                        ),
                    )
                ),
            )
        ),
        'product_query' => array(
            'title' => __( 'Query', 'xpro' ),
            'file'  => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-woo-upsells/includes/loop-settings.php',
        ),
        'style'       => array(
            'title'         => __('Style', 'xpro-bb-addons'),
            'sections'      => array(
                'general'       => array(
                    'title'         => __('General', 'xpro-bb-addons'),
                    'fields'        => array(
                        'image_height' => array(
                            'type'         => 'unit',
                            'label'        => 'Image Height',
                            'units'          => array( 'px', 'vw', '%' ),
                            'default_unit' => 'px',
                            'responsive' => true,
                            'slider' => array(
                                'px'    => array(
                                    'min' =>0,
                                    'max' => 1200,
                                    'step'    => 1,
                                ),
                            ),
                            'preview'       => array(
                                'type'          => 'css',
                                'rules'           => array(
                                    array(
                                        'selector'     => '.xpro-product-grid-wrapper .xpro-woo-product-grid-img',
                                        'property'     => 'height'
                                    ),
                                    array(
                                        'selector'     => '.xpro-product-grid-wrapper .xpro-woo-product-grid-img',
                                        'property'     => 'min-height'
                                    ),
                                ),
                            ),
                        ),
                        'object_fit' => array(
                            'type'          => 'select',
                            'label'         => __( 'Object Fit', 'xpro-elementor-addons-pro' ),
                            'default'       => '',
                            'options'       => array(
                                ''      => __( 'Default', 'xpro-elementor-addons-pro' ),
                                'fill'      => __( 'Fill', 'xpro-elementor-addons-pro' ),
                                'cover'      => __( 'Cover', 'xpro-elementor-addons-pro' ),
                                'contain'      => __( 'Contain', 'xpro-elementor-addons-pro' ),
                            ),
                            'toggle'        => array(
                                'option-1'      => array(
                                    'fields'        => array( 'my_field_1', 'my_field_2' ),
                                    'sections'      => array( 'my_section' ),
                                    'tabs'          => array( 'my_tab' )
                                ),
                                'option-2'      => array()
                            )
                        ),
                        'space_between' => array(
                            'type'   => 'unit',
                            'label'  => 'Space Between',
                            'units'  => array( 'px' ),
                            'default_unit' => 'px',
                            'default' => 15,
                            'responsive' => true,
                            'slider' => array(
                                'px'    => array(
                                    'min' =>0,
                                    'max' => 500,
                                    'step'    => 1,
                                ),
                            ),
                        ),
                        'item_bg_type' => array(
                            'type'          => 'select',
                            'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                            'default'       => 'none',
                            'options'       => array(
                                'none'      => __( 'None', 'xpro-bb-addons' ),
                                'color'      => __( 'Color', 'xpro-bb-addons' ),
                                'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
                            ),
                            'toggle'        => array(
                                'color'      => array(
                                    'fields'        => array( 'item_bg' ),
                                ),
                                'gradient'      => array(
                                    'fields'        => array( 'item_bg_gradient' ),
                                ),
                            )
                        ),
                        'item_bg' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-item',
                                'property'     => 'background-color'
                            ),
                        ),
                        'item_bg_gradient' => array(
                            'type'    => 'gradient',
                            'label'   => 'Gradient Color',
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-item',
                                'property' => 'background-image',
                            ),
                        ),
                        'item_border' => array(
                            'type'       => 'border',
                            'label'      => 'Border',
                            'units'          => array( 'px', '%' ),
                            'responsive' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-item',
                                'property' => 'border'
                            ),
                        ),
                        'item_padding' => array(
                            'type'        => 'dimension',
                            'label'       => 'Padding',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-item',
                                'property'     => 'padding'
                            )
                        ),
                        'overlay_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Overlay Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-img-section::after',
                                'property'     => 'background-color'
                            ),
                        ),
                        'overlay_hcolor' => array(
                            'type'          => 'color',
                            'label'         => __( 'Overlay Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-img:hover .xpro-woo-product-img-section::after',
                                'property'     => 'background-color'
                            ),
                        ),
                    )
                ),
                'content'       => array(
                    'title'         => __('Content', 'xpro-bb-addons'),
                    'collapsed' => true,
                    'fields'        => array(
                        'alignment' => array(
                            'type'          => 'select',
                            'label'         => __( 'Alignment', 'xpro-bb-addons' ),
                            'responsive'    => true,
                            'default'    => 'none',
                            'options'       => array(
                                'none'      => __( 'None', 'xpro-bb-addons' ),
                                'left'      => __( 'Left', 'xpro-bb-addons' ),
                                'center'      => __( 'Center', 'xpro-bb-addons' ),
                                'right'      => __( 'Right', 'xpro-bb-addons' ),
                            ),
                        ),
                        'content_bg_type' => array(
                            'type'          => 'select',
                            'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                            'default'       => 'none',
                            'options'       => array(
                                'none'      => __( 'None', 'xpro-bb-addons' ),
                                'color'      => __( 'Color', 'xpro-bb-addons' ),
                                'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
                            ),
                            'toggle'        => array(
                                'color'      => array(
                                    'fields'        => array( 'content_background' ),
                                ),
                                'gradient'      => array(
                                    'fields'        => array( 'content_gradient' ),
                                ),
                            )
                        ),
                        'content_background' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-content-sec',
                                'property'     => 'background-color'
                            ),
                        ),
                        'content_gradient' => array(
                            'type'    => 'gradient',
                            'label'   => 'Gradient Color',
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-content-sec',
                                'property' => 'background-image',
                            ),
                        ),
                        'content_border' => array(
                            'type'       => 'border',
                            'label'      => 'Border',
                            'units'          => array( 'px', '%' ),
                            'responsive' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec',
                                'property' => 'border'
                            ),
                        ),
                        'content_padding' => array(
                            'type'        => 'dimension',
                            'label'       => 'Padding',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec',
                                'property'     => 'padding'
                            )
                        ),
                        'content_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margin',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec',
                                'property'     => 'margin'
                            )
                        ),
                        'xpro-widget-seprator1' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Category<hr></h2>',
                        ),
                        'category_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-product-grid-wrapper .xpro_elementor_category_term_name',
                            ),
                        ),
                        'category_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'rules'           => array(
                                    array(
                                        'selector'     => '.xpro-product-grid-wrapper .xpro_elementor_category_term_name',
                                        'property'     => 'color'
                                    ),
                                    array(
                                        'selector'     => '.xpro-woo-product-grid-category-wrapper::before',
                                        'property'     => 'background-color'
                                    ),
                                ),
                            ),
                        ),
                        'category_hover_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Hover Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'rules'           => array(
                                    array(
                                        'selector'     => '.xpro-product-grid-wrapper .xpro_elementor_category_term_name:hover',
                                        'property'     => 'color'
                                    ),
                                    array(
                                        'selector'     => '.xpro-product-grid-wrapper .xpro_elementor_category_term_name:hover .xpro-woo-product-grid-category-wrapper::before',
                                        'property'     => 'background-color'
                                    ),
                                ),
                            ),
                        ),
                        'category_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margin',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-category-wrapper',
                                'property'     => 'margin'
                            )
                        ),
                        'xpro-widget-seprator2' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Title<hr></h2>',
                        ),
                        'title_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-product-grid-wrapper .xpro-woo-product-grid-title',
                            ),
                        ),
                        'title_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'selector'     => '.xpro-product-grid-wrapper .xpro-woo-product-grid-title',
                                'property'     => 'color',
                            ),
                        ),
                        'title_hover_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Hover Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'selector'     => '.xpro-product-grid-wrapper .xpro-woo-product-grid-title:hover',
                                'property'     => 'color',
                            ),
                        ),
                        'title_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margin',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-title',
                                'property'     => 'margin'
                            )
                        ),
                        'xpro-widget-seprator3' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Description<hr></h2>',
                        ),
                        'description_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-product-grid-wrapper .xpro-woo-product-grid-excerpt',
                            ),
                        ),
                        'excerpt_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'selector'     => '.xpro-product-grid-wrapper .xpro-woo-product-grid-excerpt',
                                'property'     => 'color',
                            ),
                        ),
                        'excerpt_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margin',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-excerpt',
                                'property'     => 'margin'
                            )
                        ),
                        'xpro-widget-seprator4' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Rating<hr></h2>',
                        ),
                        'rating_size' => array(
                            'type'   => 'unit',
                            'label'  => 'Size',
                            'units'  => array( 'px' ),
                            'default_unit' => 'px',
                            'responsive' => true,
                            'slider' => array(
                                'px'    => array(
                                    'min' => 10,
                                    'max' => 100,
                                    'step' => 1,
                                ),
                            ),
                            'preview'       => array(
                                'type'          => 'css',
                                'selector'     => '.xpro-product-grid-wrapper .xpro-woo-product-grid-star-rating-wrapper .star-rating',
                                'property'     => 'font-size',
                            ),
                        ),
                        'rating_front_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'selector'     => '.xpro-product-grid-wrapper .star-rating span::before',
                                'property'     => 'color',
                            ),
                        ),
                        'rating_bg_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'selector'     => '.xpro-product-grid-wrapper .star-rating::before',
                                'property'     => 'background-color',
                            ),
                        ),
                        'rating_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margin',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-star-rating-wrapper',
                                'property'     => 'margin'
                            )
                        ),
                        'xpro-widget-seprator5' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Price<hr></h2>',
                        ),
                        'price_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-woo-product-grid-price-wrapper .price',
                            ),
                        ),
                        'price_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'selector'     => '.xpro-woo-product-grid-price-wrapper .price',
                                'property'     => 'color',
                            ),
                        ),
                        'space_between_price' => array(
                            'type'   => 'unit',
                            'label'  => 'Size',
                            'units'  => array( 'px' ),
                            'default_unit' => 'px',
                            'responsive' => true,
                            'slider' => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'selector'     => '.xpro-woo-product-grid-price-wrapper ins',
                                'property'     => 'padding-left',
                            ),
                        ),
                        'sale_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-woo-product-grid-price-wrapper del .woocommerce-Price-amount',
                            ),
                        ),
                        'sale_price_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'selector'     => '.xpro-woo-product-grid-price-wrapper del, .xpro-woo-product-grid-price-wrapper del .woocommerce-Price-amount',
                                'property'     => 'color',
                            ),
                        ),
                        'price_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margin',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-price-wrapper',
                                'property'     => 'margin'
                            )
                        ),
                    )
                ),
                'actions'       => array(
                    'title'         => __('Actions', 'xpro-bb-addons'),
                    'collapsed' => true,
                    'fields'        => array(
                        'icons_size' => array(
                            'type'   => 'unit',
                            'label'  => 'Size',
                            'units'  => array( 'px' ),
                            'default_unit' => 'px',
                            'responsive' => true,
                            'slider' => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'selector'     => '.xpro-product-grid-hv-cta-section .xpro-cta-btn i, .xpro-product-grid-hv-cta-section .xpro-qv-cart-btn .button::before',
                                'property'     => 'font-size',
                            ),
                        ),
                        'icons_bg_size' => array(
                            'type'         => 'unit',
                            'label'        => 'Background Size',
                            'units'          => array( 'px', '%' ),
                            'default_unit' => 'px',
                            'responsive' => true,
                            'slider' => array(
                                'px'    => array(
                                    'min' =>0,
                                    'max' => 500,
                                    'step'    => 1,
                                ),
                            ),
                            'preview'       => array(
                                'type'          => 'css',
                                'rules'           => array(
                                    array(
                                        'selector'     => '.xpro-hv-qv-btn.xpro-cta-btn,.xpro-hv-cart-btn.xpro-cta-btn',
                                        'property'     => 'width'
                                    ),
                                    array(
                                        'selector'     => '.xpro-hv-qv-btn.xpro-cta-btn,.xpro-hv-cart-btn.xpro-cta-btn',
                                        'property'     => 'height'
                                    ),
                                    array(
                                        'selector'     => '.xpro-hv-qv-btn.xpro-cta-btn,.xpro-hv-cart-btn.xpro-cta-btn',
                                        'property'     => 'line-height'
                                    ),
                                ),
                            ),
                        ),
                        'icons_space_between' => array(
                            'type'   => 'unit',
                            'label'  => 'Space Between',
                            'units'  => array( 'px' ),
                            'default_unit' => 'px',
                            'responsive' => true,
                            'slider' => array(
                                'px'    => array(
                                    'min' =>0,
                                    'max' => 500,
                                    'step'    => 5,
                                ),
                            ),
                            'preview'       => array(
                                'type'          => 'css',
                                'selector'     => '.xpro-product-grid-hv-cta-section',
                                'property'     => 'grid-gap',
                            ),
                        ),
                        'actions_bg_type' => array(
                            'type'          => 'select',
                            'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                            'default'       => 'none',
                            'options'       => array(
                                'none'      => __( 'None', 'xpro-bb-addons' ),
                                'normal'      => __( 'Normal', 'xpro-bb-addons' ),
                                'hover'      => __( 'Hover', 'xpro-bb-addons' ),
                            ),
                            'toggle'        => array(
                                'normal'      => array(
                                    'fields'        => array( 'qv_icons_color', 'qv_icons_background' ),
                                ),
                                'hover'      => array(
                                    'fields'        => array(  'qv_icons_hcolor', 'qv_icons_hbackground' ),
                                ),
                            )
                        ),
                        'qv_icons_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-hv-cta-section .xpro-cta-btn i,.xpro-product-grid-hv-cta-section .xpro-qv-cart-btn .button::before',
                                'property'     => 'color'
                            ),
                        ),
                        'qv_icons_hcolor' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-hv-cta-section .xpro-cta-btn:hover i,.xpro-product-grid-hv-cta-section .xpro-qv-cart-btn:hover .button::before',
                                'property'     => 'color'
                            ),
                        ),
                        'qv_icons_background' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-hv-cta-section .xpro-cta-btn',
                                'property'     => 'background-color'
                            ),
                        ),
                        'qv_icons_hbackground' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-hv-cta-section .xpro-cta-btn:hover',
                                'property'     => 'background-color'
                            ),
                        ),
                        'qv_icons_btns_border' => array(
                            'type'       => 'border',
                            'label'      => 'Border',
                            'units'          => array( 'px', '%' ),
                            'responsive' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-hv-cta-section .xpro-cta-btn',
                                'property' => 'border'
                            ),
                        ),
                        'qv_icons_btn_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margin',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-product-grid-hv-cta-section .xpro-cta-btn',
                                'property'     => 'margin'
                            )
                        ),
                    )
                ),
                'badge'       => array(
                    'title'         => __('Badge', 'xpro-bb-addons'),
                    'collapsed' => true,
                    'fields'        => array(
                        'badges_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-woo-product-grid-item .xpro-woo-badges-btn',
                            ),
                        ),
                        'badges_bg_size' => array(
                            'type'   => 'unit',
                            'label'  => 'Background Size',
                            'units'  => array( 'px' ),
                            'default_unit' => 'px',
                            'responsive' => true,
                            'slider' => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'rules'           => array(
                                    array(
                                        'selector'     => '.xpro-woo-product-grid-item .xpro-woo-sale-flash-btn, .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn',
                                        'property'     => 'width',
                                    ),
                                    array(
                                        'selector'     => '.xpro-woo-product-grid-item .xpro-woo-sale-flash-btn, .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn',
                                        'property'     => 'height'
                                    ),
                                ),
                            ),
                        ),
                        'badges_btn_border' => array(
                            'type'       => 'border',
                            'label'      => 'Border',
                            'units'          => array( 'px', '%' ),
                            'responsive' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-item .xpro-woo-badges-btn',
                                'property' => 'border'
                            ),
                        ),
                        'badges_btn_padding' => array(
                            'type'        => 'dimension',
                            'label'       => 'Padding',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-item .xpro-woo-badges-btn',
                                'property'     => 'padding'
                            )
                        ),
                        'badges_btn_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Buttons Spacing',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-item .xpro-woo-product-grid-badges-wrapper',
                                'property'     => 'margin'
                            )
                        ),
                        'xpro-widget-seprator17' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Sale<hr></h2>',
                        ),
                        'sale_btn_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-item .xpro-woo-sale-flash-btn',
                                'property'     => 'color'
                            ),
                        ),
                        'sale_btn_background' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-item .xpro-woo-sale-flash-btn',
                                'property'     => 'background-color'
                            ),
                        ),
                        'sale_btn_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Buttons Spacing',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-item .xpro-woo-sale-flash-btn',
                                'property'     => 'margin'
                            )
                        ),
                        'xpro-widget-seprator18' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Featured<hr></h2>',
                        ),
                        'featured_btn_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-item .xpro-woo-featured-flash-btn',
                                'property'     => 'color'
                            ),
                        ),
                        'featured_btn_background' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-item .xpro-woo-featured-flash-btn',
                                'property'     => 'background-color'
                            ),
                        ),
                        'featured_btn_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Buttons Spacing',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-item .xpro-woo-featured-flash-btn',
                                'property'     => 'margin'
                            )
                        ),
                        'xpro-widget-seprator19' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Out Of Stock<hr></h2>',
                        ),
                        'out_stock_btn_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-item .xpro-woo-out-of-stock-btn',
                                'property'     => 'color'
                            ),
                        ),
                        'out_stock_background' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-item .xpro-woo-out-of-stock-btn',
                                'property'     => 'background-color'
                            ),
                        ),
                        'out_stock_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Buttons Spacing',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-item .xpro-woo-out-of-stock-btn',
                                'property'     => 'margin'
                            )
                        ),
                    )
                ),
                'button'       => array(
                    'title'         => __('Button', 'xpro-bb-addons'),
                    'collapsed' => true,
                    'fields'        => array(
                        'button_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-woo-product-grid-add-to-cart-btn .button',
                            ),
                        ),
                        'btn_bg_type' => array(
                            'type'          => 'select',
                            'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                            'default'       => 'none',
                            'options'       => array(
                                'none'      => __( 'None', 'xpro-bb-addons' ),
                                'normal'      => __( 'Normal', 'xpro-bb-addons' ),
                                'hover'      => __( 'Hover', 'xpro-bb-addons' ),
                            ),
                            'toggle'        => array(
                                'normal'      => array(
                                    'fields'        => array( 'button_color', 'button_bg' ),
                                ),
                                'hover'      => array(
                                    'fields'        => array(  'button_hcolor', 'button_hbg' ),
                                ),
                            )
                        ),
                        'button_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-add-to-cart-btn .button',
                                'property'     => 'color'
                            ),
                        ),
                        'button_hcolor' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-add-to-cart-btn .button:hover,.xpro-woo-product-grid-add-to-cart-btn .button:focus',
                                'property'     => 'color'
                            ),
                        ),
                        'button_bg' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-add-to-cart-btn .button',
                                'property'     => 'background-color'
                            ),
                        ),
                        'button_hbg' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-add-to-cart-btn .button:hover,.xpro-woo-product-grid-add-to-cart-btn .button:focus',
                                'property'     => 'background-color'
                            ),
                        ),
                        'button_border' => array(
                            'type'       => 'border',
                            'label'      => 'Border',
                            'units'          => array( 'px', '%' ),
                            'responsive' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-add-to-cart-btn .button',
                                'property' => 'border'
                            ),
                        ),
                        'button_item_padding' => array(
                            'type'        => 'dimension',
                            'label'       => 'Padding',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-add-to-cart-btn .button',
                                'property'     => 'padding'
                            )
                        ),
                        'button_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margin',
                            'responsive' => true,
                            'units'          => array( 'px', '%' ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-woo-product-grid-add-to-cart-btn .button',
                                'property'     => 'margin'
                            )
                        ),
                    )
                ),
            )
        ),
    ));

} else {

    class XproWooUpsellsModuleWooNotExist extends FLBuilderModule {

        /**
         * @return void
         */
        public function __construct() {
            parent::__construct(
                array(
                    'name'          => __('Woo Up Sells', 'xpro-bb-addons'),
                    'description'   => __('An awesome addition by Xpro team!', 'xpro-bb-addons'),
                    'group'         => XPRO_Plugins_Helper::$branding_modules,
                    'category'      => XPRO_Plugins_Helper::$themer_modules,
                    'dir'           => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-woo-upsells/',
                    'url'           => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-woo-upsells/',
                    'partial_refresh' 	=> true,
                )
            );
        }
    }

    FLBuilder::register_module(
        'XproWooUpsellsModuleWooNotExist',
        array(
            'general-info' => array(
                'title'       => __( 'General', 'xpro' ),
                'description' => __( 'Please Install Woocommerce Plugin to use this Module.', 'xpro' ),
            ),
        )
    );

}
