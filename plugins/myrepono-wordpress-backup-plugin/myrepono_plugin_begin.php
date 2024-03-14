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


function myrepono_plugin_home() {

	require_once 'myrepono_plugin_functions.php';

	require_once 'myrepono_plugin_home.php';

	myrepono_plugin_home_func();

}


function myrepono_plugin_home_queue($domain_id, $full = '1') {

	require_once 'myrepono_plugin_home.php';

	return myrepono_plugin_home_queue_func($domain_id, $full);

}


function myrepono_plugin_home_queue_ajax() {

	require_once 'myrepono_plugin_functions.php';

	require_once 'myrepono_plugin_home.php';

	return myrepono_plugin_home_queue_ajax_func();

}


function myrepono_plugin_files() {

	require_once 'myrepono_plugin_functions.php';

	require_once 'myrepono_plugin_files.php';

	myrepono_plugin_files_func();

}


function myrepono_plugin_databases() {

	require_once 'myrepono_plugin_functions.php';

	require_once 'myrepono_plugin_databases.php';

	myrepono_plugin_databases_func();

}


function myrepono_plugin_settings() {

	require_once 'myrepono_plugin_functions.php';

	require_once 'myrepono_plugin_settings.php';

	myrepono_plugin_settings_func();

}


function myrepono_plugin_account() {

	require_once 'myrepono_plugin_functions.php';

	require_once 'myrepono_plugin_account.php';

	myrepono_plugin_account_func();

}


function myrepono_plugin_plugin() {

	require_once 'myrepono_plugin_functions.php';

	require_once 'myrepono_plugin_plugin.php';

	myrepono_plugin_plugin_func();

}


function myrepono_plugin_help() {

	require_once 'myrepono_plugin_functions.php';

	$output = '';

	$myrepono_help = myrepono_help();
	$myrepono_help_count = count($myrepono_help);

	for ($i=1; $i<$myrepono_help_count; $i++) {

		$output .= '<p>'.$myrepono_help[$i]['content'].'</p><br />';

	}

	$output = <<<END

<br class="clear" />

<div id="col-container">
	<div class="col-wrap">

		<p><strong>Useful Links</strong></p><p><a href="http://myRepono.com/" target="new">Visit myRepono.com</a></p><p><a href="https://myRepono.com/my/" target="new">Log-In to myRepono.com</a></p><p><a href="http://myRepono.com/faq/" target="new">View Documentation &amp; FAQ</a></p><p><a href="http://myRepono.com/contact/" target="new">Contact Support</a></p><br />

		$output

	</div>
</div>

END;

	myrepono_plugin_output('help', $output);


}


function myrepono_plugin_status() {

	if (current_user_can('manage_options')) {

		require_once 'myrepono_plugin_functions.php';

		global $myrepono;

		$init_request = false;

		$output = myrepono_plugin_init('backups', $init_request, '0', '1');

		if ($output===false) {

			if (!isset($myrepono['plugin']['status_box'])) {
				$myrepono['plugin']['status_box'] = '1';
			}

			if (isset($_POST['myrepono_status_box'])) {

				if ($myrepono['plugin']['status_box']=='1') {

					$myrepono['plugin']['status_box'] = '0';

					$myrepono['tmp']['success'][] = 'The plugin status box has been successfully disabled.';

				} else {

					$myrepono['plugin']['status_box'] = '1';

					$myrepono['tmp']['success'][] = 'The plugin status box has been successfully enabled.';

				}

				myrepono_update_option($myrepono);

			}

			if ($myrepono['plugin']['status_box']=='1') {

				$status = '';
				$img_url = $myrepono['plugin']['url'].'img';
				$icon_url = $myrepono['plugin']['url'].'img/icons';
				$backups_url = admin_url('admin.php?page=myrepono');

				if (isset($myrepono['tmp']['mini_critical'][0])) {

					$status = myrepono_response('critical', array($myrepono['tmp']['mini_critical'][0]), '', '1');

				} elseif (isset($myrepono['tmp']['mini_error'][0])) {

					$status = myrepono_response('error', array($myrepono['tmp']['mini_error'][0]), '', '1');

				} elseif (isset($myrepono['tmp']['mini_success'][0])) {

					$status = myrepono_response('success', array($myrepono['tmp']['mini_success'][0]), '', '1');

				} elseif ((isset($myrepono['papi']['domain'])) && (isset($myrepono['papi']['permissions']['backup']))) {

					$myrepono_domain = $myrepono['papi']['domain'];
					$queue_icon = '';

					if ((isset($myrepono['domain'][$myrepono_domain]['name'])) && ($myrepono['domain'][$myrepono_domain]['name']!='')) {

						$status_name = $myrepono['domain'][$myrepono_domain]['name'].': ';

					}

					if ((isset($myrepono['domain'][$myrepono_domain]['settings'])) && (isset($myrepono['papi']['permissions']['queue']))) {

						$domain_id_keys = array_keys($myrepono['domain'][$myrepono_domain]['settings']);
						$domain_id_count = count($domain_id_keys);

						$domain_id_time = '0';

						for ($i=0; $i<$domain_id_count; $i++) {

							$domain_id_key = $domain_id_keys[$i];

							if ((isset($myrepono['domain'][$domain_id_key]['queue']['status'])) && (isset($myrepono['domain'][$domain_id_key]['queue']['time']))) {

								if ($domain_id_time<$myrepono['domain'][$domain_id_key]['queue']['time']) {

									$domain_match = '0';

									if ($myrepono['domain'][$domain_id_key]['queue']['status']=='2') {

										$queue_icon = 'control_play_blue.png';
										$status = 'Backup In Progress';
										$domain_match = '1';

									} elseif ($myrepono['domain'][$domain_id_key]['queue']['status']=='0') {

										$queue_icon = 'exclamation.png';
										$status = 'Last Backup Failed';
										$domain_match = '1';

									}

									if ($domain_match=='1') {

										$domain_id_time = $myrepono['domain'][$domain_id_key]['queue']['time'];

										if ((isset($myrepono['domain'][$myrepono_domain]['settings'][$domain_id_key]['name'])) && ($myrepono['domain'][$myrepono_domain]['settings'][$domain_id_key]['name']!='')) {

											$status_name = $myrepono['domain'][$myrepono_domain]['settings'][$domain_id_key]['name'].': ';

										} elseif ((isset($myrepono['domain'][$domain_id_key]['name'])) && ($myrepono['domain'][$domain_id_key]['name']!='')) {

											$status_name = $myrepono['domain'][$domain_id_key]['name'].': ';

										}
									}
								}
							}
						}
					}

					if ($status=='') {

						if ((isset($myrepono['domain'][$myrepono_domain]['queue']['status'])) && (isset($myrepono['papi']['permissions']['queue']))) {

							if ($myrepono['domain'][$myrepono_domain]['queue']['status']=='1') {

							} elseif ($myrepono['domain'][$myrepono_domain]['queue']['status']=='2') {

								$queue_icon = 'control_play_blue.png';
								$status = 'Backup In Progress';

							} elseif ($myrepono['domain'][$myrepono_domain]['queue']['status']=='0') {

								$queue_icon = 'exclamation.png';
								$status = 'Last Backup Failed';

							} elseif ($myrepono['domain'][$myrepono_domain]['queue']['status']=='3') {

								$queue_icon = 'control_pause.png';
								$status = 'Backup Processing Paused';

							}
						}
					}

					if ($status=='') {

						if ((isset($myrepono['domain'][$myrepono_domain]['backups'])) && (is_array($myrepono['domain'][$myrepono_domain]['backups']))) {

							$backups_keys = array_keys($myrepono['domain'][$myrepono_domain]['backups']);
							$backups_count = count($backups_keys);

							$backups_latest_time = '';

							for ($i=0; $i<$backups_count; $i++) {

								$backup_id = $backups_keys[$i];

								if (isset($myrepono['domain'][$myrepono_domain]['backups'][$backup_id]['time'])) {

									$backup_time = myrepono_time_offset($myrepono['domain'][$myrepono_domain]['backups'][$backup_id]['time']);

									if ($backup_time>$backups_latest_time) {

										$backups_latest_time = $backup_time;

										$backup_domain_id = $myrepono['domain'][$myrepono_domain]['backups'][$backup_id]['domain'];

										if ((isset($myrepono['domain'][$myrepono_domain]['settings'][$backup_domain_id]['name'])) && ($myrepono['domain'][$myrepono_domain]['settings'][$backup_domain_id]['name']!='')) {

											$status_name = $myrepono['domain'][$myrepono_domain]['settings'][$backup_domain_id]['name'].': ';

										} elseif ((isset($myrepono['domain'][$backup_domain_id]['name'])) && ($myrepono['domain'][$backup_domain_id]['name']!='')) {

											$status_name = $myrepono['domain'][$backup_domain_id]['name'].': ';

										}
									}
								}
							}

							if ($backups_latest_time!='') {

								$backups_latest_time_ago = myrepono_time_ago($backups_latest_time, '1').' ago';

								$status = '<small>Last Backup:</small> '.$backups_latest_time_ago;
								$queue_icon = 'accept.png';

							}
						}
					}

					if (($status!='') && ($queue_icon!='')) {

						$status = '<img src="'.$icon_url.'/'.$queue_icon.'" width="12" height="12" id="myrepono_plugin_status_icon" alt="'.htmlentities($status_name).strip_tags($status).'" title="'.htmlentities($status_name).strip_tags($status).'" />'.$status;

					}
				}

				if ($status!='') {

					print '<div id="myrepono_plugin_status" onclick="document.location.href=\''.$backups_url.'\';"><a href="'.$backups_url.'"><img src="'.$img_url.'/myrepono_icon.png" id="myrepono_plugin_status_logo" width="21" height="12" alt="myRepono"> '.$status.'</a></div>';

				}
			}
		}
	}

	unset($myrepono);

}


function myrepono_plugin_admin_notices() {

	global $current_screen;

	$show_notices = '1';

	if ((isset($current_screen)) && (is_object($current_screen))) {

		if (($current_screen->id=='toplevel_page_myrepono') || (substr($current_screen->id,0,23)=='myrepono_page_myrepono-')) {

			$show_notices = '0';

		}
	}

	if (($show_notices=='1') && (current_user_can('manage_options'))) {

		require_once 'myrepono_plugin_functions.php';

		global $myrepono;

		$myrepono = myrepono_get_option();

		$plugin_url = admin_url('admin.php?page=myrepono');

		if (($myrepono!==false) && (isset($myrepono['papi']['key'])) && ($myrepono['papi']['key']!='') && (isset($myrepono['papi']['data']['alerts']))) {

			$alerts_keys = array_keys($myrepono['papi']['data']['alerts']);
			$alerts_count = count($alerts_keys);

			for ($i=0; $i<$alerts_count; $i++) {

				$alerts_key = $alerts_keys[$i];

				if ((isset($myrepono['papi']['data']['alerts'][$alerts_key]['msg'])) & (!isset($myrepono['papi']['data']['alerts'][$alerts_key]['hide']))) {

					if (!isset($myrepono['papi']['data']['alerts'][$alerts_key]['type'])) {
						$myrepono['papi']['data']['alerts'][$alerts_key]['type'] = 'error';
					}
					$alert_type = $myrepono['papi']['data']['alerts'][$alerts_key]['type'];

					$show_alert = '1';

					if (isset($myrepono['papi']['data']['alerts'][$alerts_key]['version'])) {
						if (version_compare(WP_MYREPONO_PLUGIN, $myrepono['papi']['data']['alerts'][$alerts_key]['version'], '>')) {
							$show_alert = '0';
						}
					}

					$dismiss_notice = '0';

					global $current_user;

					if ((isset($current_user)) && (is_object($current_user)) && (function_exists('get_user_meta'))) {

						$user_id = $current_user->ID;

						if (get_user_meta($user_id, 'myrepono_ignore_notice_alert_'.$alerts_key) ) {

							$show_alert = '0';

						}

						$dismiss_notice = '1';

					}

					if ($show_alert=='1') {

						print '<div class="'.$alert_type.'" style="padding:5px;"><b>myRepono Important Notice:</b> '.str_replace('%plugin_url%', $plugin_url, $myrepono['papi']['data']['alerts'][$alerts_key]['msg']);

						if ($dismiss_notice=='1') {

							print '  <a href="'.admin_url('index.php?myrepono_ignore_notice=alert_'.$alerts_key).'">Dismiss Notice</a>';

						}

						print '</div>';

					}
				}
			}

		} else {

			global $current_user;

			if ((isset($current_user)) && (is_object($current_user)) && (function_exists('get_user_meta'))) {

				$user_id = $current_user->ID;

				if (!get_user_meta($user_id, 'myrepono_ignore_notice_setup') ) {

					if (($myrepono_old = get_option('myrepono')) && (isset($myrepono_old['myr_username']))) {

						print '<div class="updated" style="padding:5px;"><b>Thank you for upgrading the myRepono WordPress Backup Plugin!</b><br />To complete the upgrade process please proceed to the <a href="'.$plugin_url.'"><b>myRepono</b></a> section of your WordPress administration panel and follow the \'Connect Plugin\' process.  <a href="'.admin_url('index.php?myrepono_ignore_notice=setup').'">Dismiss Notice</a></div>';

					} else {

						print '<div class="updated" style="padding:5px;"><b>Thank you for installing the myRepono WordPress Backup Plugin!</b><br />To complete the set-up process please proceed to the <a href="'.$plugin_url.'"><b>myRepono</b></a> section of your WordPress administration panel and follow the \'Connect Plugin\' process.  <a href="'.admin_url('index.php?myrepono_ignore_notice=setup').'">Dismiss Notice</a></div>';

					}
				}
			}
		}

		unset($myrepono);

	}
}


function myrepono_plugin_admin_notices_ignore() {

    global $current_user;

    if ((isset($current_user)) && (is_object($current_user)) && (function_exists('add_user_meta'))) {

    	$user_id = $current_user->ID;

        if ((isset($_GET['myrepono_ignore_notice'])) && (($_GET['myrepono_ignore_notice']=='setup') || (substr($_GET['myrepono_ignore_notice'],0,6)=='alert_'))) {

             add_user_meta($user_id, 'myrepono_ignore_notice_'.$_GET['myrepono_ignore_notice'], 'true', true);

		}
    }
}


function myrepono_wordpress_version() {

	global $wp_version;
	$wordpress_version = explode('-', $wp_version);
	$wordpress_version = explode('.', $wordpress_version[0]);
	if (!isset($wordpress_version[1])) {
		$wordpress_version[1] = '0';
	}
	$wordpress_version = $wordpress_version[0].'.'.$wordpress_version[1];
	if (!is_numeric($wordpress_version)) {
		if (function_exists('get_bloginfo')) {
			$wordpress_version = get_bloginfo('version');
		}
		$wordpress_version = explode('-', $wordpress_version);
		$wordpress_version = explode('.', $wordpress_version[0]);
		if (!isset($wordpress_version[1])) {
			$wordpress_version[1] = '0';
		}
		$wordpress_version = $wordpress_version[0].'.'.$wordpress_version[1];
		if (!is_numeric($wordpress_version)) {
			$wordpress_version = '2.8';
		}
	}

	return $wordpress_version;

}


function myrepono_plugin_options_help($contextual_help, $screen_id, $screen) {

	if (($screen_id=='toplevel_page_myrepono') || (substr($screen_id,0,23)=='myrepono_page_myrepono-')) {

		$myrepono_help = myrepono_help();
		$myrepono_help_count = count($myrepono_help);

		for ($i=0; $i<$myrepono_help_count; $i++) {

			$screen->add_help_tab(array(
				'id' => 'myrepono_help_'.$i,
				'title' => $myrepono_help[$i]['title'],
				'content' => $myrepono_help[$i]['content']
			));

		}

		$img_url = WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__), '', plugin_basename(__FILE__)).'img';

		$screen->set_help_sidebar('<p><strong>Useful Links</strong></p><p><a href="http://myRepono.com/" target="new">Visit myRepono.com</a></p><p><a href="https://myRepono.com/my/" target="new">Log-In to myRepono.com</a></p><p><a href="http://myRepono.com/faq/" target="new">View Documentation &amp; FAQ</a></p><p><a href="http://myRepono.com/contact/" target="new">Contact Support</a></p>');

	}

	if ($screen_id=='toplevel_page_myrepono') {

		require_once 'myrepono_plugin_functions.php';

		global $myrepono;

		$myrepono = myrepono_get_option();

		if ((($myrepono!==false) && (isset($myrepono['papi']['key'])) && ($myrepono['papi']['key']!='')) || (isset($_GET['myr_token']))) {

			$screen->add_option('per_page', array(
				'label' => 'Backups per Page',
				'default' => 10,
				'option' => 'myrepono_screen_backups_per_page'
			));

		}
	}
}


function myrepono_plugin_screen_option($status, $option, $value) {

	if ($option=='myrepono_screen_backups_per_page') {
		return $value;
	}
}


function myrepono_plugin_head(){

	global $current_screen;

	if ((isset($current_screen)) && (is_object($current_screen))) {

		if (($current_screen->id=='toplevel_page_myrepono') || (substr($current_screen->id,0,23)=='myrepono_page_myrepono-')) {

			print '<script type="text/javascript">var myrepono_img_url = \''.plugins_url('img', __FILE__).'\';</script>';

		}
	} else {

		print '<script type="text/javascript">var myrepono_img_url = \''.plugins_url('img', __FILE__).'\';</script>';

	}
}


function myrepono_plugin_styles(){

	global $current_screen;

	if ((isset($current_screen)) && (is_object($current_screen))) {

		if (($current_screen->id=='toplevel_page_myrepono') || (substr($current_screen->id,0,23)=='myrepono_page_myrepono-')) {

			wp_register_script('myrepono_plugin_js', plugins_url('js/myrepono.js', __FILE__), false, '1.0.0' );
			wp_enqueue_script('myrepono_plugin_js');

			wp_register_style('myrepono_plugin_css', plugins_url('css/myrepono.css', __FILE__), false, '1.0.0' );
			wp_enqueue_style('myrepono_plugin_css');

		}
	} else {

		wp_register_script('myrepono_plugin_js', plugins_url('js/myrepono.js', __FILE__), false, '1.0.0' );
		wp_enqueue_script('myrepono_plugin_js');

		wp_register_style('myrepono_plugin_css', plugins_url('css/myrepono.css', __FILE__), false, '1.0.0' );
		wp_enqueue_style('myrepono_plugin_css');

		if (myrepono_wordpress_version()<3) {

			wp_register_style('myrepono_plugin_nav_css', plugins_url('css/myrepono_nav.css', __FILE__), false, '1.0.0' );
			wp_enqueue_style('myrepono_plugin_nav_css');

		}
	}

	if (current_user_can('manage_options')) {

		if (myrepono_wordpress_version()<3.8) {

			wp_register_style('myrepono_plugin_status_css', plugins_url('css/myrepono_status.css', __FILE__), false, '1.0.0' );
			wp_enqueue_style('myrepono_plugin_status_css');

		} else {

			wp_register_style('myrepono_plugin_status_css', plugins_url('css/myrepono_status_38.css', __FILE__), false, '1.0.0' );
			wp_enqueue_style('myrepono_plugin_status_css');

		}
	}
}


function myrepono_plugin_menu() {

	add_menu_page('myRepono WordPress Backup Plugin', 'myRepono', 'manage_options', 'myrepono', 'myrepono_plugin_home', '', 82.14);

	require_once 'myrepono_plugin_functions.php';

	global $myrepono;

	$myrepono = myrepono_get_option();

	if ((($myrepono!==false) && (isset($myrepono['papi']['key'])) && ($myrepono['papi']['key']!='')) || (isset($_GET['myr_token']))) {

		add_submenu_page('myrepono', 'myRepono WordPress Backup Plugin', 'Backups', 'manage_options', 'myrepono', 'myrepono_plugin_home');

		add_submenu_page('myrepono', 'Files - myRepono WordPress Backup Plugin', 'Files', 'manage_options', 'myrepono-files', 'myrepono_plugin_files');

		add_submenu_page('myrepono', 'Databases - myRepono WordPress Backup Plugin', 'Databases', 'manage_options', 'myrepono-databases', 'myrepono_plugin_databases');

		add_submenu_page('myrepono', 'Settings - myRepono WordPress Backup Plugin', 'Settings', 'manage_options', 'myrepono-settings', 'myrepono_plugin_settings');

		add_submenu_page('myrepono', 'Account - myRepono WordPress Backup Plugin', 'Account', 'manage_options', 'myrepono-account', 'myrepono_plugin_account');

		add_submenu_page('myrepono', 'Plugin - myRepono WordPress Backup Plugin', 'Plugin', 'manage_options', 'myrepono-plugin', 'myrepono_plugin_plugin');

	} else {

		add_submenu_page('myrepono', 'myRepono WordPress Backup Plugin', 'Connect Plugin', 'manage_options', 'myrepono', 'myrepono_plugin_home');

	}
}


function myrepono_plugin_menu_help() {

	add_submenu_page('myrepono', 'Help - myRepono WordPress Backup Plugin', 'Help', 'manage_options', 'myrepono-help', 'myrepono_plugin_help');

}


function myrepono_help() {

	$myrepono_help = array(
		'0' => array(
			'title' => 'myRepono Help',
			'content' => <<<END
<p><strong>Getting Started</strong><br />If you're new to the myRepono WordPress Backup Plugin, please select the 'Getting Started' option to the left of this text.</p>
<p><strong>Plugin Information</strong><br />For general information about this plugin, please select the 'Plugin Information' option to the left of this text.</p>
<p><strong>Plugin Usage &amp; Documentation</strong><br />For usage guidance and documentation for each tab/section of this plugin, please select the 'Usage' options to the left of this text.</p>
END
		),
		'1' => array(
			'title' => 'Plugin Information',
			'content' => <<<END
<p><strong>Plugin Information</strong><br />myRepono is a website backup service which enables you to securely backup your website files and mySQL databases using an online and web-based management system.  The myRepono WordPress Backup Plugin provides an interface for the myRepono.com system as part of your WordPress administration panel, and also automates the myRepono setup process.</p>
<p>When you first set-up the myRepono WordPress Backup Plugin the myRepono API will be installed on your website, this API is used to manage your backup and restoration processing independently of WordPress.  Please note that the myRepono WordPress Backup Plugin is not directly associated to the backup or restoration processing which is handled by your myRepono API and the myRepono.com system, the key benefit to this approach is that you can restore your WordPress installation by simply re-installing the myRepono API and provoking a restoration via the myRepono.com system - this process involves uploading a single file to your website which will enable you to restore your complete WordPress installation and the myRepono WordPress Backup Plugin.</p>
<p>myRepono is a commercial backup service which uses a pay-as-you-go balance system.  New users receive \$5 USD free credit to help them get started, and with prices starting at 2 cents per day that's enough free credit to backup most WordPress installations for several months!<p>
<p>For guidance setting up the myRepono WordPress Backup Plugin, please select the 'Getting Started' option to the left of this text.</p>
END
		),
		'2' => array(
			'title' => 'Getting Started',
			'content' => <<<END
<p><strong>Getting Started</strong><br />To set-up the myRepono WordPress Backup Plugin you need to connect it to your myRepono.com account, to do this we use a 'Connect Plugin' process which provides a secure and convenient method for creating a secure connection between this plugin and the myRepono.com system.  If you have not yet connected your plugin then a 'Connect Plugin' button will be shown when accessing this plugin, simply select the 'Connect Plugin' button and follow the on-screen instructions.</p>
<p>Once you have completed the 'Connect Plugin' process your plugin will automatically install the myRepono API, and if it has permission to do so it will automatically select your WordPress files and database for backup and begin processing a backup.  If during the 'Connect Plugin' process you de-selected the 'Select Data' or 'Change Settings' permissions then your plugin will not have permission to complete these steps, in this case please log-in to your myRepono.com account and proceed to the 'Domains' -&gt; 'Files', 'Databases' and 'Settings' sections.</p>
<p>If your first backup fails you will be sent an email notification and you will be shown a failed backup error when viewing this domain under your myRepono.com account.  If you have assigned the 'Manage Backups' permissions for this plugin then a failed backup error will also be shown under the 'Backups' tab of this plugin.  Please follow the instructions included in the failed backup error to resolve the issue, if problems persist we recommend accessing your myRepono.com account which will be able to provide more detailed information about the error than can be made available through this plugin.</p>
<p>If you experience issues during the 'Connect Plugin' process, or if you expierence API installation or failed backup issues after connecting your plugin, please contact support immediately.</p>
END
		),
		'3' => array(
			'title' => 'Usage: Backups',
			'content' => <<<END
<p><strong>Usage: The 'Backups' Tab</strong><br />
If the 'View Backup List' plugin permission is enabled then the 'Backups' tab will provide a list of your backups.  If the 'Manage Backups' plugin permission is enabled then you will be able to 'Archive', 'Un-Archive' and 'Remove' your backups, as well as view your 'Backup Queue' and 'Backup Now!' at the top of the page.</p>
<p>The 'Archive' and 'Un-Archive' options enable you to archive your backups, when a backup is archived it can not be automatically removed by the system when it is due to be replaced by a new backup.  Archived backups are not counted under your 'Stored Backups' setting and will be stored in addition to the maximum number of stored backups.</p>
<p>The 'Remove' option enables you to remove a backup, all removed backups will be available for recovery via the 'Backups' -&gt; 'Recover Deleted Backups' section of your myRepono.com account for a short period of time.</p>
<p>The 'Add Note' option enables you to add a note in assocation to the relevant backup, this can be used to help you identify specific backups in the future - for example you may wish to note which backup was processed prior to upgrading WordPress.</p>
<p>Your 'Backup Queue' is shown at the top of the 'Backups' pages, just below the navigation tabs.  The 'Backup Queue' enables you to view the backup status for each of your profiles and will indicate whether your last backup was successful or failed, or whether a backup is in progress.  If a backup is not in progress and your 'Backup Status' setting is 'Active' then a 'Backup Now!' button will be displayed which enables you to provoke an immediate backup.  The 'Refresh' option enables you to retrieve the latest backup status.</p>
END
		),
		'4' => array(
			'title' => 'Usage: Files',
			'content' => <<<END
<p><strong>Usage: The 'Files' Tab</strong><br />
If the 'Select Data' plugin permission is enabled then the 'Files' tab will enable you to manage your file/directory selections which control what files/directories will be backed up, as well as your file exclusion rules which control what files/directories should not be backed up.</p>
<p>To add a new file/directory for backup simply enter the full/absolute file path to the file or directory you would like to backup (e.g. /home/username/public_html/ or /home/username/public_html/index.php) and select the 'Add File/Directory' button - when adding a directory the complete contents of the directory will be selected for backup.</p>
<p>Any existing files or directories which you have selected for backup will be displayed in the 'Selected Files' section, if a directory containing your WordPress installation is selected then a 'Contains WordPress' icon will be displayed, alternatively any files or directories which do not appear to exist will be indicated with a 'May Not Exist' icon.</p>
<p>File exclusion rules enable you to define search patterns which are checked against your file paths, if a match is found the file will be excluded from your backup. This feature is ideal for preventing the backup of redundant and unnecessary data such as log files, or data files from another backup system.</p>
<p>File exclusion rules are compared for an exact match against your file path, this means to block a specific file you must specify the absolute/full file path for the file (e.g. /home/username/public_html/dir/file.html).  File exclusion rules can include asterisk (*) wildcards to create partial match searches. For example, the exclusion rule *.log would exclude any file which ended with .log. Asterisk wildcards can be used multiple times and within rules (e.g. /home/*/public_html/*.log).</p>
<p>To add a new exclusion rule simply enter the exclusion rule and select the 'Add Exclusion Rule' button.  Any existing exclusion rules will be displayed in the 'Exclusion Rules' section.</p>
END
		),
		'5' => array(
			'title' => 'Usage: Databases',
			'content' => <<<END
<p><strong>Usage: The 'Databases' Tab</strong><br />
If the 'Select Data' plugin permission is enabled then the 'Database' tab will enable you to manage your database selections which control what database tables will be backed up.</p>
<p>To add a new database for backup simply enter your database host address, name, username and password and select the 'Add Database' button.  When adding a new database all database tables will be automatically selected for backup.</p>
<p>Any existing databases which you have selected for backup will be displayed in the 'Selected Databases' section, if your WordPress database is selected then a 'WordPress Database' icon will be displayed.</p>
<p>To edit a database simply select the appropriate database and select the 'Edit Database' option, you will then be prompted to enter your database password and confirm your database access details, once confirmed you will be shown a list of your database tables and will be able to de-select any database tables you do not wish to backup.</p>
<p>Please note, for security reasons your plugin can not retrieve your database passwords, therefore the plugin can not verify your selected database details are valid - your database details will be validated when adding or editing a database.  Also note, this plugin will not permit you to manage database table selections for databases which have more than 256 database tables, except when your plugin supports PUT requests in which case the plugin will allow you to manage up to 2048 database tables per database.  If you experience difficulty managing your database tables please log-in to your myRepono.com account and proceed to the 'Domains' -&gt; 'Databases' section.</p>
END
		),
		'6' => array(
			'title' => 'Usage: Settings',
			'content' => <<<END
<p><strong>Usage: The 'Settings' Tab</strong><br />
If the 'Change Settings' plugin permission is enabled then the 'Settings' tab will enable you to manage your domain settings and profiles.</p>
<p>Your domain settings allow you to customise various settings associated to your backups, including how often you would like your backups to be processed and how many backups you would like to store.  You may add up to 10 domain profiles under each domain, each profile may have it's own unique settings.</p>
<p>To add a new domain profile simply select the 'Add New Profile' option.  When viewing any profile but the first, a 'Delete Profile' option will be provided allowing you to delete the profile, when doing so any backups associated to that profile will be transferred to be associated to the first profile.</p>
<p>Please note, if you have a large number of databases or tables then you may experience difficulty managing your settings.  If you experience difficulty managing your settings please log-in to your myRepono.com account and proceed to the 'Domains' -&gt; 'Settings' section.</p>
END
		),
		'7' => array(
			'title' => 'Usage: Account',
			'content' => <<<END
<p><strong>Usage: The 'Account' Tab</strong><br />
If the 'View Balance' plugin permission is enabled then the 'Account' tab will enable you to view your myRepono.com account email address, balance and currency.</p>
<p>To top-up your account balance simply select the 'Top-Up Account Balance' option which will direct you to myRepono.com to submit your top-up payment.</p>
<p>Please note, if the 'View Balance' plugin permission is not enable then the plugin will be unable to notify you when your account balance is running low.</p>
END
		),
		'8' => array(
			'title' => 'Usage: Plugin',
			'content' => <<<END
<p><strong>Usage: The 'Plugin' Tab</strong><br />
The 'Plugin' tab provides an overview of your plugin configuration as well as various useful functions.</p>
<p>Your 'Plugin Permissions' will be listed indicating which permissions are assigned to your plugin.  Only the myRepono.com account holder may update your plugin permissions via the 'Plugins' section of their myRepono.com account.</p>
<p>The 'Plugin Connection' section provides details of your plugin's connection to your myRepono.com account, including your 'abbreviated' key, associated domain configuration, and plugin connection method.  Your 'abbreviated' key and domain configuration are used to help you identify your plugin in the 'Plugins' section of your myRepono.com account.  Your plugin connection method indicates how your plugin is communicating with your myRepono.com account, the optimal method is 'HTTPS via PHP CURL (PUT Requests are Supported)' indicating the plugin is using PHP CURL to communicate over HTTPS, using PUT requests.  If HTTPS is not supported then the HTTP protocol will be used, if PHP CURL is not supported then various fail-over methods will be used which do not support PUT requests - PUT requests are used to communicate large quantities of data to the myRepono.com system, such as a long list of database table names.</p>
<p>The 'myRepono API' section provides the URL and file system path to your myRepono API which is used to process your backups and restorations independently of WordPress, the URL and path are used to help you understand how your plugin and domain configuration are setup.</p>
<p>The 'Create .htaccess File' option will create a '.htaccess' file within your API directory to remove any restrictions created by other '.htaccess' files which may disrupt access to your API.  The created '.htaccess' file will only affect your API directory and will not affect any other directories on your website/server.  Please note, '.htaccess' files are only supported on servers running the Apache Web Server software.  If a '.htaccess' file already exists then a 'Remove .htaccess File' option will be provided.</p>
<p>The 'Refresh Cache' option enables you to update the account data your plugin has saved.  For example, your backups list will be automatically cached by your plugin and will be updated periodically, if you're feeling impatient simply select the 'Refresh Cache' option to immediately retrieve your latest backups list.</p>
<p>The 'Status Box' option allows you to enable or disable the myRepono status box which is shown in the top right through-out your WordPress administration panel.</p>
<p>The 'Re-Install myRepono API' option enables you to automatically re-install the latest version of the myRepono API.</p>
<p>The 'Disconnect Plugin' option allows you to reset your myRepono WordPress Backup Plugin, when selected your plugin's connection to your myRepono.com account will be cancelled and any data your plugin is storing will be removed.</p>
END
		)
	);

	return $myrepono_help;

}


?>