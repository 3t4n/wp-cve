<?php
if ( ! function_exists( 'wrld_get_guest_message_on_reports_page' ) ) {
	function wrld_get_guest_message_on_reports_page( $content ) {
		$settings_data = get_option( 'wrld_settings', array() );
		if ( ! empty( $settings_data ) && isset( $settings_data['dashboard-access-roles'] ) ) {
			$gl_access         = isset( $settings_data['dashboard-access-roles']['group_leader'] ) ? $settings_data['dashboard-access-roles']['group_leader'] : true;
			$instructor_access = isset( $settings_data['dashboard-access-roles']['wdm_instructor'] ) ? $settings_data['dashboard-access-roles']['wdm_instructor'] : true;
		}
		if ( ( is_single() || is_page() ) && in_the_loop() && is_main_query() ) {
			global $post;
			if ( has_block( 'wisdm-learndash-reports/average-quiz-attempts' ) || has_block( 'wisdm-learndash-reports/course-completion-rate' ) || has_block( 'wisdm-learndash-reports/course-list' ) || has_block( 'wisdm-learndash-reports/daily-enrollments' ) || has_block( 'wisdm-learndash-reports/learner-pass-fail-rate-per-course' ) || has_block( 'wisdm-learndash-reports/pending-assignments' ) || has_block( 'wisdm-learndash-reports/quiz-completion-rate-per-course' ) || has_block( 'wisdm-learndash-reports/quiz-completion-time-per-course' ) || has_block( 'wisdm-learndash-reports/quiz-reports' ) || has_block( 'wisdm-learndash-reports/report-filters' ) || has_block( 'wisdm-learndash-reports/revenue-from-courses' ) || has_block( 'wisdm-learndash-reports/time-spent-on-a-course' ) || has_block( 'wisdm-learndash-reports/total-courses' ) || has_block( 'wisdm-learndash-reports/total-learners' ) || has_block( 'wisdm-learndash-reports/total-revenue-earned' ) ) {
				if ( ! is_user_logged_in() ) {
					$content = '
						<div class="ldrp-nodata-container">
							<div>
								<h2><strong>' . esc_html__( 'Welcome to the Reports Dashboard.', 'quiz_reporting_learndash' ) .
								'</strong></h2><br><div class="ldrp-no-data-text">' . esc_html__(
									'You can view this dashboard as both a LearnDash LMS admin as well as a Group Leader.	
								Click on either of the two buttons below to enter the dashboard and check Reports for your LearnDash LMS.',
									'quiz_reporting_learndash'
								) .
							'</div></div>
							<div class="login-buttons-for-demo">
								<a href="https://reports.wisdmlabs.com/learndash-reports/?admin_login=49">
								<button>Login As An Administrator</button>
								</a>
								<a href="https://reports.wisdmlabs.com/learndash-reports/?group_leader_login=3">
								<button>Login As A Group Leader</button>
								</a>
							</div>
						</div>';
				}
				$user = wp_get_current_user();
				if ( current_user_can( 'manage_options' ) ) {
					return $content;
				} elseif ( 0 != $user->ID && in_array( 'group_leader', (array) $user->roles, true ) && $gl_access ) {
					return $content;
				} elseif ( 0 != $user->ID && function_exists( 'ir_get_instructor_complete_course_list' ) && in_array( 'wdm_instructor', (array) $user->roles, true ) && $instructor_access ) {
					return $content;
				} else {
					$content = '
						<div class="ldrp-nodata-container">
							<div>
								<h2><strong>' . esc_html__( 'Welcome to the Reports Dashboard.', 'quiz_reporting_learndash' ) .
								'</strong></h2><br><div class="ldrp-no-data-text">' . esc_html__(
									'You can view this dashboard as both a LearnDash LMS admin as well as a Group Leader. 
	
								Click on either of the two buttons below to enter the dashboard and check Reports for your LearnDash LMS.',
									'quiz_reporting_learndash'
								) .
							'</div></div>
							<div class="login-buttons-for-demo">
								<a href="https://reports.wisdmlabs.com/learndash-reports/?admin_login=49">
								<button>Login As An Administrator</button>
								</a>
								<a href="https://reports.wisdmlabs.com/learndash-reports/?group_leader_login=3">
								<button>Login As A Group Leader</button>
								</a>
							</div>
						</div>';
				}
			}
		}
		return $content;
	}
}
