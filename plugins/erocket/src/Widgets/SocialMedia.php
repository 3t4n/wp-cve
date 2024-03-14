<?php
namespace ERocket\Widgets;

use WP_Widget;

class SocialMedia extends WP_Widget {
	/**
	 * Default widget options.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Widget setup.
	 */
	public function __construct() {
		$this->defaults = [
			'title' => esc_html__( 'Social Media', 'erocket' ),
		];
		parent::__construct( 'esm', esc_html__( '[eRocket] Social Media', 'erocket' ), [
			'description' => esc_html__( '[eRocket] Social Media', 'erocket' ),
		] );
	}

	/**
	 * How to display the widget on the screen.
	 *
	 * @param array $args     Widget parameters.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		echo $args['before_widget'];
		if ( $instance['title'] ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		$services = array_intersect_key( $instance, block_core_social_link_services() );
		if ( ! empty( $services ) ) {
			$blocks = '<!-- wp:social-links --><ul class="wp-block-social-links esm-profiles">';
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

		echo $args['after_widget'];
	}

	/**
	 * Update the widget settings.
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $old_instance Old widget instance.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = parent::update( $new_instance, $old_instance );
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		$services = block_core_social_link_services();
		foreach ( $services as $key => $service ) {
			$instance[ $key ] = esc_url_raw( $new_instance[ $key ] );
		}

		return array_filter( $instance );
	}

	/**
	 * Widget form.
	 *
	 * @param array $instance Widget instance.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		?>
		<p>
			<label for="<?= $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'erocket' ); ?></label>
			<input class="widefat" id="<?= $this->get_field_id( 'title' ); ?>" name="<?= $this->get_field_name( 'title' ); ?>" type="text" value="<?= esc_attr( $instance['title'] ); ?>">
		</p>
		<?php $services = block_core_social_link_services(); ?>
		<?php

		foreach ( $services as $key => $service ) :
			?>
			<p>
				<label for="<?= $this->get_field_id( $key ); ?>"><?= esc_html( $service['name'] ); ?>:</label>
				<input class="widefat" id="<?= $this->get_field_id( $key ); ?>" name="<?= $this->get_field_name( $key ); ?>" type="text" value="<?= esc_attr( $instance[ $key ] ?? '' ); ?>">
			</p>
		<?php endforeach; ?>
		<?php
	}
}

