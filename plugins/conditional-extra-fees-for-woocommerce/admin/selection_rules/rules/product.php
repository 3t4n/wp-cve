<?php

class Pi_cefw_selection_rule_product{
    function __construct($slug){
        $this->slug = $slug;
        $this->condition = 'product';

        add_filter("pi_".$this->slug."_condition", array($this, 'addRule'));

        add_action( 'wp_ajax_pi_'.$this->slug.'_value_field_'.$this->condition, array( $this, 'ajaxCall' ) );

        add_filter('pi_'.$this->slug.'_saved_values_'.$this->condition, array($this, 'savedDropdown'), 10, 3);
        
        add_action( 'wp_ajax_pi_'.$this->slug.'_options_'.$this->condition, array( $this, 'search_product' ) );
        
        add_filter('pi_'.$this->slug.'_condition_check_'.$this->condition, array($this,'conditionCheck'),10,4);

        add_action('pi_'.$this->slug.'_logic_'.$this->condition, array($this, 'logicDropdown'));
        add_filter('pi_'.$this->slug.'_saved_logic_'.$this->condition, array($this, 'savedLogic'),10,3);
    }

    function addRule($rules){
        $rules[$this->condition] = array(
            'name'=>__('Cart has Product','conditional-extra-fees-woocommerce'),
            'group'=>'product_related',
            'condition'=>$this->condition      
        );
        return $rules;
    }

    function logicDropdown(){
        $html = "";
        $html .= 'var pi_logic_'.$this->condition.'= "<select class=\'form-control\' name=\'pi_selection[{count}][pi_'.$this->slug.'_logic]\'>';
        
        $html .= '<option value=\'equal_to\' title=\'If any of the selected product is present (non selected product can also be there) then the rule is true, if none of the selected product are there in the cart then it is false\'>Equal to (=)</option>';

        $html .= '<option value=\'not_equal_to\' title=\'If none of the selected product is present in the cart then the rule is true, if any one of the selected product is present then the rule is false\'>Not Equal to (!=)</option>';

        $html .= '<option value=\'must_not_have_all_selected_products\' title=\'If cart has all the selected product then rule will be false (there can be other products as well), if even one of the selected product is not there then rule will be true\' disabled>Must not have all the selected products (PRO)</option>';

        $html .= '<option value=\'only_has_this_products\' title=\'If cart has any other product other then the selected product then rule will be false, if there is no other product other then the selected one then it will be true\'  disabled>Only have this products in cart (PRO)</option>';

        $html .= '<option value=\'must_have_all_selected_products\' title=\'If cart has all the selected product then rule will be true (there can be other products as well), if even one of the selected product is not there then rule will be false\'  disabled>Must have all the selected products (PRO)</option>';
        
        $html .= '<option value=\'exact_this_selected_products\' title=\'If cart has this selected product (no other non selected product) then it will be true, If there is even one non selected product or if even one of the selected product is not there then it will be false\' disabled>Exactly this products are in cart (PRO)</option>';
       
        
        $html .= '</select>';

        $html .= '<a href=\'https://www.piwebsolution.com/faq-for-conditional-extra-fees/#Cart_has_product_rule\' target=\'_blank\'>Know more about this</a>";';
        echo $html;
    }

    function savedLogic($html_in, $saved_logic, $count){
        $html = "";
        $html .= '<select class="form-control" name="pi_selection['.$count.'][pi_'.$this->slug.'_logic]">';
        
            $html .= '<option value="equal_to" '.selected($saved_logic , "equal_to",false ).' title="If any of the selected product is present (non selected product can also be there) then the rule is true, if none of the selected product are there in the cart then it is false">Equal to (=)</option>';

            $html .= '<option value="not_equal_to" '.selected($saved_logic , "not_equal_to",false ).' title="If none of the selected product is present in the cart then the rule is true, if any one of the selected product is present then the rule is false">Not Equal to (!=)</option>';
           
            $html .= '<option value="must_not_have_all_selected_products"  '.selected($saved_logic , "must_not_have_all_selected_products",false ).'  title="If cart has all the selected product then rule will be false (there can be other products as well), if even one of the selected product is not there then rule will be true"  disabled>Must not have all the selected products (PRO)</option>';

            $html .= '<option value="only_has_this_products" '.selected($saved_logic , "only_has_this_products",false ).' title="If cart has any other product other then the selected product then rule will be false, if there is no other product other then the selected one then it will be true" disabled>Only have this products in cart (PRO)</option>';

            $html .= '<option value="must_have_all_selected_products" '.selected($saved_logic , "must_have_all_selected_products",false ).' title="If cart has all the selected product then rule will be true (there can be other products as well), if even one of the selected product is not there then rule will be false" disabled>Must have all the selected products (PRO)</option>';

            $html .= '<option value="exact_this_selected_products" '.selected($saved_logic , "exact_this_selected_products",false ).' title="If cart has this selected product (no other non selected product) then it will be true, If there is even one non selected product or if even one of the selected product is not there then it will be false" disabled>Exactly this products are in cart (PRO)</option>';

        $html .= '</select>';

        $html .= '<a href="https://www.piwebsolution.com/faq-for-conditional-extra-fees/#Cart_has_product_rule" target="_blank">Know more about this</a>';

        return $html;
    }


    function ajaxCall(){
        if(!current_user_can( 'manage_options' )) {
            return;
            die;
        }
        $count = filter_input(INPUT_POST,'count',FILTER_VALIDATE_INT);
        echo Pi_cefw_selection_rule_main::createSelect(array(), $count, $this->condition,  "multiple", null,'dynamic');
        die;
    }

    function savedProducts($values){
        $saved_products = array();
        if(is_array($values)){
            foreach($values as $value){
                $prod_obj = wc_get_product($value);
                if(is_wp_error( $prod_obj ) || !is_object($prod_obj) ) continue;
                $saved_products[$value] = strip_tags( $prod_obj->get_formatted_name());
            }
        }
        
        return $saved_products;
    }

    function savedDropdown($html, $values, $count){
        $html = Pi_cefw_selection_rule_main::createSelect($this->savedProducts($values), $count, $this->condition,  "multiple", $values,'dynamic');
        return $html;
    }

    public function search_product( $x = '', $post_types = array( 'product' ) ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

        ob_start();
        
        if(!isset($_GET['keyword'])) die;

		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : "";

		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
			'post_status'    => 'publish',
			'post_type'      => $post_types,
			'posts_per_page' => 50,
			's'              => $keyword

		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$prd = wc_get_product( get_the_ID() );

				if ( $prd->has_child() && $prd->is_type( 'variable' ) ) {
                    /** This is for the variable product */
                    $found_products[] = array(
                        'id'   => get_the_ID(),
                        'text' => strip_tags($prd->get_formatted_name())
                    );;
					$product_children = $prd->get_children();
					if ( count( $product_children ) ) {
						foreach ( $product_children as $product_child ) {
							
                            $child_wc  = wc_get_product( $product_child );
                            $product   = array(
                                'id'   => $product_child,
                                'text' => strip_tags($child_wc->get_formatted_name())
                            );

							
							$found_products[] = $product;
						}

                    }
                    
                    
				} else {
					$product_id    = get_the_ID();
					$the_product   = new WC_Product( $product_id );
					$product_title = strip_tags($the_product->get_formatted_name());
					$product          = array( 'id' => $product_id, 'text' => $product_title );
					$found_products[] = $product;
				}
			}
        }
		wp_send_json( $found_products );
		die;
    }

    function conditionCheck($result, $package, $logic, $values){
        
       

        $or_result = false;
        
            
                
                    $user_products = $this->getProductsFromOrder();
                    if(is_array($values)){
                        $rule_products = $values;
                    }else{
                        $rule_products = array();
                    }
                    $intersect = array_intersect($rule_products, $user_products);
                    if($logic == 'equal_to'){
                        if(count($intersect) > 0){
                            $or_result = true;
                        }else{
                            $or_result = false;
                        }
                    }else{
                        if(count($intersect) == 0){
                            $or_result = true;
                        }else{
                            $or_result = false;
                        }
                    }
               
        return  $or_result;    
       
    }

    function getProductsFromOrder(){
        $products = WC()->cart->get_cart();
        $user_products = array();
        foreach($products as $product){
            $product_obj = $product['data'];
            if($product_obj instanceof WC_Product_Variation){
                $user_products[] = $product_obj->get_parent_id();
            }
            $user_products[] = $product_obj->get_ID();
        }
        return $user_products;
    }

}

new Pi_cefw_selection_rule_product(PI_CEFW_SELECTION_RULE_SLUG);

/**
 *
 * @param string $version
 *
 * @return bool
 */
if ( ! function_exists( 'woocommerce_version_check' ) ) {
	function woocommerce_version_check( $version = '3.0' ) {
		global $woocommerce;

		if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}

		return false;
	}
}