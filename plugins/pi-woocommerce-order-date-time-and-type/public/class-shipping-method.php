<?php
class pisol_dtt_shipping_method{

    public $type;
    
    function __construct(){
        $this->type = pi_dtt_delivery_type::getType();
        if($this->type == 'pickup'){
            add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false');
            add_filter( 'woocommerce_product_needs_shipping','__return_false');
            add_filter( 'woocommerce_customer_taxable_address', array($this, 'shopBasedTaxableAddress'));
        }
    }

    /**
     * When pickup is selected we do the Tax calculation based on the shop 
     * base address
     */
    function shopBasedTaxableAddress($location){
        $country  = WC()->countries->get_base_country();
        $state    = WC()->countries->get_base_state();
        $postcode = WC()->countries->get_base_postcode();
        $city     = WC()->countries->get_base_city();
        return array( $country, $state, $postcode, $city );
    }
}

add_action('wp_loaded', function(){
    $pisol_disable_dtt_completely = apply_filters('pisol_disable_dtt_completely',false);
    if($pisol_disable_dtt_completely){
        return ;
    }

    new pisol_dtt_shipping_method();
});