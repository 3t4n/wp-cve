<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectUtils.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 11 May 2011
 *
 * @file WordpressConnectWidgetRecommendations.php
 *
 * This class provides methods for the recommendations widget
 */
class WordpressConnectWidgetRecommendations extends WP_Widget {

	/**
	 * Creates new instance of the WordpressConnectWidgetRecommendations
	 *
	 * @since 1.0
	 */
	function WordpressConnectWidgetRecommendations() {

		$widget_ops = array(
			'classname' => 'widget-wpc-recommendations',
			'description' => __( 'Shows personalized recommendations to your users.', WPC_TEXT_DOMAIN )
		);

		$this->WP_Widget( 'widget-wpc-recommendations', 'WPC Recommendations', $widget_ops );

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
		$domain = $instance['domain'];
		$width = $instance['width'];
		$height = $instance['height'];
		$show_header = $instance['show_header'];
		$colorscheme = $instance['colorscheme'];
		$font = $instance['font'];
		$border_color = $instance['border_color'];
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

		require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectRecommendations.php' );

		echo WordpressConnectRecommendations::getFbml( $domain, $width, $height, $show_header, $colorscheme, $font, $border_color, $ref );

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
		$instance['domain'] = $new_instance['domain'];
		$instance['width'] = $new_instance['width'];
		$instance['height'] = $new_instance['height'];
		$instance['show_header'] = $new_instance['show_header'];
		$instance['colorscheme'] = $new_instance['colorscheme'];
		$instance['font'] = $new_instance['font'];
		$instance['border_color'] = $new_instance['border_color'];
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

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => _x( 'Recommendations', 'widget title', WPC_TEXT_DOMAIN ),
			'domain' => $_SERVER['HTTP_HOST'],
			'width' => '200',
			'height' => '400',
			'show_header' => WPC_OPTION_ENABLED,
			'colorscheme' => $default_options[ WPC_OPTIONS_THEME ],
			'font' => WPC_FONT_DEFAULT,
			'border_color' => '',
			'ref' => ''
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$show_header_options = array(
			WPC_OPTION_ENABLED => WPC_OPTION_ENABLED,
			WPC_OPTION_DISABLED => WPC_OPTION_DISABLED
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
			<label for="<?php echo $this->get_field_id( 'domain' ); ?>"><?php _e( 'Domain', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The domain to show recommendations for e.g. "www.example.com". Defaults to the domain the plugin is on.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="text" size="38" id="<?php echo $this->get_field_id( 'domain' ); ?>" name="<?php echo $this->get_field_name( 'domain' ); ?>"  value="<?php echo $instance['domain']; ?>"/>
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
			<label for="<?php echo $this->get_field_id( 'show_header' ); ?>"><?php _e( 'Show Header', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'Show the Facebook header on the plugin.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<?php WordpressConnectUtils::printSelectElement( $this->get_field_name( 'show_header' ), $this->get_field_id( 'show_header' ), $show_header_options, $instance['show_header'] ); ?>
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
			<label for="<?php echo $this->get_field_id( 'border_color' ); ?>"><?php _e( 'Border Color', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'The border color of the plugin.', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input size="10" id="<?php echo $this->get_field_id( 'border_color' ); ?>" name="<?php echo $this->get_field_name( 'border_color' ); ?>" value="<?php echo $instance['border_color']; ?>" class="inputtext" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ref' ); ?>"><?php _e( 'Ref', WPC_TEXT_DOMAIN ); ?>: <acronym title="<?php echo _e( 'A label for tracking referrals; must be less than 50 characters and can contain alphanumeric characters and some punctuation (currently +/=-.:_).', WPC_TEXT_DOMAIN ); ?>">(?)</acronym></label>
			<input type="text" size="20" id="<?php echo $this->get_field_id( 'ref' ); ?>" name="<?php echo $this->get_field_name( 'ref' ); ?>" value="<?php echo $instance['ref']; ?>" class="inputtext" />
		</p>
<?php
	}
}


?>
