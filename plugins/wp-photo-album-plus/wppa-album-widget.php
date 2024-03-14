<?php
/* wppa-album-widget.php
* Package: wp-photo-album-plus
*
* display thumbnail albums
* Version 8.4.03.002
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

class AlbumWidget extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_album_widget', 'description' => __( 'Display thumbnail images that link to albums' , 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_album_widget', __( 'WPPA+ Photo Albums' , 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget( $args, $instance ) {
		global $wpdb;

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'alb' );
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
		$page 			= in_array( wppa_opt( 'album_widget_linktype' ), wppa( 'links_no_page' ) ) ? '' : wppa_get_the_landing_page( 'album_widget_linkpage', __( 'Photo Albums', 'wp-photo-album-plus' ) );
		$max  			= wppa_opt( 'album_widget_count' ) ? wppa_opt( 'album_widget_count' ) : '10';
		$maxw 			= wppa_opt( 'album_widget_size' );
		$maxh 			= wppa_checked( $instance['name'] ) ? $maxw + 14 + wppa_opt( 'fontsize_widget_thumb' ) : $maxw;
		$parent 		= $instance['parent'];
		$subs 			= wppa_checked( $instance['subs'] );

		switch ( $parent ) {
			case 'all':
				if ( wppa_has_many_albums() ) {
					$albums = array();
				}
				else {
					$albums = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_albums
												   WHERE id > 0 " .
												   wppa_get_album_order(), ARRAY_A );
				}
				break;
			case 'last':
				if ( wppa_has_many_albums() ) {
					$albums = array();
				}
				else {
					$albums = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_albums
												   WHERE id > 0
												   ORDER BY timestamp DESC", ARRAY_A );
				}
				break;
			default:
				$albums = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
															   WHERE a_parent = %d " .
															   wppa_get_album_order( $parent ), $parent ), ARRAY_A );
		}

		$albums = wppa_strip_void_albums( $albums );

		// Add sub albums if required
		if ( $parent != 'all' && ! empty( $albums ) && $subs ) {
			$ids = '';

			// Find existing album ids
			foreach ( $albums as $alb ) {
				$ids .= $alb['id'] . '.';
			}
			$ids = rtrim( $ids, '.' );

			// Add (grand)childrens ids
			$ids = wppa_alb_to_enum_children( $ids );
			$ids_arr = explode( '.', $ids );
			$ids_arr = wppa_strip_void_albums( $ids_arr );
			$ids = implode( ',', $ids_arr );

			// Do the new query
			$albums = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
														   WHERE id IN ( " . $ids . " ) " .
														   wppa_get_album_order( $parent ) . "
														   LIMIT %d", $max ), ARRAY_A );
		}

		$widget_content = "\n".'<!-- WPPA+ album Widget start -->';

		$count = 0;
		global $albums_used;
		global $photos_used;

		if ( wppa_has_many_albums() && in_array( $parent, array( 'all', 'last' ) ) ) {
			$widget_content .= __( 'There are too many albums for this widget', 'wp-photo-album-plus' );
		}
		elseif ( $albums ) foreach ( $albums as $album ) {

			$albums_used .= '.' . $album['id'];
			if ( $count < $max ) {

				$imageid 		= wppa_get_coverphoto_id( $album['id'] );
				$photos_used .= '.' . $imageid;
				$image 			= $imageid ? wppa_cache_photo( $imageid ) : false;
				$imgcount 		= $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE album = %s", $album['id']  ) );
				$subalbumcount 	= wppa_has_children( $album['id'] );
				$thumb 			= $image;

				// Make the HTML for current picture
				if ( $image && ( $imgcount || $subalbumcount ) ) {
					$link       = wppa_get_imglnk_a('albwidget', $image['id']);
					$file       = wppa_get_thumb_path($image['id']);
					$imgevents  = wppa_get_imgevents('thumb', $image['id'], true);
					$imgstyle_a = wppa_get_imgstyle_a( $image['id'], $file, $maxw, 'center', 'albthumb' );
					$imgstyle   = $imgstyle_a['style'];
					$width      = $imgstyle_a['width'];
					$height     = $imgstyle_a['height'];
					$cursor		= $imgstyle_a['cursor'];
					if ( wppa_switch( 'show_albwidget_tooltip') ) $title = esc_attr(strip_tags(wppa_get_album_desc($album['id'])));
					else $title = '';
					$imgurl 	= wppa_get_thumb_url( $image['id'], true, '', $width, $height );
				}
				else {
					$link       = '';
					$file 		= '';
					$imgevents  = '';
					$imgstyle   = 'width:'.$maxw.';height:'.$maxh.';';
					$width      = $maxw;
					$height     = $maxw; // !!
					$cursor		= 'default';
					$title 		= __( 'Upload at least 1 photos to this album!', 'wp-photo-album-plus' );
					if ( $imageid ) {	// The 'empty album has a cover image
						$file       = wppa_get_thumb_path( $image['id'] );
						$imgstyle_a = wppa_get_imgstyle_a( $image['id'], $file, $maxw, 'center', 'albthumb' );
						$imgstyle   = $imgstyle_a['style'];
						$width      = $imgstyle_a['width'];
						$height     = $imgstyle_a['height'];
						$imgurl 	= wppa_get_thumb_url( $image['id'], true, '', $width, $height );
					}
					else {
						$imgurl		= wppa_get_imgdir().'album32.png';
					}
				}

				if ( $imageid ) {
					$imgurl = wppa_fix_poster_ext( $imgurl, $image['id'] );
				}

				if ( $imgcount || ! wppa_checked( $instance['skip'] ) ) {

					$widget_content .=
					'<div
						class="wppa-widget"
						style="' .
							'width:' . strval( intval( $maxw ) ) . 'px;' .
							'height:' . strval( intval( $maxh ) ) . 'px;' .
							'margin:4px;' .
							'display:inline;' .
							'text-align:center;' .
							'float:left;' .
							'overflow:hidden"
						data-wppa="yes">';

					if ( $link ) {
						if ( $link['is_url'] ) {	// Is a href
							$widget_content .= '
							<a
								href="' . esc_url( $link['url'] ) . '"
								title="' . esc_attr( $title ) . '"
								target="' . esc_attr( $link['target'] ) . '">';

							// Video?
							if ( $imageid && wppa_is_video( $image['id'] ) ) {
								$widget_content .= wppa_get_video_html( array( 	'id' 			=> $image['id'],
																				'width' 		=> $width,
																				'height' 		=> $height,
																				'controls' 		=> false,
																				'margin_top' 	=> $imgstyle_a['margin-top'],
																				'margin_bottom' => $imgstyle_a['margin-bottom'],
																				'cursor' 		=> 'pointer',
																				'events' 		=> $imgevents,
																				'tagid' 		=> 'i-'.$image['id'].'-'.wppa( 'mocc' ),
																				'title' 		=> $title
																			 )
																	 );
							}
							else {
								$widget_content .= '
								<img
									id="i-' . strval( intval( $image['id'] ) ) . '-' . wppa( 'mocc' ) . '"
									title="' . esc_attr( $title ) . '"
									src="' . esc_url( $imgurl ) . '"
									style="' . esc_attr( $imgstyle . ' cursor:pointer;' ) . '" ' .
									$imgevents . ' ' .
									wppa_get_imgalt( $image['id'] ) . '
								/>';
							}
							$widget_content .= '
							</a>';
						}
						elseif ( $link['is_lightbox'] ) {
							$thumbs = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->wppa_photos WHERE album = %s ".wppa_get_photo_order($album['id']), $album['id']), 'ARRAY_A');
							if ( $thumbs ) foreach ( $thumbs as $thumb ) {
								$title = wppa_get_lbtitle('alw', $thumb['id']);
								if ( wppa_is_video( $thumb['id']  ) ) {
									$siz['0'] = wppa_get_videox( $thumb['id'] );
									$siz['1'] = wppa_get_videoy( $thumb['id'] );
								}
								else {
									$siz['0'] = wppa_get_photox( $thumb['id'] );
									$siz['1'] = wppa_get_photoy( $thumb['id'] );
								}
								$link 		= wppa_get_photo_url( $thumb['id'], true, '', $siz['0'], $siz['1'] );
								$is_video 	= wppa_is_video( $thumb['id'] );
								$has_audio 	= wppa_has_audio( $thumb['id'] );
								$is_pdf 	= wppa_is_pdf( $thumb['id'] );

								$widget_content .= '
									<a href="' . esc_url( $link ) . '"' .
										' data-id="' . wppa_encrypt_photo( $thumb['id'] ) . '"' .
										( $is_video ? ' data-videohtml="' . esc_attr( wppa_get_video_body( $thumb['id'] ) ) . '"' .
										' data-videonatwidth="' . esc_attr( wppa_get_videox( $thumb['id'] ) ) . '"' .
										' data-videonatheight="' . esc_attr( wppa_get_videoy( $thumb['id'] ) ) . '"' : '' ) .
										( $has_audio ? ' data-audiohtml="' . esc_attr( wppa_get_audio_body( $thumb['id'] ) ) . '"' : '' ) .
										( $is_pdf ? ' data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $thumb['id'] ) ) .'"' : '' ) .
										' data-rel="wppa[alw-' . wppa( 'mocc' ) . '-' . $album['id'] . ']"' .
										' ' . 'data-lbtitle' . '="' . esc_attr( $title ) . '"' .
										wppa_get_lb_panorama_full_html( $id ) .
										' data-alt="' . esc_attr( wppa_get_imgalt( $thumb['id'], true ) ) . '"' .
										' style="cursor:' . wppa_wait() . ';"' .
										' onclick="return false;"' .
										' >';
								if ( $thumb['id'] == $image['id'] ) {		// the cover image
									if ( wppa_is_video( $image['id'] ) ) {
										$widget_content .= wppa_get_video_html( array( 	'id' 			=> $image['id'],
																						'width' 		=> $width,
																						'height' 		=> $height,
																						'controls' 		=> false,
																						'margin_top' 	=> $imgstyle_a['margin-top'],
																						'margin_bottom' => $imgstyle_a['margin-bottom'],
																					//	'cursor' 		=> $cursor,
																						'events' 		=> $imgevents,
																						'tagid' 		=> 'i-'.$image['id'].'-'.wppa( 'mocc' ),
																						'title' 		=> wppa_zoom_in( $image['id'] ),
																					 )
																			 );
									}
									else {
										$widget_content .= '
										<img
											id="i-' . strval( intval( $image['id'] ) ) . '-' . wppa( 'mocc' ) . '"
											title="' . esc_attr( wppa_zoom_in( $image['id'] ) ) . '"
											src="' . esc_url( $imgurl ) . '"
											style="' . esc_attr( $imgstyle ) . '" ' .
											$imgevents . ' ' .
											wppa_get_imgalt( $image['id'] ) . '
										/>';
									}
								}
								$widget_content .= '
								</a>';
							}
						}
						else { // Is an onclick unit
							if ( $imageid && wppa_is_video( $image['id'] ) ) {
								$widget_content .= wppa_get_video_html( array( 	'id' 			=> $image['id'],
																				'width' 		=> $width,
																				'height' 		=> $height,
																				'controls' 		=> false,
																				'margin_top' 	=> $imgstyle_a['margin-top'],
																				'margin_bottom' => $imgstyle_a['margin-bottom'],
																				'cursor' 		=> 'pointer',
																				'events' 		=> $imgevents.' onclick="'.$link['url'].'"',
																				'tagid' 		=> 'i-'.$image['id'].'-'.wppa( 'mocc' ),
																				'title' 		=> $title,
																			 )
																	 );
							}
							else {
								$widget_content .= '
								<img
									id="i-' . strval( intval( $image['id'] ) ) . '-' . wppa( 'mocc' ) . '"
									title="' . esc_attr( $title ) . '"
									src="' . esc_url( $imgurl ) . '"
									style="' . esc_attr( $imgstyle . ' cursor:pointer;' ) . '" ' .
									$imgevents . ' ' . '
									onclick="' . esc_url( $link['url'] ) . '" ' .
									wppa_get_imgalt( $image['id'] ) . '
								/>';
							}
						}
					}
					else {
						if ( $imageid && wppa_is_video( $image['id'] ) ) {
							$widget_content .= wppa_get_video_html( array( 	'id' 			=> $image['id'],
																			'width' 		=> $width,
																			'height' 		=> $height,
																			'controls' 		=> false,
																			'margin_top' 	=> $imgstyle_a['margin-top'],
																			'margin_bottom' => $imgstyle_a['margin-bottom'],
																			'cursor' 		=> 'pointer',
																			'events' 		=> $imgevents,
																			'tagid' 		=> 'i-'.$image['id'].'-'.wppa( 'mocc' ),
																			'title' 		=> $title,
																		 )
																 );
						}
						else {
							$widget_content .= '
							<img
								id="i-' . ( $imageid ? wppa_get_imgalt( $image['id'] ) : '' ) . '-' . wppa( 'mocc' ) . '"
								title="' . esc_attr( $title ) . '"
								src="' . esc_url( $imgurl ) . '"
								style="' . esc_attr( $imgstyle ) . '" ' .
								$imgevents . ' ' .
								( $imageid ? wppa_get_imgalt( $image['id'] ) : '' ) . '
							/>';
						}
					}

					if ( wppa_checked( $instance['name'] ) ) {
						$widget_content .= '
						<span style="font-size:' . strval( intval( wppa_opt( 'fontsize_widget_thumb' ) ) ) . 'px; min-height:100%">' .
							htmlspecialchars( __( stripslashes( $album['name'] ) ) ) . '
						</span>';
					}

					$widget_content .= '
					</div>';

					$count++;
				}
			}
		}
		else {
			$widget_content .= __( 'There are no albums (yet)', 'wp-photo-album-plus' );
		}

		$widget_content .= '<div style="clear:both"></div>';

		// End widget content
		if ( ! $cache ) $widget_content .= wppa_widget_timer( 'show', $widget_title );

		$result =  "\n" . $before_widget;
		if ( ! empty( $widget_title ) ) {
			$result .= $before_title . $widget_title . $after_title;
		}
		$result .= $widget_content . $after_widget;

		wppa_echo( $result );

		// Cache?
		if ( $cache ) {
			wppa_save_cache_file( ['file' => $cachefile, 'data' => $result] );
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
		global $wpdb;

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Widget title
		wppa_echo(
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) ) );

		// Parent album selection
		$albs = $wpdb->get_results( "SELECT id, name FROM $wpdb->wppa_albums ORDER BY name", ARRAY_A );
		$albs = wppa_add_paths( $albs );
		$albs = wppa_array_sort( $albs, 'name' );


		$options 	= array(
							__( '--- all albums ---', 'wp-photo-album-plus' ),
							__( '--- all generic albums ---', 'wp-photo-album-plus' ),
							__( '--- all separate albums ---', 'wp-photo-album-plus' ),
							__( '--- most recently added albums ---', 'wp-photo-album-plus' ),

						);

		$values 	= array(
							'all',
							'0',
							'-1',
							'last',
						);

		$disabled 	= array(
							false,
							false,
							false,
							false,
						);

		if ( count( $albs ) <= wppa_opt( 'photo_admin_max_albums' ) ) {
			if ( $albs ) foreach( $albs as $alb ) {
				$options[] 	= $alb['name'];
				$values[] 	= $alb['id'];
				$disabled[] = ! wppa_has_children( $alb['id'] );
			}
		}

		wppa_widget_selection( $this, 'parent', $instance['parent'],  __( 'Album selection or Parent album', 'wp-photo-album-plus' ), $options, $values, $disabled );

		// Include sub albums
		wppa_widget_checkbox( $this, 'subs', $instance['subs'], __( 'Include sub albums', 'wp-photo-album-plus' ) );

		// Show album name?
		wppa_widget_checkbox( $this, 'name', $instance['name'], __( 'Show album names', 'wp-photo-album-plus' ) );

		// Skip empty albums?
		wppa_widget_checkbox( $this, 'skip', $instance['skip'], __( 'Skip "empty" albums', 'wp-photo-album-plus' ) );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );

		// Cache
		wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) );

		// Explanation
		if ( current_user_can( 'wppa_settings' ) ) {
			wppa_echo(
			'<p>' .
				__( 'You can set the sizes in this widget in the <b>Photo Albums -> Settings</b> admin page.', 'wp-photo-album-plus' ) .
				' ' . __( 'Basic settings -> Widgets -> I -> Items 10 and 11', 'wp-photo-album-plus' ) .
				wppa_see_also( 'widget', '1', '10.11' ) .
			'</p>' );
		};
    }

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Thumbnail Albums', 'wp-photo-album-plus' ),
							'parent' 	=> '0',
							'subs' 		=> 'no',
							'name' 		=> 'no',
							'skip' 		=> 'no',
							'logonly' 	=> 'no',
							'cache' 	=> 'no',
							);
		return $defaults;
	}

} // class AlbumWidget

// register AlbumWidget widget
add_action('widgets_init', 'wppa_register_AlbumWidget' );

function wppa_register_AlbumWidget() {
	register_widget("AlbumWidget");
}
