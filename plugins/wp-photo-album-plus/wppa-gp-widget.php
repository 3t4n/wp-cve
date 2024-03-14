<?php
/* wppa-gp-widget.php
* Package: wp-photo-album-plus
*
* A text widget that interpretes wppa shortcodes
*
* Version 8.4.03.002
*/

class WppaGpWidget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'wppa_gp_widget', 'description' => __( 'General purpose widget that may contain [wppa] shortcodes', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_gp_widget', __( 'WPPA+ Text', 'wp-photo-album-plus' ), $widget_ops );
	}

	function widget( $args, $instance ) {

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'gp' );
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
			wppa_echo( wppa_widget_timer( 'show', $widget_title, true ) );
			wppa( 'in_widget', false );
			return;
		}

		// Other inits

		// Body
		$text = $instance['text'];
		$text = __( $text );
		if ( wppa_checked( $instance['filter'] ) ) {	// Do wpautop BEFORE do_shortcode
			$text = wpautop( $text );
		}
		$text = do_shortcode( $text );
		$text = apply_filters( 'widget_text', $text );	// If shortcode at wppa filter priority, insert result. See wppa-filter.php

		$widget_content = '
		<div' .
			' class="wppa-gp-widget"' .
			' style="margin-top:2px; margin-left:2px;"' .
			' data-wppa="yes"' .
			' >' .
			$text .
		'</div>' .
		'<div style="clear:both"></div>';

		// Output
		$result = "\n" . $before_widget;
		if ( ! empty( $widget_title ) ) {
			$result .= $before_title . $widget_title . $after_title;
		}
		$result .= $widget_content . $after_widget;

		echo( $result );
		wppa_echo( wppa_widget_timer( 'show', $widget_title ) );

		// Cache?
		if ( $cache ) {
			wppa_save_cache_file( ['file' => $cachefile, 'data' => $result] );
		}

		wppa( 'in_widget', false );
		wppa( 'fullsize', '' );	// Reset to prevent inheritage of wrong size in case widget is rendered before main column

	}

	function update( $new_instance, $old_instance ) {

		// Completize all parms
		$instance = wppa_parse_args( $new_instance, $this->get_defaults() );

		// Sanitize certain args
		$instance['title'] 			= strip_tags( $instance['title'] );
		if ( current_user_can('unfiltered_html') ) {
			$instance['text'] 		=  $new_instance['text'];
		}
		else {
			$instance['text'] 		= stripslashes( wp_filter_post_kses( addslashes( $new_instance['text'] ) ) ); // wp_filter_post_kses() expects slashed
		}

		wppa_remove_widget_cache( $this->id );

        return $instance;

	}

	function form( $instance ) {

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Widget title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Text area
		wppa_widget_textarea( $this, 'text', $instance['text'], __( 'Enter the content just like a normal text widget. This widget will interpret [wppa] shortcodes', 'wp-photo-album-plus' ) );

		// Run wpautop?
		wppa_widget_checkbox( $this, 'filter', $instance['filter'], __( 'Automatically add paragraphs', 'wp-photo-album-plus' ) );

		// Logged in only?
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );

		// Cache
		wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) );

	}

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Text', 'wp-photo-album-plus' ),
							'text' 		=> '',
							'filter' 	=> 'no',
							'logonly' 	=> 'no',
							'cache' 	=> '0',
							);
		return $defaults;
	}

}
// register WppaGpWidget widget
add_action( 'widgets_init', 'wppa_register_WppaGpWidget' );

function wppa_register_WppaGpWidget() {
	register_widget( "WppaGpWidget" );
}