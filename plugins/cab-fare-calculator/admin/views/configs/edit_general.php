<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.btn-group-yesno label.btn').click(function () {
		//if (jQuery(this).prop("checked")) {
			//return;
		//}
		jQuery(this).siblings('label.btn').removeClass('active');
		jQuery(this).addClass('active');
		jQuery(this).parent('.btn-group-yesno').children('input').prop('checked', false);
		jQuery(this).prev('input').prop('checked', true);
	});		
	jQuery('label.has-child').click(function(){
		if (jQuery(this).hasClass('btn-yes')) {
			jQuery(this).closest('div.form-group').next('div.has-parent').show('slow');
		} else {
			jQuery(this).closest('div.form-group').next('div.has-parent').hide('slow');
		}
	});
})
</script>

<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>
<div class="tblight-wrap">
	
	<form method="post" name="admin-form" id="admin-form" class="admin-form validate">
		
		<?php wp_nonce_field( 'create-general-config', 'tblight_create_general_config' ); ?>
		<input type="hidden" name="action" value="save" />
		<input type="hidden" name="title" id="title" value="General Settings" />

		<div class="form-group clearfix">
			<label class="label">Distance Unit</label>
			<fieldset id="distance_unit" class="btn-group btn-group-yesno radio">
				<input type="radio" id="distance_unit0" name="configdata[distance_unit]" value="mile"<?php echo ( $data->distance_unit == 'mile' ) ? ' checked="checked"' : ''; ?> />
				<label for="distance_unit0" class="btn<?php echo ( $data->distance_unit == 'mile' ) ? ' active' : ''; ?>">Mile</label>
				<input type="radio" id="distance_unit1" name="configdata[distance_unit]" value="km"<?php echo ( $data->distance_unit == 'km' ) ? ' checked="checked"' : ''; ?> />
				<label for="distance_unit1" class="btn<?php echo ( $data->distance_unit == 'km' ) ? ' active' : ''; ?>">Kilometre</label>
			</fieldset>
		</div>
		<div class="form-group clearfix">
			<label class="label">Booking allowed after (hours)</label>
			<input type="text" name="configdata[restrict_time]" id="restrict_time" class="form-control small-text" value="<?php echo esc_attr( $data->restrict_time ); ?>" />
		</div>
		<div class="form-group clearfix">
			<label class="label">Date Format</label>
			<fieldset id="date_format" class="btn-group btn-group-yesno radio">
				<input type="radio" id="date_format0" name="configdata[date_format]" value="dd-mm-yy"<?php echo ( $data->date_format == 'dd-mm-yy' ) ? ' checked="checked"' : ''; ?> />
				<label for="date_format0" class="btn<?php echo ( $data->date_format == 'dd-mm-yy' ) ? ' active' : ''; ?>">dd-mm-yy</label>
				<input type="radio" id="date_format1" name="configdata[date_format]" value="mm-dd-yy"<?php echo ( $data->date_format == 'mm-dd-yy' ) ? ' checked="checked"' : ''; ?> />
				<label for="date_format1" class="btn<?php echo ( $data->date_format == 'mm-dd-yy' ) ? ' active' : ''; ?>">mm-dd-yy</label>
			</fieldset>
		</div>	
		<input type="hidden" name="datepicker_type" value="jquery" />
		<div class="form-group clearfix">
			<label class="label">Time Format</label>
			<fieldset id="time_format" class="btn-group btn-group-yesno radio">
				<input type="radio" id="time_format0" name="configdata[time_format]" value="12hr"<?php echo ( $data->time_format == '12hr' ) ? ' checked="checked"' : ''; ?> />
				<label for="time_format0" class="btn<?php echo ( $data->time_format == '12hr' ) ? ' active' : ''; ?>">12 Hour</label>
				<input type="radio" id="time_format1" name="configdata[time_format]" value="24hr"<?php echo ( $data->time_format == '24hr' ) ? ' checked="checked"' : ''; ?> />
				<label for="time_format1" class="btn<?php echo ( $data->time_format == '24hr' ) ? ' active' : ''; ?>">24 Hour</label>
			</fieldset>
		</div>
		<div class="form-group clearfix">
			<label class="label">Load Current Date</label>
			<fieldset id="load_current_date" class="btn-group btn-group-yesno radio">
				<input type="radio" id="load_current_date0" name="configdata[load_current_date]" value="1"<?php echo ( $data->load_current_date == 1 ) ? ' checked="checked"' : ''; ?> />
				<label for="load_current_date0" class="btn<?php echo ( $data->load_current_date == 1 ) ? ' active' : ''; ?>">Yes</label>
				<input type="radio" id="load_current_date1" name="configdata[load_current_date]" value="0"<?php echo ( $data->load_current_date == 0 ) ? ' checked="checked"' : ''; ?> />
				<label for="load_current_date1" class="btn<?php echo ( $data->load_current_date == 0 ) ? ' active' : ''; ?>">No</label>
			</fieldset>
		</div>	
		
		<div class="form-group clearfix">
			<label class="label">Show Adult Seats</label>
			<fieldset id="show_passengers_select" class="btn-group btn-group-yesno radio">
				<input type="radio" id="show_passengers_select0" name="configdata[show_passengers_select]" value="1"<?php echo ( $data->show_passengers_select == 1 ) ? ' checked="checked"' : ''; ?> />
				<label for="show_passengers_select0" class="btn has-child btn-yes<?php echo ( $data->show_passengers_select == 1 ) ? ' active' : ''; ?>">Yes</label>
				<input type="radio" id="show_passengers_select1" name="configdata[show_passengers_select]" value="0"<?php echo ( $data->show_passengers_select == 0 ) ? ' checked="checked"' : ''; ?> />
				<label for="show_passengers_select1" class="btn has-child btn-no<?php echo ( $data->show_passengers_select == 0 ) ? ' active' : ''; ?>">No</label>
			</fieldset>
		</div>
		<div class="form-group has-parent clearfix" style="<?php echo ( $data->show_passengers_select == 1 ) ? 'display:block;' : 'display:none;'; ?>">
			<label class="label">Default Adult Seats</label>
			<input type="text" name="configdata[default_adult_seat]" id="default_adult_seat" class="form-control small-text" value="<?php echo esc_attr( $data->default_adult_seat ); ?>" />
		</div>

		<div class="form-group clearfix">
			<label class="label">Show Suitcase drop down</label>
			<fieldset id="show_suitcase_select" class="btn-group btn-group-yesno radio">
				<input type="radio" id="show_suitcase_select0" name="configdata[show_suitcase_select]" value="1" <?php echo ( $data->show_suitcase_select ) ? 'checked="checked"' : ''; ?> />
				<label for="show_suitcase_select0" class="btn <?php echo ( $data->show_suitcase_select ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="show_suitcase_select1" name="configdata[show_suitcase_select]" value="0" <?php echo ( $data->show_suitcase_select ) ? '' : 'checked="checked"'; ?> />
				<label for="show_suitcase_select1" class="btn <?php echo ( $data->show_suitcase_select ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>
		<div class="form-group clearfix">
			<label class="label">Enable Captcha</label>
			<fieldset id="enable_captcha" class="btn-group btn-group-yesno radio">
				<input type="radio" id="enable_captcha0" name="configdata[enable_captcha]" value="1"<?php echo ( $data->enable_captcha ) ? ' checked="checked"' : ''; ?> />
				<label for="enable_captcha0" class="btn has-child btn-yes<?php echo ( $data->enable_captcha ) ? ' active' : ''; ?>">Yes</label>
				<input type="radio" id="enable_captcha1" name="configdata[enable_captcha]" value="0"<?php echo ( $data->enable_captcha ) ? '' : ' checked="checked"'; ?> />
				<label for="enable_captcha1" class="btn has-child btn-no<?php echo ( $data->enable_captcha ) ? '' : ' active'; ?>">No</label>
			</fieldset>
		</div>
		<div class="form-group has-parent clearfix" style="<?php echo ( $data->enable_captcha == 1 ) ? 'display:block;' : 'display:none;'; ?>">
			<label class="label">reCaptcha site key</label>
			<input type="text" name="configdata[recaptcha_key]" id="recaptcha_key" class="form-control regular-text" value="<?php echo esc_attr( $data->recaptcha_key ); ?>" />
		</div>	
		<input type="hidden" name="configdata[recaptcha_key_added]" id="recaptcha_key_added" value="yes" />

		<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>" />
		<input type="submit" name="submit" id="submit" class="button button-primary submit-general-config" value="<?php esc_attr_e( 'Save', 'cab-fare-calculator' ); ?>" />
		<a href="<?php echo admin_url( 'admin.php?page=configs' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Cancel', 'cab-fare-calculator' ); ?></a>
	</form>
	
</div>
