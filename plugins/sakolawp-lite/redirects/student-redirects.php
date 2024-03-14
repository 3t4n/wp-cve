<?php
if(current_user_can( 'read' )) {
	if($user_active == 1) {
		if(is_page( 'myaccount' ) || $wp->request === "myaccount") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/myaccount.php';
			return $new_template;
		}
		elseif($wp->request === "class_routine") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/class_routine.php';
			return $new_template;
		}
		elseif($wp->request === "waiting") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/template/waiting.php';
			return $new_template;
		}
		elseif($wp->request === "attendance_report") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/attendance_report.php';
			return $new_template;
		}
		elseif($wp->request === "report_attendance_view") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/report_attendance_view.php';
			return $new_template;
		}
		elseif($wp->request === "online_exams") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/online_exams.php';
			return $new_template;
		}
		elseif($wp->request === "examroom") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/examroom.php';
			return $new_template;
		}
		elseif($wp->request === "exam") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/exam.php';
			return $new_template;
		}
		elseif($wp->request === "online_exams_done") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/online_exams_done.php';
			return $new_template;
		}
		elseif($wp->request === "view_exam_result") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/view_exam_result.php';
			return $new_template;
		}
		elseif($wp->request === "edit_profile") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/edit_profile.php';
			return $new_template;
		}
		// news post type
		elseif($wp->request === "news_post") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/news_post.php';
			return $new_template;
		}
		// event post type
		elseif($wp->request === "event_post") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/event_post.php';
			return $new_template;
		}
		// my_marks
		elseif($wp->request === "my_marks") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/my_marks.php';
			return $new_template;
		}
		// view_mark
		elseif($wp->request === "view_mark") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/student/view_mark.php';
			return $new_template;
		}

		if($user_active == 1 && get_page_template_slug() == 'register-template.php') {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/dashboard.php';
			return $new_template;
		}
	}
	else {
		$new_template =  SAKOLAWP_PLUGIN_DIR . '/template/waiting.php';
		return $new_template;			
	}
}