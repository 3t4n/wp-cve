===  Easy Dash for LearnDash  ===
Contributors: WPTrat, Luis Rock
Tags: learndash, education, elearning, lms, learning
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 2.4.3
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Easy Dash for LearnDash: an improved (and easy) dashboard for your LearnDash site.

== Description ==
An improved (and easy) dashboard for your LearnDash site.

Main features (free version):

* Everything in one page
* Number of students, groups, comments, courses, lessons, topics, quizzes
* Courses enrolls, starts and completions
* Essays and Assignments pending
* Top Courses (by access mode and number of enrolled students)
* Top Groups (by members)
* Most Active Students
* Least Active Students
* Most Commenting Users
* Most Completed Courses
* Most Completed Lessons
* Most Completed Topics
* Most Completed Quizzes
* Courses with more comments
* Courses completed in the same day
* Courses stats over time
* Lessons stats over time
* Topics stats over time
* Quizzes stats over time
* Table with courses completions stats
* Table students activity
* Number of students
* get stats for filtered course
* get stats for filtered user
* get stats for filtered group

[Pro](https://wptrat.com/easy-dash-for-learndash?from=wporg) add-on Premium features (paid version):

* Shortcode to place the dash (global or filtered) wherever you like on the frontend of your site
* Shortcode attributes, so you can customize your dash (show or hide widgets)
* Export (to CSV, Excel or PDF), copy and print table data
* Export CSV file with course/user stats for filtered user/course/group
* Define column visibility on the dash tables
* Restrict group stats to leaders
* More premium features to come

Get the Easy Dash for Learndash Pro add-on at [WP Trat](https://wptrat.com/easy-dash-for-learndash?from=wporg)

== Installation ==
1. Upload plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Visit dashboard and settings through "Easy Dash" (LearnDash - LMS submenu) link.
4. Easy Dash for LearnDash Pro is an add-on, thus needing Easy Dash for LearnDash (free version) to be installed and activated

== Frequently Asked Questions ==
= Have feedback or a feature request? =

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins! Just drop us an email with your suggestion.

= Does Easy Dash mess with my website's database? =

* Not at all! Easy Dash do not insert or update data (except for a few transients, for caching purposes). It only reads data from WP and LearnDash database tables.

= Why are some values in my website's dash showing N/A? =

* This is due to inconsistencies in the wp_learndash_user_activity table in your site's database. The LearnDash plugin is responsible for filling this table with student activities.


== Screenshots ==
1. Menu
2. Dashboard - Filtered
3. Dashboard - Top Boxes
4. Dashboard - Charts
5. Dashboard - Tables

== Changelog ==


= 2.4.3 =
* New: Option to only allow group leaders and admins to have access to group stats (PRO)
* New: Hooks added to more functions

= 2.4.2 =
* New: Hooks added to several functions
* New: Option to exclude admin from course stats

= 2.4.1 =
* New: First and Last Name columns for filtered course users and filtered group users tables (show/hide columns with PRO version)
* Fix: Small adjustment to prevent some js errors

= 2.4.0 =
* New: Filter groups and get users and courses stats
* New: Export groups stats (PRO)
* New: Column Visibility on dash tables (PRO)

= 2.3.0 =
* Fixed: hours (not minutes) in dash for user filtered
* Fixed: N/A instead of negative numbers for time spent in course
* New: Show days, hours, minutes and seconds for time spent in course
* New: Export CSV file with course/user stats for filtered user/course (Pro)

= 2.2.0 =
* New: top groups (#members) in course filtered
* New: filter user and get its stats
* Improved: buttons to export data
* Fixed: some PHP notices

= 2.1.0 =
* New: added more options do "last x days" queries: '120', '180', '365' and 'all time'.
* Fixed: tested up to 5.9

= 2.0.0 = 
* New: filter course and get its stats
* New: edit widgets visibility directly on the dash
* Improved: translate everything (including widgets elements and details)
* Fixed: small bugs
* Attention pro users: shortcode attributtes (widgets numbers and names) may have changed


= 1.4.0 = 
* New chart: Lessons Stats Over Time (starts, completions)
* New chart: Topics Stats Over Time (starts, completions)
* New chart: Quizzes Stats Over Time (starts, completions)
* New chart: Most Completed topics (last x days)
* New chart: Most Completed quizzes (last x days)
* Attention pro users: shortcode attributtes (widgets numbers and names) have changed. Check the new ones on the shortcode tab (admin) and fix it on your page/post. 

= 1.3.0 = 
* Plugin prepared for the pro add-on premium feature: export (to CSV, Excel or PDF), copy and print table data

= 1.2.2 = 
* Plugin prepared for the pro add-on premium feature: a shortcode to display the dash on the frontend of your site, with attributes (so you can choose which widgets to show or hide)

= 1.2.0 = 
* NEW widget: courses stats over time

= 1.1.0 = 
* Fixed bug when there is no activity in the last X days.
* Fixed bug when there is no group.
* Fixed bug when there is no course.

= 1.0.0 = 
* Initial Release.