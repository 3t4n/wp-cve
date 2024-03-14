<?php

// Creating VI Random Post Widget
class VI_Random_Posts extends WP_Widget {

	function __construct() {

		// Set up the widget options.
		$widget_options = array(
			'classname'   => 'virp-random-widget',
			'description' => __( 'A highly customizable plugin to display your random posts.', 'virp' )
		);

		// Create the widget.
		parent::__construct(
			'virp-widget',                // $this->id_base
			__( 'VI Random Posts', 'virp' ), // $this->name
			$widget_options              // $this->widget_options
		);

	}
	// Output the widget
	function widget( $args, $instance ) {
		extract( $args );

		// Output $before_widget wrapper.
		echo $before_widget;

		// If both title and title url is not empty, display it.
		if ( ! empty( $instance['title_url'] ) && ! empty( $instance['title'] ) ) {
			echo $before_title . '<a href="' . esc_url( $instance['title_url'] ) . '" title="' . esc_attr( $instance['title'] ) . '">' . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . '</a>' . $after_title;

		// If the title not empty, display it.
		} elseif ( ! empty( $instance['title'] ) ) {
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;
		}

		// Get the random posts query.
		echo virp_get_random_posts( $instance );

		// Close the widget wrapper.
		echo $after_widget;
 }

	/**
	 * Updates the widget control options.
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']             = strip_tags( $new_instance['title'] );
		$instance['title_url']         = esc_url( $new_instance['title_url'] );

		$instance['limit']             = (int) $new_instance['limit'];
		$instance['post_type']         = esc_attr( $new_instance['post_type'] );
		$instance['orderby']     		= esc_attr($new_instance['orderby']); 
		$instance['cat']               = $new_instance['cat'];


		$instance['thumbnail']         = isset( $new_instance['thumbnail'] ) ? (bool) $new_instance['thumbnail'] : false;
		$instance['thumbnail_size']    = esc_attr( $new_instance['thumbnail_size'] );
		$instance['thumbnail_align']   = esc_attr( $new_instance['thumbnail_align'] );
		$instance['thumbnail_custom']  = isset( $new_instance['thumbnail_custom'] ) ? (bool) $new_instance['thumbnail_custom'] : false;
		$instance['thumbnail_width']   = (int) $new_instance['thumbnail_width'];
		$instance['thumbnail_height']  = (int) $new_instance['thumbnail_height'];

		$instance['excerpt']           = isset( $new_instance['excerpt'] ) ? (bool) $new_instance['excerpt'] : false;
		$instance['excerpt_length']    = (int) $new_instance['excerpt_length'];
		$instance['readmore']         = isset( $new_instance['readmore'] ) ? (bool) $new_instance['readmore'] : false;
		$instance['readmore_text']    = strip_tags( $new_instance['readmore_text'] );
		$instance['comment_count']    = isset( $new_instance['comment_count'] ) ? (bool) $new_instance['comment_count'] : false;
		$instance['author_name']      = isset( $new_instance['author_name'] ) ? (bool) $new_instance['author_name'] : false;
		$instance['show_category']    = isset( $new_instance['show_category'] ) ? (bool) $new_instance['show_category'] : false;
		$instance['show_tags']    		= isset( $new_instance['show_tags'] ) ? (bool) $new_instance['show_tags'] : false;
		$instance['date']             = isset( $new_instance['date'] ) ? (bool) $new_instance['date'] : false;
		$instance['format']     		= esc_attr($new_instance['format']);
		 
		$instance['before']           = stripslashes( $new_instance['before'] );
		$instance['after']            = stripslashes( $new_instance['after'] );

		return $instance;
	}

	function form( $instance ) {

		// Merge the user arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, virp_get_default_args() );

		// Extract the array.
		extract( $instance );

		// Loads the widget form.
		include( VIRP_INC . 'form.php' );

	}

}