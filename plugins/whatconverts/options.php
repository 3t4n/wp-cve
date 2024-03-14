<div class="wrap">
<h1>WhatConverts</h1>
<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<?php settings_fields('whatconverts'); ?>
<p>Don't have your Profile ID? Visit <a href="http://app.whatconverts.com/">WhatConverts</a> and under your profile select 'Tracking' > 'Tracking Code'.</p>
<table class="form-table">
<tr valign="top">
<th scope="row">Profile ID:</th>
<td><input type="text" name="whatconverts_profile_id" value="<?php echo esc_attr(get_option('whatconverts_profile_id')); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row">Load in Footer:</th>
<td><input type="checkbox" name="whatconverts_footer_load" <?php if (esc_attr(get_option('whatconverts_footer_load')) == 1) { echo 'checked'; } ?> value="1" /></td>
</tr>
</table>
<input type="hidden" name="action" value="update" />
<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
<p>Once you have added your Profile ID, visit <a href="http://app.whatconverts.com/">WhatConverts</a> to confirm you have added Web Forms to track.</p>
</form>
</div>
