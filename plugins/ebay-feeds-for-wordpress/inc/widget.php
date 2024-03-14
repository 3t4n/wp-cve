<?php

// Check to see required Widget API functions are defined...

if ( !function_exists( 'register_sidebar_widget' ) || !function_exists( 'register_widget_control' ) )

  return; // ...and if not, exit gracefully from the script.



// This function prints the sidebar widget--the cool stuff!
class ebay_feeds_for_wordpress_Widget_class extends WP_Widget {

	public function __construct() {
		parent::__construct( 'ebay_feeds_for_wordpress_widget', 'WP eBay Product Feeds', array( 'description' => 'Widget for an eBay Feed' ) );
	}


	function widget( $args, $instance ) {



    // $args is an array of strings which help your widget
    // conform to the active theme: before_widget, before_title,
    // after_widget, and after_title are the array keys.

		extract( $args );
		extract( $args, EXTR_SKIP );

		$title = empty( $instance['widget_title'] ) ? '' : apply_filters( 'widget_title', $instance['widget_title'] );
		$text = empty( $instance['widget_text'] ) ? '' : $instance['widget_text'];

		if ( empty( $instance['widget_num'] ) && !is_numeric( $instance['widget_num'] ) ) {

			$num = 3;

		} else {

			$num = $instance['widget_num'];

		}

		$feed = empty( $instance['widget_feed'] ) ? get_option( 'ebay-feeds-for-wordpress-default' ) : $instance['widget_feed'];

		echo $before_widget;

		echo $before_title . $title . $after_title;

		echo $text;

		echo ebay_feeds_for_wordpress_notecho( $feed, $num );

		echo $after_widget;


	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['widget_title'] = strip_tags( $new_instance['widget_title'] );
		$instance['widget_text'] = strip_tags( $new_instance['widget_text'] );
		$instance['widget_num'] = strip_tags( $new_instance['widget_num'] );
		$instance['widget_feed'] = strip_tags( $new_instance['widget_feed'] );
		return $instance;
	}

  /**
   * admin control form
   */
  function form( $instance ) {
  	$default =  array( 'title' => __( 'eBay Items' ) );
  	$instance = wp_parse_args( (array) $instance, $default );

  	$title_id = $this->get_field_id( 'widget_title' );
  	$title_name = $this->get_field_name( 'widget_title' );
  	$text_id = $this->get_field_id( 'widget_text' );
  	$text_name = $this->get_field_name( 'widget_text' );
  	$num_id = $this->get_field_id( 'widget_num' );
  	$num_name = $this->get_field_name( 'widget_num' );
  	$feed_id = $this->get_field_id( 'widget_feed' );
  	$feed_name = $this->get_field_name( 'widget_feed' );

  	$title = "";
  	$text = "";
  	$num = "";
  	$feed = "";

  	if ( isset( $instance['widget_title'] ) ) { $title = $instance['widget_title']; }
  	if ( isset( $instance['widget_text'] ) ) { $text = $instance['widget_text']; }
  	if ( isset( $instance['widget_num'] ) ) { $num = $instance['widget_num']; }
  	if ( isset( $instance['widget_feed'] ) ) { $feed = $instance['widget_feed']; }

  	echo "\r\n".'<p><label for="'.$title_id.'">'.__( 'Title','ebay-feeds-for-wordpress'  ).': <input type="text" class="widefat" id="'.$title_id.'" name="'.$title_name.'" value="'.esc_attr( $title ).'" /><label></p>';
  	echo "\r\n".'<p><label for="'.$text_id.'">'.__( 'Text','ebay-feeds-for-wordpress' ).': <input type="text" class="widefat" id="'.$text_id.'" name="'.$text_name .'" value="'.esc_attr( $text ).'" /><label></p>';
  	echo "\r\n".'<p><label for="'.$num_id.'">'.__( 'Number of Items','ebay-feeds-for-wordpress'  ).': <input type="text" class="widefat" id="'.$num_id.'" name="'.$num_name .'" value="'.esc_attr( $num ).'" /><label></p>';
  	echo "\r\n".'<p><label for="'.$feed_id.'">'.__( 'eBay Feed','ebay-feeds-for-wordpress'  ).': <input type="text" class="widefat" id="'.$feed_id.'" name="'.$feed_name .'" value="'.esc_attr( $feed ).'" /><label></p>';
  }

}

/**
 * Register the WP eBay Product Feeds widget
 * @return void
 */
function ebay_feeds_for_wordpress_Widget() {
  // curl need to be installed
	register_widget( 'ebay_feeds_for_wordpress_Widget_class' );
} add_action( 'widgets_init', 'ebay_feeds_for_wordpress_Widget', 10 );
