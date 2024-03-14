=== Dynamic Time ===
Contributors: rermis
Tags: timecard, timesheet, time track, time management, time punch
Requires at least: 6.0
Tested up to: 6.4
Stable tag: 5.0.14
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

The number one timesheet plugin for WordPress. A simple calendar-based timecard and time management solution. 

== Description ==
A simple calendar-based timesheet and timecard plugin for your WordPress website. This WordPress timesheet can record hours & notes on weekly, bi-weekly, monthly or bi-monthly schedules, including automatic overtime calculations. Dynamic Time is mobile compatible and integrates with existing WordPress users.

## Features
&#9745; **Automatic Overtime** calculations, configurable by user, even across pay periods

&#9745; **Multiple Time Punches** per day with Predictive entry

&#9745; **Fully Configurable Pay Periods**, including notes & bonus amount field

&#9745; **Approval Process** between user, supervisor and payroll

&#9745; **Automatic User Integration** with existing WordPress users

## PRO Features
&#9989; **Custom Categories** - Supports custom PTO and Regular Categories

&#9989; **PTO Bank** - With Automatic Annual Accruals

&#9989; **Signature Pad** - Mobile and desktop compatible

&#9989; **Reporting Tools** - Filter and total time entries

&#9989; **CSV export** - Compatible with Excel

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/dynamic-time` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the \'Plugins\' screen in WordPress
3. Visit Dynamic Time from the WordPress admin menu to configure settings

== Screenshots ==
1. Timesheet set up for bi-weekly pay period
2. Settings Page with user list of entries

== Changelog ==
= 5.0.14 = * Improvements to deleted user handling & archival. Minor bug fixes.
= 5.0.12 = * Bug fixes to user level period length.
= 5.0.11 = * Improvements to display-all option, name fetching, and deleted users.
= 5.0.9 = * Minor improvements and readme updates.
= 5.0.8 = * Improved diagnostics.
= 5.0.7 = * Minor improvements to current period submission logic, current signer assignment.
= 5.0.5 = * CSS refinements. Predictive entry toggle. Entry default control improvements. Punch setting bug fixes.
= 5.0.4 = * Bug fixes for contributor role in admin view. Persistent settings fix.
= 5.0.3 = * JS bug fixes for time period cycling.
= 5.0.2 = * Signature pad support. Print view improvements. Numerous bug fixes. Setup refinements. Rendering improvements and shortcode max_width option. Highlight current day timezone fix. Exempt defaults.
= 4.2.15 = * Allow rate values more than 99.99.
= 4.2.14 = * Readme SEO updates.
= 4.2.11 = * Use WP site title in approval email instead of from Dynamic Time.
= 4.2.9 = * Compatibility with WP 6.3
= 4.2.8 = * Fix for excessive backslash escape chars in notes and categories. 
= 4.2.7 = * New period per user to default to last created user period.
= 4.2.6 = * Setup improvements.
= 4.2.5 = * Improved user filter for entry module.
= 4.2.3 = * Bug fix to summary timestamps in admin view.
= 4.2.2 = * CSS admin icons, ensure no text-decoration applies.
= 4.2.1 = * Stability improvements. Change logic retrieving last submitted and approved timestamps in entry module.
= 4.1.5 = * UDF sql prepare() bug fix.
= 4.1.4 = * Bug fix: Re-escape chars in notes.
= 4.1.3 = * Bug fix: Approval timestamps. Updated query function for period data.
= 4.1.2 = * Addition of human date to period table. Various bug fixes affecting period end timestamps and user period dates.
= 4.0.9 = * Misc var escaping. Consolidated SQL prepare into UDF. View notes on entry hover. Switch df_date generation from JS to PHP for consistency between users.
= 4.0.6 = * Obtain current day from php instead of JS.
= 4.0.5 = * Added CSS classes to calendar days.
= 4.0.4 = * CSS bug fix to nav buttons.
= 4.0.3 = * Update to biweekly period adjustment setup.
= 4.0.2 = * Punch only entry setting JS bug fixes.
= 4.0.1 = * General improvements. User dropdown menu bug fixes.
= 4.0.0 = * Multisite compatibility. Overtime bug fixes. PRO Upgrade improvements. Biweekly schedule sync to admin. Submitting & Approving IP logging.

== Frequently Asked Questions ==

= Does this plugin have a user limit? =
This plugin works with an unlimited number of users/employees.

= How do users get started? =
To get started, users just need a WordPress login and the URL to the page where the shortcode is pasted.  Once they save time, it will show up to administrators in the Dynamic Time admin page.

= Do I have to use the [dynamicTime] shortcode? =
No, if your users have access to the WordPress dashboard they can click Dynamic Time in the main WordPress menu. If a user is not an administrator they will only see their timecard on this page.

= How do I make sure time and pay rates are private? =
WordPress administrators (with list_users permission) can see all users' time and pay rates. If a user is not an administrator or an assigned supervisor to someone else, they will only be able to see their own timecard. Any user that views the page where the shortcode lives (that is not logged in) will be redirected to login first.

= Why can't I submit my timecard for approval? =
Users cannot submit time until approx one week within the time period ending.  This is to prevent users from accidentally submitting time too early. If you are an administrator you can bypass this requirement by viewing the timecard from the Dynamic Time admin page.

= Will supervisors receive notification that a timecard is submitted? =
Yes, supervisors can be assigned to every user, also a payroll admin can be assigned as a whole.  If a supervisor is assigned, an email will be sent to them when a user submits a pay period for approval. If a payroll admin is assigned, an email will be sent to them when a supervisor approves a user's pay period.

= Do supervisors require a particular role? =
Supervisors are not required to hold any particular type of role, although providing supervisors with WP Dashboard access (minimum Reader role with moderate_comments capability) will allow users assigned to the supervisor to be displayed in a list.

= Biweekly time period does not begin on the correct week =
If your schedule is set to Biweekly, you may alter the beginning week by clicking the dates at the top of the timecard to advance in one week increments, forward or reverse.   Once the beginning week is correct, continue to use the arrows on the left and right to navigate between pay periods.

= Why do time periods appear differently between admin and user screens? =
Ensure that the pay period beginning and end dates match up on both the admin screen and user screen.  Click the blue dates at the top of the timecard to advance in one week increments, forward or reverse.

= Why do time periods appear differently between admin and user screens? =
Ensure that the pay period beginning and end dates match up on both the admin screen and user screen.  Click the blue dates at the top of the timecard to advance in one week increments, forward or reverse.

= Can I add more time labels instead of just Reg (Regular Time) and PTO (Paid Time Off)? =
The plugin was designed around just a few types of time, Reg, PTO, and automatic overtime.  If more categorization or labels are needed, we recommend using the notes section (on each time entry) as an additional field. In the PRO version, an additional dropdown is offered for categorization and note sections can be filtered.

= I have selected bi-weekly pay periods but the period needs to begin on week 2 instead week 1 =
Each user can click the date at the top of the timecard (week 1 to go back, week 2 to go forward) to increment one week on a bi-weekly schedule. This alignment will be necessary only the first time they use the plugin.

= Why can't I find a user in the supervisor menu? =
If you have more than 1000 users, the plugin will display the last 1000 active users in the supervisor dropdown menu. If a user doesn't appear on the list, have that user log into WordPress, then reload the Dynamic Time admin page.

= I am finding time or date inconsistencies on the timesheets =
Dynamic Time uses javascript to obtain local system time.  If the user is in a private/incognito tab or on a browser that doesn't support js, the time entry might be inaccurate.

= How do I delete users? =
User management is accomplished through the native WP user profiles.  Deleting users is not necessary, as idle users will fall off the main entry list if no time is received in the last month.  To reduce accidental loss data, Dynamic Time does not automatically delete time entry data if a WP user is deleted.

= How do I delete entries? =
To remove a previously saved time entry in simple entry mode, just type -0 (negative zero) into the hours field.  To remove an entry in itemized mode, adjust clock-in and clock-out times to the same time.

= How is overtime calculated? =
Overtime is designated on the status dropdown menu, next to each user's name. There are two types of overtime supported, 'Standard FLSA', and 'California'.  FLSA considers overtime as time and a half for hours worked in excess of 40/hours per week.  California considers overtime as time and a half for hours worked in excess of 8 hours/day or 40 hours/week.  'Exempt' status will not apply overtime under any condition.

= Can I change the timecard color? =
The front end timecard primary color can be changed by declaring the css variable --dyt_clr. For example, pasting <style>:root{--dyt_clr:darkred!important}</style> below the timecard shortcode will display the timecard in dark red.

= Where can I get more information on Dynamic Time PRO? =
[Dynamic Time PRO](https://richardlerma.com/dyt-terms/) provides reporting tools useful for larger groups of employees, including copying & paste data into Excel, table based overviews, and searching employee note fields.
