<?php

// Checking whether Class already exist or not.
if ( ! class_exists('SEO_Breadcrumbs_Widget' ) ) {

// Creating new class by extending WP_Widget class.
class SEO_Breadcrumbs_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'seo_breadcrumbs_widget',
			esc_html__( 'SEO Breadcrumbs', 'text_domain' ),array( 'description' => esc_html__( 'Display breadcrumbs with seo schema markup included.', 'text_domain' )));
	}

public function widget( $args, $instance ) {

echo $args['before_widget'];
		
if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

if (function_exists('seo_breadcrumbs'))
 {
      seo_breadcrumbs();
}

echo $args['after_widget'];

}

public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'You are here : ', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title : ', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

}

}

function register_seo_breadcrumbs_widget() {
    register_widget( 'SEO_Breadcrumbs_Widget' );

}

// Adding  this plugin widget to Widgets menu.
add_action( 'widgets_init', 'register_seo_breadcrumbs_widget' );

?>