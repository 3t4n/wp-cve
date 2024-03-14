<?php
/* wppa-topten-widget.php
* Package: wp-photo-album-plus
*
* display the top rated photos
* Version 8.4.03.002
*/

class TopTenWidget extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_topten_widget', 'description' => __( 'Display top rated photos', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_topten_widget', __( 'WPPA+ Top Ten Photos', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget( $args, $instance ) {
		global $wpdb;
		global $photos_used;

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'topten' );
		wppa_bump_mocc( $this->id );
        extract( $args );
		$instance 		= wppa_parse_args( (array) $instance, $this->get_defaults() );
		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );
		$page 			= in_array( wppa_opt( 'topten_widget_linktype' ), wppa( 'links_no_page' ) ) ? '' : wppa_get_the_landing_page('topten_widget_linkpage', __('Top Ten Photos', 'wp-photo-album-plus' ));
		$max  			= wppa_opt( 'topten_count' );
		$album 			= $instance['album'];
		$display 		= $instance['display'];
		$meanrat		= wppa_checked( $instance['meanrat'] ) ? 'yes' : false;
		$ratcount 		= wppa_checked( $instance['ratcount'] ) ? 'yes' : false;
		$viewcount 		= wppa_checked( $instance['viewcount'] ) ? 'yes' : false;
		$dlcount 		= wppa_checked( $instance['dlcount'] ) ? 'yes' : false;
		$includesubs 	= wppa_checked( $instance['includesubs'] ) ? 'yes' : false;
		$albenum 		= '';
		$medalsonly 	= wppa_checked( $instance['medalsonly'] ) ? 'yes' : false;
		$showowner 		= wppa_checked( $instance['showowner'] ) ? 'yes' : false;
		$showalbum 		= wppa_checked( $instance['showalbum'] ) ? 'yes' : false;
		$albumlinkpage 	= $showalbum ? wppa_get_the_landing_page('topten_widget_album_linkpage', __('Top Ten Photo album', 'wp-photo-album-plus' )) : '';
		$cache 			= wppa_cache_widget( $instance['cache'] );
		$cachefile 		= wppa_get_widget_cache_path( $this->id );

		// Logged in only and logged out?
		if ( wppa_checked( $instance['logonly'] ) && ! is_user_logged_in() ) {
			return;
		}

		// Cache?
		if ( $cache && wppa_is_file( $cachefile ) ) {
			echo wppa_get_contents( $cachefile );
			wppa_update_option( 'wppa_cache_hits', wppa_get_option( 'wppa_cache_hits', 0 ) +1 );
			echo wppa_widget_timer( 'show', $widget_title, true );
			wppa( 'in_widget', false );
			return;
		}

		// Make the widget content
		wppa( 'medals_only', $medalsonly );

		$likes = wppa_opt( 'rating_display_type' ) == 'likes';

		// When likes only, mean rating has no meaning, change to (rating)(like)count
		if ( $likes && $instance['sortby'] == 'mean_rating' ) {
			$instance['sortby'] = 'rating_count';
		}

		// Non-zero
		$non_zero = "";
		if ( wppa_switch( 'topten_non_zero' ) ) {
			if ( $instance['sortby'] == 'views' ) {
				$non_zero = "AND views > 0 ";
			}
			elseif ( $instance['sortby'] == 'dlcount' ) {
				$non_zero = "AND dlcount > 0";
			}
			else {
				$non_zero = "AND rating_count > 0 ";
			}
		}

		// Non-private
		$non_private = is_user_logged_in() ? "" : "AND status <> 'private' ";

		// Album specified?
		if ( $album ) {

			// All albums ?
			if ( $album == '-2' ) {
				$album = '0';
			}

			// Albums of owner is current logged in user or public?
			if ( $album == '-3' ) {
				$temp = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums
															 WHERE owner = '--- public ---'
															 OR owner = %s
															 ORDER BY id", wppa_get_user() ), ARRAY_A );
				$album = '';
				if ( $temp ) {
					foreach( $temp as $t ) {
						$album .= '.' . $t['id'];
					}
					$album = ltrim( $album, '.' );
				}
			}

			// Including sub albums?
			if ( $includesubs ) {
				$albenum = wppa_expand_enum( $album );
				$albenum = wppa_alb_to_enum_children( $albenum );

				$album = str_replace( '.', ',', $albenum );
			}

			$album = implode( ',', wppa_strip_void_albums( explode( ',', $album ) ) );

			// Doit
			if ( $medalsonly ) {
				switch ( $instance['sortby'] ) {

					case 'rating_count':
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album IN ( aaaa )
								  AND status IN ( 'gold', 'silver', 'bronze' )
								  $non_zero
								  ORDER BY rating_count DESC, mean_rating DESC, views DESC
								  LIMIT %d";
						break;

					case 'views':
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album IN ( aaaa )
								  AND status IN ( 'gold', 'silver', 'bronze' )
								  $non_zero
								  ORDER BY views DESC, mean_rating DESC, rating_count DESC
								  LIMIT %d";
						break;

			//		case 'mean_rating':
					default:
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album IN ( aaaa )
								  AND status IN ( 'gold', 'silver', 'bronze' )
								  $non_zero
								  ORDER BY mean_rating DESC, rating_count DESC, views DESC
								  LIMIT %d";
						break;

				}
				$thumbs = $wpdb->get_results( $wpdb->prepare( str_replace( 'aaaa', $album, $query ), $max ), ARRAY_A );
			}

			else {
				switch ( $instance['sortby'] ) {

					case 'rating_count':
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album IN ( aaaa )
								  $non_zero
								  ORDER BY rating_count DESC, mean_rating DESC, views DESC
								  LIMIT %d";
						break;

					case 'views':
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album IN ( aaaa )
								  $non_zero
								  ORDER BY views DESC, mean_rating DESC, rating_count DESC
								  LIMIT %d";
						break;

					case 'mean_rating':
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album IN ( aaaa )
								  $non_zero
								  ORDER BY mean_rating DESC, rating_count DESC, views DESC
								  LIMIT %d";
						break;

				//	case 'dlcount':
					default:
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album IN ( aaa )
								  $non_zero
								  ORDER BY dlcount DESC, mean_rating DESC, rating_count DESC, views DESC
								  LIMIT %d";
						break;
				}
				$thumbs = $wpdb->get_results( $wpdb->prepare( str_replace( 'aaaa', $album, $query ), $max ), ARRAY_A );
			}
		}

		// No album specified
		else {

			if ( $medalsonly ) {
				switch ( $instance['sortby'] ) {

					case 'rating_count':
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album > 0
								  AND status IN ( 'gold', 'silver', 'bronze' )
								  $non_zero
								  ORDER BY rating_count DESC, mean_rating DESC, views DESC
								  LIMIT %d";
						break;

					case 'views':
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album > 0
								  AND status IN ( 'gold', 'silver', 'bronze' )
								  $non_zero
								  ORDER BY views DESC, mean_rating DESC, rating_count DESC
								  LIMIT %d";
						break;

					case 'mean_rating':
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album > 0
								  AND status IN ( 'gold', 'silver', 'bronze' )
								  $non_zero
								  ORDER BY mean_rating DESC, rating_count DESC, views DESC
								  LIMIT %d";
						break;

				//	case 'dlcount':
					default:
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album > 0
								  AND status IN ( 'gold', 'silver', 'bronze' )
								  $non_zero
								  ORDER BY dlcount DESC, mean_rating DESC, rating_count DESC, views DESC
								  LIMIT %d";
						break;
				}
				$thumbs = $wpdb->get_results( $wpdb->prepare( $query, $max ), ARRAY_A );
			}

			else {
				switch ( $instance['sortby'] ) {

					case 'rating_count':
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album > 0
								  $non_zero
								  ORDER BY rating_count DESC, mean_rating DESC, views DESC
								  LIMIT %d";
						break;

					case 'views':
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album > 0
								  $non_zero
								  ORDER BY views DESC, mean_rating DESC, rating_count DESC
								  LIMIT %d";
						break;

					case 'mean_rating':
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album > 0
								  $non_zero
								  ORDER BY mean_rating DESC, rating_count DESC, views DESC
								  LIMIT %d";
						break;

				//	case 'dlcount':
					default:
						$query = "SELECT * FROM $wpdb->wppa_photos
								  WHERE album > 0
								  $non_zero
								  ORDER BY dlcount DESC, mean_rating DESC, rating_count DESC, views DESC
								  LIMIT %d";
						break;

				}

				$thumbs = $wpdb->get_results( $wpdb->prepare( $query, $max ), ARRAY_A );
			}
		}

		$thumbs = wppa_strip_void_photos( $thumbs );

		$widget_content = "\n".'<!-- WPPA+ TopTen Widget start -->';
		$maxw = wppa_opt( 'topten_size' );
		$maxh = $maxw;
		$lineheight = wppa_opt( 'fontsize_widget_thumb' ) * 1.5;
		$maxh += $lineheight;
		if ( $meanrat ) 	$maxh += $lineheight;
		if ( $ratcount ) 	$maxh += $lineheight;
		if ( $viewcount ) 	$maxh += $lineheight;
		if ( $showowner ) 	$maxh += $lineheight;
		if ( $showalbum ) 	$maxh += $lineheight;

		if ( $thumbs ) foreach ( $thumbs as $image ) {

			$thumb = $image;

			// Save ids for caching
			$photos_used .= '.' . $thumb['id'];

			// Make the HTML for current picture
			if ( $display == 'thumbs' ) {
				$widget_content .= '
					<div' .
						' class="wppa-widget"' .
						' style="' .
							'width:' . $maxw . 'px;' .
							'height:' . $maxh . 'px;' .
							'margin:4px;' .
							'display:inline;' .
							'text-align:center;' .
							'float:left;' .
							'"' .
						' data-wppa="yes"' .
						' >';
			}
			else {
				$widget_content .= '
					<div' .
						' class="wppa-widget"' .
						' data-wppa="yes"' .
						' >';
			}
			if ( $image ) {
				$no_album = !$album;
				if ($no_album) $tit = __('View the top rated photos', 'wp-photo-album-plus' ); else $tit = esc_attr(__(stripslashes($image['description'])));
				$compressed_albumenum = wppa_compress_enum( $albenum );
				$link       = wppa_get_imglnk_a('topten', $image['id'], '', $tit, '', $no_album, $compressed_albumenum );
				$file       = wppa_get_thumb_path($image['id']);
				$imgstyle_a = wppa_get_imgstyle_a( $image['id'], $file, $maxw, 'center', 'ttthumb');
				$imgurl 	= wppa_get_thumb_url($image['id'], true, '', $imgstyle_a['width'], $imgstyle_a['height']);
				$imgevents 	= wppa_get_imgevents('thumb', $image['id'], true);
				$title 		= $link ? esc_attr(stripslashes($link['title'])) : '';

				$widget_content .= wppa_get_the_widget_thumb('topten', $image, $album, $display, $link, $title, $imgurl, $imgstyle_a, $imgevents);

				$widget_content .= "\n\t".'<div style="font-size:'.wppa_opt( 'fontsize_widget_thumb' ).'px; line-height:'.$lineheight.'px;">';

					// Display (owner) ?
					if ( $showowner ) {
						$widget_content .= '<div>(' . $image['owner'] . ')</div>';
					}

					// Display (album) ?
					if ( $showalbum ) {
						$href = wppa_get_album_url( array( 'album' => $image['album'],
														   'page' => $albumlinkpage,
														   'type' => 'content',
														   'mocc' => '1' ) );
						$widget_content .= '<div>(<a href="' . $href . '" >' . wppa_get_album_name( $image['album'] ) . '</a>)</div>';
					}

					// Display the rating
					if ( $likes ) {
						$lt = wppa_get_like_title_a( $image['id'] );
					}
					switch ( $instance['sortby'] ) {

						case 'mean_rating':

							if ( $meanrat == 'yes' ) {
								$widget_content .=
								'<div>' .
									wppa_get_rating_by_id( $image['id'] ) .
								'</div>';
							}
							if ( $ratcount == 'yes' ) {
								$n = wppa_get_rating_count_by_id( $image['id'] );
								$widget_content .=
								'<div>' .
									sprintf( _n( '%d vote', '%d votes', $n, 'wp-photo-album-plus' ), $n ) .
								'</div>';
							}
							if ( $viewcount == 'yes' ) {
								$n = $image['views'];
								$widget_content .=
								'<div>' .
									sprintf( _n( '%d view', '%d views', $n, 'wp-photo-album-plus' ), $n ) .
								'</div>';
							}
							if ( $dlcount == 'yes' ) {
								$n = $image['dlcount'];
								if ( $n ) {
									$widget_content .=
									'<div>' .
										sprintf( _n( '%d download', '%d downloads', $n, 'wp-photo-album-plus' ), $n ) .
									'</div>';
								}
							}
							break;

						case 'rating_count':
							if ( $ratcount 	== 'yes' ) {
								$n = wppa_get_rating_count_by_id( $image['id'] );
								$widget_content .=
								'<div>' .
									( $likes ? $lt['display'] : sprintf( _n( '%d vote', '%d votes', $n, 'wp-photo-album-plus' ), $n ) ) .
								'</div>';
							}
							if ( $meanrat  	== 'yes' ) {
								$widget_content .=
								'<div>' .
									wppa_get_rating_by_id( $image['id'] ) .
								'</div>';
							}
							if ( $viewcount == 'yes' ) {
								$n = $image['views'];
								$widget_content .=
								'<div>' .
									sprintf( _n( '%d view', '%d views', $n, 'wp-photo-album-plus' ), $n ) .
								'</div>';
							}
							if ( $dlcount == 'yes' ) {
								$n = $image['dlcount'];
								if ( $n ) {
									$widget_content .=
									'<div>' .
										sprintf( _n( '%d download', '%d downloads', $n, 'wp-photo-album-plus' ), $n ) .
									'</div>';
								}
							}
							break;

						case 'views':
							if ( $viewcount == 'yes' ) {
								$n = $image['views'];
								$widget_content .=
								'<div>' .
									sprintf( _n( '%d view', '%d views', $n, 'wp-photo-album-plus' ), $n ) .
								'</div>';
							}
							if ( $meanrat  	== 'yes' ) {
								$widget_content .=
								'<div>' .
									wppa_get_rating_by_id( $image['id'] ) .
								'</div>';
							}
							if ( $ratcount 	== 'yes' ) {
								$n = wppa_get_rating_count_by_id( $image['id'] );
								$widget_content .=
								'<div>' .
									( $likes ? $lt['display'] : sprintf( _n( '%d vote', '%d votes', $n, 'wp-photo-album-plus' ), $n ) ) .
								'</div>';
							}
							if ( $dlcount == 'yes' ) {
								$n = $image['dlcount'];
								if ( $n ) {
									$widget_content .=
									'<div>' .
										sprintf( _n( '%d download', '%d downloads', $n, 'wp-photo-album-plus' ), $n ) .
									'</div>';
								}
							}
							break;

						case 'dlcount':
							if ( $dlcount == 'yes' ) {
								$n = $image['dlcount'];
								$widget_content .=
								'<div>' .
									sprintf( _n( '%d download', '%d downloads', $n, 'wp-photo-album-plus' ), $n ) .
								'</div>';
							}
							if ( $viewcount == 'yes' ) {
								$n = $image['views'];
								$widget_content .=
								'<div>' .
									sprintf( _n( '%d view', '%d views', $n, 'wp-photo-album-plus' ), $n ) .
								'</div>';
							}
							if ( $meanrat  	== 'yes' ) {
								$widget_content .=
								'<div>' .
									wppa_get_rating_by_id( $image['id'] ) .
								'</div>';
							}
							if ( $ratcount 	== 'yes' ) {
								$n = wppa_get_rating_count_by_id( $image['id'] );
								$widget_content .=
								'<div>' .
									( $likes ? $lt['display'] : sprintf( _n( '%d vote', '%d votes', $n, 'wp-photo-album-plus' ), $n ) ) .
								'</div>';
							}
							break;

						default:
							wppa_log( 'err', 'Unimplemented sortby: '. $instance['sortby'] .' in topten widget' );
							break;
					}
				$widget_content .= '</div>';
			}
			else {	// No image
				$widget_content .= __( 'Photo not found', 'wp-photo-album-plus' );
			}
			$widget_content .= "\n".'</div>';
		}
		else $widget_content .= __( 'There are no rated photos (yet)', 'wp-photo-album-plus' );

		$widget_content .= '<div style="clear:both"></div>';

		// Output
		$result = "\n" . $before_widget;
		if ( ! empty( $widget_title ) ) {
			$result .= $before_title . $widget_title . $after_title;
		}
		$result .= $widget_content . $after_widget;

		wppa_echo( $result );
		echo wppa_widget_timer( 'show', $widget_title );

		// Cache?
		if ( $cache ) {

			wppa_save_cache_file( ['file' => $cachefile, 'data' => $result, 'other' => 'R'] );
		}

		wppa( 'in_widget', false );
    }

    /** @see WP_Widget::update */
    function update( $new_instance, $old_instance ) {

		// Completize all parms
		$instance = wppa_parse_args( $new_instance, $this->get_defaults() );

		// Sanitize certain args
		$instance['title'] 	= strip_tags( $instance['title'] );
		$instance['album'] 	= strval( intval( $new_instance['album'] ) );

		wppa_remove_widget_cache( $this->id );

        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form( $instance ) {

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Album
		$body = wppa_album_select_a( array( 'selected' 	=> $instance['album'],
											'addall' 	=> true,
											'addowner' 	=> true,
											'path' 		=> true,
											'sort' 		=> true,
											) );

		wppa_widget_selection_frame( $this, 'album', $body, __( 'Album', 'wp-photo-album-plus' ) );

		// Display type
		$options = array( 	__( 'thumbnail images', 'wp-photo-album-plus' ),
							__( 'photo names', 'wp-photo-album-plus' ),
							);
		$values  = array( 	'thumbs',
							'names',
							);

		wppa_widget_selection( $this, 'display', $instance['display'], __( 'Display', 'wp-photo-album-plus' ), $options, $values );

		// Sortby
		$options = array(	__( 'Mean value', 'wp-photo-album-plus' ),
							__( 'Number of votes', 'wp-photo-album-plus' ),
							__( 'Number of views', 'wp-photo-album-plus' ),
							__( 'Number of downloads', 'wp-photo-album-plus' ),
							);
		$values  = array(	'mean_rating',
							'rating_count',
							'views',
							'dlcount',
							);

		wppa_widget_selection( $this, 'sortby', $instance['sortby'], __( 'Sort by', 'wp-photo-album-plus' ), $options, $values );

		// Include sub albums
		wppa_widget_checkbox( $this, 'includesubs', $instance['includesubs'], __( 'Include sub albums', 'wp-photo-album-plus' ) );

		// Medals only
		wppa_widget_checkbox( $this, 'medalsonly', $instance['medalsonly'], __( 'Only with medals', 'wp-photo-album-plus' ) );

		// Subtitles
		wppa_echo( '<fieldset style="padding:6px;border:1px solid lightgray;margin-top:2px"><legend>' . __( 'Subtitles', 'wp-photo-album-plus' ) .  '</legend>' );

			// Owner
			wppa_widget_checkbox( $this, 'showowner', $instance['showowner'], __( 'Owner', 'wp-photo-album-plus' ) );

			// Album
			wppa_widget_checkbox( $this, 'showalbum', $instance['showalbum'], __( 'Album', 'wp-photo-album-plus' ) );

			// Mean rating
			wppa_widget_checkbox( $this, 'meanrat', $instance['meanrat'], __( 'Mean rating', 'wp-photo-album-plus' ) );

			// Rating count
			wppa_widget_checkbox( $this, 'ratcount', $instance['ratcount'], __( 'Rating count', 'wp-photo-album-plus' ) );

			// View count
			wppa_widget_checkbox( $this, 'viewcount', $instance['viewcount'], __( 'View count', 'wp-photo-album-plus' ) );

			// Download count
			wppa_widget_checkbox( $this, 'dlcount', $instance['dlcount'], __( 'Download count', 'wp-photo-album-plus' ) );

		wppa_echo( '</fieldset>' );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );

		// Cache
		wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) );

		$result =
		'<p>' .
			__( 'You can set the sizes in this widget in the <b>Photo Albums -> Settings</b> admin page.', 'wp-photo-album-plus' ) .
			' ' . wppa_setting_path( 'b', 'widget', '1', ['2', '3'] ) .
		'</p>';
		wppa_echo( strip_tags( wp_check_invalid_utf8( $result), ["<br>", "<a>", "<i>", "<b>"] ) );

    }

	// Set defaults
	function get_defaults() {

		$defaults = array(	'title' 		=> __( 'Top Ten Photos', 'wp-photo-album-plus' ),
							'sortby' 		=> 'mean_rating',
							'album' 		=> '0',
							'display' 		=> 'thumbs',
							'meanrat' 		=> 'no',
							'ratcount' 		=> 'no',
							'viewcount' 	=> 'no',
							'dlcount' 		=> 'no',
							'includesubs' 	=> 'no',
							'medalsonly' 	=> 'no',
							'showowner' 	=> 'no',
							'showalbum' 	=> 'no',
							'logonly' 		=> 'no',
							'cache' 		=> '0',
							);
		return $defaults;
	}

} // class TopTenWidget

// register TopTenWidget widget
add_action('widgets_init', 'wppa_register_TopTenWidget' );

function wppa_register_TopTenWidget() {
	register_widget("TopTenWidget");
}
