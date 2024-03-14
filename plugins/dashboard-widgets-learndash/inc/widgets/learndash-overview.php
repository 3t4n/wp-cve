<?php

/**
 * LearnDash Overview: Dashboard Widget
 * Basic stats & links about the learning environment
 * 
 * @link https://www.cssigniter.com/make-wordpress-dashboard-widget/
 * @link https://generatewp.com/snippet/0mkyy7d/
 */

class DWFL_Learndash_Overview_Dashboard_Widget {

	public function __construct() {
		
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );

	}

	public function add_dashboard_widget() {

		wp_add_dashboard_widget(
			'dwfl_learndash_overview',
			__( 'LearnDash Overview', 'dashboard-widgets-learndash' ),
			array( $this, 'render_dashboard_widget' )
		);

	}

	public function render_dashboard_widget() {

		/**
		 * Get Counts of Total Courses, Lessons, etc.
		 * Store in a variable for later output
		 */

		// Get Total Courses
		$count_courses = wp_count_posts( 'sfwd-courses' );
		$published_courses = $count_courses->publish;

		// Get Total Lessons
		$count_lessons = wp_count_posts( 'sfwd-lessons' );
		$published_lessons = $count_lessons->publish;

		// Get Total Topics
		$count_topics = wp_count_posts( 'sfwd-topic' );
		$published_topics = $count_topics->publish;

		// Get Total Groups
		$count_groups = wp_count_posts( 'groups' );
		$published_groups = $count_groups->publish;

		// Get Group Leaders
		$user_count_data = count_users();
		$avail_roles = $user_count_data['avail_roles'];

		$group_leader_count = $avail_roles['group_leader'];

		// Get Total Quizzes
		$count_quizzes = wp_count_posts( 'sfwd-quiz' );
		$published_quizzes = $count_quizzes->publish;

		// Get Total Questions
		$count_questions = wp_count_posts( 'sfwd-question' );
		$published_questions = $count_questions->publish;

		// Get Total Certificates
		$count_certificates = wp_count_posts( 'sfwd-certificates' );
		$published_certificates = $count_certificates->publish;

		// Get Total Assignments
		$count_assignments = wp_count_posts( 'sfwd-assignment' );
		$published_assignments = $count_assignments->publish;

		// Get Total Essays
		$count_essays = wp_count_posts( 'sfwd-essays' );
		$graded_essays = $count_essays->graded;
		$notgraded_essays = $count_essays->not_graded;
		$all_essays = $graded_essays + $notgraded_essays
		// Essays don't use "published" post status, so no need to count only published ones

		
		/**
		 * Output Widget HTML
		 */
		?>

		<div class="ldx-db-widget ldx-widget-overview">

			<div class="ldx-grid ldx-grid-3c">

				<ul class="ldx-flex ldx-flex-column ldx-stats-totals ldx-no-spacing">

					<li>
						<?php // Show link (<a>) if user has ability to view the page
						if ( current_user_can( 'edit_courses' ) ) : ?>
							<a class="ldx-stat-block" href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-courses' ) ); ?>"><strong class="ldx-num"><?php echo $published_courses; ?></strong> <span class="ldx-after-num"><?php esc_html_e( LearnDash_Custom_Label::label_to_lower( 'courses' ), 'dashboard-widgets-learndash' ); ?></span></a>
						<?php // Show <span> if they don't
						else : ?>
							<span class="ldx-stat-block"><strong class="ldx-num"><?php echo $published_courses; ?></strong> <span class="ldx-after-num"><?php esc_html_e( LearnDash_Custom_Label::label_to_lower( 'courses' ), 'dashboard-widgets-learndash' ); ?></span></a>
						<?php endif; ?>
					</li>
					<li>
						<?php if ( current_user_can( 'edit_courses' ) ) : ?>
							<a class="ldx-stat-block" href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-lessons' ) ); ?>"><strong class="ldx-num"><?php echo $published_lessons; ?></strong> <span class="ldx-after-num"><?php esc_html_e( LearnDash_Custom_Label::label_to_lower( 'lessons' ), 'dashboard-widgets-learndash' ); ?></span></a>
						<?php else : ?>
							<span class="ldx-stat-block"><strong class="ldx-num"><?php echo $published_lessons; ?></strong> <span class="ldx-after-num"><?php esc_html_e( LearnDash_Custom_Label::label_to_lower( 'lessons' ), 'dashboard-widgets-learndash' ); ?></span></a>
						<?php endif; ?>
					</li>
					<li>
						<?php if ( current_user_can( 'edit_courses' ) ) : ?>
							<a class="ldx-stat-block" href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-topic' ) ); ?>"><strong class="ldx-num"><?php echo $published_topics; ?></strong> <span class="ldx-after-num"><?php esc_html_e( LearnDash_Custom_Label::label_to_lower( 'topics' ), 'dashboard-widgets-learndash' ); ?></span></a>
						<?php else : ?>
							<span class="ldx-stat-block"><strong class="ldx-num"><?php echo $published_topics; ?></strong> <span class="ldx-after-num"><?php esc_html_e( LearnDash_Custom_Label::label_to_lower( 'topics' ), 'dashboard-widgets-learndash' ); ?></span></a>
						<?php endif; ?>
					</li>
					<li style="border:0;">
						<?php if ( current_user_can( 'edit_courses' ) ) : ?>
							<a class="ldx-stat-block" href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-certificates' ) ); ?>"><strong class="ldx-num"><?php echo $published_certificates; ?></strong> <span class="ldx-after-num"><?php esc_html_e( 'certificates', 'dashboard-widgets-learndash' ); ?></span></a>
						<?php else : ?>
							<span class="ldx-stat-block"><strong class="ldx-num"><?php echo $published_certificates; ?></strong> <span class="ldx-after-num"><?php esc_html_e( 'certificates', 'dashboard-widgets-learndash' ); ?></span></a>
						<?php endif; ?>
					</li>

				</ul>

				<ul class="ldx-flex ldx-flex-column ldx-stats-totals ldx-no-spacing">

					<li>
						<?php if ( current_user_can( 'edit_courses' ) ) : ?>
							<a class="ldx-stat-block" href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-quiz' ) ); ?>"><strong class="ldx-num"><?php echo $published_quizzes; ?></strong> <span class="ldx-after-num"><?php esc_html_e( LearnDash_Custom_Label::label_to_lower( 'quizzes' ), 'dashboard-widgets-learndash' ); ?></span></a>
						<?php else : ?>
							<span class="ldx-stat-block"><strong class="ldx-num"><?php echo $published_quizzes; ?></strong> <span class="ldx-after-num"><?php esc_html_e( LearnDash_Custom_Label::label_to_lower( 'quizzes' ), 'dashboard-widgets-learndash' ); ?></span></a>
						<?php endif; ?>
					</li>
					<li>
						<?php if ( current_user_can( 'edit_courses' ) ) : ?>
							<a class="ldx-stat-block" href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-question' ) ); ?>"><strong class="ldx-num"><?php echo $published_questions; ?></strong> <span class="ldx-after-num"><?php esc_html_e( LearnDash_Custom_Label::label_to_lower( 'questions' ), 'dashboard-widgets-learndash' ); ?></span></a>
						<?php else : ?>
							<span class="ldx-stat-block"><strong class="ldx-num"><?php echo $published_questions; ?></strong> <span class="ldx-after-num"><?php esc_html_e( LearnDash_Custom_Label::label_to_lower( 'questions' ), 'dashboard-widgets-learndash' ); ?></span></a>
						<?php endif; ?>
					</li>				
					<li>
						<?php if ( current_user_can( 'edit_assignments' ) ) : ?>
							<a class="ldx-stat-block" href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-assignment' ) ); ?>"><strong class="ldx-num"><?php echo $published_assignments; ?></strong> <span class="ldx-after-num"><?php esc_html_e( 'assignments', 'dashboard-widgets-learndash' ); ?></span></a>
						<?php else : ?>
							<span class="ldx-stat-block"><strong class="ldx-num"><?php echo $published_assignments; ?></strong> <span class="ldx-after-num"><?php esc_html_e( 'assignments', 'dashboard-widgets-learndash' ); ?></span></a>
						<?php endif; ?>
					</li>
					<li style="border:0;">
						<?php if ( current_user_can( 'edit_essays' ) ) : ?>
							<a class="ldx-stat-block" href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-essays' ) ); ?>"><strong class="ldx-num"><?php echo $all_essays; ?></strong> <span class="ldx-after-num"><?php esc_html_e( 'essays', 'dashboard-widgets-learndash' ); ?></span></a>
						<?php else : ?>
							<span class="ldx-stat-block"><strong class="ldx-num"><?php echo $all_essays; ?></strong> <span class="ldx-after-num"><?php esc_html_e( 'essays', 'dashboard-widgets-learndash' ); ?></span></a>
						<?php endif; ?>
					</li>

				</ul>

				<ul class="ldx-flex ldx-flex-column ldx-stats-totals ldx-no-spacing">

					<li>
						<?php if ( current_user_can( 'edit_groups' ) ) : ?>
							<a class="ldx-stat-block" href="<?php echo esc_url( admin_url( 'edit.php?post_type=groups' ) ); ?>"><strong class="ldx-num"><?php echo $published_groups; ?></strong> <span class="ldx-after-num"><?php esc_html_e( 'groups', 'dashboard-widgets-learndash' ); ?></span></a>
						<?php else : ?>
							<span class="ldx-stat-block"><strong class="ldx-num"><?php echo $published_groups; ?></strong> <span class="ldx-after-num"><?php esc_html_e( 'groups', 'dashboard-widgets-learndash' ); ?></span></a>
						<?php endif; ?>
					</li>

					<li>
						<?php if ( current_user_can( 'edit_groups' ) ) : ?>
							<a class="ldx-stat-block" href="<?php echo esc_url( admin_url( 'users.php?role=group_leader' ) ); ?>"><strong class="ldx-num"><?php echo $group_leader_count; ?></strong> <span class="ldx-after-num"><?php esc_html_e( 'group leaders', 'dashboard-widgets-learndash' ); ?></span></a>
						<?php else : ?>
							<span class="ldx-stat-block"><strong class="ldx-num"><?php echo $group_leader_count; ?></strong> <span class="ldx-after-num"><?php esc_html_e( 'group leaders', 'dashboard-widgets-learndash' ); ?></span></a>
						<?php endif; ?>
					</li>

				</ul>

			</div>

		</div> <!-- .ldx-db-widget -->

	<?php } // function render_dashboard_widget

} // Class DWFL_Learndash_Overview_Dashboard_Widget

// Instantiate Widget
new DWFL_Learndash_Overview_Dashboard_Widget;