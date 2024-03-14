<?php
class WPS_EXT_CST_Frontend
{
	public function __construct()
	{
		$ext_cst_status 	 = (get_option('ext_cst_status')) ? get_option('ext_cst_status') : 'enable';
		if($ext_cst_status == 'enable' && !is_admin()){
			add_action( 'woocommerce_after_order_notes', array($this,'add_option_to_checkout' ));
			add_action( 'wp_footer', array($this,'add_script_on_checkout' ));
			add_action( 'woocommerce_cart_calculate_fees', array($this,'apply_the_cost_to_cart' ));
		}
	}
	
	public static function get_condition( $cndtn ){
		global $woocommerce;
		$products_ids_array = array();
		$items = $woocommerce->cart->get_cart();
		foreach( $items as $item => $values ){
			$products_ids_array[] = $values['product_id'];
		}
		switch ($cndtn) {
			case 'all':
				return true;
				break;
			
			default:
				return true;
				break;
		}
	}
	public static function add_option_to_checkout( $checkout ){
		global $woocommerce;
		$ext_cst_apply_cndtn = 'all';
		
		$get_cndtn = WPS_EXT_CST_Frontend::get_condition($ext_cst_apply_cndtn);
		if($get_cndtn){
			$ext_cst_label 	 	 = (get_option('ext_cst_label')) ? get_option('ext_cst_label') : 'Unlabelled Fees';

			$ext_cst_amount_type = (get_option('ext_cst_amount_type')) ? get_option('ext_cst_amount_type') : 'fixed';
			$ext_cst_amount 	 = (get_option('ext_cst_amount')) ? get_option('ext_cst_amount') : 1;
			$final_cost = WPS_EXT_CST_Frontend::calculate_final_cost($ext_cst_apply_cndtn,$ext_cst_amount,$ext_cst_amount_type);

			echo '<div id="wp_ext_cst_field">';
		    woocommerce_form_field( 'wps_ext_cst_label', array(
		        'type'          => 'checkbox',
		        'class'         => array('wps_ext_cst_label form-row-wide'),
		        'label'         => $ext_cst_label,
		        'required'      => false,
		        'placeholder'   => __('')
		        ), $checkout->get_value( 'wps_ext_cst_label' ));
		    echo "<input type='hidden' name='extra_cost_amount' value=".$final_cost." />";
		    echo '</div>';
		}
	   
	}

	public static function calculate_final_cost( $cndtn, $amount, $type ){
		global $woocommerce;
		$cost = $amount;

		switch ($type) {
    		case 'fixed':
    			$extracost =  $cost;
    			break;
    		case 'percent':

    		    $extracost = ( $woocommerce->cart->cart_contents_total ) * $cost;
    			$extracost = ( $extracost )/100;
    			break;
    		default:
    			$extracost =  $cost;
    			break;
    	}

    	return $extracost;
	}

	public static function add_script_on_checkout(){
		if (is_checkout()) {
			$ext_cst_label_css   = (get_option('ext_cst_label_css')) ? get_option('ext_cst_label_css') : '';
    	?>
		    <script type="text/javascript">
		    jQuery( document ).ready(function( $ ) {
		        $('#wps_ext_cst_label').click(function(){
		            jQuery('body').trigger('update_checkout');
		        });
		    });
		    </script>
		    <style>
		    	<?php echo $ext_cst_label_css; ?>
		    </style>
	    <?php
	    }
	    
	}

	public static function apply_the_cost_to_cart( $cart ){
        $ext_cst_label_billing 	= (get_option('ext_cst_label_billing')) ? get_option('ext_cst_label_billing') : 'Unlabelled Fees';
        
        global $woocommerce;

        if ( ! $_POST || ( is_admin() && ! is_ajax() ) ) {
        	return;
	    }

	    if ( isset( $_POST['post_data'] ) ) {
	        parse_str( $_POST['post_data'], $post_data );
	    } else {
	        $post_data = $_POST;
	    }
	   // echo "<pre>"; print_r($post_data); echo "</pre>";
	    if (isset($post_data['wps_ext_cst_label']) && isset($post_data['extra_cost_amount'])) {
	    	global $woocommerce;
	    	$extracost = $post_data['extra_cost_amount'];
	        WC()->cart->add_fee( $ext_cst_label_billing, $extracost );
	    }
	}


	

}new WPS_EXT_CST_Frontend();

?>