<?php

/**
 * LearnDash Recently Updated: Dashboard Widget
 * 
 * Shows the 2-3 most recently updated items in a variety of
 * different LearnDash post types.
 * 
 * @link https://www.cssigniter.com/make-wordpress-dashboard-widget/
 * @link https://generatewp.com/snippet/0mkyy7d/
 */

class DWFL_Learndash_Recently_Updated_Dashboard_Widget {

	public function __construct() {
		
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );

	}

	public function add_dashboard_widget() {

		wp_add_dashboard_widget(
			'dwfl_learndash_recently_updated',
			__( 'LearnDash Recently Modified', 'dashboard-widgets-learndash' ),
			array( $this, 'render_dashboard_widget' )
		);

	}

	public function render_dashboard_widget() {

		/**
		 * Custom query to retrieve recently updated courses
		 */
		$args = array( 
			'post_type'              => 'sfwd-courses',
			'post_status'            => array( 'publish', 'future', 'private', 'pending', 'draft' ),
			'posts_per_page'         => 3,
			'orderby'                => 'modified',
			'order'                  => 'DESC',
			'no_found_rows'          => true,
			'update_post_term_cache' => false
		);

		$ld_modified_courses_query = new WP_Query( $args );

		/**
		 * Custom query to retrieve recently updated lessons
		 */
		$args = array( 
			'post_type'              => 'sfwd-lessons',
			'post_status'            => array( 'publish', 'future', 'private', 'pending', 'draft' ),
			'posts_per_page'         => 3,
			'orderby'                => 'modified',
			'order'                  => 'DESC',
			'no_found_rows'          => true,
			'update_post_term_cache' => false
		);

		$ld_modified_lessons_query = new WP_Query( $args );

		/**
		 * Custom query to retrieve recently updated topics
		 */
		$args = array( 
			'post_type'              => 'sfwd-topic',
			'post_status'            => array( 'publish', 'future', 'private', 'pending', 'draft' ),
			'posts_per_page'         => 3,
			'orderby'                => 'modified',
			'order'                  => 'DESC',
			'no_found_rows'          => true,
			'update_post_term_cache' => false
		);

		$ld_modified_topics_query = new WP_Query( $args );

		/**
		 * Custom query to retrieve recently updated quizzes
		 */
		$args = array( 
			'post_type'              => 'sfwd-quiz',
			'post_status'            => array( 'publish', 'future', 'private', 'pending', 'draft' ),
			'posts_per_page'         => 3,
			'orderby'                => 'modified',
			'order'                  => 'DESC',
			'no_found_rows'          => true,
			'update_post_term_cache' => false
		);

		$ld_modified_quizzes_query = new WP_Query( $args );

		/**
		 * Get total counts so we can only display sections
		 * with at least 1 piece of content.
		 */
		// Get Total Courses
		$count_courses = wp_count_posts( 'sfwd-courses' );

		// Get Total Lessons
		$count_lessons = wp_count_posts( 'sfwd-lessons' );

		// Get Total Topics
		$count_topics = wp_count_posts( 'sfwd-topic' );

		// Get Total Quizzes
		$count_quizzes = wp_count_posts( 'sfwd-quiz' );

		/**
		 * Output Widget
		 */
		?>

		<div class="ldx-db-widget ldx-widget-recently-updated">

			<?php if( $count_courses > 0 && current_user_can( 'edit_courses' ) ) { ?>

				<div class="ldx-mod-courses">	

					<div class="ldx-flex ldx-header">

						<h3><?php esc_html_e( LearnDash_Custom_Label::get_label( 'courses' ) ); ?></h3>

					</div>

					<?php
					if ( $ld_modified_courses_query->have_posts() ) :

						while ( $ld_modified_courses_query->have_posts() ) : $ld_modified_courses_query->the_post();

							$post_id = get_the_ID();
							$modified_time = human_time_diff( get_the_modified_date( 'U' ), current_time('timestamp') ); ?>

							<div class="ldx-post-item">

								<div class="ldx-flex">
									<span class="ldx-item-title"><?php the_title(); ?></span>
									<span class="ldx-modified-time"><?php esc_html_e( $modified_time . ' ago' ); ?></span>
								</div>

								<div class="ldx-go-links">

									<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) ); ?>"><?php _e( 'Edit' ); ?></a> &nbsp;|&nbsp;

									<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $post_id . '&action=edit&currentTab=learndash_course_builder' ) ); ?>"><?php _e( 'Builder' ); ?></a> &nbsp;|&nbsp;

									<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $post_id . '&action=edit&currentTab=sfwd-courses-settings' ) ); ?>"><?php _e( 'Settings' ); ?></a>

								</div>

							</div> <!-- .ldx-post-item -->

						<?php endwhile; // end loop

					endif; // end if have_posts()

					wp_reset_postdata(); ?>

				</div> <!-- .ldx-mod-courses -->

			<?php } // if has courses & can edit_courses ?>

			<?php if( $count_lessons > 0 && current_user_can( 'edit_courses' ) ) { ?>

				<div class="ldx-mod-lessons">

					<div class="ldx-flex ldx-header">

						<h3><?php esc_html_e( LearnDash_Custom_Label::get_label( 'lessons' ) ); ?></h3>

					</div>

					<?php
					if ( $ld_modified_lessons_query->have_posts() ) :

						while ( $ld_modified_lessons_query->have_posts() ) : $ld_modified_lessons_query->the_post();

							$post_id = get_the_ID();
							$modified_time = human_time_diff( get_the_modified_date( 'U' ), current_time('timestamp') ); ?>

							<a class="ldx-flex ldx-post-item" href="<?php echo esc_url( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) ); ?>">

								<span class="ldx-item-title"><?php the_title(); ?></span>
								<span class="ldx-modified-time"><?php esc_html_e( $modified_time . ' ago' ); ?></span>

							</a>

						<?php endwhile; // end loop ?>

					<?php endif; // end if have_posts()

					wp_reset_postdata(); ?>

				</div> <!-- .ldx-mod-lessons -->

			<?php } // if has lessons & can edit_courses ?>

			<?php if( $count_topics > 0 && current_user_can( 'edit_courses' ) ) { ?>

				<div class="ldx-mod-topics">

					<div class="ldx-flex ldx-header">

						<h3><?php esc_html_e( LearnDash_Custom_Label::get_label( 'topics' ) ); ?></h3>

					</div>

					<?php
					if ( $ld_modified_topics_query->have_posts() ) :

						while ( $ld_modified_topics_query->have_posts() ) : $ld_modified_topics_query->the_post();

							$post_id = get_the_ID();
							$modified_time = human_time_diff( get_the_modified_date( 'U' ), current_time('timestamp') ); ?>

							<a class="ldx-flex ldx-post-item" href="<?php echo esc_url( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) ); ?>">

								<span class="ldx-item-title"><?php the_title(); ?></span>
								<span class="ldx-modified-time"><?php esc_html_e( $modified_time . ' ago' ); ?></span>

							</a>

						<?php endwhile; // end loop

					endif; // end if have_posts()

					wp_reset_postdata(); ?>

				</div> <!-- .ldx-mod-topics -->

			<?php } // if has topics & can edit_courses ?>

			<?php if( $count_quizzes > 0 && current_user_can( 'edit_courses' ) ) { ?>

				<div class="ldx-mod-quizzes">

					<div class="ldx-flex ldx-header">

						<h3><?php esc_html_e( LearnDash_Custom_Label::get_label( 'quizzes' ) ); ?></h3>

					</div>

					<?php
					if ( $ld_modified_quizzes_query->have_posts() ) :

						while ( $ld_modified_quizzes_query->have_posts() ) : $ld_modified_quizzes_query->the_post();

							$post_id = get_the_ID();
							$modified_time = human_time_diff( get_the_modified_date( 'U' ), current_time('timestamp') ); ?>

							<a class="ldx-flex ldx-post-item" href="<?php echo esc_url( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) ); ?>">

								<span class="ldx-item-title"><?php the_title(); ?></span>
								<span class="ldx-modified-time"><?php esc_html_e( $modified_time . ' ago' ); ?></span>

							</a>

						<?php endwhile; // end loop

					endif; // end if have_posts()

					wp_reset_postdata(); ?>

				</div> <!-- .ldx-mod-quizzes -->

			<?php } // if has quizzes & can edit_courses ?>

		</div> <!-- .ldx-db-widget -->

	<?php } // function render_dashboard_widget

} // Class DWFL_Learndash_Recently_Updated_Dashboard_Widget

// Instantiate Widget
new DWFL_Learndash_Recently_Updated_Dashboard_Widget;