<?php

use PISOL\CEFW\ExtraFees;
class Pi_cefw_Apply_fees{

    protected static $instance = null;

    public $fees_amount = [];

    public static function get_instance( ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
    
    function __construct(){
        add_action('woocommerce_cart_calculate_fees' , array($this,'addfees'), PHP_INT_MAX);
        //add_action('woocommerce_review_order_after_cart_contents', array($this, 'extraFeesSelector'));
        /*
        woocommerce_review_order_after_shipping => this is removed as it was not working for many themes and it will not work when shipping is disabled
        */
        add_action('woocommerce_review_order_after_order_total', array($this, 'extraFeesSelector'));
    }

    function get_fees_list(){
        return $this->fees_amount;
    }

    function add_to_fees_amount_list($name, $amount){
        $this->fees_amount[$name] = $amount;
    }

    function get_fees_amount( $name ){
        if(isset($this->fees_amount[$name])){
            $calculated_fees_amount = $this->fees_amount[$name];
        }else{
            $calculated_fees_amount = '';
        }
        return $calculated_fees_amount;
    }

    function addfees($cart){
        
        $fees_arg = $this->getFees( $cart );
        
        $this->applyFees($fees_arg, $cart);
       
    }

    function getFees($cart){
        $fees = ExtraFees::matched_fees( $cart );
        $fee_arg = [];
        foreach($fees as $fees){
            $fees_obj = new ExtraFees( $fees->ID );
            $title = $fees_obj->get_title();
            $fees_name = $fees_obj->get_name();
            $fees_id = $fees_obj->get_id();
            $fees_type = $fees_obj->get_type();
            $fees = $fees_obj->get_fees();
            
            $total = pisol_cefw_revertToBaseCurrency($cart->get_displayed_subtotal());
            $taxable = $fees_obj->is_taxable();
            $tax_class = $fees_obj->get_tax_class();


            if( $fees_obj->is_available()  ){
                if($fees_type == 'percentage'){
                    
                    $fees_value = $this->evaluate_cost($fees, $fees_id, $cart);

                    $fees_amount = $fees_value * $total  /100;
                
                }else{
                    $fees_amount = $this->evaluate_cost($fees, $fees_id, $cart);
                }

                $fees_amount = apply_filters('pi_cefw_add_additional_charges',$fees_amount, $fees_id, $cart);
                
                if($fees_amount > 0 || apply_filters('pisol_cefw_allow_discount', false, $fees_amount)){

                    $fees_amount = pisol_cefw_multiCurrencyFilters($fees_amount);

                    $this->add_to_fees_amount_list($fees_name, $fees_amount);

                    if($fees_obj->is_optional()){

                        if(!self::feesChecked($fees_id)){
                            continue;
                        }
                    }
                    
                     /**
                     * without this advance way of adding fees with ID
                     * we cant remove wc coupon based on condition
                     * as we cant find which discount is applied
                     */
                    $fee_arg[$fees_name] = array(
                        'id' => $fees_name,
                        'name'=> $fees_obj->get_title(),
                        'amount' => $fees_amount,
                        'taxable' => $fees_obj->is_taxable(),
                        'tax_class' => $fees_obj->get_tax_class(),
                        'pi_fees_id' => $fees_id
                    );

                   
                }
            }
        }

        return $fee_arg;
       
    }

    function applyFees($fees, $cart){
        if(empty($fees) || !is_array($fees)) return;

        $fees = apply_filters('pisol_cefw_fees_filter', $fees, $cart);

        foreach($fees as $fee_arg){
            $cart->fees_api()->add_fee( $fee_arg );
        }
    }

    /**
     * function taken from woocommerce / includes / shipping / flat_rate / class-wc-shipping-flat-rate.php
     * https://docs.woocommerce.com/document/flat-rate-shipping/
     * https://github.com/woocommerce/woocommerce/blob/9431b34f0dc3d1ed7b45807ffde75de4bb58f831/includes/shipping/flat-rate/class-wc-shipping-flat-rate.php
     */
	protected function evaluate_cost( $sum, $fees_id, $cart) {
	
        include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';

        // Allow 3rd parties to process shipping cost arguments.
        
        $locale         = localeconv();
        $decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );

        $this->short_code_fees_id = $fees_id;
        $this->short_code_cart = $cart;

        // Expand shortcodes.
        add_shortcode( 'selected_product_qty', array( $this, 'selected_product_qty' ) );

        add_shortcode( 'qty', array( $this, 'qty' ) );

        add_shortcode( 'selected_product_count', array( $this, 'selected_product_count' ) );

        $sum = do_shortcode( $sum );

        remove_shortcode( 'selected_product_qty', array( $this, 'selected_product_qty' ) );
        remove_shortcode( 'selected_product_count', array( $this, 'selected_product_count' ) );
        remove_shortcode( 'qty', array( $this, 'qty' ) );

        // Remove whitespace from string.
        $sum = preg_replace( '/\s+/', '', $sum );

        // Remove locale from string.
        $sum = str_replace( $decimals, '.', $sum );

        // Trim invalid start/end characters.
        $sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

        // Do the math.
        if($sum){
            try{
                $result = WC_Eval_Math::evaluate( $sum );
                return $result !== false ? $result : 0;
            }catch(Exception $e){
                return 0;
            }
        }
    }

    function qty($arg){
        $atts = shortcode_atts(
            array(
                'max_qty' => '',
                'max_product_qty'=> '',
                'excluded_products' => ''
            ),
            $arg,
            'qty'
        );
        $fees_id = $this->short_code_fees_id;
        $cart = $this->short_code_cart;

        $obj_for_qty = new pi_cefw_products_matching_rule($fees_id, $cart);
        $matched_product_qty = $obj_for_qty->getProductQty($atts['max_qty'], $atts['max_product_qty'], $atts['excluded_products']);
        return $matched_product_qty;
    }

    function selected_product_qty($arg){
            $atts = shortcode_atts(
                array(
                    'max_qty' => '',
                    'max_product_qty'=> '',
                    'excluded_products' => ''
                ),
                $arg,
                'selected_product_qty'
            );
            $fees_id = $this->short_code_fees_id;
            $cart = $this->short_code_cart;

            $obj_for_qty = new pi_cefw_products_matching_rule($fees_id, $cart);
            $matched_product_qty = $obj_for_qty->getMatchedProductsQuantity($atts['max_qty'], $atts['max_product_qty'], $atts['excluded_products']);
            return $matched_product_qty;
    }

    function selected_product_count($arg){
        $atts = shortcode_atts(
            array(
                'max_count' => '',
                'excluded_products' => ''
            ),
            $arg,
            'selected_product_qty'
        );
        $fees_id = $this->short_code_fees_id;
        $cart = $this->short_code_cart;

        $obj_for_qty = new pi_cefw_products_matching_rule($fees_id, $cart);
        $matched_product_count = $obj_for_qty->getMatchedProductsCount($atts['max_count'], $atts['excluded_products']);
        return apply_filters('pisol_cefw_selected_product_count',$matched_product_count, $atts, $fees_id, $cart );
    }

    function extraFeesSelector(){
        if(!function_exists('WC') || !isset(WC()->cart)) return;

        $cart = WC()->cart->get_cart();
        $fees = ExtraFees::matched_optional_fees($cart);
        $html = '';
        
        foreach($fees as $fee){
            $checked = '';
            $fees_obj = new ExtraFees( $fee->ID );

            $fees_id = $fees_obj->get_id();

            $title = $fees_obj->get_title();

            if($fees_obj->is_optional()){
                $name = $fees_obj->get_name();
                if(self::feesChecked($fees_id)){
                    $checked = ' checked="checked" ';
                }
                $html .= sprintf('<li><label><input type="checkbox" value="1" name="%s" class="pi-cefw-optional-fees" %s> %s</label></li>', $name, $checked, $title );
            }
        }
        echo !empty($html) ? sprintf('<tr><td colspan="2" class="pi-condition-fees"><strong>%s</strong><ul class="pi-cefw-optional-fees-list">%s</ul></td></tr>', esc_html(get_option('pisol_cefw_optional_services',__('Optional services', 'conditional-extra-fees-woocommerce'))), $html) : '';
    }

    static function feesChecked($fees_id){
        $name = 'pisol-cefw-fees:'.$fees_id;

        if(!isset($_POST['post_data']) && !isset($_POST[$name])){
            if(self::feesSelectedInSession($fees_id)){
                return true;
            }

            return 0;
        }  

        if(isset($_POST[$name])){
            $values[$name] = $_POST[$name];
        }else{
            parse_str($_POST['post_data'], $values);
        }

        if(!empty($values[$name])){
            self::saveFeesInSession($fees_id);
            return true;
        }else{
            self::removeFeesInSession($fees_id);
        }
        
        return false;
    }

    static function saveFeesInSession($fees_id){
        if(function_exists('WC') && is_object(WC()->session)){
            $stored_values = WC()->session->get('pisol_cefw_selected_fees');

            if(empty($stored_values) || !is_array($stored_values)) $stored_values = [];

            $stored_values[$fees_id] = true;

            WC()->session->set('pisol_cefw_selected_fees', $stored_values);
        }
    }

    static function removeFeesInSession($fees_id){
        if(function_exists('WC') && is_object(WC()->session)){
            $stored_values = WC()->session->get('pisol_cefw_selected_fees');

            if(empty($stored_values) || !is_array($stored_values)) return;

            $stored_values[$fees_id] = false;

            WC()->session->set('pisol_cefw_selected_fees', $stored_values);
        }
    }

    static function feesSelectedInSession($fees_id){
        if(function_exists('WC') && is_object(WC()->session)){
            $stored_values = WC()->session->get('pisol_cefw_selected_fees');

            $selected_by_default = 'no';

            if(empty($stored_values) || !is_array($stored_values)){
                

                if($selected_by_default == 'yes'){
                    self::saveFeesInSession($fees_id);
                    return true;
                }

                return false;
            } 

            if(!isset($stored_values[$fees_id]) && $selected_by_default == 'yes'){
                self::saveFeesInSession($fees_id);
                return true;
            }


            if(isset($stored_values[$fees_id]) && $stored_values[$fees_id] == true) return true;

        }
        return false;
    }
}