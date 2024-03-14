<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  ?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e('Settings','pho-pincode-zipcode-cod'); ?></h1>
	<form novalidate="novalidate" method="post">

		<?php $nonce = wp_create_nonce( 'check_pincode_setting' ); ?>					
		<input type="hidden" value="<?php echo $nonce; ?>" name="_wpnonce_check_pincode_setting" id="_wpnonce_check_pincode_setting" />


		<table class="form-table">
			<tbody>
				<h3 class="wp-heading-inline"><?php esc_html_e('Enter Pincode Settings','pho-pincode-zipcode-cod'); ?></h3>
				<tr>
					<th>
						<label for="enter_pincode_heading"><?php esc_html_e('Enter Pincode Heading','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<input type="text" name="enter_pincode_heading" id="enter_pincode_heading" value="<?= isset($setting_data['enter_pincode_heading']) ? $setting_data['enter_pincode_heading'] : 'Check Availability At.' ?>">
					</td>
				</tr>

				<tr>
					<th>
						<label for="check_btn_name"><?php esc_html_e('Check Pincode Button Name','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<input type="text" name="check_btn_name" id="check_btn_name" value="<?= isset($setting_data['check_btn_name']) ? $setting_data['check_btn_name'] : 'CHECK' ?>">
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<h3 class="wp-heading-inline"><?php esc_html_e('Available Pincode Settings','pho-pincode-zipcode-cod'); ?></h3>
				<tr>
					<th>
						<label for="available_pincode_heading"><?php esc_html_e('Available Pincode Heading','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<input type="text" name="available_pincode_heading" id="available_pincode_heading" value="<?= isset($setting_data['available_pincode_heading']) ? $setting_data['available_pincode_heading'] : 'Availability At.' ?>">
					</td>
				</tr>

				<tr>
					<th>
						<label for="change_btn_name"><?php esc_html_e('Change Pincode Button Name','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<input type="text" name="change_btn_name" id="change_btn_name" value="<?= isset($setting_data['change_btn_name']) ? $setting_data['change_btn_name'] : 'CHANGE' ?>">
					</td>
				</tr>

				<tr>
					<th>
						<label for="show_state"><?php esc_html_e('Enable To Show State','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<input type="checkbox" name="show_state" id="show_state" value="yes" <?= isset($setting_data['show_state']) && $setting_data['show_state'] === 'yes' ? 'checked': '' ?> >
					</td>
				</tr>

				<tr>
					<th>
						<label for="show_city"><?php esc_html_e('Enable To Show City','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<input type="checkbox" name="show_city" id="show_city" value="yes" <?= isset($setting_data['show_city']) && $setting_data['show_city'] === 'yes' ? 'checked': '' ?>>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<h3 class="wp-heading-inline"><?php esc_html_e('Cash On Delivery Settings','pho-pincode-zipcode-cod'); ?></h3>
				<tr class="user-user-login-wrap">
					<th>
						<label for="cod_heading"><?php esc_html_e('Cash On Delivery Heading','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<input type="text" name="cod_heading" id="cod_heading" value="<?= isset($setting_data['cod_heading']) ? $setting_data['cod_heading'] : 'COD' ?>">
					</td>
				</tr>
				<tr class="user-user-login-wrap">
					<th>
						<label for="cod_help_text"><?php esc_html_e('Cash On Delivery Help Text','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<textarea class="regular-text" id="cod_help_text" name="cod_help_text"><?= (isset($setting_data['cod_help_text'])) ? $setting_data['cod_help_text'] : '' ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<h3 class="wp-heading-inline"><?php esc_html_e('Date Of Delivery Settings','pho-pincode-zipcode-cod'); ?></h3>
				<tr class="user-user-login-wrap">
					<th>
						<label for="dod_heading"><?php esc_html_e('Date Of Delivery Heading','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<input type="text" name="dod_heading" id="dod_heading" value="<?= isset($setting_data['dod_heading']) ? $setting_data['dod_heading'] : 'Get Delivery By' ?>">
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th>
						<label for="enable_delivery_date"><?php esc_html_e('Enable To Show Delivery Date','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<label for="enable_delivery_date"><input type="radio" <?php if(isset($setting_data['enable_delivery_date']) && $setting_data['enable_delivery_date'] == 'yes') { ?> checked <?php } ?> name="enable_delivery_date" value="yes"><?php esc_html_e('ON','pho-pincode-zipcode-cod'); ?></label>

						<label for="enable_delivery_date"><input type="radio" <?php if(isset($setting_data['enable_delivery_date']) && $setting_data['enable_delivery_date'] == 'no') { ?> checked <?php } ?> name="enable_delivery_date" value="no"><?php esc_html_e('OFF','pho-pincode-zipcode-cod'); ?></label>
					</td>
				</tr>

				<tr class="user-user-login-wrap">
					<th>
						<label for="delivery_date_help_text"><?php esc_html_e('Delivery Date Help Text','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<textarea class="regular-text" id="delivery_date_help_text" name="delivery_date_help_text"><?= (isset($setting_data['delivery_date_help_text'])) ? $setting_data['delivery_date_help_text'] : '' ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<h3 class="wp-heading-inline"><?php esc_html_e('Styling For Product Page','pho-pincode-zipcode-cod'); ?></h3>

				<tr class="user-user-login-wrap">
					<th><label for="box_bg_color"><?php esc_html_e('Box Background color','pho-pincode-zipcode-cod'); ?></label></th>
					<td><input type="text" class="regular-text phoeniixx-pincode-zipcode-color-picker" value="<?php echo $setting_data['box_bg_color']; ?>" id="box_bg_color" name="box_bg_color"></td>
				</tr>


				<tr class="user-first-name-wrap">
					<th><label for="label_txt_color"><?php esc_html_e('Label Text Color','pho-pincode-zipcode-cod'); ?></label></th>
					<td><input type="text" class="regular-text phoeniixx-pincode-zipcode-color-picker" value="<?php echo $setting_data['label_txt_color']; ?>" id="label_txt_color" name="label_txt_color"></td>
				</tr>


				<tr class="user-last-name-wrap">
					<th><label for="btn_txt_color"><?php esc_html_e('Button Color','pho-pincode-zipcode-cod'); ?></label></th>
					<td><input type="text" class="regular-text phoeniixx-pincode-zipcode-color-picker" value="<?php echo $setting_data['btn_txt_color']; ?>" id="btn_txt_color" name="btn_txt_color"></td>
				</tr>
					
					
				<tr class="user-last-name-wrap">
					<th><label for="btn_bg_color"><?php esc_html_e('Button Text Color','pho-pincode-zipcode-cod'); ?></label></th>
					<td><input type="text" class="regular-text phoeniixx-pincode-zipcode-color-picker" value="<?php echo $setting_data['btn_bg_color']; ?>" id="btn_bg_color" name="btn_bg_color"></td>
				</tr>
					
			</tbody>
		</table>		

		<table class="form-table">
			<tbody>
				<h3 class="wp-heading-inline"><?php esc_html_e('Pincode Messages','pho-pincode-zipcode-cod'); ?></h3>

				<tr class="user-user-login-wrap">
					<th>
						<label for="pincode_verify_error"><?php esc_html_e('Verify Pincode When Click To Add To Cart Button','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<textarea class="regular-text" id="pincode_verify_error" name="pincode_verify_error"><?= (isset($setting_data['pincode_verify_error'])) ? $setting_data['pincode_verify_error'] : 'verify pincode before Add To Cart' ?></textarea>
					</td>
				</tr>

				<tr class="user-user-login-wrap">
					<th>
						<label for="pincode_input_error"><?php esc_html_e('Message When Pincode Field Is Blank','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<textarea class="regular-text" id="pincode_input_error" name="pincode_input_error"><?= (isset($setting_data['pincode_input_error'])) ? $setting_data['pincode_input_error'] : 'Input is required field.' ?></textarea>
					</td>
				</tr>

				<tr class="user-user-login-wrap">
					<th>
						<label for="wrong_pincode_error"><?php esc_html_e('Message When Pincode Is Not Available','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<textarea class="regular-text" id="wrong_pincode_error" name="wrong_pincode_error"><?= (isset($setting_data['wrong_pincode_error'])) ? $setting_data['wrong_pincode_error'] : 'Oops! we are not servicing at your pincode.' ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" value="Save" class="button button-primary" id="submit" name="submit"></p>
	</form>
</div>