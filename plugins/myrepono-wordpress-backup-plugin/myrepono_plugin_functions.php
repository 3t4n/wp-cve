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


function myrepono_plugin_init($tab = '', $init_request = false, $refresh_cache = '0', $status_box = '0') {

	global $myrepono;

	if (!ini_get('safe_mode')) {
		if (function_exists('set_time_limit')) {
			@set_time_limit('300');
		}
	}
	if (function_exists('ini_set')) {
		@ini_set('mysql.connect_timeout', 180);
		@ini_set('default_socket_timeout', 30);
	}

	$output = false;

	$myrepono_plugin_url = '';
	if (strtolower(substr(WP_PLUGIN_URL,0,4))=='http') {
		$myrepono_plugin_url = WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__), '', plugin_basename(__FILE__));
	} elseif (function_exists('plugins_url')) {
		$myrepono_plugin_url = plugins_url('', __FILE__).'/';
	}

	if (strtolower(substr($myrepono_plugin_url,0,4))!='http') {
		$myrepono['tmp']['error'][] = 'Your plugin is unable to correctly detect your WordPress installation URL and therefore you may be unable to complete the \'Connect Plugin\' process.<br /><em>If problems persist, please contact support.</em>';
	}

	$myrepono_plugin_path = myrepono_path(dirname(__FILE__).'/');

	if (!file_exists($myrepono_plugin_path.'api')) {
		@mkdir($myrepono_plugin_path.'api' , 0755);
	}
	if (!file_exists($myrepono_plugin_path.'api/data')) {
		@mkdir($myrepono_plugin_path.'api/data' , 0777, true);
	} elseif (!is_writable($myrepono_plugin_path.'api/data')) {
		@chmod($myrepono_plugin_path.'api/data', 0777);
		if (!is_writable($myrepono_plugin_path.'api/data')) {
			@chmod($myrepono_plugin_path.'api/data', 0755);
			if (!is_writable($myrepono_plugin_path.'api/data')) {
				@chmod($myrepono_plugin_path.'api/data', 0666);
			}
		}
	}

	if (!file_exists($myrepono_plugin_path.'api')) {

		$myrepono['tmp']['error'][] = 'Your plugin is unable to create your myRepono API directory.  Please manually create the following directory:<br /><code>'.$myrepono_plugin_path.'api/</code><br /><em>Note, you can create the directory using FTP or an online file manager.';

	}

	if (!file_exists($myrepono_plugin_path.'api/data')) {

		$myrepono['tmp']['error'][] = 'Your plugin is unable to create your myRepono API Data directory.  Please manually create the following directory with it\'s permissions/CHMOD set to \'777\':<br /><code>'.$myrepono_plugin_path.'api/data/</code><br /><em>Note, you can create the directory and update it\'s permissions using FTP or an online file manager.';

	}

	if (isset($myrepono['tmp'])) {
		$myrepono_tmp = $myrepono['tmp'];
	}

	$api_installed = '0';

	if ($myrepono = myrepono_get_option()) {

		if (isset($myrepono_tmp)) {
			$myrepono['tmp'] = $myrepono_tmp;
		}

		if (($myrepono['plugin']['url']!=$myrepono_plugin_url) || ($myrepono['plugin']['path']!=$myrepono_plugin_path)) {

			$myrepono['tmp']['error'][] = 'Your plugin is currently installed incorrectly, this is likely due to a change in your website URL or file system path.<br /><em>Please disconnect the plugin and then repeat the \'Connect Plugin\' process.</em>';

			$myrepono['tmp']['mini_error'][] = 'Plugin Connection Error';

		} elseif (!file_exists($myrepono['plugin']['path'].'api/myrepono.php')) {

			if ((isset($myrepono['papi']['key'])) && ($myrepono['papi']['key']!='') && (isset($myrepono['papi']['domain']))) {

				$papi_domain = $myrepono['papi']['domain'];
				$papi_request = array();
				$papi_request['domain-api'][$papi_domain] = '';

				if ($papi_response = myrepono_connect($papi_request)) {

					if (isset($papi_response['domain'][$papi_domain]['api']['source'])) {

						if ($myrepono_save_api = myrepono_save_api($myrepono['plugin']['path'].'api/myrepono.php', $papi_response['domain'][$papi_domain]['api']['source'])) {

							unset($papi_response);

							$api_installed = '1';

							$myrepono['tmp']['success'][] = 'Your myRepono API was not installed correctly and has now been successfully re-installed.';

							$myrepono['tmp']['mini_success'][] = 'API Re-Installed';

						}
					}
				}

				if ($api_installed!='1') {

					$myrepono['tmp']['error'][] = "myRepono API could not be installed!<br /><em>Please ensure your myRepono API directory is writable (e.g. it has it's permissions/CHMOD set to 755 or 777), then refresh this page.  If problems persist, please create a blank text file called 'myrepono.php' within your API directory with it's permissions/CHMOD set to 777, then refresh this page.<br />Your myRepono API directory is located at:</em><br /><code>".$myrepono['plugin']['path']."api/</code><br />Note, you can upload the blank text file and update your file/directory permissions using FTP or an online file manager.<br /><em>If problems persist, please contact support.</em>";

					$myrepono['tmp']['mini_error'][] = 'API Installation Error';

				}
			}

		} elseif ((isset($myrepono['papi']['key'])) && ($myrepono['papi']['key']!='')) {

			if (file_exists($myrepono['plugin']['path'].'api/myrepono_config.php')) {

				if (!isset($myrepono['cache']['config_time'])) {
					$myrepono['cache']['config_time'] = 0;
				}

				$file_time = filemtime($myrepono['plugin']['path'].'api/myrepono_config.php');

				if ($myrepono['cache']['config_time']<$file_time) {

					$myrepono_config = base64_encode(file_get_contents($myrepono['plugin']['path'].'api/myrepono_config.php'));

					if ($myrepono_config!='') {

						$myrepono['cache']['config_time'] = $file_time;

						myrepono_update_option($myrepono);

						add_option('myrepono-plugin-config', $myrepono_config, '', 'no');

						$myrepono['tmp']['mini_success'][] = 'API Configuration Saved';

						$myrepono['tmp']['success'][] = 'API Configuration File Saved<br /><em>Your \'myrepono_config.php\' API configuration file has been saved so it can be automatically re-installed in the future, for example after upgrading this plugin or if the file is removed.</em>';

					}
				}

			} elseif ($myrepono_config = get_option('myrepono-plugin-config')) {

				$myrepono_config = base64_decode($myrepono_config);

				$fh = fopen($myrepono['plugin']['path'].'api/myrepono_config.php', 'w');

				if ($fh) {

					fwrite($fh, $myrepono_config);
					fclose($fh);

					$myrepono['cache']['config_time'] = filemtime($myrepono['plugin']['path'].'api/myrepono_config.php');

					myrepono_update_option($myrepono);

					$myrepono['tmp']['success'][] = 'API Configuration File Re-Installed<br /><em>Your \'myrepono_config.php\' API configuration file has been successfully re-installed.</em>';

					$myrepono['tmp']['mini_success'][] = 'API Configuration Updated';


				}
			}
		}

	} else {

		$myrepono = array(
			'cache' => array(),
			'plugin' => array(
				'url' => $myrepono_plugin_url,
				'path' => $myrepono_plugin_path,
				'status_box' => '1'
			),
			'papi' => array(
				'time' => '',
				'time_offset' => '',
				'key' => '',
				'password' => '',
				'token' => '',
				'secret' => '',
				'domain' => '',
				'domains' => array(),
				'connect' => array(
					'method' => '0',
					'protocol' => '0',
					'put' => '0',
					'error' => '0',
					'auth_error' => '0',
					'skip' => false
				)
			)
		);

		if (isset($myrepono_tmp)) {
			$myrepono['tmp'] = $myrepono_tmp;
		}

		myrepono_add_option($myrepono);

	}

	if ($myrepono['papi']['key']!='') {

		myrepono_plugin_data($init_request, $refresh_cache, $status_box);

	}

	if (!isset($myrepono['tmp']['success'])) {
		$myrepono['tmp']['success'] = array();
	}
	if (!isset($myrepono['tmp']['error'])) {
		$myrepono['tmp']['error'] = array();
	}
	if (!isset($myrepono['tmp']['critical'])) {
		$myrepono['tmp']['critical'] = array();
	}

	if ($myrepono['papi']['key']=='') {

		$output = myrepono_plugin_begin($tab);

	}

	if ((isset($myrepono['papi']['connect']['skip'])) && ($myrepono['papi']['connect']['skip']<time()-180)) {
		$myrepono['papi']['connect']['skip'] = false;
	}

	if ((isset($myrepono['papi']['connect']['skip'])) && ($myrepono['papi']['connect']['skip']>time()-180) && (!isset($_POST['myrepono_refresh']))) {

		$plugin_url = admin_url('admin.php?page=myrepono-plugin');

		$myrepono['tmp']['error'][] = 'Plugin Connection Temporarily Disabled<br /><em>Your plugin\'s connections to myRepono.com to retrieve account data have been temporarily disabled due to a connection error, this is to protect your WordPress administration panel in case the myRepono system is currently unavailable as hanging connections may affect the loading of your administration panel.  Your plugin\'s connections will be re-enabled after 3 minutes.</em>';

		$myrepono['tmp']['mini_error'][] = 'Plugin Connection Disabled';

	} elseif ((isset($myrepono['papi']['connect']['auth_error'])) && ($myrepono['papi']['connect']['auth_error']>0)) {

		$plugin_url = admin_url('admin.php?page=myrepono-plugin');

		$myrepono['tmp']['error'][] = 'Plugin Connection Error<br /><em>Your plugin\'s connection to myRepono.com to retrieve account data has failed due to an authentication error.  Please proceed to the <a href="'.$plugin_url.'"><b>Plugin</b></a> section and select the \'Disconnect Plugin\' option, once done please select the \'Connect Plugin\' option to reconnect your plugin with your myRepono.com account.</em>';

		$myrepono['tmp']['mini_error'][] = 'Plugin Connection Error';

	} elseif ((isset($myrepono['papi']['connect']['error'])) && ($myrepono['papi']['connect']['error']>0)) {

		$plugin_url = admin_url('admin.php?page=myrepono-plugin');

		$myrepono['tmp']['error'][] = 'Plugin Connection Error<br /><em>Your plugin\'s connection to myRepono.com to retrieve account data has failed.  Please retry the action you are performing, if problems persist please proceed to the <a href="'.$plugin_url.'"><b>Plugin</b></a> section and select the \'Disconnect Plugin\' option, once done please select the \'Connect Plugin\' option to reconnect your plugin with your myRepono.com account.</em>';

		$myrepono['tmp']['mini_error'][] = 'Plugin Connection Error';

	}

	if ($output===false) {

		if (isset($myrepono['account']['balance'])) {

			$account_balance = $myrepono['account']['balance'];
			if (!is_numeric($account_balance)) {
				$account_balance = "0.00";
			}

			$account_balance_warning = '0';
			if ($account_balance<=0) {
				$account_balance_warning = '2';
			} elseif ($account_balance<2.5) {
				$account_balance_warning = '1';
			}

			if (isset($myrepono['account']['balance-warning'])) {
				$account_balance_warning = $myrepono['account']['balance-warning'];
			}

			if ($account_balance_warning=='2') {

				$myrepono['tmp']['critical'][] = 'Your myRepono account balance has been exhausted!<br /><em>No further backups will be processed, and all stored backups will be removed.  Please <a href="https://myRepono.com/my/billing/topup/" target="new"><b>top-up your account balance</b></a> as soon as possible.</em>&nbsp; <a href="https://myRepono.com/my/billing/topup/" target="new" class="button-secondary">Top-Up Now</a>';

				$myrepono['tmp']['mini_critical'][] = 'Account Balance Exhausted';

			} elseif ($account_balance_warning=='1') {

				$myrepono['tmp']['error'][] = 'Your myRepono account balance is running low!<br /><em>To avoid disruption to your backup processing and stored backups, please <a href="https://myRepono.com/my/billing/topup/" target="new"><b>top-up your account balance</b></a> as soon as possible.</em>&nbsp; <a href="https://myRepono.com/my/billing/topup/" target="new" class="button-secondary">Top-Up Now</a>';

				if ($account_balance<1) {

					$myrepono['tmp']['mini_error'][] = 'Account Balance Low';

				}
			}

			if (isset($myrepono['papi']['data']['notices'])) {

				$notices_keys = array_keys($myrepono['papi']['data']['notices']);
				$notices_count = count($notices_keys);

				for ($i=0; $i<$notices_count; $i++) {

					$notices_key = $notices_keys[$i];

					if ((isset($myrepono['papi']['data']['notices'][$notices_key]['msg'])) & (!isset($myrepono['papi']['data']['notices'][$notices_key]['hide']))) {

						if (!isset($myrepono['papi']['data']['notices'][$notices_key]['type'])) {
							$myrepono['papi']['data']['notices'][$notices_key]['type'] = 'success';
						}
						$notice_type = $myrepono['papi']['data']['notices'][$notices_key]['type'];

						$show_notice = '1';

						if (isset($myrepono['papi']['data']['notices'][$notices_key]['version'])) {
							if (version_compare(WP_MYREPONO_PLUGIN, $myrepono['papi']['data']['notices'][$notices_key]['version'], '>')) {
								$show_notice = '0';
							}
						}

						if ($show_notice=='1') {

							$myrepono['tmp'][$notice_type][] = $myrepono['papi']['data']['notices'][$notices_key]['msg'];

						}
					}
				}
			}
		}

		if ((isset($myrepono['papi']['domain'])) && (isset($myrepono['papi']['permissions']['select']))) {

			$domain_id = $myrepono['papi']['domain'];

			$files_url = admin_url('admin.php?page=myrepono-files');
			$databases_url = admin_url('admin.php?page=myrepono-databases');

			$files_wordpress_found = '0';
			$files_non_existant_count = '0';
			$files_non_existant_fields = '';

			if ((isset($myrepono['domain'][$domain_id]['files'])) && (is_array($myrepono['domain'][$domain_id]['files']))) {

				$files_keys = array_keys($myrepono['domain'][$domain_id]['files']);
				$files_count = count($files_keys);

				for ($i=0; $i<$files_count; $i++) {

					$files_key = $files_keys[$i];

					if (isset($myrepono['domain'][$domain_id]['files'][$files_key]['path'])) {

						$files_path = $myrepono['domain'][$domain_id]['files'][$files_key]['path'];

						if (substr(dirname(dirname(dirname($myrepono['plugin']['path']))).'/',0,strlen($files_path))==$files_path) {

							$files_wordpress_found = '1';

						}

						if (!file_exists($files_path)) {

							$files_non_existant_count++;
							$files_non_existant_fields .= '<input name="myrepono_files[]" type="hidden" value="'.$files_key.'" />';

						}
					}
				}
			}


			if ((isset($_POST['myrepono_files_add'])) && ($_POST['myrepono_files_add']=='Add WordPress Directory')) {
			} elseif ($files_wordpress_found=='0') {

				$myrepono['tmp']['error'][] = 'Your WordPress directory is not currently selected for backup.&nbsp; <form action="'.$files_url.'" method="POST"><input type="hidden" name="myrepono_domain_id" value="'.$domain_id.'" /><input name="myrepono_files_path" type="hidden" value="'.dirname(dirname(dirname($myrepono['plugin']['path']))).'" /><input type="submit" name="myrepono_files_add" class="button" value="Add WordPress Directory" /></form>';

				$myrepono['tmp']['mini_error'][] = 'File Selection Error';

			}

			if ((isset($_POST['myrepono_files_remove'])) && (stristr($_POST['myrepono_files_remove'], ' Invalid File'))) {
			} elseif ($files_non_existant_count>0) {

				$files_non_existant_s = 's';
				if ($files_non_existant_count=='1') {
					$files_non_existant_s = '';
				}

				$myrepono['tmp']['error'][] = 'You have '.$files_non_existant_count.' file'.$files_non_existant_s.' selected for backup which may not exist.&nbsp; <form action="'.$files_url.'" method="POST"><input type="hidden" name="myrepono_domain_id" value="'.$domain_id.'" />'.$files_non_existant_fields.'<input type="submit" name="myrepono_files_remove" class="button" value="Remove '.$files_non_existant_count.' Invalid File'.$files_non_existant_s.'" /></form>';

				$myrepono['tmp']['mini_error'][] = 'File Selection Error';

			}

			if ((isset($_POST['myrepono_databases_add'])) && ($_POST['myrepono_databases_add']=='Add WordPress Database')) {
			} else {

				$databases_wordpress_found = '0';

				if ((isset($myrepono['domain'][$domain_id]['dbs'])) && (is_array($myrepono['domain'][$domain_id]['dbs']))) {

					$databases_keys = array_keys($myrepono['domain'][$domain_id]['dbs']);
					$databases_count = count($databases_keys);

					for ($i=0; $i<$databases_count; $i++) {

						$databases_key = $databases_keys[$i];

						if ((isset($myrepono['domain'][$domain_id]['dbs'][$databases_key]['host'])) && (isset($myrepono['domain'][$domain_id]['dbs'][$databases_key]['name'])) && (isset($myrepono['domain'][$domain_id]['dbs'][$databases_key]['user']))) {

							$databases_host = $myrepono['domain'][$domain_id]['dbs'][$databases_key]['host'];
							$databases_name = $myrepono['domain'][$domain_id]['dbs'][$databases_key]['name'];
							$databases_user = $myrepono['domain'][$domain_id]['dbs'][$databases_key]['user'];

							if (($databases_host==DB_HOST) && ($databases_name==DB_NAME) && ($databases_user==DB_USER)) {

								$databases_wordpress_found = '1';

							}
						}
					}
				}

				if ($databases_wordpress_found=='0') {

					$myrepono['tmp']['error'][] = 'Your WordPress database is not currently selected for backup.&nbsp; <form action="'.$databases_url.'" method="POST"><input type="hidden" name="myrepono_domain_id" value="'.$domain_id.'"><input name="myrepono_databases_host" type="hidden" value="'.DB_HOST.'" /><input name="myrepono_databases_name" type="hidden" value="'.DB_NAME.'" /><input name="myrepono_databases_user" type="hidden" value="'.DB_USER.'" /><input name="myrepono_databases_pass" type="hidden" value="'.DB_PASSWORD.'" /><input type="submit" name="myrepono_databases_add" class="button" value="Add WordPress Database" /></form>';

					$myrepono['tmp']['mini_error'][] = 'Database Selection Error';

				}
			}
		}

		if ((($tab=='databases') || ($tab=='files')) && (!isset($myrepono['papi']['permissions']['select']))) {

			$output = myrepono_plugin_permissions($tab);

		} elseif (($tab=='settings') && (!isset($myrepono['papi']['permissions']['setting']))) {

			$output = myrepono_plugin_permissions($tab);

		} elseif (($tab=='account') && (!isset($myrepono['papi']['permissions']['balance']))) {

			$output = myrepono_plugin_permissions($tab);

		}
	}

	return $output;

}


function myrepono_plugin_begin($tab = '') {

	global $myrepono;

	if (!isset($myrepono['tmp']['success'])) {
		$myrepono['tmp']['success'] = array();
	}
	if (!isset($myrepono['tmp']['error'])) {
		$myrepono['tmp']['error'] = array();
	}

	$init_request = false;
	$response = '';
	$output = '';

	$myr_token = '';
	if (isset($_GET['myr_token'])) {
		if ((strlen($_GET['myr_token'])=='32') && (!preg_match("/[^a-zA-Z0-9]/", $_GET['myr_token']))) {
			$myr_token = $_GET['myr_token'];
		}
	}

	$myr_cancel = '0';
	if (isset($_GET['myr_cancel'])) {
		$myr_cancel = '1';
	}

	if ($myrepono['papi']['secret']=='') {
		$myrepono['papi']['secret'] = myrepono_secret();
		myrepono_update_option($myrepono);
	}

	$myrepono_secret = $myrepono['papi']['secret'];
	$myrepono_return_url = urlencode(admin_url('admin.php?page=myrepono'));
	$myrepono_api_url =  $myrepono['plugin']['url'].'api/myrepono.php';
	$myrepono_api_url_encode = urlencode($myrepono_api_url);
	$icon_url = $myrepono['plugin']['url'].'img/icons';

	$myr_token_success = '0';

	if (($myr_token!='') && (strlen($myr_token)=='32') && (!preg_match("/[^a-zA-Z0-9]/", $myr_token))) {

		if ($myrepono_connect = myrepono_connect('', 'auth/token/'.$myr_token.'/'.$myrepono_secret.'/')) {

			if ((isset($myrepono_connect['papi_key'])) && (isset($myrepono_connect['papi_password']))) {
				if ((strlen($myrepono_connect['papi_key'])=='32') && (!preg_match("/[^a-zA-Z0-9]/", $myrepono_connect['papi_key'])) && (strlen($myrepono_connect['papi_password'])=='32') && (!preg_match("/[^a-zA-Z0-9]/", $myrepono_connect['papi_password'])) && (isset($myrepono_connect['papi_domain'])) && (is_numeric($myrepono_connect['papi_domain']))) {

					$myrepono['papi']['key'] = $myrepono_connect['papi_key'];
					$myrepono['papi']['password'] = $myrepono_connect['papi_password'];
					$myrepono['papi']['domain'] = $myrepono_connect['papi_domain'];
					$myrepono['papi']['token'] = $myr_token;

					myrepono_update_option($myrepono);

					$myr_token_success = '1';

				}
			}
		}

		if ($myr_token_success!='1') {

			$myrepono['tmp']['error'][] = 'Unable to complete \'Connect Plugin\' process due to invalid token, please restart the process by selecting the \'Connect Plugin\' button below.';

		}
	}

	if ($myr_token_success=='1') {

		$output = myrepono_plugin_begin_setup();

	} else {

		if ($myr_cancel=='1') {

			$myrepono['tmp']['error'][] = 'The \'Connect Plugin\' process has been cancelled, to restart the process please select the \'Connect Plugin\' button below.';

		}

		$output .= <<<END

<div>

	<h3>Set-Up Plugin</h3>
	<p>To setup the myRepono WordPress Backup Plugin we need to connect this plugin with your myRepono.com account, to do this we use a 'Connect Plugin' process which provides a secure and convenient method for creating a secure connection between this plugin and the myRepono.com system.</p>

	<p><b>To begin, please select the 'Connect Plugin' button below and follow the process to connect this plugin with your myRepono.com account:</b></p>

	<p><a href="https://myRepono.com/papi/1.0/auth/begin/?plugin=wordpress&secret=$myrepono_secret&return=$myrepono_return_url&api=$myrepono_api_url_encode" onclick="return myrepono_authorise(this.href);" class="button-primary">Connect Plugin</a></p>

	<p><small>This button will open a pop-up window hosted by myRepono.com, please ensure your web browser does not block the pop-up window.  After completing the 'Connect Plugin' process the pop-up window will close and this page will refresh to complete the connection process, please be patient and wait for this page to confirm the set-up process is complete.</small></small></p>

</div>

<br class="clear" />
<h3>Help &amp; Documentation</h3>
<p>For help using the myRepono WordPress Backup Plugin, please select the 'Help' tab shown in the top right of the page.</p>


END;

	}

	if ($myr_token_success!='1') {

		$plugin_url_explode = explode('/', $myrepono['plugin']['url']);
		if (isset($plugin_url_explode[2])) {
			if ((stristr($plugin_url_explode[2], '127.0.0.1')) || ($plugin_url_explode[2]=='localhost')) {

				$myrepono['tmp']['error'][] = 'Local URL Detected<br /><em>Your plugin has detected that your WordPress installation is using a local URL such as \'127.0.0.1\' or \'localhost\', therefore the myRepono system will be unable to connect to your server to backup your WordPress installation.  The myRepono WordPress Backup Plugin can only be used with WordPress installations which are installed on publicly accessible web servers.</em>';

			}
		}

		if ($myrepono_old = get_option('myrepono')) {
			if (isset($myrepono_old['myr_username'])) {

				$myrepono['tmp']['success'][] = "Upgrade Detected!<br /><em>Your plugin has detected that you were running version 1.x.x of the myRepono WordPress Backup Plugin and you have since upgraded to version 2.x.x.  Congratulations, and thank you for upgrading, we hope you will be pleased with the significant changes!<br />Due to new security measures the new plugin can not be setup automatically using your existing plugin configuration - we sincerely apologise for any inconvenience this may cause!  However, when completing the setup process you will be able to connect this plugin to your existing domain configuration so your backups and domain configuration will continue as normal and without disruption!  To begin, simply select the 'Connect Plugin' button below, then when confirming your plugin permissions you will be shown an 'Existing Domain Configurations' notification which will allow you to select your existing domain configuration to connect with this plugin.</em>";

			}
		}
	}

	$response = myrepono_response('success', $myrepono['tmp']['success'], $response);
	$response = myrepono_response('error', $myrepono['tmp']['error'], $response);
	$response = myrepono_response('critical', $myrepono['tmp']['critical'], $response);

	$output = <<<END
$response

<br class="clear" />

<div id="col-container">
	<div class="col-wrap">

		<a href="http://myRepono.com/" target="new"><img src="{$myrepono['plugin']['url']}img/myrepono_logo_grey_200.png" width="200" height="55" border="0" align="right" style="padding:2px;border:1px solid #ccc;margin:0 0 10px 10px;" alt="myRepono Website, Database &amp; WordPress Backup Service" title="myRepono Website, Database &amp; WordPress Backup Service" id="myrepono_logo" /></a>

		<h3>myRepono WordPress Backup Plugin</h3>

		<p>myRepono is a WordPress, website and mySQL database backup service.  The service enables you to backup your WordPress files and databases automatically to remote backup servers across the world, for as little as 2 cents per day.</p>

		<p>The myRepono website backup service automates the process of backing up your entire WordPress web site and database, including all post, comments and user data, and your WordPress PHP, template and plugin files.  Comprehensive backup management and restoration tools are provided via myRepono.com, giving you an independent backup management and restoration system if your WordPress installation is unavailable.</p>

		<p>myRepono is a commercial backup service which uses a pay-as-you-go balance system. Users receive \$5 USD free credit to help them get started, and with prices starting at 2 cents per day that's enough free credit to backup most WordPress installations for several months!<br />&nbsp;</p>
		<p>Useful Links: <a href="http://myRepono.com/" target="new" class="button-secondary"><b>myRepono.com</b></a> <a href="http://wordpress.org/extend/plugins/myrepono-wordpress-backup-plugin/" target="new" class="button-secondary"><b>Plugin Info</b></a> <a href="http://myRepono.com/faq/" target="new" class="button-secondary"><b>FAQ &amp; Documentation</b></a> <a href="http://myRepono.com/blog/" target="new" class="button-secondary"><b>Blog</b></a> <a href="http://twitter.com/myRepono" target="new" class="button-secondary"><b>Twitter</b></a> <a href="https://myRepono.com/contact/" target="new" class="button-secondary"><b>Contact Us</b></a></p>

		<br />
		$output

	</div>
</div>

END;


	return $output;

}


function myrepono_plugin_begin_setup() {

	global $myrepono;

	if (!isset($myrepono['tmp']['success'])) {
		$myrepono['tmp']['success'] = array();
	}
	if (!isset($myrepono['tmp']['error'])) {
		$myrepono['tmp']['error'] = array();
	}

	$papi_domain = $myrepono['papi']['domain'];

	$papi_request = array();
	$papi_request['papi-permissions'] = '';
	$papi_request['domain-files-add'][$papi_domain][0]['path'] = dirname(dirname(dirname($myrepono['plugin']['path']))).'/';

	$input_databases_added = '0';

	if ($db_test = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST)) {

		$papi_request['domain-dbs-add'][$papi_domain][0] = array(
			'host' => DB_HOST,
			'name' => DB_NAME,
			'user' => DB_USER,
			'pass' => DB_PASSWORD,
			'backup_all' => '1'
		);

		$input_databases_added = '1';

		if ($db_test_result = $db_test->get_results('show tables from `'.myrepono_escape_string(DB_NAME).'`;', ARRAY_A )) {

			$db_test_number = count($db_test_result);

			if ($db_test_number<=256) {

				for ($i=0; $i<$db_test_number; $i++) {

					$db_test_name = array_keys($db_test_result[$i]);
					$db_test_name = $db_test_result[$i][$db_test_name[0]];

					$papi_request['domain-dbs-add'][$papi_domain][0]['tables'][] = array(
						'name' => $db_test_name
					);

				}

			} else {

				$input_databases_no_cache = '1';

			}
		}
	}

	$papi_request['domain-settings-update'][$papi_domain]['active'] = '1';
	$papi_request['domain'][$papi_domain] = '';
	$papi_request['domain-api'][$papi_domain] = '';
	$papi_request['domain-queue'][$papi_domain] = '';

	if ($papi_response = myrepono_connect($papi_request)) {

		if ((isset($papi_response['papi']['permissions'])) && (isset($papi_response['domain'][$papi_domain]['api']['source']))) {

			if ($myrepono_save_api = myrepono_save_api($myrepono['plugin']['path'].'api/myrepono.php', $papi_response['domain'][$papi_domain]['api']['source'])) {

				unset($papi_response['domain'][$papi_domain]['api']);

				myrepono_connect_response_merge($papi_response);

				if ((isset($papi_response['domain-settings-update'][$papi_domain]['success'])) && ((isset($papi_response['domain-files-add'][$papi_domain][0]['id'])) || ((isset($papi_response['domain-files-add'][$papi_domain][0]['error_code'])) && ($papi_response['domain-files-add'][$papi_domain][0]['error_code']=='2'))) && ((isset($papi_response['domain-dbs-add'][$papi_domain][0]['id'])) || ((isset($papi_response['domain-dbs-add'][$papi_domain][0]['error_code'])) && ($papi_response['domain-dbs-add'][$papi_domain][0]['error_code']=='2')))) {

					if ((isset($papi_response['domain'][$papi_domain]['queue']['status'])) && (isset($papi_response['papi']['permissions']['queue'])) && ($papi_response['domain'][$papi_domain]['queue']['status']=='2')) {

						$myrepono['tmp']['success'][] = "Setup Complete!<br /><em>Your plugin has been connected to your myRepono.com account successfully!  Your first backup will be processed shortly, you can monitor the progress using the backup queue shown above.  We recommend you check your 'Files' and 'Databases' selections to ensure all your website data is selected for backup, once done please review your 'Settings' to ensure your backups are scheduled as you require.</em>";

					} else {

						$myrepono['tmp']['success'][] = "Setup Complete!<br /><em>Your plugin has been connected to your myRepono.com account successfully!  We recommend you check your 'Files' and 'Databases' selections to ensure all your website data is selected for backup, once done please review your 'Settings' to ensure your backups are scheduled as you require.</em>";

					}
				} else {

					$myrepono['tmp']['success'][] = "Setup Complete!<br /><em>Your plugin has been connected to your myRepono.com account successfully!</em>";

					if ((!isset($papi_response['papi']['permissions']['select'])) || (!isset($papi_response['papi']['permissions']['setting']))) {

						$myrepono['tmp']['error'][] = "Please verify setup!<br /><em>Due to your plugin permissions the plugin can not automatically setup your 'Files' and 'Databases' selections.  Please log-in to your myRepono.com account and review your 'Files' and 'Databases' selections to ensure all your website data is selected for backup, once done please review your 'Settings' to ensure your backups are scheduled as you require.</em>";

					} else {

						$myrepono['tmp']['error'][] = "Please verify setup!<br /><em>Your plugin has not been able to verify whether the setup process completed successfully.  Please review your 'Files' and 'Databases' selections to ensure all your website data is selected for backup, once done please review your 'Settings' to ensure your backups are scheduled as you require.</em>";

					}
				}

				if ($myrepono_old = get_option('myrepono')) {

					delete_option('myrepono');

				}

			} else {

				$myrepono['tmp']['error'][] = "myRepono API could not be installed!<br /><em>Please ensure your myRepono API directory is writable (e.g. it has it's permissions/CHMOD set to 755 or 777), then refresh this page.  If problems persist, please create a blank file called 'myrepono.php' within your API directory with it's permissions/CHMOD set to 777, then refresh this page.<br />Your API directory is located at:</em><br /><code>".$myrepono['plugin']['path']."api/</code><br /><em>If problems persist, please contact support.</em>";

			}

		} else {

			$myrepono['tmp']['error'][] = 'Unable to retrieve myRepono API for installation, please contact support.';

		}

	} else {

		$myrepono['tmp']['error'][] = 'Unable to retrieve domain configuration, please contact support.';

	}

	return;

}


function myrepono_plugin_data($init_request = false, $refresh_cache = '0', $status_box = '0') {

	global $myrepono;

	$cache_time = '180';
	$partial_cache_time = '600';
	$full_cache_time = '1800';
	$token_cache_time = '60';
	$connect_cache_time = '604800';

	$time_now = time();

	if ($refresh_cache=="0") {
		if ((!isset($myrepono['cache']['full_time'])) || (!isset($myrepono['cache']['partial_time'])) || (!isset($myrepono['cache']['time']))) {
			$refresh_cache = '3';
		} elseif ($myrepono['cache']['full_time']<($time_now-$full_cache_time)) {
			$refresh_cache = '3';
		} elseif ($myrepono['cache']['partial_time']<($time_now-$partial_cache_time)) {
			$refresh_cache = '2';
		} elseif ($myrepono['cache']['time']<($time_now-$cache_time)) {
			$refresh_cache = '1';
		}
	}

	$papi_request = false;

	if ($init_request!==false) {

		$papi_request = $init_request;
		$refresh_cache = '2';

	}

	if ($refresh_cache!='0') {

		if ($papi_request===false) {
			$papi_request = array();
		}

		if ((!isset($myrepono['papi']['permissions'])) || (!isset($myrepono['papi']['domain']))) {

			$papi_request['papi-permissions'] = '';

			if ($papi_response = myrepono_connect($papi_request)) {

				if (isset($papi_response['papi']['permissions'])) {

					myrepono_connect_response_merge($papi_response);

				}
			}
		}

		if (isset($papi_request['papi-permissions'])) {

			unset($papi_request['papi-permissions']);

			$refresh_cache = '3';

		} else {

			$papi_request['papi-permissions'] = '';

		}

		if ($refresh_cache=='3') {

			$papi_request['papi-data'] = '';

		}

		if (isset($myrepono['papi']['domain'])) {

			$papi_request_domain = $myrepono['papi']['domain'];

			$papi_request_domain_profiles = array();

			if (isset($myrepono['domain'][$papi_request_domain]['settings'])) {

				$papi_request_domain_profiles = array_keys($myrepono['domain'][$papi_request_domain]['settings']);

			}

			$papi_request_domain_profiles_count = count($papi_request_domain_profiles);

			if (($refresh_cache=='3') && (isset($myrepono['papi']['permissions']['queue'])) && (isset($myrepono['papi']['permissions']['backup'])) && (isset($myrepono['papi']['permissions']['select'])) && (isset($myrepono['papi']['permissions']['setting']))) {

				$papi_request['domain-all'][$papi_request_domain] = '';

			} else {

				if (($refresh_cache=='2') || ($refresh_cache=='3')) {

					$papi_request['domain'][$papi_request_domain] = '';

					for ($i=0; $i<$papi_request_domain_profiles_count; $i++) {

						$papi_request['domain'][$papi_request_domain_profiles[$i]] = '';

					}
				}

				if (isset($myrepono['papi']['permissions']['queue'])) {

					$papi_request['domain-queue'][$papi_request_domain] = '';

					for ($i=0; $i<$papi_request_domain_profiles_count; $i++) {

						$papi_request['domain-queue'][$papi_request_domain_profiles[$i]] = '';

					}
				}

				if (($refresh_cache=='2') || ($refresh_cache=='3')) {

					if (isset($myrepono['papi']['permissions']['backup'])) {

						$papi_request['domain-backups'][$papi_request_domain] = '';

					}
				}

				if ($refresh_cache=='3') {

					if (isset($myrepono['papi']['permissions']['select'])) {

						$papi_request['domain-files'][$papi_request_domain] = '';
						$papi_request['domain-excludes'][$papi_request_domain] = '';
						$papi_request['domain-dbs'][$papi_request_domain] = '';

					}

					$papi_request['domain-settings'][$papi_request_domain] = '';

				}
			}
		}

		if (($refresh_cache=='2') || ($refresh_cache=='3')) {

			if (isset($myrepono['papi']['permissions']['balance'])) {

				$papi_request['account'] = '';

			}
		}

		if ((($refresh_cache=='2') || ($refresh_cache=='3')) && (isset($myrepono['papi']['permissions']['domain'])) && (isset($myrepono['papi']['domains']))) {

			$papi_request_domains = $myrepono['papi']['domains'];

			if (isset($myrepono['papi']['domain'])) {

				$papi_request_domain = $myrepono['papi']['domain'];
				unset($papi_request_domains[$papi_request_domain]);

			}

			$papi_request_domains_keys = array_keys($papi_request_domains);
			$papi_request_domains_count = count($papi_request_domains_keys);

			for ($i=0; $i<$papi_request_domains_count; $i++) {

				$papi_request_domains_key = $papi_request_domains_keys[$i];

				if (is_numeric($papi_request_domains_key)) {

					$papi_request['domain'][$papi_request_domains_key] = '';

					if ((isset($myrepono['domain'][$papi_request_domains_key]['parent_domain'])) && ($myrepono['domain'][$papi_request_domains_key]['parent_domain']!='0')) {

					} elseif (isset($myrepono['domain'][$papi_request_domain]['settings'][$papi_request_domains_key])) {

					} else {

						$papi_request['domain-backups'][$papi_request_domains_key] = '';

					}
				}
			}
		}

		if ($refresh_cache=='1') {

			if (!isset($myrepono['cache']['connect_cache'])) {
				$myrepono['cache']['connect_cache'] = $time_now;
			}

			if (!isset($myrepono['cache']['token_cache'])) {
				$myrepono['cache']['token_cache'] = $time_now;
			}

			if ($myrepono['cache']['connect_cache']<$time_now-$connect_cache_time) {

				$myrepono['papi']['connect'] = array(
					'method' => '0',
					'protocol' => '0',
					'put' => '0',
					'error' => '0'
				);

				$myrepono['cache']['connect_cache'] = $time_now;

			} elseif (($myrepono['cache']['token_cache']<$time_now-$token_cache_time) && ($status_box!='1')) {

				$papi_request['papi-token'] = '';

				$myrepono['cache']['token_cache'] = $time_now;

			}
		}
	}

	if ($papi_request!==false) {

		if ($papi_response = myrepono_connect($papi_request)) {

			if ($papi_response!==false) {
				if (isset($papi_response['error_code'])) {

					if ($papi_response['error_code']=='0') {

						$papi_response = false;

						$myrepono['papi']['connect']['auth_error']++;

					} elseif ($papi_response['error_code']=='1') {

						$papi_response = false;

					}
				} elseif (isset($papi_response['domain'][$papi_request_domain]['error_code'])) {

					$papi_response = false;

				} elseif (isset($papi_response['papi-disconnect']['success'])) {

					$papi_response = false;

					$myrepono['papi']['connect']['auth_error']++;

				}
			}

			if ($papi_response!==false) {

				$myrepono['tmp']['papi_response'] = myrepono_connect_response_merge($papi_response);

				if ($refresh_cache>0) {
					$myrepono['cache']['time'] = $time_now;
					if ($refresh_cache=='2') {
						$myrepono['cache']['partial_time'] = $time_now;
					} elseif ($refresh_cache=='3') {
						$myrepono['cache']['partial_time'] = $time_now;
						$myrepono['cache']['full_time'] = $time_now;
					}
				}

				if (isset($myrepono['papi']['time'])) {

					$offset = -14400;

					if (($offset>60) || ($offset<-60)) {

						$myrepono['papi']['time_offset'] = $offset;

					}
				}

				if (($refresh_cache=='3') && (isset($myrepono['papi']['domain']))) {

					$papi_request_domain = $myrepono['papi']['domain'];

					if (isset($myrepono['domain'][$papi_request_domain]['settings'])) {

						$papi_domain_keys = array_keys($myrepono['domain'][$papi_request_domain]['settings']);
						$papi_domain_count = count($papi_domain_keys);

						if (!isset($myrepono['papi']['domains'][$papi_request_domain])) {
							$myrepono['papi']['domains'][$papi_request_domain] = '';
						}
					}
				}

			} else {

				$myrepono['papi']['connect']['error']++;

			}

		} else {

			$myrepono['papi']['connect']['error']++;

		}

		myrepono_update_option($myrepono);

	}
}


function myrepono_connect($request = array(), $request_uri = false, $timeout = 30) {

	global $myrepono;

	$update_myrepono = '0';
	$request_format = '1';

	if ($myrepono['papi']['connect']['protocol']=='0') {

		$request_url = 'https://myrepono.com/papi/1.0/';

	} else {

		$request_url = 'http://myrepono.com/papi/1.0/';

	}

	$response = false;

	$connect_file = $myrepono['plugin']['path'].'api/data/MYREPONO-PLUGIN-CONNECT-SKIP.dat';

	if (file_exists($connect_file)) {

		if ((filemtime($connect_file)>time()-180) && (!isset($_POST['myrepono_refresh']))) {

			$myrepono['papi']['connect']['skip'] = time();

			$response = myrepono_connect_response(false, $request_format);

			return $response;

		} else {

			@unlink($connect_file);

			$myrepono['papi']['connect']['skip'] = false;

		}
	}

	$fh = @fopen($connect_file, 'w');

	if ($fh) {

		fwrite($fh, time());
		fclose($fh);

	}

	if ($request_uri===false) {

		if (!isset($myrepono['papi']['key'])) {

			if (file_exists($connect_file)) {
				@unlink($connect_file);
			}

			$myrepono['papi']['connect']['skip'] = false;

			return false;

		}

		$request_url .= $myrepono['papi']['key'].'/'.$myrepono['papi']['password'].'/'.$myrepono['papi']['token'].'/';

		if (function_exists('json_encode')) {
			$request_encode = base64_encode(json_encode($request));
			$request_url .= '?json64=';
			$request_format = '0';
		} else {
			$request_encode = base64_encode(serialize($request));
			$request_url .= '?serial64=';
			$request_format = '1';
		}

		if (($myrepono['papi']['connect']['put']=='0') || ($myrepono['papi']['connect']['put']=='1')) {

			if ($request_encode!='') {

				$response = myrepono_connect_put($request_url, $request_encode, $timeout);

				if (($response===false) && ($myrepono['papi']['connect']['protocol']=='0')) {

					$myrepono['papi']['connect']['put'] = '0';
					$myrepono['papi']['connect']['protocol'] = '1';

					$response = myrepono_connect($request, false, $timeout);

					if ($response===false) {

						$myrepono['papi']['connect']['put'] = '2';
						$myrepono['papi']['connect']['protocol'] = '0';

					} else {

						$myrepono['papi']['connect']['put'] = '1';
						$myrepono['papi']['connect']['method'] = '1';

						return $response;

					}

				} elseif ($response===false) {

					$myrepono['papi']['connect']['put'] = '2';

				} else {

					$myrepono['papi']['connect']['put'] = '1';
					$myrepono['papi']['connect']['method'] = '1';

				}

				$update_myrepono = '1';

			}
		}

	} else {

		$request_encode = '';

		if (function_exists('json_encode')) {
			$request_url .= $request_uri.'?json64=';
			$request_format = '0';
		} else {
			$request_url .= $request_uri.'?serial64=';
			$request_format = '1';
		}
	}

	$request_url .= $request_encode;

	if (($response===false) || ($myrepono['papi']['connect']['put']=='2')) {

		$response = myrepono_connect_url($request_url, $timeout);

	}

	if (($response!==false) && (trim($response)=='')) {
		$response = false;
	}

	if (($response===false) && ($myrepono['papi']['connect']['protocol']=='0')) {

		$myrepono['papi']['connect']['protocol'] = '1';
		$update_myrepono = '1';

		$response = myrepono_connect_url($request_url, $timeout);

		if (trim($response)=='') {
			$response = false;
		}
	}

	if ($response===false) {

		$myrepono['papi']['connect'] = array(
			'method' => '0',
			'protocol' => '0',
			'put' => '0',
			'error' => '0'
		);
		$update_myrepono = '1';

	}

	$response = myrepono_connect_response($response, $request_format);

	if (file_exists($connect_file)) {
		@unlink($connect_file);
	}

	$myrepono['papi']['connect']['skip'] = false;

	return $response;

}


function myrepono_connect_url($request_url, $timeout = 30) {

	global $myrepono;

	$update_myrepono = '0';

	if ($myrepono['papi']['connect']['method']=='0') {

		$socket_timeout = false;
		if ((function_exists('ini_get')) && (function_exists('ini_set'))) {
			$socket_timeout = ini_get('default_socket_timeout');
			if (!is_numeric($socket_timeout)) {
				$socket_timeout = false;
			} else {
				ini_set('default_socket_timeout', $timeout);
			}
		}

		if ($response = @file_get_contents($request_url)) {

			return $response;

		}

		if ($socket_timeout!==false) {
			ini_set('default_socket_timeout', $socket_timeout);
		}

		$myrepono['papi']['connect']['method'] = '1';
		$update_myrepono = '1';

	}

	if ($myrepono['papi']['connect']['method']=='1') {

		if (function_exists('curl_init')) {

			if ($ch = @curl_init()) {

				@curl_setopt($ch, CURLOPT_URL, $request_url);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

				if (($myrepono['papi']['connect']['protocol']=='0') && (file_exists($myrepono['plugin']['path'].'api/myrepono.crt'))) {

					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					curl_setopt($ch, CURLOPT_CAINFO, $myrepono['plugin']['path'].'api/myrepono.crt');

				} else {

					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				}

				if ($response = @curl_exec($ch)) {

					curl_close($ch);

					return $response;

				}

				curl_close($ch);

			}
		}

		$myrepono['papi']['connect']['method'] = '2';
		$update_myrepono = '1';

	}

	if ($myrepono['papi']['connect']['method']=='2') {

		if (file_exists($myrepono['plugin']['path']."api/libcurlemu/libcurlemu.inc.php")) {
			require_once($myrepono['plugin']['path']."api/libcurlemu/libcurlemu.inc.php");
		}

		if (function_exists('curl_init')) {

			if ($ch = @curl_init()) {

				@curl_setopt($ch, CURLOPT_URL, $request_url);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

				if (($myrepono['papi']['connect']['protocol']=='0') && (file_exists($myrepono['plugin']['path'].'api/myrepono.crt'))) {

					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					curl_setopt($ch, CURLOPT_CAINFO, $myrepono['plugin']['path'].'api/myrepono.crt');

				} else {

					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				}

				if ($response = @curl_exec($ch)) {

					curl_close($ch);

					return $response;

				}

				curl_close($ch);

			}
		}
	}

	return false;

}


function myrepono_connect_put($request_url, $request_encode, $timeout = 30) {

	global $myrepono;

	$update_myrepono = '0';

	if (($request_encode!='') && (function_exists('curl_init'))) {

		$put_file = $myrepono['plugin']['path'].'api/data/MYREPONO-PLUGIN-PUT-'.myrepono_secret(4).'.dat';

		$fh = fopen($put_file, 'w');

		if ($fh) {

			fwrite($fh, $request_encode);
			fclose($fh);

			$request_length = filesize($put_file);

			$fh = fopen($put_file, "rb");

			if (($fh) && ($ch = @curl_init())) {

				@curl_setopt($ch, CURLOPT_URL, $request_url);
				curl_setopt($ch, CURLOPT_PUT, true);
				curl_setopt($ch, CURLOPT_INFILE, $fh);
				curl_setopt($ch, CURLOPT_READFUNCTION, 'myrepono_connect_put_curl');
				curl_setopt($ch, CURLOPT_INFILESIZE, $request_length);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

				if (($myrepono['papi']['connect']['protocol']=='0') && (file_exists($myrepono['plugin']['path'].'api/myrepono.crt'))) {

					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					curl_setopt($ch, CURLOPT_CAINFO, $myrepono['plugin']['path'].'api/myrepono.crt');

				} else {

					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				}

				if ($response = curl_exec($ch)) {

					curl_close($ch);
					fclose($fh);
					unlink($put_file);

					$myrepono['papi']['connect']['put'] = '1';
					$myrepono['papi']['connect']['method'] = '1';

					return $response;

				}

				curl_close($ch);
				fclose($fh);

			}

			unlink($put_file);

		}
	}

	$myrepono['papi']['connect']['put'] = '2';

	return false;

}


function myrepono_connect_put_curl($curl_handle, $file_handle, $read_buffer = '16384') {

	while ($file_data = fread($file_handle, $read_buffer)) {

		return $file_data;

	}
}


function myrepono_connect_response($response, $format = '0') {

	if ($response) {

		if ($format=='0') {

			$response = @base64_decode($response);
			return @json_decode($response, true);

		} elseif ($format=='1') {

			$response = @base64_decode($response);
			return @unserialize($response);

		}
	}

	return false;

}


function myrepono_connect_response_merge($response) {

	global $myrepono;

	$merge_tags = array('papi', 'domain', 'account');
	$merge_tags_count = count($merge_tags);

	for ($i=0; $i<$merge_tags_count; $i++) {
		$merge_tag = $merge_tags[$i];
		if (isset($response[$merge_tag])) {
			if (!isset($myrepono[$merge_tag])) {
				$myrepono[$merge_tag] = array();
			}
			$myrepono[$merge_tag] = myrepono_array_merge_recursive($myrepono[$merge_tag], $response[$merge_tag]);
			unset($response[$merge_tag]);
		}
	}

	return $response;

}


function myrepono_array_merge_recursive() {

	$arrays = func_get_args();
	$arrays_shift = array_shift($arrays);

	foreach ($arrays as $array) {
		reset($arrays_shift);
		while (list($key, $value) = @each($array)) {
			if (($key=='files') || ($key=='excludes') || ($key=='dbs') || ($key=='settings') || ($key=='backups') || ($key=='permissions') || ($key=='data') || ($key=='account') || ($key=='domains')) {
				$arrays_shift[$key] = array();
			}
			if (is_array($value)) {
				if ((isset($value['error_code'])) || (isset($arrays_shift[$key]['error_code']))) {
					unset($value);
					unset($arrays_shift[$key]);
				}
			}

			if (isset($value)) {
				if ((is_array($value)) && (@is_array($arrays_shift[$key]))) {
					$arrays_shift[$key] = myrepono_array_merge_recursive($arrays_shift[$key], $value);
				} elseif (is_string($value)) {
					$arrays_shift[$key] = $value;
				} else {
					$arrays_shift[$key] = $value;
				}
			}
		}
	}

	return $arrays_shift;

}


function myrepono_plugin_output($tab = 'backups', $output = '', $type = '') {

	if ($output!='') {

		if (($type=='') || ($type=='header')) {

			global $myrepono;

			if ((!isset($myrepono['papi']['key'])) || ($myrepono['papi']['key']=='')) {
				$tab = false;
			}

			$tabs_output = '';

			$tabs = array(
				'backups' => 'Backups',
				'files' => 'Files',
				'databases' => 'Databases',
				'settings' => 'Settings',
				'account' => 'Account',
				'plugin' => 'Plugin'
			);

			$tabs_keys = array_keys($tabs);
			$tabs_count = '6';

			for ($i=0; $i<$tabs_count; $i++) {

				$tabs_key = $tabs_keys[$i];

				if (($tab!==false) || ($tabs_key=='backups')) {

					$tabs_output .= '<a href="';

					if ($tabs_key=='backups') {
						$tabs_output .= admin_url('admin.php?page=myrepono');
					} else {
						$tabs_output .= admin_url('admin.php?page=myrepono-'.$tabs_key);
					}

					$tabs_output .= '"';

				} else {

					$tabs_output .= '<span';

				}

				$tabs_output .= ' class="nav-tab';

				if (($tab===false) && ($tabs_key=='backups')) {
					$tabs_output .= ' nav-tab-active';
				} elseif ($tab===false) {
					$tabs_output .= '" style="color:#d4d4d4;';
				} elseif ($tab==$tabs_key) {
					$tabs_output .= ' nav-tab-active';
				}

				$tabs_output .= '">';

				$tabs_output .= $tabs[$tabs_key];

				if (($tab!==false) || ($tabs_key=='backups')) {

					$tabs_output .= '</a>';

				} else {

					$tabs_output .= '</span>';

				}
			}

			$header_type = 'h2';

			if (myrepono_wordpress_version()>4.2) {

				$header_type = 'h1';

			}

			if ($tabs_output!='') {

				$img_url = $myrepono['plugin']['url'].'img';

				$tabs_output = '<'.$header_type.' class="nav-tab-wrapper">'.$tabs_output.'<a href="http://myRepono.com/" target="new"><img src="'.$img_url.'/myrepono_logo_trans_nav.png" align="right" width="94" height="29" alt="myRepono Website Backup Service" title="myRepono Website Backup Service" border="0" style="position:relative;left:2px;" /></a></'.$header_type.'>';

			}

			print <<<END
<div class="wrap" style="min-width:700px;">
	<div id="icon-options-general" class="icon32"><br /></div>
	<$header_type>myRepono WordPress Backup Plugin</$header_type>
	$tabs_output
END;

		}

		if (($type=='') || ($type=='footer')) {

			print <<<END

	$output
</div>
END;

		}
	}
}


function myrepono_plugin_permissions($tab = '') {

	global $myrepono;

	$output = <<<END

<br class="clear" />
<div id="col-container">
	<div class="col-wrap">

		<p><b>You do not have permission to access this section of the myRepono WordPress Backup Plugin.</b></p>
		<p>To update your plugin permissions, please <a href="https://myRepono.com/my/"><b>log-in to your myRepono.com account</b></a> and proceed to the 'Plugins' section.</p>

	</div>
</div>

END;

	return $output;

}


function myrepono_save_api($path, $api) {

	$fh = fopen($path, 'w');

	if ($fh) {

		fwrite($fh, $api);
		fclose($fh);

		return true;

	}

	return false;

}


function myrepono_add_option($option) {

	if (isset($option['tmp'])) {
		unset($option['tmp']);
	}

	$option['papi']['connect']['error'] = '0';
	$option['papi']['connect']['auth_error'] = '0';

	$option = base64_encode(serialize($option));

	add_option('myrepono-plugin', $option, '', 'no');

}


function myrepono_update_option($option) {

	if (isset($option['tmp'])) {
		unset($option['tmp']);
	}

	$option['papi']['connect']['error'] = '0';
	$option['papi']['connect']['auth_error'] = '0';

	$option = base64_encode(serialize($option));

	update_option('myrepono-plugin', $option);

}


function myrepono_get_option() {

	if ($option = get_option('myrepono-plugin')) {

		$option = unserialize(base64_decode($option));

		if (is_array($option)) {

			$option['papi']['connect']['error'] = '0';
			$option['papi']['connect']['auth_error'] = '0';

			return $option;

		}
	}

	return false;

}


function myrepono_delete_option() {

	delete_option('myrepono-plugin');

}


function myrepono_url_to_name($url = '') {

	$url_short = "";
	$url_clean = str_replace('http://','',$url);
	$url_clean = str_replace('https://','',$url_clean);
	$url_clean = explode('/',$url_clean);
	$url_clean = str_replace('www.','',$url_clean[0]);

	$url_short = substr($url_clean,0,35);
	if (substr($url_clean,35,1)!='') {
		if (substr($url_clean,36,1)!='') {
			$url_short .= '...';
		} else {
			$url_short = substr($url_clean,0,36);
		}
	}

	return $url_short;

}


function myrepono_secret($length = '32') {

	$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

	$string = '';

	for ($i=0; $i<$length; $i++) {

		$string .= $characters[rand(0,strlen($characters)-1)];

	}

	return $string;

}


function myrepono_time_offset($time) {

	global $myrepono;

	if (isset($myrepono['papi']['time_offset'])) {
		if ($myrepono['papi']['time_offset']!='0') {
			$time += $myrepono['papi']['time_offset'];
		}
	}

	return $time;

}


function myrepono_time_ago($time, $granularity = '2') {

	global $myrepono;

	$difference = myrepono_time_offset(time()) - $time;

	 $periods = array(
    	'decade' => 315360000,
        'year' => 31536000,
        'month' => 2628000,
        'week' => 604800,
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60
	);

	$return = '';

    foreach ($periods as $key => $value) {
        if ($difference>=$value) {
            $time_floor = floor($difference/$value);
            $difference %= $value;
            $return .= ($return ? ' ' : '').$time_floor.' ';
            $return .= (($time_floor > 1) ? $key.'s' : $key);
            $granularity--;
        }
        if ($granularity==0) {
        	break;
        }
    }

    if ($return=='') {

    	$return = '1 minute';

    }

    return $return;

}


function myrepono_filesize($filesize_bytes) {

	$filesize = $filesize_bytes;
	$filesize_type = 'bytes';

	if ($filesize>512) {

		$filesize = $filesize / 1024;
		$filesize_type = 'KB';

		if ($filesize>512) {

			$filesize = $filesize / 1024;
			$filesize_type = 'MB';

			if ($filesize>512) {

				$filesize = $filesize / 1024;
				$filesize_type = 'GB';

				if ($filesize>512) {

					$filesize = $filesize / 1024;
					$filesize_type = 'TB';

				}
			}
		}

		$filesize = sprintf ("%01.2f", $filesize);

	} else {

		$filesize = sprintf ("%01.0f", $filesize);

	}

	return $filesize." ".$filesize_type;

}


function myrepono_clean($string = '') {

	return htmlentities(strip_tags($string));

}


function myrepono_path($path = '') {

	return str_replace('//', '/', str_replace('./', '/', str_replace('../', '/', str_replace('\\', '/', $path))));

}

function myrepono_escape_string($string = '') {

	$search = array("\\", "\0", "\n", "\r", "\x1a", "'", '"');
	$replace = array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"');
	$string = str_replace($search, $replace, $string);

	return $string;

}

function myrepono_response($type = 'success', $array = array(), $response = '', $mini = '0') {

	global $myrepono;

	$icon_url = $myrepono['plugin']['url'].'img/icons';

	if (is_array($array)) {

		$responses = array();

		$array_keys = array_keys($array);
		$array_count = count($array_keys);

		for ($i=0; $i<$array_count; $i++) {

			$array_key = $array_keys[$i];

			$div_id = myrepono_secret('8');

			if (!isset($responses[$array[$array_key]])) {

				$responses[$array[$array_key]] = true;

				if ($type=='success') {

					if ($mini=='0') {

						$response .= '<div class="myrepono_success" id="myrepono_success_'.$div_id.'"><img src="'.$icon_url.'/accept.png" width="16" height="16" alt="Success" title="Success" /><a href="javascript:;" onclick="myrepono_hide(\'myrepono_success_'.$div_id.'\');" class="myrepono_hide"><img src="'.$icon_url.'/cross.png" width="10" height="10" alt="Hide Notice" title="Hide Notice" /></a><span>'.$array[$array_key].'</span></div>';

					} else {

						$response .= '<img src="'.$icon_url.'/accept.png" width="12" height="12" id="myrepono_plugin_status_icon" alt="Success" title="Success" />'.$array[$array_key];

					}
				} elseif ($type=='error') {

					if ($mini=='0') {

						$response .= '<div class="myrepono_error" id="myrepono_error_'.$div_id.'"><img src="'.$icon_url.'/error.png" width="16" height="16" alt="Error" title="Error" /><a href="javascript:;" onclick="myrepono_hide(\'myrepono_error_'.$div_id.'\');" class="myrepono_hide"><img src="'.$icon_url.'/cross.png" width="10" height="10" alt="Hide Notice" title="Hide Notice" /></a><span>'.$array[$array_key].'</span></div>';

					} else {

						$response .= '<img src="'.$icon_url.'/error.png" width="12" height="12" id="myrepono_plugin_status_icon" alt="Error" title="Error" />'.$array[$array_key];

					}
				} elseif ($type=='critical') {

					if ($mini=='0') {

						$response .= '<div class="myrepono_error" id="myrepono_error_'.$div_id.'"><img src="'.$icon_url.'/exclamation.png" width="16" height="16" alt="Error" title="Error" /><a href="javascript:;" onclick="myrepono_hide(\'myrepono_error_'.$div_id.'\');" class="myrepono_hide"><img src="'.$icon_url.'/cross.png" width="10" height="10" alt="Hide Notice" title="Hide Notice" /></a><span>'.$array[$array_key].'</span></div>';

					} else {

						$response .= '<img src="'.$icon_url.'/exclamation.png" width="12" height="12" id="myrepono_plugin_status_icon" alt="Error" title="Error" />'.$array[$array_key];

					}
				}
			}
		}
	}

	return $response;

}


?>