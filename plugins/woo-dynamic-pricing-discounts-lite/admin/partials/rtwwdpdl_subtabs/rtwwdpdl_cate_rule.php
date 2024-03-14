<?php
global $wp;
if( isset( $_GET['delcate'] ) )
{
	$rtwwdpdl_products_option = get_option( 'rtwwdpdl_single_cat_rule' );
	$rtwwdpdl_row_no = sanitize_text_field( $_GET['delcate'] );
	array_splice( $rtwwdpdl_products_option, $rtwwdpdl_row_no, 1 );
	update_option( 'rtwwdpdl_single_cat_rule',$rtwwdpdl_products_option) ;
	$rtwwdpdl_new_url = esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_cat_rules' );
	header( 'Location: '.$rtwwdpdl_new_url );
	die();
}

if(isset($_POST['rtwwdpdl_save_cat'])){
	if( isset( $_POST['rtwwdpd_cat_field'] ) && wp_verify_nonce( $_POST['rtwwdpd_cat_field'], 'rtwwdpd_cat' ) ) 
	{
		$rtwwdpdl_cat = $_POST; 
		$rtwwdpdl_option = sanitize_text_field( $_POST['rtw_save_single_cat'] );
		$rtwwdpdl_cat_option = get_option('rtwwdpdl_single_cat_rule');

		if( $rtwwdpdl_cat_option == '' )
		{
			$rtwwdpdl_cat_option = array();
		}
		$rtwwdpdl_products = array();
		$rtwwdpdl_products_array = array();

		foreach($rtwwdpdl_cat as $key => $val){
			$rtwwdpdl_products[$key] = $val;
		}
		if($rtwwdpdl_option != 'save')
		{
			$rtw_edit_row = isset( $_REQUEST['editcat'] ) ? sanitize_text_field( $_REQUEST['editcat'] ) : '';
			unset( $rtw_edit_row );
			$rtwwdpdl_cat_option[$rtwwdpdl_option] = $rtwwdpdl_products;
		}
		else{
			$rtwwdpdl_cat_option[] = $rtwwdpdl_products;
		}
		update_option( 'rtwwdpdl_single_cat_rule', $rtwwdpdl_cat_option );

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
if(isset($_GET['editcat']))
{
	$rtwwdpdl_cats = get_option( 'rtwwdpdl_single_cat_rule' );
	$rtwwdpdl_url = esc_url( admin_url('admin.php').add_query_arg( $_GET,$wp->request ) );
	
	$rtwwdpdl_prev_prod = $rtwwdpdl_cats[ sanitize_text_field( $_GET['editcat'] ) ];
	$edit = 'editcat';
	$filteredURL = preg_replace('~(\?|&)'.$edit.'=[^&]*~', '$1', $rtwwdpdl_url);
	$rtwwdpdl_new_url = esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_cat_rules');
	
	?>
<div class="rtwwdpdl_right">
<div class="rtwwdpdl_add_buttons">
	<input class="rtw-button rtwwdpdl_single_cat" type="button" name="rtwwdpdl_single_prod_rule" value="<?php esc_attr_e( 'Add Single Category Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
	<input class="rtw-button rtwwdpdl_combi_cat" type="button" name="rtwwdpdl_combi_prod_rule" value="<?php esc_attr_e( 'Add Combi Category Rule (Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
</div>

<div class="rtwwdpdl_single_cat_rule rtwwdpdl_active rtwwdpdl_form_layout_wrapper">
	<form method="post" action="<?php echo esc_url($rtwwdpdl_new_url); ?>" enctype="multipart/form-data">
		<?php wp_nonce_field( 'rtwwdpd_cat', 'rtwwdpd_cat_field' ); ?>
		<div id="woocommerce-product-data" class="postbox ">
			<div class="inside">
				<div class="panel-wrap product_data">
					<ul class="product_data_tabs wc-tabs">
						<li class="rtwwdpdl_single_cat_rule_tab active">
							<a class="rtwwdpdl_link" id="rtwcat_rule">
								<span><?php esc_html_e('Rule','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
							</a>
						</li>
						<li class="rtwwdpdl_restriction_tab">
							<a class="rtwwdpdl_link" id="rtwcat_restrict">
								<span><?php esc_html_e('Restrictions','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
							</a>
						</li>
						<li class="rtwwdpdl_time_tab">
							<a class="rtwwdpdl_link" id="rtwcat_validity">
								<span><?php esc_html_e('Validity','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
							</a>
						</li>
					</ul>

					<div class="panel woocommerce_options_panel">
						<div class="options_group rtwwdpdl_active" id="rtwcat_rule_tab">
							<input type="hidden" id="rtw_save_single_cat" name="rtw_save_single_cat" value="<?php echo esc_attr( sanitize_text_field( $_GET['editcat'] )); ?>">
							<table class="rtwwdpdl_table_edit">
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<input type="text" name="rtwwdpdl_offer_cat_name" placeholder="<?php esc_html_e( 'Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" required="required" value="<?php echo isset( $rtwwdpdl_prev_prod['rtwwdpdl_offer_cat_name'] ) ? esc_attr( $rtwwdpdl_prev_prod['rtwwdpdl_offer_cat_name'] ) : ''; ?>">

										<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Product category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<?php
											$rtwwdpdl_categories = get_terms('product_cat', 'orderby=name&hide_empty=0');
											$cats = array();

											if ( is_array( $rtwwdpdl_categories ) && !empty( $rtwwdpdl_categories ) ) {
											   foreach ( $rtwwdpdl_categories as $cat ) {
											        $cats[ $cat->term_id ] = $cat->name;
											   }
											}
										?>
										<select name="category_id" id="category_id" class="wc-enhanced-select rtw_clsscategory rtwwdpdl_prod_class" data-placeholder="<?php esc_attr_e('Select category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>">
										<?php 
										if( is_array( $cats ) && !empty( $cats ) )
										{
				            				foreach ($cats as $key => $value) {
					            				if(isset($rtwwdpdl_prev_prod['category_id']))
					            				{ 			            					
					            					if($key == $rtwwdpdl_prev_prod['category_id'])
				            						{
			            								echo '<option value="' . esc_attr($key) . '"' . selected($key, $rtwwdpdl_prev_prod['category_id']) . '>' . esc_html($cats[$key]) . '</option>'; 
			            							}
			            							else{
			            								echo '<option value="' . esc_attr($key) . '">' . esc_html($cats[$key]) . '</option>';
			            							}
				            					}
				            					else{
		            								echo '<option value="' . esc_attr($key) . '">' . esc_html($cats[$key]) . '</option>';
		            							}
				            				}
				            			}
			            				?>
			            			</select>
			            			<i class="rtwwdpdl_description"><?php esc_html_e( 'Select category on which rule is applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
			            			</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Check for', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<select name="rtwwdpdl_check_for_cat" id="rtwwdpdl_check_for_cat">
											<option value="rtwwdpdl_quantity" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_check_for_cat'], 'rtwwdpdl_quantity '); ?>><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
											<option value="rtwwdpdl_price" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_check_for_cat'], 'rtwwdpdl_price' ); ?>><?php esc_html_e( 'Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
											<option value="rtwwdpdl_weight" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_check_for_cat'], 'rtwwdpdl_weight' ); ?>><?php esc_html_e( 'Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
										</select>
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Rule can be applied for either on Price/ Quantity/ Weight.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Minimum ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<input type="number" value="<?php echo isset($rtwwdpdl_prev_prod['rtwwdpdl_min_cat']) ? esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_min_cat']) : '' ; ?>" min="0" name="rtwwdpdl_min_cat">
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum value to check', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Maximum ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<input type="number" value="<?php echo isset( $rtwwdpdl_prev_prod['rtwwdpdl_max_cat'] ) ? esc_attr( $rtwwdpdl_prev_prod['rtwwdpdl_max_cat'] ) : '' ; ?>" min="0" name="rtwwdpdl_max_cat">
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Maximum value to check', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<select name="rtwwdpdl_dscnt_cat_type">
											<option value="rtwwdpdl_discount_percentage" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_dscnt_cat_type'], 'rtwwdpdl_discount_percentage' ); ?>>
												<?php esc_html_e( 'Percent Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</option>
											<option value="rtwwdpdl_flat_discount_amount" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_dscnt_cat_type'], 'rtwwdpdl_flat_discount_amount' ); ?>>
												<?php esc_html_e( 'Flat Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</option>
											<option value="rtwwdpdl_fixed_price" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_dscnt_cat_type'], 'rtwwdpdl_fixed_price' ); ?>>
												<?php esc_html_e( 'Fixed Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</option>
										</select>
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Choose discount type.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e( 'Discount Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
									</td>
									<td>
										<input type="number" value="<?php echo isset( $rtwwdpdl_prev_prod['rtwwdpdl_dscnt_cat_val'] ) ? $rtwwdpdl_prev_prod['rtwwdpdl_dscnt_cat_val'] : ''; ?>" required="required" min="0" step="0.1" name="rtwwdpdl_dscnt_cat_val">
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Discount should be given according to discount type.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
							</table>
						</div>
						<div class="options_group rtwwdpdl_inactive" id="rtwcat_restriction_tab">
							<table class="rtwwdpdl_table_edit">
								<tr>
		            				<td>
				            			<label class="rtwwdpdl_label"><?php esc_html_e('Exclude Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
				            		</td>
				            		<td>
				            			<select class="wc-product-search rtwwdpdl_prod_class" multiple="multiple" name="product_exe_id[]" data-action="woocommerce_json_search_products_and_variations" placeholder="<?php esc_html_e('Search for a product','rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?>" >
				            				<?php 
				            				if( isset( $rtwwdpdl_prev_prod['product_exe_id'] ) && is_array( $rtwwdpdl_prev_prod['product_exe_id'] ) )
				            				{
					            				foreach ($rtwwdpdl_prev_prod['product_exe_id'] as $key => $value) {
					            					$product = wc_get_product($value);
														if (is_object($product)) {
															echo '<option value="' . esc_attr($value) . '"' . selected(true, true, false) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
														}
					            				}
				            				}
				            				?>
				            			</select>
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Exclude products form this rule.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
					            		<label class="rtwwdpdl_label"><?php esc_html_e('Maximum Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					            		</label>
				            		</td>
				            		<td>
			            				<input type="number" value="<?php echo isset($rtwwdpdl_prev_prod['rtwwdpdl_max_discount']) ? esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_max_discount']) : '' ; ?>" min="0" name="rtwwdpdl_max_discount" required>
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
			            			<select disabled="disabled" class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles[]" multiple="multiple">
			            				<option selected value="all">
											<?php esc_html_e( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</option>
			            			</select>
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Select user role for this offer.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Minimum orders done', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
			            			</label>
									</td>
									<td>
										<input disabled="disabled" type="number" value="0" min="0" name="rtwwdpdl_min_orders">
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum number of orders done by a customer to be eligible for this discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Minimum amount spend', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
			            			</label>
									</td>
									<td>
										<input disabled="disabled" type="number" value="0" min="0" name="rtwwdpdl_min_spend">
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum amount need to be spent by a customer on previous orders to be eligible for this discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
		            				<td>
				            			<label class="rtwwdpdl_label"><?php esc_html_e('Exclude sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
				            			</label>
				            		</td>
				            		<td>
				            			<input type="checkbox" value="yes" name="rtwwdpdl_exclude_sale" <?php checked( isset( $rtwwdpdl_prev_prod['rtwwdpdl_exclude_sale'] ) ? $rtwwdpdl_prev_prod['rtwwdpdl_exclude_sale'] : '', 'yes' ); ?>/>
										<i class="rtwwdpdl_description"><?php esc_html_e( 'This will exclude the discount from the products that are on sale.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
							</table>
						</div>
						<div class="options_group rtwwdpdl_inactive" id="rtwcat_time_tab">
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
		<div class="rtwwdpdl_cat_single_save_n_cancel rtwwdpdl_btn_save_n_cancel">
			<input class="rtw-button rtwwdpdl_save_cat" type="submit" name="rtwwdpdl_save_cat" value="<?php esc_attr_e( 'Update Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			<input class="rtw-button rtwwdpdl_cancel_rule" type="submit" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		</div>
	</form>
</div>
<?php include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_cat_combi.php' ); 
	}
else{

?>
<div class="rtwwdpdl_right">
	<div class="rtwwdpdl_add_buttons">
		<input class="rtw-button rtwwdpdl_single_cat" type="button" name="rtwwdpdl_single_prod_rule" value="<?php esc_attr_e( 'Add Single Category Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		<input class="rtw-button rtwwdpdl_combi_cat" type="button" name="rtwwdpdl_combi_prod_rule" value="<?php esc_attr_e( 'Add Combi Category Rule (Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
	</div>

	<div class="rtwwdpdl_single_cat_rule rtwwdpdl_form_layout_wrapper">
		<form method="post" action="" enctype="multipart/form-data">
			<?php wp_nonce_field( 'rtwwdpd_cat', 'rtwwdpd_cat_field' ); ?>
			<div id="woocommerce-product-data" class="postbox ">
				<div class="inside">
					<div class="panel-wrap product_data">
						<ul class="product_data_tabs wc-tabs">
							<li class="rtwwdpdl_single_cat_rule_tab active">
								<a class="rtwwdpdl_link" id="rtwcat_rule">
									<span><?php esc_html_e('Rule','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li class="rtwwdpdl_restriction_tab">
								<a class="rtwwdpdl_link" id="rtwcat_restrict">
									<span><?php esc_html_e('Restrictions','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li class="rtwwdpdl_time_tab">
								<a class="rtwwdpdl_link" id="rtwcat_validity">
									<span><?php esc_html_e('Validity','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
						</ul>

						<div class="panel woocommerce_options_panel">
							<div class="options_group rtwwdpdl_active" id="rtwcat_rule_tab">
								<input type="hidden" id="rtw_save_single_cat" name="rtw_save_single_cat" value="save">
							
								<table class="rtwwdpdl_table_edit">
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<input type="text" name="rtwwdpdl_offer_cat_name" placeholder="<?php esc_html_e( 'Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" required="required" value="">

										<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Product category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<?php
											$rtwwdpdl_categories = get_terms('product_cat', 'orderby=name&hide_empty=0');
											$cats = array();

											if ( is_array( $rtwwdpdl_categories ) && !empty( $rtwwdpdl_categories ) ) {
											   foreach ( $rtwwdpdl_categories as $cat ) {
											        $cats[ $cat->term_id ] = $cat->name;
											   }
											}
										?>
										<select name="category_id" id="category_id" class="wc-enhanced-select rtw_clsscategory rtwwdpdl_prod_class" data-placeholder="<?php esc_attr_e('Select category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>">
										<?php 
											if(is_array($cats) && !empty($cats))
											{
												foreach ($cats as $catid => $cat) {
													echo '<option value="' . esc_attr($catid) . '">' . esc_html($cat) . '</option>';
												}
											}
			            				?>
			            			</select>
			            			<i class="rtwwdpdl_description"><?php esc_html_e( 'Select category on which rule is applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
			            			</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Check for', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<select name="rtwwdpdl_check_for_cat" id="rtwwdpdl_check_for_cat">
											<option value="rtwwdpdl_quantity" ><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
											<option value="rtwwdpdl_price" ><?php esc_html_e( 'Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
											<option value="rtwwdpdl_weight" ><?php esc_html_e( 'Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
										</select>
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Rule can be applied for either on Price/ Quantity/ Weight.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Minimum ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<input type="number" value="" min="0" name="rtwwdpdl_min_cat">
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum value to check', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Maximum ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<input type="number" value="" min="0" name="rtwwdpdl_max_cat">
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Maximum value to check', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<select name="rtwwdpdl_dscnt_cat_type">
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
										<label class="rtwwdpdl_label"><?php esc_html_e( 'Discount Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
									</td>
									<td>
										<input type="number" value="" required="required" min="0" step="0.1" name="rtwwdpdl_dscnt_cat_val">
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Discount should be given according to discount type.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
							</table>
							</div>
							<div class="options_group rtwwdpdl_inactive" id="rtwcat_restriction_tab">

								<table class="rtwwdpdl_table_edit">
								<tr>
		            				<td>
				            			<label class="rtwwdpdl_label"><?php esc_html_e('Exclude Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
				            		</td>
				            		<td>
				            			<select class="wc-product-search rtwwdpdl_prod_class" multiple="multiple" name="product_exe_id[]" data-action="woocommerce_json_search_products_and_variations" placeholder="<?php esc_html_e('Search for a product','rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?>" >
				            				
				            			</select>
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Exclude products form this rule.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
					            		<label class="rtwwdpdl_label"><?php esc_html_e('Maximum Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					            		</label>
				            		</td>
				            		<td>
			            				<input type="number" value="" min="0" name="rtwwdpdl_max_discount" required>
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
			            			<select disabled="disabled" class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles[]" multiple="multiple">
			            				<option selected value="all">
											<?php esc_html_e( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</option>
			            			</select>
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Select user role for this offer.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Minimum orders done', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
			            			</label>
									</td>
									<td>
										<input disabled="disabled" type="number" value="0" min="0" name="rtwwdpdl_min_orders">
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum number of orders done by a customer to be eligible for this discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Minimum amount spend', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
			            			</label>
									</td>
									<td>
										<input disabled="disabled" type="number" value="0" min="0" name="rtwwdpdl_min_spend">
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum amount need to be spent by a customer on previous orders to be eligible for this discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
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
							<div class="options_group rtwwdpdl_inactive" id="rtwcat_time_tab">
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
			<div class="rtwwdpdl_cat_single_save_n_cancel rtwwdpdl_btn_save_n_cancel">
				<input class="rtw-button rtwwdpdl_save_cat" type="submit" name="rtwwdpdl_save_cat" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
				<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			</div>
		</form>
	</div>
<?php include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_cat_combi.php' ); 
} ?>
</div>
<div class="rtwwdpdl_table rtwwdpdl_cat_table">
	<table class="rtwtable table table-striped table-bordered dt-responsive nowrap" data-value="categor" cellspacing="0">
		<thead>
			<tr>
				<th><?php esc_attr_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Check On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Min', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Excluded Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Min Order Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Min Order Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Exclude Sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
			</tr>
		</thead>
		<?php
		$rtwwdpdl_products_option = get_option( 'rtwwdpdl_single_cat_rule' );
		$rtwwdpdl_absolute_url = esc_url( admin_url('admin.php').add_query_arg( $_GET, $wp->request ) );

		if( is_array( $rtwwdpdl_products_option ) &&  !empty( $rtwwdpdl_products_option ) ) { ?>
			<tbody>
			<?php
			$cat = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
			$products = array();
			if( is_array( $cat ) && !empty( $cat ) )
			{
				foreach ( $cat as $value ) {
					$products[ $value->term_id ] = $value->name;
				}
			}
			
			foreach ($rtwwdpdl_products_option as $key => $value) {
				echo '<tr data-val="'.$key.'">';

				echo '<td class="rtwrow_no">'.esc_html__( $key+1 , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
				echo '<td class="rtw_drag"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"/></td>';
				echo '</td>';
				
				echo '<td>'.( isset( $value['rtwwdpdl_offer_cat_name'] ) ? esc_html__($value['rtwwdpdl_offer_cat_name'], 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';
				
				echo '<td>';
				if(isset( $value['category_id'] ) )
				{
					echo esc_html__( $products[ $value['category_id'] ], 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
				}
				echo '</td>';

				if( $value['rtwwdpdl_check_for_cat'] == 'rtwwdpdl_price' )
				{
					echo '<td>'.esc_html__('Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				elseif( $value['rtwwdpdl_check_for_cat'] == 'rtwwdpdl_quantity' )
				{
					echo '<td>'.esc_html__('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				else{
					echo '<td>'.esc_html__('Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				
				echo '<td>'.( isset( $value['rtwwdpdl_min_cat'] ) ? esc_html__( $value['rtwwdpdl_min_cat'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';
				
				echo '<td>'.( isset( $value['rtwwdpdl_max_cat'] ) ? esc_html__( $value['rtwwdpdl_max_cat'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

				if( $value['rtwwdpdl_dscnt_cat_type'] == 'rtwwdpdl_discount_percentage')
				{
					echo '<td>'.esc_html__('Percentage', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				elseif($value['rtwwdpdl_dscnt_cat_type'] == 'rtwwdpdl_flat_discount_amount')
				{
					echo '<td>'.esc_html__('Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				else{
					echo '<td>'.esc_html__('Fixed Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				
				echo '<td>'.(isset($value['rtwwdpdl_dscnt_cat_val']) ? esc_html__($value['rtwwdpdl_dscnt_cat_val'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';
				
				echo '<td>'.(isset($value['rtwwdpdl_max_discount']) ? esc_html__($value['rtwwdpdl_max_discount'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';
				
				echo '<td>';
				esc_html_e( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
				echo '</td>';

				echo '<td>';
				if(isset($value['product_exe_id']) && is_array($value['product_exe_id']) && !empty($value['product_exe_id']))
				{
					foreach ($value['product_exe_id'] as $val)
					{
						echo '<span id="'.esc_attr($val).'">';
						echo esc_html__(get_the_title( $val , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite')).'</span><br>';
					}
				}
				else{
					echo '';
				}
				echo '</td>';
				
				echo '<td>'.(isset($value['rtwwdpdl_from_date']) ? esc_html__($value['rtwwdpdl_from_date'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';
				
				echo '<td>'.(isset($value['rtwwdpdl_to_date']) ? esc_html__($value['rtwwdpdl_to_date'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

				echo '<td>0</td>';

				echo '<td>0</td>';

				if(!isset($value['rtwwdpdl_exclude_sale']))
				{
					echo '<td>'.esc_html__('No', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				else
				{
					echo '<td>'.esc_html__('Yes', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}

				echo '<td><a href="'.esc_url( $rtwwdpdl_absolute_url .'&editcat='.$key ).'"><input type="button" class="rtw_edit_cat rtwwdpdl_edit_dt_row" value="'.esc_attr__('Edit', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'" />
				</a>
				<a href="'.esc_url( $rtwwdpdl_absolute_url .'&delcate='.$key ).'"><input type="button" class="rtw_delete_row rtwwdpdl_delete_dt_row" value="'.esc_attr__('Delete', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'"/></a></td>';
				
				echo '</tr>';
			}
			?>		
			</tbody>
		<?php } ?>

		<tfoot>
			<tr>
				
				<th><?php esc_attr_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Check On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Min', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Excluded Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Min Order Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Min Order Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Exclude Sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_attr_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
			</tr>
		</tfoot>
	</table>
</div>