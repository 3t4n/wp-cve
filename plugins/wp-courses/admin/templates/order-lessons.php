<div class="wrap">
	<div class="wpc-flex-container">
		<div class="wpc-flex-12">
			<div class="wpc-flex-toolbar wpc-toolbar-dark">
				<span style="margin-left: -10px;"><?php echo wpc_get_course_dropdown(-1, 'wpc-select wpc-lesson-sorting-course-select', false, ''); ?></span>
				<button id="wpc-add-module" style="display: none; margin-left: 10px; margin-left: 10px; position: relative; top: 3px;" type="button" class="wpc-btn wpc-btn-solid"><?php esc_html_e('Add Module', 'wp-courses'); ?></button>
			</div>
		</div>
	</div>
	<div class="wpc-flex-container">
		<div class="wpc-flex-12">
			<div id="wpc-lesson-sorting-container">
				<!-- lesson list loads here -->
			</div>
		</div>
	</div>
</div>

<?php 

// first course ID
global $wpdb;
$sql = 'SELECT DISTINCT ID, post_title, post_status FROM '.$wpdb->posts.' WHERE post_type = "course" AND post_status = "publish" OR post_type = "course" AND post_status = "draft" ORDER By post_title';
$results = $wpdb->get_results($sql);
$courseID = count($results) ? sanitize_text_field($results[0]->ID) : null;
$courseID = !empty($courseID) ? $courseID : 'null';
?>

<script>
	jQuery(document).ready(function($){
		wpcInitLessonSorting(<?php echo $courseID; ?>);
	});
</script>