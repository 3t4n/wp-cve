=== Scheduled Announcements Widget ===
Contributors: kionae
Donate link: http://nlb-creations.com/donate
Tags: announcements, alerts, scheduled announcements
Requires at least: 3.2.1
Tested up to: 6.1
Stable tag: trunk

The Scheduled Announcements Widget lets you add a scrolling list of site announcements to any widgetized area of your site.

== Description ==

The Scheduled Announcements Widget lets you add a scrolling list of site announcements, independent of normal posts and pages, to any widgetized 
area of your site, or to your theme files. Perfect for publicizing an event, alert, or notice that doesn't require a full-page write-up. Announcements 
can be scheduled to run indefinitely or during a specific date range, and admins can chose between horizontal or vertical scrolling. 

== Installation ==

1. Unzip and upload plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add announcements using the Announcements tab in the Wordpress Dashboard.
4. Configure your widget under Announcements > Settings

== Frequently Asked Questions ==

= Can I re-order my announcments? = 

Yes, as of version 0.1.1.  Each Announcement now includes a numerical Order field.  You can set whether you want the widget to display Announcements in ascending 
or descending order based on this field in the plugin's Settings panel. 

= Can I put the ticker in a post or page? =

Yes, using the shortcode `[announcements]`

The shortcode function will use the default configuration options set in Announcements > Settings, but those settings can be overriden and customized manually using any 
of the following variables.

title - Will display a header title above the ticker
show_titles - Will show or hide the title field of the announcements.  Set to 1 for show, 0 for hide.
order - Set whether announcements display in ascending or descending order.  Valid options are 'ASC' or 'DESC'.
scroll - The scroll style of the ticker.  Valid options are 'horizontal' or 'vertical'.
speed - The speed in milliseconds at which the ticker will scroll
transition - The length in milliseconds at which the scroll animation will take to complete
width - The width in pixels of the ticker
height - The height in pixels of the ticker
link - The hexidecimal color code to use for links in the announcments (eg. 0000FF for blue)
text - The hexidecimal color code to use for links in the announcments (eg. 000000 for black)
saw_id - User specificed CSS ID for the ticker.  Required if using more than one ticker shortcode on a page.
tax - The taxonomy/category ID (you may use the ID or the slug) to filter by

For example:

`[announcements title="Announcements" order="ASC" show_titles="1" scroll="horizontal" speed="4000" transition="800" width="600" height="50" link="0000FF" text="000000" saw_id="news" tax="news"]`

= Can I display announcements by category? =

Yes.  Assign a category to your announcement, and then select that category from the dropdown menu in the widget settings.  If you are using the shortcode rather
than the widget, set the "tax" attribute to either the ID or slug of the category you wish to display.  Make sure you are using slugs/IDs for the Announcement Categories 
(displayed under the Announcements tab in the Dashboard) and not the default Wordpress Categories.

`[announcements tax="news"]`

or

`[announcements tax="7"]` 

= I want to put more than one ticker on a page, but it breaks when I try. =

You need to give each ticker its own unique id. In the shortcode this is done with the saw_id attribute (see shortcode example two questions back).  In the widget, just fill in the
text box label Ticker ID.

You should also avoid the use of hypens in your ID names, because it will royally mess up the javascript.  In most cases, if you forget, the plugin will automatically replace them 
with underscores.

== Screenshots ==

1. The widget works in both the sidebar and within posts/pages and themes.
2. Customize the widget under Announcements > Settings
3. Announcements can appear on the site indefinitely or only during a set time range.

== Changelog ==

= 1.0 =
* Overhaul of code to make it compatible with current version of WordPress.
* Sanitizing inputs on the shortcode option for better security.

= 0.2 =
* Fixed an issue with the shortcode not having default configuration settings if the user hasn't visited the config page.
* Verified WordPress 4.0 compatibility

= 0.1.8.1 =
* Added some text to clarify the purpose of some of the widget fields.
* Updated the icon

= 0.1.8 =
* Fixed a badly written query for announcements filtered by category (thanks to Jurrien Dokter for pointing it out and offering a fix!)

= 0.1.7 =
* Fixed some WordPress 3.7.1 compatibility issues
* Altered shortcode to accept either category ID or category slug.
* Added default CSS id to the shortcode to prevent conflicts with the sidebar widget.
* Added code to replace hypens with underscores in user-defined ID names so javascript doesn't freak out.

= 0.1.6 =
* Removed divider between anouncement title and text, and replaced it with a CSS span styles can be applied to if desired

= 0.1.5 =
* Fixed issue with unpublished annoucements showing up in the scroller.
* Clarified how the date scheduling feature works.

= 0.1.4 =
* Added the ability to change the transition speed of the scroll animation

= 0.1.3 =
* Fixed a bug that was breaking pages with multiple wigets in use
* Fixed a bug with the taxonomy filter within the widget

= 0.1.2 =
* Announcements now support shortcode in the body text
* Bug Fix: If no announcements are scheduled, the announcment block is hidden
* Announcements can now be filtered by category, allowing you to have multiple types of announcements (e.g. News, Alerts, Events, etc.)

= 0.1.1 =
* Added the ability to specify order of the announcements
* Announcement scrolling now pauses on mouseover

= 0.1 =
* Initial release.

== Upgrade Notice ==

= 1.0 =
* Overhaul of code to make it compatible with current version of WordPress.
* Sanitizing inputs on the shortcode option for better security.

= 0.2 =
* Fixed an issue with the shortcode not having default configuration settings if the user hasn't visited the config page.
* Verified WordPress 4.0 compatibility

= 0.1.8.1 =
* Added some text to clarify the purpose of some of the widget fields.
* Updated the icon

= 0.1.8 =
* Fixed a badly written query for announcements filtered by category (thanks to Jurrien Dokter for pointing it out and offering a fix!)

= 0.1.7 =
* Fixed some WordPress 3.7.1 compatibility issues
* Altered shortcode to accept either category ID or category slug.
* Added default CSS id to the shortcode to prevent conflicts with the sidebar widget.
* Added code to replace hypens with underscores in user-defined ID names so javascript doesn't freak out.

= 0.1.6 =
* Removed divider between anouncement title and text, and replaced it with a CSS span styles can be applied to if desired

= 0.1.5 =
* Fixed issue with unpublished annoucements showing up in the scroller.
* Clarified how the date scheduling feature works.

= 0.1.4 =
* Added the ability to change the transition speed of the scroll animation

= 0.1.3 =
* Fixed a bug that was breaking pages with multiple wigets in use
* Fixed a bug with the taxonomy filter within the widget

= 0.1.2 =
* Announcements now support shortcode in the body text
* Bug Fix: If no announcements are scheduled, the announcment block is hidden
* Announcements can now be filtered by category, allowing you to have multiple types of announcements (e.g. News, Alerts, Events, etc.)

= 0.1.1 =
* Added the ability to specify order of the announcements
* Announcement scrolling now pauses on mouseover

= 0.1 =
* Initial release.

== Additional Details ==

This plugin makes use of the JSColor library. The JSColor project is maintained by Jan Odv�rko and released under the GNU Lesser General Public 
License. See http://jscolor.com for more information.