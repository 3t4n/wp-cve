<?php
if(current_user_can( 'read' )) {
	if($user_active == 1) {
		if(is_page( 'myaccount' ) || $wp->request === "myaccount") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/myaccount.php';
			return $new_template;
		}
		elseif($wp->request === "class_routine") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/class_routine.php';
			return $new_template;
		}
		
		elseif($wp->request === "attendance_report") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/attendance_report.php';
			return $new_template;
		}
		elseif($wp->request === "report_attendance_view") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/report_attendance_view.php';
			return $new_template;
		}
	    elseif($wp->request === "online_exams") {
	        $new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/online_exams.php';
	        return $new_template;
	    }
	    elseif($wp->request === "online_exams_done") {
	        $new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/online_exams_done.php';
	        return $new_template;
	    }
	    elseif($wp->request === "view_exam_result") {
	        $new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/view_exam_result.php';
	        return $new_template;
		}
		elseif($wp->request === "edit_profile") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/edit_profile.php';
			return $new_template;
		}

		// news post type
		elseif($wp->request === "news_post") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/news_post.php';
			return $new_template;
		}
		// event post type
		elseif($wp->request === "event_post") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/event_post.php';
			return $new_template;
		}
		// marks
		elseif($wp->request === "marks") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/marks.php';
			return $new_template;
		}
		// view mark
		elseif($wp->request === "view_mark") {
			$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/view_mark.php';
			return $new_template;
		}
	}
	else {
		$new_template =  SAKOLAWP_PLUGIN_DIR . '/template/waiting.php';
		return $new_template;			
	}
}