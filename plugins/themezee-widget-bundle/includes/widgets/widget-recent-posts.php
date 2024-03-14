<?php
/**
 * Recent Posts Widget
 *
 * Display the latest posts from a selected category in a boxed layout.
 *
 * @package ThemeZee Widget Bundle
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Recent Posts Widget Class
 */
class TZWB_Recent_Posts_Widget extends WP_Widget {

	/**
	 * Widget Constructor
	 *
	 * @uses WP_Widget::__construct() Create Widget
	 * @return void
	 */
	function __construct() {

		parent::__construct(
			'tzwb-recent-posts', // ID.
			esc_html__( 'Recent Posts (ThemeZee)', 'themezee-widget-bundle' ), // Name.
			array(
				'classname' => 'tzwb-recent-posts',
				'description' => esc_html__( 'Displays recent posts.', 'themezee-widget-bundle' ),
				'customize_selective_refresh' => true,
			) // Args.
		);

		// Delete Widget Cache on certain actions.
		add_action( 'save_post', array( $this, 'delete_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'delete_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'delete_widget_cache' ) );
	}

	/**
	 * Set default settings of the widget
	 *
	 * @return array Default widget settings.
	 */
	private function default_settings() {

		$defaults = array(
			'title'	         => '',
			'category'       => 0,
			'order'          => 'date',
			'number'         => 5,
			'excerpt_length' => 0,
			'thumbnails'     => true,
			'meta_date'      => false,
			'meta_author'    => false,
			'meta_comments'  => false,
		);

		return $defaults;
	}

	/**
	 * Reset widget cache object
	 *
	 * @return void
	 */
	public function delete_widget_cache() {

		wp_cache_delete( 'tzwb_recent_posts', 'widget' );

	}

	/**
	 * Returns the excerpt length in number of words
	 *
	 * @param int $length Excerpt length.
	 * @return integer $this->excerpt_length Number of Words
	 */
	function excerpt_length( $length ) {
		return $this->excerpt_length;
	}

	/**
	 * Main Function to display the widget
	 *
	 * @uses this->render()
	 *
	 * @param array $args Parameters from widget area created with register_sidebar().
	 * @param array $instance Settings for this widget instance.
	 * @return void
	 */
	function widget( $args, $instance ) {

		$cache = array();

		// Get Widget Object Cache.
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'tzwb_recent_posts', 'widget' );
		}
		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		// Display Widget from Cache if exists.
		if ( isset( $cache[ $this->id ] ) ) {
			echo $cache[ $this->id ];
			return;
		}

		// Start Output Buffering.
		ob_start();

		// Get Widget Settings.
		$settings = wp_parse_args( $instance, $this->default_settings() );

		// Add Widget Title Filter.
		$widget_title = apply_filters( 'widget_title', $settings['title'], $settings, $this->id_base );

		// Output.
		echo $args['before_widget'];

		// Display Title.
		if ( ! empty( $widget_title ) ) { echo $args['before_title'] . $widget_title . $args['after_title']; }; ?>

		<div class="tzwb-content tzwb-clearfix">

			<ul class="tzwb-posts-list">
				<?php echo $this->render( $settings ); ?>
			</ul>

		</div>

		<?php
		echo $args['after_widget'];

		// Set Cache.
		if ( ! $this->is_preview() ) {
			$cache[ $this->id ] = ob_get_flush();
			wp_cache_set( 'tzwb_recent_posts', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	/**
	 * Display the post list
	 *
	 * @param array $settings Settings for this widget instance.
	 * @return void
	 */
	function render( $settings ) {

		// Get latest posts from database.
		$query_arguments = array(
			'posts_per_page' => (int) $settings['number'],
			'ignore_sticky_posts' => true,
			'cat' => (int) $settings['category'],
			'orderby' => esc_attr( $settings['order'] ),
		);
		$posts_query = new WP_Query( $query_arguments );

		// Check if there are posts.
		if ( $posts_query->have_posts() ) :

			// Limit the number of words for the excerpt.
			$this->excerpt_length = (int) $settings['excerpt_length'];
			add_filter( 'excerpt_length', array( $this, 'excerpt_length' ) );

			// Display Posts.
			while ( $posts_query->have_posts() ) :

				$posts_query->the_post();

				if ( true == $settings['thumbnails'] and has_post_thumbnail() ) : ?>

					<li class="tzwb-has-thumbnail">
						<a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>">
							<?php the_post_thumbnail( 'tzwb-thumbnail' ); ?>
						</a>

				<?php else : ?>

					<li>

				<?php endif; ?>

					<a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>">
						<?php if ( get_the_title() ) { the_title(); } else { the_ID();} ?>
					</a>

				<?php // Display Post Content.
				if ( $settings['excerpt_length'] > 0 ) : ?>

					<span class="tzwb-excerpt"><?php the_excerpt(); ?></span>

				<?php endif;

				// Display Post Meta.
				echo $this->postmeta( $settings );

			endwhile;

			// Remove excerpt filter.
			remove_filter( 'excerpt_length', array( $this, 'excerpt_length' ) );

		endif;

		// Reset Postdata.
		wp_reset_postdata();
	}

	/**
	 * Display post meta
	 *
	 * @param array $settings Settings for this widget instance.
	 * @return void
	 */
	function postmeta( $settings ) {

		// Create Post Meta Array.
		$postmeta = array( $settings['meta_date'], $settings['meta_author'], $settings['meta_comments'] );

		// Return early if no meta is displayed.
		if ( in_array( true, $postmeta, true ) === false ) :
			return;
		endif;
		?>

		<div class="tzwb-entry-meta entry-meta">

		<?php // Display Date.
		if ( true == $settings['meta_date'] ) : ?>

			<span class="tzwb-meta-date meta-date"><?php the_time( get_option( 'date_format' ) ); ?></span>

		<?php endif; ?>

		<?php // Display Author.
		if ( true == $settings['meta_author'] ) : ?>

			<span class="tzwb-meta-author meta-author">
				<?php printf('<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_attr( sprintf( esc_html__( 'View all posts by %s', 'themezee-widget-bundle' ), get_the_author() ) ),
					get_the_author()
				);?>
			</span>

		<?php endif; ?>

		<?php // Display Comments.
		if ( true == $settings['meta_comments'] and comments_open() ) : ?>

			<span class="tzwb-meta-comments meta-comments">
				<?php comments_popup_link( esc_html__( 'No comments', 'themezee-widget-bundle' ), esc_html__( 'One comment','themezee-widget-bundle' ), esc_html__( '% comments','themezee-widget-bundle' ) ); ?>
			</span>

		<?php endif; ?>

		</div>

		<?php
	}

	/**
	 * Update Widget Settings
	 *
	 * @param array $new_instance Form Input for this widget instance.
	 * @param array $old_instance Old Settings for this widget instance.
	 * @return array $instance New widget settings
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = esc_attr( $new_instance['title'] );
		$instance['category'] = (int) $new_instance['category'];
		$instance['order'] = esc_attr( $new_instance['order'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['excerpt_length'] = (int) $new_instance['excerpt_length'];
		$instance['thumbnails'] = ! empty( $new_instance['thumbnails'] );
		$instance['meta_date'] = ! empty( $new_instance['meta_date'] );
		$instance['meta_author'] = ! empty( $new_instance['meta_author'] );
		$instance['meta_comments'] = ! empty( $new_instance['meta_comments'] );

		$this->delete_widget_cache();

		return $instance;
	}

	/**
	 * Display Widget Settings Form in the Backend
	 *
	 * @param array $instance Settings for this widget instance.
	 * @return void
	 */
	function form( $instance ) {

		// Get Widget Settings.
		$settings = wp_parse_args( $instance, $this->default_settings() );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'themezee-widget-bundle' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php esc_html_e( 'Select Category:', 'themezee-widget-bundle' ); ?></label><br/>
			<?php
				// Display Category Select.
				$args = array(
					'show_option_all'    => esc_html__( 'All Categories', 'themezee-widget-bundle' ),
					'selected'           => $settings['category'],
					'name'               => $this->get_field_name( 'category' ),
					'id'                 => $this->get_field_id( 'category' ),
				);
				wp_dropdown_categories( $args );
			?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php esc_html_e( 'Order by:', 'themezee-widget-bundle' ); ?></label><br/>
			<select name="<?php echo $this->get_field_name( 'order' ); ?>" id="<?php echo $this->get_field_id( 'order' ); ?>">
				<option <?php selected( $settings['order'], 'date' ); ?> value="date"><?php esc_html_e( 'Post Date', 'themezee-widget-bundle' ); ?></option>
				<option <?php selected( $settings['order'], 'comment_count' ); ?> value="comment_count"><?php esc_html_e( 'Comment Count', 'themezee-widget-bundle' ); ?></option>
				<option <?php selected( $settings['order'], 'rand' ); ?> value="rand"><?php esc_html_e( 'Random', 'themezee-widget-bundle' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number of posts:', 'themezee-widget-bundle' ); ?>
				<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo absint( $settings['number'] ); ?>" size="3" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php esc_html_e( 'Excerpt length in number of words:', 'themezee-widget-bundle' ); ?>
				<input id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" type="text" value="<?php echo absint( $settings['excerpt_length'] ); ?>" size="5" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'thumbnails' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['thumbnails'] ); ?> id="<?php echo $this->get_field_id( 'thumbnails' ); ?>" name="<?php echo $this->get_field_name( 'thumbnails' ); ?>" />
				<?php esc_html_e( 'Display post thumbnails', 'themezee-widget-bundle' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'meta_date' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['meta_date'] ); ?> id="<?php echo $this->get_field_id( 'meta_date' ); ?>" name="<?php echo $this->get_field_name( 'meta_date' ); ?>" />
				<?php esc_html_e( 'Display post date', 'themezee-widget-bundle' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'meta_author' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['meta_author'] ); ?> id="<?php echo $this->get_field_id( 'meta_author' ); ?>" name="<?php echo $this->get_field_name( 'meta_author' ); ?>" />
				<?php esc_html_e( 'Display post author', 'themezee-widget-bundle' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'meta_comments' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['meta_comments'] ); ?> id="<?php echo $this->get_field_id( 'meta_comments' ); ?>" name="<?php echo $this->get_field_name( 'meta_comments' ); ?>" />
				<?php esc_html_e( 'Display post comments', 'themezee-widget-bundle' ); ?>
			</label>
		</p>

		<?php
	}
}
