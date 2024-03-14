<?php
global $wp; 
if( isset( $_GET['delcart'] ) )
{
	$rtwwdpdl_products_option = get_option( 'rtwwdpdl_cart_rule' );
	$rtwwdpdl_row_no = sanitize_text_field( $_GET['delcart'] );
	array_splice( $rtwwdpdl_products_option, $rtwwdpdl_row_no, 1 );
	update_option( 'rtwwdpdl_cart_rule', $rtwwdpdl_products_option );
	$rtwwdpdl_new_url = esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_cart_rules' );
	header('Location: '. $rtwwdpdl_new_url);
	die();
}

if(isset($_POST['rtwwdpdl_cart_rule'])){
	if( isset( $_POST['rtwwdpd_cart_field'] ) && wp_verify_nonce( $_POST['rtwwdpd_cart_field'], 'rtwwdpd_cart' ) ) 
	{
		
		$rtwwdpdl_prod = $_POST;
		$rtwwdpdl_option_no = sanitize_text_field( $rtwwdpdl_prod['edit_chk_cart'] );
		$rtwwdpdl_products_option = get_option('rtwwdpdl_cart_rule');
		if( $rtwwdpdl_products_option == '' )
		{
			$rtwwdpdl_products_option = array();
		}
		$rtwwdpdl_products = array();
		$rtwwdpdl_products_array = array();

		foreach( $rtwwdpdl_prod as $key => $val ){
			$rtwwdpdl_products[ $key ] = $val;
		}
		if($rtwwdpdl_option_no != 'save'){
			$rtw_edit_row = isset( $_REQUEST['editcart'] ) ? sanitize_text_field( $_REQUEST['editcart'] ) : '';
			unset( $rtw_edit_row );
			$rtwwdpdl_products_option[ $rtwwdpdl_option_no ] = $rtwwdpdl_products;
		}
		else{
			$rtwwdpdl_products_option[] = $rtwwdpdl_products;
		}
		update_option('rtwwdpdl_cart_rule',$rtwwdpdl_products_option);

		?>
		<div class="notice notice-success is-dismissible">
			<p><strong><?php esc_html_e('Rule saved.','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e('Dismiss this notices.','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
			</button>
		</div><?php
	}else {
		esc_html_e( 'Sorry, your are not allowed to access this page.' , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
   		exit;
	}
}
if( isset( $_GET['editcart'] ) )
{	
	$rtwwdpdl_url = esc_url( admin_url('admin.php').add_query_arg( $_GET, $wp->request ));
	
	$rtwwdpdl_products_option = get_option( 'rtwwdpdl_cart_rule' );
	$rtwwdpdl_prev_prod = $rtwwdpdl_products_option[ sanitize_text_field ( $_GET['editcart'] ) ];
	$key = 'editcart';
	$filteredURL = preg_replace('~(\?|&)'.$key.'=[^&]*~', '$1', $rtwwdpdl_url);

	$rtwwdpdl_new_url = esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_cart_rules');

	?>
	<div class="rtwwdpdl_right">
		<div class="rtwwdpdl_add_buttons">
			<input class="rtw-button rtwwdpdl_single_prod_rule" id="rtwwdpdl_single_cart_rule" type="button" name="rtwwdpdl_single_prod_rule" value="<?php esc_attr_e( 'Add New Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		</div>

		<div class="rtwwdpdl_add_single_rule rtwwdpdl_active rtwwdpdl_form_layout_wrapper">
			<form action="<?php echo esc_url($rtwwdpdl_new_url); ?>" method="POST" accept-charset="utf-8">
				<?php wp_nonce_field( 'rtwwdpd_cart', 'rtwwdpd_cart_field' ); ?>
				<div id="woocommerce-product-data" class="postbox ">
					<div class="inside">
						<div class="panel-wrap product_data">
							<ul class="product_data_tabs wc-tabs">
								<li class="rtwwdpdl_rule_tab active">
									<a class="rtwwdpdl_link" id="rtwproduct_rule">
										<span><?php esc_html_e('Rule','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
									</a>
								</li>
								<li class="rtwwdpdl_restriction_tab">
									<a class="rtwwdpdl_link" id="rtwproduct_restrict">
										<span><?php esc_html_e('Restrictions','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
									</a>
								</li>
								<li class="rtwwdpdl_time_tab">
									<a class="rtwwdpdl_link" id="rtwproduct_validity">
										<span><?php esc_html_e('Validity','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
									</a>
								</li>
							</ul>

							<div class="panel woocommerce_options_panel">
								<div class="options_group rtwwdpdl_active" id="rtwwdpdl_rule_tab">
									<input type="hidden" id="edit_chk_cart" value="<?php echo esc_attr( sanitize_text_field( $_GET['editcart'] ));?>" name="edit_chk_cart">	
									<table class="rtwwdpdl_table_edit">
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<input type="text" name="rtwwdpdl_cart_offer" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" required="required" value="<?php echo isset($rtwwdpdl_prev_prod['rtwwdpdl_cart_offer']) ? esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_cart_offer']) : ''; ?>">

												<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Check for', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<select name="rtwwdpdl_check_for" id="rtwwdpdl_check_for">
													<option value="rtwwdpdl_quantity" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_check_for'], 'rtwwdpdl_quantity' ); ?>><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></option>
													<option value="rtwwdpdl_price" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_check_for'], 'rtwwdpdl_price' ); ?>><?php esc_html_e( 'Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></option>
													<option value="rtwwdpdl_weight" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_check_for'], 'rtwwdpdl_weight'); ?>><?php esc_html_e( 'Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></option>
												</select>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Rule can be applied for either on Price/ Quantity/ Weight.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<?php $rtwwdpdl_check_var = 'Quantity'; ?>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Minimum ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<input type="number" value="<?php echo isset( $rtwwdpdl_prev_prod['rtwwdpdl_min'] ) ? esc_attr( $rtwwdpdl_prev_prod['rtwwdpdl_min']) : '' ; ?>" min="0" name="rtwwdpdl_min">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum value to check', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Maximum ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<input type="number" value="<?php echo isset($rtwwdpdl_prev_prod['rtwwdpdl_max']) ? esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_max']) : '' ; ?>" min="0" name="rtwwdpdl_max">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Maximum value to check', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<select name="rtwwdpdl_discount_type">
													<option value="rtwwdpdl_discount_percentage" <?php selected($rtwwdpdl_prev_prod['rtwwdpdl_discount_type'], 'rtwwdpdl_discount_percentage') ?>>
												<?php esc_html_e( 'Percent Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
													<option value="rtwwdpdl_flat_discount_amount" <?php selected($rtwwdpdl_prev_prod['rtwwdpdl_discount_type'], 'rtwwdpdl_flat_discount_amount') ?>>
														<?php esc_html_e( 'Flat Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
													<option value="rtwwdpdl_fixed_price" <?php selected($rtwwdpdl_prev_prod['rtwwdpdl_discount_type'], 'rtwwdpdl_fixed_price') ?>>
														<?php esc_html_e( 'Fixed Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
												</select>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Choose discount type.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Discount Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
											</td>
											<td>
												<input type="number" value="<?php echo isset($rtwwdpdl_prev_prod['rtwwdpdl_discount_value']) ? $rtwwdpdl_prev_prod['rtwwdpdl_discount_value'] : ''; ?>" required="required" min="0" step="0.1" name="rtwwdpdl_discount_value">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Discount should be given according to discount type.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
									</table>
								</div>
								<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_restriction_tab">
									<table class="rtwwdpdl_table_edit">
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Exclude Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
											</td>
											<td>
												<select disabled="disabled" class="wc-product-search rtwwdpdl_prod_class" multiple="multiple" name="product_exe_id[]" data-action="woocommerce_json_search_products_and_variations" placeholder="<?php esc_html_e('Search for a product','rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?>" >
												</select>
												<i class="rtwwdpdl_description rtwwdpdl_text_pro"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Exclude Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
											</td>
											<td>
												<select disabled="disabled" name="category_exe_id[]" class="wc-enhanced-select form-field rtwwdpdl_prod_class" multiple placeholder="<?php esc_html_e('Search for a category','rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?>">
												<option value="0"><?php esc_html_e('0','rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?></option>
														
												</select>
												<i class="rtwwdpdl_description rtwwdpdl_text_pro"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<select disabled="disabled" class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles[]" multiple="multiple">
												<option selected value="all">
													<?php esc_html_e( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
											</select>
											<i class="rtwwdpdl_description rtwwdpdl_text_pro"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Select Required Product', 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai' ); ?>
											</label>
										</td>
										<td>
											<select class="wc-product-search rtwwdpdl_prod_class" name="rtwwdpdl_select_product[]" data-action="woocommerce_json_search_products_and_variations" multiple="multiple" placeholder="<?php esc_html_e('Search for a product','rtwwdpd-woo-dynamic-pricing-discounts-with-ai') ?>" >
											<?php
											if(isset($rtwwdpdl_prev_prod['rtwwdpdl_select_product']))
											{
												$products = $rtwwdpdl_prev_prod['rtwwdpdl_select_product'];
												if(is_array($products) && !empty($products))
												{
													foreach($products as $key => $val)
													{
														$product = wc_get_product($val);
														if (is_object($product)) {
														echo '<option value="' . esc_attr($val) . '"' . selected(true, true, false) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
														}
													}
												}
											}
											?>
											</select>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Select product for this offer.', 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai' ); ?>
											</i>
										</td>
									</tr>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Maximum Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<input type="number" value="<?php echo isset($rtwwdpdl_prev_prod['rtwwdpdl_max_discount']) ? esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_max_discount']) : '' ; ?>" min="0" name="rtwwdpdl_max_discount">
											<i class="rtwwdpdl_description"><?php esc_html_e( 'This is used to set a threshold limit on the discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
											</i>
										</td>
									</tr>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Exclude sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<input type="checkbox" value="yes" name="rtwwdpdl_exclude_sale" <?php checked(isset($rtwwdpdl_prev_prod['rtwwdpdl_exclude_sale'])?$rtwwdpdl_prev_prod['rtwwdpdl_exclude_sale']:'', 'yes'); ?>/>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'This will exclude the discount from the products that are on sale.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
								</table>
							</div>
							<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_time_tab">
								<table class="rtwwdpdl_table_edit">
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Valid from', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</label>
									</td>
									<td>
										<input type="date" name="rtwwdpdl_from_date" placeholder="YYYY-MM-DD" required="required" value="<?php echo esc_attr( $rtwwdpdl_prev_prod['rtwwdpdl_from_date']); ?>" />
										<i class="rtwwdpdl_description"><?php esc_html_e( 'The date from which the rule would be applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Valid To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</label>
									</td>
									<td>
										<input type="date" name="rtwwdpdl_to_date" placeholder="YYYY-MM-DD" required="required" value="<?php echo esc_attr( $rtwwdpdl_prev_prod['rtwwdpdl_to_date']); ?>"/>
										<i class="rtwwdpdl_description"><?php esc_html_e( 'The date till which the rule would be applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
										</i>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="rtwwdpdl_prod_single_save_n_cancel rtwwdpdl_btn_save_n_cancel">
			<input class="rtw-button rtwwdpdl_save_rule" type="submit" name="rtwwdpdl_cart_rule" value="<?php esc_attr_e( 'Update Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			<input class="rtw-button rtwwdpdl_cancel_rule" type="submit" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		</div>
	</form>
</div>
</div>
<?php
}
else{

	?>
	<div class="rtwwdpdl_right">
		<div class="rtwwdpdl_add_buttons">
			<input class="rtw-button rtwwdpdl_single_prod_rule" id="rtwwdpdl_single_cart_rule" type="button" name="rtwwdpdl_single_prod_rule" value="<?php esc_attr_e( 'Add New Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		</div>

		<div class="rtwwdpdl_add_single_rule rtwwdpdl_form_layout_wrapper">
			<form action="" method="POST" accept-charset="utf-8">
				<?php wp_nonce_field( 'rtwwdpd_cart', 'rtwwdpd_cart_field' ); ?>
				<div id="woocommerce-product-data" class="postbox ">
					<div class="inside">
						<div class="panel-wrap product_data">
							<ul class="product_data_tabs wc-tabs">
								<li class="rtwwdpdl_rule_tab active">
									<a class="rtwwdpdl_link" id="rtwproduct_rule">
										<span><?php esc_html_e('Rule','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
									</a>
								</li>
								<li class="rtwwdpdl_restriction_tab">
									<a class="rtwwdpdl_link" id="rtwproduct_restrict">
										<span><?php esc_html_e('Restrictions','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
									</a>
								</li>
								<li class="rtwwdpdl_time_tab">
									<a class="rtwwdpdl_link" id="rtwproduct_validity">
										<span><?php esc_html_e('Validity','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
									</a>
								</li>
							</ul>

							<div class="panel woocommerce_options_panel">
								<div class="options_group rtwwdpdl_active" id="rtwwdpdl_rule_tab">
									<input type="hidden" id="edit_chk_cart" value="save" name="edit_chk_cart">	
									<?php //woocommerce_admin_fields( rtwwdpdl_cart_rule()); ?>
									<table class="rtwwdpdl_table_edit">
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<input type="text" name="rtwwdpdl_cart_offer" placeholder="" required="required" value="<?php echo isset($rtwwdpdl_prev_prod['rtwwdpdl_cart_offer']) ? esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_cart_offer']) : ''; ?>">

												<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Check for', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<select name="rtwwdpdl_check_for" id="rtwwdpdl_check_for">
													<option value="rtwwdpdl_quantity" ><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></option>
													<option value="rtwwdpdl_price" ><?php esc_html_e( 'Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></option>
													<option value="rtwwdpdl_weight"><?php esc_html_e( 'Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></option>
												</select>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Rule can be applied for either on Price/ Quantity/ Weight.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<?php $rtwwdpdl_check_var = 'Quantity'; ?>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Minimum ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<input type="number" value="" min="0" name="rtwwdpdl_min">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum value to check', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Maximum ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<input type="number" value="" min="0" name="rtwwdpdl_max">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Maximum value to check', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<select name="rtwwdpdl_discount_type">
													<option value="rtwwdpdl_discount_percentage" >
												<?php esc_html_e( 'Percent Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
													<option value="rtwwdpdl_flat_discount_amount" >
														<?php esc_html_e( 'Flat Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
													<option value="rtwwdpdl_fixed_price" >
														<?php esc_html_e( 'Fixed Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
												</select>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Choose discount type.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Discount Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
											</td>
											<td>
												<input type="number" value="" required="required" min="0" step="0.1" name="rtwwdpdl_discount_value">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Discount should be given according to discount type.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
									</table>
								</div>
								<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_restriction_tab">
									<?php //woocommerce_admin_fields( rtwwdpdl_cart_restrict()); ?>
									<table class="rtwwdpdl_table_edit">
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Exclude Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
											</td>
											<td>
												<select disabled="disabled" class="wc-product-search rtwwdpdl_prod_class" multiple="multiple" name="product_exe_id[]" data-action="woocommerce_json_search_products_and_variations" placeholder="<?php esc_html_e('Search for a product','rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?>" >
												</select>
												<i class="rtwwdpdl_description rtwwdpdl_text_pro"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Exclude Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
											</td>
											<td>
												<select disabled="disabled" name="category_exe_id[]" class="wc-enhanced-select form-field rtwwdpdl_prod_class" multiple placeholder="<?php esc_html_e('Search for a category','rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?>">
												<option value="0"><?php esc_html_e('0','rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?></option>
														
												</select>
												<i class="rtwwdpdl_description rtwwdpdl_text_pro"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<select disabled="disabled" class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles[]" multiple="multiple">
												<option selected value="all">
													<?php esc_html_e( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
											</select>
											<i class="rtwwdpdl_description rtwwdpdl_text_pro"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Select Required Product', 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai' ); ?>
											</label>
										</td>
										<td>
											<select class="wc-product-search rtwwdpdl_prod_class" name="rtwwdpdl_select_product[]" data-action="woocommerce_json_search_products_and_variations" multiple="multiple" placeholder="<?php esc_html_e('Search for a product','rtwwdpd-woo-dynamic-pricing-discounts-with-ai') ?>" >
											</select>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Select product for this offer.', 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai' ); ?>
											</i>
										</td>
									</tr>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Maximum Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<input type="number" value="" min="0" name="rtwwdpdl_max_discount">
											<i class="rtwwdpdl_description"><?php esc_html_e( 'This is used to set a threshold limit on the discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
											</i>
										</td>
									</tr>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Exclude sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<input type="checkbox" value="yes" name="rtwwdpdl_exclude_sale" >
											<i class="rtwwdpdl_description"><?php esc_html_e( 'This will exclude the discount from the products that are on sale.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
								</table>
								</div>
								<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_time_tab">
									<table class="rtwwdpdl_table_edit">
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Valid from', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</label>
											</td>
											<td>
												<input type="date" name="rtwwdpdl_from_date" placeholder="YYYY-MM-DD" required="required" value="" />
												<i class="rtwwdpdl_description"><?php esc_html_e( 'The date from which the rule would be applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Valid To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</label>
											</td>
											<td>
												<input type="date" name="rtwwdpdl_to_date" placeholder="YYYY-MM-DD" required="required" value=""/>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'The date till which the rule would be applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
												</i>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="rtwwdpdl_prod_single_save_n_cancel rtwwdpdl_btn_save_n_cancel">
					<input class="rtw-button rtwwdpdl_save_rule" type="submit" name="rtwwdpdl_cart_rule" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
					<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
				</div>
			</form>
		</div>
	</div>
<?php } ?>
<div class="rtwwdpdl_cart_table">
	<table class="rtwtable table table-striped table-bordered dt-responsive nowrap" data-value="cart_tbl" cellspacing="0">
		<thead>
			<tr>

				<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Check On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Excluded Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Excluded Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Required Products', 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai' ); ?></th>
				<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Exclude Sale Items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
			</tr>
		</thead>
		<?php
		$rtwwdpdl_absolute_url = esc_url( admin_url( 'admin.php' ).add_query_arg( $_GET,$wp->request ) );
		$rtwwdpdl_products_option = get_option( 'rtwwdpdl_cart_rule' );
		$rtwwdpdl_categories = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
		$products = array();

		if ( $rtwwdpdl_categories ) {
			foreach ( $rtwwdpdl_categories as $cat ) {
				$products[ $cat->term_id ] = $cat->name;
			}
		}
		if( is_array( $rtwwdpdl_products_option ) && !empty( $rtwwdpdl_products_option ) ) { ?>
			<tbody>
				<?php
				foreach ( $rtwwdpdl_products_option as $key => $value ) {
					echo '<tr data-val="'.$key.'">';

					echo '<td class="rtwrow_no">'.esc_html__( $key+1 , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					echo '<td class="rtw_drag"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"/></td>';

					echo '<td>'.( isset( $value['rtwwdpdl_cart_offer'] ) ? esc_html__( $value['rtwwdpdl_cart_offer'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

					if( $value['rtwwdpdl_check_for'] == 'rtwwdpdl_price' )
					{
						echo '<td>'.esc_html__('Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					}
					elseif( $value['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity' )
					{
						echo '<td>'.esc_html__('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					}
					else{
						echo '<td>'.esc_html__('Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					}

					echo '<td>'.( isset( $value['rtwwdpdl_min'] ) ? esc_html__( $value['rtwwdpdl_min'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

					echo '<td>'.( isset( $value['rtwwdpdl_max'] ) ? esc_html__( $value['rtwwdpdl_max'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

					if( $value['rtwwdpdl_discount_type'] == 'rtwwdpdl_discount_percentage' )
					{
						echo '<td>'.esc_html__('Percentage', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					}
					elseif( $value['rtwwdpdl_discount_type'] == 'rtwwdpdl_flat_discount_amount' )
					{
						echo '<td>'.esc_html__('Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					}
					else{
						echo '<td>'.esc_html__('Fixed Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					}

					echo '<td>'.( isset( $value['rtwwdpdl_discount_value'] ) ? esc_html__( $value['rtwwdpdl_discount_value'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '' ).'</td>';

					echo '<td></td>';

					echo '<td></td>';

					echo '<td>'.( isset( $value['rtwwdpdl_max_discount'] ) ? esc_html__($value['rtwwdpdl_max_discount'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';
					echo '<td>';

					if(isset($value['rtwwdpdl_select_product']) && is_array($value['rtwwdpdl_select_product']) && !empty($value['rtwwdpdl_select_product']))
					{
						foreach ($value['rtwwdpdl_select_product'] as $keys => $val)
						{
							echo esc_html__(get_the_title($val), 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<br>';
						}
					}
					else
					{
						echo esc_html__('Not Selected', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<br>';
					}
					echo "</td>";
					
					echo '<td>';
					
						esc_html_e( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
					
					echo '</td>';

					echo '<td>'.( isset( $value['rtwwdpdl_from_date'] ) ? esc_html__( $value['rtwwdpdl_from_date'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

					echo '<td>'.( isset( $value['rtwwdpdl_to_date'] ) ? esc_html__( $value['rtwwdpdl_to_date'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

					if( !isset( $value['rtwwdpdl_exclude_sale'] ) )
					{
						echo '<td>'.esc_html__('No', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					}
					else
					{
						echo '<td>'.esc_html__('Yes', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					}

					echo '<td><a href="'.esc_url( $rtwwdpdl_absolute_url .'&editcart='.$key ).'"><input type="button" class="rtw_edit_cart rtwwdpdl_edit_dt_row" value="'.esc_attr__('Edit', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'" />
					</a><a href="'.esc_url( $rtwwdpdl_absolute_url .'&delcart='.$key ).'"><input type="button" class="rtw_delete_row rtwwdpdl_delete_dt_row" value="'.esc_attr__('Delete', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'"/></a></td>';
					echo '</tr>';
				}
				?>	
			</tbody>
		<?php } ?>
		<tfoot>
			<tr>
				<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Product/ Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Check On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Excluded Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Excluded Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Required Products', 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai' ); ?></th>
				<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Exclude Sale Items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
			</tr>
		</tfoot>
	</table>
</div>