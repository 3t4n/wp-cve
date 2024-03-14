<?php
/* wppa-featen-widget.php
* Package: wp-photo-album-plus
*
* display the featured photos
* Version 8.4.03.002
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

class FeaTenWidget extends WP_Widget {

    // constructor
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_featen_widget', 'description' => __( 'Display thumbnails of featured photos', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_featen_widget', __( 'WPPA+ Featured Photos', 'wp-photo-album-plus' ), $widget_ops );
    }

	// @see WP_Widget::widget
    function widget($args, $instance) {
		global $wpdb;
		global $wppa_opt;

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'featen' );
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
		$page 			= in_array( wppa_opt( 'featen_widget_linktype' ), wppa( 'links_no_page' ) ) ? '' : 	wppa_get_the_landing_page( 'featen_widget_linkpage', __( 'Featured photos', 'wp-photo-album-plus' ) );
		$max 			= wppa_opt( 'featen_count' );
		$album 			= $instance['album'];
		$generic 		= ( $album == '-2' );

		switch( $album ) {

			// Owner/public
			case '-3':
				$temp = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_photos
											 WHERE status = 'featured'
											 ORDER BY RAND(" . wppa_get_randseed() . ") DESC", ARRAY_A );
				if ( $temp ) {
					$c = '0';
					$thumbs = array();
					while ( $c < $max && $c < count( $temp ) ) {
						$alb = wppa_get_photo_item( $temp[$c]['id'], 'album' );
						$own = wppa_get_album_item( $alb, 'owner' );
						if ( $own == '---public---' || $own == wppa_get_user() ) {
							$thumbs[] = $temp[$c];
						}
						$c++;
					}
				}
				else {
					$thumbs = false;
				}
				break;

			// Generic
			case '-2':
				$temp = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_photos
											 WHERE status = 'featured'
											 ORDER BY RAND(" . wppa_get_randseed() . ") DESC", ARRAY_A );
				if ( $temp ) {
					$c = '0';
					$thumbs = array();
					while ( $c < $max && $c < count( $temp ) ) {
						$alb = wppa_get_photo_item( $temp[$c]['id'], 'album' );
						if ( ! wppa_is_separate( $alb ) ) {
							$thumbs[] = $temp[$c];
						}
						$c++;
					}
				}
				else {
					$thumbs = false;
				}
				break;

			// All
			case '0':
				$thumbs = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_photos
											   WHERE status = 'featured'
											   ORDER BY RAND(" . wppa_get_randseed() . ") DESC LIMIT " . $max, ARRAY_A );
				break;

			// Album spec
			default:
				if ( wppa_is_album_visible( $album ) ) {
					$thumbs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos WHERE status= 'featured' AND album = %s ORDER BY RAND(" . wppa_get_randseed() . ") DESC LIMIT " . $max, $album ), ARRAY_A );
				}
				else {
					$thumbs = array();
				}
		}

		$widget_content = "\n".'<!-- WPPA+ FeaTen Widget start -->';
		$maxw 			= wppa_opt( 'featen_size' );
		$maxh 			= $maxw;
		$lineheight 	= wppa_opt( 'fontsize_widget_thumb' ) * 1.5;
		$maxh 			+= $lineheight;
		$count 			= '0';

		if ( $thumbs ) foreach ( $thumbs as $image ) {

			$thumb = $image;

			if ( $generic && wppa_is_separate( $thumb['album'] ) ) continue;

			// Make the HTML for current picture
			$widget_content .=
				"\n" .
				'<div' .
					' class="wppa-widget"' .
					' style="width:' . strval( intval( $maxw ) ) . 'px;height:' . strval( intval( $maxh ) ) . 'px;margin:4px;display:inline;text-align:center;float:left"' .
					' data-wppa="yes"' .
					' >';

			if ( $image ) {
				$no_album = ! $album;
				if ( $no_album ) {
					$tit 	= __( 'View the featured photos', 'wp-photo-album-plus' );
				}
				else {
					$tit 	= esc_attr( __( stripslashes( $image['description'] ) ) );
				}
				$link       = wppa_get_imglnk_a( 'featen', $image['id'], '', $tit, '', $no_album, $album );
				$file       = wppa_get_thumb_path( $image['id'] );
				$imgstyle_a = wppa_get_imgstyle_a( $image['id'], $file, $maxw, 'center', 'ttthumb' );
				$imgstyle   = $imgstyle_a['style'];
				$width      = $imgstyle_a['width'];
				$height     = $imgstyle_a['height'];
				$cursor		= $imgstyle_a['cursor'];
				$imgurl 	= wppa_get_thumb_url( $image['id'], true, '', $width, $height );
				$imgevents 	= wppa_get_imgevents( 'thumb', $image['id'], true );

				if ( $link ) {
					$title 	= esc_attr( stripslashes( $link['title'] ) );
				}
				else {
					$title 	= '';
				}

				$display 	= 'thumbs';

				$widget_content .= wppa_get_the_widget_thumb( 'featen', $image, $album, $display, $link, $title, $imgurl, $imgstyle_a, $imgevents );

			}

			// No image
			else {
				$widget_content .= __( 'Photo not found', 'wp-photo-album-plus' );
			}

			$widget_content .=
				'</div>';

			$count++;
			if ( $count == wppa_opt( 'featen_count' ) ) break;

		}

		// No thumbs
		else $widget_content .= __( 'There are no featured photos (yet)', 'wp-photo-album-plus' );

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
			wppa_save_cache_file( ['file' => $cachefile, 'data' => $result, 'photos' => '*'] );
		}

		wppa( 'in_widget', false );

    }

    // @see WP_Widget::update
    function update( $new_instance, $old_instance ) {

		// Completize all parms
		$instance = wppa_parse_args( $new_instance, $this->get_defaults() );

		// Sanitize certain args
		$instance['title'] 		= strip_tags( $instance['title'] );

		wppa_remove_widget_cache( $this->id );

        return $instance;
    }

    // @see WP_Widget::form
    function form( $instance ) {

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		$album = $instance['album'];

		// Widget title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Album selection
		$body = wppa_album_select_a( array( 'selected' 	=> $album,
											'addall' 	=> true,
											'addowner' 	=> true,
											'path' 		=> true,
											'sort'		=> true,
											) );
		wppa_widget_selection_frame( $this, 'album', $body, __( 'Album', 'wp-photo-album-plus' ) );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );

		// Cache
		wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) );

 		// Explanation
		$result = '
		<p>' .
			__( 'You can set the sizes in this widget in the <b>Photo Albums -> Settings</b> admin page.', 'wp-photo-album-plus' ) .
			' ' . __( 'Basic settings -> Widgets -> I -> Items 12 and 13', 'wp-photo-album-plus' ) . '
		</p>';
		wppa_echo( strip_tags( wp_check_invalid_utf8( $result), ["<br>", "<a>", "<i>", "<b>", "<p>"] ) );

	}

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Featured Photos', 'wp-photo-album-plus' ),
							'album' 	=> '0',
							'logonly' 	=> 'no',
							'cache' 	=> '0',
							);
		return $defaults;
	}

} // class FeaTenWidget

// register FeaTenWidget widget
add_action( 'widgets_init', 'wppa_register_FeaTenWidget' );

function wppa_register_FeaTenWidget() {
	register_widget( "FeaTenWidget" );
}
