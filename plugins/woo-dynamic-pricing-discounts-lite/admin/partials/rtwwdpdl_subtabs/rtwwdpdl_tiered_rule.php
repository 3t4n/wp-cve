<?php
global $wp; 
if( isset( $_GET['deltier'] ) )
{
	$rtwwdpdl_products_option = get_option('rtwwdpdl_tiered_rule');
	$rtwwdpdl_row_no = sanitize_text_field( $_GET['deltier'] );
	array_splice( $rtwwdpdl_products_option, $rtwwdpdl_row_no, 1 );
	update_option('rtwwdpdl_tiered_rule',$rtwwdpdl_products_option);
	$rtwwdpdl_new_url = esc_url(admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_tiered_rules');
	header('Location: '.$rtwwdpdl_new_url);
	die();
}
if( isset( $_POST['rtwwdpdl_tiered_rule'] ) ){
	if( isset( $_POST['rtwwdpd_tier_field'] ) && wp_verify_nonce( $_POST['rtwwdpd_tier_field'], 'rtwwdpd_tier' ) ) 
	{
		$rtwwdpdl_prod = $_POST;
		$rtwwdpdl_option_no = sanitize_text_field( $rtwwdpdl_prod['edit_tiern'] );
		$rtwwdpdl_products_option = get_option( 'rtwwdpdl_tiered_rule' );
		if( $rtwwdpdl_products_option == '' )
		{
			$rtwwdpdl_products_option = array();
		}
		$rtwwdpdl_products = array();
		$rtwwdpdl_products_array = array();

		foreach($rtwwdpdl_prod as $key => $val){
			$rtwwdpdl_products[$key] = $val;
		}
		if($rtwwdpdl_option_no != 'save'){
			$rtw_eidt_row = isset( $_REQUEST['edit_tiern'] ) ? sanitize_text_field( $_REQUEST['edit_tiern'] ) : '';
			unset( $rtw_eidt_row );
			$rtwwdpdl_products_option[$rtwwdpdl_option_no] = $rtwwdpdl_products;
		}
		else{
			$rtwwdpdl_products_option[] = $rtwwdpdl_products;
		}
		update_option('rtwwdpdl_tiered_rule',$rtwwdpdl_products_option);

		?>
		<div class="notice notice-success is-dismissible">
			<p><strong><?php esc_html_e('Rule saved.','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e('Dismiss this notices.','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
			</button>
		</div><?php
	}
	else {
		esc_html_e( 'Sorry, your are not allowed to access this page.' , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
   		exit;
	}

}
?>
<div class="rtwwdpdl_right">
	<div class="rtwwdpdl_add_buttons">
		<input class="rtw-button rtwwdpdl_tier_pro_rule" type="button" name="rtwwdpdl_single_prod_rule" value="<?php esc_html_e( 'Add Tiered Rule for Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		<input class="rtw-button rtwwdpdl_tier_cat_rule" type="button" name="rtwwdpdl_combi_prod_rule" value="<?php esc_html_e( 'Add Tiered Rule for Category (Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
	</div>
	<?php 
if( isset( $_GET['edit_tier'] ) )
{	
	$rtwwdpdl_url = esc_url( admin_url('admin.php').add_query_arg( $_GET,$wp->request ));

	$rtwwdpdl_products_option = get_option('rtwwdpdl_tiered_rule');
	$rtwwdpdl_prev_prod = $rtwwdpdl_products_option[ sanitize_text_field( $_GET['edit_tier'] )];
	$key = 'edit_tier';
	$filteredURL = preg_replace('~(\?|&)'.$key.'=[^&]*~', '$1', $rtwwdpdl_url);
	$rtwwdpdl_new_url = esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_tiered_rules');
	?>
	<div class="rtwwdpdl_add_tier_pro_rule_tab rtwwdpdl_active rtwwdpdl_form_layout_wrapper">
		<form method="post" action="<?php echo esc_url($rtwwdpdl_new_url); ?>" enctype="multipart/form-data">
			<?php wp_nonce_field( 'rtwwdpd_tier', 'rtwwdpd_tier_field' ); ?>
			<div id="woocommerce-product-data" class="postbox ">
				<div class="inside">
					<div class="panel-wrap product_data">
						<ul class="product_data_tabs wc-tabs">
							<li class="rtwwdpdl_rule_tab active">
								<a class="rtwwdpdl_link" id="rtwtier_rule">
									<span><?php esc_html_e('Rule','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li class="rtwwdpdl_restriction_tab">
								<a class="rtwwdpdl_link" id="rtwtier_restrict">
									<span><?php esc_html_e('Restrictions','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li class="rtwwdpdl_time_tab">
								<a class="rtwwdpdl_link" id="rtwtier_validity">
									<span><?php esc_html_e('Validity','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
						</ul>

						<div class="panel woocommerce_options_panel">
							<div class="options_group rtwwdpdl_active" id="rtwwdpdl_tiered_rule_tab">
								<input type="hidden" id="edit_tiern" name="edit_tiern" value="<?php echo esc_attr( sanitize_text_field( $_GET['edit_tier'] ) ); ?>">
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
			                    		<label class="rtwwdpdl_label"><?php esc_html_e('Check for', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
	                              	</td>
	                              	<td>
	                                 	<select name="rtwwdpdl_check_for" id="rtwwdpdl_check_for">
		                                    <option value="rtwwdpdl_quantity" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_check_for'], 'rtwwdpdl_quantity'); ?>><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
		                                    <option value="rtwwdpdl_weight" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_check_for'], 'rtwwdpdl_weight'); ?>><?php esc_html_e( 'Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
		                                    <option value="rtwwdpdl_price" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_check_for'], 'rtwwdpdl_price'); ?>><?php esc_html_e( 'Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
		                                </select>
		                                <i class="rtwwdpdl_description"><?php esc_html_e( 'Rule can be applied for either on Price/ Quantity/ Weight.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
		                                </i>
		                            </td>
		                        </tr>                   
							</table>
							<div>
								<h4 id="rtw_a"><a><?php esc_html_e('Select Product on which rule is applied:', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?> </a>
									<select class="wc-product-search rtwwdpdl_prod_class" multiple="multiple" id="rtwtiered" name="products[]" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" data-action="woocommerce_json_search_products_and_variations">
										<?php
										$rtwwdpdl_product_ids = array();
										if( isset( $rtwwdpdl_prev_prod ) && array_key_exists( 'products', $rtwwdpdl_prev_prod ) ){
											$rtwwdpdl_product_ids = $rtwwdpdl_prev_prod['products'];
										}
										if( is_array( $rtwwdpdl_product_ids ) && !empty( $rtwwdpdl_product_ids ) ){

											foreach ( $rtwwdpdl_product_ids as $key => $product_id ) {
												$product = wc_get_product( $product_id );
												if ( is_object( $product ) ) {
													echo '<option value="' . esc_attr( $product_id ) . '"' . selected( $product_id, $product_id, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
												}
											}
										}
										?>
									</select>
								</h4>
							</div>
							<table id="rtwtiered_table">
								<thead>
									<tr>
										<th class="rtwtable_header"><?php esc_attr_e('Tiers', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										<th class="rtwtable_header"><a class="rtwtiered_chk_for"><?php esc_attr_e('Quantity ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?> </a><?php esc_attr_e('Min', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										<th class="rtwtable_header"><a class="rtwtiered_chk_for"><?php esc_attr_e('Quantity ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?> </a><?php esc_attr_e('Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										<th class="rtwtable_header"><div id="rtw_header"><?php esc_attr_e('Discount value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></div></th>
										<th></th>
									</tr>
								</thead>
								<tbody id="product_list_body">
									<?php 
									if( isset( $rtwwdpdl_prev_prod['quant_min'] ) && is_array( $rtwwdpdl_prev_prod['quant_min'] ) && !empty( $rtwwdpdl_prev_prod['quant_min'] ) )
									{
									foreach ( $rtwwdpdl_prev_prod['quant_min'] as $nos => $valss ) {
										?>
										<tr>
											<td id="td_product_name">
												Tier <?php echo esc_html($nos +1); ?>
											</td>
											<td id="td_quant">
												<input type="number" min="1" name="quant_min[]" value="<?php echo esc_attr($valss); ?>"  />
											</td>
											<td id="td_quant">
												<input type="number" class="quant_max max" min="1" name="quant_max[]" value="<?php echo esc_attr($rtwwdpdl_prev_prod['quant_max'][$nos]); ?>"  />
											</td>
											<td>
												<input type="number" step="0.1" min="0.1" name="discount_val[]" value="<?php echo esc_attr($rtwwdpdl_prev_prod['discount_val'][$nos]); ?>"  />
											</td>
											<?php if( $nos == 0 ){ ?>
											<td id="td_remove">
												<?php esc_html_e('Min One product.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</td>
										<?php }else{ ?>
											<td id="td_remove">
												<a class="button insert rtw_remov_tiered" name="deletebtn" ><?php esc_html_e('Remove', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
											</td>
										</tr>
									<?php } } }else {?>
										<tr>
										<td id="td_product_name">
											<?php esc_attr_e('Tier 1', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
										</td>
										<td id="td_quant_min">
											<input type="number" min="1" name="quant_min[]" value="1"  />
										</td>
										<td id="td_quant_max">
											<input type="number" class="quant_max max" min="1" name="quant_max[]" value="2"  />
										</td>
										<td>
											<input type="number" min="0" step="0.1" name="discount_val[]" value="1"  />
										</td>
										<td id="td_remove">
											<?php esc_attr_e('Min One product.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
										</td>
									</tr>
								<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan=3>
											<a  class="button insert" name="rtwnsertbtn" id="rtwadd_tiered" ><?php esc_html_e('Add Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>

						<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_tiered_restr_tab">
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
		                              	<label class="rtwwdpdl_label"><?php esc_html_e('Minimum orders done', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
		                              	</label>
		                           	</td>
		                           	<td>
		                              	<input disabled="disabled" type="number" value="0" min="0" name="rtwwdpdl_min_orders">
		                              	<i class="rtwwdpdl_description rtwwdpdl_text_pro"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
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
		                              	<i class="rtwwdpdl_description rtwwdpdl_text_pro"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
		                              	</i>
		                           	</td>
		                        </tr>
		                        <tr>
		                           	<td>
		                              	<label class="rtwwdpdl_label"><?php esc_html_e('Exclude sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
		                              	</label>
		                           	</td>
		                           	<td>
		                              	<input type="checkbox" value="yes" name="rtwwdpdl_exclude_sale" <?php checked( isset($rtwwdpdl_prev_prod['rtwwdpdl_exclude_sale'] ) ? $rtwwdpdl_prev_prod['rtwwdpdl_exclude_sale'] : 'no', 'yes'); ?>/>
		                              	<i class="rtwwdpdl_description rtwwdpdl_text_pro"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
		                              	</i>
		                           	</td>
		                        </tr>
		                    </table>
						</div>
						<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_tiered_time_tab">
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
			<input class="rtw-button rtwwdpdl_save_rule" type="submit" name="rtwwdpdl_tiered_rule" value="<?php esc_attr_e( 'Update Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			<input class="rtw-button rtwwdpdl_cancel_rule" type="submit" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		</div>
	</form>
</div>
<?php include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_tiered_cat.php' ); 
}else{	
?>
<div class="rtwwdpdl_add_tier_pro_rule_tab rtwwdpdl_inactive rtwwdpdl_form_layout_wrapper">
	<form method="post" action="" enctype="multipart/form-data">
		<?php wp_nonce_field( 'rtwwdpd_tier', 'rtwwdpd_tier_field' ); ?>
		<div id="woocommerce-product-data" class="postbox ">
			<div class="inside">
				<div class="panel-wrap product_data">
					<ul class="product_data_tabs wc-tabs">
						<li class="rtwwdpdl_rule_tab active">
							<a class="rtwwdpdl_link" id="rtwtier_rule">
								<span><?php esc_html_e('Rule','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
							</a>
						</li>
						<li class="rtwwdpdl_restriction_tab">
							<a class="rtwwdpdl_link" id="rtwtier_restrict">
								<span><?php esc_html_e('Restrictions','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
							</a>
						</li>
						<li class="rtwwdpdl_time_tab">
							<a class="rtwwdpdl_link" id="rtwtier_validity">
								<span><?php esc_html_e('Validity','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
							</a>
						</li>
					</ul>

					<div class="panel woocommerce_options_panel">
						<div class="options_group rtwwdpdl_active" id="rtwwdpdl_tiered_rule_tab">
							<input type="hidden" id="edit_tiern" name="edit_tiern" value="save">
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
										<label class="rtwwdpdl_label"><?php esc_html_e('Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<select name="rtwwdpdl_discount_type">
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
                           				<select name="rtwwdpdl_check_for" id="rtwwdpdl_check_for">
                           					<option value="rtwwdpdl_quantity"><?php esc_html_e( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
                           					<option value="rtwwdpdl_weight" ><?php esc_html_e( 'Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
                           					<option value="rtwwdpdl_price"><?php esc_html_e( 'Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
                           				</select>
                           				<i class="rtwwdpdl_description"><?php esc_html_e( 'Rule can be applied for either on Price/ Quantity/ Weight.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
	                           			</i>
	                           		</td>
	                           	</tr>                   
                           	</table>
                           	<div>
								<h4 id="rtw_a"><a><?php esc_attr_e('Select Product on which rule is applied : ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a> 
									<select class="wc-product-search rtwwdpdl_prod_class" multiple="multiple" id="rtwtiered" name="products[]" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" data-action="woocommerce_json_search_products_and_variations">
									</select>
								</h4>
							</div>
							<table id="rtwtiered_table">
								<thead>
									<tr>
										<th class="rtwtable_header"><?php esc_attr_e('Tiers', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										<th class="rtwtable_header"><a class="rtwtiered_chk_for"><?php esc_attr_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?> </a><?php esc_attr_e('Min', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										<th class="rtwtable_header"><a class="rtwtiered_chk_for"><?php esc_attr_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?> </a><?php esc_attr_e('Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										<th class="rtwtable_header"><div id="rtw_header"><?php esc_attr_e('Discount value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></div></th>
										<th></th>
									</tr>
								</thead>
								<tbody id="product_list_body">
									<tr>
										<td id="td_product_name">
											<?php esc_attr_e('Tier 1', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
										</td>
										<td id="td_quant_min">
											<input type="number" step="0.1" min="1" name="quant_min[]" value="1"  />
										</td>
										<td id="td_quant_max">
											<input type="number" class="quant_max max" min="1" name="quant_max[]" value="2"  />
										</td>
										<td>
											<input type="number" min="0" step="0.1" name="discount_val[]" value="1"  />
										</td>
										<td id="td_remove">
											<?php esc_attr_e('Min One product.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
										</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan=3>
											<a  class="button insert" name="rtwnsertbtn" id="rtwadd_tiered" ><?php esc_html_e('Add Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>

						<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_tiered_restr_tab">
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
	                              		<label class="rtwwdpdl_label"><?php esc_html_e('Minimum orders done', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
	                              		</label>
	                              	</td>
	                              	<td>
	                              		<input disabled="disabled" type="number" value="0" min="0" name="rtwwdpdl_min_orders">
	                              		<i class="rtwwdpdl_description rtwwdpdl_text_pro"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
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
		                          		<i class="rtwwdpdl_description rtwwdpdl_text_pro"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
		                          		</i>
		                          	</td>
		                      	</tr>
		                      	<tr>
		                      		<td>
		                      			<label class="rtwwdpdl_label"><?php esc_html_e('Exclude sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
                              			</label>
		                           	</td>
		                           	<td>
		                              	<input type="checkbox" value="yes" name="rtwwdpdl_exclude_sale"/>
		                              	<i class="rtwwdpdl_description"><?php esc_html_e( 'This will exclude the discount from the products that are on sale.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
		                              	</i>
                           			</td>
                        		</tr>
                     		</table>
						</div>

						<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_tiered_time_tab">
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
			<input class="rtw-button rtwwdpdl_save_rule" type="submit" name="rtwwdpdl_tiered_rule" value="<?php esc_attr_e('Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		</div>
	</form>
</div>
<?php include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_tiered_cat.php' );
} 
if(isset($_GET['edit_tier']))
{
	echo '<div class="rtwwdpdl_tier_pro_table rtwwdpdl_active">';
}
else{
	echo '<div class="rtwwdpdl_tier_pro_table">';
}
?>
	<table class="rtwtable table table-striped table-bordered dt-responsive nowrap" cellspacing="0">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Check For', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min - Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
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
		<?php
		$rtwwdpdl_products_option = get_option('rtwwdpdl_tiered_rule');
		$rtwwdpdl_absolute_url = esc_url( admin_url('admin.php').add_query_arg( $_GET,$wp->request));
		if( is_array( $rtwwdpdl_products_option ) && !empty( $rtwwdpdl_products_option )) { ?>
		<tbody>
			<?php
			foreach ($rtwwdpdl_products_option as $key => $value) {
				echo '<tr data-val="'.$key.'">';

				echo '<td>'.esc_html__( $key+1 , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				echo '<td class="rtw_drag"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"/></td>';


				echo '<td>'.(isset($value['rtwwdpdl_offer_name']) ? esc_html__($value['rtwwdpdl_offer_name'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

				echo '<td>';
				if(isset($value['products']) && is_array($value['products']) && !empty($value['products']))
				{
					foreach ($value['products'] as $val) {
						esc_html_e(get_the_title($val), 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
						echo '<br>';
					}
				}
				echo '</td>';

				if($value['rtwwdpdl_check_for'] == 'rtwwdpdl_price')
				{
					echo '<td>'.esc_html__('Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				elseif($value['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity')
				{
					echo '<td>'.esc_html__('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				else{
					echo '<td>'.esc_html__('Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				echo '<td>';
				if(isset($value['quant_min']) && is_array($value['quant_min']) && !empty($value['quant_min']))
				{
					foreach ($value['quant_min'] as $keys => $val) {
						echo esc_html($val).' - '. esc_html($value['quant_max'][$keys]).'<br>';
					}
				}
				echo '</td>';

				if($value['rtwwdpdl_discount_type'] == 'rtwwdpdl_discount_percentage')
				{
					echo '<td>'.esc_html__('Percentage', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				elseif($value['rtwwdpdl_discount_type'] == 'rtwwdpdl_flat_discount_amount')
				{
					echo '<td>'.esc_html__('Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				else{
					echo '<td>'.esc_html__('Fixed Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}

				echo '<td>';
				if(isset($value['discount_val']) && is_array($value['discount_val']) && !empty($value['discount_val']))
				{
					foreach ($value['discount_val'] as $val) {
						echo esc_html__($val, 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<br>';
					}
				}
				echo '</td>';

				echo '<td>'.(isset($value['rtwwdpdl_max_discount']) ? esc_html__($value['rtwwdpdl_max_discount'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

				echo '<td>';
				esc_html_e( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<br>';
				echo '</td>';

				echo '<td>'.(isset($value['rtwwdpdl_from_date']) ? esc_html__($value['rtwwdpdl_from_date'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

				echo '<td>'.(isset($value['rtwwdpdl_to_date']) ? esc_html__($value['rtwwdpdl_to_date'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

				echo '<td>0</td>';

				echo '<td>0</td>';

				if(!isset($value['rtwwdpdl_exclude_sale']))
				{
					echo '<td>'.esc_html__('No', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}else
				{
					echo '<td>'.esc_html__('Yes', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
				}
				echo '<td><a href="'.esc_url( $rtwwdpdl_absolute_url .'&edit_tier='.$key ).'"><input type="button" class="rtw_edit_tier_pro rtwwdpdl_edit_dt_row" value="'.esc_attr__('Edit', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'" /></a>
				<a href="'.esc_url( $rtwwdpdl_absolute_url .'&deltier='.$key ).'"><input type="button" class="rtw_delete_row rtwwdpdl_delete_dt_row" value="'.esc_attr__('Delete', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'"/></a></td>';
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
				<th><?php esc_html_e( 'Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Check For', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min - Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
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