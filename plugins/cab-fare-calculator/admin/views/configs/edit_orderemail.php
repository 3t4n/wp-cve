<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>
<div class="tblight-wrap">
	
	<form method="post" name="admin-form" id="admin-form" class="admin-form validate">
	
		<?php wp_nonce_field( 'create-orderemail-config', 'tblight_create_orderemail_config' ); ?>
		<input type="hidden" name="action" value="save" />
		<?php // echo "<pre>"; print_r($item); echo "</pre>"; ?>
		<input type="hidden" name="title" id="title" value="Order Email Settings" />
		
		<div class="form-group clearfix">
			<label class="label">Header Logo</label>
			<div class="image-block">
				<div class="preview-img">
					<img src="<?php echo esc_attr( $data->header_logo ); ?>" id="preview-block" width="150" height="150" alt="" />
				</div>
				<input id="upload_image" type="hidden" name="configdata[header_logo]" value="<?php echo esc_attr( $data->header_logo ); ?>" /> 
				<input id="upload_image_button" class="button" type="button" value="Upload Image" />
			</div>			
		</div>
		<div class="form-group clearfix">
			<label class="label">Header Info</label>
			<!--textarea name="configdata[header_info]"><?php // echo $data->header_info; ?></textarea-->
			<?php
			$header_info = $data->header_info; // this var may contains previous data that is stored in mysql.
			wp_editor(
				$header_info,
				'header_info',
				array(
					'textarea_rows' => 12,
					'editor_class'  => 'header_info',
				)
			);
			?>
							
		</div>
		<div class="form-group clearfix">
			<label class="label">Footer Info</label>
			<!--textarea name="configdata[contact_info]"><?php // echo $data->contact_info; ?></textarea-->
			<?php
			$contact_info = $data->contact_info; // this var may contains previous data that is stored in mysql.
			wp_editor(
				$contact_info,
				'contact_info',
				array(
					'textarea_rows' => 12,
					'editor_class'  => 'contact_info',
				)
			);
			?>
		</div>
		
		<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>" />
		<input type="submit" name="submit" id="submit" class="button button-primary submit-orderemail-config" value="<?php esc_attr_e( 'Save', 'cab-fare-calculator' ); ?>" />
		<a href="<?php echo admin_url( 'admin.php?page=configs' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Cancel', 'cab-fare-calculator' ); ?></a>
	</form>
	
</div>
