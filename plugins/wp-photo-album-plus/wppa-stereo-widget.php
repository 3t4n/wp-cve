<?php
/* wppa-stereo-widget.php
* Package: wp-photo-album-plus
*
* display the top rated photos
* Version: 8.4.03.002
*/

class wppaStereoWidget extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_stereo_widget', 'description' => __( 'Display stereo photo settings dialog', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_stereo_widget', __( 'WPPA+ Stereo Photo Settings', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget( $args, $instance ) {

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'stereo' );
		wppa_bump_mocc( $this->id );
        extract( $args );
		$instance 		= wppa_parse_args( (array) $instance, $this->get_defaults() );
		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );

		// Logged in only and logged out?
		if ( wppa_checked( $instance['logonly'] ) && ! is_user_logged_in() ) {
			return;
		}

		// Make the widget content
		$widget_content = "\n".'<!-- WPPA+ stereo Widget start -->';
		$widget_content .= wppa_get_stereo_html();
		$widget_content .= '<div style="clear:both;" data-wppa="yes"></div>';
		$widget_content .= "\n".'<!-- WPPA+ stereo Widget end -->';

		// Output
		$result = "\n" . $before_widget;
		if ( ! empty( $widget_title ) ) {
			$result .= $before_title . $widget_title . $after_title;
		}
		$result .= $widget_content . $after_widget;

		wppa_echo( $result );
		echo wppa_widget_timer( 'show', $widget_title );

		wppa( 'in_widget', false );
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {

		// Completize all parms
		$instance = wppa_parse_args( $new_instance, $this->get_defaults() );

		// Sanitize certain args
		$instance['title'] 		= strip_tags( $instance['title'] );

        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );
    }

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Stereo Photo Settings', 'wp-photo-album-plus ' ),
							'logonly' 	=> 'no',
							);
		return $defaults;
	}


} // class wppaStereoWidget

// register wppaStereoWidget widget
add_action('widgets_init', 'wppa_register_wppaStereoWidget' );

function wppa_register_wppaStereoWidget() {
	register_widget("wppaStereoWidget");
}
