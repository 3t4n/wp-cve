<?php
/* wppa-super-view-widget.php
* Package: wp-photo-album-plus
*
* ask the album / display you want
* Version: 8.4.03.002
*/


class WppaSuperView extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_super_view', 'description' => __( 'Display a super selection dialog', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_super_view', __( 'WPPA+ Super View', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
		wppa( 'in_widget', 'superview' );
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
		$album_root 	= $instance['root'];
		$sort 			= wppa_checked( $instance['sort'] ) ? true : false;

		// Make the widget content
		$widget_content = '<span data-wppa="yes"></span>' . wppa_get_superview_html( $album_root, $sort );

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
			wppa_save_cache_file( ['file' => $cachefile, 'data' => $result, 'albums' => '*'] );
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

		// Title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Root
		$body = wppa_album_select_a( array( 'selected' => $instance['root'], 'addall' => true, 'addseparate' => true, 'addgeneric' => true, 'path' => true ) );
		wppa_widget_selection_frame( $this, 'root', $body, __( 'Enable (sub)albums of', 'wp-photo-album-plus' ) );

		// Sort
		wppa_widget_checkbox( 	$this,
								'sort',
								$instance['sort'],
								__( 'Sort alphabetically', 'wp-photo-album-plus' ),
								__( 'If unticked, the album sort method for the album or system will be used', 'wp-photo-album-plus' )
								);

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );

		// Cache
		wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) );

	}

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Super View' , 'wp-photo-album-plus' ),
							'root' 		=> '0',
							'sort'		=> true,
							'logonly' 	=> 'no',
							'cache' 	=> '0',
							);
		return $defaults;
	}


} // class WppaSuperView

// register WppaSuperView widget
add_action('widgets_init', 'wppa_register_wppaSuperView' );

function wppa_register_wppaSuperView() {
	register_widget("WppaSuperView");
}
