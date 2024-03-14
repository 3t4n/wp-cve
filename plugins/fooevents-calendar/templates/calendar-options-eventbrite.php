<?php
/**
 * Template for FooEvents Calendar Eventbrite options
 *
 * @file    FooEvents Calendar Eventbrite options
 * @link    https://www.fooevents.com
 * @since   1.0.0
 * @package fooevents-calendar
 */

?>
<tr valign="top">
	<th scope="row" colspan="3"><h3 class="fooevents-settings-section-title"><?php esc_attr_e( 'Eventbrite', 'fooevents-calendar' ); ?></h3></th>
</tr>
<tr valign="top">
	<th scope="row"><?php esc_attr_e( 'Eventbrite private token', 'fooevents-calendar' ); ?></th>
	<td>
		<input type="password" name="globalFooEventsEventbriteToken" id="globalFooEventsEventbriteToken" value="<?php echo esc_attr( $global_fooevents_eventbrite_token ); ?>">
		<img class="help_tip fooevents-tooltip" title="<?php esc_attr_e( 'Optional API key that is used to add events to your Eventbrite account.', 'fooevents-calendar' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
	</td>
</tr>
<?php if ( ! empty( $global_fooevents_eventbrite_token ) ) : ?>
<tr valign="top">
	<th scope="row"><?php esc_attr_e( 'Import Eventbrite events', 'fooevents-calendar' ); ?></th>
	<td>
		<a class="button" id="fooevents-eventbrite-import" href="#">Import</a>
		<div id="fooevents-eventbrite-import-output"></div>
	</td>
</tr>
<?php endif; ?>
