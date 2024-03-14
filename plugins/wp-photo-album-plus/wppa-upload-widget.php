<?php
/* wppa-upload-widget.php
* Package: wp-photo-album-plus
*
* A wppa widget to upload photos
*
* Version: 8.4.03.002
*/

class WppaUploadWidget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'wppa_upload_widget', 'description' => __( 'Display upload photos dialog', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_upload_widget', __( 'WPPA+ Upload photos', 'wp-photo-album-plus' ), $widget_ops );
	}

	function widget( $args, $instance ) {

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'upload' );
		wppa_bump_mocc( $this->id );
        extract( $args );
		$instance 		= wppa_parse_args( (array) $instance, $this->get_defaults() );
		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );
		$cache 			= ! is_admin() && $instance['cache'];
		$cachefile 		= wppa_get_widget_cache_path( $this->id );

		// Logged in only and logged out?
		if ( wppa_checked( $instance['logonly'] ) && ! is_user_logged_in() ) {
			return;
		}

		// Restricted user?
		if ( wppa_user_is_basic() ) {
			return;
		}

		$album = $instance['album'];

		if ( ! wppa_album_exists( $album ) ) {
			$album = '0';	// Album vanished
		}

		wppa_user_upload();	// Do the upload if required

		$mocc = wppa( 'mocc' );

		$create = wppa_get_user_create_html( $album, wppa_opt( 'widget_width' ), 'widget' );
		$upload = wppa_get_user_upload_html( $album, wppa_opt( 'widget_width' ), 'widget' );

		// Anything to do?
		if ( ! $create && ! $upload ) {
			return;
		}

		$widget_content =
		'<div' .
			' id="wppa-container-' . $mocc . '"' .
			' class="wppa-upload-widget"' .
			' style="margin-top:2px;margin-left:2px;"' .
			' data-wppa="yes"' .
			' >' .
			$create .
			$upload .
			wppa( 'out' ) .
		'</div>';

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

	function update( $new_instance, $old_instance ) {

		// Completize all parms
		$instance = wppa_parse_args( $new_instance, $this->get_defaults() );

		// Sanitize certain args
		$instance['title'] = strip_tags( $instance['title'] );
		$instance['album'] = strval( intval( $new_instance['album'] ) );

		wppa_remove_widget_cache( $this->id );

		return $instance;
	}

	function form( $instance ) {

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Widget title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Album selection
		$body = wppa_album_select_a( array( 'path' => true, 'selected' => $instance['album'], 'addselbox' => true ) );
		wppa_widget_selection_frame( $this, 'album', $body, __( 'Album', 'wp-photo-album-plus' ) );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );
	}

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Upload photos', 'wp-photo-album-plus' ),
							'album' 	=> '0',
							'logonly' 	=> 'no',
							'cache' 	=> '0',
							);
		return $defaults;
	}
}

// register WppaUploadWidget
add_action('widgets_init', 'wppa_register_WppaUploadWidget' );

function wppa_register_WppaUploadWidget() {

	if ( wppa_get_option( 'wppa_email_on', 'yes' ) == 'yes' ) {
		register_widget("WppaUploadWidget");
	}
}