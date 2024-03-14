<?php
/**
 * Widget API: WP_Widget_Chessgame_Search class
 *
 * @since 1.0.8
 */

/**
 * Class used to implement a search widget.
 *
 * @since 1.0.8
 *
 * @see WP_Widget
 */
class WP_Widget_Chessgame_Search extends WP_Widget {

	/**
	 * Sets up a new Search Chessgame widget instance.
	 *
	 * @since 1.0.8
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_search',
			'description' => esc_html__( 'Searchbox for chessgames.', 'chessgame-shizzle'  ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'search-chessgame', esc_html__( 'Search Chessgames', 'chessgame-shizzle'  ), $widget_ops );
		$this->alt_option_name = 'widget_search';
	}

	/**
	 * Outputs the content for the current Search Chessgame widget instance.
	 *
	 * @since 1.0.8
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Search widget instance.
	 */
	public function widget( $args, $instance ) {

		if ( isset( $instance['title'] ) ) {
			$title = esc_attr( $instance['title'] );
		} else {
			$title = esc_html__( 'Search Chessgames', 'chessgame-shizzle' );
		}

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		$unique_id = uniqid( 'search-form-' ); ?>
		<form role="search" method="get" class="search-form" action="<?php echo esc_attr( home_url( '/' ) ); ?>">
			<label for="<?php echo esc_attr( $unique_id ); ?>">
				<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'chessgame-shizzle' ); ?></span>
			</label>
			<input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field" placeholder="<?php echo esc_attr_e( 'Search...', 'chessgame-shizzle' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" required="required" />
			<input type="hidden" value="cs_chessgame" name="post_type" id="post_type" />
			<input type="submit" class="search-submit" value="<?php esc_attr_e('Search', 'chessgame-shizzle'); ?>" />
		</form><?php

		echo $args['after_widget'];

	}

	/**
	 * Handles updating the settings for the current Search Chessgame widget instance.
	 *
	 * @since 1.0.8
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}

	/**
	 * Outputs the settings form for the Search Chessgame widget.
	 *
	 * @since 1.0.8
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = esc_attr( $instance['title'] );
		} else {
			$title = esc_html__( 'Search Chessgames', 'chessgame-shizzle' );
		}
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'chessgame-shizzle' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		<?php
	}
}

function chessgame_shizzle_widget_search() {
	register_widget('WP_Widget_Chessgame_Search');
}
add_action('widgets_init', 'chessgame_shizzle_widget_search' );
