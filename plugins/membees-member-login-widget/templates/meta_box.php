<input type="hidden" name="members_only_nonce" id="members_only_nonce" value="<?php echo wp_create_nonce( 'members_only' ); ?>" />

<div id="members_only_container">
	<label>
		<input type="checkbox" name="members_only" id="members_only" value="true" <?php echo $is_members_only; ?> />
		<?php _e( 'Only accessible by members?', 'wpac' ); ?>
	</label>

	<div id="members_only_options" class="hide-if-js">
		<br /><strong><?php _e( 'Only Accessible By (Defaults to everyone):', 'wpac' ); ?></strong><br />

		<?php
		global $wp_roles;
		$roles = array_map('translate_user_role', $wp_roles->get_names());
		$checked_roles = (array) maybe_unserialize( get_post_meta( $post->ID, '_wpac_restricted_to', TRUE ) );

		if ( $post->post_status == 'auto-draft' && $post->post_type == 'post' ) {
			$checked_roles = get_option( 'wpac_posts_default_restricted_to', array() );
		} else if ( $post->post_status == 'auto-draft' ) {
			$checked_roles = get_option( 'wpac_pages_default_restricted_to', array() );
		}

		foreach ( $roles as $role => $label ) {
			if ( in_array( $role, $checked_roles ) ) {
				echo '<label><input type="checkbox" name="wpac_restricted_to[]" checked="checked" value="' . $role . '"> ' . $label . '</label><br>';
			} else {
				echo '<label><input type="checkbox" name="wpac_restricted_to[]" value="' . $role . '"> ' . $label . '</label><br>';
			}
		}

		$redirect_to = get_post_meta( $post->ID, '_wpac_members_redirect_to', TRUE );

		if ( $post->post_status == 'auto-draft' ) {
			$redirect_to = get_option( 'wpac_default_members_redirect', '' );
		}
		?>

		<br /><strong><?php _e( 'Redirect To (Leave empty for login page):', 'wpac' ); ?></strong><br />
		<input type="text" name="wpac_members_redirect_to" value="<?php echo $redirect_to; ?>" /><br />

		<br /><strong>Options</strong><br />
		<?php
		if ( $post->post_type == 'post' && $post->post_status == 'auto-draft' ) {
			$wpac_show_in_search = get_option( 'wpac_show_posts_in_search', 0 );
			$wpac_show_excerpt_in_search = get_option( 'wpac_show_post_excerpts_in_search', 0 );
		} else if ( $post->post_status == 'auto-draft' ) {
			$wpac_show_in_search = get_option( 'wpac_show_pages_in_search', 0 );
			$wpac_show_excerpt_in_search = get_option( 'wpac_show_page_excerpts_in_search', 0 );
		}
		?>
		<label><input type="checkbox" name="wpac_show_in_search" value="yes" <?php checked( $wpac_show_in_search, 1 ); ?> /> <?php _e( 'Show in search results?', 'wpac' ); ?><br /></label>
		<label><input type="checkbox" name="wpac_show_excerpt_in_search" value="yes"<?php checked( $wpac_show_excerpt_in_search, 1 ); ?> /> <?php _e( 'Show excerpt in search?', 'wpac' ); ?><br /></label>
	</div>
</div>

<div id="nonmembers_only_container">
	<label>
		<input type="checkbox" name="nonmembers_only" id="nonmembers_only" value="true" <?php echo $is_nonmembers_only; ?> />
		<?php _e( 'Only accessible by non-members?', 'wpac' ); ?>
	</label>

	<div id="nonmembers_only_options" class="hide-if-js">
		<?php $redirect_to = get_post_meta( $post->ID, '_wpac_nonmembers_redirect_to', true ); ?>

		<br /><strong><?php _e( 'Redirect To (Leave empty for home page):', 'wpac' ); ?> </strong><br />
		<input type="text" name="wpac_nonmembers_redirect_to" value="<?php echo $redirect_to; ?>" />
	</div>
</div>

<?php if ( $post->post_type == 'page' ) { ?>
<label>
	<input type="checkbox" name="pass_to_children" id="pass_to_children" value="true" <?php checked( $pass_to_children, 'true' ); ?> /> <?php _e( 'Apply options to children?', 'wpac' ); ?>
</label>
<?php } ?>

<script>
//$j = jQuery.noConflict();

jQuery('#members_only').change(function() {
	if (jQuery(this).is(":checked") == true) {
		jQuery('#nonmembers_only_container').hide('fast');
		jQuery('#members_only_options').show('fast');
	} else {
		jQuery('#nonmembers_only_container').show('fast');
		jQuery('#members_only_options').hide('fast');
	}
});

jQuery('#nonmembers_only').change(function() {
	if (jQuery(this).is(":checked") == true) {
		jQuery('#members_only_container').hide('fast');
		jQuery('#nonmembers_only_options').show('fast');
	} else {
		jQuery('#members_only_container').show('fast');
		jQuery('#nonmembers_only_options').hide('fast');
	}
});

if (jQuery('#nonmembers_only').is(":checked") == true) {
	jQuery('#members_only_container').hide('fast');
	jQuery('#nonmembers_only_options').show();
} else if (jQuery('#members_only').is(":checked") == true) {
	jQuery('#nonmembers_only_container').hide('fast');
	jQuery('#members_only_options').show();
}
</script>
