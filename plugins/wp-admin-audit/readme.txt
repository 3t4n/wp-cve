=== WP Admin Audit ===
Contributors: brandtoss
Tags: activity log, admin activity report, audit log, audit trail, security audit log, security event log, security incident log, siem, wordpress admin tracking, wordpress admin monitoring
Tested up to: 6.4
Stable tag: 1.2.9
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Admin Audit monitors the security relevant activities on your site, keeps an event log and tells you when something out of the usual is happening.

== Description ==

<strong>The modern activity log solution for WordPress</strong>

[WP Admin Audit](https://wpadminaudit.com/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WADA&utm_content=plugin+repo+description) is the powerful monitoring log plugin for WordPress.
Site owners and administrators can sleep better at night knowing the plugin keeps track of all site changes, security events, and admin activities.

Ever wondered

* who unpublished that blog post?
* when the new media files were uploaded?
* how that weird user account was created?

The WordPress activity log in WP Admin Audit answers this questions.

Keep track of everything that happens on your WordPress sites to:

* Have a modern log of the changes done
* Know about security-relevant activities
* Find out who did what and at which time
* Analyze the steps that led to a technical problem
* Identify and mitigate automated login attempts by bots

### What is being logged?
The short answer: almost all changes on your WordPress site, but you can decide what is kept in the audit log.

The longer answer: WP Admin Audit has sensors that monitor the changes in your WordPress site and record what actions were performed by which user at which time on which item. A summary of the types of monitored events is below.

* **Content:** Page and Post changes (e.g. post created/updated/published/unpublished/deleted)
* **Taxonomy:** Changes to Categories and Tags (e.g. tag is created, updated, or deleted)
* **User:** User registration, user profile updates, password resets, user deletions, login, and logout
* **WordPress:** Updates of the WordPress core version, settings updates (general/writing/reading/discussion/media/permalink/privacy settings)
* **Plugin:** Installation, activation, updates, deactivation, and deletion of plugins
* **Theme:** Installation, activation (theme switch), update, and deletion of themes
* **Media:** Media file and data creations, updates, and deletions
* **Menu:** Creation, updates, and, deletions of menus
* **Comment:** Comment creations, updates, deletions, and status changes (approved, unapproved, spammed, etc.)
* **File:** File changes via the  plugin file editor and theme file editor

See the complete list of sensors, i.e. [the event types that are stored in the WordPress activity log](https://wpadminaudit.com/documentation/wp-admin-audit/sensors/event-types/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WADA&utm_content=plugin+repo+description).

For every event WP Admin Audit records:

* Event type
* Date and time
* IP address (the action/event originated from)
* Acting user (the user who did the change)
* Subject (the item e.g. a post the action is done with/to)

### Features (free)
Besides the WordPress event log, WP Admin Audit also features:

* **Powerful search & filtering:** Powerful free-text search as well as filtering by all sorts of categories makes it easy to find the data you are interested in.
* **Administrator & user audit:** Find inactive administrator accounts and review the users’ last login dates. Check on their individual activity log.
* **Login attempts audit:** Monitor logins to be aware of automated (brute-force) attacks and to identify IP addresses for blocking.

### Features (premium editions)
Upgrade to the [premium editions](https://wpadminaudit.com/pricing/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WADA&utm_content=plugin+repo+description) for the following features:

* **Third-party plugin support:** Optional extensions help you capture events happening in other WordPress plugins. [See our extension directory for more details.](https://wpadminaudit.com/extensions/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WADA&utm_content=plugin+repo+description)
* **Notifications:** Select event types or event severity levels (e.g. critical and high) for instant notification via email. You can choose whole user groups (e.g. administrators), individual WordPress users, or selected email addresses.
* **Offsite archive / Replication:** To increase security and for backup purposes, you can forward the events for storage to an external logging provider.
* **Enforce password changes:** You can enable a policy that requires users (with specific user roles) to change their passwords regularly. For example, administrator accounts can be required to change their passwords at least every 90 days.
* **CSV export:** Export events, users, and login attempts to CSV files.

[Click here for more details and for a complete feature list](https://wpadminaudit.com/feature-comparison/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WADA&utm_content=plugin+repo+description)

== Frequently Asked Questions ==

= Where is the documentation?  =
Please see the [documentation on our site](https://wpadminaudit.com/documentation/wp-admin-audit/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WADA&utm_content=plugin+repo+description).

= How do I get support?  =
Feel free to reach out directly via the [contact form](https://wpadminaudit.com/contact/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WADA&utm_content=plugin+repo+description).

= Will you add event x?  =
Most probably yes!
We know there are still some WP core events missing. This applies even more to 3rd party plugins where we want to release more extensions.
The good news is that we very actively developing the plugin and look for the feedback of the users to know which events first to add. You can share your feedback directly within the plugin, there is a link in the <strong>Settings » Sensor</strong> menu item.

= Can the plugin track the pages my visitors are browsing? =
No, and we do not have any intention to add that kind of functionality.
WP Admin Audit focuses on actions done by administrators and actions done by users which could be security relevant (e.g. user account creation, user role changes, ...).

= Is WP Admin Audit translation ready? =
Yes, WP Admin Audit has full translation and localization support via the wp-admin-audit textdomain.
Based on your site language, required .mo and .po translation files will be downloaded and placed into the default WordPress languages directory.

== Screenshots ==

1. The dashboard provides an overview of the overall status (number of events and sensors)
2. All available sensors can be activated/deactivated and categorized for the severity (i.e. how important the events recognized by the sensors are considered to be)
3. The event log is the central hub of WP Admin Audit. It is essentially an audit trail of all the activities of your admin(s) and other security relevant events.
4. Each event is stored with a series of data that allows you to understand who did what and which time
5. Example event: a user account was edited where the password was changed and the role was promoted from subscriber to editor
6. Example event: password reset
7. Example event: a page was published
8. Example event: a plugin was activated
9. Find the important sensors with the built-in search
10. Search through the event log with the powerful built-in search and filter capabilities
11. Filter the event log by the type of event / sensor

== Changelog ==

= 1.2.9 =
*Release Date - February 20th, 2024*

*   Fix issue when handling special posts being saved, causing e.g. incompatibility with CM Tooltip Glossary Ecommerce plugin
*   Fix "undefined array key" warnings
*   Add log preview functionality

= 1.2.8 =
*Release Date - November 14th, 2023*

*   Updated PUC library
*   Avoid PHP warnings
*   Tested with WP v6.4

= 1.2.7 =
*Release Date - October 2nd, 2023*

*   Fix: Better Stack (replication, formerly known as Logtail) API endpoint updated
*   Updated PUC library
*   Tested with WP v6.3

= 1.2.6 =
*Release Date - July 3rd, 2023*

*   UI improvement: in the event details, non-changed attributes can be hidden
*   UI improvement: in the event details, changes can be highlighted (diff of content changes)
*   Fix: showing plugin/theme file edits works

= 1.2.5 =
*Release Date - June 1st, 2023*

*   Add two new sensors: theme file edit / plugin file edit, including showing the textual differences
*   Fix: localize date and date/time in the user interface (format can be controlled via WP Admin Audit settings)
*   Fix: Do not show the "Login Attempts" widget if there is no login data recorded/stored, yet
*   Fix: avoid PHP warnings in PHP 8.1/8.2
*   Add Logsnag as an option for notification target / recipient (premium only)
*   Logtail (replication) now called Better Stack
*   WP compatibility now from version 5.5 onwards

= 1.2.4 =
*Release Date - April 8th, 2023*

*   Add "Login Attempts" widget
*   Fix: avoid database errors on installation
*   Fix: avoid PHP warnings

= 1.2.3 =
*Release Date - April 2nd, 2023*

*   Add support for new extension for the third-party plugin WPForms
*   Compatible with WordPress 6.2
*   Fix: check for available extensions working
*   Fix: avoid PHP warnings

= 1.2.2 =
*Release Date - February 17th, 2023*

*   Four new sensors: Option create / update (core) / update (other) / delete
*   Improvement: login audit view gets filter (to filter to IP addresses that once/never had a successful login attempt), show percentage of successful logins, show percentage of existing usernames used in login attempts
*   Improvement: for failed login attempts that tried to login into an existing username, show the user account in the Event log list view, too
*   Improvement: In diagnosis view add event log stats (e.g. top five event types)
*   Fixing issues when WADA table collation is different from core (user / usermeta) table
*   Fix: password resets (through frontend/user) are recorded
*   Fix: enforce password change only when enabled

= 1.2.1 =
*Release Date - November 2nd, 2022*

*   Fix potential infinite loop (in logging functionality)
*   User interface improvements in settings (quicker load times)
*   Prepare database to support extensions that record events of third-party plugins

= 1.2.0 =
*Release Date - August 26th, 2022*

*   Total of additional 19 new sensors for WP core events (categories, tags, comments, menus)
*   Plugin now keeps track of last user activity, and allows to for example identify inactive admins
*   Plugin now monitors login attempts, and allows to find the source (IP addresses) of automated / brute-force login attempts
*   New admin dashboard widget: "Last Activities" (can be disabled in settings)
*   User interface improvement: add severity filter to events list
*   Bug fix: post meta data changes are recorded as well on post update

= 1.1.2 =
*Release Date - July 3rd, 2022*

*   Fix installation issue

= 1.1.1 =
*Release Date - July 2nd, 2022*

*   Fix issue where new sensors were not installed by default
*   Fix "join beta tester" button
*   UI improvement: add loading indicators for admin tables when sorting/filtering/etc.
*   UI improvement: change pagination "items per page" setting from screen option to dropdown

= 1.1.0 =
*Release Date - June 27th, 2022*

*   New sensors: WP general/writing/reading/discussion/media/permalink/privacy settings
*   New feature: discover & install (missed) sensors via diagnostics view
*   Fix for diagnostics view: download and delete log file buttons now work
*   Fix for statistics on dashboard

= 1.0.3 =
*Release Date - April 20th, 2022*

*   Fix the sorting functionality on the sensor list view
*   Improve rendering for deleted posts
*   Various other small UI improvements

= 1.0.2 =
*Release Date - February 18th, 2022*

*   Fixes issue saving the event log retention period
*   Introducing German translation
*   Adding description (icons) in settings view

= 1.0.1 =
*Release Date - February 1st, 2022*

*   Fixes installation problem

= 1.0.0 =
*Release Date - January 24th, 2022*

*   Initial release


== Upgrade Notice ==
= 1.2.9 =
Fix issues saving certain posts, Fix "undefined array key" warnings, Add log preview

= 1.2.8 =
Avoid PHP warnings, Updated PUC library, Tested with WP v6.4

= 1.2.7 =
Fix API endpoint for BetterStack, Updated PUC library, Tested with WP v6.3

= 1.2.6 =
UI improvements for event details view to highlight changes, showing plugin/theme file edits works


