<?php
/**
 * The Banner Widget class.
 *
 * @package    Rock_Convert\Inc\Frontend\Widget
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Frontend\Widget;

/**
 * Banner Widget Class
 */
class Banner extends \WP_Widget {

	/**
	 * Construct
	 */
	public function __construct() {
		parent::__construct(
			'rock_cta_widget',
			esc_html__( 'Banner customizável | Rock Convert', 'rock-convert' ),
			array( 'description' => __( 'Adiciona um CTA na sidebar', 'rock-convert' ) )
		);
	}

	/**
	 * Build widget banner
	 *
	 * @param array      $args Arguments to customize widget.
	 * @param \WP_Widget $instance Instance of default widget class.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$title      = apply_filters( 'widget_title', $instance['title'] );
		$link       = esc_url_raw( $instance['link'] );
		$bg_color   = ! empty( $instance['bg_color'] ) ? $instance['bg_color'] : '#333333';
		$link_color = ! empty( $instance['link_color'] ) ? $instance['link_color'] : '#ffffff';
		echo $args['before_widget'];// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		<style>
			.rock-convert-widget-cta a span::before, .rock-convert-widget-cta a span::after {
				background: <?php echo esc_attr( $link_color ); ?>;
			}
		</style>
		<div class="rock-convert-widget-cta" style="background-color:<?php echo esc_attr( $bg_color ); ?>">
			<a href="<?php echo esc_url( $link ); ?>" style="color:<?php echo esc_attr( $link_color ); ?>">
				<span style="color: <?php echo esc_attr( $link_color ); ?>">
					<?php echo esc_html( $title ); ?>
				</span>
			</a>
		</div>
		<?php
		echo $args['after_widget'];// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Widget form
	 *
	 * @param \WP_Widget $instance Instance of default widget class.
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			$instance,
			array(
				'title'      => __( 'Conheça nossos produtos', 'rock-convert' ),
				'link'       => '',
				'bg_color'   => '#333333',
				'link_color' => '#ffffff',
			)
		);

		$title      = $instance['title'];
		$link       = $instance['link'];
		$bg_color   = $instance['bg_color'];
		$link_color = $instance['link_color'];
		?>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>">
				<?php echo esc_html_e( 'Título', 'rock-convert' ); ?></label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>"
				type="text" value="<?php echo esc_html( $title ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_url( $this->get_field_id( 'link' ) ); ?>">
				<?php esc_html_e( 'Link do CTA', 'rock-convert' ); ?></label>
			<input class="widefat" id="<?php echo esc_url( $this->get_field_id( 'link' ) ); ?>"
				name="<?php echo esc_url( $this->get_field_name( 'link' ) ); ?>"
				type="text" value="<?php echo esc_url( $link ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'bg_color' ) ); ?>">
				<?php esc_html_e( 'Cor do fundo', 'rock-convert' ); ?>
			</label>
			<br>
			<input type="text" name="<?php echo esc_html( $this->get_field_name( 'bg_color' ) ); ?>" class="color-picker"
				id="<?php echo esc_html( $this->get_field_id( 'bg_color' ) ); ?>" value="<?php echo esc_html( $bg_color ); ?>"
				data-default-color="#333333"/>
		</p>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'link_color' ) ); ?>">
				<?php esc_html_e( 'Cor do texto', 'rock-convert' ); ?>
			</label>
			<br>
			<input type="text" name="<?php echo esc_html( $this->get_field_name( 'link_color' ) ); ?>" class="color-picker"
				id="<?php echo esc_html( $this->get_field_id( 'link_color' ) ); ?>" value="<?php echo esc_html( $link_color ); ?>"
				data-default-color="#fff"/>
		</p>
		<?php
	}

	/**
	 * Update data.
	 *
	 * @param \WP_Widget $new_instance A new Widget instance of WordPress default class.
	 * @param \WP_Widget $old_instance An old Widget instance of WordPress default class.
	 * @return \WP_Widget
	 */
	public function update( $new_instance, $old_instance ) {
		$instance               = array();
		$instance['link']       = ! empty( $new_instance['link'] ) ? sanitize_text_field( $new_instance['link'] ) : '';
		$instance['title']      = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['bg_color']   = ! empty( $new_instance['bg_color'] ) ? sanitize_hex_color( $new_instance['bg_color'] ) : '';
		$instance['link_color'] = ! empty( $new_instance['link_color'] ) ? sanitize_hex_color( $new_instance['link_color'] ) : '';

		return $instance;
	}
}
