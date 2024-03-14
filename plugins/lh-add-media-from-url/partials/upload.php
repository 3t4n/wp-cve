<form method="post">
<input type="hidden" value="<?php echo wp_create_nonce("lh_add_media_from_url-file_url"); ?>" name="lh_add_media_from_url-nonce" id="lh_add_media_from_url-nonce" />
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="lh_add_media_from_url-file_url"><?php echo __( 'URL', self::return_plugin_text_domain());  ?></label></th>
<td><input type="url" name="lh_add_media_from_url-file_url" id="lh_add_media_from_url-file_url" value="<?php  if (isset($value)){  echo $value; } ?>" size="50" /></td>
</tr>
</table>
<?php submit_button( 'Submit' ); ?>
</form>