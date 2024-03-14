<?php

class Pi_Edd_Message_Engine{
    public $rule;
    public $msg_template_single_single;
    public $msg_template_single_range;
    public $msg_template_loop_single;
    public $msg_template_loop_range;    
    public $msg_template_cart_single;
    public $msg_template_cart_range;
    public $today;
    public $estimate_date;
    public $min_estimate_date;
    public $max_estimate_date;
    
   
    function __construct($rule){
        $this->rule = $rule;
        $this->msg_template_single_single = $this->messageTemplate('single');
        $this->msg_template_single_range = $this->messageTemplate('single','_range');
        $this->msg_template_loop_single = $this->messageTemplate('loop');
        $this->msg_template_loop_range = $this->messageTemplate('loop','_range');
        $this->msg_template_cart_single = $this->messageTemplate('cart');
        $this->msg_template_cart_range = $this->messageTemplate('cart','_range');

        $this->today  = current_time('Y/m/d');

        if($this->rule->getEnableEstimateForThisProduct()){
            $this->estimate_date = $this->rule->getMinMax() == 'min' ? $this->rule->getMinEstimateDate() : $this->rule->getMaxEstimateDate();

            $this->min_estimate_date = $this->rule->getMinEstimateDate();

            $this->max_estimate_date = $this->rule->getMaxEstimateDate();

            $this->generateMessage();
        }
    }

    function generateMessage(){
        

        if($this->rule->useVariablePreparationTime()){
            $variations = $this->rule->getVariations();
            $message = array();
            foreach($variations as $variation){
                $message[$variation['variation_id']] = $this->messageMaker($this->getDate($variation['variation_id']), $this->getDateMin($variation['variation_id']), $this->getDateMax($variation['variation_id']));
            }
            $this->rule->setCompiledMessage($message);

        }else{
            $message = $this->messageMaker($this->estimate_date, $this->min_estimate_date, $this->max_estimate_date);
            $this->rule->setCompiledMessage($message);
        }
    }

    function formatedDate($date){
        if(empty($date)) return null;
        return date_i18n('Y/m/d', strtotime($date));
    }

    function getDate($variation_id){
        return isset($this->estimate_date[$variation_id]) ? $this->estimate_date[$variation_id] : "";
    }
    function getDateMin($variation_id){
        return isset($this->min_estimate_date[$variation_id]) ? $this->min_estimate_date[$variation_id] : "";
    }

    function getDateMax($variation_id){
        return isset($this->max_estimate_date[$variation_id]) ? $this->min_estimate_date[$variation_id] : "";
    }

    function messageMaker($date, $min_date, $max_date){
        if(empty($min_date) && empty($max_date)) return null;

        $days = self::daysAwayFromToday($date);
        $min_days = self::daysAwayFromToday($min_date);
        $max_days = self::daysAwayFromToday($max_date);

        $date = $this->formatedDate($date);
        $min_date = $this->formatedDate($min_date);
        $max_date = $this->formatedDate($max_date);

        $find = array('{date}','{min_date}','{max_date}','{days}', '{min_days}', '{max_days}');
        $replace = array($date, $min_date, $max_date, $days, $min_days, $max_days);

        $msg['single'] =  $this->msg_template_single_single." ".$date;

        $msg['single_range'] =  $this->msg_template_single_range." ".$min_date." - ".$max_date;

        $msg['loop'] = $this->msg_template_loop_single." ".$date;

        $msg['loop_range'] =  $this->msg_template_loop_range." ".$min_date." - ".$max_date;

        $msg['cart'] = $this->msg_template_cart_single." ".$date;

        $msg['cart_range'] =  $this->msg_template_cart_range." ".$min_date." - ".$max_date;

        return $msg;
    }

    function messageTemplate($position, $range = ""){
        $msg = 'msg_tmp_'.$position.$range;
        return $this->rule->getMessageText($msg);  
    }

    static function daysAwayFromToday($estimate){
        $today = current_time("Y/m/d");
        $datetime1 = date_create($today); 
        $datetime2 = date_create($estimate); 
  
        $interval = date_diff($datetime1, $datetime2);
        return $interval->days;
    }

}