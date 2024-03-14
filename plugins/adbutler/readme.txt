=== AdButler WordPress Plugin ===
Contributors: sparklit
Donate link: 
Tags: Ad serving, AdButler, Ad Server,Ad Management,Ad Rotation
Requires at least: 3.3
Tested up to: 6.2.2
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Simplify the deployment of your AdButler Ads with this highly efficient widget based ad deployment plugin

== Description ==

= AdButler Wordpress Plugin =

The AdButler plugin will generate ad tags enabling you to configure and manage your AdButler ad zones directly from inside your WordPress administration widget area. 

_This plugin requires an AdButler account_ to utilize the plugin functionality. Using the AdButler system you can create publisher zones, advertisers and associated banners to help monetize your site and effortlessly manage your ad distribution and configuration. 

Currently this plugin only allows the configuration of widgets within WordPress. Leaderboard formats and other dimensions outside the standard widget size are not currently supported. Ideal banner sizes are square or rectangle, but if your theme supports it the tags will as well.


= Special Considerations =

The AdButler Plugin communicates with the AdButler servers under two circumstances.
1. When the AdButler WordPress key is entered, a server to server call is made validating the key and storing basic adserving information for use in generating the tags. 
1. During the creation and use of any widgets. At this point the widget makes a AJAX/jsonp call to the AdButler server requesting the current publishers and zones associated with the current account. 

**NOTE:** In both of these instance the only information sent to the server is the AdButler WordPress key and any information returned in the first request. Additionally, no confidential or personal AdButler account information is exchanged.

== Installation ==

A visual walk through for setting it up is available here
[https://www.adbutler.com/help/article/using-adbutler-wordpress-plugin](https://www.adbutler.com/help/article/using-adbutler-wordpress-plugin "Visual Wordpress Walk Through")


= Install from your WordPress Dashboard =

1. In your Admin, go to menu Plugins > Add New.
2. Search for AdButler.
3. Click on Install Now.
4. Activate the plugin.
5. Click on AdButler > Settings to configure the plugin.

= Download from wordpress.org =

1. Download the plugin (.zip file) on the right column of this page.
2. In your Admin, go to menu Plugins > Add New.
3. Click the "Upload Plugin" button.
4. Upload the .zip file you just downloaded.
5. Activate the plugin.
6. Click on the AdButler > Settings to configure.

== Frequently asked Questions ==

=Where do I get my plugin key?=
Your WordPress plugin key is in the settings panel of your [AdButler account](https://admin.adbutler.com/login.spark "AdButler Login").

=Do I require an AdButler account=
Yes.  You can learn more about how easy it is to use AdButler and sign up for an account on our website: [https://adbutler.com/](https://adbutler.com/)

== Screenshots ==

1. screenshot-1.png
1. screenshot-2.png


== Changelog ==
* 1.29 Employ secure SSL for all requests.
* 1.28 Pass full referrer URL in ad requests for all ad types.
* 1.27 Bug fixes.
* 1.26 Fixed editing widget in customize mode
* 1.25 Fixed header bidding bug. Updated supported WordPress version.
* 1.22 Added Async Beta 1.1 tags. Updated supported WordPress version.
* 1.21 Fixed resources not loading with https on some configurations.
* 1.20 Removed AdButler key config from dashboard. The key must be configured in the admin settings.
* 1.19 Bug fixes.
* 1.18 Bug and compatibility fixes.
* 1.17 Added support for header bidding ads. Header bidding ads can now be added as a widget or shortcode. Interval ads can now be targeted at specific pages.
* 1.16 Fixed a CSS caching issue.
* 1.15 You now have the option of displaying an ad between posts. Configure this in AdButler > Interval Ads.
* 1.14 Some user have many instances of the widget on their site and with them all named "AdButler Widget" they are really hard to tell apart, but no longer!  Now you can set a title for each widget, say "Cute Puppy Ad" which will be appended to the title so "AdButler Widget" becomes "AdButler Widget: Cute Puppy Ad" or whatever you happen to name it.  So go give your widgets a title!
* 1.13 Update tags, branding, and instructions.
* 1.12 Compatibility with plugins enhancement.
* 1.11 Compatibility with customizer.
* 1.10 Tag cleanup and secure bug fix.
* 1.09 Added Secure Tags and Refresh Settings.
* 1.08 Fixed development bug.
* 1.07 Added Support for shortcodes.
* 1.06 Added Async Javascript support.
* 1.05 Added the ability to associate AdButler keywords with a given post.
* 1.04 Fixed widgets on customize appearance page.
* 1.03 Fixed some connection issues.
