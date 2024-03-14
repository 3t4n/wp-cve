=== Plugin Name ===
Contributors: cheesefather
Donate link: https://www.paypal.com/xclick/business=support@pelinor.com&item_name=BAB Donation&currency_code=GBP
Tags: admin, attack, blocker, botnet, brute-force, ddos, distributed, global, ip, lockout, lockdown, login, plugin, security, wp-admin, admin, multisite
Requires at least: 3.0.0
Tested up to: 4.7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin blocks distributed botnet brute-force attacks on your Wordpress installation.

== Description ==

After the recent global distributed botnet attack on WordPress installations that took down servers and broke into admin accounts, I thought I'd write a plugin to prevent it happening again.

Distributed botnet attacks can come from multiple IP addresses and locations at the same time, so conventional IP-based lockouts are not effective (e.g. those found in Wordfence and other WordPress security plugins).

For example, if 1,000 different computers (with unique IP addresses) are trying to brute-force your admin password and you lock out each IP address after 5 incorrect attempts then you have still allowed 5,000 attempts. My plugin essentially ignores the different IP addresses and locks out all admin login attempts in a configurable way - so if you have it set to 5 failed attempts (default) then those 1,000 different computers will only have a total between them of 5 attempts.

You can select how many login failures causes the lockout, how much time to allow between failures, how long to block logins for and also you can input a whitelisted IP address (or multiple addresses separated with commas or spaces) which can bypass the lockdown and always log in - so you can still always get into your site even in the middle of an attack. There is also support for partial IP address matching for those with dynamic IP addresses. You can also define a secret key to bypass the lock.

* Any failed login is counted regardless of username or IP address (unless whitelisted)
* Once locked down, nobody can log in except from whitelisted IP addresses or using the secret key
* You can specify the number of login failures that triggers a lockdown
* You can specify the time between failed attempts that should be counted
* You can specify how long the lockdown should last
* You can add a secret key that bypasses the lockdown
* You can customise the lockout message
* You can add whitelisted IP addresses that bypass the lockdown
* Partial IP address matching for dynamically-allocated IP addresses
* Multisite compatible
* Available in English, French, German, Italian and Russian

== Installation ==

1. Upload `botnet-attack-blocker.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Settings, Botnet Blocker to configure your settings

== Frequently Asked Questions ==

= How do I configure the plugin? =

Navigate to Settings and then Botnet Blocker, change the options and click Update.

= Can I whitelist multiple IP addresses? =

Yes, separate them by a space or comma.

= Can I whitelist partial IP addresses? =

Yes, just type in the IP part to match, e.g. 1.2 or 1.2.3 and leave out the part to ignore. This will allow dynamically-allocated IP addresses in the whitelist.

== Screenshots ==

1. Simple admin screen to change plugin options.

== Changelog ==

= 2.0.0 =
* Add secret key option
* Add customised message

= 1.9.1 =
* Bugfix for whitelist errors

= 1.9 =
* Improve table deletion on deactivation

= 1.8 =
* Multisite compatible for individual or network activation
* Add 24 hour blocking option

= 1.7 =
* Remove options and plugin table on deactivation
* Change init hooks to run less often

= 1.6 =
* Bugfix - fix invalid header on activation.

= 1.5 =
* Bugfix - unquoted text amended thanks to John Dorner.

= 1.4 =
* Bugfix - added check for blank whitelist.

= 1.3 =
* Added French, German and Russian translations (my own, feel free to suggest corrections).

= 1.2 =
* Added Italian translation thanks to Giacinto (www.iononmollo.it).

= 1.1 =
* Added translation support.
* Added partial IP whitelist matching (for dynamic IPs).

= 1.0 =
* Initial release.
