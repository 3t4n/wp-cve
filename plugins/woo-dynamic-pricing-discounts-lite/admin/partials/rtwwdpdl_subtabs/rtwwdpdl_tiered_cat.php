<div class="rtwwdpdl_add_tier_cat_rule_tab rtwwdpdl_inactive rtwwdpdl_form_layout_wrapper">
   <span class="rtwwdpdl_pro_text"><?php esc_html_e('This feature is available in Pro version','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
   <a target="_blank" href="<?php echo esc_url('https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts-with-ai/24165502'); ?>"><?php esc_html_e('Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
	<form method="post" action="" enctype="multipart/form-data">
      <?php wp_nonce_field( 'rtwwdpd_tiercat', 'rtwwdpd_tiercat_field' ); ?>
		<div id="woocommerce-product-data" class="postbox ">
			<div class="inside">
				<div class="panel-wrap product_data rtwwdpdl_pro_text_overlay">
					<ul class="product_data_tabs wc-tabs">
						<li class="rtwwdpdl_rule_tab_combi active">
							<a class="rtwwdpdl_link" id="rtwproduct_rule_combi">
								<span><?php esc_html_e('Rule','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
							</a>
						</li>
						<li class="rtwwdpdl_restriction_tab_combi">
							<a class="rtwwdpdl_link" id="rtwproduct_restrict_combi">
								<span><?php esc_html_e('Restrictions','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
							</a>
						</li>
						<li class="rtwwdpdl_time_tab_combi">
							<a class="rtwwdpdl_link" id="rtwproduct_validity_combi">
								<span><?php esc_html_e('Validity','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
							</a>
						</li>
					</ul>

					<div class="panel woocommerce_options_panel">
						<div class="options_group rtwwdpdl_active" id="rtwwdpdl_rule_tab_combi">
							<input type="hidden" id="edit_t_cat" name="edit_t_cat" value="save">
                     <table class="rtwwdpdl_table_edit">
                        <tr>
                           <td>
                              <label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
                           </td>
                           <td>
                              <input disabled="disabled" type="text" name="rtwwdpdl_offer_name" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" required="required" value="">

                              <i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
                              </i>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <label class="rtwwdpdl_label"><?php esc_html_e('Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
                           </td>
                           <td>
                              <select disabled="disabled" name="rtwwdpdl_discount_type">
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
                              <label class="rtwwdpdl_label"><?php esc_html_e('Check for', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
                           </td>
                           <td>
                              <select disabled="disabled" name="rtwwdpdl_check_for" id="rtwwdpdl_check_for">
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
								<h4 id="rtw_ab"><a><?php esc_html_e('Select Category on which this rule is apply : ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );?></a>
									<select disabled="disabled" name="category_id[]" id="rtwwdpdl_category_id" class="wc-enhanced-select rtwwdpdl_prod_class" multiple data-placeholder="<?php esc_attr_e('Select category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>">
										
                           </select>
                        </h4>
                     </div>
                     <table id="rtwtiered_tbl_cat">
                     	<thead>
                     		<tr>
                     			<th class="rtwtable_header"><?php esc_attr_e('Tiers', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
                     			<th class="rtwtable_header"><a class="rtwtiered_chk_for"><?php esc_html_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?> </a><?php esc_html_e('Min', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
                     			<th class="rtwtable_header"><a class="rtwtiered_chk_for"><?php esc_html_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?> </a><?php esc_html_e('Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
                     			<th class="rtwtable_header"><div id="rtw_header"><?php esc_html_e('Percent Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></div></th>
                              <th></th>
                     		</tr>
                     	</thead>
                     	<tbody id="product_cat_tier">
                     		<tr>
                     			<td id="td_product_name">
                     				<?php esc_html_e('Tier 1', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
                     			</td>
                     			<td id="td_quant">
                     				<input disabled="disabled" type="number" min="1" name="quant_min[]" value="1"  />
                     			</td>
                     			<td id="td_quant">
                     				<input disabled="disabled" type="number" class="quant_c_max max" min="1" name="quant_max[]" value="1"  />
                     			</td>
                     			<td>
                     				<input disabled="disabled" type="number" min="1" name="discount_val[]" value="1"  />
                     			</td>
                     			<td id="td_remove">
                     				<?php esc_html_e('Min One product.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
                     			</td>
                     		</tr>
                     	</tbody>
                     	<tfoot>
                     		<tr>
                     			<td>
                     				<a disabled="disabled" class="button insert" name="rtwnsertbtn" id="rtwadd_tiered_cat" ><?php esc_html_e('Add Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
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
                              <input disabled="disabled" type="number" value="" min="0" name="rtwwdpdl_max_discount">
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
                              <input disabled="disabled" type="number" value="" min="0" name="rtwwdpdl_min_orders">
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
                              <input disabled="disabled" type="number" value="" min="0" name="rtwwdpdl_min_spend">
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
                              <input disabled="disabled" type="checkbox" value="yes" name="rtwwdpdl_exclude_sale"/>
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
                              <input disabled="disabled" type="date" name="rtwwdpdl_frm_date_c" placeholder="YYYY-MM-DD" required="required" value="" />
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
                              <input disabled="disabled" type="date" name="rtwwdpdl_to_date_c" placeholder="YYYY-MM-DD" required="required" value=""/>
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
      	<input class="rtw-button rtwwdpdl_save_rule" type="button" name="rtwwdpdl_tiered_cat" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
      	<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
      </div>
   </form>
</div>
<div class="rtwwdpdl_tier_c_table">
   <table class="rtwtables table table-striped table-bordered dt-responsive nowrap" data-value="tier_cat_tbl" cellspacing="0">
   	<thead>
   		<tr>
   			<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Check For', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Min - Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Excluded Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Min Order Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Min Order Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Exclude Sale Items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   		</tr>
   	</thead>
   	<tfoot>
   		<tr>
   			<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Category', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Check For', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Min - Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Excluded Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Min Order Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Min Order Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Exclude Sale Items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   			<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
   		</tr>
   	</tfoot>
   </table>
</div>