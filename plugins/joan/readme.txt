=== Jock On Air Now ===
Contributors: ganddser  
Plugin URI: https://wordpress.org/plugins/joan/
Donate link: https://paypal.me/gandinc
Tags: DJ, radio schedule, programming, webcast, Ajax, radio station, radio jock, manager
Requires at least: 3.2
Tested up to: 6.4.2
Stable tag: 5.7.9
Author URI: https://gandenterprisesinc.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily manage your station's on air schedule and share it with your website visitors and listeners using Jock On Air Now (JOAN). Use the widget to display the current show/Jock on air, display full station schedule by inserting the included shortcode into any post or page. Your site visitors can then keep track of your on air schedule. 


== Description ==

JOAN, is a simple yet powerful WordPress plugin, makes managing your station's on air schedule and sharing it with your website visitors and listeners a smooth and effortless experience. With its user-friendly widget, you can easily display the current show or jock on air. And for a comprehensive station schedule, you can effortlessly insert the included shortcode into any post or page. This way, your site visitors can stay up-to-date with your on air schedule and never miss a beat. Try JOAN (Jock On Air Now) today and see how it can take your radio station's online presence to the next level.

Features 

*Easily create and edit shows
*Customize your "Off Air Message".
*Hide or show next show/event.
*Turn schedule ON/Off (Requires show next JOAN to be set to No)
*Add show/jock external URL
*Add jock/show image 
*On Air Now notification widget
*Add On Air Now widget to Posts or Pages.
*Seven day schedule shortcode
*Daily schedule Shortcode
*Supports non-standard WP database prefix

== Installation ==

Download and install using the standard WordPress plugin installation method then activate the Plugin from Plugins page.

Find Jock on air now in the Admin menu click to begin adding or editing your schedule.

Go to WP Settings->General; select your broadcast timezone and set the time format to 24/hrs clock (military time) or (H:i). 1300 = 1pm, 00:00 = 12 am.

== Frequently Asked Questions ==

Q: How do I setup my schedule?

A: 1 - See the screenshots
     2 - Click on the Schedule button in settings and fillin the reqired fields. 
     3 - If a show goes past midnight i.e Monday 10 PM - 12 AM, You'll get errors. Instead do, Monday 22:00 - Monday 23:59           Then, Tuesday 00:00. -

Q: After I upload an image and click "Insert into post" the image doesn't show.

A: Before clicking "Insert Into Post" make sure the "Link URL" field is empty if it's not clear it. Then click insert into post. 

Q: I get a destination already exists error, plugin install failed, why?

A: You should deactivate and then delete any previous versions of the the plugin then install this version.

Q. Plugin could not be activated because it triggered a fatal error.

A. Most likely you installed this version before removing the previously installed version  3.2.1 or lower. Deactivate and delete it and try activating this one again.

== Screenshots ==

1. The Show data input form
2. A properly created schedule
3. How it looks on the frontend using the Widget and the Day Schedule
4. Inserting a Jock/show image 
5. The error you get if you try to add a show to an existing timeslot in the database
6. Shows two instances of incorrect scheduling. JOAN expects you to use every hour. But do not overlap.
7. Shows the correct time format setting.
8. Shows incorrect end of day settings. 
9. Shows the correct setting for end of day.
10. Shows user adding a recurring show in JOAN Premium
 

== Display Options ==

Display the current Jock/Show in a sidebar. Find the Joan Widget in your Appearance->Widgets settings, add to your sidebar 
or add [joan-now-playing] to any page.

Display your stations Weekly Schedule. Create a new page, name it Full Schedule place the schedule Shortcode save and add to your site navigation.[joan-schedule]

Automatically display your schedule for each day of the week.
[schedule-today]

== Custom CSS ==


.joan-now-playing,.joan-container * {
                        font-family: Arial;
                        font-size:16px;
                        color: #000000; }

Affects Widget only text

.joan-now-playing * {

  font-family: Arial;
  font-size:21px;
  color: #000000; } 


== Changelog ==
5.7.9
*Fixed issue with language file

5.7.8
Added Translation
Added custom css option

5.7.7  
No major changes

5.7.3 
No major changes

5.7.2
Minor bug fixes
Works with latest WordPress version

5.7.1
Minor bug fixes
Works with newest WP version

5.7.0
Minor bug fixes.

5.6.9
Fixed ON/Off switch failing.

5.6.8
Fixed issue with input form when using Manual Offsets.

5.6.7
Works with WP 5.9
Fixed time error
Changed menu title to JOAN
Added ability to remove title from JOAN widget


5.6.6
Bug fixes

5.6.5
Few cosmetic changes
Minor bug fixes

5.6.4
Fixed a bug which affected editing existing schedule

5.6.3
Bug fix

5.6.2
Fixed reported security vunerability

5.6.1
Assets and readme file update

5.6
No code changes

5.5.9
Minor bug fixes

5.5.8
Minor bug fixes

5.5.7
Minor bug fixes

5.5.6
Fixed edit/delete issue

5.5.5
Fixed jquery conflict causing Widget to not display DJ/Show image

5.5.4
Minor bug fixes

5.4
Minor bug fixes

5.3.0
Fixed css 404 error

4.7.7
No changes

4.7.6
Added Elementor plugin support

4.7.5
Fixed on/off switch

4.7.4
Minor error fixed

4.7.3
Added uninstall function

4.7.2
Minor error fixed

4.7.1
Minor fix

4.7.0
Minor bug fix

4.6.0 
Minor changes

4.5.0
Updated FAQs.
Fixed version error. 

4.3.0
Added target blank to show URLs in on air widget.
Fixed PHP 7 error Deprecated: Methods with the same name as their class will not be constructors in a future version of PHP.
Fixed high CPU usage issue.

4.2.3
No major changes. Works with WP 4.6 

4.2.2
Added new screenshots for adding images.

4.2.1
Fixed add media/images in WP posts and pages.

Fixes JS conflict in admin pages.

4.1.1
Added ability to show schedule for each day e.g. What's on today - Monday. 
Version 4.1.1 Fixes all deprecated WordPress Functions.

4.0
Upgrade to version 4.0.

This is a major upgrade, you absolutely MUST save a copy of your current schedule and delete the previously installed version before upgrading as it will wipeout the existing database and start a new. You'll also get an error because WP will be looking for a file that no longer exists. You may need to replace your schedule display short code to this [joan-schedule]. This is because we re-wrote the database and the plugin and removed all references to the old database. Applies only to those upgrading from 3.2.1 or lower to 4.0.

Wipes old database, starts fresh, saves database from there going forward.
Removed Template Tag option, we feel the standard display options are sufficient.

3.2.1 
Fixed issue in crud declaration.
Fixed security issue.

3.1.2 
Added plugin icon, screenshots.

3.1.1 
Improved plugin code. 
Properly en queued jQuery.


== Switch to JOAN Premium ==

Upgrade your listener engagement to new heights with JOAN Premium. Experience effortless management of your on-air schedule, showcasing of your current and upcoming shows, and add an "On Air Now/Upcoming Jock" widget with ease. Unlock exclusive benefits such as free lifetime upgrades and support, Multi-site support, localization readiness, and simple editing of your existing schedule. Don't miss out on the chance to revolutionize your radio station! Choose JOAN Premium today.

-And get:
*Everything found in JOAN Lite plus;
*Social media Share current (Facebook & Twitter)
*WP User Role - designate a user to manage your schedule.
*Supports Localization
*Multi-site support
*Import/Export schedule
*Free upgrades
*Access to new features
*Display time in 24/Hrs format
*Add/show schedule image
*Update show status
*Show/Hide show status
*Add Default Jock Image
*Jock image resizer
*Multiple display shortcodes
*Grid/List view schedule page
*Edit schedule without having to first delete shows
*Add an to the schedule page
*Easily ‘duplicate show schedules (Recurring shows)
*Priority Support

Purchase only from our website: [JOAN Premium](https://gandenterprisesinc.com/premium-plugins/ "Premium Features and Support, go beyond the basics"). Premium Features and Support, go beyond the basics.