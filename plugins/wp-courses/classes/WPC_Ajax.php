<?php

class WPC_Ajax {

	public $user_id;
	public $courses_per_page;
	public $profile_results_per_page;

	public function __construct($user_id) {

		$this->user_id = $user_id;
		$this->courses_per_page = get_option( 'wpc_courses_per_page');
		$this->profile_results_per_page = 16;

		add_action( 'wp_ajax_nopriv_wpc_toggle_completed', array( $this, 'setCompletedStatus') );
		add_action( 'wp_ajax_nopriv_wpc_get_awards', array( $this, 'getAwards'));
		add_action( 'wp_ajax_nopriv_wpc_admin_html_lesson_nav', array( $this, 'adminLessonNav'));
		add_action( 'wp_ajax_nopriv_wpc_submit_comment', array( $this, 'submitComment'));

		add_action( 'wp_ajax_wpc_toggle_completed', array( $this, 'setCompletedStatus') );
		add_action( 'wp_ajax_wpc_get_awards', array( $this, 'getAwards'));
		add_action( 'wp_ajax_wpc_admin_html_lesson_nav', array( $this, 'adminLessonNav'));
		add_action( 'wp_ajax_wpc_submit_comment', array( $this, 'submitComment'));
		
	}

	function submitComment(){

		check_ajax_referer( 'wpc_nonce', 'security' );

		$logged_in = is_user_logged_in();
		$user_id = get_current_user_id();
		$current_user = wp_get_current_user();
		$lesson_id = (int) $_POST['id'];
		$name = sanitize_text_field($_POST['name']);
		$email = sanitize_email($_POST['email']);
		$url = sanitize_text_field($_POST['url']);
		$comment = sanitize_textarea_field($_POST['comment']);

		$require_name_email = get_option('require_name_email');
		$comment_registration = get_option('comment_registration');
		$comment_moderation = get_option('comment_moderation');
		$comment_previously_approved = get_option('comment_previously_approved');

		$comment_count = get_comments(array(
	        'user_id'   => $user_id,
	        'count'     => true, // return comment count instead of comments
	        'status'	=> 'approve',
	    ));

		$approved = 0;

		if($comment_moderation == 1) {
			$approved = 0;
		} else {
			$approved = 1;
		}

		if($comment_previously_approved == 1 && $comment_count > 0){
			$approved = 1;
		}

		if($comment_registration = get_option('comment_registration') == 1 && $logged_in === false) {
			echo json_encode('<div class="wpc-alert-message">' . __('You must be logged in to post a comment.', 'wp-courses') . '</div>');
			wp_die();
		}

		if(empty($comment)){
			echo json_encode('<div class="wpc-alert-message">' . __('Comments cannot be empty.', 'wp-courses') . '</div>');
			wp_die();
		}

		if(empty($name) && $require_name_email == 1){
			echo json_encode('<div class="wpc-alert-message">' . __('Please fill in your name before submitting a comment.', 'wp-courses') . '</div>');
			wp_die();
		}

		if(empty($email) && $require_name_email == 1){
			echo json_encode('<div class="wpc-alert-message">' . __('Please fill in your email address before submitting a comment.', 'wp-courses') . '</div>');
			wp_die();
		}

		if($logged_in === false){

			$data = array(
				'comment_author'		=> $name,
				'comment_author_email'	=> $email,
				'comment_author_url' 	=> $url,
				'comment_post_ID'		=> $lesson_id,
				'comment_content'		=> $comment,
				'comment_approved'		=> $approved,
			);
			$id = wp_insert_comment($data);

			$comment = get_comments(array(
				'post_id' 		=> $lesson_id,
				'comment_in' 	=> array($id),
				'orderby'		=> 'comment_date',
				'number'		=> 1,
			));

			if($approved == 1) {
				echo json_encode('<li class="wpc-comment" style="display: none;"><div class="wpc-comment-avatar">' . get_avatar($user_id, 32) . '</div><div class="wpc-comment-author">' . esc_html($comment[0]->comment_author) . '</div><div class="wpc-comment-date">' . esc_html($comment[0]->comment_date) . '</div><div class="wpc-comment-content">' . esc_html($comment[0]->comment_content) . '</div>' . $comment_count . ' </li>');
			} else {
				echo json_encode('<div class="wpc-alert-message">' . __('Your comment is awaiting approval.', 'wp-courses') . '</div>');
			}

			wp_die();
		}

		if($logged_in === true){
			$data = array(
				'comment_author'		=> $current_user->display_name,
				'comment_author_email'	=> $current_user->user_email,
				'user_id'				=> $user_id,
				'comment_post_ID'		=> $lesson_id,
				'comment_content'		=> $comment,
				'comment_approved'		=> $approved,
			);
			$id = wp_insert_comment($data);

			$comment = get_comments(array(
				'post_id'		=> $lesson_id,
				'comment_in' 	=> array($id),
				'orderby'		=> 'comment_date',
				'number'		=> 1,
			));

			if($approved == 1) {
				echo json_encode('<li class="wpc-comment" style="display: none;"><div class="wpc-comment-avatar">' . get_avatar($user_id, 32) . '</div><div class="wpc-comment-author">' . esc_html($comment[0]->comment_author) . '</div><div class="wpc-comment-date">' . esc_html($comment[0]->comment_date) . '</div><div class="wpc-comment-content">' . esc_html($comment[0]->comment_content) . '</div></li>');
			} else {
				echo json_encode('<div class="wpc-alert-message">' . __('Your comment is awaiting approval.', 'wp-courses') . '</div>');
			}

			wp_die();
		}

	}

	function getAwards(){
		check_ajax_referer( 'wpc_nonce', 'security' );
		$awards = wpc_get_awards();
		echo $awards;
		wp_die();
	}

	function setCompletedStatus(){

		check_ajax_referer( 'wpc_nonce', 'security' );

		// Only allows $_POST['user_id'] to be passed if in admin area
		if( current_user_can( 'administrator' ) && isset($_POST['user_id']) ) {
			$this->user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : get_current_user_id();
		} else {
			$this->user_id = get_current_user_id();
		}

		$status = (int)	$_POST['status'];
		$status = $status === 0 ? 1 : 0;
		$id = (int)	$_POST['id'];
		$course_id = (int) $_POST['course_id'];

		wpc_push_completed($this->user_id, $id, $status);

		$percent = wpc_get_percent_done($course_id, $this->user_id, 1);

		$result = array(
			'status' 	=> (int) $status,
			'percent'	=> (int) $percent,
			'icon'		=> get_lesson_icon($this->user_id, $id, 'lesson'),
			'class'		=> get_lesson_li_class($this->user_id, $id, null),
		);

		echo json_encode($result);

		wp_die();
	}

	function adminLessonNav(){

		check_ajax_referer( 'wpc_nonce', 'security' );

		$course_id = (int) $_POST['course_id'];

		$args = array(
			'post_to'           => $course_id,
	        'connection_type'   => array('lesson-to-course', 'module-to-course', 'quiz-to-course'),
	        'order_by'          => 'menu_order',
	        'order'             => 'asc',
	        'join'				=> true,
	        'join_on'			=> "post_from"
	    );

		$lessons = wpc_get_connected($args);

		$html = '<ul class="wpc-nav-list wpc-nav-list-contained wpc-admin-lesson-list">';

			foreach($lessons as $lesson) {

				if($lesson->post_type == 'wpc-module' ) {
					$html .= '<li data-id="' . $lesson->post_from . '" data-post-type="' . $lesson->post_type . '" data-course-id="' . $course_id . '" class="wpc-order-lesson-list-lesson wpc-nav-list-header ui-sortable-handle wpc-module-button"><i class="fa-solid fa-grip"></i> <input  class="wpc-input wpc-module-title-input" type="text" placeholder="Module Name" value="' . $lesson->post_title . '" class="wpc-module-title-input"><button type="button" class="wpc-delete-module wpc-btn wpc-btn-icon"><i class="fa fa-trash"></i></button></li>';
				} else if($lesson->post_type == 'lesson' || $lesson->post_type == 'wpc-quiz') {
					$html .= '<li data-id="' . $lesson->post_from . '" data-post-type="' . $lesson->post_type . '" data-course-id="' . $course_id . '"  class="wpc-order-lesson-list-lesson"><i class="fa-solid fa-grip"></i> ' . $lesson->post_title . '<a style="float:right;" href="' . get_edit_post_link($lesson->post_from) . '"> Edit</a></li>';
				}

			}

		$html .= '</ul>';

		echo $html;

		wp_die();

	}

}

add_action('init', 'wpc_ajax_init');

function wpc_ajax_init(){
	new WPC_Ajax(get_current_user_id());
}

?>