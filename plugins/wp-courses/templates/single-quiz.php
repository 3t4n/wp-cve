<?php get_header(); ?>
<div class="wpc-main">
	<div class="wpc-wrapper">
		<?php include 'template-parts/lesson-toolbar.php'; ?>
		<div class="wpc-flex-container">
			<div class="wpc-flex-content">
				<div class="wpc-flex-container">
					<div class="wpc-flex-12 wpc-flex-no-margin">
						<div class="wpc-material wpc-material-content">
							<?php
							while(have_posts()){

								the_post();

								$logged_in = is_user_logged_in();
								$user_id = get_current_user_ID();
								$lesson_id = get_the_ID();
								$title = get_the_title();
								$course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : wpc_get_first_connected_course($lesson_id, 'quiz-to-course');
						        $quiz_data = get_post_meta( $lesson_id, 'wpc-quiz-data', true );
								$json_quiz_data = json_encode( $quiz_data );
								$json_quiz_data = wpc_is_restricted($lesson_id) === true ? 'null' : $json_quiz_data;
								$welcome_text = get_post_meta( $lesson_id, 'wpc-quiz-welcome-message', true);
								$welcome_text = nl2br($welcome_text);
								$welcome_text = str_replace(array("\r", "\n"), '', $welcome_text);
								$max_attempts = get_post_meta( $lesson_id, 'wpc-quiz-max-attempts', true );
								$show_answers = get_post_meta( $lesson_id, 'wpc-quiz-show-answers', true );
								$show_score = get_post_meta( $lesson_id, 'wpc-quiz-show-score', true );
								$show_progress = get_post_meta( $lesson_id, 'wpc-quiz-progress-bar', true );
								$empty_answers = get_post_meta( $lesson_id, 'wpc-quiz-empty-answers', true );
								$attempts_remaining = wpcq_get_quiz_attempts_remaining($lesson_id, $user_id);

							}

					        $restriction = get_post_meta( $lesson_id, 'wpc-lesson-restriction', true );
					        $custom_logged_out_message = get_option('wpc_logged_out_message');

					        if($restriction == 'free-account' && !$logged_in){ ?>
					        	<p class="wpc-content-restricted wpc-free-account-required">
						        	<?php if(!empty($custom_logged_out_message)){
						        		echo wp_kses($custom_logged_out_message, 'post');
						        	} else { ?>
						            	<a href="<?php echo wp_login_url( get_permalink() );?>"><?php esc_html_e('Log in', 'wp-courses'); ?></a> <?php esc_html_e('or', 'wp-courses'); ?> <a href="<?php echo wp_registration_url(); ?>"><?php esc_html_e('Register', 'wp-courses'); ?></a> <?php esc_html_e('to view this quiz.', 'wp-courses');
						        	} ?>
					            </p>
					        <?php } else { ?>
					           	<div class="wpc-lesson-content">
					           		<?php the_content(); ?>
					           	</div>
					        <?php } ?>
					    </div>
				    </div>
				</div>
			</div>
			<div id="wpc-right-sidebar" class="wpc-flex-sidebar">
				<?php $lesson_nav = wp_kses(wpc_get_classic_lesson_navigation( $course_id, $user_id ), 'post'); ?>
		    	<?php echo wp_kses($lesson_nav, 'post'); ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
<script>
	jQuery(document).ready(function(){
		args = {
			quiz 				: <?php echo $json_quiz_data; ?>,
			content 			: <?php echo json_encode(get_the_content(null, false, $lesson_id)); ?>,
			title 				: "<?php echo $title; ?>",
			selector 			: '#wpc-fe-quiz-container',
			maxAttempts 		: <?php echo empty($max_attempts) ? 0 : (int) $max_attempts; ?>,
			attemptsRemaining	: <?php echo empty($attempts_remaining) || $attempts_remaining === 0 ? 'false' : esc_attr($attempts_remaining); ?>, // false for unlimited
			showScore 	 		: <?php echo empty($show_score) ? 'true' : esc_attr($show_score); ?>,
			showAnswers 		: <?php echo empty($show_answers) ? 'true' : esc_attr($show_answers); ?>,
			allowEmptyAnswers	: <?php echo empty($empty_answers) ? 'true' : esc_attr($empty_answers); ?>,
			showProgress 		: <?php echo empty($show_progress) ? 'true' : esc_attr($show_progress); ?>,
			firstAttempt 		: true,
			render 				: 'quiz', // or "editor"
			welcomeMessage 		: <?php echo !empty($welcome_text) ? "'" . wp_kses($welcome_text, 'post') . "'" : 'false'; ?>,
			userID 				: <?php if(is_user_logged_in()){ echo get_current_user_ID(); } else { echo 'null'; } ?>,
			quizID 				: <?php echo !empty( $lesson_id ) ? $lesson_id : 'null'; ?>,
			courseID 			: <?php echo isset($_GET['course_id']) && !empty($_GET['course_id']) ? (int) $_GET['course_id'] : "null" ; ?>
		}
		WPCQInit(args);
	});

	jQuery(document).ready(function($){
		new WPC_UI({
			loggedIn 			: <?php echo $logged_in === true ? 'true' : 'false'; ?>,
			userID 				: <?php echo $user_id === 0 ? 'false' : $user_id; ?>,
			onLoad 				: false,
			fixedToolbar 		: <?php echo get_option('wpc_fix_toolbar_top') == 'true' ? 'true' : 'false'; ?>,
			fixedToolbarOffset 	: <?php echo get_option('wpc_fixed_toolbar_offset') == 'true' ? 'true' : 'false'; ?>,
			adminBar 			: <?php echo is_admin_bar_showing() === true ? 'true' : 'false'; ?>,
		})
		UI_Controller.resizeIframe();
	});
</script>