<?php

// add action hooks
add_action( 'widgets_init', 'tx_woocompare_widgets_init' );

/**
 * Registers widgets.
 *
 * @since 1.0.0
 * @action widgets_init
 */
function tx_woocompare_widgets_init() {

	register_widget( 'Tm_Woocommerce_Recent_Compare_Widget' );
}

/**
 * Recent compare widget.
 *
 * @since 1.0.0
 */
class Tm_Woocommerce_Recent_Compare_Widget extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		parent::__construct( 'tx_woocompare_recent_compare_list', esc_html__( 'TX WooCommerce Recent Compare', 'tx' ), array(
			'description' => esc_html__( 'Shows a recent compare on your site.', 'tx' ),
		) );
	}

	/**
	 * Renders widget settings form.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $instance The array of values, associated with current widget instance.
	 */
	public function form( $instance ) {

		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';

		?><p><label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php esc_html_e( 'Title:', 'tx' ) ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ) ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" type="text" value="<?php echo $title ?>"></p><?php
	}

	/**
	 * Renders widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args The array of widget settings.
	 * @param array $instance The array of widget instance settings.
	 */
	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Recent Compare', 'tx' ) : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'], $args['before_title'], $title, $args['after_title'];

		$list = tx_woocompare_get_list();

		echo '<div class="tm-woocompare-widget-wrapper">';

		echo tx_woocompare_list_render_widget( $list );

		echo '</div>';

		echo $args['after_widget'];
	}
}