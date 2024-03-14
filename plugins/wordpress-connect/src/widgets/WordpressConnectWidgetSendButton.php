<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectUtils.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 11 May 2011
 *
 * @file WordpressConnectWidgetSendButton.php
 *
 * This class provides methods for the Send Button widget
 */
class WordpressConnectWidgetSendButton extends WP_Widget {

	/**
	 * Creates new instance of the WordpressConnectWidgetSendButton
	 *
	 * @since 2.0
	 */
	function WordpressConnectWidgetSendButton() {

		$widget_ops = array(
			'classname' => 'widget-wpc-send-button',
			'description' => __( 'Adds a Facebook Send Button', WPC_TEXT_DOMAIN )
		);

		$this->WP_Widget( 'widget-wpc-send-button', 'WPC Send Button', $widget_ops );

	}

	/**
	 * Prints the widget
	 *
	 * @since 2.0
	 */
	function widget( $args, $instance ) {

		extract( $args );

		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$url = $instance['url'];
		$font = $instance['font'];
		$colorscheme = $instance['colorscheme'];
		$ref = $instance['ref'];
		$height = $instance['height'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title ){
			echo $before_title . $title . $after_title;
		}

		if ( empty( $url ) ){
			$url = get_home_url();
		}

		require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectSendButton.php' );

		echo WordpressConnectSendButton::getFbml( $url, $font, $colorscheme, $ref, $height );

		/* After widget (defined by themes). */
		echo $after_widget;
	}

 	/**
	 * Saves the widget
	 *
	 * @since 2.0
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = $new_instance['title'];
		$instance['url'] = $new_instance['url'];
		$instance['font'] = $new_instance['font'];
		$instance['colorscheme'] = $new_instance['colorscheme'];
		$instance['ref'] = $new_instance['ref'];
		$instance['height'] = $new_instance['height'];

		return $instance;

	}

 	/**
	 * Widget form in back-end
	 *
	 * @since 2.0
	 *
	 */
	function form( $instance ) {

		$default_options = get_option( WPC_OPTIONS );

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => _x( 'Send Button', 'widget title', WPC_TEXT_DOMAIN ),
			'url' => get_home_url(),
			'font' => WPC_FONT_DEFAULT,
			'colorscheme' => $default_options[ WPC_OPTIONS_THEME ],
			'ref' => '',
			'height' => '300'
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$font_options = array(
			WPC_FONT_ARIAL => WPC_FONT_ARIAL,
			WPC_FONT_LUCIDA_GRANDE => WPC_FONT_LUCIDA_GRANDE,
			WPC_FONT_SEGOE_UI => WPC_FONT_SEGOE_UI,
			WPC_FONT_TAHOMA => WPC_FONT_TAHOMA,
			WPC_FONT_TREBUCHET_MS => WPC_FONT_TREBUCHET_MS,
			WPC_FONT_VERDANA => WPC_FONT_VERDANA
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
			<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'URL to Send', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'URL to Send', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="text" size="38" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>"  value="<?php echo $instance['url']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'font' ); ?>"><?php _e( 'Font', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The font of the plugin', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'font' ), $this->get_field_id( 'font' ), $font_options, $instance['font'] ); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'colorscheme' ); ?>"><?php _e( 'Theme', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The Facebook theme', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'colorscheme' ), $this->get_field_id( 'colorscheme' ), $colorscheme_options, $instance['colorscheme'] ); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ref' ); ?>"><?php _e( 'Ref', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'A label for tracking referrals; must be less than 50 characters and can contain alphanumeric characters and some punctuation (currently +/=-.:_).', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="text" size="20" id="<?php echo $this->get_field_id( 'ref' ); ?>" name="<?php echo $this->get_field_name( 'ref' ); ?>" value="<?php echo $instance['ref']; ?>" class="inputtext" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height', WPC_TEXT_DOMAIN ); ?>: <acronym title="test">(?)</acronym></label>
			<input type="number" size="8" min="200" max="1200" step="20" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>"/>
		</p>
<?php
	}
}


?>