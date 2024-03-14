<?php
/**
 * BuddyPress Membership Functions
 *
 * @package BadgeOS Community
 * @subpackage Members
 * @author LearningTimes, LLC
 * @license http://www.gnu.org/licenses/agpl.txt GNU AGPL v3.0
 * @link https://credly.com
 */

/**
 * Creates a BuddyPress member page for BadgeOS
 *
 * @since 1.0.0
 */
function badgeos_bp_member_achievements() {
	add_action( 'bp_template_content', 'badgeos_bp_member_achievements_content' );
	bp_core_load_template( apply_filters( 'badgeos_bp_member_achievements', 'members/single/plugins' ) );
}

/**
 * Displays a members achievements
 *
 * @since 1.0.0
 */
function badgeos_bp_member_achievements_content() {
	
	$badgeos_settings = ( $exists = get_option( 'badgeos_settings' ) ) ? $exists : array(); 
	
	$args = array(
		'numberposts' => -1,
		'post_type'   => trim( $badgeos_settings['achievement_main_post_type'] )
	);
	$achievement_types = get_posts( $args );
	$type = '';

	$current_param = badgeos_bp_last_url_param( );

	if ( is_array( $achievement_types ) && !empty( $achievement_types ) ) {
		foreach ( $achievement_types as $achievement_type ) {
			
			$name = $achievement_type->post_title;
			$slug = $achievement_type->post_name;
			
			$can_bp_member_menu = get_post_meta( $achievement_type->ID, '_badgeos_show_bp_member_menu', true );
			if ( $slug && $can_bp_member_menu ) {
				if ( $slug && $current_param == $slug ) {
					$type = $slug;
				}
			}
		}
		if (  empty( $type ) || $type=='bos-bp-achievements' ) {
			if( count( $achievement_types ) > 0 ) {
				$can_bp_member_menu = get_post_meta( $achievement_types[0]->ID, '_badgeos_show_bp_member_menu', true );
				if ( $can_bp_member_menu ) {
					$type = $achievement_types[0]->post_name;
				}
			}
		}
	}
	
	$atts = array(
		'type'        => $type,
		'limit'       => '10',
		'show_search' => 'false',
		'user_id'     => bp_displayed_user_id(),
		'wpms'        => badgeos_ms_show_all_achievements(),
	);
	
	echo apply_filters( 'bp_badgeos_community_achivements', badgeos_earned_achievements_shortcode( $atts ) );
}

/**
 * Loads BadgeOS_Community_Members Class from bp_init
 *
 * @since 1.0.0
 */
function badgeos_community_loader() {
	$bp = buddypress();
	$hasbp = function_exists( 'buddypress' ) && buddypress() && ! buddypress()->maintenance_mode && bp_is_active( 'xprofile' );
	if ( !$hasbp )
		return;

	$GLOBALS['badgeos_community_members'] = new BadgeOS_Community_Members();

}
add_action( 'bp_init', 'badgeos_community_loader', 1 );

/**
 * handel progress map for achievements
 */
function badgeos_bp_achievement_progress_map() {
	add_action( 'bp_template_content', 'badgeos_bp_achievement_progress_map_content' );
	bp_core_load_template( apply_filters( 'badgeos_bp_member_achievements', 'members/single/plugins' ) );
}

function badgeos_bp_achievement_progress_map_content() {
	echo do_shortcode('[badgeos_achievements_interactive_progress_map limit="10" status="all"]');
}

/**
 * Build BP_Component extension object
 *
 * @since 1.0.0
 */
class BadgeOS_Community_Members extends BP_Component {

	function __construct() {
		parent::start(
			'badgeos',
			__( 'BadgeOS', 'badgeos-community' ),
			BP_PLUGIN_DIR
		);

	}

	// Globals
	public function setup_globals( $args = '' ) {
		parent::setup_globals( array(
				'has_directory' => true,
				'root_slug'     => 'bos-bp-achievements',
				'slug'          => 'bos-bp-achievements',
			) );
	}

	// BuddyPress actions
	public function setup_actions() {
		parent::setup_actions();
	}

	// Member Profile Menu
	public function setup_nav( $main_nav = array(), $sub_nav = array() ) {

		if ( ! is_user_logged_in() && ! bp_displayed_user_id() )
			return;

		$parent_url = trailingslashit( bp_displayed_user_domain() . $this->slug );

		// Loop existing achievement types to build array of array( 'slug' => 'ID' )
		// @TODO: update global $badgeos->achievement_types to include the post_id of each slug
		$badgeos_settings = ( $exists = get_option( 'badgeos_settings' ) ) ? $exists : array();
		$args=array(
			'post_type'      => trim( $badgeos_settings['achievement_main_post_type'] ),
			'post_status'    => 'publish',
			'posts_per_page' => -1
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->next_post();
				$post_id = $query->post->ID;
				$achievement_object = get_post_type_object( $query->post->post_name );
				$name = is_object( $achievement_object ) ? $achievement_object->labels->name : '';
				$slug = $query->post->post_name;
				// Get post_id of earned achievement type slug

				if ( $post_id ) {

					//check if this achievement type can be shown on the member profile page
					$can_bp_member_menu = get_post_meta( $post_id, '_badgeos_show_bp_member_menu', true );
					if ( $slug && $can_bp_member_menu ) {

						// Only run once to set main nav and defautl sub nav
						if ( empty( $main ) ) {
							// Add to the main navigation
							$main_nav = array(
								'name'                => __( 'Achievements', 'badgeos-community' ),
								'slug'                => $this->slug,
								'position'            => 100,
								'screen_function'     => 'badgeos_bp_member_achievements',
								'default_subnav_slug' => $slug
							);
							$main = true;
						}

						$sub_nav[] = array(
							'name'            => $name,
							'slug'            => $slug,
							'parent_url'      => $parent_url,
							'parent_slug'     => $this->slug,
							'screen_function' => 'badgeos_bp_member_achievements',
							'position'        => 10,
						);

					}

				}
			endwhile;

		}

		wp_reset_query();

		$settings = ( $exists = get_option( 'badgeos_settings' ) ) ? $exists : array();
		if( isset( $settings['badgeos_bp_inp_tab'] ) && !empty( $settings['badgeos_bp_inp_tab'] ) ) {
			$badgeos_bp_inp_tab = trim( $settings['badgeos_bp_inp_tab'] );
			if( $badgeos_bp_inp_tab=='yes' && class_exists('BadgeOS_Interactive_Progress_Map') ) {
				
				$callback = 'badgeos_bp_member_achievements';
				if( count( $sub_nav ) == 0 ) {
					$callback = 'badgeos_bp_achievement_progress_map';
				}

				$sub_nav[] = array(
					'name'            => __( 'Progress Map', 'badgeos-community' ),
					'slug'            => 'progress-map',
					'parent_url'      => $parent_url,
					'parent_slug'     => $this->slug,
					'screen_function' => 'badgeos_bp_achievement_progress_map',
					'position'        => 10,
				);

				$main_nav = array(
					'name'                => __( 'Achievements', 'badgeos-community' ),
					'slug'                => $this->slug,
					'position'            => 100,
					'screen_function'     => $callback,
					'default_subnav_slug' => $slug
				);
				$main = true;
			}
		}
		

		parent::setup_nav( $main_nav, $sub_nav );
	}

}

/**
 * Override the achievement earners list to use BP details
 *
 * @since  1.0.0
 * @param string  $user_content The list item output for the given user
 * @param integer $achievement_id      The achievement's ID
 * @param integer $user_id      The given user's ID
 * @return string               The updated user output
 */
function badgeos_bp_achievement_earner( $user_content, $achievement_id, $user_id ) {
	$user = new BP_Core_User( $user_id );
	return '<li><a href="' .  $user->user_url . '">' . $user->avatar_mini . '</a></li>';
}
add_filter( 'badgeos_get_achievement_earners_list_user', 'badgeos_bp_achievement_earner', 10, 3 );
