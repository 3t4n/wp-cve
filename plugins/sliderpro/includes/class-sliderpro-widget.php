<?php
/**
 * Slider Pro widget
 * 
 * @since 4.0.0
 */
class BQW_SliderPro_Widget extends WP_Widget {
	
	/**
	 * Initialize the widget
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
		
		$widget_opts = array(
			'classname' => 'bqw-sliderpro-widget',
			'description' => 'Display a Slider Pro instance in the widgets area.'
		);
		
		parent::__construct( 'bqw-sliderpro-widget', 'Slider Pro', $widget_opts );
	}
	
	/**
	 * Create the admin interface of the widget.
	 *
	 * Receives the title of the widget and the id of the
	 * selected slider. Then it gets loads all slider
	 * id's and names from the database and displays them in
	 * the list of sliders to chose from.
	 *
	 * @since 4.0.0
	 * 
	 * @param  array $instance The slider id and widget title
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( ( array )$instance, array( 'slider_id' => '' ) );
		
		$slider_id = strip_tags( $instance['slider_id'] );
		$title = isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : '';
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'slider_pro_sliders';
		$sliders = $wpdb->get_results( "SELECT id, name FROM $table_name", ARRAY_A );
		
		echo '<p>';
		echo '<label for="' . esc_attr( $this->get_field_name( 'title' ) ) . '">Title: </label>';
		echo '<input type="text" value="' . esc_attr( $title ) . '" name="' . esc_attr( $this->get_field_name( 'title' ) ) . '" id="' . esc_attr( $this->get_field_name( 'title' ) ) . '" class="widefat">';
		echo '</p>';
		
		echo '<p>';
		echo '<label for="' . esc_attr( $this->get_field_name( 'slider_id' ) ) . '">Select the slider: </label>';
		echo '<select name="' . esc_attr( $this->get_field_name( 'slider_id' ) ) . '" id="' . esc_attr( $this->get_field_name( 'slider_id' ) ) . '" class="widefat">';
			foreach ( $sliders as $slider ) {
				$selected = $slider_id == $slider['id'] ? 'selected="selected"' : "";
				echo "<option value=". esc_attr( $slider['id'] ) ." $selected>" . esc_html( stripslashes( $slider['name'] ) ) . ' (' . intval( $slider['id'] ) . ')' . "</option>";
			}
		echo '</select>';
		echo '</p>';
	}
	
	/**
	 * Updates the selected slider.
	 *
	 * @since 4.0.0
	 * 
	 * @param  array $new_instance The new slider instance.
	 * @param  array $old_instance The old slider instance.
	 * @return array               The new slider instance.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;		
		$instance['slider_id'] = strip_tags( $new_instance['slider_id'] );
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
	}
	
	/**
	 * Create the public view.
	 *
	 * @since 4.0.0
	 * 
	 * @param  array $args     Widget data.
	 * @param  array $instance Slider instance id and widget title
	 */
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		
		echo wp_kses_post( $before_widget );
		
		if ( $title ) {
			echo wp_kses_post( $before_title ) . esc_html( $title ) . wp_kses_post( $after_title );
		}

		echo do_shortcode( '[sliderpro id="' . intval( $instance['slider_id'] ) . '"]' );
		echo wp_kses_post( $after_widget );
	}
}

/**
 * Register the widget
 *
 * @since 4.0.0
 */
function bqw_sp_register_widget() {
	register_widget( 'BQW_SliderPro_Widget' );
}