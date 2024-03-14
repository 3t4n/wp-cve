<?php
global $wp;
if( isset( $_GET['delpay'] ) )
{
	$rtwwdpdl_products_option = get_option('rtwwdpdl_pay_method');
	$rtwwdpdl_row_no = sanitize_text_field( $_GET['delpay'] );
	array_splice( $rtwwdpdl_products_option, $rtwwdpdl_row_no, 1 );
	update_option( 'rtwwdpdl_pay_method', $rtwwdpdl_products_option );
	$rtwwdpdl_new_url = admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_payment_method';
	header( 'Location: '. $rtwwdpdl_new_url );
    die();
}

if(isset($_POST['rtwwdpdl_save_pay_rule'])){
	if( isset( $_POST['rtwwdpd_pay_field'] ) && wp_verify_nonce( $_POST['rtwwdpd_pay_field'], 'rtwwdpd_pay' ) ) 
	{
		$rtwwdpdl_prod = $_POST;
		$rtwwdpdl_option_no = sanitize_text_field( $rtwwdpdl_prod['edit_pay'] );
		$rtwwdpdl_products_option = get_option('rtwwdpdl_pay_method');
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
			$rtw_edit_row = isset( $_REQUEST['edit_pay'] ) ? sanitize_text_field( $_REQUEST['edit_pay'] ) : '';
			unset( $rtw_edit_row );
			$rtwwdpdl_products_option[ $rtwwdpdl_option_no ] = $rtwwdpdl_products;
		}
		else{
			$rtwwdpdl_products_option[] = $rtwwdpdl_products;
		}
		update_option('rtwwdpdl_pay_method',$rtwwdpdl_products_option);
		?>
		<div class="notice notice-success is-dismissible">
			<p><strong><?php esc_html_e('Rule saved.','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e('Dismiss this notices.','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
			</button>
		</div>
		<?php
	}else {
		esc_html_e( 'Sorry, your are not allowed to access this page.' , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
   		exit;
	}
}
?>
<div class="rtwwdpdl_right">
	<div class="rtwwdpdl_add_buttons">
		<input class="rtw-button rtwwdpdl_single_prod_rule" id="add_single_product" type="button" name="rtwwdpdl_single_prod_rule" value="<?php esc_attr_e( 'Add New Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
	</div> 
<?php
if( isset( $_GET['edit_pay'] ) )
{	
	$rtwwdpdl_url = esc_url( admin_url('admin.php').add_query_arg( $_GET,$wp->request ));
	
	$rtwwdpdl_products_option = get_option('rtwwdpdl_pay_method');
	$rtwwdpdl_prev_prod = $rtwwdpdl_products_option[ sanitize_text_field( $_GET['edit_pay'] )];
	$key = 'edit_pay';
	$filteredURL = preg_replace('~(\?|&)'.$key.'=[^&]*~', '$1', $rtwwdpdl_url);
	$rtwwdpdl_new_url = esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_payment_method');
?>
<div class="rtwwdpdl_add_single_rule rtwwdpdl_active rtwwdpdl_form_layout_wrapper">
	<form method="post" action="<?php echo esc_url($rtwwdpdl_new_url); ?>" enctype="multipart/form-data">
		<?php wp_nonce_field( 'rtwwdpd_pay', 'rtwwdpd_pay_field' ); ?>
		<div id="woocommerce-product-data" class="postbox ">
			<div class="inside">
				<div class="panel-wrap product_data">
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
							<input type="hidden" id="edit_pay" name="edit_pay" value="<?php echo esc_attr( sanitize_text_field( $_GET['edit_pay'] ) ); ?>">
							<table class="rtwwdpdl_table_edit">
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<input type="text" name="rtwwdpdl_pay_offer_name" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" required="required" value="<?php echo isset($rtwwdpdl_prev_prod['rtwwdpdl_pay_offer_name']) ? esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_pay_offer_name']) : ''; ?>">

										<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
										</i>
									</td>
								</tr>
							
								<?php
								$rtwwdpdl_payment_gateway_obj = new WC_Payment_Gateways();
								$rtwwdpdl_available_methods = $rtwwdpdl_payment_gateway_obj->get_available_payment_gateways();
								$rtwwdpdl_get_title = function($obj){
									return $obj->get_method_title(); };

								$rtwwdpdl_available_methods = array_map( $rtwwdpdl_get_title,$rtwwdpdl_available_methods );				
								?>
		            			<tr>
		            				<td>
		            					<label class="rtwwdpdl_label"><?php esc_html_e('Choose Payment Methods', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>	
		            				</td>
		            				<td>
		            					<select class="rtwwdpdl_payment_method" name="allowed_payment_methods">
		            						<?php 
		            						if( is_array( $rtwwdpdl_available_methods ) && !empty( $rtwwdpdl_available_methods ) )
		            						{
			            						foreach ( $rtwwdpdl_available_methods as $key => $value ) 
			            						{
			            							if( isset( $rtwwdpdl_prev_prod['allowed_payment_methods'] ) )
						            				{ 			 
						            					if( $key == $rtwwdpdl_prev_prod['allowed_payment_methods'] )
					            						{
				            								echo '<option value="' . esc_attr($key) . '"' . selected($key, $rtwwdpdl_prev_prod['allowed_payment_methods']) . '>' . esc_html($value) . '</option>'; 
				            							}
				            							else{
				            								echo '<option value="' . esc_attr($key) . '">' . esc_html($value) . '</option>';
				            							}
					            					}
					            					else{
			            								echo '<option value="' . esc_attr($key) . '">' . esc_html($value) . '</option>';
			            							}
				            					}
				            				}
			            					?>
		            					</select>
		            				</td>
		            			</tr>
		            			<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<select name="rtwwdpdl_pay_discount_type">
											<option value="rtwwdpdl_discount_percentage" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_pay_discount_type'], 'rtwwdpdl_discount_percentage'); ?>>
												<?php esc_html_e( 'Percent Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</option>
											<option value="rtwwdpdl_flat_discount_amount" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_pay_discount_type'], 'rtwwdpdl_flat_discount_amount'); ?>>
												<?php esc_html_e( 'Flat Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</option>
											<option value="rtwwdpdl_fixed_price" <?php selected( $rtwwdpdl_prev_prod['rtwwdpdl_pay_discount_type'], 'rtwwdpdl_fixed_price' ); ?>>
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
										<input type="number" value="<?php echo isset($rtwwdpdl_prev_prod['rtwwdpdl_pay_discount_value']) ? $rtwwdpdl_prev_prod['rtwwdpdl_pay_discount_value'] : ''; ?>" required="required" min="0" step="0.1" name="rtwwdpdl_pay_discount_value">
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
					            		<label class="rtwwdpdl_label"><?php esc_html_e( 'Maximum Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
					            		</label>
				            		</td>
				            		<td>
				            			<input type="number" value="<?php echo isset($rtwwdpdl_prev_prod['rtwwdpdl_pay_max_discount']) ? esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_pay_max_discount']) : '' ; ?>" min="0" name="rtwwdpdl_pay_max_discount">
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
				            			<select class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles[]" multiple="multiple">
				            				<option selected="selected" value="all">
												<?php esc_html_e( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</option>
				            			</select>
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
										<label class="rtwwdpdl_label"><?php esc_html_e('On Minimum Purchase Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
			            				</label>
									</td>
									<td>
										<input disabled="disabled" type="number" value="0" min="0" name="rtwwdpdl_min_prod_cont">
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
				            			<input type="checkbox" value="yes" name="rtwwdpdl_pay_exclude_sale" <?php checked( isset( $rtwwdpdl_prev_prod['rtwwdpdl_pay_exclude_sale'] ) ? $rtwwdpdl_prev_prod['rtwwdpdl_pay_exclude_sale'] : 'no' , 'yes'); ?>/>
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
				           				<input type="date" name="rtwwdpdl_pay_from_date" placeholder="YYYY-MM-DD" required="required" value="<?php echo esc_attr( $rtwwdpdl_prev_prod['rtwwdpdl_pay_from_date']); ?>" />
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
			           						<input type="date" name="rtwwdpdl_pay_to_date" placeholder="YYYY-MM-DD" required="required" value="<?php echo esc_attr( $rtwwdpdl_prev_prod['rtwwdpdl_pay_to_date']); ?>"/>
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
			<div class="rtwwdpdl_prod_combi_save_n_cancel rtwwdpdl_btn_save_n_cancel"> 
				<input class="rtw-button rtwwdpdl_save_combi_rule" type="submit" name="rtwwdpdl_save_pay_rule" value="<?php esc_attr_e( 'Update Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
				<input class="rtw-button rtwwdpdl_cancel_rule" type="submit" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			</div>
		</form>
	</div>
<?php }else{
 ?>
<div class="rtwwdpdl_add_single_rule rtwwdpdl_form_layout_wrapper">
	<form method="post" action="" enctype="multipart/form-data">
		<?php wp_nonce_field( 'rtwwdpd_pay', 'rtwwdpd_pay_field' ); ?>
		<div id="woocommerce-product-data" class="postbox ">
			<div class="inside">
				<div class="panel-wrap product_data">
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
							<input type="hidden" id="edit_pay" name="edit_pay" value="save"/>
							<table class="rtwwdpdl_table_edit">
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<input type="text" name="rtwwdpdl_pay_offer_name" placeholder="<?php esc_html_e('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>" required="required" value="">

										<i class="rtwwdpdl_description"><?php esc_html_e( 'This title will be displayed in the Offer listings.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
										</i>
									</td>
								</tr>
								<tr>
									<?php 
									$rtwwdpdl_payment_gateway_obj = new WC_Payment_Gateways();
									$rtwwdpdl_available_methods=$rtwwdpdl_payment_gateway_obj->get_available_payment_gateways();
									$rtwwdpdl_get_title=function($obj){
										return $obj->get_method_title(); };

									$rtwwdpdl_available_methods = array_map($rtwwdpdl_get_title,$rtwwdpdl_available_methods);
									?>
		            				<td>
		            					<label class="rtwwdpdl_label"><?php esc_html_e('Choose Payment Methods', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>	
		            				</td>
		            				<td>
		            					<select class="rtwwdpdl_payment_method" name="allowed_payment_methods">
		            						<?php 
		            						if( is_array( $rtwwdpdl_available_methods ) && !empty( $rtwwdpdl_available_methods ) )
		            						{
			            						foreach ( $rtwwdpdl_available_methods as $key => $value ) 
			            						{
			            							echo '<option value="' . esc_attr($key) . '">' . esc_html($value) . '</option>';
				            					}
				            				}
			            					?>
		            					</select>
		            				</td>
		            			</tr>
		            			<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label>
									</td>
									<td>
										<select name="rtwwdpdl_pay_discount_type">
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
										<input type="number" value="" required="required" min="0" step="0.1" name="rtwwdpdl_pay_discount_value">
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
				            			<input type="number" value="" min="0" name="rtwwdpdl_pay_max_discount"/>
										<i class="rtwwdpdl_description"><?php esc_html_e( 'This is used to set a threshold limit on the discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										<b class="rtwwdpdl_required" ><?php esc_html_e( 'Required', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></b>
										</i>
									</td>
								</tr>
								<tr>
									<td>
				            			<label class="rtwwdpdl_label"><?php esc_html_e('Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); 
				            			?>
				            			</label>
				            		</td>
				            		<td>
				            			<select disabled="disabled" class="rtwwdpdl_select_roles" name="rtwwdpdl_select_roles_com[]" multiple="multiple">
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
										<label class="rtwwdpdl_label"><?php esc_html_e('Minimum amount spend', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
			            				</label>
									</td>
									<td>
										<input disabled="disabled" type="number" value="0" min="0" name="rtwwdpdl_min_spend"/>
										<i class="rtwwdpdl_description rtwwdpdl_text_pro"><?php esc_html_e( 'Available in Pro version.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
										</i>
									</td>
								</tr>
								<tr>
									<td>
										<label class="rtwwdpdl_label"><?php esc_html_e('On Minimum Purchase Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
			            				</label>
									</td>
									<td>
										<input disabled="disabled" type="number" value="0" min="0" name="rtwwdpdl_min_prod_cont"/>
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
			            				<input type="checkbox" value="yes" name="rtwwdpdl_pay_exclude_sale"/>
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
			           					<input type="date" name="rtwwdpdl_pay_from_date" placeholder="YYYY-MM-DD" required="required" value="" />
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
			           					<input type="date" name="rtwwdpdl_pay_to_date" placeholder="YYYY-MM-DD" required="required" value=""/>
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
		<div class="rtwwdpdl_prod_combi_save_n_cancel rtwwdpdl_btn_save_n_cancel"> 
			<input class="rtw-button rtwwdpdl_save_combi_rule" type="submit" name="rtwwdpdl_save_pay_rule" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		</div>
	</form>
</div>
<?php } ?>
<div class="rtwwdpdl_prod_table">
	<table class="rtwtable table table-striped table-bordered dt-responsive nowrap" data-value="pay_tbl" cellspacing="0">
		<thead>
			<tr>
		    	<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Payment Method', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Min Spend', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Min Product Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_attr_e( 'Exclude Sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		  	</tr>
		</thead>
		<?php $rtwwdpdl_products_option = get_option('rtwwdpdl_pay_method');
		
		$rtwwdpdl_absolute_url = esc_url( admin_url('admin.php').add_query_arg($_GET,$wp->request));

		$rtwwdpdl_payment_gateway_obj = new WC_Payment_Gateways();
	    $rtwwdpdl_available_methods = $rtwwdpdl_payment_gateway_obj->get_available_payment_gateways();
	    
	    $rtwwdpdl_get_title = function($obj){
    		return $obj->get_method_title(); };
	    $rtwwdpdl_available_methods = array_map( $rtwwdpdl_get_title,$rtwwdpdl_available_methods );

		if( is_array( $rtwwdpdl_products_option ) && !empty( $rtwwdpdl_products_option ) ){	?>
		<tbody>
			<?php
				foreach ( $rtwwdpdl_products_option as $key => $value ) {

					echo '<tr data-val="'.$key.'">';

					echo '<td class="rtwrow_no">'.esc_html( $key+1 ).'</td>';
					echo '<td class="rtw_drag"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"/></td>';
					
					echo '<td>'.( isset( $value['rtwwdpdl_pay_offer_name'] ) ? esc_html($value['rtwwdpdl_pay_offer_name'] ) : '').'</td>';

					echo '<td>'.( isset( $rtwwdpdl_available_methods[ $value['allowed_payment_methods'] ] ) ? esc_html__($rtwwdpdl_available_methods[$value['allowed_payment_methods']], 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

					if($value['rtwwdpdl_pay_discount_type'] == 'rtwwdpdl_discount_percentage')
					{
						echo '<td>'.esc_html__('Percentage', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					}
					elseif($value['rtwwdpdl_pay_discount_type'] == 'rtwwdpdl_flat_discount_amount')
					{
						echo '<td>'.esc_html__('Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					}
					else{
						echo '<td>'.esc_html__('Fixed Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</td>';
					}
					
					echo '<td>'.(isset($value['rtwwdpdl_pay_discount_value']) ? esc_html__($value['rtwwdpdl_pay_discount_value'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';
					
					echo '<td>'.(isset($value['rtwwdpdl_pay_max_discount']) ? esc_html__($value['rtwwdpdl_pay_max_discount'] , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) : '').'</td>';

					echo '<td>0</td>';

					echo '<td>0</td>';

					echo '<td>';
					esc_html_e('All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
					echo '</td>';
					
					echo '<td>'.(isset($value['rtwwdpdl_pay_from_date']) ? esc_html($value['rtwwdpdl_pay_from_date'] ) : '').'</td>';
					
					echo '<td>'.(isset($value['rtwwdpdl_pay_to_date']) ? esc_html($value['rtwwdpdl_pay_to_date'] ) : '').'</td>';
					
					echo '<td>';
					if(isset($value['rtwwdpdl_pay_exclude_sale']))
					{
						echo esc_html__($value['rtwwdpdl_pay_exclude_sale'], 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
					}
					else{
						esc_html_e( 'No', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
					}
					echo '</td>';
					
					echo '<td><a href="'.esc_url( $rtwwdpdl_absolute_url .'&edit_pay='.$key ).'"><input type="button" class="rtwwdpdl_edit_dt_row" value="'.esc_attr__( 'Edit', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'" /></a>
							<a href="'.esc_url( $rtwwdpdl_absolute_url .'&delpay='.$key ).'"><input type="button" class="rtw_delete_row rtwwdpdl_delete_dt_row" value="'.esc_attr__( 'Delete', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'"/></a></td>';
					echo '</tr>';
				}
			?>		
		</tbody>
		<?php } ?>
		<tfoot>
			<tr>
		    	<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Payment Method', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Discount Type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Value', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Max Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Min Spend', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Min Product Count', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'From', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'To', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_attr_e( 'Exclude Sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		  	</tr>
		</tfoot>
	</table>
</div>