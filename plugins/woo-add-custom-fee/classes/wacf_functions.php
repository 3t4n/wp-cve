<?php
/*
 * @author Mohammad Mursaleen
 * Class containing all function for Woo Add Custom Fee plugin
 */
class WACF_Funcitons {

    /**
     * required actions & filters.
     */
    public static function init(){
        //hook function to add custom fee to cart
        add_action( 'woocommerce_cart_calculate_fees',  __CLASS__ . '::add_fee' );
    }

    /**
     * Function to add Custom Fee
     */
    public static function add_fee() {
        global $woocommerce;
        
        if( get_option('wacf_enable', 'no' ) != 'yes' )
            return;
        $minimum = get_option('wacf_minimum' , 0 ) ;
        $minimum = floatval(str_replace(',', '',  $minimum));
        $maximum = get_option('wacf_maximum', 0);
        $maximum = floatval(str_replace(',', '',  $maximum));
        $cart_total =  preg_replace("/([^0-9\\.])/i", "", $woocommerce->cart->cart_contents_total) ;
        if(get_option('wacf_enable_min', true )=='yes' && get_option('wacf_enable_max', true )=='yes' && get_option('wacf_enable', true )=='yes') {
            if ($cart_total >= $minimum && $cart_total <= $maximum){
                WACF_Funcitons::wacf_calculate_fee($cart_total);
            } else {
                $cart_total;
            }
        } elseif(get_option('wacf_enable', true )=='yes' && get_option('wacf_enable_min', true )=='yes'){
            if ($cart_total >= $minimum) {
                WACF_Funcitons::wacf_calculate_fee($cart_total);
            }
        } elseif(get_option('wacf_enable', true )=='yes' && get_option('wacf_enable_max', true )=='yes' ) {
            if($cart_total <= $maximum) {
                WACF_Funcitons::wacf_calculate_fee($cart_total);
            }
        } else {
            if($cart_total >= $minimum) {
                WACF_Funcitons::wacf_calculate_fee($cart_total);
            }
        }
    }

    public static function wacf_calculate_fee($cart_total) {
        global $woocommerce;
        if( get_option('wacf_type', true ) == 'percentage' ) {
            $fee = ($cart_total / 100) * get_option('wacf_fee_charges', 0);
            $woocommerce->cart->add_fee(get_option('wacf_fee_label', __('Custom Fee', 'wacf')), $fee, get_option('wacf_taxable', false), get_option('wacf_tax_class', ''));
        } else {
            $woocommerce->cart->add_fee(get_option('wacf_fee_label', __('Custom Fee', 'wacf')), get_option('wacf_fee_charges', 0), get_option('wacf_taxable', false), get_option('wacf_tax_class', ''));
        }
    }
}
WACF_Funcitons::init();