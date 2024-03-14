=== Limit Login Attempts Plus - WordPress Limit Login Attempts By Felix ===
Tags: login, security, authentication, Limit Login Attempts, Limit Login Attempts Reloaded, Limit Login Attempts Revamped, Limit Login Attempts Renovated, Limit Login Attempts Updated, Better Limit Login Attempts, Limit Login Attempts Renewed, Limit Login Attempts Upgraded
Requires at least: 2.8
Tested up to: 6.1.1
Stable tag: 1.1.0
License: GPLv2 or later

WordPress Limit Login Attempts Plus plugin for Login Protection by Felix Moira

== Description ==

Limit the number of login attempts that possible both through the normal login as well as using the auth cookies.
WordPress by default allows unlimited login attempts either through the login page or by sending special cookies. This allows passwords (or hashes) to be cracked via brute-force relatively easily.
Limit Login Attempts Reloaded blocks an Internet address from making further attempts after a specified limit on retries has been reached, making a brute-force attack difficult or impossible.

Features:

* Limit the number of retry attempts when logging in (per each IP). This is fully customizable.
* Limit the number of attempts to log in using authorization cookies in the same way.
* Informs the user about the remaining retries or lockout time on the login page.
* Optional logging and optional email notification.
* Handles server behind the reverse proxy.
* It is possible to whitelist IPs using a filter. But you probably shouldn't do this.

All your settings will be kept in tact!

Many languages are currently supported in Limit Login Attempts Reloaded plugin but we welcome any additional ones.
Help us bring Limit Login Attempts Reloaded to even more cultures.

Translations: Bulgarian, Brazilian Portuguese, Catalan, Chinese (Traditional), Czech, Dutch, Finnish, French, German, Hungarian, Norwegian, Persian, Romanian, Russian, Spanish, Swedish, Turkish

Plugin uses standard actions and filters only.

== Screenshots ==


== Changelog ==

= 1.1.0 =
* Settings are moved to a separate page.
* Fixed: login error message.
* A security issue inherited from the ancestor plugin Limit Login Attempts has been fixed.

= 1.0.9 =
* GDPR compliance implemented.
* Fixed: ip_in_range() loop $ip overrides itself causing invalid results.
* Fixed: the plugin was locking out the same IP address multiple times, each with a different port.

= 1.0.8 =
* Added support of Sucuri Website Firewall.

= 1.0.7 =
* Fixed the issue with backslashes in usernames.

= 1.0.6 =
* Plugin returns the 403 Forbidden header after the limit of login attempts via XMLRPC is reached.
* Added support of IP ranges in white/black lists.
* Lockouts now can be released selectively.
* Fixed the issue with encoding of special symbols in email notifications.

= 1.0.5 =
* Added Multi-site Compatibility and additional MU settings.

= 1.0.4 =
* Usernames and IP addresses can be white-listed and black-listed now.
* The lockouts log has been inversed.

= 1.0.3 =
* IP addresses can be white-listed now.
* A "Gateway" column is added to the lockouts log. It shows what endpoint an attacker was blocked from. 
* The "Undefined index: client_type" error is fixed. 

= 1.0.2 =

= 1.0.1 =
* The site connection settings are now applied automatically and therefore have been removed from the admin interface.
* Now compatible with PHP 5.2 to support some older WP installations.

= 1.0.0 =
* Plugin release