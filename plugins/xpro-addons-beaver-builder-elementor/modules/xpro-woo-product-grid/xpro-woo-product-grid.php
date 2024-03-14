<?php
/**
 * @class XPROWooProductGridModule
 *
 */

if ( class_exists( 'WooCommerce' ) ) {

    class XproWooProductGridModule extends FLBuilderModule {

        /**
         * @method __construct
         *
         */
        public function __construct()
        {
            parent::__construct(array(
                'name'          => __('Woo Product Grid', 'xpro-bb-addons'),
                'description'   => __('An awesome addition by Xpro team!', 'xpro-bb-addons'),
                'group'         => XPRO_Plugins_Helper::$branding_modules,
                'category'      => XPRO_Plugins_Helper::$woo_modules,
                'dir'           => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-woo-product-grid/',
                'url'           => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-woo-product-grid/',
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
            $this->add_js('wc-add-to-cart-variation');
        }

    }

    /**
     * Register the module and its form settings.
     */
    FLBuilder::register_module('XPROWooProductGridModule', array(
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
                                '1' => __('Layout 1', 'xpro-bb-addons'),
                                '2' => __('Layout 2', 'xpro-bb-addons'),
                                '3' => __('Layout 3', 'xpro-bb-addons'),
                                '4' => __('Layout 4', 'xpro-bb-addons'),
                                '5' => __('Layout 5', 'xpro-bb-addons'),
                                '6' => __('Layout 6', 'xpro-bb-addons'),
                                '7' => __('Layout 7', 'xpro-bb-addons'),
                                '8' => __('Layout 8', 'xpro-bb-addons'),
                                '9' => __('Layout 9', 'xpro-bb-addons'),
                                '10' => __('Layout 10', 'xpro-bb-addons'),
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
                            'default' => 'no',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                            'toggle'        => array(
                                'yes'      => array(
                                    'fields'      => array( 'show_qv_icon', 'show_cart_icon' ),
                                ),
                            )
                        ),
                        'show_qv_icon' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Quick View', 'xpro-bb-addons'),
                            'default' => 'no',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                            'toggle'        => array(
                                'yes'      => array(
                                    'sections'      => array( 'quick-view' ),
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
                                    'fields'    => array( 'badges_styles' ),
                                ),
                            )
                        ),
                        'badges_styles'    => array(
                            'type'         => 'form',
                            'label'        => __( 'Badges Styles', 'xpro-bb-addons' ),
                            'form'         => 'xpro_products_badges_form',
                            'preview_text' => 'icon',
                        ),
                    )
                ),
                'pagination'       => array(
                    'title'         => __('Pagination', 'xpro-bb-addons'),
                    'collapsed' => true,
                    'fields'        => array(
                        'show_pagination' => array(
                            'type'    => 'button-group',
                            'label'   => __('Show Pagination', 'xpro-bb-addons'),
                            'default' => 'no',
                            'options' => array(
                                'yes' => __('Show', 'xpro-bb-addons'),
                                'no' => __('Hide', 'xpro-bb-addons'),
                            ),
                            'toggle'        => array(
                                'yes'      => array(
                                    'fields'        => array( 'pagination_styles' ),
                                ),
                            )
                        ),
                        'pagination_styles'    => array(
                            'type'         => 'form',
                            'label'        => __( 'Pagination Styles', 'xpro-bb-addons' ),
                            'form'         => 'xpro_products_pagination_form',
                            'preview_text' => 'icon',
                        ),
                    )
                ),
            )
        ),
        'product_query' => array(
            'title' => __( 'Query', 'xpro' ),
            'file'  => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-woo-product-grid/includes/loop-settings.php',
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
                            'type'          => 'button-group',
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
                            'type'          => 'button-group',
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
                        'quick_view_styles'    => array(
                            'type'         => 'form',
                            'label'        => __( 'Quick View Styles', 'xpro-bb-addons' ),
                            'form'         => 'xpro_products_quick_view_form',
                            'preview_text' => 'icon',
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
                            'type'          => 'button-group',
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
                            'type'          => 'button-group',
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
                'quick-view' => array(
                    'title'  => __( 'Quick View', 'xpro-bb-addons' ),
                    'fields' => array(
                        'quick_view_styles'    => array(
                            'type'         => 'form',
                            'label'        => __( 'Quick View Styles', 'xpro-bb-addons' ),
                            'form'         => 'xpro_products_quick_view_form',
                            'preview_text' => 'icon',
                        ),
                    ),
                ),
            )
        ),
    ));

    /**
     * Register a settings form for Quick View Styles.
     */
    FLBuilder::register_settings_form(
        'xpro_products_content_style_form',
        array(
            'title' => __( 'Content Styles', 'xpro-bb-addons' ),
            'tabs'  => array(
                'general' => array(
                    'title'    => __( 'Content Styles', 'xpro-bb-addons' ),
                    'sections' => array(
                        'content'       => array(
                            'title'         => __('Content', 'xpro-bb-addons'),
                            'collapsed' => true,
                            'fields'        => array(
                                'alignment' => array(
                                    'type'          => 'button-group',
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
                                    'type'          => 'button-group',
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
                    ),
                ),
            ),
        )
    );

    /**
     * Register a settings form for Quick View Styles.
     */
    FLBuilder::register_settings_form(
        'xpro_products_quick_view_form',
        array(
            'title' => __( 'Quick View Styles', 'xpro-bb-addons' ),
            'tabs'  => array(
                'general' => array(
                    'title'    => __( 'Feature List', 'xpro-bb-addons' ),
                    'sections' => array(
                        'quick-view'       => array(
                            'title'         => __('General', 'xpro-bb-addons'),
                            'fields'        => array(
                                'qv_layout' => array(
                                    'type'    => 'select',
                                    'label'   => __('Layout', 'xpro-bb-addons'),
                                    'default' => '1',
                                    'options' => array(
                                        '1' => __('Layout 1', 'xpro-bb-addons'),
                                        '2' => __('Layout 2', 'xpro-bb-addons'),
                                        '3' => __('Layout 3', 'xpro-bb-addons'),
                                        '4' => __('Layout 4', 'xpro-bb-addons'),
                                    ),
                                ),
                            )
                        ),
                        'popup-content'       => array(
                            'title'         => __('Popup Content', 'xpro-bb-addons'),
                            'collapsed' => true,
                            'fields'        => array(
                                'qv_main_content_bg_type' => array(
                                    'type'          => 'button-group',
                                    'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                                    'default'       => 'none',
                                    'options'       => array(
                                        'none'      => __( 'None', 'xpro-bb-addons' ),
                                        'color'      => __( 'Color', 'xpro-bb-addons' ),
                                        'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
                                    ),
                                    'toggle'        => array(
                                        'color'      => array(
                                            'fields'        => array( 'qv_main_content_background' ),
                                        ),
                                        'gradient'      => array(
                                            'fields'        => array( 'qv_main_content_gradient' ),
                                        ),
                                    )
                                ),
                                'qv_main_content_background' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-qv-popup-inner',
                                        'property'     => 'background-color'
                                    ),
                                ),
                                'qv_main_content_gradient' => array(
                                    'type'    => 'gradient',
                                    'label'   => 'Gradient Color',
                                    'preview' => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-qv-popup-inner',
                                        'property' => 'background-image',
                                    ),
                                ),
                                'qv_overlay_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Overlay Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-qv-popup-overlay',
                                        'property'     => 'background-color'
                                    ),
                                ),
                                'qv_main_content_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'Border',
                                    'units'          => array( 'px', '%' ),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-qv-popup-inner',
                                        'property' => 'border'
                                    ),
                                ),
                                'qv_main_content_padding' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Padding',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-qv-popup-inner',
                                        'property'     => 'padding'
                                    )
                                ),
                                'qv_main_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Margin',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-qv-popup-inner',
                                        'property'     => 'margin'
                                    )
                                ),
                                'xpro-widget-seprator6' => array(
                                    'type'    => 'raw',
                                    'content' => '<h2 class="xpro-widget-separator-heading">SKU<hr></h2>',
                                ),
                                'qv_meta_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '.xpro-qv-main-wrapper .sku_wrapper',
                                    ),
                                ),
                                'qv_sku_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .sku_wrapper',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_sku_title_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Title Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .sku_wrapper .sku',
                                        'property'     => 'color',
                                    ),
                                ),

                                'xpro-widget-seprator7' => array(
                                    'type'    => 'raw',
                                    'content' => '<h2 class="xpro-widget-separator-heading">Taxonomy<hr></h2>',
                                ),
                                'qv_tax_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '.xpro-qv-main-wrapper .product_meta .posted_in',
                                    ),
                                ),
                                'qv_tax_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .product_meta .posted_in',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_tax_link_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Link Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .product_meta .posted_in a',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_tax_link_hv_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Link Hover', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .product_meta .posted_in a:hover',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_tax_link_bg_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Link Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .product_meta .posted_in a',
                                        'property'     => 'Background-color',
                                    ),
                                ),
                                'qv_tax_seprator_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Separator Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .xpro-woo-qv-content-sec .sku_wrapper',
                                        'property'     => 'border-color',
                                    ),
                                ),
                                'qv_seprator_size' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Separator Size',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .xpro-woo-qv-content-sec .sku_wrapper',
                                        'property'     => 'border-width',
                                    ),
                                ),
                                'qv_sku_background' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .product_meta',
                                        'property'     => 'Background-color',
                                    ),
                                ),
                                'qv_meta_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'Border',
                                    'units'          => array( 'px', '%' ),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .product_meta',
                                        'property' => 'border'
                                    ),
                                ),
                                'qv_meta_link_padding' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Link Padding',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .product_meta .posted_in a',
                                        'property'     => 'padding'
                                    )
                                ),
                                'qv_meta_padding' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Padding',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .product_meta',
                                        'property'     => 'padding'
                                    )
                                ),
                                'qv_meta_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Padding',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .product_meta',
                                        'property'     => 'padding'
                                    )
                                ),
                                'xpro-widget-seprator8' => array(
                                    'type'    => 'raw',
                                    'content' => '<h2 class="xpro-widget-separator-heading">Title<hr></h2>',
                                ),
                                'qv_title_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '.xpro-qv-main-wrapper .product_title',
                                    ),
                                ),
                                'qv_title_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .product_title',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_title_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Margin',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .product_title',
                                        'property'     => 'margin'
                                    )
                                ),
                                'xpro-widget-seprator9' => array(
                                    'type'    => 'raw',
                                    'content' => '<h2 class="xpro-widget-separator-heading">Description<hr></h2>',
                                ),
                                'qv_description_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '.xpro-qv-main-wrapper .woocommerce-product-details__short-description',
                                    ),
                                ),
                                'qv_desc_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .woocommerce-product-details__short-description',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_desc_padding' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Padding',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .woocommerce-product-details__short-description',
                                        'property'     => 'padding'
                                    )
                                ),
                                'qv_desc_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Margin',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .woocommerce-product-details__short-description',
                                        'property'     => 'margin'
                                    )
                                ),
                                'xpro-widget-seprator10' => array(
                                    'type'    => 'raw',
                                    'content' => '<h2 class="xpro-widget-separator-heading">Rating<hr></h2>',
                                ),
                                'qv_rating_txt_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Text Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .woocommerce-review-link',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_rating_txt_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '.xpro-qv-main-wrapper .woocommerce-review-link',
                                    ),
                                ),
                                'qv_rating_size' => array(
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
                                        'selector'     => '.xpro-qv-main-wrapper .star-rating',
                                        'property'     => 'font-size',
                                    ),
                                ),
                                'qv_rating_front_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .star-rating span::before',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_rating_bg_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .star-rating span::before',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_rating_txt_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Text Margin',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .woocommerce-review-link',
                                        'property'     => 'margin'
                                    )
                                ),
                                'qv_rating_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Margin',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .woocommerce-product-rating',
                                        'property'     => 'margin'
                                    )
                                ),
                                'xpro-widget-seprator11' => array(
                                    'type'    => 'raw',
                                    'content' => '<h2 class="xpro-widget-separator-heading">Price<hr></h2>',
                                ),
                                'qv_price_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '.xpro-qv-main-wrapper .price',
                                    ),
                                ),
                                'qv_price_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .price',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_space_between_price' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Space Between Sale Price',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .price ins',
                                        'property'     => 'padding-left',
                                    ),
                                ),
                                'qv_sale_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Sale Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '.xpro-qv-main-wrapper .price del .woocommerce-Price-amount',
                                    ),
                                ),
                                'qv_sale_price_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Sale Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .price del .woocommerce-Price-amount',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_price_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Margin',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .price',
                                        'property'     => 'margin'
                                    )
                                ),
                            )
                        ),
                        'popup-button'       => array(
                            'title'         => __('Popup Button', 'xpro-bb-addons'),
                            'collapsed' => true,
                            'fields'        => array(
                                'xpro-widget-seprator12' => array(
                                    'type'    => 'raw',
                                    'content' => '<h2 class="xpro-widget-separator-heading">Close Button<hr></h2>',
                                ),
                                'qv_close_icon_size' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Icon Size',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .xpro-woo-qv-cross i',
                                        'property'     => 'font-size',
                                    ),
                                ),
                                'qv_close_icon_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-woo-qv-cross i',
                                        'property'     => 'color'
                                    ),
                                ),
                                'qv_close_icon_hv_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Icon Hover Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-woo-qv-cross i:hover',
                                        'property'     => 'color'
                                    ),
                                ),
                                'qv_close_icon_bg_type' => array(
                                    'type'          => 'button-group',
                                    'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                                    'default'       => 'none',
                                    'options'       => array(
                                        'none'      => __( 'None', 'xpro-bb-addons' ),
                                        'color'      => __( 'Color', 'xpro-bb-addons' ),
                                        'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
                                    ),
                                    'toggle'        => array(
                                        'color'      => array(
                                            'fields'        => array( 'qv_main_content_background' ),
                                        ),
                                        'gradient'      => array(
                                            'fields'        => array( 'qv_main_content_gradient' ),
                                        ),
                                    )
                                ),
                                'qv_close_icon_background' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-woo-qv-cross',
                                        'property'     => 'background-color'
                                    ),
                                ),
                                'qv_close_icon_gradient' => array(
                                    'type'    => 'gradient',
                                    'label'   => 'Gradient Color',
                                    'preview' => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-woo-qv-cross',
                                        'property' => 'background-image',
                                    ),
                                ),
                                'qv_close_icon_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'Border',
                                    'units'          => array( 'px', '%' ),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-woo-qv-cross',
                                        'property' => 'border'
                                    ),
                                ),
                                'qv_close_icon_padding' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Padding',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-woo-qv-cross',
                                        'property'     => 'padding'
                                    )
                                ),
                                'qv_close_icon_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Margin',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-woo-qv-cross',
                                        'property'     => 'margin'
                                    )
                                ),
                                'xpro-widget-seprator13' => array(
                                    'type'    => 'raw',
                                    'content' => '<h2 class="xpro-widget-separator-heading">Quantity Buttons<hr></h2>',
                                ),
                                'qv_quantity_btn_bg_type' => array(
                                    'type'          => 'button-group',
                                    'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                                    'default'       => 'none',
                                    'options'       => array(
                                        'none'      => __( 'None', 'xpro-bb-addons' ),
                                        'normal'      => __( 'Normal', 'xpro-bb-addons' ),
                                        'hover'      => __( 'Hover', 'xpro-bb-addons' ),
                                    ),
                                    'toggle'        => array(
                                        'normal'      => array(
                                            'fields'        => array( 'qv_quantity_btn_color', 'qv_quantity_btn_bg_color' ),
                                        ),
                                        'hover'      => array(
                                            'fields'        => array( 'qv_quantity_btn_hcolor', 'qv_quantity_btn_bg_hcolor', 'qv_quantity_btn_border_hcolor' ),
                                        ),
                                    )
                                ),
                                'qv_quantity_btn_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .xpro-minus, .xpro-qv-main-wrapper .xpro-plus',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_quantity_btn_bg_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .xpro-minus, .xpro-qv-main-wrapper .xpro-plus',
                                        'property'     => 'background-color',
                                    ),
                                ),
                                'qv_quantity_btn_hcolor' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .xpro-minus:hover, .xpro-qv-main-wrapper .xpro-plus:hover',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_quantity_btn_bg_hcolor' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .xpro-minus:hover, .xpro-qv-main-wrapper .xpro-plus:hover',
                                        'property'     => 'background-color',
                                    ),
                                ),
                                'qv_quantity_btn_border_hcolor' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .xpro-minus:hover, .xpro-qv-main-wrapper .xpro-plus:hover',
                                        'property'     => 'border-color',
                                    ),
                                ),
                                'qv_quantity_btn_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'Border',
                                    'units'          => array( 'px', '%' ),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .xpro-minus, .xpro-qv-main-wrapper .xpro-plus',
                                        'property' => 'border'
                                    ),
                                ),
                                'xpro-widget-seprator14' => array(
                                    'type'    => 'raw',
                                    'content' => '<h2 class="xpro-widget-separator-heading">Quantity Buttons Input<hr></h2>',
                                ),
                                'qv_quantity_btn_input_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-popup-wrapper input[type="number"]',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_quantity_btn_input_bg_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-woo-qv-content-sec .quantity, .xpro-qv-popup-wrapper input[type="number"]',
                                        'property'     => 'background-color',
                                    ),
                                ),
                                'qv_quantity_btn_input_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'Border',
                                    'units'          => array( 'px', '%' ),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-woo-qv-content-sec .quantity',
                                        'property' => 'border'
                                    ),
                                ),
                                'xpro-widget-seprator15' => array(
                                    'type'    => 'raw',
                                    'content' => '<h2 class="xpro-widget-separator-heading">Button<hr></h2>',
                                ),
                                'qv_button_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '.xpro-qv-main-wrapper .single_add_to_cart_button',
                                    ),
                                ),
                                'qv_button_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .single_add_to_cart_button',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_button_hcolor' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color Hover', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .single_add_to_cart_button:hover,.xpro-qv-main-wrapper .single_add_to_cart_button:focus',
                                        'property'     => 'color',
                                    ),
                                ),
                                'qv_button_bg' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .single_add_to_cart_button',
                                        'property'     => 'Background-color',
                                    ),
                                ),
                                'qv_button_hbg' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Hover Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .single_add_to_cart_button:hover,.xpro-qv-main-wrapper .single_add_to_cart_button:focus',
                                        'property'     => 'Background-color',
                                    ),
                                ),
                                'qv_button_hborder' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Border Hover Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-qv-main-wrapper .single_add_to_cart_button:hover,.xpro-qv-main-wrapper .single_add_to_cart_button:focus',
                                        'property'     => 'border-color',
                                    ),
                                ),
                                'qv_button_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'Border',
                                    'units'          => array( 'px', '%' ),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .single_add_to_cart_button',
                                        'property' => 'border'
                                    ),
                                ),
                                'qv_button_item_padding' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Padding',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .single_add_to_cart_button',
                                        'property'     => 'padding'
                                    )
                                ),
                                'qv_button_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Padding',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-qv-main-wrapper .single_add_to_cart_button',
                                        'property'     => 'padding'
                                    )
                                ),
                            )
                        ),
                        'popup-Variations'       => array(
                            'title'         => __('Popup Variations', 'xpro-bb-addons'),
                            'collapsed' => true,
                            'fields'        => array(
                                'variation_label_bg_type' => array(
                                    'type'          => 'button-group',
                                    'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                                    'default'       => 'Label',
                                    'options'       => array(
                                        'Label'      => __( 'label', 'xpro-bb-addons' ),
                                        'description'      => __( 'Description', 'xpro-bb-addons' ),
                                        'price'      => __( 'Price', 'xpro-bb-addons' ),
                                    ),
                                    'toggle'        => array(
                                        'Label'      => array(
                                            'fields'        => array( 'variation_label_typography', 'variation_label_color', 'variation_label_display_style', 'variation_inline_label_width' ),
                                        ),
                                        'description'      => array(
                                            'fields'        => array( 'variation_description_typography', 'variation_description_color', 'variation_description_margin' ),
                                        ),
                                        'price'      => array(
                                            'fields'        => array( 'variation_price_typography', 'variation_price_color', 'variation_sale_price_color', 'variation_price_discount_badge_color', 'variation_price_discount_badge_bg_color', 'variation_price_discount_badge_font_size', 'variation_price_margin' ),
                                        ),
                                    )
                                ),
                                'variation_label_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '.variations label, .variations select',
                                    ),
                                ),
                                'variation_label_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.variations td label, .variations select',
                                        'property'     => 'color'
                                    ),
                                ),
                                'variation_inline_label_space_between' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Space Between',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'rules'           => array(
                                            array(
                                                'selector'     => '.variations td.value .xpro_swatches',
                                                'property'     => 'grid-gap'
                                            ),
                                            array(
                                                'selector'     => '.variations td.value .xpro_swatches .swatch',
                                                'property'     => 'margin-right'
                                            ),
                                        ),
                                    ),
                                ),
                                'variation_label_display_style' => array(
                                    'type'          => 'group-button',
                                    'label'         => __( 'Display Style', 'xpro-bb-addons' ),
                                    'default'       => 'row',
                                    'options'       => array(
                                        'row'      => __( 'Row', 'xpro-bb-addons' ),
                                        'column'      => __( 'Column', 'xpro-bb-addons' ),
                                    ),
                                    'toggle'        => array(
                                        'row'      => array(
                                            'fields'        => array( 'variation_inline_label_width' ),
                                        ),
                                    ),
                                ),
                                'variation_inline_label_width' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Label Width',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.variations th.label, .variations td.value',
                                        'property'     => 'width',
                                    ),
                                ),
                                'variation_description_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '.woocommerce-variation-description',
                                    ),
                                ),
                                'variation_description_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.woocommerce-variation-description p',
                                        'property'     => 'color'
                                    ),
                                ),
                                'variation_description_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Margin',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.woocommerce-variation-description',
                                        'property'     => 'margin'
                                    )
                                ),
                                'variation_price_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => ':is(.price, .price del, .price ins )',
                                    ),
                                ),
                                'variation_price_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => ':is(.price, .price del, .price ins )',
                                        'property'     => 'color'
                                    ),
                                ),
                                'variation_sale_price_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.price ins .amount',
                                        'property'     => 'color'
                                    ),
                                ),
                                'variation_price_discount_badge_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Discount Badge Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-badge',
                                        'property'     => 'color'
                                    ),
                                ),
                                'variation_price_discount_badge_bg_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Discount Badge Background', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-badge',
                                        'property'     => 'background-color'
                                    ),
                                ),
                                'variation_price_discount_badge_font_size' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Badge Font Size',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-badge',
                                        'property'     => 'font-size',
                                    ),
                                ),
                                'variation_price_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Margin',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.woocommerce-variation-price',
                                        'property'     => 'margin'
                                    )
                                ),
                                'xpro-widget-seprator15' => array(
                                    'type'    => 'raw',
                                    'content' => '<h2 class="xpro-widget-separator-heading">Dropdown<hr></h2>',
                                ),
                                'variation_dropdown_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Dropdown Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.variations select',
                                        'property'     => 'color',
                                    ),
                                ),
                                'variation_dropdown_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'Border',
                                    'units'          => array( 'px', '%' ),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.variations select',
                                        'property' => 'border'
                                    ),
                                ),
                                'xpro-widget-seprator16' => array(
                                    'type'    => 'raw',
                                    'content' => '<h2 class="xpro-widget-separator-heading"><hr></h2>',
                                ),
                                'variation_item_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Space Between',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.variations tr',
                                        'property'     => 'margin'
                                    )
                                ),
                            )
                        ),
                        'popup-swatches'       => array(
                            'title'         => __('Popup Swatches', 'xpro-bb-addons'),
                            'collapsed' => true,
                            'fields'        => array(
                                'variation_swatch_bg_type' => array(
                                    'type'          => 'button-group',
                                    'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                                    'default'       => 'Label',
                                    'options'       => array(
                                        'color'      => __( 'Color', 'xpro-bb-addons' ),
                                        'image'      => __( 'Image', 'xpro-bb-addons' ),
                                        'label'      => __( 'Label', 'xpro-bb-addons' ),
                                    ),
                                    'toggle'        => array(
                                        'color'      => array(
                                            'fields'        => array( 'variation_swatch_color_width', 'variation_swatch_color_height', 'variation_swatch_color_label_border', 'variation_swatch_color_selected_label_border', 'variation_swatch_color_border_radius' ),
                                        ),
                                        'image'      => array(
                                            'fields'        => array( 'variation_swatch_image_width', 'variation_swatch_image_height', 'variation_swatch_image_border_radius', 'variation_swatch_image_label_border', 'variation_swatch_image_selected_label_border' ),
                                        ),
                                        'label'      => array(
                                            'fields'        => array( 'variation_swatch_label_typography', 'variation_swatch_label_text_color', 'variation_swatch_label_background_color', 'variation_price_discount_badge_color', 'variation_swatch_label_label_border', 'variation_swatch_label_padding' ),
                                        ),
                                    )
                                ),
                                'variation_swatch_color_width' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Swatch Width',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro_swatches .swatch.swatch_color',
                                        'property'     => 'width',
                                    ),
                                ),
                                'variation_swatch_color_height' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Swatch Height',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro_swatches .swatch.swatch_color',
                                        'property'     => 'height',
                                    ),
                                ),
                                'variation_swatch_color_border_radius' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Border Radius',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro_swatches .swatch_color',
                                        'property'     => 'border-radius',
                                    ),
                                ),
                                'variation_swatch_color_label_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'Border',
                                    'units'          => array( 'px', '%' ),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro_swatches .swatch_color',
                                        'property' => 'border'
                                    ),
                                ),
                                'variation_swatch_color_selected_label_border' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Selected Border', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro_swatches .swatch_color.selected',
                                        'property'     => 'border-color'
                                    ),
                                ),
                                'variation_swatch_image_width' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Swatch Width',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro_swatches .swatch.swatch_image',
                                        'property'     => 'width',
                                    ),
                                ),
                                'variation_swatch_image_height' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Swatch Height',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro_swatches .swatch.swatch_image',
                                        'property'     => 'height',
                                    ),
                                ),
                                'variation_swatch_image_border_radius' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Border Radius',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro_swatches .swatch_image',
                                        'property'     => 'border-radius',
                                    ),
                                ),
                                'variation_swatch_image_label_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'Border',
                                    'units'          => array( 'px', '%' ),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro_swatches .swatch_image',
                                        'property' => 'border'
                                    ),
                                ),
                                'variation_swatch_image_selected_label_border' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Selected Border', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro_swatches .swatch_image.selected',
                                        'property'     => 'border-color'
                                    ),
                                ),
                                'variation_swatch_label_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '.xpro_swatches .swatch_label',
                                    ),
                                ),
                                'variation_swatch_label_text_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Text Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro_swatches .swatch_label',
                                        'property'     => 'color'
                                    ),
                                ),
                                'variation_swatch_label_background_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro_swatches .swatch_label',
                                        'property'     => 'background-color'
                                    ),
                                ),
                                'variation_price_discount_badge_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Selected Border', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro_swatches .swatch_label.selected',
                                        'property'     => 'border-color'
                                    ),
                                ),
                                'variation_swatch_label_label_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'Border',
                                    'units'          => array( 'px', '%' ),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro_swatches .swatch_label',
                                        'property' => 'border'
                                    ),
                                ),
                                'variation_swatch_label_padding' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Padding',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro_swatches .swatch_label',
                                        'property'     => 'padding'
                                    )
                                ),

                            )
                        ),
                    ),
                ),
            ),
        )
    );

    /**
     * Register a settings form for Pagination Styles.
     */
    FLBuilder::register_settings_form(
        'xpro_products_pagination_form',
        array(
            'title' => __( 'Pagination Styles', 'xpro-bb-addons' ),
            'tabs'  => array(
                'general' => array(
                    'title'    => __( 'Content', 'xpro-bb-addons' ),
                    'sections' => array(
                        'general'       => array(
                            'title'         => __('Pagination', 'xpro-bb-addons'),
                            'fields'        => array(
                                'prev_label' => array(
                                    'type'          => 'text',
                                    'label'         => __( 'Prev Label', 'fl-builder' ),
                                    'default'       => 'Prev',
                                ),
                                'next_label' => array(
                                    'type'          => 'text',
                                    'label'         => __( 'Next Label', 'fl-builder' ),
                                    'default'       => 'Next',
                                ),
                                'posts_per_page'     => array(
                                    'type'    => 'unit',
                                    'label'   => __( 'Posts Per Page', 'xpro-bb-addons' ),
                                    'default' => 10,
                                    'slider'  => true,
                                ),
                                'arrow' => array(
                                    'type'    => 'select',
                                    'label'   => __('Arrows Type', 'xpro-bb-addons'),
                                    'default' => 'fas fa-arrow-left',
                                    'options' => array(
                                        'fas fa-arrow-left' => __('Arrow', 'xpro-bb-addons'),
                                        'fas fa-angle-left' => __('Angle', 'xpro-bb-addons'),
                                        'fas fa-angle-double-left' => __('Double Angle', 'xpro-bb-addons'),
                                        'fas fa-chevron-left' => __('Chevron', 'xpro-bb-addons'),
                                        'fas fa-chevron-circle-left' => __('Chevron Circle', 'xpro-bb-addons'),
                                        'fas fa-caret-left' => __('Caret', 'xpro-bb-addons'),
                                        'xi xi-long-arrow-left' => __('Long Arrow', 'xpro-bb-addons'),
                                        'fas fa-arrow-circle-left' => __('Arrow Circle', 'xpro-bb-addons'),
                                    ),
                                ),
                            )
                        ),
                        'styl-pagination'       => array(
                            'title'         => __('Pagination', 'xpro-bb-addons'),
                            'collapsed' => true,
                            'fields'        => array(
                                'text-align' => array(
                                    'type'    => 'align',
                                    'label'   => 'Alignment',
                                    'default' => 'center',
                                    'responsive' => true,
                                    'preview' => array(
                                        'type'       => 'css',
                                        'selector'   => '.xpro-elementor-post-pagination',
                                        'property'   => 'justify-content',
                                    ),
                                ),
                                'pagination_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '.xpro-elementor-post-pagination .page-numbers',
                                    ),
                                ),
                                'pagination_space_between' => array(
                                    'type'   => 'unit',
                                    'label'  => 'Space Between',
                                    'units'  => array( 'px' ),
                                    'default_unit' => 'px',
                                    'responsive' => true,
                                    'slider' => true,
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'     => '.xpro-elementor-post-pagination',
                                        'property'     => 'grid-gap',
                                    ),
                                ),
                                'pagination_bg_type' => array(
                                    'type'          => 'button-group',
                                    'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                                    'default'       => 'none',
                                    'options'       => array(
                                        'none'      => __( 'None', 'xpro-bb-addons' ),
                                        'normal'      => __( 'Normal', 'xpro-bb-addons' ),
                                        'hover'      => __( 'Hover', 'xpro-bb-addons' ),
                                        'active'      => __( 'Active', 'xpro-bb-addons' ),
                                    ),
                                    'toggle'        => array(
                                        'normal'      => array(
                                            'fields'        => array( 'pagination_color', 'pagination_bg_color' ),
                                        ),
                                        'hover'      => array(
                                            'fields'        => array(  'pagination_hover_color', 'pagination_bg_hover_color' ),
                                        ),
                                        'active'      => array(
                                            'fields'        => array(  'pagination_active_color', 'pagination_bg_arctive_color' ),
                                        ),
                                    )
                                ),
                                'pagination_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => ' .xpro-elementor-post-pagination .page-numbers',
                                        'property'     => 'color'
                                    ),
                                ),
                                'pagination_bg_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-elementor-post-pagination .page-numbers',
                                        'property'     => 'background-color'
                                    ),
                                ),
                                'pagination_hover_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-elementor-post-pagination .page-numbers:hover',
                                        'property'     => 'color'
                                    ),
                                ),
                                'pagination_bg_hover_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-elementor-post-pagination .page-numbers:hover',
                                        'property'     => 'background-color'
                                    ),
                                ),
                                'pagination_active_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-elementor-post-pagination .page-numbers.current',
                                        'property'     => 'color'
                                    ),
                                ),
                                'pagination_bg_arctive_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-elementor-post-pagination .page-numbers.current',
                                        'property'     => 'background-color'
                                    ),
                                ),
                                'pagination_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'Border',
                                    'units'          => array( 'px', '%' ),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-elementor-post-pagination .page-numbers',
                                        'property' => 'border'
                                    ),
                                ),
                                'pagination_padding' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Padding',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-elementor-post-pagination .page-numbers',
                                        'property'     => 'padding'
                                    )
                                ),
                                'pagination_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       => 'Margin',
                                    'responsive' => true,
                                    'units'          => array( 'px', '%' ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '.xpro-elementor-post-pagination',
                                        'property'     => 'margin'
                                    )
                                ),
                            )
                        ),
                    ),
                ),
            ),
        )
    );

    /**
     * Register a settings form for Badge Styles.
     */
    FLBuilder::register_settings_form(
        'xpro_products_badges_form',
        array(
            'title' => __( 'Badges Styles', 'xpro-bb-addons' ),
            'tabs'  => array(
                'general' => array(
                    'title'    => __( 'Content', 'xpro-bb-addons' ),
                    'sections' => array(
                        'general'       => array(
                            'title'         => __('Pagination', 'xpro-bb-addons'),
                            'fields'        => array(
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
                    ),
                ),
            ),
        )
    );

} else {

    class XproWooProductGridModuleWooNotExist extends FLBuilderModule {

        /**
         * @return void
         */
        public function __construct() {
            parent::__construct(
                array(
                    'name'          => __('Woo Product Grid', 'xpro-bb-addons'),
                    'description'   => __('An awesome addition by Xpro team!', 'xpro-bb-addons'),
                    'group'         => XPRO_Plugins_Helper::$branding_modules,
                    'category'      => XPRO_Plugins_Helper::$woo_modules,
                    'dir'           => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-woo-product-grid/',
                    'url'           => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-woo-product-grid/',
                    'partial_refresh' 	=> true,
                )
            );
        }
    }

    FLBuilder::register_module(
        'XproWooProductGridModuleWooNotExist',
        array(
            'general-info' => array(
                'title'       => __( 'General', 'xpro' ),
                'description' => __( 'Please Install Woocommerce Plugin to use this Module.', 'xpro' ),
            ),
        )
    );

}
