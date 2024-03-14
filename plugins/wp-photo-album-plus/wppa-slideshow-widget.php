<?php
/* wppa-slideshow-widget.php
* Package: wp-photo-album-plus
*
* display a slideshow in the sidebar
* Version: 8.4.06.008
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

/**
 * SlideshowWidget Class
 */
class SlideshowWidget extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'slideshow_widget', 'description' => __( 'Display a slideshow', 'wp-photo-album-plus' ) );
		parent::__construct( 'slideshow_widget', __( 'WPPA+ Sidebar Slideshow', 'wp-photo-album-plus' ), $widget_ops);
	}

	/** @see WP_Widget::widget */
    function widget( $args, $instance ) {

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'slide' );
		wppa_bump_mocc( $this->id );
        extract( $args );
		$instance 		= wppa_parse_args( (array) $instance, $this->get_defaults() );
		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );
		$album 			= $instance['album'];
		$page 			= in_array( wppa_opt( 'slideonly_widget_linktype' ), wppa( 'links_no_page' ) ) ? '' :
						  wppa_get_the_landing_page( 'slideonly_widget_linkpage', __( 'Widget landing page', 'wp-photo-album-plus' ) );
		$cache 			= wppa_cache_widget( $instance['cache'] );
		$cachefile 		= wppa_get_widget_cache_path( $this->id );
		$widget_content = '';

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

		// Calculate the height if set to 0
		if ( ! $instance['height'] ) {
			$instance['height'] = round( wppa_opt( 'widget_width' ) * wppa_opt( 'maxheight' ) / wppa_opt( 'fullsize' ) );
		}

		// Do the widget if we know the album
		if ( is_numeric( $album ) && ( wppa_album_exists( $album ) || $album == '-2' ) ) {

			// Show text above slideshow
			if ( __( $instance['supertext'] ) ) {
				$widget_content .=
				'<div style="padding-top:2px; padding-bottom:4px; text-align:center" >' .
					__( $instance['supertext'] ) .
				'</div>';
			}

			// Fill in runtime parameters to tune the slideshow
			if ( $instance['linkurl'] && wppa_opt( 'slideonly_widget_linktype' ) == 'widget' ) {
				wppa( 'in_widget_linkurl', $instance['linkurl'] );
				wppa( 'in_widget_linktitle', __( $instance['linktitle'] ) );
			}
			wppa( 'auto_colwidth', false );
			wppa( 'in_widget', 'ss' );
			wppa( 'in_widget_frame_height', $instance['height'] );
			wppa( 'in_widget_frame_width', $instance['width'] );
			wppa( 'timeout', $instance['timeout'] * 1000 );
			wppa( 'portrait_only', wppa_checked( $instance['ponly'] ) );
			wppa( 'ss_widget_valign', $instance['valign'] );
			wppa( 'film_on', wppa_checked( $instance['film'] ) );
			wppa( 'browse_on', wppa_checked( $instance['browse'] ) );
			wppa( 'name_on', wppa_checked( $instance['name'] ) );
			wppa( 'numbar_on', wppa_checked( $instance['numbar'] ) );
			wppa( 'desc_on', wppa_checked( $instance['desc'] ) );
			wppa( 'max_slides_in_ss_widget', $instance['maxslides'] );
			wppa( 'is_random', wppa_checked( $instance['random'] ) );

			// Including sub albums?
			if ( $album > '0' && wppa_checked( $instance['incsubs'] ) ) {
				$album = wppa_alb_to_enum_children( $album );
			}
			elseif ( $album == '-2' ) {
				$album = wppa_alb_to_enum_children( '0' );
			}
			$album = implode( '.', wppa_strip_void_albums( explode( '.', $album ) ) );

			if ( $album ) {

				// Open the slideshow container
				$widget_content .=
				'<div' .
					' style="padding-top:2px; padding-bottom:4px;" ' .
					' data-wppa="yes">';

					// The very slideshow
					$widget_content .= wppa_albums( $album, 'slideonly', $instance['width'], 'center' );

				// Close slideshw container
				$widget_content .=
				'</div>';
			}
			else {

				// Nothing left after stripping. quit
				wppa( 'in_widget', false );
				return;
			}

			// Show text below the slideshow
			if ( __( $instance['subtext'] ) ) {
				$widget_content .=
				'<div style="padding-top:2px; padding-bottom:0px; text-align:center" >' .
					__( $instance['subtext'] ) .
				'</div>';
			}
		}

		// No album specified
		else {
			$widget_content .= __( 'Unknown album or album does not exist', 'wp-photo-album-plus' );
		}

		// Output
		$result = "\n" . $before_widget;
		if ( ! empty( $widget_title ) ) {
			$result .= $before_title . $widget_title . $after_title;
		}
		$result .= $widget_content . $after_widget;

		echo wppa_compress_html( $result );
		wppa_echo( wppa_widget_timer( 'show', $widget_title ) );

		// Cache?
		if ( $cache ) {
			wppa('in_widget', 'slide');
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
		$instance['linktitle'] 	= strip_tags( $instance['linktitle'] );
		$instance['supertext'] 	= force_balance_tags( $instance['supertext'] );
		$instance['subtext'] 	= force_balance_tags( $instance['subtext'] );
		$instance['linkurl'] 	= esc_url( $instance['linkurl'] );

		wppa_remove_widget_cache( $this->id );

        return $instance;
    }

    /** @see WP_Widget::form */
    function form( $instance ) {

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Album
		if ( wppa_opt( 'photo_admin_max_albums' ) >= wppa_get_count( WPPA_ALBUMS ) ) {
			$body =
			'<option value="-2"' . ( $instance['album'] == '-2' ? ' selected' : '' ) . ' >' . __( '--- all ---', 'wp-photo-album-plus' ) . '</option>' .
			wppa_album_select_a( array (
											'selected' 	=> $instance['album'],
											'path' 		=> true,
											'sort' 		=> true,
											) );

			wppa_widget_selection_frame( $this, 'album', $body, __( 'Album', 'wp-photo-album-plus' ) );
		}
		else {
			wppa_widget_input( $this, 'album', $instance['album'], __( 'Album', 'wp-photo-album-plus' ) );
		}

		// Including sub albums?
		wppa_widget_checkbox( $this, 'incsubs', $instance['incsubs'], __( 'Include sub albums', 'wp-photo-album-plus' ) );

		// Max
		$body =
		'<option value="10" ' . ( $instance['maxslides'] == '10' ? 'selected' : '' ) . ' >10</option>' .
		'<option value="25" ' . ( $instance['maxslides'] == '25' ? 'selected' : '' ) . ' >25</option>' .
		'<option value="50" ' . ( $instance['maxslides'] == '50' ? 'selected' : '' ) . ' >50</option>' .
		'<option value="75" ' . ( $instance['maxslides'] == '75' ? 'selected' : '' ) . ' >75</option>' .
		'<option value="100" ' . ( $instance['maxslides'] == '100' ? 'selected' : '' ) . ' >100</option>' .
		'<option value="150" ' . ( $instance['maxslides'] == '150' ? 'selected' : '' ) . ' >150</option>' .
		'<option value="200" ' . ( $instance['maxslides'] == '200' ? 'selected' : '' ) . ' >200</option>' .
		'<option value="250" ' . ( $instance['maxslides'] == '250' ? 'selected' : '' ) . ' >250</option>' .
		'<option value="350" ' . ( $instance['maxslides'] == '350' ? 'selected' : '' ) . ' >350</option>' .
		'<option value="500" ' . ( $instance['maxslides'] == '500' ? 'selected' : '' ) . ' >500</option>';
		wppa_widget_selection_frame( $this, 'maxslides', $body, __( 'Max slides', 'wp-photo-album-plus' ), false, __( 'High numbers may cause slow pageloads!', 'wp-photo-album-plus' ) );

		// Random
		wppa_widget_checkbox( $this, 'random', $instance['random'], __( 'Random sequence', 'wp-photo-album-plus' ) );

		// Sizes and alignment
		wppa_echo(
		__( 'Sizes and alignment', 'wp-photo-album-plus' ) . ':' .
		'<div style="padding:6px;border:1px solid lightgray;margin-top:2px">' .
			__( 'Enter the width and optionally the height of the area wherein the slides will appear. If you specify a 0 for the height, it will be calculated. The value for the height will be ignored if you set the vertical alignment to \'fit\'.', 'wp-photo-album-plus' ) .
			' ' .
			__( 'Tick the portrait only checkbox if there are only portrait images in the album and you want the photos to fill the full width of the widget.', 'wp-photo-album-plus' ) .
			' ' .
			__ ( 'If portrait only is checked, the vertical alignment will be forced to \'fit\'.', 'wp-photo-album-plus' ) );

			// Width
			wppa_widget_number( $this, 'width', $instance['width'], __( 'Width in pixels', 'wp-photo-album-plus' ), '50', '500', '', 'float' );

			// Height
			wppa_widget_number( $this, 'height', $instance['height'], __( 'Height in pixels', 'wp-photo-album-plus' ), '0', '500', '', 'float' );

			// Portrait only
			wppa_widget_checkbox( 	$this,
									'ponly',
									$instance['ponly'],
									__( 'Portrait only', 'wp-photo-album-plus' )
									);

			// Vertical alignment
			$options = array(	__( 'top', 'wp-photo-album-plus' ),
								__( 'center', 'wp-photo-album-plus' ),
								__( 'bottom', 'wp-photo-album-plus' ),
								__( 'fit', 'wp-photo-album-plus' ),
								);
			$values  = array(	'top',
								'center',
								'bottom',
								'fit',
								);

			wppa_widget_selection( 	$this,
									'valign',
									$instance['valign'],
									__( 'Vertical alignment', 'wp-photo-album-plus' ),
									$options,
									$values,
									array(),
									'',
									__( 'Set the desired vertical alignment method.', 'wp-photo-album-plus' )
									);


		wppa_echo( '</div>' );

		// Timeout
		wppa_widget_number( $this, 'timeout', $instance['timeout'], __( 'Slideshow timeout in seconds', 'wp-photo-album-plus' ), '1', '60' );

		// Linkurl
		if ( wppa_opt( 'slideonly_widget_linktype' ) == 'widget' ) {
			wppa_widget_input( 	$this,
								'linkurl',
								$instance['linkurl'],
								__( 'Link to', 'wp-photo-album-plus' ),
								__( 'If you want that a click on the image links to another web address, type the full url here.', 'wp-photo-album-plus' )
								);
		}

		// Additional boxes
		wppa_echo(
		__( 'Slideshow options to display', 'wp-photo-album-plus' ) .
		'<div style="padding:6px;border:1px solid lightgray;margin-top:2px;margin-bottom:1em">' );

			// Name
			wppa_widget_checkbox( $this, 'name', $instance['name'], __( 'Show name', 'wp-photo-album-plus' ) );

			// Description
			wppa_widget_checkbox( $this, 'desc', $instance['desc'], __( 'Show description', 'wp-photo-album-plus' ) );

			// Filmstrip
			wppa_widget_checkbox( $this, 'film', $instance['film'], __( 'Show filmstrip', 'wp-photo-album-plus' ) );

			// Browsebar
			wppa_widget_checkbox( $this, 'browse', $instance['browse'], __( 'Show browsebar', 'wp-photo-album-plus' ) );

			// Numbar
			wppa_widget_checkbox( $this, 'numbar', $instance['numbar'], __( 'Show number bar', 'wp-photo-album-plus' ) );

		wppa_echo( '</div>' );

		// qTranslate supported textfields
		wppa_echo(
		__( 'The following text fields support qTranslate', 'wp-photo-album-plus' ) .
		'<div style="padding:6px;border:1px solid lightgray;margin-top:2px">' );

			// Link title
			wppa_widget_input( $this, 'linktitle', $instance['linktitle'], __( 'Tooltip text', 'wp-photo-album-plus' ) );

			// Supertext
			wppa_widget_input( $this, 'supertext', $instance['supertext'], __( 'Text above photos', 'wp-photo-album-plus' ) );

			// Sutext
			wppa_widget_input( $this, 'subtext', $instance['subtext'], __( 'Text below photos', 'wp-photo-album-plus' ) );

		wppa_echo( '</div>' );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );

		// Cache
		wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) );

    }

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Sidebar Slideshow', 'wp-photo-album-plus' ),
							'album' 	=> '-2',
							'width' 	=> wppa_get_option( 'wppa_widget_width' ),
							'height' 	=> '0',
							'ponly' 	=> 'no',
							'linkurl' 	=> '',
							'linktitle' => '',
							'subtext' 	=> '',
							'supertext' => '',
							'valign' 	=> 'center',
							'timeout' 	=> '4',
							'film' 		=> 'no',
							'browse' 	=> 'no',
							'name' 		=> 'no',
							'numbar'	=> 'no',
							'desc' 		=> 'no',
							'maxslides' => '100',
							'random' 	=> 'no',
							'incsubs' 	=> 'no',
							'logonly' 	=> 'no',
							'cache' 	=> '',
							);
		return $defaults;
	}

} // class SlideshowWidget

// register SlideshowWidget widget
add_action('widgets_init', 'wppa_register_SlideshowWidget' );

function wppa_register_SlideshowWidget() {
	register_widget("SlideshowWidget");
}
