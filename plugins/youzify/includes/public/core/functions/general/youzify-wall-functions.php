<?php

/**
 * Activity Shortcode - Ajax Pagination
 */

add_action( 'wp_ajax_youzify_activity_load_activities', 'youzify_activity_load_activities' );
add_action( 'wp_ajax_nopriv_youzify_activity_load_activities', 'youzify_activity_load_activities' );

function youzify_activity_load_activities() {

	if ( isset( $_POST['data']['action'] ) ) {
		$_POST['data']['type'] = $_POST['data']['action'];
	}
	// Sanitize Args.
	$activity_args = array_map( 'sanitize_text_field', $_POST['data'] );

	if ( bp_has_activities( $activity_args ) ) {

		ob_start();
	echo' wal';

		while ( bp_activities() ) : bp_the_activity();

			bp_get_template_part( 'activity/entry' );

		endwhile;

		youzify_activity_load_more();

		$content = ob_get_clean();

		wp_send_json_success( $content );

	} else {
		wp_send_json_error( array(
			'message' => __( 'Sorry, there was no activity found.', 'bp-activity-shortcode' ),
		) );
	}

	die();
}

/**
 * Load More Button
 */
function youzify_activity_load_more() { ?>

	<?php if ( bp_activity_has_more_items() ) : ?>

		<li class="load-more">
			<a href="<?php bp_activity_load_more_link() ?>"><i class="fas fa-level-down-alt"></i><?php _e( 'Load More Posts', 'youzify' ); ?></a>
		</li>

	<?php endif; ?>

	<?php

}
/**
 * Activity Shortcode.
 */
function youzify_set_activity_stream_shortcode_atts( $loop ) {
    global $youzify_activity_shortcode_args;
    return shortcode_atts( $loop, $youzify_activity_shortcode_args, 'youzify_activity_stream' );
}

/**
 * Set Wall Posting Form By Role.
 */
function youzify_set_wall_posting_form_by_role( $active ) {

	global $youzify_activity_shortcode_args;

    $active = false;

    $shortcode_roles = explode( ',' , $youzify_activity_shortcode_args['form_roles'] );

    if ( ! empty( $shortcode_roles ) ) {

	    // Get Current User Data.
	    $user = get_userdata( bp_loggedin_user_id() );

	    // Get Roles.
	    $user_roles = (array) $user->roles;

	    foreach ( $shortcode_roles as $role ) {
	        if ( in_array( $role, $user_roles ) ) {
	            $active = true;
	            continue;
	        }
	    }

    }

    return $active;

}

/**
 * Get Post Like Button.
 */
function youzify_get_post_like_button() {

	// Get Activity ID.
	$activity_id = bp_get_activity_id();

	if ( ! bp_get_activity_is_favorite() ) {

		// Get Like Link.
		$like_link = bp_get_activity_favorite_link();

		// Filter.
		$button = apply_filters( 'youzify_filter_post_like_button', '<a href="'. $like_link .'" class="button fav bp-secondary-action">' . __( 'Like', 'youzify' ) . '</a>', $like_link, $activity_id );

	} else {

		// Get Unlike Link.
		$unlike_link = bp_get_activity_unfavorite_link();

		// Filter.
		$button = apply_filters( 'youzify_filter_post_unlike_button', '<a href="'. $unlike_link .'" class="button unfav bp-secondary-action">' . __( 'Unlike', 'youzify' ) . '</a>', $unlike_link, $activity_id );

	}

	return $button;

}

/**
 * Wall Post - Get Comment Button Title.
 */
function youzify_wall_get_comment_button_title() {

	// Get Comments Number.
	$comments_nbr = bp_activity_get_comment_count();

	$button_title = sprintf( _n( '<span>%s</span> <span class="stats-name">Comment</span>', '<span>%s</span> <span class="stats-name">Comments</span>', $comments_nbr, 'youzify' ), $comments_nbr );

	echo apply_filters( 'youzify_wall_get_comment_button_title', $button_title, $comments_nbr );

}

/**
 * Register Wall New Actions.
 */
function youzify_add_new_wall_post_actions() {

	// Init Vars
	$bp = buddypress();

	bp_activity_set_action(
		$bp->activity->id,
		'activity_status',
		__( 'Posted a new status', 'youzify' ),
		'youzify_activity_action_wall_posts',
		__( 'Status', 'youzify' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_quote',
		__( 'Posted a new quote', 'youzify' ),
		'youzify_activity_action_wall_posts',
		__( 'Quotes', 'youzify' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_photo',
		__( 'Posted a new photo', 'youzify' ),
		'youzify_activity_action_wall_posts',
		__( 'Photos', 'youzify' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_video',
		__( 'Posted a new video', 'youzify' ),
		'youzify_activity_action_wall_posts',
		__( 'Videos', 'youzify' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_audio',
		__( 'Posted a new audio file', 'youzify' ),
		'youzify_activity_action_wall_posts',
		__( 'Audios', 'youzify' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_slideshow',
		__( 'Posted a new slideshow', 'youzify' ),
		'youzify_activity_action_wall_posts',
		__( 'Slideshows', 'youzify' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_link',
		__( 'Posted a new link', 'youzify' ),
		'youzify_activity_action_wall_posts',
		__( 'Links', 'youzify' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_file',
		__( 'Uploaded a new file', 'youzify' ),
		'youzify_activity_action_wall_posts',
		__( 'Files', 'youzify' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->profile->id,
		'new_cover',
		__( 'Changed their profile cover', 'youzify' ),
		'youzify_activity_action_wall_posts',
		__( 'Cover', 'youzify' ),
		array( 'activity', 'member' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_giphy',
		__( 'Added a new GIF', 'youzify' ),
		'youzify_activity_action_wall_posts',
		__( 'Giphy', 'youzify' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_share',
		__( 'Shared a new post', 'youzify' ),
		'youzify_activity_action_wall_posts',
		__( 'Shared Posts', 'youzify' ),
		array( 'activity', 'group', 'member' )
	);

}

add_action( 'bp_register_activity_actions', 'youzify_add_new_wall_post_actions' );

/**
 * Activity Mood
 */
function youzify_enable_activity_mood() {
	$active = 'on' == youzify_option( 'youzify_activity_mood', 'on' ) ? true : false;
	return apply_filters( 'youzify_enable_activity_mood', $active );
}

/**
 * Activity Check In
 */
function youzify_enable_activity_checkin() {
	$active = 'on' == youzify_option( 'youzify_activity_checkin', 'on' ) ? true : false;
	return apply_filters( 'youzify_enable_activity_checkin', $active );
}

/**
 * Activity Privacy
 */
function youzify_enable_activity_privacy() {
	$active = 'on' == youzify_option( 'youzify_activity_privacy', 'on' ) ? true : false;
	return apply_filters( 'youzify_enable_activity_privacy', $active );
}

/**
 * Activity Mood
 */
function youzify_enable_activity_tag_friends() {
	$active = 'on' == youzify_option( 'youzify_activity_tag_friends', 'on' ) && bp_is_active( 'friends' ) ? true : false;
	return apply_filters( 'youzify_enable_activity_tag_friends', $active );
}

/**
 * Activity Hashtags
 */
function youzify_enable_activity_hastags() {
	$active = 'on' == youzify_option( 'youzify_activity_hashtags', 'on' ) ? true : false;
	return apply_filters( 'youzify_enable_activity_hastags', $active );
}

/**
 * Get Activity Attachments.
 */
function youzify_get_activity_attachments( $activity_id = null, $field = 'src', $component = null ) {

	if ( empty( $activity_id ) ) {
		return;
	}

	global $wpdb, $Youzify_media_table;

	$component = ! empty( $component ) ? $component : 'activity';

	// Prepare Sql
	$sql = $wpdb->prepare( "SELECT $field FROM $Youzify_media_table WHERE item_id = %d AND component = '%s'", $activity_id, $component );

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
 * Support Wall Embeds Videos Attachments.
 */
function youzify_attachments_embeds_videos() {
	return apply_filters( 'youzify_attachments_embeds_videos', array( 'youtube' => 'youtube.com', 'vimeo' => 'vimeo.com', 'dailymotion' => 'dailymotion.com' ));
}

/**
 * Get Vimeo Video Url
 */
function youzify_get_embed_video_id( $provider, $url ) {

	// Init Vars
	$id = '';
	$match = array();

	switch ( $provider ) {

		case 'youtube':

			if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match ) ) {
				if ( isset( $match[1] ) && ! empty( $match[1] ) ) {
					$id = $match[1];
				}
			}

			break;

		case 'vimeo':
		 	if ( preg_match( '%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $match ) ) {
		    	if ( isset( $match[3] ) && ! empty( $match[3] ) ) {
		        	$id = $match[3];
		    	}
		    }

			break;

		case 'dailymotion':

		 	if ( preg_match( '!^.+dailymotion\.com/(video|hub)/([^_]+)[^#]*(#video=([^_&]+))?|(dai\.ly/([^_]+))!', $url, $match ) ) {

		        if ( isset( $match[6] ) ) {
		            return $match[6];
		        }

		        if ( isset( $match[4] ) ) {
		            return $match[4];
		        }

		        return $match[2];

		    }

			break;

	}

    return apply_filters( 'youzify_get_embed_video_id', $id );

}

/**
 * Get Video Thumbnail By Provider
 **/
function youzify_get_embed_video_thumbnails( $provider, $id, $size = null ) {

	$data = array( 'provider' => $provider, 'id' => $id );

	switch ( $provider ) {

		case 'youtube':

			// Check format
			$youtube_imgs = array(
				'thumbnail' => "https://img.youtube.com/vi/$id/mqdefault.jpg",
				'medium' 	=> "https://img.youtube.com/vi/$id/sddefault.jpg",
				'full' 		=> "https://img.youtube.com/vi/$id/sddefault.jpg"
			);

			// If Image Not Working Use Other Extension.
			if ( wp_remote_retrieve_response_code( wp_remote_get( $youtube_imgs['medium'] ) ) == 404 ) {
				if ( wp_remote_retrieve_response_code( wp_remote_get( "https://i.ytimg.com/vi_webp/$id/hqdefault.webp" ) ) != '404' ) {

					$youtube_imgs = array(
						'thumbnail' => "https://i.ytimg.com/vi_webp/$id/hqdefault.webp",
						'medium' 	=> "https://i.ytimg.com/vi_webp/$id/hqdefault.webp",
						'full' 		=> "https://i.ytimg.com/vi_webp/$id/hqdefault.webp"
					);

				}

			}

			$data = array_merge( $data, $youtube_imgs );

		case 'vimeo':

			$get_thumbnail_data = youzify_file_get_contents( 'http://vimeo.com/api/v2/video/' . $id . '.php' );

			if ( ! empty( $get_thumbnail_data ) ) {

				$thumbnails = maybe_unserialize( $get_thumbnail_data );

				if ( isset( $thumbnails[0]['thumbnail_small'] ) ) {
					$data['thumbnail'] = $thumbnails[0]['thumbnail_small'];
				}

				if ( isset( $thumbnails[0]['thumbnail_medium'] ) ) {
					$data['medium'] = $thumbnails[0]['thumbnail_medium'];
				}

				if ( isset( $thumbnails[0]['thumbnail_large'] ) ) {
					$data['full'] = $thumbnails[0]['thumbnail_large'];
				}

			}

			break;

        case 'dailymotion':

            $thumbnails = json_decode( youzify_file_get_contents( "https://api.dailymotion.com/video/$id?fields=thumbnail_medium_url,thumbnail_small_url,thumbnail_large_url" ) );

            if ( isset( $thumbnails->thumbnail_small_url ) ) {
            	$data['thumbnail'] = $thumbnails->thumbnail_small_url;
            }

            if ( isset( $thumbnails->thumbnail_medium_url ) ) {
            	$data['medium'] = $thumbnails->thumbnail_medium_url;
            }

            if ( isset( $thumbnails->thumbnail_large_url ) ) {
            	$data['full'] = $thumbnails->thumbnail_large_url;
            }

            break;

	}

	if ( ! empty( $size ) ) {
		$data = isset( $data[ $size ] ) ? $data[ $size ] : '';
	}

	return apply_filters( 'youzify_get_wall_embed_video_thumbnails', $data );

}

/**
 * Remove Blog Posts Default Content
 */
add_filter( 'bp_activity_create_summary', 'youzify_remove_blog_post_excerpt', 10, 3 );

function youzify_remove_blog_post_excerpt( $summary, $content, $activity ) {

	if ( $activity['type'] == 'new_blog_post' ) {
		return '';
	}

	return $summary;

}

/**
 * Get Wall Comments.
 */
function youzify_activity_comments_count() {

	// Check if comments allowed.
	if ( 0 == bp_activity_get_comment_count() ) {
		return false;
	}

	?>

	<div class="youzify-post-comments-count"><i class="far fa-comments"></i><?php youzify_wall_get_comment_button_title(); ?></div>

	<?php

}

/**
 * Get Share Count.
 */
function youzify_activity_share_count() {

	// Get Share Count
	$share_count = bp_activity_get_meta( bp_get_activity_id(), 'youzify_activity_share_count' );

	if ( $share_count < 1 ) {
		return;
	}

	?><div class="youzify-post-shares-count youzify-trigger-who-modal" data-action="youzify_get_who_shared_post"><i class="far fa-share-square"></i><?php echo apply_filters( 'youzify_wall_get_share_button_title', sprintf( _n( '<span>%s</span> <span class="stats-name">Share</span>', '<span>%s</span> <span class="stats-name">Shares</span>', $share_count, 'youzify' ), $share_count ), $share_count ); ?></div><?php
}

/**
 * Check if Sharing Posts are enabled.
 */
function youzify_is_share_posts_active() {

	// Get Sharing value.
	$active = 'on' == youzify_option( 'youzify_share_activity_posts', 'on' ) ? true : false;

	return apply_filters( 'youzify_is_sticky_posts_active', $active );

}

/**
 * Check if Sticky Posts are Enabled.
 */
function youzify_is_sticky_posts_active() {

	$active = false;

	if ( 'on' == youzify_option( 'youzify_enable_activity_sticky_posts', 'on' ) || 'on' == youzify_option( 'youzify_enable_groups_sticky_posts', 'on' ) ) {
		$active = true;
	}

	return apply_filters( 'youzify_is_sticky_posts_active', $active );
}

/**
 * Get Activity Layout
 */
function youzify_get_activity_layout() {

	// Get Layout
	$activity_layout = youzify_option( 'youzify_activity_stream_layout', 'youzify-wall-right-sidebar' );

	switch ( $activity_layout ) {

		case 'youzify-wall-right-sidebar':
			$layout = 'youzify-right-sidebar-layout';
			break;

		case 'youzify-wall-left-sidebar':
			$layout = 'youzify-left-sidebar-layout';
			break;

		case 'youzify-wall-3columns':
			$layout = 'youzify-3columns-layout';
			break;

	}

	return apply_filters( 'youzify_get_activity_layout', $layout, $activity_layout );
}