=== Booter - Bots & Crawlers Manager ===
Contributors: upress, ilanf, haimondo
Tags: upress,hosting,security,rate limit,request
Requires at least: 4.0
Tested up to: 6.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Booter - Bots & Crawlers Manager is a preventative measure (treatment in advance) and treatment of damages caused by crawlers and bots.
The plugin uses a number of existing technologies which are known by crawlers and bots and takes them one step forward - smartly and almost completely automatically.

== Description ==
Booter - Bots & Crawlers Manager is a preventative measure (treatment in advance) and treatment of damages caused by crawlers and bots.
The plugin uses a number of existing technologies which are known by crawlers and bots and takes them one step forward - smartly and almost completely automatically.
To allow the plugin to function correctly, you must follow the instructions and manually enter some data (which must be done by a human being to avoid errors).

= At the prevention level =
- Booter allows you to manage and create an advanced dynamic robots.txt file.
- View a 404 error log to see the most common bad links.
- Blocking bad bots that cause high server loads due to very frequent page crawls, or are used to search for security vulnerabilities.

= At the treatment level =
- Booter allows you to limit the amount of requests from crawlers and bots, if or when they exceed the specified amount of requests per minute, it will be rejected for a specified period of time.
- Rejecting links that we do not want in the fastest way, not by just blocking but by sending the appropriate HTTP status code to make search engines forget them.

= Instructions for use in case of damage treatment =
1. Activate the plugin.
1. Enable the 404 error log option.
1. Set the access rate limit.
1. Watch the 404 log, try to find common parts in the URLs that repeats most often.
1. Enter the common parts to the "reject links" page, and ensure the rejection code is 410.
1. Clear the 404 error log.
1. Repeat the process once every few hours until the 404 error log remains blank.
1. Check the status of your website's index coverage every few days.

== Installation ==
1. Upload `booter-crawlers-manager` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. The plugin will start rate limiting as soon as it is activated, however it is recommended to update the settings to suit your needs, under 'Settings' -> 'Booter - Crawlers Manager' menu

== Screenshots ==
1. Plugin General Settings
2. Robots.txt Management
3. Reject Links Settings

== Changelog ==
= 1.5.6 =
- Move additiona bots list to a remote list

= 1.5.5 =
- Fix rare crash of the UI

= 1.5.4 =
- Fix rate limited not properly detecting excluded useragents

= 1.5.3 =
- Fix scheduled task not setting properly

= 1.5.2 =
- Fix bots list not updating

= 1.5.1 =
- Fix regression introduced in version 1.5

= 1.5 =
- Added options for weekly and monthly 404 log report
- Added option to exclude user agents from rate limiting
- Updated UI components
- Updated bad bots list
- Server IP will be excluded from rate limiting by default
