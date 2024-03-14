<h3><?php echo __( 'Email Reports', 'houzezpropertyfeed' ); ?></h3>

<p>With email reports enabled you can have the logs automatically emailed to you each time an import finishes running.</p>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="email_reports"><?php echo __( 'Enable Email Reports', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<input type="checkbox" name="email_reports" id="email_reports" value="yes"<?php if ( apply_filters( 'houzez_property_feed_pro_active', false ) === true && isset($options['email_reports']) && $options['email_reports'] === true ) { echo ' checked'; } ?><?php if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true ) { echo ' disabled'; } ?>>
				<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/pro-label.php' ); ?>
			</td>
		</tr>
		<tr id="email_reports_to_row" style="display:none">
			<th><label for="email_reports_to"><?php echo __( 'Email Reports To', 'houzezpropertyfeed' ); ?></label></th>
			<td >
				<input type="email" name="email_reports_to" id="email_reports_to" style="width:100%; max-width:400px;" value="<?php echo ( ( isset($options['email_reports_to']) && sanitize_email($options['email_reports_to']) != '' ) ? esc_attr(sanitize_email($options['email_reports_to'])) : get_option('admin_email') ) ?>">
			</td>
		</tr>
	</tbody>
</table>