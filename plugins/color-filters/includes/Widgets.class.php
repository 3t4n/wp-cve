<?php

class ewduwcfWidgetManager {

	public function __construct() {

		add_action( 'widgets_init', array( $this, 'register_color_filters_widget' ) );
	}

	public function register_color_filters_widget() {

		return register_widget( 'ewduwcfColorFiltersWidget' );
	}
}

class ewduwcfColorFiltersWidget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		parent::__construct(
			'nm_color_filters', // Base ID
			__( 'WooCommerce Filters', 'color-filters' ), // Name
			array( 'description' => __( 'WooCommerce product color filters.', 'color-filters' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		if ( ! function_exists( 'is_shop' ) or ( ! is_woocommerce() or is_product() ) ) { return; }

		
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo wp_kses_post( $args['before_widget'] );
		
		if ( ! empty( $title ) )
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );

		echo do_shortcode('[ultimate-woocommerce-filters]');
		
		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

		$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Filters', 'color-filters' );
		
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		
		return $instance;
	}
}
