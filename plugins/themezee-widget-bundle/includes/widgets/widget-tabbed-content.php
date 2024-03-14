<?php
/**
 * Tabbed Content Widget
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
 * Tabbed Content Widget Class
 */
class TZWB_Tabbed_Content_Widget extends WP_Widget {

	/**
	 * Widget Constructor
	 *
	 * @uses WP_Widget::__construct() Create Widget
	 * @return void
	 */
	function __construct() {

		parent::__construct(
			'tzwb-tabbed-content', // ID.
			esc_html__( 'Tabbed Content (ThemeZee)', 'themezee-widget-bundle' ), // Name.
			array(
				'classname' => 'tzwb-tabbed-content',
				'description' => esc_html__( 'Displays various content with tabs.', 'themezee-widget-bundle' ),
			) // Args.
		);

		// Enqueue Javascript for Tabs.
		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		// Delete Widget Cache on certain actions.
		add_action( 'save_post', array( $this, 'delete_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'delete_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'delete_widget_cache' ) );
		add_action( 'comment_post', array( $this, 'delete_widget_cache' ) );
		add_action( 'transition_comment_status', array( $this, 'delete_widget_cache' ) );
	}

	/**
	 * Set default settings of the widget
	 *
	 * @return array Default widget settings.
	 */
	private function default_settings() {

		$defaults = array(
			'title'       => '',
			'number'      => 5,
			'thumbnails'  => true,
			'tab_titles'  => array( '', '', '', '' ),
			'tab_content' => array( 0, 0, 0, 0 ),
		);

		return $defaults;
	}

	/**
	 * Reset widget cache object
	 *
	 * @return void
	 */
	public function delete_widget_cache() {

		wp_cache_delete( 'tzwb_tabbed_content', 'widget' );

	}

	/**
	 * Enqueue jquery tabs javascript
	 *
	 * @see https://codex.wordpress.org/Function_Reference/wp_enqueue_script WordPress Codex.
	 * @return void
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'tzwb-tabbed-content', TZWB_PLUGIN_URL . '/assets/js/tabbed-content.js', array( 'jquery' ), TZWB_VERSION );

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
			$cache = wp_cache_get( 'tzwb_tabbed_content', 'widget' );
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

			<?php echo $this->render( $args, $settings ); ?>

		</div>

		<?php
		echo $args['after_widget'];

		// Set Cache.
		if ( ! $this->is_preview() ) {
			$cache[ $this->id ] = ob_get_flush();
			wp_cache_set( 'tzwb_tabbed_content', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	/**
	 * Display the tab navigation
	 *
	 * @uses this->tab_content() to display content of tabs.
	 * @param array $args Parameters from widget area created with register_sidebar().
	 * @param array $settings Settings for this widget instance.
	 * @return void
	 */
	function render( $args, $settings ) {
		?>

		<div class="tzwb-tabnavi-wrap tzwb-clearfix">

			<ul class="tzwb-tabnavi">

				<?php // Display Tab Titles.
				for ( $i = 0; $i <= 3; $i++ ) :

					// Do not display empty tabs.
					if ( '' === $settings['tab_titles'][ $i ] and 0 === $settings['tab_content'][ $i ] ) {
						continue;
					}
					?>

					<li><a href="#<?php echo $args['widget_id']; ?>-tab-<?php echo $i; ?>"><?php echo esc_html( $settings['tab_titles'][ $i ] ); ?></a></li>

				<?php endfor; ?>

			</ul>

		</div>

		<?php
		// Display Tab Content.
		for ( $i = 0; $i <= 3; $i++ ) : ?>

			<div id="<?php echo $args['widget_id']; ?>-tab-<?php echo $i; ?>" class="tzwb-tabcontent">

				<?php echo $this->tab_content( $settings, $settings['tab_content'][ $i ] ); ?>

			</div>

		<?php
		endfor;
	}

	/**
	 * Display the tab content
	 *
	 * @param array   $settings Settings for this widget instance.
	 * @param integer $tabcontent Tab ID to select which tab is displayed.
	 * @return void
	 */
	function tab_content( $settings, $tabcontent ) {

		switch ( $tabcontent ) :

			 // Archives.
			case 1: ?>

				<ul class="tzwb-tabcontent-archives">
					<?php wp_get_archives( array( 'type' => 'monthly', 'show_post_count' => 1 ) ); ?>
				</ul>

			<?php
			break;

			// Categories.
			case 2:  ?>

				<ul class="tzwb-tabcontent-categories">
					<?php wp_list_categories( array( 'title_li' => '', 'orderby' => 'name', 'show_count' => 1, 'hierarchical' => false ) ); ?>
				</ul>

			<?php
			break;

			// Pages.
			case 3: ?>

				<ul class="tzwb-tabcontent-pages">
					<?php wp_list_pages( array( 'title_li' => '' ) ); ?>
				</ul>

			<?php
			break;

			// Popular Posts.
			case 4:

				// Get latest popular posts from database.
				$query_arguments = array(
					'posts_per_page' => (int) $settings['number'],
					'ignore_sticky_posts' => true,
					'orderby' => 'comment_count',
				);
				$posts_query = new WP_Query( $query_arguments );
			?>

				<ul class="tzwb-tabcontent-popular-posts tzwb-posts-list">

					<?php // Display Posts.
					if ( $posts_query->have_posts() ) : while ( $posts_query->have_posts() ) : $posts_query->the_post();

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

							<div class="tzwb-entry-meta entry-meta">

							<?php // Display Date only on posts with thumbnails.
							if ( true == $settings['thumbnails'] and has_post_thumbnail() ) : ?>

								<span class="tzwb-meta-date meta-date"><?php the_time( get_option( 'date_format' ) ); ?></span>

							<?php endif; ?>

							</div>

					<?php endwhile;
					endif; ?>

				</ul>

			<?php
			break;

			// Recent Comments.
			case 5:

				// Get latest comments from database.
				$comments = get_comments( array(
					'number' => (int) $settings['number'],
					'status' => 'approve',
					'post_status' => 'publish',
				) );
			?>

				<ul class="tzwb-tabcontent-comments tzwb-comments-list">

					<?php // Display Comments.
					if ( $comments ) :
						foreach ( (array) $comments as $comment ) :

							 // Display Gravatar.
							if ( true == $settings['thumbnails'] ) : ?>

								<li class="tzwb-has-avatar">
									<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
										<?php echo get_avatar( $comment, 55 ); ?>
									</a>

							<?php else : ?>

								<li>

							<?php endif;

							echo get_comment_author_link( $comment->comment_ID );
							esc_html_e( ' on', 'themezee-widget-bundle' ); ?>

							<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
								<?php echo get_the_title( $comment->comment_post_ID ); ?>
							</a>

					<?php endforeach;
					endif; ?>

				</ul>

			<?php
			break;

			// Recent Posts.
			case 6:

				// Get latest posts from database.
				$query_arguments = array(
					'posts_per_page' => (int) $settings['number'],
					'ignore_sticky_posts' => true,
				);
				$posts_query = new WP_Query( $query_arguments );
				?>

				<ul class="tzwb-tabcontent-recent-posts tzwb-posts-list">

					<?php
					// Display Posts.
					if ( $posts_query->have_posts() ) : while ( $posts_query->have_posts() ) : $posts_query->the_post();

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

							<div class="tzwb-entry-meta entry-meta">

							<?php // Display Date only for posts with thumbnails.
							if ( true == $settings['thumbnails'] and has_post_thumbnail() ) : ?>

								<span class="tzwb-meta-date meta-date"><?php the_time( get_option( 'date_format' ) ); ?></span>

							<?php endif; ?>

							</div>

					<?php endwhile; endif; ?>

				</ul>

			<?php
			break;

			// Tag Cloud.
			case 7: ?>

				<div class="tzwb-tabcontent-tagcloud widget_tag_cloud">
					<div class="tagcloud"><?php wp_tag_cloud( array( 'taxonomy' => 'post_tag' ) ); ?></div>
				</div>

			<?php
			break;

			// No Content selected.
			default: ?>

				<p class="tzwb-tabcontent-missing">
					<?php esc_html_e( 'Please select the Tab Content in the Widget Settings.', 'themezee-widget-bundle' ); ?>
				</p>

			<?php
			break;

		endswitch;
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
		$instance['number'] = (int) $new_instance['number'];
		$instance['thumbnails'] = ! empty( $new_instance['thumbnails'] );

		// Validate Tab Settings.
		$instance['tab_content'] = array();
		$instance['tab_titles'] = array();

		for ( $i = 0; $i <= 3; $i++ ) :

			$instance['tab_content'][ $i ] = (int) $new_instance[ 'tab_content-' . $i ];
			$instance['tab_titles'][ $i ] = sanitize_text_field( $new_instance[ 'tab_titles-' . $i ] );

		endfor;

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

		<div style="background: #fafafa; border-top: 1px solid #eaeaea; margin-bottom: 20px;">

		<?php
		// Display Tab Options.
		for ( $i = 0; $i <= 3; $i++ ) : ?>

			<p style="border-bottom: 1px solid #eaeaea; padding: 10px; margin: 0;">

				<label style="display: inline-block; min-width: 50px" for="<?php echo $this->get_field_id( 'tab_content-' . $i ); ?>">
					<?php printf( esc_html__( 'Tab %s:', 'themezee-widget-bundle' ), $i + 1 ); ?>
				</label>

				<select id="<?php echo $this->get_field_id( 'tab_content-' . $i ); ?>" name="<?php echo $this->get_field_name( 'tab_content-' . $i ); ?>">
					<option value="0" <?php selected( $settings['tab_content'][ $i ], 0 ); ?>></option>
					<option value="1" <?php selected( $settings['tab_content'][ $i ], 1 ); ?>><?php esc_html_e( 'Archives', 'themezee-widget-bundle' ); ?></option>
					<option value="2" <?php selected( $settings['tab_content'][ $i ], 2 ); ?>><?php esc_html_e( 'Categories', 'themezee-widget-bundle' ); ?></option>
					<option value="3" <?php selected( $settings['tab_content'][ $i ], 3 ); ?>><?php esc_html_e( 'Pages', 'themezee-widget-bundle' ); ?></option>
					<option value="4" <?php selected( $settings['tab_content'][ $i ], 4 ); ?>><?php esc_html_e( 'Popular Posts', 'themezee-widget-bundle' ); ?></option>
					<option value="5" <?php selected( $settings['tab_content'][ $i ], 5 ); ?>><?php esc_html_e( 'Recent Comments', 'themezee-widget-bundle' ); ?></option>
					<option value="6" <?php selected( $settings['tab_content'][ $i ], 6 ); ?>><?php esc_html_e( 'Recent Posts', 'themezee-widget-bundle' ); ?></option>
					<option value="7" <?php selected( $settings['tab_content'][ $i ], 7 ); ?>><?php esc_html_e( 'Tag Cloud', 'themezee-widget-bundle' ); ?></option>
				</select>

				<br/>

				<label style="display: inline-block; min-width: 50px" for="<?php echo $this->get_field_id( 'tab_titles-' . $i ); ?>">
					<?php esc_html_e( 'Title:', 'themezee-widget-bundle' ); ?>
				</label>

				<input style="width: auto" id="<?php echo $this->get_field_id( 'tab_titles-' . $i ); ?>" name="<?php echo $this->get_field_name( 'tab_titles-' . $i ); ?>" type="text" value="<?php echo esc_attr( $settings['tab_titles'][ $i ] ); ?>" />

			</p>

		<?php endfor; ?>

		</div>

		<strong><?php esc_html_e( 'Settings for Recent/Popular Posts and Recent Comments', 'themezee-widget-bundle' ); ?></strong>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number of entries:', 'themezee-widget-bundle' ); ?>
				<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo absint( $settings['number'] ); ?>" size="3" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'thumbnails' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['thumbnails'] ); ?> id="<?php echo $this->get_field_id( 'thumbnails' ); ?>" name="<?php echo $this->get_field_name( 'thumbnails' ); ?>" />
				<?php esc_html_e( 'Show Thumbnails?', 'themezee-widget-bundle' ); ?>
			</label>
		</p>

		<?php
	}
}
