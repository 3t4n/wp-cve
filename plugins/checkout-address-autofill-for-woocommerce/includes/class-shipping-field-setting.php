<?php
/* *
* Author: zetamatic
* @package https://zetamatic.com/
*/

class WCAF_ShippingFieldSetting {
  /**
   * __construct
   *
   * @return void
   */
    public function __construct() {
		// add plugin setting
		add_action( 'admin_init', array( $this, 'wcaf_shipping_field_register_settings' ) );

		add_action( 'wcaf_settings_tab_heading', array($this,'wcaf_setting_shipping_field_heading'));

		add_action( 'wcaf_settings_tab_content', array($this,'wcaf_setting_shipping_field_content'),1);

	}

	public function wcaf_shipping_field_register_settings() {
        register_setting( 'wcaf-shipping-field-settings-group', 'wc_af_enable_for_shipping' );
		register_setting( 'wcaf-shipping-field-settings-group', 'wc_af_label_for_ship_field' );
        register_setting( 'wcaf-shipping-field-settings-group', 'wc_af_show_below_for_ship' );

		register_setting( 'wcaf-shipping-field-settings-group', 'wc_af_enable_company_name_for_ship' );
		
		
	}
	

	public function wcaf_setting_shipping_field_heading(){
		global $active_tab;
        ?>
        	<a href="?page=wc-af-options&tab=wcaf-shipping-field-setting" class="nav-tab <?php echo $active_tab == 'wcaf-shipping-field-setting' ? 'nav-tab-active' : ''; ?>">
				<?php echo __( 'Shipping Field', 'checkout_address_autofill_for_woocommerce' ); ?>
			</a>

        <?php

    }

    public function wcaf_setting_shipping_field_content(){
        global $active_tab;
		?>
		<?php if($active_tab == 'wcaf-shipping-field-setting'): ?>
			
            <?php settings_fields( 'wcaf-shipping-field-settings-group' ); ?>
		    <?php do_settings_sections( 'wcaf-shipping-field-settings-group' ); ?>

			<table class="form-table">

                <!-- Shipping Fields -->
				<tr valign="top">

					<th colspan="2" scope="row"><h2 style="margin-bottom: 0;">
						<?php echo __( 'Shipping Autocomplete Fields', 'checkout_address_autofill_for_woocommerce' ); ?></h2>
						<hr>
					</th>

				</tr>

				<tr valign="top">

					<th scope="row">
						<?php echo __( 'Enable for Shipping', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<input type="checkbox" name="wc_af_enable_for_shipping" value="1" <?php checked( 1, get_option( 'wc_af_enable_for_shipping' ), true ); ?>>

						<p class="description" id="label_for_field_description">
							<?php echo __( 'Enable autocomplete.', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>
					</td>

				</tr>

				<tr valign="top">

					<th scope="row">
						<?php echo __( 'Auto Complete Field Label', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<input type="text" name="wc_af_label_for_ship_field" value="<?php echo ( get_option( 'wc_af_label_for_ship_field' ) ); ?>">

						<p class="description" id="label_for_field_description">
							<?php echo __( 'Enter the label of autocomplete field you want to show', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>
					</td>

				</tr>

				<tr valign="top" class = "pro-feature">
      
					<th scope="row">
						<?php echo __( 'Auto Complete Field Placeholder', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>
					
					<td>
						<input type="text" name="wc_af_placeholder_for_ship_field" value="" disabled>
						
						<p class="description" id="label_for_field_description">
						<?php echo __( 'Enter the placeholder of autocomplete field you want to show', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>
					</td>
			
				</tr>

				<tr valign="top" class = "pro-feature">
        
					<th scope="row">
						<?php echo __( 'Enable for Autofill field required for shipping', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>
					
					<td>
						<input type="checkbox" name="wc_af_enable_autofill_required_for_shipping" value="1" <?php checked( 1, get_option( 'wc_af_enable_autofill_required_for_shipping' ), true ); ?> disabled>

						<p class="description" id="label_for_field_description">
							<?php echo __( 'Make autocomplete field required.', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>
					</td>
				
				</tr>

				<tr valign="top">
					<th scope="row">
						<?php echo __( 'Show Autofill Below Address', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<input type="checkbox" name="wc_af_show_below_for_ship" value="1" <?php checked( 1, get_option( 'wc_af_show_below_for_ship' ), true); ?>>
						<p class="description">
							<?php echo __( 'Check to show field below address. By default it is above address field', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>
					</td>
		  		</tr>

				<tr valign="top" class = "pro-feature">

					<th scope="row">
						<?php echo __( 'Google Autocomplete Field Position', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>

						<?php
						$wc_af_saved_shipping_autofill_fields_priority = get_option('wc_af_show_select_field_above_for_ship');
						// print_r($wc_af_saved_fields);die;
						$shipping_field_priority =  get_option('wc_af_checkout_shipping_fields_priority');

						?>

						<select name="wc_af_show_select_field_above_for_ship" id="wc_af_show_select_field_above_for_ship" disabled>
							<?php
								foreach($shipping_field_priority as $shipping_field_priority_number => $shipping_field_name){
									if($wc_af_saved_shipping_autofill_fields_priority == $shipping_field_priority_number){
										?>
											<option selected value="<?php echo $wc_af_saved_shipping_autofill_fields_priority; ?>"><?php echo $shipping_field_name; ?></option>
										<?php
			
									}else{
										?>
											<option value="<?php echo $shipping_field_priority_number; ?>"><?php echo $shipping_field_name; ?></option>
										<?php
									}
			
								}
								
							?>
						</select>
							
						<p class="description">
							<?php echo __( 'Select checkout field to show google autofill field above. By default it is at the starting of checkout billing form.', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>

					</td>

				</tr>

				<tr>
					<td><hr></td>
				</tr>

				        <!-- Integration of autocomplete field with existing field -->
				<tr valign="top" class = "pro-feature">

					<th scope="row">
						<?php echo __( 'Enable Integration of autocomplete field with existing shipping fields', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<input type="checkbox" name="wc_af_enable_integration_of_autocomplete_field_for_shipping" value="1" <?php checked( 1, get_option( 'wc_af_enable_integration_of_autocomplete_field_for_shipping' ), true); ?>>

						<p class="description" id="wc_af_enable_integration_of_autocomplete_field_for_shipping">
							<?php echo __( 'This option will enable the integration of google autocomplete field with the existing shipping fields. <br> <b> Note: </b> Please uncheck the <strong>Enable autocomplete </strong> for shipping option.', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>
					</td>

				</tr>

				<tr valign="top" class = "pro-feature">

					<th scope="row">
						<?php echo __( 'Shipping Field ID', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<input type="text" name="wc_af_shipping_field_id_for_integration_with_autocomplete_field" value="<?php echo ( get_option( ' wc_af_shipping_field_id_for_integration_with_autocomplete_field' ) ); ?>">
						
						<p class="description" id="wc_af_shipping_field_id_for_integration_with_autocomplete_field_description">
							<?php echo __( 'Enter the shipping field id to integrate with autocomplete field', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>
					</td>

				</tr>

				<tr>
					<td>
						<hr>
					</td>
				</tr>

				<!-- Use Current Location  for shipping-->
				<tr valign="top" class = "pro-feature">
					<th scope="row"><?php echo __( 'Enable Use Current Location', 'checkout_address_autofill_for_woocommerce' ); ?></th>
					<td>
					<input type="checkbox" name="wc_af_enable_use_current_location_shipping" value="1" <?php checked( 1, get_option( 'wc_af_enable_use_current_location_shipping' ), true); ?> disabled>
					<p class="description" id="wc_af_enable_use_current_location_shipping"><?php echo __( 'This option simply shows an option where user can use their current location easily', 'checkout_address_autofill_for_woocommerce' ); ?></p>
					</td>
				</tr>

				<tr valign="top" class = "pro-feature">

					<th scope="row">
						<?php echo __( 'Current Location Icon Position', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<?php
							$wc_af_saved_shipping_autofill_current_location_icon_priority = get_option('wc_af_show_select_icon_above_for_ship');

							$shipping_field_priority =  get_option('wc_af_checkout_shipping_fields_priority');
						?>

						<select name="wc_af_show_select_icon_above_for_ship" id="wc_af_show_select_icon_above_for_ship" disabled>
							<?php
								foreach($shipping_field_priority as $shipping_field_priority_number => $shipping_field_name){
									if($wc_af_saved_shipping_autofill_current_location_icon_priority == $shipping_field_priority_number){
										?>
											<option selected value="<?php echo $wc_af_saved_shipping_autofill_current_location_icon_priority; ?>"><?php echo $shipping_field_name; ?></option>
										<?php
			
									}else{
										?>
											<option value="<?php echo $shipping_field_priority_number; ?>"><?php echo $shipping_field_name; ?></option>
										<?php
									}
			
								}
								
							?>
						</select>

						<p class="description"><?php echo __( 'Select checkout field to show google current location icon above. By default it is at the starting of checkout billing form.', 'checkout_address_autofill_for_woocommerce' ); ?></p>
					</td>
				</tr>

				<tr>
					<td><hr></td>
				</tr>

				<!-- Location Picker for Shipping Starts -->
				<tr valign="top" class = "pro-feature">

					<th scope="row">
						<?php echo __('Enable Location Picker for Shipping', 'checkout_address_autofill_for_woocommerce_pro'); ?>
					</th>

					<td>

						<input type="checkbox" name="wc_af_enable_picker_for_shipping" value="1" <?php checked(1, get_option('wc_af_enable_picker_for_shipping'), true); ?> disabled>

						<p class="description">
							<?php echo __('Enable Location Picker.', 'checkout_address_autofill_for_woocommerce_pro'); ?>
						</p>
					</td>

				</tr>


				<!-- Upload image for location -->
				<tr valign="top" class = "pro-feature">
					<th scope="row">
						<?php echo __('Upload Image For Location Picker', 'checkout_address_autofill_for_woocommerce_pro'); ?>
					</th>

					<td>
						<p>
							<img class="image_picker_logo_shipping" src="<?php echo get_option('wc_af_location_picker_image_shipping'); ?>" height="<?php echo get_option('wc_af_picker_image_height_shipping'); ?>" width="<?php echo get_option('wc_af_image_picker_width_shipping'); ?>"/>
							<input class="image_picker_logo_url_shipping" type="hidden" name="wc_af_location_picker_image_shipping" value="<?php echo get_option('wc_af_location_picker_image_shipping'); ?>" disabled>
							<input type="button" class="image_picker_logo_upload_shipping button" value="Upload" style="vertical-align: top;" disabled>
						</p>
					</td>
				</tr>
			
				<tr valign="top" class = "pro-feature">

					<th scope="row">
						<?php echo __( 'Location Picker Icon Position', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>
					
					<td>
						<?php
						$wc_af_saved_shipping_autofill_location_picker_icon_priority = get_option('wc_af_show_select_location_picker_icon_above_for_ship');
						
						$shipping_field_priority =  get_option('wc_af_checkout_shipping_fields_priority');
						
						?>
						<select name="wc_af_show_select_location_picker_icon_above_for_ship" id="wc_af_show_select_location_picker_icon_above_for_ship" disabled>
							<?php
							foreach($shipping_field_priority as $shipping_field_priority_number => $shipping_field_name){
							if($wc_af_saved_shipping_autofill_location_picker_icon_priority == $shipping_field_priority_number){
								?>
								<option selected value="<?php echo $wc_af_saved_shipping_autofill_location_picker_icon_priority; ?>"><?php echo $shipping_field_name; ?></option>
								<?php
								
							}else{
								?>
								<option value="<?php echo $shipping_field_priority_number; ?>"><?php echo $shipping_field_name; ?></option>
								<?php
							}
							
							}
							
							?>
						</select>

						<p class="description">
							<?php echo __( 'Select checkout field to show google autofill field above. By default it is at the starting of checkout shipping form.', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>
					</td>
				</tr>


				<!-- Set height and width of image -->
				<tr valign="top" class = "pro-feature">

					<th scope="row">
						<?php echo __('Location Picker Image Size In px', 'checkout_address_autofill_for_woocommerce_pro'); ?>
					</th>

					<td>
					<div class="image-dimension-wrapper">

						<label>

						<?php echo __('Height', 'checkout_address_autofill_for_woocommerce_pro'); ?> :
						</label>
						
						<input type="number" class="image-dimension" name="wc_af_picker_image_height_shipping" value="<?php echo get_option('wc_af_picker_image_height_shipping'); ?>" disabled>
					</div>
						
					<div class="image-dimension-wrapper">

						<label>
						<?php echo __('Width', 'checkout_address_autofill_for_woocommerce_pro'); ?> : 
						</label>
						
						<input type="number" name="wc_af_image_picker_width_shipping"  value="<?php echo get_option('wc_af_image_picker_width_shipping'); ?>" disabled>
					</div>
					</td>
				</tr>

				<tr>
					<td><hr></td>
				</tr>


				<tr valign="top">
					<th scope="row"><?php echo __( 'Enable Company Name Autofill', 'checkout_address_autofill_for_woocommerce' ); ?></th>
					<td>
					<input type="checkbox" name="wc_af_enable_company_name_for_ship" value="1" <?php checked( 1, get_option( 'wc_af_enable_company_name_for_ship' ), true ); ?>>
					<p class="description"><?php echo __( 'Check to autofill the Company name field.', 'checkout_address_autofill_for_woocommerce' ); ?></p>
					</td>
				</tr>
				<!-- Shipping Fields END -->

					<!-- dynamic mapping for shipping fields starts here  -->
					<tr valign="top" >
					<th colspan="2" scope="row"><h2 style="margin-bottom: 0;">
						<?php echo __('Google Autofill Checkout Shipping Field Mapping', 'checkout_address_autofill_for_woocommerce_pro'); ?></h2>
						<hr>
					</th>
				</tr>
				
				<!-- Mapping table -->
				<tr valign="top">
					<table class="wp-list-table widefat fixed striped posts wcaf-google-custom-shipping-checkout-block-fields-mapping">
						<thead>
							<tr>
								<th>
									<?php echo esc_html__('Google Shipping Autofill Field', 'checkout_address_autofill_for_woocommerce_pro'); ?>
									<br>
									<p class="description" style="display: inline;"><?php echo __('Do not leave field empty if corresponding checkout field ID is provided', 'checkout_address_autofill_for_woocommerce_pro'); ?></p>
									
								</th>
								
									<th>
									<?php echo esc_html__('Shipping Checkout Field ID', 'checkout_address_autofill_for_woocommerce_pro'); ?>
									<p class="description" style="display: inline;"><?php echo __('Must provide unique field ID', 'checkout_address_autofill_for_woocommerce_pro'); ?></p>
								</th>
								
								<th>
									<?php echo esc_html__('Action', 'checkout_address_autofill_for_woocommerce_pro'); ?>
								</th>
							</tr>
						</thead>
						
						<tbody id ="shippingCheckoutBlockGoogleMappingTable">
						
							<!-- for google fields -->
							<tr class= "pro-feature">
								<td>
									<select class="wcaf_shipping_checkout_block_google_field_key" name="wc_af_shipping_checkout_block_google_fields1[]" disabled>
										<option value="">Select Google address</option>
									</select>
								
								</td>
								
								<!-- for custom fields -->
								<td>
									<input type="text" name="wc_af_shipping_checkout_block_custom_fields[]" id = "wc_af_shipping_checkout_block_custom_fields[]" placeholder="Enter your unique checkout field id"  disabled/>
								</td>
								
								<!-- for delete button -->
								<td>
									<button class="button wcaf-remove-mappings" type="button" disabled>&times;</button>
								</td>
							
							</tr>
							
						</tbody>
							
						<tfoot>
							<tr>
								<td>
									<button class="button wcaf-add-shipping-checkout-block-mappings" type="button" disabled><?php echo esc_html__('Add Another Mapping', 'checkout_address_autofill_for_woocommerce_pro'); ?></button>
								</td>
							</tr>
						</tfoot>
					</table>
				</tr>
				<!-- dynamic mapping shipping fields ends here -->

			</table>

	
		<?php endif; ?>

		<?php

    }
	  

}