<?php if ( empty( $args ) ) { return; } ?>

<?php $user_access = TaskBreakerCT::get_instance(); ?>
<?php $core = new TaskBreakerCore(); ?>
<?php $template = new TaskBreakerTemplate(); ?>

<?php
if ( ! empty( $args->user ) ) {

	// Only allow members who has an access view to view the task.
	if ( ! $user_access->can_see_project_tasks( $args->project_id ) ) { ?>

		<div id="task_breaker-single-task">
			<p class="info" id="message">
				<?php _e( 'Unable to access the task details. Only group members can access this page.', 'taskbreaker-project-management' ); ?>
			</p>
		</div>

		<?php return;
		
	} ?>

	<div id="task_breaker-single-task">

		<div id="task_breaker-single-task-details">

			<?php
				$priority_label = array(
					'1' => __( 'Normal', 'taskbreaker-project-management' ),
					'2' => __( 'High', 'taskbreaker-project-management' ),
					'3' => __( 'Critical', 'taskbreaker-project-management' ),
				);
			?>
			<?php
			// Task Meta.
			?>
			<div id="task-details-priority" class="task-priority <?php echo sanitize_title( $priority_label[ $args->priority ] ); ?>">
				<?php echo esc_html( $priority_label[ $args->priority ] ); ?>
			</div>

			<?php if ( 0 != $args->completed_by ) { ?>
				<div id="task-details-status" class="task-status completed">
					<?php esc_html_e( 'Completed', 'taskbreaker-project-management' ); ?>
				</div>
			<?php } else { ?>
				<div id="task-details-status" class="task-status open">
					<?php esc_html_e( 'Open', 'taskbreaker-project-management' ); ?>
				</div>
			<?php } ?>

			<!-- Task Title. -->
			<h2>
				<?php echo esc_html( $args->title ); ?>
			</h2>
			<!-- Task Title End. -->
			
			<!-- Task Deadline. -->
			<?php if ( $args->deadline ) { ?>
				<h5 id="single-task-deadline">
					<span class="deadline-label">
						<?php esc_html_e('Deadline: ', 'taskbreaker-project-management') ?>
						<?php echo esc_html( $args->deadline ); ?>
					</span>
					<span class="deadline-human-time-diff">
						<br/>
				 		<em>
				 			<?php $task_deadline = strtotime( str_replace('-', '', $args->deadline ) ); ?>
				 			<?php if ( current_time( 'timestamp' ) <= $task_deadline ) { ?>
				 				<?php printf( _x( '%s left', '%s = human-readable time difference', 'taskbreaker-project-management' ), human_time_diff( $task_deadline, current_time( 'timestamp' ) ) ); ?>
				 			<?php } else { ?>
				 				<strong>
				 					<?php esc_html_e('This task has already reached its deadline', 'taskbreaker-project-management'); ?>
				 				</strong>
				 			<?php } ?>
				 		</em>
				 	</span>
				</h5>
			<?php } ?>
			<!-- Task Deadline End. -->

			<span class="clearfix"></span>

			<?php
			// Task Content.
			?>
			<div class="task-content">
				<?php echo do_shortcode( $args->description ); ?>
			</div>

			<?php if ( ! empty( $args->assign_users ) ) { ?>
				<div class="task-members">
					<h5>
						<?php esc_attr_e( 'This task is assigned to:', 'taskbreaker-project-management' ); ?>
					</h5>
					<?php
						$assign_users = $core->parse_assigned_users( $args->assign_users );
					?>
					<ul class="task-members-items">
						<?php foreach ( $assign_users as $assign_user ) { ?>
							<li class="task-members-items-item">
								<a title="<?php esc_attr_e( $assign_user->display_name ); ?>" href="<?php echo esc_url( bp_core_get_userlink( $assign_user->ID, false, true ) );  ?>" class="task-members-items-item-link">
									<?php echo get_avatar( $assign_user->ID ); ?>
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>

			<?php $attachments = TaskBreakerFileAttachment::task_get_attached_files( $args->id, $args->user ); ?>

			<?php if ( ! empty( $attachments ) ) { ?>

				<div id="taskbreaker-file-attachment">
					<?php foreach( $attachments as $attachment ) { ?>
						<div class="taskbreaker-file-attachment-item">
							<a target="_blank" href="<?php echo esc_url( $attachment['url'] ); ?>" title="<?php esc_attr_e('Download File', 'taskbreaker-project-management'); ?>">
								<?php echo esc_html( $attachment['name'] ); ?>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php } ?>

			<div class="task-content-meta">

				<div class="alignright">
					<a href="#tasks" title="<?php _e( 'Tasks List', 'taskbreaker-project-management' ); ?>" class="button">
						<?php _e( '&larr; Tasks List', 'taskbreaker-project-management' ); ?>
					</a>

					<?php if ( $user_access->can_update_task( $args->project_id ) ) { ?>
						<a href="#tasks/edit/<?php echo intval( $args->id ); ?>" class="button">
							<?php _e( 'Edit', 'taskbreaker-project-management' ); ?>
						</a>
					<?php } ?>
				</div>

				<div class="clearfix"></div>

			</div>

		</div><!--#task_breaker-single-task-details-->

		<ul id="task-lists">

			<li class="task_breaker-task-discussion">
				<h3>
					<?php _e( 'Discussion', 'taskbreaker-project-management' ); ?>
				</h3>
			</li>

			<?php $comments = $core->get_tasks_comments( $args->id ); ?>

			<?php if ( ! empty( $comments ) ) { ?>
				<?php foreach ( $comments as $comment ) { ?>
					<?php echo $template->comments_template( $comment, (array) $args ); ?>
				<?php } ?>
			<?php } ?>

		</ul><!--#task-lists-->

		<?php if ( $user_access->can_add_task_comment( $args->project_id, $args->id ) ) { ?>

			<div id="task-editor">
				<div id="task-editor_update-status" class="task_breaker-form-field">
					<?php
					$completed = 'no';
					if ( absint( $args->completed_by ) !== 0 ) {
						$completed = 'yes';
					}
					?>
						<div id="comment-completed-radio">
							<?php if ( $completed === 'no' ) { ?>
							<div class="alignleft">
								<label for="ticketStatusInProgress">
									<input <?php echo $completed === 'no' ?  'checked': ''; ?> id="ticketStatusInProgress" type="radio" value="no" name="task_commment_completed">
									<small><?php _e( 'In Progress', 'taskbreaker-project-management' ); ?></small>
								</label>
							</div>
							<?php } ?>
							<div class="alignleft">
								<label for="ticketStatusComplete">
									<input <?php echo $completed === 'yes' ? 'checked': ''; ?> id="ticketStatusComplete" type="radio" value="yes" name="task_commment_completed">
									<small><?php _e( 'Completed', 'taskbreaker-project-management' ); ?></small>
								</label>
							</div>
							<?php if ( $completed === 'yes' ) { ?>
							<div class="alignleft">
								<label for="ticketStatusReOpen">
									<input id="ticketStatusReOpen" type="radio" value="reopen" name="task_commment_completed">
									<small><?php _e( 'Reopen Task', 'taskbreaker-project-management' ); ?></small>
								</label>
							</div>
							<?php } ?>
						</div>
						<!--On Complete -->
						<div id="task_breaker-comment-completed-radio" class="hide">
							<div class="alignleft">
								<label for="ticketStatusCompleteUpdate">
									<input disabled id="ticketStatusCompleteUpdate" type="radio" value="yes" name="task_commment_completed">
									<small><?php _e( 'Completed', 'taskbreaker-project-management' ); ?></small>
								</label>
							</div>
							<div class="alignleft">
								<label for="ticketStatusReOpenUpdate">
									<input disabled id="ticketStatusReOpenUpdate" type="radio" value="reopen" name="task_commment_completed">
									<small><?php _e( 'Reopen Task', 'taskbreaker-project-management' ); ?></small>
								</label>
							</div>
						</div>

						<!-- On ReOpen -->
						<div id="task_breaker-comment-reopen-radio" class="hide">
							<div class="alignleft">
								<label disabled for="ticketStatusReOpenInProgress">
									<input id="ticketStatusReOpenInProgress" type="radio" value="yes" name="task_commment_completed">
									<small><?php _e( 'In Progress', 'taskbreaker-project-management' ); ?></small>
								</label>
							</div>
							<div class="alignleft">
								<label disabled for="ticketStatusReOpenComplete">
									<input disabled id="ticketStatusReOpenComplete" type="radio" value="reopen" name="task_commment_completed">
									<small><?php _e( 'Complete', 'taskbreaker-project-management' ); ?></small>
								</label>
							</div>
						</div>

					<div class="clearfix"></div>
				</div>

				<div id="task-editor_update-content" class="task_breaker-form-field">
					<textarea placeholder="<?php esc_attr_e('Explain what this update is all about', 'taskbreaker-project-management'); ?>" id="task-comment-content" rows="5" width="100"></textarea>
				</div>

				<div id="task-editor_update-priority" class="task_breaker-form-field">
					<label for="task_breaker-task-priority-select" class="task_breaker-form-field">
						<?php _e( 'Update Priority:', 'taskbreaker-project-management' ); ?>
						<?php $core->task_priority_select( absint( $args->priority ),
							'task_breaker-task-priority-update-select',
							'task_breaker-task-priority-update-select' );
						?>
					</label>
				</div>

				<div id="task-editor_update-submit">
					<button type="button" id="updateTaskBtn" class="button">
						<?php _e( 'Update Task', 'taskbreaker-project-management' ); ?>
					</button>
				</div>

			</div><!--#task-editor-->
		<?php } else { ?>
			<div id="task-editor">
				<p class="error" id="message">
					<?php esc_attr_e( 'Only assigned members of this task or members with the right privilege are able to add progress to this task.', 'taskbreaker-project-management' ); ?>
				</p>
			</div>
		<?php } ?>
	</div>
<?php } ?>
