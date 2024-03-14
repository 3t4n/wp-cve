<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

do_action('majesticsupport_load_wp_plugin_file');
// check for plugin using plugin name
if (is_plugin_active('majestic-support/majestic-support.php')) {
	$query = "SELECT * FROM `".majesticsupport::$_db->prefix."mjtc_support_config` WHERE configname = 'versioncode' OR configname = 'last_version' OR configname = 'last_step_updater'";
	$result = majesticsupport::$_db->get_results($query);
	$config = array();
	foreach($result AS $rs){
		$config[$rs->configname] = $rs->configvalue;
	}
	if($config['versioncode'] != ''){
		$config['versioncode'] = MJTC_majesticsupportphplib::MJTC_str_replace('.', '', $config['versioncode']);
	}
	if(!empty($config['last_version']) && $config['last_version'] != '' && $config['last_version'] < $config['versioncode']){
		$last_version = $config['last_version'] + 1; // files execute from the next version
		$currentversion = $config['versioncode'];
		for($i = $last_version; $i <= $currentversion; $i++){
			$path = MJTC_PLUGIN_PATH.'includes/updater/files/'.$i.'.php';
			if(file_exists($path)){
				include_once($path);
			}
		}
	}
	$mainfile = MJTC_PLUGIN_PATH.'majestic-support.php';
	$contents = file_get_contents($mainfile);
	if($contents != ''){
		$contents = MJTC_majesticsupportphplib::MJTC_str_replace("include_once 'includes/updater/updater.php';", '', $contents);
	}
	file_put_contents($mainfile, $contents);

	function mjtc_recursiveremove($dir) {
		$structure = glob(MJTC_majesticsupportphplib::MJTC_rtrim($dir, "/").'/*');
		if (is_array($structure)) {
			foreach($structure as $file) {
				if (is_dir($file)) mjtc_recursiveremove($file);
				elseif (is_file($file)) unlink($file);
			}
		}
		rmdir($dir);
	}            	
	$dir = MJTC_PLUGIN_PATH.'includes/updater';
	mjtc_recursiveremove($dir);

}



?>
