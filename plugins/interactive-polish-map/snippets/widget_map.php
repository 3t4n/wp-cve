<?php
class InteractivePolishMapWidget extends WP_Widget
{
	/** constructor */
	function __construct() {

		parent::__construct(
			__CLASS__,
			$name = __( 'Interactive Polish Map', 'interactive-polish-map' ),
			array(
				'description' => __( 'Widget is used to place interactive polish map.', 'interactive-polish-map' ),
				'classname' => 'interactive_polish_map',
			)
		);
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {

		/**
		 * cache id for wp_cache object
		 */
		$cache_id = 'InteractivePolishMapWidget'.$args['widget_id'];
		/**
		 * content
		 */
		$content = wp_cache_get( $cache_id, 'InteractivePolishMapWidget' );
		$content = false;
		if ( $content === false ) {
			global $ipm_data;
			extract( $args );
			$title   = apply_filters( 'widget_title', $instance['title'] );
			$content = $before_widget;
			$content .= $before_title.$title.$after_title;
			$content .= sprintf( '<div id="ipm_type_%d"><ul id="w" class="%s">', $instance['type'], $instance['menu'] );
			$i = 1;
			foreach ( $ipm_data['districts'] as $key => $value ) {
				$url = get_option( 'ipm_districts_'.$key, '#' );
				if ( empty( $url ) ) {
					$url = '#';
				}
				$content .= sprintf(
					'<li id="w%d"><a href="%s" title="%s">%s</a></li>',
					$i++,
					$url,
					$value,
					$value
				);
			}
			$content .= '</ul></div>';
			$content .= $after_widget;
		}
		wp_cache_set( $cache_id, $content, 'InteractivePolishMapWidget', 1800 );
		echo $content;
	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		foreach ( array( 'title', 'type', 'menu' ) as $key ) {
			$instance[ $key ] = strip_tags( $new_instance[ $key ] );
		}
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {

		global $ipm_data;
		/**
		 * title
		 */
		printf(
			'<p><label for="%s">%s <input class="widefat" id="%s" name="%s" type="text" value="%s" /></label></p>',
			$this->get_field_id( 'title' ),
			__( 'Title:' ),
			$this->get_field_id( 'title' ),
			$this->get_field_name( 'title' ),
			isset( $instance['title'] )? esc_attr( $instance['title'] ):''
		);
		/**
		 * type
		 */
		$current = 'standard';
		if ( isset( $instance['type'] ) && ! empty( $instance['type'] ) ) {
			$current = $instance['type'];
		}
		$select = '';
		foreach ( $ipm_data['type'] as $value => $data ) {
			if ( ! $data['widget'] ) {
				continue;
			}
			$select .= sprintf(
				'<option value="%s"%s>%s</option>',
				$value,
				($value == $current)? ' selected="selected"':'',
				$data['desc']
			);
		}
		printf(
			'<p><label for="%s">%s <select id="%s" name="%s">%s</select></label></p>',
			$this->get_field_id( 'type' ),
			__( 'Map width', 'interactive-polish-map' ),
			$this->get_field_id( 'type' ),
			$this->get_field_name( 'type' ),
			$select
		);
		/**
		 * menu
		 */
		$current = 'standard';
		if ( isset( $instance['menu'] ) && ! empty( $instance['menu'] ) ) {
			$current = $instance['menu'];
		}
		$select = '';
		foreach ( $ipm_data['menu'] as $value => $data ) {
			if ( ! $data['widget'] ) {
				continue;
			}
			$select .= sprintf(
				'<option value="%s"%s>%s</option>',
				$value,
				($value == $current)? ' selected="selected"':'',
				$data['desc']
			);
		}
		printf(
			'<p><label for="%s">%s <select id="%s" name="%s">%s</select></label></p>',
			$this->get_field_id( 'menu' ),
			__( 'Display list', 'interactive-polish-map' ),
			$this->get_field_id( 'menu' ),
			$this->get_field_name( 'menu' ),
			$select
		);
	}
}
add_action(
	'widgets_init',
	function() {
		register_widget( 'InteractivePolishMapWidget' );
	}
);

