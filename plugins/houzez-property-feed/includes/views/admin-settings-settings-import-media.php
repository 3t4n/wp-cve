<h3><?php echo __( 'Media Processing', 'houzezpropertyfeed' ); ?></h3>

<p>Here you can control at what time media is imported; either at the same time as properties, or in a separate queue.</p>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="media_processing"><?php echo __( 'Import media', 'houzezpropertyfeed' ); ?></label></th>
			<td style="padding-top:20px;">

				<div style="padding:3px 0"><label><input type="radio" name="media_processing" id="media_processing" value="" <?php if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true || ( !isset($options['media_processing']) || (isset($options['media_processing']) && $options['media_processing'] == '' ) ) ) { echo ' checked'; } ?>> Immediately as each property is imported</label></div>

				<div style="padding:3px 0">
					<label><input type="radio" name="media_processing" id="media_processing_background" value="background" <?php if ( apply_filters( 'houzez_property_feed_pro_active', false ) === true && ( (isset($options['media_processing']) && $options['media_processing'] == 'background' ) ) ) { echo ' checked'; } ?><?php if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true ) { echo ' disabled'; } ?>> After imports have all completed in a separate queue <?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/pro-label.php' ); ?></label>
				</div>

				<br>
				<small><em>Media can take a long time to import. If you have a lot of properties and find that imports are timing out or not completing we recommend switching to processing media in a separate queue. This will ensure the core property data is imported first, and then a separate process will run in the background importing media shortly after at a later date.</em></small>

			</td>
		</tr>
	</tbody>
</table>