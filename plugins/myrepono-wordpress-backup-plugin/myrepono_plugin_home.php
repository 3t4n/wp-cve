<?php
/**
 * @package myRepono
 * @version 2.0.12
 */
/*
Copyright 2016 ionix Limited (email: support@myRepono.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ((defined('WP_MYREPONO_PLUGIN')) && (function_exists('is_admin')) && (is_admin())) {

} else {

	print 'myRepono WordPress Backup Plugin can not load.';
	exit;

}


function myrepono_plugin_home_func() {

	global $myrepono;

	$init_request = false;

	$input_domain_id = '';
	$response = '';

	if (!isset($myrepono['tmp']['success'])) {
		$myrepono['tmp']['success'] = array();
	}
	if (!isset($myrepono['tmp']['error'])) {
		$myrepono['tmp']['error'] = array();
	}
	if (!isset($myrepono['tmp']['critical'])) {
		$myrepono['tmp']['critical'] = array();
	}

	if (isset($_GET['myrepono_queue_now'])) {
		if (is_numeric($_GET['myrepono_queue_now'])) {

			$init_request = array(
				'domain-backups-now' => array(
					$_GET['myrepono_queue_now'] => ''
				),
				'domain-queue' => array(
					$_GET['myrepono_queue_now'] => ''
				)
			);

		}
	} elseif (isset($_GET['myrepono_queue_refresh'])) {
		if (is_numeric($_GET['myrepono_queue_refresh'])) {

			$init_request = array(
				'domain-queue' => array(
					$_GET['myrepono_queue_refresh'] => ''
				)
			);

		}
	} elseif (isset($_POST['myrepono_backups'])) {

		if ((isset($_POST['myrepono_backups_archive'])) || (isset($_POST['myrepono_backups_unarchive'])) || (isset($_POST['myrepono_backups_remove']))) {

			$input_backups = $_POST['myrepono_backups'];
			if (!is_array($input_backups)) {
				$input_backups = array($input_backups);
			}
			$input_backups_count = count($input_backups);

			for ($i=0; $i<$input_backups_count; $i++) {

				$input_backups[$i] = explode('/', $input_backups[$i]);

				if ((isset($input_backups[$i][1])) && (is_numeric($input_backups[$i][1])) && (is_numeric($input_backups[$i][0]))) {

					$input_domain_id = $input_backups[$i][0];
					$input_backup_id = $input_backups[$i][1];

					if ($init_request===false) {
						$init_request = array();
					}

					if (isset($_POST['myrepono_backups_archive'])) {

						$init_request['domain-backups-archive'][$input_domain_id][$input_backup_id] = '';

					} elseif (isset($_POST['myrepono_backups_unarchive'])) {

						$init_request['domain-backups-unarchive'][$input_domain_id][$input_backup_id] = '';

					} elseif (isset($_POST['myrepono_backups_remove'])) {

						$init_request['domain-backups-delete'][$input_domain_id][$input_backup_id] = '';

					}

					if (!isset($init_request['domain-backups'][$input_domain_id])) {
						$init_request['domain-backups'][$input_domain_id] = '';
					}
				}
			}
		}
	} elseif ((isset($_POST['myrepono_backups_note'])) && (isset($_POST['myr_domain'])) && (is_numeric($_POST['myr_domain']))) {

		$input_note_id = false;
		$input_domain_id = $_POST['myr_domain'];
		$input_notes = array_keys($_POST);
		$input_notes_count = count($input_notes);

		for ($i=0; $i<$input_notes_count; $i++) {
			if ((substr($input_notes[$i],0,14)=='myrepono_note_') && (is_numeric(substr($input_notes[$i],14)))) {
				$input_note_id = substr($input_notes[$i],14);
			}
		}

		if (($input_note_id!==false) && (isset($_POST['myrepono_note_'.$input_note_id]))) {

			$input_note = preg_replace("/[^A-Za-z0-9 \,\.\-\:\;\/\(\)\&\=\@\'\%_\#]/", '', strip_tags($_POST['myrepono_note_'.$input_note_id]));
			$input_note = substr(strip_tags($input_note),0,201);

			$init_request['domain-backups-note'][$input_domain_id][$input_note_id] = $input_note;

			if (!isset($init_request['domain-backups'][$input_domain_id])) {
				$init_request['domain-backups'][$input_domain_id] = '';
			}
		}
	}

	$output = myrepono_plugin_init('backups', $init_request);

	if ($output===false) {

		$icon_url = $myrepono['plugin']['url'].'img/icons';
		$flag_url = $myrepono['plugin']['url'].'img/flags';

		if (isset($_POST['myrepono_backups'])) {

			if (isset($myrepono['tmp']['papi_response']['domain-backups-archive'])) {

				$domain_backups_success = '0';
				$domain_backups_error = '0';

				$domain_keys = array_keys($myrepono['tmp']['papi_response']['domain-backups-archive']);
				$domain_count = count($domain_keys);

				for ($j=0; $j<$domain_count; $j++) {

					$input_domain_id = $domain_keys[$j];

					$domain_backups_keys = array_keys($myrepono['tmp']['papi_response']['domain-backups-archive'][$input_domain_id]);
					$domain_backups_count = count($domain_backups_keys);

					for ($i=0; $i<$domain_backups_count; $i++) {

						$domain_backups_key = $domain_backups_keys[$i];

						if (isset($myrepono['tmp']['papi_response']['domain-backups-archive'][$input_domain_id][$domain_backups_key]['success'])) {

							$domain_backups_success++;

						} elseif (isset($myrepono['tmp']['papi_response']['domain-backups-archive'][$input_domain_id][$domain_backups_key]['error'])) {

							$domain_backups_error++;

						}
					}
				}

				if ($domain_backups_success=='1') {

					$myrepono['tmp']['success'][] = $domain_backups_success.' backup was archived successfully.';

				} elseif ($domain_backups_success>0) {

					$myrepono['tmp']['success'][] = $domain_backups_success.' backups were archived successfully.';

				}

				if ($domain_backups_error=='1') {

					$myrepono['tmp']['error'][] = $domain_backups_error.' backup could not be archived.';

				} elseif ($domain_backups_error>0) {

					$myrepono['tmp']['error'][] = $domain_backups_error.' backups could not be archived.';
				}

			} elseif (isset($myrepono['tmp']['papi_response']['domain-backups-unarchive'])) {

				$domain_backups_success = '0';
				$domain_backups_error = '0';

				$domain_keys = array_keys($myrepono['tmp']['papi_response']['domain-backups-unarchive']);
				$domain_count = count($domain_keys);

				for ($j=0; $j<$domain_count; $j++) {

					$input_domain_id = $domain_keys[$j];

					$domain_backups_keys = array_keys($myrepono['tmp']['papi_response']['domain-backups-unarchive'][$input_domain_id]);
					$domain_backups_count = count($domain_backups_keys);

					for ($i=0; $i<$domain_backups_count; $i++) {

						$domain_backups_key = $domain_backups_keys[$i];

						if (isset($myrepono['tmp']['papi_response']['domain-backups-unarchive'][$input_domain_id][$domain_backups_key]['success'])) {

							$domain_backups_success++;

						} elseif (isset($myrepono['tmp']['papi_response']['domain-backups-unarchive'][$input_domain_id][$domain_backups_key]['error'])) {

							$domain_backups_error++;

						}
					}
				}

				if ($domain_backups_success=='1') {

					$myrepono['tmp']['success'][] = $domain_backups_success.' backup was un-archived successfully.';

				} elseif ($domain_backups_success>0) {

					$myrepono['tmp']['success'][] = $domain_backups_success.' backups were un-archived successfully.';

				}

				if ($domain_backups_error=='1') {

					$myrepono['tmp']['error'][] = $domain_backups_error.' backup could not be un-archived.';

				} elseif ($domain_backups_error>0) {

					$myrepono['tmp']['error'][] = $domain_backups_error.' backups could not be un-archived.';
				}

			} elseif (isset($myrepono['tmp']['papi_response']['domain-backups-delete'])) {

				$domain_backups_success = '0';
				$domain_backups_error = '0';

				$domain_keys = array_keys($myrepono['tmp']['papi_response']['domain-backups-delete']);
				$domain_count = count($domain_keys);

				for ($j=0; $j<$domain_count; $j++) {

					$input_domain_id = $domain_keys[$j];

					$domain_backups_keys = array_keys($myrepono['tmp']['papi_response']['domain-backups-delete'][$input_domain_id]);
					$domain_backups_count = count($domain_backups_keys);

					for ($i=0; $i<$domain_backups_count; $i++) {

						$domain_backups_key = $domain_backups_keys[$i];

						if (isset($myrepono['tmp']['papi_response']['domain-backups-delete'][$input_domain_id][$domain_backups_key]['success'])) {

							$domain_backups_success++;

						} elseif (isset($myrepono['tmp']['papi_response']['domain-backups-delete'][$input_domain_id][$domain_backups_key]['error'])) {

							$domain_backups_error++;

						}
					}
				}

				if ($domain_backups_success=='1') {

					$myrepono['tmp']['success'][] = $domain_backups_success.' backup was removed successfully.';

				} elseif ($domain_backups_success>0) {

					$myrepono['tmp']['success'][] = $domain_backups_success.' backups were removed successfully.';

				}

				if ($domain_backups_error=='1') {

					$myrepono['tmp']['error'][] = $domain_backups_error.' backup could not be removed.';

				} elseif ($domain_backups_error>0) {

					$myrepono['tmp']['error'][] = $domain_backups_error.' backups could not be removed.';
				}
			}
		}

		$response = myrepono_response('success', $myrepono['tmp']['success'], $response);
		$response = myrepono_response('error', $myrepono['tmp']['error'], $response);
		$response = myrepono_response('critical', $myrepono['tmp']['critical'], $response);

		$myrepono_plugin_home_queue = '';
		$myrepono_plugin_home_domain_name = '';
		if (isset($myrepono['papi']['domain'])) {

			$myrepono_domain = $myrepono['papi']['domain'];
			$myrepono_queue_domain = $myrepono_domain;

			if (isset($_GET['myrepono_queue_now'])) {
				$myrepono_queue_domain = $_GET['myrepono_queue_now'];
			} elseif (isset($_GET['myrepono_queue_refresh'])) {
				$myrepono_queue_domain = $_GET['myrepono_queue_refresh'];
			}

			$myrepono_plugin_home_queue = myrepono_plugin_home_queue($myrepono_queue_domain);

			if ((isset($myrepono['domain'][$myrepono_domain]['name'])) && ($myrepono['domain'][$myrepono_domain]['name']!='')) {
				$myrepono_plugin_home_domain_name = $myrepono['domain'][$myrepono_domain]['name'];
			} elseif ((isset($myrepono['domain'][$myrepono_domain]['url'])) && ($myrepono['domain'][$myrepono_domain]['url']!='')) {
				$myrepono_plugin_home_domain_name = myrepono_url_to_name($myrepono['domain'][$myrepono_domain]['url']);
			}
		}

		$backups = '';
		$total_backups = '0';
		$backups_url = admin_url('admin.php?page=myrepono');

		$backups_buttons = '';
		$backups_cols = '6';
		$backups_rows = '10';

		if (myrepono_wordpress_version()>3.2) {
			if ((function_exists('get_current_user_id')) && (function_exists('get_current_screen')) && (function_exists('get_user_meta'))) {

				$user = get_current_user_id();
				$screen = get_current_screen();
				$option = $screen->get_option('per_page', 'option');

				$backups_rows = get_user_meta($user, $option, true);
				if (!is_numeric($backups_rows)) {

					$backups_rows = '10';

				} elseif ($backups_rows<1) {

					$backups_rows = '1';

				}
			}
		}

		$backups_navigation = '';
		$backups_buttons = '';

		$backups_page = '1';
		if ((isset($_GET['myr_page'])) && (is_numeric($_GET['myr_page']))) {
			$backups_page = myrepono_clean($_GET['myr_page']);
		} elseif ((isset($_POST['myr_page'])) && (is_numeric($_POST['myr_page']))) {
			$backups_page = myrepono_clean($_POST['myr_page']);
		}
		$backups_rows_start = '0';
		if (($backups_page - 1)>0) {
			$backups_rows_start = ($backups_page - 1) * $backups_rows;
		}

		$backups_note  = '';
		if ((isset($_GET['myrepono_note'])) && (is_numeric($_GET['myrepono_note']))) {
			$backups_note = myrepono_clean($_GET['myrepono_note']);
		}

		if (isset($myrepono['papi']['permissions']['backup'])) {

			$backups_array = array();

			$backups_select_domain = '';
			if ((isset($_GET['myr_backups_domain'])) && (is_numeric($_GET['myr_backups_domain']))) {
				$backups_select_domain = myrepono_clean($_GET['myr_backups_domain']);
				if (!isset($myrepono['domain'][$backups_select_domain]['name'])) {
					$backups_select_domain = '';
				}
			}
			if ($backups_select_domain=='') {
				$backups_select_domain = $myrepono['papi']['domain'];
			}

			$domains_array = array(
				'0' => $backups_select_domain
			);
			$domains_count = count($domains_array);

			if ((isset($myrepono['papi']['permissions']['queue'])) && ($backups_select_domain==$myrepono_domain)) {

				$backups_buttons = '<input class="button" value="Archive" name="myrepono_backups_archive" type="submit" /> &nbsp;<input class="button" value="Un-Archive" name="myrepono_backups_unarchive" type="submit" /> &nbsp;<input class="button" value="Remove" name="myrepono_backups_remove" type="submit" />';
				$backups_cols = '7';

			}

			if ($domains_count>0) {

				for ($j=0; $j<$domains_count; $j++) {

					$domains_key = $domains_array[$j];

					if ((isset($myrepono['domain'][$domains_key]['backups'])) && (is_array($myrepono['domain'][$domains_key]['backups']))) {

						$total_backups = count($myrepono['domain'][$domains_key]['backups']);

						if ($total_backups>0) {

							$backups_keys = array_keys($myrepono['domain'][$domains_key]['backups']);

							if (!isset($myrepono['papi']['data']['locations'])) {
								$myrepono['papi']['data']['locations'] = array();
							}

							$location_keys = array_keys($myrepono['papi']['data']['locations']);
							$location_count = count($location_keys);

							for ($i=0; $i<$total_backups; $i++) {

								$backup_id = $backups_keys[$i];

								if (isset($myrepono['domain'][$domains_key]['backups'][$backup_id]['time'])) {

									$backup_time = myrepono_time_offset($myrepono['domain'][$domains_key]['backups'][$backup_id]['time']);

									$backup_timestamp = date('H:i', $backup_time);

									$backup_icon = 'server_compressed.png';
									$backup_icon_txt = 'Backup';
									if ($myrepono['domain'][$domains_key]['backups'][$backup_id]['archived']=='1') {
										$backup_icon = 'server_save.png';
										$backup_icon_txt = 'Archived Backup';
									}

									$backup_location = 'Unknown';
									$backup_location_img = '';

									$location_key = $myrepono['domain'][$domains_key]['backups'][$backup_id]['location'];

									if (isset($myrepono['papi']['data']['locations'][$location_key]['location'])) {

										$backup_location = $myrepono['papi']['data']['locations'][$location_key]['location'];
										$backup_location_img = '<img src="'.$flag_url.'/'.$myrepono['papi']['data']['locations'][$location_key]['flag'].'.png" width="16" height="11" alt="" style="position:relative;top:1px;padding-right:1px;" title="Backups stored in $backup_location" /> ';

									}

									$backup_filesize = myrepono_filesize($myrepono['domain'][$domains_key]['backups'][$backup_id]['size']);

									$backup_note = strip_tags($myrepono['domain'][$domains_key]['backups'][$backup_id]['note']);

									if (!isset($backups_array[$backup_time])) {
										$backups_array[$backup_time] = '';
									}

									$backup_domain_id = $domains_key;
									if (isset($myrepono['domain'][$domains_key]['backups'][$backup_id]['domain'])) {
										$backup_domain_id = $myrepono['domain'][$domains_key]['backups'][$backup_id]['domain'];
									}
									$backup_domain_name = $myrepono_plugin_home_domain_name;
									if (isset($myrepono['domain'][$backup_domain_id]['name'])) {
										$backup_domain_name = $myrepono['domain'][$backup_domain_id]['name'];
									} elseif (isset($myrepono['domain'][$myrepono_domain]['settings'][$backup_domain_id]['name'])) {
										$backup_domain_name = $myrepono['domain'][$myrepono_domain]['settings'][$backup_domain_id]['name'];
									} elseif (isset($myrepono['domain'][$backups_select_domain]['name'])) {
										$backup_domain_name = $myrepono['domain'][$backups_select_domain]['name'];
									} else {
										$backup_domain_name = 'Unknown';
									}

									if (strlen($backup_domain_name)>36) {
										$backup_domain_name = substr($backup_domain_name, 0, 34).'...';
									}

									$backups_colspan = '6';
									$backups_checkbox = '';
									if ((isset($myrepono['papi']['permissions']['queue'])) && ($backups_select_domain==$myrepono_domain)) {

										$backups_colspan = '7';
										$backups_checkbox = '<td><input type="checkbox" name="myrepono_backups[]" id="myrepono_backups_'.$backup_id.'" value="'.$backup_domain_id.'/'.$backup_id.'" /></td>';

									}

									$backup_add_note = '';

									if ((isset($myrepono['papi']['permissions']['queue'])) && ($backups_select_domain==$myrepono_domain)) {

										$backup_add_note = 'Add Note';

										if ($backup_note!='') {

											$backup_add_note = 'Edit Note';

										}

										$backup_add_note = '<a href="'.$backups_url.'&amp;myrepono_note='.$backup_id.'#myr_backup_'.$backup_id.'" class="button-secondary">'.$backup_add_note.'</a> ';

									}

									if ($backups_note==$backup_id) {

										$backup_add_note = '';

									}

									$backups_array[$backup_time] .= <<<END
	<tr%row_colour%>
		$backups_checkbox
		<td><a name="myr_backup_$backup_id"></a><img src="$icon_url/$backup_icon" width="16" height="16" alt="$backup_icon_txt" title="$backup_icon_txt" /></td>
		<td nowrap><b style="font-size:13px;">$backup_timestamp</b></td>
		<td nowrap>$backup_domain_name</td>
		<td nowrap>$backup_location_img$backup_location</td>
		<td nowrap>$backup_filesize</td>
		<td nowrap>$backup_add_note<a href="https://myRepono.com/my/backups/view/$backup_id/" class="button-secondary" target="new">View</a> <a href="https://myRepono.com/my/backups/download/$backup_id/" class="button-secondary" target="new">Download</a> <a href="https://myRepono.com/my/backups/restore/$backup_id/" class="button-secondary" target="new">Restore</a></td>
	</tr>

END;

									if ((isset($myrepono['papi']['permissions']['queue'])) && ($backups_select_domain==$myrepono_domain) && ($backups_note==$backup_id)) {

										$backups_colspan--;

										$backup_note_chars = 200 - strlen($backup_note);

										$backups_array[$backup_time] .= <<<END
	<tr%row_colour% style="padding-top:0px;">
		<td style="padding-top:0px;"></td><td style="padding-top:0px;font-size:11px;" colspan="$backups_colspan"><textarea name="myrepono_note_$backup_id" id="myrepono_note_$backup_id" rows="2" cols="40" placeholder="Enter Notes..." onkeydown="myrepono_text_counter('$backup_id')" onkeyup="myrepono_text_counter('$backup_id')" style="width:100%;">$backup_note</textarea><br /><span id="myrepono_note_length_$backup_id">$backup_note_chars</span> characters remaining.<input class="button" value="Save Note" name="myrepono_backups_note" type="submit" style="float:right;" /></td>
	</tr>

END;

									} elseif ($backup_note!='') {

										$backups_colspan--;

										$backups_array[$backup_time] .= <<<END
	<tr%row_colour% style="padding-top:0px;">
		<td style="padding-top:0px;"></td><td style="padding-top:0px;font-size:11px;" colspan="$backups_colspan">Note: <em>$backup_note</em></td>
	</tr>

END;

									}

								}
							}
						}
					}
				}

				$backups_array_keys = array_keys($backups_array);
				$backups_array_count = count($backups_array_keys);

				while (($backups_rows_start>0) && ($backups_rows_start>$backups_array_count)) {

					$backups_rows_start = $backups_rows_start - $backups_rows;
					$backups_page--;

				}

				rsort($backups_array_keys);

				$backups = array();

				$row_colour = '';

				for ($i=0; $i<$backups_array_count; $i++) {

					if (($i>=$backups_rows_start) && ($i<($backups_rows_start+$backups_rows))) {

						$backups_array_key = $backups_array_keys[$i];

						$backups_date = date('D jS F Y', $backups_array_key);

						if (!isset($backups[$backups_date])) {

							//$backups_time_ago = myrepono_time_ago($backups_array_key, 1).' ago';

							$backups[$backups_date] = <<<END
	   <tr>
		 <td colspan="$backups_cols"><b style="font-size:14px;"><i>$backups_date</i></b></td>
	   </tr>

END;
						}

						if ($row_colour=='') {
							$row_colour = ' class="alternate"';
						} else {
							$row_colour = '';
						}

						$backups[$backups_date] .= str_replace('%row_colour%', $row_colour, $backups_array[$backups_array_key]);

					}
				}

				$backups = implode("\n", $backups);

			}

			$backups_select = '';

			if ((isset($myrepono['papi']['permissions']['domain'])) && (isset($myrepono['papi']['domains']))) {

				$domains_keys = array_keys($myrepono['papi']['domains']);
				$domains_count = count($domains_keys);
				$domains_match_count = '0';

				for ($i=0; $i<$domains_count; $i++) {

					$domains_key = $domains_keys[$i];

					if (isset($myrepono['domain'][$domains_key]['name'])) {

						if ((isset($myrepono['domain'][$domains_key]['parent_domain'])) && ($myrepono['domain'][$domains_key]['parent_domain']!='0')) {
						} else {

							$backups_select .= '<option value="'.$domains_key.'"';

							if ($domains_key==$backups_select_domain) {
								$backups_select .= ' selected="selected"';
							}

							$domains_match_count++;

							$backups_select .= '>'.htmlentities($myrepono['domain'][$domains_key]['name']).'&nbsp; </option>';

						}
					}
				}

				if ($domains_match_count<=1) {
					$backups_select = '';
				}
			}

			if ($backups_select=='') {

				$backups_navigation_top = '<h3>Your Backups</h3>';

			} else {

				$backups_navigation_top = '<table cellpadding="0" cellspacing="0" border="0"><tr><td><h3>Your Backups for&nbsp;</h3></td><td><form action="'.admin_url('admin.php').'" method="GET" name="myrepono_backups_domain"><input type="hidden" name="page" value="myrepono"><select name="myr_backups_domain" style="font-size:12px;" onchange="myrepono_backups_domain.submit();"><option value="">Domain</option>'.$backups_select.'</select><noscript>&nbsp;<input type="submit" value="Go" style="font-size:11px;line-height:10px;"></noscript></form></td></tr></table>';

			}

			$backups_navigation_bottom = '';

			$backups_checkbox = '';

			if ($backups=='') {

				$backups_buttons = '';
				$backups_cols = '6';

				$backups = <<<END
   <tr>
     <td colspan="$backups_cols" align="center">&nbsp;<br /<b>No backups currently stored.</b><br />&nbsp;</td>
   </tr>

END;

			} else {

				if ((isset($myrepono['papi']['permissions']['queue'])) && ($backups_select_domain==$myrepono_domain)) {
					$backups_checkbox = '<td scope="col" class="manage-column check-column" width="16"><input type="checkbox" name="myrepono_backups_all" onclick="myrepono_select_all(\'backups\', this.checked);" /></td>';
				}

				$backups_total_pages = '1';

				$backups_array_count_s = 's';
				if ($backups_array_count=='1') {
					$backups_array_count_s = '';
				}

				if ($backups_array_count>0) {

					$backups_total_pages = ceil($backups_array_count / $backups_rows);

					if ($backups_total_pages<1) {
						$backups_total_pages = '1';
					}
				}

				$args = array(
					'base' => $backups_url.'&myr_backups_domain='.$backups_select_domain.'&myr_page=%#%',
					'format' => '',
					'total' => $backups_total_pages,
					'current' => $backups_page,
					'show_all' => false,
					'end_size' => 1,
					'mid_size' => 1,
					'prev_next' => true,
					'prev_text' => __('&laquo; Previous'),
					'next_text' => __('Next &raquo;'),
					'type' => 'plain',
					'add_args' => false,
					'add_fragment' => ''
				);

				$backups_navigation_top = '<div class="tablenav top"><div class="alignleft">'.$backups_navigation_top.'</div><div class="tablenav-pages"><span class="displaying-num">'.$backups_array_count.' backup'.$backups_array_count_s.'</span><span class="pagination-links">'.paginate_links($args).'</span></div></div>';

				$backups_navigation_bottom = '<div class="tablenav bottom"><div class="alignleft actions">'.$backups_buttons.'</div><div class="tablenav-pages"><span class="displaying-num">'.$backups_array_count.' backup'.$backups_array_count_s.'</span><span class="pagination-links">'.paginate_links($args).'</span></div></div>';

			}

			$backups = <<<END

<br class="clear" />

<div id="col-container">
	<div class="col-wrap">

		$backups_navigation_top
		<form action="$backups_url" method="POST">
		<input type="hidden" name="myr_page" value="$backups_page">
		<input type="hidden" name="myr_domain" value="$backups_select_domain">
		<table class="widefat">
		<thead>
			<tr>
				$backups_checkbox
				<td scope="col" class="manage-column" width="16"></td>
				<td scope="col" class="manage-column" width="120">Time</td>
				<td scope="col" class="manage-column" width="200">Profile Name</td>
				<td scope="col" class="manage-column" width="160">Location</td>
				<td scope="col" class="manage-column">Size</td>
				<td scope="col" class="manage-column" width="200">Actions</td>
			</tr>
		</thead>
		<tbody>
		$backups
		</tbody>
		</table>
		$backups_navigation_bottom
		</form>

	</div>
</div>

<script type="text/javascript">
function myrepono_text_counter(id) {
if (document.getElementById('myrepono_note_' + id).value.length > 200) {
	document.getElementById('myrepono_note_' + id).value = document.getElementById('myrepono_note_' + id).value.substring(0, 200);
} else {
	document.getElementById('myrepono_note_length_' + id).innerHTML = 200 - document.getElementById('myrepono_note_' + id).value.length;
}
}
</script>

END;

		} else {

			$backups = myrepono_plugin_permissions();

		}


		$output = <<<END
$myrepono_plugin_home_queue

$response

$backups

END;

	}

	myrepono_plugin_output('backups', $output);

}


function myrepono_plugin_home_queue_func($domain_id, $full = '1') {

	global $myrepono;

	$icon_url = $myrepono['plugin']['url'].'img/icons';

	$queue_output = '';

	$parent_domain_id = $domain_id;
	if (isset($myrepono['domain'][$domain_id]['parent_domain'])) {
		$parent_domain_id = $myrepono['domain'][$domain_id]['parent_domain'];
		if ($parent_domain_id=='0') {
			$parent_domain_id = $domain_id;
		}
	} elseif (isset($myrepono['papi']['domain'])) {
		$papi_domain = $myrepono['papi']['domain'];
		if (isset($myrepono['domain'][$papi_domain]['settings'][$domain_id]['name'])) {
			$parent_domain_id = $papi_domain;
		}
	}

	if ((isset($myrepono['domain'][$domain_id]['queue']['status'])) && (isset($myrepono['domain'][$domain_id]['queue']['message'])) && (isset($myrepono['papi']['permissions']['queue']))) {

		$queue_icon = '';
		$queue_message = $myrepono['domain'][$domain_id]['queue']['message'];
		$queue_time_ago = '';

		$queue_domain_name = '';
		if (isset($myrepono['domain'][$domain_id]['name'])) {

			$queue_domain_name = $myrepono['domain'][$domain_id]['name'];
			if (strlen($queue_domain_name)>26) {
				$queue_domain_name = substr($queue_domain_name, 0, 24).'...';
			}
			$queue_domain_name = '<strong>Queue for '.$queue_domain_name.':</strong>';

		} elseif (isset($myrepono['domain'][$parent_domain_id]['settings'][$domain_id]['name'])) {

			$queue_domain_name = $myrepono['domain'][$parent_domain_id]['settings'][$domain_id]['name'];
			if (strlen($queue_domain_name)>26) {
				$queue_domain_name = substr($queue_domain_name, 0, 24).'...';
			}
			$queue_domain_name = '<strong>Queue for '.$queue_domain_name.':</strong>';

		}

		if ($queue_domain_name!='') {

			$queue_domain_profiles = '';

			if (isset($myrepono['domain'][$parent_domain_id]['settings'])) {

				$queue_domain_profile_keys = array_keys($myrepono['domain'][$parent_domain_id]['settings']);
				$queue_domain_profile_count = count($queue_domain_profile_keys);

				if ($queue_domain_profile_count>1) {

					for ($i=0; $i<$queue_domain_profile_count; $i++) {

						$queue_domain_profile_key = $queue_domain_profile_keys[$i];
						$queue_domain_profile_name = '';

						if (isset($myrepono['domain'][$queue_domain_profile_key]['name'])) {

							$queue_domain_profile_name = $myrepono['domain'][$queue_domain_profile_key]['name'];

						} elseif (isset($myrepono['domain'][$parent_domain_id]['settings'][$queue_domain_profile_key]['name'])) {

							$queue_domain_profile_name = $myrepono['domain'][$parent_domain_id]['settings'][$queue_domain_profile_key]['name'];

						}

						if (strlen($queue_domain_profile_name)>26) {
							$queue_domain_profile_name = substr($queue_domain_profile_name, 0, 24).'...';
						}

						if ($queue_domain_profile_name=='') {
							$queue_domain_profile_name = 'Profile '.($i+1);
						}

						if ($queue_domain_profile_name!='') {

							$queue_domain_profiles .= '<option value="'.$queue_domain_profile_key.'"';

							if ($queue_domain_profile_key==$domain_id) {
								$queue_domain_profiles .= ' selected="selected"';
							}

							$queue_domain_profiles .= '>'.htmlentities($queue_domain_profile_name).'&nbsp;</option>';

						}
					}

					if ($queue_domain_profiles!='') {
						$queue_domain_name = '<strong>Queue for:&nbsp;</strong><form action="'.admin_url('admin.php').'" method="GET"><select name="myrepono_queue_refresh" style="font-size:12px;" onchange="myrepono_ajax_queue(this.value, \'refresh\');return false;"><option value="">Profile Name</option>'.$queue_domain_profiles.'</select><noscript>&nbsp;<input type="hidden" name="page" value="myrepono"><input type="submit" value="Go" style="font-size:11px;line-height:10px;"></noscript></form>';
					}
				}
			}
		} else {

			$queue_domain_name = '<strong>Queue:</strong>';

		}

		$queue_now_url = admin_url('admin.php?page=myrepono&myrepono_queue_now='.$domain_id);
		$queue_refresh_url = admin_url('admin.php?page=myrepono&myrepono_queue_refresh='.$domain_id);
		$queue_now = "&nbsp;&nbsp;<a href=\"$queue_now_url\" id=\"myrepono-queue-backup-now\" onclick=\"myrepono_ajax_queue('$domain_id', 'now');return false;\" class=\"button-primary\">Backup Now!</a>";
		$queue_refresh = "<br /><small><a href=\"$queue_refresh_url\" onclick=\"myrepono_ajax_queue('$domain_id', 'refresh');return false;\"><img src=\"$icon_url/arrow_refresh_small_grey.png\" width=\"12\" height=\"12\" alt=\"Refresh Queue\" title=\"Refresh Queue\" border=\"0\" />Refresh</a></small>";
		$queue_now_separator = "<td width=\"1\" bgcolor=\"#e3e3e3\"><!-- --></td>\n";

		if ($queue_message!='') {

			if (isset($myrepono['domain'][$domain_id]['queue']['time'])) {

				$queue_time_ago = myrepono_time_ago(myrepono_time_offset($myrepono['domain'][$domain_id]['queue']['time'])).' ago';

			}

			if ($myrepono['domain'][$domain_id]['queue']['status']=='1') {

				$queue_icon = 'accept.png';
				$queue_icon_alt = 'Successful';

			} elseif ($myrepono['domain'][$domain_id]['queue']['status']=='2') {

				$queue_icon = 'control_play_blue.png';
				$queue_icon_alt = 'Processing Now';
				$queue_now = '';
				$queue_now_separator = '';

			} elseif ($myrepono['domain'][$domain_id]['queue']['status']=='0') {

				$queue_icon = 'exclamation.png';
				$queue_icon_alt = 'Failed';

			} elseif ($myrepono['domain'][$domain_id]['queue']['status']=='3') {

				$queue_icon = 'control_pause.png';
				$queue_icon_alt = 'Pending';
				$queue_now = '';
				$queue_now_separator = '';
				$queue_time_ago = '';
				//$queue_refresh = '';

			}

			$queue_message = str_replace("<br>&nbsp;<br>", "<br />", $queue_message);

			if ($queue_icon!='') {

				$queue_icon = "<img src=\"$icon_url/".$queue_icon."\" width=\"14\" height=\"14\" alt=\"".$queue_icon_alt."\" title=\"".$queue_icon_alt."\" />&nbsp;";

				$queue_message = '<div>'.$queue_icon.$queue_message.'</div>';

				if ($queue_refresh!='') {
					$queue_message .= '<em>'.$queue_time_ago.'</em>';
				}

				$queue_output = <<<END
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="10%" nowrap="nowrap" valign="top">$queue_domain_name&nbsp;&nbsp;$queue_refresh</td>
	<td width="1" bgcolor="#e3e3e3"><!-- --></td>
	<td>$queue_message</td>
	$queue_now_separator
	<td width="100" nowrap="nowrap" align="right">$queue_now</td>
</tr>
</table>
END;

				if ($full=='1') {

					$queue_output = <<<END
<div id="myrepono_backups_queue" class="myrepono_backups_queue">
<div id="myrepono_backups_queue_content" class="myrepono_backups_queue_content">
$queue_output
</div>
<div id="myrepono_backups_queue_loading" class="myrepono_backups_queue_loading"><img src="$icon_url/loading.gif" width="14" height="14" alt="Loading..." title="Loading..." /> <strong>Please wait a moment while we check your queue...</strong></div>
</div>
END;

				}
			}
		}
	}

	return $queue_output;

}


function myrepono_plugin_home_queue_ajax_func() {

	global $myrepono;

	if ((isset($_POST['myrepono_domain_id'])) && (is_numeric($_POST['myrepono_domain_id'])) && (isset($_POST['myrepono_queue']))) {

		$myrepono_domain_id = $_POST['myrepono_domain_id'];

		$init_request = array(
			'domain-queue' => array(
				$myrepono_domain_id => ''
			)
		);

		if ($_POST['myrepono_queue']=='now') {

			$init_request = array(
				'domain-backups-now' => array(
					$myrepono_domain_id => ''
				),
				'domain-queue' => array(
					$myrepono_domain_id => ''
				)
			);

		}

		$output = myrepono_plugin_init('backups', $init_request);

		if ($output===false) {

			$queue = myrepono_plugin_home_queue($myrepono_domain_id, 0);

			if ($queue=='') {

				$icon_url = $myrepono['plugin']['url'].'img/icons';

				$queue = '<img src="'.$icon_url.'/error.png" width="14" height="14" alt="Error" title="Error" /> <strong>Your queue information could not be retrieved.</strong>';

			}

			print $queue;

		}
	}

	die();

}


?>