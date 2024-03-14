<tr>
	<td class="label-cell">
		<label for="flickr-api-key"><?php _e( 'API Key', 'sliderpro' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input id="flickr-api-key" class="slide-setting" type="text" name="flickr_api_key" value="<?php echo isset( $slide_settings['flickr_api_key'] ) ? esc_attr( $slide_settings['flickr_api_key'] ) : $slide_default_settings['flickr_api_key']['default_value']; ?>" />
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label for="flickr-load-by"><?php _e( 'Load By', 'sliderpro' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select id="flickr-load-by" class="slide-setting" name="flickr_load_by">
			<?php
				foreach ( $slide_default_settings['flickr_load_by']['available_values'] as $value_name => $value_label ) {
					$selected = ( isset( $slide_settings['flickr_load_by'] ) && $value_name === $slide_settings['flickr_load_by'] ) || ( ! isset( $slide_settings['flickr_load_by'] ) && $value_name === $slide_default_settings['flickr_load_by']['default_value'] ) ? ' selected="selected"' : '';
					echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
	            }
			?>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label for="flickr-id"><?php _e( 'ID', 'sliderpro' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input id="flickr-id" class="slide-setting" type="text" name="flickr_id" value="<?php echo isset( $slide_settings['flickr_id'] ) ? esc_attr( $slide_settings['flickr_id'] ) : $slide_default_settings['flickr_id']['default_value']; ?>" />
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label for="flickr-limit"><?php _e( 'Limit', 'sliderpro' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input id="flickr-limit" class="slide-setting" type="text" name="flickr_per_page" value="<?php echo isset( $slide_settings['flickr_per_page'] ) ? esc_attr( $slide_settings['flickr_per_page'] ) : $slide_default_settings['flickr_per_page']['default_value']; ?>" />
	</td>
</tr>
<tr>
	<td colspan="2">
		<?php
            $hide_info = get_option( 'sliderpro_hide_inline_info' );

            if ( $hide_info != true ) {
        ?>
            <div class="inline-info slide-settings-info">
            	<input type="checkbox" id="show-hide-info" class="show-hide-info">
				<label for="show-hide-info" class="show-info"><?php _e( 'Show info', 'sliderpro' ); ?></label>
				<label for="show-hide-info" class="hide-info"><?php _e( 'Hide info', 'sliderpro' ); ?></label>
				
				<div class="info-content">
            		<p><?php _e( 'One <i>Flickr</i> slide in the admin area will dynamically generate multiple slides in the published slider (one slide for each Flickr image loaded), based on the set parameters.', 'sliderpro' ); ?></p>
                	<p><?php _e( 'First, you need to request an API key', 'sliderpro' ); ?> <a href="https://www.flickr.com/services/apps/create/apply/"><?php _e( 'here', 'sliderpro' ); ?></a> <?php _e( 'and then specify it in the <i>API Key</i> field above.', 'sliderpro' ); ?></p>
                	<p><?php _e( 'In the <i>ID</i> field you need to enter the id of the set or the id of the username, depending on the <i>Load by</i> selection.', 'sliderpro' ); ?></p>
                	<p><?php _e( 'The images and their data can be fetched through <i>dynamic tags</i>, which are enumerated in the Main Image, Layers and HTML editors.', 'sliderpro' ); ?></p>
            	</div>
            </div>
        <?php
            }
        ?>
	</td>
</tr>