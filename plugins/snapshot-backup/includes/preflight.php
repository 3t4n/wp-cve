<?php
/* Snapshot Backup PREFLIGHT CHECKLIST
   check folder permissions to avoid nasty surprises
   @since 1.6
   */

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
// returns error message if there is one
function snapshot_preflight_problem($trouble) {
echo '<div class="error"><h3>Houston, we have a problem: </h3>' .$trouble . '<br /><br /></div>';
echo '<hr><br />';
// call footer
snapshot_footer();
exit;
}

echo "<h2>Preflight Checks</h2>";

// check if wp-contents/uploads exists
$savepath = WP_CONTENT_DIR . '/uploads/';
if (!file_exists ($savepath)){
$trouble = 'Your local wp-content/uploads directory does not exist. <br />Please create it in the following folder and set its permissions to 666: <br /><br />' . WP_CONTENT_DIR;
snapshot_preflight_problem($trouble);
}
// and if it's writeable
if (!is_writable ($savepath)){
$trouble = 'Your local wp-content directory is not writable. <br />Please change folder permissions of the following folder to 666: <br /><br />' . WP_CONTENT_DIR . '/uploads/';
snapshot_preflight_problem($trouble);
}

// now let's see if we can connect to the FTP repo
// set up variables
$host = get_option('snapshot_ftp_host');
$user = get_option('snapshot_ftp_user');
$pass = get_option('snapshot_ftp_pass');
$subdir = get_option('snapshot_ftp_subdir');
if ($subdir =='') {
	$subdir = '/';
}
$remotefile = $subdir . '/' . $filename;

// @since 1.6.1
// only check FTP Connection if we have details
// otherwise skip this and do a local backup
//

if ($host) {
// connect to host
// extra security
// @since 2.1
// If in WP Dashboard or Admin Panels
if ( is_admin() ) {
// If user has WP manage options permissions
if ( current_user_can('manage_options')) {
// connect to host ONLY if the 2 security conditions are valid / met
$conn = ftp_connect($host);
if (!$conn)
{
  $trouble = 'I could not connect to your FTP server.<br />Please check your FTP Host settings and try again (leave FTP Host BLANK for local backups).';
  snapshot_preflight_problem($trouble);
}
// can we log in?
$result = ftp_login($conn, $user, $pass);
if (!$result)
{
$trouble = 'I could not log in to your FTP server.<br />Please check your FTP Username and Password, then try again.<br />For local backups, please leave the FTP Host option BLANK.';
  snapshot_preflight_problem($trouble);
}
// and does the remote directory exist?
$success = ftp_chdir($conn, $subdir);
if (!$success)
{
$trouble = 'I cannot change into the FTP subdirectory you specified. Does it exist?<br />You must create it first using an FTP client like FileZilla.<br />Please check and try again.';
  snapshot_preflight_problem($trouble);
}
// and is it writeable?
// ah... I don't know how to test that :-(

// end if
}
}
}
else {
	echo "The FTP Details are missing or not complete. This will be a local backup only.<br />";
}

echo "All good - let's Snapshot!<br />";

?>
