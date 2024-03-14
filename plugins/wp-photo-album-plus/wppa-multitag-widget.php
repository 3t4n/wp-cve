<?php
/* wppa-multitag-widget.php
* Package: wp-photo-album-plus
*
* display the multitag widget
* Version 8.4.03.002
*
*/

class MultitagPhotos extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_multitag_photos', 'description' => __( 'Display checkboxes to select photos by one or more tags', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_multitag_photos', __( 'WPPA+ Photo Tags Filter', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {
		global $widget_content;

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'multitag' );
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
		$tags = is_array( $instance['tags'] ) ? implode( ',', $instance['tags'] ) : '';

		// Make the widget content
		$widget_content = '
		<div class="wppa-multitag-widget" data-wppa="yes">' .
			wppa_get_multitag_html( $instance['cols'], $tags ) .
		'</div>' .
		'<div style="clear:both" ></div>';

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
			wppa_update_option( 'wppa_cache_misses', wppa_get_option( 'wppa_cache_misses', 0 ) +1 );
		}

		wppa( 'in_widget', false );

	}

    /** @see WP_Widget::update */
    function update( $new_instance, $old_instance ) {

		// Completize all parms
		$instance = wppa_parse_args( $new_instance, $this->get_defaults() );

		// Sanitize certain args
		$instance['title'] 	= strip_tags( $instance['title'] );
		$instance['cols'] 	= min( max( '1', $instance['cols'] ), '6' );

		wppa_remove_widget_cache( $this->id );

        return $instance;
    }

    /** @see WP_Widget::form */
    function form( $instance ) {

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		$title 		= $instance['title'];
		$cols 		= $instance['cols'];
		$stags 		= (array) $instance['tags'];
		if ( empty( $stags ) ) $stags = array();

		// Title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Columns
		wppa_widget_number( $this, 'cols', $instance['cols'], __( 'Number of columns', 'wp-photo-album-plus' ), '1', '6', '', 'true' );

		// Tags selection
		$tags = wppa_get_taglist();
		$body = '<option value="" >' . __( '--- all ---', 'wp-photo-album-plus' ) . '</option>';
		if ( $tags ) foreach ( array_keys( $tags ) as $tag ) {
			if ( in_array( $tag, $stags ) ) $sel = ' selected'; else $sel = '';
			$body .= '<option value="' . esc_attr( $tag ) . '"' . $sel . ' >' . htmlspecialchars( $tag ) . '</option>';
		}
		wppa_widget_selection_frame( $this, 'tags', $body, '<div style="clear:both"></div>' . __( 'Select multiple tags or --- all ---', 'wp-photo-album-plus' ), 'multi' );

		// Currently selected
		if ( isset( $instance['tags']['0'] ) && $instance['tags']['0'] ) $s = implode( ',', $instance['tags'] ); else $s = __( '--- all ---', 'wp-photo-album-plus' );
		$result = '
		<p style="word-break:break-all">' .
			__( 'Currently selected tags', 'wp-photo-album-plus' ) . ':
			<br>
			<b>' .
				$s . '
			</b>
		</p>';
		wppa_echo( strip_tags( wp_check_invalid_utf8( $result), ["<br>", "<a>", "<i>", "<b>"] ) );

		// Loggedin only
		wppa_echo( wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) ) );

		// Cache
		wppa_echo( wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) ) );

    }

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Photo Tags Filter', 'wp-photo-album-plus' ),
							'cols' 		=> '2',
							'tags' 		=> '',
							'logonly' 	=> 'no',
							'cache' 	=> '0',
							);
		return $defaults;
	}

} // class MultitagPhotos

// register Photo Tags widget
add_action('widgets_init', 'wppa_register_MultitagPhotos' );

function wppa_register_MultitagPhotos() {
	register_widget("MultitagPhotos");
}
