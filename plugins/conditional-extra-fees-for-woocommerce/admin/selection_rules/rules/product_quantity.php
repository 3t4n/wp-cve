<?php

class Pi_cefw_selection_rule_product_quantity{
    function __construct($slug){
        $this->slug = $slug;
        $this->condition = 'product_quantity';
        /* this adds the condition in set of rules dropdown */
        add_filter("pi_".$this->slug."_condition", array($this, 'addRule'));
        
        /* this gives value field to store condition value either select or text box */
        add_action( 'wp_ajax_pi_'.$this->slug.'_value_field_'.$this->condition, array( $this, 'ajaxCall' ) );

        /* This gives our field with saved value */
        add_filter('pi_'.$this->slug.'_saved_values_'.$this->condition, array($this, 'savedDropdown'), 10, 3);

        add_action( 'wp_ajax_pi_'.$this->slug.'_options_'.$this->condition, array( __CLASS__, 'search_product' ) );

        /* This perform condition check */
        add_filter('pi_'.$this->slug.'_condition_check_'.$this->condition,array($this,'conditionCheck'),10,4);

        /* This gives out logic dropdown */
        add_action('pi_'.$this->slug.'_logic_'.$this->condition, array($this, 'logicDropdown'));

        /* This give saved logic dropdown */
        add_filter('pi_'.$this->slug.'_saved_logic_'.$this->condition, array($this, 'savedLogic'),10,3);
    }

    function addRule($rules){
        $rules[$this->condition] = array(
            'name'=>__('Specific product quantity (PRO also support Variable Product)'),
            'group'=>'product_related',
            'condition'=>$this->condition
        );
        return $rules;
    }

    function logicDropdown(){
        $html = "";
        $html .= 'var pi_logic_'.$this->condition.'= "<select class=\'form-control\' name=\'pi_selection[{count}][pi_'.$this->slug.'_logic]\'>';
    
            $html .= '<option value=\'equal_to\'>Equal to ( = )</option>';
			$html .= '<option value=\'less_equal_to\'>Less or Equal to ( &lt;= )</option>';
			$html .= '<option value=\'less_then\'>Less than ( &lt; )</option>';
			$html .= '<option value=\'greater_equal_to\'>Greater or Equal to ( &gt;= )</option>';
			$html .= '<option value=\'greater_then\'>Greater than ( &gt; )</option>';
			$html .= '<option value=\'not_equal_to\'>Not Equal to ( != )</option>';
            $html .= '<option value=\'multiple_of\'>Multiple of</option>';
            $html .= '<option value=\'not_multiple_of\'>Not Multiple of</option>';
        
        $html .= '</select>";';
        echo $html;
    }

    function savedLogic($html_in, $saved_logic, $count){
        $html = "";
        $html .= '<select class="form-control" name="pi_selection['.$count.'][pi_'.$this->slug.'_logic]">';

            $html .= '<option value=\'equal_to\' '.selected($saved_logic , "equal_to",false ).'>Equal to ( = )</option>';
			$html .= '<option value=\'less_equal_to\' '.selected($saved_logic , "less_equal_to",false ).'>Less or Equal to ( &lt;= )</option>';
			$html .= '<option value=\'less_then\' '.selected($saved_logic , "less_then",false ).'>Less than ( &lt; )</option>';
			$html .= '<option value=\'greater_equal_to\' '.selected($saved_logic , "greater_equal_to",false ).'>Greater or Equal to ( &gt;= )</option>';
			$html .= '<option value=\'greater_then\' '.selected($saved_logic , "greater_then",false ).'>Greater than ( &gt; )</option>';
			$html .= '<option value=\'not_equal_to\' '.selected($saved_logic , "not_equal_to",false ).'>Not Equal to ( != )</option>';
            $html .= '<option value=\'not_multiple_of\' '.selected($saved_logic , "not_multiple_of",false ).'>Not Multiple of</option>';
        
        
        $html .= '</select>';
        return $html;
    }

    function ajaxCall(){
        if(!current_user_can('manage_options')) {
            return;
            die;
        }
        $count = filter_input(INPUT_POST,'count',FILTER_VALIDATE_INT);
        $html_class = self::createSelect(array(), $count, $this->condition,  "",  array(),'dynamic');
        $html_total =  self::createNumberField($count, $this->condition, null);
        echo self::bootstrapRow($html_class, $html_total);
        die;

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

    static function bootstrapRow($left, $right){
        return sprintf('<div class="row"><div class="col-6">%s</div><div class="col-6">%s</div></div>', $left, $right);
    }

    function savedDropdown($html, $values, $count){
        $html_class = self::createSelect(self::savedProducts($values), $count, $this->condition,  "", $values,'dynamic');
        $qty = isset($values['quantity']) ? $values['quantity'] : 0;
        $html_total = self::createNumberField($count, $this->condition,  $qty);
        return self::bootstrapRow($html_class, $html_total);
    }

    static function createSelect($array, $count, $condition ="",  $multiple = "",  $values = array(), $dynamic = ""){

        if($multiple === 'multiple'){
            $multiple = ' multiple="multiple" ';
        }else{
            $multiple = '';
        }

        $html = '<select class="form-control pi_condition_value pi_values_'.$dynamic.'" data-condition="'.$condition.'" name="pi_selection['.$count.'][pi_'.PI_CEFW_SELECTION_RULE_SLUG.'_condition_value][product]" '.$multiple.' placeholder="Select">';
        foreach ($array as $key => $value){
                $selected = "";
                if(is_array($values) && in_array($key, $values)){
                    $selected = ' selected="selected" ';
                }
                $html .= '<option value="'.$key.'" '.$selected.'>';
            $html .= $value;
            $html .= '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    static function createNumberField($count, $condition ="",  $value = "", $step = 'any'){

        
        $html = '<input type="number" step="'.$step.'" class="form-control" data-condition="'.$condition.'" name="pi_selection['.$count.'][pi_'.PI_CEFW_SELECTION_RULE_SLUG.'_condition_value][quantity]" value="'.$value.'" placeholder="'.__('Total Quantity').'" >';
        return $html;
    }


    function conditionCheck($result, $package, $logic, $values){
        
                    $or_result = false;
                    

                    if(isset($values['product'])){
                        $product_quantity = $this->getProductQuantity($values['product']);
                        
                        $rule_cart_total = $values['quantity'];
                        switch ($logic){
                            case 'equal_to':
                                if($product_quantity == $rule_cart_total){
                                    $or_result = true;
                                }
                            break;
    
                            case 'less_equal_to':
                                if($product_quantity <= $rule_cart_total){
                                    $or_result = true;
                                }
                            break;
    
                            case 'less_then':
                                if($product_quantity < $rule_cart_total){
                                    $or_result = true;
                                }
                            break;
    
                            case 'greater_equal_to':
                                if($product_quantity >= $rule_cart_total){
                                    $or_result = true;
                                }
                            break;
    
                            case 'greater_then':
                                if($product_quantity > $rule_cart_total){
                                    $or_result = true;
                                }
                            break;
    
                            case 'not_equal_to':
                                if($product_quantity != $rule_cart_total){
                                    $or_result = true;
                                }
                            break;

                            case 'multiple_of':
                                if( $product_quantity > 0 && $product_quantity % $rule_cart_total === 0 ){
                                    $or_result = true;
                                }
                            break;

                            case 'not_multiple_of':
                                if( $product_quantity > 0 && $product_quantity % $rule_cart_total !== 0 ){
                                    $or_result = true;
                                }
                            break;
                        }

                    }
               
        return  $or_result;
    }

    function getProductQuantity($saved_product){
        $products = WC()->cart->get_cart();
        $product_qty = 0;
        foreach($products as $product){
            $product_id = $product['product_id'];
            $variation_id = $product['variation_id'];
            if($saved_product == $product_id || $saved_product == $variation_id){
                $product_qty = $product_qty + $product['quantity'];
            }
        }
        return $product_qty;
    }

    static function savedProducts($values){
        $saved_products = array();
        if(is_array($values) && isset($values['product'])){
            
            $product = wc_get_product($values['product']);

            if(!is_object($product)) return $saved_products;

            $saved_products[$values['product']] = $product->get_formatted_name();
            
        }
        
        return $saved_products;
    }

    static function search_product( $x = '', $post_types = array( 'product' ) ) {
		if ( ! current_user_can('manage_options' ) ) {
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
			'posts_per_page' => -1,
			's'              => $keyword

		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
                $product_id = get_the_ID();
				$prd = wc_get_product( $product_id );

                if(!is_object( $prd )) continue;

                if($prd->is_type( 'variable' )) continue;

                $product_title = $prd->get_formatted_name();
                if ( ! $prd->is_in_stock() ) {
                    $product_title .= ' (Out of stock)';
                }
                $product          = array( 'id' => $product_id, 'text' => $product_title );
                $found_products[] = $product;

                

                if ( $prd->has_child() && $prd->is_type( 'variable' ) ) {
                    $product_children = $prd->get_children();
					if ( count( $product_children ) ) {
						foreach ( $product_children as $product_child ) {
                            $child_prod = wc_get_product($product_child);
                            if(!is_object($child_prod)) continue;
                            $found_products[] = array(
                                'id'   => $product_child,
                                'text' => strip_tags($child_prod->get_formatted_name())
                            );
                        }
                    }
                }
				
			}
        }
		wp_send_json( $found_products );
		die;
    }
}


new Pi_cefw_selection_rule_product_quantity(PI_CEFW_SELECTION_RULE_SLUG);