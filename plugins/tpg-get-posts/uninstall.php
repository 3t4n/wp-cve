<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit ();
}else {
	tpg_get_posts_uninstall();
}

function tpg_get_posts_uninstall() {
	$tpg_gp_opts = get_option("tpg_gp_opts");
	if ($tpg_gp_opts['valid-lic'] && $tpg_gp_opts['keep-opts']) {
		return;
	} else {
		delete_option('tpg_gp_opts');
	}
}


?>
