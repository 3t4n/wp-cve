<?php

function mbp_is_admin_page() {
	return !empty($_GET['page']) and $_GET['page'] === 'mybookprogress';
}

function mbp_init_admin() {
	add_action('admin_menu', 'mbp_add_admin_page');
	add_action('admin_enqueue_scripts', 'mbp_admin_page_enqueue_resources');
	add_action('admin_enqueue_scripts', 'mbp_admin_page_localize_scripts');
	add_action('admin_init', 'mbp_add_admin_notices', 20);
}
add_action('mbp_init', 'mbp_init_admin');



/*---------------------------------------------------------*/
/* Admin Page                                              */
/*---------------------------------------------------------*/

function mbp_get_admin_page_templates() {
	return apply_filters('mbp_admin_page_templates', array());
}

function mbp_add_default_admin_page_templates($templates) {
	$plugin_path = plugin_dir_path(dirname(__FILE__)).'includes/';

	$templates['admin_template'] = $plugin_path.'templates/admin.php';

	$templates['help_template'] = $plugin_path.'templates/help/help.php';
	$templates['help_search_template'] = $plugin_path.'templates/help/search.php';
	$templates['help_topic_template'] = $plugin_path.'templates/help/topic.php';
	$templates['help_enable_tracking_modal_template'] = $plugin_path.'templates/help/enable_tracking_modal.php';

	$templates['book_progress_template'] = $plugin_path.'templates/book_progress/book_progress.php';
	$templates['book_progress_view_template'] = $plugin_path.'templates/book_progress/progress_view.php';
	$templates['book_phase_item_template'] = $plugin_path.'templates/book_progress/book_phase_item.php';
	$templates['book_progress_tabs_template'] = $plugin_path.'templates/book_progress/book_tabs.php';
	$templates['book_progress_statistics_tab_template'] = $plugin_path.'templates/book_progress/statistics_tab.php';
	$templates['book_progress_nudges_tab_template'] = $plugin_path.'templates/book_progress/nudges_tab.php';
	$templates['book_progress_email_updates_tab_template'] = $plugin_path.'templates/book_progress/email_updates_tab.php';
	$templates['book_progress_nudge_item_template'] = $plugin_path.'templates/book_progress/nudge_item.php';
	$templates['book_progress_create_template'] = $plugin_path.'templates/book_progress/create_progress.php';
	$templates['book_progress_entry_template'] = $plugin_path.'templates/book_progress/progress_entry.php';
	$templates['book_progress_header_template'] = $plugin_path.'templates/book_progress/progress_header.php';
	$templates['book_progress_more_template'] = $plugin_path.'templates/book_progress/progress_more.php';
	$templates['book_progress_newbook_template'] = $plugin_path.'templates/book_progress/progress_newbook.php';
	$templates['book_overview_template'] = $plugin_path.'templates/book_progress/overview.php';
	$templates['book_overview_book_template'] = $plugin_path.'templates/book_progress/overview_book.php';
	$templates['book_progress_delete_confirm_modal_template'] = $plugin_path.'templates/book_progress/progress_delete_confirm.php';
	$templates['book_progress_phase_complete_confirm_modal_template'] = $plugin_path.'templates/book_progress/phase_complete_confirm.php';

	$templates['book_setup_template'] = $plugin_path.'templates/book_setup/book_setup.php';
	$templates['book_setup_mybooktable_step_template'] = $plugin_path.'templates/book_setup/mybooktable_step.php';
	$templates['book_setup_title_step_template'] = $plugin_path.'templates/book_setup/title_step.php';
	$templates['book_setup_phase_step_template'] = $plugin_path.'templates/book_setup/phase_step.php';
	$templates['book_setup_phase_editor_template'] = $plugin_path.'templates/book_setup/phase_editor.php';
	$templates['book_setup_phase_item_template'] = $plugin_path.'templates/book_setup/phase_item.php';
	$templates['book_setup_phase_details_template'] = $plugin_path.'templates/book_setup/phase_details.php';
	$templates['book_setup_phase_template_template'] = $plugin_path.'templates/book_setup/phase_template.php';
	$templates['book_setup_display_step_template'] = $plugin_path.'templates/book_setup/display_step.php';
	$templates['book_setup_delete_confirm_modal_template'] = $plugin_path.'templates/book_setup/delete_confirm.php';
	$templates['book_setup_discard_confirm_modal_template'] = $plugin_path.'templates/book_setup/discard_confirm.php';
	$templates['book_setup_manage_templates_modal_template'] = $plugin_path.'templates/book_setup/manage_templates.php';
	$templates['book_setup_phase_template_item_template'] = $plugin_path.'templates/book_setup/phase_template_item.php';

	$templates['promote_tab_template'] = $plugin_path.'templates/promote_tab/promote_tab.php';
	$templates['setup_mailinglist_template'] = $plugin_path.'templates/promote_tab/mailinglist.php';
	$templates['setup_mailchimp_template'] = $plugin_path.'templates/promote_tab/mailchimp.php';
	$templates['setup_other_template'] = $plugin_path.'templates/promote_tab/other.php';
	$templates['setup_linkback_template'] = $plugin_path.'templates/promote_tab/linkback.php';
	$templates['setup_mybooktable_template'] = $plugin_path.'templates/promote_tab/mybooktable.php';

	$templates['style_tab_template'] = $plugin_path.'templates/style_tab/style_tab.php';
	$templates['style_tab_style_pack_template'] = $plugin_path.'templates/style_tab/style_pack.php';
	$templates['style_tab_book_style_template'] = $plugin_path.'templates/style_tab/book_style.php';

	$templates['display_tab_template'] = $plugin_path.'templates/display_tab/display_tab.php';

	$templates['upgrade_tab_template'] = $plugin_path.'templates/upgrade_tab/upgrade_tab.php';
	$templates['upgrade_apikey_template'] = $plugin_path.'templates/upgrade_tab/apikey.php';

	$templates['book_progress_type_numeric_editor_template'] = $plugin_path.'templates/progress_types/numeric_editor.php';
	$templates['book_progress_type_numeric_display_template'] = $plugin_path.'templates/progress_types/numeric_display.php';
	$templates['book_progress_type_percent_editor_template'] = $plugin_path.'templates/progress_types/percent_editor.php';
	$templates['book_progress_type_percent_display_template'] = $plugin_path.'templates/progress_types/percent_display.php';

	return $templates;
}
add_filter('mbp_admin_page_templates', 'mbp_add_default_admin_page_templates');

function mbp_do_admin_page_templates() {
	$templates = mbp_get_admin_page_templates();
	foreach($templates as $name => $path) {
		echo('<script type="text/template" id="'.$name.'">');
		include($path);
		echo('</script>');
	}
}

function mbp_admin_page_url() {
	return admin_url('admin.php?page=mybookprogress');
}

function mbp_add_admin_page() {
	add_menu_page(__('MyBookProgress', 'mybookprogress'), __('MyBookProgress', 'mybookprogress'), 'manage_options', 'mybookprogress', 'mbp_do_admin_page', 'dashicons-chart-bar', '10.8');
	add_action('mbp_admin_page', 'mbp_admin_page', 100);
}

function mbp_do_admin_page() {
	do_action('mbp_admin_page');
}

function mbp_admin_page() {
	if(!empty($_GET['subpage']) and $_GET['subpage'] == 'mbp_get_upgrade_page') { return mbp_render_get_upgrade_page(); }
	mbp_do_admin_page_templates();
	?><div class="wrap" id="mbp-admin-page"></div><?php
	// always refresh style packs on admin page load
	mbp_refresh_style_packs();
}

function mbp_admin_page_enqueue_resources() {
	wp_enqueue_script('mbp-admin-global-script', plugins_url('js/admin-global.js', dirname(__FILE__)), array('jquery', 'underscore'), MBP_VERSION, true);
	wp_enqueue_style('mbp-admin-global-style', plugins_url('css/admin-global.css', dirname(__FILE__)), array(), MBP_VERSION);

	if(!mbp_is_admin_page()) { return; }
	if(!empty($_GET['subpage']) and $_GET['subpage'] == 'mbp_get_upgrade_page') { return; }

	wp_enqueue_script('mbp-analytics-jsapi', 'https://www.google.com/jsapi', array(), MBP_VERSION);
	wp_register_script('mbp-colorpicker', plugins_url('js/lib/colpick.js', dirname(__FILE__)), array('jquery'), MBP_VERSION);
	wp_enqueue_style('mbp-colorpicker-style', plugins_url('css/lib/colpick.css', dirname(__FILE__)), array(), MBP_VERSION);
	wp_register_script('mbp-fireworks', plugins_url('js/lib/fireworks.js', dirname(__FILE__)), array('jquery'), MBP_VERSION);
	wp_register_script('mbp-fuse', plugins_url('js/lib/fuse.js', dirname(__FILE__)), array('jquery'), MBP_VERSION);
	wp_enqueue_script('mbp-admin-script', plugins_url('js/admin.js', dirname(__FILE__)), array('jquery', 'backbone', 'underscore', 'jquery-ui-tabs', 'jquery-ui-sortable', 'jquery-ui-slider', 'jquery-ui-datepicker', 'jquery-ui-tooltip', 'mbp-colorpicker', 'mbp-fireworks', 'mbp-fuse'), rand(100,333), true);

	wp_enqueue_style('mbp-admin-style', plugins_url('css/admin.css', dirname(__FILE__)), array(), MBP_VERSION);
	wp_enqueue_style('mbp-sirwalter-font', 'http://fonts.googleapis.com/css?family=EB+Garamond', array(), MBP_VERSION);

	wp_enqueue_media();
	add_thickbox();
}

function mbp_admin_page_localize_scripts() {
	$strings = array(
		'january' => __('January', 'mybookprogress'),
		'february' => __('February', 'mybookprogress'),
		'march' => __('March', 'mybookprogress'),
		'april' => __('April', 'mybookprogress'),
		'may' => __('May', 'mybookprogress'),
		'june' => __('June', 'mybookprogress'),
		'july' => __('July', 'mybookprogress'),
		'august' => __('August', 'mybookprogress'),
		'september' => __('September', 'mybookprogress'),
		'october' => __('October', 'mybookprogress'),
		'november' => __('November', 'mybookprogress'),
		'december' => __('December', 'mybookprogress'),
		'day' => __('Day', 'mybookprogress'),
		'days' => __('Days', 'mybookprogress'),
		'daily' => __('Daily', 'mybookprogress'),
		'week' => __('Week', 'mybookprogress'),
		'weeks' => __('Weeks', 'mybookprogress'),
		'weekly' => __('Weekly', 'mybookprogress'),
		'month' => __('Month', 'mybookprogress'),
		'months' => __('Months', 'mybookprogress'),
		'monthly' => __('Monthly', 'mybookprogress'),
		'never' => __('Never', 'mybookprogress'),
		'edit_blog_post' => __('Edit Blog Post', 'mybookprogress'),
		'make_blog_post' => __('Make Blog Post', 'mybookprogress'),
		'share_on_facebook' => __('Share on Facebook', 'mybookprogress'),
		'share_on_twitter' => __('Share on Twitter', 'mybookprogress'),
		'progress_update' => __('Progress Update', 'mybookprogress'),
		'chapters_name' => __('Chapters', 'mybookprogress'),
		'chapters_unit' => __('Chapter', 'mybookprogress'),
		'chapters_units' => __('Chapters', 'mybookprogress'),
		'pages_name' => __('Pages', 'mybookprogress'),
		'pages_unit' => __('Page', 'mybookprogress'),
		'pages_units' => __('Pages', 'mybookprogress'),
		'words_name' => __('Words', 'mybookprogress'),
		'words_unit' => __('Word', 'mybookprogress'),
		'words_units' => __('Words', 'mybookprogress'),
		'scenes_name' => __('Scenes', 'mybookprogress'),
		'scenes_unit' => __('Scene', 'mybookprogress'),
		'scenes_units' => __('Scenes', 'mybookprogress'),
		'percent_name' => __('Percentage', 'mybookprogress'),
		'percent_unit' => __('Percent', 'mybookprogress'),
		'percent_units' => __('Percent', 'mybookprogress'),
		'progress' => __('Progress', 'mybookprogress'),
		'promote' => __('Promote', 'mybookprogress'),
		'style' => __('Style', 'mybookprogress'),
		'display' => __('Display', 'mybookprogress'),
		'upgrade' => __('Upgrade', 'mybookprogress'),
		'time' => __('Time', 'mybookprogress'),
		'target' => __('Target', 'mybookprogress'),
		'deadline' => __('Deadline', 'mybookprogress'),
		'anonymous' => __('Anonymous', 'mybookprogress'),
		'pace' => __('Pace', 'mybookprogress'),
		'stats' => __('Stats', 'mybookprogress'),
		'nudges' => __('Nudges', 'mybookprogress'),
		'email_updates' => __('Email Updates', 'mybookprogress'),
		'more' => __('More', 'mybookprogress'),
		'save' => __('Save', 'mybookprogress'),
		'edit' => __('Edit', 'mybookprogress'),
		'choose_one' => __('Choose One', 'mybookprogress'),
		'custom' => __('Custom', 'mybookprogress'),
		'new_template' => __('New Template', 'mybookprogress'),
		'edit_widget' => __('Edit Widget', 'mybookprogress'),
		'add_widget' => __('Add Widget', 'mybookprogress'),
		'cover_image' => __('Cover Image', 'mybookprogress'),
		'remove' => __('Remove', 'mybookprogress'),
		'choose' => __('Choose', 'mybookprogress'),
		'search' => __('Search', 'mybookprogress'),
		'loading' => __('Loading', 'mybookprogress'),
		'thank_you_for_feedback' => __('Thank you for your feedback!', 'mybookprogress'),
		'no_lists_available' => __('No Lists Available', 'mybookprogress'),
		'subscribers' => __('Subscribers', 'mybookprogress'),
		'mailchimp' => __('MailChimp', 'mybookprogress'),
		'other' => __('Other', 'mybookprogress'),
		'style_pack' => __('Style Pack', 'mybookprogress'),
		'upgrade_plugin_not_installed_error' => __('Your MyBookProgress Upgrade plugin is not installed.<br><div class="mbp-alert-message-desc">Click here to install your MyBookProgress Upgrade plugin and enable your advanced features.</div>', 'mybookprogress'),
		'problem_with_apikey_error' => __('There is a problem with your MyBookProgress License Key.<br><div class="mbp-alert-message-desc">You must enter a valid License Key below to enable your advanced features.</div>', 'mybookprogress'),
		'no_title' => __('no title', 'mybookprogress'),
		'untitled_book' => __('Untitled Book', 'mybookprogress'),
		'complete_this_phase' => __('Click here to mark this phase as complete'),
	);
	wp_localize_script('mbp-admin-script', 'mybookprogress_i18n', $strings);
}



/*---------------------------------------------------------*/
/* Upgrade Page                                            */
/*---------------------------------------------------------*/

function mbp_render_get_upgrade_page() {
?>
	<div class="wrap">
		<h2><?php _e('Get Upgrade', 'mybookprogress'); ?></h2>
		<?php
			function mbp_get_upgrade_check_is_plugin_inactivate($slug) {
				$plugin = $slug.DIRECTORY_SEPARATOR.$slug.'.php';
				if(!is_wp_error(activate_plugin($plugin))) {
					echo('<p>'.__('Plugin successfully activated.', 'mybookprogress').'</p>');
					return true;
				}

				return false;
			}

			function mbp_get_upgrade_get_plugin_url($slug) {
				global $wp_version;

				$apikey = mbp_get_setting('apikey');
				if(!empty($apikey)) {
					$to_send = array(
						'action'  => 'basic_check',
						'version' => 'none',
						'api-key' => $apikey,
						'site'    => get_bloginfo('url')
					);

					$options = array(
						'timeout' => 3,
						'body' => $to_send,
						'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url')
					);

					$raw_response = wp_remote_post('http://api.authormedia.com/plugins/'.$slug.'/update-check', $options);
					if(!is_wp_error($raw_response) and wp_remote_retrieve_response_code($raw_response) == 200) {
						$response = maybe_unserialize(wp_remote_retrieve_body($raw_response));
						if(is_array($response) and !empty($response['package'])) {
							return $response['package'];
						}
					}
				}

				return '';
			}

			function mbp_get_upgrade_do_plugin_install($name, $slug, $url) {
				if(empty($url)) { echo('<p>'.__('An error occurred while trying to retrieve the plugin from the server. Please check your License Key.', 'mybookprogress').'</p>'); return; }
				if(!current_user_can('install_plugins')) { echo('<p>'.__('Sorry, but you do not have the correct permissions to install plugins. Contact the administrator of this site for help on getting the plugin installed.', 'mybookprogress').'</p>'); return; }

				$nonce_url = wp_nonce_url('admin.php?page=mybookprogress', 'mbp-install-upgrade');
				$output = mbp_get_wp_filesystem($nonce_url);
				if(!empty($output)) { echo($output); return; }

				$plugin = array();
				$plugin['name']   = $name;
				$plugin['slug']   = $slug;
				$plugin['source'] = $url;

				require_once(ABSPATH.'wp-admin/includes/plugin-install.php');
				require_once(ABSPATH.'wp-admin/includes/class-wp-upgrader.php');

				$args = array(
					'type'   => 'web',
					'title'  => sprintf(__('Installing Plugin: %s', 'mybookprogress'), $plugin['name']),
					'nonce'  => 'install-plugin_' . $plugin['slug'],
					'plugin' => $plugin,
				);

				add_filter('install_plugin_complete_actions', '__return_false', 100);
				$upgrader = new Plugin_Upgrader(new Plugin_Installer_Skin($args));
				$upgrader->install($plugin['source']);
				wp_cache_flush();
				remove_filter('install_plugin_complete_actions', '__return_false', 100);

				$plugin_info = $upgrader->plugin_info();
				$activate    = activate_plugin($plugin_info);
				if(is_wp_error($activate)) { echo('<div id="message" class="error"><p>'.$activate->get_error_message().'</p></div>'); }
			}

			$slug = mbp_get_upgrade();
			if(empty($slug) or mbp_get_upgrade_plugin_exists() === $slug) {
				echo('<p>'.__('You have no Upgrades available to download at this time.', 'mybookprogress').'</p>');
			} else {
				if(!mbp_get_upgrade_check_is_plugin_inactivate($slug)) {
					$url = mbp_get_upgrade_get_plugin_url($slug);
					if($slug == 'mybookprogress-dev')  { $name = 'MyBookProgress Developer Upgrade'; }
					if($slug == 'mybookprogress-pro')  { $name = 'MyBookProgress Professional Upgrade'; }
					mbp_get_upgrade_do_plugin_install($name, $slug, $url);
				}
			}
		?>
		<a class="button button-primary" href="<?php echo(admin_url('admin.php?page=mybookprogress')); ?>"><?php _e('Back to MyBookProgress', 'mybookprogress'); ?></a>
	</div>
<?php
}



/*---------------------------------------------------------*/
/* Data Models                                             */
/*---------------------------------------------------------*/

function mbp_ajax_json_decode($json) {
	return json_decode(str_replace('\\\\', '\\', str_replace('\\\'', '\'', str_replace('\\"', '"', $json))), true);
}

/* Settings */

add_action('wp_ajax_mbp_settings_read', 'mbp_settings_read_callback');
function mbp_settings_read_callback() {
	$settings = array('id' => 1);
	global $mbp_settings;
	foreach($mbp_settings as $key => $value) {
		$settings[$key] = $value;
	}
	$settings = apply_filters('mbp_admin_settings_read', $settings);

	echo(json_encode(array('object' => $settings)));
	die();
}

add_action('wp_ajax_mbp_settings_patch', 'mbp_settings_update_callback');
function mbp_settings_update_callback() {
	$settings = mbp_ajax_json_decode(isset($_POST['object']) ? $_POST['object'] : '');

	unset($settings['id']);
	$settings = apply_filters('mbp_admin_settings_update', $settings);
	foreach($settings as $key => $value) {
		mbp_update_setting($key, $value);
	}

	die();
}

function mbp_default_admin_settings_read($settings) {
	$date_format = get_option('date_format');
	$replace = array('d' => 'dd', 'j' => 'd', 'z' => 'o', 'l' => 'DD', 'm' => 'mm', 'n' => 'm', 'F' => 'MM', 'Y' => 'yy');
	$date_format = str_replace(array_keys($replace), array_values($replace), $date_format);
	$settings['date_format'] = $date_format;
	$settings['mybooktable_installed'] = defined('MBT_VERSION');
	$settings['upgrade_exists'] = mbp_get_upgrade_plugin_exists();

	unset($settings['books']);
	unset($settings['phase_templates']);

	return $settings;
}
add_filter('mbp_admin_settings_read', 'mbp_default_admin_settings_read');

function mbp_default_admin_settings_update($settings) {
	unset($settings['date_format']);
	unset($settings['mybooktable_installed']);
	unset($settings['books']);
	unset($settings['phase_templates']);
	return $settings;
}
add_filter('mbp_admin_settings_update', 'mbp_default_admin_settings_update');

/* Books */

add_action('wp_ajax_mbp_books_read', 'mbp_books_read_callback');
function mbp_books_read_callback() {
	echo(json_encode(array('object' => mbp_get_books())));
	die();
}

add_action('wp_ajax_mbp_book_create', 'mbp_book_create_callback');
function mbp_book_create_callback() {
	$object = mbp_ajax_json_decode(isset($_POST['object']) ? $_POST['object'] : '');
	$book_id = mbp_create_book($object);
	echo(json_encode(array('id' => $book_id)));
	die();
}

add_action('wp_ajax_mbp_book_read', 'mbp_book_read_callback');
function mbp_book_read_callback() {
	$object = mbp_ajax_json_decode(isset($_POST['object']) ? $_POST['object'] : '');
	$book = mbp_get_book($object['id']);
	echo(json_encode(array('object' => $book)));
	die();
}

add_action('wp_ajax_mbp_book_update', 'mbp_book_update_callback');
function mbp_book_update_callback() {
	$object = mbp_ajax_json_decode(isset($_POST['object']) ? $_POST['object'] : '');
	mbp_update_book($object);
	die();
}

add_action('wp_ajax_mbp_book_delete', 'mbp_book_delete_callback');
function mbp_book_delete_callback() {
	$object = mbp_ajax_json_decode(isset($_POST['object']) ? $_POST['object'] : '');
	mbp_delete_book($object['id']);
	die();
}

/* Progress Entries */

add_action('wp_ajax_mbp_book_progress_read', 'mbp_book_progress_read_callback');
function mbp_book_progress_read_callback() {
	$book_id = isset($_POST['book_id']) ? $_POST['book_id'] : 0;
	$before = isset($_POST['before']) ? $_POST['before'] : 0;
	list($entries, $last, $has_more) = mbp_get_book_progress_entries_page($book_id, $before);
	echo(json_encode(array('object' => array('entries' => $entries, 'last' => $last, 'has_more' => $has_more))));
	die();
}

add_action('wp_ajax_mbp_progress_entry_create', 'mbp_progress_entry_create_callback');
function mbp_progress_entry_create_callback() {
	$object = mbp_ajax_json_decode(isset($_POST['object']) ? $_POST['object'] : '');
	$progress_id = mbp_create_progress_entry($object);
	echo(json_encode(array('id' => $progress_id)));
	die();
}

add_action('wp_ajax_mbp_progress_entry_read', 'mbp_progress_entry_read_callback');
function mbp_progress_entry_read_callback() {
	$object = mbp_ajax_json_decode(isset($_POST['object']) ? $_POST['object'] : '');
	$progress = mbp_get_progress_entry($object['id']);
	echo(json_encode(array('object' => $progress)));
	die();
}

add_action('wp_ajax_mbp_progress_entry_update', 'mbp_progress_entry_update_callback');
function mbp_progress_entry_update_callback() {
	$object = mbp_ajax_json_decode(isset($_POST['object']) ? $_POST['object'] : '');
	mbp_update_progress_entry($object);
	die();
}

add_action('wp_ajax_mbp_progress_entry_delete', 'mbp_progress_entry_delete_callback');
function mbp_progress_entry_delete_callback() {
	$object = mbp_ajax_json_decode(isset($_POST['object']) ? $_POST['object'] : '');
	mbp_delete_progress_entry($object['id']);
	die();
}

/* Style Packs */

add_action('wp_ajax_mbp_style_packs_read', 'mbp_style_packs_read_callback');
function mbp_style_packs_read_callback() {
	$formatted_style_packs = array();
	foreach (mbp_get_style_packs() as $id => $style_pack) {
		$style_pack['id'] = $id;
		$formatted_style_packs[] = $style_pack;
	}
	echo(json_encode(array('object' => $formatted_style_packs)));
	die();
}

/* Phase Templates */

add_action('wp_ajax_mbp_phase_templates_read', 'mbp_phase_templates_read_callback');
function mbp_phase_templates_read_callback() {
	echo(json_encode(array('object' => mbp_get_phase_templates())));
	die();
}

add_action('wp_ajax_mbp_phase_templates_update', 'mbp_phase_templates_update_callback');
function mbp_phase_templates_update_callback() {
	$object = mbp_ajax_json_decode(isset($_POST['object']) ? $_POST['object'] : '');
	mbp_update_phase_templates($object);
	die();
}

/* MyBookTable Books */

add_action('wp_ajax_mbp_mbt_books_read', 'mbp_mbt_books_read_callback');
function mbp_mbt_books_read_callback() {
	echo(json_encode(array('object' => mbp_get_mybooktable_books())));
	die();
}

add_action('wp_ajax_mbp_mbt_book_update', 'mbp_mbt_book_update_callback');
function mbp_mbt_book_update_callback() {
	$object = mbp_ajax_json_decode(isset($_POST['object']) ? $_POST['object'] : '');
	mbp_update_mybooktable_book($object);
	die();
}

/* Statistics */

add_action('wp_ajax_mbp_get_global_stats', 'mbp_get_global_stats_callback');
function mbp_get_global_stats_callback() {
	$stats = mbp_get_global_stats();
	echo(json_encode($stats));
	die();
}

add_action('wp_ajax_mbp_get_book_stats', 'mbp_get_book_stats_callback');
function mbp_get_book_stats_callback() {
	$book_id = isset($_POST['book_id']) ? $_POST['book_id'] : 0;
	$stats = mbp_get_book_stats($book_id);
	echo(json_encode($stats));
	die();
}

add_action('wp_ajax_mbp_get_phase_stats', 'mbp_get_phase_stats_callback');
function mbp_get_phase_stats_callback() {
	$book_id = isset($_POST['book_id']) ? $_POST['book_id'] : 0;
	$phase_id = isset($_POST['phase_id']) ? $_POST['phase_id'] : 0;
	$stats = mbp_get_phase_stats($book_id, $phase_id);
	echo(json_encode($stats));
	die();
}

/* Utility Functions */

add_action('wp_ajax_mbp_get_book_phases_progress', 'mbp_get_book_phases_progress_callback');
function mbp_get_book_phases_progress_callback() {
	$book_id = isset($_POST['book_id']) ? $_POST['book_id'] : 0;
	echo(json_encode(mbp_get_book_phases_progress($book_id)));
	die();
}

add_action('wp_ajax_mbp_track_event', 'mbp_track_event_callback');
function mbp_track_event_callback() {
	$event_name = isset($_POST['event_name']) ? $_POST['event_name'] : '';
	$instance = mbp_ajax_json_decode(isset($_POST['instance']) ? $_POST['instance'] : 'false');
	if(!empty($event_name)) {
		mbp_track_event($event_name, $instance);
	}
	die();
}

add_action('wp_ajax_mbp_update_apikey', 'mbp_update_apikey_callback');
function mbp_update_apikey_callback() {
	$apikey = isset($_POST['apikey']) ? $_POST['apikey'] : 0;
	mbp_update_setting('apikey', $apikey);
	mbp_verify_apikey();
	echo(json_encode(array(
		'apikey_status' => mbp_get_setting('apikey_status'),
		'apikey_message' => mbp_get_setting('apikey_message'),
		'upgrade_enabled' => mbp_get_setting('upgrade_enabled'),
		'upgrade_exists' => mbp_get_upgrade_plugin_exists(),
	)));
	die();
}

add_action('wp_ajax_mbp_get_post_permalink', 'mbp_get_post_permalink_callback');
function mbp_get_post_permalink_callback() {
	$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : 0;
	echo(json_encode(get_permalink($post_id)));
	die();
}

add_action('wp_ajax_mbp_progress_sharing_blog', 'mbp_progress_sharing_blog_callback');
function mbp_progress_sharing_blog_callback() {
	$message = isset($_POST['message']) ? $_POST['message'] : '';
	$title = isset($_POST['title']) ? $_POST['title'] : '';
	$post_id = wp_insert_post(array(
		'post_content' => $message,
		'post_title' => (empty($title) ? '' : $title.' ').__('Progress Update'),
		'post_status' => 'draft',
	));
	echo(json_encode(array('post_id' => $post_id)));
	die();
}

add_action('wp_ajax_mbp_upload_stylepack', 'mbp_upload_stylepack_callback');
function mbp_upload_stylepack_callback() {
	$stylepack_id = isset($_POST['stylepack_id']) ? $_POST['stylepack_id'] : 0;
	if($stylepack_id) { mbp_upload_stylepack($stylepack_id); }
	die();
}

add_action('wp_ajax_mbp_do_mailchimp_query', 'mbp_do_mailchimp_query_callback');
function mbp_do_mailchimp_query_callback() {
	$apikey = isset($_POST['apikey']) ? $_POST['apikey'] : '';
	$method = isset($_POST['method']) ? $_POST['method'] : '';
	$data = isset($_POST['data']) ? $_POST['data'] : '';
	$data = mbp_ajax_json_decode($data);
	$result = mbp_do_mailchimp_query($apikey, $method, $data);
	echo(json_encode($result));
	die();
}

add_action('wp_ajax_mbp_get_preview', 'mbp_get_preview_callback');
function mbp_get_preview_callback() {
	$values = mbp_ajax_json_decode(isset($_POST['values']) ? $_POST['values'] : '');

	$book = array(
		'id' => 0,
		'title' => $values['title'],
		'phase_template' => null,
		'phases' => array(),
		'display_bar_color' => $values['bar_color'],
		'display_cover_image' => $values['cover_image'],
		'mbt_book' => $values['mbt_book'],
	);
	$progress_data = array(
		'phase_name' => null,
		'deadline' => null,
		'progress' => 0.7,
	);
	$output = '';
	$output .= '<link rel="stylesheet" type="text/css" href="'.plugins_url('css/frontend.css', dirname(__FILE__)).'?ver='.MBP_VERSION.'" />';
	$output .= '<script type="text/javascript" src="'.plugins_url('js/frontend.js', dirname(__FILE__)).'?ver='.MBP_VERSION.'"></script>';

	$style_pack = mbp_get_style_pack($values['style_pack']);
	if(!empty($style_pack) and !empty($style_pack['style_dir_url'])) {
		$output .= '<link rel="stylesheet" type="text/css" href="'.$style_pack['style_dir_url'].'/style.css'.'?ver='.MBP_VERSION.'.'.$style_pack['version'].'" />';
		if(file_exists($style_pack['style_dir'].'/style.js')) { $output .= '<script type="text/javascript" src="'.$style_pack['style_dir_url'].'/style.js'.'?ver='.MBP_VERSION.'.'.$style_pack['version'].'"></script>'; }
	}

	$output .= '<div class="mbp-container"><div class="mbp-books">';
	$output .= mbp_format_book_progress($book, array('include_wrapper' => false, 'show_buttons' => false), $progress_data);
	$output .= '</div></div>';

	echo(json_encode(array('output' => $output)));
	die();
}

add_action('wp_ajax_mbp_add_sidebar', 'mbp_add_sidebar_callback');
function mbp_add_sidebar_callback() {
	if(!current_user_can('edit_theme_options')) { return; }

	$sidebar_id = isset($_POST['sidebar']) ? $_POST['sidebar'] : '';
	$sidebars = wp_get_sidebars_widgets();
	if(!isset($sidebars[$sidebar_id])) { return; }

	$widget_settings = get_option('widget_mbp_widget', array());
	$highest_number = 0;
	foreach($widget_settings as $num => $data) {
		if($num > $highest_number) { $highest_number = $num; }
	}
	$widget_number = $highest_number+1;
	$widget_settings[$widget_number] = array();
	update_option('widget_mbp_widget', $widget_settings);

	$sidebars[$sidebar_id][] = 'mbp_widget-'.$widget_number;

	wp_set_sidebars_widgets($sidebars);

	die();
}



/*---------------------------------------------------------*/
/* Notices                                                 */
/*---------------------------------------------------------*/

function mbp_add_admin_notices() {
	if(count(mbp_get_books()) > 0 and mbp_is_admin_page() and current_user_can('manage_options')) {
		if(!mbp_get_setting('allow_tracking')) {
			wp_enqueue_script('wp-pointer');
			wp_enqueue_style('wp-pointer');
			add_action('admin_print_footer_scripts', 'mbp_admin_notice_allow_tracking');
		} else if(mbp_get_setting('email_subscribe_notice') !== 'done') {
			wp_enqueue_script('wp-pointer');
			wp_enqueue_style('wp-pointer');
			add_action('admin_print_footer_scripts', 'mbp_admin_notice_email_subscribe');
		}
	}

	if(!mbp_get_setting('hide_admin_notice_setup_mailing_list') and !mbp_verify_subscribe_enabled()) {
		if(!mbp_is_admin_page() and is_active_widget(false, false, 'mbp_widget')) {
			add_action('admin_notices', 'mbp_admin_notice_setup_mailing_list');
		}
	}
}

function mbp_admin_notice_setup_mailing_list() {
	?>
	<div id="message" class="mbp-admin-notice mbp-admin-notice-setup-mailing-list">
		<h4><?php _e('<strong>Connect with your readers</strong> &#8211; Your MyBookProgress Mailing List settings are not configured.', 'mybookprogress'); ?></h4>
		<a class="notice-button primary" href="<?php echo(admin_url('admin.php?page=mybookprogress&tab=mbp-promote-tab')); ?>"><?php _e('Setup Now', 'mybookprogress'); ?></a>
		<a class="notice-button secondary" href="#"><?php _e("I know, don't bother me", 'mybookprogress'); ?></a>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function () {
			jQuery('.mbp-admin-notice-setup-mailing-list').on('click', '.notice-button.secondary', function() {
				jQuery.post(ajaxurl, {action: 'mbp_hide_setup_mailing_list_notice'});
				jQuery('.mbp-admin-notice-setup-mailing-list').hide(300);
				return false;
			});
		});
	</script>
	<?php
}

add_action('wp_ajax_mbp_hide_setup_mailing_list_notice', 'mbp_hide_setup_mailing_list_notice_callback');
function mbp_hide_setup_mailing_list_notice_callback() {
	mbp_update_setting('hide_admin_notice_setup_mailing_list', true);
	die();
}

function mbp_admin_notice_allow_tracking() {
	$content  = '<h3>'.__('Help improve MyBookProgress', 'mybookprogress').'</h3>';
	$content .= '<p>'.__('You can help make MyBookProgress even better and easier to use by allowing it to gather anonymous statistics about how you use the plugin.', 'mybookprogress').'</p>';
	$content .= '<div class="mbp-pointer-buttons wp-pointer-buttons">';
	$content .= '<a id="mbp-pointer-yes" class="button-primary" style="float:left">'.htmlspecialchars(__("Let's do it!", 'mybookprogress'), ENT_QUOTES).'</a>';
	$content .= '<a id="mbp-pointer-no" class="button-secondary">'.htmlspecialchars(__("I'd Rather Not", 'mybookprogress'), ENT_QUOTES).'</a>';
	$content .= '</div>';

	?>
	<script type="text/javascript">
		jQuery(document).ready(function () {
			var content = jQuery('<div class="mbp-pointer-content"><?php echo($content); ?></div>');

			content.on('click', '#mbp-pointer-yes', function() {
				content.find('#mbp-pointer-yes').attr('disabled', 'disabled');
				content.find('#mbp-pointer-no').attr('disabled', 'disabled');
				jQuery.post(ajaxurl,
					{
						action: 'mbp_allow_tracking_notice',
						allow_tracking: 'yes',
					},
					function(response) {
						content.html(response);
					}
				);
			});
			content.on('click', '#mbp-pointer-no', function() {
				jQuery.post(ajaxurl, {action: 'mbp_allow_tracking_notice', allow_tracking: 'no'});
				jQuery('#wpadminbar').pointer('close');
			});
			content.on('click', '#mbp-pointer-close', function() {
				jQuery('#wpadminbar').pointer('close');
			});

			mybookprogress.utils.pointer(content, {pointerClass: 'mbp-allow-tracking-notice'});
		});
	</script>
	<?php
}

add_action('wp_ajax_mbp_allow_tracking_notice', 'mbp_allow_tracking_notice_callback');
function mbp_allow_tracking_notice_callback() {
	if(empty($_REQUEST['allow_tracking'])) { die(); }
	if($_REQUEST['allow_tracking'] === 'yes') {
		mbp_update_setting('allow_tracking', 'yes');
		mbp_track_event('tracking_allowed', true);
		mbp_send_tracking_data();

		$content  = '<h3>'.__('Help improve MyBookProgress', 'mybookprogress').'</h3>';
		$content .= '<p>'.__('Thanks! You\'re the best!', 'mybookprogress').'</p>';
		$content .= '<div class="mbp-pointer-buttons wp-pointer-buttons">';
		$content .= '<a id="mbp-pointer-close" class="button-secondary">'.__('Close', 'mybookprogress').'</a>';
		$content .= '</div>';
		echo($content);
	} else {
		mbp_track_event('tracking_denied', true);
		mbp_update_setting('allow_tracking', 'no');
	}
	die();
}

function mbp_admin_notice_email_subscribe() {
	$current_user = wp_get_current_user();
	$email = $current_user->user_email;

	$content  = '<h3>'.__('Get Branding Tips, Marketing Advice and Plugin Updates', 'mybookprogress').'</h3>';
	$content .= '<p>'.htmlspecialchars(__('Join over 7,000 other authors on the Author Media\'s award winning newsletter. AuthorMedia.com has been frequently recommended by Writer\'s Digest as one of the most helpful websites for authors. You can unsubscribe at anytime with just one click.', 'mybookprogress'), ENT_QUOTES).'</p>';
	$content .= '<p>'.'<input type="text" name="mbp-pointer-email" id="mbp-pointer-email" autocapitalize="off" autocorrect="off" placeholder="you@example.com" value="'.$email.'" style="width: 100%">'.'</p>';
	$content .= '<div class="mbp-pointer-buttons wp-pointer-buttons">';
	$content .= '<a id="mbp-pointer-yes" class="button-primary" style="float:left">'.htmlspecialchars(__("Let's do it!", 'mybookprogress'), ENT_QUOTES).'</a>';
	$content .= '<a id="mbp-pointer-no" class="button-secondary">'.__('No, thanks', 'mybookprogress').'</a>';
	$content .= '</div>';

	?>
	<script type="text/javascript">
		jQuery(document).ready(function () {

			var content = jQuery('<div class="mbp-pointer-content"><?php echo($content); ?></div>');

			function mbp_email_subscribe_pointer_subscribe() {
				if(!/^.+@.+$/.test(content.find('#mbp-pointer-email').val())) {
					content.find('#mbp-pointer-email').addClass('error').focus();
				} else {
					content.find('#mbp-pointer-yes').attr('disabled', 'disabled');
					content.find('#mbp-pointer-no').attr('disabled', 'disabled');
					content.find('#mbp-pointer-email').attr('disabled', 'disabled');
					jQuery.post(ajaxurl,
						{
							action: 'mbp_email_subscribe_notice',
							subscribe: 'yes',
							email: content.find('#mbp-pointer-email').val(),
						},
						function(response) {
							content.html(response);
						}
					);
				}
			}

			content.on('click', '#mbp-pointer-yes', function() {
				mbp_email_subscribe_pointer_subscribe(content);
			});
			content.on('keypress', '#mbp-pointer-email', function(event) {
				if(event.which == 13) {
					mbp_email_subscribe_pointer_subscribe(content);
				}
			});
			content.on('click', '#mbp-pointer-no', function() {
				jQuery.post(ajaxurl, {action: 'mbp_email_subscribe_notice', subscribe: 'no'});
				jQuery('#wpadminbar').pointer('close');
			});
			content.on('click', '#mbp-pointer-close', function() {
				jQuery('#wpadminbar').pointer('close');
			});

			mybookprogress.utils.pointer(content, {pointerClass: 'mbp-email-subscribe-notice'});
		});
	</script>
	<?php
}

add_action('wp_ajax_mbp_email_subscribe_notice', 'mbp_email_subscribe_notice_callback');
function mbp_email_subscribe_notice_callback() {
	if(empty($_REQUEST['subscribe'])) { die(); }
	if($_REQUEST['subscribe'] === 'yes') {
		mbp_track_event('admin_notice_email_subscribe_accept');

		$email = $_POST['email'];
		wp_remote_post('http://AuthorMedia.us1.list-manage1.com/subscribe/post', array(
			'method' => 'POST',
			'body' => array(
				'u' => 'b7358f48fe541fe61acdf747b',
				'id' => '6b5a675fcf',
				'MERGE0' => $email,
				'MERGE1' => '',
				'MERGE3' => '',
				'group[3045][4194304]' => 'on',
				'b_b7358f48fe541fe61acdf747b_6b5a675fcf' => ''
			)
		));

		$content  = '<h3>'.__('Get Branding Tips, Marketing Advice and Plugin Updates', 'mybookprogress').'</h3>';
		$content .= '<p>'.__('Thank you for subscribing! Please check your inbox for a confirmation letter.', 'mybookprogress').'</p>';
		$content .= '<div class="mbp-pointer-buttons wp-pointer-buttons">';

		$email_title = '';
		$email_link = '';
		if(strpos($email , '@yahoo') !== false) {
			$email_title = __('Go to Yahoo! Mail', 'mybookprogress');
			$email_link = 'https://mail.yahoo.com/';
		} else if(strpos($email, '@hotmail') !== false) {
			$email_title = __('Go to Hotmail', 'mybookprogress');
			$email_link = 'https://www.hotmail.com/';
		} else if(strpos($email, '@gmail') !== false) {
			$email_title = __('Go to Gmail', 'mybookprogress');
			$email_link = 'https://mail.google.com/';
		} else if(strpos($email, '@aol') !== false) {
			$email_title = __('Go to AOL Mail', 'mybookprogress');
			$email_link = 'https://mail.aol.com/';
		}
		if(!empty($email_title)) {
			$content .= '<a class="button-primary" style="float:left" href="'.$email_link.'" target="_blank">'.$email_title.'</a>';
		}

		$content .= '<a id="mbp-pointer-close" class="button-secondary">'.__('Close', 'mybookprogress').'</a>';
		$content .= '</div>';
		echo($content);
	} else {
		mbp_track_event('admin_notice_email_subscribe_deny');
	}
	mbp_update_setting('email_subscribe_notice', 'done');
	die();
}
