<?php
function rtwwdpdl_get_rule_settings(){
	$rtwwdpdl_select_rule = 'rtwwdpdl_cart';
	$rtwwdpdl_check_var = 'Quantity';

	$rtwwdpdl_rule = array(
		'section_title' => array(
			'name'     => '',
			'type'     => 'title',
			'desc'     => '',
			'id'       => 'rtwwocp_wc_settings_tab_section_title'
		),
		array(
			'id' 				=> 'rtwwdpdl_offer_name',
			'name'    => esc_html__( 'Offer Title', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'placeholder' 		=> esc_html__('Enter title for this offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'),
			'desc' 		=> esc_html__('This title will be displayed in the Offer listings. We suggest a brief detail about this discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'),
			'type' 				=> 'text',
			'desc_tip' 			=> true,
			'value' 			=> '',
			'custom_attributes' => array( 'disabled' => 'disabled' ),
		),

		array(
			'id' 				=> 'rtwwdpdl_check_for',
			'name' 			=> esc_html__('Check for', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'),
			'type'		=> 'select',
			'options' 			=> array(
				'rtwwdpdl_quantity' 	=> esc_html__( 'Quantity', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'),
				'rtwwdpdl_price' => esc_html__( 'Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'),
				'rtwwdpdl_weight' 	=> esc_html__( 'Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'),
			),
			'value' 			=> $rtwwdpdl_check_var,
			'desc' 		=> sprintf( '%s', esc_html__( 'Rule can be applied for either on Price/ Quantity/ Weight', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) ),
			'desc_tip' => true,
			'custom_attributes' => array( 'disabled' => 'disabled' ),
		),

		array(
			'id' 				=> 'rtwwdpdl_min',
			'name' 			=> esc_html__( 'Minimum '.$rtwwdpdl_check_var, 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'desc' 		=> esc_html__( 'Minimum value to check', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'type' 				=> 'number',
			'desc_tip' 			=> true,
			'value' 			=> '1',
			'custom_attributes' => array( 'disabled' => 'disabled' ),
		),

		array(
			'id' 				=> 'rtwwdpdl_max',
			'name' 			=> esc_html__( 'Maximum '.$rtwwdpdl_check_var, 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'desc' 		=> esc_html__( 'Maximum value to check, set it empty for no limit', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'type' 				=> 'number',
			'desc_tip' 			=> true,
			'value' 			=> '',
			'custom_attributes' => array( 'disabled' => 'disabled' ),
		),

		array(
			'id' 				=> 'rtwwdpdl_discount_type',
			'name' 			=> esc_html__( 'Discount type', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'type'		=> 'select',
			'options' 			=> array(
				'rtwwdpdl_discount_percentage' 	=> 'Percent Discount',
				'rtwwdpdl_flat_discount_amount' => 'Flat Discount',
				'rtwwdpdl_fixed_price' 			=> 'Fixed Price'
			),
			'value' 			=> 'percent_discount',
			'desc' 		=> esc_html__( 'Three types of discounts can be applied – "Percentage Discount/Flat Discount/Fixed Price"', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'desc_tip' 			=> true,
			'custom_attributes' => array( 'disabled' => 'disabled' ),
		),

		array(
			'id' 				=> 'rtwwdpdl_discount_value',
			'name' 			=> esc_html__( 'Percent Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'desc' 		=> esc_html__( 'If you select "Percentage Discount", the given percentage (value) would be discounted on each unit of the product in the cart. If you select "Flat Discount", the given amount (value) would be discounted at subtotal level in the cart. If you select "Fixed Price", the original price of the product is replaced by the given fixed price (value).', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'type' 				=> 'number',
			'desc_tip' 			=> true,
			'value' 			=> '',
			'custom_attributes' => array( 'disabled' => 'disabled' ),
		),
		'section_end' => array(
			'type' => 'sectionend',
			'id' => 'wc_settings_tab_demo_section_end'
		)

	);
	return $rtwwdpdl_rule;
}
    
function rtwwdpdl_get_restrict_settings(){
	
	$rtwwdpdl_role_all 	= esc_html__( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
	$rtwwdpdl_roles 	= array( 'all' => $rtwwdpdl_role_all );

	$rtwwdpdl_restrict = array(
		'section_title' => array(
			'name'     => '',
			'type'     => 'title',
			'desc'     => '',
			'id'       => 'rtwwocp_wc_settings_tab_section_title'
		),
		array(
			'id' 				=> 'rtwwdpdl_max_discount',
			'name' 			=> esc_html__( 'Maximum Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'desc' 		=> esc_html__( 'This is used to set a threshold limit on the discount. Set it to 0 if you don\'t want to set a limit.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'type' 				=> 'number',
			'desc_tip' 			=> true,
			'value' 			=> 0,
			'custom_attributes' => array( 'disabled' => 'disabled' ),
		),
		array(
			'id' 				=> 'rtwwdpdl_select_roles[]',
			'class' 		=> 'form-field rtwwdpdl_select_roles_field',
			'name' 			=> esc_html__( 'Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'desc' 		=> esc_html__( 'Minimum number of orders done by a customer to be eligible for this discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'type' 				=> 'multiselect',
			'options'			=> $rtwwdpdl_roles,
			'desc_tip' 			=> true,
			'value' 			=> 0,
			'custom_attributes' => array( 'disabled' => 'disabled' ),
		),
		array(
			'id' 				=> 'rtwwdpdl_min_orders',
			'name' 			=> esc_html__( 'Minimum no. of Previous Orders', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'desc' 		=> esc_html__( 'Minimum number of orders done by a customer to be eligible for this discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'type' 				=> 'number',
			'desc_tip' 			=> true,
			'value' 			=> 0,
			'custom_attributes' => array( 'disabled' => 'disabled' ),
		),
		array(
			'id' 				=> 'rtwwdpdl_min_spend',
			'name' 			=> esc_html__( 'Minimum amount spend on Previous Orders', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'desc' 		=> esc_html__( 'Minimum amount need to be spent by a customer on previous orders to be eligible for this discount.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'type' 				=> 'number',
			'desc_tip' 			=> true,
			'value' 			=> 0,
			'custom_attributes' => array( 'disabled' => 'disabled' ),
		),
		array(
			'id' 				=> 'rtwwdpdl_exclude_sale',
			'name' 			=> esc_html__( 'Exclude sale items', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'desc' 		=> esc_html__( 'This will exclude the discount from the products that are on sale', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ),
			'type' 				=> 'checkbox',
			'desc_tip' 			=> true,
			'value' 			=> 'yes',
			'custom_attributes' => array( 'disabled' => 'disabled' ),
		),
		'section_end' => array(
			'type' => 'sectionend',
			'id' => 'wc_settings_tab_demo_section_end'
		)
	);
	return $rtwwdpdl_restrict;
}
?>
<div class="rtwwdpdl_right">
	<span class="rtwwdpdl_pro_text"><?php esc_html_e('This feature is available in Pro version','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
	<a target="_blank" href="<?php echo esc_url('https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts-with-ai/24165502'); ?>"><?php esc_html_e('Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
	<div class="rtwwdpdl_add_buttons">
		<input class="rtw-button rtwwdpdl_single_prod_rule" id="rtw_var" type="button" name="rtwwdpdl_single_prod_rule" value="<?php esc_attr_e( 'Add New Rule (Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
	</div>
	<div class="rtwwdpdl_add_single_rule rtwwdpdl_form_layout_wrapper">
		<form action="" method="POST" accept-charset="utf-8">
			<?php wp_nonce_field( 'rtwwdpd_variation', 'rtwwdpd_variation_field' ); ?>
			<div id="woocommerce-product-data" class="postbox ">
				<div class="inside">
					<div class="panel-wrap product_data rtwwdpdl_pro_text_overlay">
						<ul class="product_data_tabs wc-tabs">
							<li class="rtwwdpdl_rule_tab active">
								<a class="rtwwdpdl_link" id="rtwproduct_rule">
									<span><?php esc_html_e('Rule','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li class="rtwwdpdl_restriction_tab">
								<a class="rtwwdpdl_link" id="rtwproduct_restrict">
									<span><?php esc_html_e('Restrictions','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li class="rtwwdpdl_time_tab">
								<a class="rtwwdpdl_link" id="rtwproduct_validity">
									<span><?php esc_html_e('Validity','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
						</ul>

						<div class="panel woocommerce_options_panel">
							<div class="options_group rtwwdpdl_active" id="rtwwdpdl_rule_tab">
								<input type="hidden" id="rtw_var_n" name="rtw_var_n" value="save">
								<?php woocommerce_admin_fields( rtwwdpdl_get_rule_settings()); ?>
							</div>
							<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_restriction_tab">
								<?php woocommerce_admin_fields( rtwwdpdl_get_restrict_settings()); ?>
							</div>
							<div class="options_group rtwwdpdl_inactive" id="rtwwdpdl_time_tab">
								<table class="rtwwdpdl_table_edit">
									<tr>
										<td>
											<label class="rtwwdpdl_label"><?php esc_html_e('Valid from', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
											</label>
										</td>
										<td>
											<input disabled="disabled" type="date" name="rtwwdpdl_from_date" placeholder="YYYY-MM-DD" required="required" value="" />
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
											<input disabled="disabled" type="date" name="rtwwdpdl_to_date" placeholder="YYYY-MM-DD" required="required" value=""/>
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
				<input class="rtw-button rtwwdpdl_save_var_rule" type="button" name="rtwwdpdl_save_var_rule" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
				<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_rule" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			</div>
		</form>
	</div>
</div>
<div class="rtwwdpdl_prod_table">
	<table class="rtwtable table table-striped table-bordered dt-responsive nowrap" data-value="vari_tbl" cellspacing="0">
		<caption class="rtw_variation_tbl"><?php esc_html_e( 'Note: These rule can be applied form the product variation page.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></caption>
		<thead>
			<tr>
		    	<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Check On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Min', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
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
		<tfoot>
			<tr>
		    	<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Offer Name', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Check On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Min', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Max', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
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