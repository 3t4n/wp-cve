<?php

if ('wpks-admin.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not access this file directly. Thanks!');

if ( !is_admin() ) {
	die();
}
if ( !current_user_can( 'manage_options' ) ) :
	wp_die( 'You do not have sufficient permissions to access this page.' );
endif;

if ( isset( $_POST['wpks-submit'] ) ) :
    if ( !wp_verify_nonce( $_POST['wpks-nonce'], 'wpks-nonce' ) ) die( 'Invalid Nonce.' );
	if ( function_exists( 'current_user_can' ) && current_user_can( 'edit_plugins' ) ) :
		update_option( 'wpks_intense', $_POST['wpks_intense'] );
		echo '<div class="updated fade"><p>Options updated and saved.</p></div>';
else :
	wp_die( '<p>' . 'You do not have sufficient permissions.' . '</p>' );
endif;
endif;
?>
<div id="wpks-options" class="wrap">
<div id="wpks-options-icon" class="icon32"><br /></div>
<h2><?php _e('WP Keyword Suggest Options', _PLUGIN_NAME_); ?></h2>
<form class="wpks-form" name="wpks-options" method="post" action="">
<h3>General</h3>
<table class="form-table">
<tr valign="top">
	<th scope="row"><label for="wpks-api-key"><?php _e('Intense', _PLUGIN_NAME_); ?></label></th>
	<td>
		<select id="wpks_intense" name="wpks_intense" style="min-width:80px;">
		<?php
		$suggestions_limit = array( __('Low (up to 30 keywords ideas)', _PLUGIN_NAME_) => 'low', __('High (up to 260 keywords ideas)', _PLUGIN_NAME_) => 'high' );
		foreach ( $suggestions_limit as $limit => $value ) :
			echo '<option value="' . $value . '" ';
			if ( get_option( 'wpks_intense' ) == $value ) echo 'selected="selected"';
			echo '>' . $limit . '</option>';
		endforeach
		?>
		</select>
		<br/>
		<span class="description"><?php _e('Important note: few seconds loading time in LOW and half minute to a minute in HIGH. HIGH is only available for Latin languages.', _PLUGIN_NAME_); ?></span>
	</td>
</tr>
</table>
<?php wp_nonce_field( 'wpks-nonce', 'wpks-nonce', false ) ?> 
<p class="submit"><input id="wpks-submit" type="submit" name="wpks-submit" class="button-primary wpks-button" value="<?php _e('Save Changes', _PLUGIN_NAME_); ?>" /></p>
</form>
</div>
<div class="clear"></div>
