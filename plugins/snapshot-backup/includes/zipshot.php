<?php

// Direct calls to this file are Forbidden when core files are not present
// Thanks to Ed from ait-pro.com for this  code 
// @since 2.1
// doesn't work when file is included by script :-(
/*
if ( !function_exists('add_action') ){
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}

if ( !current_user_can('manage_options') ){
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}
*/
// 
//

echo "<h2>Archiving your website</h2>";
echo "This could take another moment...<br />";
$savepath = WP_CONTENT_DIR . '/uploads/';

// delete previous backups
$output = exec('rm '.$savepath.'*snapshot-*.tar');
// generate filename for backup
$prefix = get_option('snapshot_ftp_prefix');
if (!$prefix == ''){
$filename = $prefix.'-snapshot-'.$filetime.'.tar';
} else {
$filename = 'snapshot-'.$filetime.'.tar';
}
// echo "<br>The filename will be $filename <br>";
// save filename to database 
update_option('snapshot_latest', $filename);
// echo "<br>saving backup in $savepath";

// create exec command out of the following components:
// $safepath, $filename, .htaccess file, root directory
$snapshotfile = 'tar -cvf ' . $savepath . $filename . ' '. ABSPATH . '.htaccess ' . ABSPATH . '*';
// let's check for any additional directories
$extradir = get_option('snapshot_add_dir1');
if ($extradir) {
	$snapshotfile = $snapshotfile . ' ' . $extradir . '/*';
}
// echo $snapshotfile . '<br />';
// execute tar at system level
$output = exec($snapshotfile);  
// echo $output . '<br />';
// get size of latest file
echo "The size of this Snapshot is " . round((filesize($savepath.$filename)/1024/1024), 2)." MB<br>";

// CLEANUP: delete database file
$output = exec('rm '.$savepath.'snapshot-db-*.sql');

echo "Done!";
?>
