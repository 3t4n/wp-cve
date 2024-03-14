<?php
class WPS_EXT_CST_Extra_Fees_Frontend
{
	public function __construct()
	{
		$extra_options = get_option('ext_cst_extra');
		if(is_array($extra_options) && !empty($extra_options)){
			foreach ($extra_options as $option => $value) {
				$status = $value['status'] ? $value['status'] : 'enable';
				if( $status == 'enable' && !is_admin() ){
					add_action( 'woocommerce_after_order_notes', array($this,'add_checkbox_after_order_notes' ));
					add_action( 'wp_footer', array($this,'add_extra_script_on_checkout_page' ));
					add_action( 'woocommerce_cart_calculate_fees', array($this,'calculate_extra_costs' ));
					break;
				}
			}
		}

	}

	
	public static function get_extra_condition( $extra_rule ){
		global $woocommerce;
		$cndtn = $extra_rule['apply_cndtn'];
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
	public static function add_extra_script_on_checkout_page(){
		if (is_checkout()) {
			$extra_options = get_option('ext_cst_extra');
			if(is_array($extra_options) && !empty($extra_options)){
				foreach ($extra_options as $option => $value) {
					$status = $value['status'] ? $value['status'] : 'enable';
					if( $status == 'enable' && !is_admin() ){
						$key = 'wps_ext_cst_label_extra_'.$option;
    	?>
						    <script type="text/javascript">
						    jQuery( document ).ready(function( $ ) {
						        $('#<?php echo $key; ?>').click(function(){
						            jQuery('body').trigger('update_checkout');
						        });
						    });
						    </script>
	    <?php
	    			}

	    		}
	    	}
	    }
	}
	public static function add_checkbox_after_order_notes( $checkout ){
		global $woocommerce;
		$extra_options = get_option('ext_cst_extra');
		if(is_array($extra_options) && !empty($extra_options)){
			foreach ($extra_options as $option => $value) {
				$status = $value['status'] ? $value['status'] : 'enable';
				$get_cndtn = WPS_EXT_CST_Extra_Fees_Frontend::get_extra_condition($value);
				
				if( $status == 'enable' && !is_admin() && $get_cndtn){
					$is_required_extra = ($value['ext_cst_is_required_extra']=='yes') ? true : false;
					$key = 'wps_ext_cst_label_extra_'.$option;
					$field_id = 'wps_ext_cst_extra_field_'.$option;
					$label = $value['label'] ? $value['label'] : 'Unlabelled Fees';
					$extra_final_cost = WPS_EXT_CST_Extra_Fees_Frontend::calculate_final_cost_extra($value);


				   	echo '<div id="'.$field_id.'">';
					    woocommerce_form_field( $key, array(
					        'type'          => 'checkbox',
					        'class'         => array('wps_ext_cst_label_extra_option form-row-wide'),
					        'label'         => $label,
					        'required'      => false,
					        'placeholder'   => __('')
					        ), $checkout->get_value( $key ));
					echo "<input type='hidden' name='cost_amount_hidden_".$key."' value=".$extra_final_cost." />";
					echo "</div>";
				}
			}
		}
	}
	public static function calculate_final_cost_extra( $fees ){
		global $woocommerce;
		
		$ext_ext_cst_amount_type = ($fees['amount_type']) ? $fees['amount_type'] : 'fixed';
		$ext_ext_cst_amount 	 = ($fees['amount']) ? $fees['amount'] : 1;
		$ext_ext_cst_apply_cndtn = ($fees['apply_cndtn']) ? $fees['apply_cndtn'] : 'all';

		$cost = $ext_ext_cst_amount;

		switch ($ext_ext_cst_amount_type) {
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

	public static function calculate_extra_costs( $cart ){
        if ( ! $_POST || ( is_admin() && ! is_ajax() ) ) {
        	return;
	    }

	    if ( isset( $_POST['post_data'] ) ) {
	        parse_str( $_POST['post_data'], $post_data );
	    } else {
	        $post_data = $_POST;
	    }

	    $extra_options = get_option('ext_cst_extra');
		if(is_array($extra_options) && !empty($extra_options)){
			foreach ($extra_options as $option => $value) {
				$status = $value['status'] ? $value['status'] : 'enable';

				if( $status == 'enable' ){
					$key = 'wps_ext_cst_label_extra_'.$option;
					if( array_key_exists($key,$post_data) ){
						$billing_label = $value['label_billing'] ? $value['label_billing'] : 'Unlabelled Fees';
				    	$cost_key = 'cost_amount_hidden_'.$key;
				    	$extracost = $post_data[$cost_key];
				    	WC()->cart->add_fee( $billing_label, $extracost );
					}
				}
			}
		}
	}

	

}new WPS_EXT_CST_Extra_Fees_Frontend();

?>