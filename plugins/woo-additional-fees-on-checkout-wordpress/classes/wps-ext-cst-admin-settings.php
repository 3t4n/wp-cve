<?php
class WPS_EXT_CST_Admin_Settings
{
	public static function register_admin_settings(){
		add_settings_section("wps-ext-cst-option-section", "Additional Fees Settings", null, "wps-ext-cst-options");

		register_setting("wps-ext-cst-option-section", "ext_cst_status");
		register_setting("wps-ext-cst-option-section", "ext_cst_label");
		register_setting("wps-ext-cst-option-section", "ext_cst_label_billing");
		register_setting("wps-ext-cst-option-section", "ext_cst_amount_type");
		register_setting("wps-ext-cst-option-section", "ext_cst_amount");
		register_setting("wps-ext-cst-option-section", "ext_cst_auto_checked");
		register_setting("wps-ext-cst-option-section", "ext_cst_apply_cndtn");
		register_setting("wps-ext-cst-option-section", "cart_total_amount_min");
		register_setting("wps-ext-cst-option-section", "cart_total_amount_max");
		register_setting("wps-ext-cst-option-section", "cart_no_product_min");
		register_setting("wps-ext-cst-option-section", "cart_no_product_max");
		register_setting("wps-ext-cst-option-section", "selected_product_id");
		register_setting("wps-ext-cst-option-section", "ext_cst_is_required");
		register_setting("wps-ext-cst-option-section", "ext_cst_label_css");
		register_setting("wps-ext-cst-option-section", "ext_cst_inc_ship_costs");
		register_setting("wps-ext-cst-option-section", "ext_cst_inc_tax");




		register_setting("wps-ext-cst-option-section", "ext_cst_extra");

	}
	public static function admin_settings(){
		?>
			<div class="wps-afoc-mainwraper">
				<div class="wps-afoc-main-wrap">
					<form method="post" action="options.php">
						<?php
						settings_fields("wps-ext-cst-option-section");
			            do_settings_sections("wps-ext-cst-options");

			            $ext_cst_status 	 = (get_option('ext_cst_status')) ? get_option('ext_cst_status') : 'enable';
			            $ext_cst_label 	 	 = (get_option('ext_cst_label')) ? get_option('ext_cst_label') : 'Unlabelled Fees';
			            $ext_cst_label_billing 	 	 = (get_option('ext_cst_label_billing')) ? get_option('ext_cst_label_billing') : 'Unlabelled Fees:';
			            $ext_cst_amount_type = (get_option('ext_cst_amount_type')) ? get_option('ext_cst_amount_type') : 'fixed';
			            $ext_cst_amount 	 = (get_option('ext_cst_amount')) ? get_option('ext_cst_amount') : 1;
			            $ext_cst_label_css   	 = (get_option('ext_cst_label_css')) ? get_option('ext_cst_label_css') : '';
			           	$extra_options = get_option('ext_cst_extra');            
			           ?>
			            <p>Before you start, please check <a href="https://www.wpsuperiors.com/woo-additional-fees-on-checkout/" target="_blank"><i>User Guide</i></a>. Have Fun :) </p>
			            <?php settings_errors(); ?>
			            <table class="form-table" style="width: 63%; margin-left: auto; margin-right: auto;">
			            	<tbody>
								<tr>
									<th scope="row"><label><?php _e( 'Status'); ?></label></th>
									<td>
										<select name="ext_cst_status" id="ext_cst_status">
											<option value="enable" <?php if($ext_cst_status=='enable'){echo 'selected';} ?>>Enable</option>
											<option value="disable" <?php if($ext_cst_status=='disable'){echo 'selected';} ?>>Disable</option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e( 'Label'); ?></label></th>
									<td>
										<input type="text" name="ext_cst_label" class="regular-text code" id="ext_cst_label" value="<?php echo $ext_cst_label; ?>"/>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e( 'Label for Billing'); ?></label></th>
									<td>
										<input type="text" name="ext_cst_label_billing" class="regular-text code" id="ext_cst_label_billing" value="<?php echo $ext_cst_label_billing; ?>"/>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e( 'Type'); ?></label></th>
									<td>
										<select name="ext_cst_amount_type" id="ext_cst_amount_type" onchange="showHideTaxShipping(this,1)">
											<option value="fixed" <?php if($ext_cst_amount_type=='fixed'){echo 'selected';} ?>>Fixed</option>
											<option value="percent" <?php if($ext_cst_amount_type=='percent'){echo 'selected';} ?>>Percentage</option>
										</select>
									</td>
								</tr>
								<tr id="incTax-1" class="incTax">
									<th scope="row"><label><?php _e( 'Calculate fees including TAX'); ?></label></th>
									<td>
										<select name="ext_cst_inc_tax" id="ext_cst_inc_tax">
											<option>Yes</option>
											<option>No</option>
										</select>
										<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
									</td>
								</tr>
								<tr id="incShipCosts-1" class="incShipCosts">
									<th scope="row"><label><?php _e( 'Calculate fees including Shipping Costs'); ?></label></th>
									<td>
										<select name="ext_cst_inc_ship_costs" id="ext_cst_inc_ship_costs">
											<option value="yes">Yes</option>
											<option value="no">No</option>
										</select>
										<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e( 'Amount'); ?></label></th>
									<td>
										<input type="number" step="any" name="ext_cst_amount" class="fees_amount regular-text code" id="ext_cst_amount" value="<?php echo $ext_cst_amount; ?>"/>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e( 'Apply Condition'); ?></label></th>
									<td>
										<select data-id="1" class="ext_cst_cndtn_dropdown">
											<option value="one_time">One Time Only</option>
											<option value="multiply">Multiplied By Product Quantity</option>
										</select>
										<p style="font-size:12px; font-style: italic;">If you want to charge additional fees for each product quantity into cart then choose <b>Multiplied By Product Quantity.</b> otherwise choose One Time Only.</p>
										<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e( 'Auto-checked/Auto-applied the fees'); ?></label></th>
									<td>
										<select name="ext_cst_auto_checked" id="ext_cst_auto_checked">
											<option value="enable">Enable</option>
											<option value="disable">Disable</option>
										</select>
										<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e( 'Condition'); ?></label></th>
									<td>
										<select data-id="1" class="ext_cst_cndtn_dropdown" name="ext_cst_apply_cndtn" id="ext_cst_apply_cndtn" onchange="show_hide_cndtn()">
											<option>All</option>
											<option value="cart_total_amount">Cart Total Amount</option>
											<option value="cart_no_product">Number of Product on Cart</option>
											<option value="selected_product">Selected Product</option>
											<option value="selected_category">Selected Category</option>
											<option value="selected_pr_type">Selected Product Type</option>
										</select>
									</td>
								</tr>
								<tr id="cart_total_amount" class="cndtn_mode">
									<th scope="row"><label><?php _e( 'Cart Amount'); ?></label></th>
									<td>
										<label>Minimum</label>
										<input type="number" name="cart_total_amount_min" class="small-text" id="cart_total_amount_min" value="<?php echo $cart_total_amount_min; ?>"/>

										<label>Maximum</label>
										<input type="number" name="cart_total_amount_max" class="small-text" id="cart_total_amount_max" value="<?php echo $cart_total_amount_max; ?>"/>
										<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
									</td>
								</tr>
								<tr id="cart_no_product" class="cndtn_mode">
									<th scope="row"><label><?php _e( 'No. Of Product on Cart'); ?></label></th>
									<td>
										<label>Minimum</label>
										<input type="number" name="cart_no_product_min" class="small-text" id="cart_no_product_min" value="<?php echo $cart_no_product_min; ?>"/>

										<label>Maximum</label>
										<input type="number" name="cart_no_product_max" class="small-text" id="cart_no_product_max" value="<?php echo $cart_no_product_max; ?>"/>
										<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
									</td>
								</tr>
								<tr id="selected_product" class="cndtn_mode">
									<th scope="row"><label><?php _e( 'Select Product'); ?></label></th>
									<td>
										<select name="selected_product_id[]" id="selected_product_id" multiple="multiple" class="wps_wafc_multiselect">
											<?php
												$args = array(
													'post_type' => 'product',
													'posts_per_page' => -1
												);
												$loop = new WP_Query( $args );
												if ( $loop->have_posts() ) {
													while ( $loop->have_posts() ) : $loop->the_post();?>
														<option><?php echo get_the_title(); ?></option>
														<?php
													endwhile;
												} else {
													echo '<option>No products found</option>';
												}
												wp_reset_postdata();
											?>
										</select>
										<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
									</td>
								</tr>
								<tr id="selected_category" class="cndtn_mode">
									<th scope="row"><label><?php _e( 'Select Product Category'); ?></label></th>
									<td>
										<select name="selected_cat_id[]" id="selected_cat_id" multiple="multiple" class="wps_wafc_multiselect">
											<?php
												$args = array(
													'taxonomy' => 'product_cat',
													'orderby' => 'name',
													'order' => 'ASC',
													'hide_empty' => false
												);
												$categories = get_categories( $args );
												if ( $categories ) {
													foreach( $categories as $category ) :?>
														<option><?php echo $category->name.' ('.$category->count.')'; ?></option>
														<?php
													endforeach;
												} else {
													echo '<option>No category found</option>';
												}
											?>
										</select>
										<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
									</td>
								</tr>
								<tr id="selected_pr_type" class="cndtn_mode">
									<th scope="row"><label><?php _e( 'Select Product Type'); ?></label></th>
									<td>
										<select name="selected_pr_type[]" id="selected_pr_type" multiple="multiple" class="wps_wafc_multiselect">
											<?php
												$pr_types = array('simple','grouped','variable','external_affiliate');
													foreach ( $pr_types as $types ) :?>
														<option><?php echo str_replace("_"," / ",ucwords($types)); ?></option>
														<?php
													endforeach;
											?>
										</select>
										<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e( 'Required Field'); ?></label></th>
									<td>
										<select name="ext_cst_is_required" id="ext_cst_is_required">
											<option value="no">No</option>
											<option value="yes">Yes</option>
										</select>
										<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e( 'Hide Option At Checkout'); ?></label></th>
									<td>
										<select>
											<option value="no">No</option>
											<option value="yes">Yes</option>
										</select>
										<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e( 'Custom CSS'); ?></label></th>
									<td>
										<textarea class="large-text code" name="ext_cst_label_css" id="ext_cst_label_css"><?php echo $ext_cst_label_css; ?></textarea>
									</td>
								</tr>
							</tbody>
			            </table>
			            <div id="wps_custom_fees_add_more" style="width: 64%; margin-left: auto; margin-right: auto;">
			            	<?php 

			            		if(is_array($extra_options) && !empty($extra_options)){
			            			$count = 2;

			            			foreach ($extra_options as $option => $value) {
			            				?>
			            				<div class="wps-ext-cst-fees" id="fees<?php echo $option; ?>">
								    		<h3 style="border-bottom: 1px solid black;">
								    			<span class="fees-title"><?php echo $value['label'] ? $value['label'] : ' Unlabelled Fees';?></span>
								    			<span style="float:right; color:red; cursor: pointer;" class="dashicons dashicons-trash" onclick="remove_fees(<?php echo $option; ?>)"></span>
								    		</h3>
								    		<table class="form-table">
								            	<tbody>
													<tr>
														<th scope="row"><label><?php _e( 'Status'); ?><label></th>
														<td>
															<select name="ext_cst_extra[<?php echo $option; ?>][status]" id="ext_cst_status_extra">
																<option value="enable" <?php if($value['status']=='enable'){ echo 'selected=selected';} ?>>Enable</option>
																<option value="disable" <?php if($value['status']=='disable'){ echo 'selected=selected';} ?>>Disable</option>
															</select>
														</td>
													</tr>
													<tr>
														<th scope="row"><label><?php _e( 'Label'); ?></labe></td>
														<td>
															<input type="text" name="ext_cst_extra[<?php echo $option; ?>][label]" class="regular-text code" id="ext_cst_label_extra" value="<?php echo $value['label'] ?>"/>
														</td>
													</tr>
													<tr>
														<th scope="row"><label><?php _e( 'Label for Billing'); ?></labe></td>
														<td>
															<input type="text" name="ext_cst_extra[<?php echo $option; ?>][label_billing]" class="regular-text code" id="ext_cst_label_billing_extra" value="<?php echo $value['label_billing'] ?>"/>
														</td>
													</tr>
													<tr>
														<th scope="row"><label><?php _e( 'Type'); ?><label></th>
														<td>
															<select name="ext_cst_extra[<?php echo $option; ?>][amount_type]" id="ext_cst_amount_type_extra" onchange="showHideTaxShipping(this,<?php echo $option; ?>)">
																<option value="fixed" <?php if($value['amount_type']=='fixed'){ echo 'selected=selected';} ?>>Fixed</option>
																<option value="percent" <?php if($value['amount_type']=='percent'){ echo 'selected=selected';} ?>>Percentage</option>
															</select>
														</td>
													</tr>
													<tr id="incTax-<?php echo $option; ?>" class="incTax">
														<th scope="row"><label><?php _e( 'Calculate fees including TAX'); ?></label></th>
														<td>
															<select>
																<option value="yes">Yes</option>
																<option value="no">No</option>
															</select>
															<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
														</td>
													</tr>
													<tr id="incShipCosts-<?php echo $option; ?>" class="incShipCosts">
														<th scope="row"><label><?php _e( 'Calculate fees including Shipping Costs'); ?></label></th>
														<td>
															<select>
																<option value="yes">Yes</option>
																<option value="no">No</option>
															</select>
															<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
														</td>
													</tr>
													<tr>
														<th scope="row"><label><?php _e( 'Amount'); ?></labe></td>
														<td>
															<input type="number" step="any" name="ext_cst_extra[<?php echo $option; ?>][amount]" class="fees_amount regular-text code" id="ext_cst_amount_extra<?php echo $option; ?>" value="<?php echo $value['amount'] ?>"/>
														</td>
													</tr>
													<tr>
														<th scope="row"><label><?php _e( 'Apply Condition'); ?></label></th>
														<td>
															<select name="ext_cst_extra[<?php echo $option; ?>][apply_type]" id="ext_cst_apply_type<?php echo $option; ?>">
																<option>One Time Only</option>
																<option>Multiplied By Product Quantity</option>
															</select>
															<p>If you want to charge additional fees for each product quantity into cart then choose <b>Multiplied By Product Quantity.</b> otherwise choose One Time Only.</p>
															<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
														</td>
													</tr>
													<tr>
														<th scope="row"><label><?php _e( 'Auto-checked/Auto-applied the fees'); ?><label></th>
														<td>
															<select>
																<option value="enable">Enable</option>
																<option value="disable">Disable</option>
															</select>
															<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
														</td>
													</tr>
													<tr>
														<th scope="row"><label><?php _e( 'Condition'); ?><label></th>
														<td>
															<select name="ext_cst_extra[<?php echo $option; ?>][apply_cndtn]" id="ext_cst_apply_cndtn_extra<?php echo $option; ?>" class="ext_cst_cndtn_dropdown" onchange="show_hide_cndtn_extra(<?php echo $option; ?>)">
																<option value="all">All</option>
																<option value="cart_total_amount">Cart Total Amount</option>
																<option value="cart_no_product">Number of Product on Cart</option>
																<option value="selected_product">Selected Product</option>
																<option value="selected_category">Selected Category</option>
																<option value="selected_pr_type">Selected Product Type</option>
															</select>
															
														</td>
													</tr>
													<tr id="cart_total_amount<?php echo $option; ?>" class="cndtn_mode_extra<?php echo $option; ?>">
														<th scope="row"><label><?php _e( 'Cart Amount'); ?></labe></td>
														<td>
															<label>Minimum</label>
															<input type="number" class="small-text" />

															<label>Maximum</label>
															<input type="number" class="small-text" />
															<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
														</td>
													</tr>
													<tr id="cart_no_product<?php echo $option; ?>" class="cndtn_mode_extra<?php echo $option; ?>">
														<th scope="row"><label><?php _e( 'No. Of Product on Cart'); ?></labe></td>
														<td>
															<label>Minimum</label>
															<input type="number" class="small-text" />

															<label>Maximum</label>
															<input type="number" class="small-text" value=""/>
															<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
														</td>
													</tr>
													<tr id="selected_product<?php echo $option; ?>" class="cndtn_mode_extra<?php echo $option; ?>">
														<th scope="row"><label><?php _e( 'Selected Product'); ?><label></th>
														<td>
															<select name="ext_cst_extra[<?php echo $option; ?>][selected_product_id_extra][]" multiple="multiple" class="wps_wafc_multiselect">
																<?php
																	$args = array(
																		'post_type' => 'product',
																		'posts_per_page' => -1
																		);
																	$loop = new WP_Query( $args );
																	if ( $loop->have_posts() ) {
																		while ( $loop->have_posts() ) : $loop->the_post();?>
																			<option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option>
																			<?php
																		endwhile;
																	} else {
																		echo '<option>No products found</option>';
																	}
																	wp_reset_postdata();
																?>
															</select>
															<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
														</td>
													</tr>
													<tr id="selected_category<?php echo $option; ?>" class="cndtn_mode_extra<?php echo $option; ?>">
														<th scope="row"><label><?php _e( 'Selected Product Category'); ?><label></th>
														<td>
															<select name="ext_cst_extra[<?php echo $option; ?>][selected_cat_id_extra][]" multiple="multiple" class="wps_wafc_multiselect">
																<?php
																	$args = array(
																		'taxonomy' => 'product_cat',
																		'orderby' => 'name',
																		'order' => 'ASC',
																		'hide_empty' => false
																	);
																	$categories = get_categories( $args );
																	if ( $categories ) {
																		foreach( $categories as $category ) :?>
																			<option value="<?php echo $category->term_id; ?>"><?php echo $category->name.' ('.$category->count.')'; ?></option>
																			<?php
																		endforeach;
																	} else {
																		echo '<option>No category found</option>';
																	}
																?>
															</select>
															<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
														</td>
													</tr>
													<tr id="selected_pr_type<?php echo $option; ?>" class="cndtn_mode_extra<?php echo $option; ?>">
														<th scope="row"><label><?php _e( 'Selected Product Type'); ?><label></th>
														<td>
															<select name="ext_cst_extra[<?php echo $option; ?>][selected_pr_type_extra][]" multiple="multiple" class="wps_wafc_multiselect">
																<?php
																	$pr_types = array('simple','grouped','variable','external_affiliate');
																		foreach ( $pr_types as $types ) :?>
																			<option value="<?php echo $types; ?>"><?php echo str_replace("_"," / ",ucwords($types)); ?></option>
																			<?php
																		endforeach;
																?>
															</select>
															<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
														</td>
													</tr>
													<tr>
														<th scope="row"><label><?php _e( 'Required Field'); ?></labe></td>
														<td>
															<select>
																<option value="no">No</option>
																<option value="yes">Yes</option>
															</select>
															<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
														</td>
													</tr>
													<tr>
														<th scope="row"><label><?php _e( 'Hide Option At Checkout'); ?></label></th>
														<td>
															<select>
																<option value="no">No</option>
																<option value="yes">Yes</option>
															</select>
															<p class="error">Available with premium version.<a target="_blank;" href="https://www.wpsuperiors.com/woocommerce-additional-fees-on-checkout/"><br/>Buy Now</a>.</p>
														</td>
													</tr>
												</tbody>
								            </table>
								    	</div>
								    	<script type="text/javascript">
								    		show_hide_cndtn_extra(<?php echo $option; ?>);
								    		function show_hide_cndtn_extra(s_id){
												jQuery(".cndtn_mode_extra"+s_id).hide();
												var id = jQuery('#ext_cst_apply_cndtn_extra'+s_id).val();
												jQuery("#"+id+s_id).show();
											}
											var type_value = jQuery("select[name='ext_cst_extra[<?php echo $option; ?>][amount_type]']").val();
											if( type_value == 'percent' ){
												jQuery('#incTax-<?php echo $option; ?>').show();
												jQuery('#incShipCosts-<?php echo $option; ?>').show();	
											}else{
												jQuery('#incTax-<?php echo $option; ?>').hide();
												jQuery('#incShipCosts-<?php echo $option; ?>').hide();	
											}

								    	</script>
			            				<?php
			            				$count ++;
			            			}
			            		}

			            	?>
			            	<input type="hidden" id="current_number_fees" value="<?php echo $option; ?>" />
			            </div>
			            <div class="wafoc-bottom-line" style="width: 100%; height: 50px;">
			            	<div class="wafoc-bottom-line-button" style="float: left;">
			            		<?php 
									submit_button();
								?>
			            	</div>
			            	<div class="wafoc-bottom-line-add-new" style="float: right; margin-top: 30px; margin-right: 20px;">
			            		<a href="javascript:void(0);" class="button button-secondary" style="font-family: raleway;">Add More New Fees</a>
			            	</div>
			            </div>	
					</form>
				</div>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					var value = jQuery("#ext_cst_amount_type").val();
					if( value == 'percent' ){
						jQuery('#incTax-1').show();
						jQuery('#incShipCosts-1').show();	
					}else{
						jQuery('#incTax-1').hide();
						jQuery('#incShipCosts-1').hide();	
					}
				});
				function showHideTaxShipping(select,id){
					var value = select.value;
					if( value == 'percent' ){
						jQuery('#incTax-'+id).show();
						jQuery('#incShipCosts-'+id).show();	
					}else{
						jQuery('#incTax-'+id).hide();
						jQuery('#incShipCosts-'+id).hide();	
					}
				}
			</script>

			<p style="width:100%; float:left; display:inline-block; margin-top:30px; font-size:12px;">After activate this plugin checkout page design looks bad? Send a scrrenshot and checkout URL at <a style="text-decoration:none;" href="mailto:support@wpsuperiors.com">support@wpsuperiors.com</a>, it will be solved for sure.
		    <p style="margin-top:30px; font-size:12px;">Still Confused? Need our help? Feel free to write on us <a style="text-decoration:none;" href="mailto:support@wpsuperiors.com">support@wpsuperiors.com</a> OR visit <a style="text-decoration:none;" href="http://www.wpsuperiors.com/contact-us/" target="_blank">Contact Us</a></p>
		    <p>Like this plugin? Leave  
	  			<span style="font-size:200%;color:yellow;">&starf;</span>
	  			<span style="font-size:200%;color:yellow;">&starf;</span>
	  			<span style="font-size:200%;color:yellow;">&starf;</span>
	  			<span style="font-size:200%;color:yellow;">&starf;</span>
	  			<span style="font-size:200%;color:yellow;">&starf;</span> rating at <a href="https://wordpress.org/support/plugin/woo-additional-fees-on-checkout-wordpress/reviews/#new-post" target="_blank;">WordPress</a>
	  		</p>
	  		<div class="wps-buy-notice"> 
				<p><span>Are you looking for WooCommerce Checkout Additional Fess based on <strong style="letter-spacing: 1px;"> PaymentGateway,Shipping,ProductType(Simple,Variable,Subscription),Category</strong> ? </span><a class="gradient-button gradient-button-4" href="https://www.wpsuperiors.com/extra-amount-on-checkout-premium/" target="_click;">Click Here</a></p>
			</div>
		<?php
	}

}new WPS_EXT_CST_Admin_Settings();

?>