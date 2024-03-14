<?php
class WPS_EXT_CST_Admin
{
	public function __construct()
	{
		add_action( 'wp_ajax_wps_generate_new_fees', array($this,'wps_generate_new_fees' ));
	}
	public static function init(){
		add_action( 'admin_menu', array( 'WPS_EXT_CST_Admin', 'add_menu_extra_fee_option' ) );
		add_action("admin_init", array('WPS_EXT_CST_Admin_Settings',"register_admin_settings"));
		add_action( 'admin_enqueue_scripts', array('WPS_EXT_CST_Admin','selectively_enqueue_admin_script' ));
		
	}
	public static function add_menu_extra_fee_option() {
		$setting_menu_create = add_submenu_page( 'woocommerce' , __( 'Additional Fees'), __( 'Additional Fees' ), 'manage_options', 'wps-ext-cst-option', array(
				'WPS_EXT_CST_Admin_Settings','admin_settings'));
	}
	public static function selectively_enqueue_admin_script(){
		wp_register_style( 'WPS_EXT_CST_SELECT2_ADMIN_CSS', WPS_EXT_CST_CSS . '/wafc-select2.min.css', false, '1.0.0' );
        wp_enqueue_style( 'WPS_EXT_CST_SELECT2_ADMIN_CSS' );
        
		wp_register_style( 'WPS_EXT_CST_ADMIN_CSS', WPS_EXT_CST_CSS . '/admin-style.css', false, '1.0.0' );
        wp_enqueue_style( 'WPS_EXT_CST_ADMIN_CSS' );
	}
	public static function wps_generate_new_fees(){
		?>

		<div class="wps-ext-cst-fees" id="fees<?php echo $_POST['number'];?>">
    		<h3 style="border-bottom: 1px solid black;">
    			<span class="fees-title">Unlabelled Fees</span>
    			<span style="float:right; color:red; cursor: pointer;" class="dashicons dashicons-trash" onclick="remove_fees(<?php echo $_POST['number'];?>)"></span>
    		</h3>
    		<table class="form-table">
            	<tbody>
					<tr>
						<th scope="row"><label><?php _e( 'Status'); ?><label></th>
						<td>
							<select name="ext_cst_extra[<?php echo $_POST['number'];?>][status]" id="ext_cst_status_extra">
								<option value="enable">Enable</option>
								<option value="disable">Disable</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e( 'Label'); ?></labe></td>
						<td>
							<input type="text" name="ext_cst_extra[<?php echo $_POST['number'];?>][label]" class="regular-text code" id="ext_cst_label_extra" value="<?php echo 'Unlabelled Fees #'.$_POST['number'];?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e( 'Label for Billing'); ?></labe></td>
						<td>
							<input type="text" name="ext_cst_extra[<?php echo $_POST['number'];?>][label_billing]" class="regular-text code" id="ext_cst_label_billing_extra" value="Unlabelled Fees #<?php echo $_POST['number'];?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e( 'Type'); ?><label></th>
						<td>
							<select name="ext_cst_extra[<?php echo $_POST['number'];?>][amount_type]" id="ext_cst_amount_type_extra" onchange="showHideTaxShipping(this,<?php echo $_POST['number'];?>)">
								<option value="fixed">Fixed</option>
								<option value="percent">Percentage</option>
							</select>
							
						</td>
					</tr>
					<tr id="incTax-<?php echo $_POST['number']; ?>" class="incTax" style="display:none;">
						<th scope="row"><label><?php _e( 'Calculate fees including TAX'); ?></label></th>
						<td>
							<select>
								<option value="no">No</option>
								<option value="yes">Yes</option>
							</select>
							<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
						</td>
					</tr>
					<tr id="incShipCosts-<?php echo $_POST['number']; ?>" class="incShipCosts" style="display:none;">
						<th scope="row"><label><?php _e( 'Calculate fees including Shipping Costs'); ?></label></th>
						<td>
							<select>
								<option value="no">No</option>
								<option value="yes">Yes</option>
							</select>
							<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
						</td>
					</tr>
					<?php /* To show fee after tax row, change woocommerce/includes/class-wcorder.php(2047) get_order_item_totals file */ ?>
					<tr>
						<th scope="row"><label><?php _e( 'Amount'); ?></labe></td>
						<td>
							<input type="number" step="any" name="ext_cst_extra[<?php echo $_POST['number'];?>][amount]" class="fees_amount regular-text code" id="ext_cst_amount_extra<?php echo $_POST['number'];?>" value="1"/>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e( 'Apply Condition'); ?></label></th>
						<td>
							<select name="ext_cst_extra[<?php echo $_POST['number']; ?>][apply_type]" id="ext_cst_apply_type<?php echo $_POST['number']; ?>">
								<option value="one_time">One Time Only</option>
								<option value="multiply">Multiplied By Product Quantity</option>
							</select>
							<p>If you want to charge additional fees for each product quantity into cart then choose <b>Multiplied By Product Quantity.</b> otherwise choose One Time Only.</p>
							<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e( 'Auto-checked/Auto-applied the fees' ); ?><label></th>
						<td>
							<select>
								<option value="disable">Disable</option>
								<option value="enable">Enable</option>
							</select>
							<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e( 'Condition'); ?><label></th>
						<td>
							<select data-id="<?php echo $_POST['number'];?>" name="ext_cst_extra_apply_cndtn<?php echo $_POST['number'];?>" id="ext_cst_apply_cndtn_extra<?php echo $_POST['number'];?>" class="ext_cst_cndtn_dropdown"  onchange="show_hide_cndtn_extra(<?php echo $_POST['number'];?>)">
								<option value="all">All</option>
								<option value="cart_total_amount">Cart Total Amount</option>
								<option value="cart_no_product">Number of Product on Cart</option>
								<option value="selected_product">Selected Product</option>
								<option value="selected_category">Selected Category</option>
								<option value="selected_pr_type">Selected Product Type</option>
							</select>
						</td>
					</tr>
					<tr id="cart_total_amount<?php echo $_POST['number'];?>" class="cndtn_mode_extra<?php echo $_POST['number'];?>" style="display: none;">
						<th scope="row"><label><?php _e( 'Cart Amount'); ?></labe></td>
						<td>
							<label>Minimum</label>
							<input type="number" class="small-text" id="cart_total_amount_min_extra" value=""/>

							<label>Maximum</label>
							<input type="number" class="small-text" id="cart_total_amount_max_extra" value=""/>
							<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
						</td>
					</tr>
					<tr id="cart_no_product<?php echo $_POST['number'];?>" class="cndtn_mode_extra<?php echo $_POST['number'];?>" style="display: none;">
						<th scope="row"><label><?php _e( 'No. Of Product on Cart'); ?></labe></td>
						<td>
							<label>Minimum</label>
							<input type="number" class="small-text" id="cart_no_product_min_extra" value=""/>

							<label>Maximum</label>
							<input type="number" class="small-text" id="cart_no_product_max_extra" value=""/>
							<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
						</td>
					</tr>
					<tr id="selected_product<?php echo $_POST['number'];?>" class="cndtn_mode_extra<?php echo $_POST['number'];?>" style="display: none;">
						<th scope="row"><label><?php _e( 'Selected Product'); ?><label></th>
						<td>
							<select name="ext_cst_extra_selected_product_id_extra" id="ext_cst_extra_selected_product_id_extra" class="wps_wafc_multiselect" multiple="multiple">
								<?php
									$args = array(
										'post_type' => 'product',
										'posts_per_page' => -1
										);
									$loop = new WP_Query( $args );
									if ( $loop->have_posts() ) {
										while ( $loop->have_posts() ) : $loop->the_post();?>
											<option><?php echo get_the_title(); ?></option>
											<?php
										endwhile;
									} else {
										echo '<option>No products found</option>';
									}
									wp_reset_postdata();
								?>
							</select>
							<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
						</td>
					</tr>
					<tr id="selected_category<?php echo $_POST['number'];?>" class="cndtn_mode_extra<?php echo $_POST['number'];?>" style="display: none;">
						<th scope="row"><label><?php _e( 'Selected Product Category'); ?><label></th>
						<td>
							<select name="ext_cst_extra_selected_cat_id_extra" id="ext_cst_extra_selected_cat_id_extra" class="wps_wafc_multiselect" multiple="multiple">
								<?php
									$args = array(
										'taxonomy' => 'product_cat',
										'orderby' => 'name',
										'order' => 'ASC',
										'hide_empty' => false
									);
									$categories = get_categories( $args );
									if ( $categories ) {
										foreach( $categories as $category ) :?>
											<option>
												<?php echo $category->name.' ('.$category->count.')'; ?>
											</option>
											<?php
										endforeach;
									} else {
										echo '<option>No category found</option>';
									}
								?>
							</select>
							<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
						</td>
					</tr>
					<tr id="selected_pr_type<?php echo $_POST['number'];?>" class="cndtn_mode_extra<?php echo $_POST['number'];?>" style="display: none;">
						<th scope="row"><label><?php _e( 'Selected Product Type'); ?><label></th>
						<td>
							<select name="ext_cst_extra_selected_pr_type_extra" id="ext_cst_extra_selected_pr_type_extra" class="wps_wafc_multiselect" multiple="multiple">
								<?php
									$pr_types = array('simple','grouped','variable','external_affiliate');
									foreach ( $pr_types as $types ) :?>
										<option><?php echo str_replace("_"," / ",ucwords($types)); ?></option>
										<?php
									endforeach;
								?>
							</select>
							<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e( 'Required Field'); ?></labe></td>
						<td>
							<select>
								<option value="no">No</option>
								<option value="yes">Yes</option>
							</select>
							<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e( 'Hide Option At Checkout'); ?></label></th>
						<td>
							<select name="ext_cst_extra_hide_on_frontend" id="ext_cst_extra_hide_on_frontend">
								<option value="no">No</option>
								<option value="yes">Yes</option>
							</select>
							<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
						</td>
					</tr>
				</tbody>
            </table>
    	</div>
    	<script type="text/javascript">
    		$('.wps_wafc_multiselect').select2({
    			placeholder: "Select your choices",
    			allowClear: true
    		});
    	</script>
		<?php
		die;
	}
}new WPS_EXT_CST_Admin();

?>