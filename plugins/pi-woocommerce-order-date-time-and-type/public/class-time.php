<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
class pisol_dtt_time{

    function __construct(){
        
        add_action('wp_ajax_pisol_dtt_get_time', array($this,"getTime") ); 
        add_action('wp_ajax_nopriv_pisol_dtt_get_time', array($this,"getTime") ); 
        
    }

    function getTime(){
        if(!isset($_POST['selected_date'])) return;

        $type = '';
        if(isset($_POST['pi_dtt_delivery_type'])){
            $type = sanitize_text_field($_POST['pi_dtt_delivery_type']);
        }
        /**
         * have removed this check as we do validation of time slots as per date
         * during checkout
         */
        /* if(!pi_dtt_date::isDateValid($_POST['selected_date'])) return; */

        if(self::useTimeSlot()){
            pisol_dtt_time_slot::getTimeSlotJson(sanitize_text_field($_POST['selected_date']), $type);
            die;
        }else{
            pisol_dtt_time_range::getTimeRangeJson(sanitize_text_field($_POST['selected_date']), $type);
            die;
        }
        
    }

    static function getTimeArray($date, $type = ""){
        $obj = new self();
        if(!isset($date)) return;

        if(!pi_dtt_date::isDateValid($date, $type)) return;

        if(self::useTimeSlot($type)){
            return pisol_dtt_time_slot::getTimeSlotArray($date, $type);
        }else{
            return pisol_dtt_time_range::getTimeRangeArray($date, $type);
        }
        

    }

    static function isTimeValid($date, $input_time, $type = ""){

        if(empty( $type )){
            $type =  pi_dtt_delivery_type::getType();
        }else{
            $type =  $type;
        }

        $obj = new self();
        if(!isset($date)) return false;

        if(!pi_dtt_date::isDateValid($date, $type)) return false;

        if(self::useTimeSlot($type)){
            $time = pisol_dtt_time_slot::getTimeSlotArray($date, $type);
        }else{
            $time = pisol_dtt_time_range::getTimeRangeArray($date, $type);
        }
        

        if(empty($time)) return false;

        $input_time = str_replace(' ', '', $input_time);

        $time = str_replace(' ', '', array_column($time, 'id'));

        if(array_search($input_time, $time) !== false) {
            return true;
        }
        
        return false;
        

    }

    static function isTimeAvailable( $date ){
        $time = array();

        if(pi_dtt_display_fields::enableTimeField()){
            
            if(self::useTimeSlot()){
                $time = pisol_dtt_time_slot::getTimeSlotArray($date);
            }else{
                $time = pisol_dtt_time_range::getTimeRangeArray($date);
            }
            
            if( empty($time) ) return false;
        }

        return true;
    }


    static function dayOfTheWeek($date){
        return strtolower(date("l",strtotime($date)));
    }


    static function formatTimeForDisplay($times){
        $format = pisol_dtt_time::getDisplayFormat();
        return pisol_dtt_time::formatTimeForStorage($times, $format);
    }

    static function formatTimeForStorage($times, $format = 'H:i'){
        if(strpos($times,'-')){
            $times_array = explode("-",$times);
        }else{
            $times_array[] = $times;
        }

        $formated_array = array();
        foreach($times_array as $time){
            $formated_array[] = strtotime(trim($time)) ? date($format, strtotime(trim($time))) : trim($time);
        }
        $return  = "";
        if(count($formated_array) == 2){
            $return  .= $formated_array[0].' - '.$formated_array[1];
        }else{
            $return  .= $formated_array[0];
        }
        return $return;
    }

    static function getDisplayFormat(){
        $format = pisol_dtt_get_setting('pi_time_format','h:mm p');

        switch($format){
            case 'h:mm p':
                return "g:i A"; 
            break;

            case 'H:mm':
                return "H:i";
            break;

            case 'H:mm p':
                return "H:i A";
            break;

            default:
                return "g:i A"; 
            break;
        }
    }

    static function useTimeSlot($type = ""){
        if(empty($type)){
            $type = pi_dtt_delivery_type::getType();
        }
        $user_setting = pisol_dtt_get_setting('pi_use_time_slot','all_range');
        switch($user_setting){
            case 'all_slot':
                return true;
            break;

            case 'all_range':
                return false;
            break;
            case 'pickup_slot_delivery_range':
                if($type == 'pickup') return true;

                return false;
            break;
            case 'delivery_slot_pickup_range':
                if($type == 'delivery') return true;
                
                return false;
            break;
        }
        return true;
    }
}

new pisol_dtt_time();