<div class="rtwwdpdl_right">
	<span class="rtwwdpdl_pro_text"><?php esc_html_e('This feature is available in Pro version','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
	<a target="_blank" href="<?php echo esc_url('https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts-with-ai/24165502'); ?>"><?php esc_html_e('Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
	<div class="rtwwdpdl_add_buttons">
		<h1 class="rtwcenter"><b><?php esc_attr_e('Create Upcoming Sale','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></b></h1>
	</div>
	<div class="rtwwdpdl_form_layout_wrapper">
		<form method="post" action="" enctype="multipart/form-data">
			<?php wp_nonce_field( 'rtwwdpd_coming', 'rtwwdpd_coming_field' ); ?>
			<div id="woocommerce-product-data" class="postbox ">
				<div class="inside">
					<div class="panel-wrap product_data rtwwdpdl_pro_text_overlay">
						<ul class="product_data_tabs wc-tabs">
							<li class="rtwwdpdl_rule_tab_combi active">
								<a class="rtwwdpdl_link" id="rtwproduct_rule_combi">
									<span><?php esc_html_e('Rule','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li class="rtwwdpdl_restriction_tab_combi" id="rtwproduct_restrict_combi">
								<a class="rtwwdpdl_link">
									<span><?php esc_html_e('Restrictions','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li class="rtwwdpdl_time_tab_combi" id="rtwproduct_validity_combi">
								<a class="rtwwdpdl_link">
									<span><?php esc_html_e('Validity','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
						</ul>

						<div class="panel woocommerce_options_panel rtwwdpdl_woocommerce_pannel_option" >
							<div class="options_group rtwwdpdl_active" id="rtwwdpdl_rule_tab_combi">
								<input type="hidden" id="editsale" name="editsale" value="save">
								<table class='rtw_specific_tbl'>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<input type="text" name="rtwwdpdl_sale_name" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" value="">

											<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Sale of', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<select id="rtwwdpdl_sale_of" name="rtwwdpdl_sale_of">
												<option value="rtwwdpdl_select">
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
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Check for', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<select name="rtwwdpdl_sale_check_for" id="rtwwdpdl_sale_check_for">
												<option value="rtwwdpdl_quantity"><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
												<option value="rtwwdpdl_price"><?php esc_html_e( 'Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
												<option value="rtwwdpdl_weight"><?php esc_html_e( 'Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
											</select>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Rule can be applied for either on Price/ Quantity/ Weight.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
								</table>
								<div>
									<h3 class="rtw_tbltitle"><?php esc_attr_e('To be Applied on', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></h3>
									<table id="rtw_for_product">
										<thead>
											<tr>
												<th class="rtwtable_header"><?php esc_attr_e('Row no', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
												<th class="rtwtable_header"><?php esc_attr_e('Product Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
												<th class="rtwtable_header">
													<div class="rtw_sale_quant"><?php esc_attr_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></div></th>
													<th class="rtwtable_header"><?php esc_html_e('Remove Item', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
												</tr>
											</thead>

											<tbody id="rtw_product_body">
												<tr>
													<td id="td_row_no">1</td>
													<td id="td_product_name">
														<select id="rtwproduct" name="product_id[]" class="wc-product-search rtwwdpdl_prod_tbl_class" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" >
														</select>
													</td>
													<td id="td_quant">
														<input type="number" min="0" name="quant_pro[]" value=""  />
													</td>
													<td id="td_remove">
														<?php esc_html_e('Min One product.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
													</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td colspan=3>
														<a  class="button insert" name="rtwnsertbtn" id="rtwinsert_product" ><?php esc_html_e('Add Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
									<div>
										<table id="rtw_for_category">
											<caption><b><?php esc_attr_e('To be Applied on', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></b></caption>
											<thead>
												<tr>
													<th class="rtwtable_header rtwten"><?php esc_attr_e('Row no', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
													<th class="rtwtable_header rtwforty"><?php esc_attr_e('Categorie', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
													<th class="rtwtable_header rtwtwenty"><div class="rtw_sale_quant"><?php esc_attr_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></div></th>
													<th class="rtwtable_header rtwthirty"><?php esc_attr_e('Remove Item', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
												</tr>
											</thead>
											<tbody id="rtw_category_body">
												<tr id="rtw_tbltr">
													<td id="td_row_no">1</td>
													<td class="td_product_name">
														<select name="category_id[]" id="category_id" class="wc-enhanced-select rtwwdpdl_prod_tbl_class" multiple data-placeholder="<?php esc_attr_e('Select category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>">
														</select>
													</td>
													<td class="td_quant">
														<input type="number" min="0" class="rtwtd_quant"name="quant_cat[]" value=""  />
													</td>
													<td id="td_remove">
														<?php esc_html_e('Minimum One Category Required.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
													</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td colspan=3>
														<a  class="button insert" name="rtwnsertbtn" id="rtwinsert_category" ><?php esc_html_e('Add Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
									<table class="rtwwdpdl_table_edit">
					            		<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<select name="rtwwdpdl_sale_discount_type">
													<option value="rtwwdpdl_discount_percentage">
														<?php esc_html_e( 'Percent Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
													</option>
													<option value="rtwwdpdl_flat_discount_amount">
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
												<input type="number" value="" min="0" name="rtwwdpdl_sale_discount_value">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Discount should be given according to discount type.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
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
					            			<input type="number" value="" min="0" name="rtwwdpdl_sale_max_discount">
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
												<option value="all">
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
												<input type="number" value="" min="0" name="rtwwdpdl_sale_min_orders">
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
												<input type="number" value="" min="0" name="rtwwdpdl_sale_min_spend">
												<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum amount need to be spent by a customer on previous orders to be eligible for this discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
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
					           				<input type="date" name="rtwwdpdl_sale_from_date" placeholder="YYYY-MM-DD" value="" />
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
					           				<input type="date" name="rtwwdpdl_sale_to_date" placeholder="YYYY-MM-DD" value=""/>
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
			</div>
		<div class="rtwwdpdl_btn_save_n_cancel">
			<input class="rtw-button" type="button" name="rtwwdpdl_cmng_sale" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		</div>
	</form>
</div>
<div class="">
	<table class="rtwtable table table-striped table-bordered dt-responsive nowrap" cellspacing="0">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Sale Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Applied On.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Products/Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Sale Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Applied On.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Products/Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
			</tr>
		</tfoot>
	</table>
</div>
