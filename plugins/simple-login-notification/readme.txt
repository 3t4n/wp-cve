=== Simple Login Notification ===

Plugin Name: Simple Login Notification
Plugin URI: https://perishablepress.com/simple-login-notification/
Description: Sends an email when any admin-level user logs in to your site.
Tags: email notification, admin login notification, email notify on admin login, login notification
Author: Jeff Starr
Author URI: https://plugin-planet.com/
Donate link: https://monzillamedia.com/donate.html
Contributors: specialk
Requires at least: 5.3
Tested up to: 6.5
Stable tag: 1.7
Version:    1.7
Requires PHP: 5.6.20
Text Domain: simple-login-notification
Domain Path: /languages
License: GPL v2 or later

Sends an email when any admin-level user logs in to your site.



== Description ==

Activate this plugin to send an email when any admin-level user logs in to your site. This is useful for keeping an eye on any unauthorized administrator logins. Each email alert includes the user name, IP address, user agent, and other details. Just in case you need to do some forensic investigation.


**Features**

* Send email to any user role or email address
* Exclude email notifications for any IP address
* Provides detailed information about each admin login
* Provides option to exclude/whitelist any IP addresses
* Lightweight and fast - total plugin size around 70 KB
* Simple to use - activate and done, just works
* No permanent changes are made to anything
* No settings or anything to worry about
* Easy peasy mac and cheesy


**Why is this useful?**

I use this plugin to keep an eye on any unauthorized login attempts. Probably a bit paranoid but I don't care, paranoid works well for me.


**How is this plugin different?**

While researching for this plugin, I found four other "admin login notification" type plugins:

* [Email Notification on Login](https://wordpress.org/plugins/email-notification-on-login/)
* [Email notification on admin login](email-notification-on-admin-login)
* [Kaya Login Notification](https://wordpress.org/plugins/kaya-login-notification/)
* [KolorWeb Access Admin Notification](https://wordpress.org/plugins/kolorweb-access-admin-notification/)

Unfortunately none of these plugins suited my specific needs:

* Lightweight, clean and simple
* Current with latest WordPress
* No requirement for PHP sessions

So I decided to build my own. Let me emphasize the utter simplicity of this plugin. It does one thing and does it well: sends an email whenever an admin-level user logs in to WordPress. No bells and whistles, no bloat. If you need more functionality, check out the above plugins should get you there.


**Privacy**

This plugin does not collect or store any user data. It does not set any cookies, and it does not connect to any third-party locations. The *only* thing this plugin does is send an email for each admin-level login. Each email includes information about the user, such as username, IP address, user agent, and other details.

Simple Login Notification is developed and maintained by [Jeff Starr](https://twitter.com/perishable), 15-year [WordPress developer](https://plugin-planet.com/) and [book author](https://books.perishablepress.com/).



== Installation ==

**Installing the plugin**

Activate like any other plugin and done. There are no settings, works automatically.

More info on [installing WP plugins](https://wordpress.org/support/article/managing-plugins/#installing-plugins).


**Using the plugin**

Activate and done. The plugin requires nothing else to work its magic.


**Exclude IP address**

If you want to exclude an IP address from email alerts, visit the plugin settings &gt; "Exclude IPs". So if you don't want to get an email every time you log in, add your IP address to this setting. You can use a free online tool to [get your current IP address](https://perishablepress.com/tools/ip/). If you are unsure, leave this setting blank.

To add an IP address to the "Exclude IPs" setting, you can use any of the following notations:

* Individual IP address, like `93.184.216.34`
* Sequential range of IP addresses, like `93.184.`
* CIDR range of IP addresses, like `93.184.216.34/24`

__Important:__ Separate multiple IP/strings with commas.


**Uninstalling**

To uninstall/remove the plugin, visit the Plugins screen, deactivate and delete the plugin. This plugin makes no changes to the WP database.



== Upgrade Notice ==

To upgrade this plugin, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.



== Frequently Asked Questions ==

**Got a question?**

Send any questions or feedback via my [contact form](https://plugin-planet.com/support/#contact)



== Changelog ==

__Thank you__ for using Simple Login Notification! If you like the plugin, please show support with a [5-star rating &raquo;](https://wordpress.org/support/plugin/simple-login-notification/reviews/?rate=5#new-post)


**1.7 (2024/03/06)**

* Updates plugin settings page
* Updates default translation template
* Improves plugin docs/readme.txt
* Tests on WordPress 6.5 (beta)


Full changelog @ [https://plugin-planet.com/wp/changelog/simple-login-notification.txt](https://plugin-planet.com/wp/changelog/simple-login-notification.txt)
