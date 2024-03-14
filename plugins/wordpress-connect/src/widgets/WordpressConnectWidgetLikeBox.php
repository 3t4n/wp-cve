<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectUtils.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 12 May 2011
 *
 * @file WordpressConnectWidgetLikeBox.php
 *
 * This class provides methods for the like box widget
 */
class WordpressConnectWidgetLikeBox extends WP_Widget {

	/**
	 * Creates new instance of the WordpressConnectWidgetLikeBox
	 *
	 * @since 1.0
	 */
	function WordpressConnectWidgetLikeBox() {

		$widget_ops = array(
			'classname' => 'widget-wpc-like-box',
			'description' => __( 'Enables Facebook Page owners to attract and gain Likes from their own website.', WPC_TEXT_DOMAIN )
		);

		$this->WP_Widget( 'widget-wpc-like-box', 'WPC Like Box', $widget_ops );

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
		$url = $instance['url'];
		$width = $instance['width'];
		$height = $instance['height'];
		$colorscheme = $instance['colorscheme'];
		$show_faces = $instance['show_faces'];
		$show_stream = $instance['show_stream'];
		$show_header = $instance['show_header'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title ){
			echo $before_title . $title . $after_title;
		}

		if ( empty( $url ) ){
			$url = get_home_url();
		}

		require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectLikeBox.php' );

		echo WordpressConnectLikeBox::getFbml( $url, $width, $height, $colorscheme, $show_faces, $show_stream, $show_header );

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
		$instance['url'] = $new_instance['url'];
		$instance['width'] = $new_instance['width'];
		$instance['height'] = $new_instance['height'];
		$instance['colorscheme'] = $new_instance['colorscheme'];
		$instance['show_faces'] = $new_instance['show_faces'];
		$instance['show_stream'] = $new_instance['show_stream'];
		$instance['show_header'] = $new_instance['show_header'];

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
			'title' => _x( 'Like Box', 'widget title', WPC_TEXT_DOMAIN ),
			'url' => '',
			'width' => '200',
			'height' => '556',
			'colorscheme' => $default_options[ WPC_OPTIONS_THEME ],
			'show_faces' => WPC_OPTION_ENABLED,
			'show_stream' => WPC_OPTION_ENABLED,
			'show_header' => WPC_OPTION_ENABLED
		);

		$url = $instance['url'];
		$width = $instance['width'];
		$height = $instance['height'];
		$colorscheme = $instance['colorscheme'];
		$show_faces = $instance['show_faces'];
		$show_stream = $instance['show_stream'];
		$show_header = $instance['show_header'];

		$instance = wp_parse_args( (array) $instance, $defaults );

		$show_faces_options = array(
			WPC_OPTION_ENABLED => WPC_OPTION_ENABLED,
			WPC_OPTION_DISABLED => WPC_OPTION_DISABLED
		);
		$show_stream_options = array(
			WPC_OPTION_ENABLED => WPC_OPTION_ENABLED,
			WPC_OPTION_DISABLED => WPC_OPTION_DISABLED
		);
		$show_header_options = array(
			WPC_OPTION_ENABLED => WPC_OPTION_ENABLED,
			WPC_OPTION_DISABLED => WPC_OPTION_DISABLED
		);
		$colorscheme_options = array(
			WPC_THEME_LIGHT => WPC_THEME_LIGHT,
			WPC_THEME_DARK => WPC_THEME_DARK
		);
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', WPC_TEXT_DOMAIN ); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Facebook Page URL', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The URL of the Facebook Page for this Like box.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="text" size="38" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>"  value="<?php echo $instance['url']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The width of the plugin in pixels.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="number" size="8" min="200" max="1200" step="20" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The height of the plugin in pixels.', WPC_TEXT_DOMAIN ); ?> <?php echo _e( 'The default height varies based on number of faces to display, and whether the stream is displayed. With the stream displayed, and 10 faces the default height is 556px. With no faces, and no stream the default height is 63px.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="number" size="8" min="200" max="1200" step="20" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'colorscheme' ); ?>"><?php _e( 'Theme', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The Facebook theme', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'colorscheme' ), $this->get_field_id( 'colorscheme' ), $colorscheme_options, $instance['colorscheme'] ); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_faces' ); ?>"><?php _e( 'Show Faces', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'Show profile pictures below the button.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'show_faces' ), $this->get_field_id( 'show_faces' ), $show_faces_options, $instance['show_faces'] ); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_stream' ); ?>"><?php _e( 'Show Stream', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'Show the profile stream for the public profile.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'show_stream' ), $this->get_field_id( 'show_stream' ), $show_stream_options, $instance['show_stream'] ); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_header' ); ?>"><?php _e( 'Show Header', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'Show the Facebook header on the plugin.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'show_header' ), $this->get_field_id( 'show_header' ), $show_header_options, $instance['show_header'] ); ?>
		</p>

<?php
	}
}


?>