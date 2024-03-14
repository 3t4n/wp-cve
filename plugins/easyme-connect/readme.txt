=== EasyMe Connect ===
Contributors: easymebiz
Tags: easyme, booking, online courses, events, subscriptions
Requires at least: 7.0
Tested up to: 6.4
Stable tag: 3.0.2
 
Connects your EasyMe account to Wordpress.

== Description ==

Connects your EasyMe account and automatically embeds your custom javascript client code in your Web site.

Grab and insert "Magic" EasyMe links from the links tab of any product and your booking modal will open as a layer on top of your own design.

The plugin will automatically update your embedded code, so once connected, you can forget about it.

== Installation ==

To connect: Just activate the plugin, and you will be taken to your EasyMe account where you can authorize the connection.

To disconnect: Simply de-activate the plugin.

== Frequently Asked Questions ==

= How do I insert sales links from EasyMe in my Wordpress page? =

You pull the links you need from your EasyMe account - go to the links tab of any product.

NOTE: For calendar links, click the "configure calendar" button in the calendar.

= Can I insert sales links from multiple EasyMe accounts in the same Wordpress installation? =

Yes. However, only links from the connected account  will open inline - others will open in a new window.

Questions answered by the sublime support at support@easyme.biz

== Changelog ==

= 3.0.2 =
* Fixed documentation link

= 3.0.1 =
* Bug fixes

= 3.0.0 =
* Updated to OpenID endpoints
* Removed a lot of old conditionals for unsupported features
* Solved conflicts with EasyMe JS client and various theme builders

= 2.2.4 =
* Bug fixes

= 2.2.3 =
* Bug fixes

= 2.2.2 =
* A few new settings

= 2.2.1 =
* Bugfixes for accounts not yet on OTP

= 2.2.0 =
* WordPress PRO can now restrict access based on client tags (requires OTP login with EasyMe)

= 2.1.1 =
* Bugfixes for 2.1.x

= 2.1.0 =
* Support for new OTP login mechanism 
* Comments no longer shown on restricted pages if not logged in

= 2.0.4 =
* Bug fixes

= 2.0.3 =
* Implemented lazy initialization of sessions to avoid using them whenever possible.
* Fixed race condition with token renewal.

= 2.0.2 =
* Minor bug fixes to the new 2x version

= 2.0.1 =
* Minor bug fixes to the new 2x version

= 2.0.0 =
* New access control mechanism. Restrict access to WP pages based on subscriptions and online courses in EasyMe
* Control the primary color of the EasyMe sales widget directly in WordPress

= 1.0.4 =
* Quickfix for bug in 1.0.3

= 1.0.3 =
* Fixed path issues with return_uri for wp.com hosted sites
* Normalized protocols in return_uris

= 1.0.2 =
* More error handling on network problems
* Admin notice if plugin has not been connected to EasyMe
* Admin notice if PHP is not at least version 5.4.0

= 1.0.1 =
* Fixed bug when running on non-tls installations on Apache pre-2.4.

= 1.0.0 =
* First version

== Upgrade Notice ==

= 2.0.3 =
Get rid of those annoying prompts to reconnect the plugin.
