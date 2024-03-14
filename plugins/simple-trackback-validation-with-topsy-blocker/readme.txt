=== Simple Trackback Validation with Topsy Blocker ===
Contributors: Tobiask
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3736248
Tags: trackback, validation, spam, anti-spam, protection, topsy, blocker, block
Requires at least: 3.2
Tested up to: 4.8
Stable tag: 1.2.7

REPLACEMENT of the original Simple Trackback Validation Plugin from Miachel. Performs a simple but very effective test on all incoming trackbacks in order to stop trackback spam. Now with topsy.com blocker.

== Changelog ==

= 1.2.7 =
* check for WP 4.8

= 1.2.6 =
* check for WP 4.6

= 1.2.5 =
* check for WP 4.5

= 1.2.4 =
* check for WP 4.1

= 1.2.3 =
* check for WP 4.0
* added icon - Icon made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a>

= 1.2.2 =
* fixed deprecated code

= 1.2.1 =
* fixed upgrading error

= 1.2.0 =
* tested with WP 3.9

= 1.1.8 =
* Fixed deprecated code

= 1.1.7 =
* Tested with 3.7.1

= 1.1.6 =
* Tested with 3.6

= 1.1.5 =
* Tested with 3.4.1

= 1.1.4 =
* Removed deprecated class

= 1.1.3 =
* Fixed minor bug, thanks to Birt

= 1.1.2 =
* IP Check works now with Proxy

= 1.1.1 =
* Minor bugfixes

= 1.1 =
* Minor bugfixes

= 1.0 =
* Some bugfixes

= 0.7 =
* Checked 3.0.2 compatibility

= 0.6 =
* Checked 3.0 compatibility

= 0.5 =
* Fixed minor bug

= 0.4 =
* Added installation guide


== Description ==

Simple Trackback Validation Plugin performs a simple but very effective test on all incoming trackbacks in order to stop trackback spam. Now with topsy.com blocker.

**How it works:**

When a trackback is received, this plugin

1. checks for topsy.com trackbacks and marks this as spam.

2. checks if the IP address of the trackback sender is equal to the IP address of the webserver the trackback URL is referring to. This reveals almost every spam trackback (more than 99%) since spammers do usually use bots which are not running on the machine of their customers.

3. retrieves the web page located at the URL included in the trackback. If the page doesn’t a link to your blog, the trackback is considered to be spam. Since most trackback spammers do not set up custom web pages linking to the blogs they attack, this simple test will quickly reveal illegitimate trackbacks. Also, bloggers can be stopped abusing trackback by sending trackbacks with their blog software or webservices without having a link to the post.

There are several options available and you also can enable logging to get all actions, which are performed by the plugin, logged. Also, you can select how to treat spam trackbacks (do not save in the database or mark as spam or place into moderation) and several other stuff.

Please visit [the official website](http://sjmp.de/blogging/simple-trackback-validation-with-topsy-blocker/ "Simple Trackback Validation with Topsy Blocker") for further details and the latest information on this plugin.


== Installation ==

1. Deactivate the original plugin "Simple Trackback Validation" if installed previously (this new plugin is a REPLACEMENT of the original one!)
2. Upload this new plugin
3. Activate this plugin
4. Configure it via admin interface
5. Ready to go!


== Frequently Asked Questions ==

= Where can I get more information? =

Please visit [the official website](http://sjmp.de/blogging/simple-trackback-validation-with-topsy-blocker/ "Simple Trackback Validation with Topsy Blocker") for the latest information on this plugin.