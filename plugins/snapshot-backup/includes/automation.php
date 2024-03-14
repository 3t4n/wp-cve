<?php

// Direct calls to this file are Forbidden when core files are not present
// Thanks to Ed from ait-pro.com for this  code 
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

// 

// read in values from the database
	$opt_name7 = 'snapshot_auto_interval';
	$opt_name8 = 'snapshot_auto_email';
	$opt_name9 = 'snapshot_repo_amount';
	
	$data_field_name7 = 'snapshot_auto_interval';
	$data_field_name8 = 'snapshot_auto_email';
	$data_field_name9 = 'snapshot_repo_amount';
	
	$opt_val7 = get_option ($opt_name7 );
	$opt_val8 = get_option ($opt_name8 );
	$opt_val9 = get_option ($opt_name9 );
	$hidden_field_name4 = 'snapshot_auto_hidden';

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name4 ]) && $_POST[ $hidden_field_name4 ] == 'Y' ) {
    // Read their posted value
    $opt_val7 = trim($_POST[ $data_field_name7 ]);
	$opt_val8 = trim($_POST[ $data_field_name8 ]);
	$opt_val9 = trim($_POST[ $data_field_name9 ]);
	// Save the posted value in the database
    update_option( $opt_name7, $opt_val7 );
    update_option( $opt_name8, $opt_val8 );
	update_option( $opt_name9, $opt_val9 );
	
	// if the option is enabled and not already scheduled lets schedule it
	if ( get_option('snapshot_auto_interval') != 'never' && !wp_next_scheduled( 'snapshot_automation' ) ) {
	
		//schedule the event to run at interval
		// >> same trick as activation in main file <<
		wp_schedule_event( time(), 'snapshot_interval', 'snapshot_automation' );
	// if the option is NOT enabled and scheduled lets unschedule it
	} elseif ( $snapshot_auto_interval == 'never' && wp_next_scheduled( 'snapshot_automation' ) ) {	
		//get time of next scheduled run
		$timestamp = wp_next_scheduled( 'snapshot_automation' );
		//unschedule custom action hook
		wp_unschedule_event( $timestamp, 'snapshot_automation' );
	} // end if
	
	// Put a "settings updated" message on the screen
?>
<div class="updated"><p><strong><?php _e('Your automation settings have been saved.', 'snapshot-automation' ); ?></strong></p></div>
<p>
  <?php
	} // end if
	
	include plugin_dir_path( __FILE__ ) . 'snapshot-functions.php';
	snapshot_header('Setup Automation');
	?>
  <strong>You can 
    use Snapshot Backup to create Snapshots in the background automatically. </strong></p>
<p>For this feature to work Snapshot Backup relies on WordPress' Scheduled Tasks, <br />
which in turn rely on your site being visited every once in a while. </p>
<p>Live production sites with 50+ hits per day will work fine, but on fresh test sites with <br />
  blocked search engines the automation may appear unreliable (due to lack traffic) . </p>
<p>You can help this along by using a dedicated Cron Job.

<?php
/*
 * CREATE YOUR OWN SCHEDULES
 * *************************
 * If you'd like to add your own intervals here, please follow these instructions.
 * Snapshot Backup adds its own WP Cron Schedule in seconds. Say you'd like to backup every 8 hours,
 * you'd get out your calculator and hack in 60 seconds times 60 minutes times 8 hours
 * 60 * 60 * 8 = 28800 seconds
 * Then you add this line to the bottom of the select menu (or replace at your own leisure):
 * 
 * <option value="28800" <?php if ($opt_val7 == "28800") echo 'selected'; ?>>every eight hours</option>
 *
 * Save this file, select your new drop-down option and you're good to go.
 */
?>
</p>
<form method="post" action="">
  <input type="hidden" name="<?php echo $hidden_field_name4; ?>" value="Y">
    <label><strong>How often would you like to create backups?</strong> 
    <select name="<?php echo $data_field_name7; ?>">
    <option value="never" <?php if ($opt_val7 == "never") echo 'selected'; ?>>I don't want to use automation </option>
    <option value="3600" <?php if ($opt_val7 == "3600") echo 'selected'; ?>>every hour</option>
    <option value="43200" <?php if ($opt_val7 == "43200") echo 'selected'; ?>>every twelve hours</option>
    <option value="86400" <?php if ($opt_val7 == "86400") echo 'selected'; ?>>once every day</option>
    <option value="604800" <?php if ($opt_val7 == "604800") echo 'selected'; ?>>once every week</option>
    <option value="1209600" <?php if ($opt_val7 == "1209600") echo 'selected'; ?>>once every two weeks</option>
    <option value="120" <?php if ($opt_val7 == "120") echo 'selected'; ?>>every two minutes (good for testing)</option>
    <!--
    <option value="600" <?php // if ($opt_val7 == "600") echo 'selected'; ?>>every ten minutes (good for testing)</option>
        -->
    </select>
    </label>
    <p><input type="submit" name="button" id="button" class="button-primary" value="Save Automation Settings" /></p>
<br />
<hr />
<br />
<h2 class="wrap">Auto Delete Option</h2>
<p>To manage space efficiently, and to avoid filling up your FTP server, Snapshot Backup can keep a certain amount <br />
  of &quot;rolling backups&quot; in your repository. When a new Snapshot is written the oldest one is deleted. </p>
<p> This amount must be at least 1 (obviously) and is limited only by the space on your FTP server.</p>
<p><strong>How many Snapshots would you like to keep in your Repository?</strong></p>
<p>
  <input type="text" size="2" name="<?php echo $data_field_name9; ?>" value="<?php echo $opt_val9; ?>">
</p>
<p><em>Defaults to 10 if empty. Set to anything over 100 to disable this feature.</em></p>
    <p><input type="submit" name="button" id="button" class="button-primary" value="Save Auto-Delete Options" /></p>
  <br />
<hr />
  <br />
  <h2 div class="wrap">Email Notifications</h2>
<p>Would you like  an email every time a Snapshot is being created?</p>


    <input type="text" name="<?php echo $data_field_name8; ?>" value="<?php echo $opt_val8; ?>" size="45" />


<p><em>Leave blank if you don't want to use this feature.</em></p>

    <p><input type="submit" name="button" id="button" class="button-primary" value="Save Email" /></p>

</form>
<?php
// call footer
snapshot_footer();
?>