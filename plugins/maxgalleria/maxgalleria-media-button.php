<?php
global $pagenow;
global $maxgalleria;
?>

<?php // Only run in post/page creation and edit screens ?>
<?php if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) { ?>
	<?php // Get all published galleries ?>
	<?php $galleries = get_posts(array('post_type' => MAXGALLERIA_POST_TYPE, 'post_status' => 'publish', 'numberposts' => -1)) ?>
	
	<script type="text/javascript">
		function insertGalleryShortcode() {
			// Grab gallery ID
			var gallery_id = jQuery("#select-maxgallery").val();
			
			// Check if a gallery was selected
			if (gallery_id == "") {
				alert("<?php esc_html_e('Please select a gallery.', 'maxgalleria') ?>");
				return;
			}
			
			// Send shortcode to the editor
			window.send_to_editor('[maxgallery id="' + gallery_id + '"]');
		}
	</script>
	
	<div id="select-maxgallery-container" style="display: none;">
		<div class="wrap">
			<h2 style="padding-top: 3px; padding-left: 40px; background: url(<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL . '/images/maxgalleria-icon-32.png') ?>) no-repeat;">
				<?php esc_html_e('Insert Gallery into Editor', 'maxgalleria') ?>
			</h2>

			<p><?php esc_html_e('Select a gallery from the list below, then click "Insert Gallery" to place the gallery shortcode in the editor.', 'maxgalleria') ?></p>
			
			<select id="select-maxgallery" style="clear: both; display: block; margin-bottom: 1em;">
				<option value="">-- <?php esc_html_e('Select Gallery', 'maxgalleria') ?> --</option>
				<optgroup label="<?php esc_html_e('Image Galleries', 'maxgalleria') ?>">
				<?php foreach ($galleries as $gallery) { ?>
					<?php
					$maxgallery = new MaxGalleryOptions($gallery->ID);
					if ($maxgallery->is_image_gallery()) {
						$args = array('post_parent' => $gallery->ID, 'post_type' => 'attachment', 'numberposts' => -1);
						$attachments = get_posts($args);

						$template_key = $maxgallery->get_template();
						$template_name = $maxgalleria->get_template_name($template_key);

						$number = '';
						if (count($attachments) == 0) { $number = esc_html__('0 images', 'maxgalleria'); }
						if (count($attachments) == 1) { $number =  esc_html__('1 image', 'maxgalleria'); }
						if (count($attachments) > 1) { $number = sprintf(esc_html__('%d images', 'maxgalleria'), count($attachments)); }
						
						echo '<option value="' . esc_attr($gallery->ID) . '">' . esc_html($gallery->post_title . ' (' . $number . ', ' . $template_name . ')') . '</option>';
					}
					?>
				<?php } ?>
				</optgroup>
				
				<optgroup label="<?php esc_html_e('Video Galleries', 'maxgalleria') ?>">
				<?php foreach ($galleries as $gallery) { ?>
					<?php
					$maxgallery = new MaxGalleryOptions($gallery->ID);
					if ($maxgallery->is_video_gallery()) {
						$args = array('post_parent' => $gallery->ID, 'post_type' => 'attachment', 'numberposts' => -1);
						$attachments = get_posts($args);
						
						$template_key = $maxgallery->get_template();
						$template_name = $maxgalleria->get_template_name($template_key);
						
						$number = '';
						if (count($attachments) == 0) { $number = esc_html__('0 videos', 'maxgalleria'); }
						if (count($attachments) == 1) { $number =  esc_html__('1 video', 'maxgalleria'); }
						if (count($attachments) > 1) { $number = sprintf(esc_html__('%d videos', 'maxgalleria'), count($attachments)); }
						
						echo '<option value="' . esc_attr($gallery->ID) . '">' . esc_html($gallery->post_title . ' (' . $number . ', ' . $template_name . ')') . '</option>';
					}
					?>
				<?php } ?>
				</optgroup>
			</select>

			<input type="button" class="button-primary" value="<?php esc_html_e('Insert Gallery', 'maxgalleria') ?>" onclick="insertGalleryShortcode();" />
			<a class="button-secondary" style="margin-left: 10px;" onclick="tb_remove();"><?php esc_html_e('Cancel', 'maxgalleria') ?></a>
		</div>
	</div>
<?php } ?>