<?php
/*
 This file is part of Site Launcher.
 Site Launcher is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.
 Site Launcher is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Site Launcher.  If not, see <http://www.gnu.org/licenses/>.
 */




// "=====================================================
// 		set some values
// "=====================================================


$fonts = array(
	'robotoslab'		=>	'"Roboto Slab", serif',
	'sans'			=>	'"Open Sans", sans-serif',
	'ubuntu'		=>	'"Ubuntu", sans-serif',
	'raleway'		=>	'"Raleway", sans-serif',
	'roboto'		=>	'"Roboto", sans-serif',
	'philosopher'		=>	'"Philosopher", sans-serif',
	'playfair'		=>	'"Playfair Display", serif',
	'poiret'		=>	'"Poiret One", cursive',
	'orbitron'		=>	'"Orbitron", sans-serif',
	'patua'			=>	'"Patua One", cursive',
	'limelight'		=>	'"Limelight", cursive',
	'elite'			=>	'"Special Elite", cursive',
	'indie'			=>	'"Indie Flower", cursive',
	'griffy'		=>	'"Griffy", cursive'
);

// "=====================================================
// 		process action and redirect url
// "=====================================================
// get $_POST data

if ( isset( $_POST[ 'action' ] ) )
{
	update_option( 'site_launcher_action',  $_POST[ 'action' ]);
}

if ( isset( $_POST[ 'redirect_url' ] ) )
{
	$url = $_POST[ 'redirect_url' ];
	if (strpos( $_POST[ 'redirect_url' ], 'http' ) !== 0 ) $url = 'http://'.$_POST[ 'redirect_url' ];
	$url = filter_var( $url, FILTER_VALIDATE_URL );
	update_option( 'site_launcher_redirect_url', $url );
}

if ( isset( $_POST[ 'action_suspended' ] ) )
{
	update_option( 'site_launcher_action_suspended',  $_POST[ 'action_suspended' ]);
}

if ( isset( $_POST[ 'redirect_url_suspended' ] ) )
{
	$url = $_POST[ 'redirect_url_suspended' ];
	if (strpos( $_POST[ 'redirect_url_suspended' ], 'http' ) !== 0 ) $url = 'http://'.$_POST[ 'redirect_url_suspended' ];
	$url = filter_var( $url, FILTER_VALIDATE_URL );
	update_option( 'site_launcher_redirect_url_suspended', $url );
}


// "=====================================================
// 		process display options
// "=====================================================

// initialize display options to pre-set defaults
$display_options = array(
	'background_color'		=>	'#5173a1',
	'background_image'		=>	'under_construction__david_alexander_straight.jpg',
	'message_text'			=>	'is under construction.',
	'fine_print'			=>	'',
	'show_message_box'		=>	'1',
	'text_color'			=>	'#111111',
	'message_box_width'		=>	'900px',
	'message_box_opacity'		=>	'0.7',
	'message_box_border'		=>	'3-d',
	'font'				=>	'"Roboto Slab", serif',
	'show_login'			=>	'1',
	'login_message'			=>	'Log in here to preview:',
	'background_color_suspended'	=>	'#000',
	'background_image_suspended'	=>	'night_ship__national_archives.jpg',
	'message_text_suspended'	=>	'has been suspended.',
	'fine_print_suspended'		=>	'',
	'show_message_box_suspended'	=>	'1',
	'text_color_suspended'		=>	'#fff',
	'message_box_width_suspended'	=>	'900px',
	'message_box_opacity_suspended'	=>	'0.3',
	'message_box_border_suspended'	=>	'3-d',
	'font_suspended'		=>	'"Roboto Slab", serif',
	'show_login_suspended'		=>	'0',
	'login_message_suspended'	=>	'Web master log in here:'
);

foreach ( $display_options as $display_option_name=>$display_option )
{
	// get $_POST data
	if ( isset( $_POST[ $display_option_name ] ) )
	{
		// make sure width is in px or %
		if ( strpos( $display_option_name, 'width' ) !== false ) 
		{
			$width  = esc_attr( $_POST[ $display_option_name ] );
			if ( is_numeric( $width ) && $width <= 100 && $width > 0 ) $display_options[ $display_option_name ] = $width.'%';
			elseif ( is_numeric( $width ) && $width > 100 ) $display_options[ $display_option_name ] =  $width.'px';
			elseif ( ! is_numeric( $width ) && strpos( $width, 'px' ) !== false  && strpos( $width, '%' ) !== false ) $display_options[ $display_option_name ] = '900px';
			else $display_options[ $display_option_name ] = $width;
		} 
		elseif ( strpos( $display_option_name, 'font' ) !== false )
		{
			 $font  = esc_attr( $_POST[ $display_option_name ] );
			 $display_options[ $display_option_name ] = $fonts[ $font ];
		}
		elseif  ( strpos( $display_option_name, 'fine_print' ) !== false )
		{
			$display_options[ $display_option_name ] = str_replace("'", '"', $_POST[ $display_option_name ]);
		}
		else
		{
			$display_options[ $display_option_name ] = esc_attr( $_POST[ $display_option_name ] );
		}
	}
	// or default to stored data
  	elseif ( $this->get_display_option( $display_option_name ) !== false )
  	{
		// for legacy installs, set background images to 'none'
		if ( strpos( $display_option_name, 'background_image' ) !== false )
		{
			$display_options[ $display_option_name ] = 'none';
		}
		$display_options[ $display_option_name ] = $this->get_display_option( $display_option_name );
	}
}

update_option( 'site_launcher_display_options', $display_options );


// "=====================================================
// 		process allowed users
// "=====================================================

$userID = get_current_user_id();

if ( isset( $_POST['allowed_admins'] ) )
{
	$allowed_admins = $_POST['allowed_admins'];
	// add current user!
	if ( ! in_array($userID, $allowed_admins ) ) $allowed_admins[] = $userID;
}
else
{
	$allowed_admins = $this->get_allowed_admins();
}
update_option( 'site_launcher_allowed_admins', $allowed_admins );



// "=====================================================
// 		process demoted users list for site suspended mode
// "=====================================================

if ( isset( $_POST['demoted_users'] ) )
{
	$demoted_users = $_POST['demoted_users'];
	update_option( 'site_launcher_users_to_demote', $demoted_users );
}

// "=====================================================
// 		process IP addresses
// "=====================================================

if ( isset( $_POST['ip_address'] ) ){
	$ip = $_POST['ip_address'];
	$ip = long2ip( ip2long( $ip ) );

	if ( is_numeric( ip2long( $ip ) ) && ($ip != '0.0.0.0' ) )
	{
		$ip_array = get_option( 'site_launcher_ip_whitelist' );

		if ( !$ip_array ){
			$ip_array = array();
		}

		$ip_array[] = $ip;

		$ip_array = array_unique( $ip_array );

		update_option( 'site_launcher_ip_whitelist', $ip_array );
	}
}

if ( isset( $_POST['remove_selected_ip_btn'] ) )
{
	if ( isset( $_POST['ip_whitelist'] ) )
	{
		$ip_array = get_option( 'site_launcher_ip_whitelist' );

		if ( !$ip_array)
		{
			$ip_array = array();
		}

		unset( $ip_array[ $_POST['ip_whitelist'] ] );
		$ip_array = array_values( $ip_array );
		update_option( 'site_launcher_ip_whitelist', $ip_array );
	} 
}

if ( isset( $_POST['ip_address_suspended'] ) )
{
	$ip = $_POST['ip_address_suspended'];
	$ip = long2ip( ip2long( $ip ) );

	if ( is_numeric( ip2long( $ip ) ) && ($ip != '0.0.0.0' ) )
	{
		$ip_array = get_option( 'site_launcher_ip_whitelist_suspended' );

		if ( !$ip_array )
		{
			$ip_array = array();
		}

		$ip_array[] = $ip;

		$ip_array = array_unique( $ip_array );

		update_option( 'site_launcher_ip_whitelist_suspended', $ip_array );
	}
}

if ( isset( $_POST['remove_selected_ip_btn_suspended'] ) ){
	if ( isset( $_POST['ip_whitelist_suspended'] ) ){
		$ip_array = get_option( 'site_launcher_ip_whitelist_suspended' );

		if ( !$ip_array){
			$ip_array = array();
		}

		unset( $ip_array[ $_POST['ip_whitelist_suspended'] ] );
		$ip_array = array_values( $ip_array );
		update_option( 'site_launcher_ip_whitelist_suspended', $ip_array );
	} 
}


// "=====================================================
// 		process launch and suspend times
// "=====================================================

$current_time = current_time( 'timestamp' ); // use the WordPress blog time function


$in_thirty_days = $current_time + (30 * 24 * 60 * 60);

if ( isset( $_POST['launch_date'] ) && ( $_POST['launch_date'] == 'never' ) )
{
	$launch_julian = 'never'; //set launch date to never
}
elseif ( isset( $_POST['mm'] ) ||  isset( $_POST['jj'] ) ||  isset( $_POST['aa'] ) || isset( $_POST['hh'] ) ||  isset( $_POST['mn'] ) ||  isset( $_POST['ampm']) )
{
	$mm = $_POST['mm']; //month
	$jj = $_POST['jj']; //day
	$aa = $_POST['aa']; //year
	$hh = $_POST['hh']; //hour
	$mn = $_POST['mn']; //minute
	$ampm = $_POST['ampm']; //am or pm
	
	if ( (int) $jj > 31 || (int) $jj < 1 ) $jj = 1;
	if ( strlen( $jj ) == 1 ) $jj = '0'.$jj;
	
	if ( strlen( $aa ) == 2 ) $aa = '20'.$aa;
	
	if ( $ampm == 'pm' ) $hh = $hh + 12;
	if ( (int) $hh > 24 || (int) $hh < 0 ) $hh = 0;
	if ( strlen( $hh ) == 1 ) $hh = '0'.$hh;
	
	if ( (int) $mn > 59 || (int) $mn < 0 ) $mn = 0;
	if ( strlen( $mn ) == 1 ) $mn = '0'.$mn;
	
	$launch_datetime = $aa.':'.$mm.':'.$jj.' '.$hh.':'.$mn.':00'; //EXIF
	$launch_julian = strtotime( $launch_datetime );
	if ( $launch_julian <= $current_time ) $launch_julian = $in_thirty_days;
}
// or default to stored data
else
{
	$launch_julian = $this->get_site_launch_date();
}

update_option( 'site_launcher_launch_date', $launch_julian );


if ( isset( $_POST['suspend_date'] ) && ( $_POST['suspend_date'] == 'now' ) )
{
	$suspend_julian = 'now';
}
elseif ( isset( $_POST['mm_s'] ) ||  isset( $_POST['jj_s'] ) ||  isset( $_POST['aa_s'] ) || isset( $_POST['hh_s'] ) ||  isset( $_POST['mn_s'] ) ||  isset( $_POST['ampm_s']) )
{
	$mm = $_POST['mm_s']; //month
	$jj = $_POST['jj_s']; //day
	$aa = $_POST['aa_s']; //year
	$hh = $_POST['hh_s']; //hour
	$mn = $_POST['mn_s']; //minute
	$ampm = $_POST['ampm_s']; //am or pm
	
	if ( (int) $jj > 31 || (int) $jj < 1 ) $jj = 1;
	if ( strlen( $jj ) == 1 ) $jj = '0'.$jj;
	
	if ( strlen( $aa ) == 2 ) $aa = '20'.$aa;
	
	if ( $ampm == 'pm' ) $hh = $hh + 12;
	if ( (int) $hh > 24 || (int) $hh < 0 ) $hh = 0;
	if ( strlen( $hh ) == 1 ) $hh = '0'.$hh;
	
	if ( (int) $mn > 59 || (int) $mn < 0 ) $mn = 0;
	if ( strlen( $mn ) == 1 ) $mn = '0'.$mn;
	
	$suspend_datetime = $aa.':'.$mm.':'.$jj.' '.$hh.':'.$mn.':00'; //EXIF
	$suspend_julian = strtotime( $suspend_datetime );

	if ( $suspend_julian <= $current_time ) $suspend_julian = $in_thirty_days;
	
}
// or default to stored data
else
{
	$suspend_julian = $this->get_site_suspend_date();
}

update_option( 'site_launcher_suspend_date', $suspend_julian );




// "=====================================================
// 		process mode AFTER checking/setting date inputs!
// "=====================================================
if ( isset( $_POST['mode']) )
{
	$mode = $_POST['mode'];
}
else
{
	$mode = $this->get_plugin_mode(); //get status, not mode
}
if ( $mode !== 'coming_soon' ) update_option( 'site_launcher_launch_date', 'now' );
if ( $mode !== 'site_suspended'  && $mode != 'site_scheduled_for_suspension' ) update_option( 'site_launcher_suspend_date', 'never' );

if ( get_option( 'site_launcher_users_have_been_demoted' ) === false ) update_option( 'site_launcher_users_have_been_demoted', 'no' );
if ( $mode == 'live' )
{

	if ( get_option( 'site_launcher_users_have_been_demoted' ) == 'yes' ) 
	{
		$user_id_role_strings = get_option( 'site_launcher_users_to_demote' );
		$subscriber = get_role( 'subscriber' );
		$subscriber->add_cap( 'read' );
		if ( is_array( $user_id_role_strings ) )
		{
			foreach ( $user_id_role_strings as $id_role )
			{
				$bits = explode( '_', $id_role );
				$user_id = $bits[0];
				$role = $bits[1];
				wp_update_user( array( 'ID' => $user_id, 'role' => $role ) );
			}
		}
		update_option( 'site_launcher_users_have_been_demoted', 'no' );
	}
}

update_option( 'site_launcher_mode', $mode );


//set displayed suspend and launch dates for thirty days from now, if not already set
if ( is_numeric( $this->get_site_launch_date() ) ) $show_launch_julian = $launch_julian; else $show_launch_julian = $in_thirty_days;
if ( is_numeric( $this->get_site_suspend_date() ) ) $show_suspend_julian = $suspend_julian; else $show_suspend_julian = $in_thirty_days;


?>


<noscript>
	<div class='updated' id='javascriptwarn'>
		<p><?php _e( 'JavaScript appears to be disabled in your browser. For this plugin to work correctly, please enable JavaScript or switch to a more modern browser.', 'site-launcher' );?></p>
	</div>
</noscript>
<div class="wrap">

	<form method="post"
		action="<?php echo $GLOBALS['PHP_SELF'] . '?page=' . $this->main_options_page; ?>">
		<h2><?php _e( 'Site Launcher', 'site-launcher' );?></h2><br />
		<div class="site-launcher-box">
			<h2 style="font-weight:bold;"><?php echo $this->get_status_message(); ?></h2>
		</div>
		<table>
			<tr>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e( 'Select Mode', 'site-launcher' );?></span>
						</legend>
						 <label title="Launch Website" style="font-weight:bold;font-size:16px;" >
						 <input type="radio" name="mode" id="mode0" value="live" <?php if ( $this->get_plugin_mode() == 'live' ) echo ' checked="checked"';  ?> >
							<?php if ( $this->get_plugin_mode() == 'live' ) {
								_e( 'Website is Live', 'site-launcher' );
							} elseif ( $this->get_plugin_mode() == 'coming_soon' ) {
								_e( 'Launch Website', 'site-launcher' );
							} else  {
								_e( 'Unsuspend Website', 'site-launcher' );
							}
							?>
						</label><br />
						
						<label title="Coming Soon Mode">
						<input type="radio" name="mode" id="mode1" value="coming_soon" <?php if ( $this->get_plugin_mode() == 'coming_soon' ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( '"Coming Soon" mode.', 'site-launcher' );?>
						</label><br /> 
						
						<label title="Site Suspended Mode">
						<input type="radio" name="mode" id="mode2" value="site_suspended" <?php if ( $this->get_plugin_mode() == 'site_suspended' || $this->get_plugin_mode() == 'site_scheduled_for_suspension') { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( '"Site Suspended" mode.', 'site-launcher' );?>
						</label><br /> 

					</fieldset>
				</td>
			</tr>
		</table>
		<div id="coming-soon-mode" class="site-launcher-box" <?php if ( ! ( $this->get_plugin_mode() == 'coming_soon' ) ) { echo ' style="display:none;"'; } ?> >
		<table>
			<tr>
				<td colspan="2">
					<h3 style="font-size:22px;"><?php _e( 'Coming Soon Settings', 'site-launcher' );?></h3>
				</td>
			</tr>
		</table>
		<table class="admin-table divider">
			<tr>
				<td colspan="2">
				      <h3><?php _e( 'Launch Mode', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr valign="top">
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e( 'Login Form', 'site-launcher' );?></span>
						</legend>
						<label title="No launch date">
						  <input type="radio" name="launch_date" id="hidelaunchdate"  value="never" <?php if ( $this->get_site_launch_date() == 'never' || $this->get_site_launch_date() == 'now') echo ' checked="checked"';  ?> >&nbsp;<?php _e( 'Launch when I select the "Launch Website" option', 'site-launcher' );?>
						</label><br /> 
						<label title="Set launch date">
						  <input type="radio" name="launch_date" id="showlaunchdate" value="automatic"<?php if ( is_numeric ( $this->get_site_launch_date() ) ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Launch on this date and time:', 'site-launcher' );?>
						</label>
					</fieldset>
				  <div id="timestampdiv" class="" <?php if ( $this->get_site_launch_date() == 'never' || $this->get_site_launch_date() == 'now') echo ' style="display:none;"'; ?> ><div class="timestamp-wrap">
				  <?php 
					$launch_month = date('m', $show_launch_julian );
					$launch_day = date('d', $show_launch_julian );
					$launch_year = date('Y', $show_launch_julian );
					$launch_hour = date('H', $show_launch_julian );
					$launch_minute = date('i', $show_launch_julian );
					$launch_ampm = 'am';
					if ( $launch_hour > 12 )
					{
						$launch_hour = $launch_hour - 12;
						$launch_ampm = 'pm';
					}
				  ?>
				  <select id="mm" name="mm">
					<option value="01" <?php if ( $launch_month == '01' ) echo 'selected="selected"';?> >01-Jan</option>
					<option value="02" <?php if ( $launch_month == '02' ) echo 'selected="selected"';?> >02-Feb</option>
					<option value="03" <?php if ( $launch_month == '03' ) echo 'selected="selected"';?> >03-Mar</option>
					<option value="04" <?php if ( $launch_month == '04' ) echo 'selected="selected"';?> >04-Apr</option>
					<option value="05" <?php if ( $launch_month == '05' ) echo 'selected="selected"';?> >05-May</option>
					<option value="06" <?php if ( $launch_month == '06' ) echo 'selected="selected"';?> >06-Jun</option>
					<option value="07" <?php if ( $launch_month == '07' ) echo 'selected="selected"';?> >07-Jul</option>
					<option value="08" <?php if ( $launch_month == '08' ) echo 'selected="selected"';?> >08-Aug</option>
					<option value="09" <?php if ( $launch_month == '09' ) echo 'selected="selected"';?> >09-Sep</option>
					<option value="10" <?php if ( $launch_month == '10' ) echo 'selected="selected"';?> >10-Oct</option>
					<option value="11" <?php if ( $launch_month == '11' ) echo 'selected="selected"';?> >11-Nov</option>
					<option value="12" <?php if ( $launch_month == '12' ) echo 'selected="selected"';?> >12-Dec</option>
				  </select>
				  <input id="jj" name="jj" value="<?php echo $launch_day; ?>" size="2" maxlength="2" autocomplete="off" type="text">, <input id="aa" name="aa" value="<?php echo $launch_year; ?>" size="4" maxlength="4" autocomplete="off" type="text"> @ <input id="hh" name="hh" value="<?php echo $launch_hour; ?>" size="2" maxlength="2" autocomplete="off" type="text"> : <input id="mn" name="mn" value="<?php echo $launch_minute; ?>" size="2" maxlength="2" autocomplete="off" type="text">
				  <select id="ampm" name="ampm">
					<option value="am" <?php if ( $launch_ampm == 'am' ) echo 'selected="selected"';?> >am</option>
					<option value="pm" <?php if ( $launch_ampm == 'pm' ) echo 'selected="selected"';?> >pm</option>
				  </select><br />
				  <?php _e( 'Make sure your timezone is set correctly in Settings->General', 'site-launcher' );?>
				  </div></div>
				</td>
			</tr>
		</table>
		<table class="admin-table divider">
		
			<tr>
				<td colspan="2">
				      <h3><?php _e( 'Action', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e( 'Select Action', 'site-launcher' );?></span>
						</legend>
						
						<label title="Redirect to a different URL">
						<input type="radio" name="action" id="redirect" value="redirect" <?php if ( get_option( 'site_launcher_action' ) == 'redirect' ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Redirect to a different URL.', 'site-launcher' );?>
						</label><br /> 
						<?php //echo get_option( 'site_launcher_action' ); ?>
						<label title="Show Coming Soon page">
						<input type="radio" name="action" id="showpage" value="show_page" <?php if ( get_option( 'site_launcher_action' ) == 'show_page' || get_option( 'site_launcher_action' ) === false ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Show "Coming Soon" page.', 'site-launcher' );?>
						</label><br /> 

					</fieldset>
				</td>
			</tr>
		</table>
		
		<table class="admin-table" id="setredirect" <?php if ( get_option( 'site_launcher_action' ) == 'show_page' || get_option( 'site_launcher_action' ) === false ) echo 'style="display:none;"'; ?> >
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'URL to redirect to:', 'site-launcher' );?></th>
				<td style="padding-left:5px;">
				<?php if ( ! get_option( 'site_launcher_redirect_url' ) &&  get_option( 'site_launcher_action' ) == 'redirect' ) echo '<span style="color:#bf0000;">Please enter a valid URL.</span><br />';
				$url = get_option( 'site_launcher_redirect_url' );
				if ( ! is_string( $url ) ) $url = '';
				?>
				<input style="width:320px;" type="text" name="redirect_url" id="redirect_url" value="<?php echo $url; ?>" />
				</td>
			</tr>
		</table>
		
		<table class="admin-table divider" id="comingsoonpage" <?php if ( get_option( 'site_launcher_action' ) == 'redirect' ) echo 'style="display:none;"'; ?> >
		
			<tr>
				<td colspan="2">
				      <h3 style="font-size:20px;"><?php _e( 'Coming Soon Page Settings', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="dotted-divider">
				      <h3><?php _e( 'Page Background', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr>
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Background Color', 'site-launcher' ) ?>:</th>
				<td style="padding-left:5px;">
				<input style="width:70px;" type="text" name="background_color" class="background_color" value="<?php echo $this->get_display_option( 'background_color' ); ?>" data-default-color="#92b7ce" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Background Image:', 'site-launcher' );?></th><td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">
				To add your own background image (at least 1200px x 800px) upload it to the /images/full subdirectory of this plugin.<br />
				<?php
				$imagefiles = $this->get_filenames('full');
				$fullpathname = WP_PLUGIN_URL.'/'.$this->installed_folder.'/images/full/';
				$count = 0;
				foreach ( $imagefiles as $imagefile ) {
					$count++;
					$imagefilenicename = str_replace('__', ' - ',  $imagefile );
					$imagefilenicename = str_replace('_', ' ',  $imagefilenicename );
					$imagefilenicename = str_replace('.jpg', '',  $imagefilenicename );
					$imagefilenicename = str_replace('.png', '',  $imagefilenicename );
					$imagefilenicename = str_replace('.gif', '',  $imagefilenicename );
					$imagefilenicename = ucwords( $imagefilenicename );
				?>
					<?php if ( $count == 1 ) { ?>
					<label title="No Image">
					<input  type="radio" name="background_image" class="background_image" value="none" <?php if ( $this->get_display_option( 'background_image' ) == 'none' ) echo ' checked="checked"'; ?>/>&nbsp; <div style="display:inline-block;width:190px;background-color:<?php echo $this->get_display_option( 'background_color' ); ?>;"><img style="margin:5px 0;width:190px;height;100px;vertical-align:middle;" src="<?php echo $fullpathname.'transparent.gif'; ?>" /></div>
					</label>				
					<?php } ?>
					<label title="<?php echo $imagefilenicename; ?>">
					<input  type="radio" name="background_image" class="background_image" value="<?php echo $imagefile; ?>" <?php if ( $this->get_display_option( 'background_image' ) == $imagefile ) echo ' checked="checked"'; ?>/>&nbsp;<img style="margin:5px 0;width:190px;height;auto;vertical-align:middle;" src="<?php echo $fullpathname.$imagefile; ?>" /> 
					</label><?php if ( ($count + 1) % 3  == 0 ) echo '<br />'; ?>
				<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="height:15px;">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" class="dotted-divider">
				      <h3><?php _e( 'Message Box Content', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;"><label for="message_text"> <?php _e( 'Message Text:', 'site-launcher' );?> </label></th>
				<td><?php echo '<textarea rows="2" cols="42" name="message_text" id="message_text" class="regular-text">'.trim( $this->get_display_option( 'message_text' ) ).'</textarea>'; ?></td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:70px;text-align:left;"><label for="fine_print"> <?php _e( 'Fine Print:<br />(html allowed)', 'site-launcher' );?> </label></th>
				<td><?php echo '<textarea rows="4" cols="42" name="fine_print" id="fine_print" class="regular-text">'.trim( $this->get_display_option( 'fine_print' ) ).'</textarea>'; ?></td>
			</tr>
			<tr>
				<td colspan="2" style="height:15px;">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" class="dotted-divider">
				      <h3><?php _e( 'Message Box Style', 'site-launcher' );?></h3>
				</td>
			</tr>	
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;padding-top:8px;padding-bottom:8px;"><label > <?php _e( 'Show Message Box:', 'site-launcher' );?> </label></th>
				<td style="padding-top:8px;padding-bottom:8px;">
					<fieldset>
						<legend class="screen-reader-text">
						<span><?php _e( 'Message Box', 'site-launcher' );?></span>
						</legend>
						<label title="Show message box.">
						  <input type="radio" name="show_message_box" id="showmessagebox" value="1"<?php if ( $this->get_display_option( 'show_message_box' ) != '0') { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Show', 'site-launcher' );?>
						</label>&nbsp;&nbsp;
						<label title="Hide message box.">
						  <input type="radio" name="show_message_box" id="hidemessagebox" value="0"<?php if ( $this->get_display_option( 'show_message_box' ) == '0') { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Hide', 'site-launcher' );?>
						</label>
					</fieldset>
				</td>
			</tr>
			</table>
			<table id="showmessageboxdetails" style="margin-top:-15px;">
			<tr valign="top">
				<th scope="row" style="padding-top:15px;width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Text Color:', 'site-launcher' );?></th>
				<td style="padding-left:5px;padding-top:15px">
				<input style="width:70px;" type="text" name="text_color" class="text_color" value="<?php echo $this->get_display_option( 'text_color' ); ?>" data-default-color="#ffffff" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Font:', 'site-launcher' );?>
				</th>
				<td style="padding-left:5px;">
					<table style="margin-top:-2px;">
						<tr>
							<td >
								<select name="font">
								<?php foreach ( $fonts as $font_nickname=>$font_name ) {
									$fontbits = explode ( '"', $font_name );
									$font_nicename = $fontbits[1];
									?>
									<option value="<?php echo $font_nickname; ?>" <?php if ( $this->get_display_option( 'font' ) == $font_name ) echo ' selected="selected"'; ?> ><?php echo $font_nicename; ?></option>
								<?php } ?>
								</select>
							</td>
							<td style="padding-left:15px;">
								(<?php foreach ( $fonts as $font_nickname=>$font_name ) {
									$fontbits = explode ( '"', $font_name );
									$font_nicename = $fontbits[1];
									echo "<span style='font-family:".$font_name.";'>".$font_nicename."</span>, ";
									?>
								<?php } ?>)
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Message Box Width:', 'site-launcher' );?></th>
				<td style="padding-left:5px;">
				<input style="width:70px;" type="text" name="message_box_width" id="message_box_width" value="<?php echo $this->get_display_option( 'message_box_width' ); ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Message Box Opacity:', 'site-launcher' );?></th>
				<td style="padding-left:5px;">
					<table style="margin-top:-2px;">
						<tr>
							<td >
								<input style="width:40px;" type="text" name="message_box_opacity" id="message_box_opacity" value="<?php echo $this->get_display_option( 'message_box_opacity' ); ?>" />
							</td>
							<td style="padding-left:15px;">
								<?php _e( '1 = white, 0 = transparent', 'site-launcher' );?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;padding-top:4px;"><label for="message_box_border"> <?php _e( 'Message Box Border:', 'site-launcher' );?> </label></th>
				<td style="padding-top:4px;">
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e( 'Message Box Border', 'site-launcher' );?></span>
						</legend>
						<label title="None">
						  <input type="radio" name="message_box_border"  value="none"<?php if ( $this->get_display_option( 'message_box_border' ) =='none' ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'None', 'site-launcher' );?>
						</label>&nbsp;&nbsp;
						<label title="Simple">
						  <input type="radio" name="message_box_border"  value="simple"<?php if ( $this->get_display_option( 'message_box_border' ) == 'simple') { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Simple', 'site-launcher' );?>
						</label>&nbsp;&nbsp;
						<label title="3-D">
						  <input type="radio" name="message_box_border" value="3-d"<?php if ( $this->get_display_option( 'message_box_border' ) == '3-d' ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( '3-D', 'site-launcher' );?>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;padding-top:8px;padding-bottom:8px;"><label > <?php _e( 'Show Log-in Form:', 'site-launcher' );?> </label></th>
				<td style="padding-top:8px;padding-bottom:8px;">
					<fieldset>
						<legend class="screen-reader-text">
						<span><?php _e( 'Login Form', 'site-launcher' );?></span>
						</legend>
						<label title="Show log-in">
						  <input type="radio" name="show_login" id="showlogin" value="1"<?php if ( $this->get_display_option( 'show_login' ) == '1') { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Show', 'site-launcher' );?>
						</label>&nbsp;&nbsp;
						<label title="Hide log-in">
						  <input type="radio" name="show_login" id="hidelogin" value="0"<?php if ( $this->get_display_option( 'show_login' ) != '1') { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Hide', 'site-launcher' );?>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top" id="loginmessageinput" <?php if ( ! $this->get_display_option( 'show_login' )) echo ' style="display:none;"'; ?> >
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Log-in Message:', 'site-launcher' );?></th>
				<td style="padding-left:5px;">
				<input style="" type="text" name="login_message" id="login_message" value="<?php echo $this->get_display_option( 'login_message' ); ?>" />
				</td>
			</tr>

		</table>
		<table class="admin-table divider" style="margin-top:20px;">
			<tr>
				<td colspan="2">
					<h3 title="Display site as if launched for IP addresses on this list"><?php _e( 'IP Address Whitelist', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr>
				<td>
				Add a new IP address to the list by typing it in the box then clicking "Save Changes".<br /><br />
				<label><?php _e( 'IP Address:', 'site-launcher' );?> <input type="text" name="ip_address" id="ip_address" /> </label><a id="add_current_address_btn" style="cursor: pointer;"><?php _e( 'Get Current Address', 'site-launcher' );?></a><br />
				<img id="loading_current_address" class="hidden" src="<?php echo plugins_url( 'ajax-loader.gif' , __FILE__ ); ?>" />
				<?php $whitelist = get_option( 'site_launcher_ip_whitelist' );
				if ( $whitelist ):?> 
				  <select size="4" id="ip_whitelist" name="ip_whitelist" style="width: 250px; height: 100px;margin-top: 20px;">
					<?php for( $i = 0; $i < count( $whitelist ); $i++ ):?>
						<option id="<?php echo $i; ?>" value="<?php echo $i;?>">
						<?php echo $whitelist[ $i ];?>
						</option>
						<?php endfor;?>
				  </select><br />
				  <?php if ( $whitelist ): ?> 
				  <input type="submit" value="<?php _e( 'Remove Selected IP Address', 'site-launcher' ); ?>" name="remove_selected_ip_btn" id="remove_selected_ip_btn" /> <br />
				  <?php endif; ?>
				<?php endif; ?> 
				</td>
			</tr>
		</table>
		</div>
		<div id="site-suspended-mode" class="site-launcher-box" <?php if ( ! ( $this->get_plugin_mode() == 'site_suspended'  || $this->get_plugin_mode() == 'site_scheduled_for_suspension' ) ) { echo ' style="display:none;"'; } ?> >
		<table>
			<tr>
				<td colspan="2">
					<h3 style="font-size:22px;"><?php _e( 'Site Suspended Settings', 'site-launcher' );?></h3>
				</td>
			</tr>
		</table>
		<table class="admin-table divider">
			<tr>
				<td colspan="2">
				      <h3><?php _e( 'Suspend Mode', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr valign="top">
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e( 'Login Form', 'site-launcher' );?></span>
						</legend>
						<label title="No suspend date">
						  <input type="radio" name="suspend_date" id="hidesuspenddate"  value="now"<?php if ( ! is_numeric( $this->get_site_suspend_date() ) ) { echo ' checked="checked"'; } ?> >&nbsp;<span style="color:#bf0000;"><?php _e( 'Suspend site NOW!', 'site-launcher' );?></span>
						</label><br /> 
						<label title="Set suspend date">
						  <input type="radio" name="suspend_date" id="showsuspenddate" value="automatic"<?php if ( is_numeric( $this->get_site_suspend_date() ) ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Suspend on this date and time:', 'site-launcher' );?>
						</label>
					</fieldset>
				  <div id="timestampdivsuspended" class="" <?php if ( $this->get_site_suspend_date() == 'never' || $this->get_site_suspend_date() == 'now') echo ' style="display:none;"'; ?> ><div class="timestamp-wrap-suspended">
				  <?php 
					$suspend_month = date('m', $show_suspend_julian );
					$suspend_day = date('d', $show_suspend_julian );
					$suspend_year = date('Y', $show_suspend_julian );
					$suspend_hour = date('H', $show_suspend_julian );
					$suspend_minute = date('i', $show_suspend_julian );
					$suspend_ampm = 'am';
					if ( $suspend_hour > 12 )
					{
						$suspend_hour = $suspend_hour - 12;
						$suspend_ampm = 'pm';
					}
				  ?>
				  <select id="mm_s" name="mm_s">
					<option value="01" <?php if ( $suspend_month == '01' ) echo 'selected="selected"';?> >01-Jan</option>
					<option value="02" <?php if ( $suspend_month == '02' ) echo 'selected="selected"';?> >02-Feb</option>
					<option value="03" <?php if ( $suspend_month == '03' ) echo 'selected="selected"';?> >03-Mar</option>
					<option value="04" <?php if ( $suspend_month == '04' ) echo 'selected="selected"';?> >04-Apr</option>
					<option value="05" <?php if ( $suspend_month == '05' ) echo 'selected="selected"';?> >05-May</option>
					<option value="06" <?php if ( $suspend_month == '06' ) echo 'selected="selected"';?> >06-Jun</option>
					<option value="07" <?php if ( $suspend_month == '07' ) echo 'selected="selected"';?> >07-Jul</option>
					<option value="08" <?php if ( $suspend_month == '08' ) echo 'selected="selected"';?> >08-Aug</option>
					<option value="09" <?php if ( $suspend_month == '09' ) echo 'selected="selected"';?> >09-Sep</option>
					<option value="10" <?php if ( $suspend_month == '10' ) echo 'selected="selected"';?> >10-Oct</option>
					<option value="11" <?php if ( $suspend_month == '11' ) echo 'selected="selected"';?> >11-Nov</option>
					<option value="12" <?php if ( $suspend_month == '12' ) echo 'selected="selected"';?> >12-Dec</option>
				  </select>
				  <input id="jj_s" name="jj_s" value="<?php echo $suspend_day; ?>" size="2" maxlength="2" autocomplete="off" type="text">, <input id="aa_s" name="aa_s" value="<?php echo $suspend_year; ?>" size="4" maxlength="4" autocomplete="off" type="text"> @ <input id="hh_s" name="hh_s" value="<?php echo $suspend_hour; ?>" size="2" maxlength="2" autocomplete="off" type="text"> : <input id="mn_s" name="mn_s" value="<?php echo $suspend_minute; ?>" size="2" maxlength="2" autocomplete="off" type="text">
				  <select id="ampm_s" name="ampm_s">
					<option value="am" <?php if ( $suspend_ampm == 'am' ) echo 'selected="selected"';?> >am</option>
					<option value="pm" <?php if ( $suspend_ampm == 'pm' ) echo 'selected="selected"';?> >pm</option>
				  </select><br />
				  <?php _e( 'Make sure your timezone is set correctly in Settings->General', 'site-launcher' );?>
				  </div></div>
				</td>
			</tr>
		</table>
		
		
		<table class="admin-table divider">
		
			<tr>
				<td colspan="2">
				      <h3><?php _e( 'Action', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e( 'Select Action', 'site-launcher' );?></span>
						</legend>
						
						<label title="Redirect to a different URL">
						<input type="radio" name="action_suspended" id="redirectsuspended" value="redirect" <?php if ( get_option( 'site_launcher_action_suspended' ) == 'redirect' ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Redirect to a different URL.', 'site-launcher' );?>
						</label><br /> 
						
						<label title="Show Site Suspended Page">
						<input type="radio" name="action_suspended" id="showpagesuspended" value="show_page" <?php if ( get_option( 'site_launcher_action_suspended' ) == 'show_page' || get_option( 'site_launcher_action_suspended' ) === false ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Show "Site Suspended" page.', 'site-launcher' );?>
						</label><br /> 

					</fieldset>
				</td>
			</tr>
		</table>
		
		<table class="admin-table" id="setredirectsuspended" <?php if ( get_option( 'site_launcher_action_suspended' ) == 'show_page' || get_option( 'site_launcher_action_suspended' ) === false ) echo 'style="display:none;"'; ?> >
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'URL to redirect to:', 'site-launcher' );?></th>
				<td style="padding-left:5px;">
				<?php if ( ! get_option( 'site_launcher_redirect_url_suspended' ) &&  get_option( 'site_launcher_action_suspended' ) == 'redirect' ) echo '<span style="color:#bf0000;">Please enter a valid URL.</span><br />';
				$url = get_option( 'site_launcher_redirect_url_suspended' );
				if ( ! is_string( $url ) ) $url = '';
				?>
				<input style="width:320px;" type="text" name="redirect_url_suspended" id="redirect_url_suspended" value="<?php echo $url; ?>" />
				</td>
			</tr>
		</table>
			
		<table class="admin-table divider" id="suspendedpage" <?php if ( get_option( 'site_launcher_action_suspended' ) == 'redirect' ) echo 'style="display:none;"'; ?> >

			<tr>
				<td colspan="2">
				      <h3 style="font-size:20px;"><?php _e( 'Site Suspended Page Settings', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="dotted-divider">
				      <h3><?php _e( 'Page Background', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr>
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Background Color', 'site-launcher' ) ?>:</th>
				<td style="padding-left:5px;">
				<input style="width:70px;" type="text" name="background_color_suspended" class="background_color_suspended" value="<?php echo $this->get_display_option( 'background_color_suspended' ); ?>" data-default-color="#530000" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Background Image:', 'site-launcher' );?></th><td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">
				To add your own background image (at least 1200px x 800px) upload it to the /images/full subdirectory of this plugin.<br />
				<?php
				$imagefiles = $this->get_filenames('full');
				$fullpathname = WP_PLUGIN_URL.'/'.$this->installed_folder.'/images/full/';
				$count = 0;
				foreach ( $imagefiles as $imagefile ) {
					$count++;
					$imagefilenicename = str_replace('__', ' - ',  $imagefile );
					$imagefilenicename = str_replace('_', ' ',  $imagefilenicename );
					$imagefilenicename = str_replace('.jpg', '',  $imagefilenicename );
					$imagefilenicename = str_replace('.png', '',  $imagefilenicename );
					$imagefilenicename = str_replace('.gif', '',  $imagefilenicename );
					$imagefilenicename = ucwords( $imagefilenicename );
				?>
					<?php if ( $count == 1 ) { ?>
					<label title="No Image">
					<input  type="radio" name="background_image_suspended" class="background_image_suspended" value="none" <?php if ( $this->get_display_option( 'background_image_suspended' ) == 'none' ) echo ' checked="checked"'; ?>/>&nbsp; <div style="display:inline-block;width:190px;background-color:<?php echo $this->get_display_option( 'background_color_suspended' ); ?>;"><img style="margin:5px 0;width:190px;height;100px;vertical-align:middle;" src="<?php echo $fullpathname.'transparent.gif'; ?>" /></div>
					</label>				
					<?php } ?>
					<label title="<?php echo $imagefilenicename; ?>">
					<input  type="radio" name="background_image_suspended" class="background_image_suspended" value="<?php echo $imagefile; ?>" <?php if ( $this->get_display_option( 'background_image_suspended' ) == $imagefile ) echo ' checked="checked"'; ?>/>&nbsp;<img style="margin:5px 0;width:190px;height;auto;vertical-align:middle;" src="<?php echo $fullpathname.$imagefile; ?>" /> 
					</label><?php if ( ($count + 1) % 3  == 0 ) echo '<br />'; ?>
				<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="height:15px;">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" class="dotted-divider">
				      <h3><?php _e( 'Message Box Content', 'site-launcher' );?></h3>
				</td>
			</tr>	
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;"><label for="message_text"> <?php _e( 'Message Text:', 'site-launcher' );?> </label></th>
				<td><?php echo '<textarea rows="2" cols="42" name="message_text_suspended" id="message_text_suspended" class="regular-text">'.trim( $this->get_display_option( 'message_text_suspended' ) ).'</textarea>'; ?></td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;"><label for="fine_print"> <?php _e( 'Fine Print:<br />(html allowed)', 'site-launcher' );?> </label></th>
				<td><?php echo '<textarea rows="4" cols="42" name="fine_print_suspended" id="fine_print_suspended" class="regular-text">'.trim( $this->get_display_option( 'fine_print_suspended' ) ).'</textarea>'; ?></td>
			</tr>
			</tr>
			<tr>
				<td colspan="2" style="height:15px;">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" class="dotted-divider">
				      <h3><?php _e( 'Message Box Style', 'site-launcher' );?></h3>
				</td>
			</tr>	
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;padding-top:8px;padding-bottom:8px;"><label > <?php _e( 'Show Message Box:', 'site-launcher' );?> </label></th>
				<td style="padding-top:8px;padding-bottom:8px;">
					<fieldset>
						<legend class="screen-reader-text">
						<span><?php _e( 'Message Box', 'site-launcher' );?></span>
						</legend>
						<label title="Show log-in">
						  <input type="radio" name="show_message_box_suspended" id="showmessageboxsuspended" value="1"<?php if ( $this->get_display_option( 'show_message_box_suspended' ) != '0') { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Show', 'site-launcher' );?>
						</label>&nbsp;&nbsp;
						<label title="Hide log-in">
						  <input type="radio" name="show_message_box_suspended" id="hidemessageboxsuspended" value="0"<?php if ( $this->get_display_option( 'show_message_box_suspended' ) == '0') { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Hide', 'site-launcher' );?>
						</label>
					</fieldset>
				</td>
			</tr>
			</table>
			<table id="showmessageboxdetailssuspended" style="margin-top:-15px;">
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Text Color:', 'site-launcher' );?></th>
				<td style="padding-left:5px;">
				<input style="width:70px;" type="text" name="text_color_suspended" class="text_color_suspended" value="<?php echo $this->get_display_option( 'text_color_suspended' ); ?>" data-default-color="#ffffff" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Font:', 'site-launcher' );?>
				</th>
				<td style="padding-left:5px;">
					<table style="margin-top:-2px;">
						<tr>
							<td >
								<select name="font_suspended">
								<?php foreach ( $fonts as $font_nickname=>$font_name ) {
									$fontbits = explode ( '"', $font_name );
									$font_nicename = $fontbits[1];
									?>
									<option value="<?php echo $font_nickname; ?>" <?php if ( $this->get_display_option( 'font_suspended' ) == $font_name ) echo ' selected="selected"'; ?> ><?php echo $font_nicename; ?></option>
								<?php } ?>
								</select>
							</td>
							<td style="padding-left:15px;">
								(<?php foreach ( $fonts as $font_nickname=>$font_name ) {
									$fontbits = explode ( '"', $font_name );
									$font_nicename = $fontbits[1];
									echo "<span style='font-family:".$font_name.";'>".$font_nicename."</span>, ";
									?>
								<?php } ?>)
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Message Box Width:', 'site-launcher' );?></th>
				<td style="padding-left:5px;">
				<input style="width:70px;" type="text" name="message_box_width_suspended" id="message_box_width_suspended" value="<?php echo $this->get_display_option( 'message_box_width_suspended' ); ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Message Box Opacity:', 'site-launcher' );?></th>
				<td style="padding-left:5px;">
					<table style="margin-top:-2px;">
						<tr>
							<td >
								<input style="width:40px;" type="text" name="message_box_opacity_suspended" id="message_box_opacity_suspended" value="<?php echo $this->get_display_option( 'message_box_opacity' ); ?>" />
							</td>
							<td style="padding-left:15px;">
								<?php _e( '1 = black, 0 = transparent', 'site-launcher' );?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;padding-top:4px;"><label for="message_box_border_suspended"> <?php _e( 'Message Box Border:', 'site-launcher' );?> </label></th>
				<td style="padding-top:4px;">
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e( 'Message Box Border', 'site-launcher' );?></span>
						</legend>
						<label title="None">
						  <input type="radio" name="message_box_border_suspended"  value="none"<?php if ( $this->get_display_option( 'message_box_border_suspended' ) =='none' ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'None', 'site-launcher' );?>
						</label>&nbsp;&nbsp;
						<label title="Simple">
						  <input type="radio" name="message_box_border_suspended"  value="simple"<?php if ( $this->get_display_option( 'message_box_border_suspended' ) == 'simple') { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Simple', 'site-launcher' );?>
						</label>&nbsp;&nbsp;
						<label title="3-D Fancy">
						  <input type="radio" name="message_box_border_suspended" value="3-d"<?php if ( $this->get_display_option( 'message_box_border_suspended' ) == '3-d' ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( '3-D', 'site-launcher' );?>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:140px;text-align:left;padding-top:8px;padding-bottom:8px;"><label for="show_login_suspended"> <?php _e( 'Show Log-in Form:', 'site-launcher' );?> </label></th>
				<td style="padding-top:8px;padding-bottom:8px;">
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e( 'Login Form', 'site-launcher' );?></span>
						</legend>
						<label title="Show log-in">
						  <input type="radio" name="show_login_suspended" id="showloginsuspended" value="1"<?php if ( $this->get_display_option( 'show_login_suspended' ) == '1' ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Show', 'site-launcher' );?>
						</label>&nbsp;&nbsp;
						<label title="Hide log-in">
						  <input type="radio" name="show_login_suspended" id="hideloginsuspended" value="0"<?php if ( ! $this->get_display_option( 'show_login_suspended' ) == '0' ) { echo ' checked="checked"'; } ?> >&nbsp;<?php _e( 'Hide', 'site-launcher' );?>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top" id="loginmessageinputsuspended" <?php if ( ! $this->get_display_option( 'show_login_suspended' )) echo ' style="display:none;"'; ?> >
				<th scope="row" style="width:140px;text-align:left;vertical-align:middle;"><?php _e( 'Log-in Message:', 'site-launcher' );?></th>
				<td style="padding-left:5px;">
				<input style="" type="text" name="login_message_suspended" id="login_message_suspended" value="<?php echo $this->get_display_option( 'login_message_suspended' ); ?>" />
				</td>
			</tr>

		</table>
		<table class="admin-table divider" style="margin-top:20px;">
			<tr>
				<td colspan="2">
					<h3 title="Display site as if launched for IP addresses on this list"><?php _e( 'IP Address Whitelist', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr>
				<td>
				Add a new IP address to the list by typing it in the box then clicking "Save Changes".<br /><br />
				<label><?php _e( 'IP Address:', 'site-launcher' );?> <input type="text" name="ip_address_suspended" id="ip_address_suspended" /> </label><a id="add_current_address_btn_suspended" style="cursor: pointer;"><?php _e( 'Get Current Address', 'site-launcher' );?></a><br />
				<img id="loading_current_address_suspended" class="hidden" src="<?php echo plugins_url( 'ajax-loader.gif' , __FILE__ ); ?>" />
				<?php $whitelist_suspended = get_option( 'site_launcher_ip_whitelist_suspended' );
				if ( $whitelist_suspended ): ?> 
				  <select size="4" id="ip_whitelist_suspended" name="ip_whitelist_suspended" style="width: 250px; height: 100px;margin-top: 20px;">
					<?php for( $i = 0; $i < count( $whitelist_suspended ); $i++ ):?>
						<option id="<?php echo $i; ?>" value="<?php echo $i;?>">
						<?php echo $whitelist_suspended[ $i ];?>
						</option>
						<?php endfor;?>
				  </select><br />
				  <?php if ( $whitelist_suspended ): ?> 
				  <input type="submit" value="<?php _e( 'Remove Selected IP Address', 'site-launcher' ); ?>" name="remove_selected_ip_btn_suspended" id="remove_selected_ip_btn_suspended" /> <br />
				  <?php endif; ?>
				<?php endif; ?> 
				</td>
			</tr>
		</table>
		<table class="admin-table divider">
			<tr>
				<td colspan="2">
				      <h3><?php _e( 'Users to Convert to Subscribers until Site is Unsuspended', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr>
				<td>
				<?php 
					$args = array(
						'orderby' 	=> 	'nicename'
					);

					$user_query = new WP_User_Query( $args );
					$users_to_demote = get_option( 'site_launcher_users_to_demote' );
					if ( ! is_array( $users_to_demote ) ) $users_to_demote = array();
					foreach ( $user_query->results as $user )
					{
						$checkedstring = '';
						$disabledstring = '';
						$user_roles = $user->roles;
						$user_role = $user_roles[0];
						// report original user roles, not post-demotion roles
						if ( $user_role === 'subscriber' )
						{
							foreach ( $users_to_demote as $user_to_demote )
							{
								$bits = explode( '_', $user_to_demote );
								$user_to_demote_id = $bits[0];
								$user_to_demote_original_role = $bits[1];
								if ( $user_to_demote_id == $user->ID ) $user_role = $user_to_demote_original_role;
							}
						}
						$user_id_role_string =  $user->ID.'_'.$user_role;

						if ( $user_role !== 'subscriber' )
						{	
							if ( $user->ID == get_current_user_id() )
							{
								$disabledstring = ' disabled="disabled"';
							}
							elseif ( empty( $demoted_users ) )
							{
								$checkedstring = ' checked="checked"';
							}
							elseif ( in_array( $user_id_role_string, $users_to_demote ) )
							{
								$checkedstring = ' checked="checked"';
							}
							echo '<label><input type="checkbox" name="demoted_users[]" value="' . $user_id_role_string.'" style="vertical-align:middle;"' . $checkedstring . $disabledstring . '/>' . $user->user_nicename .' ('. $user_role.')</label><br style="clear:both;" />';
						}
					}
				?>
				</td>
			</tr>
		</table>
	</div>
	<div class="site-launcher-box">
		<table>
			<tr>
				<td colspan="2">
				      <h3><?php _e( 'Admins Who Have Access to Plugin Settings', 'site-launcher' );?></h3>
				</td>
			</tr>
			<tr>
				<td>
				<?php 
					$args = array(
						'role' 		=> 	'Administrator',
						'orderby' 	=> 	'nicename'
					);

					$user_query = new WP_User_Query( $args );
					$allowed_admins = get_option( 'site_launcher_allowed_admins' );

					foreach ( $user_query->results as $user )
					{
						$checkedstring = '';
						$disabledstring = '';
						if ( $user->ID == $current_user_ID )
						{
							$checkedstring = ' checked="checked"';
							$disabledstring = ' disabled="disabled"';
						}
						elseif ( in_array( $user->ID, $allowed_admins ) )
						{
							$checkedstring = ' checked="checked"';
						}
						echo '<label><input type="checkbox" name="allowed_admins[]" value="' . $user->ID.'" style="vertical-align:middle;"' . $checkedstring . $disabledstring . '/>' . $user->user_nicename . '</label><br style="clear:both;" />';
					}
				?>
				</td>
			</tr>
		</table>
	</div>

		<table>
			<tr>
			      <td>
				  <p class="submit">
				  <?php wp_nonce_field( 'save_options','save_options_field' ); ?>
				  <input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'site-launcher' ); ?>" id="submit=changes" />
				  </p>
			      </td>
			</tr>
		</table>


	</form>


</div>
