<?php
/**
 * The team widget page.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 * @package    team-free
 * @subpackage team-free/includes
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Admin;

/**
 * Adds Foo_Widget widget.
 */
class WP_Team_Widget extends \WP_Widget {
	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'wpteam_widget',
			'description' => esc_html__( 'Create and display team', 'team-free' ),
		);
		parent::__construct( 'wpteam_widget', 'WP Team', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args widget args.
	 * @param array $instance widget value.
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget.
		extract( $args, EXTR_SKIP ); // phpcs:ignore
		$title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title'] );
		$team  = empty( $instance['team'] ) ? '' : $instance['team'];

		echo ( isset( $before_widget ) ? wp_kses_post( $before_widget ) : '' );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $before_title . $title . $after_title );
		}
		if ( ! empty( $team ) ) {
			$output = '[wpteam id="' . esc_attr( $team ) . '"]';
			echo do_shortcode( $output );
		}

		echo ( isset( $after_widget ) ? wp_kses_post( $after_widget ) : '' );
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options.
	 */
	public function form( $instance ) {
		// outputs the options form on admin.
		$title      = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$team       = ! empty( $instance['team'] ) ? esc_attr( $instance['team'] ) : '';
		$shortcodes = get_posts(
			array(
				'post_type'      => 'sptp_generator',
				'posts_per_page' => -1,
			)
		);
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'team-free' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'team' ) ); ?>"><?php esc_attr_e( 'Team:', 'team-free' ); ?></label>
			<br/>
			<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'team' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'team' ) ); ?>" type="text">
				<?php
				foreach ( $shortcodes as $shortcode ) :
					?>
				<option value="<?php echo esc_attr( $shortcode->ID ); ?>" <?php echo ( $shortcode->ID === $team ) ? 'selected' : ''; ?>><?php echo wp_kses_post( $shortcode->post_title ); ?></option>
					<?php
				endforeach;
				?>
			</select>
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved.
		$instance          = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['team']  = $new_instance['team'];
		return $instance;
	}

}
