=== WP Datepicker ===
Contributors: fahadmahmood
Tags: datepicker, jquery-ui, html datepicker, date selection
Requires at least: 3.0.1
Tested up to: 6.3
Stable tag: 2.0.8
Requires PHP: 7.0
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
A great plugin to implement custom styled jQuery UI datepicker site-wide.

== Description ==
* Author: [Fahad Mahmood](https://www.androidbubbles.com/contact)
* Project URI: <http://androidbubble.com/blog/wordpress/plugins/wp-datepicker>
* Demo URI: <http://demo.androidbubble.com/wp-datepicker>


WP Datepicker is a free WordPress plugin to display date field on WordPress post, page, contact and event forms. You can enter multiple CSS selectors as CSV to display date field on desired forms or pages. You can enable date field for backend which means datepicker will be implemented in wp-admin as well. It is possible to make datepicker field editable or readonly. There is an option to enable weekends so, Saturdays and Sundays will be removed from datepicker as some service businesses do not offer weekend service. You can set months to full or short form and can edit date format or default date. It saves changes automatically.

WP Datepicker is a lightweight WordPress Plugin with variety of options without worrying about jQuery scripts for every other field in your scripts. It's a FREE plugin for advanced users obviously, because they can extend it with a little effort. Advanced version is also available with most of the demanded/common parameters under settings page. 

I hope you will enjoy this plugin and will feel it a convenience in your life.

= Tags =
calender, widget, popup
== Tutorial ==

= Video =
[youtube http://www.youtube.com/watch?v=eILaObbYucU]

= Blog Post =
http://androidbubble.com/blog/wordpress/plugins/wp-datepicker


###Compatibility List:

* GuavaPattern
* Genesis
* Thesis
* WooThemes
* Gantry
* Carrington Core
* Hybrid Core
* Options Framework
* Redux Framework
* SMOF
* UPThemes
* Vafpress
* Codestar

### Premium Features:
* Change close text, current text, min-date, max-date
* Inline position will display calendar beneath the field
* Change year, month, first day, year range
* Use custom colors and font selection
* Multi instances can be added
* Various datepicker styles and skins
* Show button panel
   
== Installation ==

How to install the plugin and get it working:


Method-A:

1. Go to your wordpress admin "yoursite.com/wp-admin"

2. Login and then access "yoursite.com/wp-admin/plugin-install.php?tab=upload

3. Upload and activate this plugin

4. Now go to admin menu -> settings -> WP Datepicker

Method-B:

1.	Download the WP Datepicker installation package and extract the files on

	your computer. 
2.	Create a new directory named `WP Datepicker` in the `wp-content/plugins` directory of your WordPress installation. Use an FTP or SFTP client to upload the contents of your WP Datepicker archive to the new directory that you just created on your web host.
3.	Log in to the WordPress Dashboard and activate the WP Datepicker plugin.
4.	Once the plugin is activated, a new **WP Datepicker** sub-menu will appear in your Wordpress admin -> settings menu.


== Frequently Asked Questions ==

= How date range works? =

When a date is selected in the first box, the number of days in this box will be disabled (including selected day) form the selected date. For example, if 10th March is selected in first field and in the backend, you have added 3 days so, in the second field day 10, 11 & 12 will be disabled. It will happen same when you add Months or Years in the backend. 

= Is this plugin speed optimized? =

Yes, this plugin is using independent CSS and JavaScript files instead of inserting scripts in web page source-code.

= Are all scripts in relevant files or rendered as HTML/CSS/JS tags? =

All scripts are well managed in relevant files. 

= Are styles and files will be generated one time or every-time when you save settings? =

Yes, it will generate the required files whenever you will modify something on settings page and that version will keep serving your visitors everytime with zero delay.

= Does it support multiple instances? =

Yes, in advanced version you can create multiple instaces so each field can have different settings.

= Is this compatible with all WordPress themes? =

Yes, it is compatible with all WordPress themes which are developed according to the WordPress theme development standards. 

= Is everything ready in this plugin for final deployment? =

Every theme will have different global styles so a few stylesheet properties will be required to be added and/or modified.

= Is it possible to disable specific days and dates? =

Yes, a code snippet is available to disable specific days and dates under custom scripts section.

= How to install WP Datepicker and Configure =

1) Go to plugin section (wp-admin) click on add new and then write wp datepicker in search bar
2) Click on install button wp datepicker and then click on activate respectively
3) Settings Menu > WP Datepicker > Settings Page

Here we have a few options: 

a) First option is Configure WP Datepicker by Input field's Id
b) Second option is Configure WP Datepicker by Input field's Class
c) Third option is Configure WP Datepicker by Input field's attribute. 
e.g. name, type and HTML5 data

= How to install WP Datepicker and configure it with Contact Form 7 =

[youtube http://www.youtube.com/watch?v=c2afBhUPp4w]

Go to Contact Menu (wp-admin) after installation of contactform7 plugin, click on Contact forms and here we have a contact form 1 by default, click on it. 
You will see something like this:


<label> Your Name (required)  [text* your-name] </label>
<label> Your Email (required)  [email* your-email] </label>
<label> Date 2   [date date-134 class:dp]</label>
<label> Subject  [text your-subject] </label>
<label> Your Message  [textarea your-message] </label>
[submit "Send"]

Create a new field with an id "#Calendar" Like: <label> Date [date date-726 id:calendar]</label>
Create second field with a class ".dp" Like: <label> Date 2   [date date-134 class:dp]</label>
Create second field with a class ".dp" like: <label> Date 3   [date date-499]</label> having no id and class

I) Now go to the options panel in the input field write id of the first input field with hash sight #calendar and click on save
then refresh page, here first input field have a calendar and other two fields do not have this calendar.

II) Now we configure with second option write second input field's class here with dot sign by separating with comma like #calendar, .bday
then refresh your page and here the second field also has this calendar.

III) Now configure with input field's name, write it by separating comma like #calendar, .bday, input [name="datepicker"]
and refresh your page. Click on the third field to try, this field will also have this calendar option.

Finally:
The first "Date" field is configured with id, second "Date 2" with input field's class and third "Date 3" with input field's name.

= How can I report an issue to the plugin author? =

It's better to post on support forum but if you need it be fixed on urgent basis then you can reach me through my blog too. You can find my blog link above.

== Screenshots ==

1. WP Datepicker > Default Settings Page - 1
2. WP Datepicker > Preview - 2
3. WP Datepicker > Preview - 3
4. WP Datepicker > Preview - 4
5. WP Datepicker > Implementation inside content editor
6. WP Datepicker > Preview - 5
7. WP Datepicker > Settings Page - 2
8. WP Datepicker > Settings Page - 3
9. WP Datepicker > Go Premium
10. Speed Optimization
11. Date range > selection from a specific date to a specific date.
12. How date range works?
13. Default date & extended options for datepicker.
14. Recommended plugins.

== Changelog ==
= 2.0.8 =
* Fix: Speed Optimization feature related improvement. [02/10/2023][Thanks to Cristiano Fava]
= 2.0.7 =
* Fix: Google Fonts implementation refined. [12/06/2023][Thanks to Kitty Bakker / TexelSites]
= 2.0.6 =
* Fix: Fatal error: Uncaught TypeError: extract(): Argument #1 ($array) must be of type array, string given in. [25/05/2023][Thanks to FeralReason]
= 2.0.5 =
* WP-CLI error: Undefined array key "HTTP_HOST", fixed. [09/03/2023][Thanks to gmariani405]
= 2.0.4 =
* WPML compatibility revised. [04/12/2022][Thanks to Richard Praschil]
= 2.0.3 =
* Date range selection enabled/disable functionality improved. [03/12/2022][Thanks to Richard Praschil]
= 2.0.2 =
* Datepicker date-range section will work with CSV CSS selector values as well. [07/10/2022][Thanks to Matthew / Vitaliy]
= 2.0.1 =
* Datepicker date-range section will work with CSV CSS selector values as well. This was a free feature but was not working with multiple selectors before. [07/10/2022][Thanks to Matthew / Vitaliy]
= 2.0.0 =
* Datepicker settings page instances, box height set to min-height instead of fixed. [03/04/2022][Thanks to Matej Fanco]
= 1.9.9 =
* Newly added function to disable days from November 24 to Dec 25 has been improved. [Thanks to shovonboshak]
= 1.9.8 =
* New function added to disable days from November 24 to Dec 25. [Thanks to shovonboshak & Abu Usman]
= 1.9.7 =
* Datepicker date range related improvements. [Thanks to Petar Jovović / Softech Solutions]
= 1.9.6 =
* Datepicker CSS interferes with Divi Theme in Admin. Global settings introduced. [Thanks to zamartz]
= 1.9.5 =
* Alive scripts toggle function provided for on fly HTML elements cloning of datepicker fields. [Thanks to Edwin Makenzi]
= 1.9.4 =
* Missing JS concatenation operator causes error. Fixed. [Thanks to thaikolja]
= 1.9.3 =
* WPML compatibility revised.
= 1.9.2 =
* Improved UX introduced. [Thanks to Team AndroidBubbles]
= 1.9.1 =
* Default date value attributed revised. [Thanks to Joshua Eberly]
= 1.9.0 =
* Assets updated.
= 1.8.9 =
* Updated with auto JS and auto CSS files. [Thanks to Makenzi Edwin]
* Date Range functionality introduced as beta. [Thanks to Sebastian]
= 1.8.8 =
* Default date current date related fix. [Thanks to Howard Blythe from hwbdesign]
= 1.8.7 =
* Developer mode improved. [Thanks to Team Ibulb Work]
* JS and CSS files will be generated on plugin activation automatically. [Thanks to Edmund Wallner]
= 1.8.6 =
* Language files updated.
= 1.8.5 =
* WP Hamburger added on settings page.
= 1.8.4 =
* Tags updated and settings page improved.
= 1.8.3 =
* Version update for WordPress.
= 1.8.2 =
* Performance optimization added, scripts combined and enqueued in auto generated files. [Thanks to Ryan Ouellette & Team Ibulb Work]
= 1.8.1 =
* Android App released. [Thanks to Team Ibulb Work]
= 1.8.0 =
* data-default added and tested again. [Thanks to @feralreason]
= 1.7.9 =
* Invalid argument supplied for foreach(), fixed. [Thanks to @threechatons3]
= 1.7.8 =
* data-default date added. [Thanks to @feralreason]
= 1.7.7 =
* Disable specific set of dates, another custom script added. [Thanks to Howard Blythe]
= 1.7.6 =
* Custom scripts managed with a dropdown.
= 1.7.5 =
* A few important fixes regarding multiple instances. [Thanks to Rais Sufyan]
= 1.7.4 =
* Video tutorials added for JavaScript custom functions. [Thanks to Rais Sufyan]
= 1.7.3 =
* Go Premium image added. [Thanks to Rais Sufyan]
= 1.7.2 =
* Multi-instance functionality introduced. [Thanks to Ibulb Work Team]
= 1.7.1 =
* Updated demo URI. [Thanks to Norke88]
= 1.7.0 =
* Another example script added to disable certain months. [Thanks to Largo WInzclav]
= 1.6.9 =
* jQuery for mobile issue resolved. [Thanks to Pieter Grobler]
= 1.6.8 =
* Faizan-e-Madina premium style added. [Thanks to Bolekula]
= 1.6.7 =
* Google Fonts will not load until you will select one of those. [Thanks to Justin Chalfant]
= 1.6.6 =
* Calendar icon to the datepicker fields. [Thanks to pansuriya123]
= 1.6.5 =
* Readonly field value saving issue on settings page fixed. [Thanks to Paul Singh]
= 1.6.4 =
* Default date option added in free version. [Thanks to princebhalani143]
= 1.6.3 =
* Custom style screenshots fixed. [Thanks to Rais Sufyan]
= 1.6.2 =
* Custom style issue fixed.
= 1.6.1 =
* Settings page revised.
= 1.6.0 =
= 1.5.9 =
* Font selection reviewed. [Thanks to Rafa / X2CREATIVOS]
= 1.5.8 =
* Inline position feature restricted with an extra condition.
= 1.5.7 =
* Inline position feature added. [Thanks to Benji McKinney / Moxa Media]
= 1.5.6 =
* More languages added. [Thanks to Abu Usman]
= 1.5.5 =
* Contact form 7 video tutorial added on settings page. [Thanks to Trish Parr]
= 1.5.4 =
* Contact form 7 video tutorial added. [Thanks to Paul Scollon]
= 1.5.3 =
* Read-only and editable option added through settings page. [Thanks to bjoern76]
= 1.5.2 =
* Each selector should have a separate default value. [Thanks to Raul Pinto]
= 1.5.1 =
* Autocomplete OFF. [Thanks to Michael Ellis]
= 1.5.0 =
* Update regional settings with dateFormat overridden possibility. [Thanks to Jmashweb]
= 1.4.9 =
* Added a textarea field for beforeShowDay. [Thanks to William V. Hughes]
= 1.4.8 =
* Added extra checks for front end scripts. [Thanks to Ricardo Orozco Vergara]
= 1.4.7 =
* Added a check for admin side scripts. [Thanks to rabidin]
= 1.4.6 =
* JS interval based errors are stopped. [Thanks to Arnold S]
= 1.4.5 =
* Custom colors are improved. [Thanks to Dalia Herceg]
= 1.4.3 =
* Weekends can be turned off now. [Thanks to Tem Balanco]
= 1.4.1 =
* Language selection refined and today button functionality added. [Thanks to Richard Rowley]
= 1.4.0 =
* Default value issue reported and fixed. [Thanks to Guy Hagen]
= 1.3.9 =
* Capabilities and roles related bug fixed. [Thanks to Paul Munro]
= 1.3.8 =
* Sanitized input and fixed direct file access issues.
= 1.3.7 =
* Multilingual months can be in short and full. These are now capitialize as well. [Thanks to Jose Braña]
= 1.3.6 =
* Change year related option refined. [Thanks to Makenzi Edwin]
= 1.3.5 =
* Repeater fields compatibility refined
= 1.3.4 =
* Repeater fields compatibility added
= 1.3.3 =
* Datepicker dateFormat option provided.
* Translated in German language.
= 1.3.2 =
* Datepicker options refined.
= 1.3.1 =
* Datepicker with 74 languages.
= 1.3 =
* jQuery live to on [Thanks to nickylew]
= 1.2.9 =
* minDate & maxDate added in Pro version.
= 1.2.8 =
* Fixed: Stopping google translate from translating datepicker.
= 1.2.7 =
* A few minor fixes.
* FAQ's are added.
= 1.2.6 =
* Code Generator Added.
= 1.2.4 =
* An important fix related to mobile responsive layout.
= 1.2.3 =
* An important fix.
= 1.2.2 =
* A few important tweaks.
= 1.2.1 =
* A javascript file excluded.
= 1.2 =
* More styles are added.
= 1.1 =
* Options & ColorPicker added for Pro Users.
= 1.0 =
* Initial Commit

== Upgrade Notice ==
= 2.0.8 =
Fix: Speed Optimization feature related improvement.
= 2.0.7 =
Fix: Google Fonts implementation refined.
= 2.0.6 =
Fix: Fatal error: Uncaught TypeError: extract(): Argument #1 ($array) must be of type array, string given in.
= 2.0.5 =
WP-CLI error: Undefined array key "HTTP_HOST", fixed.
= 2.0.4 =
WPML compatibility revised.
= 2.0.3 =
Date range selection enabled/disable functionality improved.
= 2.0.2 =
Datepicker date-range section will work with CSV CSS selector values as well. [07/10/2022][Thanks to Matthew / Vitaliy]
= 2.0.1 =
Datepicker date-range section will work with CSV CSS selector values as well. This was a free feature but was not working with multiple selectors before.
= 2.0.0 =
Datepicker settings page instances, box height set to min-height instead of fixed.
= 1.9.9 =
Newly added function to disable days from November 24 to Dec 25 has been improved.
= 1.9.8 =
New function added to disable days from November 24 to Dec 25.
= 1.9.7 =
Datepicker date range related improvements.
= 1.9.6 =
Datepicker CSS interferes with Divi Theme in Admin. Global settings introduced.
= 1.9.5 =
Alive scripts toggle function provided for on fly HTML elements cloning of datepicker fields.
= 1.9.4 =
Missing JS concatenation operator causes error. Fixed.
= 1.9.3 =
WPML compatibility revised.
= 1.9.2 =
Improved UX introduced.
= 1.9.1 =
Default date value attributed revised.
= 1.9.0 =
Assets updated.
= 1.8.9 =
Updated with auto JS and auto CSS files.
= 1.8.8 =
Default date current date related fix.
= 1.8.7 =
Developer mode improved.
= 1.8.6 =
Language files updated.
= 1.8.5 =
WP Hamburger added on settings page.
= 1.8.4 =
Tags updated and settings page improved.
= 1.8.3 =
Version update for WordPress.
= 1.8.2 =
Performance optimization added, scripts combined and enqueued in auto generated files.
= 1.8.1 =
Android App released.
= 1.8.0 =
data-default added and tested again.
= 1.7.9 =
Invalid argument supplied for foreach(), fixed.
= 1.7.8 =
data-default date added.
= 1.7.7 =
Disable specific set of dates, another custom script added.
= 1.7.6 =
Custom scripts managed with a dropdown.
= 1.7.5 =
A few important fixes regarding multiple instances.
= 1.7.4 =
Video tutorials added for JavaScript custom functions.
= 1.7.3 =
Go Premium image added.
= 1.7.2 =
Multi-instance functionality introduced.
= 1.7.1 =
Updated demo URI.
= 1.7.0 =
Another example script added to disable certain months.
= 1.6.9 =
jQuery for mobile issue resolved.
= 1.6.8 =
Faizan-e-Madina premium style added.
= 1.6.7 =
Google Fonts will not load until you will select one of those.
= 1.6.6 =
Calendar icon to the datepicker fields.
= 1.6.5 =
Readonly field value saving issue on settings page fixed.
= 1.6.4 =
Default date option added in free version.
= 1.6.3 =
Custom style screenshots fixed.
= 1.6.2 =
Custom style issue fixed.
= 1.6.1 =
Settings page revised.
= 1.6.0 =
= 1.5.9 =
Font selection reviewed.
= 1.5.8 =
Inline position feature restricted with an extra condition.
= 1.5.7 =
Inline position feature added.
= 1.5.6 =
More languages added.
= 1.5.5 =
Contact form 7 video tutorial added on settings page.
= 1.5.4 =
Contact form 7 video tutorial added.
= 1.5.3 =
Read-only and editable option added through settings page.
= 1.5.2 =
Each selector should have a separate default value.
= 1.5.1 =
Autocomplete OFF.
= 1.5.0 =
Update regional settings with dateFormat overridden possibility.
= 1.4.9 =
Added a textarea field for beforeShowDay.
= 1.4.8 =
Added extra checks for front end scripts.
= 1.4.7 =
Added a check for admin side scripts.
= 1.4.6 =
JS interval based errors are stopped.
= 1.4.5 =
Custom colors are improved.
= 1.4.3 =
Weekends can be turned off now.
= 1.4.1 =
Language selection refined and today button functionality added.
= 1.4.0 =
Default value issue reported and fixed.
= 1.3.9 =
Capabilities and roles related bug fixed.
= 1.3.8 =
Sanitized input and fixed direct file access issues.
= 1.3.7 =
Multilingual months can be in short and full. These are now capitialize as well.
= 1.3.6 =
Change year related option refined.
= 1.3.5 =
Repeater fields compatibility refined
= 1.3.4 =
Repeater fields compatibility added
= 1.3.3 =
Datepicker dateFormat option provided and translated in German language.
= 1.3.2 =
Datepicker options refined.
= 1.3.1 =
Datepicker with 74 languages.
= 1.3 =
jQuery live to on [Thanks to nickylew]
= 1.2.9 =
No need to updated if you are using FREE version.
= 1.2.8 =
Fixed: Stopping google translate from translating datepicker.
= 1.2.7 =
A few minor fixes.
= 1.2.6 =
Code Generator Added.
= 1.2.4 =
An important fix related to mobile responsive layout.
= 1.2.3 =
An important fix.
= 1.2.2 =
A few important tweaks.
= 1.2.1 =
An important fix.
= 1.2 =
More styles are added.
= 1.1 =
Options & ColorPicker added for Pro Users.
= 1.0 =
Initial Commit

== Arbitrary section ==

I would appreciate the suggestions related to new features. Please don't forget to support this free plugin by giving your awesome reviews.

== A brief Markdown Example ==

Ordered list:

1. Can be used with WooCommerce
2. Exceptional support is available
3. Developed according to the WordPress plugin development standards





== License ==
This WordPress Plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version. This free software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this software. If not, see http://www.gnu.org/licenses/gpl-2.0.html.