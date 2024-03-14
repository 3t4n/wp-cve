=== IP Based Login ===
Contributors: brijeshk89
Tags: auto, login, ip, based, authentication, ezproxy, access, credentials, easy access, central, management, ipv6, ipv4, ip based login, ip based authentication, auth
Requires at least: 3.0
Tested up to: 6.4
Stable tag: 2.3.10
Requires PHP: 5.6
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

IP Based Login allows you to directly login from an authorized IP without password. 

== Description ==

IP Based Login allows you to directly login from an authorized IP without password. So if you want to allow someone to login but you do not want to share the login details just add their IP / IP Range using IP Based Login and when they access your site they will be logged in without having to enter the login credentials.

Features in IP Based Login include:

[PRO Features]
- IPv6 Support
- EZProxy Support
- Analytics - Check sessions usage and find which university/institution is actively accessing your content
- Central Management for IP ranges - Add your IP ranges on our central server and get the IP ranges synced across all your websites automatically. 

[Free Features]
- Create IP ranges
- IPv4 Support
- Cloudflare support
- Choose the username accessible when accessed by the IP existing in provided range
- Bulk Export/Import IP ranges
- Delete IP ranges
- Enable/Disable IP ranges
- Terminate Session if IP changed
- Licensed under GNU GPL version 3
- Does not affect when accessed from any other IPs not existing in any ranges
- Safe & Secure
- No passwords saved

[For Publishers]
Just add University IP Address to the plugin and when the students access your website from the University campus or EZ Proxy server they will be automatically authenticated to your WordPress website with the subscriber account you choose while adding the IP address. 

[Developers Section]
- **is_logged_in_using_ipbl()** function to determine if a user is logged in with IP Based login plugin or with username/password
- Add additional layer of check before the user is auto logged using the **ipbl_can_auto_login** hook
- Execute custom PHP code after the user is auto logged in using the **ipbl_auto_logged_in** hook

== Frequently Asked Questions ==

= How do I configure EZProxy? =

For EZProxy please add /?login to your site URL in the EZProxy config file (Config.txt)
E.g. If your website URL is domain.com please add the URL in EZProxy (Config.txt) as 
URL https://domain.com/?login

Then Login to WordPress admin panel and go to IP Based Login page from left menu and select the IP Detection Preference as **(EZProxy) PROXY_REMOTE_ADDR**

That's it! Try to login from your EZProxy server now. 

= How do I temporarily disable auto login and login as another user? =

If your IP is in the allowed list you will be logged into the user assigned for that IP in the settings, however if you want to disable auto login for some time and login as another user please hover on the "Logged in by IP Based Login" link in the admin bar in WordPress admin panel and you will find the option to disable auto login for 1, 15, 30 or 60 minutes. 

If you are auto logged as a non admin user and do not have access to admin panel you can create a custom floating logout button using the following post :
https://wordpress.org/support/topic/reopen-ip-login-as-option/#post-16772750


= How do I determine (using PHP) if the current user is logged in using IP Based Login? =

Use the function **is_logged_in_using_ipbl()** which allows you to determine if a user is logged in with Auto Login via IP Based login plugin or with manual login using username/password. 
This function returns true if the user is logged in with Auto Login via IP Based Login plugin else false. 
This function can be called from any other theme/plugin or any PHP file.

= How do I add additional layer of check before the user is auto logged in? =

The Hook point "ipbl_can_auto_login" is called just before logging in the user via IP Based Login plugin. Using this hook you can decide to proceed with auto login or not. Return false in this hook to disallow auto login, return true to allow auto login.
Note : This hook is executed after the IP check with the allowed IPs in database so if the accessing IP is not in the allowed list this hook will not be called.

= How do I execute custom PHP code after the user is auto logged in? =

The Hook point "ipbl_auto_logged_in" is called immediately after the user is auto logged in via IP Based Login plugin. You can use this hook to execute any custom PHP code after the user is auto logged in.

== Installation ==

Upload the IP Based Login plugin to your blog, Activate it.
That's it. You're done!

== Changelog ==

= 2.3.10 22nd November 2023 =
* [Improvement] Compatible with WordPress 6.4

= 2.3.9 3rd November 2023 =
* [Bug Fix] Fixed Admin menu for custom capability `manage_ip_ranges`
* [Bug Fix] Fixed PHP warnings

= 2.3.8 26th October 2023 =
* [Improvement] Admins can now allow other user roles to manage IP Ranges by allowing `manage_ip_ranges` capability.

= 2.3.7 1st September 2023 =
* [Pro Feature] EZProxy Support added.

= 2.3.6 5th June 2023 =
* [Pro Feature] Released Analytics plugin for detailed analytics of auto logins.

= 2.3.5 22nd April 2023 =
* [Bug Fix] In some cases when using wp-security-audit-log Auto Login failed. This is fixed.

= 2.3.4 18th April 2023 =
* [Bug Fix] In some cases when using wp-security-audit-log Auto Login failed. This is fixed.

= 2.3.3 7th April 2023 =
* [Bug Fix] While Exporting IP ranges in the Free version there were some columns included which are not allowed to be imported in the Free version. This is fixed.

= 2.3.2 11th February 2023 =
* [Bug Fix] Temporary Disable Auto Login option did not work when the logged in username contained a dot (.) This is fixed.

= 2.3.1 4th February 2023 =
* [Bug Fix] In rare cases when the IP Based Login version entry was deleted manually from options table it would break the structure of IP Based Login table. This is fixed.

= 2.3.0 2nd November 2022 =
* [Feature] Added a hook point "ipbl_auto_logged_in" which is called after logging in the user. Using this hook the admin can perform actions after a user is logged in.

= 2.2.9 24th October 2022 =
* [Feature] Added a hook point "ipbl_can_auto_login" which is called just before logging in the user. Using this hook the admin can decide to proceed with auto login or not. Return false to disallow auto login, return true to allow auto login. Note : This hook is executed after the IP check with the allowed IPs in database so if the accessing IP is not in the allowed list this hook will not be called.

= 2.2.8 27th September 2022 =
* [Bug Fix] Logging IP Based Auto Logins with (WP Activity Log) failed in some cases. This is fixed.

= 2.2.7 27th August 2022 =
* [Task] IP Based Auto Logins will now be logged into (WP Activity Log) plugin

= 2.2.6 4th May 2022 =
* [Bug Fix] Fixed Login Usage values while exporting the IP ranges to CSV

= 2.2.5 1st May 2022 =
* [Improvement] [Pro] Tracking IP usage will now work for ranges that have Unlimited usage as well
* [Bug Fix] Fixed some PHP warnings

= 2.2.4 27th February 2022 =
* [Improvement] [Pro] Settings such as IP detection preference, sync frequency, terminate session on IP change and hide IP ranges can now be synced from central server across all sites to save time in updating settings on each site
* [Bug Fix] "is_logged_in_using_ipbl" function was not returining that the session was created by IP Based Login plugin on initial page load. This is fixed now.

= 2.2.3 30th January 2022 =
* [Feature] [Pro] Central server can now add multiple IP ranges with same IP with unique usernames. IP Based Login will take the 1st username that exists on the site and login the visitor into that user.
* [Feature] [Pro] Central server can now add Include and Exclude sites to decide if the site should be allowed to sync the IP range or not.
* [Bug Fix] If an IP was present in IP Based Login and the username assigned to it did not exist on the site, when a visitor with that IP tried to login with their username/pass was leading to a redirect loop causing the login to fail.

= 2.2.2 24th January 2022 =
* [Bug Fix] Due to changes in v2.2.1 IP detection failed on some servers. This is fixed now. 

= 2.2.1 23rd January 2022 =
* [Bug Fix] Fixed detection of IP when using Cloudflare.

= 2.2.0 10th January 2022 =
* [Feature] [Pro] Added support to sync IP ranges from a central server across all your sites automatically.

= 2.1.0 =
* [Feature] [Pro] Added Redirect link to the IP range, to redirect users to a certain page after login.
* [Feature] [Pro] Added Usage limit to alllow users to login from the allowed IPs for certain times.

= 2.0.7 =
* [Bug Fix] Changes to support translations for some left out texts

= 2.0.6 =
* [Feature] Added option to sort the IP ranges by date/username.

= 2.0.5 =
* [Feature] Added a setting to destroy session created by IP Based Login if the client's IP changes.

= 2.0.4 =
* [Improvement] Changes to improve efficiency on sites having high traffic and high number of IP ranges added in IP Based Login.

= 2.0.3 =
* [Improvement] After using the "Disable auto login" option user will be re-directed to login page. Previously user was redirected to home page.

= 2.0.2 =
* [Bug Fix] The database structure was not created correctly during activation of the plugin. This was due to a bug introduced in v2.0

= 2.0.1 =
* [Bug Fix] Removed escapeshellcmd function usage as it could be disabled on some servers. Instead we are using esc_sql which is safe.

= 2.0 =
* [PRO Feature] Added support for IPv6.

= 1.5.2 =
* Trimed whitespaces while importing the CSV file.
* Added error reporting for the rows not imported while importing the CSV file.

= 1.5.1 =
* Created the WordPress Test Cookie because some plugins need it. This cookie is generally created on login page but since we are auto login this cookie was not created hence we need to create it.

= 1.5.0 =
* Added "Delete All IP Ranges" to delete all IP ranges at once
* Added support for redirect_to parameter to redirect to a given URL after login

= 1.4.9 =
* Improved users list dropdown to load even with over 10k users

= 1.4.8 =
* List all users in dropdown for users list
* Minor UI improvements

= 1.4.7 =
* Fixed a scenario leading to error about MySQL query syntax in the web server logs
* Added admin login check while exporting the IP Ranges to CSV file

= 1.4.6 =
* Added setting to choose the method to detect user's IP e.g. REMOTE_ADDR, HTTP_X_FORWARDED_FOR or HTTP_CLIENT_IP

= 1.4.5 =
* Fix for detecting IP when the client is behind proxy

= 1.4.4 =
* Removed usage of Deprecated function get_userdatabylogin(), replaced it with get_user_by()

= 1.4.3 =
* Added support for Translations

= 1.4.2 =
* Added option to Bulk Export/Import IP ranges

= 1.4.1 =
* Display the "Logged in by IP Based Login" only if the user is actually logged by our plugin.

= 1.4.0 =
* Added function "is_logged_in_using_ipbl()" which allows admin to determine if a user is logged in with IP Based login plugin or with username/password. This function can be called from any other theme/plugin or any PHP file.

= 1.3.9 =
* Fixed compatibility issue with PHP 7

= 1.3.8 =
* Now compatible with WordPress 4.2.2
* Added Settings link on Plugins page itself

= 1.3.7 =
* Compatible with WordPress 4.0
* Fixed the issue that caused error while adding IP range when there was whitespace in Start IP or End IP

= 1.3.6 =
* Added the users list dropdown toggle so that the plugin does not break when the site has huge list of users

= 1.3.5 =
* Minor User Interface improvements
* Added compatibility with "ARYO Activity Log" plugin

= 1.3.4 =
* User can disable auto login temporarily for 15, 30 or 60 minutes

= 1.3.3 =
* Added a note in header stating the user is logged in by IP Based Login
* Added Error Handling for exising IP ranges
* Added IP Based Login details in footer

= 1.3.1 =
* Added the list of users in a dropdown while adding an IP range
* Fixed a typo

= 1.3 =
* Added Option to Enable/Disble IP Ranges
* Changed Start and End IP columns in database to BIGINT

= 1.2 =
* Compatible with WordPress 3.8

= 1.1 =
* IP Based Login will now drop the database if the plugin is uninstalled and not when the plugin is deactivated

= 1.0 =

* Create IP ranges
* Choose the username accessible when accessed by the IP existing in provided range
* Delete IP ranges
* Licensed under GNU GPL version 3
* Does not affect when accessed from any other IPs not existing in any ranges
* Safe & Secure
* No passwords saved
