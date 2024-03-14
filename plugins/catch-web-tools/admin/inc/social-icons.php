<?php
/**
 * @package Frontend
 */

/**
 * Fget the social icon setting and format output
 * @return [string] [social icon information]
 */
function catchwebtools_get_social_icons() {
	//delete_transient( 'catchwebtools_social_display' );
	$social_settings = catchwebtools_get_options( 'catchwebtools_social' );

	if ( ! $social_settings['status'] ) {
		//Bail early if social icon module is disabled
		return;
	}

	$output = '';

	// Get any existing copy of our transient data
	if ( false === ( $output = get_transient( 'catchwebtools_social_display' ) ) ) {
		// It wasn't there, so regenerate the data and save the transient
		$output .= '<!-- Refresh CWT Social Icons Cache  --->';

		//Social Icon's Brand Color Options
		$brand_color = $social_settings['social_icon_brand_color'];

		$class = 'catchwebtools-social';

		if ( 'hover' == $brand_color ) {
			$class .= ' social-brand-hover';
		} elseif ( 'hover-static' == $brand_color ) {
			$class .= ' social-brand-static';
		}

		$output .= '
		<div class="' . $class . '">
			<ul>';

			$non_icon_setting = array(
				'status',
				'social_icon_size',
				'social_icon_color',
				'social_icon_hover_color',
				'social_icon_brand_color',
			);

			foreach ( $social_settings as $key => $value ) {
				if ( in_array( $key, $non_icon_setting ) ) {
					// Do not execute rest of the loop if the setting key is non icon option
					continue;
				}
				if ( '' != $value ) {
					if ( 'mail' == $key ) {
						$output .= '<a class="genericon genericon-' . sanitize_key( $key ) . '" target="_blank" title="' . esc_html__( 'Email', 'catch-web-tools' ) . '" href="mailto:' . antispambot( sanitize_email( $value ) ) . '"><span class="screen-reader-text">' . esc_html__( 'Email', 'catch-web-tools' ) . '</span> </a>';
					} elseif ( 'skype' == $key ) {
						$output .= '<a class="genericon genericon-' . sanitize_key( $key ) . '" target="_blank" title="' . esc_attr( $value ) . '" href="' . esc_attr( $value ) . '"><span class="screen-reader-text">' . esc_attr( $value ) . '</span> </a>';
					} elseif ( 'phone' == $key || 'handset' == $key ) {
						$output .= '<a class="genericon genericon-' . sanitize_key( $key ) . '" target="_blank" title="' . esc_attr( $value ) . '" href="tel:' . preg_replace( '/\s+/', '', esc_attr( $value ) ) . '"><span class="screen-reader-text">' . esc_attr( $value ) . '</span> </a>';
					} else {
						$output .= '<a class="genericon genericon-' . sanitize_key( $key ) . '" target="_blank" title="' . esc_attr( $value ) . '" href="' . esc_url( $value ) . '"><span class="screen-reader-text">' . esc_attr( $value ) . '</span> </a>';
					}
				}
			}
			$output .= '
			</ul>
		</div><!-- .catchwebtools-social -->';

			set_transient( 'catchwebtools_social_transient', $social_settings, 7 * DAY_IN_SECONDS );
	}

	return $output;
}


/**
 * Adds CatchWebToolsSocialIcons widget.
 */
class CatchWebToolsSocialIcons extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'catch_web_tools_social_icons', // Base ID
			'CWT Social Icons', // Name
			array( 'description' => esc_html__( 'Use this widget to add Catch Web Tools Social Icons as a widget. ', 'catch-web-tools' ) ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
		}

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo catchwebtools_get_social_icons();

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = esc_html__( 'Social Icons', 'catch-web-tools' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php esc_html_e( 'Title (optional):', 'catch-web-tools' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
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
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}

/**
 * Register social widget
 */
function catchwebtools_register_social_widget() {
	register_widget( 'CatchWebToolsSocialIcons' );
}

$social_settings = catchwebtools_get_options( 'catchwebtools_social' );

if ( $social_settings['status'] ) {
	add_action( 'widgets_init', 'catchwebtools_register_social_widget' );
	add_shortcode( 'catchthemes_social_icons', 'catchwebtools_get_social_icons' );
}

/**
 * Output contents of function catchwebtools_get_social_icons
 * @uses catchwebtools_get_social_icons
 */
function catchwebtools_social_icons() {
	echo catchwebtools_get_social_icons();
}
