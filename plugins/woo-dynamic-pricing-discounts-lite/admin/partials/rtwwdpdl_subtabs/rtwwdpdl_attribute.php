<div class="rtwwdpdl_right">
	<span class="rtwwdpdl_pro_text"><?php esc_html_e('This feature is available in Pro version','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
	<a target="_blank" href="<?php echo esc_url('https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts-with-ai/24165502'); ?>"><?php esc_html_e('Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
	<div class="rtwwdpdl_add_buttons">
		<input class="rtw-button rtwwdpdl_single_prod_rule" id="add_single_product" type="button" name="rtwwdpdl_single_prod_rule" value="<?php esc_attr_e( 'Add New Rule (Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
	</div> 
	<div class="rtwwdpdl_add_single_rule rtwwdpdl_form_layout_wrapper">
		<form method="post" action="" enctype="multipart/form-data">
			<?php wp_nonce_field( 'rtwwdpd_attr', 'rtwwdpd_attr_field' ); ?>
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
								<input type="hidden" id="edit_att" name="edit_att" value="save">
								<table class="rtwwdpdl_table_edit">
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<input disabled="disabled" type="text" name="rtwwdpdl_att_offer_name" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" required="required">
											<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Select Attribute Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<select disabled="disabled" id="rtwwdpdl_attributes" name="rtwwdpdl_attributes" class="rtwwdpdl_payment_method">
												<option value="0">
												<?php esc_html_e( 'Select', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
											</select>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Select attribute type on which discount should be given.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr id="rtwwdpdl_attribute_val_col">
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Select Color', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<select disabled="disabled" multiple="multiple" class="rtwwdpdl_payment_method" name="rtwwdpdl_attribute_val_col[]">
												<option value="0">
													<?php
													esc_html_e( 'col', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
													?>
												</option>
											</select>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Select attribute name on which discount should be given.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr id="rtwwdpdl_attribute_val_size">
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Select Size', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<select disabled="disabled" multiple="multiple" class="rtwwdpdl_payment_method" name="rtwwdpdl_attribute_val_size[]">
											<option value="0">
												<?php
												esc_html_e( 'siz', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
												?>
											</option>
											</select>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Select attribute name on which discount should be given.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<select disabled="disabled" name="rtwwdpdl_att_discount_type">
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
											<input disabled="disabled" type="number" value="" required="required" min="0" name="rtwwdpdl_att_discount_value">
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
											<label class="rtwwdpdl_label"><?php esc_html_e('Exclude Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
										</td>
										<td>
											<select disabled="disabled" class="wc-product-search rtwwdpdl_prod_class" multiple="multiple" name="product_exe_id[]" data-action="woocommerce_json_search_products_and_variations" placeholder="<?php esc_html_e('Search for a product','rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?>" >
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
											<input disabled="disabled" type="number" value="" required="required" min="0" name="rtwwdpdl_att_max_discount">
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
											<select disabled="disabled" class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles[]" multiple="multiple">
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
											<label class="rtwwdpdl_label"><?php esc_html_e('Exclude sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<input disabled="disabled" type="checkbox" value="yes" name="rtwwdpdl_att_exclude_sale"/>
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
											<input disabled="disabled" type="date" name="rtwwdpdl_att_from_date" placeholder="YYYY-MM-DD" required="required" />
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
											<input disabled="disabled" type="date" name="rtwwdpdl_att_to_date" placeholder="YYYY-MM-DD" required="required" />
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
				<input class="rtw-button rtwwdpdl_save_combi_rule" type="button" name="rtwwdpdl_save_pay_rule" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
				<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			</div>
		</form>
	</div>
	<div class="rtwwdpdl_prod_table">
		<table class="rtwtable table table-striped table-bordered dt-responsive nowrap" data-value="attr_tbl" cellspacing="0">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
					<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
					<th><?php esc_html_e( 'Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
					<th><?php esc_html_e( 'On Attribue', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
					<th><?php esc_html_e( 'Attribue Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
					<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
					<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
					<th><?php esc_html_e( 'Exclude Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
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
					<th><?php esc_html_e( 'Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
					<th><?php esc_html_e( 'On Attribue', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
					<th><?php esc_html_e( 'Attribue Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
					<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
					<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
					<th><?php esc_html_e( 'Exclude Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
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
</div>
