<?php
function wpc_remove_category_meta_box() {
    remove_meta_box( 'tagsdiv-course-category', 'course', 'side' );
}
add_action('add_meta_boxes', 'wpc_remove_category_meta_box');

function wpc_remove_difficulty_meta_box() {
    remove_meta_box( 'tagsdiv-course-difficulty', 'course', 'side' );
}
add_action('add_meta_boxes', 'wpc_remove_difficulty_meta_box');

function wpc_add_course_meta_box() {
	$screens = array( 'course' );

	foreach ( $screens as $screen ) {
		add_meta_box(
			'wpc_sectionid',
			esc_html( __( 'Course Details', 'wp-courses' ) ),
			'wpc_course_meta_box_callback',
			esc_html($screen),
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'wpc_add_course_meta_box' );

/*
** Add course lesson ordering meta box
*/
function wpc_add_course_lessons_meta_box() {
	$screens = array( 'course' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'wpc_course_lessons',
			esc_html( __( 'Manage Course Lessons', 'wp-courses' ) ),
			'wpc_course_lessons_meta_box_callback',
			esc_html($screen),
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'wpc_add_course_lessons_meta_box' );

function wpc_course_lessons_meta_box_callback( $post ) { ?>

	<button id="wpc-add-module" type="button" class="wpc-btn wpc-btn-solid" style="margin-bottom: 10px;"><?php _e('Add Module', 'wp-courses'); ?></button>
	<a href="post-new.php?post_type=lesson" class="wpc-btn">New Lesson</a>
	<a href="post-new.php?post_type=wpc-quiz" class="wpc-btn">New Quiz</a>

	<?php wpc_admin_lesson_list($post->ID); ?>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			wpcInitLessonSorting(<?php echo (int) $post->ID; ?>);
		});
	</script>

<?php }

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function wpc_course_meta_box_callback( $post ) {
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'wpc_save_meta_box_data', 'wpc_meta_box_nonce' );
	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, 'course-video', true ); ?>
	
	<div class="wpc-metabox-item">
		<label for="wpc_new_field"><?php esc_html_e( 'Video Embed Code (iframe)', 'wp-courses' ); ?></label>
		<textarea style="width:100%;" id="wpc_video_id" name="wpc_video_id"><?php echo wpc_sanitize_video($value); ?></textarea>
	</div>

	<?php

	global $post;
	$post_old = $post;

	$teachers = wpc_get_connected_teachers($post->ID);

	$query_args = array(
		'post_type'			=> 'teacher',
		'orderby'			=> 'title',
		'post_status'		=> 'publish',
		'posts_per_page'	=> -1,
	);
	
	$query = new WP_Query($query_args);

	if($teachers === false){
		$none_selected = 'selected';
	} else if(in_array(-1, $teachers)) {
		$none_selected = 'selected';
	} else {
		$none_selected = '';
	}

	echo '<div class="wpc-metabox-item"><label>Teacher</label><br><select id="teacher-select" name="teacher-selection[]" class="wpc-teacher-multiselect" multiple>';
	echo '<option value="-1" ' . selected(esc_attr($none_selected), 'selected')  . '>' . esc_html__('None', 'wp-courses') . '</option>';
	while($query->have_posts()){
		$query->the_post();
		$teacher_id = get_the_ID();
		foreach($teachers as $teacher){
			$selected = $teacher == $teacher_id ? true : false;
			if($selected != false) {
				break;
			}
		}
		echo '<option value="' . (int) $teacher_id . '" ' . selected(esc_attr($selected), true) . '>' . get_the_title() . '</option>';
	}
	wp_reset_postdata();
	echo '</select></div>';
	$admin_url = admin_url();
	echo '<div class="wpc-metabox-item"><a href="' . esc_url($admin_url . 'post-new.php?post_type=teacher') . '" class="page-title-action add-new button" style="margin-top: 8px">' . esc_html( __('Add New', 'wp-courses') ) . '</a></div>';
	// fixes strange issue with wrong slug being used
	$post = $post_old;
  	setup_postdata( $post );
  	// Course Category Dropdown
  	$tax_name = 'course-category';
    $taxonomy = get_taxonomy($tax_name);
	?>
	<div class="wpc-metabox-item">
		<label><?php esc_html_e('Course Category', 'wp-courses'); ?>:</label>
		<div class="tagsdiv" id="<?php echo esc_attr($tax_name); ?>">
		    <div class="jaxtag">
		    <?php 
		    // Use nonce for verification
		    wp_nonce_field( plugin_basename( __FILE__ ), 'course-category_noncename' );
		    $type_IDs = wp_get_object_terms( $post->ID, 'course-category', array('fields' => 'ids') );
		    if(!empty($type_IDs[0])){
		    	$id = $type_IDs[0];
		    } else {
		    	$id = '';
		    }
		    wp_dropdown_categories('taxonomy=course-category&hide_empty=0&orderby=name&name=course-category&show_option_none=Select Category&selected='. $id); ?>
		    <?php echo ' <a href="' . esc_url($admin_url . 'edit-tags.php?taxonomy=course-category&post_type=course') . '" class="page-title-action add-new button">' . esc_html( __('Add New', 'wp-courses') ) . '</a>'; ?>
		    </div>
		</div>
	</div>
	<?php 
	$tax_name = 'course-difficulty';
    $taxonomy = get_taxonomy($tax_name);
	?>
	<div class="wpc-metabox-item">
		<label><?php esc_html_e('Course Difficulty', 'wp-courses'); ?>:</label>
		<div class="tagsdiv" id="<?php echo esc_attr($tax_name); ?>">
		    <div class="jaxtag">
		    <?php 
		    // Use nonce for verification
		    wp_nonce_field( plugin_basename( __FILE__ ), 'course-difficulty_noncename' );
		    $type_IDs = wp_get_object_terms( $post->ID, 'course-difficulty', array('fields' => 'ids') );
		    if(!empty($type_IDs[0])){
		    	$id = $type_IDs[0];
		    } else {
		    	$id = '';
		    }
		    wp_dropdown_categories('taxonomy=course-difficulty&hide_empty=0&orderby=name&name=course-difficulty&show_option_none=Select Difficulty&selected='. $id); ?>
		   	<?php echo ' <a href="' . esc_url($admin_url . 'edit-tags.php?taxonomy=course-difficulty&post_type=course') . '" class="page-title-action add-new button">' . esc_html( __('Add New', 'wp-courses') ) . '</a>'; ?>
		    </div>
		</div>
	</div>
<?php }
/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function wpc_course_save_meta_box_data( $post_id ) {
	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */
	// Check if our nonce is set.
	if ( ! isset( $_POST['wpc_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wpc_meta_box_nonce'], 'wpc_save_meta_box_data' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'course' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
	
	if(isset($_POST['wpc_video_id'])){
		$my_data = wpc_sanitize_video($_POST['wpc_video_id']);
		update_post_meta( (int) $post_id, 'course-video', $my_data );
	}
	if(isset($_POST['course-category'])){
		$type_ID = sanitize_title_with_dashes($_POST['course-category']);
	  	$type = ( $type_ID > 0 ) ? get_term( $type_ID, 'course-category' )->slug : NULL;
	  	wp_set_object_terms( (int) $post_id , $type, 'course-category' );
	}
	if(isset($_POST['course-difficulty'])){
		$type_ID = sanitize_title_with_dashes($_POST['course-difficulty']);
	  	$type = ( $type_ID > 0 ) ? get_term( $type_ID, 'course-difficulty' )->slug : NULL;
	  	wp_set_object_terms( (int) $post_id , $type, 'course-difficulty' );
	}

	$teachers = !empty($_POST['teacher-selection']) ? array_map('intval', $_POST['teacher-selection']) : array(-1);

	// Don't allow selection of "none" as well as other connected teachers
	if(count($teachers) > 1	){
		$teachers = array_diff($teachers, array(-1));
	}

    $args = array(
		'post_from'			=> (int) $post_id,
		'post_to'			=> $teachers,
		'connection_type'	=> 'course-to-teacher',
        'exclude_from'      => wpc_get_connected_course_ids((int) $post_id, 'course-to-teacher')
	);

	wpc_create_connections($args);
}
add_action( 'save_post', 'wpc_course_save_meta_box_data' );