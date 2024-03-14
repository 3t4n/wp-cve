<?php
/**
 * Client functions.
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Upstream_get_client_id
 */
function upstream_get_client_id() {
	$client_id = (int) upstream_project_client_id();

	if ( 0 === $client_id ) {
		$user_id   = upstream_current_user_id();
		$client_id = (int) upstream_get_users_client_id( $user_id );
	}

	return $client_id > 0 ? $client_id : null;
}

/**
 * Upstream_client_logo
 *
 * @param int $client_id Client id.
 */
function upstream_client_logo( $client_id = 0 ) {
	$logo_url = '';

	if ( 0 === (int) $client_id ) {
		$client_id = upstream_get_client_id();
	}

	if ( $client_id > 0 ) {
		global $wpdb, $table_prefix;

		$logo_url = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT meta_value
				FROM $wpdb->postmeta
				WHERE post_id = %s
				AND meta_key = %s",
				array(
					$client_id,
					'_upstream_client_logo',
				)
			)
		);
	}

	return apply_filters( 'upstream_client_logo', $logo_url, $client_id );
}

/**
 * Save post metadata when a post is saved.
 * Mainly used to update user ids
 *
 * @param int  $post_id The post ID.
 * @param post $post    The post object.
 * @param bool $update  Whether this is an existing post being updated or not.
 */
function upstream_update_client_meta_values( $post_id, $post, $update ) {
	$slug      = 'client';
	$post_data = wp_unslash( $_POST );
	$nonce     = isset( $post_data['upstream_admin_client_form_nonce'] ) ? $post_data['upstream_admin_client_form_nonce'] : null;

	// If this isn't a 'client' post, don't update it.
	if ( $slug !== $post->post_type ) {
		return;
	}

	// Verify nonce.
	if ( ! wp_verify_nonce( $nonce, 'upstream_admin_client_form' ) ) {
		return;
	}

	// update the overall progress of the project.
	if ( isset( $post_data['_upstream_client_users'] ) ) :

		$users = $post_data['_upstream_client_users']; // This is sanitized in the following lines.

		$i = 0;
		if ( $users && is_array( $users ) ) :
			foreach ( $users as $user ) {
				if ( ! is_array( $user ) || ! isset( $user['id'] ) || empty( $user['id'] ) || sanitize_text_field( $user['id'] ) === '' ) {
					$users[ $i ]['id'] = upstream_admin_set_unique_id();
				} else {
					// already sanity checked is_array($users[$i]) && isset($users[$i]['id']).
					$users[ $i ]['id'] = (int) $users[ $i ]['id'];
				}
				$i++;
			}
		else :
			$users = array();
		endif;

		update_post_meta( $post_id, '_upstream_client_users', $users );

	endif;
}
add_action( 'save_post', 'upstream_update_client_meta_values', 99999, 3 );

/**
 * Retrieve all Client Users associated with a given client.
 *
 * @since   1.11.0
 *
 * @param   int $client_id The reference id.
 *
 * @return  array|bool  Array in case of success or false in case the client_id is invalid.
 */
function upstream_get_client_users( $client_id ) {
	$client_id = (int) $client_id;
	if ( $client_id <= 0 ) {
		return false;
	}

	$users_list        = array();
	$client_users_list = array_filter( (array) get_post_meta( $client_id, '_upstream_new_client_users', true ) );
	if ( count( $client_users_list ) > 0 ) {
		$users_ids_list = array();
		foreach ( $client_users_list as $client_user ) {
			if ( isset( $client_user['user_id'] ) ) {
				$users_ids_list[] = (int) $client_user['user_id'];
			}
		}

		$rowset = (array) get_users(
			array(
				'fields'  => array( 'ID', 'display_name' ),
				'include' => $users_ids_list,
			)
		);

		foreach ( $rowset as $row ) {
			$users_list[ (int) $row->ID ] = array(
				'id'   => (int) $row->ID,
				'name' => $row->display_name,
			);
		}
	}

	return $users_list;
}

/**
 * Check if a given user is a Client User associated with a given client.
 *
 * @since   1.11.0
 *
 * @param   int $client_user_id The client user id.
 * @param   int $client_id      The client id.
 *
 * @return  bool|null   Bool or NULL in case the user is invalid.
 */
function upstream_do_client_user_belongs_to_client( $client_user_id, $client_id ) {
	$client_user_id = (int) $client_user_id;
	if ( $client_user_id <= 0 ) {
		return null;
	}

	$client_users = (array) upstream_get_client_users( $client_id );

	$client_user_belongs_to_client = isset( $client_users[ $client_user_id ] );

	return $client_user_belongs_to_client;
}

/**
 * Retrieve all client user permissions.
 *
 * @since   1.11.0
 *
 * @param   int $client_user_id The client user id.
 *
 * @return  array|bool  A permissions array or false if user doesn't exist.
 */
function upstream_get_client_user_permissions( $client_user_id ) {
	$client_user = new \WP_User( (int) $client_user_id );
	if ( 0 === $client_user->ID ) {
		return false;
	}

	$permissions = upstream_get_client_users_permissions();
	foreach ( $permissions as $permission_index => $permission ) {
		if ( isset( $client_user->caps[ $permission['key'] ] ) ) {
			$permission['value'] = $client_user->caps[ $permission['key'] ];
		} elseif ( isset( $client_user->allcaps[ $permission['key'] ] ) ) {
			$permission['value'] = $client_user->allcaps[ $permission['key'] ];
		}

		$permissions[ $permission_index ] = $permission;
	}

	return $permissions;
}
