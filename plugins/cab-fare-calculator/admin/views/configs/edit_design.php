<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.btn-group-yesno label.btn').click(function () {
			if (jQuery(this).prop("checked")) {
				// checked
				return;
			}
			jQuery(this).siblings('.btn').removeClass('active');
			jQuery(this).addClass('active');
			jQuery(this).parent('.btn-group-yesno').children('input').attr('checked', false);
			jQuery(this).prev('input').attr('checked', true);
		});

		jQuery('.show_map_key label.btn').click(function(){
			if (jQuery(this).text()=='Yes') {
				jQuery(this).closest('.show_map_key').siblings('.show_map_option').hide('slow');
			} else {
				jQuery(this).closest('.show_map_key').siblings('.show_map_option').show('slow');
			}
		})
	})
</script>

<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>
<div class="tblight-wrap">
	
	<form method="post" name="admin-form" id="admin-form" class="admin-form validate">
	
		<?php wp_nonce_field( 'create-design-config', 'tblight_create_design_config' ); ?>
		<input type="hidden" name="action" value="save" />
		<?php // echo "<pre>"; print_r($item); echo "</pre>"; ?>
		<input type="hidden" name="title" id="title" value="Design Settings" />
		
		<div class="form-group clearfix">
			<label class="label">Cars default display</label>
			<fieldset id="frontend_cars_default_display" class="btn-group btn-group-yesno radio">
				<input type="radio" id="frontend_cars_default_display1" name="configdata[frontend_cars_default_display]" value="grid"<?php echo ( $data->frontend_cars_default_display == 'grid' ) ? ' checked="checked"' : ''; ?> />
				<label for="frontend_cars_default_display1" class="btn <?php echo ( $data->frontend_cars_default_display == 'grid' ) ? 'active' : ''; ?>">Grid</label>
				<input type="radio" id="frontend_cars_default_display0" name="configdata[frontend_cars_default_display]" value="list" <?php echo ( $data->frontend_cars_default_display == 'list' ) ? ' checked="checked"' : ''; ?> />
				<label for="frontend_cars_default_display0" class="btn <?php echo ( $data->frontend_cars_default_display == 'list' ) ? 'active' : ''; ?>">List</label>
			</fieldset>
		</div>
		<div class="form-group clearfix">
			<label class="label">Highlights</label>
			<input type="color" name="configdata[highlights_color]" value="<?php echo esc_attr( $data->highlights_color ); ?>" />
		</div>
		<div class="form-group clearfix show_map_key">
			<label class="label">Show Map in Popup</label>
			<fieldset id="show_map_in_popup_only" class="btn-group btn-group-yesno radio">
				<input type="radio" id="show_map_in_popup_only1" name="configdata[show_map_in_popup_only]" value="1" <?php echo ( $data->show_map_in_popup_only ) ? 'checked="checked"' : ''; ?> />
				<label for="show_map_in_popup_only1" class="btn <?php echo ( $data->show_map_in_popup_only ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="show_map_in_popup_only0" name="configdata[show_map_in_popup_only]" value="0" <?php echo ( $data->show_map_in_popup_only ) ? '' : 'checked="checked"'; ?> />
				<label for="show_map_in_popup_only0" class="btn <?php echo ( $data->show_map_in_popup_only ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>		
		<div class="form-group clearfix show_map_option" style="<?php echo ( $data->show_map_in_popup_only ) ? 'display:none' : 'display:block'; ?>">
			<label class="label">Show Map on desktop devices</label>
			<fieldset id="show_map_on_desktop" class="btn-group btn-group-yesno radio">
				<input type="radio" id="show_map_on_desktop1" name="configdata[show_map_on_desktop]" value="1" <?php echo ( $data->show_map_on_desktop ) ? 'checked="checked"' : ''; ?> />
				<label for="show_map_on_desktop1" class="btn <?php echo ( $data->show_map_on_desktop ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="show_map_on_desktop0" name="configdata[show_map_on_desktop]" value="0" <?php echo ( $data->show_map_on_desktop ) ? '' : 'checked="checked"'; ?> />
				<label for="show_map_on_desktop0" class="btn <?php echo ( $data->show_map_on_desktop ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>
		<div class="form-group clearfix show_map_option" style="<?php echo ( $data->show_map_in_popup_only ) ? 'display:none' : 'display:block'; ?>">
			<label class="label">Show Map on mobile devices</label>
			<fieldset id="show_map_on_mobile" class="btn-group btn-group-yesno radio">
				<input type="radio" id="show_map_on_mobile1" name="configdata[show_map_on_mobile]" value="1" <?php echo ( $data->show_map_on_mobile ) ? 'checked="checked"' : ''; ?> />
				<label for="show_map_on_mobile1" class="btn <?php echo ( $data->show_map_on_mobile ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="show_map_on_mobile0" name="configdata[show_map_on_mobile]" value="0" <?php echo ( $data->show_map_on_mobile ) ? '' : 'checked="checked"'; ?> />
				<label for="show_map_on_mobile0" class="btn <?php echo ( $data->show_map_on_mobile ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>	
	
		
		<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>" />
		<input type="submit" name="submit" id="submit" class="button button-primary submit-design-config" value="<?php esc_attr_e( 'Save', 'cab-fare-calculator' ); ?>" />
		<a href="<?php echo admin_url( 'admin.php?page=configs' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Cancel', 'cab-fare-calculator' ); ?></a>
	</form>
	
</div>
