<?php
/**
 * Wp tabs widget.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/admin
 */

/**
 * Adds widget.
 */
class WP_Tabs_Widget extends WP_Widget {
	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'wptabs_widget',
			'description' => esc_html__( 'Create and display tabs', 'wp-expand-tabs-free' ),
		);
		parent::__construct( 'wptabs_widget', 'WP Tabs', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args Widget args.
	 * @param array $instance Widget instance value.
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget.
		// extract( $args, EXTR_SKIP ).
		$before_widget = $args['before_widget'];
		$before_title  = $args['before_title'];
		$after_title   = $args['after_title'];
		$after_widget  = $args['after_widget'];

		$title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title'] );
		$tabs  = empty( $instance['tabs'] ) ? '' : $instance['tabs'];

		echo ( isset( $before_widget ) ? wp_kses_post( $before_widget ) : '' );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $before_title . $title . $after_title );
		}
		if ( ! empty( $tabs ) ) {
			$output = '[wptabs id="' . $tabs . '"]';
			echo do_shortcode( $output );
		}

		echo ( isset( $after_widget ) ? wp_kses_post( $after_widget ) : '' );
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options.
	 */
	public function form( $instance ) {
		// outputs the options form on admin.
		$title      = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$tabs       = ! empty( $instance['tabs'] ) ? intval( $instance['tabs'] ) : '';
		$shortcodes = get_posts(
			array(
				'post_type'      => 'sp_wp_tabs',
				'posts_per_page' => -1,
			)
		);
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'wp-expand-tabs-free' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>"><?php esc_attr_e( 'Tab Groups:', 'wp-expand-tabs-free' ); ?></label>
			<br/>
			<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'tabs' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>" type="text">
				<?php
				foreach ( $shortcodes as $shortcode ) :
					?>
				<option value="<?php echo esc_attr( $shortcode->ID ); ?>" <?php echo ( $tabs === $shortcode->ID ) ? 'selected' : ''; ?>><?php echo esc_html( $shortcode->post_title ); ?></option>
					<?php
				endforeach;
				?>
			</select>
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved.
		$instance          = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['tabs']  = $new_instance['tabs'];
		return $instance;
	}

}
