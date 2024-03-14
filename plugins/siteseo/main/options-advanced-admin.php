<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

//MANDATORY for using is_plugin_active
include_once ABSPATH . 'wp-admin/includes/plugin.php';

global $pagenow;

////////////////////////
//Admin notices
////////////////////////

//Permalinks notice
if(isset($pagenow) && 'options-permalink.php' == $pagenow){
	
	add_action('admin_notices', 'siteseo_notice_permalinks');
	function siteseo_notice_permalinks(){
		$class   = 'notice notice-warning';
		$message = '<strong>' . __('WARNING', 'siteseo') . '</strong>';
		$message .= '<p>' . __('Do NOT change your permalink structure on a production site. Changing URLs can severely damage your SEO.', 'siteseo') . '</p>';

		printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), wp_kses_post($message));
	}

	if ('' == get_option('permalink_structure')){ //If default permalink
		function siteseo_notice_no_rewrite_url(){
			$class   = 'notice notice-warning';
			$message = '<strong>' . __('WARNING', 'siteseo') . '</strong>';
			$message .= '<p>' . __('URL rewriting is NOT enabled on your site. Select a permalink structure that is optimized for SEO (NOT Plain).', 'siteseo') . '</p>';

			printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), wp_kses_post($message));
		}
		add_action('admin_notices', 'siteseo_notice_no_rewrite_url');
	}
}

////////////////////////
//Advanced
////////////////////////

//Cleaning filename
if(siteseo_get_service('AdvancedOption')->getAdvancedCleaningFileName() === '1'){
	
	add_filter('sanitize_file_name', 'siteseo_image_seo_cleaning_filename', 10);
	function siteseo_image_seo_cleaning_filename($filename){
		$filename = apply_filters( 'siteseo_image_seo_before_cleaning', $filename );

		/* Force the file name in UTF-8 (encoding Windows / OS X / Linux) */
		$filename = mb_convert_encoding($filename, "UTF-8");

		$char_not_clean = ['/•/','/·/','/À/','/Á/','/Â/','/Ã/','/Ä/','/Å/','/Ç/','/È/','/É/','/Ê/','/Ë/','/Ì/','/Í/','/Î/','/Ï/','/Ò/','/Ó/','/Ô/','/Õ/','/Ö/','/Ù/','/Ú/','/Û/','/Ü/','/Ý/','/à/','/á/','/â/','/ã/','/ä/','/å/','/ç/','/è/','/é/','/ê/','/ë/','/ì/','/í/','/î/','/ï/','/ð/','/ò/','/ó/','/ô/','/õ/','/ö/','/ù/','/ú/','/û/','/ü/','/ý/','/ÿ/', '/©/'];

		$char_not_clean = apply_filters( 'siteseo_image_seo_clean_input', $char_not_clean );

		$clean = ['-','-','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','u','u','u','u','y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','o','u','u','u','u','y','y','copy'];

		$clean = apply_filters( 'siteseo_image_seo_clean_output', $clean );

		$friendly_filename = preg_replace($char_not_clean, $clean, $filename);

		/* After replacement, we destroy the last residues */
		$friendly_filename = utf8_decode($friendly_filename);
		$friendly_filename = preg_replace('/\?/', '', $friendly_filename);

		/* Remove uppercase */
		$friendly_filename = strtolower($friendly_filename);

		$friendly_filename = apply_filters( 'siteseo_image_seo_after_cleaning', $friendly_filename );

		return $friendly_filename;
	}
}

if ('' != siteseo_get_service('AdvancedOption')->getImageAutoTitleEditor() ||
'' != siteseo_get_service('AdvancedOption')->getImageAutoAltEditor() ||
'' != siteseo_get_service('AdvancedOption')->getImageAutoCaptionEditor() ||
'' != siteseo_get_service('AdvancedOption')->getImageAutoDescriptionEditor()) {
	add_action('add_attachment', 'siteseo_auto_image_attr');
	function siteseo_auto_image_attr($post_ID){
		if (wp_attachment_is_image($post_ID)) {

			$parent = get_post($post_ID)->post_parent ? get_post($post_ID)->post_parent : null;
			$cpt = get_post_type($parent) ?  get_post_type($parent) : null;

			if (isset($cpt) && isset($parent) && $cpt === 'product') { //use the product title for WC products
				$img_attr = get_post($parent)->post_title;
			} else {
				$img_attr = get_post($post_ID)->post_title;
			}

			// Sanitize the title: remove hyphens, underscores & extra spaces:
			$img_attr = preg_replace('%\s*[-_\s]+\s*%', ' ', $img_attr);

			// Lowercase attributes
			$img_attr = strtolower($img_attr);

			$img_attr = apply_filters('siteseo_auto_image_title', $img_attr, $cpt, $parent);

			// Create an array with the image meta (Title, Caption, Description) to be updated
			$img_attr_array = ['ID'=>$post_ID]; // Image (ID) to be updated

			if ('' != siteseo_get_service('AdvancedOption')->getImageAutoTitleEditor()) {
				$img_attr_array['post_title'] = $img_attr; // Set image Title
			}

			if ('' != siteseo_get_service('AdvancedOption')->getImageAutoCaptionEditor()) {
				$img_attr_array['post_excerpt'] = $img_attr; // Set image Caption
			}

			if ('' != siteseo_get_service('AdvancedOption')->getImageAutoDescriptionEditor()) {
				$img_attr_array['post_content'] = $img_attr; // Set image Desc
			}

			$img_attr_array = apply_filters('siteseo_auto_image_attr', $img_attr_array);

			// Set the image Alt-Text
			if ('' != siteseo_get_service('AdvancedOption')->getImageAutoAltEditor()) {
				update_post_meta($post_ID, '_wp_attachment_image_alt', $img_attr);
			}

			// Set the image meta (e.g. Title, Excerpt, Content)
			if ('' != siteseo_get_service('AdvancedOption')->getImageAutoTitleEditor() || '' != siteseo_get_service('AdvancedOption')->getImageAutoCaptionEditor() || '' != siteseo_get_service('AdvancedOption')->getImageAutoDescriptionEditor()) {
				wp_update_post($img_attr_array);
			}
		}
	}
}

if('' != siteseo_get_service('AdvancedOption')->getAppearanceTitleCol()
|| '' != siteseo_get_service('AdvancedOption')->getAppearanceMetaDescriptionCol()
|| '' != siteseo_get_service('AdvancedOption')->getAppearanceRedirectEnableCol()
|| '' != siteseo_get_service('AdvancedOption')->getAppearanceRedirectUrlCol()
|| '' != siteseo_get_service('AdvancedOption')->getAppearanceCanonical()
|| '' != siteseo_get_service('AdvancedOption')->getAppearanceTargetKwCol()
|| '' != siteseo_get_service('AdvancedOption')->getAppearanceNoIndexCol()
|| '' != siteseo_get_service('AdvancedOption')->getAppearanceNoFollowCol()
|| '' != siteseo_get_service('AdvancedOption')->getAppearanceWordsCol()
|| '' != siteseo_get_service('AdvancedOption')->getAppearancePsCol()
|| '' != siteseo_get_service('AdvancedOption')->getAppearanceScoreCol()
|| !empty(siteseo_get_service('AdvancedOption')->getAppearanceSearchConsole())

){
	function siteseo_add_columns(){
		if(!isset(get_current_screen()->post_type)){
			return;
		}

		$key = get_current_screen()->post_type;
		if (null === siteseo_titles_single_cpt_enable_option($key) && '' != $key) {
			$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
			if (array_key_exists($key, $postTypes)) {
				add_filter('manage_' . $key . '_posts_columns', 'siteseo_title_columns');
				add_action('manage_' . $key . '_posts_custom_column', 'siteseo_title_display_column', 10, 2);
				if (is_plugin_active('easy-digital-downloads/easy-digital-downloads.php')) {
					add_filter('manage_edit-' . $key . '_columns', 'siteseo_title_columns');
				}
			}
		}
	}

	$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
	
	// Sortable columns
	foreach ($postTypes as $key => $value) {
		add_filter('manage_edit-' . $key . '_sortable_columns', 'siteseo_admin_sortable_columns');
	}

	function siteseo_admin_sortable_columns($columns){
		
		$columns['siteseo_noindex']  = 'siteseo_noindex';
		$columns['siteseo_nofollow'] = 'siteseo_nofollow';
		$columns['siteseo_search_console_clicks'] = 'siteseo_search_console_clicks';
		$columns['siteseo_search_console_ctr'] = 'siteseo_search_console_ctr';
		$columns['siteseo_search_console_impressions'] = 'siteseo_search_console_impressions';
		$columns['siteseo_search_console_position'] = 'siteseo_search_console_position';

		return $columns;
	}

	add_filter('pre_get_posts', 'siteseo_admin_sort_columns_by');
	function siteseo_admin_sort_columns_by($query){
		
		if(! is_admin()){
			return;
		}

		$orderby = $query->get('orderby');
		if ('siteseo_noindex' == $orderby) {
			$query->set('meta_key', '_siteseo_robots_index');
			$query->set('orderby', 'meta_value');
		}
		if ('siteseo_nofollow' == $orderby) {
			$query->set('meta_key', '_siteseo_robots_follow');
			$query->set('orderby', 'meta_value');
		}
		if ('siteseo_search_console_clicks' == $orderby) {
			$query->set('meta_key', '_siteseo_search_console_analysis_clicks');
			$query->set('orderby', 'meta_value_num');
		}
		if ('siteseo_search_console_impressions' == $orderby) {
			$query->set('meta_key', '_siteseo_search_console_analysis_impressions');
			$query->set('orderby', 'meta_value_num');
		}
		if ('siteseo_search_console_ctr' == $orderby) {
			$query->set('meta_key', '_siteseo_search_console_analysis_ctr');
			$query->set('orderby', 'meta_value_num');
		}
		if ('siteseo_search_console_position' == $orderby) {
			$query->set('meta_key', '_siteseo_search_console_analysis_position');
			$query->set('orderby', 'meta_value_num');
		}
	}
}

//Remove Content Analysis Metaboxe
if('' != siteseo_get_service('AdvancedOption')->getAppearanceCaMetaboxe()){
	function siteseo_advanced_appearance_ca_metaboxe_hook(){
		add_filter('siteseo_metaboxe_content_analysis', '__return_false');
	}
	add_action('init', 'siteseo_advanced_appearance_ca_metaboxe_hook', 999);
}

//Remove Genesis SEO Metaboxe
if('' != siteseo_get_service('AdvancedOption')->getAppearanceGenesisSeoMetaboxe()){
	function siteseo_advanced_appearance_genesis_seo_metaboxe_hook(){
		remove_action('admin_menu', 'genesis_add_inpost_seo_box');
	}
	add_action('init', 'siteseo_advanced_appearance_genesis_seo_metaboxe_hook', 999);
}

//Remove Genesis SEO Menu Link
if('' != siteseo_get_service('AdvancedOption')->getAppearanceGenesisSeoMenu()){
	function siteseo_advanced_appearance_genesis_seo_menu_hook(){
		remove_theme_support('genesis-seo-settings-menu');
	}
	add_action('init', 'siteseo_advanced_appearance_genesis_seo_menu_hook', 999);
}

$postTypes = siteseo_get_service('WordPressData')->getPostTypes();

//Bulk actions
//noindex
foreach ($postTypes as $key => $value) {
	add_filter('bulk_actions-edit-' . $key, 'siteseo_bulk_actions_noindex');
}

foreach (siteseo_get_service('WordPressData')->getTaxonomies() as $key => $value) {
	add_filter('bulk_actions-edit-' . $key, 'siteseo_bulk_actions_noindex');
}

if (is_plugin_active('woocommerce/woocommerce.php')) {
	add_filter('bulk_actions-edit-product', 'siteseo_bulk_actions_noindex');
}

function siteseo_bulk_actions_noindex($bulk_actions){
	$bulk_actions['siteseo_noindex'] = __('Enable noindex', 'siteseo');

	return $bulk_actions;
}

foreach($postTypes as $key => $value){
	add_filter('handle_bulk_actions-edit-' . $key, 'siteseo_bulk_action_noindex_handler', 10, 3);
}

foreach(siteseo_get_service('WordPressData')->getTaxonomies() as $key => $value){
	add_filter('handle_bulk_actions-edit-' . $key, 'siteseo_bulk_action_noindex_handler', 10, 3);
}

if(is_plugin_active('woocommerce/woocommerce.php')){
	add_filter('handle_bulk_actions-edit-product', 'siteseo_bulk_action_noindex_handler', 10, 3);
}

function siteseo_bulk_action_noindex_handler($redirect_to, $doaction, $post_ids){
	if ('siteseo_noindex' !== $doaction) {
		return $redirect_to;
	}
	foreach ($post_ids as $post_id) {
		// Perform action for each post/term
		update_post_meta($post_id, '_siteseo_robots_index', 'yes');
		update_term_meta($post_id, '_siteseo_robots_index', 'yes');
	}
	$redirect_to = add_query_arg('bulk_noindex_posts', count($post_ids), $redirect_to);

	return $redirect_to;
}

add_action('admin_notices', 'siteseo_bulk_action_noindex_admin_notice');
function siteseo_bulk_action_noindex_admin_notice(){
	if (! empty($_REQUEST['bulk_noindex_posts'])) {
		$noindex_count = intval($_REQUEST['bulk_noindex_posts']);
		printf('<div id="message" class="updated fade"><p>' .
				esc_html(_n(
					'%s post to noindex.',
					'%s posts to noindex.',
					$noindex_count,
					'siteseo'
				)) . '</p></div>', esc_html($noindex_count));
	}
}

$postTypes = siteseo_get_service('WordPressData')->getPostTypes();

// Index
foreach ($postTypes as $key => $value) {
	add_filter('bulk_actions-edit-' . $key, 'siteseo_bulk_actions_index');
}

foreach (siteseo_get_service('WordPressData')->getTaxonomies() as $key => $value) {
	add_filter('bulk_actions-edit-' . $key, 'siteseo_bulk_actions_index');
}

if(is_plugin_active('woocommerce/woocommerce.php')){
	add_filter('bulk_actions-edit-product', 'siteseo_bulk_actions_index');
}

function siteseo_bulk_actions_index($bulk_actions){
	$bulk_actions['siteseo_index'] = __('Enable index', 'siteseo');

	return $bulk_actions;
}

foreach ($postTypes as $key => $value) {
	add_filter('handle_bulk_actions-edit-' . $key, 'siteseo_bulk_action_index_handler', 10, 3);
}

foreach (siteseo_get_service('WordPressData')->getTaxonomies() as $key => $value) {
	add_filter('handle_bulk_actions-edit-' . $key, 'siteseo_bulk_action_index_handler', 10, 3);
}

if (is_plugin_active('woocommerce/woocommerce.php')) {
	add_filter('handle_bulk_actions-edit-product', 'siteseo_bulk_action_index_handler', 10, 3);
}

function siteseo_bulk_action_index_handler($redirect_to, $doaction, $post_ids){
	
	if ('siteseo_index' !== $doaction) {
		return $redirect_to;
	}
	
	foreach ($post_ids as $post_id) {
		// Perform action for each post.
		delete_post_meta($post_id, '_siteseo_robots_index', '');
		delete_term_meta($post_id, '_siteseo_robots_index', '');
	}
	$redirect_to = add_query_arg('bulk_index_posts', count($post_ids), $redirect_to);

	return $redirect_to;
}

add_action('admin_notices', 'siteseo_bulk_action_index_admin_notice');
function siteseo_bulk_action_index_admin_notice(){
	if (! empty($_REQUEST['bulk_index_posts'])) {
		$index_count = intval($_REQUEST['bulk_index_posts']);
		printf('<div id="message" class="updated fade"><p>' .
				esc_html(_n(
					'%s post to index.',
					'%s posts to index.',
					$index_count,
					'siteseo'
				)) . '</p></div>', esc_html($index_count));
	}
}

//nofollow
foreach ($postTypes as $key => $value) {
	add_filter('bulk_actions-edit-' . $key, 'siteseo_bulk_actions_nofollow');
}

foreach (siteseo_get_service('WordPressData')->getTaxonomies() as $key => $value) {
	add_filter('bulk_actions-edit-' . $key, 'siteseo_bulk_actions_nofollow');
}

if (is_plugin_active('woocommerce/woocommerce.php')) {
	add_filter('bulk_actions-edit-product', 'siteseo_bulk_actions_nofollow');
}

function siteseo_bulk_actions_nofollow($bulk_actions){
	$bulk_actions['siteseo_nofollow'] = __('Enable nofollow', 'siteseo');

	return $bulk_actions;
}

foreach ($postTypes as $key => $value) {
	add_filter('handle_bulk_actions-edit-' . $key, 'siteseo_bulk_action_nofollow_handler', 10, 3);
}

foreach (siteseo_get_service('WordPressData')->getTaxonomies() as $key => $value) {
	add_filter('handle_bulk_actions-edit-' . $key, 'siteseo_bulk_action_nofollow_handler', 10, 3);
}

if (is_plugin_active('woocommerce/woocommerce.php')) {
	add_filter('handle_bulk_actions-edit-product', 'siteseo_bulk_action_nofollow_handler', 10, 3);
}

function siteseo_bulk_action_nofollow_handler($redirect_to, $doaction, $post_ids){
	
	if('siteseo_nofollow' !== $doaction) {
		return $redirect_to;
	}
	
	foreach ($post_ids as $post_id) {
		// Perform action for each post.
		update_post_meta($post_id, '_siteseo_robots_follow', 'yes');
		update_term_meta($post_id, '_siteseo_robots_follow', 'yes');
	}
	
	$redirect_to = add_query_arg('bulk_nofollow_posts', count($post_ids), $redirect_to);

	return $redirect_to;
}

add_action('admin_notices', 'siteseo_bulk_action_nofollow_admin_notice');
function siteseo_bulk_action_nofollow_admin_notice(){
	if (! empty($_REQUEST['bulk_nofollow_posts'])) {
		$nofollow_count = intval($_REQUEST['bulk_nofollow_posts']);
		printf('<div id="message" class="updated fade"><p>' .
				esc_html(_n(
					'%s post to nofollow.',
					'%s posts to nofollow.',
					$nofollow_count,
					'siteseo'
				)) . '</p></div>', esc_html($nofollow_count));
	}
}

// Follow
foreach ($postTypes as $key => $value) {
	add_filter('bulk_actions-edit-' . $key, 'siteseo_bulk_actions_follow');
}

foreach (siteseo_get_service('WordPressData')->getTaxonomies() as $key => $value) {
	add_filter('bulk_actions-edit-' . $key, 'siteseo_bulk_actions_follow');
}

if(is_plugin_active('woocommerce/woocommerce.php')){
	add_filter('bulk_actions-edit-product', 'siteseo_bulk_actions_follow');
}

function siteseo_bulk_actions_follow($bulk_actions){
	$bulk_actions['siteseo_follow'] = __('Enable follow', 'siteseo');

	return $bulk_actions;
}

foreach ($postTypes as $key => $value) {
	add_filter('handle_bulk_actions-edit-' . $key, 'siteseo_bulk_action_follow_handler', 10, 3);
}

foreach (siteseo_get_service('WordPressData')->getTaxonomies() as $key => $value) {
	add_filter('handle_bulk_actions-edit-' . $key, 'siteseo_bulk_action_follow_handler', 10, 3);
}

if (is_plugin_active('woocommerce/woocommerce.php')) {
	add_filter('handle_bulk_actions-edit-product', 'siteseo_bulk_action_follow_handler', 10, 3);
}

function siteseo_bulk_action_follow_handler($redirect_to, $doaction, $post_ids){
	
	if ('siteseo_follow' !== $doaction) {
		return $redirect_to;
	}
	
	foreach ($post_ids as $post_id) {
		// Perform action for each post.
		delete_post_meta($post_id, '_siteseo_robots_follow');
		delete_term_meta($post_id, '_siteseo_robots_follow');
	}
	
	$redirect_to = add_query_arg('bulk_follow_posts', count($post_ids), $redirect_to);

	return $redirect_to;
}

add_action('admin_notices', 'siteseo_bulk_action_follow_admin_notice');
function siteseo_bulk_action_follow_admin_notice(){
	if (! empty($_REQUEST['bulk_follow_posts'])) {
		$follow_count = intval($_REQUEST['bulk_follow_posts']);
		printf('<div id="message" class="updated fade"><p>' .
				esc_html(_n(
					'%s post to follow.',
					'%s posts to follow.',
					$follow_count,
					'siteseo'
				)) . '</p></div>', esc_html($follow_count));
	}
}

// Enable 301
foreach ($postTypes as $key => $value) {
	add_filter('bulk_actions-edit-' . $key, 'siteseo_bulk_actions_redirect_enable');
}

function siteseo_bulk_actions_redirect_enable($bulk_actions){
	$bulk_actions['siteseo_enable'] = __('Enable redirection', 'siteseo');

	return $bulk_actions;
}

foreach ($postTypes as $key => $value) {
	add_filter('handle_bulk_actions-edit-' . $key, 'siteseo_bulk_action_redirect_enable_handler', 10, 3);
}

function siteseo_bulk_action_redirect_enable_handler($redirect_to, $doaction, $post_ids){
	if ('siteseo_enable' !== $doaction) {
		return $redirect_to;
	}
	foreach ($post_ids as $post_id) {
		// Perform action for each post.
		update_post_meta($post_id, '_siteseo_redirections_enabled', 'yes');
	}
	$redirect_to = add_query_arg('bulk_enable_redirects_posts', count($post_ids), $redirect_to);

	return $redirect_to;
}

add_action('admin_notices', 'siteseo_bulk_action_redirect_enable_admin_notice');
function siteseo_bulk_action_redirect_enable_admin_notice(){
	if (! empty($_REQUEST['bulk_enable_redirects_posts'])) {
		$enable_count = intval($_REQUEST['bulk_enable_redirects_posts']);
		printf('<div id="message" class="updated fade"><p>' .
				esc_html(_n(
					'%s redirections enabled.',
					'%s redirections enabled.',
					$enable_count,
					'siteseo'
				)) . '</p></div>', esc_html($enable_count));
	}
}

//disable 301
foreach ($postTypes as $key => $value) {
	add_filter('bulk_actions-edit-' . $key, 'siteseo_bulk_actions_redirect_disable');
}

function siteseo_bulk_actions_redirect_disable($bulk_actions){
	$bulk_actions['siteseo_disable'] = __('Disable redirection', 'siteseo');

	return $bulk_actions;
}

foreach ($postTypes as $key => $value){
	add_filter('handle_bulk_actions-edit-' . $key, 'siteseo_bulk_action_redirect_disable_handler', 10, 3);
}

function siteseo_bulk_action_redirect_disable_handler($redirect_to, $doaction, $post_ids){
	if ('siteseo_disable' !== $doaction) {
		return $redirect_to;
	}
	foreach ($post_ids as $post_id) {
		// Perform action for each post.
		update_post_meta($post_id, '_siteseo_redirections_enabled', '');
	}
	$redirect_to = add_query_arg('bulk_disable_redirects_posts', count($post_ids), $redirect_to);

	return $redirect_to;
}

add_action('admin_notices', 'siteseo_bulk_action_redirect_disable_admin_notice');
function siteseo_bulk_action_redirect_disable_admin_notice(){
	if (! empty($_REQUEST['bulk_disable_redirects_posts'])) {
		$enable_count = intval($_REQUEST['bulk_disable_redirects_posts']);
		printf('<div id="message" class="updated fade"><p>' .
				esc_html(_n(
					'%s redirection disabled.',
					'%s redirections disabled.',
					$enable_count,
					'siteseo'
				)) . '</p></div>', esc_html($enable_count));
	}
}

//Quick Edit
add_action('quick_edit_custom_box', 'siteseo_bulk_quick_edit_custom_box', 10, 2);
function siteseo_bulk_quick_edit_custom_box($column_name){
	static $printNonce = true;
	if ($printNonce) {
		$printNonce = false;
		wp_nonce_field(plugin_basename(__FILE__), 'siteseo_title_edit_nonce');
	} ?>
<div class="wp-clearfix"></div>
<fieldset class="inline-edit-col-left">
	<div class="inline-edit-col column-<?php echo esc_attr($column_name); ?>">

		<?php
				switch ($column_name) {
				case 'siteseo_title':
				?>
		<h4><?php esc_html_e('SiteSEO', 'siteseo'); ?>
		</h4>
		<label class="inline-edit-group">
			<span class="title"><?php esc_html_e('Title tag', 'siteseo'); ?></span>
			<span class="input-text-wrap"><input type="text" name="siteseo_title" /></span>
		</label>
		<?php
				break;
				case 'siteseo_desc':
				?>
		<label class="inline-edit-group">
			<span class="title"><?php esc_html_e('Meta description', 'siteseo'); ?></span>
			<span class="input-text-wrap"><textarea cols="18" rows="1" name="siteseo_desc" autocomplete="off"
					role="combobox" aria-autocomplete="list" aria-expanded="false"></textarea></span>
		</label>
		<?php
				break;
				case 'siteseo_tkw':
				?>
		<label class="inline-edit-group">
			<span class="title"><?php esc_html_e('Target keywords', 'siteseo'); ?></span>
			<span class="input-text-wrap"><input type="text" name="siteseo_tkw" /></span>
		</label>
		<?php
				break;
				case 'siteseo_canonical':
				?>
		<label class="inline-edit-group">
			<span class="title"><?php esc_html_e('Canonical', 'siteseo'); ?></span>
			<span class="input-text-wrap"><input type="text" name="siteseo_canonical" /></span>
		</label>
		<?php
				break;
				case 'siteseo_noindex':
				?>
		<label class="alignleft">
			<input type="checkbox" name="siteseo_noindex" value="yes">
			<span class="checkbox-title"><?php echo wp_kses_post(__('Do not display this page in search engine results / Sitemaps <strong>(noindex)</strong>', 'siteseo')); ?></span>
		</label>
		<?php
				break;
				case 'siteseo_nofollow':
				?>
		<label class="alignleft">
			<input type="checkbox" name="siteseo_nofollow" value="yes">
			<span class="checkbox-title"><?php echo wp_kses_post(__('Do not follow links for this page <strong>(nofollow)</strong>', 'siteseo')); ?></span>
		</label>
		<?php
				break;
				default:
				break;
				} ?>
	</div>
</fieldset>
<?php
}

add_action('save_post', 'siteseo_bulk_quick_edit_save_post', 10, 2);
function siteseo_bulk_quick_edit_save_post($post_id){
	
	// don't save if Elementor library
	if (isset($_REQUEST['post_type']) && 'elementor_library' == $_REQUEST['post_type']) {
		return $post_id;
	}

	// don't save for autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// dont save for revisions
	if (isset($_REQUEST['post_type']) && 'revision' == $_REQUEST['post_type']) {
		return $post_id;
	}

	if (! current_user_can('edit_posts', $post_id)) {
		return;
	}

	$_REQUEST += ['siteseo_title_edit_nonce' => '']; //phpcs:ignore

	if (! wp_verify_nonce(siteseo_opt_req('siteseo_title_edit_nonce'), plugin_basename(__FILE__))) {
		return;
	}
	if (isset($_REQUEST['siteseo_title'])) {
		update_post_meta($post_id, '_siteseo_titles_title', siteseo_opt_req('siteseo_title'));
	}
	if (isset($_REQUEST['siteseo_desc'])) {
		update_post_meta($post_id, '_siteseo_titles_desc', siteseo_opt_req('siteseo_desc'));
	}
	if (isset($_REQUEST['siteseo_tkw'])) {
		update_post_meta($post_id, '_siteseo_analysis_target_kw', siteseo_opt_req('siteseo_tkw'));
	}
	if (isset($_REQUEST['siteseo_canonical'])) {
		update_post_meta($post_id, '_siteseo_robots_canonical', siteseo_opt_req('siteseo_canonical'));
	}
	if ('' != siteseo_get_service('AdvancedOption')->getAppearanceNoIndexCol()) {
		if (isset($_REQUEST['siteseo_noindex'])) {
			update_post_meta($post_id, '_siteseo_robots_index', 'yes');
		} else {
			delete_post_meta($post_id, '_siteseo_robots_index');
		}
	}
	if ('' != siteseo_get_service('AdvancedOption')->getAppearanceNoFollowCol()) {
		if (isset($_REQUEST['siteseo_nofollow'])) {
			update_post_meta($post_id, '_siteseo_robots_follow', 'yes');
		} else {
			delete_post_meta($post_id, '_siteseo_robots_follow');
		}
	}

	// Elementor sync
	if (did_action('elementor/loaded')) {
		$elementor = get_post_meta($post_id, '_elementor_page_settings', true);

		if (! empty($elementor)) {
			if (isset($_REQUEST['siteseo_title'])) {
				$elementor['_siteseo_titles_title'] = siteseo_opt_req('siteseo_title');
			}
			if (isset($_REQUEST['siteseo_desc'])) {
				$elementor['_siteseo_titles_desc'] = siteseo_opt_req('siteseo_desc');
			}
			if (isset($_REQUEST['siteseo_noindex'])) {
				$elementor['_siteseo_robots_index'] = 'yes';
			} else {
				$elementor['_siteseo_robots_index'] = '';
			}
			if (isset($_REQUEST['siteseo_nofollow'])) {
				$elementor['_siteseo_robots_follow'] = 'yes';
			} else {
				$elementor['_siteseo_robots_follow'] = '';
			}
			if (isset($_REQUEST['siteseo_canonical'])) {
				$elementor['_siteseo_robots_canonical'] = siteseo_opt_req('siteseo_canonical');
			}
			if (isset($_REQUEST['siteseo_tkw'])) {
				$elementor['_siteseo_analysis_target_kw'] = siteseo_opt_req('siteseo_tkw');
			}
			update_post_meta($post_id, '_elementor_page_settings', $elementor);
		}
	}
}

//WP Editor on taxonomy description field
if ('' != siteseo_get_service('AdvancedOption')->searchOptionByKey('advanced_tax_desc_editor') && current_user_can('publish_posts')) {
	
	add_action('init', 'siteseo_tax_desc_wp_editor_init', 100);
	function siteseo_tax_desc_wp_editor_init(){
		global $pagenow;
		
		if ('term.php' == $pagenow || 'edit-tags.php' == $pagenow) {
			remove_filter('pre_term_description', 'wp_filter_kses');
			remove_filter('term_description', 'wp_kses_data');

			//Disallow HTML Tags
			if (! current_user_can('unfiltered_html')) {
				add_filter('pre_term_description', 'wp_kses_post');
				add_filter('term_description', 'wp_kses_post');
			}

			//Allow HTML Tags
			add_filter('term_description', 'wptexturize');
			add_filter('term_description', 'convert_smilies');
			add_filter('term_description', 'convert_chars');
			add_filter('term_description', 'wpautop');
		}
	}

	function siteseo_tax_desc_wp_editor($tag){
		global $pagenow;
		
		if ('term.php' == $pagenow || 'edit-tags.php' == $pagenow) {
			$content = '';

			if ('term.php' == $pagenow) {
				$editor_id = 'description';
			} elseif ('edit-tags.php' == $pagenow) {
				$editor_id = 'tag-description';
			} ?>

<tr class="form-field term-description-wrap">
	<th scope="row"><label for="description"><?php esc_html_e('Description'); ?></label></th>
	<td>
		<?php
					$settings = [
						'textarea_name' => 'description',
						'textarea_rows' => 10,
					];
			wp_editor(htmlspecialchars_decode($tag->description), 'html-tag-description', $settings); ?>
		<p class="description"><?php esc_html_e('The description is not prominent by default; however, some themes may show it.'); ?>
		</p>
	</td>
	<script type="text/javascript">
		// Remove default description field
		jQuery('textarea#description').closest('.form-field').remove();
	</script>
</tr>

<?php
		}
	}
	
	$siteseo_get_taxonomies = siteseo_get_service('WordPressData')->getTaxonomies();
	foreach ($siteseo_get_taxonomies as $key => $value) {
		add_action($key . '_edit_form_fields', 'siteseo_tax_desc_wp_editor', 9, 1);
	}
}
