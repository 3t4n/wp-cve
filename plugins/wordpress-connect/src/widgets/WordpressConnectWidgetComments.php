<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectUtils.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 11 May 2011
 *
 * @file WordpressConnectWidgetComments.php
 *
 * This class provides methods for the Comments Feed widget
 */
class WordpressConnectWidgetComments extends WP_Widget {

	/**
	 * Creates new instance of the WordpressConnectWidgetComments
	 *
	 * @since 1.0
	 */
	function WordpressConnectWidgetComments() {

		$widget_ops = array(
			'classname' => 'widget-wpc-comments',
			'description' => __( 'Enables Facebook comments on your site.', WPC_TEXT_DOMAIN )
		);

		$this->WP_Widget( 'widget-wpc-comments', 'WPC Comments', $widget_ops );

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
		$href = $instance['href'];
		$width = $instance['width'];
		$number_of_comments = $instance['number_of_comments'];
		$colorscheme = $instance['colorscheme'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title ){
			echo $before_title . $title . $after_title;
		}

		if ( empty( $href ) ){
			$href = get_home_url();
		}

		require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectComments.php' );

		echo WordpressConnectComments::getFbml( $href, $width, $number_of_comments, $colorscheme );

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
		$instance['href'] = $new_instance['href'];
		$instance['width'] = $new_instance['width'];
		$instance['number_of_comments'] = $new_instance['number_of_comments'];
		$instance['colorscheme'] = $new_instance['colorscheme'];

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
		$comments_options = get_option( WPC_OPTIONS_COMMENTS );

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => _x( 'Comments', 'widget title', WPC_TEXT_DOMAIN ),
			'href' => get_home_url(),
			'width' => $comments_options[ WPC_OPTIONS_COMMENTS_WIDTH ],
			'number_of_comments' => $comments_options[ WPC_OPTIONS_COMMENTS_NUMBER ],
			'colorscheme' => $default_options[ WPC_OPTIONS_THEME ]
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

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
			<label for="<?php echo $this->get_field_id( 'href' ); ?>"><?php _e( 'Site', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'URL (domain) of the site where the widget appears.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="text" id="<?php echo $this->get_field_id( 'href' ); ?>" name="<?php echo $this->get_field_name( 'href' ); ?>"  value="<?php echo $instance['href']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The width of the plugin in pixels.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="number" size="8" min="200" max="1200" step="20" name="<?php echo $this->get_field_name( 'width' ); ?>" size="8" value="<?php echo $instance['width']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number_of_comments' ); ?>"><?php _e( 'Number of Comments', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The number of comments to display, or 0 to hide all comments.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="number" size="8" min="1" step="1" name="<?php echo $this->get_field_name( 'number_of_comments' ); ?>" value="<?php echo $instance['number_of_comments']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'colorscheme' ); ?>"><?php _e( 'Theme', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The Facebook theme', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'colorscheme' ), $this->get_field_id( 'colorscheme' ), $colorscheme_options, $instance['colorscheme'] ); ?>
		</p>
<?php
	}
}


?>