<?php

$elsettings = BookingHelper::config();
wp_enqueue_script( 'car-custom', TBLIGHT_PLUGIN_DIR_URL . 'admin/js/car.js', array(), filemtime( TBLIGHT_PATH . '/admin/js/car.js' ), true );
?>

<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>
<div class="tblight-wrap">
	
	<form method="post" name="admin-form" id="admin-form" class="admin-form validate">
	
		<?php wp_nonce_field( 'create-car', 'tblight_create_car' ); ?>
		<input type="hidden" name="action" value="save" />
		<?php // echo "<pre>"; print_r($item); echo "</pre>"; ?>
		<div class="form-group clearfix form-required car_title_form_field">
			<label class="label"><?php esc_attr_e( 'Title', 'cab-fare-calculator' ); ?> <span class="star">*</span></label>
			<input type="text" name="title" id="title" class="form-control regular-text requried" aria-required="true" value="<?php echo esc_attr( $item->title ); ?>" />
		</div>
		<div class="form-group clearfix car_status_form_field">
			<label class="label">Published</label>
			<fieldset id="state" class="btn-group btn-group-yesno radio">
				<input type="radio" id="state1" name="state" value="1" <?php echo ( $item->state ) ? 'checked="checked"' : ''; ?> />
				<label for="state1" class="btn <?php echo ( $item->state ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="state0" name="state" value="0" <?php echo ( $item->state ) ? '' : 'checked="checked"'; ?> />
				<label for="state0" class="btn <?php echo ( $item->state ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>
		<div class="form-group clearfix car_minpassenger_form_field">
			<label class="label"><?php esc_attr_e( 'Minimum Passengers', 'cab-fare-calculator' ); ?></label>
			<input type="number" name="min_passenger_no" id="min_passenger_no" class="form-control small-text" value="<?php echo esc_attr( $item->min_passenger_no ); ?>" />
		</div>
		<div class="form-group clearfix form-required car_maxpassenger_form_field">
			<label class="label"><?php esc_attr_e( 'Maximum Passengers', 'cab-fare-calculator' ); ?> <span class="star">*</span></label>
			<input type="number" name="passenger_no" id="passenger_no" class="form-control small-text required numeric" value="<?php echo esc_attr( $item->passenger_no ); ?>" />
		</div>
		<div class="form-group clearfix car_maxsuitcases_form_field">
			<label class="label">Maximum suitcases</label>
			<input type="number" name="suitcase_no" id="suitcase_no" class="form-control small-text" value="<?php echo esc_attr( $item->suitcase_no ); ?>" />
		</div>
		<div class="form-group clearfix car_childseat_form_field">
			<label class="label">Maximum Child Seats</label>
			<input type="number" name="child_seat_no" id="child_seat_no" class="form-control small-text" value="<?php echo esc_attr( $item->child_seat_no ); ?>" />
		</div>
		<div class="form-group clearfix car_childseatprice_form_field">
			<label class="label">Child seat price</label>
			<input type="text" name="child_seat_price" id="child_seat_price" class="form-control small-text" value="<?php echo esc_attr( $item->child_seat_price ); ?>" />
		</div>
		<div class="form-group clearfix car_image_form_field">
			<label class="label">Car Image</label>
			<div class="image-block">
				<div class="preview-img">
					<img src="<?php echo esc_attr( $item->image ); ?>" id="preview-block" width="150" height="150" alt="" />
				</div>
				<input id="upload_image" type="hidden" name="image" value="<?php echo esc_attr( $item->image ); ?>" /> 
				<input id="upload_image_button" class="button" type="button" value="Upload Image" />
				<div class="field-message" style="color:#999;">Best Image dimensions 300px X 300px (Square image)</div>
			</div>
		</div>
		<div class="form-group clearfix car_price_form_field">
			<label class="label">Additional Car type flat fee</label>
			<input type="text" name="price" id="price" class="form-control small-text" value="<?php echo esc_attr( $item->price ); ?>" />
		</div>
		<div class="form-group clearfix car_minmil_form_field">
			<label class="label">Minimum Trip Distance</label>
			<input type="text" name="minmil" id="minmil" class="form-control small-text" value="<?php echo esc_attr( $item->minmil ); ?>" />
		</div>
		<div class="form-group clearfix car_minprice_form_field">
			<label class="label">Flat fee if trip less than minimum distance</label>
			<input type="text" name="minprice" id="minprice" class="form-control small-text" value="<?php echo esc_attr( $item->minprice ); ?>" />
		</div>
		<div class="form-group clearfix form-required car_unitprice_form_field">
			<label class="label">Price per <?php echo esc_attr( $elsettings->distance_unit ); ?> <span class="star">*</span></label>
			<input type="text" name="unit_price" id="unit_price" class="form-control small-text required numeric" value="<?php echo esc_attr( $item->unit_price ); ?>" />
		</div>
		<div class="form-group clearfix car_chargepermin_form_field">
			<label class="label">Charge per min</label>
			<input type="text" name="charge_per_min" id="charge_per_min" class="form-control small-text" value="<?php echo esc_attr( $item->charge_per_min ); ?>" />
		</div>
		<div class="form-group clearfix car_description_form_field">
			<label class="label">Description</label>
			<!--textarea type="editor" name="text" class="form-control"><?php // echo $item->text; ?></textarea-->
			<?php
			$text = $item->text; // this var may contains previous data that is stored in mysql.
			wp_editor(
				$text,
				'text',
				array(
					'textarea_rows' => 12,
					'editor_class'  => 'text',
				)
			);
			?>
						
		</div>
		<div class="form-group clearfix car_block_form_field">
			<label class="label">Blocked Dates</label>
			<div class="controls">
				<div id="blocked_dates_wrapper">
					<div class="block-add-new-btn">
						<a class="button button-small" href="javascript:void(0);" id="add_blocked_date">Add New</a>
					</div>
					<?php
					$blocked_dates_arr = json_decode( $item->blocked_dates );
					if ( empty( $blocked_dates_arr ) ) {
						?>
					<div class="blocked_date row0">
						<input name="blocked_dates[]" id="blocked_dates0" value="" class="inputbox datepicker_input" type="text" autocomplete="off" />
						<button type="button" class="btn remove_blocked_date" style="margin: 0px 0px 9px 9px;">
							<span class="dashicons dashicons-no"></span>
						</button>			
					</div>
						<?php
					} else {
						foreach ( $blocked_dates_arr as $i => $v ) {
							?>
					<div class="blocked_date row<?php echo $i; ?>">
						<input name="blocked_dates[]" id="blocked_dates<?php echo $i; ?>" value="<?php echo esc_attr( $v ); ?>" class="inputbox datepicker_input" type="text" autocomplete="off" />
						<button type="button" class="btn remove_blocked_date" style="margin: 0px 0px 9px 9px;">
							<span class="dashicons dashicons-no"></span>
						</button>			
					</div>	
							<?php
						}
					}
					?>
				</div>
			</div>
		</div>				

		<legend class="legend-row">Availability</legend>
		<div class="form-group clearfix car_availability_form_field">
			<label class="label">Track Availability</label>
			<fieldset id="track_availability" class="btn-group btn-group-yesno radio">
				<input type="radio" id="track_availability0" name="track_availability" value="1" <?php echo ( $item->track_availability ) ? 'checked="checked"' : ''; ?>>
				<label for="track_availability0" class="btn <?php echo ( $item->track_availability ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="track_availability1" name="track_availability" value="0" <?php echo ( $item->track_availability ) ? '' : 'checked="checked"'; ?>>
				<label for="track_availability1" class="btn <?php echo ( $item->track_availability ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>
		
		<?php
		$weekdays   = array(
			0 => ( 'MONDAY' ),
			1 => ( 'TUESDAY' ),
			2 => ( 'WEDNESDAY' ),
			3 => ( 'THURSDAY' ),
			4 => ( 'FRIDAY' ),
			5 => ( 'SATURDAY' ),
			6 => ( 'SUNDAY' ),
		);
		$hr_options = array();
		for ( $i = 0; $i < 24; $i++ ) {
			$i            = ( $i < 10 ) ? "0$i" : $i;
			$hr_options[] = '<option value="' . $i . '">' . $i . '</option>';
		}

		$min_options = array();
		for ( $i = 0; $i < 60; $i = $i + 5 ) {
			$i             = ( $i < 10 ) ? "0$i" : $i;
			$min_options[] = '<option value="' . $i . '">' . $i . '</option>';
		}
		$days_availability = json_decode( $item->days_availability );
		?>
		
		<div class="availability-table no-more-tables_style2" style='<?php echo ( $item->track_availability ) ? 'display:block;' : 'display:none;'; ?>'>
		<table class="adminlist" width="100%" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th class="one">Weekdays</th>
					<th class="two">Is Available?</th>
					<th class="three numeric">Available From</th>
					<th class="four numeric">Available To</th>
				</tr>
			</thead>
			<tbody>
				<?php
				for ( $i = 0;$i < count( $weekdays );$i++ ) {
					if ( isset( $days_availability[ $i ]->is_available ) ) {
						if ( $days_availability[ $i ]->is_available == 1 ) {
							$checked = ' checked="checked"';
						} else {
							$checked = '';
						}
					} else {
						$checked = '';
					}
					?>
				<tr>
				<td class="center" data-title="Weekdays"><?php echo $weekdays[ $i ]; ?></td>
				<td class="center" data-title="Is Available?">
					<input type="checkbox" value="1" name="days_availability[<?php echo (int) $i; ?>][is_available]" <?php echo $checked; ?> />
				</td>
				<td class="center numeric" data-title="Available From">
					<select id="days_availability<?php echo (int) $i; ?>opening_hrs" name="days_availability[<?php echo (int) $i; ?>][opening_hrs]" class="days_availability_time">
						<option value="-1" selected="selected">Hrs</option>
						<?php echo html_entity_decode( esc_html( implode( '', $hr_options ) ) ); ?>
					</select>
					<span class="dot">:</span>
					<select id="days_availability<?php echo (int) $i; ?>opening_mins" name="days_availability[<?php echo (int) $i; ?>][opening_mins]" class="days_availability_time">
						<option value="-1" selected="selected">Mins</option>
						<?php echo html_entity_decode( esc_html( implode( '', $min_options ) ) ); ?>
					</select>
				</td>
				<td class="center numeric" data-title="Available To">
					<select id="days_availability<?php echo (int) $i; ?>closing_hrs" name="days_availability[<?php echo (int) $i; ?>][closing_hrs]" class="days_availability_time">
						<option value="-1" selected="selected">Hrs</option>
						<?php echo html_entity_decode( esc_html( implode( '', $hr_options ) ) ); ?>
					</select>
					<span class="dot">:</span>
					<select id="days_availability<?php echo (int) $i; ?>closing_mins" name="days_availability[<?php echo (int) $i; ?>][closing_mins]" class="days_availability_time">
						<option value="-1" selected="selected">Mins</option>
						<?php echo html_entity_decode( esc_html( implode( '', $min_options ) ) ); ?>
					</select>
				</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		</div>

		<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>" />
		<input type="submit" name="submit" id="submit" class="button button-primary submit-car" value="<?php esc_attr_e( 'Save', 'cab-fare-calculator' ); ?>" />
		<a href="<?php echo admin_url( 'admin.php?page=cars' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Cancel', 'cab-fare-calculator' ); ?></a>
	</form>
	
</div>
