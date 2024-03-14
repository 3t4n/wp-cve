<?php
/**
 * Plugin Name: BadgeOS Community Add-On
 * Plugin URI: https://badgeos.org/downloads/community/
 * Description: This BadgeOS add-on integrates BadgeOS features with BuddyPress and bbPress.
 * Tags: buddypress
 * Author: BadgeOS
 * Version: 1.3.1
 * Author URI: https://badgeos.org/
 * License: GNU AGPL
 * Text Domain: badgeos-community
 */

/*
 * Copyright © 2012-2020 LearningTimes, LLC
 *
 * This program is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General
 * Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>;.
*/
// Run our activation
register_activation_hook( __FILE__, array( 'BadgeOS_Community', 'activate' ) );

class BadgeOS_Community {

	function __construct() {

		// Define plugin constants
		$this->basename       = plugin_basename( __FILE__ );
		$this->directory_path = plugin_dir_path( __FILE__ );
		$this->directory_url  =  trailingslashit ( plugins_url ( '', __FILE__ ) );

		// Load translations
		load_plugin_textdomain( 'badgeos-community', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
		// If BadgeOS is unavailable, deactivate our plugin
		add_action( 'admin_notices', array( $this, 'maybe_disable_plugin' ) );
		add_action( 'plugins_loaded', array( $this, 'includes' ) );
		add_action( 'bp_include', array( $this, 'bp_include' ) );
		add_action( 'wp_print_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_enqueue_scripts' ] );
		// BuddyPress Action Hooks
		$this->community_triggers = array(
			__( 'Profile/Independent Actions', 'badgeos-community' ) => array(
				'bp_core_activated_user'           => __( 'Activated Account', 'badgeos-community' ),//Working
				'bp_members_avatar_uploaded'         => __( 'Change Profile Avatar', 'badgeos-community' ),//Working
				'xprofile_updated_profile'         => __( 'Update Profile Information', 'badgeos-community' ),//Working
				'bp_user_completed_profile'         => __( 'When the User Completes their Profile', 'badgeos-community' ),//Working
			),
			__( 'Social Actions', 'badgeos-community' ) => array(
				'bp_activity_posted_update'        => __( 'Write an Activity Stream message', 'badgeos-community' ),//Working
				'bp_groups_posted_update'          => __( 'Write a Group Activity Stream message', 'badgeos-community' ),//Working
				'bp_groups_posted_update_specific' => __( 'Write a Group Activity Stream message for specific group.', 'badgeos-community' ),//Working
				'bp_activity_comment_posted'       => __( 'Reply to an item in an Activity Stream', 'badgeos-community' ),//Working
				'bp_activity_add_user_favorite'    => __( 'Favorite an Activity Stream item', 'badgeos-community' ),//Working
				'get_a_favorite_on_activity_stream'    => __( 'Get a favorite on an activity stream item', 'badgeos-community' ),//Working
				'friends_friendship_requested'     => __( 'Send a Friendship Request', 'badgeos-community' ),//Working
				'friends_friendship_accepted'      => __( 'Accept a Friendship Request', 'badgeos-community' ),//Working
				'messages_message_sent'            => __( 'Send/Reply to a Private Message', 'badgeos-community' ),//Working
			),
			__( 'Group Actions', 'badgeos-community' ) => array(
				'groups_group_create_complete'     => __( 'Create a Group', 'badgeos-community' ),//Working
				'groups_join_group'                => __( 'Join a Group', 'badgeos-community' ),//Working
				'groups_join_specific_group'       => __( 'Join a Specific Group', 'badgeos-community' ),//Working
				'groups_invite_user'               => __( 'Invite someone to Join a Group', 'badgeos-community' ),//Working
				'groups_invite_user_specific'      => __( 'Invite someone to join a specific group.', 'badgeos-community' ),//Working
				'get_accepted_on_private_group'    => __( 'Get Accepted on a Private Group', 'badgeos-community' ),//Working
				'get_accepted_on_specific_private_group'    => __( 'Get Accepted on a Specific Private Group', 'badgeos-community' ),//Working
				'badgeos_groups_promote_member'            => __( 'Promoted to Group Moderator/Administrator', 'badgeos-community' ),
				'badgeos_groups_promote_member_specific'   => __( 'Promoted to a specific Group Moderator/Administrator', 'badgeos-community' ),
				'badgeos_groups_promoted_member'           => __( 'Promote another Group Member to Moderator/Administrator', 'badgeos-community' ),
			),
			__( 'Discussion Forum Actions', 'badgeos-community' ) => array(
				'bbp_new_topic'                    => __( 'Create a Forum Topic', 'badgeos-community' ),
				'bbp_new_topic_specific'           => __( 'Create a specific Forum Topic', 'badgeos-community' ),
				'bbp_new_reply'                    => __( 'Reply to a Forum Topic', 'badgeos-community' ),
				'bbp_new_specific_reply'           => __( 'Reply to a specific Forum Topic', 'badgeos-community' ),
			)
		);
	}

	/**
     * Enqueue frontend script
     *
     * @param $hook
     * @return bool
     */
    public function frontend_enqueue_scripts( $hook ) {
        
        if( is_admin() ) {
            return false;
        }
        
        wp_enqueue_style( 'bos-community-style', $this->directory_url . 'css/bos-community.css', [] );
    }

	/**
	 * Files to include for BadgeOS integration.
	 *
	 * @since  1.1.1
	 */
	public function includes() {
		if ( $this->meets_requirements() ) {
			require_once( $this->directory_path . '/includes/rules-engine.php' );
			require_once( $this->directory_path . '/includes/steps-ui.php' );

			// load this file when submission nomination is active
			if ( class_exists( 'BOS_Nomination_Submission' ) ) {
				require_once( $this->directory_path . '/includes/submission-filters.php' );
			}
		}
	}

	/**
	 * Files to include for BuddyPress integration.
	 *
	 * @since 1.0.0
	 */
	public function bp_include() {

		if ( $this->meets_requirements() ) {
			if ( bp_is_active( 'xprofile' ) ) {
				require_once( $this->directory_path . '/includes/bp-members.php' );
				require_once( $this->directory_path . '/includes/bp-member-ranks.php' );
				require_once( $this->directory_path . '/includes/bp-member-points.php' );
				require_once( $this->directory_path . '/includes/settings.php' );
			}
			if ( bp_is_active( 'activity' ) ) {
				require_once( $this->directory_path . '/includes/bp-activity.php' );
			}
		}
	}

	/**
	 * Enqueue custom scripts and styles
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		// Grab the global BuddyPress object
		global $bp;

		// If we're on a BP activity page
		if ( isset( $bp->current_component ) && 'activity' == $bp->current_component ) {
			wp_enqueue_style( 'badgeos-front' );
		}
	}

	/**
	 * Activation hook for the plugin.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {

		// If BadgeOS is available, run our activation functions
		if ( self::meets_requirements() ) {

			//Add default BuddPress settings to each achievement type that may already exist.
			$badgeos_settings = ( $exists = get_option( 'badgeos_settings' ) ) ? $exists : array();
			$args=array(
				'post_type' => trim( $badgeos_settings['achievement_main_post_type'] ),
			  	'post_status' => 'publish',
			  	'posts_per_page' => -1
			);
			$query = new WP_Query($args);
			if( $query->have_posts() ) {
  				while ($query->have_posts()) : $query->the_post();
 	 				update_post_meta( get_the_ID(), '_badgeos_create_bp_activty', 'on' );
 	 				update_post_meta( get_the_ID(), '_badgeos_show_bp_member_menu', 'on' );
 	 			endwhile;
			}
		}
	}

	/**
	 * Check if BadgeOS is available
	 *
	 * @since  1.0.0
	 * @return bool True if BadgeOS is available, false otherwise
	 */
	public static function meets_requirements() {

		if ( class_exists('BadgeOS') && version_compare( BadgeOS::$version, '3.4', '>=' ) && ( class_exists( 'BuddyPress' ) || class_exists( 'bbPress' ) ) ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Generate a custom error message and deactivates the plugin if we don't meet requirements
	 *
	 * @since 1.0.0
	 */
	public function maybe_disable_plugin() {
		if ( ! $this->meets_requirements() ) {
			// Display our error
			echo '<div id="message" class="error">';
			echo '<p>' . sprintf( __( 'BadgeOS Community Add-On requires BadgeOS 3.4 or greater, and either BuddyPress or bbPress and has been <a href="%s">deactivated</a>. Please install and activate BadgeOS and either BuddyPress or bbPress and then reactivate this plugin.', 'badgeos-community' ), admin_url( 'plugins.php' ) ) . '</p>';
			echo '</div>';

			// Deactivate our plugin
			deactivate_plugins( $this->basename );
		}
	}

}
$GLOBALS['badgeos_community'] = new BadgeOS_Community();