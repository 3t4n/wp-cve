<div class="directorypress-modal-content wp-clearfix">
	<form class="search-config" method="POST" action="">
		<?php wp_nonce_field(DIRECTORYPRESS_PATH, 'directorypress_configure_fields_nonce');?>
		<div class="field-holder">
			<div><label><?php _e('How to show in Search Form', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<p><?php _e('Add in keywords field', 'DIRECTORYPRESS')?></p>
				<label class="switch">
					<input name="search_input_mode" type="radio" value="keywords" <?php checked($search_field->search_input_mode, 'keywords'); ?> />
					<span class="slider"></span>
				</label>
				<p><?php _e('seperate input field', 'DIRECTORYPRESS')?></p>
				<label class="switch">
					<input name="search_input_mode" type="radio" value="input" <?php checked($search_field->search_input_mode, 'input'); ?> />
					<span class="slider"></span>
				</label>
			</div>
		</div>
		<div class="id">
			<input type="hidden" name="id" value="">
		</div>
	</form>
</div>