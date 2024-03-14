<?php
/**
 * Destroy/tear down zoom addon setup.
 *
 * @since 1.8.3
 */

$instructor_caps = array(
	'publish_google_classrooms',
	'edit_google_classrooms',
	'edit_private_google_classroom',
	'edit_published_google_classroom',
	'delete_google_classroom',
	'delete_published_google_classroom',
	'delete_privateGoogleclassroom',
);

$instructor = get_role( 'masteriyo_instructor' );
if ( $instructor ) {
	foreach ( $instructor_caps as $cap ) {
		$instructor->remove_cap( $cap );
	}
}

$manager_caps = array_merge(
	$instructor_caps,
	array( 'edit_others_google_classroom', 'delete_others_google_classroom' )
);

$manager = get_role( 'masteriyo_manager' );
if ( $manager ) {
	foreach ( $manager_caps as $cap ) {
		$manager->remove_cap( $cap );
	}
}

$administrator_caps = $manager_caps;
$administrator      = get_role( 'administrator' );
if ( $administrator ) {
	foreach ( $administrator_caps as $cap ) {
		$administrator->remove_cap( $cap );
	}
}
