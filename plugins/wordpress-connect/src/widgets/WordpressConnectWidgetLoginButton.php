<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectUtils.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 12 May 2011
 *
 * @file WordpressConnectWidgetLoginButton.php
 *
 * This class provides methods for the login button widget
 */
class WordpressConnectWidgetLoginButton extends WP_Widget {

	/**
	 * Creates new instance of the WordpressConnectWidgetLoginButton
	 *
	 * @since 1.0
	 */
	function WordpressConnectWidgetLoginButton() {

		$widget_ops = array(
			'classname' => 'widget-wpc-login-button',
			'description' => __( 'Shows profile pictures of the user\'s friends who have already signed up for your site in addition to a login button.', WPC_TEXT_DOMAIN )
		);

		$this->WP_Widget( 'widget-wpc-login-button', 'WPC Login Button', $widget_ops );

	}

	/**
	 * Prints the widget
	 *
	 * @since 1.0
	 */
	function widget( $args, $instance ) {

		extract( $args );

		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$show_faces = $instance['show_faces'];
		$width = $instance['width'];
		$max_rows = $instance['max_rows'];
		$perms = $instance['perms'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title ){
			echo $before_title . $title . $after_title;
		}

		require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectLoginButton.php' );

		echo WordpressConnectLoginButton::getFbml( $show_faces, $width, $max_rows, $perms );

		/* After widget (defined by themes). */
		echo $after_widget;
	}

 	/**
	 * Saves the widget
	 *
	 * @since 1.0
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = $new_instance['title'];
		$instance['show_faces'] = $new_instance['show_faces'];
		$instance['width'] = $new_instance['width'];
		$instance['max_rows'] = $new_instance['max_rows'];
		$instance['perms'] = $new_instance['perms'];

		return $instance;

	}

 	/**
	 * Widget form in back-end
	 *
	 * @since 1.0
	 *
	 */
	function form( $instance ) {

		$default_options = get_option( WPC_OPTIONS );

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => _x( 'Login Button', 'widget title', WPC_TEXT_DOMAIN ),
			'show_faces' => WPC_OPTION_ENABLED,
			'width' => '200',
			'max_rows' => '1',
			'perms' => ''
		);

		$show_faces = $instance['show_faces'];
		$width = $instance['width'];
		$max_rows = $instance['max_rows'];
		$perms = $instance['perms'];

		$instance = wp_parse_args( (array) $instance, $defaults );

		$show_faces_options = array(
			WPC_OPTION_ENABLED => WPC_OPTION_ENABLED,
			WPC_OPTION_DISABLED => WPC_OPTION_DISABLED
		);
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', WPC_TEXT_DOMAIN ); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_faces' ); ?>"><?php _e( 'Show Faces', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'Show profile pictures below the button.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'show_faces' ), $this->get_field_id( 'show_faces' ), $show_faces_options, $instance['show_faces'] ); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The width of the plugin in pixels.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="number" size="8" min="200" max="1200" step="20" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'max_rows' ); ?>"><?php _e( 'Num rows', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The maximum number of rows of profile pictures to show.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="number" size="8" min="1" max="10" step="1" id="<?php echo $this->get_field_id( 'max_rows' ); ?>" name="<?php echo $this->get_field_name( 'max_rows' ); ?>" value="<?php echo $instance['max_rows']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'perms' ); ?>"><?php _e( 'Permissions', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'A comma separated list of extended permissions. By default the Login button prompts users for their public information. If your application needs to access other parts of the user\'s profile that may be private, your application can request extended permissions.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="text" size="32" id="<?php echo $this->get_field_id( 'perms' ); ?>" name="<?php echo $this->get_field_name( 'perms' ); ?>" value="<?php echo $instance['perms']; ?>"/>
		</p>
		<p><?php _e( 'A complete list of extended permissions can be found <a href="http://developers.facebook.com/docs/authentication/permissions/" target="_blank" rel="nofollow">here</a>.', WPC_TEXT_DOMAIN );?></p>

<?php
	}
}


?>