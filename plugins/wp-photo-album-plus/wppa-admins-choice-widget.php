<?php
/* wppa-admins-choice-widget.php
* Package: wp-photo-album-plus
*
* display the admins-choice widget
* Version 8.4.03.002
*
*/

class AdminsChoice extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_admins_choice', 'description' => __( 'Display admins choice of photos download links', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_admins_choice', __( 'WPPA+ Admins Choice', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {
		global $widget_content;

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'adminchoice' );
		wppa_bump_mocc( $this->id );
        extract( $args );
		$instance 		= wppa_parse_args( (array) $instance, $this->get_defaults() );
		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );

		// Logged in only and logged out?
		if ( wppa_checked( $instance['logonly'] ) && ! is_user_logged_in() ) {
			return;
		}

		// Make the widget content
		if ( ! wppa_opt( 'admins_choice' ) == 'none' ) {
			$widget_content = __( 'This feature is not enabled', 'wp-photo-album-plus' );
		}
		else {
			$widget_content =
			'<div class="wppa-admins-choice-widget" data-wppa="yes">' .
				wppa_get_admins_choice_html( false ) .
			'</div>';
		}

		$widget_content .= '<div style="clear:both"></div>';

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

		// Make sure the feature is enabled
		if ( wppa_opt( 'admins_choice' ) == 'none' ) {
			wppa_echo( '
			<p style="color:red">' .
				__( 'Please enable this feature', 'wp-photo-album-plus' ) . ' ' . wppa_see_also( 'system', '1', '28' ) . '
			</p>' );
		}

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Title
		wppa_echo( wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) ) );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );
	}

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Admins Choice', 'wp-photo-album-plus' ),
							'logonly' 	=> 'no',
							'cache' 	=> '0',
							);
		return $defaults;
	}


} // class AdminsChoice

// register Admins Choice widget
add_action( 'widgets_init', 'wppa_register_AdminsChoice' );

function wppa_register_AdminsChoice() {
	register_widget( "AdminsChoice" );
}