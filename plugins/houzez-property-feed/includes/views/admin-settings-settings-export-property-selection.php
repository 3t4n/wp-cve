<h3><?php echo __( 'Property Selection', 'houzezpropertyfeed' ); ?></h3>

<p>Here you can choose how you determine which properties are sent in the exports.</p>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="property_selection_all"><?php echo __( 'Which Properties Should Be Sent In Exports', 'houzezpropertyfeed' ); ?></label></th>
			<td style="padding-top:20px;">

				<div style="padding:3px 0">
					<label><input type="radio" name="property_selection" id="property_selection_all" value="" <?php if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true || ( !isset($options['property_selection']) || (isset($options['property_selection']) && $options['property_selection'] == '' ) ) ) { echo ' checked'; } ?>> Send all published properties in all exports</label>
				</div>

				<div style="padding:3px 0">
					<label><input type="radio" name="property_selection" id="property_selection_individual" value="individual" <?php if ( apply_filters( 'houzez_property_feed_pro_active', false ) === true && ( !isset($options['property_selection']) || (isset($options['property_selection']) && $options['property_selection'] == 'individual' ) ) ) { echo ' checked'; } ?><?php if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true ) { echo ' disabled'; } ?>> Allow me to select which properties are included in which exports <?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/pro-label.php' ); ?></label>
				</div>

				<div style="padding:3px 0">
					<label><input type="radio" name="property_selection" id="property_selection_per_export" value="per_export" <?php if ( apply_filters( 'houzez_property_feed_pro_active', false ) === true && ( !isset($options['property_selection']) || (isset($options['property_selection']) && $options['property_selection'] == 'per_export' ) ) ) { echo ' checked'; } ?><?php if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true ) { echo ' disabled'; } ?>> Each export works differently. Allow me to choose how property selection works in each exports' settings <?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/pro-label.php' ); ?></label>
				</div>

				<div style="color:#999; font-size:13px; margin-top:5px;">If selecting individual properties is enabled, you'll have a new 'Exports' tab on a Houzez property listing where you can select whether the property should be included in which exports.</div>

			</td>
		</tr>
	</tbody>
</table>