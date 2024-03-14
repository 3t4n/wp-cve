<?php
/*custom field archive*/
/* Dropdown in cart page start*/
if ( ! function_exists( 'woocommerce_quantity_input' ) ) {
	function woocommerce_quantity_input($args = array(), $product = null, $echo = true) {
	global $pqdfw_comman;
    if ( is_null( $product ) ) {
      	$product = $GLOBALS['product'];
    }
    
    if( !($product->is_type( 'grouped' )) ){

		$defaults = array(
			'input_id' => uniqid( 'quantity_' ),
		    'input_name' => 'quantity',
		    'input_value' => '1',
		    'classes' => apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text' ), $product ),
			'max_value'     => apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
			'min_value'     => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
			'step'         => apply_filters( 'woocommerce_quantity_input_step', '', $product ),
			'pattern' => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
		    'inputmode' => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
		    'product_name' => $product ? $product->get_title() : '',
		);

		$args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );

		// Apply sanity to min/max args - min cannot be lower than 0.
   		$defaults['min_value'] = max( $defaults['min_value'], 0 );
	    // Note: change 20 to whatever you like
	    $defaults['max_value'] = 0 < $defaults['max_value'] ? $defaults['max_value'] : 20;

	   	if (!empty($pqdfw_comman['pqdfw_min_quantity'])){
			$min = $pqdfw_comman['pqdfw_min_quantity'];
		}else {
			$min = $defaults['min_value'];
		}

		if (!empty($pqdfw_comman['pqdfw_max_quantity'])){
			$max = $pqdfw_comman['pqdfw_max_quantity'];
		}else {
			$max = $defaults['max_value'];
		}

		if (!empty($pqdfw_comman['pqdfw_step_quantity'])){
			$step = $pqdfw_comman['pqdfw_step_quantity'];
		}else {
			$step = 1;
		}

		/* For single product */
		$pro_id = $product->get_id();

		$pqdfw_pro_min_quantity= get_post_meta( $pro_id, 'pqdfw_pro_min_quantity', true );
      	$pqdfw_pro_max_quantity= get_post_meta( $pro_id, 'pqdfw_pro_max_quantity', true );
      	$pqdfw_pro_step_quantity= get_post_meta( $pro_id, 'pqdfw_pro_step_quantity', true );

      	/*echo "<pre>";
      	print_r(get_the_ID());
      	echo "</pre>";*/

		if (!empty($pqdfw_pro_min_quantity)){
			$min = $pqdfw_pro_min_quantity;
		}

		if (!empty($pqdfw_pro_max_quantity)){
			$max = $pqdfw_pro_max_quantity;
		}

		if (!empty($pqdfw_pro_step_quantity)){
			$step = $pqdfw_pro_step_quantity;
		}

		/* For single product end*/

		$options = '';

	   	for( $count = $min; $count <= $max; $count = $count + $step ) {
	      	// Cart item quantity defined?
	      	// if ( '' !== $defaults['input_value'] && $defaults['input_value'] >= 1 && $count == $defaults['input_value'] ) {
	        // 	$selected = 'selected';      
	      	// } else $selected = '';	

	      	if ( '' !== $args['input_value'] && $args['input_value'] >= 1 && $count == $args['input_value'] ) {
	        	$selected = 'selected';      
	      	} else $selected = '';

	      	$options .= '<option value="' . esc_attr($count) . '"' . esc_attr($selected) . '>' . esc_html($count) . '</option>';	 
	   	} 

	   	if ( '' !== $defaults['input_value'] && $defaults['input_value'] >= 1) {
        	$selected = 'selected';      
      	} else $selected = '';

	      	if(get_option('quantity_product_rule', 'all_product') == 'all_product' ){
	  			$lable = $pqdfw_comman['pqdfw_dropdown_lable'];
	        	?>
	        	<div class = "quantity_drop_down"> 
	  				<div class="drop_down_lable"><p><?php echo esc_html($lable); ?></p></div>
	  				<div class="quantity">
							<select class="qty_select" id="ss" name="<?php echo esc_attr($args['input_name']);?>" onchange="extendons_selectbox();">
								<?php echo _e($options,"product-quantity-dropdown-for-woocommerce-pro"); ?>
							</select>
						</div>				          						
						<input type="hidden" class="product_id" value="<?php echo esc_attr($product->get_id());?>">
					</div>
	        	<?php
	      	}elseif(get_option('quantity_product_rule', 'all_product') == 'specific_product' ){
				$var2 = 'false';		   		
			   	if(!empty(get_option('pqdfw_select2')) || !empty(get_option('pqdfw_cats_select2')) || !empty(get_option('pqdfw_tags_select2')) ){
			   		
			   		if(!empty(get_option('pqdfw_select2'))){
			   			if(in_array($pro_id , get_option('pqdfw_select2'))){
			   				$var2 = 'true';
			   			}
			   		}
	          		$terms = get_the_terms ( $pro_id, 'product_cat');
	                foreach ($terms as $key => $value) {
	                    if(!empty(get_option('pqdfw_cats_select2'))){
	                        if (in_array($value->term_id, get_option('pqdfw_cats_select2'))) {
	                          	$var2 = 'true';
	                        }
	                    }
	                }
	                $terms = get_the_terms( $pro_id, 'product_tag' );
	                if(!empty($terms)){                  
	                    foreach ($terms as $key => $value) {
	                        if(!empty(get_option('pqdfw_tags_select2'))){
	                            if (in_array($value->term_id, get_option('pqdfw_tags_select2'))) {
	                              	$var2 = 'true';
	                            }
	                        }
	                    }
	                }
	                
	       		}
	           	if($var2 == 'true'){
	            	$lable = $pqdfw_comman['pqdfw_dropdown_lable'];
	            	?>
	            	<div class = "quantity_drop_down"> 
	      				<div class="drop_down_lable"><p><?php echo esc_html($lable); ?></p></div>
	      				<div class="quantity">
								<select class="qty_select" id="ss" name="<?php echo esc_attr($defaults['input_name']);?>" onchange="extendons_selectbox();">
									<?php echo _e($options,"product-quantity-dropdown-for-woocommerce-pro"); ?>
								</select>
							</div>				          						
							<input type="hidden" class="product_id" value="<?php echo esc_attr($product->get_id());?>">
						</div>
	            	<?php 
				}
	        }
		}
	}
}
/* Dropdown in cart page end*/

/*custom field shop loop*/
function pqdfw_custom_quantity_field_shop_loop($args = array(), $product = null, $echo = true ){
	global $pqdfw_comman;
	if ( is_null( $product ) ) {
     	$product = $GLOBALS['product'];
   	}
   	if($product->is_in_stock()){
 
	   	$defaults = array(
		    'input_id' => uniqid( 'quantity_' ),
		    'input_name' => 'quantity',
		    'input_value' => '1',
		    'classes' => apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text' ), $product ),
		    'max_value' => apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
		    'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
		    'step' => apply_filters( 'woocommerce_quantity_input_step', '', $product ),
		    'pattern' => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
		    'inputmode' => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
		    'product_name' => $product ? $product->get_title() : '',
	  	);

	    $args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );

	   	// Apply sanity to min/max args - min cannot be lower than 0.
		$defaults['min_value'] = max( $defaults['min_value'], 0 );
	    // Note: change 20 to whatever you like
	    $defaults['max_value'] = 0 < $defaults['max_value'] ? $defaults['max_value'] : 20;

	   	if (!empty($pqdfw_comman['pqdfw_min_quantity'])){
			$min = $pqdfw_comman['pqdfw_min_quantity'];
		}else {
			$min = $defaults['min_value'];
		}

		if (!empty($pqdfw_comman['pqdfw_max_quantity'])){
			$max = $pqdfw_comman['pqdfw_max_quantity'];
		}else {
			$max = $defaults['max_value'];
		}

		if (!empty($pqdfw_comman['pqdfw_step_quantity'])){
			$step = $pqdfw_comman['pqdfw_step_quantity'];
		}else {
			$step = 1;
		}

		$options = '';
	    
	   	for( $count = $min; $count <= $max; $count = $count + $step ) {
	      	// Cart item quantity defined?
	      	if ( '' !== $defaults['input_value'] && $defaults['input_value'] >= 1 && $count == $defaults['input_value'] ) {
	        	$selected = 'selected';      
	      	} else $selected = '';		 
	      	$options .= '<option value="' . esc_attr($count) . '"' . esc_attr($selected) . '>' . esc_html($count) . '</option>';	 
	   	} 

	   	if ( '' !== $defaults['input_value'] && $defaults['input_value'] >= 1) {
	    	$selected = 'selected';      
	  	} else $selected = '';
	  	
	  	if(get_option('quantity_product_rule', 'all_product') == 'all_product' ){
	  		$lable = $pqdfw_comman['pqdfw_dropdown_lable']; 
	    	?>
	        <div class = "quantity_drop_down"> 
					<div class="drop_down_lable"><p><?php echo esc_html($lable);?></p></div>
					<div class="quantity">
						<select class="qty_select" name="<?php echo esc_attr($defaults['input_name']);?>" ><?php echo _e($options,"product-quantity-dropdown-for-woocommerce");?>
						</select>
					</div>				          						
				<input type="hidden" class="product_id" value="<?php echo esc_attr($product->get_id());?>">
			</div>
			<?php
	  	}

    }
}

function PQDFW_custom_css(){
	if(get_option('quantity_product_rule', 'all_product') == 'all_product'){
		?>
        <style type="text/css">
            .single-product .quantity>.text {
			    display: none;
			}
        </style>
        <?php
	}

}

/*all action here add */
function pqdfw_get_refresh_fragments(){
	if (!empty($_REQUEST['product']) && !empty($_REQUEST['quantity'])) {
		$product_id = sanitize_text_field($_REQUEST['product']);
		$quantity = sanitize_text_field($_REQUEST['quantity']);
		WC()->cart->add_to_cart( $product_id, $quantity );
	}
	die;
}

/*all action here add */
add_action('init' ,'pqdfw_action_add_here');
function pqdfw_action_add_here(){
global $pqdfw_comman;
	if($pqdfw_comman['enable_plugin'] == "yes" ){
		add_action( 'woocommerce_after_shop_loop_item', 'pqdfw_custom_quantity_field_shop_loop', 5 );
		add_action( 'wp_ajax_nopriv_pqdfw_get_refresh_fragments', 'pqdfw_get_refresh_fragments' );
		add_action( 'wp_ajax_pqdfw_get_refresh_fragments',  'pqdfw_get_refresh_fragments' );
	
		add_action( 'wp_head','PQDFW_custom_css' );				
	}  	        	
}
