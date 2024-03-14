<?php 

$options = new MaxGalleriaYoutubeOptions();

?>
<script>
	jQuery(document).ready(function() {


    jQuery(document).on("click", "#save-youtube-settings", function () {
			jQuery("#save-youtube-settings-success").hide();
			
			var form_data = jQuery("#form-youtube-settings").serialize();
                        
			form_data += "&action=save_youtube_settings";
			
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: form_data,
				success: function(message) {
					if (message === "success") {
						jQuery("#save-youtube-settings-success").show();
					}
          window.location.reload();          
				}
			});
			
			return false;
		});

	});
</script>

<div id="save-youtube-settings-success" class="alert alert-success" style="display: none;">
	<?php esc_html_e('Settings saved.', 'maxgalleria') ?>
</div>

<div class="settings-title">
	<?php esc_html_e('YouTube Settings', 'maxgalleria') ?>
</div>

<div class="settings-options">
	
	<form id="form-youtube-settings">
    
    <table>
			<tr>
				<td><?php esc_html_e('Google Developer API Key:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_attr($options->developer_api_key_default) ?>" type="text" class="wide" id="<?php echo esc_attr($options->developer_api_key_default_key) ?>" name="<?php echo esc_attr($options->developer_api_key_key) ?>" value="<?php echo esc_html($options->get_developer_api_key_default()) ?>" />
				</td>
			</tr>    
    </table>
      
		<?php wp_nonce_field($options->nonce_save_youtube_defaults['action'], $options->nonce_save_youtube_defaults['name']) ?>

	</form>
  
  <a id="save-youtube-settings" href="#" class="button button-primary"><?php esc_html_e('Save Settings', 'maxgalleria') ?></a>
  
</div>
