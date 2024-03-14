<?php
/* wppa-qr-widget.php
* Package: wp-photo-album-plus
*
* display qr code
* Version: 8.4.03.002
*
*/

class wppaQRWidget extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'qr_widget', 'description' => __( 'Display the QR code of the current url' , 'wp-photo-album-plus' ) );
		parent::__construct( 'qr_widget', __( 'WPPA+ QR Widget', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget( $args, $instance ) {

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'qr' );
		wppa_bump_mocc( $this->id );
        extract( $args );
		$instance 		= wppa_parse_args( (array) $instance, $this->get_defaults() );
		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );

		// Logged in only and logged out?
		if ( wppa_checked( $instance['logonly'] ) && ! is_user_logged_in() ) {
			return;
		}

		// Get the qrcode
		$qrsrc = wppa_create_qrcode_cache( $_SERVER['SCRIPT_URI'], wppa_opt( 'qr_size' ) );

		// Make the html
		$widget_content = '
		<div
			style="text-align:center;"
			data-wppa="yes"
			>
			<img
				id="wppa-qr-img"
				src="' . $qrsrc . '"
				title="' . esc_attr( wppa_convert_to_pretty( $_SERVER['SCRIPT_URI'] ) ) . '"
				alt="' . __( 'QR code', 'wp-photo-album-plus' ) . '"
			/>
		</div>
		<div style="clear:both" ></div>';

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

			$result = '
			<p>' .
				__( 'You can set the sizes and colors in this widget in the <b>Photo Albums -> Settings</b> admin page Tab Widgets -> III', 'wp-photo-album-plus' ) .
				wppa_see_also( 'widget', '3' ) .
			'</p>';
			wppa_echo( strip_tags( wp_check_invalid_utf8( $result), ["<br>", "<a>", "<i>", "<b>", "<p>"] ) );
		};
	}

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'QR Code', 'wp-photo-album-plus' ),
							'logonly' 	=> 'no',
							'cache' 	=> '0',
							);
		return $defaults;
	}

} // class wppaQRWidget

// register wppaQRWidget widget
add_action('widgets_init', 'wppa_register_QRWidget' );

function wppa_register_QRWidget() {
	register_widget( "wppaQRWidget" );
}
