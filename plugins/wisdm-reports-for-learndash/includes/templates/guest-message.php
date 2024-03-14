<?php
if ( ! function_exists( 'wrld_get_guest_message_on_reports_page' ) ) {
	function wrld_get_guest_message_on_reports_page( $content ) {
		if ( ( is_single() || is_page() ) && in_the_loop() && is_main_query() ) {
			global $post;
			if ( has_block( 'wisdm-learndash-reports/average-quiz-attempts' ) || has_block( 'wisdm-learndash-reports/course-completion-rate' ) || has_block( 'wisdm-learndash-reports/course-list' ) || has_block( 'wisdm-learndash-reports/daily-enrollments' ) || has_block( 'wisdm-learndash-reports/learner-pass-fail-rate-per-course' ) || has_block( 'wisdm-learndash-reports/pending-assignments' ) || has_block( 'wisdm-learndash-reports/quiz-completion-rate-per-course' ) || has_block( 'wisdm-learndash-reports/quiz-completion-time-per-course' ) || has_block( 'wisdm-learndash-reports/quiz-reports' ) || has_block( 'wisdm-learndash-reports/report-filters' ) || has_block( 'wisdm-learndash-reports/revenue-from-courses' ) || has_block( 'wisdm-learndash-reports/time-spent-on-a-course' ) || has_block( 'wisdm-learndash-reports/total-courses' ) || has_block( 'wisdm-learndash-reports/total-learners' ) || has_block( 'wisdm-learndash-reports/total-revenue-earned' ) ) {
				if ( ! is_user_logged_in() ) {
					$content = '
                    <div class="ldrp-nodata-container wrld-error">
                        <div>
                            <strong>' . esc_html__( 'Access Denied.', 'learndash-reports-by-wisdmlabs' ) .
							'</strong>' . esc_html__( 'You need to be logged in to access this page.', 'learndash-reports-by-wisdmlabs' ) .
						'</div>
                    </div>';
				}
				$user              = wp_get_current_user();
				$settings_data     = get_option( 'wrld_settings', false );
				$dashboard_access  = ! empty( $settings_data ) && isset( $settings_data['dashboard-access-roles'] ) ? $settings_data['dashboard-access-roles'] : array();
				$gl_access         = isset( $dashboard_access['group_leader'] ) ? $dashboard_access['group_leader'] : false;
				$instructor_access = isset( $dashboard_access['wdm_instructor'] ) ? $dashboard_access['wdm_instructor'] : false;
				if ( false == $settings_data || ! isset( $settings_data['dashboard-access-roles'] ) ) {
					$gl_access         = true;
					$instructor_access = true;
				}
				if ( current_user_can( 'manage_options' ) ) {
					// update_option( 'wrld_reporting_page_visited', 1 );
					return $content;
				} elseif ( 0 != $user->ID && in_array( 'group_leader', (array) $user->roles, true ) && $gl_access ) {

					return $content;
				} elseif ( 0 != $user->ID && function_exists( 'ir_get_instructor_complete_course_list' ) && in_array( 'wdm_instructor', (array) $user->roles, true ) && $instructor_access ) {
					return $content;
				} else {
					$content = '
                    <div class="ldrp-nodata-container wrld-error">
                        <div>
                            <strong>' . esc_html__( 'Access Denied.', 'learndash-reports-by-wisdmlabs' ) .
							'</strong>' . esc_html__( ' You do not have a permission to access this page.', 'learndash-reports-by-wisdmlabs' ) .
						'</div>
                    </div>';
				}
			}
		}
		return $content;
	}
}




