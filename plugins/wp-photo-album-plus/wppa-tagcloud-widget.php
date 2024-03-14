<?php
/* wppa-tagcloud-widget.php
* Package: wp-photo-album-plus
*
* display the tagcloud widget
* Version 8.4.03.002
*/

class TagcloudPhotos extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_tagcloud_photos', 'description' => __( 'Display a cloud of photo tags', 'wp-photo-album-plus' ) );	//
		parent::__construct( 'wppa_tagcloud_photos', __( 'WPPA+ Photo Tag Cloud', 'wp-photo-album-plus' ), $widget_ops );															//
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {
		global $widget_content;

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'tagcloud' );
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
		if ( empty( $instance['tags'] ) ) $instance['tags'] = array();


		$widget_content = '
		<div' .
			' class="wppa-tagcloud-widget"' .
			' data-wppa="yes"' .
			' >' .
			wppa_get_tagcloud_html( implode( ',', $instance['tags'] ), wppa_opt( 'tagcloud_min' ), wppa_opt( 'tagcloud_max' ) ) .
		'</div>' .
		'<div style="clear:both"></div>';

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
			wppa_save_cache_file( ['file' => $cachefile, 'data' => $result, 'photos' => '*'] );
		}

		wppa( 'in_widget', false );
	}

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {

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

		$title = $instance['title'];
		$stags = $instance['tags'];
		if ( ! $stags ) $stags = array();

		// Title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Tags selection
		$tags = wppa_get_taglist();
		$body =
		'<option value="" >' . __( '--- all ---', 'wp-photo-album-plus' ) . '</option>';
		if ( $tags ) foreach ( array_keys( $tags ) as $tag ) {
			if ( in_array( $tag, $stags ) ) $sel = ' selected'; else $sel = '';
			$body .= '<option value="' . esc_attr( $tag ) . '"' . $sel . ' >' . htmlspecialchars( $tag ) . '</option>';
		}
		wppa_widget_selection_frame( $this, 'tags', $body, __( 'Select multiple tags or --- all ---', 'wp-photo-album-plus' ), 'multi' );

		// Show current selection
		if ( isset( $instance['tags']['0'] ) && $instance['tags']['0'] ) {
			$s = implode( ',', $instance['tags'] );
		}
		else {
			$s = __( '--- all ---', 'wp-photo-album-plus' );
		}
		wppa_echo( '<p style="word-break:break-all">' . esc_html__( 'Currently selected tags', 'wp-photo-album-plus' ) . ': <br><b>' . $s . '</b></p>' );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );

		// Cache
		wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) );
	}

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Photo Tag Cloud', 'wp-photo-album-plus' ),
							'tags' 		=> '',
							'logonly' 	=> 'no',
							'cache' 	=> '0',
							);
		return $defaults;
	}

} // class TagcloudPhotos

// register Photo Tags widget
add_action('widgets_init', 'wppa_register_TagcloudPhotos' );

function wppa_register_TagcloudPhotos() {
	register_widget("TagcloudPhotos");
}