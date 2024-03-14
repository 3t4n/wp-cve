<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectUtils.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 11 May 2011
 *
 * @file WordpressConnectWidgetFacepile
 *
 * This class provides methods for the facepile widget
 */
class WordpressConnectWidgetFacepile extends WP_Widget {

	/**
	 * Creates new instance of the WordpressConnectWidgetFacepile
	 *
	 * @since 1.0
	 */
	function WordpressConnectWidgetFacepile() {

		$widget_ops = array(
			'classname' => 'widget-wpc-facepile',
			'description' => __( 'Displays the Facebook profile pictures of users who have liked your page or have signed up for your site.', WPC_TEXT_DOMAIN )
		);

		$this->WP_Widget( 'widget-wpc-facepile', 'WPC Facepile', $widget_ops );

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
		$max_rows = $instance['max_rows'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title ){
			echo $before_title . $title . $after_title;
		}

		if ( empty( $url ) ){
			$url = get_home_url();
		}

		require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectFacepile.php' );

		echo WordpressConnectFacepile::getFbml( $url, $width, $max_rows );

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
		$instance['max_rows'] = $new_instance['max_rows'];

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
			'title' => _x( 'Facepile', 'widget title', WPC_TEXT_DOMAIN ),
			'url' => get_home_url(),
			'width' => '200',
			'max_rows' => '1'
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', WPC_TEXT_DOMAIN ); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'URL', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'If you want the Facepile to display friends who have liked your page, specify the URL of the page here.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="text" size="38" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>"  value="<?php echo $instance['url']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The width of the plugin in pixels.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="number" size="8" min="200" max="1200" step="20" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'max_rows' ); ?>"><?php _e( 'Num rows', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The maximum number of rows of profile pictures to show.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="number" size="8" min="1" max="10" step="1" id="<?php echo $this->get_field_id( 'max_rows' ); ?>" name="<?php echo $this->get_field_name( 'max_rows' ); ?>" value="<?php echo $instance['max_rows']; ?>"/>
		</p>
<?php
	}
}


?>