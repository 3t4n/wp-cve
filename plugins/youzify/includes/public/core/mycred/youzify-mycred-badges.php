<?php

/**
 * Get User Badges
 */
function youzify_mycred_get_user_badges( $user_id = null, $max_badges = 6, $more_type = 'box', $width = MYCRED_BADGE_WIDTH, $height = MYCRED_BADGE_HEIGHT ) {

	// Get User ID.
	$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

	// Get Ballance
	$user_badges = youzify_mycred_get_users_badges( $user_id );

	// Get Badges total
	$badges_nbr = count( $user_badges );

	?>

	<?php if ( ! empty( $user_badges ) ) : ?>

	<div class="youzify-user-badges">

		<?php

	    // Limit Bqdges Number
	    $user_badges = array_slice( $user_badges, 0, $max_badges, true );

		foreach ( $user_badges as $badge_id => $level ) {

			// Get Levels.
			$levels = mycred_get_badge_levels( $badge_id );

			// Image URL.
			$image_url = isset( $levels[ $level ] ) ? mycred_get_attachment_url( $levels[ $level ]['attachment_id'] ) : '';

			if ( ! empty( $image_url ) ) {
				echo '<div class="youzify-badge-item" data-youzify-tooltip="'. mycred_get_the_title( $badge_id ) .'">' . apply_filters( 'mycred_the_badge', '<img loading="lazy" ' . youzify_get_image_attributes_by_link(  $image_url ) . ' alt="">', $badge_id, array(), $user_id ) . '</div>';
			}

		}

		if ( 'box' == $more_type ) {
			youzify_mycred_get_badges_more_button( $user_id, $badges_nbr, $max_badges, $more_type );
		}

		?>

	</div>

    <?php endif;


    if ( 'text' == $more_type ) {
    	youzify_mycred_get_badges_more_button( $user_id, $badges_nbr, $max_badges, $more_type );
    }

}

/**
 * Get Badges Widget More Button.
 */
function youzify_mycred_get_badges_more_button( $user_id = null, $badges_nbr = null, $max_badges = null, $more_type = 'box' ) {

    if ( $badges_nbr > $max_badges ) :

    	$more_nbr = $badges_nbr - $max_badges;
    	$more_title = ( 'text' == $more_type ) ? sprintf( __( 'Show All Badges ( %s )', 'youzify' ), $badges_nbr ) : '+' . $more_nbr; ?>
        <div class="youzify-badge-item youzify-more-items youzify-user-badges-more-<?php echo $more_type ?>" <?php if ( 'box' == $more_type ) echo 'data-youzify-tooltip="' . __( 'Show All Badges', 'youzify' )  . '"'; ?>><a href="<?php echo bp_members_get_user_url( $user_id ) . youzify_mycred_badges_slug();?>"><?php echo $more_title; ?></a></div>
    <?php endif;

}

/**
 * Get Profile Badges Widget.
 */
function youzify_mycred_profile_badges_widget_content() {

	// Get User id.
	$user_id = bp_displayed_user_id();

	// Get Bages max number.
	$max_badges = youzify_option( 'youzify_wg_max_user_badges_items', 12 );

	// Get Badges
	youzify_mycred_get_user_badges( $user_id, $max_badges, 'text' );

}

add_action( 'youzify_user_badges_widget_content', 'youzify_mycred_profile_badges_widget_content' );

/**
 * Get Mycred Badges slug
 */
function youzify_mycred_badges_slug() {
	return apply_filters( 'youzify_mycred_badges_slug', 'badges' );
}

/**
 * Get Badges Tab Template
 */
function youzify_profile_mycred_badges_tab_screen() {

    // Call Posts Tab Content.
    add_action( 'bp_template_content', 'youzify_get_mycred_badges_page_content' );

    // Load Tab Template
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Get Badges Tab Content.
 */
function youzify_get_mycred_badges_page_content() {

	// Get User ID.
	$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

	// Get Ballance
	$user_badges = youzify_mycred_get_users_badges( $user_id );

	// Get Badges Total
	$badges_total = isset( $user_badges ) ? count( $user_badges ) : 0;

	$full_name = bp_get_displayed_user_fullname();

	$first_name = bp_get_user_firstname( $full_name );

	$username = ! empty( $first_name ) ? $first_name : bp_members_get_user_slug( $user_id );

	$page_title = bp_is_my_profile() ? __( 'My Badges', 'youzify' ) : sprintf( __( "%1s's Badges", 'youzify' ), $username );

	?>

	<div class="youzify-tab-title-box">
		<div class="youzify-tab-title-icon"><i class="fas fa-trophy"></i></div>
		<div class="youzify-tab-title-content">
			<h2><?php echo $page_title; ?></h2>
			<span><?php echo sprintf( _n( '%s Badge', '%s Badges', $badges_total, 'youzify' ), $badges_total ); ?></span>
		</div>
	</div>

	<div class="youzify-user-badges-tab">

		<?php

		if ( ! empty( $user_badges ) ) {

			foreach ( $user_badges as $badge_id => $level ) {

				$badge = mycred_get_badge( $badge_id, $level );

				if ( $badge === false ) continue;

				$badge->image_width  = 100;
				$badge->image_height = 100;

				if ( $badge->level_image !== false ){
					echo '<div class="youzify-user-badge-item">';
					echo apply_filters( 'mycred_the_badge', $badge->get_image( $level ), $badge_id, $badge, $user_id );
					echo apply_filters( 'youzify_mycred_the_badge_title', '<div class="youzify-user-badge-title">' . $badge->title . '</div>', $badge, $level );
					echo '</div>';
				}

			}

		}

		?>

		<?php do_action( 'youzify_after_user_badges_tab' ); ?>

	</div>

	<?php
}


/**
 * Members Directory - Display Badges
 */
function youzify_md_display_user_badges() {

	if ( ! bp_is_directory() ) {
		return false;
	}
    // Get badges visibility
    if ( 'off' == youzify_option( 'youzify_enable_cards_mycred_badges', 'on' ) ) {
        return;
    }

    // Get User id.
    $user_id = bp_get_member_user_id();

    // Get Bages max number.
    $max_badges = youzify_option( 'youzify_wg_max_card_user_badges_items', 4 );

    ?>

    <div class="youzify-md-user-badges"><?php youzify_mycred_get_user_badges( $user_id, $max_badges, 'box' ); ?></div>

    <?php
}

add_action( 'bp_directory_members_item', 'youzify_md_display_user_badges');

/**
 * Author Box - Display Badges
 */
function youzify_mycred_author_box_badges( $args = null ) {

    // Get badges visibility
    if ( 'off' == youzify_option( 'youzify_enable_author_box_mycred_badges', 'on' ) ) {
        return;
    }

    // Get Bages max number.
    $max_badges = youzify_option( 'youzify_author_box_max_user_badges_items', 3 );

    ?>

    <div class="youzify-user-badges"><?php youzify_mycred_get_user_badges( $args['user_id'], $max_badges, 'box' ); ?></div>

    <?php
}

add_action( 'youzify_author_box_badges_content', 'youzify_mycred_author_box_badges' );

/**
 * Check User Badges Widget Visibility.
 */
function youzify_mycred_is_user_have_widgets( $widget_visibility, $widget_name ) {

    if ( 'user_badges' != $widget_name ) {
        return $widget_visibility;
    }

    // Get User Badges.
    $user_badges = youzify_mycred_get_users_badges( bp_displayed_user_id() );

    if ( empty( $user_badges ) ) {
        return false;
    }

    return true;
}

// add_filter( 'youzify_profile_widget_visibility', 'youzify_mycred_is_user_have_widgets', 10, 2 );

if ( ! function_exists( 'youzify_mycred_get_users_badges' ) ) :
	function youzify_mycred_get_users_badges( $user_id = NULL, $force = false ) {

		if ( $user_id === NULL ) return array();

		// global $mycred_current_account;

		// if ( mycred_is_current_account( $user_id ) && isset( $mycred_current_account->badge_ids ) && $force == false )
		// 	return $mycred_current_account->badge_ids;

		$badge_ids = mycred_get_user_meta( $user_id, MYCRED_BADGE_KEY . '_ids', '', true );
		if ( !isset($badge_ids) || $badge_ids == '' || $force ) {

			global $wpdb;

			$badge_ids = array();
			$query     = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key LIKE %s AND meta_key NOT LIKE '%_issued_on' AND meta_key NOT LIKE '%_ids'", $user_id, mycred_get_meta_key( MYCRED_BADGE_KEY ) . '%' ) );

			if ( ! empty( $query ) ) {

				foreach ( $query as $badge ) {

					$badge_id = str_replace( MYCRED_BADGE_KEY, '', $badge->meta_key );
					if ( $badge_id == '' ) continue;

					$badge_id = absint( $badge_id );
					if ( ! array_key_exists( $badge_id, $badge_ids ) )
						$badge_ids[ $badge_id ] = absint( $badge->meta_value );

				}

				mycred_update_user_meta( $user_id, MYCRED_BADGE_KEY . '_ids', '', $badge_ids );

			}

		}

		$clean_ids = array();
		if ( ! empty( $badge_ids ) ) {
			foreach ( $badge_ids as $id => $level ) {

				$id = absint( $id );
				if ( $id === 0 || strlen( $level ) < 1 ) continue;
				$clean_ids[ $id ] = absint( $level );

			}
		}

		return apply_filters( 'youzify_mycred_get_users_badges', $clean_ids, $user_id );

	}
endif;