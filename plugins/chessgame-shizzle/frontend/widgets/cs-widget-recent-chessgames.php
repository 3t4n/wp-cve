<?php
/**
 * Widget API: WP_Widget_Recent_Chessgames class
 *
 * @since 1.0.3
 */

/**
 * Class used to implement a Recent Chessgames widget.
 *
 * @since 1.0.3
 *
 * @see WP_Widget
 */
class WP_Widget_Recent_Chessgames extends WP_Widget {

	/**
	 * Sets up a new Recent Chessgames widget instance.
	 *
	 * @since 1.0.3
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_recent_chessgames',
			'description' => esc_html__( 'Your site&#8217;s most recent chessgames.', 'chessgame-shizzle'  ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'recent-chessgames', esc_html__( 'Recent Chessgames', 'chessgame-shizzle'  ), $widget_ops );
		$this->alt_option_name = 'widget_recent_chessgames';
	}

	/**
	 * Outputs the content for the current Recent Chessgames widget instance.
	 *
	 * @since 1.0.3
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Chessgames widget instance.
	 */
	public function widget( $args, $instance ) {

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Recent Chessgames', 'chessgame-shizzle'  );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		$r = new WP_Query( array(
			'posts_per_page'         => $number,
			'no_found_rows'          => true,
			'post_status'            => 'publish',
			'post_type'              => 'cs_chessgame',
			'ignore_sticky_posts'    => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		) );

		if ($r->have_posts()) {
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>
			<ul>
			<?php
			while ( $r->have_posts() ) {
				$r->the_post(); ?>
				<li>
					<a href="<?php esc_attr( the_permalink() ); ?>"><?php esc_html( get_the_title() ) ? the_title() : esc_html_e( '(no title)', 'chessgame-shizzle'  ); ?></a>
					<?php if ( $show_date ) : ?>
						<span class="post-date"><?php echo get_the_date(); ?></span>
					<?php endif; ?>
				</li>
				<?php
			} ?>
			</ul>
			<?php echo $args['after_widget'];

			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();

		}
	}

	/**
	 * Handles updating the settings for the current Recent Chessgames widget instance.
	 *
	 * @since 1.0.3
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Chessgames widget.
	 *
	 * @since 1.0.3
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? $instance['title'] : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'chessgame-shizzle'  ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of games to show:', 'chessgame-shizzle'  ); ?></label>
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo (int) $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display publish date?', 'chessgame-shizzle'  ); ?></label></p>
		<?php
	}
}

function chessgame_shizzle_widget_recent_chessgames() {
	register_widget('WP_Widget_Recent_Chessgames');
}
add_action('widgets_init', 'chessgame_shizzle_widget_recent_chessgames' );
