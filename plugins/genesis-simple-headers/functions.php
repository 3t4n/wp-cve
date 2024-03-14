<?php
// deal with activation

// require Genesis 1.5 upon activation
register_activation_hook(__FILE__, 'simpleheaders_activation_check');
function simpleheaders_activation_check() {

	$latest = '1.5';

	$theme_info = get_theme_data(TEMPLATEPATH.'/style.css');

	if( basename(TEMPLATEPATH) != 'genesis' ) {
		deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
		wp_die('Sorry, you can\'t activate unless you have installed <a href="http://9seeds.com/studiopress-genesis">Genesis</a>');
	}

	if( version_compare( $theme_info['Version'], $latest, '<' ) ) {
		deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
		wp_die('Sorry, you can\'t activate without <a href="http://www.studiopress.com/support/showthread.php?t=19576">Genesis '.$latest.'</a> or greater');
	}
}


// Remove genesis version
remove_action( 'after_setup_theme', 'genesis_custom_header', 10 );

// Add our fields to the settings page
add_action('custom_header_options', 'gsh_custom_header_options');

 
function gsh_custom_header_options()
{
?>
<h3>Genesis Simple Headers [<a href="http://9seeds.com/help/genesis-simple-headers/" target="_blank">?</a>]</h3>
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><?php _e( 'Simple Headers Type' ); ?></th>
			<td><p><input type="radio" name="gsh_type" value="standard" <?php if( esc_attr( get_theme_mod( 'gsh_type' ) ) != 'advanced' ) { echo "checked='checked'"; } ?>> Standard<br />
					<input type="radio" name="gsh_type" value="advanced" <?php if( esc_attr( get_theme_mod( 'gsh_type' ) ) == 'advanced' ) { echo "checked='checked'"; } ?> > Advanced</p></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Header Width:' ); ?></th>
			<td>
				<p>
					<input type="text" name="gsh_adv_header_width" id="gsh_adv_header_width" value="<?php echo esc_attr( get_theme_mod( 'gsh_adv_header_width', '999' ) ); ?>" />
				</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Header Height:' ); ?></th>
			<td>
				<p>
					<input type="text" name="gsh_adv_header_height" id="gsh_adv_header_height" value="<?php echo esc_attr( get_theme_mod( 'gsh_adv_header_height', '999' ) ); ?>" />
				</p>
			</td>
		</tr>
	</tbody>
</table>
<?php
}

// Save our data
add_action( 'admin_head', 'gsh_save_custom_options', 1 );

function gsh_save_custom_options()
{
	if( isset( $_POST['gsh_type']  ) ) {
		check_admin_referer( 'custom-header-options', '_wpnonce-custom-header-options' );

		if ( current_user_can('manage_options') ) {
			set_theme_mod( 'gsh_type', esc_attr( $_POST['gsh_type'] ) );
			set_theme_mod( 'gsh_adv_header_width', esc_attr( $_POST['gsh_adv_header_width'] ) );
			set_theme_mod( 'gsh_adv_header_height', esc_attr( $_POST['gsh_adv_header_height'] ) );		
		}
	}
	return;
}
