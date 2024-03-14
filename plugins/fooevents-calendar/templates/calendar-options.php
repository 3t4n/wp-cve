<?php
/**
 * Template for FooEvents Calendar options
 *
 * @file    FooEvents Calendar global options
 * @link    https://www.fooevents.com
 * @package fooevents-calendar
 */

?>
<?php settings_fields( 'fooevents-calendar-settings-calendar' ); ?>
<?php do_settings_sections( 'fooevents-calendar-settings-calendar' ); ?>
<tr valign="top" class="fooevents-settings-title">
	<th scope="row"><h2><?php esc_attr_e( 'Calendar', 'fooevents-calendar' ); ?></h2></th>
	<td></td>
	<td></td>
</tr>
<tr valign="top">
	<th scope="row"><?php esc_attr_e( 'Enable 24-hour time format', 'fooevents-calendar' ); ?></th>
	<td>
		<input type="checkbox" name="globalFooEventsTwentyFourHour" id="globalFooEventsTwentyFourHour" value="yes" <?php echo esc_attr( $global_fooevents_twentyfour_hour_checked ); ?>>
		<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( 'Uses the 24-hour time format on the calendar instead of 12-hour format.', 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php esc_attr_e( 'Only display start date', 'fooevents-calendar' ); ?></th>
	<td>
		<select name="globalFooEventsStartDay" id="globalFooEventsStartDay">
			<option value="" <?php echo '' === $global_fooevents_start_day ? 'Selected' : ''; ?>><?php esc_attr_e( 'Disabled', 'fooevents-calendar' ); ?></option>
			<option value="calendar" <?php echo 'calendar' === $global_fooevents_start_day ? 'Selected' : ''; ?>><?php esc_attr_e( 'Calendar', 'fooevents-calendar' ); ?></option>
			<option value="eventlist" <?php echo 'eventlist' === $global_fooevents_start_day ? 'Selected' : ''; ?>><?php esc_attr_e( 'Event List', 'fooevents-calendar' ); ?></option>
			<option value="both" <?php echo 'both' === $global_fooevents_start_day ? 'Selected' : ''; ?>><?php esc_attr_e( 'Both', 'fooevents-calendar' ); ?></option>
		</select>
		<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( 'When the FooEvents Multi-day plugin is active the calendar and/or event list will only display the start date for multi-day events.', 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php esc_attr_e( 'Enable full-day events', 'fooevents-calendar' ); ?></th>
	<td>
		<input type="checkbox" name="globalFooEventsAllDayEvent" id="globalFooEventsAllDayEvent" value="yes" <?php echo esc_attr( $global_fooevents_all_day_event_checked ); ?>>
		<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( 'Removes the event time from calendar event titles.', 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php esc_attr_e( 'Calendar theme', 'fooevents-calendar' ); ?></th>
	<td>
		<select name="globalFooEventsCalendarTheme" id="globalFooEventsCalendarTheme">
			<option value="default" <?php echo 'default' === $global_fooevents_calendar_theme ? 'Selected' : ''; ?>><?php esc_attr_e( 'Default', 'fooevents-calendar' ); ?></option>
			<option value="light" <?php echo 'light' === $global_fooevents_calendar_theme ? 'Selected' : ''; ?>><?php esc_attr_e( 'Light', 'fooevents-calendar' ); ?></option>
			<option value="dark" <?php echo 'dark' === $global_fooevents_calendar_theme ? 'Selected' : ''; ?>><?php esc_attr_e( 'Dark', 'fooevents-calendar' ); ?></option>
			<option value="flat" <?php echo 'flat' === $global_fooevents_calendar_theme ? 'Selected' : ''; ?>><?php esc_attr_e( 'Flat', 'fooevents-calendar' ); ?></option>
			<option value="minimalist" <?php echo 'minimalist' === $global_fooevents_calendar_theme ? 'Selected' : ''; ?>><?php esc_attr_e( 'Minimalist', 'fooevents-calendar' ); ?></option>
		</select>
		<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( 'Select the calendar theme to be used on your website.', 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php esc_attr_e( 'Events list theme', 'fooevents-calendar' ); ?></th>
	<td>
		<select name="globalFooEventsCalendarListTheme" id="globalFooEventsCalendarListTheme">
			<option value="default" <?php echo 'default' === $global_fooevents_calendar_list_theme ? 'Selected' : ''; ?>><?php esc_attr_e( 'Default', 'fooevents-calendar' ); ?></option>
			<option value="light-card" <?php echo 'light-card' === $global_fooevents_calendar_list_theme ? 'Selected' : ''; ?>><?php esc_attr_e( 'Light Card', 'fooevents-calendar' ); ?></option>
			<option value="dark-card" <?php echo 'dark-card' === $global_fooevents_calendar_list_theme ? 'Selected' : ''; ?>><?php esc_attr_e( 'Dark Card', 'fooevents-calendar' ); ?></option>
		</select>
		<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( 'Select the events list theme to be used on your website.', 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php esc_attr_e( 'Associate with post types', 'fooevents-calendar' ); ?></th>
	<td>
		<select multiple name="globalFooEventsCalendarPostTypes[]" id="globalFooEventsCalendarPostTypes">
			<?php foreach ( $associated_post_types as $associated_post_type ) : ?>
			<option value="<?php echo esc_attr( $associated_post_type ); ?>" <?php echo in_array( $associated_post_type, $global_fooevents_calendar_post_types, true ) || empty( $global_fooevents_calendar_post_types ) ? 'Selected' : ''; ?>><?php echo esc_attr( $associated_post_type ); ?></option>
			<?php endforeach; ?>
		</select>
		<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( 'Select which post types will be associated with events.', 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/fooevents-calendar/images/help.png" height="16" width="16" />
	</td>
</tr>
