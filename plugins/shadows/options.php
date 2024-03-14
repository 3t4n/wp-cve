<?php

function shadows_plugin_options() {

	if( $_POST['action'] == 'update' ) {
		$curl_shadow_height = preg_replace ('/[^0-9]*/', '', $_POST['curl_shadow_height'] );		# strip any non digits
		$curl_shadow_height = $curl_shadow_height ? $curl_shadow_height : '10';				# default to 10 if empty
		$curl_shadow_opacity = preg_replace ('/[^0-9]*/', '', $_POST['curl_shadow_opacity'] );		# strip any non digits
		$curl_shadow_opacity = $curl_shadow_opacity ? $curl_shadow_opacity : '100';			# default to 100 if empty
		$curl_shadow_height_unit = preg_replace ('/.*(px|em)$/i', '\\1', $_POST['curl_shadow_height'] );	# grab either px or em
		$curl_shadow_height_unit = strtolower($curl_shadow_height_unit);
		if (! ($curl_shadow_height_unit == 'px' || $curl_shadow_height_unit == 'em')) {
			$curl_shadow_height_unit = 'px';
		}

		update_option( 'curl_shadow_height', $curl_shadow_height . $curl_shadow_height_unit );
		update_option( 'curl_shadow_opacity', $curl_shadow_opacity );

		$flat_shadow_height = preg_replace ('/[^0-9]*/', '', $_POST['flat_shadow_height'] );		# strip any non digits
		$flat_shadow_height = $flat_shadow_height ? $flat_shadow_height : '10';				# default to 10 if empty
		$flat_shadow_opacity = preg_replace ('/[^0-9]*/', '', $_POST['flat_shadow_opacity'] );		# strip any non digits
		$flat_shadow_opacity = $flat_shadow_opacity ? $flat_shadow_opacity : '100';			# default to 100 if empty
		$flat_shadow_height_unit = preg_replace ('/.*(px|em)$/', '\\1', $_POST['flat_shadow_height'] );	# grab either px or em
		$flat_shadow_height_unit = strtolower($flat_shadow_height_unit);
		if (! ($flat_shadow_height_unit == 'px' || $flat_shadow_height_unit == 'em')) {
			$flat_shadow_height_unit = 'px';
		}

		update_option( 'flat_shadow_height', $flat_shadow_height . $flat_shadow_height_unit );
		update_option( 'flat_shadow_opacity', $flat_shadow_opacity );
?>
<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
<?php
	}

	$curl_shadow_height = get_option('curl_shadow_height');
	$curl_shadow_height = $curl_shadow_height ? $curl_shadow_height : '10px';
	$curl_shadow_opacity = get_option('curl_shadow_opacity');
	$curl_shadow_opacity = $curl_shadow_opacity ? $curl_shadow_opacity : '100%';

	$flat_shadow_height = get_option('flat_shadow_height');
	$flat_shadow_height = $flat_shadow_height ? $flat_shadow_height : '10px';
	$flat_shadow_opacity = get_option('flat_shadow_opacity');
	$flat_shadow_opacity = $flat_shadow_opacity ? $flat_shadow_opacity : '100%';

	# opacities must be a number ONLY but are better displayed with a trailing %
	$curl_shadow_opacity = preg_replace ('/[^0-9]*/', '', $curl_shadow_opacity) . '%';
	$flat_shadow_opacity = preg_replace ('/[^0-9]*/', '', $flat_shadow_opacity) . '%';

?>
<div class="wrap">
<h2>Shadows Options</h2>
<form method="post" action="">

<?php wp_nonce_field('update-options'); ?>

<p>It is recommended that you leave the height of the shadows at the default of 10px for best results.</p>

<p>Heights can be specified in px or em. They will default to px if the units are unspecified or invalid.</p>

<table class="form-table">
<tr valign="top">
<th scope="row"><strong>Curl</strong> shadow height</th>
<td><input type="text" name="curl_shadow_height" value="<?php echo $curl_shadow_height; ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><strong>Curl</strong> shadow opacity</th>
<td><input type="text" name="curl_shadow_opacity" value="<?php echo $curl_shadow_opacity; ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><strong>Flat</strong> shadow height</th>
<td><input type="text" name="flat_shadow_height" value="<?php echo $flat_shadow_height; ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><strong>Flat</strong> shadow opacity</th>
<td><input type="text" name="flat_shadow_opacity" value="<?php echo $flat_shadow_opacity; ?>" /></td>
</tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="curl_shadow_height,curl_shadow_opacity,flat_shadow_height,flat_shadow_opacity" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'mt_trans_domain' ); ?>" />
</p>
</form>
</div>

<?php
}
?>
