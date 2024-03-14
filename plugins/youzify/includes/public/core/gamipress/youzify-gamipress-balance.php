<?php

/**
 * Get User Balance
 */
function youzify_gamipress_get_user_balance_box( $user_id = null , $title = null, $point_type = null ) {

		global $gamipress_template_args;

		// Shorthand
		$gamipress = $gamipress_template_args;

		// Get User ID.
		$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

		// Get Show Points Value
		$show_points = apply_filters( 'youzify_gamipress_show_user_balance', true, $user_id );

		// Get Show Rank Value
		$show_rank = apply_filters( 'youzify_gamipress_show_user_rank', true, $user_id );

		// Get Rank Types.
		$rank_types = gamipress_get_rank_types_slugs();
		
	?>

		<div class="youzify-gamipress-user-balance-box youzify-user-balance-box">
			<span class="youzify-box-head"><i class="far fa-gem"></i><?php echo $title; ?></span>
			<?php  

				// Display Points.
				echo gamipress_points_shortcode( array( 'user_id' => $user_id ) );

				foreach ( $rank_types as $rank_type ) {

					// Get Rank ID.
					$current_rank_id = gamipress_get_user_rank_id( $user_id, $rank_type );

					if ( $current_rank_id == 0 ) {
						continue;
					}

					// Get Rank Post.
					$rank = gamipress_get_post( $current_rank_id );

					// Get Rank Image.
					$img = get_the_post_thumbnail_url( $rank->ID, 'thumbnail' );

					// Check If Image Exist.
					$img = ! empty( $img ) ? '<img src="' . get_the_post_thumbnail_url( $rank->ID, 'thumbnail' ) . '" class="rank-logo" alt="Newbie" width="250" height="250">' : '';

					if ( $rank->ID != 0 ) {

						// Display User Rank.
						echo '<div class="youzify-user-level-data">' . $img . '<span class="youzify-user-level-title">' . $rank->post_title . '</span></div>';
					}
				}


			?>


		</div>
		<style type="text/css">
			
			.gamipress-points .gamipress-user-points-description .gamipress-user-points-amount:after {
			    content: '/';
			    color: #fff;
			    opacity: .8;
			    font-size: 30px;
			    font-weight: 100;
			    font-family: open sans,sans-serif;
			    margin-left: 5px;
			}
			.gamipress-points .gamipress-user-points-description .gamipress-user-points-amount {
			    margin: 0;
			    padding: 0;
			    color: #fff;
			    font-size: 52px;
			    font-weight: 400;
			    line-height: 50px;
			    text-align: center;
			    display: inline-block;
			    font-family: Poppins,Open sans,sans-serif;
			}

			.gamipress-points .gamipress-user-points-description .gamipress-user-points-label {
			    color: #fff;
			    font-size: 12px;
			    margin-top: 9px;
			    font-weight: 600;
			    padding: 2px 10px;
			    border-radius: 30px;
			    letter-spacing: 3px;
			    background-color: rgba(255,255,255,.15);
			    font-family: Poppins,Open sans,sans-serif;
			}
			html body .youzify-user-balance-box .gamipress-user-points .gamipress-points {
			    display: flex;
			    flex-direction: row;
			    flex-wrap: nowrap;
			    justify-content: center;
			    align-content: flex-start;
			    align-items: flex-start;
			}
		</style>
	<?php
}

/**
 * Function Get GamiPress balance widget content.
 */

add_action( 'youzify_gamipress_user_balance_widget_content', 'youzify_gamipress_profile_balance_widget_content' );

function youzify_gamipress_profile_balance_widget_content() {

	// Get Widget Title.
	$title = youzify_option( 'youzify_wg_gamipress_user_balance_title', __( 'Gamipress User Balance', 'youzify' ) );

	// Get Widget.
	youzify_gamipress_get_user_balance_box( null, $title );

}


/**
 * Check User Balance Widget Visibility.
 */

add_filter( 'youzify_profile_widget_visibility', 'youzify_gamipress_is_user_have_balance', 10, 2 );

function youzify_gamipress_is_user_have_balance( $widget_visibility, $widget_name ) {

    if ( 'user_balance' != $widget_name ) {
        return $widget_visibility;
    }

    return true;
}


/**
 * User Balance WP Widget
 */

add_action( 'widgets_init', 'youzify_gamipress_user_balance_wp_widget' );

function youzify_gamipress_user_balance_wp_widget() {
    register_widget( 'youzify_gamipress_balance_widget' );
}


/**
 * Get Members Directory GamiPress Statistics.
 */

// add_action( 'youzify_after_members_directory_card_statistics', 'youzify_get_md_gamipress_statistics' );

function youzify_get_md_gamipress_statistics( $user_id ) {

	?>

    <?php if ( 'on' == youzify_option( 'youzify_enable_md_user_points_statistics', 'on' ) ) :  ?>
       	<?php $points = mycred_get_users_balance( $user_id ); ?>
        <a href="<?php echo youzify_get_user_profile_page( 'mycred-history', $user_id ); ?>" class="youzify-data-item youzify-data-points" data-youzify-tooltip="<?php echo sprintf( _n( '%s Point', '%s Points', $points, 'youzify' ), $points ); ?>">
            <span class="dashicons dashicons-awards"></span>
        </a>
    <?php endif; ?>

	<?php

}
