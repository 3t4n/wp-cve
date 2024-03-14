<?php

/**
 * step 1: check user selected zone
 * step 2: get gro location based zone
 * step 3: get the default zone
 * 
 * Function with "Edd" in name are as per the plugin logic remaining are general functions
 */

class Pi_edd_shipping_zone
{

    public static function getEddShippingZone($default_zone_id = 0){
        $shipping_zone = null;

            if($shipping_zone == null){
                $shipping_zone = self::getUserSelectedZone();
            }

            if($shipping_zone == null){
                $shipping_zone = self::getGeoLocatedZone();
            }

            if($shipping_zone == null){
                $shipping_zone = self::getShippingZone($default_zone_id);
            }

        return $shipping_zone;
    }

    public static function getEddShippingMethods($default_zone_id = 0){
        $shipping_zone = self::getEddShippingZone($default_zone_id);
        if(is_object($shipping_zone)){
            $methods = $shipping_zone->get_shipping_methods(true);
            return $methods;
        }
        return null;
    }

    public static function getZoneMethods( $zone_id = 0){
        $shipping_zone = WC_Shipping_Zones::get_zone( $zone_id );
        if(is_object($shipping_zone)){
            $methods = $shipping_zone->get_shipping_methods(true);
            return $methods;
        }
        return null;
    }

    /** this should be called after the wp_loaded action */
    public static function getUserSelectedZone(){
        global $woocommerce;
        if(isset(WC()->cart)){
            $shipping_packages =  WC()->cart->get_shipping_packages();
        
            $shipping_zone = wc_get_shipping_zone( reset( $shipping_packages ) );

            if(is_object($shipping_zone)){
                $methods = self::getZoneMethods($shipping_zone->get_id());
                if(!empty($methods)){
                    return $shipping_zone;
                }
                return null;
            }
        }
        return null;
    }

    public static function getGeoLocatedZone(){
        $destination = self::ipBasedDestination();
        $shipping_zone = WC_Shipping_Zones::get_zone_matching_package( $destination );
        if(is_object($shipping_zone)){
            $methods = self::getZoneMethods($shipping_zone->get_id());
            if(!empty($methods)){
                return $shipping_zone;
            }
            return null;
        }
        return null;
    }

    public static function getShippingZone($zone_id = 0){
        $shipping_zone = WC_Shipping_Zones::get_zone( $zone_id );
        if(is_object($shipping_zone)){
            $methods = self::getZoneMethods($shipping_zone->get_id());
            if(!empty($methods)){
                return $shipping_zone;
            }
            return null;
        }
        return null;
    }

    public static function getUserSelectedShippingMethod($get_name = false){
        $selected_shipping_method = array();
        if( isset( WC()->session ) ){
            $selected_shipping_method = WC()->session->get( 'chosen_shipping_methods' );
        }

        if( isset( $selected_shipping_method[0] ) && $selected_shipping_method[0] !== false){ // flat_rate:19
            $method = explode( ":", $selected_shipping_method[0] );

            if( $get_name == true ){
                return $method[0];
            }else{
                if(isset($method[1])){
                    return $method[1];
                }else{
                    return null;
                }
            }
        }
        
        return null;
    }

    /**
     * geo locate customer destination
     */
    public static function ipBasedDestination(){
        $geo_instance  = new WC_Geolocation();
        $user_ip  = $geo_instance->get_ip_address();
        $user_geodata = $geo_instance->geolocate_ip($user_ip);
        
        $destination['destination']['country'] =  $user_geodata['country'];
        $destination['destination']['state'] =  $user_geodata['state'];
        $destination['destination']['postcode'] = "";
        return $destination;
    }
}
