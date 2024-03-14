<?php
/*
Plugin Name: Yes/No Chart
Plugin URI: https://kohsei-works.com/plugins
Description: Yes/No Chart plugin. Yes/Noチャートを作れるプラグインです
Author: kohseiworks
Ahthor Name: KOHSEI WORKS
Author URI: https://kohsei-works.com
Text Domain: yesno
Domain Path: /languages
Version: 1.0.12
*/
	require_once dirname(__FILE__).'/class/class-yesno.php';
	require_once dirname(__FILE__).'/class/class-activation.php';
	require_once dirname(__FILE__).'/class/class-updation.php';
	require_once dirname(__FILE__).'/class/class-paging.php';
	require_once dirname(__FILE__).'/class/class-ajax.php';
	require_once dirname(__FILE__).'/class/class-functions.php';
	require_once dirname(__FILE__).'/class/class-set.php';
	require_once dirname(__FILE__).'/class/class-question.php';
	require_once dirname(__FILE__).'/class/class-info.php';
	require_once dirname(__FILE__).'/class/class-adminpage.php';

	$yesno = new YESNO();
?>