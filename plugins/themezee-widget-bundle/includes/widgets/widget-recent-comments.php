<?php
/**
 * Recent Comments Widget
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
 * Recent Comments Widget Class
 */
class TZWB_Recent_Comments_Widget extends WP_Widget {

	/**
	 * Widget Constructor
	 *
	 * @uses WP_Widget::__construct() Create Widget
	 * @return void
	 */
	function __construct() {

		parent::__construct(
			'tzwb-recent-comments', // ID.
			esc_html__( 'Recent Comments (ThemeZee)', 'themezee-widget-bundle' ), // Name.
			array(
				'classname' => 'tzwb-recent-comments',
				'description' => esc_html__( 'Displays latest comments with Gravatar.', 'themezee-widget-bundle' ),
				'customize_selective_refresh' => true,
			) // Args.
		);

		// Delete Widget Cache on certain actions.
		add_action( 'comment_post', array( $this, 'delete_widget_cache' ) );
		add_action( 'transition_comment_status', array( $this, 'delete_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'delete_widget_cache' ) );
	}

	/**
	 * Set default settings of the widget
	 *
	 * @return array Default widget settings.
	 */
	private function default_settings() {

		$defaults = array(
			'title'          => '',
			'number'         => 5,
			'comment_length' => 0,
			'avatar'         => true,
			'post_title'     => true,
			'comment_date'   => false,
		);

		return $defaults;
	}

	/**
	 * Reset widget cache object
	 *
	 * @return void
	 */
	public function delete_widget_cache() {

		wp_cache_delete( 'tzwb_recent_comments', 'widget' );

	}

	/**
	 * Generates excerpt of comment content
	 *
	 * @param string  $comment Comment content.
	 * @param integer $length Number of Characters.
	 * @return string $parts[0] Excerpt of comment
	 */
	function comment_length( $comment, $length = 0 ) {
		$parts = explode( "\n", wordwrap( $comment, $length, "\n" ) );
		return $parts[0];
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
			$cache = wp_cache_get( 'tzwb_recent_comments', 'widget' );
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

			<ul class="tzwb-comments-list">
				<?php echo $this->render( $settings ); ?>
			</ul>

		</div>

		<?php
		echo $args['after_widget'];

		// Set Cache.
		if ( ! $this->is_preview() ) {
			$cache[ $this->id ] = ob_get_flush();
			wp_cache_set( 'tzwb_recent_comments', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	/**
	 * Display the comment list
	 *
	 * @param array $settings Settings for this widget instance.
	 * @return void
	 */
	function render( $settings ) {

		// Get latest comments from database.
		$comments = get_comments( array(
			'number' => (int) $settings['number'],
			'status' => 'approve',
			'post_status' => 'publish',
		) );

		// Check if there are comments.
		if ( $comments ) :

			// Display Comments.
			foreach ( (array) $comments as $comment ) : ?>

				<?php // Display Gravatar.
				if ( true == $settings['avatar'] ) : ?>

					<li class="tzwb-has-avatar">
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<?php echo get_avatar( $comment, 55 ); ?>
						</a>

				<?php else : ?>

					<li>

				<?php endif; ?>


				<?php // Display Post Title.
				if ( true == $settings['post_title'] ) :

					echo get_comment_author_link( $comment->comment_ID );
					esc_html_e( ' on', 'themezee-widget-bundle' ); ?>

					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<?php echo get_the_title( $comment->comment_post_ID ); ?>
					</a>

				<?php else : ?>

					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<?php echo get_comment_author_link( $comment->comment_ID ); ?>
					</a>

				<?php endif; ?>


				<?php // Display Comment Content.
				if ( $settings['comment_length'] > 0 ) :  ?>

					<div class="tzwb-comment-content"><?php echo $this->comment_length( $comment->comment_content, $settings['comment_length'] ); ?></div>

				<?php endif; ?>

				<?php // Display Comment Date.
				if ( true == $settings['comment_date'] ) :

					$date_format = get_option( 'date_format' );
					$time_format = get_option( 'time_format' );
				?>

					<div class="tzwb-comment-date"><?php echo date_i18n( $date_format . ' ' . $time_format, strtotime( $comment->comment_date ) ); ?></div>

				<?php endif; ?>

			<?php
			endforeach;

		endif;
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
		$instance['comment_length'] = (int) $new_instance['comment_length'];
		$instance['avatar'] = ! empty( $new_instance['avatar'] );
		$instance['post_title'] = ! empty( $new_instance['post_title'] );
		$instance['comment_date'] = ! empty( $new_instance['comment_date'] );

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
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number of comments to show:', 'themezee-widget-bundle' ); ?>
				<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo absint( $settings['number'] ); ?>" size="3" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'comment_length' ); ?>">
				<?php esc_html_e( 'Excerpt length in number of characters:', 'themezee-widget-bundle' ); ?>
				<input id="<?php echo $this->get_field_id( 'comment_length' ); ?>" name="<?php echo $this->get_field_name( 'comment_length' ); ?>" type="text" value="<?php echo absint( $settings['comment_length'] ); ?>" size="5" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'avatar' ); ?>">
				<input class="checkbox" type="checkbox"  <?php checked( $settings['avatar'] ); ?> id="<?php echo $this->get_field_id( 'avatar' ); ?>" name="<?php echo $this->get_field_name( 'avatar' ); ?>" />
				<?php esc_html_e( 'Display avatar of comment author', 'themezee-widget-bundle' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'post_title' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['post_title'] ); ?> id="<?php echo $this->get_field_id( 'post_title' ); ?>" name="<?php echo $this->get_field_name( 'post_title' ); ?>" />
				<?php esc_html_e( 'Display post title of commented post', 'themezee-widget-bundle' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'comment_date' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['comment_date'] ); ?> id="<?php echo $this->get_field_id( 'comment_date' ); ?>" name="<?php echo $this->get_field_name( 'comment_date' ); ?>" />
				<?php esc_html_e( 'Display comment date', 'themezee-widget-bundle' ); ?>
			</label>
		</p>

		<?php
	}
}
