<?php

/*
** Lesson video meta box
*/

function wpc_add_lesson_video_meta_box() {
	add_meta_box(
		'wpc_lesson_video', // id
		esc_html(__( 'Lesson Video', 'wp-courses' )),
		'wpc_lesson_video_meta_box_callback',
		'lesson',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wpc_add_lesson_video_meta_box' );

function wpc_lesson_video_meta_box_callback( $post ) {

		wp_nonce_field('wpc_save_lesson_video_meta_box_data', 'wpc_lesson_video_meta_box_nonce');

		$video = get_post_meta( $post->ID, 'lesson-video', true );
		$video = wpc_sanitize_video($video);

  		do_action('wpc-before-lesson-meta');

		?>

		<label>
			<button type="button" class="wpc-question-btn wpc-btn wpc-btn-sm" data-content='If your lesson has a video, copy and paste the iframe from YouTube, Vimeo or another host here.'><i class="fa fa-question"></i></button>
			<?php esc_html_e('Video Embed Code', 'wp-courses'); ?> (iframe)
		</label>

		<br>

		<textarea style="width:100%;" id="wpc-lesson-video" name="wpc-lesson-video"><?php echo $video; ?></textarea>

<?php }

function wpc_save_lesson_video_meta_box_data($post_id) {

	// Check if nonce is set
	if ( ! isset( $_POST['wpc_lesson_video_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid
	if ( ! wp_verify_nonce( $_POST['wpc_lesson_video_meta_box_nonce'], 'wpc_save_lesson_video_meta_box_data' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions
	if ( isset( $_POST['post_type'] ) && 'lesson' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', (int) $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', (int) $post_id ) ) {
			return;
		}
	}

	// Save the data to post meta
	if ( isset( $_POST['wpc-lesson-video'] ) ) {
		$my_data = wpc_sanitize_video( $_POST['wpc-lesson-video'] );
		update_post_meta( (int) $post_id, 'lesson-video', $my_data );
	}

}

add_action( 'save_post', 'wpc_save_lesson_video_meta_box_data' );

/*
** connected course to lesson meta box
*/

function wpc_add_connected_course_to_lesson_meta_box() {
	$screens = array( 'lesson', 'wpc-quiz' );
		foreach ( $screens as $screen ) {
		add_meta_box(
			'wpc_connected_course_to_lesson', // id
			esc_html__( 'Connected Courses', 'wp-courses' ),
			'wpc_connected_course_to_lesson_meta_box_callback',
			esc_html($screen),
			'side',
			'high'
		);
	}
}

add_action( 'add_meta_boxes', 'wpc_add_connected_course_to_lesson_meta_box' );

function wpc_connected_course_to_lesson_meta_box_callback( $post ) { 

	wp_nonce_field('wpc_save_connected_course_to_lesson_meta_box_data', 'wpc_connected_course_to_lesson_meta_box_nonce'); ?>

	<?php 

		$post_type = get_post_type($post->ID);
		$connection_type = $post_type == 'lesson' ? 'lesson-to-course' : 'quiz-to-course';
		$course_ids = wpc_get_connected_course_ids($post->ID, $connection_type);

		if(is_plugin_active( 'wp-courses-woocommerce/wp-courses-woocommerce.php' )){
			echo "<div class='wpc-warning'>You cannot connect lessons to courses until you update to WP Courses Premium 3.0 or later.  <a href='https://wpcoursesplugin.com/lesson/upgrading-wp-courses-woocommerce-integration-for-3-0/?course_id=958'>More information can be found here</a>.</div>";
		 } else {
		 	echo '<div class="wpc-metabox-item">';
		 		echo '<button type="button" class="wpc-question-btn wpc-btn wpc-btn-sm" style="margin-bottom: 10px;" data-content="If you would like your lessons to appear in a course you have created, you will need to connect them to that course."><i class="fa fa-question"></i></button>';
				wpc_course_multiselect($course_ids, "course-selection[]", 'wpc-single-lesson-course-multiselect');
			echo '</div>';
		}

	?>

	<?php echo '<div class="wpc-metabox-item"><a href="' . esc_url(admin_url() . 'post-new.php?post_type=course') . '" class="button">' . esc_html__('Add New Course', 'wp-courses') . '</a></div>';
}


// Save connected course to lesson
function wpc_save_connected_course_to_lesson_meta_box_data( $post_id ) {

	// Check if our nonce is set
	if ( ! isset( $_POST['wpc_connected_course_to_lesson_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid
	if ( ! wp_verify_nonce( $_POST['wpc_connected_course_to_lesson_meta_box_nonce'], 'wpc_save_connected_course_to_lesson_meta_box_data' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions
	if ( isset( $_POST['post_type'] ) && 'lesson' == $_POST['post_type'] || isset( $_POST['post_type'] ) && 'wpc-quiz' == $_POST['post_type']) {
		if ( ! current_user_can( 'edit_page', (int) $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', (int) $post_id ) ) {
			return;
		}
	}

	$connection_type = $_POST['post_type'] == 'lesson' ? 'lesson-to-course' : 'quiz-to-course';
	$courses = !empty($_POST['course-selection']) ? array_map('intval', $_POST['course-selection']) : array(-1);

	// Don't allow selection of "none" as well as other connected courses
	if(count($courses) > 1	){
		$courses = array_diff($courses, array(-1));
	}

	$args = array(
		'post_from'			=> (int) $post_id,
		'post_to'			=> $courses,
		'connection_type'	=> $connection_type,
		'exclude_from'		=> wpc_get_connected_course_ids((int) $post_id, $connection_type)
	);

	wpc_create_connections($args);

}

add_action( 'save_post', 'wpc_save_connected_course_to_lesson_meta_box_data' );

/*
** Lesson restriction meta box
*/

function wpc_add_lesson_restriction_meta_box() {
	$screens = array( 'lesson', 'wpc-quiz' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'wpc_lesson_restriction',
			esc_html__( 'Lesson Restriction', 'wp-courses' ),
			'wpc_lesson_restriction_meta_box_callback',
			esc_html($screen),
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'wpc_add_lesson_restriction_meta_box' );

function wpc_lesson_restriction_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later
	wp_nonce_field( 'wpc_save_lesson_restriction_meta_box_data', "wpc_lesson_meta_box_nonce" );

	$restriction = sanitize_title_with_dashes( get_post_meta( (int) $post->ID, 'wpc-lesson-restriction', true ) );

	$display = $restriction != 'membership' ? 'display:none;' : '';

	?>

	<div id="wpc-lesson-restriction-container" style="position:relative;">
		<div id="wpc-lesson-restriction-overlay" style="<?php echo esc_attr($display); ?>"><?php echo esc_html__('Lesson Restriction Options Disabled if Membership Level(s) Checked', 'wp-courses'); ?>
		</div>

		<?php echo wpc_lesson_restriction_radio_buttons((int) $post->ID, $name = 'wpc-lesson-restriction', ''); ?>

	</div>

<?php }

// Save lesson restriction

function wpc_save_lesson_restriction_meta_box_data( $post_id ) {

	// Check if our nonce is set
	if ( ! isset( $_POST['wpc_lesson_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid
	if ( ! wp_verify_nonce( $_POST['wpc_lesson_meta_box_nonce'], 'wpc_save_lesson_restriction_meta_box_data' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions
	if ( isset( $_POST['post_type'] ) && 'lesson' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', (int) $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', (int) $post_id ) ) {
			return;
		}
	}

	if(isset($_POST['wpc-lesson-restriction'])){
		$restriction = sanitize_title_with_dashes( $_POST['wpc-lesson-restriction'] );
		update_post_meta( (int) $post_id, 'wpc-lesson-restriction', $restriction );
	}
}
add_action( 'save_post', 'wpc_save_lesson_restriction_meta_box_data', 10 );