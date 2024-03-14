<?php

/**
 * LearnDash Courses: Dashboard Widget
 * Lists each course, along with info & quick links
 * 
 * @link https://www.cssigniter.com/make-wordpress-dashboard-widget/
 * @link https://generatewp.com/snippet/0mkyy7d/
 */

class DWFL_Learndash_Courses_Dashboard_Widget {

	public function __construct() {
		
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );

	}

	public function add_dashboard_widget() {

		wp_add_dashboard_widget(
			'dwfl_learndash_courses',
			__( 'LearnDash ', 'dashboard-widgets-learndash' ) . LearnDash_Custom_Label::get_label( 'courses' ),
			array( $this, 'render_dashboard_widget' )
		);

	}

	public function render_dashboard_widget() {

		/**
		 * Custom query to retrieve course information
		 */
		$args = array( 
			'post_type'              => 'sfwd-courses',
			'post_status'            => array( 'publish', 'future', 'private', 'pending', 'draft' ),
			'posts_per_page'         => 10,
			'orderby'                => 'menu_order title',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'update_post_term_cache' => false
		);

		$ld_courses_query = new WP_Query( $args );


		/**
		 * User Role & Capability Setup
		 * 
		 * Show "Edit" link if user can edit courses AND published courses.
		 * Show "Lessons (1)", "Topics (3)", "Quizzes (1)" & Certificate link
		 * if user has ability to access these pages in the admin (edit_courses),
		 * but not necessarily edit them.
		 */
		$ldx_has_access_edit_courses_all = current_user_can( 'edit_courses' ) && current_user_can( 'edit_published_courses' );
		$ldx_has_access_edit_courses = current_user_can( 'edit_courses' );


		/**
		 * Output Widget
		 */
		?>

		<div class="ldx-db-widget ldx-widget-scrollable ldx-widget-courses">

			<?php
			if ( $ld_courses_query->have_posts() ) :

				while ( $ld_courses_query->have_posts() ) : $ld_courses_query->the_post();

					$post_id = get_the_ID();
					$post_status = get_post_status();
					if ( $post_status == 'future' ) { $post_status = 'scheduled'; }
					if ( $post_status == 'pending' ) { $post_status = 'Pending Review'; }

					$courses = get_post_meta( get_the_ID(), '_sfwd-courses', false );
					$course_steps = get_post_meta( get_the_ID(), 'ld_course_steps', false );
					?>

					<div class="ldx-post-container">

						<div class="ldx-flex">

							<h3 class="ldx-post-title"><?php the_title(); if ( $post_status != 'publish' ) {
								echo '<small class="ldx-text-capitalize ldx-text-light"> - ' . esc_html__( $post_status, 'dashboard-widgets-learndash' ) . '</small>';
								} ?></h3>

							<?php foreach ( $courses as $course ) :

								$course_type = $course['sfwd-courses_course_price_type'];
								if ( $course_type == 'paynow' ) { $course_type = 'Buy Now'; }
								if ( $course_type == 'subscribe' ) { $course_type = 'Recurring'; }
								?>

								<div class="ldx-course-type">
									<span class="ldx-badge"><?php esc_html_e( $course_type, 'dashboard-widgets-learndash' ); ?></span>
								</div>

							<?php endforeach; ?>

						</div>

						<?php if ( $post_status == 'scheduled' ) {
							echo '<div class="ldx-scheduled-date ldx-text-light ldx-mb-md"><span class="dashicons dashicons-calendar-alt"></span>' . esc_html( get_the_time( get_option( 'date_format' ) ) ) . '</div>';
						} ?>

						<div class="ldx-action-links">

							<?php if ( $ldx_has_access_edit_courses_all ) : ?>
								<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) ); ?>"><?php _e( 'Edit' ); ?></a> &nbsp;|&nbsp;
							<?php endif; ?>

							<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'View', 'dashboard-widgets-learndash' ); ?></a>

							<?php if ( $ldx_has_access_edit_courses ) : ?>

								&nbsp;|&nbsp; <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-lessons&course_id=' . $post_id ) ); ?>"><?php esc_html_e( LearnDash_Custom_Label::get_label( 'lessons' ), 'dashboard-widgets-learndash' ); ?>

								<?php if( empty( $course_steps ) ) : ?>
									<span class="count">(0)</span></a>
								<?php endif; ?>

								<?php foreach ( $course_steps as $course_step ) :

									$course_lessons = $course_step['t']['sfwd-lessons'];

									if( ! $course_lessons == 0 ) : ?>
										<span class="count">(<?php echo count( $course_lessons ); ?>)</span></a>
									<?php else : ?>
										<span class="count">(0)</span></a>
									<?php endif; ?>

								<?php endforeach; ?>

									&nbsp;|&nbsp; <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-topic&course_id=' . $post_id ) ); ?>"><?php esc_html_e( LearnDash_Custom_Label::get_label( 'topics' ), 'dashboard-widgets-learndash' ); ?>

								<?php if( empty( $course_steps ) ) : ?>
									<span class="count">(0)</span></a>
								<?php endif; ?>

								<?php foreach ( $course_steps as $course_step ) :

									$course_topics = $course_step['t']['sfwd-topic'];
									
									if( ! $course_topics == 0 ) : ?>
										<span class="count">(<?php echo count( $course_topics ); ?>)</span></a>
									<?php else : ?>
										<span class="count">(0)</span></a>
									<?php endif; ?>

								<?php endforeach; ?>

									&nbsp;|&nbsp; <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-quiz&course_id=' . $post_id ) ); ?>"><?php esc_html_e( LearnDash_Custom_Label::get_label( 'quizzes' ), 'dashboard-widgets-learndash' ); ?>

									<?php if( empty( $course_steps ) ) : ?>
										<span class="count">(0)</span></a>
									<?php endif; ?>

								<?php foreach ( $course_steps as $course_step ) :

									$course_quizzes = $course_step['t']['sfwd-quiz'];
									
									if( ! $course_quizzes == 0 ) : ?>
										<span class="count">(<?php echo count( $course_quizzes ); ?>)</span></a>
									<?php else : ?>
										<span class="count">(0)</span></a>
									<?php endif; ?>

								<?php endforeach; ?>

								<?php if( current_user_can( 'list_users' ) ) { ?>

									&nbsp;|&nbsp; <a href="<?php echo esc_url( admin_url( 'users.php?role=subscriber&course_id=' . $post_id ) ); ?>"><?php _e( 'Students', 'dashboard-widgets-learndash' ); ?></a>
								<?php } ?>

							<?php endif; // capability check: ldx_has_access_edit_courses ?>

						</div> <!-- .ldx-action-links -->

						<?php if ( $ldx_has_access_edit_courses ) : ?>

							<?php foreach ( $courses as $course ) :

								$course_cert_id = $course['sfwd-courses_certificate'];

								if ( $course_cert_id != '0' ) :
									echo '<hr /><div class="ldx-mt-md ldx-course-certificate">' . __( 'Certificate', 'dashboard-widgets-learndash' ) . ': <a href="' . esc_url( get_edit_post_link( $course_cert_id ) ) . '">' . esc_html( get_the_title( $course_cert_id ) ) . '</a></div>';
								endif;

							endforeach;
						
						endif; // capability check: ldx_has_access_edit_courses ?>

					</div> <!-- .ldx-post-container -->

				<?php endwhile; // end loop

			endif; // end if have_posts()

			wp_reset_postdata(); ?>

		</div> <!-- .ldx-db-widget -->

	<?php } // function render_dashboard_widget

} // Class DWFL_Learndash_Courses_Dashboard_Widget

// Instantiate Widget
new DWFL_Learndash_Courses_Dashboard_Widget;