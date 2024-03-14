<?php


/**
 * Renderer for the admin page for publish checklist
 *
 * @function render_manage_page
 */
function render_manage_page() {
	global $PUBLISH_CHECKLIST_DIR;

	$pc_on_publish = get_option('pc_on_publish');
	require_once($PUBLISH_CHECKLIST_DIR . '/inc/views/manage.php');
}

/**
 * Registers JavaScript for the admin view
 *
 * @function pc_register_admin_scripts
 */
function pc_register_admin_scripts() {
	global $PUBLISH_CHECKLIST_URI;

	wp_register_script('pc_vendor', $PUBLISH_CHECKLIST_URI . '/build/js/vendor.min.js');
	wp_enqueue_script('pc_vendor');
	wp_register_script('pc_templates', $PUBLISH_CHECKLIST_URI . '/build/js/templates.js');
	wp_enqueue_script('pc_templates');
	wp_register_script('pc_app', $PUBLISH_CHECKLIST_URI . '/build/js/app.min.js');
	wp_enqueue_script('pc_app');

	wp_enqueue_style('pc_manage', $PUBLISH_CHECKLIST_URI . '/build/css/styles.css');
}

/**
 * Creates the link in settings dropdown for plugin
 *
 * @function add_settings_page
 */
function add_settings_page() {
	add_options_page('Pre-Publish Post Checklist', 'Pre-Publish Post Checklist', 'manage_options', 'manage_publish_checklist', 'render_manage_page');
	add_action('admin_print_scripts-settings_page_manage_publish_checklist', 'pc_register_admin_scripts');
}

add_action('admin_menu', 'add_settings_page');


function pc_create_meta_box_callback() {
	global $post;
	global $PUBLISH_CHECKLIST_URI;

	echo '<script type="application/javascript">'
	     . 'var pcPostId = ' . $post->ID . ';'
	     . 'var pcPageLink = "' . admin_url("options-general.php?page=manage_publish_checklist") . '";'
	     . '</script>';

	wp_register_script('pc_vendor', $PUBLISH_CHECKLIST_URI . '/build/js/vendor.min.js');
	wp_enqueue_script('pc_vendor');
	wp_register_script('pc_templates', $PUBLISH_CHECKLIST_URI . '/build/js/templates.js');
	wp_enqueue_script('pc_templates');
	wp_register_script('pc_app', $PUBLISH_CHECKLIST_URI . '/build/js/app.min.js');
	wp_enqueue_script('pc_app');


	wp_enqueue_style('pc_manage', $PUBLISH_CHECKLIST_URI . '/build/css/styles.css');
}

/**
 *
 */
function pc_create_meta_box() {
	$screens = array('post', 'page');

	foreach ($screens as $screen) {

		add_meta_box(
			'pc-meta-box',
			'Pre-Publish Post Checklist',
			'pc_create_meta_box_callback',
			$screen,
			'side'
		);
	}
}

add_action('add_meta_boxes', 'pc_create_meta_box');