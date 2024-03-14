<?php
/**
 * The file that defines the woo category slider widget.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/includes
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Widget main class.
 */
class Woo_Category_Slider_Widget extends WP_Widget {
	/**
	 * Construct function of the class.
	 */
	public function __construct() {
		parent::__construct(
			'Woo_Category_Slider_Widget',
			__( 'Woo Category Slider', 'woo-category-slider-grid' ),
			array(
				'description' => __( 'Display Category Slider for WooCommerce.', 'woo-category-slider-grid' ),
			)
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args widget args.
	 * @param array $instance widget instance value.
	 */
	public function widget( $args, $instance ) {
		extract( $args ); //phpcs:ignore

		$title        = apply_filters( 'widget_title', esc_attr( $instance['title'] ) );
		$shortcode_id = isset( $instance['shortcode_id'] ) ? absint( $instance['shortcode_id'] ) : 0;

		if ( ! $shortcode_id ) {
			return;
		}

		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}

		echo do_shortcode( '[woocatslider id=' . $shortcode_id . ']' );
		echo wp_kses_post( $args['after_widget'] );
	}


	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options.
	 */
	public function form( $instance ) {
		$shortcodes   = $this->shortcodes_list();
		$shortcode_id = ! empty( $instance['shortcode_id'] ) ? absint( $instance['shortcode_id'] ) : null;
		$title        = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';

		if ( count( $shortcodes ) > 0 ) {

			echo sprintf( '<p><label for="%1$s">%2$s</label>', esc_attr( $this->get_field_id( 'title' ) ), esc_html( __( 'Title:', 'woo-category-slider-grid' ) ) );
			echo sprintf( '<input type="text" class="widefat" id="%1$s" name="%2$s" value="%3$s" /></p>', esc_attr( $this->get_field_id( 'title' ) ), esc_attr( $this->get_field_name( 'title' ) ), esc_attr( $title ) );

			echo sprintf( '<p><label>%s</label>', esc_html( __( 'Shortcode:', 'woo-category-slider-grid' ) ) );
			echo sprintf( '<select class="widefat" name="%s">', esc_attr( $this->get_field_name( 'shortcode_id' ) ) );
			foreach ( $shortcodes as $shortcode ) {
				$selected = $shortcode->id === $shortcode_id ? 'selected="selected"' : '';
				echo sprintf(
					'<option value="%1$d" %3$s>%2$s</option>',
					esc_attr( $shortcode->id ),
					esc_html( $shortcode->title ),
					wp_kses_post( $selected )
				);
			}
			echo '</select></p>';

		} else {
			echo sprintf(
				'<p>%1$s <a href="' . esc_url( admin_url( 'post-new.php?post_type=sp_wcslider' ) ) . '">%3$s</a> %2$s</p>',
				esc_html__( 'You did not generate any slider yet.', 'woo-category-slider-grid' ),
				esc_html__( 'to generate a new slider now.', 'woo-category-slider-grid' ),
				esc_html__( 'click here', 'woo-category-slider-grid' )
			);
		}
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                 = array();
		$instance['title']        = sanitize_text_field( $new_instance['title'] );
		$instance['shortcode_id'] = absint( $new_instance['shortcode_id'] );

		return $instance;
	}

	/**
	 * Shortcodes list function
	 *
	 * @return statement
	 */
	private function shortcodes_list() {
		$shortcodes = get_posts(
			array(
				'post_type'      => 'sp_wcslider',
				'post_status'    => 'publish',
				'posts_per_page' => 1000,
			)
		);

		if ( count( $shortcodes ) < 1 ) {
			return array();
		}

		return array_map(
			function ( $shortcode ) {
					return (object) array(
						'id'    => absint( $shortcode->ID ),
						'title' => esc_html( $shortcode->post_title ),
					);
			},
			$shortcodes
		);
	}

}

/**
 *  Category Slider for WooCommerce Widget
 */
function sp_wcs_shortcode_widget() {
	register_widget( 'Woo_Category_Slider_Widget' );
}
add_action( 'widgets_init', 'sp_wcs_shortcode_widget' );
