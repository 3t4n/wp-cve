<?php

function qem_add_role_caps() {
	qem_add_role();
	$roles = array( 'administrator', 'editor', 'event-manager' );
	foreach ( $roles as $item ) {
		$role = get_role( $item );
		if ( null !== $role ) {
			$role->add_cap( 'read' );
			$role->add_cap( 'read_event' );
			$role->add_cap( 'read_private_event' );
			$role->add_cap( 'edit_event' );
			$role->add_cap( 'edit_events' );
			$role->add_cap( 'edit_others_events' );
			$role->add_cap( 'edit_published_events' );
			$role->add_cap( 'publish_events' );
			$role->add_cap( 'delete_events' );
			$role->add_cap( 'delete_others_events' );
			$role->add_cap( 'delete_private_events' );
			$role->add_cap( 'delete_published_events' );
			$role->add_cap( 'manage_categories' );
			$role->add_cap( 'upload_files' );
			$role->add_cap( 'edit_posts' );
		}
	}
}

function qem_users( $output ) {
	global $post;
	if ( $post->post_type == 'event' ) {
		$users  = get_users();
		$output = "<select id='post_author_override' name='post_author_override' class=''>";
		foreach ( $users as $user ) {
			$sel    = ( $post->post_author == $user->ID ) ? "selected='selected'" : '';
			$output .= '<option value="' . $user->ID . '"' . $sel . '>' . $user->user_login . '</option>';
		}
		$output .= "</select>";
	}

	return $output;
}

function qem_add_role() {
	remove_role( 'event-manager' );
	add_role(
		'event-manager',
		'Event Manager',
		array(
			'read'           => true,
			'edit_posts'     => false,
			'edit_event'     => true,
			'edit_events'    => true,
			'publish_events' => true,
			'delete_events'  => true
		)
	);
}