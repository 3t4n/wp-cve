<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
class pi_dtt_validate{
    function __construct(){
        /* validating field */
        /** old validation method */
        //add_action('woocommerce_checkout_process', array($this,'validateCheckout'));
        
        /**
         * New validation based on this hook
         * https://github.com/woocommerce/woocommerce/blob/23710744c01ded649d6a94a4eaea8745e543159f/includes/class-wc-checkout.php#L878
         */
        add_action('woocommerce_after_checkout_validation', array($this,'validateCheckout'),10,2);
    }

    function validateCheckout($data, $errors){

        $pisol_disable_dtt_completely = apply_filters('pisol_disable_dtt_completely',false);
        if($pisol_disable_dtt_completely){
            return ;
        }


        $dateLabel = __('Date','pisol-dtt');
        $timeLabel = __('Time','pisol-dtt');

        $date = isset($data['pi_system_delivery_date']) ? sanitize_text_field($data['pi_system_delivery_date']) : "";
        $time = isset($data['pi_delivery_time']) ? sanitize_text_field($data['pi_delivery_time']) : "";

        if(pi_dtt_display_fields::showDateAndTime()):
            if(pi_dtt_display_fields::isDateRequired()){     
                if (!isset($data['pi_system_delivery_date']) || empty( $data['pi_system_delivery_date'])){
                    $errors->add('error', sprintf(__( '<strong>%s</strong> is a required field','pisol-dtt' ), $dateLabel));
                }

               
                if(!empty($date)){
                    if(!pi_dtt_date::isDateValid($date)){
                        $errors->add('error',  sprintf(__( '<strong>%s</strong> date you selected is not available any more, please refresh page to get available dates ','pisol-dtt' ), $dateLabel));
                    }
                }
            }else{

                if(!pi_dtt_date::isDateValid($date) && !empty($date)){
                    $errors->add('error',  sprintf(__( '<strong>%s</strong> date you selected is not available any more, please refresh page to get available dates ','pisol-dtt' ), $dateLabel));
                }

            }
    
            if(pi_dtt_display_fields::enableTimeField() && pi_dtt_display_fields::isTimeRequired()){    
                if (!isset($data['pi_delivery_time']) || empty($data['pi_delivery_time'])){
                    $errors->add('error', sprintf(__( '<strong>%s</strong> is a required field','pisol-dtt' ), $timeLabel));
                }

                if(!empty($data['pi_delivery_time'])){
                    if(!pisol_dtt_time::isTimeValid($date, $time)){
                        $errors->add('error',  sprintf(__( '<strong>%s</strong> time you inserted is not available any more, please refresh page to get available time','pisol-dtt' ), $timeLabel));
                    }
                }
            }else{
                
                if(!pisol_dtt_time::isTimeValid($date, $time) && !empty($time)){
                    $errors->add('error',  sprintf(__( '<strong>%s</strong> time you inserted is not available any more, please refresh page to get available time','pisol-dtt' ), $timeLabel));
                }
            }
            
        endif;


        if(self::isLocationRequired()){
            
            if (!isset($data['pickup_location']) || empty($data['pickup_location'])){
                $errors->add('error', sprintf(__( '<strong>%s</strong> is a required field','pisol-dtt' ), __('Pickup Location', 'pisol-dtt')));
            }

        }
    }

    /**
     * Required:
     * if location is marked as required field in plugin setting
     * and there is location created in plugin but user zone is not having any location
     * 
     * not required:
     * if user has marked it not required
     * 
     * if user has marked it required but there is no pickup location created in the system
     */
    static function isLocationRequired(){
        $required = 1;

        $type =  pi_dtt_delivery_type::getType();
       
        if( $type == 'pickup' && !empty($required) && $required == '1' && pi_dtt_pickup_location::isLocationPresentInSystem()){
            return true;
        }

        return false;
    }

   
}

add_action('wp_loaded',function(){
        /**
         * This filter allow you to hide all the fields added by this plugin 
         * so you can use this to disable the plugin when you have virtual product in
         * your cart
         */
		$pisol_disable_dtt_completely = apply_filters('pisol_disable_dtt_completely',false);
        if($pisol_disable_dtt_completely){
            return ;
        }
        
    new pi_dtt_validate();
});