<?php
/*
 * widget.php
 * Zeno Font Resizer Widget
 */

if ( function_exists('register_sidebar') && class_exists('WP_Widget') ) {
	class Zeno_FR_Widget extends WP_Widget {

		/* Constructor */
		public function __construct() {
			$widget_ops = array(
				'classname'   => 'Zeno_FR_Widget',
				'description' => esc_html__( 'Displays options to change the font size.', 'zeno-font-resizer' ),
				);
			parent::__construct('Zeno_FR_Widget', 'Zeno Font Resizer', $widget_ops);
			$this->alt_option_name = 'Zeno_FR_Widget';
		}

		/** @see WP_Widget::widget */
		public function widget( $args, $instance ) {
			$default_value = array(
					'title' => esc_html__('Font Resizer', 'zeno-font-resizer'),
				);
			$instance = wp_parse_args( (array) $instance, $default_value );
			$title    = $instance['title'];

			echo $args['before_widget'];

			if ($title !== false) {
				echo $args['before_title'] . apply_filters( 'widget_title', esc_attr( $title ) ) . $args['after_title'];
			}

			// The real content:
			zeno_font_resizer_place();

			echo $args['after_widget'];
		}

		/** @see WP_Widget::update */
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = wp_strip_all_tags($new_instance['title']);
			return $instance;
		}

		/** @see WP_Widget::form */
		public function form( $instance ) {

			$default_value = array(
					'title' => esc_html__('Font Resizer', 'zeno-font-resizer'),
				);
			$instance = wp_parse_args( (array) $instance, $default_value );
			$title    = $instance['title'];
			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>" /><?php esc_html_e('Title:', 'zeno-font-resizer'); ?></label><br />
				<input type="text" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" value="<?php echo esc_attr( $title ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" />
			</p>

			<?php
		}
	}

	function zeno_font_resizer_widget() {
		register_widget('Zeno_FR_Widget');
	}
	add_action('widgets_init', 'zeno_font_resizer_widget' );
}


