<?php

use Dev4Press\Plugin\GDPOL\Basic\Poll;
use Dev4Press\v43\Core\Quick\Arr;
use Dev4Press\v43\Core\Quick\WPR;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpol_get_topic_poll_id( $topic_id = 0 ) : int {
	$topic_id = bbp_get_topic_id( $topic_id );

	$poll_id = get_post_meta( $topic_id, '_bbp_topic_poll_id', true );

	return empty( $poll_id ) ? 0 : absint( $poll_id );
}

function gdpol_topic_has_poll( $topic_id = 0 ) : bool {
	return gdpol_get_topic_poll_id( $topic_id ) > 0;
}

function gdpol_user_can_create_poll( $user_id = 0 ) : bool {
	$can     = false;
	$user_id = $user_id == 0 ? bbp_get_current_user_id() : $user_id;

	if ( gdpol_settings()->get( 'global_cap_check' ) == 'cap' ) {
		$can = user_can( $user_id, 'gdpol_create_poll' );
	} else if ( $user_id > 0 ) {
		$user = wp_get_current_user();

		if ( $user instanceof WP_User ) {
			$roles = gdpol_settings()->get( 'global_user_roles' );

			$matched = array_intersect( $user->roles, $roles );
			$can     = ! empty( $matched );
		}
	}

	return (bool) apply_filters( 'gdpol_user_can_create_poll', $can, $user_id );
}

function gdpol_init_poll_from_topic( $topic_id = 0 ) {
	$topic_id = bbp_get_topic_id( $topic_id );

	if ( gdpol_topic_has_poll( $topic_id ) ) {
		$_poll_id = absint( gdpol_get_topic_poll_id( $topic_id ) );

		gdpol_init_poll( $_poll_id );
	}

	return null;
}

function gdpol_init_poll( $poll_id ) {
	gdpol()->init_poll( $poll_id );
}

function gdpol_get_poll() : Poll {
	return gdpol()->poll();
}

/**
 * @return \Dev4Press\Plugin\GDPOL\Basic\Poll|false|\WP_Error
 */
function gdpol_get_poll_from_topic_id( int $topic_id ) {
	if ( gdpol_topic_has_poll( $topic_id ) ) {
		$poll_id = gdpol_get_topic_poll_id( $topic_id );

		return Poll::load( $poll_id );
	}

	return false;
}

function gdpol_bbpress_forums_list() : array {
	$_base_forums = get_posts( array(
		'post_type'   => bbp_get_forum_post_type(),
		'numberposts' => - 1,
	) );

	$forums = array();

	foreach ( $_base_forums as $forum ) {
		$forums[ $forum->ID ] = (object) array(
			'id'     => $forum->ID,
			'url'    => get_permalink( $forum->ID ),
			'parent' => $forum->post_parent,
			'title'  => $forum->post_title,
		);
	}

	return $forums;
}

function gdpol_integrate_poll_icon() {
	gdpol()->bbpress()->show_poll_icon();
}

function gdpol_integrate_poll_in_topic() {
	gdpol()->bbpress()->show_poll_in_topic();
}

function gdpol_integrate_form_in_topic() {
	gdpol()->bbpress()->show_form_in_topic();
}
