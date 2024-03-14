<?php

function wpcq_get_results_table($user_ID, $page = 1, $posts_per_page = 50){

	ob_start();

		global $wpdb;
		$table_name = $wpdb->prefix . "wpc_quiz_results";

		$is_admin = is_admin();

		$offset = ($page - 1) * $posts_per_page;

		$sql = "SELECT id, user_ID, quiz_ID, quiz_result, score_percent, time, course_id FROM {$table_name} WHERE user_ID = {$user_ID} ORDER BY time DESC LIMIT {$posts_per_page} OFFSET {$offset}";
		$results = $wpdb->get_results($sql);

		$total_quiz_attempts = wpcq_count_all_quiz_attempts($user_ID);

		if($total_quiz_attempts > 0) {
			echo '<table class="wpc-table">';
				echo '<thead>
					<tr>
						<th>' . __('Title', 'wp-courses') . '</th>
						<th>' . __('Course', 'wp-courses') . '</th>
						<th>' . __('Date', 'wp-courses') . '</th>
						<th>' . __('Score', 'wp-courses') . '</th>
						<th></th>
					</tr>';
				echo '</thead>';

				echo '<tbody>';

					foreach($results as $result){

						$color = '#4f646d';

						$show_answers = get_post_meta($result->quiz_ID, 'wpc-quiz-show-answers', true);
						$show_score = get_post_meta($result->quiz_ID, 'wpc-quiz-show-score', true);

						if($is_admin){
							$show_score = 'true';
							$show_answers = 'true';
						}

						$first_connected_course = wpc_get_first_connected_course($result->quiz_ID);

						if($result->course_id != 'NULL' && !empty($result->course_id)) {
							$course_id = $result->course_id;
						} elseif($first_connected_course !== false){
							$course_id = $first_connected_course;
						} else {
							$course_id = false;
						}

						$title = $course_id !== false ? get_the_title($course_id) : '';

						if( $is_admin == true ) {
							$button_class = 'button';
							$url = add_query_arg( array('page' => 'manage_students', 'student_id' => (int) $user_ID, 'quiz_id' => (int) $result->id ), get_admin_url() . 'admin.php' );
						} else {
							$button_class = 'wpc-button';
							$url = add_query_arg( array('wpc_view' => 'quiz_results', 'student_id' => (int) $user_ID, 'quiz_id' => (int) $result->id ), get_the_permalink( get_the_ID() ));
						}

						echo '<tr>';
							echo '<td>' . get_the_title($result->quiz_ID) . '</td>';
							echo '<td>' . $title . '</td>';
							echo '<td>' . esc_html($result->time) . '</td>';
							echo '<td>';
								echo wpc_progress_bar($result->score_percent, '', $color);
							'</td>';
							echo '<td><button type="button" class="wpc-btn wpc-btn-sm wpc-load-quiz-result" data-id="' . (int) $result->id . '" data-score="' . $show_score . '" data-answers="' . $show_answers . '">' . __('Result', 'wp-courses') . '</button></td>';
						echo '</tr>';

					}

				echo '</tbody>';

			echo '</table>';

		} else {
			_e('No results', 'wp-courses');
		}

	$ob_str = ob_get_contents();
	ob_end_clean();

	return $ob_str;
	
}

function wpcq_get_quiz_attempts_remaining($quiz_id, $user_id){
	$max_attempts = (int) get_post_meta( $quiz_id, 'wpc-quiz-max-attempts', true );

	if($max_attempts === 0 || empty($max_attempts)){
		return 'false';
	}

	global $wpdb;
	$sql = "SELECT quiz_ID, user_ID FROM " . $wpdb->prefix . "wpc_quiz_results WHERE quiz_ID = {$quiz_id} AND {$user_id} = user_ID";
	$all_results = $wpdb->get_results($sql);
	$user_attempts = (int) $wpdb->num_rows;

	$attempts_remaining = $max_attempts - $user_attempts;
	$attempts_remaining = $attempts_remaining < 0 ? 0 : $attempts_remaining;

	return $attempts_remaining;

}

function wpcq_get_single_quiz_result($quiz_id, $user_id) {
	global $wpdb;
	$sql = "SELECT id, user_ID, quiz_ID, quiz_result, score_percent, time, course_id FROM " . $wpdb->prefix . "wpc_quiz_results WHERE id = {$quiz_id} AND $user_id = user_ID LIMIT 1";
	$results = $wpdb->get_results($sql);
	return $results[0];
}

function wpcq_count_all_quiz_attempts($user_id) {
	global $wpdb;
	$table_name = $wpdb->prefix . "wpc_quiz_results";
	$sql = "SELECT id FROM {$table_name} WHERE user_ID = {$user_id}";
	$results = $wpdb->get_results($sql);
	return count($results);
}

function wpcq_is_first_attempt($user_id, $quiz_id) {
	global $wpdb;
	$table_name = $wpdb->prefix . "wpc_quiz_results";
	$sql = "SELECT id FROM {$table_name} WHERE user_ID = {$user_id} AND quiz_ID = {$quiz_id}";
	$results = $wpdb->get_results($sql);
	if(count($results) > 0) {
		return true;
	} else {
		return false;
	}
}