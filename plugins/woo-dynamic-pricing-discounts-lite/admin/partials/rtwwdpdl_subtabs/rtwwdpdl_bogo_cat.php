<div class="rtwwdpdl_bogo_combi_tab rtwwdpdl_inactive rtwwdpdl_form_layout_wrapper">
	<span class="rtwwdpdl_pro_text"><?php esc_html_e('This feature is available in Pro version','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
	<a target="_blank" href="<?php echo esc_url('https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts-with-ai/24165502'); ?>"><?php esc_html_e('Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
	<form method="post" action="" enctype="multipart/form-data">
		<?php wp_nonce_field( 'rtwwdpd_bogocat', 'rtwwdpd_bogocat_field' ); ?>
		<div id="woocommerce-product-data" class="postbox ">
			<div class="inside">
				<div class="panel-wrap product_data rtwwdpdl_pro_text_overlay">
					<ul class="product_data_tabs wc-tabs">
						<li class="rtwwdpdl_bogo_c_rule_tab active">
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
							<input type="hidden" id="edit_cat_bogo" name="edit_cat_bogo" value="save">
							<table class="rtwwdpdl_table_edit">
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<input disabled="disabled" type="text" name="rtwwdpdl_bogo_cat_name" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" required="required" value="">

										<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
							</table>
							<h3 class="rtw_tbltitle"><?php esc_html_e('Category Need to be Purchased', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></h3>
							<table id="rtwcat_table_bogo">
								<thead>
									<tr>
										<th class="rtwtable_header rtwten"><?php esc_html_e('Row no', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										<th class="rtwtable_header rtwforty"><?php esc_html_e('Categorie', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										<th class="rtwtable_header rtwtwenty"><?php esc_html_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										<th class="rtwtable_header rtwthirty"><?php esc_html_e('Remove Item', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
									</tr>
								</thead>
								<tbody id="product_list_body_bogo">
									<tr id="rtw_tbltr">
										<td id="td_row_no">1</td>
										<td class="td_product_name">
											<select disabled="disabled" name="category_id[]" id="category_id" class="wc-enhanced-select rtw_clsscategory" data-placeholder="<?php esc_attr_e('Select category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>">
											
				                            </select>
				                        </td>
				                        <td class="td_quant">
				                        	<input disabled="disabled" type="number" min="0" class="rtwtd_quant"name="combi_quant[]" value=""  />
				                        </td>
				                        <td id="td_remove">
				                        	<?php esc_html_e('Minimum One Category Required.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
				                        </td>
				                    </tr>
				                </tbody>
				                <tfoot>
				                	<tr>
				                 		<td colspan=3>
				                 			<a disabled="disabled" class="button insert" name="rtwnsertbtn" id="rtwinsert_cat_bogo" ><?php esc_html_e('Add Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
				                 		</td>
				                 	</tr>
				                 </tfoot>
				             </table>
				             <h3 class="rtw_tbltitle"><?php esc_html_e('Free Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></h3>
				             <table id="rtwbogo_table_cat_pro">
				             	<thead>
				             		<tr>
				             			<th class="rtwtable_header"><?php esc_html_e('Row no', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
				             			<th class="rtwtable_header"><?php esc_html_e('Product Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
				             			<th class="rtwtable_header"><?php esc_html_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
				             			<th class="rtwtable_header"><?php esc_html_e('Remove Item', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
				             		</tr>
				             	</thead>
				             	<tbody id="rtw_bogo_cat_pro">
				             		<tr>
			             			<td id="td_row_no">1</td>
			             			<td id="td_product_name">
			             				<select disabled="disabled" id="rtwproductfree" name="rtwbogo[]" class="wc-product-search rtwwdpdl_prod_tbl_class" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" >
			             					
			             				</select>
			             			</td>
			             			<td id="td_quant">
			             				<input disabled="disabled" type="number" min="0" name="bogo_quant_free[]" value=""  />
			             			</td>
			             			<td id="td_remove">
			             				<?php esc_html_e('Min One product.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
			             			</td>
			             		</tr>
			             	</tbody>
			             	<tfoot>
			             		<tr>
			             			<td colspan=3>
			             				<a disabled="disabled" class="button insert" name="rtwnsertbtn" id="rtwinsert_bogo_cat_p" ><?php esc_html_e('Add Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
			             			</td>
			             		</tr>
			             	</tfoot>
			             </table>
			         </div>

			         <div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_restriction_tab_combi">
			         	<table class="rtwwdpdl_table_edit">
			         		<tr>
			         			<td>
			         				<label class="rtwwdpdl_label"><?php esc_html_e('Exclude Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></label>
			         			</td>
			         			<td>
			         				<select disabled="disabled" class="wc-product-search rtwwdpdl_prod_tbl_class" multiple="multiple" name="product_exe_id[]" data-action="woocommerce_json_search_products_and_variations" placeholder="<?php esc_html_e('Search for a product','rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?>" >
			         				</select>
			         				<i class="rtwwdpdl_description"><?php esc_html_e( 'Exclude products form this rule.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
				         			</i>
				         		</td>
				         	</tr>
				         	<tr>
				         		<td>
				         			<label class="rtwwdpdl_label"><?php esc_html_e('Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					         		</label>
					         	</td>
					         	<td>
					         		<select disabled="disabled" class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles_com[]" multiple="multiple">
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
							     	<input disabled="disabled" type="number" value="" min="0" name="rtwwdpdl_bogo_min_orders">
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
									<input disabled="disabled" type="number" value="" min="0" name="rtwwdpdl_bogo_min_spend">
									<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum amount need to be spent by a customer on previous orders to be eligible for this discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
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
										<input disabled="disabled" type="date" name="rtwwdpdl_bogo_from_date" placeholder="YYYY-MM-DD" required="required" value="" />
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
										<input disabled="disabled" type="date" name="rtwwdpdl_bogo_to_date" placeholder="YYYY-MM-DD" required="required" value=""/>
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
			<input class="rtw-button rtwwdpdl_save_combi_rule" type="button" name="rtwwdpdl_save_catbogo_rule" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		</div>
	</form>
</div>
<div class="rtwwdpdl_bogo_c_table">
	<table class="rtwtables table table-striped table-bordered dt-responsive nowrap" data-value="bogo_cat_tbl" cellspacing="0">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Category Purchased', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Purchased Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Free Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Free Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Excluded Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Repeat', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Category Purchased', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Purchased Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Free Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Free Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Excluded Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Repeat', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
			</tr>
		</tfoot>
	</table>
</div>