<?php

	class WPC_AJAX_Components {

		public $user_id;
		public $courses_per_page;
		public $profile_results_per_page;

		public function __construct(){

			$this->user_id = get_current_user_id();
			$this->courses_per_page = get_option( 'wpc_courses_per_page');
			$this->profile_results_per_page = 16;

			add_action('wp_ajax_wpc_lesson_toolbar', array($this, 'lessonToolbar'));
			add_action('wp_ajax_wpc_lesson', array($this, 'lesson'));
			add_action('wp_ajax_wpc_attachments', array($this, 'attachments'));
			add_action('wp_ajax_wpc_lesson_navigation', array($this, 'lessonNavigation'));
			add_action('wp_ajax_wpc_course_categories', array($this, 'courseCategories'));
			add_action('wp_ajax_wpc_teacher', array($this, 'teacher'));
			add_action('wp_ajax_wpc_course_toolbar', array($this, 'courseToolbar'));
			add_action('wp_ajax_wpc_course', array($this, 'course'));
			add_action('wp_ajax_wpc_course_archive', array($this, 'courseArchive'));
			add_action('wp_ajax_wpc_profile_nav', array( $this, 'profileNav'));
			add_action('wp_ajax_wpc_profile_part', array( $this, 'profilePart'));
			add_action( 'wp_ajax_wpc_profile_part_pagination', array( $this, 'profilePartPagination'));
			add_action('wp_ajax_wpc_login_form', array( $this, 'loginForm'));
			add_action('wp_ajax_wpc_certificate', array( $this, 'certificate'));

			add_action('wp_ajax_nopriv_wpc_lesson_toolbar', array($this, 'lessonToolbar'));
			add_action('wp_ajax_nopriv_wpc_lesson', array($this, 'lesson'));
			add_action('wp_ajax_nopriv_wpc_attachments', array($this, 'attachments'));
			add_action('wp_ajax_nopriv_wpc_lesson_navigation', array($this, 'lessonNavigation'));
			add_action('wp_ajax_nopriv_wpc_course_categories', array($this, 'courseCategories'));
			add_action('wp_ajax_nopriv_wpc_teacher', array($this, 'teacher'));
			add_action('wp_ajax_nopriv_wpc_course_toolbar', array($this, 'courseToolbar'));
			add_action('wp_ajax_nopriv_wpc_course', array($this, 'course'));
			add_action('wp_ajax_nopriv_wpc_course_archive', array($this, 'courseArchive'));
			add_action('wp_ajax_nopriv_wpc_profile_nav', array( $this, 'profileNav'));
			add_action('wp_ajax_nopriv_wpc_profile_part', array( $this, 'profilePart'));
			add_action( 'wp_ajax_wpc_profile_part_pagination', array( $this, 'profilePartPagination'));
			add_action('wp_ajax_nopriv_wpc_login_form', array( $this, 'loginForm'));
			add_action('wp_ajax_nopriv_wpc_certificate', array( $this, 'certificate'));

		}

		function lesson(){
			check_ajax_referer( 'wpc_nonce', 'security' );
			$lesson_id = (int) $_POST['lesson_id'];
			$course_id = (int) $_POST['course_id'];

			ob_start();
			wpc_lesson($lesson_id, $course_id);
			$lesson = ob_get_clean();

			$icon = get_lesson_icon($this->user_id, $lesson_id, 'lesson');
			$class = get_lesson_li_class($this->user_id, $lesson_id, null);
			
			$return = array(
				'content'	=> $lesson,
				'class'		=> $class,
				'icon'		=> $icon,
			);

			echo json_encode( $return );

			wp_die();
		}

		function lessonNavigation(){
			check_ajax_referer( 'wpc_nonce', 'security' );
			// Only allows $_POST['user_id'] to be passed if in admin area
			if( current_user_can( 'administrator' ) && isset($_POST['user_id']) ) {
				$this->user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : get_current_user_id();
			} else {
				$this->user_id = get_current_user_id();
			}

			$course_id = (int) $_POST['course_id'];
			$ajax = esc_attr( $_POST['ajax'] );
			$lesson_id = (int) $_POST['lesson_id'];

			if($ajax == 'true') {
				wpc_lesson_navigation( $course_id, $this->user_id, false, $lesson_id );
			} else {
				echo wpc_get_classic_lesson_navigation( $course_id, $this->user_id );
			}

			wp_die();
		}

		function lessonToolbar(){
			check_ajax_referer( 'wpc_nonce', 'security' );
			$lesson_id = (int) $_POST['lesson_id'];
			$course_id = (int) $_POST['course_id'];
			wp_kses(wpc_lesson_toolbar($lesson_id, $course_id), 'post');
			wp_die();
		}

		function attachments(){
			check_ajax_referer( 'wpc_nonce', 'security' );
			$lesson_id = (int) $_POST['lesson_id'];
			wpc_attachments($lesson_id);
	        wp_die();
		}

		function courseCategories(){
			check_ajax_referer( 'wpc_nonce', 'security' );

			if($_POST['ajax'] == 'true'){
				wpc_course_categories();
			} else {
				echo wpc_get_course_category_list();
			}
			
			wp_die();
		}

		function teacher(){
			check_ajax_referer( 'wpc_nonce', 'security' );
			$teacher_id = (int) $_POST['id'];
			wpc_teacher( $teacher_id );
			wp_die();
		}

		function courseToolbar(){
			check_ajax_referer( 'wpc_nonce', 'security' );
			$order = isset($_POST['orderby']) ? $_POST['orderby'] : 'menu_order'; // Fallback
			$orderby = sanitize_text_field( $order );
			course_toolbar($orderby);
			wp_die();
		}

		function course(){
			check_ajax_referer( 'wpc_nonce', 'security' );
			$ajax = sanitize_text_field( $_POST['ajax'] );
			$ajax = filter_var( $ajax, FILTER_VALIDATE_BOOLEAN);
			$caller = sanitize_text_field( $_POST['caller'] );
			wpc_course((int) $_POST['id'], $ajax, $caller);
			wp_die();
		}

		function courseArchive(){
			check_ajax_referer( 'wpc_nonce', 'security' );
			$search = sanitize_text_field( $_POST['search'] );
			$orderby = sanitize_text_field( $_POST['orderby'] );
			$page = (int) sanitize_text_field( $_POST['page'] );
			$category = sanitize_text_field( $_POST['category'] );

			$args = array(
				'post_status'		=> 'publish',
				'post_type'			=> 'course',		
				's'					=> $search,
				'orderby'			=> $orderby,
				'paged'				=> $page,
				'order'				=> 'ASC',
				'posts_per_page'	=> get_option('wpc_courses_per_page'),
			);

			if($orderby === 'date'){
				$args['order'] = 'DESC';
			}

			if(isset($category) && !empty($category) && $_POST['category'] !== 'all') {
				$args['tax_query'] = array(
			        array(
			            'taxonomy' => 'course-category',
			            'field'    => 'slug',
			            'terms'    => $category,
			        ),
			    );
			}

			wpc_course_archive($args);
			wp_die();
		}

		function profileNav(){
			check_ajax_referer( 'wpc_nonce', 'security' );
			$ulClass = sanitize_html_class( $_POST['ul_class'] );
			$liClass = sanitize_html_class( $_POST['li_class'] );
			wpc_profile_nav($ulClass, $liClass);
			wp_die();
		}

		function loginForm(){
			check_ajax_referer( 'wpc_nonce', 'security' );
			$redirect = sanitize_url($_POST['redirect']);

			$html = wp_login_form(array(
				'echo' => false, 
				'redirect' => $redirect
			));

			echo $html;
			wp_die();
		}

		function profilePart() {
			check_ajax_referer( 'wpc_nonce', 'security' );

			if(is_user_logged_in() === false){
				echo '<a class="wpc-link" href="' . wp_login_url() . '">' . __('Log in', 'wp-courses') . '</a> ' . __('to view your profile.', 'wp-courses');
				wp_die();
			}

			// Only allows $_POST['user_id'] to be passed if in admin area
			if( current_user_can( 'administrator' ) && isset($_POST['user_id']) ) {
				$this->user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : get_current_user_id();
			} else {
				$this->user_id = get_current_user_id();
			}

			$ajax_links = isset($_POST['ajax_links']) ? sanitize_title($_POST['ajax_links']) : true;
			
			$name = sanitize_title($_POST['name']);
			$page = (int) $_POST['page'];
			$status = isset($_POST['status']) ? sanitize_title($_POST['status']) : 'publish';
			$premium = WPCP_VERSION;
			$premim_active = WPCP_ACTIVE;
			$allowed = 'post';

			if($name === 'viewed'){
				$response = wpc_get_lesson_tracking_table($this->user_id, $page, $this->profile_results_per_page, 0, $ajax_links);
			} else if($name === 'completed'){
				$response = wpc_get_lesson_tracking_table($this->user_id, $page, $this->profile_results_per_page, 1, $ajax_links);
			} elseif($name === 'progress') {
				$args = array(
					'post_type'			=> 'course',
					'posts_per_page'	=> $this->profile_results_per_page,
					'paged'				=> $page,
					'post_status'		=> $status,
				);
				$response = wpc_get_course_progress_table($this->user_id, $args, $ajax_links);
			} elseif($name === 'quiz-results'){
				$response = wpcq_get_results_table($this->user_id, $page, $this->profile_results_per_page);
			} elseif($name === 'badges'){
				if($premim_active === false){
					$response = wpc_feature_upgrade_notice(); // Should not happen
				} else {
					$args = array(
						'post_type'			=> 'wpc-badge',
						'posts_per_page'	=> $this->profile_results_per_page,
						'paged'				=> $page,
						'post_status'		=> $status,
					);
					$response = wpc_get_award_results_table($this->user_id, $args);
				}
			} elseif($name === 'certificates'){
				if($premim_active === false){
					$response = wpc_feature_upgrade_notice(); // Should not happen
				} else {
					$args = array(
						'post_type'			=> 'wpc-certificate',
						'posts_per_page'	=> $this->profile_results_per_page,
						'paged'				=> $page,
						'post_status'		=> $status,
					);
					$response = wpc_get_award_results_table($this->user_id, $args);
				}
			} elseif($name === 'purchased-courses'){
				$show_woo_tab = get_option( 'wpc_woo_tab');

				if(WPCP_ACTIVE == true && $show_woo_tab == 'true' && class_exists( 'WooCommerce' )) {
					$response = wpc_woo_profile_content($this->user_id);
				} else {
					$response = wpc_get_lesson_tracking_table($this->user_id, $page, $this->profile_results_per_page, 0, $ajax_links); // Send viewed
				}
			} elseif($name === 'options'){
				if(function_exists('wpc_get_email_options')) {
					$response = wpc_get_email_options($this->user_id);

					// Exception for input element, which is used for mail notofication option toggle
					$allowed = wp_kses_allowed_html('post');
					$allowed['input'] = array(
						'type' => true,
						'class' => true,
						'id' => true,
						'data-*' => true,
						'value' => true,
						'checked' => true
					);
				}
			}

			echo wp_kses($response, $allowed);

			wp_die();

		}

		function profilePartPagination($name){
			check_ajax_referer( 'wpc_nonce', 'security' );
			$name = sanitize_title($_POST['name']);
			$this->profile_results_per_page;
			
			switch ($name) {

				case 'viewed':
					$tracking_count = wpc_get_user_tracking_count($this->user_id, 0, false);
					$pages = wpc_get_pages($tracking_count, $this->profile_results_per_page);
					$html = wpc_ajax_pagination($pages, 'wpc-load-profile wpc-btn wpc-btn-sm wpc-btn-pagination', array('name' => $name));
				break;

				case 'completed':
					$tracking_count = wpc_get_user_tracking_count($this->user_id, 1, false);
					$pages = wpc_get_pages($tracking_count, $this->profile_results_per_page);
					$html = wpc_ajax_pagination($pages, 'wpc-load-profile wpc-btn wpc-btn-sm wpc-btn-pagination', array('name' => $name));
				break;

				case 'progress':
					$count = wpc_count_posts('course');
					$pages = wpc_get_pages($count, $this->profile_results_per_page);
					$html = wpc_ajax_pagination($pages, 'wpc-load-profile wpc-btn wpc-btn-sm wpc-btn-pagination', array('name' => 'progress'));
				break;

				case 'badges':
					$count = wpc_count_posts('wpc-badge');
					$pages = wpc_get_pages($count, $this->profile_results_per_page);
					$html = wpc_ajax_pagination($pages, 'wpc-load-profile wpc-btn wpc-btn-sm wpc-btn-pagination', array('name' => 'badges'));
				break;

				case 'certificates':
					$course_count = wpc_count_posts('wpc-certificate');
					$pages = wpc_get_pages($course_count, $this->profile_results_per_page);
					$html = wpc_ajax_pagination($pages, 'wpc-load-profile wpc-btn wpc-btn-sm wpc-btn-pagination', array('name' => 'certificates'));
				break;

				case 'quiz-results':
					$total_quiz_attempts = wpcq_count_all_quiz_attempts($this->user_id);
					$pages = wpc_get_pages($total_quiz_attempts, $this->profile_results_per_page);
					$html = wpc_ajax_pagination($pages, 'wpc-load-profile wpc-btn wpc-btn-sm wpc-btn-pagination', array('name' => 'quiz-results'));
				break;

				case 'purchased-courses':
				    $purchased = wpc_woo_get_purchased_course_ids($this->user_id);
				    if($purchased !== false){
				        $pages = wpc_get_pages(count($purchased), $this->profile_results_per_page);
				        $html = wpc_ajax_pagination($pages, 'wpc-load-profile wpc-btn wpc-btn-sm wpc-btn-pagination', array('name' => 'purchased-courses'));
				    }
				break;

			}

			echo $html;

			wp_die();

		}

		function certificate() {
			check_ajax_referer( 'wpc_nonce', 'security' );
			$id = (int) $_POST['id'];
			$user_id = (int) $_POST['user'];
			$design = get_post_meta($id, 'wpc-certificate-design', true);

			$html = '<button class="wpc-btn wpc-btn-sm wpc-btn-nav wpc-load-profile wpc-load-profile-part-pagination" data-page="1" data-name="certificates" data-title="' . __('Certificates') . '"><i class="fa fa-arrow-left"></i> ' . __('Back', 'wp-courses') . '</button>';

			$html .= '<div class="wpc-single-certificate-wrapper"><style>* { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }</style>' . wpc_render_content($id, '', 'width:11in; height:8.5in;', $user_id) . '</div><div class="wpc-modal-tools"><a href="#" class="wpc-btn wpc-print-certificate"><i class="fa fa-print"></i> ' . esc_html__('Print', 'wp-courses-premium') . '</a></div>';
			
			echo $html;
			wp_die();
		}

	}

	add_action('init', 'wpc_ajax_components_init');

	function wpc_ajax_components_init(){
		new WPC_AJAX_Components();
	}