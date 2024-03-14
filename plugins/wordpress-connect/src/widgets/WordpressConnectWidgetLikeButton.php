<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectUtils.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 11 May 2011
 *
 * @file WordpressConnectWidgetLikeButton.php
 *
 * This class provides methods for the Like Button widget
 */
class WordpressConnectWidgetLikeButton extends WP_Widget {

	/**
	 * Creates new instance of the WordpressConnectWidgetLikeButton
	 *
	 * @since 1.0
	 */
	function WordpressConnectWidgetLikeButton() {

		$widget_ops = array(
			'classname' => 'widget-wpc-like-button',
			'description' => __( 'Adds a Facebook Like Button', WPC_TEXT_DOMAIN )
		);

		$this->WP_Widget( 'widget-wpc-like-button', 'WPC Like Button', $widget_ops );

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
		$send_button = $instance['send_button'];
		$layout = $instance['layout'];
		$width = $instance['width'];
		$show_faces = $instance['show_faces'];
		$verb = $instance['verb'];
		$colorscheme = $instance['colorscheme'];
		$font = $instance['font'];
		$ref = $instance['ref'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title ){
			echo $before_title . $title . $after_title;
		}

		if ( empty( $url ) ){
			$url = get_home_url();
		}

		require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectLikeButton.php' );

		echo WordpressConnectLikeButton::getFbml( $url, $send_button, $layout, $width, $show_faces, $verb, $colorscheme, $font, $ref );

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
		$instance['send_button'] = $new_instance['send_button'];
		$instance['layout'] = $new_instance['layout'];
		$instance['width'] = $new_instance['width'];
		$instance['show_faces'] = $new_instance['show_faces'];
		$instance['verb'] = $new_instance['verb'];
		$instance['colorscheme'] = $new_instance['colorscheme'];
		$instance['font'] = $new_instance['font'];
		$instance['ref'] = $new_instance['ref'];

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
		$like_button_options = get_option( WPC_OPTIONS_LIKE_BUTTON );

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => _x( 'Like Button', 'widget title', WPC_TEXT_DOMAIN ),
			'url' => get_home_url(),
			'send_button' => WPC_OPTION_ENABLED,
			'layout' => WPC_LAYOUT_STANDARD,
			'width' => $like_button_options[ WPC_OPTIONS_LIKE_BUTTON_WIDTH ],
			'show_faces' => WPC_OPTION_ENABLED,
			'verb' => WPC_ACTION_LIKE,
			'colorscheme' => $default_options[ WPC_OPTIONS_THEME ],
			'font' => WPC_FONT_DEFAULT,
			'ref' => ''
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$send_button_options = array(
			WPC_OPTION_ENABLED => WPC_OPTION_ENABLED,
			WPC_OPTION_DISABLED => WPC_OPTION_DISABLED
		);
		$layout_options = array(
			WPC_LAYOUT_STANDARD => WPC_LAYOUT_STANDARD,
			WPC_LAYOUT_BUTTON_COUNT => WPC_LAYOUT_BUTTON_COUNT,
			WPC_LAYOUT_BOX_COUNT => WPC_LAYOUT_BOX_COUNT
		);
		$show_faces_options = array(
			WPC_OPTION_ENABLED => WPC_OPTION_ENABLED,
			WPC_OPTION_DISABLED => WPC_OPTION_DISABLED
		);
		$verb_options = array(
			WPC_ACTION_LIKE => WPC_ACTION_LIKE,
			WPC_ACTION_RECOMMEND => WPC_ACTION_RECOMMEND
		);
		$colorscheme_options = array(
			WPC_THEME_LIGHT => WPC_THEME_LIGHT,
			WPC_THEME_DARK => WPC_THEME_DARK
		);
		$font_options = array(
			WPC_FONT_ARIAL => WPC_FONT_ARIAL,
			WPC_FONT_LUCIDA_GRANDE => WPC_FONT_LUCIDA_GRANDE,
			WPC_FONT_SEGOE_UI => WPC_FONT_SEGOE_UI,
			WPC_FONT_TAHOMA => WPC_FONT_TAHOMA,
			WPC_FONT_TREBUCHET_MS => WPC_FONT_TREBUCHET_MS,
			WPC_FONT_VERDANA => WPC_FONT_VERDANA
		);
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', WPC_TEXT_DOMAIN ); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'URL to Like', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'URL to Like', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="text" size="38" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>"  value="<?php echo $instance['url']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'send_button' ); ?>"><?php _e( 'Send Button', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'Include a Send Button', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'send_button' ), $this->get_field_id( 'send_button' ), $send_button_options, $instance['send_button'] ); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'layout' ); ?>"><?php _e( 'Layout Style', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'Include a Send Button', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'layout' ), $this->get_field_id( 'layout' ), $layout_options, $instance['layout'] ); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The width of the plugin in pixels.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="number" size="8" min="200" max="1200" step="20" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_faces' ); ?>"><?php _e( 'Show Faces', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'Show profile pictures below the button.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'show_faces' ), $this->get_field_id( 'show_faces' ), $show_faces_options, $instance['show_faces'] ); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'verb' ); ?>"><?php _e( 'Verb to display', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The verb to display in the button. Currently only "like" and "recommend" are supported.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'verb' ), $this->get_field_id( 'verb' ), $verb_options, $instance['verb'] ); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'colorscheme' ); ?>"><?php _e( 'Theme', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The Facebook theme', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'colorscheme' ), $this->get_field_id( 'colorscheme' ), $colorscheme_options, $instance['colorscheme'] ); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'font' ); ?>"><?php _e( 'Font', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The font of the plugin', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'font' ), $this->get_field_id( 'font' ), $font_options, $instance['font'] ); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ref' ); ?>"><?php _e( 'Ref', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'A label for tracking referrals; must be less than 50 characters and can contain alphanumeric characters and some punctuation (currently +/=-.:_).', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="text" size="20" id="<?php echo $this->get_field_id( 'ref' ); ?>" name="<?php echo $this->get_field_name( 'ref' ); ?>" value="<?php echo $instance['ref']; ?>" class="inputtext" />
		</p>
<?php
	}
}


?>