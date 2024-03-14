<?php

	add_action( 'admin_head' , 'wpc_remove_old_quiz_metaboxes' );
	 
	function wpc_remove_old_quiz_metaboxes() {
	    // Remove old meta boxes from when quizzes were in premium version to avoid potential conflicts
		remove_meta_box( 'wpcq_quiz_meta', 'wpc-quiz', 'normal' );
		remove_meta_box( 'wpcq_quiz_options_meta', 'wpc-quiz', 'normal' );
	}

	// Add meta boxes
	function wpcq_add_meta_box() {
		add_meta_box(
			'wpcq_meta',
			esc_html__( 'Quiz', 'wp-courses-premium' ),
			'wpcq_meta_box_callback',
			'wpc-quiz',
			'normal',
			'high'
		);
	}

	function wpcq_options_meta_box() {
		add_meta_box(
			'wpcq_options_meta',
			esc_html__( 'Quiz Options', 'wp-courses-premium' ),
			'wpcq_options_meta_box_callback',
			'wpc-quiz',
			'normal',
			'core'
		);
	}

	add_action( 'add_meta_boxes', 'wpcq_options_meta_box' );
	add_action( 'add_meta_boxes', 'wpcq_add_meta_box' );

	function wpcq_options_meta_box_callback( $post ) {

		wp_nonce_field( 'wpc_save_quiz_options_meta_box_data', 'wpc_save_quiz_options_meta_box_data' );

		$settings = array(
		    'teeny' => true,
		    'textarea_rows' => 6,
		    'tabindex' => 2,
		    'textarea_name'	=> 'wpc_quiz_welcome_message',
		); 

		$welcome_text = get_post_meta( (int) $post->ID, 'wpc-quiz-welcome-message', true);
		$max_attempts = get_post_meta( (int) $post->ID, 'wpc-quiz-max-attempts', true );
		$show_answers = get_post_meta( (int) $post->ID, 'wpc-quiz-show-answers', true);
		$show_score = get_post_meta( (int) $post->ID, 'wpc-quiz-show-score', true);
		$show_progress = get_post_meta( (int) $post->ID, 'wpc-quiz-progress-bar', true);
		$empty_answers = get_post_meta( (int) $post->ID, 'wpc-quiz-empty-answers', true);

		?>

		<div class="wpc-flex-container wpc-material-item" style="margin-top: 40px;">
			<div class="wpc-flex-10">
				<h3 class="wpc-single-option-header">Maximum Attempts</h3>
				<label for="wpc-quiz-max-attempts">Maximum attempts allowed for this quiz.  Leave empty or set to 0 for unlimited attempts.  When set, users will be required to log in to take the quiz.</label>
			</div>
			<div class="wpc-flex-2">
				<input style="margin-top:20px;" class="wpc-wide-input" id="wpc-quiz-max-attempts" type="number" value="<?php echo (int) $max_attempts; ?>" name="wpc_quiz_max_attempts"/>
			</div>
		</div>

		<div class="wpc-flex-container wpc-material-item">
			<div class="wpc-flex-10">
				<h3 class="wpc-single-option-header">Show Answers</h3>
				<label for="wpc-quiz-max-attempts">If enabled, users will be able to see answers upon completion of the quiz.</label>
			</div>
			<div class="wpc-flex-2">
				<div class="wpc-option wpc-option-toggle">
					<label class="wpc-switch" for="wpc-quiz-show-answers">
						<input type="checkbox" id="wpc-quiz-show-answers" name="wpc_quiz_show_answers" value="true" <?php checked(esc_attr($show_answers), 'true'); ?>/>
						<div class="wpc-slider wpc-round"></div>
					</label>
				</div>
			</div>
		</div>

		<div class="wpc-flex-container wpc-material-item">
			<div class="wpc-flex-10">
				<h3 class="wpc-single-option-header">Show Score</h3>
				<label for="wpc-quiz-max-attempts">If enabled, score will be shown upon quiz completion.</label>
			</div>
			<div class="wpc-flex-2">
				<div class="wpc-option wpc-option-toggle">
					<label class="wpc-switch" for="wpc-quiz-show-score">
						<input type="checkbox" id="wpc-quiz-show-score" name="wpc_quiz_show_score" value="true" <?php checked(esc_attr($show_score), 'true'); ?>/>
						<div class="wpc-slider wpc-round"></div>
					</label>
				</div>
			</div>
		</div>

		<div class="wpc-flex-container wpc-material-item">
			<div class="wpc-flex-10">
				<h3 class="wpc-single-option-header">Show Progress Bar</h3>
				<label for="wpc-quiz-max-attempts">If enabled a progress bar will show which displays current quiz progress.</label>
			</div>
			<div class="wpc-flex-2">
				<div class="wpc-option wpc-option-toggle">
					<label class="wpc-switch" for="wpc-quiz-progress-bar">
						<input type="checkbox" id="wpc-quiz-progress-bar" name="wpc_quiz_progress_bar" value="true" <?php checked(esc_attr($show_progress), 'true'); ?>/>
						<div class="wpc-slider wpc-round"></div>
					</label>
				</div>
			</div>
		</div>

		<div class="wpc-flex-container wpc-material-item">
			<div class="wpc-flex-10">
				<h3 class="wpc-single-option-header">Allow Empty Answers</h3>
				<label for="wpc-quiz-max-attempts">If enabled, quiz can be submitted with empty answers.</label>
			</div>
			<div class="wpc-flex-2">
				<div class="wpc-option wpc-option-toggle">
					<label class="wpc-switch" for="wpc-quiz-empty-answers">
						<input type="checkbox" id="wpc-quiz-empty-answers" name="wpc_quiz_empty_answers" value="true" <?php checked(esc_attr($empty_answers), 'true'); ?>/>
						<div class="wpc-slider wpc-round"></div>
					</label>
				</div>
			</div>
		</div>

		<div class="wpc-flex-container wpc-material-item">
			<div class="wpc-flex-4">
				<h3 class="wpc-single-option-header">Welcome Message</h3>
				<label>Your welcome or quiz overview message users will see before taking the quiz.</label><br>
			</div>
			<div class="wpc-flex-8">
				<div style="margin-top:20px;">
					<?php wp_editor( wp_kses($welcome_text, 'post'), 'wpc_quiz_welcome_message', $settings); ?>
				</div>
			</div>
		</div>

	<?php }

	function wpcq_meta_box_callback( $post ) {

		wp_nonce_field( 'wpcq_save_meta_box_data', 'wpc_meta_box_nonce' );

		$quiz = get_post_meta( (int) get_the_ID(), 'wpc-quiz-data', true );

		?>

		<script>

			jQuery(document).ready(function(){

				args = {
					quiz 				: <?php echo !empty($quiz) ? json_encode($quiz) : 'null'; ?>,
					selector 			: '#wpc-be-quiz-container',
					attemptsRemaining	: false, // false for unlimited
					showScore			: true,
					firstAttempt 		: true,
					render 				: 'editor', // or "editor"
					welcomeMessage 		: false,
					quizID 				: <?php echo (int) $post->ID; ?>,

				}

				WPCQInit(args);

			});

		</script>

		<div id="wpc-quiz-toolbar" class="wpc-flex-toolbar wpc-toolbar-dark wpc-metabox-toolbar">

		<button type="button" id="wpc-add-question" class="wpc-btn wpc-btn-soft"><?php esc_html_e('Add Question', 'wp-courses-premium'); ?></button>
		<button type="button" id="wpc-delete-question" class="wpc-btn wpc-btn-soft" disabled="disabled"><?php esc_html_e('Delete', 'wp-courses-premium'); ?></button>
		<button type="button" id="wpc-quiz-question-img-button" class="wpc-btn wpc-btn-soft">Add Image</button>

		<select id="wpcq-question-type-select" class="wpc-select">
			<option value="multiple-choice"><?php esc_html_e('Multiple Choice', 'wp-courses-premium'); ?></option>
			<option value="multiple-answer"><?php esc_html_e('Multiple Answer', 'wp-courses-premium'); ?></option>
		</select>

		<?php 

				$pid = (int) get_the_ID(); 
				$post_status = get_post_status( $pid );

		?>

		<?php if( $post_status == 'publish' || $post_status == 'draft' || $post_status == 'pending' ) { ?>

			<button type="button" id="wpc-save-question" class="wpc-btn wpc-btn-solid"><?php esc_html_e('Save Quiz', 'wp-courses-premium'); ?></button>

		<?php } ?>


		</div>

		<div id="wpc-be-quiz-container"></div>

<?php }

function wpcq_save_meta_box_data( $post_id ) {

	if ( ! isset( $_POST['wpc_save_quiz_options_meta_box_data'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['wpc_save_quiz_options_meta_box_data'], 'wpc_save_quiz_options_meta_box_data' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( isset( $_POST['post_type'] ) && 'wpc-quiz' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', (int) $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', (int) $post_id ) ) {
			return;
		}
	}

	if(isset($_POST['wpc_quiz_max_attempts'])){
		update_post_meta( (int) $post_id, 'wpc-quiz-max-attempts', (int) $_POST['wpc_quiz_max_attempts'] );
	}

	if(isset($_POST['wpc-lesson-restriction'])){
		$restriction = sanitize_title_with_dashes( $_POST['wpc-lesson-restriction'] );
		update_post_meta( (int) $post_id, 'wpc-lesson-restriction', $restriction );
	}

	if(isset($_POST['wpc_quiz_welcome_message'])){
		update_post_meta( (int) $post_id, 'wpc-quiz-welcome-message', wp_filter_post_kses(addslashes($_POST['wpc_quiz_welcome_message'])));
	}

	$value = isset($_POST['wpc_quiz_show_answers']) ? 'true' : 'false';
	update_post_meta( (int) $post_id, 'wpc-quiz-show-answers', sanitize_title( $value ) );

	$value = isset($_POST['wpc_quiz_show_score']) ? 'true' : 'false';
	update_post_meta( (int) $post_id, 'wpc-quiz-show-score', sanitize_title( $value ) );

	$value = isset($_POST['wpc_quiz_progress_bar']) ? 'true' : 'false';
	update_post_meta( (int) $post_id, 'wpc-quiz-progress-bar', sanitize_title( $value ) );

	$value = isset($_POST['wpc_quiz_empty_answers']) ? 'true' : 'false';
	update_post_meta( (int) $post_id, 'wpc-quiz-empty-answers', sanitize_title( $value ) );
	
}
add_action( 'save_post', 'wpcq_save_meta_box_data', 11 );