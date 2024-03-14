<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
class pi_dtt_delivery_type{

    public $default_type;   

    function __construct(){
        $this->default_type = pisol_dtt_get_setting('pi_default_type', 'delivery');
    }

    static function getType(){
        $obj = new self();
        $type = $obj->getDeliveryType();
        return $type;
    }

    function availableDeliveryType(){
        $aloud_types = pisol_dtt_get_setting('pi_type', "Both");
        if($aloud_types == "Both"){
            return 'both';
        }

        if($aloud_types == "Pickup"){
            return 'pickup';
        }

        if($aloud_types == "Delivery"){
            return 'delivery';
        }
    }

    function getDeliveryType(){
        $available_type = $this->availableDeliveryType();

        if($this->checkDeliveryTypeSetInSession( $available_type )){
            $type = WC()->session->get( 'pi_delivery_type', false);
            return $type;
        }

        if($available_type != "both"){
            $this->setDeliveryType($available_type);
            return $available_type;
        }
        
        return $this->setDeliveryType($this->default_type);
    }

    function setDeliveryType($type){
        if($this->checkSession()){
            WC()->session->set("pi_delivery_type", $type);
            return WC()->session->get( 'pi_delivery_type', false);
        }
        return false;
    }

    function checkDeliveryTypeSetInSession($available_type){
        if($this->checkSession()){
            $type = WC()->session->get( 'pi_delivery_type', false);
            if($available_type == 'both' && ($type == 'pickup' || $type == 'delivery')){
                return true;
            }

            if($available_type == 'pickup' && ($type == 'pickup')){
                return true;
            }

            if($available_type == 'delivery' && ($type == 'delivery')){
                return true;
            }

            
            if($this->allowedCustomType($type)){
                return true;
            }
        }
        return false;
    }

    function allowedCustomType($type){
        $custom_type = apply_filters('pisol_dtt_custom_delivery_type',"");
        if($custom_type == "") return false;

        if(is_array($custom_type)){ 
            if(in_array($type, $custom_type)){
                return true;
            }else{
                return false;
            }
        }else{
            if($type == $custom_type){
                return true;
            }else{
                return false;
            }
        }

    }

    function checkSession(){
        if(function_exists('WC') && isset(WC()->session)){
            return true;
        }
        return false;
    }

}