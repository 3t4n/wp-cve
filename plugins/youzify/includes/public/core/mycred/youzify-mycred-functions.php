<?php

/**
 * Include MyCRED Files.
 */
function youzify_init_mycred() {

	if ( ! youzify_is_mycred_active() ) {
		return;
	}

	// Balance Functions.
    require YOUZIFY_CORE . 'mycred/youzify-mycred-balance.php';

	// Badges Functions.
	if ( defined( 'myCRED_BADGE_VERSION' ) ) {
    	require YOUZIFY_CORE . 'mycred/youzify-mycred-badges.php';
	}

}

add_action( 'setup_theme', 'youzify_init_mycred' );

/**
 * MyCRED Enqueue scripts.
 */
function youzify_mycred_scripts( $hook_suffix ) {

    if ( ! youzify_is_mycred_active() )  {
        return;
    }

    // Register MyCRED Css.
    wp_register_style( 'youzify-mycred', YOUZIFY_ASSETS . 'css/youzify-mycred.min.css', array(), YOUZIFY_VERSION );

    // Call MyCRED Css.
    wp_enqueue_style( 'youzify-mycred' );

}

add_action( 'wp_enqueue_scripts', 'youzify_mycred_scripts' );

/**
 * Edit My Cred Title
 */
function youzify_edit_mycred_tab_title( $title ) {

	ob_start();

	?>

	<div class="youzify-tab-title-box">
		<div class="youzify-tab-title-icon"><i class="fas fa-history"></i></div>
		<div class="youzify-tab-title-content">
			<h2><?php echo $title; ?></h2>
			<span><?php _e( 'This is the user points log.', 'youzify' );?></span>
		</div>
	</div>

	<?php

	$output = ob_get_contents();
	ob_end_clean();

	return $output;

}

add_filter( 'mycred_br_history_page_title' , 'youzify_edit_mycred_tab_title' );


/**
 * Leader Board Widget.
 */
function youzify_mycred_leader_board_widget( $layout, $template, $user, $position, $data ) {

	if ( apply_filters( 'youzify_mycred_leader_board_widget', true ) ) {
		$avatar = bp_core_fetch_avatar( array( 'item_id' => $user['ID'], 'type' => 'thumb' ) );
		$layout = '<li class="youzify-leaderboard-item"><div class="youzify-leaderboard-avatar"><span class="youzify-leaderboard-position"># ' . $position .'</span>'. $avatar . '</div><div class="youzify-leaderboard-content"><a class="youzify-leaderboard-username" href="' . bp_members_get_user_url( $user['ID'] ).'">' . bp_core_get_user_displayname( $user['ID'] ) . '</a><div class="youzify-leaderboard-points">' . sprintf( _n( '%s ' . $data->core->core['name']['singular'], '%s ' . $data->core->core['name']['plural'], $user['cred'], 'youzify' ), $user['cred'] ) . '</div></li>';
	}
	return $layout;
}

add_filter( 'mycred_ranking_row', 'youzify_mycred_leader_board_widget', 10, 5 );

/**
 * Get Statistics Value
 */
function youzify_get_mycred_statistics_values( $value, $user_id, $type ) {

	if ( $type == 'points' ) {
		return mycred_get_users_balance( $user_id );
	}

	return $value;

}

add_filter( 'youzify_get_user_statistic_number', 'youzify_get_mycred_statistics_values', 10, 3 );