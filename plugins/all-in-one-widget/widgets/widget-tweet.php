<?php
/**
 * Twitter Widget Class
 */
if ( !defined('ABSPATH')) exit;

class Themeidol_Widget_Tweets extends WP_Widget {

	/**
	 * Registers the widget with WordPress.
	 */
	public function __construct() {

		parent::__construct(
			'themeidol-tweets',
			apply_filters( 'themeidol_weets_widget', esc_html__( 'Themeidol-Twitter Timeline ', 'themeidol-all-widget' ) ),
			array(
				'classname'   => 'widget-themeidol-tweets',
				'description' => esc_html__( 'Display an official Twitter Embedded Timeline widget.', 'themeidol-all-widget' )
			)
		);

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		// Refreshing the widget's cached output with each new post
	    add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
	    add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
	    add_action( 'delete_attachment', array( $this, 'flush_group_cache' ) );
	    add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );   
	}

	/**
	 * Enqueue scripts for front-end.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'themeidol-twitter-widgets', THEMEIDOL_WIDGET_JS_URL . 'twitter-widgets.js', array( 'jquery' ), '1.0', true );
	}


	public function flush_widget_cache() {
    		wp_cache_delete( 'themeidol-tweetstimeline', 'widget' );
  	}

	/**
	 * Custom functions for the plugin
	 */

	/**
	 * Plugin Options Defaults
	 *
	 * Sane Defaults Logic
	 * Plugin will not save default settings to the database without explicit user action
	 * and Plugin will function properly out-of-the-box without user configuration.
	 *
	 * @param string $option - Name of the option to retrieve.
	 * @return mixed
	 */
	function themeidol_tweets_option_default( $option = 'enable' ) {

		$themeidol_options_default = array (
			'twitter_script' => true,
		);

		if( isset( $themeidol_options_default[$option] ) ) {
			return $themeidol_options_default[$option];
		}

		return '';

	}

	/**
	 * Retrieve the plugin option.
	 *
	 * @param string $option - Name of the option to retrieve.
	 * @return mixed
	 */
	function themeidol_tweets_option( $option = 'twitter_script' ) {
		$themeidol_options_default = array (
			'twitter_script' => true,
		);
		$themeidol_tweets_options = apply_filters( 'themeidol_tweets_options', $themeidol_options_default );

		if ( isset( $themeidol_tweets_options[$option] ) ) {
			return $themeidol_tweets_options[$option];
		} else {
			return $this->themeidol_tweets_option_default( $option );
		}

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {
		 $cache    = (array) wp_cache_get( 'themeidol-tweetstimeline', 'widget' );

         if(!is_array($cache)) $cache = array();
      
         if(isset($cache[$args['widget_id']])){
            echo $cache[$args['widget_id']];
            return;
         }
      	ob_start();
		// Defaults
		$defaults = $this->defaults();

		// Merge the user-selected arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, $defaults );

		$before_widget = $args['before_widget'];
		if (strpos($args['before_widget'], 'widget ') !== false) {
            $before_widget = preg_replace('/widget /', "idol-widget ", $args['before_widget'], 1);
        }
		// Open the output of the widget.
		echo $before_widget;

?>
		<?php if ( ! empty( $instance['title'] ) ) : ?>
			<?php echo $args['before_title'] . apply_filters( 'widget_title',  esc_attr($instance['title']), $instance, $this->id_base ) . $args['after_title']; ?>
		<?php endif; ?>

		<?php
			// Build Twitter Markup
			// @see https://dev.twitter.com/web/embedded-timelines
			$timeline = '<a class="twitter-timeline"';

			// Data Attributes
			$data_attribs = array (
				'twitter_widget_width'        => 'width',
				'twitter_widget_height'       => 'height',
				'twitter_widget_tweet_limit'  => 'tweet-limit',
				'twitter_widget_theme'        => 'theme',
				'twitter_widget_link_color'   => 'link-color',
				'twitter_widget_border_color' => 'border-color',
			);
			foreach ( $data_attribs as $key => $val ) {
				if ( ! empty( $instance[ $key ] ) ) {
					$timeline .= ' data-' . esc_attr( $val ) . '="' . esc_attr( $instance[ $key ] ) . '"';
				}
			}

			// Chrome Settings
			if ( ! empty( $instance['twitter_widget_chrome'] ) && is_array( $instance['twitter_widget_chrome'] ) ) {
				$timeline .= ' data-chrome="' . esc_attr( join ( ' ', $instance['twitter_widget_chrome'] ) ) . '"';
			}

			// Widget Timeline Route
			switch ( $instance['twitter_timeline_type'] ) {
				case 'username':
					$timeline .= ' href="https://twitter.com/' . esc_attr( $instance['twitter_widget_username'] ) . '"';
					break;
				case 'widget-id':
				default:
					$timeline .= ' data-widget-id="' . esc_attr( $instance['twitter_widget_id'] ) . '"';
					break;
			}

			// Close Twitter Markup
			$timeline .= '>';
			$timeline .= esc_html__( 'Tweets by @', 'themeidol-all-widget' ) . esc_attr($instance['twitter_widget_username']);
			$timeline .= '</a>';

			// Output Markup
			echo $timeline;

		?>

<?php

		/** Close the output of the widget. */
		echo $args['after_widget'];
		$widget_string = ob_get_flush();
		$cache[$args['widget_id']] = $widget_string;
		wp_cache_add('themeidol-tweetstimeline', $cache, 'widget');


	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		// Instance
		$instance = $old_instance;

		// Sanitization
		$instance['title'] = strip_tags( $new_instance['title'] );

		$instance['twitter_timeline_type'] = $new_instance['twitter_timeline_type'];
		if ( ! in_array( $instance['twitter_timeline_type'], array( 'widget-id', 'username' ) ) ) {
			$instance['twitter_timeline_type'] = 'widget-id';
		}

		$instance['twitter_widget_username'] = sanitize_text_field( $new_instance['twitter_widget_username'] );
		$instance['twitter_widget_id']       = sanitize_text_field( $new_instance['twitter_widget_id'] );

		$twitter_widget_width = absint( $new_instance['twitter_widget_width'] );
		if ( $twitter_widget_width ) {
			// From publish.twitter.com: 220 <= width <= 1200
			$instance['twitter_widget_width'] = min ( max ( $twitter_widget_width, 220 ), 1200 );
		} else {
			$instance['twitter_widget_width'] = '';
		}

		$twitter_widget_height = absint( $new_instance['twitter_widget_height'] );
		if ( $twitter_widget_height ) {
			// From publish.twitter.com: height >= 200
			$instance['twitter_widget_height'] = max ( $twitter_widget_height, 200 );
		} else {
			$instance['twitter_widget_height'] = '';
		}

		$twitter_widget_tweet_limit = absint( $new_instance['twitter_widget_tweet_limit'] );
		$instance['twitter_widget_tweet_limit'] = ( $twitter_widget_tweet_limit ? $twitter_widget_tweet_limit : null );

		$instance['twitter_widget_theme'] = $new_instance['twitter_widget_theme'];
		if ( ! in_array( $instance['twitter_widget_theme'], array( 'light', 'dark' ) ) ) {
			$instance['twitter_widget_theme'] = 'light';
		}

		$instance['twitter_widget_link_color']   = sanitize_hex_color( $new_instance['twitter_widget_link_color'] );
		$instance['twitter_widget_border_color'] = sanitize_hex_color( $new_instance['twitter_widget_border_color'] );

		$instance['twitter_widget_chrome'] = array();
		$chrome_settings = array(
			'noheader',
			'nofooter',
			'noborders',
			'noscrollbar',
			'transparent'
		);
		if ( isset( $new_instance['twitter_widget_chrome'] ) ) {
			foreach ( $new_instance['twitter_widget_chrome'] as $chrome ) {
				if ( in_array( $chrome, $chrome_settings ) ) {
					$instance['twitter_widget_chrome'][] = $chrome;
				}
			}
		}

		return $instance;

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	function form( $instance ) {

		// Defaults
		$defaults = $this->defaults();

		// Merge the user-selected arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Controls
		$twitter_timeline_type = array (
			'username'  => esc_html__( 'Username',  'themeidol-all-widget'),
			'widget-id' => esc_html__( 'Widget ID', 'themeidol-all-widget'),
		);

		$twitter_widget_theme = array (
			'light' => esc_html__( 'Light', 'themeidol-all-widget'),
			'dark'  => esc_html__( 'Dark', 'themeidol-all-widget'),
		);
?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'themeidol-all-widget' ); ?>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_timeline_type' ); ?>">
				<?php esc_html_e( 'Timeline Type:', 'themeidol-all-widget' ); ?>
				<a href="https://designorbital.com/easy-twitter-feed-widget/" target="_blank">( ? )</a>
			</label>
			<select class="widefat" name="<?php echo $this->get_field_name( 'twitter_timeline_type' ); ?>" id="<?php echo $this->get_field_id( 'twitter_timeline_type' ); ?>">
              <?php foreach ( $twitter_timeline_type as $key => $val ): ?>
			    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $instance['twitter_timeline_type'], $key ); ?>><?php echo esc_html( $val ); ?></option>
			  <?php endforeach; ?>
            </select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_username' ) ); ?>">
				<?php esc_html_e( 'Twitter Username:', 'themeidol-all-widget' ); ?>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_username' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_username'] ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_id' ) ); ?>">
				<?php esc_html_e( 'Widget ID:', 'dthemeidol-all-widget' ); ?>
				<br /><code><?php echo esc_html__( 'It can be empty, if you are using Timeline Type "Username".', 'do-etfw' ); ?></code>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_id' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_id'] ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_width' ) ); ?>">
				<?php esc_html_e( 'Maximum Width (px; 220 to 1200):', 'themeidol-all-widget' ); ?>
				<input type="number" min="220" max="1200" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_width' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_width'] ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_height' ) ); ?>">
				<?php esc_html_e( 'Height (px; at least 200):', 'themeidol-all-widget' ); ?>
				<input type="number" min="200" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_height' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_height'] ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_tweet_limit' ) ); ?>">
				<?php esc_html_e( 'Number of Tweets Shown:', 'themeidol-all-widget' ); ?>
				<input type="number" min="1" max="20" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_tweet_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_tweet_limit' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_tweet_limit'] ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_widget_theme' ); ?>">
				<?php esc_html_e( 'Theme:', 'themeidol-all-widget' ); ?>
			</label>
			<select class="widefat" name="<?php echo $this->get_field_name( 'twitter_widget_theme' ); ?>" id="<?php echo $this->get_field_id( 'twitter_widget_theme' ); ?>">
              <?php foreach ( $twitter_widget_theme as $key => $val ): ?>
			    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $instance['twitter_widget_theme'], $key ); ?>><?php echo esc_html( $val ); ?></option>
			  <?php endforeach; ?>
            </select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_link_color' ) ); ?>">
				<?php esc_html_e( 'Link Color (hex):', 'themeidol-all-widget' ); ?>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_link_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_link_color' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_link_color'] ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_border_color' ) ); ?>">
				<?php esc_html_e( 'Border Color (hex):', 'themeidol-all-widget' ); ?>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_border_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_border_color' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_border_color'] ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_widget_chrome' ); ?>">
				<?php esc_html_e( 'Layout Options:', 'themeidol-all-widget' ); ?>
			</label>
			<br />
			<input type="checkbox" <?php checked( in_array( 'noheader', $instance['twitter_widget_chrome'] ) ); ?> id="<?php echo $this->get_field_id( 'twitter_widget_chrome_header' ); ?>" name="<?php echo $this->get_field_name( 'twitter_widget_chrome' ); ?>[]" value="noheader" />
			<label for="<?php echo $this->get_field_id( 'twitter_widget_chrome_header' ); ?>">
				<?php esc_html_e( 'No Header', 'themeidol-all-widget' ); ?>
			</label>
			<br />
			<input type="checkbox"<?php checked( in_array( 'nofooter', $instance['twitter_widget_chrome'] ) ); ?> id="<?php echo $this->get_field_id( 'twitter_widget_chrome_footer' ); ?>" name="<?php echo $this->get_field_name( 'twitter_widget_chrome' ); ?>[]" value="nofooter" />
			<label for="<?php echo $this->get_field_id( 'twitter_widget_chrome_footer' ); ?>">
				<?php esc_html_e( 'No Footer', 'themeidol-all-widget' ); ?>
			</label>
			<br />
			<input type="checkbox"<?php checked( in_array( 'noborders', $instance['twitter_widget_chrome'] ) ); ?> id="<?php echo $this->get_field_id( 'twitter_widget_chrome_border' ); ?>" name="<?php echo $this->get_field_name( 'twitter_widget_chrome' ); ?>[]" value="noborders" />
			<label for="<?php echo $this->get_field_id( 'twitter_widget_chrome_border' ); ?>">
				<?php esc_html_e( 'No Borders', 'themeidol-all-widget' ); ?>
			</label>
			<br />
			<input type="checkbox"<?php checked( in_array( 'noscrollbar', $instance['twitter_widget_chrome'] ) ); ?> id="<?php echo $this->get_field_id( 'twitter_widget_chrome_scrollbar' ); ?>" name="<?php echo $this->get_field_name( 'twitter_widget_chrome' ); ?>[]" value="noscrollbar" />
			<label for="<?php echo $this->get_field_id( 'twitter_widget_chrome_scrollbar' ); ?>">
				<?php esc_html_e( 'No Scrollbar', 'themeidol-all-widget' ); ?>
			</label>
			<br />
			<input type="checkbox"<?php checked( in_array( 'transparent', $instance['twitter_widget_chrome'] ) ); ?> id="<?php echo $this->get_field_id( 'twitter_widget_chrome_transparent' ); ?>" name="<?php echo $this->get_field_name( 'twitter_widget_chrome' ); ?>[]" value="transparent" />
			<label for="<?php echo $this->get_field_id( 'twitter_widget_chrome_transparent' ); ?>">
				<?php esc_html_e( 'Transparent Background', 'themeidol-all-widget' ); ?>
			</label>
		</p>

<?php
	}

	// Defaults
	public function defaults() {

		$defaults = array(
			'title'                       => esc_html__( 'Follow me on Twitter', 'themeidol-all-widget' ),
			'twitter_timeline_type'       => 'username',
			'twitter_widget_username'     => 'DesignOrbital',
			'twitter_widget_id'           => '',
			'twitter_widget_width'        => '',
			'twitter_widget_height'       => 400,
			'twitter_widget_tweet_limit'  => null,
			'twitter_widget_theme'        => 'light',
			'twitter_widget_link_color'   => '#3b94d9',
			'twitter_widget_border_color' => '#f5f5f5',
			'twitter_widget_chrome'       => array(),
		);

		return $defaults;

	}

}
add_action( 'widgets_init', create_function( '', 'return register_widget("Themeidol_Widget_Tweets");' ) );