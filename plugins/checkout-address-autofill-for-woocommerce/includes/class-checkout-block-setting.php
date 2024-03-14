<?php
/* *
* Author: zetamatic
* @package https://zetamatic.com/
*/

class WCAF_CheckoutBlockSetting {
	protected $option_name = 'wcaf_options';

  /**
   * __construct
   *
   * @return void
   */
    public function __construct() {

		// add plugin setting
		add_action( 'admin_init', array( $this, 'wcaf_checkout_block_settings' ) );

		add_action( 'wcaf_settings_tab_heading', array($this,'wcaf_setting_checkout_block_heading'));

		add_action( 'wcaf_settings_tab_content', array($this,'wcaf_setting_checkout_block_content'),1);

		
	}

	public function wcaf_checkout_block_settings() {
		// add settings groups here here ..
		register_setting( 'wcaf-checkout-block-settings-group', 'wc_af_checkout_block_testing' );
	}

	public function wcaf_setting_checkout_block_heading(){
		global $active_tab;
        ?>
        	<a href="?page=wc-af-options&tab=wcaf-checkout-block-setting" class="nav-tab <?php echo $active_tab == 'wcaf-checkout-block-setting' ? 'nav-tab-active' : ''; ?>">
				<?php echo __( 'Checkout Block', 'checkout_address_autofill_for_woocommerce' ); ?>
			</a>

        <?php

    }

    public function wcaf_setting_checkout_block_content(){
        global $active_tab;
		?>
		<?php if($active_tab == 'wcaf-checkout-block-setting'): ?>
            <?php settings_fields( 'wcaf-checkout-block-settings-group' ); ?>
		    <?php do_settings_sections( 'wcaf-checkout-block-settings-group' ); ?>
			<table class="form-table" >

				<tr valign="top">

					<th colspan="2" scope="row">

						<h2 style="margin-bottom: 0;">
							<?php echo __( 'WooCommerce Checkout Block', 'checkout_address_autofill_for_woocommerce' ); ?>
						</h2>

						<p class="description">
							<?php echo __( 'This Option can only used when WooCommerce Block is activated', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>
						<hr>

					</th>

				</tr>

				<!-- Enable use of checkout block -->
				<tr valign="top" class= "pro-feature">

					<th scope="row">
						<?php echo __( 'Enable Use Checkout Block', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<input type="checkbox" name="wc_af_enable_use_checkout_block" value="" disabled>

						<p class="description" id="wc_af_enable_use_checkout_block">
							<?php echo __( 'This option will enable the Google autocomplete for WooCommerce Checkout Block', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>

					</td>
					
				</tr>

				<!-- enable google field -->
				<tr valign="top" class= "pro-feature" >

					<th scope="row">
						<?php echo __( 'Enable Use Google Autofill Field', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<input type="checkbox" name="wc_af_enable_use_checkout_block_autofill_field" value="" disabled>

						<p class="description" id="wc_af_enable_use_checkout_block_autofill_field">
							<?php echo __( 'This option will enable the Google autocomplete field for WooCommerce Checkout Block', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>

					</td>

				</tr>

                <tr valign="top" class= "pro-feature">

                    <th scope="row">
                        <?php echo __( 'Checkout Block Auto Complete Field Label', 'checkout_address_autofill_for_woocommerce' ); ?>
                    </th>

                    <td>
                        <input type="text" name="wc_af_label_for_checkout_block_field" value="" disabled>

                        <p class="description" id="label_for_field_description">
                            <?php echo __( 'Enter the label of autocomplete field you want to show', 'checkout_address_autofill_for_woocommerce' ); ?>
                        </p>
                    </td>

				</tr>
				
				<!-- enable current location -->
				<tr valign="top" class= "pro-feature">

					<th scope="row">
						<?php echo __( 'Enable Use Google Autofill Current Location', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<input type="checkbox" name="wc_af_enable_use_checkout_block_autofill_current_location" value="" disabled>

						<p class="description" id="wc_af_enable_use_checkout_block_autofill_current_location">
							<?php echo __( 'This option will enable the Google autocomplete current location for WooCommerce Checkout Block', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>

					</td>

				</tr>

				<!-- enable current location -->
				<tr valign="top" class= "pro-feature">

					<th scope="row">
						<?php echo __( 'Enable Use Google Autofill Location Picker', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<input type="checkbox" name="wc_af_enable_use_checkout_block_location_picker" value="" disabled>

						<p class="description" id="wc_af_enable_use_checkout_block_location_picker">
							<?php echo __( 'This option will enable the Google autocomplete location picker for WooCommerce Checkout Block', 'checkout_address_autofill_for_woocommerce' ); ?>
						</p>

					</td>

				</tr>


				<!-- dynamic mapping for shipping fields starts here  -->
				<tr valign="top" >
					<th colspan="2" scope="row"><h2 style="margin-bottom: 0;">
						<?php echo __('Google Autofill Checkout Block Shipping Field Mapping', 'checkout_address_autofill_for_woocommerce_pro'); ?></h2>
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
									<?php echo esc_html__('Block Checkout Field ID', 'checkout_address_autofill_for_woocommerce_pro'); ?>
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