<?php
/**
 * We find min max as per user selected shipping method
 *      if he has not yet selected a shipping method
 * we find min max from all the methods in the assigned shipping zone and uses them to find min max
 */
class Pi_edd_min_max{
    protected $days = array();
    protected $methods = array();
    function __construct(){

        $this->methods  =   $this->getMethods();
        $this->days =   $this->getDays($this->methods);
    }

    /**
     * step 1: find user selected method
     * if no method selected yet
     * step 2: find all methods from shipping zone
     */
    function getMethods(){
        $methods = array();

        $zone_methods = $this->getMethodsAsPerShippingZone();
        $selected_method = Pi_edd_shipping_zone::getUserSelectedShippingMethod();
        if($selected_method != null ){

            if(empty($zone_methods)) return array();

            foreach((array)$zone_methods as $method){
                if($method->instance_id == $selected_method){
                    $methods[] = $method;
                }
            }
        }else{
            //return $zone_methods;
            /** considering first shipping method as selected method, when user has not selected any method yet */

            if(empty($zone_methods)) return array();
            
            foreach($zone_methods as $method){
                $zones[] = array($method);
            }
            return isset($zones[0]) ? $zones[0] : array();
        }
        return $methods;
    }

    /**
     * extract min and max days from all the found method and add them in days array 
     */
    function getDays($methods){
        $days = array();
        if(is_array($methods)){
            foreach($methods as $method){
                $min_days = $this->getMinDaysFromMethod($method);
                if($min_days != null) $days[] = $min_days;

                $max_days = $this->getMaxDaysFromMethod($method);
                if($max_days != null) $days[] = $max_days;
            }
        }
        return $days;
    }

    function getMinDays(){
        return empty($this->days) ? null : min($this->days);
    }

    function getMaxDays(){
        return empty($this->days) ? null : max($this->days);
    }

    function getMethodsAsPerShippingZone(){
        $default_shipping_zone = get_option('pi_defaul_shipping_zone',0);
        $methods = Pi_edd_shipping_zone::getEddShippingMethods($default_shipping_zone);
        return $methods;
    }

    function getMinDaysFromMethod($method){
        $min_days = null;
        if(isset($method->instance_settings['min_days'])){
            $min_days = $method->instance_settings['min_days'];
        }
        return $min_days;
    }

    function getMaxDaysFromMethod($method){
        $max_days = null;
        if(isset($method->instance_settings['max_days'])){
            $max_days = $method->instance_settings['max_days'];
        }
        return $max_days;
    }
}