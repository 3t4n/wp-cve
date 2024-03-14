<?php
/**
 * BuddyPress Membership Points Functions
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
function badgeos_bp_member_points() {
	add_action( 'bp_template_content', 'badgeos_bp_member_points_content' );
	bp_core_load_template( apply_filters( 'badgeos_bp_member_points', 'members/single/plugins' ) );
}

/**
 * Displays a members achievements
 *
 * @since 1.0.0
 */
function badgeos_bp_member_points_content() {

    $user_ID = bp_displayed_user_id();
    if( intval( $user_ID ) == 0 ) {
        $user_ID = get_current_user_id();
    }

    ob_start();
	?>
        <hr />
        <h2><?php _e( 'Earned Points', 'badgeos-community' ); ?></h2>
        <div class="earned-user-credits-wrapper">
            <?php
            $credit_types = badgeos_get_point_types();
            if ( is_array( $credit_types ) && ! empty( $credit_types ) ) {
                foreach ( $credit_types as $credit_type ) {
                    $earned_credits = badgeos_get_points_by_type( $credit_type->ID, $user_ID );
                    ?>
                    <div class="badgeos-credits">
                        <h3><?php echo $credit_type->post_title; ?></h3>
                        <div class="badgeos-earned-credit"><?php echo __( 'Earned Points: ' ) . (int) $earned_credits; ?></div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    <?php
	
	$output = ob_get_clean();

	echo apply_filters( 'bp_badgeos_community_points', $output );
}

/**
 * Loads BadgeOS_Community_Members Class from bp_init
 *
 * @since 1.0.0
 */
function badgeos_community_points_loader() {
	$bp = buddypress();
	$hasbp = function_exists( 'buddypress' ) && buddypress() && ! buddypress()->maintenance_mode && bp_is_active( 'xprofile' );
	if ( !$hasbp )
		return;

	$GLOBALS['badgeos_community_member_points'] = new BadgeOS_Community_Member_Points();

}
add_action( 'bp_init', 'badgeos_community_points_loader', 1 );

/**
 * Build BP_Component extension object
 *
 * @since 1.0.0
 */
class BadgeOS_Community_Member_Points extends BP_Component {

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
				'root_slug'     => 'points',
				'slug'          => 'points',
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

		$credit_types = badgeos_get_point_types();
		if ( is_array( $credit_types ) && ! empty( $credit_types ) ) {
			// Loop existing achievement types to build array of array( 'slug' => 'ID' )
			// @TODO: update global $badgeos->achievement_types to include the post_id of each slug
			$main_nav = array(
				'name'                => __( 'Points', 'badgeos-community' ),
				'slug'                => $this->slug,
				'position'            => 100,
				'screen_function'     => 'badgeos_bp_member_points',
				'default_subnav_slug' => 'points',
			);
		}
		$sub_nav = array();
		parent::setup_nav( $main_nav, $sub_nav );
	}

}

?>