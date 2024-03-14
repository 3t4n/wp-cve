=== NK Google Analytics ===
Contributors: quantumdev
Donate link: #
Tags: GA code, google analytics, analytics, tracking code, display advertising, remarketing analytics, universal analytics, Google Analytics in WordPress, WordPress Google Analytics, EU cookie law, fingerprint
Requires at least: 3
Tested up to: 5.5.3
Stable tag: 1.6.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

NK Google Analytics for Wordpress adds necessary javascript to enable Google Analytics tracking on your website.

== Description ==

Need Google Analytics on your website? NK Google Analytics adds the required javascript to track your website, it supports <b>Classic</b>, <b>Universal</b> and <b>Remarketing</b> Google Analytics tracking code.

For more information about Google Analytics visit:
[Google Analytics](http://www.google.com/analytics)

For more information about Display Advertising
[Support Display Advertising](https://support.google.com/analytics/answer/2444872?hl=en&utm_id=ad)

NK Google Analytics its simple, just add your Google Analytics ID and click "Save changes"


== Features ==

* Supports standard Google Analytics tracking via the latest async tracking methods (faster and more reliable than the older ga.js tracking method)
* Inserts your tracking code on all theme pages
* Inserts your tracking code into Head or Footer area
* Supports Classic Google Analytics tracking code
* Supports Universal Google Analytics tracking code
* Supports Display Advertising (Remarketing) tracking code
* Supports Custom Google Analytics tracking code
* No tracking for admin users logged-in
* Track login and register page if you want to
* Don't track logged users by role
* Supports cookieless tracking using fingerprint.js
* Can anonymize ip
* Other values, dimensions and metrics
* Options per page/post
* Google Tag Manager support

NK Google Analytics enables Google Analytics on all pages.


= Feedback / Review =

Please take the time to rate this plugin, let me and others to know about your experiences by leaving a review, with your help I can improve the plugin for you and other users.


Want to collaborate? Find this plugin on GitHub.


== Installation ==

If you don't have an Google Analytics ID, you need to go to [Google Analytics](http://www.google.com/analytics), create an account and get the code (Similar to UA-0000000-0)

1. Upload `nk-google-analytics` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the ID from Google Analytics (UA-0000000-0) to the settings (Admin > Settings > NK Google Analytics > Google Analytics ID field) and press "Save Changes"
4. Change other options like "Enable Remarketing" or "Universal Analytics" if you want


== Frequently Asked Questions ==

= Does NK Google Analytics supports classic Tracking code? =

Yes, it does.

= Does NK Google Analytics supports Universal Tracking code? =

Yes, it does.

= Does NK Google Analytics supports Display Advertising / Remarketing Tracking code? =

Yes, it does.

= Does NK Google Analytics supports Custom Tracking code? =

Yes, it does.

= The plugin should work as soon as the Google Analytics code is inserted? =

Yes.

= What kind of Google Analytics do I have? =

You should go to Google.com/Analytics and select your website profile. After select "Admin > Tracking Info" .

For Universal Analytics you will see this label: "Universal Analytics is enabled for this property.", for Classic Analytics you doesn't

= Is the plugin working? =

You can enter to real time panel into google/analytics and navigate into your website to test if the tracking code is working

= The plugin can enable Display Advertising for Classic and Universal Analytics? =

Yes, it does.

= The plugin can track login and register page? =

If the option is set to "Yes", Yes, it does.

= There is an option to select where the tracking code will be inserted in head of footer? =

Yes.

= There is an option to ignore users by role? =

Yes.

= What does "NK Google Analytics Status" option do? =

This option can "turn off" the plugin, if is set to "on" the plugin will print tracking code in the web page, if not, the plugin will not insert the tracking code.

= How accurate is cookieless tracking using fingerprint.js? =

It is ~90% accurate. The next version fingerprint2.js will be even better, but it is still in development at https://github.com/Valve/fingerprintjs2

== Screenshots ==
1. Modified settings panel with NK Google Analytics.
2. NK Google Analytics basic settings page.
3. NK Google Analytics EU Cookie Law support page.
4. NK Google Analytics more settings page.

== Changelog ==

= 1.6.2 =
* Bug Fix with EU Cookie Law vs. Global Site Tag https://wordpress.org/support/topic/eu-cookie-law-vs-global-site-tag-bug/#post-12289740

= 1.6.1 =
* Minor fix

= 1.6.0 =
* Updated to Google Tag
* Tested up Wordpress 5.3.1

= 1.5.0 =
* PHP compatibility fixes
* Tested up Wordpress 5.2.3

= 1.4.16 =
* Minor fixes
* Tested up Wordpress 4.9.7

= 1.4.15 =
* Minor fixes
* Clean some comments in code
* Tested up Wordpress 4.9.5

= 1.4.14 =
* Minor fixes
* Clean some comments in code

= 1.4.13 =
* Google Tag Manager better support
* Minor code fixes

= 1.4.12 =
* Minor code fixes

= 1.4.11 =
* Minor code fixes

= 1.4.10 =
* Added missing file
* Minor code fixes

= 1.4.9 =
* Added settings for other values, dimensions and metrics. Thank you to javitury (https://wordpress.org/support/profile/javitury)
* Added options per page/post
* Minor code fixes


= 1.4.8 =
* Added missing file
* Minor code fixes

= 1.4.7 =
* Added EU cookie law support (https://wordpress.org/support/topic/patchadd-eu-cookie-law-support). Thank you to javitury (https://wordpress.org/support/profile/javitury)
* Minor code fixes

= 1.4.6 =
* Fix CSS Conflict (https://wordpress.org/support/topic/admin-css-conflict?replies=1)

= 1.4.5 =
* Custom code bug fix

= 1.4.4 =
* Remove "free tools" links

= 1.4.3 =
* Minor fix, change in styles (https://wordpress.org/support/topic/avoid-css-collision-please)
* Some changes (https://wordpress.org/support/view/plugin-reviews/nk-google-analytics)

= 1.4.2 =
* Minor fix, removed some code (https://wordpress.org/support/topic/beware-12)

= 1.4.1 =
* Minor fix

= 1.4 =
* Improved interface
* Role level control for tracking code (https://wordpress.org/support/topic/dont-want-to-track-logged-in-admin-users, https://wordpress.org/support/topic/suggestion-non-include-code-as-administrator)

= 1.3.9 =
* Minor code fix (https://wordpress.org/support/topic/n-between-body-and-script-tag) 

= 1.3.8 =
* Minor code fixes

= 1.3.7 =
* Changes in menu
* Fixed HTTPS (https://wordpress.org/support/topic/ssl-option-load-scripts-from-secure-url)

= 1.3.6 =
* Changes in menu
* Added links for sumome

= 1.3.5 =
* Corrected a conflict with plugin "wpMandrill"

= 1.3.4 =
* Corrected a conflict with plugin "janrain social sharing"

= 1.3.3 =
* login and register page tracking fix

= 1.3.2 =
* Display Advertising "failover" fix (thanks to user j_shb)
* Changes to Enable demographics in Universal Analytics (thanks to user Levi_r)
* Added option to add tracking code to login and register page

= 1.3.1 =
* Fixes

= 1.3 =
* Added option to select tracking code location (head or end of page)
* Minor fixes

= 1.2.9 =
* Removed an error_log call, so the log file will not grow more
* Added an index.html file in plugin's directory to avoid to crawlers to index it

= 1.2.8 =
* Upgraded some validations and fixed some minor bugs

= 1.2.7 =
* Tracking issue fix with remarketing tracking code

= 1.2.6 =
* New on/off option.
* Retyped and reordered some option for improve reading and comprehension of options.

= 1.2.5 =
* Custom code fix.

= 1.2.4 =
* Update documentation and FAQ.
* Added Universal Analytics domain verification.

= 1.2.3 =
* Fix some minor bugs.

= 1.2.2 =
* Added support to custom Google Analytics tracking code.

= 1.2.1 =
* Google Analytics ID setting verification.

= 1.2 =
* Fix some minor bugs.
* Update documentation.

= 1.1 =
* Fix some bugs.

= 1.0 =
* First release.

== Upgrade Notice ==

= 1.0 =

Fisrt release.
