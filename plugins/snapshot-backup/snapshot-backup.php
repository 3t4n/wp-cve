<?php
/*
Plugin Name: Snapshot Backup
Plugin URI: http://wpguru.co.uk/2011/02/snapshot-backup/
Description: Backs up your ENTIRE Wordpress site and sends it to an FTP archive. Excellent!
Author: Jay Versluis
Version: 2.1.1
Author URI: http://wpguru.co.uk
License: GPLv2 or later

Copyright 2011-2012 by Jay Versluis (email : versluis2000@yahoo.com)

This is Version 2.1.1 as of 02/04/2012

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

// let's start with getting some functions
// include plugin_dir_path( __FILE__ ) . 'includes/snapshot-functions.php';

/*****************************/
/* @since 2.1
/* AUTOMATION 
/*****************************/
// This has been completely re-written in version 2.1
// first we'll unschedule any cron events upon deactivation
	register_deactivation_hook (__FILE__, 'snapshot_deactivate');
	function snapshot_deactivate() {	
	// find out when the last event was scheduled
	$timestamp = wp_next_scheduled ('snapshot_automation');
	// unschedule previous event if any
	wp_unschedule_event ($timestamp, 'snapshot_automation');
	}

// now we'll add our cron hook
add_action ('snapshot_automation', 'snapshot_do_cron');

/* OLD:
add_action('snapshot_automation', 'snapshot_auto_activation');
function snapshot_auto_activation() {
	if ( !wp_next_scheduled( 'snapshot_automation' ) ) {
		wp_schedule_event(time(), 'snapshot_interval', 'snapshot_automation');
	}
}
*/

// next up is the actual function that creates the snapshot
function snapshot_do_cron() {
    // grab functions
	include plugin_dir_path( __FILE__ ) . 'includes/snapshot-functions.php';
    // check if we're actually suppsed to do something
    // if ( get_option ('snapshot_auto_interval') != 'never') {
	// create snapshot
	// moved further down: do_the_snapshot();
    // call AUTO DELETE FEATURE if lower than 100
	if (get_option('snapshot_repo_amount') < '101') {
	snapshot_autodelete();
	}
    // now create the snapshot
    do_the_snapshot();
	
    // call SEND EMAIL NOTIFICATION
	if ( get_option('snapshot_auto_email') !='') {
		snapshot_sendmail();

	} // end if email
    // } // end if do snapshot
	
} // end of function

// cueue automation whenever WordPress loads
add_action('wp', 'snapshot_auto_activation');

    // if the user wants to use automation, let's activate it here
function snapshot_auto_activation () {	
	
	// if the option is enabled and not already scheduled lets schedule it
	if ( get_option('snapshot_auto_interval') != 'never' && !wp_next_scheduled( 'snapshot_automation' ) ) {
	
		//schedule the event to run at interval
		wp_schedule_event( time(), 'snapshot_interval', 'snapshot_automation' );
		
	// if the option is NOT enabled and scheduled lets unschedule it
	} elseif ( $snapshot_auto_interval == 'never' && wp_next_scheduled( 'snapshot_automation' ) ) {	
		//get time of next scheduled run
		$timestamp = wp_next_scheduled( 'snapshot_automation' );
		
		//unschedule custom action hook
		wp_unschedule_event( $timestamp, 'snapshot_automation' );

	} // end if
} // end function

// now let's devise our own schedule by amending the existing ones
function snapshot_add_interval( $schedules ) {
	// let's only do this if the user wants to use automation
	if (get_option('snapshot_auto_interval') != 'never'){
	$schedules['snapshot_interval'] = array(
		'interval' => get_option ('snapshot_auto_interval'),
		'display' => __('Snapshot Backup Automation')
	);
	return $schedules;
	}
}
add_filter( 'cron_schedules', 'snapshot_add_interval' );
  
// ************************************************************
// END OF AUTOMATION HOOK
// ************************************************************


// Hook for adding admin menu
// @since 1.0
add_action('admin_menu', 'snapshot_admin');

// action function for above hook
function snapshot_admin() {

// Adding new top-level menu SNAPSHOT BACUP
// @since 2.0
add_menu_page('Snapshot Backup', 'Snapshot Backup', 'administrator', 'snapshot', 'snapshot_home', plugin_dir_url( __FILE__ ). 'images/snapshot-icon.png');
// Add a submenu to the custom top-level menu: MANAGE
add_submenu_page('snapshot', 'List Snapshots', 'List Snapshots', 'administrator', 'manage-repo', 'snapshot_manage_repo');
// Add a submenu to the custom top-level menu: FTP DETAILS
add_submenu_page('snapshot', 'FTP Settings', 'FTP Settings', 'administrator', 'snapshot-ftp-details', 'snapshot_ftp_details');
// Add a submenu to the custom top-level menu: AUTOMATION
add_submenu_page('snapshot', 'Automation', 'Automation', 'administrator', 'snapshot-automation', 'snapshot_automation');
// Add a submenu to the custom top-level menu: HELP
// add_submenu_page('snapshot', 'Help and Documentation', 'Help and Documentation', 'administrator', 'snapshot-help', 'snapshot_codex');
// Add a submenu to the custom top-level menu: DEV
// add_submenu_page('snapshot', 'DEV SECTION', 'DEV SECTION', 'administrator', 'dev-section', 'dev_section');
}

// Auto populate new option - in case it's empty
if (!get_option('snapshot_repo_amount')) {
	update_option('snapshot_repo_amount', '10');
}

// displays the page content for the admin submenu
function snapshot_home() {

// Direct calls to this file are Forbidden when core files are not present
// Thanks to Ed from ati-pro.com for this  code 
// @since 2.1

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

    // variables for the field and option names 
    $opt_name = 'snapshot_ftp_host';
    $opt_name2 = 'snapshot_ftp_user';
    $opt_name3 = 'snapshot_ftp_pass';
    $opt_name4 = 'snapshot_ftp_subdir';
	$opt_name5 = 'snapshot_ftp_prefix';
	$opt_name6 = 'snapshot_add_dir1';
	$opt_name7 = 'snapshot_auto_interval';
	$opt_name8 = 'snapshot_auto_email';
	
    $hidden_field_name = 'snapshot_ftp_hidden';
    $hidden_field_name2 = 'snapshot_backup_hidden';
    $hidden_field_name3 = 'snapshot_check_repo';
    $data_field_name = 'snapshot_ftp_host';
    $data_field_name2 = 'snapshot_ftp_user';
    $data_field_name3 = 'snapshot_ftp_pass';
    $data_field_name4 = 'snapshot_ftp_subdir';
	$data_field_name5 = 'snapshot_ftp_prefix';
	$data_field_name6 = 'snapshot_add_dir1';
	$data_field_name7 = 'snapshot_auto_interval';
	$data_field_name8 = 'snapshot_auto_email';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );
    $opt_val2 = get_option ($opt_name2 );
    $opt_val3 = get_option ($opt_name3 );
    $opt_val4 = get_option ($opt_name4 );
	$opt_val5 = get_option ($opt_name5 );
	$opt_val6 = get_option ($opt_name6 );
	$opt_val7 = get_option ($opt_name7 );
	$opt_val8 = get_option ($opt_name8 );


    // reset working directory to WP root
    // chdir('../');
	chdir (ABSPATH);
	
	// @since 1.6
	// let's include some subroutines - doesn't work yet
include plugin_dir_path(__FILE__).'includes/test-ftp.php';
	
/* 
 * @since 1.5
 * ADDITIONAL BACKUP SETTINGS
 */
 
    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name3 ]) && $_POST[ $hidden_field_name3 ] == 'Y' ) {
    // Read their posted value
    $opt_val6 = trim($_POST[ $data_field_name6 ]);
	// Save the posted value in the database
    update_option( $opt_name6, $opt_val6 );
	// Put a "settings updated" message on the screen
?>
<div class="updated"><p><strong><?php echo 'Your additional directory has been saved.'; ?></strong></p></div>
<?php
    }
/*
 * @since 1.0
 * FTP FORM SETTINGS
 */
	
    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
    $opt_val = trim($_POST[ $data_field_name ]);
    $opt_val2 = trim($_POST[ $data_field_name2 ]);
	$opt_val3 = trim($_POST[ $data_field_name3 ]);
    $opt_val4 = trim($_POST[ $data_field_name4 ]);
	$opt_val5 = trim($_POST[ $data_field_name5 ]);
        
	// Save the posted value in the database
    update_option( $opt_name, $opt_val );
    update_option( $opt_name2, $opt_val2 );
	update_option( $opt_name3, $opt_val3 );
	update_option( $opt_name4, $opt_val4 );
	update_option( $opt_name5, $opt_val5 );

     // Put a "settings updated" message on the screen
?>
<div class="updated"><p><strong><?php _e('Your FTP details have been saved.', 'snapshot-menu' ); ?></strong></p></div>
<?php
    }

/****************************************************
/ SNAPSHOT HOME AREA
/****************************************************/
// HEADER
// grab some functions
// @since 2.0
include plugin_dir_path( __FILE__ ) . 'includes/snapshot-functions.php';
snapshot_header('Welcome to Snapshot Backup');
?>
<table class="snapshot-backup" width=600 cellspacing=10>
<tr><td>
<p><strong>With this plugin you can create an  archive of your entire website and save it to an offsite location.</strong></p>
<p>Things couldn't be easier: </p>
<ul>
<li>&nbsp;&nbsp;&bull; enter your FTP details under Settings</li>
<li>&nbsp;&nbsp;&bull; click on CREATE NEW SNAPSHOT</li>
<li>&nbsp;&nbsp;&bull; rest assured you've backed your database AND contents with just one single click</li></ul>
<p>If you don't have an FTP account you can <a href="http://wpguru.co.uk/hosting/ftp/" target="_blank">sign up for one here</a> or download your snapshot from this server once it's done.</p>
</td></tr>
</table>
<p>Any questions? Check out the included <?php echo '<a href="' . plugins_url('readme.txt', __FILE__) .'"'; ?>" target="_blank">readme file</a> or visit the <a href="http://wpguru.co.uk/2011/02/snapshot-backup/" target="_blank">Snapshot Backup Website</a>. Have fun!

<?php

 if( isset($_POST[ $hidden_field_name2 ]) && $_POST[ $hidden_field_name2 ] == 'Y' ) {

// call main snapshot function 
do_the_snapshot();

} // end if

?>
</p>
<form name="form2" method="post" action="">
  <input type="hidden" name="<?php echo $hidden_field_name2; ?>" value="Y">
<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Create New Snapshot') ?>" />
</p>
</form>
<hr />

<p>
  <?php
// call Recent Download option
if (get_option('snapshot_latest')){
include plugin_dir_path(__FILE__).'includes/download-recent.php';
}
// call FTP Details form
// include plugin_dir_path(__FILE__).'includes/ftp-form.php';

// call Backup Settings
// include plugin_dir_path(__FILE__).'includes/settings.php';

// call footer
snapshot_footer();


} // end of function snapshot_home

////////////////////
// MANAGE SNAPSHOTS
////////////////////
function snapshot_manage_repo(){
	include plugin_dir_path( __FILE__ ) . 'includes/snapshot-functions.php';

	snapshot_header('Manage Snapshots');
	include plugin_dir_path(__FILE__).'includes/check-repo.php';
	
snapshot_footer();
} // end of function manage_repo

//////////////////////
// FTP DETAILS PAGE
/////////////////////
function snapshot_ftp_details(){
	// grab functions
	include plugin_dir_path( __FILE__ ) . 'includes/snapshot-functions.php';
	// call header
	snapshot_header ('FTP Details');
    // call FTP Details form
    include plugin_dir_path(__FILE__).'includes/ftp-form.php';
    // call Backup Settings
    // include plugin_dir_path(__FILE__).'includes/settings.php';
    // call footer
    snapshot_footer();
} // end of function snapshot_ftp_details

///////////////////////////
// SETUP AUTOMATION PAGE
//////////////////////////
function snapshot_automation(){
	
	include plugin_dir_path(__FILE__).'includes/automation.php';
	
} // end of function snapshot_automation

/////////////////////////
// HELP and CODEX PAGE
////////////////////////
function snapshot_codex(){
	include plugin_dir_path( __FILE__ ) . 'includes/snapshot-functions.php';
	// call header
	snapshot_header ('Help and Documentation');
	?>
<ul>
  <li>Newsletter Signup (need to create form)</li>
  <li>WordPress Forum (I don't get notified)</li>
  <li>Snapshot Homepage</li>
  <li>Donation Link</li>
  <li>Usage Video</li>
  <li>FAQ re FTP Details</li>
  <li>Link to Dev Diary (or RSS feed)</li>
  <li>Disclaimer</li>
  </ul>
<?php
// call footer
snapshot_footer();
} // end of function snapshot_codex

/////////////////////////////////////
// DEV SECTION (for me to play with)
/////////////////////////////////////

function dev_section() {
	include plugin_dir_path( __FILE__ ) . 'includes/snapshot-functions.php';
	// include plugin_dir_path( __FILE__ ) . 'includes/test-ftp.php';
	snapshot_header('Testing Testing');
	echo "DEV SECTION - testing stuff<br />";
        
        echo "Let's investigate the autodelete function:<br><br>";
snapshot_autodelete();

echo '<br>';

echo "<br><br> the end";
	
} // end of function dev_section

?>
