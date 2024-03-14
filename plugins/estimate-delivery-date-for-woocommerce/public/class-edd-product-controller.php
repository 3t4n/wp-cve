<?php

class Pi_Edd_Product_Controller{

    protected $rule = array(
        'single_page_position' => 'woocommerce_before_add_to_cart_button',
        'product_loop_position' => 'woocommerce_after_shop_loop_item_title',
        'show_range'    => false,
    );
    public $range;

    function __construct(){
        $this->setProductPagePosition();
        $this->setProductLoopPosition();
        $this->setSingleOrRange();
    

        add_action( 'wp_enqueue_scripts', array($this,'enqueue_scripts') );

        if($this->showOnSinglePage()){
        add_action($this->rule['single_page_position'], array($this,'estimateOnProductPage'));
        }

        if($this->showOnLoopPage()){
        add_action($this->rule['product_loop_position'], array($this,'estimateOnLoopPage'));
        }

        if($this->showOnCartPage()){
        add_action('woocommerce_after_cart_item_name', array($this,'estimateOnCartPage'),10,2);
        }

        if($this->showOnCheckoutPage()){
        add_filter('woocommerce_checkout_cart_item_quantity', array($this,'estimateOnCheckoutPage'),10,3);
        }

       

        
    }

   

    
    function estimateOnProductPage(){
        global $product;
        $product_id = $product->get_id();
        $rule_obj = new Pi_Edd_Product_Rule($product_id);
        if($rule_obj->getEnableEstimateForThisProduct()){
            if($rule_obj->useVariablePreparationTime()){
                echo '<div id="pisol_variation_estimate">';
                echo '</div>';
            }else{
                
                $msg = $rule_obj->getCompiledMessageAsPerLocation('single'.$this->range);
                //print_r($rule_obj);
                Pi_Edd_Template_Engine::message('single'.$this->range, $msg);
            }
        }
    }

    function estimateOnLoopPage(){
        global $product;
        $product_id = $product->get_id();
        $rule_obj = new Pi_Edd_Product_Rule($product_id);
        if($rule_obj->getEnableEstimateForThisProduct() && $product->is_in_stock()){
        if($rule_obj->useVariablePreparationTime()){
            //Pi_Edd_Template_Engine::message('loop',"");
        }else{
            $msg = $rule_obj->getCompiledMessageAsPerLocation('loop'.$this->range);
            Pi_Edd_Template_Engine::message('loop'.$this->range, $msg);
        }
        }
    }

    function estimateOnCartPage($cart_item, $cart_item_key){
       
        $product_id = $cart_item['product_id'];
        $rule_obj = new Pi_Edd_Product_Rule($product_id);
        if($rule_obj->getEnableEstimateForThisProduct()){
        if($rule_obj->useVariablePreparationTime()){
            $msg = $rule_obj->getCompiledMessageAsPerLocation('cart'.$this->range, $cart_item['variation_id']);
            echo '<br>';
            Pi_Edd_Template_Engine::message('cart'.$this->range, $msg);
        }else{
            $msg = $rule_obj->getCompiledMessageAsPerLocation('cart'.$this->range);
            echo '<br>';
            Pi_Edd_Template_Engine::message('cart'.$this->range, $msg);
        }
        }
    }

   
    function estimateOnCheckoutPage($link_text, $cart_item, $cart_item_key){
        $product_id = $cart_item['product_id'];
        $rule_obj = new Pi_Edd_Product_Rule($product_id);
        if($rule_obj->getEnableEstimateForThisProduct()){
        if($rule_obj->useVariablePreparationTime()){
            $msg = $rule_obj->getCompiledMessageAsPerLocation('cart'.$this->range, $cart_item['variation_id']);
            return $link_text.'<br>'.Pi_Edd_Template_Engine::message('cart'.$this->range, $msg, true);
        }else{
            $msg = $rule_obj->getCompiledMessageAsPerLocation('cart'.$this->range);
            echo '<br>';
            return $link_text.'<br>'.Pi_Edd_Template_Engine::message('cart'.$this->range, $msg, true);
        }
        }
        return $link_text;
    }

    function setProductPagePosition(){
        $position = get_option('pi_product_page_position','woocommerce_before_add_to_cart_button');
        $show = 1;
        if($show){
            $this->rule['single_page_position'] = $position;
        }else{
            $this->rule['single_page_position'] = false;
        }
    }

    function setProductLoopPosition(){
        $position = get_option('pi_loop_page_position','woocommerce_after_shop_loop_item_title');
        $show = 1;
        if($show){
            $this->rule['product_loop_position'] = $position;
        }else{
            $this->rule['product_loop_position'] = false;
        }
    }

    function setSingleOrRange(){
        $range = get_option('pi_general_range',0);
        if($range == 1){
            $this->rule['show_range'] = true;
        }else{
            $this->rule['show_range'] = false;
        }
        $this->range = $this->showRange() ? "_range" : "";

    }

    function showRange(){
        return $this->rule['show_range'];
    }

    function getProductPagePosition(){
		return $this->rule['single_page_position'];
    }

    function getProductLoopPosition(){
		return $this->rule['product_loop_position'];
    }

    function enqueue_scripts(){
        if(function_exists('is_cart') && is_cart()){
            wp_enqueue_script('pi-edd-script', plugin_dir_url( __FILE__ ) . 'js/pi-edd-public.js', array( 'jquery' ), "", false );
        }

        if( is_product() ){
            global $post;
            $product_id = $post->ID;
            $rule_obj = new Pi_Edd_Product_Rule($product_id);
            if($rule_obj->getEnableEstimateForThisProduct()){
                $js = $rule_obj->getCompiledMessageAsPerLocation('single');
                $js_var = '
                    var variation_estimate_msg = '.wp_json_encode($js).';
                ';
                wp_register_script( 'pi-edd-variable-product', '', array('jquery') );
                wp_enqueue_script( 'pi-edd-variable-product'  );
                wp_add_inline_script( 'pi-edd-variable-product',  $js_var, 'after');
            }
        }
    }

   

    function showOnSinglePage(){
       return true;
    }

    function showOnLoopPage(){
       return true;
    }

    function showOnCartPage(){
       return true;
    }

    function showOnCheckoutPage(){
        return true;
    }

    function showOverAllOrderEstimate(){
        return false;
    }

    function addEstimateInEachItemOfOrder(){
        $option = get_option('pi_edd_cart_page_show_single_estimate', 1);
        if(!empty($option)){
            return true;
        }
        return false;
    }

    function overallEstimateInOrderEmail(){
        $option = get_option('pi_edd_show_overall_estimate_in_email', 1);
        if(!empty($option)){
            return true;
        }
        return false;
    }

    function overallEstimateInOrderSuccessPage(){
        $option = get_option('pi_edd_show_overall_estimate_in_order_success_page', 1);
        if(!empty($option)){
            return true;
        }
        return false;
    }

}


add_action('wp_loaded',function(){
    if(!empty(get_option('pi_edd_enable_estimate',1))){
        new Pi_Edd_Product_Controller();
    }
});