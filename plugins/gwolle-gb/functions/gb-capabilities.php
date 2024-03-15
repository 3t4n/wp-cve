<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Use a custom capability for 'moderate_comments' and 'upload_files'.
 * Add them to the corresponding roles.
 * Checked with WordPress 6.1.
 *
 * @since 4.4.0
 */
function gwolle_gb_custom_capabilities() {

	$role = get_role( 'administrator' );
	if ( is_object( $role ) && is_a( $role, 'WP_Role' ) ) {
		$role->add_cap( 'gwolle_gb_upload_files', true );
		$role->add_cap( 'gwolle_gb_moderate_comments', true );
	}

	$role = get_role( 'editor' );
	if ( is_object( $role ) && is_a( $role, 'WP_Role' ) ) {
		$role->add_cap( 'gwolle_gb_upload_files', true );
		$role->add_cap( 'gwolle_gb_moderate_comments', true );
	}

	$role = get_role( 'author' );
	if ( is_object( $role ) && is_a( $role, 'WP_Role' ) ) {
		$role->add_cap( 'gwolle_gb_upload_files', true );
		//$role->add_cap( 'gwolle_gb_moderate_comments', true ); // nope :)
	}

}
// priority must be after the initial role definition.
add_action( 'init', 'gwolle_gb_custom_capabilities', 11 );

