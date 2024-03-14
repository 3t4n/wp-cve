<?php

/**
 * Wall- Status Action
 */
function youzify_activity_action_wall_posts( $action, $activity ) {

	if ( $activity->component == 'gamipress' ) {
		return $action;
	}

	// Get User & Post Data.
	$post_link = youzify_get_wall_post_url( $activity->id );
	$user_link = bp_core_get_userlink( $activity->user_id );

	// Get Post Action.
	switch ( $activity->type ) {

		case 'activity_update':
		case 'activity_giphy':
		case 'activity_slideshow':
		case 'activity_status':
		case 'activity_quote':
		case 'activity_photo':
		case 'activity_video':
		case 'activity_audio':
		case 'activity_poll':
		case 'activity_versus_poll':
		case 'activity_classic_poll':
		case 'activity_hotornot_poll':
		case 'activity_link':
		case 'activity_share':
		case 'activity_file':

			// Add Group Description.
			if ( youzify_wall_is_group_post( $activity ) ) {
				$action =  sprintf( __( '%1s posted', 'youzify' ), $user_link );
			} else {
				$action = $user_link;
			}

			break;

		case 'new_cover':
			$action = sprintf(
				__( '%1s Changed their profile cover', 'youzify' ), $user_link );
			break;

	};


	$action = apply_filters( 'youzify_activity_post_action_before_group_description', $action, $activity, $user_link, $post_link );

	// Add Group Description.
	$hide_group_description = array( 'joined_group', 'created_group', 'activity_update' );

	if (
		bp_is_active( 'groups' ) && 'groups' == $activity->component && ! bp_is_groups_component() &&
		! in_array( $activity->type, $hide_group_description ) ) {
		$group = groups_get_group( $activity->item_id );
		$action .= sprintf( __( ' in the group %1s', 'youzify' ), '<a href="' . bp_get_group_url( $group ) . '">' . esc_attr( $group->name ) . '</a>' );
	} else {
		$mood = apply_filters( 'youzify_activity_post_mood', false, $activity );
		$tagged_users = apply_filters( 'youzify_activity_post_tagged_users', false, $activity );
		$checkin = apply_filters( 'youzify_activity_post_checkin_string', false, $activity );

		if ( ! empty( $tagged_users ) || ! empty( $mood ) ) {
			$action .= apply_filters( 'youzify_activity_action_is_string', ' ' . __( 'is', 'youzify' ) . ' ' . $mood . $tagged_users, $mood, $tagged_users, $activity );
		}

		// if ( ! empty( $mood ) ) {
		// 	$action .=  apply_filters( 'youzify_activity_action_at_string', ' ' . $mood . $tagged_users . __( 'at', 'youzify' ) . ' ' . $checkin, $mood, $tagged_users, $activity );
		// } else {
		// 	$action .=  apply_filters( 'youzify_activity_action_in_string', ' ' . $mood . $tagged_users . __( 'in', 'youzify' ) . ' ' . $checkin, $mood, $tagged_users, $activity );
		// }
	}

	// Return Action
	return apply_filters( 'youzify_activity_new_post_action', $action, $activity );

}

add_filter( 'bp_get_activity_action_pre_meta' , 'youzify_activity_action_wall_posts', 999, 2 );

/**
 * Get Wall Post Url
 */
function youzify_get_wall_post_url( $activity_id ) {
	// Get Post Url.
	$post_link = bp_get_root_url() . '/' . bp_get_activity_root_slug() . '/p/' . $activity_id . '/';
	// Return Link.
	return $post_link;
}

/**
 * Check if post belong to a group.
 */
function youzify_wall_is_group_post( $activity ) {

	if ( bp_is_active( 'groups' ) && 'groups' == $activity->component && ! bp_is_groups_component() ) {
		return true;
	}

	return false;
}

/**
 * Strip Emoji from Content.
 */
function youzify_remove_emoji( $content ) {

    // Clear Content .
    $content = preg_replace('/&#x[\s\S]+?;/', '', $content );

    return $content;
}

/**
 * Copy Image from Buddypress Directory to Youzify Directory.
 */
function youzify_copy_image_to_youzify_directory( $bp_path ) {

	global $Youzify_upload_url, $Youzify_upload_dir;

	// Get File Name
	$filename = basename( $bp_path );

    // Get File New Name.
    $new_name = $filename;

	// Get Unique File Name for the file.
    while ( file_exists( $Youzify_upload_dir . $new_name ) ) {
		$new_name = uniqid( 'file_' ) . '.' . $ext;
	}

	// Get Files Path.
	$old_file = $bp_path;
	$new_file = $Youzify_upload_dir . $new_name;

	// Move File From Buddypress Directory to the Youzify Directory.
    if ( copy( $old_file, $new_file ) ) {
    	return  $Youzify_upload_url . $filename;
    }

   return false;

}

/**
 * Get List of people who liked a post.
 */
function youzify_get_who_liked_activities( $activity_id ) {

	$users = get_transient( 'youzify_get_who_liked_activities_' . $activity_id );

	if ( false === $users ) :

	global $wpdb;

	// Prepare Sql
	$sql = $wpdb->prepare( "SELECT user_id FROM " . $wpdb->base_prefix . "usermeta WHERE meta_key = 'bp_favorite_activities' AND meta_value LIKE %s", '%' . $activity_id . '%' );

	// Get Result
	$result = $wpdb->get_results( $sql , ARRAY_A );

	// Get List of user id's & Remove Duplicated Users.
	$users = array_unique( wp_list_pluck( $result, 'user_id' ) );

    set_transient( 'youzify_get_who_liked_activities_' . $activity_id, $users, 12 * HOUR_IN_SECONDS );

	endif;

	return $users;
}


/**
 * Display Who Liked a Post.
 */
function youzify_show_who_liked_activities() {

	// Check if likes allowed.
	if ( ! apply_filters( 'youzify_show_who_liked_activities', true ) || ! bp_activity_can_favorite() ) {
		return false;
	}

	// Get list of people who liked a post.
	$liked_users = youzify_get_who_liked_activities( bp_get_activity_id() );

    if ( empty( $liked_users ) ) {
    	return false;
    }

    $output = '';

    // Max User Number.
    $max_users_number = 3;

	$liked_count = (int) bp_activity_get_meta( bp_get_activity_id(), 'favorite_count' ) - $max_users_number;

    foreach ( $liked_users as $key => $user_id ) {

    	if ( $key > $max_users_number - 1 ) {
    		break;
    	}

    	// Get User Image Code.
        $output .= "<a data-youzify-tooltip='" . bp_core_get_user_displayname( $user_id ) . "' href='" . bp_members_get_user_url ( $user_id ) . "'>" . bp_core_fetch_avatar( array( 'html' => true, 'type' => 'thumb', 'item_id' => $user_id ) ) ."</a>";
    }

    if ( $output ) { ?>
		<div class="youzify-post-liked-by">
        	<?php echo $output; if ( $liked_count > 0 ) : ?>
			<a class="youzify-trigger-who-modal youzify-view-all" data-action="youzify_get_who_liked_post" data-youzify-tooltip="<?php _e( 'View All', 'youzify' ); ?>">+<?php echo $liked_count; ?></a>
			<?php endif ;?>
			<span class="youzify-liked-this"><?php _e( 'liked this', 'youzify' ); ?></span>
        </div>
    <?php }

}

/**
 * Get Wall Model.
 */
function youzify_wall_modal( $args = false ) {

	// item ID.
	$content_function = $args['function'];

	// Get Args
	$params = isset( $args['item_id'] ) ? $args['item_id'] : $args['args'];

	?>

	<div id="youzify-modal">
		<div class="youzify-wall-modal">
			<div class="youzify-wall-modal-title"><?php if ( isset( $args['icon'] ) ) echo '<i class="' . $args['icon'] . '"></i>'; echo $args['title']; ?><i class="fas fa-times youzify-wall-modal-close youzify-modal-close-icon"></i></div>
			<div class="youzify-wall-modal-content">
				<?php $content_function( $params ); ?>
			</div>
		</div>
	</div>

	<?php
}

/**
 * Get who liked a post Modal.
 */
function youzify_get_who_liked_post_modal() {

	// Get Modal Args
	$args = array(
		'item_id'  => absint( $_POST['post_id'] ),
		'function' => 'youzify_get_who_liked_post_list',
		'title'    => __( 'People Who Liked This', 'youzify' )
	);

	// Get Modal Content
	youzify_wall_modal( $args );

	die();
}

add_action( 'wp_ajax_youzify_get_who_liked_post', 'youzify_get_who_liked_post_modal' );
add_action( 'wp_ajax_nopriv_youzify_get_who_liked_post', 'youzify_get_who_liked_post_modal' );

/**
 * Get who liked a post List.
 */
function youzify_get_who_liked_post_list( $post_id ) {

	// Get Liked Users.
	$users = youzify_get_who_liked_activities( $post_id );

	// Get Users List.
	youzify_get_popup_user_list( $users );

}

/**
 * Get who liked a post List.
 */
function youzify_get_activity_tagged_users( $activity_id ) {

	// Get Tagged Users.
	$users = bp_activity_get_meta( $activity_id, 'tagged_users' );

	// Remove First User as it's already shown.
	if ( isset( $users[0] ) ) {
		unset( $users[0] );
	}

	// Get Users List.
	youzify_get_popup_user_list( $users );

}

/**
 * Users Pop Up List.
 **/
function youzify_get_popup_user_list( $users ) {

	if ( empty( $users ) ) {
		return;
	}

	echo '<div class="youzify-users-who-list">';

	foreach ( $users as $user_id ) {

		?>

		<div class="youzify-list-item">
			<a href="<?php echo bp_members_get_user_url( $user_id ); ?>" class="youzify-item-avatar"><?php echo bp_core_fetch_avatar( array( 'type'=> 'thumb', 'item_id'=> $user_id ) ); ?></a>
			<div class="youzify-item-data">
				<div class="youzify-item-name"><?php echo bp_core_get_userlink( $user_id ); ?></div>
				<div class="youzify-item-meta">@<?php echo bp_members_get_user_slug( $user_id ); ?></div>
			</div>
		</div>

	<?php }

	echo '</div>';
}

/**
 * Edit 'User wrote a new post' Post Action.
 */
function youzify_edit_new_blog_action( $action , $activity ) {

	// Get User Link
	$user_link = bp_core_get_userlink( $activity->user_id );

	// Get Action
	$action = sprintf( __( '%1s wrote a new post', 'youzify' ), $user_link );

	return $action;
}

add_filter( 'bp_blogs_format_activity_action_new_blog_post', 'youzify_edit_new_blog_action', 10, 2 );

/**
 * Edit 'posted an update' Post Action.
 */
function youzify_edit_activity_post_action( $action , $activity ) {

	// Get User Link
	$user_link = bp_core_get_userlink( $activity->user_id );

	// Add Group Description.
	if ( youzify_wall_is_group_post( $activity ) ) {
		$action =  sprintf( __( '%1s posted', 'youzify' ), $user_link );
	} else {
		$action = $user_link;
	}

	return $action;
}

add_filter( 'bp_activity_new_update_action', 'youzify_edit_activity_post_action', 10, 2 );

/**
 * Get Wall Single Post Content.
 */
function youzify_get_single_wall_post() {

    ?>

    <?php do_action( 'bp_before_single_activity_post' ); ?>

    <div class="activity no-ajax">
        <?php if ( bp_has_activities( 'display_comments=threaded&show_hidden=true&include=' . bp_current_action() ) ) : ?>

            <ul id="activity-stream" class="activity-list item-list">

            <?php while ( bp_activities() ) : bp_the_activity(); ?>
                <?php bp_get_template_part( 'activity/entry' ); ?>
            <?php endwhile; ?>

            </ul>

        <?php endif; ?>
    </div>

    <?php do_action( 'bp_after_single_activity_post' ); ?>

    <?php
}

/**
 * Get Activity By ID.
 */
function youzify_get_activity_by_id( $activity_id ) {

	$activity = BP_Activity_Activity::get( array( 'in' => $activity_id ) );
	$activity = isset( $activity['activities'][0] ) ? $activity['activities'][0] : null;

	return $activity;
}

/**
 * Delete Wall Favs Transient.
 */
function youzify_delete_activity_likes_transient( $activity_id = null ) {
	// Delete Transient.
	delete_transient( 'youzify_get_who_liked_activities_' . $activity_id );
}

add_action( 'bp_activity_remove_user_favorite', 'youzify_delete_activity_likes_transient', 1 );
add_action( 'bp_activity_add_user_favorite', 'youzify_delete_activity_likes_transient', 1 );

/**
 * Limit Wall Posts Images Height.
 */
function youzify_limit_wall_posts_image_height() {
	return apply_filters( 'youzify_limit_wall_posts_image_height', false );
}

/**
 * Enable Posting Form.
 */
function youzify_is_wall_posting_form_active() {
	return apply_filters( 'youzify_is_wall_posting_form_active', true );
}

/**
 * Enable Posts Effect.
 */
function youzify_enable_wall_posts_effect() {
	return apply_filters( 'youzify_enable_wall_posts_effect', 'on' == youzify_option( 'youzify_enable_wall_activity_effects', 'on' ) ? true : false );
}

/**
 * Add Effect
 */
function youzify_add_activity_css_class( $classes ) {

	// Add Activity Class.
	// $classes .= ' youzify-activity-item';

	if ( ! youzify_enable_wall_posts_effect() ) {
		return $classes;
	}
	return $classes . ' youzify_effect';
}

add_filter( 'bp_get_activity_css_class', 'youzify_add_activity_css_class' );

/**
 * Get Activity Live Url Preview Meta.
 */
function youzify_get_activity_url_preview_meta( $activity_id ) {

	// Get Url Data.
	$url_data = bp_activity_get_meta( $activity_id, 'url_preview' );

	if ( ! empty( $url_data ) ) {

		// Unserialize data.
		$url_data = is_serialized( $url_data ) ? unserialize( $url_data ) : maybe_unserialize( base64_decode( $url_data ) );

	}

	return apply_filters( 'youzify_get_activity_url_preview_meta', $url_data, $activity_id );
}


/**
 * Add profile activity page filter bar.
 */
function youzify_profile_activity_tab_filter_bar() {

    if ( ! bp_is_user_activity() ) {
        return;
    }

    if ( 'on' == youzify_option( 'youzify_enable_wall_filter_bar', 'on' ) ) : ?>

	<div class="item-list-tabs no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Member secondary navigation', 'youzify' ); ?>" role="navigation">
	    <ul>

	        <?php bp_get_options_nav(); ?>

	        <li id="activity-filter-select" class="last">
	            <label for="activity-filter-by"><?php _e( 'Show:', 'youzify' ); ?></label>
	            <select id="activity-filter-by">
	                <option value="-1"><?php _e( '&mdash; Everything &mdash;', 'youzify' ); ?></option>

	                <?php bp_activity_show_filters(); ?>

	                <?php

	                /**
	                 * Fires inside the select input for member activity filter options.
	                 *
	                 * @since 1.2.0
	                 */
	                do_action( 'bp_member_activity_filter_options' ); ?>

	            </select>
	        </li>
	    </ul>
	</div><!-- .item-list-tabs -->

	<?php endif;

}

add_action( 'youzify_profile_main_content', 'youzify_profile_activity_tab_filter_bar' );

/**
 * Fix Buddypress Time Since
 */
function youzify_get_activity_time_stamp_meta( $activity = false ) {

    global $activities_template;

    $activity = empty( $activity ) ? $activities_template->activity : $activity;

    // Strip any legacy time since placeholders from BP 1.0-1.1.
    $new_content = $content = '';
    // $new_content = str_replace( '<span class="time-since">%s</span>', '', $content );

    // Get the time since this activity was recorded.
    $date_recorded = bp_core_time_since( $activity->date_recorded );

    // Set up 'time-since' <span>.
    $time_since = sprintf(
        '<span class="time-since" data-livestamp="%1$s">%2$s</span>',
        bp_core_get_iso8601_date( $activity->date_recorded ),
        $date_recorded
    );

    /**
     * Filters the activity item time since markup.
     *
     * @since 1.2.0
     *
     * @param array $value Array containing the time since markup and the current activity component.
     */
    $time_since = apply_filters_ref_array( 'bp_activity_time_since', array(
        $time_since,
        &$activity
    ) );

    // Insert the permalink.
    if ( ! bp_is_single_activity() ) {

        // Setup variables for activity meta.
        $activity_permalink = bp_activity_get_permalink( $activity->id, $activity );
        $activity_meta      = sprintf( '%1$s <a href="%2$s" class="view activity-time-since bp-tooltip" data-bp-tooltip="%3$s">%4$s</a>',
            $new_content,
            $activity_permalink,
            esc_attr__( 'View Discussion', 'buddypress' ),
            $time_since
        );

        /**
         * Filters the activity permalink to be added to the activity content.
         *
         * @since 1.2.0
         *
         * @param array $value Array containing the html markup for the activity permalink, after being parsed by
         *                     sprintf and current activity component.
         */
        $new_content = apply_filters_ref_array( 'bp_activity_permalink', array(
            $activity_meta,
            &$activity
        ) );
    } else {
        $new_content .= str_pad( $time_since, strlen( $time_since ) + 2, ' ', STR_PAD_BOTH );
    }

    /**
     * Filters the activity content after activity metadata has been attached.
     *
     * @since 1.2.0
     *
     * @param string $content Activity content with the activity metadata added.
     */
    return apply_filters( 'youzify_insert_activity_meta', $new_content, $content, $activity->id );
}

function youzify_get_activity_page_class() {

    // New Array
    $activity_class = array( 'youzify-horizontal-layout youzify-global-wall' );

    // Get Tabs List Icons Style
    $activity_class[] = youzify_option( 'youzify_tabs_list_icons_style', 'youzify-tabs-list-gradient' );

    // Get Profile Scheme
    $activity_class[] = youzify_option( 'youzify_profile_scheme', 'youzify-blue-scheme' );

    // Get Page Buttons Style
    $activity_class[] = 'youzify-page-btns-border-' . youzify_option( 'youzify_buttons_border_style', 'oval' );

    $activity_class = apply_filters( 'youzify_activity_page_class', $activity_class );

    return youzify_generate_class( $activity_class );
}


/**
 * Get Activity Attachments.
 */
function youzify_get_activity_attachments_by_media_id( $media_id = null, $field = 'src' ) {

	if ( empty( $media_id ) ) {
		return;
	}

	global $wpdb, $Youzify_media_table;

	$sql = "SELECT $field FROM $Youzify_media_table WHERE ";

	if ( is_array( $media_id ) ) {
		$sql .= "id = %d";
	} else {
		$sql .= "id IN ( %s )";
	}

	// Prepare Sql
	$sql = $wpdb->prepare( $sql, $media_id );

	// Get Result
	$result = $wpdb->get_results( $sql , ARRAY_A );

	if ( empty( $result ) ) {
		return false;
	}

	if ( $field != '*' ) {

		$result = wp_list_pluck( $result, $field );

		$atts = array();

		foreach ( $result as $src ) {
			$atts[] = maybe_unserialize( $src );
		}

	} else {
		$atts = $result;
	}

	return $atts;

}

/**
 * Get Wall Allowed Images Format
 */
function youzify_wall_compressed_images_format() {
	return apply_filters( 'youzify_wall_allowed_images_format', array( 'jpeg', 'jpg', 'png' ) );
}

/***
 * Feeling / Activity
 */
function youzify_wall_mood_categories() {

	$items = array(
		'feeling' => array(
			'title' => __( 'feeling', 'youzify' ),
			'question' => __( 'How are you feeling?', 'youzify' ),
			'icon' => 'fas fa-smile',
			'color' => '#ffc107'
		),
		'celebrating' => array(
			'title' => __( 'celebrating', 'youzify' ),
			'question' => __( 'What are you celebrating?', 'youzify' ),
			'icon' => 'fas fa-birthday-cake',
			'color' => '#2196F3'
		),
		'watching' => array(
			'title' => __( 'watching', 'youzify' ),
			'question' => __( 'What are you watching?', 'youzify' ),
			'icon' => 'fas fa-glasses',
			'color' => '#F44336'
		),
		'eating' => array(
			'title' => __( 'eating', 'youzify' ),
			'question' => __( 'What are you eating?', 'youzify' ),
			'icon' => 'fas fa-utensils',
			'color' => '#707dc3'
		),
		'drinking' => array(
			'title' => __( 'drinking', 'youzify' ),
			'question' => __( 'What are you drinking?', 'youzify' ),
			'icon' => 'fas fa-glass-cheers',
			'color' => '#0dc5b4'
		),
		'travelling' => array(
			'title' => __( 'travelling', 'youzify' ),
			'question' => __( 'Where are you going?', 'youzify' ),
			'icon' => 'fas fa-map-marked-alt',
			'color' => '#f7407e'
		),
		'listening' => array(
			'title' => __( 'listening', 'youzify' ),
			'question' => __( 'What are you listening to?', 'youzify' ),
			'icon' => 'fas fa-headphones-alt',
			'color' => '#5365ca'
		),
		'looking' => array(
			'title' => __( 'looking for', 'youzify' ),
			'question' => __( 'What are you looking for?', 'youzify' ),
			'icon' => 'fas fa-search',
			'color' => '#ff5722'
		),
		'thinking' => array(
			'title' => __( 'thinking', 'youzify' ),
			'question' => __( 'What are you thinking about?', 'youzify' ),
			'icon' => 'fas fa-brain',
			'color' => '#16a1e6'
		),
		'reading' => array(
			'title' => __( 'reading', 'youzify' ),
			'question' => __( 'What are you reading?', 'youzify' ),
			'icon' => 'fas fa-book-reader',
			'color' => '#ff5b93'
		),
		'playing' => array(
			'title' => __( 'playing', 'youzify' ),
			'question' => __( 'What are you playing?', 'youzify' ),
			'icon' => 'fas fa-gamepad',
			'color' => '#ff5548'
		),
		'supporting' => array(
			'title' => __( 'supporting', 'youzify' ),
			'question' => __( 'What are you supporting?', 'youzify' ),
			'icon' => 'fas fa-thumbs-up',
			'color' => '#ff9800'
		)
	);

	return apply_filters( 'youzify_wall_mood_categories', $items );
}

/**
 * Get Feeling Emojis.
 */
function youzify_get_mood_feeling_emojis() {
	$emojis = array(
		'happy' 	=> __( 'Happy', 'youzify' ),
		'blessed' 	=> __( 'Blessed', 'youzify' ),
		'excited' 	=> __( 'Excited', 'youzify' ),
		'lovely'  	=> __( 'Lovely', 'youzify' ),
		'sad' 	  	=> __( 'Sad', 'youzify' ),
		'sleepy'  	=> __( 'Sleepy', 'youzify' ),
		'angry' 	=> __( 'Angry', 'youzify' ),
		'crazy'   	=> __( 'Crazy', 'youzify' ),
		'evil' 		=> __( 'Evil', 'youzify' ),
		'furious'	=> __( 'Furious', 'youzify' ),
		'inlove'	=> __( 'In love', 'youzify' ),
		'confused' 	=> __( 'Confused', 'youzify' ),
		'silly' 	=> __( 'Silly', 'youzify' ),
		'annoyed' 	=> __( 'Annoyed', 'youzify' ),
		'mad' 		=> __( 'Mad', 'youzify' ),
		'sick'		=> __( 'Sick', 'youzify' ),
		'shy' 		=> __( 'Shy', 'youzify' ),
		'surprised' => __( 'Surprised', 'youzify' ),
		'cool' 		=> __( 'Cool', 'youzify' ),
		'determined'=> __( 'Determined', 'youzify' ),
		'tired' 	=> __( 'Tired', 'youzify' ),
		'shocked' 	=> __( 'Shocked', 'youzify' ),
		'relaxed' 	=> __( 'Relaxed', 'youzify' ),
		'rich' 		=> __( 'Rich', 'youzify' ),
		'weird' 	=> __( 'Weird', 'youzify' ),
	);

	return $emojis;
}

/**
 * Get Feeling Emoji By type
 */
function youzify_get_mood_emojis_image( $emoji ) {
	return apply_filters( 'youzify_get_mood_emojis_image', YOUZIFY_ASSETS . 'images/emojis/' . $emoji . '.png', $emoji );
}

/**
 * Check if activity can be shared.
 */
function youzify_activity_can_share() {

	global $activities_template;

	// Init.
	$can_share = true;

	// Only public posts can be shared.
	if ( ! empty( $activities_template->activity->privacy ) && $activities_template->activity->privacy != 'public' ) {
		$can_share = false;
	}

	return apply_filters( 'youzify_activity_can_share', $can_share, $activities_template->activity->privacy );

}