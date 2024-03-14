<?php  

	function wpc_track_lesson() {
		$post_type = get_post_type();
		$logged_in = is_user_logged_in();
		if($logged_in && $post_type == 'lesson' || $logged_in && $post_type == 'wpc-quiz') {
			global $wpdb;

			$post_id = get_the_ID();
			$user_id = get_current_user_id();

			$time_now = time();

			if($post_type == 'lesson'){
				$course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : wpc_get_first_connected_course($post_id);
			} else {
				$course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : wpc_get_first_connected_course($post_id, 'quiz-to-course');
			}

			wpc_push_viewed($post_id, $course_id, $user_id);
		}
	}
	add_action("wp_head", "wpc_track_lesson", 10);

	function wpc_push_viewed($post_id, $course_id, $user_id) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpc_tracking';
		$sql = "SELECT post_id from $table_name WHERE post_id = $post_id And user_id = $user_id";
		$results = $wpdb->get_results($sql);

		$time_now = time();

		if($wpdb->num_rows <= 0) {
			$wpdb->insert(
	            $table_name, array(
	                "user_id"               => $user_id,
	                "post_id"               => $post_id,
	                "course_id"				=> $course_id,
	                "completed"             => 0,
	                "viewed_timestamp"    	=> $time_now,
	                "completed_timestamp"	=> 0
	            ), 
	            array("%d", "%d", "%d", "%d", "%d")
	        );
		} else {
			$wpdb->query($wpdb->prepare("UPDATE $table_name SET viewed_timestamp = %d WHERE user_id = $user_id AND post_id = $post_id AND course_id = $course_id", $time_now));
		}
		update_user_meta($user_id, 'wpc-last-viewed-course', $course_id);
	}

	function wpc_push_completed($user_id, $post_id, $status){
	    global $wpdb;
	    $table_name = $wpdb->prefix . "wpc_tracking";
	    $sql = "UPDATE $table_name SET completed_timestamp = %d, completed = %d WHERE user_id = $user_id AND post_id = $post_id";
	    $wpdb->query($wpdb->prepare($sql, time(), $status));
	}

	function wpc_get_lesson_status($user_id, $post_id) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wpc_tracking";
		$sql = "SELECT completed FROM $table_name WHERE $post_id = post_id AND user_id = $user_id LIMIT 1";
		$result = $wpdb->get_results($sql);
		if(!empty($result)) {
			$status = array(
				'viewed'	=> 1,
				'completed'	=> (int) $result[0]->completed
			);
		} else {
			$status = array(
				'viewed'	=> 0,
				'completed'	=> 0
			);
		}
		return $status;
	}

	function wpc_get_last_tracked_lesson($user_id, $view = 0) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wpc_tracking";
		if($view === 0){
			$sql = "SELECT post_id FROM $table_name ORDER BY viewed_timestamp DESC LIMIT 1";
		} else {
			$sql = "SELECT post_id FROM $table_name WHERE completed = 1 ORDER BY completed_timestamp DESC LIMIT 1";
		}
		$result = $wpdb->get_results($sql);
		return $result = !empty($result) ? $result[0]->post_id : null;
	}

	/**
    *
	* @param int $user_id User ID
	* @param bool $view 0 for viewed, 1 for completed
	* @param int $page Page you'd like to retrieve results from
	* @param int $posts_per_page The number of rows you'd like to retrieve
	* @param bool $include_quiz Set to true to include tracked quizzes in return
	* @return array Completed and viewed lessons and quizzes (if $include_quiz === true)
	*/

	function wpc_get_tracked_lessons_by_user($user_id, $view = 0, $page = 1, $posts_per_page = 1000000, $include_quiz = true) {
		global $wpdb;
		$offset = ($page - 1) * $posts_per_page;
		$table_name = $wpdb->prefix . "wpc_tracking";
		$table_name_2 = $wpdb->prefix . 'posts';

		if($view === 0) {
			if($include_quiz === true) {
				$sql = "SELECT {$table_name}.user_id, {$table_name}.course_id, {$table_name}.viewed_timestamp, {$table_name}.completed_timestamp, {$table_name}.completed, {$table_name}.post_id, {$table_name_2}.post_status, {$table_name_2}.post_type FROM {$table_name} INNER JOIN $table_name_2 ON {$table_name}.post_id={$table_name_2}.ID WHERE {$table_name}.user_id = {$user_id} AND {$table_name_2}.post_status = 'publish' ORDER BY viewed_timestamp DESC LIMIT $posts_per_page OFFSET $offset";
			} else {
				$sql = "SELECT {$table_name}.user_id, {$table_name}.course_id, {$table_name}.viewed_timestamp, {$table_name}.completed_timestamp, {$table_name}.completed, {$table_name}.post_id, {$table_name_2}.post_status, {$table_name_2}.post_type FROM {$table_name} INNER JOIN $table_name_2 ON {$table_name}.post_id={$table_name_2}.ID WHERE {$table_name}.user_id = {$user_id} AND {$table_name_2}.post_status = 'publish' AND {$table_name_2}.post_type != 'wpc-quiz' ORDER BY viewed_timestamp DESC LIMIT $posts_per_page OFFSET $offset";
			}
		} else {
			if($include_quiz === true) {
				$sql = "SELECT {$table_name}.user_id, {$table_name}.course_id, {$table_name}.viewed_timestamp, {$table_name}.completed_timestamp, {$table_name}.completed, {$table_name}.post_id, {$table_name_2}.post_status, {$table_name_2}.post_type FROM {$table_name} INNER JOIN $table_name_2 ON {$table_name}.post_id={$table_name_2}.ID WHERE {$table_name}.user_id = {$user_id} AND {$table_name_2}.post_status = 'publish' AND {$table_name}.completed = 1 ORDER BY viewed_timestamp DESC LIMIT $posts_per_page OFFSET $offset";
			} else {
				$sql = "SELECT {$table_name}.user_id, {$table_name}.course_id, {$table_name}.viewed_timestamp, {$table_name}.completed_timestamp, {$table_name}.completed, {$table_name}.post_id, {$table_name_2}.post_status, {$table_name_2}.post_type FROM {$table_name} INNER JOIN $table_name_2 ON {$table_name}.post_id={$table_name_2}.ID WHERE {$table_name}.user_id = {$user_id} AND {$table_name_2}.post_status = 'publish' AND {$table_name}.completed = 1 AND {$table_name_2}.post_type != 'wpc-quiz' ORDER BY viewed_timestamp DESC LIMIT $posts_per_page OFFSET $offset";
			}
		}
		$results = $wpdb->get_results($sql);
		return $results;
	}

	function wpc_get_viewed_lessons_per_day($days = 90, $view = 0) {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpc_tracking";
        $view = $view === 0 ? 0 : 1;
        $view_column_timestamp = $view === 0 ? 'viewed_timestamp' : 'completed_timestamp';
        $sql = "SELECT COUNT(FROM_UNIXTIME($view_column_timestamp, '%Y-%m-%d')) as y, FROM_UNIXTIME($view_column_timestamp, '%Y-%m-%d') as x FROM $table_name WHERE $view_column_timestamp > 0 AND completed = {$view} GROUP BY FROM_UNIXTIME($view_column_timestamp, '%Y-%m-%d') ORDER BY $view_column_timestamp DESC LIMIT {$days}";
        $results = $wpdb->get_results($sql);
        $results = array_reverse($results);
        return $results;
    }

    function wpc_get_active_users($num = 25, $view = 0, $time_length = 604800) {
    	global $wpdb;
    	$table_name = $wpdb->prefix . "wpc_tracking";
    	$table_name_2 = $wpdb->prefix . "users";

    	$time_now = time();
        $time_start = $time_now - $time_length;

    	// get viewed
    	if($view === 0) {
    		$sql = "SELECT COUNT({$table_name}.post_id) as y, {$table_name_2}.user_login as x FROM $table_name INNER JOIN $table_name_2 ON {$table_name}.user_id={$table_name_2}.ID WHERE viewed_timestamp >= $time_start GROUP BY {$table_name}.user_id ORDER BY y DESC LIMIT $num";
    	} else {
    		// get completed
    		$sql = "SELECT  COUNT({$table_name}.post_id) as y, {$table_name_2}.user_login as x FROM $table_name INNER JOIN $table_name_2 ON {$table_name}.user_id={$table_name_2}.ID WHERE completed = {$view} AND completed_timestamp >= $time_start GROUP BY {$table_name}.user_id ORDER BY y DESC LIMIT $num";
    	}

    	$results = $wpdb->get_results($sql);
    	return $results;
    }

    /**
    * checks whether or not someone has viewed of completed a lesson
	* @param int $post_id The lesson or quiz ID you'd like to check for being viewed or completed
	* @param array $tracking The user tracking data from wpc_get_tracked_lessons_by_user( (int) $user_id, (int) $view (int) $page, (int) $posts_per_page)
	* @param bool $view 0 for viewed lessons, 1 for completed lessons
	* @return bool If the lesson or quiz has been completed or viewed depending upon the passed $view parameter
	*/

    function wpc_has_done($post_id, $tracking, $view = 0) {
		$done = false;
		foreach($tracking as $tracked){
			if($view === 1) {
				if($tracked->post_id == $post_id && $tracked->completed == $view){
					$done = true;
					break;
				} else {
					$done = false;
				}
			} else {
				if($tracked->post_id == $post_id){
					$done = true;
					break;
				} else {
					$done = false;
				}
			}
			
		}
		return $done;
	}

	/**
	* @param int $user_id User ID
	* @param bool $view 0 for viewed lessons, 1 for completed lessons
	* @param bool $include_quiz true to include viewed and completed quizzes in result
	* @return int Total count of how many lessons have been viewed or completed
	*/

	function wpc_get_user_tracking_count($user_id, $view = 0, $include_quiz = true){
		$query = wpc_get_tracked_lessons_by_user($user_id, $view, 1, 1000000, $include_quiz);
		return count($query);
	}

	/**
	* @param int $course_id The course ID for the percentage complete you'd like to retrieve
	* @param int $user_id
	* @param bool $view 0 for viewed lessons, 1 for completed lessons
	* @return int Percentage complete for the passed course ID
	*/

	function wpc_get_percent_done($course_id, $user_id = null, $view = 0){

		if($course_id == -1) {
			return 0;
		}

		if($user_id == null){
			$user_id = get_current_user_id();
		}

		$args = array(
			'post_to'			=> $course_id,
			'connection_type'	=> array('lesson-to-course', 'quiz-to-course'),
			'join'				=> false,
		);

		$lessons = wpc_get_connected($args);

		$all_lessons_count = 0;

		if(!empty($lessons)){
			foreach($lessons as $lesson) {
				if(get_post_status($lesson->post_from) == 'publish'){
					$all_lessons_count++;
				}
			}
		}

		$count = 0;

		if($all_lessons_count === 0) {
			return 0;
		}

		foreach($lessons as $lesson) {
			$status = wpc_get_lesson_status($user_id, $lesson->post_from);
			$status = $view === 0 ? (int) $status['viewed'] : (int) $status['completed'];
			$status === 1 && get_post_status($lesson->post_from) == 'publish' ? $count++ : '';
		}

		$percent_done = $count > 0 ? ($count / $all_lessons_count) * 100 : 0;
		return (int) $percent_done;
	}

	/**
	* @param int $module_id The module ID for the percentage complete you'd like to retrieve
	* @param int $user_id
	* @param bool $view 0 for viewed lessons, 1 for completed lessons
	* @return int Percentage complete for the passed module ID
	*/

	function wpc_get_module_percent_done($module_id, $user_id, $view = 0) {
            global $wpdb;
            $table_name = $wpdb->prefix . "wpc_connections";
            $sql = "SELECT post_from FROM $table_name WHERE connection_type = 'lesson-to-module' AND post_to = $module_id";
            $results = $wpdb->get_results($sql, ARRAY_N);
            $all_lesson_ids_count = $wpdb->num_rows;
            $lesson_ids = array_map('end', $results);
            $lesson_ids = "'" . implode("', '", $lesson_ids) . "'";

            $table_name = $wpdb->prefix . "wpc_tracking";
            if($view === 0) {
				$sql = "SELECT post_id FROM $table_name WHERE post_id IN ( $lesson_ids ) AND user_id = $user_id";
            } else {
            	$sql = "SELECT post_id FROM $table_name WHERE post_id IN ( $lesson_ids ) AND user_id = $user_id AND completed = $view";
            }

            $results = $wpdb->get_results($sql);
            $tracked_lesson_ids = $wpdb->num_rows;

            return $tracked_lesson_ids === 0 || $all_lesson_ids_count === 0 ? 0 : ( $tracked_lesson_ids / $all_lesson_ids_count ) * 100;
	}

	function wpc_get_average_percent( $view = 0, $count = 5, $time_length = 7889229 ) {
    	$time_now = time();
        $time_start = $time_now - $time_length;

		global $wpdb;
		$user_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->users" );
		$table_name = $wpdb->prefix . "wpc_connections";
		$tracking_table = $wpdb->prefix . "wpc_tracking";

		$course_titles = array();
		$all_percent = array();

	    $course_args = array(
	        'post_type'         => 'course',
	        'nopaging'          => true,
	        'post_status'       => 'publish',
	        'posts_per_page'    => -1,
	    );

	    $course_query = new WP_Query($course_args);

	    while($course_query->have_posts()) {
	    	$course_query->the_post();
	    	$id = get_the_ID();
	    	$course_titles[] = get_the_title();

	    	$sql = "SELECT post_from FROM $table_name WHERE connection_type = 'lesson-to-course' AND post_to = $id";
	    	$wpdb->get_results($sql);
	    	$total_connected_count = $wpdb->num_rows;

	    	$sql = $view === 0 ? "SELECT user_id FROM $tracking_table WHERE course_id = $id" : "SELECT user_id FROM $tracking_table WHERE course_id = $id AND completed = 1";
	    	$wpdb->get_results($sql);
	    	$total_tracked = $wpdb->num_rows;

	    	if($total_connected_count !== 0 && $total_tracked !== 0) {
	    		$avg_lessons_viewed = $total_tracked / $user_count;
	    		$avg_percent = $avg_lessons_viewed / $total_connected_count;
	    	} else {
	    		$avg_percent = 0;
	    	}

	        $all_percent[] = $avg_percent * 100;
	    }

	    array_multisort($all_percent, SORT_DESC, SORT_NUMERIC, $course_titles);
	    // limit number of courses
	    $all_percent = array_slice($all_percent, 0, 5);
	    $course_titles = array_slice($course_titles, 0, 5);
	    return array($course_titles, $all_percent);
	}

?>