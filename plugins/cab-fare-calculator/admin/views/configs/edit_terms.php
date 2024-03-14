<?php wp_enqueue_script( 'config-custom', TBLIGHT_PLUGIN_DIR_URL . 'admin/js/config.js', array(), filemtime( TBLIGHT_PATH . '/admin/js/config.js' ), true ); ?>
<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>
<div class="tblight-wrap">
	
	<form method="post" name="admin-form" id="admin-form" class="admin-form validate">
	
		<?php wp_nonce_field( 'create-terms-config', 'tblight_create_terms_config' ); ?>
		<input type="hidden" name="action" value="save" />
		<?php // echo "<pre>"; print_r($item); echo "</pre>"; ?>
		<input type="hidden" name="title" id="title" value="Terms Settings" />
		
		<div class="form-group clearfix">
			<label class="label">Use Terms and Conditions</label>
			<fieldset id="use_terms" class="btn-group btn-group-yesno radio">
				<input type="radio" id="use_terms1" name="configdata[use_terms]" value="1" <?php echo ( $data->use_terms ) ? 'checked="checked"' : ''; ?> />
				<label for="use_terms1" class="btn <?php echo ( $data->use_terms ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="use_terms0" name="configdata[use_terms]" value="0" <?php echo ( $data->use_terms ) ? '' : 'checked="checked"'; ?> />
				<label for="use_terms0" class="btn <?php echo ( $data->use_terms ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>
		<div class="form-group clearfix">
			<label class="label">Terms and Conditions Info</label>
			<!--textarea name="configdata[terms_conditions]"><?php // echo $data->terms_conditions; ?></textarea-->
			<?php
			$terms_conditions = $data->terms_conditions; // this var may contains previous data that is stored in mysql.
			wp_editor(
				$terms_conditions,
				'terms_conditions',
				array(
					'textarea_rows' => 12,
					'editor_class'  => 'terms_conditions',
				)
			);
			?>
						
		</div>		
		
		<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>" />
		<input type="submit" name="submit" id="submit" class="button button-primary submit-terms-config" value="<?php esc_attr_e( 'Save', 'cab-fare-calculator' ); ?>" />
		<a href="<?php echo admin_url( 'admin.php?page=configs' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Cancel', 'cab-fare-calculator' ); ?></a>
	</form>
	
</div>
