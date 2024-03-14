<?php
/**
 * @package Shop Ready 
 */
namespace Shop_Ready\base\elementor\style_controls\common;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

trait Widget_Product {
 
    /**
     * WooCommerce Product Grid Control
     * @since 1.0
     */
    public function wooCommerce_product_grid_style_control(){

            $this->box_css(
                [ 
                    'title'        => esc_html__('Main Container','shopready-elementor-addon'),
                    'slug'         => 'wready_product_box_style',
                    'element_name' => '_woo_ready__container',
                    'selector'     => '{{WRAPPER}} .woo-ready-products'
                ]
            );
                    
                    
            $this->box_css(
                    [
                        'title'        => esc_html__('Product Item','shopready-elementor-addon'),
                        'slug'         => 'wready_product_list_style',
                        'element_name' => '__woo_ready_item',
                        'selector'     => '{{WRAPPER}} .woo-ready-products .woo-ready-single-product'
                    ]
            );

            $this->text_css(
                [
                    'title'        => esc_html__('Product onsale','shopready-elementor-addon'),
                    'slug'         => 'wready_product_list_onsale_style',
                    'element_name' => 's__woo_ready__onsale',
                    'selector'     => '{{WRAPPER}} .woo-ready-products .woo-ready-single-product .onsale'
                ]
            );
            
            $this->text_css(
                [
                    'title'        => esc_html__('Product Title','shopready-elementor-addon'),
                    'slug'         => 'wready_product_list_title_style',
                    'element_name' => 's__woo_ready__title',
                    'selector'     => '{{WRAPPER}} .woo-ready-products .woo-ready-single-product .woocommerce-loop-product__title'
                ]
            );

            $this->box_css(
                [
                    'title'        => esc_html__('Product Image','shopready-elementor-addon'),
                    'slug'         => 'wready_product_list_image_style',
                    'element_name' => 's__woo_ready__image',
                    'selector'     => '{{WRAPPER}} .woo-ready-products .woo-ready-single-product .attachment-woocommerce_thumbnail'
                ]
            );

            $this->text_minimum_css(
                [
                    'title'          => esc_html__('Price','shopready-elementor-addon'),
                    'slug'           => 'wready_wc_default_product_price_c',
                    'element_name'   => '_product_price_',
                    'hover_selector' =>  false,
                    'selector'       => '{{WRAPPER}} .woo-ready-single-product bdi',
                    
                ]
            );

            $this->text_minimum_css(
                [
                    'title'          => esc_html__('Currency','shopready-elementor-addon'),
                    'slug'           => 'wready_wc_default_product_price_currency',
                    'element_name'   => '_product_price_curren',
                    'hover_selector' =>  false,
                    'selector'       => '{{WRAPPER}} .woo-ready-single-product bdi .woocommerce-Price-currencySymbol,{{WRAPPER}} .woo-ready-single-product bdi > span',
                    
                ]
            );

            $this->text_wrapper_css(
                [
                    'title'          => esc_html__('Regular Price Wrapper','shopready-elementor-addon'),
                    'slug'           => 'wready_wc_default_product_regular_price_del',
                    'element_name'   => '_product_price_reg',
                    'hover_selector' =>  false,
                    'selector'       => '{{WRAPPER}} .woo-ready-single-product del,{{WRAPPER}} .woo-ready-single-product del',
                    
                ]
            );

            $this->text_wrapper_css(
                [
                    'title'          => esc_html__('Sale Price Wrapper','shopready-elementor-addon'),
                    'slug'           => 'wready_wc__product_regular_price_ins',
                    'element_name'   => '_product_price_ins',
                    'hover_selector' =>  false,
                    'selector'       => '{{WRAPPER}} .woo-ready-single-product ins,{{WRAPPER}} .woo-ready-single-product ins',
                    
                ]
            );

            $this->text_wrapper_css(
                [
                    'title'          => esc_html__('Sale Price','shopready-elementor-addon'),
                    'slug'           => 'wready_wc__product_sales_price_ins',
                    'element_name'   => '_product_Sales_price_ins',
                    'hover_selector' =>  false,
                    'selector'       => '{{WRAPPER}} .woo-ready-single-product ins bdi',
                    
                ]
            );
            
            $this->text_wrapper_css(
                [
                    'title'          => esc_html__('Sale Currency','shopready-elementor-addon'),
                    'slug'           => 'wready_wc__product_sales_cur_ins',
                    'element_name'   => '_product_Sales_price_ins',
                    'hover_selector' =>  false,
                    'selector'       => '{{WRAPPER}} .woo-ready-single-product ins bdi .woocommerce-Price-currencySymbol,{{WRAPPER}} .woo-ready-single-product ins bdi > span',
                    
                ]
            );

            $this->text_wrapper_css(
                [
                    'title'          => esc_html__('Buy Button','shopready-elementor-addon'),
                    'slug'           => 'wready_wc__product_sales_buy',
                    'element_name'   => '_product_Sales_buy',
                    'hover_selector' =>  '{{WRAPPER}} .woo-ready-single-product a.button:hover',
                    'selector'       => '{{WRAPPER}} .woo-ready-single-product a.button',
                    
                ]
            );

            $this->text_minimum_css(
                [
                    'title'          => esc_html__('Rating','shopready-elementor-addon'),
                    'slug'           => 'wready_wc_rating_inactives',
                    'element_name'   => 'wrag_product_star',
                    'selector'       => '{{WRAPPER}} .woo-ready-single-product .star-rating',
                    'hover_selector' => false,
                    
                ]
            );

            $this->text_minimum_css(
                [
                    'title'          => esc_html__('Inactive Rating','shopready-elementor-addon'),
                    'slug'           => 'wready_wc_rating_s',
                    'element_name'   => 'wrating_product_star',
                    'selector'       => '{{WRAPPER}} .woo-ready-single-product .star-rating::before',
                    'hover_selector' => false,
                    
                ]
            );
   
    }
}