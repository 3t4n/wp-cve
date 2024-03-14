<?php
/**
 * Template for FooEvents Calendar Eventbrite option
 *
 * @file    FooEvents Calendar eventbrite option
 * @link    https://www.fooevents.com
 * @package fooevents-calendar
 */

?>
<div class="options_group">
	<p class="form-field">
		<label><?php esc_attr_e( 'Add event to Eventbrite', 'fooevents-calendar' ); ?></label>
		<input type="checkbox" id="WooCommerceEventsMetaBoxAddEventbrite" name="WooCommerceEventsAddEventbrite" value="1" <?php echo esc_attr( $event_add_eventbrite_checked ); ?>/>
		<img class="help_tip" data-tip="<?php esc_attr_e( 'Checking this option will submit the event to Eventbrite.', 'woocommerce-events' ); ?>" src="<?php echo esc_attr( plugins_url() ); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
	</p>
</div>
