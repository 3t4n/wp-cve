<?php
/**
 * Widget API: WP_Widget_Featured_Chessgame class
 *
 * @since 1.0.4
 */

/**
 * Class used to implement a Featured Chessgame widget.
 *
 * @since 1.0.4
 *
 * @see WP_Widget
 */
class WP_Widget_Featured_Chessgame extends WP_Widget {

	/**
	 * Sets up a new Featured Chessgame widget instance.
	 *
	 * @since 1.0.4
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_featured_chessgame',
			'description' => esc_html__( 'Your site&#8217;s featured chessgame.', 'chessgame-shizzle'  ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'featured-chessgame', esc_html__( 'Featured Chessgame', 'chessgame-shizzle'  ), $widget_ops );
		$this->alt_option_name = 'widget_featured_chessgame';
	}

	/**
	 * Outputs the content for the current Featured Chessgame widget instance.
	 *
	 * @since 1.0.4
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Featured Chessgame widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Featured Chessgame', 'chessgame-shizzle'  );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$post_id = ( ! empty( $instance['post_id'] ) ) ? absint( $instance['post_id'] ) : 0;

		$r = new WP_Query( array(
			'posts_per_page'         => 1,
			'p'                      => (int) $post_id,
			'no_found_rows'          => true,
			'post_status'            => 'publish',
			'post_type'              => 'cs_chessgame',
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		) );

		if ($r->have_posts()) {
			echo $args['before_widget'];

			while ( $r->have_posts() ) {

				$r->the_post();

				if ( $title ) {
					$permalink = get_permalink( get_the_ID() );
					$raquo = '<a href="' . esc_attr( $permalink ) . '" title="' . esc_attr__('Click here to get to the chessgame', 'chessgame-shizzle') . '">&raquo;</a>';
					echo $args['before_title'] . $title . ' ' . $raquo . $args['after_title'];
				}

				echo chessgame_shizzle_get_iframe( get_the_ID() );

			}
			echo $args['after_widget'];

			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();

		}
	}

	/**
	 * Handles updating the settings for the current Featured Chessgame widget instance.
	 *
	 * @since 1.0.4
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']   = sanitize_text_field( $new_instance['title'] );
		$instance['post_id'] = (int) $new_instance['post_id'];
		return $instance;
	}

	/**
	 * Outputs the settings form for the Featured Chessgame widget.
	 *
	 * @since 1.0.4
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? $instance['title'] : '';
		$post_id   = isset( $instance['post_id'] ) ? absint( $instance['post_id'] ) : 0;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'chessgame-shizzle'  ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<?php
		$r = new WP_Query( array(
			'posts_per_page'      => 50,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'post_type'           => 'cs_chessgame',
			'ignore_sticky_posts' => true,
		) );

		if ($r->have_posts()) { ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ); ?>"><?php esc_html_e( 'Chessgame:', 'chessgame-shizzle' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'post_id' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ); ?>" class="widefat">
			<?php
			while ( $r->have_posts() ) {
				$r->the_post(); ?>
					<option value="<?php the_ID(); ?>" <?php selected( $post_id, get_the_ID() ); ?>><?php the_title(); ?></option>
				<?php
			} ?>
				</select>
			</p>
			<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
		}

	}
}

function chessgame_shizzle_widget_featured_chessgame() {
	register_widget('WP_Widget_Featured_Chessgame');
}
add_action('widgets_init', 'chessgame_shizzle_widget_featured_chessgame' );
