<?php

/**
 * The file that used to register and load the cart widget
 *
 * @since      1.0.0
 * @package    Caddy
 * @subpackage Caddy/includes
 */
class caddy_cart_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'caddy_cart_widget',
			__( 'Caddy Cart', 'caddy' ),
			array( 'description' => __( 'Caddy cart widget', 'caddy' ), )
		);
	}

	/**
	 * Creating front-end widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$cart_widget_title = isset( $instance['cart_widget_title'] ) ? apply_filters( 'widget_title', $instance['cart_widget_title'] ) : '';
		$cc_cart_icon = isset( $instance['cc_cart_icon'] ) ? $instance['cc_cart_icon'] : '';
		$cart_text = isset( $instance['cart_text'] ) ? $instance['cart_text'] : '';

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $cart_widget_title ) ) {
			echo $args['before_title'] . $cart_widget_title . $args['after_title'];
		}

		$cart_count    = 0;
		$cc_cart_class = '';
		if ( ! is_admin() ) {
			$cart_count    = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
			$cc_cart_class = ( $cart_count == 0 ) ? 'cc_cart_count cc_cart_zero' : 'cc_cart_count';
		}
		$cart_icon_class = apply_filters( 'caddy_cart_bubble_icon', 'cp_icon_cart' );
		$cart_items_link = sprintf(
			'<a href="%1$s" class="cc_cart_items_list" aria-label="%2$s">%3$s %4$s <span class="%5$s">%6$s</span></a>',
			'javascript:void(0);',
			esc_html__( 'Cart Items', 'caddy' ),
			( 'on' == $cc_cart_icon ) ? '' : $cart_icon_class,
			esc_html( $cart_text ),
			$cc_cart_class,
			esc_html( $cart_count )
		);
		echo $cart_items_link;

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$cart_widget_title = isset( $instance['cart_widget_title'] ) ? $instance['cart_widget_title'] : __( 'New title', 'caddy' );
		$cart_text         = isset( $instance['cart_text'] ) ? $instance['cart_text'] : __( 'Cart', 'caddy' );
		$cc_cart_icon      = ( isset( $instance['cc_cart_icon'] ) && 'on' == $instance['cc_cart_icon'] ) ? ' checked="checked"' : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'cart_widget_title' ); ?>"><?php _e( 'Widget Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'cart_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'cart_widget_title' ); ?>"
			       type="text" value="<?php echo esc_attr( $cart_widget_title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cart_text' ); ?>"><?php _e( 'Cart Text:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'cart_text' ); ?>" name="<?php echo $this->get_field_name( 'cart_text' ); ?>" type="text"
			       value="<?php echo esc_attr( $cart_text ); ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php echo $cc_cart_icon; ?> id="<?php echo $this->get_field_id( 'cc_cart_icon' ); ?>"
			       name="<?php echo $this->get_field_name( 'cc_cart_icon' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'cc_cart_icon' ); ?>"><?php _e( 'Disable cart icon' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Updating widget replacing old instances with new
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                      = array();
		$instance['cart_widget_title'] = ( ! empty( $new_instance['cart_widget_title'] ) ) ? strip_tags( $new_instance['cart_widget_title'] ) : '';
		$instance['cart_text']         = ( ! empty( $new_instance['cart_text'] ) ) ? strip_tags( $new_instance['cart_text'] ) : '';
		$instance['cc_cart_icon']      = $new_instance['cc_cart_icon'];

		return $instance;
	}

}

/**
 * Register and load the cart widget
 */
function caddy_cart_widget() {
	register_widget( 'caddy_cart_widget' );
}

// Add action to register and load the cart widget
add_action( 'widgets_init', 'caddy_cart_widget' );
