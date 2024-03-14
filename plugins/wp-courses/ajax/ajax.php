<?php
// Add requirement ajax
add_action('admin_footer', 'wpc_action_add_requirement_javascript');

function wpc_action_add_requirement_javascript()
{ ?>
	<?php $ajax_nonce = wp_create_nonce("wpc-add-requirement-nonce"); ?>
	<script type="text/javascript">

		function renderRequirements(data) {

			var requirementData = JSON.parse(data);

			var courseSelect = jQuery('#wpc-hidden-course-select').clone(true);

			var html = '';

			html += '<div class="wpc-requirement wpc-metabox-item" data-requirement-id="' + requirementData[0].id + '">';

			html += '<label>' + WPCAdminTranslations.whenSomeone + ': </label>';
			html += '<select name="wpc-requirement-action" class="wpc-requirement-action">';
			html += ' <option value="views">' + WPCAdminTranslations.views + '</option>';
			html += ' <option value="completes">' + WPCAdminTranslations.completes + '</option>';
			html += ' <option value="scores">' + WPCAdminTranslations.scores + '</option>';
			html += ' </select>';

			html += ' <select name="wpc-requirement-type" class="wpc-requirement-type">';
			html += ' <option value="any-course">' + WPCAdminTranslations.anyCourse + '</option>';
			html += ' <option value="specific-course">' + WPCAdminTranslations.aSpecificCourse + '</option>';
			html += ' <option value="any-lesson">' + WPCAdminTranslations.anyLesson + '</option>';
			html += ' <option value="specific-lesson">' + WPCAdminTranslations.aSpecificLesson + '</option>';
			html += '<option value="any-module">' + WPCAdminTranslations.anyModule + '</option>';
			html += '<option value="specific-module">' + WPCAdminTranslations.aSpecificModule + '</option>';
			html += '<option value="any-quiz">' + WPCAdminTranslations.anyQuiz + '</option>';
			html += '<option value="specific-quiz">' + WPCAdminTranslations.aSpecificQuiz + '</option>';
			html += ' </select>';

			html += courseSelect.html();

			html += '<select class="wpc-requirement-lesson-select wpc-hide"><option value="none">' + WPCAdminTranslations.none + '</option></select>';

			html += '<label class="wpc-percent-label">' + WPCAdminTranslations.percent + ': </label><input name="wpc-percent" type="number" min="1" max="100" value="0" class="wpc-percent" novalidate/>';

			html += '<label class="wpc-times-label">' + WPCAdminTranslations.times + ': </label><input name="wpc-times" type="number" min="1" value="1" class="wpc-requirement-times" novalidate/>';

			html += '<div class="wpc-metabox-item"><button class="wpc-delete-requirement wpc-btn" type="button" data-requirement-id="' + requirementData[0].id + '"><i class="fa fa-trash"></i> ' + WPCAdminTranslations.deleteRequirement + '</button></div>';

			html += '</div>';

			jQuery('#wpc-requirements').append(html);

		}

		// pass an array of lesson IDs to return lesson select

		function lessonOptions(data) {
			var html = '';

			data = JSON.parse(data);

			html += '<option value="none">' + WPCAdminTranslations.none + '</option>'

			for (i = 0; i < data.length; i++) {
				html += '<option value="' + data[i].id + '">' + data[i].title + '</option>'
			}

			return html;
		}

		jQuery(document).ready(function ($) {

			jQuery('#wpc-add-new-requirement').click(function () {
				var data = {
					'security': "<?php echo esc_js($ajax_nonce); ?>",
					'action': 'add_requirement',
					'post_id': "<?php echo esc_js(get_the_ID()); ?>",
					'course_id': '',
					'lesson_id': '',
					'module_id': '',
					'required_action': 'views', // views or completes
					'type': 'any-course', // any-course, specific-course, any-lesson, specific-lesson
					'times': '1',
					'percent': '' // numeric value
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function (response) {
					wpcHideAjaxIcon();

					var notice = jQuery('.wpc-requirement-notice');

					if (notice.length > 0) {
						notice.hide();
					}

					renderRequirements(response);
				});
			});

		});
	</script>
	<?php
}
add_action('wp_ajax_add_requirement', 'wpc_add_requirement_action_callback');
function wpc_add_requirement_action_callback()
{

	if (!current_user_can('administrator')) {
		wp_die();
	}

	check_ajax_referer('wpc-add-requirement-nonce', 'security');

	global $wpdb;
	$table = $wpdb->prefix . 'wpc_rules';

	// insert new rule to db

	$wpdb->insert(
		$table,
		array(
			'post_id' => (int) $_POST['post_id'],
			'action' => sanitize_key($_POST['required_action']),
			'type' => sanitize_key($_POST['type']),
			'times' => (int) $_POST['times'],
			'percent' => (int) $_POST['percent'],
			'course_id' => (int) $_POST['course_id'],
			'lesson_id' => (int) $_POST['lesson_id'],
			'module_id' => (int) $_POST['module_id'],
		),
		array(
			'%d',
			'%s',
			'%s',
			'%d',
			'%d',
			'%d',
			'%d',
			'%d',
		)
	);

	// return all requirements so we can render them with JS

	$last_id = $wpdb->insert_id;

	$requirements = $wpdb->get_results(
		$wpdb->prepare(
			"
			SELECT id, post_id, course_id, action, type, times, percent 
			FROM $table
			WHERE id = %d
			",
			$last_id
		)
	);

	echo json_encode($requirements);

	wp_die(); // Required
}


// Delete requirement ajax
add_action('admin_footer', 'wpc_action_delete_requirement_javascript');

function wpc_action_delete_requirement_javascript()
{ ?>
	<?php $ajax_nonce = wp_create_nonce("wpc-delete-requirement-nonce"); ?>
	<script type="text/javascript">

		jQuery(document).ready(function ($) {

			jQuery(document).on('click', '.wpc-delete-requirement', function () {

				var clickedDelElem = $(this);

				var data = {
					'security': "<?php echo esc_js($ajax_nonce); ?>",
					'action': 'delete_requirement',
					'requirement_id': clickedDelElem.attr('data-requirement-id')
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function (response) {
					wpcHideAjaxIcon();
					var count = $('.wpc-requirement').length;

					clickedDelElem.parent().parent().remove();

					if (count === 1) {
						$('#wpc-requirements').html('<div class="wpc-requirement-notice" id="wpc-no-requirement-notice">Click "add requirement" to add your first requirement</div>');
					}

				});
			});

		});
	</script>
	<?php
}

add_action('wp_ajax_delete_requirement', 'wpc_delete_requirement_action_callback');
function wpc_delete_requirement_action_callback()
{

	if (!current_user_can('administrator')) {
		wp_die();
	}

	check_ajax_referer('wpc-delete-requirement-nonce', 'security');

	global $wpdb;

	$table = $wpdb->prefix . 'wpc_rules';
	$id = (int) $_POST['requirement_id'];

	$return = $wpdb->delete($table, array('id' => $id));

	if ($return !== false) {
		echo (int) $id;
	}

	wp_die(); // required
}


// Change requirement course ajax
add_action('admin_footer', 'wpc_action_change_requirement_course_javascript');

function wpc_action_change_requirement_course_javascript()
{ ?>
	<?php $ajax_nonce = wp_create_nonce("wpc-change-requirement-course-nonce"); ?>
	<script type="text/javascript">

		jQuery(document).ready(function ($) {

			jQuery(document).on('change', '.wpc-requirement-courses-select', function () {

				var changedElem = $(this);

				var requirementID = changedElem.parent().attr('data-requirement-id');

				var selectedCourseID = $(this).children('option:selected').val();

				var requirementType = $(this).siblings('.wpc-requirement-type').val();

				if (requirementType == 'specific-lesson' || requirementType == 'specific-module' || requirementType == 'specific-course' || requirementType == 'specific-quiz') {

					var data = {
						'security': "<?php echo esc_js($ajax_nonce); ?>",
						'action': 'change_requirement_course',
						'requirement_id': requirementID,
						'course_id': selectedCourseID,
						'type': requirementType
					}

					wpcShowAjaxIcon();

					jQuery.post(ajaxurl, data, function (response) {
						wpcHideAjaxIcon();

						var $lessonSelect = changedElem.siblings('.wpc-requirement-lesson-select');

						$lessonSelect.html(lessonOptions(response));

						console.log(response);

						if (requirementType == 'specific-lesson' || requirementType == 'specific-module' || requirementType == 'specific-quiz') {
							$lessonSelect.show();
						}

					});
				} else {
					// else, reset lesson list to val of null and hide the list
					changedElem.siblings('.wpc-requirement-lesson-select').html('<option value="none">' + WPCAdminTranslations.none + '</option>').hide();
				}


			});

		});
	</script>
	<?php
}

add_action('wp_ajax_change_requirement_course', 'wpc_change_requirement_course_action_callback');
function wpc_change_requirement_course_action_callback()
{
	if (!current_user_can('administrator')) {
		wp_die();
	}	

	check_ajax_referer('wpc-change-requirement-course-nonce', 'security');

	$type = sanitize_title_with_dashes($_POST['type']);

	if ($type == 'specific-module') {
		$connection_type = array('module-to-course');
	} elseif ($type == 'specific-quiz') {
		$connection_type = array('quiz-to-course');
	} else {
		$connection_type = array('lesson-to-course');
	}

	$args = array(
		'post_to' => (int) $_POST['course_id'],
		'connection_type' => $connection_type,
		'join' => false,
	);

	$lessons = wpc_get_connected($args);

	$newLessons = array();

	foreach ($lessons as $lesson) {
		array_push($newLessons, array(
			'id' => (int) $lesson->post_from,
			'title' => get_the_title((int) $lesson->post_from),
		)
		);
	}

	// save to db

	global $wpdb;

	$table_name = $wpdb->prefix . 'wpc_rules';

	$wpdb->update(
		$table_name,
		array('course_id' => (int) $_POST['course_id']),
		array('id' => (int) $_POST['requirement_id']),
		array('%d'),
		array('%d')
	);

	// returns lessons needed to populate dynamic select option requirements
	echo json_encode($newLessons);

	wp_die(); // required
}


// Change requirement action ajax
add_action('admin_footer', 'wpc_action_change_requirement_act_javascript');

function wpc_action_change_requirement_act_javascript()
{ ?>
	<?php $ajax_nonce = wp_create_nonce("wpc-change-requirement-action-nonce"); ?>
	<script type="text/javascript">

		jQuery(document).ready(function ($) {

			jQuery(document).on('change', '.wpc-requirement-action', function () {

				var requirementAction = $(this).val();

				var requirementID = $(this).parent().attr('data-requirement-id');

				var data = {
					'security': "<?php echo esc_js($ajax_nonce); ?>",
					'action': 'change_requirement_act',
					'requirement_id': requirementID,
					'requirement_action': requirementAction
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function (response) {
					wpcHideAjaxIcon();
				});
			});
		});
	</script>
	<?php
}

add_action('wp_ajax_change_requirement_act', 'wpc_change_requirement_act_action_callback');
function wpc_change_requirement_act_action_callback()
{

	if (!current_user_can('administrator')) {
		wp_die();
	}
	
	check_ajax_referer('wpc-change-requirement-action-nonce', 'security');

	// save to db

	global $wpdb;

	$table_name = $wpdb->prefix . 'wpc_rules';

	$wpdb->update(
		$table_name,
		array('action' => sanitize_title_with_dashes($_POST['requirement_action'])),
		array('id' => (int) $_POST['requirement_id']),
		array('%s'),
		array('%d')
	);

	wp_die(); // required
}



// Change requirement type ajax
add_action('admin_footer', 'wpc_action_change_requirement_type_javascript');

function wpc_action_change_requirement_type_javascript()
{ ?>
	<?php $ajax_nonce = wp_create_nonce("wpc-change-requirement-type-nonce"); ?>
	<script type="text/javascript">

		jQuery(document).ready(function ($) {

			jQuery(document).on('change', '.wpc-requirement-type', function () {

				var requirementType = $(this).val();

				var requirementID = $(this).parent().attr('data-requirement-id');

				var data = {
					'security': "<?php echo esc_js($ajax_nonce); ?>",
					'action': 'change_requirement_type',
					'requirement_id': requirementID,
					'type': requirementType
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function (response) {
					wpcHideAjaxIcon();
				});
			});
		});
	</script>
	<?php
}

add_action('wp_ajax_change_requirement_type', 'wpc_change_requirement_type_action_callback');
function wpc_change_requirement_type_action_callback()
{
	if (!current_user_can('administrator')) {
		wp_die();
	}
	
	check_ajax_referer('wpc-change-requirement-type-nonce', 'security');

	// save to db

	global $wpdb;

	$table_name = $wpdb->prefix . 'wpc_rules';

	$wpdb->update(
		$table_name,
		array('type' => sanitize_title_with_dashes($_POST['type'])),
		array('id' => (int) $_POST['requirement_id']),
		array('%s'),
		array('%d')
	);

	wp_die(); // required
}



// Change requirement times ajax
add_action('admin_footer', 'wpc_action_change_requirement_times_javascript');

function wpc_action_change_requirement_times_javascript()
{ ?>
	<?php $ajax_nonce = wp_create_nonce("wpc-change-requirement-times-nonce"); ?>
	<script type="text/javascript">

		jQuery(document).ready(function ($) {

			jQuery(document).on('change', '.wpc-requirement-times', function () {

				var requirementTimes = $(this).val();

				var requirementID = $(this).parent().attr('data-requirement-id');

				var data = {
					'security': "<?php echo esc_js($ajax_nonce); ?>",
					'action': 'change_requirement_times',
					'requirement_id': requirementID,
					'times': requirementTimes
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function (response) {
					wpcHideAjaxIcon();
				});
			});
		});
	</script>
	<?php
}

add_action('wp_ajax_change_requirement_times', 'wpc_change_requirement_times_action_callback');
function wpc_change_requirement_times_action_callback()
{
	if (!current_user_can('administrator')) {
		wp_die();
	}
	
	check_ajax_referer('wpc-change-requirement-times-nonce', 'security');

	// save to db

	global $wpdb;

	$table_name = $wpdb->prefix . 'wpc_rules';

	$wpdb->update(
		$table_name,
		array('times' => (int) $_POST['times']),
		array('id' => (int) $_POST['requirement_id']),
		array('%d'),
		array('%d')
	);

	wp_die(); // required
}



// Change requirement percent ajax
add_action('admin_footer', 'wpc_action_change_requirement_percent_javascript');

function wpc_action_change_requirement_percent_javascript()
{ ?>
	<?php $ajax_nonce = wp_create_nonce("wpc-change-requirement-percent-nonce"); ?>
	<script type="text/javascript">

		jQuery(document).ready(function ($) {

			jQuery(document).on('change', '.wpc-percent', function () {

				var requirementPercent = $(this).val();

				var requirementID = $(this).parent().attr('data-requirement-id');

				var data = {
					'security': "<?php echo esc_js($ajax_nonce); ?>",
					'action': 'change_requirement_percent',
					'requirement_id': requirementID,
					'percent': requirementPercent
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function (response) {
					wpcHideAjaxIcon();
				});
			});
		});
	</script>
	<?php
}

add_action('wp_ajax_change_requirement_percent', 'wpc_change_requirement_percent_action_callback');
function wpc_change_requirement_percent_action_callback()
{
	if (!current_user_can('administrator')) {
		wp_die();
	}
	
	check_ajax_referer('wpc-change-requirement-percent-nonce', 'security');

	// save to db

	global $wpdb;

	$table_name = $wpdb->prefix . 'wpc_rules';

	$wpdb->update(
		$table_name,
		array('percent' => (int) $_POST['percent']),
		array('id' => (int) $_POST['requirement_id']),
		array('%d'),
		array('%d')
	);

	wp_die(); // required
}



// Change requirement lesson ajax
add_action('admin_footer', 'wpc_action_change_requirement_lesson_javascript');

function wpc_action_change_requirement_lesson_javascript()
{ ?>
	<?php $ajax_nonce = wp_create_nonce("wpc-change-requirement-lesson-nonce"); ?>
	<script type="text/javascript">

		jQuery(document).ready(function ($) {

			jQuery(document).on('change', '.wpc-requirement-lesson-select', function () {

				var requirementType = $(this).siblings('.wpc-requirement-type').val();

				var requirementLessonID = $(this).val();

				var requirementID = $(this).parent().attr('data-requirement-id');

				var data = {
					'security': "<?php echo esc_js($ajax_nonce); ?>",
					'action': 'change_requirement_lesson',
					'requirement_type': requirementType,
					'requirement_id': requirementID,
					'lesson_id': requirementLessonID
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function (response) {
					wpcHideAjaxIcon();
				});
			});
		});
	</script>
	<?php
}

add_action('wp_ajax_change_requirement_lesson', 'wpc_change_requirement_lesson_action_callback');
function wpc_change_requirement_lesson_action_callback()
{
	if (!current_user_can('administrator')) {
		wp_die();
	}
	
	check_ajax_referer('wpc-change-requirement-lesson-nonce', 'security');

	$type = sanitize_title($_POST['requirement_type']);

	if ($type == 'any-module' || $type == 'specific-module') {
		$lesson_id = null;
		$module_id = (int) $_POST['lesson_id'];
	} else {
		$lesson_id = (int) $_POST['lesson_id'];
		$module_id = null;
	}

	// save to db

	global $wpdb;

	$table_name = $wpdb->prefix . 'wpc_rules';

	$wpdb->update(
		$table_name,
		array('lesson_id' => $lesson_id, 'module_id' => $module_id),
		array('id' => (int) $_POST['requirement_id']),
		array('%d', '%d'),
		array('%d')
	);

	wp_die(); // required
}


?>