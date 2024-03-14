<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
class pisol_dtt_time_slot{

    public $selected_date;
    public $delivery_type;
    public $day;
    public $time_slot;

    function __construct($selected_date){
        $this->selected_date = $selected_date;
    }

    static function getTimeSlotArray( $date, $type = "" ){
        $obj = new self($date);
        $time_slot  =  $obj->init($type);
        return $time_slot;
    }

    static function getTimeSlotJson($date, $type = "" ){
        $obj = new self($date);
        $obj->getTime($type);
    }

    function getTime($type = ""){
        $time_slot  =  $this->init($type);
        echo json_encode($time_slot);
        die;
    }

    function init($type = ""){
        
        if(empty($this->selected_date)) return array();
        if(empty($type)){
            $this->delivery_type = pi_dtt_delivery_type::getType();
        }else{
            $this->delivery_type = $type;
        }
        
        $this->day = strtolower(pisol_dtt_time::dayOfTheWeek( $this->selected_date ));
        $this->time_slot = $this->getTimeSlot($this->day, $this->delivery_type);
        return apply_filters('pisol_dtt_time_slot_filter',$this->time_slot, $this->selected_date, $this->delivery_type);
    }

    function getTimeSlot($day, $delivery_type){

        $forced_time_slots = apply_filters('pisol_forced_valid_time_slots',null, $day, $this->selected_date, $delivery_type);
        if($forced_time_slots !== null && is_array($forced_time_slots)) return $forced_time_slots;
            
        $slots = $this->getGeneralTimeSlots( $delivery_type );
            
        $slots = apply_filters('pisol_dtt_forced_time_slots', $slots, $day, $this->selected_date, $delivery_type);

        return   $this->filterSlots($slots);
    }

    function getGeneralTimeSlots( $delivery_type ){
        $slots = pisol_dtt_get_setting('pi_general_time_slot_'.$delivery_type, array());
        if(empty($slots)) return array();

        return $slots;
    }

    function filterSlots($slots){
        if(empty($slots)) return array();

        $return1 = $this->removePastTime($slots);

        $return2 = $this->customFilter($return1);

        $return3 = $this->convertToString($return2);

        return $return3;
    }

    static function slotToString($slot){
        $key = pisol_dtt_time::formatTimeForStorage($slot['from']).' - '.pisol_dtt_time::formatTimeForStorage($slot['to']);
        return $key;
    }

    
    function removePastTime($time_array){


        if(!is_array($time_array) || $time_array == ""){
            return "";
        }

        if(!$this->isSelectedDateToday()) return $time_array;
        
        $order_preparation_time = pisol_dtt_get_setting('pi_order_preparation_hours',60);
        $order_preparation_time = $order_preparation_time == "" ? 0 : $order_preparation_time;

        $now = current_time('h:i A');

        $minutes_to_add = "+".$order_preparation_time." minutes";

        $time_limit  = strtotime($minutes_to_add, strtotime($now));
        foreach($time_array as $key => $time){
            if($time['to'] != ""){
                if(strtotime($time['to']) < $time_limit){
                    unset($time_array[$key]);
                }
            }else{
                if($time['from'] != ""){
                    if(strtotime($time['from']) < $time_limit){
                        unset($time_array[$key]);
                    }
                }
            }
        }
        return $time_array;
    }

    function customFilter($return){
        return apply_filters("pisol_dtt_custom_remove_time_slots", $return, $this->selected_date);
    }

    function isSelectedDateToday(){
        $today = current_time('Y/m/d');
        if($this->selected_date == $today){
            return true;
        }
        return false;
    }

    function convertToString($slots){
        $return  = array();
        if(!empty($slots)){
            foreach($slots as $slot){
                $key = pisol_dtt_time::formatTimeForStorage($slot['from']).' - '.pisol_dtt_time::formatTimeForStorage($slot['to']);
                
                $value =  self::removeEmptyTimeFromDisplay(pisol_dtt_time::formatTimeForDisplay($slot['from']), pisol_dtt_time::formatTimeForDisplay($slot['to']));
                $return[] = array('id'=>$key, 'text'=>$value);
            }
        }
        return $return;
    }  

    static function removeEmptyTimeFromDisplay($from, $to){
        if(!empty($from) && !empty($to)){
            return $from.' - '.$to;
        }

        if(!empty($from) && empty($to)){
            return $from;
        }

        if(empty($from) && !empty($to)){
            return $to;
        }
        return "";
    }   
}