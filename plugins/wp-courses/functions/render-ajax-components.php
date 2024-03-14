<?php 

	/** 
	* @param int $lesson_id
	* @param int $course_id
	* @return array Lesson toolbar that's compatible with AJAX views
	*/

	function wpc_lesson_toolbar($lesson_id, $course_id){
		$post_type = get_post_type( $lesson_id );
		$user_id = get_current_user_id();
		$logged_in = is_user_logged_in();
		$status = $logged_in === true ? wpc_get_lesson_status($user_id, $lesson_id) : false;

		$has_attachments = wpc_has_attachments($lesson_id);
		$show_progress_bar = get_option('wpc_show_completed_lessons');

		$restricted = wpc_is_restricted($lesson_id);

		// Content drip
		$timestamp = 0; // Fallback, means show completed button
		if (function_exists('wpc_woo_has_bought')) {
			$has_bought = wpc_woo_has_bought($lesson_id);
			
			if($has_bought == true) {
				$lesson_content_drip_days = get_post_meta($lesson_id, 'wpc-lesson-content-drip-days', true);
				
				if(!empty($lesson_content_drip_days)) {
					$timestamp = wpc_timestamp_drip_lesson_content($lesson_id, $lesson_content_drip_days);
				}
			}
		}

		$show_completed_button = get_option('wpc_show_completed_lessons');

		$prev_next_ids = wpc_get_previous_and_next_lesson_ids($lesson_id, $course_id);
		$prev_post_type = get_post_type($prev_next_ids['prev_id']);
		$next_post_type = get_post_type($prev_next_ids['next_id']);
		$show_login_button = get_option('wpc_show_login_button'); ?>

		<div class="wpc-flex-container wpc-flex-center wpc-ajax-course-toolbar">

			<div class="wpc-flex-4 wpc-flex-toolbar-left">
				<?php if($logged_in === true && $show_progress_bar === 'true' && $course_id != 'none'){ ?>
					<div class="single-lesson-course-progress"><?php echo wp_kses( wpc_get_progress_bar($course_id), 'post' ); ?></div>
				<?php } ?>
			</div>

			<div class="wpc-flex-8 wpc-flex-toolbar-right">
				<div class="wpc-toolbar-buttons">

					<!-- COURSE ARCHIVE BUTTON -->
					<button class="wpc-load-courses wpc-btn-solid wpc-btn wpc-load-category-list" data-category="all" data-location="left-sidebar"><?php _e('Courses', 'wpc-courses'); ?></button>

					<!-- PREVIOUS BUTTON -->
					<?php 
					$loadTypeClass = $prev_post_type === 'wpc-quiz' ? 'wpc-load-quiz' : 'wpc-load-lesson';
					$disabled = $prev_next_ids['prev_id'] === false ? 'disabled' : '';
					?>

					<button <?php echo esc_attr($disabled); ?> type="button" class="<?php echo esc_attr($loadTypeClass); ?> wpc-btn wpc-btn-soft wpc-btn-next wpc-load-lesson-toolbar" title="<?php _e('Prev lesson', 'wp-courses'); ?>" data-id="<?php echo (int) $prev_next_ids['prev_id']; ?>" data-course-id="<?php echo $course_id; ?>" ><i class="fa fa-arrow-left"></i> <span><?php _e('Prev', 'wp-courses'); ?> </span></button>
					
					<!-- NEXT BUTTON -->
					<?php 
					$loadTypeClass = $next_post_type === 'wpc-quiz' ? 'wpc-load-quiz' : 'wpc-load-lesson';
					$disabled = $prev_next_ids['next_id'] === false ? 'disabled' : '';
					?>

					<button <?php echo esc_attr($disabled); ?> type="button" class="<?php echo esc_attr($loadTypeClass); ?> wpc-btn wpc-btn-soft wpc-btn-next wpc-load-lesson-toolbar" title="<?php _e('Next lesson', 'wp-courses'); ?>" data-id="<?php echo (int) $prev_next_ids['next_id']; ?>" data-course-id="<?php echo $course_id; ?>" ><span><?php _e('Next', 'wp-courses'); ?> </span><i class="fa fa-arrow-right"></i></button>

					<!-- COMPLETED BUTTON -->
					<?php if(
						$logged_in === true 
						&& $restricted !== true  
						&& $timestamp === 0
						&& $show_completed_button == 'true'
					){ 
						$completed_icon = $post_type === 'wpc-quiz' ? 'fa-solid fa-graduation-cap' : ($status['completed'] === 1 ? 'fa fa-check' : 'fa-regular fa-square');
						$completed_class = $status['completed'] === 1 ? 'wpc-marked-completed' : '';
						$disabled = $post_type === 'wpc-quiz' ? 'disabled' : '';
						?>
						<button <?php echo esc_attr($disabled); ?> type="button" class="wpc-mark-completed wpc-btn wpc-btn-soft <?php echo esc_attr($completed_class); ?>" data-id="<?php echo (int) $lesson_id; ?>" data-course-id="<?php echo (int) $course_id; ?>" data-status="<?php echo esc_attr( $status['completed'] );?>" title="<?php _e('Toggle lesson completion (not possible for quizzes)', 'wp-courses'); ?>"><i class="<?php echo $completed_icon; ?>"></i></button>
					<?php } ?>

					<!-- USER PROFILE BUTTON -->
					<?php if($logged_in === true) { ?>
						<button class="wpc-btn wpc-btn-soft wpc-open-sidebar wpc-load-profile-nav" data-visible="false"><i class="fa-solid fa-user"></i></button>
					<?php } else { ?>
						<?php if($show_login_button == 'true') { ?>
							<button class="wpc-btn wpc-load-login"><i class="fa-solid fa-right-to-bracket"></i> <?php _e('Log In', 'wp-courses'); ?></button>
						<?php } ?>
					<?php } ?>

					<!-- ATTACHMENTS BUTTON -->
					<?php if($has_attachments === true){ ?>
						<button class="wpc-btn wpc-btn-soft wpc-load-attachments" data-id="<?php echo (int) $lesson_id; ?>"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
					<?php } ?>

					<!-- MOBILE NAV BUTTON -->
					<button type="button" class="wpc-btn wpc-btn-soft wpc-load-lesson-nav wpc-mobile-btn wpc-open-bottom-sidebar" title="Lesson Navigation" data-course-id="<?php echo (int) $course_id; ?>"><i class="fa-solid fa-list"></i></button>

				</div>
			</div>
		</div>
	<?php }

	/** 
    * @param int $lesson_id The lesson ID
    * @param int $course_id The course ID
    * @return string Breadcrumb trail with AJAX functionality and pattern of Category->Course->Lesson
    */

	function wpc_breadcrumb($lesson_id, $course_id){ 

		$terms = get_the_terms($course_id, 'course-category');
		$category = $terms[0]->name; ?>

		<div class="wpc-crumb">

			<?php if( $category !== null ){ ?>
				<span class="wpc-load-courses wpc-load-category-list wpc-crumb-link wpc-load-category-list" data-category="<?php echo esc_attr( $category ); ?>" data-page="1"><?php echo esc_html($category); ?></span> > 
			<?php } ?>

			<?php if(!empty($course_id)) { ?>
				<span><?php echo esc_html(get_the_title( $course_id )); ?></span> > 
			<?php } ?>

			<span><?php echo get_the_title($lesson_id); ?></span>

		</div>

	<?php }

	/** 
    * @param int $lesson_id The lesson ID for the single lesson you'd like to retrieve
    * @param int $course_id The course ID.  This is for the wpc_lesson_content filter and the breadcrumb trail.
    * @return string Single lesson with AJAX functionality
    */

	function wpc_lesson($lesson_id, $course_id){

		global $wp_embed;

		$video = '<div class="wpc-vid-wrapper" style="margin: 0; width: 100%;">' . wpc_get_video($lesson_id, 'lesson') . '</div>';
        $content = get_the_content(null, true, $lesson_id);
        $content = $video . $content;

        if(!has_blocks($lesson_id)) {
        	$content = wpautop( $content );
        } else {

        	$blocks = parse_blocks( $content );
        	$content = '';
        	
        	foreach ($blocks as $block) {
        		// adds inline styling for Gutenberg blocks because Gutenberg blocks do not render styling for AJAX requests otherwise
        		$content .= wpc_render_layout_support_flag( render_block($block), $block );
			}
        }

        $content = wpc_restrict_content($lesson_id, $content, 'lesson'); // applies "free-account" restriction if enabled
        $content = apply_filters('wpc_lesson_content', $content, $lesson_id, $course_id);
        $content = do_shortcode($content); 
        $content = $wp_embed->autoembed( $content ); // renders videos embedded with URLs

        $show_breadcrumb = get_option('wpc_show_breadcrumb_trail');

        $user_id = get_current_user_id();
        $logged_in = is_user_logged_in();

        if($logged_in === true) {
            wpc_push_viewed($lesson_id, $course_id, $user_id);
        }

        $allowed = wpc_video_and_content_kses();

        ?>

		<div class="wpc-material wpc-material-content wpc-fade" id="wpc-material-content">
			<?php if($show_breadcrumb == 'true') {
					wpc_breadcrumb($lesson_id, $course_id);
				} ?>
			<h1 class="wpc-h1 wpc-content-title"><?php echo get_the_title($lesson_id); ?></h1>
			<div class="wpc-lesson-content"><?php echo wp_kses($content, $allowed); ?></div>

			<?php wpc_comments_form($lesson_id); ?>
			<?php wpc_comments($lesson_id); ?>

		</div>



	<?php }

	/** 
    * Returns AJAX compatible navigation list for specific course
    * @param int $course_id The course ID for the lesson nav list you'd like to retrieve
    * @param int $user_id User ID is passed to display appropriate lesson icons like fa-check, fa-eye, etc.
    * @return Ul lesson navigation list for a specific course
    */

	function wpc_lesson_navigation($course_id, $user_id = 0, $load_nav = false, $lesson_id){
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

		if($load_nav == 'true') {
			echo '<button class="wpc-btn wpc-btn-sm wpc-btn-nav wpc-load-profile wpc-load-profile-part-pagination" data-page="1" data-name="progress" data-title="' . __('Progress') . '"><i class="fa fa-arrow-left"></i> ' . __('Back', 'wp-courses') . '</button>';
		}

		if(!empty($lessons)){ ?>
			<ul class="wpc-nav-list wpc-lesson-nav wpc-transition-nav wpc-fade">
				<?php $this_post_id = get_the_ID();

				$module_count = 0;

				if ($lesson_id === 0) {
					$first_lesson_id = (int) wpc_get_course_first_uncompleted_lesson_id($course_id);
				} else {
					$first_lesson_id = $lesson_id;
				}

				// Find out opened module
				$open = get_option('wpc_modules_opened') === 'true' ? true : false;
				$opened_module_id = 0;
				if (!$open) {
					foreach ($lessons as $lesson) {
						$post_id = $lesson->post_from;

						if ($lesson->connection_type === 'module-to-course') {
							$opened_module_id = $post_id;
						}

						if ($first_lesson_id == $post_id) {
							break;
						}
					}
				}

				foreach($lessons as $lesson) {
					$post_id = $lesson->post_from;
					$icon = get_lesson_icon($user_id, $post_id, get_post_type($post_id));
					$class = get_lesson_li_class($user_id, $post_id, $this_post_id);
					$title = get_the_title($lesson->post_from);

					// Highlight first uncompleted lesson of course
					// Or last lesson of course, if all are completed
					if ($first_lesson_id == $post_id) {
						$class = $class . ' wpc-active-nav-item';
					}

					// Open module which includes highlighted lesson
					if ($opened_module_id == $post_id) {
						$open = true;
					}

					// Define HTML classes
			        $load_class = $lesson->connection_type === 'lesson-to-course' ? 'wpc-load-lesson' : 'wpc-load-quiz';
			        $load_nav_class = $load_nav == 'true' ? 'wpc-load-lesson-nav' : '';

					// Close / open modules
					$angle = $open ? 'fa fa-angle-down' : 'fa fa-angle-up';
					$status = $open ? 'true' : 'false';
					$style = $open ? '' : 'height: 0px;';
					$data_height = 'null';

					if($lesson->connection_type == 'module-to-course' && $lesson->post_status == 'publish'){
						if($module_count !== 0){ ?>
							</div>
						<?php } ?>

						<li class="wpc-module-title wpc-nav-list-header" data-module-id="<?php echo (int) $module_count; ?>" data-status="false"><i class="<?php echo $angle; ?>"></i> <?php echo $title; ?></li>

						<div class="wpc-nav-list-section" data-height="<?php echo $data_height; ?>" data-module-id="<?php echo (int) $module_count; ?>" data-status="<?php echo $status; ?>" style="<?php echo $style; ?>">

						<?php $module_count++;
					} elseif(
						$lesson->connection_type == 'lesson-to-course' 
						&& $lesson->post_status == 'publish' 
						|| $lesson->connection_type == 'quiz-to-course' 
						&& $lesson->post_status == 'publish'
					) { ?>
						<li class="<?php echo esc_attr($load_class); ?> wpc-load-lesson-toolbar <?php echo $load_nav_class; ?> <?php echo $class; ?>" data-id="<?php echo (int) $lesson->post_from; ?>" data-course-id="<?php echo (int) $course_id; ?>"><?php echo $icon . $title; ?></li>
						<?php $count++;
					}
				} ?>
			</ul>

		<?php }
		
	}

	/** 
    * @param int $post_id
    * @return string AJAX powered comments form
    */

	function wpc_comments_form($post_id){

		$logged_in = is_user_logged_in();

	    $require_name_email = get_option('require_name_email');
		$comment_registration = get_option('comment_registration');

		$login_url = wp_login_url();
       	$registration_url = wp_registration_url();

		$comments_open = comments_open($post_id);

		if($comments_open === true) { ?>

			<h2 class="wpc-comments-header wpc-h2"><?php _e('Leave a Comment', 'wp-courses'); ?></h3>

			<?php if($logged_in == false && $comment_registration == 1) { ?>
				<div class="wpc-comments-register-login wpc-alert-message">
					<a href="<?php esc_url($login_url); ?>"><?php _e('Login', 'wp-courses'); ?></a>
					 <?php _e('or', 'wp-courses'); ?> 
					<a href="<?php esc_url($registration_url); ?>"><?php _e('Register', 'wp-courses'); ?></a>
					 <?php _e('to leave a comment.', 'wp-courses'); ?>
				</div>
			<?php } else { ?>
				<textarea id="wpc-comment-textarea"></textarea>
				<div class="wpc-flex-container" style="margin: 2% -1% 0;">
					<?php if($require_name_email == 1) { ?>
						<div class="wpc-flex-4"><input id="wpc-comment-name" class="wpc-input" type="text" placeholder="<?php _e('Name', 'wp-courses'); ?>"/></div>
						<div class="wpc-flex-4"><input id="wpc-comment-email" class="wpc-input" type="email" placeholder="<?php _e('Email Address', 'wp-courses'); ?>"/></div>
					<?php } ?>

					<div class="wpc-flex-4"><input id="wpc-comment-url" class="wpc-input" type="url" placeholder="<?php _e('Website', 'wp-courses'); ?>"/></div>
				</div>
				<button id="wpc-submit-comment" class="wpc-btn" data-id="<?php echo (int) $post_id; ?>"><?php _e('Post Comment', 'wp-courses'); ?></button>
			<?php }
		}
	}

	/** 
    * @param int $post_id
    * @return string List of comments for use with the AJAX powered comments form
    */

	function wpc_comments($post_id){

		$comments = get_comments(array(
			'post_id' 	=> $post_id,
			'status'	=> 'approve',
		));

		$comments_open = comments_open($post_id);

		if($comments_open === true) {
			if(count($comments) > 0) { ?>
			<h2 class="wpc-comments-header wpc-h2"><?php _e('Comments', 'wp-courses'); ?></h2>
			<?php } ?>

			<ul class="wpc-comments">
				<?php foreach($comments as $comment) {
					$avatar = get_avatar($comment->user_id, 32); ?>
					<li class="wpc-comment">
						<div class="wpc-comment-avatar"><?php echo wp_kses($avatar, 'post'); ?></div>
						<div class="wpc-comment-author"><?php echo esc_html($comment->comment_author); ?></div>
						<div class="wpc-comment-date"><?php echo esc_html($comment->comment_date); ?></div>
						<div class="wpc-comment-content"><?php echo wp_kses($comment->comment_content, 'post'); ?></div>
					</li>
				<?php } ?>
			</ul>
		<?php }

	}

	/** 
	* @param int $lesson_id
	* @return string ul of attachments for a specific lesson
	*/

	function wpc_attachments($lesson_id){

		if(wpc_is_restricted($lesson_id) === true){
			return false;
		}

		$attachments = array();

		// Yes, this is stupid saving each attachment to its own post meta... This is from back when I didn't know what I was doing.
        $attachment1 = get_post_meta($lesson_id, 'wpc-media-sections-1', true);
        $attachment2 = get_post_meta($lesson_id, 'wpc-media-sections-2', true);
        $attachment3 = get_post_meta($lesson_id, 'wpc-media-sections-3', true);

        if(!empty($attachment1)) {
        	$attachments[] = $attachment1;
        } 

        if(!empty($attachment2)) {
        	$attachments[] = $attachment2;
        } 

        if(!empty($attachment3)) {
        	$attachments[] = $attachment3;
        } ?>

        <ul class="wpc-nav-list wpc-fade">
        	<?php $count = 1; ?>
	        <?php foreach($attachments as $attachment){

	        	$file_name = basename($attachment);
	        	$type = pathinfo($file_name, PATHINFO_EXTENSION);

	        	$icon = '<i class="fa fa-paperclip"></i>';

	        	switch ($type) {
				  case 'pdf':
				    $icon = '<i class="fa fa-file-pdf"></i>';
				    break;
				  case 'zip':
				    $icon = '<i class="fa fa-file-zipper"></i>';
				    break;
				  case 'png':
				    $icon = '<i class="fa fa-file-image"></i>';
				    break;
				  case 'jpg':
				    $icon = '<i class="fa fa-file-image"></i>';
				    break;
				  case 'csv':
				    $icon = '<i class="fa-solid fa-file-csv"></i>';
				    break;
				}

				$allowed = array(
					'i'	=> array('class' => array()),
				); ?>

	        	<li><a href="<?php echo esc_url($attachment); ?>" target="_blank"><?php echo wp_kses($icon, $allowed); ?> <?php echo esc_attr($file_name); ?></a></li>

	        	<?php $count++; ?>
	        <?php } ?>
        </ul>

	<?php }

	/** 
	* @param int $id Course ID
	* @param int $ajaxLinks false to use permalinks true to use classes that load AJAX content
	* @return string single course details
	*/

	function wpc_course($id, $ajaxLinks = true, $caller){
		$first_lesson_id = (int) wpc_get_course_first_uncompleted_lesson_id($id);
		$first_lesson_link = get_the_permalink( $first_lesson_id );
		$content = get_the_content(null, false, $id);
		$fi = get_the_post_thumbnail_url( $id, 'large' );
		$video = wpc_get_video($id, 'course');
		$link = get_the_permalink($id);

		if(!has_blocks($id)) {
        	$content = wpautop( $content );
        } else {

        	$blocks = parse_blocks( $content );
        	$content = '';
        	
        	foreach ($blocks as $block) {
        		// adds inline styling for Gutenberg blocks because Gutenberg blocks do not render styling for AJAX requests otherwise
        		$content .= wpc_render_layout_support_flag( render_block($block), $block );
			}

        }

        $content = do_shortcode($content);

        $allowed = wp_kses_allowed_html( 'post' );
        $allowed['style'] = array();

		$lesson_count = wpc_count_connected($id);
		$quiz_count = wpc_count_connected($id, 'quiz-to-course');
		$module_count = wpc_count_connected($id, 'module-to-course');
		$course_content = ($lesson_count === 0 && $quiz_count === 0) ? false : true; // Needed to identify main bundle product

		if($video !== false && $video !== '') { ?>
			<div class="wpc-vid-wrapper wpc-single-course-vid-wrapper" ><?php echo $video; ?></div>
		<?php } else if($fi !== false) { ?>
			<img src="<?php echo esc_url( $fi ); ?>" class="wpc-lightbox-featured-img"/>
		<?php } ?>

		<!-- Title -->
		<h2 class="wpc-single-course-title"><?php echo get_the_title($id); ?></h2>

		<?php if (get_option('wpc_show_course_counters') == 'true' && $course_content) { ?>

			<!-- Counters -->
			<div class="wpc-flex-container wpc-meta-container-lg wpc-counter-container">

				<?php if ($lesson_count !== 0) { ?>
					<div class="wpc-material-meta-item wpc-material-meta-item-lg wpc-flex-4 wpc-center">
						<i class="wpc-material-meta-icon"><span class="wpc-counter" data-count="<?php echo $lesson_count; ?>">0</span></i><br> <span class="wpc-meta-key"><?php _e('Lessons', 'wp-courses'); ?></span><br>
					</div>
				<?php } ?>

				<?php if ($quiz_count !== 0) { ?>
					<div class="wpc-material-meta-item wpc-material-meta-item-lg wpc-flex-4 wpc-center">
						<i class="wpc-material-meta-icon"><span class="wpc-counter" data-count="<?php echo $quiz_count; ?>">0</span></i><br> <span class="wpc-meta-key"><?php _e('Quizzes', 'wp-courses'); ?></span><br>
					</div>
				<?php } ?>

				<?php if ($module_count !== 0) { ?>
					<div class="wpc-material-meta-item wpc-material-meta-item-lg wpc-flex-4 wpc-center">
						<i class="wpc-material-meta-icon"><span class="wpc-counter" data-count="<?php echo $module_count; ?>">0</span></i><br> <span class="wpc-meta-key"><?php _e('Modules', 'wp-courses'); ?></span><br>
					</div>
				<?php } ?>

			</div>

		<?php } ?>

		<!-- Start course (and add to cart) button -->
		<div class="wpc-content-toolbar">
			<?php do_action('wpc_before_course_buttons', $id); // Unused ?>

			<?php if ($course_content) {
				if ($caller === 'wpc_profile-shortcode' || $caller === 'wpc_courses-shortcode' || $caller === '') { // $caller === '' is WP Admin > WP Courses > All Students 
					$courses_shortcode_url = wpc_get_main_shortcode_page_url();
					if ($courses_shortcode_url) {
						$params = array(
							'view' => 'single-lesson',
							'course_id' => $id,
							'lesson_id' => $first_lesson_id,
							'page' => 'null',
							'category' => 'null',
							'orderby' => 'null',
							'search' => 'null',
						);
						$courses_hash = wpc_get_courses_hash($params);
						$link = $courses_shortcode_url . $courses_hash;
					} else {
						$link = $first_lesson_link;
					}
					?>
					<a class="wpc-btn" href="<?php echo esc_url($link); ?>"><i class="fa-solid fa-circle-play"></i> <?php _e('Start Course', 'wp-courses'); ?></a>
				<?php } else if ($ajaxLinks === true) { ?>
					<button type="button" class="wpc-btn wpc-load-lesson wpc-load-lesson-toolbar wpc-start-course wpc-clear-search wpc-close-modal" data-id="<?php echo (int) $first_lesson_id; ?>" data-course-id="<?php echo (int) $id; ?>"><i class="fa-solid fa-circle-play"></i> <?php _e('Start Course', 'wp-courses'); ?></button>
				<?php } else { ?>
					<a class="wpc-btn" href="<?php echo esc_url($first_lesson_link); ?>"><i class="fa-solid fa-circle-play"></i> <?php _e('Start Course', 'wp-courses'); ?></a>
				<?php }
			} ?>

			<?php do_action('wpc_after_course_buttons', $id); // Adds add to cart button ?>
		</div>

		<!-- Course description / content of post -->
		<div class="wpc-flex-12 wpc-single-course-content"><?php echo wp_kses($content, $allowed); ?></div>

		<!-- Heading -->
		<?php if ($course_content) { ?>
			<h2 class="wpc-single-course-details-header"><?php _e('What You\'ll Learn', 'wp-courses'); ?></h2>
		<?php } ?>

		<!-- Overview lessons, quizzes and modules -->
		<?php wpc_course_modules($id); ?>

	<?php }

	/** 
	* @param int $id Course ID
	* @return string Single course card typically for use in course archive
	*/

	function wpc_course_card($id){ ?>

		<div class="wpc-flex-3 wpc-material wpc-single-course-archive">
			<h3 class="wpc-material-heading wpc-h3"><?php echo get_the_title( $id ); ?></h3>

			<?php 
				$fi = get_the_post_thumbnail_url( $id, 'large' );
				$video = wpc_get_video($id, 'course');
				$first_lesson_id = (int) wpc_get_course_first_uncompleted_lesson_id($id);
				$user_id = get_current_user_id();
				$teachers = wpc_get_connected_teachers($id);
				$difficulty = wp_get_post_terms($id, 'course-difficulty');
				$difficulty = !empty($difficulty) ? $difficulty[0]->name : false;
				$logged_in = is_user_logged_in();

				$show_progress_bar = get_option('wpc_show_completed_lessons'); ?>

			<?php if($video !== false && $video !== '') { ?>
				<div class="wpc-vid-wrapper"><?php echo $video; ?></div>
			<?php } else if($fi !== false){ ?>
				<div class="wpc-img-wrapper"><img src="<?php echo esc_url($fi); ?>"/></div>
			<?php } ?>

			<div class="wpc-material-tools">
				<?php do_action('wpc_before_course_details_button'); ?>
				<button type="button" class="wpc-btn wpc-btn-solid wpc-btn-round wpc-btn-sm wpc-load-course wpc-clear-search" data-id="<?php echo (int) $id; ?>" data-ajax="true"><?php _e('Details', 'wp-courses'); ?></button>
				<?php do_action('wpc_after_course_details_button'); ?>
			</div>

			<p class="wpc-material-text wpc-course-archive-excerpt"><?php echo get_the_excerpt( $id ); ?></p>

			<?php
			do_action('wpc_before_course_buttons', $id); // Unused

			$lesson_count = wpc_count_connected($id);
			$quiz_count = wpc_count_connected($id, 'quiz-to-course');
			$module_count = wpc_count_connected($id, 'module-to-course');
			$course_content = ($lesson_count === 0 && $quiz_count === 0) ? false : true; // Needed to identify main bundle product

			if ($course_content) {
			?>
			<button type="button" class="wpc-btn wpc-load-lesson wpc-load-lesson-toolbar wpc-start-course wpc-clear-search" data-id="<?php echo (int) $first_lesson_id; ?>" data-course-id="<?php echo (int) $id; ?>"><i class="fa-solid fa-circle-play"></i> <?php _e('Start Course', 'wp-courses'); ?></button>
			
			<?php
			}
			
			do_action('wpc_after_course_buttons', $id); // Adds add to cart button
			?>

			<?php if( !in_array(-1, $teachers) || $difficulty !== false || $logged_in) { ?>

				<div class="wpc-material-meta wpc-flex-container">

					<?php if($logged_in) { ?>
						<div class="wpc-material-meta-item wpc-flex-12"><?php echo wp_kses( wpc_get_progress_bar($id, $user_id, 0), 'post' ); ?></div>
					<?php } ?>

					<?php if($logged_in && $show_progress_bar == true) { ?>
						<div class="wpc-material-meta-item wpc-flex-12"><?php echo wp_kses( wpc_get_progress_bar($id, $user_id, 1, true, '#4f646d'), 'post' ); ?></div>
					<?php } ?>

					<?php if(!in_array(-1, $teachers)) { ?>
						<div class="wpc-material-meta-item wpc-flex-6 wpc-center">
							<i class="fa fa-users wpc-material-meta-icon"></i><br> <span class="wpc-meta-key"><?php echo count($teachers) > 1 ? __('Teachers', 'wp-courses') :  __('Teacher', 'wp-courses'); ?></span><br>
							<?php foreach($teachers as $teacher) { ?>
								<div class="wpc-meta-value">
									<div class="wpc-load-teacher wpc-ajax-link" data-id="<?php echo (int) $teacher; ?>"><?php echo get_the_title($teacher); ?></div>
								</div>
							<?php } ?>
						</div>
					<?php } ?>

					<?php if($difficulty !== false) { ?>
						<div class="wpc-material-meta-item wpc-flex-6 wpc-center">
							<i class="fa-solid fa-temperature-half wpc-material-meta-icon"></i><br> <span class="wpc-meta-key"><?php _e('Difficulty', 'wp-courses'); ?></span><br>
							<div class="wpc-meta-value"><?php echo esc_html($difficulty); ?></div>
						</div>
					<?php } ?>

				</div>

			<?php } // end if ?>
		</div>

	<?php }

	/** 
	* @param array $args WP_Query arguments
	* @return string Course archive
	*/

	function wpc_course_archive($args){
		$query = new WP_Query($args);

		if(!$query->have_posts()) { ?>
			<div id="wpc-results-empty" class="wpc-flex-12"><i class="fa-regular fa-face-frown"></i> <?php _e('No results', 'wp-courses'); ?></div>
			<?php return;
		} ?>

		<div class="wpc-flex-container wpc-flex-cards-wrapper wpc-fade">

			<?php while($query->have_posts()) {
				$query->the_post();

				wpc_course_card(get_the_ID());

			} 

			wp_reset_postdata(); ?>

		</div>

		<?php 
		// ajax pagination
		$args['posts_per_page'] = -1;

		$query = new WP_Query($args);

		$course_count = $query->post_count;
		$courses_per_page = get_option( 'wpc_courses_per_page');

		if($courses_per_page < $course_count) {
			$num_pages = $course_count / $courses_per_page;
			$category = empty($args['tax_query'][0]['terms']) ? 'all' : $args['tax_query'][0]['terms'];
			$pages = array();

			?>

			<div class="wpc-pagination">
				<?php for($i = 1; $i < $num_pages + 1; $i++) { ?>
					<button class="wpc-load-courses wpc-btn-sm wpc-btn-pagination" data-page="<?php echo (int) $i; ?>" data-category="<?php echo esc_attr($category); ?>"><?php echo (int) $i; ?></button>
				<?php } ?>
			</div>

		<?php }

	}

	/** 
	* @return string ul of all course categories with classes for AJAX loading of courses
	*/

	function wpc_course_categories(){
		$categories = get_terms('course-category'); ?>

		<?php if(!empty($categories)){ ?>
			<ul class="wpc-nav-list wpc-nav-list-contained wpc-fade" style="border-top: 0;">
				<li class="wpc-load-courses" data-category="all"><?php _e('All Categories', 'wp-courses'); ?></li>
				
					<?php foreach($categories as $category) { ?>
						<li class="wpc-load-courses wpc-clear-search" data-category="<?php echo esc_attr($category->slug); ?>" data-page="1"><?php echo esc_html($category->name); ?></li>
					<?php }	?>
				
			</ul>
		<?php } else {
			echo 'false';
		} ?>

	<?php }

	/** 
	* @param int $course_id Course ID
	* @return string ul of modules and lessons for presentation in single course view
	*/

	function wpc_course_modules($course_id){

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

		if(!empty($lessons)){ ?>
			<ul class="wpc-module-list wpc-fade">
				<?php $this_post_id = get_the_ID();

				$module_count = 0;
				$lesson_count = 0;

				foreach($lessons as $lesson) {
					$post_id = $lesson->post_from;

					if($lesson->connection_type == 'module-to-course' && $lesson->post_status == 'publish'){
						if($module_count !== 0){ ?>
							</ul>
						<?php } ?>

						<li data-module-id="<?php echo (int) $module_count; ?>" data-status="false"><h3 class="wpc-module-list-header"><span class="wpc-module-list-count"><?php echo 0 . ($module_count + 1); ?></span> <?php echo get_the_title($lesson->post_from); ?></h3></li>

						<ul class="wpc-module-list-section" data-height="null" data-module-id="<?php echo (int) $module_count; ?>" data-status="true">

						<?php $module_count++;
					} elseif(
						$lesson->connection_type == 'lesson-to-course' 
						&& $lesson->post_status == 'publish' 
						|| $lesson->connection_type == 'quiz-to-course' 
						&& $lesson->post_status == 'publish'
					) { 
						$lesson_count++;
					?>
						<li class="wpc-module-list-lesson"><span class="wpc-module-list-lesson-count"><?php echo $lesson_count; ?></span> <?php echo get_the_title($lesson->post_from); ?></li>
						<?php $count++;
					}

				} ?>
			</ul>

		<?php }
		
	}

	/** 
	* @param string $orderby the order that courses are currently ordered by.  Sets the order select to appropriate value.
	* @return string Course toolbar for use with AJAX view
	*/

	function course_toolbar($orderby){
		$logged_in = is_user_logged_in(); 
		$show_search = get_option('wpc_show_course_search');
		$show_login_button = get_option('wpc_show_login_button'); ?>

		<div class="wpc-flex-container wpc-flex-center wpc-ajax-course-toolbar">
			<div class="wpc-flex-4 wpc-ajax-search-wrapper">
				<div class="wpc-ajax-tool">
					<?php if($show_search == 'true') { ?>
						<input type="text" id="wpc-course-ajax-search" placeholder="<?php _e('Search Courses', 'wp-courses'); ?>" />
					<?php } ?>
				</div>
			</div>

			<div class="wpc-flex-8 wpc-ajax-filters-wrapper">
				<div class="wpc-ajax-tool wpc-ajax-course-sort-wrapper">
					<div class="wpc-ajax-filters-wrapper-right">
						<select id="wpc-ajax-course-sort" class="wpc-select">
							<option value="menu_order" <?php echo $orderby === 'menu_order' ? 'selected' : ''; ?>><?php _e('default', 'wp-courses'); ?></option>
							<option value="date" <?php echo $orderby === 'date' ? 'selected' : ''; ?>><?php _e('newest', 'wp-courses'); ?></option>
						</select>
						<button type="button" class="wpc-btn wpc-btn-soft wpc-load-category-list wpc-mobile-btn wpc-open-bottom-sidebar" title="Course Categories" data-ajax="true" style="margin-left: 10px;"><i class="fa-solid fa-list"></i> <?php _e('Categories', 'wp-courses'); ?></button>
						<?php if($logged_in === true) { ?>
							<button class="wpc-btn wpc-btn-soft wpc-open-sidebar wpc-load-profile-nav" data-visible="false"><i class="fa-solid fa-user"></i></button>
						<?php } else { ?>
							<?php if($show_login_button == 'true') { ?>
								<button class="wpc-btn wpc-load-login"><i class="fa-solid fa-right-to-bracket"></i> <?php _e('Log In', 'wp-courses'); ?></button>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	<?php }

	/** 
	* @param int $teacher_id The teacher ID
	* @return string Single teacher with featured image and content
	*/

	function wpc_teacher( $teacher_id ){

		$image = get_the_post_thumbnail_url( $teacher_id, 'large' );
		$content = get_the_content(null, true, $teacher_id);

		if(!has_blocks($teacher_id)) {
        	$content = wpautop( $content );
        } else {

        	$blocks = parse_blocks( $content );
        	$content = '';
        	
        	foreach ($blocks as $block) {
        		// adds inline styling for Gutenberg blocks because Gutenberg blocks do not render styling for AJAX requests otherwise
        		$content .= wpc_render_layout_support_flag( render_block($block), $block );
			}

        }

        $content = do_shortcode($content);

        $allowed = wp_kses_allowed_html( 'post' );
        $allowed['style'] = array();

		if($image === false){
			echo '<h2>' . get_the_title( $teacher_id ) . '</h2>' . wp_kses($content, $allowed);
		} else { ?>
			<div class="wpc-flex-container">

				<div class="wpc-flex-4">
					<img class="wpc-teacher-img" src="<?php echo esc_url($image); ?>"/>
				</div>

				<div class="wpc-flex-8 wpc-teacher-content">
					<h2><?php echo get_the_title( $teacher_id ); ?></h2>
					<?php echo wp_kses($content, $allowed); ?>
				</div>

			</div>
		<?php }

	}

	/** 
	* @param string $ulClass Any additional CSS classes you'd like to add to the unordered list
	* @param string $liClass Any additional CSS classes you'd like to add to each list item
	* @return string AJAX powered profile navigation
	*/

	function wpc_profile_nav($ulClass = '', $liClass = ''){
		$show_woo_tab = get_option( 'wpc_woo_tab');	?>
		<ul class="wpc-nav-list wpc-nav-list-profile wpc-fade <?php echo esc_attr($ulClass); ?>">

			<?php if(WPCP_ACTIVE == true && $show_woo_tab == 'true' && class_exists( 'WooCommerce' )) { ?>
				<li class="wpc-load-profile wpc-load-profile-part-pagination <?php echo esc_attr($liClass); ?>" data-page="1" data-name="purchased-courses" data-title="<?php _e('Purchased', 'wp-courses'); ?>"><i class="fa-solid fa-basket-shopping"></i> <?php _e('Purchased', 'wp-courses'); ?></li>
			<?php } ?>

			<li class="wpc-load-profile wpc-load-profile-part-pagination <?php echo esc_attr($liClass); ?>" data-page="1" data-name="viewed" data-title="<?php _e('Viewed', 'wp-courses'); ?>"><i class="fa-solid fa-eye"></i> <?php _e('Viewed', 'wp-courses'); ?></li>
			<li class="wpc-load-profile wpc-load-profile-part-pagination <?php echo esc_attr($liClass); ?>" data-page="1" data-name="completed" data-title="<?php _e('Completed', 'wp-courses'); ?>"><i class="fa-regular fa-square-check"></i> <?php _e('Completed', 'wp-courses'); ?></li>
			<li class="wpc-load-profile wpc-load-profile-part-pagination <?php echo esc_attr($liClass); ?>" data-page="1" data-name="progress" data-title="<?php _e('Progress', 'wp-courses'); ?>"><i class="fa fa-bar-chart"></i> <?php _e('Progress', 'wp-courses'); ?></li>
			<li class="wpc-load-profile wpc-load-profile-part-pagination <?php echo esc_attr($liClass); ?>" data-page="1" data-name="quiz-results" data-title="<?php _e('Quiz Results', 'wp-courses'); ?>"><i class="fa-solid fa-graduation-cap"></i> <?php _e('Quiz Results', 'wp-courses'); ?></li>

			<?php if(WPCP_ACTIVE == true){ ?>
				<li class="wpc-load-profile wpc-load-profile-part-pagination <?php echo esc_attr($liClass); ?>" data-page="1" data-name="badges" data-title="<?php _e('Badges', 'wp-courses'); ?>"><i class="fa-solid fa-ribbon"></i> <?php _e('Badges', 'wp-courses'); ?></li>
				<li class="wpc-load-profile wpc-load-profile-part-pagination <?php echo esc_attr($liClass); ?>" data-page="1" data-name="certificates" data-title="<?php _e('Certificates', 'wp-courses'); ?>"><i class="fa-solid fa-certificate"></i> <?php _e('Certificates', 'wp-courses'); ?></li>
				<li class="wpc-load-profile wpc-load-profile-part-pagination <?php echo esc_attr($liClass); ?>" data-page="1" data-name="options" data-title="<?php _e('Options', 'wp-courses'); ?>"><i class="fa fa-gears"></i> <?php _e('Options', 'wp-courses'); ?></li>
			<?php } ?>
		</ul>
	<?php }

	/** 
	* @param array $pages An array with page numbers in sequential order ie. array(1,2,3,4,5)
	* @param string $class A class you'd like to apply to every page button which can be used to trigger a specific ajax action
	* @param array $data_attr An array of keys and values of data attributes you'd like added to each button.  For example:  array('name' => 'completed')
	* @return Pagination buttons with ID's which can be used to load pages with ajax
	*/

	function wpc_ajax_pagination($pages, $class = 'wpc-btn wpc-btn-sm wpc-btn-pagination', $data_attr = array('name'	=> 'completed')){
		if( $pages === false || is_array( $pages ) === false ) {
			return '';
		}

		if(count($pages) <= 1) {
			return '';
		}

		$inlineAttr = '';
		foreach($data_attr as $key => $val) {
			$inlineAttr .= 'data-' . $key . '="' . $val .'" ';
		}

		foreach($pages as $page){ ?>
			<button class="<?php echo esc_attr($class); ?>" data-page="<?php echo (int) $page; ?>" <?php echo esc_attr($inlineAttr); ?>><?php echo (int) $page; ?></button>
		<?php }

	}