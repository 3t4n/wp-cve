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
?>

<h2>Reading out the Database</h2>
<?php
/* BEFORE WE START
We should use his opportunity to check for a writeable directory
perhaps create one under /wp-content/snapshots/
*/
echo 'This could take a moment...<br>';

// generate filename from system time
$dbsnapshotfile = 'snapshot-db-'.date('Ymd-Gi').'.sql';

/*********************************************
I'm retiring the previous scrip in this place
due to incompatibilities with certain servers.

The Script by David B Walsh - is still online here:
http://davidwalsh.name/backup-mysql-database-php
*/

// Database Readout via mysqldump
// @since 1.5
//
$dbfilename = 'snapshot-db-'.$filetime.'.sql';
$dbfilepath = WP_CONTENT_DIR .'/uploads/'.$dbfilename;
// echo '<br><br>xx' . $dbfilepath . 'xx<br><br>';
// @since 2.1
// mysqldump requires the --password= option if password includes special characters
// it also means we need to 'escape' it first
// 
// TO DO: use $wpdb->prefix to readout only WP tables, not everything (makes restore easier)
$dumpstring = "mysqldump -h ".DB_HOST." -u ". DB_USER ." --password='". DB_PASSWORD ."' ". DB_NAME ." > ". $dbfilepath;
// echo '<br>--'.$dbfilepath.'--<br>--'.$dumpstring.'--<br>';

$output = shell_exec($dumpstring);
// echo "<pre>$output</pre>";
// echo 'End of Database thing<br>';
$dbfilesize = round((filesize($dbfilepath)/1024/1024), 2);
// echo '<br>dbfilesize is '.$dbfilesize . ' - we are testing '.$dbfilepath.'<br>';
if ($dbfilesize > 0) {
	if ($dbfilesize < 1) {
		echo "The size of your Database File is " . ($dbfilesize * 1024) . " kB.<br>";
	}
	else {
		echo "The size of your Database File is " . $dbfilesize . " MB.<br>";
	}
} else {
	echo '<div class="error"><br>Oh Dear... The Database File appears to be empty. That sucks!<br /><br /></div><br />';
}

echo "Done!";
?>
