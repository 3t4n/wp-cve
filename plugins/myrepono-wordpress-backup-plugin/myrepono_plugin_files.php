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


function myrepono_plugin_files_func() {

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

	if ((isset($_POST['myrepono_domain_id'])) && (is_numeric($_POST['myrepono_domain_id']))) {

		$input_domain_id = $_POST['myrepono_domain_id'];

		if (isset($_POST['myrepono_files'])) {

			$input_files = $_POST['myrepono_files'];
			if (!is_array($input_files)) {
				$input_files = array($input_files);
			}
			$input_files_count = count($input_files);

			for ($i=0; $i<$input_files_count; $i++) {

				if ((isset($input_files[$i])) && (is_numeric($input_files[$i]))) {

					if ($init_request===false) {
						$init_request = array();
					}
					$init_request['domain-files-delete'][$input_domain_id][$input_files[$i]] = '';

					if (!isset($init_request['domain-files'][$input_domain_id])) {
						$init_request['domain-files'][$input_domain_id] = '';
					}
				}
			}
		} elseif (isset($_POST['myrepono_excludes'])) {

			$input_excludes = $_POST['myrepono_excludes'];
			if (!is_array($input_excludes)) {
				$input_excludes = array($input_excludes);
			}
			$input_excludes_count = count($input_excludes);

			for ($i=0; $i<$input_excludes_count; $i++) {

				if ((isset($input_excludes[$i])) && (is_numeric($input_excludes[$i]))) {

					if ($init_request===false) {
						$init_request = array();
					}
					$init_request['domain-excludes-delete'][$input_domain_id][$input_excludes[$i]] = '';

					if (!isset($init_request['domain-excludes'][$input_domain_id])) {
						$init_request['domain-excludes'][$input_domain_id] = '';
					}
				}
			}
		} elseif (isset($_POST['myrepono_files_path'])) {

			$input_files_path = myrepono_path(trim($_POST['myrepono_files_path']));

			if ($input_files_path!='') {
				if ((file_exists($input_files_path)) && (is_readable($input_files_path))) {

					if (substr($input_files_path,-1)!='/') {
						if (is_dir($input_files_path)) {
							$input_files_path .= '/';
						}
					}

					if ($init_request===false) {
						$init_request = array();
					}
					$init_request['domain-files-add'][$input_domain_id][]['path'] = $input_files_path;

					if (!isset($init_request['domain-files'][$input_domain_id])) {
						$init_request['domain-files'][$input_domain_id] = '';
					}
				} else {

					$myrepono['tmp']['error'][] = 'The file/directory path you entered does not appear to exist or is unreadable and therefore can not be added.';

				}
			}
		} elseif (isset($_POST['myrepono_excludes_rule'])) {

			$input_excludes_rule = myrepono_path($_POST['myrepono_excludes_rule']);

			if ($input_excludes_rule!='') {

				if ($init_request===false) {
					$init_request = array();
				}
				$init_request['domain-excludes-add'][$input_domain_id][] = $input_excludes_rule;

				if (!isset($init_request['domain-excludes'][$input_domain_id])) {
					$init_request['domain-excludes'][$input_domain_id] = '';
				}
			}
		}

	}

	$output = myrepono_plugin_init('files', $init_request);

	if ($output===false) {

		$icon_url = $myrepono['plugin']['url'].'img/icons';

		if ($input_domain_id!='') {

			if (isset($myrepono['tmp']['papi_response']['domain-files-delete'][$input_domain_id])) {

				$domain_files_success = '0';
				$domain_files_error = '0';

				$domain_files_keys = array_keys($myrepono['tmp']['papi_response']['domain-files-delete'][$input_domain_id]);
				$domain_files_count = count($domain_files_keys);

				for ($i=0; $i<$domain_files_count; $i++) {

					$domain_files_key = $domain_files_keys[$i];

					if (isset($myrepono['tmp']['papi_response']['domain-files-delete'][$input_domain_id][$domain_files_key]['success'])) {

						$domain_files_success++;

					} elseif (isset($myrepono['tmp']['papi_response']['domain-files-delete'][$input_domain_id][$domain_files_key]['error'])) {

						$domain_files_error++;

					}
				}

				if ($domain_files_success=='1') {

					$myrepono['tmp']['success'][] = $domain_files_success.' file was removed successfully.';

				} elseif ($domain_files_success>0) {

					$myrepono['tmp']['success'][] = $domain_files_success.' files were removed successfully.';

				}

				if ($domain_files_error=='1') {

					$myrepono['tmp']['error'][] = $domain_files_error.' file could not be removed.';

				} elseif ($domain_files_error>0) {

					$myrepono['tmp']['error'][] = $domain_files_error.' files could not be removed.';
				}

			} elseif (isset($myrepono['tmp']['papi_response']['domain-files-add'][$input_domain_id])) {

				$domain_files_success = '0';
				$domain_files_error = '0';

				$domain_files_keys = array_keys($myrepono['tmp']['papi_response']['domain-files-add'][$input_domain_id]);
				$domain_files_count = count($domain_files_keys);

				for ($i=0; $i<$domain_files_count; $i++) {

					$domain_files_key = $domain_files_keys[$i];

					if (isset($myrepono['tmp']['papi_response']['domain-files-add'][$input_domain_id][$domain_files_key]['id'])) {

						$domain_files_success++;

					} elseif (isset($myrepono['tmp']['papi_response']['domain-files-add'][$input_domain_id][$domain_files_key]['error'])) {

						$domain_files_error++;

					}
				}

				if ($domain_files_success=='1') {

					$myrepono['tmp']['success'][] ='Your file/directory was added successfully!';

				} elseif ($domain_files_success>0) {

					$myrepono['tmp']['success'][] = $domain_files_success.' files/directories were added successfully!';

				}

				if ($domain_files_error=='1') {

					$myrepono['tmp']['error'][] = 'Your file could not be added, this may be because you have already added this file.';

				} elseif ($domain_files_error>0) {

					$myrepono['tmp']['error'][] = $domain_files_error.' files could not be added, this may be because you have already added these files.';
				}

			} elseif (isset($myrepono['tmp']['papi_response']['domain-excludes-delete'][$input_domain_id])) {

				$domain_excludes_success = '0';
				$domain_excludes_error = '0';

				$domain_excludes_keys = array_keys($myrepono['tmp']['papi_response']['domain-excludes-delete'][$input_domain_id]);
				$domain_excludes_count = count($domain_excludes_keys);

				for ($i=0; $i<$domain_excludes_count; $i++) {

					$domain_excludes_key = $domain_excludes_keys[$i];

					if (isset($myrepono['tmp']['papi_response']['domain-excludes-delete'][$input_domain_id][$domain_excludes_key]['success'])) {

						$domain_excludes_success++;

					} elseif (isset($myrepono['tmp']['papi_response']['domain-excludes-delete'][$input_domain_id][$domain_excludes_key]['error'])) {

						$domain_excludes_error++;

					}
				}

				if ($domain_excludes_success=='1') {

					$myrepono['tmp']['success'][] = $domain_excludes_success.' exclusion rule was removed successfully.';

				} elseif ($domain_excludes_success>0) {

					$myrepono['tmp']['success'][] = $domain_excludes_success.' exclusion rules were removed successfully.';

				}

				if ($domain_excludes_error=='1') {

					$myrepono['tmp']['error'][] = $domain_excludes_error.' exclusion rule could not be removed.';

				} elseif ($domain_excludes_error>0) {

					$myrepono['tmp']['error'][] = $domain_excludes_error.' exclusion rules could not be removed.';
				}

			} elseif (isset($myrepono['tmp']['papi_response']['domain-excludes-add'][$input_domain_id])) {

				$domain_excludes_success = '0';
				$domain_excludes_error = '0';

				$domain_excludes_keys = array_keys($myrepono['tmp']['papi_response']['domain-excludes-add'][$input_domain_id]);
				$domain_excludes_count = count($domain_excludes_keys);

				for ($i=0; $i<$domain_excludes_count; $i++) {

					$domain_excludes_key = $domain_excludes_keys[$i];

					if (isset($myrepono['tmp']['papi_response']['domain-excludes-add'][$input_domain_id][$domain_excludes_key]['id'])) {

						$domain_excludes_success++;

					} elseif (isset($myrepono['tmp']['papi_response']['domain-excludes-add'][$input_domain_id][$domain_excludes_key]['error'])) {

						$domain_excludes_error++;

					}
				}

				if ($domain_excludes_success=='1') {

					$myrepono['tmp']['success'][] ='Your exclusion rule was added successfully!';

				} elseif ($domain_excludes_success>0) {

					$myrepono['tmp']['success'][] = $domain_excludes_success.' exclusion rules were added successfully!';

				}

				if ($domain_excludes_error=='1') {

					$myrepono['tmp']['error'][] = 'Your exclusion rule could not be added, this may be because you have already added this rule.';

				} elseif ($domain_excludes_error>0) {

					$myrepono['tmp']['error'][] = $domain_excludes_error.' exclusion rules could not be added, this may be because you have already added this rule.';
				}

			}

		}

		$files = '';
		$files_buttons = '<p><input class="button" value="Remove" name="myrepono_files_remove" type="submit" /></p>';
		$excludes = '';
		$excludes_buttons = '<p><input class="button" value="Remove" name="myrepono_excludes_remove" type="submit" /></p>';

		$files_wordpress_found = '0';
		$files_non_existant_count = '0';

		$files_url = admin_url('admin.php?page=myrepono-files');

		if (isset($myrepono['papi']['domain'])) {

			$domain_id = $myrepono['papi']['domain'];

			if ((isset($myrepono['domain'][$domain_id]['files'])) && (is_array($myrepono['domain'][$domain_id]['files']))) {

				$files_keys = array_keys($myrepono['domain'][$domain_id]['files']);
				$files_count = count($files_keys);

				$row_colour = '';

				for ($i=0; $i<$files_count; $i++) {

					$files_key = $files_keys[$i];

					if (isset($myrepono['domain'][$domain_id]['files'][$files_key]['path'])) {

						$files_path = $myrepono['domain'][$domain_id]['files'][$files_key]['path'];
						$files_path_notes = '';

						if (substr(dirname(dirname(dirname($myrepono['plugin']['path']))).'/',0,strlen($files_path))==$files_path) {

							$files_path_notes .= '&nbsp;<span><img src="'.$icon_url.'/wordpress.png" width="14" height="14" alt="Contains WordPress" title="Contains WordPress" /> Contains WordPress</span>';
							$files_wordpress_found = '1';

						}

						if (!file_exists($files_path)) {

							$files_path_notes .= '&nbsp;<span><img src="'.$icon_url.'/error.png" width="14" height="14" alt="File may not exist!" title="File may not exist!" /> File may not exist!</span>&nbsp;';
							$files_non_existant_count++;
							$files_non_existant_fields .= '<input name="myrepono_files[]" type="hidden" value="'.$files_key.'" />';

						}

						if ($row_colour=='') {
							$row_colour = ' class="alternate"';
						} else {
							$row_colour = '';
						}

						$files .= <<<END
   <tr$row_colour>
     <td><input type="checkbox" name="myrepono_files[]" id="myrepono_files_$files_key" value="$files_key" /></td>
     <td>$files_path$files_path_notes</td>
   </tr>

END;

					}
				}
			}

			if ((isset($myrepono['domain'][$domain_id]['excludes'])) && (is_array($myrepono['domain'][$domain_id]['excludes']))) {

				$excludes_keys = array_keys($myrepono['domain'][$domain_id]['excludes']);
				$excludes_count = count($excludes_keys);

				$row_colour = '';

				for ($i=0; $i<$excludes_count; $i++) {

					$excludes_key = $excludes_keys[$i];

					if (isset($myrepono['domain'][$domain_id]['excludes'][$excludes_key]['rule'])) {

						$excludes_rule = $myrepono['domain'][$domain_id]['excludes'][$excludes_key]['rule'];

						if ($row_colour=='') {
							$row_colour = ' class="alternate"';
						} else {
							$row_colour = '';
						}

						$excludes .= <<<END
   <tr$row_colour>
     <td><input type="checkbox" name="myrepono_excludes[]" id="myrepono_excludes_$excludes_key" value="$excludes_key" /></td>
     <td>$excludes_rule</td>
   </tr>

END;

					}
				}
			}
		}

		if ($files=='') {

			$files_buttons = '';
			$files = <<<END
   <tr>
     <td colspan="2" align="center">&nbsp;<br /><b>No files selected.</b><br />&nbsp;</td>
   </tr>

END;
		}

		if ($excludes=='') {

			$excludes_buttons = '';
			$excludes = <<<END
   <tr>
     <td colspan="2" align="center">&nbsp;<br /><b>No exclusion rules added.</b><br />&nbsp;</td>
   </tr>

END;
		}

		$response = myrepono_response('success', $myrepono['tmp']['success'], $response);
		$response = myrepono_response('error', $myrepono['tmp']['error'], $response);
		$response = myrepono_response('critical', $myrepono['tmp']['critical'], $response);

		$output = <<<END

$response

<br class="clear" />

<div id="col-container">

	<div id="col-right">
		<div class="col-wrap">

			<h3>Selected Files</h3>

			<form action="$files_url" method="POST">
			<input type="hidden" name="myrepono_domain_id" value="$domain_id" />
			<table class="widefat" id="myrepono_files">
			<thead>
				<tr>
					<td scope="col" class="manage-column check-column" width="16"><input type="checkbox" name="myrepono_files_all" onclick="myrepono_select_all('files', this.checked);" /></td>
					<td scope="col" class="manage-column">File/Directory Path</td>
				</tr>
			</thead>
			<tbody>
			$files
			</tbody>
			</table>
			$files_buttons
			</form>

		</div>
	</div>

	<div id="col-left">
		<div class="col-wrap">

			<div class="form-wrap">

				<h3>Add File/Directory</h3>

				<form action="$files_url" method="POST">
				<input type="hidden" name="myrepono_domain_id" value="$domain_id" />

				<div class="form-field form-required">
					<label for="myrepono_files_path">File/Directory Path:</label>
					<input name="myrepono_files_path" id="myrepono_files_path" type="text" value="" size="40" placeholder="e.g. /home/username/public_html/" />
					<p>The full/absolute file system path to the file or directory you would like to backup.</p>
				</div>

				<p class="submit" style="padding-top:0px;"><input type="submit" name="myrepono_files_add" id="myrepono_files_add" class="button" value="Add File/Directory"  /></p>

				</form>

			</div>

		</div>
	</div>

</div>

<br class="clear" />

<div id="col-container">

	<div id="col-right">
		<div class="col-wrap">

			<h3>Exclusion Rules</h3>

			<form action="$files_url" method="POST">
			<input type="hidden" name="myrepono_domain_id" value="$domain_id" />
			<table class="widefat">
			<thead>
				<tr>
					<td scope="col" class="manage-column check-column" width="16"><input type="checkbox" name="myrepono_excludes_all" onclick="myrepono_select_all('excludes', this.checked);" /></td>
					<td scope="col" class="manage-column">Exclusion Rule</td>
				</tr>
			</thead>
			<tbody>
			$excludes
			</tbody>
			</table>
			$excludes_buttons
			</form>

		</div>
	</div>

	<div id="col-left">
		<div class="col-wrap">

			<div class="form-wrap">

				<h3>Add Exclusion Rule</h3>

				<form action="$files_url" method="POST">
				<input type="hidden" name="myrepono_domain_id" value="$domain_id" />

				<div class="form-field form-required">
					<label for="myrepono_excludes_rule">Exclusion Rule:</label>
					<input name="myrepono_excludes_rule" id="myrepono_excludes_rule" type="text" value="" size="40" placeholder="e.g. *.log or /home/username/logs/*" />
					<p>Exclusion rules allow you to define search strings which are compared against your file paths, if matched the file is excluded from your backup. Asterisk (*) wildcards can be used for partial match searches.</p>
				</div>

				<p class="submit" style="padding-top:0px;"><input type="submit" name="myrepono_excludes_add" id="myrepono_excludes_add" class="button" value="Add Exclusion Rule"  /></p>

				</form>

			</div>

		</div>
	</div>

</div>

END;

	}

	myrepono_plugin_output('files', $output);

}


?>