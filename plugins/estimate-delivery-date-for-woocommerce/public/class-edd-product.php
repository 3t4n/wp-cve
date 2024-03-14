<?php

class Pi_Edd_Product_Rule{

    protected $product_id;

    public $product_obj;

    protected $rule = array(
        'default_shipping_zone' => "",
        'min_max'=>'max',
        'date_format'   =>  'Y/m/d',
        'show_range' => 0,
        'last_shipment_time'    =>  "",
        'shipping_close_on_week'    =>  array(),
        'holidays'  => array(),
        'enable_estimate_for_this_product' => false,
        'product_preparation_time' => 0,
        'use_variation_preparation_time' => false,
        'variation_preparation_time' => null,
        'msg_tmp_single'=> 'Estimated delivery date',
        'msg_tmp_single_range'=> 'Estimated delivery between',
        'msg_tmp_loop' => 'Estimated delivery date',
        'msg_tmp_loop_range'=> 'Estimated delivery between',
        'msg_tmp_cart' => 'Estimated delivery date',
        'msg_tmp_cart_range'=> 'Estimated delivery between',
        'min_estimate_date' => "",
        'max_estimate_date' => "",
        'compiled_message'=> array()
    );

    function __construct($product_id){
        $this->product_id = $product_id;
        $this->product_obj = wc_get_product($product_id);
        
        $this->setGlobalRules();
        
    }

    function setEstimate(){
        /*get estimate */
        $estimate_obj = new Pi_Edd_Estimate($this);
        $min_estimate = $estimate_obj->estimate('min');
        $max_estimate = $estimate_obj->estimate('max');
        $this->rule['min_estimate_date'] = $min_estimate;
        $this->rule['max_estimate_date'] = $max_estimate;

        $this->setMessage();
    }

    function setMessage(){
        $msg_obj = new Pi_Edd_Message_Engine($this);
    }

    function setCompiledMessage($message){
        $this->rule['compiled_message'] = $message;
    }

    function getCompiledMessage(){
        return $this->rule['compiled_message'];
    }

    function getRule(){
        return $this->rule;
    }


    function setGlobalRules(){
        $this->rule['default_shipping_zone'] = get_option('pi_defaul_shipping_zone',null);
        $this->rule['show_range'] = get_option('pi_general_range',0);
        $this->rule['min_max'] = get_option('pi_edd_min_max','max'); 
        $this->rule['date_format'] = 'Y/m/d';
        $this->rule['last_shipment_time'] = "";
        $this->rule['shipping_close_on_week'] = array();
        $this->rule['holidays'] = $this->setHolidays();

        $this->setEnableEstimateForThisProduct(); // this should be run first
        $this->setProductPreparationDays();
        $this->setVariablePreparationDays();
        $this->setMessageTemplate();

        $this->setEstimate();
    }

    function setHolidays(){
        $holidays = get_option( 'pi_edd_holidays', array() );
        if(!empty($holidays)){
            $explode = explode( ":", $holidays );
            return $explode ;
        }
        return array();
    }

    /**
     * not showing estimate if product is out of stock
     * we can improve on this with variable level stock management
     * showing an estimate even when product is out of stock
     */
    function setEnableEstimateForThisProduct(){
		$disable_estimate = $this->product_obj->get_meta('pisol_edd_disable_estimate', true);

        if($disable_estimate == 'yes'){
            $this->rule['enable_estimate_for_this_product'] = false;
            return;
        }

		if( $this->product_obj->is_virtual() || $this->product_obj->is_type('external') ){
			$this->rule['enable_estimate_for_this_product'] = false;
		}else{
            $this->rule['enable_estimate_for_this_product'] = true;
        }
    }

    function setProductPreparationDays(){
        $product_preparation_time = get_post_meta($this->product_id,'product_preparation_time',true);
        $this->rule['product_preparation_time'] = (int)$product_preparation_time;
    }

    function setVariablePreparationDays(){
        $pisol_edd_use_variation_preparation_time = get_post_meta($this->product_id,'pisol_edd_use_variation_preparation_time',true);
    
        if($this->product_obj->is_type( 'variable' )){
            if($pisol_edd_use_variation_preparation_time == 'yes'){
                $this->rule['use_variation_preparation_time'] = true;
                $this->setIndividualVariablePreparationDays();
            }else{
                $this->rule['use_variation_preparation_time'] = false;
                $this->rule['variation_preparation_time'] = null;
            }
        }else{
            $this->rule['use_variation_preparation_time'] = false;
            $this->rule['variation_preparation_time'] = null;
        }
    }

    function setIndividualVariablePreparationDays(){
        $preparations_days = array();
        $variations = $this->product_obj->get_available_variations();
        foreach((array)$variations as $variation){
            $preparation_time = get_post_meta( $variation['variation_id'], 'pisol_preparation_days', true );
            $preparations_days[$variation['variation_id']] = (int)$preparation_time;
        }
        $this->rule['product_preparation_time'] = null;
        
        $this->rule['variation_preparation_time'] = $preparations_days;
    }

    function getEnableEstimateForThisProduct(){
		return $this->rule['enable_estimate_for_this_product'];
    }

    function getProductPreparationDays(){
        return $this->rule['product_preparation_time'];
    }

    function getVariablePreparationDays(){
        return $this->rule['variation_preparation_time'];
    }

    function useVariablePreparationTime(){
        if($this->rule['use_variation_preparation_time']){
            return true;
        }
        return false;
    }
    
    function getPreparationDays(){
        if($this->rule['use_variation_preparation_time']){
            return $this->rule['variation_preparation_time'];
        }else{
            return $this->rule['product_preparation_time'];
        }
    }

    function getShippingCutOffTime(){
        return $this->rule['last_shipment_time'];
    }

    function getHolidays(){
        return  $this->rule['holidays'];
    }

    function getWeekDayOff(){
        return  $this->rule['shipping_close_on_week'];
    }

    function getProductId(){
        return $this->product_id;
    }

    function showRange(){
        if($this->rule['show_range'] == 1){
            return true;
        }
        return false;
    }

    function getMinMax(){
        return $this->rule['min_max'];
    }

    function setMessageTemplate(){
        $this->rule['msg_tmp_single'] = self::getMessage("pi_product_page_text",$this->rule['msg_tmp_single']);

        $this->rule['msg_tmp_single_range'] = self::getMessage("pi_product_page_text_range",$this->rule['msg_tmp_single_range']);

        $this->rule['msg_tmp_loop'] = self::getMessage("pi_loop_page_text",$this->rule['msg_tmp_loop']);

        $this->rule['msg_tmp_loop_range'] = self::getMessage("pi_loop_page_text_range",$this->rule['msg_tmp_loop_range']);

       
        $this->rule['msg_tmp_cart'] = self::getMessage("pi_cart_page_text",$this->rule['msg_tmp_cart']);

        $this->rule['msg_tmp_cart_range'] = self::getMessage("pi_cart_page_text_range",$this->rule['msg_tmp_cart_range']);
       
        
    }

    function getMessageText($msg_name){
        return isset($this->rule[$msg_name]) ? $this->rule[$msg_name] : "";
    }

    public static function getMessage($variable, $default){
        $message = get_option($variable, $default);
        return $message;
    }

    
    public function getMinEstimateDate($variation_id = false){
        if($variation_id){
            return isset($this->rule['min_estimate_date'][$variation_id]) ? $this->rule['min_estimate_date'][$variation_id] : "";
        }
        return $this->rule['min_estimate_date'];
    }

    public function getMaxEstimateDate($variation_id = false){
        if($variation_id){
            return isset($this->rule['max_estimate_date'][$variation_id]) ? $this->rule['max_estimate_date'][$variation_id] : "";
        }
        return $this->rule['max_estimate_date'];
    }

    public function getVariations(){
        return $variations = $this->product_obj->get_available_variations();
    }

    public function getDateFormat(){
        return $this->rule['date_format'];
    }

    public function getCompiledMessageAsPerLocation($location, $variation_id = false){
        $compiled_message = $this->getCompiledMessage();
        
        if(empty($compiled_message)) return null;

        if($this->useVariablePreparationTime()){
            $variations = $this->getVariations();
            $message = array();
            foreach($variations as $variation){
                $message[$variation['variation_id']] = $this->getMessageAsPerLocation($compiled_message[$variation['variation_id']], $location);
            }

            if($variation_id){
                return isset($message[$variation_id]) ? $message[$variation_id] : "";
            }

            return $message ;

        }else{
            return $this->getMessageAsPerLocation($compiled_message,$location);
        }
    }

    public function getMessageAsPerLocation($message_array,$location){
        return isset($message_array[$location]) ? $message_array[$location] : "";
    } 
}


