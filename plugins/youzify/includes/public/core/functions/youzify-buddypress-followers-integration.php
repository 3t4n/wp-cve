<?php

/**
 * Get Users Follow Button !
 */
function youzify_follow_message_button( $user_id ) {

	$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

	if ( bp_is_active( 'messages' ) ) {

	?>

	<div class="youzify-follow-message-button">
		<?php

            bp_follow_add_follow_button( 'leader_id=' . $user_id );

			if ( bp_is_active( 'messages' ) ) {
                youzify_send_private_message_button( $user_id, '<span>' . __( 'Message', 'youzify' ) . '</span>' );
            }

        ?>
	</div>

	<?php

	} else {
        bp_follow_add_follow_button( 'leader_id=' . $user_id );
	}

}

add_action( 'youzify_social_buttons', 'youzify_follow_message_button' );

/**
 * Remove Js Script
 */
function youzify_delete_buddypress_follwers_script() {

	// Remove Buddypress Follwers Default Script.
	wp_dequeue_script( 'bp-follow-js' );

	// Add the Youzify Follow Script.
	wp_enqueue_script( 'youzify-follow-js', YOUZIFY_ASSETS . 'js/youzify-follow.min.js', array( 'jquery' ), YOUZIFY_VERSION );

}

add_action( 'wp_enqueue_scripts', 'youzify_delete_buddypress_follwers_script', 999 );

/**
 * Setup Tabs.
 */
function youzify_bpfollwers_tabs() {

	// Remove Settings Profile, General Pages
	bp_core_remove_nav_item( 'followers' );
	bp_core_remove_nav_item( 'following' );

	// Remove Follow Menu - Admin Bar.
	remove_action( 'bp_follow_setup_admin_bar', 'bp_follow_user_setup_toolbar' );

}

add_action( 'bp_actions', 'youzify_bpfollwers_tabs', 99 );

/**
 * Get Statistics Value
 */

add_filter( 'youzify_get_user_statistic_number', 'youzify_get_follows_statistics_values', 10, 3 );

function youzify_get_follows_statistics_values( $value = null, $user_id = false, $type = null ) {

	switch ( $type ) {

		case 'followers':
			return bp_follow_get_the_followers_count( array( 'object_id' => $user_id ) );

		case 'following':
			return bp_follow_get_the_following_count( array( 'object_id' => $user_id ) );

		default:
			return $value;
	}

}

/**
 * Get Members Directory Follows Statistics.
 */
function youzify_get_md_follows_statistics( $user_id ) {

	if ( 'on' == youzify_option( 'youzify_enable_md_user_followers_statistics', 'on' ) ) :  ?>
       	<?php $followers_nbr = bp_follow_get_the_followers_count( array( 'object_id' => $user_id ) ); ?>
        <a href="<?php echo youzify_get_user_profile_page( 'follows/followers', $user_id ); ?>" class="youzify-data-item youzify-data-followers" data-youzify-tooltip="<?php echo sprintf( _n( '%s Follower', '%s Followers', $followers_nbr, 'youzify' ), $followers_nbr ); ?>">
            <span class="dashicons dashicons-rss"></span>
        </a>
    <?php

	endif;

    if ( 'on' == youzify_option( 'youzify_enable_md_user_following_statistics', 'on' ) ) :  ?>
       	<?php $following_nbr = bp_follow_get_the_following_count( array( 'object_id' => $user_id ) ); ?>
        <a href="<?php echo youzify_get_user_profile_page( 'follows/following', $user_id ); ?>" class="youzify-data-item youzify-data-following" data-youzify-tooltip="<?php echo sprintf( __( '%s Following', 'youzify' ) , $following_nbr ); ?>">
            <span class="dashicons dashicons-redo"></span>
        </a>
    <?php

	endif;

}

add_action( 'youzify_before_members_directory_card_statistics', 'youzify_get_md_follows_statistics' );