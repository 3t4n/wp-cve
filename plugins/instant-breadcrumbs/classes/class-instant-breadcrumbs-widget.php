<?php
/**
 * Adds Instant_Breadcrumbs_Widget widget.
 */
class Instant_Breadcrumbs_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
				'lym_ib_widget', // Base ID
				__( 'Instant Breadcrumbs', 'instant-breadcrumbs' ), // Name
				array( 'description' => __( 'Shows the Instant Breadcrumbs trail in the widget area', 'instant-breadcrumbs' ), ) // Args
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
		$params = self::sanitize_params( $instance );
		$title  = apply_filters( 'widget_title', $params['title'] );

		// @codingStandardsIgnoreStart
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		instant_breadcrumb( $params['separator'] );
		echo $args['after_widget'];
		// @codingStandardsIgnoreEnd
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$params = self::sanitize_params( $instance );
		// @codingStandardsIgnoreStart
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'instant-breadcrumbs' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $params['title'] ); ?>">
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'HTML separator:', 'instant-breadcrumbs' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'separator' ); ?>" name="<?php echo $this->get_field_name( 'separator' ); ?>" type="text" value="<?php echo esc_attr( $params['separator'] ); ?>">
		</p>
		<?php
		// codingStandardsIgnoreEnd
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
		$instance = self::sanitize_params( $old_instance );
		if ( isset( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( $new_instance['title'] );
		}
		if ( isset( $new_instance['separator'] ) ) {
			$instance['separator'] = $new_instance['separator'];
		}
		return $instance;
	}
	
	public static function sanitize_params( $in ) {
		$out = array();
		if ( is_array( $in ) && isset( $in['title'] ) && is_string( $in['title'] ) ) {
			$out['title'] = $in['title'];
		} else {
			$out['title'] = __( 'Breadcrumbs', 'instant-breadcrumbs' );
		}
		if ( is_array( $in ) && isset( $in['separator'] ) && is_string( $in['separator'] ) ) {
			$out['separator'] = $in['separator'];
		} else {
			$out['separator'] = '';
		}
		return $out;
	}
	
	public static function register_this_widget() {
		register_widget( 'Instant_Breadcrumbs_Widget' );
	}
	public static function hook() {
		add_action( 'widgets_init', array( 'Instant_Breadcrumbs_Widget', 'register_this_widget' ) );
	}
} // class Instant_Breadcrumbs_Widget
