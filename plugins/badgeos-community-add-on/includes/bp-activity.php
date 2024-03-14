<?php
/**
 * BuddyPress Activity Functions
 *
 * @package BadgeOS Community
 * @subpackage Activity
 * @author LearningTimes, LLC
 * @license http://www.gnu.org/licenses/agpl.txt GNU AGPL v3.0
 * @link https://credly.com
 */

/**  
 * Create BuddyPress Activity when a user earns an achievement.
 *
 * @since 1.0.0
 */
function badgeos_award_achievement_bp_activity( $user_id, $achievement_id, $this_trigger, $site_id, $args ) {

	if ( ! $user_id || ! $achievement_id )
		return false;

	$post = get_post( $achievement_id );
	$type = $post->post_type;

	// Don't make activity posts for step post type
	$badgeos_settings = ( $exists = get_option( 'badgeos_settings' ) ) ? $exists : array(); 
	if ( trim( $badgeos_settings['achievement_step_post_type'] ) == $type ) {
		return false;
	}

	// Check if option is on/off
	$badgeos_settings = ( $exists = get_option( 'badgeos_settings' ) ) ? $exists : array();
	$achievement_type = get_page_by_title( str_replace('-',' ', $type), 'OBJECT', trim( $badgeos_settings['achievement_main_post_type'] ) );
	$can_bp_activity = get_post_meta( $achievement_type->ID, '_badgeos_create_bp_activty', true );
	if ( ! $can_bp_activity ) {
		return false;
	}

	// Grab the singular name for our achievement type
	$post_type_singular_name = strtolower( get_post_type_object( $type )->labels->singular_name );

	// Setup our entry content
	$content = '<div class="badgeos-achievements-list-item user-has-earned">';
	$content .= '<div class="badgeos-item-image"><a href="'. get_permalink( $achievement_id ) . '">' . badgeos_get_achievement_post_thumbnail( $achievement_id ) . '</a></div>';
	$content .= '<div class="badgeos-item-description">' . wpautop( $post->post_excerpt ) . '</div>';
	$content .= '</div>';

	# Bypass checking our activity items from moderation, as we know we are legit.
	add_filter( 'bp_bypass_check_for_moderation', '__return_true' );

	// Insert the activity
	bp_activity_add( apply_filters(
		'badgeos_award_achievement_bp_activity_details',
		array(
			'action'       => sprintf( __( '%1$s earned the %2$s achievement: %3$s', 'badgeos-community' ), bp_core_get_userlink( $user_id ), $post_type_singular_name, '<a href="' . get_permalink( $achievement_id ) . '">' . $post->post_title . '</a>' ),
			'content'      => $content,
			'component'    => 'badgeos',
			'type'         => 'activity_update',
			'primary_link' => get_permalink( $achievement_id ),
			'user_id'      => $user_id,
			'item_id'      => $achievement_id,
		),
		$user_id,
		$achievement_id,
		$this_trigger,
		$site_id,
		$args
	) );

}
add_action( 'badgeos_award_achievement', 'badgeos_award_achievement_bp_activity', 10, 5 );

/** 
 * Create BuddyPress Activity when a user earns a rank.
 */
function bp_badgeos_after_award_rank( $user_id, $rank_id, $rank_type, $credit_id, $credit_amount, $admin_id, $this_trigger ) {
	
	if ( ! $user_id || ! $rank_id )
		return false;
	
	$site_id = get_current_blog_id();

	$post = get_post( $rank_id );
	$post_type = $post->post_type;
	
	$settings = get_option( 'badgeos_settings' );
	// Don't make activity posts for step post type
	if ( $settings['ranks_step_post_type'] == $post_type ) {
		return false;
	}
	
	// Check if option is on/off
	$rank_type = get_page_by_title( $post_type, 'OBJECT', $settings['ranks_main_post_type'] );
	$can_bp_activity = get_post_meta( $rank_type->ID, '_badgeos_create_bp_activty', true );
	
	if ( ! $can_bp_activity ) {
		return false;
	}
	
	// Grab the singular name for our achievement type
	$post_type_singular_name = strtolower( get_post_type_object( $post_type )->labels->singular_name );

	// Setup our entry content
	$content = '<div class="badgeos-ranks-list-item user-has-earned">';
	$content .= '<div class="badgeos-item-image"><a href="'. get_permalink( $rank_id ) . '">' . badgeos_get_rank_image( $rank_id ) . '</a></div>';
	$content .= '<div class="badgeos-item-description">' . wpautop( $post->post_excerpt ) . '</div>';
	$content .= '</div>';
	
	# Bypass checking our activity items from moderation, as we know we are legit.
	add_filter( 'bp_bypass_check_for_moderation', '__return_true' );
	
	// Insert the activity
	bp_activity_add( apply_filters(
		'badgeos_award_rank_bp_activity_details',
		array(
			'action'       => sprintf( __( '%1$s earned the %2$s rank: %3$s', 'badgeos-community' ), bp_core_get_userlink( $user_id ), $post_type_singular_name, '<a href="' . get_permalink( $rank_id ) . '">' . $post->post_title . '</a>' ),
			'content'      => $content,
			'component'    => 'badgeos',
			'type'         => 'activity_update',
			'primary_link' => get_permalink( $rank_id ),
			'user_id'      => $user_id,
			'item_id'      => $rank_id,
		),
		$user_id,
		$rank_id,
		$this_trigger,
		$site_id,
		$args
	) );
}
add_action( 'badgeos_after_award_rank', 'bp_badgeos_after_award_rank', 10, 7 );

/** 
 * Create BuddyPress Activity when a user earns a point.
 */
function bp_badgeos_after_award_points( $user_id, $credit_id, $achievement_id, $type, $new_points, $this_trigger, $step_id ) {
	if ( ! $user_id || ! $credit_id )
		return false;
	
	$site_id = get_current_blog_id();	
	$post = get_post( $credit_id );
	$post_type = $post->post_type;
	
	// Don't make activity posts for step post type
	$settings = get_option( 'badgeos_settings' );
	if ( $settings['points_main_post_type'] != $post_type ) {
		return false;
	}

	// Check if option is on/off
	$can_bp_activity = get_post_meta( $post->ID, '_badgeos_create_bp_activty', true );

	if ( ! $can_bp_activity ) {
		return false;
	}
	
	// Grab the singular name for our achievement type
	$post_type_singular_name = strtolower( get_post_type_object( $post_type )->labels->singular_name );

	// Setup our entry content
	$content = '<div class="badgeos-points-list-item user-has-earned">';
	$content .= '<div class="badgeos-item-image"><a href="'. get_permalink( $credit_id ) . '">' . get_the_post_thumbnail( $credit_id, 'thumbnail' ) . '</a></div>';
	$content .= '<div class="badgeos-item-description">' . wpautop( $post->post_excerpt ) . '</div>';
	$content .= '</div>';

	# Bypass checking our activity items from moderation, as we know we are legit.
	add_filter( 'bp_bypass_check_for_moderation', '__return_true' );

	// Insert the activity
	if( $type == 'Award' ) {
		bp_activity_add( apply_filters(
			'badgeos_award_points_bp_activity_details',
			array(
				'action'       => sprintf( __( '%1$s earned the %2$s points: %3$s', 'badgeos-community' ), bp_core_get_userlink( $user_id ), $post_type_singular_name, '<a href="' . get_permalink( $credit_id ) . '">'.$new_points." ".$post->post_title . '</a>' ),
				'content'      => $content,
				'component'    => 'badgeos',
				'type'         => 'activity_update',
				'primary_link' => get_permalink( $credit_id ),
				'user_id'      => $user_id,
				'item_id'      => $credit_id,
			),
			$user_id, $credit_id, $achievement_id, $type, $new_points, $this_trigger, $step_id, $site_id
		) );
	} else {
		bp_activity_add( apply_filters(
			'badgeos_deduct_points_bp_activity_details',
			array(
				'action'       => sprintf( __( '%1$s points are deducted from %2$s : %3$s', 'badgeos-community' ), $post_type_singular_name, bp_core_get_userlink( $user_id ), '<a href="' . get_permalink( $credit_id ) . '">'.$new_points." ".$post->post_title.'</a>' ),
				'content'      => $content,
				'component'    => 'badgeos',
				'type'         => 'activity_update',
				'primary_link' => get_permalink( $credit_id ),
				'user_id'      => $user_id,
				'item_id'      => $credit_id,
			),
			$user_id, $credit_id, $achievement_id, $type, $new_points, $this_trigger, $step_id, $site_id
		) );
	}
	
}
add_action( 'badgeos_after_award_points', 'bp_badgeos_after_award_points', 10, 7 );

/**
 * Filter activity allowed html tags to allow divs with classes and ids.
 *
 * @since 1.0.0
 */
function badgeos_bp_activity_allowed_tags( $activity_allowedtags ) {

	$activity_allowedtags['div'] = array();
	$activity_allowedtags['div']['id'] = array();
	$activity_allowedtags['div']['class'] = array();

	return $activity_allowedtags;
}
add_filter( 'bp_activity_allowed_tags', 'badgeos_bp_activity_allowed_tags' );


/**
 * Adds meta box to achievement types for turning on/off BuddyPress activity posts when a user earns an achievement
 *
 * @since 1.0.0
 */
function badgeos_bp_custom_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_badgeos_';

	// Setup our $post_id, if available
	$post_id = isset( $_GET['post'] ) ? $_GET['post'] : 0;
	
	$badgeos_settings = ( $exists = get_option( 'badgeos_settings' ) ) ? $exists : array(); 
	$cmb_obj = new_cmb2_box( array(
        'id'            => 'bp_achievement_type_data',
        'title'         => __( 'BuddyPress Member Activity', 'badgeos-community' ),
        'object_types'  => array( trim( $badgeos_settings['achievement_main_post_type'] ) ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
    ) );

	$cmb_obj->add_field(array(
        'name'    => __( 'Activity Posts', 'badgeos-community' ),
        'desc' 	  => ' '.__( 'When a user earns any achievements of this type create an activity entry on their profile.', 'badgeos-community' ),
        'id'   => $prefix . 'create_bp_activty',
		'type' => 'checkbox',
	));
	
	$cmb_obj->add_field(array(
		'name' => __( 'Profile Achievements', 'badgeos-community' ),
		'desc' => ' '.__( 'Display earned achievements of this type in the user profile "Achievements" section.', 'badgeos-community' ),
		'id'   => $prefix . 'show_bp_member_menu',
		'type' => 'checkbox',
	));
}
add_filter( 'cmb2_admin_init', 'badgeos_bp_custom_metaboxes' );

/**
 * Adds meta box to rank types for turning on/off BuddyPress activity posts when a user earns a rank
 *
 * @since 1.0.0
 */
function badgeos_bp_custom_rank_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_badgeos_';

	$settings = ( $exists = get_option( 'badgeos_settings' ) ) ? $exists : array();
	// Setup our $post_id, if available
	$post_id = isset( $_GET['post'] ) ? $_GET['post'] : 0;

	$cmb_obj = new_cmb2_box( array(
        'id'            => 'bp_rank_type_data',
        'title'         => __( 'BuddyPress Member Activity', 'badgeos-community' ),
        'object_types'  => array( $settings['ranks_main_post_type'] ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
    ) );

	$cmb_obj->add_field(array(
        'name'    => __( 'Activity Posts', 'badgeos-community' ),
        'desc' 	  => ' '.__( 'When a user earns any achievements of this type create an activity entry on their profile.', 'badgeos-community' ),
        'id'   => $prefix . 'create_bp_activty',
		'type' => 'checkbox',
	));
	
	$cmb_obj->add_field(array(
		'name' => __( 'Profile Ranks', 'badgeos-community' ),
		'desc' => ' '.__( 'Display earned ranks of this type in the user profile "Ranks" section.', 'badgeos-community' ),
		'id'   => $prefix . 'show_bp_member_menu_ranks',
		'type' => 'checkbox',
	));
}
add_filter( 'cmb2_admin_init', 'badgeos_bp_custom_rank_metaboxes' );

/**
 * Adds meta box to point types for turning on/off BuddyPress activity posts when a user earns a point
 *
 * @since 1.0.0
 */
function badgeos_bp_custom_point_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_badgeos_';

	// Setup our $post_id, if available
	$post_id = isset( $_GET['post'] ) ? $_GET['post'] : 0;
	$settings = ( $exists = get_option( 'badgeos_settings' ) ) ? $exists : array();

	$cmb_obj = new_cmb2_box( array(
        'id'            => 'bp_point_types_data',
        'title'         => __( 'BuddyPress Member Activity', 'badgeos-community' ),
        'object_types'  => array( $settings['points_main_post_type'] ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
    ) );

	$cmb_obj->add_field(array(
        'name'    => __( 'Activity Posts', 'badgeos-community' ),
        'desc' 	  => ' '.__( 'When a user earns any point of this type create an activity entry on their profile.', 'badgeos-community' ),
        'id'   => $prefix . 'create_bp_activty',
		'type' => 'checkbox',
	));
	
}
add_filter( 'cmb2_admin_init', 'badgeos_bp_custom_point_metaboxes' );