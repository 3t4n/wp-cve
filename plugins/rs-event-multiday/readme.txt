=== RS EVENT multiday ===
Contributors: wp-lefty
Donate link: http://dalking.de/rs-event-multiday/
Tags: widget, plugin, manage events, event widget, events, event, events list, event list, list, rs event, multiday
Requires at least: 2.8
Tested up to: 3.5
Stable tag: 1.3.2
License: GPL (GNU Public License) Version 3

== Description ==

"RS EVENT multiday" is an easy to use and highly flexible tool to manage and print posts as events by adding the sidebar-widget "RS EVENT multiday" and/or the functions rs_event_list(), rs_event_post() and rs_event_id(), which can be used in templates. It is based on the original "RS EVENT" by Robert Sargant.

The plugin offers the possibility to add a "RS EVENT multiday" widget to the sidebar of your blog.
It allows you to show a list of upcoming and/or past events. 

"RS EVENT multiday" includes three different functions, which can be used in templates.
rs_event_list(), rs_event_post(), rs_event_id()

<br /><br />
**FUNCTIONS**

First of all, the "RS EVENT multiday" widget adds the function rs_event_list() to your sidebar and you can costumize all the settings via the widget control panel at the backend of your blog. If you want to create designated pages to show an event list, you should create a special template file for the theme you use and add the function rs_event_list() manually to the code of your template.

1. rs_event_list()<br />
This is the function used for the widget. Of course, it can be used in templates as well. 
It prints an unordered list of events according to the parameters given to it (see below).
Both, the `<ul>` and the `<li>` include class="rsevent", so you can costumize the output with stylesheets.
Furthermore, the output can be customized with the parameters "html_list_v1", "html_list_v2", "html_list_v3" and "html_list_v4".<br />
For details see the parameters-section below.
Note: If you just want to get the return values from rs_event_list(), so they do not get printed, you should use the function rs_event_list_return().

1. rs_event_post()<br />
This function should be used in the single-post-template (usually the file single.php of your theme).
It prints the date of the event.
The output can be customized with the parameters "html_post_v1", "html_post_v2", "html_post_v3" and "html_post_v4".<br />
For details see the parameters-section below.<br />
Note: If you just want to get the return values from rs_event_post(), so they do not get printed, you should use the function rs_event_post_return(). (This might be useful for e.g. Thematic themes.)

1. rs_event_id()<br />
This function returns an array with IDs of posts, which include event-data. This array of IDs can then be used in other functions or loops.

Each function can be customized by parameters.

<br /><br />
**PARAMETERS**

The parameters are used to customize the functions (see above), if you use them within templates. If you use the sidebar-widget of "RS EVENT multiday", you can make all the settings via the widget control panel at the backend of your blog. <br />
Please, have a look at the example below ("How to pass on parameters to one of the functions") in order to see how it works.

**title**<br />
*(string)* The widget-title in your sidebar. Not used, when the function is added to a template.<br />
*default value: 'Upcoming Events'*

**timespan**<br />
*(integer)* The maximum distance into the future events will be retrieved for, in days. <br />
*default value: 365*

**history**<br />
*(integer)* The maximum distance into the past that events will be retrieved for, in days. This can also be set to a negative negative number, e.g. -2 to prevent events that are occuring in the next two days from appearing. <br />
*default value: 0*

**date_format_1**<br />
*(string)* The style in which the date is formatted. date_format_1 is used for single-day events, for the end date of multi-day events, and for the start date of multi-day events if the two dates are NOT within the same year.
*default value: 'd.m.Y'*

**date_format_2**<br />
*(string)* The style in which the date is formatted. date_format_2 is used for the start date of multi-day events if the two dates are within the same year.<br />
*default value: 'd.m.'*

**groupdateformat_y**<br />
*(string)* The style in which the yearly headline date is formatted, if events are grouped by year. See also parameter group_by_year.<br />
*default value: 'Y'*

**groupdateformat_m**<br />
*(string)* The style in which the monthly headline date is formatted, if events are grouped by month. See also parameter group_by_month.<br />
*default value: 'F'*

**groupdateformat_d**<br />
*(string)* The style in which the daily headline date is formatted, if events are grouped by day. See also parameter group_by_day.<br />
*default value: 'd, l'*

**time_format**<br />
*(string)* The style in which the time is formatted.<br />
Note: 'second' cannot be entered for event time and must not be used because of variable $fake_second (used to mark empty time value.)<br />
*default value: 'H:i'*

**time_connector**<br />
*(string)* The sign(s) that are printed between the start-time and the end-time, if an end-time is entered.<br />
*default value: ' - '*
		
**html_list_v1**<br />
*(string)* Used for rs_event_list(). Formats the output, if first date and time of an event is entered with "multi-day"-option off.<br />
*default value:* <br />
' `%DATE% @ %TIME% | %LOCATION% <br /><strong>%TITLE%</strong> <br />&#187; <a href="%URL%">read more</a> &#171;<hr />` '

**html_list_v2**<br />
*(string)* Used for rs_event_list(). Formats the output, if first date but no time is entered with "multi-day"-option off; if %TIME% is used here anyway (which usually does not make any sense), output of %TIME% equals "--:--".<br />
*default value:*<br />
' `%DATE% | %LOCATION% <br /><strong>%TITLE%</strong> <br />&#187; <a href="%URL%">read more</a> &#171;<hr />` '

**html_list_v3**<br />
*(string)* Used for rs_event_list(). Formats the output, if "multi-day"-option is on and first date and end date is entered.<br />
*default value:*<br />
' `%DATE% - %ENDDATE% | %LOCATION% <br /><strong>%TITLE%</strong> <br />&#187; <a href="%URL%">read more</a> &#171;<hr />` '

**html_list_v4**<br />
*(string)* Used for rs_event_list(). Formats the output, if "multi-day"-option is on but no end date is entered.<br />
*default value:*<br />
' `%DATE% (multi-day) | %LOCATION% <br /><strong>%TITLE%</strong> <br />&#187; <a href="%URL%">read more</a> &#171;<hr />` '

**html_post_v1**<br />
*(string)* Used for rs_event_post(). Formats the output, if first date and time of an event is entered with "multi-day"-option off.<br />
*default value:*<br />
' `<div class="rseventpost">%DATE% @ %TIME%</div>` '

**html_post_v2**<br />
*(string)* Used for rs_event_post(). Formats the output, if first date but no time is entered with "multi-day"-option off; if %TIME% is used here anyway 

(which usually does not make any sense), output of %TIME% equals "--:--".<br />
*default value:*<br />
' `<div class="rseventpost">%DATE%</div>` '

**html_post_v3**<br />
*(string)* Used for rs_event_post(). Formats the output, if "multi-day"-option is on and first date and end date is entered.<br />
*default value:*<br />
' `<div class="rseventpost">%DATE% - %ENDDATE%</div>` '

**html_post_v4**<br />
*(string)* Used for rs_event_post(). Formats the output, if "multi-day"-option is on but no end date is entered.<br />
*default value:*<br />
' `<div class="rseventpost">%DATE% (multi-day event)</div>` '

**max_events**<br />
*(integer)* The maximum number of events to show. If this is set to 0, then all events will be shown. <br />
*default value: 0 (i.e. show all)*

**group_by_year**<br />
*(integer)* value 0 = off, value 1 = on. If group_by_year is set, the function rs_event_list() outputs a date as a headline for all events, that take place within the same year. For multiday events, the first day of the event is used for grouping and every event shows up only once. The format of the date can be set with the parameter groupdateformat_y<br /> 
*default value: 0 (i.e. "off")*

**group_by_month**<br />
*(integer)* value 0 = off, value 1 = on. If group_by_month is set, the function rs_event_list() outputs a date as a headline for all events, that take place within the same month. For multiday events, the first day of the event is used for grouping and every event shows up only once. The format of the date can be set with the parameter groupdateformat_m<br /> 
*default value: 0 (i.e. "off")*

**group_by_day**<br />
*(integer)* value 0 = off, value 1 = on. If group_by_day is set, the function rs_event_list() outputs a date as a headline for all events, that take place within the same day. For multiday events, the first day of the event is used for grouping and every event shows up only once. The format of the date can be set with the parameter groupdateformat_y<br /> 
*default value: 0 (i.e. "off")*

**no_events_msg**<br />
*(string)* The output when the are no events within the set timespan. <br />
*default value: 'no upcoming events'*

**sort_order**<br />
*(string)* Whether to list the events in ascending (value: 'ASC') or descending (value: 'DESC') date order.<br />
*default value: 'ASC'*

**category**<br />
*(integer)* Show only events listed against posts in the category with this ID number. If this is set to 0, then all events will be shown. <br />
*default value: 0 (i.e. show all)*

**fulltext_active**<br />
*(integer)* If this is set to 0, the wildcard %FULLTEXT% cannot be used for the HTML-Output of the Event-List and an excerpt for the wildcard %EXCERPT% is not created automatically, consequently, it has to be entered via the excerpt-box at the edit-post-page. You should disable %FULLTEXT% and set this to 0, if you do not need or use %FULLTEXT% and if you manually creat excerpts in order to improve the perfomance of the plugin RS EVENT multiday.<br />
*default value: 1 (i.e. enabled and %FULLTEXT% can be used and excerpts are created automatically)*

**loggedin**<br />
*(integer)* Allows to hide the widget or the output of the function rs_event_list(). If this is set to 0, the list or the widget will only be visible to logged in users. <br />
*default value: 0 (i.e. widget and function are visible to all)*


<br /><br />
**Customizing the html-output with wildcards**

The values "html_list_vX" and "html_post_vX" can be styled both for the widget (-> via the widget control panel) and within templates (please have a look at the example "How to pass on parameters to one of the functions" below).
Simply use html-code and the following parameters:

**%URL%**<br />
Outputs the URL of the event-post.

**%DATE%**<br />
Outputs the start-date of the event, formatted as <em>date_format_1</em> in _v1, _v2 and _v4 and as <em>date_format_2</em> in _v3.
    
**%ENDDATE%**<br />
Outputs the end-date of the event, formatted as <em>date_format_2</em>.

**%TIME%**<br />
If a starttime and endtime is entered, the output is similar to <em>%STARTTIME% time-connector %ENDTIME%</em>.<br />
If just a starttime and no endtime is entered, %TIME% is similar to <em>%STARTTIME%</em>.

**%STARTTIME%**<br />
Outputs the time when the event starts, if one is entered, formatted as <em>time_format</em>.

**%ENDTIME%**<br />
Outputs the time when the event ends, if one is entered (can only be entered if a starttime exists), formatted as <em>time_format</em>.

**%LOCATION%**<br />
Outputs an ordinary text value (if one is entered) which might be used to enter e.g. the location of the event.

**%TITLE%**<br />
Outputs the title of the post.

**%FULLTEXT%**<br />
Outputs the entire text of the post. (You can disable this wildcard to improve the perfomance of the plugin.)

**%EXCERPT%**<br />
Outputs the excerpt of the post. If the %FULLTEXT% wildcard is disabled, an excerpt is not created automatically. 
Consequently, the excerpt-box at the edit-post-page has to be filled with text in order to output an excerpt when %FULLTEXT% is disabled.<br />
Please, also see the information for "fulltext_active" at the parameter section above. 

**%ID%**<br />
Outputs the ID of the post.

<br /><br />
**How to pass on parameters to one of the functions**

Here is an example how to pass on some parameters to the function rs_event_list() [or rs_event_list_return()], when used within a template.
It works similar with rs_event_post() [and rs_event_post_return()], just use the html_post_vX parameters instead of the html_list_vX parameters.<br />
Note: Parameters for the widget can be simply passed via the Widget-Panel of your blog-backend.

`
<?php 		
	$argsevent = array(	
		'timespan'	=> 60,
		'date_format_1'	=> 'm|d|Y',
		'date_format_2'	=> 'm|d',
		'html_list_v1'	=> '<a class="list1" href="%URL%">%DATE%, %TIME%<br /><b>%TITLE%</b></a>',
		'html_list_v2'	=> '<a class="list1" href="%URL%">%DATE%<br /><b>%TITLE%</b></a>',
		'html_list_v3'	=> '<a class="list1" href="%URL%">%DATE% to %ENDDATE%<br /><b>%TITLE%</b></a>',
		'html_list_v4'	=> '<a class="list1" href="%URL%">%DATE% (multiday!!!)<br /><b>%TITLE%</b></a>',
		'max_events'	=> 4,
		);

	rs_event_list($argsevent); 
?>
`

== Installation ==

This section describes how to install the plugin and get it working.

1. Download the zip-file, unpack it and upload the folder `rs-event-multiday` to the `/wp-content/plugins/` directory of your blog OR simply add the plugin via the "Add new" menu page at the backend of your wordpress installation.
1. Activate the plugin.
1. Add the Widget "RS EVENT multiday" to your sidebar and customize it AND/OR
1. Place the function rs_event_list(), rs_event_post() and/or rs_event_id() in your template(s) and customize it.
1. Make sure, that the "RS EVENT multiday" box is visible at the edit-post-page (-> Screen Options).
1. Add a date to the post at the "RS EVENT multiday" box and publish/update the post.

See <a href="http://wordpress.org/extend/plugins/rs-event-multiday/">Description</a> for details and for customizing the plugin and its html-output via parameters and wildcards.

== Frequently Asked Questions ==

<b>Q: Is an event nothing else then an ordinary post with some (hidden) meta values?</b><br />
A: Yes, it is this simple! So, just publish a post and add a date at the "RS EVENT mutliday".<br /><br />

Please see/search/use the comments on <a href="http://dalking.de/rs-event-multiday/">http://dalking.de/rs-event-multiday/</a><br />
A lot of questions are answered there already (including the one above).<BR 7>
Futhermore, you can use the Wordpress <a href="http://wordpress.org/support/plugin/rs-event-multiday">support forum</a> for this plugin.

== Screenshots ==

1. Customization of the Widget RS EVENT multiday.
2. Add event data to your post at the edit-post-panel.
3. This is how the widget might look like in your sidebar.

== Changelog ==

Update History by Florian Meier for RS EVENT multiday

= 1.3.2	(24th Dezember 2012) =

* bugfix of function rs_event_post() (line 914)<br />
	If there is now endtime (at events edited with versions older than 1.3), it will no longer be printed as e.g. 00:00 (similar to rs_event_list)
* bugfix of function rs_event_list() (line 693)<br />
	"$fulltext_active == 0" changed to "$fulltext_active == 1"
* Serbian translation added (thanks to Borisa Djuraskovic www.webhostinghub.com )	

= 1.3.1	(17th Dezember 2012) =

* bugfix of function rs_event_post() (lines 941, 952, 962)<br />
	ts_l_location replaced by ts_p_location in order to show %LOCATION% with function rs_event_post()

= 1.3 (04th December 2012) =

* major changes
	* group by date<br />
	  completely new code: now grouping by year, month and/or day is possible
	* introduction of the function rs_event_list_return() in order to offer direct access to $output_array
	* %TIME%, %STARTTIME%, %ENDTIME%<br />
	  possibility to add an endtime to an event
		* %TIME% shows the starttime or, if an endtime is entered, the start and endtime with the variable 'time-connector' (default value: " - ") in between.
		* %STARTTIME% shows just the entered %STARTTIME%
		* %ENDTIME% shows just the entered %ENDTIME%
	* widget<br />
	  code for widget control is completely new: now, more than one widget of RS EVENT multiday can be added to a sidebar; furthermore, different categories can be entered.
	* parameter "loggedin" added<br />
		if you want to show the widget/function only to logged in users
	* wildcard %FULLTEXT%<br />
		possibilty added to disable %FULLTEXT% in order to improve performance if not needed
* fixes and minor improvements<br />	
	* function rs_event_sidebar controls<br />
		id-tags added to div-tags in order to customize input area via css
	* fix of function rs_event_list()<br />
		argument "$event->id" added to %TITLE% (thanks to Christian Sander)
	* fix of function rs_event_list()<br />
		addition to force observance of Role Scoper-imposed restrictions (thanks to kevinB)
	* fix of widget input<br />
		" changed to ', so html-code using " can be entered as further info without any problem

= 1.2.1	(10th July 2010) =

* bugfix of functions rs_event_list() (line 526) and rs_event_id() (line 897)<br />
	JOIN changed from INNER to LEFT, so pages are found as well <br />
	(Category must be set to '0' [=default], if pages should be includes, too!)
	
= 1.2 (26th May 2010) =

* major changes
	* $query_string completely renewed in functions rs_event_list() and rs_event_id()
	  in order to improve performance
	* Javasript removed in widget control panel (did not really work anyway)
* Norwegian translation added (thanks to Barbara)
		
= 1.1beta (22nd April 2010) =
	
* some major changes
	* intruduction of the function rs_event_post_return()<br />
	  in order to make it compatible to Thematic themes <br />
	  (thanks to Michelle McGinnis, www.thefriendlywebconsultant.com)
	* the possibility to mark pages as events is added<br />
	  i.e. the RS EVENT multiday custom box is now visible on the edit-pages page
	* the possibility to add a location to the event is added
	* a tiny facelift to the custom box
* Russian translation added
* Romanian translation added (thanks to Horia)

= 1.0.1 (26th May 2009) =

* bugfixes
	* bugfix of function rs_event_save()
	* bugfix of function rs_event_list(); ORDER BY-clause added at $query_string1

= 1.0 (20th April 2009) =

* initial release of RS EVENT multiday

	* function rs-event-delete() removed
	* option "remove from list with start- or end-date" added
	* meta_keys _rs_event_ts and _rs_event_start added with option "remove from list"
	* event data is put in an array and stored with the meta-key _rs_event_array<br />
	  (data stored with RS EVENT <= v.0.9.8 is still recognized)
	* meta_keys are no longer visible in "edit post"-panel

= 0.9.8 (14th April 2009) =

* first beta release of RS EVENT multiday

	* function rs-event-post() added
	* function rs-event-id() added
	* option "multiday" added

== Upgrade Notice ==

= 1.3.2 =
<hr />PLEASE UPDATE: Important bugfixes for wildcard %EXCERPT% and function rs_event_post() !!!

= 1.3.1 =
Important bugfix in order to use %LOCATION% with function rs_event_post() !!!

= 1.3 =
Version 1.3 allows better group_by_date (check settings after upgrade!!!), offers the possibility to enter an endtime and many many other improvements.

= 1.2.1 =
Version 1.2.1 includes 1 important bugfix of function rs_event_list()

= 1.2 =
Version 1.2 includes the location(info) feature. Please upgrade, if you want to add a location or any other info to an event.

= 1.1beta =
BETA version only. Includes location(info) feature.

= 1.0.1 =
Version 1.0.1 includes two bugfixes.

= 1.0 =
Initial release of multiday version. Please upgrade, if you want to use multiday feature.

