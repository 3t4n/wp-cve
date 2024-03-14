<?php
/* *
* Author: zetamatic
* @package https://zetamatic.com/
*/

class WCAF_BillingFieldSetting {
  /**
   * __construct
   *
   * @return void
   */
    public function __construct() {
		// add plugin setting
		add_action( 'admin_init', array( $this, 'wcaf_billing_field_register_settings' ) );

		add_action( 'wcaf_settings_tab_heading', array($this,'wcaf_setting_billing_field_heading'));

		add_action( 'wcaf_settings_tab_content', array($this,'wcaf_setting_billing_field_content'),1);

	}

	public function wcaf_billing_field_register_settings() {
        register_setting( 'wcaf-billing-field-settings-group', 'wc_af_enable_for_billing' );
		register_setting( 'wcaf-billing-field-settings-group', 'wc_af_label_for_bill_field' );
        register_setting( 'wcaf-billing-field-settings-group', 'wc_af_show_below_for_bill' );
 
        register_setting( 'wcaf-billing-field-settings-group', 'wc_af_enable_phone_number_for_bill' );
        register_setting( 'wcaf-billing-field-settings-group', 'wc_af_enable_company_name_for_bill' );

        
	}
	
	public function wcaf_setting_billing_field_heading(){
        global $active_tab;
        ?>
        	<a href="?page=wc-af-options&tab=wcaf-billing-field-setting" class="nav-tab <?php echo $active_tab == 'wcaf-billing-field-setting' ? 'nav-tab-active' : ''; ?>">
				<?php echo __( 'Billing Field', 'checkout_address_autofill_for_woocommerce' ); ?>
			</a>

        <?php

    }

    public function wcaf_setting_billing_field_content(){
        global $active_tab;
		?>
		<?php if($active_tab == 'wcaf-billing-field-setting'): ?>
            <?php settings_fields( 'wcaf-billing-field-settings-group' ); ?>
		    <?php do_settings_sections( 'wcaf-billing-field-settings-group' ); ?>
			<table class="form-table">

                <tr valign="top">
                    <th colspan="2" scope="row">
                        <h2 style="margin-bottom: 0;">
                            <?php echo __( 'Billing Autocomplete Fields', 'checkout_address_autofill_for_woocommerce' ); ?>
                        </h2>
                        <hr>
                    </th>
                </tr>

                <tr valign="top">

                    <th scope="row">
                        <?php echo __( 'Enable for Billing', 'checkout_address_autofill_for_woocommerce' ); ?>
                    </th>

                    <td>
                        <input type="checkbox" name="wc_af_enable_for_billing" value="1" <?php checked( 1, get_option( 'wc_af_enable_for_billing' ), true ); ?>>
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
                        <input type="text" name="wc_af_label_for_bill_field" value="<?php echo ( get_option( ' wc_af_label_for_bill_field' ) ); ?>">

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
                        <input type="text" name="wc_af_placeholder_for_bill_field" value="" disabled>
                        
                        <p class="description" id="label_for_field_description">
                            <?php echo __( 'Enter the placeholder of autocomplete field you want to show', 'checkout_address_autofill_for_woocommerce' ); ?>
                        </p>
                    </td>
                    
                </tr>

                <tr valign="top"  class = "pro-feature">
                    
                    <th scope="row">
                        <?php echo __( 'Enable for Autofill field required for billing', 'checkout_address_autofill_for_woocommerce' ); ?>
                    </th>
                    
                    <td>
                        <input type="checkbox" name="wc_af_enable_autofill_required_for_billing" value="1" <?php checked( 1, get_option( 'wc_af_enable_autofill_required_for_billing' ), true ); ?> disabled>

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
                        <input type="checkbox" name="wc_af_show_below_for_bill" value="1" <?php checked( 1, get_option( 'wc_af_show_below_for_bill' ), true ); ?>>
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
                        
                        $wc_af_saved_billing_autofill_fields_priority = get_option('wc_af_show_select_field_above_for_bill');

                        // print_r($wc_af_saved_fields);die;
                        $billing_field_priority =  get_option('wc_af_checkout_billing_fields_priority');
                        ?>
                        <select name="wc_af_show_select_field_above_for_bill" id="wc_af_show_select_field_above_for_bill" disabled>
                            <?php
                                foreach($billing_field_priority as $billing_field_priority_number => $billing_field_name){
                                    if($wc_af_saved_billing_autofill_fields_priority == $billing_field_priority_number){
                                        ?>
                                            <option selected value="<?php echo $wc_af_saved_billing_autofill_fields_priority; ?>"><?php echo $billing_field_name; ?></option>
                                        <?php
            
                                    }else{
                                        ?>
                                            <option value="<?php echo $billing_field_priority_number; ?>"><?php echo $billing_field_name; ?></option>
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
			        <td>
                        <hr>
                    </td>
		        </tr>

                <!-- Integration of autocomplete field with existing field -->
                <tr valign="top" class = "pro-feature">

                    <th scope="row">
                        <?php echo __( 'Enable Integration of autocomplete field with existing billing fields', 'checkout_address_autofill_for_woocommerce' ); ?>
                    </th>

                    <td>
                        <input type="checkbox" name="wc_af_enable_integration_of_autocomplete_field_for_billing" value="1" <?php checked( 1, get_option( 'wc_af_enable_integration_of_autocomplete_field_for_billing' ), true); ?>>

                        <p class="description" id="wc_af_enable_integration_of_autocomplete_field_for_billing">
                            <?php echo __( 'This option will enable the integration of google autocomplete field with the existing billing fields. <br> <b> Note: </b> Please uncheck the <strong>Enable autocomplete </strong> for billing option.', 'checkout_address_autofill_for_woocommerce' ); ?>
                        </p>
                    </td>

                </tr>

                <tr valign="top" class = "pro-feature">

                    <th scope="row">
                        <?php echo __( 'Billing field ID', 'checkout_address_autofill_for_woocommerce' ); ?>
                    </th>

                    <td>
                        <input type="text" name="wc_af_billing_field_id_for_integration_with_autocomplete_field" value="<?php echo ( get_option( ' wc_af_billing_field_id_for_integration_with_autocomplete_field' ) ); ?>">
                    
                        <p class="description" id="wc_af_billing_field_id_for_integration_with_autocomplete_field_description">
                            <?php echo __( 'Enter the billing field id to integrate with autocomplete field', 'checkout_address_autofill_for_woocommerce' ); ?>
                        </p>
                    </td>

                </tr>

                <tr>
                    <td>
                        <hr>
                    </td>
                </tr>

                <!-- Current Location For Checkout Billing Field -->
                <tr valign="top" class ="pro-feature">
                    <th scope="row">
                        <?php echo __( 'Enable Use Current Location', 'checkout_address_autofill_for_woocommerce' ); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wc_af_enable_use_current_location_for_billing" value="1" <?php checked( 1, get_option( 'wc_af_enable_use_current_location_for_billing' ), true); ?> disabled>
                        <p class="description" id="wc_af_enable_use_current_location_for_billing">
                            <?php echo __( 'This option simply shows an option where user can use their current location easily', 'checkout_address_autofill_for_woocommerce' ); ?>
                        </p>
                    </td>
		        </tr>

                <tr valign="top" class = "pro-feature">
                    <th scope="row">
                        <?php echo __( 'Current Location Icon Position', 'checkout_address_autofill_for_woocommerce' ); ?>
                    </th>
                    <td>
                        <?php
                            $wc_af_saved_billing_autofill_current_location_icon_priority = get_option('wc_af_show_select_icon_above_for_bill');

                            $billing_field_priority =  get_option('wc_af_checkout_billing_fields_priority');

                        ?>
                        <select name="wc_af_show_select_icon_above_for_bill" id="wc_af_show_select_icon_above_for_bill" disabled>
                            <?php
                                foreach($billing_field_priority as $billing_field_priority_number => $billing_field_name){
                                    if($wc_af_saved_billing_autofill_current_location_icon_priority == $billing_field_priority_number){
                                        ?>
                                            <option selected value="<?php echo $wc_af_saved_billing_autofill_current_location_icon_priority; ?>"><?php echo $billing_field_name; ?></option>
                                        <?php
            
                                    }else{
                                        ?>
                                            <option value="<?php echo $billing_field_priority_number; ?>"><?php echo $billing_field_name; ?></option>
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

                <tr valign="top" class = "pro-feature">

                    <th scope="row">
                        <?php echo __('Enable Location Picker for Billing', 'checkout_address_autofill_for_woocommerce_pro'); ?>
                    </th>

                    <td>
                        <input type="checkbox" name="wc_af_enable_picker_for_billing" value="1" <?php checked(1, get_option('wc_af_enable_picker_for_billing'), true); ?> disabled>
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
                        <img class="image_picker_logo_billing" src="<?php echo get_option('wc_af_location_picker_image_billing'); ?>" height="<?php echo get_option('wc_af_picker_image_height_billing'); ?>" width="<?php echo get_option('wc_af_image_picker_width_billing'); ?>"/>
                        <input class="image_picker_logo_url_billing" type="hidden" name="wc_af_location_picker_image_billing" value="<?php echo get_option('wc_af_location_picker_image_billing'); ?>" disabled>
                        <input type="button" class="image_picker_logo_upload_billing button" value="Upload" style="vertical-align: top;" disabled>
                        </p>
                    </td>

                </tr>

                <tr valign="top" class ="pro-feature">

                    <th scope="row">
                        <?php echo __( 'Location Picker Icon Position', 'checkout_address_autofill_for_woocommerce' ); ?>
                    </th>

                    <td>
                        <?php
                        $wc_af_saved_billing_autofill_location_picker_icon_priority = get_option('wc_af_show_select_location_picker_icon_above_for_bill');
                        
                        $billing_field_priority =  get_option('wc_af_checkout_billing_fields_priority');
                        
                        ?>
                        <select name="wc_af_show_select_location_picker_icon_above_for_bill" id="wc_af_show_select_location_picker_icon_above_for_bill" disabled>
                        <?php
                        foreach($billing_field_priority as $billing_field_priority_number => $billing_field_name){
                            if($wc_af_saved_billing_autofill_location_picker_icon_priority == $billing_field_priority_number){
                            ?>
                            <option selected value="<?php echo $wc_af_saved_billing_autofill_location_picker_icon_priority; ?>"><?php echo $billing_field_name; ?></option>
                            <?php
                            
                            }else{
                            ?>
                            <option value="<?php echo $billing_field_priority_number; ?>"><?php echo $billing_field_name; ?></option>
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

                <!-- Set height and width of image -->
                <tr valign="top" class ="pro-feature">

                    <th scope="row">
                        <?php echo __('Location Picker Image Size In px', 'checkout_address_autofill_for_woocommerce_pro'); ?>
                    </th>

                    <td>
                        <div class="image-dimension-wrapper">
                            <label><?php echo __('Height', 'checkout_address_autofill_for_woocommerce_pro'); ?> : </label>
                            <input type="number" class="image-dimension" name="wc_af_picker_image_height_billing" value="<?php echo get_option('wc_af_picker_image_height_billing'); ?>" disabled>
                        </div>

                        <div class="image-dimension-wrapper">
                            <label><?php echo __('Width', 'checkout_address_autofill_for_woocommerce_pro'); ?> : </label>
                            <input type="number" name="wc_af_image_picker_width_billing"  value="<?php echo get_option('wc_af_image_picker_width_billing'); ?>" disabled>
                        </div>
                    </td>
                </tr>

                <!-- On hover properties -->
                <tr valign="top" class ="pro-feature">

                    <th scope="row">
                        <?php echo __('Enable Location Picker Image Hover Effect', 'checkout_address_autofill_for_woocommerce_pro'); ?>
                    </th>

                    <td>
                        <input type="checkbox" name="wc_af_picker_enable_hover_billing" value="1" <?php checked(1, get_option('wc_af_picker_enable_hover_billing'), true); ?> disabled>
                    </td>
                </tr>

                <tr>
                    <td><hr></td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <?php echo __( 'Allow Phone Number', 'checkout_address_autofill_for_woocommerce' ); ?>
                    </th>

                    <td>
                        <input type="checkbox" name="wc_af_enable_phone_number_for_bill" value="1" <?php checked( 1, get_option( 'wc_af_enable_phone_number_for_bill' ), true ); ?>>
                        <p class="description">
                            <?php echo __( 'Check to autofill the Phone number field.', 'checkout_address_autofill_for_woocommerce' ); ?>
                        </p>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php echo __( 'Allow Company Name', 'checkout_address_autofill_for_woocommerce' ); ?>
                    </th>

                    <td>
                        <input type="checkbox" name="wc_af_enable_company_name_for_bill" value="1" <?php checked( 1, get_option( 'wc_af_enable_company_name_for_bill' ), true ); ?>>
                        <p class="description"><?php echo __( 'Check to autofill the Company Name field.', 'checkout_address_autofill_for_woocommerce' ); ?></p>
                    </td>
                </tr>

                <!-- enable geolocation latitude longitude -->
                <tr valign="top" class = "pro-feature">

                    <th scope="row">
                        <?php echo __( 'Save Billing Geolocation (Latitude, Longitude)', 'checkout_address_autofill_for_woocommerce_pro' ); ?>
                    </th>

                    <td>
                        <input type="checkbox" name="wc_af_enable_geolocation_for_bill" value="1" <?php checked( 1, get_option( 'wc_af_enable_geolocation_for_bill' ), true ); ?> disabled>
                        <p class="description"><?php echo __( 'Save billing geolocation and display on admin Edit Order.', 'checkout_address_autofill_for_woocommerce_pro' ); ?></p>
                    </td>
                </tr>

                <!-- dynamic mapping for shipping fields starts here  -->
				<tr valign="top" >
					<th colspan="2" scope="row"><h2 style="margin-bottom: 0;">
						<?php echo __('Google Autofill Checkout Billing Field Mapping', 'checkout_address_autofill_for_woocommerce_pro'); ?></h2>
						<hr>
					</th>
				</tr>
				
				<!-- Mapping table -->
				<tr valign="top">
					<table class="wp-list-table widefat fixed striped posts wcaf-google-custom-shipping-checkout-block-fields-mapping">
						<thead>
							<tr>
								<th>
									<?php echo esc_html__('Google Billing Autofill Field', 'checkout_address_autofill_for_woocommerce_pro'); ?>
									<br>
									<p class="description" style="display: inline;"><?php echo __('Do not leave field empty if corresponding checkout field ID is provided', 'checkout_address_autofill_for_woocommerce_pro'); ?></p>
									
								</th>
								
									<th>
									<?php echo esc_html__('Billing Checkout Field ID', 'checkout_address_autofill_for_woocommerce_pro'); ?>
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