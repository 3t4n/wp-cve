<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
class pi_dtt_date{

    public $format;
    public $type;
    public $today;
    public $preparation_days;
    public $pre_order_days;
    public $maxDays;
    public $allowed_dates;
    public $holidays;
    
    function __construct( $type = "" ){
        $this->format = 'Y/m/d';

        if(empty( $type )){
            $obj  = new pi_dtt_delivery_type();
            $this->type =  $obj->getDeliveryType();
        }else{
            $this->type =  $type;
        }

        $this->today = current_time($this->format);
        $this->preparation_days = $this->preparationDaysCalculator();
        $this->pre_order_days = empty(pisol_dtt_get_setting('pi_preorder_days', 10)) ? 0 : abs(pisol_dtt_get_setting('pi_preorder_days', 10));
        $this->maxDays = $this->preparation_days + $this->pre_order_days;

        $this->allowed_dates = $this->allowedDates($this->type);
        $this->holidays = $this->getHolidays($this->type);
    }

    function preparationDaysCalculator(){
        $preparation_days = (int)pisol_dtt_get_setting('pi_order_preparation_days',0);
        return $preparation_days;
    }

    static function isDateValid($date, $type = ""){
        $obj = new self($type);
        $valid_dates = $obj->getValidDates();
        if(in_array($date, $valid_dates)) return true;
        
        return false;
    } 

    function getValidDates(){
        $valid_dates = array();

        $forced_dates = apply_filters('pisol_forced_valid_dates',array(), $this->type);
        if(!empty( $forced_dates )) return self::removeDatesOutSidePreparationTime($forced_dates);

        for($i = $this->preparation_days; $i <= $this->maxDays; $i++){
            $date = date($this->format, strtotime($this->today.' + '.$i.' days'));
            
            if($this->nonWorkingDay($date)) continue;

            if($this->isHoliday($date)) continue;

            if($this->isTimeNotAvailable( $date )) continue;

            $valid_dates[] = $date;
        }
        
        $valid_dates = apply_filters('pisol_valid_dates', $valid_dates, $this->type);
        $valid_dates = self::removePastDates($valid_dates);
        $valid_dates = self::removeDatesOutSidePreparationTime($valid_dates);
        return is_array($valid_dates) ? array_values($valid_dates) : array();
    }

    function removeDatesOutSidePreparationTime($dates){
        if(apply_filters('pisol_dtt_make_special_dates_not_follow_preparation_time',false)) return $dates;

            $today = current_time('Y/m/d');
            $today_timestamp = strtotime($today);
            $first_allowed_date = date('Y/m/d', strtotime($today.' + '.$this->preparation_days.' days'));
            foreach($dates as $key => $date){
                if(strtotime($date) < strtotime($first_allowed_date)){
                    if(apply_filters('pisol_dtt_make_date_follow_preparation_time',true, $date, $this->preparation_days)){
                        unset($dates[$key]);
                    }
                }
            }

            if(empty($dates)) return array();
            
            return is_array($dates) ? array_values($dates) : array();
    }

    static function removePastDates($dates){
        if(!is_array($dates)) array();
        $today = current_time('Y/m/d');
        $today_timestamp = strtotime($today);
        $new_dates = array();
        foreach($dates as $date){
            if(!empty($date)){
                $date_timestamp = strtotime($date);
                if($date_timestamp >= $today_timestamp){
                    $new_dates[] = $date;
                }
            }
        }
        return $new_dates;
    }

    /**
     * Handle once time class implemented
    */
    function isTimeNotAvailable( $date ){
        return !pisol_dtt_time::isTimeAvailable( $date );
    }

    function nonWorkingDay($date){
        $week = date('w', strtotime($date));
        $allowed_days = $this->allowed_dates;
        if(is_array($allowed_days) && in_array($week, $allowed_days)){
            return false;
        }
        return true;
    }

    function allowedDates($type){
        $var_name = 'pi_'.$type.'_days';
        $allowed_days = pisol_dtt_get_setting($var_name, array());
        if(empty( $allowed_days )){
            return array(0,1,2,3,4,5,6);
        }else{
            return $allowed_days;
        }
    }

    function isHoliday($date){
        $holidays = $this->holidays;
        if(is_array($holidays) && in_array($date,$holidays)){
            return true;
        }
        return false;
    }

    function getHolidays($type){
        $var_name = 'pisol_dtt_'.$type.'_dd';
        $holidays = pisol_dtt_get_setting($var_name, array());
        return $holidays;
    }

    static function formatedDate($date){
        $format = pisol_dtt_get_setting('date_format','F j, Y');
        $formated_date = strtotime($date) ? date($format, strtotime($date)) : $date;
        return $formated_date;
    }
    
    static function translatedDate( $date ){
        /**
         * H:i:s is needed else it create different time stamp when time is missing
         */
        $original_timezone = date_default_timezone_get();
        date_default_timezone_set( 'UTC' );
        $date = str_replace( '/', '-', $date );
        $datestamp = strtotime($date);
        if($datestamp){
            if(apply_filters('pi_dtt_bypass_date_i18n',false)){
                $date = date(pisol_dtt_get_setting( 'date_format' ), $datestamp);
            }else{
                $date = date_i18n(pisol_dtt_get_setting( 'date_format' ), $datestamp);
            }
            date_default_timezone_set( $original_timezone );
            return $date;
        }
        date_default_timezone_set( $original_timezone );
        return $date;
    }
}