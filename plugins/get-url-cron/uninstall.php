<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

UNINSTALL_geturlcron_plugin_options();
function UNINSTALL_geturlcron_plugin_options() {
	if (get_option('geturlcron-uninstall-deleteall')==1) {
		geturlcron_UNINSTALL_options();
		$ulp = wp_upload_dir();
		$plugincachepath = $ulp["basedir"]."/geturlcron";
		$logfile = $plugincachepath."/geturlcron-log.cgi";
		unlink($logfile);
	}
	return;
}

function geturlcron_UNINSTALL_options() {
		delete_option( "geturlcron-emailadr" );
		delete_option( "geturlcron-timeout" );
		delete_option( "geturlcron-dellog-days" );
		$geturlcronmaxnocronjobs = (int) trim(get_option('geturlcron-maxno-cronjobs'));
		delete_option( "geturlcron-maxno-cronjobs" );
		$nooffields = 15;
		if ($geturlcronmaxnocronjobs>$nooffields) {
			$nooffields = $geturlcronmaxnocronjobs;
		}
		for ($r = 1; $r <= $nooffields; $r++) {
			delete_option( 'geturlcron-url-'.$r );
			delete_option( 'geturlcron-interval-'.$r );
			delete_option( 'geturlcron-startdate-'.$r );
			delete_option( 'geturlcron-requiredjsonfield-'.$r );
			delete_option( 'geturlcron-requiredformat-'.$r );
			delete_option( 'geturlcron-sendmail-'.$r );
		}
		delete_option('geturlcron-uninstall-deleteall');
}
?>