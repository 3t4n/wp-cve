<?php

// The Widget
Class WPC_New_Stuff_Widget extends WP_Widget {
	function __construct(){
		parent::__construct(
			// Base ID
			'WPC_New_Stuff_Widget',
			__('WP Courses Widget', 'wp-courses'),
			// Widget description
			array( 'description' => __('Displays a list of specific courses, lessons or teachers', 'wp-courses') )
		);
	}
	public function widget($args, $instance){

			$title = apply_filters('widget_title', $instance['title']);

			echo $args['before_widget'];

			if(!empty($title)){
				echo $args['before_title'] . esc_textarea( $title ) . $args['after_title'];
			}

			$query_args = array(
				'posts_per_page'	=> $instance['posts_per_page'],
				'post_type' 		=> $instance['post_type'],
				'orderby'			=> $instance['orderby'],
				'order'				=> $instance['order'],
				'post_status'		=> 'publish',
			);


			$query = new WP_Query($query_args);
			echo '<ul class="wpc-widget-ul">';

			if($query->have_posts()){

				$logged_in = is_user_logged_in();
				$user_id = get_current_user_id();
				$viewed_tracking = wpc_get_tracked_lessons_by_user($user_id, 0);
				$completed_tracking = wpc_get_tracked_lessons_by_user($user_id, 1);

				while($query->have_posts()){

					$query->the_post();

					$class = '';
					$icon = '';

					if($instance['post_type'] == 'lesson'){
						$pid = get_the_ID();

						if( wpc_has_done( $pid, $viewed_tracking ) ) {
							$icon = '<i class="fa-regular fa-square"></i>';
						}

						if( wpc_has_done( $pid, $completed_tracking) ) {
							$icon = '<i class="fa-regular fa-square-check"></i>';
						}
					}

					$allowed = array(
						'i' => array(
							'class' => array()
						)
					);

					echo '<li class="wpc-widget-li"><a href="'. esc_url(get_the_permalink()) . '" class="' . esc_attr($class) . '">' . wp_kses($icon, $allowed) . ' ' . esc_html(get_the_title()) . '</a></li>';
				}
			}
			echo '</ul>';
			wp_reset_postdata();
			echo $args['after_widget'];
	
	}
	// Widget Backend
	public function form( $instance ) {

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'wpb_widget_domain' );
		}

		if( isset( $instance['posts_per_page'])) {
			$posts_per_page = $instance['posts_per_page'];
		} else {
			$posts_per_page = 10;
		}

		if( isset( $instance['post_type'])) {
			$post_type = $instance['post_type'];
		} else {
			$post_type = 'course';
		}

		if(isset($instance['order'])){
			$order = $instance['order'];
		} else {
			$order = 'ASC';
		}

		if(isset($instance['orderby'])){
			$orderby = $instance['orderby'];
		} else {
			$orderby = 'none';
		}

		if(isset($instance['course_id'])){
			$course_id = $instance['course_id'];
		} else {
			$course_id = 'none';
		}

		// Widget admin form
		?>

		<p>
		<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'wp-courses' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		<label for="<?php echo esc_attr($this->get_field_id( 'posts_per_page' )); ?>"><?php esc_html_e( 'Number of Lessons, Courses or Teachers to Show:', 'wp-courses' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'posts_per_page' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'posts_per_page' )); ?>" type="number" value="<?php echo esc_attr( $posts_per_page ); ?>" />

		<?php 

			$style = ($post_type != 'lesson') ? 'display: none;' : '';

		?>

		<label for="<?php echo esc_attr($this->get_field_id( 'post_type' )); ?>"><?php esc_html_e( 'Post Type:', 'wp-courses' ); ?></label>
		<select class="widefat wpc-widget-post-type-select" id="<?php echo esc_attr($this->get_field_id( 'post_type' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'post_type' )); ?>">
			<option value="course" <?php selected(esc_attr($post_type), 'course'); ?>><?php esc_html_e('Course', 'wp-courses'); ?></option>
			<option value="lesson" <?php selected(esc_attr($post_type), 'lesson'); ?>><?php esc_html_e('Lesson', 'wp-courses'); ?></option>
			<option value="teacher" <?php selected(esc_attr($post_type), 'teacher'); ?>><?php esc_html_e('Teacher', 'wp-courses'); ?></option>
		</select>

		<label for="<?php echo esc_attr($this->get_field_id( 'order' )); ?>"><?php esc_html_e( 'Order:', 'wp-courses' ); ?></label>
		<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'order' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'order' )); ?>">
			<option value="ASC" <?php selected(esc_attr($order), 'ASC'); ?>><?php esc_html_e('Ascending', 'wp-courses'); ?></option>
			<option value="DESC" <?php selected(esc_attr($order), 'DESC'); ?>><?php esc_html_e('Descending', 'wp-courses'); ?></option>
		</select>

		<label for="<?php echo esc_attr($this->get_field_id( 'orderby' )); ?>"><?php esc_html_e( 'Order By:', 'wp-courses' ); ?></label>
		<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'orderby' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'orderby' )); ?>">
			<option value="none" <?php selected(esc_attr($orderby), 'none'); ?>><?php esc_html_e('None', 'wp-courses'); ?></option>
			<option value="author" <?php selected(esc_attr($orderby), 'author'); ?>><?php esc_html_e('Author', 'wp-courses'); ?></option>
			<option value="date" <?php selected(esc_attr($orderby), 'date'); ?>><?php esc_html_e('Date', 'wp-courses'); ?></option>
			<option value="ID" <?php selected(esc_attr($orderby), 'ID'); ?>><?php esc_html_e('ID', 'wp-courses'); ?></option>
			<option value="menu_order" <?php selected(esc_attr($orderby), 'menu_order'); ?>><?php esc_html_e('Menu Order', 'wp-courses'); ?></option>
			<option value="name" <?php selected(esc_attr($orderby), 'name'); ?>><?php esc_html_e('Name', 'wp-courses'); ?></option>
			<option value="rand" <?php selected(esc_attr($orderby), 'rand'); ?>><?php esc_html_e('Random', 'wp-courses'); ?></option>
			<option value="title" <?php selected(esc_attr($orderby), 'title'); ?>><?php esc_html_e('Title', 'wp-courses'); ?></option>
		</select>

		</p>
	<?php
	}
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	$instance['posts_per_page'] = ( ! empty( $new_instance['posts_per_page'] ) ) ? strip_tags( $new_instance['posts_per_page'] ) : '';
	$instance['post_type'] = ( ! empty( $new_instance['post_type'] ) ) ? strip_tags( $new_instance['post_type'] ) : '';
	$instance['order'] = ( ! empty( $new_instance['order'] ) ) ? strip_tags( $new_instance['order'] ) : '';
	$instance['orderby'] = ( ! empty( $new_instance['orderby'] ) ) ? strip_tags( $new_instance['orderby'] ) : '';
	$instance['course_id'] = ( ! empty( $new_instance['course_id'] ) ) ? strip_tags( $new_instance['course_id'] ) : '';
	return $instance;
	}
}
function wpc_new_widget() {
    register_widget( 'WPC_New_Stuff_Widget' );
}
add_action( 'widgets_init', 'wpc_new_widget' );
?>