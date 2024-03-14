<?php
/* wppa-album-navigator-widget.php
* Package: wp-photo-album-plus
*
* display album names linking to content
* Version: 8.4.03.002
*/

class AlbumNavigatorWidget extends WP_Widget {

    /** constructor */
    function __construct() {

		$widget_ops = array( 'classname' 	=> 'wppa_album_navigator_widget', 'description' 	=> __( 'Display hierarchical album navigator', 'wp-photo-album-plus' ), );
		parent::__construct( 'wppa_album_navigator_widget', __( 'WPPA+ Album Navigator', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'albnav' );
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
		$page 			= wppa_get_the_landing_page( 'album_navigator_widget_linkpage', __( 'Photo Albums', 'wp-photo-album-plus' ) );
		$parent 		= $instance['parent'];
		$skip 			= wppa_checked( $instance['skip'] );

		// Start widget content
		$widget_content = "\n".'<!-- WPPA+ Album Navigator Widget start -->';

		// Body
		if ( wppa_has_many_albums() ) {
			$widget_content .= __( 'There are too many albums in the system for this widget', 'wp-photo-album-plus' );
		}
		else {
			if ( $parent == 'all' ) {
				$widget_content .= $this->do_album_navigator( '0', $page, $skip, '', '', $cache );
				$widget_content .= $this->do_album_navigator( '-1', $page, $skip, '', '', $cache );
			}
			elseif ( $parent == 'owner' ) {
				$widget_content .= $this->do_album_navigator( '0', $page, $skip, '', " AND ( owner = '--- public ---' OR owner = '".wppa_get_user()."' ) ", $cache );
				$widget_content .= $this->do_album_navigator( '-1', $page, $skip, '', " AND ( owner = '--- public ---' OR owner = '".wppa_get_user()."' ) ", $cache );
			}
			else {
				$widget_content .= $this->do_album_navigator( $parent, $page, $skip, '', '', $cache );
			}
			$widget_content .= '<div style="clear:both" data-wppa="yes"></div>';
		}

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
			global $albums_used;
			$albums_used = '*';
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

		// Title
		wppa_echo(
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) ) );

		// This widget can not be used when there are too many albums
		if ( wppa_has_many_albums() ) {
			wppa_echo( __( 'There are too many albums in the system for this widget', 'wp-photo-album-plus' ) );
		}

		// Parent
		else {
			$options = array( 	__( '--- all albums ---', 'wp-photo-album-plus' ),
								__( '--- all generic albums ---', 'wp-photo-album-plus' ),
								__( '--- all separate albums ---', 'wp-photo-album-plus' ),
								__( '--- owner/public ---', 'wp-photo-album-plus' ),
								);
			$values  = array(	'all',
								'0',
								'-1',
								'owner',
								);
			$disabled = array(	false,
								false,
								false,
								false,
								);
			$albs = $wpdb->get_results( "SELECT name, id FROM $wpdb->wppa_albums ORDER BY name", ARRAY_A );
			$albs = wppa_add_paths( $albs );
			$albs = wppa_array_sort( $albs, 'name' );

			if ( $albs ) foreach( $albs as $alb ) {
				$options[] 	= __( stripslashes( $alb['name'] ) );
				$values[]  	= $alb['id'];
				$disabled[] = false;
			}

			wppa_widget_selection( $this, 'parent', $instance['parent'], __( 'Album selection or Parent album', 'wp-photo-album-plus' ), $options, $values, $disabled );

			// Skip empty
			wppa_widget_checkbox( $this, 'skip', $instance['skip'], __( 'Skip "empty" albums', 'wp-photo-album-plus' ) );
		}

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );

		// Cache
		wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) );
	}

	function get_widget_id() {
		$widgetid = substr( $this->get_field_name( 'txt' ), strpos( $this->get_field_name( 'txt' ), '[' ) + 1 );
		$widgetid = substr( $widgetid, 0, strpos( $widgetid, ']' ) );
		return $widgetid;
	}

	function do_album_navigator( $parent, $page, $skip, $propclass, $extraclause, $cache ) {
	global $wpdb;
	static $level;
	static $ca;

		if ( ! $level ) {
			$level = '1';
			$ca = wppa_get( 'album' );
			$ca = wppa_force_numeric_else( $ca, '0' );
			if ( $ca && ! wppa_album_exists( $ca ) ) {
				$ca = '0';
			}

			// Ignore current album when caching, i.e. no raquo
			if ( $cache ) {
				$ca = '0';
			}
		}
		else {
			$level++;
		}

		$slide = wppa_opt( 'album_navigator_widget_linktype' ) == 'slide' ? '&amp;wppa-slide=1' : '';

		$w = $this->get_widget_id();
		$p = $parent;
		$result = '';

		$albums = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
													   WHERE a_parent = %s " .
													   $extraclause .
													   wppa_get_album_order( max( '0', $parent ) ), $parent ), ARRAY_A );
		$albums = wppa_strip_void_albums( $albums );

		if ( ! empty( $albums ) ) {
			wppa_cache_album( 'add', $albums );
			$result .= '
			<ul class="albnav-ul albnav-ul-' . $level . '">';
			foreach ( $albums as $album ) {
				$a = $album['id'];
				$treecount = wppa_get_treecounts_a( $a );
				if ( $treecount['treealbums'] || $treecount['selfphotos'] || ! $skip ) {
					$has_children = wppa_has_children($a);
					$result .= '
						<li
							class="anw-'.$w.'-'.$p.$propclass.' albnav albnav-li albnav-li-' . $level . '"
							style="list-style:none;' . ( $level == '1' ? '' : 'display:none;' ) . '"
							>';
						if ( $has_children ) {
							$result .= '
							<span
								class="anw-'.$w.'-'.$a.'- albnav albnav-span albnav-span-' . $level . ' albnav-x"
								style="padding:0;margin:0 2px 0 -4px;cursor:default;font-weight:bold;"
								onclick="
									jQuery(\'.anw-'.$w.'-'.$a.'\').css(\'display\',\'\');
									jQuery(\'.anw-'.$w.'-'.$a.'-\').css(\'display\',\'none\');
								">' .
								( $a == $ca ? '&raquo;' : '+') .
							'</span>
							<span
								class="anw-'.$w.'-'.$a.' albnav albnav-span albnav-span-' . $level . ' albnav-link"
								style="padding:0;margin:0 2px 0 -4px;cursor:default;font-weight:bold;display:none;"
								onclick="
									jQuery(\'.anw-'.$w.'-'.$a.'-\').css(\'display\',\'\');
									jQuery(\'.anw-'.$w.'-'.$a.'\').css(\'display\',\'none\');
									jQuery(\'.p-'.$w.'-'.$a.'\').css(\'display\',\'none\');
								">' .
								( $a == $ca ? '&raquo;' : '-') .
							'</span>';
						}
						else {
							$result .= '
							<span style="padding:0;margin:0 2px 0 -4px;cursor:default;font-weight:bold">' .
								( $a == $ca ? '&raquo;' : '&nbsp;' ) .
							'</span>';
						}

						// Find the link
						$coverphoto = wppa_get_coverphoto_id( $a );
						$link = $coverphoto ? wppa_get_imglnk_a( 'albnavwidget', $coverphoto, '', '', '', false, $a ) : false;

						if ( ! $link ) {
							$link['url'] = '';
							$link['title'] = '';
							$link['is_url'] = false;
							$link['is_lightbox'] = false;
							$link['onclick'] = '';
							$link['target'] = '';
						}

						// Link is lightbox
						if ( $link['is_lightbox'] ) {

							$count = wppa_get_visible_photo_count( $a, true );
							if ( $count <= 1000 ) {
								$thumbs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos WHERE album = %s " . wppa_get_photo_order( $album['id'] ), $album['id'] ), ARRAY_A );
								wppa_cache_photo( 'invalidate' );
								wppa_cache_photo( 'add', $thumbs );
							}
							else {
								$thumbs = false;
							}


							if ( $thumbs ) {
								foreach ( $thumbs as $thumb ) {
									$title = wppa_get_lbtitle('alw', $thumb['id']);
									if ( wppa_is_video( $thumb['id']  ) ) {
										$siz['0'] = wppa_get_videox( $thumb['id'] );
										$siz['1'] = wppa_get_videoy( $thumb['id'] );
									}
									else {
										$siz['0'] = wppa_get_photox( $thumb['id'] );
										$siz['1'] = wppa_get_photoy( $thumb['id'] );
									}
									$url 		= wppa_get_photo_url( $thumb['id'], true, '', $siz['0'], $siz['1'] );
									$is_video 	= wppa_is_video( $thumb['id'] );
									$has_audio 	= wppa_has_audio( $thumb['id'] );
									$is_pdf 	= wppa_is_pdf( $thumb['id'] );

									$result .= '
									<a href="' . esc_url( $url ) . '"
										data-id="' . wppa_encrypt_photo( $thumb['id'] ) . '"' .
										( $is_video ? '
											data-videohtml="' . esc_attr( wppa_get_video_body( $thumb['id'] ) ) . '"
											data-videonatwidth="' . esc_attr( wppa_get_videox( $thumb['id'] ) ) . '"
											data-videonatheight="' . esc_attr( wppa_get_videoy( $thumb['id'] ) ) . '" ' :
											' '
										) .
										( $has_audio ? 'data-audiohtml="' . esc_attr( wppa_get_audio_body( $thumb['id'] ) ) . '" ' : ' ' ) .
										( $is_pdf ? 'data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $thumb['id'] ) ) .'" ' : ' ' ) .
										'data-rel="wppa[alw-' . wppa( 'mocc' ) . '-' . $album['id'] . ']" ' .
										'data-lbtitle' . '="' . esc_attr( $title ) . '" ' .
										wppa_get_lb_panorama_full_html( $thumb['id'] ) . '
										data-alt="' . esc_attr( wppa_get_imgalt( $thumb['id'], true ) ) . '"
										style="cursor:' . wppa_wait() . ';"
										onclick="return false;" ' .
										( $thumb['id'] == $thumbs[0]['id'] ? 'title="' . sprintf( __( '%d items', 'wp-photo-album-plus' ), $count ) . '" ' : ' ' ) . '
										>' .
										( $thumb['id'] == $thumbs[0]['id'] ? wppa_get_album_name( $a ) : '' ) . '
									</a>';
								}
							}
							else {
								$result .= '
								<a
									class="albnav albnav-albumlink" ' .
									( $thumbs == false ? 'title="' . __( 'Too many photos to display directly', 'wp-photo-album-plus' ) . ' (' . $count . ')" ' : ' ' ) .
									( $thumbs == false ? 'href="' . wppa_encrypt_url( get_permalink( $page ) . '?wppa-album=' . $a . '&wppa-cover=0&wppa-photos-only=1&wppa-occur=1' ) . '" ' : ' ' ) . '
									>' .
									wppa_get_album_name( $a ) .
								'</a>';
							}
						}

						// Link is not lighjtbox
						else {
							$result .= '
								<a
									class="albnav albnav-albumlink" ' .
									( $link['is_url'] ? 'href="' . $link['url'] . '" ' : ' ' ) .
									( $link['target'] ? 'target="' . $link['target'] . '" ' : ' ' ) .
									( $link['onclick'] ? 'onclick="' . $link['onclick'] . '" ' : ' ' ) .
									'
									>' .
									wppa_get_album_name( $a ) .
								'</a>';
							}
						$result .= '
						</li>';
					$newpropclass = $propclass . ' p-'.$w.'-'.$p;

					$next_level = $this->do_album_navigator( $a, $page, $skip, $newpropclass, $extraclause, $cache );
					if ( $next_level ) {
						$result .= '
						<li
							class="anw-'.$w.'-'.$a.$propclass.' albnav albnax-next"
							id="anw-'.$w.'-'.$a.'"
							style="list-style:none;display:none;background-image:none;"
							>' .
							$next_level . '
						</li>';
					}
				}
			}
			$result .= '</ul>';
		}
		$level--;
		return $result;
	}

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Album Navigator', 'wp-photo-album-plus' ),
							'parent' 	=> '0',			// Parent album
							'skip' 		=> 'no',		// Skip empty albums
							'logonly' 	=> 'no',
							'cache' 	=> '0',
							);
		return $defaults;
	}

} // class AlbumNavigatorWidget
// register AlbumNavigatorWidget widget
add_action('widgets_init', 'wppa_register_AlbumNavigatorWidget' );

function wppa_register_AlbumNavigatorWidget() {
	register_widget("AlbumNavigatorWidget");
}
