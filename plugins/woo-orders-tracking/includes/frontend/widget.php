<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'VI_WOO_ORDERS_TRACKING_WIDGET' ) ) {
	class VI_WOO_ORDERS_TRACKING_WIDGET extends WP_Widget {

		public function __construct() {
			parent::__construct(
				"vi_wot_track_order",
				esc_html__( 'Orders Tracking', 'woo-orders-tracking' ),
				array(
					'classname'   => 'vi-wot-widget-form-tracking',
					'description' => 'Check the status of your shipment'
				) );
		}

		public function widget( $args, $instance ) {
			if ( isset( $instance['title'] ) ) {
				$title = apply_filters( 'widget_title', $instance['title'] );
				echo wp_kses_post( $args['before_widget'] );
				if ( $title ) {
					echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
				}
			}
			$settings              = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
			$service_tracking_page = $settings->get_params( 'service_tracking_page' );

			if ( ! $service_tracking_page || ! is_page( $service_tracking_page ) ) {
				echo do_shortcode( '[vi_wot_form_track_order]' );
			}

			echo wp_kses_post( $args['after_widget'] );
		}

		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : 'Orders Tracking'; ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'woo-orders-tracking' ) ?></label>
            </p>
            <p>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                   value="<?php echo esc_attr( $title ); ?>"/>
            </p><?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance          = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );

			return $instance;
		}
	}
}