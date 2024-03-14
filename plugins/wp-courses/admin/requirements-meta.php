<?php

// Requirements

add_action( 'add_meta_boxes', 'wpc_requirements_meta_box' );

function wpc_requirements_meta_box() {
	$views = array('wpc-badge', 'wpc-certificate', 'wpc-email');
	foreach($views as $view){
		add_meta_box(
			'wpc_requirements_wrapper', // id
			esc_html( __( 'Requirements', 'wp-courses' ) ),
			'wpc_requirements_meta_box_callback',
			esc_html($view),
			'normal',
			'low'
		);
	}
}

function wpc_requirements_meta_box_callback( $post ) {

		wp_nonce_field('wpc_save_requirements_meta_box_data', 'wpc_requirements_meta_box_nonce');

		?>

		<div class="wpc-flex-toolbar wpc-toolbar-dark wpc-metabox-toolbar">
			<button id="wpc-add-new-requirement" class="wpc-btn wpc-btn-soft" type="button"><?php esc_html_e('Add Requirement', 'wp-courses'); ?></button>
		</div>

		<div class="wpc-flex-container wpc-metabox-container">

			<div id="wpc-requirements" class="wpc-flex-12 wpc-flex-no-margin"> 
				
				<?php

					$results = wpc_get_rules(get_the_ID());

					if(!empty($results)){
						foreach($results as $result){ ?>
							<div class="wpc-requirement wpc-metabox-item" data-requirement-id="<?php echo (int) $result->id ?>">

								<label><?php esc_html_e('When Someone', 'wp-courses'); ?>: </label>
								<select name="wpc-requirement-action" class="wpc-requirement-action">
									 <option value="views" <?php selected(esc_attr($result->action), 'views'); ?>><?php esc_html_e('Views', 'wp-courses'); ?></option>

									 <option value="completes" <?php selected(esc_attr($result->action), 'completes'); ?>><?php esc_html_e('Completes', 'wp-courses'); ?></option>

									 <option value="scores" <?php selected(esc_attr($result->action), 'scores'); ?>><?php esc_html_e('Scores', 'wp-courses'); ?></option>
								 </select>

								 <select name="wpc-requirement-type" class="wpc-requirement-type">
									 <option value="any-course" <?php selected(esc_attr($result->type), 'any-course'); ?> class="<?php echo esc_attr($result->action == 'scores' ? 'wpc-hide' : ''); ?>"><?php esc_html_e('Any Course', 'wp-courses'); ?></option>

									 <option value="specific-course" <?php selected(esc_attr($result->type), 'specific-course'); ?> class="<?php echo esc_attr($result->action == 'scores' ? 'wpc-hide' : ''); ?>"><?php esc_html_e('A Specific Course', 'wp-courses'); ?></option>

									 <option value="any-lesson" <?php selected(esc_attr($result->type), 'any-lesson'); ?> class="<?php echo esc_attr($result->action == 'scores' ? 'wpc-hide' : ''); ?>"><?php esc_html_e('Any Lesson', 'wp-courses'); ?></option>

									 <option value="specific-lesson" <?php selected(esc_attr($result->type), 'specific-lesson'); ?> class="<?php echo esc_attr($result->action == 'scores' ? 'wpc-hide' : ''); ?>"><?php esc_html_e('A Specific Lesson', 'wp-courses'); ?></option>

									 <option value="any-module" <?php selected(esc_attr($result->type), 'any-module'); ?> class="<?php echo esc_attr($result->action == 'scores' ? 'wpc-hide' : ''); ?>"><?php esc_html_e('Any Module', 'wp-courses'); ?></option>

									 <option value="specific-module" <?php selected(esc_attr($result->type), 'specific-module'); ?> class="<?php echo esc_attr($result->action == 'scores' ? 'wpc-hide' : ''); ?>"><?php esc_html_e('A Specific Module', 'wp-courses'); ?></option>

									 <option value="any-quiz" <?php selected(esc_attr($result->type), 'any-quiz'); ?>><?php esc_html_e('Any Quiz', 'wp-courses'); ?></option>

									 <option value="specific-quiz" <?php selected(esc_attr($result->type), 'specific-quiz'); ?>><?php esc_html_e('A Specific Quiz', 'wp-courses'); ?></option>
								 </select>

								 <?php 

								 	$class = $result->type == 'any-module' || $result->type == 'any-lesson' || $result->type == 'any-course' || $result->type == 'any-quiz' ? ' wpc-hide' : '';
								 	echo wpc_get_course_dropdown($result->course_id, 'wpc-requirement-courses-select' . $class ); 

								?>

							 	<?php if(!empty($result->course_id && $result->course_id != null)){
							 
								    $args = array(
								        'post_to'           => $result->course_id,
								        'connection_type'   => array('lesson-to-course'),
								        'join'				=> false,
								    );

								    $lessons = wpc_get_connected($args);
						
									$args = array(
								        'post_to'           => $result->course_id,
								        'connection_type'   => array('module-to-course'),
								        'join'				=> false,
								    );

								    $modules = wpc_get_connected($args);

								    $args = array(
								        'post_to'           => $result->course_id,
								        'connection_type'   => array('quiz-to-course'),
								        'join'				=> false,
								    );

								    $quizzes = wpc_get_connected($args);						 		

							 		$class = $result->type != 'specific-lesson' && $result->type != 'specific-module' && $result->type != 'specific-quiz' ? 'wpc-hide' : '' ; ?>

						 			<select class="<?php echo esc_attr('wpc-requirement-lesson-select' . $class); ?>">
						 				<?php if($result->type == 'specific-lesson'){ ?>

								 			<?php foreach($lessons as $lesson){ ?>
								 				<?php $id = $lesson->post_from; ?>

												<option value="<?php echo (int) $id; ?>" <?php echo (int) $id == (int) $result->lesson_id ? ' selected' : ''; ?>>
								 				<?php echo get_the_title((int) $id); ?>
								 				</option>

											<?php } // end foreach ?>

										<?php } elseif($result->type == 'specific-module') { ?>

											<?php foreach($modules as $module){ ?>
												<?php $id = $module->post_from; ?>

												<option value="<?php echo (int) $id; ?>" <?php echo (int) $id == (int) $result->module_id ? ' selected' : ''; ?>>
								 				<?php echo get_the_title((int) $id); ?>
								 				</option>

											<?php } // end foreach ?>

										<?php } elseif($result->type == 'specific-quiz') { ?>

											<?php foreach($quizzes as $quiz){ ?>
												<?php $id = $quiz->post_from; ?>

												<option value="<?php echo (int) $id; ?>" <?php echo (int) $id == (int) $result->lesson_id ? ' selected' : ''; ?>>
								 				<?php echo get_the_title((int) $id); ?>
								 				</option>

											<?php } // end foreach ?>

										<?php } else { ?>
											<option value="none"><?php esc_html_e('none', 'wp-courses'); ?></option>
										<?php } ?>

									</select>		

								<?php } // end if ?>			

								<?php if( $result->type == 'specific-course' || $result->type == 'specific-module' || $result->type == 'any-course' || $result->type == 'any-module' ){
									$class = '';
								} elseif($result->action == 'views' && $result->type == 'any-quiz'){
									$class = 'wpc-hide';
								} elseif($result->action == 'completes' && $result->type == 'any-quiz'){
									$class = 'wpc-hide';
								} elseif($result->action == 'views' && $result->type == 'specific-quiz'){
									$class = 'wpc-hide';
								} elseif($result->action == 'completes' && $result->type == 'specific-quiz'){
									$class = 'wpc-hide';
								} elseif($result->action == 'scores' && $result->type == 'specific-quiz'){
									$class = '';
								} elseif($result->action == 'scores' && $result->type == 'any-quiz'){
									$class = '';
								} else {
									$class = 'wpc-hide';
								} ?> 		

								<label class="<?php echo esc_attr('wpc-percent-label ' . $class); ?>"><?php esc_html_e('Percent', 'wp-courses'); ?>: </label>
								<input type="number" min="0" max="100" value="<?php echo (int) $result->percent; ?>" class="<?php echo esc_attr('wpc-percent ' . $class); ?>"/>

								<?php $class = ($result->type == 'specific-course' || $result->type == 'specific-lesson' || $result->type == 'specific-module' || $result->type == 'specific-quiz') ? ' wpc-hide' : ''; ?>

								<label class="<?php echo esc_attr('wpc-times-label ' . $class); ?>"><?php esc_html_e('Times', 'wp-courses'); ?>: </label><input name="wpc-times" type="number" min="1" value="<?php echo (int) $result->times; ?>" class="<?php echo esc_attr('wpc-requirement-times ' . $class); ?>"/>
								
								<div class="wpc-metabox-item">
									<button class="wpc-delete-requirement wpc-btn" type="button" data-requirement-id="<?php echo (int) $result->id ?>"><i class="fa fa-trash"></i> <?php esc_html_e('Delete Requirement', 'wp-courses'); ?></button>
								</div>
								
							</div>

						<?php }
						} else {
							echo '<div class="wpc-requirement-notice" id="wpc-no-requirement-notice">' . esc_html( __('Click "add requirement" to add your first requirement', 'wp-courses') ) . '</div>';
						} ?>

			</div> <!-- badge requirements inside here -->
		</div>

		<?php echo '<div id="wpc-hidden-course-select">' . wpc_get_course_dropdown(null, 'wpc-requirement-courses-select wpc-hide') . '</div>';

	}

function wpc_save_requirements_meta_box_data($post_id) {

	// check if nonce is set.
	if ( ! isset( $_POST['wpc_requirements_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wpc_requirements_meta_box_nonce'], 'wpc_save_requirements_meta_box_data' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$post_types = array('wpc-badge', 'wpc-certificate', 'wpc-email');

	foreach($post_types as $type) {
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && $type == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
	}

	// save the data to post meta

}

add_action( 'save_post', 'wpc_save_requirements_meta_box_data' );

?>