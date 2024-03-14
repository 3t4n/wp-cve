<?php
if(current_user_can( 'read' )) {
	if($user_active == 1) {
		if(is_page( 'myaccount' ) || $wp->request === "myaccount") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/myaccount.php';
			return $new_template;
		}
		elseif($wp->request === "waiting") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/template/waiting.php';
			return $new_template;
		}
		elseif($wp->request === "my_routines") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/my_routines.php';
			return $new_template;
		}
		elseif($wp->request === "questions_bank") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/questions_bank.php';
			return $new_template;
		}
		elseif($wp->request === "add_new_question") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/add_new_question.php';
			return $new_template;
		}
		elseif($wp->request === "view_bank_question") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/view_bank_question.php';
			return $new_template;
		}
		elseif($wp->request === "edit_bank_question") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/edit_bank_question.php';
			return $new_template;
		}
		elseif($wp->request === "online_exams") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/online_exams.php';
			return $new_template;
		}
		elseif($wp->request === "marks") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/marks.php';
			return $new_template;
		}
		elseif($wp->request === "examroom") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/examroom.php';
			return $new_template;
		}
		elseif($wp->request === "exam_questions") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/exam_questions.php';
			return $new_template;
		}
		elseif($wp->request === "manage_attendance") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/manage_attendance.php';
			return $new_template;
		}
		elseif($wp->request === "manage_attendance_view") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/manage_attendance_view.php';
			return $new_template;
		}
		elseif($wp->request === "attendance_report") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/attendance_report.php';
			return $new_template;
		}
		elseif($wp->request === "report_attendance_view") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/report_attendance_view.php';
			return $new_template;
		}
		elseif($wp->request === "exam_edit") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/exam_edit.php';
			return $new_template;
		}
		elseif($wp->request === "exam_results") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/exam_results.php';
			return $new_template;
		}
		elseif($wp->request === "view_exam_result") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/view_exam_result.php';
			return $new_template;
		}
		elseif($wp->request === "view_exam_question") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/view_exam_question.php';
			return $new_template;
		}
		elseif($wp->request === "online_exams_done") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/online_exams_done.php';
			return $new_template;
		}
		elseif($wp->request === "edit_profile") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/edit_profile.php';
			return $new_template;
		}
		elseif($wp->request === "edit_profile") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/edit_profile.php';
			return $new_template;
		}
		elseif($wp->request === "event_post") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/event_post.php';
			return $new_template;
		}
		elseif($wp->request === "news_post") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/news_post.php';
			return $new_template;
		}
		elseif($wp->request === "view_homework_student") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/view_homework_student.php';
			return $new_template;
		}

		if($user_active == 1 && get_page_template_slug() == 'register-template.php') {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/teacher/dashboard.php';
			return $new_template;
		}
	}
	else {
		if(is_page( 'dashboard' ) || is_front_page()) {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/template/waiting.php';
			return $new_template;
		}
		else {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/template/waiting.php';
			return $new_template;
		}
	}
}