=== Mythic Cerberus ===
Developer: Mythic Beasts
Contributors: mythic_beasts, mvandemar
Tags: security, login, login form, protect login, login control, login blocking, lockdown, ban ip
Requires at least: 4.0
Tested up to: 6.4
Stable Tag: 1.1.2
Requires PHP: 5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Guard your login form by limiting login failures from the same IP.

== Description ==

Mythic Cerberus records the IP address and timestamp of every failed login attempt. If more than a certain number of attempts are detected within a short period of time from the same IP range, then the login function is disabled for all requests from that IP address. This helps to prevent brute force password discovery and attacks.

The plugin defaults to blocking an incorrect username immediately, and a 10 minuite lock out of an IP block after 5 failed login attempts within 5 minutes. This can be modified in options, and administrators can release locked out IP ranges manually from the dashboard.

Mythic Cerberus is a fork of "[Login Lockdown](https://wordpress.org/plugins/login-lockdown/)", and preserves the traditional interface and functionality of that plugin.

Plugin setting can be configured via Settings - Mythic Cerberus in the dashboard.

== Installation ==

1. Extract the zip file into your plugins directory into its own folder.
2. Activate the plugin in the Plugin options.
3. Customize the settings from the dashboard under Settings > 'Mythic Cerberus'.

== Change Log ==

= 1.1.2 =
* Small updates to readme.txt

= 1.1.0 =
* Clarified option page
* Don't immediately block invalid usernames by default
* Disable XML-RPC by default.

= 1.0.1 =
* Forked from Login Lockdown v1.83
* Replaces some terminology with clearer wording.
* Adjusted default options to better match typical usage.
* Removed legacy code.
* Index tables for faster queries and clean up database when deactivating.

= Login-Lockdown Changelog =

= v1.83 =
* 2022/10/04
* fixed timezone bug

= v1.82 =
* 2022/09/23
* WebFactory took over development
* a full rewrite will follow soon, for now we patched some urgent things
* prefixed function names that are in global namespace
* properly escaped all inputs

= Older changelog =
 ver. 1.8.1 30-Sep-2019

 - adding missing ./languages folder

 ver. 1.8 30-Sep-2019

 - fixed issues with internationalization, added .pot file
 - changed the credit link to default to not showing

 ver. 1.7.1 13-Sep-2016

 - fixed bug causing all ipv6 addresses to get locked out if 1 was
 - added in WordPress MultiSite functionality
 - fixed bug where subnets could be overly matched, causing more IPs to be blocked than intended
 - moved the report for locked out IP addresses to its own tab

 ver. 1.6.1 8-Mar-2014

 - fixed html glitch preventing options from being saved

 ver. 1.6 7-Mar-2014

 - cleaned up deprecated functions
 - fixed bug with invalid property on a non-object when locking out invalid usernames
 - fixed utilization of $wpdb->prepare
 - added more descriptive help text to each of the options
 - added the ability to remove the "Login form protected by Login LockDown." message from within the dashboard

 ver. 1.5 17-Sep-2009

 - implemented wp_nonce security in the options and lockdown release forms in the admin screen
 - fixed a security hole with an improperly escaped SQL query
 - encoded certain outputs in the admin panel using esc_attr() to prevent XSS attacks
 - fixed an issue with the 'Lockout Invalid Usernames' option not functioning as intended

 ver. 1.4 29-Aug-2009

 - removed erroneous error affecting WP 2.8+
 - fixed activation error caused by customizing the location of the wp-content folder
 - added in the option to mask which specific login error (invalid username or invalid password) was generated
 - added in the option to lock out failed login attempts even if the username doesn't exist

 ver. 1.3 23-Feb-2009
 - adjusted positioning of plugin byline
 - allowed for dynamic location of plugin files

 ver. 1.2 15-Jun-2008

 - now compatible with WordPress 2.5 and up only

 ver. 1.1 01-Sep-2007

 - revised time query to MySQL 4.0 compatibility

 ver. 1.0 29-Aug-2007

 - released
