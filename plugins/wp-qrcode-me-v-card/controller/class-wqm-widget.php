<?php
/**
 * The plugin widget handler class.
 *
 * Register and work with QR code widget
 *
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WQM_Widget' ) ) {
	class WQM_Widget extends WP_Widget {

		/**
		 * Widget system name
		 */
		const WIDGET_SYSTEM_NAME = 'widget_' . WQM_Common::PLUGIN_SYSTEM_NAME;

		/**
		 * WQM_Widget constructor. Register QR code widget
		 */
		public function __construct() {
			$widget_ops  = array(
				'classname'   => self::WIDGET_SYSTEM_NAME,
				'description' => __( 'Load QR code MeCard/vCard as widget', 'wp-qrcode-me-v-card' )
			);
			$control_ops = array( 'id_base' => self::WIDGET_SYSTEM_NAME );

			parent::__construct( self::WIDGET_SYSTEM_NAME, __( 'QR code MeCard/vCard generator Widget', 'wp-qrcode-me-v-card' ), $widget_ops, $control_ops );
		}

		/**
		 * Widget register entry point
		 */
		public static function load_widget() {
			register_widget( 'WQM_Widget' );
		}

		/**
		 * Widget frontend output
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];

			if ( ! empty( $instance['wqm_title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['wqm_title'] ) . $args['after_title'];
			}

			if ( ! empty( $instance['wqm_card_id'] ) ) {
				$qrcode = get_the_post_thumbnail_url( $instance['wqm_card_id'], 'full' );

				echo WQM_Common::render( 'widget.php', array(
					'qrcode'  => $qrcode,
					'title'   => $instance['wqm_title'],
					'as_link' => $instance['wqm_as_link'],
					'code_id' => $instance['wqm_card_id']
				) );
			}

			echo $args['after_widget'];
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance Previously saved values from database.
		 *
		 * @return string
		 * @see WP_Widget::form()
		 *
		 */
		public function form( $instance ) {
			$data = WQM_Common::render( 'widget-config.php', array(
				'instance' => $instance,
				'widget'   => $this,
				'cards'    => WQM_Cards::get_all_cards()
			) );
			echo $data;

			return $data;
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 * @see WP_Widget::update()
		 *
		 */
		public function update( $new_instance, $old_instance ): array {
			$instance                = array();
			$instance['wqm_title']   = ! empty( $new_instance['wqm_title'] ) ? sanitize_text_field( $new_instance['wqm_title'] ) : '';
			$instance['wqm_card_id'] = ! empty( $new_instance['wqm_card_id'] ) ? WQM_Common::clear_digits( $new_instance['wqm_card_id'] ) : 0;
			$instance['wqm_as_link'] = ! empty( $new_instance['wqm_as_link'] ) &&
			                           in_array( $new_instance['wqm_as_link'], array( 'none', 'img', 'vcf' ) ) ?
				$new_instance['wqm_as_link'] : 'none';

			return $instance;
		}
	}
}