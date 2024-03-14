<?php
/**
 * Template for FooEvents Calendar event metabox on non-product events
 *
 * @file    FooEvents Calendar metabox
 * @link    https://www.fooevents.com
 * @package fooevents-calendar
 */

?>
<div id="fooevents_options" class="fooevents_post_options">
	<div class="options_group">
		<p class="form-field">
			<label><?php esc_attr_e( 'Is this post an event?', 'fooevents-calendar' ); ?></label>
			<select name="WooCommerceEventsEvent" id="WooCommerceEventsMetaEvent">
				<option value="NotEvent" <?php echo ( 'NotEvent' === $event_event ) ? 'SELECTED' : ''; ?>><?php esc_attr_e( 'No', 'fooevents-calendar' ); ?></option>
				<option value="Event" <?php echo ( 'Event' === $event_event ) ? 'SELECTED' : ''; ?>><?php esc_attr_e( 'Yes', 'fooevents-calendar' ); ?></option>
			</select>
			<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( 'This option enables event and ticketing functionality.', 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
		</p>
	</div>
	<div id="WooCommerceEventsMetaForm" style="">
		<?php if ( $multi_day_active ) : ?>
		<div class="options_group" id="WooCommerceEventsNumDaysContainer">
			<p class="form-field">
					<label><?php esc_attr_e( 'Number of days:', 'fooevents-multiday-events' ); ?></label>
					<select name="WooCommerceEventsNumDays" id="WooCommerceEventsNumDays">
						<?php for ( $x = 1; $x <= 45; $x++ ) : ?>
						<option value="<?php echo esc_attr( $x ); ?>" <?php echo ( $x === (int) $woocommerce_events_num_days ) ? 'SELECTED' : ''; ?>><?php echo esc_attr( $x ); ?></option>
						<?php endfor; ?>
					</select>
					<img class="help_tip" data-tip="<?php esc_attr_e( 'Select the number of days for multi-day events. This setting is used by the Event Check-ins apps to manage daily check-ins.', 'fooevents-multiday-events' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
			</p>
		</div>
		<div class="options_group" id="WooCommerceEventsTypeHolder">
			<p class="form-field">
				<label><?php esc_attr_e( 'Event type:', 'fooevents-multiday-events' ); ?></label> 

					<span>
						<label><input type="radio" name="WooCommerceEventsType" value="single" <?php echo ( 'single' === $event_type || empty( $event_type ) ) ? 'CHECKED' : ''; ?>> <?php esc_attr_e( 'Single', 'fooevents-calendar' ); ?></label><br />
						<label><input type="radio" name="WooCommerceEventsType" value="sequential" <?php echo ( 'sequential' === $event_type ) ? 'CHECKED' : ''; ?>> <?php esc_attr_e( 'Sequential days', 'fooevents-calendar' ); ?></label><br />
						<label><input type="radio" name="WooCommerceEventsType" value="select" <?php echo ( 'select' === $event_type ) ? 'CHECKED' : ''; ?>> <?php esc_attr_e( 'Select days', 'fooevents-calendar' ); ?></label>
						<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( "Select the type of multi-day event. 'Sequential' means the days are in consecutive order whereas 'Select' allows you to choose the exact days.", 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
						</span>

			</p>
		</div>
		<!-- Start select Days code -->
		
		<div class="options_group" id ="WooCommerceEventsSelectDateContainer">
			<?php if ( ! empty( $woocommerce_events_select_date ) ) : ?>
				<p>Confirm2</p>
				<?php $x = 1; ?>
				<?php foreach ( $woocommerce_events_select_date as $event_date ) : ?>
			<div class="WooCommerceEventsSelectDateDay">
				<p class="form-field">
					<label><?php echo esc_attr( $day_term ); ?> <?php echo esc_attr( $x ); ?></label>
					<input type="text" class="WooCommerceEventsSelectDate" name="WooCommerceEventsSelectDate[]" value="<?php echo esc_attr( $event_date ); ?>"/>
				</p>
				<p class="form-field WooCommerceEventsSelectDateTimeContainer">
					<label><?php esc_attr_e( 'Start time:', 'woocommerce-events' ); ?></label>
					<select name="WooCommerceEventsSelectDateHour[]" class="WooCommerceEventsSelectDateHour" id="WooCommerceEventsSelectDateHour-<?php echo esc_attr( $x ); ?>">
						<?php for ( $y = 0; $y <= 23; $y++ ) : ?>
							<?php $y = sprintf( '%02d', $y ); ?>
						<option value="<?php echo esc_attr( $y ); ?>" <?php echo ( ( ! empty( $woocommerce_events_select_date_hour ) && $y === $woocommerce_events_select_date_hour[ $x - 1 ] ) || ( empty( $woocommerce_events_select_date_hour ) && $y === $woocommerce_events_hour ) ) ? 'SELECTED' : ''; ?>><?php echo esc_attr( $y ); ?></option>
						<?php endfor; ?>
					</select>
					<select name="WooCommerceEventsSelectDateMinutes[]" class="WooCommerceEventsSelectDateMinutes" id="WooCommerceEventsSelectDateMinutes-<?php echo esc_attr( $x ); ?>">
						<?php for ( $y = 0; $y <= 59; $y++ ) : ?>
							<?php $y = sprintf( '%02d', $y ); ?>
						<option value="<?php echo esc_attr( $y ); ?>"<?php echo ( ! empty( $woocommerce_events_select_date_minutes ) && $y === $woocommerce_events_select_date_minutes[ $x - 1 ] || ( empty( $woocommerce_events_select_date_hour ) && $y === $woocommerce_events_minutes ) ) ? 'SELECTED' : ''; ?>><?php echo esc_attr( $y ); ?></option>
						<?php endfor; ?>
					</select>
					<select name="WooCommerceEventsSelectDatePeriod[]" class="WooCommerceEventsSelectDatePeriod" id="WooCommerceEventsSelectDatePeriod-<?php echo esc_attr( $x ); ?>">
						<option value="">-</option>
						<option value="a.m." <?php echo ( ! empty( $woocommerce_events_select_date_period ) && isset( $woocommerce_events_select_date_period[ $x - 1 ] ) && 'a.m.' === $woocommerce_events_select_date_period[ $x - 1 ] || ( empty( $woocommerce_events_select_date_period ) && 'a.m.' === $woocommerce_events_period ) ) ? 'SELECTED' : ''; ?>>a.m.</option>
						<option value="p.m." <?php echo ( ! empty( $woocommerce_events_select_date_period ) && isset( $woocommerce_events_select_date_period[ $x - 1 ] ) && 'p.m.' === $woocommerce_events_select_date_period[ $x - 1 ] || ( empty( $woocommerce_events_select_date_period ) && 'p.m.' === $woocommerce_events_period ) ) ? 'SELECTED' : ''; ?>>p.m.</option>
					</select>
				</p>
				<p class="form-field WooCommerceEventsSelectDateTimeContainer">
					<label><?php esc_attr_e( 'End time:', 'woocommerce-events' ); ?></label>
					<select name="WooCommerceEventsSelectDateHourEnd[]" class="WooCommerceEventsSelectDateHourEnd" id="WooCommerceEventsSelectDateHourEnd-<?php echo esc_attr( $x ); ?>">
						<?php for ( $y = 0; $y <= 23; $y++ ) : ?>
							<?php $y = sprintf( '%02d', $y ); ?>
						<option value="<?php echo esc_attr( $y ); ?>" <?php echo ( ! empty( $woocommerce_events_select_date_hour_end ) && $y === $woocommerce_events_select_date_hour_end[ $x - 1 ] || ( empty( $woocommerce_events_select_date_hour_end ) && $y === $woocommerce_events_hour_end ) ) ? 'SELECTED' : ''; ?>><?php echo esc_attr( $y ); ?></option>
						<?php endfor; ?>
					</select>
					<select name="WooCommerceEventsSelectDateMinutesEnd[]" class="WooCommerceEventsSelectDateMinutesEnd" id="WooCommerceEventsSelectDateMinutesEnd-<?php echo esc_attr( $x ); ?>">
						<?php for ( $y = 0; $y <= 59; $y++ ) : ?>
							<?php $y = sprintf( '%02d', $y ); ?>
						<option value="<?php echo esc_attr( $y ); ?>" <?php echo ( ! empty( $woocommerce_events_select_date_minutes_end ) && $y === $woocommerce_events_select_date_minutes_end[ $x - 1 ] || ( empty( $woocommerce_events_select_date_minutes_end ) && $y === $woocommerce_events_minutes_end ) ) ? 'SELECTED' : ''; ?>><?php echo esc_attr( $y ); ?></option>
						<?php endfor; ?>
					</select>
					<select name="WooCommerceEventsSelectDatePeriodEnd[]" class="WooCommerceEventsSelectDatePeriodEnd" id="WooCommerceEventsSelectDatePeriodEnd-<?php echo esc_attr( $x ); ?>">
						<option value="">-</option>
						<option value="a.m." <?php echo ( ! empty( $woocommerce_events_select_date_period_end ) && isset( $woocommerce_events_select_date_period_end[ $x - 1 ] ) && 'a.m.' === $woocommerce_events_select_date_period_end[ $x - 1 ] || ( empty( $woocommerce_events_select_date_period_end ) && 'a.m.' === $woocommerce_events_end_period ) ) ? 'SELECTED' : ''; ?>>a.m.</option>
						<option value="p.m." <?php echo ( ! empty( $woocommerce_events_select_date_period_end ) && isset( $woocommerce_events_select_date_period_end[ $x - 1 ] ) && 'p.m.' === $woocommerce_events_select_date_period_end[ $x - 1 ] || ( empty( $woocommerce_events_select_date_period_end ) && 'p.m.' === $woocommerce_events_end_period ) ) ? 'SELECTED' : ''; ?>>p.m.</option>
					</select>
				</p>
			</div>
					<?php $x++; ?>
			<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<!-- End select Days code -->
		<div class="options_group" id="WooCommerceEventsSelectGlobalTimeContainer">
			<p class="form-field">
				<label><?php esc_attr_e( 'Set start/end times globally?', 'woocommerce-events' ); ?></label>
				<input type="checkbox" name="WooCommerceEventsSelectGlobalTime" id="WooCommerceEventsSelectGlobalTime" value="on" <?php echo( 'on' === $woocommerce_events_select_global_time ) ? 'CHECKED' : ''; ?>>
				<img class="help_tip" data-tip="<?php esc_attr_e( 'Enable this option to use the same start and end times for each day of a multi-day event.', 'woocommerce-events' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
			</p>
		</div>
		<?php endif; ?>
		<?php echo esc_attr( $multi_day_type ); ?>
				
		<div class="options_group" id="WooCommerceEventsDateContainer">
			<p class="form-field">
				<label><?php esc_attr_e( 'Start date:', 'fooevents-calendar' ); ?></label>
				<input type="text" id="WooCommerceEventsMetaBoxDate" class="WooCommerceEventsMetaBoxDate" name="WooCommerceEventsDate" value="<?php echo esc_attr( $event_date ); ?>"/>
				<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( "The date that the event is scheduled to take place. This is used as a label on your website and it's also used by the FooEvents Calendar to display the event.", 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
			</p>
		</div>
		<?php if ( $multi_day_active ) : ?>
		<div class="options_group" id="WooCommerceEventsEndDateContainer">
			<p class="form-field">
				<label><?php esc_attr_e( 'End date:', 'fooevents-calendar' ); ?></label>
				<input type="text" id="WooCommerceEventsEndDate" class="WooCommerceEventsSelectDate" name="WooCommerceEventsEndDate" value="<?php echo esc_attr( $event_end_date ); ?>"/>
				<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( "The date that the event is scheduled to end. This is used as a label on your website and it's also used by the FooEvents Calendar to display a multi-day event.", 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
			</p>
		</div>
		<?php endif; ?>
		
		<div class="options_group WooCommerceEventsSingleGroup">
			<p class="form-field">
				<label><?php esc_attr_e( 'Start time:', 'fooevents-calendar' ); ?></label>
				<select name="WooCommerceEventsHour" id="WooCommerceEventsHour">
					<?php for ( $x = 0; $x <= 23; $x++ ) : ?>
						<?php $x = sprintf( '%02d', $x ); ?>
					<option value="<?php echo esc_attr( $x ); ?>" <?php echo ( $event_hour === $x ) ? 'SELECTED' : ''; ?>><?php echo esc_attr( $x ); ?></option>
					<?php endfor; ?>
				</select>
				<select name="WooCommerceEventsMinutes" id="WooCommerceEventsMinutes">
					<?php for ( $x = 0; $x <= 59; $x++ ) : ?>
						<?php $x = sprintf( '%02d', $x ); ?>
					<option value="<?php echo esc_attr( $x ); ?>" <?php echo ( $event_minutes === $x ) ? 'SELECTED' : ''; ?>><?php echo esc_attr( $x ); ?></option>
					<?php endfor; ?>
				</select>
				<select name="WooCommerceEventsPeriod" id="WooCommerceEventsPeriod">
					<option value="">-</option>
					<option value="a.m." <?php echo ( 'a.m.' === $event_period ) ? 'SELECTED' : ''; ?>>a.m.</option>
					<option value="p.m." <?php echo ( 'p.m.' === $event_period ) ? 'SELECTED' : ''; ?>>p.m.</option>
				</select>
				<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( 'The time that the event is scheduled to start.', 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
			</p>
		</div>
		<div class="options_group WooCommerceEventsSingleGroup">
			<p class="form-field">
				<label><?php esc_attr_e( 'End time:', 'fooevents-calendar' ); ?></label>
				<select name="WooCommerceEventsHourEnd" id="WooCommerceEventsHourEnd">
					<?php for ( $x = 0; $x <= 23; $x++ ) : ?>
						<?php $x = sprintf( '%02d', $x ); ?>
					<option value="<?php echo esc_attr( $x ); ?>" <?php echo ( $event_hour_end === $x ) ? 'SELECTED' : ''; ?>><?php echo esc_attr( $x ); ?></option>
					<?php endfor; ?>
				</select>
				<select name="WooCommerceEventsMinutesEnd" id="WooCommerceEventsMinutesEnd">
					<?php for ( $x = 0; $x <= 59; $x++ ) : ?>
						<?php $x = sprintf( '%02d', $x ); ?>
					<option value="<?php echo esc_attr( $x ); ?>" <?php echo ( $event_minutes_end === $x ) ? 'SELECTED' : ''; ?>><?php echo esc_attr( $x ); ?></option>
					<?php endfor; ?>
				</select>
				<select name="WooCommerceEventsEndPeriod" id="WooCommerceEventsEndPeriod">
					<option value="">-</option>
					<option value="a.m." <?php echo ( $event_end_period == 'a.m.' ) ? 'SELECTED' : ''; ?>>a.m.</option>
					<option value="p.m." <?php echo ( $event_end_period == 'p.m.' ) ? 'SELECTED' : ''; ?>>p.m.</option>
				</select>
				<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( 'The time that the event is scheduled to end', 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
			</p>
		</div>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_attr_e( 'Time zone:', 'woocommerce-events' ); ?></label>
				<select name="WooCommerceEventsTimeZone" id="WooCommerceEventsTimeZone">
					<option value="" 
					<?php
					if ( '' === $event_timezone ) :
						?>
						SELECTED<?php endif; ?>>(Not set)</option>
				<?php
					$tzlist = DateTimeZone::listIdentifiers( DateTimeZone::ALL );
				foreach ( $tzlist as $tz ) {
					?>
						<option value="<?php echo esc_attr( $tz ); ?>" 
												<?php
												if ( $event_timezone === $tz ) :
													?>
							SELECTED<?php endif; ?>><?php echo esc_attr( str_replace( '_', ' ', str_replace( '/', ' / ', $tz ) ) ); ?></option>
					<?php
				}
				?>
				
				</select>
				<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( 'The time zone where the event is taking place.', 'woocommerce-events' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
			</p>
		</div>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_attr_e( 'Calendar background color:', 'woocommerce-events' ); ?></label>
				<input type="text" class="woocommerce-events-color-field" id="WooCommerceEventsBackgroundColor" name="WooCommerceEventsBackgroundColor" value="<?php echo esc_html( $woocommerce_events_background_color ); ?>"/>
				<img class="help_tip fooevents-tooltip" data-tip="<?php esc_attr_e( 'Color of the calendar background for the event. Also changes the background color of the date icon in the FooEvents Check-ins app.', 'woocommerce-events' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
			</p>
		</div>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_attr_e( 'Calendar text color:', 'woocommerce-events' ); ?></label>
				<input type="text" class="woocommerce-events-color-field" id="WooCommerceEventsTextColor" name="WooCommerceEventsTextColor" value="<?php echo esc_html( $woocommerce_events_text_color ); ?>"/>
				<img class="help_tip fooevents-tooltip" data-tip="<?php esc_attr_e( 'Color of the calendar text for the event. Also changes the font color of the date icon in the FooEvents Check-ins app.', 'woocommerce-events' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
			</p>
		</div>
		<?php if ( $eventbrite_option ) : ?>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_attr_e( 'Add event to Eventbrite', 'fooevents-calendar' ); ?></label>
				<input type="checkbox" id="WooCommerceEventsMetaBoxAddEventbrite" name="WooCommerceEventsAddEventbrite" value="1" <?php echo esc_attr( $event_add_eventbrite_checked ); ?>/>
				<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( 'Checking this option will submit the event to Eventbrite.', 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
			</p>
		</div>
		<?php endif; ?>
	</div>
	<input type="hidden" name="WooCommerceEventsNonProductEvent" value="yes" />
	<div class="spacer"></div>
</div>
