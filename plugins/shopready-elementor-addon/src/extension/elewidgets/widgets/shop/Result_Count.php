<?php

namespace Shop_Ready\extension\elewidgets\widgets\shop;

class Result_Count extends \Shop_Ready\extension\elewidgets\Widget_Base {

    protected function register_controls() {

        $this->text_css(
            [
                'title'        => esc_html__( 'Style', 'shopready-elementor-addon' ),
                'slug'         => 'wooready_products_grid_product_title_style',
                'element_name' => 's__wooready_products_grid_product_title_style',
                'selector'     => '{{WRAPPER}} .woocommerce-result-count',
                'hover_selector'     => false,
                'disable_controls' => [
                    'display','position','size'
                ],
            ]
        );

    }

    protected function html() {

        $settings = $this->get_settings_for_display();

        if(shop_ready_is_elementor_mode()){
            echo wp_kses_post('<p class="woocommerce-result-count">
            Showing all 6 results</p>');
        }else{
            echo esc_html(woocommerce_result_count());
        }
       
        
    
    }

}