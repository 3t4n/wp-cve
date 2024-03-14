<div class="rtwwdpdl_add_combi_rule_tab">
	<span class="rtwwdpdl_pro_text"><?php esc_html_e('This feature is available in Pro version','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
	<a target="_blank" href="<?php echo esc_url('https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts-with-ai/24165502'); ?>"><?php esc_html_e('Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
	<form method="post" action="" enctype="multipart/form-data">
		<?php wp_nonce_field( 'rtwwdpd_procom', 'rtwwdpd_procom_field' ); ?>
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
					<div class="panel woocommerce_options_panel">
						<div class="options_group rtwwdpdl_active" id="rtwwdpdl_rule_tab_combi">
							<input type="hidden" id="edit_chk_combi" name="edit_chk_comb" value="save">
							<table class="rtwwdpdl_table_edit">
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<input disabled="disabled" type="text" name="rtwwdpdl_combi_offer_name" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" required="required" value="">

										<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
							</table>
							<h3 class="rtw_tbltitle"><?php esc_attr_e('To be Applied on', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></h3>
							<table id="rtwproduct_table">
								<thead>
									<tr>
										<th class="rtwtable_header"><?php esc_attr_e('Row no', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										<th class="rtwtable_header"><?php esc_attr_e('Product Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										<th class="rtwtable_header"><?php esc_attr_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										<th class="rtwtable_header"><?php esc_attr_e('Remove Item', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
									</tr>
								</thead>
								<tbody id="product_list_body">
									<tr>
										<td id="td_row_no">1</td>
										<td id="td_product_name">
											<select disabled="disabled" id="rtwproduct" name="product_id[]" class="wc-product-search rtwwdpdl_prod_tbl_class" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" >
											</select>
										</td>
										<td id="td_quant">
											<input disabled="disabled" type="number" min="1"  name="combi_quant[]" value=""  />
										</td>
										<td id="td_remove">
											<?php esc_attr_e('Min One product.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
										</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="5">
											<a disabled="disabled" class="button insert" name="rtwnsertbtn" id="rtwinsertbtn" ><?php esc_html_e('Add Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
										</td>
									</tr>
								</tfoot>
							</table>
							<table class="rtwwdpdl_table_edit">
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<select disabled="disabled" name="rtwwdpdl_combi_discount_type">
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
										<input disabled="disabled" type="number" value="" required="required" min="0" name="rtwwdpdl_combi_discount_value">
										<i class="rtwwdpdl_description"><?php esc_html_e( 'Discount should be given according to discount type.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
							</table>
						</div>

						<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_restriction_tab_combi">
							<table class="rtwwdpdl_table_edit">
								<tr>
									<td>
					            		<label class="rtwwdpdl_label"><?php esc_html_e('Maximum Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					            		</label>
				            		</td>
				            		<td>
				            			<input disabled="disabled" type="number" value="" min="0" name="rtwwdpdl_combi_max_discount">
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
				            			<select disabled="disabled" class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles_com[]" multiple>
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
										<input disabled="disabled" type="number" value="" min="0" name="rtwwdpdl_combi_min_orders">
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
										<input disabled="disabled" type="number" value="" min="0" name="rtwwdpdl_combi_min_spend">
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
			            				<input disabled="disabled" type="checkbox" value="yes" name="rtwwdpdl_combi_exclude_sale"/>
										<i class="rtwwdpdl_description"><?php esc_html_e( 'This will exclude the discount from the products that are on sale.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
							</table>
						</div>
						<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_time_tab_combi">
							<table class="rtwwdpdl_table_edit">
	           					<tr>
	           						<td>
				           				<label class="rtwwdpdl_label"><?php esc_html_e('Valid from', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					            		</label>
					            	</td>
					            	<td>
			           					<input disabled="disabled" type="date" name="rtwwdpdl_combi_from_date" placeholder="YYYY-MM-DD" required="required" value="" />
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
			           					<input disabled="disabled" type="date" name="rtwwdpdl_combi_to_date" placeholder="YYYY-MM-DD" required="required" value=""/>
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
		<div class="rtwwdpdl_prod_combi_save_n_cancel rtwwdpdl_btn_save_n_cancel">
			<input class="rtw-button rtwwdpdl_save_combi_rule" type="button" name="rtwwdpdl_save_combi_rule" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		</div>
	</form>
</div>
<div class="rtwwdpdl_prod_c_table">
	<table class="rtwtables table table-striped table-bordered dt-responsive nowrap" data-value="prodct_com" cellspacing="0">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
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
		
		<tfoot>
			<tr>
				
				<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
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
