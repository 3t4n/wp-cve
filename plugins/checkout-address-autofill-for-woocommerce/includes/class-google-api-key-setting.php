<?php
/* *
* Author: zetamatic
* @package https://zetamatic.com/
*/

// api key testing
class WCAF_GoogleApiKeySetting {
  /**
   * __construct
   *
   * @return void
   */
    public function __construct() {
		// add plugin setting
		add_action( 'admin_init', array( $this, 'wcaf_google_api_key_register_settings' ) );

		add_action( 'wcaf_settings_tab_heading', array($this,'wcaf_setting_google_api_key_heading'));

		add_action( 'wcaf_settings_tab_content', array($this,'wcaf_setting_google_api_key_content'),1);

	}

	public function wcaf_google_api_key_register_settings() {
		// add settings groups here here ..
		register_setting( 'wc-af-google-api-key-testing-settings-group', 'wc_af_api_key_testing' );
	}
	

	public function wcaf_setting_google_api_key_heading(){
		global $active_tab;
        ?>
        	<a href="?page=wc-af-options&tab=wcaf-google-api-key-setting" class="nav-tab <?php echo $active_tab == 'wcaf-google-api-key-setting' ? 'nav-tab-active' : ''; ?>">
				<?php echo __( 'Verify API Key', 'checkout_address_autofill_for_woocommerce' ); ?>
			</a>

        <?php

    }

    public function wcaf_setting_google_api_key_content(){
        global $active_tab;
		?>
		<?php if($active_tab == 'wcaf-google-api-key-setting'): ?>
			<?php settings_fields( 'wc-af-google-api-key-testing-settings-group' ); ?>
			<?php do_settings_sections( 'wc-af-google-api-key-testing-settings-group' ); ?>
			<table class="form-table">

				<!-- testing testing google api key starts here -->
				<tr valign="top">

					<th colspan="2" scope="row">
						<h2 style="margin-bottom: 0;">
							<?php echo __('Verify Google API Key', 'checkout_address_autofill_for_woocommerce'); ?>
						</h2>
						<hr>
					</th>

				</tr>

				<!-- verify autofill field -->
				<tr valign="top">

					<th scope="row">
						<?php echo __('Verify Autocomplete Field', 'checkout_address_autofill_for_woocommerce'); ?>
					</th>

					<td>
						<input type="text" name="autofill_checkout_field_testing" id = "autofill_checkout_field_testing" autocomplete = "on">
					</td>

				</tr>

				<!-- verify current location -->
				<tr valign="top">

					<th scope="row">
						<?php echo __('Verify Current Location', 'checkout_address_autofill_for_woocommerce'); ?>
					</th>

					<td>

						<img class="locimg" src="<?php echo get_option('wc_af_location_image'); ?>" id = "testing_current_location" style="width: 50px ;height:'50px';display: inline-block;">
						<!-- creating icon for using current location -->
					</td>
					
				</tr>

				<!-- map zoom size -->
				<tr valign="top" class = "pro-feature">
					
					<th scope="row">
						<?php echo __('Location Picker Zoom', 'checkout_address_autofill_for_woocommerce_pro'); ?>
					</th>

					<td>
						<input type="number" name="wc_af_map_zoom_test" value="<?php echo(get_option(' wc_af_map_zoom_test')); ?>" disabled>
						<p class="description" id="label_for_field_description">
							<?php echo __('By default zoom is 15', 'checkout_address_autofill_for_woocommerce_pro'); ?>
						</p>
					</td>
      			</tr>

				<tr valign="top" class ="pro-feature">
					<th scope="row">
						<?php echo __('Verify location picker', 'checkout_address_autofill_for_woocommerce_pro'); ?>
					</th>

					<td>

						<div>
							<img class="locimg" src= "<?php echo get_option('wc_af_location_picker_image_for_testing'); ?>" class="testing_location_picker_btn" id="testing_location_picker_btn" style="width:'50px' height:'50px';display: inline-block;cursor: pointer;" disabled>

							
						</div>
						
					</td>
				</tr>


				<!-- Testing google api key ends here -->
			</table>

	
		<?php endif; ?>

		<?php

    }
	  

}