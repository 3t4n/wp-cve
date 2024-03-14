<?php
/**
 * Template for FooEvents Calendar options layout
 *
 * @file    FooEvents Calendar global options layout
 * @link    https://www.fooevents.com
 * @since   1.0.0
 * @package fooevents-calendar
 */
?>
<div class="wrap" id="fooevents-calendar-options-page">

	<div class="fooevents-calendar-setting-container"> 

		<div class="fooevents-calendar-setting-intro">
			<h2><a href="https://www.fooevents.com/" title="<?php esc_attr_e( 'FooEvents', 'fooevents-calendar' ); ?>"><img src="<?php echo esc_url(plugins_url('../images/fooevents-logo.png', __FILE__)); ?>" width="120px" alt="<?php esc_attr_e( 'FooEvents', 'fooevents-calendar' ); ?>" /></a></h2>
			<h1><?php esc_attr_e( 'Welcome to the Events Calendar by FooEvents', 'fooevents-calendar' ); ?></h1>
			<p><?php esc_attr_e( 'Convert any post, page, or custom post type into an event and display them in a stylish calendar on your WordPress website. This unique approach ensures that the FooEvents Calendar is super flexible and very easy to use.', 'fooevents-calendar' ); ?></p>
			<p><a href="https://www.fooevents.com/?ref=calendar" class="button button-primary button-hero"><?php esc_attr_e( 'Sell Tickets & Bookable Services', 'fooevents-calendar' ); ?></a></p>
			<p><a href="https://demo.fooevents.com/standalone-calendar/?ref=calendar"><?php esc_attr_e( 'Calendar Demo', 'fooevents-calendar' ); ?></a> | <a href="https://help.fooevents.com/docs/frequently-asked-questions/events/how-do-i-use-the-fooevents-calendar-on-its-own/?ref=calendar"><?php esc_attr_e( 'Documentation', 'fooevents-calendar' ); ?></a> | <a href="https://www.fooevents.com/promo/free/?ref=calendar"><?php esc_attr_e( 'Get FooEvents for FREE', 'fooevents-calendar' ); ?></a></p>
		</div>

		<div class="fooevents-column-content">

			<div class="fooevents-calendar-setting-form">
				<h2><?php esc_attr_e( 'Calendar Settings', 'fooevents-calendar' ); ?></h2>
				<form method="post" action="options.php">
					<table class="form-table">
						<?php echo $calendar_options; ?>
					</table>
					<?php submit_button(); ?>
				</form>	

			</div>

			<div class="fooevents-calendar-setting-description">
				<h2><?php esc_attr_e( 'How it works', 'fooevents-calendar' ); ?></h2>
				<ul class="fooevents-calendar-setting-steps">
					<li><strong><?php esc_attr_e( 'Step 1:', 'fooevents-calendar' ); ?></strong> <?php esc_attr_e( 'Configure the Calendar Settings.', 'fooevents-calendar' ); ?></li>
					<li><strong><?php esc_attr_e( 'Step 2:', 'fooevents-calendar' ); ?></strong> <?php esc_attr_e( 'Create or modify existing posts, pages, and custom post types and complete their event date and time settings.', 'fooevents-calendar' ); ?></li>
					<li><strong><?php esc_attr_e( 'Step 3:', 'fooevents-calendar' ); ?></strong> <?php esc_attr_e( 'Embed a calendar on any post or page using an Events Calendar shortcode or widget.', 'fooevents-calendar' ); ?></li>	
				</ul>
				<h2><?php esc_attr_e( 'FooEvents Plugins', 'fooevents-calendar' ); ?></h2>
				<ul class="fooevents-calendar-setting-extend">
					<li><strong><a href="https://www.fooevents.com/products/fooevents-for-woocommerce/?ref=calendar"><?php esc_attr_e( 'FooEvents for WooCommerce', 'fooevents-calendar' ); ?></a></strong> <span><?php esc_attr_e( 'core plugin', 'fooevents-calendar' ); ?></span></li>  
					<li><a href="https://www.fooevents.com/products/fooevents-bookings/?ref=calendar"><?php esc_attr_e( 'FooEvents Bookings', 'fooevents-calendar' ); ?></a></li>  
					<li><a href="https://www.fooevents.com/products/fooevents-seating/?ref=calendar"><?php esc_attr_e( 'FooEvents Seating', 'fooevents-calendar' ); ?></a></li>  
					<li><a href="https://www.fooevents.com/products/fooevents-pos/?ref=calendar"><?php esc_attr_e( 'FooEvents Point of Sale', 'fooevents-calendar' ); ?></a></li>  
					<li><a href="https://www.fooevents.com/products/fooevents-custom-attendee-fields/?ref=calendar"><?php esc_attr_e( 'FooEvents Custom Attendee Fields', 'fooevents-calendar' ); ?></a></li>  
					<li><a href="https://www.fooevents.com/products/fooevents-pdf-tickets/?ref=calendar"><?php esc_attr_e( 'FooEvents PDF Tickets', 'fooevents-calendar' ); ?></a></li>  
					<li><a href="https://www.fooevents.com/products/fooevents-multi-day/?ref=calendar"><?php esc_attr_e( 'FooEvents Multi-day', 'fooevents-calendar' ); ?></a></li>    
					<li><a href="https://www.fooevents.com/products/fooevents-express-check-in/?ref=calendar"><?php esc_attr_e( 'FooEvents Express Check-in', 'fooevents-calendar' ); ?></a></li>  
					<li><a href="https://www.fooevents.com/features/apps/?ref=calendar"><?php esc_attr_e( 'FooEvents Check-ins Apps', 'fooevents-calendar' ); ?></a> <span><?php esc_attr_e( 'iOS & Android', 'fooevents-calendar' ); ?></span></li>  
					<li><em><?php esc_attr_e( 'These extensions add additional functionality to the FooEvents for WooCommerce core plugin.', 'fooevents-calendar' ); ?></em></li>
				</ul>
			</div>

			<div class="clear clearfix"></div>

		</div> 

	</div>

</div>
