<div class="rtwwdpdl_right">
	<div class="rtwwdpdl_add_buttons">
		<input class="rtw-button rtwwdpdl_single_prod_rule" id="rtw_speci" type="button" name="rtwwdpdl_single_prod_rule" value="<?php esc_attr_e( 'Discount Rule for Plus Member (Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />

		<input class="rtw-button rtwwdpdl_combi_prod_rule" id="rtwwdpdl_plus_rule" type="button" name="rtwwdpdl_plus_mem_rule" value="<?php esc_attr_e( 'Plus Member Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		
	</div>
	<?php include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_tabs/rtwwdpdl_plus_member_rule.php' ); ?>
	<div class="rtwwdpdl_add_single_rule rtwwdpdl_form_layout_wrapper">
		<span class="rtwwdpdl_pro_text"><?php esc_html_e('This feature is available in Pro version','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
		<a target="_blank" href="<?php echo esc_url('https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts-with-ai/24165502'); ?>"><?php esc_html_e('Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
		<form action="" method="POST" accept-charset="utf-8">
			<?php wp_nonce_field( 'rtwwdpd_plus', 'rtwwdpd_plus_field' ); ?>
			<div id="woocommerce-product-data" class="postbox ">
				<div class="inside">
					<div class="panel-wrap product_data rtwwdpdl_pro_text_overlay">
						<ul class="product_data_tabs wc-tabs">
							<li class="rtwwdpdl_rule_tab active">
								<a class="rtwwdpdl_link" id="rtwproduct_rule_combi">
									<span><?php esc_html_e('Rule','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li class="rtwwdpdl_restriction_tab">
								<a class="rtwwdpdl_link" id="rtwproduct_restrict_combi">
									<span><?php esc_html_e('Restrictions','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li class="rtwwdpdl_time_tab">
								<a class="rtwwdpdl_link" id="rtwproduct_validity_combi">
									<span><?php esc_html_e('Validity','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
						</ul>

						<div class="panel woocommerce_options_panel">
							<div class="options_group rtwwdpdl_active" id="rtwwdpdl_rule_tab_combi">
								<input type="hidden" name="edit_plus" id="edit_plus" value="save">
								<table class='rtw_specific_tbl'>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<input type="text" name="rtwwdpdl_plus_name" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>">

											<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Sale of', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<select id="rtwwdpdl_rule_for_plus" name="rtwwdpdl_rule_for_plus">
												<option value="rtw_select">
													<?php esc_html_e( 'Select', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="rtwwdpdl_product">
													<?php esc_html_e( 'Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="rtwwdpdl_category">
													<?php esc_html_e( 'Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
											</select>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Offer should be applied on the selected products or category.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr class="rtw_if_prod">
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Choose Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<select id="rtwproducts" name="product_ids[]" class="wc-product-search" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" multiple="multiple"  >
	                					</select>
										</td>
						        	</tr>
						        	<tr class="rtw_if_cat">
						        		<td>
						        			<label class="rtwwdpdl_label"><?php esc_html_e('Choose Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
						        		</td>
										<td>
											<select name="category_ids[]" id="category_ids" class="wc-enhanced-select" multiple data-placeholder="<?php esc_attr_e('Select category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>">
                             				</select>
										</td>
						        	</tr>
						        	<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<select name="rtwwdpdl_dsnt_type">
												<option value="rtwwdpdl_dis_percent">
													<?php esc_html_e( 'Percent Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="rtwwdpdl_flat_dis_amt">
													<?php esc_html_e( 'Flat Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="rtwwdpdl_fxd_price">
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
											<input type="number" min="0" name="rtwwdpdl_dscnt_val">
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Discount should be given according to discount type.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Rule On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<select id="rtwwdpdl_rule_on_plus" name="rtwwdpdl_rule_on">
												<option value="rtw_amt">
													<?php esc_html_e( 'Minimum Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="rtw_quant">
													<?php esc_html_e( 'Minimum Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="rtw_both">
													<?php esc_html_e( 'Both', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
											</select>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Customer have to buy atlest this amount/quantity of products to get this offer.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr id="rtw_min_price">
						        		<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('On Minimum Purchase Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<input type="number" value="" min="1" name="rtwwdpdl_min_purchase_of">
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Customer have to buy atlest this amount of product to get this offer.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
						        	</tr>
						        	<tr id="rtw_min_quant">
						         	<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('On Minimum Purchase Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<input type="number" value="" min="1" name="rtwwdpdl_min_purchase_quant">
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Customer have to buy atlest this number of products to get this offer.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
						            </td>
						        	</tr>
								</table>
							</div>
							<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_restriction_tab_combi">
								<table class='rtw_specific_tbl'>
									<tr>
										<td>
					            		<label class="rtwwdpdl_label"><?php esc_html_e('Maximum Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					            		</label>
				            		</td>
				            		<td>
				            			<input type="number" value="" min="0" name="rtwwdpdl_max_discount">
											<i class="rtwwdpdl_description"><?php esc_html_e( 'This is used to set a threshold limit on the discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr>
										<td>
				            			<label class="rtwwdpdl_label"><?php esc_html_e('Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
				            			</label>
				            		</td>
				            		<td>
				            			<select class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles[]" multiple>
				            			</select>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Select user role for this offer.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr>
		            				<td>
				            			<label class="rtwwdpdl_label"><?php esc_html_e('Exclude sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
				            			</label>
				            		</td>
				            		<td>
				            			<input type="checkbox" value="yes" name="rtwwdpdl_combi_exclude_sale"/>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'This will exclude the discount from the products that are on sale.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
								</table>
							</div>
               		<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_time_tab_combi">
               			<table class='rtw_specific_tbl'>
	           					<tr>
	           						<td>
				           				<label class="rtwwdpdl_label"><?php esc_html_e('Valid from', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					            		</label>
					            	</td>
					            	<td>
				           				<input type="date" name="rtwwdpdl_from_date" placeholder="YYYY-MM-DD"/>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'The date from which the rule would be applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr>
										<td>
				           				<label class="rtwwdpdl_label"><?php esc_html_e('Valid To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					            		</label>
					            	</td>
					            	<td>
				           				<input type="date" name="rtwwdpdl_to_date" placeholder="YYYY-MM-DD" />
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
				<input class="rtw-button rtwwdpdl_save_rule" type="button" name="rtwwdpdl_plus_mem" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
				<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			</div>
		</form>
	</div>
</div>
<?php 
$rtwwdpdl_enable = '';
?>	
<div class="rtwwdpdl_enable_rule">
	<b><?php esc_html_e( 'Rule Permission : ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
	</b>
	<select name="rtw_enable_plus" class="rtw_enable_plus">
		<option value="select" <?php selected( $rtwwdpdl_enable, 'select'); ?>><?php esc_attr_e( 'Select', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></option>
		<option value="enable" <?php selected( $rtwwdpdl_enable, 'enable'); ?>><?php esc_attr_e( 'Enable', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></option>
		<option value="disable" <?php selected( $rtwwdpdl_enable, 'disable'); ?>><?php esc_attr_e( 'Disable', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></option>
	</select>
</div>
<div class="rtwwdpdl_prod_table">
	<table id="rtw_plus_tbl" class="rtwtable table table-striped table-bordered dt-responsive nowrap" cellspacing="0">
		<caption>
			<h3 class="rtw_plus_class"><?php esc_html_e('Note : These rules is only for the "Plus" or "Prime" members which you have set from the users page.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite')?></h3>
		</caption>
		<thead>
			<tr>
		    	<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Rule For', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Rule On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_attr_e( 'Exclude Sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		  	</tr>
		</thead>
		<tfoot>
			<tr>
		    	<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Rule For', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Rule On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_attr_e( 'Exclude Sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		  	</tr>
		</tfoot>
	</table>
</div>