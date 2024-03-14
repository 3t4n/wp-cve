<h3><?php echo __( 'Export Frequency', 'houzezpropertyfeed' ); ?></h3>

<p><?php echo __( 'Choose how often exports should run by selecting the frequency below', 'houzezpropertyfeed' ); ?>:</p>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="frequency"><?php echo __( 'Frequency', 'houzezpropertyfeed' ); ?></label></th>
			<td style="padding-top:20px;">
				<?php
					foreach ( $frequencies as $key => $frequency )
					{
						$checked = false;
						if ( isset($export_settings['frequency']) && $export_settings['frequency'] == $key )
						{
							$checked = true;
						}
						elseif ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true && $key == 'daily' )
						{
							$checked = true;
						}
						elseif( !isset($export_settings['frequency']) && $key == 'daily' )
						{
							$checked = true;
						}

						echo '<div style="padding:3px 0"><label><input type="radio" name="frequency" value="' . esc_attr($key) . '"' . ( $checked === true ? 'checked' : '' ) . ' ' . ( ( isset($frequency['pro']) && $frequency['pro'] === true && apply_filters( 'houzez_property_feed_pro_active', false ) !== true ) ? 'disabled' : '' ) . '> ' . esc_html($frequency['name']);
						if ( isset($frequency['pro']) && $frequency['pro'] === true )
						{
							include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/pro-label.php' );
						}
						echo '</label></div>';
					}
				?>
			</td>
		</tr>
	</tbody>
</table>