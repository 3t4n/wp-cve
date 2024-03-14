=== Current Date & Time Widget ===
Contributors: chrisbliss18
Donate link: http://blog.realthemes.com/donations/
Tags: timezone, date, time
Requires at least: 2.3
Tested up to: 2.5.1
Stable tag: 1.0.3

Provides a widget that shows the current date and time given a specified timezone and format.

== Description ==

This is a fairly simple plugin used to display the date and time in any widget-enabled location.

There were two goals for this plugin:

* Provide a means to have the date and time produced match a specific timezone.
* Enable complete customization over how the date and time is formatted.

I think that I've done a fairly good job of meeting those goals. Admittedly, setting either field is fairly user-unfriendly. The timezone specified must match [PHP's List of Supported Timezones](http://us3.php.net/timezones). The date and time format has the same requirements as the format parameter of [PHP's date() function](http://us.php.net/date) (for obvious reasons since the defined format is actually passed directly to the date function in the code :) ).

If this plugin gets enough attention and use, I'll find ways of easing the use. I think a series of drop-downs would make selecting the options easier, so that would be the option used if people are interested. If you happen to be one of those interested people, please send me a message on our [contact page](http://realthemes.com/contact-us/).

== Installation ==

1. Download and unzip the latest release zip file
2. Upload the entire current-date-time-widget directory to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to the 'Widgets' page and add the 'Current Date & Time' widget to the desired area
5. Click 'Edit' next to the widget to customize the options

== Frequently Asked Questions ==

= What do I enter for my Timezone? =

You can find all the supported timezones on [PHP's List of Supported Timezones](http://us3.php.net/timezones). Click on the link representing the region your located in and then find the matching timezone. The timezone you specify must exactly match one of the listed timezones.

= What does Format mean? =

Format allows you to define your own customized date and time output format.

The format used for the date and time is the same format used by [PHP's date() function](http://us.php.net/date). This format allows you to create date and time output that is extremely flexible and should meet most everyone's needs.

Examples:

* 'l jS \of F Y h:i:s A' produces output with a format of 'Monday 8th of August 2005 03:12:46 PM'.
* 'l' simply outputs the full name of the day, such as 'Monday'.
* You can even include text in the output. Each letter must be escaped by a \ first. 'l \\t\h\e jS' produces output with a format of 'Monday the 8th'.
* 'F j, Y, g:i a' produces 'August 8, 2005, 3:12 pm'.
* You can even just show the date: 'M j' produces 'Aug 8'.
* Or you can just show the time: 'H:i:s' produces '15:12:46'.

As you can see, the format may be a bit confusing at first, but it is very capable of allowing you to specify just how you'd like to display the date and time.

== Version History ==

* 1.0.1 - 2008-06-04 - Basic proof of concept
* 1.0.2 - 2008-06-12 - Contained plugin code inside a class to prevent namespace collisions. Standardized code with coding style (http://comox.textdrive.com/pipermail/wp-hackers/2006-July/006930.html)
* 1.0.3 - 2008-07-01 - Added support for PHP 4