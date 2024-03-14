<?php
/*
Plugin Name: RS EVENT multiday
Plugin URI: http://dalking.de/rs-event-multiday/
Description: "RS EVENT multiday" is an easy to use and highly flexible tool to manage and print events in your blog by adding the sidebar-widget "RS EVENT multiday" and/or the functions rs_event_list(), rs_event_post() and rs_event_id(), which can be used in templates. It is based on the original "RS EVENT" by Robert Sargant.
Version: 1.3.2

Author: Florian Meier
Author URI: http://dalking.de/

Author of the original RS EVENT plugin: Robert Sargant (http://www.sargant.com/)

It seems Robert Sargant stopped working on this RS EVENT plugin. According to his readme-File, which was published along
with version 0.9.3, Robert licensed RS Event under the GPL (GNU Public License) Version 2. 
RS EVENT multiday is licensed under the GPL (GNU Public License) Version 3. 

*********************************************************************************************
	Copyright (C) 2009-2012  Florian Meier  (email : fm-web@dalking.de)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*********************************************************************************************

Since Robert Sargant stopped working on RS EVENT, many others have started to develop new versions of this great plugin. 
Thanks a lot to all, who made it possible, that RS EVENT is compatible up to Wordpress 2.7 !!!

Special thanks to nepf, Nudnik, Rick Parsons, Tim (livingos.com) and of course to Robert Sargant for a really great plugin.

*/


/*** LOCALISATION *********************************************************/
add_action('init', 'rs_event_localize');

function rs_event_localize() {	
		$plugin_dir = basename(dirname(__FILE__));
		load_plugin_textdomain('rs-event-multiday', false, $plugin_dir . '/lang');
	}

	
/*** INPUT CONTROLS *******************************************************/

/*** Add the RS EVENT multiday controls to the posting sidebar */
/* 0.9.6 moved add_action('dbx_post_sidebar', 'rs_event_sidebar_controls'); */
add_action('admin_menu', 'rs_event_add_custom_box');

/*** Manipulate RS Event data when modifying posts */
add_action('edit_post', 'rs_event_save');
add_action('save_post', 'rs_event_save');
add_action('publish_post', 'rs_event_save');

/*** These defaults are used all over the place */
$rs_event_defaults = array
	(
		'title'				=> __('Upcoming Events', 'rs-event-multiday'),
		'timespan'			=> 365,
		'history'			=> 0,
		'date_format_1'		=> 'd.m.Y',
		'date_format_2'		=> 'd.m.',
		'groupdateformat_y'	=> 'Y',
		'groupdateformat_m'	=> 'F',
		'groupdateformat_d'	=> 'd, l',
		'time_format'		=> 'H:i',  //seconds can not be entered or used
		'time_connector'	=> ' - ',
		'html_list_v1'		=> '%DATE% @ %TIME% | %LOCATION% <br /><strong>%TITLE%</strong> <br />&#187; <a href="%URL%">read more</a> &#171;<hr />',
		'html_list_v2'		=> '%DATE% | %LOCATION% <br /><strong>%TITLE%</strong> <br />&#187; <a href="%URL%">read more</a> &#171;<hr />',
		'html_list_v3'		=> '%DATE% - %ENDDATE% | %LOCATION% <br /><strong>%TITLE%</strong> <br />&#187; <a href="%URL%">read more</a> &#171;<hr />',
		'html_list_v4'		=> '%DATE% (multi-day) | %LOCATION% <br /><strong>%TITLE%</strong> <br />&#187; <a href="%URL%">read more</a> &#171;<hr />',
		'html_post_v1' 		=> '<div class="rseventpost">%LOCATION% | %DATE% @ %TIME%</div>',
		'html_post_v2' 		=> '<div class="rseventpost">%LOCATION% | %DATE%</div>',
		'html_post_v3' 		=> '<div class="rseventpost">%LOCATION% | %DATE% - %ENDDATE%</div>',
		'html_post_v4' 		=> '<div class="rseventpost">%LOCATION% | %DATE% (multi-day event)</div>',
		'max_events'		=> 0,
		'group_by_year'		=> 0,
		'group_by_month'	=> 0,
		'group_by_day'		=> 0,
		'no_events_msg'		=> __('no upcoming events', 'rs-event-multiday'),
		'sort_order'		=> 'ASC',
		'category'			=> 0,
		'fulltext_active'	=> 1,
		'loggedin'			=> 0,
);
/*
date_format_1 	=  used for single-day events, for end date of multi-day events within the same year, for start date and end date of multi-day events not within the same year
date_format_2	=  used for start date of multi-day events within the same year
groupdateformat_y/_m/_d = if grouping by date is turned on for year, month and/or day
time-format		= 'second' cannot be entered for event time and must not be used because of variables $fake_secondstart and $fake_secondend (used to mark empty time value)
html_list_vX  	=  output of all selected events in a list; use function rs_event_list() in your template; also default values for widget;
html_post_vX  	=  output of event-date on a single post; use function rs_event_post() in your template;
_v1  =  first date and time is entered with "multi-day"-option off;
_v2  =  first date but no time is entered with "multi-day"-option off; if %TIME% is used here anyway (which usually does not make any sense), output of %TIME% is nothing.
_v3  =  "multi-day"-option is on and first date and end date is entered;
_v4  =  "multi-day"-option is on but no end date is entered;
max_events => 0, outputs all events
*/



/*** ADMIN CONTROLS *******************************************************/


/* 0.9.6 - Register function for the plugin in a new way for WP 2.7 */
function rs_event_add_custom_box()
{
	add_meta_box('rs_event_sectionid', 'RS EVENT multiday', 'rs_event_sidebar_controls', 'post', 'advanced');
/* 1.1 - The custom box is now added to the edit-page page at the wp-backend as well. Thus pages can be marked as an event, too. */
	add_meta_box('rs_event_sectionid', 'RS EVENT multiday', 'rs_event_sidebar_controls', 'page', 'advanced');
}


/*** Inserts some drop-down menus as an extra posting sidebar panel. */
function rs_event_sidebar_controls()
{
	
	/*** If there are existing post details, get values to autofill the form */
	if(isset($_REQUEST['post']))
	{
		$event_timestamp = get_post_meta($_REQUEST['post'], "_rs_event_array", true);
		/* the following makes the code compatible to events, that used RS EVENT <= 0.9.8 */
		if ($event_timestamp)
		{
			$ts1_start = $event_timestamp[0];
			$ts1_end = $event_timestamp[1];
			$ts1_multiday = $event_timestamp[2];
			$ts1_removeby = $event_timestamp[3];
			$ts1_location = $event_timestamp[4];
			$ts1_endtime  = $event_timestamp[5];
		}
		else
		{
			$event_timestamp = get_post_meta($_REQUEST['post'], "rs_event", true);
			$event_multiday = get_post_meta($_REQUEST['post'], "rs_multiday", true);
			$event_timestamp_end = get_post_meta($_REQUEST['post'], "rs_event_end", true);
			$ts1_start = $event_timestamp;
			$ts1_multiday = $event_multiday;
			$ts1_end = $event_timestamp_end;
		}
	
		if($ts1_start) 
		{
			list($year, $month, $day, $hour, $minute, $second) = explode(" ", gmdate("Y n j G i s", $ts1_start));
			// Cast as int to get rid of the zero
			$hour = (int)$hour;
			$minute = (int)$minute;
			$second = (int)$second;
		}
		
		if($ts1_endtime) 
		{
			list($endhour, $endminute, $endsecond) = explode(" ", gmdate("G i s", $ts1_endtime));
			// Cast as int to get rid of the zero
			$endhour = (int)$endhour;
			$endminute = (int)$endminute;
			$endsecond = (int)$endsecond;
		}

		$multiday = $ts1_multiday;
		$multiday = (int)$multiday;
		
		$removeby = $ts1_removeby;
		$removeby = (int)$removeby; 
		
		$location = $ts1_location;
		$location = (string)$location;
		
		if($multiday == 2)
		{
			if($ts1_end)
			{
				list($year_end, $month_end, $day_end) = explode(" ", gmdate("Y n j", $ts1_end));
			}
			if($year_end < $year)
			{
			$year_end = $year;
			}
		}
	}
	else {	/* 0.9.6: from Nudnik - preselect current year */
		$year = date("Y");
		$year_end = date("Y");
	}
?>

	<!-- 0.9.6: from Nudnik - Print current date, for user-friendliness -->
	<?php $todaydate = __('j M Y', 'rs-event-multiday'); ?>
	<span id="rs_dbx_today" style="color:#999;font-size:.85em;"><?php _e('Today is', 'rs-event-multiday')?> <strong><?php echo date($todaydate); ?></strong></span>
	<br />

	<div id="rs_dbx_startdate" style="float:left; margin:15px 15px 15px 30px;">
		<label><strong><?php _e('(Start-)Date', 'rs-event-multiday'); ?>:</strong><br />&nbsp;</label><br />
		<select name="rs_event_day" style="width: 4.5em">
			<option value=""><?php _e('Day', 'rs-event-multiday') ?></option>
			<option value="">----</option>
			<?php for($d = 1; $d <= 31; $d++) {
					if($d == $day) { ?>
			<option selected="selected" value="<?php echo $d ?>"><?php echo $d ?></option>
			<?php } else { ?>
			<option value="<?php echo $d ?>"><?php echo $d ?></option>
			<?php }
			} ?>
		</select>

		<select name="rs_event_month" style="width: 6em;">
			<option value=""><?php _e('Month', 'rs-event-multiday') ?></option>
			<option value="">----</option>
			<?php
		foreach( array( 1 => __('January', 'rs-event-multiday'), 2 => __('February', 'rs-event-multiday'), 3 => __('March', 'rs-event-multiday'), 4 => __('April', 'rs-event-multiday'), 5 => __('May', 'rs-event-multiday'), 6 => __('June', 'rs-event-multiday'), 7 => __('July', 'rs-event-multiday'), 8 => __('August', 'rs-event-multiday'), 9 => __('September', 'rs-event-multiday'), 10 => __('October', 'rs-event-multiday'), 11 => __('November', 'rs-event-multiday'), 12 => __('December', 'rs-event-multiday')) as $id => $m ) {
			if($id == $month) { ?>
			<option selected="selected" value="<?php echo $id ?>"><?php _e($m, 'rs-event-multiday') ?></option>
			<?php } else { ?>
			<option value="<?php echo $id ?>"><?php _e($m, 'rs-event-multiday') ?></option>
			<?php }
			} ?>
		</select>

		<br />

		<select name="rs_event_year" style="width: 7em;">
			<option value=""><?php _e('Year', 'rs-event-multiday') ?></option>
			<option value="">----</option>
			<?php for($y = date("Y")-1; $y <= date("Y")+2; $y++) { ?>
				<?php if($y == $year) { ?>
					<option selected="selected" value="<?php echo $y ?>"><?php echo $y ?></option>
				<?php } else { ?>
					<option value="<?php echo $y ?>"><?php echo $y ?></option>
				<?php } ?>
			<?php } ?>
		</select>

	</div>

	<div id="rs_dbx_time" style="float:left; margin:15px 15px 15px 20px;">
	
		<div id="rs_dbx_starttime">
		<label><strong><?php _e('(Start-)Time', 'rs-event-multiday') ?>:</strong><br />(<?php _e('optional', 'rs-event-multiday') ?>)</label><br />
		<select name="rs_event_hour" style="width: 5em;">
			<?php if($second == 59) { ?>
			<option selected="selected" value=""><?php _e('Hour', 'rs-event-multiday') ?></option>
			<option value="">----</option>
				<?php for($h = 0; $h <= 23; $h++) { ?>
				<option value="<?php echo $h ?>"><?php echo str_pad($h, 2, "0", STR_PAD_LEFT) ?></option>
				<?php }
			} else { ?>
				<option value=""><?php _e('Hour', 'rs-event-multiday') ?></option>
				<option value="">----</option>
				<?php for($h = 0; $h <= 23; $h++) {
				  		if($h === $hour) { ?>
					<option selected="selected" value="<?php echo $h ?>"><?php echo str_pad($h, 2, "0", STR_PAD_LEFT) ?></option>
					<?php } else { ?>
					<option value="<?php echo $h ?>"><?php echo str_pad($h, 2, "0", STR_PAD_LEFT) ?></option>
					<?php }
				}
			} ?>
		</select>		
		<span style="position:relative; top:5px;">:</span>
		<select name="rs_event_minute" style="width: 5em;">
			<?php if($second == 59) { ?>
			<option selected="selected" value=""><?php _e('Min', 'rs-event-multiday') ?></option>
			<option value="">----</option>
				<?php for($mi = 0; $mi <= 55; $mi = $mi + 5) { ?>
				<option value="<?php echo $mi ?>"><?php echo str_pad($mi, 2, "0", STR_PAD_LEFT) ?></option>
				<?php }
			} else { ?>
				<option value=""><?php _e('Min', 'rs-event-multiday') ?></option>
				<option value="">----</option>
				<?php for($mi = 0; $mi <= 55; $mi = $mi + 5) {
				  		if($mi === $minute) { ?>
					<option selected="selected" value="<?php echo $mi ?>"><?php echo str_pad($mi, 2, "0", STR_PAD_LEFT) ?></option>
					<?php } else { ?>
					<option value="<?php echo $mi ?>"><?php echo str_pad($mi, 2, "0", STR_PAD_LEFT) ?></option>
					<?php }
				}
			} ?>
		</select>	
		</div>
		<div id="rs_dbx_endtime">
		<label><strong><?php _e('End-Time', 'rs-event-multiday') ?>:</strong><br />(<?php _e('optional', 'rs-event-multiday') ?>)</label><br />
		<select name="rs_event_endhour" style="width: 5em;">
			<?php if($endsecond == 58) { ?>
			<option selected="selected" value=""><?php _e('Hour', 'rs-event-multiday') ?></option>
			<option value="">----</option>
				<?php for($h = 0; $h <= 23; $h++) { ?>
				<option value="<?php echo $h ?>"><?php echo str_pad($h, 2, "0", STR_PAD_LEFT) ?></option>
				<?php }
			} else { ?>
				<option value=""><?php _e('Hour', 'rs-event-multiday') ?></option>
				<option value="">----</option>
				<?php for($h = 0; $h <= 23; $h++) {
				  		if($h === $endhour) { ?>
					<option selected="selected" value="<?php echo $h ?>"><?php echo str_pad($h, 2, "0", STR_PAD_LEFT) ?></option>
					<?php } else { ?>
					<option value="<?php echo $h ?>"><?php echo str_pad($h, 2, "0", STR_PAD_LEFT) ?></option>
					<?php }
				}
			} ?>
		</select>		
		<span style="position:relative; top:5px;">:</span>
		<select name="rs_event_endminute" style="width: 5em;">
			<?php if($endsecond == 58) { ?>
			<option selected="selected" value=""><?php _e('Min', 'rs-event-multiday') ?></option>
			<option value="">----</option>
				<?php for($mi = 0; $mi <= 55; $mi = $mi + 5) { ?>
				<option value="<?php echo $mi ?>"><?php echo str_pad($mi, 2, "0", STR_PAD_LEFT) ?></option>
				<?php }
			} else { ?>
				<option value=""><?php _e('Min', 'rs-event-multiday') ?></option>
				<option value="">----</option>
				<?php for($mi = 0; $mi <= 55; $mi = $mi + 5) {
				  		if($mi === $endminute) { ?>
					<option selected="selected" value="<?php echo $mi ?>"><?php echo str_pad($mi, 2, "0", STR_PAD_LEFT) ?></option>
					<?php } else { ?>
					<option value="<?php echo $mi ?>"><?php echo str_pad($mi, 2, "0", STR_PAD_LEFT) ?></option>
					<?php }
				}
			} ?>
		</select>	
		</div>

	</div>
		
	<div id="rs_dbx_multiday" style="float:left; margin:15px 15px 15px 2px; padding-left:15px; border-left:2px solid #999;">

		<label><strong><?php _e('multi-day event?', 'rs-event-multiday') ?></strong></label><br />
		<select name="rs_event_multiday" style="width: 5em;">
				<?php if($multiday != 2) { ?>
					<option selected="selected" value="1"><?php _e('no', 'rs-event-multiday') ?></option>
					<option value="2"><?php _e('yes', 'rs-event-multiday') ?></option>
				<?php } else { ?>
					<option value="1"><?php _e('no', 'rs-event-multiday') ?></option>
					<option selected="selected" value="2"><?php _e('yes', 'rs-event-multiday') ?></option>
			<?php } ?>
		</select>

<br />&nbsp;<br />

		<label><strong><?php _e('Remove from list...', 'rs-event-multiday') ?></strong><br />(<?php _e('if end-date exists', 'rs-event-multiday') ?>)</label><br />
		<select name="rs_event_removeby" style="width: 13em;">
				<?php if($removeby == 1) { ?>
					<option selected="selected" value="1"><?php _e('by start date', 'rs-event-multiday') ?></option>
					<option value="2"><?php _e('by end date', 'rs-event-multiday') ?></option>
				<?php } else { ?>
					<option value="1"><?php _e('by start date', 'rs-event-multiday') ?></option>
					<option selected="selected" value="2"><?php _e('by end date', 'rs-event-multiday') ?></option>
			<?php } ?>
		</select>

	</div>
	<div id="rs_dbx_enddate" style="float:left; margin:15px 5px 15px 20px;">

		<label><strong><?php _e('End-Date', 'rs-event-multiday') ?></strong>, <?php _e('if multi-day', 'rs-event-multiday') ?>:<br />(<?php _e('optional', 'rs-event-multiday') ?>)</label><br />
		<select name="rs_event_day_end" style="width: 4.5em">
			<option value=""><?php _e('Day', 'rs-event-multiday') ?></option>
			<option value="">----</option>
<?php
			for($d = 1; $d <= 31; $d++) {
			if($d == $day_end) {
?>
				<option selected="selected" value="<?php echo $d ?>"><?php echo $d ?></option>
<?php
			} else {
?>
				<option value="<?php echo $d ?>"><?php echo $d ?></option>
<?php
		}
	}
?>
		</select>

		<select name="rs_event_month_end" style="width: 6em;">
			<option value=""><?php _e('Month', 'rs-event-multiday') ?></option>
			<option value="">----</option>
			<?php
		foreach( array( 1 => __('January', 'rs-event-multiday'), 2 => __('February', 'rs-event-multiday'), 3 => __('March', 'rs-event-multiday'), 4 => __('April', 'rs-event-multiday'), 5 => __('May', 'rs-event-multiday'), 6 => __('June', 'rs-event-multiday'), 7 => __('July', 'rs-event-multiday'), 8 => __('August', 'rs-event-multiday'), 9 => __('September', 'rs-event-multiday'), 10 => __('October', 'rs-event-multiday'), 11 => __('November', 'rs-event-multiday'), 12 => __('December', 'rs-event-multiday')) as $id => $m ) {
			if($id == $month_end) { ?>
			<option selected="selected" value="<?php echo $id ?>"><?php _e($m, 'rs-event-multiday') ?></option>
			<?php } else { ?>
			<option value="<?php echo $id ?>"><?php _e($m, 'rs-event-multiday') ?></option>
			<?php }
			} ?>
		</select>

		<br />

		<select name="rs_event_year_end" style="width: 7em;">
			<option value=""><?php _e('Year', 'rs-event-multiday') ?></option>
			<option value="">----</option>
			<?php for($y = date("Y")-1; $y <= date("Y")+2; $y++) { ?>
				<?php if($y == $year_end) { ?>
					<option selected="selected" value="<?php echo $y ?>"><?php echo $y ?></option>
				<?php } else { ?>
					<option value="<?php echo $y ?>"><?php echo $y ?></option>
				<?php } ?>
			<?php } ?>
		</select>

	</div>
		
	<div id="rs_dbx_location" style="clear:left; margin:15px 15px 15px 30px;">

		<label><strong><?php _e('Location', 'rs-event-multiday') ?> (<?php _e('or any other info', 'rs-event-multiday') ?>):&nbsp;</strong></label>		
		<input name='rs_event_location' type='text' size='60' maxlength='200' value='<?php echo $location; ?>'><label>&nbsp;(<?php _e('optional', 'rs-event-multiday') ?>)</label>

	</div>


<?php
		if($event_timestamp) {
?>
	<div id="rs_dbx_delete" style="clear:both; margin:15px 30px 15px 30px;">
		<label for="rs-event-delete" class="selectit"><input id="rs-event-delete" type="checkbox" name="rs_event_delete" value="1" /> <?php _e('delete event date?', 'rs-event-multiday'); ?></label>
	</div>
<?php
		}
?>
	<div style="clear:both; display:block;"></div>
<?php
}



function rs_event_save($id)
{
	if(!isset($id)) { $id = $_REQUEST['post_ID']; }



	if($_REQUEST['rs_event_delete'])
	{
		$testoldversion = get_post_meta($id, "rs_event", true);
		if ($testoldversion) {
			delete_post_meta($id, "rs_event");
			delete_post_meta($id, "rs_multiday");
			delete_post_meta($id, "rs_event_end");
		}

		delete_post_meta($id, "_rs_event_array");
/* bugfix v.1.0.1: the following two lines were added */
		delete_post_meta($id, "_rs_event_start");
		delete_post_meta($id, "_rs_event_ts");
		return true;
	}
	elseif(($_REQUEST['rs_event_year'] || $_REQUEST['rs_event_year_end']) && $_REQUEST['rs_event_month'] && $_REQUEST['rs_event_day'])
	{
		$year_ts = ($_REQUEST['rs_event_year']) ? $_REQUEST['rs_event_year'] : $_REQUEST['rs_event_year_end'];
		$hour = ($_REQUEST['rs_event_hour']!="" && $_REQUEST['rs_event_minute']!="") ? $_REQUEST['rs_event_hour'] : 0;
		$minute = ($_REQUEST['rs_event_hour']!="" && $_REQUEST['rs_event_minute']!="") ? $_REQUEST['rs_event_minute'] : 99;
		$second = 0;
		if ($minute==99) {
			$second = 59;
			$minute = 0;
		}
		$endhour = ($_REQUEST['rs_event_hour']!="" && $_REQUEST['rs_event_minute']!="" && $_REQUEST['rs_event_endhour']!="" && $_REQUEST['rs_event_endminute']!="") ? $_REQUEST['rs_event_endhour'] : 0;
		$endminute = ($_REQUEST['rs_event_hour']!="" && $_REQUEST['rs_event_minute']!="" && $_REQUEST['rs_event_endhour']!="" && $_REQUEST['rs_event_endminute']!="") ? $_REQUEST['rs_event_endminute'] : 99;
		$endsecond = 0;
		if ($endminute==99) {
			$endsecond = 58;
			$endminute = 0;
		}

		/*** gmmktime stops PHP from interfering with local timezone settings */
		$ts_start = gmmktime($hour, $minute, $second, $_REQUEST['rs_event_month'], $_REQUEST['rs_event_day'], $year_ts);

		delete_post_meta($id, "_rs_event_start");
		add_post_meta($id, "_rs_event_start", $ts_start);		

		$multiday = ($_REQUEST['rs_event_multiday']);
		$removeby = ($_REQUEST['rs_event_removeby']);
		$location = ($_REQUEST['rs_event_location']);
		$ts_endtime = gmmktime($endhour, $endminute, $endsecond, 0, 0, 0);

		if($multiday==2 && $_REQUEST['rs_event_day_end'] && $_REQUEST['rs_event_month_end'] && ($_REQUEST['rs_event_year'] || $_REQUEST['rs_event_year_end']))
		{
			$year_ts_end = ($_REQUEST['rs_event_year_end']) ? $_REQUEST['rs_event_year_end'] : $_REQUEST['rs_event_year'];
			$ts_end = gmmktime(0, 0, 0, $_REQUEST['rs_event_month_end'], $_REQUEST['rs_event_day_end'], $year_ts_end);
		}

		$ts_values = array ($ts_start, $ts_end, $multiday, $removeby, $location, $ts_endtime);

		$testoldversion = get_post_meta($id, "rs_event", true);
		if ($testoldversion) {
			delete_post_meta($id, "rs_event");
			delete_post_meta($id, "rs_multiday");
			delete_post_meta($id, "rs_event_end");
		}
		delete_post_meta($id, "_rs_event_array");
		add_post_meta($id, "_rs_event_array", $ts_values);

		if($ts_end && $removeby==2) {
			$rs_event_ts = $ts_end;
		} else {
			$rs_event_ts = $ts_start;
		}
		delete_post_meta($id, "_rs_event_ts");
		add_post_meta($id, "_rs_event_ts", $rs_event_ts);

		return true;
	}
}

/*** New in 0.6.2 - delete the event when the parent post is deleted */
/*function rs_event_delete($id) */
/* Removed in 1.0, since it is obsolete for WP 2.7 and does not work with limitation of post-revisions */






/*** RS_EVENT_LIST() and RS_EVENT_LIST_RETURN - OUTPUT EVENT-LIST **********************************************/

/*  1.3 - use rs_event_list_return() for returned value; rs_event_list() for echoed string; */

function rs_event_list_return($args = array())
{
	global $wpdb, $rs_event_defaults;

	/*** 0.9 - Use array_walk instead (cleaner) */
	$values = $rs_event_defaults;
	$callback = create_function('&$v, $k, $a', '$v = isset($a[$k]) ? $a[$k] : $v;');
	array_walk($values, $callback, $args);
	extract($values);

	/* 0.9.6: Correct timezone bug - use WordPress current time not GMT
	$lower_time = time() - ($history  * 24 * 60 * 60);
	$upper_time = time() + ($timespan * 24 * 60 * 60); */
	$lower_time = current_time('timestamp') - ($history  * 24 * 60 * 60);
	$upper_time = current_time('timestamp') + ($timespan * 24 * 60 * 60);

	/* 0.9.6: keep stuff visible until the current day ends */
	$lower_time = floor($lower_time / 86400) * 86400;
	$upper_time = floor($upper_time / 86400) * 86400;

	/* 0.9.8: wpdb-problem solved: term_taxonomy_id and term_id of a category in table term_taxonomy can be different from each other */
	$category_id = (0 == $category) ? 0 : $wpdb->escape(stripslashes($category));
	$term_tax_id = $wpdb->get_var("
					SELECT 
						term_taxonomy_id 
					FROM 
						$wpdb->term_taxonomy
					WHERE 
						term_id = {$category_id} 
					AND 
						taxonomy = 'category'
					");
	
	// 0.9.6: from LivingOS - Allow for WP 2.3
	$where_category_clause = (0 == $category_id) ? '' : 'AND cats.term_taxonomy_id = '.$wpdb->escape(stripslashes($term_tax_id)); 
	// 1.3: possibility to turn off %FULLTEXT%
	$where_fulltext_clause = (0 == $fulltext_active) ? "" : "post.post_content AS `fulltext`,";
	
	/*** Modified in 0.6.3 - only select published posts ***/
	/*** 0.9 - don't show postdated posts, grab excerpt, DISTINCT modifier if no category restriction ***/
	/* 0.9.6 - from LivingOS - This section has been modified for WP 2.3 */
	/*** 1.0 -	$query_string1 gets only the IDs of the events 
				$query_string2 gets further information of the events selected with $query_string1 
				-> $query_string1 is important for option "remove by start-date or end-date, since meta-key here is rs_event_ts 
				-> $query_string2 is important to sort events by start-date, no matter if they are multiday or removed by end-date 
					!!! -> completely removed/renewed in 1.2 !!! */
	/* 1.0.1 - bugfix: ORDER BY-clause added -> obsolete in 1.2 */
	/*** 1.2 - only one query in order to improve performance (back to the roots ;-) ) */
	/* 1.2.1 - bugfix: third JOIN changed from INNER to LEFT, so pages are found as well if category is set to '0' (pages do not have a category!) */
	$query_string = "
	SELECT DISTINCT
		post.ID AS `id`,
		post.post_title AS `title`,
		{$where_fulltext_clause}
		post.post_excerpt AS `excerpt`
	FROM 
		{$wpdb->posts} AS post
		INNER JOIN
			{$wpdb->postmeta} AS meta1
			ON
				post.ID = meta1.post_id	
			AND
			(
				meta1.meta_key = 'rs_event'
			OR
				meta1.meta_key = '_rs_event_ts'
			)
		INNER JOIN
			{$wpdb->postmeta} AS meta2
			ON
				post.ID = meta2.post_id	
			AND
			(
				meta2.meta_key = 'rs_event'
			OR
				meta2.meta_key = '_rs_event_start'
			)
		LEFT JOIN
			{$wpdb->term_relationships} AS cats
		ON
			post.ID = cats.object_id
	WHERE
			post.post_date <= '".current_time('mysql')."'
		AND 
		post.post_status = 'publish'
		AND
			meta1.meta_value >= {$lower_time}
		AND
			meta1.meta_value <= {$upper_time}
		{$where_category_clause}
	ORDER BY 
		meta2.meta_value {$sort_order}
	";

	/*** 0.5.1 - Allow event limiting */
	if($max_events != 0) { $query_string .= " LIMIT {$max_events}"; }

	/*** Get a list of the events from our query string */
	$event_list = $wpdb->get_results($query_string);
	
	/*** Items for outputting will be placed here for imploding later */
	$output_array = array();

	/*** If the query has returned an array, do stuff */
	if(!empty($event_list))
	{
		/*** To store previous dates if we have $group_by_year, $group_by_month and/or $group_by_day turned on */
		$previous_year = false;
		$previous_month = false;
		$previous_day = false;
		
		/*** Loop through each event */
		foreach($event_list as $event)
		{
				/*** 1.3 - addition to force observance of Role Scoper-imposed restrictions (thanks to kevinB) */
					if (is_user_logged_in() )
					{ 
						if (! current_user_can( 'read_post', $event->id ) )
							continue;
					}
			/*** Setup variables */
			$event_timestamp_l = get_post_meta($event->id, "_rs_event_array", true);
			/* the following makes the code compatible to events, that used RS EVENT <= 0.9.8 */
			if ($event_timestamp_l)
			{
				$ts_l_start = $event_timestamp_l[0];
				$ts_l_end = $event_timestamp_l[1];
				$ts_l_multiday = $event_timestamp_l[2];
				$ts_l_location = $event_timestamp_l[4];
				$ts_l_endtime = $event_timestamp_l[5];			
			}
			else
			{
				$event_timestamp_l = get_post_meta($event->id, "rs_event", true);
				$event_multiday_l = get_post_meta($event->id, "rs_multiday", true);
				$event_timestamp_end_l = get_post_meta($event->id, "rs_event_end", true);
				$ts_l_start = $event_timestamp_l;
				$ts_l_multiday = $event_multiday_l;
				$ts_l_end = $event_timestamp_end_l;
			}	
			
			/*** Format the date/time/HTML now */
			$output_date = mysql2date($date_format_1, gmdate('Y-m-d H:i:s', $ts_l_start));
			$output_time = mysql2date($time_format, gmdate('Y-m-d H:i:s', $ts_l_start));
			$output_endtime = mysql2date($time_format, gmdate('Y-m-d H:i:s', $ts_l_endtime));
			/*** Format fake-secondstart, if no starttime is entered; Format fake-secondend, if no endtime ist entered. */
			$fake_secondstart = mysql2date('s', gmdate('Y-m-d H:i:s', $ts_l_start));
			$fake_secondend = $ts_l_endtime ? mysql2date('s', gmdate('Y-m-d H:i:s', $ts_l_endtime)) : 58;
			
			if($ts_l_end)
			{
			$compare_year1 = mysql2date('Y', gmdate('Y', $ts_l_start));
			$compare_year2 = mysql2date('Y', gmdate('Y', $ts_l_end));

				if($compare_year1 === $compare_year2)
				{
				$output_date = mysql2date($date_format_2, gmdate('Y-m-d H:i:s', $ts_l_start));
				$output_enddate = mysql2date($date_format_1, gmdate('Y-m-d', $ts_l_end));
				}
				else
				{
				$output_enddate = mysql2date($date_format_1, gmdate('Y-m-d', $ts_l_end));
				}
			}

			/***
			Fake an excerpt if it doesn't exist
			Nicked from WP 2.0.3 functions-formatting.php line 721 (stupid globals)
			*/
			if($event->excerpt == '' && $fulltext_active == 1)
			{
				$event->excerpt = $event->fulltext;
				$event->excerpt = apply_filters('the_content', $event->excerpt);
				$event->excerpt = str_replace(']]>', ']]&gt;', $event->excerpt);
				$event->excerpt = strip_tags($event->excerpt);
				$excerpt_length = 20; /* 0.9.6 modified from 55 which is too many for sidebars */
				$words = explode(' ', $event->excerpt, $excerpt_length + 1);
				if (count($words) > $excerpt_length) 
				{
					array_pop($words);
					array_push($words, '[...]');
					$event->excerpt = implode(' ', $words);
				}
			}
			
			// 1.3: possibility to turn off %FULLTEXT%
			$fulltextinarray = (string)$fulltextinarray;
			$fulltextinarray = (0 == $fulltext_active) ? "" : apply_filters('the_content', $event->fulltext);
			
			/*** Tidy these up into keys/values and add filters */
			if ($fake_secondstart == 59) {   //$fake_secondstart == 59 means: no starttime is entered
			$replacements = array
			(
				'%URL%' 		=> get_permalink($event->id),
				'%DATE%' 		=> apply_filters('the_date', $output_date),
				'%TIME%' 		=> '',
				'%STARTTIME%'	=> '',
				'%ENDTIME%'		=> '',
				'%ENDDATE%'		=> apply_filters('the_date', $output_enddate),
				'%TITLE%' 		=> apply_filters('the_title', $event->title, $event->id),
				'%FULLTEXT%'	=> $fulltextinarray,
				'%EXCERPT%' 	=> apply_filters('the_excerpt', $event->excerpt),
				'%LOCATION%'	=> $ts_l_location,
				'%ID%' 			=> $event->id,
			);
			} else {
				if ($fake_secondend == 58) { //$fake_second == 58 means: no endtime is entered
				$replacements = array
				(
					'%URL%' 		=> get_permalink($event->id),
					'%DATE%'	 	=> apply_filters('the_date', $output_date),
					'%TIME%' 		=> apply_filters('the_time', $output_time),
					'%STARTTIME%'	=> apply_filters('the_time', $output_time),
					'%ENDTIME%'		=> '',
					'%ENDDATE%'		=> apply_filters('the_date', $output_enddate),
					'%TITLE%' 		=> apply_filters('the_title', $event->title, $event->id),
					'%FULLTEXT%' 	=> $fulltextinarray,
					'%EXCERPT%' 	=> apply_filters('the_excerpt', $event->excerpt),
					'%LOCATION%'	=> $ts_l_location,
					'%ID%' 			=> $event->id,
				);
				} else {
				$replacements = array
				(
					'%URL%' 		=> get_permalink($event->id),
					'%DATE%'	 	=> apply_filters('the_date', $output_date),
					'%TIME%' 		=> apply_filters('the_time', $output_time).$time_connector.apply_filters('the_time', $output_endtime),
					'%STARTTIME%'	=> apply_filters('the_time', $output_time),
					'%ENDTIME%'		=> apply_filters('the_time', $output_endtime),
					'%ENDDATE%'		=> apply_filters('the_date', $output_enddate),
					'%TITLE%' 		=> apply_filters('the_title', $event->title, $event->id),
					'%FULLTEXT%' 	=> $fulltextinarray,
					'%EXCERPT%' 	=> apply_filters('the_excerpt', $event->excerpt),
					'%LOCATION%'	=> $ts_l_location,
					'%ID%' 			=> $event->id,
				);		
				}
			}
			
			if ($ts_l_multiday != 2)
			{
				if($fake_secondstart != 59)       //$fake_secondstart == 59 means: no starttime is entered
				{
					$output_html = str_replace(array_keys($replacements), array_values($replacements), $html_list_v1);
				}
				else
				{
					$output_html = str_replace(array_keys($replacements), array_values($replacements), $html_list_v2);
				}
			}
			else
			{
				if($ts_l_end)
				{
					$output_html = str_replace(array_keys($replacements), array_values($replacements), $html_list_v3);
				}
				else
				{
					$output_html = str_replace(array_keys($replacements), array_values($replacements), $html_list_v4);
				}
			}

			/*** If we are not grouping by date, output as a list item now. */
			if($group_by_year == false && $group_by_month == false && $group_by_day == false) { $output_array[] = $output_html; }

			/*** If we are grouping by date */
			else
			{
				$group_year = mysql2date('Y', gmdate('Y-m-d H:i:s', $ts_l_start));			
				$group_month = mysql2date('Y-m', gmdate('Y-m-d H:i:s', $ts_l_start));			
				$group_day = mysql2date('Y-m-d', gmdate('Y-m-d H:i:s', $ts_l_start));
			
				if($group_by_year != false)
				{
					/*** If this is a new year, create a new element in the array */
					if($group_year != $previous_year)
					{
						$output_groupdate_y = mysql2date($groupdateformat_y, gmdate('Y-m-d H:i:s', $ts_l_start));
						$output_array[] = "<span class=\"rs-groupdate-year\">$output_groupdate_y</span>";
						$previous_year = $group_year;
						$previous_month = false;
						$previous_day = false;
					}				
				}
				if($group_by_month != false)
				{
					/*** If this is a new month, create a new element in the array */
					if($group_month != $previous_month)
					{
						$output_groupdate_m = mysql2date($groupdateformat_m, gmdate('Y-m-d H:i:s', $ts_l_start));
						$output_array[] = "<span class=\"rs-groupdate-month\">$output_groupdate_m</span>";
						$previous_month = $group_month;
						$previous_day = false;
					}				
				}
				if($group_by_day != false)
				{
					/*** If this is a new day, create a new element in the array */
					if($group_day != $previous_day)
					{
						$output_groupdate_d = mysql2date($groupdateformat_d, gmdate('Y-m-d H:i:s', $ts_l_start));
						$output_array[] = "<span class=\"rs-groupdate-day\">$output_groupdate_d</span>";
						$previous_day = $group_day;
					}				
				}
				
				/*** Append the event's HTML onto the last item in the list */
				$output_array[] = "$output_html";
			}
		}
	}
	/*** If no array returned, say nothing */
	else { $output_array[] = $no_events_msg; }

	/*** Now RETURN html (changed this from echo)*/
	return $output_array;	

} // end rs_event_list_return


// Backwards-compatible rs_event_list function echos rs_event_list_return results
function rs_event_list($args = array())
{
	global $wpdb, $rs_event_defaults;
	$values = $rs_event_defaults;
	$callback = create_function('&$v, $k, $a', '$v = isset($a[$k]) ? $a[$k] : $v;');
	array_walk($values, $callback, $args);
	extract($values);

		if (!is_user_logged_in() && $loggedin == 1) {
		} else {
			$output_array_list = rs_event_list_return($args); 
			/*** Now output the array */
			echo "<ul class=\"rsevent\"><li class=\"rsevent\">".implode("</li><li class=\"rsevent\">", $output_array_list)."</li></ul>";
		}
}
// end rs_event_list





/*** RS_EVENT_POST() and RS_EVENT_POST_RETURN - OUTPUT EVENT-DATE ON SINGLE POST **********************************************/
/* introduced by Florian Meier in v. 0.9.8 */
/* edited by Michelle McGinnis to return output, rather than echo in v. 1.1 */
/* use rs_event_post_return() for returned value; rs_event_post() for echoed string; */

function rs_event_post_return($args = array())
{

	global $wpdb, $post, $rs_event_defaults;

	/*** 0.9 - Use array_walk instead (cleaner) */
	$values = $rs_event_defaults;
	$callback = create_function('&$v, $k, $a', '$v = isset($a[$k]) ? $a[$k] : $v;');
	array_walk($values, $callback, $args);
	extract($values);

	$postid = $post->ID;

			/*** Setup variables */
			$event_timestamp_p = get_post_meta($postid, "_rs_event_array", true);
			/* the following makes the code compatible to events, that used RS EVENT <= 0.9.8 */
			if ($event_timestamp_p)
			{
				$ts_p_start = $event_timestamp_p[0];
				$ts_p_end = $event_timestamp_p[1];
				$ts_p_multiday = $event_timestamp_p[2];
				$ts_p_location = $event_timestamp_p[4];
				$ts_p_endtime = $event_timestamp_p[5];
			}
			else
			{
				$event_timestamp_p = get_post_meta($postid, "rs_event", true);
				$event_multiday_p = get_post_meta($postid, "rs_multiday", true);
				$event_timestamp_end_p = get_post_meta($postid, "rs_event_end", true);
				$ts_p_start = $event_timestamp_p;
				$ts_p_multiday = $event_multiday_p;
				$ts_p_end = $event_timestamp_end_p;
			}


	if($ts_p_start)
	{
		/*** Format the date/time/HTML now */
		$output_date = mysql2date($date_format_1, gmdate('Y-m-d H:i:s', $ts_p_start));
		$output_time = mysql2date($time_format, gmdate('Y-m-d H:i:s', $ts_p_start));
		$output_endtime = mysql2date($time_format, gmdate('Y-m-d H:i:s', $ts_p_endtime));
		/*** Format the fake-secondstart, if no starttime is entered; Format the fake-secondend, if no endtime is entered. */
		$fake_secondstart = mysql2date('s', gmdate('Y-m-d H:i:s', $ts_p_start));
		$fake_secondend = $ts_p_endtime ? mysql2date('s', gmdate('Y-m-d H:i:s', $ts_p_endtime)) : 58;
		
		if($ts_p_end)
		{
			$compare_year1 = mysql2date('Y', gmdate('Y', $ts_p_start));
			$compare_year2 = mysql2date('Y', gmdate('Y', $ts_p_end));
		
			if($compare_year1 === $compare_year2)
			{
				$output_date = mysql2date($date_format_2, gmdate('Y-m-d H:i:s', $ts_p_start));
				$output_enddate = mysql2date($date_format_1, gmdate('Y-m-d H:i:s', $ts_p_end));
			}
			else
			{
				$output_enddate = mysql2date($date_format_1, gmdate('Y-m-d H:i:s', $ts_p_end));
			}
		}

		/*** Tidy these up into keys/values and add filters */
			if ($fake_secondstart == 59) {       //$fake_secondstart == 59 means: no starttime is entered
			$replacements = array
			(
				'%DATE%' 		=> apply_filters('the_date', $output_date),
				'%TIME%' 		=> '',
				'%STARTTIME%'	=> '',
				'%ENDTIME%'		=> '',
				'%ENDDATE%'		=> apply_filters('the_date', $output_enddate),
				'%LOCATION%'	=> $ts_p_location,
			);
			} else {
				if ($fake_secondend == 58) {       //$fake_secondend == 58 means: no endtime is entered
				$replacements = array
				(
					'%DATE%'	 	=> apply_filters('the_date', $output_date),
					'%TIME%' 		=> apply_filters('the_time', $output_time),
					'%STARTTIME%'	=> apply_filters('the_time', $output_time),
					'%ENDTIME%'		=> '',
					'%ENDDATE%'		=> apply_filters('the_date', $output_enddate),
					'%LOCATION%'	=> $ts_p_location,
				);
				} else {
				$replacements = array
				(
					'%DATE%'	 	=> apply_filters('the_date', $output_date),
					'%TIME%' 		=> apply_filters('the_time', $output_time).$time_connector.apply_filters('the_time', $output_endtime),
					'%STARTTIME%'	=> apply_filters('the_time', $output_time),
					'%ENDTIME%'		=> apply_filters('the_time', $output_endtime),
					'%ENDDATE%'		=> apply_filters('the_date', $output_enddate),
					'%LOCATION%'	=> $ts_p_location,
				);		
				}
			}		
				
		if ($ts_p_multiday != 2)
		{
			if($fake_secondstart != 59)       //$fake_secondstart == 59 means: no starttime is entered
			{	
				$output_html = str_replace(array_keys($replacements), array_values($replacements), $html_post_v1);
			}
			else
			{
				$output_html = str_replace(array_keys($replacements), array_values($replacements), $html_post_v2);
			}
		}
		else
		{
			if($output_enddate)
			{
				$output_html = str_replace(array_keys($replacements), array_values($replacements), $html_post_v3);
			}
			else
			{
				$output_html = str_replace(array_keys($replacements), array_values($replacements), $html_post_v4);
			}
		}
	}

	/*** If no date, say nothing */
	else
	{
		$output_html = "";
	}

	/*** Now RETURN html (changed this from echo)*/
	return $output_html;

} // end rs_event_post_return

// Backwards-compatible rs_event_post function echos rs_event_post_return results
function rs_event_post($args = array())
{
	echo rs_event_post_return($args);
}
// end rs_event_post





/****** RETURNS THE IDs OF THE EVENTS IN AN ARRAY *******************************************/
/* introduced by Florian Meier in v. 0.9.8 */

function rs_event_id($args = array())
{
	global $wpdb, $rs_event_defaults;

	$values = $rs_event_defaults;
	$callback = create_function('&$v, $k, $a', '$v = isset($a[$k]) ? $a[$k] : $v;');
	array_walk($values, $callback, $args);
	extract($values);

	$lower_time = current_time('timestamp') - ($history  * 24 * 60 * 60);
	$upper_time = current_time('timestamp') + ($timespan * 24 * 60 * 60);

	/* 0.9.6: keep stuff visible until the current day ends */
	$lower_time = floor($lower_time / 86400) * 86400;
	$upper_time = floor($upper_time / 86400) * 86400;

	/* 0.9.8: wpdb-problem solved: term_taxonomy_id and term_id of a category in table term_taxonomy can be different from each other */
	$category_id = (0 == $category) ? 0 : $wpdb->escape(stripslashes($category));
	$term_tax_id = $wpdb->get_var("
					SELECT 
						term_taxonomy_id 
					FROM 
						$wpdb->term_taxonomy
					WHERE 
						term_id = {$category_id} 
					AND 
						taxonomy = 'category'
					");
		
	$where_category_clause = (0 == $category_id) ? '' : 'AND cats.term_taxonomy_id = '.$wpdb->escape(stripslashes($term_tax_id)); // 0.9.6: from LivingOS - Allow for WP 2.3

	/*** 1.0 -	$query_string1 gets only the IDs of the events 
				$query_string2 gets further information of the events selected with $query_string1 
				-> $query_string1 is important for option "remove by start-date or end-date, since meta-key here is rs_event_ts 
				-> $query_string2 is important to sort events by start-date, no matter if they are multiday or removed by end-date 
					!!! -> completely removed/renewed in 1.2 !!! */
	/* 1.0.1 - bugfix: ORDER BY-clause added -> obsolete in 1.2 */
	/*** 1.2 - only one query in order to improve performance (back to the roots ;-) ) */
	/* 1.2.1 - bugfix: third JOIN changed from INNER to LEFT, so pages are found as well if category is set to '0' (pages do not have a category!) */
	$query_string = "
	SELECT DISTINCT
		post.ID AS `id`
	FROM 
		{$wpdb->posts} AS post
		INNER JOIN
			{$wpdb->postmeta} AS meta1
			ON
				post.ID = meta1.post_id	
			AND
			(
				meta1.meta_key = 'rs_event'
			OR
				meta1.meta_key = '_rs_event_ts'
			)
		INNER JOIN
			{$wpdb->postmeta} AS meta2
			ON
				post.ID = meta2.post_id	
			AND
			(
				meta2.meta_key = 'rs_event'
			OR
				meta2.meta_key = '_rs_event_start'
			)
		LEFT JOIN
			{$wpdb->term_relationships} AS cats
		ON
			post.ID = cats.object_id
	WHERE
			post.post_date <= '".current_time('mysql')."'
		AND
			post.post_status = 'publish'
		AND
			meta1.meta_value >= {$lower_time}
		AND
			meta1.meta_value <= {$upper_time}
		{$where_category_clause}
	ORDER BY 
		meta2.meta_value {$sort_order}
	";

	/*** 0.5.1 - Allow event limiting */
	if($max_events != 0) { $query_string .= " LIMIT {$max_events}"; }

	/*** Get a list of the events from our query string */
	$event_id = $wpdb->get_results($query_string);
	
	/*** Items for outputting will be placed here for imploding later */
	$output_array = array();

	/*** If the query has returned an array, do stuff */
	if(is_array($event_id))
	{
		/*** Loop through each event */
		foreach($event_id as $event)
		{
			$output_id = $event->id;
			$output_array[count($output_array)-1] .= $output_id;
		}
	/*** Now output the array */
	return $output_array;
	}
} // end rs_event_id


/*** WIDGET FUNCTIONS *****************************************************/

// check version. only 2.8 WP support class multi widget system
global $wp_version;
if((float)$wp_version >= 2.8){

class RS_EVENT_multiday_widget extends WP_Widget {
	
	/**
	 * constructor
	 */	
	function RS_EVENT_multiday_widget() {
			/* Widget settings. */
		$widget_ops = array( 'classname' => 'rseventwidget', 'description' => __('Adds a list of events to your sidebar by executing the function rs_event_list of the RS EVENT multiday plugin.', 'rs-event-multiday'));
			/* Widget control settings. */
		$control_ops = array( 'width' => 700, 'height' => 350, 'id_base' => 'rs_event_widget' );	
		parent::WP_Widget('rs_event_widget', 'RS EVENT multiday', $widget_ops, $control_ops);	
	}	 
	
	/**
	 * display widget
	 */	 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		if (!is_user_logged_in() && $instance['loggedin'] == 1) {
		} else {
			echo $before_widget;
			$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
			rs_event_list($instance);
			echo $after_widget;	
		}
	}
	
	/**
	 *	update/save function
	 */	 	
	function update($new_instance, $old_instance) {
		$instance = wp_parse_args( (array) $new_instance, $old_instance );	
		return $instance;
	}
	
	/**
	 *	admin control form
	 */	 	
	function form($instance) {
//		$default = 	array( 'title' => __('Just Widget') );
//		$instance = wp_parse_args( (array) $instance, $default );
//		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
		global $rs_event_defaults;	
		$default = $rs_event_defaults;
		$instance = wp_parse_args( (array) $instance, $default );	
		extract($instance, EXTR_OVERWRITE);		
?>

<fieldset style="float: left; width: 340px; border-top:1px solid #999;">
	<legend style="font-weight:bold; font-size:1.3em;"><?php _e('Output Options', 'rs-event-multiday') ?></legend>
	<p style="text-align:right">
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ); ?>:</label> 
		<input style="width: 200px;" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value='<?php echo esc_attr( $title ); ?>' />
	</p>
	<p style="text-align:right; margin-bottom:0;">
		<label for="<?php echo $this->get_field_id('html_list_v1'); ?>"><?php _e('Output', 'rs-event-multiday') ?> HTML-1:</label>
		<input style="width: 200px" id="<?php echo $this->get_field_id('html_list_v1'); ?>" name="<?php echo $this->get_field_name('html_list_v1'); ?>" type="text" value='<?php echo $html_list_v1; ?>' />
		<br />
		<label for="<?php echo $this->get_field_id('html_list_v2'); ?>"><?php _e('Output', 'rs-event-multiday') ?> HTML-2:</label>
		<input style="width: 200px" id="<?php echo $this->get_field_id('html_list_v2'); ?>" name="<?php echo $this->get_field_name('html_list_v2'); ?>" type="text" value='<?php echo $html_list_v2; ?>' />
		<br />
		<label for="<?php echo $this->get_field_id('html_list_v3'); ?>"><?php _e('Output', 'rs-event-multiday') ?> HTML-3:</label>
		<input style="width: 200px" id="<?php echo $this->get_field_id('html_list_v3'); ?>" name="<?php echo $this->get_field_name('html_list_v3'); ?>" type="text" value='<?php echo $html_list_v3; ?>' />
		<br />
		<label for="<?php echo $this->get_field_id('html_list_v4'); ?>"><?php _e('Output', 'rs-event-multiday') ?> HTML-4:</label>
		<input style="width: 200px" id="<?php echo $this->get_field_id('html_list_v4'); ?>" name="<?php echo $this->get_field_name('html_list_v4'); ?>" type="text" value='<?php echo $html_list_v4; ?>' />
		<br />
	</p>
	<p style="text-align:left;">
		<small>
		<strong><?php _e('The output can be customized with simple HTML code and the following wildcards', 'rs-event-multiday') ?>: </strong><br />
		|&#37;TITLE&#37; | &#37;DATE&#37; | &#37;ENDDATE&#37; | &#37;LOCATION&#37; | <br />
		|&#37;ID&#37; | &#37;TIME&#37; | &#37;STARTTIME&#37; | &#37;ENDTIME&#37; |  <br />
		| &#37;EXCERPT&#37; | &#37;URL&#37; | &#37;FULLTEXT&#37; (<?php _e('if enabled', 'rs-event-multiday') ?>) |
		</small>
	</p>
	<p style="text-align:left;">
		<?php _e('wildcard %FULLTEXT%', 'rs-event-multiday') ?>:&nbsp;&nbsp;&nbsp;
		<label for="<?php echo $this->get_field_id('fulltext_enabled'); ?>"><input type="radio" name="<?php echo $this->get_field_name('fulltext_active'); ?>" id="<?php echo $this->get_field_id('fulltext_enabled'); ?>" value="1" <?php echo (1 == $fulltext_active) ?'checked="checked"':'' ?> /><?php _e('enabled', 'rs-event-multiday') ?>&nbsp;&nbsp;&nbsp;</label>
		<label for="<?php echo $this->get_field_id('fulltext_disabled'); ?>"><input type="radio" name="<?php echo $this->get_field_name('fulltext_active'); ?>" id="<?php echo $this->get_field_id('fulltext_disabled'); ?>" value="0" <?php echo (0 == $fulltext_active) ?'checked="checked"':'' ?> /><?php _e('disabled', 'rs-event-multiday') ?></label><br />
		<small>
		<?php _e('Disable the wildcard %FULLTEXT%, if you do not use it, in order to improve the performace of RS EVENT multiday.', 'rs-event-multiday') ?><br /><?php _e('NOTE:', 'rs-event-multiday') ?> <?php _e('If %FULLTEXT% is disabled, excerpts are not created automatically.', 'rs-event-multiday') ?>
		</small>		
	</p>
	<p style="text-align:right;">
		<label for="<?php echo $this->get_field_id('no_events_msg'); ?>"><?php _e('"No events" message', 'rs-event-multiday') ?>:</label>
		<input style="width: 150px" id="<?php echo $this->get_field_id('no_events_msg'); ?>" name="<?php echo $this->get_field_name('no_events_msg'); ?>" type="text" value='<?php echo $no_events_msg; ?>' />	
	</p>
	
	<p style="text-align:right;">
		<table align="right" cellpadding="0" cellspacing="0">
		<tr><td colspan="2" style="text-align:right;"><strong><?php _e('Group events by day, month and/or year?', 'rs-event-multiday') ?></strong>
		</td></tr><tr><td style="vertical-align:baseline;">
		<label for="<?php echo $this->get_field_id('group_by_year'); ?>"><input type="checkbox" name="<?php echo $this->get_field_name('group_by_year'); ?>" id="<?php echo $this->get_field_id('group_by_year'); ?>" value="1" <?php echo ($group_by_year?'checked="checked"':'') ?> /> <?php _e('group by year', 'rs-event-multiday') ?></label></td>
		<td style="padding-left:5px; vertical-align:baseline;">
		<label for="<?php echo $this->get_field_id('groupdateformat_y'); ?>"> &rArr; <?php _e('date formatting', 'rs-event-multiday') ?>:</label>
		<input style="width: 70px;" id="<?php echo $this->get_field_id('groupdateformat_y'); ?>" name="<?php echo $this->get_field_name('groupdateformat_y'); ?>" type="text" value="<?php echo $groupdateformat_y; ?>" />
		</td></tr><tr><td style="vertical-align:baseline;">
		<label for="<?php echo $this->get_field_id('group_by_month'); ?>"><input type="checkbox" name="<?php echo $this->get_field_name('group_by_month'); ?>" id="<?php echo $this->get_field_id('group_by_month'); ?>" value="1" <?php echo ($group_by_month?'checked="checked"':'') ?> /> <?php _e('group by month', 'rs-event-multiday') ?></label></td>
		<td style="padding-left:5px; vertical-align:baseline;">
		<label for="<?php echo $this->get_field_id('groupdateformat_m'); ?>"> &rArr; <?php _e('date formatting', 'rs-event-multiday') ?>:</label>
		<input style="width: 70px;" id="<?php echo $this->get_field_id('groupdateformat_m'); ?>" name="<?php echo $this->get_field_name('groupdateformat_m'); ?>" type="text" value="<?php echo $groupdateformat_m; ?>" />
		</td></tr><tr><td style="vertical-align:baseline;">
		<label for="<?php echo $this->get_field_id('group_by_day'); ?>"><input type="checkbox" name="<?php echo $this->get_field_name('group_by_day'); ?>" id="<?php echo $this->get_field_id('group_by_day'); ?>" value="1" <?php echo ($group_by_day?'checked="checked"':'') ?> /> <?php _e('group by day', 'rs-event-multiday') ?></label></td>
		<td style="padding-left:5px; vertical-align:baseline;">
		<label for="<?php echo $this->get_field_id('groupdateformat_d'); ?>"> &rArr; <?php _e('date formatting', 'rs-event-multiday') ?>:</label>
		<input style="width: 70px;" id="<?php echo $this->get_field_id('groupdateformat_d'); ?>" name="<?php echo $this->get_field_name('groupdateformat_d'); ?>" type="text" value="<?php echo $groupdateformat_d; ?>" />
		</td></tr></table>
	</p>	
	<p style="text-align:right; clear:both; padding-top:10px;">
		<strong><?php _e('Formatting start-date and end-date:', 'rs-event-multiday') ?></strong><br />
		<label for="<?php echo $this->get_field_id('date_format_1'); ?>"><?php _e('Date-1 formatting', 'rs-event-multiday') ?>:</label>
		<input style="width: 70px;" id="<?php echo $this->get_field_id('date_format_1'); ?>" name="<?php echo $this->get_field_name('date_format_1'); ?>" type="text" value="<?php echo $date_format_1; ?>" />
	<br />
		<label for="<?php echo $this->get_field_id('date_format_2'); ?>"><?php _e('Date-2 formatting', 'rs-event-multiday') ?>:</label>
		<input style="width: 70px;" id="<?php echo $this->get_field_id('date_format_2'); ?>" name="<?php echo $this->get_field_name('date_format_2'); ?>" type="text" value="<?php echo $date_format_2; ?>" />
	<br />
		<label for="<?php echo $this->get_field_id('time_format'); ?>"><?php _e('Time Formatting', 'rs-event-multiday') ?>:</label>
		<input style="width: 70px;" id="<?php echo $this->get_field_id('time_format'); ?>" name="<?php echo $this->get_field_name('time_format'); ?>" type="text" value="<?php echo $time_format; ?>" />
	<br />
		<label for="<?php echo $this->get_field_id('time_connector'); ?>"><?php _e('between starttime and endtime', 'rs-event-multiday') ?>:</label>
		<input style="width: 70px;" id="<?php echo $this->get_field_id('time_connector'); ?>" name="<?php echo $this->get_field_name('time_connector'); ?>" type="text" value="<?php echo $time_connector; ?>" />
	</p>	
</fieldset>

<fieldset style="float:right; width:340px; border-top:1px solid #999;">
	<legend style="font-weight:bold; font-size:1.3em;"><?php _e('Selection Options', 'rs-event-multiday') ?></legend>
	<p style="text-align:right;">
	<table align="right" cellpadding="0" cellspacing="0">
	<tr><td style="padding-left:5px; vertical-align:baseline; text-align:right;">
		<label for="<?php echo $this->get_field_id('timespan'); ?>"><?php _e('Timespan', 'rs-event-multiday') ?>:</label>
		</td><td style="padding-left:5px; vertical-align:baseline; text-align:right;">
		<input style="width: 40px; text-align: right" id="<?php echo $this->get_field_id('timespan'); ?>" name="<?php echo $this->get_field_name('timespan'); ?>" type="text" value="<?php echo $timespan; ?>" />
		</td><td style="padding-left:5px; vertical-align:baseline; text-align:left;"><?php _e('days', 'rs-event-multiday') ?>
	</td></tr><tr><td style="padding-left:5px; vertical-align:baseline; text-align:right;">
		<label for="<?php echo $this->get_field_id('history'); ?>"><?php _e('History', 'rs-event-multiday') ?>:</label>
		</td><td style="padding-left:5px; vertical-align:baseline; text-align:right;">
		<input style="width: 40px; text-align: right" id="<?php echo $this->get_field_id('history'); ?>" name="<?php echo $this->get_field_name('history'); ?>" type="text" value="<?php echo $history; ?>" />
		</td><td style="padding-left:5px; vertical-align:baseline; text-align:left;"><?php _e('days', 'rs-event-multiday') ?>
	</td></tr><tr><td style="padding-left:5px; vertical-align:baseline; text-align:right;">
		<label for="<?php echo $this->get_field_id('max_events'); ?>"><?php _e('Show up to (0 for all)', 'rs-event-multiday') ?>:</label>
		</td><td style="padding-left:5px; vertical-align:baseline; text-align:right;">
		<input style="width: 40px; text-align: right" id="<?php echo $this->get_field_id('max_events'); ?>" name="<?php echo $this->get_field_name('max_events'); ?>" type="text" value="<?php echo $max_events; ?>" />
		</td><td style="padding-left:5px; vertical-align:baseline; text-align:left;"><?php _e('events', 'rs-event-multiday') ?>
	</td></tr><tr><td style="padding-left:5px; vertical-align:baseline; text-align:right;">
		<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Events in category (0 for all)', 'rs-event-multiday') ?>:</label>
		</td><td style="padding-left:5px; vertical-align:baseline; text-align:right;">
		<input style="width: 40px; text-align: right" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" type="text" value="<?php echo $category; ?>" />
		</td><td style="padding-left:5px; vertical-align:baseline; text-align:left;">&nbsp;</td></tr>
	<tr><td colspan="3" style="text-align:right; vertical-align:baseline; padding: 7px 0 0 0;">
		<?php _e('Sort Order', 'rs-event-multiday') ?>:&nbsp;&nbsp;&nbsp;
		<label for="<?php echo $this->get_field_id('sort_order_asc'); ?>"><input type="radio" name="<?php echo $this->get_field_name('sort_order'); ?>" id="<?php echo $this->get_field_id('sort_order_asc'); ?>" value="ASC" <?php echo ("ASC" == $sort_order) ?'checked="checked"':'' ?> /><?php _e('ascending', 'rs-event-multiday') ?>&nbsp;&nbsp;&nbsp;</label>
		<label for="<?php echo $this->get_field_id('sort_order_desc'); ?>"><input type="radio" name="<?php echo $this->get_field_name('sort_order'); ?>" id="<?php echo $this->get_field_id('sort_order_desc'); ?>" value="DESC" <?php echo ("DESC" == $sort_order) ?'checked="checked"':'' ?> /><?php _e('descending', 'rs-event-multiday') ?></label></td></tr>
	<tr><td colspan="3" style="text-align:right; vertical-align:baseline; padding: 7px 0 20px 0;">
		<?php _e('Show only to logged in users?', 'rs-event-multiday') ?>&nbsp;&nbsp;&nbsp;
		<label for="<?php echo $this->get_field_id('loggedin_yes'); ?>"><input type="radio" name="<?php echo $this->get_field_name('loggedin'); ?>" id="<?php echo $this->get_field_id('loggedin_yes'); ?>" value="1" <?php echo (1 == $loggedin) ?'checked="checked"':'' ?> /><?php _e('yes', 'rs-event-multiday') ?>&nbsp;&nbsp;&nbsp;</label>
		<label for="<?php echo $this->get_field_id('loggedin_no'); ?>"><input type="radio" name="<?php echo $this->get_field_name('loggedin'); ?>" id="<?php echo $this->get_field_id('loggedin_no'); ?>" value="0" <?php echo (0 == $loggedin) ?'checked="checked"':'' ?> /><?php _e('no', 'rs-event-multiday') ?></label></td></tr>
	</table>	
	</p>
</fieldset>

<fieldset style="float:right; width:340px; border-top:1px solid #999; margin-bottom:4px;">
	<legend style="font-weight:bold; font-size:1.3em;"><?php _e('Description', 'rs-event-multiday') ?></legend>
	<p style="text-align:left;">
	<table>
	<tr valign="top"><td><strong>HTML-1:</strong>&nbsp;</td><td><?php _e('Output, if first date and time exist with "multi-day"-option off.', 'rs-event-multiday') ?></td></tr>
	<tr valign="top"><td><strong>HTML-2:</strong>&nbsp;</td><td><?php _e('Output, if first date but no time exist with "multi-day"-option off.', 'rs-event-multiday') ?></td></tr>
	<tr valign="top"><td><strong>HTML-3:</strong>&nbsp;</td><td><?php _e('Output, if "multi-day"-option is on and first date and end date exist.', 'rs-event-multiday') ?></td></tr>
	<tr valign="top"><td><strong>HTML-4:</strong>&nbsp;</td><td><?php _e('Output, if "multi-day"-option is on but no end date exists.', 'rs-event-multiday') ?></td></tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr valign="top"><td><strong>Date-1:</strong>&nbsp;</td><td><?php _e('used (1.) for single-day events, (2.) for end date of multi-day events within the same year, (3.) for start date and end date of multi-day events not within the same year', 'rs-event-multiday') ?></td></tr>
	<tr valign="top"><td><strong>Date-2:</strong>&nbsp;</td><td><?php _e('used for start date of multi-day events within the same year', 'rs-event-multiday') ?></td></tr>
	</table>
	</p>
	<p style="text-align:right;">
		<a href="http://php.net/manual/en/function.date.php" target="_blank"><?php _e('PHP documentation on time and date formatting', 'rs-event-multiday') ?></a>
	</p>
</fieldset>
<fieldset style="clear:both; border-bottom:1px solid #999;"></fieldset>
		
<?php 
	}
}


/* register widget when loading the WP core */
add_action('widgets_init', rs_event_widgets);

function rs_event_widgets(){
	// curl need to be installed
	register_widget('RS_EVENT_multiday_widget');
}

}



/*** The standard date_i18n uses machine timezones - BAD! *****************/

function gmdate_i18n($dateformatstring, $unixtimestamp)
{
	global $month, $weekday, $month_abbrev, $weekday_abbrev;
	$i = $unixtimestamp;
	if ((!empty($month)) && (!empty($weekday)))
  {
		$datemonth = $month[gmdate('m', $i)]; /* 0.9.6: fixed to properly use gmdate */
		$datemonth_abbrev = $month_abbrev[$datemonth];
		$dateweekday = $weekday[gmdate('w', $i)]; /* 0.9.6: ditto */
		$dateweekday_abbrev = $weekday_abbrev[$dateweekday];
		$dateformatstring = ' '.$dateformatstring;
		$dateformatstring = preg_replace("/([^\\\])D/", "\${1}".backslashit($dateweekday_abbrev), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])F/", "\${1}".backslashit($datemonth), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])l/", "\${1}".backslashit($dateweekday), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])M/", "\${1}".backslashit($datemonth_abbrev), $dateformatstring);
		$dateformatstring = substr($dateformatstring, 1, strlen($dateformatstring)-1);
	}
	$j = @gmdate($dateformatstring, $i);
	return $j;
}

?>
