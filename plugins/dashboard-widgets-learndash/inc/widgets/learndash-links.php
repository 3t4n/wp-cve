<?php

/**
 * LearnDash Links: Dashboard Widget
 * Quick access to most interior LearnDash pages, settings, etc.
 * 
 * @link https://www.cssigniter.com/make-wordpress-dashboard-widget/
 * @link https://generatewp.com/snippet/0mkyy7d/
 */

class DWFL_Learndash_Links_Dashboard_Widget {

	public function __construct() {
		
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );

	}

	public function add_dashboard_widget() {

		wp_add_dashboard_widget(
			'dwfl_learndash_links',
			__( 'LearnDash Links', 'dashboard-widgets-learndash' ),
			array( $this, 'render_dashboard_widget' )
		);

	}

	public function render_dashboard_widget() {

		/**
		 * Get Totals for Each Post Type
		 *
		 * We use this to only show sections if at least 1 piece of content
		 * exists within a particular post type.
		 */
		// Get Total Topics
		$count_topics = wp_count_posts( 'sfwd-topic' );
		$published_topics = $count_topics->publish;

		// Get Total Groups
		$count_groups = wp_count_posts( 'groups' );
		$published_groups = $count_groups->publish;

		// Get Total Quizzes
		$count_quizzes = wp_count_posts( 'sfwd-quiz' );
		$published_quizzes = $count_quizzes->publish;

		// Get Total Assignments
		$count_assignments = wp_count_posts( 'sfwd-assignment' );
		$published_assignments = $count_assignments->publish;

		// Get Total Essays
		$count_essays = wp_count_posts( 'sfwd-essays' );
		$graded_essays = $count_essays->graded;
		$notgraded_essays = $count_essays->not_graded;
		$all_essays = $graded_essays + $notgraded_essays


		/**
		 * Output Widget
		 */
		?>

		<div class="ldx-db-widget ldx-widget-links">

			<?php if ( current_user_can( 'manage_options' ) ) { ?>

				<div class="ldx-post-container">

					<div class="ldx-flex">

						<h3><?php _e( 'LearnDash Settings', 'dashboard-widgets-learndash' ); ?></h3>

					</div>

					<div class="ldx-action-links">

						<a href="<?php echo esc_url( admin_url( 'admin.php?page=learndash_lms_settings' ) ); ?>"><?php _e( 'General' ); ?></a> |

						<a href="<?php echo esc_url( admin_url( 'admin.php?page=learndash_lms_registration' ) ); ?>"><?php _e( 'Registration' ); ?></a> |

						<a href="<?php echo esc_url( admin_url( 'admin.php?page=learndash_lms_payments' ) ); ?>"><?php _e( 'Payments' ); ?></a> |

						<a href="<?php echo esc_url( admin_url( 'admin.php?page=learndash_lms_emails' ) ); ?>"><?php _e( 'Emails' ); ?></a> |

						<a href="<?php echo esc_url( admin_url( 'admin.php?page=learndash_lms_advanced&section-advanced=settings_custom_labels' ) ); ?>"><?php _e( 'Custom Labels' ); ?></a> |

						<a href="<?php echo esc_url( admin_url( 'admin.php?page=learndash_support' ) ); ?>"><?php _e( 'Support' ); ?></a>

					</div> <!-- .ldx-action-links -->

				</div> <!-- .ldx-post-container -->

			<?php } // if current_user_can( 'manage_options' ) ?>

			<?php if( current_user_can( 'edit_courses' ) ) { ?>

				<div class="ldx-post-container">

					<div class="ldx-flex">

						<h3><?php _e( LearnDash_Custom_Label::get_label( 'courses' ), 'dashboard-widgets-learndash' ); ?></h3>

					</div>

					<div class="ldx-action-links">

						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-courses' ) ); ?>"><?php _e( 'View All' ); ?></a> &nbsp;|&nbsp;

						<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=sfwd-courses' ) ); ?>"><?php _e( 'Add New' ); ?></a>

						<?php if( current_user_can( 'manage_options' ) ) { ?>

							&nbsp;|&nbsp; <a href="<?php echo esc_url( admin_url( 'admin.php?page=courses-options' ) ); ?>"><?php _e( 'Settings' ); ?></a>

						<?php } ?>

						<?php if( current_user_can( 'manage_categories' ) ) { ?>

							&nbsp;|&nbsp; <a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=ld_course_category&post_type=sfwd-courses' ) ); ?>"><?php _e( 'Categories' ); ?></a> &nbsp;|&nbsp;

							<a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=ld_course_tag&post_type=sfwd-courses' ) ); ?>"><?php _e( 'Tags' ); ?></a>

						<?php } ?>

					</div> <!-- .ldx-action-links -->

				</div> <!-- .ldx-post-container -->

				<div class="ldx-post-container">

					<div class="ldx-flex">

						<h3><?php _e( LearnDash_Custom_Label::get_label( 'lessons' ), 'dashboard-widgets-learndash' ); ?></h3>

					</div>

					<div class="ldx-action-links">

						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-lessons' ) ); ?>"><?php _e( 'View All' ); ?></a> &nbsp;|&nbsp;

						<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=sfwd-lessons' ) ); ?>"><?php _e( 'Add New' ); ?></a>

						<?php if( current_user_can( 'manage_options' ) ) { ?>

							&nbsp;|&nbsp; <a href="<?php echo esc_url( admin_url( 'admin.php?page=lessons-options' ) ); ?>"><?php _e( 'Settings' ); ?></a>

						<?php } ?>

						<?php if( current_user_can( 'manage_categories' ) ) { ?>

							&nbsp;|&nbsp; <a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=ld_lesson_category&post_type=sfwd-lessons' ) ); ?>"><?php _e( 'Categories' ); ?></a> &nbsp;|&nbsp;

							<a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=ld_lesson_tag&post_type=sfwd-lessons' ) ); ?>"><?php _e( 'Tags' ); ?></a>

						<?php } ?>						

					</div> <!-- .ldx-action-links -->

				</div> <!-- .ldx-post-container -->

				<?php if( $published_topics > 0 ) { ?>

					<div class="ldx-post-container">

						<div class="ldx-flex">

							<h3><?php _e( LearnDash_Custom_Label::get_label( 'topics' ), 'dashboard-widgets-learndash' ); ?></h3>

						</div>

						<div class="ldx-action-links">

							<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-topic' ) ); ?>"><?php _e( 'View All' ); ?></a> &nbsp;|&nbsp;

							<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=sfwd-topic' ) ); ?>"><?php _e( 'Add New' ); ?></a>

							<?php if( current_user_can( 'manage_options' ) ) { ?>

								&nbsp;|&nbsp; <a href="<?php echo esc_url( admin_url( 'admin.php?page=topics-options' ) ); ?>"><?php _e( 'Settings' ); ?></a>

							<?php } ?>

							<?php if( current_user_can( 'manage_categories' ) ) { ?>

								&nbsp;|&nbsp; <a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=ld_topic_category&post_type=sfwd-topic' ) ); ?>"><?php _e( 'Categories' ); ?></a> &nbsp;|&nbsp;

								<a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=ld_topic_tag&post_type=sfwd-topic' ) ); ?>"><?php _e( 'Tags' ); ?></a>

							<?php } ?>

						</div> <!-- .ldx-action-links -->

					</div> <!-- .ldx-post-container -->

				<?php } // if $published_topics ?>

				<?php if( $published_quizzes > 0 ) { ?>

					<div class="ldx-post-container">

						<div class="ldx-flex">

							<h3><?php _e( LearnDash_Custom_Label::get_label( 'quizzes' ), 'dashboard-widgets-learndash' ); ?></h3>

						</div>

						<div class="ldx-action-links">

							<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-quiz' ) ); ?>"><?php _e( 'View All' ); ?></a> &nbsp;|&nbsp;

							<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=sfwd-quiz' ) ); ?>"><?php _e( 'Add New' ); ?></a>

							<?php if( current_user_can( 'manage_options' ) ) { ?>

								&nbsp;|&nbsp; <a href="<?php echo esc_url( admin_url( 'admin.php?page=quizzes-options' ) ); ?>"><?php _e( 'Settings' ); ?></a>

							<?php } ?>

							<?php if( $all_essays > 0 && current_user_can( 'edit_essays' ) ) { ?>&nbsp;|&nbsp;

								<b style="color:#72777c;">Essays:</b> &nbsp;<a href="<?php echo esc_url( admin_url( 'edit.php?post_status=graded&post_type=sfwd-essays' ) ); ?>"><?php _e( 'Graded' ); ?></a> &nbsp;|&nbsp;

								<a href="<?php echo esc_url( admin_url( 'edit.php?post_status=not_graded&post_type=sfwd-essays' ) ); ?>"><?php _e( 'Not Graded' ); ?></a>

							<?php } // if $all_essays && can edit_essays ?>

						</div> <!-- .ldx-action-links -->

					</div> <!-- .ldx-post-container -->

				<?php } // if $published_quizzes ?>

			<?php } // if current_user_can( 'edit_courses' ) ?>

			<?php if( $published_assignments > 0 && current_user_can( 'edit_assignments' ) ) { ?>	

				<div class="ldx-post-container">

					<div class="ldx-flex">

						<h3><?php _e( 'Assignments', 'dashboard-widgets-learndash' ); ?></h3>

					</div>

					<div class="ldx-action-links">

						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-assignment' ) ); ?>"><?php _e( 'View All' ); ?></a> &nbsp;|&nbsp;

						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-assignment&approval_status=approved' ) ); ?>"><?php _e( 'Approved' ); ?></a> &nbsp;|&nbsp;

						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sfwd-assignment&approval_status=not_approved' ) ); ?>"><?php _e( 'Not Approved' ); ?></a>

						<?php if( current_user_can( 'manage_options' ) ) { ?>

							&nbsp;|&nbsp; <a href="<?php echo esc_url( admin_url( 'admin.php?page=assignments-options' ) ); ?>"><?php _e( 'Settings' ); ?></a>

						<?php } ?>

					</div> <!-- .ldx-action-links -->

				</div> <!-- .ldx-post-container -->

			<?php } // if $published_assignments && can edit_assignments ?>

			<?php if( $published_groups > 0 && current_user_can( 'edit_groups' ) ) { ?>

				<div class="ldx-post-container">

					<div class="ldx-flex">

						<h3><?php _e( 'Groups', 'dashboard-widgets-learndash' ); ?></h3>

					</div>

					<div class="ldx-action-links">

						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=groups' ) ); ?>"><?php _e( 'View All' ); ?></a> &nbsp;|&nbsp;

						<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=groups' ) ); ?>"><?php _e( 'Add New' ); ?></a>

						<?php if( current_user_can( 'group_leader' ) ) { ?> &nbsp;|&nbsp;

							<a href="<?php echo esc_url( admin_url( 'admin.php?page=groups-options' ) ); ?>"><?php _e( 'Settings' ); ?></a> &nbsp;|&nbsp;

							<a href="<?php echo esc_url( admin_url( 'admin.php?page=group_admin_page' ) ); ?>"><?php _e( 'Administration' ); ?></a>

						<?php } ?>

						<?php if( current_user_can( 'list_users' ) ) { ?> &nbsp;|&nbsp;

							<a href="<?php echo esc_url( admin_url( 'users.php?role=group_leader' ) ); ?>"><?php _e( 'Group Leaders' ); ?></a>

						<?php } ?>

					</div> <!-- .ldx-action-links -->

				</div> <!-- .ldx-post-container -->

			<?php } // if $published_groups && can edit_groups ?>

		</div> <!-- .ldx-db-widget -->

	<?php } // function render_dashboard_widget

} // Class DWFL_Learndash_Links_Dashboard_Widget

// Instantiate Widget
new DWFL_Learndash_Links_Dashboard_Widget;