<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
class pisol_dtt_time_range{

    static $time_format = 'H:i';
    public $selected_date;
    public $delivery_type;
    public $day;
    public $time_slot;
    public $time_array;

    function __construct($selected_date){
        $this->selected_date = $selected_date;
    }

    static function getTimeRangeArray( $date, $type = ""  ){
        $obj = new self($date);
        $time_range  =  $obj->init($type);
        return apply_filters('pisol_dtt_time_range_filter',$time_range, $date, $type);
    }

    static function getTimeRangeJson($date, $type = "" ){
        $obj = new self($date);
        $obj->getTime($type);
    }

    function getTime($type = ""){
        $time_range  =  $this->init($type);
        echo json_encode($time_range);
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

        $this->time_array = $this->getTimeArray($this->day, $this->delivery_type);

        return apply_filters('pisol_dtt_time_range_filter',$this->time_array, $this->selected_date, $this->delivery_type);
    }

    function getTimeArray($day, $delivery_type){
        //return array(array('id'=>'9:00 AM','text'=>'9:00 AM' ));
        $start_time = $this->getStartTime($day, $delivery_type);
        $end_time = $this->getEndTime($day, $delivery_type);
        $time_array = $this->generateTimeArray($start_time, $end_time);
        $formated_array = $this->formatTimeArray($time_array);
        return $formated_array;
    }

    function generateTimeArray($start_time, $end_time){
        $start_time = self::validateTime($start_time, '9:00 AM');
        $end_time = self::validateTime($end_time, '9:00 PM');

        if(!self::isStartTimeSmallerThenEndTime($start_time, $end_time)) return array();

        $time_array = self::generateArray($this->selected_date, $start_time, $end_time);
        return $time_array;
    }

    static function generateArray($selected_date, $start_time, $end_time){
        $time_interval = pisol_dtt_get_setting('pi_time_interval',15);
        $time_array = array();
        $time = $start_time;
        $compare_time = $selected_date.' '.$start_time;
        while(strtotime($compare_time) <= strtotime($selected_date.' '.$end_time)){
            if(self::isTimeAllowed($selected_date, $time)){
                $time_array[] = $time;
            }
            $time = date(self::$time_format, strtotime(' + '.$time_interval.' minutes', strtotime($time)));
            $compare_time = date('Y/m/d h:i A', strtotime(' + '.$time_interval.' minutes', strtotime($compare_time)));
        }
        return $time_array;
    }

    function formatTimeArray($time_array){
        if(empty($time_array)) return array();
        $formated = array();
        foreach($time_array as $time){
            $formated[] = array(
                'id'=>date( self::$time_format , strtotime($time) ),
                'text'=>date( pisol_dtt_time::getDisplayFormat(), strtotime($time) )
            );
        }
        return $formated;
    }

    static function isTimeAllowed($date, $time){
        $current_date = current_time('Y/m/d');
        if($current_date == $date){
            $pi_order_preparation_hours = pisol_dtt_get_setting('pi_order_preparation_hours',60);
            $pi_order_preparation_hours = !empty($pi_order_preparation_hours) ? $pi_order_preparation_hours : 0;
            $current_time = current_time(self::$time_format);
            $last_allowed_time = strtotime(' + '.$pi_order_preparation_hours.' minutes',strtotime($current_time));
            if(strtotime($time) > $last_allowed_time){
                return true;
            }
            return false;
        }
        return true;
    }

    static function isStartTimeSmallerThenEndTime($start_time, $end_time){
        if( strtotime($start_time) < strtotime($end_time) ){
            return true;
        }
        return false;
    }

    static function validateTime($time, $default){
        return strtotime($time) ? $time : $default;
    }

    function getStartTime($day, $delivery_type){
        return $this->getGeneralStartTime($delivery_type);
    }

    function getGeneralStartTime($delivery_type){
        $name = 'pi_'.$delivery_type.'_start_time';
        $start_time = pisol_dtt_get_setting($name,"");
        return $start_time;
    }

    function getGeneralEndTime($delivery_type){
        $name = 'pi_'.$delivery_type.'_end_time';
        $end_time = pisol_dtt_get_setting($name,"");
        return $end_time;
    }

    function getEndTime($day, $delivery_type){
        return $this->getGeneralEndTime($delivery_type);
    }

    function getDaysStartTime($day, $delivery_type){
        $name = 'pi_'.$delivery_type.'_'.$day.'_start_time';
        $start_time = pisol_dtt_get_setting($name,"");
        return $start_time;
    }

    function getDaysEndTime($day, $delivery_type){
        $name = 'pi_'.$delivery_type.'_'.$day.'_end_time';
        $end_time = pisol_dtt_get_setting($name,"");
        return $end_time;
    }

    
}