<?php

/**
 * Get User Balance
 */
function youzify_mycred_get_user_balance_box( $user_id = null , $title = null, $point_type = null ) {

	if ( ! youzify_is_mycred_active() )  {
		return;
	}

	// Get User ID.
	$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

	// Get Ballance
	$user_balance = mycred_get_users_fcred( $user_id );

	// Load myCRED with this points type.
	$mycred = mycred( 'mycred_default' );

	// Get Show Points Value
	$show_points = apply_filters( 'youzify_mycred_show_user_balance', true, $user_id );

	// Get Show Rank Value
	$show_rank = apply_filters( 'youzify_mycred_show_user_rank', true, $user_id );

	?>

	<div class="youzify-user-balance-box">

		<?php if ( $show_points ) : ?>
			<?php if ( ! empty( $title ) ) : ?>
				<span class="youzify-box-head"><i class="far fa-gem"></i><?php echo $title; ?></span>
			<?php endif; ?>
			<span class="youzify-user-points"><?php echo $user_balance; ?></span>
			<span class="youzify-user-points-slash">/</span>
			<span class="youzify-user-points-title"><?php echo _n( $mycred->singular(), $mycred->plural(), $user_balance ); ?></span>
		<?php endif; ?>

		<?php

		if ( function_exists( 'mycred_get_users_rank' ) && $show_rank ) {

			// Get rank object
			$rank = mycred_get_users_rank( $user_id );

			// If the user has a rank, $rank will be an object
			if ( is_object( $rank ) ) {

				// Rank Logo
				$rank_logo = $rank->has_logo && $rank->logo_id != 0 ? $rank->get_image( 'logo' ) : '<i class="fas fa-user"></i>';

				// Show rank title
				echo '<div class="youzify-user-level-data">' . $rank_logo . '<span class="youzify-user-level-title">' . $rank->title . '</span></div>';

			}
		}

		?>

		<?php do_action( 'youzify_after_user_balance_widget', $user_id ); ?>

	</div>

	<?php
}

/**
 * Function Get Mycred balance widget content.
 */
function youzify_mycred_profile_balance_widget_content() {

	// Get Widget Title.
	$title = youzify_option( 'youzify_wg_user_balance_title', __( 'User Balance', 'youzify' ) );

	// Get Widget.
	youzify_mycred_get_user_balance_box( null, $title );

}

add_action( 'youzify_user_balance_widget_content', 'youzify_mycred_profile_balance_widget_content' );

/**
 * Check User Balance Widget Visibility.
 */
function youzify_mycred_is_user_have_balance( $widget_visibility, $widget_name ) {

    if ( 'user_balance' != $widget_name ) {
        return $widget_visibility;
    }

    return true;
}

add_filter( 'youzify_profile_widget_visibility', 'youzify_mycred_is_user_have_balance', 10, 2 );

/**
 * User Balance WP Widget
 */
function youzify_mycred_user_balance_wp_widget() {
    // require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-mycred-balance-widget.php';

    // register_widget( 'Youzify_Mycred_Balance_Widget' );
}

add_action( 'widgets_init', 'youzify_mycred_user_balance_wp_widget' );

/**
 * Get Members Directory Mycred Statistics.
 */
function youzify_get_md_mycred_statistics( $user_id ) {

	?>

    <?php if ( 'on' == youzify_option( 'youzify_enable_md_user_points_statistics', 'on' ) ) :  ?>
       	<?php $points = mycred_get_users_balance( $user_id ); ?>
        <a href="<?php echo youzify_get_user_profile_page( 'mycred-history', $user_id ); ?>" class="youzify-data-item youzify-data-points" data-youzify-tooltip="<?php echo sprintf( _n( '%s Point', '%s Points', $points, 'youzify' ), $points ); ?>">
            <span class="dashicons dashicons-awards"></span>
        </a>
    <?php endif; ?>

	<?php

}

add_action( 'youzify_after_members_directory_card_statistics', 'youzify_get_md_mycred_statistics' );