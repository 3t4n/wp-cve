<?php
/**
 * Class to serve notification Calls.
 *
 * @since    1.0.0
 * @author   Wbcom Designs
 * @package BuddyPress_Member_Reviews
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function bupr_filter_notifications_get_registered_components( $component_names = array() ) {

			// Force $component_names to be an array.
	if ( ! is_array( $component_names ) ) {
			$component_names = array();
	}

			// Add 'buddypress_member_review' component to registered components array.
			array_push( $component_names, 'buddypress_member_review' );

			// Return component's with 'buddypress_member_review' appended.
			return $component_names;
}
add_filter( 'bp_notifications_get_registered_components', 'bupr_filter_notifications_get_registered_components', 10 );



add_filter( 'bp_notifications_get_notifications_for_user', 'bupr_add_review_notification_format', 10, 7 );
/**
 * Formatting notifications for review when added.
 *
 * @since    1.0.0
 * @access   public
 * @param    string $action     Action.
 * @param    string $item_id    Item id.
 * @param    string $secondary_item_id       Secondary item id.
 * @param    string $format        Format.
 * @author   Wbcom Designs
 */
function bupr_add_review_notification_format( $action, $item_id, $secondary_item_id, $total_items, $format = 'string', $component_action_name, $component_name ) {
	global $bp, $bupr;

	if ( 'bupr_add_review_action' === $component_action_name ) {
		$anonymous_review = get_post_meta( $item_id, 'bupr_anonymous_review_post', true );
		$user_name        = '';
		if ( 'yes' === $anonymous_review ) {
			$user_name = __( 'An anonymous user', 'bp-member-reviews' );
		} else {
			$post_author_id = get_post_field( 'post_author', $item_id );
			$admin_info     = get_userdata( $secondary_item_id );
			if ( $admin_info ) {
				$admin_name = $admin_info->user_nicename;
			} else {
				$admin_name = '';
			}

			$user_info = get_userdata( $post_author_id );

			if ( $user_info ) {
				$user_name = $user_info->display_name;
			}
		}

		$user_id              = bp_loggedin_user_id();
		// Check if the BuddyPress version function exists and compare the version
		if (function_exists('bp_get_version') && version_compare(bp_get_version(), '12.0.0', '>=')) {
			// Use bp_members_get_user_url() for BuddyPress v12.0.0 and above
			$notification_link = bp_members_get_user_url($user_id) . strtolower($bupr['review_label_plural']) . '/view/' . $item_id;
		} else {
			// Use bp_core_get_user_domain() for older versions
			$notification_link = bp_core_get_user_domain($user_id) . strtolower($bupr['review_label_plural']) . '/view/' . $item_id;
		}

		/* translators: %s: */
		$notification_title   = sprintf( esc_html__( 'A new %s posted.', 'bp-member-reviews' ), esc_html( $bupr['review_label'] ) );
		/* translators: %s: */
		$notification_content = sprintf( esc_html__( '%1$s posted a %2$s.', 'bp-member-reviews' ), esc_html( $user_name ), esc_html( strtolower( $bupr['review_label'] ) ) );		

		if ( 'string' === $format ) {
			$return = sprintf( "<a href='%s' title='%s'>%s</a>", esc_url( $notification_link ), esc_attr( $notification_title ), esc_html( $notification_content ) );
		} else {
			$return = array(
				'text' => $notification_content,
				'link' => $notification_link,
			);
		}

		return apply_filters( 'bupr_add_review_notification_format', $return, $user_id, $format );

	}
	return $action;
}

/**
 * Hooked into the new reply function, this notification action is responsible
 * for notifying topic and hierarchical reply authors of topic replies.
 *
 * @param integer $reply_id
 * @param integer $topic_id
 * @param integer $forum_id
 * @param boolean $anonymous_data
 * @param integer $author_id
 * @param boolean $is_edit
 * @param integer $reply_to
 * @return void
 */
function bupr_buddypress_add_notification( $bupr_memberid, $review_id ) {

	// Bail if somehow this is hooked to an edit action.
	if ( ! empty( $is_edit ) ) {
			return;
	}

	// Get autohr information.
	$current_user = wp_get_current_user();
	$member_id    = $current_user->ID;

	// Get some reply information.
		$args = array(
			'user_id'           => $bupr_memberid,
			'item_id'           => $review_id,
			'secondary_item_id' => $bupr_memberid,
			'component_name'    => 'buddypress_member_review',
			'component_action'  => 'bupr_add_review_action',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
			'allow_duplicate'   => true,
		);
		bp_notifications_add_notification( $args );

}
add_action( 'bupr_sent_review_notification', 'bupr_buddypress_add_notification', 10, 2 );

