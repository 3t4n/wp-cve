<?php 
global $wp;
if(isset($_GET['delbr']))
{
	$rtwwdpdl_products_option = get_option('rtwwdpdl_bogo_rule');
	$rtwwdpdl_row_no = sanitize_text_field($_GET['delbr']);
	array_splice($rtwwdpdl_products_option, $rtwwdpdl_row_no, 1);
	update_option('rtwwdpdl_bogo_rule',$rtwwdpdl_products_option);
	$rtwwdpdl_new_url = esc_url(admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_bogo_rules');
	header('Location: '.$rtwwdpdl_new_url);
	die();
}

if(isset($_POST['rtwwdpdl_save_rule'])){
	if( isset( $_POST['rtwwdpd_bogo_field'] ) && wp_verify_nonce( $_POST['rtwwdpd_bogo_field'], 'rtwwdpd_bogo' ) ) 
	{
		$rtwwdpdl_prod = $_POST;
		$rtwwdpdl_option_no = sanitize_text_field($rtwwdpdl_prod['edit_chk_bogo']);
		$rtwwdpdl_products_option = get_option('rtwwdpdl_bogo_rule');
		if($rtwwdpdl_products_option == '')
		{
			$rtwwdpdl_products_option = array();
		}
		$rtwwdpdl_products = array();
		$rtwwdpdl_products_array = array();

		foreach($rtwwdpdl_prod as $key => $val){
			$rtwwdpdl_products[$key] = $val;
		}
		if($rtwwdpdl_option_no != 'save'){
			$rtw_edit_row = '';
			if( isset( $_REQUEST['editbogo'] ) )
			{
				$rtw_edit_row = sanitize_text_field( $_REQUEST['editbogo'] );
			}
			
			unset( $rtw_edit_row );
			$rtwwdpdl_products_option[$rtwwdpdl_option_no] = $rtwwdpdl_products;
		}
		else{
			$rtwwdpdl_products_option[] = $rtwwdpdl_products;
		}
		update_option('rtwwdpdl_bogo_rule',$rtwwdpdl_products_option);

		?>
		<div class="notice notice-success is-dismissible">
			<p><strong><?php esc_html_e('Rule saved.','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e('Dismiss this notices.','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
			</button>
		</div><?php
	}
	else {
		print 'Sorry, your are not allowed to access this page.';
   		exit;
	}
}?>

<div class="rtwwdpdl_right">
	<div class="rtwwdpdl_add_buttons">
		<input class="rtw-button rtwwdpdl_single_bogo_rule" type="button" name="rtwwdpdl_single_prod_rule" value="<?php esc_attr_e( 'Add BOGO Product Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		<input class="rtw-button rtwwdpdl_cat_bogo_rule" type="button" name="rtwwdpdl_combi_prod_rule" value="<?php esc_attr_e( 'Add BOGO Categorie Rule (Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
	</div>


	<?php	
	if(isset($_GET['editbogo']))
	{	
		$rtwwdpdl_url = esc_url( admin_url('admin.php').add_query_arg($_GET,$wp->request));

		$rtwwdpdl_bogo = get_option('rtwwdpdl_bogo_rule');
		$rtwwdpdl_prev_prod = $rtwwdpdl_bogo[ sanitize_text_field( $_GET['editbogo'] ) ];
		$key = 'editbogo';
		$filteredURL = preg_replace('~(\?|&)'.$key.'=[^&]*~', '$1', $rtwwdpdl_url);
		$rtwwdpdl_new_url = esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_bogo_rules');

		?>
		<div class="rtwwdpdl_single_bogo_rule_tab rtwwdpdl_active rtwwdpdl_form_layout_wrapper">
			<form action="<?php echo esc_url($rtwwdpdl_new_url); ?>" method="POST" accept-charset="utf-8">
				<?php wp_nonce_field( 'rtwwdpd_bogo', 'rtwwdpd_bogo_field' ); ?>
				<div id="woocommerce-product-data" class="postbox ">
					<div class="inside">
						<div class="panel-wrap product_data">
							<ul class="product_data_tabs wc-tabs">
								<li class="rtwwdpdl_bogo_rule_tab active">
									<a class="rtwwdpdl_link" id="rtwbogo_rule">
										<span><?php esc_html_e('Rule','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
									</a>
								</li>
								<li class="rtwwdpdl_restriction_tab">
									<a class="rtwwdpdl_link" id="rtwbogo_restrict">
										<span><?php esc_html_e('Restrictions','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
									</a>
								</li>
								<li class="rtwwdpdl_time_tab">
									<a class="rtwwdpdl_link" id="rtwbogo_validity">
										<span><?php esc_html_e('Validity','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
									</a>
								</li>
							</ul>

							<div class="panel woocommerce_options_panel">
								<div class="options_group rtwwdpdl_active" id="rtwbogo_rule_tab">
									<input type="hidden" id="edit_chk_bogo" name="edit_chk_bogo" value="<?php echo esc_attr( sanitize_text_field( $_GET['editbogo'] ) ); ?>">
									<table class="rtwwdpdl_table_edit">
										<tr>
											<td>
												<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
											</td>
											<td>
												<input type="text" name="rtwwdpdl_bogo_offer_name" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" required="required" value="<?php echo isset($rtwwdpdl_prev_prod['rtwwdpdl_bogo_offer_name']) ? esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_bogo_offer_name']) : ''; ?>">

												<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</i>
											</td>
										</tr>
									</table>
									<h3 class="rtw_tbltitle"><?php esc_html_e('Product Need to be Purchased', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></h3>
									<table id="rtwproduct_table">
										<thead>
											<tr>
												<th class="rtwtable_header"><?php esc_attr_e('Row no', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
												<th class="rtwtable_header"><?php esc_html_e('Product Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
												<th class="rtwtable_header"><?php esc_attr_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
												<th class="rtwtable_header"><?php esc_attr_e('Remove Item', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
											</tr>
										</thead>
										<tbody id="product_list_body">
											<?php
											if( isset( $rtwwdpdl_prev_prod['product_id'] ) && is_array( $rtwwdpdl_prev_prod['product_id'] ) && !empty( $rtwwdpdl_prev_prod['product_id'] ) ){
											foreach ($rtwwdpdl_prev_prod['product_id'] as $key => $val) {
											?>
											<tr>
												<td id="td_row_no"><?php echo ($key +1)?></td>
												<td id="td_product_name">
													<select id="rtwproduct" name="product_id[]" class="wc-product-search rtwwdpdl_prod_tbl_class"  data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" >
													<?php
													$product = wc_get_product($val);
													if (is_object($product)) {
														echo '<option value="' . esc_attr($val) . '"' . selected(true, true, false) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
													}
													?>
												</select>
											</td>
											<td id="td_quant">
												<input type="number" min="0" name="combi_quant[]" value="<?php echo isset($rtwwdpdl_prev_prod['combi_quant'][$key]) ? $rtwwdpdl_prev_prod['combi_quant'][$key] : ''; ?>"  />
											</td>
											<td id="td_remove">
												<a class="button insert remove" name="deletebtn" id="deletebtn" ><?php esc_html_e('Remove', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
											</td>
										</tr><?php } } else{?>
										<tr>
											<td id="td_row_no">1</td>
											<td id="td_product_name">
												<select id="rtwproduct" name="product_id[]" class="wc-product-search rtwwdpdl_prod_tbl_class" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" >
												</select>
											</td>
											<td id="td_quant">
												<input type="number" min="0" name="combi_quant[]" value=""  />
											</td>
											<td id="td_remove">
												<?php esc_html_e('Min One product.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</td>
										</tr>
										<?php } ?>	
									</tbody>
									<tfoot>
										<tr>
											<td colspan="5">
												<a  class="button insert" name="rtwnsertbtn" id="rtwinsertbtnbogo" ><?php esc_html_e('Add Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
											</td>
										</tr>
									</tfoot>
								</table>

								<caption class="rtw_tbltitle"><?php esc_html_e('Free Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></caption>
								<table id="rtwbogo_table_pro">
									<thead>
										<tr>
											<th class="rtwtable_header"><?php esc_attr_e('Row no', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
											<th class="rtwtable_header"><?php esc_attr_e('Product Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
											<th class="rtwtable_header"><?php esc_attr_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
											<th class="rtwtable_header"><?php esc_html_e('Remove Item', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										</tr>
									</thead>
									<tbody id="rtw_bogo_row">
										<?php
										if(isset($rtwwdpdl_prev_prod['rtwbogo']) && is_array($rtwwdpdl_prev_prod['rtwbogo']) && !empty($rtwwdpdl_prev_prod['rtwbogo']))
										{
										foreach ($rtwwdpdl_prev_prod['rtwbogo'] as $key => $val) {
											?>
											<tr>
												<td id="td_row_no"><?php echo ($key +1)?></td>
												<td id="td_product_name">

													<select id="rtwproduct" name="rtwbogo[]" class="wc-product-search rtwwdpdl_prod_tbl_class" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" >
														<?php
														$product = wc_get_product($val);
														if (is_object($product)) {
															echo '<option value="' . esc_attr($val) . '"' . selected(true, true, false) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
														}
														?>
													</select>
												</td>
												<td id="td_quant">
													<input type="number" min="0" name="bogo_quant_free[]" value="<?php echo isset($rtwwdpdl_prev_prod['bogo_quant_free'][$key]) ? esc_attr($rtwwdpdl_prev_prod['bogo_quant_free'][$key]) : ''; ?>"  />
												</td>
												<td id="td_remove">
													<a class="button insert remove" name="deletebtn" id="deletebtn" ><?php esc_html_e('Remove', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
												</td>
											</tr><?php }} else { ?>
											<tr>
												<td id="td_row_no">1</td>
												<td id="td_product_name">
													<select id="rtwproduct" name="rtwbogo[]" class="wc-product-search rtwwdpdl_prod_tbl_class" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" >
													</select>
												</td>
												<td id="td_quant">
													<input type="number" min="0" name="bogo_quant_free[]" value=""  />
												</td>
												<td id="td_remove">
													<?php esc_html_e('Min One product.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</td>
											</tr>
											<?php }?>	
										</tbody>
										<tfoot>
											<tr>
												<td colspan=3>
													<a  class="button insert" name="rtwnsertbtn" id="rtwinsert_bogo_pro" ><?php esc_html_e('Add Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>

								<div class="options_group rtwwdpdl_inactive" id="rtwbogo_restrict_tab">
									<table class="rtwwdpdl_table_edit">
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
											<input type="number" disabled="disabled" value="0" min="0" name="rtwwdpdl_bogo_min_orders">
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
											<input type="number" disabled="disabled" value="0" min="0" name="rtwwdpdl_bogo_min_spend">
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum amount need to be spent by a customer on previous orders to be eligible for this discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
								</table>
							</div>

							<div class="options_group rtwwdpdl_inactive" id="rtwbogo_validity_tab">
								<table class="rtwwdpdl_table_edit">
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Valid from', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<input type="date" name="rtwwdpdl_bogo_from_date" placeholder="YYYY-MM-DD" required="required" value="<?php echo esc_attr( $rtwwdpdl_prev_prod['rtwwdpdl_bogo_from_date']); ?>" />
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
											<input type="date" name="rtwwdpdl_bogo_to_date" placeholder="YYYY-MM-DD" required="required" value="<?php echo esc_attr( $rtwwdpdl_prev_prod['rtwwdpdl_bogo_to_date']); ?>"/>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'The date till which the rule would be applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e(' Based On Day', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<input type="checkbox" name="rtwwdpdl_enable_day_bogo" value="yes" class="rtwwdpdl_day_chkbox" <?php checked(isset($rtwwdpdl_prev_prod['rtwwdpdl_enable_day_bogo']) ? $rtwwdpdl_prev_prod['rtwwdpdl_enable_day_bogo'] : '', 'yes'); ?>/>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Check If You want to Set Discount on Speciifc Day.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											
											</i>
										</td>
									</tr>
									<tr class="rtwwdpdl_daywise_rule_row">
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Day', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<select class="rtwwdpdl_select_day_bogo" name="rtwwdpdl_select_day_bogo">
												
												<option value="">
													<?php esc_html_e( '-- Select --', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="7"  <?php selected($rtwwdpdl_prev_prod['rtwwdpdl_select_day_bogo'], 7) ?>>
													<?php esc_html_e( 'Sunday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="1" <?php selected($rtwwdpdl_prev_prod['rtwwdpdl_select_day_bogo'], 1) ?>>
													<?php esc_html_e( 'Monday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="2" <?php selected($rtwwdpdl_prev_prod['rtwwdpdl_select_day_bogo'], 2) ?>>
													<?php esc_html_e( 'Tuesday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="3" <?php selected($rtwwdpdl_prev_prod['rtwwdpdl_select_day_bogo'], 3) ?>>
													<?php esc_html_e( 'Wednesday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="4" <?php selected($rtwwdpdl_prev_prod['rtwwdpdl_select_day_bogo'], 4) ?>>
													<?php esc_html_e( 'Thursday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="5" <?php selected($rtwwdpdl_prev_prod['rtwwdpdl_select_day_bogo'], 5) ?>>
													<?php esc_html_e( 'Friday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="6" <?php selected($rtwwdpdl_prev_prod['rtwwdpdl_select_day_bogo'], 6) ?>>
													<?php esc_html_e( 'Saturday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												
											</select>
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
<?php include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_bogo_cat.php' ); ?>
</div>
<?php }
else { ?>
	<div class="rtwwdpdl_single_bogo_rule_tab rtwwdpdl_inactive rtwwdpdl_form_layout_wrapper">
		<form action="" method="POST" accept-charset="utf-8">
			<?php wp_nonce_field( 'rtwwdpd_bogo', 'rtwwdpd_bogo_field' ); ?>
			<div id="woocommerce-product-data" class="postbox ">
				<div class="inside">
					<div class="panel-wrap product_data">
						<ul class="product_data_tabs wc-tabs">
							<li class="rtwwdpdl_bogo_rule_tab active">
								<a class="rtwwdpdl_link" id="rtwbogo_rule">
									<span><?php esc_html_e('Rule','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li class="rtwwdpdl_restriction_tab">
								<a class="rtwwdpdl_link" id="rtwbogo_restrict">
									<span><?php esc_html_e('Restrictions','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li class="rtwwdpdl_time_tab">
								<a class="rtwwdpdl_link" id="rtwbogo_validity">
									<span><?php esc_html_e('Validity','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
						</ul>

						<div class="panel woocommerce_options_panel">
							<div class="options_group rtwwdpdl_active" id="rtwbogo_rule_tab">
								<input type="hidden" id="edit_chk_bogo" name="edit_chk_bogo" value="save">
								<table class="rtwwdpdl_table_edit">
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
										</td>
										<td>
											<input type="text" name="rtwwdpdl_bogo_offer_name" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" required="required" value="">

											<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
								</table>
								<h3 class="rtw_tbltitle"><?php esc_attr_e('Product Need to be Purchased', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></h3>
								<table id="rtwproduct_table">
									<thead>
										<tr>
											<th class="rtwtable_header"><?php esc_attr_e('Row no', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
											<th class="rtwtable_header"><?php esc_attr_e('Product Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
											<th class="rtwtable_header"><?php esc_attr_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
											<th class="rtwtable_header"><?php esc_html_e('Remove Item', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										</tr>
									</thead>
									<tbody id="product_list_body">
										<tr>
											<td id="td_row_no">1</td>
											<td id="td_product_name">
												<select id="rtwproduct" name="product_id[]" class="wc-product-search rtwwdpdl_prod_tbl_class" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" >
												</select>
											</td>
											<td id="td_quant">
												<input type="number" min="0" name="combi_quant[]" value=""  />
											</td>
											<td id="td_remove">
												<?php esc_html_e('Min One product.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="5">
												<a  class="button insert" name="rtwnsertbtn" id="rtwinsertbtnbogo" ><?php esc_html_e('Add Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
											</td>
										</tr>
									</tfoot>
								</table>

								<h3 class="rtw_tbltitle"><?php esc_attr_e('Free Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></h3>
								<table id="rtwbogo_table_pro">
									<thead>
										<tr>
											<th class="rtwtable_header"><?php esc_attr_e('Row no', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
											<th class="rtwtable_header"><?php esc_attr_e('Product Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
											<th class="rtwtable_header"><?php esc_attr_e('Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
											<th class="rtwtable_header"><?php esc_attr_e('Remove Item', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
										</tr>
									</thead>
									<tbody id="rtw_bogo_row">
										<tr>
											<td id="td_row_no">1</td>
											<td id="td_product_name">
												<select id="rtwproduct" name="rtwbogo[]" class="wc-product-search rtwwdpdl_prod_tbl_class" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" >
												</select>
											</td>
											<td id="td_quant">
												<input type="number" min="0" name="bogo_quant_free[]" value=""  />
											</td>
											<td id="td_remove">
												<?php esc_html_e('Min One product.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td colspan=3>
												<a  class="button insert" name="rtwnsertbtn" id="rtwinsert_bogo_pro" ><?php esc_html_e('Add Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>

							<div class="options_group rtwwdpdl_inactive" id="rtwbogo_restrict_tab">
								<table class="rtwwdpdl_table_edit">
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<select disabled="disabled" class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles[]" multiple="multiple">
												<option selected value="<?php echo esc_attr($roles); ?>">
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
											<input disabled="disabled" type="number" value="0" min="0" name="rtwwdpdl_bogo_min_orders">
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
											<input disabled="disabled" type="number" value="0" min="0" name="rtwwdpdl_bogo_min_spend">
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Minimum amount need to be spent by a customer on previous orders to be eligible for this discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
								</table>
							</div>

							<div class="options_group rtwwdpdl_inactive" id="rtwbogo_validity_tab">
								<table class="rtwwdpdl_table_edit">
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Valid from', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<input type="date" name="rtwwdpdl_bogo_from_date" placeholder="YYYY-MM-DD" required="required" value="" />
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
											<input type="date" name="rtwwdpdl_bogo_to_date" placeholder="YYYY-MM-DD" required="required" value=""/>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'The date till which the rule would be applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</i>
										</td>
									</tr>
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e(' Based On Day', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<input type="checkbox" name="rtwwdpdl_enable_day_bogo" value="yes" class="rtwwdpdl_day_chkbox"/>
											<i class="rtwwdpdl_description"><?php esc_html_e( 'Check If You want to Set Discount on Speciifc Day.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											
											</i>
										</td>
									</tr>
									<tr class="rtwwdpdl_daywise_rule_row">
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Day', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<select class="rtwwdpdl_select_day_bogo" name="rtwwdpdl_select_day_bogo">
												
												<option value="">
													<?php esc_html_e( '-- Select --', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="7">
													<?php esc_html_e( 'Sunday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="1">
													<?php esc_html_e( 'Monday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="2">
													<?php esc_html_e( 'Tuesday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="3">
													<?php esc_html_e( 'Wednesday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="4">
													<?php esc_html_e( 'Thursday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="5">
													<?php esc_html_e( 'Friday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												<option value="6">
													<?php esc_html_e( 'Saturday', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
												</option>
												
											</select>
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
<?php include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_bogo_cat.php' ); ?>
</div>
<?php } 
if(isset($_GET['editbogo']) && !isset($_GET['editbcat']))
{
	echo '<div class="rtwwdpdl_bogo_edit_table rtwwdpdl_active">';
}
elseif(isset($_GET['editbcat']))
{
	echo '<div class="rtwwdpdl_bogo_table rtwwdpdl_inactive">';
}else{
	echo '<div class="rtwwdpdl_bogo_table">';
}
?>
	<table class="rtwtable table table-striped table-bordered dt-responsive nowrap" data-value="bogo_tbl" cellspacing="0">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Purchased Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Purchased Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Free Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Free Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Min Order Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Repeat', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
			</tr>
		</thead>
		<?php
		$rtwwdpdl_products_option = get_option( 'rtwwdpdl_bogo_rule' );
		$rtwwdpdl_absolute_url = esc_url( admin_url('admin.php').add_query_arg( $_GET,$wp->request ) );

		if( is_array( $rtwwdpdl_products_option ) &&  !empty( $rtwwdpdl_products_option ) ) { ?>
			<tbody>
				<?php
				foreach ( $rtwwdpdl_products_option as $key => $value ) {
					echo '<tr data-val="'.$key.'">';

					echo '<td class="rtwrow_no">'.esc_html__( $key+1 , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					echo '<td class="rtw_drag"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ).'"/></td>';

					echo '<td>'.( isset( $value['rtwwdpdl_bogo_offer_name'] ) ? esc_html__( $value['rtwwdpdl_bogo_offer_name'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

					echo '<td>';
					if(isset( $value['product_id'] ) && is_array( $value['product_id'] ) && !empty( $value['product_id'] ) )
					{
						foreach ( $value['product_id'] as $val ) {
							echo esc_html__( get_the_title( $val ), 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<br>';
						}
					}
					echo '</td>';

					echo '<td>';
					if( isset( $value['combi_quant'] ) && is_array( $value['combi_quant'] ) && !empty( $value['combi_quant'] ) )
					{
						foreach ( $value['combi_quant'] as $val ) {
							echo esc_html__( $val , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<br>';
						}
					}
					
					echo '</td>';

					echo '<td>';
					if(isset( $value['rtwbogo'] ) && is_array( $value['rtwbogo'] ) && !empty( $value['rtwbogo'] ) )
					{
						foreach ( $value['rtwbogo'] as $val ) {
							echo esc_html__(get_the_title( $val ), 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<br>';
						}
					}
					echo '</td>';

					echo '<td>';
					if(isset( $value['bogo_quant_free'] ) && is_array( $value['bogo_quant_free'] ) && !empty( $value['bogo_quant_free'] ) )
					{
						foreach ( $value['bogo_quant_free'] as $val ) {
							echo esc_html__( $val , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<br>';
						}
					}
					
					echo '</td>';

					echo '<td>';
					esc_html_e( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
					echo '</td>';

					echo '<td>0</td>';

					echo '<td>0</td>';

					echo '<td>'.( isset( $value['rtwwdpdl_bogo_from_date'] ) ? esc_html__( $value['rtwwdpdl_bogo_from_date'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '' ).'</td>';

					echo '<td>'.( isset( $value['rtwwdpdl_bogo_to_date'] ) ? esc_html__( $value['rtwwdpdl_bogo_to_date'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '' ).'</td>';

					echo '<td>';
					if( isset( $value['rtwwdpdl_repeat_bogo'] ) )
					{
						echo esc_html__( $value['rtwwdpdl_repeat_bogo'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
					}
					else{
						esc_html_e('No', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
					}
					echo '</td>';

					echo '<td><a href="'.esc_url( $rtwwdpdl_absolute_url .'&editbogo='.$key ).'"><input type="button" class="rtw_edit_bogo rtwwdpdl_edit_dt_row" value="'.esc_html__('Edit', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'" /></a>
					<a href="'.esc_url( $rtwwdpdl_absolute_url .'&delbr='.$key ).'"><input type="button" class="rtw_delete_row rtwwdpdl_delete_dt_row" value="'.esc_html__('Delete', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'"/></a></td>';
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
				<th><?php esc_html_e( 'Purchased Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Purchased Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Free Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
				<th><?php esc_html_e( 'Free Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
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