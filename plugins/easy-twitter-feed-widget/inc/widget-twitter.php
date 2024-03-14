<?php
/**
 * Widget Name: Easy Twitter Feed Widget
 * Widget Description: Add twitter feeds on your WordPress site.
 *
 * @see https://publish.twitter.com/
 * @see https://dev.twitter.com/web/embedded-timelines
 */

/**
* Register the widget for use in Appearance -> Widgets
*/
add_action( 'widgets_init', 'do_etfw_widget_init' );
function do_etfw_widget_init() {
	register_widget( 'DO_ETFW_Widget' );
}

/**
 * Core class used to implement a widget.
 * @see WP_Widget
 */
class DO_ETFW_Widget extends WP_Widget {

	/**
	 * Default instance.
	 */
	protected $default_instance;

	/**
	 * Form Options
	 */
	protected $twitter_timeline_type;
	protected $twitter_widget_theme;

	/**
	 * Sets up a new widget instance.
	 */
	public function __construct() {

		// Initialize Default Instance
		$this->default_instance = array (
			'title'                       => esc_html__( 'Follow me on Twitter', 'do-etfw' ),
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

		// Initialize Form Options
		$this->set_form_options();

		// Widget Options
		$widget_ops = array (
			'classname'   => 'widget-do-etfw',
			'description' => esc_html__( 'Display an official Twitter Embedded Timeline widget.', 'do-etfw' ),
			'customize_selective_refresh' => true,
		);

		// Constructor
		parent::__construct (
			'do-etfw', // ID
			apply_filters( 'do_etfw_widget_name', esc_html__( 'Twitter Timeline (Easy Twitter Feed Widget)', 'do-etfw' ) ),
			$widget_ops
		);

		// Scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {

		if ( do_etfw_option( 'twitter_script' ) ) {
			wp_enqueue_script( 'do-etfw-twitter-widgets', DO_ETFW_URI . 'js/twitter-widgets.js', array( 'jquery' ), '1.0', true );
		}

	}

	/**
	 * Outputs the content for the current widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Custom HTML widget instance.
	 */
	function widget( $args, $instance ) {

		// Merge the instance arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, $this->default_instance );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		// Open the output of the widget.
		echo $args['before_widget'];

?>
		<?php if ( ! empty ( $title ) ) : ?>
			<?php echo $args['before_title'] . $title . $args['after_title']; ?>
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
			$timeline .= esc_html__( 'Tweets by @', 'do-etfw' ) . $instance['twitter_widget_username'];
			$timeline .= '</a>';

			// Output Markup
			echo $timeline;
		?>

<?php

		/** Close the output of the widget. */
		echo $args['after_widget'];

	}

	/**
	 * Handles updating settings for the current widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {

		// Instance
		$instance = $old_instance;

		// Sanitization
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		$instance['twitter_timeline_type'] = $new_instance['twitter_timeline_type'];
		if ( ! array_key_exists( $instance['twitter_timeline_type'], $this->twitter_timeline_type ) ) {
			$instance['twitter_timeline_type'] = $this->default_instance['twitter_timeline_type'];
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
		if ( ! array_key_exists( $instance['twitter_widget_theme'], $this->twitter_widget_theme ) ) {
			$instance['twitter_widget_theme'] = $this->default_instance['twitter_widget_theme'];
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
	 * Set Form Options
	 *
	 * @returns void
	 */
	public function set_form_options() {

		// Timeline Type
		$this->twitter_timeline_type = array (
			'username'  => esc_html__( 'Username',  'do-etfw'),
			'widget-id' => esc_html__( 'Widget ID', 'do-etfw'),
		);

		// Widget Theme
		$this->twitter_widget_theme = array (
			'light' => esc_html__( 'Light', 'do-etfw'),
			'dark'  => esc_html__( 'Dark', 'do-etfw'),
		);

	}

	/**
	 * Outputs the widget settings form.
	 *
	 * @param array $instance Current instance.
	 * @returns void
	 */
	function form( $instance ) {

		// Merge the instance arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, $this->default_instance );

?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'do-etfw' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_timeline_type' ) ); ?>"><?php esc_html_e( 'Timeline Type:', 'do-etfw' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_timeline_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_timeline_type' ) ); ?>">
              <?php foreach ( $this->twitter_timeline_type as $key => $val ): ?>
			    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $instance['twitter_timeline_type'], $key ); ?>><?php echo esc_html( $val ); ?></option>
			  <?php endforeach; ?>
            </select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_username' ) ); ?>"><?php esc_html_e( 'Twitter Username:', 'do-etfw' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_username' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_username'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_id' ) ); ?>"><?php esc_html_e( 'Widget ID:', 'do-etfw' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_id' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_id'] ); ?>" />
			<br />
			<small><?php echo esc_html__( 'It can be empty, if you are using Timeline Type "Username".', 'do-etfw' ); ?></small>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_width' ) ); ?>"><?php esc_html_e( 'Maximum Width:', 'do-etfw' ); ?></label>
			<input type="number" min="220" max="1200" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_width' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_width'] ); ?>" />
			<br />
			<small><?php echo esc_html__( 'px; 220 to 1200', 'do-etfw' ); ?></small>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_height' ) ); ?>"><?php esc_html_e( 'Height:', 'do-etfw' ); ?></label>
			<input type="number" min="200" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_height' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_height'] ); ?>" />
			<br />
			<small><?php echo esc_html__( 'px; at least 200', 'do-etfw' ); ?></small>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_tweet_limit' ) ); ?>"><?php esc_html_e( 'Number of Tweets Shown:', 'do-etfw' ); ?></label>
			<input type="number" min="1" max="20" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_tweet_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_tweet_limit' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_tweet_limit'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_theme' ) ); ?>"><?php esc_html_e( 'Theme:', 'do-etfw' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_theme' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_theme' ) ); ?>">
              <?php foreach ( $this->twitter_widget_theme as $key => $val ): ?>
			    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $instance['twitter_widget_theme'], $key ); ?>><?php echo esc_html( $val ); ?></option>
			  <?php endforeach; ?>
            </select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_link_color' ) ); ?>"><?php esc_html_e( 'Link Color (hex):', 'do-etfw' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_link_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_link_color' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_link_color'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_border_color' ) ); ?>"><?php esc_html_e( 'Border Color (hex):', 'do-etfw' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_widget_border_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_widget_border_color' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_widget_border_color'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_widget_chrome' ); ?>">
				<?php esc_html_e( 'Layout Options:', 'do-etfw' ); ?>
			</label>
			<br />
			<input type="checkbox" <?php checked( in_array( 'noheader', $instance['twitter_widget_chrome'] ) ); ?> id="<?php echo $this->get_field_id( 'twitter_widget_chrome_header' ); ?>" name="<?php echo $this->get_field_name( 'twitter_widget_chrome' ); ?>[]" value="noheader" />
			<label for="<?php echo $this->get_field_id( 'twitter_widget_chrome_header' ); ?>">
				<?php esc_html_e( 'No Header', 'do-etfw' ); ?>
			</label>
			<br />
			<input type="checkbox"<?php checked( in_array( 'nofooter', $instance['twitter_widget_chrome'] ) ); ?> id="<?php echo $this->get_field_id( 'twitter_widget_chrome_footer' ); ?>" name="<?php echo $this->get_field_name( 'twitter_widget_chrome' ); ?>[]" value="nofooter" />
			<label for="<?php echo $this->get_field_id( 'twitter_widget_chrome_footer' ); ?>">
				<?php esc_html_e( 'No Footer', 'do-etfw' ); ?>
			</label>
			<br />
			<input type="checkbox"<?php checked( in_array( 'noborders', $instance['twitter_widget_chrome'] ) ); ?> id="<?php echo $this->get_field_id( 'twitter_widget_chrome_border' ); ?>" name="<?php echo $this->get_field_name( 'twitter_widget_chrome' ); ?>[]" value="noborders" />
			<label for="<?php echo $this->get_field_id( 'twitter_widget_chrome_border' ); ?>">
				<?php esc_html_e( 'No Borders', 'do-etfw' ); ?>
			</label>
			<br />
			<input type="checkbox"<?php checked( in_array( 'noscrollbar', $instance['twitter_widget_chrome'] ) ); ?> id="<?php echo $this->get_field_id( 'twitter_widget_chrome_scrollbar' ); ?>" name="<?php echo $this->get_field_name( 'twitter_widget_chrome' ); ?>[]" value="noscrollbar" />
			<label for="<?php echo $this->get_field_id( 'twitter_widget_chrome_scrollbar' ); ?>">
				<?php esc_html_e( 'No Scrollbar', 'do-etfw' ); ?>
			</label>
			<br />
			<input type="checkbox"<?php checked( in_array( 'transparent', $instance['twitter_widget_chrome'] ) ); ?> id="<?php echo $this->get_field_id( 'twitter_widget_chrome_transparent' ); ?>" name="<?php echo $this->get_field_name( 'twitter_widget_chrome' ); ?>[]" value="transparent" />
			<label for="<?php echo $this->get_field_id( 'twitter_widget_chrome_transparent' ); ?>">
				<?php esc_html_e( 'Transparent Background', 'do-etfw' ); ?>
			</label>
		</p>

		<p>
			<?php
				printf( '%1$s <a href="%2$s" target="_blank">%3$s</a>',
					esc_html__( '30% discount on premium WordPress themes?', 'do-etfw' ),
					esc_url( add_query_arg( array( 'page' => 'do-etfw-options' ), admin_url( 'options-general.php' ) ) ),
					esc_html__( 'Get Discount Code', 'do-etfw' )
				);
			?>
		</p>

		<p>
			<?php
				printf( '%1$s <a href="%2$s" target="_blank">%3$s</a>',
					esc_html__( 'Do you like the plugin?', 'do-etfw' ),
					esc_url( 'https://wordpress.org/support/plugin/easy-twitter-feed-widget/reviews/' ),
					esc_html__( 'Please rate it at wordpress.org!', 'do-etfw' )
				);
			?>
		</p>

<?php
	}

} // End Class DO_ETFW_Widget
