<?php
/**
 * Custom Achievement Rules
 *
 * @package BadgeOS Community
 * @subpackage Achievements
 * @author LearningTimes, LLC 
 * @license http://www.gnu.org/licenses/agpl.txt GNU AGPL v3.0
 * @link https://credly.com
 */

/**
 * Load up our community triggers so we can add actions to them
 *
 * @since 1.0.0
 */
function badgeos_bp_load_community_triggers() {

	/**
	 * Grab our community triggers
	 */
	$community_triggers = $GLOBALS['badgeos_community']->community_triggers;
	if ( !empty( $community_triggers ) ) {
		foreach ( $community_triggers as $optgroup_name => $triggers ) {
			foreach ( $triggers as $trigger_hook => $trigger_name ) {
				add_action( $trigger_hook, 'badgeos_bp_trigger_event', 10, 10 );
				add_action( $trigger_hook, 'badgeos_bp_trigger_award_points_event', 10, 10 );
				add_action( $trigger_hook, 'badgeos_bp_trigger_deduct_points_event', 10, 10 );
				add_action( $trigger_hook, 'badgeos_bp_trigger_ranks_event', 10, 10 );
			}
		}
	}

}
add_action( 'init', 'badgeos_bp_load_community_triggers' );

/**
 * Count a user's relevant actions for a given step
 *
 * @param  integer $activities count that specifies count if applied on previous filters
 * @param  integer $user_id The given user's ID
 * @param  integer $step_id The Current Step ID
 *
 * @return integer $activities The activity count of applied trigger
 */
function badgeos_bp_step_activity( $return, $user_id, $step_id, $this_trigger, $site_id, $arg ) {
	
	$badgeos_settings = ( $exists = get_option( 'badgeos_settings' ) ) ? $exists : array();
	if ( trim( $badgeos_settings['achievement_step_post_type'] ) != get_post_type( $step_id ) )
		return $return;

	// Grab the requirements for this step
	/**
	 * Grab our community triggers
	 */
	$step_requirements = badgeos_get_step_requirements( $step_id );
	
	
	/**
	 * If the step is triggered by joining a specific group
	 */
	if ( 'get_accepted_on_specific_private_group' ==  $step_requirements['community_trigger'] ) {

		/**
		 * And our user is a part of that group, return true
		 */
		if ( ! groups_is_user_member( $user_id, $step_requirements['private_group_id'] ) ) {
			return 0;
		}
	}

	if ( ! empty( $step_requirements["trigger_type"] ) && trim( $step_requirements["trigger_type"] )=='community_trigger' ) {

		$parent_achievement = badgeos_get_parent_of_achievement( $step_id );
		$parent_id = $parent_achievement->ID;
		
		$user_crossed_max_allowed_earnings = badgeos_achievement_user_exceeded_max_earnings( $user_id, $parent_id );
		if ( ! $user_crossed_max_allowed_earnings ) {
			$minimum_activity_count = absint( get_post_meta( $step_id, '_badgeos_count', true ) );
			$count_step_trigger = $step_requirements["community_trigger"];
			$activities = badgeos_get_user_trigger_count( $user_id, $count_step_trigger );
			$relevant_count = absint( $activities );

			$achievements = badgeos_get_user_achievements(
				array(
					'user_id' => absint( $user_id ),
					'achievement_id' => $step_id
				)
			);

			$total_achievments = count( $achievements );
			$used_points = intval( $minimum_activity_count ) * intval( $total_achievments );
			$remainder = intval( $relevant_count ) - $used_points;

			$return  = 0;
			if ( absint( $remainder ) >= $minimum_activity_count )
				$return  = $remainder;
			
			return $return;
		} else {
			return 0;
		}
	}

	return $return;
}
add_filter( 'user_deserves_achievement', 'badgeos_bp_step_activity', 15, 6 );

/**
 * Handle community triggers for award points
 */
function badgeos_bp_trigger_award_points_event() {
	
	/**
     * Setup all our globals
     */
	global $user_ID, $blog_id, $wpdb;

	$site_id = $blog_id;

	$args = func_get_args();
	
	/**
     * Grab our current trigger
     */
	$this_trigger = current_filter();
	
	/**
     * Grab the user ID
     */
	$user_id = badgeos_trigger_get_user_id( $this_trigger, $args );
	
	if('badgeos_groups_promoted_member' == $this_trigger || 'badgeos_groups_promote_member' == $this_trigger || 'badgeos_groups_promote_member_specific' == $this_trigger ){
		$user_id = absint( $args[0][1] );
		$triggered_object_id = $args[0][0];
	}

	$user_data = get_user_by( 'id', $user_id );

	/**
     * Sanity check, if we don't have a user object, bail here
     */
	if ( ! is_object( $user_data ) )
		return $args[ 0 ];
	
	/**
     * If the user doesn't satisfy the trigger requirements, bail here\
     */
	if ( ! apply_filters( 'user_deserves_point_award_trigger', true, $user_id, $this_trigger, $site_id, $args ) ) {
        return $args[ 0 ];
    }
    
	/**
     * Now determine if any badges are earned based on this trigger event
     */
	$triggered_points = $wpdb->get_results( $wpdb->prepare("
			SELECT p.ID as post_id FROM $wpdb->postmeta AS pm INNER JOIN $wpdb->posts AS p ON 
			( p.ID = pm.post_id AND pm.meta_key = '_point_trigger_type' )INNER JOIN $wpdb->postmeta AS pmtrg 
			ON ( p.ID = pmtrg.post_id AND pmtrg.meta_key = '_badgeos_community_trigger' ) 
			where p.post_status = 'publish' AND pmtrg.meta_value =  %s 
			",
			$this_trigger
		) );
	
	if( !empty( $triggered_points ) ) {
		foreach ( $triggered_points as $point ) { 
			if( 'badgeos_groups_promote_member_specific' == $this_trigger ){
				$object_id = get_post_meta( $point->post_id, '_badgeos_promote_group_id', true );
				if( $triggered_object_id == $object_id ){
					$parent_point_id = badgeos_get_parent_id( $point->post_id );

					/**
					 * Update hook count for this user
					 */
					$new_count = badgeos_points_update_user_trigger_count( $point->post_id, $parent_point_id, $user_id, $this_trigger, $site_id, 'Award', $args );
					
					badgeos_maybe_award_points_to_user( $point->post_id, $parent_point_id , $user_id, $this_trigger, $site_id, $args );
				}
			}
			else
			{
				$parent_point_id = badgeos_get_parent_id( $point->post_id );

					/**
					 * Update hook count for this user
					 */
					$new_count = badgeos_points_update_user_trigger_count( $point->post_id, $parent_point_id, $user_id, $this_trigger, $site_id, 'Award', $args );
					
					badgeos_maybe_award_points_to_user( $point->post_id, $parent_point_id , $user_id, $this_trigger, $site_id, $args );
			}
		}
	}
}

/**
 * Handle community triggers for deduct points
 */
function badgeos_bp_trigger_deduct_points_event( $args='' ) {
	
	/**
     * Setup all our globals
     */
	global $user_ID, $blog_id, $wpdb;

	$site_id = $blog_id;

	$args = func_get_args();

	/**
     * Grab our current trigger
     */
	$this_trigger = current_filter();

	/**
     * Grab the user ID
     */
	$user_id = badgeos_trigger_get_user_id( $this_trigger, $args );
		
	if('badgeos_groups_promoted_member' == $this_trigger || 'badgeos_groups_promote_member' == $this_trigger || 'badgeos_groups_promote_member_specific' == $this_trigger ){
		$user_id = absint( $args[0][1] );
		$triggered_object_id = $args[0][0];
	}

	$user_data = get_user_by( 'id', $user_id );

	/**
     * Sanity check, if we don't have a user object, bail here
     */
	if ( ! is_object( $user_data ) ) {
        return $args[ 0 ];
    }

	/**
     * If the user doesn't satisfy the trigger requirements, bail here
     */
	if ( ! apply_filters( 'user_deserves_point_deduct_trigger', true, $user_id, $this_trigger, $site_id, $args ) ) {
        return $args[ 0 ];
    }

	/**
     * Now determine if any Achievements are earned based on this trigger event
     */
	$triggered_deducts = $wpdb->get_results( $wpdb->prepare(
        "SELECT p.ID as post_id FROM $wpdb->postmeta AS pm INNER JOIN $wpdb->posts AS p ON 
		( p.ID = pm.post_id AND pm.meta_key = '_deduct_trigger_type' )INNER JOIN $wpdb->postmeta AS pmtrg 
		ON ( p.ID = pmtrg.post_id AND pmtrg.meta_key = '_badgeos_community_trigger' ) 
		where p.post_status = 'publish' AND pmtrg.meta_value =  %s",
        $this_trigger
    ) );

	if( !empty( $triggered_deducts ) ) {
		foreach ( $triggered_deducts as $point ) { 
			if( 'badgeos_groups_promote_member_specific' == $this_trigger ){
				$object_id = get_post_meta( $point->post_id, '_badgeos_promote_group_id', true );
				if( $triggered_object_id == $object_id ){
					$parent_point_id = badgeos_get_parent_id( $point->post_id );

					/**
		             * Update hook count for this user
		             */
					$new_count = badgeos_points_update_user_trigger_count( $point->post_id, $parent_point_id, $user_id, $this_trigger, $site_id, 'Deduct', $args );
					
					badgeos_maybe_deduct_points_to_user( $point->post_id, $parent_point_id , $user_id, $this_trigger, $site_id, $args );
				}
			} else{
					$parent_point_id = badgeos_get_parent_id( $point->post_id );

					/**
		             * Update hook count for this user
		             */
					$new_count = badgeos_points_update_user_trigger_count( $point->post_id, $parent_point_id, $user_id, $this_trigger, $site_id, 'Deduct', $args );
					
					badgeos_maybe_deduct_points_to_user( $point->post_id, $parent_point_id , $user_id, $this_trigger, $site_id, $args );
				}

		}
	}	
}

/**
 * Handle community triggers for ranks
 */
function badgeos_bp_trigger_ranks_event( $args='' ) {
	
	/**
     * Setup all our globals
     */
	global $user_ID, $blog_id, $wpdb;

	$site_id = $blog_id;

	$args = func_get_args();

	/**
     * Grab our current trigger
     */
	$this_trigger = current_filter();

	
	/**
     * Grab the user ID
     */
	$user_id = badgeos_trigger_get_user_id( $this_trigger, $args );
	
	if('badgeos_groups_promoted_member' == $this_trigger || 'badgeos_groups_promote_member' == $this_trigger || 'badgeos_groups_promote_member_specific' == $this_trigger ){
		$user_id = absint( $args[0][1] );
		$triggered_object_id = $args[0][0];
	}

	$user_data = get_user_by( 'id', $user_id );

	/**
     * Sanity check, if we don't have a user object, bail here
     */
	if ( ! is_object( $user_data ) )
		return $args[ 0 ];

	/**
     * If the user doesn't satisfy the trigger requirements, bail here
     */
	if ( ! apply_filters( 'badgeos_user_rank_deserves_trigger', true, $user_id, $this_trigger, $site_id, $args ) )
		return $args[ 0 ];

	/**
     * Now determine if any Achievements are earned based on this trigger event
     */
	$triggered_ranks = $wpdb->get_results( $wpdb->prepare(
							"SELECT p.ID as post_id FROM $wpdb->postmeta AS pm INNER JOIN $wpdb->posts AS p ON 
							( p.ID = pm.post_id AND pm.meta_key = '_rank_trigger_type' )INNER JOIN $wpdb->postmeta AS pmtrg 
							ON ( p.ID = pmtrg.post_id AND pmtrg.meta_key = '_badgeos_community_trigger' ) 
							where p.post_status = 'publish' AND pmtrg.meta_value =  %s",
							$this_trigger
						) );
	
	if( !empty( $triggered_ranks ) ) {
		foreach ( $triggered_ranks as $rank ) { 
			if( 'badgeos_groups_promote_member_specific' == $this_trigger ){
				$object_id = get_post_meta( $rank->post_id, '_badgeos_promote_group_id', true );
				if( $triggered_object_id == $object_id ){
					$parent_id = badgeos_get_parent_id( $rank->post_id );
					if( absint($parent_id) > 0) { 
						$new_count = badgeos_ranks_update_user_trigger_count( $rank->post_id, $parent_id,$user_id, $this_trigger, $site_id, $args );
						badgeos_maybe_award_rank( $rank->post_id,$parent_id,$user_id, $this_trigger, $site_id, $args );
					} 
				}
			}else {
				$parent_id = badgeos_get_parent_id( $rank->post_id );
				if( absint($parent_id) > 0) { 
					$new_count = badgeos_ranks_update_user_trigger_count( $rank->post_id, $parent_id,$user_id, $this_trigger, $site_id, $args );
					badgeos_maybe_award_rank( $rank->post_id,$parent_id,$user_id, $this_trigger, $site_id, $args );
				} 
			}
		}
	}
}

/**
 * Handle each of our community triggers
 *
 * @since 1.0.0
 */
function badgeos_bp_trigger_event( $args='' ) {
	/**
	 * Setup all our important variables
	 */
	global $user_ID, $blog_id, $wpdb;
	
	if ( 'bp_core_activated_user' == current_filter() ) {
		$user_ID = absint( $args );
	}

	if ( 'get_a_favorite_on_activity_stream' == current_filter() ||  'groups_join_specific_group' == current_filter() || 'get_accepted_on_private_group' == current_filter() || 'get_accepted_on_specific_private_group' == current_filter() ||  'badgeos_groups_promoted_member' == current_filter() || 'badgeos_groups_promote_member' == current_filter() || 'badgeos_groups_promote_member_specific' == current_filter() ) {
		$user_ID = absint( $args[1] );
	}

	if ( 'bp_groups_posted_update_specific' == current_filter() || 'groups_invite_user_specific' == current_filter() ) {
		$user_ID = absint( $args[1] );
	}

	if ( 'bbp_new_topic_specific' == current_filter() || 'bbp_new_specific_reply' == current_filter() ) {
		$user_ID = absint( $args[1] );
	}

	$user_data = get_user_by( 'id', $user_ID );

	/**
	 * Sanity check, if we don't have a user object, bail here
	 */
	if ( ! is_object( $user_data ) ) {
		return $args[0];
	}

	/**
	 * Grab the current trigger
	 */
	$this_trigger = current_filter();

	/**
	 * Now determine if any badges are earned based on this trigger event
	 */
	$triggered_achievements = $wpdb->get_results( 
		$wpdb->prepare( 
			"SELECT pm.post_id FROM $wpdb->postmeta as pm inner 
			join $wpdb->posts as p on( pm.post_id = p.ID ) WHERE p.post_status = 'publish' and 
			pm.meta_key = '_badgeos_community_trigger' AND pm.meta_value = %s", $this_trigger) 
		);
	
	if( count( $triggered_achievements ) > 0 ) {
		$is_not_logged = true;
		foreach ( $triggered_achievements as $achievement ) {
		
			$parents = badgeos_get_achievements( array( 'parent_of' => $achievement->post_id ) );
			if( count( $parents ) > 0 ) {
				
				/**
				 * Since we are triggering multiple times based on group joining, we need to check if we're on the groups_join_specific_group filter.
				 */
				if ( 'bp_user_completed_profile' == current_filter() ) {
					$main_achievement = $parents[0];
					if( $main_achievement ) {

						$achievements = badgeos_get_user_achievements( array( 'user_id' => absint( $user_ID ), 'achievement_id' => absint( $main_achievement->ID ) ) );
						if( count( $achievements ) == 0 ) {

							if( $is_not_logged == true ) {
								
								/**
								 * Update hook count for this user
								 */
								$new_count = badgeos_update_user_trigger_count( $user_ID, $this_trigger, $blog_id ); 
		
								/**
								 * Mark the count in the log entry
								 */
								badgeos_post_log_entry( null, $user_ID, null, sprintf( __( '%1$s triggered %2$s (%3$dx)', 'badgeos-community' ), $user_data->user_login, $this_trigger, $new_count ) );
								$is_not_logged = false;
							}

							badgeos_maybe_award_achievement_to_user( $achievement->post_id, $user_ID, $this_trigger, $blog_id, $args );
						}
					}
				} else if ( 'groups_join_specific_group' == current_filter() ) {
					
					/**
					 * We only want to trigger this when we're checking for the appropriate triggered group ID.
					 */
					$group_id = get_post_meta( $achievement->post_id, '_badgeos_group_id', true );
					
					if ( $group_id == $args[0] ) {
						
						if( $is_not_logged == true ) {
							
							/**
							 * Update hook count for this user
							 */
							$new_count = badgeos_update_user_trigger_count( $user_ID, $this_trigger, $blog_id ); 
	
							/**
							 * Mark the count in the log entry
							 */
							badgeos_post_log_entry( null, $user_ID, null, sprintf( __( '%1$s triggered %2$s (%3$dx)', 'badgeos-community' ), $user_data->user_login, $this_trigger, $new_count ) );
							$is_not_logged = false;
						}

						badgeos_maybe_award_achievement_to_user( $achievement->post_id, $user_ID, $this_trigger, $blog_id, $args );
					}
				} else if ( 'get_accepted_on_specific_private_group' == current_filter() ) {
					
					/**
					 * We only want to trigger this when we're checking for the appropriate triggered group ID.
					 */
					$group_id = get_post_meta( $achievement->post_id, '_badgeos_private_group_id', true );
										
					if ( $group_id == $args[0] ) {
						
						if( $is_not_logged == true ) {
							
							/**
							 * Update hook count for this user
							 */
							$new_count = badgeos_update_user_trigger_count( $user_ID, $this_trigger, $blog_id ); 

							/**
							 * Mark the count in the log entry
							 */
							badgeos_post_log_entry( null, $user_ID, null, sprintf( __( '%1$s triggered %2$s (%3$dx)', 'badgeos-community' ), $user_data->user_login, $this_trigger, $new_count ) );
							$is_not_logged = false;
						}

						badgeos_maybe_award_achievement_to_user( $achievement->post_id, $user_ID, $this_trigger, $blog_id, $args );
					}
				} else if ( 'bp_groups_posted_update_specific' == current_filter() ) {
					
					/**
					 * We only want to trigger this when we're checking for the appropriate triggered group ID.
					 */
					$group_id = get_post_meta( $achievement->post_id, '_badgeos_group_id', true );
										
					if ( $group_id == $args[0] ) {
						
						if( $is_not_logged == true ) {
							
							/**
							 * Update hook count for this user
							 */
							$new_count = badgeos_update_user_trigger_count( $user_ID, $this_trigger, $blog_id ); 

							/**
							 * Mark the count in the log entry
							 */
							badgeos_post_log_entry( null, $user_ID, null, sprintf( __( '%1$s triggered %2$s (%3$dx)', 'badgeos-community' ), $user_data->user_login, $this_trigger, $new_count ) );
							$is_not_logged = false;
						}

						badgeos_maybe_award_achievement_to_user( $achievement->post_id, $user_ID, $this_trigger, $blog_id, $args );					
					}
				} else if ( 'badgeos_groups_promote_member_specific' == current_filter() ) {
					
					/**
					 * We only want to trigger this when we're checking for the appropriate triggered group ID.
					 */
						$group_id = get_post_meta( $achievement->post_id, '_badgeos_promote_group_id', true );
										
						if ( $group_id == $args[0] ) {
							
							if( $is_not_logged == true ) {
								
								/**
								 * Update hook count for this user
								 */
								$new_count = badgeos_update_user_trigger_count( $user_ID, $this_trigger, $blog_id ); 

								/**
								 * Mark the count in the log entry
								 */
								badgeos_post_log_entry( null, $user_ID, null, sprintf( __( '%1$s triggered %2$s (%3$dx)', 'badgeos-community' ), $user_data->user_login, $this_trigger, $new_count ) );
								$is_not_logged = false;
							}

							badgeos_maybe_award_achievement_to_user( $achievement->post_id, $user_ID, $this_trigger, $blog_id, $args );
						}
				} else if ( 'badgeos_groups_promote_member' == current_filter() || 'badgeos_groups_promoted_member' == current_filter()) {
					
					/**
					 * We only want to trigger this when we're checking for user is being promoted.
					 */	
						if( $is_not_logged == true ) {
							
							/**
							 * Update hook count for this user
							 */
							$new_count = badgeos_update_user_trigger_count( $user_ID, $this_trigger, $blog_id ); 

							/**
							 * Mark the count in the log entry
							 */
							badgeos_post_log_entry( null, $user_ID, null, sprintf( __( '%1$s triggered %2$s (%3$dx)', 'badgeos-community' ), $user_data->user_login, $this_trigger, $new_count ) );
							$is_not_logged = false;
						}

						badgeos_maybe_award_achievement_to_user( $achievement->post_id, $user_ID, $this_trigger, $blog_id, $args );
				} else if ( 'groups_invite_user_specific' == current_filter() ) {
					
					/**
					 * We only want to trigger this when we're checking for the appropriate triggered group ID.
					 */
					$group_id = get_post_meta( $achievement->post_id, '_badgeos_group_id', true );
										
					if ( $group_id == $args[0] ) {
						
						if( $is_not_logged == true ) {
							
							/**
							 * Update hook count for this user
							 */
							$new_count = badgeos_update_user_trigger_count( $user_ID, $this_trigger, $blog_id ); 

							/**
							 * Mark the count in the log entry
							 */
							badgeos_post_log_entry( null, $user_ID, null, sprintf( __( '%1$s triggered %2$s (%3$dx)', 'badgeos-community' ), $user_data->user_login, $this_trigger, $new_count ) );
							$is_not_logged = false;
						}

						badgeos_maybe_award_achievement_to_user( $achievement->post_id, $user_ID, $this_trigger, $blog_id, $args );					
					}
				} else if ( 'bbp_new_topic_specific' == current_filter() ) {
					/**
					 * We only want to trigger this when we're checking for the appropriate triggered forum ID.
					 */
					$forum_id = get_post_meta( $achievement->post_id, '_badgeos_forum_id', true );
										
					if ( $forum_id == $args[0] ) {
						
						if( $is_not_logged == true ) {
							
							/**
							 * Update hook count for this user
							 */
							$new_count = badgeos_update_user_trigger_count( $user_ID, $this_trigger, $blog_id ); 

							/**
							 * Mark the count in the log entry
							 */
							badgeos_post_log_entry( null, $user_ID, null, sprintf( __( '%1$s triggered %2$s (%3$dx)', 'badgeos-community' ), $user_data->user_login, $this_trigger, $new_count ) );
							$is_not_logged = false;
						}

						badgeos_maybe_award_achievement_to_user( $achievement->post_id, $user_ID, $this_trigger, $blog_id, $args );

					}
				} else if ( 'bbp_new_specific_reply' == current_filter() ) {
					/**
					 * We only want to trigger this when we're checking for the appropriate triggered forum ID.
					 */
					$forum_id = get_post_meta( $achievement->post_id, '_badgeos_forum_id', true );
										
					if ( $forum_id == $args[0] ) {
						
						if( $is_not_logged == true ) {
							
							/**
							 * Update hook count for this user
							 */
							$new_count = badgeos_update_user_trigger_count( $user_ID, $this_trigger, $blog_id ); 

							/**
							 * Mark the count in the log entry
							 */
							badgeos_post_log_entry( null, $user_ID, null, sprintf( __( '%1$s triggered %2$s (%3$dx)', 'badgeos-community' ), $user_data->user_login, $this_trigger, $new_count ) );
							$is_not_logged = false;
						}

						badgeos_maybe_award_achievement_to_user( $achievement->post_id, $user_ID, $this_trigger, $blog_id, $args );

					}
				} else {
					if( $is_not_logged == true ) {
						
						/**
						 * Update hook count for this user
						 */
						$new_count = badgeos_update_user_trigger_count( $user_ID, $this_trigger, $blog_id ); 

						/**
						 * Mark the count in the log entry
						 */
						badgeos_post_log_entry( null, $user_ID, null, sprintf( __( '%1$s triggered %2$s (%3$dx)', 'badgeos-community' ), $user_data->user_login, $this_trigger, $new_count ) );
						
						$is_not_logged = false;
					}
					
					badgeos_maybe_award_achievement_to_user( $achievement->post_id, $user_ID, $this_trigger, $blog_id, $args );
				}
			}
		}
	}
}

if ( ! function_exists('write_log')) {
	function write_log ( $log )  {
	   if ( is_array( $log ) || is_object( $log ) ) {
		  error_log( print_r( $log, true ) );
	   } else {
		  error_log( $log );
	   }
	}
 }

/**
 * Fires our group_join_specific_group action for joining public groups.
 *
 * @since 1.2.1
 *
 * @param int $group_id ID of the public group being joined.
 * @param int $user_id ID of the user joining the group.
 */
function badgeos_bp_do_specific_group( $group_id = 0, $user_id = 0 ) {
	
	if ( groups_is_user_member( $user_id, $group_id ) ) {
		do_action( 'groups_join_specific_group', array( $group_id, $user_id ) );
	}
}
add_action( 'groups_join_group', 'badgeos_bp_do_specific_group', 15, 2 );

/**
 * because buddypress version 6.2.0 changed the mechanism of promote group member
 *
 * @since 1.2.10
 */
function badgos_community_load_group_promoted_specific_triggers( $user, $group_member, $group, $response, $request ){

	$user_id = $user->ID;
	$promoted_user_id = get_current_user_id();
	$group_id = $group->id;
	$action = $request['action'];

	if( 'promote' == $action ){
		/**
		 * that user who is promoted to another role
		 */
		do_action( 'badgeos_groups_promote_member', array( $group_id, $user_id ) );

		/**
		 * the who promote the other user to another role
		 */
		do_action( 'badgeos_groups_promoted_member', array( $group_id, $promoted_user_id ) );

		/**
		 * that user who is promoted to another role in a specific group
		 */
		do_action( 'badgeos_groups_promote_member_specific', array( $group_id, $user_id ) );
	}
}

// because buddypress version 6.2.0 changed the mechanism of promote group member
add_action( 'bp_rest_group_members_update_item', 'badgos_community_load_group_promoted_specific_triggers', 15, 10 );

/**
 * Fires our group_join_specific_group action for joining Membership request or Hidden groups.
 *
 * @since 1.2.2
 *
 * @param int       $user_id  ID of the user joining the group.
 * @param int       $group_id ID of the group being joined.
 * @param bool|true $accepted Whether or not the membership was accepted. Default true.
 */
function badgeos_bp_do_specific_group_requested_invited( $user_id = 0, $group_id = 0, $accepted = true ) {
	if ( groups_is_user_member( $user_id, $group_id ) ) {
		do_action( 'groups_join_specific_group', array( $group_id, $user_id ) );
	}
}
add_action( 'groups_membership_accepted', 'badgeos_bp_do_specific_group_requested_invited', 15, 3 );
add_action( 'groups_accept_invite', 'badgeos_bp_do_specific_group_requested_invited', 15, 3 );

/**
 * Fires when user make an item favorite.
 *
 * @since 1.2.6
 *
 * @param int       $user_id  ID of the user joining the group.
 * @param int       $group_id ID of the group being joined.
 * @param bool|true $accepted Whether or not the membership was accepted. Default true.
 */
function get_accepted_on_private_group_callback( $user_id = 0, $group_id = 0, $accepted = true ) {
	$group = groups_get_group( $group_id );
	if( $group ) {
		if( trim( $group->status ) == 'private' ) {
			do_action( 'get_accepted_on_private_group', array( $group_id, $user_id  ) );

			do_action( 'get_accepted_on_specific_private_group', array( $group_id, $user_id  ) );
		}
	}
}
add_action( 'groups_membership_accepted', 'get_accepted_on_private_group_callback', 15, 3 );

/**
 * Fires when user make an item favorite.
 *
 * @since 1.2.6
 *
 * @param int       $user_id  ID of the user joining the group.
 * @param int       $group_id ID of the group being joined.
 * @param bool|true $accepted Whether or not the membership was accepted. Default true.
 */
function get_a_favorite_on_activity_stream_item( $activity_id = 0, $user_id = 0 ) {

	$favorites = bp_get_user_meta( $user_id, 'bp_favorite_activities', true );

	$activites = bp_has_activities( array(
		'show_hidden'      => true,
		'include'          => $activity_id,
	) );
	if( $activites ) {
		bp_the_activity();
		$new_user_id = bp_get_activity_user_id();
		if( intval( $new_user_id ) > 0 ) {
			do_action( 'get_a_favorite_on_activity_stream', array( $activity_id, $new_user_id ) );
		}
	}
}
add_action( 'bp_activity_add_user_favorite', 'get_a_favorite_on_activity_stream_item', 15, 3 );

/**
 * Fires when user make an item favorite.
 *
 * @since 1.2.6
 *
 * @param int       $user_id  ID of the user joining the group.
 * @param int       $group_id ID of the group being joined.
 * @param bool|true $accepted Whether or not the membership was accepted. Default true.
 */
function bp_user_completed_profile_callback() {
	
	$user_id = get_current_user_id();

	if ( ! function_exists( 'bp_get_profile_field_data' ) ) { 
		require_once '/bp-xprofile/bp-xprofile-template.php'; 
	}
	
	$name = bp_get_profile_field_data('field=Name&user_id='.$user_id);
	if( ! empty( $name ) ) {
		if( bp_get_user_has_avatar()){
			if( bp_attachments_get_user_has_cover_image()){
				do_action( 'bp_user_completed_profile', array(  ) );
			}
		}
	}
}

add_action( 'xprofile_profile_field_data_updated', 'bp_user_completed_profile_callback' );//$field_id,  $value 
add_action( 'bp_members_avatar_uploaded', 'bp_user_completed_profile_callback' );//$int_avatar_data_item_id,  $avatar_data_type,  $avatar_data 
add_action( 'xprofile_cover_image_uploaded', 'bp_user_completed_profile_callback' );//$int_bp_params_item_id 

// If we're looking to Write a Group Activity Stream message for specific group.
function bp_badge_bp_groups_posted_update_specific_callback( $content, $user_id, $group_id, $activity_id ) {
	do_action( 'bp_groups_posted_update_specific', array( $group_id,  $user_id ) );
}
add_action( 'bp_groups_posted_update', 'bp_badge_bp_groups_posted_update_specific_callback', 15, 4 );

// If we're looking to Invite someone to join a specific group.
function bp_badge_groups_invite_user_specific_callback( $r, $created ) {
	
	$user_id 	= $r['inviter_id'];
	$group_id 	= $r['group_id'];

	do_action( 'groups_invite_user_specific', array( $group_id, $user_id ) );
}
add_action( 'groups_invite_user', 'bp_badge_groups_invite_user_specific_callback', 15, 2 );



// If we're looking to Create a specific Forum Topic
function bbp_new_topic_specific_callback( $topic_id, $forum_id, $anonymous_data, $topic_author ) {
	do_action( 'bbp_new_topic_specific', array( $forum_id, $topic_author ) );
}
add_action( 'bbp_new_topic', 'bbp_new_topic_specific_callback', 15, 4 );


// If we're looking to Reply to a specific Forum Topic
function bbp_new_specific_reply_callback( $reply_id, $topic_id, $forum_id, $anonymous_data, $reply_author, $reply_to ) {
	do_action( 'bbp_new_specific_reply', array( $forum_id, $reply_author ) );
}
add_action( 'bbp_new_reply', 'bbp_new_specific_reply_callback', 15, 6 );


// If we're looking to Promoted to a specific Group Moderator/Administrator
function groups_promote_member_specific_callback($group_id, $user_id) {
	do_action('groups_promote_member_specific', array($group_id, $user_id) );
}
add_action( 'groups_promote_member', 'groups_promote_member_specific_callback', 15, 2 );

/**
 * Decrease the number of times trigger when a community badge has been revoked..
 *
 * @since 1.2.6
 *
 * @param $user_id
 * @param $step_id
 * @param $trigger
 * @param $del_ach_id
 * @param $site_id
 */
function bos_bp_decrement_user_trigger_count_callback( $user_id, $step_id, $trigger, $del_ach_id, $site_id ){
	if( $trigger == 'community_trigger' ) {
		$times 				= absint( get_post_meta( $step_id, '_badgeos_count', true ) );
		$community_trigger 	= get_post_meta( $step_id, '_badgeos_community_trigger', true );
		
		$trigger_count = absint( badgeos_get_user_trigger_count( $user_id, $community_trigger, $site_id, [] ) );
		$trigger_count -= $times;
		
		if( $trigger_count < 0 )
	        $trigger_count = 0;

		$user_triggers = badgeos_get_user_triggers( $user_id, false );
		$user_triggers[$site_id][$community_trigger] = $trigger_count;
		update_user_meta( $user_id, '_badgeos_triggered_triggers', $user_triggers );
	}
}

add_action( 'badgeos_decrement_user_trigger_count', 'bos_bp_decrement_user_trigger_count_callback', 10, 5 );