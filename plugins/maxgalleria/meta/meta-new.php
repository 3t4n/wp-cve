<?php
global $maxgalleria;
global $post;
$options = new MaxGalleryOptions($post->ID);
?>

<script type="text/javascript">			
	jQuery(document).ready(function() {
		// Hides the meta box
		jQuery("#new-gallery").css("display", "none");
		
    jQuery('.maxgalleria-meta').fadeIn(300);

    //jQuery(document).on("click", "#simplemodal-container a.simplemodal-close", function() {
		//	window.location = "< ?php echo esc_url(admin_url() . 'edit.php?post_type=' . esc_html(MAXGALLERIA_POST_TYPE)) ?>";
		//});
		
    jQuery(document).on("click", "#<?php echo esc_html($options->type_key) ?>_image_icon", function() {
			jQuery("#<?php echo esc_html($options->type_key) ?>_image_icon").addClass("selected");
			jQuery("#<?php echo esc_html($options->type_key) ?>_video_icon").removeClass("selected");
			jQuery("#<?php echo esc_html($options->type_key) ?>").val("image");
			jQuery("#<?php echo esc_html($options->template_key) ?>").val("<?php echo esc_html($maxgalleria->settings->get_default_image_gallery_template()) ?>");
			submitForm();
		});
		
    jQuery(document).on("click", "#<?php echo esc_html($options->type_key) ?>_video_icon", function() {
			jQuery("#<?php echo esc_html($options->type_key) ?>_video_icon").addClass("selected");
			jQuery("#<?php echo esc_html($options->type_key) ?>_image_icon").removeClass("selected");
			jQuery("#<?php echo esc_html($options->type_key) ?>").val("video");
			jQuery("#<?php echo esc_html($options->template_key) ?>").val("<?php echo esc_html($maxgalleria->settings->get_default_video_gallery_template()) ?>");
			submitForm();
		});
	});
	
	function submitForm() {
		var form_data = jQuery("#post").serialize();
		form_data += "&<?php echo esc_html($options->type_key) ?>=" + jQuery("#<?php echo esc_html($options->type_key) ?>").val();
		form_data += "&<?php echo esc_html($options->template_key) ?>=" + jQuery("#<?php echo esc_html($options->template_key) ?>").val();
		form_data += "&action=save_new_gallery_type";
				
		jQuery.ajax({
			type: "POST",
			url: "<?php echo admin_url('admin-ajax.php') ?>",
			data: form_data,
			success: function(redirec_url) {
				window.location = redirec_url;
			}
		});
	}
</script>

<div class="maxgalleria-meta" style="display: none;">
	<div class="gallery-type">
		<div align="center">
			<p><?php esc_html_e('What type of gallery do you want to create?', 'maxgalleria') ?></p>
			
			<table>
				<tr>
					<td>
						<img id="<?php echo esc_attr($options->type_key . '_image_icon') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/image-80.png') ?>" alt="<?php esc_html_e('Image', 'maxgalleria') ?>" title="<?php esc_html_e('Image', 'maxgalleria') ?>" />
						<br />
						<label><?php esc_html_e('Image', 'maxgalleria') ?></label>
					</td>
					<td>
						<img id="<?php echo esc_attr($options->type_key . '_video_icon') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/video-80.png') ?>" alt="<?php esc_html_e('Video', 'maxgalleria') ?>" title="<?php esc_html_e('Video', 'maxgalleria') ?>" />
						<br />
						<label><?php esc_html_e('Video', 'maxgalleria') ?></label>
					</td>
				</tr>
			</table>
			
			<!-- Default to an image gallery with the Image Tiles template -->
			<input type="hidden" id="<?php echo esc_attr($options->type_key) ?>" name="<?php echo esc_attr($options->type_key) ?>" value="image" />
			<input type="hidden" id="<?php echo esc_attr($options->template_key) ?>" name="<?php echo esc_attr($options->template_key) ?>" value="image-tiles" />
		</div>
	</div>
</div>
