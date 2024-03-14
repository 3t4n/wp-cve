<?php
/* wppa-potd-widget.php
* Package: wp-photo-album-plus
*
* display the photo of the day widget
* Version 8.4.04.001
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

class PhotoOfTheDay extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_widget', 'description' => __( 'Display Photo Of The Day', 'wp-photo-album-plus' ) );	//
		parent::__construct( 'wppa_widget', __( 'WPPA+ Photo Of The Day', 'wp-photo-album-plus' ), $widget_ops );															//
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {
		global $wpdb;

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'potd' );
		wppa_bump_mocc( $this->id );
        extract( $args );
		$instance 		= wppa_parse_args( (array) $instance, $this->get_defaults() );
		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );

		// Logged in only and logged out?
		if ( wppa_checked( $instance['logonly'] ) && ! is_user_logged_in() ) {
			return;
		}

		// get the photo  ($image)
		$image = wppa_get_potd();

		// Make the HTML for current picture
		$widget_content = "\n".'<!-- WPPA+ Photo of the day Widget start -->';

		$widget_content .= '
		<div' .
			' class="wppa-widget-photo"' .
			' style="padding-top:2px;position:relative;"' .
			' data-wppa="yes"' .
			' >';

		if ( $image ) {

			$id 		= $image['id'];
			$ratio 		= ( wppa_get_photox( $id ) ? wppa_get_photoy( $id ) / wppa_get_photox( $id ) : 1 );
			$usethumb	= wppa_use_thumb_file( $id, '300', '0' );
			$imgurl 	= $usethumb ? wppa_get_thumb_url( $id, true ) : wppa_get_photo_url( $id, true );
			$name 		= wppa_get_photo_name( $id );
			$page 		= ( in_array( wppa_opt( 'potd_linktype' ), wppa( 'links_no_page' ) ) && ! wppa_switch( 'potd_counter' ) ) ? '' : wppa_get_the_landing_page( 'potd_linkpage', __('Photo of the day', 'wp-photo-album-plus' ) );
			$link 		= wppa_get_imglnk_a( 'potdwidget', $id );
			$is_video 	= wppa_is_video( $id );
			$has_audio 	= wppa_has_audio( $id );
			$alb 		= wppa_get_photo_item( $id, 'album' );
			$is_pdf 	= wppa_is_pdf( $id );

			if ( $link && $link['is_lightbox'] ) {
				$lightbox = ( $is_video ? ' data-videohtml="' . esc_attr( wppa_get_video_body( $id ) ) . '"' .
							' data-videonatwidth="'.wppa_get_videox( $id ).'"' .
							' data-videonatheight="'.wppa_get_videoy( $id ).'"' : '' ) .
							( $has_audio ? ' data-audiohtml="' . esc_attr( wppa_get_audio_body( $id ) ) . '"' : '' ) .
							( $is_pdf ? ' data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $id ) ) .'"' : '' ) .
							' data-rel="wppa"' .
							' data-alt="' . esc_attr( wppa_get_imgalt( $id, true ) ) . '"' .
							' data-id="' . wppa_encrypt_photo( $id ) . '"' .
							wppa_get_lb_panorama_full_html( $id ) .
							' onclick="return false;"' .
							' style="cursor:' . wppa_wait() . ';"';
			}
			else {
				$lightbox = '';
			}

			if ( $link ) {
				if ( $link['is_lightbox'] ) {
					$cursor = '';//' cursor:' . wppa_wait() . ';'; //url('.wppa_get_imgdir().wppa_opt( 'magnifier').'),pointer;';
					$title  = wppa_zoom_in( $id );
					$ltitle = wppa_get_lbtitle('potd', $id);
				}
				else {
					$cursor = ' cursor:pointer;';
					$title  = $link['title'];
					$ltitle = $title;
				}
			}
			else {
				$cursor = ' cursor:default;';
				$title = esc_attr(stripslashes(__($image['name'], 'wp-photo-album-plus' )));
			}

			// The medal if on top
			$widget_content .= wppa_get_medal_html_a( array( 'id' => $id, 'size' => 'M', 'where' => 'top' ) );

			// The link, if any
			if ( $link ) {
				$widget_content .= '
				<a href="' . $link['url'] . '"' .
					( $link['target'] ? ' target="' . $link['target'] . '"' : ' ' ) .
					$lightbox . '
					data-lbtitle="' . $ltitle . '">';
			}

				// The image
				if ( wppa_is_video( $id ) ) {
					$widget_content .= "\n\t\t".wppa_get_video_html( array ( 	'id' 		=> $id,
																				'title' 	=> $title,
																				'controls' 	=> ( wppa_opt( 'potd_linktype' ) == 'none' ),
																				'cursor' 	=> $cursor,
																				'widthp' 	=> '100'
																	));
				}
				else {
					$widget_content .= 	'<img' .
											' src="'.$imgurl.'"' .
											' style="width: 100%;'.$cursor.'"' .
											' ' . wppa_get_imgalt( $id ) .
											( $title ? 'title="' . $title . '"' : '' ) .
											' />';
				}

			// Close the link
			if ( $link ) $widget_content .= '</a>';

			// The medal if at the bottom
			$widget_content .= wppa_get_medal_html_a( array( 'id' => $id, 'size' => 'M', 'where' => 'bot' ) );

			// The counter
			if ( wppa_switch( 'potd_counter' ) ) { 	// If we want this

				$c = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE album = " . $alb ) - 1;
				if ( $c > 0 ) {
					if ( wppa_opt( 'potd_counter_link' ) == 'thumbs' ) {
						$lnk = wppa_get_album_url( array( 'album' => $alb,
														  'page' => $page,
														  'type' => 'thumbs',
														  'mocc' => '1' ) );
					}
					elseif ( wppa_opt( 'potd_counter_link' ) == 'slide' ) {
						$lnk = wppa_get_slideshow_url( array( 'album' => $alb,
															  'page' => $page,
															  'photo' => $id,
															  'mocc' => '1' ) );
					}
					elseif ( wppa_opt( 'potd_counter_link' ) == 'single' ) {
						$lnk = wppa_encrypt_url( get_permalink( $page ) . '?occur=1&photo=' . $id );
					//	wppa_get_image_page_url_by_id( $id, true, false, $page );
					}
					else {
						wppa_log( 'Err', 'Unimplemented counter link type in wppa-potd-widget: ' . wppa_opt( 'potd_counter_link' ) );
					}

					$widget_content .= 	'<a href="' . $lnk . '" >' .
											'<div style="font-size:12px;position:absolute;right:4px;bottom:4px">+' . $c . '</div>' .
										'</a>';
				}
			}

			// Audio
			if ( wppa_has_audio( $id ) ) {
				$widget_content .= wppa_get_audio_html( array ( 	'id' 		=> $id,
																	'controls' 	=> true
													));
			}

		}
		else {	// No image
			$widget_content .= __( 'Photo not found', 'wp-photo-album-plus' );
		}
		$widget_content .= "\n".'</div>';

		// Add subtitle, if any
		if ( $image ) {
			switch ( wppa_opt( 'potd_subtitle' ) ) {
				case 'none':
					break;
				case 'name':
					$widget_content .= '<div class="wppa-widget-text wppa-potd-text" >' . wppa_get_photo_name( $id ) . '</div>';
					break;
				case 'desc':
					$widget_content .= "\n".'<div class="wppa-widget-text wppa-potd-text" >' . wppa_get_photo_desc( $id ) . '</div>';
					break;
				case 'owner':
					$owner = $image['owner'];
					$user = wppa_get_user_by('login', $owner);
					$owner = $user->display_name;
					$widget_content .= "\n".'<div class="wppa-widget-text wppa-potd-text" >'.__('By:', 'wp-photo-album-plus' ).' ' . $owner . '</div>';
					break;
				case 'extended':
					$alb = wppa_get_photo_item( $id, 'album' );
					$widget_content .=
					'<div class="wppa-widget-text wppa-potd-text" >' .
						'<span class="potd-pname" >' . wppa_get_photo_name( $id ) . '</span>' .
						'<span class="pots-pdesc" >' . wppa_get_photo_desc( $id ) . '</span>' .
						'<br>' .
						'<span class="potd-adesc" >' . wppa_get_album_desc( $alb ) . '</span>' .
						'<span class="potd-aname" >' . wppa_get_album_name( $alb ) . '</span>' .
					'</div>';
					break;
				default:
					wppa_log( 'Err', 'Unimplemented potd_subtitle found in wppa-potd-widget: ' . wppa_opt( 'potd_subtitle' ) );
			}
		}

		$widget_content .= '<div style="clear:both"></div>';

		$widget_content .= "\n".'<!-- WPPA+ Photo of the day Widget end -->';

		// Output
		$result = "\n" . $before_widget;
		if ( ! empty( $widget_title ) ) {
			$result .= $before_title . $widget_title . $after_title;
		}
		$result .= $widget_content . $after_widget;

		if ( $image ) {
			wppa_echo( $result );
		}
		wppa_echo( wppa_widget_timer( 'show', $widget_title ) );

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

		// Explanation
		if ( current_user_can( 'wppa_settings' ) ) {
			$result =
			'<p>' .
				__( 'You can set the content and the sizes in this widget in the <b>Photo Albums -> Photo of the day</b> admin page.', 'wp-photo-album-plus' ) .
				wppa_see_also( 'photos', 3 ); //  .
			'</p>';
		}

		wppa_echo( $result );
    }

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Photo of the day', 'wp-photo-album-plus' ),
							'logonly' 	=> 'no',
							);
		return $defaults;
	}

} // class PhotoOfTheDay

// register PhotoOfTheDay widget
add_action( 'widgets_init', 'wppa_register_PhotoOfTheDay' );

function wppa_register_PhotoOfTheDay() {
	register_widget( 'PhotoOfTheDay' );
}
