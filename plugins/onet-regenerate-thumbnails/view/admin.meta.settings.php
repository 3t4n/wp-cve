<form action="" method="post" class="saveditor">
	<p><input type="checkbox" name="after_media_btn" id="after_media_btn" <?php echo $opts->after_media_btn == 1 ? 'checked="checked"' : "" ?>/>
		<label for="after_media_btn" title="<?php _e('Displays a button on post editor next to media uploader button.', 'onetrt'); ?>"><?php _e('Regen. button next to uploader', 'onetrt'); ?></label>
	</p>
	<p><input type="checkbox" name="bulk_action" id="bulk_action" <?php echo $opts->bulk_action == 1 ? 'checked="checked"' : "" ?> />
		<label for="bulk_action" title="<?php _e('Displays regeneration bulk option in bulk actions dropdown.', 'onetrt'); ?>"><?php _e('Bulk action', 'onetrt'); ?></label>
	</p>
	<p><input type="checkbox" name="only_complement" id="only_complement" <?php echo $opts->only_complement == 1 ? 'checked="checked"' : "" ?> />
		<label for="only_complement" title="<?php _e('Generates only missing images. This option can improve performance.', 'onetrt'); ?>"><?php _e('Complementary generate', 'onetrt'); ?></label>
	</p>
	<p><input type="checkbox" name="advanced" id="advanced" <?php echo $opts->advanced == 1 ? 'checked="checked"' : "" ?> />
		<label for="advanced"><em><?php _e('Display advanced options', 'onetrt'); ?></em></label>
	</p>
	<div style="<?php echo $opts->advanced == 1 ? "" : "display:none;"; ?>">
		<p><input type="checkbox" name="massgen" id="massgen" <?php echo $opts->massgen == 1 ? 'checked="checked"' : "" ?> />
			<label for="massgen" title="<?php _e('This option can speed up the process extremely but be aware that this option will also put heavy load on your server. Use this function on your own risk!', 'onetrt'); ?>"><em><?php _e('Mass regenerate', 'onetrt'); ?></em></label>
		</p>
		<p><input type="checkbox" name="success_redir" id="success_redir" <?php echo $opts->success_redir == 1 ? 'checked="checked"' : "" ?> />
			<label for="success_redir" title="<?php _e('Redirect user when process is done.', 'onetrt'); ?>"><em><?php _e('Redirect on success', 'onetrt'); ?></em></label>
		</p>
		<p><input type="checkbox" name="quality_overwrite" id="quality_overwrite" <?php echo $opts->quality_overwrite == 1 ? 'checked="checked"' : "" ?> />
			<label for="quality" title="<?php _e('Defines JPG quality for images, there is no guarantee that the results will have better quality.', 'onetrt'); ?>"><em><?php _e('Overwrite JPEG quality.', 'onetrt'); ?> </em></label>
			<input type="number" name="quality" min="10" max="100" id="quality" value="<?php echo $opts->quality; ?>" />%
		</p>

	</div>
	<input type="submit" name="save_settings" id="saveSettings" class="button" value="<?php _e('Save settings','onetrt'); ?>" />
</form>