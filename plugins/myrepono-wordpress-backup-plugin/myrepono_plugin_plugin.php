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


function myrepono_plugin_plugin_func() {

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

	$output_left = '';
	$output_right = '';

	if (isset($_POST['myrepono_reset'])) {

		$papi_request = array();
		$plugin_request_domain = '';

		if ((isset($_POST['myrepono_reset_domain'])) && (is_numeric($_POST['myrepono_reset_domain']))) {

			$plugin_request_domain = $_POST['myrepono_reset_domain'];

			$papi_request['domain-settings-update'][$plugin_request_domain] = array(
				'active' => '0'
			);

		}

		$papi_request['papi-disconnect'] = '';

		$plugin_reset = '0';

		if ($papi_response = myrepono_connect($papi_request)) {

			if (isset($papi_response['papi-disconnect']['success'])) {

				$plugin_reset = '1';

				if ($plugin_request_domain!='') {
					if (isset($papi_response['domain-settings-update'][$plugin_request_domain]['success'])) {

						$plugin_reset = '2';

					}
				}

			}
		}

		myrepono_delete_option();

		$myrepono['papi']['connect']['error'] = '0';

		if ($plugin_reset=='2') {

			$myrepono['tmp']['success'][] = 'You have successfully reset your plugin and the associated domain configuration has been successfully paused to prevent further backups from being processed.  Please note, your domain configuration will remain configured under your myRepono.com account.';

		} else {

			$myrepono['tmp']['success'][] = 'You have successfully reset your plugin.  Please note, your domain configuration will remain configured under your myRepono.com account, if the backup status is active then backups will continue to be processed until the backup status is paused.';

		}
	}

	$refresh_cache = "0";

	if (isset($_POST['myrepono_refresh'])) {

		$refresh_cache = "3";

	}

	$output = myrepono_plugin_init('plugin', $init_request, $refresh_cache);

	if ($output===false) {

		$plugin_url = admin_url('admin.php?page=myrepono-plugin');
		$icon_url = $myrepono['plugin']['url'].'img/icons';
		$htaccess_file = $myrepono['plugin']['path'].'api/.htaccess';

		if (isset($_POST['myrepono_refresh'])) {

			if ($myrepono['papi']['connect']['error']=='0') {

				$myrepono['tmp']['success'][] = 'You have successfully refreshed the plugin cache.';

			} else {

				$myrepono['tmp']['error'][] = 'Your plugin cache could not be refreshed, please verify your plugin is connected correctly.';

			}
		}

		if (isset($_POST['myrepono_api'])) {

			if (isset($myrepono['papi']['domain'])) {

				$domain_id = $myrepono['papi']['domain'];

				$papi_request = array();
				$papi_request['domain-api'][$domain_id] = '';

				if ($papi_response = myrepono_connect($papi_request)) {

					if (isset($papi_response['domain'][$domain_id]['api']['source'])) {

						if ($myrepono_save_api = myrepono_save_api($myrepono['plugin']['path'].'api/myrepono.php', $papi_response['domain'][$domain_id]['api']['source'])) {

							$myrepono['tmp']['success'][] = 'Your myRepono API has been re-installed successfully!';

						} else {

							$myrepono['tmp']['error'][] = "Your myRepono API could not be re-installed, most likely due to a file permissions issue.<br /><em>Please ensure your myRepono API directory is writable (e.g. it has it's permissions/CHMOD set to 755 or 777).  If problems persist, please create a blank text file called 'myrepono.php' within your API directory with it's permissions/CHMOD set to 777, then refresh this page.<br />Your myRepono API directory is located at:</em><br /><code>".$myrepono['plugin']['path']."api/</code><br />Note, you can upload the blank text file and update your file/directory permissions using FTP or an online file manager.<br /><em>If problems persist, please contact support.</em>";

						}
					} else {

						$myrepono['tmp']['error'][] = 'Your myRepono API could not be retrieved for re-installation.';

					}

					unset($papi_response);

				} else {

					$myrepono['tmp']['error'][] = 'Your myRepono API could not be retrieved for re-installation due to a connection error.';
					$myrepono['papi']['connect']['error']++;

				}


				unset($papi_request);

			} else {

				$myrepono['tmp']['error'][] = 'Your myRepono API could not be retrieved for re-installation.';

			}
		}

		if (isset($_POST['myrepono_htaccess'])) {

			if (file_exists($htaccess_file)) {

				unlink($htaccess_file);

				if (file_exists($htaccess_file)) {

					$myrepono['tmp']['error'][] = 'Unable to remove \'.htaccess\' file within API directory.';

				} else {

					$myrepono['tmp']['success'][] = 'API \'.htaccess\' file has been removed successfully!';

				}
			} else {

				$fh = @fopen($htaccess_file, 'w');

				if ($fh) {

					fwrite($fh, "RewriteEngine off\n<Files myrepono.php>\nallow from all\n</Files>");
					fclose($fh);

					$myrepono['tmp']['success'][] = 'API \'.htaccess\' file has been created successfully!';

				} else {

					$myrepono['tmp']['error'][] = 'Unable to create \'.htaccess\' file within API directory.';

				}
			}
		}

		$permissions = array(
			'backup' => array(
				'name' => 'View Backup List',
				'desc' => 'Allow access to a list of backup timestamps for this domain.'
			),
			'queue' => array(
				'name' => 'Manage Backups',
				'desc' => 'Allow access to archive and delete backups, to edit backup notes, and to provoke new backups.'
			),
			'setting' => array(
				'name' => 'Change Settings',
				'desc' => 'Allow access to change domain settings.'
			),
			'select' => array(
				'name' => 'Select Data',
				'desc' => 'Allow access to change which files and databases are backed up.'
			),
			'balance' => array(
				'name' => 'View Balance &amp; Top-Up',
				'desc' => 'Allow access to view and top-up account balance.'
			),
			'domain' => array(
				'name' => 'View Other Domains',
				'desc' => 'Allow access to a list of backup timestamps for other domains under this account.'
			)
		);
		$permissions_keys = array_keys($permissions);
		$plugin_permissions = '';

		for ($i=0; $i<6; $i++) {

			$permissions_key = $permissions_keys[$i];

			$permissions_txt = 'Not Allowed';
			$permissions_icon = 'cross';
			if (isset($myrepono['papi']['permissions'][$permissions_key])) {
				$permissions_icon = 'accept';
				$permissions_txt = 'Allowed';
			}

			$plugin_permissions .= '<p><img src="'.$icon_url.'/'.$permissions_icon.'.png" width="16" height="16" alt="'.$permissions_txt.'" title="'.$permissions_txt.'" border="0" style="position:relative;top:2px;" /> <b>'.$permissions[$permissions_key]['name'].'</b><br /><small>'.$permissions[$permissions_key]['desc'].'</small></p>';

		}

		$plugins_section = "'Plugins'";

		if (isset($myrepono['papi']['permissions']['balance'])) {

			$plugins_section = '<a href="http://myRepono.com/my/plugins/" target="new"><b>Plugins</b></a>';

		}

		$output_left .= <<<END

		<h3>Plugin Permissions</h3>
		<p>This plugin has been assigned the following permissions which control what actions this plugin may perform, and what account data this plugin may access.  Your plugin permissions can be updated via the $plugins_section section of your myRepono.com account.

		$plugin_permissions
		<br class="clear" />

END;

		if ((isset($myrepono['papi']['key'])) && ($myrepono['papi']['key']!='')) {

			$output_left .= '<h3>Plugin Connection</h3><p>Your \'Connect Plugin\' abbreviated key is:<br /><code>'.substr($myrepono['papi']['key'],0,4).'-'.substr($myrepono['papi']['key'],4,4).'</code></p>';

			$plugin_domain = 'Unknown';
			if (isset($myrepono['papi']['domain'])) {
				$plugin_domain = $myrepono['papi']['domain'];
				if (isset($myrepono['domain'][$plugin_domain]['name'])) {
					$plugin_domain = $myrepono['domain'][$plugin_domain]['name'].' (ID: '.$myrepono['papi']['domain'].')';
				} else {
					$plugin_domain = 'ID: '.$myrepono['papi']['domain'];
				}
			}

			$output_left .= '<p>Your plugin is connected to the following domain configuration:<br /><code>'.$plugin_domain.'</code><p>';

			if ((isset($myrepono['papi']['connect']['method'])) && (isset($myrepono['papi']['connect']['protocol'])) && (isset($myrepono['papi']['connect']['put']))) {

				$protocol = 'Unknown';

				if ($myrepono['papi']['connect']['protocol']=='0') {

					$protocol = 'HTTPS';

				} elseif ($myrepono['papi']['connect']['protocol']=='1') {

					$protocol = 'HTTP';

				}

				$method = 'Unknown';

				if ($myrepono['papi']['connect']['method']=='0') {

					$method = 'via PHP file_get_contents()';

				} elseif ($myrepono['papi']['connect']['method']=='1') {

					$method = 'via PHP CURL';

				} elseif ($myrepono['papi']['connect']['method']=='2') {

					$method = 'via PHP CURL (using Emulation Library)';

				}

				$put = 'Unknown';

				if ($myrepono['papi']['connect']['put']=='1') {

					$put = 'Supported';

				} elseif ($myrepono['papi']['connect']['put']=='2') {

					$put = 'Not Supported';

				} elseif ($myrepono['papi']['connect']['put']=='0') {

					$put = 'Not Yet Tested';

				}

				if (($protocol!='Unknown') || ($socket!='Unknown')) {

					$output_left .= '<p>Your plugin is connecting to myRepono.com using the following method:<br /><code>'.$protocol.' '.$method.' (PUT Requests are '.$put.')</code></p>';

				}
			}
		}

		if (file_exists($myrepono['plugin']['path'].'api/myrepono.php')) {

			$output_left .= '<br class="clear" /><h3>myRepono API</h3><p>Your myRepono API is located at the following URL:<br /><code>'.$myrepono['plugin']['url'].'api/myrepono.php</code><p><p>The file system path to your myRepono API is:<br /><code>'.$myrepono['plugin']['path'].'api/myrepono.php</code></p>';

		} else {

			$output_left .= '<br class="clear" /><h3>myRepono API</h3><p><b>Your myRepono API does not currently exist!</b></p><p>Your myRepono API should be located at the following URL:<br /><code>'.$myrepono['plugin']['url'].'api/myrepono.php</code><p><p>The file system path to this URL is:<br /><code>'.$myrepono['plugin']['path'].'api/myrepono.php</code></p>';

		}

		$output_left .= <<<END
		<br class="clear" />
		<h3>Help &amp; Documentation</h3>
		<p>For help using the myRepono WordPress Backup Plugin, please select the 'Help' tab shown in the top right of the page.</p>
END;

		$domain_id = '';
		if (isset($myrepono['papi']['domain'])) {

			$domain_id = $myrepono['papi']['domain'];

		}

		$status_box = <<<END
<p>The myRepono WordPress Backup Plugin will show a status box in your WordPress administration panel header, this status box is currently enabled.  To disable the status box please select the button below.</p>
<form action="$plugin_url" method="POST">
<p><input type="submit" name="myrepono_status_box" id="myrepono_status_box" class="button" value="Disable Status Box" /></p>
</form>
END;

		if ($myrepono['plugin']['status_box']=='0') {

			$status_box = <<<END
<p>The myRepono WordPress Backup Plugin will show a status box in your WordPress administration panel header, this status box is currently disabled.  To enable the status box please select the button below.</p>
<form action="$plugin_url" method="POST">
<p><input type="submit" name="myrepono_status_box" id="myrepono_status_box" class="button" value="Enable Status Box" /></p>
</form>
END;

		}

		if (file_exists($htaccess_file)) {

			$output_htaccess = <<<END

			<table class="widefat">
			<thead>
				<tr>
					<th scope="col">Remove API .htaccess</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p>A '.htaccess' file currently exists within your API directory, if you experience API connection issues please try selecting the 'Remove .htaccess File' button.</p>
						<form action="$plugin_url" method="POST">
						<p><input type="submit" name="myrepono_htaccess" id="myrepono_htaccess" class="button" value="Remove .htaccess File" /></p>
						</form>
					</td>
				</tr>
			</tbody>
			</table>
			<br />
END;

		} else {

			$output_htaccess = <<<END

			<table class="widefat">
			<thead>
				<tr>
					<th scope="col">Create API .htaccess</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p>If you are using security restrictions such as those implemented with WordPress security plugins then these can prevent the myRepono system from accessing your API, in this case please select the 'Create .htaccess File' button below to disable security restrictions within your API directory which have been implented using another '.htaccess' file.  This will only affect your API directory and will not affect any other directories on your website/server.</p>
						<form action="$plugin_url" method="POST">
						<p><input type="submit" name="myrepono_htaccess" id="myrepono_htaccess" class="button" value="Create .htaccess File" /></p>
						</form>
					</td>
				</tr>
			</tbody>
			</table>
			<br />
END;

		}

		$output_right .= <<<END

			<table class="widefat">
			<thead>
				<tr>
					<th scope="col">Refresh Plugin Cache</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p>The myRepono WordPress Backup Plugin will temporarily save/cache your account data to prevent the need to retrieve the data every time you access the plugin.  The cached data will be automatically updated periodically, however if you would like to force the plugin to refresh it's cache please select the button below.</p>
						<form action="$plugin_url" method="POST">
						<p><input type="submit" name="myrepono_refresh" id="myrepono_refresh" class="button" value="Refresh Cache" /></p>
						</form>
					</td>
				</tr>
			</tbody>
			</table>
			<br />

			<table class="widefat">
			<thead>
				<tr>
					<th scope="col">Status Box</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						$status_box
					</td>
				</tr>
			</tbody>
			</table>
			<br />

			<table class="widefat">
			<thead>
				<tr>
					<th scope="col">Re-Install myRepono API</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p>When you first connect the myRepono WordPress Backup Plugin to your myRepono.com account your myRepono API will be automatically installed.  If you would like to re-install your API please select the button below.</p>
						<form action="$plugin_url" method="POST">
						<p><input type="submit" name="myrepono_api" id="myrepono_api" class="button" value="Re-Install myRepono API" /></p>
						</form>
					</td>
				</tr>
			</tbody>
			</table>
			<br />

$output_htaccess

			<table class="widefat">
			<thead>
				<tr>
					<th scope="col">Disconnect Plugin</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p>Your myRepono WordPress Backup Plugin is connected to your myRepono account and domain configuration.  To disconnect and reset the plugin please select the button below.</p>
						<form action="$plugin_url" method="POST">
						<p><input type="button" name="myrepono_reset_tmp" id="myrepono_reset_tmp" class="button" value="Disconnect Plugin" onclick="this.style.display='none';document.getElementById('myrepono_reset').style.display='inline-block';" /><input type="hidden" name="myrepono_reset_domain" value="$domain_id" /><input type="submit" name="myrepono_reset" id="myrepono_reset" class="button-primary" value="Confirm Disconnect Plugin" style="display:none;" /></p>
						</form>
					</td>
				</tr>
			</tbody>
			</table>
			<br />

END;

	}

	if ($output===false) {

		$response = myrepono_response('success', $myrepono['tmp']['success'], $response);
		$response = myrepono_response('error', $myrepono['tmp']['error'], $response);
		$response = myrepono_response('critical', $myrepono['tmp']['critical'], $response);

		$output = <<<END

$response

<br class="clear" />

<div id="col-container">

	<div id="col-right" style="width:40%;">
		<div class="col-wrap">

			$output_right

		</div>
	</div>

	<div id="col-left" style="width:60%;">
		<div class="col-wrap">

			$output_left

		</div>
	</div>
</div>

END;

	}

	myrepono_plugin_output('plugin', $output);

}


?>