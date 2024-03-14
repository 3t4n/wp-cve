<?php

class pi_edd_common{

    /* 4/7/19 */
    public static $legacy_methods = array('flat_rate', 'free_shipping', 'local_pickup');

    public static function getMinMax($method, $method_name){
        $min_max = array();
        /** option format is like this woocommerce_free_shipping_23_settings */
            $option_name = "woocommerce_".$method_name."_".$method."_settings";
            $present_setting = get_option($option_name);
            $min_max['min_days'] = isset($present_setting['min_days']) ? $present_setting['min_days'] : 1;
            $min_max['max_days'] = isset($present_setting['max_days']) ? $present_setting['max_days'] : 1;
        return $min_max;
    }

    public static function getMin($method, $method_name){
        $min_days = "";
        /** option format is like this woocommerce_free_shipping_23_settings */
            $option_name = "woocommerce_".$method_name."_".$method."_settings";
            $present_setting = get_option($option_name);
            $min_days = isset($present_setting['min_days']) ? $present_setting['min_days'] : 1;
           
        return $min_days;
    }

    public static function getMax($method, $method_name){
        $max_days = "";
        /** option format is like this woocommerce_free_shipping_23_settings */
            $option_name = "woocommerce_".$method_name."_".$method."_settings";
            $present_setting = get_option($option_name);
            $min_days = isset($present_setting['min_days']) ? $present_setting['min_days'] : 1;

            $max_days = isset($present_setting['max_days']) ? $present_setting['max_days'] : $min_days;
           
        return $max_days;
    }
    /* 4/7/19 */

    
    
}