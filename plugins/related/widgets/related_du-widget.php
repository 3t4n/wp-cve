<?php

class Related_du_Widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'classname' => 'related_du_widget', 'description' => esc_html__('Displays Related Posts (Doubled Up).', 'related') );
		parent::__construct('related_du_widget', esc_html__('Related Posts (Doubled Up)', 'related'), $widget_ops);
		$this->alt_option_name = 'related_du_widget';

		add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
	}

	public function widget( $args, $instance ) {

		$title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Related Posts', 'related') : $instance['title'], $instance, $this->id_base);

		if ( is_singular() ) {
			global $related_du;
			$related_str = $related_du->show( get_the_ID() );

			if ( ! empty( $related_str ) ) {
				echo $args['before_widget'];
				if ( $title ) {
					echo $args['before_title'] . $title . $args['after_title'];
				}

				echo $related_str;

				echo $args['after_widget'];
			}
		}

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = wp_strip_all_tags($new_instance['title']);

		return $instance;

	}

	public function flush_widget_cache() {
		wp_cache_delete('related_du_widget', 'widget');
	}

	public function form( $instance ) {

		$default_value = array( 'title' => esc_html__('Related Posts', 'related') );
		$instance      =  wp_parse_args( (array) $instance, $default_value );
		$title = isset($instance['title']) ? esc_attr($instance['title']) : ''; ?>

		<p><label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title', 'related'); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p><?php

	}
}


function related_du_widget() {
	register_widget('Related_du_Widget');
}
add_action('widgets_init', 'related_du_widget' );
