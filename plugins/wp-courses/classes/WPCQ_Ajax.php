<?php

// remove old quiz class when was in WP Courses premium
add_action( 'wp_head', 'wpcq_remove_old_class' );
function wpcq_remove_old_class() {
    remove_action( 'init', 'wpcq_ajax_init');
}

class WPCQ_Ajax {

	public $user_id;
	public $quiz_id;
	public $result_id;
	public $score;
	public $course_id;
	public $quiz;

	public function __construct() {
		$this->user_id = get_current_user_id();
		$this->quiz_id = isset( $_POST['quizID'] ) ? (int) $_POST['quizID'] : null;
		$this->result_id = isset($_POST['resultID']) ? (int) $_POST['resultID'] : null;
		$this->score = isset( $_POST['scorePercent']) ? (int) $_POST['scorePercent'] : null;
		$this->quiz = isset($_POST['quiz']) ? $_POST['quiz'] : null;
		$this->course_id = isset($_POST['courseID']) ? (int) $_POST['courseID'] : null;

		add_action( 'wp_ajax_wpcq_save_quiz_results_action', array( $this, 'saveResult') );
		add_action( 'wp_ajax_wpcq_save_quiz_action', array( $this, 'saveQuiz') );
		add_action( 'wp_ajax_wpcq_get_quiz_result', array( $this, 'getResult') );

		add_action( 'wp_ajax_wpc_get_quiz', array( $this, 'getQuiz'));
		add_action( 'wp_ajax_nopriv_wpc_get_quiz', array( $this, 'getQuiz'));

	}

	function saveResult() {

		check_ajax_referer( 'wpc_nonce', 'security' );

		global $wpdb;

		$this->user_id = (int) $_POST['userID'];
		$this->quiz_id = (int) $_POST['quizID'];
		$this->score = (int) $_POST['scorePercent'];
		$this->quiz = json_encode($_POST['quiz']);
		$this->course_id = (int) $_POST['courseID'];

		wpc_push_completed($this->user_id, $this->quiz_id, 1);
			
		$table_name = $wpdb->prefix . 'wpc_quiz_results';
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'time' 			=> current_time( 'mysql' ), 
				'user_ID' 		=> $this->user_id,
				'quiz_ID' 		=> $this->quiz_id,
				'quiz_result' 	=> $this->quiz,
				'score_percent' => $this->score,
				'course_id'		=> $this->course_id
			), array('%s', '%d', '%d', '%s', '%d', '%d')
		);

		wp_die();
	}

	function saveQuiz() {
		check_ajax_referer( 'wpc_nonce', 'security' );
		$this->quiz_id = (int) $_POST['quizID'];
		$this->quiz = $_POST['quiz'];
		update_post_meta( (int) $this->quiz_id, 'wpc-quiz-data', $this->quiz);
		wp_die();
	}

	function getResult() {
		check_ajax_referer( 'wpc_nonce', 'security' );
		$this->result_id = (int) $_POST['resultID'];

		global $wpdb;
		$table_name = $wpdb->prefix . 'wpc_quiz_results';
		$sql = "SELECT quiz_result, score_percent FROM {$table_name} WHERE id = {$this->result_id} LIMIT 1";
		$results = $wpdb->get_results($sql);

		if(!empty($results)) {
			echo str_replace('\\\\', '', $results[0]->quiz_result);
		} else {
			echo false;
		}

		wp_die();

	}

	function getQuiz(){
		check_ajax_referer( 'wpc_nonce', 'security' );

		$quiz_id = (int) $_POST['id'];
		$course_id = (int) $_POST['course_id'];

		// Only allows $_POST['user_id'] to be passed if in admin area
		if( current_user_can( 'administrator' ) && isset($_POST['user_id']) ) {
			$this->user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : get_current_user_id();
		} else {
			$this->user_id = get_current_user_id();
		}

		$content = wpc_restrict_content($quiz_id, '<div id="wpc-fe-quiz-container"></div>', 'quiz'); // Returns free restriction message if applicable
		$content = apply_filters('wpc_lesson_content', $content, $quiz_id, $course_id);  // returns restriction messages for Woo and PMPro

        $quiz_data = get_post_meta( $quiz_id, 'wpc-quiz-data', true );
        $quiz_data = wpc_is_restricted($quiz_id) === true ? null : $quiz_data;

		$welcome_text = get_post_meta( $quiz_id, 'wpc-quiz-welcome-message', true);
		$welcome_text = nl2br($welcome_text);
		$welcome_text = str_replace(array("\r", "\n"), '', $welcome_text);

		$max_attempts = (int) get_post_meta( $quiz_id, 'wpc-quiz-max-attempts', true );
		$show_answers = get_post_meta( $quiz_id, 'wpc-quiz-show-answers', true );
		$show_score = get_post_meta( $quiz_id, 'wpc-quiz-show-score', true );
		$show_progress = get_post_meta( $quiz_id, 'wpc-quiz-progress-bar', true );
		$empty_answers = get_post_meta( $quiz_id, 'wpc-quiz-empty-answers', true );
		$attempts_remaining = (int) wpcq_get_quiz_attempts_remaining( $quiz_id, $this->user_id );

		$attempts_remaining = $attempts_remaining == 0 ? false : $attempts_remaining;

		$prev_next_ids = wpc_get_previous_and_next_lesson_ids($quiz_id, $course_id);
		$terms = get_the_terms($course_id, 'course-category');
		$status = wpc_get_lesson_status($this->user_id, $quiz_id);

		wpc_push_viewed($quiz_id, $course_id, $this->user_id);

		$icon = get_lesson_icon($this->user_id, $quiz_id, 'quiz');

		$quiz = array(

			'quiz_id'				=> $quiz_id,
			'course_id'				=> $course_id,
			'quiz'					=> $quiz_data,
			'title'					=> get_the_title($quiz_id),
			'content'				=> $content,
			'welcome_message'		=> $welcome_text,
			'max_attempts'			=> $max_attempts,
			'show_answers'			=> filter_var($show_answers, FILTER_VALIDATE_BOOLEAN),
			'show_score'			=> filter_var($show_score, FILTER_VALIDATE_BOOLEAN),
			'show_progress'			=> filter_var($show_progress, FILTER_VALIDATE_BOOLEAN),
			'allow_empty_answers'	=> filter_var($empty_answers, FILTER_VALIDATE_BOOLEAN),
			'attempts_remaining'	=> $attempts_remaining,
			'first_attempt'			=> wpcq_is_first_attempt($this->user_id, $quiz_id),
			'user_id'				=> $this->user_id,
			'prev_id'				=> $prev_next_ids['prev_id'],
			'next_id'				=> $prev_next_ids['next_id'],
			'viewed_status'			=> (int) $status['viewed'],
			'completed_status'		=> (int) $status['completed'],
			'viewedPercent'			=> wpc_get_percent_done($course_id, $this->user_id, 0),
			'completedPercent'		=> wpc_get_percent_done($course_id, $this->user_id, 1),
			'icon'					=> $icon,

		);

		echo json_encode($quiz);

		wp_die();

	}

}

add_action('init', 'wpcq_ajax');

function wpcq_ajax(){
	new WPCQ_Ajax();
}

?>