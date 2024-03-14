<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * File for widget functionality
 * WPInventory supports template overrides.
 * @author WP Inventory Manager
 *
 */
class WPInventory_Categories_Widget extends WP_Widget {
	function __construct() {
		parent::__construct( 'WPInventory_Categories_Widget', 'WP Inventory Categories', [ 'description' => 'List Inventory categories, with link(s) to view inventory for each category' ] );
	}

	function widget( $args, $instance ) {
		echo wpinventory_category_widget_shortcode( $args, $instance );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		foreach ( $new_instance as $k => $v ) {
			$instance[ $k ] = $v;
		}

		return $instance;
	}

	function form( $instance ) {
		$default = [
			'title'          => WPIMCore::__( 'Inventory Categories' ),
			'page_id'        => '',
			'sort_order'     => '',
			'display_as'     => 'list',
			'include_counts' => '0'
		];

		$instance = wp_parse_args( (array) $instance, $default );

		$display_as_select = WPIMCore::dropdown_array( $this->get_field_name( 'display_as' ), $instance['display_as'], [
			'list'   => WPIMCore::__( 'List' ),
			'select' => WPIMCore::__( 'Dropdown' )
		] );
		$sort_order_select = WPIMCore::dropdown_array( $this->get_field_name( 'sort_order' ), $instance['sort_order'], [
			'sort_order'    => WPIMCore::__( 'Sort Order' ),
			'category_name' => WPIMCore::__( 'Category Name' )
		] );

		echo '<p><label for="' . $this->get_field_name( 'title' ) . '">' . WPIMCore::__( 'Widget Title' ) . '</label> <input type="text" class="widefat" name="' . $this->get_field_name( 'title' ) . '" value="' . esc_attr($instance['title']) . '" /></p>';
		echo '<p><label for="' . $this->get_field_name( 'page_id' ) . '">' . WPIMCore::__( 'Links to Page' ) . '</label> ' . wp_dropdown_pages( 'echo=0&name=' . $this->get_field_name( 'page_id' ) . '&selected=' . $instance['page_id'] . '&show_option_none=' . WPIMCore::__( 'Select...' ) ) . '</p>';
		echo '<p><label for="' . $this->get_field_name( 'display_as' ) . '">' . WPIMCore::__( 'Display As' ) . '</label> ' . $display_as_select . '</p>';
		echo '<p><label for="' . $this->get_field_name( 'sort_order' ) . '">' . WPIMCore::__( 'Sort Order' ) . '</label> ' . $sort_order_select . '</p>';
	}
}

class WPInventory_Latest_Items_Widget extends WP_Widget {
	function __construct() {
		parent::__construct( 'WPInventory_Latest_Items_Widget', 'WP Inventory Latest Items', [ 'description' => 'List the latest items added to inventory.' ] );
	}

	function widget( $args, $instance ) {
		global $wpim_widget_page_id;
		$wpim_widget_page_id = ( ! empty( $instance['page_id'] ) ) ? $instance['page_id'] : NULL;

		if ( ! $wpim_widget_page_id ) {
			echo '<!-- Page not set in widget.  Defaulting to current page / post -->';
			$wpim_widget_page_id = get_queried_object_id();
		}

		do_action( 'wpim_before_latest_items' );

		echo wp_kses( $args['before_widget'], 'post' );
		if ( $instance['title'] ) {
			echo wp_kses( $args['before_title'], 'post' ) . esc_attr( $instance['title'] ) . wp_kses( $args['after_title'], 'post' );
		}

		$number = (int) $instance['number'];
		$number = max( 1, min( 10, $number ) );

		$loop_args = [
			'page_size' => $number,
			'order'     => 'inventory_date_added DESC'
		];

		$loop_args = apply_filters( 'wpim_latest_items_filter_args', $loop_args );

		if ( $instance['category_id'] ) {
			$loop_args['category_id'] = $instance['category_id'];
		}

		$custom_loop = new WPIMLoop();
		$custom_loop->set_single( TRUE );
		$custom_loop->load_items( $loop_args );

		global $WPIMLoop;
		$old_loop = $WPIMLoop;
		wpinventory_set_loop( $custom_loop );

		wpinventory_get_template_part( 'widget-latest-items-loop' );

		$WPIMLoop = $old_loop;
		do_action( 'wpim_after_latest_items' );
		echo wp_kses( $args['after_widget'], 'post' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		foreach ( $new_instance as $k => $v ) {
			$instance[ $k ] = $v;
		}

		return $instance;
	}

	function form( $instance ) {
		$default  = [
			'title'       => WPIMCore::__( 'Latest Items' ),
			'page_id'     => '',
			'category_id' => '',
			'number'      => '4'
		];
		$instance = wp_parse_args( (array) $instance, $default );

		$WPIMCategories = new WPIMCategory();
		$categories     = $WPIMCategories->get_all( [ 'order' => 'sort_order' ] );

		$categories_array = [ '' => WPIMCore::__( 'Show All' ) ];
		foreach ( $categories AS $cat ) {
			$categories_array[ $cat->category_id ] = $cat->category_name;
		}

		$category_select = WPIMCore::dropdown_array( $this->get_field_name( 'category_id' ), $instance['category_id'], $categories_array );

		echo '<p><label for="' . $this->get_field_name( 'title' ) . '">' . WPIMCore::__( 'Widget Title' ) . '</label> <input type="text" class="widefat" name="' . $this->get_field_name( 'title' ) . '" value="' . esc_attr($instance['title']) . '" /></p>';
		echo '<p><label for="' . $this->get_field_name( 'number' ) . '">' . WPIMCore::__( 'Number of Items' ) . '</label> <input type="text" class="small-text" name="' . $this->get_field_name( 'number' ) . '" value="' . $instance['number'] . '" /></p>';
		echo '<p><label for="' . $this->get_field_name( 'page_id' ) . '">' . WPIMCore::__( 'Links to Page' ) . '</label> ' . wp_dropdown_pages( 'echo=0&name=' . $this->get_field_name( 'page_id' ) . '&selected=' . $instance['page_id'] . '&show_option_none=' . WPIMCore::__( 'Select...' ) ) . '</p>';
		echo '<p><label for="' . $this->get_field_name( 'category_id' ) . '">' . WPIMCore::__( 'Category' ) . '</label> ' . $category_select . '</p>';
	}
}
