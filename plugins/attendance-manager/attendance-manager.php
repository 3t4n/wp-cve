<?php
/*
Plugin Name: Attendance Manager
Plugin URI: http://attmgr.com
Description: Each user can edit their attendance schedule by themselves. 管理者のほか、ユーザー自身も編集可能な出勤管理プラグイン。
Author: tnomi
Ahthor Name: SUKIMALAB
Author URI: http://sukimalab.com
Text Domain: attendance-manager
Domain Path: /languages/
Version: 0.6.1
*/

	require_once dirname(__FILE__).'/class/class-attmgr.php';
	require_once dirname(__FILE__).'/class/class-cron.php';
	require_once dirname(__FILE__).'/class/class-user.php';
	require_once dirname(__FILE__).'/class/class-activation.php';
	require_once dirname(__FILE__).'/class/class-updation.php';
	require_once dirname(__FILE__).'/class/class-calendar.php';
	require_once dirname(__FILE__).'/class/class-shortcodes.php';
	require_once dirname(__FILE__).'/class/class-form.php';
	require_once dirname(__FILE__).'/class/class-info.php';
	require_once dirname(__FILE__).'/class/class-adminpage.php';
	require_once dirname(__FILE__).'/class/class-functions.php';

	$attmgr = new ATTMGR();
?>
