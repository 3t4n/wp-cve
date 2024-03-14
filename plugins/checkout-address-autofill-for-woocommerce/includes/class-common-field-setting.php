<?php
/* *
* Author: zetamatic
* @package https://zetamatic.com/
*/

class WCAF_CommonFieldSetting {

	protected $option_name = 'wcaf_options';

  /**
   * __construct
   *
   * @return void
   */
    public function __construct() {
		// add plugin setting
		add_action( 'admin_init', array( $this, 'wcaf_common_field_register_settings' ) );

		add_action( 'wcaf_settings_tab_heading', array($this,'wcaf_setting_common_field_heading'));

		add_action( 'wcaf_settings_tab_content', array($this,'wcaf_setting_common_field_content'),1);

	}

	public function wcaf_common_field_register_settings() {
		
		register_setting( 'wcaf-common-field-settings-group', 'wc_af_enable_use_location' );
        register_setting( 'wcaf-common-field-settings-group', 'wc_af_country' );
		register_setting( 'wcaf-common-field-settings-group', 'wc_af_languages_for_google_autofill' );
        register_setting( 'wcaf-common-field-settings-group', 'wc_af_location_image' );
        register_setting( 'wcaf-common-field-settings-group', 'wc_af_image_height' );
        register_setting( 'wcaf-common-field-settings-group', 'wc_af_image_width' );
        register_setting( 'wcaf-common-field-settings-group', 'wc_af_enable_hover' );
        register_setting( 'wcaf-common-field-settings-group', 'wc_af_prohibit_address_clear' );
        register_setting( 'wcaf-common-field-settings-group', $this->option_name, array( $this, 'validate' ) );

    }
    public function validate( $input ) {
        $valid        = array();
        $output_array = array();
    
        if( isset( $_POST['wc_af_country'] ) ) {
          foreach( $_POST['wc_af_country'] as $key => $post_arr ) {
            array_push( $output_array, sanitize_text_field( $post_arr ) );
          }
        }
    
        $valid['wc_af_country'] = $output_array;
        return $valid;
      }
	
	public function wcaf_setting_common_field_heading(){
		global $active_tab;
        ?>
        	<a href="?page=wc-af-options&tab=wcaf-common-field-setting" class="nav-tab <?php echo $active_tab == 'wcaf-common-field-setting' ? 'nav-tab-active' : ''; ?>">
				<?php echo __( 'Common Field', 'checkout_address_autofill_for_woocommerce' ); ?>
			</a>

        <?php

    }

    public function wcaf_setting_common_field_content(){
        global $active_tab;
		?>
		<?php if($active_tab == 'wcaf-common-field-setting'): ?>
            <?php settings_fields( 'wcaf-common-field-settings-group' ); ?>
		    <?php do_settings_sections( 'wcaf-common-field-settings-group' ); ?>

			<table class="form-table">

				<tr valign="top">
					<th colspan="2" scope="row">
						<h2 style="margin-bottom: 0;">
							<?php echo __( 'Common fields for both Billing and Shipping Address', 'checkout_address_autofill_for_woocommerce' ); ?>
						</h2>
						<hr>
					</th>
				</tr>

				 <!-- Use Current Location -->
				<tr valign="top">
					<th scope="row">
						<?php echo __( 'Enable Use Current Location', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<input type="checkbox" name="wc_af_enable_use_location" value="1" <?php checked( 1, get_option( 'wc_af_enable_use_location' ), true); ?>>

						<p class="description" id="wc_af_enable_use_location"><?php echo __( 'This option simply shows an option where user can use their current location easily', 'checkout_address_autofill_for_woocommerce' ); ?></p>
					</td>
				</tr>

				<!-- location picker map zoom -->
				<tr valign="top" class = "pro-feature">
					
					<th scope="row">
						<?php echo __('Location Picker Zoom', 'checkout_address_autofill_for_woocommerce_pro'); ?>
					</th>

					<td>
						<input type="number" name="wc_af_location_picker_map_zoom" value="<?php echo(get_option(' wc_af_location_picker_map_zoom')); ?>" disabled>
						<p class="description" id="label_for_field_description">
							<?php echo __('By default zoom is 15', 'checkout_address_autofill_for_woocommerce_pro'); ?>
						</p>
					</td>
            	</tr>

				<tr valign="top">

					<th scope="row">
						<?php echo __( 'Show Results From Country', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<select class="wc_gaa_countries" name="wc_af_country[]" multiple="multiple">
						<?php
							global $woocommerce;
							$countries_obj      = new WC_Countries();
							$countries          = $countries_obj->__get( 'countries' );
							$saved_country_list = get_option( 'wc_af_country' );

							if( is_array( $countries ) && ! empty( $countries ) ) {
								foreach( $countries as $key => $country ) {
									if( is_array( $saved_country_list )
									&& ! empty( $saved_country_list ) ) {
									if( in_array( $key, $saved_country_list ) ) {  ?>
										<option selected value="<?php echo $key; ?>"><?php echo $country; ?></option>
										<?php
									} else { ?>
										<option value="<?php echo $key; ?>"><?php echo $country; ?></option>
										<?php
									}
									} else { ?>
									<option value="<?php echo $key; ?>"><?php echo $country; ?></option>
									<?php
									}
								}
							}
						?>
						</select>
						<a href="https://youtu.be/Tq8rb2byIv4" style="font-size:13px;" target="_blank">
							<?php echo __('Restrict  for Specific Countries Video Tutorial', 'checkout_address_autofill_for_woocommerce_pro'); ?>
						</a>
					</td>

				</tr>

				<tr valign="top">

					<th scope="row">
						<?php echo __( 'Language for Google Autofill', 'checkout_address_autofill_for_woocommerce' ); ?>
					
					</th>

					<td>
						<?php
						$wc_af_languages_for_google_autofill = get_option('wc_af_languages_for_google_autofill');
						
						$laguage_list_for_google_autofill =  get_option('laguage_list_for_google_autofill');
						
						?>
						<select name="wc_af_languages_for_google_autofill" id="wc_af_languages_for_google_autofill">
						<?php
						foreach($laguage_list_for_google_autofill as $language_code_for_google_autofill => $language_name_for_google_autofill){
							if($wc_af_languages_for_google_autofill == $language_code_for_google_autofill){
							?>
							<option selected value="<?php echo $wc_af_languages_for_google_autofill; ?>"><?php echo $language_name_for_google_autofill; ?></option>
							<?php
							
							}else{
							?>
							<option value="<?php echo $language_code_for_google_autofill; ?>"><?php echo $language_name_for_google_autofill; ?></option>
							<?php
							}
							
						}
						
						?>
						</select>

						<p class="description" id="label_for_field_description">
							<?php echo __('Indicating in which language the results should be returned, if possible', 'checkout_address_autofill_for_woocommerce_pro'); ?>
						</p>
					</td>

				</tr>

				<!-- Upload image for location -->
				<tr valign="top">

					<th scope="row">
						<?php echo __( 'Upload Image For Location', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>
					<td>

						<p>
							<img class="image_logo" src="<?php echo get_option( 'wc_af_location_image' ); ?>" height="<?php echo get_option( 'wc_af_image_height' ); ?>" width="<?php echo get_option( 'wc_af_image_width' ); ?>"/>
							<input class="image_logo_url" type="hidden" name="wc_af_location_image" value="<?php echo get_option( 'wc_af_location_image' ); ?>">
							<input type="button" class="image_logo_upload button" value="Upload">
						</p>

					</td>
				</tr>

				<!-- Set height and width of image -->
				<tr valign="top">

					<th scope="row">
						<?php echo __( 'Location Image Size In px', 'checkout_address_autofill_for_woocommerce' ); ?>
					</th>

					<td>
						<div class="image-dimension-wrapper">
						<label><?php echo __( 'Height', 'checkout_address_autofill_for_woocommerce' ); ?> : </label>
						<input type="number" class="image-dimension" name="wc_af_image_height" value="<?php echo get_option( 'wc_af_image_height' ); ?>">
						</div>

						<div class="image-dimension-wrapper">
						<label><?php echo __( 'Width', 'checkout_address_autofill_for_woocommerce' ); ?> : </label>
						<input type="number" name="wc_af_image_width"  value="<?php echo get_option( 'wc_af_image_width' ); ?>" >
						</div>
						<div class="tip">
                        <span class="help">
                            <i class="fa fa-question-circle"></i>
                            <span class="tip-txt">
							Change the size of the Location image (in pixel) </span>
                        </span>
                        </div>
					</td>

				</tr>

				<!-- On hover properties -->
				<tr valign="top">
					<th scope="row"><?php echo __( 'Enable Location Image Hover Effect', 'checkout_address_autofill_for_woocommerce' ); ?></th>
					<td>
					<input type="checkbox" name="wc_af_enable_hover" value="1" <?php checked( 1, get_option( 'wc_af_enable_hover' ), true); ?>>
					</td>
				</tr>

				<!-- Disable auto clearing default address values-->
				<tr valign="top">
					<th scope="row">
					<?php echo __('Disable auto clearing default address values', 'checkout_address_autofill_for_woocommerce'); ?>
					</th>
					<td>
					<input type="checkbox" name="wc_af_prohibit_address_clear" value="1" <?php checked(1, get_option('wc_af_prohibit_address_clear'), true); ?>>
					<p class="description" style="display: inline;"><?php echo __('This plugin overwrites Woocommerce feature that keeps filled in address values on page refresh to blank them. If you want to disable this feature and keep Woocommerce default behavior please check this.', 'checkout_address_autofill_for_woocommerce'); ?></p>
					</td>
				</tr>

			</table>

	
		<?php endif; ?>

		<?php

    }
	  

}