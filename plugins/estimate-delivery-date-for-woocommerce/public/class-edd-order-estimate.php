<?php

class Pi_Edd_Order_Estimate{
    public $items;
    public $estimate;
    public $days;
    public $msg;
    public $date_format;
    public $min_max;
    public $msg_template;

    function __construct($items){
        $this->items = $items;
        $this->date_format = get_option('pi_general_date_format','Y/m/d');
        $this->min_max = get_option('pi_edd_min_max','min');
        $this->msg_template = Pi_Edd_Product_Rule::getMessage('pi_overall_estimate_text','Order estimated delivery date {date}');
        $this->estimate = $this->getLargestDate();
        if($this->estimate != ""){
            $this->days = Pi_Edd_Message_Engine::daysAwayFromToday($this->estimate);
            $this->msg = $this->messageMaker();
        }else{
            $this->days = "";
            $this->msg = "";
        }
    }

    function getMessage(){
        return $this->msg;
    }

    function getEstimates(){
        $estimates = array();
        foreach($this->items as $item){
            $rule_obj = new Pi_Edd_Product_Rule($item['product_id']);
            
            if($rule_obj->useVariablePreparationTime()){
                $variation_id = $item['variation_id'];
                if($this->min_max == 'min'){
                    $estimates[] = $rule_obj->getMinEstimateDate($variation_id);
                }else{
                    $estimates[] = $rule_obj->getMaxEstimateDate($variation_id);
                }
            }else{
                if($this->min_max == 'min'){
                    $estimates[] = $rule_obj->getMinEstimateDate();
                }else{
                    $estimates[] = $rule_obj->getMaxEstimateDate();
                }
            }
        }
        return $estimates;
    }

    function getLargestDate(){
        $estimates = $this->getEstimates();
        $longest = 0;
        $longestDate = "";

        foreach($estimates as $key => $date){
            if(!$date) continue;

            $curDate = strtotime($date);
            if ($curDate > $longest) {
                $longest = $curDate;
                $longestDate = $date;
            }
        }
        return $longestDate;
    }

    function validateDate($date, $format = 'Y/m/d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    function formatedDate($date){
        if(empty($date)) return null;

        return date_i18n($this->date_format, strtotime($date));
    }

    function messageMaker(){
        $find = array('{date}','{days}');
        $replace = array($this->formatedDate($this->estimate), $this->days);
        $msg = str_replace($find, $replace, $this->msg_template);
        return $msg;
    }
}