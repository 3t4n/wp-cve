<?php
/**
 * FoodMenu Widget.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Widgets;

use WP_Widget;
use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * FoodMenu Widget.
 */
class FmWidget extends WP_Widget {

	/**
	 * Food Menu widget setup
	 */
	public function __construct() {
		$widget_ops = [
			'classname'   => 'widget_fmp',
			'description' => esc_html__( 'Display Food menu', 'tlp-food-menu' ),
		];

		parent::__construct( 'widget_fmp', esc_html__( 'Food Menu', 'tlp-food-menu' ), $widget_ops );
	}

	/**
	 * Display the widgets on the screen.
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$id = ( ! empty( $instance['id'] ) ? $instance['id'] : null );

		echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', ( isset( $instance['title'] ) ? esc_html( $instance['title'] ) : 'Food Menu' ) ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		if ( ! empty( $id ) ) {
			echo do_shortcode( '[foodmenu id="' . absint( $id ) . '" ]' );
		}

		echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function form( $instance ) {
		$scList   = Fns::get_shortCode_list();
		$defaults = [
			'title' => esc_html__( 'Food Menu', 'tlp-food-menu' ),
			'id'    => null,
		];

		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php
				esc_html_e( 'Title:', 'tlp-food-menu' );
				?>
			</label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:100%;"/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>">
				<?php
				esc_html_e( 'Select Food Menu', 'tlp-food-menu' );
				?>
			</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'id' ) ); ?>">
				<option value="">Select one</option>
				<?php
				if ( ! empty( $scList ) ) {
					foreach ( $scList as $scId => $sc ) {
						$selected = ( $scId == $instance['id'] ? 'selected' : null );
						echo '<option value="' . absint( $scId ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $sc ) . '</option>';
					}
				}
				?>
			</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = [];
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['id']    = ( ! empty( $new_instance['id'] ) ) ? (int) ( $new_instance['id'] ) : null;
		return $instance;
	}
}
