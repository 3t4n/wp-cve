<?php wp_enqueue_script( 'config-custom', TBLIGHT_PLUGIN_DIR_URL . 'admin/js/config.js', array(), filemtime( TBLIGHT_PATH . '/admin/js/config.js' ), true ); ?>

<?php 
if( !is_array( $data->gmap_api_avoids )){
	$data->gmap_api_avoids = array();
}
?>
<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>
<div class="tblight-wrap">
	
	<form method="post" name="admin-form" id="admin-form" class="admin-form validate">
	
		<?php wp_nonce_field( 'create-map-config', 'tblight_create_map_config' ); ?>
		<input type="hidden" name="action" value="save" />
		<?php // echo "<pre>"; print_r($item); echo "</pre>"; ?>
		<input type="hidden" name="title" id="title" value="Map Settings" />

		<div class="form-group clearfix">
			<label class="label">Show Map on Address search</label>
			<fieldset id="show_map_address" class="btn-group btn-group-yesno radio">
				<input type="radio" id="show_map_address1" name="configdata[show_map_address]" value="1" <?php echo ( $data->show_map_address ) ? 'checked="checked"' : ''; ?> />
				<label for="show_map_address1" class="btn <?php echo ( $data->show_map_address ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="show_map_address0" name="configdata[show_map_address]" value="0" <?php echo ( $data->show_map_address ) ? '' : 'checked="checked"'; ?> />
				<label for="show_map_address0" class="btn <?php echo ( $data->show_map_address ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>		
		<div class="form-group clearfix">
			<label class="label">Map Height(px)</label>
			<input type="text" name="configdata[map_height]" class="form-control small-text" value="<?php echo esc_attr( $data->map_height ); ?>" />
		</div>
		<div class="form-group clearfix">
			<label class="label">Map Zoom</label>
			<input type="text" name="configdata[map_zoom]" class="form-control small-text" value="<?php echo esc_attr( $data->map_zoom ); ?>" />
		</div>
		<div class="form-group clearfix">
			<label class="label">API Browser Key <span class="star">*</span></label>
			<input type="text" name="configdata[api_key]" class="form-control regular-text" value="<?php echo esc_attr( $data->api_key ); ?>" />
		</div>
		<div class="form-group clearfix">
			<label class="label">API Server Key <span class="star">*</span></label>
			<input type="text" name="configdata[api_server_key]" class="form-control regular-text" value="<?php echo esc_attr( $data->api_server_key ); ?>" />
		</div>
		<div class="form-group clearfix">
			<label class="label">API Avoid</label>
			<select name="configdata[gmap_api_avoids][]" multiple="multiple" size="4">
				<option value="ferries"<?php echo ( !empty( $data->gmap_api_avoids ) && in_array('ferries', $data->gmap_api_avoids ) ) ? ' selected="selected"' : '';?>>Ferries</option>
				<option value="highways"<?php echo ( !empty( $data->gmap_api_avoids ) && in_array('highways', $data->gmap_api_avoids ) ) ? ' selected="selected"' : '';?>>Highways</option>
				<option value="tolls"<?php echo ( !empty( $data->gmap_api_avoids ) && in_array('tolls', $data->gmap_api_avoids ) ) ? ' selected="selected"' : '';?>>Tolls</option>				
			</select>
		</div>		

		<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>" />
		<input type="submit" name="submit" id="submit" class="button button-primary submit-map-config" value="<?php esc_attr_e( 'Save', 'cab-fare-calculator' ); ?>" />
		<a href="<?php echo admin_url( 'admin.php?page=configs' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Cancel', 'cab-fare-calculator' ); ?></a>
	</form>
	
</div>
