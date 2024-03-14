<?php

use Vimeotheque\Player\Player;
use Vimeotheque\Plugin;
use Vimeotheque\Templates\Helper;
use Vimeotheque\Video_Post;


/**
 * Embed the current video post.
 *
 * Outputs the embed for the current video post in the loop.
 *
 * @param bool $echo	Echo the output.
 * @return string
 */
function vimeotheque_the_video_embed( $echo  = true ){
	$post = \Vimeotheque\Helper::get_video_post();
	$player = new Player( $post );
	
	/**
	 * Filter the player output.
	 *
	 * @since 2.2.4
	 *
	 * @param string $output	The video embed output.
	 * @param Video_Post $post 	The video post object reference.
	 * @param Player $player	The video player object reference.
	 */
	$output = apply_filters( 'vimeotheque\the_video_embed', $player->get_output( false ), $post, $player );
	
	if( $echo ){
		echo $output;
	}
	
	return $output;
}

/**
 * Display the video duration.
 *
 * Templating function that will display the video duration for the current video in the loop.
 *
 * @param string $before    Text to display before the duration.
 * @param string $after     Text to display after the duration.
 *
 * @return void
 */
function vimeotheque_the_video_duration( $before = '<span class="video-duration">', $after = '</span>' ){
	$duration = vimeotheque_get_the_video_duration();

	if( $duration ){
		echo $before . $duration . $after;
	}
}

/**
 * Get video duration.
 *
 * Get the current post video duration.
 *
 * @return string
 */
function vimeotheque_get_the_video_duration(){
	return Helper::get_the_video_duration();
}

/**
 * Display the number of video views.
 *
 * Templating function that will display the total views for the current video in the loop.
 *
 * @param string $before    Text to display before the views number.
 * @param string $after     Text to display after the views number.
 *
 * @return string
 */
function vimeotheque_the_video_views( $before = '<span class="video-views">', $after = '</span>' ){
	$post = \Vimeotheque\Helper::get_video_post();

	$output = $before . number_format_i18n( $post->stats['views'] ) . $after;

	echo $output;

	return $output;
}

/**
 * Display the number of video likes.
 *
 * Templating function that will display the total likes for the current video in the loop.
 *
 * @param string $before    Text to display before the likes number.
 * @param string $after     Text to display after the likes number.
 *
 * @return string
 */
function vimeotheque_the_video_likes( $before = '<span class="video-likes">', $after = '</span>' ){
	$post = \Vimeotheque\Helper::get_video_post();

	$output = $before . number_format_i18n( $post->stats['likes'] ) . $after;

	echo $output;

	return $output;
}

/**
 * Get the video image.
 *
 * Returns the video image HTML with or without the play button and duration overlay.
 *
 * @param string $size          Optional. Image size. Accepts any registered image size name, or an array of width and height values in pixels (in that order). Default 'post-thumbnail'.
 * @param string $attr          Optional. Query string or array of attributes. Default empty.
 * @param bool $with_overlay    Optional. Get image with orwithout the overlaying video play button.
 *
 * @return void
 */
function vimeotheque_the_post_thumbnail( $size = 'post-thumbnail', $attr = '', $with_overlay = true ){

	$thumbnail = get_the_post_thumbnail( null, $size, $attr );

	if( !empty( $thumbnail ) && $with_overlay ){
		$overlay = sprintf(
			'<div class="play-btn" style="display: none;"><img src="%s" class="overlay" /></div>',
			\Vimeotheque\Helper::get_url() . 'assets/front-end/svg/play-solid.svg'
		);

		$timer = sprintf(
			'<div class="duration" style="display: none;">%s</div>',
			vimeotheque_get_the_video_duration()
		);

		$thumbnail = sprintf(
			'<div class="vimeotheque-featured-image with-overlay">%s%s%s</div>',
			$thumbnail,
			$overlay,
			$timer
		);
	}

	echo $thumbnail;
}

if( !function_exists( 'vimeotheque_the_entry_taxonomies' ) ) {
	/**
	 * Print category and tags.
	 *
	 * Prints HTML with category and tags for current post.
	 *
	 * @return void
	 */
	function vimeotheque_the_entry_taxonomies() {
		$post = get_post();

		$categories_list = get_the_term_list( $post->ID,
			Plugin::instance()->get_cpt()->get_post_tax(), '', ', ' );

		if ( $categories_list ) {
			printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Categories', 'Used before category names.', 'codeflavors-vimeo-video-post-lite' ),
				$categories_list
			);
		}

		$tags_list = get_the_term_list( $post->ID,
			Plugin::instance()->get_cpt()->get_tag_tax(), '', ', ' );
		if ( $tags_list ) {
			printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Tags', 'Used before tag names.', 'codeflavors-vimeo-video-post-lite' ),
				$tags_list
			);
		}
	}
}