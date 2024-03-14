<?php $user_id = get_current_user_id(); ?>
<?php $logged_in = is_user_logged_in(); ?>
<?php $lesson_id = get_the_ID(); ?>
<?php $course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : wpc_get_first_connected_course($lesson_id); ?>
<?php $prev_next_ids = wpc_get_previous_and_next_lesson_ids($lesson_id, $course_id); ?>
<?php $status = $logged_in === true ? wpc_get_lesson_status($user_id, $lesson_id) : false; ?>

<?php

	if( $status !== false ) {
		$completed_icon = $status['completed'] === 1 ? 'fa fa-check' : 'fa-regular fa-square';
		$completed_class = $status['completed'] === 1 ? 'wpc-marked-completed' : '';
	} else {
		$completed_icon = '';
		$completed_class = '';
	}

$has_attachments = wpc_has_attachments($lesson_id); ?>
<?php $show_completed_button = get_option('wpc_show_completed_lessons'); ?>
<?php $show_login_button = get_option('wpc_show_login_button'); ?>

<div id="wpc-toolbar-top" class="wpc-flex-toolbar wpc-flex-container">
	<div class="wpc-flex-4 wpc-flex-no-margin  wpc-flex-toolbar-left">
		<?php 
			$show_progress_bar = get_option('wpc_show_completed_lessons');

			if(is_user_logged_in() && $show_progress_bar === 'true' && $course_id != 'none'){
				echo '<div class="single-lesson-course-progress">' . wp_kses( wpc_get_progress_bar($course_id), 'post' ) . '</div>'; 
			} 

		 ?>
	</div>
	<div class="wpc-flex-8 wpc-flex-no-margin wpc-flex-toolbar-right">
		<div>
			<div class="wpc-ajax-filters-wrapper-right wpc-toolbar-buttons">

				<?php if($prev_next_ids['prev_id'] !== false){ ?>
					<a href="<?php echo add_query_arg( 'course_id', $course_id, get_the_permalink($prev_next_ids['prev_id'])); ?>" class="wpc-btn wpc-btn-soft"><i class="fa fa-arrow-left"></i> <?php _e('Prev', 'wp-courses'); ?></a>
				<?php } ?>

				<?php if($prev_next_ids['next_id'] !== false) { ?>
					<a href="<?php echo add_query_arg( 'course_id', $course_id, get_the_permalink($prev_next_ids['next_id'])); ?>" class="wpc-btn wpc-btn-soft"><?php _e('Next', 'wp-courses'); ?> <i class="fa fa-arrow-right"></i></a>
				<?php } ?>

				<?php if($logged_in === true && get_post_type() !== 'wpc-quiz' && wpc_is_restricted($lesson_id) === false && $show_completed_button == 'true') { ?>
					<button type="button" class="wpc-mark-completed wpc-btn wpc-btn-soft <?php echo $completed_class; ?>" data-id="<?php echo (int) $lesson_id; ?>" data-course-id="<?php echo (int) $course_id; ?>" data-status="<?php echo $status['completed']; ?>" title="Toggle Lesson Completion"><i class="<?php echo $completed_icon; ?>"></i></button>
				<?php } ?>

				<?php if( wpc_is_restricted($lesson_id) === false && $has_attachments === true ) { ?>
					<button class="wpc-btn wpc-btn-soft wpc-load-attachments" data-id="<?php echo (int) $lesson_id; ?>"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
				<?php } ?>

				<?php if(is_user_logged_in()) { ?>
					<button class="wpc-btn wpc-btn-soft wpc-open-sidebar wpc-load-profile-nav" data-visible="false"><i class="fa-solid fa-user"></i></button>
					<button type="button" class="wpc-btn wpc-btn-soft wpc-load-lesson-nav wpc-mobile-btn wpc-open-bottom-sidebar" title="Lesson Navigation" data-ajax="false" data-course-id="<?php echo (int) $course_id; ?>" data-location="bottom-toggle-sidebar"><i class="fa-solid fa-list"></i></button>
				<?php } else { ?>
					<?php if($show_login_button == 'true') { ?>
						<button class="wpc-btn wpc-load-login"><i class="fa-solid fa-right-to-bracket"></i> <?php _e('Log In', 'wp-courses'); ?></button>
					<?php } ?>
				<?php } ?>

			</div>
		</div>
	</div>	
</div>