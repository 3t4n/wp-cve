=== Powie's Uptime Robot Plugin ===
Contributors: PowieT
Donate link: https://powie.de
Tags: Uptime Robot, Server Monitoring, Widget, Shortcode
Requires at least: 4.0
Tested up to: 5.3
Stable tag: 0.9.7
License: GPLv2

Uptime Robot (www.uptimerobot.com) Shortcode and Widget Plugin

== Description ==
A Plugin to Uptime Robot Server Monitoring. Add a status List to a post or page using the shortcode PUM 
or simple add a widget to your sidebar.

If you make a donation to this plugin we will put it directly to the UptimeRobot project.

= Documentation =
Add your API code from uptimerobot.com > My Settings > Main API Key to Settings > Uptime Robot Setup > API Key

If you wish to hide a special monitor from beeing displayed, add it to the list under Settings. For more than one monitor make a comma separated list: mon1,mon2,mon3

**Basic example:**
[pum] inserts the shortcode on a page or post. You can use the attribute monitor to display a singe monitor by friendlyname. 
exampel: [pum monitor="friendlyname"]

= Support =
Support Forum @ [powie.de](https://forum.powie.de)

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place the shortcode on your page or `<?php echo do_shortcode('[pum]'); ?>` in your templates

== Frequently Asked Questions ==

Use our forum at www.powie.de for support

== Changelog ==

= 0.9.7 (05.12.2019) =
* 5.3 checks
* Bugfix for using the monitor attribute with the friendly_name

= 0.9.6 (28.06.2019) =
* 5.2.2 checks
* translation readiness

= 0.9.5 (06.07.2017) =
* API v2

= 0.9.4 (12.06.2016) =
* added option to display a singe monitor

= 0.9.3 (16.05.2016) =
* added class pum to the shortcoded table

= 0.9.2 (28.12.2015) =
* Wordpress 4.4 compat

= 0.9.1 (01.12.2014) =
* comma separated list to hide monitors

= 0.9.0 =
* initial version

== Upgrade Notice ==
na

== Screenshots ==
1. Shortcode output on a page
2. Status Cloud Widget