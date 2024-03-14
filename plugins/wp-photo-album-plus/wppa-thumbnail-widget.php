<?php
/* wppa-thumbnail-widget.php
* Package: wp-photo-album-plus
*
* display thumbnail photos
* Version 8.4.03.002
*/

class ThumbnailWidget extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_thumbnail_widget', 'description' => __( 'Display thumbnails of the photos in an album', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_thumbnail_widget', __( 'WPPA+ Thumbnail Photos', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {
		global $wpdb;
		global $photos_used;

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'tn' );
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
			echo wppa_get_contents( $cachefile );
			wppa_update_option( 'wppa_cache_hits', wppa_get_option( 'wppa_cache_hits', 0 ) +1 );
			echo wppa_widget_timer( 'show', $widget_title, true );
			wppa( 'in_widget', false );
			return;
		}

		// Other inits
		$widget_link	= $instance['link'];
		$page 			= in_array( wppa_opt( 'thumbnail_widget_linktype' ), wppa( 'links_no_page' ) ) ? '' : wppa_get_the_landing_page('thumbnail_widget_linkpage', __('Thumbnail photos', 'wp-photo-album-plus' ));
		$max  			= $instance['limit'];
		$sortby 		= $instance['sortby'];
		$album 			= $instance['album'];
		$name 			= wppa_checked( $instance['name'] ) ? 'yes' : 'no';
		$display 		= $instance['display'];
		$linktitle 		= $instance['linktitle'];

		// Make the widget content
		$generic = ( $album == '-2' );
		if ( $generic ) {
			$album = '0';
			$max += '1000';
		}
		$separate = ( $album == '-1' );
		if ( $separate ) {
			$album = '0';
			$max += '1000';
		}

		if ( $album ) {
			$thumbs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   WHERE status <> 'pending'
														   AND status <> 'scheduled'
														   AND album = %s " .
														   $sortby . "
														   LIMIT %d", $album, $max ), ARRAY_A );
		}
		else {
			$thumbs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   WHERE status <> 'pending'
														   AND status <> 'scheduled' " .
														   $sortby . "
														   LIMIT %d", $max ), ARRAY_A );
		}

		$widget_content = "\n".'<!-- WPPA+ thumbnail Widget start -->';
		$maxw = wppa_opt( 'thumbnail_widget_size' );
		$maxh = $maxw;
		$lineheight = wppa_opt( 'fontsize_widget_thumb' ) * 1.5;
		$maxh += $lineheight;
		if ( $name == 'yes' ) $maxh += $lineheight;

		$count = '0';
		if ( $thumbs ) foreach ( $thumbs as $image ) {

			$thumb = $image;
			$photos_used .= '.' . $thumb['id'];

			if ( $generic && wppa_is_separate( $thumb['album'] ) ) continue;
			if ( $separate && ! wppa_is_separate( $thumb['album'] ) ) continue;

			// Make the HTML for current picture
			if ( $display == 'thumbs' ) {
				$widget_content .= '
					<div' .
						' class="wppa-widget"' .
						' style="width:'.$maxw.'px; height:'.$maxh.'px; margin:4px; display:inline; text-align:center; float:left"' .
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
			if ($image) {
				$link       = wppa_get_imglnk_a('tnwidget', $image['id']);
				$file       = wppa_get_thumb_path($image['id']);
				$imgstyle_a = wppa_get_imgstyle_a( $image['id'], $file, $maxw, 'center', 'twthumb');
				$imgurl 	= wppa_get_thumb_url( $image['id'], true, '', $imgstyle_a['width'], $imgstyle_a['height'] );
				$imgevents 	= wppa_get_imgevents('thumb', $image['id'], true);
				$title 		= $link ? esc_attr(stripslashes($link['title'])) : '';

				$widget_content .= wppa_get_the_widget_thumb('thumbnail', $image, $album, $display, $link, $title, $imgurl, $imgstyle_a, $imgevents);

				$widget_content .= "\n\t".'<div style="font-size:'.wppa_opt( 'fontsize_widget_thumb' ).'px; line-height:'.$lineheight.'px;">';
				if ( $name == 'yes' && $display == 'thumbs' ) {
					$widget_content .= "\n\t".'<div>'.__(stripslashes($image['name']), 'wp-photo-album-plus' ).'</div>';
				}
				$widget_content .= "\n\t".'</div>';
			}
			else {	// No image
				$widget_content .= __( 'Photo not found', 'wp-photo-album-plus' );
			}
			$widget_content .= "\n".'</div>';
			$count++;
			if ( $count == $instance['limit'] ) break;

		}
		else $widget_content .= __( 'There are no photos (yet)', 'wp-photo-album-plus' );

		$widget_content .= '<div style="clear:both"></div>';

		// Title link
		if ( ! empty( $widget_link ) ) {
			$widget_title = '
			<a href="' . esc_url( $widget_link ) . '" title="' . esc_attr( $linktitle ) . '" >' . $widget_title . '</a>';
		}

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
			wppa_save_cache_file( ['file' => $cachefile, 'data' => $result] );
		}

		wppa( 'in_widget', false );
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {

		// Completize all parms
		$instance = wppa_parse_args( $new_instance, $this->get_defaults() );

		// Sanitize certain args
		$instance['title'] 		= strip_tags( $instance['title'] );
		$instance['link'] 		= strip_tags( $new_instance['link'] );

		wppa_remove_widget_cache( $this->id );

        return $instance;
    }

    /** @see WP_Widget::form */
    function form( $instance ) {

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Link from the widget title
		wppa_widget_input( $this, 'link', $instance['link'], __( 'Link from the title', 'wp-photo-album-plus' ) );

		// Tooltip on the link from the title
		wppa_widget_input( $this, 'linktitle', $instance['linktitle'], __( 'Link Title ( tooltip )', 'wp-photo-album-plus' ) );

		// Album
		$body = wppa_album_select_a( array( 'selected' => $instance['album'], 'addseparate' => true, 'addall' => true, 'path' => true ) );
		wppa_widget_selection_frame( $this, 'album', $body, __( 'Album', 'wp-photo-album-plus' ) );

		// Sort by
		$options = array( 	__( '--- none ---', 'wp-photo-album-plus' ),
							__( 'Order #', 'wp-photo-album-plus' ),
							__( 'Name', 'wp-photo-album-plus' ),
							__( 'Random', 'wp-photo-album-plus' ),
							__( 'Rating mean value desc', 'wp-photo-album-plus' ),
							__( 'Number of votes desc', 'wp-photo-album-plus' ),
							__( 'Timestamp desc', 'wp-photo-album-plus' ),
							);
		$values  = array(	'',
							'ORDER BY p_order',
							'ORDER BY name',
							'ORDER BY RAND()',
							'ORDER BY mean_rating DESC',
							'ORDER BY rating_count DESC',
							'ORDER BY timestamp DESC',
							);

		wppa_widget_selection( $this, 'sortby', $instance['sortby'], __( 'Sort by', 'wp-photo-album-plus' ), $options, $values );

		// Max number
		wppa_widget_number( $this, 'limit', $instance['limit'], __( 'Max number', 'wp-photo-album-plus' ), '1', '100' );

		// Display type
		$options = array( 	__( 'thumbnail images', 'wp-photo-album-plus' ),
							__( 'photo names', 'wp-photo-album-plus' ),
							);
		$values  = array( 	'thumbs',
							'names',
							);

		wppa_widget_selection( $this, 'display', $instance['display'], __( 'Display', 'wp-photo-album-plus' ), $options, $values );

		// Names under thumbs
		wppa_widget_checkbox( $this, 'name', $instance['name'], __( 'Show photo names under thumbnails', 'wp-photo-album-plus' ) );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );

		// Cache
		wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) );

		$result = '
		<p>' .
			__( 'You can set the sizes in this widget in the <b>Photo Albums -> Settings</b> admin page.', 'wp-photo-album-plus' ) .
			' ' . wppa_setting_path( 'b', 'widget', '1', ['6', '7'] ) .
		'</p>';
		wppa_echo( strip_tags( wp_check_invalid_utf8( $result), ["<br>", "<a>", "<i>", "<b>"] ) );

    }

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title'		=> __( 'Thumbnail Photos', 'wp-photo-album-plus' ),
							'link'	 	=> '',
							'linktitle' => '',
							'album' 	=> '0',
							'name' 		=> 'no',
							'display' 	=> 'thumbs',
							'sortby' 	=> wppa_get_photo_order('0'),
							'limit' 	=> wppa_opt( 'thumbnail_widget_count' ),
							'logonly' 	=> 'no',
							'cache' 	=> '0',
							);
		return $defaults;
	}

} // class thumbnailWidget

// register thumbnailWidget widget
add_action('widgets_init', 'wppa_register_ThumbnailWidget' );

function wppa_register_ThumbnailWidget() {
	register_widget("ThumbnailWidget");
}
