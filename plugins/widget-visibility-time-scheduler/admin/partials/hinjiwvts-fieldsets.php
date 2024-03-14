<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://wordpress.org/plugins/widget-visibility-time-scheduler
 * @since      1.0.0
 *
 * @package    Hinjiwvts
 * @subpackage Hinjiwvts/admin/partials
 */

$date_format = get_option( 'date_format' );
$time_format = get_option( 'time_format' );

$field_id    = $widget->get_field_id( 'mode' );
?>
<div class="wvts-container wvts-collapsed">
	<div class="wvts-scheduler">
		<h4><?php esc_html_e( 'Visibility Time Scheduler', 'hinjiwvts' ); ?></h4>
        <p><?php esc_html_e( 'Current time is', 'hinjiwvts' ); ?> <?php echo current_time( $date_format . ', ' . $time_format ); ?></p>
		<p>
			<label for="<?php echo $field_id; ?>"><?php $text = 'Schedule'; echo esc_html_x( $text, 'post action/button label' );?>:</label>
			<select id="<?php echo $field_id; ?>" name="<?php echo $this->plugin_slug; ?>[mode]">
				<option value=""><?php $text = '&mdash; Select &mdash;'; esc_html_e( $text );?></option>
<?php
foreach ( $this->modes as $mode ) {
?>
				<option value="<?php echo $mode; ?>"<?php selected( $mode, $this->scheduler[ 'mode' ] );?>><?php esc_html_e( $mode );?></option>
<?php
}
?>
			</select>
		</p>
		<fieldset>
			<legend><?php esc_html_e( 'from', 'hinjiwvts' ); ?></legend>
			<p><?php $this->touch_time( 'start' ); ?></p>
		</fieldset>
		<fieldset>
			<legend><?php esc_html_e( 'to', 'hinjiwvts' ); ?></legend>
			<p><?php $this->touch_time( 'end' ); ?></p>
<?php
// show advice and delete flag if user typed in an end year later than 2037
if ( false !== get_transient( $this->plugin_slug ) ) {
?>
		<p>
<?php
	printf(
		esc_html__( 'Why only up to end of 2037? Read %s.', 'hinjiwvts' ),
		sprintf(
			'<a href="%s" target="_blank">%s</a>',
			esc_url( __( 'http://en.wikipedia.org/wiki/Year_2038_problem', 'hinjiwvts' ) ),
			esc_html__( 'Wikipedia: Year 2038 problem (opens new window)', 'hinjiwvts' )
		)
	);
?>
		</p>
<?php
	delete_transient( $this->plugin_slug );
}
?>
		</fieldset>
		<fieldset>
			<legend><?php esc_html_e( 'on', 'hinjiwvts' ); ?></legend>
			<p>
<?php
		foreach ( $this->weekdays as $dayname => $value ) {
			$field_id = $widget->get_field_id( $dayname );
?>
				<input class="checkbox" type="checkbox" <?php checked( in_array( $value, $this->scheduler[ 'daysofweek' ] ) ); ?> id="<?php echo $field_id; ?>" name="<?php echo $this->plugin_slug; ?>[daysofweek][]" value="<?php echo $value; ?>" />
				<label for="<?php echo $field_id; ?>"><?php esc_html_e( $dayname ); ?></label><br />
<?php
		}
?>
			</p>
		</fieldset>
        <p><?php esc_html_e( 'Do you need more options?', 'hinjiwvts' );?> <a href="https://www.kybernetik-services.com/shop/wordpress/plugin/widget-visibility-time-scheduler-pro/?utm_source=wordpress_org&utm_medium=plugin&utm_campaign=widget-visibility-time-scheduler&utm_content=update-notice" target="_blank"><?php esc_html_e( 'Get the Pro version.', 'hinjiwvts' );?></a></p>
	</div><!-- .wvts-scheduler -->
	<p><a href="#" class="button wvts-link"><?php esc_html_e( 'Open scheduler', 'hinjiwvts' ); ?></a></p>
</div><!-- .wvts-scheduler -->
