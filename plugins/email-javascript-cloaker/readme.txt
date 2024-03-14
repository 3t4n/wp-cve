=== Email JavaScript Cloak ===
Contributors: cgarvey
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6137112
Tags: email address, email cloak, harvest, cloaking, spam
Requires at least: 3.5.0
Tested up to: 5.0
Stable tag: rel_1-03
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A simple plugin to use JavaScript to cloak email addresses in your WordPress content (posts & pages).

== Description ==
This plugin lets you use a shortcode to automatically generate 'cloaked' email addresses in your content.

*What is cloaking?*
Take an email address like barack@whitehouse.gov. If that was to appear on one of your posts/pages, it could easily be 'scraped' or 'harvested' automatically to add that email address to a spam list of some sort. Cloaking is all about making that harder to do.

*How does this plugin do its cloaking?*
For any email address you include in your content, using the custom shortcode of [email barack@whitehouse.gov], that email address will appear as "barack -at- whitehouse -dot- gov" in your content initially. JavaScript running in the browser will then convert that email address to a regular, clickable, email link. Whilst it may seem pointless to convert a regular email address to a strange format only to convert it back again, the idea is that most automated 'scrapers', or 'harvesters', do not run JavaScript and hence won't be able to pick up on the non-standard email address. The vast majority of users visiting your site will have JavaScript, and will see regular email addresses (not the strange format).

*What about users who have no JavaScript, or have it disabled?*
They will see the strange format ("barack -at- whitehouse -dot- gov"). You can include a footnote using a custom short code [emailnojs] which will explain the strange format, if you wish to cater for that tiny minority of visitors.

== Changelog ==
* Version 1.03 - Fix PHP callback warning (thanks to Simon Schaller).
* Version 1.02 - Confirm compatibility with WordPress v4.0.
* Version 1.01 - Remove debug code (affects earlier MSIE versions).
* Version 1.00 - Initial release.

== Installation ==

There are 2 ways of installation. If your setup supports it, you can search for the plugin, by name, within your WordPress control panel, and install it from there.
Alternatively, you download the .zip file, unzip it, and copy the resultant `email-js-cloak` folder to the `wp-content/plugins/` folder of your WordPress instaltion folder.

== Frequently Asked Questions ==
There are no FAQs at this time. Feel free to suggest some!

== Screenshots ==

1. Sample use of the [email] custom shortcode.
2. How use of the [email] custom shortcode appears on a regular JavaScript-enabled browser.
3. How use of the [email] custom shortcode appears on a browser with JavaScript disabled.

== Upgrade Notice ==
See Changelog for details.

== License ==
This plugin uses the GPLv3 license.
