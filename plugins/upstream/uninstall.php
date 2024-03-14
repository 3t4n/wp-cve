<?php
/**
 * Uninstall UpStream
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Load UpStream file.
require_once 'upstream.php';

global $wpdb, $wp_roles;

$options = (array) get_option( 'upstream_general' );
$remove  = ! empty( $options['remove_data'] ) && ! empty( $options['remove_data'][0] ) ? 'yes' === (string) $options['remove_data'][0] : false;

if ( $remove ) {
	/** Delete All the Custom Post Types */
	$upstream_taxonomies = array( 'project_category' );
	$upstream_post_types = array( 'project', 'client' );

	foreach ( $upstream_post_types as $wp_post_type ) {
		$upstream_taxonomies = array_merge( $upstream_taxonomies, get_object_taxonomies( $wp_post_type ) );
		$items               = get_posts(
			array(
				'post_type'   => $wp_post_type,
				'post_status' => 'any',
				'numberposts' => -1,
				'fields'      => 'ids',
			)
		);

		if ( $items ) {
			foreach ( $items as $item ) {
				wp_delete_post( $item, true );
			}
		}
	}

	/** Delete All the Terms & Taxonomies */
	foreach ( array_unique( array_filter( $upstream_taxonomies ) ) as $wp_tax ) {
		$terms = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN (%s) ORDER BY t.name ASC",
				$wp_tax
			)
		);

		// Delete Terms.
		if ( $terms ) {
			foreach ( $terms as $wp_term ) {
				$wpdb->delete( $wpdb->term_taxonomy, array( 'term_taxonomy_id' => $wp_term->term_taxonomy_id ) );
				$wpdb->delete( $wpdb->terms, array( 'term_id' => $wp_term->term_id ) );
			}
		}

		// Delete Taxonomies.
		$wpdb->delete( $wpdb->term_taxonomy, array( 'taxonomy' => $wp_tax ), array( '%s' ) );
	}

	/** Delete all the Plugin Options */
	delete_option( 'upstream_extensions' );
	delete_option( 'upstream_bugs' );
	delete_option( 'upstream_tasks' );
	delete_option( 'upstream_milestones' );
	delete_option( 'upstream_projects' );
	delete_option( 'upstream_general' );
	delete_option( 'upstream_version' );

	/** Delete Capabilities */
	$roles = new UpStream_Roles();
	$roles->remove_caps();

	/** Delete the Roles */
	$upstream_roles = array( 'upstream_manager', 'upstream_user', 'upstream_client_user' );
	foreach ( $upstream_roles as $wp_role ) {
		remove_role( $wp_role );
	}
}
