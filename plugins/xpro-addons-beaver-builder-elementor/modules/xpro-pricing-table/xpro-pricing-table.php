<?php

/**
 * @class XproPricingTable
 *
 */

if ( ! class_exists( 'XproPricingTable' ) ) {

    class XproPricingTable extends FLBuilderModule {

        /**
         * @method __construct
         *
         */
        public function __construct()
        {
            parent::__construct(array(
                'name'            => __( 'Pricing Table', 'xpro-bb-addons' ),
                'description' 	  => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
                'group'           => XPRO_Plugins_Helper::$branding_modules,
                'category'        => XPRO_Plugins_Helper::$creative_modules,
                'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-pricing-table/',
                'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-pricing-table/',
                'partial_refresh' => true,
            ));

        }

        /**
         * Returns link rel based on settings
         * 
         */
        public function get_rel()
        {
            $rel = array();
            if ('_blank' == $this->settings->pricing_link_target) {
                $rel[] = 'noopener';
            }
            if (isset($this->settings->pricing_link_nofollow) && 'yes' == $this->settings->pricing_link_nofollow) {
                $rel[] = 'nofollow';
            }
            $rel = implode(' ', $rel);
            if ($rel) {
                $rel = ' rel="' . $rel . '" ';
            }
            return $rel;
        }

    }

    /**
     * Register the module and its form settings.
     */
    FLBuilder::register_module('XproPricingTable', array(
        'general'       => array(
            'title'         => __('General', 'xpro-bb-addons'),
            'sections'      => array(
                'heading'       => array(
                    'title'         => __('Heading', 'xpro-bb-addons'),
                    'fields'        => array(
                        'pricing_title_tag' => array(
                            'type'          => 'select',
                            'label'         => __( 'HTML Title Tag', 'xpro-bb-addons' ),
                            'default'       => 'h3',
                            'options'       => array(
                                'h1'      => __( 'H1', 'xpro-bb-addons' ),
                                'h2'      => __( 'H2', 'xpro-bb-addons' ),
                                'h3'      => __( 'H3', 'xpro-bb-addons' ),
                                'h4'      => __( 'H4', 'xpro-bb-addons' ),
                                'h5'      => __( 'H5', 'xpro-bb-addons' ),
                                'h6'      => __( 'H6', 'xpro-bb-addons' ),
                            ),
                        ),
                        'pricing_title' => array(
                            'type'          => 'text',
                            'label'         => __( 'Title', 'xpro-bb-addons' ),
                            'placeholder'   => __( 'Heading Title', 'xpro-bb-addons' ),
                            'default'   => __( 'Basic', 'xpro-bb-addons' ),
                        ),
                    )
                ),
                'image-icon'       => array(
                    'title'         => __('Icon/Image', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_media_type' => array(
                            'type'    => 'button-group',
                            'label'   => 'Media Type',
                            'default' => 'pricing_icon',
                            'options'       => array(
                                'pricing_icon'      => __( 'Icon'),
                                'pricing_image'      => __( 'Image'),

                            ),
                            'toggle'        => array(
                                'pricing_icon'      => array(
                                    'fields'        => array( 'pricing_icon', 'pricing_title_position',
                                        'icon_color_style_heading', 'pricing_icon_size_style_heading'),
                                ),
                                'pricing_image'      => array(
                                    'fields'        => array( 'pricing_image', 'pricing_title_position',
                                        'style_media_image_background_size'),
                                ),

                            )
                        ),
                        'pricing_icon' => array(
                            'type'          => 'icon',
                            'label'         => __( 'Icon Field', 'xpro-bb-addons' ),
                            'show_remove'   => true,
                            'default' => 'far fa-paper-plane'
                        ),
                        'pricing_image' => array(
                            'type'          => 'photo',
                            'label'         => __( 'Image Field', 'xpro-bb-addons' ),
                            'show_remove'   => true,
                            'toggle'        => array(
                            )
                        ),
                        'pricing_title_position' => array(
                            'type'          => 'button-group',
                            'label'         => __( 'Position', 'xpro-bb-addons' ),
                            'default'       => 'after_title',
                            'options'       => array(
                                'before_title'      => __( 'Before Title', 'xpro-bb-addons' ),
                                'after_title'      => __( 'After Title', 'xpro-bb-addons' )
                            ),
                        ),
                    )
                ),
                'price'       => array(
                    'title'         => __('Price', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_currency' => array(
                            'type'          => 'text',
                            'label'         => __( 'Currency', 'xpro-bb-addons' ),
                            'default'       => __( '$', 'xpro-bb-addons' ),
                        ),
                        'pricing_price' => array(
                            'type'          => 'text',
                            'label'         => __( 'Price', 'xpro-bb-addons' ),
                            'default'       => __( '39.99', 'xpro-bb-addons' ),
                        ),
                        'pricing_period' => array(
                            'type'          => 'text',
                            'label'         => __( 'Period', 'xpro-bb-addons' ),
                            'default'       => __( '/ Month', 'xpro-bb-addons' ),
                        ),
                        'pricing_position' => array(
                            'type'          => 'button-group',
                            'label'         => __( 'Position', 'xpro-bb-addons' ),
                            'default'       => 'after_featured',
                            'options'       => array(
                                'after_featured'      => __( 'After Features', 'xpro-bb-addons' ),
                                'before_featured'      => __( 'Before Features', 'xpro-bb-addons' )

                            ),
                        ),
                    )
                ),
                'description'       => array(
                    'title'         => __('Description', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_text_badge' => array(
                            'type'    => 'button-group',
                            'label'   => 'Transform',
                            'default' => 'show',
                            'options' => array(
                                'none'    =>  __( 'OFF', 'xpro-bb-addons' ),
                                'show'    => __( 'ON', 'xpro-bb-addons' )
                            ),
                            'toggle'        => array(
                                'none'      => array(
                                ),
                                'show'      => array(
                                    'fields'        => array( 'pricing_text' ),
                                ),
                            ),
                        ),
                        'pricing_text' => array(
                            'type'          => 'editor',
                            'media_buttons' => true,
                            'wpautop'       => false,
                            'default'  => __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'xpro-bb-addons'),
                            'connections' => array( 'string', 'html' ),
                        ),
                    )
                ),
                'button'       => array(
                    'title'         => __('Button', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_button' => array(
                            'type'          => 'text',
                            'label'         => __( 'Title', 'xpro-bb-addons' ),
                            'default'   => 'Get Started'  ,
                        ),
                        'pricing_link' => array(
                            'type'          => 'link',
                            'label'         =>  __( 'Link', 'xpro-bb-addons' ),
                            'show_target'   => true,
                            'show_nofollow' => true,
                            'placeholder'   => __( 'http://example.com', 'xpro-bb-addons' ),
                        ),
                        'pricing_button_position' => array(
                            'type'          => 'button-group',
                            'label'         => __( 'Position', 'xpro-bb-addons' ),
                            'default'       => 'after_feature',
                            'options'       => array(
                                'before_feature'      => __( 'Before Description', 'xpro-bb-addons' ),
                                'after_feature'      => __( 'After Features', 'xpro-bb-addons' )
                            ),
                        ),
                    )
                ),
                'badge'       => array(
                    'title'         => __('Badge', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'badge' => array(
                            'type'          => 'text',
                            'label'         => __( 'Badge', 'xpro-bb-addons' ),
                            'placeholder'   => __( 'Type Icon Badge Text', 'xpro-bb-addons' ),
                            'default'   => __( 'Featured', 'xpro-bb-addons' ),
                        ),
                    )
                ),
            )
        ),
        'list'       => array(
            'title'         => __('List', 'xpro-bb-addons'),
            'sections'      => array(
                'features'       => array(
                    'title'         => __('Features', 'xpro-bb-addons'),
                    'fields'        => array(
                        'pricing_features_title_tag' => array(
                            'type'          => 'select',
                            'label'         => __( 'HTML Title Tag', 'xpro-bb-addons' ),
                            'default'       => 'h3',
                            'options'       => array(
                                'h1'      => __( 'H1', 'xpro-bb-addons' ),
                                'h2'      => __( 'H2', 'xpro-bb-addons' ),
                                'h3'      => __( 'H3', 'xpro-bb-addons' ),
                                'h4'      => __( 'H4', 'xpro-bb-addons' ),
                                'h5'      => __( 'H5', 'xpro-bb-addons' ),
                                'h6'      => __( 'H6', 'xpro-bb-addons' ),
                            ),
                        ),
                        'pricing_features_title' => array(
                            'type'          => 'text',
                            'label'         => __( 'Title', 'xpro-bb-addons' ),
                            'placeholder'   => __( 'Features Title', 'xpro-bb-addons' ),
                            'default'   => __( '', 'xpro-bb-addons' ),
                        ),
                        'pricing_features_form' => array(
                            'type'          => 'form',
                            'label'         => __('Features List', 'xpro-bb-addons'),
                            'multiple'      => true,
                            'form'          => 'features_list_form',
                            'preview_text'  => 'pricing_feature_list_title',
                            'default'      => array(
                                array(
                                    'pricing_feature_icon'   => 'fas fa-check',
                                    'pricing_feature_list_title' => __('Feature List 1', 'xpro-bb-addons'),
                                    'feature_status' => 'active',
                                ),
                                array(
                                    'pricing_feature_icon'   => 'fas fa-check',
                                    'pricing_feature_list_title' => __('Feature List 2', 'xpro-bb-addons'),
                                    'feature_status' => 'active',
                                    'pricing_tooltip_text'   => __('Tooltip Text Here', 'xpro-bb-addons'),
                                ),
                                array(
                                    'pricing_feature_icon'   => 'fas fa-times',
                                    'pricing_feature_list_title' => __('Feature List 3', 'xpro-bb-addons'),
                                    'feature_status' => 'inactive',
                                ),
                                array(
                                    'pricing_feature_icon'   => 'fas fa-times',
                                    'pricing_feature_list_title' => __('Feature List 4', 'xpro-bb-addons'),
                                    'feature_status' => 'inactive',
                                ),
                            ),
                        )
                    )
                ),
            )
        ),
        'style'       => array(
            'title'         => __('Style', 'xpro-bb-addons'),
            'sections'      => array(
                'general'       => array(
                    'title'         => __('General', 'xpro-bb-addons'),
                    'fields'        => array(
                        'pricing_alignment_style' => array(
                            'type'    => 'align',
                            'label'   => 'Text Align',
                            'default' => 'left'
                        ),
                        'pricing_color_style' => array(
                            'type'          => 'color',
                            'label'         => __( 'Text Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_background_type_style' => array(
                            'type'          => 'button-group',
                            'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                            'default'       => 'none',
                            'options'       => array(
                                'none'      => __( 'None', 'xpro-bb-addons' ),
                                'color'      => __( 'Color', 'xpro-bb-addons' ),
                                'gradient'      => __( 'Gradient', 'xpro-bb-addons' )
                            ),
                            'toggle'        => array(
                                'color'      => array(
                                    'fields'        => array( 'pricing_background_color_style'),
                                ),
                                'gradient'      => array(
                                    'fields'        => array( 'pricing_gradient_style'),
                                ),
                            )
                        ),
                        'pricing_background_color_style' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-inner',
                                'property' => 'background-color',
                            ),
                        ),
                        'pricing_gradient_style' => array(
                            'type'    => 'gradient',
                            'label'   => 'Gradient',
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-inner',
                                'property' => 'background-image',
                            ),
                        ),
                        'style_general_border' => array(
                            'type'       => 'border',
                            'label'      => 'Border',
                            'responsive' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-inner',
                            ),
                        ),
                        'style_general_margin' => array(
                            'type'        => 'dimension',
                            'label'       =>  __( 'Margin', 'xpro-bb-addons' ),
                            'units'          => array( 'px', 'vw', '%' ),
                            'slider'  => true,
                            'responsive'  => true,
                            'default_unit' => 'px',
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-inner',
                                'property' => 'margin',
                            ),
                        ),
                        'style_general_padding' => array(
                            'type'        => 'dimension',
                            'label'       =>  __( 'Padding', 'xpro-bb-addons' ),
                            'units'          => array( 'px', 'vw', '%' ),
                            'slider'  => true,
                            'responsive'  => true,
                            'default_unit' => 'px',
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-inner',
                                'property' => 'padding',
                            ),
                        ),
                    )
                ),
                'heading'       => array(
                    'title'         => __('Heading', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_display_style_heading' => array(
                            'type'    => 'button-group',
                            'label'   => 'Display',
                            'default' => 'block',
                            'options' => array(
                                'block'    => __( 'Block', 'xpro-bb-addons' ),
                                'inline-block'    => __( 'Inline Block', 'xpro-bb-addons' ),
                            ),
                        ),
                        'pricing_color_heading' => array(
                            'type'          => 'color',
                            'label'         => __( 'Text Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-title',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_background_type' => array(
                            'type'    => 'button-group',
                            'label'   => 'Background-Type',
                            'default' => 'color',
                            'options'       => array(
                                'color'      => __( 'Color', 'xpro-bb-addons' ),
                                'gradient'      => __( 'Gradient', 'xpro-bb-addons' )
                            ),
                            'toggle'        => array(
                                'color'      => array(
                                    'fields'        => array( 'pricing_background_heading' )
                                ),
                                'gradient'      => array(
                                    'fields'        => array( 'pricing_gradient_heading' )
                                ),
                            ),
                        ),
                        'pricing_background_heading' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-title',
                                'property' => 'background-color',
                            ),
                        ),
                        'pricing_gradient_heading' => array(
                            'type'    => 'gradient',
                            'label'   => 'Gradient',
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-title',
                                'property' => 'background-image',
                            ),
                        ),
                        'pricing_border_style_heading' => array(
                            'type'       => 'border',
                            'label'      => 'Border',
                            'responsive' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-title',
                            ),
                        ),
                        'pricing_padding_style_heading' => array(
                            'type'        => 'dimension',
                            'label'       => 'Padding',
                            'units'          => array( 'px', '%' ),
                            'slider'  => true,
                            'responsive'  => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-title',
                                'property'     => 'padding'
                            ),

                        ),
                        'pricing_margin_style_heading' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margin',
                            'units'          => array( 'px', '%' ),
                            'slider'  => true,
                            'responsive'  => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-title',
                                'property'     => 'margin'
                            ),
                        ),
                    )
                ),
                'image-icon'       => array(
                    'title'         => __('Icon/Image', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'icon_color_style_heading' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-icon',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_icon_size_style_heading' => array(
                            'type'         => 'unit',
                            'label'        => 'Size',
                            'units'          => array( 'px', 'rem', 'em' ),
                            'responsive' => 'true',
                            'slider'         => true,
                            'default_unit' => 'px',
                            'preview'    => array(
                                'type'          => 'css',
                                'selector'      => '.xpro-pricing-item-icon',
                                'property'      => 'font-size',
                            ),
                        ),
                        'style_media_image_background_size' => array(
                            'type'        => 'unit',
                            'label'       => 'Width',
                            'units'          => array( 'px', '%' ),
                            'slider'  => true,
                            'responsive' => true ,
                            'default_unit' => 'px',
                            'preview'       => array(
                                'type'          => 'css',
                                'rules'           => array(
                                    array(
                                        'selector'      => '.xpro-pricing-media > img',
                                        'property'      => 'width',
                                        'unit'          => 'px'
                                    ),
                                    array(
                                        'selector'      => '.xpro-pricing-media > img',
                                        'property'      => 'height',
                                        'unit'          => 'px'
                                    ),
                                ),
                            ),
                        ),
                        'pricing_icon_margin_style_heading' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margin',
                            'units'          => array( 'px', '%' ),
                            'responsive'  => true,
                            'slider'  => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-icon, .xpro-pricing-media',
                                'property'     => 'margin'
                            ),
                        ),
                    )
                ),
                'price'       => array(
                    'title'         => __('Price', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_display_price' => array(
                            'type'    => 'button-group',
                            'label'   => 'Display',
                            'default' => 'inline-block',
                            'options' => array(
                                'block'    =>  __('Block', 'xpro-bb-addons'),
                                'inline-block'    =>  __('Inline', 'xpro-bb-addons'),
                            ),
                            'preview' => array(
                                'type'       => 'css',
                                'selector'   => '.xpro-pricing-price-tag',
                                'property'   => 'display',
                            ),
                        ),
                        'pricing_price_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'default'  => '32d39b',
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-price-tag',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_price_margin' => array(
                            'type'        => 'dimension',
                            'label'       =>  __( 'Margin', 'xpro-bb-addons' ),
                            'units'          => array( 'px', 'vw', '%' ),
                            'responsive'  => true,
                            'slider'  => true,
                            'default_unit' => 'px',
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-price-box',
                                'property' => 'margin',
                            ),
                        ),
                        'xpro-widget-seprator1' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Currency<hr></h2>',
                        ),
                        'pricing_price_currency_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-currency',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_price_currency_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margins',
                            'units'       => array( 'px', '%' ),
                            'responsive'  => true,
                            'slider'  => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-currency',
                                'property'     => 'margin'
                            ),
                        ),
                        'xpro-widget-seprator2' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Period<hr></h2>',
                        ),
                        'pricing_price_period_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-price-period',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_price_period_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margins',
                            'units'       => array( 'px', '%' ),
                            'responsive'  => true,
                            'slider'  => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-price-period',
                                'property'     => 'margin'
                            ),
                        ),
                    )
                ),
                'features'       => array(
                    'title'         => __('Features', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_feature_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Wrapper Margins',
                            'units'       => array( 'px', '%' ),
                            'responsive'  => true,
                            'slider'  => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-features',
                                'property'     => 'margin'
                            ),
                        ),
                        'xpro-widget-seprator3' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Title<hr></h2>',
                        ),
                        'pricing_feature_title_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-features-title',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_feature_title_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margins',
                            'units'       => array( 'px', '%' ),
                            'responsive'  => true,
                            'slider'  => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-features-title',
                                'property'     => 'margin'
                            ),
                        ),
                        'xpro-widget-seprator4' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">List<hr></h2>',
                        ),
                        'pricing_feature_list_icon_size' => array(
                            'type'         => 'unit',
                            'label'        => 'Icon Size',
                            'units'          => array( 'px', 'rem', 'em' ),
                            'responsive' =>  true,
                            'slider'         => true,
                            'default_unit' => 'px',
                            'preview'       => array(
                                'type'          => 'css',
                                'rules'           => array(
                                    array(
                                        'selector'      => '.xpro-pricing-feature-icon i',
                                        'property'      => 'font-size',
                                        'unit'          => 'px'
                                    ),
                                    array(
                                        'selector'     => '.xpro-pricing-feature-icon',
                                        'property'     => 'width',
                                        'unit'          => 'px'
                                    ),
                                ),
                            ),
                        ),
                        'pricing_feature_icon_space' => array(
                            'type'         => 'unit',
                            'label'        => 'Icon Space',
                            'units'          => array( 'px'),
                            'default_unit' => 'px',
                            'slider'      =>    true,
                            'responsive' =>  true,
                            'preview'    => array(
                                'type'          => 'css',
                                'selector'      => '.xpro-pricing-feature-icon i',
                                'property'      => 'margin-right',
                            ),
                        ),
                        'pricing_feature_icon_position' => array(
                            'type'         => 'unit',
                            'label'        => 'Icon Vertical Spacing',
                            'units'          => array( 'px'),
                            'slider'      =>    true,
                        ),
                        'pricing_feature_content_align' => array(
                            'type'    => 'align',
                            'label'   => 'Content Alignment',
                        ),
                        'pricing_feature_icon_space_between' => array(
                            'type'         => 'unit',
                            'label'        => 'Space Between',
                            'units'          => array( 'px'),
                            'default_unit' => 'px',
                            'responsive' => true,
                            'slider'      =>    true,
                            'preview'    => array(
                                'type'          => 'css',
                                'selector'      => '.xpro-pricing-features-list li',
                                'property'      => 'margin-bottom',
                            ),
                        ),
                        'pricing_feature_active_inactive_Status' => array(
                            'type'          => 'button-group',
                            'label'         => __( 'Status Color', 'xpro-bb-addons' ),
                            'default'       => 'active',
                            'options'       => array(
                                'active'      => __( 'Active', 'xpro-bb-addons' ),
                                'inactive'      => __( 'Inactive', 'xpro-bb-addons' ),

                            ),
                            'toggle'        => array(
                                'active'      => array(
                                    'fields'        => array( 'pricing_feature_list_active_color', 'pricing_feature_list_active_icon_color' )
                                ),
                                'inactive'      => array(
                                    'fields'        => array( 'pricing_feature_list_inactive_color', 'pricing_feature_list_inactive_icon_color' )
                                ),
                            ),
                        ),
                        'pricing_feature_list_active_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => 'li.active',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_feature_list_active_icon_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Icon', 'xpro-bb-addons' ),
                            'default'  => '32d39b',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => 'li.active .xpro-pricing-feature-icon',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_feature_list_inactive_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'default'  => 'eaeaea',
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => 'li.inactive',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_feature_list_inactive_icon_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Icon', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => 'li.inactive .xpro-pricing-feature-icon',
                                'property' => 'color',
                            ),
                        ),
                        'xpro-widget-seprator5' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Tooltip<hr></h2>',
                        ),
                        'pricing_feature_tooltip_icon_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Icon color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-tooltip-toggle',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_feature_tooltip_icon_background_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-tooltip-toggle',
                                'property' => 'background-color',
                            ),
                        ),
                        'pricing_feature_tooltip_content_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Content color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-tooltip',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_feature_tooltip_content_background' => array(
                            'type'          => 'color',
                            'label'         => __( 'Content Background', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'       => array(
                                'type'          => 'css',
                                'rules'           => array(
                                    array(
                                        'selector'  => '.xpro-pricing-tooltip',
                                        'property' => 'background-color',
                                    ),
                                    array(
                                        'selector'     => '.xpro-pricing-tooltip:after',
                                        'property'     => 'border-right-color',
                                    ),
                                ),
                            ),
                        ),
                        'pricing_feature_tooltip_width' => array(
                            'type'         => 'unit',
                            'label'        => 'Tooltip Width',
                            'units'          => array( 'px', 'vw', '%' ),
                            'default_unit' => 'px',
                            'slider' => array(
                                'px'    => array(
                                    'min' => 200,
                                    'max' => 1000,
                                    'step'    => 10,
                                ),
                                'vw'    => array(
                                    'min' => 0,
                                    'max' => 100,
                                    'step'    => 10,
                                ),
                                '%'    => array(
                                    'min' => 0,
                                    'max' => 100,
                                    'step'    => 10,
                                ),
                            ),
                            'preview'    => array(
                                'type'          => 'css',
                                'selector'      => '.xpro-pricing-tooltip',
                                'property'      => 'width',
                            ),
                        ),
                        'pricing_feature_tooltip_content_padding' => array(
                            'type'        => 'dimension',
                            'label'       => 'Content Padding',
                            'units'          => array( 'px', '%' ),
                            'responsive'  => true,
                            'slider'  => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-tooltip',
                                'property'     => 'padding'
                            )
                        ),
                    )
                ),
                'description'       => array(
                    'title'         => __('Description', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_description_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Text Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-text',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_description_width' => array(
                            'type'         => 'unit',
                            'label'        => 'Max Width',
                            'units'          => array( 'px', '%' ),
                            'default_unit' => 'px', // Optional
                            'slider' => array(
                                'px'    => array(
                                    'min' => 400,
                                    'max' => 1000,
                                    'step'    => 1,
                                ),
                                '%'    => array(
                                    'min' => 400,
                                    'max' => 1000,
                                    'step'    => 1,
                                ),
                            ),
                            'preview'    => array(
                                'type'          => 'css',
                                'selector'      => '.xpro-pricing-item-text',
                                'property'      => 'max-width',
                            ),
                        ),
                        'pricing_description_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margins',
                            'units'       => array( 'px', '%' ),
                            'responsive'  => true,
                            'slider'  => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-text',
                                'property'     => 'margin'
                            ),
                        ),
                    )
                ),
                'separator'       => array(
                    'title'         => __('Separator', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_display_separator' => array(
                            'type'    => 'button-group',
                            'label'   => 'Enable',
                            'default' => 'show',
                            'options' => array(
                                'show'    => __('ON', 'xpro-bb-addons'),
                                'hide'    =>__('OFF', 'xpro-bb-addons'),
                            ),
                            'toggle'        => array(
                                'show'      => array(
                                    'fields'        => array( 'pricing_separator_color', 'pricing_separator_margin','pricing_separator_width','pricing_separator_height' ),
                                ),
                            ),
                        ),
                        'pricing_separator_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'default'       => 'dbdbdb',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-separator',
                                'property' => 'background-color',
                            ),
                        ),
                        'pricing_separator_width' => array(
                            'type'         => 'unit',
                            'label'        => 'Width',
                            'units'          => array( 'px', '%' ),
                            'default'  => 100,
                            'default_unit' => '%',
                            'slider' => array(
                                'px'    => array(
                                    'min' => 0,
                                    'max' => 1000,
                                    'step'    => 1,
                                ),
                                '%'    => array(
                                    'min' => 0,
                                    'max' => 100,
                                    'step'    => 1,
                                ),
                            ),
                            'preview'    => array(
                                'type'          => 'css',
                                'selector'      => '.xpro-pricing-item-separator',
                                'property'      => 'width',
                            ),
                        ),
                        'pricing_separator_height' => array(
                            'type'         => 'unit',
                            'label'        => 'Height',
                            'units'          => array( 'px', '%' ),
                            'default_unit' => 'px',
                            'slider' => array(
                                'px'    => array(
                                    'min' => 1,
                                    'max' => 10,
                                    'step'    => 1,
                                ),
                                '%'    => array(
                                    'min' => 1,
                                    'max' => 10,
                                    'step'    => 1,
                                ),
                            ),
                            'preview'    => array(
                                'type'          => 'css',
                                'selector'      => '.xpro-pricing-item-separator',
                                'property'      => 'height',
                            ),
                        ),
                        'pricing_separator_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margin',
                            'default' => 'px',
                            'units'       => array( 'px', '%' ),
                            'responsive'  => true,
                            'slider'  => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-separator',
                                'property'     => 'margin'
                            ),
                        ),
                    )
                ),
                'button'       => array(
                    'title'         => __('Button', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_display_button' => array(
                            'type'          => 'button-group',
                            'label'         => __( 'Display', 'xpro-bb-addons' ),
                            'default'       => 'inline-block',
                            'options'       => array(
                                'inline-block'      => __( 'Inline Block', 'xpro-bb-addons' ),
                                'block'      => __( 'Block', 'xpro-bb-addons' ),
                            ),
                        ),
                        'pricing_button_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-btn',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_button_color_hover' => array(
                            'type'          => 'color',
                            'label'         => __( 'Color Hover', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-btn:hover',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_background_style_type_button' => array(
                            'type'    => 'button-group',
                            'label'   => 'Background Type',
                            'default' => 'flat',
                            'options'       => array(
                                'flat'      => __( 'Flat', 'xpro-bb-addons' ),
                                'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
                            ),
                            'toggle'        => array(
                                'flat'      => array(
                                    'fields'        => array('pricing_button_background_color', )
                                ),
                                'gradient'      => array(
                                    'fields'        => array('pricing_button_background_gradient',)
                                ),
                            ),
                        ),
                        'pricing_button_background_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-btn',
                                'property' => 'background-color',
                            ),
                        ),
                        'pricing_button_background_gradient' => array(
                            'type'          => 'gradient',
                            'label'         => __( 'Gradient', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-btn',
                                'property' => 'background-image',
                            ),
                        ),
                        'pricing_background_style_type_hover_button' => array(
                            'type'    => 'button-group',
                            'label'   => 'Background Type Hover',
                            'default' => 'flat',
                            'options'       => array(
                                'flat'      => __( 'Flat', 'xpro-bb-addons' ),
                                'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
                            ),
                            'toggle'        => array(
                                'flat'      => array(
                                    'fields'        => array('pricing_button_background_color_hover', )
                                ),
                                'gradient'      => array(
                                    'fields'        => array('pricing_button_background_gradient_hover',)
                                ),
                            ),
                        ),
                        'pricing_button_background_color_hover' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background Hover', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-btn:hover',
                                'property' => 'background-color',
                            ),
                        ),
                        'pricing_button_background_gradient_hover' => array(
                            'type'          => 'gradient',
                            'label'         => __( 'Gradient Hover', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-btn:hover',
                                'property' => 'background-image',
                            ),
                        ),
                        'pricing_button_border_hover' => array(
                            'type'          => 'color',
                            'label'         => __( 'Border Hover', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-btn:hover',
                                'property' => 'border-color',
                            ),
                        ),
                        'pricing_button_border' => array(
                            'type'       => 'border',
                            'label'      => 'Border',
                            'responsive' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-btn',
                            ),
                        ),
                        'pricing_button_padding' => array(
                            'type'        => 'dimension',
                            'label'       => 'Padding',
                            'units'       => array( 'px', '%' ),
                            'responsive'  => true,
                            'slider'  => true,
                            'default' => 'px',
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-btn',
                                'property'     => 'padding'
                            ),
                        ),
                        'pricing_button_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Margin',
                            'default' => 'px',
                            'units'       => array( 'px', '%' ),
                            'responsive'  => true,
                            'slider'  => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-pricing-item-btn',
                                'property'     => 'margin'
                            ),
                        ),
                    )
                ),
                'badge'       => array(
                    'title'         => __('Badge', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_badge_text_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Text Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-price-item-badge',
                                'property' => 'color',
                            ),
                        ),
                        'pricing_badge_background_color' => array(
                            'type'          => 'color',
                            'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                            'show_reset'    => true,
                            'show_alpha'    => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-price-item-badge',
                                'property' => 'background-color',
                            ),
                        ),
                        'style_badge_background_size' => array(
                            'type'        => 'unit',
                            'label'       => 'Width',
                            'units'          => array( 'px', '%' ),
                            'slider'  => true,
                            'responsive' => true ,
                            'default_unit' => '%',
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-price-item-badge',
                                'property'      => 'width',

                            ),
                        ),
                        'pricing_badge_transform_section' => array(
                            'type'    => 'button-group',
                            'label'   => 'Transform',
                            'default' => 'none',
                            'options' => array(
                                'block'    =>  __( 'ON', 'xpro-bb-addons' ),
                                'none'    => __( 'OFF', 'xpro-bb-addons' ),

                            ),
                            'toggle'        => array(
                                'none'      => array(
                                ),
                                'block'      => array(
                                    'fields'        => array( 'pricing_badge_offset_top', 'pricing_badge_offset_left' ,
                                        'pricing_badge_rotate' , 'pricing_badge_origin', 'pricing_position_badge',
                                        'pricing_position_overflow'),
                                ),
                            ),
                            'preview'    => array(
                                'type'          => 'css',
                                'selector'      => '.xpro-price-item-badge',
                                'property'      => 'display',
                            ),
                        ),
                        'pricing_badge_offset_top' => array(
                            'type'         => 'unit',
                            'label'        => 'Vertical Offset',
                            'units'          => array( 'px', '%', 'em' ),
                            'responsive' => 'true',
                            'slider' => array(
                                'px'    => array(
                                    'min' => -1000,
                                    'max' => 1000,
                                    'step'    => 1,
                                ),
                                '%'    => array(
                                    'min' => -1000,
                                    'max' => 1000,
                                    'step'    => 1,
                                ),
                                'em'    => array(
                                    'min' => -1000,
                                    'max' => 1000,
                                    'step'    => 1,
                                ),
                            ),
                            'default_unit' => 'px',
                            'preview'    => array(
                                'type'          => 'css',
                                'selector'      => '.xpro-price-item-badge',
                                'property'      => '--xpro-badge-translate-y',
                            ),
                        ),
                        'pricing_badge_offset_left' => array(
                            'type'         => 'unit',
                            'label'        => 'Horizontal Offset',
                            'units'          => array( 'px', '%', 'em' ),
                            'responsive' => 'true',
                            'slider' => array(
                                'px'    => array(
                                    'min' => -1000,
                                    'max' => 1000,
                                    'step'    => 1,
                                ),
                                '%'    => array(
                                    'min' => -1000,
                                    'max' => 1000,
                                    'step'    => 1,
                                ),
                                'em'    => array(
                                    'min' => -1000,
                                    'max' => 1000,
                                    'step'    => 1,
                                ),
                            ),
                            'default_unit' => 'px',
                            'preview'    => array(
                                'type'          => 'css',
                                'selector'      => '.xpro-price-item-badge',
                                'property'      => '--xpro-badge-translate-x',
                            ),
                        ),
                        'pricing_badge_rotate' => array(
                            'type'         => 'unit',
                            'label'        => 'Rotate',
                            'units'          => array( 'deg'),
                            'responsive' => 'true',
                            'slider' => array(
                                'deg'    => array(
                                    'min' => -360,
                                    'max' => 360,
                                    'step'    => 1,
                                )
                            ),
                            'default_unit' => 'deg',
                            'preview'    => array(
                                'type'          => 'css',
                                'selector'      => '.xpro-price-item-badge',
                                'property'      => '--xpro-badge-rotate',
                            ),
                        ),
                        'pricing_badge_origin' => array(
                            'type'          => 'select',
                            'label'         => __( 'Transform Origin', 'xpro-bb-addons' ),
                            'default'       => 'top right',
                            'options'       => array(
                                'center center'      => __( 'Center Center', 'xpro-bb-addons' ),
                                'center left'      => __( 'Center Left', 'xpro-bb-addons' ),
                                'center right'      => __( 'Center Right', 'xpro-bb-addons' ),
                                'top center'      => __( 'Top Center', 'xpro-bb-addons' ),
                                'top left'      => __( 'Top Left', 'xpro-bb-addons' ),
                                'top right'      => __( 'Top Right', 'xpro-bb-addons' ),
                                'bottom center'      => __( 'Bottom Center', 'xpro-bb-addons' ),
                                'bottom left'      => __( 'Bottom Left', 'xpro-bb-addons' ),
                                'bottom right'      => __( 'Bottom Right', 'xpro-bb-addons' ),
                            ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-price-item-badge',
                                'property' => 'transform-origin'
                            ),
                        ),
                        'pricing_position_badge' => array(
                            'type'          => 'button-group',
                            'label'         => __( 'Position', 'xpro-bb-addons' ),
                            'default'       => 'top-right',
                            'options'       => array(
                                'top-left'      => __( 'Top Left', 'xpro-bb-addons' ),
                                'top-center'      => __( 'Top Center', 'xpro-bb-addons' ),
                                'top-right'      => __( 'Top Right', 'xpro-bb-addons' ),
                            ),
                        ),
                        'pricing_position_overflow' => array(
                            'type'          => 'button-group',
                            'label'         => __( 'Overflow', 'xpro-bb-addons' ),
                            'default'       => 'inherit',
                            'options'       => array(
                                'inherit'      => __( 'Auto', 'xpro-bb-addons' ),
                                'hidden'      => __( 'Hidden', 'xpro-bb-addons' ),
                            ),
                        ),
                        'pricing_badge_border' => array(
                            'type'       => 'border',
                            'label'      => 'Border',
                            'responsive' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-price-item-badge',
                                'property' => 'border'
                            ),
                        ),
                        'pricing_badge_padding' => array(
                            'type'        => 'dimension',
                            'label'       => 'Padding',
                            'responsive'  => true,
                            'units'          => array( 'px', '%' ),
                            'slider' => array(
                                'px'    => array(
                                    'min' => 0,
                                    'max' => 500,
                                    'step'    => 1,
                                ),
                                '%'    => array(
                                    'min' => 0,
                                    'max' => 500,
                                    'step'    => 1,
                                ),
                            ),
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.xpro-price-item-badge',
                                'property'     => 'padding'
                            ),
                        ),
                    )
                ),
            )
        ),
        'typography'       => array(
            'title'         => __('Typography', 'xpro-bb-addons'),
            'sections'      => array(
                'heading'       => array(
                    'title'         => __('Heading', 'xpro-bb-addons'),
                    'fields'        => array(
                        'pricing_heading_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-title',
                            ),
                        ),
                    )
                ),
                'price'       => array(
                    'title'         => __('Price', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_prices_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-price-tag',
                            ),
                        ),
                        'xpro-widget-seprator1' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Currency<hr></h2>',
                        ),
                        'pricing_price_currency_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-currency',
                            ),
                        ),
                        'xpro-widget-seprator2' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Period<hr></h2>',
                        ),
                        'pricing_price_period_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-price-period',
                            ),
                        ),
                    )
                ),
                'features'       => array(
                    'title'         => __('Features', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'xpro-widget-seprator3' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Title<hr></h2>',
                        ),
                        'pricing_feature_title_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-features-title',
                            ),
                        ),
                        'xpro-widget-seprator4' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">List<hr></h2>',
                        ),
                        'pricing_feature_icon_title_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-feature-title',
                            ),
                        ),
                        'xpro-widget-seprator5' => array(
                            'type'    => 'raw',
                            'content' => '<h2 class="xpro-widget-separator-heading">Tooltip<hr></h2>',
                        ),
                        'pricing_feature_tooltip_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Content Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-tooltip',
                            ),
                        ),
                    )
                ),
                'description'       => array(
                    'title'         => __('Description', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_description_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-text',
                            ),
                        ),
                    )
                ),
                'button'       => array(
                    'title'         => __('Button', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_button_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-pricing-item-btn',
                            ),
                        ),
                    )
                ),
                'badge'       => array(
                    'title'         => __('Badge', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'pricing_badge_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '.xpro-price-item-badge',
                            ),
                        ),
                    )
                ),
            )
        ),
    ));

    //Features Form
    FLBuilder::register_settings_form('features_list_form', array(
        'title' => __('My Form Field', 'xpro-bb-addons'),
        'tabs'  => array(
            'general'      => array(
                'title'         => __('General', 'xpro-bb-addons'),
                'sections'      => array(
                    'general'       => array(
                        'title'         => '',
                        'fields'        => array(
                            'pricing_feature_icon' => array(
                                'type'          => 'icon',
                                'label'         => __( 'Icon', 'xpro-bb-addons' ),
                                'show_remove'   => true,
                                'default' => 'fas fa-check'
                            ),
                            'pricing_feature_list_title' => array(
                                'type'          => 'text',
                                'label'         => __( 'Title', 'xpro-bb-addons' ),
                                'placeholder'   => __( 'Feature List', 'xpro-bb-addons' ),
                            ),
                            'pricing_tooltip_text' => array(
                                'type'          => 'text',
                                'label'         => __( 'Text', 'xpro-bb-addons' ),
                                'placeholder'   => __( 'Tooltip Text', 'xpro-bb-addons' ),
                            ),
                            'pricing_feature_status' => array(
                                'type'    => 'button-group',
                                'label'   => 'Status',
                                'default' => 'active',
                                'options' => array(
                                    'active'    => __( 'Active', 'xpro-bb-addons' ),
                                    'inactive'    => __( 'Inactive', 'xpro-bb-addons' ),
                                ),
                            ),
                        )
                    ),
                )
            )
        )
    ));

}