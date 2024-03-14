<?php
/**
 * SVG icons: Boxicons https://boxicons.com/, (C) CC 4.0.
 * Using icons with square dimension (width = height)
 */

namespace ERocket\Widgets;

use WP_Widget_Text;
use WP_Widget;

class ContactInfo extends WP_Widget_Text {

	private $defaults;

	public function __construct() {
		$this->defaults = [
			'title'   => __( 'Contact Info', 'erocket' ),
			'text'    => '',
			'address' => '',
			'email'   => '',
			'phone'   => '',
			'support' => '',
		];

		$widget_ops  = [
			'classname' => 'eci',
		];
		$control_ops = [
			'width'  => 400,
			'height' => 350,
		];

		WP_Widget::__construct( 'eci', __( '[eRocket] Contact Info', 'erocket' ), $widget_ops, $control_ops );

		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
			add_action( 'wp_head', [ $this, 'output_style' ] );
		}
	}

	public function output_style() {
		?>
		<style>
		.eci-info {
			display: flex;
			align-items: flex-start;
			margin-bottom: 4px;
		}
		.eci-info svg {
			width: 1em;
			min-width: 1em;
			max-width: 1em;
			height: 1em;
			margin-right: 4px;
			margin-top: 4px;
		}
		.eci-info address {
			margin: 0;
		}
		.eci-profiles {
			flex-wrap: wrap;
			margin-top: 1.5em;
		}
		</style>
		<?php
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		$after_widget         = $args['after_widget'];
		$args['after_widget'] = '';

		parent::widget( $args, $instance );
		?>

		<?php if ( ! empty( $instance['address'] ) ) : ?>
			<div class="eci-info">
				<?php $this->output_svg( 'map' ); ?>
				<address><?= esc_html( $instance['address'] ); ?></address>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $instance['email'] ) ) : ?>
			<div class="eci-info">
				<?= block_core_social_link_get_icon( 'mail' ); ?>
				<a href="mailto:<?= esc_attr( $instance['email'] ); ?>"><?= esc_html( $instance['email'] ); ?></a>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $instance['phone'] ) ) : ?>
			<div class="eci-info">
				<?php $this->output_svg( 'phone' ); ?>
				<a href="tel:<?= esc_attr( preg_replace( '/[^\+0-9]/', '', $instance['phone'] ) ); ?>"><?= esc_html( $instance['phone'] ); ?></a>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $instance['support'] ) ) : ?>
			<div class="eci-info">
				<?php $this->output_svg( 'support' ); ?>
				<a href="<?= esc_attr( $instance['support'] ); ?>"><?= esc_html( $instance['support'] ); ?></a>
			</div>
		<?php endif; ?>

		<?php
		$services = array_intersect_key( $instance, block_core_social_link_services() );
		if ( ! empty( $services ) ) {
			$blocks = '<!-- wp:social-links --><ul class="wp-block-social-links eci-profiles">';
			foreach ( $services as $service => $url ) {
				$attributes = [
					'service' => $service,
					'url'     => $url,
				];
				$blocks    .= '<!-- wp:social-link ' . wp_json_encode( $attributes ) . ' /-->';
			}
			$blocks .= '</ul><!-- /wp:social-links -->';

			echo do_blocks( $blocks );
		}
		?>

		<?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = parent::update( $new_instance, $old_instance );

		$instance['address'] = sanitize_text_field( $new_instance['address'] );
		$instance['email']   = is_email( $new_instance['email'] ) ? $new_instance['email'] : '';
		$instance['phone']   = sanitize_text_field( $new_instance['phone'] );
		$instance['support'] = sanitize_text_field( $new_instance['support'] );

		$services = block_core_social_link_services();
		foreach ( $services as $key => $service ) {
			$instance[ $key ] = esc_url_raw( $new_instance[ $key ] );
		}

		return array_filter( $instance );
	}

	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		parent::form( $instance );
		?>
		<p>
			<label for="<?= $this->get_field_id( 'address' ); ?>"><?php esc_html_e( 'Address:', 'erocket' ); ?></label>
			<input class="widefat" id="<?= $this->get_field_id( 'address' ); ?>" name="<?= $this->get_field_name( 'address' ); ?>" type="text" value="<?= esc_attr( $instance['address'] ); ?>">
		</p>
		<p>
			<label for="<?= $this->get_field_id( 'email' ); ?>"><?php esc_html_e( 'Email:', 'erocket' ); ?></label>
			<input class="widefat" id="<?= $this->get_field_id( 'email' ); ?>" name="<?= $this->get_field_name( 'email' ); ?>" type="text" value="<?= esc_attr( $instance['email'] ); ?>">
		</p>
		<p>
			<label for="<?= $this->get_field_id( 'phone' ); ?>"><?php esc_html_e( 'Phone:', 'erocket' ); ?></label>
			<input class="widefat" id="<?= $this->get_field_id( 'phone' ); ?>" name="<?= $this->get_field_name( 'phone' ); ?>" type="text" value="<?= esc_attr( $instance['phone'] ); ?>">
		</p>
		<p>
			<label for="<?= $this->get_field_id( 'support' ); ?>"><?php esc_html_e( 'Support:', 'erocket' ); ?></label>
			<input class="widefat" id="<?= $this->get_field_id( 'support' ); ?>" name="<?= $this->get_field_name( 'support' ); ?>" type="text" value="<?= esc_attr( $instance['support'] ); ?>">
		</p>
		<?php $services = block_core_social_link_services(); ?>
		<?php foreach ( $services as $key => $service ) : ?>
			<p>
				<label for="<?= $this->get_field_id( $key ); ?>"><?= esc_html( $service['name'] ); ?>:</label>
				<input class="widefat" id="<?= $this->get_field_id( $key ); ?>" name="<?= $this->get_field_name( $key ); ?>" type="text" value="<?= esc_attr( $instance[ $key ] ?? '' ); ?>">
			</p>
		<?php endforeach; ?>
		<?php
	}

	private function output_svg( $key ) {
		echo file_get_contents( EROCKET_DIR . "/img/$key.svg" );
	}
}
