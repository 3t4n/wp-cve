<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// dashboard item - getting started
function etimeclockwp_dashboard_load_getting($etimeclockwp_dashboard_array) {

	$getting_started_array = array(
		'getting started' => array(
			'title'  				=> __( 'Getting Started Guide', 'etimeclockwp' ),
			'body'  				=> __( "
			
1. On any page or post, you can place the shortcode [timeclock]. This will display a timeclock.  <br /> <br  /> Note: You should only have one timeclock shortcode per page or post. <br /><br />

2. Create a new user on the <a target='_blank' href='edit.php?post_type=etimeclockwp_users'> Users page</a>. <br /><br />

3. When the user enters their username and password (which is setup on the Users page) on the timeclock, there time will be recorded. <br /><br />

4. You can view the users activity on the <a target='_blank' href='edit.php?post_type=etimeclockwp_clock'>Activity page</a>. <br /><br />

5. You may wish to change the datetime format displayed in the timeclock and on the Activity page. You can do this on the General Tab -> <a href='admin.php?page=etimeclockwp_settings_page&tab=13'>Date & Time Format section</a> <br /><br />

			", 'etimeclockwp' ),
		),
	);

	return array_merge($etimeclockwp_dashboard_array,$getting_started_array);
}
add_filter( 'etimeclockwp_dashboard_array','etimeclockwp_dashboard_load_getting');



// dashboard item - pro version 
function etimeclockwp_dashboard_pro_version($etimeclockwp_dashboard_array) {

	$pro_version_array = array(
		'pro_version' => array(
			'title'  				=> __( 'Pro Version', 'etimeclockwp' ),
			'body'  				=> __( "
			
			You may be interested in this Pro version of this plugin. Features include: <br />
			
			<div class='show-bullets'>
				<ul>
					<li>Add a New Activity Day</li>
					<li>Add a New Clock Event for a Specific Day</li>
					<li>Export Activity to CSV File</li>
					<li>See an admin notice when a User Forgets to Clock In / Out</li>
					<li>Time Clock can be used as a Widget</li>
					<li>Admin Metrics</li>
					<li>See how many users are Currently Working</li>
					<li>Link a WordPress account to a Time Clock Account so users don't have to login</li>
					<li>Option to Disable Clock Button after user logs activity</li>
					<li>Support Further Development of this plugin</li>
				</ul>
				
				<center>
					<a target='_blank' href='https://wpplugin.org/downloads/time-clock-pro/?utm_source=plugin-settings-page&utm_medium=plugin&utm_campaign=etimeclockwp_settings_page' class='button-primary' style='font-size: 17px;line-height: 28px;height: 32px;'>Learn More</a>
				</center>
			</div>
			
			", 'etimeclockwp' ),
		),
	);

	return array_merge($etimeclockwp_dashboard_array,$pro_version_array);
}
add_filter( 'etimeclockwp_dashboard_array','etimeclockwp_dashboard_pro_version');