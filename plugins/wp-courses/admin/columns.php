<?php
// Courses
add_filter('manage_edit-course_columns', 'wpc_course_columns', 9, 1);

function wpc_course_columns($columns)
{
	$columns['title'] = esc_html__('Course', 'wp-courses');
	$columns = wpc_array_insert_after('title', $columns, 'lessons', esc_html__('Connected Lessons / Quizzes', 'wp-courses'));
	$columns = wpc_array_insert_after('lessons', $columns, 'teacher', esc_html__('Connected Teachers', 'wp-courses'));
	$columns = wpc_array_insert_after('teacher', $columns, 'woo', esc_html__('Connected WooCommerce Products', 'wp-courses'));
	unset($columns['date']);

	return $columns;
}

add_action('manage_course_posts_custom_column', 'wpc_manage_course_columns', 10, 2);

function wpc_manage_course_columns($column, $post_id)
{
	switch ($column) {
		case 'lessons':
			$lessons = wpc_get_connected_lessons($post_id);
			$list = wpc_lesson_collection($lessons);

			echo $list;
			break;

		case 'teacher':
			$args = array(
				'post_from' => $post_id,
				'connection_type' => array('course-to-teacher'),
				'order_by' => 'post_title',
				'order' => 'asc',
				'order_posts_table' => true,
				'join' => true,
				'join_on' => "post_to"
			);

			$teachers = wpc_get_connected($args);

			$list = wpc_teacher_collection($teachers);

			echo $list;
			break;

		case 'woo':
			if (is_plugin_active('wp-courses-premium/wp-courses-premium.php') == false) {
				$wooCell = 'Sell your courses via WooCommerce with our: <a target="_blank" href="https://wpcoursesplugin.com/cart/?add-to-cart=829" class="wpc-btn wpc-btn-solid wpc-premium-upgrade-small wpc-premium-upgrade-all"><i class="fa fa-shopping-cart"></i> Premium Plugin</a>';
			} else if (function_exists('wpc_get_connected_product_id') && class_exists('WooCommerce')) {
				$product_id = wpc_get_connected_product_id($post_id);

				$_product = wc_get_product($product_id);

				if ($_product) {
					$wooCell = esc_html($_product->get_name());
				} else {
					$wooCell = '—';
				}
			} else {
				$wooCell = '—';
			}

			echo $wooCell;
			break;
	}
}

// Lessons
add_filter('manage_edit-lesson_columns', 'wpc_lesson_columns', 9, 1);

function wpc_lesson_columns($columns)
{
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => esc_html__('Lesson', 'wp-courses'),
		'course' => esc_html__('Connected Courses', 'wp-courses'),
		'restriction' => esc_html__('Restriction', 'wp-courses')
	);

	if (is_plugin_active('wp-courses-premium/wp-courses-premium.php') == false) {
		$columns = wpc_array_insert_after('restriction', $columns, 'woo_pmp', esc_html__('WooCommerce / Paid Memberships Pro', 'wp-courses'));
	} 

	return $columns;
}

add_action('manage_lesson_posts_custom_column', 'wpc_manage_lesson_columns', 10, 2);

function wpc_manage_lesson_columns($column, $post_id)
{
	switch ($column) {
		case 'course':
			if (is_plugin_active('wp-courses-woocommerce/wp-courses-woocommerce.php')) {
				echo "<div class='wpc-warning'>You cannot connect lessons to courses until you update to WP Courses Premium 3.0 or later. <a href='https://wpcoursesplugin.com/lesson/upgrading-wp-courses-woocommerce-integration-for-3-0/?course_id=958'>More information can be found here</a>.<div>";
			} else {
				$course_ids = wpc_get_connected_course_ids($post_id);
				wpc_course_multiselect($course_ids, "course-selection[]", 'wpc-course-multiselect wpc-lesson-type-lesson');
			}

			break;

		case 'restriction':
			$radio_name = 'radio-' . $post_id;
			echo wpc_lesson_restriction_radio_buttons($post_id, $radio_name);

			break;

		case 'woo_pmp':
			echo '<b>Sell your lessons</b> via WooCommerce / Paid Memberships Pro<br>NEW: Setup <b>DRIP CONTENT!</b><br><a target="_blank" href="https://wpcoursesplugin.com/cart/?add-to-cart=829" class="wpc-btn wpc-btn-solid wpc-premium-upgrade-small wpc-premium-upgrade-all"><i class="fa fa-shopping-cart"></i> Premium Plugin</a>';
			break;
	}
}

// Quizzes
add_filter('manage_edit-wpc-quiz_columns', 'wpc_quiz_columns', 9, 1);

function wpc_quiz_columns($columns)
{
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => esc_html__('Quiz', 'wp-courses'),
		'course' => esc_html__('Connected Courses', 'wp-courses'),
		'restriction' => esc_html__('Restriction', 'wp-courses')
	);
	return $columns;
}

add_action('manage_wpc-quiz_posts_custom_column', 'wpc_manage_quiz_columns', 10, 2);

function wpc_manage_quiz_columns($column, $post_id)
{
	global $post;
	switch ($column) {
		case 'course':
			if (is_plugin_active('wp-courses-woocommerce/wp-courses-woocommerce.php')) {
				echo "<div class='wpc-warning'>You cannot connect quizzes to courses until you update to WP Courses Premium 3.0 or later. <a href='https://wpcoursesplugin.com/lesson/upgrading-wp-courses-woocommerce-integration-for-3-0/?course_id=958'>More information can be found here</a>.<div>";
			} else {
				$course_ids = wpc_get_connected_course_ids($post_id, 'quiz-to-course');
				wpc_course_multiselect($course_ids, "course-selection[]", 'wpc-course-multiselect wpc-lesson-type-quiz');
			}

			break;

		case 'restriction':
			$radio_name = 'radio-' . $post_id;
			echo wpc_lesson_restriction_radio_buttons($post_id, $radio_name);

			break;
	}
}

// Teachers
add_filter('manage_edit-teacher_columns', 'teacher_columns', 9, 1);

function teacher_columns($columns)
{
	$columns['title'] = esc_html__('Teachers', 'wp-courses');
	$columns = wpc_array_insert_after('title', $columns, 'courses', esc_html__('Connected Courses', 'wp-courses'));
	unset($columns['date']);

	return $columns;
}

add_action('manage_teacher_posts_custom_column', 'wpc_manage_teacher_columns', 10, 2);

function wpc_manage_teacher_columns($column, $post_id)
{
	global $post;
	switch ($column) {
		case 'courses':
			$args = array(
				'post_to' => $post_id,
				'connection_type' => array('course-to-teacher'),
				'order_by' => 'post_title',
				'order' => 'asc',
				'order_posts_table' => true,
				'join' => true,
				'join_on' => "post_from"
			);

			$teachers = wpc_get_connected($args);

			$list = wpc_course_collection($teachers);

			echo $list;
			break;
	}
}