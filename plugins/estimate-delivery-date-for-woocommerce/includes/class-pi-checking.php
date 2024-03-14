<?php

class pisol_checking{

    static function is_Methods($zone_id){
        if(self::zoneExist($zone_id)){
            $zone_obj = new WC_Shipping_Zone($zone_id);
            $methods = $zone_obj->get_shipping_methods(true);
            if(count($methods) > 0){
                return true;
            }
        }
        return false;
    }

    static function zoneExist($zone_id){
        $zones = WC_Shipping_Zones::get_zones();
        foreach($zones as $zone){
            if($zone['zone_id'] == $zone_id){
                return true;
            }
        }
        return false;
    }

    static function checkUserSelectedClass(){
        global $woocommerce;
		if( isset(WC()->session) ):
		if(is_array(WC()->session->get( 'chosen_shipping_methods' )) || (isset($_GET['wc-ajax']) && $_GET['wc-ajax'] == 'update_order_review')) {
			if(isset($_POST['shipping_method'][0])){
				$selection  = $_POST['shipping_method'];
			}else{
				$selection = WC()->session->get( 'chosen_shipping_methods' );
			}
			if(isset($selection[0])):
                $val = explode(":",$selection[0]);

                if($val[0] == "pisol_extended_flat_shipping"){
                    return true;
                }
                
				if(isset($val[1])){
					$selected_shipping_method = (int)$val[1];
				}else{
					return false;
				}
                $method = WC_Shipping_Zones::get_shipping_method($selected_shipping_method );

                if($method != false){
                    return true;
                }

			endif;
		}
        endif;

        return false;
		
    }
    
    static function checkZones(){
        $zones = WC_Shipping_Zones::get_zones();
        if(count($zones) > 0){
            return true;
        }
        return false;
    }
}