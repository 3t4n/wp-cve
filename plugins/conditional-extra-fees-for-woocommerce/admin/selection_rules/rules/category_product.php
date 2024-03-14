<?php

class Pi_cefw_selection_rule_category_product{
    function __construct($slug){
        $this->slug = $slug;
        $this->condition = 'category_product';
        /* this adds the condition in set of rules dropdown */
        add_filter("pi_".$this->slug."_condition", array($this, 'addRule'));
        
        /* this gives value field to store condition value either select or text box */
        add_action( 'wp_ajax_pi_'.$this->slug.'_value_field_'.$this->condition, array( $this, 'ajaxCall' ) );

        /* This gives our field with saved value */
        add_filter('pi_'.$this->slug.'_saved_values_'.$this->condition, array($this, 'savedDropdown'), 10, 3);

        /* This perform condition check */
        add_filter('pi_'.$this->slug.'_condition_check_'.$this->condition,array($this,'conditionCheck'),10,4);

        /* This gives out logic dropdown */
        add_action('pi_'.$this->slug.'_logic_'.$this->condition, array($this, 'logicDropdown'));

        /* This give saved logic dropdown */
        add_filter('pi_'.$this->slug.'_saved_logic_'.$this->condition, array($this, 'savedLogic'),10,3);
    }

    function addRule($rules){
        $rules[$this->condition] = array(
            'name'=>__('Cart has product of category','conditional-extra-fees-woocommerce'),
            'group'=>'product_related',
            'condition'=>$this->condition
        );
        return $rules;
    }

    function logicDropdown(){
        $html = "";
        $html .= 'var pi_logic_'.$this->condition.'= "<select class=\'form-control\' name=\'pi_selection[{count}][pi_'.$this->slug.'_logic]\'>';
        
        $html .= '<option value=\'equal_to\' title=\'If any of the selected category product is present (non selected category product can also be there) then the rule is true, if none of the selected category product are there in the cart then it is false\'>Equal to (=)</option>';

        $html .= '<option value=\'not_equal_to\' title=\'If none of the selected category product is present in the cart then the rule is true, if any one of the selected category product is present then the rule is false\'>Not Equal to (!=)</option>';

        $html .= '<option value=\'must_not_have_all_selected_products\' title=\'If cart has all the selected category product then rule will be false (there can be other products as well), if even one of the selected category product is not there then rule will be true\' disabled>Must not have all the selected category products (PRO)</option>';

        $html .= '<option value=\'only_has_this_products\' title=\'If cart has any other category product other then the selected category product then rule will be false, if there is no other category product other then the selected one then it will be true\' disabled>Only have this category products in cart (PRO)</option>';

        $html .= '<option value=\'must_have_all_selected_products\' title=\'If cart has all the selected category product then rule will be true (there can be other products as well), if even one of the selected category product is not there then rule will be false\' disabled>Must have all the selected category products (PRO)</option>';
       
        $html .= '<option value=\'exact_this_selected_products\' title=\'If cart has this selected category product (no other non selected category product) then it will be true, If there is even one non selected category product or if even one of the selected category product is not there then it will be false\' disabled>Exactly this category products are in cart (PRO)</option>';

        
        $html .= '</select>';

        $html .= '<a href=\'https://www.piwebsolution.com/faq-for-conditional-extra-fees/#Cart_has_product_of_category\' target=\'_blank\'>Know more about this</a>";';

        echo $html;
    }

    function savedLogic($html_in, $saved_logic, $count){
        $html = "";
        $html .= '<select class="form-control" name="pi_selection['.$count.'][pi_'.$this->slug.'_logic]">';
        
        $html .= '<option value="equal_to" '.selected($saved_logic , "equal_to",false ).' title="If any of the selected category product is present (non selected category product can also be there) then the rule is true, if none of the selected category product are there in the cart then it is false">Equal to (=)</option>';

            $html .= '<option value="not_equal_to" '.selected($saved_logic , "not_equal_to",false ).' title="If none of the selected category product is present in the cart then the rule is true, if any one of the selected category product is present then the rule is false">Not Equal to (!=)</option>';

            $html .= '<option value="must_not_have_all_selected_products"  '.selected($saved_logic , "must_not_have_all_selected_products",false ).'  title="If cart has all the selected category product then rule will be false (there can be other products as well), if even one of the selected category product is not there then rule will be true" disabled>Must not have all the selected category products (PRO)</option>';
           
            $html .= '<option value="only_has_this_products" '.selected($saved_logic , "only_has_this_products",false ).' title="If cart has any other category product other then the selected category product then rule will be false, if there is no other category product other then the selected one then it will be true" disabled>Only have this category products in cart (PRO)</option>';

            $html .= '<option value="must_have_all_selected_products" '.selected($saved_logic , "must_have_all_selected_products",false ).' title="If cart has all the selected category product then rule will be true (there can be other products as well), if even one of the selected category product is not there then rule will be false" disabled>Must have all the selected category products (PRO)</option>';

            $html .= '<option value="exact_this_selected_products" '.selected($saved_logic , "exact_this_selected_products",false ).' title="If cart has this selected category product (no other non selected category product) then it will be true, If there is even one non selected category product or if even one of the selected category product is not there then it will be false" disabled>Exactly this category products are in cart (PRO)</option>';

        $html .= '</select>';

        $html .= '<a href="https://www.piwebsolution.com/faq-for-conditional-extra-fees/#Cart_has_product_of_category" target="_blank">Know more about this</a>';

        return $html;
    }

    function ajaxCall(){
        if(!current_user_can( 'manage_options' )) {
            return;
            die;
        }
        $count = filter_input(INPUT_POST,'count',FILTER_VALIDATE_INT);
        echo Pi_cefw_selection_rule_main::createSelect($this->allCategories(), $count, $this->condition,  "multiple",null,'static');
        die;
    }

    function savedDropdown($html, $values, $count){
        $html = Pi_cefw_selection_rule_main::createSelect($this->allCategories(), $count, $this->condition,  "multiple", $values,'static');
        return $html;
    }

    function allCategories(){
        $taxonomy     = 'product_cat';
		$post_status  = 'publish';
		$orderby      = 'name';
		$hierarchical = 1;      // 1 for yes, 0 for no
        $empty        = 0;
        
        $args               = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'taxonomy'       => 'product_cat',
			'orderby'        => 'name',
			'hierarchical'   => 1,
			'hide_empty'     => 0,
			'posts_per_page' => 1000,
        );
        $get_all_categories = get_categories( $args );
        $return_category = array();
        foreach($get_all_categories as $category){
           
            if ( $category->parent > 0 ) {
                $parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
                $return_category[$category->term_id] = $parent_category->name.' -&gt; '.$category->name;
            }else{
                $return_category[$category->term_id] = $category->name;
            }
        }
        return $return_category;
    }

    function conditionCheck($result, $package, $logic, $values){
        
                    $or_result = false;
                    $user_categories = $this->getCategoriesFromOrder($package);
                    $rule_categories = $values;
                    $intersect = array_intersect($rule_categories, $user_categories);
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

    function getCategoriesFromOrder($package){
        $products = WC()->cart->get_cart();
        $user_products_categories = array();
        foreach($products as $product){
            
            if($product['variation_id'] != 0){
                $product_obj = wc_get_product($product['product_id']);
            }else{
                $product_obj = $product['data'];
            }

            $product_categories = $product_obj->get_category_ids();
            foreach($product_categories as $product_category){
                $user_products_categories[] = $product_category;
            }
        }
        return $user_products_categories;
    }
}

new Pi_cefw_selection_rule_category_product(PI_CEFW_SELECTION_RULE_SLUG);