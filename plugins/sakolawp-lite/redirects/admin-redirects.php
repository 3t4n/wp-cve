<?php
if(current_user_can( 'read' )) {
	if(is_page( 'myaccount' ) || $wp->request === "myaccount") {
		$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/administrator/myaccount.php';
		return $new_template;
	}
	elseif($wp->request === "class_routine") {
		$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/administrator/class_routine.php';
		return $new_template;
	}
	elseif($wp->request === "news_post") {
		$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/administrator/news_post.php';
		return $new_template;
	}
	elseif($wp->request === "event_post") {
		$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/administrator/event_post.php';
		return $new_template;
	}
	elseif($wp->request === "edit_profile") {
		$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/parent/edit_profile.php';
		return $new_template;
	}
	elseif($wp->request === "view-user") {
		$new_template =  SAKOLAWP_PLUGIN_DIR . '/templates/administrator/view_user.php';
		return $new_template;
	}
}