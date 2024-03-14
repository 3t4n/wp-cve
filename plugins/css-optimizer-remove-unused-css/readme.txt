=== Plugin Name ===
Contributors: wooncherk
Donate link: https://zippisite.com/
Tags: css, speed, autoptimize, critical css
Requires at least: 4.7
Tested up to: 5.9
Stable tag: 1.7
Requires PHP: 5.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Clean up and remove unused CSS from your website. Also generates Critical CSS to improve PageSpeed Score.

== Description ==

"Remove Unused CSS" is frequently flagged in Google's PageSpeed Insights. Indeed, most scenario, around 70% - 80% of a page's CSS codes are redundant, and can be removed without affecting the overall visual. This happens mainly because WordPress themes and plugins are created as generic solutions that includes a bunch of features that may or may not be used on a particular page. 

Example: A theme may come with a bunch of features such as *slider*, *carousel*, *animation* and etc, but your home page may use only the *slider* feature and your about page may use only the *carousel* feature. However, for practical reason, it's virtually impossible to selectively include only the required CSS rules for the features that were enabled on a particular page.

This is where the CSS Optimizer becomes useful. The plugin make calls to [ZippiSite](https://zippisite.com)'s CSS Optimizer API to find the required CSS rules on a particular page, and remove the ones that are not needed.

== Frequently Asked Questions ==

= Is the CSS Optimizer API free to use? =

It's free to use with a quota of 150 API hits per months. Note that API hits does not equals page views. In our tests on real world websites, 150 API calls is able to serve approximately 1,000 monthly page views. In other words, the Free Plan should suffice for small websites.

If you require more API quota, paid plans are available, starting from as cheap as $3 for 800 monthly API calls - approximately 5,300 monthly page views.

= What happens if there are styling issues after using this plugin? =

We provide free support for all plans - even free plans. We'll reply within 12 hours for paid plans, and within 24h for free plan.

= What this plugin really does? Will it add extra burden to my server? =

What this plugin does is to make calls to our API hosted on our server. The real heavy-lifting is done on our server, so you don't need to worry that the plugin will require extra resources.

== Screenshots ==

1. Remove Unused CSS to pass Google PageSpeed Insights (Core Web Vitals)
2. Settings page, the only place you need to configure.

== Changelog ==

= 1.7 =
* Integrates with W3 Total Cache
* Fix bug in job queue handling
* Add warning in case of high amount of failed API calls

= 1.6 =
* Integrates with WP-Optimize
* Fix bug where relative path is being incorrectly processed
* Fix compatibiliy issue with Autopimize
* Improve admin dashboard notification
* Add FAQ section

= 1.5 =
* Integrats with SG Optimizer.
* Improved compatibility.
* Minor bug fixes 

= 1.4 =
* Integrates with WP Fastest Cache and WP Rocket. More integration coming soon!
* Improvements in the general workflow.
* Minor bug fixes.

= 1.3 =
* Remove dependency on Auoptimize. The plugin can now work with or without Autoptimize.

= 1.2 =
* Add Job Queue for easier management.

= 1.1 =
* Add support for generating Critical CSS.

= 1.0 =
* First version. More updates coming soon!