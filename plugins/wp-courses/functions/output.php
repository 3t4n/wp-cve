<?php

	/** 
    * @param Int $id ID of the lesson or course
    * @param string $type Accepts post type of course or lesson
    * @return Course or lesson video iframe from lesson-video or course-video post_meta
    */

	function wpc_get_video($id, $type = 'lesson'){

		$video = $type == 'lesson' ? get_post_meta( $id, 'lesson-video', true ) : get_post_meta( $id, 'course-video', true );
	
		if(empty($video)){
			$video = false;
		} elseif( strpos( $video, '[' ) !== false ){
			$video = do_shortcode($video);
		} elseif( strpos( $video, 'iframe' ) !== false || strpos( $video, '<video' ) !== false ){
			// it's an iframe or <video>, so return as is
			$video = $video;
		} elseif(strpos($video, 'youtu.be' )){
			// it's a YT video with shortened url
			$video = str_replace('youtube.be/', 'https://www.youtube.com/watch?v=', $video);
			$video = wp_oembed_get( $video );
		}elseif( strpos($video, 'youtube.com' ) || strpos($video, 'vimeo.com')) {
			// it's a youtube or vimeo video using a url 
			$video = wp_oembed_get( $video );
		} elseif( preg_match("/[a-z]/i", $video) || preg_match("/[A-Z]/i", $video )){
			// it's a YT video with code only (ie. CvL5Amq0e8w)
			$video = '<iframe class="wpc-video" id="video-iframe" width="560" height="315" src="https://www.youtube.com/embed/' . $video . '" frameborder="0" allowfullscreen></iframe>';
		} elseif( preg_match("/[a-z]/i", $video) == 0 || preg_match("/[A-Z]/i", $video) == 0 ){ 
			// it's not a YT video with code only (ie. CvL5Amq0e8w).  Assumed to be Vimeo.
			$video = '<iframe class="wpc-video" id="video-iframe" src="https://player.vimeo.com/video/' . $video . '" width="500" height="216" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		}

		if(has_filter( 'wpc_lesson_video' )){
			$video = apply_filters( 'wpc_lesson_video', $video );
		}

		$video = wpc_sanitize_video($video);

		return $video;
		
	}

	/** 
    * Returns unordered list of all course categories
    * @param String $active_category_slug if category slug is passed, the class wpc-active-nav-item will be added to the active category li.  Pass true to select the "all" category.
    * @return Ul of all course categories
    */

	function wpc_get_course_category_list($active_category_slug = true){

		$categories = get_terms('course-category');

		$html = '<ul class="wpc-nav-list wpc-nav-list-contained">';
			$html .= '<li><a id="wpc-all-categories-button" href="' . get_post_type_archive_link( 'course' ) . '" class="' . $active_category_slug . '">' . esc_html( __('All', 'wp-courses') ) . '</a></li>';
			if(!empty($categories)){
				foreach($categories as $category){
					$html .= '<li><a href="' . get_term_link($category) . '" class="' . $active_category_slug . '">' . $category->name . '</a></li>';
				}
			} else {
				return false;
			}
		$html .= '</ul>';

		if( has_filter( 'wpc_course_category_list' ) ){
			$html = apply_filters( 'wpc_course_category_list', $html );
		}

		return $html;
	}


	/**
    * @return Unordered list of all published and draft courses
    */

	function wpc_get_course_list(){
		$course_args = array(
			'post_type'			=> 'course',
			'nopaging' 			=> true,
			'order'				=> 'ASC',
			'orderby'			=> 'menu_order',
			'post_status'		=> array('publish', 'draft'),
		);
		$course_query = new WP_Query($course_args);
		$html = '<ul class="wpc-nav-list wpc-nav-list-contained wpc-course-list">';
		while($course_query->have_posts()){
			$course_query->the_post();
			$post_id = get_the_ID();
			$display_status = (get_post_status( $post_id ) == 'draft') ? ' (draft)' : '';
			$html .= '<li class="wpc-course-list-li" data-id="' . $post_id . '"><i class="fa-solid fa-grip"></i> ' . get_the_title() . $display_status . '</li>';
		}
		$html .= '</ul>';
		wp_reset_postdata();
		return $html;
	}

	/** 
    * Returns radio buttons for setting lesson restriction
    * @param int $lesson_id Tthe lesson you'd like to set the restriction for
    * @param string $name Sets the name parameter value for the radio buttons
    * @param string $class Assigns a class to each radio button
    * @return Radio buttons for restricting lesson content
    */

	function wpc_lesson_restriction_radio_buttons($lesson_id, $name = 'wpc-lesson-restriction', $class = 'lesson-restriction-radio'){

		$lesson_restriction = get_post_meta($lesson_id, 'wpc-lesson-restriction', true);

		if(empty($lesson_restriction)){
			$lesson_restriction = 'none';
		}

		$html = '<div class="wpc-radio-buttons">';

			$html .= '<div class="wpc-metabox-item">';
				$html .= ' <button type="button" class="wpc-question-btn wpc-btn wpc-btn-sm" data-content="Anyone visiting your website can view this lesson."><i class="fa fa-question"></i> </button>';

				$html .= '<input id="wpc-none-radio" class="' . $class . '" type="radio" name="' . $name . '" value="none" data-id="' . $lesson_id . '"';
				$html .= checked($lesson_restriction, 'none', false);
				$html .= '/> None<br>';
			$html .= '</div>';

			$html .= '<div class="wpc-metabox-item">';
				$html .= ' <button type="button" class="wpc-question-btn wpc-btn wpc-btn-sm" data-content="Users must have an account on your WordPress website and must be logged in to view the contents of this lesson."><i class="fa fa-question"></i> </button>';
				$html .= '<input id="wpc-free-account-radio" class="' . $class . '" type="radio" name="' . $name . '" value="free-account" data-id="' . $lesson_id . '"';
				$html .= checked($lesson_restriction, 'free-account', false);
				$html .= '/> Login Required';
			$html .= '</div>';

			if(has_filter('wpc_lesson_restriction_radio_buttons')){
				$html = apply_filters( 'wpc_lesson_restriction_radio_buttons', $html, $lesson_id );
			}

			// Hidden membership field
			$html .= '<input style="display:none;" id="wpc-membership-radio" class="' . $class . '" type="radio" name="' . $name . '" value="membership" data-id="' . $lesson_id . '"';
			$html .= checked($lesson_restriction, 'membership', false);
			$html .= '/>';
		$html .= '</div>';

		return $html;
	}

	/** 
    * Returns the correct status icon for lessons and quizzes.
    * @param int $user_id
    * @param int $post_id Should be the ID of either the lesson or quiz you'd like to get the status icon for
    * @param string $post_type Accepts either lesson or wpc-quiz
    * @return Fa icons html icon
    */

	function get_lesson_icon($user_id, $post_id, $post_type = 'lesson'){

		$restriction =  get_post_meta( $post_id, 'wpc-lesson-restriction', true );

		if( is_user_logged_in() ){ // User logged in
			if($post_type === 'lesson'){ // Lesson
				$status = wpc_get_lesson_status($user_id, $post_id);
				$viewed = (int) $status['viewed'];
				$completed = (int) $status['completed'];

				if($completed === 1){
					$icon = '<i class="fa-regular fa-square-check wpc-default-status"></i>'; // Completed
				} elseif($viewed === 1) {
					$icon = '<i class="fa-regular fa-square wpc-default-status"></i>'; // Viewed
				} else {
					$icon = '<i class="fa-regular fa-square wpc-default-status"></i>'; // Not viewed so far
				}
			} else { // Quiz
				$icon = '<i class="fa-solid fa-graduation-cap"></i>';
			}

			// Overwrite icon if content drip is active and duration is not over
			if (function_exists('wpc_woo_has_bought')) {
				$has_bought = wpc_woo_has_bought($post_id);

				if($has_bought == true) {
					$lesson_content_drip_days = get_post_meta($post_id, 'wpc-lesson-content-drip-days', true);

					if(!empty($lesson_content_drip_days)) {
						$timestamp = wpc_timestamp_drip_lesson_content($post_id, $lesson_content_drip_days);

						if ($timestamp !== 0) {
							$icon = '<i class="fa-regular fa-hourglass"></i>';
						}
					}
				}
			}
		} else { // User logged out
			if($restriction == 'none'){
				if($post_type === 'lesson'){ // Lesson
					$icon = '<i class="fa-regular fa-square wpc-default-status"></i>';
				} else { // Quiz
					$icon = '<i class="fa-solid fa-graduation-cap"></i>';
				}
			} else {
				$icon = '<i class="fa-solid fa-lock wpc-default-status"></i>';
			}
		}

		// Check for WooCommerce and Paid Memberships Pro restrictions
		if(has_filter( 'wpc_lesson_button_icon' )){
            $icon = apply_filters( 'wpc_lesson_button_icon', $icon, $post_id );
        }

        return $icon;
	}

	/** 
    * Return css classes to assign to lesson buttons to indicate certian lesson statuses
    * @param int $user_id User ID
    * @param int $post_id Lesson or quiz ID that's currently being looped through
    * @param int $active_post_id The current post that's being viewed
    * @return CSS class
    */

	function get_lesson_li_class($user_id, $post_id, $active_post_id = null){		
		$class = '';
		if(is_user_logged_in()){

			$status = wpc_get_lesson_status($user_id, $post_id);
			$viewed = (int) $status['viewed'];
			$completed = (int) $status['completed'];

			if($status['completed'] === 1){
				$class = 'wpc-nav-item-success';
			} else if($status['viewed'] === 1){
				$class = 'wpc-nav-item-highlight';
			}

		} 

		if((int) $post_id === $active_post_id){
			$class = 'wpc-active-nav-item';
		}

		return $class;

	}

	/** 
    * Returns navigation list for specific course
    * @param int $course_id The course ID for the lesson nav list you'd like to retrieve
    * @param int $user_id User ID is passed to display appropriate lesson icons like fa-check, fa-eye, etc.
    * @return Ul lesson navigation list for a specific course
    */

	function wpc_get_classic_lesson_navigation($course_id, $user_id = 0){
		$html = '';

		$count = 1;

		$args = array(
	        'post_to'           => $course_id,
	        'connection_type'   => array('lesson-to-course', 'module-to-course', 'quiz-to-course'),
	        'order_by'          => 'menu_order',
	        'order'             => 'asc',
	        'join'				=> true,
	        'join_on'			=> "post_from"
	    );

		$lessons = wpc_get_connected($args);

		if(!empty($lessons)){
			$html .= '<ul class="wpc-nav-list wpc-lesson-nav wpc-classic-lesson-nav wpc-transition-nav">';
			$this_post_id = get_the_ID();

			$module_count = 0;

			foreach($lessons as $lesson) {
				$post_id = $lesson->post_from;
				$restriction =  get_post_meta( $post_id, 'wpc-lesson-restriction', true );

				$icon = get_lesson_icon($user_id, $post_id, get_post_type($post_id));
				$class = get_lesson_li_class($user_id, $post_id, $this_post_id);

		        $url = wpc_course_id_to_url(get_the_permalink($lesson->post_from), $course_id);

				if($lesson->connection_type == 'module-to-course' && $lesson->post_status == 'publish'){
					$html .= $module_count !== 0 ? '</div>' : '';
					$html .= '<li class="wpc-module-title wpc-nav-list-header" data-module-id="' . $module_count . '" data-status="false"><i class="fa fa-angle-down"></i> ' . get_the_title($lesson->post_from) . '</li>';
					$html .= '<div class="wpc-nav-list-section" data-height="null" data-module-id="' . $module_count . '" data-status="true">';
					$module_count++;
				} elseif($lesson->connection_type == 'lesson-to-course' && $lesson->post_status == 'publish' || $lesson->connection_type == 'quiz-to-course' && $lesson->post_status == 'publish') {
					$html .= '<li data-id="' . (int) $lesson->post_from . '"><a class="wpc-lesson-button lesson-button ' . $class . '" data-lesson-button-id="' . (int) $lesson->post_from . '" href="' . $url . '">' . $icon . get_the_title($lesson->post_from) . '</a></li>';	
					$count++;
				}

			}
			$html .= '</ul>';

			// $module_exists = false;

			wp_reset_postdata();
		}

		return $html;
		
	}

	/** 
    * Returns a multiselect dropdown for selecting courses
    * @param array $selected_ids An array of selected courses ids so they can be selected on load
    * @param string $name The name parameter value for the select
    * @param string $class Class name assigned to select
    * @return Course multiselect
    */

	function wpc_course_multiselect($selected_ids, $name = "course-selection[]", $class = 'wpc-course-multiselect') {
		global $wpdb;
	    $sql = 'SELECT DISTINCT ID, post_title, post_status FROM '.$wpdb->posts.' WHERE post_type = "course" AND post_status = "publish" OR post_type = "course" AND post_status = "draft" ORDER By post_title';
	    $results = $wpdb->get_results($sql); ?>

	    <select name="<?php echo $name; ?>" class="<?php echo $class; ?>" multiple>
	    	<option value="-1" <?php echo in_array(-1, $selected_ids) ? 'selected' :''; ?>>None</option>
	    	<?php
	    		foreach($results as $result) {
	    			$selected = in_array($result->ID, $selected_ids) ? ' selected' : '';
	    			echo '<option value="' . (int) $result->ID . '" ' . $selected . '>' . get_the_title((int) $result->ID) . '</option>';
	    		}
	    	?>
	    </select>
	<?php }

	/** 
    * @param int $course_id Course ID for the lesson list you'd like to retrieve
    * @return An ul of lessons, quizzes and modules for sorting and managing modules
    */

	function wpc_admin_lesson_list($course_id) {
		$args = array(
	        'post_to'           => $course_id,
	        'connection_type'   => array('lesson-to-course', 'module-to-course', 'quiz-to-course'),
	        'order_by'          => 'menu_order',
	        'order'             => 'ASC',
	        'join'				=> true,
	        'join_on'			=> 'post_from',
	    );

	    $lessons = wpc_get_connected($args);

    	$html = '<ul class="wpc-nav-list wpc-nav-list-contained wpc-admin-lesson-list">';

	    	if(!empty($lessons)) {

				foreach($lessons as $lesson) {

					if($lesson->post_type == 'wpc-module' ) {
						$html .= '<li data-id="' . $lesson->post_from . '" data-post-type="' . $lesson->post_type . '" data-course-id="' . $course_id . '" class="wpc-order-lesson-list-lesson wpc-nav-list-header ui-sortable-handle wpc-module-button"><i class="fa-solid fa-grip"></i> <input  class="wpc-input wpc-module-title-input" type="text" placeholder="Module Name" value="' . $lesson->post_title . '" class="wpc-module-title-input"><button type="button" class="wpc-delete-module wpc-btn wpc-btn-icon"><i class="fa fa-trash"></i></button></li>';
					} else if($lesson->post_type == 'lesson' || $lesson->post_type == 'wpc-quiz') {
						$html .= '<li data-id="' . $lesson->post_from . '" data-post-type="' . $lesson->post_type . '" data-course-id="' . $course_id . '"  class="wpc-order-lesson-list-lesson"><i class="fa-solid fa-grip"></i> ' . $lesson->post_title . '<a style="float:right;" href="' . get_edit_post_link($lesson->post_from) . '"> Edit</a></li>';
					}

				}
				
			}

		$html .= '</ul>';

		echo $html;
	}

	/** 
	* @param int $course_id Course ID for the option you'd like selected by default
	* @param string $class Class you'd like to apply to the select dropdown
	* @param bool $none Sets if the "none" option is available
	* @param bool $name Sets the name parameter value for the select element
    * @return string $name Name parameter value for the select
    */

	function wpc_get_course_dropdown($course_id = null, $class = 'wpc-select', $none = true, $name = 'course-selection'){

		$course_id = (int) $course_id;
		$name = sanitize_title_with_dashes($name);

    	global $wpdb;
    	$sql = 'SELECT DISTINCT ID, post_title, post_status FROM '.$wpdb->posts.' WHERE post_type = "course" AND post_status = "publish" OR post_type = "course" AND post_status = "draft" ORDER By post_title';
    	$results = $wpdb->get_results($sql);

    	$html = '';

		$html .= '<select name="' . $name . '" class="' . $class . '">';
		$html .= $none == true ? '<option value="-1">None</option>' : '';

		foreach($results as $result) {
			if($result->ID == $course_id){
				$selected = 'selected';
			} else {
				$selected = '';
			}
			if(!empty($result->ID)){
				$html .= '<option value="' . (int) $result->ID . '" ' . $selected . '>' . $result->post_title . '</option>';
			}
		}

		$html .= '</select>';
		return $html;

	}

	/** 
	* @param int $lesson_id Lesson ID for the breadcrumb trail you'd like to retrieve
	* @param int $course_id The course ID for the breadcrumb trail you'd like to retrieve
    * @return A breadcrumb trail
    */

	function wpc_get_breadcrumb($lesson_id, $course_id = null){
		$show_breadcrumb = get_option('wpc_show_breadcrumb_trail');
		$user_id = get_current_user_id();
		$terms = get_the_terms($course_id, 'course-category');

		if($show_breadcrumb != true || empty($terms) && $course_id == 'none'){
			return;
		} 

		$term_link = empty($terms) ? '' : '<a href="' . get_term_link($terms[0]->term_id) . '">' . $terms[0]->name . '</a> > ';
		$course_link = $course_id != 'none' || $course_id != -1 ? '<a href="#" class="wpc-load-course" data-id="' . $course_id . '" data-ajax="false">' . get_the_title($course_id) . '</a> > ' : '';

		$lesson_link = wpc_course_id_to_url(get_the_permalink($lesson_id), $course_id);

		return '<div class="wpc-breadcrumb">' . $term_link . $course_link . '<a href="' . $lesson_link . '">' . get_the_title($lesson_id) . '</a></div>';
	}

	/** 
	* @param int $user_id The user ID for which you'd like to retrieve the tracking table for
	* @param int $page Page number you'd like to get
	* @param int $posts_per_page Number of table rows per table
	* @param int $view 0 for viewed lessons, 1 for completed lessons
	* @param bool $links false will return ajax links/buttons, true will return permalinks
    * @return A table with viewed lesson data in order of most recently viewed
    */

	function wpc_get_lesson_tracking_table($user_id, $page = 1, $posts_per_page = -1, $view = 0, $ajax_links = false){

		if($view == 0){
			$text = esc_html__('Viewed', 'wp-courses');
			$dataView = 'viewed';
		} else {
			$text = esc_html__('Completed', 'wp-courses');
			$dataView = 'completed';
		}

		$html = '';

		$tracked_lessons = wpc_get_tracked_lessons_by_user($user_id, $view, $page, $posts_per_page, false);

		if(!empty($tracked_lessons)) {

				$html .= '<table class="wpc-table wpc-lesson-table wpc-fade" cellspacing="0">';
			 		$html .= '<thead>
			 				<tr><th scope="col">' . esc_html__('Lesson Name', 'wp-courses') . '</th>
			 				<th scope="col">' . esc_html__('Course Name', 'wp-courses') . '</th>
			 				<th scope="col">' . esc_html__('Time', 'wp-courses') . ' ' . $text .'</th></tr></thead>';
			 		$html .= '<tbody>';

						foreach( $tracked_lessons as $viewed ){

							$lesson_id = $viewed->post_id;

							$html .= '<tr data-id="' . $lesson_id . '">';
								$html .= '<td>';

									$courses_shortcode_url = wpc_get_main_shortcode_page_url();
									if ($courses_shortcode_url) {
										$params = array(
											'view' => 'single-lesson',
											'course_id' => $viewed->course_id,
											'lesson_id' => $lesson_id,
											'page' => 'null',
											'category' => 'null',
											'orderby' => 'null',
											'search' => 'null',
										);
										$courses_hash = wpc_get_courses_hash($params);
										$link = $courses_shortcode_url . $courses_hash;
									} else {
										$link = add_query_arg( array('course_id' => $viewed->course_id ), get_the_permalink($lesson_id) );
									}
									$title = get_the_title($lesson_id);
									$html .= '<a class="wpc-link" href="' . $link . '">' . $title . '</a>';

								$html .= '</td>';

								$html .= '<td>';

								$html .= get_the_title( $viewed->course_id );

								$html .= '</td>';

								$html .= '<td>';
									$html .= $view === 0 ? date('d/m/Y H:i', $viewed->viewed_timestamp) : date('d/m/Y H:i', $viewed->completed_timestamp);
								$html .= '</td>';

							$html .= '</tr>';

						}

					$html .= '<tbody>';
				$html .= '</table>';

		} else {
			$html .= __('No results', 'wp-courses');
		}

		return $html;

	}

	/** 
	* @param int $user_id The user ID for which you'd like to retrieve the tracking table for
	* @param array $args Arguments for WP_Query()
	* @param bool $links True to get permalinks, false to get AJAX load css lasses
    * @return A table with course completion data for a specific user
    */

	function wpc_get_course_progress_table($user_id, $args, $ajax_links = false){

		$html = '';

		$query = new WP_Query($args);

		$html .= '<table class="wpc-table wpc-fade">';

			$html .= '<thead>
				<tr><th></th><th>' . esc_html__('Title', 'wp-courses') . '</th><th>' . esc_html__('Viewed', 'wp-courses') . '</th><th>' . esc_html__('Completed', 'wp-courses') . '</th></tr>
			</thead>';

			while($query->have_posts()){
				$query->the_post();

				$course_id = get_the_id();
				$link = get_the_permalink($course_id);
				$title = get_the_title($course_id);


				$args = array(
					'post_to'           => $course_id,
					'connection_type'   => array('lesson-to-course', 'quiz-to-course'),
					'order_by'          => 'menu_order',
					'order'             => 'asc',
					'join'				=> true,
					'join_on'			=> "post_from"
				);

				$lessons_quizzes = wpc_get_connected($args);
				$course_content = is_array($lessons_quizzes) ? count($lessons_quizzes) : 0;

				if ($course_content) {
					$html .= '<tr>';
						$html .= '<td><button type="button" class="wpc-load-lesson-nav wpc-btn wpc-btn-sm wpc-open-modal wpc-btn-solid wpc-btn-round" data-id="' . $course_id . '" data-course-id="' . $course_id . '" data-selector=".wpc-lightbox-content" data-load-nav="true">' . esc_html( __('Details') ) . '</button></td>';
						$html .= '<td>';

							$courses_shortcode_url = wpc_get_main_shortcode_page_url();
							$first_lesson_id = wpc_get_course_first_lesson_id($course_id);
							if ($courses_shortcode_url) {
								$params = array(
									'view' => 'single-lesson',
									'course_id' => $course_id,
									'lesson_id' => $first_lesson_id,
									'page' => 'null',
									'category' => 'null',
									'orderby' => 'null',
									'search' => 'null',
								);
								$courses_hash = wpc_get_courses_hash($params);
								$link = $courses_shortcode_url . $courses_hash;
							}
							$html .= '<a class="wpc-link" href="' . $link . '">' . $title . '</a>';
							
						$html .= '</td>';
						$html .= '<td>' . wpc_get_progress_bar($course_id, $user_id, false, false, 'rgb(79, 100, 109)') . '</td>';
						$html .= '<td>' . wpc_get_progress_bar($course_id, $user_id, true, false) . '</td>';
					$html .= '</tr>';
				}

			}

		$html .= '</table>';

		// $html .= $current_page; // Unassigned variable, needed anymore?

		wp_reset_postdata(); 

		return $html;
	}

	/** 
	* @param int $user_id The user ID for which you'd like to retrieve the results for
	* @param array $args Arguments for WP_Query()
    * @return A table with course completion data for a specific user
    */

	function wpc_get_award_results_table($user_id, $args){

		ob_start();

			echo '<div class="wpc-flex-container wpc-fade">';

				$query = new WP_Query($args);

				while($query->have_posts()){
					$query->the_post();

					$post_id = get_the_ID();

					$has_requirement = wpc_has_requirement($post_id, $user_id);
					$class = $has_requirement === true ? 'wpc-has-badge' : 'wpc-no-badge';

					if($args['post_type'] === 'wpc-certificate') {
						$award = wpc_render_tiny_certificate($user_id, $post_id);
					} else {
						$award =  wpc_render_badge($post_id, $class, true);
					}

					if( wpc_has_requirement( $post_id, $user_id ) == true ){
						$has = '<i class="fa-solid fa-circle-check wpc-correct-i"></i>';
					} else {
						$has = '<i class="fa-solid fa-circle-xmark wpc-incorrect-i"></i>';
					}

					echo '<div class="wpc-flex-3">' . $award . '</div>';

				}

				if (!$query->have_posts()) {
					_e('No results', 'wp-courses');
				}

			echo '</div>';

			wp_reset_postdata();

		$ob_str = ob_get_contents();
		ob_end_clean();

		return $ob_str;

	}

	/** 
    * @return Returns a prompt that a certain feature is part of WP Courses Premium
    */

	function wpc_feature_upgrade_notice(){
		return '<div id="wpc-feature-upgrade-notice"><p>This feature is only availabe with WP Courses Premium.</p><img src="' . WPC_PLUGIN_URL . 'images/premium.png"/><a class="wpc-btn" href="https://wpcoursesplugin.com/wp-courses-premium/">Learn More</a></div>';
	}

	/** 
	* @param string $percent Width of the progress indicator
	* @param string $text The text inside the progress bar
	* @param string $color Hex or RGB value for the progress bar's color
    * @return A progress bar
    */

	function wpc_progress_bar($percent, $text = '', $color = '#4f646d'){

		$html = '<div class="wpc-progress-wrapper"><div class="wpc-progress-inner" style="width: ' . $percent . '%; background-color: ' . $color . '" data-current-percent="' . $percent . '" data-percent="' . $percent . '"><div class="wpc-progress-text"><span class="wpc-progress-perecent">' . $percent . '</span>% ' . $text . '</div></div></div>';

		return $html;

	}

	/** 
	* @param int $course_id
	* @param int $user_id
	* @param int $view 0 for viewed percent, 1 for completed percent
	* @param bool $text Whether or not to show "Viewed" or "Completed" text inside the progress bar
	* @param hex/rgb string $color The color of the progress bar
    * @return A progress bar
    */

	function wpc_get_progress_bar($course_id, $user_id = null, $view = 1, $text = true, $color = '#4f646d') {
		$course_id = (int) $course_id;

		if($user_id == null){
			$user_id = get_current_user_id();
		}

		if($view == 1){
			$percent = wpc_get_percent_done($course_id, $user_id, 1);
			$text = '';
			$class = "wpc-complete-progress";
			$icon = '<i class="fa fa-check"></i> ';
		} else {
			$percent = wpc_get_percent_done($course_id, $user_id, 0);
			$text = $text == true ? esc_html__('Viewed', 'wp-courses') : '';
			$class = "wpc-viewed-progress wpc-hide-viewed";
			$icon = '<i class="fa-solid fa-eye"></i> ';
		}

		$data = '<div class="wpc-progress-wrapper"><div class="wpc-progress-inner ' . $class . '" style="width: ' . $percent . '%; background-color: ' . $color . '" data-current-percent="' . $percent . '" data-percent="' . $percent . '"><div class="wpc-progress-text"><span class="wpc-progress-perecent">' . $percent . '</span>% ' . $text . '</div></div></div>';

		return $data;
	}

	/** 
	* @param int $lesson_id The ID for the lesson that has attachments you'd like to retrieve
	* @return array An array with attachment URLs
	*/

	function wpc_get_lesson_attachments($lesson_id){

		$attachments = array();
		for($i = 1; $i<=3; $i++){
			$url = get_post_meta( $lesson_id, 'wpc-media-sections-' . $i, true );
			if(!empty($url)){
				$url = esc_url( $url );
				array_push($attachments, $url);
			}

		}

		return $attachments;
	}

	function wpc_get_comments($post_id){
		$comments = get_comments(array(
			'post_id' 	=> $post_id,
			'status'	=> 'approve',
		));

		$html = count($comments) > 0 ? '<h2 class="wpc-comments-header wpc-h2">Comments</h3>' : '';

		$html .= '<ul class="wpc-comments">';
			foreach($comments as $comment) {
				$avatar = get_avatar($comment->user_id, 32);
				$html .= '<li class="wpc-comment"><div class="wpc-comment-avatar">' . $avatar . '</div><div class="wpc-comment-author">' . $comment->comment_author . '</div><div class="wpc-comment-date">' . $comment->comment_date . '</div><div class="wpc-comment-content">' . $comment->comment_content . '</div></li>';
			}
		$html .= '</ul>';

		return $html;

	}

	function wpc_get_comments_template($post_id){

		$html = '';

		$logged_in = is_user_logged_in();

	    $require_name_email = get_option('require_name_email');
		$comment_registration = get_option('comment_registration');

		$login_url = wp_login_url();
       	$registration_url = wp_registration_url();

		$comments_open = comments_open($post_id);

		if($comments_open === true) {

			$html .= '<h2 class="wpc-comments-header wpc-h2">' . __('Leave a Comment', 'wp-courses') . '</h3>';

			if($logged_in == false && $comment_registration == 1) {
				$html .= '<div class="wpc-comments-register-login wpc-alert-message"><a href="' . $login_url . '">' . __('Login', 'wp-courses') . '</a> ' . __('or', 'wp-courses') . ' <a href="' . $registration_url . '">' . __('Register', 'wp-courses') . '</a> ' . __('to leave a comment.', 'wp-courses') . '</div>';
			} else {
				$html .= '<textarea id="wpc-comment-textarea"></textarea>';
				$html .= '<div class="wpc-flex-container" style="margin: 2% -1% 0;">';
					$html .= $require_name_email == 1 ? '<div class="wpc-flex-4"><input id="wpc-comment-name" class="wpc-input" type="text" placeholder="' . __('Name', 'wp-courses') . '"/></div>' : '';
					$html .= $require_name_email == 1 ? '<div class="wpc-flex-4"><input id="wpc-comment-email" class="wpc-input" type="email" placeholder="' . __('Email Address', 'wp-courses') . '"/></div>' : '';
					$html .= '<div class="wpc-flex-4"><input id="wpc-comment-url" class="wpc-input" type="url" placeholder="' . __('Website', 'wp-courses') . '"/></div>';
				$html .= '</div>';
				$html .= '<button id="wpc-submit-comment" class="wpc-btn" data-id="' . $post_id . '">' . __('Post Comment', 'wp-courses') . '</button>';
			}

			$html .= wpc_get_comments($post_id);

		}

		return $html;

	}

	/** 
    * Returns a list of lesson names, connected to a specific course
    * @param array $lessons_quizzes An array of lessons and quizzes
    * @return string ordered list
    */

	function wpc_lesson_collection($lessons) {
		if (!empty($lessons)) {
			?> <ol class="wpc-connected-courses-list"> <?php
			foreach($lessons as $lq) {
				$title = $lq->post_title;
				$title = strlen($title) > 18 ? substr($title, 0, 15) . '...' : $title;

				echo '<li>' . $title . '</li>';
			}
			?> </ol> <?php
		} else {
			echo '—';
		}
	}

		/** 
    * Returns a list of lesson names, connected to a specific course
    * @param array $teachers An array of lessons and quizzes
    * @return string ordered list
    */

	function wpc_teacher_collection($teachers) {
		if (!empty($teachers) && $teachers[0]->post_title) {
			?> <ol class="wpc-connected-courses-list"> <?php
			foreach($teachers as $t) {
				$title = $t->post_title;
				$title = strlen($title) > 18 ? substr($title, 0, 15) . '...' : $title;

				echo '<li>' . $title . '</li>';
			}
			?> </ol> <?php
		} else {
			echo '—';
		}
	}

	function wpc_course_collection($courses) {
		if (!empty($courses) && $courses[0]->post_title) {
			?> <ol class="wpc-connected-courses-list"> <?php
			foreach($courses as $t) {
				$title = $t->post_title;
				$title = strlen($title) > 18 ? substr($title, 0, 15) . '...' : $title;

				echo '<li>' . $title . '</li>';
			}
			?> </ol> <?php
		} else {
			echo '—';
		}
	}