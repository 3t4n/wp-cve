<h3><?php echo __( 'Export Property Enquiries', 'houzezpropertyfeed' ); ?></h3>

<p><?php echo __( 'Export property enquiries made through your website back into', 'houzezpropertyfeed' ); ?> <span class="hpf-import-format-name"></span>.</p>

<table class="form-table media-image-settings">
	<tbody>
		<tr>
			<th><label for="export_enquiries_enabled"><?php echo __( 'Enabled', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<select name="export_enquiries_enabled" id="export_enquiries_enabled">
					<option value="">No</option>
					<option value="yes"<?php if ( isset($import_settings['export_enquiries_enabled']) && $import_settings['export_enquiries_enabled'] == 'yes' ) { echo ' selected'; } ?>>Yes</option>
				</select>
			</td>
		</tr>
	</tbody>
</table>