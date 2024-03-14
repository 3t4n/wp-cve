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


function myrepono_plugin_settings_func() {

	global $myrepono;

	$init_request = false;

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

	if (isset($_GET['myr_profile_new'])) {
		if (is_numeric($_GET['myr_profile_new'])) {

			$profile_new = myrepono_clean($_GET['myr_profile_new']);

			if ($init_request===false) {
				$init_request = array();
			}

			$init_request['domain-settings-add'][$profile_new][0] = array(
				'active' => 0
			);

			$init_request['domain-settings'][$profile_new] = '';
			$init_request['papi-domains'] = '';

		}
	} elseif (isset($_GET['myr_profile_delete'])) {
		if (is_numeric($_GET['myr_profile_delete'])) {

			$profile_delete = myrepono_clean($_GET['myr_profile_delete']);

			if ($init_request===false) {
				$init_request = array();
			}

			$init_request['domain-settings-delete'][$profile_delete] = '';

			if (isset($_GET['myr_domain_id'])) {

				$profile_domain_id = myrepono_clean($_GET['myr_domain_id']);
				$init_request['domain-settings'][$profile_domain_id] = '';

			}
		}
	} elseif ((isset($_POST['myrepono_domain_id'])) && (is_numeric($_POST['myrepono_domain_id']))) {

		$input_domain_id = $_POST['myrepono_domain_id'];

		if ((isset($_POST['myrepono_settings'])) && (isset($_POST['myrepono_settings_id']))) {

			$update_settings = array();

			if (is_numeric($_POST['myrepono_settings_id'])) {

				$input_settings_id = myrepono_clean($_POST['myrepono_settings_id']);

				$input_settings_name = '';
				if (isset($_POST['myrepono_settings_name'])) {
					$update_settings['name'] = strip_tags($_POST['myrepono_settings_name']);
					$update_settings['name'] = str_replace("\\'", "'", $update_settings['name']);
					$update_settings['name'] = str_replace('\\"', '"', $update_settings['name']);
				}

				$input_settings_data = '';
				if (isset($_POST['myrepono_settings_data'])) {
					$input_settings_data = myrepono_clean($_POST['myrepono_settings_data']);
					if (($input_settings_data=='0') || ($input_settings_data=='1') || ($input_settings_data=='2')) {
						$update_settings['data'] = $input_settings_data;
					}
				}

				$input_settings_data_tables = '';
				if (isset($_POST['myrepono_settings_data_tables'])) {

					$input_settings_data_tables = myrepono_clean($_POST['myrepono_settings_data_tables']);
					$input_settings_data_tables = explode(',', $input_settings_data_tables);
					$input_settings_data_tables_count = count($input_settings_data_tables);
					$input_settings_data_tables_array = array();

					for ($i=0; $i<$input_settings_data_tables_count; $i++) {
						if (is_numeric($input_settings_data_tables[$i])) {

							$input_table_id = $input_settings_data_tables[$i];

							if (isset($_POST['myrepono_settings_data_tables_'.$input_table_id])) {

								$input_settings_data_tables_array[$input_table_id] = array();

								if (!is_array($_POST['myrepono_settings_data_tables_'.$input_table_id])) {
									if (is_numeric($_POST['myrepono_settings_data_tables_'.$input_table_id])) {
										$input_table_id_id = myrepono_clean($_POST['myrepono_settings_data_tables_'.$input_table_id]);
										$input_settings_data_tables_array[$input_table_id][$input_table_id_id] = '';
									} else {
										unset($input_settings_data_tables_array[$input_table_id]);
									}
								} else {
									$input_table_id_count = count($_POST['myrepono_settings_data_tables_'.$input_table_id]);
									for ($j=0; $j<$input_table_id_count; $j++) {
										if (is_numeric($_POST['myrepono_settings_data_tables_'.$input_table_id][$j])) {
											$input_table_id_id = myrepono_clean($_POST['myrepono_settings_data_tables_'.$input_table_id][$j]);
											$input_settings_data_tables_array[$input_table_id][$input_table_id_id] = '';
										}
									}
								}
							}

						}
					}

					if (count($input_settings_data_tables)>0) {

						$update_settings['data_tables'] = $input_settings_data_tables_array;

					}
				}

				$input_settings_freq = '';
				if (isset($_POST['myrepono_settings_freq'])) {
					$update_settings['frequency'] = myrepono_clean($_POST['myrepono_settings_freq']);
				}

				$input_settings_freq_days = '';
				if (isset($_POST['myrepono_settings_freq_days'])) {
					$update_settings['frequency_days'] = myrepono_clean($_POST['myrepono_settings_freq_days']);
				}

				$input_settings_store = '';
				if (isset($_POST['myrepono_settings_store'])) {
					$update_settings['store'] = myrepono_clean($_POST['myrepono_settings_store']);
				}

				$input_settings_primary_location = '';
				if (isset($_POST['myrepono_settings_primary_location'])) {
					$update_settings['primary_location'] = myrepono_clean($_POST['myrepono_settings_primary_location']);
				}

				$input_settings_mirror_location = '';
				if (isset($_POST['myrepono_settings_mirror_location'])) {
					$update_settings['mirror_location'] = myrepono_clean($_POST['myrepono_settings_mirror_location']);
				}

				$input_settings_time_start = '';
				if (isset($_POST['myrepono_settings_time_start'])) {
					$update_settings['time_filter_start'] = myrepono_clean($_POST['myrepono_settings_time_start']);
				}
				if ($update_settings['time_filter_start']<0) {
					$update_settings['time_filter_start'] = '0';
				}
				if ($update_settings['time_filter_start']>46) {
					$update_settings['time_filter_start'] = '46';
				}

				$input_settings_time_end = '';
				if (isset($_POST['myrepono_settings_time_end'])) {
					$update_settings['time_filter_end'] = myrepono_clean($_POST['myrepono_settings_time_end']);
				}
				if ($update_settings['time_filter_end']<2) {
					$update_settings['time_filter_end'] = '2';
				}
				if ($update_settings['time_filter_end']>48) {
					$update_settings['time_filter_end'] = '48';
				}

				$input_settings_active = '';
				if (isset($_POST['myrepono_settings_active'])) {
					$update_settings['active'] = myrepono_clean($_POST['myrepono_settings_active']);
				}

				if (count($update_settings)>0) {

					if ($init_request===false) {
						$init_request = array();
					}

					$init_request['domain-settings-update'][$input_settings_id] = $update_settings;

					$init_request['domain-settings'][$input_domain_id] = '';

				}
			}
		}
	}

	$output = myrepono_plugin_init('settings', $init_request);

	if (($output===false) && (isset($myrepono['papi']['domain']))) {

		$domain_id = $myrepono['papi']['domain'];

		if (isset($myrepono['domain'][$domain_id]['settings'])) {

			$icon_url = $myrepono['plugin']['url'].'img/icons';
			$flag_url = $myrepono['plugin']['url'].'img/flags';

			$profile_current = '0';
			$profiles = array();


			$profiles = $myrepono['domain'][$domain_id]['settings'];
			$profiles_keys = array_keys($profiles);
			sort($profiles_keys);
			$profiles_count = count($profiles_keys);

			if (isset($myrepono['tmp']['papi_response']['domain-settings-add'][$domain_id][0])) {

				if (isset($myrepono['tmp']['papi_response']['domain-settings-add'][$domain_id][0]['id'])) {

					$profile_current = $myrepono['tmp']['papi_response']['domain-settings-add'][$domain_id][0]['id'];

					if (isset($profiles[$profile_current])) {

						$myrepono['tmp']['success'][] = 'New domain profile added successfully!';

					} else {

						$profile_current = '0';

					}
				} else {

					$myrepono['tmp']['error'][] = 'New domain profile could not be added.';

				}
			} elseif (isset($_GET['myr_profile_delete'])) {
				if (is_numeric($_GET['myr_profile_delete'])) {

					$profile_delete = myrepono_clean($_GET['myr_profile_delete']);

					if (isset($myrepono['tmp']['papi_response']['domain-settings-delete'][$profile_delete]['success'])) {

						$myrepono['tmp']['success'][] = 'Domain profile removed successfully!';

					} elseif (isset($myrepono['tmp']['papi_response']['domain-settings-delete'][$profile_delete]['error'])) {

						$myrepono['tmp']['error'][] = 'Domain profile could not be removed.';

					}
				}
			}


			if (isset($_GET['myr_profile_id'])) {
				if (is_numeric($_GET['myr_profile_id'])) {
					$profile_current = myrepono_clean($_GET['myr_profile_id']);
					if (!isset($profiles[$profile_current])) {
						$profile_current = '0';
					}
				}
			}

			if ($profile_current=='0') {
				$profile_current = $domain_id;
				if (!isset($profiles[$profile_current])) {
					$profile_current = $profiles_keys[0];
				}
			}

			$profiles_navigation = '';
			$profiles_navigation2 = '';
			$profile_current_no = '';
			for ($i=0; $i<$profiles_count; $i++) {

				if (isset($profiles[$profiles_keys[$i]])) {

					$profiles_url = admin_url('admin.php?page=myrepono-settings&myr_profile_id='.$profiles_keys[$i]);

					$profiles_navigation_separator = '';
					if ((($i+1)<$profiles_count) || ($profiles_count<10) || ($profile_current!=$domain_id)) {
						$profiles_navigation_separator = ' |';
					}

					if ($profiles_keys[$i]==$profile_current) {

						$profiles_navigation .= '<li>&nbsp;<a href="'.$profiles_url.'"><img src="'.$icon_url.'/wrench.png" width="14" height="14" alt="" border="0" style="position:relative;top:2px;" /> <b>Profile '.($i+1).'</b></a>'.$profiles_navigation_separator.'</li>';

						$profile_current_no = ($i+1);

					} else {

						$profile_text = 'Profile ';
						if ($profiles_count>5) {
							$profile_text = '';
						}

						$profiles_navigation .= '<li><a href="'.$profiles_url.'"><a href="'.$profiles_url.'"><img src="'.$icon_url.'/wrench.png" width="14" height="14" alt="" border="0" style="position:relative;top:2px;" /> '.$profile_text.($i+1).'</a>'.$profiles_navigation_separator.'</li>';

					}
				}
			}

			$profiles_navigation_add = '';

			if ($profiles_count<10) {

				$profiles_url = admin_url('admin.php?page=myrepono-settings&myr_profile_new='.$domain_id);

				$profiles_navigation .= '<li><a href="'.$profiles_url.'"><img src="'.$icon_url.'/add.png" width="14" height="14" alt="" border="0" style="position:relative;top:2px;" /> Add New Profile</a>';

				if ($profile_current!=$domain_id) {
					$profiles_navigation .= ' |';
				}

				$profiles_navigation .= '</li>';

			}

			$profiles_navigation_delete = '';

			if ($profile_current!=$domain_id) {

				$profiles_url = admin_url('admin.php?page=myrepono-settings&myr_profile_delete='.$profile_current.'&myr_domain_id='.$domain_id);

				$profiles_navigation .= '<li><a href="'.$profiles_url.'"><img src="'.$icon_url.'/delete.png" width="14" height="14" alt="" border="0" style="position:relative;top:2px;" /> Delete Profile '.$profile_current_no.'</a>';

			}

			$profile_name = htmlentities($profiles[$profile_current]['name']);


			if (isset($myrepono['tmp']['papi_response']['domain-settings-update'][$profile_current])) {

				if (isset($myrepono['tmp']['papi_response']['domain-settings-update'][$profile_current]['success'])) {

					$myrepono['tmp']['success'][] = "Domain profile '$profile_name' settings updated successfully!";

				} elseif (isset($myrepono['tmp']['papi_response']['domain-settings-update'][$profile_current]['error'])) {

					$myrepono['tmp']['success'][] = "Domain profile '$profile_name' settings could not be updated.";

				}
			} elseif (isset($init_request['domain-settings-update'][$profile_current])) {

				$myrepono['tmp']['error'][] = 'Domain profile \''.$profile_name.'\' settings could not be updated.  Please <a href="https://myRepono.com/my/" target="new">log-in to your myRepono.com account</a> and proceed to the \'Domains\' -&gt; \'Settings\' section to manage your settings.';

			}

			$response = myrepono_response('success', $myrepono['tmp']['success'], $response);
			$response = myrepono_response('error', $myrepono['tmp']['error'], $response);
			$response = myrepono_response('critical', $myrepono['tmp']['critical'], $response);

			$profile_data_selected0 = '';
			$profile_data_selected1 = '';
			$profile_data_selected2 = '';
			$profile_data_tables_display = 'none';
			if ($profiles[$profile_current]['data']=='1') {
				$profile_data_selected1 = ' selected="selected"';
			} elseif ($profiles[$profile_current]['data']=='2') {
				$profile_data_selected2 = ' selected="selected"';
				$profile_data_tables_display = 'block';
			} else {
				$profile_data_selected0 = ' selected="selected"';
			}

			$profile_data_tables_select = '';
			$profile_data_tables_ids = array();
			if (!is_array($myrepono['domain'][$domain_id]['dbs'])) {
				$myrepono['domain'][$domain_id]['dbs'] = array();
			}
			$databases_keys = array_keys($myrepono['domain'][$domain_id]['dbs']);
			$databases_count = count($databases_keys);
			$tables_count = '0';
			$profile_data_tables_keys = implode(',', $databases_keys);

			if (isset($myrepono['domain'][$domain_id]['settings'][$profile_current]['data_tables'])) {
				$settings_data_tables_new = array();
				$settings_data_tables = explode('|', $myrepono['domain'][$domain_id]['settings'][$profile_current]['data_tables']);
				$settings_data_tables_count = count($settings_data_tables);
				for ($i=0; $i<$settings_data_tables_count; $i++) {
					$settings_data_tables[$i] = explode(':', $settings_data_tables[$i]);
					$settings_data_tables_key = $settings_data_tables[$i][0];
					$settings_data_tables_value = '';
					if (isset($settings_data_tables[$i][1])) {
						$settings_data_tables_value = $settings_data_tables[$i][1];
					}
					$settings_data_tables_new[$settings_data_tables_key][$settings_data_tables_value] = '';
				}
				$myrepono['domain'][$domain_id]['settings'][$profile_current]['data_tables'] = $settings_data_tables_new;
			} else {
				$myrepono['domain'][$domain_id]['settings'][$profile_current]['data_tables'] = array();
			}

			for ($i=0; $i<$databases_count; $i++) {

				$databases_key = $databases_keys[$i];
				$profile_data_tables_select_display = 'none';
				$profile_data_tables_select_img = 'collapsed';

				if ($i==0) {
					$profile_data_tables_select_display = 'block';
					$profile_data_tables_select_img = 'expanded';
				}

				if (isset($myrepono['domain'][$domain_id]['dbs'][$databases_key]['name'])) {

					$profile_data_tables_select_tables = '';
					$profile_data_tables_select_tables_checked = ' checked="checked"';
					$databases_tables_count = '0';

					if (isset($myrepono['domain'][$domain_id]['dbs'][$databases_key]['tables'])) {

						$databases_tables_keys = array_keys($myrepono['domain'][$domain_id]['dbs'][$databases_key]['tables']);
						$databases_tables_count = count($databases_tables_keys);

						$tables_count += $databases_tables_count;

						for ($j=0; $j<$databases_tables_count; $j++) {

							$databases_tables_key = $databases_tables_keys[$j];

							$profile_data_tables_select_tables .= '<input type="checkbox" name="myrepono_settings_data_tables_'.$databases_key.'[]" id="myrepono_settings_data_tables_'.$databases_key.'_'.$databases_tables_key.'" value="'.$databases_tables_key.'"';

							if (isset($myrepono['domain'][$domain_id]['settings'][$profile_current]['data_tables'][$databases_key][$databases_tables_key])) {
								$profile_data_tables_select_tables .= ' checked="checked"';
							} else {
								$profile_data_tables_select_tables_checked = '';
							}

							$profile_data_tables_select_tables .= ' />&nbsp; '.$myrepono['domain'][$domain_id]['dbs'][$databases_key]['tables'][$databases_tables_key]['name'].'<br />';

						}
					}

					if ($profile_data_tables_select_tables=='') {

						$databases_url = admin_url('admin.php?page=myrepono-databases');

						$profile_data_tables_select_tables = "<i>Database '".$myrepono['domain'][$domain_id]['dbs'][$databases_key]['name']."' table names not cached, please <a href=\"$databases_url\">edit database</a> to refresh cache.</i>";
						$profile_data_tables_select_tables_checked = '';
					}

					if (($i<5) && ($profile_data_tables_select_tables_checked!='')) {
						$profile_data_tables_select_display = 'block';
						$profile_data_tables_select_img = 'expanded';
					}

					$profile_data_tables_select .= '<input type="checkbox" name="myrepono_settings_data_tables_all_'.$databases_key.'" id="myrepono_settings_data_tables_all_'.$databases_key.'" onclick="myrepono_select_all('."'settings_data_tables_$databases_key'".',this.checked);" value="'.$databases_tables_count.'"'.$profile_data_tables_select_tables_checked.' />&nbsp; <img src="'.$icon_url.'/'.$profile_data_tables_select_img.'.gif" alt="" id="myrepono_settings_data_tables_img_'.$databases_key.'" height="9" width="9" border="0" style="position:relative;top:2px;" onclick="myrepono_settings_data_tables_select('."'$databases_key'".');" /> <a href="javascript:;" onclick="myrepono_settings_data_tables_select('."'$databases_key'".');"><b>'.$myrepono['domain'][$domain_id]['dbs'][$databases_key]['name'].'</b></a><br /><div id="myrepono_settings_data_tables_div_'.$databases_key.'" class="myrepono_settings_data_tables_div" style="display:'.$profile_data_tables_select_display.';">'.$profile_data_tables_select_tables.'</div>';

				}
			}

			if ($profile_data_tables_select=='') {
				$profile_data_tables_select = '<i>No databases added.</i>';
			}

			$tables_error = '';

			if (($tables_count>1024) || ($databases_count>16)) {

				$tables_error = '<div class="myrepono_error_small"><img src="'.$icon_url.'/exclamation.png" width="14" height="14" alt="Error" title="Error" /><span>Due to the quantity of database tables which you have selected for backup you may experience difficulty managing your table selections using this plugin.  If you experience any difficulties please <a href="https://myRepono.com/my/" target="new">log-in to your myRepono.com account</a> and proceed to the \'Domains\' -&gt; \'Settings\' section to manage your table selections.</span></div>';

			}

			$profile_freq_selected1 = '';
			$profile_freq_selected2 = '';
			$profile_freq_selected3 = '';
			$profile_freq_selected4 = '';
			$profile_freq_selected5 = '';
			$profile_freq_selected6 = '';
			$profile_freq_days_display = 'none';

			if ($profiles[$profile_current]['frequency']=='1') {
				$profile_freq_selected1 = ' selected="selected"';
			} elseif ($profiles[$profile_current]['frequency']=='2') {
				$profile_freq_selected2 = ' selected="selected"';
			} elseif ($profiles[$profile_current]['frequency']=='3') {
				$profile_freq_selected3 = ' selected="selected"';
			} elseif ($profiles[$profile_current]['frequency']=='4') {
				$profile_freq_selected4 = ' selected="selected"';
			} elseif ($profiles[$profile_current]['frequency']=='5') {
				$profile_freq_selected5 = ' selected="selected"';
			} elseif ($profiles[$profile_current]['frequency']=='6') {
				$profile_freq_selected6 = ' selected="selected"';
				$profile_freq_days_display = 'block';
			}

			$profile_frequency_days_select = '';
			for ($i=2; $i<31; $i++) {

				$profile_frequency_days_select .= '<option value="'.$i.'"';

				if ($i==$profiles[$profile_current]['frequency_days']) {
					$profile_frequency_days_select .= ' selected="selected"';
				}

				$profile_frequency_days_select .= '>'.$i.'&nbsp; </option>';

			}

			$profile_store_select = '';
			for ($i=1; $i<51; $i++) {

				$profile_store_select .= '<option value="'.$i.'"';

				if ($i==$profiles[$profile_current]['store']) {
					$profile_store_select .= ' selected="selected"';
				}

				$profile_store_select .= '>'.$i.'&nbsp; </option>';

			}

			$store_days_values = array('90','120','180','365','730','1095','1825','10000');
			for ($i=0; $i<8; $i++) {

				$profile_store_select .= '<option value="'.$store_days_values[$i].'"';

				if ($store_days_values[$i]==$profiles[$profile_current]['store']) {
					$profile_store_select .= ' selected="selected"';
				}

				$profile_store_select .= '>'.number_format($store_days_values[$i]).'&nbsp; </option>';

			}

			$profile_time_start_select = '';
			for ($i=0; $i<49; $i++) {

				$profile_time = ($i / 2);
				$profile_time = explode('.',$profile_time);
				if (isset($profile_time[1])) {
					$profile_time[1] = '30';
				} else {
					$profile_time[1] = '00';
				}
				if (isset($profile_time[0])) {

					if (strlen($profile_time[0])=='1') {
						$profile_time[0] = '0'.$profile_time[0];
					}
					$profile_time = implode(':',$profile_time);

					$profile_time_start_select .= '<option value="'.$i.'"';

					if ($i==$profiles[$profile_current]['time_filter_start']) {
						$profile_time_start_select .= ' selected="selected"';
					}

					$profile_time_start_select .= '>'.$profile_time.'&nbsp; </option>';

				}
			}

			$profile_time_end_select = '';
			for ($i=0; $i<49; $i++) {

				$profile_time = ($i / 2);
				$profile_time = explode('.',$profile_time);
				if (isset($profile_time[1])) {
					$profile_time[1] = '30';
				} else {
					$profile_time[1] = '00';
				}
				if (isset($profile_time[0])) {

					if (strlen($profile_time[0])=='1') {
						$profile_time[0] = '0'.$profile_time[0];
					}
					$profile_time = implode(':',$profile_time);

					$profile_time_end_select .= '<option value="'.$i.'"';

					if ($i==$profiles[$profile_current]['time_filter_end']) {
						$profile_time_end_select .= ' selected="selected"';
					}

					$profile_time_end_select .= '>'.$profile_time.'&nbsp; </option>';

				}
			}

			$profile_primary_location_id = '1';
			$profile_primary_location_select = '';
			$profile_mirror_location_select = '';

			if (isset($myrepono['papi']['data']['locations'])) {

				$locations_keys = array_keys($myrepono['papi']['data']['locations']);
				$locations_count = count($locations_keys);

				for ($i=0; $i<$locations_count; $i++) {

					$locations_key = $locations_keys[$i];

					if (isset($myrepono['papi']['data']['locations'][$locations_key]['location'])) {

						$profile_primary_location_select .= '<input type="radio" name="myrepono_settings_primary_location" value="'.$locations_key.'"';

						if ($locations_key==$profiles[$profile_current]['primary_location']) {

							$profile_primary_location_select .= ' checked="checked"';
							$profile_primary_location_id = $locations_key;

						}

						if ($locations_count>1) {

							$profile_primary_location_select .= ' onclick="myrepono_settings_location_select(this.value);"';

							if ($locations_key==$profiles[$profile_current]['primary_location']) {

								$profile_mirror_location_select .= '<div id="myrepono_settings_mirror_location_div_'.$locations_key.'" style="display:none;">';

							} else {

								$profile_mirror_location_select .= '<div id="myrepono_settings_mirror_location_div_'.$locations_key.'">';

							}

							$profile_mirror_location_select .= '<input type="checkbox" name="myrepono_settings_mirror_location" id="myrepono_settings_mirror_location_'.$locations_key.'" value="'.$locations_key.'"';

							if ($locations_key==$profiles[$profile_current]['mirror_location']) {

								$profile_mirror_location_select .= ' checked="checked"';

							}

							$mirror_flag = '';
							if (isset($myrepono['papi']['data']['locations'][$locations_key]['flag'])) {
								$mirror_flag = '<img src="'.$flag_url.'/'.$myrepono['papi']['data']['locations'][$locations_key]['flag'].'.png" alt="'.$myrepono['papi']['data']['locations'][$locations_key]['location'].'" hspace="2" style="position:relative;top:1px;" />&nbsp;';
							}

							$profile_mirror_location_select .= ' /> '.$mirror_flag.$myrepono['papi']['data']['locations'][$locations_key]['location'].'</div>';

						}

						$primary_flag = '';
						if (isset($myrepono['papi']['data']['locations'][$locations_key]['flag'])) {
							$primary_flag = '<img src="'.$flag_url.'/'.$myrepono['papi']['data']['locations'][$locations_key]['flag'].'.png" alt="'.$myrepono['papi']['data']['locations'][$locations_key]['location'].'" hspace="2" style="position:relative;top:1px;" />&nbsp;';
						}

						$profile_primary_location_select .= ' /> '.$primary_flag.$myrepono['papi']['data']['locations'][$locations_key]['location'];

						if (($i+1)<$locations_count) {
							$profile_primary_location_select .= '<br />';
						}
					}
				}
			}

			if ($profile_mirror_location_select=='') {
				$profile_mirror_location_select = '<small><i>None available.</i></small>';
			}

			$profile_active_selected0 = '';
			$profile_active_selected1 = '';

			if ($profiles[$profile_current]['active']=='1') {
				$profile_active_selected1 = ' selected="selected"';
			} else {
				$profile_active_selected0 = ' selected="selected"';
			}


			$settings_url = admin_url('admin.php?page=myrepono-settings&myr_profile_id='.$profile_current);

			$output = <<<END

$response

<br class="clear" />

<div id="col-container">

	<div class="col-wrap">

		<ul class="subsubsub">$profiles_navigation</ul>

		<form action="$settings_url" method="POST">
		<input type="hidden" name="myrepono_domain_id" value="$domain_id" />
		<input type="hidden" name="myrepono_settings_id" value="$profile_current" />
		<input type="hidden" name="myrepono_settings_data_tables" value="$profile_data_tables_keys" />
		<table class="form-table">

		<tr valign="top">
			<th scope="row"><label for="myrepono_settings_name">Profile Name:</label></th>
			<td><input name="myrepono_settings_name" type="text" id="myrepono_settings_name" value="$profile_name" class="regular-text" />
			<p class="description">By default your profile name is your domain name, this profile name can be changed to help you distinguish between your different profiles.</p></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="myrepono_settings_data">Backup:</label></th>
			<td><select name="myrepono_settings_data" id="myrepono_settings_data" class="regular-text" onchange="myrepono_settings_data_select(this.value);">
			<option value="0"$profile_data_selected0>Files &amp; Databases&nbsp; </option>
			<option value="1"$profile_data_selected1>Files</option>
			<option value="2"$profile_data_selected2>Databases</option>
			</select><br /><div id="myrepono_settings_data_tables_div" style="display:$profile_data_tables_display;">$tables_error<small>Select Database Tables:</small><br />$profile_data_tables_select</div>
			<p class="description">The backup setting enables you to select what data should be backed up, enabling you to create profiles to backup specific database tables, or all your files.</p></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="myrepono_settings_freq">Backup Frequency:</label></th>
			<td><select name="myrepono_settings_freq" id="myrepono_settings_freq" class="regular-text" onchange="myrepono_settings_freq_select(this.value);">
			<option value="1"$profile_freq_selected1>Hourly</option>
			<option value="2"$profile_freq_selected2>Twice Daily</option>
			<option value="3"$profile_freq_selected3>Daily</option>
			<option value="4"$profile_freq_selected4>Weekly</option>
			<option value="5"$profile_freq_selected5>Monthly</option>
			<option value="6"$profile_freq_selected6>Every X Days&nbsp; </option>
			</select><br /><div id="myrepono_settings_freq_days_div" style="display:$profile_freq_days_display;">Every <select name="myrepono_settings_freq_days" id="myrepono_settings_freq_days" class="regular-text">$profile_frequency_days_select</select> Days</div>
			<p class="description">The backup frequency setting allows you to control how often your data will be backed up.</p></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="myrepono_settings_store">Stored Backups:</label></th>
			<td><select name="myrepono_settings_store" id="myrepono_settings_store" class="regular-text">
			$profile_store_select
			</select>
			<p class="description">The stored backups setting enables you to control how many backups you would like to store.</p></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="myrepono_settings_primary_location">Primary Location:</label></th>
			<td>$profile_primary_location_select
			<p class="description">The primary location controls which of our server locations should connect to your API to backup your domain.  We recommend selecting the closest server location to your server.</p></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="myrepono_settings_mirror_location">Mirror Location(s):</label></th>
			<td>$profile_mirror_location_select
			<p class="description">The mirror location controls which of our server locations should store copies of your backup made by the primary server location, this enables you to store copies of the same backup in different parts of the world.</p></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="myrepono_settings_time_start">Backup Timeframe:</label></th>
			<td><select name="myrepono_settings_time_start" id="myrepono_settings_time_start" class="regular-text">$profile_time_start_select</select> to <select name="myrepono_settings_time_end" id="myrepono_settings_time_end" class="regular-text">$profile_time_end_select</select>
			<p class="description">The backup timeframe setting allows you to control at what point in the day your backups are processed.  The minimum time difference is 1 hour, timezone is Eastern Standard Time (EST).</p></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="myrepono_settings_active">Backup Status:</label></th>
			<td><select name="myrepono_settings_active" id="myrepono_settings_active" class="regular-text">
			<option value="1"$profile_active_selected1>Active</option>
			<option value="0"$profile_active_selected0>Paused&nbsp; </option>
			</select>
			<p class="description">The status setting enables you to pause and restart your backups.</p></td>
		</tr>

		</table>

		<p class="submit"><input type="submit" name="myrepono_settings" id="myrepono_settings" class="button" value="Update Settings" /></p>

		</form>

	</div>
</div>

<script type="text/javascript">
myrepono_settings_location_id='$profile_primary_location_id';function myrepono_settings_location_select(l){document.getElementById('myrepono_settings_mirror_location_'+l).checked=false;document.getElementById('myrepono_settings_mirror_location_div_'+l).style.display='none';if(myrepono_settings_location_id!=''){document.getElementById('myrepono_settings_mirror_location_div_'+myrepono_settings_location_id).style.display='block';}myrepono_settings_location_id=l;}
</script>
END;

		} else {

			$output = <<<END

$response

<br class="clear" />

<div id="col-container">
	<div class="col-wrap">

		<b>Settings data is not currently available, please check back in a few minutes.</b>

	</div>
</div>

END;


		}
	}

	myrepono_plugin_output('settings', $output);

}


?>