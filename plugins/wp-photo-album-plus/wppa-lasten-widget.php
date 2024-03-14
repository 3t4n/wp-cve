<?php
/* wppa-lasten-widget.php
* Package: wp-photo-album-plus
*
* display the last uploaded photos
* Version 8.4.03.002
*/

class LasTenWidget extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_lasten_widget', 'description' => __( 'Display most recently uploaded photos', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_lasten_widget', __( 'WPPA+ Last Ten Photos', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {
		global $wpdb;
		global $wppa_opt;

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'lasten' );
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

		// Cache?
		if ( $cache && wppa_is_file( $cachefile ) ) {
			wppa_echo( wppa_get_contents( $cachefile ) );
			wppa_update_option( 'wppa_cache_hits', wppa_get_option( 'wppa_cache_hits', 0 ) +1 );
			wppa_echo( wppa_widget_timer( 'show', $widget_title, true ) );
			wppa( 'in_widget', false );
			return;
		}

		// Other inits
		$page 			= in_array( wppa_opt( 'lasten_widget_linktype' ), wppa( 'links_no_page' ) ) ? '' : wppa_get_the_landing_page( 'lasten_widget_linkpage', __( 'Last Ten Photos', 'wp-photo-album-plus' ) );
		$max  			= wppa_opt( 'lasten_count' );
		$album 			= $instance['album'];
		$timesince 		= wppa_checked( $instance['timesince'] ) ? 'yes' : 'no';
		$display 		= $instance['display'];
		$albumenum 		= $instance['albumenum'];
		$subs 			= wppa_checked( $instance['includesubs'] );

		switch ( $album ) {
			case '-99': // 'Multiple see below' is a list of id, seperated by comma's
				$album = str_replace( ',', '.', $albumenum );
				if ( $subs ) {
					$album = wppa_expand_enum( wppa_alb_to_enum_children( $album ) );
				}
				$album = str_replace( '.', ',', $album );
				break;
			case '0': // ---all---
				break;
			case '-2': // ---generic---
				$albs = $wpdb->get_results( "SELECT id FROM $wpdb->wppa_albums WHERE a_parent = '0'", ARRAY_A );
				$album = '';
				foreach ( $albs as $alb ) {
					$album .= '.' . $alb['id'];
				}
				$album = ltrim( $album, '.' );
				if ( $subs ) {
					$album = wppa_expand_enum( wppa_alb_to_enum_children( $album ) );
				}
				$album = str_replace( '.', ',', $album );
				break;
			default:
				if ( $subs ) {
					$album = wppa_expand_enum( wppa_alb_to_enum_children( $album ) );
					$album = str_replace( '.', ',', $album );
				}
				break;
		}
		$album = trim( $album, ',' );
		$album_arr = explode( ',', $album );
		$album_arr = wppa_strip_void_albums( $album_arr );
		$album = implode( ',', $album_arr );

		// Eiter look at timestamp or at date/time modified
		$order_by = wppa_switch( 'lasten_use_modified' ) ? 'modified' : 'timestamp';

		// If you want only 'New' photos in the selection, the period must be <> 0;
		if ( wppa_switch( 'lasten_limit_new' ) && wppa_opt( 'max_photo_newtime' ) ) {
			$newtime = " " . $order_by . " >= ".( time() - wppa_opt( 'max_photo_newtime' ) );
			if ( $album ) {
				$q = "SELECT * FROM $wpdb->wppa_photos
					  WHERE (".$newtime.")
					  AND album IN ( ".$album." )
					  ORDER BY " . $order_by . " DESC LIMIT " . $max;
			}
			else {
				$q = "SELECT * FROM $wpdb->wppa_photos
					  WHERE (".$newtime.")
					  AND album > 0
					  ORDER BY " . $order_by . " DESC LIMIT " . $max;
			}
		}
		else {
			if ( $album ) {
				$q = "SELECT * FROM $wpdb->wppa_photos
				      WHERE album IN ( ".$album." )
					  ORDER BY " . $order_by . " DESC LIMIT " . $max;
			}
			else {
				$q = "SELECT * FROM $wpdb->wppa_photos
					  WHERE album > 0
					  ORDER BY " . $order_by . " DESC LIMIT " . $max;
			}
		}

		$thumbs 		= $wpdb->get_results( $q, ARRAY_A );
		$thumbs 		= wppa_strip_void_photos( $thumbs );

		$widget_content = "\n".'<!-- WPPA+ LasTen Widget start -->';
		$maxw 			= wppa_opt( 'lasten_size' );
		$maxh 			= $maxw;
		$lineheight 	= wppa_opt( 'fontsize_widget_thumb' ) * 1.5;
		$maxh 			+= $lineheight;

		if ( $timesince == 'yes' ) $maxh += $lineheight;

		$count = '0';

		if ( $thumbs ) foreach ( $thumbs as $image ) {

			$thumb = $image;

			// Make the HTML for current picture
			if ( $display == 'thumbs' ) {
				$widget_content .= '
				<div class="wppa-widget"' .
					' style="width:'.$maxw.'px; height:'.$maxh.'px; margin:4px; display:inline; text-align:center; float:left"' .
					' data-wppa="yes"' .
					' >';
			}
			else {
				$widget_content .= "\n".'<div class="wppa-widget" >';
			}
			if ( $image ) {
				$no_album = !$album;
				if ($no_album) $tit = __( 'View the most recent uploaded photos', 'wp-photo-album-plus' ); else $tit = esc_attr(__(stripslashes($image['description'])));
				$link       = wppa_get_imglnk_a('lasten', $image['id'], '', $tit, '', $no_album, str_replace( ',', '.', $album ) );
				$file       = wppa_get_thumb_path($image['id']);
				$imgstyle_a = wppa_get_imgstyle_a( $image['id'], $file, $maxw, 'center', 'ltthumb');
				$imgurl 	= wppa_get_thumb_url( $image['id'], true, '', $imgstyle_a['width'], $imgstyle_a['height'] );
				$imgevents 	= wppa_get_imgevents('thumb', $image['id'], true);
				$title 		= $link ? esc_attr(stripslashes($link['title'])) : '';

				$widget_content .= wppa_get_the_widget_thumb('lasten', $image, $album, $display, $link, $title, $imgurl, $imgstyle_a, $imgevents);

				$widget_content .= "\n\t".'<div style="font-size:' . wppa_opt( 'fontsize_widget_thumb' ) . 'px; line-height:'.$lineheight.'px;">';
				if ( $timesince == 'yes' ) {
					$widget_content .= "\n\t".'<div>' . htmlspecialchars( wppa_get_time_since( $image[$order_by] ) ) . '</div>';
				}
				$widget_content .= '</div>';
			}
			else {	// No image
				$widget_content .= __( 'Photo not found', 'wp-photo-album-plus' );
			}
			$widget_content .= "\n".'</div>';
			$count++;
			if ( $count == wppa_opt( 'lasten_count' ) ) break;

		}
		else $widget_content .= __( 'There are no uploaded photos (yet)', 'wp-photo-album-plus' );

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
			wppa_save_cache_file( ['file' => $cachefile, 'data' => $result, 'albums' => '*', 'photos' => '*'] );
		}

		wppa( 'in_widget', false );
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {

		// Completize all parms
		$instance = wppa_parse_args( $new_instance, $this->get_defaults() );

		// Sanitize certain args
		$instance['title'] 			= strip_tags( $instance['title'] );
		if ( $instance['album'] != '-99' ) $instance['albumenum'] = '';

		wppa_remove_widget_cache( $this->id );

        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
		global $wppa_opt;

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Album
		$body = wppa_album_select_a( array( 'selected' 		=> $instance['album'],
											'addall' 		=> true,
											'addmultiple' 	=> true,
											'addnumbers' 	=> true,
											'path' 			=> true,
											) );

		wppa_widget_selection_frame( $this, 'album', $body, __( 'Album', 'wp-photo-album-plus' ) );

		// Album enumeration
		wppa_widget_input( 	$this, 'albumenum', $instance['albumenum'], __( 'Albums', 'wp-photo-album-plus' ), __( 'Select --- multiple see below --- in the Album selection box. Then enter album numbers seperated by commas', 'wp-photo-album-plus' ) );

		// Include sub albums
		wppa_widget_checkbox( $this, 'includesubs', $instance['includesubs'], __( 'Include sub albums', 'wp-photo-album-plus' ) );

		// Display type
		$options = array( 	__( 'thumbnail images', 'wp-photo-album-plus' ),
							__( 'photo names', 'wp-photo-album-plus' ),
							);
		$values  = array(	'thumbs',
							'names',
							);

		wppa_widget_selection( $this, 'display', $instance['display'], __( 'Display type', 'wp-photo-album-plus' ), $options, $values );

		// Time since
		wppa_widget_checkbox( $this, 'timesince', $instance['timesince'], __( 'Show time since', 'wp-photo-album-plus' ) );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );

		// Cache
		wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) );

		$result = '
		<p>' .
			__( 'You can set the sizes in this widget in the <b>Photo Albums -> Settings</b> admin page.', 'wp-photo-album-plus' ) .
			' ' . wppa_setting_path( 'b', 'widget', '1', ['8', '9'] ) . '
		</p>';
		wppa_echo( strip_tags( wp_check_invalid_utf8( $result), ["<br>", "<a>", "<i>", "<b>", "<p>"] ) );
    }

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Last Ten Photos', 'wp-photo-album-plus' ),
							'album' 		=> '0',
							'albumenum' 	=> '',
							'timesince' 	=> 'no',
							'display' 		=> 'thumbs',
							'includesubs' 	=> 'no',
							'logonly' 		=> 'no',
							'cache' 		=> '0',
							);
		return $defaults;
	}

} // class LasTenWidget

// register LasTenWidget widget
add_action('widgets_init', 'wppa_register_LasTenWidget' );

function wppa_register_LasTenWidget() {
	register_widget("LasTenWidget");
}
