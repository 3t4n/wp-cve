<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
class pi_dtt_pickup_location{
    

    static function isLocationPresentInSystem(){
        $address1 = get_option('pi_pickup_address_1', "");
        $address2 = get_option('pi_pickup_address_2', "");

        if(empty($address1) && empty($address2)){
            return false;
        }
        
        return true;
    }

    
   
}