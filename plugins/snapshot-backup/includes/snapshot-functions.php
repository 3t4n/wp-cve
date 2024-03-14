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
// FUNCTIONS GALORE
// we can use this command to include more functions into this file
// include plugin_dir_path( __FILE__ ) . 'extra-functions.php';
// include plugin_dir_path( __FILE__ ) . 'database-functions.php';
// include plugin_dir_path( __FILE__ ) . 'tar-functions.php';
// include plugin_dir_path( __FILE__ ) . 'ftp-functions.php';

// DISPLAY HEADER
// @since 2.0
function snapshot_header($headline){
echo '<img src="'.plugin_dir_url( __FILE__ ).'../images/Snapshot-Header.png'.'"><br />';
echo '<div class="wrap"><h2>';
echo ' ' . $headline. '</h2>';
}

// DISPLAY FOOTER
// @since 2.0
function snapshot_footer(){
    ?>
	<br /><hr />

<p>This plugin was brought to you by<br />
    <a href="http://wpguru.co.uk" target="_blank"><?php echo '<img src="'. plugin_dir_url(__FILE__).'../images/WP-Guru-Header2.png'.'">'; ?></a></p>
    <p>Thank you for using <a href="http://wpguru.co.uk/2011/02/snapshot-backup/" target="_blank">Snapshot Backup</a> | <a href="http://wpguru.co.uk/hosting/ftp/" target="_blank">Get an FTP Account</a> | <a href="http://wpguru.co.uk/say-thanks/" target="_blank">Buy me a Coffee</a>
    <br>
    Signup for the <a href="http://eepurl.com/j1Hzj" target="_blank">Snapshot Backup Newsletter</a> | <a href="http://wpguru.co.uk/2012/03/snashot-backup-2-1/" target="_blank">Release Notes</a> for this version</p>
    <?php 
	} // end of function snapshot_footer

/************************
 * @since 2.0
 * MAIN SNAPSHOT FUNCTION
 *
 *
 *************************/
function do_the_snapshot() {
    
// set the number of seconds you'd like to wait for the script here
// default is 300 
set_time_limit(300);

// create global file name
$filetime = date('Ymd-Gi');

// call pre-flight checklist
include plugin_dir_path(__FILE__).'preflight.php';
// readout the Database
include plugin_dir_path(__FILE__).'database.php';
// create ZIP package
include plugin_dir_path(__FILE__).'zipshot.php';
// send package to FTP
include plugin_dir_path(__FILE__).'sendaway.php';
// we're done
echo '<div class="updated"><h2>All done - thank you for using Snapshot Backup!</h2>';
?>
</div>
<?php

 } // end of function do_the_snapshot

// SEND EMAIL FUNCTION
// @since 2.0
function snapshot_sendmail() {
	// add lines of generic text to variable
	// then add link to latest snapshot and send out the email
	$snapshot_latest = get_option('snapshot_latest');
	$snapshot_latest_path = WP_CONTENT_DIR . '/uploads/' . $snapshot_latest;
	// $latest_link = content_url() . '/uploads/' . $snapshot_latest;
	$latest_size = round((filesize($snapshot_latest_path)/1024/1024), 2);
	$latest_filetime = date ("F d Y \a\\t H:i:s.", filemtime($snapshot_latest_path));
	// $latest_link = content_url().'/uploads/'. $snapshot_latest;
	$message = "Howdy!\n\nI've just created a new Snapshot on your FTP Repository.\n\nIt's $latest_size MB and was created on $latest_filetime\nThe file is called $snapshot_latest.\n\nAll the best,\n\n\n\nYour SNAPSHOT BACKUP ;-)";
    // In case any of our lines are larger than 70 characters, we should use wordwrap()
    $message = wordwrap($message, 70);
    $snapshot_email = get_option ('snapshot_auto_email');
	$headers = "From: Snapshot Backup <" . $snapshot_email . ">\r\n";	
    // Send
    wp_mail($snapshot_email, 'New Backup of ' . get_bloginfo('name'), $message, $headers);

} // end of function

// AUTO DELETE FILES FUNCTION
// @since 2.0
function snapshot_autodelete() {
	// determine how many files we want to keep
	// then connect to FTP and see how many files are there (array)
	// delete files we don't want
	// report success or failure
	
	// set up variables
	$keepers = get_option('snapshot_repo_amount');
    $host = get_option('snapshot_ftp_host');
    $user = get_option('snapshot_ftp_user');
    $pass = get_option('snapshot_ftp_pass');
    $subdir = get_option('snapshot_ftp_subdir');
	
	// set up basic connection
	// SECURE THIS GODAMMIT
    $conn_id = ftp_connect($host);

    // login with username and password
    $login_result = ftp_login($conn_id, $user, $pass);

    // get contents of the current directory
    $contents = ftp_nlist($conn_id, "$subdir/*.tar");
	
	// 
	
	echo "<br />Number of Files there are: " . count($contents);
	echo "<br />Number of files we want to keep: " . $keepers;
	echo "<br />Files to be deleted:<br />";
	
	$num = $keepers;
	while ($num <= count($contents)){
		echo $contents[$num] . "<br />";
		// delete files
		if (ftp_delete($conn_id, $contents[$num])) {
        echo "$file deleted successful\n";
        } else {
        echo "could not delete $file\n";
		}
		$num++;
	}
	
	/* try to delete $file
    if (ftp_delete($conn_id, $contents[5])) {
    echo "$file deleted successful\n";
    } else {
    echo "could not delete $file\n";
    } */
	// close our connection
	ftp_close($conn_id);
} // end of function

?>
