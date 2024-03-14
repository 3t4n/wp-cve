<form method="post">
	<?php include('_inc/notices.php'); ?>

	<div id="google-web-fonts-setup-initial-container">

		<h3><?php _e('Initial Setup'); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="google-web-fonts-api-key"><?php _e('API Key'); ?></label></th>
					<td>
						<input data-bind="value: unsaved_api_key, valueUpdate: 'afterkeydown'" type="text" class="code regular-text" name="google-web-fonts[api-key]" id="google-web-fonts-api-key" value="<?php esc_attr_e($settings['api-key']); ?>" />
						<input data-bind="click: save_unsaved_api_key, enable: has_unsaved_api_key" type="button" class="button button-secondary" id="google-web-fonts-setup-api-key-validate" value="<?php _e('Validate and Save'); ?>" />
						<input data-bind="click: clear_saved_api_key, visible: has_saved_api_key" type="button" class="button button-secondary" id="google-web-fonts-setup-api-key-clear" value="<?php _e('Clear Saved Key'); ?>" />
						
						<div><?php printf(__('Get your <a target="_blank" href="%s">API Key</a> by following the directions on <a href="%s">Google</a>.'), 'https://code.google.com/apis/console/', 'https://developers.google.com/webfonts/docs/developer_api#Auth'); ?></div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</form>