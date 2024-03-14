===Today's Date Inserter===
Contributors: mulscully
Donate link://http://www.mediawebsite.com/mwd/services_wordpress-plugin.php
Tags: date, today's date, current date, current day, current month, current year, current time
Requires at least: 3.0.1
Tested up to: 3.9.2
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simply and quickly add the current date and or time to your wordpress posts or pages using a shortcode
Date widget also included

== Description ==

Simply and quickly add the current date and or time to your wordpress posts by using a simple shortcode in the text of your page or post.  Simply use the short code [todaysdate] in your text and when the post or page is displayed, [todaysdate] will be replaced by the current date and/or time that you defined on the settings screen.

You can also override the default format by including the format string in the shortcode.

For example, if you placed the following text in your post and your format string was set to l, F jS Y
-> Thank you for visiting my site today [todaysdate].

The date would be displayed as
-> Thank you for visiting my site today Wednesday, April 23rd 2014. 

Here how to override he default setting by including the format string in the shortcode

[todaysdate format="F Y"] would display April 2014 no matter what the default was set to.

This plugin also includes a Today's date widget.  This widget has it's own default format, but can be overridden by and valid php date format string

The plugin also uses the timezone settings from wordpress to display the time and date.

== Installation ==

1. Upload `lfo-todays-date-inserter` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently asked questions ==

= How do I add the date/time to my post? =

Simply insert the following code into your post to display the current date/time.

[todaysdate]

= How to I control the way the date or time is displayed? =

In the wordpress dashboard, go to Settings->Todays date and in the box labeled "Enter the format string here" enter the format string you would like to use.  
For a complete list of options visit http://www.php.net/manual/en/function.date.php

= Do I have to use the same format for every post? =

No.  You can override the saved format by including the format string in the shortcode.  For example using the shortcode [todaysdate format="F Y"] will display April 2014

== Changelog ==
2014-08-19 version 1.2.1
Corrected a Typo in the default date string.  The default will now display as Tuesday, August 19th 2014 instead of Tuesday, August, 19,th 2014 
Thank you hannah!

2014-04-29 version 1.2.0
Added a Date widget

2014-04-25 version 1.1.1
corrected minor typos in readme.txt file

2014-04-24 version 1.1
added the ability to override the stroed format by including the format string in the shortcode

2014-04-22 version 1.0
