<?php

/**
 * Get User Badges
 */
function youzify_gamipress_get_user_badges( $user_id = null, $max_badges = 6, $more_type = 'box' ) {
	
	// Get User ID.
	$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();
	
	// Get Achivements.
	$achievement_ids = gamipress_get_user_earned_achievement_ids( $user_id );

    if( empty( $achievement_ids ) ){
        return;
    }

    $user_badges = array();

	// Get Query
	$query_args = array(
		'post_type' => gamipress_get_achievement_types_slugs(),
		'post__in' => $achievement_ids,
		'post_status' => 'publish',
		'posts_per_page' => -1
	);
	// Query achievements
    $achievement_posts = new WP_Query( $query_args );

    // Init Var.
    $achievements = '';

    // Loop achievements found
    while( $achievement_posts->have_posts() ) : $achievement_posts->the_post();

        // Render the achievement passing the template args
    	$img_url = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );

    	if ( ! empty( $img_url ) ) {

    		// Set User Badges.
			$user_badges[] = array( 
				'title' => get_the_title(),
				'image' => apply_filters( 'gamipress_the_badge', '<img loading="lazy" ' . youzify_get_image_attributes_by_link( $img_url ) . ' alt="">', get_the_title(), $user_id ),
			);
			
    	}

    endwhile;
    
    // Get Badges total
	$badges_nbr = isset( $user_badges ) ? count( $user_badges ) : '';

	?>

	
	<?php if ( ! empty( $user_badges ) ) : ?>

			<div class="youzify-user-badges">
				<?php

			    // Limit Bqdges Number
			    $user_badges = array_slice( $user_badges, 0, $max_badges, true );

				foreach ( $user_badges as $id => $user_badge ) {
					// Display Badges.
					echo '<div class="youzify-badge-item" data-youzify-tooltip="'. $user_badge['title'] .'">' . $user_badge['image'] . '</div>';
					
				}
				if ( 'box' == $more_type ) {
					// Display Show More Button.
			    	youzify_gamipress_get_badges_more_button( $user_id, $badges_nbr, $max_badges, $more_type );
			    }
				?>

			</div>
			<style type="text/css">
				.youzify-user-badges .youzify-badge-item img {
				    width: 55px;
				    height: 55px;
				}

				html body .youzify-user-balance-box .gamipress-user-points .gamipress-points {
				    padding: 0;
				}

				.youzify-user-badges {
				    padding: 12.5px;
				}

				.youzify-widget-main-content .youzify-widget-content .youzify-user-badges .youzify-badge-item {
				    margin: 12.5px 10px;
				    display: inline-block;
				    vertical-align: middle;
				}

				.youzify-widget-content .youzify-user-badges-more-text.youzify-more-items a {
				    color: #969696;
				    display: block;
				    font-size: 13px;
				    padding: 20px 0;
				    font-weight: 600;
				    text-align: center;
				    border-top: 1px solid #f5f5f5;
				}
			</style>

    <?php endif;
    if ( 'text' == $more_type ) {
		// Display Badges.
    	youzify_gamipress_get_badges_more_button( $user_id, $badges_nbr, $max_badges, $more_type );
    }
}

/**
 * Get Badges Widget More Button.
 */
function youzify_gamipress_get_badges_more_button( $user_id = null, $badges_nbr = null, $max_badges = null, $more_type = 'box' ) {

    if ( $badges_nbr > $max_badges ) :

    	$more_nbr = $badges_nbr - $max_badges;
    	$more_title = ( 'text' == $more_type ) ? sprintf( __( 'Show All Badges ( %s )', 'youzify' ), $badges_nbr ) : '+' . $more_nbr; ?>
        <div class="youzify-badge-item youzify-more-items youzify-user-badges-more-<?php echo $more_type ?>" <?php if ( 'box' == $more_type ) echo 'data-youzify-tooltip="' . __( 'Show All Badges', 'youzify' )  . '"'; ?>><a href="<?php echo bp_members_get_user_url( $user_id ) . youzify_gamipress_badges_slug();?>"><?php echo $more_title; ?></a></div>
    <?php endif;

}

/**
 * Get Profile Badges Widget.
 */

add_action( 'youzify_gamipress_user_badges_widget_content', 'youzify_gamipress_profile_badges_widget_content' );

function youzify_gamipress_profile_badges_widget_content() {

	// Get User id.
	$user_id = bp_displayed_user_id();

	// Get Bages max number.
	$max_badges = youzify_option( 'youzify_gamipress_wg_max_user_badges_items', 12 );

	// Get Badges
	youzify_gamipress_get_user_badges( $user_id, $max_badges, 'text' );

}


/**
 * Get Gamipress Badges slug
 */
function youzify_gamipress_badges_slug() {
	return apply_filters( 'youzify_gamipress_badges_slug', 'gamipress-badges' );
}

/**
 * Get Badges Tab Template
 */
function youzify_profile_gamipress_badges_tab_screen() {

    // Call Posts Tab Content.
    add_action( 'bp_template_content', 'youzify_get_gamipress_badges_page_content' );

    // Load Tab Template
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Get Badges Tab Content.
 */
function youzify_get_gamipress_badges_page_content() {

	// Get User ID.
	$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

	$full_name = bp_get_displayed_user_fullname();

	$first_name = bp_get_user_firstname( $full_name );

	$username = ! empty( $first_name ) ? $first_name : bp_members_get_user_slug( $user_id );

	$page_title = bp_is_my_profile() ? __( 'My Badges', 'youzify' ) : sprintf( __( "%1s's Badges", 'youzify' ), $username );

	// Get Query
	$query_args = array(
		'post_type' => gamipress_get_achievement_types_slugs(),
		'post__in' => gamipress_get_user_earned_achievement_ids( $user_id ),
		'post_status' => 'publish',
		'posts_per_page' => -1
	);
 
	// Query achievements
    $achievement_posts = new WP_Query( $query_args );

	// Get Badges Total
	$badges_total = isset( $achievement_posts->post_count ) ? $achievement_posts->post_count : 0;

	// Loop achievements found
    while ( $achievement_posts->have_posts() ) : $achievement_posts->the_post();

        // Render the achievement passing the template args
    	$img_url = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );

    	if ( ! empty( $img_url ) ) {

    		// Set User Badges.
			$user_badges[] = array( 
				'title' => get_the_title(),
				'image' => apply_filters( 'gamipress_the_badge', '<img loading="lazy" ' . youzify_get_image_attributes_by_link( $img_url ) . ' alt="">', get_the_title(), $user_id ),
			);
			
    	}

    endwhile;

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

				foreach ( $user_badges as $badge_id => $user_badge ) {

					echo '<div class="youzify-user-badge-item">';
					echo apply_filters( 'youzify_gamipress_the_badge', $user_badge['image'], $badge_id, $user_badge, $user_id );
					echo apply_filters( 'youzify_gamipress_the_badge_title', '<div class="youzify-user-badge-title">' . $user_badge['title'] . '</div>', $user_badge );
					echo '</div>';
						
				}

			}

		?>

		<?php do_action( 'youzify_after_user_badges_tab' ); ?>

		<style type="text/css">
			.youzify-user-badges-tab .youzify-user-badge-item {
			    display: flex;
			    flex-direction: column;
			    justify-content: flex-end;
			}
			.youzify-right-sidebar-layout .youzify-main-column .youzify-user-badges-tab {
			    display: flex;
			    flex-direction: row;
			    flex-wrap: nowrap;
			    justify-content: flex-start;
			    align-content: stretch;
			    align-items: stretch;
			}
		</style>
	</div>

	<?php
}


/**
 * Members Directory - Display Badges
 */

add_action( 'bp_directory_members_item', 'youzify_gamipress_md_display_user_badges');

function youzify_gamipress_md_display_user_badges() {

	if ( ! bp_is_directory() ) {
		return false;
	}

    // Get badges visibility
    if ( 'off' == youzify_option( 'youzify_enable_cards_gamipress_badges', 'on' ) ) {
        return;
    }

    // Get User id.
    $user_id = bp_get_member_user_id();

    // Get Bages max number.
    $max_badges = youzify_option( 'youzify_wg_gamipress_max_card_user_badges_items', 4 );

    ?>

    <div class="youzify-md-user-badges"><?php youzify_gamipress_get_user_badges( $user_id, $max_badges, 'box' ); ?></div>

    <?php

}


/**
 * Author Box - Display Badges
 */

add_action( 'youzify_author_box_badges_content', 'youzify_gamipress_author_box_badges' );

function youzify_gamipress_author_box_badges( $args = null ) {

    // Get badges visibility
    if ( 'off' == youzify_option( 'youzify_enable_author_box_gamipress_badges', 'on' ) ) {
        return;
    }

    // Get Bages max number.
    $max_badges = youzify_option( 'youzify_gamipress_author_box_max_user_badges_items', 3 );

    ?>

    <div class="youzify-user-badges"><?php youzify_gamipress_get_user_badges( $args['user_id'], $max_badges, 'box' ); ?></div>

    <?php
}


/**
 * Check User Badges Widget Visibility.x
 */

add_filter( 'youzify_profile_widget_visibility', 'youzify_gamipress_is_user_have_widgets', 10, 2 );

function youzify_gamipress_is_user_have_widgets( $widget_visibility, $widget_name ) {

    if ( 'gamipress_user_badges' != $widget_name ) {
        return $widget_visibility;
    }

    // Get User Badges.
    $user_badges = gamipress_get_user_earned_achievement_ids( bp_displayed_user_id() );

    if ( empty( $user_badges ) ) {
        return false;
    }

    return true;
}
