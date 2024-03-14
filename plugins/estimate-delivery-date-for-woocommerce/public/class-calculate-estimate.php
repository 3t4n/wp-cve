<?php
/**
 * input: preparation days
 */
class Pi_Edd_Estimate{
    
    protected $min_days;

    protected $max_days;

    protected $estimated_days;

    protected $current_date;

    protected $current_time;

    public $rule;
    
    
    function  __construct($rule){
        $this->rule = $rule;
        
        $this->current_date = current_time('Y/m/d');
        $this->current_time = current_time('H:i');

        $min_max_obj = new Pi_edd_min_max();
        $this->min_days = $min_max_obj->getMinDays();
        $this->max_days = $min_max_obj->getMaxDays();

    }

    function estimate($min_max = 'min'){
        if(!$this->rule->getEnableEstimateForThisProduct()) return false;

        
            if($min_max == 'min'){
                $shipping_days = $this->min_days;
            }else{
                $shipping_days = $this->max_days;
            }
        if(empty($shipping_days)) return null;

        if($this->rule->useVariablePreparationTime()){
            return $this->variableEstimate($shipping_days);
        }else{
            return $this->estimateCalculator($shipping_days, $this->rule->getPreparationDays());
        }
    }

    function variableEstimate($shipping_days){
        $variations = $this->rule->getVariablePreparationDays();
        $variation_estimate = array();
        foreach($variations as $variation_id => $preparation_days){
            $variation_estimate[$variation_id] = $this->estimateCalculator($shipping_days, $preparation_days);
        }
        return $variation_estimate;
    }

    function estimateCalculator($shipping_days, $preparation_days){
            $working_days = $this->workingDaysNeeded($shipping_days, $preparation_days );
            return $this->getExactDate($working_days);
    }

    function workingDaysNeeded($shipping_days, $preparation_days ){
        $delivery_in_working_days = $shipping_days + $preparation_days;
        return $delivery_in_working_days;
    }

    /**
     * it adds holidays, wek off , cut off and give exact date
     */
    function getExactDate($working_days){
        if($this->checkTodayShippingPossible()){
            $first_day = $this->current_date;
        }else{
            $first_day = date('Y/m/d', strtotime($this->current_date."+1 days"));
        }


        $working_days_array = array();
        $count = 0;
        while(count($working_days_array) < $working_days){
            $date = date('Y/m/d', strtotime($first_day."+".$count." days"));
            if($this->todayShippingWorking($date)){
                $working_days_array[] = $date;
            }
            $count++;
        }

        return end($working_days_array);
    }

    function todayShippingWorking($date){
        if($this->isHoliday($date) || $this->isSkipDay($date)){
            return false;
        }
        return true;
    }

    static function addDays($date, $days_to_add){
        return date('Y/m/d', strtotime(' + '.$days_to_add.' days', strtotime($date)));
    }

    /**
     * check for cut off time, check for holiday, check for week day off
     */
    function checkTodayShippingPossible(){

        $shipping_cutoff_time = $this->rule->getShippingCutOffTime();
        /**
         * if there is not cut off time set, we return false
         */
        if(empty($shipping_cutoff_time) || $shipping_cutoff_time == ""){
            return false;
        }

        
        $now = current_time('H:i');
        $cut_off_time = $shipping_cutoff_time;
        
        /**
         * If today is in holiday we return false as we cant to shipping today
         */
        $today = current_time('Y/m/d');
        
        if ( $this->isHoliday( $today ) ){
            return false;
        }


        if ( $this->isSkipDay( $today ) ){
            return false;
        }

        /**
         * if present time is below cut off time will return true
         */
        if(strtotime($cut_off_time) > strtotime($now)){
			return true;
        }
        
        return false;
    }
    
    function isHoliday($date){
        $holidays = $this->rule->getHolidays();
        if(is_array($holidays)){
            return in_array($date, $holidays) ? true : false;
        }
        return false;
    }

    function isSkipDay($date){
        $day = date('N',strtotime($date));
        $skip_days_of_the_week = $this->rule->getWeekDayOff();
        $skip_days_of_the_week = is_array($skip_days_of_the_week) ? $skip_days_of_the_week : array();
        
        if(in_array($day, $skip_days_of_the_week)){
            return true;
        }
        return false;
    }
}