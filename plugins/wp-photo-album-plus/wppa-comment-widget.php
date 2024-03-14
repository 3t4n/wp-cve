<?php
/* wppa-comment-widget.php
* Package: wp-photo-album-plus
*
* display the recent commets on photos
* Version 8.4.03.002
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

class wppaCommentWidget extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_comment_widget', 'description' => __( 'Display comments on Photos', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_comment_widget', __( 'WPPA+ Comments on Photos', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {
		global $wpdb;

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'com' );
		wppa_bump_mocc( $this->id );
        extract( $args );
		$instance 		= wppa_parse_args( (array) $instance, $this->get_defaults() );
		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );
		$cache 			= wppa_cache_widget( $instance['cache'] );
		$cachefile 		= wppa_get_widget_cache_path( $this->id );

		// Logged in only and logged out?
		if ( wppa_checked( $instance['logonly'] ) && ! is_user_logged_in() ) {
			return;
		}
		// Hide widget if not logged in and login required to see comments
		if ( wppa_switch( 'comment_view_login' ) && ! is_user_logged_in() ) {
			return;
		}

		// Cache?
		if ( $cache && wppa_is_file( $cachefile ) ) {
			wppa_echo( wppa_get_contents( $cachefile ) );
			wppa_update_option( 'wppa_cache_hits', wppa_get_option( 'wppa_cache_hits', 0 ) +1 );
			wppa_echo( wppa_widget_timer( 'show', $widget_title, true ) );
			wppa( 'in_widget', false );
			return;
		}

		// Other inits
		$page 			= in_array( wppa_opt( 'comment_widget_linktype' ), wppa( 'links_no_page' ) ) ? '' : wppa_get_the_landing_page( 'comment_widget_linkpage', __( 'Recently commented photos', 'wp-photo-album-plus' ) );
		$max  			= wppa_opt( 'comten_count' );
		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );
		$photo_ids 		= wppa_get_comten_ids( $max );
		$maxw 			= strval( intval( wppa_opt( 'comten_size' ) ) );
		$maxh 			= $maxw + 18;

		// Make the widget content
		$widget_content = "\n".'<!-- WPPA+ Comment Widget start -->';

		global $photos_used;

		if ( $photo_ids ) foreach( $photo_ids as $id ) {

			$photos_used .= '.' . $id;

			// Make the HTML for current comment
			$widget_content .= '
			<div
				class="wppa-widget"
				style="width:' . $maxw . 'px;height:' . $maxh . 'px;margin:4px;display:inline;text-align:center;float:left"
				data-wppa="yes">';

			$image = wppa_cache_photo( $id );

			if ( $image ) {

				$link       = wppa_get_imglnk_a( 'comten', $id, '', '', true );
				$file       = wppa_get_thumb_path( $id );
				$imgstyle_a = wppa_get_imgstyle_a( $id, $file, $maxw, 'center', 'comthumb' );
				$imgstyle   = $imgstyle_a['style'];
				$width      = $imgstyle_a['width'];
				$height     = $imgstyle_a['height'];
				$cursor		= $imgstyle_a['cursor'];
				$imgurl 	= wppa_get_thumb_url($id, true, '', $width, $height);

				$imgevents = wppa_get_imgevents( 'thumb', $id, true );

				$title = '';
				$comments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_comments WHERE photo = %s AND status = 'approved' ORDER BY timestamp DESC", $id ), ARRAY_A );
				if ( $comments ) {
					$first = true;
					$first_comment = $comments['0'];
					foreach ( $comments as $comment ) {
						if ( ! $first ) {
							$title .= "&#013;&#010;&#013;&#010;";
						}
						$first = false;
						$title .= $comment['user'] . ' ' . __( 'wrote' , 'wp-photo-album-plus' ) . ' ' . wppa_get_time_since( $comment['timestamp'] ).":&#013;&#010;";
						$title .= stripslashes( $comment['comment'] );
					}
				}
				$title = esc_attr( strip_tags( trim ( $title ) ) );

				$album = '0';
				$display = 'thumbs';

				$widget_content .= wppa_get_the_widget_thumb( 'comten', $image, $album, $display, $link, $title, $imgurl, $imgstyle_a, $imgevents );

			}

			else {
				$widget_content .= __( 'Photo not found', 'wp-photo-album-plus' );
			}
			$widget_content .= "\n\t".'<span class="wppa-comment-owner" style="font-size:'.wppa_opt( 'fontsize_widget_thumb' ).'px; cursor:pointer" title="'.esc_attr($first_comment['comment']).'" >'.htmlspecialchars($first_comment['user']).'</span>';
			$widget_content .= "\n".'</div>';

		}
		else $widget_content .= __( 'There are no commented photos (yet)', 'wp-photo-album-plus' );

		$widget_content .= '<div style="clear:both"></div>';

		// Output
		$result = "\n" . $before_widget;
		if ( ! empty( $widget_title ) ) {
			$result .= $before_title . $widget_title . $after_title;
		}
		$result .= $widget_content . $after_widget;

		wppa_echo( $result );
		wppa_echo( wppa_widget_timer( 'show', $widget_title ) );

		// Cache?
		if ( $cache ) {
			wppa_save_cache_file( ['file' => $cachefile, 'data' => $result, 'other' => 'C'] );
		}

		wppa( 'in_widget', false );
    }

    /** @see WP_Widget::update */
    function update( $new_instance, $old_instance ) {

		// Completize all parms
		$instance = wppa_parse_args( $new_instance, $this->get_defaults() );

		// Sanitize certain args
		$instance['title'] 		= strip_tags( $instance['title'] );

		wppa_remove_widget_cache( $this->id );

        return $instance;
    }

    /** @see WP_Widget::form */
    function form( $instance ) {

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );

		// Cache
		wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) );

		$subtext = __( 'You can set the sizes in this widget in the <b>Photo Albums -> Settings</b> admin page.', 'wp-photo-album-plus' ) .
			' ' . __( 'Basic settings -> Widgets -> I -> Items 4 and 5', 'wp-photo-album-plus' ) . '.';

		$result = '
		<p>' .
			strip_tags( wp_check_invalid_utf8( $subtext ), ["<br>", "<a>", "<i>", "<b>"] );

			if ( current_user_can( 'wppa_settings' ) ) {
				$result .= wppa_see_also( 'widget', 1, '4.5' );
			}
		$result .='</p>';
		wppa_echo( $result );

	}

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' => __( 'Comments on photos', 'wp-photo-album-plus' ),
							'logonly' 	=> 'no',
							'cache' 	=> '0',
							);
		return $defaults;
	}

} // class wppaCommentWidget

// register wppaCommentWidget widget
add_action('widgets_init', 'wppa_register_wppaCommentWidget' );

function wppa_register_wppaCommentWidget() {

	if ( wppa_get_option( 'wppa_show_comments', 'yes' ) == 'yes' ) {
		register_widget("wppaCommentWidget");
	}
}
