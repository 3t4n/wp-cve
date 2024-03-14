<?php

global $wp;
if( isset( $_GET['delprod'] ) )
{
	$rtwwdpdl_products_option = get_option('rtwwdpdl_single_prod_rule');
	$rtwwdpdl_row_no = sanitize_text_field( $_GET['delprod'] );
	array_splice( $rtwwdpdl_products_option, $rtwwdpdl_row_no, 1 );
	update_option( 'rtwwdpdl_single_prod_rule', $rtwwdpdl_products_option);
	$rtwwdpdl_new_url = esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_prod_rules' );
	header('Location: '. $rtwwdpdl_new_url );
	die();
}
if( isset( $_POST['rtwwdpdl_save_rule'] ) ){
	if( isset( $_POST['rtwwdpd_product_field'] ) && wp_verify_nonce( $_POST['rtwwdpd_product_field'], 'rtwwdpd_product' ) ) 
	{
		$rtwwdpdl_prod = $_POST;
		$rtwwdpdl_option_no = sanitize_text_field( $rtwwdpdl_prod['edit_chk'] );
		$rtwwdpdl_products_option = get_option('rtwwdpdl_single_prod_rule');
		if($rtwwdpdl_products_option == '')
		{
			$rtwwdpdl_products_option = array();
		}
		$rtwwdpdl_products = array();
		$rtwwdpdl_products_array = array();

		foreach($rtwwdpdl_prod as $key => $val){
			$rtwwdpdl_products[$key] = $val;
		}
		if( $rtwwdpdl_option_no != 'save'){
			
			$rtw_edit_row = isset( $_REQUEST['editpid'] ) ? sanitize_text_field( $_REQUEST['editpid'] ) : '';
			unset( $rtw_edit_row );
			$rtwwdpdl_products_option[$rtwwdpdl_option_no] = $rtwwdpdl_products;
			
		}
		else{
			$rtwwdpdl_products_option[] = $rtwwdpdl_products;
		}
		update_option('rtwwdpdl_single_prod_rule',$rtwwdpdl_products_option);
		

		?>
		<div class="notice notice-success is-dismissible">
			<p><strong><?php esc_html_e('Rule saved.','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e('Dismiss this notices.','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
			</button>
		</div><?php
	}else {
		print 'Sorry, your are not allowed to access this page.';
   		exit;
	}

}
if( isset( $_GET['editpid'] ) )
{	
	$rtwwdpdl_url = esc_url( admin_url('admin.php').add_query_arg( $_GET, $wp->request ) );
	
	$rtwwdpdl_products_option = get_option( 'rtwwdpdl_single_prod_rule' );

	$rtwwdpdl_prev_prod = $rtwwdpdl_products_option[ sanitize_text_field( $_GET['editpid'] )];
	$key = 'editpid';
	$filteredURL = preg_replace('~(\?|&)'.$key.'=[^&]*~', '$1', $rtwwdpdl_url);
	$rtwwdpdl_new_url = esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_prod_rules');
?>
<div class="rtwwdpdl_right">
	<div class="rtwwdpdl_add_buttons">
		<input class="rtw-button rtwwdpdl_single_prod_rule" id="add_single_product" type="button" name="rtwwdpdl_single_prod_rule" value="<?php esc_attr_e( 'Add Single Product Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		<input class="rtw-button rtwwdpdl_combi_prod_rule" id="add_combi_product" type="button" name="rtwwdpdl_combi_prod_rule" value="<?php esc_attr_e( 'Add Combi Product Rule (Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
	</div>
	<div class="rtwwdpdl_add_main_wrapper rtwwdpdl_form_layout_wrapper">
		<div class="rtwwdpdl_add_single_rule rtwwdpdl_active">
			<form action="<?php echo esc_url( $rtwwdpdl_new_url ); ?>" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
				<?php wp_nonce_field( 'rtwwdpd_product', 'rtwwdpd_product_field' ); ?>
				<div id="woocommerce-product-data" class="postbox ">
					<div class="inside">
						<div class="panel-wrap product_data">
							<ul class="product_data_tabs wc-tabs">
								<li class="rtwwdpdl_prod_rule_tab rtwwdpdl_active">
									<a class="rtwwdpdl_link rtwwdpdl_active" id="rtwproduct_rule">
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
									<input type="hidden" id="edit_chk_single" value="<?php echo esc_attr( sanitize_text_field( $_GET['editpid'] ) );?>" name="edit_chk">
									<table class="rtwwdpdl_table_edit">
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<input type="text" name="rtwwdpdl_offer_name" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" required="required" value="<?php echo isset($rtwwdpdl_prev_prod['rtwwdpdl_offer_name']) ? esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_offer_name']) : ''; ?>">

												<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('To be Applied on', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>

												<select  id="rtwwdpdl_rule_on" class="rtwwdpdl_rule_select" name="rtwwdpdl_rule_on">
													<option value="rtwwdpdl_products" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_rule_on'] , 'rtwwdpdl_products' ); ?>>
														<?php esc_html_e( 'Selected Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>	
													</option>
													<option <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_rule_on'] , 'rtwwdpd_multiple_products' ); ?>value="rtwwdpd_multiple_products">
														<?php esc_html_e( 'Multiple Products', 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai'); ?>	
													</option>
													<option value="rtwwdpdl_cart" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_rule_on'] , 'rtwwdpdl_cart' ); ?>>
														<?php esc_html_e( 'All Products in Cart', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
													</option>
												</select>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Select option on which rule is applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr id="product_id" class="rtwwdpdl_products">
				            				<td>
						            			<label class="rtwwdpdl_label"><?php esc_html_e('Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
						            		</td>
						            		<td>
						            			<select class="wc-product-search rtwwdpdl_prod_class" name="product_id" data-action="woocommerce_json_search_products_and_variations" placeholder="<?php esc_html_e( 'Search for a product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) ?>" >
						            				<?php 
						            				if( isset( $rtwwdpdl_prev_prod['product_id'] ) )
						            				{
						            					$product = wc_get_product( $rtwwdpdl_prev_prod['product_id'] );
														if ( is_object( $product )) {
															echo '<option value="' . esc_attr( $rtwwdpdl_prev_prod['product_id'] ) . '"' . selected(true, true, false) . '>' . wp_kses_post( $product->get_formatted_name()) . '</option>';
														}
						            				}
						            				?>
					            				</select>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Product on which rule is applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr class="multiple_product_ids">
				            				<td>
						            			<label class="rtwwdpd_label"><?php esc_html_e('Select Products', 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai' ); ?></label>
						            		</td>
						            		<td>
						            			<select class="wc-product-search rtwwdpd_prod_class" multiple="multiple" name="multiple_product_ids[]" data-action="woocommerce_json_search_products_and_variations" placeholder="<?php esc_html_e('Search for a product','rtwwdpd-woo-dynamic-pricing-discounts-with-ai') ?>" >
												<?php
												if(isset($rtwwdpdl_prev_prod['multiple_product_ids']))
												{
													$products = $rtwwdpdl_prev_prod['multiple_product_ids'];
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
												<i class="rtwwdpd_description"><?php esc_html_e( 'Products on which rule is applied.', 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Check for', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<select name="rtwwdpdl_check_for" id="rtwwdpdl_check_for">
													<option value="rtwwdpdl_quantity" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_check_for'], 'rtwwdpdl_quantity'); ?>><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
													<option value="rtwwdpdl_price" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_check_for'], 'rtwwdpdl_price'); ?>><?php esc_html_e( 'Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
													<option value="rtwwdpdl_weight" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_check_for'], 'rtwwdpdl_weight'); ?>><?php esc_html_e( 'Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
												</select>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Rule can be applied for either on Price/ Quantity/ Weight.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<?php $rtwwdpdl_check_var = 'Quantity';?>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Minimum ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<input type="number" value="<?php echo isset( $rtwwdpdl_prev_prod['rtwwdpdl_min']) ? esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_min']) : '' ; ?>" min="0" name="rtwwdpdl_min">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum value to check', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Maximum ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<input type="number" value="<?php echo isset( $rtwwdpdl_prev_prod['rtwwdpdl_max']) ? esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_max']) : '' ; ?>" min="0" name="rtwwdpdl_max">
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
													<option value="rtwwdpdl_discount_percentage" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_discount_type'], 'rtwwdpdl_discount_percentage'); ?>>
														<?php esc_html_e( 'Percent Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
													<option value="rtwwdpdl_flat_discount_amount" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_discount_type'], 'rtwwdpdl_flat_discount_amount'); ?>>
														<?php esc_html_e( 'Flat Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
													<option value="rtwwdpdl_fixed_price" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_discount_type'], 'rtwwdpdl_fixed_price'); ?>>
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
						            			<label class="rtwwdpdl_label"><?php esc_html_e('Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
						            			</label>
						            		</td>
						            		<td>
						            			<select  class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles[]" multiple>
													<option selected value="all">
														<?php esc_html_e( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
						            			</select>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Minimum orders done', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					            				</label>
											</td>
											<td>
												<input disabled="disabled" type="number" value="" min="0" name="rtwwdpdl_min_orders">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Minimum amount spend', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					            				</label>
											</td>
											<td>
												<input disabled="disabled" type="number" value="" min="0" name="rtwwdpdl_min_spend">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
				            				<td>
						            			<label class="rtwwdpdl_label"><?php esc_html_e('Exclude sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
						            			</label>
						            		</td>
						            		<td>
				            					<input type="checkbox" value="yes" name="rtwwdpdl_exclude_sale" <?php checked(isset($rtwwdpdl_prev_prod['rtwwdpdl_exclude_sale']) ? $rtwwdpdl_prev_prod['rtwwdpdl_exclude_sale'] : '', 'yes'); ?>/>
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
				           					<input type="date" name="rtwwdpdl_single_from_date" placeholder="YYYY-MM-DD" required="required" value="<?php echo esc_attr( $rtwwdpdl_prev_prod['rtwwdpdl_single_from_date']); ?>" />
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
				           					<input type="date" name="rtwwdpdl_single_to_date" placeholder="YYYY-MM-DD" required="required" value="<?php echo esc_attr( $rtwwdpdl_prev_prod['rtwwdpdl_single_to_date']); ?>"/>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'The date till which the rule would be applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
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
				<input class="rtw-button rtwwdpdl_save_rule" type="submit" name="rtwwdpdl_save_rule" value="<?php esc_attr_e( 'Update Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
				<input class="rtw-button rtwwdpdl_cancel_rule" type="submit" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			</div>
		</form>
	</div>
	<?php include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_prod_combi.php' ); ?>
	</div>
</div>
<?php }else {
?>
<div class="rtwwdpdl_right">
	<div class="rtwwdpdl_add_buttons">
		<input class="rtw-button rtwwdpdl_single_prod_rule" id="add_single_product" type="button" name="rtwwdpdl_single_prod_rule" value="<?php esc_attr_e( 'Add Single Product Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		<input class="rtw-button rtwwdpdl_combi_prod_rule" id="add_combi_product" type="button" name="rtwwdpdl_combi_prod_rule" value="<?php esc_attr_e( 'Add Combi Product Rule (Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
	</div>
	<div class="rtwwdpdl_add_main_wrapper rtwwdpdl_form_layout_wrapper">
		<div class="rtwwdpdl_add_single_rule">
			<form action="" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
				<?php wp_nonce_field( 'rtwwdpd_product', 'rtwwdpd_product_field' ); ?>
				<div id="woocommerce-product-data" class="postbox ">
					<div class="inside">
						<div class="panel-wrap product_data">
							<ul class="product_data_tabs wc-tabs">
								<li class="rtwwdpdl_prod_rule_tab active">
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
									<input type="hidden" id="edit_chk_single" value="save" name="edit_chk">
									<table class="rtwwdpdl_table_edit">
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<input type="text" name="rtwwdpdl_offer_name" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" required="required" value="">

												<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('To be Applied on', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<select  id="rtwwdpdl_rule_on" name="rtwwdpdl_rule_on">
													<option value="rtwwdpdl_products">
														<?php esc_html_e( 'Selected Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>	
													</option>
													<option value="rtwwdpd_multiple_products">
														<?php esc_html_e( 'On Multiple Products', 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai'); ?>	
													</option>
													<option value="rtwwdpdl_cart">
														<?php esc_html_e( 'All Products in Cart', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
													</option>
												</select>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Select option on which rule is applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr id="product_id">
				            				<td>
						            			<label class="rtwwdpdl_label"><?php esc_html_e('Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
						            		</td>
						            		<td>
						            			<select class="wc-product-search rtwwdpdl_prod_class" name="product_id" data-action="woocommerce_json_search_products_and_variations" placeholder="<?php esc_html_e( 'Search for a product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) ?>" >
						            			
					            				</select>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Product on which rule is applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr class="multiple_product_ids">
				            				<td>
						            			<label class="rtwwdpd_label"><?php esc_html_e('Select Products', 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai' ); ?></label>
						            		</td>
						            		<td>
						            			<select id="rtwwdpd_checking_placeholder" class="wc-product-search rtwwdpd_prod_class" multiple="multiple" name="multiple_product_ids[]" data-action="woocommerce_json_search_products_and_variations" placeholder="<?php esc_html_e('Search for a product','rtwwdpd-woo-dynamic-pricing-discounts-with-ai') ?>" >
					            				</select>
												<i class="rtwwdpd_description"><?php esc_html_e( 'Products on which rule is applied.', 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai' ); ?>
												</i>
											</td>
										</tr>
										<tr >
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Check for', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<select name="rtwwdpdl_check_for" id="rtwwdpdl_check_for">
													<option value="rtwwdpdl_quantity"><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
													<option value="rtwwdpdl_price"><?php esc_html_e( 'Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
													<option value="rtwwdpdl_weight"><?php esc_html_e( 'Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
												</select>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Rule can be applied for either on Price/ Quantity/ Weight.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<?php $rtwwdpdl_check_var = 'Quantity';?>
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
													<option value="rtwwdpdl_discount_percentage">
														<?php esc_html_e( 'Percent Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
													<option value="rtwwdpdl_flat_discount_amount" >
														<?php esc_html_e( 'Flat Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
													<option value="rtwwdpdl_fixed_price">
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
									
									<table class="rtwwdpdl_table_edit">
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
						            			<label class="rtwwdpdl_label"><?php esc_html_e('Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
						            			</label>
						            		</td>
						            		<td>
						            			<select  class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles[]" multiple>
													<option selected value="all">
														<?php esc_html_e( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
						            			</select>
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Minimum orders done', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					            				</label>
											</td>
											<td>
												<input disabled="disabled" type="number" value="" min="0" name="rtwwdpdl_min_orders">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Minimum amount spend', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					            				</label>
											</td>
											<td>
												<input disabled="disabled" type="number" value="" min="0" name="rtwwdpdl_min_spend">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
										<tr>
				            				<td>
						            			<label class="rtwwdpdl_label"><?php esc_html_e('Exclude sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
						            			</label>
						            		</td>
						            		<td>
				            					<input type="checkbox" value="yes" name="rtwwdpdl_exclude_sale">
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
						           				<input type="date" name="rtwwdpdl_single_from_date" placeholder="YYYY-MM-DD" required="required" value="" />
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
						           				<input type="date" name="rtwwdpdl_single_to_date" placeholder="YYYY-MM-DD" required="required" value=""/>
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
					<input class="rtw-button rtwwdpdl_save_rule" type="submit" name="rtwwdpdl_save_rule" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
					<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
				</div>
			</form>
		</div>
		<?php include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_prod_combi.php' ); ?>
	</div>
</div>
<?php }
if(isset($_GET['editid']))
{
	echo '<div class="rtwwdpdl_prod_table_edit">';
}
else{
	echo '<div class="rtwwdpdl_prod_table">';
}
?>
	<table class="rtwtable table table-striped table-bordered dt-responsive nowrap" data-value="prodct" cellspacing="0">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Rule On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Check On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Exclude Sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
			</tr>
		</thead>
		<?php
		$rtwwdpdl_products_option = get_option('rtwwdpdl_single_prod_rule');
		$rtwwdpdl_absolute_url = esc_url( admin_url('admin.php').add_query_arg( $_GET,$wp->request));

		if( is_array( $rtwwdpdl_products_option ) && !empty( $rtwwdpdl_products_option ) ) { ?>
			<tbody>
				<?php
				foreach ($rtwwdpdl_products_option as $key => $value) {
				
					
					echo '<tr data-val="'.$key.'">';
					
					echo '<td class="rtwrow_no">'.($key+1).'</td>';
					echo '<td class="rtw_drag"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"/></td>';
					echo '<td>'.(isset($value['rtwwdpdl_offer_name']) ? esc_html__($value['rtwwdpdl_offer_name'], 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';
					if($value['rtwwdpdl_rule_on'] == 'rtwwdpdl_cart')
					{
						echo '<td>'.esc_html__('All Products in Cart', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite').'</td>';
						echo '<td>-----</td>';
					}
					elseif($value['rtwwdpdl_rule_on'] == 'rtwwdpdl_products')
					{
						echo '<td>'.esc_html__('Selected Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite').'</td>';
						if(isset($value['product_id']) && $value['product_id'] != '')
						{
							echo '<td>'.get_the_title( $value["product_id"] ).'</td>';
						}
						else{
							echo '<td>-----</td>';
						}
					}
					elseif($value['rtwwdpdl_rule_on'] == 'rtwwdpd_multiple_products')
					{
						
						echo '<td>'.esc_html__('Multiple Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite').'</td>';

						if(isset($value['multiple_product_ids']) && is_array($value['multiple_product_ids']) && !empty($value['multiple_product_ids']))
						{
							echo '<td>';
							foreach ( $value['multiple_product_ids'] as $pp => $iid) {
								echo get_the_title( $iid ). ', ';
							}
							echo '</td>';
						}
						else{
							echo '<td>-----</td>';
						}
					}
				

					if($value['rtwwdpdl_check_for'] == 'rtwwdpdl_price')
					{
						echo '<td>'.esc_html__("Price", "rtwwdpdl-woo-dynamic-pricing-discounts-lite").'</td>';
					}
					elseif($value['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity')
					{
						echo '<td>Quantity</td>';
					}
					else{
						echo '<td>Weight</td>';
					}
					
					echo '<td>'.(isset($value['rtwwdpdl_min']) ? esc_html__($value['rtwwdpdl_min'], "rtwwdpdl-woo-dynamic-pricing-discounts-lite") : '').'</td>';
					
					echo '<td>'.(isset($value['rtwwdpdl_max']) ? esc_html__($value['rtwwdpdl_max'], "rtwwdpdl-woo-dynamic-pricing-discounts-lite") : '').'</td>';

					if($value['rtwwdpdl_discount_type'] == 'rtwwdpdl_discount_percentage')
					{
						echo '<td>'.esc_html__('Percentage', "rtwwdpdl-woo-dynamic-pricing-discounts-lite").'</td>';
					}
					elseif($value['rtwwdpdl_discount_type'] == 'rtwwdpdl_flat_discount_amount')
					{
						echo '<td>'.esc_html__('Amount', "rtwwdpdl-woo-dynamic-pricing-discounts-lite").'</td>';
					}
					else{
						echo '<td>'.esc_html__('Fixed Price', "rtwwdpdl-woo-dynamic-pricing-discounts-lite").'</td>';
					}
					
					echo '<td>'.(isset($value['rtwwdpdl_discount_value']) ? esc_html__($value['rtwwdpdl_discount_value'] , "rtwwdpdl-woo-dynamic-pricing-discounts-lite") : '').'</td>';
					
					echo '<td>'.(isset($value['rtwwdpdl_max_discount']) ? esc_html__($value['rtwwdpdl_max_discount'], "rtwwdpdl-woo-dynamic-pricing-discounts-lite" ) : '').'</td>';
					
					echo '<td>';
					
					esc_html_e( 'All', "rtwwdpdl-woo-dynamic-pricing-discounts-lite");
					echo '</td>';
					
					echo '<td>'.(isset($value['rtwwdpdl_single_from_date']) ? esc_html__($value['rtwwdpdl_single_from_date'], "rtwwdpdl-woo-dynamic-pricing-discounts-lite" ) : '' ).'</td>';
					
					echo '<td>'.(isset($value['rtwwdpdl_single_to_date']) ? esc_html__($value['rtwwdpdl_single_to_date'], "rtwwdpdl-woo-dynamic-pricing-discounts-lite") : '').'</td>';

					echo '<td>0</td>';

					echo '<td>0</td>';

					if(!isset($value['rtwwdpdl_exclude_sale']))
					{
						echo '<td>'.esc_html__('No' , "rtwwdpdl-woo-dynamic-pricing-discounts-lite").'</td>';
					}
					else
					{
						echo '<td>Yes</td>';
					}
					echo '<td><a href="'.esc_url( $rtwwdpdl_absolute_url .'&editpid='.$key ).'"><input type="button" class="rtw_single_prod_edit rtwwdpdl_edit_dt_row" value="'.esc_attr__('Edit' , "rtwwdpdl-woo-dynamic-pricing-discounts-lite").'" /></a>
					<a href="'.esc_url( $rtwwdpdl_absolute_url .'&delprod='.$key ).'"><input type="button" class="rtw_delete_row rtwwdpdl_delete_dt_row" value="'.esc_attr__('Delete' , "rtwwdpdl-woo-dynamic-pricing-discounts-lite").'"/></a></td>';
					echo '</tr>';
				}
				?>		
			</tbody>
		<?php } ?>
		<tfoot>
			<tr>
				
				<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Rule On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Check On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Exclude Sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
			</tr>
		</tfoot>
	</table>
</div>