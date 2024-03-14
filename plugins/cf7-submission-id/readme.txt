=== UniqueID for Contact Form 7 ===
Tags: contact form 7, unique id, submission id
Contributors: tsjippy
Donate link: https://www.harmseninnigeria.nl/helpmee/
Requires at least: 4.0.0
Requires PHP: 5.2.4
Tested up to: 5.7.0
Stable tag: 1.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

An add-on for Contact Form 7 to add an unique id to every form submission.

== Description ==

Plugin for Contact Form 7: adds a field for an unique submission id.
You can choose to include a hidden or an visible field to your form.
This field will be automatically filled with an number which is incremented on every submission.

== Usage ==
Just add the field to your form.

You can filter the value using the cf7_submission_id_filter.
To add leading zeros for example, just put the code below in your functions.php

	add_filter('cf7_submission_id_filter', 'custom_cf7_submission_id_filter');
	function custom_cf7_submission_id_filter($val){
		return sprintf("%04d", $val);
	}

== Installation ==

Installing UniqueID for Contact Form 7 can be done either by searching for "UniqueID for Contact Form 7" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org.
2. Upload the ZIP file through the "Plugins > Add New > Upload" screen in your WordPress dashboard.
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Visit the settings screen and configure, as desired.

== Frequently Asked Questions ==

== Screenshots ==

1. The field generator
2. The add field button

== Changelog ==
= 2.4.0 =
* Not dependent on jQuery anymore
* Only increments on success

= 2.3.0 =
* Bugfix when submitting multiple forms at the same time
* Added the cf7_submission_id_filter

= 2.2.5 =
* only load js when needed

= 2.2.1 =
* bugfix when no id field in contact form

= 2.2.0 =
* Bug fix to take initial value into account

= 2.1.0 = 
* Bug fix to be compatible with CF7 5.2 and higher

= 2.0.1 = 
* Bug fix for new forms

= 2.0.0 = 
* Only update id on succesfull form submission, not on reload of the screen
* BREAKING CHANGE: name should contain "submission_id"

= 1.4.0 = 
* No reload of the page needed anymore, refresh is done via AJAX

= 1.3.0 = 
* Wait 5 deconds instead of 3 before reloading the page

= 1.2.2 = 
* Small bufix

= 1.2.1 = 
* Small bufix

= 1.2.0 =
* Added a 3 seconds delay before the auto-reload takes place

= 1.1.0 =
* Fixed bug where page is not reloaded when field is renamed.

= 1.0.0 =
* Initial release









