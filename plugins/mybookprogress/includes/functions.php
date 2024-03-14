<?php

/*---------------------------------------------------------*/
/* Settings                                                */
/*---------------------------------------------------------*/

function mbp_load_settings() {
	global $mbp_settings;
	$mbp_settings = get_option('mbp_settings');
	if(empty($mbp_settings)) { mbp_reset_settings(); }
}

function mbp_reset_settings() {
	global $mbp_settings;
	$mbp_settings = array(
		'version' => MBP_VERSION,
		'installed_time' => time(),
		'apikey' => '',
		'apikey_status' => 0,
		'apikey_message' => '',
		'upgrade_enabled' => false,
		'mailinglist_type' => '',
		'other_subscribe_url' => '',
		'mailchimp_apikey' => '',
		'mailchimp_list' => '',
		'mailchimp_subscribe_url' => '',
		'enable_linkback' => false,
		'current_book' => -1,
		'style_pack' => '',
		'mybooktable_social_media_link' => true,
		'mybooktable_frontend_link' => true,
	);
	if(defined('MBT_VERSION')) {
		$mailchimp_apikey = mbt_get_setting('mailchimp_apikey');
		if(!empty($mailchimp_apikey)) {
			$mbp_mailinglist_type = 'mailchimp';
			$mbp_settings['mailchimp_apikey'] = $mailchimp_apikey;
		}
	}
	update_option('mbp_settings', $mbp_settings);
}

function mbp_get_setting($name) {
	global $mbp_settings;
	$value = isset($mbp_settings[$name]) ? $mbp_settings[$name] : null;
	return apply_filters('mbp_get_setting', $value, $name);
}

function mbp_update_setting($name, $value) {
	global $mbp_settings;
	$mbp_settings[$name] = apply_filters('mbp_update_setting', $value, $name);
	update_option('mbp_settings', $mbp_settings);
}



/*---------------------------------------------------------*/
/* Books                                                   */
/*---------------------------------------------------------*/

function mbp_get_books() {
	global $mbp_books;
	if(empty($mbp_books)) {
		$mbp_books = get_option('mbp_books');
		if(!is_array($mbp_books)) { $mbp_books = array(); }
	}
	return apply_filters('mbp_get_books', $mbp_books);
}

function mbp_update_all_books($updated_books) {
	global $mbp_books;
	$mbp_books = $updated_books;
	update_option('mbp_books', $updated_books);
}

function mbp_get_book($book) {
	$returns = null;

	if(is_array($book)) {
		$returns = $book;
	} else if(is_numeric($book)) {
		$books = mbp_get_books();
		foreach($books as $this_book) {
			if($this_book['id'] == $book) { $returns = $this_book; break; }
		}
	}

	return apply_filters('mbp_get_book', $returns, $book);
}

function mbp_update_book($updated_book) {
	$books = mbp_get_books();
	$updated_book = apply_filters('mbp_update_book', $updated_book);
	foreach($books as $key => $book) {
		if($book['id'] == $updated_book['id']) {
			$books[$key] = $updated_book;
			break;
		}
	}
	mbp_update_all_books($books);
}

function mbp_delete_book($book_id) {
	$books = mbp_get_books();
	do_action('mbp_delete_book', $book_id);
	foreach($books as $key => $book) {
		if($book['id'] == $book_id) {
			mbp_delete_book_progress_entries($book_id);
			array_splice($books, $key, 1);
			mbp_update_all_books($books);
			break;
		}
	}
}

function mbp_create_book($new_book) {
	$books = mbp_get_books();
	if(count($books) < 1) {
		$new_book['id'] = 1;
	} else {
		$last_book = end($books);
		$new_book['id'] = $last_book['id']+1;
	}
	$books[] = $new_book;

	mbp_update_all_books($books);
	return $new_book['id'];
}

function mbp_get_book_phases($book) {
	$book = mbp_get_book($book);
	$phases = $book['phases'];
	if(is_string($phases)) {
		$template = mbp_get_phase_template($phases);
		$phases = $template ? $template['phases'] : null;
	}
	if(!$phases) { $phases = array(); }
	return $phases;
}

function mbp_get_book_title($book) {
	$book = mbp_get_book($book);
	$title = $book['title'];
	if(empty($title) && DEFINED('MBT_VERSION') && !empty($book['mbt_book'])) {
		$mbt_book = get_post($book['mbt_book']);
		if(!empty($mbt_book)) {
			$title = $mbt_book->post_title;
		}
	}
	return $title ? $title : __('Untitled Book', 'mybookprogress');
}

function mbp_get_book_phase($book, $phase_id) {
	$phases = mbp_get_book_phases($book);
	foreach($phases as $phase) {
		if($phase['id'] == $phase_id) {
			return $phase;
		}
	}
	return null;
}

function mbp_get_book_phase_status($book, $phase_id) {
	$book = mbp_get_book($book);
	return !empty($book['phases_status'][$phase_id]) ? $book['phases_status'][$phase_id] : '';
}

function mbp_get_book_phase_progress($book, $phase_id) {
	$phases_progress = mbp_get_book_phases_progress($book);
	return !empty($phases_progress[$phase_id]) ? $phases_progress[$phase_id] : 0;
}

function mbp_get_book_phases_progress($book) {
	$book = mbp_get_book($book);

	$cache_id = 'mbp_book_'.$book['id'].'_phases_progress';
	if(false === ($phases_progress = get_transient($cache_id))) {
		$phases = mbp_get_book_phases($book);
		$phases_progress = array();

		global $wpdb;
		foreach($phases as $i => $phase) {
			$query = $wpdb->prepare("SELECT * FROM ".$wpdb->mbp_progress." WHERE book_id = %d AND phase_id = %d ORDER BY timestamp DESC LIMIT 1", $book['id'], $phase['id']);
			$results = $wpdb->get_results($query, ARRAY_A);
			if(empty($results)) {
				$phases_progress[$phase['id']] = 0;
			} else {
				$entry = mbp_progress_entry_db_export(reset($results));
				$phases_progress[$phase['id']] = $entry['progress'];
			}
		}

		set_transient($cache_id, $phases_progress, DAY_IN_SECONDS);
	}

	return $phases_progress;
}

function mbp_refresh_book_phases_progress($book) {
	$book = mbp_get_book($book);
	delete_transient('mbp_book_'.$book['id'].'_phases_progress');
}

function mbp_get_book_current_progress_data($book) {
	$data = array('progress' => 0, 'phase_name' => null, 'deadline' => null);
	$book = mbp_get_book($book);
	$phases = mbp_get_book_phases($book['id']);

	$working_phases = array();
	foreach($phases as $phase) {
		$phase_status = mbp_get_book_phase_status($book, $phase['id']);
		$phase_progress = mbp_get_book_phase_progress($book, $phase['id']);
		if($phase_status != 'complete' and $phase_progress > 0) {
			$working_phases[] = array_merge($phase, array('progress' => $phase_progress));
		}
	}

	if(empty($phases)) {
		$data['progress'] = 0;
	} else if(count($working_phases) == 1) {
		$current_phase = $working_phases[0];
		$data['progress'] = $current_phase['progress'];
		$data['phase_name'] = $current_phase['name'];
		$data['deadline'] = $current_phase['deadline'];
	} else if(count($working_phases) > 1) {
		$total_progress = 0;
		foreach($working_phases as $working_phase) {
			$total_progress += $working_phase['progress'];
		}
		$data['progress'] = $total_progress / count($working_phases);
	} else {
		global $wpdb;
		$query = $wpdb->prepare("SELECT * FROM ".$wpdb->mbp_progress." WHERE book_id = %d ORDER BY timestamp DESC LIMIT 1", $book['id']);
		$results = $wpdb->get_results($query, ARRAY_A);
		if(!empty($results)) {
			$progress = mbp_progress_entry_db_export(reset($results));
			$current_phase = mbp_get_book_phase($book, $progress['phase_id']);
		}

		if(!empty($current_phase) and mbp_get_book_phase_status($book, $current_phase['id']) === 'complete') {
			$data['progress'] = 1;
			$data['phase_name'] = $current_phase['name'];
		} else {
			$total_progress = 0;
			foreach($phases as $phase) {
				$phase_status = mbp_get_book_phase_status($book, $phase['id']);
				$phase_progress = mbp_get_book_phase_progress($book, $phase['id']);
				$total_progress += $phase_status == 'complete' ? 1 : $phase_progress;
			}
			$data['progress'] = $total_progress / count($phases);
		}
	}

	return $data;
}



/*---------------------------------------------------------*/
/* Progress Entries                                        */
/*---------------------------------------------------------*/

function mbp_progress_entry_db_export($progress) {
	$new_progress = unserialize($progress['data']);
	$new_progress['id'] = $progress['id'];
	$new_progress['book_id'] = intval($progress['book_id']);
	$new_progress['phase_id'] = intval($progress['phase_id']);
	$new_progress['timestamp'] = strtotime($progress['timestamp']);
	return $new_progress;
}

function mbp_progress_entry_db_import($progress) {
	$new_progress = array();
	$new_progress['id'] = $progress['id'];
	$new_progress['book_id'] = intval($progress['book_id']);
	$new_progress['phase_id'] = intval($progress['phase_id']);
	$new_progress['timestamp'] = date("Y-m-d H:i:s", $progress['timestamp']);
	unset($progress['book_id']);
	unset($progress['phase_id']);
	unset($progress['timestamp']);
	$new_progress['data'] = serialize($progress);
	return $new_progress;
}

function mbp_get_book_phase_progress_entries($book_id, $phase_id) {
	global $wpdb;
	$query = $wpdb->prepare("SELECT * FROM ".$wpdb->mbp_progress." WHERE book_id = %d AND phase_id = %d ORDER BY timestamp DESC", $book_id, $phase_id);
	$results = $wpdb->get_results($query, ARRAY_A);
	for($i=0; $i < count($results); $i++) { $results[$i] = mbp_progress_entry_db_export($results[$i]); }
	return $results;
}

function mbp_get_book_progress_entries($book_id) {
	global $wpdb;
	$query = $wpdb->prepare("SELECT * FROM ".$wpdb->mbp_progress." WHERE book_id = %d ORDER BY timestamp DESC", $book_id);
	$results = $wpdb->get_results($query, ARRAY_A);
	for($i=0; $i < count($results); $i++) { $results[$i] = mbp_progress_entry_db_export($results[$i]); }
	return $results;
}

function mbp_get_book_progress_entries_page($book_id, $before) {
	if(empty($before)) { $before = time(); }
	$entries_per_page = apply_filters('mbp_progress_entries_per_page', 10);
	global $wpdb;
	$query = $wpdb->prepare("SELECT * FROM ".$wpdb->mbp_progress." WHERE book_id = %d AND UNIX_TIMESTAMP(timestamp) < UNIX_TIMESTAMP(%s) ORDER BY timestamp DESC LIMIT %d", $book_id, date("Y-m-d H:i:s", $before), $entries_per_page);
	$entries = $wpdb->get_results($query, ARRAY_A);
	for($i = 0; $i < count($entries); $i++) { $entries[$i] = mbp_progress_entry_db_export($entries[$i]); }
	$last = count($entries) ? intval($entries[count($entries)-1]['timestamp']) : $before;
	$has_more = count($entries) ? ($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM ".$wpdb->mbp_progress." WHERE book_id = %d AND UNIX_TIMESTAMP(timestamp) < UNIX_TIMESTAMP(%s)", $book_id, date("Y-m-d H:i:s", $last))) > 0) : false;
	return array($entries, $last, $has_more);
}

function mbp_delete_book_progress_entries($book_id) {
	global $wpdb;
	$wpdb->delete($wpdb->mbp_progress, array('book_id' => $book_id), array('%d'));
}

function mbp_create_progress_entry($new_progress) {
	global $wpdb;
	$new_progress['id'] = 0;
	$progress = mbp_progress_entry_db_import($new_progress);
	$result = $wpdb->insert($wpdb->mbp_progress, array('book_id' => $progress['book_id'], 'phase_id' => $progress['phase_id'], 'timestamp' => $progress['timestamp'], 'data' => $progress['data']), array('%d', '%d', '%s', '%s'));
	mbp_refresh_book_phases_progress($progress['book_id']);
	return $result ? $wpdb->insert_id : false;
}

function mbp_get_progress_entry($progress_id) {
	global $wpdb;
	$query = $wpdb->prepare("SELECT * FROM ".$wpdb->mbp_progress." WHERE id = %d", $progress_id);
	$result = $wpdb->get_row($query, ARRAY_A);
	return $result ? mbp_progress_entry_db_export($result) : null;
}

function mbp_update_progress_entry($updated_progress) {
	global $wpdb;
	$progress = mbp_progress_entry_db_import($updated_progress);
	$wpdb->update($wpdb->mbp_progress, array('book_id' => $progress['book_id'], 'phase_id' => $progress['phase_id'], 'timestamp' => $progress['timestamp'], 'data' => $progress['data']), array('id' => $progress['id']), array('%d', '%d', '%s', '%s'), array('%d'));
	mbp_refresh_book_phases_progress($progress['book_id']);
}

function mbp_delete_progress_entry($progress_id) {
	global $wpdb;
	$progress = mbp_get_progress_entry($progress_id);
	$wpdb->delete($wpdb->mbp_progress, array('id' => $progress_id), array('%d'));
	mbp_refresh_book_phases_progress($progress['book_id']);
}

function mpb_progress_database_check() {
	global $wpdb;
	$wpdb->mbp_progress = $wpdb->prefix.'mbp_progress';

	$table_exists = $wpdb->query("SHOW TABLES LIKE '".$wpdb->mbp_progress."'");
	if(!$table_exists) { mpb_progress_install_tables(); }
}
add_action('mbp_init', 'mpb_progress_database_check', 5);

function mpb_progress_install_tables() {
	global $wpdb;

	$query = "CREATE TABLE IF NOT EXISTS `".$wpdb->mbp_progress."` (
		`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		`book_id` bigint(20) unsigned NOT NULL,
		`phase_id` bigint(20) unsigned,
		`timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`data` longtext NOT NULL,
		PRIMARY KEY (`id`)
		) ".$wpdb->get_charset_collate().";";
	$wpdb->query($query);
}



/*---------------------------------------------------------*/
/* Style Packs                                             */
/*---------------------------------------------------------*/

function mbp_get_wp_filesystem($nonce_url) {
	ob_start();
	$creds = request_filesystem_credentials($nonce_url, '', false, false, null);
	$output = ob_get_contents();
	ob_end_clean();
	if($creds === false) { return $output; }

	if(!WP_Filesystem($creds)) {
		ob_start();
		request_filesystem_credentials($nonce_url, '', true, false, null);
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	return '';
}

function mbp_upload_stylepack($stylepack_id) {
	$file_post = get_post($stylepack_id);
	if(empty($file_post)) { return false; }
	$style_name = $file_post->post_title;
	$file_path = get_post_meta($stylepack_id, '_wp_attached_file', true);

	$nonce_url = wp_nonce_url('admin.php?page=mbp_admin_page', 'mbp-stylepack-upload');
	$output = mbp_get_wp_filesystem($nonce_url);
	if(!empty($output)) { return false; }

	global $wp_filesystem;

	$upload_dir = wp_upload_dir();
	if(substr($upload_dir['basedir'], 0, strlen(ABSPATH)) !== ABSPATH) { return false; }
	$content_prefix = substr($upload_dir['basedir'], strlen(ABSPATH));
	$from = $upload_dir['basedir'].DIRECTORY_SEPARATOR.$file_path;
	$to = $wp_filesystem->abspath().$content_prefix.DIRECTORY_SEPARATOR.'mbp_styles'.DIRECTORY_SEPARATOR.$style_name;
	$result = unzip_file($from, $to);
	delete_transient('mbp_style_packs');

	return $result === true;
}

function mbp_get_current_style_pack() {
	$style_packs = mbp_get_style_packs();
	$style_pack = mbp_get_setting('style_pack');
	foreach($style_packs as $id => $pack) {
		if($id === $style_pack) { return $pack; }
	}
	return empty($style_packs) ? null : reset($style_packs);
}

function mbp_get_style_pack($style_pack_id) {
	$style_packs = mbp_get_style_packs();
	foreach($style_packs as $id => $pack) {
		if($id === $style_pack_id) { return $pack; }
	}
	return null;
}

function mbp_get_style_packs() {
	$styles = get_transient('mbp_style_packs');
	if($styles === false) {
		$styles = array();
		$dirs = mbp_get_style_pack_dirs();

		$default_headers = array(
			'name' => 'Style Pack Name',
			'stylepack_uri' => 'Style Pack URI',
			'version' => 'Version',
			'desc' => 'Description',
			'author' => 'Author',
			'author_uri' => 'Author URI',
			'supports' => 'Supports',
		);

		foreach($dirs as $id => $dir) {
			if(is_dir($dir) and $dir_handle = opendir($dir)) {
				while(false !== ($dir_entry = readdir($dir_handle))) {
					if($dir_entry == '.' or $dir_entry == '..') { continue; }
					$style_file = $dir.DIRECTORY_SEPARATOR.$dir_entry.DIRECTORY_SEPARATOR.'style.css';
					if(file_exists($style_file)) {
						$data = get_file_data($style_file, $default_headers, 'mbp_style_pack');
						$data['supports'] = array_map('trim', explode(',', $data['supports']));
						if(!in_array('no-bar-color', $data['supports']) and !in_array('bar-color', $data['supports'])) { $data['supports'][] = 'bar-color'; }
						$data['style_dir'] = dirname($style_file);
						$data['style_dir_url'] = content_url(str_replace(str_replace("\\", "/", WP_CONTENT_DIR), '', str_replace("\\", "/", dirname($style_file))));

						if($style_dir_handle = opendir($data['style_dir'])) {
							while(false !== ($style_dir_entry = readdir($style_dir_handle))) {
								if($style_dir_entry == '.' or $style_dir_entry == '..') { continue; }
								$file_info = wp_check_filetype($style_dir_entry);
								$basename = basename($style_dir_entry, '.'.$file_info['ext']);
								$is_image = preg_match('/image\/.*/', $file_info['type']);

								if($basename == 'preview' and $is_image) {
									$data['preview'] = $data['style_dir_url'].'/'.$style_dir_entry;
									break;
								}
							}
							closedir($style_dir_handle);
						}

						$styles[$id.'/'.$dir_entry] = $data;
					}
				}
				closedir($dir_handle);
			}
		}

		set_transient('mbp_style_packs', $styles, 600);
	}

	return $styles;
}

function mbp_refresh_style_packs() {
	delete_transient('mbp_style_packs');
}

function mbp_plugins_path($path = '', $plugin = '') {
	$path = wp_normalize_path($path);
	$plugin = wp_normalize_path($plugin);

	$plugins_path = WP_PLUGIN_DIR;

	if(!empty($plugin) and is_string($plugin)) {
		$folder = dirname(plugin_basename($plugin));
		if('.' != $folder) {
			$plugins_path .= DIRECTORY_SEPARATOR . ltrim($folder, DIRECTORY_SEPARATOR);
		}
	}

	if($path and is_string($path)) {
		$plugins_path .= DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
	}

	return $plugins_path;

}

function mbp_get_style_pack_dirs() {
	$upload_dir = wp_upload_dir();
	$dirs = array(
		'plugin' => mbp_plugins_path('styles', dirname(__FILE__)),
		'uploaded' => $upload_dir['basedir'].DIRECTORY_SEPARATOR.'mbp_styles',
	);
	return apply_filters('mbp_style_pack_dirs', $dirs);
}



/*---------------------------------------------------------*/
/* Phase Templates                                         */
/*---------------------------------------------------------*/

function mbp_get_phase_template($template_id) {
	$templates = mbp_get_phase_templates();
	foreach($templates as $i => $template) {
		if($template['id'] == $template_id) {
			return $template;
		}
	}
	return null;
}

function mbp_get_phase_templates() {
	$default_templates = apply_filters('mbp_phase_templates', array());
	$formatted_default_templates = array();
	foreach($default_templates as $i => $template) {
		$default_templates[$i]['id'] = $i;
		$default_templates[$i]['default'] = true;
		$formatted_default_templates[] = $default_templates[$i];
	}
	$saved_templates = mbp_get_setting('phase_templates');
	if(empty($saved_templates) or !is_array($saved_templates)) { $saved_templates = array(); }
	$templates = array_merge($formatted_default_templates, $saved_templates);
	foreach($templates as $i => $template) {
		foreach($templates[$i]['phases'] as $j => $phase) {
			if(empty($phase['id'])) { $templates[$i]['phases'][$j]['id'] = abs(crc32(strval($templates[$i]['id'])))-$j; }
		}
		if(empty($templates[$i]['priority'])) { $templates[$i]['priority'] = 0; }
	}
	usort($templates, 'sh_usort_function');
	return $templates;
}

function sh_usort_function($a,$b){
	return ($a["priority"] == $b["priority"]) ? strcmp($a["id"], $b["id"]) : (($a["priority"] > $b["priority"]) ? -1 : 1);
}
function mbp_update_phase_templates($templates) {
	foreach($templates as $id => $template) {
		if($templates[$id]['default']) { unset($templates[$id]); }
	}
	mbp_update_setting('phase_templates', $templates);
}

function mbp_add_default_phase_templates($templates) {
	$templates['default'] = array(
		'name' => 'Standard',
		'priority' => 5,
		'phases' => array(
			array(
				'name' => 'Research',
				'deadline' => null,
				'progress_type' => 'percent',
				'progress_target' => 100,
			),
			array(
				'name' => 'Outlining',
				'deadline' => null,
				'progress_type' => 'percent',
				'progress_target' => 100,
			),
			array(
				'name' => 'Writing',
				'deadline' => null,
				'progress_type' => 'words',
				'progress_target' => 50000,
			),
			array(
				'name' => 'Editing',
				'deadline' => null,
				'progress_type' => 'pages',
				'progress_target' => 200,
			),
			array(
				'name' => 'Proofing',
				'deadline' => null,
				'progress_type' => 'percent',
				'progress_target' => 100,
			),
		),
	);

	$templates['nonfiction'] = array(
		'name' => 'Traditional Nonfiction',
		'priority' => 4,
		'phases' => array(
			array(
				'name' => 'Outline',
				'deadline' => null,
				'progress_type' => 'percent',
				'progress_target' => 100,
			),
			array(
				'name' => 'Sample Chapters',
				'deadline' => null,
				'progress_type' => 'chapters',
				'progress_target' => 3,
			),
			array(
				'name' => 'Proposal',
				'deadline' => null,
				'progress_type' => 'percent',
				'progress_target' => 100,
			),
			array(
				'name' => 'Writing',
				'deadline' => null,
				'progress_type' => 'words',
				'progress_target' => 50000,
			),
			array(
				'name' => 'Editing',
				'deadline' => null,
				'progress_type' => 'pages',
				'progress_target' => 200,
			),
			array(
				'name' => 'Proofing',
				'deadline' => null,
				'progress_type' => 'percent',
				'progress_target' => 100,
			),
		),
	);

	$templates['seat-of-the-pants'] = array(
		'name' => 'Seat of the Pants',
		'priority' => 3,
		'phases' => array(
			array(
				'name' => 'Exploratory Draft',
				'deadline' => null,
				'progress_type' => 'words',
				'progress_target' => 50000,
			),
			array(
				'name' => 'Revising',
				'deadline' => null,
				'progress_type' => 'words',
				'progress_target' => 50000,
			),
			array(
				'name' => 'Editing',
				'deadline' => null,
				'progress_type' => 'pages',
				'progress_target' => 200,
			),
			array(
				'name' => 'Proofing',
				'deadline' => null,
				'progress_type' => 'percent',
				'progress_target' => 100,
			),
		),
	);

	$templates['nanowrimo'] = array(
		'name' => 'NaNoWriMo',
		'priority' => date('n') == '11' ? 10 : 2,
		'phases' => array(
			array(
				'name' => 'Week 1',
				'deadline' => strtotime('07/Nov/'.date('Y').':00:00:00 -0000'),
				'progress_type' => 'words',
				'progress_target' => 12500,
			),
			array(
				'name' => 'Week 2',
				'deadline' => strtotime('14/Nov/'.date('Y').':00:00:00 -0000'),
				'progress_type' => 'words',
				'progress_target' => 12500,
			),
			array(
				'name' => 'Week 3',
				'deadline' => strtotime('21/Nov/'.date('Y').':00:00:00 -0000'),
				'progress_type' => 'words',
				'progress_target' => 12500,
			),
			array(
				'name' => 'Week 4',
				'deadline' => strtotime('30/Nov/'.date('Y').':00:00:00 -0000'),
				'progress_type' => 'words',
				'progress_target' => 12500,
			),
		),
	);

	$templates['snowflake'] = array(
		'name' => 'Snowflake Method',
		'priority' => 1,
		'phases' => array(
			array(
				'name' => 'Story Structure',
				'deadline' => null,
				'progress_type' => 'percent',
				'progress_target' => 100,
			),
			array(
				'name' => 'Characters',
				'deadline' => null,
				'progress_type' => 'percent',
				'progress_target' => 100,
			),
			array(
				'name' => 'Scene List',
				'deadline' => null,
				'progress_type' => 'scenes',
				'progress_target' => 100,
			),
			array(
				'name' => 'First Draft',
				'deadline' => null,
				'progress_type' => 'words',
				'progress_target' => 50000,
			),
			array(
				'name' => 'Editing',
				'deadline' => null,
				'progress_type' => 'words',
				'progress_target' => 200,
			),
			array(
				'name' => 'Proofing',
				'deadline' => null,
				'progress_type' => 'percent',
				'progress_target' => 100,
			),
		),
	);

	return $templates;
}
add_filter('mbp_phase_templates', 'mbp_add_default_phase_templates');



/*---------------------------------------------------------*/
/* Statistics                                              */
/*---------------------------------------------------------*/

function mbp_get_phase_stats($book_id, $phase_id) {
	global $wpdb;
	$stats = array();

	$query = $wpdb->prepare("SELECT DAYOFWEEK(timestamp) as day, COUNT(*) as count FROM ".$wpdb->mbp_progress." WHERE book_id = %d AND phase_id = %d GROUP BY DAYOFWEEK(timestamp)", $book_id, $phase_id);
	$results = $wpdb->get_results($query, ARRAY_A);
	if(empty($results)) { return null; }
	$day = '';
	$max = -1;
	$days = array(
		1 => __('Sunday', 'mybookprogress'),
		2 => __('Monday', 'mybookprogress'),
		3 => __('Tuesday', 'mybookprogress'),
		4 => __('Wednesday', 'mybookprogress'),
		5 => __('Thursday', 'mybookprogress'),
		6 => __('Friday', 'mybookprogress'),
		7 => __('Saturday', 'mybookprogress'),
	);
	foreach($results as $result) {
		if($result['count'] > $max) {
			$max = $result['count'];
			$day = $days[$result['day']];
		}
	}
	$stats['most_productive_day'] = $day;

	$phase = mbp_get_book_phase($book_id, $phase_id);
	$entries = mbp_get_book_phase_progress_entries($book_id, $phase_id);

	$last_entry = $entries[0];
	$first_entry = $entries[count($entries)-1];

	$time_elapsed = $last_entry['timestamp'] - $first_entry['timestamp'];
	if($time_elapsed != 0) {
		$total_progress = $last_entry['target'] * $last_entry['progress'];

		$current_per_second = $total_progress / $time_elapsed;
		$stats['progress_type'] = $phase['progress_type'];
		$stats['current_per_day'] = min($total_progress, $current_per_second*86400);
		$stats['current_per_week'] = min($total_progress, $current_per_second*604800);
		$stats['current_per_month'] = min($total_progress, $current_per_second*2592000);

		if(!empty($phase['deadline'])) {
			$time_to_deadline = $phase['deadline'] - current_time('timestamp');
			$progress_to_deadline = $phase['progress_target'] - $last_entry['target']*$last_entry['progress'];
			if($time_to_deadline > 0 and $progress_to_deadline > 0) {
				$needed_per_second = $progress_to_deadline/$time_to_deadline;

				$stats['needed_per_day'] = min($phase['progress_target'], $needed_per_second*86400);
				$stats['needed_per_week'] = min($phase['progress_target'], $needed_per_second*604800);
				$stats['needed_per_month'] = min($phase['progress_target'], $needed_per_second*2592000);
			}
		}
	}

	$graph_data = array();
	foreach($entries as $key => $progress) {
		$graph_data[] = array(
			$progress['timestamp'],
			$progress['progress']*$progress['target'],
		);
	}
	$stats['graph_data'] = array_reverse($graph_data);

	if(!empty($phase['deadline'])) { $stats['deadline'] = $phase['deadline']; }
	$stats['progress_target'] = $phase['progress_target'];

	return $stats;
}

function mbp_get_book_stats($book_id) {
	global $wpdb;
	$stats = array();

	$query = $wpdb->prepare("SELECT DAYOFWEEK(timestamp) as day, COUNT(*) as count FROM ".$wpdb->mbp_progress." WHERE book_id = %d GROUP BY DAYOFWEEK(timestamp)", $book_id);
	$results = $wpdb->get_results($query, ARRAY_A);
	if(empty($results)) { return null; }
	$day = '';
	$max = -1;
	$days = array(
		1 => __('Sunday', 'mybookprogress'),
		2 => __('Monday', 'mybookprogress'),
		3 => __('Tuesday', 'mybookprogress'),
		4 => __('Wednesday', 'mybookprogress'),
		5 => __('Thursday', 'mybookprogress'),
		6 => __('Friday', 'mybookprogress'),
		7 => __('Saturday', 'mybookprogress'),
	);
	foreach($results as $result) {
		if($result['count'] > $max) {
			$max = $result['count'];
			$day = $days[$result['day']];
		}
	}
	$stats['most_productive_day'] = $day;

	$book = mbp_get_book($book_id);
	$entries = mbp_get_book_progress_entries($book_id);
	$phases = mbp_get_book_phases($book_id);

	// get most recent progress entry of book
	$query = $wpdb->prepare("SELECT * FROM ".$wpdb->mbp_progress." WHERE book_id = %d ORDER BY timestamp DESC LIMIT 1", $book_id);
	$results = $wpdb->get_results($query, ARRAY_A);
	$last_entry = count($results) > 0 ? mbp_progress_entry_db_export($results[0]) : null;

	// get oldest progress entry of book
	$query = $wpdb->prepare("SELECT * FROM ".$wpdb->mbp_progress." WHERE book_id = %d ORDER BY timestamp ASC LIMIT 1", $book_id);
	$results = $wpdb->get_results($query, ARRAY_A);
	$first_entry = count($results) > 0 ? mbp_progress_entry_db_export($results[0]) : null;

	$time_elapsed = $last_entry['timestamp'] - $first_entry['timestamp'];
	if($time_elapsed != 0) {
		$total_progress = 0;
		foreach($phases as $phase) {
			$phase_status = mbp_get_book_phase_status($book, $phase['id']);
			$phase_progress = mbp_get_book_phase_progress($book, $phase['id']);
			$total_progress += $phase_status == 'complete' ? 1 : $phase_progress;
		}
		$total_progress = $total_progress*100/max(1, count($phases));

		$current_per_second = $total_progress / $time_elapsed;
		$stats['current_per_day'] = min($total_progress, $current_per_second*86400);
		$stats['current_per_week'] = min($total_progress, $current_per_second*604800);
		$stats['current_per_month'] = min($total_progress, $current_per_second*2592000);
		$stats['progress_type'] = 'percent';
	}

	$graph_data = array();
	$phase_names = array();
	for($i=0; $i < count($phases); $i++) {
		$phase_names[] = empty($phases[$i]['name']) ? __('(no name)', 'mybookprogress') : $phases[$i]['name'];
		$entries = mbp_get_book_phase_progress_entries($book['id'], $phases[$i]['id']);
		foreach($entries as $entry) {
			$new_data = array($entry['timestamp']);
			foreach($phases as $phase) {
				if($phase['id'] == $phases[$i]['id']) {
					$new_data[] = $entry['progress'];
				} else {
					$new_data[] = null;
				}
			}
			$graph_data[] = $new_data;
		}
	}
	if(empty($graph_data)) { return null; }
	$stats['phases'] = $phase_names;
	$stats['graph_data'] = $graph_data;

	return $stats;
}

function mbp_get_global_stats() {
	global $wpdb;
	$stats = array();

	$results = $wpdb->get_results("SELECT DAYOFWEEK(timestamp) as day, COUNT(*) as count FROM ".$wpdb->mbp_progress." GROUP BY DAYOFWEEK(timestamp)", ARRAY_A);
	if(empty($results)) { return null; }
	$day = '';
	$max = -1;
	$days = array(
		1 => __('Sunday', 'mybookprogress'),
		2 => __('Monday', 'mybookprogress'),
		3 => __('Tuesday', 'mybookprogress'),
		4 => __('Wednesday', 'mybookprogress'),
		5 => __('Thursday', 'mybookprogress'),
		6 => __('Friday', 'mybookprogress'),
		7 => __('Saturday', 'mybookprogress'),
	);
	foreach($results as $result) {
		if($result['count'] > $max) {
			$max = $result['count'];
			$day = $days[$result['day']];
		}
	}
	$stats['most_productive_day'] = $day;

	$books = mbp_get_books();
	$graph_data = array();
	$book_names = array();
	for($i=0; $i < count($books); $i++) {
		$book_names[] = mbp_get_book_title($books[$i]);
		$phases = mbp_get_book_phases($books[$i]['id']);
		$entries = mbp_get_book_progress_entries($books[$i]['id']);
		foreach($entries as $entry) {
			$new_data = array($entry['timestamp']);
			foreach($books as $book) {
				if($book['id'] == $books[$i]['id']) {
					$entry_phase = -1;
					for($j=0; $j < count($phases); $j++) {
						if($phases[$j]['id'] == $entry['phase_id']) {
							$entry_phase = $j;
							break;
						}
					}
					if($entry_phase !== -1) {
						$new_data[] = ($entry['progress']+$entry_phase)/count($phases);
					} else {
						$new_data[] = null;
					}
				} else {
					$new_data[] = null;
				}
			}
			$graph_data[] = $new_data;
		}
	}
	if(empty($graph_data)) { return null; }
	$stats['books'] = $book_names;
	$stats['graph_data'] = $graph_data;

	return $stats;
}



/*---------------------------------------------------------*/
/* MyBookTable Integration                                 */
/*---------------------------------------------------------*/

function mbp_mybooktable_metabox($post) {
	$current_book = intval(get_post_meta($post->ID, 'mbp_mybookprogress_book', true));
	$books = mbp_get_books();
	echo(__('This book corresponds to: ', 'mybookprogress'));
	echo('<select name="mbp_mybookprogress_book">');
	echo('<option value="">-- '.__('Choose One').' --</option>');
	foreach($books as $book) {
		if(!empty($book['mbt_book']) and $book['mbt_book'] != $post->ID) { continue; }
		echo('<option value="'.$book['id'].'"'.($current_book == $book['id'] ? ' selected="selected"' : '').'>'.mbp_get_book_title($book).'</option>');
	}
	echo('</select>');
	echo('<br>');
	$show_progress = get_post_meta($post->ID, 'mbp_show_mybookprogress', true);
	echo('<input type="checkbox" name="mbp_show_mybookprogress" id="mbp_show_mybookprogress"'.($show_progress == 'no' ? '' : ' checked="checked"').'>');
	echo('<label for="mbp_show_mybookprogress">'.__('Show progress bar on book page').'</label>');
}

function mbp_save_mybooktable_metabox($post_id) {
	if((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || get_post_type($post_id) != "mbt_book") { return; }

	update_post_meta($post_id, 'mbp_show_mybookprogress', isset($_REQUEST['mbp_show_mybookprogress']) ? 'yes' : 'no');

	if(isset($_REQUEST['mbp_mybookprogress_book'])) {
		$current_book = get_post_meta($post_id, 'mbp_mybookprogress_book', true);
		if(empty($current_book)) { $current_book = null; } else { $current_book = intval($current_book); }
		$mbp_book = intval($_REQUEST['mbp_mybookprogress_book']);
		if($current_book == $mbp_book) { return; }

		mbp_update_mybooktable_book(array('id' => $post_id, 'mbp_book' => $mbp_book));
		$mbp_books = mbp_get_books();
		foreach($mbp_books as $book) {
			if($book['id'] == $mbp_book) {
				$book['mbt_book'] = $post_id;
				mbp_update_book($book);
			} else if($book['mbt_book'] == $post_id) {
				$book['mbt_book'] = null;
				mbp_update_book($book);
			}
		}
	}

}

function mpb_add_mybooktable_metabox() {
	add_meta_box('mbp_mybooktable_metabox', __('MyBookProgress Book', 'mybookprogress'), 'mbp_mybooktable_metabox', 'mbt_book', 'normal', 'high');
}

function mbp_mybooktable_book_progress() {
	global $post;
	$current_book = get_post_meta($post->ID, 'mbp_mybookprogress_book', true);
	$show_progress = get_post_meta($post->ID, 'mbp_show_mybookprogress', true);
	if(!empty($current_book) and $show_progress != 'no') {
		echo('<div class="mbt-book-progress"><div class="mbp-container">');
		echo(mbp_format_book_progress($current_book, array('show_image' => false)));
		echo('</div></div>');
	}
}

function mpb_mybooktable_integration() {
	if(defined('MBT_VERSION')) {
		add_action('add_meta_boxes', 'mpb_add_mybooktable_metabox', 11);
		add_action('save_post', 'mbp_save_mybooktable_metabox');
		add_action('mbt_single_book_buybuttons', 'mbp_mybooktable_book_progress', 15);
	}
}
add_action('mbp_init', 'mpb_mybooktable_integration');

function mbp_update_mybooktable_book($object) {
	if(defined('MBT_VERSION')) {
		update_post_meta($object['id'], 'mbp_mybookprogress_book', $object['mbp_book']);
	}
}

function mbp_get_mybooktable_books() {
	$mbt_books = array();
	if(defined('MBT_VERSION')) {
		$query = new WP_Query(array('post_type' => 'mbt_book', 'posts_per_page' => -1));
		if(!empty($query->posts) and is_array($query->posts)) {
			foreach($query->posts as $post) {
				$book = get_post_meta($post->ID, 'mbt_mybookprogress_book', true);
				$mbt_books[] = array(
					'id' => $post->ID,
					'title' => $post->post_title,
					'mbp_book' => empty($book) ? null : intval($book),
				);
			}
		}
	}
	return $mbt_books;
}

function mbp_add_mybooktable_link_book_button($buttons, $book) {
	if($book['mbt_book'] and mbp_get_setting('mybooktable_frontend_link')) {
		$buttons['mybooktable-link'] = array('attrs' => array(
			'onclick' => 'return mybookprogress.mybooktable_link(this);',
			'data-href' => get_permalink($book['mbt_book']),
		));
	}
	return $buttons;
}
add_filter('mbp_get_book_buttons', 'mbp_add_mybooktable_link_book_button', 10, 2);



/*---------------------------------------------------------*/
/* Utility                                                 */
/*---------------------------------------------------------*/

function mbp_do_mailchimp_query($api_key, $method, $data = array()) { 
	$datacenter = substr(strrchr($api_key, '-'), 1);
	if(empty($datacenter)) { $datacenter = "us1"; }
	$url = "https://{$datacenter}.api.mailchimp.com/3.0/".$method."/";
	//$data['apikey'] = $api_key;
	global $wp_version;
	$options = array(
		'headers' => array(
			'Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key )
		),
		'body'		=> $data,
		'sslverify' => false,
		'timeout' 	=> 3,
		'user-agent' => 'WordPress/'.$wp_version
	);
	if($method=='lists'){
		$raw_response = wp_remote_get($url, $options);
		if(!is_wp_error($raw_response)) {
			$code = wp_remote_retrieve_response_code($raw_response);
			if($code == 200 or $code == 500) {
				try {
					$response = json_decode(wp_remote_retrieve_body($raw_response));
				} catch (Exception $e) {
					$response = null;
				}
				return $response;
			}
		}		
	} else {
		$raw_response = wp_remote_get($url, $options);
		if(!is_wp_error($raw_response) and wp_remote_retrieve_response_code($raw_response) == 200) {
			$response = json_decode(wp_remote_retrieve_body($raw_response));
			return $response;
		}
	}
}

// added separate function for subscribes because of all the changes in api 3.0
function mbp_do_mailchimp_subscribe($apikey, $list_id, $email) { 
	$datacenter = substr(strrchr($apikey, '-'), 1);
	if(empty($datacenter)) { $datacenter = "us1"; }
	$url = 'https://'.$datacenter.'.api.mailchimp.com/3.0/lists/'.$list_id.'/members/'.md5(strtolower($email));
	$options = array(
		'method' => 'PUT',
	 	'headers' => array(
			'Authorization' => 'Basic ' . base64_encode( 'user:'. $apikey )
		),
		'body' => json_encode(
			array(
				'email_address' => $email, 
				'status' => 'pending' 
			)
		)
	);
	$raw_response = wp_remote_request($url, $options);

	if(!is_wp_error($raw_response) and wp_remote_retrieve_response_code($raw_response) == 200) {
		$response = json_decode(wp_remote_retrieve_body($raw_response));
		return $response;
	}
}

/*---------------------------------------------------------*/
/* Tracking                                                */
/*---------------------------------------------------------*/

function mbp_init_tracking() {
	if(mbp_get_setting('allow_tracking') !== 'yes') { return; }

	if(!wp_next_scheduled('mbp_periodic_tracking')) { wp_schedule_event(time(), 'weekly', 'mbp_periodic_tracking'); }
	add_action('mbp_periodic_tracking', 'mbp_send_tracking_data');
}
add_action('mbp_init', 'mbp_init_tracking');

function mbp_load_tracking_data() {
	global $mbp_tracking_data;
	if(empty($mbp_tracking_data)) {
		$mbp_tracking_data = get_option('mbp_tracking_data');
		if(empty($mbp_tracking_data)) {
			mt_srand(time());
			$payload = strval(get_bloginfo('url')).strval(time()).strval(rand());
			if(function_exists('hash')) {
				$id = hash('sha256', $payload);
			} else {
				$id = sha1($payload);
			}

			$mbp_tracking_data = array(
				'id' => $id,
				'events' => array(),
				'ab_status' => array(),
			);

			update_option('mbp_tracking_data', $mbp_tracking_data);
		}
	}
}

function mbp_get_tracking_data($name) {
	global $mbp_tracking_data;
	mbp_load_tracking_data();
	return isset($mbp_tracking_data[$name]) ? $mbp_tracking_data[$name] : NULL;
}

function mbp_update_tracking_data($name, $value) {
	global $mbp_tracking_data;
	mbp_load_tracking_data();
	$mbp_tracking_data[$name] = $value;
	update_option('mbp_tracking_data', $mbp_tracking_data);
}

function mbp_track_event($name, $instance=false) {
	$events = mbp_get_tracking_data('events');
	if(!isset($events[$name])) { $events[$name] = array(); }
	if(!isset($events[$name]['count'])) { $events[$name]['count'] = 0; }
	$events[$name]['count'] += 1;
	$events[$name]['last_time'] = time();

	if($instance !== false) {
		if(!is_array($instance)) { $instance = array(); }
		$instance['time'] = time();
		if(!isset($events[$name]['instances'])) { $events[$name]['instances'] = array(); }
		$events[$name]['instances'][] = $instance;
	}

	mbp_update_tracking_data('events', $events);
}

function mbp_send_tracking_data() {
	if(mbp_get_setting('allow_tracking') !== 'yes') { return; }

	$books = mbp_get_books();
	$email_updates_periods = array();
	$has_mbt_book = 0;
	$templates = array('custom' => 0);
	$all_templates = array();
	foreach(mbp_get_phase_templates() as $id => $template) {
		$all_templates[$template['id']] = $template;
		$templates[$template['name']] = 0;
	}
	foreach($books as $book) {
		if(is_array($book['phases'])) {
			$templates['custom'] += 1;
		} else if(isset($all_templates[$book['phases']])) {
			$templates[$all_templates[$book['phases']]['name']] += 1;
		}
		if(mbp_get_upgrade() and isset($book['email_updates_period'])) {
			if(!isset($email_updates_periods[$book['email_updates_period']])) { $email_updates_periods[$book['email_updates_period']] = 0; }
			$email_updates_periods[$book['email_updates_period']] += 1;
		}
		if(!empty($book['mbt_book'])) { $has_mbt_book += 1; }
	}

	$nudges = array();
	if(function_exists('mbp_get_nudges')) {
		$nudges = mbp_get_nudges();
	}

	$data = array(
		'id' => mbp_get_tracking_data('id'),
		'time' => time(),
		'version' => MBP_VERSION,
		'installed_time' => mbp_get_setting('installed_time'),
		'settings' => array(
			'mailinglist_type' => mbp_get_setting('mailinglist_type'),
			'enable_linkback' => mbp_get_setting('enable_linkback'),
			'style_pack' => mbp_get_setting('style_pack'),
			'mybooktable_social_media_link' => mbp_get_setting('mybooktable_social_media_link'),
			'mybooktable_frontend_link' => mbp_get_setting('mybooktable_frontend_link'),
		),
		'books' => array(
			'count' => count($books),
			'templates' => $templates,
			'email_updates_periods' => $email_updates_periods,
			'has_mbt_book' => $has_mbt_book,
		),
		'upgrade' => array(
			'name' => mbp_get_upgrade(),
			'version' => mbp_get_upgrade_version(),
			'nudges' => count($nudges),
		),
		'plugins' => array(
			'has_mybooktable' => defined('MBT_VERSION'),
			'has_myspeakingpage' => defined('MSP_VERSION'),
			'has_myspeakingevents' => defined('MSE_VERSION'),
		),
		'events' => mbp_get_tracking_data('events'),
	);

	global $wp_version;
	$options = array(
		'timeout' => ((defined('DOING_CRON') && DOING_CRON) ? 30 : 3),
		'body' => array('data' => serialize($data)),
		'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url')
	);

	$response = wp_remote_post('http://api.authormedia.com/plugins/mybookprogress/analytics/submit', $options);
}

function mbp_track_plugin_activate() {
	mbp_track_event('plugin_activated', true);
}
add_action('mbp_plugin_activate', 'mbp_track_plugin_activate');

function mbp_track_plugin_deactivate() {
	mbp_track_event('plugin_deactivated', true);
	mbp_send_tracking_data();
}
add_action('mbp_plugin_deactivate', 'mbp_track_plugin_deactivate');



/*---------------------------------------------------------*/
/* API / Upgrades                                          */
/*---------------------------------------------------------*/

function mbp_verify_apikey() {
	global $wp_version;

	$apikey = mbp_get_setting('apikey');
	if(empty($apikey)) {
		mbp_update_setting('apikey_status', 0);
		mbp_update_setting('apikey_message', '');
		mbp_update_setting('upgrade_enabled', false);
		return;
	}

	$to_send = array(
		'action' => 'basic_check',
		'version' => MBP_VERSION,
		'api-key' => $apikey,
		'site' => get_bloginfo('url')
	);

	$options = array(
		'timeout' => 3,
		'body' => $to_send,
		'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url')
	);

	$raw_response = wp_remote_post('http://api.authormedia.com/plugins/apikey/check', $options);

	if(is_wp_error($raw_response) || 200 != wp_remote_retrieve_response_code($raw_response)) {
		mbp_update_setting('apikey_status', -1);
		mbp_update_setting('apikey_message', __('Unable to connect to server!', 'mybookprogress'));
		return;
	}

	$response = maybe_unserialize(wp_remote_retrieve_body($raw_response));

	if(!is_array($response) or empty($response['status'])) {
		mbp_update_setting('apikey_status', -2);
		mbp_update_setting('apikey_message', __('Invalid response received from server', 'mybookprogress'));
		return;
	}

	$status = $response['status'];

	if($status == 10) {
		$permissions = array();
		if(!empty($response['permissions']) and is_array($response['permissions'])) {
			$permissions = $response['permissions'];
		}

		if(in_array('mybookprogress-dev', $permissions)) {
			mbp_update_setting('apikey_status', 10);
			mbp_update_setting('upgrade_enabled', 'mybookprogress-dev');
			mbp_update_setting('apikey_message', __('Valid for MyBookProgress Developer', 'mybookprogress'));
		} else if(in_array('mybookprogress-pro', $permissions)) {
			mbp_update_setting('apikey_status', 10);
			mbp_update_setting('upgrade_enabled', 'mybookprogress-pro');
			mbp_update_setting('apikey_message', __('Valid for MyBookProgress Professional', 'mybookprogress'));
		} else {
			mbp_update_setting('apikey_status', -20);
			mbp_update_setting('apikey_message', __('Permissions error!', 'mybookprogress'));
			mbp_update_setting('upgrade_enabled', false);
		}
	} else if($status == -10) {
		mbp_update_setting('apikey_status', $status);
		mbp_update_setting('apikey_message', __('Key not found', 'mybookprogress'));
		mbp_update_setting('upgrade_enabled', false);
	} else if($status == -11) {
		mbp_update_setting('apikey_status', $status);
		mbp_update_setting('apikey_message', __('Key has been deactivated', 'mybookprogress'));
		mbp_update_setting('upgrade_enabled', false);
	} else {
		mbp_update_setting('apikey_status', -2);
		mbp_update_setting('apikey_message', __('Invalid response received from server', 'mybookprogress'));
	}
}

function mbp_schedule_apikey_check() {
	if(!wp_next_scheduled('mbp_periodic_apikey_check')) { wp_schedule_event(time(), 'weekly', 'mbp_periodic_apikey_check'); }
	add_action('mbp_periodic_apikey_check', 'mbp_verify_apikey');
}
add_action('mbp_init', 'mbp_schedule_apikey_check');

function mbp_get_upgrade() {
	$upgrade_enabled = mbp_get_setting('upgrade_enabled');
	return empty($upgrade_enabled) ? false : $upgrade_enabled;
}

function mbp_get_upgrade_version() {
	$upgrade = mbp_get_upgrade();
	if($upgrade == 'mybookprogress-dev' and defined('MBPDEV_VERSION')) { return MBPDEV_VERSION; }
	if($upgrade == 'mybookprogress-pro' and defined('MBPPRO_VERSION')) { return MBPPRO_VERSION; }
	return false;
}

function mbp_get_upgrade_plugin_exists() {
	$upgrade = mbp_get_upgrade();
	if($upgrade == 'mybookprogress-dev')  { return defined('MBPDEV_VERSION') ? 'mybookprogress-dev' : false; }
	if($upgrade == 'mybookprogress-pro')  { return defined('MBPPRO_VERSION') ? 'mybookprogress-pro' : false; }
	return defined('MBPPRO_VERSION') ? 'mybookprogress-pro' : (defined('MBPDEV_VERSION') ? 'mybookprogress-dev' : false);
}



/*---------------------------------------------------------*/
/* Updates                                                 */
/*---------------------------------------------------------*/

function mbp_detect_updates() {
	$version = mbp_get_setting('version');

	if(version_compare($version, '0.8.0') < 0) { mbp_update_0_8_0(); }

	if($version !== MBP_VERSION) {
		mbp_update_setting('version', MBP_VERSION);
		mbp_track_event('plugin_updated', array('version' => MBP_VERSION));
	}
}

function mbp_update_0_8_0() {
	$books = mbp_get_setting('books');
	$books = (empty($books) or !is_array($books)) ? array() : $books;
	foreach($books as $i => $book) {
		$phases_status = array();
		$old_data = (empty($books[$i]['phases_status']) or !is_array($books[$i]['phases_status'])) ? array() : $books[$i]['phases_status'];
		foreach($old_data as $phase_id => $data) {
			$phases_status[$phase_id] = $data['status'] === 'complete' ? 'complete' : '';
		}
		$books[$i]['phases_status'] = $phases_status;
	}
	update_option('mbp_books', $books);

	mbp_update_setting('upgrade_enabled', mbp_get_setting('upgrade_active'));

	mpb_progress_database_check();
	$query = new WP_Query(array('post_type' => 'post', 'posts_per_page'=>-1));
	foreach($query->posts as $post) {
		$matches = array();
		preg_match_all('/\[mybookprogress progress_id="(\d+)"\]/', $post->post_content, $matches, PREG_SET_ORDER);
		foreach($matches as $match) {
			$progress = mbp_get_progress_entry($match[1]);
			if($progress) {
				$book = mbp_get_book($progress['book_id']);
				$phase = mbp_get_book_phase($book['id'], $progress['phase_id']);
				$shortcode_attrs = array();
				$shortcode_attrs['progress'] = $progress['progress'];
				$shortcode_attrs['phase_name'] = $progress['phase_name'];
				if($phase && $phase['deadline']) { $shortcode_attrs['deadline'] = $phase['deadline']; }
				$shortcode_attrs['book'] = $book['id'];
				$shortcode_attrs['book_title'] = mbp_get_book_title($book);
				$shortcode_attrs['bar_color'] = $book['display_bar_color'];
				if($book['display_cover_image']) { $shortcode_attrs['cover_image'] = $book['display_cover_image']; }
				if($book['mbt_book']) { $shortcode_attrs['mbt_book'] = $book['mbt_book']; }
				$shortcode = '';
				foreach($shortcode_attrs as $attr => $value) {
					$shortcode .= ' '.$attr.'="'.$value.'"';
				}
				$shortcode = '[mybookprogress'.$shortcode.']';

				$new_content = preg_replace('/\[mybookprogress progress_id="'.$match[1].'"\]/', $shortcode, $post->post_content);
				wp_update_post(array('ID' => $post->ID, 'post_content' => $new_content));
			}
		}
	}
}
