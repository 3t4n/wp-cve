<?php
/* wppa-notify-widget.php
* Package: wp-photo-album-plus
*
* notify events to users
* Version 8.4.03.002
*/

class wppaNotifyWidget extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_notify_widget', 'description' => __( 'Let users select if they want to get emails on selected events', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_notify_widget', __( 'WPPA+ Notify Me', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget( $args, $instance ) {

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'notify' );
		wppa_bump_mocc( $this->id );
        extract( $args );
		$instance 		= wppa_parse_args( (array) $instance, $this->get_defaults() );
		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );

		// Only when logged in. We can not notify logged out users
		if ( ! is_user_logged_in() ) {
			return;
		}

		// Make the widget content
		$widget_content = '<!-- WPPA+ notify Widget start -->';
		$widget_content .= __( 'Notify me when...', 'wp-photo-album-plus' ) . '<br>';

		// Get the body of the widget
		$body = wppa_get_email_subscription_body();

		// Nothing to show?
		if ( ! $body ) {
			return;
		}

		$widget_content .= $body; //'<ul>'.$body.'</ul>';
		$widget_content .= '<input type="hidden" id="wppa-ntfy-nonce" value="' . wp_create_nonce( 'wppa-ntfy-nonce' ) . '" />';
		$widget_content .= '<div style="clear:both" ></div>';
		$widget_content .= "\n".'<!-- WPPA+ notify Widget end -->';

		// Output
		$result = "\n" . $before_widget;
		if ( ! empty( $widget_title ) ) {
			$result .= $before_title . $widget_title . $after_title;
		}
		$result .= $widget_content . $after_widget;

		wppa_echo( $result );
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
		wppa_echo( wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) ) );
    }

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Notify me', 'wp-photo-album-plus' ),
							'logonly' 	=> 'yes',
							'cache' 	=> '0',
							);
		return $defaults;
	}


} // class wppaNotifyWidget

// register wppaNotifyWidget widget
add_action( 'widgets_init', 'wppa_register_wppaNotifyWidget' );

function wppa_register_wppaNotifyWidget() {

	if ( wppa_get_option( 'wppa_email_on', 'yes' ) == 'yes' ) {
		register_widget( "wppaNotifyWidget" );
	}
}