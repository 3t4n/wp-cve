<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectUtils.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 12 May 2011
 *
 * @file WordpressConnectWidgetLiveStream.php
 *
 * This class provides methods for the live stream widget
 */
class WordpressConnectWidgetLiveStream extends WP_Widget {

	/**
	 * Creates new instance of the WordpressConnectWidgetLiveStream
	 *
	 * @since 1.0
	 */
	function WordpressConnectWidgetLiveStream() {

		$widget_ops = array(
			'classname' => 'widget-wpc-live-stream',
			'description' => __( 'Lets users visiting your site or application share activity and comments in real time.', WPC_TEXT_DOMAIN )
		);

		$this->WP_Widget( 'widget-wpc-live-stream', 'WPC Live Stream', $widget_ops );

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
		$width = $instance['width'];
		$height = $instance['height'];
		$xid = $instance['xid'];
		$attribution = $instance['attribution'];
		$post_to_friends = $instance['post_to_friends'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title ){
			echo $before_title . $title . $after_title;
		}

		require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectLiveStream.php' );

		echo WordpressConnectLiveStream::getFbml( $width, $height, $xid, $attribution, $post_to_friends );

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
		$instance['width'] = $new_instance['width'];
		$instance['height'] = $new_instance['height'];
		$instance['xid'] = $new_instance['xid'];
		$instance['attribution'] = $new_instance['attribution'];
		$instance['post_to_friends'] = $new_instance['post_to_friends'];

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
			'title' => _x( 'Live Stream', 'widget title', WPC_TEXT_DOMAIN ),
			'width' => '200',
			'height' => '400',
			'xid' => '',
			'attribution' => '',
			'post_to_friends' => WPC_OPTION_ENABLED
		);

		$width = $instance['width'];
		$height = $instance['height'];
		$xid = $instance['xid'];
		$attribution = $instance['attribution'];
		$post_to_friends = $instance['post_to_friends'];

		$instance = wp_parse_args( (array) $instance, $defaults );

		$post_to_friends_options = array(
			WPC_OPTION_ENABLED => WPC_OPTION_ENABLED,
			WPC_OPTION_DISABLED => WPC_OPTION_DISABLED
		);
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', WPC_TEXT_DOMAIN ); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The width of the plugin in pixels.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="number" size="8" min="200" max="1200" step="20" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The height of the plugin in pixels.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="number" size="8" min="200" max="1200" step="20" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'xid' ); ?>"><?php _e( 'XID', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'If you have multiple live stream boxes on the same page, specify a unique "xid" for each.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="text" size="32" id="<?php echo $this->get_field_id( 'xid' ); ?>" name="<?php echo $this->get_field_name( 'xid' ); ?>" value="<?php echo $instance['xid']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'attribution' ); ?>"><?php _e( 'Via Attribution URL', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The URL that users are redirected to when they click on your app name on a status (if not specified, your Connect URL is used).', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="text" size="32" id="<?php echo $this->get_field_id( 'attribution' ); ?>" name="<?php echo $this->get_field_name( 'attribution' ); ?>" value="<?php echo $instance['attribution']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'post_to_friends' ); ?>"><?php _e( 'Always post to friends', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'If set, all user posts will always go to their profile. This option should only be used when users\' posts are likely to make sense outside of the context of the event.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'post_to_friends' ), $this->get_field_id( 'post_to_friends' ), $post_to_friends_options, $instance['post_to_friends'] ); ?>
		</p>

<?php
	}
}


?>