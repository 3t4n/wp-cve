<?php

// WP_Testimonial class

final class Testimonials_Widget extends WP_Widget {

	function __construct () {

		parent::__construct( false, 'Testimonial Widget' );

	}

	function widget ( $args, $instance ) {

		extract( $args );

		// Get testimonials to display
		$query_args = array(

			'post_type' => 'testimonial',
			'numberposts' => 1

		);

		if( is_numeric( $instance['testimonial_id'] ) )
			$query_args['include'] = $instance['testimonial_id'];
		else {

			if( is_array( $instance['testimonial_random_category'] ) )
			$query_args['testimonial_category'] = implode( ',', array_keys( $instance['testimonial_random_category'] ) );

			$query_args['orderby'] = 'rand';
		}

		$testimonials = get_posts( $query_args );

		// Widget title
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		echo $before_widget;

		if ( strlen( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		if ( $testimonials ) {

			foreach( $testimonials as $testimonial ) {
				$testimonial = new WP_Testimonial( $testimonial->ID );
				$testimonial->word_limit = isset( $instance['testimonial_word_limit'] ) ? $instance['testimonial_word_limit'] : 0;
			}

			$testimonial->render(true);
		}

		echo $after_widget;
	}

	function update ( $new_instance, $old_instance ) {

		$instance = $old_instance;

		if( !empty( $new_instance['testimonial_id'] ) )
			$instance['testimonial_id'] = $new_instance['testimonial_id'];

		if( !empty( $new_instance['title'] ) )
			$instance['title'] = $new_instance['title'];

		$instance['testimonial_word_limit'] = isset( $new_instance['testimonial_word_limit'] ) ? $new_instance['testimonial_word_limit'] : 0;
		$instance['testimonial_random_category'] = $new_instance['testimonial_random_category'];


		return $instance;

	}

	function form ( $instance ) {

		$defaults = array(

			'title' => '',
			'testimonial_id' => 'random',
			'testimonial_word_limit' => NULL,
			'testimonial_random_category' => ''

		);

		$instance = wp_parse_args( (array)$instance, $defaults );

		?>

		<p>
			<label for="testimonials_title"><?php _e( 'Title', 'clean-testimonials' ); ?>:</label>
			<input type="text" id="testimonials_title" name="<?php echo $this->get_field_name( 'title' ) ?>" style="width: 100%;" value="<?php echo $instance['title'] ?>" />
		</p>

		<p>
			<label for="testimonial_id"><?php _e( 'Select a Testimonial to display', 'clean-testimonials' ); ?></label>
			<select class="testimonial_widget_select" name="<?php echo $this->get_field_name( 'testimonial_id' ); ?>" style="width:100%;">
				<option value="random"><?php _e( 'Random', 'clean-testimonials' ); ?></option>

				<?php if( $testimonials = get_posts( array( 'post_type' => 'testimonial', 'numberposts' => -1 ) ) ) :?>

				<?php foreach( $testimonials as $testimonial ): ?>
				<option value="<?php echo esc_attr( $testimonial->ID ); ?>"<?php echo ( $instance['testimonial_id'] == $testimonial->ID ? ' selected="selected"' : NULL ); ?>><?php echo $testimonial->post_title; ?></option>
				<?php endforeach; ?>

				<?php endif; ?>
			</select>
		</p>

		<p>
			<label for="testimonial_word_limit"><?php _e( 'Word limit (optional)', 'clean-testimonials' ); ?></label>
			<input type="Text" name="<?php echo $this->get_field_name( 'testimonial_word_limit' ); ?>" style="width:100%;" value="<?php echo $instance['testimonial_word_limit']; ?>" />
		</p>

		<div class="testimonial_random_category">

			<p><?php _e( 'If random, get from specific category (optional)', 'clean-testimonials' ); ?></p>

			<p>
				<?php if( $categories = get_terms( 'testimonial_category', array( 'hide_empty' => false ) ) ) foreach( $categories as $category ): $id = uniqid(); ?>
				<input id="project-category-<?php echo $category->slug . '-' . $id; ?>" class="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'testimonial_random_category' ); ?>[<?php echo $category->slug; ?>]"<?php echo ( ( is_array( $instance['testimonial_random_category'] ) && array_key_exists( $category->slug, $instance['testimonial_random_category'] ) ) ? ' checked="checked"' :  NULL ); ?>></input><label for="project-category-<?php echo $category->slug . '-' . $id; ?>"><?php echo $category->name; ?></label><br />
				<?php endforeach; ?>
			</p>

		</div>

		<br />

		<?php

	}

}

?>
