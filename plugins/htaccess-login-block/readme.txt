=== Plugin Name ===
Contributors: anton.aleksandrov
Donate link: http://wp-htaccess.hosting.guru/
Tags: deny, htaccess, block, login, firewall, security, protect, xmlrpc, auth, protect, apache, json
Requires at least: 4.0.0
Tested up to: 6.4
Stable tag: 5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple plugin to block login hijackers using apache and .htaccess. Simple and resource efficient way to protect your blog. Works with Apache from 1.3 to 2.4. 

== Description ==

Every failed login is counted and if configurable threshold is hit, plugin will block access to wp-login.php in .htaccess, so 
that hijackers won't be able to try anymore and at the same time - won't use server resources. 

If the same IP keeps trying after first block has expired, it is possible to block it from accessing website completely.

Blocking visitor in .htaccess is the most simple and most resource friendly way of stopping webpage abuse and wasting server resources.

Since v0.6 plugin should also log and stop XML-RPC abusers.

== Installation ==


1. Install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress, review settings.



== Changelog ==


= 0.99 =
* Added option to hide registered user exposure through JSON requests.

= 0.98 =
* Making plugin ready for Wordpress 5.6 and PHP8.0

= 0.97 =
* Fixing small bug, when IP address can not be detected.

= 0.96 =
* Tiny warning fix.

= 0.95 =
* Using correct way for modifying .htaccess using WP functions

= 0.94 =
* Minor fix for new line and .htaccess parsing

= 0.93 =
* Minor fix for valid-IP detection function.

= 0.92 =
* Small additional fixed for the bad IP entries.

= 0.91 =
* Fixed bug with unknown or invalid IP entries, that would break .htaccess

= 0.9 =
* Fixed bug with non-delisting IPs. 
* Added code to cleanup old log entries, to reduce useless database usage.

= 0.8 =
* Tiny bug fixing

= 0.7 =
* Bug fix for installations with big many blocked IPs

= 0.6 =
* Added support for XML-RPC login and abuse monitoring.

= 0.5 =
* Minor PHP strict standard fix.

= 0.3 =
* Minor visual changes.

= 0.2 =
* Minor visual changes.

= 0.1 =
* First version for testing on private sites.

