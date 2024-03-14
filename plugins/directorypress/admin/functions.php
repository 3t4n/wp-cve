<?php

function directorypress_pull_current_listing_admin() {
	global $directorypress_object;
	
	return $directorypress_object->current_listing;
}
function directorypress_is_admin_directory_page() {
	global $pagenow;

	if (
		is_admin() &&
		(($pagenow == 'edit-tags.php' || $pagenow == 'term.php') && ($taxonomy = directorypress_get_input_value($_GET, 'taxonomy')) &&
				(in_array($taxonomy, array(DIRECTORYPRESS_LOCATIONS_TAX, DIRECTORYPRESS_CATEGORIES_TAX, DIRECTORYPRESS_TAGS_TAX)))
		) ||
		(($page = directorypress_get_input_value($_GET, 'page')) &&
				(in_array($page,
						array(
								'directorypress-admin-panel',
								'directorypress_admin_settings',
								'directorypress_directorytypes',
								'directorypress_packages',
								//'directorypress_manage_upgrades',
								'directorypress_locations_depths',
								'directorypress_fields',
								//'directorypress_csv_import',
								'directorypress_renew',
								'directorypress_upgrade',
								'directorypress_changedate',
								'directorypress_raise_up',
								'directorypress_upgrade',
								'directorypress_upgrade_bulk',
								'directorypress_process_claim',
								'directorypress_choose_package'
						)
				))
		)
	) {
		return true;
	}
}
function directorypress_is_admin_terms_page() {
	global $pagenow;

	if (
		is_admin() &&
		(($pagenow == 'edit-tags.php' || $pagenow == 'term.php') && ($taxonomy = directorypress_get_input_value($_GET, 'taxonomy')) &&
				(in_array($taxonomy, array(DIRECTORYPRESS_CATEGORIES_TAX)))
		)
	) {
		return true;
	}
}
function directorypress_is_listing_admin_edit_page() {
	global $pagenow;

	if (
		($pagenow == 'post-new.php' && ($post_type = directorypress_get_input_value($_GET, 'post_type')) &&
				(in_array($post_type, array(DIRECTORYPRESS_POST_TYPE)))
		) ||
		($pagenow == 'post.php' && ($post_id = directorypress_get_input_value($_GET, 'post')) && ($post = get_post($post_id)) &&
				(in_array($post->post_type, array(DIRECTORYPRESS_POST_TYPE)))
		)
	) {
		return true;
	}
}
add_action('directorypress_reduxt_custom_header_before', 'directorypress_redux_template_header_before');
function directorypress_redux_template_header_before(){
	if(isset($_GET['page'])){
		if($_GET['page'] == 'directorypress_settings'){
			echo '<div class="wrap about-wrap directorypress-admin-wrap">';
				DirectoryPress_Admin_Panel::listing_dashboard_header();
				echo '<div class="directorypress-plugins directorypress-theme-browser-wrap';
					echo '<div class="theme-browser rendered">';
						echo '<div class="directorypress-box">';
							echo '<div class="directorypress-box-head">';
								echo '<h1>'. esc_html__('DirectoryPress Settings', 'DIRECTORYPRESS').'</h1>';
								echo '<p>'. esc_html__('All DirectoryPress Settings can be handle here', 'DIRECTORYPRESS').'</p>';
							echo '</div>';
							echo '<div class="directorypress-box-content wp-clearfix">';
		}
	}
}
add_action('directorypress_reduxt_custom_header_after', 'directorypress_redux_template_header_after');
function directorypress_redux_template_header_after(){
	echo '</div></div></div></div></div></div>';
}