<?php

add_action('admin_menu', 'wpc_create_menu');
function wpc_create_menu() {
	//create new top-level menu
	add_menu_page('WP Courses', 'WP Courses', 'administrator', 'wpc_settings', 'wpc_settings_page', esc_url(WPC_PLUGIN_URL . 'images/wpc-icon-sm-white.png'));
}

function wpc_settings_page(){ ?>
	<?php include 'admin-nav-menu.php'; ?>
	<div class="wpc-main-admin-wrapper wrap">
		<?php include 'dashboard.php'; ?>
	</div>
<?php }

function wpc_register_submenu(){
	// Free and premium menu 1
	add_submenu_page( 'wpc_settings', esc_html__('Dashboard', 'wp-courses'), esc_html__('Dashboard', 'wp-courses') . ' <span class="wpc-admin-separator"></span>', 'manage_options', 'wpc_settings', 'wpc_settings_page' );
	add_submenu_page( 'wpc_settings', esc_html__('Setup and Help', 'wp-courses'), esc_html__('Setup and Help', 'wp-courses') . ' <i class="fa fa-info-circle" aria-hidden="true"></i>', 'manage_options', 'wpc_help', 'wpc_help_page' );
	add_submenu_page( 'wpc_settings', esc_html__('All Courses', 'wp-courses'), esc_html__('All Courses', 'wp-courses') . ' <span class="wpc-admin-separator"></span>', 'manage_options', 'edit.php?post_type=course' );
	add_submenu_page( 'wpc_settings', esc_html__('All Lessons', 'wp-courses'), esc_html__('All Lessons', 'wp-courses'), 'manage_options', 'edit.php?post_type=lesson' );
    if(WPCP_VERSION > 3.07 || WPCP_VERSION === false) {
    	add_submenu_page( 'wpc_settings', esc_html__('All Quizzes', 'wp-courses'), esc_html__('All Quizzes', 'wp-courses'), 'manage_options', 'edit.php?post_type=wpc-quiz' );
    }

	// Hidden pages
	add_submenu_page( 'wpc_settings', esc_html__('Course Order', 'wp-courses'), esc_html__('Course Order', 'wp-courses') . ' <span class="wpc-admin-hide"></span>', 'manage_options', 'order_courses', 'wpc_order_courses_page' );
	add_submenu_page( 'wpc_settings', esc_html__('Lesson Order', 'wp-courses'), esc_html__('Lesson Order', 'wp-courses') . ' <span class="wpc-admin-hide"></span>', 'manage_options', 'order_lessons', 'wpc_order_lessons_page' );
	add_submenu_page( 'wpc_settings', esc_html__('Shortcode Note', 'wp-courses'), esc_html__('Shortcode Note', 'wp-courses') . ' <span class="wpc-admin-hide"></span>', 'manage_options', 'wpc_shortcode_note', 'wpc_shortcode_note_page' );

	// Free and premium menu 2
	add_submenu_page( 'wpc_settings', esc_html__('All Teachers', 'wp-courses'), esc_html__('All Teachers', 'wp-courses'), 'manage_options', 'edit.php?post_type=teacher' );
	add_submenu_page( 'wpc_settings', esc_html__('All Students', 'wp-courses'), esc_html__('All Students', 'wp-courses'), 'manage_options', 'manage_students', 'wpc_manage_students_page' );
	add_submenu_page( 'wpc_settings', esc_html__('All Settings', 'wp-courses'), esc_html__('Settings', 'wp-courses') . ' <span class="wpc-admin-separator"></span>', 'manage_options', 'wpc_options', 'wpc_options_page' );

    // Free only menu
    if( is_plugin_active( 'wp-courses-premium/wp-courses-premium.php' ) == false ) {
		add_submenu_page( 'wpc_settings', esc_html__('All Emails', 'wp-courses'), esc_html__('All Emails', 'wp-courses') . ' <span class="wpc-admin-separator"></span>', 'manage_options', 'wpc_premium_emails', 'wpc_premium_emails');
		add_submenu_page( 'wpc_settings', esc_html__('All Badges', 'wp-courses'), esc_html__('All Badges', 'wp-courses'), 'manage_options', 'wpc_premium_badges', 'wpc_premium_badges');
		add_submenu_page( 'wpc_settings', esc_html__('All Certificates', 'wp-courses'), esc_html__('All Certificates', 'wp-courses'), 'manage_options', 'wpc_premium_certificates', 'wpc_premium_certificates');
    }

    do_action( 'wpc_after_register_submenu' );
}

add_action('admin_menu', 'wpc_register_submenu');

function wpc_premium_emails() { ?>
	<?php include 'admin-nav-menu.php'; ?>
	<?php include 'templates/premium-emails.php'; ?>
<?php }

function wpc_premium_badges() { ?>
	<?php include 'admin-nav-menu.php'; ?>
	<?php include 'templates/premium-badges.php'; ?>
<?php }

function wpc_premium_certificates() { ?>
	<?php include 'admin-nav-menu.php'; ?>
	<?php include 'templates/premium-certificates.php'; ?>
<?php }

function get_user_and_fallback() {
	 try {
		 $userName = wp_get_current_user()->data->display_name; 
	 } catch(\Error $e) {
		 $userName = 'there';
	 }

	 return $userName;
}

function wpc_help_page(){ ?>
	<?php 
	 $userName = get_user_and_fallback();
	?>

	<?php include 'admin-nav-menu.php'; ?>
	<?php include 'templates/help.php'; ?>
<?php }

function wpc_manage_students_page(){
	include 'admin-nav-menu.php';

	if(isset($_GET['student_id'])){
		include 'templates/single-student.php';
	} else { 
		include 'wpc-admin-student-table.php';
	}
}

// Order lesson page
function wpc_order_lessons_page(){
	include 'admin-nav-menu.php';
	include 'templates/order-lessons.php';
}

// Shortcode note page
function wpc_shortcode_note_page(){
	include 'admin-nav-menu.php';
	include 'templates/shortcode_note.php';
}

add_action( 'admin_footer', 'wpc_change_lesson_restriction_javascript' );

function wpc_order_courses_page(){ ?>
		<?php include 'admin-nav-menu.php'; ?>
		<?php include 'templates/order-courses.php'; ?>
<?php }

// Keep submenu displayed / opened
function manipulate_submenu( $parent_file ) {

	global $plugin_page, $parent_file, $submenu_file;
	$screen = get_current_screen();

	// 1) Keep menu opened
	// Course categories, course difficulties
    if (($screen->base ===  'edit-tags' || $screen->base ===  'term') && $screen->post_type === 'course' && ($screen->taxonomy === 'course-category' || $screen->taxonomy === 'course-difficulty')) {
		$plugin_page = 'wpc_help';
	}
	
	// New quiz, new email, new badge, new certificate
	if ($screen->action ===  'add' && $screen->base ===  'post' && ($screen->post_type === 'wpc-quiz' || $screen->post_type === 'wpc-email' || $screen->post_type === 'wpc-badge' || $screen->post_type === 'wpc-certificate')) {
		$plugin_page = 'wpc_help';
	}

	// 2) Hide highligting
	// Edit quiz, edit email, edit badge, edit certificate
	if ($screen->action ===  '' && $screen->base ===  'post' && ($screen->post_type === 'wpc-quiz' || $screen->post_type === 'wpc-email' || $screen->post_type === 'wpc-badge' || $screen->post_type === 'wpc-certificate')) {
		$plugin_page = 'wpc_help';
		$submenu_file = 'post-new.php?post_type=wpc-badge';
	}

	return $parent_file;
}

add_filter( 'parent_file', 'manipulate_submenu' );