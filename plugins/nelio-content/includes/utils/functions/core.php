<?php
/**
 * Nelio Content core functions.
 *
 * General core functions available on both the front-end and admin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils/functions
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * Returns this site's ID.
 *
 * @return string This site's ID. This option is used for accessing AWS.
 *
 * @since 1.0.0
 */
function nc_get_site_id() {

	return get_option( 'nc_site_id', false );

}//end nc_get_site_id()

/**
 * Returns the limits the plugin has, based on the current subscription and so on.
 *
 * @return array the limits the plugin has.
 *
 * @since 1.0.0
 */
function nc_get_site_limits() {

	return wp_parse_args(
		get_option( 'nc_site_limits', array() ),
		array(
			'maxAutomationGroups'   => 1,
			'maxProfiles'           => -1,
			'maxProfilesPerNetwork' => 1,
		)
	);

}//end nc_get_site_limits()

/**
 * Returns the reference whose ID is the given ID.
 *
 * @param integer $id The ID of the reference.
 *
 * @return Nelio_Content_Reference|boolean The reference with the given ID or
 *               false if such a reference does not exist.
 *
 * @since  1.0.0
 */
function nc_get_reference( $id ) {

	$post = get_post( $id );
	if ( $post ) {
		return new Nelio_Content_Reference( $post );
	} else {
		return false;
	}//end if

}//end nc_get_reference()

/**
 * Returns the reference whose URL is the given URL.
 *
 * @param string $url The URL of the reference we want to retrieve.
 *
 * @return Nelio_Content_Reference|boolean The reference with the given URL or
 *               false if such a reference does not exist.
 *
 * @since  1.0.0
 */
function nc_get_reference_by_url( $url ) {

	global $post;
	$result = false;

	// Look for an existing reference with the given URL.
	$args  = array(
		'post_type'   => 'nc_reference',
		'post_parent' => 0,
		'post_status' => 'any',
		'meta_key'    => '_nc_url', // phpcs:ignore
		'meta_value'  => $url,      // phpcs:ignore
	);
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {

		while ( $query->have_posts() ) {
			$query->the_post();
			$result = new Nelio_Content_Reference( $post );
			break;
		}//end while
	}//end if

	wp_reset_postdata();

	// If we didn't find the reference, let's check if it's an internal link.
	if ( ! $result ) {

		$reference = nc_url_to_postid( $url ); // phpcs:ignore
		if ( $reference ) {
			$result = new Nelio_Content_Reference( $reference );
			$result->set_url( $url );
		}//end if
	}//end if

	return $result;

}//end nc_get_reference_by_url()

/**
 * Creates a new reference with the given URL.
 *
 * If a reference with the given URL already exists, that reference will be returned.
 *
 * @param string $url The URL of the (possibly) new reference.
 *
 * @return Nelio_Content_Reference|boolean The new reference (or an
 *              existing one, if there already existed one reference
 *              with the given URL). If the reference didn't exist and
 *              couldn't be created, `false` is returned.
 *
 * @since  1.0.0
 */
function nc_create_reference( $url ) {

	$reference = nc_get_reference_by_url( $url );

	if ( empty( $reference ) ) {

		$reference = wp_insert_post(
			array(
				'post_title'  => '',
				'post_type'   => 'nc_reference',
				'post_status' => 'nc_pending',
			)
		);

		if ( $reference && ! is_wp_error( $reference ) ) {
			// We add the URL using the meta directly, or else the status would be
			// changed from "pending" to "improvable", because all Reference setters
			// may update its status.
			update_post_meta( $reference, '_nc_url', $url );
			$reference = new Nelio_Content_Reference( $reference );
		} else {
			$reference = false;
		}//end if
	}//end if

	return $reference;

}//end nc_create_reference()

/**
 * Returns a list of all the references related to a given post.
 *
 * @param integer|WP_Post $post_id The post whose references will be returned.
 * @param string          $status  Optional. Either `discarded`, `included`, or `suggested`.
 *                                 It specifies which references have to be returned.
 *                                 Default: `included`.
 *
 * @return array a list of all the references related to the given post.
 *
 * @since  1.0.0
 */
function nc_get_post_reference( $post_id, $status = 'included' ) {

	// Making sure we're using the post's ID.
	if ( $post_id instanceof WP_Post ) {
		$post_id = $post_id->ID;
	}//end if

	switch ( $status ) {

		case 'discarded':
			$arr = get_post_meta( $post_id, '_nc_discarded_reference', false );
			$arr = ! empty( $arr ) ? $arr : array();
			return array_map( 'absint', $arr );

		case 'suggested':
			$arr = get_post_meta( $post_id, '_nc_suggested_reference', false );
			$arr = ! empty( $arr ) ? $arr : array();
			return array_map( 'absint', $arr );

		case 'included':
		default:
			$arr = get_post_meta( $post_id, '_nc_included_reference', false );
			$arr = ! empty( $arr ) ? $arr : array();
			return array_map( 'absint', $arr );

	}//end switch

}//end nc_get_post_reference()

/**
 * Adds a reference to the given post, which means the reference appears in post's content.
 *
 * @param integer|WP_Post $post_id      The post in which a certain reference has to be added.
 * @param integer|WP_Post $reference_id The reference to be added.
 *
 * @since  1.0.0
 */
function nc_add_post_reference( $post_id, $reference_id ) {

	// Making sure we're using the post's ID.
	if ( $post_id instanceof WP_Post ) {
		$post_id = $post_id->ID;
	}//end if

	// Making sure we're using the reference's ID.
	if ( $reference_id instanceof WP_Post ) {
		$reference_id = $reference_id->ID;
	}//end if

	// If the reference has been added to the post, it can't be "discarded".
	delete_post_meta( $post_id, '_nc_discarded_reference', $reference_id );
	nc_add_post_meta_once( $post_id, '_nc_included_reference', $reference_id );

}//end nc_add_post_reference()

/**
 * Removes a reference from the list of "included references" of a certain post.
 *
 * @param integer|WP_Post $post_id      The post from which a certain reference has to be deleted.
 * @param integer|WP_Post $reference_id The reference to be deleted.
 *
 * @since  1.0.0
 */
function nc_delete_post_reference( $post_id, $reference_id ) {

	// Making sure we're using the post's ID.
	if ( $post_id instanceof WP_Post ) {
		$post_id = $post_id->ID;
	}//end if

	// Making sure we're using the reference's ID.
	if ( $reference_id instanceof WP_Post ) {
		$reference_id = $reference_id->ID;
	}//end if

	delete_post_meta( $post_id, '_nc_included_reference', $reference_id );
	nc_remove_unused_reference( $reference_id );

}//end nc_delete_post_reference()

/**
 * Adds a reference as a suggestion of our post.
 *
 * @param integer|WP_Post $post_id      The post in which a certain reference has been suggested.
 * @param integer|WP_Post $reference_id The suggested reference.
 * @param integer         $advisor      The user ID who's suggesting this reference.
 *                           If the advisor is Nelio Content itself, the ID is 0.
 *
 * @since  1.0.0
 */
function nc_suggest_post_reference( $post_id, $reference_id, $advisor ) {

	// Making sure we're using the post's ID.
	if ( $post_id instanceof WP_Post ) {
		$post_id = $post_id->ID;
	}//end if

	// Making sure we're using the reference's ID.
	if ( $reference_id instanceof WP_Post ) {
		$reference_id = $reference_id->ID;
	}//end if

	// After (re)suggesting a reference, it can't be "discarded". It can, however,
	// be also included, so there's no need to update the "included" meta.
	delete_post_meta( $post_id, '_nc_discarded_reference', $reference_id );
	nc_add_post_meta_once( $post_id, '_nc_suggested_reference', $reference_id );

	$meta = array(
		'advisor' => $advisor,
		'date'    => time(),
	);
	add_post_meta( $post_id, '_nc_suggested_reference_' . $reference_id . '_meta', $meta, true );

}//end nc_suggest_post_reference()

/**
 * Removes a reference from the list of suggested references in a post.
 *
 * @param integer|WP_Post $post_id      The post from which a certain suggested reference has been discarded.
 * @param integer|WP_Post $reference_id The discarded reference.
 *
 * @since  1.0.0
 */
function nc_discard_post_reference( $post_id, $reference_id ) {

	// Making sure we're using the reference's ID.
	if ( $reference_id instanceof WP_Post ) {
		$reference_id = $reference_id->ID;
	}//end if

	// Making sure we're using the post's ID.
	if ( $post_id instanceof WP_Post ) {
		$post_id = $post_id->ID;
	}//end if

	// Add the reference in the discarded list.
	nc_add_post_meta_once( $post_id, '_nc_discarded_reference', $reference_id );

	// And remove it from any other list.
	nc_delete_post_reference( $post_id, $reference_id );
	delete_post_meta( $post_id, '_nc_suggested_reference', $reference_id );
	delete_post_meta( $post_id, '_nc_suggested_reference_' . $reference_id . '_meta' );

}//end nc_discard_post_reference()

/**
 * Returns meta information about a suggested reference, such as who suggested
 * it in a certain post.
 *
 * @param integer|WP_Post $post_id      The post for which the reference was suggested.
 * @param integer|WP_Post $reference_id The reference from which we want to obtain its meta information.
 *
 * @return array the available meta information about the suggested reference for the given post.
 *
 * @since  2.0.0
 */
function nc_get_suggested_reference_meta( $post_id, $reference_id ) {

	$result = get_post_meta( $post_id, '_nc_suggested_reference_' . $reference_id . '_meta', true );

	return $result;

}//end nc_get_suggested_reference_meta()

/**
 * Removes a reference from the database, iff it's not used by any post.
 *
 * @param integer|WP_Post $reference_id The reference to be deleted.
 *
 * @since  1.3.4
 */
function nc_remove_unused_reference( $reference_id ) {

	global $wpdb;

	// Making sure we're using the reference's ID.
	if ( $reference_id instanceof WP_Post ) {
		$reference_id = $reference_id->ID;
	}//end if

	$reference = get_post( $reference_id );
	if ( 'nc_reference' !== $reference->post_type ) {
		return;
	}//end if

	$posts_with_the_ref = $wpdb->get_var( // phpcs:ignore
		$wpdb->prepare(
			"SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_value = %s AND meta_key IN ( %s, %s, %s )",
			$reference_id,
			'_nc_included_reference',
			'_nc_suggested_reference',
			'_nc_discarded_reference'
		)
	);

	if ( $posts_with_the_ref ) {
		return;
	}//end if

	wp_delete_post( $reference_id );

}//end nc_remove_unused_reference()
